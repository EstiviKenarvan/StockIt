<?php
function crearCardProveedor($nombre, $tel, $tags, $status, $fecha, $bordeColor = "#28A745")
{
    $statusStyle = ($status == "Activo")
        ? "background-color:#D4EDDA; color:#155724;"
        : (($status == "Reabastecer urgente")
            ? "background-color:#F8D7DA; color:#721C24;"
            : "background-color:#e9ecef; color:#6c757d;");

    $tagsHtml = '';
    foreach ($tags as $tag) {
        $tagsHtml .= "<span class='badge p-2 mr-1' style='background-color:#FFE0B2; color:#C06000; font-weight:normal; border-radius:8px;'>{$tag}</span>";
    }

    return "
    <div class='col-md-4 mb-4'>
        <div class='card' style='border-top:4px solid {$bordeColor}; border-radius:12px; padding:20px; background:white;'>
            <div class='d-flex align-items-center mb-2'>
                <i class='fas fa-store mr-2' style='color:#6c757d;'></i>
                <h5 class='m-0 font-weight-bold'>{$nombre}</h5>
            </div>
            <p class='text-muted small mb-3'><i class='fas fa-phone-alt mr-1'></i> {$tel}</p>
            <div class='mb-3'>{$tagsHtml}</div>
            <div class='mb-3'>
                <span class='badge p-1 px-3' style='{$statusStyle} border-radius:5px;'>{$status}</span>
            </div>
            <div class='d-flex justify-content-between align-items-center mt-2'>
                <small class='text-muted'>Último pedido: {$fecha}</small>
                <button class='btn btn-sm' style='background-color:#E8820C; color:white; border-radius:8px; padding:5px 15px;'>Ver detalle</button>
            </div>
        </div>
    </div>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stock It | Proveedores</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="public/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="public/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="public/plugins/jqvmap/jqvmap.min.css">
    <link rel="stylesheet" href="public/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="public/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="public/plugins/summernote/summernote-bs4.min.css">
    <style>
        .main-header.navbar, .main-header.navbar-white, .main-header.navbar-light, nav.main-header { background-color:#E8820C !important; border-bottom:none !important; }
        .main-header .nav-link, .main-header .nav-link i, .main-header .navbar-nav .nav-item a, .navbar-light .navbar-nav .nav-link { color:#ffffff !important; }
        .main-sidebar, .main-sidebar:before, .brand-link, .sidebar-dark-primary { background-color:#E8820C !important; }
        .nav-sidebar .nav-link, .brand-link .brand-text, .sidebar .user-panel .info a, .nav-sidebar .nav-link i { color:#ffffff !important; }
        .form-control-sidebar, .btn-sidebar { background-color:#ffffff !important; border:1px solid #ddd !important; color:#333 !important; }
        .btn-sidebar i { color:#333 !important; }
        .user-panel, .form-inline { border-bottom:1px solid rgba(255,255,255,0.2) !important; }
        .content-wrapper { background-color:#FFF5E5 !important; }
        .titulo-naranja { color:#E8820C; font-weight:bold; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="public/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
    </div>
<?php
$paginaActiva = "proveedores"; // o clientes, reportes, ventas, etc.
include __DIR__ . "/includes/barras.php";
    ?>

    <!-- CONTENIDO -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-4">
                    <div class="col-md-6">
                        <h1 class="m-0">Módulo de <span class="titulo-naranja">Proveedores</span></h1>
                        <p class="text-muted">Seguimiento de proveedores</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <div class="position-relative mr-3" style="width:250px;">
                                <i class="fas fa-search position-absolute" style="left:10px; top:10px; color:#ccc;"></i>
                                <input type="text" class="form-control pl-5" placeholder="Buscar proveedor..." style="border-radius:8px;">
                            </div>
                            <a href="?menu=proveedores&submenu=registro" class="btn" style="background-color:#E8820C; color:white; border-radius:8px; font-weight:bold;">
                                <i class="fas fa-plus mr-1"></i> Registrar proveedor
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tarjetas resumen -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card p-3" style="border-top:5px solid #E8820C; border-radius:10px;">
                            <small class="text-muted font-weight-bold">Total proveedores</small>
                            <h2 class="titulo-naranja mb-1">12</h2>
                            <div class="p-1 px-2" style="background-color:#FFF5E5; border-radius:5px; font-size:0.8rem;">Registrados</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3" style="border-top:5px solid #28A745; border-radius:10px;">
                            <small class="text-muted font-weight-bold">Activos</small>
                            <h2 class="text-success mb-1">9</h2>
                            <div class="p-1 px-2" style="background-color:#E8F5E9; border-radius:5px; font-size:0.8rem;">En servicio</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3" style="border-top:5px solid #007BFF; border-radius:10px;">
                            <small class="text-muted font-weight-bold">Productos asociados</small>
                            <h2 class="text-primary mb-1">148</h2>
                            <div class="p-1 px-2" style="background-color:#E7F1FF; border-radius:5px; font-size:0.8rem;">Productos</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3" style="border-top:5px solid #C0392B; border-radius:10px;">
                            <small class="text-muted font-weight-bold">Reabastecer</small>
                            <h2 class="text-danger mb-1">5</h2>
                            <div class="p-1 px-2" style="background-color:#FDEDEC; border-radius:5px; font-size:0.8rem;">Urgentes</div>
                        </div>
                    </div>
                </div>

                <!-- Tarjetas de proveedores -->
                <div class="row">
                    <?php
                    echo crearCardProveedor("Marinela",        "55-1234-5678", ["Gansitos","Submarinos"],   "Activo",              "28 Feb", "#E8820C");
                    echo crearCardProveedor("Bimbo",           "55-8765-4321", ["Nitos","Chocorroles"],     "Activo",              "25 Feb", "#28A745");
                    echo crearCardProveedor("Coca Cola",       "55-4444-3333", ["Delaware","Fanta"],        "Reabastecer urgente", "10 Feb", "#C0392B");
                    echo crearCardProveedor("Gamesa",          "55-9999-0000", ["Chokis","Emperador"],      "Activo",              "01 Mar", "#28A745");
                    echo crearCardProveedor("Barcel",          "55-7777-1111", ["Takis","Big Mix"],         "Activo",              "27 Feb", "#28A745");
                    echo crearCardProveedor("Dist. Chidas",    "55-2222-8888", ["Papas","Nachos"],          "Inactivo",            "10 Feb", "#ced4da");
                    ?>
                </div>
            </div>
        </div>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
        <div class="float-right d-none d-sm-inline-block"><b>Version</b> 3.1.0</div>
    </footer>
    <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<script src="public/plugins/jquery/jquery.min.js"></script>
<script src="public/plugins/jquery-ui/jquery-ui.min.js"></script>
<script>$.widget.bridge('uibutton', $.ui.button)</script>
<script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="public/dist/js/adminlte.js"></script>
<script src="public/dist/js/demo.js"></script>
</body>
</html>