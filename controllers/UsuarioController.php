<?php
require_once 'models/UsuarioModel.php';

class UsuarioController {
    private $modelo;
    
    public function __construct($conexion) {
        $this->modelo = new UsuarioModel($conexion);
    }

    // ── Lista todos los usuarios ──────────────────────────────
    public function index(): void {
        try {
            $usuarios = $this->modelo->consultar();

            $viewPath = 'views/UsuarioView.php';
            if (!file_exists($viewPath)) {
                throw new Exception("La vista '$viewPath' no se encuentra en el servidor.");
            }
            include $viewPath;

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // ── Registro de nuevo usuario ─────────────────────────────
    public function registrar(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombres  = $_POST['nombres']  ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $email    = $_POST['email']    ?? '';
            $password = $_POST['password'] ?? '';

            if (!empty($nombres) && !empty($apellido) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password)) {
                // Hashear la contraseña antes de guardar
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $this->modelo->insertar($nombres, $apellido, $email, $passwordHash);
            }

            header("Location: index.php?menu=login");
            exit;
        }

        // GET: mostrar formulario de registro
        include 'views/register.php';
    }

    // ── Login ─────────────────────────────────────────────────
    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = $_POST['email']    ?? '';
            $password = $_POST['password'] ?? '';

            $usuario = $this->modelo->consultarPorEmail($email);

            if ($usuario && password_verify($password, $usuario['password'])) {
                // Credenciales correctas — iniciar sesión
                session_start();
                $_SESSION['idUsuarios']  = $usuario['idUsuarios'];
                $_SESSION['nombres']     = $usuario['Nombres'];
                $_SESSION['apellido']    = $usuario['apellido'];
                $_SESSION['email']       = $usuario['email'];

                header("Location: index.php?menu=productos");
                exit;
            } else {
                // Credenciales incorrectas
                $error = "Correo o contraseña incorrectos.";
                include 'views/iniciosec.php';
                return;
            }
        }

        // GET: mostrar formulario de login
        include 'views/iniciosec.php';
    }

    // ── Logout ────────────────────────────────────────────────
    public function logout(): void {
        session_start();
        session_destroy();
        header("Location: index.php?menu=login");
        exit;
    }

    // ── Editar usuario ────────────────────────────────────────
    public function editar(int $id): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = $_POST['email']    ?? '';
            $password = $_POST['password'] ?? '';

            if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password)) {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $this->modelo->actualizar($id, $email, $passwordHash);
            }

            header("Location: index.php");
            exit;
        }

        $usuario = $this->modelo->consultarPorId($id);
        if (!$usuario) {
            header("Location: index.php");
            exit;
        }
        include 'views/editUsuario.php';
    }

    // ── Borrar usuario ────────────────────────────────────────
    public function borrar(int $id): void {
        if ($id) {
            $this->modelo->eliminar($id);
        }
        header("Location: index.php");
        exit;
    }
}
?>