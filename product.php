<?php require_once 'php_action/db_connect.php' ?>
<?php require_once 'includes/header.php'; ?>

<div class="row">
	<div class="col-md-12">

		<ol class="breadcrumb">
		  <li><a href="dashboard.php">Inicio</a></li>
		  <li class="active">Inventario de Activos</li>
		</ol>

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Gestionar Inventario de Activos</div>
			</div> <!-- /panel-heading -->
			<div class="panel-body">

				<div class="remove-messages"></div>

				<div class="div-action pull pull-right" style="padding-bottom:20px;">
					<button class="btn btn-default button1" data-toggle="modal" id="addProductModalBtn" data-target="#addProductModal"> <i class="glyphicon glyphicon-plus-sign"></i> Añadir Activo </button>
				</div> <!-- /div-action -->				
				
				<table class="table" id="manageProductTable">
					<thead>
						<tr>
							<th style="width:10%;">Imagen</th>
							<th>Código</th>
							<th>Activo</th>
							<th>Color</th>
							<th>Cantidad</th>
							<th>Sede</th>
							<th>Tipo de Activo</th>
							<th>Ubicación</th>
							<th style="width:15%;">Opciones</th>
						</tr>
					</thead>
				</table>
				<!-- /table -->

			</div> <!-- /panel-body -->
		</div> <!-- /panel -->		
	</div> <!-- /col-md-12 -->
</div> <!-- /row -->


<!-- add product -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">

    	<form class="form-horizontal" id="submitProductForm" action="php_action/createProduct.php" method="POST" enctype="multipart/form-data">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><i class="fa fa-plus"></i> Añadir Activo</h4>
	      </div>

	      <div class="modal-body" style="max-height:450px; overflow:auto;">

	      	<div id="add-product-messages"></div>

	      	<div class="form-group">
			<label for="productImage" class="col-sm-3 control-label">Imagen del Activo: </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
					    <!-- the avatar markup -->
							<div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>							
					    <div class="kv-avatar center-block">					        
					        <input type="file" class="form-control" id="productImage" name="productImage" class="file-loading" style="width:auto;"/>
					    </div>
				      
				    </div>
	        </div> <!-- /form-group-->	     	           	       

	        <div class="form-group">
			<label for="codigoInterno" class="col-sm-3 control-label">Código Interno: </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="codigoInterno" placeholder="Código Patrimonial" name="codigoInterno" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->

	        <div class="form-group">
			<label for="productName" class="col-sm-3 control-label">Nombre del Activo: </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="productName" placeholder="Nombre del Activo" name="productName" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->

	        <div class="form-group">
			<label for="color" class="col-sm-3 control-label">Color: </label>
			<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="color" placeholder="Color (ej. Blanco, Azul)" name="color" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->

	        <div class="form-group">
			<label for="quantity" class="col-sm-3 control-label">Cantidad: </label>
			<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="quantity" placeholder="Cantidad" name="quantity" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->	        	 

	        <div class="form-group">
			<label for="estadoActivo" class="col-sm-3 control-label">Estado Físico: </label>
			<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <select class="form-control" id="estadoActivo" name="estadoActivo">
					<option value="">~~SELECCIONAR~~</option>
					<option value="Nuevo">Nuevo</option>
					<option value="Buen Estado">Buen Estado</option>
					<option value="Reparación">En Reparación</option>
					<option value="Baja">Baja</option>
				      </select>
				    </div>
	        </div> <!-- /form-group-->

	        <div class="form-group">
			<label for="ubicacionEspecifica" class="col-sm-3 control-label">Ubicación: </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="ubicacionEspecifica" placeholder="Estante, Salón, etc." name="ubicacionEspecifica" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->

	        <div class="form-group">
			<label for="rate" class="col-sm-3 control-label">Valor/Costo: </label>
			<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="rate" placeholder="Valor unitario" name="rate" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->	     	        

	        <div class="form-group">
			<label for="brandName" class="col-sm-3 control-label">Sede: </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <select class="form-control" id="brandName" name="brandName">
					<option value="">~~SELECCIONAR~~</option>
				      	<?php 
				      	$sql = "SELECT brand_id, brand_name, brand_active, brand_status FROM brands WHERE brand_status = 1 AND brand_active = 1";
								$result = $connect->query($sql);

								while($row = $result->fetch_array()) {
									echo "<option value='".$row[0]."'>".$row[1]."</option>";
								} // while
								
				      	?>
				      </select>
				    </div>
	        </div> <!-- /form-group-->	

	        <div class="form-group">
			<label for="categoryName" class="col-sm-3 control-label">Tipo de Activo: </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <select type="text" class="form-control" id="categoryName" name="categoryName" >
					<option value="">~~SELECCIONAR~~</option>
				      	<?php 
				      	$sql = "SELECT categories_id, categories_name, categories_active, categories_status FROM categories WHERE categories_status = 1 AND categories_active = 1";
								$result = $connect->query($sql);

								while($row = $result->fetch_array()) {
									echo "<option value='".$row[0]."'>".$row[1]."</option>";
								} // while
								
				      	?>
				      </select>
				    </div>
	        </div> <!-- /form-group-->					        	         	       

	        <div class="form-group">
			<label for="productStatus" class="col-sm-3 control-label">Visibilidad: </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <select class="form-control" id="productStatus" name="productStatus">
					<option value="">~~SELECCIONAR~~</option>
					<option value="1">Disponible</option>
					<option value="2">No Disponible</option>
				      </select>
				    </div>
	        </div> <!-- /form-group-->	         	        
	      </div> <!-- /modal-body -->
	      
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cerrar</button>
	        
	        <button type="submit" class="btn btn-primary" id="createProductBtn" data-loading-text="Cargando..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> Guardar Cambios</button>
	      </div> <!-- /modal-footer -->	      
     	</form> <!-- /.form -->	     
    </div> <!-- /modal-content -->    
  </div> <!-- /modal-dailog -->
