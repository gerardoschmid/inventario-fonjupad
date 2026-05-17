<?php require_once 'php_action/db_connect.php' ?>
<?php require_once 'includes/header.php'; ?>

<!-- Header Section -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-md mb-8">
    <div>
        <nav class="flex items-center text-label-md font-label-md text-on-surface-variant mb-base space-x-2">
            <span>Activos</span>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-primary">Inventario</span>
        </nav>
        <h2 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg text-on-surface">📦 Inventario de Activos</h2>
    </div>
    <button class="bg-primary text-on-primary flex items-center justify-center gap-2 px-6 py-3 rounded-xl hover:opacity-90 active:scale-95 transition-transform shadow-md" data-toggle="modal" id="addProductModalBtn" data-target="#addProductModal">
        <span class="material-symbols-outlined text-sm">add</span>
        <span class="font-bold text-body-md font-body-md">+ Añadir Activo</span>
    </button>
</div>

<div class="remove-messages"></div>

<!-- Table Container -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden p-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="manageProductTable">
            <thead>
                <tr class="bg-surface-container-low">
                    <th class="px-md py-4 text-label-md font-label-md text-outline uppercase tracking-wider">Imagen</th>
                    <th class="px-md py-4 text-label-md font-label-md text-outline uppercase tracking-wider">Código</th>
                    <th class="px-md py-4 text-label-md font-label-md text-outline uppercase tracking-wider">Activo</th>
                    <th class="px-md py-4 text-label-md font-label-md text-outline uppercase tracking-wider">Sede</th>
                    <th class="px-md py-4 text-label-md font-label-md text-outline uppercase tracking-wider">Tipo</th>
                    <th class="px-md py-4 text-label-md font-label-md text-outline uppercase tracking-wider">Color</th>
                    <th class="px-md py-4 text-label-md font-label-md text-outline uppercase tracking-wider">Ubicación</th>
                    <th class="px-md py-4 text-label-md font-label-md text-outline uppercase tracking-wider">Estado</th>
                    <th class="px-md py-4 text-label-md font-label-md text-outline uppercase tracking-wider">Cant.</th>
                    <th class="px-md py-4 text-label-md font-label-md text-outline uppercase tracking-wider text-right">Opciones</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content rounded-xl overflow-hidden border-none shadow-2xl">
	<form class="form-horizontal" id="submitProductForm" action="php_action/save_activo.php" method="POST" enctype="multipart/form-data">
	      <div class="modal-header bg-surface-container-low border-b border-outline-variant px-6 py-4">
	        <button type="button" class="close text-on-surface-variant" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined">add_box</span> Añadir Activo
            </h4>
	      </div>
	      <div class="modal-body px-6 py-8" style="max-height: calc(100vh - 200px); overflow-y: auto;">
	      	<div id="add-product-messages"></div>
	        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Image Upload -->
                <div class="md:col-span-2 flex flex-col items-center p-6 bg-surface-container-low rounded-xl border-2 border-dashed border-outline-variant">
                    <label for="productImage" class="text-label-md font-label-md text-on-surface-variant uppercase mb-4 text-center w-full">Imagen del Activo</label>
                    <div id="kv-avatar-errors-1" class="w-full" style="display:none;"></div>
                    <div class="kv-avatar">
                        <input type="file" id="productImage" name="productImage" class="file-loading">
                    </div>
                </div>

                <!-- Basic Info -->
                <div class="space-y-4">
                    <div>
                        <label for="codigoInterno" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-1">Código Interno</label>
                        <input type="text" class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="codigoInterno" placeholder="Código Patrimonial" name="codigoInterno" autocomplete="off">
                    </div>
                    <div>
                        <label for="productName" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-1">Nombre del Activo</label>
                        <input type="text" class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="productName" placeholder="Nombre del Activo" name="productName" autocomplete="off">
                    </div>
                    <div>
                        <label for="quantity" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-1">Cantidad</label>
                        <input type="text" class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="quantity" placeholder="Cantidad" name="quantity" autocomplete="off">
                    </div>
                    <div>
                        <label for="rate" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-1">Valor/Costo</label>
                        <input type="text" class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="rate" placeholder="Valor unitario" name="rate" autocomplete="off">
                    </div>
                </div>

                <!-- Selects -->
                <div class="space-y-4">
                    <div>
                        <label for="brandName" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-1">Sede</label>
                        <select class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="brandName" name="brandName">
                            <option value="">~~SELECCIONAR~~</option>
                        </select>
                    </div>
                    <div>
                        <label for="categoryName" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-1">Tipo de Activo</label>
                        <select class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="categoryName" name="categoryName">
                            <option value="">~~SELECCIONAR~~</option>
                        </select>
                    </div>
                    <div>
                        <label for="color" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-1">Color</label>
                        <select class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="color" name="color">
                            <option value="">~~SELECCIONAR~~</option>
                        </select>
                    </div>
                    <div>
                        <label for="estadoActivo" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-1">Estado Físico</label>
                        <select class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="estadoActivo" name="estadoActivo">
                            <option value="">~~SELECCIONAR~~</option>
                        </select>
                    </div>
                </div>

                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="ubicacionEspecifica" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-1">Ubicación</label>
                        <select class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="ubicacionEspecifica" name="ubicacionEspecifica">
                            <option value="">~~SELECCIONAR~~</option>
                        </select>
                    </div>
                    <div>
                        <label for="productEstado" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-1">Visibilidad</label>
                        <select class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="productEstado" name="productEstado">
                            <option value="">~~SELECCIONAR~~</option>
                            <option value="1">Disponible</option>
                            <option value="2">No Disponible</option>
                        </select>
                    </div>
                </div>
            </div>
	      </div>
	      <div class="modal-footer bg-surface-container-low border-t border-outline-variant px-6 py-4">
	        <button type="button" class="px-6 py-2 text-label-md font-label-md text-outline hover:text-primary transition-colors" data-dismiss="modal">Cerrar</button>
	        <button type="submit" class="bg-primary text-on-primary px-8 py-2 rounded-lg font-label-md text-label-md shadow-md hover:bg-primary/90 transition-all active:scale-95" id="createProductBtn" data-loading-text="Cargando..." autocomplete="off">Guardar Cambios</button>
	      </div>
	</form>
    </div>
  </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content rounded-xl overflow-hidden border-none shadow-2xl">
	      <div class="modal-header bg-surface-container-low border-b border-outline-variant px-6 py-4">
	        <button type="button" class="close text-on-surface-variant" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined">edit</span> Editar Activo
            </h4>
	      </div>
	      <div class="modal-body px-6 py-8" style="max-height: calc(100vh - 200px); overflow-y: auto;">
		<div class="div-loading flex justify-center py-10">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary"></div>
	      	</div>
	      	<div class="div-result">
				  <!-- Nav tabs -->
				  <ul class="nav nav-tabs border-b border-outline-variant flex gap-6 mb-8" role="tablist">
				    <li role="presentation" class="active">
                        <a href="#photo" aria-controls="home" role="tab" data-toggle="tab" class="pb-2 text-label-md font-label-md uppercase tracking-wider text-outline hover:text-primary transition-colors">Foto</a>
                    </li>
				    <li role="presentation">
                        <a href="#productInfo" aria-controls="profile" role="tab" data-toggle="tab" class="pb-2 text-label-md font-label-md uppercase tracking-wider text-outline hover:text-primary transition-colors">Información del Activo</a>
                    </li>
				  </ul>

				  <!-- Tab panes -->
				  <div class="tab-content">
				    <div role="tabpanel" class="tab-pane active" id="photo">
					<form action="php_action/editProductImage.php" method="POST" id="updateProductImageForm" class="space-y-8" enctype="multipart/form-data">
					    <div id="edit-productPhoto-messages"></div>
                            <div class="flex flex-col md:flex-row gap-8 items-center justify-center p-6 bg-surface-container-low rounded-xl">
                                <div class="text-center">
                                    <label class="block text-label-md font-label-md text-on-surface-variant uppercase mb-4">Imagen Actual</label>
                                    <img src="" id="getProductImage" class="w-48 h-48 object-cover rounded-xl border border-outline-variant shadow-sm" />
                                </div>
                                <div class="flex-grow w-full max-w-md">
                                    <label class="block text-label-md font-label-md text-on-surface-variant uppercase mb-4">Nueva Foto</label>
                                    <input type="file" id="editProductImage" name="editProductImage" class="file-loading">
                                </div>
                            </div>
                            <div class="flex justify-end gap-4 pt-4 border-t border-outline-variant">
                                <button type="button" class="px-6 py-2 text-label-md font-label-md text-outline hover:text-primary transition-colors" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="bg-primary text-on-primary px-8 py-2 rounded-lg font-label-md text-label-md shadow-md hover:bg-primary/90 transition-all active:scale-95" id="editProductImageBtn" data-loading-text="Cargando...">Guardar Foto</button>
                            </div>
				        </form>
				    </div>

				    <div role="tabpanel" class="tab-pane" id="productInfo">
					    <form class="space-y-6" id="editProductForm" action="php_action/save_activo.php" method="POST">
					    <div id="edit-product-messages"></div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="editCodigoInterno" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-1">Código Interno</label>
                                    <input type="text" class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="editCodigoInterno" name="editCodigoInterno" autocomplete="off">
                                </div>
                                <div>
                                    <label for="editProductName" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-1">Nombre del Activo</label>
                                    <input type="text" class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="editProductName" name="editProductName" autocomplete="off">
                                </div>
                                <div>
                                    <label for="editQuantity" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-1">Cantidad</label>
                                    <input type="text" class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="editQuantity" name="editQuantity" autocomplete="off">
                                </div>
                                <div>
                                    <label for="editRate" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-1">Valor/Costo</label>
                                    <input type="text" class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="editRate" name="editRate" autocomplete="off">
                                </div>
                                <div>
                                    <label for="editBrandName" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-1">Sede</label>
                                    <select class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="editBrandName" name="editBrandName">
                                        <option value="">~~SELECCIONAR~~</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="editCategoryName" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-1">Tipo de Activo</label>
                                    <select class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="editCategoryName" name="editCategoryName">
                                        <option value="">~~SELECCIONAR~~</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="editColor" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-1">Color</label>
                                    <select class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="editColor" name="editColor">
                                        <option value="">~~SELECCIONAR~~</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="editEstadoActivo" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-1">Estado Físico</label>
                                    <select class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="editEstadoActivo" name="editEstadoActivo">
                                        <option value="">~~SELECCIONAR~~</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="editUbicacionEspecifica" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-1">Ubicación</label>
                                    <select class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="editUbicacionEspecifica" name="editUbicacionEspecifica">
                                        <option value="">~~SELECCIONAR~~</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="editProductEstado" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-1">Visibilidad</label>
                                    <select class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="editProductEstado" name="editProductEstado">
                                        <option value="">~~SELECCIONAR~~</option>
                                        <option value="1">Disponible</option>
                                        <option value="2">No Disponible</option>
                                    </select>
                                </div>
                            </div>
			                <div class="flex justify-end gap-4 pt-4 border-t border-outline-variant">
				                <button type="button" class="px-6 py-2 text-label-md font-label-md text-outline hover:text-primary transition-colors" data-dismiss="modal">Cerrar</button>
				                <button type="submit" class="bg-primary text-on-primary px-8 py-2 rounded-lg font-label-md text-label-md shadow-md hover:bg-primary/90 transition-all active:scale-95" id="editProductBtn" data-loading-text="Cargando...">Guardar Cambios</button>
				            </div>
			        </form>
				    </div>    
				  </div>
				</div>
	      </div>
    </div>
  </div>
