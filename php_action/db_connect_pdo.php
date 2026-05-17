<?php

$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "store"; // Updated to match user context

try {
    $pdo = new PDO("mysql:host=$localhost;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Silently fail or log, but for web output:
    die("Error de conexión: " . $e->getMessage());
}

?>
