var manageProductTable;

$(document).ready(function() {
	// top nav bar 
	$('#navProduct').addClass('active');

    // Cargar catálogos y poblar selects
    cargarCatalogos();

	// manage product data table
	manageProductTable = $('#manageProductTable').DataTable({
		'ajax': 'php_action/get_inventario.php',
		'order': [],
		'dom': 'Bfrtip',
        'buttons': [
            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel-o"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success'
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fa fa-file-pdf-o"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger'
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i> Imprimir',
                titleAttr: 'Vista de Impresión',
                className: 'btn btn-info'
            }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        }
	});

	// add product modal btn clicked
	$("#addProductModalBtn").unbind('click').bind('click', function() {
		// // product form reset
		$("#submitProductForm")[0].reset();		

		// remove text-error 
		$(".text-danger").remove();
		// remove from-group error
		$(".form-group").removeClass('has-error').removeClass('has-success');

		$("#productImage").fileinput({
	      overwriteInitial: true,
		    maxFileSize: 2500,
		    showClose: false,
		    showCaption: false,
		    browseLabel: '',
		    removeLabel: '',
		    browseIcon: '<i class="glyphicon glyphicon-folder-open"></i>',
		    removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
		    removeTitle: 'Cancel or reset changes',
		    elErrorContainer: '#kv-avatar-errors-1',
		    msgErrorClass: 'alert alert-block alert-danger',
		    defaultPreviewContent: '<img src="assests/images/photo_default.png" alt="Profile Image" style="width:100%;">',
		    layoutTemplates: {main2: '{preview} {remove} {browse}'},								    
	  		allowedFileExtensions: ["jpg", "png", "gif", "JPG", "PNG", "GIF"]
			});   

		// submit product form
		$("#submitProductForm").unbind('submit').bind('submit', function() {

			// form validation
			var productName = $("#productName").val();
			var quantity = $("#quantity").val();
			var brandName = $("#brandName").val();
			var categoryName = $("#categoryName").val();
			var productStatus = $("#productStatus").val();
            var color = $("#color").val();
            var estadoActivo = $("#estadoActivo").val();
            var ubicacionEspecifica = $("#ubicacionEspecifica").val();
	
			if(productName == "") {
				$("#productName").after('<p class="text-danger">El nombre del activo es obligatorio</p>');
				$('#productName').closest('.form-group').addClass('has-error');
			}	else {
				$("#productName").find('.text-danger').remove();
				$("#productName").closest('.form-group').addClass('has-success');	  	
			}

			if(quantity == "") {
				$("#quantity").after('<p class="text-danger">La cantidad es obligatoria</p>');
				$('#quantity').closest('.form-group').addClass('has-error');
			}	else {
				$("#quantity").find('.text-danger').remove();
				$("#quantity").closest('.form-group').addClass('has-success');	  	
			}

			if(brandName == "") {
				$("#brandName").after('<p class="text-danger">La sede es obligatoria</p>');
				$('#brandName').closest('.form-group').addClass('has-error');
			}	else {
				$("#brandName").find('.text-danger').remove();
				$("#brandName").closest('.form-group').addClass('has-success');	  	
			}

			if(categoryName == "") {
				$("#categoryName").after('<p class="text-danger">La categoría es obligatoria</p>');
				$('#categoryName').closest('.form-group').addClass('has-error');
			}	else {
				$("#categoryName").find('.text-danger').remove();
				$("#categoryName").closest('.form-group').addClass('has-success');	  	
			}

            if(color == "") {
				$("#color").after('<p class="text-danger">El color es obligatorio</p>');
				$('#color').closest('.form-group').addClass('has-error');
			}	else {
				$("#color").find('.text-danger').remove();
				$("#color").closest('.form-group').addClass('has-success');
			}

            if(estadoActivo == "") {
				$("#estadoActivo").after('<p class="text-danger">El estado es obligatorio</p>');
				$('#estadoActivo').closest('.form-group').addClass('has-error');
			}	else {
				$("#estadoActivo").find('.text-danger').remove();
				$("#estadoActivo").closest('.form-group').addClass('has-success');
			}

            if(ubicacionEspecifica == "") {
				$("#ubicacionEspecifica").after('<p class="text-danger">La ubicación es obligatoria</p>');
				$('#ubicacionEspecifica').closest('.form-group').addClass('has-error');
			}	else {
				$("#ubicacionEspecifica").find('.text-danger').remove();
				$("#ubicacionEspecifica").closest('.form-group').addClass('has-success');
			}

			if(productStatus == "") {
				$("#productStatus").after('<p class="text-danger">La visibilidad es obligatoria</p>');
				$('#productStatus').closest('.form-group').addClass('has-error');
			}	else {
				$("#productEstado").find('.text-danger').remove();
				$("#productEstado").closest('.form-group').addClass('has-success');
			}

			if(productName && quantity && brandName && categoryName && color && estadoActivo && ubicacionEspecifica && productStatus) {
				// submit loading button
				$("#createProductBtn").button('loading');

				var form = $(this);
				var formData = new FormData(this);

				$.ajax({
					url : form.attr('action'),
					type: form.attr('method'),
					data: formData,
					dataType: 'json',
					cache: false,
					contentType: false,
					processData: false,
					success:function(response) {

						if(response.success == true) {
							// submit loading button
							$("#createProductBtn").button('reset');
							
							$("#submitProductForm")[0].reset();

							$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
																	
							// shows a successful message after operation
							$('#add-product-messages').html('<div class="alert alert-success">'+
		            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
		            '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
		          '</div>');

							// remove the mesages
		          $(".alert-success").delay(500).show(10, function() {
								$(this).delay(3000).hide(10, function() {
									$(this).remove();
								});
							}); // /.alert

		          // reload the manage student table
							manageProductTable.ajax.reload(null, true);

							// remove text-error 
							$(".text-danger").remove();
							// remove from-group error
							$(".form-group").removeClass('has-error').removeClass('has-success');

						} // /if response.success
						
					} // /success function
				}); // /ajax function
			}	 // /if validation is ok 					

			return false;
		}); // /submit product form

	}); // /add product modal btn clicked
	

	// remove product 	

}); // document.ready fucntion

