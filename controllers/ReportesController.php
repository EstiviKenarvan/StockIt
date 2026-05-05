<?php
require_once 'models/ReportesModel.php';

class ReportesController {
    private $modelo;

    public function __construct($conexion) {
        $this->modelo = new ReportesModel($conexion);
    }

    public function index(): void {
        try {
            $reportes = $this->modelo->consultar();

            $viewPath = 'views/modulo_reportes.php';
            if (!file_exists($viewPath)) {
                throw new Exception("La vista '$viewPath' no se encuentra en el servidor.");
            }
            include $viewPath;

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function crear(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->modelo->insertar(
                $_POST['tipoReporte']   ?? '',
                $_POST['filtros']       ?? '',
                $_POST['peridodTiempo'] ?? date('Y-m-d H:i:s'),
                $_POST['idProducto']    ?? null
            );
            header("Location: index.php?menu=reportes");
            exit;
        }
    }

    public function borrar(int $id): void {
        if ($id) {
            $this->modelo->eliminar($id);
        }
        header("Location: index.php?menu=reportes");
        exit;
    }
}
?>