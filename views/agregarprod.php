<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Stock It | Agregar Producto</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="public/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="public/dist/css/adminlte.min.css">

  <style>
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

    .btn-sidebar i {
      color: #333 !important;
    }

    .content-wrapper {
      background-color: #FFF5E5 !important;
    }

    .user-panel,
    .form-inline {
      border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
    }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <?php
    $paginaActiva = "productos";
    include __DIR__ . "/includes/barras.php";
    ?>

    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 style="color: #333; font-weight: bold;">Agregar nuevo <span style="color: #dd6e12; font-style: italic;">producto</span></h1>
              <p class="text-muted">Completa la información detallada del catálogo</p>
            </div>
          </div>
        </div>
      </section>

      <section class="content">
        <div class="container-fluid">
          <div class="card card-orange card-outline shadow">

            <form action="?menu=productos&submenu=registro" method="POST" enctype="multipart/form-data">
              <div class="card-body">

                <h5 class="text-orange font-weight-bold"><i class="fas fa-info-circle mr-2"></i>Información general</h5>
                <hr>
                <div class="row">
                  <div class="col-md-6 form-group">
                    <label>Nombre del producto *</label>
                    <input type="text" name="nombreProducto" class="form-control" placeholder="Ej. Gansito Marinela" required>
                  </div>
                  <div class="col-md-6 form-group">
                    <label>Categoría *</label>
                    <select name="idCategoria" class="form-control">
                      <option value="" selected disabled>Seleccionar categoría</option>
                      <?php foreach ($categorias as $c): ?>
                        <option value="<?= $c['idCategoria'] ?>"><?= $c['nombreCategoria'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4 form-group">
                    <label>Precio de compra *</label>
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                      <input type="number" name="precioCompra" id="p_compra" class="form-control" placeholder="0.00" step="0.01" required>
                    </div>
                  </div>
                  <div class="col-md-4 form-group">
                    <label>Precio de venta *</label>
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                      <input type="number" name="precioVenta" id="p_venta" class="form-control" placeholder="0.00" step="0.01" required>
                    </div>
                    <small id="error-precio" class="text-danger" style="display:none; font-weight:bold;">
                      ¡Cuidado! El precio de venta es menor al de compra.
                    </small>
                  </div>
                  <div class="col-md-4 form-group">
                    <label>Stock Total</label>
                    <input type="number" name="stockEnGeneral" class="form-control" placeholder="Cantidad en existencia">
                  </div>
                </div>

                <h5 class="text-orange font-weight-bold mt-4"><i class="fas fa-barcode mr-2"></i>Caducidad y código de barras</h5>
                <hr>
                <div class="row">
                  <div class="col-md-4 form-group">
                    <label>Fecha de caducidad</label>
                    <input type="date" name="fechaCaducidad" class="form-control">
                  </div>
                  <div class="col-md-4 form-group">
                    <label>Código de barras / SKU</label>
                    <input type="text" name="codigoBarras" class="form-control" placeholder="7501234567890">
                  </div>
                  <div class="col-md-4 form-group">
                    <label>Estado *</label>
                    <select name="estado" class="form-control">
                      <option value="Disponible">Activo</option>
                      <option value="Agotado">Inactivo</option>
                    </select>
                  </div>
                </div>

                <h5 class="text-orange font-weight-bold mt-4"><i class="fas fa-image mr-2"></i>Imagen del producto</h5>
                <hr>
                <div class="row align-items-center">
                  <div class="col-md-3 text-center border p-3 bg-light rounded ml-2">
                    <i class="fas fa-camera fa-3x text-muted"></i>
                    <p class="small mb-0 mt-2">Vista previa</p>
                  </div>
                  <div class="col-md-8">
                    <div class="form-group">
                      <label>Seleccionar archivo</label>
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="file" name="image" class="custom-file-input" id="exampleInputFile">
                          <label class="custom-file-label" for="exampleInputFile">Formatos: JPG, PNG. Máx 2MB</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group mt-3">
                  <label>Notas adicionales</label>
                  <textarea name="notas" class="form-control" rows="3" placeholder="Describe detalles como sabor, tamaño o lote..."></textarea>
                </div>
              </div>

              <div class="card-footer bg-white border-top text-right">
                <a href="?menu=productos" class="btn btn-default mr-2">Cancelar</a>
                <button type="submit" class="btn btn-orange px-4">Guardar producto</button>
              </div>
            </form>

          </div>
        </div>
      </section>
    </div>

    <script src="public/plugins/jquery/jquery.min.js"></script>
    <script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="public/dist/js/adminlte.min.js"></script>
    <script>
      $(function() {
        $('.custom-file-input').on('change', function() {
          let fileName = $(this).val().split('\\').pop();
          $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
      });
    </script>
    <script>
      $(document).ready(function() {
        const inputCompra = $('#p_compra');
        const inputVenta = $('#p_venta');
        const mensajeError = $('#error-precio');
        const btnGuardar = $('button[type="submit"]');

        function validarPrecios() {
          const compra = parseFloat(inputCompra.val()) || 0;
          const venta = parseFloat(inputVenta.val()) || 0;
          if (venta > 0 && venta < compra) {
            inputVenta.addClass('is-invalid');
            mensajeError.show();
            btnGuardar.prop('disabled', true);
          } else {
            inputVenta.removeClass('is-invalid');
            inputVenta.addClass('is-valid');
            mensajeError.hide();
            btnGuardar.prop('disabled', false);
          }
        }
        inputCompra.on('input', validarPrecios);
        inputVenta.on('input', validarPrecios);
      });
    </script>
</body>

</html>