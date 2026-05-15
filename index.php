<?php
session_start();

$menu    = $_GET['menu']    ?? 'login';
$submenu = $_GET['submenu'] ?? 'index';

// ── Páginas públicas (sin BD) ─────────────────────────────
if ($menu == 'login') {
    require_once 'views/iniciosec.php';
    exit;
}
if ($menu == 'registro') {
    require_once 'views/register.php';
    exit;
}
if ($menu == 'olvide') {
    require_once 'views/contrasenaolv.php';
    exit;
}
if ($menu == 'recuperar') {
    require_once 'views/recoverpass.php';
    exit;
}

// ── Requiere BD ───────────────────────────────────────────
require_once 'config/database.php';
$db = new Database();
$conexion = $db->getConnection();

require_once 'functions.php';
require_once 'controllers/ClientesController.php';
require_once 'controllers/ProductosController.php';
require_once 'controllers/UsuarioController.php';
require_once 'controllers/VentasController.php';

// ── Router ────────────────────────────────────────────────
if ($menu == 'productos') {
    $productosController = new ProductosController($conexion);
    if ($submenu == 'registro') {
        $productosController->crear();
    } elseif ($submenu == 'editar') {
        $id = (int)($_GET['id'] ?? 0);
        $productosController->editar($id);
    } elseif ($submenu == 'borrar') {
        $id = (int)($_GET['id'] ?? 0);
        $productosController->borrar($id);
    } else {
        $productosController->index();
    }

} elseif ($menu == 'analisisproductos') {
    if ($submenu == 'menosvendidos')
        require_once 'views/productos_menos_vendidos.php';
    else
        require_once 'views/analisisProductos.php';

} elseif ($menu == 'clientes') {
    $clientecontroller = new ClientesController($conexion);
    if ($submenu == 'registro') {
        $clientecontroller->crear();
    } elseif ($submenu == 'editar') {
        $id = (int)($_GET['id'] ?? 0);
        $clientecontroller->editar($id);
    } elseif ($submenu == 'borrar') {
        $id = (int)($_GET['id'] ?? 0);
        $clientecontroller->borrar($id);
    } else {
        $clientecontroller->index();
    }

} elseif ($menu == 'inventario') {
    require_once 'controllers/InventarioController.php';
    $ctrl = new InventarioController($conexion);
    if ($submenu == 'registro')
        $ctrl->crear();
    elseif ($submenu == 'borrar')
        $ctrl->borrar((int)($_GET['id'] ?? 0));
    else
        $ctrl->index();

} elseif ($menu == 'proveedores') {
    if ($submenu == 'registro')
        require_once 'views/RegistrarProveedor.php';
    else
        require_once 'views/proveedores.php';

} elseif ($menu == 'reportes') {
    require_once __DIR__ . '/controllers/ReportesController.php';
    $submenu = $_GET['submenu'] ?? '';   // ← sin esto el match() falla silencioso
    $ctrl = new ReportesController($conexion);

    match($submenu) {
        'datos'   => $ctrl->datos(),
        'guardar' => $ctrl->guardar(),
        'borrar'  => $ctrl->borrar((int)($_GET['id'] ?? 0)),
        default   => $ctrl->index(),
    };
} elseif ($menu == 'ventas') {
    $ventasController = new VentasController($conexion);
    if ($submenu == 'buscar-productos') {
        $ventasController->buscarProductos();
    } elseif ($submenu == 'procesar-venta') {
        $ventasController->procesarVenta();
    } elseif ($submenu == 'detalle-venta') {
        $ventasController->detalleVenta();
    } elseif ($submenu == 'registrar-devolucion') {
        $ventasController->registrarDevolucion();
    } elseif ($submenu == 'datos-dashboard') {
        $ventasController->datosDashboard();
    } else {
        $ventasController->index();
    }

} elseif ($menu == 'usuarios') {
    $UsuarioController = new UsuarioController($conexion);
    if ($submenu == 'registro') {
        $UsuarioController->crear();
    } elseif ($submenu == 'editar') {
        $id = (int)($_GET['id'] ?? 0);
        $UsuarioController->editar($id);
    } elseif ($submenu == 'borrar') {
        $id = (int)($_GET['id'] ?? 0);
        $UsuarioController->borrar($id);
    } else {
        $UsuarioController->index();
    }

} elseif ($menu == 'dashboard') {
    require_once 'views/dashboard_view.php';

} elseif ($menu == 'respaldo') {
    require_once 'views/respaldo.php';

} else {
    http_response_code(404);
    echo "<h1>404 - Página no encontrada</h1>";
}
?>