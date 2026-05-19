<?php
// views/ventas.php
// Variables disponibles desde el controlador:
//   $ventasHoy    → array de ventas del día (para corte)
//   $totalHoy     → suma total del día
//   $devoluciones → array de devoluciones (para tab devoluciones)
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>StockIt | Gestión de Operaciones</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="public/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="public/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <style>
    /* ── Navbar / Sidebar ── */
    .main-header.navbar,.main-header.navbar-white,
    .main-header.navbar-light,nav.main-header      { background-color:#E8820C!important;border-bottom:none!important }
    .main-header .nav-link,.main-header .nav-link i,
    .navbar-light .navbar-nav .nav-link            { color:#fff!important }
    .main-sidebar,.main-sidebar:before,
    .brand-link,.sidebar-dark-primary              { background-color:#E8820C!important }
    .nav-sidebar .nav-link,.brand-link .brand-text,
    .sidebar .user-panel .info a,
    .nav-sidebar .nav-link i                       { color:#fff!important }
    .form-control-sidebar,.btn-sidebar             { background-color:#fff!important;border:1px solid #ddd!important;color:#333!important }
    .btn-sidebar i                                 { color:#333!important }
    .user-panel,.form-inline                       { border-bottom:1px solid rgba(255,255,255,.2)!important }
    .nav-sidebar .nav-link                         { background:transparent!important }
    .nav-sidebar .nav-link:hover                   { background-color:rgba(255,255,255,.15)!important }
    .nav-sidebar .nav-link.active,
    .nav-sidebar .nav-item.menu-open>.nav-link     { background-color:rgba(0,0,0,.15)!important;color:#fff!important }

    /* ── Layout ── */
    .content-wrapper { background-color:#FFF5E5!important }
    .text-stockit    { color:#E8820C!important }

    /* ── Pills ── */
    #pills-tab .nav-link        { color:#E8820C;border:1px solid #E8820C;border-radius:5px;margin-right:10px;background:white }
    #pills-tab .nav-link.active { background-color:#E8820C!important;color:white!important;border-color:#E8820C!important }

    /* ── Métodos de pago ── */
    .metodo-pago        { border:2px solid #E8820C!important;background:white;color:#E8820C;
                          margin-bottom:8px;text-align:left;padding:15px;transition:.3s;
                          border-radius:12px!important;font-weight:bold }
    .metodo-pago.active { background-color:#FFF9C4!important;border-width:3px!important }

    /* ── Carrito ── */
    #resultados-locales             { max-height:220px;overflow-y:auto }
    .barcode-input-group .input-group-text { background:#343a40;color:white;border:none }

    /* ── Resumen IVA en carrito ── */
    .resumen-iva           { background:#FFF3E0;border-radius:10px;padding:10px 14px;font-size:.85rem }
    .resumen-iva .ri-row   { display:flex;justify-content:space-between;padding:2px 0 }
    .resumen-iva .ri-total { border-top:1px solid #E8820C;margin-top:4px;padding-top:6px;
                             font-weight:bold;color:#E8820C;font-size:1rem }

    /* ── Modal cambio ── */
    .cambio-display  { font-size:2.5rem;font-weight:bold;text-align:center;padding:15px;border-radius:10px;margin-top:10px }
    .cambio-positivo { background:#e8f5e9;color:#2e7d32 }
    .cambio-negativo { background:#ffebee;color:#c62828 }
    .cambio-cero     { background:#fff3e0;color:#E8820C }

    /* ── Motivos devolución ── */
    .motivo-card          { border:2px solid #E8820C;border-radius:8px;padding:12px 15px;
                            margin-bottom:10px;cursor:pointer;background:white;transition:all .2s;color:#555 }
    .motivo-card:hover,
    .motivo-card.selected { background-color:#FFF3E0;border-color:#E8820C;color:#E8820C;font-weight:bold }
    .motivo-card i        { color:#E8820C;margin-right:8px }

    /* ── Corte ── */
    #historial-transacciones tr       { cursor:pointer }
    #historial-transacciones tr:hover { background-color:#FFF3E0 }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">

<?php $paginaActiva = "ventas"; include __DIR__ . "/includes/barras.php"; ?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="d-inline font-weight-bold">Gestión de</h1>
          <h1 class="d-inline text-stockit" style="font-style:italic"> Operaciones</h1>
        </div>
      </div>
      <ul class="nav nav-pills mt-3" id="pills-tab" role="tablist">
        <li class="nav-item"><a class="nav-link active shadow-sm" data-toggle="pill" href="#tab-venta">Venta Directa</a></li>
        <li class="nav-item"><a class="nav-link shadow-sm"        data-toggle="pill" href="#tab-devolucion">Devoluciones</a></li>
        <li class="nav-item"><a class="nav-link shadow-sm"        data-toggle="pill" href="#tab-corte">Corte Diario</a></li>
      </ul>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="tab-content">

        <!-- ══════════════ TAB: VENTA DIRECTA ══════════════ -->
        <div class="tab-pane fade show active" id="tab-venta">
          <div class="row">

            <!-- Carrito -->
            <div class="col-md-7">
              <div class="card shadow-sm border-top border-warning">
                <div class="card-header bg-white">
                  <h3 class="card-title text-stockit font-weight-bold">
                    <i class="fas fa-shopping-cart mr-1"></i> Carrito de Compras
                  </h3>
                </div>
                <div class="card-body">

                  <div class="position-relative mb-2">
                    <input type="text" id="busqueda-local" class="form-control"
                           placeholder="🔍 Buscar producto por nombre...">
                    <div id="resultados-locales" class="list-group position-absolute w-100 shadow"
                         style="z-index:1050"></div>
                  </div>

                  <div class="input-group mb-3 barcode-input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                    </div>
                    <input type="text" id="busqueda-barcode" class="form-control"
                           placeholder="Escanear o escribir código de barras y presionar Enter...">
                  </div>

                  <table class="table table-sm mt-1">
                    <thead>
                      <tr class="text-stockit">
                        <th>Producto</th>
                        <th style="width:130px">Cant.</th>
                        <th>Precio s/IVA</th>
                        <th>IVA</th>
                        <th>Subtotal c/IVA</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody id="lista-venta"></tbody>
                  </table>

                  <!-- Resumen IVA -->
                  <div class="resumen-iva mt-2" id="resumen-iva" style="display:none">
                    <div class="ri-row">
                      <span class="text-muted">Subtotal sin IVA</span>
                      <span id="res-sin-iva">$0.00</span>
                    </div>
                    <div class="ri-row">
                      <span class="text-muted">IVA total</span>
                      <span id="res-iva">$0.00</span>
                    </div>
                    <div class="ri-row ri-total">
                      <span>Total con IVA</span>
                      <span id="res-total-iva">$0.00</span>
                    </div>
                  </div>

                  <div class="text-right mt-2">
                    <h3>Total: <span class="text-stockit font-weight-bold" id="total-venta">$0.00</span></h3>
                  </div>
                </div>
              </div>
            </div>

            <!-- Panel de pago -->
            <div class="col-md-5">
              <div class="card shadow-sm" style="border-radius:15px;border:none">
                <div class="card-header" style="background:#343a40;color:white;border-radius:15px 15px 0 0">
                  <h3 class="card-title font-weight-bold">
                    <i class="fas fa-credit-card mr-1"></i> Finalizar Transacción
                  </h3>
                </div>
                <div class="card-body">
                  <label class="text-muted small mb-2">Seleccione Método de Pago:</label>
                  <button class="btn btn-block metodo-pago shadow-sm" data-metodo="1">
                    <i class="fas fa-money-bill-wave mr-2"></i> Dinero en Efectivo
                  </button>
                  <button class="btn btn-block metodo-pago shadow-sm mb-4" data-metodo="2">
                    <i class="fas fa-credit-card mr-2"></i> Tarjeta / Transferencia
                  </button>
                  <button id="btn-finalizar" class="btn btn-block shadow"
                          style="background:#E8820C;color:white;font-weight:bold;padding:15px;border-radius:12px;border:none;font-size:1.1rem">
                    <i class="fas fa-check-circle mr-2"></i> PROCESAR PAGO
                  </button>
                  <button class="btn btn-block shadow-sm mt-3" onclick="limpiarCarrito()"
                          style="background:#6c757d;color:white;border-radius:12px;padding:10px;border:none;font-weight:bold">
                    <i class="fas fa-times mr-2"></i> CANCELAR OPERACIÓN
                  </button>
                </div>
              </div>
            </div>

          </div>
        </div><!-- /tab-venta -->

        <!-- ══════════════ TAB: DEVOLUCIONES ══════════════ -->
        <div class="tab-pane fade" id="tab-devolucion">
          <div class="row">
            <div class="col-md-3">
              <div class="card shadow-sm border-top border-warning">
                <div class="card-header bg-white">
                  <h5 class="card-title text-stockit font-weight-bold mb-0">
                    <i class="fas fa-lightbulb mr-1"></i> Motivos Comunes
                  </h5>
                </div>
                <div class="card-body p-2">
                  <p class="text-muted small px-1">Clic para seleccionar:</p>
                  <?php
                  $motivos = [
                    ['fa-exclamation-triangle', 'Producto Defectuoso'],
                    ['fa-exchange-alt',          'Cambio de Talla/Color'],
                    ['fa-times-circle',          'No Era Lo Esperado'],
                    ['fa-calendar-times',        'Producto Caducado'],
                    ['fa-money-bill-wave',       'Error en Cobro'],
                    ['fa-box-open',              'Producto Incompleto'],
                  ];
                  foreach ($motivos as [$icon, $label]): ?>
                    <div class="motivo-card" onclick="seleccionarMotivo(this,'<?= $label ?>')">
                      <i class="fas <?= $icon ?>"></i> <?= $label ?>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>

            <div class="col-md-9">
              <div class="card shadow-sm">
                <div class="card-header bg-white">
                  <h3 class="card-title text-stockit font-weight-bold">
                    <i class="fas fa-undo-alt mr-1"></i> Registrar Devolución
                  </h3>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4 form-group">
                      <label>Producto:</label>
                      <div class="position-relative">
                        <input type="text"   id="dev-prod-nombre" class="form-control" placeholder="Buscar producto...">
                        <input type="hidden" id="dev-prod-id">
                        <div id="dev-resultados" class="list-group position-absolute w-100 shadow"
                             style="z-index:1050;max-height:180px;overflow-y:auto"></div>
                      </div>
                    </div>
                    <div class="col-md-4 form-group">
                      <label>Motivo:</label>
                      <select id="dev-motivo" class="form-control">
                        <?php foreach ($motivos as [, $label]): ?>
                          <option><?= $label ?></option>
                        <?php endforeach; ?>
                        <option>Otro</option>
                      </select>
                    </div>
                    <div class="col-md-2 form-group">
                      <label>Cantidad:</label>
                      <input type="number" id="dev-cantidad" class="form-control" value="1" min="1">
                    </div>
                    <div class="col-md-2 form-group">
                      <label>ID Venta <small class="text-muted">(opt.)</small></label>
                      <input type="number" id="dev-idventa" class="form-control" placeholder="—">
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Descripción adicional <small class="text-muted">(opcional)</small></label>
                    <input type="text" id="dev-descripcion" class="form-control" placeholder="Detalle extra...">
                  </div>
                  <button class="btn btn-warning px-4 font-weight-bold text-white" onclick="registrarDevolucion()">
                    <i class="fas fa-plus mr-1"></i> Registrar Devolución
                  </button>
                </div>
              </div>

              <div class="card shadow-sm mt-2">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                  <h5 class="card-title text-stockit font-weight-bold mb-0">
                    <i class="fas fa-list mr-1"></i> Devoluciones Recientes
                  </h5>
                  <span class="badge badge-warning"><?= count($devoluciones) ?> registros</span>
                </div>
                <div class="card-body p-0">
                  <table class="table table-hover mb-0 text-center">
                    <thead class="thead-light">
                      <tr><th>Fecha</th><th>Producto</th><th>Cant.</th><th>Motivo</th><th>Estado</th></tr>
                    </thead>
                    <tbody id="tabla-devoluciones">
                      <?php if (empty($devoluciones)): ?>
                        <tr id="dev-empty-row">
                          <td colspan="5" class="text-muted py-3">No hay devoluciones registradas.</td>
                        </tr>
                      <?php else: ?>
                        <?php foreach ($devoluciones as $d): ?>
                          <tr>
                            <td><?= htmlspecialchars(date('d/m H:i', strtotime($d['fecha']))) ?></td>
                            <td><?= htmlspecialchars($d['nombreProducto'] ?? '—') ?></td>
                            <td><?= $d['cantidad'] ?></td>
                            <td><span class="badge badge-warning"><?= htmlspecialchars($d['motivo']) ?></span></td>
                            <td><span class="badge badge-success"><?= htmlspecialchars($d['estado']) ?></span></td>
                          </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div><!-- /tab-devolucion -->

        <!-- ══════════════ TAB: CORTE DIARIO ══════════════ -->
        <div class="tab-pane fade" id="tab-corte">
          <div class="card shadow-sm">
            <div class="card-header bg-dark">
              <h3 class="card-title text-white">
                <i class="fas fa-cash-register mr-2"></i> Corte de Caja Diario
              </h3>
            </div>
            <div class="card-body">
              <h4 class="text-stockit">
                Vendido Hoy: <b>$<?= number_format($totalHoy, 2) ?></b>
              </h4>
              <table class="table table-hover mt-3 text-center">
                <thead>
                  <tr><th>Hora</th><th>Artículos</th><th>Método</th><th>Monto</th><th></th></tr>
                </thead>
                <tbody id="historial-transacciones">
                  <?php if (empty($ventasHoy)): ?>
                    <tr><td colspan="5" class="text-muted py-3">Sin ventas registradas hoy.</td></tr>
                  <?php else: ?>
                    <?php foreach ($ventasHoy as $v): ?>
                      <tr onclick="verDetalleVenta(
                            <?= $v['idVenta'] ?>,
                            '<?= date('H:i', strtotime($v['fechaHora'])) ?>',
                            <?= $v['totalVenta'] ?>,
                            '<?= $v['tipoMetodo'] == 1 ? 'Efectivo' : 'Tarjeta' ?>')"
                          title="Ver detalle">
                        <td><?= date('H:i', strtotime($v['fechaHora'])) ?></td>
                        <td><?= $v['totalItems'] ?> items</td>
                        <td>
                          <?= $v['tipoMetodo'] == 1
                            ? '<i class="fas fa-money-bill-wave"></i> Efectivo'
                            : '<i class="fas fa-credit-card"></i> Tarjeta' ?>
                        </td>
                        <td class="font-weight-bold text-stockit">
                          $<?= number_format($v['totalVenta'], 2) ?>
                        </td>
                        <td><i class="fas fa-search text-stockit"></i></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div><!-- /tab-corte -->

      </div>
    </div>
  </section>
</div>

<!-- ═══════════════════════ MODAL: EFECTIVO ═══════════════════════ -->
<div class="modal fade" id="modalCambio" tabindex="-1" data-backdrop="static">
  <div class="modal-dialog modal-md">
    <div class="modal-content" style="border-radius:12px;overflow:hidden">
      <div class="modal-header" style="background:#343a40;color:white;border:none">
        <h5 class="modal-title font-weight-bold">
          <i class="fas fa-money-bill-wave mr-2"></i> Pago en Efectivo
        </h5>
      </div>
      <div class="modal-body">
        <div class="text-center mb-3">
          <p class="text-muted mb-0">Total a cobrar (con IVA):</p>
          <h2 class="text-stockit font-weight-bold mb-0" id="modal-total-display">$0.00</h2>
          <small class="text-muted" id="modal-desglose-iva"></small>
        </div>
        <div class="form-group">
          <label class="font-weight-bold">Dinero recibido del cliente:</label>
          <div class="input-group input-group-lg">
            <div class="input-group-prepend"><span class="input-group-text">$</span></div>
            <input type="number" id="dinero-recibido" class="form-control"
                   placeholder="0.00" step="0.01" min="0">
          </div>
        </div>
        <div id="cambio-resultado" class="cambio-display cambio-cero" style="display:none">
          <p class="mb-0 small text-muted">Cambio a entregar:</p>
          <span id="cambio-valor">$0.00</span>
        </div>
      </div>
      <div class="modal-footer" style="border:none">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="limpiarCarrito()">
          <i class="fas fa-times mr-1"></i> Cancelar
        </button>
        <button type="button" id="btn-confirmar-pago" class="btn btn-success" disabled>
          <i class="fas fa-check mr-1"></i> Confirmar y Procesar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ═══════════════════════ MODAL: TARJETA ════════════════════════ -->
<div class="modal fade" id="modalTarjeta" tabindex="-1" data-backdrop="static">
  <div class="modal-dialog modal-md">
    <div class="modal-content" style="border-radius:12px;overflow:hidden">
      <div class="modal-header" style="background:#343a40;color:white;border:none">
        <h5 class="modal-title font-weight-bold">
          <i class="fas fa-credit-card mr-2"></i> Pago con Tarjeta / Transferencia
        </h5>
      </div>
      <div class="modal-body">
        <div class="text-center mb-4">
          <p class="text-muted mb-0">Total a cobrar (con IVA):</p>
          <h2 class="font-weight-bold text-stockit mb-0" id="modal-total-tarjeta">$0.00</h2>
          <small class="text-muted" id="modal-desglose-iva-tarjeta"></small>
        </div>
        <div class="form-group">
          <label class="font-weight-bold">
            <i class="fas fa-hashtag mr-1 text-stockit"></i>
            Folio de pago
            <small class="text-muted font-weight-normal">(referencia de transferencia o voucher)</small>
          </label>
          <input type="text" id="folio-pago" class="form-control form-control-lg"
                 placeholder="Ej. TRF-20250515-001"
                 style="border:2px solid #E8820C;border-radius:10px;letter-spacing:1px">
          <small class="text-muted">El folio queda registrado para seguimiento. Es opcional pero recomendado.</small>
        </div>
        <div class="alert mb-0" style="background:#FFF3E0;border-left:4px solid #E8820C;border-radius:8px">
          <i class="fas fa-info-circle mr-2 text-stockit"></i>
          Con tarjeta no se genera cambio. Verifica que el cobro fue autorizado antes de confirmar.
        </div>
      </div>
      <div class="modal-footer" style="border:none">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="limpiarCarrito()">
          <i class="fas fa-times mr-1"></i> Cancelar
        </button>
        <button type="button" id="btn-confirmar-tarjeta" class="btn font-weight-bold px-4"
                style="background:#E8820C;color:white;border-radius:8px;border:none">
          <i class="fas fa-check mr-1"></i> Confirmar Pago
        </button>
      </div>
    </div>
  </div>
</div>

<script src="public/plugins/jquery/jquery.min.js"></script>
<script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="public/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ── Estado global ─────────────────────────────────────────
let carrito          = [];
let pagoSeleccionado = null;
let totalActual      = 0;   // con IVA
let totalSinIva      = 0;
let totalIva         = 0;

// ── Método de pago ────────────────────────────────────────
$('.metodo-pago').on('click', function () {
    $('.metodo-pago').removeClass('active');
    $(this).addClass('active');
    pagoSeleccionado = parseInt($(this).data('metodo'));
});

// ── Búsqueda por nombre ───────────────────────────────────
let timeoutBusqueda;
$('#busqueda-local').on('keyup', function () {
    clearTimeout(timeoutBusqueda);
    const q   = $(this).val().trim();
    const res = $('#resultados-locales');
    if (q.length < 2) { res.empty(); return; }

    timeoutBusqueda = setTimeout(() => {
        $.get('index.php?menu=ventas&submenu=buscar-productos', { q }, data => {
            res.empty();
            if (!data.length) {
                res.append('<div class="list-group-item text-muted">Sin resultados</div>');
                return;
            }
            data.forEach(p => {
                const pSin   = parseFloat(p.precioVenta);
                const ivaP   = parseFloat(p.ivaPorc) || 16;
                const pFinal = parseFloat(p.precioVentaFinal) > 0
                               ? parseFloat(p.precioVentaFinal)
                               : pSin * (1 + ivaP / 100);

                const item = $(`
                    <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                      <span>${p.nombreProducto}</span>
                      <span>
                        <b class="text-stockit">$${pFinal.toFixed(2)}</b>
                        <small class="text-muted ml-1">(c/IVA ${ivaP}%)</small>
                        <small class="text-muted ml-2">Stock: ${p.stockEnGeneral}</small>
                      </span>
                    </a>`);
                item.on('click', () => {
                    agregarAlCarrito({ id: p.idProducto, nombre: p.nombreProducto,
                                       precio: pSin, ivaPorc: ivaP, pFinal });
                    res.empty();
                    $('#busqueda-local').val('');
                });
                res.append(item);
            });
        }, 'json');
    }, 300);
});

// ── Búsqueda por código de barras ─────────────────────────
$('#busqueda-barcode').on('keydown', function (e) {
    if (e.key !== 'Enter') return;
    const codigo = $(this).val().trim();
    if (!codigo) return;

    $.get('index.php?menu=ventas&submenu=buscar-productos', { barcode: codigo }, data => {
        const buildItem = p => {
            const pSin   = parseFloat(p.precioVenta);
            const ivaP   = parseFloat(p.ivaPorc) || 16;
            const pFinal = parseFloat(p.precioVentaFinal) > 0
                           ? parseFloat(p.precioVentaFinal)
                           : pSin * (1 + ivaP / 100);
            return { id: p.idProducto, nombre: p.nombreProducto, precio: pSin, ivaPorc: ivaP, pFinal };
        };

        if (data.length === 1) {
            agregarAlCarrito(buildItem(data[0]));
            $(this).val('').addClass('is-valid');
            setTimeout(() => $(this).removeClass('is-valid'), 800);
        } else if (!data.length) {
            $(this).addClass('is-invalid');
            setTimeout(() => $(this).removeClass('is-invalid').val(''), 1000);
            Swal.fire({ icon:'warning', title:'No encontrado',
                        text:`Código "${codigo}" no existe.`, timer:1500, showConfirmButton:false });
        } else {
            const res = $('#resultados-locales');
            res.empty();
            data.forEach(p => {
                const obj  = buildItem(p);
                const item = $(`<a class="list-group-item list-group-item-action">
                    ${p.nombreProducto} — $${obj.pFinal.toFixed(2)} (c/IVA)</a>`);
                item.on('click', () => {
                    agregarAlCarrito(obj);
                    res.empty();
                    $('#busqueda-barcode').val('');
                });
                res.append(item);
            });
        }
    }, 'json');
});

// ── Carrito ───────────────────────────────────────────────
function agregarAlCarrito(p) {
    const ex = carrito.find(i => i.id === p.id);
    if (ex) { ex.cantidad++; } else { carrito.push({ ...p, cantidad: 1 }); }
    actualizarVista();
}

function actualizarVista() {
    const tbody = $('#lista-venta').empty();
    let totSin = 0, totIva = 0, totCon = 0;

    carrito.forEach((p, i) => {
        const subSin = p.precio  * p.cantidad;
        const subIva = (p.precio * p.ivaPorc / 100) * p.cantidad;
        const subCon = p.pFinal  * p.cantidad;
        totSin += subSin; totIva += subIva; totCon += subCon;

        tbody.append(`
            <tr>
              <td>
                ${p.nombre}
                <br><small class="text-muted">${p.ivaPorc}% IVA</small>
              </td>
              <td>
                <div class="input-group input-group-sm" style="width:120px">
                  <div class="input-group-prepend">
                    <button class="btn btn-outline-secondary btn-sm" onclick="cambiarCantidad(${i},-1)">−</button>
                  </div>
                  <input type="number" class="form-control text-center" value="${p.cantidad}" min="1"
                         onchange="setCantidad(${i}, this.value)">
                  <div class="input-group-append">
                    <button class="btn btn-outline-secondary btn-sm" onclick="cambiarCantidad(${i},1)">+</button>
                  </div>
                </div>
              </td>
              <td>$${subSin.toFixed(2)}</td>
              <td class="text-muted small">+$${subIva.toFixed(2)}</td>
              <td class="font-weight-bold text-stockit">$${subCon.toFixed(2)}</td>
              <td>
                <button class="btn btn-xs btn-danger" onclick="carrito.splice(${i},1);actualizarVista()">
                  <i class="fas fa-times"></i>
                </button>
              </td>
            </tr>`);
    });

    totalSinIva = totSin;
    totalIva    = totIva;
    totalActual = totCon;

    if (carrito.length) {
        $('#resumen-iva').show();
        $('#res-sin-iva').text(`$${totSin.toFixed(2)}`);
        $('#res-iva').text(`$${totIva.toFixed(2)}`);
        $('#res-total-iva').text(`$${totCon.toFixed(2)}`);
    } else {
        $('#resumen-iva').hide();
    }

    $('#total-venta').text(`$${totCon.toFixed(2)}`);
}

function setCantidad(i, val) {
    const n = parseInt(val);
    if (!n || n <= 0) carrito.splice(i, 1); else carrito[i].cantidad = n;
    actualizarVista();
}

function cambiarCantidad(i, delta) {
    carrito[i].cantidad += delta;
    if (carrito[i].cantidad <= 0) carrito.splice(i, 1);
    actualizarVista();
}

function limpiarCarrito() {
    carrito = []; pagoSeleccionado = null;
    totalActual = 0; totalSinIva = 0; totalIva = 0;
    $('.metodo-pago').removeClass('active');
    actualizarVista();
}

// ── Desglose IVA para modales ─────────────────────────────
function desgloseTexto() {
    return `Subtotal: $${totalSinIva.toFixed(2)} + IVA: $${totalIva.toFixed(2)}`;
}

// ── Botón Procesar Pago ───────────────────────────────────
$('#btn-finalizar').on('click', function () {
    if (!carrito.length)
        return Swal.fire({ icon:'warning', title:'Carrito vacío',
                           text:'Agrega productos antes de procesar.', timer:1500, showConfirmButton:false });
    if (!pagoSeleccionado)
        return Swal.fire({ icon:'warning', title:'Método de pago',
                           text:'Selecciona un método de pago.', timer:1500, showConfirmButton:false });

    if (pagoSeleccionado === 2) {
        $('#folio-pago').val('');
        $('#modal-total-tarjeta').text(`$${totalActual.toFixed(2)}`);
        $('#modal-desglose-iva-tarjeta').text(desgloseTexto());
        $('#modalTarjeta').modal('show');
    } else {
        $('#dinero-recibido').val('');
        $('#cambio-resultado').hide();
        $('#btn-confirmar-pago').prop('disabled', true);
        $('#modal-total-display').text(`$${totalActual.toFixed(2)}`);
        $('#modal-desglose-iva').text(desgloseTexto());
        $('#modalCambio').modal('show');
        setTimeout(() => $('#dinero-recibido').focus(), 400);
    }
});

// ── Cambio en tiempo real ─────────────────────────────────
$('#dinero-recibido').on('input', function () {
    const recibido = parseFloat($(this).val()) || 0;
    const cambio   = recibido - totalActual;
    const res      = $('#cambio-resultado');
    const btn      = $('#btn-confirmar-pago');

    if (!$(this).val()) { res.hide(); btn.prop('disabled', true); return; }

    res.show().removeClass('cambio-positivo cambio-negativo cambio-cero');
    btn.prop('disabled', cambio < 0);

    if (cambio > 0)      { res.addClass('cambio-positivo'); $('#cambio-valor').text(`$${cambio.toFixed(2)}`); }
    else if (cambio === 0) { res.addClass('cambio-cero');  $('#cambio-valor').text('Exacto 👌'); }
    else                  { res.addClass('cambio-negativo'); $('#cambio-valor').text(`Faltan $${Math.abs(cambio).toFixed(2)}`); }
});

// ── Confirmar efectivo ────────────────────────────────────
$('#btn-confirmar-pago').on('click', function () {
    const cambio = parseFloat($('#dinero-recibido').val()) - totalActual;
    $('#modalCambio').modal('hide');
    procesarVenta(cambio);
});

// ── Confirmar tarjeta ─────────────────────────────────────
$('#btn-confirmar-tarjeta').on('click', function () {
    const folio = $('#folio-pago').val().trim();
    $('#modalTarjeta').modal('hide');
    procesarVenta(0, folio);
});

// ── Procesar venta (AJAX) ─────────────────────────────────
function procesarVenta(cambio, folio = '') {
    $.ajax({
        url: 'index.php?menu=ventas&submenu=procesar-venta',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ carrito, idMetodoPago: pagoSeleccionado, folio: folio || null }),
        success(res) {
            if (!res.ok) return Swal.fire({ icon:'error', title:'Error', text: res.msg });

            // ── Ticket con desglose IVA ──────────────────
            const filas = carrito.map(p => {
                const subCon = (p.pFinal * p.cantidad).toFixed(2);
                const subIva = (p.precio * p.ivaPorc / 100 * p.cantidad).toFixed(2);
                return `<tr>
                    <td style="padding:4px 6px;text-align:left">${p.nombre} x${p.cantidad}</td>
                    <td style="padding:4px 6px;text-align:right;color:#888;font-size:.8rem">+$${subIva} IVA</td>
                    <td style="padding:4px 6px;text-align:right;font-weight:600">$${subCon}</td>
                </tr>`;
            }).join('');

            let html = `
                <div style="font-family:monospace;font-size:.85rem;text-align:left">
                  <table style="width:100%;border-collapse:collapse;margin-bottom:8px">
                    <thead>
                      <tr style="border-bottom:1px dashed #ccc;color:#888;font-size:.75rem">
                        <th style="padding:4px 6px">Producto</th>
                        <th style="padding:4px 6px;text-align:right">IVA</th>
                        <th style="padding:4px 6px;text-align:right">Subtotal</th>
                      </tr>
                    </thead>
                    <tbody>${filas}</tbody>
                  </table>
                  <div style="border-top:1px dashed #ccc;padding-top:6px">
                    <div style="display:flex;justify-content:space-between;margin-bottom:2px">
                      <span style="color:#888">Subtotal sin IVA</span>
                      <span>$${totalSinIva.toFixed(2)}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                      <span style="color:#888">IVA total</span>
                      <span>$${totalIva.toFixed(2)}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-weight:bold;font-size:1.1rem;
                                color:#E8820C;border-top:2px solid #E8820C;padding-top:6px">
                      <span>Total con IVA</span>
                      <span>$${parseFloat(res.total).toFixed(2)}</span>
                    </div>
                  </div>`;

            if (pagoSeleccionado === 1)
                html += `<div style="margin-top:8px;color:#2e7d32;font-weight:bold">
                            <i class="fas fa-coins"></i> Cambio: $${Math.max(0, cambio).toFixed(2)}
                          </div>`;
            if (folio)
                html += `<div style="margin-top:4px;color:#555">
                            <i class="fas fa-hashtag"></i> Folio: <code>${folio}</code>
                          </div>`;

            html += `</div>`;

            Swal.fire({
                icon: 'success',
                title: `¡Venta #${res.idVenta} Exitosa!`,
                html,
                width: 500,
                confirmButtonColor: '#E8820C',
                confirmButtonText: 'Aceptar'
            }).then(() => location.reload());
        },
        error() { Swal.fire({ icon:'error', title:'Error de conexión' }); }
    });
}

// ── Devoluciones: búsqueda ────────────────────────────────
let timeoutDev;
$('#dev-prod-nombre').on('keyup', function () {
    clearTimeout(timeoutDev);
    const q   = $(this).val().trim();
    const res = $('#dev-resultados');
    if (q.length < 2) { res.empty(); return; }

    timeoutDev = setTimeout(() => {
        $.get('index.php?menu=ventas&submenu=buscar-productos', { q }, data => {
            res.empty();
            data.forEach(p => {
                const item = $(`<a class="list-group-item list-group-item-action">${p.nombreProducto}</a>`);
                item.on('click', () => {
                    $('#dev-prod-nombre').val(p.nombreProducto);
                    $('#dev-prod-id').val(p.idProducto);
                    res.empty();
                });
                res.append(item);
            });
        }, 'json');
    }, 300);
});

function seleccionarMotivo(el, motivo) {
    document.querySelectorAll('.motivo-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('dev-motivo').value = motivo;
}

function registrarDevolucion() {
    const idProducto  = $('#dev-prod-id').val();
    const nombre      = $('#dev-prod-nombre').val().trim();
    const motivo      = $('#dev-motivo').val();
    const cantidad    = parseInt($('#dev-cantidad').val());
    const descripcion = $('#dev-descripcion').val().trim();
    const idVenta     = $('#dev-idventa').val() || null;

    if (!nombre || !idProducto)
        return Swal.fire({ icon:'warning', title:'Producto requerido',
                           text:'Busca y selecciona un producto.', timer:1500, showConfirmButton:false });

    $.ajax({
        url: 'index.php?menu=ventas&submenu=registrar-devolucion',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ idProducto, motivo, descripcion, cantidad, idVenta }),
        success(res) {
            if (!res.ok) return Swal.fire({ icon:'error', title:'Error', text: res.msg });

            const hora     = new Date().toLocaleTimeString([], { hour:'2-digit', minute:'2-digit' });
            const emptyRow = document.getElementById('dev-empty-row');
            if (emptyRow) emptyRow.remove();

            const tr = document.createElement('tr');
            tr.innerHTML = `<td>${hora}</td><td>${nombre}</td><td>${cantidad}</td>
                <td><span class="badge badge-warning">${motivo}</span></td>
                <td><span class="badge badge-success">Procesado</span></td>`;
            document.getElementById('tabla-devoluciones').prepend(tr);

            $('#dev-prod-nombre, #dev-descripcion').val('');
            $('#dev-prod-id, #dev-idventa').val('');
            $('#dev-cantidad').val(1);
            document.querySelectorAll('.motivo-card').forEach(c => c.classList.remove('selected'));
            Swal.fire({ icon:'success', title:'Devolución Registrada', showConfirmButton:false, timer:1000 });
        },
        error() { Swal.fire({ icon:'error', title:'Error de conexión' }); }
    });
}

// ── Corte: detalle de venta ───────────────────────────────
function verDetalleVenta(idVenta, hora, total, metodo) {
    Swal.fire({
        title: `<span style="color:#E8820C;font-style:italic">Venta #${idVenta}</span>`,
        html:  `<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x" style="color:#E8820C"></i></div>`,
        showConfirmButton: false,
        didOpen() {
            $.get(`index.php?menu=ventas&submenu=detalle-venta&id=${idVenta}`, data => {
                const filas = (data || []).map(p =>
                    `<tr>
                       <td style="text-align:left;padding:6px">${p.nombreProducto}</td>
                       <td style="padding:6px;text-align:center">${p.cantidad ?? 1}</td>
                       <td style="padding:6px;text-align:right">$${parseFloat(p.totalVentaP).toFixed(2)}</td>
                     </tr>`
                ).join('') || '<tr><td colspan="3" style="color:#999;text-align:center">Sin detalle</td></tr>';

                Swal.update({
                    html: `
                        <div style="text-align:left;margin-bottom:8px;font-size:13px">
                          <b>Hora:</b> ${hora} &nbsp;|&nbsp; <b>Método:</b> ${metodo}
                        </div>
                        <table style="width:100%;font-size:13px;border-collapse:collapse">
                          <thead>
                            <tr style="background:#FFF3E0;color:#E8820C">
                              <th style="padding:6px;text-align:left;border-bottom:2px solid #E8820C">Producto</th>
                              <th style="padding:6px;text-align:center;border-bottom:2px solid #E8820C">Cant.</th>
                              <th style="padding:6px;text-align:right;border-bottom:2px solid #E8820C">Subtotal</th>
                            </tr>
                          </thead>
                          <tbody>${filas}</tbody>
                        </table>
                        <div style="background:#FFF3E0;border-radius:8px;padding:12px;margin-top:12px;text-align:center">
                          <p style="margin:0;color:#555;font-size:12px">TOTAL (con IVA incluido)</p>
                          <p style="margin:0;font-size:2rem;font-weight:bold;color:#E8820C">
                            $${parseFloat(total).toFixed(2)}
                          </p>
                        </div>`,
                    showConfirmButton: true,
                    confirmButtonColor: '#E8820C',
                    confirmButtonText: 'Cerrar',
                    width: '500px'
                });
            }, 'json');
        }
    });
}
</script>
</body>
</html>