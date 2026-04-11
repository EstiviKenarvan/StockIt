<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Recover Password (v2)</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../public/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../public/dist/css/adminlte.min.css">
  <style>
      body.login-page {
    background-color: #FFF5E5 !important;
  }

  /* 1. Cambiar color del logo "Admin" */
  .login-logo b {
    color: #dd6e12 !important;
  }

  /* 2. Cambiar color del logo "LTE" */
  .login-logo a {
    color: #093960 !important;
  }

  /* 3. Cambiar color de los enlaces de abajo */
  .login-box a:not(.btn) {
    color: #dd6e12 !important;
  }

  /* 4. Cambiar el botón azul por el naranja StockIt */
  .btn-primary {
    background-color: #dd6e12 !important;
    border-color: #dd6e12 !important;
  }

  /* 5. Efecto cuando pasas el mouse por el botón */
  .btn-primary:hover {
    background-color: #093960 !important;
    border-color: #093960 !important;
  }
</style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="../public/index2.html" class="h1">Stock<b>It</b></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Introduce tu nueva contraseña</p>
      <form action="../views/iniciosec.php" method="post">
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Nueva contraseña">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Confirmar contraseña">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Cambiar contraseña</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mt-3 mb-1">
        <a href="iniciosec.php">Iniciar sesión</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
</body>
</html>