function cargarCatalogos() {
    $.ajax({
        url: 'php_action/fetchCatalogos.php',
        type: 'get',
        dataType: 'json',
        success: function(response) {
            // Poblar Sedes (Marcas)
            var marcasOptions = '<option value="">~~SELECCIONAR~~</option>';
            response.marcas.forEach(function(item) {
                marcasOptions += '<option value="'+item.id+'">'+item.nombre+'</option>';
            });
            $("#brandName, #editBrandName").html(marcasOptions);

            // Poblar Categorías
            var categoriasOptions = '<option value="">~~SELECCIONAR~~</option>';
            response.categorias.forEach(function(item) {
                categoriasOptions += '<option value="'+item.id+'">'+item.nombre+'</option>';
            });
            $("#categoryName, #editCategoryName").html(categoriasOptions);

            // Poblar Colores
            var coloresOptions = '<option value="">~~SELECCIONAR~~</option>';
            response.colores.forEach(function(item) {
                coloresOptions += '<option value="'+item.id+'">'+item.nombre+'</option>';
            });
            $("#color, #editColor").html(coloresOptions);

            // Poblar Ubicaciones
            var ubicacionesOptions = '<option value="">~~SELECCIONAR~~</option>';
            response.ubicaciones.forEach(function(item) {
                ubicacionesOptions += '<option value="'+item.id+'">'+item.nombre+'</option>';
            });
            $("#ubicacionEspecifica, #editUbicacionEspecifica").html(ubicacionesOptions);

            // Poblar Estados
            var estadosOptions = '<option value="">~~SELECCIONAR~~</option>';
            response.estados.forEach(function(item) {
                estadosOptions += '<option value="'+item.id+'">'+item.nombre+'</option>';
            });
            $("#estadoActivo, #editEstadoActivo").html(estadosOptions);
        }
    });
}

