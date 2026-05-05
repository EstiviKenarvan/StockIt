<?php
class ProveedoresModel {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function consultar() {
        $sql = "SELECT idProveedor, nombreProveedor, telefono, email,
                       frecuenciaPedido, diasEntregas, diaVisita, estado
                FROM proveedores
                ORDER BY idProveedor ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function consultarPorId($idProveedor) {
        $sql = "SELECT * FROM proveedores WHERE idProveedor = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idProveedor]);
        return $stmt->fetch();
    }

    public function insertar($nombreProveedor, $telefono, $email, $frecuenciaPedido, $diasEntregas, $diaVisita, $estado) {
        $sql = "INSERT INTO proveedores (nombreProveedor, telefono, email, frecuenciaPedido, diasEntregas, diaVisita, estado)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            trim($nombreProveedor), trim($telefono), trim($email),
            trim($frecuenciaPedido), trim($diasEntregas),
            trim($diaVisita), trim($estado)
        ]);
    }

    public function actualizar($idProveedor, $nombreProveedor, $telefono, $email, $frecuenciaPedido, $diasEntregas, $diaVisita, $estado) {
        $sql = "UPDATE proveedores SET nombreProveedor=?, telefono=?, email=?,
                frecuenciaPedido=?, diasEntregas=?, diaVisita=?, estado=?
                WHERE idProveedor=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            trim($nombreProveedor), trim($telefono), trim($email),
            trim($frecuenciaPedido), trim($diasEntregas),
            trim($diaVisita), trim($estado),
            $idProveedor
        ]);
    }

    public function eliminar($idProveedor) {
        $sql = "DELETE FROM proveedores WHERE idProveedor = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idProveedor]);
    }
}
?>