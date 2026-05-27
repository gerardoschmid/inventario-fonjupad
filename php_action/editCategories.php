<?php 	

require_once 'core.php';


$valid = array('success' => false, 'messages' => array());

if($_POST) {	

	$categoriesName = $_POST['editCategoriesName'];
    $categoriesEstado = $_POST['editCategoriesEstado'];
    $categoriesId = $_POST['categoriesId'];

	try {
        $sql = "UPDATE categories SET categories_name = :categoriesName, categories_active = :categoriesEstado WHERE categories_id = :categoriesId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':categoriesName' => $categoriesName,
            ':categoriesEstado' => $categoriesEstado,
            ':categoriesId' => $categoriesId
        ]);
        $valid['success'] = true;
        $valid['messages'] = "Actualizado correctamente";
    } catch (PDOException $e) {
        $valid['success'] = false;
        $valid['messages'] = "Error al actualizar la categoría: " . $e->getMessage();
    }

	echo json_encode($valid);
 
}
