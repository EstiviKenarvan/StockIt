<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>StockIt | Reportes</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="public/plugins/fontawesome-free/css/all.min.css">
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
    .titulo-naranja{color:#E8820C;font-weight:bold}

    /* Tarjetas acceso rápido */
    .reporte-card{border-top:5px solid #E8820C;border-radius:12px;transition:.2s;cursor:pointer}
    .reporte-card:hover{transform:translateY(-3px);box-shadow:0 8px 20px rgba(232,130,12,.2)!important}
    .reporte-card .icon-wrap{width:45px;height:45px;background:#FFF3E0;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:#E8820C}

    /* Modal */
    .btn-tipo{border:2px solid #E8820C;border-radius:10px;padding:12px;width:100%;text-align:left;background:white;color:#555;transition:.2s;margin-bottom:8px}
    .btn-tipo:hover,.btn-tipo.active{background:#FFF3E0;color:#E8820C;font-weight:bold}
    .btn-tipo i{color:#E8820C;margin-right:8px}
    .btn-periodo-modal{border:1px solid #FFE0B2;border-radius:20px;padding:4px 14px;font-size:.8rem;color:#E8820C;background:white;cursor:pointer;transition:.2s}
    .btn-periodo-modal.active{background:#E8820C;color:white;border-color:#E8820C}

    /* Preview en modal */
    .preview-item{display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #f5f5f5;font-size:.85rem}
    .preview-item span:first-child{color:#999}
    .preview-item span:last-child{font-weight:600;color:#333}
    .preview-item .val-naranja{color:#E8820C}
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <?php $paginaActiva = "reportes"; include __DIR__ . "/includes/barras.php"; ?>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row align-items-center mb-4">
          <div class="col-md-6">
            <h1 class="m-0">Módulo de <span class="titulo-naranja">Reportes</span></h1>
            <p class="text-muted">Análisis e informes del sistema</p>
          </div>
          <div class="col-md-6 text-right">
            <button class="btn shadow-sm" style="background:#E8820C;color:white;border-radius:8px;font-weight:bold;padding:10px 20px"
                    data-toggle="modal" data-target="#modalReporte">
              <i class="fas fa-plus mr-2"></i> Nuevo Reporte
            </button>
          </div>
        </div>

        <?php if (isset($_GET['eliminado'])): ?>
          <div class="alert alert-info alert-dismissible fade show">
            <i class="fas fa-trash mr-2"></i> Reporte eliminado.
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
        <?php endif; ?>

        <!-- Tarjetas acceso rápido -->
        <div class="row mb-4">
          <?php
          $tarjetas = [
            ['tipo'=>'disponibilidad', 'icon'=>'fa-boxes',       'titulo'=>'Disponibilidad de Stock',  'desc'=>'Productos disponibles, agotados y con poco stock.'],
            ['tipo'=>'caducidades',    'icon'=>'fa-clock',        'titulo'=>'Próximos a Caducar',       'desc'=>'Listado de productos con fecha de caducidad próxima.'],
            ['tipo'=>'ventas',         'icon'=>'fa-cash-register','titulo'=>'Reporte de Ventas',        'desc'=>'Detalle de ventas por período de tiempo.'],
            ['tipo'=>'movimientos',    'icon'=>'fa-truck',        'titulo'=>'Movimientos de Inventario','desc'=>'Entradas de productos por proveedor y período.'],
            ['tipo'=>'general',        'icon'=>'fa-chart-pie',    'titulo'=>'Reporte General',          'desc'=>'Resumen completo del negocio: stock, ventas y clientes.'],
          ];
          foreach ($tarjetas as $t): ?>
            <div class="col-md-4 mb-3">
              <div class="card p-3 shadow-sm reporte-card"
                   onclick="abrirModalConTipo('<?= $t['tipo'] ?>')">
                <div class="d-flex align-items-center mb-2">
                  <div class="icon-wrap mr-3"><i class="fas <?= $t['icon'] ?>"></i></div>
                  <h6 class="font-weight-bold mb-0"><?= $t['titulo'] ?></h6>
                </div>
                <p class="text-muted small mb-2"><?= $t['desc'] ?></p>
                <span class="small" style="color:#E8820C"><i class="fas fa-file-pdf mr-1"></i> Generar PDF</span>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Historial -->
        <div class="card shadow-sm" style="border-radius:15px;overflow:hidden">
          <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="titulo-naranja font-weight-bold m-0"><i class="fas fa-history mr-2"></i>Historial de Reportes</h6>
            <span class="badge badge-warning text-dark"><?= count($reportes) ?> registros</span>
          </div>
          <div class="card-body p-0">
            <table class="table table-hover m-0 text-center">
              <thead style="background:#FFF5E5;color:#C06000">
                <tr>
                  <th>#</th><th>Fecha</th><th>Tipo</th><th>Período</th>
                  <th>Filtros</th><th>Formato</th><th>Generado por</th><th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($reportes)): ?>
                  <tr><td colspan="8" class="text-muted py-4">No hay reportes generados aún.</td></tr>
                <?php else: ?>
                  <?php foreach ($reportes as $r): ?>
                    <tr>
                      <td class="text-muted"><?= $r['idReportes'] ?></td>
                      <td><?= date('d/m/Y H:i', strtotime($r['fechaGenerado'])) ?></td>
                      <td><span class="badge badge-warning text-dark"><?= htmlspecialchars($r['tipoReporte']) ?></span></td>
                      <td><?= htmlspecialchars($r['peridodTiempo']) ?></td>
                      <td><?= htmlspecialchars($r['filtros'] ?? '—') ?></td>
                      <td><i class="fas fa-file-pdf text-danger mr-1"></i><?= htmlspecialchars($r['formato']) ?></td>
                      <td><?= htmlspecialchars($r['generadoPorNombre'] ?? '—') ?></td>
                      <td>
                        <button class="btn btn-sm btn-outline-danger btn-borrar-reporte"
                                data-id="<?= $r['idReportes'] ?>">
                          <i class="fas fa-trash"></i>
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
  </div>

  <!-- ══ MODAL GENERAR REPORTE ══════════════════════════ -->
  <div class="modal fade" id="modalReporte" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" style="border-radius:15px;overflow:hidden">
        <div class="modal-header" style="background:#E8820C;color:white;border:none">
          <h5 class="modal-title font-weight-bold"><i class="fas fa-file-pdf mr-2"></i> Generar Reporte PDF</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="row">

            <!-- Izquierda: formulario -->
            <div class="col-md-7">

              <h6 class="titulo-naranja font-weight-bold mb-3">Tipo de reporte</h6>
              <button class="btn-tipo active" data-tipo="disponibilidad">
                <i class="fas fa-boxes"></i> Disponibilidad de Stock
              </button>
              <button class="btn-tipo" data-tipo="caducidades">
                <i class="fas fa-clock"></i> Próximos a Caducar
              </button>
              <button class="btn-tipo" data-tipo="ventas">
                <i class="fas fa-cash-register"></i> Reporte de Ventas
              </button>
              <button class="btn-tipo" data-tipo="movimientos">
                <i class="fas fa-truck"></i> Movimientos de Inventario
              </button>
              <button class="btn-tipo" data-tipo="general">
                <i class="fas fa-chart-pie"></i> Reporte General
              </button>

              <h6 class="titulo-naranja font-weight-bold mt-4 mb-2">Período</h6>
              <div class="mb-3">
                <button class="btn-periodo-modal mr-1" data-periodo="hoy">Hoy</button>
                <button class="btn-periodo-modal mr-1" data-periodo="semana">Esta semana</button>
                <button class="btn-periodo-modal active mr-1" data-periodo="mes">Este mes</button>
                <button class="btn-periodo-modal" data-periodo="año">Este año</button>
              </div>
              <div class="row mb-3">
                <div class="col-6">
                  <label class="small text-muted">Fecha inicio</label>
                  <input type="date" id="modal-fecha-inicio" class="form-control" style="border-radius:8px">
                </div>
                <div class="col-6">
                  <label class="small text-muted">Fecha fin</label>
                  <input type="date" id="modal-fecha-fin" class="form-control" style="border-radius:8px">
                </div>
              </div>

              <h6 class="titulo-naranja font-weight-bold mb-2">Formato</h6>
              <div class="d-flex">
                <div class="custom-control custom-radio mr-4">
                  <input class="custom-control-input" type="radio" id="fmt-pdf" name="formato-modal" value="PDF" checked>
                  <label for="fmt-pdf" class="custom-control-label">PDF</label>
                </div>
                <div class="custom-control custom-radio">
                  <input class="custom-control-input" type="radio" id="fmt-csv" name="formato-modal" value="CSV">
                  <label for="fmt-csv" class="custom-control-label">CSV</label>
                </div>
              </div>
            </div>

            <!-- Derecha: preview -->
            <div class="col-md-5">
              <div class="card shadow-sm p-3" style="border-radius:12px;border-top:3px solid #E8820C;position:sticky;top:0">
                <h6 class="titulo-naranja font-weight-bold mb-3"><i class="fas fa-eye mr-2"></i>Vista previa</h6>
                <div class="preview-item">
                  <span>Tipo</span>
                  <span class="val-naranja" id="prev-tipo">Disponibilidad de Stock</span>
                </div>
                <div class="preview-item">
                  <span>Período</span>
                  <span id="prev-periodo">Este mes</span>
                </div>
                <div class="preview-item">
                  <span>Fechas</span>
                  <span id="prev-fechas">—</span>
                </div>
                <div class="preview-item">
                  <span>Formato</span>
                  <span id="prev-formato">PDF</span>
                </div>
                <div class="mt-3 p-2" style="background:#FFF3E0;border-radius:8px;font-size:.8rem;color:#7a3900">
                  <i class="fas fa-info-circle mr-1"></i>
                  El reporte se descargará automáticamente y quedará guardado en el historial.
                </div>
              </div>
            </div>

          </div>
        </div>
        <div class="modal-footer" style="border:none">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" id="btn-generar" class="btn font-weight-bold px-4"
                  style="background:#E8820C;color:white;border-radius:8px;border:none">
            <i class="fas fa-file-download mr-2"></i> Generar y Descargar
          </button>
        </div>
      </div>
    </div>
  </div>

</div>

<script src="public/plugins/jquery/jquery.min.js"></script>
<script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="public/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<script>
const { jsPDF } = window.jspdf;

// ── Estado del modal ──────────────────────────────────────
let tipoSeleccionado  = 'disponibilidad';
let periodoSeleccionado = 'mes';
let formatoSeleccionado = 'PDF';

// ── Fechas por defecto (mes actual) ──────────────────────
function fechasPorPeriodo(p) {
    const hoy = new Date();
    let ini, fin = hoy.toISOString().split('T')[0];
    switch(p) {
        case 'hoy':
            ini = fin; break;
        case 'semana':
            const lun = new Date(hoy); lun.setDate(hoy.getDate() - hoy.getDay() + 1);
            ini = lun.toISOString().split('T')[0]; break;
        case 'año':
            ini = hoy.getFullYear() + '-01-01'; break;
        default: // mes
            ini = hoy.getFullYear() + '-' + String(hoy.getMonth()+1).padStart(2,'0') + '-01';
    }
    return { ini, fin };
}

function actualizarPreview() {
    const tipoTextos = {
        disponibilidad: 'Disponibilidad de Stock',
        caducidades:    'Próximos a Caducar',
        ventas:         'Reporte de Ventas',
        movimientos:    'Movimientos de Inventario',
        general:        'Reporte General'
    };
    const periodoTextos = { hoy:'Hoy', semana:'Esta semana', mes:'Este mes', 'año':'Este año' };

    $('#prev-tipo').text(tipoTextos[tipoSeleccionado] || tipoSeleccionado);
    $('#prev-periodo').text(periodoTextos[periodoSeleccionado]);
    $('#prev-formato').text(formatoSeleccionado);

    const fi = $('#modal-fecha-inicio').val();
    const ff = $('#modal-fecha-fin').val();
    if (fi && ff) $('#prev-fechas').text(fi.split('-').reverse().join('/') + ' — ' + ff.split('-').reverse().join('/'));
}

// Inicializar fechas al abrir modal
$('#modalReporte').on('show.bs.modal', function() {
    const f = fechasPorPeriodo('mes');
    $('#modal-fecha-inicio').val(f.ini);
    $('#modal-fecha-fin').val(f.fin);
    actualizarPreview();
});

// Tipo de reporte
$('.btn-tipo').on('click', function() {
    $('.btn-tipo').removeClass('active');
    $(this).addClass('active');
    tipoSeleccionado = $(this).data('tipo');
    actualizarPreview();
});

// Período
$('.btn-periodo-modal').on('click', function() {
    $('.btn-periodo-modal').removeClass('active');
    $(this).addClass('active');
    periodoSeleccionado = $(this).data('periodo');
    const f = fechasPorPeriodo(periodoSeleccionado);
    $('#modal-fecha-inicio').val(f.ini);
    $('#modal-fecha-fin').val(f.fin);
    actualizarPreview();
});

// Formato
$('input[name="formato-modal"]').on('change', function() {
    formatoSeleccionado = $(this).val();
    actualizarPreview();
});

$('#modal-fecha-inicio, #modal-fecha-fin').on('change', actualizarPreview);

// ── Abrir modal con tipo preseleccionado ──────────────────
function abrirModalConTipo(tipo) {
    tipoSeleccionado = tipo;
    $('.btn-tipo').removeClass('active');
    $(`.btn-tipo[data-tipo="${tipo}"]`).addClass('active');
    const f = fechasPorPeriodo('mes');
    $('#modal-fecha-inicio').val(f.ini);
    $('#modal-fecha-fin').val(f.fin);
    actualizarPreview();
    $('#modalReporte').modal('show');
}

// ── Generar PDF / CSV ─────────────────────────────────────
$('#btn-generar').on('click', function() {
    const fi = $('#modal-fecha-inicio').val();
    const ff = $('#modal-fecha-fin').val();

    $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Generando...');

    $.get('index.php?menu=reportes&submenu=datos', {
        tipo: tipoSeleccionado, fechaInicio: fi, fechaFin: ff
    }, res => {
        if (!res.ok) {
            Swal.fire({ icon:'error', title:'Error', text:'No se pudieron obtener los datos.' });
            return;
        }

        if (formatoSeleccionado === 'CSV') {
            generarCSV(res.data, tipoSeleccionado, fi, ff);
        } else {
            generarPDF(res.data, tipoSeleccionado, fi, ff);
        }

        // Guardar en BD
        // Guardar en BD
$.ajax({
    url: 'index.php?menu=reportes&submenu=guardar',
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({
        tipoReporte:   tipoSeleccionado,
        filtros:       `${fi} al ${ff}`,
        peridodTiempo: new Date().toISOString().slice(0,19).replace('T',' '),
        idProducto:    null,
        formato:       formatoSeleccionado
    }),
    success(res) {       // ← agrega "res" como parámetro
        console.log('Respuesta guardar:', res);  // ← línea nueva
        $('#modalReporte').modal('hide');
        setTimeout(() => location.reload(), 800);
    },
    error(xhr) {         // ← bloque nuevo
        console.error('Error guardar:', xhr.responseText);
    }
});

        $('#btn-generar').prop('disabled', false).html('<i class="fas fa-file-download mr-2"></i> Generar y Descargar');

    }, 'json').fail(() => {
        $('#btn-generar').prop('disabled', false).html('<i class="fas fa-file-download mr-2"></i> Generar y Descargar');
        Swal.fire({ icon:'error', title:'Error de conexión' });
    });
});

// ── Generador PDF ─────────────────────────────────────────
function generarPDF(data, tipo, fi, ff) {
    const doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
    const naranja = [232, 130, 12];
    const gris    = [100, 100, 100];
    const anchoPag = 210;

    // Encabezado
    doc.setFillColor(...naranja);
    doc.rect(0, 0, anchoPag, 28, 'F');
    doc.setTextColor(255, 255, 255);
    doc.setFontSize(18);
    doc.setFont('helvetica', 'bold');
    doc.text('StockIt', 14, 12);
    doc.setFontSize(11);
    doc.setFont('helvetica', 'normal');

    const titulos = {
        disponibilidad: 'Reporte de Disponibilidad de Stock',
        caducidades:    'Reporte de Productos por Caducar',
        ventas:         'Reporte de Ventas',
        movimientos:    'Reporte de Movimientos de Inventario',
        general:        'Reporte General del Negocio'
    };
    doc.text(titulos[tipo] || 'Reporte', 14, 20);
    doc.setFontSize(9);
    doc.text(`Período: ${fi} — ${ff}   |   Generado: ${new Date().toLocaleDateString('es-MX')}`, anchoPag - 14, 20, { align:'right' });

    let y = 36;

    // Contenido según tipo
    if (tipo === 'disponibilidad') {
        doc.setTextColor(...gris);
        doc.setFontSize(10);
        doc.setFont('helvetica','bold');
        doc.text('Inventario de Productos', 14, y); y += 6;

        doc.autoTable({
            startY: y,
            head: [['Código', 'Producto', 'Categoría', 'Stock', 'Precio Venta', 'Estado']],
            body: data.map(p => [
                p.codigoBarras || '—', p.nombreProducto || '—',
                p.nombreCategoria || '—', p.stockEnGeneral,
                '$' + parseFloat(p.precioVenta || 0).toFixed(2), p.estado
            ]),
            headStyles: { fillColor: naranja, textColor: 255, fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [255, 245, 229] },
            styles: { fontSize: 8, cellPadding: 3 },
            columnStyles: { 5: { halign: 'center' } }
        });

    } else if (tipo === 'caducidades') {
        doc.autoTable({
            startY: y,
            head: [['Código', 'Producto', 'Categoría', 'Stock', 'Fecha Caducidad', 'Días Restantes', 'Estado']],
            body: data.map(p => [
                p.codigoBarras || '—', p.nombreProducto || '—',
                p.nombreCategoria || '—', p.stockEnGeneral,
                p.fechaCaducidad ? p.fechaCaducidad.split(' ')[0] : '—',
                p.diasRestantes !== null ? p.diasRestantes + ' días' : '—',
                p.estado
            ]),
            headStyles: { fillColor: naranja, textColor: 255, fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [255, 245, 229] },
            styles: { fontSize: 8, cellPadding: 3 },
            didParseCell(data) {
                if (data.column.index === 5 && data.section === 'body') {
                    const dias = parseInt(data.cell.raw);
                    if (dias <= 7)  data.cell.styles.textColor = [220, 53, 69];
                    else if (dias <= 30) data.cell.styles.textColor = [255, 140, 0];
                }
            }
        });

    } else if (tipo === 'ventas') {
        const totalVentas = data.reduce((s, v) => s + parseFloat(v.totalVentaP || 0), 0);

        doc.setFillColor(255, 243, 224);
        doc.roundedRect(14, y, 80, 16, 3, 3, 'F');
        doc.setTextColor(...naranja);
        doc.setFontSize(9);
        doc.setFont('helvetica','bold');
        doc.text('Total del período:', 18, y + 7);
        doc.setFontSize(13);
        doc.text('$' + totalVentas.toFixed(2), 18, y + 13);
        y += 22;

        doc.autoTable({
            startY: y,
            head: [['Venta #', 'Fecha', 'Producto', 'Cantidad', 'Subtotal']],
            body: data.map(v => [
                v.idVenta || '—',
                v.fechaHora ? v.fechaHora.slice(0,16) : '—',
                v.nombreProducto || '—',
                v.cantidad || 1,
                '$' + parseFloat(v.totalVentaP || 0).toFixed(2)
            ]),
            headStyles: { fillColor: naranja, textColor: 255, fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [255, 245, 229] },
            styles: { fontSize: 8, cellPadding: 3 },
            foot: [['', '', '', 'TOTAL', '$' + totalVentas.toFixed(2)]],
            footStyles: { fillColor: [255, 243, 224], textColor: naranja, fontStyle: 'bold' }
        });

    } else if (tipo === 'movimientos') {
        const totalUnidades = data.reduce((s, m) => s + parseInt(m.cantidad || 0), 0);

        doc.setFillColor(255, 243, 224);
        doc.roundedRect(14, y, 80, 16, 3, 3, 'F');
        doc.setTextColor(...naranja);
        doc.setFontSize(9); doc.setFont('helvetica','bold');
        doc.text('Total unidades ingresadas:', 18, y + 7);
        doc.setFontSize(13);
        doc.text(totalUnidades + ' uds.', 18, y + 13);
        y += 22;

        doc.autoTable({
            startY: y,
            head: [['Fecha', 'Producto', 'Proveedor', 'Cantidad', 'Costo Unit.', 'Total']],
            body: data.map(m => [
                m.fechaEntrada ? m.fechaEntrada.slice(0,10) : '—',
                m.nombreProducto || '—',
                m.nombreProveedor || '—',
                m.cantidad,
                '$' + parseFloat(m.costo || 0).toFixed(2),
                '$' + (parseFloat(m.costo || 0) * parseInt(m.cantidad || 0)).toFixed(2)
            ]),
            headStyles: { fillColor: naranja, textColor: 255, fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [255, 245, 229] },
            styles: { fontSize: 8, cellPadding: 3 }
        });

    } else if (tipo === 'general') {
        // Resumen en tarjetas
        const kpis = [
            ['Productos totales', data.productos],
            ['Stock total (uds.)', data.stock],
            ['Agotados', data.agotados],
            ['Disponibles', data.disponibles],
            ['Ventas este mes ($)', '$' + parseFloat(data.ventasMes || 0).toFixed(2)],
            ['Clientes registrados', data.clientes]
        ];

        let x = 14;
        kpis.forEach((k, i) => {
            if (i % 3 === 0 && i > 0) { x = 14; y += 24; }
            const col = 14 + (i % 3) * 62;
            doc.setFillColor(255, 243, 224);
            doc.roundedRect(col, y, 58, 18, 3, 3, 'F');
            doc.setTextColor(...gris);
            doc.setFontSize(7); doc.setFont('helvetica','normal');
            doc.text(k[0], col + 4, y + 6);
            doc.setTextColor(...naranja);
            doc.setFontSize(12); doc.setFont('helvetica','bold');
            doc.text(String(k[1]), col + 4, y + 14);
        });
        y += 30;

        // Tabla por categoría
        doc.setTextColor(...gris);
        doc.setFontSize(10); doc.setFont('helvetica','bold');
        doc.text('Stock por Categoría', 14, y); y += 4;

        doc.autoTable({
            startY: y,
            head: [['Categoría', 'Productos', 'Stock Total']],
            body: (data.categorias || []).map(c => [c.nombreCategoria, c.total, c.stock || 0]),
            headStyles: { fillColor: naranja, textColor: 255, fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [255, 245, 229] },
            styles: { fontSize: 9, cellPadding: 4 }
        });
    }

    // Pie de página
    const totalPags = doc.internal.getNumberOfPages();
    for (let i = 1; i <= totalPags; i++) {
        doc.setPage(i);
        doc.setFillColor(245, 245, 245);
        doc.rect(0, 287, 210, 10, 'F');
        doc.setTextColor(150, 150, 150);
        doc.setFontSize(7);
        doc.text('StockIt — Sistema de Gestión de Inventario', 14, 293);
        doc.text(`Página ${i} de ${totalPags}`, anchoPag - 14, 293, { align: 'right' });
    }

    doc.save(`reporte_${tipo}_${fi}_${ff}.pdf`);
}

// ── Generador CSV ─────────────────────────────────────────
function generarCSV(data, tipo, fi, ff) {
    if (!Array.isArray(data)) {
        // General: convertir a array plano
        data = Object.entries(data).filter(([k]) => k !== 'categorias')
                     .map(([k,v]) => ({ campo: k, valor: v }));
    }
    if (!data.length) { Swal.fire({ icon:'warning', title:'Sin datos', text:'No hay datos para exportar.' }); return; }

    const headers = Object.keys(data[0]);
    const filas   = data.map(r => headers.map(h => `"${(r[h] ?? '').toString().replace(/"/g,'""')}"`).join(','));
    const csv     = [headers.join(','), ...filas].join('\n');
    const blob    = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link    = document.createElement('a');
    link.href     = URL.createObjectURL(blob);
    link.download = `reporte_${tipo}_${fi}_${ff}.csv`;
    link.click();
}

// ── Eliminar reporte ──────────────────────────────────────
$('.btn-borrar-reporte').on('click', function() {
    const id = $(this).data('id');
    Swal.fire({
        title: '¿Eliminar del historial?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#E8820C',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(r => {
        if (r.isConfirmed)
            window.location = `index.php?menu=reportes&submenu=borrar&id=${id}`;
    });
});
</script>
</body>
</html>