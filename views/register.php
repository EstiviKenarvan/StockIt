<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>StockIt | Registrar Usuario</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="public/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="public/dist/css/adminlte.min.css">
  <style>
    body.register-page { background-color: #FFF5E5 !important; }
    .register-logo a, .register-box a:not(.btn) { color: #dd6e12 !important; }
    .btn-primary { background-color: #dd6e12 !important; border-color: #dd6e12 !important; }
    .btn-primary:hover { background-color: #093960 !important; border-color: #093960 !important; }
    .error-box { background:#fff0e0; border-left:4px solid #dd6e12; padding:10px 15px; border-radius:4px; margin-bottom:15px; color:#7a3900; font-size:.9rem; }

    /* Indicadores de fuerza de contraseña */
    .req { font-size: .8rem; margin-bottom: 2px; }
    .req i { width: 14px; }
    .req.ok { color: #28a745; }
    .req.fail { color: #ccc; }
  </style>
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="index.php">Stock<b>It</b></a>
  </div>
  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Registrar nuevo usuario administrador</p>

      <?php
      if (session_status() === PHP_SESSION_NONE) session_start();
      $error = null;

      // Regex: mín 8 chars, mayúscula, minúscula, número, carácter especial
      $regexPassword = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $nombres  = trim($_POST['Nombres']  ?? '');
          $apellido = trim($_POST['apellido'] ?? '');
          $email    = trim($_POST['email']    ?? '');
          $password = $_POST['password'] ?? '';
          $confirm  = $_POST['confirm']  ?? '';

          if (!$nombres || !$apellido || !$email || !$password) {
              $error = "Todos los campos son obligatorios.";
          } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              $error = "El correo electrónico no es válido.";
          } elseif (!preg_match($regexPassword, $password)) {
              $error = "La contraseña no cumple los requisitos de seguridad.";
          } elseif ($password !== $confirm) {
              $error = "Las contraseñas no coinciden.";
          } else {
              require_once __DIR__ . '/../config/database.php';
              $db       = new Database();
              $conexion = $db->getConnection();

              // Verificar email duplicado
              $stmt = $conexion->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
              $stmt->execute([$email]);
              if ($stmt->fetchColumn() > 0) {
                  $error = "Ya existe un usuario con ese correo.";
              } else {
                  $hash = password_hash($password, PASSWORD_DEFAULT);
                  $stmt = $conexion->prepare(
                      "INSERT INTO usuarios (Nombres, apellido, email, password) VALUES (?, ?, ?, ?)"
                  );
                  $stmt->execute([$nombres, $apellido, $email, $hash]);
                  header("Location: index.php?menu=login&registrado=1");
                  exit;
              }
          }
      }
      ?>

      <?php if ($error): ?>
        <div class="error-box"><i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form action="index.php?menu=registro" method="POST" id="form-registro">
        <div class="input-group mb-3">
          <input type="text" name="Nombres" class="form-control" placeholder="Nombre(s)"
                 value="<?= htmlspecialchars($_POST['Nombres'] ?? '') ?>" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-user"></span></div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" name="apellido" class="form-control" placeholder="Apellido(s)"
                 value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-user"></span></div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Correo electrónico"
                 value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
          </div>
        </div>
        <div class="input-group mb-1">
          <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>

        <!-- Indicadores en tiempo real -->
        <div class="mb-3 px-1">
          <div class="req fail" id="req-len"><i class="fas fa-circle"></i> Mínimo 8 caracteres</div>
          <div class="req fail" id="req-upper"><i class="fas fa-circle"></i> Al menos una mayúscula</div>
          <div class="req fail" id="req-lower"><i class="fas fa-circle"></i> Al menos una minúscula</div>
          <div class="req fail" id="req-num"><i class="fas fa-circle"></i> Al menos un número</div>
          <div class="req fail" id="req-special"><i class="fas fa-circle"></i> Al menos un carácter especial (!@#$...)</div>
        </div>

        <div class="input-group mb-3">
          <input type="password" name="confirm" id="confirm" class="form-control" placeholder="Confirmar contraseña" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>
        <div class="req fail mb-3" id="req-match"><i class="fas fa-circle"></i> Las contraseñas coinciden</div>

        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" required>
              <label for="agreeTerms">Acepto los <a href="#">términos</a></label>
            </div>
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Registrar</button>
          </div>
        </div>
      </form>

      <a href="index.php" class="text-center d-block mt-3">Ya tengo una cuenta</a>
    </div>
  </div>
</div>

<script src="public/plugins/jquery/jquery.min.js"></script>
<script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="public/dist/js/adminlte.min.js"></script>
<script>
const pass    = document.getElementById('password');
const confirm = document.getElementById('confirm');

function check(id, ok) {
    const el = document.getElementById(id);
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
</script>
</body>
</html>