function editProduct(productId = null) {

	if(productId) {
		$("#productId").remove();		
		// remove text-error 
		$(".text-danger").remove();
		// remove from-group error
		$(".form-group").removeClass('has-error').removeClass('has-success');
		// modal spinner
		$('.div-loading').removeClass('div-hide');
		// modal div
		$('.div-result').addClass('div-hide');

		$.ajax({
			url: 'php_action/fetchSelectedInventario.php',
			type: 'post',
			data: {productId: productId},
			dataType: 'json',
			success:function(response) {		
				// modal spinner
				$('.div-loading').addClass('div-hide');
				// modal div
				$('.div-result').removeClass('div-hide');				

				$("#getProductImage").attr('src', 'stock/'+response.product_image);

				$("#editProductImage").fileinput({		      
				});  

				// product id 
				$(".editProductFooter").append('<input type="hidden" name="productId" id="productId" value="'+response.id_inventario+'" />');
				$(".editProductPhotoFooter").append('<input type="hidden" name="productId" id="productId" value="'+response.id_inventario+'" />');
				
				// fill fields
                $("#editCodigoInterno").val(response.codigo_interno);
				$("#editProductName").val(response.nombre_articulo);
                $("#editColor").val(response.id_color);
				$("#editQuantity").val(response.cantidad);
                $("#editEstadoActivo").val(response.id_estado);
                $("#editUbicacionEspecifica").val(response.id_ubicacion);
				$("#editRate").val(response.rate);
				$("#editBrandName").val(response.id_marca);
				$("#editCategoryName").val(response.id_categoria);
				$("#editProductStatus").val(response.activo);

				// update the product data function
				$("#editProductForm").unbind('submit').bind('submit', function() {

					// form validation
					var productName = $("#editProductName").val();
					var quantity = $("#editQuantity").val();
					var brandName = $("#editBrandName").val();
					var categoryName = $("#editCategoryName").val();
					var productEstado = $("#editProductEstado").val();
								

					if(productName == "") {
						$("#editProductName").after('<p class="text-danger">El nombre es obligatorio</p>');
						$('#editProductName').closest('.form-group').addClass('has-error');
					}	else {
						$("#editProductName").find('.text-danger').remove();
						$("#editProductName").closest('.form-group').addClass('has-success');	  	
					}

					if(quantity == "") {
						$("#editQuantity").after('<p class="text-danger">La cantidad es obligatoria</p>');
						$('#editQuantity').closest('.form-group').addClass('has-error');
					}	else {
						$("#editQuantity").find('.text-danger').remove();
						$("#editQuantity").closest('.form-group').addClass('has-success');	  	
					}

					if(brandName == "") {
						$("#editBrandName").after('<p class="text-danger">La sede es obligatoria</p>');
						$('#editBrandName').closest('.form-group').addClass('has-error');
					}	else {
						$("#editBrandName").find('.text-danger').remove();
						$("#editBrandName").closest('.form-group').addClass('has-success');	  	
					}

					if(categoryName == "") {
						$("#editCategoryName").after('<p class="text-danger">La categoría es obligatoria</p>');
						$('#editCategoryName').closest('.form-group').addClass('has-error');
					}	else {
						$("#editCategoryName").find('.text-danger').remove();
						$("#editCategoryName").closest('.form-group').addClass('has-success');	  	
					}

					if(productEstado == "") {
						$("#editProductEstado").after('<p class="text-danger">La visibilidad es obligatoria</p>');
						$('#editProductEstado').closest('.form-group').addClass('has-error');
					}	else {
						$("#editProductEstado").find('.text-danger').remove();
						$("#editProductEstado").closest('.form-group').addClass('has-success');
					}

					if(productName && quantity && brandName && categoryName && productEstado) {
						// submit loading button
						$("#editProductBtn").button('loading');

						var form = $(this);
						var formData = new FormData(this);

						$.ajax({
							url : form.attr('action'),
							type: form.attr('method'),
							data: formData,
							dataType: 'json',
							cache: false,
							contentType: false,
							processData: false,
							success:function(response) {
								console.log(response);
								if(response.success == true) {
									// submit loading button
									$("#editProductBtn").button('reset');																		

									$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
																			
									// shows a successful message after operation
									$('#edit-product-messages').html('<div class="alert alert-success">'+
				            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
				            '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
				          '</div>');

									// remove the mesages
				          $(".alert-success").delay(500).show(10, function() {
										$(this).delay(3000).hide(10, function() {
											$(this).remove();
										});
									}); // /.alert

				          // reload the manage student table
									manageProductTable.ajax.reload(null, true);

									// remove text-error 
									$(".text-danger").remove();
									// remove from-group error
									$(".form-group").removeClass('has-error').removeClass('has-success');

								} // /if response.success
								
							} // /success function
						}); // /ajax function
					}	 // /if validation is ok 					

					return false;
				}); // update the product data function

				// update the product image				
				$("#updateProductImageForm").unbind('submit').bind('submit', function() {					
					// form validation
					var productImage = $("#editProductImage").val();					
					
					if(productImage == "") {
						$("#editProductImage").closest('.center-block').after('<p class="text-danger">La imagen es obligatoria</p>');
						$('#editProductImage').closest('.form-group').addClass('has-error');
					}	else {
						$("#editProductImage").find('.text-danger').remove();
						$("#editProductImage").closest('.form-group').addClass('has-success');	  	
					}

					if(productImage) {
						// submit loading button
						$("#editProductImageBtn").button('loading');

						var form = $(this);
						var formData = new FormData(this);

						$.ajax({
							url : form.attr('action'),
							type: form.attr('method'),
							data: formData,
							dataType: 'json',
							cache: false,
							contentType: false,
							processData: false,
							success:function(response) {
								
								if(response.success == true) {
									// submit loading button
									$("#editProductImageBtn").button('reset');																		

									$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
																			
									// shows a successful message after operation
									$('#edit-productPhoto-messages').html('<div class="alert alert-success">'+
				            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
				            '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
				          '</div>');

									// remove the mesages
				          $(".alert-success").delay(500).show(10, function() {
										$(this).delay(3000).hide(10, function() {
											$(this).remove();
										});
									}); // /.alert

				          // reload the manage student table
									manageProductTable.ajax.reload(null, true);

									$(".fileinput-remove-button").click();

									$.ajax({
										url: 'php_action/fetchProductImageUrl.php?i='+productId,
										type: 'post',
										success:function(response) {
										$("#getProductImage").attr('src', response);		
										}
									});																		

									// remove text-error 
									$(".text-danger").remove();
									// remove from-group error
									$(".form-group").removeClass('has-error').removeClass('has-success');

								} // /if response.success
								
							} // /success function
						}); // /ajax function
					}	 // /if validation is ok 					

					return false;
				}); // /update the product image

			} // /success function
		}); // /ajax to fetch product image

				
	} else {
		alert('error please refresh the page');
	}
} // /edit product function

