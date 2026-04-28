<?php 	

require_once 'core.php';
require_once 'db_connect_pdo.php';

$valid = array('success' => false, 'messages' => array());

if($_POST) {

	$userId = $_POST['userId'];

	try {
        $sql = "DELETE FROM users WHERE user_id = :userId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        $valid['success'] = true;
        $valid['messages'] = "Eliminado correctamente";
    } catch (PDOException $e) {
        $valid['success'] = false;
        $valid['messages'] = "Error al eliminar: " . $e->getMessage();
    }

	echo json_encode($valid);
 
}
