<?php
require_once 'config/database.php';
require_once 'controllers/ArticuloController.php';

// Limpiar cualquier salida anterior para que el CSV se descargue correctamente
if (ob_get_level()) { ob_end_clean(); }

$controller = new ArticuloController($pdo);

// Obtenemos los mismos filtros que en la vista
$filtros_aplicados = [
    'sede' => $_GET['sede'] ?? '',
    'ubicacion' => $_GET['ubicacion'] ?? '',
    'color' => $_GET['color'] ?? '',
    'forma' => $_GET['forma'] ?? '',
];

$inventario = $controller->listarInventario($filtros_aplicados);

// Nombre del archivo con la fecha actual
$filename = "Inventario_CERMOPA_" . date('Ymd_His') . ".csv";

// Configuración de cabeceras para descarga de archivo
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');

// Añadir BOM para que Excel reconozca correctamente los caracteres con tildes y eñes
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Cabeceras del CSV (delimitado por punto y coma para compatibilidad directa con Excel en español)
fputcsv($output, ['Código Interno', 'Nombre de Artículo', 'Marca', 'Color', 'Forma', 'Cantidad Total', 'Ubicaciones Detalladas'], ';');

// Datos
foreach ($inventario as $item) {
    // Reemplazamos los saltos de línea HTML por un espacio para el CSV
    $detalle_limpio = str_replace('<br>', ' | ', $item['detalle_ubicacion']);

    fputcsv($output, [
        $item['codigo_interno'],
        $item['nombre'],
        $item['marca'],
        $item['color'],
        $item['forma'],
        $item['total_cantidad'],
        $detalle_limpio
    ], ';');
}

fclose($output);
exit();
