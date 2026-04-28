<?php 	

require_once 'core.php';
require_once 'db_connect_pdo.php';

// The SQL View vista_inventario is used to fetch inventory data with descriptive names
$sql = "SELECT * FROM vista_inventario WHERE status = 1";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();

    $output = array('data' => array());

    foreach($result as $row) {
        $productId = $row['product_id'];

        $button = '<!-- Single button -->
        <div class="btn-group">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Acción <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
            <li><a type="button" data-toggle="modal" id="editProductModalBtn" data-target="#editProductModal" onclick="editProduct('.$productId.')"> <i class="glyphicon glyphicon-edit"></i> Editar</a></li>
            <li><a type="button" data-toggle="modal" data-target="#removeProductModal" id="removeProductModalBtn" onclick="removeProduct('.$productId.')"> <i class="glyphicon glyphicon-trash"></i> Eliminar</a></li>
          </ul>
        </div>';

        $imageUrl = substr($row['product_image'], 3);
        $productImage = "<img class='img-round' src='".$imageUrl."' style='height:30px; width:50px;'  />";

        $output['data'][] = array(
            // image
            $productImage,
            // codigo interno
            $row['codigo_interno'],
            // product name
            $row['product_name'],
            // brand (Sede)
            $row['brand_name'],
            // category (Tipo de Activo)
            $row['categories_name'],
            // color
            $row['color'],
            // ubicacion
            $row['ubicacion_especifica'],
            // estado
            $row['estado'],
            // quantity
            $row['quantity'],
            // button
            $button
        );
    }

    echo json_encode($output);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
