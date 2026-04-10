<?php
require_once 'php_action/core.php';

if (isset($_POST['import'])) {
    $filename = $_FILES['file']['tmp_name'];

    if ($_FILES['file']['size'] > 0) {
        $file = fopen($filename, "r");

        // Skip first line (header)
        fgetcsv($file, 10000, ";");

        $success_count = 0;
        $error_count = 0;
        $errors = [];

        // Prepare statements
        $stmt_brand = $connect->prepare("SELECT brand_id FROM brands WHERE brand_name = ?");
        $stmt_cat = $connect->prepare("SELECT categories_id FROM categories WHERE categories_name = ?");

        $stmt_insert = $connect->prepare("INSERT INTO product (product_name, codigo_interno, color, brand_id, categories_id, quantity, rate, estado, ubicacion_especifica, active, status)
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

            // Validate Sede
            $stmt_brand->bind_param("s", $sede_nombre);
            $stmt_brand->execute();
            $res_brand = $stmt_brand->get_result();
            if ($res_brand->num_rows == 0) {
                $errors[] = "Sede '$sede_nombre' no encontrada para el código $codigo_interno";
                $error_count++;
                continue;
            }
            $sede_id = $res_brand->fetch_assoc()['brand_id'];

            // Validate Categoria
            $stmt_cat->bind_param("s", $categoria_nombre);
            $stmt_cat->execute();
            $res_cat = $stmt_cat->get_result();
            if ($res_cat->num_rows == 0) {
                $errors[] = "Categoría '$categoria_nombre' no encontrada para el código $codigo_interno";
                $error_count++;
                continue;
            }
            $categories_id = $res_cat->fetch_assoc()['categories_id'];

            $stmt_insert->bind_param("sssiiidss", $nombre, $codigo_interno, $color, $sede_id, $categories_id, $cantidad, $costo, $estado, $ubicacion);

            if ($stmt_insert->execute()) {
                $success_count++;
            } else {
                $errors[] = "Error al insertar $codigo_interno: " . $connect->error;
                $error_count++;
            }
        }
        fclose($file);
        $stmt_brand->close();
        $stmt_cat->close();
        $stmt_insert->close();

        $msg = "Importación completada. Éxito: $success_count, Errores: $error_count.";
    } else {
        $msg = "Archivo inválido.";
    }
}

require_once 'includes/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
          <li><a href="dashboard.php">Inicio</a></li>
          <li class="active">Importar Activos</li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="page-heading"> <i class="glyphicon glyphicon-import"></i> El Cargador de Barinas</div>
            </div>
            <div class="panel-body">
                <?php if (isset($msg)) { ?>
                    <div class="alert alert-info">
                        <?php echo $msg; ?>
                        <?php if (!empty($errors)) {
                            echo "<ul>";
                            foreach($errors as $e) echo "<li>$e</li>";
                            echo "</ul>";
                        } ?>
                    </div>
                <?php } ?>

                <form class="form-horizontal" action="" method="post" name="upload_excel" enctype="multipart/form-data">
                    <fieldset>
                        <legend>Importar desde CSV (;)</legend>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="filebutton">Seleccionar archivo CSV</label>
                            <div class="col-md-4">
                                <input type="file" name="file" id="file" class="input-large">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="singlebutton">Importar datos</label>
                            <div class="col-md-4">
                                <button type="submit" id="submit" name="import" class="btn btn-primary button-loading" data-loading-text="Cargando...">Subir archivo</button>
                            </div>
                        </div>
                    </fieldset>
                </form>

                <hr>
                <h4>Formato del CSV (con punto y coma):</h4>
                <p><code>codigo_interno;nombre;color;cantidad;costo;sede_nombre;categoria_nombre;estado;ubicacion</code></p>
                <p>Ejemplo:<br><code>BAR-001;Silla Ejecutiva;Negro;10;50.00;Barinas;Mobiliario;Buen Estado;Salón 2</code></p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
