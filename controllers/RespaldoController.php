<?php

class RespaldoController
{

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // ── Vista principal con historial ─────────────────────────
    public function index()
    {
        $stmt = $this->conexion->query(
            "SELECT * FROM historial_respaldos ORDER BY fecha_hora DESC"
        );
        $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/respaldo.php';
    }

    // ── EXPORTAR ──────────────────────────────────────────────
    public function exportar()
    {
        $fecha   = date('Y-m-d_H-i-s');
        $archivo = "respaldo_stockit_{$fecha}.txt";

        $contenido  = "================================================\n";
        $contenido .= "   RESPALDO DE BASE DE DATOS - STOCKIT\n";
        $contenido .= "   Fecha: " . date('d/m/Y H:i:s') . "\n";
        $contenido .= "================================================\n\n";

        $stmtTablas = $this->conexion->query("SHOW TABLES");
        $tablas     = $stmtTablas->fetchAll(PDO::FETCH_COLUMN);

        $totalTablas  = 0;
        $totalRegistros = 0;
        $errores = 0;

        foreach ($tablas as $tabla) {
            if ($tabla === 'historial_respaldos') continue;

            $contenido .= "================================================\n";
            $contenido .= "  TABLA: " . strtoupper($tabla) . "\n";
            $contenido .= "================================================\n";

            try {
                $stmtData = $this->conexion->query("SELECT * FROM `{$tabla}`");
                $filas    = $stmtData->fetchAll(PDO::FETCH_ASSOC);

                if (empty($filas)) {
                    $contenido .= "  (Sin registros)\n\n";
                    $totalTablas++;
                    continue;
                }

                $encabezados  = array_keys($filas[0]);
                $contenido   .= implode(" | ", $encabezados) . "\n";
                $contenido   .= str_repeat("-", 80) . "\n";

                foreach ($filas as $row) {
                    $valores = [];
                    foreach ($row as $v) {
                        $valores[] = ($v === null) ? "NULL" : $v;
                    }
                    $contenido .= implode(" | ", $valores) . "\n";
                    $totalRegistros++;
                }
                $contenido .= "\n";
                $totalTablas++;
            } catch (Exception $e) {
                $errores++;
            }
        }

        $contenido .= "================================================\n";
        $contenido .= "   FIN DEL RESPALDO\n";
        $contenido .= "================================================\n";

        $bytes  = strlen($contenido);
        $tamano = $bytes < 1024 ? $bytes . ' B' : round($bytes / 1024, 2) . ' KB';

        $usuario = $_SESSION['usuario_nombre'] ?? 'Sistema';

        // Notas del reporte
        $notas = "Respaldo generado manualmente por el usuario.\n";
        $notas .= "Tablas procesadas: {$totalTablas}.\n";
        $notas .= "Total de registros respaldados: {$totalRegistros}.\n";
        if ($errores > 0) {
            $notas .= "Advertencias durante el proceso: {$errores}.";
        } else {
            $notas .= "Generado exitosamente sin errores de consistencia.";
        }

        $stmt = $this->conexion->prepare(
            "INSERT INTO historial_respaldos (operacion, archivo, tamano, usuario, notas)
             VALUES ('EXPORTACION', :archivo, :tamano, :usuario, :notas)"
        );
        $stmt->execute([
            ':archivo' => $archivo,
            ':tamano'  => $tamano,
            ':usuario' => $usuario,
            ':notas'   => $notas,
        ]);

        header('Content-Type: text/plain; charset=utf-8');
        header("Content-Disposition: attachment; filename=\"{$archivo}\"");
        header('Content-Length: ' . $bytes);
        header('Pragma: no-cache');
        echo $contenido;
        exit;
    }

