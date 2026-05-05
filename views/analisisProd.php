<?php
// Función para crear una fila colapsable con COLOR VARIABLE
function crearFilaColapsable($id, $titulo, $esRojo = false)
{
    $claseCard = $esRojo ? 'card-fila-roja' : 'card-fila';
    $claseIcono = $esRojo ? 'style="background:#F5C6CB; color:#D92323;"' : '';
    return "
    <div class='card {$claseCard}'>
        <div class='card-header' data-toggle='collapse' data-target='#collapse-{$id}'>
            <h5 class='mb-0' style='color: #444;'>{$titulo}</h5>
            <i class='fas fa-caret-down icono-collapse' {$claseIcono}></i>
        </div>
        <div id='collapse-{$id}' class='collapse'>
            <div class='card-body'>
                Detalles de baja rotación para {$titulo}...
            </div>
        </div>
    </div>
    ";
}

function crearLeyenda($color, $texto)
{
    return "
    <div class='leyenda-item'>
        <div class='color-box' style='background-color: {$color};'></div>
        <span>{$texto}</span>
    </div>
    ";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>StockIt | Análisis de Productos</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="public/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="public/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

  <style>
    /* ── Navbar ─────────────────────────────────────────────── */
    .main-header.navbar,
    .main-header.navbar-white,
    .main-header.navbar-light,
    nav.main-header { background-color: #E8820C !important; border-bottom: none !important; }

    .main-header .nav-link,
    .main-header .nav-link i,
    .main-header .navbar-nav .nav-item a,
    .navbar-light .navbar-nav .nav-link { color: #ffffff !important; }

    /* ── Sidebar ─────────────────────────────────────────────── */
    .main-sidebar, .main-sidebar:before, .brand-link, .sidebar-dark-primary { background-color: #E8820C !important; }

    .nav-sidebar .nav-link { background: transparent !important; color: #ffffff !important; }
    .nav-sidebar .nav-link:hover { background-color: rgba(255,255,255,0.15) !important; }
    .nav-sidebar .nav-link.active { background-color: rgba(0,0,0,0.15) !important; color: #ffffff !important; }
    .nav-sidebar .nav-link p, .nav-sidebar .nav-link i { color: #ffffff !important; }
    .brand-link .brand-text, .sidebar .user-panel .info a { color: #ffffff !important; }

    .form-control-sidebar, .btn-sidebar { background-color: #ffffff !important; border: 1px solid #ddd !important; color: #333 !important; }
    .btn-sidebar i { color: #333 !important; }
    .user-panel, .form-inline { border-bottom: 1px solid rgba(255,255,255,0.2) !important; }

    /* ── Contenido ───────────────────────────────────────────── */
    .content-wrapper { background-color: #FFF5E5 !important; }

    /* ── Filas rosadas (baja rotación) ───────────────────────── */
    .card-fila-roja {
      background: #FCE4E4 !important;
      border-radius: 15px !important;
      border: 1px solid #F5C6CB !important;
      margin-bottom: 15px !important;
      border-left: 8px solid #D92323 !important;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
    }
    .icono-collapse { float: right; padding: 5px; border-radius: 50%; }

    /* ── Panel derecho rojo ──────────────────────────────────── */
    .panel-derecho { background-color: white !important; border-radius: 15px !important; border: 1px solid #F5C6CB !important; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05) !important; }
    .panel-derecho-header { background-color: #FCE4E4 !important; padding: 15px 20px !important; border-bottom: 1px solid #F5C6CB !important; }
    .titulo-panel-rojo { color: #D92323 !important; font-weight: bold !important; margin: 0 !important; }

    /* ── Leyenda ─────────────────────────────────────────────── */
    .leyenda-item { display: flex; align-items: center; margin-bottom: 8px; }
    .color-box { width: 15px; height: 15px; border-radius: 3px; margin-right: 10px; }

    /* ── Título naranja ──────────────────────────────────────── */
    .titulo-naranja { color: #E8820C; font-style: italic; }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="public/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>
<?php
$paginaActiva = "inventario"; // o clientes, reportes, ventas, etc.
include __DIR__ . "/includes/barras.php";
    ?>

  <!-- ── Contenido ─────────────────────────────────────────────── -->
  <div class="content-wrapper">

    <div class="content-header">
      <div class="container-fluid">
        <div class="row align-items-center mb-4">
          <div class="col-md-6">
            <h1 class="m-0">Análisis de <span class="titulo-naranja">Productos</span></h1>
            <p class="text-muted">Productos con menor movimiento</p>
          </div>
          <div class="col-md-6 text-right d-flex align-items-center justify-content-end">
            <div class="position-relative d-inline-block mr-3" style="width: 200px;">
              <i class="fas fa-search" style="position: absolute; left: 10px; top: 10px; color: #ccc;"></i>
              <input type="text" class="form-control" style="padding-left: 30px; border-radius: 8px;" placeholder="Buscar...">
            </div>
            <a href="?menu=inventario&submenu=registro" class="btn" style="background-color: #E8820C; color: white; border-radius: 8px; font-weight: 500; white-space: nowrap; text-decoration: none;">
    + Registrar Entrada
</a>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">

        <!-- Tarjetas de resumen -->
        <div class="row mb-4">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-white elevation-1" style="border-top: 5px solid #28a745; border-radius: 10px;">
              <div class="inner">
                <p class="text-muted mb-0">Entradas hoy</p>
                <h3 class="text-success" style="font-weight: bold;">124</h3>
                <div style="background-color: #e8f5e9; color: #28a745; padding: 2px 10px; border-radius: 5px; display: inline-block; font-size: 0.8rem;">Piezas</div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-white elevation-1" style="border-top: 5px solid #dc3545; border-radius: 10px;">
              <div class="inner">
                <p class="text-muted mb-0">Salidas hoy</p>
                <h3 class="text-danger" style="font-weight: bold;">178</h3>
                <div style="background-color: #ffebee; color: #dc3545; padding: 2px 10px; border-radius: 5px; display: inline-block; font-size: 0.8rem;">Piezas</div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-white elevation-1" style="border-top: 5px solid #fd7e14; border-radius: 10px;">
              <div class="inner">
                <p class="text-muted mb-0">Stock total</p>
                <h3 style="color: #fd7e14; font-weight: bold;">1300</h3>
                <div style="background-color: #fff3e0; color: #fd7e14; padding: 2px 10px; border-radius: 5px; display: inline-block; font-size: 0.8rem;">Productos disponibles</div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-white elevation-1" style="border-top: 5px solid #ffc107; border-radius: 10px;">
              <div class="inner">
                <p class="text-muted mb-0">Stock bajo</p>
                <h3 class="text-warning" style="font-weight: bold;">5</h3>
                <div style="background-color: #fffde7; color: #fbc02d; padding: 2px 10px; border-radius: 5px; display: inline-block; font-size: 0.8rem;">Productos</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Gráficas -->
        <div class="row">
          <div class="col-md-8">
            <div class="card card-outline" style="border-radius: 15px; background-color: #FFF9F2; border: 1px solid #FAD7A0;">
              <div class="card-header border-0">
                <h5 style="color: #A04000; font-weight: bold; margin-top: 10px;">Movimientos Semanales</h5>
                <div class="card-tools">
                  <span class="badge" style="color: #888;"><i class="fas fa-circle" style="color: #F39C12;"></i> Entradas</span>
                  <span class="badge" style="color: #888;"><i class="fas fa-circle" style="color: #D35400;"></i> Salidas</span>
                </div>
              </div>
              <div class="card-body">
                <div style="border: 1px solid #FAD7A0; border-radius: 10px; padding: 15px; background-color: #FFF9F2;">
                  <canvas id="barChartMovimientos" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card h-100" style="border-radius: 15px; border: 1px solid #FAD7A0; background-color: #FFF9F2;">
              <div class="card-header border-0">
                <h5 style="color: #A04000; font-weight: bold; margin-top: 10px;">Stock por Categoría</h5>
              </div>
              <div class="card-body">
                <div style="border: 1px solid #FAD7A0; border-radius: 10px; padding: 15px; background-color: white; position: relative; height: 220px;">
                  <canvas id="graficoStockCategoria"></canvas>
                </div>
                <div class="mt-4 px-2">
                  <div class="d-flex justify-content-between mb-1">
                    <span><i class="fas fa-circle" style="color: #E67E22;"></i> Pastelitos</span>
                    <span class="font-weight-bold">40%</span>
                  </div>
                  <div class="d-flex justify-content-between mb-1">
                    <span><i class="fas fa-circle" style="color: #2E86C1;"></i> Rollos</span>
                    <span class="font-weight-bold">25%</span>
                  </div>
                  <div class="d-flex justify-content-between mb-1">
                    <span><i class="fas fa-circle" style="color: #28B463;"></i> Galletas</span>
                    <span class="font-weight-bold">20%</span>
                  </div>
                  <div class="d-flex justify-content-between">
                    <span><i class="fas fa-circle" style="color: #AF601A;"></i> Otros</span>
                    <span class="font-weight-bold">15%</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabla de movimientos -->
        <div class="row mt-4">
          <div class="col-md-12">
            <div class="card" style="border-radius: 15px; border: 1px solid #FAD7A0; background-color: white;">
              <div class="card-header border-0" style="background-color: #FFF9F2;">
                <h5 style="color: #A04000; font-weight: bold; margin-bottom: 0;">Lista de movimientos</h5>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead style="background-color: #FDEBD0; color: #A04000;">
                    <tr>
                      <th class="pl-4">Fecha</th>
                      <th>Producto</th>
                      <th>Tipo</th>
                      <th>Cantidad</th>
                      <th>Responsable</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="pl-4">01/03/2026 09:12</td>
                      <td>Gansito Marinela</td>
                      <td><span style="color: #2E86C1;">▲ Entrada</span></td>
                      <td><strong>+100 pzs</strong></td>
                      <td>Ana López</td>
                    </tr>
                    <tr>
                      <td class="pl-4">01/03/2026 11:30</td>
                      <td>Chocorroles</td>
                      <td><span style="color: #A93226;">▼ Salida</span></td>
                      <td><strong>-45 pzs</strong></td>
                      <td>Carlos Ruiz</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      </div><!-- /.container-fluid -->
    </section><!-- /.content -->

  </div><!-- /.content-wrapper -->

  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block"><b>Version</b> 3.1.0</div>
  </footer>

</div><!-- /.wrapper -->

<!-- Scripts -->
<script src="public/plugins/jquery/jquery.min.js"></script>
<script src="public/plugins/jquery-ui/jquery-ui.min.js"></script>
<script>$.widget.bridge('uibutton', $.ui.button)</script>
<script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="public/plugins/chart.js/Chart.min.js"></script>
<script src="public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="public/dist/js/adminlte.js"></script>

<script>
$(function() {
  // Gráfica de Movimientos Semanales (Barras)
  var barCtx = $('#barChartMovimientos').get(0).getContext('2d');
  new Chart(barCtx, {
    type: 'bar',
    data: {
      labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
      datasets: [
        { label: 'Entradas', backgroundColor: '#F39C12', data: [65, 59, 80, 81, 56, 55, 40] },
        { label: 'Salidas',  backgroundColor: '#D35400', data: [28, 48, 40, 19, 86, 27, 90] }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: { yAxes: [{ ticks: { beginAtZero: true } }] }
    }
  });

  // Gráfica de Stock por Categoría (Dona)
  var donaCtx = document.getElementById('graficoStockCategoria').getContext('2d');
  new Chart(donaCtx, {
    type: 'doughnut',
    data: {
      labels: ['Pastelitos', 'Rollos', 'Galletas', 'Otros'],
      datasets: [{
        data: [40, 25, 20, 15],
        backgroundColor: ['#E67E22', '#2E86C1', '#28B463', '#AF601A'],
        borderWidth: 5,
        borderColor: '#FFF9F2'
      }]
    },
    options: {
      maintainAspectRatio: false,
      cutoutPercentage: 70,
      legend: { display: false }
    }
  });
});
</script>
</body>
</html>