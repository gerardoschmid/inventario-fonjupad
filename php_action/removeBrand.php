<?php 	

require_once 'core.php';


$valid = array('success' => false, 'messages' => array());

if($_POST) {

	$brandId = $_POST['brandId'];

	try {
        $sql = "UPDATE brands SET brand_status = 2 WHERE brand_id = :brandId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':brandId' => $brandId]);
        $valid['success'] = true;
        $valid['messages'] = "Eliminado correctamente";
    } catch (PDOException $e) {
        $valid['success'] = false;
        $valid['messages'] = "Error al eliminar: " . $e->getMessage();
    }

	echo json_encode($valid);
 
}
