<?php
require_once 'core.php';
require_once 'db_connect_pdo.php';

$output = array('data' => array());

try {
    $sql = "SELECT * FROM vista_inventario WHERE status = 1";
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll();

    foreach ($result as $row) {
        $id_inventario = $row['id_inventario'];

        if($row['activo'] == 1) {
            $active = "<label class='label label-success'>Disponible</label>";
        } else {
            $active = "<label class='label label-danger'>No Disponible</label>";
        }

        $button = '<!-- Single button -->
        <div class="btn-group">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Acción <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
            <li><a type="button" data-toggle="modal" id="editProductModalBtn" data-target="#editProductModal" onclick="editProduct('.$id_inventario.')"> <i class="glyphicon glyphicon-edit"></i> Editar</a></li>
            <li><a type="button" data-toggle="modal" data-target="#removeProductModal" id="removeProductModalBtn" onclick="removeProduct('.$id_inventario.')"> <i class="glyphicon glyphicon-trash"></i> Eliminar</a></li>
          </ul>
        </div>';

        $imageUrl = substr($row['product_image'], 3);
        $productImage = "<img class='img-round' src='".$imageUrl."' style='height:30px; width:50px;'  />";

        $output['data'][] = array(
            $productImage,
            $row['codigo_interno'],
            $row['nombre_articulo'],
            $row['nombre_color'],
            $row['cantidad'],
            $row['nombre_marca'],
            $row['nombre_categoria'],
            $row['nombre_ubicacion'],
            $button
        );
    }
} catch (PDOException $e) {
    // Handle error
}

echo json_encode($output);
?>