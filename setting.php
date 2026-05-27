<?php
require_once 'includes/header.php';
?>

<?php 
$user_id = $_SESSION['userId'];

// Refactorización: Uso de PDO y Sentencias Preparadas para prevenir SQL Injection
$sql = "SELECT * FROM users WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':user_id' => $user_id]);
$result = $stmt->fetch();
?>

<!-- Header Section -->
<div class="mb-lg">
    <div class="flex items-center gap-2">
        <span class="text-2xl">⚙️</span>
        <h2 class="text-headline-lg font-headline-lg text-primary">Configuración de Usuario</h2>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Change Username -->
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-8">
        <form action="php_action/changeUsername.php" method="post" id="changeUsernameForm" class="space-y-6">
            <h4 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2 mb-6">
                <span class="material-symbols-outlined">person</span> Cambiar Nombre de Usuario
            </h4>

            <div class="changeUsenrameMessages"></div>

            <div>
                <label for="username" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-2">Usuario</label>
                <input type="text" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="username" name="username" placeholder="Nuevo Usuario" value="<?php echo e($result['username']); ?>"/>
            </div>

            <div class="pt-4">
                <input type="hidden" name="user_id" id="user_id" value="<?php echo $result['user_id'] ?>" />
                <button type="submit" class="w-full bg-primary text-on-primary px-8 py-3 rounded-lg font-bold shadow-md hover:bg-primary/90 transition-all active:scale-95" id="changeUsernameBtn" data-loading-text="Cargando...">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    <!-- Change Password -->
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-8">
        <form action="php_action/changePassword.php" method="post" id="changePasswordForm" class="space-y-6">
            <h4 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2 mb-6">
                <span class="material-symbols-outlined">lock</span> Cambiar Contraseña
            </h4>

            <div class="changePasswordMessages"></div>

            <div>
                <label for="password" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-2">Contraseña Actual</label>
                <input type="password" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="password" name="password" placeholder="Contraseña Actual">
            </div>

            <div>
                <label for="npassword" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-2">Nueva Contraseña</label>
                <input type="password" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="npassword" name="npassword" placeholder="Nueva Contraseña">
            </div>

            <div>
                <label for="cpassword" class="block text-label-md font-label-md text-on-surface-variant uppercase mb-2">Confirmar Nueva Contraseña</label>
                <input type="password" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary outline-none transition-all" id="cpassword" name="cpassword" placeholder="Confirmar Contraseña">
            </div>

            <div class="pt-4">
                <input type="hidden" name="user_id" id="user_id" value="<?php echo $result['user_id'] ?>" />
                <button type="submit" class="w-full bg-primary text-on-primary px-8 py-3 rounded-lg font-bold shadow-md hover:bg-primary/90 transition-all active:scale-95">
                    Actualizar Contraseña
                </button>
            </div>
        </form>
    </div>
</div>

<script src="custom/js/setting.js"></script>
<?php require_once 'includes/footer.php'; ?>
