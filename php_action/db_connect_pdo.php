<?php
$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "store";

try {
    $pdo = new PDO("mysql:host=$localhost;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection Failed: " . $e->getMessage());
}
?>