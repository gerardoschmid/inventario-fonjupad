<?php 	

require_once 'core.php';
require_once 'db_connect_pdo.php';

$valid = array('success' => false, 'messages' => array());

if($_POST) {
	$productId = $_POST['productId'];

	$type = explode('.', $_FILES['editProductImage']['name']);
	$type = strtolower($type[count($type)-1]);
	$url = '../assests/images/stock/'.uniqid(rand()).'.'.$type;

	if(in_array($type, array('gif', 'jpg', 'jpeg', 'png'))) {
		if(is_uploaded_file($_FILES['editProductImage']['tmp_name'])) {			
			if(move_uploaded_file($_FILES['editProductImage']['tmp_name'], $url)) {

                try {
                    $sql = "UPDATE product SET product_image = :url WHERE product_id = :productId";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':url' => $url, ':productId' => $productId]);

                    $valid['success'] = true;
                    $valid['messages'] = "Actualizado correctamente";
                } catch (PDOException $e) {
                    $valid['success'] = false;
                    $valid['messages'] = "Error al actualizar: " . $e->getMessage();
                }

			}	else {
				$valid['success'] = false;
				$valid['messages'] = "Error al subir la imagen";
			}
		}
	} else {
        $valid['success'] = false;
        $valid['messages'] = "Formato no válido";
    }

	echo json_encode($valid);
}
