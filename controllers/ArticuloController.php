<?php
// controllers/ArticuloController.php
require_once __DIR__ . '/../models/Articulo.php';

class ArticuloController {
    private $articuloModel;

    public function __construct($pdo) {
        $this->articuloModel = new Articulo($pdo);
    }

    /**
     * Procesa la creación o actualización de un activo.
     */
    public function guardar($postData) {
        $id_inventario = $postData['productId'] ?? null;

        // Mapeo unificado para resolver inconsistencias entre frontend y backend
        $datos = [
            'nombre_articulo' => $postData['editProductName'] ?? $postData['productName'],
            'codigo_interno'  => $postData['editCodigoInterno'] ?? $postData['codigoInterno'],
            'id_marca'        => $postData['editBrandName'] ?? $postData['brandName'],
            'id_categoria'    => $postData['editCategoryName'] ?? $postData['categoryName'],
            'id_color'        => $postData['editColor'] ?? $postData['color'],
            'cantidad'        => $postData['editQuantity'] ?? $postData['quantity'],
            'id_estado'       => $postData['editEstadoActivo'] ?? $postData['estadoActivo'],
            'id_ubicacion'    => $postData['editUbicacionEspecifica'] ?? $postData['ubicacionEspecifica'],
            'rate'            => $postData['editRate'] ?? $postData['rate'],
            'activo'          => $postData['editProductEstado'] ?? $postData['productEstado']
        ];

        try {
            if ($id_inventario) {
                $resultado = $this->articuloModel->actualizar($id_inventario, $datos);
                $mensaje = "Actualizado exitosamente";
            } else {
                $resultado = $this->articuloModel->crear($datos);
                $mensaje = "Agregado exitosamente";
            }

            return [
                'success' => $resultado,
                'messages' => $resultado ? $mensaje : "No se pudo procesar la solicitud"
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'messages' => "Error: " . $e->getMessage()
            ];
        }
    }

    public function eliminar($id) {
        $resultado = $this->articuloModel->eliminar($id);
        return [
            'success' => $resultado,
            'messages' => $resultado ? "Eliminado correctamente" : "Error al eliminar"
        ];
    }
}
