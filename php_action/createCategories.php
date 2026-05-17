<?php 	

require_once 'core.php';
require_once 'db_connect_pdo.php';

$valid = array('success' => false, 'messages' => array());

if($_POST) {	

	$categoriesName = $_POST['categoriesName'];
    $categoriesEstado = $_POST['categoriesEstado'];

	try {
        $sql = "INSERT INTO categories (categories_name, categories_active, categories_status)
                VALUES (:categoriesName, :categoriesEstado, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':categoriesName' => $categoriesName,
            ':categoriesEstado' => $categoriesEstado
        ]);
        $valid['success'] = true;
        $valid['messages'] = "Agregado correctamente";
    } catch (PDOException $e) {
        $valid['success'] = false;
        $valid['messages'] = "Error al agregar la categoría: " . $e->getMessage();
    }

	echo json_encode($valid);
 
}
