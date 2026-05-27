<?php 	

require_once 'core.php';


$productId = $_GET['i'];

try {
    $sql = "SELECT product_image FROM product WHERE product_id = :productId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':productId' => $productId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo substr($row['product_image'], 3);
} catch (PDOException $e) {
    echo "";
}
