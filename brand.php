<?php
require_once 'php_action/db_connect_pdo.php';
require_once 'includes/header.php';

// Fetch stats for Sede
$stmt = $pdo->prepare("SELECT COUNT(*) FROM brands WHERE brand_status = 1");
$stmt->execute();
$totalSedes = $stmt->fetchColumn();

// For demonstration/context, let's say we have some active vs alert status
// Since the schema might not have an 'alert' status directly, we can count by availability
$stmt = $pdo->prepare("SELECT COUNT(*) FROM brands WHERE brand_active = 1 AND brand_status = 1");
$stmt->execute();
$disponibles = $stmt->fetchColumn();

$enAlerta = $totalSedes - $disponibles;

?>

<!-- Header Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
    <div class="flex items-center gap-2">
        <span class="text-2xl">📍</span>
        <h2 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg text-primary">Gestión de Sedes</h2>
    </div>
    <button class="bg-primary text-on-primary px-6 py-3 rounded-xl flex items-center gap-2 shadow-lg hover:bg-primary-container transition-all group active:scale-95" data-toggle="modal" data-target="#addBrandModel">
        <span class="material-symbols-outlined text-lg">add_location_alt</span>
        <span class="font-label-md text-label-md">+ Añadir Sede</span>
    </button>
</div>

<!-- Bento Grid Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-surface-container-lowest p-6 rounded-xl border-t-4 border-primary shadow-sm hover:shadow-md transition-shadow">
        <p class="text-on-surface-variant text-label-md font-label-md mb-2 uppercase tracking-wider">Sedes Totales</p>
        <h3 class="text-headline-md font-headline-md"><?php echo $totalSedes; ?></h3>
    </div>
    <div class="bg-surface-container-lowest p-6 rounded-xl border-t-4 border-tertiary-fixed-dim shadow-sm hover:shadow-md transition-shadow">
        <p class="text-on-surface-variant text-label-md font-label-md mb-2 uppercase tracking-wider">Disponibles</p>
        <h3 class="text-headline-md font-headline-md"><?php echo $disponibles; ?></h3>
    </div>
    <div class="bg-surface-container-lowest p-6 rounded-xl border-t-4 border-error shadow-sm hover:shadow-md transition-shadow">
        <p class="text-on-surface-variant text-label-md font-label-md mb-2 uppercase tracking-wider">En Alerta / No Disp.</p>
        <h3 class="text-headline-md font-headline-md text-error"><?php echo $enAlerta; ?></h3>
    </div>
</div>

<div class="remove-messages"></div>

<!-- Table Container -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden p-6">
    <table class="w-full text-left border-collapse" id="manageBrandTable">
        <thead>
            <tr class="bg-surface-container-low">
                <th class="px-6 py-4 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Nombre de la Sede</th>
                <th class="px-6 py-4 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Estado</th>
                <th class="px-6 py-4 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider text-right">Opciones</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modals -->
<!-- Add Brand -->
<div class="modal fade" id="addBrandModel" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content rounded-xl overflow-hidden border-none shadow-2xl">
    	<form class="form-horizontal" id="submitBrandForm" action="php_action/createBrand.php" method="POST">
	      <div class="modal-header bg-surface-container-low border-b border-outline-variant px-6 py-4">
	        <button type="button" class="close text-on-surface-variant" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined">add_location_alt</span> Añadir Sede
            </h4>
	      </div>
	      <div class="modal-body px-6 py-8">
	      	<div id="add-brand-messages"></div>
	        <div class="space-y-6">
                <div>
                    <label for="brandName" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-2">Nombre de Sede</label>
                    <input type="text" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="brandName" placeholder="Ej. Barinas Centro" name="brandName" autocomplete="off">
                </div>
                <div>
                    <label for="brandEstado" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-2">Estado</label>
                    <select class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="brandEstado" name="brandEstado">
                        <option value="">~~SELECCIONAR~~</option>
                        <option value="1">Disponible</option>
                        <option value="2">No Disponible</option>
                    </select>
                </div>
            </div>
	      </div>
	      <div class="modal-footer bg-surface-container-low border-t border-outline-variant px-6 py-4">
	        <button type="button" class="px-6 py-2 text-label-md font-label-md text-outline hover:text-primary transition-colors" data-dismiss="modal">Cerrar</button>
	        <button type="submit" class="bg-primary text-on-primary px-8 py-2 rounded-lg font-label-md text-label-md shadow-md hover:bg-primary/90 transition-all active:scale-95" id="createBrandBtn" data-loading-text="Cargando..." autocomplete="off">Guardar Cambios</button>
	      </div>
     	</form>
    </div>
  </div>
