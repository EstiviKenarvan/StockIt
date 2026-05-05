<?php
class UsuarioModel {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function consultar() {
        $sql = "SELECT * FROM usuarios ORDER BY idUsuarios ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function insertar($nombres, $apellido, $email, $password) {
        $sql = "INSERT INTO usuarios (Nombres, apellido, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([trim($nombres), trim($apellido), trim($email), trim($password)]);
    }

    public function eliminar($idUsuarios) {
        $sql = "DELETE FROM usuarios WHERE idUsuarios = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idUsuarios]);
    }

    public function consultarPorId($idUsuarios) {
        $sql = "SELECT * FROM usuarios WHERE idUsuarios = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idUsuarios]);
        return $stmt->fetch();
    }

    // Necesario para el login
    public function consultarPorEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([trim($email)]);
        return $stmt->fetch();
    }

    public function actualizar($idUsuarios, $email, $password) {
        $sql = "UPDATE usuarios SET email = ?, password = ? WHERE idUsuarios = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([trim($email), trim($password), $idUsuarios]);
    }
}
?>