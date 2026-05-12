<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>StockIt | <?= isset($cliente) ? 'Editar' : 'Agregar' ?> Cliente</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="public/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="public/dist/css/adminlte.min.css">
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
    .text-orange-dark{color:#E8820C;font-weight:bold}
    .btn-orange{background-color:#E8820C;color:white;border-radius:8px}
    .btn-orange:hover{background-color:#d1750a;color:white}
    .card-custom{border-radius:15px;border-top:5px solid #E8820C}
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <?php $paginaActiva = "clientes"; include __DIR__ . "/includes/barras.php"; ?>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <p class="mb-0 small">
          <a href="?menu=clientes" class="text-warning">Gestión de Clientes</a>
          / <span class="text-muted"><?= isset($cliente) ? 'Editar Cliente' : 'Agregar Cliente' ?></span>
        </p>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="card card-custom shadow-sm">
          <div class="card-header bg-white">
            <h4 class="text-orange-dark mb-0">
              <i class="fas fa-<?= isset($cliente) ? 'user-edit' : 'user-plus' ?> mr-2"></i>
              <?= isset($cliente) ? 'Editar cliente' : 'Agregar nuevo cliente' ?>
            </h4>
            <small class="text-muted">Ingresa la información del cliente</small>
          </div>

          <?php
            // Si es editar usa la URL con id, si es crear usa registro
            $action = isset($cliente)
              ? "?menu=clientes&submenu=editar&id={$cliente['idCliente']}"
              : "?menu=clientes&submenu=registro";
          ?>
          <form action="<?= $action ?>" method="POST">
            <div class="card-body">

              <h6 class="text-orange-dark mb-3">Datos personales</h6>
              <div class="row">
                <div class="col-md-6 form-group">
                  <label>Nombre completo <span class="text-danger">*</span></label>
                  <input type="text" name="cliente_nombre" class="form-control"
                         placeholder="Ej. Nicol Flores García" required
                         value="<?= htmlspecialchars($cliente['cliente_nombre'] ?? '') ?>"
                         <?= isset($cliente) ? '' : '' ?>>
                  <?php if (isset($cliente)): ?>
                    <small class="text-muted">El nombre no se puede editar.</small>
                  <?php endif; ?>
                </div>
                <div class="col-md-3 form-group">
                  <label>Teléfono</label>
                  <input type="text" name="telefono" class="form-control"
                         placeholder="2467654387"
                         value="<?= htmlspecialchars($cliente['telefono'] ?? '') ?>">
                </div>
                <div class="col-md-3 form-group">
                  <label>Estado <span class="text-danger">*</span></label>
                  <select name="estado" class="form-control" required>
                    <?php foreach (['Activo','Inactivo','VIP'] as $est): ?>
                      <option value="<?= $est ?>" <?= (($cliente['estado'] ?? 'Activo') === $est) ? 'selected' : '' ?>>
                        <?= $est ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 form-group">
                  <label>Correo electrónico</label>
                  <input type="email" name="email" class="form-control"
                         placeholder="correo@ejemplo.com"
                         value="<?= htmlspecialchars($cliente['email'] ?? '') ?>">
                </div>
              </div>

              <hr class="my-4">

              <h6 class="text-orange-dark mb-3">Información comercial</h6>
              <div class="row">
                <div class="col-md-4 form-group">
                  <label>Tipo de cliente <span class="text-danger">*</span></label>
                  <select name="tipoCliente" class="form-control" required>
                    <?php foreach (['Minorista','Mayorista','Frecuente'] as $tipo): ?>
                      <option value="<?= $tipo ?>" <?= (($cliente['tipoCliente'] ?? '') === $tipo) ? 'selected' : '' ?>>
                        <?= $tipo ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-4 form-group">
                  <label>Crédito disponible</label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                    <input type="number" name="credito" class="form-control"
                           placeholder="0.00" step="0.01" min="0"
                           value="<?= htmlspecialchars($cliente['credito'] ?? '0') ?>">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 form-group">
                  <label>Notas</label>
                  <textarea name="notas" class="form-control" rows="3"
                            placeholder="Observaciones del cliente..."><?= htmlspecialchars($cliente['notas'] ?? '') ?></textarea>
                </div>
              </div>
            </div>

            <div class="card-footer bg-white border-top-0 pb-4">
              <button type="submit" class="btn btn-orange px-5 mr-2">
                <i class="fas fa-save mr-1"></i> <?= isset($cliente) ? 'Actualizar cliente' : 'Guardar cliente' ?>
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