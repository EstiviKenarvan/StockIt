<?php
function tarjeta(string $color,string $numero,string $texto,string $icono):string{
    $miColor= [
        'amarillo'=>'bg-warning',
        'azul'=>'bg-info',
        'rojo'=>'bg-danger',
        'verde'=>'bg-success',
        'blanco'=>'bg-white'
    ];
    $ioncono= [
        'persona'=>'ion ion-person-add',
        'estadisticas'=>'ion ion-stats-bars',
        'bolsa'=>'ion ion-bag',
        'grafico'=>'ion ion-pie-graph',
        'calendario'=>'ion ion-calendar',
        'check'=>'ion ion-checkmark'
    ];  

    $colofinal=$miColor[$color];
    $icono=$ioncono[$icono];
    return "
        <div class='col-lg-3 col-6'>
            <!-- small box -->
            <div class='small-box $colofinal'>
            <a href='#' class='small-box-footer'> </a>
              <div class='inner'>
                <h3>$numero</h3>

                <p>$texto</p>
              </div>
              <div class='icon'>
                <i class='$icono'></i>
              </div>
            </div>
          </div>
    ";
}
?>