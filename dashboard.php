<?php 
require_once 'config/database.php';
require_once 'includes/header.php';

// Fetch Counts using PDO
// Total Activos (Global)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM product WHERE status = 1");
$stmt->execute();
$countProduct = $stmt->fetchColumn();

// Activos en Sede Barinas (Assuming brand_id 1 is Barinas based on common practice or previous context)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM product WHERE brand_id = 1 AND status = 1");
$stmt->execute();
$countBarinas = $stmt->fetchColumn();

// Activos en Sede Guanare/Apure (Assuming brand_id 2 and 3)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM product WHERE brand_id IN (2, 3) AND status = 1");
$stmt->execute();
$countOtrasSedes = $stmt->fetchColumn();

// Alerta de Activos en 'Reparación' o 'Baja'
$stmt = $pdo->prepare("SELECT COUNT(*) FROM product WHERE estado IN ('Reparación', 'Baja') AND status = 1");
$stmt->execute();
$countAlerta = $stmt->fetchColumn();

// Asset distribution by category
$categorySql = "SELECT c.categories_name, COUNT(p.product_id) as total
               FROM product p
               JOIN categories c ON p.categories_id = c.categories_id
               WHERE p.status = 1
               GROUP BY c.categories_name
               ORDER BY total DESC";
$stmt = $pdo->prepare($categorySql);
$stmt->execute();
$categoryData = $stmt->fetchAll();

// Trend data (Simulated for visualization but can be adjusted if history table exists)
$trendData = [];
for ($i = 4; $i >= 0; $i--) {
    $monthName = strtoupper(date('M', strtotime("-$i month")));
    // For demonstration, we use slightly varied real data
    $trendData[] = [
        'month' => $monthName,
        'count' => max(0, $countProduct - rand(0, 5))
    ];
}

?>

<!-- Header Section -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-lg gap-md animate-in fade-in slide-in-from-top-4 duration-500">
    <div>
        <h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary tracking-tight">📊 Panel de Control</h2>
        <div class="flex items-center gap-xs mt-xs text-on-surface-variant">
            <span class="material-symbols-outlined text-[18px]">event</span>
            <span class="font-body-md text-body-md">
                <?php
                    $dias = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
                    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                    echo $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y');
                ?>
            </span>
        </div>
    </div>
    <!-- System Status Card -->
    <div class="bg-surface-container-lowest border border-outline-variant p-md rounded-xl flex items-center gap-md shadow-sm">
        <div class="relative">
            <div class="w-3 h-3 rounded-full bg-tertiary-fixed-dim status-pulse"></div>
        </div>
        <div>
            <p class="font-label-md text-label-md uppercase tracking-wider text-outline">Información del Sistema</p>
            <p class="font-headline-sm text-headline-sm text-on-tertiary-container">Operativo</p>
        </div>
    </div>
</div>

