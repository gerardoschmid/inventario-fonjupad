<?php require_once 'php_action/core.php'; ?>

<!DOCTYPE html>
<html class="light" lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>AssetTrack - CERMOPA</title>

	<!-- Tailwind CSS -->
	<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
	<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

	<script id="tailwind-config">
		tailwind.config = {
			darkMode: "class",
			theme: {
				extend: {
					"colors": {
						"secondary-fixed": "#d5e3fd",
						"on-secondary-fixed": "#0d1c2f",
						"surface-container-highest": "#e0e3e5",
						"surface-variant": "#e0e3e5",
						"on-tertiary": "#ffffff",
						"background": "#f7f9fb",
						"tertiary-fixed-dim": "#4edea3",
						"on-background": "#191c1e",
						"tertiary-fixed": "#6ffbbe",
						"inverse-primary": "#bec6e0",
						"primary": "#000000",
						"surface-bright": "#f7f9fb",
						"primary-container": "#131b2e",
						"surface": "#f7f9fb",
						"surface-container": "#eceef0",
						"secondary": "#515f74",
						"on-primary-fixed": "#131b2e",
						"primary-fixed": "#dae2fd",
						"on-tertiary-container": "#009668",
						"inverse-on-surface": "#eff1f3",
						"surface-dim": "#d8dadc",
						"error-container": "#ffdad6",
						"outline": "#76777d",
						"on-secondary": "#ffffff",
						"on-error-container": "#93000a",
						"on-tertiary-fixed-variant": "#005236",
						"on-primary-fixed-variant": "#3f465c",
						"surface-container-high": "#e6e8ea",
						"on-secondary-fixed-variant": "#3a485c",
						"error": "#ba1a1a",
						"on-tertiary-fixed": "#002113",
						"on-surface-variant": "#45464d",
						"secondary-fixed-dim": "#b9c7e0",
						"tertiary": "#000000",
						"secondary-container": "#d5e3fd",
						"on-surface": "#191c1e",
						"primary-fixed-dim": "#bec6e0",
						"surface-container-low": "#f2f4f6",
						"surface-container-lowest": "#ffffff",
						"on-secondary-container": "#57657b",
						"on-primary-container": "#7c839b",
						"on-primary": "#ffffff",
						"tertiary-container": "#002113",
						"surface-tint": "#565e74",
						"on-error": "#ffffff",
						"outline-variant": "#c6c6cd",
						"inverse-surface": "#2d3133"
					},
					"borderRadius": {
						"DEFAULT": "0.25rem",
						"lg": "0.5rem",
						"xl": "0.75rem",
						"full": "9999px"
					},
					"spacing": {
						"sm": "8px",
						"xl": "40px",
						"margin-mobile": "16px",
						"margin-desktop": "32px",
						"base": "4px",
						"md": "16px",
						"gutter": "16px",
						"lg": "24px",
						"xs": "4px"
					},
					"fontFamily": {
						"headline-lg-mobile": ["Hanken Grotesk"],
						"headline-md": ["Hanken Grotesk"],
						"headline-sm": ["Hanken Grotesk"],
						"body-md": ["Hanken Grotesk"],
						"label-md": ["Hanken Grotesk"],
						"body-lg": ["Hanken Grotesk"],
						"headline-lg": ["Hanken Grotesk"]
					}
				},
			},
		}
	</script>

	<style type="text/tailwindcss">
		body { font-family: 'Hanken Grotesk', sans-serif; -webkit-font-smoothing: antialiased; }
		.material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
		.status-pulse { animation: pulse-green 2s infinite; }
		@keyframes pulse-green {
			0% { box-shadow: 0 0 0 0 rgba(0, 150, 104, 0.7); }
			70% { box-shadow: 0 0 0 10px rgba(0, 150, 104, 0); }
			100% { box-shadow: 0 0 0 0 rgba(0, 150, 104, 0); }
		}
		.bento-grid {
			display: grid;
			grid-template-columns: repeat(12, 1fr);
			gap: 16px;
		}
		.canvas-bg {
			background-color: #f8fafc;
			background-image: radial-gradient(#e2e8f0 0.5px, transparent 0.5px);
			background-size: 24px 24px;
		}
		/* Maintain DataTables compatibility while styling */
		.dataTables_wrapper {
			@apply font-body-md text-on-surface;
		}
		.dataTables_wrapper .dataTables_paginate .paginate_button {
			@apply px-4 py-2 rounded-lg border-none hover:bg-surface-container-high transition-colors !important;
		}
		.dataTables_wrapper .dataTables_paginate .paginate_button.current {
			@apply bg-primary text-white border-none rounded-lg font-bold !important;
		}
		.dataTables_wrapper .dataTables_filter input {
			@apply bg-surface-container-low border border-outline-variant rounded-lg text-body-md py-2 px-4 ml-2 outline-none focus:ring-2 focus:ring-primary transition-all;
		}
		.dataTables_wrapper .dataTables_length select {
			@apply bg-surface-container-low border border-outline-variant rounded-lg text-body-md py-1 px-3 mx-2 focus:ring-2 focus:ring-primary outline-none transition-all;
		}
		.dataTables_wrapper table.dataTable {
			@apply border-collapse border-none my-4 !important;
		}
		.dataTables_wrapper table.dataTable thead th {
			@apply bg-surface-container-low text-label-md font-label-md text-outline uppercase tracking-wider px-6 py-4 border-b border-outline-variant !important;
		}
		.dataTables_wrapper table.dataTable tbody td {
			@apply px-6 py-4 border-b border-outline-variant text-body-md !important;
		}
		.dataTables_wrapper table.dataTable tbody tr:hover {
			@apply bg-surface-container-low transition-colors !important;
		}
		/* DataTables Buttons styling */
		.dt-buttons .dt-button {
			@apply bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 text-body-md font-body-md hover:bg-surface-container-high transition-colors !important;
		}
	</style>

	<!-- bootstrap (Limited use for modals if needed, but Tailwind is preferred) -->
	<link rel="stylesheet" href="assests/bootstrap/css/bootstrap.min.css">

	<!-- custom css -->
	<link rel="stylesheet" href="custom/css/custom.css">

	<!-- DataTables -->
	<link rel="stylesheet" href="assests/plugins/datatables/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

	<!-- file input -->
	<link rel="stylesheet" href="assests/plugins/fileinput/css/fileinput.min.css">

	<!-- jquery -->
	<script src="assests/jquery/jquery.min.js"></script>
	<!-- jquery ui -->
	<link rel="stylesheet" href="assests/jquery-ui/jquery-ui.min.css">
	<script src="assests/jquery-ui/jquery-ui.min.js"></script>

	<!-- bootstrap js -->
	<script src="assests/bootstrap/js/bootstrap.min.js"></script>

	<!-- DataTables Buttons -->
	<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
</head>
<body class="bg-background text-on-background min-h-screen pb-24 md:pb-0 md:pt-16">

<header class="bg-surface-container-lowest dark:bg-inverse-surface border-b border-outline-variant dark:border-outline shadow-sm dark:shadow-none docked full-width top-0 z-50 fixed w-full h-16 flex justify-between items-center px-margin-mobile md:px-margin-desktop">
	<div class="flex items-center gap-md">
		<span class="material-symbols-outlined text-primary dark:text-inverse-primary">inventory_2</span>
		<h1 class="text-headline-md font-headline-md font-bold text-primary dark:text-inverse-primary">AssetTrack</h1>
	</div>
	<!-- Desktop Nav -->
	<nav class="hidden md:flex gap-lg">
		<a id="topNavDashboard" class="text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-low dark:hover:bg-surface-variant transition-colors px-3 py-2 rounded font-bold" href="dashboard.php">Panel</a>

		<?php if(isset($_SESSION['userId']) && $_SESSION['userId']==1) { ?>
			<a id="topNavBrand" class="text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-low dark:hover:bg-surface-variant transition-colors px-3 py-2 rounded font-bold" href="brand.php">Sedes</a>
			<a id="topNavCategories" class="text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-low dark:hover:bg-surface-variant transition-colors px-3 py-2 rounded font-bold" href="categories.php">Categorías</a>
			<a id="topNavProduct" class="text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-low dark:hover:bg-surface-variant transition-colors px-3 py-2 rounded font-bold" href="product.php">Inventario</a>
			<a id="topNavInventario" class="text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-low dark:hover:bg-surface-variant transition-colors px-3 py-2 rounded font-bold" href="inventario.php">Consulta</a>
			<a id="topNavImport" class="text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-low dark:hover:bg-surface-variant transition-colors px-3 py-2 rounded font-bold" href="importar_activos.php">Cargador</a>
		<?php } ?>
	</nav>

	<div class="flex items-center gap-sm">
		<div class="dropdown" id="navSetting">
			<button class="dropdown-toggle flex items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<div class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant overflow-hidden">
					<img src="assests/images/stock/user.png" alt="User Profile" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=Admin&background=131b2e&color=fff'">
				</div>
			</button>
			<ul class="dropdown-menu dropdown-menu-right">
				<?php if(isset($_SESSION['userId']) && $_SESSION['userId']==1) { ?>
					<li id="topNavSetting"><a href="setting.php"> <i class="glyphicon glyphicon-wrench"></i> Configuración</a></li>
					<li id="topNavUser"><a href="user.php"> <i class="glyphicon glyphicon-user"></i> Añadir Usuario</a></li>
				<?php } ?>
				<li id="topNavLogout"><a href="logout.php"> <i class="glyphicon glyphicon-log-out"></i> Salir</a></li>
			</ul>
		</div>
	</div>
</header>

<main class="max-w-7xl mx-auto px-margin-mobile md:px-margin-desktop py-lg canvas-bg min-h-screen">
