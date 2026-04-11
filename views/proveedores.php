<?php
require_once "../config/conexion.php"; 
$stmt = $conexion->query("SELECT * FROM proveedores ORDER BY nombre ASC");
$listaProveedores = $stmt->fetchAll(PDO::FETCH_OBJ);
// Función para crear una fila colapsable
function crearFilaColapsable($id, $titulo)
{
    return "
    <div class='card card-fila'>
        <div class='card-header' data-toggle='collapse' data-target='#collapse-{$id}'>
            <h5 class='mb-0'>{$titulo}</h5>
            <i class='fas fa-caret-down icono-collapse'></i>
        </div>
        <div id='collapse-{$id}' class='collapse'>
            <div class='card-body'>
                Detalles para {$titulo}...
            </div>
        </div>
    </div>
    ";
}

// Función para crear un ítem de la leyenda del gráfico
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
        /* 1. MANTENER NARANJA ORIGINAL DE ADMINLTE (No tocar esto) */
        .main-header.navbar {
            background-color: #E8820C !important;
        }

        .main-header .nav-link,
        .main-header .nav-link i {
            color: #ffffff !important;
        }

        .main-sidebar,
        .brand-link {
            background-color: #E8820C !important;
        }

        .nav-sidebar .nav-link,
        .brand-link .brand-text,
        .nav-sidebar .nav-link i {
            color: #ffffff !important;
        }

        /* 2. FONDO GENERAL CREMA (Más suave) */
        .content-wrapper {
            background-color: #FFF5E5 !important;
        }

        /* 3. DISEÑO DE FILAS (IZQUIERDA - También mejorado) */
        .card-fila {
            background: white !important;
            border-radius: 15px !important;
            border: 1px solid #E0E0E0 !important;
            margin-bottom: 15px !important;
            border-left: 8px solid #E8820C !important;
            /* Borde naranja lateral */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05) !important;
        }

        .titulo-naranja {
            color: #E8820C;
            font-weight: bold;
        }

        /* 4. DISEÑO DEL PANEL DERECHO (EL QUE NOS INTERESA) */
        .panel-derecho {
            background-color: white !important;
            border-radius: 15px !important;
            border: 1px solid #FFE0B2 !important;
            /* Borde naranja suave */
            padding: 0px !important;
            /* Quitamos padding aquí para el header */
            overflow: hidden;
            /* Para que el header crema respete las esquinas redondeadas */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05) !important;
            min-height: 450px;
        }

        /* Header del panel con fondo crema suave */
        .panel-derecho-header {
            background-color: #FFF5E5 !important;
            padding: 15px 20px !important;
            border-bottom: 1px solid #FFE0B2 !important;
        }

        .titulo-panel {
            color: #C06000 !important;
            /* Naranja más oscuro/marrón para el título */
            font-weight: bold !important;
            font-size: 1.1rem !important;
            margin: 0 !important;
        }

        /* Cuerpo del panel (donde va el gráfico) */
        .panel-derecho-body {
            padding: 25px !important;
        }

        /* 5. GRÁFICO CIRCULAR SIMULADO */
        .grafico-circular-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
            position: relative;
        }

        .grafico-circular {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background-color: #FFF5E5;
            /* Fondo crema dentro del círculo */
            border: 2px solid #FFE0B2;
            /* Borde naranja suave del círculo */
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
            /* Sombra interna sutil */
        }

        /* Usamos un emoji de pastelito para simular el icono de la imagen */
        .pastelito-icon {
            font-size: 50px;
            transform: translateY(-5px);
            /* Ajuste de posición */
        }

        /* 6. LEYENDA DEL GRÁFICO (IZQUIERDA Y DERECHA) */
        .leyenda-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            font-size: 14px;
            color: #333;
        }

        .color-box {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            margin-right: 12px;
            display: inline-block;
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
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row align-items-center mb-4">
                        <div class="col-md-6">
                            <h1 class="m-0">Módulo de <span class="titulo-naranja">Proveedores</span></h1>
                            <p class="text-muted">Seguimiento de proveedores</p>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <div class="position-relative mr-3" style="width: 250px;">
                                    <i class="fas fa-search position-absolute" style="left: 10px; top: 10px; color: #ccc;"></i>
                                    <input type="text" class="form-control pl-5" placeholder="Buscar proveedor..." style="border-radius: 8px;">
                                </div>
                                <button class="btn" style="background-color: #E8820C; color: white; border-radius: 8px; font-weight: bold;">Registrar proveedor</button>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card p-3" style="border-top: 5px solid #E8820C; border-radius: 10px;">
                                <small class="text-muted font-weight-bold">Total proveedores</small>
                                <h2 class="titulo-naranja mb-1">12</h2>
                                <div class="p-1 px-2" style="background-color: #FFF5E5; border-radius: 5px; font-size: 0.8rem;">Registrados</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card p-3" style="border-top: 5px solid #28A745; border-radius: 10px;">
                                <small class="text-muted font-weight-bold">Activos</small>
                                <h2 class="text-success mb-1">9</h2>
                                <div class="p-1 px-2" style="background-color: #E8F5E9; border-radius: 5px; font-size: 0.8rem;">En servicio</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card p-3" style="border-top: 5px solid #007BFF; border-radius: 10px;">
                                <small class="text-muted font-weight-bold">Productos asociados</small>
                                <h2 class="text-primary mb-1">148</h2>
                                <div class="p-1 px-2" style="background-color: #E7F1FF; border-radius: 5px; font-size: 0.8rem;">Productos</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card p-3" style="border-top: 5px solid #C0392B; border-radius: 10px;">
                                <small class="text-muted font-weight-bold">Reabastecer</small>
                                <h2 class="text-danger mb-1">5</h2>
                                <div class="p-1 px-2" style="background-color: #FDEDEC; border-radius: 5px; font-size: 0.8rem;">Urgentes</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php
                        // Función auxiliar para generar las tarjetas de proveedor rápidamente
                        function crearCardProveedor($nombre, $tel, $tags, $status, $fecha, $bordeColor = "#28A745")
                        {
                            $statusClass = ($status == "Activo") ? "badge-success" : (($status == "Inactivo") ? "badge-light" : "badge-danger");
                            $statusStyle = ($status == "Activo") ? "background-color: #D4EDDA; color: #155724;" : "background-color: #F8D7DA; color: #721C24;";

                            echo "
                <div class='col-md-4 mb-4'>
                    <div class='card card-fila' style='border-top: 4px solid {$bordeColor} !important; border-left: none !important; padding: 20px; border-radius: 12px;'>
                        <div class='d-flex align-items-center mb-2'>
                            <i class='fas fa-store mr-2' style='color: #6c757d;'></i>
                            <h5 class='m-0 font-weight-bold'>{$nombre}</h5>
                        </div>
                        <p class='text-muted small mb-3'><i class='fas fa-phone-alt mr-1'></i> {$tel}</p>
                        
                        <div class='mb-3'>";
                            foreach ($tags as $tag) {
                                echo "<span class='badge p-2 mr-1' style='background-color: #FFE0B2; color: #C06000; font-weight: normal; border-radius: 8px;'>{$tag}</span>";
                            }
                            echo "  </div>
                        
                        <div class='mb-3'>
                            <span class='badge p-1 px-3' style='{$statusStyle} border-radius: 5px;'>{$status}</span>
                        </div>
                        
                        <div class='d-flex justify-content-between align-items-center mt-2'>
                            <small class='text-muted'>Último pedido: {$fecha}</small>
                            <button class='btn btn-sm btn-orange' style='background-color: #E8820C; color: white; border-radius: 8px; padding: 5px 15px;'>Ver detalle</button>
                        </div>
                    </div>
                </div>";
                        }

                        // Generamos los ejemplos de tu imagen
                        crearCardProveedor("Marinela", "55-1234-5678", ["Gansitos", "Submarinos"], "Activo", "28 Feb", "#E8820C");
                        crearCardProveedor("Bimbo", "55-8765-4321", ["Nitos", "Chocorroles"], "Activo", "25 Feb", "#28A745");
                        crearCardProveedor("Coca Cola", "55-4444-3333", ["Delaware", "Fanta"], "Reabastecer urgente", "10 Feb", "#C0392B");
                        crearCardProveedor("Gamesa", "55-9999-0000", ["Chokis", "Emperador"], "Activo", "01 Mar", "#28A745");
                        crearCardProveedor("Barcel", "55-7777-1111", ["Takis", "Big Mix"], "Activo", "27 Feb", "#28A745");
                        crearCardProveedor("Dist. Chidas", "55-2222-8888", ["Papas", "Nachos"], "Inactivo", "10 Feb", "#ced4da");
                        ?>
                    </div>
                </div>
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