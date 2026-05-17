<?php require_once 'includes/header.php'; ?>

<!-- Header Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
    <div class="flex items-center gap-2">
        <span class="text-2xl">📁</span>
        <h2 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg text-primary">Gestionar Categorías</h2>
    </div>
    <button class="bg-primary text-on-primary px-6 py-3 rounded-xl flex items-center gap-2 shadow-lg hover:bg-primary-container transition-all group active:scale-95" data-toggle="modal" id="addCategoriesModalBtn" data-target="#addCategoriesModal">
        <span class="material-symbols-outlined text-lg">category</span>
        <span class="font-label-md text-label-md">+ Añadir Categoría</span>
    </button>
</div>

<div class="remove-messages"></div>

<!-- Table Container -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden p-6 animate-in fade-in duration-700 delay-200">
    <table class="w-full text-left border-collapse" id="manageCategoriesTable">
        <thead>
            <tr class="bg-surface-container-low">
                <th class="px-6 py-4 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Nombre de Categoría</th>
                <th class="px-6 py-4 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Estado</th>
                <th class="px-6 py-4 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider text-right">Opciones</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modals -->
<!-- Add Categories -->
<div class="modal fade" id="addCategoriesModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content rounded-xl overflow-hidden border-none shadow-2xl">
    	<form class="form-horizontal" id="submitCategoriesForm" action="php_action/createCategories.php" method="POST">
	      <div class="modal-header bg-surface-container-low border-b border-outline-variant px-6 py-4">
	        <button type="button" class="close text-on-surface-variant" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined">add_box</span> Añadir Categoría
            </h4>
	      </div>
	      <div class="modal-body px-6 py-8">
	      	<div id="add-categories-messages"></div>
	        <div class="space-y-6">
                <div>
                    <label for="categoriesName" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-2">Nombre de Categoría</label>
                    <input type="text" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="categoriesName" placeholder="Ej. Mobiliario" name="categoriesName" autocomplete="off">
                </div>
                <div>
                    <label for="categoriesEstado" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-2">Estado</label>
                    <select class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="categoriesEstado" name="categoriesEstado">
                        <option value="">~~SELECCIONAR~~</option>
                        <option value="1">Disponible</option>
                        <option value="2">No Disponible</option>
                    </select>
                </div>
            </div>
	      </div>
	      <div class="modal-footer bg-surface-container-low border-t border-outline-variant px-6 py-4">
	        <button type="button" class="px-6 py-2 text-label-md font-label-md text-outline hover:text-primary transition-colors" data-dismiss="modal">Cerrar</button>
	        <button type="submit" class="bg-primary text-on-primary px-8 py-2 rounded-lg font-label-md text-label-md shadow-md hover:bg-primary/90 transition-all active:scale-95" id="createCategoriesBtn" data-loading-text="Cargando..." autocomplete="off">Guardar Cambios</button>
	      </div>
	</form>
    </div>
  </div>
</div>

<!-- Edit Categories -->
<div class="modal fade" id="editCategoriesModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content rounded-xl overflow-hidden border-none shadow-2xl">
    	<form class="form-horizontal" id="editCategoriesForm" action="php_action/editCategories.php" method="POST">
	      <div class="modal-header bg-surface-container-low border-b border-outline-variant px-6 py-4">
	        <button type="button" class="close text-on-surface-variant" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined">edit</span> Editar Categoría
            </h4>
	      </div>
	      <div class="modal-body px-6 py-8">
	      	<div id="edit-categories-messages"></div>
		<div class="modal-loading div-hide flex justify-center py-10">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary"></div>
			</div>
		    <div class="edit-categories-result space-y-6">
                <div>
                    <label for="editCategoriesName" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-2">Nombre de Categoría</label>
                    <input type="text" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="editCategoriesName" placeholder="Nombre de Categoría" name="editCategoriesName" autocomplete="off">
                </div>
                <div>
                    <label for="editCategoriesEstado" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-2">Estado</label>
                    <select class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="editCategoriesEstado" name="editCategoriesEstado">
                        <option value="">~~SELECCIONAR~~</option>
                        <option value="1">Disponible</option>
                        <option value="2">No Disponible</option>
                    </select>
                </div>
		    </div>
	      </div>
	      <div class="modal-footer editCategoriesFooter bg-surface-container-low border-t border-outline-variant px-6 py-4">
	        <button type="button" class="px-6 py-2 text-label-md font-label-md text-outline hover:text-primary transition-colors" data-dismiss="modal">Cerrar</button>
	        <button type="submit" class="bg-primary text-on-primary px-8 py-2 rounded-lg font-label-md text-label-md shadow-md hover:bg-primary/90 transition-all active:scale-95" id="editCategoriesBtn" data-loading-text="Cargando..." autocomplete="off">Guardar Cambios</button>
	      </div>
     	</form>
    </div>
  </div>
</div>

<!-- Remove Categories -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeCategoriesModal">
  <div class="modal-dialog">
    <div class="modal-content rounded-xl overflow-hidden border-none shadow-2xl">
      <div class="modal-header bg-error-container/20 border-b border-outline-variant px-6 py-4">
        <button type="button" class="close text-on-surface-variant" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title font-headline-sm text-headline-sm text-error flex items-center gap-2">
            <span class="material-symbols-outlined">delete_forever</span> Eliminar Categoría
        </h4>
      </div>
      <div class="modal-body px-6 py-8 text-center">
        <p class="text-body-lg font-body-lg text-on-surface">¿Realmente desea eliminar esta categoría?</p>
        <p class="text-body-md text-outline mt-2">Esta acción no se puede deshacer.</p>
      </div>
      <div class="modal-footer removeCategoriesFooter bg-surface-container-low border-t border-outline-variant px-6 py-4">
        <button type="button" class="px-6 py-2 text-label-md font-label-md text-outline hover:text-primary transition-colors" data-dismiss="modal">Cancelar</button>
        <button type="button" class="bg-error text-on-error px-8 py-2 rounded-lg font-label-md text-label-md shadow-md hover:bg-error/90 transition-all active:scale-95" id="removeCategoriesBtn" data-loading-text="Cargando...">Eliminar Categoría</button>
      </div>
    </div>
  </div>
</div>

<script src="custom/js/categories.js"></script>

<?php require_once 'includes/footer.php'; ?>
