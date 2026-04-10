<?php
// controllers/ArticuloController.php
require_once 'config/database.php';

class ArticuloController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerFiltros() {
        return [
            'sedes' => $this->pdo->query("SELECT DISTINCT nombre_sede FROM sedes")->fetchAll(PDO::FETCH_COLUMN),
            'ubicaciones' => $this->pdo->query("SELECT DISTINCT nombre_ubicacion FROM ubicaciones")->fetchAll(PDO::FETCH_COLUMN),
            'colores' => $this->pdo->query("SELECT DISTINCT color FROM articulos WHERE color != ''")->fetchAll(PDO::FETCH_COLUMN),
            'formas' => $this->pdo->query("SELECT DISTINCT forma FROM articulos WHERE forma IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN)
        ];
    }

    public function listarInventario($filtros = []) {
        $sql = "SELECT
                    a.id_articulo,
                    a.codigo_interno,
                    a.nombre,
                    a.marca,
                    a.color,
                    a.forma,
                    SUM(e.cantidad_actual) as total_cantidad,
                    GROUP_CONCAT(CONCAT(u.nombre_ubicacion, ' (', s.nombre_sede, '): ', e.cantidad_actual) SEPARATOR '<br>') as detalle_ubicacion
                FROM articulos a
                JOIN inventario_existencias e ON a.id_articulo = e.id_articulo
                JOIN ubicaciones u ON e.id_ubicacion = u.id_ubicacion
                JOIN sedes s ON u.id_sede = s.id_sede
                WHERE 1=1";

        $params = [];

        if (!empty($filtros['sede'])) {
            $sql .= " AND s.nombre_sede = :sede";
            $params[':sede'] = $filtros['sede'];
        }
        if (!empty($filtros['ubicacion'])) {
            $sql .= " AND u.nombre_ubicacion = :ubicacion";
            $params[':ubicacion'] = $filtros['ubicacion'];
        }
        if (!empty($filtros['color'])) {
            $sql .= " AND a.color = :color";
            $params[':color'] = $filtros['color'];
        }
        if (!empty($filtros['forma'])) {
            $sql .= " AND a.forma = :forma";
            $params[':forma'] = $filtros['forma'];
        }

        $sql .= " GROUP BY a.id_articulo, a.codigo_interno, a.nombre, a.marca, a.color, a.forma";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
