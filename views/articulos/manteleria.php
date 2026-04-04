<?php
require_once 'models/Articulo.php';
$objArticulo = new Articulo($pdo);
$items = $objArticulo->obtenerTodo($_GET);
?>

<div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
    <h2>Consulta de Inventario</h2>
    <button onclick="window.print()" class="btn btn-success">Imprimir Reporte</button>
</div>

<div class="card mb-4 shadow-sm d-print-none">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <input type="hidden" name="view" value="manteleria">
            <div class="col-md-6">
                <input type="text" name="busqueda" class="form-control" placeholder="Buscar por nombre o código..." value="<?= $_GET['busqueda'] ?? '' ?>">
            </div>
            <div class="col-md-4">
                <select name="color" class="form-select">
                    <option value="">Todos los colores</option>
                    <option value="BLANCO">Blanco</option>
                    <option value="NEGRO">Negro</option>
                    </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped bg-white">
        <thead class="table-dark">
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th>Color</th>
                <th>Ubicación</th>
                <th>Cant.</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($items as $item): ?>
            <tr>
                <td><?= $item['codigo_interno'] ?></td>
                <td><?= $item['nombre'] ?></td>
                <td><?= $item['color'] ?></td>
                <td><?= $item['nombre_ubicacion'] ?></td>
                <td><?= $item['cantidad_actual'] ?></td>
                <td><?= $item['estado_conservacion'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>