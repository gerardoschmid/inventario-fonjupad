<?php 	

require_once 'core.php';
require_once 'db_connect_pdo.php';

$sql = "SELECT product_id, product_name FROM product WHERE status = 1 AND active = 1";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result_data = $stmt->fetchAll();

$data = $result->fetch_all();



echo json_encode($data);