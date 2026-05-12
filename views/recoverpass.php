<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>StockIt | Nueva Contraseña</title>
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
    .success-box { background:#e8f5e9; border-left:4px solid #28a745; padding:10px 15px; border-radius:4px; margin-bottom:15px; color:#1b5e20; font-size:.9rem; }
    .req { font-size:.8rem; margin-bottom:2px; }
    .req i { width:14px; }
    .req.ok { color:#28a745; }
    .req.fail { color:#ccc; }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="index.php" class="h1">Stock<b>It</b></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Introduce tu nueva contraseña</p>

      <?php
      // Visual only: en producción aquí validarías un token enviado por email
      $error   = null;
      $success = false;
      $regexPassword = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $password = $_POST['password'] ?? '';
          $confirm  = $_POST['confirm']  ?? '';

          if (!$password || !$confirm) {
              $error = "Completa ambos campos.";
          } elseif (!preg_match($regexPassword, $password)) {
              $error = "La contraseña no cumple los requisitos de seguridad.";
          } elseif ($password !== $confirm) {
              $error = "Las contraseñas no coinciden.";
          } else {
              // Visual: simula cambio exitoso
              $success = true;
          }
      }
      ?>

      <?php if ($error): ?>
        <div class="error-box"><i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="success-box"><i class="fas fa-check-circle mr-2"></i> Contraseña actualizada correctamente.</div>
        <p class="text-center"><a href="index.php">Iniciar sesión</a></p>
      <?php else: ?>
        <form action="index.php?menu=recuperar" method="POST">
          <div class="input-group mb-1">
            <input type="password" name="password" id="password" class="form-control" placeholder="Nueva contraseña" required>
            <div class="input-group-append">
              <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
          </div>

          <div class="mb-3 px-1">
            <div class="req fail" id="req-len"><i class="fas fa-circle"></i> Mínimo 8 caracteres</div>
            <div class="req fail" id="req-upper"><i class="fas fa-circle"></i> Al menos una mayúscula</div>
            <div class="req fail" id="req-lower"><i class="fas fa-circle"></i> Al menos una minúscula</div>
            <div class="req fail" id="req-num"><i class="fas fa-circle"></i> Al menos un número</div>
            <div class="req fail" id="req-special"><i class="fas fa-circle"></i> Al menos un carácter especial (!@#$...)</div>
          </div>

          <div class="input-group mb-1">
            <input type="password" name="confirm" id="confirm" class="form-control" placeholder="Confirmar contraseña" required>
            <div class="input-group-append">
              <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
          </div>
          <div class="req fail mb-3" id="req-match"><i class="fas fa-circle"></i> Las contraseñas coinciden</div>

          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">Cambiar contraseña</button>
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
<script>
const pass    = document.getElementById('password');
const confirm = document.getElementById('confirm');
if (pass) {
    function check(id, ok) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.toggle('ok', ok);
        el.classList.toggle('fail', !ok);
        el.querySelector('i').className = ok ? 'fas fa-check-circle' : 'fas fa-circle';
    }
    pass.addEventListener('input', () => {
        const v = pass.value;
        check('req-len',     v.length >= 8);
        check('req-upper',   /[A-Z]/.test(v));
        check('req-lower',   /[a-z]/.test(v));
        check('req-num',     /\d/.test(v));
        check('req-special', /[\W_]/.test(v));
        check('req-match',   v && v === confirm.value);
    });
    confirm.addEventListener('input', () => {
        check('req-match', pass.value && pass.value === confirm.value);
    });
}
</script>
</body>
</html>