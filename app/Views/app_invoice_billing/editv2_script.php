<script>	
	class clsTransactionMaster {		  
		  constructor() {
				this.companyID						= 0;
				this.transactionID					= 0;
				this.transactionMasterID			= 0;				
				
				this.amount 						= 0;					
				this.transactionOn 					= moment().format("YYYY-MM-DD");
				this.createdOn						= moment().format("YYYY-MM-DD");				
				this.transactionNumber				= "FAC00000000"; 
				this.createdBy						= 0;	
				this.sourceWarehouseID				= 0;					
				this.typePriceID					= 0;
				this.zoneID							= 0;
				
				this.statusID 						= 66; 	//REGISTRADO
				this.transactionCausalID			= 21; 	//CONTADO				
				this.entityID						= 13;	//CLIENTE DEFAULT
				this.currencyID						= 1;	//CORDOBA
				
				
				this.reference1						= 0;	
				this.reference2						= 0;
				this.reference3						= 0;
				this.reference4						= 0;				
				this.referenceClientName			= "";
				this.referenceClientIdentifier		= "";
				this.amount 						= 0;					
				this.receiptAmount					= 0;
				this.receiptAmountDol				= 0;
				this.note 							= "";
				
				
				this.transactionMasterDetailID 		= [];
				this.itemID							= [];
				this.quantity						= [];
				this.price							= [];
				this.subtotal						= [];
				this.iva							= [];
				this.lote							= [];
				this.vencimiento					= [];
				this.sku							= [];
				
				
		  }
	};

	//crear la cache intervalo 	
	var objTransactionMaster 	= new clsTransactionMaster();	
	var objListaProductosStore 	= localStorage.getItem("objListaProductos");		
	objListaProductos 			= JSON.parse(objListaProductosStore);
	
	var objListaProductosCaterogiraStore 	= localStorage.getItem("objListaProductosCategorias");		
	objListaProductosCategorias 			= JSON.parse(objListaProductosCaterogiraStore);
	
	var objListaClienteStore 	= localStorage.getItem("objListaClientes");		
	objListaClientes 			= JSON.parse(objListaClienteStore);
	
	var objListaClienteLineStore 	= localStorage.getItem("objListaClientesLine");		
	objListaClientesLine 			= JSON.parse(objListaClienteLineStore);
	
	var varConstKeyCurrencyDolares						= 2;
	var varConstKeyCurrencyCordoba						= 1;
	var varConstKeyContado								= 21;
	var varConstKeyCredito								= 22;
	var varConstKeyEstadoRegistrado						= 66;
	var varConstKeyEstadoAplicada						= 67;
	var varConstSkuUnidad								= 78;
	var varConstSiteUrl 								= "<?php echo base_url(); ?>/";
	var varConstComponentItem 							= "<?php echo $objComponentItem->componentID; ?>";	
	var varConstComponentItemCategory					= "<?php echo $objComponentItemCategory->componentID; ?>";	
	var varConstComponentCustomer						= "<?php echo $objComponentCustomer->componentID; ?>";
	var varConstListPrice								= "<?php echo $objListPrice->listPriceID; ?>";
	var varConstEsPermitidoFacturarEnZero				= '<?php echo $objParameterInvoiceBillingQuantityZero; ?>';
	var varConstUrlPrinter								= '<?php echo $urlPrinterDocument; ?>';
	var varConstParameterInvoiceBillingPrinterDirect			= '<?php echo $objParameterInvoiceBillingPrinterDirect; ?>';	
	var varConstParameterInvoiceBillingPrinterDirectUrl			= '<?php echo $objParameterInvoiceBillingPrinterDirectUrl; ?>';	
	var varConstParameterInvoiceBillingPrinterDirectCocinaUrl	= '<?php echo $objParameterInvoiceBillingPrinterDirectCocinaUrl; ?>';	
	var varConstListaDePermisos							= JSON.parse('<?php echo json_encode($objListaPermisos); ?>');
	
	
	var varConstTransactionMasterDetail 				= JSON.parse('<?php echo json_encode($objTransactionMasterDetail); ?>');	
	var varConstTransactionMasterDetailWarehouse		= JSON.parse('<?php echo json_encode($objTransactionMasterDetailWarehouse); ?>');	
	var varConstTransactionMasterDetailConcept 			= JSON.parse('<?php echo json_encode($objTransactionMasterDetailConcept); ?>');	
	var varConstTransactionMaster 						= JSON.parse('<?php echo json_encode($objTransactionMaster); ?>');	
	var varConstTransactionMasterInfo					= JSON.parse('<?php echo json_encode($objTransactionMasterInfo); ?>');	
	
	
	var varConstTransactionCausalID						= <?php echo $objTransactionMaster == null ? 0 : $objTransactionMaster->transactionCausalID; ?>;	
	var varConstCustomerCrediLineID						= <?php echo $objTransactionMaster == null ? 0 : $objTransactionMaster->reference4; ?>;	
	
	var varConstEntityID 								= <?php echo $objTransactionMaster == null ? 0 : $objTransactionMaster->entityID; ?>;
	var varConstCompanyID								= <?php echo $objTransactionMaster == null ? 0 : $objTransactionMaster->companyID;?>;
	var varConstTransactionID							= <?php echo $objTransactionMaster == null ? 0 : $objTransactionMaster->transactionID;?>;
	var varConstTransactionMasterID						= <?php echo $objTransactionMaster == null ? 0 : $objTransactionMaster->transactionMasterID;?>;
	var varConstReceipAmountCordoba						= <?php echo $objTransactionMasterInfo == null ? 0 : $objTransactionMasterInfo->receiptAmount; ?>;
	var varConstReceipAmountDolares						= <?php echo $objTransactionMasterInfo == null ? 0 : $objTransactionMasterInfo->receiptAmountDol; ?>;
	
	var varConstExchangeRate							= <?php echo $exchangeRate; ?>;
	var varConstClienteName								= '<?php echo $objNaturalDefault != null ? strtoupper($objCustomerDefault->customerNumber . " ". $objNaturalDefault->firstName . " ". $objNaturalDefault->lastName ) : strtoupper($objCustomerDefault->customerNumber." ".$objLegalDefault->comercialName); ?>';	
	var varConstClienteID								= '<?php echo $objNaturalDefault != null ? $objNaturalDefault->entityID : $objCustomerDefault->entityID ?>';	
	var varConstCausales								= JSON.parse('<?php echo json_encode($objCaudal); ?>');	
	var varConstZonas									= JSON.parse('<?php echo json_encode($objListZone); ?>');	
	var varConstTiposPrecio								= JSON.parse('<?php echo json_encode($objListTypePrice); ?>');		
	var varConstBodegas									= JSON.parse('<?php echo json_encode($objListWarehouse); ?>');	
	var varConstClienteDefault							= JSON.parse('<?php echo json_encode($objCustomerDefault); ?>');	
	var varConstMonedas									= JSON.parse('<?php echo json_encode($listCurrency); ?>');	
	var varConstProveedores								= JSON.parse('<?php echo json_encode($listProvider); ?>');	
	var varConstEstados									= JSON.parse('<?php echo json_encode($objListWorkflowStageAll); ?>');	
	var varConstCategoryID								= 0;
	var varConstComando 								= "";
	var varConstComandoNumerico							= "";
	
	var varObjDataTableCustomer 						= {};
	var varObjDataTableProducto							= {};
	var varConstEsPermitidoModificarPrecioDeProducto 	= 
		jLinq.from(varConstListaDePermisos).where(function(obj){ 
			return obj.display == "ES_PERMITIDO_MODIFICAR_PRECIO_EN_FACTURACION"}
		).select().length > 0 ?
			true:
			false;
	
	
	
	
	
	$(document).on("click","#btnRegresar",function(){
		//iniciar carga
		$.blockUI({
			message: '<div class="spinner-border text-primary" role="status"></div>',
			timeout: 1000,
			css: {
			  backgroundColor: 'transparent',
			  border: '0'
			},
			overlayCSS: {
			  backgroundColor: '#fff',
			  opacity: 0.8
			}
		});
		
		window.location = varConstSiteUrl+"app_invoice_billing/index";
		
	});
	
	$(document).on("click","#btnAgregarItemFactura",function(){
		
		var nombreProducto 	= $("#txtSelectedProductoMovil").text();
		var itemID 			= $("#txtSelectedProductoMovil").val();
		var cantidad 		= parseInt($("#txtSelectedCantidadMovil").val());
		var tipoPrecioID	= $("#txtTiposDePrecios").val();
		var warehouseID    	= $("#txtBodegasOrigen").val();
		
		
		
		fnRenderItemInInvoice(0,nombreProducto,itemID,cantidad,tipoPrecioID,warehouseID,0);
		//varConstComando 			= "";
		//varConstComandoNumerico 	= "0";
		fnCalcularDetalleFactura();
		
	});
	
	$(document).on("click",".btnProductosShowGrid",function(){
		//Render productos
		createTableProductos();	
		
		//Mostrar Ventana
		$("#fullscreenModal2").modal("show");	
	});
	
	$(document).on("click","#btnSeleccionarProducto",function(){
		
		//Tomar los seleccionados
		var itemIdArray     	  = [];
		var itemIdArrayNombre     = [];
		var resultItem   	= varObjDataTableProducto.rows((idx,data,node) => { 
			var uv = $(node).find("input[type='checkbox'").val();
			var uc = $(node).find("input[type='checkbox'").is(':checked');
			if(uc){				
				itemIdArray.push(data.itemID);
				itemIdArrayNombre.push(data.Nombre);
			}
		}).data();
		
		
		var cantidad 		= parseInt(varConstComandoNumerico) * parseInt( (varConstComando+"1") );
		var tipoPrecioID	= $("#txtTiposDePrecios").val();
		var warehouseID    	= $("#txtBodegasOrigen").val();
		
		
		
		//validar comando numerico y comando operativo
		if(varConstComandoNumerico == "" || varConstComando == ""){
			$(".tituloTostas").text("Error");
			$(".timeTostas").text("");
			$(".cuerpoTostas").text("Seleccione la cantidad y la operacion");
			var  selectedType = "bg-warning";
			var  selectedAnimation = "animate__swing";			
			var  toastAnimationExample = document.querySelector('.toast-ex-error'); 			
			
			toastAnimation = new bootstrap.Toast(toastAnimationExample);
			
			toastAnimation.show();
			return;
		}
		

		//Agregarlos a la factura
		for(var i = 0 ; i < itemIdArray.length; i ++ ){
			
			var nombreProducto 	= itemIdArrayNombre[i];				
			var itemID 			= itemIdArray[i];			
			
			fnRenderItemInInvoice(0,nombreProducto,itemID,cantidad,tipoPrecioID,warehouseID,0);
			//varConstComando 		  = "";
			//varConstComandoNumerico = "0";
			fnCalcularDetalleFactura();
			
		}
		
		
		
		
	});
	
	
	$(document).on("click","#btnCodigoBarra",function(e){	
		$("#txtCodigoBarra").val("");
	});
	
	$(document).on("keypress","#txtCodigoBarra",function(e){			
		 var code = e.keyCode || e.which;
		 if(code != 13) { 
			 return;
		 }		 
		 
		 //Buscar normal
		 var barrat = $(this).val();
		 var objSerchProducto = jLinq.from(objListaProductos).where(function(obj){ 
			var result = 
			parseInt(obj.warehouseID) == parseInt($("#txtBodegasOrigen").val()) && 
			parseInt(obj.typePriceID) == parseInt($("#txtTiposDePrecios").val()) && 
			obj.Barra == barrat;
		
		
			return result;
		
		}).select();
		
		//Buscar por codigo por defecto
		if(objSerchProducto.length == 0){
			
			barrat 			 =  "BITT" + ("00000000"+barrat).substr(("00000000"+barrat).length - 8 ,8);				
			objSerchProducto = jLinq.from(objListaProductos).where(function(obj){ 
						var result = 
						parseInt(obj.warehouseID) == parseInt($("#txtBodegasOrigen").val()) && 
						parseInt(obj.typePriceID) == parseInt($("#txtTiposDePrecios").val()) && 
						obj.Barra == barrat;
					
					
						return result;
					
			}).select();
			
		}
		
		if(objSerchProducto.length == 0)
		return;
	
	
		objSerchProducto = objSerchProducto[0];		
		var tipoPrecioID	= $("#txtTiposDePrecios").val();
		var warehouseID    	= $("#txtBodegasOrigen").val();		

		//Agregarlos a la factura
		var nombreProducto 	= objSerchProducto.Nombre;			
		var itemID 			= objSerchProducto.itemID;			
		
		fnRenderItemInInvoice(0,nombreProducto,itemID,1,tipoPrecioID,warehouseID,0);
		//varConstComando 		  = "";
		//varConstComandoNumerico = "0";
		fnCalcularDetalleFactura();
			 
	});
	
	$(document).on("change","#txtSelectedCategoriaMovil",function(){			
	    varConstCategoryID  = $(this).val();
		fnRenderProductos();
	});
	
	$(document).on("change","#txtReferenceClientName",function(){			
			objTransactionMaster.referenceClientName = $(this).val();	
	});
	$(document).on("change","#txtReferenceClientIdentifier",function(){			
			objTransactionMaster.referenceClientIdentifier = $(this).val();	
	});
	$(document).on("change","#txNote",function(){			
			objTransactionMaster.note = $(this).val();	
	});
	$(document).on("change","#txtReceiptAmount",function(){			
			objTransactionMaster.receiptAmount = $(this).val();	
	});	
	$(document).on("change","#txtCredintLine",function(){			
			objTransactionMaster.reference4 = $(this).val();	
	});
	$(document).on("change","#txtReceiptAmountDol",function(){			
			objTransactionMaster.receiptAmountDol = $(this).val();	
	});
	$(document).on("change","#txtChangeReceipt",function(){						
	});
	$(document).on("change",".invoice-item-qty",function(){		
		var index 								= $(this).data("index");
		objTransactionMaster.quantity[index] 	= $(this).val();
		fnCalcularDetalleFactura();
	});
	$(document).on("change",".invoice-item-price",function(){						
		var index 								= $(this).data("index");
		objTransactionMaster.price[index] 		= $(this).val();
		fnCalcularDetalleFactura();
	});
	
	$(document).on("click","#btnContado",function(){
		objTransactionMaster.transactionCausalID = varConstKeyContado;
	});
	
	$(document).on("click","#btnCredito",function(){
		objTransactionMaster.transactionCausalID = varConstKeyCredito;
	});
	
	$(document).on("click","#btnCordoba",function(){
		objTransactionMaster.currencyID = varConstKeyCurrencyCordoba;
	});
	
	$(document).on("click","#btnDolares",function(){
		objTransactionMaster.currencyID = varConstKeyCurrencyDolares;
	});
	
	$(document).on("click","#btnAceptarPago",function(){		

			if(objTransactionMaster.amount <= 0){
				//Notificar
				$(".tituloTostas").text("Error");
				$(".timeTostas").text("");
				$(".cuerpoTostas").text("No hay producos en el detalle");
				var  toastAnimationExample 	= document.querySelector('.toast-ex-error');
				toastAnimation 				= new bootstrap.Toast(toastAnimationExample);		
				toastAnimation.show();			
				return;
			}
			
			if(objTransactionMaster.transactionMasterID == 0){
				//Notificar
				$(".tituloTostas").text("Error");
				$(".timeTostas").text("");
				$(".cuerpoTostas").text("Salvar y luego pagar");
				var  toastAnimationExample 	= document.querySelector('.toast-ex-error'); 							
				toastAnimation 				= new bootstrap.Toast(toastAnimationExample);		
				toastAnimation.show();			
				return;
			}
			
			if( parseInt(objTransactionMaster.transactionCausalID) == parseInt(varConstKeyCredito) && parseInt(objTransactionMaster.reference4) == 0 ){
				//Notificar
				$(".tituloTostas").text("Error");
				$(".timeTostas").text("");
				$(".cuerpoTostas").text("Si la factura es de credito seleccione la linea");
				var  toastAnimationExample 	= document.querySelector('.toast-ex-error'); 			
				toastAnimation 				= new bootstrap.Toast(toastAnimationExample);		
				toastAnimation.show();			
				return;
			}
			
			if( parseInt(objTransactionMaster.statusID) == parseInt(varConstKeyEstadoAplicada) ){
				//Notificar
				$(".tituloTostas").text("Error");
				$(".timeTostas").text("");
				$(".cuerpoTostas").text("La factura ya esta aplicada");
				var  toastAnimationExample 	= document.querySelector('.toast-ex-error'); 			
				toastAnimation 				= new bootstrap.Toast(toastAnimationExample);		
				toastAnimation.show();			
				return;
			}
		
			
			var fn = (async function(){	
				//Cambiar de estado a la factura
				objTransactionMaster.statusID = varConstKeyEstadoAplicada;
				
				await fnSendFactura();		
				
				await fnImprimirFactura();
				
				objTransactionMaster 	= new clsTransactionMaster();	 
				
				//Inciaializar los valores de la factura
				fnInitFactura();		
			
				//Renderizar la factura
				fnRenderFactura();	
			});			
			fn();
	});
	
	$(document).on("click","#btnNuevaFactura",function(){
			var oldBodega 			= objTransactionMaster.sourceWarehouseID;
			var oldTipoPrecio		= objTransactionMaster.typePriceID;
			
			
			objTransactionMaster 	= new clsTransactionMaster();	 			
			//Inciaializar los valores de la factura
			fnInitFactura();		
		
			objTransactionMaster.typePriceID					= oldTipoPrecio;		//TIPO DE PRECIO
			objTransactionMaster.sourceWarehouseID				= oldBodega;			//BODEGA		
		
		
			//Renderizar la factura
			fnRenderFactura();
		
	});
	
	
	$(document).on("click","#btnAceptarSave",function(){
		
			if(objTransactionMaster.amount <= 0){
				//Notificar
				$(".tituloTostas").text("Error");
				$(".timeTostas").text("");
				$(".cuerpoTostas").text("No hay producos en el detalle");
				var  toastAnimationExample 	= document.querySelector('.toast-ex-error'); 			
				toastAnimation 				= new bootstrap.Toast(toastAnimationExample);		
				toastAnimation.show();			
				return;
			}
			
			if( parseInt(objTransactionMaster.statusID) == parseInt(varConstKeyEstadoAplicada) ){
				//Notificar
				$(".tituloTostas").text("Error");
				$(".timeTostas").text("");
				$(".cuerpoTostas").text("La factura ya esta aplicada");
				var  toastAnimationExample 	= document.querySelector('.toast-ex-error'); 			
				toastAnimation 				= new bootstrap.Toast(toastAnimationExample);		
				toastAnimation.show();			
				return;
			}
		
		
			
			var fn = (async function(){				
				await fnSendFactura();	
				fnRenderFactura();
			});			
			fn();			
			
	});
	
	$(document).on("change","#txtTiposDePrecios",function(){
			objTransactionMaster.typePriceID 		= $(this).val();
	});
	
	$(document).on("change","#txtBodegasOrigen",function(){
			objTransactionMaster.sourceWarehouseID 	= $(this).val();
			
			fnRenderProductos();
			fnLimpiarDetalleFactura();
			fnRenderFactura();
			fnCalcularDetalleFactura();
			
	});
	$(document).on("click","#btnPagarShowModal",function(){
			fnRenderFactura();
	});
	
	
	$(document).on("click","#btnSeleccionarCliente",function(){
		
		
		var resultClienteID = 0;
		var resultCliente 	= varObjDataTableCustomer.rows((idx,data,node) => { 
			
			//console.info(idx);  
			//console.info(data);  
			//console.info(node);  
			var uv = $(node).find("input[type='checkbox'").val();
			var uc = $(node).find("input[type='checkbox'").is(':checked');
			
			
			if(uc){
				resultClienteID = data.entityID;
			}
			
			
		}).data();
		
		
		objTransactionMaster.entityID = resultClienteID;
	});
	
	$(document).on("click",".btn-delete-row-factura",function(){
		var row = $(this).data("delete");
		$(".row-factura[data-rowid='"+row+"']").remove();
		fnCalcularDetalleFactura();
	});
	
	$(document).on("click",".btn-printer-row-factura",function(){
		var itemID = $(this).data("print");
		var companyID = objTransactionMaster.companyID;
		var transactionID = objTransactionMaster.transactionID;
		var transactionMasterID = objTransactionMaster.transactionMasterID;  
		
		//Validar
		if(transactionMasterID == 0)
		{
			$(".tituloTostas").text("Error");
			$(".timeTostas").text("");
			$(".cuerpoTostas").text("Salvar y luego imprimir");
			var  selectedType = "bg-warning";
			var  selectedAnimation = "animate__swing";			
			var  toastAnimationExample = document.querySelector('.toast-ex-error'); 			
			
			toastAnimation = new bootstrap.Toast(toastAnimationExample);			
			toastAnimation.show();
			return;
		}
	
	
		var fn = (async function(companyID,transactionID,transactionMasterID,itemID){				
			await fnImprimirComandaCocina(companyID,transactionID,transactionMasterID,itemID);
		});			
		fn(companyID,transactionID,transactionMasterID,itemID);
		
		
		
		
	});
	
	$(document).on("click",".btnProductoAdd",function(){
		
		var nombreProducto 	= $(this).data("nombre");				
		var itemID 			= $(this).data("itemid");				
		var cantidad 		= parseInt(varConstComandoNumerico) * parseInt( (varConstComando+"1") );
		var tipoPrecioID	= $("#txtTiposDePrecios").val();
		var warehouseID    	= $("#txtBodegasOrigen").val();
		
		
		
		//validar comando numerico y comando operativo
		if(varConstComandoNumerico == "" || varConstComando == ""){
			$(".tituloTostas").text("Error");
			$(".timeTostas").text("");
			$(".cuerpoTostas").text("Seleccione la cantidad y la operacion");
			var  selectedType = "bg-warning";
			var  selectedAnimation = "animate__swing";			
			var  toastAnimationExample = document.querySelector('.toast-ex-error'); 			
			
			toastAnimation = new bootstrap.Toast(toastAnimationExample);
			
			toastAnimation.show();
			return;
		}
		
		fnRenderItemInInvoice(0,nombreProducto,itemID,cantidad,tipoPrecioID,warehouseID,0);
		//varConstComando 		= "";
		//varConstComandoNumerico = "0";
		fnCalcularDetalleFactura();
		
	});
	
	$(document).on("click",".btnComandoNumerico",function(){
		varConstComandoNumerico = $(this).data("value");				
	});
	$(document).on("click",".btnComando",function(){
		varConstComando = $(this).data("value");				
	});
	
	
	$(document).on("click",".btn-category-producto",function(){
		varConstCategoryID = $(this).data("idcategory");		
		fnRenderProductos();
	});
	
	$(document).on("click","#btnActualizarClienteYProductos",function(){
		//iniciar carga
		$.blockUI({
			message: '<div class="spinner-border text-primary" role="status"></div>',
			timeout: 1000,
			css: {
			  backgroundColor: 'transparent',
			  border: '0'
			},
			overlayCSS: {
			  backgroundColor: '#fff',
			  opacity: 0.8
			}
		});
		  
		fnActualizar().then((data) => {
			
			fnRenderCategorias();			
			fnRenderProductos();			
		    createTableCustomer();
			createTableProductos();	

			$(".tituloTostas").text("Información");
			$(".timeTostas").text("");
			$(".cuerpoTostas").text("Catalogos actualizados");
			var  toastAnimationExample 	= document.querySelector('.toast-ex-exito'); 						
			toastAnimation = new bootstrap.Toast(toastAnimationExample);			
			toastAnimation.show();
			
			
		});

		
	});			
		
		
	$(document).on("click","#btnImprimir",function(){
		//iniciar carga
		$.blockUI({
			message: '<div class="spinner-border text-primary" role="status"></div>',
			timeout: 1000,
			css: {
			  backgroundColor: 'transparent',
			  border: '0'
			},
			overlayCSS: {
			  backgroundColor: '#fff',
			  opacity: 0.8
			}
		});
		
		
		var fn = (async function(){				
			await fnImprimirFactura();
		});			
		fn();
		
	});
	
	fnInit();
	
	async function fnInit(){
		
		
		//iniciar carga
		$.blockUI({
			message: '<div class="spinner-border text-primary" role="status"></div>',
			timeout: 1000,
			css: {
			  backgroundColor: 'transparent',
			  border: '0'
			},
			overlayCSS: {
			  backgroundColor: '#fff',
			  opacity: 0.8
			}
		});		  
		
		
		if(objListaClientes == null || objListaProductos == null || objListaProductosCategorias == null || objListaClientesLine == null)
		await fnActualizar();
	
		createTableCustomer();
		createTableProductos();
		
		//llenar lista de tipos de precios
		$("#txtTiposDePrecios").empty();
		varConstTiposPrecio.forEach(function(obj,i){ 
			$("#txtTiposDePrecios").append("<option value='" + obj.catalogItemID +"'>"+ obj.display + "</option>");
		});
		
		//Llenar lista de bodegas
		$("#txtBodegasOrigen").empty();
		varConstBodegas.forEach(function(obj,i){ 
			$("#txtBodegasOrigen").append("<option value='" + obj.warehouseID +"'>"+ obj.name + "</option>");
		});		
		
		
		//Llenar categorias de productos
		fnRenderCategorias();
		
		//Llenar listsa de productos en la pantalla
		fnRenderProductos();
		
		//Inciaializar los valores de la factura
		fnInitFactura();		
		
		//Cargar los valores de la factura segun la edicion
		fnLoadFactura();
		
		//Renderizar la factura
		fnRenderFactura();
		
		
		//Notificar
		//$(".tituloTostas").text("Informacion");
		//$(".timeTostas").text("");
		//$(".cuerpoTostas").text("listo");
		//var  toastAnimationExample 	= document.querySelector('.toast-ex-exito'); 	
		//toastAnimation 				= new bootstrap.Toast(toastAnimationExample);		
		//toastAnimation.show();
		
		
	}	
	
	function fnRenderCategorias(){
		$("#vertical-example").empty();
		$("#txtSelectedCategoriaMovil").empty();
		
		var appendMovil_ 	= "";
		appendMovil_ 		= appendMovil_ + "<option value='' selected >Seleccionar</option>";
		var append_ 		= "";
		append_ 			= append_ + '<div class="row">';
		var varConstCategoryID  = 0;
		
		if(objListaProductosCategorias != null)
		if(objListaProductosCategorias.length > 0){
			objListaProductosCategorias.forEach(function(obj,i){ 
				var ii 		= i+1;			
				var modu4 	= ii%2;			
				
				if(i == 0)
				varConstCategoryID = obj.Codigo;
				
				
				
				append_ = append_ + 
					//'<div class="col-md-4 col-lg-4 mb-4">'+
					//	'<button type="button" class="btn btn-facebook btn-category-producto" data-idcategory="'+obj.Codigo+'" >'+
					//	  '<span class="tf-icons bx bx-category"></span>&nbsp; '+obj.Nombre +
					//	'</button>'+
					//'</div>';
					
					//'<div class="col-md-4 col-lg-4 col-sm-6 mb-4 btn-category-producto"  data-idcategory="'+obj.Codigo+'" style="cursor:pointer"  >'+
					//  '	<div class="card bg-primary text-white  mb-3">'+
					//  '		<div class="card-body">'+
					//  '			<h5 class="card-title"></h5>'+
					//  '			<p class="card-text">'+obj.Nombre+'</p>'+
					//  '		</div>'+
					//  '	</div>'+
				    //'</div>';
					
					'<div class="col-md-4 col-lg-4 col-sm-6 mb-4">'+
					'	<button type="button" class="btn btn-warning w-100 h-100 d-inline-bloc btn-category-producto " data-idcategory="'+obj.Codigo+'" >'+
					'		'+obj.Nombre+''+
					'	</button>'+
					'</div>';
				
				appendMovil_ = appendMovil_ + 
				'<option value="'+obj.Codigo+'">'+obj.Nombre+'</option>';
						
				
			});	
			append_ = append_ + '</div>';			
			$("#vertical-example").append(append_);
			$("#txtSelectedCategoriaMovil").append(appendMovil_);
		}
	}
	
	function fnRenderProductos(){
		var categoryID 		= varConstCategoryID;
		var tipoPrecioID 	= $("#txtTiposDePrecios").val();
		var warehouseID		= $("#txtBodegasOrigen").val();
		
		
		var listProductosRender 	= 
		jLinq.from(objListaProductos).where(function(obj){ 
			var r = 
				parseInt(obj.typePriceID) == parseInt(tipoPrecioID) && 
				parseInt(obj.warehouseID) == parseInt(warehouseID) && 
				parseInt(obj.inventoryCategoryID) == parseInt(categoryID);
				
			
			return r;
		}).select();
		
		
		$("#vertical-example2").empty();
	    $("#txtSelectedProductoMovil").empty();
		
		var append_ 		= "";
		var appendMovil_ 	= "";
		append_ 			= append_ + '<div class="row">';
		append_				=	
					'<div class="col-md-6 col-lg-6 col-sm-6 mb-4  		d-none d-sm-none d-md-block 		">'+
					'	<button type="button" class="btn btn-warning w-100 h-100 d-inline-bloc  btnProductosShowGrid "  >'+
					'		TODOS'+
					'	</button>'+
					'</div>';
					
		listProductosRender.forEach(function(obj,i){ 
			var ii 		= i+1;			
			var modu4 	= ii%3				
			
			
			append_ = append_ + 
					//'<div class="col-md-4 col-lg-4 mb-4">'+
					//	'<button type="button" class="btn btn-google-plus w-100 btnProductoAdd" data-itemid="'+obj.itemID+'" data-nombre="'+obj.Nombre+'" >'+
					//	  '<!--<span class="tf-icons bx bx-category"></span>&nbsp;--> '+ formatNameproducto(obj.Nombre) +
					//	'</button>'+
					//'</div>';
				    
					//'<div class="col-md-6 col-xl-6 btnProductoAdd" data-itemid="'+obj.itemID+'" data-nombre="'+obj.Nombre+'" style="cursor:pointer"  >'+
					//  '	<div class="card shadow-none bg-transparent border border-warning  mb-3">'+
					//  '		<div class="card-body">'+
					//  '			<h5 class="card-title">'+obj.Codigo+'</h5>'+
					//  '			<p class="card-text">'+obj.Nombre+'</p>'+
					//  '		</div>'+
					//  '	</div>'+
				    //'</div>';
					
					'<div class="col-md-6 col-lg-6 col-sm-6 mb-4 ">'+
					'	<button type="button" class="btn btn-instagram  w-100 h-100 d-inline-bloc  btnProductoAdd "  data-itemid="'+obj.itemID+'" data-nombre="'+obj.Nombre+'"  >'+
					'		'+obj.Nombre+''+
					'	</button>'+
					'</div>';
			
			 appendMovil_ = appendMovil_+
			 '<option value="'+obj.itemID+'" data-itemid="'+obj.itemID+'" data-nombre="'+obj.Nombre +'"  >'+obj.Nombre+'</option>';
			
		});		
		
		append_ = append_ + '</div>';
		$("#vertical-example2").append(append_);
		$("#txtSelectedProductoMovil").append(appendMovil_);
		
		
		
	}
	
	function formatNameproducto(nombre){
		
		nombre = 
			"&nbsp;"+
			nombre+".......................";
			
		nombre = nombre.substr(0,21);
		
		
		return nombre;
	}
	
	async function fnActualizar(){
		
		//Obtener Productos	    
		var objResult_				= await fnObtenerListaProductos();
		objListaProductos 			= objResult_.objGridView;
		var objListaProductosStore 	= localStorage.getItem("objListaProductos");		
		localStorage.setItem("objListaProductos",JSON.stringify(objListaProductos));				
		
		//Obtener Categorias
		var objResult_							= await fnObtenerListaCategorias();
		objListaProductosCategorias 			= objResult_.objGridView;
		var objListaProductosCaterogiraStore 	= localStorage.getItem("objListaProductosCategorias");		
		localStorage.setItem("objListaProductosCategorias",JSON.stringify(objListaProductosCategorias));	
	
	
		//Obtener Clientes		
		var objResult_				= await fnObtenerListaClientes();
		objListaClientes 			= objResult_.objGridView;
		var objListaClienteStore 	= localStorage.getItem("objListaClientes");		
		localStorage.setItem("objListaClientes",JSON.stringify(objListaClientes));		


		//Obtener Lineas de Credito
		var objResult_					= await fnGetLineaCredito();
		objListaClientesLine 			= objResult_.objListCustomerCreditLine;
		var objListaClienteLineStore 	= localStorage.getItem("objListaClientesLine");		
		localStorage.setItem("objListaClientesLine",JSON.stringify(objListaClientesLine));		
		
	
		
	}
	
	async function fnObtenerListaProductos(){
		const resultAjax2 = await $.ajax({									
			cache       : false,			
			dataType    : 'json',
			type        : 'GET',			
			url  		: varConstSiteUrl + "app_invoice_api/getViewApi/"+ varConstComponentItem + "/onCompleteNewItem/SELECCIONAR_ITEM_BILLING_V2/"+encodeURI('{"warehouseID"|"'+  0   +'"{}"listPriceID"|"'+varConstListPrice+'"{}"typePriceID"|"'+ 0 +'"}')
		});	

		return resultAjax2;
	}
	
	async function fnObtenerListaCategorias(){
		const resultAjax2 = await $.ajax({									
			cache       : false,			
			dataType    : 'json',
			type        : 'GET',			
			url  		: varConstSiteUrl + "app_invoice_api/getViewApi/"+ varConstComponentItemCategory + "/onCompleteNewItem/SELECCIONAR_CATEGORIA/true/empty"
		});	

		return resultAjax2;
	}
	
	
	async function fnObtenerListaClientes(){
		
		const resultAjax2 = await $.ajax({									
			cache       : false,
			dataType    : 'json',
			type        : 'GET',	
			url  		: varConstSiteUrl+"app_invoice_api/getViewApi/"+varConstComponentCustomer+"/onCompleteNewItem/SELECCIONAR_CLIENTES_BILLING/true/empty"
		});	

		return resultAjax2;
	}
	
	async function fnGetLineaCredito(){
		
		const resultAjax = await $.ajax({									
			cache       : false,
			dataType    : 'json',
			type        : 'POST',
			url  		: "<?php echo base_url(); ?>/app_invoice_api/getLineByCustomerOnly",
			data 		: {entityID : 0  }
		});		
		
		return resultAjax;
		
	}
	
	async function fnSendFactura(){
		
		
		const resultAjax2 = await $.ajax({									
			cache       : false,			
			dataType    : 'json',			
			type        : 'POST',			
			url  		: varConstSiteUrl+"app_invoice_billing/saveApi",
			data		: objTransactionMaster
		});	

		
		
		objTransactionMaster.transactionMasterID 	= resultAjax2.transactionMasterID;
		objTransactionMaster.transactionID 			= resultAjax2.transactionID;
		objTransactionMaster.companyID 				= resultAjax2.companyID;
		objTransactionMaster.transactionNumber 		= resultAjax2.transactionNumber;

		//Notificar
		$(".tituloTostas").text("Informacion");
		$(".timeTostas").text("");
		$(".cuerpoTostas").text("Factura guardada: " + resultAjax2.message);
		var  toastAnimationExample 	= document.querySelector('.toast-ex-exito'); 	
		toastAnimation 				= new bootstrap.Toast(toastAnimationExample);		
		toastAnimation.show();		
		return resultAjax2;
		
		
	}
	
		
	function fnRenderFactura(){
		
		//Obtener cliente		
		var objClienteSelected 	= 
		jLinq.from(objListaClientes).where(function(obj){ 
			return parseInt(obj.entityID) == parseInt(objTransactionMaster.entityID)
		}).select();
		
		objClienteSelected = objClienteSelected[0];
	
	
		//Obtener causal		
		var objCausalSelected 	= 
		jLinq.from(varConstCausales).where(function(obj){ 
			return parseInt(obj.transactionCausalID) == parseInt(objTransactionMaster.transactionCausalID)
		}).select();
		
		objCausalSelected = objCausalSelected[0];
		
		
		//Obtener moneda		
		var objCurrencySelected 	= 
		jLinq.from(varConstMonedas).where(function(obj){ 
			return parseInt(obj.currencyID) == parseInt(objTransactionMaster.currencyID)
		}).select();
		
		objCurrencySelected = objCurrencySelected[0];
		
		//Obtener Estado 
		var objStatusSelected 	= 
		jLinq.from(varConstEstados).where(function(obj){ 
			return parseInt(obj.workflowStageID) == parseInt(objTransactionMaster.statusID)
		}).select();
		
		objStatusSelected = objStatusSelected[0];
		
		
		//Obtener Tipo de precio 		
		var objTipoPrecioSelected 	= 
		jLinq.from(varConstTiposPrecio).where(function(obj){ 
			var r = parseInt(obj.catalogItemID) == parseInt(objTransactionMaster.typePriceID);			
			return r;
		}).select();
		
		objTipoPrecioSelected = objTipoPrecioSelected[0];
		
		
		//Obtener bodega
		var objWarehouseSelected 	= 
		jLinq.from(varConstBodegas).where(function(obj){ 
			return parseInt(obj.warehouseID) == parseInt(objTransactionMaster.sourceWarehouseID)
		}).select();
		
		objWarehouseSelected = objWarehouseSelected[0];
		
		//Obtener las lineas de credito del cliente		
		var objListaCreditLine 	= 
		jLinq.from(objListaClientesLine).where(function(obj){ 
			return parseInt(obj.entityID) == parseInt(objTransactionMaster.entityID)
		}).select();		
		objListaCreditLine = objListaCreditLine;
		
		
		//Renderizar Lineas de Credito
		$("#txtCredintLine").empty();
		$("#txtCredintLine").append('<option value='+ 0 +'>'+ 'CCL00000000 NINGUNA' +'</option>');
		objListaCreditLine.forEach(function(obj,i){			
			$("#txtCredintLine").append('<option value='+ obj.customerCreditLineID +'>'+ obj.accountNumber + ' '  + obj.line.toUpperCase()  +'</option>');
		});
		
		
		$("#txtCredintLine").val(objTransactionMaster.reference4);
		$("#txNote").val(objTransactionMaster.note);
		$("#txtReceiptAmount").val(fnFormatNumber( objTransactionMaster.receiptAmount,2));
		$("#txtReceiptAmountDol").val(fnFormatNumber( objTransactionMaster.receiptAmountDol,2));
		$("#txtChangeReceipt").val(0);
		
		
		$("#txtSubAmount").text(objCurrencySelected.simb + " " + fnFormatNumber( objTransactionMaster.amount,2));
		$("#txtIva").text(0);
		$("#txtAmount").text(objCurrencySelected.simb + " " + fnFormatNumber( objTransactionMaster.amount,2));
		
		$("#txtTransactionNumber").text(objTransactionMaster.transactionNumber);
		$("#txtLabelTransactionNumber").text(objTransactionMaster.transactionNumber);
		$("#txtTipoFactura").text(objCausalSelected.name.toLowerCase());
		$("#txtCurrency").text(objCurrencySelected.name.toLowerCase());
		$("#txtCliente").text(objClienteSelected.Nombre.toLowerCase());
		$("#txtStatus").text(objStatusSelected.display.toLowerCase());
		$("#txtWarehouse").text(objWarehouseSelected.name.toLowerCase());
		$("#txtTipoPrecio").text(objTipoPrecioSelected.display.toLowerCase());
		
		$("#txtBodegasOrigen").val(objTransactionMaster.sourceWarehouseID);
		$("#txtTiposDePrecios").val(objTransactionMaster.typePriceID);
		
		$("#txtReferenceClientName").val(objTransactionMaster.referenceClientName);		
		$("#txtReferenceClientIdentifier").val(objTransactionMaster.referenceClientIdentifier);
		
		
		if(objTransactionMaster.referenceClientName != "" ){
			$("#txtCliente").text(objTransactionMaster.referenceClientName);		
		}
				
		//Renderizar Detalle
		var tipoPrecioID 	= $("#txtTiposDePrecios").val();
		var warehouseID		= $("#txtBodegasOrigen").val();		 
		$("#container_factura").empty();
		if(objTransactionMaster.transactionMasterDetailID.length > 0){			
			
			objTransactionMaster.transactionMasterDetailID.forEach(function(obj,i){				
					var transactionMasterID = objTransactionMaster.transactionMasterDetailID[i];
					var itemID 		= objTransactionMaster.itemID[i];
					var quantity 	= objTransactionMaster.quantity[i];
					var price 		= objTransactionMaster.price[i];
					var subtotal 	= objTransactionMaster.subtotal[i];
					var iva 		= objTransactionMaster.iva[i];
					var lote 		= objTransactionMaster.lote[i];
					var vencimiento	= objTransactionMaster.vencimiento[i];
					
					
					fnRenderItemInInvoice(i,"N/D",itemID,quantity,tipoPrecioID,warehouseID,price);
					
			});
			
		}
		
		
				
		
	}
	
	function fnLoadFactura(){
		
		if(varConstTransactionMaster != null){
			objTransactionMaster.amount 						= varConstTransactionMaster.amount;
			objTransactionMaster.statusID 						= varConstTransactionMaster.statusID;
			objTransactionMaster.transactionOn 					= varConstTransactionMaster.transactionOn;
			objTransactionMaster.createdOn						= varConstTransactionMaster.createdOn;		
			objTransactionMaster.companyID						= varConstTransactionMaster.companyID;
			objTransactionMaster.transactionID					= varConstTransactionMaster.transactionID;		
			objTransactionMaster.transactionMasterID			= varConstTransactionMaster.transactionMasterID;
			objTransactionMaster.transactionCausalID			= varConstTransactionMaster.transactionCausalID;		
			objTransactionMaster.transactionNumber				= varConstTransactionMaster.transactionNumber;
			objTransactionMaster.entityID						= varConstTransactionMaster.entityID;
			objTransactionMaster.currencyID						= varConstTransactionMaster.currencyID;
			objTransactionMaster.reference1						= varConstTransactionMaster.reference1;
			objTransactionMaster.reference2						= varConstTransactionMaster.reference2;
			objTransactionMaster.reference3						= varConstTransactionMaster.reference3;
			objTransactionMaster.reference4						= varConstTransactionMaster.reference4;
			objTransactionMaster.sourceWarehouseID				= varConstTransactionMaster.sourceWarehouseID;
			objTransactionMaster.createdBy						= varConstTransactionMaster.createdBy;		
			objTransactionMaster.note 							= varConstTransactionMaster.note;
			objTransactionMaster.typePriceID					= objTransactionMaster.typePriceID;
			
			
		}
		
		if(varConstTransactionMasterInfo != null){
			objTransactionMaster.zoneID							= varConstTransactionMasterInfo.zoneID;
			objTransactionMaster.referenceClientName			= varConstTransactionMasterInfo.referenceClientName;
			objTransactionMaster.referenceClientIdentifier		= varConstTransactionMasterInfo.referenceClientIdentifier;
			objTransactionMaster.receiptAmount					= varConstTransactionMasterInfo.receiptAmount;
			objTransactionMaster.receiptAmountDol				= varConstTransactionMasterInfo.receiptAmountDol;
		}
		
		objTransactionMaster.transactionMasterDetailID		= [];
		objTransactionMaster.itemID							= [];
		objTransactionMaster.quantity						= [];
		objTransactionMaster.price							= [];
		objTransactionMaster.subtotal						= [];
		objTransactionMaster.iva							= [];
		objTransactionMaster.lote							= [];
		objTransactionMaster.vencimiento					= [];
		objTransactionMaster.sku							= [];
		
		if(varConstTransactionMasterDetail  == null){
			return;
		}
		
		if(varConstTransactionMasterDetail.length == 0){
			return;
		}

		
		varConstTransactionMasterDetail.forEach(function(obj,i){
				objTransactionMaster.transactionMasterDetailID.push(obj.transactionMasterDetailID);
				objTransactionMaster.itemID.push(obj.componentItemID);
				objTransactionMaster.quantity.push(obj.quantity);
				objTransactionMaster.price.push(obj.unitaryPrice);
				objTransactionMaster.subtotal.push(0);
				objTransactionMaster.iva.push(0);
				objTransactionMaster.lote.push(obj.lote);
				objTransactionMaster.vencimiento.push(obj.expirationDate);
				objTransactionMaster.sku.push(varConstSkuUnidad);
		});
		
	}

	function fnRenderItemInInvoice(index,nombreProducto,itemID,cantidad,tipoPrecioID,warehouseID,price){
		
		var rowProducto 	= 
		jLinq.from(objListaProductos).where(function(obj){ 
			var r = 
				parseInt(obj.typePriceID) == parseInt(tipoPrecioID) && 
				parseInt(obj.warehouseID) == parseInt(warehouseID) && 
				parseInt(obj.itemID) == parseInt(itemID);
			
			return r;
		}).select();
		
		if(rowProducto.length == 0)
		return;
	
		//Obtener el precio.		
		rowProducto = rowProducto[0];
	
	
		//Obtener producto en pantalla
		var price							= price == 0 ? parseFloat(rowProducto.Precio)  : price;
		var productoEnPantalla 				= $("#container_factura").find(".invoice-item-itemid[value='"+rowProducto.itemID+"']");		
		var cantidadDeProductoEnPantalla 	= $("#container_factura").find(".invoice-item-itemid").length;	
	
		//Calcular el index		
		if(index == 0 && productoEnPantalla.length == 0 && cantidadDeProductoEnPantalla > 0)
		index = cantidadDeProductoEnPantalla;
	
	
		if(productoEnPantalla.length == 0){
			<!--Registro de factura -->
			var stringRow = "";		
			stringRow = 
			'<div class="d-flex border rounded position-relative pe-0 row-factura"  data-rowid="'+cantidadDeProductoEnPantalla+'" >'+
			'		<!--Parte visible del registro de factura-->'+
			'		<div class="row w-100 m-0 p-3">'+
			'	  '+
			'				<div class="col-md-12 col-12 mb-md-0 mb-3 ps-md-0">'+
			'				  <p class="mb-12 repeater-title">'+rowProducto.Codigo + " & " + rowProducto.Nombre+'</p>'+
			'				</div>'+
			'				<br/>'+
			'				<div class="col-md-6 col-4 mb-md-0 mb-3">'+
			'				  <p class="mb-2 repeater-title">Precio</p>'+
			'				  <input type="hidden"  class="invoice-item-itemid" data-index="'+index+'" value="'+ parseFloat(rowProducto.itemID) +'"  />'+
			'				  <input type="decimal" class="form-control invoice-item-price mb-2" data-index="'+index+'"  value="'+ price +'" placeholder="24" min="12"  />'+
			'				  '+
			'				</div>'+
			'				<div class="col-md-6 col-4 mb-md-0 mb-3">'+
			'				  <p class="mb-2 repeater-title">Cant</p>'+
			'				  <input type="number" class="form-control invoice-item-qty" data-index="'+index+'"  value="'+fnFormatNumber(cantidad,2)+'" placeholder="1" min="1" max="50"    />'+
			'				</div>'+
			'		</div>'+
			'		<!--/Parte visible del registro de factura-->'+
			'	  '+
			'		<!--div oculto del registro de factura-->'+
			'		<div class="d-flex flex-column align-items-center justify-content-between border-start p-2" >'+
			'			<i class="bx bx-x fs-4 text-muted cursor-pointer btn-delete-row-factura" data-delete="'+cantidadDeProductoEnPantalla+'" ></i>'+
			'			<i class="bx bxs-printer btn-printer-row-factura" data-print="'+rowProducto.itemID+'" ></i>'+			
			'		</div>'+
			'		<!--/div oculto del registro de factura-->'+
			'</div>'+
			'</br>'+
			'<!--/Registro de factura -->';
			
			$("#container_factura").prepend(stringRow);
		}
		else{
			productoEnPantalla 	= productoEnPantalla[0];
			var oldCantidad		= $(productoEnPantalla).parent().parent().find(".invoice-item-qty").val();
			oldCantidad 		= parseInt(oldCantidad);
			var newCantidad		= oldCantidad + cantidad;
			
			
			if(newCantidad < 0)
			newCantidad = 0
				
				
			if(newCantidad <= 0)
			$(productoEnPantalla).parent().parent().parent().remove();
			
		
			
			$(productoEnPantalla).parent().parent().find(".invoice-item-qty").val(fnFormatNumber(newCantidad,2));
			
			
			
			
		}
		
	}
	
	function fnInitFactura(){		
			
			objTransactionMaster 								= new clsTransactionMaster();				
			objTransactionMaster.statusID 						= varConstKeyEstadoRegistrado;		//REGISTRADO
			objTransactionMaster.transactionCausalID			= varConstCausales[0].transactionCausalID; 	//CONTADO				
			objTransactionMaster.entityID						= varConstClienteID;						//CLIENTE DEFAULT
			objTransactionMaster.currencyID						= varConstMonedas[0].currencyID;	
			objTransactionMaster.typePriceID					= varConstTiposPrecio[0].catalogItemID;		//TIPO DE PRECIO
			objTransactionMaster.sourceWarehouseID				= varConstBodegas[0].warehouseID;			//BODEGA			
			

	}
	
	function fnLimpiarDetalleFactura(){
		objTransactionMaster.transactionMasterDetailID 		= [];
		objTransactionMaster.itemID							= [];
		objTransactionMaster.quantity						= [];
		objTransactionMaster.price							= [];
		objTransactionMaster.subtotal						= [];
		objTransactionMaster.iva							= [];
		objTransactionMaster.lote							= [];
		objTransactionMaster.vencimiento					= [];
		objTransactionMaster.sku							= [];
		
		objTransactionMaster.amount 						= 0;					
		objTransactionMaster.receiptAmount					= 0;
		objTransactionMaster.receiptAmountDol				= 0;
		
	}
		
	function fnCalcularDetalleFactura()
	{
	
		objTransactionMaster.transactionMasterDetailID 		= [];
		objTransactionMaster.itemID							= [];
		objTransactionMaster.quantity						= [];
		objTransactionMaster.price							= [];
		objTransactionMaster.subtotal						= [];
		objTransactionMaster.iva							= [];
		objTransactionMaster.lote							= [];
		objTransactionMaster.vencimiento					= [];
		objTransactionMaster.sku							= [];
		
		objTransactionMaster.amount 						= 0;					
		objTransactionMaster.receiptAmount					= 0;
		objTransactionMaster.receiptAmountDol				= 0;
		
		if($("#container_factura .row-factura").length == 0)
		{
			return;
		}
		
		for(var i = 0; i < $("#container_factura .row-factura").length ; i++){
			
			var ielemtn 	= $("#container_factura .row-factura")[i];
			var itemID 		= $(ielemtn).find(".invoice-item-itemid").val();
			var cantidad 	= $(ielemtn).find(".invoice-item-qty").val();
			var precio 		= $(ielemtn).find(".invoice-item-price").val();
			
			objTransactionMaster.transactionMasterDetailID.push(0);
			objTransactionMaster.itemID.push(itemID);
			objTransactionMaster.quantity.push(cantidad);
			objTransactionMaster.price.push(precio);
			objTransactionMaster.subtotal.push(0);
			objTransactionMaster.iva.push(0);
			objTransactionMaster.lote.push("");
			objTransactionMaster.vencimiento.push("");
			objTransactionMaster.sku.push(varConstSkuUnidad);
			
			objTransactionMaster.amount 						= objTransactionMaster.amount + ( cantidad * precio);
			objTransactionMaster.receiptAmount					= objTransactionMaster.amount;
			objTransactionMaster.receiptAmountDol				= 0;
		
		}
		
				
	}
	
	
	async function fnImprimirComandaCocina(companyID,transactionID,transactionMasterID,itemID){
		
		let resultAjx3
		 try {
			 
			if(objTransactionMaster.transactionMasterID == undefined)
			return;
		
			if(objTransactionMaster.transactionMasterID == null)
			return;
		
			if(objTransactionMaster.transactionMasterID == 0)
			return;
		
			//Impresion directa
			var url	= varConstSiteUrl + varConstParameterInvoiceBillingPrinterDirectCocinaUrl ;
				url = url+
				"/companyID/" + objTransactionMaster.companyID + 
				"/transactionID/" + objTransactionMaster.transactionID +
				"/transactionMasterID/" + objTransactionMaster.transactionMasterID +
				"/itemID/" + itemID ;
				
			
			resultAjx3 = await $.ajax({									
				cache       : false,
				dataType    : 'json',
				type        : 'GET',
				url  		: url
			});
			
			
			
			//$(".tituloTostas").text("Información");
			//$(".timeTostas").text("");
			//$(".cuerpoTostas").text("Impresion con exitosa");
			//var  toastAnimationExample 	= document.querySelector('.toast-ex-exito'); 			
			//toastAnimation = new bootstrap.Toast(toastAnimationExample);			
			//toastAnimation.show();			
			return resultAjx3;
			
			
		 } catch (error) {
			
			//$(".tituloTostas").text("Error");
			//$(".timeTostas").text("");
			//$(".cuerpoTostas").text("No se logro imprimir configurar la impresora");
			//var  toastAnimationExample 	= document.querySelector('.toast-ex-error'); 		
			//toastAnimation = new bootstrap.Toast(toastAnimationExample);			
			//toastAnimation.show();		
			
		 }
		 
	}
	
	async function fnImprimirFactura(){
		
		 let resultAjx3
		 try {
			 
			if(objTransactionMaster.transactionMasterID == undefined)
			return;
		
			if(objTransactionMaster.transactionMasterID == null)
			return;
		
			if(objTransactionMaster.transactionMasterID == 0)
			return;
		
			//Impresion directa
			var url	= varConstSiteUrl + varConstParameterInvoiceBillingPrinterDirectUrl ;
				url = url+
				"/companyID/" + objTransactionMaster.companyID + 
				"/transactionID/" + objTransactionMaster.transactionID +
				"/transactionMasterID/" + objTransactionMaster.transactionMasterID ;
				
			
			resultAjx3 = await $.ajax({									
				cache       : false,
				dataType    : 'json',
				type        : 'GET',
				url  		: url
			});
			
			
			
			//$(".tituloTostas").text("Información");
			//$(".timeTostas").text("");
			//$(".cuerpoTostas").text("Impresion con exitosa");
			//var  toastAnimationExample 	= document.querySelector('.toast-ex-exito'); 			
			//toastAnimation = new bootstrap.Toast(toastAnimationExample);			
			//toastAnimation.show();			
			return resultAjx3;
			
			
		 } catch (error) {
			
			//$(".tituloTostas").text("Error");
			//$(".timeTostas").text("");
			//$(".cuerpoTostas").text("No se logro imprimir configurar la impresora");
			//var  toastAnimationExample 	= document.querySelector('.toast-ex-error'); 		
			//toastAnimation = new bootstrap.Toast(toastAnimationExample);			
			//toastAnimation.show();		
			
		 }
		  
		
		
	}
		
</script>
