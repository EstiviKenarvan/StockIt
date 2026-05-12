<?php
require_once __DIR__ . '/../models/ClientesModel.php';

class ClientesController {
    private $modelo;

    public function __construct($conexion) {
        $this->modelo = new ClientesModel($conexion);
    }

    // ── Lista todos los clientes ──────────────────────────────
    public function index(): void {
        $clientes = $this->modelo->consultar();
        include 'views/Clientes.php';
    }

    // ── Crear cliente ─────────────────────────────────────────
    public function crear(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->modelo->insertar(
                $_POST['tipoCliente']    ?? '',
                $_POST['cliente_nombre'] ?? '',
                $_POST['telefono']       ?? '',
                $_POST['email']          ?? '',
                $_POST['credito']        ?? 0,
                $_POST['estado']         ?? 'Activo',
                $_POST['notas']          ?? '',
                0,
                date('Y-m-d H:i:s'),
                '0'
            );
            header("Location: index.php?menu=clientes&exito=1");
            exit;
        }
        include 'views/agregarcliente.php';
    }

    // ── Editar cliente ────────────────────────────────────────
    public function editar(int $id): void {
        $cliente = $this->modelo->consultarPorId($id);
        if (!$cliente) {
            header("Location: index.php?menu=clientes");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->modelo->actualizar(
                $id,
                $_POST['tipoCliente'] ?? '',
                $_POST['telefono']    ?? '',
                $_POST['email']       ?? '',
                $_POST['credito']     ?? 0,
                $_POST['estado']      ?? 'Activo',
                $_POST['notas']       ?? '',
                $cliente['TotalCompras'],
                $cliente['fechaPago'],
                $cliente['TotalCredito']
            );
            header("Location: index.php?menu=clientes&exito=2");
            exit;
        }

        include 'views/agregarcliente.php';
    }

    // ── Borrar cliente ────────────────────────────────────────
    public function borrar(int $id): void {
        if ($id) $this->modelo->eliminar($id);
        header("Location: index.php?menu=clientes&eliminado=1");
        exit;
    }
}
?>