<?php
include __DIR__ . "/includes/tarjeta.php";
include __DIR__ . "/includes/tablas.php";
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>StockIt | Clientes</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="public/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="public/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <style>
    .main-header.navbar,
    .main-header.navbar-white,
    .main-header.navbar-light,
    nav.main-header {
      background-color: #E8820C !important;
      border-bottom: none !important
    }

    .main-header .nav-link,
    .main-header .nav-link i,
    .navbar-light .navbar-nav .nav-link {
      color: #fff !important
    }

    .main-sidebar,
    .main-sidebar:before,
    .brand-link,
    .sidebar-dark-primary {
      background-color: #E8820C !important
    }

    .nav-sidebar .nav-link {
      background: transparent !important;
      color: #fff !important
    }

    .nav-sidebar .nav-link:hover {
      background-color: rgba(255, 255, 255, .15) !important
    }

    .nav-sidebar .nav-link.active {
      background-color: rgba(0, 0, 0, .15) !important;
      color: #fff !important
    }

    .nav-sidebar .nav-link p,
    .nav-sidebar .nav-link i {
      color: #fff !important
    }

    .brand-link .brand-text,
    .sidebar .user-panel .info a {
      color: #fff !important
    }

    .form-control-sidebar,
    .btn-sidebar {
      background-color: #fff !important;
      border: 1px solid #ddd !important;
      color: #333 !important
    }

    .user-panel,
    .form-inline {
      border-bottom: 1px solid rgba(255, 255, 255, .2) !important
    }

    .content-wrapper {
      background-color: #FFF5E5 !important
    }

    .text-stockit {
      color: #E8820C !important
    }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <?php $paginaActiva = "clientes";
    include __DIR__ . "/includes/barras.php"; ?>

    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-3 align-items-end">
            <div class="col-sm-6">
              <h1 class="m-0 d-inline" style="font-weight:bold">Gestión de</h1>
              <h1 class="m-0 d-inline text-stockit" style="font-style:italic"> Clientes</h1>
              <p class="text-muted m-0">Catálogo de clientes</p>
            </div>
            <div class="col-sm-6 d-flex justify-content-end">
              <a href="?menu=clientes&submenu=registro" class="btn btn-outline-warning">
                <i class="fas fa-plus mr-1"></i> Agregar Cliente
              </a>
            </div>
          </div>

          <?php if (isset($_GET['exito'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
              <i class="fas fa-check-circle mr-2"></i>
              <?= $_GET['exito'] == 1 ? 'Cliente agregado correctamente.' : 'Cliente actualizado correctamente.' ?>
              <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
          <?php endif; ?>

          <?php if (isset($_GET['eliminado'])): ?>
            <div class="alert alert-info alert-dismissible fade show">
              <i class="fas fa-trash mr-2"></i> Cliente eliminado.
              <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <section class="content">
        <div class="container-fluid">
          <div class="card shadow-sm border-top border-warning">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
              <h5 class="card-title text-stockit font-weight-bold mb-0">
                <i class="fas fa-users mr-1"></i> Clientes Registrados
              </h5>
              <span class="badge badge-warning text-dark"><?= count($clientes) ?> registros</span>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover text-center mb-0">
                  <thead class="thead-light">
                    <tr>
                      <th>#</th>
                      <th>Nombre</th>
                      <th>Tipo</th>
                      <th>Teléfono</th>
                      <th>Email</th>
                      <th>Crédito</th>
                      <th>Estado</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($clientes)): ?>
                      <tr>
                        <td colspan="8" class="text-muted py-4">No hay clientes registrados.</td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($clientes as $c): ?>
                        <tr>
                          <td class="text-muted"><?= $c['idCliente'] ?></td>
                          <td class="font-weight-bold"><?= htmlspecialchars($c['cliente_nombre']) ?></td>
                          <td><?= htmlspecialchars($c['tipoCliente']) ?></td>
                          <td><?= htmlspecialchars($c['telefono'] ?? '—') ?></td>
                          <td><?= htmlspecialchars($c['email'] ?? '—') ?></td>
                          <td>$<?= number_format($c['credito'], 2) ?></td>
                          <td>
                            <?php
                            $badge = match ($c['estado']) {
                              'Activo'   => 'success',
                              'Inactivo' => 'danger',
                              'VIP'      => 'primary',
                              default    => 'secondary'
                            };
                            ?>
                            <span class="badge badge-<?= $badge ?>"><?= htmlspecialchars($c['estado']) ?></span>
                          </td>
                          <td>
                            <a href="?menu=clientes&submenu=editar&id=<?= $c['idCliente'] ?>"
                              class="btn btn-sm btn-outline-warning mr-1" title="Editar">
                              <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-danger btn-borrar"
                              data-id="<?= $c['idCliente'] ?>"
                              data-nombre="<?= htmlspecialchars($c['cliente_nombre']) ?>"
                              title="Eliminar">
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
      </section>
    </div>
  </div>

  <script src="public/plugins/jquery/jquery.min.js"></script>
  <script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="public/dist/js/adminlte.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    $('.btn-borrar').on('click', function() {
      const id = $(this).data('id');
      const nombre = $(this).data('nombre');
      Swal.fire({
        title: '¿Eliminar cliente?',
        text: `Se eliminará a "${nombre}" permanentemente.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#E8820C',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then(r => {
        if (r.isConfirmed)
          window.location = `index.php?menu=clientes&submenu=borrar&id=${id}`;
      });
    });
  </script>
</body>

</html>