<?php 	

require_once 'core.php';


$valid = array('success' => false, 'messages' => array());

if($_POST) {	

	$username = $_POST['userName'];
    $password = md5($_POST['upassword']);
    $email = $_POST['uemail'];

	try {
        $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':password' => $password,
            ':email' => $email
        ]);
        $valid['success'] = true;
        $valid['messages'] = "Agregado correctamente";
    } catch (PDOException $e) {
        $valid['success'] = false;
        $valid['messages'] = "Error al agregar usuario: " . $e->getMessage();
    }

	echo json_encode($valid);
 
}
