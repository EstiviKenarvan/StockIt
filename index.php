<?php

$menu    = $_GET['menu']    ?? 'productos';
$submenu = $_GET['submenu'] ?? 'index';

require_once 'config/database.php';
$db = new Database();
$conexion = $db->getConnection();

require_once 'functions.php';
require_once 'controllers/ClientesController.php';
require_once 'controllers/ProductosController.php';

if ($menu == 'productos') {
    $productosController = new ProductosController($conexion);
    if ($submenu == 'registro') {
        $productosController->crear();      // ← solo este cambio
    } else if ($submenu == 'editar') {
        $id = (int)($_GET['id'] ?? 0);
        $productosController->editar($id);
    } else if ($submenu == 'borrar') {
        $id = (int)($_GET['id'] ?? 0);
        $productosController->borrar($id);
    } else {
        $productosController->index();
    }

} else if ($menu == 'analisisproductos') {
    if ($submenu == 'menosvendidos')
        require_once 'views/productos_menos_vendidos.php';
    else
        require_once 'views/analisisProductos.php';

} else if ($menu == 'clientes') {
    $clientecontroller = new ClientesController($conexion);
    if ($submenu == 'registro') {
        require_once 'views/agregarcliente.php';
    } else {
        $clientecontroller->index(); // ya incluye la vista internamente
    }

} else if ($menu == 'inventario') {
    if ($submenu == 'registro')
        require_once 'views/registro_entrada.php';
    else
        require_once 'views/analisisProd.php';

} else if ($menu == 'proveedores') {
    if ($submenu == 'registro')
        require_once 'views/RegistrarProveedor.php';
    else
        require_once 'views/proveedores.php';

} else if ($menu == 'reportes') {
    if ($submenu == 'generar')
        require_once 'views/generar_nuevo_reporte.php';
    else
        require_once 'views/modulo_reportes.php';

} else if ($menu == 'ventas') {
    require_once 'controllers/VentasController.php';
    $ventasController = new VentasController($conexion);

    if ($submenu == 'buscar-productos') {
        $ventasController->buscarProductos();

    } elseif ($submenu == 'procesar-venta') {
        $ventasController->procesarVenta();

    } elseif ($submenu == 'detalle-venta') {
        $ventasController->detalleVenta();

    } elseif ($submenu == 'registrar-devolucion') {
        $ventasController->registrarDevolucion();

    } else {
        $ventasController->index();
    }
}
?>