<?php
// controllers/ImportarController.php
require_once 'config/database.php';

class ImportarController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function importarCSV($archivo, $id_sede = 1) {
        if (!file_exists($archivo) || !is_readable($archivo)) {
            return ["error" => "El archivo no existe o no se puede leer."];
        }

        $contador_nuevos = 0;

        if (($gestor = fopen($archivo, "r")) !== FALSE) {
            // Saltamos la cabecera
            fgetcsv($gestor, 0, ";");

            while (($datos = fgetcsv($gestor, 0, ";")) !== FALSE) {
                if (empty($datos[1])) continue; // Salta si no hay código interno

                // Normalización de datos con mb_strtoupper para acentos y caracteres especiales
                $codigo_interno = mb_strtoupper(trim($datos[1]), 'UTF-8');
                $nombre         = mb_strtoupper(trim($datos[2]), 'UTF-8');
                $marca          = mb_strtoupper(trim($datos[3]), 'UTF-8');
                $color          = mb_strtoupper(trim($datos[6]), 'UTF-8');
                $cantidad       = (int)$datos[7];
                $ubicacion_nom  = mb_strtoupper(trim($datos[8]), 'UTF-8');
                $estado         = mb_strtoupper(trim($datos[9]), 'UTF-8');

                // Nota: El CSV original no tiene "forma", pero el sistema lo requiere como filtro.
                // Si el CSV tuviera más columnas, mapearíamos 'forma' aquí (ej: $datos[16]).
                $forma = '';

                try {
                    $this->pdo->beginTransaction();

                    // 1. Insertar o Actualizar ARTICULO
                    $sqlArt = "INSERT INTO articulos (codigo_interno, nombre, marca, color, forma)
                               VALUES (?, ?, ?, ?, ?)
                               ON DUPLICATE KEY UPDATE
                               nombre = VALUES(nombre),
                               marca = VALUES(marca),
                               color = VALUES(color),
                               forma = VALUES(forma)";
                    $stmtArt = $this->pdo->prepare($sqlArt);
                    $stmtArt->execute([$codigo_interno, $nombre, $marca, $color, $forma]);

                    // Obtener ID del artículo
                    $stmtGetId = $this->pdo->prepare("SELECT id_articulo FROM articulos WHERE codigo_interno = ?");
                    $stmtGetId->execute([$codigo_interno]);
                    $id_articulo = $stmtGetId->fetchColumn();

                    // 2. Gestionar UBICACIÓN (verificar si existe para la sede)
                    $stmtUbi = $this->pdo->prepare("SELECT id_ubicacion FROM ubicaciones WHERE nombre_ubicacion = ? AND id_sede = ?");
                    $stmtUbi->execute([$ubicacion_nom, $id_sede]);
                    $id_ubicacion = $stmtUbi->fetchColumn();

                    if (!$id_ubicacion) {
                        $sqlInsUbi = "INSERT INTO ubicaciones (nombre_ubicacion, id_sede) VALUES (?, ?)";
                        $this->pdo->prepare($sqlInsUbi)->execute([$ubicacion_nom, $id_sede]);
                        $id_ubicacion = $this->pdo->lastInsertId();
                    }

                    // 3. Gestionar EXISTENCIAS
                    $sqlExist = "INSERT INTO inventario_existencias (id_articulo, id_ubicacion, cantidad_actual, estado_conservacion)
                                 VALUES (?, ?, ?, ?)
                                 ON DUPLICATE KEY UPDATE
                                 cantidad_actual = VALUES(cantidad_actual),
                                 estado_conservacion = VALUES(estado_conservacion)";
                    $this->pdo->prepare($sqlExist)->execute([$id_articulo, $id_ubicacion, $cantidad, $estado]);

                    $this->pdo->commit();
                    $contador_nuevos++;
                } catch (Exception $e) {
                    $this->pdo->rollBack();
                    error_log("Error importando código $codigo_interno: " . $e->getMessage());
                }
            }
            fclose($gestor);
            return ["success" => "Proceso finalizado. Filas procesadas: $contador_nuevos"];
        }
        return ["error" => "No se pudo abrir el archivo."];
    }
}
