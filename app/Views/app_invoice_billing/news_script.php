<!-- ./ page heading -->
<script>					
	fnWaitOpen();
	var objTableDetail 						= {};	
	var objListaProductos					= {};
	var objListaProductos2					= {};
	var objListaProductos3					= {};
	var objListaProductosSku				= {};
	var openedSearchWindow					= false;
	var objWindowSearchProduct				= {};
	var warehouseID 						= $("#txtWarehouseID").val();
	var objTableProductosSearch 			= null;
	var objRowTableProductosSearch 			= null;	
	var varAutoAPlicar						= '<?php echo $objParameterInvoiceAutoApply; ?>';
	var varPermitirFacturarProductosEnZero	= '<?php echo $objParameterInvoiceBillingQuantityZero; ?>';
	var varParameterImprimirPorCadaFactura	= '<?php echo $objParameterImprimirPorCadaFactura; ?>';
	var varPermisos							= JSON.parse('<?php echo json_encode($objListaPermisos); ?>');
	var varPermisosEsPermitidoModificarPrecio = 
		jLinq.from(varPermisos).where(function(obj){ return obj.display == "ES_PERMITIDO_MODIFICAR_PRECIO_EN_FACTURACION"}).select().length > 0 ?
		true:
		false;
	var PriceStatus = varPermisosEsPermitidoModificarPrecio == true ? "":"readonly";
		
	//Incializar Focos
	document.getElementById("txtScanerCodigo").focus();
	
	
	
		
	//crear la cache intervalo 
	var objListaProductosStore 	= localStorage.getItem("objListaProductos");		
	objListaProductos 			= JSON.parse(objListaProductosStore);
	
	var objListaProductosStore2 	= localStorage.getItem("objListaProductos2");		
	objListaProductos2 				= JSON.parse(objListaProductosStore2);
	
	var objListaProductosStore3 	= localStorage.getItem("objListaProductos3");		
	objListaProductos3	 			= JSON.parse(objListaProductosStore3);
	
	var objListaProductosStoreSku	= localStorage.getItem("objListaProductosSku");		
	objListaProductosSku 			= JSON.parse(objListaProductosStoreSku);	
	
	
	if(objListaProductosStore == null ){
		fnObtenerListadoProductos();
		fnObtenerListadoProductos2();
		fnObtenerListadoProductos3();
		fnObtenerListadoProdcutosSku();
		fnGetCustomerClient();
		setTimeout( function() { fnWaitClose(); }, 1000);
	}
	//No actualizar datos
	else{		
		fnGetCustomerClient(); 
		setTimeout( function() { fnWaitClose(); }, 20);
	}


	

	$(document).ready(function(){			 
		 $('#txtDate').datepicker({format:"yyyy-mm-dd"});
		 $('#txtDate').val(moment().format("YYYY-MM-DD"));	
		 $("#txtDate").datepicker("update");
		 $('#txtDateFirst').datepicker({format:"yyyy-mm-dd"});						 
		 $('#txtDateFirst').val(moment().add('days', 15).format("YYYY-MM-DD"));			 
		 $("#txtDateFirst").datepicker("update");
		 $("html, body").animate({scrollTop:  $("body").height()  +"px"});
		 
		objTableDetail = $("#tb_transaction_master_detail").dataTable({
			"bPaginate"		: false,
			"bFilter"		: false,
			"bSort"			: false,
			"bInfo"			: false,
			"bAutoWidth"	: false,
			"aoColumnDefs": [ 
						{
							"aTargets"		: [ 0 ],//checked
							"mRender"		: function ( data, type, full ) {
								if (data == false)
								return '<input type="checkbox"  class="classCheckedDetail"  value="0" ></span>';
								else
								return '<input type="checkbox"  class="classCheckedDetail" checked="checked" value="0" ></span>';
							}
						},
						{
							"aTargets"		: [ 1 ],//transactionMasterDetailID
							"bVisible"  	: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtTransactionMasterDetailID[]" />';
							}
						},
						{
							"aTargets"		: [ 2 ],//itemID
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtItemID[]" />';
							}
						},
						{
							"aTargets"		: [ 4 ],//descripcion
							"sWidth" 		: "40%"
						},
						{
							"aTargets"		: [ 5 ],//Sku
							"mRender"		: function ( data, type, full ) {
								
								var objListaSkuByProducto 	= jLinq.from(objListaProductosSku).where(function(obj){ return obj.itemID == full[2]; }).select();
								var sel 					= '';
								sel 						= '<select name="txtSku[]" id="txtSku'+full[2]+'" class="txtSku" >';									
								
								if(objListaSkuByProducto.length == 0)
								{
									sel = sel + '<option value="0" data-skuv="1" data-skupriceunitary="'+full[7]+'"   selected style="font-size:200%" >UNIDAD</option>';
								}
								else{
									for(var ix = 0 ; ix < objListaSkuByProducto.length ; ix++)
									{
										if(objListaSkuByProducto[ix].catalogItemID == data)
											sel = sel + '<option value="'+objListaSkuByProducto[ix].catalogItemID+'" data-skuv="'+objListaSkuByProducto[ix].Valor+'" data-skupriceunitary="'+full[7]+'"  style="font-size:200%" selected >'+objListaSkuByProducto[ix].Sku+'</option>';
										else
											sel = sel + '<option value="'+objListaSkuByProducto[ix].catalogItemID+'" data-skuv="'+objListaSkuByProducto[ix].Valor+'" data-skupriceunitary="'+full[7]+'"  style="font-size:200%"  >'+objListaSkuByProducto[ix].Sku+'</option>';
									}																				
								}
								
								sel = sel + '</select>';					
								return sel;
										
							}
						},
						{
							"aTargets"		: [ 6 ],//Cantidad
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtQuantity" id="txtQuantityRow'+full[2]+'"  value="'+data+'" name="txtQuantity[]" style="text-align:right" />';
							}
						},
						{
							"aTargets"		: [ 7 ],//Precio
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtPrice"   id="txtPriceRow'+full[2]+'"   '+PriceStatus+'  value="'+data+'" name="txtPrice[]" style="text-align:right" />';
							}
						},
						{
							"aTargets"		: [ 8 ],//Total
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtSubTotal" readonly value="'+data+'" name="txtSubTotal[]" style="text-align:right" />';
							}
						},
						{
							"aTargets"		: [ 9 ],//Iva
							"bVisible"		: true,
							"sClass"		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtIva" value="'+data+'" name="txtIva[]" style="text-align:right" />';
							}
						},
						{
							"aTargets"		: [ 10 ],//skuQuantityBySku
							"bVisible"		: true,
							"sClass"		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 skuQuantityBySku" value="'+data+'" name="skuQuantityBySku[]" style="text-align:right" />';
							}
						},
						{
							"aTargets"		: [ 11 ],//unitaryPriceInvidual
							"bVisible"		: true,
							"sClass"		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 unitaryPriceInvidual" value="'+data+'" name="unitaryPriceInvidual[]" style="text-align:right" />';
							}
						}
			]							
		});
		
		
		
			
		$(document).on("click","#btnAddProductoOnLine",function(){
				
				
				/*
				var objRow 							= {};		
				objRow.checked 						= false;						
				objRow.transactionMasterDetailID 	= 0;
				objRow.itemID						= objResponse[5];
				objRow.codigo						= objResponse[17];
				objRow.description					= objResponse[18].toLowerCase();
				objRow.um							= objResponse[20];
				objRow.quantity 					= fnFormatNumber(1,2);
				objRow.bquantity 					= fnFormatNumber(objResponse[21],2);
				objRow.price 						= fnFormatNumber(objResponse[22],2);
				objRow.total 						= fnFormatNumber(objRow.quantity * objRow.price,2);						
				objRow.iva 							= 0;
				objRow.lote 						= "";
				objRow.vencimiento					= "";				
				*/
				
				/*
				filterResult[i].itemID,
				filterResult[i].Codigo,
				"'"+filterResult[i].Nombre+"'",
				filterResult[i].Medida,
				filterResult[i].Cantidad,
				filterResult[i].Precio,
				filterResult[i].Barra,
				*/
				
				var data		 = {};					
				var dataResponse = [];
				data			 = objTableProductosSearch.fnGetData(objRowTableProductosSearch);				
				dataResponse[0] = data[0];
				dataResponse[1] = data[0];
				dataResponse[2] = data[0];
				dataResponse[3] = data[0];
				dataResponse[4] = data[0];
				dataResponse[5] = data[0]; //itemID
				dataResponse[6] = data[0];
				dataResponse[7] = data[0];
				dataResponse[8] = data[0];
				dataResponse[9] = data[0];
				dataResponse[10] = data[0];
				dataResponse[11] = data[0];
				dataResponse[12] = data[0];
				dataResponse[13] = data[0];
				dataResponse[14] = data[0];
				dataResponse[15] = data[0];
				dataResponse[16] = data[0];
				dataResponse[17] = data[1];//Codigo
				dataResponse[18] = data[2];//Nombre
				dataResponse[19] = data[0];
				dataResponse[20] = data[3];//Unidad de medida
				dataResponse[21] = data[4];//Cantidad
				dataResponse[22] = data[5];//Precio
				dataResponse[23] = data[0];
				dataResponse[24] = data[0];
				dataResponse[25] = data[0];
				dataResponse[26] = data[0];
				
				onCompleteNewItem(dataResponse,true);

				
		});						
	
	
		
		
		
		$(document).on("keypress",'#txtReceiptAmount', function(e) {	
			var code = e.keyCode || e.which;
			 if(code != 13) { 
			   	 return;
			 }		 
			 
			document.getElementById("txtReceiptAmountDol").focus();
			return;
				
		});
		
		
		$(document).on("keypress",'#txtReceiptAmountDol', function(e) {		
		
			var code = e.keyCode || e.which;			
			if(code != 13) { 
			   	 return;
			}					 
			fnEnviarFactura();
			return;
			 
		});
		$(document).on("keydown",'#txtReceiptAmountDol', function(e) {		
		
			var code = e.keyCode || e.which;			
			//Enviar Factura
			if(e.key === "a"  && e.ctrlKey) { 													
			   	fnEnviarFactura();				 
			}		
			
			//Regresar al scaner
			if(e.key === "b"  && e.ctrlKey) { 		
				document.getElementById("txtScanerCodigo").focus();				
			}		
			
			
			//e.preventDefault();
			//e.stopPropagation();
			return;
		});
		
		
		
		
		$(document).on("keypress","#table_list_productos_filter > label > input[type='text']", function(e) {	
			 
			 
			 //buscar el primer rgistro que se encuetre
			 var element 		= $("#table_list_productos_filter > label > input[type='text']").val();		
			 
			 var code = e.keyCode || e.which;
			 
			 /*si la tecla precionada no es +, agregar los caracteres al control*/
			 if(code != 43) { 
				$("#table_list_productos_detail tr.row-selected").removeClass("row-selected");
			   	 return;
			 }	
			 
			 
			 //buscar
			 var elementr 		= $("#table_list_productos_filter > label > input[type='text']").val("");
			 
			 //Obtener el primer reigstro y agregar
			 var elementoTr 	= $("#table_list_productos_detail tr.row-selected")[0];
			 objRowTableProductosSearch = elementoTr; 
			 fnTableSelectedRow(this,event);
			 
			 
			 var data		 	= {};					
			 var dataResponse 	= [];
			 data			 	= objTableProductosSearch.fnGetData(objRowTableProductosSearch);				
			 dataResponse[0] = data[0];
			 dataResponse[1] = data[0];
			 dataResponse[2] = data[0];
			 dataResponse[3] = data[0];
			 dataResponse[4] = data[0];
			 dataResponse[5] = data[0]; //itemID
			 dataResponse[6] = data[0];
			 dataResponse[7] = data[0];
			 dataResponse[8] = data[0];
			 dataResponse[9] = data[0];
			 dataResponse[10] = data[0];
			 dataResponse[11] = data[0];
			 dataResponse[12] = data[0];
			 dataResponse[13] = data[0];
			 dataResponse[14] = data[0];
			 dataResponse[15] = data[0];
			 dataResponse[16] = data[0];
			 dataResponse[17] = data[1];//Codigo
			 dataResponse[18] = data[2];//Nombre
			 dataResponse[19] = data[0];
			 dataResponse[20] = data[3];//Unidad de medida
			 dataResponse[21] = data[4];//Cantidad
			 dataResponse[22] = data[5];//Precio
			 dataResponse[23] = data[0];
			 dataResponse[24] = data[0];
			 dataResponse[25] = data[0];
			 dataResponse[26] = data[0];
			 
			 onCompleteNewItem(dataResponse,true);
			 $(this).focus();
			 $(this).val("");
			 e.preventDefault();
			 
		});
		$(document).on("keydown","#table_list_productos_filter > label > input[type='text']", function(e) {	
			 
			 
			 //Obtener la tabla
			 var element 		= $("#table_list_productos_detail");			 
			 var code 			= e.keyCode || e.which;
			 var selecte 		= element.find("tr.row-selected").length;
			 var rowselected 	= element.find("tr.row-selected")[0];
			 var firstrow		= element.children('tr:first');
			 var lastrow		= element.children('tr:last');
			 
			 
			 if(selecte == 0){
				 firstrow.addClass("row-selected");
				 return;
			 }
			 
			 //hacia abajo
			 if(code == 40) { 
			    $(rowselected).removeClass("row-selected");
				$(rowselected).next().addClass("row-selected");
			   	 return;
			 }	
			 
			 //hacia arriba
			 if(code == 38) { 
				$(rowselected).removeClass("row-selected");
				$(rowselected).prev().addClass("row-selected");
			   	return;
			 }	
			 
			 //Obtener el registro seleccionado
			 var rowselected 	= element.find("tr.row-selected")[0];
			 
		});
			 
			 
		$('#mi_modal').on('hidden.bs.modal', function (e) {
			document.getElementById("txtScanerCodigo").focus();	
		});
		

		$(document).on("keydown",'#txtScanerCodigo', function(e) {
			
			
			var code = e.keyCode || e.which;						
			
			//Nueva
			if(e.key === "k" && e.ctrlKey) { 		
				e.preventDefault();
				e.stopPropagation();			
			   	window.location = "<?php echo base_url(); ?>/app_invoice_billing/add";			 
			}
			
			//Abrir Caja
			if(e.key === "i" && e.ctrlKey) 
			{ 		

				$.ajax({									
					cache       : false,
					dataType    : 'json',
					type        : 'POST',
					url  		: "<?php echo base_url(); ?>/app_invoice_billing/viewPrinterOpen",
					data 		: {companyID : 2 },
					success:function(data){
						console.info("complete delete success");
						fnWaitClose();
						if(data.error){
							fnShowNotification(data.message,"error");
						}
						else{
							window.location = "<?php echo base_url(); ?>/app_invoice_billing/index";
						}
					},
					error:function(xhr,data){	
						console.info("complete delete error");									
						fnWaitClose();
						fnShowNotification("Error 505","error");
					}
				});
				e.preventDefault();
				e.stopPropagation();
			}

			
			
			
		});
		
		
		$(document).on("keypress",'#txtScanerCodigo', function(e) {
			
			var code = e.keyCode || e.which;
			 if(code != 13) { 
			   	 return;
			 }		 
			
			
			var codigoABuscar = $("#txtScanerCodigo").val();
			$("#txtScanerCodigo").val("");
			
			
			
			
			//++Abrir popup de productos
			if(codigoABuscar == "++"){
				
				var ventana_ancho = $(window).width()-50;
				var ventana_alto = $(window).height();
				$("#div-modal-dialog-lista-productos").css("width",ventana_ancho+"px");
			
				fnCreateTableSearchProductos();
				$("#mi_modal").modal();			
				setTimeout(function() { $($("#table_list_productos_filter").find("input")[0]).focus(); }, 500);								
				return;
			}
			
			//Mover a ingreso de dinero Cordoba
			if(codigoABuscar == ""){
				document.getElementById("txtReceiptAmount").focus();
				$("#txtReceiptAmount").val("");
				return;
			}
			
			
			
			//buscar el producto y agregar
			var filterResult = {};
			
			//buscar producto por codigo de barra autimatico
			//precio 1 ---> 154 --> precio publico
			if($("#txtTypePriceID").val() == 154){
				filterResult = jLinq.from(objListaProductos).where(function(obj){ return obj["Barra"] == codigoABuscar}).select();
			}
			//precio 2 ---> 155 --> precio mayorista
			if($("#txtTypePriceID").val() == 155){
				filterResult = jLinq.from(objListaProductos2).where(function(obj){ return obj["Barra"] == codigoABuscar}).select();
			}
			//precio 3 ---> 156 --> precio credito
			if($("#txtTypePriceID").val() == 156){
				filterResult = jLinq.from(objListaProductos3).where(function(obj){ return obj["Barra"] == codigoABuscar}).select();
			}
			
			
			
			//buscar producto por codigo de barra escrito
			if(filterResult.length == 0)
			{
				
				codigoABuscar =  "BITT" + ("00000000"+codigoABuscar).substr(("00000000"+codigoABuscar).length - 8 ,8);				
				//precio 1 ---> 154 --> precio publico
				if($("#txtTypePriceID").val() == 154){
					filterResult = jLinq.from(objListaProductos).where(function(obj){ return obj["Barra"] == codigoABuscar}).select();
				}
				//precio 2 ---> 155 --> precio mayorista
				if($("#txtTypePriceID").val() == 155){
					filterResult = jLinq.from(objListaProductos2).where(function(obj){ return obj["Barra"] == codigoABuscar}).select();
				}
				//precio 3 ---> 156 --> precio credito
				if($("#txtTypePriceID").val() == 156){
					filterResult = jLinq.from(objListaProductos3).where(function(obj){ return obj["Barra"] == codigoABuscar}).select();
				}
			}
			
			
			//Buscar producto por codigo de sistema		
			var sumar  = true;
			if(filterResult.length == 0)
			{
				//Agregar Cantidad
				if(codigoABuscar.indexOf("+")  >= 0 ){				
					sumar  = true;
					codigoABuscar =  codigoABuscar.replace("BITT","").replace("+","");
					codigoABuscar =  "ITT" + ("00000000"+codigoABuscar).substr(("00000000"+codigoABuscar).length - 8 ,8);				
				}
				if(codigoABuscar.indexOf("-")  >= 0 ){				
					sumar  = false;
					codigoABuscar =  codigoABuscar.replace("BITT","").replace("-","");
					codigoABuscar =  "ITT" + ("00000000"+codigoABuscar).substr(("00000000"+codigoABuscar).length - 8 ,8);				
				}
				
				//precio 1 ---> 154 --> precio publico
				if($("#txtTypePriceID").val() == 154){
					filterResult = jLinq.from(objListaProductos).where(function(obj){ return obj["Codigo"] == codigoABuscar}).select();
				}
				//precio 2 ---> 155 --> precio mayorista
				if($("#txtTypePriceID").val() == 155){
					filterResult = jLinq.from(objListaProductos2).where(function(obj){ return obj["Codigo"] == codigoABuscar}).select();
				}
				//precio 3 ---> 156 --> precio credito
				if($("#txtTypePriceID").val() == 156){
					filterResult = jLinq.from(objListaProductos3).where(function(obj){ return obj["Codigo"] == codigoABuscar}).select();
				}
			}
			
			//No se encontro
			if(filterResult.length == 0)
			{
				return;
			}
			
			
			filterResult 			= filterResult[0];
			var filterResultArray 	= [];
			filterResultArray[5] 	= filterResult.itemID;
			filterResultArray[17] 	= filterResult.Codigo;
			filterResultArray[18] 	= filterResult.Nombre;
			filterResultArray[20] 	= filterResult.Medida;
			filterResultArray[21] 	= filterResult.Cantidad;
			filterResultArray[22] 	= filterResult.Precio;
			//Agregar el Item a la Fila
			onCompleteNewItem(filterResultArray,sumar); 
			 
		});
		
		//Buscar el Cliente
		$(document).on("click","#btnSearchCustomer",function(){
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCustomer->componentID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_BILLING/true/empty";
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteCustomer = onCompleteCustomer; 
		});						
	
		//Eliminar Cliente
		$(document).on("click","#btnClearCustomer",function(){
					$("#txtCustomerID").val("");
					$("#txtCustomerDescription").val("");
		});
		
		$(document).on("change","#txtCausalID,#txtCustomerCreditLineID",function(){
			fnClearData();
		});
		
		$(document).on("change","#txtTypePriceID",function(){
			fnActualizarPrecio();
		});
		


		$(document).on("change","#txtCausalID",function(){
			fnRenderLineaCreditoDiv();
		});
		
		
		
		//Regresar a la lista
		$(document).on("click","#btnBack",function(){
				fnWaitOpen();
		});
		
		//Evento Agregar el Usuario
		$(document).on("click","#btnAcept",function(){
				fnEnviarFactura();
		});
		
		$(document).on("click","#btnNewItem",function(){
			
			var ventana_ancho = $(window).width()-50;
			var ventana_alto = $(window).height();
			$("#div-modal-dialog-lista-productos").css("width",ventana_ancho+"px");
			//$("#div-modal-dialog-lista-productos").css("height",ventana_alto+"px");
  
			//if(openedSearchWindow 				== false){
			//	openedSearchWindow 			= true;
			//	var url_request 			= "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentItem->componentID; ?>/onCompleteNewItem/SELECCIONAR_ITEM_BILLING/false/"+encodeURI("{\"warehouseID\"|\""+  $("#txtWarehouseID").val()    +"\"{}\"listPriceID\"|\"<?php echo $objListPrice->listPriceID; ?>\"{}\"typePriceID\"|\""+ $("#txtTypePriceID").val() + "\"}");				
			//	objWindowSearchProduct 		= window.open(url_request,"MsgWindow","width=900,height=450");				
			//	window.onCompleteNewItem 	= onCompleteNewItem; 
			//}
			//else{
			//	objWindowSearchProduct.focus();
			//}
			
			fnCreateTableSearchProductos();
			$("#mi_modal").modal();
		
		});

		$(document).on("click","#btnNewItemCatalog",function(){
			
			var url_request 			= "<?php echo base_url(); ?>/app_inventory_item/add/callback/fnObtenerListadoProductos";
			window.open(url_request,"MsgWindow","width=700,height=600");
			window.fnObtenerListadoProductos = fnObtenerListadoProductos; 			
		});

		$(document).on("click","#btnRefreshDataCatalogo",function(){
			fnWaitOpen();
			setTimeout( function() { fnObtenerListadoProductos(); }, 10);			
			setTimeout( function() { fnObtenerListadoProductos2(); }, 10);			
			setTimeout( function() { fnObtenerListadoProductos3(); }, 10);			
			setTimeout( function() { fnObtenerListadoProdcutosSku(); }, 10);	
			setTimeout( function() { fnWaitClose(); }, 10000);
		});


		$(document).on("click","#btnSearchCustomerNew",function(){
			var url_request 				 = "<?php echo base_url(); ?>/app_cxc_customer/add/callback/fnCustomerNewCompleted";
			window.open(url_request,"mozillaWindow","width=700,height=600");
			window.fnCustomerNewCompleted = fnCustomerNewCompleted; 	
		});
		
		
		$(document).on("click","#btnDeleteItem",function(){
				var listRow = objTableDetail.fnGetData();							
				var length 	= listRow.length;
				var i 		= 0;
				var itemid 	= 0;
				while (i< length ){
					if(listRow[i][0] == true){
						itemid = listRow[i][2];
						objTableDetail.fnDeleteRow(i);
					}
					i++;
				}
				fnRecalculateDetail(true,"");									
		});
		$(document).on("change","input.txtQuantity",function(){			
			fnRecalculateDetail(true,"");		
		});
		$(document).on("change","input.txtPrice",function(){
			fnRecalculateDetail(true,"txtPrice");		
		});
		$(document).on("change","select.txtSku",function(){
			fnRecalculateDetail(true,"");		
		});
		$(document).on("change","input#txtReceiptAmount",function(){							
			var ingreso 	= fnFormatFloat($("#txtReceiptAmount").val());
			var ingresoDol 	= fnFormatFloat($("#txtReceiptAmountDol").val());
			var tipoCambio 	= fnFormatFloat($("#txtExchangeRate").val()); 			
			var total 		= fnFormatFloat($("#txtTotal").val());				
			var resultTotal =  (ingreso + (ingresoDol * tipoCambio)) - total;
			var resultTotal = fnFormatNumber(resultTotal,2);
			$("#txtChangeAmount").val(resultTotal);	
			
		});
		$(document).on("change","input#txtReceiptAmountDol",function(){							
			var ingreso 	= fnFormatFloat($("#txtReceiptAmount").val());
			var ingresoDol 	= fnFormatFloat($("#txtReceiptAmountDol").val());
			var tipoCambio 	= fnFormatFloat($("#txtExchangeRate").val()); 			
			var total 		= fnFormatFloat($("#txtTotal").val());			
			
			var resultTotal =  (ingreso + (ingresoDol * tipoCambio)) - total;
			var resultTotal = fnFormatNumber(resultTotal,2);
			$("#txtChangeAmount").val(resultTotal);	
			
		});

		
		if (varAutoAPlicar == "true"){
		$( "#form-new-invoice" ).submit(function(e){
				  e.preventDefault(e);

				  var formData = new FormData(this);

				  //Mandar la factura
				  //Interna mente, se se guarda y se imprimie
				  $.ajax({
						async: 		true,
						type: 		$( "#form-new-invoice" ).attr('method'),
						url: 		$( "#form-new-invoice" ).attr('action'),
						data: 		formData,
						cache: 		false,
						processData:false,
						contentType:false,				  
						success: 	function (data) {
						  console.log("success form data")
						},
						error: 		function(request, status, error) {
						  console.log("error form data")
						}
				  });
				  
				  
				  //Mandar abrir la caja de efectivo
				  $.ajax({
						async: 		true,
						type: 		"GET",
						url: 		"<?php echo base_url(); ?>/app_invoice_billing/viewPrinterOpen",
						data: 		formData,
						cache: 		false,
						processData:false,
						contentType:false,				  
						success: 	function (data) {
						  console.log("success form data")
						},
						error: 		function(request, status, error) {
						  console.log("error form data")
						}
				  });
				  
				  
				  window.location	= "<?php echo base_url(); ?>/app_invoice_billing/add";
				  fnWaitClose();
		});
		}
		
	});
	
	//Seleccionar Checke 
	$(document).on("click",".classCheckedDetail",function(){
		var objrow_ = $(this).parent().parent().parent().parent()[0];
		var objind_ = objTableDetail.fnGetPosition(objrow_);
		var objdat_ = objTableDetail.fnGetData(objind_);								
		objTableDetail.fnUpdate( !objdat_[0], objind_, 0 );
		refreschChecked();
	});
	
	
	function onCompleteCustomer(objResponse){
		console.info("CALL onCompleteCustomer");
	
		var entityID = objResponse[1];
		$("#txtCustomerID").val(objResponse[1]);
		$("#txtCustomerDescription").val(objResponse[2] + " " + objResponse[3] + " / " + objResponse[4]);	
		fnClearData();
		
		
		fnGetCustomerClient();
		
	}
	
	function onCompleteNewItem(objResponse,suma){
		console.info("CALL onCompleteNewItem");

		
		var objRow 							= {};		
		objRow.checked 						= false;						
		objRow.transactionMasterDetailID 	= 0;
		objRow.itemID						= objResponse[5];
		objRow.codigo						= objResponse[17];
		objRow.description					= objResponse[18].toLowerCase();
		objRow.um							= objResponse[20];
		objRow.quantity 					= fnFormatNumber(1,2);
		objRow.bquantity 					= fnFormatNumber(objResponse[21],2);
		objRow.price 						= fnFormatNumber(objResponse[22],2);
		objRow.total 						= fnFormatNumber(objRow.quantity * objRow.price,2);						
		objRow.iva 							= 0;
		objRow.lote 						= "";
		objRow.vencimiento					= "";
		
		
		//Actualizar
		if(jLinq.from(objTableDetail.fnGetData()).where(function(obj){ return obj[2] == objRow.itemID;}).select().length > 0 ){
			
			var x_ 			=  jLinq.from(objTableDetail.fnGetData()).where(function(obj){ return obj[2] == objRow.itemID;}).select();
			var newCantidad =  0;
			
			if (suma == true)
			newCantidad =  parseFloat(fnFormatNumber(x_[0][6],2)) + 1;
			else
			newCantidad =  parseFloat(fnFormatNumber(x_[0][6],2)) - 1;
			
			var objind_ 	= fnGetPosition(x_,objTableDetail.fnGetData());
			objTableDetail.fnUpdate( fnFormatNumber(newCantidad,2)  , objind_, 6 );
			
			
			$("#body_tb_transaction_master_detail tr")[objind_].animate({ 
			backgroundColor : "#4eacc8" },500);
			$("#body_tb_transaction_master_detail tr")[objind_].animate({ 
			backgroundColor : "" },500);
			
		}
		//Agregar
		else{
			objTableDetail.fnAddData([
				objRow.checked,
				objRow.transactionMasterDetailID,
				objRow.itemID,
				objRow.codigo,
				objRow.description,
				objRow.um,
				objRow.quantity,
				objRow.price,
				objRow.total,
				objRow.iva,
				0,
				0
			]);
			
			
			$("#body_tb_transaction_master_detail tr")[objTableDetail.fnGetData().length - 1].animate({ 
			backgroundColor : "#4eacc8" },500);
			$("#body_tb_transaction_master_detail tr")[objTableDetail.fnGetData().length - 1].animate({ 
			backgroundColor : "" },500);
		}
		
		
		
		fnGetConcept(objRow.itemID,"IVA");						
		refreschChecked();
		document.getElementById("txtScanerCodigo").focus();	
		$("html, body").animate({scrollTop:  $("body").height()  +"px"});		
		
	}
	
	function validateFormAndSubmit(){
		var result 				= true;		
		var timerNotification 	= 15000;
		var switchDesembolso	= !$("#txtLabelIsDesembolsoEfectivo").parent().find(".switch.has-switch").children().hasClass("switch-off");
		
		//Validar bodega de despacho
		if($("#txtWarehouseID").val() == ""){
			fnShowNotification("Seleccionar bodega de desapcho","error",timerNotification);			
			result = false;	
			fnWaitClose();
		}
		
		
		//Validar Fecha		
		if($("#txtDate").val() == ""){
			fnShowNotification("Establecer Fecha al Documento","error",timerNotification);
			result = false;
			fnWaitClose();
		}
		
		//Validar Cliente		
		if($("#txtCustomerID").val() == ""){
			fnShowNotification("Seleccionar el Cliente","error",timerNotification);
			result = false;
			fnWaitClose();
		}
		//Validar Proveedor de Credito
		if($("#txtReference1").val() == "0" && switchDesembolso){
			fnShowNotification("Seleccionar el Proveedor de Credito","error",timerNotification);
			result = false;
			fnWaitClose();
		}
		//Validar Zona
		if($("#txtZoneID").val() == "" && switchDesembolso){
			fnShowNotification("Seleccionar la Zona de la Factura","error",timerNotification);
			result = false;
			fnWaitClose();
		}
		
		
		
		
		
		//Validar Detalle
		//
		///////////////////////////////////////////////		
		var cantidadTotalesEnZero = jLinq.from(objTableDetail.fnGetData()).where(function(obj){ return obj[8] == 0;}).select().length ;
		if(cantidadTotalesEnZero > 0){
			fnShowNotification("No pueden haber totales en 0","error",timerNotification);
			result = false;
			fnWaitClose();
		};		
		
		var cantidadTotalesEnZero = jLinq.from(objTableDetail.fnGetData()).where(function(obj){ return obj[6] == 0;}).select().length ;
		if(cantidadTotalesEnZero > 0){
			fnShowNotification("No pueden haber cantidades en 0","error",timerNotification);
			result = false;
			fnWaitClose();
		};		
		
		
		if(varAutoAPlicar == "true" && objTableDetail.fnGetData().length == 0){
			fnShowNotification("La factura no puede estar vacia","error",timerNotification);
			result = false;
			fnWaitClose();
		}
		
		var listItemIDToValid 	= "-1";
		var listQntity 			= "-1"
		for(var i = 0; i < objTableDetail.fnGetData().length; i++){
			
			var rowTable 				= objTableDetail.fnGetData()[i];
			var rowTableItemID 		 	= rowTable[2];
			var rowTableItemQuantity 	= rowTable[6];
			var rowTableItemNombre 		= rowTable[4];			
			var objProducto 			= jLinq.from(objListaProductos).where(function(obj){ return obj.itemID == rowTableItemID}).select();
			
			
			if(objProducto.length == 0){
				fnShowNotification("Producto no se encuentra en inventario","error",timerNotification);
				result = false;	
				fnWaitClose();
			}
			listItemIDToValid = listItemIDToValid+ ","+rowTableItemID;
			listQntity = listQntity+ ","+rowTableItemQuantity;
			
			
			
		}	 
		
		
		//Si es de credito que la factura no supere la linea de credito
		var causalSelect 				= $("#txtCausalID").val();
		var customerCreditLineID 		= $("#txtCustomerCreditLineID").val();
		var objCustomerCreditLine 		= jLinq.from(tmpInfoClient.objListCustomerCreditLine).where(function(obj){ return obj.customerCreditLineID == customerCreditLineID; }).select();
		var causalCredit 				= tmpInfoClient.objCausalTypeCredit.value.split(",");
		var invoiceTypeCredit 			= false;
		
		
		//Obtener si la factura es al credito						
		for(var i=0;i<causalCredit.length;i++){
			if(causalCredit[i] == causalSelect){
				invoiceTypeCredit = true;
			}
		}
		
		
		//Validaciones si la factura es al credito.
		if(invoiceTypeCredit){
			
			
			//Validar Fecha del Primer Pago si es de Credito
			if($("#txtDateFirst").val() == "" && switchDesembolso){
				fnShowNotification("Seleccionar la Fecha del Primer Pago","error",timerNotification);
				result = false;
				fnWaitClose();
			}
			
			
			//Validar Notas
			if($("#txtNote").val() == "" && switchDesembolso){
				fnShowNotification("Asignarle una nota al documento","error",timerNotification);
				result = false;
				fnWaitClose();
			}
			
			//Validar Escritura Publica
			if($("#txtFixedExpenses").val() == "" && switchDesembolso){
				fnShowNotification("Ingresar el Porcentaje de Gastos Fijo por Desembolso","error",timerNotification);
				result = false;
				fnWaitClose();
			}
			
			
			
			var montoTotalInvoice 	= fnFormatFloat(fnFormatNumber($("#txtTotal").val(),"4"));
			var balanceCredit 		= 0;
			
			if(tmpInfoClient.objCurrencyCordoba.currencyID == objCustomerCreditLine[0].currencyID)
				balanceCredit =  fnFormatFloat(fnFormatNumber(objCustomerCreditLine[0].balance,"4"));
			else{
				balanceCredit = (
									fnFormatFloat(fnFormatNumber(objCustomerCreditLine[0].balance,"4")) * 
									fnFormatFloat(fnFormatNumber(objCustomerCreditLine[0].objExchangeRate,"4")) 
								);
			}
			
			//Validar Limite
			if(balanceCredit < montoTotalInvoice){
				fnShowNotification("La factura no puede ser facturada al credito. Balance del cliente: " + balanceCredit,"error",timerNotification);
				result = false;
				fnWaitClose();
			}
			
			
		}
		else{			
			//Validar Pago
			if( parseFloat( $("#txtChangeAmount").val() )  < 0 ){
				fnShowNotification("El cambio de la factura no puede ser menor a 0","error",timerNotification);
				result = false;
				fnWaitClose();
			}
		}
		
		
		
		if(varPermitirFacturarProductosEnZero == "true" && result )
		{			
			$("#form-new-invoice" ).submit();
			return;
		}
		
		//Validar en el servidor	
		if(result){			
			$.ajax(
				{									
					cache       : false,
					dataType    : 'json',
					async		: false,
					type        : 'GET',																	
					url  		: "<?php echo base_url(); ?>/app_invoice_api/getValidExistencia/warehouseID/"+$("#txtWarehouseID").val() +'/itemID/'+listItemIDToValid+"/quantity/"+listQntity,
					success		: function(result){						
													
							if(result.resultValidate.length == 0){
								$( "#form-new-invoice" ).submit();
							}
							else{
								fnWaitClose();	
								for(var ie = 0 ; ie < result.resultValidate.length ; ie++){								
									fnShowNotification(
										result.resultValidate[ie].Codigo + " " + 
										result.resultValidate[ie].Nombre + " " + 
										result.resultValidate[ie].QuantityInWarehouse +  " " + 
										result.resultValidate[ie].Mensaje,
										"error"
									);
								}
							}
							
							
					},
					error: function(result){							
						fnWaitClose();
					}
				}
			);
		}
		
		
	}
	
	function fnGetConcept(conceptItemID,nameConcept){
		fnWaitOpen();
		$.ajax({									
			cache       : false,
			dataType    : 'json',
			type        : 'POST',
			url  		: "<?php echo base_url(); ?>/core_concept_api/index",
			data 		: {companyID : <?php echo $companyID;?>, componentID : <?php echo $objComponentItem->componentID;?>, componentItemID : conceptItemID, name : nameConcept  },
			success:function(data){
				console.info("complete concept success");
				fnWaitClose();
				if(data.error){
					fnShowNotification(data.message,"error");
					fnRecalculateDetail(true,"");		
					return;
				}								
				
				if(data.data != null){
					var x_		= jLinq.from(objTableDetail.fnGetData()).where(function(obj){ return obj[2] == data.data.componentItemID;}).select();									
					var objind_ = fnGetPosition(x_,objTableDetail.fnGetData());
					objTableDetail.fnUpdate( fnFormatNumber(data.data.valueOut,2), objind_, 9 );
				}
				fnRecalculateDetail(true,"");		
			},
			error:function(xhr,data){	
				console.info("complete concept error");									
				fnWaitClose();
				fnShowNotification("Error 505","error");
				fnRecalculateDetail(true,"");		
			}
		});								
	}
	
	function refreschChecked(){
		$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();						
	}
	
	function fnClearData(){
			console.info("fnClearData");
			objTableDetail.fnClearTable();
			$("#txtReceiptAmount").val("0");
			$("#txtReceiptAmountDol").val("0");
			$("#txtChangeAmount").val("0");
			$("#txtSubTotal").val("0");
			$("#txtIva").val("0");
			$("#txtTotal").val("0");
	}
	
	function fnRecalculateDetail(clearRecibo,sourceEvent){
		
		var typePriceID 			= $("#txtTypePriceID").val();
		var cantidad 				= 0;
		var iva 					= 0;
		var precio					= 0;		
		var subtotal 				= 0;		
		var total 					= 0;
		
		var cantidadGeneral 				= 0;
		var ivaGeneral 						= 0;
		var precioGeneral					= 0;		
		var subtotalGeneral 				= 0;		
		var totalGeneral 					= 0;
		
		var priceTemporal		= 0;
		var cantidadTemporal	= 0;
		
		
		var NSSystemDetailInvoice	= objTableDetail.fnGetData();		
		for(var i = 0; i < NSSystemDetailInvoice.length; i++){
			
			var skuSelecte 			= $($(".txtSku")[i]);
			var skuCatalogItemID 	= skuSelecte.val();
			var skuSelecteOption	= $($(".txtSku")[i]).find("option[value='"+skuCatalogItemID+"']").first();						
			var skuValue 			= skuSelecteOption.data("skuv");
			var skuValuePrimceUnit	= skuSelecteOption.data("skupriceunitary");
			
			cantidadTemporal 	=  $(".txtQuantity")[i].value;
			priceTemporal  		=  $(".txtPrice")[i].value;
			
			
			if(sourceEvent != "txtPrice"){
				priceTemporal  		=   skuValue * skuValuePrimceUnit;				
			}
			
			objTableDetail.fnUpdate( cantidadTemporal, i, 6 );
			objTableDetail.fnUpdate( priceTemporal, i, 7 );
		
			cantidad 	= parseFloat(NSSystemDetailInvoice[i][6]);
			precio 		= parseFloat(NSSystemDetailInvoice[i][7]);
			iva 		= parseFloat(NSSystemDetailInvoice[i][9]);
			
			
			
			subtotal    = precio * cantidad;
			iva 		= (precio * cantidad) * iva;
			total 		= iva + subtotal;
			
			
			cantidadGeneral 	= cantidadGeneral + cantidad;
			precioGeneral 		= precioGeneral + precio;
			ivaGeneral 			= ivaGeneral + iva;			
			subtotalGeneral 	= subtotalGeneral + subtotal;
			totalGeneral 		= totalGeneral + total;
			
			
			objTableDetail.fnUpdate( fnFormatNumber(subtotal,2), i, 8 );
		}						
		$("#txtSubTotal").val(fnFormatNumber(subtotalGeneral,2));
		$("#txtIva").val(fnFormatNumber(ivaGeneral,2));
		$("#txtTotal").val(fnFormatNumber(totalGeneral,2));
		
		$("#txtReceiptAmount").val(fnFormatNumber(totalGeneral,2));
		$("#txtReceiptAmountDol").val("0.00");
		$("#txtChangeAmount").val("0.00");
			
	}
	
	function fnFillListaProductos(data)
	{		

		
		console.info("complete success data");
		objListaProductos 			= data.objGridView;
		var objListaProductosStore 	= localStorage.getItem("objListaProductos");		
		localStorage.setItem("objListaProductos",JSON.stringify(objListaProductos));

	
	}
	function fnFillListaProductos2(data)
	{		

		
		console.info("complete success data");
		objListaProductos2 			= data.objGridView;
		var objListaProductosStore2 	= localStorage.getItem("objListaProductos2");		
		localStorage.setItem("objListaProductos2",JSON.stringify(objListaProductos2));

	
	}
	function fnFillListaProductos3(data)
	{		

		
		console.info("complete success data");
		objListaProductos3 						= data.objGridView;
		var objListaProductosStore3 			= localStorage.getItem("objListaProductos3");		
		localStorage.setItem("objListaProductos3",JSON.stringify(objListaProductos3));

	
	}
	function fnFillListaProductosSku(data)
	{		
		console.info("complete success data");
		objListaProductosSku					= data.objGridView;
		var objListaProductosStoreSku 			= localStorage.getItem("objListaProductosSku");		
		localStorage.setItem("objListaProductosSku",JSON.stringify(objListaProductosSku));
	
	}
	
	function fnCompleteGetCustomerCreditLine (data)
	{
		
		console.info("complete success data credit line");		
		tmpInfoClient = data;
		console.info(tmpInfoClient);
		
		//Renderizar Line Credit
		$("#txtCustomerCreditLineID").html("");
		$("#txtCustomerCreditLineID").val("");
		if(tmpInfoClient.objListCustomerCreditLine != null)
		for(var i = 0; i< tmpInfoClient.objListCustomerCreditLine.length;i++){
			if(i==0){
				$("#txtCustomerCreditLineID").append("<option value='"+tmpInfoClient.objListCustomerCreditLine[i].customerCreditLineID+"' selected>"+ tmpInfoClient.objListCustomerCreditLine[i].accountNumber + " " +tmpInfoClient.objListCustomerCreditLine[i].line  +"</option>");
				$("#txtCustomerCreditLineID").val(tmpInfoClient.objListCustomerCreditLine[i].customerCreditLineID);
			}
			else
				$("#txtCustomerCreditLineID").append("<option  value='"+tmpInfoClient.objListCustomerCreditLine[i].customerCreditLineID+"'>"+ tmpInfoClient.objListCustomerCreditLine[i].accountNumber + " " +tmpInfoClient.objListCustomerCreditLine[i].line  + "</option>");
		}
		
		//Habilitar la compra al contado o al credito
		$("#txtCausalID option").removeAttr("disabled");
		$("#txtCausalID").val("");
		
		var listArrayCausalCredit = tmpInfoClient.objCausalTypeCredit.value.split(",");
		$.each( $("#txtCausalID option"),function(index,obj){
			for(var i=0;i<listArrayCausalCredit.length;i++){
				var causalIDCredit = listArrayCausalCredit[i];
				
				if( ($(obj).attr("value") == causalIDCredit) && (tmpInfoClient.objListCustomerCreditLine != null))
					$("#txtCausalID option[value="+causalIDCredit+"]").removeAttr("disabled");
				else if( ($(obj).attr("value") == causalIDCredit) && (tmpInfoClient.objListCustomerCreditLine == null))
					$("#txtCausalID option[value="+causalIDCredit+"]").attr("disabled","true");
				else
					$("#txtCausalID option[value="+causalIDCredit+"]").removeAttr("disabled");
			}
		});
		
		
		//Refresh Control
		$("#txtCustomerCreditLineID").select2();
		$("#txtCausalID").select2();
		refreschChecked();
		//fnWaitClose();
	}
	
	function fnGetPosition(item,data){
		
		var i = 0;
		for(i = 0 ; i < data.length; i++){			
			if(data[i][2] == item[0][2])
			return i;							
		}
		
		return i;
	}
	
					
	function fnEnviarFactura(){
		fnWaitOpen();
		$( "#form-new-invoice" ).attr("method","POST");
		$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_invoice_billing/save/new");
		validateFormAndSubmit();
	}		
	
	function fnRenderLineaCreditoDiv(){
			//Si es de credito que la factura no supere la linea de credito
			var causalSelect 				= $("#txtCausalID").val();
			var customerCreditLineID 		= $("#txtCustomerCreditLineID").val();
			var objCustomerCreditLine 		= jLinq.from(tmpInfoClient.objListCustomerCreditLine).where(function(obj){ return obj.customerCreditLineID == customerCreditLineID; }).select();
			var causalCredit 				= tmpInfoClient.objCausalTypeCredit.value.split(",");
			var invoiceTypeCredit 			= false;
		
		
			//Obtener si la factura es al credito						
			for(var i=0;i<causalCredit.length;i++){
				if(causalCredit[i] == causalSelect){
					invoiceTypeCredit = true;
				}
			}
			

			if(invoiceTypeCredit ){
				$("#divLineaCredit").removeClass("hidden");
			}
			else{
				$("#divLineaCredit").addClass("hidden");				
			}
			
			

	}	
	
	
	//obtener informacion de los productos	
	async function fnObtenerListadoProductos(){		


		const resultAjax2 = await $.ajax(
		{									
			cache       : false,
			dataType    : 'json',
			type        : 'GET',																	
			//url  		: "<?php echo base_url(); ?>/app_invoice_api/getViewApi/<?php echo $objComponentItem->componentID; ?>/onCompleteNewItem/SELECCIONAR_ITEM_BILLING/"+encodeURI('{"warehouseID"|"<?php echo $warehouseID ?>"{}"listPriceID"|"<?php echo $objListPrice->listPriceID; ?>"{}"typePriceID"|"'+$("#txtTypePriceID").val() +'"}'),		
			url  		: "<?php echo base_url(); ?>/app_invoice_api/getViewApi/<?php echo $objComponentItem->componentID; ?>/onCompleteNewItem/SELECCIONAR_ITEM_BILLING/"+encodeURI('{"warehouseID"|"'+ $("#txtWarehouseID").val() +'"{}"listPriceID"|"<?php echo $objListPrice->listPriceID; ?>"{}"typePriceID"|"'+154+'"}'),		/*TIPO PRECIO 1 --> 154 --> PUBLICO*/
			success		: fnFillListaProductos,
			error:function(xhr,data)
			{	
				console.info("complete data error");	
				fnShowNotification("Error 505","error");
			}
		});
		
		
		return resultAjax2;
	}
	async function fnObtenerListadoProductos2(){		


		const resultAjax2 = await $.ajax(
		{									
			cache       : false,
			dataType    : 'json',
			type        : 'GET',																	
			//url  		: "<?php echo base_url(); ?>/app_invoice_api/getViewApi/<?php echo $objComponentItem->componentID; ?>/onCompleteNewItem/SELECCIONAR_ITEM_BILLING/"+encodeURI('{"warehouseID"|"<?php echo $warehouseID ?>"{}"listPriceID"|"<?php echo $objListPrice->listPriceID; ?>"{}"typePriceID"|"'+$("#txtTypePriceID").val() +'"}'),		
			url  		: "<?php echo base_url(); ?>/app_invoice_api/getViewApi/<?php echo $objComponentItem->componentID; ?>/onCompleteNewItem/SELECCIONAR_ITEM_BILLING/"+encodeURI('{"warehouseID"|"'+ $("#txtWarehouseID").val() +'"{}"listPriceID"|"<?php echo $objListPrice->listPriceID; ?>"{}"typePriceID"|"'+155+'"}'),		/*TIPO PRECIO 2 --> 155 --> POR MAYOR*/
			success		: fnFillListaProductos2,
			error:function(xhr,data)
			{	
				console.info("complete data error");	
				fnShowNotification("Error 505","error");
			}
		});
		
		
		return resultAjax2;
	}
	async function fnObtenerListadoProductos3(){		


		const resultAjax2 = await $.ajax(
		{									
			cache       : false,
			dataType    : 'json',
			type        : 'GET',																	
			//url  		: "<?php echo base_url(); ?>/app_invoice_api/getViewApi/<?php echo $objComponentItem->componentID; ?>/onCompleteNewItem/SELECCIONAR_ITEM_BILLING/"+encodeURI('{"warehouseID"|"<?php echo $warehouseID ?>"{}"listPriceID"|"<?php echo $objListPrice->listPriceID; ?>"{}"typePriceID"|"'+$("#txtTypePriceID").val() +'"}'),		
			url  		: "<?php echo base_url(); ?>/app_invoice_api/getViewApi/<?php echo $objComponentItem->componentID; ?>/onCompleteNewItem/SELECCIONAR_ITEM_BILLING/"+encodeURI('{"warehouseID"|"'+ $("#txtWarehouseID").val() +'"{}"listPriceID"|"<?php echo $objListPrice->listPriceID; ?>"{}"typePriceID"|"'+156+'"}'),		/*TIPO PRECIO 3 --> 156 --> CREDITO*/
			success		: fnFillListaProductos3,
			error:function(xhr,data)
			{	
				console.info("complete data error");	
				fnShowNotification("Error 505","error");
			}
		});
		
		
		return resultAjax2;
	}
	async function fnObtenerListadoProdcutosSku(){	

		const resultAjax2 = await $.ajax(
		{									
			cache       : false,
			dataType    : 'json',
			type        : 'GET',
			url  		: "<?php echo base_url(); ?>/app_invoice_api/getViewApi/<?php echo $objComponentItem->componentID; ?>/onCompleteNewItem/SELECCIONAR_ITEM_SKU/0",
			success		: fnFillListaProductosSku,
			error:function(xhr,data)
			{	
				console.info("complete data error");	
				fnShowNotification("Error 505","error");
			}
		});
		
		
		return resultAjax2;
	}
	
	function fnActualizarPrecio()
	{
		
		var typePriceID 			= $("#txtTypePriceID").val();
		var NSSystemDetailInvoice	= objTableDetail.fnGetData();	
		for(var i = 0; i < NSSystemDetailInvoice.length; i++)
		{			
			var itemID 			= NSSystemDetailInvoice[i][2];
			var filterResult 	= {};
			
			//precio 1 ---> 154 --> precio publico
			if(typePriceID == 154){
				filterResult = jLinq.from(objListaProductos).where(function(obj){ return obj["itemID"] == itemID}).select();
			}
			//precio 2 ---> 155 --> precio mayorista
			if(typePriceID == 155){
				filterResult = jLinq.from(objListaProductos2).where(function(obj){ return obj["itemID"] == itemID}).select();
			}
			//precio 3 ---> 156 --> precio credito
			if(typePriceID == 156){
				filterResult = jLinq.from(objListaProductos3).where(function(obj){ return obj["itemID"] == itemID}).select();
			}
			
			//Actualizar Precio
			objTableDetail.fnUpdate(fnFormatNumber( filterResult[0].Precio,2) , i, 7 );
	
		}
	}
	
	
	function fnCustomerNewCompleted(){
		console.info("cliente completado");
	}
	
	async function fnGetCustomerClient(){
		const resultAjax = await $.ajax({									
			cache       : false,
			dataType    : 'json',
			type        : 'POST',
			url  		: "<?php echo base_url(); ?>/app_invoice_api/getLineByCustomer",
			data 		: {entityID : $("#txtCustomerID").val()  },
			success		: fnCompleteGetCustomerCreditLine,
			error:function(xhr,data){	
				console.info("complete data error");													
				fnShowNotification("Error 505","error");
			}
		});
		
		
		return resultAjax;
		
	}
	
	function fnCreateTableSearchProductos(){
		//Filtrar Datos
		var typePriceID 	= $("#txtTypePriceID").val();		
		var filterResult 	= {};
		
		//precio 1 ---> 154 --> precio publico
		if(typePriceID == 154){
			filterResult = objListaProductos;
		}
		//precio 2 ---> 155 --> precio mayorista
		if(typePriceID == 155){
			filterResult = objListaProductos2;			
		}
		//precio 3 ---> 156 --> precio credito
		if(typePriceID == 156){
			filterResult = objListaProductos3;
		}
			

				
		var dataSourceProductos = [];
		for(var i = 0 ; i < filterResult.length;i++){
			dataSourceProductos.push(
				[
					filterResult[i].itemID,
					filterResult[i].Codigo,
					"'"+filterResult[i].Nombre+"'",
					filterResult[i].Medida,
					filterResult[i].Cantidad,
					filterResult[i].Precio,
					filterResult[i].Barra,
					
				]
			);
		}
		
		
		
		
		if( objTableProductosSearch != null)
		objTableProductosSearch.fnDestroy();
		
		$('#table_list_productos').dataTable({
			//"bPaginate"		: false,
			//"bFilter"			: false,
			//"bSort"			: false,
			//"bInfo"			: false,
			//"bAutoWidth"		: false,
			
			'Dom'				: "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-6'p>>",
			"aaData"			: dataSourceProductos,
			 "aoColumnDefs": [ 
						{
							"aTargets"		: [ 0 ],//itemID
							"bVisible"		: false,
							"bSearchable"	: false,
							//"mData":		'itemID',
							//"mRender"		: function ( data, type, full ) {
							//}
						},
						{
							"aTargets"		: [ 1 ],//Codigo
							"bVisible"  	: true,
							//"sClass" 		: "hidden",
							"bSearchable"	: true,
							//"mData":		'Codigo',
							//"mRender"		: function ( data, type, full ) {
							//}
						},
						{
							"aTargets"		: [ 2 ],//Descripcion
							"bVisible"		: true,
							//"sClass" 		: "hidden",
							"bSearchable"	: true,
							//"mData":		'Nombre',
							//"mRender"		: function ( data, type, full ) {
							//	
							//}
						},
						{
							"aTargets"		: [ 3 ],//Unidad
							"bVisible"		: false,
							"bSearchable"	: false,
							//"mData":		'Medida',
							//"sWidth" 		: "40%"
						},
						{
							"aTargets"		: [ 4 ],//Cantidad
							"bVisible"		: false,
							"bSearchable"	: false,
							//"mData":		'Cantidad',
							"mRender"		: function ( data, type, full ) {								
								return "<span class='red' style='text-align:right;display:block' >"+fnFormatNumber(data,2)+"</span>";
							}
						},
						{
							"aTargets"		: [ 5 ],//Precio
							"bVisible"		: true,
							//"mData":		'Precio',
							"mRender"		: function ( data, type, full ) {
								return "<span class='green' style='text-align:right;display:block' >"+fnFormatNumber(data,2) +"</span>";
							}
						},
						{
							"aTargets"		: [ 6 ],//Barra
							"bVisible"		: true,
							//"mData":		'Precio',
							//"mRender"		: function ( data, type, full ) {
							//	
							//}
						}
			],
			
			'sPaginationType'	: 'bootstrap',
			'bJQueryUI'			: false,
			'bAutoWidth'		: false,							
			'iDisplayLength'	: 5,
			'oLanguage'	: {
				'sSearch'		: '<span>Filtro:</span> _INPUT_ <p>+ para agregar</p>',
				/*'sLengthMenu'	: '<span>_MENU_ elementos</span>',*/
				'sLengthMenu'	: '',
				/*'oPaginate'		: { 'sFirst': 'First', 'sLast': 'Last' }*/
				'oPaginate'		: { 'sFirst': 'Primera', 'sLast': 'Ultima','sNext':'Siguiente','sPrevious':'Atras' },
				'sInfo'			:'_START_ de _END_ total _TOTAL_'
			}
			
		});
					
		objTableProductosSearch = $('#table_list_productos').dataTable(); 
		
		$('.dataTables_length select').uniform();
		$('.dataTables_paginate > ul').addClass('pagination');		
		
					
										
		$(document).on('click','#table_list_productos tr',function(event){ 			
			objRowTableProductosSearch = this; 
			fnTableSelectedRow(this,event);
		});  
			
		$('#table_list_productos').css('display','table');
	}
	

</script>
<script>  (function(g,u,i,d,e,s){g[e]=g[e]||[];var f=u.getElementsByTagName(i)[0];var k=u.createElement(i);k.async=true;k.src='https://static.userguiding.com/media/user-guiding-'+s+'-embedded.js';f.parentNode.insertBefore(k,f);if(g[d])return;var ug=g[d]={q:[]};ug.c=function(n){return function(){ug.q.push([n,arguments])};};var m=['previewGuide','finishPreview','track','identify','triggerNps','hideChecklist','launchChecklist'];for(var j=0;j<m.length;j+=1){ug[m[j]]=ug.c(m[j]);}})(window,document,'script','userGuiding','userGuidingLayer','744100086ID'); </script>
<script>
	//window.userGuiding.identify(userId*, attributes)
	  
	// example with attributes
	window.userGuiding.identify('<?php echo get_cookie("email"); ?>', {
	  email: '<?php echo get_cookie("email"); ?>',
	  name: '<?php echo get_cookie("email"); ?>',
	  created_at: 1644403436643,
	});
	// or just send userId without attributes
	//window.userGuiding.identify('1Ax69i57j0j69i60l4')
</script>
