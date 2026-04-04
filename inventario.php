<?php
require_once 'config/database.php';
require_once 'controllers/ArticuloController.php';

$controller = new ArticuloController($pdo);
$filtros_disponibles = $controller->obtenerFiltros();

$filtros_aplicados = [
    'sede' => $_GET['sede'] ?? '',
    'ubicacion' => $_GET['ubicacion'] ?? '',
    'color' => $_GET['color'] ?? '',
    'forma' => $_GET['forma'] ?? '',
];

$inventario = $controller->listarInventario($filtros_aplicados);

include_once 'views/layout/header.php';
?>

<div class="container-fluid px-4">
    <!-- Título y Acciones -->
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h3 class="fw-bold mb-0 text-dark">Consulta de Inventario de Activos</h3>
        <div class="btn-group">
            <button type="button" class="btn btn-outline-success" onclick="exportarExcel()">
                <i class="fas fa-file-excel me-1"></i> Exportar a Excel
            </button>
            <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                <i class="fas fa-print me-1"></i> Imprimir Reporte
            </button>
        </div>
    </div>

    <div class="row g-4">
        <!-- Panel Lateral de Filtros -->
        <div class="col-lg-3 no-print">
            <div class="card shadow-sm border-0 sticky-top" style="top: 1rem;">
                <div class="card-header border-0 bg-transparent py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-filter me-2"></i> Filtros de Búsqueda</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="inventario.php" id="filtroForm" class="row g-3">
                        <div class="col-12">
                            <label class="form-label small text-muted fw-semibold">Sede</label>
                            <select name="sede" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Todas las Sedes</option>
                                <?php foreach ($filtros_disponibles['sedes'] as $sede): ?>
                                    <option value="<?= e($sede) ?>" <?= ($filtros_aplicados['sede'] == $sede) ? 'selected' : '' ?>><?= e($sede) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-muted fw-semibold">Ubicación</label>
                            <select name="ubicacion" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Todas las Ubicaciones</option>
                                <?php foreach ($filtros_disponibles['ubicaciones'] as $ubicacion): ?>
                                    <option value="<?= e($ubicacion) ?>" <?= ($filtros_aplicados['ubicacion'] == $ubicacion) ? 'selected' : '' ?>><?= e($ubicacion) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-muted fw-semibold">Color</label>
                            <select name="color" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Todos los Colores</option>
                                <?php foreach ($filtros_disponibles['colores'] as $color): ?>
                                    <option value="<?= e($color) ?>" <?= ($filtros_aplicados['color'] == $color) ? 'selected' : '' ?>><?= e($color) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-muted fw-semibold">Forma</label>
                            <select name="forma" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Todas las Formas</option>
                                <?php foreach ($filtros_disponibles['formas'] as $forma): ?>
                                    <option value="<?= e($forma) ?>" <?= ($filtros_aplicados['forma'] == $forma) ? 'selected' : '' ?>><?= e($forma) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 pt-2">
                            <a href="inventario.php" class="btn btn-light btn-sm w-100"><i class="fas fa-undo me-1"></i> Reestablecer</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabla de Resultados -->
        <div class="col-lg-9">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header border-0 bg-transparent py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-box-open me-2"></i> Existencias Disponibles</h6>
                    <div class="no-print w-50">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" id="buscadorRealTime" class="form-control border-start-0 shadow-none" placeholder="Buscador inteligente (cualquier campo)...">
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0" id="tablaInventario">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3">Código</th>
                                    <th>Descripción de Artículo</th>
                                    <th>Marca</th>
                                    <th>Color</th>
                                    <th class="text-center">Cant. Total</th>
                                    <th class="no-print">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($inventario)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fas fa-search-minus fa-3x mb-3 d-block"></i>
                                            No hay registros que coincidan con los filtros.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($inventario as $index => $item): ?>
                                        <tr class="item-row">
                                            <td class="ps-3 fw-bold text-primary"><?= e($item['codigo_interno']) ?></td>
                                            <td><?= e($item['nombre']) ?></td>
                                            <td><?= e($item['marca']) ?: '<span class="text-muted">-</span>' ?></td>
                                            <td><span class="badge bg-light text-dark border badge-custom"><?= e($item['color']) ?: 'N/A' ?></span></td>
                                            <td class="text-center fw-bold fs-6"><?= $item['total_cantidad'] ?></td>
                                            <td class="no-print">
                                                <button class="btn btn-link btn-sm p-0 text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#detalle-<?= $index ?>">
                                                    <i class="fas fa-info-circle me-1"></i> Ver Detalle
                                                </button>
                                            </td>
                                        </tr>
                                        <tr class="collapse no-print bg-light" id="detalle-<?= $index ?>">
                                            <td colspan="6" class="p-3">
                                                <div class="card card-body border-0 shadow-sm p-3 small">
                                                    <h6 class="fw-bold mb-2 small text-uppercase letter-spacing-1">Desglose de Ubicaciones:</h6>
                                                    <div class="row g-2">
                                                        <?php
                                                            $detalles = explode('<br>', $item['detalle_ubicacion']);
                                                            foreach($detalles as $detalle):
                                                        ?>
                                                        <div class="col-md-6 col-lg-4">
                                                            <div class="p-2 border rounded bg-white">
                                                                <i class="fas fa-map-marker-alt text-danger me-2"></i> <?= $detalle ?>
                                                            </div>
                                                        </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 py-3 d-flex justify-content-between align-items-center">
                    <span class="small text-muted">Mostrando <strong><?= count($inventario) ?></strong> tipos de artículos agrupados.</span>
                    <span class="small text-muted no-print"><i class="fas fa-info-circle me-1"></i> Los datos se actualizan automáticamente cada vez que aplicas un filtro.</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Buscador inteligente en tiempo real
document.getElementById('buscadorRealTime').addEventListener('keyup', function() {
    let valor = this.value.toLowerCase().trim();
    let filas = document.querySelectorAll('#tablaInventario tbody .item-row');

    filas.forEach(fila => {
        let texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(valor) ? '' : 'none';

        // Si la fila está oculta, ocultar también su detalle colapsable si estuviera abierto
        if (fila.style.display === 'none') {
            let nextRow = fila.nextElementSibling;
            if (nextRow && nextRow.classList.contains('collapse')) {
                let bsCollapse = bootstrap.Collapse.getInstance(nextRow);
                if (bsCollapse) bsCollapse.hide();
                nextRow.style.display = 'none';
            }
        } else {
            let nextRow = fila.nextElementSibling;
            if (nextRow && nextRow.classList.contains('collapse')) {
                nextRow.style.display = '';
            }
        }
    });
});

function exportarExcel() {
    let params = new URLSearchParams(window.location.search);
    window.location.href = 'exportar.php?' + params.toString();
}
</script>

<?php include_once 'views/layout/footer.php'; ?>