    // ── IMPORTAR ──────────────────────────────────────────────
    public function importar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?menu=respaldo');
            exit;
        }

        if (!isset($_FILES['archivo_respaldo']) || $_FILES['archivo_respaldo']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['respaldo_error'] = 'Error al subir el archivo. Intenta de nuevo.';
            header('Location: index.php?menu=respaldo');
            exit;
        }

        $archivo = $_FILES['archivo_respaldo'];
        $ext     = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));

        if ($ext !== 'txt') {
            $_SESSION['respaldo_error'] = 'Solo se permiten archivos .txt generados por StockIt.';
            header('Location: index.php?menu=respaldo');
            exit;
        }

        $contenido = file_get_contents($archivo['tmp_name']);
        if ($contenido === false) {
            $_SESSION['respaldo_error'] = 'No se pudo leer el archivo.';
            header('Location: index.php?menu=respaldo');
            exit;
        }

        $errores = $this->restaurarDesdeTexto($contenido);

        $bytes  = $archivo['size'];
        $tamano = $bytes < 1024 ? $bytes . ' B' : round($bytes / 1024, 2) . ' KB';

        $usuario = $_SESSION['usuario_nombre'] ?? 'Sistema';

        // Notas del reporte
        $notas = "Importación realizada desde el archivo: {$archivo['name']}.\n";
        if ($errores > 0) {
            $notas .= "Proceso completado con {$errores} advertencia(s).\n";
            $notas .= "Algunas filas pueden no haberse insertado por duplicados o restricciones.";
        } else {
            $notas .= "Restauración completada exitosamente sin errores.";
        }

        $stmt = $this->conexion->prepare(
            "INSERT INTO historial_respaldos (operacion, archivo, tamano, usuario, notas)
             VALUES ('IMPORTACION', :archivo, :tamano, :usuario, :notas)"
        );
        $stmt->execute([
            ':archivo' => $archivo['name'],
            ':tamano'  => $tamano,
            ':usuario' => $usuario,
            ':notas'   => $notas,
        ]);

        if ($errores > 0) {
            $_SESSION['respaldo_error'] = "Restauración completada con {$errores} advertencia(s). Revisa el reporte.";
        } else {
            $_SESSION['respaldo_ok'] = '¡Base de datos restaurada correctamente!';
        }

        header('Location: index.php?menu=respaldo');
        exit;
    }

    // ── Parsear el .txt y restaurar fila por fila ─────────────
    private function restaurarDesdeTexto($contenido)
    {
        $errores = 0;
        $lineas  = explode("\n", $contenido);
        $tablaActual  = null;
        $encabezados  = null;
        $leyendoTabla = false;

        foreach ($lineas as $linea) {
            $linea = rtrim($linea);

            if (str_starts_with($linea, '  TABLA: ')) {
                $tablaActual  = strtolower(trim(str_replace('  TABLA: ', '', $linea)));
                $encabezados  = null;
                $leyendoTabla = false;
                continue;
            }

            if (
                str_starts_with($linea, '===') ||
                str_starts_with($linea, '---') ||
                $linea === '' ||
                str_starts_with($linea, '  (Sin registros)') ||
                str_starts_with($linea, '   RESPALDO') ||
                str_starts_with($linea, '   Fecha') ||
                str_starts_with($linea, '   FIN')
            ) continue;

            if ($tablaActual === null) continue;

            if ($encabezados === null) {
                $encabezados  = array_map('trim', explode(' | ', $linea));
                $leyendoTabla = true;
                continue;
            }

            if ($leyendoTabla) {
                $valores = array_map('trim', explode(' | ', $linea));
                if (count($valores) !== count($encabezados)) continue;

                $cols         = implode(', ', array_map(fn($c) => "`{$c}`", $encabezados));
                $placeholders = implode(', ', array_fill(0, count($encabezados), '?'));

                try {
                    $stmt   = $this->conexion->prepare(
                        "INSERT INTO `{$tablaActual}` ({$cols}) VALUES ({$placeholders})"
                    );
                    $params = array_map(fn($v) => $v === 'NULL' ? null : $v, $valores);
                    $stmt->execute($params);
                } catch (Exception $e) {
                    $errores++;
                }
            }
        }
        return $errores;
    }
}
