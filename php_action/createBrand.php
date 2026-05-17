<?php 	

require_once 'core.php';
require_once 'db_connect_pdo.php';

$valid = array('success' => false, 'messages' => array());

if($_POST) {	

	$brandName = $_POST['brandName'];
    $brandEstado = $_POST['brandEstado'];

	try {
        $sql = "INSERT INTO brands (brand_name, brand_active, brand_status) VALUES (:brandName, :brandEstado, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':brandName' => $brandName,
            ':brandEstado' => $brandEstado
        ]);
        $valid['success'] = true;
        $valid['messages'] = "Agregado correctamente";
    } catch (PDOException $e) {
        $valid['success'] = false;
        $valid['messages'] = "Error al agregar la sede: " . $e->getMessage();
    }

	echo json_encode($valid);
 
}
