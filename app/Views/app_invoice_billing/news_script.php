<!-- ./ page heading -->
<script>					
    var heigthTop							= 0;
	var objTableDetail 						= {};
    var tmpData 				            = [];
    var tmpInfoClient			            = 0;
	var scrollPosition						= 0;
    var warehouseID 						= $("#txtWarehouseID").val();
	let transactionID						= <?= $transactionID?>;
	var isAdmin								= '<?php echo $isAdmin; ?>';
	var esMesero							= '<?php echo $esMesero; ?>';
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
	var varParameterCustomPopupFacturacion	= '<?php echo $objParameterCustomPopupFacturacion; ?>';	
	var varParameterScanerProducto			= '<?php echo $objParameterScanerProducto; ?>';
    var varPermitirFacturarProductosEnZero	= '<?php echo $objParameterInvoiceBillingQuantityZero; ?>';
    var varParameterCantidadItemPoup		= '<?php echo $objParameterCantidadItemPoup; ?>';
	var varParameterRestaurante				= '<?php echo $objParameterRestaurant; ?>';
	var varTransactionMasterIDToPrinter  	= '<?php echo $transactionMasterIDToPrinter; ?>';

	var varAutoAPlicar						= '<?php echo $objParameterInvoiceAutoApply; ?>';
	var varParameterHidenFiledItemNumber	= <?php echo $objParameterHidenFiledItemNumber; ?>;  
	var objParameterPantallaParaFacturar 	= '<?php echo $objParameterPantallaParaFacturar; ?>';	
	var varParameterAmortizationDuranteFactura		= <?php echo $objParameterAmortizationDuranteFactura; ?>;  
	var varParameterImprimirPorCadaFactura			= '<?php echo $objParameterImprimirPorCadaFactura; ?>';
	var varParameterRegresarAListaDespuesDeGuardar	= '<?php echo $objParameterRegresarAListaDespuesDeGuardar; ?>';
	var varParameterAlturaDelModalDeSeleccionProducto	= '<?php echo $objParameterAlturaDelModalDeSeleccionProducto; ?>';
	var varParameterScrollDelModalDeSeleccionProducto	= '<?php echo $objParameterScrollDelModalDeSeleccionProducto; ?>';
	var varParameterINVOICE_BILLING_SELECTITEM			= '<?php echo $objParameterINVOICE_BILLING_SELECTITEM; ?>';
    var varUrlPrinter									= '<?php echo $urlPrinterDocument; ?>';

    var varParameterInvoiceBillingPrinterDirectBarUrl		= '<?php echo $objParameterINVOICE_BILLING_PRINTER_DIRECT_URL_BAR; ?>';
    var varTransactionCausalID								= 0;
    var varParameterMostrarImagenEnSeleccion 				= '<?php echo $objParameterMostrarImagenEnSeleccion; ?>';
	var varPermisos											= JSON.parse('<?php echo json_encode($objListaPermisos); ?>');
	var varParameterInvoiceBillingPrinterDataLocal			= '<?php echo $dataPrinterLocal; ?>';
	var varParameterUrlServidorDeImpresion 					= '<?php echo $objParameterUrlServidorDeImpresion; ?>';
	var varParameterINVOICE_BILLING_VALIDATE_EXONERATION 	= '<?php echo $objParameterINVOICE_BILLING_VALIDATE_EXONERATION; ?>';
	var objParameterINVOICE_SHOW_FIELD_PESO					= '<?php echo $objParameterINVOICE_SHOW_FIELD_PESO; ?>';

	
	var varPermisosEsPermitidoModificarPrecio 			= jLinq.from(varPermisos).where(function(obj){ return obj.display == "ES_PERMITIDO_MODIFICAR_PRECIO_EN_FACTURACION"}).select().length > 0 ? true:	false;	
	var varPermisosEsPermitidoModificarNombre 			= jLinq.from(varPermisos).where(function(obj){ return obj.display == "ES_PERMITIDO_MODIFICAR_NOMBRE_EN_FACTURACION"}).select().length > 0 ? true:	false;
	var varPermisosEsPermitidoSeleccionarPrecioPublico 	= jLinq.from(varPermisos).where(function(obj){ return obj.display == "ES_PERMITIDO_SELECCIONAR_PRECIO_PUBLICO"}).select().length > 0 ? true:	false;
	var varPermisosEsPermitidoSeleccionarPrecioPormayor	= jLinq.from(varPermisos).where(function(obj){ return obj.display == "ES_PERMITIDO_SELECCIONAR_PRECIO_PORMAYOR"}).select().length > 0 ? true:	false;
	var varPermisosEsPermitidoSeleccionarPrecioCredito 	= jLinq.from(varPermisos).where(function(obj){ return obj.display == "ES_PERMITIDO_SELECCIONAR_PRECIO_CREDITO"}).select().length > 0 ? true:	false;
	var varPermisosNoPermitirEliminarProductosFactura 	= jLinq.from(varPermisos).where(function(obj){ return obj.display == "NO_PERMITIR_ELIMINAR_PRODUCTOS_DE_FACTURA"}).select().length > 0 ? true:	false;

	var PriceStatus = varPermisosEsPermitidoModificarPrecio == true ? "":"readonly";
	var NameStatus  = varPermisosEsPermitidoModificarNombre == true ? "":"readonly";
	var varParameterInvoiceBillingPrinterDirect				= '<?php echo $objParameterInvoiceBillingPrinterDirect; ?>';	
	var varParameterInvoiceBillingPrinterDirectUrl			= '<?php echo $objParameterInvoiceBillingPrinterDirectUrl; ?>';	
	
	<?php echo $objListParameterJavaScript; ?>
	
	let objListCustomerCreditLine 	= JSON.parse('<?php echo json_encode($objListCustomerCreditLine); ?>');
	let objCausalTypeCredit 		= JSON.parse('<?php echo json_encode($objCausalTypeCredit); ?>');
	let objCurrencyCordoba 			= JSON.parse('<?php echo json_encode($objCurrencyCordoba); ?>');
	let varCustomerCrediLineID		= 0;
    let objRenderInit				= true;
	let loadEdicion 				= false;
	let transactionMasterID			= <?= $transactionMasterID ?>;
	let codigoMesero				= '<?= $codigoMesero ?>';
    let $grid;
    let selectedFilaInfoProducto;
    let selectedDataInfoProducto;
	let objComponentBilling = [];

    //Variables de edicion
    let varParameterTipoPrinterDownload;
    let objParameterPrinterDirectAndPreview;
    let objParameterINVOICE_BILLING_SHOW_COMMAND_BAR;
    let varParameterShowComandoDeCocina;
    let varUrlPrinterOpcion2;
    let varUrlPrinterCocina;
    let varUrlPrinterBar;
    let objParameterINVOICE_OPEN_CASH_WHEN_PRINTER_INVOICE = '<?= $objParameterINVOICE_OPEN_CASH_WHEN_PRINTER_INVOICE ?>';
    let objParameterINVOICE_OPEN_CASH_PASSWORD;
    let varDetail;
    let varDetailReferences;
    let varDetailWarehouse;
    let varDetailConcept;
    let varParameterInvoiceBillingPrinterDirectCocinaUrl;
    let objTransactionMasterItemPrice;
    let objTransactionMasterItemConcepto;
    let objTransactionMasterItemSku;
    let objTransactionMasterItem;
    

    let columnasTableDetail = {
        precio1 	: 22,
        precio2 	: 14,
        precio3 	: 15,
		descripcion : 4
    };

	const Toast = Swal.mixin({
		toast				: true,
		position			: "bottom-end",
		showConfirmButton	: false,
		timer				: 15000,
		customClass			: {
			title			: 'white swal2-title-custom',
		},
		background 			: 'red',
		timerProgressBar	: true,
		didOpen: (toast) => {
			toast.onmouseenter = Swal.stopTimer;
			toast.onmouseleave = Swal.resumeTimer;
		}
	});

	const ToastSuccess = Swal.mixin({
		toast				: true,
		position			: "bottom-end",
		showConfirmButton	: false,
		timer				: 15000,
		customClass			: {
			title			: 'white swal2-title-custom',
		},
		background 			: 'green',
		timerProgressBar	: true,
		didOpen: (toast) => {
			toast.onmouseenter = Swal.stopTimer;
			toast.onmouseleave = Swal.resumeTimer;
		}
	});

	$(document).on("click",".btnPlus",function(){		
		var quantity = $(this).parent().parent().parent().find(".txtQuantity").val();
		quantity 	 = fnFormatFloat(quantity);
		quantity	 = quantity + 1;
		$(this).parent().parent().parent().find(".txtQuantity").val(quantity);
		fnRecalculateDetail(true,"");				
	});

    $('#txtCheckApplyExoneracion').parent().parent().on('change', function() {
        let exoneracion = $('#txtCheckApplyExoneracion').parent().hasClass("switch-on");
        if(exoneracion)
            $("#txtCheckApplyExoneracionValue").val(1);
        else
            $("#txtCheckApplyExoneracionValue").val(0);

        var listRow = objTableDetail.fnGetData();
        var length 	= listRow.length;

        var i 		= 0;
        while (i < length )
        {
            fnGetConcept(listRow[i][2],"IVA");
            i++;
        }

    });

    $('#txtCheckReportSinRiesgo').parent().parent().on('change', function() {
        let state = $('#txtCheckReportSinRiesgo').parent().hasClass("switch-on");
		if(state)
			$("#txtCheckReportSinRiesgoValue").val(1);
		else
			$("#txtCheckReportSinRiesgoValue").val(0);
    });

	$('#txtCheckDeEfectivo').parent().parent().on('change', function() {
        let state = $('#txtCheckDeEfectivo').parent().hasClass("switch-on");
		if(state)
			$("#txtCheckDeEfectivoValue").val(1);
		else
			$("#txtCheckDeEfectivoValue").val(0);
    });
	
	$('#txtLayFirstLineProtocolo').on('change', function() 
	{
		
		var urlExoneration="<?php echo base_url(); ?>/app_invoice_api/getNumberExoneration/value/"+$("#txtLayFirstLineProtocolo").val();
		if(varParameterINVOICE_BILLING_VALIDATE_EXONERATION == "true")
		{
			mostarModalPersonalizado('Cargando recursos de linea de protocolo...');
			$.ajax({									
				cache       : false,
				dataType    : 'json',
				type        : 'GET',
				url  		: urlExoneration,
				success		: function(e,o){
					
					//La exoneracion ya existe no exonerar
					var timerNotification 	= 15000;
					if(e.objTransactionMaster.length > 0 )
					{
						fnSetCheckBoxValue($('#txtCheckApplyExoneracion'),false);
						$("#txtCheckApplyExoneracionValue").val(0);
						fnShowNotification("El numero de exoneracion ya existe!!","error",timerNotification);
					}
					//La exoneracoin no existe si se puede exonerar
					else
					{
                        fnSetCheckBoxValue($('#txtCheckApplyExoneracion'),true);
						$("#txtCheckApplyExoneracionValue").val(1);
						fnShowNotification("Exoneracion aplicada!!","success",timerNotification);
					}
					
					//Aplicar exoneracion
					var listRow = objTableDetail.fnGetData();							
					var length 	= listRow.length;		
					var i 		= 0;
					while (i < length )
					{	
						fnGetConcept(listRow[i][2],"IVA");			
						i++;
					}								
				},
				complete:function(){
					cerrarModal('ModalCargandoDatos');
				}
			});	
		}
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
	
	$("#btnCancelarIrMesa").click(function(){		
		cerrarModal("ModalIrMesaDocumentDialogCustom");
	});	
	
	$("#btnAceptarMesaBussyV2").click(function(){
		mostarModalPersonalizado('Cargando...');
		var value 				= $("#txtMesaOcupada").val();
		window.location.href 	= "<?=base_url()."/"."app_invoice_billing/edit/companyID/".$companyID."/transactionID/".$transactionID."/transactionMasterID/"?>"+value+"<?="/codigoMesero/".$codigoMesero ?>";			
		cerrarModal("ModalIrMesaDocumentDialogCustom");
	});	
	
	$("#btnAceptarDialogBarV2").click(function(){
	});	
	
	$("#btnAceptarDialogCocinaV2").click(function(){
	});	
	
	$(document).on("click","#btnAbrirCaja",function(){
		$("#txtClaveOpenCash").val("");
		mostrarModal("ModalCodigoCaja");
	});
	
	$('#btnCancelarCashOpen').click(function () {
		cerrarModal('ModalCodigoCaja');
	});

	$("#btnAceptarClaveOpenCash").click(function()
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
			cerrarModal("ModalCodigoCaja");
		}
	});	
	
	$('#btnNew').click(function(){
		mostarModalPersonalizado("Cargando datos de nueva factura, por favor espere...")
		fnClearForm();
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
	$(document).on("focus",'#txtReceiptAmount', function(e) {	
		$(this).val("");	
	});	
	$(document).on("click","#btnLinkPayment",function(){
		mostarModalPersonalizado('ABRIENDO LINK DE PAGO');	
		window.open("<?php echo base_url(); ?>/app_invoice_api/getLinkPaymentPagadito/companyID/"+$("#txtCompanyID").val()+"/transactionID/"+$("#txtTransactionID").val() +"/transactionMasterID/"+$("#txtTransactionMasterID").val(),"MsgWindow","width=700,height=600");
		cerrarModal('ModalCargandoDatos');
	});
	$(document).on("keypress",'#txtReceiptAmount', function(e) {	
		var code = e.keyCode || e.which;
		if(code != 13) 
		{ 
			 return;
		}	
		document.getElementById("txtReceiptAmountDol").focus();
		return;
			
	});
	
	$(document).on("focus",'#txtReceiptAmountDol', function(e) {	
		$(this).val("");	
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
		
		return;
	});
	
	
	

	$(document).on("keydown",'#txtScanerCodigo', function(e) {
		var code = e.keyCode || e.which;						
		
		//Nueva
		if(e.key === "k" && e.ctrlKey) { 		
			e.preventDefault();
			e.stopPropagation();			
			window.location = "<?php echo base_url(); ?>/app_invoice_billing/add/codigoMesero/<?php echo $codigoMesero; ?>";			 
		}
		
		//Imprimir Factura Anterior
		if(e.key === "m" && e.ctrlKey && varParameterInvoiceBillingPrinterDataLocal != "" )
		{
			
			e.preventDefault();
			e.stopPropagation();			
			
			var url	= varParameterUrlServidorDeImpresion+varParameterInvoiceBillingPrinterDirectUrl;
			url 	= url+
			"/companyID/"+"<?php echo $dataPrinterLocalCompanyID; ?>" + 
			"/transactionID/"+"<?php echo $dataPrinterLocalTransactionID; ?>"+
			"/transactionMasterID/"+"<?php echo $dataPrinterLocalTransactionMasterID; ?>";
			
			$.ajax({									
				cache       : false,
				dataType    : 'json',
				type        : 'POST',
				data		: { "fromServer" : varParameterInvoiceBillingPrinterDataLocal },
				url  		: url,
				success		: function(){
									
				},
				error:function(xhr,data){
					console.info("complete data error");									
					console.info(data);
					console.info(xhr);
				}
			});	
			
			
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
					if(data.error){
						fnShowNotification(data.message,"error");
					}
					else{
						window.location = "<?php echo base_url(); ?>/app_invoice_billing/index";
					}
				},
				error:function(xhr,data){	
					console.info("complete delete error");
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
		
		e.preventDefault();
		var currencyID 		= $("#txtCurrencyID").val();
		var codigoABuscar 	= $("#txtScanerCodigo").val();
		codigoABuscar 		= codigoABuscar.toUpperCase();
		$("#txtScanerCodigo").val("");
		
		
		//++Abrir popup de productos
		if(  codigoABuscar.includes("++")  ){
			codigoABuscar = codigoABuscar.replace("++","");
			fnCreateTableSearchProductos(codigoABuscar);
			return;
		}
		
		//Mover a ingreso de dinero Cordoba
		if(codigoABuscar == ""){
			$("#txtReceiptAmount").focus();
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
				var codigoABuscar 	= e.codigoABuscar.toUpperCase();
				let data			= e.all;
				var encontrado		= false;				
				for(var i = 0 ; i < e.length ; i++)
				{
					
					if(encontrado == true)
					{
						i--;
						break;
					}
					
					//buscar por codigo de sistema					
					var currencyTemp	= data[i].currencyID;
					var currencyID 		= $("#txtCurrencyID").val();
					
					var warehouseIDTemp		= data[i].warehouseID;
					var warehouseID			= $("#txtWarehouseID").val();
					
					if(  
						currencyID == currencyTemp && 
						fnDeleteCerosIzquierdos(codigoABuscar) == fnDeleteCerosIzquierdos(data[i].Codigo.replace("BITT","").replace("ITT","").toUpperCase())  && 
						warehouseID == warehouseIDTemp
					)
					{
						
						encontrado 		= true;
						break;
					}
					
					
					//buscar por codigo de barra
					var listCodigTmp 	= data[i].Barra.split(",");
					currencyTemp		= data[i].currencyID;
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
					var filterResult 		= data[i];						
					var filterResultArray 	= [];					
					filterResultArray[5]  	= filterResult.itemID;
					filterResultArray[17] 	= filterResult.Codigo;
					filterResultArray[18] 	= filterResult.Nombre;
					filterResultArray[20] 	= filterResult.Medida;
					filterResultArray[21] 	= 1; //filterResult.Cantidad;
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
		var url_request 				= "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentTransactionBilling->componentID; ?>/onCompleteSelectInvoice/SELECCIONAR_BILLING_REGISTER/true/empty/false/not_redirect_when_empty";
		window.open(url_request,"MsgWindow","width=900,height=450");
		window.onCompleteSelectInvoice 	= onCompleteSelectInvoice; 
	});
	
	//Buscar el Cliente
	$(document).on("click","#btnSearchCustomer",function(){
		
		//Ocultar Boton de Contado
		$("#divTipoFactura").addClass("hidden");
		$("#divLineaCredit").addClass("hidden");

		//Redireccion pantalla
		var url_redirect		= "__app_cxc_customer__add__callback__onCompleteCustomer__comando__pantalla_abierta_desde_la_factura";			
		url_redirect 			= encodeURIComponent(url_redirect);
		
		
		var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_ALL_PAGINATED/true/empty/true/"+url_redirect+"/1/1/"+varParameterCantidadItemPoup+"/";
		window.open(url_request,"MsgWindow","width=900,height=450");
		window.onCompleteCustomer = onCompleteCustomer; 
	});						

	//Eliminar Cliente
	$(document).on("click","#btnClearCustomer",function(){
				$("#txtCustomerID").val("");
				$("#txtCustomerDescription").val("");
	});
	
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

	//mostrar dialogo de informacion de producto
    $(document).on('click', '.btnInfoProducto',function(e){
        e.preventDefault();
        //obtener datos
        let selectTr 				= $(this).closest('tr');
        selectedFilaInfoProducto 	= objTableDetail.fnGetPosition(selectTr[0]);
        selectedDataInfoProducto 	= objTableDetail.fnGetData(selectedFilaInfoProducto);
        let precio1 				= selectedDataInfoProducto[columnasTableDetail.precio1];
        let precio2 				= selectedDataInfoProducto[columnasTableDetail.precio2];
        let precio3 				= selectedDataInfoProducto[columnasTableDetail.precio3];
        let precios 				= [fnFormatNumber(precio1, 2), fnFormatNumber(precio2, 2), fnFormatNumber(precio3, 2)];
        let selectPrecio 			= $('#selectPrecio');
        let precio 					= selectedDataInfoProducto[7];
        selectPrecio.empty();

        //establecer precios
        if (precio>0) {
            precios.forEach(function(p) {
                let selected 	= parseFloat(p) === parseFloat(precio);
				let option 		= new Option(p, p, selected, selected);
                selectPrecio.append(option);
            });
        } else {
            let i = 0;
            precios.forEach(function(p) {
                let option = new Option(p, p, i===0, i===0);
                selectPrecio.append(option);
                i++;
            });
        }

        selectPrecio.trigger('change');

        //establecer vendedor
        let vendedor = selectedDataInfoProducto[19];
        if(vendedor>0){
            $('#selectVendedor').val(vendedor).trigger('change');
        }else{
            $('#selectVendedor').val("0").trigger('change');
        }
        //establecer serie
        let serie 			= selectedDataInfoProducto[20];
        $('#txtSerieProducto').val(serie);
        //establecer referencia
        let infoReferencia 	= selectedDataInfoProducto[21];
        $('#txtReferenciaProducto').val(infoReferencia);
        refreschChecked();
        mostrarModal('ModalInfoProducto');
    });
	
	//Regresar a la lista
	$("#btnBack").click(function(e){
        e.preventDefault();
        let listRow = objTableDetail.fnGetData();
        let length 	= listRow.length;
        if(length > 0)
        {
            mostrarModal('ModalBackToList');
        }
        else
        {
            mostarModalPersonalizado('Regresando a la lista principal...')
            window.location.href = '<?php echo base_url(); ?>/app_invoice_billing/index';
        }
	});

	//Evento Agregar el Usuario
	$(document).on("click","#btnAcept",function(e){	
		e.preventDefault();
		fnEnviarFactura();
	});

	$(document).on("click",".btnAcept",function(e){			
		e.preventDefault();
		var valueWorkflow = $(this).data("valueworkflow");
		$("#txtStatusID").val(valueWorkflow);			
		fnEnviarFactura();
		
	});

	$(document).on("click","#btnSaveInvoice",function(e)
	{
		e.preventDefault();
		Swal.fire({ 
			title: '<strong>CARGANDO DATOS DE FACTURA...</strong>', 
			allowOutsideClick: false, 
			timer: 20000,
			didOpen: () => { 
				Swal.showLoading(); 
			} 
		});
		if(loadEdicion){
			var valueWorkflow = $(".btnAcept").data("valueworkflow");
			$("#txtStatusID").val(valueWorkflow);	
		}					
		fnEnviarFactura();
		
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
			$("#txtScanerCodigo").focus();				
		}		
		
	});	
	
	$('#btnDelete').click(function(){
		mostrarModal('ModalDeleteInvoice');
	});
	$(document).on("click","#btnNewItem",function(){
		
		var CodigoBuscar = "";
		fnCreateTableSearchProductos(CodigoBuscar);
	
	});

	$(document).on("click","#btnNewItemCatalog",function(){
		
		var url_request 					= "<?php echo base_url(); ?>/app_inventory_item/add/callback/fnObtenerListadoProductos";
		window.open(url_request,"MsgWindow","width=700,height=600");
		window.fnObtenerListadoProductos 	= fnObtenerListadoProductos; 			
	});

	$(document).on("click","#btnRefreshDataCatalogo",function(){
		openDataBaseAndCreate(false,true);		
	});


	$(document).on("click","#btnSearchCustomerNew",function(){
		var url_request 				 	= "<?php echo base_url(); ?>/app_cxc_customer/add/callback/fnCustomerNewCompleted";
		window.open(url_request,"mozillaWindow","width=700,height=600");
		window.fnCustomerNewCompleted 		= fnCustomerNewCompleted; 	
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
	//Ir a archivos
	$(document).on("click","#btnClickArchivo",function(){
		if(objComponentBilling.componentID)
			window.open(varBaseUrl + "/core_elfinder/index/componentID/" + objComponentBilling.componentID + "/componentItemID/" + objTransactionMaster.transactionMasterID,"blanck");
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
	$(document).on("focus","input#txtReceiptAmountTarjeta",function(){							
			$(this).val("");			
	});
	$(document).on("change","input#txtReceiptAmountTarjeta",function(){							
			fnCalculateAmountPay();			
	});
	$(document).on("focus","input#txtReceiptAmountTarjetaDol",function(){							
			$(this).val("");		
	});
	$(document).on("change","input#txtReceiptAmountTarjetaDol",function(){							
			fnCalculateAmountPay();			
	});
	$(document).on("change","input#txtReceiptAmountBankDol",function(){							
			fnCalculateAmountPay();			
	});
	$('#tb_transaction_master_detail').on('change', 'input', function () {
		var input 		= $(this);
		var inputType 	= input.attr('type');
		var tr 			= input.closest('tr')[0];
		var td 			= input.parents('td')[0];
		var rowIndex 	= objTableDetail.fnGetPosition(tr);
		var colIndex 	= $('td', tr).index(td);

		if (inputType === "checkbox") {
			var isChecked = input.is(':checked');
			objTableDetail.fnUpdate(isChecked, rowIndex, colIndex, true);
			refreschChecked();
		}
		else {
			var newValue = input.val();
			if (colIndex === columnasTableDetail.descripcion) {
				objTableDetail.fnUpdate(newValue, rowIndex, columnasTableDetail.descripcion, true);
			}
		}
	});

    $('.item-categoria').on('click', function () {
		mostarModalPersonalizado('Cargando, por favor espere...');
        $(".custom-table-container-inventory").show();
        var filterValue = $(this).attr('data-filter');        
        $grid.isotope({ filter: filterValue + ', .item-producto-back' });
        $(".item-categoria").removeClass('selected');
        $(this).addClass("selected");
		cerrarModal('ModalCargandoDatos');
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
	

	$("#btnAceptarDialogPrinterV2AceptarTabla").click(function(){		
	});
	
	$("#btnAceptarDialogPrinterV2AceptarDocument").click(function(){			
		mostarModalPersonalizado('Cargando impresión, por favor espere...');
		window.open("<?php echo base_url(); ?>/"+varUrlPrinter+"/companyID/2/transactionID/19/transactionMasterID/"+$("#txtTransactionMasterID").val(), '_blank');
		cerrarModal('ModalCargandoDatos');
		cerrarModal("ModalOpcionesImpresion");
	});
	$("#btnCloseModalOpcionesImpresion").click(function(){		
		cerrarModal("ModalOpcionesImpresion");
	});
	
	$("#btnAceptarDialogPrinterV2AceptarDirect").click(function(){		
	});

	$('#txtPorcentajeDescuento').on('input', function() {
		 //validar que solo sea numero
		 var valor 		= $(this).val();
		 var expresion 	= /^\d*\.?\d{0,2}$/;
		 if (!expresion.test(valor)) {
			$(this).val(valor.slice(0, -1));
		 }
		 fnRecalculateDetail(true,"");
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
	
	function fnAceptarModalModalPrinterDocumentDialogCustom()
	{
		mostarModalPersonalizado('Cargando, por favor espere...');
		var url=varParameterUrlServidorDeImpresion+varParameterInvoiceBillingPrinterDirectUrl;
			url = url+
			"/companyID/"+"<?php echo $dataPrinterLocalCompanyID; ?>" + 
			"/transactionID/"+"<?php echo $dataPrinterLocalTransactionID; ?>"+
			"/transactionMasterID/"+"<?php echo $dataPrinterLocalTransactionMasterID; ?>";
									
		$.ajax({									
			cache       : false,
			dataType    : 'json',
			type        : 'POST',
			data		: { "fromServer" : varParameterInvoiceBillingPrinterDataLocal },
			url  		: url,
			success		: function(){
								
			},
			error:function(xhr,data){
				console.info("complete data error");									
				console.info(data);
				console.info(xhr);
			}
		});	
			
		
		cerrarModal("ModalPrinterDocumentDialogCustom");
		cerrarModal('ModalCargandoDatos');		
	}
	
	function fnCalculateAmountPay()
	{
        let resultTotal     = 0.0;
        let currencyId      = $("#txtCurrencyID").val();
        let ingresoCordoba 	= fnFormatFloat($("#txtReceiptAmount").val());
        let bancoCordoba 	= fnFormatFloat($("#txtReceiptAmountBank").val());
        let puntoCordoba 	= fnFormatFloat($("#txtReceiptAmountPoint").val());
        let tarjetaCordoba 	= fnFormatFloat($("#txtReceiptAmountTarjeta").val());
        let tarejtaDolares 	= fnFormatFloat($("#txtReceiptAmountTarjetaDol").val());
        let bancoDolares 	= fnFormatFloat($("#txtReceiptAmountBankDol").val());
        let ingresoDol 	    = fnFormatFloat($("#txtReceiptAmountDol").val());
        let tipoCambio 	    = fnFormatFloat($("#txtExchangeRate").val());
        let total 		    = fnFormatFloat($("#txtTotal").val());
        if( currencyId === "1" /*Cordoba*/ )
		{
            resultTotal =  (ingresoCordoba +  bancoCordoba + puntoCordoba + tarjetaCordoba + ( bancoDolares / tipoCambio ) + ( tarejtaDolares / tipoCambio )   + (ingresoDol / tipoCambio)) - total;
		}else if( currencyId === "2" /*dolares*/ )
		{
			resultTotal =  (ingresoCordoba +  bancoCordoba + puntoCordoba + tarjetaCordoba + ( bancoDolares * tipoCambio ) + ( tarejtaDolares * tipoCambio )   + (ingresoDol * tipoCambio)) - total;
		}

        resultTotal = fnFormatNumber(resultTotal,2);
        $("#txtChangeAmount").val(resultTotal);
    }
	
	function fnImprimir(){
		
		
		if(
			varParameterInvoiceBillingPrinterDirect == 'true' && 
			objParameterPrinterDirectAndPreview == 'false' && 
			varParameterTipoPrinterDownload == 'false' 
		)
		{
			
			var url	= varParameterUrlServidorDeImpresion+varParameterInvoiceBillingPrinterDirectUrl;
			url 	= url+
			"/companyID/"+ objTransactionMaster.companyID + 
			"/transactionID/"+ objTransactionMaster.transactionID +
			"/transactionMasterID/"+ objTransactionMaster.transactionMasterID;
			mostarModalPersonalizado('Imprimiendo, por favor espere...');
			$.ajax({									
				cache       : false,
				dataType    : 'json',
				type        : 'POST',
				data		: { "fromServer" : varParameterInvoiceBillingPrinterDataLocal },
				url  		: url,
				success		: function(){					
				},
				error:function(xhr,data){
					console.info("complete data error");									
					console.info(data);
					console.info(xhr);
					//fnShowNotification("Error 505","error");
				},
				complete: function(){
					cerrarModal('ModalCargandoDatos');
				}
			});	
			return;
		}
		if ( varParameterTipoPrinterDownload == 'true' )
		{
			mostarModalPersonalizado('Imprimiendo, por favor espere...');
            window.open("<?php echo base_url(); ?>/"+ varUrlPrinter + "/companyID/"+ objTransactionMaster.companyID +"/transactionID/" + objTransactionMaster.transactionID+"/transactionMasterID/" + objTransactionMaster.transactionMasterID, '_blank');
			cerrarModal('ModalCargandoDatos');
			return;
		}
		if(objParameterPrinterDirectAndPreview == 'true')
		{
			mostrarModal("ModalOpcionesImpresion");
			return
		}
		
		
		mostrarModal("ModalOpcionesImpresion");
		
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
			
			var url	=	"<?= base_url(); ?>/"+varParameterInvoiceBillingPrinterDirectBarUrl;
			url 	= 	url+
			"/companyID/"+ objTransactionMaster.companyID + 
			"/transactionID/"+ objTransactionMaster.transactionID +
			"/transactionMasterID/"+ objTransactionMaster.transactionMasterID +
			"/itemID/"+itemid;
			mostarModalPersonalizado('Imprimiendo, por favor espe...');	
			$.ajax({									
				cache       : false,
				dataType    : 'json',
				type        : 'GET',
				url  		: url,
				success		: function(){						
				},
				error:function(xhr,data){
					console.info("complete data error");									
					console.info(data);
					console.info(xhr);
				},
				complete: function(){
					cerrarModal('ModalCargandoDatos');
				}
			});	
			return;
		}
		else{
			mostrarModal('ModalImprimirComandaBar');
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
			
			var url	= 	"<?php echo base_url(); ?>/"+varParameterInvoiceBillingPrinterDirectCocinaUrl;
			url 	= 	url+
			"/companyID/"+ objTransactionMaster.companyID + 
			"/transactionID/"+ objTransactionMaster.transactionID +
			"/transactionMasterID/"+ objTransactionMaster.transactionMasterID +
			"/itemID/"+itemid+"/transactionMasterComment/"+comentario;
			
			mostarModalPersonalizado('Imprimiendo, por favor espere...');
			$.ajax({									
				cache       : false,
				dataType    : 'json',
				type        : 'GET',
				url  		: url,
				success		: function(){						
				},
				error:function(xhr,data){
					console.info("complete data error");									
					console.info(data);
					console.info(xhr);
					//fnShowNotification("Error 505","error");
				},
				complete: function(){
					cerrarModal('ModalCargandoDatos');
				}
			});	
			return;
		}
		else{
			mostrarModal("ModalCustomCocinaV2");
			return
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
	}
	
	//Cargar Factura
	function onCompleteSelectInvoice(objResponse) {
		console.info("CALL onCompleteSelectInvoice");
		if (!objResponse || objResponse.length === 0) return;
		let data = objResponse[0];
		// Muestra la alerta inmediatamente
		mostarModalPersonalizado("Cargando datos de factura, por favor espere...");
		let url = objParameterPantallaParaFacturar == "-" 
			? `${varBaseUrl}/app_invoice_billing/edit/${data[0]}/${data[1]}/${data[2]}/${$("#txtCodigoMesero").val()}`
			: `${varBaseUrl}/app_invoice_billing/${objParameterPantallaParaFacturar}/${data[0]}/${data[1]}/${data[2]}/${$("#txtCodigoMesero").val()}`;

		$.ajax({
			url		: url,
			success	: function(response) {           
				fnUpdateInvoiceView(response.data);
			},
		});
	}

	
	function onCompleteCustomer(objResponse){
		
		console.info("CALL onCompleteCustomer");
		if(objResponse !== undefined)
		{
            mostarModalPersonalizado('cargando datos del cliente, por favor espere...');
			var entityID = objResponse[0][1];
			$("#txtCustomerID").val(objResponse[0][1]);
			$("#txtCustomerDescription").val(objResponse[0][2] + " " + objResponse[0][3] + " / " + objResponse[0][4]);	
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
		objRow.um							= objResponse[23];
		objRow.umDescription				= objResponse[20];
		objRow.quantity 					= fnFormatNumber(objResponse[21],2);
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
		objRow.peso 						= 0;
		objRow.vendedor 					= 0;
		objRow.serie 						= "";
		objRow.referencia 					= "";
		objRow.price1 					    = fnFormatNumber(objResponse[22],2);

		//Actualizar
		if(jLinq.from(objTableDetail.fnGetData()).where(function(obj){ return obj[2] == objRow.itemID;}).select().length > 0 ){
			
			var x_ 			=  jLinq.from(objTableDetail.fnGetData()).where(function(obj){ return obj[2] == objRow.itemID;}).select();
			var newCantidad =  0;
			
			if (suma == true)
			newCantidad =  parseFloat(fnFormatNumber(x_[0][6],2)) + parseFloat(objRow.bquantity);
			else
			newCantidad =  parseFloat(fnFormatNumber(x_[0][6],2)) - parseFloat(objRow.bquantity);
			
			var objind_ 	= fnGetPosition(x_,objTableDetail.fnGetData());
			objTableDetail.fnUpdate( fnFormatNumber(newCantidad,2)  , objind_, 6 );
			
			
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
				objRow.umDescription,
				objRow.price2,
				objRow.price3,
				objRow.itemNameDescription /*itemDescriptionLog*/,
				objRow.taxServices,
				objRow.peso,
                objRow.vendedor,
                objRow.serie,
                objRow.referencia,
                objRow.price1
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
		$("#txtScanerCodigo").focus();
		
		
		
	}
	
	function fnSetCheckBoxValue(element,value)
	{
		if(value)
		{
			element.parent().removeClass("switch-off");
			element.parent().addClass("switch-on");
		}
		else
		{
			element.parent().removeClass("switch-on");
			element.parent().addClass("switch-off");
		}
	}

	function validateFormAndSubmit(){	
		let result 				= true;		
		let timerNotification 	= 15000;
		let switchDesembolso	= !$("#txtLabelIsDesembolsoEfectivo").parent().find(".switch.has-switch").children().hasClass("switch-off");
		
		//Validar bodega de despacho
		if($("#txtWarehouseID").val() == ""){		
			Toast.fire({
				icon: "warning",
				title: "Seleccionar bodega de desapcho"
			});	
			result = false;	
		}
		
		
		//Validar Fecha		
		if($("#txtDate").val() == ""){
			Toast.fire({
				icon: "warning",
				title: "Establecer Fecha al Documento"
			});	
			result = false;
		}
		
		//Validar Cliente		
		if($("#txtCustomerID").val() == ""){
			Toast.fire({
				icon: "warning",
				title: "Seleccionar el Cliente"
			});
			result = false;
		}
		//Validar Proveedor de Credito
		if($("#txtReference1").val() == "0" && switchDesembolso){
			Toast.fire({
				icon: "warning",
				title: "Seleccionar el Proveedor de Credito"
			});
			result = false;
		}
		//Validar Zona
		if($("#txtZoneID").val() == "" && switchDesembolso){
			Toast.fire({
				icon: "warning",
				title: "Seleccionar la Zona de la Factura"
			});
			result = false;
		}
		//Validar Vendedor
		if($("#txtEmployeeID").val() == "" && switchDesembolso){
			Toast.fire({
				icon: "warning",
				title: "Seleccionar el vendedor en la Factura"
			});
			result = false;
		}
		
		//Validar monto descuento en rango de 0 a 100
		let porcentajeDescuento = parseFloat($('#txtPorcentajeDescuento').val()) || 0;
		if (porcentajeDescuento < 0 || porcentajeDescuento > 100) {
			Toast.fire({
				icon: "warning",
				title: "El porcentaje de descuento no es valido"
			});
			result = false;
        }

		//Validar Estado de la factura
		if($("#txtStatusIDOld").val() ==  varStatusInvoiceAplicado){
			Toast.fire({
				icon: "error",
				title: "Crear una nueva factura, por que la actual esta aplicada, no puede ser modificada"
			});
			result = false;
		}
		
		//Validar estado anulado
		if($("#txtStatusID").val() ==  varStatusInvoiceAnular){
			Toast.fire({
				icon: "error",
				title: "No puede pasar a estado anulado"
			});
			result = false;
		}
		
		//Validar Detalle
		//
		///////////////////////////////////////////////		
		var cantidadTotalesEnZero 	= jLinq.from(objTableDetail.fnGetData()).where(function(obj){ return obj[8] == 0;}).select().length ;
		var validateTotalesZero 	= true;
		<?php echo getBehavio($company->type,"app_invoice_billing","scriptValidateTotalesZero",""); ?>  

		if(validateTotalesZero == true)
		{
			if(cantidadTotalesEnZero > 0){
				Toast.fire({
					icon	: "warning",
					title	: "No pueden haber totales en 0"
				});
				result = false;
			}
		}
		
		
		var cantidadTotalesEnZero = jLinq.from(objTableDetail.fnGetData()).where(function(obj){ return obj[6] == 0;}).select().length ;
		if(cantidadTotalesEnZero > 0){
			Toast.fire({
				icon	: "warning",
				title	: "No pueden haber cantidades en 0"
			});
			result = false;
		}
		
		
		if( /*varAutoAPlicar == "true" && */ objTableDetail.fnGetData().length == 0){
			Toast.fire({
				icon	: "warning",
				title	: "La factura no puede estar vacia"
			});
			result = false;
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
		}
		
		//No puede haber cambio, si la factura es de credito
		if(invoiceTypeCredit && $("#txtChangeAmount").val() > 0 )
		{
			result = false;
			Toast.fire({
				icon: "warning",
				title: "No puede haber cambio si la factura es de credito"
			});
		}
		
		
		<?php echo getBehavio($company->type,"app_invoice_billing","scriptValidateCustomer",""); ?>  
		
		
		//Validaciones si la factura es al credito.
		if(invoiceTypeCredit){
			
			<?php echo getBehavio($company->type,"app_invoice_billing","scriptValidateInCredit",""); ?>  
			
			//Validar Fecha del Primer Pago si es de Credito
			if($("#txtDateFirst").val() == "" && switchDesembolso){
				result = false;
				Toast.fire({
					icon	: "warning",
					title	: "Seleccionar la Fecha del Primer Pago"
				});
			}
			
			
			//Validar Notas
			if($("#txtNote").val() == "" && switchDesembolso){
				fnShowNotification("Asignarle una nota al documento","error",timerNotification);
				result = false;
				Toast.fire({
					icon	: "warning",
					title	: "Asignarle una nota al documento"
				});
			}
			
			//Validar Escritura Publica
			if($("#txtFixedExpenses").val() == "" && switchDesembolso){
				fnShowNotification("Ingresar el Porcentaje de Gastos Fijo por Desembolso","error",timerNotification);
				result = false;
				Toast.fire({
					icon	: "warning",
					title	: "Ingresar el Porcentaje de Gastos Fijo por Desembolso"
				});
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
				result = false;
				Toast.fire({
					icon	: "warning",
					title	: "La factura no puede ser facturada al credito. Balance del cliente: " + balanceCredit
				});
			}
			
			
		}
		else{			
			//Validar Pago
			if( parseFloat( $("#txtChangeAmount").val() )  < 0 ){
				result = false;
				Toast.fire({
					icon	: "warning",
					title	: "El cambio de la factura no puede ser menor a 0"
				});
			}
		}
		
		
		
		if(varPermitirFacturarProductosEnZero === "true" && result )
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
								$("#form-new-invoice").submit();
							}
							else{
								cerrarModal('ModalCargandoDatos');	
								for(var ie = 0 ; ie < result.resultValidate.length ; ie++){								
									fnShowNotification(
										"" + 
										result.resultValidate[ie].Codigo + " " + 
										result.resultValidate[ie].Nombre + " cantidad en bodega   " + 
										result.resultValidate[ie].QuantityInWarehouse +  "  " + 
										result.resultValidate[ie].Mensaje,
										"error"
									);
									Toast.fire({
										icon	: "warning",
										title	: "" + 
											result.resultValidate[ie].Codigo + " " + 
											result.resultValidate[ie].Nombre + " cantidad en bodega   " + 
											result.resultValidate[ie].QuantityInWarehouse +  "  " + 
											result.resultValidate[ie].Mensaje
									});
								}
							}
							
							
					},
					error: function(result){
						Toast.fire({
							icon	: "error",
							title	: "Error al realizar la petición en el servidor, 500"
						});
					},
					complete: function(){
						cerrarModal('ModalCargandoDatos');
					}
				}
			);
		}else{
			cerrarModal('ModalCargandoDatos');
		}
	}

    // Evitar el envío tradicional y manejar con AJAX
    $("#form-new-invoice").on('submit', function(e) {
        e.preventDefault(); // Esto evita el envío tradicional

        // Serializar los datos del formulario
        var formData = $(this).serialize();

        $.ajax({
            url		: $(this).attr('action'),
            type	: $(this).attr('method'),
            dataType: 'json',
            data	: formData,         
            success	: function(response) {
				
                // Restaurar botón
                if(response.success) {
                    fnUpdateInvoiceView(response.data);
					ToastSuccess.fire({
							icon  : 'success',
							title : 'Factura aplicada correctamente'
					});
                } else {
                    // Manejar error
					Toast.fire({
							icon  : 'error',
							title : 'Codigo:' + response.error.code + " , " + response.error.message 
					});
                }
            },
            error: function(xhr) {
				
                // Manejar error de conexión
                Swal.fire({
                    icon	: 'error',
                    title	: 'Error de conexión',
                    text	: 'No se pudo conectar con el servidor'
                });

                console.error("Error en AJAX:", xhr.responseText);
            },
			complete: function(){
				cerrarModal('ModalCargandoDatos');
			}
        });
    });

	function mostarModalPersonalizado(title){
		$('#title-modal').text(title.toUpperCase());
		mostrarModal('ModalCargandoDatos');
	}

    function fnClearForm(){
		
		loadEdicion = false;
		mostarModalPersonalizado('Cargando nueva factura, por favor espere...');
        $("#form-new-invoice")[0].reset();
		$('#invoice-num').text("00000000");		
		$("select").each(function() {
			let firstValue = $(this).find("option:first").val(); 
			if(firstValue !== undefined){
				$(this).val(firstValue).trigger("change"); // Asigna el valor y dispara el evento 'change'
			}			
		});
		
		$('#txtTypePriceID').val(<?= $objParameterTypePreiceDefault?>);		
		$('#txtWarehouseID').val(<?= $objParameterTipoWarehouseDespacho?>);
		$("#txtCurrencyID option").each(function() {
			if ($(this).text().trim() === '<?= $objParameterACCOUNTING_CURRENCY_NAME_IN_BILLING ?>') {
				$(this).prop("selected", true);
				$("#txtCurrencyID").trigger("change");
			}
		});
		if(objParameterINVOICE_OPEN_CASH_WHEN_PRINTER_INVOICE==="false"){
            $('#divPanelOpenCash').css('display','block');
        }else{
            $('#divPanelOpenCash').css('display', 'none');
        }
        $('#txtCustomerCreditLineID').empty();
        objListCustomerCreditLine 	= JSON.parse('<?php echo json_encode($objListCustomerCreditLine); ?>');
        objCausalTypeCredit 		= JSON.parse('<?php echo json_encode($objCausalTypeCredit); ?>');
        fnRenderLineaCredit(objListCustomerCreditLine, objCausalTypeCredit);
        $('#txtDate').datepicker({format:"yyyy-mm-dd"});
		$('#txtDate').val(moment().format("YYYY-MM-DD"));	
		$("#txtDate").datepicker("update");
		$('#txtNextVisit').datepicker({format:"yyyy-mm-dd"});		
		$('#txtDateFirst').datepicker({format:"yyyy-mm-dd"});						 
		$('#txtDateFirst').val(moment().add('days', 0).format("YYYY-MM-DD"));			 
		$("#txtDateFirst").datepicker("update");
		$('#txtCustomerID').val(<?= $objCustomerDefault->entityID;  ?>);
		$('#btnLinkPayment').css('display','none');
		$('#rowBotoneraFacturaFila5').css('display','none');
		$('.showComandoDeCocina').css('display', 'none');
		$('.showPanelEdicion').css('display', 'none');
		$('#registrarFacturaNueva').css('display','block');
		$('#showCommandBar').css('display', 'none');
		$("#workflowLink").empty();
		transactionID = <?= $transactionIDNueva ?>;
		$('#txtStatusID').val(<?= isset($objListWorkflowStage) ? $objListWorkflowStage[0]->workflowStageID : 0 ?>);
        $('#txtStatusIDOld').val(0);
		$("#txtCheckApplyExoneracionValue").val(0);
		$("#txtCheckReportSinRiesgoValue").val(0);
		$("#txtCheckDeEfectivoValue").val(0);
		fnSetCheckBoxValue($("#txtCheckApplyExoneracion"), false);
		fnSetCheckBoxValue($("#txtCheckReportSinRiesgo"), false);
		fnSetCheckBoxValue($("#txtCheckDeEfectivo"), false);
		
		
		<?php echo getBehavio($company->type, 'app_invoice_billing', 'jsClearForm','') ?>
		setTimeout(()=>{ cerrarModal('ModalCargandoDatos'); }, 1000);
		
    }

    function fnUpdateInvoiceView(data){
        console.info("LOAD INVOICE");
        loadEdicion = true;
		objParameterINVOICE_BILLING_SHOW_COMMAND_BAR    = data.objParameterINVOICE_BILLING_SHOW_COMMAND_BAR;
        varParameterTipoPrinterDownload     			= data.objParameterTipoPrinterDonwload;
        objParameterPrinterDirectAndPreview 			= data.objParameterPrinterDirectAndPreview;        
        varParameterShowComandoDeCocina     			= data.objParameterShowComandoDeCocina;
        varUrlPrinterOpcion2    = data.urlPrinterDocumentOpcion2;
        varUrlPrinterCocina	    = data.urlPrinterDocumentCocina;
        varUrlPrinterBar        = data.objParameterINVOICE_BILLING_PRINTER_URL_BAR;

        objParameterINVOICE_OPEN_CASH_WHEN_PRINTER_INVOICE		= data.objParameterINVOICE_OPEN_CASH_WHEN_PRINTER_INVOICE;
        objParameterINVOICE_OPEN_CASH_PASSWORD					= data.objParameterINVOICE_OPEN_CASH_PASSWORD;
		objParameterPantallaParaFacturar						= data.objParameterPantallaParaFacturar;
        varDetail               = data.objTransactionMasterDetail;
        varDetailReferences		= data.objTransactionMasterDetailReferences;
        varDetailWarehouse		= data.objTransactionMasterDetailWarehouse;
        varDetailConcept 		= data.objTransactionMasterDetailConcept;

        varParameterInvoiceBillingPrinterDirect				= data.objParameterInvoiceBillingPrinterDirect;
        varParameterInvoiceBillingPrinterDirectUrl			= data.objParameterInvoiceBillingPrinterDirectUrl;
        varParameterInvoiceBillingPrinterDirectCocinaUrl	= data.urlPrinterDocumentCocinaDirect;
        varParameterInvoiceBillingPrinterDirectBarUrl		= data.objParameterINVOICE_BILLING_PRINTER_DIRECT_URL_BAR;
        varParameterInvoiceBillingPrinterDataLocal			= data.dataPrinterLocal;
        varParameterUrlServidorDeImpresion 					= data.objParameterUrlServidorDeImpresion;
        varParameterINVOICE_BILLING_VALIDATE_EXONERATION 	= data.objParameterINVOICE_BILLING_VALIDATE_EXONERATION;
        objParameterINVOICE_SHOW_FIELD_PESO					= data.objParameterINVOICE_SHOW_FIELD_PESO;
		objParameterPrinterDirectAndPreview					= data.objParameterPrinterDirectAndPreview;
		objListCustomerCreditLine							= data.objListCustomerCreditLine;
        objTransactionMaster                    			= data.objTransactionMaster;
        objTransactionMasterItemPrice 						= data.objTransactionMasterItemPrice;
        objTransactionMasterItemConcepto 					= data.objTransactionMasterItemConcepto;
        objTransactionMasterItemSku 						= data.objTransactionMasterItemSku;
        objTransactionMasterItem 							= data.objTransactionMasterItem;
        varParameterUrlServidorDeImpresion      			= data.objParameterUrlServidorDeImpresion;
        varTransactionCausalID	                			= objTransactionMaster.transactionCausalID;
        varCustomerCrediLineID	                			= objTransactionMaster.reference4;
        
		let objTransactionMasterDetailCredit 	= data.objTransactionMasterDetailCredit;
        let objTransactionMasterInfo            = data.objTransactionMasterInfo;
		let objNaturalDefault 					= data.objNaturalDefault;
		let objLegalDefault 					= data.objLegalDefault;
		let objCustomerDefault  				= data.objCustomerDefault;
		let objTransactionMasterReferences  	= data.objTransactionMasterReferences;

		transactionID 			= objTransactionMaster.transactionID;
		transactionMasterID 	= objTransactionMaster.transactionMasterID;
		objComponentBilling		= data.objComponentBilling;
	
        let counter 				= 0;
        let objListWorkflowStage 	= data.objListWorkflowStage;
		$('#linkMobile').empty();
		if(varParameterShowComandoDeCocina === "true"){
			$('#linkMobile').append('<li><a href="#"  id="btnFooter">COCINA</a></li>');
		}
		if(objParameterINVOICE_BILLING_SHOW_COMMAND_BAR == "true"){
			$('#showCommandBar').show();
			$('#linkMobile').append('<li><a href="#"  id="btnBar">BAR</a></li>');
		}
        if(objListWorkflowStage.length > 0){
			$("#workflowLink").empty();
            for(let w=0; w < objListWorkflowStage.length; w++){
                let nameWorkflow 	= data.objListWorkflowStageNames[w];
                let ws 				= objListWorkflowStage[w];
                let buttonClass 	= w === 0 ? 'btn-comando-factura' : 'btnAceptAplicar btn-comando-factura';
                let buttonHtml 		= '<div class="col col-lg-2">'+
                    '<a href="#" class="btn btn-flat btn-warning btnAcept btn-block '+ buttonClass +'" data-valueworkflow="'+ ws.workflowStageID+ '">' +
                        '<i class="icon16 i-checkmark-4"></i> ' + nameWorkflow.trim() +
                    '</a>'+
                '</div>';
				$('#linkMobile').append('<li><a href="#" class="btnAcept "' + buttonClass + '" data-valueworkflow="' + ws.workflowStageID + '"> ' + nameWorkflow.trim() + '</a></li>');
                $("#workflowLink").append(buttonHtml);
            }
        }

		//Renderizar combobox de las lineas de credito
        fnRenderLineaCredit(data.objListCustomerCreditLine, data.objCausalTypeCredit);

		$('#txtExchangeRate').val(data.exchangeRate);
		$('#invoice-num').empty().text(objTransactionMaster.transactionNumber);
		$('#txtDate').val(objTransactionMaster.transactionOn);
        $("#txtDate").datepicker("update");
        $('#txtNextVisit').val(objTransactionMaster.nextVisit);
        $("#txtNextVisit").datepicker("update");
        $('#txtDateFirst').val(objTransactionMaster.transactionOn2);
        $("#txtDateFirst").datepicker("update");
		$('#txtCompanyID').val(objTransactionMaster.companyID);
        $('#txtTransactionID').val(objTransactionMaster.transactionID);
        $('#txtTransactionMasterID').val(objTransactionMaster.transactionMasterID);
        $('#txtStatusID').val(objTransactionMaster.statusID);
        $('#txtStatusIDOld').val(objTransactionMaster.statusID);
		$('#txtNote').val(objTransactionMaster.note);
		$('#txtCurrencyID').val(objTransactionMaster.currencyID);
		$('#txtCustomerID').val(objTransactionMaster.entityID);

		if(objNaturalDefault !== null && objNaturalDefault.length > 0)
		{
			$('#txtCustomerDescription').val(objCustomerDefault.customerNumber.toUpperCase() + " " + objNaturalDefault.firstName.toUpperCase() + " " + $objNaturalDefault.lastName.toUpperCase());
		}
		else
		{
			$('#txtCustomerDescription').val(objCustomerDefault.customerNumber.toUpperCase() +" " + objLegalDefault.comercialName.toUpperCase());
		}

		$('#txtReferenceClientName').val(objTransactionMasterInfo.referenceClientName);
		$('#txtReferenceClientIdentifier').val(objTransactionMasterInfo.referenceClientIdentifier);
		$('#txtZoneID').val(objTransactionMasterInfo.zoneID).trigger("change");
		$('#txtWarehouseID').val(objTransactionMaster.sourceWarehouseID).trigger("change");
		$('#txtReference3').val(objTransactionMaster.reference3);
		$('#txtEmployeeID').val(objTransactionMaster.entityIDSecondary).trigger("change");
		$('#txtTypePriceID').val(0).trigger("change");
		$('#txtNumberPhone').val(objTransactionMaster.numberPhone);
		$('#txtMesaID').val(objTransactionMasterInfo.mesaID).trigger("change");
		$('#txtReference2').val(objTransactionMaster.reference2);
		$('#txtPeriodPay').val(objTransactionMaster.periodPay).trigger('change');
		$('#txtReference1').val(objTransactionMaster.reference1).trigger('change');
		$('#txtDayExcluded').val(objTransactionMaster.dayExcluded).trigger('change');
		$('#txtCausalID').val(objTransactionMaster.transactionCausalID).trigger("change");

		if(objTransactionMaster.isApplied === "1"){
			$('#txtIsApplied').bootstrapSwitch('toggleState', true);
		}else{
			$('#txtIsApplied').bootstrapSwitch('toggleState', false);
		}

		$('#txtFixedExpenses').val(objTransactionMasterDetailCredit.reference1);
		$('#txtTMIReference1').val(objTransactionMasterInfo.reference1);
		$('#txtLayFirstLineProtocolo').val(objTransactionMasterReferences.reference1);

		//limpiar tabla de datos
		objTableDetail.fnClearTable();

        if(varDetail != null){
			tmpData = [];
            for(let i = 0 ; i < varDetail.length;i++){
                //master detail reference
                let objDetailReference = jLinq.from(varDetailReferences).where(function(obj){ return obj.transactionMasterDetailID === varDetail[i].transactionMasterDetailID }).select();
                //Obtener Iva
                var tmp_ 			= jLinq.from(varDetailConcept).where(function(obj){ return obj.componentItemID === varDetail[i].componentItemID && obj.name === "IVA" }).select();
                var iva_ 			= (tmp_.length <= 0 ? 0 : parseFloat(tmp_[0].valueOut));
                var Precio2 		= jLinq.from(objTransactionMasterItemPrice).where(function(obj){ return (obj.itemID === varDetail[i].componentItemID && obj.typePriceID === "155"); }).select()[0].Precio;
                var Precio3 		= jLinq.from(objTransactionMasterItemPrice).where(function(obj){ return (obj.itemID === varDetail[i].componentItemID && obj.typePriceID === "156"); }).select()[0].Precio;
                var tax2			= varDetail[i].tax2;
                var taxServices 	= 0;

                //Validar impuesto IVA
                if (  isNaN(varDetail[i].tax1 / varDetail[i].unitaryPrice)  )
                {
                    iva_  = 0 ;
                }
                else
                {
                    iva_  = varDetail[i].tax1 / varDetail[i].unitaryPrice ;
                }

                //Validar servicio TAX_SERVICES
                if (  isNaN(varDetail[i].tax2 / varDetail[i].unitaryPrice)  )
                {
                    taxServices  = 0 ;
                }
                else
                {
                    taxServices  = varDetail[i].tax2 / varDetail[i].unitaryPrice ;
                }
                let infoSales       = '';
                let infoSerie       = '';
                let infoReferencia  = '';
                let infoPrecio1     = 0;
                let infoPrecio2     = 0;
                let infoPrecio3     = 0;

                if (objDetailReference && objDetailReference.length > 0) {
                    infoSales       = objDetailReference[0].sales;
                    infoSerie       = objDetailReference[0].reference1;
                    infoReferencia  = objDetailReference[0].reference2;
                    infoPrecio1     = objDetailReference[0].precio1;
                    infoPrecio2     = objDetailReference[0].precio2;
                    infoPrecio3     = objDetailReference[0].precio3;
                }

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
                    fnFormatNumber(iva_,2),
                    fnFormatNumber(varDetail[i].skuQuantityBySku, 4),
                    fnFormatNumber(varDetail[i].unitaryPrice, 4),
                    "",//acciones
                    varDetail[i].skuFormatoDescription,
                    fnFormatNumber(infoPrecio2, 2),
                    fnFormatNumber(infoPrecio3, 2),
                    "'"+varDetail[i].itemNameDescriptionLog + "'",
                    fnFormatNumber(taxServices,2) /*tax_services*/ ,
                    varDetail[i].reference1, /*peso */
                    infoSales,
                    infoSerie,
                    infoReferencia,
                    fnFormatNumber(infoPrecio1, 2)
                ]);
            }
			objTableDetail.fnAddData(tmpData);
			objTableDetail.fnDraw();
        }
        $("#txtDescuento").val(fnFormatNumber(objTransactionMaster.discount, 2));
        $("#txtPorcentajeDescuento").val(fnFormatNumber(objTransactionMaster.tax4,2));

        fnRecalculateDetail(false,"");

        $('#txtReceiptAmountTarjeta_BankID').val(objTransactionMasterInfo.receiptAmountCardBankID).trigger("change");
        $('#txtReceiptAmountTarjetaDol_BankID').val(objTransactionMasterInfo.receiptAmountCardBankDolID).trigger("change");
        $('#txtReceiptAmountBank_BankID').val(objTransactionMasterInfo.receiptAmountBankID).trigger("change");
        $('#txtReceiptAmountBankDol_BankID').val(objTransactionMasterInfo.receiptAmountBankDolID).trigger("change");

        $('#txtChangeAmount').val(fnFormatNumber(objTransactionMasterInfo.changeAmount, 2));
        $('#txtReceiptAmount').val(fnFormatNumber(objTransactionMasterInfo.receiptAmount, 2));
        $('#txtReceiptAmountDol').val(fnFormatNumber(objTransactionMasterInfo.receiptAmountDol, 2));
        $('#txtReceiptAmountTarjeta').val(fnFormatNumber(objTransactionMasterInfo.receiptAmountCard, 2));
        $('#txtReceiptAmountTarjetaDol').val(fnFormatNumber(objTransactionMasterInfo.receiptAmountCardDol, 2));
        $('#txtReceiptAmountBank').val(fnFormatNumber(objTransactionMasterInfo.receiptAmountBank, 2));
        $('#txtReceiptAmountBankDol').val(fnFormatNumber(objTransactionMasterInfo.receiptAmountBankDol, 2));
        $('#txtReceiptAmountPoint').val(fnFormatNumber(objTransactionMasterInfo.receiptAmountPoint, 2));

        $('#txtReceiptAmountBank_Reference').val(objTransactionMasterInfo.receiptAmountBankReference);
        $('#txtReceiptAmountBankDol_Reference').val(objTransactionMasterInfo.receiptAmountBankDolReference);
        $('#txtReceiptAmountTarjeta_Reference').val(objTransactionMasterInfo.receiptAmountCardBankReference);
        $('#txtReceiptAmountTarjetaDol_Reference').val(objTransactionMasterInfo.receiptAmountCardBankDolReference);

        objRenderInit = false;
        if(varPermisosNoPermitirEliminarProductosFactura && isAdmin !== "1"){
            $('.btnMenus').addClass('hidden');
            $('#btnDelete').addClass('hidden');
            $('#btnDeleteItem').addClass('hidden');
        }

		if(data.objParameterInvoiceButtomPrinterFidLocalPaymentAndAmortization === "true"){	
			$("#btnAceptarDialogPrinterV2AceptarTabla").removeClass("hidden");
		}
		if(objParameterPrinterDirectAndPreview === 'true' ){
			$("#btnAceptarDialogPrinterV2AceptarDirect").removeClass("hidden");
		}
		$('#btnLinkPayment').css('display','block');
        $('.showPanelEdicion').css('display','block');
        $('#rowBotoneraFacturaFila5').css('display','block');
        $('#registrarFacturaNueva').css('display','none');

		if(varParameterShowComandoDeCocina === "true"){
            $('.showComandoDeCocina').show();
        }else{
            $('.showComandoDeCocina').hide();
        }

        if(objParameterINVOICE_OPEN_CASH_WHEN_PRINTER_INVOICE==="false"){
            $('#divPanelOpenCash').css('display','block');
        }else{
            $('#divPanelOpenCash').css('display', 'none');
        }

        <?php echo getBehavio($company->type, 'app_invoice_billing', 'btnFooter','') ?>

		if(objTransactionMasterReferences.reference2 === "1"){
            $('#txtCheckApplyExoneracionValue').val(1);
            fnSetCheckBoxValue($('#txtCheckApplyExoneracion'),true);
		}else{
            fnSetCheckBoxValue($('#txtCheckApplyExoneracion'),false);
			$('#txtCheckApplyExoneracionValue').val(0);
		}

		if(objTransactionMasterDetailCredit.reference2 === "1"){
			$('#txtCheckReportSinRiesgoValue').val(1);
            fnSetCheckBoxValue($('#txtCheckReportSinRiesgo'),true);
		}else{
			$('#txtCheckReportSinRiesgoValue').val(0);
            fnSetCheckBoxValue($('#txtCheckReportSinRiesgo'),false);
		}

		$('#txtCheckDeEfectivoValue').val(0);
        fnSetCheckBoxValue($('#txtCheckDeEfectivo'),false);

		cerrarModal('ModalCargandoDatos');
    }

	function fnGetConcept(conceptItemID,nameConcept){
		
		
		//Recalculoa el concepto via AJAX 2023-12-05 Inicio		
		var x_			= jLinq.from(objTableDetail.fnGetData()).where(function(obj){ return obj[2] === conceptItemID ;}).select();
		var objind_ 	= fnGetPosition(x_,objTableDetail.fnGetData());
		
		
		//Obtener el concepto de la base de datos del navegador y calcular nuevamente
		obtenerDataDBProductoArray(
			"objListaProductosConceptosX001",
			"componentItemID",conceptItemID,"none",{},
			function(e){ 
					
					var objConcepto = e;						
					var exoneracion = $("#txtCheckApplyExoneracionValue").val();
					
					if(exoneracion === "0")
					{
						objConcepto1 	= jLinq.from(objConcepto).where(function(obj){ return (obj.name === "IVA"); }).select();
						if( objConcepto1.length > 0 )
						{
							
							objTableDetail.fnUpdate( fnFormatNumber(objConcepto1[0].valueOut,2), objind_, 9 );			
						}
						objConcepto2 	= jLinq.from(objConcepto).where(function(obj){ return (obj.name === "TAX_SERVICES"); }).select();
						if( objConcepto2.length > 0 )
						{
							
							objTableDetail.fnUpdate( fnFormatNumber(objConcepto2[0].valueOut,2), objind_, 17 );			
						}
					}	
					else 
					{
						objTableDetail.fnUpdate( 0, objind_, 9 );		//IVA	
						objTableDetail.fnUpdate( 0, objind_, 17 );		//TAX_SERVICES
					}
					fnRecalculateDetail(true,"");
					
			}
		);		
	}
	
	
	
	function refreschChecked()
	{
		
		if(varUseMobile === "0")
		{
			var cantidaRow = $(".txtItemSelected").length;
			for(var i = 0 ; i < cantidaRow; i++)
			{
				var x = $($(".txtItemSelected")[i]).find("a").length;
				if(x === 0 )
					$($(".txtItemSelected")[i]).select2();
			}
		}
		
		
		$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect, .skuStyleNormal').uniform();
		if(varUseMobile === "1")
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
			
			mostrarModal("ModalIrMesaDocumentDialogCustom");
			$("#txtMesaOcupada").val(value);
			$(".modal-backdrop.fade.in").removeClass("modal-backdrop");
		}else{
            $("#mySidebarFactura").css("width","100%");
            $("#mySidebarMesa").css("width","0%");
			$("#mySidebarMesa").addClass("hidden");
        }
	}
	
	function fnSelectCellCategoryInventory(cell) 
	{		
		var inventoryCategoryID = $(cell).data("value");

		$(".custom-table-container-categorias").hide();
		$(".custom-table-container-inventory").show();

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
		
		if(codigoProducto === "0" )
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
					filterResultArray[21] 	= 1;//filterResult.Cantidad;
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
			$("#txtReceiptAmountDol").val("0");
			$("#txtChangeAmount").val("0");
			$("#txtReceiptAmountBank").val("0");
			$("#txtReceiptAmountPoint").val("0");
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
			
			
			
			objTableDetail.fnUpdate( cantidadTemporal, i, 6 );
			objTableDetail.fnUpdate( priceTemporal, i, 7 );
			objTableDetail.fnUpdate(skuValueDescription , i, 13 );
		
			cantidad 	= parseFloat(NSSystemDetailInvoice[i][6]);
			precio 		= parseFloat(NSSystemDetailInvoice[i][7]);
			iva 		= parseFloat(NSSystemDetailInvoice[i][9]);
			taxServices	= parseFloat(NSSystemDetailInvoice[i][17]);
			
			
			
			subtotal    = precio * cantidad;
			iva 		= (precio * cantidad) * iva;
			taxServices = (precio * cantidad) * taxServices;
			total 		= iva + taxServices + subtotal ;
			
			
			cantidadGeneral 	= cantidadGeneral + cantidad;
			precioGeneral 		= precioGeneral + precio;
			ivaGeneral 			= ivaGeneral + iva;	
			serviceGeneral		= serviceGeneral + taxServices;			
			subtotalGeneral 	= subtotalGeneral + subtotal;
			totalGeneral 		= totalGeneral + total;
			
			
			objTableDetail.fnUpdate( fnFormatNumber(subtotal,2), i, 8 );
		}
		
        let descuento 	= subtotalGeneral * (porcentajeDescuento / 100);
        totalGeneral 	= subtotalGeneral + serviceGeneral + ivaGeneral - descuento;
        
		$("#txtSubTotal").val(fnFormatNumber(subtotalGeneral,2));
		$('#txtDescuento').val(fnFormatNumber(descuento, 2));
		$("#txtIva").val(fnFormatNumber(ivaGeneral,2));
		$("#txtServices").val(fnFormatNumber(serviceGeneral,2));
		$("#txtTotal").val(fnFormatNumber(totalGeneral,2));
		$("#txtTotalAlternativo").text(fnFormatNumber(totalGeneral,2));

		
	
		//Si es de credito que la factura no supere la linea de credito
		var causalSelect 				= $("#txtCausalID").val();
		var customerCreditLineID 		= $("#txtCustomerCreditLineID").val();
		var objCustomerCreditLine 		= jLinq.from(objListCustomerCreditLine).where(function(obj){ return obj.customerCreditLineID == customerCreditLineID; }).select();
		var causalCredit 				= objCausalTypeCredit.value.split(",");
		var invoiceTypeCredit 			= false;
		
		
		//Obtener si la factura es al credito						
		for(var i=0;i<causalCredit.length;i++){
			if(causalCredit[i] === causalSelect){
				invoiceTypeCredit = true;
			}
		}
		
		
		if(invoiceTypeCredit === true){
			$("#txtReceiptAmount").val("0.00");
		}
		else{
			$("#txtReceiptAmount").val(fnFormatNumber(totalGeneral,2));
		}
        if (clearRecibo){
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
		
		console.info("fnFillListaProductos success data");
		var objListaProductos 			= data.objGridView;		
		
		
		removeDataDB("objListaProductosX001");		
		addDataDBArray("objListaProductosX001",objListaProductos);		
		fnObtenerListadoItemConcept();
	
	}
	
	
	function fnFillListaItemConcept(data)
	{		
		
		console.info("fnFillListaItemConcept success data");
		removeDataDB("objListaProductosConceptosX001");		
		addDataDBArray("objListaProductosConceptosX001",data.objGridView);
		cerrarModal('ModalActualizandoCatalogo'); 
	
	}
	
	function fnFillListaCreditLine(data)
	{		
		console.info("fnFillListaCreditLine success data");
		removeDataDB("objListaCustomerCreditLineX001");		
		addDataDBArray("objListaCustomerCreditLineX001",data.objListCustomerCreditLine);		
	
	}
	
	
	function fnCompleteGetCustomerCreditLine(data)
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
	
					
	function fnEnviarFactura()
	{
		mostarModalPersonalizado('GUARDANDO DATOS DE FACTURA, POR FAVOR ESPERE...');
		$("#form-new-invoice").attr("method","POST");
        if (loadEdicion){
            $( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_invoice_billing/save/edit");
        }else{
            $( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_invoice_billing/save/new");
        }

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

		/*TIPO PRECIO 1 -- 154 -- PUBLICO */
		$.ajax(
		{									
			cache       : false,
			dataType    : 'json',
			type        : 'GET',																				
			url  		: "<?= base_url(); ?>/app_invoice_api/getViewApi/<?= $objComponentItem->componentID; ?>/onCompleteNewItem/SELECCIONAR_ITEM_BILLING_BACKGROUND/"+encodeURI('{"warehouseID"|"'+ $("#txtWarehouseID").val() +'"{}"listPriceID"|"<?= $objListPrice->listPriceID; ?>"{}"typePriceID"|"'+154+'"}'),		
			success		: fnFillListaProductos,
			error:function(xhr,data)
			{	
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
				cerrarModal('ModalActualizandoCatalogo'); 
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
			success		: function(data){
				fnRenderLineaCredit(data.objListCustomerCreditLine,data.objCausalTypeCredit);
			},
			error:function(xhr,data){	
				console.info("complete data error");													
				fnShowNotification("Error 505","error");
			},
            complete    : function () {
                cerrarModal('ModalCargandoDatos');
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
					if(existe == 0 && (currencyID == currencyTemp) &&  (warehouseIDTemp == warehouseID) )
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
	
	
	
	function fnCreateTableSearchProductos(codigoBuscar){
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
	
	function fnRenderLineaCredit(listCustomerCreditLine,causalTypeCredit)
	{
		objListCustomerCreditLine 	= listCustomerCreditLine;
		objCausalTypeCredit 		= causalTypeCredit;
		$("#divTipoFactura").removeClass("<?= getBehavio($company->type,"app_invoice_billing","divTxtCausalIDScript","hidden"); ?>");
		$("#txtCustomerCreditLineID").html("");
		$("#txtCustomerCreditLineID").val("");
		
		if(objListCustomerCreditLine != null)
		for(var i = 0; i< objListCustomerCreditLine.length;i++){
			if(i===0 && varCustomerCrediLineID === 0){
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
				if( ($(obj).attr("value") === causalIDCredit) && (objListCustomerCreditLine.length > 0))
					$("#txtCausalID option[value="+causalIDCredit+"]").removeAttr("disabled");
				else if( ($(obj).attr("value") === causalIDCredit) && (objListCustomerCreditLine.length === 0 ))
					$("#txtCausalID option[value="+causalIDCredit+"]").attr("disabled","true");
				else
					$("#txtCausalID option[value="+causalIDCredit+"]").removeAttr("disabled");
			}
		});

		//Refresh Control
		if(varUseMobile !== "1")
		{
			$("#txtCustomerCreditLineID").select2();
			$("#txtCausalID").select2();
			$("#txtPeriodPay").select2();
		}
		
		refreschChecked();	
		if(varParameterINVOICE_BILLING_SELECTITEM === "true")
		{
			fnAddRowSelected(); 
		}			
		onCompletePantalla();
	}
	
	function openDataBaseAndCreate(bInicializar,obtenerRegistroDelServer) {
		var indexDB 	= window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
		const request 	= indexDB.open('MyDatabasePosMe', 2);
		
		request.onsuccess = (e) => 
		{
			
			// Se crea la conexion
			db 				   = request.result;
			console.info('Database success');		
			if(obtenerRegistroDelServer)
			{
				mostrarModal("ModalActualizandoCatalogo");
				fnObtenerListadoProductos();				
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
			function(e,u){    
				
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

    function obtenerDataDBProductoArray(varTable, varColumn, varValue, valueComando, varDataExt, varFunction) {
        const requestStore = db.transaction(varTable, 'readwrite').objectStore(varTable);

        let request;
        let varIndex;

        if (varColumn === "all") {
            request = requestStore.getAll();
        } else {
            varIndex 	= requestStore.index(varColumn);
            request 	= varIndex.getAll(varValue);
        }

        request.onsuccess = function() {
            try {
                if (valueComando !== "none") {
                    varDataExt[valueComando] = request.result;
                    varFunction(varDataExt, varDataExt);
                } else {
                    varFunction(request.result, varDataExt);
                }
            } catch (ex) {
                console.error("Error in onsuccess:", ex);
            }
        }

        request.onerror = function(err) {
            console.error("Error in request:", err);
            if (err.target && err.target.error) {
                console.error("Error message:", err.target.error.message);
            }
        }
    }
	
	
	function addDataDB(varTable,varDatos){
		const transaction 		= db.transaction(varTable, 'readwrite');		
		transaction.oncomplete 	= function(event) {
			//...
		};
		
		transaction.onerror 	= function(event) {
		  //...
		};
		
		const objectStore 	= transaction.objectStore(varTable);
		
		// Se agrega un nuevo estudiante
		const request 		= objectStore.add({"name":varDatos});
		
		request.onsuccess 	= ()=> {
			// request.result contiene el key del objeto agregado
			console.log('success');
		}
		
		request.onerror = (err)=> {
			console.log('error');
		}
	}
	
	
	function removeDataDB(varTable){
		const request 		= db.transaction(varTable, 'readwrite')
							  .objectStore(varTable)
							  .clear();

		request.onsuccess 	= ()=> {
			console.info("success");
		}

		request.onerror = (err)=> {
			console.log('error');
		}
	}

	function fnAceptarModalInfoProducto(){
        let precioRecomendado 	= $('#selectPrecio').val();
        let vendedor 			= $('#selectVendedor').val();
        let serie 				= $('#txtSerieProducto').val();
        let referencia 			= $('#txtReferenciaProducto').val();
        objTableDetail.fnUpdate(precioRecomendado, selectedFilaInfoProducto, 7, false);
		objTableDetail.fnUpdate(vendedor, selectedFilaInfoProducto, 19, false);
		objTableDetail.fnUpdate(serie, selectedFilaInfoProducto, 20, false);
		objTableDetail.fnUpdate(referencia, selectedFilaInfoProducto, 21, false);
		refreschChecked();
        fnRecalculateDetail(true,"txtPrice");
        cerrarModal('ModalInfoProducto');
    }

	function fnAceptarModalBackToList()
	{
		cerrarModal('ModalBackToList');
		mostarModalPersonalizado('Regresando a la lista principal...')
		window.location.href = '<?php echo base_url(); ?>/app_invoice_billing/index'; 	
	}
	
	function fnAceptarModalDeleteInvoice(){
		cerrarModal('ModalDeleteInvoice');
		if(objTransactionMaster && objTransactionMaster.transactionMasterID > 0){
			mostarModalPersonalizado('Anulando factura, por favor espere...');
			$.ajax({									
				cache       : false,
				dataType    : 'json',
				type        : 'POST',
				url  		: "<?php echo base_url(); ?>/app_invoice_billing/delete",					
				data 		: {
					companyID 			: objTransactionMaster.companyID, 
					transactionID 		: objTransactionMaster.transactionID,
					transactionMasterID : objTransactionMaster.transactionMasterID  
				},
				success:function(data){
					console.info("complete delete " + data.error);
					if(data.error){
						Toast.fire({
							icon 	: 'error',
							title	: data.message
						});
					}
					else{
						ToastSuccess.fire({
							icon  : 'success',
							title : 'Factura eliminada correctamente'
						});
						fnClearForm();
					}
				},
				error:function(xhr,data){	
					console.info("complete delete error");
					fnShowNotification("Error 505","error");
				},
				complete: function(){
					cerrarModal('ModalCargandoDatos');
				}
			});
		}else{
			Toast.fire({
				icon	: 'error',
				title	:'No hay factura seleccionada'
			});
		}
	}
	
	function onCompleteNewItemPopPub (ee,uu,zz){

        var data		 = {};
        var dataResponse = [];
        data			 = ee;
		
        if(data.length < 0) return;
        for(let i=0; i < data.length; i++)
		{
			//si no hay ningun string input o select 
			//dejar por defecto cantidad igual a 1
            let element 				= data[i];
            const containsInputOrSelect = element.some(item => {
                if (typeof item === 'string') {
                    return item.includes('<input') || item.includes('<select');
                }
                return false;
            });
			
            if (containsInputOrSelect === false)
			{
                element[8]   = 1 ;//Cantidad
            }
			
            dataResponse[0]  = element[0];
            dataResponse[1]  = element[0];
            dataResponse[2]  = element[0];
            dataResponse[3]  = element[0];
            dataResponse[4]  = element[0];
            dataResponse[5]  = element[0];//itemID
            dataResponse[6]  = element[0];
            dataResponse[7]  = element[0];
            dataResponse[8]  = element[0];
            dataResponse[9]  = element[0];
            dataResponse[10] = element[0];
            dataResponse[11] = element[0];
            dataResponse[12] = element[0];
            dataResponse[13] = element[0];
            dataResponse[14] = element[0];
            dataResponse[15] = element[0];
            dataResponse[16] = element[0];
            dataResponse[17] = element[4];//Codigo
            dataResponse[18] = element[5];//Nombre
            dataResponse[19] = element[0];
            dataResponse[20] = element[7];//Unidad de medida
            dataResponse[21] = element[8];//Cantidad
            dataResponse[22] = element[9];//Precio
            dataResponse[23] = element[1];//UnitMeasuereID
            dataResponse[24] = element[10];//Description
            dataResponse[25] = element[2];//Precio2
            dataResponse[26] = element[3];//Precio3

            onCompleteNewItem(dataResponse, true);
        }
			
	}
	
		 
	
	
	
		

	$(document).ready(function()
	{
		openDataBaseAndCreate(true,false);	
		if(transactionMasterID !== 0)
		{
			loadEdicion = true;
		}
		
		$('#divLoandingCustom').css('display','none');
		$('#btnLinkPayment').css('display','none');
		$('#rowBotoneraFacturaFila5').css('display','none');
		$('.showComandoDeCocina').css('display', 'none');
		$('.showPanelEdicion').css('display', 'none');
		$('#registrarFacturaNueva').css('display','block');
		$('#showCommandBar').css('display', 'none');
		if(objParameterINVOICE_OPEN_CASH_WHEN_PRINTER_INVOICE==="false"){
            $('#divPanelOpenCash').css('display','block');
        }else{
            $('#divPanelOpenCash').css('display', 'none');
        }
		$('#txtClaveOpenCash').css({
			'webkitTextSecurity': 'disc', 		// Para WebKit browsers
			'textSecurity'		: 'disc'        // Para otros browsers que lo soporten
		});

		$grid = $('.custom-table-container-inventory .row').isotope({
			itemSelector	: '.item-producto',
			layoutMode		: 'fitRows',
			filter			: '.item-producto-back'
		});

		//codigo para cuando carga la pagina y mostrar la zona
		if(varParameterRestaurante == "true")
		{
			$("#mySidebarZona").css("width","100%");
			$("#mySidebarZona").removeClass("hidden");
		}

		$(".custom-table-container-inventory").hide();
		var zonaDefault = $("#txtZoneID").val();
		$(".custom-table-mesas").find("td").addClass("hidden");
		$(".custom-table-mesas").find('td[data-parent="'+zonaDefault+'"]').removeClass("hidden");

		var objectParameterButtomsBack		= {};
		$('#txtDate').datepicker({format:"yyyy-mm-dd"});
		$('#txtDate').val(moment().format("YYYY-MM-DD"));	
		$("#txtDate").datepicker("update");
		$('#txtNextVisit').datepicker({format:"yyyy-mm-dd"});
		 
		
		$('#txtDateFirst').datepicker({format:"yyyy-mm-dd"});						 
		$('#txtDateFirst').val(moment().add('days', 0).format("YYYY-MM-DD"));			 
		$("#txtDateFirst").datepicker("update");
	
		 
		 heigthTop							= 300;
		 //Incializar Focos en el codigo de barra
		if(varParameterScanerProducto != "false" && varUseMobile == "0" )
		{
			$("#txtScanerCodigo").focus();			
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
		
		
		//Guardar y regresar a la lista, de una sola ves si
		//Si es auto aplicar y es regresar a la lista y no es impresion directa 
		//Y no hay impresion por cada factura			
		if (
			
			(
				(varParameterInvoiceBillingPrinterDirect == "false"   && varAutoAPlicar == "true" ) || 
				(varParameterInvoiceBillingPrinterDirect == "false"   && varParameterRegresarAListaDespuesDeGuardar == "true" ) 
			) && 
			objCompanyParameter_Key_INVOICE_PRINT_BY_INVOICE == "false" 
			
		)
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
			});
		}
		
	
		objTableDetail = $("#tb_transaction_master_detail").dataTable({
			"bPaginate"		: false,
			"bFilter"		: false,
			"bSort"			: false,
			"bInfo"			: false,				
			"oLanguage"		: {
				"sEmptyTable": "", // No mostrar texto cuando está vacía
				"sZeroRecords": "",
			},
			"bAutoWidth"	: false,
			"aoColumnDefs": [ 
						{
							"aTargets"		: [ 0 ],//checked
							"sWidth"		: "50px",
							"sClass"		: "td-center",
							"mRender"		: function ( data, type, full ) {
								var ocultarBoton="";
								if(varPermisosNoPermitirEliminarProductosFactura && isAdmin !== "1"){
									ocultarBoton="hidden";
								}

								if (data == false)
								return '<input type="checkbox"  class="classCheckedDetail '+ocultarBoton+'"  value="0" ></span>';
								else
								return '<input type="checkbox"  class="classCheckedDetail '+ocultarBoton+'" checked="checked" value="0" ></span>';
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
								return '<input type="text"  class="col-lg-12 <?= $useMobile == "1" ? 'hidden' : '' ?>" style="text-align:left; <?= $useMobile == "1" ? 'width: 100%;' : '' ?>" value="'+data+'" readonly="true" />';
							}
						},
						{
							"aTargets"		: [ columnasTableDetail.descripcion ],//descripcion
							"sWidth"		: "250px",
							"mRender"		: function ( data, type, full ) 
							{
							
								
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
								let strFiled = "";
								if (varUseMobile === "1"){
									strFiled        += '<label class="col-lg-12" style="text-align:right; <?= $useMobile == "1" ? 'width: 100%;' : '' ?>">Descripción: '+full[4]+'</label>';
									strFiled 		+= '<input type="hidden" name="txtTransactionDetailName[]" id="txtTransactionDetailName'+full[2]+'"  value="'+full[4]+'" '+NameStatus+' />';
								}else{
									strFiled 		+= '<input type="text" name="txtTransactionDetailName[]" id="txtTransactionDetailName'+full[2]+'"  class="col-lg-12 '+classHiddenTex+'" style="text-align:left; <?= $useMobile == "1" ? 'width: 100%;' : '' ?>" value="'+full[4]+'" '+NameStatus+' />';
								}
								let strFiledSelecte = "<select  name='txtItemSelected' class='<?php echo ($useMobile == "1" ? "" : "select2"); ?> txtItemSelected "+classHiddenSelect+" ' >";
								strFiledSelecte		= strFiledSelecte+"<option value='"+full[2]+"' selected data-itemid='"+full[2]+"' data-codigo='"+full[3]+"' data-name='"+full[4].replace("'","").replace("'","") +"' data-unidadmedida='"+full[5]+"' data-cantidad='"+full[6]+"' data-precio='"+full[7]+"' data-barra='"+full[3]+"'  data-description='"+full[4].replace("'","").replace("'","") + "'    >"+ full[4].replace("'","").replace("'","")  +"</option>";									
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
									sel 						= '<select name="txtSku[]" id="txtSku'+full[2]+'" class="txtSku col-lg-12 skuStyleNormal <?= $useMobile == "1" ? 'hidden' : '' ?>" style="<?= $useMobile == "1" ? 'width: 100%;' : '' ?>" >';
									
									if(varUseMobile == "1")
										espacio = "";		
									
									
									sel = sel + '<option value="'+full[5]+'" data-skuv="1" data-skupriceunitary="'+full[7]+'"   selected style="font-size:200%" data-description="'+full[13]+'" >'+full[13]+espacio+'</option>';
									sel = sel + '</select>';																			
									return sel;
										
							}
						},
						{
							"aTargets"		: [ 6 ],//Cantidad
							"sWidth"		: objParameterINVOICE_SHOW_FIELD_PESO == "true" ? "150px" : "250px",
							"mRender"		: function ( data, type, full ) {
								if (varPermisosNoPermitirEliminarProductosFactura && isAdmin !== "1"){
									str = '<input type="text" class="col-lg-12 txtQuantity txt-numeric" id="txtQuantityRow'+full[2]+'"  value="'+data+'" name="txtQuantity[]" style="text-align:right; <?= $useMobile == "1" ? 'width: 100%;' : '' ?>" autocomplete="off" readonly />';
								}else{
									str = '<input type="text" class="col-lg-12 txtQuantity txt-numeric" id="txtQuantityRow'+full[2]+'"  value="'+data+'" name="txtQuantity[]" style="text-align:right; <?= $useMobile == "1" ? 'width: 100%;' : '' ?>" autocomplete="off" />';
								}
								if (varUseMobile == "1")
								str = str + " <span class='badge badge-inverse' >Cantidad</span>";
						
								return str;
							}
						},
						{
							"aTargets"		: [ 7 ],//Precio
							"sWidth"		: "250px",
							"mRender"		: function ( data, type, full ) {
								var str =  '<input type="text" class="col-lg-12 txtPrice txt-numeric"   id="txtPriceRow'+full[2]+'"   '+PriceStatus+'  value="'+data+'" name="txtPrice[]" style="text-align:right; <?= $useMobile == "1" ? 'width: 100%;' : '' ?>" autocomplete="off" />';
								if (varUseMobile == "1")
								str = str + " <span class='badge badge-inverse' >Precio</span>";
								return str;
							}
						},
						{
							"aTargets"		: [ 8 ],//Total
							"sWidth"		: "250px",
							"mRender"		: function ( data, type, full ) {
								let str;
								if (varUseMobile === "1"){
									str = '<input type="hidden" name="txtSubTotal[]" value="'+data+'" />';
								}else{
									str = '<input type="text" class="col-lg-12 txtSubTotal" readonly value="'+data+'" name="txtSubTotal[]" style="text-align:right; <?= $useMobile == "1" ? 'width: 100%;' : '' ?>" autocomplete="off" />';
								}

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
								
								
											
									//publico
									var objProductoPrecio1 	= full[7];
									//por mayor
									var objProductoPrecio2 	= full[14];
									//credito
									var objProductoPrecio3 	= full[15];
									
									
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
									var ocultarBoton="";
									if(varPermisosNoPermitirEliminarProductosFactura && isAdmin !== "1"){
										ocultarBoton="hidden";
									}

									str    	= str + '' +
									'<button type="button" class="btn btn-primary btnMenus '+ ocultarBoton +'"><span class="icon16 i-minus"></span> </button>';
									
									str    	= str + '' + 
									'<button type="button" class="btn btn-primary btnPlus"><span class="icon16 i-plus"></span> </button>';
									
									
									
									ocultarBoton="<?= getBehavio($company->type, 'app_invoice_billing','divBtnPrecios','')?>";
									str     = str+'<button type="button" class="btn btn-success btnInfoProducto '+ocultarBoton+' " data-precio1="'+objProductoPrecio1+'" data-precio2="'+objProductoPrecio2+'" data-precio3="'+objProductoPrecio3+'"><i class="icon16 i-info"></i></button>';
									str		= str+'</div>';
									
									return str;
											
										
									
								
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
							"aTargets"		: [ columnasTableDetail.precio2 ],//Precio2
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
							"aTargets"		: [ columnasTableDetail.precio3 ],//Precio3
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
						},
						{
							"aTargets"		: [ 18 ],//Peso
							"bVisible"		: true,
							"sClass"		: objParameterINVOICE_SHOW_FIELD_PESO == "true" ? "" : "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailLote" value="'+data+'" name="txtDetailLote[]" style="text-align:right" />';
							}
						},
						{
							"aTargets"		: [ 19 ],//VENDEDOR SELECCIONADO DEL MODAL
							"bVisible"		: true,
							"sClass"		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtInfoVendedor" value="'+data+'" name="txtInfoVendedor[]" style="text-align:right" />';
							}
						},
						{
							"aTargets"		: [ 20 ],//SERIE INGRESADO DESDE EL MODAL
							"bVisible"		: true,
							"sClass"		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtInfoSerie" value="'+data+'" name="txtInfoSerie[]" style="text-align:right" />';
							}
						},
						{
							"aTargets"		: [ 21 ],//REFERENCIA INGRESADO DESDE EL MODAL
							"bVisible"		: true,
							"sClass"		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtInfoReferencia" value="'+data+'" name="txtInfoReferencia[]" style="text-align:right" />';
							}
						},
						{
							"aTargets"		: [ columnasTableDetail.precio1 ],//Precio1
							"bVisible"		: true,
							"sClass"		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtItemPrecio1[]" />';
							}
						},

			]							
		});
					
		//Renderizar combobox de las lineas de credito			
		fnRenderLineaCredit(objListCustomerCreditLine,objCausalTypeCredit);	

		//Mandar a imprimr la factura
		//Por cada factura nueva
		//Si que la impresiona sea directa
		if(
			varParameterInvoiceBillingPrinterDirect == 'true' && 
			varParameterImprimirPorCadaFactura == "true" && 
			varParameterInvoiceBillingPrinterDataLocal != "" 
		)
		{
			mostrarModal("ModalPrinterDocumentDialogCustom");
			$(".modal-backdrop.fade.in").removeClass("modal-backdrop");
		}
		
		
		//Mandar a imprimr la factura
		//Por cada factura
		//Con el cuadro de dialogo
		//Con factura en el server 
		if(
			varParameterInvoiceBillingPrinterDirect == 'false' && 
			varParameterImprimirPorCadaFactura == "true"  && 
			varTransactionMasterIDToPrinter != "" 
		)
		{
			mostrarModal("ModalOpcionesImpresion");
			$(".modal-backdrop.fade.in").removeClass("modal-backdrop");
		}

		if(varPermisosNoPermitirEliminarProductosFactura && isAdmin !== "1"){
			$('.btnMenus').addClass('hidden');
			$('#btnDelete').addClass('hidden');
			$('#btnDeleteItem').addClass('hidden');
		}

        if( $('#txtCheckApplyExoneracionValue').val() === "1"  )
        {
            fnSetCheckBoxValue($('#txtCheckApplyExoneracion'),true);
        }
        else
        {
            fnSetCheckBoxValue($('#txtCheckApplyExoneracion'),false);

        }
        if( $('#txtCheckDeEfectivoValue').val() === "1"  )
        {
            fnSetCheckBoxValue($('#txtCheckDeEfectivoValue'),true);
        }
        else
        {
            fnSetCheckBoxValue($('#txtCheckDeEfectivoValue'),false);

        }
        if( $('#txtCheckReportSinRiesgoValue').val() === "1"  )
        {
            fnSetCheckBoxValue($('#txtCheckReportSinRiesgoValue'),true);
        }
        else
        {
            fnSetCheckBoxValue($('#txtCheckReportSinRiesgoValue'),false);
        }
		
		
		if(transactionMasterID !== 0){
			loadEdicion 	= true;
			let url 		= varBaseUrl + '/app_invoice_billing/edit/' + <?= $companyID ?> + '/' + transactionID + '/' + transactionMasterID + '/' + $("#txtCodigoMesero").val();
			const resultado = $.ajax({
				url: url
			});
			
			resultado.then(function(response) {
				fnClearForm();
				fnUpdateInvoiceView(response.data);
			});
		}
		else 
		{
			cerrarModal("ModalCargandoDatos");
		}		
	});
	
	

</script>

