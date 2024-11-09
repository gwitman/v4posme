<!-- ./ page heading -->
<script>		
	var objCallback							= '<?php echo $callback; ?>';
	var objParameterUrlPrinterCode  		 = '<?php echo $objParameterMasive; ?>';
	var objParameterUrlPrinterCodeSinPrecio  = '<?php echo $objParameterMasiveSinPrecio; ?>';
	var objParameterUrlPrinterCodeConPrecio  = '<?php echo $objParameterMasiveConPrecio; ?>';
	var objRowWarehouse 			= {};
	var objRowSku 					= {};
	var objTableDetailProvider 		= {};
	var objTableDetailConcept 		= {};
	var site_url 	  				= "<?php echo base_url(); ?>/";
	var userMobile					= '<?php echo $useMobile; ?>';
	
	
	//este evento es util cuando la pantalla se ejecuta desde la pantalla de facturacion
	if(objCallback != 'false'){
		$(window).unload(function() {
			//do something
			window.opener.<?php echo $callback; ?>(); 
		});
	}
	
	$(document).ready(function(){
		objTableDetailProvider = $("#table_provider").dataTable({
			"bPaginate"		: false,
			"bFilter"		: false,
			"bSort"			: false,
			"bInfo"			: false,
			"bAutoWidth"	: false,
			"aaData": [		
				<?php 
					$listprovider = [];
					if($objListProvider){
						foreach($objListProvider as $i)
						{
						$listprovider[] = "[0,".$i->entityID.",'".$i->providerNumber."','".$i->firstName." ".$i->comercialName."']";
						}
						echo implode(",",$listprovider);
					}
				?>
			],
			"aoColumnDefs": [ 
						{
							"aTargets"	: [ 0 ],//checked
							"mRender"	: function ( data, type, full ) {
								if (data == false)
								return '<input type="checkbox"  class="classCheckedDetail"  value="0" ></span>';
								else
								return '<input type="checkbox"  class="classCheckedDetail" checked="checked" value="0" ></span>';
							}
						},
						{
							"aTargets"		: [ 1 ],//entityID
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtProviderEntityID[]" />';
							}
						}
			]							
		});
		
		
		objTableDetailConcept = $("#table_concept").dataTable({
			"bPaginate"		: false,
			"bFilter"		: false,
			"bSort"			: false,
			"bInfo"			: false,
			"bAutoWidth"	: false,
			"aaData": [		
				<?php 
					$listconcept = [];
					if($objListConcept){
						foreach($objListConcept as $i)
						{
							$listconcept[] = "[0,'".$i->name."',".$i->valueIn.",".$i->valueOut."]";
						}
						echo implode(",",$listconcept);
					}
				?>
			],
			"aoColumnDefs": [ 
						{
							"aTargets"	: [ 0 ],//checked
							"mRender"	: function ( data, type, full ) {
								if (data == false)
								return '<input type="checkbox"  class="classCheckedDetailConcept"  value="0" ></span>';
								else
								return '<input type="checkbox"  class="classCheckedDetailConcept" checked="checked" value="0" ></span>';
							}
						},
						{
							"aTargets"		: [ 1 ],//name
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12" value="'+data+'" name="txtDetailConceptName[]" />';
							}
						},
						{
							"aTargets"		: [ 2 ],//valueIn
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12" value="'+data+'" name="txtDetailConceptValueIn[]" />';
							}
						},
						{
							"aTargets"		: [ 3 ],//valueOut
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12" value="'+data+'" name="txtDetailConceptValueOut[]" />';
							}
						}
			]							
		});
		refreschChecked();
		
		
		//Evento Regresar a la lista
		$(document).on("click","#btnBack",function(){
			fnWaitOpen();
		});
		
		//Guardar
		$(document).on("click","#btnAcept",function(){
				$( "#form-new-account-journal" ).attr("method","POST");
				$( "#form-new-account-journal" ).attr("action","<?php echo base_url(); ?>/app_inventory_item/save/edit/item/null/dataSession/null");
				
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
		
		$(document).on("dblclick","#btnPrinterCode",function(){
			var price 	= <?php echo $objListPriceItemFirst; ?>;			
			var url 	= objParameterUrlPrinterCode+"/listItem/0-0-0-0-0-0|"+"<?php echo $objItem->itemID."-1-".$objItem->itemNumber."-".$objItem->name."-".$objItem->barCode."-"; ?>"+price;
			window.open(url, "_blank");	
		});
		
		$(document).on("click","#btnPrinterSinPrecio",function(){
			var price 	= <?php echo $objListPriceItemFirst; ?>;			
			var url 	= objParameterUrlPrinterCodeSinPrecio+"/listItem/0-0-0-0-0-0|"+"<?php echo $objItem->itemID."-1-".$objItem->itemNumber."-".$objItem->name."-".$objItem->barCode."-"; ?>"+price;
			window.open(url, "_blank");	
		});
		
		$(document).on("click","#btnPrinterdConPrecio",function(){
			var price 	= <?php echo $objListPriceItemFirst; ?>;			
			var url 	= objParameterUrlPrinterCodeConPrecio+"/listItem/0-0-0-0-0-0|"+"<?php echo $objItem->itemID."-1-".$objItem->itemNumber."-".$objItem->name."-".$objItem->barCode."-"; ?>"+price;
			window.open(url, "_blank");	
		});
		
		
		$(document).on("click","#btnDelete",function(){							
			fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
				fnWaitOpen();
				$.ajax({									
					cache       : false,
					dataType    : 'json',
					type        : 'POST',
					url  		: "<?php echo base_url(); ?>/app_inventory_item/delete",
					data 		: {companyID : <?php echo $objItem->companyID;?>, itemID : <?php echo $objItem->itemID;?>  },
					success:function(data){
						console.info("complete delete success");
						fnWaitClose();
						if(data.error){
							fnShowNotification(data.message,"error");
						}
						else{
							window.location = "<?php echo base_url(); ?>/app_inventory_item/index";
						}
					},
					error:function(xhr,data){	
						console.info("complete delete error");									
						fnWaitClose();
						fnShowNotification("Error 505","error");
					}
				});
			});
		});
		//Ir a Archivo
		$(document).on("click","#btnClickArchivo",function(){
			window.open("<?php echo base_url()."/core_elfinder/index/componentID/".$objComponent->componentID."/componentItemID/".$objItem->itemID; ?>","blanck");
		});
		
		//Agregar Concepto
		$(document).on("click","#btnNewDetailConcept",function(){	
			var url_request = "<?php echo base_url(); ?>/app_inventory_item/popup_add_concept"; 
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteConcept = onCompleteConcept; 
		});
		
		//Seleccionar Concepto 
		$(document).on("click",".classCheckedDetailConcept",function(){
			var objrow_ = $(this).parent().parent().parent().parent()[0];
			var objind_ = objTableDetailConcept.fnGetPosition(objrow_);
			var objdat_ = objTableDetailConcept.fnGetData(objind_);								
			objTableDetailConcept.fnUpdate( !objdat_[0], objind_, 0 );
			refreschChecked();
		});
		
		//Eliminar Concepto
		$(document).on("click","#btnDeleteDetailConcept",function(){
			var listRow = objTableDetailConcept.fnGetData();							
			var length 	= listRow.length;
			var i 		= 0;
			var j 		= 0;
			while (i< length ){
				if(listRow[i][0] == true){
				objTableDetailConcept.fnDeleteRow( j,null,true );
				j--;
				}
				i++;
				j++;
			}
		});
		
		//Agregar Proveedor
		$(document).on("click","#btnNewDetailProvider",function(){	
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $componentProviderID; ?>/onCompleteProvider/SELECCIONAR_PROVEEDOR/true/empty/false/not_redirect_when_empty"; 
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteProvider = onCompleteProvider; 
		});
		//Seleccionar Proveedor 
		$(document).on("click",".classCheckedDetail",function(){
			var objrow_ = $(this).parent().parent().parent().parent()[0];
			var objind_ = objTableDetailProvider.fnGetPosition(objrow_);
			var objdat_ = objTableDetailProvider.fnGetData(objind_);								
			objTableDetailProvider.fnUpdate( !objdat_[0], objind_, 0 );
			refreschChecked();
		});
		//Eliminar Proveedor
		$(document).on("click","#btnDeleteDetailProvider",function(){
			var listRow = objTableDetailProvider.fnGetData();							
			var length 	= listRow.length;
			var i 		= 0;
			var j 		= 0;
			while (i< length ){
				if(listRow[i][0] == true){
				objTableDetailProvider.fnDeleteRow( j,null,true );
				j--;
				}
				i++;
				j++;
			}
		});
		
		//Nueva Bodega
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
		$(document).on("click","#btnNewDetailSku",function(){
				var objData 								= {};
				objData.txtDetailSkuID						= 0;
				objData.txtDetailSkuItemID					= 0;
				objData.txtDetailSkuCatalogItemID 		 	= $("#txtTempSkuID").val();								
				objData.skuDescription 						= $("#txtTempSkuID option:selected").text();
				objData.txtDetailSkuValue					= $("#txtTmpSkuCantidad").val();
				
				var objHtml						= $.tmpl($("#tmpl_row_sku").html(),objData);
				
				if($("input[value="+objData.txtDetailSkuCatalogItemID+"].txtDetailSkuCatalogItemID").length > 0 )
				return;
				
				$("#body_detail_sku").append(objHtml);
		});
		//Eliminar Bodega
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
				objRowSku = this;
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
		
		<?php echo getBehavio($company->type,"app_inventory_item","scriptValidate",""); ?>
		
		return result;
	}
	function onCompleteConcept(objResponse){
			console.info("CALL onCompleteConcept");
			var objRow 					= {};
			objRow.checked 				= false;
			objRow.name 				= objResponse.txtNameConcept;
			objRow.valueIn				= objResponse.txtValueIn;
			objRow.valueOut				= objResponse.txtValueOut;
			
			//Berificar que el Item ya esta agregado 
			if(jLinq.from(objTableDetailConcept.fnGetData()).where(function(obj){ return obj[1] == objRow.name;}).select().length > 0 ){
				fnShowNotification("El Concepto ya esta agregado","error");
				return;
			}
			
			objTableDetailConcept.fnAddData([objRow.checked,objRow.name,objRow.valueIn,objRow.valueOut]);
			refreschChecked();
			
	}
	
	function onCompleteProvider(objResponse){
			console.info("CALL onCompleteProvider");
			var objRow 						= {};
			objRow.checked 					= false;
			objRow.providerID 				= objResponse[0][1];
			objRow.providerNumber			= objResponse[0][2];
			objRow.providerName				= objResponse[0][3];
			
			//Berificar que el Item ya esta agregado 
			if(jLinq.from(objTableDetailProvider.fnGetData()).where(function(obj){ return obj[1] == objRow.providerID;}).select().length > 0 ){
				fnShowNotification("El Proveedor ya esta agregado","error");
				return;
			}
			
			objTableDetailProvider.fnAddData([objRow.checked,objRow.providerID,objRow.providerNumber,objRow.providerName]);
			refreschChecked();
			
	}
	function refreschChecked(){
		$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
	}
	
</script>
