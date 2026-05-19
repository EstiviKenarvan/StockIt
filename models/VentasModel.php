<?php
class VentasModel {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    /* ── Ventas ──────────────────────────────────────────── */

    public function consultar() {
        $sql = "SELECT v.idVenta, v.fechaHora, v.totalVenta, v.tipoMetodo, v.folio,
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
        $sql = "SELECT d.idDetalle, p.nombreProducto, d.cantidad,
                       d.totalVentaP,
                       COALESCE(p.ivaPorc, 16) AS ivaPorc
                FROM detalle_ventas d
                LEFT JOIN productos p ON d.idProducto = p.idProducto
                WHERE d.idVenta = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idVenta]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertar($idCliente, $idMetodoPago, $idModo, $totalVenta, $tipoMetodo, $folio = null) {
        $sql = "INSERT INTO ventas (idCliente, idMetodoPago, idModo, fechaHora, totalVenta, tipoMetodo, folio)
                VALUES (?, ?, ?, NOW(), ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idCliente, $idMetodoPago, $idModo, $totalVenta, $tipoMetodo, $folio]);
        return $this->db->lastInsertId();
    }

    public function insertarDetalle($idVenta, $idProducto, $totalVentaP, $cantidad) {
        $sql = "INSERT INTO detalle_ventas (idVenta, idProducto, totalVentaP, cantidad, productoBajarotacion, image)
                VALUES (?, ?, ?, ?, 0, '')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idVenta, $idProducto, $totalVentaP, $cantidad]);
    }

    public function descontarStock($idProducto, $cantidad) {
        $sql = "UPDATE productos SET stockEnGeneral = stockEnGeneral - ? WHERE idProducto = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$cantidad, $idProducto]);
    }

    public function eliminar($idVenta) {
        $stmt = $this->db->prepare("DELETE FROM ventas WHERE idVenta = ?");
        return $stmt->execute([$idVenta]);
    }

    public function insertarMetodoPago($total, $metodo) {
        $efectivo      = $metodo == 1 ? $total : 0;
        $credito       = $metodo == 2 ? $total : 0;
        $transferencia = $metodo == 3 ? $total : 0;
        $sql  = "INSERT INTO ventaporpedido (efectivo, credito, transferencia) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$efectivo, $credito, $transferencia]);
        return $this->db->lastInsertId();
    }

    public function insertarModoVenta($idCliente, $idProducto, $cantidad) {
        $sql = "INSERT INTO modo_ventas
                    (ventaDiracta, ventaClienteFrecuente, VentaporPedido, VnetaconDescuento, idCliente, idProducto, cantidad)
                VALUES ('Venta Directa', '', '', '', ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idCliente ?? 1, $idProducto, $cantidad]);
        return $this->db->lastInsertId();
    }

    /* ── Búsqueda de productos ───────────────────────────── */

    public function buscarProductos($termino) {
        $sql = "SELECT
                    idProducto,
                    nombreProducto,
                    precioVenta,
                    stockEnGeneral,
                    ivaPorc,
                    -- Si precioVentaConIva es 0 o NULL, lo calcula al vuelo
                    COALESCE(NULLIF(precioVentaConIva, 0), precioVenta * (1 + ivaPorc / 100)) AS precioVentaFinal
                FROM productos
                WHERE nombreProducto LIKE ?
                  AND estado IN ('Activo','Disponible')
                  AND stockEnGeneral > 0
                LIMIT 10";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['%' . $termino . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorBarcode($codigo) {
        $sql = "SELECT
                    idProducto,
                    nombreProducto,
                    precioVenta,
                    stockEnGeneral,
                    codigoBarras,
                    ivaPorc,
                    COALESCE(NULLIF(precioVentaConIva, 0), precioVenta * (1 + ivaPorc / 100)) AS precioVentaFinal
                FROM productos
                WHERE codigoBarras = ?
                  AND estado IN ('Activo','Disponible')
                  AND stockEnGeneral > 0
                LIMIT 5";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$codigo]);
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
        $sql = "SELECT v.idVenta, v.fechaHora, v.totalVenta,
                       v.tipoMetodo, v.folio,
                       COALESCE(SUM(d.cantidad), 0) AS totalItems
                FROM ventas v
                LEFT JOIN detalle_ventas d ON v.idVenta = d.idVenta
                WHERE DATE(v.fechaHora) = CURDATE()
                GROUP BY v.idVenta
                ORDER BY v.fechaHora DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function totalHoy() {
        return $this->db->query(
            "SELECT COALESCE(SUM(totalVenta),0) FROM ventas WHERE DATE(fechaHora) = CURDATE()"
        )->fetchColumn();
    }

    /* ── Dashboard ───────────────────────────────────────── */

    public function ventasPorPeriodo($where, $formato) {
        $sql = "SELECT DATE_FORMAT(fechaHora, ?) as etiqueta,
                       COALESCE(SUM(totalVenta), 0) as total,
                       COUNT(*) as cantidad
                FROM ventas
                WHERE $where
                GROUP BY etiqueta
                ORDER BY MIN(fechaHora) ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$formato]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function entradasPorPeriodo($where, $formato) {
        $sql = "SELECT DATE_FORMAT(fechaEntrada, ?) as etiqueta,
                       COALESCE(SUM(cantidad), 0) as total
                FROM movimientos_inventario
                WHERE $where
                GROUP BY etiqueta
                ORDER BY MIN(fechaEntrada) ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$formato]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salidasPorPeriodo($where, $formato) {
        $sql = "SELECT DATE_FORMAT(v.fechaHora, ?) as etiqueta,
                       COALESCE(SUM(d.cantidad), 0) as total
                FROM ventas v
                LEFT JOIN detalle_ventas d ON v.idVenta = d.idVenta
                WHERE $where
                GROUP BY etiqueta
                ORDER BY MIN(v.fechaHora) ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$formato]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function masVendidos($limite = 6) {
        $sql = "SELECT p.nombreProducto,
                       COALESCE(SUM(d.cantidad), 0) as unidades,
                       COALESCE(SUM(d.totalVentaP), 0) as total
                FROM detalle_ventas d
                LEFT JOIN productos p ON d.idProducto = p.idProducto
                GROUP BY d.idProducto, p.nombreProducto
                ORDER BY unidades DESC
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limite]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function menosVendidos($limite = 6) {
        $sql = "SELECT p.nombreProducto,
                       COALESCE(SUM(d.cantidad), 0) as unidades,
                       COALESCE(SUM(d.totalVentaP), 0) as total
                FROM detalle_ventas d
                LEFT JOIN productos p ON d.idProducto = p.idProducto
                GROUP BY d.idProducto, p.nombreProducto
                ORDER BY unidades ASC
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limite]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>