<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>StockIt | Olvidé mi Contraseña</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="public/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="public/dist/css/adminlte.min.css">
  <style>
    body.login-page { background-color: #FFF5E5 !important; }
    .login-box a:not(.btn) { color: #dd6e12 !important; }
    .btn-primary { background-color: #dd6e12 !important; border-color: #dd6e12 !important; }
    .btn-primary:hover { background-color: #093960 !important; border-color: #093960 !important; }
    .success-box { background:#e8f5e9; border-left:4px solid #28a745; padding:10px 15px; border-radius:4px; margin-bottom:15px; color:#1b5e20; font-size:.9rem; }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="index.php" class="h1">Stock<b>It</b></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">¿Olvidaste tu contraseña?</p>

      <?php
      $enviado = false;
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['email'])) {
          // Visual only: solo simula el envío
          $enviado = true;
      }
      ?>

      <?php if ($enviado): ?>
        <div class="success-box">
          <i class="fas fa-check-circle mr-2"></i>
          Si ese correo está registrado, recibirás las instrucciones pronto.
          (Funcionalidad de envío pendiente de configurar.)
        </div>
        <p class="text-center"><a href="index.php">Volver al inicio de sesión</a></p>
      <?php else: ?>
        <p class="login-box-msg" style="font-size:.85rem">Ingresa tu correo y te enviaremos instrucciones para recuperar tu contraseña.</p>
        <form action="index.php?menu=olvide" method="POST">
          <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
            <div class="input-group-append">
              <div class="input-group-text"><span class="fas fa-envelope"></span></div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">Solicitar recuperación</button>
            </div>
          </div>
        </form>
        <p class="mt-3 mb-1"><a href="index.php">Volver al inicio de sesión</a></p>
      <?php endif; ?>
    </div>
  </div>
</div>
<script src="public/plugins/jquery/jquery.min.js"></script>
<script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="public/dist/js/adminlte.min.js"></script>
</body>
</html>