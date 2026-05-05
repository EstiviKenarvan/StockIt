<?php
require_once "../config/conexion.php";

$totalStock = $conexion->query("SELECT SUM(stock_actual) FROM productos")->fetchColumn();
$alertas = $conexion->query("SELECT COUNT(*) FROM productos WHERE stock_actual <= stock_minimo")->fetchColumn();
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
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Dashboard</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="public/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="public/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="public/plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="public/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="public/plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="public/plugins/summernote/summernote-bs4.min.css">

    <style>
        /* 1. MENÚ Y BARRA SUPERIOR (NARANJA STOCKIT) */
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

        /* 2. FONDO GENERAL */
        .content-wrapper {
            background-color: #FFF5E5 !important;
        }

        /* 3. FILAS ROSADAS (BAJA ROTACIÓN) */
        .card-fila-roja {
            background: #FCE4E4 !important;
            border-radius: 15px !important;
            border: 1px solid #F5C6CB !important;
            margin-bottom: 15px !important;
            border-left: 8px solid #D92323 !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05) !important;
        }

        .icono-collapse {
            float: right;
            padding: 5px;
            border-radius: 50%;
        }

        /* 4. PANEL DERECHO ROJO */
        .panel-derecho {
            background-color: white !important;
            border-radius: 15px !important;
            border: 1px solid #F5C6CB !important;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05) !important;
        }

        .panel-derecho-header {
            background-color: #FCE4E4 !important;
            /* Header Rosado */
            padding: 15px 20px !important;
            border-bottom: 1px solid #F5C6CB !important;
        }

        .titulo-panel-rojo {
            color: #D92323 !important;
            font-weight: bold !important;
            margin: 0 !important;
        }

        .grafico-circular-rosado {
            width: 170px;
            height: 170px;
            border-radius: 50%;
            background-color: #FCE4E4;
            border: 2px solid #F5C6CB;
            margin: 20px auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* 5. BOTONES Y BUSCADOR */
        .btn-rojo-activo {
            background-color: #D92323 !important;
            color: white !important;
            border-radius: 10px;
            font-weight: bold;
            margin-right: 5px;
        }

        .btn-outline-naranja {
            border: 1px solid #E8820C;
            color: #E8820C;
            border-radius: 10px;
        }

        .input-busqueda {
            border-radius: 20px;
            padding-left: 35px;
        }

        .leyenda-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .color-box {
            width: 15px;
            height: 15px;
            border-radius: 3px;
            margin-right: 10px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="public/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
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
                                <img src="public/dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
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
                                <img src="public/dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
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
                                <img src="public/dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
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
                <img src="public/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Stock It</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="public/dist/img/persona 1.jpg" class="img-circle elevation-2" alt="User Image">
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
                            <h1 class="m-0">Análisis de <span class="titulo-naranja">Productos</span></h1>
                            <p class="text-muted">Productos con menor movimiento</p>
                        </div>
                        <div class="col-md-6 text-right d-flex align-items-center justify-content-end">
                            <div class="position-relative d-inline-block mr-3" style="width: 200px;">
                                <i class="fas fa-search" style="position: absolute; left: 10px; top: 10px; color: #ccc;"></i>
                                <input type="text" class="form-control" style="padding-left: 30px; border-radius: 8px;" placeholder="Buscar...">
                            </div>

                            <button class="btn btn-outline-dark mr-2" style="border-radius: 8px; font-weight: 500; white-space: nowrap;">
                                + Registrar Salida
                            </button>
                            <button class="btn" style="background-color: #F39C12; color: white; border-radius: 8px; font-weight: 500; white-space: nowrap;">
                                + Registrar Entrada
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <section class="content">
                            <div class="container-fluid">

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
                            </div>
                        </section>
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
                <script src="public/plugins/jquery/jquery.min.js"></script>
                <!-- jQuery UI 1.11.4 -->
                <script src="public/plugins/jquery-ui/jquery-ui.min.js"></script>
                <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
                <script>
                    $.widget.bridge('uibutton', $.ui.button)
                </script>
                <!-- Bootstrap 4 -->
                <script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
                <!-- ChartJS -->
                <script src="public/plugins/chart.js/Chart.min.js"></script>
                <!-- Sparkline -->
                <script src="public/plugins/sparklines/sparkline.js"></script>
                <!-- JQVMap -->
                <script src="public/plugins/jqvmap/jquery.vmap.min.js"></script>
                <script src="public/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
                <!-- jQuery Knob Chart -->
                <script src="public/plugins/jquery-knob/jquery.knob.min.js"></script>
                <!-- daterangepicker -->
                <script src="public/plugins/moment/moment.min.js"></script>
                <script src="public/plugins/daterangepicker/daterangepicker.js"></script>
                <!-- Tempusdominus Bootstrap 4 -->
                <script src="public/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
                <!-- Summernote -->
                <script src="public/plugins/summernote/summernote-bs4.min.js"></script>
                <!-- overlayScrollbars -->
                <script src="public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
                <!-- AdminLTE App -->
                <script src="public/dist/js/adminlte.js"></script>
                <!-- AdminLTE for demo purposes -->
                <script src="public/dist/js/demo.js"></script>
                <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
                <script src="public/dist/js/pages/dashboard.js"></script>
                <script>
                    $(function() {
                        // 1. GRÁFICA DE MOVIMIENTOS SEMANALES (BARRAS)
                        var barChartCanvas = $('#barChartMovimientos').get(0).getContext('2d');
                        var barChartData = {
                            labels: ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'],
                            datasets: [{
                                    label: 'Entradas',
                                    backgroundColor: '#F39C12',
                                    data: [65, 59, 80, 81, 56, 55, 40]
                                },
                                {
                                    label: 'Salidas',
                                    backgroundColor: '#D35400',
                                    data: [28, 48, 40, 19, 86, 27, 90]
                                }
                            ]
                        };

                        new Chart(barChartCanvas, {
                            type: 'bar',
                            data: barChartData,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }
                            }
                        });

                        // 2. GRÁFICA DE STOCK POR CATEGORÍA (DONA)
                        var ctxDona = document.getElementById('graficoStockCategoria').getContext('2d');
                        new Chart(ctxDona, {
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
                                legend: {
                                    display: false
                                }
                            }
                        });
                    });
                </script>
</body>

</html>