<?php
function crearFilaColapsable($id, $titulo) { return ""; }
function crearLeyenda($color, $texto) { return ""; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stock It | Registrar Proveedor</title>
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
        .main-header.navbar, .main-header.navbar-white, .main-header.navbar-light, nav.main-header { background-color:#E8820C !important; border-bottom:none !important; }
        .main-header .nav-link, .main-header .nav-link i, .main-header .navbar-nav .nav-item a, .navbar-light .navbar-nav .nav-link { color:#ffffff !important; }
        .main-sidebar, .main-sidebar:before, .brand-link, .sidebar-dark-primary { background-color:#E8820C !important; }
        .nav-sidebar .nav-link, .brand-link .brand-text, .sidebar .user-panel .info a, .nav-sidebar .nav-link i { color:#ffffff !important; }
        .form-control-sidebar, .btn-sidebar { background-color:#ffffff !important; border:1px solid #ddd !important; color:#333 !important; }
        .btn-sidebar i { color:#333 !important; }
        .user-panel, .form-inline { border-bottom:1px solid rgba(255,255,255,0.2) !important; }
        .content-wrapper { background-color:#FFF5E5 !important; }
        .titulo-naranja { color:#E8820C; font-weight:bold; }
        .card-fila { background:white !important; border-radius:15px !important; border:1px solid #E0E0E0 !important; margin-bottom:15px !important; border-left:8px solid #E8820C !important; box-shadow:0 2px 4px rgba(0,0,0,0.05) !important; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="public/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
    </div>

<?php
$paginaActiva = "proveedores"; // o clientes, reportes, ventas, etc.
include __DIR__ . "/includes/barras.php";
    ?>

    <!-- CONTENIDO -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <p class="text-muted small">Proveedores / <span class="titulo-naranja font-weight-bold">Registrar Proveedor</span></p>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-fila shadow-sm" style="border-radius:15px; border-left:5px solid #E8820C !important;">
                    <div class="card-header bg-white pt-4 border-0">
                        <h4 class="titulo-naranja font-weight-bold m-0">Registrar nuevo proveedor</h4>
                        <p class="text-muted small">Datos de contacto y productos asociados</p>
                    </div>
                    <div class="card-body px-4">

                        <h6 class="titulo-naranja font-weight-bold mb-3" style="border-bottom:1px solid #FFE0B2; padding-bottom:5px;">Datos del proveedor</h6>
                        <div class="row mb-3">
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold">Nombre / Razón social *</label>
                                <input type="text" class="form-control" placeholder="Ej. Marinela S.A." style="border-radius:8px;">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold">Teléfono *</label>
                                <input type="text" class="form-control" placeholder="55-1234-5678" style="border-radius:8px;">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold">Correo electrónico *</label>
                                <input type="email" class="form-control" placeholder="contacto@empresa.com" style="border-radius:8px;">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold">Sitio web</label>
                                <input type="text" class="form-control" placeholder="www.proveedor.com" style="border-radius:8px;">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-3 form-group">
                                <label class="small font-weight-bold">Estado *</label>
                                <select class="form-control" style="border-radius:8px;">
                                    <option>Activo</option>
                                    <option>Inactivo</option>
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="small font-weight-bold">Días de entrega</label>
                                <input type="text" class="form-control" placeholder="0 días" style="border-radius:8px;">
                            </div>
                        </div>

                        <h6 class="titulo-naranja font-weight-bold mb-3" style="border-bottom:1px solid #FFE0B2; padding-bottom:5px;">Productos que suministra</h6>
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control border-left-0" placeholder="Buscar y agregar productos..." style="border-radius:0 8px 8px 0;">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-block" style="background-color:#E8820C; color:white; border-radius:8px; font-weight:bold;">+ Agregar</button>
                            </div>
                        </div>
                        <div class="mb-4" id="tags-productos">
                            <span class="badge p-2 px-3 mr-2" style="background-color:#FFF5E5; color:#E8820C; border:1px solid #FFE0B2; border-radius:20px; font-weight:normal;">Gansitos</span>
                            <span class="badge p-2 px-3 mr-2" style="background-color:#FFF5E5; color:#E8820C; border:1px solid #FFE0B2; border-radius:20px; font-weight:normal;">Submarinos</span>
                            <span class="badge p-2 px-3 mr-2" style="background-color:#FFF5E5; color:#E8820C; border:1px solid #FFE0B2; border-radius:20px; font-weight:normal;">Mantecadas</span>
                            <span class="badge p-2 px-3 mr-2" style="background-color:#FFF5E5; color:#E8820C; border:1px solid #FFE0B2; border-radius:20px; font-weight:normal;">Chocorroles</span>
                        </div>

                        <h6 class="titulo-naranja font-weight-bold mb-3" style="border-bottom:1px solid #FFE0B2; padding-bottom:5px;">Condiciones comerciales</h6>
                        <div class="row mb-5">
                            <div class="col-md-4 form-group">
                                <label class="small font-weight-bold">Frecuencia de pedido</label>
                                <select class="form-control" style="border-radius:8px;">
                                    <option>Seleccionar</option>
                                    <option>Semanal</option>
                                    <option>Quincenal</option>
                                    <option>Mensual</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label class="small font-weight-bold">Pedido mínimo</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                    <input type="text" class="form-control" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label class="small font-weight-bold">Crédito (días)</label>
                                <input type="text" class="form-control" placeholder="0 días" style="border-radius:8px;">
                            </div>
                        </div>

                        <div class="mt-4 mb-3">
                            <button type="submit" class="btn px-4 mr-2" style="background-color:#E8820C; color:white; border-radius:10px; font-weight:bold; height:45px;">
                                <i class="fas fa-save mr-2"></i>Registrar proveedor
                            </button>
                            <a href="?menu=proveedores" class="btn btn-outline-secondary px-4" style="border-radius:10px; height:45px; line-height:30px;">Cancelar</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
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
<script src="public/dist/js/demo.js"></script>
</body>
</html>