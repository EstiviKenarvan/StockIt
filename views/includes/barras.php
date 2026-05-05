<?php
// Helper: marca el item activo en el sidebar
function esActiva($pagina, $actual) {
    return $pagina === $actual ? 'active' : '';
}

// $base siempre es "public/" porque todo carga desde index.php en la raíz
$base = "public/";
?>

<!-- ── CSS AdminLTE + Iconos ─────────────────────────────────── -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<link rel="stylesheet" href="<?= $base ?>plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="<?= $base ?>dist/css/adminlte.min.css">
<link rel="stylesheet" href="<?= $base ?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

<style>
  /* ── Navbar ────────────────────────────────────────────────── */
  .main-header.navbar,
  .main-header.navbar-white,
  .main-header.navbar-light,
  nav.main-header { background-color: #E8820C !important; border-bottom: none !important; }

  .main-header .nav-link,
  .main-header .nav-link i,
  .main-header .navbar-nav .nav-item a,
  .navbar-light .navbar-nav .nav-link { color: #ffffff !important; }

  /* ── Sidebar ───────────────────────────────────────────────── */
  .main-sidebar, .main-sidebar:before,
  .brand-link, .sidebar-dark-primary { background-color: #E8820C !important; }

  .nav-sidebar .nav-link { background: transparent !important; color: #ffffff !important; }
  .nav-sidebar .nav-link:hover { background-color: rgba(255,255,255,0.15) !important; }
  .nav-sidebar .nav-link.active { background-color: rgba(0,0,0,0.2) !important; color: #ffffff !important; }
  .nav-sidebar .nav-link p, .nav-sidebar .nav-link i { color: #ffffff !important; }
  .brand-link .brand-text, .sidebar .user-panel .info a { color: #ffffff !important; }

  .form-control-sidebar, .btn-sidebar { background-color: #ffffff !important; border: 1px solid #ddd !important; color: #333 !important; }
  .btn-sidebar i { color: #333 !important; }
  .user-panel, .form-inline { border-bottom: 1px solid rgba(255,255,255,0.2) !important; }

  /* ── Fondo general ─────────────────────────────────────────── */
  .content-wrapper { background-color: #FFF5E5 !important; }
</style>

<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">
  <img class="animation__shake" src="<?= $base ?>dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
</div>

<!-- ── Navbar ───────────────────────────────────────────────── -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="index.php" class="nav-link">Home</a>
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
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge">15</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">15 Notifications</span>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item"><i class="fas fa-envelope mr-2"></i> 4 new messages <span class="float-right text-muted text-sm">3 mins</span></a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item"><i class="fas fa-users mr-2"></i> 8 friend requests <span class="float-right text-muted text-sm">12 hours</span></a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item"><i class="fas fa-file mr-2"></i> 3 new reports <span class="float-right text-muted text-sm">2 days</span></a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
      </div>
    </li>
  </ul>
</nav>

<!-- ── Sidebar ──────────────────────────────────────────────── -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="index.php" class="brand-link">
    <img src="<?= $base ?>dist/img/logoStock.png" alt="Stock It Logo" class="brand-image img-circle elevation-3" style="opacity:.8">
    <span class="brand-text font-weight-light">Stock It</span>
  </a>
  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?= $base ?>dist/img/mininico.jpeg" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">Nicol Valentina</a>
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
        <li class="nav-item">
          <a href="?menu=productos" class="nav-link <?= esActiva('productos', $paginaActiva) ?>">
            <i class="nav-icon ion-bag"></i>
            <p>Productos <span class="right badge badge-danger">New</span></p>
          </a>
        </li>
        <li class="nav-item">
          <a href="?menu=analisisproductos" class="nav-link <?= esActiva('analisis', $paginaActiva) ?>">
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
            <p>Tipo de Ventas <span class="right badge badge-danger">New</span></p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>