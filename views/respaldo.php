<?php
include __DIR__ . "/includes/tarjeta.php";
include __DIR__ . "/includes/tablas.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockIt | Respaldo</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="public/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="public/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .main-header.navbar,.main-header.navbar-white,.main-header.navbar-light,nav.main-header{background-color:#E8820C!important;border-bottom:none!important}
        .main-header .nav-link,.main-header .nav-link i,.navbar-light .navbar-nav .nav-link{color:#fff!important}
        .main-sidebar,.main-sidebar:before,.brand-link,.sidebar-dark-primary{background-color:#E8820C!important}
        .nav-sidebar .nav-link{background:transparent!important;color:#fff!important}
        .nav-sidebar .nav-link:hover{background-color:rgba(255,255,255,.15)!important}
        .nav-sidebar .nav-link.active{background-color:rgba(0,0,0,.15)!important;color:#fff!important}
        .nav-sidebar .nav-link p,.nav-sidebar .nav-link i{color:#fff!important}
        .brand-link .brand-text,.sidebar .user-panel .info a{color:#fff!important}
        .form-control-sidebar,.btn-sidebar{background-color:#fff!important;border:1px solid #ddd!important;color:#333!important}
        .user-panel,.form-inline{border-bottom:1px solid rgba(255,255,255,.2)!important}
        .content-wrapper{background-color:#FFF5E5!important}
        .text-stockit{color:#E8820C!important}

        .modal-header-stockit{background-color:#E8820C;color:white;border-radius:5px 5px 0 0}
        .modal-header-stockit .close{color:white;opacity:1}

        .badge-exportacion{background-color:#28a745;color:#fff;font-size:12px;padding:5px 10px;border-radius:4px}
        .badge-importacion{background-color:#ffc107;color:#333;font-size:12px;padding:5px 10px;border-radius:4px}

        .reporte-box{
            background:#f8f9fa;
            border-left: 4px solid #E8820C;
            border-radius: 4px;
            padding: 16px 18px;
            font-size: 14px;
            color: #444;
            white-space: pre-line;
            line-height: 1.7;
        }
        .reporte-meta span {
            display: inline-block;
            margin-right: 18px;
            font-size: 13px;
            color: #777;
        }
        .reporte-meta i { color: #E8820C; margin-right: 4px; }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <?php $paginaActiva = "respaldo"; include __DIR__ . "/includes/barras.php"; ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-3 align-items-end">
                    <div class="col-sm-6">
                        <h1 class="m-0 d-inline" style="font-weight:bold">Control de</h1>
                        <h1 class="m-0 d-inline text-stockit" style="font-style:italic"> Respaldos</h1>
                        <p class="text-muted m-0">Exporta, importa y consulta el historial de respaldos</p>
                    </div>
                    <div class="col-sm-6 d-flex justify-content-end" style="gap:8px">
                        <a href="index.php?menu=respaldo&submenu=exportar"
                           class="btn btn-success" id="btnExportar">
                            <i class="fas fa-download mr-1"></i> Generar Respaldo
                        </a>
                        <button class="btn btn-outline-warning"
                                data-toggle="modal" data-target="#modalImportar">
                            <i class="fas fa-upload mr-1"></i> Importar Respaldo
                        </button>
                    </div>
                </div>

                <?php if (isset($_SESSION['respaldo_ok'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?= htmlspecialchars($_SESSION['respaldo_ok']) ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                    <?php unset($_SESSION['respaldo_ok']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['respaldo_error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?= htmlspecialchars($_SESSION['respaldo_error']) ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                    <?php unset($_SESSION['respaldo_error']); ?>
                <?php endif; ?>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card shadow-sm border-top border-warning">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title text-stockit font-weight-bold mb-0">
                            <i class="fas fa-history mr-1"></i> Historial de Respaldos
                        </h5>
                        <span class="badge badge-warning text-dark">
                            <?= count($historial) ?> registros
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover text-center mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Operación</th>
                                        <th>Archivo</th>
                                        <th>Tamaño</th>
                                        <th>Usuario</th>
                                        <th>Fecha y Hora</th>
                                        <th>Reporte</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($historial)): ?>
                                        <tr>
                                            <td colspan="7" class="text-muted py-4">
                                                <i class="fas fa-inbox mr-2"></i>No hay respaldos registrados aún.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($historial as $i => $r): ?>
                                            <tr>
                                                <td class="text-muted align-middle"><?= $i + 1 ?></td>
                                                <td class="align-middle">
                                                    <?php if ($r['operacion'] === 'EXPORTACION'): ?>
                                                        <span class="badge-exportacion">
                                                            <i class="fas fa-download mr-1"></i> EXPORTACIÓN
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge-importacion">
                                                            <i class="fas fa-upload mr-1"></i> IMPORTACIÓN
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="align-middle text-left">
                                                    <i class="fas fa-file-alt text-muted mr-1"></i>
                                                    <?= htmlspecialchars($r['archivo']) ?>
                                                </td>
                                                <td class="align-middle"><?= htmlspecialchars($r['tamano']) ?></td>
                                                <td class="align-middle font-weight-bold">
                                                    <?= htmlspecialchars($r['usuario']) ?>
                                                </td>
                                                <td class="align-middle text-muted">
                                                    <?= date('d/m/Y H:i:s', strtotime($r['fecha_hora'])) ?>
                                                </td>
                                                <td class="align-middle">
                                                    <button class="btn btn-sm btn-secondary btn-ver-reporte"
                                                            data-folio="<?= $i + 1 ?>"
                                                            data-operacion="<?= $r['operacion'] ?>"
                                                            data-archivo="<?= htmlspecialchars($r['archivo']) ?>"
                                                            data-tamano="<?= htmlspecialchars($r['tamano']) ?>"
                                                            data-usuario="<?= htmlspecialchars($r['usuario']) ?>"
                                                            data-fecha="<?= date('d/m/Y H:i:s', strtotime($r['fecha_hora'])) ?>"
                                                            data-notas="<?= htmlspecialchars($r['notas'] ?? 'Sin notas disponibles.') ?>">
                                                        <i class="fas fa-eye mr-1"></i> Ver
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- ══════════════════════════════════════════
         MODAL: VER REPORTE
    ══════════════════════════════════════════ -->
    <div class="modal fade" id="modalReporte" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-stockit">
                    <h5 class="modal-title">
                        <i class="fas fa-file-alt mr-2"></i>
                        Detalle del Reporte (Folio: <span id="rFolio"></span>)
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Meta info -->
                    <div class="reporte-meta mb-3">
                        <span><i class="fas fa-tag"></i> <strong>Operación:</strong> <span id="rOperacion"></span></span>
                        <span><i class="fas fa-user"></i> <strong>Usuario:</strong> <span id="rUsuario"></span></span>
                        <span><i class="fas fa-clock"></i> <strong>Fecha:</strong> <span id="rFecha"></span></span>
                        <span><i class="fas fa-weight-hanging"></i> <strong>Tamaño:</strong> <span id="rTamano"></span></span>
                    </div>
                    <hr>
                    <!-- Archivo -->
                    <p class="mb-2">
                        <i class="fas fa-file-alt text-stockit mr-1"></i>
                        <strong>Archivo:</strong> <span id="rArchivo" class="text-muted"></span>
                    </p>
                    <!-- Notas -->
                    <p class="font-weight-bold mb-1">
                        <i class="fas fa-clipboard-list text-stockit mr-1"></i> Notas del proceso:
                    </p>
                    <div class="reporte-box" id="rNotas"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════
         MODAL: IMPORTAR RESPALDO
    ══════════════════════════════════════════ -->
    <div class="modal fade" id="modalImportar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-stockit">
                    <h5 class="modal-title">
                        <i class="fas fa-upload mr-2"></i> Importar Respaldo
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="index.php?menu=respaldo&submenu=importar" method="POST"
                      enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>¡Advertencia!</strong> Importar intentará insertar los datos
                            del archivo en la base de datos actual. Usa solo archivos generados por StockIt.
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">
                                Selecciona el archivo <span class="text-stockit">(.txt)</span>
                            </label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="archivoRespaldo"
                                       name="archivo_respaldo" accept=".txt" required>
                                <label class="custom-file-label" for="archivoRespaldo">
                                    Elegir archivo...
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-warning text-white">
                            <i class="fas fa-upload mr-1"></i> Importar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div><!-- /.wrapper -->

<script src="public/plugins/jquery/jquery.min.js"></script>
<script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="public/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ── Mostrar nombre del archivo seleccionado ───────────────
$('#archivoRespaldo').on('change', function() {
    const nombre = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').text(nombre || 'Elegir archivo...');
});

// ── Confirmar antes de exportar ───────────────────────────
$('#btnExportar').on('click', function(e) {
    e.preventDefault();
    const url = $(this).attr('href');
    Swal.fire({
        title: '¿Generar respaldo?',
        text: 'Se descargará un archivo .txt con todos los datos del sistema.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#E8820C',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, generar',
        cancelButtonText: 'Cancelar'
    }).then(r => {
        if (r.isConfirmed) window.location = url;
    });
});

// ── Abrir modal de reporte con datos de la fila ───────────
$('.btn-ver-reporte').on('click', function() {
    const folio     = $(this).data('folio');
    const operacion = $(this).data('operacion');
    const archivo   = $(this).data('archivo');
    const tamano    = $(this).data('tamano');
    const usuario   = $(this).data('usuario');
    const fecha     = $(this).data('fecha');
    const notas     = $(this).data('notas');

    $('#rFolio').text(folio);
    $('#rOperacion').html(
        operacion === 'EXPORTACION'
            ? '<span style="color:#28a745"><i class="fas fa-download mr-1"></i>EXPORTACIÓN</span>'
            : '<span style="color:#d39e00"><i class="fas fa-upload mr-1"></i>IMPORTACIÓN</span>'
    );
    $('#rArchivo').text(archivo);
    $('#rTamano').text(tamano);
    $('#rUsuario').text(usuario);
    $('#rFecha').text(fecha);
    $('#rNotas').text(notas);

    $('#modalReporte').modal('show');
});
</script>
</body>
</html>