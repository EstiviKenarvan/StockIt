<?php
include __DIR__ . "/includes/tarjeta.php";
include __DIR__ . "/includes/tablas.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockIt | Usuarios</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="public/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="public/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
        .text-stockit{color:#E8820C!important}

        /* Modal header */
        .modal-header-stockit {
            background-color: #E8820C;
            color: white;
            border-radius: 5px 5px 0 0;
        }
        .modal-header-stockit .close { color: white; opacity: 1; }
        .modal-header-stockit .modal-title { font-weight: bold; }

        /* Indicadores de contraseña */
        .req { font-size: .82rem; margin-bottom: 3px; }
        .req i { width: 14px; }
        .req.ok   { color: #28a745; }
        .req.fail { color: #bbb; }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <?php $paginaActiva = "usuarios"; include __DIR__ . "/includes/barras.php"; ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-3 align-items-end">
                    <div class="col-sm-6">
                        <h1 class="m-0 d-inline" style="font-weight:bold">Gestión de</h1>
                        <h1 class="m-0 d-inline text-stockit" style="font-style:italic"> Usuarios</h1>
                        <p class="text-muted m-0">Catálogo de usuarios</p>
                    </div>
                    <div class="col-sm-6 d-flex justify-content-end">
                        <button class="btn btn-outline-warning" data-toggle="modal" data-target="#modalAgregar">
                            <i class="fas fa-plus mr-1"></i> Agregar Usuario
                        </button>
                    </div>
                </div>

                <?php if (isset($_GET['exito'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?= $_GET['exito'] == 1 ? 'Usuario agregado correctamente.' : 'Usuario actualizado correctamente.' ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['eliminado'])): ?>
                    <div class="alert alert-info alert-dismissible fade show">
                        <i class="fas fa-trash mr-2"></i> Usuario eliminado.
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
                            <i class="fas fa-users mr-1"></i> Usuarios Registrados
                        </h5>
                        <span class="badge badge-warning text-dark"><?= count($usuarios) ?> registros</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover text-center mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($usuarios)): ?>
                                        <tr>
                                            <td colspan="4" class="text-muted py-4">No hay usuarios registrados.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($usuarios as $u): ?>
                                            <tr>
                                                <td class="text-muted align-middle"><?= $u['idUsuarios'] ?></td>
                                                <td class="font-weight-bold align-middle">
                                                    <?= htmlspecialchars(($u['Nombres'] ?? '') . ' ' . ($u['apellido'] ?? '')) ?>
                                                </td>
                                                <td class="align-middle"><?= htmlspecialchars($u['email'] ?? '') ?></td>
                                                <td class="align-middle">
                                                    <button class="btn btn-sm btn-outline-warning mr-1 btn-editar"
                                                            data-id="<?= $u['idUsuarios'] ?>"
                                                            data-nombres="<?= htmlspecialchars($u['Nombres'] ?? '') ?>"
                                                            data-apellido="<?= htmlspecialchars($u['apellido'] ?? '') ?>"
                                                            data-email="<?= htmlspecialchars($u['email'] ?? '') ?>"
                                                            title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger btn-borrar"
                                                            data-id="<?= $u['idUsuarios'] ?>"
                                                            data-nombre="<?= htmlspecialchars(($u['Nombres'] ?? '') . ' ' . ($u['apellido'] ?? '')) ?>"
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

    <!-- ══════════════════════════════════════════
         MODAL: AGREGAR USUARIO
    ══════════════════════════════════════════ -->
    <div class="modal fade" id="modalAgregar" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-stockit">
                    <h5 class="modal-title"><i class="fas fa-user-plus mr-2"></i>Agregar nuevo usuario</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="?menu=usuarios&submenu=registro" method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Nombre(s) <span class="text-danger">*</span></label>
                                <input type="text" name="Nombres" class="form-control" placeholder="Ej. Nicol" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Apellido <span class="text-danger">*</span></label>
                                <input type="text" name="apellido" class="form-control" placeholder="Ej. Flores García" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Correo electrónico <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="correo@ejemplo.com" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Contraseña <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="pass-agregar" class="form-control" placeholder="Mínimo 8 caracteres" required>
                                <!-- Indicadores en tiempo real -->
                                <div class="mt-2">
                                    <div class="req fail" id="a-len"><i class="fas fa-circle"></i> Mínimo 8 caracteres</div>
                                    <div class="req fail" id="a-upper"><i class="fas fa-circle"></i> Al menos una mayúscula</div>
                                    <div class="req fail" id="a-lower"><i class="fas fa-circle"></i> Al menos una minúscula</div>
                                    <div class="req fail" id="a-num"><i class="fas fa-circle"></i> Al menos un número</div>
                                    <div class="req fail" id="a-special"><i class="fas fa-circle"></i> Al menos un carácter especial (!@#$...)</div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Confirmar contraseña <span class="text-danger">*</span></label>
                                <input type="password" name="confirm" id="confirm-agregar" class="form-control" placeholder="Repetir contraseña" required>
                                <div class="mt-2">
                                    <div class="req fail" id="a-match"><i class="fas fa-circle"></i> Las contraseñas coinciden</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-warning text-white">
                            <i class="fas fa-save mr-1"></i> Guardar usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════
         MODAL: EDITAR USUARIO
    ══════════════════════════════════════════ -->
    <div class="modal fade" id="modalEditar" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-stockit">
                    <h5 class="modal-title"><i class="fas fa-user-edit mr-2"></i>Editar usuario</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="formEditar" action="" method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Nombre(s) <span class="text-danger">*</span></label>
                                <input type="text" name="Nombres" id="edit-nombres" class="form-control" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Apellido <span class="text-danger">*</span></label>
                                <input type="text" name="apellido" id="edit-apellido" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Correo electrónico <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="edit-email" class="form-control" required>
                            </div>
                        </div>
                        <hr>
                        <p class="text-muted small mb-2"><i class="fas fa-info-circle mr-1"></i> Deja los campos de contraseña vacíos si no deseas cambiarla.</p>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Nueva contraseña</label>
                                <input type="password" name="password" id="pass-editar" class="form-control" placeholder="Dejar vacío para no cambiar">
                                <div class="mt-2">
                                    <div class="req fail" id="e-len"><i class="fas fa-circle"></i> Mínimo 8 caracteres</div>
                                    <div class="req fail" id="e-upper"><i class="fas fa-circle"></i> Al menos una mayúscula</div>
                                    <div class="req fail" id="e-lower"><i class="fas fa-circle"></i> Al menos una minúscula</div>
                                    <div class="req fail" id="e-num"><i class="fas fa-circle"></i> Al menos un número</div>
                                    <div class="req fail" id="e-special"><i class="fas fa-circle"></i> Al menos un carácter especial (!@#$...)</div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Confirmar nueva contraseña</label>
                                <input type="password" name="confirm" id="confirm-editar" class="form-control" placeholder="Repetir contraseña">
                                <div class="mt-2">
                                    <div class="req fail" id="e-match"><i class="fas fa-circle"></i> Las contraseñas coinciden</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-warning text-white">
                            <i class="fas fa-save mr-1"></i> Actualizar usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div><!-- /.wrapper -->

<script src="public/plugins/jquery/jquery.min.js"></script>
<script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="public/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ── Helper: actualiza el ícono y color de un indicador ───────
function checkReq(id, ok) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.toggle('ok',   ok);
    el.classList.toggle('fail', !ok);
    el.querySelector('i').className = ok ? 'fas fa-check-circle' : 'fas fa-circle';
}

// ── Validación en tiempo real — modal AGREGAR ────────────────
const passAgregar    = document.getElementById('pass-agregar');
const confirmAgregar = document.getElementById('confirm-agregar');

passAgregar.addEventListener('input', () => {
    const v = passAgregar.value;
    checkReq('a-len',     v.length >= 8);
    checkReq('a-upper',   /[A-Z]/.test(v));
    checkReq('a-lower',   /[a-z]/.test(v));
    checkReq('a-num',     /\d/.test(v));
    checkReq('a-special', /[\W_]/.test(v));
    checkReq('a-match',   v.length > 0 && v === confirmAgregar.value);
});
confirmAgregar.addEventListener('input', () => {
    checkReq('a-match', passAgregar.value.length > 0 && passAgregar.value === confirmAgregar.value);
});

// ── Validación en tiempo real — modal EDITAR ─────────────────
const passEditar    = document.getElementById('pass-editar');
const confirmEditar = document.getElementById('confirm-editar');

passEditar.addEventListener('input', () => {
    const v = passEditar.value;
    // Solo muestra indicadores si está escribiendo algo
    if (v.length === 0) {
        ['e-len','e-upper','e-lower','e-num','e-special','e-match'].forEach(id => {
            checkReq(id, false);
        });
        return;
    }
    checkReq('e-len',     v.length >= 8);
    checkReq('e-upper',   /[A-Z]/.test(v));
    checkReq('e-lower',   /[a-z]/.test(v));
    checkReq('e-num',     /\d/.test(v));
    checkReq('e-special', /[\W_]/.test(v));
    checkReq('e-match',   v === confirmEditar.value);
});
confirmEditar.addEventListener('input', () => {
    if (passEditar.value.length > 0)
        checkReq('e-match', passEditar.value === confirmEditar.value);
});

// ── Abrir modal EDITAR con datos precargados ─────────────────
$('.btn-editar').on('click', function() {
    const id       = $(this).data('id');
    const nombres  = $(this).data('nombres');
    const apellido = $(this).data('apellido');
    const email    = $(this).data('email');

    $('#edit-nombres').val(nombres);
    $('#edit-apellido').val(apellido);
    $('#edit-email').val(email);
    $('#pass-editar').val('');
    $('#confirm-editar').val('');

    // Resetear indicadores
    ['e-len','e-upper','e-lower','e-num','e-special','e-match'].forEach(id => checkReq(id, false));

    $('#formEditar').attr('action', `?menu=usuarios&submenu=editar&id=${id}`);
    $('#modalEditar').modal('show');
});

// ── Confirmar ELIMINAR ───────────────────────────────────────
$('.btn-borrar').on('click', function() {
    const id     = $(this).data('id');
    const nombre = $(this).data('nombre');

    Swal.fire({
        title: '¿Eliminar usuario?',
        text: `Se eliminará a "${nombre}" permanentemente.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#E8820C',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(r => {
        if (r.isConfirmed)
            window.location = `index.php?menu=usuarios&submenu=borrar&id=${id}`;
    });
});
</script>
</body>
</html>