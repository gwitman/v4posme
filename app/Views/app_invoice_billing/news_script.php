<!-- ./ page heading -->
<script>
    var heigthTop							= 0;
	var objTableDetail 						= {};
    var tmpData 				            = [];
    var tmpInfoClient			            = 0;
	var scrollPosition						= 0;
    var warehouseID 						= $("#txtWarehouseID").val();
	let transactionID						=	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						  <?php echo $transactionID ?>;
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
	var objListInventoryItemsRestaurant     = <?= json_encode($objListInventoryItemsRestaurant, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)?>;
	var cargaCompletada						= false;
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
	var objParameterEsRestaurante							= '<?php echo $objParameterEsResrarante; ?>'; 


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
	let objListMesa 				= <?= json_encode($objListMesa); ?>;
	let varCustomerCrediLineID		= 0;
    let objRenderInit				= true;
	let loadEdicion 				= false;
	let transactionMasterID			=	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			 	                       			  <?php echo $transactionMasterID ?>;
	let codigoMesero				= '<?php echo $codigoMesero ?>';
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
    let objParameterINVOICE_OPEN_CASH_WHEN_PRINTER_INVOICE = '<?php echo $objParameterINVOICE_OPEN_CASH_WHEN_PRINTER_INVOICE ?>';
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
		descripcion 			: 4,
		cantidad				: 6,
		precio					: 7,
		total					: 8,
		iva						: 9,
		skuQuantityBySku 		: 10,
		skuFormatoDescription 	: 13,
        precio2 				: 14,
        precio3 				: 15,
		taxServices				: 17,
		precio1 				: 22,
		valueSku				: 23,
		ratioSku				: 24,
		discount				: 25,
		comisionPosBanck		: 26
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

	$('#txtReceiptAmountTarjeta_BankID').on('change', function(){
		let value = $('#txtReceiptAmountTarjeta_BankID').find(':selected').data('comision-pos');
		fnRecalcularMontoComision(value);
	});
	$('#txtReceiptAmountTarjetaDol_BankID').on('change', function(){
		let value = $('#txtReceiptAmountTarjetaDol_BankID').find(':selected').data('comision-pos');
		fnRecalcularMontoComision(value);
	});
	$('#txtReceiptAmountBank_BankID').on('change', function(){
		let value = $('#txtReceiptAmountBank_BankID').find(':selected').data('comision-pos');
		fnRecalcularMontoComision(value);
	});
	$('#txtReceiptAmountBankDol_BankID').on('change', function(){
		let value = $('#txtReceiptAmountBankDol_BankID').find(':selected').data('comision-pos');
		fnRecalcularMontoComision(value);
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
            fnGetConcept(listRow[i][2],"ALL");
            i++;
        }
		
		mostarModalPersonalizado('Calculando conceptos');
		setTimeout(() => {
		  cerrarModal('ModalCargandoDatos');
		}, (length * 1000) * 0.2 );
		

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
						fnGetConcept(listRow[i][2],"ALL");
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
		var value 		= $("#txtMesaOcupada").val();
		loadEdicion 	= true;
		let url 		= varBaseUrl + '/app_invoice_billing/edit/' + <?php echo $companyID ?> + '/' + transactionID + '/' + transactionMasterID + '/' + $("#txtCodigoMesero").val();
		const resultado = $.ajax({
			url: url
		});

		resultado.then(function(response) {
			fnClearForm();
			fnUpdateInvoiceView(response.data);
		});
		var sidebar = $("#mySidebarMesa");
		sidebar.css("width", "0");
		sidebar.addClass("hidden");
		$("#mySidebarFactura").css("width","100%");
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

	$(document).on("click",".btnPlus",function(){
		let trSelected 	=  $(this).parent().parent().parent();
		var quantity 	= trSelected.find(".txtQuantity").val();
		quantity 	 	= parseFloat(quantity);
		quantity	 	= quantity + 1;
		trSelected.find(".txtQuantity").val(quantity);
		let aPos 		= objTableDetail.fnGetPosition(trSelected[0]);
		objTableDetail.fnUpdate(quantity, aPos, columnasTableDetail.cantidad);
		fnRecalculateDetail(true,"", aPos);
	});

	$(document).on("click",".btnMenus",function(){
		let trSelected 	=  $(this).parent().parent().parent();
		var quantity 	= $(this).parent().parent().parent().find(".txtQuantity").val();
		quantity 	 	= parseFloat(quantity);
		quantity	 	= quantity - 1;
		$(this).parent().parent().parent().find(".txtQuantity").val(quantity);
		let aPos 		= objTableDetail.fnGetPosition(trSelected[0]);
		objTableDetail.fnUpdate(quantity, aPos, columnasTableDetail.cantidad);
		fnRecalculateDetail(true,"", aPos);
	});
	
	$(document).on("focus",".txt-numeric",function(){
		if ( fnFormatNumber( $(this).val()  ) == 0)
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
		 $("#mySidebarFactura").css("width","0");
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
				let encontrado		= false;
				let index			= -1;
				for(let i = 0 ; i < data.length ; i++)
				{

					if(encontrado == true)
					{
						i--;
						index = i;
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
						for(let ii = 0 ; ii < listCodigTmp.length; ii++)
						{
							if(
								fnDeleteCerosIzquierdos(listCodigTmp[ii].toUpperCase()) == fnDeleteCerosIzquierdos(codigoABuscar) &&
								currencyID == currencyTemp  &&
								warehouseID == warehouseIDTemp
							)
							{
								index       = i;
								encontrado 	= true;
								break;
							}
						}
					}



				}

				if(encontrado == true)
				{
					var sumar				= true;
					var filterResult 		= data[index];
					var filterResultArray 	= [];
					filterResultArray[0]  	= filterResult.itemID;
					filterResultArray[1]  	= filterResult.itemID;
					filterResultArray[2]  	= filterResult.itemID;
					filterResultArray[3]  	= filterResult.itemID;
					filterResultArray[4]  	= filterResult.itemID;
					filterResultArray[5]  	= filterResult.itemID;
					filterResultArray[6]  	= filterResult.itemID;
					filterResultArray[7]  	= filterResult.itemID;
					filterResultArray[8]  	= filterResult.itemID;
					filterResultArray[9]  	= filterResult.itemID;
					filterResultArray[10]  	= filterResult.itemID;
					filterResultArray[11]  	= filterResult.itemID;
					filterResultArray[12]  	= filterResult.itemID;
					filterResultArray[13]  	= filterResult.itemID;
					filterResultArray[14]  	= filterResult.itemID;
					filterResultArray[15]  	= filterResult.itemID;
					filterResultArray[16]  	= filterResult.itemID;
					filterResultArray[17] 	= filterResult.Codigo;
					filterResultArray[18] 	= filterResult.Nombre;
					filterResultArray[19] 	= 0;
					filterResultArray[20] 	= filterResult.Medida;
					filterResultArray[21] 	= 1; //filterResult.Cantidad;
					filterResultArray[22] 	= filterResult.Precio;
					filterResultArray[23] 	= filterResult.unitMeasureID;
					filterResultArray[24] 	= filterResult.Descripcion;
					filterResultArray[25] 	= filterResult.Precio2;
					filterResultArray[26] 	= filterResult.Precio3;
					filterResultArray[27] 	= filterResult.Precio;


					//Logica de precio
					if($("#txtTypePriceID").val() == "154" /*precio1*/)
						filterResultArray[22] = filterResultArray[27];
					else if($("#txtTypePriceID").val() == "155" /*precio2*/)
						filterResultArray[22] = filterResultArray[25];
					else /*precio3*/
						filterResultArray[22] = filterResultArray[26];
						
						
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


		var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?php echo $objComponentItem->componentID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_ALL_PAGINATED/true/empty/true/"+url_redirect+"/1/1/"+varParameterCantidadItemPoup+"/";
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
		fnLockPayment();
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
        let precio 					= selectedDataInfoProducto[columnasTableDetail.precio];
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
		if(loadEdicion){
			var valueWorkflow = $(this).data("valueworkflow");
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
		var obtenerRegistrosDelServer = true;
		openDataBaseAndCreate(obtenerRegistrosDelServer);
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
			fnRecalculateDetail(true,"sumarizar");
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
	$('#tb_transaction_master_detail tbody').on('click', '.label-sku', function() {
		var fila 	= $(this).closest('tr')[0];
		var data 	= objTableDetail.fnGetData( fila );
		var label 	= $(this);
		obtenerDataDBProductoArray(
			"objListaProductosSkuX001",
			"all",
			0,
			'all',
			{'itemID': data[2]},
			function(e){
				let itemID 		= e['itemID'];
				let allData 	= e['all'];
				var resultado 	= allData.filter(function(producto) 
				{
					return producto.itemID == itemID;
				});
				
				if (resultado.length > 0) {
					var aPos 					= objTableDetail.fnGetPosition(fila);
					let catalogItemIDSelected 	= data[columnasTableDetail.valueSku];
					let cantidad 				= data[columnasTableDetail.cantidad];
					// Crear el select dinámico
					var currentText 	= label.text().trim();
    				var select 			= $('<select>');
					select.css('width','100%');
					resultado.forEach(function(producto) {
						var selected = (producto.catalogItemID === catalogItemIDSelected) ? 'selected' : '';
						select.append(`<option value="${producto.catalogItemID}" ${selected} data-price="${producto.price}" data-ratio="${producto.value}">${producto.name}</option>`);
					});
					// Reemplazar el label por el select temporalmente
					label.hide();
					label.after(select);

					 // Manejar el evento de selección
					select.on('change', function() 
					{
						let precio = $(this).find('option:selected').data('price');
						if(precio === 0){
							precio = data[columnasTableDetail.precio1];
						}
						let catalogItemID 		= $(this).val();
						let nombreSeleccionado 	= $(this).find('option:selected').text();
						let ratio 				= $(this).find('option:selected').data('ratio');
						let skuQuantityBySku 	= ratio * cantidad;
						if (catalogItemID) {
							objTableDetail.fnUpdate( catalogItemID, aPos, columnasTableDetail.valueSku );
							objTableDetail.fnUpdate( skuQuantityBySku, aPos, columnasTableDetail.skuQuantityBySku );
							objTableDetail.fnUpdate( ratio, aPos, columnasTableDetail.ratioSku );
							objTableDetail.fnUpdate( nombreSeleccionado, aPos, columnasTableDetail.skuFormatoDescription );
							objTableDetail.fnUpdate( precio, aPos, columnasTableDetail.precio );
							$(fila).find('.txtSku').val(nombreSeleccionado);
							fnRecalculateDetail(true, "", aPos);
							// Restaurar el label con el nombre seleccionado
							label.text(nombreSeleccionado).show();
							$(this).remove();
						}
					});

					select.on('blur', function(){
						let nombreSeleccionado = $(this).find('option:selected').text();
						label.text(nombreSeleccionado).show();
						$(this).remove();
					});

					// Forzar el foco en el select para que se abra automáticamente
					select.focus();
				} 
				else 
				{
					console.log("Producto no encontrado");
				}
			}
		);
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
		else 
		{
			var newValue = input.val();
			if (colIndex === columnasTableDetail.descripcion) {
				objTableDetail.fnUpdate(newValue, rowIndex, columnasTableDetail.descripcion, true);
			}
			if(colIndex === columnasTableDetail.cantidad){
				objTableDetail.fnUpdate( newValue, rowIndex, columnasTableDetail.cantidad, true);
				fnRecalculateDetail(true,"", rowIndex);
			}
			if(colIndex === columnasTableDetail.precio){
				objTableDetail.fnUpdate(newValue, rowIndex, columnasTableDetail.precio, true);
				fnRecalculateDetail(true,"", rowIndex);
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
		mostarModalPersonalizado('Cargando datos ....');
		window.open("<?php echo base_url(); ?>/app_cxc_report/document_credit/viewReport/true/documentNumber/"+$("#invoice-num").text(), '_blank');
		cerrarModal('ModalCargandoDatos');
	});

	$("#btnAceptarDialogPrinterV2AceptarDocument").click(function(){
		mostarModalPersonalizado('Cargando impresión, por favor espere...');
		
		var ahora 		= new Date();
		var año 		= ahora.getFullYear();
		var mes 		= String(ahora.getMonth() + 1).padStart(2, '0'); // Mes empieza en 0
		var dia 		= String(ahora.getDate()).padStart(2, '0');
		var hora 		= String(ahora.getHours()).padStart(2, '0');
		var minuto 		= String(ahora.getMinutes()).padStart(2, '0');
		var segundo 	= String(ahora.getSeconds()).padStart(2, '0');
		var fechaHora 	= `${año}${mes}${dia}${hora}${minuto}${segundo}`;
		
		window.open("<?php echo base_url(); ?>/"+varUrlPrinter+"/companyID/2/transactionID/19/transactionMasterID/"+$("#txtTransactionMasterID").val()+"/"+fechaHora, '_blank');
		cerrarModal('ModalCargandoDatos');
		cerrarModal("ModalOpcionesImpresion");
	});
	$("#btnAceptarDialogPrinterV2AceptarDocumentA4").click(function(){
		mostarModalPersonalizado('Cargando impresión, por favor espere...');
		window.open("<?php echo base_url(); ?>/"+varUrlPrinterOpcion2+"/companyID/2/transactionID/19/transactionMasterID/"+$("#txtTransactionMasterID").val(), '_blank');
		cerrarModal('ModalCargandoDatos');
		cerrarModal("ModalOpcionesImpresion");
	});



	$("#btnCloseModalOpcionesImpresion").click(function(){
		cerrarModal("ModalOpcionesImpresion");
	});

	$("#btnAceptarDialogPrinterV2AceptarDirect").click(function(){

		mostarModalPersonalizado('Cargando impresión, por favor espere...');		
		var ahora 		= new Date();
		var año 		= ahora.getFullYear();
		var mes 		= String(ahora.getMonth() + 1).padStart(2, '0'); // Mes empieza en 0
		var dia 		= String(ahora.getDate()).padStart(2, '0');
		var hora 		= String(ahora.getHours()).padStart(2, '0');
		var minuto 		= String(ahora.getMinutes()).padStart(2, '0');
		var segundo 	= String(ahora.getSeconds()).padStart(2, '0');
		var fechaHora 	= `${año}${mes}${dia}${hora}${minuto}${segundo}`;
		
		window.open("<?php echo base_url(); ?>/"+varUrlPrinter+"/companyID/2/transactionID/19/transactionMasterID/"+$("#txtTransactionMasterID").val()+"/direct/true/"+fechaHora, '_blank');
		cerrarModal('ModalCargandoDatos');
		cerrarModal("ModalOpcionesImpresion");


	});

	$('#txtPorcentajeDescuento').on('input', function() {
		 //validar que solo sea numero
		 var valor 		= $(this).val();
		 var expresion 	= /^\d*\.?\d{0,2}$/;
		 if (!expresion.test(valor)) {
			$(this).val(valor.slice(0, -1));
		 }
		 
		 
    });
	$('#txtPorcentajeDescuento').on('blur', function() {	
		 
		 
		 mostarModalPersonalizado('Calculando conceptos');
		 fnRecalculateDetail(true,"");
		 cerrarModal('ModalCargandoDatos');
		 
    });
	$('#txtDescuento').on('blur', function() {	
		 
		 
		 var amountDiscount 	= parseFloat($('#txtDescuento').val());
		 var amountSub 			= parseFloat($("#txtSubTotal").val());
		 var amountIva 			= parseFloat($("#txtIva").val());		 
		 var discountPercentage = (amountDiscount / (amountSub + amountIva)) * 100 ;
		 discountPercentage		= fnFormatNumber(discountPercentage,2);
		 $("#txtPorcentajeDescuento").val(discountPercentage);
		 
		 mostarModalPersonalizado('Calculando conceptos');
		 fnRecalculateDetail(true,"");
		 cerrarModal('ModalCargandoDatos');
		 
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
        let ingresoCordoba 	= fnFormatNumber($("#txtReceiptAmount").val());
        let bancoCordoba 	= fnFormatNumber($("#txtReceiptAmountBank").val());
        let puntoCordoba 	= fnFormatNumber($("#txtReceiptAmountPoint").val());
        let tarjetaCordoba 	= fnFormatNumber($("#txtReceiptAmountTarjeta").val());
        let tarejtaDolares 	= fnFormatNumber($("#txtReceiptAmountTarjetaDol").val());
        let bancoDolares 	= fnFormatNumber($("#txtReceiptAmountBankDol").val());
        let ingresoDol 	    = fnFormatNumber($("#txtReceiptAmountDol").val());
        let tipoCambio 	    = fnFormatNumber($("#txtExchangeRate").val(),6);
        let total 		    = fnFormatNumber($("#txtTotal").val());
        if( currencyId === "1" /*Cordoba*/ )
		{
            resultTotal =  
				(
					parseFloat(ingresoCordoba) +  
					parseFloat(bancoCordoba) + 
					parseFloat(puntoCordoba) + 
					parseFloat(tarjetaCordoba) + 
					( 
						parseFloat(bancoDolares) / 
						parseFloat(tipoCambio) 
					) + 
					( 
						parseFloat(tarejtaDolares) / 
						parseFloat(tipoCambio) 
					)  + 
					(
						parseFloat(ingresoDol)  * 
						((1 / parseFloat(tipoCambio)).toFixed(2) * 1)
					)
				) - parseFloat(total);
		}else if( currencyId === "2" /*dolares*/ )
		{
			resultTotal =  
				(
					parseFloat(ingresoCordoba) +  
					parseFloat(bancoCordoba) + 
					parseFloat(puntoCordoba) + 
					parseFloat(tarjetaCordoba) + 
					( 
						parseFloat(bancoDolares) * 
						parseFloat(tipoCambio) 
					) + 
					( 
						parseFloat(tarejtaDolares) * 
						parseFloat(tipoCambio) 
					)  + 
					(
						parseFloat(ingresoDol) * 
						parseFloat(tipoCambio)
					)
				) - parseFloat(total);
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

			var url	=	"<?php echo base_url(); ?>/"+varParameterInvoiceBillingPrinterDirectBarUrl;
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
		console.info(objResponse);
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
		objRow.price1 					    = fnFormatNumber(objResponse[27],2);
		objRow.skuRatio						= 1;
		objRow.discount						= 0;
		objRow.commisionBank				= 0;

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
			fnGetConcept(objRow.itemID,"ALL");
		}
		//Agregar
		else{
			obtenerDataDBProductoArray(
			"objListaProductosSkuX001",
			"all",
			0,
			'all',
			{'itemID': objRow.itemID},
			function(e){
				let itemID 			= e['itemID'];
				let allData 		= e['all'];
				let priceDefault	= objRow.price;
				
				var resultado 		= allData.filter(function(producto) {
					return producto.itemID == itemID;
				});
				if (resultado.length > 0) {
					resultado.forEach(function(producto) {
						let selected = parseInt(producto.predeterminado, 10);
						if(selected === 1){
							console.info(producto);
							objRow.um				=producto.catalogItemID;
							objRow.umDescription	=producto.name;
							objRow.price 			=producto.price;
							objRow.skuRatio 		=producto.value;
						}
					});
				}
				
				
				if (parseInt(objRow.price) == 0)
				{
					objRow.price = priceDefault;
				}
				
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
					objRow.quantity*objRow.skuRatio,
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
					objRow.price1,
					objRow.um,
					objRow.skuRatio,
					objRow.discount,
					objRow.commisionBank
				]);
				
				fnGetConcept(objRow.itemID,"ALL");
				refreschChecked();
				if(varUseMobile != "1"){
					$("#body_tb_transaction_master_detail tr")[objTableDetail.fnGetData().length - 1].animate({
					backgroundColor : "#4eacc8" },100);
					$("#body_tb_transaction_master_detail tr")[objTableDetail.fnGetData().length - 1].animate({
					backgroundColor : "" },100);
				}
			});
		}

		
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
		<?php echo getBehavio($company->type, "app_invoice_billing", "scriptValidateTotalesZero", ""); ?>

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


		<?php echo getBehavio($company->type, "app_invoice_billing", "scriptValidateCustomer", ""); ?>


		//Validaciones si la factura es al credito.
		if(invoiceTypeCredit){

			<?php echo getBehavio($company->type, "app_invoice_billing", "scriptValidateInCredit", ""); ?>

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



			var montoTotalInvoice 	= fnFormatNumber(fnFormatNumber($("#txtTotal").val(),"4"));
			var balanceCredit 		= 0;

			if(objCurrencyCordoba.currencyID == objCustomerCreditLine[0].currencyID)
				balanceCredit =  fnFormatNumber(fnFormatNumber(objCustomerCreditLine[0].balance,"4"));
			else{
				balanceCredit = (
									fnFormatNumber(fnFormatNumber(objCustomerCreditLine[0].balance,"4")) *
									fnFormatNumber(fnFormatNumber(objCustomerCreditLine[0].objExchangeRate,"4"))
								);
			}

			
			//Validar Limite
			if(parseFloat(balanceCredit) < parseFloat(montoTotalInvoice) &&  parseFloat(balanceCredit) != 0 ){
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



		if(result)
		{
			$("#form-new-invoice" ).submit();
			return;
		}
		else
		{
			cerrarModal('ModalCargandoDatos');
		}

	}

    

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

		$('#txtTypePriceID').val(<?php echo $objParameterTypePreiceDefault ?>);
		$('#txtWarehouseID').val(<?php echo $objParameterTipoWarehouseDespacho ?>);
		$("#txtCurrencyID option").each(function() {
			if ($(this).text().trim() === '<?php echo $objParameterACCOUNTING_CURRENCY_NAME_IN_BILLING ?>') {
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
		$('#txtCustomerID').val(<?php echo $objCustomerDefault->entityID; ?>);
		$('#btnLinkPayment').css('display','none');
		$('#rowBotoneraFacturaFila5').css('display','none');
		$('.showComandoDeCocina').css('display', 'none');
		$('.showPanelEdicion').css('display', 'none');
		$('#registrarFacturaNueva').css('display','block');
		$('#showCommandBar').css('display', 'none');
		$("#workflowLink").empty();
		transactionID =		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		               		                <?php echo $transactionIDNueva ?>;
		$('#txtStatusID').val(<?php echo isset($objListWorkflowStage) ? $objListWorkflowStage[0]->workflowStageID : 0 ?>);
        $('#txtStatusIDOld').val(0);
		$("#txtCheckApplyExoneracionValue").val(0);
		$("#txtCheckReportSinRiesgoValue").val(0);
		$("#txtCheckDeEfectivoValue").val(0);
		fnSetCheckBoxValue($("#txtCheckApplyExoneracion"), false);
		fnSetCheckBoxValue($("#txtCheckReportSinRiesgo"), false);
		fnSetCheckBoxValue($("#txtCheckDeEfectivo"), false);


		<?php echo getBehavio($company->type, 'app_invoice_billing', 'jsClearForm', '') ?>
		setTimeout(()=>{ cerrarModal('ModalCargandoDatos'); }, 1000);

    }

    function fnUpdateInvoiceView(data){

		
        console.info("LOAD INVOICE");
        loadEdicion 		= true;
		cargaCompletada 	= true;
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
		let objParameterEsRestaurante						= data.objParameterEsResrarante;
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

		//renderizar las mesas
		fnRenderMesas(data.objListMesa);

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
		$('#txtCurrencyID').val(objTransactionMaster.currencyID).trigger('change');
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
		if(objTransactionMasterReferences){
			$('#txtLayFirstLineProtocolo').val(objTransactionMasterReferences.reference1);
		}else{
			$('#txtLayFirstLineProtocolo').val("");
		}

		//limpiar tabla de datos
		var typePriceID =  154; /*publico*/
		objTableDetail.fnClearTable();
        if(varDetail != null)
		{
			tmpData = [];
            for(let i = 0 ; i < varDetail.length;i++){
                //master detail reference
				typePriceID			   = varDetail[i].typePriceID;
                let objDetailReference = jLinq.from(varDetailReferences).where(function(obj){ return obj.transactionMasterDetailID === varDetail[i].transactionMasterDetailID }).select();
                //Obtener Iva
                var tmp_ 			= jLinq.from(varDetailConcept).where(function(obj){ return obj.componentItemID === varDetail[i].componentItemID && obj.name === "IVA" }).select();
                var iva_ 			= (tmp_.length <= 0 ? 0 : parseFloat(tmp_[0].valueOut));
                var Precio2 		= jLinq.from(objTransactionMasterItemPrice).where(function(obj){ return (obj.itemID === varDetail[i].componentItemID && obj.typePriceID === "155"); }).select()[0].Precio;
                var Precio3 		= jLinq.from(objTransactionMasterItemPrice).where(function(obj){ return (obj.itemID === varDetail[i].componentItemID && obj.typePriceID === "156"); }).select()[0].Precio;
                var tax2			= varDetail[i].tax2;

				let skuFormatoDescription   = varDetail[i].skuFormatoDescription;
				let skuQuantityBySku		= varDetail[i].skuQuantityBySku;
				let skuCatalogItemID		= varDetail[i].skuCatalogItemID;
				let skuQuantity				= varDetail[i].skuQuantity;

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


				var itemNumber 			= "";
				var mostrarCodigoBarra 	= '<?php echo getBehavio($company->type, 'app_invoice_billing', 'javaScriptShowCodeBarra', 'false') ?>';
				if(mostrarCodigoBarra == "false")
				{
					itemNumber 			= varDetail[i].itemNumber;
				}
				else
				{
					itemNumber 			= varDetail[i].barCode + " " + varDetail[i].itemNumber;
				}

                //Rellenar Datos
                tmpData.push([
                    0,
                    varDetail[i].transactionMasterDetailID,
                    varDetail[i].componentItemID,
                    itemNumber,
                    "'"+varDetail[i].itemNameLog + "'",
                    skuFormatoDescription,
                    fnFormatNumber(varDetail[i].quantity,2),
                    fnFormatNumber(varDetail[i].unitaryPrice, 4),/*precio sistema*/
                    fnFormatNumber(varDetail[i].unitaryPrice *  varDetail[i].quantity,2), /*precio por cantidad*/
                    fnFormatNumber(iva_,2),
                    fnFormatNumber(skuQuantityBySku, 4),
                    fnFormatNumber(varDetail[i].unitaryPrice, 4),
                    "",//acciones
                    skuFormatoDescription,
                    fnFormatNumber(infoPrecio2, 2),
                    fnFormatNumber(infoPrecio3, 2),
                    "'"+varDetail[i].itemNameDescriptionLog + "'",
                    fnFormatNumber(taxServices,2) /*tax_services*/ ,
                    varDetail[i].reference1, /*peso */
                    infoSales,
                    infoSerie,
                    infoReferencia,
                    fnFormatNumber(infoPrecio1, 2),
					skuCatalogItemID,
					skuQuantity,
					varDetail[i].discount, /*discount by item*/
					varDetail[i].tax3  /*comision bank by item*/
                ]);
            }
			objTableDetail.fnAddData(tmpData);
			objTableDetail.fnDraw();
        }
		
		$("#txtTypePriceID").val(typePriceID);
		$("#txtTypePriceID").trigger("change");		
        $("#txtDescuento").val(fnFormatNumber(objTransactionMaster.discount, 2));
        $("#txtPorcentajeDescuento").val(fnFormatNumber(objTransactionMaster.tax4,2));

        fnRecalculateDetail(false,"sumarizar");

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
		if(objParameterEsRestaurante == "true")
		{
			$('.showRestaurante').show();
		}else{
			
			$('.showRestaurante').hide();
		}
        if(objParameterINVOICE_OPEN_CASH_WHEN_PRINTER_INVOICE==="false"){
            $('#divPanelOpenCash').css('display','block');
        }else{
            $('#divPanelOpenCash').css('display', 'none');
        }

        <?php echo getBehavio($company->type, 'app_invoice_billing', 'btnFooter', '') ?>

		if(objTransactionMasterReferences){
			if(objTransactionMasterReferences.reference2 === "1"){
				$('#txtCheckApplyExoneracionValue').val(1);
				fnSetCheckBoxValue($('#txtCheckApplyExoneracion'),true);
			}else{
				fnSetCheckBoxValue($('#txtCheckApplyExoneracion'),false);
				$('#txtCheckApplyExoneracionValue').val(0);
			}
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
		<?php echo getBehavio($company->type, 'app_invoice_billing', 'jsPostUpdateInvoiceView', '') ?>

		cargaCompletada 	= false;
		cerrarModal('ModalCargandoDatos');
		

    }

	function fnRenderMesas(mesas) {
        const $tbody = $('#mesa-body');
        $tbody.empty(); // Limpia el contenido previo
        let row = $('<tr></tr>');

        mesas.forEach((item, index) => {
            const td = $(`
                <td class="container-overlay lazy-background"
					data-bg="${item.reference1}"
                    style="background-size: 180%; background-repeat: no-repeat;"
                    ondblclick="fnSelectCellMesaDoubleClick(this, ${item.reference2})"
                    onclick="fnSelectCellMesa(this)"
                    data-value="${item.catalogItemID}"
                    data-parent="${item.parentCatalogItemID}">
                    
                    <span class="badge badge-success text-overlay">${item.display}</span>
                    <div class="overlay"></div>
                </td>
            `);

            row.append(td);

            if ((index + 1) % 3 === 0) {
                $tbody.append(row);
                row = $('<tr></tr>'); // Iniciar nueva fila
            }
        });

        // Si la última fila no se completó con 3 columnas
        const remaining = mesas.length % 3;
        if (remaining !== 0) {
            for (let i = 0; i < 3 - remaining; i++) {
                row.append('<td></td>');
            }
            $tbody.append(row); // Agregar última fila incompleta
        }
    }

	function fnGenerateInventoryItems(items) {
        var $container 	= $('<div>').addClass('mt-5 custom-table-container-inventory');
        var $innerDiv 	= $('<div>').css({ 'width': '98%', 'margin': '0 auto' }).appendTo($container);
        var $row 		= $('<div>').addClass('row').appendTo($innerDiv);

        // Agregar botón "REGRESAR" (como en el primer elemento del PHP)
        $row.append(
            $('<div>')
                .addClass('col-md-2 item-producto item-producto-back')
                .attr('data-filter', '*')
                .click(function() { fnSelectCellInventoryBack(this); })
                .append(
                    $('<span>').addClass('badge badge-warning text-overlay-categoria').text('REGRESAR'),
                    $('<div>').addClass('overlay')
                )
        );

        // Generar elementos dinámicos para cada ítem
        $.each(items, function(index, item) {
            $row.append(
                $('<div>')
                    .addClass('col-md-2 item-producto')
                    .css('background-image', 'url("' + item.Imagen + '")')
                    .click(function() { fnSelectCellInventory(this); })
                    .dblclick(function() { fnSelectDoubleCellInventory(this); })
                    .attr({
                        'data-value': item.inventoryCategoryID,
                        'data-parent': item.inventoryCategoryID,
                        'data-codigo': item.Codigo
                    })
                    .append(
                        $('<span>')
                            .addClass('badge badge-success text-overlay-categoria')
                            .css({ 'display': 'block', 'white-space': 'normal' })
                            .text(item.Nombre),
                        $('<div>').addClass('overlay')
                    )
            );
        });

        return $container;
    }

	function fnGetConcept(conceptItemID,nameConcept){

		
		let getData 	= objTableDetail.fnGetData();
		var x_			= getData.filter(item => item[2] === conceptItemID);
		var objind_ 	= fnGetPosition(x_,getData);

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
						objTableDetail.fnUpdate( fnFormatNumber(objConcepto1[0].valueOut,2), objind_, columnasTableDetail.iva );
					}
					objConcepto2 	= jLinq.from(objConcepto).where(function(obj){ return (obj.name === "TAX_SERVICES"); }).select();
					if( objConcepto2.length > 0 )
					{
						objTableDetail.fnUpdate( fnFormatNumber(objConcepto2[0].valueOut,2), objind_, columnasTableDetail.taxServices );
					}
				}
				else
				{
					objTableDetail.fnUpdate( 0, objind_, columnasTableDetail.iva );				//IVA
					objTableDetail.fnUpdate( 0, objind_, columnasTableDetail.taxServices );		//TAX_SERVICES
				}
				fnRecalculateDetail(true,"",objind_);
				
			}
		);
	}

	function fnRecalcularMontoComision(monto) {
		
		var cargandoDatosDeFactura 	= cargaCompletada;
		let listRow 				= objTableDetail.fnGetData();
		monto 						= parseFloat(monto);

		if(cargandoDatosDeFactura == true )
			return;

		if (isNaN(monto))
		{
			monto = 0;
		}

		if(monto == 0)
			return;
		
		
		if(listRow.length > 0)
		{
			for(let i=0; i<listRow.length; i++)
			{
				let oldPrice = listRow[i][columnasTableDetail.total];
				let newPrice = oldPrice * ( monto / 100 );
				objTableDetail.fnUpdate(fnFormatNumber(newPrice, 2), i, columnasTableDetail.comisionPosBanck);
			}
		}
		
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
			transactionMasterID = value;
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
		$('#row-items').empty();
		var inventoryCategoryID = String($(cell).data("value"));
		var filterItems = objListInventoryItemsRestaurant.filter(function(item) {
			return item.inventoryCategoryID === inventoryCategoryID;
		});
		var $inventoryContainer = fnGenerateInventoryItems(filterItems);
    	$('#row-items').append($inventoryContainer);
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
	
	
	function fnLockPayment()
	{	
		var invoiceTypeCredit 							= false;
		var causalSelect 								= $("#txtCausalID").val();
		var causalCredit 								= objCausalTypeCredit.value.split(",");
		var lockPayment									= <?php echo getBahavioDB($company->type, "app_invoice_billing", "lockPayment", "false"); ?>;
		//Obtener si la factura es al credito
		for(var i=0;i<causalCredit.length;i++)
		{
			if(causalCredit[i] === causalSelect)
			{
				invoiceTypeCredit 						= true;
			}
		}
		if(invoiceTypeCredit && lockPayment)
		{
			// Inputs → solo lectura
			$("#divPaymentOption").find("input").prop("readonly", true).css("background-color","#eee");

			// Selects → evitar cambios con evento
			$("#divPaymentOption").find("select").each(function() {
				$(this).on("mousedown.disableSelect click.disableSelect", function(e) {
					e.preventDefault();  // Evita abrir el dropdown
				}).css("background-color","#eee");
			});
		}
		if(!invoiceTypeCredit && lockPayment)
		{
			// Desbloquear
			$("#divPaymentOption").find("input").prop("readonly", false).css("background-color", "");

			$("#divPaymentOption").find("select").each(function () {
				$(this).off("mousedown.disableSelect click.disableSelect").css("background-color", "");
			});
		}
	}

	function fnRecalculateDetail(clearRecibo,tipo_calculate, index=-1)
	{
		
		var porcentajeDescuento 						= parseFloat($('#txtPorcentajeDescuento').val()) || 0;
		var totalGeneral 								= 0;
		var despuesDeRecalcularTotalRecibidoIgualCero	= <?php echo getBehavio($company->type, "app_invoice_billing", "despuesDeRecalcularTotalRecibidoIgualCero", "false"); ?>;
		var invoiceTypeCredit 							= false;

        if(index == -1 && tipo_calculate != "sumarizar")
		{
			var cantidad 				= 0;
			var iva 					= 0;
			var taxServices				= 0;
			var precio					= 0;
			var subtotal 				= 0;
			var total 					= 0;
			var cantidadGeneral 		= 0;
			var ivaGeneral 				= 0;
			var serviceGeneral			= 0;
			var precioGeneral			= 0;
			var subtotalGeneral 		= 0;			
			let skuRatio				= 0;
			var priceTemporal			= 0;
			var cantidadTemporal		= 0;
			var descuento				= 0;
			var NSSystemDetailInvoice	= objTableDetail.fnGetData();
			for(var i = 0; i < NSSystemDetailInvoice.length; i++){
				
				cantidadTemporal 	 =  $(".txtQuantity")[i].value;
				priceTemporal  		 =  $(".txtPrice")[i].value;
				objTableDetail.fnUpdate( cantidadTemporal, i, columnasTableDetail.cantidad );
				objTableDetail.fnUpdate( priceTemporal, i, columnasTableDetail.precio );

				cantidad 	= parseFloat(NSSystemDetailInvoice[i][columnasTableDetail.cantidad]);
				precio 		= parseFloat(NSSystemDetailInvoice[i][columnasTableDetail.precio]);
				iva 		= parseFloat(NSSystemDetailInvoice[i][columnasTableDetail.iva]);
				taxServices	= parseFloat(NSSystemDetailInvoice[i][columnasTableDetail.taxServices]);
				skuRatio    = parseFloat(NSSystemDetailInvoice[i][columnasTableDetail.ratioSku]);

				subtotal    = precio * cantidad;
				iva 		= (precio * cantidad) * iva;
				taxServices = (precio * cantidad) * taxServices;
				total 		= iva + taxServices + subtotal ;
				descuento	= subtotal * (porcentajeDescuento / 100);


				cantidadGeneral 	= cantidadGeneral + cantidad;
				precioGeneral 		= precioGeneral + precio;
				ivaGeneral 			= ivaGeneral + iva;
				serviceGeneral		= serviceGeneral + taxServices;
				subtotalGeneral 	= subtotalGeneral + subtotal;
				totalGeneral 		= totalGeneral + total;

				let skuQuantityBySku = cantidad * skuRatio;
				objTableDetail.fnUpdate( skuQuantityBySku, i, columnasTableDetail.skuQuantityBySku );
				objTableDetail.fnUpdate( fnFormatNumber(subtotal,2), i, columnasTableDetail.total );
				objTableDetail.fnUpdate( fnFormatNumber(descuento,2) , i, columnasTableDetail.discount );
			}

			descuento 		= subtotalGeneral * (porcentajeDescuento / 100);
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

			//Obtener si la factura es al credito
			for(var i=0;i<causalCredit.length;i++){
				if(causalCredit[i] === causalSelect){
					invoiceTypeCredit = true;
				}
			}
		}

		if(index > -1 && tipo_calculate != "sumarizar")
		{
			var NSSystemDetailInvoice		= objTableDetail.fnGetData();
			var cantidadTemporal 	 		=  $(".txtQuantity")[index].value;
			var priceTemporal  		 		=  $(".txtPrice")[index].value;
			objTableDetail.fnUpdate( cantidadTemporal, index, columnasTableDetail.cantidad );
			objTableDetail.fnUpdate( priceTemporal, index, columnasTableDetail.precio );
			
			var cantidad 				= parseFloat(NSSystemDetailInvoice[index][columnasTableDetail.cantidad]);
			var skuRatio    			= parseFloat(NSSystemDetailInvoice[index][columnasTableDetail.ratioSku]);
			let skuQuantityBySku 		= cantidad * skuRatio;
			objTableDetail.fnUpdate( skuQuantityBySku, index, columnasTableDetail.skuQuantityBySku );
			
			var precio 					= parseFloat(NSSystemDetailInvoice[index][columnasTableDetail.precio]);
			var subtotal    			= precio * cantidad;
			objTableDetail.fnUpdate( fnFormatNumber(subtotal,2), index, columnasTableDetail.total );
			
			var descuento				= subtotal * (porcentajeDescuento / 100);
			objTableDetail.fnUpdate( fnFormatNumber(descuento,2) , index, columnasTableDetail.discount );
			
			
			var NSSystemDetailInvoice		= objTableDetail.fnGetData();
			var subtotalGeneral 			= jLinq.from(jLinq.from(NSSystemDetailInvoice).select(function(a){ return parseFloat(a[columnasTableDetail.total]); })).sum().result;			
			var descuento		 			= jLinq.from(jLinq.from(NSSystemDetailInvoice).select(function(a){ return parseFloat(a[columnasTableDetail.discount]); })).sum().result;			
			var ivaGeneral		 			= jLinq.from(jLinq.from(NSSystemDetailInvoice).select(function(a){ return parseFloat(a[columnasTableDetail.iva]); })).sum().result;			
			var serviceGeneral		 		= jLinq.from(jLinq.from(NSSystemDetailInvoice).select(function(a){ return parseFloat(a[columnasTableDetail.taxServices]); })).sum().result;			
			totalGeneral				    = subtotalGeneral + ivaGeneral + serviceGeneral - descuento;
			
			$("#txtSubTotal").val(fnFormatNumber(subtotalGeneral,2));
			$('#txtDescuento').val(fnFormatNumber(descuento, 2));
			$("#txtIva").val(fnFormatNumber(ivaGeneral,2));
			$("#txtServices").val(fnFormatNumber(serviceGeneral,2));
			$("#txtTotal").val(fnFormatNumber(totalGeneral,2));
			$("#txtTotalAlternativo").text(fnFormatNumber(totalGeneral,2));
			
			var causalSelect 				= $("#txtCausalID").val();
			var causalCredit 				= objCausalTypeCredit.value.split(",");
			//Obtener si la factura es al credito
			for(var i=0;i<causalCredit.length;i++){
				if(causalCredit[i] === causalSelect){
					invoiceTypeCredit = true;
				}
			}
			
		}
		
		if(tipo_calculate == "sumarizar")
		{
			
			var NSSystemDetailInvoice		= objTableDetail.fnGetData();
			var subtotalGeneral 			= jLinq.from(jLinq.from(NSSystemDetailInvoice).select(function(a){ return parseFloat(a[columnasTableDetail.total]); })).sum().result;			
			var descuento		 			= jLinq.from(jLinq.from(NSSystemDetailInvoice).select(function(a){ return parseFloat(a[columnasTableDetail.discount]); })).sum().result;			
			var ivaGeneral		 			= jLinq.from(jLinq.from(NSSystemDetailInvoice).select(function(a){ return parseFloat(a[columnasTableDetail.iva]); })).sum().result;			
			var serviceGeneral		 		= jLinq.from(jLinq.from(NSSystemDetailInvoice).select(function(a){ return parseFloat(a[columnasTableDetail.taxServices]); })).sum().result;			
			totalGeneral				    = subtotalGeneral + ivaGeneral + serviceGeneral - descuento;
			
			$("#txtSubTotal").val(fnFormatNumber(subtotalGeneral,2));
			$('#txtDescuento').val(fnFormatNumber(descuento, 2));
			$("#txtIva").val(fnFormatNumber(ivaGeneral,2));
			$("#txtServices").val(fnFormatNumber(serviceGeneral,2));
			$("#txtTotal").val(fnFormatNumber(totalGeneral,2));
			$("#txtTotalAlternativo").text(fnFormatNumber(totalGeneral,2));
		}

		if(invoiceTypeCredit === true){
			$("#txtReceiptAmount").val("0.00");
		}
		else if(despuesDeRecalcularTotalRecibidoIgualCero == true)
		{
			$("#txtReceiptAmount").val("0.00");
		}
		else{
			
			$("#txtReceiptAmount").val(fnFormatNumber(totalGeneral,2));
		}
		
		if (clearRecibo)
		{
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
		fnObtenerListadoItemSku();
		fnObtenerListadoItemConcept();

	}

	function fnFillListaItemSku(data)
	{

		console.info("fnFillListaItemSku success data");
		removeDataDB("objListaProductosSkuX001");
		addDataDBArray("objListaProductosSkuX001",data.objListItemSku);
		cerrarModal('ModalActualizandoCatalogo');

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

	function fnGetPosition(item, data){
		return data.findIndex(value => value[2] === item[0][2])
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
			url  		: "<?php echo base_url(); ?>/app_invoice_api/getViewApi/<?php echo $objComponentItem->componentID; ?>/onCompleteNewItem/SELECCIONAR_ITEM_BILLING_BACKGROUND/"+encodeURI('{"warehouseID"|"'+ $("#txtWarehouseID").val() +'"{}"listPriceID"|"<?php echo $objListPrice->listPriceID; ?>"{}"typePriceID"|"'+154+'"}'),
			success		: fnFillListaProductos,
			error:function(xhr,data)
			{
				console.info("fnObtenerListadoProductos data error");
				fnShowNotification("Error 505","error");
			}
		});


	}

	function fnObtenerListadoItemSku(){

		$.ajax(
		{
			cache       : false,
			dataType    : 'json',
			type        : 'GET',
			url  		: "<?php echo base_url(); ?>/app_inventory_api/getSkuAllProduct",
			success		: fnFillListaItemSku,
			error:function(xhr,data)
			{
				console.info("fnObtenerListadoItemSku data error");
				fnShowNotification("Error 505","error");
				cerrarModal('ModalActualizandoCatalogo');
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
		objTableDetail.fnUpdate( fnFormatNumber(precio,2) , rowss, columnasTableDetail.precio );
		fnRecalculateDetail(true,"", rowss);
		refreschChecked();

	}

	function fnActualizarPrecio()
	{	
		var typePriceID 					= $("#txtTypePriceID").val();		
		var NSSystemDetailInvoice			= objTableDetail.fnGetData();
		for(var i = 0; i < NSSystemDetailInvoice.length; i++)
		{
			
			var precio		= 0;
			if(typePriceID == "154" /*precio1*/)
				precio = NSSystemDetailInvoice[i][22];
			else if(typePriceID == "155" /*precio2*/)
				precio = NSSystemDetailInvoice[i][14];
			else /*precio3*/
				precio = NSSystemDetailInvoice[i][15];
		
			objTableDetail.fnUpdate( fnFormatNumber(precio,2), i, columnasTableDetail.precio );
		}
		fnRecalculateDetail(false,"");
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
			var autoClose 			= '<?php echo getBehavio($company->type, "app_invoice_billing", "autoCloseSelectItem", "false"); ?>';
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
		$("#divTipoFactura").removeClass("<?php echo getBehavio($company->type, "app_invoice_billing", "divTxtCausalIDScript", "hidden"); ?>");
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
		fnLockPayment();
		
	}

	function openDataBaseAndCreate(obtenerRegistroDelServer) {
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

			const objectStoreSkuX001  = db.createObjectStore('objListaProductosSkuX001' , { keyPath : 'id',autoIncrement: true } );
			objectStoreSkuX001.createIndex("itemID", "itemID", { unique: false });
			objectStoreSkuX001.createIndex("catalogItemID", "catalogItemID", { unique: false });
			objectStoreSkuX001.createIndex("value", "value", { unique: false });
			objectStoreSkuX001.createIndex("name", "name", { unique: false });
			objectStoreSkuX001.createIndex("price", "precio", { unique: false });
			objectStoreSkuX001.createIndex("predeterminado", "predeterminado", { unique: false });

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
        fnRecalculateDetail(true,"",selectedFilaInfoProducto);
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
                element[12]   = 1 ;//Cantidad
            }


			var mostrarCodigoBarra = '<?php echo getBehavio($company->type, 'app_invoice_billing', 'javaScriptShowCodeBarra', 'false') ?>';
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

			if(mostrarCodigoBarra == "false")
			{
				dataResponse[17] = element[8];//itemNumber
			}
			else
			{
				dataResponse[17] = element[10] + " " + element[8];//barCode + itemNumber
			}

			dataResponse[18] = element[9];//Nombre
            dataResponse[19] = element[0];
            dataResponse[20] = element[11];//Unidad de medida NOMBRE
            dataResponse[21] = element[12];//Cantidad
            dataResponse[22] = element[13];//Precio
            dataResponse[23] = element[7];//UnitMeasuereID
            dataResponse[24] = element[14];//Description
            dataResponse[25] = element[2];//Precio2
            dataResponse[26] = element[3];//Precio3
			dataResponse[27] = element[1];//Precio1
			
			//Logica de precio
			if($("#txtTypePriceID").val() == "154" /*precio1*/)
				dataResponse[22] = dataResponse[27];
			else if($("#txtTypePriceID").val() == "155" /*precio2*/)
				dataResponse[22] = dataResponse[25];
			else /*precio3*/
				dataResponse[22] = dataResponse[26];
				
            onCompleteNewItem(dataResponse, true);
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


	$(document).ready(function()
	{
		fnRenderMesas(objListMesa);
		var obtenerRegistrosDelServer = false;
		openDataBaseAndCreate(obtenerRegistrosDelServer);
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
            $('.lazy-background').each(function() {
                let $td 		= $(this);
                let observer 	= new IntersectionObserver(function(entries) {
                    if (entries[0].isIntersecting) {
                        let bgUrl = $td.attr('data-bg');
                        $td.css('background-image', 'url(' + bgUrl + ')');
                        observer.unobserve($td[0]);
                    }
                }, 
				{
                    rootMargin	: '50px',
                    threshold	: 0.01
                });

                observer.observe(this);
            });
		}
		else 
		{
			$('.showRestaurante').hide();
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
							"mRender"		: function ( data, type, full ) 
							{
								var ocultarBoton	=	"";
								if(varPermisosNoPermitirEliminarProductosFactura && isAdmin !== "1"){
									ocultarBoton	=	"hidden";
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
								return '<input type="text"  class="col-lg-12								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                            								                                             <?php echo $useMobile == "1" ? 'hidden' : '' ?>" style="text-align:left;<?php echo $useMobile == "1" ? 'width: 100%;' : '' ?>" value="'+data+'" readonly="true" />';
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
									classHiddenTex 		= "hidden";
									classHiddenSelect 	= "";
								}
								else
								{
									classHiddenTex 		= "";
									classHiddenSelect 	= "hidden";
								}
								let strFiled 		= "";
								if (varUseMobile === "1")
								{
									strFiled        += '<label class="col-lg-12" style="text-align:right; <?php echo $useMobile == "1" ? 'width: 100%;' : '' ?>">Descripción: '+full[4]+'</label>';
									strFiled 		+= '<input type="hidden" name="txtTransactionDetailName[]" id="txtTransactionDetailName'+full[2]+'"  value="'+full[4]+'" '+NameStatus+' />';
								}
								else
								{
									strFiled 		+= '<input type="text" name="txtTransactionDetailName[]" id="txtTransactionDetailName'+full[2]+'"  class="col-lg-12 '+classHiddenTex+'" style="text-align:left; <?php echo $useMobile == "1" ? 'width: 100%;' : '' ?>" value="'+full[4]+'" '+NameStatus+' />';
								}
								
								let strFiledSelecte = "<select  name='txtItemSelected' class='<?php echo($useMobile == "1" ? "" : "select2"); ?> txtItemSelected "+classHiddenSelect+" ' >";
								strFiledSelecte		= strFiledSelecte+"<option value='"+full[2]+"' selected data-itemid='"+full[2]+"' data-codigo='"+full[3]+"' data-name='"+full[4].replace("'","").replace("'","") +"' data-unidadmedida='"+full[5]+"' data-cantidad='"+full[6]+"' data-precio='"+full[7]+"' data-barra='"+full[3]+"'  data-description='"+full[4].replace("'","").replace("'","") + "'    >"+ full[4].replace("'","").replace("'","")  +"</option>";
								strFiledSelecte		= strFiledSelecte+"</select>";

								strFiledSelecte 	=  strFiled + strFiledSelecte ;
								return strFiledSelecte;


							}
						},
						{
							"aTargets"		: [ 5 ],//Sku
							"sWidth"		: "250px",
							"sClass"		: "td-sku",
							"mRender"		: function ( data, type, full ) {

									var sel = '';
									sel     = '<label data-catalog-id="' + full[5] + '" class="label-sku col-lg-12  <?php echo $useMobile == "1" ? 'hidden' : '' ?>" style="<?php echo $useMobile == "1" ? 'width: 100%;' : '' ?>" >';

									if(varUseMobile == "1")
										espacio = "";

									
									sel = sel + full[13];
									sel = sel + '</label>';
									sel = sel + '<input type="hidden" class="txtSku" name="txtSku[]" id="txtSku'+full[2]+'" value="' + full[13] + '" />';
									return sel;

							}
						},
						{
							"aTargets"		: [ columnasTableDetail.cantidad ],//Cantidad
							"sWidth"		: objParameterINVOICE_SHOW_FIELD_PESO == "true" ? "150px" : "250px",
							"mRender"		: function ( data, type, full ) {
								let str = "";
								if (varPermisosNoPermitirEliminarProductosFactura && isAdmin !== "1"){
									str = '<input type="text" class="col-lg-12 txtQuantity txt-numeric" id="txtQuantityRow'+full[2]+'"  value="'+data+'" name="txtQuantity[]" style="text-align:right; <?php echo $useMobile == "1" ? 'width: 100%;' : '' ?>" autocomplete="off" readonly />';
								}else{
									str = '<input type="text" class="col-lg-12 txtQuantity txt-numeric" id="txtQuantityRow'+full[2]+'"  value="'+data+'" name="txtQuantity[]" style="text-align:right; <?php echo $useMobile == "1" ? 'width: 100%;' : '' ?>" autocomplete="off" />';
								}
								if (varUseMobile == "1")
								str = str + " <span class='badge badge-inverse' >Cantidad</span>";

								return str;
							}
						},
						{
							"aTargets"		: [ columnasTableDetail.precio ],//Precio
							"sWidth"		: "250px",
							"mRender"		: function ( data, type, full ) {
								let str =  '<input type="text" class="col-lg-12 txtPrice txt-numeric" id="txtPriceRow'+full[2]+'" '+PriceStatus+' value="'+data+'" name="txtPrice[]" style="text-align:right; <?php echo $useMobile == "1" ? 'width: 100%;' : '' ?>" autocomplete="off" />';
								if (varUseMobile == "1") str = str + " <span class='badge badge-inverse' >Precio</span>";
								return str;
							}
						},
						{
							"aTargets"		: [ columnasTableDetail.total ],//Total
							"sWidth"		: "250px",
							"mRender"		: function ( data, type, full ) {
								let str;
								if (varUseMobile === "1"){
									str = '<input type="hidden" name="txtSubTotal[]" value="'+data+'" />';
								}else{
									str = '<input type="text" class="col-lg-12 txtSubTotal" readonly value="'+data+'" name="txtSubTotal[]" style="text-align:right;	<?php echo $useMobile == "1" ? 'width: 100%;' : '' ?>" autocomplete="off" />';
								}

								return str;
							}
						},
						{
							"aTargets"		: [ columnasTableDetail.iva ],//Iva
							"bVisible"		: true,
							"sClass"		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtIva" value="'+data+'" name="txtIva[]" style="text-align:right" />';
							}
						},
						{
							"aTargets"		: [ columnasTableDetail.skuQuantityBySku ],//skuQuantityBySku
							"bVisible"		: true,
							"sClass"		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 skuQuantityBySku" value="'+ data +'" name="skuQuantityBySku[]" style="text-align:right" />';
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
									objProductoPrecio1 = fnFormatNumber(objProductoPrecio1);
									//por mayor
									objProductoPrecio2 = fnFormatNumber(objProductoPrecio2);
									//credito
									objProductoPrecio3 = fnFormatNumber(objProductoPrecio3);

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



									ocultarBoton="<?php echo getBehavio($company->type, 'app_invoice_billing', 'divBtnPrecios', '') ?>";
									str     = str+'<button type="button" class="btn btn-success btnInfoProducto '+ocultarBoton+' " data-precio1="'+objProductoPrecio1+'" data-precio2="'+objProductoPrecio2+'" data-precio3="'+objProductoPrecio3+'"><i class="icon16 i-info"></i></button>';
									str		= str+'</div>';

									return str;




							}
						},
						{
							"aTargets"		: [ columnasTableDetail.skuFormatoDescription ],//skuFormatoDescription
							"bVisible"		: true,
							"sClass"		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {

								return '<input type="text" class="col-lg-12 skuFormatoDescription" value="'+full[13]+'" name="skuFormatoDescription[]" style="text-align:right" />';
							}
						},
						{
							"aTargets"		: [ columnasTableDetail.precio2 ],//Precio2
							"bVisible"		: true,
							"sClass"		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtItemPrecio2[]" />';
							}							
						},
						{
							"aTargets"		: [ columnasTableDetail.precio3 ],//Precio3
							"bVisible"		: true,
							"sClass"		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtItemPrecio3[]" />';
							}
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
							"aTargets"		: [ columnasTableDetail.taxServices ],//TAX_SERVICES
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
						{
							"aTargets"		: [ columnasTableDetail.valueSku ],//CATALOG ITEM ID DEL SKU
							"bVisible"		: true,
							"sClass"		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtCatalogItemIDSku[]" />';
							}
						},
						{
							"aTargets"		: [ columnasTableDetail.ratioSku ],//RATIO DEL SKU
							"bVisible"		: true,
							"sClass"		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtRatioSku[]" />';
							}
						},
						{
							"aTargets"		: [ columnasTableDetail.discount ],//Descuento
							"bVisible"		: true,
							"sClass"		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtDiscountByItem[]" />';
							}
						},
						{
							"aTargets"		: [ columnasTableDetail.comisionPosBanck ],//Comision por banco
							"bVisible"		: true,
							"sClass"		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtCommisionByBankByItem[]" />';
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
			let url 		= varBaseUrl + '/app_invoice_billing/edit/' +			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                             			        		                                              <?php echo $companyID ?> + '/' + transactionID + '/' + transactionMasterID + '/' + $("#txtCodigoMesero").val();
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
		
		
		//Permisos de precios		
		if(!varPermisosEsPermitidoSeleccionarPrecioPublico)
			$("#txtTypePriceID option[value='154']").remove();

		if(!varPermisosEsPermitidoSeleccionarPrecioPormayor)
			$("#txtTypePriceID option[value='155']").remove();

		if(!varPermisosEsPermitidoSeleccionarPrecioCredito)
			$("#txtTypePriceID option[value='156']").remove();
			
		$("#txtTypePriceID").trigger("change");



	});



</script>

