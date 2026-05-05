<?php
class ProductosModel {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function consultar() {
        $sql = "SELECT p.idProducto, p.codigoBarras, p.nombreProducto, c.nombreCategoria,
                       p.stockEnGeneral, p.precioVenta, p.estado, p.fechaCaducidad
                FROM productos p
                LEFT JOIN categorias c ON p.idCategoria = c.idCategoria
                ORDER BY p.idProducto ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function consultarPorId($idProducto) {
        $sql = "SELECT * FROM productos WHERE idProducto = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idProducto]);
        return $stmt->fetch();
    }

    public function insertar($codigoBarras, $nombreProducto, $precioCompra, $precioVenta, $stockEnGeneral, $idCategoria, $idProveedor, $fechaCaducidad, $estado, $notas) {
        $sql = "INSERT INTO productos (codigoBarras, nombreProducto, precioCompra, precioVenta, stockEnGeneral, idCategoria, idProveedor, fechaCaducidad, estado, notas)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            trim($codigoBarras), trim($nombreProducto),
            trim($precioCompra), trim($precioVenta),
            trim($stockEnGeneral), $idCategoria, $idProveedor,
            $fechaCaducidad, trim($estado), trim($notas)
        ]);
    }

    public function actualizar($idProducto, $codigoBarras, $nombreProducto, $precioCompra, $precioVenta, $stockEnGeneral, $idCategoria, $idProveedor, $fechaCaducidad, $estado, $notas) {
        $sql = "UPDATE productos SET codigoBarras=?, nombreProducto=?, precioCompra=?, precioVenta=?,
                stockEnGeneral=?, idCategoria=?, idProveedor=?, fechaCaducidad=?, estado=?, notas=?
                WHERE idProducto=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            trim($codigoBarras), trim($nombreProducto),
            trim($precioCompra), trim($precioVenta),
            trim($stockEnGeneral), $idCategoria, $idProveedor,
            $fechaCaducidad, trim($estado), trim($notas),
            $idProducto
        ]);
    }

    public function eliminar($idProducto) {
        $sql = "DELETE FROM productos WHERE idProducto = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idProducto]);
    }

    public function contarPorEstado($estado) {
        $sql = "SELECT COUNT(*) FROM productos WHERE estado = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$estado]);
        return $stmt->fetchColumn();
    }

    public function contarTotal() {
        $sql = "SELECT COUNT(*) FROM productos";
        return $this->db->query($sql)->fetchColumn();
    }
   
    public function consultarCategorias() {
    return $this->db->query("SELECT idCategoria, nombreCategoria FROM categorias ORDER BY nombreCategoria ASC")->fetchAll();
}

public function consultarProveedores() {
    return $this->db->query("SELECT idProveedor, nombreProveedor FROM proveedores ORDER BY nombreProveedor ASC")->fetchAll();
}
}
?>