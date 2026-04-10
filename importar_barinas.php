<?php
require_once 'config/database.php';

// Asegúrate de que el nombre del archivo coincida exactamente (ojo con los espacios)
$archivo = 'inventario_barinas_para_subir.csv'; 
$id_sede = 1; 

if (($gestor = fopen($archivo, "r")) !== FALSE) {
    fgetcsv($gestor, 0, ";"); 

    while (($datos = fgetcsv($gestor, 0, ";")) !== FALSE) {
        
        $codigo = trim($datos[1]);    
        $nombre = trim($datos[2]);    
        $marca  = trim($datos[3]);    
        $color  = trim($datos[6]);    
        $cant   = (int)$datos[7]; 
        $ubic   = trim($datos[8]); // <-- Agregamos trim aquí para evitar "GIMNASIO " con espacios
        $estado = trim($datos[9]);    

        if(empty($codigo)) continue; 

        try {
            $pdo->beginTransaction();

            // 1. Gestionar el ARTÍCULO
            $sqlArt = "INSERT IGNORE INTO articulos (codigo_interno, nombre, marca, color) 
                       VALUES (?, ?, ?, ?)";
            $stmtArt = $pdo->prepare($sqlArt);
            $stmtArt->execute([$codigo, $nombre, $marca, $color]);
            
            $id_articulo = $pdo->lastInsertId();
            if (!$id_articulo) {
                $stmtB = $pdo->prepare("SELECT id_articulo FROM articulos WHERE codigo_interno = ?");
                $stmtB->execute([$codigo]);
                $id_articulo = $stmtB->fetchColumn();
            }

            // ============================================================
            // 2. GESTIONAR UBICACIÓN (CAMBIO AQUÍ PARA EVITAR DUPLICADOS)
            // ============================================================
            
            // Primero buscamos si la ubicación ya existe para esta sede
            $stmtU = $pdo->prepare("SELECT id_ubicacion FROM ubicaciones WHERE nombre_ubicacion = ? AND id_sede = ?");
            $stmtU->execute([$ubic, $id_sede]);
            $id_ubicacion = $stmtU->fetchColumn();

            // Si no existe (fetchColumn devuelve false), entonces la insertamos
            if (!$id_ubicacion) {
                $sqlUbi = "INSERT INTO ubicaciones (nombre_ubicacion, id_sede) VALUES (?, ?)";
                $pdo->prepare($sqlUbi)->execute([$ubic, $id_sede]);
                $id_ubicacion = $pdo->lastInsertId();
            }

            // ============================================================

            // 3. Insertar en el inventario real
            $sqlStock = "INSERT INTO inventario_existencias (id_articulo, id_ubicacion, cantidad_actual, estado_conservacion) 
                         VALUES (?, ?, ?, ?)";
            $pdo->prepare($sqlStock)->execute([$id_articulo, $id_ubicacion, $cant, $estado]);

            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Error en código $codigo: " . $e->getMessage() . "<br>";
        }
    }
    fclose($gestor);
    echo "<h1>¡Importación de Barinas completada con éxito!</h1>";
}
?>