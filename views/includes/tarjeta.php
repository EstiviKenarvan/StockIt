<?php
function tarjeta(string $color,string $numero,string $texto,string $icono):string{
    $miColor= {
        'amarillo'=>'bg-warning',
        'azul'=>'bg-info',
        'rojo'=>'bg-danger',
        'verde'=>'success'
    };

    $colofinal=$miColor[$color];

    return "
        <div class='col-lg-3 col-6'>
            <!-- small box -->
            <div class='small-box $color'>
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