// remove product 
function removeProduct(productId = null) {
	if(productId) {
		// remove product button clicked
		$("#removeProductBtn").unbind('click').bind('click', function() {
			// loading remove button
			$("#removeProductBtn").button('loading');
			$.ajax({
				url: 'php_action/removeProduct.php',
				type: 'post',
				data: {productId: productId},
				dataType: 'json',
				success:function(response) {
					// loading remove button
					$("#removeProductBtn").button('reset');
					if(response.success == true) {
						// remove product modal
						$("#removeProductModal").modal('hide');

						// update the product table
						manageProductTable.ajax.reload(null, false);

						// remove success messages
						$(".remove-messages").html('<div class="alert alert-success">'+
		            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
		            '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
		          '</div>');

						// remove the mesages
	          $(".alert-success").delay(500).show(10, function() {
							$(this).delay(3000).hide(10, function() {
								$(this).remove();
							});
						}); // /.alert
					} else {

						// remove success messages
						$(".removeProductMessages").html('<div class="alert alert-success">'+
		            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
		            '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
		          '</div>');

						// remove the mesages
	          $(".alert-success").delay(500).show(10, function() {
							$(this).delay(3000).hide(10, function() {
								$(this).remove();
							});
						}); // /.alert

					} // /error
				} // /success function
			}); // /ajax fucntion to remove the product
			return false;
		}); // /remove product btn clicked
	} // /if productid
} // /remove product function
