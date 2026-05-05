<?php
include __DIR__ . "/includes/tarjeta.php";
include __DIR__ . "/includes/tablas.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Dashboard</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="public/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="public/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

  <style>
    .main-header.navbar, .main-header.navbar-white, .main-header.navbar-light, nav.main-header { background-color: #E8820C !important; border-bottom: none !important; }
    .main-header .nav-link, .main-header .nav-link i, .main-header .navbar-nav .nav-item a, .navbar-light .navbar-nav .nav-link { color: #ffffff !important; }
    .main-sidebar, .main-sidebar:before, .brand-link, .sidebar-dark-primary { background-color: #E8820C !important; }
    .nav-sidebar .nav-link, .brand-link .brand-text, .sidebar .user-panel .info a, .nav-sidebar .nav-link i { color: #ffffff !important; }
    .form-control-sidebar, .btn-sidebar { background-color: #ffffff !important; border: 1px solid #ddd !important; color: #333 !important; }
    .btn-sidebar i { color: #333 !important; }
    .content-wrapper { background-color: #FFF5E5 !important; }
    .user-panel, .form-inline { border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important; }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="public/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
    </div>

    <?php
    $paginaActiva = "productos";
    include __DIR__ . "/includes/barras.php";
    ?>

    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-3 align-items-end">
            <div class="col-sm-6">
              <h1 class="m-0 d-inline" style="color: #333; font-weight: bold;">Gestión de</h1>
              <h1 class="m-0 d-inline" style="color: #dd6e12; font-style: italic;">Productos</h1>
              <p class="text-muted m-0">Catálogo de productos</p>
            </div>
            <div class="col-sm-6 d-flex justify-content-end align-items-center">
              <a href="?menu=productos&submenu=registro" class="btn btn-outline-warning">
                Agregar Producto
              </a>
            </div>
          </div>
        </div>
      </div>

      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <?php
$total      = count($productos);
$activos    = count(array_filter($productos, fn($p) => $p['estado'] == 'Disponible' || $p['estado'] == 'Activo'));
$porCaducar = count(array_filter($productos, fn($p) => !empty($p['fechaCaducidad']) && strtotime($p['fechaCaducidad']) <= strtotime('+30 days')));
$categorias_count = count(array_unique(array_column($productos, 'nombreCategoria')));
?>
<?= tarjeta("verde",  $total,           "Total de Productos", "bolsa"); ?>
<?= tarjeta("blanco", $activos,         "Activos",            "check"); ?>
<?= tarjeta("rojo",   $porCaducar,      "Por caducar",        "calendario"); ?>
<?= tarjeta("azul",   $categorias_count,"Categorias",         "estadisticas"); ?>
          </div>

          <?php
          $columnas = ["Código", "Nombre del Producto", "Categoría", "Stock Actual", "Precio Venta", "Estado", "Acciones"];

          // Datos reales de la BD (vienen del controlador como $productos)
          $datos = [];
          foreach ($productos as $p) {
              $badge = match($p['estado']) {
                  'Disponible' => "<span class='badge badge-success'>Disponible</span>",
                  'Poco Stock' => "<span class='badge badge-warning'>Poco Stock</span>",
                  'Agotado'    => "<span class='badge badge-danger'>Agotado</span>",
                  default      => "<span class='badge badge-secondary'>{$p['estado']}</span>"
              };

              $acciones = "
                <a href='?menu=productos&submenu=editar&id={$p['idProducto']}' class='btn btn-xs btn-warning mr-1'><i class='fas fa-edit'></i></a>
                <a href='?menu=productos&submenu=borrar&id={$p['idProducto']}' class='btn btn-xs btn-danger' onclick=\"return confirm('¿Eliminar?')\"><i class='fas fa-trash'></i></a>
              ";

              $datos[] = [
                  $p['codigoBarras'],
                  $p['nombreProducto'],
                  $p['nombreCategoria'] ?? '—',
                  $p['stockEnGeneral'] . ' uds.',
                  '$' . number_format($p['precioVenta'], 2),
                  $badge,
                  $acciones
              ];
          }

          echo crearTabla("Inventario", $columnas, $datos, "tabla_productos");
          ?>
        </div>
      </section>
    </div>

    <footer class="main-footer">
      <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
      All rights reserved.
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
</body>
</html>