<?php require_once 'php_action/db_connect.php' ?>
<?php require_once 'includes/header.php'; ?>

<!-- Header Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
    <div class="flex items-center gap-2">
        <span class="text-2xl">👤</span>
        <h2 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg text-primary">Gestionar Usuarios</h2>
    </div>
    <button class="bg-primary text-on-primary px-6 py-3 rounded-xl flex items-center gap-2 shadow-lg hover:bg-primary-container transition-colors group active:scale-95" data-toggle="modal" id="addUserModalBtn" data-target="#addUserModal">
        <span class="material-symbols-outlined text-lg">person_add</span>
        <span class="font-label-md text-label-md">+ Añadir Usuario</span>
    </button>
</div>

<div class="remove-messages"></div>

<!-- Table Container -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden p-6">
    <table class="w-full text-left border-collapse" id="manageUserTable">
        <thead>
            <tr class="bg-surface-container-low">
                <th class="px-6 py-4 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Nombre de Usuario</th>
                <th class="px-6 py-4 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider text-right">Opciones</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modals -->
<!-- Add User -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content rounded-xl overflow-hidden border-none shadow-2xl">
    	<form class="form-horizontal" id="submitUserForm" action="php_action/createUser.php" method="POST" enctype="multipart/form-data">
	      <div class="modal-header bg-surface-container-low border-b border-outline-variant px-6 py-4">
	        <button type="button" class="close text-on-surface-variant" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined">person_add</span> Añadir Usuario
            </h4>
	      </div>
	      <div class="modal-body px-6 py-8">
	      	<div id="add-user-messages"></div>
	        <div class="space-y-6">
                <div>
                    <label for="userName" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-2">Nombre de Usuario</label>
                    <input type="text" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="userName" placeholder="Nombre de Usuario" name="userName" autocomplete="off">
                </div>
                <div>
                    <label for="upassword" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-2">Contraseña</label>
                    <input type="password" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="upassword" placeholder="Contraseña" name="upassword" autocomplete="off">
                </div>
                <div>
                    <label for="uemail" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-2">Email</label>
                    <input type="email" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="uemail" placeholder="Email" name="uemail" autocomplete="off">
                </div>
            </div>
	      </div>
	      <div class="modal-footer bg-surface-container-low border-t border-outline-variant px-6 py-4">
	        <button type="button" class="px-6 py-2 text-label-md font-label-md text-outline hover:text-primary transition-colors" data-dismiss="modal">Cerrar</button>
	        <button type="submit" class="bg-primary text-on-primary px-8 py-2 rounded-lg font-label-md text-label-md shadow-md hover:bg-primary/90 transition-all active:scale-95" id="createUserBtn" data-loading-text="Cargando..." autocomplete="off">Guardar Cambios</button>
	      </div>
	</form>
    </div>
  </div>
</div>

<!-- Edit User -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content rounded-xl overflow-hidden border-none shadow-2xl">
	      <div class="modal-header bg-surface-container-low border-b border-outline-variant px-6 py-4">
	        <button type="button" class="close text-on-surface-variant" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined">person_edit</span> Editar Usuario
            </h4>
	      </div>
	      <div class="modal-body px-6 py-8">
		<div class="div-loading flex justify-center py-10">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary"></div>
	      	</div>
	      	<div class="div-result">
                <form class="space-y-6" id="editUserForm" action="php_action/editUser.php" method="POST">
                    <div id="edit-user-messages"></div>
                    <div>
                        <label for="edituserName" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-2">Nombre de Usuario</label>
                        <input type="text" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="edituserName" placeholder="Nombre de Usuario" name="edituserName" autocomplete="off">
                    </div>
                    <div>
                        <label for="editPassword" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-2">Contraseña (Dejar en blanco para mantener actual)</label>
                        <input type="password" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="editPassword" placeholder="Contraseña" name="editPassword" autocomplete="off">
                    </div>
                    <div class="modal-footer editUserFooter bg-surface-container-low border-t border-outline-variant px-6 py-4 -mx-6 -mb-8 mt-6">
                        <button type="button" class="px-6 py-2 text-label-md font-label-md text-outline hover:text-primary transition-colors" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="bg-primary text-on-primary px-8 py-2 rounded-lg font-label-md text-label-md shadow-md hover:bg-primary/90 transition-all active:scale-95" id="editProductBtn" data-loading-text="Cargando...">Guardar Cambios</button>
                    </div>
                </form>
			</div>
	      </div>
    </div>
  </div>
</div>

<!-- Remove User -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeUserModal">
  <div class="modal-dialog">
    <div class="modal-content rounded-xl overflow-hidden border-none shadow-2xl">
      <div class="modal-header bg-error-container/20 border-b border-outline-variant px-6 py-4">
        <button type="button" class="close text-on-surface-variant" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title font-headline-sm text-headline-sm text-error flex items-center gap-2">
            <span class="material-symbols-outlined">person_remove</span> Eliminar Usuario
        </h4>
      </div>
      <div class="modal-body px-6 py-8 text-center">
      	<div class="removeUserMessages"></div>
        <p class="text-body-lg font-body-lg text-on-surface">¿Realmente desea eliminar este usuario?</p>
      </div>
      <div class="modal-footer removeProductFooter bg-surface-container-low border-t border-outline-variant px-6 py-4">
        <button type="button" class="px-6 py-2 text-label-md font-label-md text-outline hover:text-primary transition-colors" data-dismiss="modal">Cancelar</button>
        <button type="button" class="bg-error text-on-error px-8 py-2 rounded-lg font-label-md text-label-md shadow-md hover:bg-error/90 transition-all active:scale-95" id="removeProductBtn" data-loading-text="Cargando...">Eliminar Usuario</button>
      </div>
    </div>
  </div>
</div>

<script src="custom/js/user.js"></script>

<?php require_once 'includes/footer.php'; ?>
