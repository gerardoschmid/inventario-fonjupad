<?php 
require_once 'php_action/core.php';
require_once 'controllers/AuthController.php';

$authController = new AuthController($pdo);

if(isset($_SESSION['userId'])) {
	header('location:'.$store_url.'dashboard.php');		
    exit();
}

$errors = array();

if($_POST) {		
	$username = $_POST['username'] ?? '';
	$password = $_POST['password'] ?? '';

    $result = $authController->login($username, $password);

    if ($result['success']) {
        header('location:'.$store_url.'dashboard.php');
        exit();
    } else {
        $errors = $result['errors'];
    }
}
?>

<!DOCTYPE html>
<html class="light" lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Acceso - CERMOPA</title>

	<!-- Tailwind CSS -->
	<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
	<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

	<script id="tailwind-config">
		tailwind.config = {
			theme: {
				extend: {
					"colors": {
						"primary": "#1a237e",
						"primary-container": "#131b2e",
						"background": "#f7f9fb",
						"surface": "#ffffff",
						"outline": "#76777d",
						"outline-variant": "#c6c6cd",
                        "error": "#ba1a1a",
                        "on-error-container": "#93000a",
                        "error-container": "#ffdad6"
					},
					"fontFamily": {
						"sans": ["Hanken Grotesk", "sans-serif"]
					}
				},
			},
		}
	</script>

	<style type="text/tailwindcss">
		body { font-family: 'Hanken Grotesk', sans-serif; }
	</style>
</head>
<body class="bg-background min-h-screen flex items-center justify-center p-4">
	<div class="w-full max-w-md animate-in fade-in zoom-in-95 duration-500">
		<div class="bg-surface rounded-2xl shadow-2xl border border-outline-variant overflow-hidden">
            <!-- Header -->
			<div class="bg-primary p-8 text-center relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                        <span class="material-symbols-outlined text-white text-4xl">inventory_2</span>
                    </div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">CERMOPA</h1>
                    <p class="text-white/70 text-sm mt-1">Gestión de Activos e Inventario</p>
                </div>
			</div>

			<div class="p-8">
                <!-- Messages -->
                <?php if($errors) { ?>
                    <div class="mb-6 space-y-2">
                        <?php foreach ($errors as $key => $value) { ?>
                            <div class="flex items-center gap-2 p-3 bg-error-container text-on-error-container rounded-lg text-sm border border-error/20 animate-in slide-in-from-left-2">
                                <span class="material-symbols-outlined text-sm">error</span>
                                <span><?php echo e($value); ?></span>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>

                <!-- Login Form -->
                <form action="<?php echo e($_SERVER['PHP_SELF']) ?>" method="post" id="loginForm" class="space-y-5">
                    <div>
                        <label for="username" class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">Nombre de Usuario</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">person</span>
                            <input type="text" class="w-full pl-10 pr-4 py-3 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all" id="username" name="username" placeholder="Ingresa tu usuario" autocomplete="off" />
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold text-outline uppercase tracking-wider mb-2">Contraseña</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">lock</span>
                            <input type="password" class="w-full pl-10 pr-4 py-3 bg-background border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all" id="password" name="password" placeholder="••••••••" autocomplete="off" />
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-bold shadow-lg hover:bg-primary-container active:scale-[0.98] transition-all flex items-center justify-center gap-2 mt-8 group">
                        <span>Iniciar Sesión</span>
                        <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </button>
                </form>
			</div>

            <!-- Footer -->
            <div class="p-6 bg-background border-t border-outline-variant text-center">
                <p class="text-xs text-outline">© <?php echo date('Y'); ?> CERMOPA C.A. - Todos los derechos reservados</p>
            </div>
		</div>

        <p class="text-center mt-8 text-outline text-sm">
            ¿Necesitas ayuda? <a href="#" class="text-primary font-bold hover:underline">Contactar soporte</a>
        </p>
	</div>
</body>
</html>
