<?php 	

require_once 'core.php';


// The SQL View vista_inventario is used to fetch inventory data with descriptive names
$sql = "SELECT * FROM vista_inventario WHERE status = 1";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();

    $output = array('data' => array());

    foreach($result as $row) {
        $productId = $row['product_id'];

        $button = '
        <div class="btn-group">
          <button type="button" class="p-2 hover:bg-surface-variant rounded-full text-outline transition-colors dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="material-symbols-outlined">more_vert</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-right rounded-xl shadow-lg border-outline-variant py-2">
            <li><a type="button" class="flex items-center px-4 py-2 hover:bg-surface-container-low text-body-md font-body-md" data-toggle="modal" id="editProductModalBtn" data-target="#editProductModal" onclick="editProduct('.$productId.')"> <i class="glyphicon glyphicon-edit mr-2"></i> Editar</a></li>
            <li><a type="button" class="flex items-center px-4 py-2 hover:bg-error-container text-error text-body-md font-body-md" data-toggle="modal" data-target="#removeProductModal" id="removeProductModalBtn" onclick="removeProduct('.$productId.')"> <i class="glyphicon glyphicon-trash mr-2"></i> Eliminar</a></li>
          </ul>
        </div>';

        $imageUrl = substr($row['product_image'], 3);
        $productImage = "<img class='w-12 h-12 rounded-lg object-cover border border-outline-variant' src='".$imageUrl."' />";

        // Status chip styling
        $estado = $row['estado'];
        $statusClass = "bg-surface-container-highest text-outline";
        if ($estado == 'Operativo') $statusClass = "bg-emerald-100 text-on-tertiary-container";
        else if ($estado == 'Reparación' || $estado == 'Mantenimiento') $statusClass = "bg-amber-100 text-on-secondary-fixed-variant";
        else if ($estado == 'Baja' || $estado == 'Sin Stock') $statusClass = "bg-rose-100 text-error";

        $output['data'][] = array(
            // image
            $productImage,
            // codigo interno
            '<span class="font-mono text-body-md font-body-md text-on-surface-variant">'.$row['codigo_interno'].'</span>',
            // product name
            '<span class="font-bold text-body-md font-body-md">'.$row['product_name'].'</span>',
            // brand (Sede)
            '<span class="text-body-md font-body-md">'.$row['brand_name'].'</span>',
            // category (Tipo de Activo)
            '<span class="text-body-md font-body-md">'.$row['categories_name'].'</span>',
            // color
            '<span class="text-body-md font-body-md">'.$row['color'].'</span>',
            // ubicacion
            '<span class="text-body-md font-body-md">'.$row['ubicacion_especifica'].'</span>',
            // estado
            '<span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider '.$statusClass.'">'.$estado.'</span>',
            // quantity
            '<span class="text-body-md font-body-md font-bold">'.$row['quantity'].'</span>',
            // button
            '<div class="text-right">'.$button.'</div>'
        );
    }

    echo json_encode($output);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
