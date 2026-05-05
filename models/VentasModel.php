<?php
class VentasModel {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    /* ── Ventas ──────────────────────────────────────────── */

    public function consultar() {
        $sql = "SELECT v.idVenta, v.fechaHora, v.totalVenta,
                       c.cliente_nombre, vp.efectivo, vp.credito, vp.transferencia
                FROM ventas v
                LEFT JOIN clientes c        ON v.idCliente    = c.idCliente
                LEFT JOIN ventaporpedido vp ON v.idMetodoPago = vp.idMetodoPago
                ORDER BY v.fechaHora DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function consultarPorId($idVenta) {
        $stmt = $this->db->prepare("SELECT * FROM ventas WHERE idVenta = ?");
        $stmt->execute([$idVenta]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function consultarDetalle($idVenta) {
        $sql = "SELECT d.idDetalle, p.nombreProducto, d.totalVentaP
                FROM detalle_ventas d
                LEFT JOIN productos p ON d.idProducto = p.idProducto
                WHERE d.idVenta = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idVenta]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertar($idCliente, $idMetodoPago, $idModo, $totalVenta) {
        $sql = "INSERT INTO ventas (idCliente, idMetodoPago, idModo, fechaHora, totalVenta)
                VALUES (?, ?, ?, NOW(), ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idCliente, $idMetodoPago, $idModo, $totalVenta]);
        return $this->db->lastInsertId();
    }

    /* Guarda cada producto del carrito en detalle_ventas */
    public function insertarDetalle($idVenta, $idProducto, $totalVentaP) {
        $sql = "INSERT INTO detalle_ventas (idVenta, idProducto, totalVentaP, productoBajarotacion, image)
                VALUES (?, ?, ?, 0, '')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idVenta, $idProducto, $totalVentaP]);
    }

    /* Descuenta stock tras una venta */
    public function descontarStock($idProducto, $cantidad) {
        $sql = "UPDATE productos SET stockEnGeneral = stockEnGeneral - ? WHERE idProducto = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$cantidad, $idProducto]);
    }

    public function eliminar($idVenta) {
        $stmt = $this->db->prepare("DELETE FROM ventas WHERE idVenta = ?");
        return $stmt->execute([$idVenta]);
    }

    /* ── Productos (búsqueda para carrito) ───────────────── */
public function insertarMetodoPago($total, $metodo) {
    // metodo: 1=efectivo, 2=credito, 3=transferencia
    $efectivo      = $metodo == 1 ? $total : 0;
    $credito       = $metodo == 2 ? $total : 0;
    $transferencia = $metodo == 3 ? $total : 0;

    $sql = "INSERT INTO ventaporpedido (efectivo, credito, transferencia) VALUES (?, ?, ?)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$efectivo, $credito, $transferencia]);
    return $this->db->lastInsertId();
}
public function insertarModoVenta($idCliente, $idProducto, $cantidad) {
    $sql = "INSERT INTO modo_ventas (ventaDiracta, ventaClienteFrecuente, VentaporPedido, VnetaconDescuento, idCliente, idProducto, cantidad)
            VALUES ('Venta Directa', '', '', '', ?, ?, ?)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$idCliente ?? 1, $idProducto, $cantidad]);
    return $this->db->lastInsertId();
}
    public function buscarProductos($termino) {
    $sql = "SELECT idProducto, nombreProducto, precioVenta, stockEnGeneral
            FROM productos
            WHERE nombreProducto LIKE ? 
            AND estado IN ('Activo', 'Disponible') 
            AND stockEnGeneral > 0
            LIMIT 10";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['%' . $termino . '%']);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    /* ── Devoluciones ────────────────────────────────────── */

    public function insertarDevolucion($idVenta, $idProducto, $idCliente, $motivo, $descripcion, $cantidad) {
        $sql = "INSERT INTO devoluciones (idVenta, idProducto, idCliente, motivo, descripcion, cantidad, fecha, estado)
                VALUES (?, ?, ?, ?, ?, ?, NOW(), 'Procesado')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idVenta, $idProducto, $idCliente, $motivo, $descripcion, $cantidad]);
    }

    public function consultarDevoluciones() {
        $sql = "SELECT d.idDevoluciones, d.motivo, d.descripcion, d.fecha, d.estado,
                       d.cantidad, p.nombreProducto
                FROM devoluciones d
                LEFT JOIN productos p ON d.idProducto = p.idProducto
                ORDER BY d.fecha DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ── Corte de caja ───────────────────────────────────── */

    public function ventasDeHoy() {
        $sql = "SELECT v.idVenta, v.fechaHora, v.totalVenta, v.idMetodoPago,
                       COUNT(d.idDetalle) AS totalItems
                FROM ventas v
                LEFT JOIN detalle_ventas d ON v.idVenta = d.idVenta
                WHERE DATE(v.fechaHora) = CURDATE()
                GROUP BY v.idVenta
                ORDER BY v.fechaHora DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function totalHoy() {
        $sql = "SELECT COALESCE(SUM(totalVenta), 0) FROM ventas WHERE DATE(fechaHora) = CURDATE()";
        return $this->db->query($sql)->fetchColumn();
    }
}
?>