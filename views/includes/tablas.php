<?php
function crearTabla(string $titulo, array $encabezados, array $filas, string $id = 'example2'): string {
    
    $headerHtml = "";
    foreach ($encabezados as $columna) {
        $headerHtml .= "<th>$columna</th>";
    }

    $bodyHtml = "";
    foreach ($filas as $fila) {
        $bodyHtml .= "<tr>";
        foreach ($fila as $celda) {
            $bodyHtml .= "<td>$celda</td>";
        }
        $bodyHtml .= "</tr>";
    }

    return "
    <div class='card card-outline card-primary shadow-sm'>
        <div class='card-header'>
            <h3 class='card-title' style='color: #002D52; font-weight: bold;'>$titulo</h3>
        </div>
        <div class='card-body'>
            <table id='$id' class='table table-bordered table-hover w-100'>
                <thead style='background-color: #f4f6f9; color: #002D52;'>
                    <tr>$headerHtml</tr>
                </thead>
                <tbody>
                    $bodyHtml
                </tbody>
            </table>
        </div>
    </div>

    <script>
        (function() {
            function cargarScript(url, callback) {
                var script = document.createElement('script');
                script.src = url;
                script.onload = callback;
                document.head.appendChild(script);
            }

            // Esperamos a que jQuery esté listo
            var checkReady = setInterval(function() {
                if (window.jQuery) {
                    clearInterval(checkReady);
                    
                    // Si DataTables no está cargado, lo traemos de la CDN
                    if (!$.fn.DataTable) {
                        cargarScript('https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js', function() {
                            cargarScript('https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js', inicializar);
                        });
                    } else {
                        inicializar();
                    }
                }
            }, 100);

            function inicializar() {
                if ($.fn.DataTable.isDataTable('#$id')) {
                    $('#$id').DataTable().destroy();
                }
                $('#$id').DataTable({
                    \"paging\": true,
                    \"lengthChange\": true,
                    \"searching\": true,
                    \"ordering\": true,
                    \"info\": true,
                    \"autoWidth\": false,
                    \"responsive\": true,
                    \"language\": {
                        \"url\": \"//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json\"
                    }
                });
            }
        })();
    </script>
    ";
}