</div> 
<!-- /add product -->


<!-- edit product -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    	    	
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><i class="fa fa-edit"></i> Editar Activo</h4>
	      </div>
	      <div class="modal-body" style="max-height:450px; overflow:auto;">

	      	<div class="div-loading">
	      		<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
						<span class="sr-only">Cargando...</span>
	      	</div>

	      	<div class="div-result">

				  <!-- Nav tabs -->
				  <ul class="nav nav-tabs" role="tablist">
				    <li role="presentation" class="active"><a href="#photo" aria-controls="home" role="tab" data-toggle="tab">Foto</a></li>
				    <li role="presentation"><a href="#productInfo" aria-controls="profile" role="tab" data-toggle="tab">Información del Activo</a></li>
				  </ul>

				  <!-- Tab panes -->
				  <div class="tab-content">

				  	
				    <div role="tabpanel" class="tab-pane active" id="photo">
				    	<form action="php_action/editProductImage.php" method="POST" id="updateProductImageForm" class="form-horizontal" enctype="multipart/form-data">

				    	<br />
				    	<div id="edit-productPhoto-messages"></div>

				    	<div class="form-group">
					<label for="editProductImage" class="col-sm-3 control-label">Imagen Actual: </label>
			        	<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">							    				   
						      <img src="" id="getProductImage" class="thumbnail" style="width:250px; height:250px;" />
						    </div>
			        </div> <!-- /form-group-->	     	           	       
				    	
			      	<div class="form-group">
					<label for="editProductImage" class="col-sm-3 control-label">Seleccionar Foto: </label>
			        	<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">
							    <!-- the avatar markup -->
									<div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>							
							    <div class="kv-avatar center-block">					        
							        <input type="file" class="form-control" id="editProductImage" name="editProductImage" class="file-loading" style="width:auto;"/>
							    </div>
						      
						    </div>
			        </div> <!-- /form-group-->	     	           	       

			        <div class="modal-footer editProductPhotoFooter">
				        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cerrar</button>
				        <button type="submit" class="btn btn-success" id="editProductImageBtn" data-loading-text="Cargando..."> <i class="glyphicon glyphicon-ok-sign"></i> Guardar Cambios</button>
				      </div>
				      <!-- /modal-footer -->
				      </form>
				      <!-- /form -->
				    </div>
				    <!-- product image -->
				    <div role="tabpanel" class="tab-pane" id="productInfo">
				    	<form class="form-horizontal" id="editProductForm" action="php_action/editProduct.php" method="POST">				    
				    	<br />

				    	<div id="edit-product-messages"></div>

				    	<div class="form-group">
					<label for="editCodigoInterno" class="col-sm-3 control-label">Código Interno: </label>
					<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">
						      <input type="text" class="form-control" id="editCodigoInterno" placeholder="Código Patrimonial" name="editCodigoInterno" autocomplete="off">
						    </div>
			        </div> <!-- /form-group-->

					<div class="form-group">
					<label for="editProductName" class="col-sm-3 control-label">Nombre del Activo: </label>
					<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">
						      <input type="text" class="form-control" id="editProductName" placeholder="Nombre del Activo" name="editProductName" autocomplete="off">
						    </div>
			        </div> <!-- /form-group-->

			        <div class="form-group">
					<label for="editColor" class="col-sm-3 control-label">Color: </label>
					<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">
						      <input type="text" class="form-control" id="editColor" placeholder="Color" name="editColor" autocomplete="off">
						    </div>
			        </div> <!-- /form-group-->

			        <div class="form-group">
					<label for="editQuantity" class="col-sm-3 control-label">Cantidad: </label>
			        	<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">
						      <input type="text" class="form-control" id="editQuantity" placeholder="Cantidad" name="editQuantity" autocomplete="off">
						    </div>
			        </div> <!-- /form-group-->

			        <div class="form-group">
					<label for="editEstadoActivo" class="col-sm-3 control-label">Estado Físico: </label>
			        	<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">
						      <select class="form-control" id="editEstadoActivo" name="editEstadoActivo">
							<option value="">~~SELECCIONAR~~</option>
							<option value="Nuevo">Nuevo</option>
							<option value="Buen Estado">Buen Estado</option>
							<option value="Reparación">En Reparación</option>
							<option value="Baja">Baja</option>
						      </select>
						    </div>
			        </div> <!-- /form-group-->

			        <div class="form-group">
					<label for="editUbicacionEspecifica" class="col-sm-3 control-label">Ubicación: </label>
			        	<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">
						      <input type="text" class="form-control" id="editUbicacionEspecifica" placeholder="Ubicación" name="editUbicacionEspecifica" autocomplete="off">
						    </div>
			        </div> <!-- /form-group-->

			        <div class="form-group">
					<label for="editRate" class="col-sm-3 control-label">Valor/Costo: </label>
					<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">
						      <input type="text" class="form-control" id="editRate" placeholder="Valor unitario" name="editRate" autocomplete="off">
						    </div>
			        </div> <!-- /form-group-->	     	        

			        <div class="form-group">
					<label for="editBrandName" class="col-sm-3 control-label">Sede: </label>
			        	<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">
						      <select class="form-control" id="editBrandName" name="editBrandName">
							<option value="">~~SELECCIONAR~~</option>
						      	<?php 
						      	$sql = "SELECT brand_id, brand_name, brand_active, brand_status FROM brands WHERE brand_status = 1 AND brand_active = 1";
										$result = $connect->query($sql);

										while($row = $result->fetch_array()) {
											echo "<option value='".$row[0]."'>".$row[1]."</option>";
										} // while

						      	?>
						      </select>
						    </div>
			        </div> <!-- /form-group-->	

			        <div class="form-group">
					<label for="editCategoryName" class="col-sm-3 control-label">Tipo de Activo: </label>
			        	<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">
						      <select type="text" class="form-control" id="editCategoryName" name="editCategoryName" >
							<option value="">~~SELECCIONAR~~</option>
						      	<?php 
						      	$sql = "SELECT categories_id, categories_name, categories_active, categories_status FROM categories WHERE categories_status = 1 AND categories_active = 1";
										$result = $connect->query($sql);

										while($row = $result->fetch_array()) {
											echo "<option value='".$row[0]."'>".$row[1]."</option>";
										} // while

						      	?>
						      </select>
						    </div>
			        </div> <!-- /form-group-->					        	         	       

			        <div class="form-group">
					<label for="editProductStatus" class="col-sm-3 control-label">Visibilidad: </label>
			        	<label class="col-sm-1 control-label">: </label>
						    <div class="col-sm-8">
						      <select class="form-control" id="editProductStatus" name="editProductStatus">
							<option value="">~~SELECCIONAR~~</option>
							<option value="1">Disponible</option>
							<option value="2">No Disponible</option>
						      </select>
						    </div>
			        </div> <!-- /form-group-->	         	        

			        <div class="modal-footer editProductFooter">
				        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cerrar</button>
				        <button type="submit" class="btn btn-success" id="editProductBtn" data-loading-text="Cargando..."> <i class="glyphicon glyphicon-ok-sign"></i> Guardar Cambios</button>
				      </div> <!-- /modal-footer -->				     
			        </form> <!-- /.form -->				     	
				    </div>    
				    <!-- /product info -->
				  </div>

				</div>
	      	
	      </div> <!-- /modal-body -->
	      	      
     	
    </div>
    <!-- /modal-content -->
  </div>
  <!-- /modal-dailog -->
</div>
<!-- /edit product -->

<!-- remove product -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeProductModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> Eliminar Activo</h4>
      </div>
      <div class="modal-body">

      	<div class="removeProductMessages"></div>

        <p>¿Realmente desea eliminar este activo?</p>
      </div>
      <div class="modal-footer removeProductFooter">
        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cerrar</button>
        <button type="button" class="btn btn-primary" id="removeProductBtn" data-loading-text="Cargando..."> <i class="glyphicon glyphicon-ok-sign"></i> Guardar Cambios</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /remove product -->


<script src="custom/js/product.js"></script>

<?php require_once 'includes/footer.php'; ?>
