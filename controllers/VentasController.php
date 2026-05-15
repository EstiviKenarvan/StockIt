<?php
require_once __DIR__ . '/../models/VentasModel.php';

class VentasController {
    private $modelo;

    public function __construct($conexion) {
        $this->modelo = new VentasModel($conexion);
    }

    /* ── Vista principal ─────────────────────────────────── */
    public function index(): void {
        $ventasHoy    = $this->modelo->ventasDeHoy();
        $totalHoy     = $this->modelo->totalHoy();
        $devoluciones = $this->modelo->consultarDevoluciones();
        include 'views/ventas.php';
    }

    /* ── AJAX: buscar productos ──────────────────────────── */
    public function buscarProductos(): void {
        header('Content-Type: application/json');
        $q       = trim($_GET['q']       ?? '');
        $barcode = trim($_GET['barcode'] ?? '');

        if ($barcode) {
            echo json_encode($this->modelo->buscarPorBarcode($barcode));
        } elseif (strlen($q) >= 1) {
            echo json_encode($this->modelo->buscarProductos($q));
        } else {
            echo json_encode([]);
        }
        exit;
    }

    /* ── AJAX: procesar venta ────────────────────────────── */
    public function procesarVenta(): void {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        $carrito    = $data['carrito']      ?? [];
        $tipoMetodo = (int)($data['idMetodoPago'] ?? 0); // 1=efectivo, 2=tarjeta
        $folio      = trim($data['folio']   ?? '');

        if (empty($carrito) || !$tipoMetodo) {
            echo json_encode(['ok' => false, 'msg' => 'Datos incompletos']);
            exit;
        }

        $total = array_sum(array_map(fn($p) => $p['precio'] * $p['cantidad'], $carrito));

        try {
            $idMetodoPago = $this->modelo->insertarMetodoPago($total, $tipoMetodo);

            $primero = $carrito[0];
            $idModo  = $this->modelo->insertarModoVenta(null, $primero['id'], $primero['cantidad']);

            $idVenta = $this->modelo->insertar(
                null,
                $idMetodoPago,
                $idModo,
                $total,
                $tipoMetodo,
                $folio ?: null
            );

            foreach ($carrito as $p) {
                $this->modelo->insertarDetalle($idVenta, $p['id'], $p['precio'] * $p['cantidad'], $p['cantidad']);
                $this->modelo->descontarStock($p['id'], $p['cantidad']);
            }

            echo json_encode(['ok' => true, 'idVenta' => $idVenta, 'total' => $total]);
        } catch (Exception $e) {
            echo json_encode(['ok' => false, 'msg' => $e->getMessage()]);
        }
        exit;
    }

    /* ── AJAX: detalle de venta ──────────────────────────── */
    public function detalleVenta(): void {
        header('Content-Type: application/json');
        $id = (int)($_GET['id'] ?? 0);
        echo json_encode($id ? $this->modelo->consultarDetalle($id) : []);
        exit;
    }

    /* ── AJAX: registrar devolución ──────────────────────── */
    public function registrarDevolucion(): void {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        $idProducto  = $data['idProducto']  ?? null;
        $motivo      = $data['motivo']       ?? '';
        $descripcion = $data['descripcion']  ?? '';
        $cantidad    = (int)($data['cantidad'] ?? 1);
        $idVenta     = $data['idVenta']      ?? null;

        if (!$idProducto || !$motivo) {
            echo json_encode(['ok' => false, 'msg' => 'Faltan datos']);
            exit;
        }

        $ok = $this->modelo->insertarDevolucion($idVenta, $idProducto, null, $motivo, $descripcion, $cantidad);
        echo json_encode(['ok' => (bool)$ok]);
        exit;
    }

    /* ── AJAX: datos dashboard ───────────────────────────── */
    public function datosDashboard(): void {
        header('Content-Type: application/json');
        $periodo = $_GET['periodo'] ?? 'semana';

        switch ($periodo) {
            case 'dia':
                $whereVentas = "DATE(fechaHora) = CURDATE()";
                $whereInv    = "DATE(fechaEntrada) = CURDATE()";
                $formato     = '%H:00';
                break;
            case 'mes':
                $whereVentas = "fechaHora >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
                $whereInv    = "fechaEntrada >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
                $formato     = '%d %b';
                break;
            default: // semana
                $whereVentas = "fechaHora >= DATE_SUB(NOW(), INTERVAL 6 DAY)";
                $whereInv    = "fechaEntrada >= DATE_SUB(NOW(), INTERVAL 6 DAY)";
                $formato     = '%a';
        }

        echo json_encode([
            'ventasPeriodo'   => $this->modelo->ventasPorPeriodo($whereVentas, $formato),
            'entradasPeriodo' => $this->modelo->entradasPorPeriodo($whereInv, $formato),
            'salidasPeriodo'  => $this->modelo->salidasPorPeriodo($whereVentas, $formato),
            'masVendidos'     => $this->modelo->masVendidos(6),
            'menosVendidos'   => $this->modelo->menosVendidos(6),
        ]);
        exit;
    }
}
?>