<?php 	

require_once 'core.php';
require_once 'db_connect_pdo.php';

$brandId = $_POST['brandId'];

try {
    $sql = "SELECT brand_id, brand_name, brand_active, brand_status FROM brands WHERE brand_id = :brandId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':brandId' => $brandId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($row);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
