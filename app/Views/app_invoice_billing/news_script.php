<!-- ./ page heading -->
<script>					
	//fnWaitOpen();
	var heigthTop							= 0;
	var objTableDetail 						= {};	
	

	
	var scrollPosition						= 0;
	var isAdmin								= '<?php echo $isAdmin; ?>';
	var openedSearchWindow					= false;
	var objWindowSearchProduct				= {};
	var warehouseID 						= $("#txtWarehouseID").val();
	var objTableProductosSearch 			= null;	
	var objRowTableProductosSearch 			= null;	
	var varBaseUrl							= '<?php echo base_url(); ?>';
	var varCurrencyDefaultSimbol			= '<?php echo $objCurrency->simbol; ?>';
	var varIsMobile							= '<?php echo $isMobile; ?>';
	var varUseMobile						= '<?php echo $useMobile; ?>';
	var varParameterCustomPopupFacturacion	= '<?php echo $objParameterCustomPopupFacturacion; ?>';	
	var varParameterScanerProducto			= '<?php echo $objParameterScanerProducto; ?>';
	var varAutoAPlicar						= '<?php echo $objParameterInvoiceAutoApply; ?>';
	var varParameterCantidadItemPoup		= '<?php echo $objParameterCantidadItemPoup; ?>';  
	var varPermitirFacturarProductosEnZero	= '<?php echo $objParameterInvoiceBillingQuantityZero; ?>';
	var varParameterHidenFiledItemNumber	= <?php echo $objParameterHidenFiledItemNumber; ?>;  
	var varParameterAmortizationDuranteFactura		= <?php echo $objParameterAmortizationDuranteFactura; ?>;  
	var varParameterImprimirPorCadaFactura			= '<?php echo $objParameterImprimirPorCadaFactura; ?>';
	var varParameterRegresarAListaDespuesDeGuardar	= '<?php echo $objParameterRegresarAListaDespuesDeGuardar; ?>';
	var varParameterAlturaDelModalDeSeleccionProducto	= '<?php echo $objParameterAlturaDelModalDeSeleccionProducto; ?>';
	var varParameterScrollDelModalDeSeleccionProducto	= '<?php echo $objParameterScrollDelModalDeSeleccionProducto; ?>';
	var varParameterINVOICE_BILLING_SELECTITEM			= '<?php echo $objParameterINVOICE_BILLING_SELECTITEM; ?>';	
	var varParameterMostrarImagenEnSeleccion 	= '<?php echo $objParameterMostrarImagenEnSeleccion; ?>';
	var varPermisos								= JSON.parse('<?php echo json_encode($objListaPermisos); ?>');
	var varPermisosEsPermitidoModificarPrecio 			= jLinq.from(varPermisos).where(function(obj){ return obj.display == "ES_PERMITIDO_MODIFICAR_PRECIO_EN_FACTURACION"}).select().length > 0 ? true:	false;	
	var varPermisosEsPermitidoModificarNombre 			= jLinq.from(varPermisos).where(function(obj){ return obj.display == "ES_PERMITIDO_MODIFICAR_NOMBRE_EN_FACTURACION"}).select().length > 0 ? true:	false;
	var varPermisosEsPermitidoSeleccionarPrecioPublico 	= jLinq.from(varPermisos).where(function(obj){ return obj.display == "ES_PERMITIDO_SELECCIONAR_PRECIO_PUBLICO"}).select().length > 0 ? true:	false;
	var varPermisosEsPermitidoSeleccionarPrecioPormayor	= jLinq.from(varPermisos).where(function(obj){ return obj.display == "ES_PERMITIDO_SELECCIONAR_PRECIO_PORMAYOR"}).select().length > 0 ? true:	false;
	var varPermisosEsPermitidoSeleccionarPrecioCredito 	= jLinq.from(varPermisos).where(function(obj){ return obj.display == "ES_PERMITIDO_SELECCIONAR_PRECIO_CREDITO"}).select().length > 0 ? true:	false;
	
	var PriceStatus = varPermisosEsPermitidoModificarPrecio == true ? "":"readonly";
	var NameStatus  = varPermisosEsPermitidoModificarNombre == true ? "":"readonly";
	<?php echo $objListParameterJavaScript; ?>
	
	var objListCustomerCreditLine 	= JSON.parse('<?php echo json_encode($objListCustomerCreditLine); ?>');
	var objCausalTypeCredit 		= JSON.parse('<?php echo json_encode($objCausalTypeCredit); ?>');
	var objCurrencyCordoba 			= JSON.parse('<?php echo json_encode($objCurrencyCordoba); ?>');	
	var varCustomerCrediLineID		= 0;
		

	
	

	$(document).on("click",".btnPlus",function(){
		
		var quantity = $(this).parent().parent().parent().find(".txtQuantity").val();
		quantity 	 = fnFormatFloat(quantity);
		quantity	 = quantity + 1;
		$(this).parent().parent().parent().find(".txtQuantity").val(quantity);
		fnRecalculateDetail(true,"");				
	});
	
	$(document).on("click","#txtToolCalcular",function(){
		
		var valor 	= $("#txtToolMontoConIva").val();
		valor 		= fnFormatNumber(valor / 1.15,2);
		$("#txtToolMontoSinIva").val(valor);
		
		//copiar texto al portapapeles
		navigator.clipboard.writeText(valor)
		.then(() => {
			console.log('Texto copiado al portapapeles')
		})
		.catch(err => {
			console.error('Error al copiar al portapapeles:', err)
		});
		  
	});
	
	$(document).on("click",".btnAddSelectedItem",function(){
		fnAddRowSelected();			
	});
	
	$(document).on("click",".btnMenus",function(){
		
		var quantity = $(this).parent().parent().parent().find(".txtQuantity").val();
		quantity 	 = fnFormatFloat(quantity);
		quantity	 = quantity - 1;
		$(this).parent().parent().parent().find(".txtQuantity").val(quantity);
		fnRecalculateDetail(true,"");				
	});
	$(document).on("click",".btnPrecioRecomendado",function(){					
		var precioRecomendado = $(this).data("precio");
		$(this).parent().parent().parent().parent().parent().parent().find(".txtPrice").val(precioRecomendado);
		fnRecalculateDetail(true,"txtPrice");				
	});
	
	
	$(document).on("focus",".txt-numeric",function(){
		if ( fnFormatFloat( $(this).val()  ) == 0)
		{
			$(this).val("");
		}			
	});
	$(document).on("blur",".txt-numeric",function(){
		if( $(this).val()   == "")
		{
			$(this).val("0.00");
		}			
	});
	
	
	$(document).on("click",".img_row",function(){				
			window.open($(this).data("src"), '_blank');
	});

	$(document).on("dblclick","#table_list_productos_detail > tr",function(){
		
			
			
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
	$(document).on("click","#btnAddProductoOnLine",function(){
			
			
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
		
		if(varParameterScanerProducto != "false")
		{
			document.getElementById("txtScanerCodigo").focus();					
		}
		
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
		
		var currencyID 		= $("#txtCurrencyID").val();
		var codigoABuscar 	= $("#txtScanerCodigo").val();
		codigoABuscar 		= codigoABuscar.toUpperCase();
		$("#txtScanerCodigo").val("");
		
		
		
		
		//++Abrir popup de productos
		if(codigoABuscar == "++"){
			
			var ventana_ancho = $(window).width()-50;				
			$("#div-modal-dialog-lista-productos").css("width",ventana_ancho+"px");
		
			fnCreateTableSearchProductos();
			$("#mi_modal").modal();			
			setTimeout(function() { 
			
				if(varUseMobile == "1")
				{
					//$("#table_list_productos_filter").remove();	
					$("#table_list_productos_info").remove();	
					$("#mi_modal > .modal-dialog > .modal-content > .modal-footer").remove();
					$("#table_list_productos_wrapper").find(".dataTables_paginate").remove();
				}
				else
				{
					$($("#table_list_productos_filter").find("input")[0]).focus(); 
				}
				
			}, 500);								
			return;
		}
		
		//Mover a ingreso de dinero Cordoba
		if(codigoABuscar == ""){
			document.getElementById("txtReceiptAmount").focus();
			$("#txtReceiptAmount").val("");
			return;
		}
		
		
		
		//buscar el producto y agregar por codigo de barra
		obtenerDataDBProductoArray(
			"objListaProductosX001",
			"all",
			0,
			"all",
			{"codigoABuscar":codigoABuscar},
			function(e){    
				
				
				//buscar el producto y agregar						
				var codigoABuscar 	= e.codigoABuscar;
				e 					= e.all;
				var encontrado		= false;
				for(var i = 0 ; i < e.length ; i++)
				{
					
					//buscar por codigo de sistema					
					var currencyTemp	= e[i].currencyID;
					var currencyID 		= $("#txtCurrencyID").val();
					if(  currencyID == currencyTemp && fnDeleteCerosIzquierdos(codigoABuscar) == fnDeleteCerosIzquierdos(e[i].Codigo.replace("BITT","").replace("ITT",""))  )
					{
						encontrado = true;
						break;
					}
					
					
					//buscar por codigo de barra
					var listCodigTmp 	= e[i].Barra.split(",");
					currencyTemp		= e[i].currencyID;
					currencyID 			= $("#txtCurrencyID").val();
					encontrado			= false;
							
					if(encontrado == false )
					{
						for(var ii = 0 ; ii < listCodigTmp.length; ii++)
						{
							if( fnDeleteCerosIzquierdos(listCodigTmp[ii]) == fnDeleteCerosIzquierdos(codigoABuscar) && currencyID == currencyTemp  )
							{
								encontrado = true;
								break;
							}
						}
					}
					
					
					
				}
				
				if(encontrado == true)
				{
					var sumar				= true;
					var filterResult 		= e[i];						
					var filterResultArray 	= [];
					filterResultArray[5]  	= filterResult.itemID;
					filterResultArray[17] 	= filterResult.Codigo;
					filterResultArray[18] 	= filterResult.Nombre;
					filterResultArray[20] 	= "N/A"
					filterResultArray[21] 	= filterResult.Cantidad;
					filterResultArray[22] 	= filterResult.Precio;
					//Agregar el Item a la Fila
					
					onCompleteNewItem(filterResultArray,sumar); 
				}
				 
			}
			
		);
		 
		 
		 
		
		
		 
	});
	
	//Buscar el Cliente
	$(document).on("click","#btnSearchCustomer",function(){
		
		//Ocultar Boton de Contado
		$("#divTipoFactura").addClass("hidden");
		
		//Redireccion pantalla
		var url_redirect		= "__app_cxc_customer__add__callback__onCompleteCustomer__comando__pantalla_abierta_desde_la_factura";			
		url_redirect 			= encodeURIComponent(url_redirect);
		
		
		var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCustomer->componentID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_BILLING/true/empty/false/"+url_redirect;
		window.open(url_request,"MsgWindow","width=900,height=450");
		window.onCompleteCustomer = onCompleteCustomer; 
	});						

	//Eliminar Cliente
	$(document).on("click","#btnClearCustomer",function(){
				$("#txtCustomerID").val("");
				$("#txtCustomerDescription").val("");
	});
	
	$(document).on("change","#txtCausalID,#txtCustomerCreditLineID,#txtCurrencyID",function(){
		fnClearData();
	});
	
	$(document).on("change",".txtItemSelected",function(e,o){			
		fnActualizarProducto(this);
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
	$(document).on("click","#btnAcept",function(e){
			e.preventDefault();
			fnEnviarFactura();
	});
	
	$(document).on("click","#btnNewItem",function(){
		
		
		
		
		setTimeout( function() { 
			
			var ventana_ancho = $(window).width()-50;
			
			
			$("#div-modal-dialog-lista-productos").css("width",ventana_ancho+"px");			
			fnCreateTableSearchProductos();
			$("#mi_modal").modal();
			
			setTimeout(function() { 
				
				if(varUseMobile == "1")
				{
					//$("#table_list_productos_filter").remove();	
					$("#table_list_productos_info").remove();		
					$("#mi_modal > .modal-dialog > .modal-content > .modal-footer").remove();		
					$("#table_list_productos_wrapper").find(".dataTables_paginate").remove();						
				}
				else
				{
					$($("#table_list_productos_filter").find("input")[0]).focus(); 
				}
			
			}, 500 );		
			
			
		}, 30 );
		
		
								
	
	});

	$(document).on("click","#btnNewItemCatalog",function(){
		
		var url_request 			= "<?php echo base_url(); ?>/app_inventory_item/add/callback/fnObtenerListadoProductos";
		window.open(url_request,"MsgWindow","width=700,height=600");
		window.fnObtenerListadoProductos = fnObtenerListadoProductos; 			
	});

	$(document).on("click","#btnRefreshDataCatalogo",function(){
		fnWaitOpen();
		openDataBaseAndCreate(false,true);		
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
		
		//obtener el nuevo valor
		//obtener el precio 1
		//obtener el precio 2
		//obtener el precio 3
		
		//si el nuevo precio es menor a todos los precios 
		//dejar como nuevo precio el menos de los 3 precios
		
		//si el nuevo precio no es menor a los tres presos
		//respetar el valor escribo por el usuario
	
	
		fnRecalculateDetail(true,"txtPrice");		
	});
	$(document).on("change","select.txtSku",function(){
		fnRecalculateDetail(true,"");		
	});
	
	$(document).on("change","input#txtReceiptAmount",function(){	
			fnCalculateAmountPay();			
	});
	$(document).on("change","input#txtReceiptAmountDol",function(){							
			fnCalculateAmountPay();			
	});
	$(document).on("change","input#txtReceiptAmountBank",function(){	
			fnCalculateAmountPay();			
	});
	$(document).on("change","input#txtReceiptAmountPoint",function(){							
			fnCalculateAmountPay();			
	});
	$(document).on("change","input#txtReceiptAmountTarjeta",function(){							
			fnCalculateAmountPay();			
	});
	$(document).on("change","input#txtReceiptAmountTarjetaDol",function(){							
			fnCalculateAmountPay();			
	});
	$(document).on("change","input#txtReceiptAmountBankDol",function(){							
			fnCalculateAmountPay();			
	});
	
	//Seleccionar Checke 
	$(document).on("click",".classCheckedDetail",function(){
		var objrow_ = $(this).parent().parent().parent().parent()[0];
		var objind_ = objTableDetail.fnGetPosition(objrow_);
		var objdat_ = objTableDetail.fnGetData(objind_);								
		objTableDetail.fnUpdate( !objdat_[0], objind_, 0 );
		refreschChecked();
	});
	
	function fnCalculateAmountPay()
	{
		
		
		if( $("#txtCurrencyID").val() == "1" /*Cordoba*/ )
		{
			var ingresoCordoba 	= fnFormatFloat($("#txtReceiptAmount").val());
			var bancoCordoba 	= fnFormatFloat($("#txtReceiptAmountBank").val());
			var puntoCordoba 	= fnFormatFloat($("#txtReceiptAmountPoint").val());
			
			var tarjetaCordoba 	= fnFormatFloat($("#txtReceiptAmountTarjeta").val());
			var tarejtaDolares 	= fnFormatFloat($("#txtReceiptAmountTarjetaDol").val());
			var bancoDolares 	= fnFormatFloat($("#txtReceiptAmountBankDol").val());
			
			var ingresoDol 	= fnFormatFloat($("#txtReceiptAmountDol").val());		
			var tipoCambio 	= fnFormatFloat($("#txtExchangeRate").val()); 		
			var total 		= fnFormatFloat($("#txtTotal").val());				
			
			var resultTotal =  (ingresoCordoba +  bancoCordoba + puntoCordoba + tarjetaCordoba + ( bancoDolares / tipoCambio ) + ( tarejtaDolares / tipoCambio )   + (ingresoDol / tipoCambio)) - total;
			var resultTotal = fnFormatNumber(resultTotal,2);
			$("#txtChangeAmount").val(resultTotal);	
		}
		
		if( $("#txtCurrencyID").val() == "2" /*dolares*/ )
		{
			var ingresoCordoba 	= fnFormatFloat($("#txtReceiptAmount").val());
			var bancoCordoba 	= fnFormatFloat($("#txtReceiptAmountBank").val());
			var puntoCordoba 	= fnFormatFloat($("#txtReceiptAmountPoint").val());			

			var tarjetaCordoba 	= fnFormatFloat($("#txtReceiptAmountTarjeta").val());
			var tarejtaDolares 	= fnFormatFloat($("#txtReceiptAmountTarjetaDol").val());
			var bancoDolares 	= fnFormatFloat($("#txtReceiptAmountBankDol").val());
			
			var ingresoDol 	= fnFormatFloat($("#txtReceiptAmountDol").val());	
			var tipoCambio 	= fnFormatFloat($("#txtExchangeRate").val()); 		
			var total 		= fnFormatFloat($("#txtTotal").val());							
			
			var resultTotal =  (ingresoCordoba +  bancoCordoba + puntoCordoba + tarjetaCordoba + ( bancoDolares * tipoCambio ) + ( tarejtaDolares * tipoCambio )   + (ingresoDol * tipoCambio)) - total;
			var resultTotal = fnFormatNumber(resultTotal,2);
			$("#txtChangeAmount").val(resultTotal);	
		}
		
	}
	
	
	
	function onCompletePantalla(){
		$(".btn-comando-factura").removeClass("hidden");
		
		
		if(varUseMobile == "1" ){
		   $(".elementMovilOculto").addClass("hidden");		   
		   
		   
		   
		   $("#table-resumen").css("width", ( $( window ).width() )+"px");
		   $("#table-resumen th").css("display","block");
		   $("#table-resumen td").css("display","block");
		   
		   
		   $("#table-resumen-pago").css("width", ( $( window ).width() )+"px");
		   $("#table-resumen-pago th").css("display","block");
		   $("#table-resumen-pago td").css("display","block");
		   
	
		   
		   $("#tb_transaction_master_detail th").css("display","none");
		   $("#tb_transaction_master_detail td").css("display","block");
		   
	    }
	   
	    //$("#divLoandingCustom").remove();
		
	}
	
	function onCompleteCustomer(objResponse){
		
		console.info("CALL onCompleteCustomer");
	
		if(objResponse != undefined)
		{
			var entityID = objResponse[1];
			$("#txtCustomerID").val(objResponse[1]);
			$("#txtCustomerDescription").val(objResponse[2] + " " + objResponse[3] + " / " + objResponse[4]);	
			fnClearData();
			fnGetCustomerClient();
		}
		
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
			
			debugger;
			if(varUseMobile != "1"){
				$("#body_tb_transaction_master_detail tr")[objind_].animate({ 
				backgroundColor : "#4eacc8" },100);
				$("#body_tb_transaction_master_detail tr")[objind_].animate({ 
				backgroundColor : "" },100);
			}
			
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
				0,
				"",
				""
			]);
			
			debugger;
			if(varUseMobile != "1"){
				$("#body_tb_transaction_master_detail tr")[objTableDetail.fnGetData().length - 1].animate({ 
				backgroundColor : "#4eacc8" },100);
				$("#body_tb_transaction_master_detail tr")[objTableDetail.fnGetData().length - 1].animate({ 
				backgroundColor : "" },100);
			}
		}
		
		
		
		fnGetConcept(objRow.itemID,"IVA");						
		refreschChecked();
		document.getElementById("txtScanerCodigo").focus();	
		
		
		
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
		//Validar Vendedor
		if($("#txtEmployeeID").val() == "" && switchDesembolso){
			fnShowNotification("Seleccionar el vendedor en la Factura","error",timerNotification);
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
			
			
			listItemIDToValid = listItemIDToValid+ ","+rowTableItemID;
			listQntity = listQntity+ ","+rowTableItemQuantity;
			
			
			
		}	 
		
		
		//Si es de credito que la factura no supere la linea de credito
		var causalSelect 				= $("#txtCausalID").val();
		var customerCreditLineID 		= $("#txtCustomerCreditLineID").val();
		var objCustomerCreditLine 		= jLinq.from(objListCustomerCreditLine).where(function(obj){ return obj.customerCreditLineID == customerCreditLineID; }).select();
		var causalCredit 				= objCausalTypeCredit.value.split(",");
		var invoiceTypeCredit 			= false;
		
		
		//Obtener si la factura es al credito						
		for(var i=0;i<causalCredit.length;i++){
			if(causalCredit[i] == causalSelect){
				invoiceTypeCredit = true;
			}
		}
		
		if(varParameterAmortizationDuranteFactura && $("#txtReference2").val() == "" && invoiceTypeCredit ){
			fnShowNotification("Seleccionar el plazo","error",timerNotification);
			result = false;
			fnWaitClose();
		}
		
		//No puede haber cambio, si la factura es de credito
		if(invoiceTypeCredit && $("#txtChangeAmount").val() > 0 )
		{
			fnShowNotification("No puede haber cambio si la factura es de credito","error",timerNotification);
			result = false;
			fnWaitClose();
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
			
			if(objCurrencyCordoba.currencyID == objCustomerCreditLine[0].currencyID)
				balanceCredit =  fnFormatFloat(fnFormatNumber(objCustomerCreditLine[0].balance,"4"));
			else{
				balanceCredit = (
									fnFormatFloat(fnFormatNumber(objCustomerCreditLine[0].balance,"4")) * 
									fnFormatFloat(fnFormatNumber(objCustomerCreditLine[0].objExchangeRate,"4")) 
								);
			}
			
			//Validar Limite
			if(balanceCredit < montoTotalInvoice &&  balanceCredit != 0 ){
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
										"" + 
										result.resultValidate[ie].Codigo + " " + 
										result.resultValidate[ie].Nombre + " cantidad en bodega   " + 
										result.resultValidate[ie].QuantityInWarehouse +  "  " + 
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
		
		
		//Recalculoa el concepto via AJAX 2023-12-05 Inicio		
		var x_			= jLinq.from(objTableDetail.fnGetData()).where(function(obj){ return obj[2] == conceptItemID ;}).select();									
		var objind_ 	= fnGetPosition(x_,objTableDetail.fnGetData());
		
		
		//Obtener el concepto de la base de datos del navegador y calcular nuevamente
		obtenerDataDBProductoArray(
			"objListaProductosConceptosX001",
			"componentItemID",conceptItemID,"none",{},
			function(e){ 
				console.info(e);
					
					var objConcepto = e;		
					if( objConcepto.length > 0 )
					{
						objTableDetail.fnUpdate( fnFormatNumber(objConcepto[0].valueOut,2), objind_, 9 );			
					}					
					fnRecalculateDetail(true,"");
					
			}
		);
		
	
		
		
	}
	
	function refreschChecked()
	{
		
		if(varUseMobile == "0")
		{
			var cantidaRow = $(".txtItemSelected").length;
			for(var i = 0 ; i < cantidaRow; i++)
			{
				var x = $($(".txtItemSelected")[i]).find("a").length;
				if(x == 0 )
					$($(".txtItemSelected")[i]).select2();
			}
		}
		
		$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();						
		
		if(varUseMobile == "1")
		{
			$("#tb_transaction_master_detail td").css("display","block");
		}
		
	}
	
	function fnClearData(){
			console.info("fnClearData");
			objTableDetail.fnClearTable();
			$("#txtReceiptAmount").val("0");
			$("#txtReceiptAmountDol").val("0");
			$("#txtChangeAmount").val("0");
			$("#txtReceiptAmountBank").val("0");
			$("#txtReceiptAmountPoint").val("0");
			$("#txtSubTotal").val("0");
			$("#txtIva").val("0");
			$("#txtTotal").val("0");
			
			$("#txtReceiptAmountTarjeta").val("0");
			$("#txtReceiptAmountTarjetaDol").val("0");
			$("#txtReceiptAmountBankDol").val("0");
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
			var skuValueDescription	= skuSelecteOption.data("description");
		
			cantidadTemporal 	=  $(".txtQuantity")[i].value;
			priceTemporal  		=  $(".txtPrice")[i].value;
			
			
			//if(sourceEvent != "txtPrice"){
			//	priceTemporal  		=   skuValue * skuValuePrimceUnit;				
			//}
			
			objTableDetail.fnUpdate( cantidadTemporal, i, 6 );
			objTableDetail.fnUpdate( priceTemporal, i, 7 );
			objTableDetail.fnUpdate(skuValueDescription , i, 13 );
		
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
		
		
	
		//Si es de credito que la factura no supere la linea de credito
		var causalSelect 				= $("#txtCausalID").val();
		var customerCreditLineID 		= $("#txtCustomerCreditLineID").val();
		var objCustomerCreditLine 		= jLinq.from(objListCustomerCreditLine).where(function(obj){ return obj.customerCreditLineID == customerCreditLineID; }).select();
		var causalCredit 				= objCausalTypeCredit.value.split(",");
		var invoiceTypeCredit 			= false;
		
		
		//Obtener si la factura es al credito						
		for(var i=0;i<causalCredit.length;i++){
			if(causalCredit[i] == causalSelect){
				invoiceTypeCredit = true;
			}
		}
		
		
		if(invoiceTypeCredit == true){
			$("#txtReceiptAmount").val("0.00");
			$("#txtReceiptAmountDol").val("0.00");
			$("#txtChangeAmount").val("0.00");
			$("#txtReceiptAmountBank").val("0");
			$("#txtReceiptAmountPoint").val("0");
			
			$("#txtReceiptAmountTarjeta").val("0");
			$("#txtReceiptAmountTarjetaDol").val("0");
			$("#txtReceiptAmountBankDol").val("0");
			
		}
		else{
			$("#txtReceiptAmount").val(fnFormatNumber(totalGeneral,2));
			$("#txtReceiptAmountDol").val("0.00");
			$("#txtChangeAmount").val("0.00");
			$("#txtReceiptAmountBank").val("0");
			$("#txtReceiptAmountPoint").val("0");
			
			$("#txtReceiptAmountTarjeta").val("0");
			$("#txtReceiptAmountTarjetaDol").val("0");
			$("#txtReceiptAmountBankDol").val("0");
		}
		
			
	}
	
	function fnFillListaProductos(data)
	{			
		
		console.info("complete success data");
		var objListaProductos 			= data.objGridView;		
		
		
		removeDataDB("objListaProductosX001");		
		addDataDBArray("objListaProductosX001",objListaProductos);
	
	}
	function fnFillListaProductos2(data)
	{		
		
		console.info("complete success data");
		var objListaProductos2 				= data.objGridView;
		
		
		removeDataDB("objListaProductosX002");		
		addDataDBArray("objListaProductosX002",objListaProductos2);
	
	}
	function fnFillListaProductos3(data)
	{		
		console.info("complete success data");
		var objListaProductos3 				= data.objGridView;
		
		
		removeDataDB("objListaProductosX003");		
		addDataDBArray("objListaProductosX003",objListaProductos3);
	
	}
	function fnFillListaProductosSku(data)
	{		
		
		console.info("complete success data");		
		removeDataDB("objListaProductosSkuX001");		
		addDataDBArray("objListaProductosSkuX001",data.objGridView);
	
	}
	
	function fnFillListaItemConcept(data)
	{		
		
		console.info("complete success data");
		removeDataDB("objListaProductosConceptosX001");		
		addDataDBArray("objListaProductosConceptosX001",data.objGridView);
	
	}
	
	function fnFillListaCreditLine(data)
	{		
	
		removeDataDB("objListaCustomerCreditLineX001");		
		addDataDBArray("objListaCustomerCreditLineX001",data.objListCustomerCreditLine);		
	
	}
	
	
	function fnCompleteGetCustomerCreditLine (data)
	{		
	
	
		objListCustomerCreditLine 	= data.objListCustomerCreditLine || [];
		objCausalTypeCredit 		= data.objCausalTypeCredit || [];
		fnRenderLineaCredit(objListCustomerCreditLine,objCausalTypeCredit);
		
		
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
			var objCustomerCreditLine 		= jLinq.from(objListCustomerCreditLine).where(function(obj){ return obj.customerCreditLineID == customerCreditLineID; }).select();
			var causalCredit 				= objCausalTypeCredit.value.split(",");
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
	function fnObtenerListadoProductos(){		


		$.ajax(
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
		
		
	}
	function fnObtenerListadoProductos2(){		


		$.ajax(
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
		
		
	}
	function fnObtenerListadoProductos3(){		


		$.ajax(
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
		
	}
	function fnObtenerListadoProdcutosSku(){	

		$.ajax(
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
		
	}
	
	
	function fnObtenerListadoItemConcept(){	

		$.ajax(
		{									
			cache       : false,
			dataType    : 'json',
			type        : 'GET',
			url  		: "<?php echo base_url(); ?>/core_concept_api/getConceptAllProduct",
			success		: fnFillListaItemConcept,
			error:function(xhr,data)
			{	
				console.info("complete data error");	
				fnShowNotification("Error 505","error");
			}
		});
		
	}
	
	function fnObtenerListadoCustomerCreditLine(){	

		$.ajax(
		{									
			cache       : false,
			dataType    : 'json',
			type        : 'GET',
			url  		: "<?php echo base_url(); ?>/app_invoice_api/getLineByCustomerAll",
			success		: fnFillListaCreditLine,
			error:function(xhr,data)
			{	
				console.info("complete data error");	
				fnShowNotification("Error 505","error");
			}
		});
		
	}
	
	function fnValidateSiAplicaPrecioPublico()
	{
		
		var parameter 	= objCompanyParameter_Key_INVOICE_BILLING_APPLY_TYPE_PRICE_ON_DAY_POR_MAYOR;	
		var parameter 	= parameter.split(",");
		var parameterI 	= "";
		var now 		= moment().format("YYYY-MM-DD");
		
		for(var i = 0 ; i < parameter.length ; i++)
		{
			var parameterI = parameter[i].toUpperCase();
			var dayName	   = moment().format("dddd").toUpperCase();
			
			if( parameterI == now)
			{
				return true;
			}
			
			if(parameterI == "0")
			{
				return true;
			}
			
			if
			( 
				parameterI ==  		
				(dayName == "MONDAY" ? "LUNES" : dayName )
			)
			{
				return true;				
			}
			
			if
			( 
				parameterI ==  		
				(dayName == "TUESDAY" ? "MARTES" : dayName )
			)
			{
				return true;				
			}
			
			if
			( 
				parameterI ==  		
				(dayName == "THURESDAY" ? "MIERCOLES" : dayName )
			)
			{
				return true;				
			}
			
			if
			( 
				parameterI ==  		
				(dayName == "THURSDAY" ? "JUEVES" : dayName )
			)
			{
				return true;				
			}
			
			if
			( 
				parameterI ==  		
				(dayName == "FRIDAY" ? "VIERNES" : dayName )
			)
			{
				return true;				
			}
			
			if
			( 
				parameterI ==  		
				(dayName == "SATURDAY" ? "SABADO" : dayName )
			)
			{
				return true;				
			}
			
			if
			( 
				parameterI ==  		
				(dayName == "SUNDAY" ? "DOMINGO" : dayName )
			)
			{
				return true;				
			}
			
		}
		
	}
	function fnActualizarProducto(obj)
	{
		
		var index 		= $(".select2.txtItemSelected.select2-offscreen").length;
		var selectes    = $(".select2.txtItemSelected.select2-offscreen");
		var rowss      	= 0;
		for(var i = 0 ; i < index ; i++)
		{
			var itemselected = $(selectes[i]).val();			
			if(itemselected == $(obj).val())
			{
				break;
			}
			else {
				rowss++;
			}
		}
		
		
		var itemid 		= $(obj).val();
		var codigo 		= $($(obj).find("option:selected")[0]).data("codigo");
		var name 		= $($(obj).find("option:selected")[0]).data("name");
		var cantidad 	= $($(obj).find("option:selected")[0]).data("cantidad");
		var precio 		= $($(obj).find("option:selected")[0]).data("precio");
		var unidad 		= $($(obj).find("option:selected")[0]).data("unidadmedida");
		var description = $($(obj).find("option:selected")[0]).data("description");
		var barra  		= $($(obj).find("option:selected")[0]).data("barra");
		
		objTableDetail.fnUpdate( itemid, rowss, 2 );
		objTableDetail.fnUpdate( codigo, rowss, 3 );
		objTableDetail.fnUpdate( name, rowss, 4 );
		objTableDetail.fnUpdate( unidad, rowss, 5 );
		objTableDetail.fnUpdate( fnFormatNumber(precio,2) , rowss, 7 );
		fnRecalculateDetail(true,"");
		refreschChecked();
		
	}
	
	function fnActualizarPrecio()
	{
		
		var typePriceID 			= $("#txtTypePriceID").val();
		var NSSystemDetailInvoice	= objTableDetail.fnGetData();	
		for(var i = 0; i < NSSystemDetailInvoice.length; i++)
		{			
			var itemID 			= NSSystemDetailInvoice[i][2];			
			obtenerDataDBProductoArrayUniByItemID(
				itemID,
				{
					"all":itemID,
					"index":i,
					"callback":function(e){ 
						
						
						var filterResult 	= {};
						
						//precio 1 ---> 154 --> precio publico
						if(typePriceID == 154){
							filterResult = e.producto1;
						}
						//precio 2 ---> 155 --> precio mayorista
						if(typePriceID == 155){
							filterResult = e.producto2;
						}
						//precio 3 ---> 156 --> precio credito
						if(typePriceID == 156){
							filterResult = e.producto2;
						}
						
						//Actualizar Precio
						objTableDetail.fnUpdate(fnFormatNumber( filterResult[0].Precio,2) , e.index, 7 );
						
					}
				}
			);
			
	
		}
	}
	
	
	function fnCustomerNewCompleted(){
		console.info("cliente completado");
	}
	
	function fnGetCustomerClient(){
		
		
		
		$.ajax({									
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
		
		
		
	}
	
	function fnDeleteCerosIzquierdos(texto){
		
		var array 						= texto.split("");
		var newTexto 				 	= "";
		var encontradoPrimerElemento 	= false;
		
		for(var i = 0 ; i<array.length;i++){
			if(array[i] != "0" && encontradoPrimerElemento == false)
			{
				newTexto = newTexto + array[i];
				encontradoPrimerElemento = true;
			}
			else{				
				if(encontradoPrimerElemento == true){
					newTexto = newTexto + array[i];
				}
			}
		}	
		
		return newTexto;
	}
	
	function fnAddRowSelected()
	{
		
		obtenerDataDBProductoArray(
			"objListaProductosX001",
			"all",
			0,
			"none",
			{},
			function(e){    
				
				debugger;
				var data		 = {};	
				var length2		 = objTableDetail.fnGetData().length;
				var data2		 = objTableDetail.fnGetData();
				
				var length		 = e.producto1.length;
				var data		 = e.producto1;
				var index		 = 0;
				
				for(var i = 0 ; i < length; i++ )
				{		
					var objDatItem 			= data[i];
					var existe 				= jLinq.from(data2).where(function(obj){   return obj[2] == objDatItem[0]; }).select().length;			
					if(existe == 0)
						break;
					
					index++;
					
				}
				
				
				var dataResponse = [];
				data			= data[index];				
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
				dataResponse[23] = data[0];//6:Barra 
				dataResponse[24] = data[0];//7:Descripcion
				dataResponse[25] = data[0];
				dataResponse[26] = data[0];		
				onCompleteNewItem(dataResponse,true);
			}
		);
		
		
	}
	
	
	
	function fnCreateTableSearchProductos(){
			
		
		obtenerDataDBProductoArray(
			"objListaProductosX001",
			"all",
			0,
			"none",
			{},
			function(e){    
				
				
				var dataSourceProductos = [];
				for(var i =0 ; i < e.length; i++)
				{
					dataSourceProductos.push(
						[
							e[i].itemID,
							e[i].Codigo,
							e[i].Nombre,
							e[i].Medida,
							e[i].Cantidad,
							e[i].Precio,
							e[i].Barra,
							e[i].Descripcion
						]
					);
				}
				
				
				if( objTableProductosSearch != null)
				objTableProductosSearch.fnDestroy();
				
				$('#table_list_productos').dataTable({
					
					
					"bPaginate"			: varParameterScrollDelModalDeSeleccionProducto == "false" ? true : false,
					//"bFilter"			: false,
					//"bSort"			: false,
					//"bInfo"			: false,
					//"bAutoWidth"		: false,
					
					
					'Dom'				: "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-6'p>>",
					'sPaginationType'	: 'bootstrap',
					'bJQueryUI'			: false,
					'bAutoWidth'		: false,							
					'iDisplayLength'	: varParameterCantidadItemPoup, //esta linea proboca que el boton siguiente no funcione...
					'oLanguage'	: {
						'sSearch'		: '<span>Filtro:</span> _INPUT_ <p>+ para agregar</p>',
						//'sLengthMenu'	: '<span>_MENU_ elementos</span>',
						'sLengthMenu'	: '',
						//'oPaginate'		: { 'sFirst': 'First', 'sLast': 'Last' }
						'oPaginate'		: { 'sFirst': 'Primera', 'sLast': 'Ultima','sNext':'Siguiente','sPrevious':'Atras' },
						'sInfo'			:'_START_ de _END_ total _TOTAL_'
					},
					
					
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
									"bVisible"  	: !(varParameterHidenFiledItemNumber == true ? true : (varUseMobile == "1" ? true : false )),
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
									"bVisible"		: true, //(varParameterCustomPopupFacturacion == "mobile_ruta_pablo" ? false : true),
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
									"bVisible"		: (varUseMobile == "1" ? false : true),
									//"mData":		'Precio',
									"mRender"		: function ( data, type, full ) 
									{
										var indexSearch = data.indexOf(",");
										if(indexSearch > 1 )
										{
											return "<!--Barra oculta:  "+data+" --> "+data.split(',')[0];
										}
										else 
										{
											return ""+data;
										}
									}
								},
								{
									"aTargets"		: [ 7 ],//Descripcion
									"bVisible"		: (varUseMobile == "1" ? false : true),
									//"mData":		'Precio',
									"mRender"		: function ( data, type, full ) {
										if(varParameterMostrarImagenEnSeleccion == "true")
										{
											var src = varBaseUrl+"/resource/file_company/company_2/component_33/component_item_"+full[0]+"/preventa.jpg";
											return ""+
												" <button type='button' class='btn btn-primary img_row' data-src='"+src+"'>Ver imagen</button><br>"+
												"<img class='img-thumbnail ' style='width:225px;height:120px' src='"+src+"' />"+
												"";
										}
										else
										{
											return ""+data;
										}
									}
								}
					]
					
					
					
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
		);
			
			
	}
	
	function fnRenderLineaCredit(objListCustomerCreditLine,objCausalTypeCredit)
	{
		
		$("#divTipoFactura").removeClass("hidden");
		$("#txtCustomerCreditLineID").html("");
		$("#txtCustomerCreditLineID").val("");
		
		
		if(objListCustomerCreditLine != null)
		for(var i = 0; i< objListCustomerCreditLine.length;i++){
			if(i==0 && varCustomerCrediLineID == 0){
				$("#txtCustomerCreditLineID").append("<option value='"+objListCustomerCreditLine[i].customerCreditLineID+"' selected>"+ objListCustomerCreditLine[i].accountNumber + " " +objListCustomerCreditLine[i].line  +"</option>");
				$("#txtCustomerCreditLineID").val(objListCustomerCreditLine[i].customerCreditLineID);
			}
			else if( varCustomerCrediLineID == objListCustomerCreditLine[i].customerCreditLineID){
				$("#txtCustomerCreditLineID").append("<option value='"+objListCustomerCreditLine[i].customerCreditLineID+"' selected>"+ objListCustomerCreditLine[i].accountNumber + " " +objListCustomerCreditLine[i].line  + "</option>");
				$("#txtCustomerCreditLineID").val(objListCustomerCreditLine[i].customerCreditLineID);
			}
			else
				$("#txtCustomerCreditLineID").append("<option  value='"+objListCustomerCreditLine[i].customerCreditLineID+"'>"+ objListCustomerCreditLine[i].accountNumber + " " +objListCustomerCreditLine[i].line  +"</option>");
		}
		
		//Habilitar la compra al contado o al credito
		$("#txtCausalID option").removeAttr("disabled");
		$("#txtCausalID").val("");		
		
		
		var listArrayCausalCredit = objCausalTypeCredit.value.split(",");
		$.each( $("#txtCausalID option"),function(index,obj){
			for(var i=0;i<listArrayCausalCredit.length;i++){
				var causalIDCredit = listArrayCausalCredit[i];
				if( ($(obj).attr("value") == causalIDCredit) && (objListCustomerCreditLine.length > 0))
					$("#txtCausalID option[value="+causalIDCredit+"]").removeAttr("disabled");
				else if( ($(obj).attr("value") == causalIDCredit) && (objListCustomerCreditLine.length == 0 ))
					$("#txtCausalID option[value="+causalIDCredit+"]").attr("disabled","true");
				else
					$("#txtCausalID option[value="+causalIDCredit+"]").removeAttr("disabled");
			}
		});
		
	
		
		
		//Refresh Control
		if(varUseMobile != "1")
		{
			$("#txtCustomerCreditLineID").select2();
			$("#txtCausalID").select2();
			$("#txtPeriodPay").select2();
		}
		
		refreschChecked();	
		if(varParameterINVOICE_BILLING_SELECTITEM == "true")
		{
			fnAddRowSelected(); 
		}			
		onCompletePantalla();		
		
	}
	
	function openDataBaseAndCreate(bInicializar,obtenerRegistroDelServer) {
		//...
		var indexDB 	= window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
		const request 	= indexDB.open('MyDatabasePosMe', 2);
		
		


		request.onsuccess = (e) => 
		{
			
			// Se crea la conexion
			db 				   = request.result;
			console.info('Database success');		
			
			if(bInicializar)
			{
				fnReady();
				setTimeout(function() { fnWaitClose(); fnWaitClose(); }, 1000);		
			}
			
			if(obtenerRegistroDelServer)
			{
				fnObtenerListadoProductos();
				fnObtenerListadoProductos2();
				fnObtenerListadoProductos3();
				fnObtenerListadoProdcutosSku();
				fnObtenerListadoItemConcept();
				fnObtenerListadoCustomerCreditLine();
				setTimeout(function() { fnWaitClose(); fnWaitClose(); }, 4000);		
				
			}
			
			
		};
		
		request.onupgradeneeded  = (e) => {
			console.info('Database created');
			
			const db = request.result;
			//...
			
			const objectStoreX001  = db.createObjectStore('objListaProductosX001' , { keyPath : 'id',autoIncrement: true } );
			objectStoreX001.createIndex("BPrecio", "BPrecio", { unique: false });
			objectStoreX001.createIndex("Barra", "Barra", { unique: false });
			objectStoreX001.createIndex("Cantidad", "Cantidad", { unique: false });
			objectStoreX001.createIndex("Codigo", "Codigo", { unique: false });
			objectStoreX001.createIndex("Descripcion", "Descripcion", { unique: false });
			objectStoreX001.createIndex("Medida", "Medida", { unique: false });
			objectStoreX001.createIndex("Precio", "Precio", { unique: false });
			objectStoreX001.createIndex("cost", "cost", { unique: false });
			objectStoreX001.createIndex("currencyID", "currencyID", { unique: false });
			objectStoreX001.createIndex("display", "display", { unique: false });
			objectStoreX001.createIndex("endOn", "endOn", { unique: false });
			objectStoreX001.createIndex("isInvoice", "isInvoice", { unique: false });
			objectStoreX001.createIndex("isInvoiceQuantityZero", "isInvoiceQuantityZero", { unique: false });
			objectStoreX001.createIndex("itemID", "itemID", { unique: false });
			objectStoreX001.createIndex("iwCost", "iwCost", { unique: false });
			objectStoreX001.createIndex("iwQuantityMax", "iwQuantityMax", { unique: false });
			objectStoreX001.createIndex("iwQuantityMin", "iwQuantityMin", { unique: false });
			objectStoreX001.createIndex("listPriceID", "listPriceID", { unique: false });
			objectStoreX001.createIndex("percentage", "percentage", { unique: false });
			objectStoreX001.createIndex("quantity", "quantity", { unique: false });
			objectStoreX001.createIndex("quantityMax", "quantityMax", { unique: false });
			objectStoreX001.createIndex("quantityMin", "quantityMin", { unique: false });
			objectStoreX001.createIndex("startOn", "startOn", { unique: false });
			objectStoreX001.createIndex("typePriceID", "typePriceID", { unique: false });
			objectStoreX001.createIndex("unitMeasureID", "unitMeasureID", { unique: false });
			
			
			
			
			
			const objectStoreX002  = db.createObjectStore('objListaProductosX002' , { keyPath : 'id',autoIncrement: true } );
			objectStoreX002.createIndex("BPrecio", "BPrecio", { unique: false });
			objectStoreX002.createIndex("Barra", "Barra", { unique: false });
			objectStoreX002.createIndex("Cantidad", "Cantidad", { unique: false });
			objectStoreX002.createIndex("Codigo", "Codigo", { unique: false });
			objectStoreX002.createIndex("Descripcion", "Descripcion", { unique: false });
			objectStoreX002.createIndex("Medida", "Medida", { unique: false });
			objectStoreX002.createIndex("Precio", "Precio", { unique: false });
			objectStoreX002.createIndex("cost", "cost", { unique: false });
			objectStoreX002.createIndex("currencyID", "currencyID", { unique: false });
			objectStoreX002.createIndex("display", "display", { unique: false });
			objectStoreX002.createIndex("endOn", "endOn", { unique: false });
			objectStoreX002.createIndex("isInvoice", "isInvoice", { unique: false });
			objectStoreX002.createIndex("isInvoiceQuantityZero", "isInvoiceQuantityZero", { unique: false });
			objectStoreX002.createIndex("itemID", "itemID", { unique: false });
			objectStoreX002.createIndex("iwCost", "iwCost", { unique: false });
			objectStoreX002.createIndex("iwQuantityMax", "iwQuantityMax", { unique: false });
			objectStoreX002.createIndex("iwQuantityMin", "iwQuantityMin", { unique: false });
			objectStoreX002.createIndex("listPriceID", "listPriceID", { unique: false });
			objectStoreX002.createIndex("percentage", "percentage", { unique: false });
			objectStoreX002.createIndex("quantity", "quantity", { unique: false });
			objectStoreX002.createIndex("quantityMax", "quantityMax", { unique: false });
			objectStoreX002.createIndex("quantityMin", "quantityMin", { unique: false });
			objectStoreX002.createIndex("startOn", "startOn", { unique: false });
			objectStoreX002.createIndex("typePriceID", "typePriceID", { unique: false });
			objectStoreX002.createIndex("unitMeasureID", "unitMeasureID", { unique: false });
			
			
			
			
			const objectStoreX003  = db.createObjectStore('objListaProductosX003' , { keyPath : 'id',autoIncrement: true } );
			objectStoreX003.createIndex("BPrecio", "BPrecio", { unique: false });
			objectStoreX003.createIndex("Barra", "Barra", { unique: false });
			objectStoreX003.createIndex("Cantidad", "Cantidad", { unique: false });
			objectStoreX003.createIndex("Codigo", "Codigo", { unique: false });
			objectStoreX003.createIndex("Descripcion", "Descripcion", { unique: false });
			objectStoreX003.createIndex("Medida", "Medida", { unique: false });
			objectStoreX003.createIndex("Precio", "Precio", { unique: false });
			objectStoreX003.createIndex("cost", "cost", { unique: false });
			objectStoreX003.createIndex("currencyID", "currencyID", { unique: false });
			objectStoreX003.createIndex("display", "display", { unique: false });
			objectStoreX003.createIndex("endOn", "endOn", { unique: false });
			objectStoreX003.createIndex("isInvoice", "isInvoice", { unique: false });
			objectStoreX003.createIndex("isInvoiceQuantityZero", "isInvoiceQuantityZero", { unique: false });
			objectStoreX003.createIndex("itemID", "itemID", { unique: false });
			objectStoreX003.createIndex("iwCost", "iwCost", { unique: false });
			objectStoreX003.createIndex("iwQuantityMax", "iwQuantityMax", { unique: false });
			objectStoreX003.createIndex("iwQuantityMin", "iwQuantityMin", { unique: false });
			objectStoreX003.createIndex("listPriceID", "listPriceID", { unique: false });
			objectStoreX003.createIndex("percentage", "percentage", { unique: false });
			objectStoreX003.createIndex("quantity", "quantity", { unique: false });
			objectStoreX003.createIndex("quantityMax", "quantityMax", { unique: false });
			objectStoreX003.createIndex("quantityMin", "quantityMin", { unique: false });
			objectStoreX003.createIndex("startOn", "startOn", { unique: false });
			objectStoreX003.createIndex("typePriceID", "typePriceID", { unique: false });
			objectStoreX003.createIndex("unitMeasureID", "unitMeasureID", { unique: false });
			
		
			
			
			const objectStoreSkuX001  = db.createObjectStore('objListaProductosSkuX001' , { keyPath : 'id',autoIncrement: true } );
			objectStoreSkuX001.createIndex("Producto", "Producto", { unique: false });
			objectStoreSkuX001.createIndex("Sku", "Sku", { unique: false });
			objectStoreSkuX001.createIndex("Valor", "Valor", { unique: false });
			objectStoreSkuX001.createIndex("catalogItemID", "catalogItemID", { unique: false });
			objectStoreSkuX001.createIndex("itemID", "itemID", { unique: false });			
			
			
			
			const objectStoreConceptX001  = db.createObjectStore('objListaProductosConceptosX001' , { keyPath : 'id',autoIncrement: true } );
			objectStoreConceptX001.createIndex("companyComponentConceptID", "companyComponentConceptID", { unique: false });
			objectStoreConceptX001.createIndex("companyID", "companyID", { unique: false });
			objectStoreConceptX001.createIndex("componentID", "componentID", { unique: false });
			objectStoreConceptX001.createIndex("componentItemID", "componentItemID", { unique: false });
			objectStoreConceptX001.createIndex("isActive", "isActive", { unique: false });	
			objectStoreConceptX001.createIndex("valueIn", "valueIn", { unique: false });	
			objectStoreConceptX001.createIndex("valueOut", "valueOut", { unique: false });	
			
			
			
			const objectStoreCustomerCreditLineX001  = db.createObjectStore('objListaCustomerCreditLineX001' , { keyPath : 'id',autoIncrement: true } );
			objectStoreCustomerCreditLineX001.createIndex("accountNumber","accountNumber", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("balance","balance", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("branchID","branchID", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("companyID","companyID", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("creditLineID","creditLineID", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("currencyID","currencyID", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("currencyName","currencyName", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("customerCreditLineID","customerCreditLineID", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("dateLastPay","dateLastPay", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("dateOpen","dateOpen", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("entityID","entityID", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("interestPay","interestPay", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("interestYear","interestYear", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("isActive","isActive", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("limitCredit","limitCredit", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("line","line", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("note","note", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("periodPay","periodPay", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("periodPayLabel","periodPayLabel", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("statusID","statusID", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("statusName","statusName", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("term","term", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("totalDefeated","totalDefeated", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("totalPay","totalPay", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("typeAmortization","typeAmortization", { unique: false });		
			objectStoreCustomerCreditLineX001.createIndex("typeAmortizationLabel","typeAmortizationLabel", { unique: false });		

			
			
			
		};
		
		//...
	}
	
	
	function addDataDBArray(varTable,varDatos){
		const transaction = db.transaction(varTable, 'readwrite');
		
		
		
		const objectStore = transaction.objectStore(varTable);
		
		// Se agrega un nuevo estudiante		
		for ( var intx = 0 ; intx < varDatos.length ; intx++)
		{
		objectStore.add(varDatos[intx]);
        }
		
		transaction.oncomplete = function(event) {
			//...
		};
		
		transaction.onerror = function(event) {
		  //...
		};
			
		//request.onsuccess = ()=> {			
		//	console.log('success');
		//}
		//
		//request.onerror = (err)=> {
		//	console.log('error');
		//}
	}
	
	function obtenerDataDBProductoArrayUniByItemID(varItemID,varFunctionI)
	{
		obtenerDataDBProductoArray(
			"objListaProductosX001",
			"itemID",
			varItemID,
			"producto1",
			varFunctionI,
			function(e){    
				obtenerDataDBProductoArray(
					"objListaProductosX002",
					"itemID",
					e.itemID,
					"producto2",
					e,
					function(e){ 
						
						obtenerDataDBProductoArray(
							"objListaProductosX003",
							"itemID",
							e.itemID,
							"producto3",
							e,
							function(e){ 
								e.callback(e);				
							}  
						)
					}  
				)     
			}
		)
	}

	function obtenerDataDBProductoArray(varTable,varColumn,varValue,valueComando,varDataExt,varFunction){
		
		const requestStore 	= db.transaction(varTable, 'readwrite')
							.objectStore(varTable);
							
		let request;
		var varIndex;
		
		if(varColumn == "all")
		{
			request 		= requestStore.getAll();
		}
		else 
		{
			varIndex 		= requestStore.index(varColumn);
			request 		= varIndex.getAll(varValue);
		}
		
		request.onsuccess = ()=> {

			try
			{
				
				if(valueComando != "none")
				{					
					varDataExt[valueComando] = request.result;
					varFunction(varDataExt,varDataExt);
				}
				else
				{
					varFunction(request.result,varDataExt);
				}
			}
			catch(ex)
			{
				
			}
			
		}

		request.onerror = (err)=> {
			console.info("error");
		}
	}
	
	
	function addDataDB(varTable,varDatos){
		const transaction = db.transaction(varTable, 'readwrite');
		
		transaction.oncomplete = function(event) {
			//...
		};
		
		transaction.onerror = function(event) {
		  //...
		};
		
		const objectStore = transaction.objectStore(varTable);
		
		// Se agrega un nuevo estudiante
		const request = objectStore.add({"name":varDatos});
		
		request.onsuccess = ()=> {
			// request.result contiene el key del objeto agregado
			console.log('success');
		}
		
		request.onerror = (err)=> {
			console.log('error');
		}
	}
	
	
	function removeDataDB(varTable){
		const request = db.transaction(varTable, 'readwrite')
							  .objectStore(varTable)
							  .clear();

		request.onsuccess = ()=> {
			console.info("success");
		}

		request.onerror = (err)=> {
			console.log('error');
		}
	}
	
	
	
	
	
		 
	
	openDataBaseAndCreate(true,false);	
	
	function fnReady()
	{
		$(document).ready(function(){
		
			 $('#txtDate').datepicker({format:"yyyy-mm-dd"});
			 $('#txtDate').val(moment().format("YYYY-MM-DD"));	
			 $("#txtDate").datepicker("update");
			 $('#txtNextVisit').datepicker({format:"yyyy-mm-dd"});
			 $('#txtDateFirst').datepicker({format:"yyyy-mm-dd"});						 
			 $('#txtDateFirst').val(moment().add('days', 0).format("YYYY-MM-DD"));			 
			 $("#txtDateFirst").datepicker("update");
			 heigthTop							= 300;
			 
			 
		
			 
			 //Incializar Focos en el codigo de barra
			if(varParameterScanerProducto != "false")
			{
				document.getElementById("txtScanerCodigo").focus();			
			}
			
			
			
			
			if(varParameterScrollDelModalDeSeleccionProducto == "true"){
				$("#modal_body_popup_productos").css("overflow","auto");
				$("#modal_body_popup_productos").css("height",varParameterAlturaDelModalDeSeleccionProducto);
			}
			
			
			if (varAutoAPlicar == "true" || varParameterRegresarAListaDespuesDeGuardar == "true")
			{
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
						  
						  if(varParameterRegresarAListaDespuesDeGuardar == "true"){
							window.location	= "<?php echo base_url(); ?>/app_invoice_billing/index";
						  }
						  if(varParameterRegresarAListaDespuesDeGuardar != "true"){
							window.location	= "<?php echo base_url(); ?>/app_invoice_billing/add";
						  }
						  
						  fnWaitClose();
				});
			}
			
			
			
			
		
			objTableDetail = $("#tb_transaction_master_detail").dataTable({
				"bPaginate"		: false,
				"bFilter"		: false,
				"bSort"			: false,
				"bInfo"			: false,
				"bAutoWidth"	: false,
				"aoColumnDefs": [ 
							{
								"aTargets"		: [ 0 ],//checked
								"sWidth"		: "50px",
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
								"aTargets"		: [ 3 ],//itemNumber
								"sWidth"		: "250px",
								"mRender"		: function ( data, type, full ) {
									return '<input type="text"  class="col-lg-12" style="text-align:left" value="'+data+'" readonly="true" />';
								}
							},
							{
								"aTargets"		: [ 4 ],//descripcion
								"sWidth"		: "250px",
								"mRender"		: function ( data, type, full ) 
								{
								
									
																	
									obtenerDataDBProductoArray(
										"objListaProductosX001",
										"all",
										0,
										"all",
										{"row" : full },
										function(e){    
											
											
											var classHiddenTex 		= "";
											var classHiddenSelect 	= "";
											if(varParameterINVOICE_BILLING_SELECTITEM == "true")									
											{
												classHiddenTex = "hidden";
												classHiddenSelect 	= "";
											}
											else 
											{
												classHiddenTex = "";
												classHiddenSelect 	= "hidden";
											}	
											
											
											var full 			= e.row
											var productos 		= e.all;
											var strFiled 		= '<input type="text" name="txtTransactionDetailName[]" id="txtTransactionDetailName'+full[2]+'"  class="col-lg-12 '+classHiddenTex+'" style="text-align:left" value="'+full[4]+'" '+NameStatus+' />';
											
											var strFiledSelecte = "<select  name='txtItemSelected' class='<?php echo ($useMobile == "1" ? "" : "select2"); ?> txtItemSelected "+classHiddenSelect+" ' >";
											strFiledSelecte		= strFiledSelecte+"<option value='"+full[2]+"' selected data-itemid='"+full[2]+"' data-codigo='"+full[3]+"' data-name='"+full[4].replace("'","").replace("'","") +"' data-unidadmedida='"+full[5]+"' data-cantidad='"+full[6]+"' data-precio='"+full[7]+"' data-barra='"+full[3]+"'  data-description='"+full[4].replace("'","").replace("'","") + "'    >"+ full[4].replace("'","").replace("'","")  +"</option>";
											for(var i = 0 ; i < productos.length; i++)
											{
												strFiledSelecte		= strFiledSelecte+"<option value='"+productos[i].itemID+"' data-itemid='"+productos[i].itemID+"' data-codigo='"+productos[i].Codigo+"'  data-name='"+ productos[i].Nombre.replace("'","").replace("'","")  +"'   data-unidadmedida='"+productos[i].Medida+"' data-cantidad='"+productos[i].quantity+"' data-precio='"+productos[i].Precio+"' data-barra='"+productos[i].Barra+"'  data-description='"+productos[i].Nombre+"'    >"+ productos[i].Nombre.replace("'","").replace("'","")  +"</option>";
											}
											strFiledSelecte		= strFiledSelecte+"</select>";
											
											
											strFiledSelecte 	=  strFiled + strFiledSelecte ;
											$("#divRowDescription"+full[2]).parent().html( strFiledSelecte );
											
											
										}
									);
										
									return "<div id='divRowDescription"+full[2]+"'></div>";
									
									
									
								}
							},
							{
								"aTargets"		: [ 5 ],//Sku
								"sWidth"		: "250px",
								"mRender"		: function ( data, type, full ) {
									
									
									//Obtener los sku de la base de datos
									obtenerDataDBProductoArray(
										"objListaProductosSkuX001",
										"itemID",full[2],"none",full,
										function(e,full){ 
											console.info(e);
												
												var objListaSkuByProducto 	= e;
												var sel 					= '';
												var espacio					=  "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";								
												sel 						= '<select name="txtSku[]" id="txtSku'+full[2]+'" class="txtSku col-lg-12"  >';									
												
												if(varUseMobile == "1")
													espacio = "";		
												
												if(objListaSkuByProducto.length == 0)
												{
													sel = sel + '<option value="0" data-skuv="1" data-skupriceunitary="'+full[7]+'"   selected style="font-size:200%" data-description="UNIDAD" >UNIDAD'+espacio+'</option>';
												}
												else{
													for(var ix = 0 ; ix < objListaSkuByProducto.length ; ix++)
													{
														if(objListaSkuByProducto[ix].catalogItemID == data)
															sel = sel + '<option value="'+objListaSkuByProducto[ix].catalogItemID+'" data-skuv="'+objListaSkuByProducto[ix].Valor+'" data-skupriceunitary="'+full[7]+'"  style="font-size:200%" selected data-description="'+objListaSkuByProducto[ix].Sku+'" >'+objListaSkuByProducto[ix].Sku+espacio+'</option>';
														else
															sel = sel + '<option value="'+objListaSkuByProducto[ix].catalogItemID+'" data-skuv="'+objListaSkuByProducto[ix].Valor+'" data-skupriceunitary="'+full[7]+'"  style="font-size:200%"  data-description="'+objListaSkuByProducto[ix].Sku+'"  >'+objListaSkuByProducto[ix].Sku+espacio+'</option>';
													}																				
												}
												
												sel = sel + '</select>';																			
												$("#divRowSku"+full[2]).parent().html( sel );
												
										}
									);
									
									return "<div id='divRowSku"+full[2]+"'></div>";
									
									
									
									
											
								}
							},
							{
								"aTargets"		: [ 6 ],//Cantidad
								"sWidth"		: "250px",
								"mRender"		: function ( data, type, full ) {
									var str =  '<input type="text" class="col-lg-12 txtQuantity txt-numeric" id="txtQuantityRow'+full[2]+'"  value="'+data+'" name="txtQuantity[]" style="text-align:right" /> ';
									
									if (varUseMobile == "1")
									str = str + " <span class='badge badge-inverse' >Cantidad</span>";
							
									return str;
								}
							},
							{
								"aTargets"		: [ 7 ],//Precio
								"sWidth"		: "250px",
								"mRender"		: function ( data, type, full ) {
									var str =  '<input type="text" class="col-lg-12 txtPrice txt-numeric"   id="txtPriceRow'+full[2]+'"   '+PriceStatus+'  value="'+data+'" name="txtPrice[]" style="text-align:right" />';
									if (varUseMobile == "1")
									str = str + " <span class='badge badge-inverse' >Precio</span>";
									return str;
								}
							},
							{
								"aTargets"		: [ 8 ],//Total
								"sWidth"		: "250px",
								"mRender"		: function ( data, type, full ) {
									var str = '<input type="text" class="col-lg-12 txtSubTotal" readonly value="'+data+'" name="txtSubTotal[]" style="text-align:right" />';
									if (varUseMobile == "1")
									str = str + " <span class='badge badge-inverse' >Total</span>";
									return str;
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
							},
							{
								"aTargets"		: [ 12 ],//PlusDimus	
								"sWidth"		: "250px",							
								"mRender"		: function ( data, type, full ) {	

									obtenerDataDBProductoArrayUniByItemID(
										full[2],
										{
											"all":full[2],
											"callback":function(e){ 
												
												
												
												//publico
												var objProductoPrecio1 	= e.producto1;
												//por mayor
												var objProductoPrecio2 	= e.producto2;
												//credito
												var objProductoPrecio3 	= e.producto3;
												
												//publico
												objProductoPrecio1 = objProductoPrecio1[0].Precio;
												//por mayor
												objProductoPrecio2 = objProductoPrecio2[0].Precio;
												//credito
												objProductoPrecio3 = objProductoPrecio3[0].Precio;
												
												//publico
												objProductoPrecio1 = fnFormatFloat(objProductoPrecio1);
												//por mayor
												objProductoPrecio2 = fnFormatFloat(objProductoPrecio2);
												//credito
												objProductoPrecio3 = fnFormatFloat(objProductoPrecio3);
												
												var styleButtom = "";
												if(varUseMobile == "1")
												styleButtom = "style='text-align:right'";
											
												var str = "<div "+styleButtom+" >";
												
												if(varParameterINVOICE_BILLING_SELECTITEM == "true")
												{
													str    	= str + '' + 
													'<button type="button" class="btn btn-warning btnAddSelectedItem"><span class="icon16 i-archive"></span> </button>';
												}
												
												str    	= str + '' + 
												'<button type="button" class="btn btn-primary btnMenus"><span class="icon16 i-minus"></span> </button>';
												
												str    	= str + '' + 
												'<button type="button" class="btn btn-primary btnPlus"><span class="icon16 i-plus"></span> </button>';
												
												
												
												
												str		= str+'<div class="btn-group">';
														str = 	str+'<button type="button" class="btn btn-success dropdown-toggle  " data-toggle="dropdown"><i class="icon16 i-bookmark"></i>  <span class="caret"></span> </button>';
														str =	str+'<ul class="dropdown-menu">';
															//publico											
															if( (objProductoPrecio1 > 0 && varPermisosEsPermitidoSeleccionarPrecioPublico == true  )   || isAdmin == "1" )
																str = str+'<li><a href="#" data-precio="'+objProductoPrecio1+'" class="btnPrecioRecomendado" >'+varCurrencyDefaultSimbol+" "+$.number(objProductoPrecio1,2)+'</a></li>';
															
															//por mayor
															if( ( objProductoPrecio2 > 0 && fnValidateSiAplicaPrecioPublico() && varPermisosEsPermitidoSeleccionarPrecioPormayor == true  ) || isAdmin == "1" ) 
																str = str+'<li><a href="#" data-precio="'+objProductoPrecio2+'" class="btnPrecioRecomendado" >'+varCurrencyDefaultSimbol+" "+$.number(objProductoPrecio2,2)+'</a></li>';
															
															//credito 
															if( (objProductoPrecio3 > 0 && varPermisosEsPermitidoSeleccionarPrecioCredito == true ) || isAdmin == "1"   )
																str = str+'<li><a href="#" data-precio="'+objProductoPrecio3+'" class="btnPrecioRecomendado"  >'+varCurrencyDefaultSimbol+" "+$.number(objProductoPrecio3,2)+'</a></li>';
															
														str = 	str+'</ul>';
												str		= str+'</div>';
												str		= str+'</div>';
												
												
												
												$("#divRowPrice"+e.all).parent().html( str );
												
											}
										}
									);
									
									return "<div id='divRowPrice"+full[2]+"'></div>";
								}
							},
							{
								"aTargets"		: [ 13 ],//skuFormatoDescription
								"bVisible"		: true,
								"sClass"		: "hidden",
								"bSearchable"	: false,
								"mRender"		: function ( data, type, full ) {
									return '<input type="text" class="col-lg-12 skuFormatoDescription" value="'+data+'" name="skuFormatoDescription[]" style="text-align:right" />';
								}
								//,
								//"fnCreatedCell": varUseMobile == "0" ? function(){  } :  function (td, cellData, rowData, row, col) 
								//{
								//	  $(td).css("display","block");
								//}
							}

				]							
			});
			
			
			//Renderizar combobox de las lineas de credito			
			fnRenderLineaCredit(objListCustomerCreditLine,objCausalTypeCredit);	

			
		});
	}


		
		

</script>

