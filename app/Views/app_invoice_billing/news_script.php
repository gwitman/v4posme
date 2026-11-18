<!-- ./ page heading -->
<?php $varParameterQzTrayView = isset($objParameterInvoiceUserQsTray) ? $objParameterInvoiceUserQsTray : "false"; ?>
<?php if ($varParameterQzTrayView === "true") { ?>
<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/qz-tray/qz-tray.js"></script>
<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/posme-qztray.js?v=1"></script>
<?php } ?>
<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/posme_invoice_discount.js?v=1"></script>
<script>
    var heigthTop							= 0;
	var objTableDetail 						= {};
    var tmpData 				            = [];
    var tmpInfoClient			            = 0;
	var scrollPosition						= 0;
	var transactionMasterDetailPriceOld		= 0;
    var warehouseID 						= $("#txtWarehouseID").val();
	let transactionID						=	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						 	                 						  <?php echo $transactionID ?>;
	var isAdmin								= '<?php echo $isAdmin; ?>';
	var esMesero							= '<?php echo $esMesero; ?>';
	var openedSearchWindow					= false;
	var objWindowSearchProduct;
	var objTableProductosSearch 			= null;
	var objRowTableProductosSearch 			= null;
	var varBaseUrl							= '<?php echo base_url(); ?>';
	var varParameterQzTray					= <?php echo json_encode((string)$varParameterQzTrayView, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
	var varQzPrinterName					= <?php echo json_encode((string)(isset($objParameterInvoiceQsTrayPrinterName) ? $objParameterInvoiceQsTrayPrinterName : ""), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
    var varStatusInvoiceAplicado			= 67; //Estado Aplicada
    var varStatusInvoiceAnular				= 68; //Anular
	var varStatusInvoiceRegistrado			= 66; //Registrado
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
	var varUrlPrinterPrinterDirect						= '<?php echo $urlPrinterDocumentDirect; ?>';
	var varParameterOcultarCalendarPickerNextVisit 		= '<?php  echo  getBehavio($company->type,"app_invoice_billing","controlNextVisitOcultarPicker",'false') ?>';

    var varParameterInvoiceBillingPrinterDirectBarUrl		= '<?php echo $objParameterINVOICE_BILLING_PRINTER_DIRECT_URL_BAR; ?>';
    var varTransactionCausalID								= 0;
    var varParameterMostrarImagenEnSeleccion 				= '<?php echo $objParameterMostrarImagenEnSeleccion; ?>';
	var varPermisos											= JSON.parse('<?php echo json_encode($objListaPermisos); ?>');
	var varParameterInvoiceBillingPrinterDataLocal			= '<?php echo $dataPrinterLocal; ?>';
	var varParameterUrlServidorDeImpresion 					= '<?php echo $objParameterUrlServidorDeImpresion; ?>';
	var varParameterINVOICE_BILLING_VALIDATE_EXONERATION 	= '<?php echo $objParameterINVOICE_BILLING_VALIDATE_EXONERATION; ?>';
	var objParameterINVOICE_SHOW_FIELD_PESO					= '<?php echo $objParameterINVOICE_SHOW_FIELD_PESO; ?>';	
	var objParameterEsRestaurante							= '<?php echo $objParameterEsResrarante; ?>';
	//Descuento por linea / global (INVOICE_BILLING_DISCOUNT_BY_ITEM); si no viene definido se trata como "false"
	var varParameterInvoiceBillingDiscountByItem			= '<?php echo isset($objParameterINVOICE_BILLING_DISCOUNT_BY_ITEM) ? $objParameterINVOICE_BILLING_DISCOUNT_BY_ITEM : "false"; ?>';


	var varPermisosEsPermitidoModificarPrecio 			= jLinq.from(varPermisos).where(function(obj){ return obj.display == "ES_PERMITIDO_MODIFICAR_PRECIO_EN_FACTURACION"}).select().length > 0 ? true:	false;
	var varPermisosEsPermitidoModificarNombre 			= jLinq.from(varPermisos).where(function(obj){ return obj.display == "ES_PERMITIDO_MODIFICAR_NOMBRE_EN_FACTURACION"}).select().length > 0 ? true:	false;
	var varPermisosEsPermitidoSeleccionarPrecioPublico 	= jLinq.from(varPermisos).where(function(obj){ return obj.display == "ES_PERMITIDO_SELECCIONAR_PRECIO_PUBLICO"}).select().length > 0 ? true:	false;
	var varPermisosEsPermitidoSeleccionarPrecioPormayor	= jLinq.from(varPermisos).where(function(obj){ return obj.display == "ES_PERMITIDO_SELECCIONAR_PRECIO_PORMAYOR"}).select().length > 0 ? true:	false;
	var varPermisosEsPermitidoSeleccionarPrecioCredito 	= jLinq.from(varPermisos).where(function(obj){ return obj.display == "ES_PERMITIDO_SELECCIONAR_PRECIO_CREDITO"}).select().length > 0 ? true:	false;
	var varPermisosNoPermitirEliminarProductosFactura 	= jLinq.from(varPermisos).where(function(obj){ return obj.display == "NO_PERMITIR_ELIMINAR_PRODUCTOS_DE_FACTURA"}).select().length > 0 ? true:	false;
	var varPermisosNoPermitirModificarDetalleFactura 	= jLinq.from(varPermisos).where(function(obj){ return obj.display == "NO_PERMITIR_MODIFICAR_DETALLE_DE_FACTURA"}).select().length > 0 ? true:	false;

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
	let varParameterInvoiceBillingPrinterCocinaUrl;
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
		comisionPosBanck		: 26,
		discountPercentage		: 27
    };

	//Estado del descuento por linea / global (solo con varParameterInvoiceBillingDiscountByItem == "true")
	let objDescuentoGlobal			= { pct: 0, monto: 0 };	//descuento global vigente
	let varDescuentoModoActual		= "none";				//"none" | "line" | "global"
	let varDescuentoAutorizado		= false;				//una autorizacion por factura
	let varDescuentoModificado		= false;				//se intento poner un descuento > 0 en esta factura
	let objDescuentoPendiente		= null;					//accion en espera de la clave {aceptar, cancelar}
	let varInfoDescuentoOrigen		= null;					//modal de informacion: ultimo campo escrito "pct" | "valor"

	const Toast = Swal.mixin({
		toast				: true,
		position			: "bottom-start",
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
		position			: "bottom-start",
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
		if(varPermisosNoPermitirModificarDetalleFactura && isAdmin !== "1" && loadEdicion) return;
		fnAddRowSelected();
	});

	$(document).on("focus", ".txtPrice",function() {
		console.log("El usuario hizo clic en el input");		
		transactionMasterDetailPriceOld = $(this).val();
	});
	$(document).on("change", ".txtPrice",function(e,o,u) {
		console.log("El input perdió el foco");
		var customerPermitirBajarPrecio 	= <?php echo getBahavioSession($company->type,'app_invoice_billing','eventChangePrice','true',$objListCompanyPageSetting) ?>;		
		if(customerPermitirBajarPrecio == true)
			return;
		
		var transactionMasterDetailPriceNew = $(this).val();
		let trSelected 				=  $("#"+e.currentTarget.id).parent().parent()
		let aPos 					=  objTableDetail.fnGetPosition(trSelected[0]);
		var NSSystemDetailInvoice	=  objTableDetail.fnGetData();
		let valores = [];
		
		if(varPermisosEsPermitidoSeleccionarPrecioPublico)
		valores.push(parseFloat(NSSystemDetailInvoice[aPos][22] /*precio1*/));			
		
		if(varPermisosEsPermitidoSeleccionarPrecioPormayor)
		valores.push(parseFloat(NSSystemDetailInvoice[aPos][14] /*precio2*/));			
		
		if(varPermisosEsPermitidoSeleccionarPrecioCredito)
		valores.push(parseFloat(NSSystemDetailInvoice[aPos][15] /*precio3*/));
		
		// Obtenemos el menor
		let menor = Math.min(...valores);
		transactionMasterDetailPriceNew = parseFloat(transactionMasterDetailPriceNew);
		transactionMasterDetailPriceOld = parseFloat(transactionMasterDetailPriceOld);
		
		if(!(transactionMasterDetailPriceNew  >= menor))		
		transactionMasterDetailPriceNew = transactionMasterDetailPriceOld;
		objTableDetail.fnUpdate( fnFormatNumber(transactionMasterDetailPriceNew,2), aPos, columnasTableDetail.precio );
		fnRecalculateDetail(true,"", aPos);
	});
	
	
	$(document).on("click",".btnPlus",function(){
		if(varPermisosNoPermitirModificarDetalleFactura && isAdmin !== "1" && loadEdicion) return;
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
		if(varPermisosNoPermitirModificarDetalleFactura && isAdmin !== "1" && loadEdicion) return;
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

		if(varPermisosNoPermitirModificarDetalleFactura && isAdmin !== "1" && loadEdicion){
			e.preventDefault();
			$("#txtScanerCodigo").val("");
			Toast.fire({ icon: "error", title: "No tiene permiso para agregar productos" });
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
				//Sin cliente no hay saldo de notas de credito
				fnNoteCreditReset(true);
				fnCalculateAmountPay();
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
        //El precio y la cantidad vigentes son los escritos en la fila (el precio pudo cambiarse a mano en txtPriceRow)
        let $precioFila 			= selectTr.find('input.txtPrice');
        let $cantidadFila 			= selectTr.find('input.txtQuantity');
        if($precioFila.length > 0) 		selectedDataInfoProducto[columnasTableDetail.precio] 	= $precioFila.val();
        if($cantidadFila.length > 0) 	selectedDataInfoProducto[columnasTableDetail.cantidad] 	= $cantidadFila.val();
        let precio 					= PosmeInvoiceDiscount.round2(PosmeInvoiceDiscount.num(selectedDataInfoProducto[columnasTableDetail.precio]));
        selectPrecio.empty();

        //Si el precio de la fila no es ninguno de los de la base, se agrega como primera opcion para no perderlo
        if (precio>0 && !precios.some(function(p){ return PosmeInvoiceDiscount.num(p) === precio; }))
            precios.unshift(fnFormatNumber(precio, 2));

        //establecer precios
        if (precio>0) {
            precios.forEach(function(p) {
                let selected 	= PosmeInvoiceDiscount.num(p) === precio;
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

        //Encabezado y resumen de la linea
        fnInfoProductoCargarResumen();
        //Descuento de la linea (solo con el parametro activo)
        fnInfoProductoCargarDescuento();

        refreschChecked();
        mostrarModal('ModalInfoProducto');
    });

    //Modal de informacion: al cambiar el precio se recalcula el resumen y el descuento (se conserva el %)
    $(document).on('change', '#selectPrecio', function(){
        if(selectedDataInfoProducto === undefined)
            return;
        //Si la linea ya tenia descuento, el % se reaplica al nuevo precio al aceptar
        if(varInfoDescuentoOrigen === null && fnDescuentoPorLineaActivo() && !$('#txtInfoDescuentoPct').prop('disabled') && PosmeInvoiceDiscount.num($('#txtInfoDescuentoPct').val()) > 0)
            varInfoDescuentoOrigen = 'pct';
        fnInfoProductoCalcularDescuento(varInfoDescuentoOrigen === "valor" ? "valor" : "pct");
    });
    //Modal de informacion: % <-> monto del descuento en vivo
    $(document).on('input', '#txtInfoDescuentoPct, #txtInfoDescuentoMonto', function(){
        //Solo numeros y hasta 2 decimales
        let limpio = $(this).val().replace(/[^0-9.]/g, '').replace(/^(\d*\.\d{0,2}).*$/, '$1');
        if(limpio !== $(this).val())
            $(this).val(limpio);
        varInfoDescuentoOrigen = this.id === 'txtInfoDescuentoPct' ? 'pct' : 'valor';
        fnInfoProductoCalcularDescuento(varInfoDescuentoOrigen);
    });
    $(document).on('focus', '#txtInfoDescuentoPct, #txtInfoDescuentoMonto', function(){
        $(this).select();
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
	$("#btnBackRegresar").click(function(e){
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
		// Abrir modal de comentario obligatorio antes de anular
		$('#txtDeleteCommentNews').val('').css({'border-color':'#dfe6e9','box-shadow':'none'});
		$('#errDeleteCommentNews').hide();
		$('#modalDeleteCommentNews').modal('show');
	});

	// Modal comentario eliminar (news)
	if($('#modalDeleteCommentNews').length === 0){
		$('body').append(`
		<div class="modal fade" id="modalDeleteCommentNews" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document" style="max-width:440px;margin-top:12vh;">
				<div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.25);">
					<div style="background:#DC2626;padding:22px 24px;color:#fff;position:relative;">
						<div style="display:flex;align-items:center;gap:12px;">
							<div style="width:44px;height:44px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:22px;">&#9888;</div>
							<div>
								<div style="font-size:17px;font-weight:700;line-height:1.2;">Anular factura</div>
								<div style="font-size:12.5px;opacity:.9;">Esta acción requiere justificación</div>
							</div>
						</div>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:absolute;top:14px;right:16px;color:#fff;opacity:.85;font-size:26px;font-weight:400;text-shadow:none;background:none;border:none;cursor:pointer;">&times;</button>
					</div>
					<div style="padding:24px;background:#fff;">
						<label style="display:block;font-size:13.5px;font-weight:600;color:#2d3436;margin-bottom:8px;">Motivo de la anulación <span style="color:#d63031;">*</span></label>
						<textarea id="txtDeleteCommentNews" rows="3" placeholder="Escriba el motivo por el cual anula esta factura..." style="width:100%;border:1.5px solid #dfe6e9;border-radius:10px;padding:12px 14px;font-size:14px;resize:none;outline:none;transition:border-color .2s,box-shadow .2s;box-sizing:border-box;"></textarea>
						<div id="errDeleteCommentNews" style="display:none;color:#d63031;font-size:12.5px;margin-top:6px;">El comentario es obligatorio para continuar.</div>
					</div>
					<div style="padding:16px 24px;background:#f8f9fa;display:flex;justify-content:flex-end;gap:10px;">
						<button type="button" data-dismiss="modal" id="btnCancelDeleteNews" style="border:none;background:#e9ecef;color:#495057;padding:10px 20px;border-radius:9px;font-size:13.5px;font-weight:600;cursor:pointer;transition:background .2s;">Cancelar</button>
						<button type="button" id="btnConfirmDeleteNews" style="border:none;background:#DC2626;color:#fff;padding:10px 22px;border-radius:9px;font-size:13.5px;font-weight:600;cursor:pointer;box-shadow:0 4px 12px rgba(214,48,49,.3);">Confirmar anulación</button>
					</div>
				</div>
			</div>
		</div>`);
	}

	$('#modalDeleteCommentNews').on('shown.bs.modal', function(){
		$('#txtDeleteCommentNews').focus();
	});
	$(document).on('focus', '#txtDeleteCommentNews', function(){
		$(this).css({'border-color':'#d63031','box-shadow':'0 0 0 3px rgba(214,48,49,.12)'});
	});
	$(document).on('input', '#txtDeleteCommentNews', function(){
		if($(this).val().trim() !== ''){
			$(this).css('border-color','#dfe6e9');
			$('#errDeleteCommentNews').hide();
		}
	});
	$(document).on('click', '#btnCancelDeleteNews', function(){
		$('#modalDeleteCommentNews').modal('hide');
	});
	$(document).on('click', '#btnConfirmDeleteNews', function(){
		var comments = $('#txtDeleteCommentNews').val().trim();
		if(comments === ''){
			$('#txtDeleteCommentNews').css({'border-color':'#d63031','box-shadow':'0 0 0 3px rgba(214,48,49,.12)'}).focus();
			$('#errDeleteCommentNews').show();
			return;
		}
		$('#modalDeleteCommentNews').modal('hide');
		fnAceptarModalDeleteInvoice(comments);
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
			if(varPermisosNoPermitirModificarDetalleFactura && isAdmin !== "1" && loadEdicion){
				Toast.fire({ icon: "error", title: "No tiene permiso para eliminar productos" });
				return;
			}
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
	//Notas de credito (eventos delegados: las personalizaciones mueven #divPaymentOption)
	$(document).on("input","input#txtReceiptAmountNoteCredit",function(){
			fnNoteCreditRender();
	});
	$(document).on("focus","input#txtReceiptAmountNoteCredit",function(){
			$(this).data("ncPrev", fnNoteCreditNum($(this).val()));
	});
	$(document).on("change","input#txtReceiptAmountNoteCredit",function(){
			//Si el recibido era el sugerido (total - NC anterior) se ajusta a total - NC nueva
			var total 		= fnNoteCreditNum($("#txtTotal").val());
			var previo 		= Math.min(fnNoteCreditNum($(this).data("ncPrev")), Math.max(total, 0));
			var recibido 	= fnNoteCreditNum($("#txtReceiptAmount").val());
			if(!$("#txtReceiptAmount").prop("readonly") && Math.abs(recibido - (total - previo)) < 0.005)
			{
				$("#txtReceiptAmount").val(fnFormatNumber(Math.max(total - fnNoteCreditAplicable(total), 0),2));
			}
			$(this).data("ncPrev", fnNoteCreditNum($(this).val()));
			fnCalculateAmountPay();
	});
	$(document).on("blur","input#txtReceiptAmountNoteCredit",function(){
			if($.trim($(this).val()) !== "") $(this).val(fnNoteCreditFmt($(this).val()));
	});
	//Cambio de moneda: el saldo de NC es por moneda
	$(document).on("change","#txtCurrencyID",function(){
			fnNoteCreditLoad();
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
		var urlImpresionDocumento = "<?php echo base_url(); ?>/"+varUrlPrinter+"/companyID/2/transactionID/19/transactionMasterID/"+$("#txtTransactionMasterID").val()+"/"+fechaHora;

		if (varParameterQzTray === 'true' && typeof PosmeQzTray !== 'undefined') {
			// Impresión directa con QZ Tray; si falla, respaldo con la vista de impresión actual
			PosmeQzTray.imprimirDocumento({
				baseUrl: varBaseUrl,
				url: urlImpresionDocumento,
				printerName: varQzPrinterName,
				onFallback: function(){ window.open(urlImpresionDocumento, '_blank'); }
			});
		} else {
			window.open(urlImpresionDocumento, '_blank');
		}
		cerrarModal('ModalCargandoDatos');
		cerrarModal("ModalOpcionesImpresion");
	});
	$("#btnAceptarDialogPrinterV2AceptarDocumentA4").click(function(){
		mostarModalPersonalizado('Cargando impresión, por favor espere...');
		window.open("<?php echo base_url(); ?>/"+varUrlPrinterOpcion2+"/companyID/2/transactionID/19/transactionMasterID/"+$("#txtTransactionMasterID").val(), '_blank');
		cerrarModal('ModalCargandoDatos');
		cerrarModal("ModalOpcionesImpresion");
	});
	$("#btnAceptarDialogPrinterV2AceptarDocumentA4White").click(function(){
		mostarModalPersonalizado('Cargando impresión, por favor espere...');
		window.open("<?php echo base_url(); ?>/"+varUrlPrinterOpcion3+"/companyID/2/transactionID/19/transactionMasterID/"+$("#txtTransactionMasterID").val(), '_blank');
		cerrarModal('ModalCargandoDatos');
		cerrarModal("ModalOpcionesImpresion");
	});
	
	$("#btnCloseModalImpresionCocinV2").click(function(){
		cerrarModal("ModalCustomCocinaV2");
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
		
		window.open("<?php echo base_url(); ?>/"+varUrlPrinterPrinterDirect+"/companyID/2/transactionID/19/transactionMasterID/"+$("#txtTransactionMasterID").val()+"/direct/true/"+fechaHora, '_blank');
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
		 //Con descuento por linea el calculo lo hace el evento change (fnDescuentoCambioGlobal)
		 if(fnDescuentoPorLineaActivo()) return;

		 mostarModalPersonalizado('Calculando conceptos');
		 fnRecalculateDetail(true,"");
		 cerrarModal('ModalCargandoDatos');
		 
    });
	$('#txtDescuento').on('blur', function() {
		 //Con descuento por linea el calculo lo hace el evento change (fnDescuentoCambioGlobal)
		 if(fnDescuentoPorLineaActivo()) return;

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

	//Descuento por linea / global (solo con INVOICE_BILLING_DISCOUNT_BY_ITEM = "true")
	//Captura: solo numeros con hasta 2 decimales
	$(document).on("input", ".txtDiscountItemPct, .txtDiscountItemAmount, #txtDescuento", function(){
		if(!fnDescuentoPorLineaActivo()) return;
		var valor 		= $(this).val();
		var expresion 	= /^\d*\.?\d{0,2}$/;
		if (!expresion.test(valor)) {
			$(this).val(valor.slice(0, -1));
		}
	});
	//Cambio del descuento de una linea (% o monto)
	$(document).on("change", ".txtDiscountItemPct, .txtDiscountItemAmount", function(){
		if(!fnDescuentoPorLineaActivo()) return;
		fnDescuentoCambioLinea(this);
	});
	//Cambio del descuento global (% o monto)
	$(document).on("change", "#txtPorcentajeDescuento", function(){
		if(!fnDescuentoPorLineaActivo()) return;
		fnDescuentoCambioGlobal("pct");
	});
	$(document).on("change", "#txtDescuento", function(){
		if(!fnDescuentoPorLineaActivo()) return;
		fnDescuentoCambioGlobal("valor");
	});
	//Modal de autorizacion del descuento
	$(document).on("click", "#btnAceptarClaveDescuento", function(){
		fnDescuentoValidarClave();
	});
	$(document).on("keydown", "#txtClaveDescuento", function(e){
		if(e.key === "Enter"){
			e.preventDefault();
			fnDescuentoValidarClave();
		}
	});
	$(document).on("click", "#btnCancelarClaveDescuento", function(){
		cerrarModal("ModalAutorizarDescuento");
		var pendiente 			= objDescuentoPendiente;
		objDescuentoPendiente 	= null;
		$("#txtClaveDescuento").val("");
		if(pendiente && typeof pendiente.cancelar === "function")
			pendiente.cancelar();
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

		//Notas de credito: se suman al recibido en la moneda de la factura, pero el vuelto nunca sale de la NC
		//(como mucho cubren el total): cambio = otros pagos - (total - NC)
		if( currencyId === "1" || currencyId === "2" )
		{
			resultTotal = parseFloat(resultTotal) + fnNoteCreditAplicable(total);
		}

        resultTotal = fnFormatNumber(resultTotal,2);
        $("#txtChangeAmount").val(resultTotal);
		fnNoteCreditRender();
    }

	/* ==========================================================
	   Pago con Notas de Credito (NC) del cliente
	   - Solo facturas de contado y en la moneda de la factura.
	   - El cajero escribe solo el monto; el servidor consume las NC mas antiguas primero.
	   - Visual 1,000.00; se envia como numero puro (txtReceiptAmountNoteCredit).
	   ========================================================== */
	var varNoteCreditBalance 	= 0;		//Saldo NC disponible del cliente en la moneda de la factura
	var varNoteCreditLoaded 	= false;	//El saldo se consulto con exito para el cliente/moneda actuales
	var varNoteCreditRequest 	= 0;		//Secuencia: solo se acepta la respuesta de la ultima consulta

	//Numero puro con 2 decimales a partir del texto con comas (1,000.50 -> 1000.5)
	function fnNoteCreditNum(value)
	{
		var texto 	= String(value === null || value === undefined ? "" : value).replace(/,/g, "");
		var numero 	= parseFloat(texto);
		return isNaN(numero) ? 0 : Math.round(numero * 100) / 100;
	}

	//Formato visual 1,000.00 (no altera el valor)
	function fnNoteCreditFmt(value)
	{
		var numero 	= fnNoteCreditNum(value);
		var partes 	= Math.abs(numero).toFixed(2).split(".");
		partes[0] 	= partes[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		return (numero < 0 ? "-" : "") + partes[0] + "." + partes[1];
	}

	//Simbolo de la moneda seleccionada en la factura (si no es la moneda de cordoba se usa su nombre)
	function fnNoteCreditSimbolo()
	{
		var currencyID = $("#txtCurrencyID").val();
		if(objCurrencyCordoba && String(objCurrencyCordoba.currencyID) === String(currencyID))
			return (objCurrencyCordoba.simbol ? objCurrencyCordoba.simbol : varCurrencyDefaultSimbol);
		return $.trim($("#txtCurrencyID option:selected").text());
	}

	//La causal seleccionada es de credito
	function fnNoteCreditEsCredito()
	{
		var causalSelect 	= $("#txtCausalID").val();
		var causalCredit 	= (objCausalTypeCredit && objCausalTypeCredit.value) ? String(objCausalTypeCredit.value).split(",") : [];
		for(var i = 0; i < causalCredit.length; i++)
		{
			if(causalCredit[i] == causalSelect) return true;
		}
		return false;
	}

	//Monto de NC que entra al calculo del pago: nunca mas que el total de la factura
	function fnNoteCreditAplicable(total)
	{
		var monto 		= fnNoteCreditNum($("#txtReceiptAmountNoteCredit").val());
		var totalFac 	= fnNoteCreditNum(total);
		if(monto <= 0) return 0;
		return Math.min(monto, Math.max(totalFac, 0));
	}

	//Limpia el saldo mostrado; con clearAmount tambien el monto escrito
	function fnNoteCreditReset(clearAmount)
	{
		varNoteCreditRequest++;
		varNoteCreditBalance 	= 0;
		varNoteCreditLoaded 	= false;
		$("#lblNoteCreditAvailable").text("0.00");
		if(clearAmount) $("#txtReceiptAmountNoteCredit").val("0");
		fnNoteCreditRender();
	}

	/* Consulta el saldo de NC del cliente en la moneda de la factura.
	   Casos: error (saldo 0 y aviso), sin saldo (0.00), con saldo. */
	function fnNoteCreditLoad()
	{
		var entityID 	= $.trim($("#txtCustomerID").val() || "");
		var currencyID 	= $.trim($("#txtCurrencyID").val() || "");
		varNoteCreditRequest++;
		var request 	= varNoteCreditRequest;
		varNoteCreditBalance 	= 0;
		varNoteCreditLoaded 	= false;
		$("#lblNoteCreditAvailable").text("0.00");
		fnNoteCreditRender();
		if(entityID === "" || currencyID === "") return;

		$.ajax({
			cache 		: false,
			dataType 	: "json",
			type 		: "POST",
			url 		: "<?php echo base_url(); ?>/app_invoice_billing/getNoteCreditAvailable",
			data 		: { entityID : entityID, currencyID : currencyID },
			success 	: function(data){
				//Respuesta vieja (cambio el cliente o la moneda mientras se consultaba)
				if(request !== varNoteCreditRequest) return;

				//Error
				if(!data || data.error === true)
				{
					fnNoteCreditRender();
					if(fnNoteCreditNum($("#txtReceiptAmountNoteCredit").val()) > 0)
						Toast.fire({ icon: "warning", title: (data && data.message) ? data.message : "No se pudo consultar el saldo de notas de credito" });
					return;
				}

				//Sin saldo / con saldo
				varNoteCreditBalance 	= Math.max(fnNoteCreditNum(data.balance), 0);
				varNoteCreditLoaded 	= true;
				$("#lblNoteCreditAvailable").text(fnNoteCreditSimbolo() + " " + fnNoteCreditFmt(varNoteCreditBalance));
				fnNoteCreditRender();
			},
			error 		: function(){
				if(request !== varNoteCreditRequest) return;
				fnNoteCreditRender();
			}
		});
	}

	//Mensaje bajo el monto: "Le quedara C$ X" o "Maximo permitido: C$ Y"
	function fnNoteCreditRender()
	{
		var $input 		= $("#txtReceiptAmountNoteCredit");
		var $mensaje 	= $("#lblNoteCreditMessage");
		if($input.length === 0) return;

		var monto 		= fnNoteCreditNum($input.val());
		var total 		= fnNoteCreditNum($("#txtTotal").val());
		var maximo 		= Math.max(Math.min(varNoteCreditBalance, total), 0);
		var simbolo 	= fnNoteCreditSimbolo();
		var texto 		= "";
		var clase 		= "";

		$mensaje.removeClass("pm-nc-ok pm-nc-error");
		$input.removeClass("pm-nc-invalido");

		//Factura ya aplicada: la NC ya se consumio, solo se muestra el monto
		if($("#txtStatusIDOld").val() == varStatusInvoiceAplicado)
		{
			$mensaje.text("");
			return;
		}

		if(fnNoteCreditEsCredito())
		{
			texto = "Solo en facturas de contado";
			clase = monto > 0 ? "pm-nc-error" : "";
		}
		else if(monto < 0)
		{
			texto = "El monto no puede ser negativo";
			clase = "pm-nc-error";
		}
		else if(monto > 0 && !varNoteCreditLoaded)
		{
			texto = "Consultando saldo...";
		}
		else if(monto > 0 && monto > maximo)
		{
			texto = "Máximo permitido: " + simbolo + " " + fnNoteCreditFmt(maximo);
			clase = "pm-nc-error";
		}
		else if(monto > 0)
		{
			texto = "Le quedará " + simbolo + " " + fnNoteCreditFmt(varNoteCreditBalance - monto);
			clase = "pm-nc-ok";
		}

		$mensaje.text(texto);
		if(clase !== "") $mensaje.addClass(clase);
		if(clase === "pm-nc-error") $input.addClass("pm-nc-invalido");
	}

	//Validacion antes de guardar; devuelve el mensaje de error o "" si es valido
	function fnNoteCreditValidar()
	{
		var monto 	= fnNoteCreditNum($("#txtReceiptAmountNoteCredit").val());
		var total 	= fnNoteCreditNum($("#txtTotal").val());
		//Factura ya aplicada: la NC ya se consumio; el servidor no la vuelve a aplicar
		if($("#txtStatusIDOld").val() == varStatusInvoiceAplicado) 	return "";
		if(monto < 0) 								return "El monto de notas de credito no puede ser negativo";
		if(monto == 0) 								return "";
		if(fnNoteCreditEsCredito()) 				return "Las notas de credito solo se aceptan en facturas de contado";
		if(!varNoteCreditLoaded) 					return "No se ha podido consultar el saldo de notas de credito del cliente; intente de nuevo";
		if(monto > total) 							return "El monto de notas de credito no puede ser mayor al total de la factura. Maximo permitido: " + fnNoteCreditFmt(Math.min(varNoteCreditBalance, total));
		if(monto > varNoteCreditBalance) 			return "Saldo de notas de credito insuficiente. Maximo permitido: " + fnNoteCreditFmt(Math.min(varNoteCreditBalance, total));
		return "";
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

	
	function fnAceptarModalImpresionCocinaV2 ()
	{
		cerrarModal("ModalCustomCocinaV2");
		
		var listRow 	= objTableDetail.fnGetData();
		var length 		= listRow.length;
		var comentario 	= $("#txtNote").val();
		comentario 		= comentario.replace(/\s+/g, '___');
		
		var i 							= 0;
		var itemid 						= "-1";
		var transactionMasterDetailID 	= "-1";

		while (i < length ){
			if(listRow[i][0] == true){				
				transactionMasterDetailID 	= transactionMasterDetailID + ","+listRow[i][1];
				itemid 						= itemid + ","+listRow[i][2];
			}
			i++;
		}

		
		if(varParameterInvoiceBillingPrinterDirect == 'false')
		{

			var url	= 	"<?php echo base_url(); ?>/"+varParameterInvoiceBillingPrinterCocinaUrl;
			url 	= 	url+
			"/companyID/"+ objTransactionMaster.companyID +
			"/transactionID/"+ objTransactionMaster.transactionID +
			"/transactionMasterID/"+ objTransactionMaster.transactionMasterID +
			"/itemID/"+itemid+"/transactionMasterDetailID/"+transactionMasterDetailID+"/transactionMasterComment/"+comentario;
			window.open(url, "_blank");
			
		}
		
	}


	function fnImprimirCocina(){

		
		var listRow 	= objTableDetail.fnGetData();
		var length 		= listRow.length;
		var comentario 	= $("#txtNote").val();

		var i 							= 0;
		var itemid 						= "-1";
		var transactionMasterDetailID 	= "-1";

		while (i < length ){
			if(listRow[i][0] == true){
				transactionMasterDetailID 	= transactionMasterDetailID + ","+listRow[i][1];
				itemid 						= itemid + ","+listRow[i][2];
			}
			i++;
		}

		
		if(varParameterInvoiceBillingPrinterDirect == 'true'){

			var url	= 	"<?php echo base_url(); ?>/"+varParameterInvoiceBillingPrinterDirectCocinaUrl;
			url 	= 	url+
			"/companyID/"+ objTransactionMaster.companyID +
			"/transactionID/"+ objTransactionMaster.transactionID +
			"/transactionMasterID/"+ objTransactionMaster.transactionMasterID +
			"/itemID/"+itemid+"/transactionMasterDetailID/"+transactionMasterDetailID+"/transactionMasterComment/"+comentario;

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
		if(varPermisosNoPermitirModificarDetalleFactura && isAdmin !== "1" && loadEdicion){
			Toast.fire({ icon: "error", title: "No tiene permiso para agregar productos" });
			return;
		}
		
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
			//Rediseño: la capa visual desplaza la tabla hasta la fila actualizada (sin mover el foco)
			if(typeof window.pmDesplazarFilaDetalle === "function")
			window.pmDesplazarFilaDetalle(objind_, false);
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
				
				var filaNueva = [
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
					objRow.commisionBank,
					0 /*discountPercentage: % de descuento de la linea*/
				];

				//Rediseño: el producto nuevo se inserta AL INICIO de la tabla.
				//Orden DOM == orden de datos (bSort=false): se reconstruye la tabla con la fila nueva primero.
				//Antes se pasan a los datos los valores que viven en los inputs de cada fila
				//(check, descripcion, cantidad, precio, peso) para no perder lo escrito.
				var filasDom = $("#body_tb_transaction_master_detail > tr");
				for(var iFila = 0; iFila < filasDom.length; iFila++){
					var posFila = objTableDetail.fnGetPosition(filasDom[iFila]);
					if(posFila === null || posFila === undefined || posFila < 0)
						continue;
					var dataFila = objTableDetail.fnGetData(posFila);
					if(!dataFila)
						continue;
					var $filaDom 		= $(filasDom[iFila]);
					var $chkFila 		= $filaDom.find("input.classCheckedDetail");
					var $nombreFila 	= $filaDom.find("input[name='txtTransactionDetailName[]']");
					var $cantidadFila 	= $filaDom.find("input.txtQuantity");
					var $precioFila 	= $filaDom.find("input.txtPrice");
					var $pesoFila 		= $filaDom.find("input.txtDetailLote");
					if($chkFila.length > 0) 	dataFila[0] 								= $chkFila.is(":checked");
					if($nombreFila.length > 0) 	dataFila[columnasTableDetail.descripcion] 	= $nombreFila.val();
					if($cantidadFila.length > 0) dataFila[columnasTableDetail.cantidad] 	= $cantidadFila.val();
					if($precioFila.length > 0) 	dataFila[columnasTableDetail.precio] 		= $precioFila.val();
					if($pesoFila.length > 0) 	dataFila[18] 								= $pesoFila.val();
					//Descuento por linea (cols 25 y 27): no se copia el input; el valor aplicado (y autorizado)
					//ya vive en los datos por fnUpdate, asi un valor escrito sin autorizar no se cuela al reconstruir
				}

				var dataActual = objTableDetail.fnGetData().slice();
				objTableDetail.fnClearTable(false);
				objTableDetail.fnAddData([filaNueva].concat(dataActual));

				fnGetConcept(objRow.itemID,"ALL");
				refreschChecked();
				if(varUseMobile != "1"){
					$("#body_tb_transaction_master_detail tr")[0].animate({
					backgroundColor : "#4eacc8" },100);
					$("#body_tb_transaction_master_detail tr")[0].animate({
					backgroundColor : "" },100);
				}
				//Rediseño: la capa visual lleva la tabla al inicio (sin mover el foco)
				if(typeof window.pmDesplazarFilaDetalle === "function")
				window.pmDesplazarFilaDetalle(0, true);
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

		//Validar descuento por linea / global: rangos, exclusion y autorizacion
		if(fnDescuentoPorLineaActivo())
		{
			let resultadoDescuento = fnDescuentoValidarAntesDeGuardar();
			if(resultadoDescuento === "clave")
			{
				//Hay descuento sin autorizar: se pide la clave y el usuario vuelve a guardar
				cerrarModal('ModalCargandoDatos');
				fnDescuentoSolicitarClave(
					function(){ ToastSuccess.fire({ icon: "success", title: "Descuento autorizado. Presione guardar nuevamente." }); },
					function(){}
				);
				return;
			}
			if(resultadoDescuento === false)
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


		//Validar pago con notas de credito: >= 0, <= total, <= saldo del cliente y solo en contado
		var errorNoteCredit 			= fnNoteCreditValidar();
		if(errorNoteCredit !== "")
		{
			result = false;
			Toast.fire({
				icon	: "warning",
				title	: errorNoteCredit
			});
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
			//La nota de credito se envia como numero puro (sin comas)
			$("#txtReceiptAmountNoteCredit").val(fnNoteCreditNum($("#txtReceiptAmountNoteCredit").val()).toFixed(2));
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
		//Notas de credito: nueva factura sin monto ni saldo (se vuelve a consultar con el cliente por defecto)
		fnNoteCreditReset(true);
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

		if(varParameterOcultarCalendarPickerNextVisit == "false")
		{
			$('#txtNextVisit').datepicker({format:"yyyy-mm-dd"});
		}
		
		$('#txtDateFirst').datepicker({format:"yyyy-mm-dd"});
		$('#txtDateFirst').val(moment().add('days', 0).format("YYYY-MM-DD"));
		$("#txtDateFirst").datepicker("update");
		$('#txtCustomerID').val(<?php echo $objCustomerDefault->entityID; ?>);
		fnNoteCreditLoad();
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
		//Descuento por linea / global: nueva factura sin autorizacion ni descuento global
		if(fnDescuentoPorLineaActivo())
			fnDescuentoReiniciar();


		<?php echo getBehavio($company->type, 'app_invoice_billing', 'jsClearForm', '') ?>
		setTimeout(()=>{ cerrarModal('ModalCargandoDatos'); }, 1000);

    }

    function fnUpdateInvoiceView(data){

		
        console.info("LOAD INVOICE");
        loadEdicion 		= true;
		cargaCompletada 	= true;
		//Descuento por linea / global: el parametro puede venir en la respuesta de edicion (no definido = "false")
		varParameterInvoiceBillingDiscountByItem 	= (data.objParameterINVOICE_BILLING_DISCOUNT_BY_ITEM !== undefined && data.objParameterINVOICE_BILLING_DISCOUNT_BY_ITEM !== null) ? String(data.objParameterINVOICE_BILLING_DISCOUNT_BY_ITEM) : "false";
		//Otra factura: se limpia la autorizacion del descuento
		if(fnDescuentoPorLineaActivo())
			fnDescuentoReiniciar();
		objParameterINVOICE_BILLING_SHOW_COMMAND_BAR    = data.objParameterINVOICE_BILLING_SHOW_COMMAND_BAR;
        varParameterTipoPrinterDownload     			= data.objParameterTipoPrinterDonwload;
        objParameterPrinterDirectAndPreview 			= data.objParameterPrinterDirectAndPreview;
        varParameterShowComandoDeCocina     			= data.objParameterShowComandoDeCocina;
        varUrlPrinterOpcion2    = data.urlPrinterDocumentOpcion2;
		varUrlPrinterOpcion3	= data.urlPrinterDocumentOpcion3;
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
		varParameterInvoiceBillingPrinterCocinaUrl			= data.urlPrinterDocumentCocina;	
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
			$('#linkMobile').append('<li><a href="javascript:void(0);"  id="btnFooter">COCINA</a></li>');
		}
		if(objParameterINVOICE_BILLING_SHOW_COMMAND_BAR == "true"){
			$('#showCommandBar').show();
			$('#linkMobile').append('<li><a href="javascript:void(0);"  id="btnBar">BAR</a></li>');
		}
        if(objListWorkflowStage.length > 0){
			$("#workflowLink").empty();
            for(let w=0; w < objListWorkflowStage.length; w++){
                let nameWorkflow 	= data.objListWorkflowStageNames[w];
                let ws 				= objListWorkflowStage[w];
                let buttonClass 	= w === 0 ? 'btn-comando-factura' : 'btnAceptAplicar btn-comando-factura';
                let buttonHtml 		= '<div class="col col-lg-2">'+
                    '<a href="javascript:void(0);" class="btn btn-flat btn-warning btnAcept btn-block '+ buttonClass +'" data-valueworkflow="'+ ws.workflowStageID+ '">' +
                        '<i class="icon16 i-checkmark-4"></i> ' + nameWorkflow.trim() +
                    '</a>'+
                '</div>';
				$('#linkMobile').append('<li><a href="javascript:void(0);" class="btnAcept ' + buttonClass + '" data-valueworkflow="' + ws.workflowStageID + '"> ' + nameWorkflow.trim() + '</a></li>');
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
		if(varParameterOcultarCalendarPickerNextVisit == "false")
		{
        	$("#txtNextVisit").datepicker("update");
		}

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

				//Descuento por linea: tax4 = monto, reference4 = %; facturas viejas traen tax4 null -> 0.
				//Sin monto no se toma el % (reference4 podria tener otro dato en facturas viejas).
				var descLineaMonto 		= varDetail[i].discount; /*legado: monto del descuento de la linea*/
				var descLineaPct 		= 0;
				if(fnDescuentoPorLineaActivo())
				{
					var descTax4 		= PosmeInvoiceDiscount.round2(varDetail[i].tax4);
					var descRef4 		= PosmeInvoiceDiscount.round4(varDetail[i].reference4);
					var descBruto 		= PosmeInvoiceDiscount.num(varDetail[i].unitaryPrice) * PosmeInvoiceDiscount.num(varDetail[i].quantity);
					descLineaMonto 		= descTax4 > 0 ? descTax4 : 0;
					descLineaPct 		= 0;
					if(descLineaMonto > 0)
						descLineaPct 	= descRef4 > 0 ? descRef4 : (descBruto > 0 ? PosmeInvoiceDiscount.round4(descLineaMonto / descBruto * 100) : 0);
					descLineaMonto 		= fnFormatNumber(descLineaMonto, 2);
					descLineaPct 		= fnFormatPctDescuento(descLineaPct);
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
					descLineaMonto, /*discount by item*/
					varDetail[i].tax3,  /*comision bank by item*/
					descLineaPct /*discountPercentage by item*/
                ]);
            }
			objTableDetail.fnAddData(tmpData);
			objTableDetail.fnDraw();
        }
		
		$("#txtTypePriceID").val(typePriceID);
		$("#txtTypePriceID").trigger("change");		
		if(!fnDescuentoPorLineaActivo())
		{
        $("#txtDescuento").val(fnFormatNumber(objTransactionMaster.discount, 2));
        $("#txtPorcentajeDescuento").val(fnFormatNumber(objTransactionMaster.tax4,2));
		}
		else
		{
			//Descuento por linea: si ninguna linea trae monto (tax4) y el maestro trae descuento, es descuento global.
			//No se pide clave mientras no se cambie el descuento.
			let hayDescuentoLinea 	= false;
			if(varDetail != null)
			{
				for(let d = 0; d < varDetail.length; d++)
				{
					if(PosmeInvoiceDiscount.num(varDetail[d].tax4) > 0) hayDescuentoLinea = true;
				}
			}
			//Base del % global = SUB TOTAL bruto de las lineas ya cargadas en la tabla
			let subtotalCarga 		= PosmeInvoiceDiscount.calcResumen(fnDescuentoLeerLineas(), "none", 0).subtotal;
			let descuentoMaestro 	= PosmeInvoiceDiscount.round2(objTransactionMaster.discount);
			objDescuentoGlobal 		= { pct: 0, monto: 0 };
			if(!hayDescuentoLinea && descuentoMaestro > 0)
			{
				objDescuentoGlobal 	= {
					monto 	: descuentoMaestro,
					pct 	: subtotalCarga > 0 ? PosmeInvoiceDiscount.round4(descuentoMaestro / subtotalCarga * 100) : 0
				};
			}
			varDescuentoModificado 	= false;
		}

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
		//Notas de credito: monto guardado en la factura y saldo actual del cliente en la moneda de la factura
		$('#txtReceiptAmountNoteCredit').val(fnNoteCreditFmt(objTransactionMasterInfo && objTransactionMasterInfo.receiptAmountNoteCredit !== undefined && objTransactionMasterInfo.receiptAmountNoteCredit !== null ? objTransactionMasterInfo.receiptAmountNoteCredit : 0));
		fnNoteCreditLoad();

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
        if(varPermisosNoPermitirModificarDetalleFactura && isAdmin !== "1" && loadEdicion){
            $('.btnMenus').addClass('hidden');
            $('.btnPlus').addClass('hidden');
            $('.btnAddSelectedItem').addClass('hidden');
            $('#btnDelete').addClass('hidden');
            $('#btnDeleteItem').addClass('hidden');
            $('.txtQuantity').attr('readonly', true);
            $('#txtScanerCodigo').attr('readonly', true);
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
				

				let getData 	= objTableDetail.fnGetData();
				var x_			= getData.filter(item => item[2] === conceptItemID);
				//Rediseño: los productos nuevos se insertan al inicio y desplazan los indices;
				//se vuelve a ubicar la fila por itemID al momento de aplicar el concepto
				if(x_.length > 0)
				objind_ 		= fnGetPosition(x_,getData);
				var cantidad    = x_[0][columnasTableDetail.cantidad];
				var precio    	= x_[0][columnasTableDetail.precio];

				var objConcepto = e;
				var exoneracion = $("#txtCheckApplyExoneracionValue").val();

				if(exoneracion === "0")
				{
					objConcepto1 	= jLinq.from(objConcepto).where(function(obj){ return (obj.name === "IVA"); }).select();
					if( objConcepto1.length > 0 )
					{
						var amount = objConcepto1[0].valueOut;
						objTableDetail.fnUpdate( fnFormatNumber(amount,2), objind_, columnasTableDetail.iva );
					}
					objConcepto2 	= jLinq.from(objConcepto).where(function(obj){ return (obj.name === "TAX_SERVICES"); }).select();
					if( objConcepto2.length > 0 )
					{
						var amount = objConcepto2[0].valueOut;
						objTableDetail.fnUpdate( fnFormatNumber(amount,2), objind_, columnasTableDetail.taxServices );
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

		//Rediseño: sin select2 en esta pantalla, el combo .txtItemSelected ya no se genera en las filas
		//if(varUseMobile === "0")
		//{
		//	var cantidaRow = $(".txtItemSelected").length;
		//	for(var i = 0 ; i < cantidaRow; i++)
		//	{
		//		var x = $($(".txtItemSelected")[i]).find("a").length;
		//		if(x === 0 )
		//			$($(".txtItemSelected")[i]).select2();
		//	}
		//}


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
		//$("#txtZoneID").select2(); //Rediseño: select nativo
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
		//$("#txtMesaID").select2(); //Rediseño: select nativo

	}

	function fnSelectCellMesaDoubleClick(cell,value) {

		$(".custom-table-mesas").find("td").removeClass("selected");
		$(cell).addClass("selected");
		$("#txtMesaID").val( $(cell).data("value") );
		//$("#txtMesaID").select2(); //Rediseño: select nativo
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
		console.info("fnSelectCellInventoryBack");
        $(".custom-table-container-categorias").show();
        $(".custom-table-container-inventory").hide();
    }

	function fnSelectCellInventory(cell) {
		console.info("fnSelectCellInventory");
        $(".item-producto").removeClass('selected');
        $(cell).addClass("selected");
	}

	function fnSelectDoubleCellInventory(cell) {
		
		console.info("fnSelectDoubleCellInventory");
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
					filterResultArray[27] 	= filterResult.Precio;
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
			//Notas de credito: se limpia el monto (el saldo del cliente se conserva)
			$("#txtReceiptAmountNoteCredit").val("0");
			fnNoteCreditRender();

			//Descuento por linea / global: sin detalle no hay descuento ni autorizacion
			if(fnDescuentoPorLineaActivo())
				fnDescuentoReiniciar();
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

		//Notas de credito: solo contado; en credito se bloquea siempre (sin depender de lockPayment)
		var $noteCredit = $("#txtReceiptAmountNoteCredit");
		if(fnNoteCreditEsCredito())
		{
			$noteCredit.val("0").prop("readonly", true).css("background-color","#eee");
		}
		else if(!lockPayment)
		{
			$noteCredit.prop("readonly", false).css("background-color", "");
		}
		fnNoteCreditRender();
	}

	function fnRecalculateDetail(clearRecibo,tipo_calculate, index=-1)
	{
		
		var porcentajeDescuento 						= parseFloat($('#txtPorcentajeDescuento').val()) || 0;
		var totalGeneral 								= 0;
		var despuesDeRecalcularTotalRecibidoIgualCero	= <?php echo getBehavio($company->type, "app_invoice_billing", "despuesDeRecalcularTotalRecibidoIgualCero", "false"); ?>;
		var invoiceTypeCredit 							= false;

		//Descuento por linea / global: calculo con PosmeInvoiceDiscount (IVA sobre el neto)
		if(fnDescuentoPorLineaActivo())
		{
			totalGeneral 		= fnRecalculateDetailDescuento(tipo_calculate, index);
			if(tipo_calculate != "sumarizar")
			{
				var causalSelectDesc 	= $("#txtCausalID").val();
				var causalCreditDesc 	= objCausalTypeCredit.value.split(",");
				//Obtener si la factura es al credito
				for(var i=0;i<causalCreditDesc.length;i++){
					if(causalCreditDesc[i] === causalSelectDesc){
						invoiceTypeCredit = true;
					}
				}
			}
		}

        if(index == -1 && tipo_calculate != "sumarizar" && !fnDescuentoPorLineaActivo())
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

		if(index > -1 && tipo_calculate != "sumarizar" && !fnDescuentoPorLineaActivo())
		{
			var NSSystemDetailInvoice		= objTableDetail.fnGetData();
			var cantidadTemporal 	 		=  $(".txtQuantity")[index].value;
			var priceTemporal  		 		=  $(".txtPrice")[index].value;

			//Actualizar cantidad y precio
			objTableDetail.fnUpdate( cantidadTemporal, index, columnasTableDetail.cantidad );
			objTableDetail.fnUpdate( priceTemporal, index, columnasTableDetail.precio );
			
			//Actualizar la cantidad de sku
			var cantidad 				= parseFloat(NSSystemDetailInvoice[index][columnasTableDetail.cantidad]);
			var skuRatio    			= parseFloat(NSSystemDetailInvoice[index][columnasTableDetail.ratioSku]);
			let skuQuantityBySku 		= cantidad * skuRatio;
			objTableDetail.fnUpdate( skuQuantityBySku, index, columnasTableDetail.skuQuantityBySku );
			
			//Actualizar el subo total
			var precio 					= parseFloat(NSSystemDetailInvoice[index][columnasTableDetail.precio]);
			var subtotal    			= precio * cantidad;
			objTableDetail.fnUpdate( fnFormatNumber(subtotal,2), index, columnasTableDetail.total );
			

			//Actualizar el descuento se guarda el monto del descuento
			var descuento				= subtotal * (porcentajeDescuento / 100);
			objTableDetail.fnUpdate( fnFormatNumber(descuento,2) , index, columnasTableDetail.discount );
			
			//Actualizar el iva se guarda el porcentaje ejemplo : 0.15
			var porcentajeIva 			= parseFloat(NSSystemDetailInvoice[index][columnasTableDetail.iva]);  			//0.15			
			var iva 					= (priceTemporal * cantidadTemporal) * porcentajeIva;
			

			//Actualisar segundo impuesto se gaurda el porcentaje ejemplo: 0.1
			var porcentajeTaxServices	= parseFloat(NSSystemDetailInvoice[index][columnasTableDetail.taxServices]); 	//0.1
			var taxServices 			= (priceTemporal * cantidadTemporal) * porcentajeTaxServices;
			
			
			
			var NSSystemDetailInvoice		= objTableDetail.fnGetData();
			var subtotalGeneral 			= jLinq.from(jLinq.from(NSSystemDetailInvoice).select(function(a){ return parseFloat(a[columnasTableDetail.total]); })).sum().result;			
			var descuento		 			= jLinq.from(jLinq.from(NSSystemDetailInvoice).select(function(a){ return parseFloat(a[columnasTableDetail.discount]); })).sum().result;			
			var ivaGeneral		 			= jLinq.from(jLinq.from(NSSystemDetailInvoice).select(function(a){ 
					return parseFloat(a[columnasTableDetail.iva]) * a[columnasTableDetail.cantidad] * a[columnasTableDetail.precio]; 
			})).sum().result;			
			var serviceGeneral		 		= jLinq.from(jLinq.from(NSSystemDetailInvoice).select(function(a){ 
					return parseFloat(a[columnasTableDetail.taxServices] * a[columnasTableDetail.cantidad] * a[columnasTableDetail.precio]); 
			})).sum().result;			
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
		
		if(tipo_calculate == "sumarizar" && !fnDescuentoPorLineaActivo())
		{

			var NSSystemDetailInvoice		= objTableDetail.fnGetData();
			var subtotalGeneral 			= jLinq.from(jLinq.from(NSSystemDetailInvoice).select(function(a){ return parseFloat(a[columnasTableDetail.total]); })).sum().result;			
			var descuento		 			= jLinq.from(jLinq.from(NSSystemDetailInvoice).select(function(a){ return parseFloat(a[columnasTableDetail.discount]); })).sum().result;			
			var ivaGeneral		 			= jLinq.from(jLinq.from(NSSystemDetailInvoice).select(function(a){ 
					return parseFloat(a[columnasTableDetail.iva]) * a[columnasTableDetail.cantidad] * a[columnasTableDetail.precio]; 
			})).sum().result;			
			var serviceGeneral		 		= jLinq.from(jLinq.from(NSSystemDetailInvoice).select(function(a){ 
					return parseFloat(a[columnasTableDetail.taxServices] * a[columnasTableDetail.cantidad] * a[columnasTableDetail.precio]); 
			})).sum().result;	
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
			
			//Lo que cubren las notas de credito no se pide en efectivo (el vuelto nunca sale de la NC)
			$("#txtReceiptAmount").val(fnFormatNumber(Math.max(totalGeneral - fnNoteCreditAplicable(totalGeneral), 0),2));
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

		// Sincronizar totales con ventana secundaria via localStorage
		try {
			localStorage.setItem('posme_billing_totals', JSON.stringify({
				subTotal: $("#txtSubTotal").val(),
				discount: $("#txtDescuento").val(),
				total: $("#txtTotal").val(),
				timestamp: Date.now()
			}));
		} catch(e) {}
		
	}

	/* ======================================================================
	 * Descuento por linea / global (INVOICE_BILLING_DISCOUNT_BY_ITEM = "true")
	 * Calculo en public/resource/js/posme_invoice_discount.js (mismas reglas que
	 * app/Libraries/core_web_invoice_discount.php, que revalida al guardar).
	 * Col 25 = monto del descuento de la linea, col 27 = % de la linea.
	 * En modo global las cols 25/27 van en 0 (el servidor prorratea).
	 * ====================================================================== */

	/**
	 * Indica si el descuento por linea / global esta activo para la empresa.
	 * @return {boolean}
	 */
	function fnDescuentoPorLineaActivo()
	{
		return String(varParameterInvoiceBillingDiscountByItem) === "true";
	}

	/**
	 * Formatea un % de descuento: 2 decimales, o 4 si los tiene (% calculado desde un monto).
	 * @param {*} pct
	 * @return {string}
	 */
	function fnFormatPctDescuento(pct)
	{
		var valor = PosmeInvoiceDiscount.round4(pct);
		return PosmeInvoiceDiscount.round2(valor) === valor ? fnFormatNumber(valor, 2) : fnFormatNumber(valor, 4);
	}

	/**
	 * Lee las lineas de la tabla de detalle en el formato de PosmeInvoiceDiscount.
	 * El origen se resuelve por linea: si el % corresponde al monto con el bruto actual
	 * se respeta el monto; si cambio cantidad/precio se conserva el % y se recalcula el monto.
	 * @return {Array}
	 */
	function fnDescuentoLeerLineas()
	{
		var D 		= PosmeInvoiceDiscount;
		var datos 	= objTableDetail.fnGetData();
		var lineas 	= [];
		for(var i = 0; i < datos.length; i++)
		{
			var fila 		= datos[i];
			var precio 		= D.num(fila[columnasTableDetail.precio]);
			var cantidad 	= D.num(fila[columnasTableDetail.cantidad]);
			var descPct 	= D.num(fila[columnasTableDetail.discountPercentage]);
			var descValor 	= D.num(fila[columnasTableDetail.discount]);
			lineas.push({
				precio 			: precio,
				cantidad 		: cantidad,
				ivaPct 			: D.num(fila[columnasTableDetail.iva]),
				taxServicesPct 	: D.num(fila[columnasTableDetail.taxServices]),
				descPct 		: descPct,
				descValor 		: descValor,
				origen 			: D.resolverOrigen(precio * cantidad, descPct, descValor)
			});
		}
		return lineas;
	}

	/**
	 * Modo de descuento de la factura: "global" si hay descuento global, "line" si alguna
	 * linea tiene descuento, "none" si no hay descuento (son excluyentes).
	 * @param {Array} lineas
	 * @return {string}
	 */
	function fnDescuentoModo(lineas)
	{
		if(objDescuentoGlobal.pct > 0 || objDescuentoGlobal.monto > 0)
			return "global";
		for(var i = 0; i < lineas.length; i++)
		{
			if(lineas[i].descValor > 0 || lineas[i].descPct > 0)
				return "line";
		}
		return "none";
	}

	/**
	 * Recalcula detalle y resumen con descuento por linea / global.
	 * @param {string} tipo_calculate "sumarizar" = no sincroniza cantidad/precio desde los inputs
	 * @param {number} index fila cambiada (-1 = todas)
	 * @return {number} total de la factura
	 */
	function fnRecalculateDetailDescuento(tipo_calculate, index)
	{
		var D 		= PosmeInvoiceDiscount;
		var datos 	= objTableDetail.fnGetData();
		var i;

		//Sincronizar cantidad y precio desde los inputs (igual que el calculo legado)
		if(tipo_calculate != "sumarizar")
		{
			for(i = 0; i < datos.length; i++)
			{
				if(index > -1 && i != index)
					continue;
				var inputCantidad 	= $(".txtQuantity")[i];
				var inputPrecio 	= $(".txtPrice")[i];
				if(inputCantidad) 	objTableDetail.fnUpdate( inputCantidad.value, i, columnasTableDetail.cantidad );
				if(inputPrecio) 	objTableDetail.fnUpdate( inputPrecio.value, i, columnasTableDetail.precio );
				var skuQuantityBySku = D.num(datos[i][columnasTableDetail.cantidad]) * D.num(datos[i][columnasTableDetail.ratioSku]);
				objTableDetail.fnUpdate( skuQuantityBySku, i, columnasTableDetail.skuQuantityBySku );
			}
		}

		var lineas 			= fnDescuentoLeerLineas();
		var modo 			= fnDescuentoModo(lineas);
		var subtotalBruto 	= D.calcResumen(lineas, "none", 0).subtotal;

		//Global: base = SUB TOTAL bruto sin IVA; si cambio el subtotal se conserva el % y se recalcula el monto
		if(modo === "global" && D.resolverOrigen(subtotalBruto, objDescuentoGlobal.pct, objDescuentoGlobal.monto) === "pct")
			objDescuentoGlobal.monto = D.round2(subtotalBruto * objDescuentoGlobal.pct / 100);

		var resumen 			= D.calcResumen(lineas, modo, objDescuentoGlobal.monto);
		varDescuentoModoActual 	= modo;

		//Actualizar cada linea: total = bruto; col 25/27 solo con descuento por linea (en global van en 0)
		for(i = 0; i < resumen.lineas.length; i++)
		{
			var linea 		= resumen.lineas[i];
			var totalTxt 	= fnFormatNumber(linea.bruto, 2);
			var montoTxt 	= fnFormatNumber(modo === "line" ? linea.desc : 0, 2);
			var pctTxt 		= fnFormatPctDescuento(modo === "line" ? linea.descPct : 0);
			if(String(datos[i][columnasTableDetail.total]) !== totalTxt)
				objTableDetail.fnUpdate( totalTxt, i, columnasTableDetail.total );
			if(String(datos[i][columnasTableDetail.discount]) !== montoTxt)
				objTableDetail.fnUpdate( montoTxt, i, columnasTableDetail.discount );
			if(String(datos[i][columnasTableDetail.discountPercentage]) !== pctTxt)
				objTableDetail.fnUpdate( pctTxt, i, columnasTableDetail.discountPercentage );
		}

		//Resumen: TOTAL = SUB TOTAL - DESC + IVA (sobre el neto) + SERVICIO (sobre el bruto)
		$("#txtSubTotal").val(fnFormatNumber(resumen.subtotal, 2));
		$("#txtIva").val(fnFormatNumber(resumen.iva, 2));
		$("#txtServices").val(fnFormatNumber(resumen.servicio, 2));
		$("#txtDescuento").val(fnFormatNumber(resumen.desc, 2));
		$("#txtPorcentajeDescuento").val(modo === "global" ? fnFormatPctDescuento(objDescuentoGlobal.pct) : "0");
		$("#txtTotal").val(fnFormatNumber(resumen.total, 2));
		$("#txtTotalAlternativo").text(fnFormatNumber(resumen.total, 2));
		$("#txtDiscountMode").val(modo);

		fnDescuentoAplicarBloqueo(modo);
		return resumen.total;
	}

	/**
	 * Bloqueo cruzado: con descuento por linea el global queda readonly; con global, los de linea.
	 * Los inputs de linea bloqueados por permiso (data-bloqueo-permiso="1") no se desbloquean.
	 * @param {string} modo
	 */
	function fnDescuentoAplicarBloqueo(modo)
	{
		$("#txtPorcentajeDescuento, #txtDescuento").prop("readonly", modo === "line");
		$(".txtDiscountItemPct, .txtDiscountItemAmount").each(function(){
			var porPermiso = $(this).attr("data-bloqueo-permiso") === "1";
			$(this).prop("readonly", porPermiso || modo === "global");
		});
	}

	/**
	 * Html del input de descuento de una linea (monto o %).
	 * @param {string} clase  txtDiscountItemAmount | txtDiscountItemPct
	 * @param {string} nombre name del input
	 * @param {*} data valor de la celda
	 * @param {Array} full fila completa
	 * @param {string} etiqueta texto del badge en useMobile=1
	 * @return {string}
	 */
	function fnDescuentoRenderInput(clase, nombre, data, full, etiqueta)
	{
		//Mismas condiciones de solo lectura que la cantidad
		var bloqueoPermiso 	= ((varPermisosNoPermitirEliminarProductosFactura || (varPermisosNoPermitirModificarDetalleFactura && loadEdicion)) && isAdmin !== "1");
		var soloLectura 	= (bloqueoPermiso || varDescuentoModoActual === "global") ? " readonly " : "";
		var valor 			= (data === undefined || data === null || data === "") ? "0" : data;
		var str 			= '<input type="text" inputmode="decimal" class="col-lg-12 txt-numeric '+clase+'" id="'+clase+'Row'+full[2]+'" value="'+valor+'" name="'+nombre+'" data-bloqueo-permiso="'+(bloqueoPermiso ? '1' : '0')+'" style="text-align:right; <?php echo $useMobile == "1" ? 'width: 100%;' : '' ?>" autocomplete="off" '+soloLectura+'/>';
		if (varUseMobile == "1")
			str = str + " <span class='badge badge-inverse' >"+etiqueta+"</span>";
		return str;
	}

	/**
	 * Cambio del descuento de una linea (% o monto): valida, pide clave si hace falta y aplica.
	 * @param {HTMLElement} input
	 */
	function fnDescuentoCambioLinea(input)
	{
		var tr 		= $(input).closest("tr")[0];
		var aPos 	= objTableDetail.fnGetPosition(tr);
		if(aPos === null || aPos === undefined || aPos < 0)
			return;
		fnDescuentoAplicarLinea(aPos, $(input).hasClass("txtDiscountItemPct") ? "pct" : "valor", $(input).val());
	}

	/**
	 * Aplica el descuento de una linea (lo usa el modal de informacion del producto):
	 * valida rango y exclusion, pide clave si hace falta y recalcula.
	 * @param {number} aPos  posicion de la fila en la tabla
	 * @param {string} campo "pct" | "valor"
	 * @param {string} texto valor escrito
	 */
	function fnDescuentoAplicarLinea(aPos, campo, texto)
	{
		var D 			= PosmeInvoiceDiscount;
		var fila 		= objTableDetail.fnGetData(aPos);
		if(!fila)
			return;
		var bruto 		= D.num(fila[columnasTableDetail.precio]) * D.num(fila[columnasTableDetail.cantidad]);

		//Rango: monto en [0, bruto], % en [0, 100]
		var validacion 	= D.validarDescuento({ campo: campo, valor: texto, base: bruto });
		if(!validacion.ok)
		{
			Toast.fire({ icon: "warning", title: validacion.mensaje });
			fnDescuentoRevertirLinea(aPos);
			return;
		}

		//Excluyente con el descuento global
		var valorNuevo 	= D.num(texto);
		if(valorNuevo > 0 && varDescuentoModoActual === "global")
		{
			Toast.fire({ icon: "warning", title: "La factura tiene descuento global; póngalo en 0 para usar descuento por línea" });
			fnDescuentoRevertirLinea(aPos);
			return;
		}

		var calculo 	= D.calcLinea({
			precio 			: fila[columnasTableDetail.precio],
			cantidad 		: fila[columnasTableDetail.cantidad],
			ivaPct 			: fila[columnasTableDetail.iva],
			taxServicesPct 	: fila[columnasTableDetail.taxServices],
			descPct 		: campo === "pct" ? valorNuevo : 0,
			descValor 		: campo === "valor" ? valorNuevo : 0,
			origen 			: campo
		});

		//Sin cambio real (mismo descuento ya aplicado): solo se vuelve a mostrar
		if(
			calculo.descValor === D.round2(fila[columnasTableDetail.discount]) &&
			calculo.descPct === D.round4(fila[columnasTableDetail.discountPercentage])
		)
		{
			fnDescuentoRevertirLinea(aPos);
			return;
		}

		var aplicar = function(){
			objTableDetail.fnUpdate( fnFormatNumber(calculo.descValor, 2), aPos, columnasTableDetail.discount );
			objTableDetail.fnUpdate( fnFormatPctDescuento(calculo.descPct), aPos, columnasTableDetail.discountPercentage );
			fnRecalculateDetail(true, "", aPos);
		};

		if(calculo.descValor > 0 || calculo.descPct > 0)
		{
			varDescuentoModificado = true;
			if(!varDescuentoAutorizado)
			{
				fnDescuentoSolicitarClave(aplicar, function(){ fnDescuentoRevertirLinea(aPos); });
				return;
			}
		}
		aplicar();
	}

	/**
	 * Vuelve a mostrar en la linea el descuento aplicado (datos) y recalcula.
	 * @param {number} aPos
	 */
	function fnDescuentoRevertirLinea(aPos)
	{
		var fila = objTableDetail.fnGetData(aPos);
		if(!fila)
			return;
		objTableDetail.fnUpdate( fila[columnasTableDetail.discount], aPos, columnasTableDetail.discount );
		objTableDetail.fnUpdate( fila[columnasTableDetail.discountPercentage], aPos, columnasTableDetail.discountPercentage );
		fnRecalculateDetail(false, "sumarizar");
	}

	/**
	 * Cambio del descuento global (% o monto) con base = SUB TOTAL bruto sin IVA.
	 * @param {string} campo "pct" | "valor"
	 */
	function fnDescuentoCambioGlobal(campo)
	{
		var D 			= PosmeInvoiceDiscount;
		var texto 		= campo === "pct" ? $("#txtPorcentajeDescuento").val() : $("#txtDescuento").val();
		var subtotal 	= D.calcResumen(fnDescuentoLeerLineas(), "none", 0).subtotal;

		//Rango: monto en [0, subtotal], % en [0, 100]
		var validacion 	= D.validarDescuento({ campo: campo, valor: texto, base: subtotal });
		if(!validacion.ok)
		{
			Toast.fire({ icon: "warning", title: validacion.mensaje });
			fnDescuentoRevertirGlobal();
			return;
		}

		//Excluyente con el descuento por linea
		var valorNuevo 	= D.num(texto);
		if(valorNuevo > 0 && varDescuentoModoActual === "line")
		{
			Toast.fire({ icon: "warning", title: "Hay descuentos por línea; póngalos en 0 para usar descuento global" });
			fnDescuentoRevertirGlobal();
			return;
		}

		//% <-> monto
		var nuevo = { pct: 0, monto: 0 };
		if(campo === "pct")
		{
			nuevo.pct 	= D.round4(valorNuevo);
			nuevo.monto = D.round2(subtotal * nuevo.pct / 100);
		}
		else
		{
			nuevo.monto = D.round2(valorNuevo);
			nuevo.pct 	= subtotal > 0 ? D.round4(nuevo.monto / subtotal * 100) : 0;
		}

		//Sin cambio real: solo se vuelve a mostrar
		if(nuevo.pct === D.round4(objDescuentoGlobal.pct) && nuevo.monto === D.round2(objDescuentoGlobal.monto))
		{
			fnDescuentoRevertirGlobal();
			return;
		}

		var aplicar = function(){
			objDescuentoGlobal = nuevo;
			fnRecalculateDetail(true, "");
		};

		if(nuevo.monto > 0 || nuevo.pct > 0)
		{
			varDescuentoModificado = true;
			if(!varDescuentoAutorizado)
			{
				fnDescuentoSolicitarClave(aplicar, fnDescuentoRevertirGlobal);
				return;
			}
		}
		aplicar();
	}

	/**
	 * Vuelve a mostrar el descuento global aplicado y recalcula.
	 */
	function fnDescuentoRevertirGlobal()
	{
		fnRecalculateDetail(false, "sumarizar");
	}

	/**
	 * Abre el modal de autorizacion del descuento.
	 * @param {Function} fnAceptar se ejecuta cuando el servidor valida la clave
	 * @param {Function} fnCancelar se ejecuta al cancelar
	 */
	function fnDescuentoSolicitarClave(fnAceptar, fnCancelar)
	{
		objDescuentoPendiente = { aceptar: fnAceptar, cancelar: fnCancelar };
		$("#txtClaveDescuento").val("");
		$("#errorMessageClaveDescuento").text("").hide();
		$("#btnAceptarClaveDescuento").removeClass("disabled");
		mostrarModal("ModalAutorizarDescuento");
		setTimeout(function(){ $("#txtClaveDescuento").focus(); }, 200);
	}

	/**
	 * Muestra un error dentro del modal de autorizacion.
	 * @param {string} mensaje
	 */
	function fnDescuentoMostrarErrorClave(mensaje)
	{
		$("#errorMessageClaveDescuento").text(mensaje).show();
	}

	/**
	 * Valida la clave en el servidor (nunca se compara en JS).
	 * POST app_invoice_billing/validateDiscountAuthorization con el campo password;
	 * respuesta JSON {error, message}.
	 */
	function fnDescuentoValidarClave()
	{
		var $btn 	= $("#btnAceptarClaveDescuento");
		var clave 	= $("#txtClaveDescuento").val();
		if($btn.hasClass("disabled"))
			return;
		//La clave en blanco se envia igual: el servidor decide (si INVOICE_BILLING_DISCOUNT_PASSWORD esta vacio no se exige clave)

		$btn.addClass("disabled");
		$("#errorMessageClaveDescuento").text("").hide();

		var parametros = new FormData();
		parametros.append("password", clave);

		fetch('<?php echo base_url(); ?>/app_invoice_billing/validateDiscountAuthorization', {
			method 	: 'POST',
			headers : { 'X-Requested-With': 'XMLHttpRequest' },
			body 	: parametros
		})
		.then(function(res){ return res.json(); })
		.then(function(json){
			//Sin datos: respuesta vacia o sin la propiedad error
			if(!json || typeof json !== "object" || json.error === undefined)
			{
				fnDescuentoMostrarErrorClave("Respuesta no válida del servidor.");
				return;
			}
			//Error: clave incorrecta u otro problema reportado por el servidor
			if(json.error === true || json.error === "true" || json.error === 1 || json.error === "1")
			{
				fnDescuentoMostrarErrorClave(json.message ? json.message : "Clave incorrecta.");
				return;
			}
			//Con datos: autorizado; la clave viaja oculta al guardar y el servidor la revalida
			varDescuentoAutorizado = true;
			$("#txtDiscountAuthorization").val(clave);
			$("#txtClaveDescuento").val("");
			cerrarModal("ModalAutorizarDescuento");
			var pendiente 			= objDescuentoPendiente;
			objDescuentoPendiente 	= null;
			if(pendiente && typeof pendiente.aceptar === "function")
				pendiente.aceptar();
		})
		.catch(function(){
			fnDescuentoMostrarErrorClave("No se pudo validar la clave. Intente de nuevo.");
		})
		.finally(function(){
			$btn.removeClass("disabled");
		});
	}

	/**
	 * Validacion del descuento antes de guardar.
	 * @return {boolean|string} true = ok, false = invalido, "clave" = falta autorizacion
	 */
	function fnDescuentoValidarAntesDeGuardar()
	{
		var D 			= PosmeInvoiceDiscount;
		var lineas 		= fnDescuentoLeerLineas();
		var hayLinea 	= false;
		for(var i = 0; i < lineas.length; i++)
		{
			var bruto 		= lineas[i].precio * lineas[i].cantidad;
			var valPct 		= D.validarDescuento({ campo: "pct", valor: lineas[i].descPct, base: bruto });
			var valMonto 	= D.validarDescuento({ campo: "valor", valor: lineas[i].descValor, base: bruto });
			if(!valPct.ok || !valMonto.ok)
			{
				Toast.fire({ icon: "warning", title: "Línea " + (i + 1) + ": " + (!valPct.ok ? valPct.mensaje : valMonto.mensaje) });
				return false;
			}
			if(lineas[i].descValor > 0 || lineas[i].descPct > 0)
				hayLinea = true;
		}

		var subtotal 		= D.calcResumen(lineas, "none", 0).subtotal;
		var valGlobalPct 	= D.validarDescuento({ campo: "pct", valor: objDescuentoGlobal.pct, base: subtotal });
		var valGlobalMonto 	= D.validarDescuento({ campo: "valor", valor: objDescuentoGlobal.monto, base: subtotal });
		if(!valGlobalPct.ok || !valGlobalMonto.ok)
		{
			Toast.fire({ icon: "warning", title: "Descuento global: " + (!valGlobalPct.ok ? valGlobalPct.mensaje : valGlobalMonto.mensaje) });
			return false;
		}

		var hayGlobal = (objDescuentoGlobal.pct > 0 || objDescuentoGlobal.monto > 0);
		if(hayLinea && hayGlobal)
		{
			Toast.fire({ icon: "warning", title: "No puede combinar descuento por línea y descuento global" });
			return false;
		}

		if((hayLinea || hayGlobal) && varDescuentoModificado && !varDescuentoAutorizado)
			return "clave";

		return true;
	}

	/**
	 * Limpia descuento global y autorizacion (nueva factura, otra factura o detalle limpio).
	 */
	function fnDescuentoReiniciar()
	{
		objDescuentoGlobal 		= { pct: 0, monto: 0 };
		varDescuentoAutorizado 	= false;
		varDescuentoModificado 	= false;
		objDescuentoPendiente 	= null;
		$("#txtDiscountAuthorization").val("");
		$("#txtClaveDescuento").val("");

		var modo = "none";
		if(objTableDetail && typeof objTableDetail.fnGetData === "function")
			modo = fnDescuentoModo(fnDescuentoLeerLineas());
		varDescuentoModoActual = modo;
		$("#txtDiscountMode").val(modo);
		fnDescuentoAplicarBloqueo(modo);
	}

	/**
	 * Prepara la pantalla para el descuento por linea: clase html (CSS de tablet),
	 * inputs ocultos del form y desbloqueo de #txtPorcentajeDescuento / #txtDescuento.
	 */
	function fnDescuentoInicializar()
	{
		if(!fnDescuentoPorLineaActivo())
			return;

		//Inputs ocultos dentro del form (con el parametro en false no se crean: el servidor usa la logica legada)
		var $form = $("#form-new-invoice");
		if($("#txtDiscountMode").length == 0)
			$form.append('<input type="hidden" name="txtDiscountMode" id="txtDiscountMode" value="none" />');
		if($("#txtDiscountAuthorization").length == 0)
			$form.append('<input type="hidden" name="txtDiscountAuthorization" id="txtDiscountAuthorization" value="" />');

		$("#txtPorcentajeDescuento, #txtDescuento").prop("readonly", false);
		$("#txtDescuento").attr("inputmode", "decimal").attr("autocomplete", "off");
		fnDescuentoAplicarBloqueo(varDescuentoModoActual);
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
	
		if($("#txtStatusIDOld").val() ==  varStatusInvoiceAplicado)
				return;
			
		if($("#txtStatusIDOld").val() ==  varStatusInvoiceRegistrado)
				return;
			
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
		//Saldo de notas de credito del cliente elegido (moneda de la factura)
		fnNoteCreditLoad();
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
		//Rediseño: selects nativos, sin select2
		//if(varUseMobile !== "1")
		//{
		//	$("#txtCustomerCreditLineID").select2();
		//	$("#txtCausalID").select2();
		//	$("#txtPeriodPay").select2();
		//}

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


	
	/**
	 * Formato visual del modal: miles con coma y dos decimales (no altera el valor).
	 * @param {number} valor
	 * @return {string}
	 */
	function fnInfoProductoMoneda(valor)
	{
		return PosmeInvoiceDiscount.num(valor).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
	}

	/**
	 * Modal de informacion: nombre, codigo, cantidad, precio y sub total de la linea seleccionada.
	 */
	function fnInfoProductoCargarResumen()
	{
		let fila 	= selectedDataInfoProducto;
		let nombre 	= $('<div>').html(String(fila[columnasTableDetail.descripcion] || '')).text();
		let codigo 	= $('<div>').html(String(fila[3] || '')).text();
		$('#pmInfoNombre').text(nombre);
		$('#pmInfoCodigo').text(codigo !== '' ? 'Código: ' + codigo : '');
		$('#pmInfoCantidad').text(fnInfoProductoMoneda(fila[columnasTableDetail.cantidad]));
		fnInfoProductoCalcularDescuento(null);
	}

	/**
	 * Modal de informacion: muestra el descuento vigente de la linea y lo bloquea si
	 * la factura tiene descuento global o el usuario no puede modificar el detalle.
	 */
	function fnInfoProductoCargarDescuento()
	{
		let $seccion 			= $('#pmInfoDescuento');
		varInfoDescuentoOrigen 	= null;
		if(!fnDescuentoPorLineaActivo())
		{
			$seccion.addClass('hidden');
			return;
		}
		$seccion.removeClass('hidden');

		let D 	 	= PosmeInvoiceDiscount;
		let fila 	= selectedDataInfoProducto;
		$('#txtInfoDescuentoPct').val(D.round2(D.num(fila[columnasTableDetail.discountPercentage])));
		$('#txtInfoDescuentoMonto').val(fnFormatNumber(D.num(fila[columnasTableDetail.discount]), 2));

		//Mismas condiciones de solo lectura que la cantidad, mas la exclusion con el descuento global
		let bloqueoPermiso 	= ((varPermisosNoPermitirEliminarProductosFactura || (varPermisosNoPermitirModificarDetalleFactura && loadEdicion)) && isAdmin !== "1");
		let mensaje 		= '';
		if(varDescuentoModoActual === "global")
			mensaje = 'La factura tiene descuento global. Póngalo en 0 para descontar por producto.';
		else if(bloqueoPermiso)
			mensaje = 'No tiene permiso para modificar el detalle de la factura.';

		$('#txtInfoDescuentoPct, #txtInfoDescuentoMonto').prop('disabled', mensaje !== '');
		$seccion.toggleClass('pm-info-descuento-bloqueado', mensaje !== '');
		fnInfoProductoAviso(mensaje, 'info');
		fnInfoProductoCalcularDescuento(null);
	}

	/**
	 * Modal de informacion: recalcula sub total, descuento y neto con el precio elegido.
	 * @param {string|null} origen "pct" | "valor" | null (solo refresca el resumen)
	 */
	function fnInfoProductoCalcularDescuento(origen)
	{
		let D 			= PosmeInvoiceDiscount;
		let fila 		= selectedDataInfoProducto;
		let precio 		= D.num($('#selectPrecio').val() || fila[columnasTableDetail.precio]);
		let cantidad 	= D.num(fila[columnasTableDetail.cantidad]);
		let bruto 		= D.round2(precio * cantidad);
		$('#pmInfoPrecio').text(fnInfoProductoMoneda(precio));
		$('#pmInfoSubtotal').text(fnInfoProductoMoneda(bruto));

		if(!fnDescuentoPorLineaActivo())
			return;

		let pct 	= D.num($('#txtInfoDescuentoPct').val());
		let monto 	= D.num($('#txtInfoDescuentoMonto').val());
		if(origen === 'pct')
		{
			monto = D.round2(bruto * pct / 100);
			$('#txtInfoDescuentoMonto').val(fnFormatNumber(monto, 2));
		}
		else if(origen === 'valor')
		{
			pct = bruto > 0 ? D.round2(monto / bruto * 100) : 0;
			$('#txtInfoDescuentoPct').val(pct);
		}

		$('#pmInfoNeto').text(fnInfoProductoMoneda(D.round2(bruto - monto)));

		//Validacion en vivo del rango
		if($('#txtInfoDescuentoPct').prop('disabled'))
			return;
		let validacion = D.validarDescuento({ campo: 'valor', valor: monto, base: bruto });
		if(validacion.ok && pct > 100)
			validacion = { ok: false, mensaje: 'El porcentaje no puede ser mayor a 100%' };
		fnInfoProductoAviso(validacion.ok ? '' : validacion.mensaje, 'error');
	}

	/**
	 * Modal de informacion: muestra u oculta el aviso de la seccion de descuento.
	 * @param {string} mensaje vacio = ocultar
	 * @param {string} tipo    "info" | "error"
	 */
	function fnInfoProductoAviso(mensaje, tipo)
	{
		let $aviso = $('#pmInfoDescuentoAviso');
		$aviso.text(mensaje)
			.toggleClass('hidden', mensaje === '')
			.toggleClass('pm-info-aviso-error', tipo === 'error');
	}

	function fnAceptarModalInfoProducto(){
        //Descuento de la linea: se valida antes de cerrar el modal
        let aplicarDescuento 	= fnDescuentoPorLineaActivo() && !$('#txtInfoDescuentoPct').prop('disabled');
        let origenDescuento 	= varInfoDescuentoOrigen === 'pct' ? 'pct' : 'valor';
        let valorDescuento 		= origenDescuento === 'pct' ? $('#txtInfoDescuentoPct').val() : $('#txtInfoDescuentoMonto').val();
        if(aplicarDescuento)
        {
            fnInfoProductoCalcularDescuento(null);
            if(!$('#pmInfoDescuentoAviso').hasClass('hidden'))
                return;
        }

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

        //Aplica el descuento con el precio ya actualizado (valida, pide clave si hace falta y recalcula)
        if(aplicarDescuento && varInfoDescuentoOrigen !== null)
            fnDescuentoAplicarLinea(selectedFilaInfoProducto, origenDescuento, valorDescuento);
    }

	function fnAceptarModalBackToList()
	{
		cerrarModal('ModalBackToList');
		mostarModalPersonalizado('Regresando a la lista principal...')
		window.location.href = '<?php echo base_url(); ?>/app_invoice_billing/index';
	}

	function fnAceptarModalDeleteInvoice(comments){
		comments = (typeof comments === 'undefined') ? '' : comments;
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
					transactionMasterID : objTransactionMaster.transactionMasterID,
					comments 			: comments
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
			var ocultarZonaInParameterRestaurante = <?php echo getBahavioSession($company->type,'app_invoice_billing','jsOcultarZonaInParameterRestaurante','false',$objListCompanyPageSetting) ?>;			
			if(ocultarZonaInParameterRestaurante == false)
			{
				$("#mySidebarZona").css("width","100%");
				$("#mySidebarZona").removeClass("hidden");
			}
			
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

		if(varParameterOcultarCalendarPickerNextVisit == "false")
		{
			$('#txtNextVisit').datepicker({format:"yyyy-mm-dd"});
		}

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
								if((varPermisosNoPermitirEliminarProductosFactura || (varPermisosNoPermitirModificarDetalleFactura && loadEdicion)) && isAdmin !== "1"){
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


								//Rediseño: ya no se genera el combo txtItemSelected (una sola opcion, el backend no lo lee)
								//el nombre del producto se muestra siempre en el input de texto
								var classHiddenTex 		= "";
								let strFiled 		= "";
								if (varUseMobile === "1")
								{
									strFiled        += '<label class="col-lg-12" style="text-align:right; <?php echo $useMobile == "1" ? 'width: 100%;' : '' ?>">Descripción: '+full[4]+'</label>';
									strFiled 		+= '<input type="hidden" name="txtTransactionDetailName[]" id="txtTransactionDetailName'+full[2]+'"  value="'+full[4]+'" '+NameStatus+' />';
								}
								else
								{
									strFiled 		+= '<input type="text" name="txtTransactionDetailName[]" id="txtTransactionDetailName'+full[2]+'"  class="col-lg-12 pm-item-name '+classHiddenTex+'" style="text-align:left; <?php echo $useMobile == "1" ? 'width: 100%;' : '' ?>" value="'+full[4]+'" title="'+String(full[4]).replace(/"/g,'&quot;')+'" '+NameStatus+' />';
								}

								return strFiled;


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
								if ((varPermisosNoPermitirEliminarProductosFactura || (varPermisosNoPermitirModificarDetalleFactura && loadEdicion)) && isAdmin !== "1"){
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

									var ocultarBtnAgregar = "";
									if(varPermisosNoPermitirModificarDetalleFactura && isAdmin !== "1" && loadEdicion){
										ocultarBtnAgregar = "hidden";
									}

									if(varParameterINVOICE_BILLING_SELECTITEM == "true")
									{
										str    	= str + '' +
										'<button type="button" class="btn btn-warning btnAddSelectedItem '+ocultarBtnAgregar+'"><span class="icon16 i-archive"></span> </button>';
									}
									var ocultarBoton="";
									if((varPermisosNoPermitirEliminarProductosFactura || (varPermisosNoPermitirModificarDetalleFactura && loadEdicion)) && isAdmin !== "1"){
										ocultarBoton="hidden";
									}

									str    	= str + '' +
									'<button type="button" class="btn btn-primary btnMenus '+ ocultarBoton +'"><span class="icon16 i-minus"></span> </button>';

									str    	= str + '' +
									'<button type="button" class="btn btn-primary btnPlus '+ ocultarBoton +'"><span class="icon16 i-plus"></span> </button>';



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
							"aTargets"		: [ columnasTableDetail.discount ],//Descuento (monto de la linea)
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
						{
							"aTargets"		: [ columnasTableDetail.discountPercentage ],//% de descuento de la linea
							"bVisible"		: true,
							"sClass"		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtDiscountPercentageByItem[]" />';
							}
						},

			]
		});

		//Descuento por linea / global: inputs ocultos, clase html y desbloqueo del descuento global
		fnDescuentoInicializar();

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
		if(varPermisosNoPermitirModificarDetalleFactura && isAdmin !== "1" && loadEdicion){
			$('.btnMenus').addClass('hidden');
			$('.btnPlus').addClass('hidden');
			$('.btnAddSelectedItem').addClass('hidden');
			$('#btnDelete').addClass('hidden');
			$('#btnDeleteItem').addClass('hidden');
			$('.txtQuantity').attr('readonly', true);
			$('#txtScanerCodigo').attr('readonly', true);
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

