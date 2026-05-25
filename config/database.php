<?php
// config/database.php

// Configuración de URL base
$store_url = "http://localhost/inventario-cermopa/";

$host = 'localhost';
$db   = 'sistema_inventario'; // El nombre que usamos en el script SQL
$user = 'root';
$pass = ''; // Por defecto en XAMPP es vacío
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza errores si algo falla
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve los datos como arreglos asociativos
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Mejora la seguridad contra SQL Injection
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // Si necesitas verificar la conexión, puedes descomentar la línea de abajo:
    // echo "Conexión exitosa";
} catch (\PDOException $e) {
    // Si hay un error, detiene la ejecución y te muestra qué pasó
    die("Error al conectar a la base de datos: " . $e->getMessage());
}