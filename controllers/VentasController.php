<?php
require_once __DIR__ . '/../models/VentasModel.php';

class VentasController
{
    private $modelo;

    public function __construct($conexion)
    {
        $this->modelo = new VentasModel($conexion);
    }

    /* ── Vista principal con tabs ────────────────────────── */
    public function index(): void
    {
        $ventasHoy    = $this->modelo->ventasDeHoy();
        $totalHoy     = $this->modelo->totalHoy();
        $devoluciones = $this->modelo->consultarDevoluciones();
        include 'views/ventas.php';
    }

    /* ── AJAX: buscar productos para el carrito ──────────── */
   public function buscarProductos(): void
{
    header('Content-Type: application/json');
    $q       = trim($_GET['q']       ?? '');
    $barcode = trim($_GET['barcode'] ?? '');

    if ($barcode !== '') {
        echo json_encode($this->modelo->buscarProductos('', $barcode));
    } elseif (strlen($q) >= 2) {
        echo json_encode($this->modelo->buscarProductos($q));
    } else {
        echo json_encode([]);
    }
    exit;
}

    /* ── AJAX: procesar venta completa ───────────────────── */
    public function procesarVenta(): void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        $carrito        = $data['carrito']      ?? [];
        $idMetodoPago   = $data['idMetodoPago'] ?? null;
        $primerProducto = $carrito[0];
        $idModo         = $this->modelo->insertarModoVenta(null, $primerProducto['id'], $primerProducto['cantidad']);
        $idCliente      = null;

        if (empty($carrito) || !$idMetodoPago) {
            echo json_encode(['ok' => false, 'msg' => 'Datos incompletos']);
            exit;
        }

        $total = array_sum(array_map(fn($p) => $p['precio'] * $p['cantidad'], $carrito));

        try {
            $idRealMetodo = $this->modelo->insertarMetodoPago($total, $idMetodoPago);
            $idVenta      = $this->modelo->insertar($idCliente, $idRealMetodo, $idModo, $total);

            foreach ($carrito as $p) {
                $this->modelo->insertarDetalle($idVenta, $p['id'], $p['precio'] * $p['cantidad']);
                $this->modelo->descontarStock($p['id'], $p['cantidad']);
            }

            // ── Generar alertas tras la venta ──────────────────────────
            $_SESSION['alertas'] = $this->modelo->consultarAlertas();
            // ──────────────────────────────────────────────────────────

            echo json_encode(['ok' => true, 'idVenta' => $idVenta, 'total' => $total]);
        } catch (Exception $e) {
            echo json_encode(['ok' => false, 'msg' => $e->getMessage()]);
        }
        exit;
    }

    /* ── AJAX: detalle de una venta (para modal corte) ──── */
    public function detalleVenta(): void
    {
        header('Content-Type: application/json');
        $id = (int)($_GET['id'] ?? 0);
        echo json_encode($id ? $this->modelo->consultarDetalle($id) : []);
        exit;
    }

    /* ── AJAX: registrar devolución ──────────────────────── */
    public function registrarDevolucion(): void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        $idVenta     = $data['idVenta']     ?? null;
        $idProducto  = $data['idProducto']  ?? null;
        $motivo      = $data['motivo']      ?? '';
        $descripcion = $data['descripcion'] ?? '';
        $cantidad    = $data['cantidad']    ?? 1;

        if (!$idProducto || !$motivo) {
            echo json_encode(['ok' => false, 'msg' => 'Faltan datos']);
            exit;
        }

        $ok = $this->modelo->insertarDevolucion($idVenta, $idProducto, null, $motivo, $descripcion, $cantidad);
        echo json_encode(['ok' => (bool)$ok]);
        exit;
    }
}
