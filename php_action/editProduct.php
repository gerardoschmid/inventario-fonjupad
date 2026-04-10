<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {
	$productId = $_POST['productId'];
	$productName 		= $_POST['editProductName']; 
    $quantity 			= $_POST['editQuantity'];
    $rate 				= $_POST['editRate'];
    $brandName 			= $_POST['editBrandName'];
    $categoryName 	    = $_POST['editCategoryName'];
    $productStatus 	    = $_POST['editProductStatus'];
    $codigoInterno      = $_POST['editCodigoInterno'];
    $color              = $_POST['editColor'];
    $estadoActivo       = $_POST['editEstadoActivo'];
    $ubicacionEspecifica = $_POST['editUbicacionEspecifica'];

	$stmt = $connect->prepare("UPDATE product SET
        product_name = ?,
        codigo_interno = ?,
        color = ?,
        brand_id = ?,
        categories_id = ?,
        quantity = ?,
        rate = ?,
        estado = ?,
        ubicacion_especifica = ?,
        active = ?,
        status = 1
    WHERE product_id = ?");

    $stmt->bind_param("ssssiddssii", $productName, $codigoInterno, $color, $brandName, $categoryName, $quantity, $rate, $estadoActivo, $ubicacionEspecifica, $productStatus, $productId);

	if($stmt->execute()) {
		$valid['success'] = true;
		$valid['messages'] = "Actualizado exitosamente";
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error al actualizar la información del activo: " . $connect->error;
	}
    $stmt->close();

} // /$_POST
	 
$connect->close();

echo json_encode($valid);
