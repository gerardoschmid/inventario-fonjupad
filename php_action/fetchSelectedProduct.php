<?php 	

require_once 'core.php';


$productId = $_POST['productId'];

try {
    $sql = "SELECT product_id, product_name, product_image, brand_id, categories_id, quantity, rate, active, status, codigo_interno, color, estado, ubicacion_especifica FROM product WHERE product_id = :productId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':productId' => $productId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($row);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
