<?php
/**
 * Genera una tabla compatible con DataTables y AdminLTE.
 * 
 * @param string $titulo      El título que aparecerá en la tarjeta.
 * @param array  $encabezados Array simple con los nombres de las columnas.
 * @param array  $filas       Array bidimensional con los datos de cada celda.
 * @param string $id          ID único para inicializar DataTable (ej: 'example2').
 * @return string             HTML completo de la tabla.
 */
function crearTabla(string $titulo, array $encabezados, array $filas, string $id = 'example2'): string {
    
    // Generamos los encabezados (thead) y pies de tabla (tfoot)
    $headerHtml = "";
    foreach ($encabezados as $columna) {
        $headerHtml .= "<th>$columna</th>";
    }

    // Generamos el cuerpo de la tabla (tbody)
    $bodyHtml = "";
    foreach ($filas as $fila) {
        $bodyHtml .= "<tr>";
        foreach ($fila as $celda) {
            $bodyHtml .= "<td>$celda</td>";
        }
        $bodyHtml .= "</tr>";
    }

// ... dentro de la función crearTabla ...
    return "
    <div class='card card-outline card-primary shadow-sm'>
        <div class='card-header'>
            <h3 class='card-title' style='color: #002D52; font-weight: bold;'>$titulo</h3>
        </div>
        <div class='card-body'>
            <table id='$id' class='table table-bordered table-hover'>
                <thead style='background-color: #f4f6f9; color: #002D52;'>
                    <tr>$headerHtml</tr>
                </thead>
                <tbody>
                    $bodyHtml
                </tbody>
                <!-- Se eliminó el tfoot de aquí para que no se repita al final -->
            </table>
        </div>
    </div>
    ";
}
?>