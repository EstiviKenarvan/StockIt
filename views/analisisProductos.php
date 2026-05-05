<?php
function crearFilaColapsable($id, $titulo, $esRojo = false) {
    $clase = $esRojo ? 'card-fila-roja' : 'card-fila';
    return "
    <div class='card {$clase}'>
        <div class='card-header' data-toggle='collapse' data-target='#collapse-{$id}'>
            <h5 class='mb-0' style='color:#444;'>{$titulo}</h5>
            <i class='fas fa-caret-down' style='float:right;'></i>
        </div>
        <div id='collapse-{$id}' class='collapse'>
            <div class='card-body'>Detalles para {$titulo}...</div>
        </div>
    </div>";
}
function crearLeyenda($color, $texto) {
    return "<div class='leyenda-item'><div class='color-box' style='background-color:{$color};'></div><span>{$texto}</span></div>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stock It | Productos Más Vendidos</title>
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
        .card-fila { background:white !important; border-radius:15px !important; border:1px solid #E0E0E0 !important; margin-bottom:15px !important; border-left:8px solid #E8820C !important; box-shadow:0 2px 4px rgba(0,0,0,0.05) !important; }
        .panel-derecho { background-color:white !important; border-radius:15px !important; border:1px solid #FFE0B2 !important; padding:0px !important; overflow:hidden; box-shadow:0 4px 6px rgba(0,0,0,0.05) !important; min-height:450px; }
        .panel-derecho-header { background-color:#FFF5E5 !important; padding:15px 20px !important; border-bottom:1px solid #FFE0B2 !important; }
        .titulo-panel { color:#C06000 !important; font-weight:bold !important; font-size:1.1rem !important; margin:0 !important; }
        .panel-derecho-body { padding:25px !important; }
        .leyenda-item { display:flex; align-items:center; margin-bottom:12px; font-size:14px; }
        .color-box { width:16px; height:16px; border-radius:4px; margin-right:12px; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="public/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
    </div>
<?php
$paginaActiva = "analisis"; 
include __DIR__ . "/includes/barras.php";
    ?>

    <!-- CONTENIDO -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-4">
                    <div class="col-md-6">
                        <h1 class="m-0">Análisis de <span class="titulo-naranja">Productos</span></h1>
                        <p class="text-muted">Productos con mayor movimiento</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="position-relative d-inline-block" style="width:300px;">
                            <i class="fas fa-search position-absolute" style="left:12px; top:11px; color:#ccc;"></i>
                            <input type="text" class="form-control pl-5" placeholder="Buscar producto..." style="border-radius:20px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-4">
                            <a href="?menu=analisisproductos&submenu=menosvendidos" class="btn btn-outline-warning mr-2" style="color:#E8820C; border-color:#FFE0B2; border-radius:10px;">
                                Productos menos vendidos
                            </a>
                            <!-- Activo: Más vendidos -->
                            <a href="?menu=analisisproductos&submenu=masvendidos" class="btn btn-danger" style="background-color:#C0392B; border:none; border-radius:10px;">
                                Productos más Vendidos
                            </a>
                        </div>

                        <?php
                        echo crearFilaColapsable(1, "Refrescos (Bebidas)");
                        echo crearFilaColapsable(2, "Frutas y Verduras");
                        echo crearFilaColapsable(3, "Productos de cuidado Personal");
                        echo crearFilaColapsable(4, "Snacks");
                        echo crearFilaColapsable(5, "Productos de limpieza");
                        echo crearFilaColapsable(6, "Revisión de categorías");
                        ?>
                    </div>

                    <!-- PANEL DERECHO -->
                    <div class="col-md-4">
                        <div class="panel-derecho card elevation-1">
                            <div class="panel-derecho-header">
                                <h5 class="titulo-panel">Distribución por categoría</h5>
                            </div>
                            <div class="panel-derecho-body">
                                <canvas id="graficoCategorias" style="min-height:220px; height:220px; max-height:220px; max-width:100%; margin-bottom:20px;"></canvas>
                                <?php
                                echo crearLeyenda("#E8820C", "Pastelitos (32%)");
                                echo crearLeyenda("#007BFF", "Bebidas (24%)");
                                echo crearLeyenda("#28A745", "Lácteos (17%)");
                                echo crearLeyenda("#6F42C1", "Botanas (17%)");
                                echo crearLeyenda("#B38600", "Galletas (10%)");
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
<script src="public/plugins/chart.js/Chart.min.js"></script>
<script src="public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="public/dist/js/adminlte.js"></script>
<script src="public/dist/js/demo.js"></script>
<script>
$(function () {
    new Chart($('#graficoCategorias').get(0).getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Pastelitos', 'Bebidas', 'Lácteos', 'Botanas', 'Galletas'],
            datasets: [{ data: [32, 24, 17, 17, 10], backgroundColor: ['#E8820C','#007BFF','#28A745','#6F42C1','#B38600'] }]
        },
        options: { maintainAspectRatio: false, responsive: true, legend: { display: false } }
    });
});
</script>
</body>
</html>