<?php
include __DIR__ . "/includes/tarjeta.php";
include __DIR__ . "/includes/tablas.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>StockIt | Gestión de Productos</title>
  
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="public/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="public/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  
  <style>
    .main-header.navbar,.main-header.navbar-white,.main-header.navbar-light,nav.main-header{background-color:#E8820C!important;border-bottom:none!important}
    .main-header .nav-link,.main-header .nav-link i,.main-header .navbar-nav .nav-item a,.navbar-light .navbar-nav .nav-link{color:#fff!important}
    .main-sidebar,.main-sidebar:before,.brand-link,.sidebar-dark-primary{background-color:#E8820C!important}
    .nav-sidebar .nav-link,.brand-link .brand-text,.sidebar .user-panel .info a,.nav-sidebar .nav-link i{color:#fff!important}
    .content-wrapper{background-color:#FFF5E5!important}
    .modal-header-stockit{background-color:#E8820C;color:white;border-radius:5px 5px 0 0}
    .modal-header-stockit .close{color:white;opacity:1}
    .modal { z-index: 1060 !important; }
    .modal-backdrop { z-index: 1050 !important; }
    .text-orange { color: #E8820C !important; }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  
  <?php $paginaActiva = "productos"; include __DIR__ . "/includes/barras.php"; ?>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-3 align-items-end">
          <div class="col-sm-6">
            <h1 class="m-0 d-inline" style="color:#333;font-weight:bold">Gestión de</h1>
            <h1 class="m-0 d-inline" style="color:#dd6e12;font-style:italic"> Productos</h1>
          </div>
          <div class="col-sm-6 d-flex justify-content-end align-items-center">
            <button type="button" class="btn btn-outline-warning" onclick="abrirModalAgregar()">
              <i class="fas fa-plus mr-1"></i> Agregar Producto
            </button>
          </div>
        </div>

        <?php if (isset($_GET['exito'])): ?>
          <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i>
            <?= $_GET['exito'] == 1 ? 'Producto agregado correctamente.' : 'Producto actualizado correctamente.' ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <?php
          $total = count($productos);
          $activos = count(array_filter($productos, fn($p) => ($p['estado'] ?? '') == 'Disponible' || ($p['estado'] ?? '') == 'Activo'));
          $porCaducar = count(array_filter($productos, fn($p) => !empty($p['fechaCaducidad']) && strtotime($p['fechaCaducidad']) <= strtotime('+30 days')));
          $categorias_count = count(array_unique(array_column($productos, 'nombreCategoria')));
          ?>
          <?= tarjeta("verde",  $total, "Total de Productos", "bolsa"); ?>
          <?= tarjeta("blanco", $activos, "Activos", "check"); ?>
          <?= tarjeta("rojo",   $porCaducar, "Por caducar", "calendario"); ?>
          <?= tarjeta("azul",   $categorias_count,"Categorias", "estadisticas"); ?>
        </div>

        <?php
        $columnas = ["Código", "Nombre del Producto", "Categoría", "Stock Actual", "Precio Venta", "Estado", "Acciones"];
        $datos = [];
        foreach ($productos as $p) {
            $estado = $p['estado'] ?? 'Disponible';
            $badge = match($estado) {
                'Disponible' => "<span class='badge badge-success'>Disponible</span>",
                'Poco Stock' => "<span class='badge badge-warning'>Poco Stock</span>",
                'Agotado'    => "<span class='badge badge-danger'>Agotado</span>",
                default      => "<span class='badge badge-secondary'>$estado</span>"
            };
            
            $acciones = "
              <button type='button' class='btn btn-xs btn-warning mr-1 btn-editar' 
                data-id='{$p['idProducto']}' 
                data-nombre='".htmlspecialchars($p['nombreProducto'], ENT_QUOTES)."'
                data-categoria='".($p['idCategoria'] ?? '')."' 
                data-compra='".($p['precioCompra'] ?? '0')."'
                data-venta='".($p['precioVenta'] ?? '0')."' 
                data-stock='".($p['stockEnGeneral'] ?? '0')."'
                data-caducidad='".($p['fechaCaducidad'] ?? '')."' 
                data-barras='".($p['codigoBarras'] ?? '')."'
                data-estado='{$estado}' 
                data-notas='".htmlspecialchars($p['notas'] ?? '', ENT_QUOTES)."'>
                <i class='fas fa-edit'></i>
              </button>
              <button type='button' class='btn btn-xs btn-danger btn-borrar' 
                data-id='{$p['idProducto']}' 
                data-nombre='".htmlspecialchars($p['nombreProducto'], ENT_QUOTES)."'>
                <i class='fas fa-trash'></i>
              </button>
            ";

            $datos[] = [
                $p['codigoBarras'] ?? '---',
                $p['nombreProducto'],
                $p['nombreCategoria'] ?? '—',
                ($p['stockEnGeneral'] ?? 0) . ' uds.',
                '$' . number_format(($p['precioVenta'] ?? 0), 2),
                $badge,
                $acciones
            ];
        }
        echo crearTabla("Inventario", $columnas, $datos, "tabla_productos");
        ?>
      </div>
    </section>
  </div>

  <div class="modal fade" id="modalProducto" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header modal-header-stockit">
          <h5 class="modal-title" id="modalTitulo"><i class="fas fa-plus-circle mr-2"></i>Nuevo producto</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <form id="formProducto" action="" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
            <h5 class="text-orange font-weight-bold"><i class="fas fa-info-circle mr-2"></i>Información general</h5>
            <hr>
            <div class="row">
              <div class="col-md-6 form-group">
                <label>Nombre del producto *</label>
                <input type="text" name="nombreProducto" id="m_nombre" class="form-control" placeholder="Ej. Gansito Marinela" required>
              </div>
              <div class="col-md-6 form-group">
                <label>Categoría *</label>
                <select name="idCategoria" id="m_categoria" class="form-control" required>
    <option value="" disabled selected>Seleccionar categoría</option>
    <?php if (!empty($categorias)): ?>
        <?php foreach ($categorias as $c): ?>
            <option value="<?= $c['idCategoria'] ?>"><?= htmlspecialchars($c['nombreCategoria']) ?></option>
        <?php endforeach; ?>
    <?php endif; ?>
</select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4 form-group">
                <label>Precio de compra *</label>
                <div class="input-group">
                  <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                  <input type="number" name="precioCompra" id="p_compra" class="form-control" placeholder="0.00" step="0.01" required>
                </div>
              </div>
              <div class="col-md-4 form-group">
                <label>Precio de venta *</label>
                <div class="input-group">
                  <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                  <input type="number" name="precioVenta" id="p_venta" class="form-control" placeholder="0.00" step="0.01" required>
                </div>
                <small id="error-precio" class="text-danger" style="display:none; font-weight:bold;">¡Venta menor a compra!</small>
              </div>
              <div class="col-md-4 form-group">
                <label>Stock Total</label>
                <input type="number" name="stockEnGeneral" id="m_stock" class="form-control" placeholder="Cantidad">
              </div>
            </div>

            <h5 class="text-orange font-weight-bold mt-4"><i class="fas fa-barcode mr-2"></i>Caducidad y Estado</h5>
            <hr>
            <div class="row">
              <div class="col-md-4 form-group">
                <label>Fecha de caducidad</label>
                <input type="date" name="fechaCaducidad" id="m_caducidad" class="form-control">
              </div>
              <div class="col-md-4 form-group">
                <label>Código de barras / SKU</label>
                <input type="text" name="codigoBarras" id="m_barras" class="form-control" placeholder="7501234567890">
              </div>
              <div class="col-md-4 form-group">
                <label>Estado *</label>
                <select name="estado" id="m_estado" class="form-control">
                  <option value="Disponible">Activo</option>
                  <option value="Agotado">Inactivo</option>
                </select>
              </div>
            </div>

            <div class="form-group mt-3">
              <label>Notas adicionales</label>
              <textarea name="notas" id="m_notas" class="form-control" rows="3" placeholder="Detalles..."></textarea>
            </div>
          </div>
          <div class="modal-footer bg-white border-top">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" id="btnGuardar" class="btn btn-warning px-4">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>

<script src="public/plugins/jquery/jquery.min.js"></script>
<script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="public/dist/js/adminlte.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Abrir modal para nuevo registro
function abrirModalAgregar() {
    $('#formProducto')[0].reset();
    $('#modalTitulo').html('<i class="fas fa-plus-circle mr-2"></i>Nuevo producto');
    $('#formProducto').attr('action', '?menu=productos&submenu=registro');
    $('#modalProducto').modal('show');
}

// Cargar datos en el modal para editar
$(document).on('click', '.btn-editar', function() {
    const d = $(this).data();
    $('#modalTitulo').html('<i class="fas fa-edit mr-2"></i>Editar producto');
    $('#formProducto').attr('action', `?menu=productos&submenu=editar&id=${d.id}`);
    
    $('#m_nombre').val(d.nombre);
    $('#m_categoria').val(d.categoria);
    $('#p_compra').val(d.compra);
    $('#p_venta').val(d.venta);
    $('#m_stock').val(d.stock);
    $('#m_caducidad').val(d.caducidad);
    $('#m_barras').val(d.barras);
    $('#m_estado').val(d.estado);
    $('#m_notas').val(d.notas);
    
    $('#modalProducto').modal('show');
});

// Validación de Precios
$(document).ready(function() {
    const inputCompra = $('#p_compra'), inputVenta = $('#p_venta');
    const mensajeError = $('#error-precio'), btnGuardar = $('#btnGuardar');

    function validarPrecios() {
        const compra = parseFloat(inputCompra.val()) || 0, venta = parseFloat(inputVenta.val()) || 0;
        if (venta > 0 && venta < compra) {
            inputVenta.addClass('is-invalid');
            mensajeError.show();
            btnGuardar.prop('disabled', true);
        } else {
            inputVenta.removeClass('is-invalid');
            mensajeError.hide();
            btnGuardar.prop('disabled', false);
        }
    }
    inputCompra.on('input', validarPrecios);
    inputVenta.on('input', validarPrecios);
});

// Borrado con SweetAlert
$(document).on('click', '.btn-borrar', function() {
    const id = $(this).data('id'), nombre = $(this).data('nombre');
    Swal.fire({
        title: '¿Eliminar producto?',
        text: `Se borrará "${nombre}" permanentemente.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Sí, eliminar'
    }).then(r => { if (r.isConfirmed) window.location = `index.php?menu=productos&submenu=borrar&id=${id}`; });
});
</script>
</body>
</html>