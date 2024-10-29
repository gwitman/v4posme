<!-- ./ https://fontawesome.com/v3/icon/print -->
<!-- ./ page heading -->
<script>	

	//fnWaitOpen();	
	
	/*
	try {
		var i = lum/0;
	} 
	catch (error) 
	{
		console.info("variable no existe");
	}
	*/
	
	
	var heigthTop				= 0;
	var objTableDetail 			= {};		
	var tmpData 				= [];
	var tmpInfoClient			= 0;
	
	
	var scrollPosition						= 0;
	var warehouseID 						= $("#txtWarehouseID").val();
	var isAdmin								= '<?php echo $isAdmin; ?>';
	var openedSearchWindow					= false;
	var objWindowSearchProduct;
	var objTableProductosSearch 			= null;	
	var objRowTableProductosSearch 			= null;	
	var varBaseUrl							= '<?php echo base_url(); ?>';
	var varStatusInvoiceAplicado			= 67; //Estado Aplicada
	var varStatusInvoiceAnular				= 68; //Anular
	var varCurrencyDefaultSimbol			= '<?php echo $objCurrency->simbol; ?>';
	var varIsMobile							= '<?php echo $isMobile; ?>';
	var varUseMobile						= '<?php echo $useMobile; ?>';	
	var varParameterTipoPrinterDownload		= '<?php echo $objParameterTipoPrinterDonwload; ?>';	
	var objParameterPrinterDirectAndPreview	= '<?php echo $objParameterPrinterDirectAndPreview; ?>';	
	var varParameterCustomPopupFacturacion	= '<?php echo $objParameterCustomPopupFacturacion; ?>';	
	var varParameterScanerProducto			= '<?php echo $objParameterScanerProducto; ?>';
	var varPermitirFacturarProductosEnZero	= '<?php echo $objParameterInvoiceBillingQuantityZero; ?>';
	var varParameterShowComandoDeCocina 	= <?php echo $objParameterShowComandoDeCocina; ?>;
	var varParameterCantidadItemPoup		= '<?php echo $objParameterCantidadItemPoup; ?>';  
	var varParameterRestaurante				= '<?php echo $objParameterRestaurant; ?>';
	
	var varParameterHidenFiledItemNumber	= <?php echo $objParameterHidenFiledItemNumber; ?>;  	
	var varParameterAmortizationDuranteFactura		= <?php echo $objParameterAmortizationDuranteFactura; ?>;  	
	var varParameterImprimirPorCadaFactura 			= '<?php echo $objParameterImprimirPorCadaFactura; ?>';
	var varParameterRegresarAListaDespuesDeGuardar	= '<?php echo $objParameterRegresarAListaDespuesDeGuardar; ?>';
	var objParameterPantallaParaFacturar 			= '<?php echo $objParameterPantallaParaFacturar; ?>';		
	var varParameterAlturaDelModalDeSeleccionProducto	= '<?php echo $objParameterAlturaDelModalDeSeleccionProducto; ?>';
	var varParameterScrollDelModalDeSeleccionProducto	= '<?php echo $objParameterScrollDelModalDeSeleccionProducto; ?>';	
	var varParameterINVOICE_BILLING_SELECTITEM			= '<?php echo $objParameterINVOICE_BILLING_SELECTITEM; ?>';
	var varParameterMostrarImagenEnSeleccion = '<?php echo $objParameterMostrarImagenEnSeleccion; ?>';
	var varUrlPrinter			= '<?php echo $urlPrinterDocument; ?>';
	var varUrlPrinterOpcion2	= '<?php echo $urlPrinterDocumentOpcion2; ?>';
	var varUrlPrinterCocina		= '<?php echo $urlPrinterDocumentCocina; ?>'; 
	var varUrlPrinterBar		= '<?php echo $objParameterINVOICE_BILLING_PRINTER_URL_BAR; ?>'; 
	
	var objParameterINVOICE_OPEN_CASH_WHEN_PRINTER_INVOICE		= '<?php echo $objParameterINVOICE_OPEN_CASH_WHEN_PRINTER_INVOICE; ?>'; 
	var objParameterINVOICE_OPEN_CASH_PASSWORD					= '<?php echo $objParameterINVOICE_OPEN_CASH_PASSWORD; ?>'; 
	
	var varDetail 				= JSON.parse('<?php echo json_encode($objTransactionMasterDetail); ?>');	
	var varDetailWarehouse		= JSON.parse('<?php echo json_encode($objTransactionMasterDetailWarehouse); ?>');	
	var varDetailConcept 		= JSON.parse('<?php echo json_encode($objTransactionMasterDetailConcept); ?>');	
	var varParameterInvoiceBillingPrinterDirect				= '<?php echo $objParameterInvoiceBillingPrinterDirect; ?>';	
	var varParameterInvoiceBillingPrinterDirectUrl			= '<?php echo $objParameterInvoiceBillingPrinterDirectUrl; ?>';	
	var varParameterInvoiceBillingPrinterDirectCocinaUrl	= '<?php echo $urlPrinterDocumentCocinaDirect; ?>';	
	var varParameterInvoiceBillingPrinterDirectBarUrl		= '<?php echo $objParameterINVOICE_BILLING_PRINTER_DIRECT_URL_BAR; ?>';	
	var varParameterInvoiceBillingPrinterDataLocal			= '<?php echo $dataPrinterLocal; ?>';
	var varParameterUrlServidorDeImpresion 					= '<?php echo $objParameterUrlServidorDeImpresion; ?>';
	var varTransactionCausalID	= <?php echo $objTransactionMaster->transactionCausalID; ?>;	
	var varCustomerCrediLineID	= <?php echo $objTransactionMaster->reference4; ?>;	
	var varPermisos				= JSON.parse('<?php echo json_encode($objListaPermisos); ?>');
	var varPermisosEsPermitidoModificarPrecio 			= jLinq.from(varPermisos).where(function(obj){ return obj.display == "ES_PERMITIDO_MODIFICAR_PRECIO_EN_FACTURACION"}).select().length > 0 ?	true: 	false;	
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
	
	var objTransactionMasterItemPrice 			= JSON.parse('<?php echo json_encode($objTransactionMasterItemPrice); ?>');
	var objTransactionMasterItemConcepto 		= JSON.parse('<?php echo json_encode($objTransactionMasterItemConcepto); ?>');
	var objTransactionMasterItemSku 			= JSON.parse('<?php echo json_encode($objTransactionMasterItemSku); ?>');
	var objTransactionMasterItem 				= JSON.parse('<?php echo json_encode($objTransactionMasterItem); ?>');	
	var objRenderInit							= true;
    var $grid;
	
	if(varDetail != null){
		for(var i = 0 ; i < varDetail.length;i++){
			//Obtener Iva
			var tmp_ = jLinq.from(varDetailConcept).where(function(obj){ return obj.componentItemID == varDetail[i].componentItemID && obj.name == "IVA" }).select();
			var iva_ = (tmp_.length <= 0 ? 0 : parseFloat(tmp_[0].valueOut));
			var Precio2 = jLinq.from(objTransactionMasterItemPrice).where(function(obj){ return (obj.itemID == varDetail[i].componentItemID && obj.typePriceID == "155"); }).select()[0].Precio;
			var Precio3 = jLinq.from(objTransactionMasterItemPrice).where(function(obj){ return (obj.itemID == varDetail[i].componentItemID && obj.typePriceID == "156"); }).select()[0].Precio;
			var tax2	= varDetail[i].tax2;
			
			//Rellenar Datos
			tmpData.push([
				0,
				varDetail[i].transactionMasterDetailID,
				varDetail[i].componentItemID,
				varDetail[i].itemNumber,
				"'"+varDetail[i].itemNameLog + "'", 
				varDetail[i].skuCatalogItemID,
				fnFormatNumber(varDetail[i].skuQuantity,2),
				fnFormatNumber(varDetail[i].unitaryPrice *  varDetail[i].skuQuantityBySku, 4),/*precio sistema*/
				fnFormatNumber(varDetail[i].unitaryPrice *  varDetail[i].skuQuantityBySku * varDetail[i].skuQuantity,2), /*precio por cantidad*/							
				//fnFormatNumber(iva_,2),
				fnFormatNumber(varDetail[i].tax1 / varDetail[i].unitaryPrice,2),
				fnFormatNumber(varDetail[i].skuQuantityBySku, 4),
				fnFormatNumber(varDetail[i].unitaryPrice, 4),
				"",//acciones
				varDetail[i].skuFormatoDescription,
				Precio2,
				Precio3,
				"'"+varDetail[i].itemNameDescriptionLog + "'", 
				fnFormatNumber(varDetail[i].tax2 / varDetail[i].unitaryPrice,2) /*tax_services*/
			]);
		}
	}	

	



		
	
	
	
	
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
			
			var resultTotal =  (ingresoCordoba +  bancoCordoba + puntoCordoba + tarjetaCordoba + ( bancoDolares / tipoCambio ) + ( tarejtaDolares / tipoCambio )  + (ingresoDol / tipoCambio)) - total;
			var resultTotal = fnFormatNumber(resultTotal,2);
			$("#txtChangeAmount").val(resultTotal);	
		}
		
		if( $("#txtCurrencyID").val() == "2" /*Cordoba*/ )
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
	
	
	$("#btnAceptarMesaBussyV2").click(function(){
		fnWaitOpen();
		var value 				= $("#txtMesaOcupada").val();
		window.location.href 	= "<?=base_url()."/"."app_invoice_billing/edit/companyID/".$companyID."/transactionID/".$transactionID."/transactionMasterID/"?>"+value+"<?="/codigoMesero/".$codigoMesero ?>";			
		$("#modalDialogMeaBussyV2").modal("hide");
	});	
			
			
	$("#modalDialogClaveOpenCash").click(function()
	{
		
		if( $("#txtClaveOpenCash").val() == objParameterINVOICE_OPEN_CASH_PASSWORD )
		{
			 $.ajax({
					async: 		true,
					type: 		"GET",
					url: 		"<?php echo base_url(); ?>/app_invoice_billing/viewPrinterOpen",						
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
			
			$("#modalDialogClaveOpenCash").modal("hide");
		}
	});	
	
	$("#btnAceptarDialogBarV2").click(function(){
				
		var listRow = objTableDetail.fnGetData();							
		var length 	= listRow.length;
		
		var i 		= 0;
		var itemid 	= "-1";
		
		while (i < length ){
			if(listRow[i][0] == true){
				itemid = itemid + ","+listRow[i][2];				
			}
			i++;
		}
	
	
		fnWaitOpen();
		window.open("<?php echo base_url(); ?>/"+
			varUrlPrinterBar+
			"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>"+
			"/itemID/"+itemid
			, '_blank'
		);
		
		fnWaitClose();
		$("#modalDialogBarV2").modal("hide");
	});	
	
	$("#btnAceptarDialogCocinaV2").click(function(){
		
		var listRow = objTableDetail.fnGetData();							
		var length 	= listRow.length;
		
		var i 		= 0;
		var itemid 	= "-1";
		
		while (i < length ){
			if(listRow[i][0] == true){
				itemid = itemid + ","+listRow[i][2];				
			}
			i++;
		}
	
	
		fnWaitOpen();
		window.open("<?php echo base_url(); ?>/"+
			varUrlPrinterCocina+
			"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>"+
			"/itemID/"+itemid
			, '_blank'
		);
		
		fnWaitClose();
		$("#modalDialogCocinaV2").modal("hide");
	});	
	
	$(document).on("click","#btnAbrirCaja",function(){
		$("#txtClaveOpenCash").val("");
		$("#modalDialogClaveOpenCash").model("show");
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
	

	//Pago
	$(document).on("click","#btnOptionPago",function(){		
		 $("#mySidebar").css("width","100%");
		 
	});				
	$(document).on("click","#btnRollbackPayment",function(){
		var sidebar = $("#mySidebar");		
		sidebar.css("width", "0");		
		 
	});
	
	//Detalle de factura
	$(document).on("click","#btnVeDetalleFactura",function(){		
		 $("#mySidebarFactura").css("width","100%");
		 
	});				
	$(document).on("click","#btnRollbackFactura",function(){
		var sidebar = $("#mySidebarFactura");		
		sidebar.css("width", "0");
	});
	
	//Zona
	$(document).on("click","#btnShowZona",function(){		
		 $("#mySidebarZona").css("width","100%");
		 $("#mySidebarZona").removeClass("hidden");
	});				
	$(document).on("click","#btnRollbackZona",function(){
		var sidebar = $("#mySidebarZona");		
		sidebar.css("width", "0");
		sidebar.addClass("hidden");
	});
	//Mesa
	$(document).on("click","#btnShowMesa",function(){		
		 $("#mySidebarMesa").css("width","100%");
		 $("#mySidebarMesa").removeClass("hidden");
	});				
	$(document).on("click","#btnRollbackMesa",function(){
		var sidebar = $("#mySidebarMesa");		
		sidebar.css("width", "0");
		sidebar.addClass("hidden");
	});

	$(document).on("click","#btnRollbackZonas",function(){
		var sidebar = $("#mySidebarMesa");
		sidebar.css("width", "0");
		sidebar.addClass("hidden");
		$("#mySidebarZona").css("width","100%");
		$("#mySidebarZona").removeClass("hidden");
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
		//Aplicar
		if(e.key === "a"  && e.ctrlKey) { 							
			var valueWorkflow = $(".btnAceptAplicar").data("valueworkflow");
			$("#txtStatusID").val(valueWorkflow);			
			fnEnviarFactura();				 
		}		
		
		//Regresar al scaner
		if(e.key === "b"  && e.ctrlKey) { 		
			document.getElementById("txtScanerCodigo").focus();				
		}		
		
		
		//e.preventDefault();
		//e.stopPropagation();
		
	});
	$(document).on("keydown",'#txtScanerCodigo', function(e) {
		
		
		var code = e.keyCode || e.which;						
		//Imprimir
		if(e.key === "m" && e.ctrlKey) { 
			fnImprimir();			 
		}
		//Nueva
		if(e.key === "k" && e.ctrlKey) { 		
			e.preventDefault();
			e.stopPropagation();			
			window.location = "<?php echo base_url(); ?>/app_invoice_billing/add/codigoMesero/<?php echo $codigoMesero; ?>";			 
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
		if(codigoABuscar.includes("++") ){
			codigoABuscar = codigoABuscar.replace("++","");
			fnCreateTableSearchProductos(codigoABuscar);
			return;
		}
		
		//Mover a ingreso de dinero Cordoba
		if(codigoABuscar == ""){
			document.getElementById("txtReceiptAmount").focus();
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
				var codigoABuscar 	= e.codigoABuscar.toUpperCase();
				e 					= e.all;
				var encontrado		= false;				
				for(var i = 0 ; i < e.length ; i++)
				{
					
					if(encontrado == true)
					{
						i--;
						break;
					}
					
					//buscar por codigo de sistema					
					var currencyTemp	= e[i].currencyID;
					var currencyID 		= $("#txtCurrencyID").val();
					
					var warehouseIDTemp		= e[i].warehouseID;
					var warehouseID			= $("#txtWarehouseID").val();
					
					if(  
						currencyID == currencyTemp && 
						fnDeleteCerosIzquierdos(codigoABuscar) == fnDeleteCerosIzquierdos(e[i].Codigo.replace("BITT","").replace("ITT","").toUpperCase())  &&
						warehouseID == warehouseIDTemp 
					)
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
							if( 
								fnDeleteCerosIzquierdos(listCodigTmp[ii].toUpperCase()) == fnDeleteCerosIzquierdos(codigoABuscar) && 
								currencyID == currencyTemp  &&
								warehouseID == warehouseIDTemp 
							)
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
					filterResultArray[20] 	= filterResult.Medida;
					filterResultArray[21] 	= filterResult.Cantidad;
					filterResultArray[22] 	= filterResult.Precio;
					filterResultArray[23] 	= filterResult.unitMeasureID;				
					filterResultArray[24] 	= filterResult.Descripcion;
					filterResultArray[25] 	= filterResult.Precio2;
					filterResultArray[26] 	= filterResult.Precio3;
					//Agregar el Item a la Fila
					 onCompleteNewItem(filterResultArray,sumar); 
				}
				 
			}
			
		);
		
		
		 
	});
	
	//Buscar Factura 
	$(document).on("click","#btnSelectInvoice",function(){
		var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentTransactionBilling->componentID; ?>/onCompleteSelectInvoice/SELECCIONAR_BILLING_REGISTER/true/empty/false/not_redirect_when_empty";
		window.open(url_request,"MsgWindow","width=900,height=450");
		window.onCompleteSelectInvoice = onCompleteSelectInvoice; 
	});
	
	
	//Buscar el Cliente
	$(document).on("click","#btnSearchCustomer",function(){
		
		
		//Ocultar Boton de Contado
		$("#divTipoFactura").addClass("hidden");			
		
		
		var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCustomer->componentID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_BILLING/true/empty/false/not_redirect_when_empty";
		window.open(url_request,"MsgWindow","width=900,height=450");
		window.onCompleteCustomer = onCompleteCustomer; 
	});						

	//Eliminar Cliente
	$(document).on("click","#btnClearCustomer",function(){
				$("#txtCustomerID").val("");
				$("#txtCustomerDescription").val("");
	});
	
	
	//Imprimir Documento
	$(document).on("click","#btnPrinter",function(){	
		fnImprimir();
	});
	$(document).on("click","#btnFooter",function(){	
		fnImprimirCocina();
	});
	$(document).on("click","#btnBar",function(){	
		fnImprimirBar();
	});
	
	//Cambios
	$(document).on("change","#txtCausalID,#txtCustomerCreditLineID,#txtCurrencyID,#txtWarehouseID",function(){
		objWindowSearchProduct = null;
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
			var listRow = objTableDetail.fnGetData();							
			var length 	= listRow.length;
			if(length > 0)
			{
				$("#modalDialogBackToListV2").modal("show");
			}
			else 
			{
				fnWaitOpen();
				window.location.href = '<?php echo base_url(); ?>/app_invoice_billing/index'; 				
			}
	});
	
	//Evento Agregar el Usuario
	$(document).on("click",".btnAcept",function(e){
			
			e.preventDefault();
			var valueWorkflow = $(this).data("valueworkflow");
			$("#txtStatusID").val(valueWorkflow);			
			fnEnviarFactura();
		
	});
	
	$(document).on("click","#btnSaveInvoice",function(e){
			
			
			e.preventDefault();
			var valueWorkflow = $(".btnAcept").data("valueworkflow");
			$("#txtStatusID").val(valueWorkflow);			
			fnEnviarFactura();
		
	});

	
	
	
	//Eliminar Documento
	$(document).on("click","#btnDelete",function(){	
		
		fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
			
			fnWaitOpen();
			$.ajax({									
				cache       : false,
				dataType    : 'json',
				type        : 'POST',
				url  		: "<?php echo base_url(); ?>/app_invoice_billing/delete",
				data 		: {companyID : <?php echo $objTransactionMaster->companyID;?>, transactionID : <?php echo $objTransactionMaster->transactionID;?>,transactionMasterID : <?php echo $objTransactionMaster->transactionMasterID; ?>  },
				success:function(data){
					console.info("complete delete success");
					fnWaitClose();
					if(data.error){
						fnShowNotification(data.message,"error");
					}
					else{
						window.location = "<?php echo base_url(); ?>/app_invoice_billing/add/codigoMesero/<?php echo $codigoMesero; ?>";
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
	
	//Nuevo Producto
	$(document).on("click","#btnNewItem",function()
	{		
		var CodigoBuscar = "";
		fnCreateTableSearchProductos(CodigoBuscar);
		
	});
	$(document).on("click","#btnNewItemCatalog",function(){
		var url_request 				 = "<?php echo base_url(); ?>/app_inventory_item/add";
		window.open(url_request,"MsgWindow","width=700,height=600");			
		window.fnObtenerListadoProductos = fnObtenerListadoProductos; 
	});
	$(document).on("click","#btnRefreshDataCatalogo",function(){
		fnWaitOpen();	
		openDataBaseAndCreate(false,true);
	});
	$(document).on("click","#btnLinkPayment",function(){
		fnWaitOpen();			
		window.open("<?php echo base_url(); ?>/app_invoice_api/getLinkPaymentPagadito/companyID/"+$("#txtCompanyID").val()+"/transactionID/"+$("#txtTransactionID").val() +"/transactionMasterID/"+$("#txtTransactionMasterID").val(),"MsgWindow","width=700,height=600");
		fnWaitClose();
	});
	$(document).on("click","#btnSearchCustomerNew",function(){
		var url_request 				 = "<?php echo base_url(); ?>/app_cxc_customer/add/callback/fnCustomerNewCompleted";
		window.open(url_request,"MsgWindow","width=700,height=600");
		window.fnCustomerNewCompleted = fnCustomerNewCompleted; 	
	});

	//Eliminar Item
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
	
	
	//Ir a archivos
	$(document).on("click","#btnClickArchivo",function(){
		window.open("<?php echo base_url()."core_elfinder/index/componentID/".$objComponentBilling->componentID."/componentItemID/".$objTransactionMaster->transactionMasterID; ?>","blanck");
	});
	//Cambio en los recibido
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

    $('.item-categoria').on('click', function () {
        $(".custom-table-container-inventory").show();
        var filterValue = $(this).attr('data-filter');

        $grid.isotope({ filter: filterValue + ', .item-producto-back' });


        $(".item-categoria").removeClass('selected');
        $(this).addClass("selected");
    });

    $('#txtZoneID').change(function() {
        zonaDefault = $(this).val();
        $(".custom-table-mesas").find("td").addClass("hidden");
        $(".custom-table-mesas").find('td[data-parent="'+zonaDefault+'"]').removeClass("hidden");
    });

    $(".container-overlay").click(function () {
        $(".container-overlay").removeClass('selected');
        $(this).addClass("selected");
    });

	$("#btnAceptarDialogBackToListV2").click(function(){								
		fnWaitOpen();
		window.location.href = '<?php echo base_url(); ?>/app_invoice_billing/index'; 	
	});


	$("#btnAceptarDialogPrinterV2AceptarTabla").click(function(){
		fnWaitOpen();
		window.open("<?php echo base_url(); ?>/app_cxc_report/document_credit/viewReport/true/documentNumber/<?php echo $objTransactionMaster->transactionNumber;?>", '_blank');
		fnWaitClose();		
		$('#modalDialogPrinterV2').modal('hide');
	});
	
	$("#btnAceptarDialogPrinterV2AceptarDocument").click(function(){
		fnWaitOpen();
		window.open("<?php echo base_url(); ?>/"+varUrlPrinter+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>", '_blank');
		fnWaitClose();	
		$('#modalDialogPrinterV2').modal('hide');
	});
	$("#btnAceptarDialogPrinterV2AceptarDocumentA4").click(function(){
		fnWaitOpen();
		window.open("<?php echo base_url(); ?>/"+varUrlPrinterOpcion2+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>", '_blank');
		fnWaitClose();	
		$('#modalDialogPrinterV2').modal('hide');
	});
	
	
	$("#btnAceptarDialogPrinterV2AceptarDirect").click(function(){
		fnWaitOpen();
		
		var url=varParameterUrlServidorDeImpresion+varParameterInvoiceBillingPrinterDirectUrl;
			url = url+
			"/companyID/"+"<?php echo $objTransactionMaster->companyID; ?>" + 
			"/transactionID/"+"<?php echo $objTransactionMaster->transactionID; ?>"+
			"/transactionMasterID/"+"<?php echo $objTransactionMaster->transactionMasterID; ?>";
		
		fnWaitOpen();	
		$.ajax({									
			cache       : false,
			dataType    : 'json',
			type        : 'POST',
			data		: { "fromServer" : varParameterInvoiceBillingPrinterDataLocal },
			url  		: url,
			success		: function(){
				fnWaitClose();						
			},
			error:function(xhr,data){
				console.info("complete data error");									
				console.info(data);
				console.info(xhr);
				fnWaitClose();
				//fnShowNotification("Error 505","error");
			}
		});	
			
		$('#modalDialogPrinterV2').modal('hide');
	});
	
	
	
	

	$('#txtPorcentajeDescuento').on('input', function() {
		 //validar que solo sea numero
		 var valor = $(this).val();
		 var expresion = /^\d*\.?\d{0,2}$/;
		 if (!expresion.test(valor)) {
			$(this).val(valor.slice(0, -1));
		 }
		 fnRecalculateDetail(true,"");
    });
	
	//Cargar Factura
	function onCompleteSelectInvoice(objResponse){
		console.info("CALL onCompleteSelectInvoice");
		
		if(objResponse == undefined)
			return;
		
		if(objParameterPantallaParaFacturar == "-")	
			window.location	= "<?php echo base_url(); ?>/app_invoice_billing/edit/companyID/"+objResponse[0][0]+"/transactionID/"+objResponse[0][1]+"/transactionMasterID/"+objResponse[0][2]+"/codigoMesero/"+$("#txtCodigoMesero").val();
		else
			window.location	= "<?php echo base_url(); ?>/app_invoice_billing/"+objParameterPantallaParaFacturar+"/companyID/"+objResponse[0][0]+"/transactionID/"+objResponse[0][1]+"/transactionMasterID/"+objResponse[0][2]+"/codigoMesero/"+$("#txtCodigoMesero").val();
			
			
	}
	
	//Cargar Cliente
	function onCompleteCustomer(objResponse){
		console.info("CALL onCompleteCustomer");
	
	
		var entityID = objResponse[0][1];
		$("#txtCustomerID").val(objResponse[0][1]);
		$("#txtCustomerDescription").val(objResponse[0][2] + " " + objResponse[0][3] + " / " + objResponse[0][4]);
	
		fnClearData();
		fnGetCustomerClient(entityID);
		
				
	}
	//Buscar Linea
	
	
	//Nuevo Producto
	function onCompleteNewItem(objResponse,suma){
		
		console.info("CALL onCompleteNewItem");
		var objRow 							= {};
		objRow.checked 						= false;						
		objRow.transactionMasterDetailID 	= 0;
		objRow.itemID						= objResponse[5];
		objRow.codigo						= objResponse[17];
		objRow.description					= objResponse[18].toLowerCase();
		objRow.um							= objResponse[23];
		objRow.umDescription				= objResponse[20];
		objRow.quantity 					= fnFormatNumber(1,2);
		objRow.bquantity 					= fnFormatNumber(objResponse[21],2);
		objRow.price 						= fnFormatNumber(objResponse[22],2);
		objRow.total 						= fnFormatNumber(objRow.quantity * objRow.price,2);						
		objRow.iva 							= 0;
		objRow.lote 						= "";
		objRow.vencimiento					= "";
		objRow.price2						= objResponse[25];
		objRow.price3						= objResponse[26];
		objRow.itemNameDescription			= objResponse[24];
		objRow.taxServices					= 0;
		
		//Berificar que el Item ya esta agregado 
		if(jLinq.from(objTableDetail.fnGetData()).where(function(obj){ return obj[2] == objRow.itemID;}).select().length > 0 ){
			var x_ 			= jLinq.from(objTableDetail.fnGetData()).where(function(obj){ return obj[2] == objRow.itemID;}).select();
			var newCantidad =  0;
			
			if (suma == true)
			newCantidad =  parseFloat(fnFormatNumber(x_[0][6],2)) + 1;
			else
			newCantidad =  parseFloat(fnFormatNumber(x_[0][6],2)) - 1;
		
		
			var objind_ 	= fnGetPosition(x_,objTableDetail.fnGetData());
			objTableDetail.fnUpdate( fnFormatNumber(newCantidad,2)  , objind_, 6 );
			
			
			
			if(varUseMobile != "1"){
				$("#body_tb_transaction_master_detail tr")[objind_].animate({ 
				backgroundColor : "#4eacc8" },100);
				
				$("#body_tb_transaction_master_detail tr")[objind_].animate({ 
				backgroundColor : "" },100);
			}
			
			
		}
		else
		{		
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
				objRow.umDescription,
				objRow.price2,
				objRow.price3,
				objRow.itemNameDescription /*itemDescriptionLog*/,
				objRow.taxServices
			]);
			
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
		
		
		try 
		{
			if( objCompanyParameter_Key_INVOICE_VALIDATE_BALANCE == "true"  )
			{
					fnShowNotification("Intete nuevamente..","error",timerNotification);			
					result = false;	
					fnWaitClose();
					return;
			}
			
		} 
		catch (error) 
		{
			fnShowNotification("Intete nuevamente..","error",timerNotification);			
			result = false;	
			fnWaitClose();
			return;
		}
		
		
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
		
		//Validar monto descuento en rango de 0 a 100
		let porcentajeDescuento = parseFloat($('#txtPorcentajeDescuento').val()) || 0;
		if (porcentajeDescuento < 0 || porcentajeDescuento > 100) {
			fnShowNotification("El porcentaje de descuento no es valido","error",timerNotification);
			result = false;
			fnWaitClose();
        }
		
		//Validar Estado de la factura
		if($("#txtStatusIDOld").val() ==  varStatusInvoiceAplicado){
			fnShowNotification("Crear una nueva factura, por que la actual esta aplicada, no puede ser modificada","error",timerNotification);
			result = false;
			fnWaitClose();
		}
		
		//Validar estado anulado
		if($("#txtStatusID").val() ==  varStatusInvoiceAnular){
			fnShowNotification("No puede pasar a estado anulado","error",timerNotification);
			result = false;
			fnWaitClose();
		}
		
		
		//Validar Detalle
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

		
		var listItemIDToValid 	= "-1";
		var listQntity 			= "-1";
		for(var i = 0; i < objTableDetail.fnGetData().length; i++){			
			var rowTable = objTableDetail.fnGetData()[i];
			var rowTableItemID 		 = rowTable[2];
			var rowTableItemQuantity = rowTable[6];
			var rowTableItemNumber = rowTable[3];
			var rowTableItemNombre = rowTable[4];
						
			listItemIDToValid = listItemIDToValid+ ","+rowTableItemID;
			listQntity = listQntity+ ","+rowTableItemQuantity;
		}
		
		
		
		//Si es de credito que la factura no supere la linea de credito		
		var objCustomerCreditLine		= [];
		var invoiceTypeCredit 			= false;
		var causalSelect 				= $("#txtCausalID").val();
		var customerCreditLineID 		= $("#txtCustomerCreditLineID").val();
		var causalCredit 				= objCompanyParameter_Key_INVOICE_BILLING_CREDIT.split(",");
		
		if(objCompanyParameter_Key_INVOICE_VALIDATE_BALANCE == "true")
		objCustomerCreditLine 			= jLinq.from(objListCustomerCreditLine).where(function(obj){ return obj.customerCreditLineID == customerCreditLineID; }).select();
		
		
		
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
		
		//Obtener Limite
		if(invoiceTypeCredit){
			
			<?php echo getBehavio($company->type,"app_invoice_billing","scriptValidateInCredit",""); ?>  
			
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
			
			
			//Validar Limite
			if(objCompanyParameter_Key_INVOICE_VALIDATE_BALANCE == "true")
			{
				if(objCurrencyCordoba.currencyID == objCustomerCreditLine[0].currencyID)
					balanceCredit =  fnFormatFloat(fnFormatNumber(objCustomerCreditLine[0].balance,"4"));
				else{
					balanceCredit = (
										fnFormatFloat(fnFormatNumber(objCustomerCreditLine[0].balance,"4")) * 
										fnFormatFloat(fnFormatNumber(objCustomerCreditLine[0].objExchangeRate,"4")) 
									);
				}
				
				if(balanceCredit < montoTotalInvoice && balanceCredit != 0 ){
					fnShowNotification("La factura no puede ser facturada al credito. Balance del cliente: " + balanceCredit,"error",timerNotification);
					result = false;
					fnWaitClose();
				}
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
									""+
									result.resultValidate[ie].Codigo + " " + 
									result.resultValidate[ie].Nombre + "  cantidad en bodega " + 
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
		
		return result;
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
				
					
					var objConcepto = e;
					objConcepto1 	= jLinq.from(objConcepto).where(function(obj){ return (obj.name == "IVA"); }).select();
					if( objConcepto1.length > 0 )
					{
						
						objTableDetail.fnUpdate( fnFormatNumber(objConcepto1[0].valueOut,2), objind_, 9 );			
					}
					objConcepto2 	= jLinq.from(objConcepto).where(function(obj){ return (obj.name == "TAX_SERVICES"); }).select();
					if( objConcepto2.length > 0 )
					{
						
						objTableDetail.fnUpdate( fnFormatNumber(objConcepto2[0].valueOut,2), objind_, 17 );			
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
		
		
		$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect, .skuStyleNormal').uniform();		
		if(varUseMobile == "1")
		{
			$("#tb_transaction_master_detail td").css("display","block");
		}
		
	}
	
	function fnSelectCellZone(cell) {		
		var catalogItemIDZone = $(cell).data("value");
		$(".custom-table-zalones").find("td").removeClass("selected");
		$(cell).addClass("selected");
		$("#txtZoneID").val( catalogItemIDZone );
		$("#txtZoneID").select2();
		$(".custom-table-mesas").find("td").addClass("hidden");
		$(".custom-table-mesas").find('td[data-parent="'+catalogItemIDZone+'"]').removeClass("hidden");
		var sidebar = $("#mySidebarZona");		
		sidebar.css("width", "0");	
		sidebar.addClass("hidden");
		$("#mySidebarMesa").css("width","100%");
		$("#mySidebarMesa").removeClass("hidden");
	}
	
	
	function fnSelectCellMesa(cell) {		
		$(".custom-table-mesas").find("td").removeClass("selected");
		$(cell).addClass("selected");
		$("#txtMesaID").val( $(cell).data("value") );
		$("#txtMesaID").select2();
		
	}

	function fnSelectCellMesaDoubleClick(cell,value) {		
		$(".custom-table-mesas").find("td").removeClass("selected");
		$(cell).addClass("selected");
		$("#txtMesaID").val( $(cell).data("value") );
		$("#txtMesaID").select2();
		if(value !== 0 && value !== 'undefined'){			
			$("#modalDialogMeaBussyV2").modal('show');
			$("#txtMesaOcupada").val(value);
			$(".modal-backdrop.fade.in").removeClass("modal-backdrop");
			
		}else{
            $("#mySidebarFactura").css("width","100%");
            $("#mySidebarMesa").css("width","0%");
			$("#mySidebarMesa").addClass("hidden");
        }
	}

    function fnSelectCellCategoryInventory(cell) {
        var inventoryCategoryID = $(cell).data("value");

        $(".custom-table-container-categorias").hide();
        $(".custom-table-container-inventory").show();
        /*$(".item-producto").find('[data-parent="'+inventoryCategoryID+'"]').removeClass("hidden");*/

    }

    function fnSelectCellInventoryBack() {
        $(".custom-table-container-categorias").show();
        $(".custom-table-container-inventory").hide();
    }

    function fnSelectCellInventory(cell) {
        $(".item-producto").removeClass('selected');
        $(cell).addClass("selected");
    }
	
	function fnSelectDoubleCellInventory(cell) {
		$(cell).addClass("selected");
		var codigoProducto = $(cell).data("codigo");
		
		if(codigoProducto == "0" )
		{
			$(".custom-table-container-categorias").removeClass("hidden");
			$(".custom-table-container-inventory").addClass("hidden");
			return;
		}
		
		
		//buscar el producto y agregar por codigo de barra
		obtenerDataDBProductoArray(
			"objListaProductosX001",
			"all",
			0,
			"all",
			{"codigoABuscar":""+codigoProducto+""},
			function(e){    
				
				
				//buscar el producto y agregar						
				var codigoABuscar 	= e.codigoABuscar.toUpperCase();
				e 					= e.all;
				var encontrado		= false;				
				for(var i = 0 ; i < e.length ; i++)
				{
					
					if(encontrado == true)
					{
						i--;
						break;
					}
					
					//buscar por codigo de sistema					
					var currencyTemp	= e[i].currencyID;
					var currencyID 		= $("#txtCurrencyID").val();
					
					var warehouseIDTemp		= e[i].warehouseID;
					var warehouseID			= $("#txtWarehouseID").val();
					
					if(  
						currencyID == currencyTemp && 
						fnDeleteCerosIzquierdos(codigoABuscar.replace("BITT","").replace("ITT","") ) == fnDeleteCerosIzquierdos(e[i].Codigo.replace("BITT","").replace("ITT","").toUpperCase())  && 
						warehouseID == warehouseIDTemp
					)
					{
						
						encontrado 		= true;
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
							if( 
								fnDeleteCerosIzquierdos(listCodigTmp[ii].toUpperCase()) == fnDeleteCerosIzquierdos(codigoABuscar) && 
								currencyID == currencyTemp  &&
								warehouseID == warehouseIDTemp
							)
							{
								
								encontrado 		= true;
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
					filterResultArray[20] 	= filterResult.Medida;
					filterResultArray[21] 	= filterResult.Cantidad;
					filterResultArray[22] 	= filterResult.Precio;
					filterResultArray[23] 	= filterResult.unitMeasureID;
					filterResultArray[24] 	= filterResult.Descripcion;
					filterResultArray[25] 	= filterResult.Precio2;
					filterResultArray[26] 	= filterResult.Precio3;
					
					//Agregar el Item a la Fila					
					onCompleteNewItem(filterResultArray,sumar); 
				}
				 
			}
		);	
	}
	
	

	function fnClearData(){
			console.info("fnClearData");
			objTableDetail.fnClearTable();
			$("#txtReceiptAmount").val("0");			
			$("#txtReceiptAmountDol").val("0.00");
			$("#txtReceiptAmountBank").val("0");
			$("#txtReceiptAmountPoint").val("0");
			$("#txtChangeAmount").val("0");
			$("#txtSubTotal").val("0");
			$("#txtDescuento").val("0");
			$("#txtPorcentajeDescuento").val("0");
			$("#txtIva").val("0");
			$("#txtServices").val("0");
			$("#txtTotal").val("0");
			$("#txtTotalAlternativo").text("0");

			
			$("#txtReceiptAmountTarjeta").val("0");
			$("#txtReceiptAmountTarjetaDol").val("0");
			$("#txtReceiptAmountBankDol").val("0");
	}


	function fnRecalculateDetail(clearRecibo,sourceEvent){
		
		var typePriceID 			= $("#txtTypePriceID").val();
		var cantidad 				= 0;
		var iva 					= 0;
		var taxServices				= 0;
		var precio					= 0;		
		var subtotal 				= 0;		
		var total 					= 0;
		var porcentajeDescuento 	= parseFloat($('#txtPorcentajeDescuento').val()) || 0;
		var cantidadGeneral 				= 0;
		var ivaGeneral 						= 0;
		var serviceGeneral					= 0;
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
			
			
			objTableDetail.fnUpdate(cantidadTemporal, i, 6 );
			objTableDetail.fnUpdate(priceTemporal , i, 7 );
			objTableDetail.fnUpdate(skuValueDescription , i, 13 );
		
			cantidad 	= parseFloat(NSSystemDetailInvoice[i][6]);
			precio 		= parseFloat(NSSystemDetailInvoice[i][7]);
			iva 		= parseFloat(NSSystemDetailInvoice[i][9]);
			taxServices	= parseFloat(NSSystemDetailInvoice[i][17]);
			
			
			
			subtotal    = precio * cantidad;
			iva 		= (precio * cantidad) * iva;
			taxServices = (precio * cantidad) * taxServices;
			total 		= iva + taxServices + subtotal;
			
			
			cantidadGeneral 	= cantidadGeneral + cantidad;
			precioGeneral 		= precioGeneral + precio;
			ivaGeneral 			= ivaGeneral + iva;	
			serviceGeneral		= serviceGeneral + taxServices;
			subtotalGeneral 	= subtotalGeneral + subtotal;
			totalGeneral 		= totalGeneral + total;
			
			
			objTableDetail.fnUpdate( fnFormatNumber(subtotal,2), i, 8 );
		}		
		
		let descuento = subtotalGeneral * (porcentajeDescuento / 100);
        totalGeneral = subtotalGeneral +  serviceGeneral + ivaGeneral - descuento;
		
		$("#txtSubTotal").val(fnFormatNumber(subtotalGeneral,2));
		$('#txtDescuento').val(fnFormatNumber(descuento, 2));
		$("#txtIva").val(fnFormatNumber(ivaGeneral,2));
		$("#txtServices").val(fnFormatNumber(serviceGeneral,2));
		$("#txtTotal").val(fnFormatNumber(totalGeneral,2));
		$("#txtTotalAlternativo").text(fnFormatNumber(totalGeneral,2));

		
			
		
		$("#txtReceiptAmount").val("0.00");
		$("#txtReceiptAmountDol").val("0.00");
		$("#txtChangeAmount").val("0.00");			
		$("#txtReceiptAmountBank").val("0");
		$("#txtReceiptAmountPoint").val("0");
		
		$("#txtReceiptAmountTarjeta").val("0");
		$("#txtReceiptAmountTarjetaDol").val("0");
		$("#txtReceiptAmountBankDol").val("0");
		
	}

	function fnFillListaProductos(data)
	{		
		console.info("fnFillListaProductos success data");
		var objListaProductos 				= data.objGridView;			
		
		
		removeDataDB("objListaProductosX001");		
		addDataDBArray("objListaProductosX001",objListaProductos);
		
	}
	
	
	function fnFillListaItemConcept(data)
	{		
		
		console.info("fnFillListaItemConcept success data");
		removeDataDB("objListaProductosConceptosX001");		
		addDataDBArray("objListaProductosConceptosX001",data.objGridView);
		
	
	}
	
	function fnFillListaCreditLine(data)
	{		
		console.info("fnFillListaCreditLine success data");
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
				$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_invoice_billing/save/edit");
				validateFormAndSubmit();
	}	
	
	function fnRenderLineaCredit(objListCustomerCreditLine,objCausalTypeCredit)
	{
		
		$("#divTipoFactura").removeClass("hidden");
		
		//Renderizar Line Credit
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
				if( ($(obj).attr("value") == causalIDCredit) && (objListCustomerCreditLine.length > 0 ))
					$("#txtCausalID option[value="+causalIDCredit+"]").removeAttr("disabled");
				else if( ($(obj).attr("value") == causalIDCredit) && (objListCustomerCreditLine.length == 0))
					$("#txtCausalID option[value="+causalIDCredit+"]").attr("disabled","true");
				else
					$("#txtCausalID option[value="+causalIDCredit+"]").removeAttr("disabled");
			}
		});
		
		$.each( $("#txtCausalID option"),function(index,obj){
			if(varTransactionCausalID == $(obj).attr("value")){
				$(obj).attr("selected");
				$("#txtCausalID").val(varTransactionCausalID);
			}
		});
		
		//Refresh Control
		if(varUseMobile != "1")
		{
			$("#txtCustomerCreditLineID").select2();
			$("#txtCausalID").select2();
		}
		
		refreschChecked();		
		fnRenderLineaCreditoDiv();		
		onCompletePantalla(); 		
		
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
	/*TIPO PRECIO 1 --> 154 --> PUBLICO*/
	function fnObtenerListadoProductos(){
		
		$.ajax({									
			cache       : false,
			dataType    : 'json',
			type        : 'GET',			
			url  		: "<?php echo base_url(); ?>/app_invoice_api/getViewApi/<?php echo $objComponentItem->componentID; ?>/onCompleteNewItem/SELECCIONAR_ITEM_BILLING_BACKGROUND/"+encodeURI('{"warehouseID"|"'+  $("#txtWarehouseID").val()   +'"{}"listPriceID"|"<?php echo $objListPrice->listPriceID; ?>"{}"typePriceID"|"'+154+'"}'),
			success		: fnFillListaProductos,
			error:function(xhr,data){	
				console.info("fnObtenerListadoProductos data error");		
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
				console.info("fnObtenerListadoItemConcept data error");	
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
				console.info("fnObtenerListadoCustomerCreditLine data error");	
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
							filterResult = e.producto1[0].Precio;
						}
						//precio 2 ---> 155 --> precio mayorista
						if(typePriceID == 155){
							filterResult = e.producto1[0].Precio;
						}
						//precio 3 ---> 156 --> precio credito
						if(typePriceID == 156){
							filterResult = e.producto1[0].Precio;
						}
						
						//Actualizar Precio
						objTableDetail.fnUpdate(fnFormatNumber( filterResult,2) , e.index, 7 );
						
					}
				}
			);
			
			
			
	
		}
	}
	
	
	function fnImprimir(){
		
		
		if(
			varParameterInvoiceBillingPrinterDirect == 'true' && 
			objParameterPrinterDirectAndPreview == 'false' && 
			varParameterTipoPrinterDownload == 'false' 
		)
		{
			
			var url=varParameterUrlServidorDeImpresion+varParameterInvoiceBillingPrinterDirectUrl;
			url = url+
			"/companyID/"+"<?php echo $objTransactionMaster->companyID; ?>" + 
			"/transactionID/"+"<?php echo $objTransactionMaster->transactionID; ?>"+
			"/transactionMasterID/"+"<?php echo $objTransactionMaster->transactionMasterID; ?>";
			fnWaitOpen();	
			$.ajax({									
				cache       : false,
				dataType    : 'json',
				type        : 'POST',
				data		: { "fromServer" : varParameterInvoiceBillingPrinterDataLocal },
				url  		: url,
				success		: function(){
					fnWaitClose();						
				},
				error:function(xhr,data){
					console.info("complete data error");									
					console.info(data);
					console.info(xhr);
					fnWaitClose();
					//fnShowNotification("Error 505","error");
				}
			});	
			return;
		}
		if ( varParameterTipoPrinterDownload == 'true' )
		{
			fnWaitOpen();
			window.open("<?php echo base_url(); ?>/"+varUrlPrinter+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>", '_blank');
			fnWaitClose();
			return;
		}
		if(objParameterPrinterDirectAndPreview == 'true')
		{
			$("#modalDialogPrinterV2").modal("show");
			return
		}
		
		
		
		$("#modalDialogPrinterV2").modal("show");
		
	}
	
	function fnImprimirBar(){
		
		var listRow = objTableDetail.fnGetData();							
		var length 	= listRow.length;
		
		var i 		= 0;
		var itemid 	= "-1";
		
		while (i < length ){
			if(listRow[i][0] == true){
				itemid = itemid + ","+listRow[i][2];				
			}
			i++;
		}
		
				
		if(varParameterInvoiceBillingPrinterDirect == 'true')
		{
			
			var url="<?php echo base_url(); ?>/"+varParameterInvoiceBillingPrinterDirectBarUrl;
			url = url+
			"/companyID/"+"<?php echo $objTransactionMaster->companyID; ?>" + 
			"/transactionID/"+"<?php echo $objTransactionMaster->transactionID; ?>"+
			"/transactionMasterID/"+"<?php echo $objTransactionMaster->transactionMasterID; ?>"+
			"/itemID/"+itemid;
			
			fnWaitOpen();	
			$.ajax({									
				cache       : false,
				dataType    : 'json',
				type        : 'GET',
				url  		: url,
				success		: function(){
					fnWaitClose();						
				},
				error:function(xhr,data){
					console.info("complete data error");									
					console.info(data);
					console.info(xhr);
					fnWaitClose();
				}
			});	
			return;
		}
		else{
			$("#modalDialogBarV2").modal("show");
			return
		}
	}
	
	function fnImprimirCocina(){
		
		var listRow 	= objTableDetail.fnGetData();							
		var length 		= listRow.length;
		var comentario 	= $("#txtNote").val();
		
		var i 		= 0;
		var itemid 	= "-1";
		
		while (i < length ){
			if(listRow[i][0] == true){
				itemid = itemid + ","+listRow[i][2];				
			}
			i++;
		}
		
				
		if(varParameterInvoiceBillingPrinterDirect == 'true'){
			
			var url="<?php echo base_url(); ?>/"+varParameterInvoiceBillingPrinterDirectCocinaUrl;
			url = url+
			"/companyID/"+"<?php echo $objTransactionMaster->companyID; ?>" + 
			"/transactionID/"+"<?php echo $objTransactionMaster->transactionID; ?>"+
			"/transactionMasterID/"+"<?php echo $objTransactionMaster->transactionMasterID; ?>"+
			"/itemID/"+itemid+"/transactionMasterComment/"+comentario;
			
			fnWaitOpen();	
			$.ajax({									
				cache       : false,
				dataType    : 'json',
				type        : 'GET',
				url  		: url,
				success		: function(){
					fnWaitClose();						
				},
				error:function(xhr,data){
					console.info("complete data error");									
					console.info(data);
					console.info(xhr);
					fnWaitClose();
					//fnShowNotification("Error 505","error");
				}
			});	
			return;
		}
		else{
			$("#modalDialogCocinaV2").modal("show");
			return
		}
	}
	function fnCustomerNewCompleted(){
		console.info("cliente completado");
	}
	
	function fnGetCustomerClient(entityIDv){
		$.ajax({									
			cache       : false,
			dataType    : 'json',
			type        : 'POST',
			url  		: "<?php echo base_url(); ?>/app_invoice_api/getLineByCustomer",
			data 		: {entityID : entityIDv  },
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
				
				
				var data		 = {};	
				var length2		 = objTableDetail.fnGetData().length;
				var data2		 = objTableDetail.fnGetData();
				
				var length		 = e.length;
				var data		 = e;
				var index		 = 0;
				
				for(var i = 0 ; i < length; i++ )
				{		
					var objDatItem 			= data[i];
					var currencyTemp		= objDatItem.currencyID;
					var currencyID 			= $("#txtCurrencyID").val();
					
					var warehouseIDTemp		= objDatItem.warehouseID;
					var warehouseID			= $("#txtWarehouseID").val();
					
					var existe 				= jLinq.from(data2).where(function(obj){   return obj[2] == objDatItem.itemID; }).select().length;			
					if(existe == 0 && (currencyID == currencyTemp) &&  (warehouseIDTemp == warehouseID)  ) 
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
				dataResponse[5] = data.itemID; //itemID
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
				dataResponse[17] = data.Codigo;//Codigo
				dataResponse[18] = data.Nombre;//Nombre
				dataResponse[19] = data[0];
				dataResponse[20] = data.Medida;//Unidad de medida
				dataResponse[21] = data.Cantidad;//Cantidad
				dataResponse[22] = data.Precio;//Precio
				dataResponse[23] = data.unitMeasureID;//6:Barra 
				dataResponse[24] = data.Descripcion;//7:Descripcion
				dataResponse[25] = data.Precio2;
				dataResponse[26] = data.Precio3;		
				onCompleteNewItem(dataResponse,true);
			}
		);
	}
	
	
	
	
	function fnCreateTableSearchProductos(codigoBuscar)
	{		
		var autoClose 			= '<?php echo getBehavio($company->type,"app_invoice_billing","autoCloseSelectItem","false"); ?>';			
		var url_request 		= 
				"<?php echo base_url(); ?>/core_view/showviewbynamepaginate"+
				"/<?php echo $objComponentItem->componentID; ?>"+
				"/onCompleteNewItemPopPub/SELECCIONAR_ITEM_BILLING_POPUP_INVOICE/"+autoClose+"/"+
				encodeURI(
					'{'+
					'\"warehouseID\"|\"'+$("#txtWarehouseID").val()+'\"'+
					',\"listPriceID\"|\"<?php echo $objListPrice->listPriceID; ?>\"'+						
					',\"typePriceID\"|\"'+$("#txtTypePriceID").val()+'\"'+
					',\"currencyID\"|\"'+$("#txtCurrencyID").val()+'\"'+
					'}' 
				) + 
				"/false/not_redirect_when_empty/1/1/"+varParameterCantidadItemPoup+"/"+codigoBuscar+"/";  
				
		 // Verificar si la ventana ya está abierta
		if (objWindowSearchProduct && !objWindowSearchProduct.closed) 
		{
			objWindowSearchProduct.focus();
		} 
		else 
		{
			objWindowSearchProduct 							= window.open(url_request,"MsgWindow","width=900,height=450");
			objWindowSearchProduct.onCompleteNewItemPopPub 	= onCompleteNewItemPopPub; 
		}
		
	}
	
	
	
	function openDataBaseAndCreate(bInicializar ,obtenerRegistroDelServer) {
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
			objectStoreX001.createIndex("Barra", "Barra", { unique: false });
			objectStoreX001.createIndex("Cantidad", "Cantidad", { unique: false });
			objectStoreX001.createIndex("Codigo", "Codigo", { unique: false });
			objectStoreX001.createIndex("Descripcion", "Descripcion", { unique: false });
			objectStoreX001.createIndex("Medida", "Medida", { unique: false });
			objectStoreX001.createIndex("Precio", "Precio", { unique: false });
			objectStoreX001.createIndex("cost", "cost", { unique: false });
			objectStoreX001.createIndex("currencyID", "currencyID", { unique: false });						
			objectStoreX001.createIndex("warehouseID", "warehouseID", { unique: false });
			objectStoreX001.createIndex("isInvoice", "isInvoice", { unique: false });
			objectStoreX001.createIndex("isInvoiceQuantityZero", "isInvoiceQuantityZero", { unique: false });
			objectStoreX001.createIndex("itemID", "itemID", { unique: false });
			objectStoreX001.createIndex("iwCost", "iwCost", { unique: false });
			objectStoreX001.createIndex("iwQuantityMax", "iwQuantityMax", { unique: false });
			objectStoreX001.createIndex("iwQuantityMin", "iwQuantityMin", { unique: false });			
			objectStoreX001.createIndex("quantity", "quantity", { unique: false });
			objectStoreX001.createIndex("quantityMax", "quantityMax", { unique: false });
			objectStoreX001.createIndex("quantityMin", "quantityMin", { unique: false });
			objectStoreX001.createIndex("unitMeasureID", "unitMeasureID", { unique: false });
			objectStoreX001.createIndex("Precio2", "Precio2", { unique: false });
			objectStoreX001.createIndex("Precio3", "Precio3", { unique: false });
			
			
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
			
				var valuex=0;
				try
				{
					valuex = e.producto1[0].itemID;
				}
				catch(z)
				{
					valuex = 0;
				}
				e.itemID = valuex;
				e.callback(e);
				
				  
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
	
	
	
	
	function onCompleteNewItemPopPub (ee,uu,zz)
	{
		var data		 = {};					
		var dataResponse = [];
		data			 = ee;
		
		dataResponse[0] =  data[0][0];
		dataResponse[1] =  data[0][0];
		dataResponse[2] =  data[0][0];
		dataResponse[3] =  data[0][0];
		dataResponse[4] =  data[0][0];
		dataResponse[5] =  data[0][0];//itemID
		dataResponse[6] =  data[0][0];
		dataResponse[7] =  data[0][0];
		dataResponse[8] =  data[0][0];
		dataResponse[9] =  data[0][0];
		dataResponse[10] = data[0][0];
		dataResponse[11] = data[0][0];
		dataResponse[12] = data[0][0];
		dataResponse[13] = data[0][0];
		dataResponse[14] = data[0][0];
		dataResponse[15] = data[0][0];
		dataResponse[16] = data[0][0];
		dataResponse[17] = data[0][4];//Codigo
		dataResponse[18] = data[0][5];//Nombre
		dataResponse[19] = data[0][0];
		dataResponse[20] = data[0][7];//Unidad de medida
		dataResponse[21] = data[0][8];//Cantidad
		dataResponse[22] = data[0][9];//Precio
		dataResponse[23] = data[0][1];//UnitMeasuereID
		
			
		dataResponse[24] = data[0][10];//Description
		dataResponse[25] = data[0][2];//Precio2
		dataResponse[26] = data[0][3];//Precio3
		
		onCompleteNewItem(dataResponse,true);
	}	
	
	
	
	
	openDataBaseAndCreate(true,false);
	
	function fnReady()
	{
		$(document).ready(function(){

			$('#txtClaveOpenCash').css({
				'webkitTextSecurity': 'disc', 		// Para WebKit browsers
				'textSecurity'		: 'disc'        // Para otros browsers que lo soporten
			});
			
			
            $grid = $('.custom-table-container-inventory .row').isotope({
                itemSelector: '.item-producto',
                layoutMode: 'fitRows',
                filter: '.item-producto-back'
            });

			if(varParameterRestaurante == "true")
			{
				$("#mySidebarFactura").css("width","100%");
			}
			
            $(".custom-table-container-inventory").hide();
            var zonaDefault = $("#txtZoneID").val();
            $(".custom-table-mesas").find("td").addClass("hidden");
            $(".custom-table-mesas").find('td[data-parent="'+zonaDefault+'"]').removeClass("hidden");

			$('#txtDate').datepicker({format:"yyyy-mm-dd"});						 
			$("#txtDate").datepicker("update");
			 
			$('#txtDateFirst').datepicker({format:"yyyy-mm-dd"});						 
			$("#txtDateFirst").datepicker("update");
			
			$('#txtNextVisit').datepicker({format:"yyyy-mm-dd"});
			heigthTop							= 300;
			
			
			
			//Incializar Focos
			if(varParameterScanerProducto != "false" && varUseMobile == "0" ){
				document.getElementById("txtScanerCodigo").focus();	
			}
			
			
			if(varUseMobile == "0")
			{
				// Añade una nueva entrada en el historial para evitar que el usuario regrese
				history.pushState(null, null, window.location.href);


				// Captura el evento popstate que ocurre cuando el usuario intenta volver
				window.onpopstate = function(event) {
					// Redirige a la misma página o realiza otra acción
					history.go(1);
				};


				//window.addEventListener('beforeunload', function (e) {
				//	// Evita que la página se cierre
				//	e.preventDefault();  // Necesario para algunos navegadores
				//
				//	// Establece el mensaje de advertencia
				//	e.returnValue = '';  // El mensaje personalizado no es compatible en la mayoría de los navegadores
				//});

			}
			
			

			if(varParameterScrollDelModalDeSeleccionProducto == "true"){
				$("#modal_body_popup_productos").css("overflow","auto");
				$("#modal_body_popup_productos").css("height",varParameterAlturaDelModalDeSeleccionProducto);
			}
			
			if(<?php echo $objParameterInvoiceButtomPrinterFidLocalPaymentAndAmortization; ?> == true){	
				$("#btnAceptarDialogPrinterV2AceptarTabla").removeClass("hidden");
			}
			if(objParameterPrinterDirectAndPreview == 'true' ){	
				$("#btnAceptarDialogPrinterV2AceptarDirect").removeClass("hidden");
			}
						
			
			if ( varParameterRegresarAListaDespuesDeGuardar == "true" )
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
							window.location	= "<?php echo base_url(); ?>/app_invoice_billing/add/codigoMesero/<?php echo $codigoMesero; ?>";
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
				"aaData"		: tmpData,
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
								//,
								//"fnCreatedCell": varUseMobile == "0" ? function(){  } :  function (td, cellData, rowData, row, col) 
								//{
								//	  $(td).css("display","block");
								//}
							},
							{
								"aTargets"		: [ 1 ],//transactionMasterDetailID
								"bVisible"  	: true,
								"sClass" 		: "hidden",
								"bSearchable"	: false,
								"mRender"		: function ( data, type, full ) {
									return '<input type="hidden" value="'+data+'" name="txtTransactionMasterDetailID[]" />';
								}
								//,
								//"fnCreatedCell": varUseMobile == "0" ? function(){  } :  function (td, cellData, rowData, row, col) 
								//{
								//	  $(td).css("display","block");
								//}
							},
							{
								"aTargets"		: [ 2 ],//itemID
								"bVisible"		: true,
								"sClass" 		: "hidden",
								"bSearchable"	: false,
								"mRender"		: function ( data, type, full ) {
									return '<input type="hidden" value="'+data+'" name="txtItemID[]" />';
								}
								//,
								//"fnCreatedCell": varUseMobile == "0" ? function(){  } :  function (td, cellData, rowData, row, col) 
								//{
								//	  $(td).css("display","block");
								//}
							},
							{
								"aTargets"		: [ 3 ],//itemNumber
								"sWidth"		: "250px",
								"mRender"		: function ( data, type, full ) {
									return '<input type="text" class="col-lg-12" style="text-align:left" value="'+data+'" readonly="true" />';
								}
								//,
								//"fnCreatedCell": varUseMobile == "0" ? function(){  } :  function (td, cellData, rowData, row, col) 
								//{
								//	  $(td).css("display","block");
								//}
							},
							{
								"aTargets"		: [ 4 ],//descripcion
								"sWidth"		: "250px",
								"mRender"		: function ( data, type, full ) {
									
									
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
									
									var  strFiledSelecte 	= "";
									var  strFiled			= '<input type="text" name="txtTransactionDetailName[]" id="txtTransactionDetailName'+full[2]+'"   class="col-lg-12 '+classHiddenTex+' " style="text-align:left" value="'+full[4]+'" '+PriceStatus+' />';									
									var strFiledSelecte 	= "<select name='txtItemSelected' class='<?php echo ($useMobile == "1" ? "" : "select2"); ?> txtItemSelected "+classHiddenSelect+"  ' >";
									strFiledSelecte			= strFiledSelecte+"<option value='"+full[2]+"' selected data-itemid='"+full[2]+"' data-codigo='"+full[3]+"' data-name='"+full[4].replace("'","").replace("'","") +"' data-unidadmedida='"+full[5]+"' data-cantidad='"+full[6]+"' data-precio='"+full[7]+"' data-barra='"+full[3]+"'  data-description='"+full[4].replace("'","").replace("'","") + "'    >"+ full[4].replace("'","").replace("'","")  +"</option>";
									
									strFiledSelecte		= strFiledSelecte+"</select>";								
									strFiledSelecte 	=  strFiled + strFiledSelecte ;								
									return strFiledSelecte;
							
									
									
								}
							
							},
							{
								"aTargets"		: [ 5 ],//Sku
								"sWidth"		: "250px",
								"mRender"		: function ( data, type, full ) {
												
												var sel 					= '';
												var espacio					=  "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";								
												sel 						= '<select name="txtSku[]" id="txtSku'+full[2]+'" class="txtSku col-lg-12 skuStyleNormal" >';	
												
												if(varUseMobile == "1")
													espacio = "";		
												
												sel = sel + '<option value="'+full[5]+'" data-skuv="1" data-skupriceunitary="'+full[7]+'" selected style="font-size:200%" data-description="'+full[13]+'" >'+full[13]+espacio+'</option>';
												sel = sel + '</select>';
												return sel;
									
								}
								//,
								//"fnCreatedCell": varUseMobile == "0" ? function(){  } :  function (td, cellData, rowData, row, col) 
								//{
								//	  $(td).css("display","block");
								//}
							},
							{
								"aTargets"		: [ 6 ],//Cantidad
								"sWidth"		: "250px",
								"mRender"		: function ( data, type, full ) {
									var str = '<input type="text" class="col-lg-12 txtQuantity txt-numeric" id="txtQuantityRow'+full[2]+'"  value="'+data+'" name="txtQuantity[]" style="text-align:right" autocomplete="off" />';
									
									if (varUseMobile == "1")
									str = str + " <span class='badge badge-inverse' >Cantidad</span>";
								
									return str;
								}
								//,
								//"fnCreatedCell": varUseMobile == "0" ? function(){  } :  function (td, cellData, rowData, row, col) 
								//{
								//	  $(td).css("display","block");
								//}
							},
							{
								"aTargets"		: [ 7 ],//Precio
								"sWidth"		: "250px",
								"mRender"		: function ( data, type, full ) {									
									var str =  '<input type="text" class="col-lg-12 txtPrice txt-numeric "  id="txtPriceRow'+full[2]+'"   '+PriceStatus+'  value="'+ data +'" name="txtPrice[]" style="text-align:right" autocomplete="off"  />';
									
									if (varUseMobile == "1")
									str = str + " <span class='badge badge-inverse' >Precio</span>";
								
									return str;
								}
								
							},
							{
								"aTargets"		: [ 8 ],//Total
								"sWidth"		: "250px",
								"mRender"		: function ( data, type, full ) {
									var str = '<input type="text" class="col-lg-12 txtSubTotal" readonly value="'+data+'" name="txtSubTotal[]" style="text-align:right" autocomplete="off" />';
									
									if (varUseMobile == "1")
									str = str + " <span class='badge badge-inverse' >Total</span>";
								
									return str;
								}
								//,
								//"fnCreatedCell": varUseMobile == "0" ? function(){  } :  function (td, cellData, rowData, row, col) 
								//{
								//	  $(td).css("display","block");
								//}
							},
							{
								"aTargets"		: [ 9 ],//Iva
								"bVisible"		: true,
								"sClass"		: "hidden",
								"bSearchable"	: false,
								"mRender"		: function ( data, type, full ) {
									return '<input type="text" class="col-lg-12 txtIva" value="'+data+'" name="txtIva[]" style="text-align:right" />';
								}
								//,
								//"fnCreatedCell": varUseMobile == "0" ? function(){  } :  function (td, cellData, rowData, row, col) 
								//{
								//	  $(td).css("display","block");
								//}
							},
							{
								"aTargets"		: [ 10 ],//skuQuantityBySku
								"bVisible"		: true,
								"sClass"		: "hidden",
								"bSearchable"	: false,
								"mRender"		: function ( data, type, full ) {
									return '<input type="text" class="col-lg-12 skuQuantityBySku" value="'+data+'" name="skuQuantityBySku[]" style="text-align:right" />';
								}
								//,
								//"fnCreatedCell": varUseMobile == "0" ? function(){  } :  function (td, cellData, rowData, row, col) 
								//{
								//	  $(td).css("display","block");
								//}
							},
							{
								"aTargets"		: [ 11 ],//unitaryPriceInvidual
								"bVisible"		: true,
								"sClass"		: "hidden",
								"bSearchable"	: false,
								"mRender"		: function ( data, type, full ) {
									return '<input type="text" class="col-lg-12 unitaryPriceInvidual" value="'+data+'" name="unitaryPriceInvidual[]" style="text-align:right" />';
								}
								//,
								//"fnCreatedCell": varUseMobile == "0" ? function(){  } :  function (td, cellData, rowData, row, col) 
								//{
								//	  $(td).css("display","block");
								//}
							},
							{
								"aTargets"		: [ 12 ],//PlusDimus	
								"sWidth"		: "250px",							
								"mRender"		: function ( data, type, full ) 
								{	
																		
									var objProductoPrecio1 = full[7];
									var objProductoPrecio2 = full[14];
									var objProductoPrecio3 = full[15];
									
									objProductoPrecio1 = fnFormatFloat(objProductoPrecio1);
									objProductoPrecio2 = fnFormatFloat(objProductoPrecio2);
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
											str = 	str+'<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="icon16 i-bookmark"></i><span class="caret"></span></button>';
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
									
									return str ;

									
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
							},
							{
								"aTargets"		: [ 14 ],//Precio2
								"bVisible"		: true,
								"sClass"		: "hidden",
								"bSearchable"	: false,
								"mRender"		: function ( data, type, full ) {
									return '<input type="hidden" value="'+data+'" name="txtItemPrecio2[]" />';
								}
								//,
								//"fnCreatedCell": varUseMobile == "0" ? function(){  } :  function (td, cellData, rowData, row, col) 
								//{
								//	  $(td).css("display","block");
								//}
							},
							{
								"aTargets"		: [ 15 ],//Precio3
								"bVisible"		: true,
								"sClass"		: "hidden",
								"bSearchable"	: false,
								"mRender"		: function ( data, type, full ) {
									return '<input type="hidden" value="'+data+'" name="txtItemPrecio3[]" />';
								}
								//,
								//"fnCreatedCell": varUseMobile == "0" ? function(){  } :  function (td, cellData, rowData, row, col) 
								//{
								//	  $(td).css("display","block");
								//}
							},
							{
								"aTargets"		: [ 16 ],//itemNameDescription
								"bVisible"		: true,
								"sClass"		: "hidden",
								"bSearchable"	: false,
								"mRender"		: function ( data, type, full ) {
									return '<input type="hidden" value="'+data+'" name="txtTransactionDetailNameDescription[]" />';
								}								
							},
							{
								"aTargets"		: [ 17 ],//TAX_SERVICES
								"bVisible"		: true,
								"sClass"		: "hidden",
								"bSearchable"	: false,
								"mRender"		: function ( data, type, full ) {
									return '<input type="text" class="col-lg-12 txtTaxServices" value="'+data+'" name="txtTaxServices[]" style="text-align:right" />';
								}
							}
				]						
			});	
		 

			refreschChecked();
			$("#txtDescuento").val("<?php echo number_format($objTransactionMaster->discount,2); ?>");
			$("#txtPorcentajeDescuento").val("<?php echo number_format($objTransactionMaster->tax4,2); ?>");
			fnRecalculateDetail(false,"");	
			
			$("#txtReceiptAmount").val("<?php echo number_format($objTransactionMasterInfo->receiptAmount,2); ?>");
			$("#txtReceiptAmountDol").val("<?php echo number_format($objTransactionMasterInfo->receiptAmountDol,2); ?>");
			$("#txtReceiptAmountBank").val("<?php echo number_format($objTransactionMasterInfo->receiptAmountBank,2); ?>");
			$("#txtReceiptAmountPoint").val("<?php echo number_format($objTransactionMasterInfo->receiptAmountPoint,2); ?>");
			$("#txtReceiptAmountTarjeta").val("<?php echo number_format($objTransactionMasterInfo->receiptAmountCard,2); ?>");
			$("#txtReceiptAmountTarjetaDol").val("<?php echo number_format($objTransactionMasterInfo->receiptAmountCardDol,2); ?>");
			$("#txtReceiptAmountBankDol").val("<?php echo number_format($objTransactionMasterInfo->receiptAmountBankDol,2); ?>");			
			$("#txtChangeAmount").val("<?php echo number_format($objTransactionMasterInfo->changeAmount,2); ?>");
			
			//Renderizar combobox de las lineas de credito			
			fnRenderLineaCredit(objListCustomerCreditLine,objCausalTypeCredit);	
			objRenderInit = false;
			
		});
	}
	
		
</script>
