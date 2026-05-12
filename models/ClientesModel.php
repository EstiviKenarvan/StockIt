<?php
class ClientesModel {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function consultar() {
        $sql = "SELECT * FROM clientes ORDER BY idCliente ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function consultarPorId($idCliente) {
        $sql = "SELECT * FROM clientes WHERE idCliente = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idCliente]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertar($tipoCliente, $cliente_nombre, $telefono, $email, $credito, $estado, $notas, $TotalCompras, $fechaPago, $TotalCredito) {
        $sql = "INSERT INTO clientes (tipoCliente, cliente_nombre, telefono, email, credito, estado, notas, TotalCompras, fechaPago, TotalCredito)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            trim($tipoCliente), trim($cliente_nombre),
            trim($telefono),    trim($email),
            $credito,           trim($estado),
            trim($notas),       $TotalCompras,
            $fechaPago,         trim($TotalCredito)
        ]);
    }

    public function actualizar($idCliente, $tipoCliente, $telefono, $email, $credito, $estado, $notas, $TotalCompras, $fechaPago, $TotalCredito) {
        $sql = "UPDATE clientes
                SET tipoCliente = ?, telefono = ?, email = ?, credito = ?,
                    estado = ?, notas = ?, TotalCompras = ?, fechaPago = ?, TotalCredito = ?
                WHERE idCliente = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            trim($tipoCliente), trim($telefono),
            trim($email),       $credito,
            trim($estado),      trim($notas),
            $TotalCompras,      $fechaPago,
            trim($TotalCredito), $idCliente
        ]);
    }

    public function eliminar($idCliente) {
        $sql = "DELETE FROM clientes WHERE idCliente = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idCliente]);
    }
}
?>