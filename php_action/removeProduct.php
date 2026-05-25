<?php 	

require_once 'core.php';


$valid = array('success' => false, 'messages' => array());

if($_POST) {

	$productId = $_POST['productId'];

	try {
        $sql = "UPDATE product SET status = 2 WHERE product_id = :productId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':productId' => $productId]);
        $valid['success'] = true;
        $valid['messages'] = "Eliminado correctamente";
    } catch (PDOException $e) {
        $valid['success'] = false;
        $valid['messages'] = "Error al eliminar: " . $e->getMessage();
    }

	echo json_encode($valid);
 
}
