<?php
class Articulo {
    private $pdo;

    public function __construct($db) {
        $this->pdo = $db;
    }

    public function obtenerTodo($filtros = []) {
        $sql = "SELECT a.*, e.cantidad_actual, e.estado_conservacion, u.nombre_ubicacion, s.nombre_sede 
                FROM articulos a
                JOIN inventario_existencias e ON a.id_articulo = e.id_articulo
                JOIN ubicaciones u ON e.id_ubicacion = u.id_ubicacion
                JOIN sedes s ON u.id_sede = s.id_sede
                WHERE 1=1";

        $params = [];
        if (!empty($filtros['busqueda'])) {
            $sql .= " AND (a.nombre LIKE :b OR a.codigo_interno LIKE :b)";
            $params[':b'] = "%".$filtros['busqueda']."%";
        }
        if (!empty($filtros['color'])) {
            $sql .= " AND a.color = :color";
            $params[':color'] = $filtros['color'];
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}