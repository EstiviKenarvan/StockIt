<?php
class InventarioModel {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function consultar() {
        $sql = "SELECT m.idMovimiento, p.nombreProducto, pr.nombreProveedor,
                       m.cantidad, m.unidad, m.costo, m.factura,
                       m.fechaEntrada, m.fechaCaducidad, m.Lote, m.Observaciones
                FROM movimientos_inventario m
                LEFT JOIN productos p   ON m.idProducto  = p.idProducto
                LEFT JOIN proveedores pr ON m.idProveedor = pr.idProveedor
                ORDER BY m.fechaEntrada DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function consultarPorId($idMovimiento) {
        $sql = "SELECT * FROM movimientos_inventario WHERE idMovimiento = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idMovimiento]);
        return $stmt->fetch();
    }

    public function insertar($idProducto, $idProveedor, $cantidad, $unidad, $costo, $factura, $fechaEntrada, $fechaCaducidad, $Lote, $Observaciones) {
        $sql = "INSERT INTO movimientos_inventario
                    (idProducto, idProveedor, cantidad, unidad, costo, factura, fechaEntrada, fechaCaducidad, Lote, Observaciones)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $idProducto, $idProveedor,
            trim($cantidad), trim($unidad),
            trim($costo), trim($factura),
            $fechaEntrada, $fechaCaducidad,
            trim($Lote), trim($Observaciones)
        ]);
    }

    public function eliminar($idMovimiento) {
        $sql = "DELETE FROM movimientos_inventario WHERE idMovimiento = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idMovimiento]);
    }
}
?>