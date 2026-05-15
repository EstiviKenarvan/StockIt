<?php
require_once __DIR__ . '/../models/ReportesModel.php';

class ReportesController {
    private $modelo;

    public function __construct($conexion) {
        $this->modelo = new ReportesModel($conexion);
    }

    // ── Vista principal ───────────────────────────────────
    public function index(): void {
        $reportes = $this->modelo->consultar();
        include 'views/modulo_reportes.php';
    }

    // ── AJAX: obtener datos para generar PDF ──────────────
    public function datos(): void {
        header('Content-Type: application/json');
        $tipo       = $_GET['tipo']        ?? 'general';
        $fechaInicio = $_GET['fechaInicio'] ?? date('Y-m-01');
        $fechaFin    = $_GET['fechaFin']    ?? date('Y-m-d');

        switch ($tipo) {
            case 'disponibilidad':
                echo json_encode(['ok' => true, 'data' => $this->modelo->datosDisponibilidad()]);
                break;
            case 'caducidades':
                echo json_encode(['ok' => true, 'data' => $this->modelo->datosCaducidades()]);
                break;
            case 'ventas':
                echo json_encode(['ok' => true, 'data' => $this->modelo->datosVentas($fechaInicio, $fechaFin)]);
                break;
            case 'movimientos':
                echo json_encode(['ok' => true, 'data' => $this->modelo->datosMovimientos($fechaInicio, $fechaFin)]);
                break;
            default: // general
                echo json_encode(['ok' => true, 'data' => $this->modelo->datosGeneral()]);
        }
        exit;
    }

    // ── AJAX: guardar reporte en BD ───────────────────────
    public function guardar(): void {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        echo json_encode(['ok' => false, 'error' => 'JSON inválido']);
        exit;
    }

    // ── DEBUG: ver qué hay en sesión ──────────────────────
    $debugSesion = array_keys($_SESSION); // solo las claves, no los valores
    
    // Prueba las claves más comunes hasta encontrar la tuya
    $idUsuario = $_SESSION['usuario_id']   // opción A
              ?? $_SESSION['idUsuarios']   // opción B
              ?? $_SESSION['id']           // opción C
              ?? $_SESSION['user_id']      // opción D
              ?? null;

    $ok = $this->modelo->insertar(
        $data['tipoReporte']   ?? '',
        $data['filtros']       ?? '',
        $data['peridodTiempo'] ?? date('Y-m-d H:i:s'),
        $data['idProducto']    ?? null,
        $data['formato']       ?? 'PDF',
        $idUsuario
    );

    // Agrega error de BD si falla
    echo json_encode([
        'ok'           => (bool)$ok,
        'idUsuario'    => $idUsuario,       // ← debe tener valor
        'clavesSesion' => $debugSesion,     // ← te dice qué claves existen
    ]);
    exit;
}

    // ── Eliminar reporte ──────────────────────────────────
    public function borrar(int $id): void {
        if ($id) $this->modelo->eliminar($id);
        header("Location: index.php?menu=reportes&eliminado=1");
        exit;
    }
}
?>