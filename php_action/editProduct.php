<?php 	

require_once 'core.php';
require_once 'db_connect_pdo.php';

$valid = array('success' => false, 'messages' => array());

if($_POST) {
	$productId = $_POST['productId'];
	$productName 		= $_POST['editProductName']; 
    $quantity 			= $_POST['editQuantity'];
    $rate 				= $_POST['editRate'];
    $brandName 			= $_POST['editBrandName'];
    $categoryName 	    = $_POST['editCategoryName'];
    $productEstado 	    = $_POST['editProductEstado']; // Fixed from JS
    $codigoInterno      = $_POST['editCodigoInterno'];
    $color              = $_POST['editColor'];
    $estadoActivo       = $_POST['editEstadoActivo'];
    $ubicacionEspecifica = $_POST['editUbicacionEspecifica'];

    try {
        $sql = "UPDATE product SET
            product_name = :productName,
            codigo_interno = :codigoInterno,
            color = :color,
            brand_id = :brandName,
            categories_id = :categoryName,
            quantity = :quantity,
            rate = :rate,
            estado = :estadoActivo,
            ubicacion_especifica = :ubicacionEspecifica,
            active = :productEstado,
            status = 1
        WHERE product_id = :productId";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':productName' => $productName,
            ':codigoInterno' => $codigoInterno,
            ':color' => $color,
            ':brandName' => $brandName,
            ':categoryName' => $categoryName,
            ':quantity' => $quantity,
            ':rate' => $rate,
            ':estadoActivo' => $estadoActivo,
            ':ubicacionEspecifica' => $ubicacionEspecifica,
            ':productEstado' => $productEstado,
            ':productId' => $productId
        ]);

        $valid['success'] = true;
        $valid['messages'] = "Actualizado correctamente";
    } catch (PDOException $e) {
        $valid['success'] = false;
        $valid['messages'] = "Error al actualizar la información del activo: " . $e->getMessage();
    }

}

echo json_encode($valid);
