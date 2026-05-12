<?php
function crearFilaColapsable($id, $titulo) { return ""; }
function crearLeyenda($color, $texto) { return ""; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stock It | Reportes</title>
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
        .main-header.navbar, .main-header.navbar-white, .main-header.navbar-light, nav.main-header {
            background-color: #E8820C !important; border-bottom: none !important;
        }
        .main-header .nav-link, .main-header .nav-link i,
        .main-header .navbar-nav .nav-item a, .navbar-light .navbar-nav .nav-link { color: #ffffff !important; }
        .main-sidebar, .main-sidebar:before, .brand-link, .sidebar-dark-primary { background-color: #E8820C !important; }
        .nav-sidebar .nav-link, .brand-link .brand-text,
        .sidebar .user-panel .info a, .nav-sidebar .nav-link i { color: #ffffff !important; }
        .form-control-sidebar, .btn-sidebar { background-color: #ffffff !important; border: 1px solid #ddd !important; color: #333 !important; }
        .btn-sidebar i { color: #333 !important; }
        .user-panel, .form-inline { border-bottom: 1px solid rgba(255,255,255,0.2) !important; }
        .content-wrapper { background-color: #FFF5E5 !important; }
        .titulo-naranja { color: #E8820C; font-weight: bold; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

<?php
$paginaActiva = "reportes"; // o clientes, reportes, ventas, etc.
include __DIR__ . "/includes/barras.php";
    ?>

    <!-- CONTENIDO -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-4">
                    <div class="col-md-6">
                        <h1 class="m-0">Módulo de <span class="titulo-naranja">Reportes</span></h1>
                        <p class="text-muted">Análisis e informes del inventario</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="?menu=reportes&submenu=generar" class="btn" style="background-color:#E8820C; color:white; border-radius:8px; font-weight:bold; padding:10px 20px;">
                            <i class="fas fa-plus mr-2"></i> Nuevo reporte
                        </a>
                    </div>
                    
                </div>

                <!-- Tarjetas acceso rápido -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card p-3 shadow-sm" style="border-top:5px solid #E8820C; border-radius:10px;">
                            <h6 class="font-weight-bold"><i class="fas fa-box-open mr-2 text-warning"></i> Disponibilidad</h6>
                            <p class="text-muted small">Productos disponibles vs agotados.</p>
                            <a href="generar_nuevo_reporte.php" class="btn btn-sm mt-2" style="background-color:#E8820C; color:white; border-radius:6px;">Ver reporte</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-3 shadow-sm" style="border-top:5px solid #E8820C; border-radius:10px;">
                            <h6 class="font-weight-bold"><i class="fas fa-clock mr-2 text-warning"></i> Próximos a Caducar</h6>
                            <p class="text-muted small">Listado con fecha de caducidad.</p>
                            <a href="generar_nuevo_reporte.php" class="btn btn-sm mt-2" style="background-color:#E8820C; color:white; border-radius:6px;">Ver reporte</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-3 shadow-sm" style="border-top:5px solid #E8820C; border-radius:10px;">
                            <h6 class="font-weight-bold"><i class="fas fa-chart-line mr-2 text-warning"></i> Reporte General</h6>
                            <p class="text-muted small">Resumen completo del inventario.</p>
                            <a href="generar_nuevo_reporte.php" class="btn btn-sm mt-2" style="background-color:#E8820C; color:white; border-radius:6px;">Ver reporte</a>
                        </div>
                    </div>
                </div>

                <!-- Gráfica + Estado actual -->
                <div class="row mb-4">
                    <div class="col-md-7">
                        <div class="card p-4 shadow-sm" style="border-radius:15px; height:100%;">
    <h6 class="titulo-naranja font-weight-bold mb-4">Tendencia Mensual de Inventario</h6>
    <div style="position: relative; height: 300px; width: 100%;">
        <canvas id="graficaTendencia"></canvas>
    </div>
    <div class="mt-3 small">
        <i class="fas fa-circle mr-1" style="color:#6c757d;"></i> Entradas
        <i class="fas fa-circle ml-3 mr-1" style="color:#E8820C;"></i> Salidas
    </div>
</div>
                    </div>
                    <div class="col-md-5">
                        <div class="card p-4 shadow-sm" style="border-radius:15px; height:100%;">
                            <h6 class="titulo-naranja font-weight-bold mb-4">Resumen de Estado Actual</h6>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small mb-1"><span>Disponibles</span><span class="font-weight-bold text-success">132/148</span></div>
                                <div class="progress" style="height:8px; border-radius:10px;"><div class="progress-bar bg-success" style="width:85%"></div></div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small mb-1"><span>Por caducar</span><span class="font-weight-bold text-warning">18 productos</span></div>
                                <div class="progress" style="height:8px; border-radius:10px;"><div class="progress-bar bg-warning" style="width:40%"></div></div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small mb-1"><span>Bajo stock</span><span class="font-weight-bold" style="color:#E8820C;">5 productos</span></div>
                                <div class="progress" style="height:8px; border-radius:10px;"><div class="progress-bar" style="width:15%; background-color:#E8820C;"></div></div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small mb-1"><span>Agotados</span><span class="font-weight-bold text-danger">1 producto</span></div>
                                <div class="progress" style="height:8px; border-radius:10px;"><div class="progress-bar bg-danger" style="width:5%"></div></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historial -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-sm" style="border-radius:15px; overflow:hidden;">
                            <div class="card-header bg-white py-3">
                                <h6 class="titulo-naranja font-weight-bold m-0">Historial de reportes</h6>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-hover m-0">
                                    <thead style="background-color:#FFF5E5; color:#C06000;">
                                        <tr><th>Fecha</th><th>Tipo</th><th>Generado por</th><th>Período</th><th>Archivo</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>01/03/2025</td><td>Disponibilidad</td><td>Ana López</td><td>Febrero 2025</td><td><span class="text-danger"><i class="fas fa-file-pdf mr-1"></i> PDF</span></td></tr>
                                        <tr><td>25/02/2025</td><td>Caducidades</td><td>Carlos Ruiz</td><td>Sem. 4 Feb</td><td><span class="text-danger"><i class="fas fa-file-pdf mr-1"></i> PDF</span></td></tr>
                                        <tr><td>01/02/2025</td><td>General</td><td>Ana López</td><td>Enero 2025</td><td><span class="text-danger"><i class="fas fa-file-pdf mr-1"></i> PDF</span></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
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
    var ctx = $('#graficaTendencia').get(0).getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [
                { label: 'Entradas', data: [65, 90, 80, 110, 95, 120], borderColor: '#6c757d', backgroundColor: 'rgba(108,117,125,0.1)', tension: 0.4, fill: true },
                { label: 'Salidas',  data: [45, 70, 60,  85, 75,  95], borderColor: '#E8820C', backgroundColor: 'rgba(232,130,12,0.1)', tension: 0.4, fill: true }
            ]
        },
        options: { maintainAspectRatio: false, responsive: true, legend: { display: false } }
    });
});
</script>
</body>
</html>