<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in (v2)</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="public/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="public/dist/css/adminlte.min.css">
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
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="public/index2.html" class="h1">Stock<b>It</b></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Inicia Sesión</p>

      <form action="../views/GestiondeProductos.php" method="post">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Mantener sesión
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <div class="social-auth-links text-center mt-2 mb-3">
       
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google mr-2"></i> Iniciar sesión con Google
        </a>
      </div>
      <!-- /.social-auth-links -->

      <p class="mb-1">
        <a href="contrasenaolv.php">Olvidé mi contraseña</a>
      </p>
      <p class="mb-0">
        <a href="register.php" class="text-center">Registrar nuevo usuario</a>
      </p>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="public/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="public/dist/js/adminlte.min.js"></script>
</body>
</html>
