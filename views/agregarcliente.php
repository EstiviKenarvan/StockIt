<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>StockIt | Agregar Cliente</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="public/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="public/dist/css/adminlte.min.css">

  <style>
    /* ── Navbar ─────────────────────────────────────────────── */
    .main-header.navbar,
    .main-header.navbar-white,
    .main-header.navbar-light,
    nav.main-header { background-color: #E8820C !important; border-bottom: none !important; }

    .main-header .nav-link,
    .main-header .nav-link i,
    .main-header .navbar-nav .nav-item a,
    .navbar-light .navbar-nav .nav-link { color: #ffffff !important; }

    /* ── Sidebar ─────────────────────────────────────────────── */
    .main-sidebar, .main-sidebar:before, .brand-link, .sidebar-dark-primary { background-color: #E8820C !important; }

    .nav-sidebar .nav-link { background: transparent !important; color: #ffffff !important; }
    .nav-sidebar .nav-link:hover { background-color: rgba(255,255,255,0.15) !important; }
    .nav-sidebar .nav-link.active { background-color: rgba(0,0,0,0.15) !important; color: #ffffff !important; }
    .nav-sidebar .nav-link p, .nav-sidebar .nav-link i { color: #ffffff !important; }
    .brand-link .brand-text, .sidebar .user-panel .info a { color: #ffffff !important; }

    .form-control-sidebar, .btn-sidebar { background-color: #ffffff !important; border: 1px solid #ddd !important; color: #333 !important; }
    .btn-sidebar i { color: #333 !important; }
    .user-panel, .form-inline { border-bottom: 1px solid rgba(255,255,255,0.2) !important; }

    /* ── Contenido ───────────────────────────────────────────── */
    .content-wrapper { background-color: #FFF5E5 !important; }

    /* ── Formulario ──────────────────────────────────────────── */
    .text-orange-dark { color: #E8820C; font-weight: bold; }
    .btn-orange { background-color: #E8820C; color: white; border-radius: 8px; }
    .btn-orange:hover { background-color: #d1750a; color: white; }
    .form-control { border-radius: 6px; border: 1px solid #ccc; }
    .card-custom { border-radius: 15px; border-top: 5px solid #E8820C; }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="public/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <?php
  $paginaActiva = "clientes"; // o analisisproductos, productos, clientes,  
  include __DIR__ . "/includes/barras.php";
  ?>
  <!-- ── Contenido ─────────────────────────────────────────────── -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <p class="mb-0 small">
          <a href="?menu=clientes&submenu=registro" class="text-warning">Gestión de Clientes</a>
          / <span class="text-muted">Agregar Cliente</span>
        </p>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="card card-custom shadow-sm">
          <div class="card-header bg-white">
            <h4 class="text-orange-dark mb-0"><i class="fas fa-user-plus mr-2"></i>Agregar nuevo cliente</h4>
            <small class="text-muted">Ingresa la información del cliente</small>
          </div>

          <form action="?menu=clientes&submenu=registro" method="POST">
            <div class="card-body">

              <h6 class="text-orange-dark mb-3">Datos personales</h6>
              <div class="row">
                <div class="col-md-6 form-group">
                  <label>Nombre(s) *</label>
                  <input type="text" class="form-control" placeholder="Ej. Nicol" required>
                </div>
                <div class="col-md-6 form-group">
                  <label>Apellidos *</label>
                  <input type="text" class="form-control" placeholder="Ej. Flores García" required>
                </div>
              </div>

              <div class="row">
                <div class="col-md-3 form-group">
                  <label>Teléfono *</label>
                  <input type="text" class="form-control" placeholder="2467654387" required>
                </div>
                <div class="col-md-5 form-group">
                  <label>Correo electrónico</label>
                  <input type="email" class="form-control" placeholder="correo@ejemplo.com">
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 form-group">
                  <label>Dirección</label>
                  <input type="text" class="form-control" placeholder="Calle, número, colonia, ciudad...">
                </div>
              </div>

              <hr class="my-4">

              <h6 class="text-orange-dark mb-3">Información comercial</h6>
              <div class="row">
                <div class="col-md-4 form-group">
                  <label>Tipo de cliente</label>
                  <select class="form-control">
                    <option>Seleccionar tipo</option>
                    <option>Minorista</option>
                    <option>Mayorista</option>
                  </select>
                </div>
                <div class="col-md-4 form-group">
                  <label>Crédito disponible</label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                    <input type="number" class="form-control" placeholder="0.00" step="0.01">
                  </div>
                </div>
                <div class="col-md-4 form-group">
                  <label>Días de crédito</label>
                  <div class="input-group">
                    <input type="number" class="form-control" placeholder="0">
                    <div class="input-group-append"><span class="input-group-text">días</span></div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 form-group">
                  <label>Notas</label>
                  <textarea class="form-control" rows="3" placeholder="Observaciones del cliente..."></textarea>
                </div>
              </div>
            </div>

            <div class="card-footer bg-white border-top-0 pb-4">
              <button type="submit" class="btn btn-orange px-5 mr-2">
                <i class="fas fa-save mr-1"></i> Guardar cliente
              </button>
              <a href="?menu=clientes" class="btn btn-outline-secondary px-5">
                <i class="fas fa-times mr-1"></i> Cancelar
              </a>
            </div>
          </form>
        </div>
      </div>
    </section>
  </div>

</div>

<script src="public/plugins/jquery/jquery.min.js"></script>
<script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="public/dist/js/adminlte.min.js"></script>
</body>
</html>