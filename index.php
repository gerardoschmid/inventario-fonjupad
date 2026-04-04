<?php
// 1. Configuración de errores (Útil en desarrollo)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2. Iniciar Sesión para seguridad
session_start();

// 3. Importar la conexión y el Auth
require_once 'config/database.php';
require_once 'core/Auth.php';

// 4. Capturar la vista solicitada (por defecto 'home')
$view = isset($_GET['view']) ? $_GET['view'] : 'home';

// 5. Verificar si el usuario está logueado (Excepto si va al login)
/*if (!isset($_SESSION['usuario_id']) && $view !== 'login') {
    header("Location: index.php?view=login");
    exit();
}*/

// 6. Lógica de Navegación (Enrutador)
// Cargamos el header común
include 'views/layout/header.php';
include 'views/layout/navbar.php';

echo '<main class="container mt-4">';

// Selección de contenido
switch ($view) {
    case 'home':
        include 'views/home.php';
        break;
        
    case 'manteleria':
        // Aquí podrías llamar a un controlador antes de la vista
        include 'views/articulos/manteleria.php';
        break;

    case 'importar':
        include 'views/importacion/subir_csv.php';
        break;

    case 'login':
        include 'views/login.php';
        break;

    default:
        echo "<h1>404 - Página no encontrada</h1>";
        break;
}

echo '</main>';

// Cargamos el footer común
include 'views/layout/footer.php';