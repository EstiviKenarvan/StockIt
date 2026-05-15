<?php
$totalProductos  = $conexion->query("SELECT COUNT(*) FROM productos")->fetchColumn() ?: 0;
$totalStock      = $conexion->query("SELECT SUM(stockEnGeneral) FROM productos")->fetchColumn() ?: 0;
$agotados        = $conexion->query("SELECT COUNT(*) FROM productos WHERE estado = 'Agotado'")->fetchColumn() ?: 0;
$pocoStock       = $conexion->query("SELECT COUNT(*) FROM productos WHERE estado = 'Poco Stock'")->fetchColumn() ?: 0;
$disponibles     = $conexion->query("SELECT COUNT(*) FROM productos WHERE estado = 'Disponible'")->fetchColumn() ?: 0;
$porCaducar      = $conexion->query("SELECT COUNT(*) FROM productos WHERE fechaCaducidad IS NOT NULL AND fechaCaducidad <= DATE_ADD(NOW(), INTERVAL 30 DAY)")->fetchColumn() ?: 0;
$ventasHoy       = $conexion->query("SELECT COALESCE(SUM(totalVenta),0) FROM ventas WHERE DATE(fechaHora) = CURDATE()")->fetchColumn() ?: 0;
$numVentasHoy    = $conexion->query("SELECT COUNT(*) FROM ventas WHERE DATE(fechaHora) = CURDATE()")->fetchColumn() ?: 0;
$totalClientes   = $conexion->query("SELECT COUNT(*) FROM clientes")->fetchColumn() ?: 0;

