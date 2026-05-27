<?php
// controllers/ImportarController.php
require_once 'config/database.php';

class ImportarController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function importarCSV($archivo) {
        if (!$archivo || !file_exists($archivo) || !is_readable($archivo)) {
            return ["error" => "Archivo inválido o no seleccionando."];
        }

        $success_count = 0;
        $error_count = 0;
        $errors = [];

        if (($file = fopen($archivo, "r")) !== FALSE) {
            // Saltamos cabecera
            fgetcsv($file, 10000, ";");

            // Preparar consultas PDO
            $stmt_brand = $this->pdo->prepare("SELECT brand_id FROM brands WHERE brand_name = ?");
            $stmt_cat = $this->pdo->prepare("SELECT categories_id FROM categories WHERE categories_name = ?");
            $stmt_insert = $this->pdo->prepare("INSERT INTO product (product_name, codigo_interno, color, brand_id, categories_id, quantity, rate, estado, ubicacion_especifica, active, status)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 1)
                        ON DUPLICATE KEY UPDATE
                        product_name = VALUES(product_name),
                        color = VALUES(color),
                        brand_id = VALUES(brand_id),
                        categories_id = VALUES(categories_id),
                        quantity = VALUES(quantity),
                        rate = VALUES(rate),
                        estado = VALUES(estado),
                        ubicacion_especifica = VALUES(ubicacion_especifica)");

            while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {
                // CSV columns: codigo_interno; nombre; color; cantidad; costo; sede_nombre; categoria_nombre; estado; ubicacion
                $codigo_interno = $column[0];
                $nombre = $column[1];
                $color = $column[2];
                $cantidad = $column[3];
                $costo = $column[4];
                $sede_nombre = $column[5];
                $categoria_nombre = $column[6];
                $estado = $column[7];
                $ubicacion = $column[8];

                // Validar Sede
                $stmt_brand->execute([$sede_nombre]);
                $res_brand = $stmt_brand->fetch();
                if (!$res_brand) {
                    $errors[] = "Sede '$sede_nombre' no encontrada para el código $codigo_interno";
                    $error_count++;
                    continue;
                }
                $sede_id = $res_brand['brand_id'];

                // Validar Categoria
                $stmt_cat->execute([$categoria_nombre]);
                $res_cat = $stmt_cat->fetch();
                if (!$res_cat) {
                    $errors[] = "Categoría '$categoria_nombre' no encontrada para el código $codigo_interno";
                    $error_count++;
                    continue;
                }
                $categories_id = $res_cat['categories_id'];

                try {
                    if ($stmt_insert->execute([$nombre, $codigo_interno, $color, $sede_id, $categories_id, $cantidad, $costo, $estado, $ubicacion])) {
                        $success_count++;
                    } else {
                        $errors[] = "Error al insertar $codigo_interno";
                        $error_count++;
                    }
                } catch (PDOException $e) {
                    $errors[] = "Error al insertar $codigo_interno: " . $e->getMessage();
                    $error_count++;
                }
            }
            fclose($file);
            return [
                "success" => "Importación completada. Éxito: $success_count, Errores: $error_count.",
                "error_count" => $error_count,
                "errors" => $errors
            ];
        }
        return ["error" => "No se pudo abrir el archivo."];
    }
}
