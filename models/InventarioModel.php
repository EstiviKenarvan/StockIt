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
                LEFT JOIN productos p    ON m.idProducto  = p.idProducto
                LEFT JOIN proveedores pr ON m.idProveedor = pr.idProveedor
                ORDER BY m.fechaEntrada DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function consultarPorId($idMovimiento) {
        $sql = "SELECT * FROM movimientos_inventario WHERE idMovimiento = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idMovimiento]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertar($idProducto, $idProveedor, $cantidad, $unidad, $costo, $factura, $fechaEntrada, $fechaCaducidad, $Lote, $Observaciones) {
        $sql = "INSERT INTO movimientos_inventario
                    (idProducto, idProveedor, cantidad, unidad, costo, factura, fechaEntrada, fechaCaducidad, Lote, Observaciones)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $ok = $stmt->execute([
            $idProducto, $idProveedor,
            trim($cantidad), trim($unidad),
            trim($costo), trim($factura),
            $fechaEntrada, $fechaCaducidad ?: null,
            trim($Lote), trim($Observaciones)
        ]);

        // Actualizar stock del producto
        if ($ok && $idProducto) {
            $upd = $this->db->prepare(
                "UPDATE productos SET stockEnGeneral = stockEnGeneral + ? WHERE idProducto = ?"
            );
            $upd->execute([$cantidad, $idProducto]);
        }
        return $ok;
    }

    public function eliminar($idMovimiento) {
        $sql = "DELETE FROM movimientos_inventario WHERE idMovimiento = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idMovimiento]);
    }

    // ── Tarjetas ──────────────────────────────────────────────
    public function totalEntradasHoy() {
        $sql = "SELECT COALESCE(SUM(cantidad), 0) FROM movimientos_inventario
                WHERE DATE(fechaEntrada) = CURDATE()";
        return $this->db->query($sql)->fetchColumn();
    }

    public function totalMovimientos() {
        return $this->db->query("SELECT COUNT(*) FROM movimientos_inventario")->fetchColumn();
    }

    public function stockTotal() {
        return $this->db->query("SELECT COALESCE(SUM(stockEnGeneral),0) FROM productos")->fetchColumn();
    }

    public function productosStockBajo() {
        return $this->db->query(
            "SELECT COUNT(*) FROM productos WHERE stockEnGeneral <= 5 AND stockEnGeneral > 0"
        )->fetchColumn();
    }

    // ── Selectores para el modal ──────────────────────────────
    public function consultarProductos() {
        return $this->db->query(
            "SELECT idProducto, nombreProducto, codigoBarras, stockEnGeneral
             FROM productos ORDER BY nombreProducto ASC"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function consultarProveedores() {
        return $this->db->query(
            "SELECT idProveedor, nombreProveedor FROM proveedores ORDER BY nombreProveedor ASC"
        )->fetchAll(PDO::FETCH_ASSOC);
    }
    public function actualizarPrecioCompra($idProducto, $precioCompra) {
    $stmt = $this->db->prepare(
        "UPDATE productos SET precioCompra = ? WHERE idProducto = ?"
    );
    return $stmt->execute([$precioCompra, $idProducto]);
}

public function obtenerPrecioCompra($idProducto) {
    $stmt = $this->db->prepare(
        "SELECT precioCompra FROM productos WHERE idProducto = ?"
    );
    $stmt->execute([$idProducto]);
    return $stmt->fetchColumn();
}
public function contarSinStock() {
    return $this->db->query(
        "SELECT COUNT(*) FROM productos WHERE stockEnGeneral <= 0"
    )->fetchColumn();
}
}
?>