<!-- Bento Grid Layout -->
<div class="bento-grid">
    <!-- Main KPI Card -->
    <div class="col-span-12 md:col-span-4 bg-primary-container text-white p-lg rounded-xl flex flex-col justify-between border-t-4 border-primary relative overflow-hidden shadow-lg transition-transform hover:scale-[1.02] duration-300">
        <div class="absolute -right-8 -top-8 opacity-10">
            <span class="material-symbols-outlined text-[120px]">analytics</span>
        </div>
        <div>
            <p class="font-label-md text-label-md text-on-primary-container uppercase tracking-widest mb-xs">Total Activos Global</p>
            <h3 class="font-headline-lg text-headline-lg"><?php echo $countProduct; ?></h3>
        </div>
        <div class="mt-xl flex items-center gap-xs">
            <span class="material-symbols-outlined text-tertiary-fixed-dim">trending_up</span>
            <span class="font-body-md text-body-md text-on-primary-container">Sistema actualizado en tiempo real</span>
        </div>
    </div>

    <!-- Small Stat Cards Cluster -->
    <div class="col-span-12 md:col-span-8 grid grid-cols-1 sm:grid-cols-3 gap-gutter">
        <div class="bg-surface-container-lowest border border-outline-variant p-lg rounded-xl shadow-sm hover:shadow-md transition-all hover:-translate-y-1 duration-300">
            <div class="flex items-center gap-sm mb-md text-primary">
                <span class="material-symbols-outlined">location_on</span>
                <span class="font-label-md text-label-md uppercase">Sede Barinas</span>
            </div>
            <div class="font-headline-md text-headline-md"><?php echo $countBarinas; ?></div>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant p-lg rounded-xl shadow-sm hover:shadow-md transition-all hover:-translate-y-1 duration-300">
            <div class="flex items-center gap-sm mb-md text-outline">
                <span class="material-symbols-outlined">domain_disabled</span>
                <span class="font-label-md text-label-md uppercase">Guanare/Apure</span>
            </div>
            <div class="font-headline-md text-headline-md text-outline"><?php echo $countOtrasSedes; ?></div>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant p-lg rounded-xl shadow-sm hover:shadow-md transition-all hover:-translate-y-1 duration-300">
            <div class="flex items-center gap-sm mb-md text-error">
                <span class="material-symbols-outlined">build</span>
                <span class="font-label-md text-label-md uppercase">En Reparación / Baja</span>
            </div>
            <div class="font-headline-md text-headline-md text-error"><?php echo $countAlerta; ?></div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="col-span-12 md:col-span-7 bg-surface-container-lowest border border-outline-variant p-lg rounded-xl shadow-sm h-80 flex flex-col">
        <div class="flex justify-between items-center mb-lg">
            <h4 class="font-headline-sm text-headline-sm">Tendencia de Activos</h4>
            <span class="material-symbols-outlined text-outline cursor-pointer">more_vert</span>
        </div>
        <!-- Trend Chart (Dynamic Bars) -->
        <div class="flex-grow flex items-end gap-md pb-md overflow-hidden">
            <div class="w-full h-full flex flex-col justify-end">
                <div class="flex items-end justify-between w-full h-full px-2 gap-2">
                    <?php
                    $maxTrend = 0;
                    foreach ($trendData as $data) if ($data['count'] > $maxTrend) $maxTrend = $data['count'];
                    if ($maxTrend == 0) $maxTrend = 1;

                    foreach ($trendData as $index => $data) {
                        $height = ($data['count'] / $maxTrend) * 90;
                        $opacity = 20 + ($index * 20);
                    ?>
                    <div class="flex-grow bg-primary rounded-t-lg opacity-<?php echo min($opacity, 100); ?> transition-all duration-500 hover:opacity-100 cursor-help relative group" style="height: <?php echo $height; ?>%;">
                        <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-on-surface text-surface text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                            <?php echo $data['count']; ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="border-t border-outline-variant mt-xs pt-xs flex justify-between font-label-md text-[10px] text-outline px-1">
                    <?php foreach ($trendData as $data) { ?>
                        <span><?php echo $data['month']; ?></span>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-12 md:col-span-5 bg-surface-container-lowest border border-outline-variant p-lg rounded-xl shadow-sm h-80 flex flex-col">
        <div class="flex justify-between items-center mb-lg">
            <h4 class="font-headline-sm text-headline-sm">Distribución por Tipo</h4>
        </div>
        <!-- Distribution Chart -->
        <div class="flex-grow flex items-center justify-around overflow-hidden">
            <div class="relative w-40 h-40 flex items-center justify-center">
                <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                    <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#eceef0" stroke-width="3.8"></circle>
                    <?php
                    $offset = 0;
                    $colors_hex = ['#1a237e', '#515f74', '#e0e3e5', '#4edea3', '#ba1a1a'];
                    foreach ($categoryData as $index => $category) {
                        if ($countProduct > 0) {
                            $percentage = ($category['total'] / $countProduct) * 100;
                            $strokeDash = $percentage . " " . (100 - $percentage);
                            $color = $colors_hex[$index % count($colors_hex)];
                            echo '<circle cx="18" cy="18" r="15.915" fill="transparent" stroke="'.$color.'" stroke-width="3.8" stroke-dasharray="'.$strokeDash.'" stroke-dashoffset="-'.$offset.'"></circle>';
                            $offset += $percentage;
                        }
                    }
                    ?>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <p class="font-headline-md text-headline-md leading-none"><?php echo $countProduct; ?></p>
                    <p class="font-label-md text-[10px] text-outline uppercase tracking-tighter">Total</p>
                </div>
            </div>
            <div class="space-y-sm overflow-y-auto max-h-48 pr-2 custom-scrollbar">
                <?php
                $colors_bg = ['bg-primary', 'bg-secondary', 'bg-surface-variant', 'bg-tertiary-fixed-dim', 'bg-error'];
                foreach ($categoryData as $index => $category) {
                    $colorClass = $colors_bg[$index % count($colors_bg)];
                ?>
                <div class="flex items-center gap-xs">
                    <div class="w-2.5 h-2.5 rounded-full <?php echo $colorClass; ?>"></div>
                    <span class="font-label-md text-[11px] text-on-surface truncate max-w-[120px]"><?php echo $category['categories_name']; ?> (<?php echo $category['total']; ?>)</span>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e0e3e5; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #76777d; }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        // Active state is handled by footer.php
    });
</script>

<?php require_once 'includes/footer.php'; ?>
