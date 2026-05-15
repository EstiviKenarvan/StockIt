<?php
class ReportesModel {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function consultar() {
        $sql = "SELECT r.idReportes, r.tipoReporte, r.filtros,
                       r.peridodTiempo, r.fechaGenerado, r.formato,
                       p.nombreProducto,
                       u.Nombres as generadoPorNombre
                FROM reportes r
                LEFT JOIN productos p ON r.idProducto = p.idProducto
                LEFT JOIN usuarios u  ON r.generadoPor = u.idUsuarios
                ORDER BY r.fechaGenerado DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertar($tipoReporte, $filtros, $peridodTiempo, $idProducto, $formato, $generadoPor) {
        $sql = "INSERT INTO reportes (tipoReporte, filtros, peridodTiempo, idProducto, formato, generadoPor, fechaGenerado)
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            trim($tipoReporte), trim($filtros),
            $peridodTiempo, $idProducto ?: null,
            trim($formato), $generadoPor ?: null
        ]);
    }

    public function eliminar($idReportes) {
        $stmt = $this->db->prepare("DELETE FROM reportes WHERE idReportes = ?");
        return $stmt->execute([$idReportes]);
    }

    // ── Datos para cada tipo de reporte ──────────────────

    public function datosDisponibilidad() {
        return $this->db->query("
            SELECT p.codigoBarras, p.nombreProducto, c.nombreCategoria,
                   p.stockEnGeneral, p.precioVenta, p.estado, p.fechaCaducidad
            FROM productos p
            LEFT JOIN categorias c ON p.idCategoria = c.idCategoria
            ORDER BY p.estado ASC, p.nombreProducto ASC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function datosCaducidades() {
        return $this->db->query("
            SELECT p.codigoBarras, p.nombreProducto, c.nombreCategoria,
                   p.stockEnGeneral, p.fechaCaducidad, p.estado,
                   DATEDIFF(p.fechaCaducidad, CURDATE()) as diasRestantes
            FROM productos p
            LEFT JOIN categorias c ON p.idCategoria = c.idCategoria
            WHERE p.fechaCaducidad IS NOT NULL
            ORDER BY p.fechaCaducidad ASC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function datosVentas($fechaInicio, $fechaFin) {
        $sql = "SELECT v.idVenta, v.fechaHora, v.totalVenta,
                       p.nombreProducto, d.cantidad, d.totalVentaP
                FROM ventas v
                LEFT JOIN detalle_ventas d ON v.idVenta = d.idVenta
                LEFT JOIN productos p ON d.idProducto = p.idProducto
                WHERE DATE(v.fechaHora) BETWEEN ? AND ?
                ORDER BY v.fechaHora DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fechaInicio, $fechaFin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function datosGeneral() {
        $productos   = $this->db->query("SELECT COUNT(*) FROM productos")->fetchColumn();
        $stock       = $this->db->query("SELECT COALESCE(SUM(stockEnGeneral),0) FROM productos")->fetchColumn();
        $agotados    = $this->db->query("SELECT COUNT(*) FROM productos WHERE estado='Agotado'")->fetchColumn();
        $disponibles = $this->db->query("SELECT COUNT(*) FROM productos WHERE estado IN ('Activo','Disponible')")->fetchColumn();
        $ventasMes   = $this->db->query("SELECT COALESCE(SUM(totalVenta),0) FROM ventas WHERE MONTH(fechaHora)=MONTH(NOW()) AND YEAR(fechaHora)=YEAR(NOW())")->fetchColumn();
        $clientes    = $this->db->query("SELECT COUNT(*) FROM clientes")->fetchColumn();
        $categorias  = $this->db->query("
            SELECT c.nombreCategoria, COUNT(p.idProducto) as total, SUM(p.stockEnGeneral) as stock
            FROM categorias c LEFT JOIN productos p ON p.idCategoria = c.idCategoria
            GROUP BY c.idCategoria, c.nombreCategoria
        ")->fetchAll(PDO::FETCH_ASSOC);

        return compact('productos','stock','agotados','disponibles','ventasMes','clientes','categorias');
    }

    public function datosMovimientos($fechaInicio, $fechaFin) {
        $sql = "SELECT m.fechaEntrada, p.nombreProducto, m.cantidad,
                       m.costo, m.unidad, pv.nombreProveedor
                FROM movimientos_inventario m
                LEFT JOIN productos p   ON m.idProducto   = p.idProducto
                LEFT JOIN proveedores pv ON m.idProveedor = pv.idProveedor
                WHERE DATE(m.fechaEntrada) BETWEEN ? AND ?
                ORDER BY m.fechaEntrada DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fechaInicio, $fechaFin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>