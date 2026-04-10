<?php require_once 'includes/header.php'; ?>

<?php 

// Total Activos (Global)
$sql = "SELECT * FROM product WHERE status = 1";
$query = $connect->query($sql);
$countProduct = $query->num_rows;

// Activos en Sede Barinas (Assuming brand_id 1 is Barinas based on my previous SQL)
$barinasSql = "SELECT * FROM product WHERE brand_id = 1 AND status = 1";
$barinasQuery = $connect->query($barinasSql);
$countBarinas = $barinasQuery->num_rows;

// Activos en Sede Guanare/Apure (Assuming brand_id 2 and 3)
$otrasSedesSql = "SELECT * FROM product WHERE brand_id IN (2, 3) AND status = 1";
$otrasSedesQuery = $connect->query($otrasSedesSql);
$countOtrasSedes = $otrasSedesQuery->num_rows;

// Alerta de Activos en 'Reparación' o 'Baja'
$alertaSql = "SELECT * FROM product WHERE estado IN ('Reparación', 'Baja') AND status = 1";
$alertaQuery = $connect->query($alertaSql);
$countAlerta = $alertaQuery->num_rows;

$connect->close();

?>

<style type="text/css">
    .ui-datepicker-calendar {
        display: none;
    }
</style>

<div class="row">
    <?php  if(isset($_SESSION['userId']) && $_SESSION['userId']==1) { ?>
    <div class="col-md-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <a href="product.php" style="text-decoration:none;color:white;">
                    Total Activos
                    <span class="badge pull pull-right"><?php echo $countProduct; ?></span>    
                </a>
            </div> <!--/panel-heading-->
        </div> <!--/panel-->
    </div> <!--/col-md-3-->
    
    <div class="col-md-3">
        <div class="panel panel-info">
            <div class="panel-heading">
                <a href="product.php" style="text-decoration:none;color:black;">
                    Sede Barinas
                    <span class="badge pull pull-right"><?php echo $countBarinas; ?></span>
                </a>
            </div> <!--/panel-heading-->
        </div> <!--/panel-->
    </div> <!--/col-md-3-->

    <div class="col-md-3">
        <div class="panel panel-info">
            <div class="panel-heading">
                <a href="product.php" style="text-decoration:none;color:black;">
                    Sedes Guanare/Apure
                    <span class="badge pull pull-right"><?php echo $countOtrasSedes; ?></span>
                </a>
            </div> <!--/panel-heading-->
        </div> <!--/panel-->
    </div> <!--/col-md-3-->
    
    <div class="col-md-3">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <a href="product.php" style="text-decoration:none;color:white;">
                    En Reparación / Baja
                    <span class="badge pull pull-right"><?php echo $countAlerta; ?></span>
                </a>
            </div> <!--/panel-heading-->
        </div> <!--/panel-->
    </div> <!--/col-md-3-->
    <?php } ?>
</div> <!--/row-->

<div class="row">
    <div class="col-md-4">
        <div class="card">
          <div class="cardHeader">
            <h1><?php echo date('d'); ?></h1>
          </div>

          <div class="cardContainer">
            <p><?php
                $dias = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
                $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                echo $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y');
            ?></p>
          </div>
        </div> 
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading"> <i class="glyphicon glyphicon-info-sign"></i> Información del Sistema</div>
            <div class="panel-body">
                <p>Bienvenido al Sistema de Gestión de Activos CERMOPA. Utilice el menú superior para navegar por las diferentes secciones.</p>
                <hr>
                <p><b>Estado Actual:</b> Operativo</p>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('#navDashboard').addClass('active');
    });
</script>

<?php require_once 'includes/footer.php'; ?>
