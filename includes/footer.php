</main>

<!-- BottomNavBar (Mobile) -->
<nav class="md:hidden fixed bottom-0 left-0 w-full flex justify-around items-center h-16 px-4 pb-safe bg-surface-container-lowest dark:bg-inverse-surface border-t border-outline-variant dark:border-outline shadow-lg z-50">
	<a href="dashboard.php" class="flex flex-col items-center justify-center text-on-surface-variant dark:text-surface-variant active:scale-90 transition-transform duration-200">
		<span class="material-symbols-outlined">dashboard</span>
		<span class="text-label-md font-label-md">Panel</span>
	</a>
	<a href="brand.php" class="flex flex-col items-center justify-center text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-high dark:hover:bg-surface-variant active:scale-90 transition-transform duration-200">
		<span class="material-symbols-outlined">location_on</span>
		<span class="text-label-md font-label-md">Sedes</span>
	</a>
	<a href="product.php" class="flex flex-col items-center justify-center text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-high dark:hover:bg-surface-variant active:scale-90 transition-transform duration-200">
		<span class="material-symbols-outlined">table_view</span>
		<span class="text-label-md font-label-md">Inventario</span>
	</a>
	<a href="importar_activos.php" class="flex flex-col items-center justify-center text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-high dark:hover:bg-surface-variant active:scale-90 transition-transform duration-200">
		<span class="material-symbols-outlined">upload_file</span>
		<span class="text-label-md font-label-md">Cargador</span>
	</a>
</nav>

	<!-- file input -->
	<script src="assests/plugins/fileinput/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>	
	<script src="assests/plugins/fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>	
	<script src="assests/plugins/fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
	<script src="assests/plugins/fileinput/js/fileinput.min.js"></script>	


	<!-- DataTables -->
	<script src="assests/plugins/datatables/jquery.dataTables.min.js"></script>

	<script>
		// Active state highlighting (simple version)
		$(document).ready(function() {
			var path = window.location.pathname.split("/").pop();
			if (path == '') path = 'index.php';

			// Desktop Nav
			$('nav.hidden.md\\:flex a[href="'+path+'"]').addClass('text-primary dark:text-inverse-primary border-b-2 border-primary').removeClass('text-on-surface-variant dark:text-surface-variant');

			// Mobile Nav
			$('nav.md\\:hidden a[href="'+path+'"]').addClass('text-primary dark:text-inverse-primary font-bold').removeClass('text-on-surface-variant dark:text-surface-variant');
		});
	</script>

</body>
</html>