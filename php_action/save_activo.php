<?php
require_once 'core.php';
require_once __DIR__ . '/../controllers/ArticuloController.php';

$controller = new ArticuloController($pdo);

if($_POST) {
    $response = $controller->guardar($_POST);
    echo json_encode($response);
}
?>