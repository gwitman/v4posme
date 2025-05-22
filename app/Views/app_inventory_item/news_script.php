<!-- ./ page heading -->
<script>
	var objCallback											= '<?php echo $callback; ?>';
	var app_inventory_item_add_producto_al_detalle_compra	= '<?php echo $app_inventory_item_add_producto_al_detalle_compra; ?>';

	var objRowWarehouse 		= {};
	var objRowSku 				= {};
	var site_url 	  			= "<?php echo base_url(); ?>/";
	var userMobile				= '<?php echo $useMobile; ?>';


	//agregar producto al listado de compra
	if(objCallback != 'false' && app_inventory_item_add_producto_al_detalle_compra != '')
	{

		var dataProductoNew 	= [];
		var dataProductoItem 	= [];
		dataProductoItem 		= app_inventory_item_add_producto_al_detalle_compra.split("|");
		dataProductoNew.push(dataProductoItem);
		window.opener.onCompleteItem(dataProductoNew);
	}

	//este evento es util cuando la pantalla se ejecuta desde la pantalla de facturacion
	if(objCallback != 'false'){
		$(window).unload(function() {
			//do something
			window.opener.<?php echo $callback; ?>();
		});
	}

	$(document).ready(function(){

        <?php echo getBehavio($company->type, "app_inventory_item", "divBirthday", "");?>

		 //Regresar a la lista
		$(document).on("click","#btnBack",function(){
				fnWaitOpen();
		});
		//Evento Agregar el Usuario
		$(document).on("click","#btnAcept",function(){
				$( "#form-new-account-journal" ).attr("method","POST");
				$( "#form-new-account-journal" ).attr("action","<?php echo base_url(); ?>/app_inventory_item/save/new/item/null/dataSession/null");

				if(validateForm()){
					fnWaitOpen();
					$( "#form-new-account-journal" ).submit();
				}

		});

		//Buscar Colagorador
		$(document).on("click","#btnSearchEmployer",function(){
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentEmployer->componentID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE/true/empty/false/not_redirect_when_empty";
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteEmployee = onCompleteEmployee;
		});
		//Eliminar Colaborador
		$(document).on("click","#btnClearEmployer",function(){
					$("#txtEmployerID").val("");
					$("#txtEmployerDescription").val("");
		});
		$(document).on("click","#btnNewDetailWarehouse",function(){
				var objData 					= {};
				objData.warehouseID 		 	= $("#txtTempWarehouseID").val();
				objData.warehouseDescription 	= $("#txtTempWarehouseID option:selected").text();
				objData.quantityMax 			= $("#txtTmpDetailQuantityMax").val();
				objData.quantityMin 			= $("#txtTmpDetailQuantityMin").val();
				var objHtml						= $.tmpl($("#tmpl_row_warehouse").html(),objData);

				if($("input[value="+objData.warehouseID+"].txtDetailWarehouseID").length > 0 )
				return;

				$("#body_detail_warehouse").append(objHtml);
		});

		//Agregar SKU
		$(document).on("click","#btnNewDetailSku",function(e){
			e.preventDefault();
		    const parseValue = (val) => {
		        if (val === '' || val === null) return NaN;
		        return parseFloat(val);
		    };
			// Validar que se haya seleccionado un SKU
		    if ($('#txtTempSkuID').val() === null || $('#txtTempSkuID').val() === '') {
		        fnShowNotification('Debe seleccionar un SKU', 'error', 1500);
		        return false;
		    }

		    // Validar que se haya ingresado una cantidad
		    if ($('#txtTmpSkuCantidad').val() === '') {
		        fnShowNotification('Debe ingresar una cantidad de SKU', 'error', 1500);
		        return false;
		    }

		    // Validar que la cantidad sea numérica
		    if (isNaN($('#txtTmpSkuCantidad').val())) {
		        fnShowNotification('La cantidad debe ser un número valido', 'error', 1500);
		        return false;
		    }

			// Validar que se haya ingresado un precio
		    if ($('#txtTmpSkuPrecio').val() === '') {
		        fnShowNotification('Debe ingresar un precio de SKU', 'error', 1500);
		        return false;
		    }

		    // Validar que el precio sea numérico
		    if (isNaN($('#txtTmpSkuPrecio').val())) {
		        fnShowNotification('El precio debe ser un número valido', 'error', 1500);
		        return false;
		    }

		    // Validación del SKU con valor 1
		    let countOnes = 0;
		    const newValue = parseValue($('#txtTmpSkuCantidad').val());

		    // Contar los unos existentes
		    $('input.txtDetailSkuValue').each(function() {
		    	let skuValue = parseValue($(this).val());
		        if (skuValue === 1) countOnes++;
		    });

		    // Si el nuevo valor es 1, sumarlo al conteo
		    if (newValue === 1) countOnes++;

		    // Validar que no haya más de un 1
		    if (countOnes > 1) {
		        fnShowNotification('Solo puede haber un SKU con valor 1', 'error', 1500);
		        return false;
		    }

			var objData 								= {};
			objData.txtDetailSkuID						= 0;
			objData.txtDetailSkuItemID					= 0;
			objData.txtDetailSkuCatalogItemID 		 	= $("#txtTempSkuID").val();
			objData.skuDescription 						= $("#txtTempSkuID option:selected").text();
			objData.txtDetailSkuValue					= $("#txtTmpSkuCantidad").val();
			objData.txtDetailSkuPrecio					= $("#txtTmpSkuPrecio").val();
			objData.txtDetailSkuDefault					= $("#txtTmpSkuDefault").is(":checked");

			// Si el nuevo viene con "checked", quita todos los anteriores marcados
			if (objData.txtDetailSkuDefault) {
				$(".sku-default").prop('checked', false).parent().removeClass('checked');
			}

			var objHtml	= $.tmpl($("#tmpl_row_sku").html(), objData);

			if($("input[value="+objData.txtDetailSkuCatalogItemID+"].txtDetailSkuCatalogItemID").length > 0 ){
				fnShowNotification('Ya existe el SKU en la tabla', 'error', 1500);
				return false;
			}

			$("#body_detail_sku").append(objHtml);
			refreschChecked();
			$('#txtTmpSkuCantidad').val('');
			$('#txtTmpSkuPrecio').val('');
			$('#txtTmpSkuDefault').prop('checked', false).parent().removeClass('checked');
			$('#txtTempSkuID').val(null).trigger('change');
		});

		$(document).on('change', '.sku-default', function () {
			if ($(this).is(':checked')) {
				// Este está marcado → poner valor 1
				$(this).val('1');
				$(this).closest('tr').find('.txtDetailSkuDefault').val('1');
				$(this).closest('tr').find('.txtTmpSkuDefault').val('1');
				// Desmarcar los demás
				$('.sku-default').not(this).each(function () {
					$(this).prop('checked', false).val('0');
					$(this).closest('tr').find('.txtDetailSkuDefault').val('0');
					$(this).closest('tr').find('.txtTmpSkuDefault').val('0');
					
					// Si hay clases visuales
					$(this).parent().removeClass('checked row-selected');
				});

				// Agregar clase visual si aplica
				$(this).parent().addClass('row-selected');

			} else {
				// Si lo desmarcan, poner en 0
				$(this).val('0');
				$(this).closest('tr').find('.txtDetailSkuDefault').val('0');
				$(this).parent().removeClass('row-selected');
			}
		});

		$(document).on("click","#btnDeleteDetailWarehouse",function(){
				var quantity = $(objRowWarehouse).find(".txtDetailQuantity").val();
				if(quantity == undefined)
				return;

				quantity  = parseFloat(quantity);
				if(quantity > 0)
				return;

				objRowWarehouse.remove();
		});
		//Eliminar SKU
		$(document).on("click","#btnDeleteDetailSku",function(){
				objRowSku.remove();
		});
		//Seleccionar Bodega
		$(document).on("click",".row_warehouse",function(event){
				objRowWarehouse = this;
				fnTableSelectedRow(this,event);
		});
		//Seleccionar sku
		$(document).on("click",".row_sku",function(event){
				objRowSku	 = this;
				fnTableSelectedRow(this,event);
		});
		$("#txtCountryID").change(function(){
			fnWaitOpen();
			$.ajax({
				cache       : false,
				dataType    : 'json',
				type        : 'POST',
				data 		: { catalogItemID : $(this).val() },
				url  		: "<?php echo base_url(); ?>/app_catalog_api/getCatalogItemByState",
				success:function(data){
					console.info("call app_catalog_api/getCatalogItemByState")
					fnWaitClose();


					$("#txtStateID").html("");
					$("#txtStateID").append("<option value=''>N/D</option>");

					if(userMobile != '1')
					$("#txtStateID").select2();

					$("#txtCityID").html("");

					if(userMobile != '1')
					$("#txtCityID").select2();


					if(data.catalogItems == null)
					return;

					$.each(data.catalogItems,function(i,obj){
						$("#txtStateID").append("<option value='"+obj.catalogItemID+"'>"+obj.name+"</option>");
					});
				},
				error:function(xhr,data){
					fnShowNotification(data.message,"error");
					fnWaitClose();
				}
			});
		});
		$("#txtStateID").change(function(){
			fnWaitOpen();
			$.ajax({
				cache       : false,
				dataType    : 'json',
				type        : 'POST',
				data 		: { catalogItemID : $(this).val() },
				url  		: "<?php echo base_url(); ?>/app_catalog_api/getCatalogItemByCity",
				success:function(data){
					console.info("call app_catalog_api/getCatalogItemByCity")
					fnWaitClose();
					$("#txtCityID").html("");
					$("#txtCityID").append("<option value=''>N/D</option>");

					if(userMobile != '1')
					$("#txtCityID").select2();

					if(data.catalogItems == null)
					return;

					$.each(data.catalogItems,function(i,obj){
						$("#txtCityID").append("<option value='"+obj.catalogItemID+"'>"+obj.name+"</option>");
					});
				},
				error:function(xhr,data){
					fnShowNotification(data.message,"error");
					fnWaitClose();
				}
			});
		});

	});

	function onCompleteEmployee(objResponse)
	{
			$("#txtEmployerID").val(objResponse[0][2]);
			$("#txtEmployerDescription").val(objResponse[0][3] + " / " + objResponse[0][4]);
	}

	function refreschChecked()
	{		
		$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect, .skuStyleNormal').uniform();
	}

	function validateForm(){
		var result 				= true;
		var timerNotification 	= 15000;

		//Nombre
		if($("#txtName").val()==""){
			fnShowNotification("El nombre no puede estar vacio","error",timerNotification);
			result = false;
		}
		//Validar Estado
		if($("#txtStatusID").val() == ""){
			fnShowNotification("Establecer Estado","error",timerNotification);
			result = false;
		}
		//Categoria
		if($("#txtInventoryCategoryID").val() == ""){
			fnShowNotification("Seleccione una categoria","error",timerNotification);
			result = false;
		}
		//Bodega por Defecto
		if($("#txtDefaultWarehouseID").val() == ""){
			fnShowNotification("Seleccione una bodega por defecto","error",timerNotification);
			result = false;
		}
		//Unidad de Medida
		if($("#txtUnitMeasureID").val() == ""){
			fnShowNotification("Seleccione la unidad de medida","error",timerNotification);
			result = false;
		}
		//La bodega por defecto debe de estar en las bodegas asociadas
		if($("input[value="+$("#txtDefaultWarehouseID").val()+"].txtDetailWarehouseID").length == 0 ){
			fnShowNotification("La bodega que esta pordefecto debe de estar en el detalle de Bodegas","error",timerNotification);
			result = false;
		}

		var hasChecked = $('.sku-default:checked').length > 0;

		if (!hasChecked) {
			fnShowNotification("Debe de existir un SKU prdeterminado","error",timerNotification);
			result = false;
		}
		
		<?php echo getBehavio($company->type, "app_inventory_item", "scriptValidate", ""); ?>

		return result;
	}
</script>
<!--posMeHelp-->
