<?php
// Función con COLOR VARIABLE — conservada del original
function crearFilaColapsable($id, $titulo, $esRojo = false)
{
    $claseCard = $esRojo ? 'card-fila-roja' : 'card-fila';
    $claseIcono = $esRojo ? 'style="color:#D92323;"' : '';
    return "
    <div class='card {$claseCard}'>
        <div class='card-header' data-toggle='collapse' data-target='#collapse-{$id}'>
            <h5 class='mb-0' style='color:#444;'>{$titulo}</h5>
            <i class='fas fa-caret-down icono-collapse' {$claseIcono}></i>
        </div>
        <div id='collapse-{$id}' class='collapse'>
            <div class='card-body'>Detalles de baja rotación para {$titulo}...</div>
        </div>
    </div>";
}

function crearLeyenda($color, $texto)
{
    return "
    <div class='leyenda-item'>
        <div class='color-box' style='background-color:{$color};'></div>
        <span>{$texto}</span>
    </div>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stock It | Productos Menos Vendidos</title>
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

        /* Filas naranja estándar */
        .card-fila {
            background: white !important; border-radius: 15px !important;
            border: 1px solid #E0E0E0 !important; margin-bottom: 15px !important;
            border-left: 8px solid #E8820C !important; box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
        }
        /* Filas rojo baja rotación */
        .card-fila-roja {
            background: #FCE4E4 !important; border-radius: 15px !important;
            border: 1px solid #F5C6CB !important; margin-bottom: 15px !important;
            border-left: 8px solid #D92323 !important; box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
        }
        .icono-collapse { float: right; padding: 5px; border-radius: 50%; }
        .leyenda-item { display: flex; align-items: center; margin-bottom: 8px; font-size: 14px; }
        .color-box { width: 15px; height: 15px; border-radius: 3px; margin-right: 10px; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

<?php
$paginaActiva = "analisis"; // o analisisproductos, productos, clientes,
include __DIR__ . "/includes/barras.php";
    ?>
    <!-- CONTENIDO -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-4">
                    <div class="col-md-6">
                        <h1 class="m-0">Análisis de <span class="titulo-naranja">Productos</span></h1>
                        <p class="text-muted">Productos con menor movimiento</p>
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
                            <!-- Activo: Menos vendidos -->
                            <a href="?menu=analisisproductos&submenu=menosvendidos" class="btn btn-danger mr-2" style="background-color:#C0392B; border-radius:10px; border:none;">
                                Productos menos vendidos
                            </a>
                            <a href="?menu=analisisproductos&submenu=masvendidos" class="btn btn-outline-warning" style="color:#E8820C; border-color:#FFE0B2; border-radius:10px;">
                                Productos más Vendidos
                            </a>
                        </div>

                        <?php
                        echo crearFilaColapsable(1, "Refrescos (Bebidas)", true);
                        echo crearFilaColapsable(2, "Frutas y Verduras", true);
                        echo crearFilaColapsable(3, "Productos de cuidado Personal", true);
                        echo crearFilaColapsable(4, "Snacks", true);
                        echo crearFilaColapsable(5, "Productos de limpieza", true);
                        echo crearFilaColapsable(6, "Revisión de categorías", true);
                        ?>
                    </div>
                </div>
            </div>
        </section>
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
    new Chart($('#graficoBajaRotacion').get(0).getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Chile seco', 'Enlatados', 'Otros'],
            datasets: [{ data: [40, 25, 35], backgroundColor: ['#C0392B', '#E74C3C', '#FADBD8'] }]
        },
        options: { maintainAspectRatio: false, responsive: true, legend: { display: false } }
    });
});
</script>
</body>
</html>