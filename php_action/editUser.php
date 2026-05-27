<?php 	

require_once 'core.php';


$valid = array('success' => false, 'messages' => array());

if($_POST) {

	$username = $_POST['editUserName'];
    $email = $_POST['editUemail'];
    $userId = $_POST['userId'];

	try {
        $sql = "UPDATE users SET username = :username, email = :email WHERE user_id = :userId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':userId' => $userId
        ]);
        $valid['success'] = true;
        $valid['messages'] = "Actualizado correctamente";
    } catch (PDOException $e) {
        $valid['success'] = false;
        $valid['messages'] = "Error al actualizar usuario: " . $e->getMessage();
    }

	echo json_encode($valid);
 
}
