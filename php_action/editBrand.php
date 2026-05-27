<?php 	

require_once 'core.php';


$valid = array('success' => false, 'messages' => array());

if($_POST) {	

	$brandName = $_POST['editBrandName'];
    $brandEstado = $_POST['editBrandEstado'];
    $brandId = $_POST['brandId'];

	try {
        $sql = "UPDATE brands SET brand_name = :brandName, brand_active = :brandEstado WHERE brand_id = :brandId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':brandName' => $brandName,
            ':brandEstado' => $brandEstado,
            ':brandId' => $brandId
        ]);
        $valid['success'] = true;
        $valid['messages'] = "Actualizado correctamente";
    } catch (PDOException $e) {
        $valid['success'] = false;
        $valid['messages'] = "Error al actualizar la sede: " . $e->getMessage();
    }

	echo json_encode($valid);
 
}
