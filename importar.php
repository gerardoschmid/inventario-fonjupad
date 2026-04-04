<?php
require_once 'config/database.php';
require_once 'controllers/ImportarController.php';

$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_csv'])) {
    $archivo = $_FILES['archivo_csv'];

    if ($archivo['error'] === UPLOAD_ERR_OK) {
        $ruta_temporal = $archivo['tmp_name'];
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);

        if (strtolower($extension) === 'csv') {
            $controller = new ImportarController($pdo);
            $resultado = $controller->importarCSV($ruta_temporal, $_POST['id_sede'] ?? 1);

            if (isset($resultado['success'])) {
                $mensaje = $resultado['success'];
                $tipo_mensaje = 'success';
            } else {
                $mensaje = $resultado['error'] ?? 'Error desconocido al importar.';
                $tipo_mensaje = 'danger';
            }
        } else {
            $mensaje = 'Por favor, sube un archivo con extensión .csv';
            $tipo_mensaje = 'warning';
        }
    } else {
        $mensaje = 'Error al subir el archivo al servidor.';
        $tipo_mensaje = 'danger';
    }
}

// Obtener sedes para el select
$sedes = $pdo->query("SELECT id_sede, nombre_sede FROM sedes")->fetchAll();

include_once 'views/layout/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 overflow-hidden">
                <div class="card-header bg-primary text-white py-4 text-center">
                    <h4 class="fw-bold mb-1"><i class="fas fa-file-upload me-2"></i> Actualización Masiva</h4>
                    <p class="mb-0 small opacity-75">Actualice cantidades y artículos mediante archivo CSV</p>
                </div>
                <div class="card-body p-4">

                    <?php if ($mensaje): ?>
                        <div class="alert alert-<?= $tipo_mensaje ?> alert-dismissible fade show border-0 shadow-sm" role="alert">
                            <i class="fas <?= $tipo_mensaje == 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle' ?> me-2"></i>
                            <?= $mensaje ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="alert alert-info border-0 shadow-sm mb-4 small">
                        <h6 class="fw-bold mb-1"><i class="fas fa-info-circle me-1"></i> Instrucciones:</h6>
                        <ul class="mb-0 ps-3">
                            <li>El archivo debe estar delimitado por punto y coma (<b>;</b>).</li>
                            <li>La columna clave es <b>CODIGO INTERNO</b> (segunda columna).</li>
                            <li>El sistema actualizará las cantidades si el código ya existe o creará uno nuevo.</li>
                        </ul>
                    </div>

                    <form action="importar.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label for="id_sede" class="form-label fw-semibold small text-muted">Sede de destino:</label>
                            <select name="id_sede" id="id_sede" class="form-select border-0 bg-light shadow-sm py-2">
                                <?php foreach ($sedes as $sede): ?>
                                    <option value="<?= $sede['id_sede'] ?>"><?= e($sede['nombre_sede']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="archivo_csv" class="form-label fw-semibold small text-muted">Seleccione archivo (.csv):</label>
                            <input type="file" name="archivo_csv" id="archivo_csv" class="form-control border-0 bg-light shadow-sm py-2" accept=".csv" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg shadow py-2">
                                <i class="fas fa-sync-alt me-2"></i> Procesar Importación
                            </button>
                            <a href="inventario.php" class="btn btn-link text-muted small py-2">
                                <i class="fas fa-arrow-left me-1"></i> Volver al Inventario
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4 small text-muted">
                <p>¿Problemas con el formato? <a href="#" class="text-primary text-decoration-none">Descargar plantilla de ejemplo</a></p>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/layout/footer.php'; ?>
