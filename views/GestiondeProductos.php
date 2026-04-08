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

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../public/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../public/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../public/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../public/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../public/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../public/plugins/summernote/summernote-bs4.min.css">

 <style>
  /* 1. NAVBAR SUPERIOR (FUERZA BRUTA) */
  /* Aplicamos a todas las variantes posibles de la navbar */
  .main-header.navbar,
  .main-header.navbar-white,
  .main-header.navbar-light,
  nav.main-header {
    background-color: #E8820C !important;
    border-bottom: none !important;
  }
  
  /* Forzar iconos, botones y links a blanco */
  .main-header .nav-link, 
  .main-header .nav-link i,
  .main-header .navbar-nav .nav-item a,
  .navbar-light .navbar-nav .nav-link {
    color: #ffffff !important;
  }

  /* 2. SIDEBAR (LATERAL Y LOGO) */
  .main-sidebar, 
  .main-sidebar:before, 
  .brand-link, 
  .sidebar-dark-primary {
    background-color: #E8820C !important;
  }

  /* 3. TEXTOS E ICONOS DEL MENÚ LATERAL */
  .nav-sidebar .nav-link, 
  .brand-link .brand-text, 
  .sidebar .user-panel .info a, 
  .nav-sidebar .nav-link i {
    color: #ffffff !important;
  }

  /* 4. BARRA DE BÚSQUEDA BLANCA */
  .form-control-sidebar, .btn-sidebar {
    background-color: #ffffff !important;
    border: 1px solid #ddd !important;
    color: #333 !important;
  }
  .btn-sidebar i { color: #333 !important; }

  /* 5. FONDO DE PÁGINA */
  .content-wrapper {
    background-color: #FFF5E5 !important;
  }

  .user-panel, .form-inline {
    border-bottom: 1px solid rgba(255,255,255,0.2) !important;
  }
</style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../public/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../public/dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../public/dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../public/dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="../public/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Stock It</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../public/dist/img/persona 1.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Miyamoto Musashi</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          <li class="nav-item">
            <a href="pages/widgets.html" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                   Productos
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="pages/widgets.html" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Analisis de Productos
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="pages/widgets.html" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Clientes
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="pages/widgets.html" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Inventario
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="pages/widgets.html" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Proveedores
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="pages/widgets.html" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Alertas
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="pages/widgets.html" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Reportes
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="pages/widgets.html" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Tipo de Ventas
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
  <div class="container-fluid">
    <div class="row mb-3 align-items-end"> <!-- align-items-end para que todo baje a la misma línea -->
      
      <!-- LADO IZQUIERDO: Títulos -->
      <div class="col-sm-6">
        <h1 class="m-0 d-inline" style="color: #333; font-weight: bold;">Gestión de</h1> 
        <h1 class="m-0 d-inline" style="color: #dd6e12; font-style: italic;">Productos</h1>
        <p class="text-muted m-0">Catálogo de productos</p>
      </div>

      <!-- LADO DERECHO: Buscador y Botón -->
      <div class="col-sm-6 d-flex justify-content-end align-items-center">
        
        <!-- Barra de Búsqueda (Sin clases de sidebar) -->
        <div class="input-group mr-2" style="max-width: 250px;">
          <input class="form-control" type="search" placeholder="Buscar producto..." aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-default">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>

        <!-- Botón Nuevo (Cambiamos el <td> por un div o simplemente el button) -->
        <td>
                      <button type="button" class="btn btn-block btn-outline-warning btn-sm">Agregar Producto</button>
                    </td>

      </div>
    </div>
  </div>
</div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <!-- Aqui colocar la funcion tarjeta -->
          <?=tarjeta("verde",     "1019", "Total de Productos", "bolsa"); ?>
          <?=tarjeta("blanco", "480", "Activos", "check"); ?>
          <?=tarjeta("rojo",    "35",   "Por caducar", "calendario"); ?>
          <?=tarjeta("azul",     "5",   "Categorias", "estadisticas"); ?>
        </div>
            <?php
// 1. Definimos los nombres de las columnas (Encabezados)
$columnas = [
    "Código", 
    "Nombre del Producto", 
    "Categoría", 
    "Stock Actual", 
    "Precio Venta", 
    "Estado"
];

// 2. Definimos los datos (Simulando lo que vendría de una base de datos)
// Nota: Puedes meter etiquetas HTML dentro de los textos (como los badges)
$productos = [
    [
        "PROD-007", 
        "Barritas Fresa 67g", 
        "Pastelitos", 
        "120 unidades", 
        "$15.00", 
        "<span class='badge badge-success'>Disponible</span>"
    ],
    [
        "PROD-008", 
        "Sabritas Sal 42g", 
        "Botanas", 
        "300 unidades", 
        "$17.00", 
        "<span class='badge badge-success'>Disponible</span>"
    ],
    [
        "PROD-009", 
        "Coca Cola 600ml", 
        "Bebidas", 
        "12 unidades", 
        "$18.00", 
        "<span class='badge badge-warning'>Poco Stock</span>"
    ],
    [
        "PROD-010", 
        "Leche Entera 1L", 
        "Lácteos", 
        "48 unidades", 
        "$26.50", 
        "<span class='badge badge-success'>Disponible</span>"
    ],
    [
        "PROD-011", 
        "Donitas Espolvoreadas", 
        "Pastelitos", 
        "0 unidades", 
        "$20.00", 
        "<span class='badge badge-danger'>Agotado</span>"
    ],
    [
        "PROD-012", 
        "Jugo de Piña 1L", 
        "Bebidas", 
        "85 unidades", 
        "$22.00", 
        "<span class='badge badge-success'>Disponible</span>"
    ],
    [
        "PROD-013", 
        "Yogurt Fresas 200g", 
        "Lácteos", 
        "8 unidades", 
        "$14.50", 
        "<span class='badge badge-warning'>Poco Stock</span>"
    ],
    [
        "PROD-014", 
        "Galletas Oreo 114g", 
        "Galletas", 
        "210 unidades", 
        "$19.00", 
        "<span class='badge badge-success'>Disponible</span>"
    ],
    [
        "PROD-015", 
        "Agua Natural 1.5L", 
        "Bebidas", 
        "150 unidades", 
        "$12.00", 
        "<span class='badge badge-success'>Disponible</span>"
    ],
    [
        "PROD-016", 
        "Cheetos Queso 52g", 
        "Botanas", 
        "5 unidades", 
        "$16.50", 
        "<span class='badge badge-danger'>Agotado</span>"
    ],
    [
        "PROD-001", 
        "Gansito 50g", 
        "Pastelitos", 
        "45 unidades", 
        "$18.50", 
        "<span class='badge badge-success'>Disponible</span>"
    ],
    [
        "PROD-002", 
        "Refresco Cola 600ml", 
        "Bebidas", 
        "12 unidades", 
        "$17.00", 
        "<span class='badge badge-warning'>Stock Bajo</span>"
    ],
    [
        "PROD-003", 
        "Papas Fritas Sal", 
        "Botanas", 
        "0 unidades", 
        "$15.50", 
        "<span class='badge badge-danger'>Agotado</span>"
    ],
    [
        "PROD-004", 
        "Leche Entera 1L", 
        "Lácteos", 
        "24 unidades", 
        "$26.00", 
        "<span class='badge badge-success'>Disponible</span>"
    ]
];

// 3. Llamamos a la función y la imprimimos
// El último parámetro 'tabla_productos' será el ID para que funcione el buscador de DataTables
echo crearTabla("Listado Maestro de Inventario", $columnas, $productos, "tabla_productos");
?>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.1.0
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../public/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../public/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="../public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="../public/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="../public/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../public/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../public/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../public/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../public/plugins/moment/moment.min.js"></script>
<script src="../public/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../public/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="../public/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="../public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../public/dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../public/dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../public/dist/js/pages/dashboard.js"></script>
</body>
</html>