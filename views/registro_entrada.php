<?php
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stock It | Registrar Entrada</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../public/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="../public/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="../public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../public/plugins/jqvmap/jqvmap.min.css">
    <link rel="stylesheet" href="../public/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="../public/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="../public/plugins/summernote/summernote-bs4.min.css">

    <style>
        /* ===== NAVBAR SUPERIOR ===== */
        .main-header.navbar,
        .main-header.navbar-white,
        .main-header.navbar-light,
        nav.main-header {
            background-color: #E8820C !important;
            border-bottom: none !important;
        }

        .main-header .nav-link,
        .main-header .nav-link i,
        .main-header .navbar-nav .nav-item a,
        .navbar-light .navbar-nav .nav-link {
            color: #ffffff !important;
        }

        /* ===== SIDEBAR ===== */
        .main-sidebar,
        .main-sidebar:before,
        .brand-link,
        .sidebar-dark-primary {
            background-color: #E8820C !important;
        }

        .nav-sidebar .nav-link,
        .brand-link .brand-text,
        .sidebar .user-panel .info a,
        .nav-sidebar .nav-link i {
            color: #ffffff !important;
        }

        .form-control-sidebar,
        .btn-sidebar {
            background-color: #ffffff !important;
            border: 1px solid #ddd !important;
            color: #333 !important;
        }

        .btn-sidebar i {
            color: #333 !important;
        }

        /* ===== FONDO DE PÁGINA ===== */
        .content-wrapper {
            background-color: #FFF5E5 !important;
        }

        .user-panel,
        .form-inline {
            border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
        }

        /* ===== CARD FORMULARIO ===== */
        .card-fila {
            background: white !important;
            border-radius: 15px !important;
            border: 1px solid #E0E0E0 !important;
            margin-bottom: 15px !important;
            border-left: 8px solid #E8820C !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05) !important;
        }

        .titulo-naranja {
            color: #E8820C;
            font-weight: bold;
        }

        /* ===== PANEL DERECHO ===== */
        .panel-derecho {
            background-color: white !important;
            border-radius: 15px !important;
            border: 1px solid #FFE0B2 !important;
            padding: 0px !important;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05) !important;
            position: sticky;
            top: 70px;
        }

        .panel-derecho-header {
            background-color: #FFF5E5 !important;
            padding: 15px 20px !important;
            border-bottom: 1px solid #FFE0B2 !important;
        }

        .titulo-panel {
            color: #C06000 !important;
            font-weight: bold !important;
            font-size: 1.1rem !important;
            margin: 0 !important;
        }

        .panel-derecho-body {
            padding: 20px !important;
        }

        /* ===== RESUMEN EN TIEMPO REAL ===== */
        .resumen-producto-box {
            background-color: #FFF5E5;
            border-radius: 10px;
            border: 1px dashed #FFE0B2;
            padding: 12px 15px;
            min-height: 50px;
        }

        .resumen-valor {
            font-size: 1.5rem;
            font-weight: bold;
            color: #E8820C;
        }

        .resumen-stock-resultante {
            font-size: 1.5rem;
            font-weight: bold;
            color: #28a745;
        }

        .badge-estado {
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 20px;
        }

        /* Animación de actualización */
        @keyframes flashUpdate {
            0% { background-color: #fff3cd; }
            100% { background-color: transparent; }
        }

        .updated {
            animation: flashUpdate 0.5s ease-out;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../public/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
        </div>

        <!-- ===== NAVBAR (igual al archivo 1) ===== -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="../index.php" class="nav-link">Home</a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
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
            </ul>
        </nav>

        <!-- ===== SIDEBAR (igual al archivo 1) ===== -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="index3.html" class="brand-link">
                <img src="../public/dist/img/logoStock.png" alt="Stock It Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Stock It</span>
            </a>

            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="../public/dist/img/mininico.jpeg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">Nicol Valentina</a>
                    </div>
                </div>

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

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                         <li class="nav-item">
              <a href="GestiondeProductos.php" class="nav-link">
                <i class="ion-bag"></i>
                <p>
                  Productos
                  <span class="right badge badge-danger">New</span>
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="analisisProductos.php" class="nav-link">
                <i class="ion-pie-graph"></i>
                <p>
                  Analisis de Productos
                  <span class="right badge badge-danger">New</span>
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="Clientes.php" class="nav-link">
                <i class="ion-ios-people"></i>
                <p>
                  Clientes
                  <span class="right badge badge-danger">New</span>
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="analisisProd.php" class="nav-link">
                <i class="ion-clipboard"></i>
                <p>
                  Inventario
                  <span class="right badge badge-danger">New</span>
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="Proveedores.php" class="nav-link">
                <i class="ion-briefcase"></i>
                <p>
                  Proveedores
                  <span class="right badge badge-danger">New</span>
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="modulo_reportes.php" class="nav-link">
                <i class="ion-document-text"></i>
                <p>
                  Reportes
                  <span class="right badge badge-danger">New</span>
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="ventas.php" class="nav-link">
                <i class="ion-cash"></i>
                <p>
                  Tipo de Ventas
                  <span class="right badge badge-danger">New</span>
                </p>
              </a>
            </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- ===== CONTENIDO PRINCIPAL ===== -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row align-items-center mb-4">
                        <div class="col-md-6">
                            <h1 class="m-0">Registrar <span class="titulo-naranja">Entrada</span></h1>
                            <p class="text-muted m-0">Registra el ingreso de productos al inventario</p>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">

                        <!-- ===== FORMULARIO (izquierda) ===== -->
                        <div class="col-md-8">
                            <div class="card card-fila" style="padding: 0px; border-radius: 15px; overflow: hidden;">
                                <div class="p-3" style="border-bottom: 1px solid #FFE0B2;">
                                    <h5 class="titulo-naranja m-0">
                                        <i class="fas fa-truck-loading mr-2" style="color: #007BFF;"></i> Registrar Entrada
                                    </h5>
                                    <small class="text-muted">Ingresa los datos del lote recibido</small>
                                </div>

                                <div class="card-body p-4">
                                    <h6 class="text-warning font-weight-bold mb-3" style="color: #E8820C !important; border-bottom: 1px solid #FFE0B2; padding-bottom: 5px;">Información del producto</h6>

                                    <div class="form-group position-relative mb-3">
                                        <i class="fas fa-search position-absolute" style="left: 15px; top: 12px; color: #ccc;"></i>
                                        <input type="text" id="buscar-producto" class="form-control pl-5" placeholder="Buscar producto por nombre o código..." style="border-radius: 8px;" autocomplete="off">
                                    </div>

                                    <div class="d-flex align-items-center p-3 mb-4" style="background-color: #E7F1FF; border: 1px solid #B6D4FE; border-radius: 10px;">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 45px; height: 45px;">
                                            <i class="fas fa-box text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="m-0 font-weight-bold" style="color: #0056b3;" id="info-nombre-cod">Gansito Marinela — Cód: 7501234567890</h6>
                                            <small class="text-muted">Stock actual: <span class="text-primary" id="info-stock-actual">240 pzs</span> | Stock mínimo: <span id="info-stock-min">50 pzs</span></small>
                                        </div>
                                    </div>

                                    <h6 class="text-warning font-weight-bold mb-4" style="color: #E8820C !important; border-bottom: 1px solid #FFE0B2; padding-bottom: 5px;">Detalles de la entrada</h6>

                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <label class="font-weight-bold mb-2">Cantidad recibida *</label>
                                            <input type="number" id="input-cantidad" class="form-control form-control-lg" placeholder="0" min="0" style="border-radius: 12px; height: 50px;">
                                        </div>

                                        <div class="col-md-4 form-group">
                                            <label class="font-weight-bold mb-2">Unidad</label>
                                            <select id="input-unidad" class="form-control form-control-lg" style="border-radius: 12px; height: 50px; appearance: auto;">
                                                <option>Piezas</option>
                                                <option>Cajas</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4 form-group">
                                            <label class="font-weight-bold mb-2">Costo unitario</label>
                                            <div class="input-group" style="border-radius: 12px; overflow: hidden; border: 1px solid #ced4da; height: 50px;">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="background-color: #e9ecef; border: none; padding: 0 20px;">$</span>
                                                </div>
                                                <input type="number" id="input-costo" class="form-control form-control-lg" placeholder="0.00" min="0" step="0.01" style="border: none; height: 100%;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4 form-group">
                                            <label class="font-weight-bold mb-2">Fecha de entrada *</label>
                                            <input type="date" class="form-control form-control-lg" style="border-radius: 12px; height: 50px;">
                                        </div>

                                        <div class="col-md-4 form-group">
                                            <label class="font-weight-bold mb-2">Fecha caducidad</label>
                                            <input type="date" id="input-caducidad" class="form-control form-control-lg" style="border-radius: 12px; height: 50px;">
                                        </div>

                                        <div class="col-md-4 form-group">
                                            <label class="font-weight-bold mb-2">No. de lote</label>
                                            <input type="text" id="input-lote" class="form-control form-control-lg" placeholder="LOTE-001" style="border-radius: 12px; height: 50px;">
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-8 form-group">
                                            <label class="font-weight-bold mb-2">Proveedor</label>
                                            <select id="input-proveedor" class="form-control form-control-lg" style="border-radius: 12px; height: 50px; appearance: auto;">
                                                <option value="">Seleccionar proveedor</option>
                                                <option>Marinela México</option>
                                                <option>Bimbo</option>
                                                <option>PepsiCo</option>
                                                <option>Coca-Cola FEMSA</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4 form-group">
                                            <label class="font-weight-bold mb-2">No. de factura</label>
                                            <input type="text" id="input-factura" class="form-control form-control-lg" placeholder="FAC-2025-001" style="border-radius: 12px; height: 50px;">
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 form-group">
                                            <label class="font-weight-bold mb-2">Notas</label>
                                            <textarea class="form-control" rows="3" placeholder="Observaciones de la entrada..." style="border-radius: 12px;"></textarea>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-lg px-5" style="background-color: #E8820C; color: white; border-radius: 12px; font-weight: bold;">
                                                <i class="fas fa-save mr-2"></i>Registrar entrada
                                            </button>
                                            <button type="button" class="btn btn-lg btn-outline-secondary px-5 ml-2" style="border-radius: 12px;">Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ===== PANEL DERECHO — RESUMEN EN TIEMPO REAL ===== -->
                        <div class="col-md-4">
                            <div class="panel-derecho card elevation-1">
                                <div class="panel-derecho-header">
                                    <h5 class="titulo-panel"><i class="fas fa-clipboard-list mr-2"></i>Resumen de entrada</h5>
                                    <small class="text-muted">Se actualiza en tiempo real</small>
                                </div>
                                <div class="panel-derecho-body">

                                    <!-- Producto -->
                                    <div class="mb-3">
                                        <label class="small text-muted d-block mb-1"><i class="fas fa-box mr-1"></i>Producto</label>
                                        <div class="resumen-producto-box" id="res-producto">
                                            <span class="text-muted">— Sin seleccionar —</span>
                                        </div>
                                    </div>

                                    <!-- Proveedor -->
                                    <div class="mb-3">
                                        <label class="small text-muted d-block mb-1"><i class="fas fa-truck mr-1"></i>Proveedor</label>
                                        <div class="resumen-producto-box" id="res-proveedor">
                                            <span class="text-muted">—</span>
                                        </div>
                                    </div>

                                    <!-- Lote y Factura -->
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <label class="small text-muted d-block mb-1"><i class="fas fa-barcode mr-1"></i>No. Lote</label>
                                            <div class="resumen-producto-box" style="padding: 8px 12px;" id="res-lote">
                                                <span class="text-muted">—</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label class="small text-muted d-block mb-1"><i class="fas fa-file-invoice mr-1"></i>Factura</label>
                                            <div class="resumen-producto-box" style="padding: 8px 12px;" id="res-factura">
                                                <span class="text-muted">—</span>
                                            </div>
                                        </div>
                                    </div>

                                    <hr style="border-color: #FFE0B2;">

                                    <!-- Cantidad y Costo -->
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <label class="small text-muted d-block mb-1">Cantidad:</label>
                                            <div class="resumen-valor" id="res-cantidad">0 pzs</div>
                                        </div>
                                        <div class="col-6">
                                            <label class="small text-muted d-block mb-1">Costo unitario:</label>
                                            <div class="resumen-valor" id="res-costo-unit">$0.00</div>
                                        </div>
                                    </div>

                                    <!-- Costo Total -->
                                    <div class="mb-3 p-3" style="background: #FFF5E5; border-radius: 10px; border: 1px solid #FFE0B2;">
                                        <label class="small text-muted d-block mb-1">Costo total:</label>
                                        <div style="font-size: 2rem; font-weight: bold; color: #E8820C;" id="res-costo-total">$0.00</div>
                                    </div>

                                    <hr style="border-color: #FFE0B2;">

                                    <!-- Stock actual y resultante -->
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <label class="small text-muted d-block mb-1">Stock actual:</label>
                                            <div style="font-size: 1.2rem; font-weight: bold; color: #555;" id="res-stock-actual">240 pzs</div>
                                        </div>
                                        <div class="col-6">
                                            <label class="small text-muted d-block mb-1">Stock resultante:</label>
                                            <div class="resumen-stock-resultante" id="res-stock-resultante">240 pzs</div>
                                        </div>
                                    </div>

                                    <!-- Caducidad -->
                                    <div class="mb-2">
                                        <label class="small text-muted d-block mb-1"><i class="fas fa-calendar-times mr-1"></i>Fecha caducidad:</label>
                                        <div class="resumen-producto-box" id="res-caducidad">
                                            <span class="text-muted">— Sin especificar —</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- /.col-md-4 -->

                    </div>
                </div>
            </section>
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 3.1.0
            </div>
        </footer>

        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>

    <!-- Scripts -->
    <script src="../public/plugins/jquery/jquery.min.js"></script>
    <script src="../public/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script>$.widget.bridge('uibutton', $.ui.button)</script>
    <script src="../public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../public/plugins/chart.js/Chart.min.js"></script>
    <script src="../public/plugins/sparklines/sparkline.js"></script>
    <script src="../public/plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="../public/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <script src="../public/plugins/jquery-knob/jquery.knob.min.js"></script>
    <script src="../public/plugins/moment/moment.min.js"></script>
    <script src="../public/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="../public/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="../public/plugins/summernote/summernote-bs4.min.js"></script>
    <script src="../public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <script src="../public/dist/js/adminlte.js"></script>
    <script src="../public/dist/js/demo.js"></script>

    <!-- ===== LÓGICA DEL RESUMEN EN TIEMPO REAL ===== -->
    <script>
        // Datos simulados del producto seleccionado (esto vendría de tu BD via AJAX)
        const productoActual = {
            nombre: "Gansito Marinela",
            codigo: "7501234567890",
            stockActual: 240,
            stockMin: 50,
            unidad: "pzs"
        };

        // Función para flashear una celda cuando se actualiza
        function flashUpdate(elementId) {
            const el = document.getElementById(elementId);
            if (el) {
                el.classList.remove('updated');
                void el.offsetWidth; // reflow
                el.classList.add('updated');
            }
        }

        // Función principal: recalcula y actualiza el resumen
        function actualizarResumen() {
            const cantidad    = parseFloat(document.getElementById('input-cantidad').value) || 0;
            const costoUnit   = parseFloat(document.getElementById('input-costo').value)    || 0;
            const unidad      = document.getElementById('input-unidad').value;
            const proveedor   = document.getElementById('input-proveedor').value;
            const lote        = document.getElementById('input-lote').value.trim();
            const factura     = document.getElementById('input-factura').value.trim();
            const caducidad   = document.getElementById('input-caducidad').value;

            const costoTotal     = cantidad * costoUnit;
            const stockResultante = productoActual.stockActual + cantidad;

            // ---- Cantidad ----
            document.getElementById('res-cantidad').textContent =
                cantidad > 0 ? `${cantidad} ${unidad.toLowerCase()}` : `0 ${unidad.toLowerCase()}`;
            flashUpdate('res-cantidad');

            // ---- Costo unitario ----
            document.getElementById('res-costo-unit').textContent =
                `$${costoUnit.toFixed(2)}`;
            flashUpdate('res-costo-unit');

            // ---- Costo total ----
            document.getElementById('res-costo-total').textContent =
                `$${costoTotal.toFixed(2)}`;
            flashUpdate('res-costo-total');

            // ---- Stock resultante ----
            const resStockEl = document.getElementById('res-stock-resultante');
            resStockEl.textContent = `${stockResultante} ${productoActual.unidad}`;
            resStockEl.style.color = stockResultante <= productoActual.stockMin ? '#dc3545' : '#28a745';
            flashUpdate('res-stock-resultante');

            // ---- Stock actual ----
            document.getElementById('res-stock-actual').textContent =
                `${productoActual.stockActual} ${productoActual.unidad}`;

            // ---- Proveedor ----
            document.getElementById('res-proveedor').innerHTML =
                proveedor ? `<strong>${proveedor}</strong>` : '<span class="text-muted">—</span>';

            // ---- Lote ----
            document.getElementById('res-lote').innerHTML =
                lote ? `<strong>${lote}</strong>` : '<span class="text-muted">—</span>';

            // ---- Factura ----
            document.getElementById('res-factura').innerHTML =
                factura ? `<strong>${factura}</strong>` : '<span class="text-muted">—</span>';

            // ---- Caducidad ----
            if (caducidad) {
                const fecha = new Date(caducidad + 'T00:00:00');
                const hoy   = new Date();
                const diffDias = Math.ceil((fecha - hoy) / (1000 * 60 * 60 * 24));
                let color = '#28a745';
                let alerta = '';
                if (diffDias < 0)       { color = '#dc3545'; alerta = ' ⚠ Vencida'; }
                else if (diffDias <= 30) { color = '#ffc107'; alerta = ` ⚠ ${diffDias} días`; }
                document.getElementById('res-caducidad').innerHTML =
                    `<strong style="color:${color};">${fecha.toLocaleDateString('es-MX')}${alerta}</strong>`;
            } else {
                document.getElementById('res-caducidad').innerHTML =
                    '<span class="text-muted">— Sin especificar —</span>';
            }
        }

        // Escuchar cambios en todos los campos del formulario
        document.addEventListener('DOMContentLoaded', function () {
            const campos = ['input-cantidad', 'input-costo', 'input-unidad',
                            'input-proveedor', 'input-lote', 'input-factura', 'input-caducidad'];
            campos.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input',  actualizarResumen);
                    el.addEventListener('change', actualizarResumen);
                }
            });

            // Inicializar con los datos del producto actual
            document.getElementById('res-producto').innerHTML =
                `<strong>${productoActual.nombre}</strong><br>
                 <small class="text-muted">Cód: ${productoActual.codigo}</small>`;
            document.getElementById('res-stock-actual').textContent =
                `${productoActual.stockActual} ${productoActual.unidad}`;
            document.getElementById('res-stock-resultante').textContent =
                `${productoActual.stockActual} ${productoActual.unidad}`;

            // También actualizar el buscador si el usuario escribe
            document.getElementById('buscar-producto').addEventListener('input', function () {
                // Aquí irías a buscar el producto a tu BD con AJAX.
                // Por ahora simulamos con los datos fijos.
                document.getElementById('info-nombre-cod').textContent =
                    `${productoActual.nombre} — Cód: ${productoActual.codigo}`;
                document.getElementById('info-stock-actual').textContent =
                    `${productoActual.stockActual} pzs`;
                document.getElementById('info-stock-min').textContent =
                    `${productoActual.stockMin} pzs`;
                actualizarResumen();
            });

            actualizarResumen();
        });
    </script>

</body>
</html>