<?php
require_once __DIR__ . '/../models/UsuariosModel.php';

class UsuarioController
{
    private $modelo;

    public function __construct($conexion)
    {
        $this->modelo = new UsuariosModel($conexion);
    }

    // ── Lista todos los usuarios ──────────────────────────
    public function index(): void
    {
        $usuarios = $this->modelo->consultar();
        include 'views/usuarios.php';
    }

    // ── Crear usuario ─────────────────────────────────────
    public function crear(): void
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombres  = $_POST['Nombres']   ?? '';
            $apellido = $_POST['apellido']  ?? '';
            $email    = $_POST['email']     ?? '';
            $password = $_POST['password']  ?? '';
            $confirm  = $_POST['confirm']   ?? '';

            if (!$nombres || !$apellido || !$email || !$password) {
                $error = "Todos los campos son obligatorios.";
            } elseif ($password !== $confirm) {
                $error = "Las contraseñas no coinciden.";
            } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
                $error = "La contraseña debe tener mínimo 8 caracteres, mayúscula, minúscula, número y carácter especial.";
            } elseif ($this->modelo->emailExiste($email)) {
                $error = "Ya existe un usuario con ese correo.";
            } else {
                $this->modelo->insertar($nombres, $apellido, $email, $password);
                header("Location: index.php?menu=usuarios&exito=1");
                exit;
            }
        }

        include 'views/usuarios_form.php';
    }

    // ── Editar usuario ────────────────────────────────────
    public function editar(int $id): void
    {
        $usuario = $this->modelo->consultarPorId($id);
        if (!$usuario) {
            header("Location: index.php?menu=usuarios");
            exit;
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombres  = $_POST['Nombres']  ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $email    = $_POST['email']    ?? '';
            $password = $_POST['password'] ?? '';
            $confirm  = $_POST['confirm']  ?? '';

            if (!$nombres || !$apellido || !$email) {
                $error = "Nombre, apellido y correo son obligatorios.";
            } elseif ($this->modelo->emailExiste($email, $id)) {
                $error = "Ya existe otro usuario con ese correo.";
            } else {
                $this->modelo->actualizar($id, $nombres, $apellido, $email);

                // Solo cambia password si llenó el campo
                if ($password) {
                    if ($password !== $confirm) {
                        $error = "Las contraseñas no coinciden.";
                        include 'views/usuarios_form.php';
                        return;
                    } elseif (strlen($password) < 6) {
                        $error = "La contraseña debe tener al menos 6 caracteres.";
                        include 'views/usuarios_form.php';
                        return;
                    }
                    $this->modelo->actualizarPassword($id, $password);
                }

                header("Location: index.php?menu=usuarios&exito=2");
                exit;
            }
        }

        include 'views/usuarios_form.php';
    }

    // ── Borrar usuario ────────────────────────────────────
    public function borrar(int $id): void
    {
        if ($id) $this->modelo->eliminar($id);
        header("Location: index.php?menu=usuarios&eliminado=1");
        exit;
    }
}
