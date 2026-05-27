<?php

require_once 'core.php';


$valid = array('success' => false, 'messages' => array());

if($_POST) {

	$password = md5($_POST['password']);
	$npassword = md5($_POST['npassword']);
	$cpassword = md5($_POST['cpassword']);
	$userId = $_POST['user_id'];

	try {
        $sql = "SELECT * FROM users WHERE user_id = :userId AND password = :password";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':userId' => $userId, ':password' => $password]);
        $result = $stmt->fetch();

        if($result) {
            if($npassword == $cpassword) {
                $updateSql = "UPDATE users SET password = :npassword WHERE user_id = :userId";
                $updateStmt = $pdo->prepare($updateSql);
                $updateStmt->execute([':npassword' => $npassword, ':userId' => $userId]);

                $valid['success'] = true;
                $valid['messages'] = "Actualizado correctamente";
            } else {
                $valid['success'] = false;
                $valid['messages'] = "Las contraseñas no coinciden";
            }
        } else {
            $valid['success'] = false;
            $valid['messages'] = "La contraseña actual es incorrecta";
        }
    } catch (PDOException $e) {
        $valid['success'] = false;
        $valid['messages'] = "Error al actualizar: " . $e->getMessage();
    }

	echo json_encode($valid);
}
