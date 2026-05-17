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

<!-- Title & Header -->
<div class="mb-lg">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-md">
        <div>
            <span class="text-label-md font-label-md text-secondary uppercase tracking-widest">Módulo de Carga Local</span>
            <h2 class="text-headline-lg font-headline-lg text-primary mt-1">📥 El Cargador</h2>
        </div>
        <div class="flex items-center gap-xs bg-surface-container-high px-3 py-1.5 rounded-full">
            <span class="material-symbols-outlined text-on-surface-variant text-[18px]">info</span>
            <span class="text-label-md font-label-md text-on-surface-variant">Importar desde CSV (;)</span>
        </div>
    </div>
</div>

<?php if (isset($msg)) { ?>
    <div class="mb-lg p-lg rounded-xl <?php echo $error_count > 0 ? 'bg-error-container text-on-error-container' : 'bg-tertiary-container text-on-tertiary-container'; ?> border border-outline-variant shadow-sm">
        <h4 class="font-bold mb-2"><?php echo $msg; ?></h4>
        <?php if (!empty($errors)) {
            echo "<ul class='list-disc pl-5 text-sm space-y-1'>";
            foreach($errors as $e) echo "<li>$e</li>";
            echo "</ul>";
        } ?>
    </div>
<?php } ?>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">
    <!-- Uploader Canvas -->
    <section class="lg:col-span-7 space-y-gutter">
        <form action="" method="post" name="upload_excel" enctype="multipart/form-data" id="importForm">
            <div class="glass-card rounded-xl p-xl border-2 border-dashed border-outline-variant flex flex-col items-center justify-center min-h-[360px] text-center transition-all duration-300 group cursor-pointer hover:shadow-md" id="drop-zone" onclick="document.getElementById('file-input').click()">
                <div class="w-20 h-20 bg-primary-container/10 rounded-full flex items-center justify-center mb-lg transition-transform group-hover:scale-110">
                    <span class="material-symbols-outlined text-primary text-[48px]">upload_file</span>
                </div>
                <h3 class="text-headline-sm font-headline-sm text-primary mb-xs">Arrastra y suelta tu archivo</h3>
                <p class="text-body-md font-body-md text-on-surface-variant mb-xl">Selecciona un archivo CSV delimitado por punto y coma (;)</p>
                <div class="flex flex-col items-center gap-sm">
                    <span class="text-label-md font-label-md text-secondary italic" id="file-name">Ningún archivo seleccionado</span>
                    <input accept=".csv" class="hidden" id="file-input" name="file" type="file"/>
                    <button type="button" class="bg-primary text-on-primary px-lg py-md rounded-lg font-label-md text-label-md uppercase tracking-bold shadow-lg hover:bg-primary/90 active:scale-95 transition-all duration-150">
                        Seleccionar archivo
                    </button>
                </div>
            </div>

            <div class="mt-lg flex justify-center">
                <button type="submit" name="import" id="submit" class="bg-secondary text-white px-xl py-md rounded-lg font-bold shadow-lg hover:bg-secondary/90 active:scale-95 transition-all hidden">
                    Comenzar Importación
                </button>
            </div>
        </form>

        <!-- Status Monitor -->
        <div class="glass-card rounded-xl p-md flex items-center justify-between border-l-4 border-primary">
            <div class="flex items-center gap-md">
                <div class="relative">
                    <div class="w-3 h-3 bg-tertiary-fixed-dim rounded-full animate-pulse"></div>
                    <div class="absolute inset-0 w-3 h-3 bg-tertiary-fixed-dim rounded-full animate-ping opacity-75"></div>
                </div>
                <div>
                    <p class="text-label-md font-label-md text-on-surface">Estado del Sistema</p>
                    <p class="text-body-md font-body-md text-on-surface-variant">Listo para procesar nuevos activos</p>
                </div>
            </div>
            <span class="material-symbols-outlined text-secondary">sync</span>
        </div>
    </section>

    <!-- Guide Panel -->
    <aside class="lg:col-span-5">
        <div class="bg-primary-container text-on-primary-container rounded-xl p-lg shadow-xl relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-on-primary-container/5 rounded-full"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-sm mb-lg">
                    <span class="material-symbols-outlined text-[20px]">menu_book</span>
                    <h4 class="text-headline-sm font-headline-sm">Guía: Formato del CSV</h4>
                </div>
                <p class="text-body-md font-body-md text-on-primary-container/80 mb-md">
                    Para una importación exitosa, el archivo debe seguir estrictamente este orden de columnas y utilizar el separador <strong class="text-on-primary font-bold">punto y coma (;)</strong>.
                </p>
                <div class="bg-black/20 rounded-lg p-md mb-lg border border-white/10 font-mono text-[13px] leading-relaxed break-all select-all hover:bg-black/30 transition-colors">
                    <code class="text-tertiary-fixed-dim">codigo_interno;nombre;color;cantidad;costo;sede_nombre;categoria_nombre;estado;ubicacion</code>
                </div>
                <div class="space-y-md">
                    <div class="flex gap-md">
                        <div class="mt-1 w-6 h-6 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                            <span class="text-label-md font-label-md text-white">1</span>
                        </div>
                        <p class="text-body-md font-body-md">Evita usar caracteres especiales en los nombres de las sedes o categorías.</p>
                    </div>
                    <div class="flex gap-md">
                        <div class="mt-1 w-6 h-6 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                            <span class="text-label-md font-label-md text-white">2</span>
                        </div>
                        <p class="text-body-md font-body-md">El campo 'cantidad' debe ser numérico entero.</p>
                    </div>
                </div>
            </div>
        </div>
    </aside>
</div>

<script>
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('file-input');
    const fileNameDisplay = document.getElementById('file-name');
    const submitBtn = document.getElementById('submit');

    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            const file = this.files[0];
            if (file.name.endsWith('.csv')) {
                fileNameDisplay.textContent = `Archivo seleccionado: ${file.name}`;
                fileNameDisplay.classList.remove('text-secondary');
                fileNameDisplay.classList.add('text-primary', 'font-bold');
                submitBtn.classList.remove('hidden');
            } else {
                alert('Por favor selecciona un archivo .csv válido');
                this.value = '';
                fileNameDisplay.textContent = "Ningún archivo seleccionado";
                submitBtn.classList.add('hidden');
            }
        }
    });

    // Drag and drop logic
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, e => {
            e.preventDefault();
            e.stopPropagation();
        }, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dropZone.classList.add('bg-surface-container-high'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dropZone.classList.remove('bg-surface-container-high'), false);
    });

    dropZone.addEventListener('drop', e => {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        // Trigger change event
        fileInput.dispatchEvent(new Event('change'));
    }, false);
</script>

<?php require_once 'includes/footer.php'; ?>
