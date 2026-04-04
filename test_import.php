<?php
// test_import.php
require_once 'config/database.php';
require_once 'controllers/ImportarController.php';

// Simulación de creación de tablas si no existen para la prueba
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS sedes (
        id_sede INT AUTO_INCREMENT PRIMARY KEY,
        nombre_sede VARCHAR(100) UNIQUE
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS ubicaciones (
        id_ubicacion INT AUTO_INCREMENT PRIMARY KEY,
        nombre_ubicacion VARCHAR(100),
        id_sede INT,
        FOREIGN KEY (id_sede) REFERENCES sedes(id_sede)
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS articulos (
        id_articulo INT AUTO_INCREMENT PRIMARY KEY,
        codigo_interno VARCHAR(50) UNIQUE,
        nombre VARCHAR(255),
        marca VARCHAR(100),
        color VARCHAR(50),
        forma VARCHAR(50)
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS inventario_existencias (
        id_articulo INT,
        id_ubicacion INT,
        cantidad_actual INT,
        estado_conservacion VARCHAR(50),
        PRIMARY KEY (id_articulo, id_ubicacion),
        FOREIGN KEY (id_articulo) REFERENCES articulos(id_articulo),
        FOREIGN KEY (id_ubicacion) REFERENCES ubicaciones(id_ubicacion)
    )");

    // Insertar sede inicial
    $pdo->exec("INSERT IGNORE INTO sedes (nombre_sede) VALUES ('SEDE BARINAS')");

    $controller = new ImportarController($pdo);
    $resultado = $controller->importarCSV('inventario_barinas_para_subir.csv', 1);

    print_r($resultado);

} catch (Exception $e) {
    echo "Error en la prueba: " . $e->getMessage();
}
