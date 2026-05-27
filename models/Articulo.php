<?php
// models/Articulo.php

class Articulo {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Crea un nuevo activo en la base de datos (3NF).
     */
    public function crear($datos) {
        try {
            $this->pdo->beginTransaction();

            // 1. Insertar en tabla descriptiva 'articulos'
            $stmtArt = $this->pdo->prepare("INSERT INTO articulos (nombre_articulo, codigo_interno, id_marca, id_categoria, id_color) VALUES (?, ?, ?, ?, ?)");
            $stmtArt->execute([
                $datos['nombre_articulo'],
                $datos['codigo_interno'],
                $datos['id_marca'],
                $datos['id_categoria'],
                $datos['id_color']
            ]);
            $id_articulo = $this->pdo->lastInsertId();

            // 2. Insertar en tabla de existencias 'inventario'
            $stmtInv = $this->pdo->prepare("INSERT INTO inventario (id_articulo, id_ubicacion, id_estado, cantidad, rate, activo, status) VALUES (?, ?, ?, ?, ?, ?, 1)");
            $stmtInv->execute([
                $id_articulo,
                $datos['id_ubicacion'],
                $datos['id_estado'],
                $datos['cantidad'],
                $datos['rate'],
                $datos['activo']
            ]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Actualiza un activo existente.
     */
    public function actualizar($id_inventario, $datos) {
        try {
            $this->pdo->beginTransaction();

            // Obtener el id_articulo asociado
            $stmt = $this->pdo->prepare("SELECT id_articulo FROM inventario WHERE id_inventario = ?");
            $stmt->execute([$id_inventario]);
            $row = $stmt->fetch();

            if (!$row) return false;
            $id_articulo = $row['id_articulo'];

            // 1. Actualizar tabla 'articulos'
            $stmtArt = $this->pdo->prepare("UPDATE articulos SET nombre_articulo = ?, codigo_interno = ?, id_marca = ?, id_categoria = ?, id_color = ? WHERE id_articulo = ?");
            $stmtArt->execute([
                $datos['nombre_articulo'],
                $datos['codigo_interno'],
                $datos['id_marca'],
                $datos['id_categoria'],
                $datos['id_color'],
                $id_articulo
            ]);

            // 2. Actualizar tabla 'inventario'
            $stmtInv = $this->pdo->prepare("UPDATE inventario SET id_ubicacion = ?, id_estado = ?, cantidad = ?, rate = ?, activo = ? WHERE id_inventario = ?");
            $stmtInv->execute([
                $datos['id_ubicacion'],
                $datos['id_estado'],
                $datos['cantidad'],
                $datos['rate'],
                $datos['activo'],
                $id_inventario
            ]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Obtiene el listado completo para el DataTable (vía Vista).
     */
    public function listarActivos() {
        $sql = "SELECT * FROM vista_inventario WHERE status = 1";
        return $this->pdo->query($sql)->fetchAll();
    }

    /**
     * Obtiene un activo específico por su ID de inventario.
     */
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM vista_inventario WHERE id_inventario = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Eliminación lógica de un activo.
     */
    public function eliminar($id) {
        $sql = "UPDATE inventario SET status = 2 WHERE id_inventario = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
