<?php
require_once 'models/InventarioModel.php';

class InventarioController {
    private $modelo;

    public function __construct($conexion) {
        $this->modelo = new InventarioModel($conexion);
    }

    public function index(): void {
        try {
            $movimientos = $this->modelo->consultar();

            $viewPath = 'views/analisisProd.php';
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
                $_POST['idProducto']    ?? null,
                $_POST['idProveedor']   ?? null,
                $_POST['cantidad']      ?? 0,
                $_POST['unidad']        ?? '',
                $_POST['costo']         ?? 0,
                $_POST['factura']       ?? 0,
                $_POST['fechaEntrada']  ?? date('Y-m-d H:i:s'),
                $_POST['fechaCaducidad'] ?? null,
                $_POST['Lote']          ?? '',
                $_POST['Observaciones'] ?? ''
            );
            header("Location: index.php?menu=inventario");
            exit;
        }
    }

    public function borrar(int $id): void {
        if ($id) {
            $this->modelo->eliminar($id);
        }
        header("Location: index.php?menu=inventario");
        exit;
    }
}
?>