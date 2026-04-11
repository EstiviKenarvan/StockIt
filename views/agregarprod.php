<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stock It | Agregar Producto</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../public/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../public/dist/css/adminlte.min.css">

 <style>
    /* 1. NAVBAR SUPERIOR (FUERZA BRUTA) */
    /* Aplicamos a todas las variantes posibles de la navbar */
    .main-header.navbar,
    .main-header.navbar-white,
    .main-header.navbar-light,
    nav.main-header {
      background-color: #E8820C !important;
      border-bottom: none !important;
    }

    /* Forzar iconos, botones y links a blanco */
    .main-header .nav-link,
    .main-header .nav-link i,
    .main-header .navbar-nav .nav-item a,
    .navbar-light .navbar-nav .nav-link {
      color: #ffffff !important;
    }

    /* 2. SIDEBAR (LATERAL Y LOGO) */
    .main-sidebar,
    .main-sidebar:before,
    .brand-link,
    .sidebar-dark-primary {
      background-color: #E8820C !important;
    }

    /* 3. TEXTOS E ICONOS DEL MENÚ LATERAL */
    .nav-sidebar .nav-link,
    .brand-link .brand-text,
    .sidebar .user-panel .info a,
    .nav-sidebar .nav-link i {
      color: #ffffff !important;
    }

    /* 4. BARRA DE BÚSQUEDA BLANCA */
    .form-control-sidebar,
    .btn-sidebar {
      background-color: #ffffff !important;
      border: 1px solid #ddd !important;
      color: #333 !important;
    }

    .btn-sidebar i {
      color: #333 !important;
    }

    /* 5. FONDO DE PÁGINA */
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

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="../public/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="index3.html" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link">Contact</a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
          <a class="nav-link" data-widget="navbar-search" href="#" role="button">
            <i class="fas fa-search"></i>
          </a>
          <div class="navbar-search-block">
            <form class="form-inline">
              <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                  <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                  <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </li>

        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-comments"></i>
            <span class="badge badge-danger navbar-badge">3</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a href="#" class="dropdown-item">
              <!-- Message Start -->
              <div class="media">
                <img src="../public/dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    Brad Diesel
                    <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                  </h3>
                  <p class="text-sm">Call me whenever you can...</p>
                  <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                </div>
              </div>
              <!-- Message End -->
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <!-- Message Start -->
              <div class="media">
                <img src="../public/dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    John Pierce
                    <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                  </h3>
                  <p class="text-sm">I got your message bro</p>
                  <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                </div>
              </div>
              <!-- Message End -->
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <!-- Message Start -->
              <div class="media">
                <img src="../public/dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    Nora Silvester
                    <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                  </h3>
                  <p class="text-sm">The subject goes here</p>
                  <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                </div>
              </div>
              <!-- Message End -->
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
          </div>
        </li>
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge">15</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">15 Notifications</span>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-envelope mr-2"></i> 4 new messages
              <span class="float-right text-muted text-sm">3 mins</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-users mr-2"></i> 8 friend requests
              <span class="float-right text-muted text-sm">12 hours</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-file mr-2"></i> 3 new reports
              <span class="float-right text-muted text-sm">2 days</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
            <i class="fas fa-th-large"></i>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="index3.html" class="brand-link">
        <img src="../public/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Stock It</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="../public/dist/img/mininico.jpeg" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block">Nicol Valentina</a>
          </div>
        </div>

        <!-- SidebarSearch Form -->
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

        <!-- Sidebar Menu -->
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
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

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

                        <form action="GestiondeProductos.php" method="POST" enctype="multipart/form-data">
                            <div class="card-body">

                                <h5 class="text-orange font-weight-bold"><i class="fas fa-info-circle mr-2"></i>Información general</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Nombre del producto *</label>
                                        <input type="text" class="form-control" placeholder="Ej. Gansito Marinela" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Categoría *</label>
                                        <select class="form-control">
                                            <option selected disabled>Seleccionar categoría</option>
                                            <option>Pastelitos</option>
                                            <option>Bebidas</option>
                                            <option>Botanas</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label>Precio de compra *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                            <input type="number" id="p_compra" class="form-control" placeholder="0.00" step="0.01" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label>Precio de venta *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                            <input type="number" id="p_venta" class="form-control" placeholder="0.00" step="0.01" required>
                                        </div>
                                        <small id="error-precio" class="text-danger" style="display:none; font-weight:bold;">
                                            ¡Cuidado! El precio de venta es menor al de compra.
                                        </small>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>Stock Total</label>
                                        <input type="number" class="form-control" placeholder="Cantidad en existencia">
                                    </div>
                                </div>

                                <h5 class="text-orange font-weight-bold mt-4"><i class="fas fa-barcode mr-2"></i>Caducidad y código de barras</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label>Fecha de caducidad</label>
                                        <input type="date" class="form-control">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>Código de barras / SKU</label>
                                        <input type="text" class="form-control" placeholder="7501234567890">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>Estado *</label>
                                        <select class="form-control">
                                            <option class="text-success">Activo</option>
                                            <option class="text-danger">Inactivo</option>
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
                                                    <input type="file" class="custom-file-input" id="exampleInputFile">
                                                    <label class="custom-file-label" for="exampleInputFile">Formatos: JPG, PNG. Máx 2MB</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <label>Notas adicionales</label>
                                    <textarea class="form-control" rows="3" placeholder="Describe detalles como sabor, tamaño o lote..."></textarea>
                                </div>
                            </div>

                            <div class="card-footer bg-white border-top text-right">
                                <a href="GestiondeProductos.php" class="btn btn-default mr-2">Cancelar</a>
                                <button type="submit" class="btn btn-orange px-4">Guardar producto</button>
                            </div>
                        </form>

                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="../public/plugins/jquery/jquery.min.js"></script>
    <script src="../public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../public/dist/js/adminlte.min.js"></script>
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
    // Convertimos a número flotante para comparar
    const compra = parseFloat(inputCompra.val()) || 0;
    const venta = parseFloat(inputVenta.val()) || 0;

    if (venta > 0 && venta < compra) {
      // Si la venta es menor a la compra: ERROR
      inputVenta.addClass('is-invalid'); // Pone el borde rojo de Bootstrap
      mensajeError.show();               // Muestra el texto de advertencia
      btnGuardar.prop('disabled', true); // Bloquea el botón
    } else {
      // Si todo está bien
      inputVenta.removeClass('is-invalid');
      inputVenta.addClass('is-valid');
      mensajeError.hide();
      btnGuardar.prop('disabled', false); // Desbloquea el botón
    }
  }

  // Ejecutar validación cada que el usuario escriba en cualquiera de los dos
  inputCompra.on('input', validarPrecios);
  inputVenta.on('input', validarPrecios);
});
</script>
</body>

</html>