<?php
include __DIR__ . "/includes/tarjeta.php";
include __DIR__ . "/includes/tablas.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>StockIt | Inventario</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="public/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="public/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <style>
    .main-header.navbar,.main-header.navbar-white,.main-header.navbar-light,nav.main-header{background-color:#E8820C!important;border-bottom:none!important}
    .main-header .nav-link,.main-header .nav-link i,.navbar-light .navbar-nav .nav-link{color:#fff!important}
    .main-sidebar,.main-sidebar:before,.brand-link,.sidebar-dark-primary{background-color:#E8820C!important}
    .nav-sidebar .nav-link,.brand-link .brand-text,.sidebar .user-panel .info a,.nav-sidebar .nav-link i{color:#fff!important}
    .content-wrapper{background-color:#FFF5E5!important}
    .modal-header-stockit{background-color:#E8820C;color:white;border-radius:5px 5px 0 0}
    .modal-header-stockit .close{color:white;opacity:1}
    .text-orange{color:#E8820C!important}
    .modal { z-index: 1060 !important; }
    .modal-backdrop { z-index: 1050 !important; }
    .resumen-box{background:#FFF5E5;border:1px dashed #FFE0B2;border-radius:10px;padding:12px 15px;min-height:42px}
    .resumen-valor{font-size:1.4rem;font-weight:bold;color:#E8820C}
    .resumen-stock-ok{font-size:1.4rem;font-weight:bold;color:#28a745}
    .resumen-stock-bajo{font-size:1.4rem;font-weight:bold;color:#dc3545}
    @keyframes flashUpdate{0%{background:#fff3cd}100%{background:transparent}}
    .flash{animation:flashUpdate .5s ease-out}
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <?php $paginaActiva = "inventario"; include __DIR__ . "/includes/barras.php"; ?>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-3 align-items-end">
          <div class="col-sm-6">
            <h1 class="m-0 d-inline" style="color:#333;font-weight:bold">Gestión de</h1>
            <h1 class="m-0 d-inline" style="color:#dd6e12;font-style:italic"> Inventario</h1>
          </div>
          <div class="col-sm-6 d-flex justify-content-end">
            <button type="button" class="btn btn-outline-warning" onclick="abrirModal()">
              <i class="fas fa-plus mr-1"></i> Registrar Entrada
            </button>
          </div>
        </div>

        <?php if (isset($_GET['exito'])): ?>
          <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i> Entrada registrada correctamente.
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">

        
        <div class="row">
          <?= tarjeta("verde",  (string)$valorInvertidoHoy, "Invertido hoy",    "check") ?>
          <?= tarjeta("rojo",   (string)$sinStock,          "Productos sin stock",  "calendario") ?>
          <?= tarjeta("blanco", (string)$stockTotal,        "Stock total",   "bolsa") ?>
          <?= tarjeta("azul",   (string)$stockBajo,         "Stock bajo", "estadisticas") ?>
        </div>

        <?php
        $columnas = ["#", "Producto", "Proveedor", "Cantidad", "Unidad", "Costo", "Factura", "Fecha Entrada", "Caducidad", "Lote", "Acciones"];
        $datos = [];
        foreach ($movimientos as $m) {
            $datos[] = [
                $m['idMovimiento'],
                $m['nombreProducto']  ?? '—',
                $m['nombreProveedor'] ?? '—',
                $m['cantidad'],
                $m['unidad'],
                '$' . number_format($m['costo'], 2),
                $m['factura'] ?: '—',
                date('d/m/Y', strtotime($m['fechaEntrada'])),
                $m['fechaCaducidad'] ? date('d/m/Y', strtotime($m['fechaCaducidad'])) : '—',
                $m['Lote'] ?: '—',
                "<button class='btn btn-xs btn-danger btn-borrar' data-id='{$m['idMovimiento']}'>
                    <i class='fas fa-trash'></i>
                 </button>"
            ];
        }
        echo crearTabla("Movimientos de Inventario", $columnas, $datos, "tabla_inventario");
        ?>
      </div>
    </section>
  </div>

  <!-- ══════════════════════════════════════
       MODAL: REGISTRAR ENTRADA
  ══════════════════════════════════════ -->
  <div class="modal fade" id="modalEntrada" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header modal-header-stockit">
          <h5 class="modal-title"><i class="fas fa-truck-loading mr-2"></i>Registrar Entrada de Inventario</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <form id="formEntrada" action="index.php?menu=inventario&submenu=registro" method="POST">
          <div class="modal-body">
            <div class="row">

              <!-- ── Formulario ── -->
              <div class="col-md-8">
                <h5 class="text-orange font-weight-bold"><i class="fas fa-box mr-2"></i>Información del producto</h5>
                <hr>

                <div class="form-group position-relative">
                  <label>Producto *</label>
                  <input type="text" id="m_buscar" class="form-control" placeholder="Buscar por nombre o código..." autocomplete="off">
                  <div id="m_resultados" class="list-group position-absolute w-100 shadow" style="z-index:2000;max-height:180px;overflow-y:auto"></div>
                  <input type="hidden" name="idProducto" id="m_idProducto" required>
                </div>

                <div id="m_info_prod" class="d-flex align-items-center p-3 mb-3" style="background:#E7F1FF;border:1px solid #B6D4FE;border-radius:10px;display:none!important">
                  <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:40px;height:40px;flex-shrink:0">
                    <i class="fas fa-box text-white"></i>
                  </div>
                  <div>
                    <h6 class="m-0 font-weight-bold text-primary" id="m_prod_nombre_cod">—</h6>
                    <small class="text-muted">
                      Stock actual: <span class="text-primary font-weight-bold" id="m_prod_stock">—</span>
                      &nbsp;|&nbsp; Precio compra actual: <span class="text-warning font-weight-bold" id="m_prod_precio">—</span>
                    </small>
                  </div>
                </div>

                <h5 class="text-orange font-weight-bold mt-3"><i class="fas fa-list mr-2"></i>Detalles de la entrada</h5>
                <hr>

                <div class="row">
                  <div class="col-md-4 form-group">
                    <label>Cantidad *</label>
                    <input type="number" name="cantidad" id="m_cantidad" class="form-control" placeholder="0" min="1" required>
                  </div>
                  <div class="col-md-4 form-group">
                    <label>Unidad</label>
                    <select name="unidad" id="m_unidad" class="form-control">
                      <option>Piezas</option>
                      <option>Cajas</option>
                      <option>Kg</option>
                    </select>
                  </div>
                  <div class="col-md-4 form-group">
                    <label>Costo unitario</label>
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                      <input type="number" name="costo" id="m_costo" class="form-control" placeholder="0.00" step="0.01" min="0">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4 form-group">
                    <label>Fecha de entrada *</label>
                    <input type="date" name="fechaEntrada" id="m_fechaEntrada" class="form-control" required>
                  </div>
                  <div class="col-md-4 form-group">
                    <label>Fecha caducidad</label>
                    <input type="date" name="fechaCaducidad" id="m_caducidad" class="form-control">
                  </div>
                  <div class="col-md-4 form-group">
                    <label>No. de lote</label>
                    <input type="text" name="Lote" id="m_lote" class="form-control" placeholder="LOTE-001">
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-8 form-group">
                    <label>Proveedor</label>
                    <select name="idProveedor" id="m_proveedor" class="form-control">
                      <option value="">— Sin proveedor —</option>
                      <?php foreach ($proveedores as $prov): ?>
                        <option value="<?= $prov['idProveedor'] ?>"><?= htmlspecialchars($prov['nombreProveedor']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-4 form-group">
                    <label>No. de factura</label>
                    <input type="text" name="factura" id="m_factura" class="form-control" placeholder="FAC-2025-001">
                  </div>
                </div>

                <div class="form-group">
                  <label>Observaciones</label>
                  <textarea name="Observaciones" id="m_obs" class="form-control" rows="2" placeholder="Notas adicionales..."></textarea>
                </div>
              </div>

              <!-- ── Resumen ── -->
              <div class="col-md-4">
                <div class="card" style="border-radius:15px;border:1px solid #FFE0B2;overflow:hidden">
                  <div class="card-header" style="background:#FFF5E5;border-bottom:1px solid #FFE0B2">
                    <h6 class="text-orange font-weight-bold m-0"><i class="fas fa-clipboard-list mr-2"></i>Resumen en tiempo real</h6>
                  </div>
                  <div class="card-body">
                    <label class="small text-muted">Producto</label>
                    <div class="resumen-box mb-2" id="r_producto"><span class="text-muted">— Sin seleccionar —</span></div>

                    <label class="small text-muted">Proveedor</label>
                    <div class="resumen-box mb-2" id="r_proveedor"><span class="text-muted">—</span></div>

                    <div class="row mb-2">
                      <div class="col-6">
                        <label class="small text-muted">Lote</label>
                        <div class="resumen-box" id="r_lote"><span class="text-muted">—</span></div>
                      </div>
                      <div class="col-6">
                        <label class="small text-muted">Factura</label>
                        <div class="resumen-box" id="r_factura"><span class="text-muted">—</span></div>
                      </div>
                    </div>

                    <hr style="border-color:#FFE0B2">

                    <div class="row mb-2">
                      <div class="col-6">
                        <label class="small text-muted">Cantidad</label>
                        <div class="resumen-valor" id="r_cantidad">0 pzs</div>
                      </div>
                      <div class="col-6">
                        <label class="small text-muted">Costo unit.</label>
                        <div class="resumen-valor" id="r_costoUnit">$0.00</div>
                      </div>
                    </div>

                    <div class="p-3 mb-2" style="background:#FFF5E5;border-radius:10px;border:1px solid #FFE0B2">
                      <label class="small text-muted d-block">Costo total</label>
                      <div style="font-size:2rem;font-weight:bold;color:#E8820C" id="r_costoTotal">$0.00</div>
                    </div>

                    <hr style="border-color:#FFE0B2">

                    <div class="row mb-2">
                      <div class="col-6">
                        <label class="small text-muted">Stock actual</label>
                        <div style="font-size:1.2rem;font-weight:bold;color:#555" id="r_stockActual">—</div>
                      </div>
                      <div class="col-6">
                        <label class="small text-muted">Stock resultante</label>
                        <div class="resumen-stock-ok" id="r_stockResultante">—</div>
                      </div>
                    </div>

                    <label class="small text-muted">Caducidad</label>
                    <div class="resumen-box" id="r_caducidad"><span class="text-muted">— Sin especificar —</span></div>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <div class="modal-footer bg-white border-top">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-warning px-4">
              <i class="fas fa-save mr-1"></i> Guardar Entrada
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>

</div>

<script src="public/plugins/jquery/jquery.min.js"></script>
<script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="public/dist/js/adminlte.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const productos = <?= json_encode(array_values($productos)) ?>;

let stockActual       = 0;
let precioCompraActual = 0;

// ── Fecha local correcta ──────────────────────────────────
function fechaHoy() {
    const h = new Date();
    return h.getFullYear() + '-' +
        String(h.getMonth() + 1).padStart(2, '0') + '-' +
        String(h.getDate()).padStart(2, '0');
}

// ── Abrir modal ───────────────────────────────────────────
function abrirModal() {
    $('#formEntrada')[0].reset();
    $('input[name="actualizarPrecio"]').remove();
    $('#m_fechaEntrada').val(fechaHoy());
    $('#m_info_prod').hide();
    limpiarResumen();
    $('#modalEntrada').modal('show');
}

// ── Buscador ──────────────────────────────────────────────
$('#m_buscar').on('keyup', function() {
    const q = $(this).val().toLowerCase().trim();
    const res = $('#m_resultados');
    res.empty();
    if (q.length < 2) return;

    const filtrados = productos.filter(p =>
        p.nombreProducto.toLowerCase().includes(q) ||
        (p.codigoBarras && p.codigoBarras.includes(q))
    ).slice(0, 8);

    if (!filtrados.length) {
        res.append('<div class="list-group-item text-muted">Sin resultados</div>');
        return;
    }
    filtrados.forEach(p => {
        const item = $(`<a class="list-group-item list-group-item-action d-flex justify-content-between">
            <span>${p.nombreProducto}</span>
            <small class="text-muted">Stock: ${p.stockEnGeneral}</small>
        </a>`);
        item.on('click', () => seleccionarProducto(p));
        res.append(item);
    });
});

function seleccionarProducto(p) {
    $('#m_buscar').val(p.nombreProducto);
    $('#m_idProducto').val(p.idProducto);
    $('#m_resultados').empty();

    stockActual        = parseInt(p.stockEnGeneral) || 0;
    precioCompraActual = parseFloat(p.precioCompra) || 0;

    $('#m_prod_nombre_cod').text(`${p.nombreProducto} — Cód: ${p.codigoBarras || 'S/N'}`);
    $('#m_prod_stock').text(`${stockActual} pzs`);
    $('#m_prod_precio').text(`$${precioCompraActual.toFixed(2)}`);
    $('#m_info_prod').show();

    $('#r_producto').html(`<strong>${p.nombreProducto}</strong>`);
    $('#r_stockActual').text(`${stockActual} pzs`);
    actualizarResumen();
}

// ── Resumen en tiempo real ────────────────────────────────
function actualizarResumen() {
    const cantidad  = parseFloat($('#m_cantidad').val()) || 0;
    const costo     = parseFloat($('#m_costo').val())    || 0;
    const unidad    = $('#m_unidad').val();
    const proveedor = $('#m_proveedor option:selected').text();
    const lote      = $('#m_lote').val().trim();
    const factura   = $('#m_factura').val().trim();
    const caducidad = $('#m_caducidad').val();

    flash('r_cantidad',   `${cantidad} ${unidad.toLowerCase()}`);
    flash('r_costoUnit',  `$${costo.toFixed(2)}`);
    flash('r_costoTotal', `$${(cantidad * costo).toFixed(2)}`);

    const resultante = stockActual + cantidad;
    const elRes = document.getElementById('r_stockResultante');
    elRes.textContent = `${resultante} pzs`;
    elRes.className   = resultante <= 10 ? 'resumen-stock-bajo' : 'resumen-stock-ok';

    $('#r_proveedor').html(proveedor && proveedor !== '— Sin proveedor —'
        ? `<strong>${proveedor}</strong>` : '<span class="text-muted">—</span>');
    $('#r_lote').html(lote    ? `<strong>${lote}</strong>`    : '<span class="text-muted">—</span>');
    $('#r_factura').html(factura ? `<strong>${factura}</strong>` : '<span class="text-muted">—</span>');

    if (caducidad) {
        const diff  = Math.ceil((new Date(caducidad) - new Date()) / 86400000);
        const color = diff < 0 ? '#dc3545' : diff <= 30 ? '#ffc107' : '#28a745';
        const label = diff < 0 ? ' ⚠ Vencida' : diff <= 30 ? ` ⚠ ${diff} días` : '';
        const fecha = new Date(caducidad + 'T00:00:00').toLocaleDateString('es-MX');
        $('#r_caducidad').html(`<strong style="color:${color}">${fecha}${label}</strong>`);
    } else {
        $('#r_caducidad').html('<span class="text-muted">— Sin especificar —</span>');
    }
}

function flash(id, valor) {
    const el = document.getElementById(id);
    if (!el) return;
    el.textContent = valor;
    el.classList.remove('flash');
    void el.offsetWidth;
    el.classList.add('flash');
}

function limpiarResumen() {
    stockActual = 0; precioCompraActual = 0;
    ['r_cantidad','r_costoUnit','r_costoTotal','r_stockActual','r_stockResultante'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.textContent = id.includes('costo') ? '$0.00' : '—';
    });
    $('#r_producto,#r_proveedor,#r_lote,#r_factura,#r_caducidad').html('<span class="text-muted">—</span>');
}

$(document).on('input change', '#m_cantidad,#m_costo,#m_unidad,#m_proveedor,#m_lote,#m_factura,#m_caducidad', actualizarResumen);

$(document).on('click', function(e) {
    if (!$(e.target).closest('#m_buscar, #m_resultados').length)
        $('#m_resultados').empty();
});

// ── Interceptar submit: alerta si costo subió ─────────────
$('#formEntrada').on('submit', function(e) {
    const costoNuevo = parseFloat($('#m_costo').val()) || 0;

    if (costoNuevo > precioCompraActual && precioCompraActual > 0) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: '⚠ El costo subió',
            html: `El precio de compra anterior era <b>$${precioCompraActual.toFixed(2)}</b>
                   y el nuevo es <b>$${costoNuevo.toFixed(2)}</b>.<br><br>
                   ¿Deseas actualizar el precio de compra del producto?`,
            showDenyButton: true,
            confirmButtonText: 'Sí, actualizar precio',
            denyButtonText: 'No, solo registrar entrada',
            confirmButtonColor: '#E8820C'
        }).then(r => {
            if (r.isConfirmed) {
                $('<input>').attr({ type:'hidden', name:'actualizarPrecio', value:'1' })
                            .appendTo('#formEntrada');
            }
            $('#formEntrada').off('submit').submit();
        });
    }
});

// ── Borrar movimiento ─────────────────────────────────────
$(document).on('click', '.btn-borrar', function() {
    const id = $(this).data('id');
    Swal.fire({
        title: '¿Eliminar movimiento?',
        text: 'Se borrará este registro del inventario.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Sí, eliminar'
    }).then(r => {
        if (r.isConfirmed)
            window.location = `index.php?menu=inventario&submenu=borrar&id=${id}`;
    });
});
</script>
</body>
</html>