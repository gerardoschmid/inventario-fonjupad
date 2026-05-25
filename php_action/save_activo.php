<?php
require_once 'core.php';


$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {
    $id_inventario = isset($_POST['productId']) ? $_POST['productId'] : null;

    // Check if it's Edit mode based on field prefixes
    if ($id_inventario) {
        $nombre_articulo = $_POST['editProductName'];
        $codigo_interno = $_POST['editCodigoInterno'];
        $id_marca = $_POST['editBrandName'];
        $id_categoria = $_POST['editCategoryName'];
        $id_color = $_POST['editColor'];
        $cantidad = $_POST['editQuantity'];
        $id_estado = $_POST['editEstadoActivo'];
        $id_ubicacion = $_POST['editUbicacionEspecifica'];
        $rate = $_POST['editRate'];
        $activo = $_POST['editProductStatus'];
    } else {
        $nombre_articulo = $_POST['productName'];
        $codigo_interno = $_POST['codigoInterno'];
        $id_marca = $_POST['brandName'];
        $id_categoria = $_POST['categoryName'];
        $id_color = $_POST['color'];
        $cantidad = $_POST['quantity'];
        $id_estado = $_POST['estadoActivo'];
        $id_ubicacion = $_POST['ubicacionEspecifica'];
        $rate = $_POST['rate'];
        $activo = $_POST['productStatus'];
    }

    try {
        $pdo->beginTransaction();

        if($id_inventario) {
            // Update mode
            $stmt = $pdo->prepare("SELECT id_articulo FROM inventario WHERE id_inventario = ?");
            $stmt->execute([$id_inventario]);
            $row = $stmt->fetch();

            if($row) {
                $id_articulo = $row['id_articulo'];

                // Update articulo
                $stmt = $pdo->prepare("UPDATE articulos SET nombre_articulo = ?, codigo_interno = ?, id_marca = ?, id_categoria = ?, id_color = ? WHERE id_articulo = ?");
                $stmt->execute([$nombre_articulo, $codigo_interno, $id_marca, $id_categoria, $id_color, $id_articulo]);

                // Update inventario
                $stmt = $pdo->prepare("UPDATE inventario SET id_ubicacion = ?, id_estado = ?, cantidad = ?, rate = ?, activo = ? WHERE id_inventario = ?");
                $stmt->execute([$id_ubicacion, $id_estado, $cantidad, $rate, $activo, $id_inventario]);

                $valid['success'] = true;
                $valid['messages'] = "Actualizado exitosamente";
            } else {
                $valid['success'] = false;
                $valid['messages'] = "ID de inventario no encontrado";
            }
        } else {
            // Create mode
            $stmt = $pdo->prepare("INSERT INTO articulos (nombre_articulo, codigo_interno, id_marca, id_categoria, id_color) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nombre_articulo, $codigo_interno, $id_marca, $id_categoria, $id_color]);
            $id_articulo = $pdo->lastInsertId();

            $stmt = $pdo->prepare("INSERT INTO inventario (id_articulo, id_ubicacion, id_estado, cantidad, rate, activo, status) VALUES (?, ?, ?, ?, ?, ?, 1)");
            $stmt->execute([$id_articulo, $id_ubicacion, $id_estado, $cantidad, $rate, $activo]);

            $valid['success'] = true;
            $valid['messages'] = "Agregado exitosamente";
        }

        $pdo->commit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        $valid['success'] = false;
        $valid['messages'] = "Error: " . $e->getMessage();
    }
}

echo json_encode($valid);
?>