</div>

<!-- Edit Brand -->
<div class="modal fade" id="editBrandModel" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content rounded-xl overflow-hidden border-none shadow-2xl">
    	<form class="form-horizontal" id="editBrandForm" action="php_action/editBrand.php" method="POST">
	      <div class="modal-header bg-surface-container-low border-b border-outline-variant px-6 py-4">
	        <button type="button" class="close text-on-surface-variant" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined">edit_location_alt</span> Editar Sede
            </h4>
	      </div>
	      <div class="modal-body px-6 py-8">
	      	<div id="edit-brand-messages"></div>
		<div class="modal-loading div-hide flex justify-center py-10">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary"></div>
			</div>
		    <div class="edit-brand-result space-y-6">
                <div>
                    <label for="editBrandName" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-2">Nombre de Sede</label>
                    <input type="text" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="editBrandName" placeholder="Nombre de la Sede" name="editBrandName" autocomplete="off">
                </div>
                <div>
                    <label for="editBrandEstado" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-2">Estado</label>
                    <select class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="editBrandEstado" name="editBrandEstado">
                        <option value="">~~SELECCIONAR~~</option>
                        <option value="1">Disponible</option>
                        <option value="2">No Disponible</option>
                    </select>
                </div>
		    </div>
	      </div>
	      <div class="modal-footer editBrandFooter bg-surface-container-low border-t border-outline-variant px-6 py-4">
	        <button type="button" class="px-6 py-2 text-label-md font-label-md text-outline hover:text-primary transition-colors" data-dismiss="modal">Cerrar</button>
	        <button type="submit" class="bg-primary text-on-primary px-8 py-2 rounded-lg font-label-md text-label-md shadow-md hover:bg-primary/90 transition-all active:scale-95" id="editBrandBtn" data-loading-text="Cargando..." autocomplete="off">Guardar Cambios</button>
	      </div>
     	</form>
    </div>
  </div>
</div>

<!-- Remove Brand -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeMemberModal">
  <div class="modal-dialog">
    <div class="modal-content rounded-xl overflow-hidden border-none shadow-2xl">
      <div class="modal-header bg-error-container/20 border-b border-outline-variant px-6 py-4">
        <button type="button" class="close text-on-surface-variant" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title font-headline-sm text-headline-sm text-error flex items-center gap-2">
            <span class="material-symbols-outlined">delete_forever</span> Eliminar Sede
        </h4>
      </div>
      <div class="modal-body px-6 py-8 text-center">
        <p class="text-body-lg font-body-lg text-on-surface">¿Realmente desea eliminar esta sede?</p>
        <p class="text-body-md text-outline mt-2">Esta acción no se puede deshacer.</p>
      </div>
      <div class="modal-footer removeBrandFooter bg-surface-container-low border-t border-outline-variant px-6 py-4">
        <button type="button" class="px-6 py-2 text-label-md font-label-md text-outline hover:text-primary transition-colors" data-dismiss="modal">Cancelar</button>
        <button type="button" class="bg-error text-on-error px-8 py-2 rounded-lg font-label-md text-label-md shadow-md hover:bg-error/90 transition-all active:scale-95" id="removeBrandBtn" data-loading-text="Cargando...">Eliminar Sede</button>
      </div>
    </div>
  </div>
</div>

<script src="custom/js/brand.js"></script>

<?php require_once 'includes/footer.php'; ?>
