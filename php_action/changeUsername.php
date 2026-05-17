<?php

require_once 'core.php';
require_once 'db_connect_pdo.php';

$valid = array('success' => false, 'messages' => array());

if($_POST) {

	$nusername = $_POST['nusername'];
    $userId = $_POST['user_id'];

	try {
        $sql = "UPDATE users SET username = :nusername WHERE user_id = :userId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nusername' => $nusername,
            ':userId' => $userId
        ]);
        $valid['success'] = true;
        $valid['messages'] = "Actualizado correctamente";
    } catch (PDOException $e) {
        $valid['success'] = false;
        $valid['messages'] = "Error al actualizar: " . $e->getMessage();
    }

	echo json_encode($valid);

}
