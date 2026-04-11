<?php
// Componentes de StockIt
include __DIR__ . "/includes/tarjeta.php";
include __DIR__ . "/includes/tablas.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>StockIt | Gestión de Operaciones</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../public/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../public/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <style>
    /* ============================================
       NAVBAR SUPERIOR
    ============================================ */
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

    /* ============================================
       SIDEBAR
    ============================================ */
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

    .btn-sidebar i { color: #333 !important; }

    .user-panel,
    .form-inline {
      border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
    }

    /* ============================================
       CONTENIDO PRINCIPAL
    ============================================ */
    .content-wrapper { background-color: #FFF5E5 !important; }
    .text-stockit { color: #E8820C !important; }

    /* ---- Pestañas de Contenido (SOLO #pills-tab, no sidebar) ---- */
    #pills-tab .nav-link {
      color: #E8820C;
      border: 1px solid #E8820C;
      border-radius: 5px;
      margin-right: 10px;
      background: white;
    }
    #pills-tab .nav-link.active {
      background-color: #E8820C !important;
      color: white !important;
      border-color: #E8820C !important;
    }

    /* ---- Sidebar: fondo transparente, texto blanco ---- */
    .nav-sidebar .nav-link {
      background: transparent !important;
      color: #ffffff !important;
    }
    .nav-sidebar .nav-link:hover {
      background-color: rgba(255,255,255,0.15) !important;
    }
    .nav-sidebar .nav-link.active,
    .nav-sidebar .nav-item.menu-open > .nav-link {
      background-color: rgba(0,0,0,0.15) !important;
      color: #ffffff !important;
    }
    .nav-sidebar .nav-link p,
    .nav-sidebar .nav-link i { color: #ffffff !important; }

    /* ---- Devoluciones: Motivos ---- */
    .motivo-card {
      border: 2px solid #E8820C;
      border-radius: 8px;
      padding: 12px 15px;
      margin-bottom: 10px;
      cursor: pointer;
      background: white;
      transition: all 0.2s;
      color: #555;
    }
    .motivo-card:hover, .motivo-card.selected {
      background-color: #FFF3E0;
      border-color: #E8820C;
      color: #E8820C;
      font-weight: bold;
    }
    .motivo-card i { color: #E8820C; margin-right: 8px; }

    /* ---- Corte: filas clickeables ---- */
    #historial-transacciones tr { cursor: pointer; }
    #historial-transacciones tr:hover { background-color: #FFF3E0; }

    /* Tarjeta Finalizar Transacción */
    .card-dark-header { background-color: #343a40; color: white; border-radius: 5px 5px 0 0; }

    /* Métodos de Pago */
    .metodo-pago { border: 1px solid #ffc107; background: white; color: #E8820C; margin-bottom: 8px; text-align: left; padding: 15px; transition: 0.3s; }
    .metodo-pago.active { background-color: #FFF9C4 !important; border-width: 2px; }

    /* Botones de Acción */
    .btn-procesar { background-color: #0056b3; color: white; font-weight: bold; padding: 12px; border: none; }
    .btn-cancelar-operacion { background-color: #dc3545; color: white; border: none; padding: 8px; }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../public/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- ============================================================
       NAVBAR SUPERIOR
  ============================================================ -->
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
      <!-- Búsqueda -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit"><i class="fas fa-search"></i></button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search"><i class="fas fa-times"></i></button>
              </div>
            </div>
          </form>
        </div>
      </li>

      <!-- Notificaciones -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item"><i class="fas fa-envelope mr-2"></i> 4 new messages <span class="float-right text-muted text-sm">3 mins</span></a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item"><i class="fas fa-users mr-2"></i> 8 friend requests <span class="float-right text-muted text-sm">12 hours</span></a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item"><i class="fas fa-file mr-2"></i> 3 new reports <span class="float-right text-muted text-sm">2 days</span></a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
    </ul>
  </nav>

  <!-- ============================================================
       SIDEBAR
  ============================================================ -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index.php" class="brand-link">
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

      <!-- Búsqueda Sidebar -->
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

      <!-- Menú Sidebar -->
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

  <!-- ============================================================
       CONTENIDO PRINCIPAL
  ============================================================ -->
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="d-inline font-weight-bold">Gestión de</h1>
            <h1 class="d-inline text-stockit" style="font-style: italic;">Operaciones</h1>
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

          <!-- TAB: VENTA DIRECTA -->
          <div class="tab-pane fade show active" id="tab-venta">
            <div class="row">
              <div class="col-md-7">
                <div class="card shadow-sm border-top border-warning">
                  <div class="card-header bg-white">
                    <h3 class="card-title text-stockit font-weight-bold"><i class="fas fa-shopping-cart"></i> Carrito de Compras</h3>
                  </div>
                  <div class="card-body">
                    <input type="text" id="busqueda-local" class="form-control mb-3" placeholder="🔍 Buscar producto por nombre...">
                    <div id="resultados-locales" class="list-group position-absolute w-100 shadow" style="z-index: 1050;"></div>
                    <table class="table table-sm">
                      <thead><tr class="text-orange"><th>Producto</th><th>Cant.</th><th>Subtotal</th><th></th></tr></thead>
                      <tbody id="lista-venta"></tbody>
                    </table>
                    <div class="text-right mt-4">
                      <h3>Total: <span class="text-stockit font-weight-bold" id="total-venta">$0.00</span></h3>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-5">
                <div class="card shadow-sm">
                  <div class="card-header card-dark-header">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-credit-card"></i> Finalizar Transacción</h3>
                  </div>
                  <div class="card-body">
                    <label class="text-muted small">Seleccione Método:</label>
                    <button class="btn btn-block metodo-pago shadow-sm" data-metodo="Efectivo"><i class="fas fa-money-bill mr-2"></i> Dinero en Efectivo</button>
                    <button class="btn btn-block metodo-pago shadow-sm" data-metodo="Tarjeta"><i class="fas fa-credit-card mr-2"></i> Tarjeta de Débito/Crédito</button>
                    <button id="btn-finalizar" class="btn btn-block btn-procesar mt-4 shadow">PROCESAR PAGO</button>
                    <button class="btn btn-block btn-cancelar-operacion mt-2 shadow-sm small" onclick="location.reload()">CANCELAR</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- TAB: DEVOLUCIONES -->
          <div class="tab-pane fade" id="tab-devolucion">
            <div class="row">
              <!-- Columna Izquierda: Motivos rapidos -->
              <div class="col-md-3">
                <div class="card shadow-sm border-top border-warning">
                  <div class="card-header bg-white">
                    <h5 class="card-title text-stockit font-weight-bold mb-0"><i class="fas fa-lightbulb"></i> Motivos Comunes</h5>
                  </div>
                  <div class="card-body p-2">
                    <p class="text-muted small px-1">Clic para seleccionar:</p>
                    <div class="motivo-card" onclick="seleccionarMotivo(this, 'Producto Defectuoso')"><i class="fas fa-exclamation-triangle"></i> Producto Defectuoso</div>
                    <div class="motivo-card" onclick="seleccionarMotivo(this, 'Cambio de Talla/Color')"><i class="fas fa-exchange-alt"></i> Cambio de Talla/Color</div>
                    <div class="motivo-card" onclick="seleccionarMotivo(this, 'No Era Lo Esperado')"><i class="fas fa-times-circle"></i> No Era Lo Esperado</div>
                    <div class="motivo-card" onclick="seleccionarMotivo(this, 'Producto Caducado')"><i class="fas fa-calendar-times"></i> Producto Caducado</div>
                    <div class="motivo-card" onclick="seleccionarMotivo(this, 'Error en Cobro')"><i class="fas fa-money-bill-wave"></i> Error en Cobro</div>
                    <div class="motivo-card" onclick="seleccionarMotivo(this, 'Producto Incompleto')"><i class="fas fa-box-open"></i> Producto Incompleto</div>
                  </div>
                </div>
              </div>
              <!-- Columna Derecha: Formulario + Tabla -->
              <div class="col-md-9">
                <div class="card shadow-sm">
                  <div class="card-header bg-white">
                    <h3 class="card-title text-stockit font-weight-bold"><i class="fas fa-undo-alt"></i> Registrar Devolución</h3>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-4 form-group">
                        <label>Producto:</label>
                        <input type="text" id="dev-prod" class="form-control" placeholder="Nombre del producto">
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
                      <div class="col-md-4 form-group">
                        <label>Cantidad:</label>
                        <input type="number" id="dev-cantidad" class="form-control" value="1" min="1">
                      </div>
                    </div>
                    <button class="btn btn-warning px-4 font-weight-bold text-white" onclick="registrarDevolucion()">
                      <i class="fas fa-plus mr-1"></i> Registrar Devolución
                    </button>
                  </div>
                </div>
                <div class="card shadow-sm mt-2">
                  <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-stockit font-weight-bold mb-0"><i class="fas fa-list"></i> Devoluciones del Día</h5>
                    <span class="badge badge-warning" id="dev-contador">0 registros</span>
                  </div>
                  <div class="card-body p-0">
                    <table class="table table-hover mb-0 text-center">
                      <thead class="thead-light">
                        <tr><th>Hora</th><th>Producto</th><th>Cant.</th><th>Motivo</th><th>Estado</th></tr>
                      </thead>
                      <tbody id="tabla-devoluciones">
                        <tr id="dev-empty-row"><td colspan="5" class="text-muted py-3">No hay devoluciones registradas hoy.</td></tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- TAB: CORTE DIARIO -->
          <div class="tab-pane fade" id="tab-corte">
            <div class="card shadow-sm">
              <div class="card-header bg-dark">
                <h3 class="card-title">Corte de Caja Diario</h3>
              </div>
              <div class="card-body">
                <h4 class="text-stockit">Vendido Hoy: <b id="corte-total">$0.00</b></h4>
                <table class="table table-hover mt-3 text-center">
                  <thead><tr><th>Hora</th><th>Artículos</th><th>Método</th><th>Monto</th><th></th></tr></thead>
                  <tbody id="historial-transacciones"></tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>
  </div><!-- /.content-wrapper -->

</div><!-- /.wrapper -->

<!-- Scripts -->
<script src="../public/plugins/jquery/jquery.min.js"></script>
<script src="../public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../public/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let carrito = [];
let historialVentas = [];
let historialDevoluciones = [];
let acumuladoCorte = 0;
let pagoSeleccionado = "";

const productosDemo = [
    { id: 1, nombre: "Gansito Marinela 50g", precio: 15.50 },
    { id: 2, nombre: "Coca Cola 600ml", precio: 18.00 }
];

// ── Métodos de pago ──────────────────────────────────────────
$('.metodo-pago').on('click', function() {
    $('.metodo-pago').removeClass('active');
    $(this).addClass('active');
    pagoSeleccionado = $(this).data('metodo');
});

// ── Carrito ──────────────────────────────────────────────────
function agregarAlCarrito(p) {
    let ex = carrito.find(i => i.id === p.id);
    if(ex) { ex.cantidad++; } else { carrito.push({...p, cantidad: 1}); }
    actualizarVista();
}

function actualizarVista() {
    let t = $('#lista-venta'); t.empty(); let tot = 0;
    carrito.forEach((p, i) => {
        let sub = p.precio * p.cantidad; tot += sub;
        t.append(`<tr>
          <td>${p.nombre}</td>
          <td>${p.cantidad}</td>
          <td>$${sub.toFixed(2)}</td>
          <td><button class="btn btn-xs btn-danger" onclick="carrito.splice(${i},1); actualizarVista();">x</button></td>
        </tr>`);
    });
    $('#total-venta').text(`$${tot.toFixed(2)}`);
}

// ── Finalizar venta ──────────────────────────────────────────
$('#btn-finalizar').on('click', function() {
    if (carrito.length === 0 || pagoSeleccionado === "") return;
    let monto = carrito.reduce((s, p) => s + (p.precio * p.cantidad), 0);
    let hora = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    let venta = { hora, productos: [...carrito], metodo: pagoSeleccionado, monto };
    historialVentas.unshift(venta);
    acumuladoCorte += monto;

    Swal.fire({ icon: 'success', title: 'Venta Exitosa', showConfirmButton: false, timer: 1000 });
    $('#corte-total').text(`$${acumuladoCorte.toFixed(2)}`);
    renderHistorial();

    carrito = []; pagoSeleccionado = ""; $('.metodo-pago').removeClass('active'); actualizarVista();
});

// ── Renderizar historial de corte ────────────────────────────
function renderHistorial() {
    let h = $('#historial-transacciones'); h.empty();
    historialVentas.forEach((v, idx) => {
        h.append(`<tr onclick="verDetalleVenta(${idx})" title="Ver detalle">
          <td>${v.hora}</td>
          <td>${v.productos.length} items</td>
          <td>${v.metodo}</td>
          <td>$${v.monto.toFixed(2)}</td>
          <td><i class="fas fa-search text-stockit"></i></td>
        </tr>`);
    });
}

// ── Modal detalle de venta ───────────────────────────────────
function verDetalleVenta(idx) {
    let v = historialVentas[idx];
    let filas = v.productos.map(p =>
        `<tr>
          <td style="text-align:left">${p.nombre}</td>
          <td>${p.cantidad}</td>
          <td>$${(p.precio * p.cantidad).toFixed(2)}</td>
        </tr>`
    ).join('');

    Swal.fire({
        title: `<span style="color:#E8820C;font-style:italic">Detalle de Venta</span>`,
        html: `
          <div style="text-align:left; margin-bottom:10px;">
            <b>Hora:</b> ${v.hora} &nbsp;|&nbsp; <b>Método:</b> ${v.metodo}
          </div>
          <table style="width:100%; border-collapse:collapse; font-size:14px;">
            <thead>
              <tr style="background:#FFF3E0; color:#E8820C;">
                <th style="padding:8px; text-align:left; border-bottom:2px solid #E8820C;">Producto</th>
                <th style="padding:8px; border-bottom:2px solid #E8820C;">Cant.</th>
                <th style="padding:8px; border-bottom:2px solid #E8820C;">Subtotal</th>
              </tr>
            </thead>
            <tbody>${filas}</tbody>
          </table>
          <div style="text-align:right; margin-top:14px; font-size:18px; font-weight:bold; color:#E8820C;">
            Total: $${v.monto.toFixed(2)}
          </div>`,
        confirmButtonColor: '#E8820C',
        confirmButtonText: 'Cerrar',
        width: '480px'
    });
}

// ── Búsqueda de productos ────────────────────────────────────
document.getElementById('busqueda-local').addEventListener('keyup', function(e) {
    let q = e.target.value.toLowerCase();
    let r = $('#resultados-locales'); r.empty();
    if(q.length > 0) {
        productosDemo.filter(p => p.nombre.toLowerCase().includes(q)).forEach(p => {
            let item = $(`<a class="list-group-item list-group-item-action">${p.nombre} <b class="float-right">$${p.precio}</b></a>`);
            item.on('click', () => { agregarAlCarrito(p); r.empty(); e.target.value = ""; });
            r.append(item);
        });
    }
});

// ── Devoluciones ─────────────────────────────────────────────
function seleccionarMotivo(el, motivo) {
    document.querySelectorAll('.motivo-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('dev-motivo').value = motivo;
}

function registrarDevolucion() {
    let prod = document.getElementById('dev-prod').value.trim();
    let motivo = document.getElementById('dev-motivo').value;
    let cantidad = document.getElementById('dev-cantidad').value;
    if (!prod) { Swal.fire({ icon:'warning', title:'Campo vacío', text:'Ingresa el nombre del producto.', timer:1500, showConfirmButton:false }); return; }

    let hora = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    historialDevoluciones.unshift({ hora, prod, cantidad, motivo });

    let tbody = document.getElementById('tabla-devoluciones');
    let emptyRow = document.getElementById('dev-empty-row');
    if (emptyRow) emptyRow.remove();

    let tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${hora}</td>
      <td>${prod}</td>
      <td>${cantidad}</td>
      <td><span class="badge badge-warning">${motivo}</span></td>
      <td><span class="badge badge-success">Procesado</span></td>`;
    tbody.prepend(tr);

    let count = historialDevoluciones.length;
    document.getElementById('dev-contador').textContent = count + (count === 1 ? ' registro' : ' registros');

    document.getElementById('dev-prod').value = '';
    document.getElementById('dev-cantidad').value = 1;
    document.querySelectorAll('.motivo-card').forEach(c => c.classList.remove('selected'));

    Swal.fire({ icon:'success', title:'Devolución Registrada', showConfirmButton:false, timer:1000 });
}
</script>
</body>
</html>