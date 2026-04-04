<?php
/**
 * Router Principal - CERMOPA
 * Redirección inteligente al módulo principal o manejo de rutas si se expande el sistema.
 */

require_once 'config/database.php';

// Por ahora, el sistema inicia en el Inventario (Consulta)
// Pero dejamos la estructura lista para un Dashboard futuro
$page = $_GET['view'] ?? 'inventario';

switch ($page) {
    case 'importar':
        include 'importar.php';
        break;
    case 'inventario':
    default:
        include 'inventario.php';
        break;
}
