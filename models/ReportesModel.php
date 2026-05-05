<?php
class ReportesModel {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function consultar() {
        $sql = "SELECT r.idReportes, r.tipoReporte, r.filtros,
                       r.peridodTiempo, p.nombreProducto
                FROM reportes r
                LEFT JOIN productos p ON r.idProducto = p.idProducto
                ORDER BY r.peridodTiempo DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function insertar($tipoReporte, $filtros, $peridodTiempo, $idProducto) {
        $sql = "INSERT INTO reportes (tipoReporte, filtros, peridodTiempo, idProducto)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            trim($tipoReporte), trim($filtros),
            $peridodTiempo, $idProducto
        ]);
    }

    public function eliminar($idReportes) {
        $sql = "DELETE FROM reportes WHERE idReportes = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idReportes]);
    }
}
?>