</div>

<!-- Remove Product Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeProductModal">
  <div class="modal-dialog">
    <div class="modal-content rounded-xl overflow-hidden border-none shadow-2xl">
      <div class="modal-header bg-error-container/20 border-b border-outline-variant px-6 py-4">
        <button type="button" class="close text-on-surface-variant" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title font-headline-sm text-headline-sm text-error flex items-center gap-2">
            <span class="material-symbols-outlined">delete_forever</span> Eliminar Activo
        </h4>
      </div>
      <div class="modal-body px-6 py-8 text-center">
      	<div class="removeProductMessages"></div>
        <p class="text-body-lg font-body-lg text-on-surface">¿Realmente desea eliminar este activo?</p>
        <p class="text-body-md text-outline mt-2">Esta acción no se puede deshacer.</p>
      </div>
      <div class="modal-footer removeProductFooter bg-surface-container-low border-t border-outline-variant px-6 py-4">
        <button type="button" class="px-6 py-2 text-label-md font-label-md text-outline hover:text-primary transition-colors" data-dismiss="modal">Cancelar</button>
        <button type="button" class="bg-error text-on-error px-8 py-2 rounded-lg font-label-md text-label-md shadow-md hover:bg-error/90 transition-all active:scale-95" id="removeProductBtn" data-loading-text="Cargando...">Eliminar Activo</button>
      </div>
    </div>
  </div>
</div>

<script src="custom/js/product.js"></script>

<?php require_once 'includes/footer.php'; ?>
