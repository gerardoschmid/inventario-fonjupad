<nav class="navbar navbar-expand-lg navbar-dark no-print">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-warehouse me-2"></i> CERMOPA <span class="fw-light small text-white-50 ms-2">Inventario</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'inventario.php') ? 'active' : '' ?>" href="inventario.php">
                        <i class="fas fa-list-ul me-1"></i> Consulta de Inventario
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'importar.php') ? 'active' : '' ?>" href="importar.php">
                        <i class="fas fa-file-import me-1"></i> Actualización Masiva
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
