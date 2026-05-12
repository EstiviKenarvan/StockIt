<?php
function esActiva($pagina, $actual) {
    return $pagina === $actual ? 'active' : '';
}
$base = "public/";

// Leer alertas de sesión
$alertas      = $_SESSION['alertas'] ?? [];
$totalAlertas = count($alertas);
?>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<link rel="stylesheet" href="<?= $base ?>plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="<?= $base ?>dist/css/adminlte.min.css">
<link rel="stylesheet" href="<?= $base ?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

<style>
  .main-header.navbar,.main-header.navbar-white,.main-header.navbar-light,nav.main-header{background-color:#E8820C!important;border-bottom:none!important}
  .main-header .nav-link,.main-header .nav-link i,.navbar-light .navbar-nav .nav-link{color:#fff!important}
  .main-sidebar,.main-sidebar:before,.brand-link,.sidebar-dark-primary{background-color:#E8820C!important}
  .nav-sidebar .nav-link{background:transparent!important;color:#fff!important}
  .nav-sidebar .nav-link:hover{background-color:rgba(255,255,255,.15)!important}
  .nav-sidebar .nav-link.active{background-color:rgba(0,0,0,.2)!important;color:#fff!important}
  .nav-sidebar .nav-link p,.nav-sidebar .nav-link i{color:#fff!important}
  .brand-link .brand-text,.sidebar .user-panel .info a{color:#fff!important}
  .form-control-sidebar,.btn-sidebar{background-color:#fff!important;border:1px solid #ddd!important;color:#333!important}
  .btn-sidebar i{color:#333!important}
  .user-panel,.form-inline{border-bottom:1px solid rgba(255,255,255,.2)!important}
  .content-wrapper{background-color:#FFF5E5!important}
  .nav-sidebar .nav-header{color:rgba(255,255,255,.5);font-size:.7rem;letter-spacing:.05rem;padding:8px 16px 4px;text-transform:uppercase}

  /* ── Dropdown notificaciones ── */
  .notif-menu { background-color:#fff !important; min-width:300px; }
  .notif-menu .notif-titulo {
      display:block; padding:8px 16px;
      color:#E8820C !important; font-weight:bold; font-size:.85rem;
      border-bottom:2px solid #f0e0cc;
  }
  .notif-menu .notif-item {
      display:flex; align-items:center; gap:10px;
      padding:10px 16px; border-bottom:1px solid #f0e0cc;
      background:#fff !important; cursor:default;
  }
  .notif-menu .notif-item i { font-size:1rem; flex-shrink:0; }
  .notif-menu .notif-item .notif-nombre { color:#333 !important; font-size:.88rem; flex:1; }
  .notif-menu .notif-item .notif-motivo { color:#888 !important; font-size:.78rem; white-space:nowrap; }
  .notif-menu .notif-vacio { padding:12px 16px; color:#888 !important; text-align:center; }
  .notif-menu .notif-footer {
      display:block; padding:8px 16px; text-align:center;
      color:#E8820C !important; font-weight:bold; font-size:.83rem;
      border-top:2px solid #f0e0cc; text-decoration:none;
  }
  .notif-menu .notif-footer:hover { background:#FFF3E0 !important; }
</style>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="index.php?menu=dashboard" class="nav-link">Home</a>
    </li>
  </ul>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a class="nav-link" data-widget="navbar-search" href="#" role="button"><i class="fas fa-search"></i></a>
      <div class="navbar-search-block">
        <form class="form-inline">
          <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-navbar" type="submit"><i class="fas fa-search"></i></button>
              <button class="btn btn-navbar" type="button" data-widget="navbar-search"><i class="fas fa-times"></i></button>
            </div>
          </div>
        </form>
      </div>
    </li>

    <!-- ── Campana de notificaciones ── -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <?php if ($totalAlertas > 0): ?>
          <span class="badge badge-warning navbar-badge"><?= $totalAlertas ?></span>
        <?php endif; ?>
      </a>
      <div class="dropdown-menu dropdown-menu-right notif-menu">
        <span class="notif-titulo">
          <?= $totalAlertas > 0 ? "$totalAlertas alertas de inventario" : "Sin alertas" ?>
        </span>

        <?php if (empty($alertas)): ?>
          <div class="notif-vacio">
            <i class="fas fa-check-circle text-success mr-1"></i> Todo en orden
          </div>
        <?php else: ?>
          <?php foreach ($alertas as $a): ?>
            <?php
              $esBajoStock = $a['stockEnGeneral'] <= 5;
              $icono  = $esBajoStock ? 'fas fa-box-open' : 'fas fa-calendar-times';
              $color  = $esBajoStock ? '#dc3545' : '#ffc107';
              $motivo = $esBajoStock
                        ? "Stock: {$a['stockEnGeneral']} uds."
                        : "Caduca: " . date('d/m/Y', strtotime($a['fechaCaducidad']));
            ?>
            <div class="notif-item">
              <i class="<?= $icono ?>" style="color:<?= $color ?>"></i>
              <span class="notif-nombre"><?= htmlspecialchars($a['nombreProducto']) ?></span>
              <span class="notif-motivo"><?= $motivo ?></span>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

        <a href="index.php?menu=productos" class="notif-footer">
          Ver todos los productos
        </a>
      </div>
    </li>
    <!-- ── Fin campana ── -->

    <!-- Cerrar sesión -->
    <li class="nav-item">
      <a class="nav-link" href="index.php?menu=login" title="Cerrar sesión">
        <i class="fas fa-sign-out-alt"></i>
      </a>
    </li>
  </ul>
</nav>

<!-- Sidebar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="index.php?menu=dashboard" class="brand-link">
    <img src="<?= $base ?>dist/img/logoStock.png" alt="Stock It Logo" class="brand-image img-circle elevation-3" style="opacity:.8">
    <span class="brand-text font-weight-light">Stock It</span>
  </a>
  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?= $base ?>dist/img/mininico.jpeg" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">
          <?= isset($_SESSION['usuario_nombre']) ? htmlspecialchars($_SESSION['usuario_nombre']) : 'Administrador' ?>
        </a>
      </div>
    </div>
    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar"><i class="fas fa-search fa-fw"></i></button>
        </div>
      </div>
    </div>
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-header">General</li>
        <li class="nav-item">
          <a href="?menu=dashboard" class="nav-link <?= esActiva('dashboard', $paginaActiva) ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="?menu=productos" class="nav-link <?= esActiva('productos', $paginaActiva) ?>">
            <i class="nav-icon ion-bag"></i>
            <p>Productos <span class="right badge badge-danger">New</span></p>
          </a>
        </li>
        <li class="nav-item">
          <a href="?menu=analisisproductos" class="nav-link <?= esActiva('analisisproductos', $paginaActiva) ?>">
            <i class="nav-icon ion-pie-graph"></i>
            <p>Análisis de Productos <span class="right badge badge-danger">New</span></p>
          </a>
        </li>
        <li class="nav-item">
          <a href="?menu=clientes" class="nav-link <?= esActiva('clientes', $paginaActiva) ?>">
            <i class="nav-icon ion-ios-people"></i>
            <p>Clientes <span class="right badge badge-danger">New</span></p>
          </a>
        </li>
        <li class="nav-item">
          <a href="?menu=inventario" class="nav-link <?= esActiva('inventario', $paginaActiva) ?>">
            <i class="nav-icon ion-clipboard"></i>
            <p>Inventario <span class="right badge badge-danger">New</span></p>
          </a>
        </li>
        <li class="nav-item">
          <a href="?menu=proveedores" class="nav-link <?= esActiva('proveedores', $paginaActiva) ?>">
            <i class="nav-icon ion-briefcase"></i>
            <p>Proveedores <span class="right badge badge-danger">New</span></p>
          </a>
        </li>
        <li class="nav-item">
          <a href="?menu=reportes" class="nav-link <?= esActiva('reportes', $paginaActiva) ?>">
            <i class="nav-icon ion-document-text"></i>
            <p>Reportes <span class="right badge badge-danger">New</span></p>
          </a>
        </li>
        <li class="nav-item">
          <a href="?menu=ventas" class="nav-link <?= esActiva('ventas', $paginaActiva) ?>">
            <i class="nav-icon ion-cash"></i>
            <p>Ventas <span class="right badge badge-danger">New</span></p>
          </a>
        </li>

        <li class="nav-header">Administración</li>
        <li class="nav-item">
          <a href="?menu=usuarios" class="nav-link <?= esActiva('usuarios', $paginaActiva) ?>">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>Usuarios</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="?menu=respaldo" class="nav-link <?= esActiva('respaldo', $paginaActiva) ?>">
            <i class="nav-icon fas fa-database"></i>
            <p>Respaldo</p>
          </a>
        </li>

        <li class="nav-header">Sesión</li>
        <li class="nav-item">
          <a href="index.php?menu=login" class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>Cerrar Sesión</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>