<?php
require_once 'models/ClientesModel.php';

class ClientesController {
    private $modelo;
    
    public function __construct($conexion) {
        $this->modelo = new ClientesModel($conexion);
    }

    // ── Lista todos los clientes ──────────────────────────────
    public function index(): void {
        try {
            $clientes = $this->modelo->consultar();

            $viewPath = 'views/Clientes.php';
            if (!file_exists($viewPath)) {
                throw new Exception("La vista '$viewPath' no se encuentra en el servidor.");
            }
            include $viewPath;

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // ── Crear cliente ─────────────────────────────────────────
    public function crear(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->modelo->insertar(
                $_POST['tipoCliente']   ?? '',
                $_POST['cliente_nombre'] ?? '',
                $_POST['credito']       ?? 0,
                $_POST['estado']        ?? 'Activo',
                $_POST['notas']         ?? '',
                $_POST['TotalCompras']  ?? 0,
                $_POST['fechaPago']     ?? date('Y-m-d H:i:s'),
                $_POST['TotalCredito']  ?? '0'
            );
            header("Location: index.php?menu=clientes");
            exit;
        }

        // GET: mostrar formulario
        include 'views/agregarcliente.php';
    }

    // ── Editar cliente ────────────────────────────────────────
    public function editar(int $id): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->modelo->actualizar(
                $id,
                $_POST['tipoCliente']  ?? '',
                $_POST['credito']      ?? 0,
                $_POST['estado']       ?? 'Activo',
                $_POST['notas']        ?? '',
                $_POST['TotalCompras'] ?? 0,
                $_POST['fechaPago']    ?? date('Y-m-d H:i:s'),
                $_POST['TotalCredito'] ?? '0'
            );
            header("Location: index.php?menu=clientes");
            exit;
        }

        $cliente = $this->modelo->consultarPorId($id);
        if (!$cliente) {
            header("Location: index.php?menu=clientes");
            exit;
        }
        include 'views/agregarcliente.php';
    }

    // ── Borrar cliente ────────────────────────────────────────
    public function borrar(int $id): void {
        if ($id) {
            $this->modelo->eliminar($id);
        }
        header("Location: index.php?menu=clientes");
        exit;
    }
}
?>