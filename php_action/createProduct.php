<?php 	

require_once 'core.php';


$valid = array('success' => false, 'messages' => array());

if($_POST) {	

  $productName 		= $_POST['productName'];
  $quantity 			= $_POST['quantity'];
  $rate 				= $_POST['rate'];
  $brandName 			= $_POST['brandName'];
  $categoryName 	    = $_POST['categoryName'];
  $productEstado 	    = $_POST['productEstado']; // Fixed name from JS: productEstado
  $codigoInterno        = $_POST['codigoInterno'];
  $color                = $_POST['color'];
  $estadoActivo         = $_POST['estadoActivo'];
  $ubicacionEspecifica  = $_POST['ubicacionEspecifica'];

	$type = explode('.', $_FILES['productImage']['name']);
	$type = strtolower($type[count($type)-1]);
	$url = '../assests/images/stock/'.uniqid(rand()).'.'.$type;

	if(in_array($type, array('gif', 'jpg', 'jpeg', 'png'))) {
		if(is_uploaded_file($_FILES['productImage']['tmp_name'])) {			
			if(move_uploaded_file($_FILES['productImage']['tmp_name'], $url)) {
				
                try {
                    $sql = "INSERT INTO product (product_name, codigo_interno, color, product_image, brand_id, categories_id, quantity, rate, estado, ubicacion_especifica, active, status)
                            VALUES (:productName, :codigoInterno, :color, :url, :brandName, :categoryName, :quantity, :rate, :estadoActivo, :ubicacionEspecifica, :productEstado, 1)";

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':productName' => $productName,
                        ':codigoInterno' => $codigoInterno,
                        ':color' => $color,
                        ':url' => $url,
                        ':brandName' => $brandName,
                        ':categoryName' => $categoryName,
                        ':quantity' => $quantity,
                        ':rate' => $rate,
                        ':estadoActivo' => $estadoActivo,
                        ':ubicacionEspecifica' => $ubicacionEspecifica,
                        ':productEstado' => $productEstado
                    ]);

                    $valid['success'] = true;
                    $valid['messages'] = "Agregado correctamente";
                } catch (PDOException $e) {
                    $valid['success'] = false;
                    $valid['messages'] = "Error al agregar el activo: " . $e->getMessage();
                }

			}	else {
				$valid['success'] = false;
				$valid['messages'] = "Error al subir la imagen";
			}
		}
	} else {
        $valid['success'] = false;
        $valid['messages'] = "La imagen es obligatoria o el formato no es válido";
    }

	echo json_encode($valid);
 
}
