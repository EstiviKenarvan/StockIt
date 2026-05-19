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
    .main-header.navbar,
    .main-header.navbar-white,
    .main-header.navbar-light,
    nav.main-header {
      background-color: #E8820C !important;
      border-bottom: none !important
    }

    .main-header .nav-link,
    .main-header .nav-link i,
    .main-header .navbar-nav .nav-item a,
    .navbar-light .navbar-nav .nav-link {
      color: #fff !important
    }

    .main-sidebar,
    .main-sidebar:before,
    .brand-link,
    .sidebar-dark-primary {
      background-color: #E8820C !important
    }

    .nav-sidebar .nav-link,
    .brand-link .brand-text,
    .sidebar .user-panel .info a,
    .nav-sidebar .nav-link i {
      color: #fff !important
    }

    .content-wrapper {
      background-color: #FFF5E5 !important
    }

    .modal-header-stockit {
      background-color: #E8820C;
      color: white;
      border-radius: 5px 5px 0 0
    }

    .modal-header-stockit .close {
      color: white;
      opacity: 1
    }

    .modal {
      z-index: 1060 !important
    }

    .modal-backdrop {
      z-index: 1050 !important
    }

    .text-orange {
      color: #E8820C !important
    }

    /* ── Panel IVA ── */
    .iva-panel {
      background: #FFF3E0;
      border: 1px solid #FFE0B2;
      border-radius: 10px;
      padding: 14px 18px
    }

    .iva-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 4px 0;
      font-size: .9rem
    }

    .iva-row .label {
      color: #888
    }

    .iva-row .value {
      font-weight: 600;
      color: #444
    }

    .iva-row.total-final {
      border-top: 2px solid #E8820C;
      margin-top: 6px;
      padding-top: 8px
    }

    .iva-row.total-final .label {
      color: #E8820C;
      font-weight: bold;
      font-size: 1rem
    }

    .iva-row.total-final .value {
      color: #E8820C;
      font-size: 1.3rem;
      font-weight: bold
    }

    .iva-exento {
      opacity: .5;
      pointer-events: none
    }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <?php $paginaActiva = "productos";
    include __DIR__ . "/includes/barras.php"; ?>

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
            $total           = count($productos);
            $activos         = count(array_filter($productos, fn($p) => in_array($p['estado'] ?? '', ['Disponible', 'Activo'])));
            $porCaducar      = count(array_filter($productos, fn($p) => !empty($p['fechaCaducidad']) && strtotime($p['fechaCaducidad']) <= strtotime('+30 days')));
            $categorias_count = count(array_unique(array_column($productos, 'nombreCategoria')));
            ?>
            <?= tarjeta("verde",  $total,            "Total de Productos", "bolsa") ?>
            <?= tarjeta("blanco", $activos,           "Activos",            "check") ?>
            <?= tarjeta("rojo",   $porCaducar,        "Por caducar",        "calendario") ?>
            <?= tarjeta("azul",   $categorias_count,  "Categorías",         "estadisticas") ?>
          </div>

          <?php
          $columnas = ["Código", "Nombre del Producto", "Categoría", "Stock", "Precio s/IVA", "IVA", "Precio c/IVA", "Estado", "Acciones"];
          $datos    = [];
          foreach ($productos as $p):
            $estado = $p['estado'] ?? 'Disponible';
            $estado   = isset($p['estado']) ? $p['estado'] : 'Disponible';
            $badge    = match ($estado) {
              'Disponible' => "<span class='badge badge-success'>Disponible</span>",
              'Poco Stock' => "<span class='badge badge-warning'>Poco Stock</span>",
              'Agotado'    => "<span class='badge badge-danger'>Agotado</span>",
              default      => "<span class='badge badge-secondary'>$estado</span>"
            };
            $ivaPorc  = isset($p['ivaPorc']) ? $p['ivaPorc'] : 16;
            $ivaVenta = isset($p['ivaVenta']) ? $p['ivaVenta'] : 0;
            $pvFinal  = round($p['precioVenta'] * (1 + $ivaPorc / 100), 2);
            $acciones = "
              <button type='button' class='btn btn-xs btn-warning mr-1 btn-editar'
                data-id='{$p['idProducto']}'
                data-nombre='" . htmlspecialchars($p['nombreProducto'], ENT_QUOTES) . "'
                data-categoria='" . ($p['idCategoria'] ?? '') . "'
                data-compra='" . ($p['precioCompra'] ?? 0) . "'
                data-venta='" . ($p['precioVenta'] ?? 0) . "'
                data-stock='" . ($p['stockEnGeneral'] ?? 0) . "'
                data-caducidad='" . ($p['fechaCaducidad'] ?? '') . "'
                data-barras='" . ($p['codigoBarras'] ?? '') . "'
                data-estado='$estado'
                data-notas='" . htmlspecialchars($p['notas'] ?? '', ENT_QUOTES) . "'
                data-ivaporc='$ivaPorc'>
                <i class='fas fa-edit'></i>
              </button>
              <button type='button' class='btn btn-xs btn-danger btn-borrar'
                data-id='{$p['idProducto']}'
                data-nombre='" . htmlspecialchars($p['nombreProducto'], ENT_QUOTES) . "'>
                <i class='fas fa-trash'></i>
              </button>";

            $datos[] = [
              $p['codigoBarras'] ?? '---',
              $p['nombreProducto'],
              $p['nombreCategoria'] ?? '—',
              ($p['stockEnGeneral'] ?? 0) . ' uds.',
              '$' . number_format($p['precioVenta'] ?? 0, 2),
              $ivaPorc . '%',
              '$' . number_format($pvFinal, 2),
              $badge,
              $acciones
            ];
          endforeach;
          echo crearTabla("Inventario", $columnas, $datos, "tabla_productos");
          ?>
        </div>
      </section>
    </div>

    <!-- ══════════════════════════════════════════
       MODAL: AGREGAR / EDITAR PRODUCTO
  ══════════════════════════════════════════ -->
    <div class="modal fade" id="modalProducto" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header modal-header-stockit">
            <h5 class="modal-title" id="modalTitulo">
              <i class="fas fa-plus-circle mr-2"></i>Nuevo producto
            </h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <form id="formProducto" action="" method="POST" enctype="multipart/form-data">
            <div class="modal-body">

              <!-- ── Información general ── -->
              <h5 class="text-orange font-weight-bold">
                <i class="fas fa-info-circle mr-2"></i>Información general
              </h5>
              <hr>
              <div class="row">
                <div class="col-md-6 form-group">
                  <label>Nombre del producto *</label>
                  <input type="text" name="nombreProducto" id="m_nombre" class="form-control"
                    placeholder="Ej. Gansito Marinela" required>
                </div>
                <div class="col-md-6 form-group">
                  <label>Categoría *</label>
                  <select name="idCategoria" id="m_categoria" class="form-control" required>
                    <option value="" disabled selected>Seleccionar categoría</option>
                    <?php foreach ($categorias ?? [] as $c): ?>
                      <option value="<?= $c['idCategoria'] ?>"><?= htmlspecialchars($c['nombreCategoria']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <!-- ── Precios ── -->
              <h5 class="text-orange font-weight-bold mt-2">
                <i class="fas fa-tag mr-2"></i>Precios e IVA
              </h5>
              <hr>
              <div class="row">

                <!-- Precio compra -->
                <div class="col-md-3 form-group">
                  <label>Precio de compra (sin IVA) *</label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                    <input type="number" name="precioCompra" id="p_compra" class="form-control"
                      placeholder="0.00" step="0.01" min="0" required>
                  </div>
                </div>

                <!-- Precio venta -->
                <div class="col-md-3 form-group">
                  <label>Precio de venta (sin IVA) *</label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                    <input type="number" name="precioVenta" id="p_venta" class="form-control"
                      placeholder="0.00" step="0.01" min="0" required>
                  </div>
                  <small id="error-precio" class="text-danger" style="display:none;font-weight:bold">
                    ¡Precio de venta menor al de compra!
                  </small>
                </div>

                <!-- % IVA -->
                <div class="col-md-3 form-group">
                  <label>IVA aplicable</label>
                  <div class="input-group">
                    <select name="ivaPorc" id="m_iva" class="form-control">
                      <option value="16">16% — Tasa general</option>
                      <option value="8">8% — Zona fronteriza</option>
                      <option value="0">0% — Exento / Tasa cero</option>
                    </select>
                    <div class="input-group-append"><span class="input-group-text">%</span></div>
                  </div>
                </div>

                <!-- Stock -->
                <div class="col-md-3 form-group">
                  <label>Stock total</label>
                  <!-- Línea del input stock, agrega value="0" -->
                  <input type="number" name="stockEnGeneral" id="m_stock" class="form-control"
                    placeholder="Cantidad" min="0" value="0">
                </div>
              </div>

              <!-- Panel resumen IVA -->
              <div class="iva-panel mb-3" id="iva-panel">
                <p class="font-weight-bold mb-2" style="color:#E8820C">
                  <i class="fas fa-calculator mr-1"></i> Resumen de precios con IVA
                </p>
                <div class="row">
                  <div class="col-md-6">
                    <div class="iva-row">
                      <span class="label">Precio compra sin IVA</span>
                      <span class="value" id="r_compra_sin">$0.00</span>
                    </div>
                    <div class="iva-row">
                      <span class="label">IVA compra (<span id="r_porc_c">16</span>%)</span>
                      <span class="value" id="r_iva_compra">$0.00</span>
                    </div>
                    <div class="iva-row total-final">
                      <span class="label">Costo total compra</span>
                      <span class="value" id="r_compra_con">$0.00</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="iva-row">
                      <span class="label">Precio venta sin IVA</span>
                      <span class="value" id="r_venta_sin">$0.00</span>
                    </div>
                    <div class="iva-row">
                      <span class="label">IVA venta (<span id="r_porc_v">16</span>%)</span>
                      <span class="value" id="r_iva_venta">$0.00</span>
                    </div>
                    <div class="iva-row total-final">
                      <span class="label">Precio final al cliente</span>
                      <span class="value" id="r_venta_con">$0.00</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Campos ocultos calculados -->
              <input type="hidden" name="ivaCompra" id="h_iva_compra">
              <input type="hidden" name="ivaVenta" id="h_iva_venta">
              <input type="hidden" name="precioVentaFinal" id="h_precio_final">

              <!-- ── Caducidad y estado ── -->
              <h5 class="text-orange font-weight-bold mt-2">
                <i class="fas fa-barcode mr-2"></i>Caducidad y Estado
              </h5>
              <hr>
              <div class="row">
                <div class="col-md-4 form-group">
                  <label>Fecha de caducidad</label>
                  <input type="date" name="fechaCaducidad" id="m_caducidad" class="form-control">
                </div>
                <div class="col-md-4 form-group">
                  <label>Código de barras / SKU</label>
                  <input type="text" name="codigoBarras" id="m_barras" class="form-control"
                    placeholder="7501234567890">
                </div>
                <div class="col-md-4 form-group">
                  <label>Estado *</label>
                  <select name="estado" id="m_estado" class="form-control">
                    <option value="Disponible">Activo</option>
                    <option value="Agotado">Inactivo</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label>Notas adicionales</label>
                <textarea name="notas" id="m_notas" class="form-control" rows="2"
                  placeholder="Detalles..."></textarea>
              </div>

            </div><!-- /modal-body -->

            <div class="modal-footer bg-white border-top">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
              <button type="submit" id="btnGuardar" class="btn btn-warning px-4">
                <i class="fas fa-save mr-1"></i> Guardar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div><!-- /.wrapper -->

  <script src="public/plugins/jquery/jquery.min.js"></script>
  <script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="public/dist/js/adminlte.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // ── Cálculo IVA en tiempo real ────────────────────────────
    function calcularIva() {
      const compra = parseFloat($('#p_compra').val()) || 0;
      const venta = parseFloat($('#p_venta').val()) || 0;
      const porc = parseFloat($('#m_iva').val()) || 0;
      const factor = porc / 100;

      const ivaCompra = compra * factor;
      const ivaVenta = venta * factor;
      const totalCompra = compra + ivaCompra;
      const totalVenta = venta + ivaVenta;

      const fmt = n => '$' + n.toFixed(2);

      // Panel visual
      $('#r_compra_sin').text(fmt(compra));
      $('#r_iva_compra').text(fmt(ivaCompra));
      $('#r_compra_con').text(fmt(totalCompra));
      $('#r_venta_sin').text(fmt(venta));
      $('#r_iva_venta').text(fmt(ivaVenta));
      $('#r_venta_con').text(fmt(totalVenta));
      $('#r_porc_c, #r_porc_v').text(porc);

      // Campos ocultos para el POST
      $('#h_iva_compra').val(ivaCompra.toFixed(2));
      $('#h_iva_venta').val(ivaVenta.toFixed(2));
      $('#h_precio_final').val(totalVenta.toFixed(2));

      // Si es exento, atenuar panel
      if (porc === 0) {
        $('#iva-panel').addClass('iva-exento');
      } else {
        $('#iva-panel').removeClass('iva-exento');
      }

      // Validar precio venta vs compra
      if (venta > 0 && venta < compra) {
        $('#p_venta').addClass('is-invalid');
        $('#error-precio').show();
        $('#btnGuardar').prop('disabled', true);
      } else {
        $('#p_venta').removeClass('is-invalid');
        $('#error-precio').hide();
        $('#btnGuardar').prop('disabled', false);
      }
    }

    $('#p_compra, #p_venta, #m_iva').on('input change', calcularIva);

    // ── Abrir modal: nuevo producto ───────────────────────────
    function abrirModalAgregar() {
      $('#formProducto')[0].reset();
      $('#m_iva').val('16');
      $('#modalTitulo').html('<i class="fas fa-plus-circle mr-2"></i>Nuevo producto');
      $('#formProducto').attr('action', '?menu=productos&submenu=registro');
      calcularIva();
      $('#modalProducto').modal('show');
    }

    // ── Abrir modal: editar producto ──────────────────────────
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
      $('#m_iva').val(d.ivaporc ?? 16);

      calcularIva();
      $('#modalProducto').modal('show');
    });

    // ── Eliminar producto ─────────────────────────────────────
    $(document).on('click', '.btn-borrar', function() {
      const id = $(this).data('id');
      const nombre = $(this).data('nombre');
      Swal.fire({
        title: '¿Eliminar producto?',
        text: `Se borrará "${nombre}" permanentemente.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Sí, eliminar'
      }).then(r => {
        if (r.isConfirmed)
          window.location = `index.php?menu=productos&submenu=borrar&id=${id}`;
      });
    });
  </script>
</body>

</html>