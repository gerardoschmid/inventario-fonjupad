<?php 	

require_once 'core.php';


$userId = $_POST['userId'];

try {
    $sql = "SELECT user_id, username, password, email FROM users WHERE user_id = :userId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':userId' => $userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($row);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
