<?php
require_once 'config/database.php';
require_once 'controllers/ArticuloController.php';

$controller = new ArticuloController($pdo);
$filtros_disponibles = $controller->obtenerFiltros();

// Función de escape para XSS
if (!function_exists('e')) {
    function e($texto) {
        return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$filtros_aplicados = [
    'sede' => $_GET['sede'] ?? '',
    'ubicacion' => $_GET['ubicacion'] ?? '',
    'color' => $_GET['color'] ?? '',
    'forma' => $_GET['forma'] ?? '',
];

$inventario = $controller->listarInventario($filtros_aplicados);

require_once 'includes/header.php';
?>

<!-- Header Section -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-md mb-8 no-print">
    <div>
        <nav class="flex items-center text-label-md font-label-md text-on-surface-variant mb-base space-x-2">
            <span>Reportes</span>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-primary">Inventario</span>
        </nav>
        <h2 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg text-on-surface">📋 Consulta de Inventario</h2>
    </div>
    <div class="flex gap-sm">
        <a href="inventario.php" class="bg-surface-container-high text-on-surface px-6 py-3 rounded-xl hover:bg-surface-variant transition-all active:scale-95 shadow-sm font-bold">
            Limpiar Filtros
        </a>
        <button onclick="window.print()" class="bg-primary text-on-primary px-6 py-3 rounded-xl hover:opacity-90 transition-all active:scale-95 shadow-md font-bold flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">print</span> Imprimir Reporte
        </button>
    </div>
</div>

<!-- Filters Panel -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-6 mb-8 no-print">
    <form method="GET" action="inventario.php" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div>
            <label for="sede" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-2">Sede</label>
            <select name="sede" id="sede" class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" onchange="this.form.submit()">
                <option value="">Todas las Sedes</option>
                <?php foreach ($filtros_disponibles['sedes'] as $sede): ?>
                    <option value="<?= e($sede) ?>" <?= ($filtros_aplicados['sede'] == $sede) ? 'selected' : '' ?>><?= e($sede) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="ubicacion" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-2">Ubicación</label>
            <select name="ubicacion" id="ubicacion" class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" onchange="this.form.submit()">
                <option value="">Todas las Ubicaciones</option>
                <?php foreach ($filtros_disponibles['ubicaciones'] as $ubicacion): ?>
                    <option value="<?= e($ubicacion) ?>" <?= ($filtros_aplicados['ubicacion'] == $ubicacion) ? 'selected' : '' ?>><?= e($ubicacion) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="color" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-2">Color</label>
            <select name="color" id="color" class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" onchange="this.form.submit()">
                <option value="">Cualquier Color</option>
                <?php foreach ($filtros_disponibles['colores'] as $color): ?>
                    <option value="<?= e($color) ?>" <?= ($filtros_aplicados['color'] == $color) ? 'selected' : '' ?>><?= e($color) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="forma" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-2">Forma</label>
            <select name="forma" id="forma" class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" onchange="this.form.submit()">
                <option value="">Cualquier Forma</option>
                <?php foreach ($filtros_disponibles['formas'] as $forma): ?>
                    <option value="<?= e($forma) ?>" <?= ($filtros_aplicados['forma'] == $forma) ? 'selected' : '' ?>><?= e($forma) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
</div>

<!-- Results Table -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
    <div class="p-6 border-b border-outline-variant flex justify-between items-center bg-white">
        <h5 class="font-headline-sm text-headline-sm text-primary">Resultados del Inventario</h5>
        <span class="px-4 py-1 bg-primary-container text-on-primary-container rounded-full text-label-md font-label-md">Total ítems: <?= count($inventario) ?></span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low">
                    <th class="px-6 py-4 text-label-md font-label-md text-outline uppercase tracking-wider">Código</th>
                    <th class="px-6 py-4 text-label-md font-label-md text-outline uppercase tracking-wider">Descripción</th>
                    <th class="px-6 py-4 text-label-md font-label-md text-outline uppercase tracking-wider">Marca</th>
                    <th class="px-6 py-4 text-label-md font-label-md text-outline uppercase tracking-wider">Color</th>
                    <th class="px-6 py-4 text-label-md font-label-md text-outline uppercase tracking-wider">Forma</th>
                    <th class="px-6 py-4 text-label-md font-label-md text-outline uppercase tracking-wider text-center">Cant.</th>
                    <th class="px-6 py-4 text-label-md font-label-md text-outline uppercase tracking-wider">Detalle por Ubicación</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                <?php if (empty($inventario)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-on-surface-variant font-body-lg italic">No se encontraron artículos con los filtros seleccionados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($inventario as $item): ?>
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-primary"><?= e($item['codigo_interno']) ?></td>
                            <td class="px-6 py-4 font-body-md"><?= e($item['nombre']) ?></td>
                            <td class="px-6 py-4 font-body-md"><?= e($item['marca']) ?: '-' ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-surface-container-high rounded-full text-xs font-bold"><?= e($item['color']) ?: 'N/A' ?></span>
                            </td>
                            <td class="px-6 py-4 font-body-md"><?= e($item['forma']) ?: '-' ?></td>
                            <td class="px-6 py-4 text-center font-headline-sm text-headline-sm"><?= e($item['total_cantidad']) ?></td>
                            <td class="px-6 py-4 text-sm text-on-surface-variant max-w-xs truncate" title="<?= e($item['detalle_ubicacion']) ?>">
                                <?= e($item['detalle_ubicacion']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    @media print {
        .no-print { display: none !important; }
        body { padding: 0 !important; margin: 0 !important; background: white !important; }
        .canvas-bg { background: none !important; }
        main { padding: 0 !important; max-width: none !important; }
    }
</style>

<?php require_once 'includes/footer.php'; ?>
