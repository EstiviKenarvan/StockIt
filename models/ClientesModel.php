<?php
class ClientesModel {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function consultar() {
        $sql = "SELECT * FROM clientes ORDER BY idCliente ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function insertar($tipoCliente, $cliente_nombre, $telefono, $email, $credito, $estado, $notas, $TotalCompras, $fechaPago, $TotalCredito) {
        $sql = "INSERT INTO clientes (tipoCliente, cliente_nombre, telefono, email, credito, estado, notas, TotalCompras, fechaPago, TotalCredito)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            trim($tipoCliente), trim($cliente_nombre),
            trim($telefono),    trim($email),
            trim($credito),     trim($estado),
            trim($notas),       trim($TotalCompras),
            trim($fechaPago),   trim($TotalCredito)
        ]);
    }

    public function eliminar($idCliente) {
        $sql = "DELETE FROM clientes WHERE idCliente = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idCliente]);
    }

    public function consultarPorId($idCliente) {
        $sql = "SELECT * FROM clientes WHERE idCliente = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idCliente]);
        return $stmt->fetch();
    }

    public function actualizar($idCliente, $tipoCliente, $telefono, $email, $credito, $estado, $notas, $TotalCompras, $fechaPago, $TotalCredito) {
        $sql = "UPDATE clientes
                SET tipoCliente = ?, telefono = ?, email = ?, credito = ?,
                    estado = ?, notas = ?, TotalCompras = ?, fechaPago = ?, TotalCredito = ?
                WHERE idCliente = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            trim($tipoCliente), trim($telefono),
            trim($email),       trim($credito),
            trim($estado),      trim($notas),
            trim($TotalCompras), trim($fechaPago),
            trim($TotalCredito), $idCliente
        ]);
    }
}
?>