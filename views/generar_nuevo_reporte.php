<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stock It | Generar Reporte</title>
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
        /* ===== NAVBAR ===== */
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
        .navbar-light .navbar-nav .nav-link { color: #ffffff !important; }

        /* ===== SIDEBAR ===== */
        .main-sidebar, .main-sidebar:before, .brand-link, .sidebar-dark-primary {
            background-color: #E8820C !important;
        }
        .nav-sidebar .nav-link, .brand-link .brand-text,
        .sidebar .user-panel .info a, .nav-sidebar .nav-link i { color: #ffffff !important; }
        .form-control-sidebar, .btn-sidebar {
            background-color: #ffffff !important;
            border: 1px solid #ddd !important;
            color: #333 !important;
        }
        .btn-sidebar i { color: #333 !important; }
        .user-panel, .form-inline { border-bottom: 1px solid rgba(255,255,255,0.2) !important; }

        /* ===== CONTENIDO ===== */
        .content-wrapper { background-color: #FFF5E5 !important; }
        .titulo-naranja { color: #E8820C; font-weight: bold; }

        /* ===== PANEL VISTA PREVIA — tiempo real ===== */
        .preview-box {
            background-color: #fdfdfd;
            border-radius: 10px;
            border: 1px solid #eee;
            padding: 15px;
        }
        .preview-row { margin-bottom: 10px; }
        .preview-row strong { display: block; font-size: 0.75rem; color: #888; }
        .preview-row span { font-weight: bold; color: #333; }
        .preview-row .val-naranja { color: #E8820C; font-weight: bold; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

<?php
    $paginaActiva = "productos"; // o clientes, reportes, ventas, etc.
     include __DIR__ . "/includes/barras.php";
    ?>
    <!-- ===== CONTENIDO ===== -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <p class="text-muted small">Reportes / <span class="titulo-naranja font-weight-bold">Generar nuevo reporte</span></p>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <!-- FORMULARIO -->
                    <div class="col-md-8">
                        <div class="card shadow-sm" style="border-radius: 15px; border-left: 5px solid #E8820C !important;">
                            <div class="card-header bg-white pt-4 border-0">
                                <h4 class="titulo-naranja font-weight-bold m-0"><i class="fas fa-chart-bar mr-2"></i> Generar nuevo reporte</h4>
                                <p class="text-muted small">Configura los parámetros del reporte</p>
                            </div>
                            <div class="card-body px-4">

                                <h6 class="titulo-naranja font-weight-bold mb-3">Tipo de reporte</h6>
                                <div class="form-group">
                                    <div class="custom-control custom-radio p-3 mb-2 border" style="border-radius:10px;">
                                        <input class="custom-control-input" type="radio" id="rep1" name="tipoReporte" value="Disponibilidad de stock">
                                        <label for="rep1" class="custom-control-label d-flex align-items-center">
                                            <i class="fas fa-boxes mr-2 text-muted"></i> Disponibilidad de stock
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio p-3 mb-2 border" style="border-radius:10px;">
                                        <input class="custom-control-input" type="radio" id="rep2" name="tipoReporte" value="Caducidades próximas">
                                        <label for="rep2" class="custom-control-label d-flex align-items-center">
                                            <i class="fas fa-hourglass-end mr-2 text-muted"></i> Caducidades próximas
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio p-3 mb-2 border" style="border-radius:10px; background-color:#FFF5E5; border-color:#FFE0B2 !important;">
                                        <input class="custom-control-input" type="radio" id="rep3" name="tipoReporte" value="Reporte general" checked>
                                        <label for="rep3" class="custom-control-label d-flex align-items-center font-weight-bold">
                                            <i class="fas fa-file-alt mr-2 titulo-naranja"></i> Reporte general
                                        </label>
                                    </div>
                                </div>

                                <h6 class="titulo-naranja font-weight-bold mt-4 mb-3">Período de tiempo</h6>
                                <div class="d-flex mb-3 flex-wrap">
                                    <button type="button" class="btn btn-outline-warning mr-2 mb-2 btn-periodo" data-periodo="Hoy" style="border-radius:8px; color:#E8820C; border-color:#FFE0B2;">Hoy</button>
                                    <button type="button" class="btn btn-outline-warning mr-2 mb-2 btn-periodo" data-periodo="Esta semana" style="border-radius:8px; color:#E8820C; border-color:#FFE0B2;">Esta semana</button>
                                    <button type="button" class="btn btn-periodo mr-2 mb-2 activo-periodo" data-periodo="Este mes" style="background-color:#E8820C; color:white; border-radius:8px; border:none;">Este mes</button>
                                    <button type="button" class="btn btn-outline-warning mb-2 btn-periodo" data-periodo="Este año" style="border-radius:8px; color:#E8820C; border-color:#FFE0B2;">Este año</button>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="small text-muted mb-1">Fecha inicio</label>
                                        <input type="date" id="fecha-inicio" class="form-control" value="2025-03-01" style="border-radius:8px; background-color:#f8f9fa;">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-muted mb-1">Fecha fin</label>
                                        <input type="date" id="fecha-fin" class="form-control" value="2025-03-31" style="border-radius:8px; background-color:#f8f9fa;">
                                    </div>
                                </div>

                                <h6 class="titulo-naranja font-weight-bold mb-3" style="margin-top:25px;">Filtros adicionales</h6>
                                <div class="row mb-3">
                                    <div class="col-md-6 form-group">
                                        <label class="small text-muted mb-1">Producto</label>
                                        <div class="position-relative">
                                            <select id="filtro-producto" class="form-control" style="border-radius:8px; background-color:#f8f9fa; height:45px; appearance:none;">
                                                <option value="Todos los productos" selected>Todos los productos</option>
                                                <option value="Gansito">Gansito</option>
                                                <option value="Coca Cola 600ml">Coca Cola 600ml</option>
                                            </select>
                                            <i class="fas fa-caret-down position-absolute" style="right:15px; top:15px; color:#6c757d; pointer-events:none;"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="small text-muted mb-1">Proveedor</label>
                                        <div class="position-relative">
                                            <select id="filtro-proveedor" class="form-control" style="border-radius:8px; background-color:#f8f9fa; height:45px; appearance:none;">
                                                <option value="Todos los proveedores" selected>Todos los proveedores</option>
                                                <option value="Marinela México">Marinela México</option>
                                                <option value="Bimbo">Bimbo</option>
                                            </select>
                                            <i class="fas fa-caret-down position-absolute" style="right:15px; top:15px; color:#6c757d; pointer-events:none;"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-6 form-group">
                                        <label class="small text-muted mb-1">Categoría</label>
                                        <div class="position-relative">
                                            <select id="filtro-categoria" class="form-control" style="border-radius:8px; background-color:#f8f9fa; height:45px; appearance:none;">
                                                <option value="Todas las categorías" selected>Todas las categorías</option>
                                                <option value="Pastelitos">Pastelitos</option>
                                                <option value="Bebidas">Bebidas</option>
                                                <option value="Botanas">Botanas</option>
                                            </select>
                                            <i class="fas fa-caret-down position-absolute" style="right:15px; top:15px; color:#6c757d; pointer-events:none;"></i>
                                        </div>
                                    </div>
                                </div>

                                <h6 class="titulo-naranja font-weight-bold mb-3">Formato de exportación</h6>
                                <div class="d-flex mb-4">
                                    <div class="custom-control custom-radio mr-4">
                                        <input class="custom-control-input" type="radio" id="f1" name="formato" value="PDF" checked>
                                        <label for="f1" class="custom-control-label">PDF</label>
                                    </div>
                                    <div class="custom-control custom-radio mr-4">
                                        <input class="custom-control-input" type="radio" id="f2" name="formato" value="Excel (.xlsx)">
                                        <label for="f2" class="custom-control-label">Excel (.xlsx)</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="f3" name="formato" value="CSV">
                                        <label for="f3" class="custom-control-label">CSV</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PANEL VISTA PREVIA EN TIEMPO REAL -->
                    <div class="col-md-4">
                        <div class="card shadow-sm" style="border-radius:15px; border-top:3px solid #FFE0B2; position:sticky; top:70px;">
                            <div class="card-header bg-white pt-4 border-0">
                                <h5 class="titulo-naranja font-weight-bold m-0"><i class="fas fa-eye mr-2"></i>Vista previa</h5>
                                <small class="text-muted">Se actualiza en tiempo real</small>
                            </div>
                            <div class="card-body">
                                <div class="preview-box mb-4">
                                    <p class="small text-muted mb-3">El reporte generará:</p>

                                    <div class="preview-row">
                                        <strong>Tipo</strong>
                                        <span class="val-naranja" id="prev-tipo">Reporte general</span>
                                    </div>
                                    <hr class="my-2" style="border-top:1px solid #f0f0f0;">
                                    <div class="preview-row">
                                        <strong>Período seleccionado</strong>
                                        <span id="prev-periodo">Este mes</span>
                                    </div>
                                    <hr class="my-2" style="border-top:1px solid #f0f0f0;">
                                    <div class="preview-row">
                                        <strong>Rango de fechas</strong>
                                        <span id="prev-fechas">01/03/2025 — 31/03/2025</span>
                                    </div>
                                    <hr class="my-2" style="border-top:1px solid #f0f0f0;">
                                    <div class="preview-row">
                                        <strong>Producto</strong>
                                        <span id="prev-producto">Todos</span>
                                    </div>
                                    <hr class="my-2" style="border-top:1px solid #f0f0f0;">
                                    <div class="preview-row">
                                        <strong>Proveedor</strong>
                                        <span id="prev-proveedor">Todos</span>
                                    </div>
                                    <hr class="my-2" style="border-top:1px solid #f0f0f0;">
                                    <div class="preview-row">
                                        <strong>Categoría</strong>
                                        <span id="prev-categoria">Todas</span>
                                    </div>
                                    <hr class="my-2" style="border-top:1px solid #f0f0f0;">
                                    <div class="preview-row">
                                        <strong>Formato</strong>
                                        <span id="prev-formato">PDF</span>
                                    </div>
                                </div>

                                <p class="small text-muted font-weight-bold mb-2">Incluirá:</p>
                                <ul class="list-unstyled small mb-4">
                                    <li class="mb-2 text-success"><i class="fas fa-check-circle mr-2"></i> Listado completo de productos</li>
                                    <li class="mb-2 text-success"><i class="fas fa-check-circle mr-2"></i> Movimientos del período</li>
                                    <li class="mb-2 text-success"><i class="fas fa-check-circle mr-2"></i> Gráfica de tendencia</li>
                                    <li class="mb-2 text-success"><i class="fas fa-check-circle mr-2"></i> Resumen por categoría</li>
                                </ul>

                                <button id="btn-exportar" class="btn btn-block py-3 shadow-sm" style="background-color:#E8820C; color:white; border-radius:12px; font-weight:bold; border:none;">
                                    <i class="fas fa-file-download mr-2"></i> Exportar reporte
                                </button>
                                <a href="?menu=reportes" id="btn-cancelar" class="btn btn-block py-3 shadow-sm" style="background-color:#6c757d; color:white; border-radius:12px; font-weight:bold; border:none; text-decoration:none;">
    <i class="fas fa-times mr-2"></i> Cancelar
</a>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
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

<!-- Scripts -->
<script src="public/plugins/jquery/jquery.min.js"></script>
<script src="public/plugins/jquery-ui/jquery-ui.min.js"></script>
<script>$.widget.bridge('uibutton', $.ui.button)</script>
<script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="public/dist/js/adminlte.js"></script>
<script src="public/dist/js/demo.js"></script>

<!-- ===== LÓGICA TIEMPO REAL ===== -->
<script>
$(function () {
    function formatearFecha(val) {
        if (!val) return '—';
        var p = val.split('-');
        return p[2] + '/' + p[1] + '/' + p[0];
    }

    function actualizarPreview() {
        // Tipo
        var tipo = $('input[name="tipoReporte"]:checked').closest('label').text().trim();
        $('#prev-tipo').text(tipo || '—');

        // Período activo
        var periodo = $('.activo-periodo').data('periodo') || '—';
        $('#prev-periodo').text(periodo);

        // Fechas
        var fi = formatearFecha($('#fecha-inicio').val());
        var ff = formatearFecha($('#fecha-fin').val());
        $('#prev-fechas').text(fi + ' — ' + ff);

        // Filtros
        var prod = $('#filtro-producto').val();
        $('#prev-producto').text(prod === 'Todos los productos' ? 'Todos' : prod);

        var prov = $('#filtro-proveedor').val();
        $('#prev-proveedor').text(prov === 'Todos los proveedores' ? 'Todos' : prov);

        var cat = $('#filtro-categoria').val();
        $('#prev-categoria').text(cat === 'Todas las categorías' ? 'Todas' : cat);

        // Formato
        var fmt = $('input[name="formato"]:checked').val();
        $('#prev-formato').text(fmt || '—');
    }

    // Botones período
    $('.btn-periodo').on('click', function () {
        $('.btn-periodo').removeClass('activo-periodo')
            .css({ 'background-color': '', 'color': '#E8820C', 'border': '1px solid #FFE0B2' });
        $(this).addClass('activo-periodo')
            .css({ 'background-color': '#E8820C', 'color': 'white', 'border': 'none' });
        actualizarPreview();
    });

    // Escuchar todos los cambios
    $('input[name="tipoReporte"], input[name="formato"]').on('change', actualizarPreview);
    $('#fecha-inicio, #fecha-fin, #filtro-producto, #filtro-proveedor, #filtro-categoria').on('change input', actualizarPreview);

    actualizarPreview();
});
</script>
</body>
</html>