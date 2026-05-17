<?php 	

require_once 'core.php';
require_once 'db_connect_pdo.php';

$categoriesId = $_POST['categoriesId'];

try {
    $sql = "SELECT categories_id, categories_name, categories_active, categories_status FROM categories WHERE categories_id = :categoriesId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':categoriesId' => $categoriesId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($row);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
