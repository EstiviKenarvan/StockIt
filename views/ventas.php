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
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <style>
    .main-header.navbar,.main-header.navbar-white,.main-header.navbar-light,nav.main-header{background-color:#E8820C!important;border-bottom:none!important}
    .main-header .nav-link,.main-header .nav-link i,.navbar-light .navbar-nav .nav-link{color:#fff!important}
    .main-sidebar,.main-sidebar:before,.brand-link,.sidebar-dark-primary{background-color:#E8820C!important}
    .nav-sidebar .nav-link,.brand-link .brand-text,.sidebar .user-panel .info a,.nav-sidebar .nav-link i{color:#fff!important}
    .form-control-sidebar,.btn-sidebar{background-color:#fff!important;border:1px solid #ddd!important;color:#333!important}
    .btn-sidebar i{color:#333!important}
    .user-panel,.form-inline{border-bottom:1px solid rgba(255,255,255,.2)!important}
    .content-wrapper{background-color:#FFF5E5!important}
    .text-stockit{color:#E8820C!important}
    #pills-tab .nav-link{color:#E8820C;border:1px solid #E8820C;border-radius:5px;margin-right:10px;background:white}
    #pills-tab .nav-link.active{background-color:#E8820C!important;color:white!important;border-color:#E8820C!important}
    .nav-sidebar .nav-link{background:transparent!important;color:#fff!important}
    .nav-sidebar .nav-link:hover{background-color:rgba(255,255,255,.15)!important}
    .nav-sidebar .nav-link.active,.nav-sidebar .nav-item.menu-open>.nav-link{background-color:rgba(0,0,0,.15)!important;color:#fff!important}
    .nav-sidebar .nav-link p,.nav-sidebar .nav-link i{color:#fff!important}
    .motivo-card{border:2px solid #E8820C;border-radius:8px;padding:12px 15px;margin-bottom:10px;cursor:pointer;background:white;transition:all .2s;color:#555}
    .motivo-card:hover,.motivo-card.selected{background-color:#FFF3E0;border-color:#E8820C;color:#E8820C;font-weight:bold}
    .motivo-card i{color:#E8820C;margin-right:8px}
    #historial-transacciones tr{cursor:pointer}
    #historial-transacciones tr:hover{background-color:#FFF3E0}
    .metodo-pago{border:2px solid #E8820C!important;background:white;color:#E8820C;margin-bottom:8px;text-align:left;padding:15px;transition:.3s;border-radius:12px!important;font-weight:bold}
    .metodo-pago.active{background-color:#FFF9C4!important;border-width:3px!important}
    #resultados-locales{max-height:220px;overflow-y:auto}
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="public/dist/img/AdminLTELogo.png" alt="Logo" height="60" width="60">
  </div>

<?php
$paginaActiva = "ventas";
include __DIR__ . "/includes/barras.php";
?>

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
          <li class="nav-item"><a class="nav-link shadow-sm" data-toggle="pill" href="#tab-devolucion">Devoluciones</a></li>
          <li class="nav-item"><a class="nav-link shadow-sm" data-toggle="pill" href="#tab-corte">Corte Diario</a></li>
        </ul>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="tab-content">

          <!-- ═══════════════════════════════════════════════
               TAB: VENTA DIRECTA
          ═══════════════════════════════════════════════ -->
          <div class="tab-pane fade show active" id="tab-venta">
            <div class="row">
              <!-- Carrito -->
              <div class="col-md-7">
                <div class="card shadow-sm border-top border-warning">
                  <div class="card-header bg-white">
                    <h3 class="card-title text-stockit font-weight-bold"><i class="fas fa-shopping-cart"></i> Carrito de Compras</h3>
                  </div>
                  <div class="card-body">
                    <div class="position-relative">
                      <input type="text" id="busqueda-local" class="form-control mb-1" placeholder="🔍 Buscar producto por nombre...">
                      <div id="resultados-locales" class="list-group position-absolute w-100 shadow" style="z-index:1050;"></div>
                    </div>
                    <table class="table table-sm mt-3">
                      <thead><tr class="text-stockit"><th>Producto</th><th>Cant.</th><th>Subtotal</th><th></th></tr></thead>
                      <tbody id="lista-venta"></tbody>
                    </table>
                    <div class="text-right mt-2">
                      <h3>Total: <span class="text-stockit font-weight-bold" id="total-venta">$0.00</span></h3>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Finalizar -->
              <div class="col-md-5">
                <div class="card shadow-sm" style="border-radius:15px;border:none">
                  <div class="card-header" style="background:#343a40;color:white;border-radius:15px 15px 0 0">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-credit-card"></i> Finalizar Transacción</h3>
                  </div>
                  <div class="card-body">
                    <label class="text-muted small mb-2">Seleccione Método de Pago:</label>
                    <button class="btn btn-block metodo-pago shadow-sm" data-metodo="1">
                      <i class="fas fa-money-bill-wave mr-2"></i> Dinero en Efectivo
                    </button>
                    <button class="btn btn-block metodo-pago shadow-sm mb-4" data-metodo="2">
                      <i class="fas fa-credit-card mr-2"></i> Tarjeta Débito/Crédito
                    </button>
                    <button id="btn-finalizar" class="btn btn-block shadow" style="background:#E8820C;color:white;font-weight:bold;padding:15px;border-radius:12px;border:none;font-size:1.1rem">
                      <i class="fas fa-check-circle mr-2"></i> PROCESAR PAGO
                    </button>
                    <button class="btn btn-block shadow-sm mt-3" onclick="limpiarCarrito()" style="background:#6c757d;color:white;border-radius:12px;padding:10px;border:none;font-weight:bold">
                      <i class="fas fa-times mr-2"></i> CANCELAR OPERACIÓN
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- ═══════════════════════════════════════════════
               TAB: DEVOLUCIONES
          ═══════════════════════════════════════════════ -->
          <div class="tab-pane fade" id="tab-devolucion">
            <div class="row">
              <!-- Motivos rápidos -->
              <div class="col-md-3">
                <div class="card shadow-sm border-top border-warning">
                  <div class="card-header bg-white">
                    <h5 class="card-title text-stockit font-weight-bold mb-0"><i class="fas fa-lightbulb"></i> Motivos Comunes</h5>
                  </div>
                  <div class="card-body p-2">
                    <p class="text-muted small px-1">Clic para seleccionar:</p>
                    <div class="motivo-card" onclick="seleccionarMotivo(this,'Producto Defectuoso')"><i class="fas fa-exclamation-triangle"></i> Producto Defectuoso</div>
                    <div class="motivo-card" onclick="seleccionarMotivo(this,'Cambio de Talla/Color')"><i class="fas fa-exchange-alt"></i> Cambio de Talla/Color</div>
                    <div class="motivo-card" onclick="seleccionarMotivo(this,'No Era Lo Esperado')"><i class="fas fa-times-circle"></i> No Era Lo Esperado</div>
                    <div class="motivo-card" onclick="seleccionarMotivo(this,'Producto Caducado')"><i class="fas fa-calendar-times"></i> Producto Caducado</div>
                    <div class="motivo-card" onclick="seleccionarMotivo(this,'Error en Cobro')"><i class="fas fa-money-bill-wave"></i> Error en Cobro</div>
                    <div class="motivo-card" onclick="seleccionarMotivo(this,'Producto Incompleto')"><i class="fas fa-box-open"></i> Producto Incompleto</div>
                  </div>
                </div>
              </div>

              <!-- Formulario devolución -->
              <div class="col-md-9">
                <div class="card shadow-sm">
                  <div class="card-header bg-white">
                    <h3 class="card-title text-stockit font-weight-bold"><i class="fas fa-undo-alt"></i> Registrar Devolución</h3>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-4 form-group">
                        <label>Producto:</label>
                        <div class="position-relative">
                          <input type="text" id="dev-prod-nombre" class="form-control" placeholder="Buscar producto...">
                          <input type="hidden" id="dev-prod-id">
                          <div id="dev-resultados" class="list-group position-absolute w-100 shadow" style="z-index:1050;max-height:180px;overflow-y:auto"></div>
                        </div>
                      </div>
                      <div class="col-md-4 form-group">
                        <label>Motivo:</label>
                        <select id="dev-motivo" class="form-control">
                          <option>Producto Defectuoso</option>
                          <option>Cambio de Talla/Color</option>
                          <option>No Era Lo Esperado</option>
                          <option>Producto Caducado</option>
                          <option>Error en Cobro</option>
                          <option>Producto Incompleto</option>
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

                <!-- Tabla devoluciones del día -->
                <div class="card shadow-sm mt-2">
                  <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-stockit font-weight-bold mb-0"><i class="fas fa-list"></i> Devoluciones Recientes</h5>
                    <span class="badge badge-warning"><?= count($devoluciones) ?> registros</span>
                  </div>
                  <div class="card-body p-0">
                    <table class="table table-hover mb-0 text-center">
                      <thead class="thead-light">
                        <tr><th>Fecha</th><th>Producto</th><th>Cant.</th><th>Motivo</th><th>Estado</th></tr>
                      </thead>
                      <tbody id="tabla-devoluciones">
                        <?php if (empty($devoluciones)): ?>
                          <tr><td colspan="5" class="text-muted py-3">No hay devoluciones registradas.</td></tr>
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
          </div>

          <!-- ═══════════════════════════════════════════════
               TAB: CORTE DIARIO
          ═══════════════════════════════════════════════ -->
          <div class="tab-pane fade" id="tab-corte">
            <div class="card shadow-sm">
              <div class="card-header bg-dark">
                <h3 class="card-title text-white">Corte de Caja Diario</h3>
              </div>
              <div class="card-body">
                <h4 class="text-stockit">Vendido Hoy: <b>$<?= number_format($totalHoy, 2) ?></b></h4>
                <table class="table table-hover mt-3 text-center">
                  <thead><tr><th>Hora</th><th>Artículos</th><th>Método</th><th>Monto</th><th></th></tr></thead>
                  <tbody id="historial-transacciones">
                    <?php if (empty($ventasHoy)): ?>
                      <tr><td colspan="5" class="text-muted py-3">Sin ventas registradas hoy.</td></tr>
                    <?php else: ?>
                      <?php foreach ($ventasHoy as $v): ?>
                        <tr onclick="verDetalleVenta(<?= $v['idVenta'] ?>)" title="Ver detalle">
                          <td><?= date('H:i', strtotime($v['fechaHora'])) ?></td>
                          <td><?= $v['totalItems'] ?> items</td>
                          <td><?= $v['idMetodoPago'] == 1 ? '<i class="fas fa-money-bill-wave"></i> Efectivo' : '<i class="fas fa-credit-card"></i> Tarjeta' ?></td>
                          <td class="font-weight-bold text-stockit">$<?= number_format($v['totalVenta'], 2) ?></td>
                          <td><i class="fas fa-search text-stockit"></i></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div><!-- /.tab-content -->
      </div>
    </section>
  </div><!-- /.content-wrapper -->
</div><!-- /.wrapper -->

<script src="public/plugins/jquery/jquery.min.js"></script>
<script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="public/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let carrito = [];
let pagoSeleccionado = null;

// ── Método de pago ────────────────────────────────────────
$('.metodo-pago').on('click', function() {
    $('.metodo-pago').removeClass('active');
    $(this).addClass('active');
    pagoSeleccionado = $(this).data('metodo');
});

// ── Buscar productos (AJAX → BD real) ────────────────────
let timeoutBusqueda;
$('#busqueda-local').on('keyup', function() {
    clearTimeout(timeoutBusqueda);
    const q = $(this).val().trim();
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
                const item = $(`<a class="list-group-item list-group-item-action d-flex justify-content-between">
                    <span>${p.nombreProducto}</span>
                    <span><b class="text-stockit">$${parseFloat(p.precioVenta).toFixed(2)}</b>
                    <small class="text-muted ml-2">Stock: ${p.stockEnGeneral}</small></span>
                </a>`);
                item.on('click', () => {
                    agregarAlCarrito({ id: p.idProducto, nombre: p.nombreProducto, precio: parseFloat(p.precioVenta) });
                    res.empty();
                    $('#busqueda-local').val('');
                });
                res.append(item);
            });
        }, 'json');
    }, 300);
});

// ── Carrito ───────────────────────────────────────────────
function agregarAlCarrito(p) {
    const ex = carrito.find(i => i.id === p.id);
    if (ex) { ex.cantidad++; } else { carrito.push({ ...p, cantidad: 1 }); }
    actualizarVista();
}

function actualizarVista() {
    const t = $('#lista-venta'); t.empty();
    let tot = 0;
    carrito.forEach((p, i) => {
        const sub = p.precio * p.cantidad; tot += sub;
        t.append(`<tr>
          <td>${p.nombre}</td>
          <td>
            <div class="input-group input-group-sm" style="width:90px">
              <div class="input-group-prepend"><button class="btn btn-outline-secondary btn-sm" onclick="cambiarCantidad(${i},-1)">-</button></div>
              <input type="text" class="form-control text-center" value="${p.cantidad}" readonly>
              <div class="input-group-append"><button class="btn btn-outline-secondary btn-sm" onclick="cambiarCantidad(${i},1)">+</button></div>
            </div>
          </td>
          <td>$${sub.toFixed(2)}</td>
          <td><button class="btn btn-xs btn-danger" onclick="carrito.splice(${i},1);actualizarVista()">x</button></td>
        </tr>`);
    });
    $('#total-venta').text(`$${tot.toFixed(2)}`);
}

function cambiarCantidad(i, delta) {
    carrito[i].cantidad += delta;
    if (carrito[i].cantidad <= 0) carrito.splice(i, 1);
    actualizarVista();
}

function limpiarCarrito() {
    carrito = []; pagoSeleccionado = null;
    $('.metodo-pago').removeClass('active');
    actualizarVista();
}

// ── Procesar pago ─────────────────────────────────────────
$('#btn-finalizar').on('click', function() {
    if (!carrito.length) {
        Swal.fire({ icon:'warning', title:'Carrito vacío', text:'Agrega productos antes de procesar.', timer:1500, showConfirmButton:false });
        return;
    }
    if (!pagoSeleccionado) {
        Swal.fire({ icon:'warning', title:'Método de pago', text:'Selecciona un método de pago.', timer:1500, showConfirmButton:false });
        return;
    }

    $.ajax({
        url: 'index.php?menu=ventas&submenu=procesar-venta',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ carrito, idMetodoPago: pagoSeleccionado }),
        success(res) {
            if (res.ok) {
                Swal.fire({ icon:'success', title:'¡Venta Exitosa!', text:`Venta #${res.idVenta} — $${parseFloat(res.total).toFixed(2)}`, confirmButtonColor:'#E8820C' })
                    .then(() => location.reload());
            } else {
                Swal.fire({ icon:'error', title:'Error', text: res.msg });
            }
        },
        error() { Swal.fire({ icon:'error', title:'Error de conexión' }); }
    });
});

// ── Devoluciones: búsqueda de producto ───────────────────
let timeoutDev;
$('#dev-prod-nombre').on('keyup', function() {
    clearTimeout(timeoutDev);
    const q = $(this).val().trim();
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

    if (!nombre || !idProducto) {
        Swal.fire({ icon:'warning', title:'Producto requerido', text:'Busca y selecciona un producto.', timer:1500, showConfirmButton:false });
        return;
    }

    $.ajax({
        url: 'index.php?menu=ventas&submenu=registrar-devolucion',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ idProducto, motivo, descripcion, cantidad, idVenta }),
        success(res) {
            if (res.ok) {
                // Agregar fila en la tabla sin recargar
                const hora = new Date().toLocaleTimeString([], { hour:'2-digit', minute:'2-digit' });
                const emptyRow = document.getElementById('dev-empty-row');
                if (emptyRow) emptyRow.remove();

                const tr = document.createElement('tr');
                tr.innerHTML = `<td>${hora}</td><td>${nombre}</td><td>${cantidad}</td>
                    <td><span class="badge badge-warning">${motivo}</span></td>
                    <td><span class="badge badge-success">Procesado</span></td>`;
                document.getElementById('tabla-devoluciones').prepend(tr);

                // Limpiar form
                $('#dev-prod-nombre').val(''); $('#dev-prod-id').val('');
                $('#dev-cantidad').val(1); $('#dev-descripcion').val(''); $('#dev-idventa').val('');
                document.querySelectorAll('.motivo-card').forEach(c => c.classList.remove('selected'));

                Swal.fire({ icon:'success', title:'Devolución Registrada', showConfirmButton:false, timer:1000 });
            } else {
                Swal.fire({ icon:'error', title:'Error', text: res.msg });
            }
        },
        error() { Swal.fire({ icon:'error', title:'Error de conexión' }); }
    });
}

// ── Detalle de venta en corte ─────────────────────────────
function verDetalleVenta(idVenta) {
    $.get('index.php?menu=ventas&submenu=buscar-productos', { detalle: idVenta })
     .always(() => {
        // Usamos ajax directo al endpoint de detalle
        Swal.fire({
            title: `<span style="color:#E8820C;font-style:italic">Venta #${idVenta}</span>`,
            html: `<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x text-stockit"></i></div>`,
            showConfirmButton: false,
            didOpen() {
                $.get(`index.php?menu=ventas&submenu=detalle-venta&id=${idVenta}`, data => {
                    let filas = data.map(p =>
                        `<tr><td style="text-align:left">${p.nombreProducto}</td><td>$${parseFloat(p.totalVentaP).toFixed(2)}</td></tr>`
                    ).join('');
                    Swal.update({
                        html: `<table style="width:100%;font-size:14px">
                            <thead><tr style="color:#E8820C">
                              <th style="text-align:left;padding:6px;border-bottom:2px solid #E8820C">Producto</th>
                              <th style="padding:6px;border-bottom:2px solid #E8820C">Subtotal</th>
                            </tr></thead>
                            <tbody>${filas || '<tr><td colspan="2" style="color:#999">Sin detalle</td></tr>'}</tbody>
                          </table>`,
                        showConfirmButton: true,
                        confirmButtonColor: '#E8820C',
                        confirmButtonText: 'Cerrar'
                    });
                }, 'json');
            }
        });
     });
}
</script>
</body>
</html>