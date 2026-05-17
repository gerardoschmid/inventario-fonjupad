<?php
require_once 'config/database.php';

// Asegúrate de que el nombre del archivo coincida exactamente (ojo con los espacios)
$archivo = 'inventario_barinas_para_subir.csv';
$id_sede = 1;

$msg = "";
if (isset($_GET['run'])) {
    if (($gestor = fopen($archivo, "r")) !== FALSE) {
        fgetcsv($gestor, 0, ";");

        $success_count = 0;
        while (($datos = fgetcsv($gestor, 0, ";")) !== FALSE) {

            $codigo = trim($datos[1]);
            $nombre = trim($datos[2]);
            $marca  = trim($datos[3]);
            $color  = trim($datos[6]);
            $cant   = (int)$datos[7];
            $ubic   = trim($datos[8]);
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

                // 2. GESTIONAR UBICACIÓN
                $stmtU = $pdo->prepare("SELECT id_ubicacion FROM ubicaciones WHERE nombre_ubicacion = ? AND id_sede = ?");
                $stmtU->execute([$ubic, $id_sede]);
                $id_ubicacion = $stmtU->fetchColumn();

                if (!$id_ubicacion) {
                    $sqlUbi = "INSERT INTO ubicaciones (nombre_ubicacion, id_sede) VALUES (?, ?)";
                    $pdo->prepare($sqlUbi)->execute([$ubic, $id_sede]);
                    $id_ubicacion = $pdo->lastInsertId();
                }

                // 3. Insertar en el inventario real
                $sqlStock = "INSERT INTO inventario_existencias (id_articulo, id_ubicacion, cantidad_actual, estado_conservacion)
                            VALUES (?, ?, ?, ?)";
                $pdo->prepare($sqlStock)->execute([$id_articulo, $id_ubicacion, $cant, $estado]);

                $pdo->commit();
                $success_count++;
            } catch (Exception $e) {
                $pdo->rollBack();
                $msg .= "<div class='text-error'>Error en código $codigo: " . $e->getMessage() . "</div>";
            }
        }
        fclose($gestor);
        $msg = "<div class='bg-tertiary-container text-on-tertiary-container p-lg rounded-xl mb-lg font-bold'>¡Importación de Barinas completada con éxito! Registros: $success_count</div>" . $msg;
    }
}

require_once 'includes/header.php';
?>

<!-- Title & Header -->
<div class="mb-lg">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-md">
        <div>
            <span class="text-label-md font-label-md text-secondary uppercase tracking-widest">Módulo de Carga Masiva</span>
            <h2 class="text-headline-lg font-headline-lg text-primary mt-1">📦 Importador Barinas</h2>
        </div>
        <div class="flex items-center gap-xs bg-surface-container-high px-3 py-1.5 rounded-full">
            <span class="material-symbols-outlined text-on-surface-variant text-[18px]">info</span>
            <span class="text-label-md font-label-md text-on-surface-variant">Archivo: <?= $archivo ?></span>
        </div>
    </div>
</div>

<?= $msg ?>

<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-xl text-center">
    <div class="w-20 h-20 bg-primary-container/10 rounded-full flex items-center justify-center mx-auto mb-lg">
        <span class="material-symbols-outlined text-primary text-[48px]">upload_file</span>
    </div>
    <h3 class="text-headline-sm font-headline-sm text-primary mb-xs">Preparado para importar</h3>
    <p class="text-body-md font-body-md text-on-surface-variant mb-xl">Este proceso leerá el archivo de inventario local y lo cargará en la base de datos central.</p>

    <a href="importar_barinas.php?run=1" class="bg-primary text-on-primary px-lg py-md rounded-lg font-label-md text-label-md uppercase tracking-bold shadow-lg hover:bg-primary/90 active:scale-95 transition-all inline-block">
        Ejecutar Importación Ahora
    </a>
</div>

<?php require_once 'includes/footer.php'; ?>
