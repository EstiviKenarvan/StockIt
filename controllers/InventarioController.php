<?php
require_once 'models/InventarioModel.php';

class InventarioController {
    private $modelo;

    public function __construct($conexion) {
        $this->modelo = new InventarioModel($conexion);
    }

    public function index(): void {
    try {
        $movimientos      = $this->modelo->consultar();
        
        // Ajustamos los nombres para que coincidan con la vista
        $valorInvertidoHoy = $this->modelo->totalEntradasHoy(); // Cambiado de $entradasHoy
        $stockTotal        = $this->modelo->stockTotal();
        $stockBajo         = $this->modelo->productosStockBajo();
        
        // Agregamos el cálculo para "Sin Stock" que falta en tu model
        // Puedes usar una consulta rápida o agregar el método al Model
        $sinStock          = $this->modelo->contarSinStock(); 

        $productos         = $this->modelo->consultarProductos();
        $proveedores       = $this->modelo->consultarProveedores();

        $viewPath = 'views/analisisProd.php';
        include $viewPath;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

   public function crear(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $idProducto      = $_POST['idProducto']     ?? null;
        $costoNuevo      = $_POST['costo']          ?? 0;
        $actualizarPrecio = $_POST['actualizarPrecio'] ?? '0';

        $this->modelo->insertar(
            $idProducto,
            $_POST['idProveedor']    ?? null,
            $_POST['cantidad']       ?? 0,
            $_POST['unidad']         ?? 'Piezas',
            $costoNuevo,
            $_POST['factura']        ?? '',
            $_POST['fechaEntrada']   ?? date('Y-m-d'),
            $_POST['fechaCaducidad'] ?? null,
            $_POST['Lote']           ?? '',
            $_POST['Observaciones']  ?? ''
        );

        // Si el dueño confirmó actualizar el precio de compra
        if ($idProducto && $actualizarPrecio === '1') {
            $this->modelo->actualizarPrecioCompra($idProducto, $costoNuevo);
        }

        header("Location: index.php?menu=inventario&exito=1");
        exit;
    }
    header("Location: index.php?menu=inventario");
    exit;
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