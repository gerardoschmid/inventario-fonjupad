<?php 	

require_once 'core.php';


$valid = array('success' => false, 'messages' => array());

if($_POST) {

	$categoriesId = $_POST['categoriesId'];

	try {
        $sql = "UPDATE categories SET categories_status = 2 WHERE categories_id = :categoriesId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':categoriesId' => $categoriesId]);
        $valid['success'] = true;
        $valid['messages'] = "Eliminado correctamente";
    } catch (PDOException $e) {
        $valid['success'] = false;
        $valid['messages'] = "Error al eliminar: " . $e->getMessage();
    }

	echo json_encode($valid);
 
}
