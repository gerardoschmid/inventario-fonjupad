<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	

  $productName 		= $_POST['productName'];
  $quantity 			= $_POST['quantity'];
  $rate 				= $_POST['rate'];
  $brandName 			= $_POST['brandName'];
  $categoryName 	    = $_POST['categoryName'];
  $productStatus 	    = $_POST['productStatus'];
  $codigoInterno        = $_POST['codigoInterno'];
  $color                = $_POST['color'];
  $estadoActivo         = $_POST['estadoActivo'];
  $ubicacionEspecifica  = $_POST['ubicacionEspecifica'];

	$type = explode('.', $_FILES['productImage']['name']);
	$type = $type[count($type)-1];		
	$url = '../assests/images/stock/'.uniqid(rand()).'.'.$type;
	if(in_array($type, array('gif', 'jpg', 'jpeg', 'png', 'JPG', 'GIF', 'JPEG', 'PNG'))) {
		if(is_uploaded_file($_FILES['productImage']['tmp_name'])) {			
			if(move_uploaded_file($_FILES['productImage']['tmp_name'], $url)) {
				
				$stmt = $connect->prepare("INSERT INTO product (product_name, codigo_interno, color, product_image, brand_id, categories_id, quantity, rate, estado, ubicacion_especifica, active, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
				$stmt->bind_param("ssssiiddssi", $productName, $codigoInterno, $color, $url, $brandName, $categoryName, $quantity, $rate, $estadoActivo, $ubicacionEspecifica, $productStatus);

				if($stmt->execute()) {
					$valid['success'] = true;
					$valid['messages'] = "Agregado exitosamente";
				} else {
					$valid['success'] = false;
					$valid['messages'] = "Error al agregar el activo: " . $connect->error;
				}
                $stmt->close();

			}	else {
				$valid['success'] = false;
				$valid['messages'] = "Error al subir la imagen";
			}	// /else	
		} // if
	} else {
        $valid['success'] = false;
        $valid['messages'] = "La imagen es obligatoria o el formato no es válido";
    }

	$connect->close();

	echo json_encode($valid);
 
} // /if $_POST