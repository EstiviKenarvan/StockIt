<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>StockIt | Iniciar Sesión</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="public/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="public/dist/css/adminlte.min.css">
  <style>
    body.login-page { background-color: #FFF5E5 !important; }
    .login-box a:not(.btn) { color: #dd6e12 !important; }
    .btn-primary { background-color: #dd6e12 !important; border-color: #dd6e12 !important; }
    .btn-primary:hover { background-color: #093960 !important; border-color: #093960 !important; }
    .error-box { background:#fff0e0; border-left:4px solid #dd6e12; padding:10px 15px; border-radius:4px; margin-bottom:15px; color:#7a3900; font-size:.9rem; }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="index.php" class="h1">Stock<b>It</b></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Inicia Sesión</p>

      <?php
      if (session_status() === PHP_SESSION_NONE) session_start();
      $error = null;

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $email    = trim($_POST['email']    ?? '');
          $password = $_POST['password'] ?? '';

          if (!$email || !$password) {
              $error = "Completa todos los campos.";
          } else {
              require_once __DIR__ . '/../config/database.php';
              $db       = new Database();
              $conexion = $db->getConnection();

              $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE email = ? LIMIT 1");
              $stmt->execute([$email]);
              $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

              if (!$usuario || !password_verify($password, $usuario['password'])) {
                  $error = "Correo o contraseña incorrectos.";
              } else {
                  $_SESSION['usuario_id']     = $usuario['idUsuarios'];
                  $_SESSION['usuario_nombre'] = $usuario['Nombres'] . ' ' . $usuario['apellido'];
                  $_SESSION['usuario_email']  = $usuario['email'];
                  header("Location: index.php?menu=productos");
                  exit;
              }
          }
      }
      ?>

      <?php if ($error): ?>
        <div class="error-box"><i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form action="index.php?menu=login" method="POST">
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Correo electrónico"
                 value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">Mantener sesión</label>
            </div>
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
          </div>
        </div>
      </form>

      <p class="mb-1 mt-3"><a href="index.php?menu=olvide">Olvidé mi contraseña</a></p>
      <p class="mb-0"><a href="index.php?menu=registro">Registrar nuevo usuario</a></p>
    </div>
  </div>
</div>
<script src="public/plugins/jquery/jquery.min.js"></script>
<script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="public/dist/js/adminlte.min.js"></script>
</body>
</html>