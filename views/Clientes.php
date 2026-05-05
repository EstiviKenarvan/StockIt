<?php
include __DIR__ . "/includes/tarjeta.php";
include __DIR__ . "/includes/tablas.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>StockIt | Clientes</title>

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
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="public/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <?php
  $paginaActiva = "clientes"; // o analisisproductos, productos, clientes,  
  include __DIR__ . "/includes/barras.php";
  ?>
  <!-- ── Contenido ─────────────────────────────────────────────── -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-3 align-items-end">
          <div class="col-sm-6">
            <h1 class="m-0 d-inline" style="color: #333; font-weight: bold;">Gestión de</h1>
            <h1 class="m-0 d-inline" style="color: #E8820C; font-style: italic;">Clientes</h1>
            <p class="text-muted m-0">Catálogo de clientes</p>
          </div>
          <div class="col-sm-6 d-flex justify-content-end align-items-center">
            <a href="?menu=clientes&submenu=registro" class="btn btn-outline-warning">
              <i class="fas fa-plus mr-1"></i> Agregar Cliente
            </a>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
       <?php
$columnas_clientes = ["IDcliente", "Tipo Cliente", "Nombre", "Crédito", "Estado", "Notas", "Compras Realizadas", "Fecha Pago", "Total Crédito"];

$datos_clientes = [];

foreach ($clientes as $c) {
    // Badge de color según el estado
    $badgeEstado = match($c['estado']) {
        'Activo'   => "<span class='badge badge-success'>Activo</span>",
        'Inactivo' => "<span class='badge badge-danger'>Inactivo</span>",
        'VIP'      => "<span class='badge badge-primary'>VIP</span>",
        default    => "<span class='badge badge-secondary'>{$c['estado']}</span>"
    };

    $datos_clientes[] = [
        $c['idCliente'],
        $c['tipoCliente'],
        $c['cliente_nombre'],
        $c['credito'],
        $badgeEstado,
        $c['notas'],
        $c['TotalCompras'],
        $c['fechaPago'],
        $c['TotalCredito'],
    ];
}

echo crearTabla("Clientes", $columnas_clientes, $datos_clientes, "tabla_clientes");
?>
      </div>
    </section>
  </div>

  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block"><b>Version</b> 3.1.0</div>
  </footer>

</div>

<script src="public/plugins/jquery/jquery.min.js"></script>
<script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="public/dist/js/adminlte.js"></script>
</body>
</html>