<?php
/**
 * IMPORTANTE: Para que este script funcione, se debe instalar PhpSpreadsheet vía composer:
 * composer require phpoffice/phpspreadsheet
 */

require_once 'php_action/db_connect_pdo.php';
// require 'vendor/autoload.php'; // Descomentar cuando esté instalado composer

use PhpOffice\PhpSpreadsheet\IOFactory;

function importarExcel($archivoPath, $pdo) {
    try {
        $spreadsheet = IOFactory::load($archivoPath);
        $sheet = $spreadsheet->getActivoSheet();
        $data = $sheet->toArray();

        // Asumimos que la primera fila es el encabezado
        // Columnas esperadas: Código, Nombre, Sede (ID), Categoría (ID), Color, Cantidad, Estado, Ubicación
        foreach ($data as $index => $row) {
            if ($index == 0) continue; // Saltar encabezado

            $codigo    = $row[0];
            $nombre    = $row[1];
            $sede_id   = $row[2];
            $cat_id    = $row[3];
            $color     = $row[4];
            $cantidad  = $row[5];
            $estado    = $row[6];
            $ubicacion = $row[7];

            $sql = "INSERT INTO product (product_name, codigo_interno, color, brand_id, categories_id, quantity, estado, ubicacion_especifica, active, status)
                    VALUES (:nombre, :codigo, :color, :sede, :cat, :cant, :est, :ubi, 1, 1)
                    ON DUPLICATE KEY UPDATE
                    product_name = VALUES(product_name),
                    color = VALUES(color),
                    quantity = VALUES(quantity),
                    estado = VALUES(estado),
                    ubicacion_especifica = VALUES(ubicacion_especifica)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':codigo' => $codigo,
                ':color'  => $color,
                ':sede'   => $sede_id,
                ':cat'    => $cat_id,
                ':cant'   => $cantidad,
                ':est'    => $estado,
                ':ubi'    => $ubicacion
            ]);
        }
        return "Importación exitosa";
    } catch (Exception $e) {
        return "Error en la importación: " . $e->getMessage();
    }
}

// Ejemplo de uso:
// if ($_FILES['archivo_excel']['tmp_name']) {
//     echo importarExcel($_FILES['archivo_excel']['tmp_name'], $pdo);
// }
?>
