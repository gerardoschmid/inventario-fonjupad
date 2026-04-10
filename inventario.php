<?php
require_once 'config/database.php';
require_once 'controllers/ArticuloController.php';

$controller = new ArticuloController($pdo);
$filtros_disponibles = $controller->obtenerFiltros();

// Función de escape para XSS
function e($texto) {
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

$filtros_aplicados = [
    'sede' => $_GET['sede'] ?? '',
    'ubicacion' => $_GET['ubicacion'] ?? '',
    'color' => $_GET['color'] ?? '',
    'forma' => $_GET['forma'] ?? '',
];

$inventario = $controller->listarInventario($filtros_aplicados);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta Avanzada de Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            .table {
                width: 100% !important;
                border-collapse: collapse;
            }
            .table th, .table td {
                border: 1px solid #ddd !important;
                padding: 8px !important;
            }
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid mt-4">
        <div class="card shadow no-print mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Filtros de Búsqueda</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="inventario.php" class="row g-3">
                    <div class="col-md-3">
                        <label for="sede" class="form-label">Sede</label>
                        <select name="sede" id="sede" class="form-select" onchange="this.form.submit()">
                            <option value="">Todas las Sedes</option>
                            <?php foreach ($filtros_disponibles['sedes'] as $sede): ?>
                                <option value="<?= e($sede) ?>" <?= ($filtros_aplicados['sede'] == $sede) ? 'selected' : '' ?>><?= e($sede) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="ubicacion" class="form-label">Ubicación</label>
                        <select name="ubicacion" id="ubicacion" class="form-select" onchange="this.form.submit()">
                            <option value="">Todas las Ubicaciones</option>
                            <?php foreach ($filtros_disponibles['ubicaciones'] as $ubicacion): ?>
                                <option value="<?= e($ubicacion) ?>" <?= ($filtros_aplicados['ubicacion'] == $ubicacion) ? 'selected' : '' ?>><?= e($ubicacion) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="color" class="form-label">Color</label>
                        <select name="color" id="color" class="form-select" onchange="this.form.submit()">
                            <option value="">Cualquier Color</option>
                            <?php foreach ($filtros_disponibles['colores'] as $color): ?>
                                <option value="<?= e($color) ?>" <?= ($filtros_aplicados['color'] == $color) ? 'selected' : '' ?>><?= e($color) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="forma" class="form-label">Forma</label>
                        <select name="forma" id="forma" class="form-select" onchange="this.form.submit()">
                            <option value="">Cualquier Forma</option>
                            <?php foreach ($filtros_disponibles['formas'] as $forma): ?>
                                <option value="<?= e($forma) ?>" <?= ($filtros_aplicados['forma'] == $forma) ? 'selected' : '' ?>><?= e($forma) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="btn-group w-100">
                            <a href="inventario.php" class="btn btn-secondary">Limpiar</a>
                            <button type="button" class="btn btn-success" onclick="window.print()">Imprimir Reporte</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Resultados del Inventario</h5>
                <span class="badge bg-info text-dark">Total ítems: <?= count($inventario) ?></span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Marca</th>
                                <th>Color</th>
                                <th>Forma</th>
                                <th class="text-center">Total Cant.</th>
                                <th>Detalle por Ubicación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($inventario)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No se encontraron artículos con los filtros seleccionados.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($inventario as $item): ?>
                                    <tr>
                                        <td class="fw-bold text-primary"><?= e($item['codigo_interno']) ?></td>
                                        <td><?= e($item['nombre']) ?></td>
                                        <td><?= e($item['marca']) ?: '-' ?></td>
                                        <td><span class="badge bg-secondary"><?= e($item['color']) ?: 'N/A' ?></span></td>
                                        <td><?= e($item['forma']) ?: '-' ?></td>
                                        <td class="text-center fw-bold fs-5"><?= e($item['total_cantidad']) ?></td>
                                        <td class="small text-muted"><?= $item['detalle_ubicacion'] // Nota: Este campo es seguro ya que se genera internamente con GROUP_CONCAT y nombres de DB sanitizados ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
