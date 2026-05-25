<?php

require_once 'core.php';


$id_inventario = $_POST['productId'];

$sql = "SELECT * FROM vista_inventario WHERE id_inventario = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_inventario]);
$result = $stmt->fetch();

echo json_encode($result);
?>