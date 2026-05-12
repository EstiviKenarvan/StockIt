<?php
require_once 'models/ProductosModel.php';
class ProductosController {
    private $modelo;
    public function __construct($conexion) {
        $this->modelo = new ProductosModel($conexion);
    }
    public function index(): void {
        try {
            $productos  = $this->modelo->consultar();
            $categorias = $this->modelo->consultarCategorias();
            $viewPath = 'views/GestiondeProductos.php';
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
                $_POST['codigoBarras']   ?? '',
                $_POST['nombreProducto'] ?? '',
                $_POST['precioCompra']   ?? 0,
                $_POST['precioVenta']    ?? 0,
                $_POST['stockEnGeneral'] ?? 0,
                $_POST['idCategoria']    ?? null,
                $_POST['idProveedor']    ?? null,
                $_POST['fechaCaducidad'] ?? null,
                $_POST['estado']         ?? 'Disponible',
                $_POST['notas']          ?? ''
            );
            header("Location: index.php?menu=productos");
            exit;
        }
        $productos   = $this->modelo->consultar();
        $proveedores = $this->modelo->consultarProveedores();
        $categorias  = $this->modelo->consultarCategorias();
        include "views/GestiondeProductos.php";
    }
    public function editar(int $id): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->modelo->actualizar(
                $id,
                $_POST['codigoBarras']   ?? '',
                $_POST['nombreProducto'] ?? '',
                $_POST['precioCompra']   ?? 0,
                $_POST['precioVenta']    ?? 0,
                $_POST['stockEnGeneral'] ?? 0,
                $_POST['idCategoria']    ?? null,
                $_POST['idProveedor']    ?? null,
                $_POST['fechaCaducidad'] ?? null,
                $_POST['estado']         ?? 'Disponible',
                $_POST['notas']          ?? ''
            );
            header("Location: index.php?menu=productos");
            exit;
        }
        $producto = $this->modelo->consultarPorId($id);
        if (!$producto) {
            header("Location: index.php?menu=productos");
            exit;
        }
        $productos  = $this->modelo->consultar();
        $categorias = $this->modelo->consultarCategorias();
        include 'views/GestiondeProductos.php';
    }
    public function borrar(int $id): void {
        if ($id) {
            $this->modelo->eliminar($id);
        }
        header("Location: index.php?menu=productos");
        exit;
    }
}
?>