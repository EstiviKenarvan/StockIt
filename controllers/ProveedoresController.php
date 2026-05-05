<?php
require_once 'models/ProveedoresModel.php';

class ProveedoresController {
    private $modelo;

    public function __construct($conexion) {
        $this->modelo = new ProveedoresModel($conexion);
    }

    public function index(): void {
        try {
            $proveedores = $this->modelo->consultar();

            $viewPath = 'views/proveedores.php';
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
                $_POST['nombreProveedor']  ?? '',
                $_POST['telefono']         ?? '',
                $_POST['email']            ?? '',
                $_POST['frecuenciaPedido'] ?? '',
                $_POST['diasEntregas']     ?? 0,
                $_POST['diaVisita']        ?? '',
                $_POST['estado']           ?? 'Activo'
            );
            header("Location: index.php?menu=proveedores");
            exit;
        }
    }

    public function editar(int $id): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->modelo->actualizar(
                $id,
                $_POST['nombreProveedor']  ?? '',
                $_POST['telefono']         ?? '',
                $_POST['email']            ?? '',
                $_POST['frecuenciaPedido'] ?? '',
                $_POST['diasEntregas']     ?? 0,
                $_POST['diaVisita']        ?? '',
                $_POST['estado']           ?? 'Activo'
            );
            header("Location: index.php?menu=proveedores");
            exit;
        }

        $proveedor = $this->modelo->consultarPorId($id);
        if (!$proveedor) {
            header("Location: index.php?menu=proveedores");
            exit;
        }
        include 'views/RegistrarProveedor.php';
    }

    public function borrar(int $id): void {
        if ($id) {
            $this->modelo->eliminar($id);
        }
        header("Location: index.php?menu=proveedores");
        exit;
    }
}
?>