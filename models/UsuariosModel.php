<?php
class UsuariosModel {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function consultar() {
        $sql = "SELECT idUsuarios, Nombres, apellido, email FROM usuarios ORDER BY idUsuarios ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function consultarPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE idUsuarios = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertar($nombres, $apellido, $email, $password) {
        $stmt = $this->db->prepare(
            "INSERT INTO usuarios (Nombres, apellido, email, password) VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([
            trim($nombres), trim($apellido), trim($email), password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    public function actualizar($id, $nombres, $apellido, $email) {
        $stmt = $this->db->prepare(
            "UPDATE usuarios SET Nombres = ?, apellido = ?, email = ? WHERE idUsuarios = ?"
        );
        return $stmt->execute([trim($nombres), trim($apellido), trim($email), $id]);
    }

    public function actualizarPassword($id, $password) {
        $stmt = $this->db->prepare(
            "UPDATE usuarios SET password = ? WHERE idUsuarios = ?"
        );
        return $stmt->execute([password_hash($password, PASSWORD_DEFAULT), $id]);
    }

    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE idUsuarios = ?");
        return $stmt->execute([$id]);
    }

    public function emailExiste($email, $excludeId = null) {
        $sql  = "SELECT COUNT(*) FROM usuarios WHERE email = ?";
        $params = [$email];
        if ($excludeId) {
            $sql .= " AND idUsuarios != ?";
            $params[] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
}
?>