$stockCategorias = $conexion->query("
    SELECT c.nombreCategoria, SUM(p.stockEnGeneral) as total
    FROM productos p LEFT JOIN categorias c ON p.idCategoria = c.idCategoria
    GROUP BY c.idCategoria, c.nombreCategoria ORDER BY total DESC LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

$gastosMeses = $conexion->query("
    SELECT DATE_FORMAT(fechaEntrada,'%b') as mes, COALESCE(SUM(costo*cantidad),0) as total
    FROM movimientos_inventario WHERE fechaEntrada >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY MONTH(fechaEntrada), mes ORDER BY MONTH(fechaEntrada) ASC
")->fetchAll(PDO::FETCH_ASSOC);

$ventasMeses = $conexion->query("
    SELECT DATE_FORMAT(fechaHora,'%b') as mes, COALESCE(SUM(totalVenta),0) as total
    FROM ventas WHERE fechaHora >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY MONTH(fechaHora), mes ORDER BY MONTH(fechaHora) ASC
")->fetchAll(PDO::FETCH_ASSOC);

$catLabels   = array_column($stockCategorias, 'nombreCategoria') ?: ['Sin datos'];
$catTotales  = array_column($stockCategorias, 'total') ?: [0];
$mesesLabels = array_column($ventasMeses, 'mes') ?: ['Sin datos'];
$ventasData  = array_column($ventasMeses, 'total') ?: [0];
$gastosData  = array_column($gastosMeses, 'total') ?: [0];

$totalProd = max($totalProductos, 1);
$pctDisp   = round($disponibles / $totalProd * 100);
$pctCaduc  = round($porCaducar  / $totalProd * 100);
$pctPoco   = round($pocoStock   / $totalProd * 100);
$pctAgot   = round($agotados    / $totalProd * 100);

$coloresCat = ['#E8820C','#2E86C1','#28B463','#6F42C1','#AF601A','#DC3545'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>StockIt | Dashboard</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="public/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="public/dist/css/adminlte.min.css">
  <style>
    .main-header.navbar,.main-header.navbar-white,.main-header.navbar-light,nav.main-header{background-color:#E8820C!important;border-bottom:none!important}
    .main-header .nav-link,.main-header .nav-link i,.navbar-light .navbar-nav .nav-link{color:#fff!important}
    .main-sidebar,.main-sidebar:before,.brand-link,.sidebar-dark-primary{background-color:#E8820C!important}
    .nav-sidebar .nav-link{background:transparent!important;color:#fff!important}
    .nav-sidebar .nav-link:hover{background-color:rgba(255,255,255,.15)!important}
    .nav-sidebar .nav-link.active{background-color:rgba(0,0,0,.15)!important;color:#fff!important}
    .nav-sidebar .nav-link p,.nav-sidebar .nav-link i{color:#fff!important}
    .brand-link .brand-text,.sidebar .user-panel .info a{color:#fff!important}
    .form-control-sidebar,.btn-sidebar{background-color:#fff!important;border:1px solid #ddd!important;color:#333!important}
    .user-panel,.form-inline{border-bottom:1px solid rgba(255,255,255,.2)!important}
    .content-wrapper{background-color:#FFF5E5!important}
    .kpi-card{border-radius:15px;border:none;box-shadow:0 4px 15px rgba(0,0,0,.08);transition:transform .2s}
    .kpi-card:hover{transform:translateY(-3px)}
    .kpi-icon{width:55px;height:55px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem}
    .kpi-number{font-size:2rem;font-weight:800;line-height:1}
    .kpi-label{font-size:.8rem;color:#999;margin-top:4px}
    .chart-card{border-radius:15px;border:none;box-shadow:0 4px 15px rgba(0,0,0,.06)}
    .chart-title{font-weight:700;font-size:1rem;color:#333}
    .chart-subtitle{font-size:.78rem;color:#aaa}
    .leyenda-item{display:flex;align-items:center;margin-bottom:6px;font-size:.83rem}
    .color-box{width:12px;height:12px;border-radius:3px;margin-right:8px;flex-shrink:0}
    .estado-row{margin-bottom:12px}
    .estado-row .label{font-size:.83rem;color:#555}
    .estado-row .valor{font-size:.83rem;font-weight:700}
    .btn-periodo{border:2px solid #E8820C;color:#E8820C;background:white;border-radius:20px;padding:4px 16px;font-size:.8rem;font-weight:600;transition:.2s;cursor:pointer}
    .btn-periodo.active,.btn-periodo:hover{background:#E8820C;color:white}
    .card-tendencia{border-radius:15px;border:none;background:linear-gradient(135deg,#1a1a2e 0%,#16213e 50%,#0f3460 100%);box-shadow:0 8px 25px rgba(0,0,0,.25)}
    .card-tendencia .chart-title{color:#fff}
    .card-tendencia .chart-subtitle{color:rgba(255,255,255,.5)}
    .badge-tend{background:rgba(255,255,255,.1);color:#fff;border-radius:20px;padding:4px 12px;font-size:.75rem}
    .chart-loading{display:none;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);color:#E8820C;font-size:1.5rem}
    .chart-wrap{position:relative}
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <?php $paginaActiva = "dashboard"; include __DIR__ . "/includes/barras.php"; ?>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h1 class="m-0 d-inline" style="font-weight:800;color:#333">Dashboard</h1>
            <span class="ml-2 text-muted" style="font-size:.9rem">Resumen general del negocio</span>
          </div>
          <div class="d-flex align-items-center">
            <span class="text-muted mr-3" style="font-size:.85rem">Ver por:</span>
            <div id="filtros-periodo">
              <button class="btn-periodo mr-1" data-periodo="dia">Hoy</button>
              <button class="btn-periodo active mr-1" data-periodo="semana">Semana</button>
              <button class="btn-periodo" data-periodo="mes">Mes</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">

        <!-- KPIs -->
        <div class="row mb-4">
          <div class="col-lg-3 col-md-6 mb-3">
            <div class="card kpi-card p-3">
              <div class="d-flex align-items-center">
                <div class="kpi-icon mr-3" style="background:#FFF3E0"><i class="fas fa-boxes" style="color:#E8820C"></i></div>
                <div>
                  <div class="kpi-number" style="color:#E8820C"><?= number_format($totalProductos) ?></div>
                  <div class="kpi-label">Productos registrados</div>
                </div>
              </div>
              <div class="mt-2 pt-2" style="border-top:1px solid #f5f5f5">
                <small class="text-muted">Stock total: <b><?= number_format($totalStock) ?> uds.</b></small>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-3">
            <div class="card kpi-card p-3">
              <div class="d-flex align-items-center">
                <div class="kpi-icon mr-3" style="background:#E8F5E9"><i class="fas fa-dollar-sign" style="color:#28a745"></i></div>
                <div>
                  <div class="kpi-number text-success">$<?= number_format($ventasHoy, 2) ?></div>
                  <div class="kpi-label">Vendido hoy</div>
                </div>
              </div>
              <div class="mt-2 pt-2" style="border-top:1px solid #f5f5f5">
                <small class="text-muted"><?= $numVentasHoy ?> transacciones</small>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-3">
            <div class="card kpi-card p-3">
              <div class="d-flex align-items-center">
                <div class="kpi-icon mr-3" style="background:#FFF8E1"><i class="fas fa-exclamation-triangle" style="color:#ffc107"></i></div>
                <div>
                  <div class="kpi-number text-warning"><?= $agotados + $pocoStock ?></div>
                  <div class="kpi-label">Productos con alerta</div>
                </div>
              </div>
              <div class="mt-2 pt-2" style="border-top:1px solid #f5f5f5">
                <small class="text-muted"><?= $agotados ?> agotados · <?= $pocoStock ?> poco stock</small>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-3">
            <div class="card kpi-card p-3">
              <div class="d-flex align-items-center">
                <div class="kpi-icon mr-3" style="background:#E3F2FD"><i class="fas fa-users" style="color:#2196f3"></i></div>
                <div>
                  <div class="kpi-number text-primary"><?= number_format($totalClientes) ?></div>
                  <div class="kpi-label">Clientes registrados</div>
                </div>
              </div>
              <div class="mt-2 pt-2" style="border-top:1px solid #f5f5f5">
                <small class="text-muted"><i class="fas fa-calendar-day mr-1"></i><?= date('d M Y') ?></small>
              </div>
            </div>
          </div>
        </div>

        <!-- Fila 1: Ventas período + Stock categoría -->
        <div class="row mb-4">
          <div class="col-md-8 mb-3">
            <div class="card chart-card p-4" style="background:#FFF9F2;border:1px solid #FAD7A0">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                  <div class="chart-title">Ventas del Período</div>
                  <div class="chart-subtitle" id="subtitulo-ventas">Últimos 7 días</div>
                </div>
                <span class="badge" style="background:#FFF3CD;color:#856404"><i class="fas fa-circle mr-1" style="color:#E8820C"></i>Monto ($)</span>
              </div>
              <div class="chart-wrap">
                <canvas id="graficaVentasPeriodo" style="height:220px;max-height:220px"></canvas>
                <div class="chart-loading" id="load-ventas"><i class="fas fa-spinner fa-spin"></i></div>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="card chart-card p-4 h-100" style="background:#FFF9F2;border:1px solid #FAD7A0">
              <div class="chart-title mb-1">Stock por Categoría</div>
              <div class="chart-subtitle mb-3">Distribución actual</div>
              <div style="position:relative;height:190px">
                <canvas id="graficoStockCategoria"></canvas>
              </div>
              <div class="mt-3">
                <?php foreach ($stockCategorias as $i => $cat): ?>
                  <div class="leyenda-item">
                    <div class="color-box" style="background:<?= $coloresCat[$i % count($coloresCat)] ?>"></div>
                    <span class="flex-grow-1"><?= htmlspecialchars($cat['nombreCategoria']) ?></span>
                    <b><?= number_format($cat['total']) ?></b>
                  </div>
                <?php endforeach; ?>
                <?php if (empty($stockCategorias)): ?>
                  <div class="text-muted text-center small">Sin datos aún</div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Fila 2: Gastos vs Ventas + Estado inventario -->
        <div class="row mb-4">
          <div class="col-md-7 mb-3">
            <div class="card chart-card p-4">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                  <div class="chart-title">Gastos vs Ventas</div>
                  <div class="chart-subtitle">Últimos 6 meses</div>
                </div>
                <div>
                  <span class="badge mr-1" style="background:#E8F5E9;color:#28a745"><i class="fas fa-circle mr-1"></i>Ventas</span>
                  <span class="badge" style="background:#FFEBEE;color:#dc3545"><i class="fas fa-circle mr-1"></i>Gastos</span>
                </div>
              </div>
              <canvas id="graficaGastosVentas" style="height:230px;max-height:230px"></canvas>
            </div>
          </div>
          <div class="col-md-5 mb-3">
            <div class="card chart-card p-4 h-100">
              <div class="chart-title mb-1">Estado del Inventario</div>
              <div class="chart-subtitle mb-4">Resumen de condición actual</div>
              <div class="estado-row">
                <div class="d-flex justify-content-between">
                  <span class="label">Disponibles</span><span class="valor text-success"><?= $disponibles ?>/<?= $totalProductos ?></span>
                </div>
                <div class="progress mt-1" style="height:8px;border-radius:5px">
                  <div class="progress-bar bg-success" style="width:<?= $pctDisp ?>%"></div>
                </div>
              </div>
              <div class="estado-row">
                <div class="d-flex justify-content-between">
                  <span class="label">Por caducar <small class="text-muted">(30 días)</small></span><span class="valor text-warning"><?= $porCaducar ?> productos</span>
                </div>
                <div class="progress mt-1" style="height:8px;border-radius:5px">
                  <div class="progress-bar bg-warning" style="width:<?= $pctCaduc ?>%"></div>
                </div>
              </div>
              <div class="estado-row">
                <div class="d-flex justify-content-between">
                  <span class="label">Poco stock</span><span class="valor" style="color:#E8820C"><?= $pocoStock ?> productos</span>
                </div>
                <div class="progress mt-1" style="height:8px;border-radius:5px">
                  <div class="progress-bar" style="width:<?= $pctPoco ?>%;background:#E8820C"></div>
                </div>
              </div>
              <div class="estado-row">
                <div class="d-flex justify-content-between">
                  <span class="label">Agotados</span><span class="valor text-danger"><?= $agotados ?> productos</span>
                </div>
                <div class="progress mt-1" style="height:8px;border-radius:5px">
                  <div class="progress-bar bg-danger" style="width:<?= $pctAgot ?>%"></div>
                </div>
              </div>
              <div class="mt-4 pt-3" style="border-top:1px solid #f0f0f0;text-align:center">
                <a href="?menu=productos" class="btn btn-sm btn-outline-warning px-4">
                  <i class="fas fa-boxes mr-1"></i> Ver todos los productos
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Fila 3: Tendencia oscura — ENTRADAS vs SALIDAS en unidades -->
        <div class="row mb-4">
          <div class="col-md-12">
            <div class="card-tendencia p-4">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                  <div class="chart-title">Tendencia: Entradas vs Salidas</div>
                  <div class="chart-subtitle" id="subtitulo-tendencia">Unidades ingresadas vs unidades vendidas — semana actual</div>
                </div>
                <div>
                  <span class="badge-tend mr-2"><i class="fas fa-circle mr-1" style="color:#64b5f6"></i>Entradas (uds.)</span>
                  <span class="badge-tend"><i class="fas fa-circle mr-1" style="color:#ff7043"></i>Salidas (uds.)</span>
                </div>
              </div>
              <div class="chart-wrap">
                <canvas id="graficaTendencia" style="height:220px;max-height:220px"></canvas>
                <div class="chart-loading" id="load-tendencia"><i class="fas fa-spinner fa-spin"></i></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Fila 4: Más y menos vendidos por unidades -->
        <div class="row mb-4">
          <div class="col-md-6 mb-3">
            <div class="card chart-card p-4">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                  <div class="chart-title"><i class="fas fa-trophy mr-2" style="color:#E8820C"></i>Productos Más Vendidos</div>
                  <div class="chart-subtitle">Top 6 por unidades vendidas</div>
                </div>
                <a href="?menu=analisisproductos" class="btn btn-sm btn-outline-warning">Ver análisis</a>
              </div>
              <div class="chart-wrap" style="position:relative;height:250px">
                <canvas id="graficaMasVendidos"></canvas>
                <div class="chart-loading" id="load-mas"><i class="fas fa-spinner fa-spin"></i></div>
              </div>
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="card chart-card p-4">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                  <div class="chart-title"><i class="fas fa-arrow-down mr-2 text-danger"></i>Productos Menos Vendidos</div>
                  <div class="chart-subtitle">Bottom 6 — menor rotación en unidades</div>
                </div>
                <a href="?menu=analisisproductos&submenu=menosvendidos" class="btn btn-sm btn-outline-danger">Ver análisis</a>
              </div>
              <div class="chart-wrap" style="position:relative;height:250px">
                <canvas id="graficaMenosVendidos"></canvas>
                <div class="chart-loading" id="load-menos"><i class="fas fa-spinner fa-spin"></i></div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>
  </div>

  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
    <div class="float-right d-none d-sm-inline-block"><b>Version</b> 3.1.0</div>
  </footer>
</div>

<script src="public/plugins/jquery/jquery.min.js"></script>
<script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="public/plugins/chart.js/Chart.min.js"></script>
<script src="public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="public/dist/js/adminlte.js"></script>
<script>
$(function() {

    // ── Estáticas ─────────────────────────────────────────

    new Chart($('#graficoStockCategoria')[0].getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($catLabels) ?>,
            datasets: [{ data: <?= json_encode(array_map('intval', $catTotales)) ?>,
                backgroundColor: <?= json_encode(array_slice($coloresCat, 0, count($catLabels))) ?>,
                borderWidth: 4, borderColor: '#FFF9F2' }]
        },
        options: { maintainAspectRatio: false, cutoutPercentage: 68, legend: { display: false } }
    });

    new Chart($('#graficaGastosVentas')[0].getContext('2d'), {
        type: 'line',
        data: {
            labels: <?= json_encode($mesesLabels) ?>,
            datasets: [
                { label:'Ventas ($)', data: <?= json_encode(array_map('floatval', $ventasData)) ?>,
                  borderColor:'#28a745', backgroundColor:'rgba(40,167,69,.08)',
                  tension:0.4, fill:true, pointBackgroundColor:'#28a745', pointRadius:5 },
                { label:'Gastos ($)', data: <?= json_encode(array_map('floatval', $gastosData)) ?>,
                  borderColor:'#dc3545', backgroundColor:'rgba(220,53,69,.08)',
                  tension:0.4, fill:true, pointBackgroundColor:'#dc3545', pointRadius:5 }
            ]
        },
        options: { responsive:true, maintainAspectRatio:false, legend:{display:false},
            scales:{ yAxes:[{ ticks:{ beginAtZero:true, callback: v => '$'+v } }] } }
    });

    // ── Dinámicas ─────────────────────────────────────────
    let grafVentas, grafTendencia, grafMas, grafMenos;

    const subtitulos = {
        dia:    'Ventas de hoy por hora',
        semana: 'Últimos 7 días',
        mes:    'Últimos 30 días'
    };
    const subtitulosTend = {
        dia:    'Entradas vs salidas en unidades — hoy',
        semana: 'Entradas vs salidas en unidades — semana actual',
        mes:    'Entradas vs salidas en unidades — últimos 30 días'
    };

    function truncar(str, n) {
        return str && str.length > n ? str.substring(0, n) + '…' : (str || 'N/A');
    }

    function actualizarGraficas(periodo) {
        $('.chart-loading').show();

        $.get('index.php?menu=ventas&submenu=datos-dashboard', { periodo }, data => {
            $('.chart-loading').hide();
            $('#subtitulo-ventas').text(subtitulos[periodo]);
            $('#subtitulo-tendencia').text(subtitulosTend[periodo]);

            // ── Ventas del período (barras en $) ──────────
            const labVentas = data.ventasPeriodo.map(v => v.etiqueta);
            const datVentas = data.ventasPeriodo.map(v => parseFloat(v.total));

            if (grafVentas) grafVentas.destroy();
            grafVentas = new Chart($('#graficaVentasPeriodo')[0].getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labVentas.length ? labVentas : ['Sin datos'],
                    datasets: [{
                        label: 'Ventas ($)', data: datVentas.length ? datVentas : [0],
                        backgroundColor: 'rgba(232,130,12,.8)', borderColor: '#E8820C',
                        borderWidth: 2, borderRadius: 6
                    }]
                },
                options: { responsive:true, maintainAspectRatio:false, legend:{display:false},
                    scales:{ yAxes:[{ ticks:{ beginAtZero:true, callback: v => '$'+v } }] } }
            });

            // ── Tendencia: entradas vs salidas en UNIDADES ─
            // Usamos las etiquetas de ventas como eje X común
            const labTend   = data.ventasPeriodo.map(v => v.etiqueta);

            // Entradas: alinear con etiquetas de ventas
            const datEnt = labTend.map(l => {
                const e = data.entradasPeriodo.find(x => x.etiqueta === l);
                return e ? parseInt(e.total) : 0;
            });

            // Salidas: unidades vendidas por período
            const datSal = labTend.map(l => {
                const s = data.salidasPeriodo.find(x => x.etiqueta === l);
                return s ? parseInt(s.total) : 0;
            });

            if (grafTendencia) grafTendencia.destroy();
            grafTendencia = new Chart($('#graficaTendencia')[0].getContext('2d'), {
                type: 'line',
                data: {
                    labels: labTend.length ? labTend : ['Sin datos'],
                    datasets: [
                        { label:'Entradas (uds.)', data: datEnt,
                          borderColor:'#64b5f6', backgroundColor:'rgba(100,181,246,.25)',
                          tension:0.4, fill:true, pointBackgroundColor:'#64b5f6', pointRadius:5, borderWidth:2 },
                        { label:'Salidas (uds.)', data: datSal,
                          borderColor:'#ff7043', backgroundColor:'rgba(255,112,67,.2)',
                          tension:0.4, fill:true, pointBackgroundColor:'#ff7043', pointRadius:5, borderWidth:2 }
                    ]
                },
                options: { responsive:true, maintainAspectRatio:false, legend:{display:false},
                    scales:{
                        xAxes:[{ ticks:{fontColor:'rgba(255,255,255,.6)'}, gridLines:{color:'rgba(255,255,255,.08)'} }],
                        yAxes:[{ ticks:{fontColor:'rgba(255,255,255,.6)', beginAtZero:true,
                                        callback: v => v + ' uds.'}, gridLines:{color:'rgba(255,255,255,.08)'} }]
                    }
                }
            });

            // ── Más vendidos por UNIDADES ─────────────────
            const masLab  = data.masVendidos.map(p => truncar(p.nombreProducto, 20));
            const masData = data.masVendidos.map(p => parseInt(p.unidades));

            if (grafMas) grafMas.destroy();
            if (masLab.length) {
                grafMas = new Chart($('#graficaMasVendidos')[0].getContext('2d'), {
                    type: 'horizontalBar',
                    data: {
                        labels: masLab,
                        datasets: [{
                            label: 'Unidades vendidas', data: masData,
                            backgroundColor: masData.map((_, i) => `rgba(232,130,12,${0.9 - i * 0.1})`)
                        }]
                    },
                    options: { responsive:true, maintainAspectRatio:false, legend:{display:false},
                        scales:{ xAxes:[{ ticks:{ beginAtZero:true, stepSize:1,
                                                   callback: v => v + ' uds.' } }] } }
                });
            }

            // ── Menos vendidos por UNIDADES ───────────────
            const menosLab  = data.menosVendidos.map(p => truncar(p.nombreProducto, 20));
            const menosData = data.menosVendidos.map(p => parseInt(p.unidades));

            if (grafMenos) grafMenos.destroy();
            if (menosLab.length) {
                grafMenos = new Chart($('#graficaMenosVendidos')[0].getContext('2d'), {
                    type: 'horizontalBar',
                    data: {
                        labels: menosLab,
                        datasets: [{
                            label: 'Unidades vendidas', data: menosData,
                            backgroundColor: menosData.map((_, i) => `rgba(220,53,69,${0.85 - i * 0.1})`)
                        }]
                    },
                    options: { responsive:true, maintainAspectRatio:false, legend:{display:false},
                        scales:{ xAxes:[{ ticks:{ beginAtZero:true, stepSize:1,
                                                   callback: v => v + ' uds.' } }] } }
                });
            }

        }, 'json').fail(() => {
            $('.chart-loading').hide();
            console.error('Error cargando datos del dashboard');
        });
    }

    $('#filtros-periodo .btn-periodo').on('click', function() {
        $('#filtros-periodo .btn-periodo').removeClass('active');
        $(this).addClass('active');
        actualizarGraficas($(this).data('periodo'));
    });

    actualizarGraficas('semana');
});
</script>
</body>
</html>