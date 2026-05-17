<?php
require_once 'core.php';
require_once 'db_connect_pdo.php';

$catalogos = array(
    'marcas' => array(),
    'categorias' => array(),
    'colores' => array(),
    'ubicaciones' => array(),
    'estados' => array()
);

try {
    $catalogos['marcas'] = $pdo->query("SELECT brand_id as id, brand_name as nombre FROM brands WHERE brand_status = 1 AND brand_active = 1")->fetchAll();
    $catalogos['categorias'] = $pdo->query("SELECT categories_id as id, categories_name as nombre FROM categories WHERE categories_status = 1 AND categories_active = 1")->fetchAll();
    $catalogos['colores'] = $pdo->query("SELECT id_color as id, nombre_color as nombre FROM colores")->fetchAll();
    $catalogos['ubicaciones'] = $pdo->query("SELECT id_ubicacion as id, nombre_ubicacion as nombre FROM ubicaciones")->fetchAll();
    $catalogos['estados'] = $pdo->query("SELECT id_estado as id, nombre_estado as nombre FROM estados")->fetchAll();
} catch (PDOException $e) {
    // Handle error
}

echo json_encode($catalogos);
?>