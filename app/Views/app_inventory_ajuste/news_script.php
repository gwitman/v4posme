<!-- ./ page heading -->
<script>	
	var numberDecimal					= 2;
	var numberDecimalSummary			= 2;
	var numberDecimalSummaryRound		= false;
	var objListaProductos				= {};	
	var objListTypePreice				= JSON.parse('<?php echo json_encode($objListTypePreice); ?>');
	var varParameterCantidadItemPoup	= '<?php echo $objParameterCantidadItemPoup; ?>';  
	
	var objParameterCORE_VIEW_CUSTOM_SCROLL_IN_DETATAIL_PURSHASE			= <?php echo $objParameterCORE_VIEW_CUSTOM_SCROLL_IN_DETATAIL_PURSHASE; ?>;
	var sScrollY = objParameterCORE_VIEW_CUSTOM_SCROLL_IN_DETATAIL_PURSHASE == true ?  "350px" : "auto";
	
	var objTableDetailTransaction 		= {};
	$(document).ready(function(){					
		//Inicializar Controles		
		$('#txtTransactionOn').datepicker({format:"yyyy-mm-dd"});
		$('#txtTransactionOn').val(moment().format("YYYY-MM-DD"));	
		$("#txtTransactionOn").datepicker("update");
		 
		objTableDetailTransaction = $("#tb_transaction_master_detail").dataTable({
			"bPaginate"		: false,
			"bFilter"		: false,
			"bSort"			: false,
			"bInfo"			: false,
			"sScrollY"		: sScrollY,
			"bAutoWidth"	: false,
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
							"aTargets"		: [ 1 ],//itemID
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtDetailItemID[]" />';
							}
						},
						{
							"aTargets"		: [2],//transactionMasterDetailID
							"bVisible"  	: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtDetailTransactionDetailID[]" />';
							}
						},
						{
							"aTargets"	: [4], //nombre
							"sWidth"	: "40%"
						},
						{
							"aTargets"	: [ 6 ],//cantidad
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailQuantity txt-numeric" value="'+data+'" name="txtDetailQuantity[]" />';
							}
						},
						{
							"aTargets"	: [ 7 ],//costo
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailCost txt-numeric" value="'+data+'" name="txtDetailCost[]" />';
							}
						},
						{
							"aTargets"	: [ 8 ],//precio
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailPrice txt-numeric" value="'+data+'" name="txtDetailPrice[]" />';
							}
						},
						{
							"aTargets"		: [ 9 ],//lote
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailLote txt-numeric"" value="'+data+'" name="txtDetailLote[]" readonly="true" />';
							}
						},
						{
							"aTargets"		: [ 10 ],//fecha expiracion
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailVencimiento txt-numeric"" value="'+data+'" name="txtDetailVencimiento[]" readonly="true" />';
							}
						},
						{
							"aTargets"	: [ 11 ],//mas informacion
							"mRender"	: function ( data, type, full ) {								
								if (data == false){									
									var str = '<a class="btn btn-primary btnMasInformcion" data-itemid="'+full[1]+'" data-transactionmasterdetailid="'+full[2]+'"  href="#" >Mas informacion</a>';
									return str;
								}
								else{									
									var str = '<a class="btn btn-primary btnMasInformcion" data-itemid="'+full[1]+'" data-transactionmasterdetailid="'+full[2]+'"  href="#" >Mas informacion</a>';
									return str;
								}
								
							}
						},
						{
							"aTargets"		: [ 12 ],//precio 02
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailPrice2 txt-numeric"" value="'+data+'" name="txtDetailPrice2[]" />';
							}
						},
						{
							"aTargets"		: [ 13 ],//precio 03
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailPrice3 txt-numeric"" value="'+data+'" name="txtDetailPrice3[]" />';
							}
						}
			]							
		});
		
		refreschChecked();
		//Buscar el Proveedor
		$(document).on("click","#btnSearchProvider",function(){
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentProvider->componentID; ?>/onCompleteProvider/SELECCIONAR_PROVEEDOR/true/empty/false/not_redirect_when_empty";
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteProvider = onCompleteProvider; 
		});		
		//Eliminar Proveedor
		$(document).on("click","#btnClearProvider",function(){
					$("#txtProviderID").val("");
					$("#txtProviderDescription").val("");
		});
		//Buscar el Orden de Compra
		$(document).on("click","#btnSearchOrdenCompra",function(){
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentInputSinPost->componentID; ?>/onCompleteOrdenCompra/SELECCIONAR_PLANTILLA_DE_COMPRA/true/empty/false/not_redirect_when_empty";
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteOrdenCompra = onCompleteOrdenCompra; 
		});		
		//Eliminar Orden de Compra
		$(document).on("click","#btnClearOrdenCompra",function(){
					$("#txtTransactionMasterIDOrdenCompra").val("");
					$("#txtTransactionNumberOrdenCompra").val("");
		});
		
		
		
		//Ir a Lista
		$(document).on("click","#btnBack",function(){
				fnWaitOpen();
		});
		
		//Guardar Documento
		$(document).on("click","#btnAcept",function(){
				$( "#form-new-transaction" ).attr("method","POST");
				$( "#form-new-transaction" ).attr("action","<?php echo base_url(); ?>/app_inventory_ajuste/save/new");
				
				if(validateForm()){
					fnWaitOpen();
					$( "#form-new-transaction" ).submit();
				}
				
		});	
		
		$(document).on("change","#txtCurrencyID",function(){
			objTableDetailTransaction.fnClearTable();
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
		
		
		//Agregar Item al Detalle
		$(document).on("click","#btnNewDetailTransaction",function(){
			var timerNotification 	= 15000;
			
			//Bodega Source
			if($("#txtProviderID").val()==""){
				fnShowNotification("Seleccione un proveedor","error",timerNotification);
				return;
			}
		
			
			var url_redirect		= "__app_inventory_item__add__callback__fnObtenerListadoProductos__comando__pantalla_abierta_desde_la_compra";			
			url_redirect 			= encodeURIComponent(url_redirect);
			
			var url_request 		= "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?php echo $objComponentItem->componentID; ?>/onCompleteItem/SELECCIONAR_ITEM_TO_PROVIDER_PAGINATED/true/"+encodeURI('{\"providerID\"|\"'+$("#txtProviderID").val()+'\",\"currencyID\"|\"'+$("#txtCurrencyID").val() +'\"}' ) + "/true/"+url_redirect+"/1/1/"+varParameterCantidadItemPoup+"/";;  
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteItem 	= onCompleteItem; 
			
		});
		
		$(document).on("click","#btnClearCache",function(){
			$.ajax
			({									
				cache       : false,
				dataType    : 'json',
				type        : 'GET',																	
				url  		: "<?php echo base_url(); ?>/core_cache/delete_by_name/SELECCIONAR_ITEM_TO_PROVIDER",
				
			});
			
		});
		
		
		$(document).on("click",".btnMasInformcion",function(){
			
			var itemID 						= $(this).data("itemid");
			var transactionMasterDetailID 	= $(this).data("transactionmasterdetailid");
			var tr 							= $(this).parent().parent()[0];
			var index 						= objTableDetailTransaction.fnGetPosition(tr);
			var objdat_ 					= objTableDetailTransaction.fnGetData(index);		
			var lote 						= objdat_[9];
			var vencimiento 				= objdat_[10];
			var precio1 					= objdat_[12];
			var precio2 					= objdat_[13];
			vencimiento 					= vencimiento.replace(" 00:00:00","");
					
			if(lote == "") lote = "0";
			if(vencimiento == "") vencimiento = moment().format("YYYY-MM-DD");
			
			var url_request = "<?php echo base_url(); ?>/app_inventory_ajuste/add_masinformacion/onCompleteUpdateMasInformacion/"+itemID+"/"+transactionMasterDetailID+"/"+index; 
			url_request = url_request + "/"+lote+"/"+vencimiento+"/"+precio1+"/"+precio2;
			
			window.open(url_request,"MsgWindow","width=900,height=500");
			window.onCompleteUpdateMasInformacion = onCompleteUpdateMasInformacion; 
			
			
		});
		
		//Eliminar Item del Detalle
		$(document).on("click","#btnDeleteDetailTransaction",function(){
			
			var listRow = objTableDetailTransaction.fnGetData();							
			var length 	= listRow.length;
			var i 		= 0;
			var j 		= 0;
			while (i< length ){
				if(listRow[i][0] == true){
				objTableDetailTransaction.fnDeleteRow( j,null,true );
				j--;
				}
				i++;
				j++;
			}
			
			
		});
		//Cambio en la cantidades
		$(document).on("blur",".txtDetailQuantity",function(){
			$(this).val(fnFormatFloat(fnFormatNumber(fnFormatFloat($(this).val()),numberDecimal)));
			var objrow_ = $(this).parent().parent()[0];
			var objind_ = objTableDetailTransaction.fnGetPosition(objrow_);
			var objdat_ = objTableDetailTransaction.fnGetData(objind_);								
			objTableDetailTransaction.fnUpdate( $(this).val(), objind_, 6 );
			fnUpdateDetail();
			refreschChecked();
		})
		//Cambio en el Costo
		$(document).on("blur",".txtDetailCost",function(){
			$(this).val(fnFormatFloat(fnFormatNumber(fnFormatFloat($(this).val()),numberDecimal)));
			var objrow_ = $(this).parent().parent()[0];
			var objind_ = objTableDetailTransaction.fnGetPosition(objrow_);
			var objdat_ = objTableDetailTransaction.fnGetData(objind_);								
			objTableDetailTransaction.fnUpdate(  $(this).val(), objind_, 7 );
			fnUpdateDetail();
			refreschChecked();
		})
		//Cambio en el Descuento
		$(document).on("change","input#txtDiscount",function(){
			fnUpdateDetail();
		});
		//Cambio en el Iva
		$(document).on("change","input#txtIva",function(){
			fnUpdateDetail();
		});
		
		//Seleccionar Checke 
		$(document).on("click",".classCheckedDetail",function(){
			
			var objrow_ = $(this).parent().parent()[0];
			var objind_ = objTableDetailTransaction.fnGetPosition(objrow_);
			var objdat_ = objTableDetailTransaction.fnGetData(objind_);								
			objTableDetailTransaction.fnUpdate( !objdat_[0], objind_, 0 );
			refreschChecked();
		});
	});
	
	//Funciones
	////////////////////////////
	////////////////////////////
	
	
	
	function validateForm(){
		var result 				= true;
		var timerNotification 	= 15000;
		
		//Bodega Source
		if($("#txtProviderID").val()==""){
			fnShowNotification("Seleccione un proveedor","error",timerNotification);
			result = false;
		}
		
		//Validar Estado
		if($("#txtStatusID").val() == ""){
			fnShowNotification("Establecer Estado","error",timerNotification);
			result = false;
		}
		//Fecha
		if($("#txtTransactionOn").val() == ""){
			fnShowNotification("Escriba la Fecha del Documento","error",timerNotification);
			result = false;
		}
		
		var lengthRow = objTableDetailTransaction.fnGetData().length;
		if(lengthRow == 0){
			fnShowNotification("Agregar el Detalle del Documento","error",timerNotification);
			result = false;
		}
		else{
			
			for(var i = 0 ; i < objTableDetailTransaction.fnGetData().length ; i++)
			{
				var iElement = objTableDetailTransaction.fnGetData()[i];
				var iElement = parseInt(iElement[6]);
				if(iElement < 0)
				{
					fnShowNotification("No puede haber cantidades en negativos","error",timerNotification);
					result = false;
				}
			}
		}
		
	
		return result;
	}
	function onCompleteUpdateMasInformacion(objResponse){
			var index 		= objResponse.txtPosition;
			var vencimiento = objResponse.txtVencimiento;
			var lote 		= objResponse.txtLote;
			var precio1 		= objResponse.txtPrecio1;
			var precio2 		= objResponse.txtPrecio2;
		
			
			var objdat_ = objTableDetailTransaction.fnGetData(index);		
			objTableDetailTransaction.fnUpdate( lote, index, 9 );
			objTableDetailTransaction.fnUpdate(  vencimiento, index, 10 );		
			objTableDetailTransaction.fnUpdate(  precio1, index, 12 );		
			objTableDetailTransaction.fnUpdate(  precio2, index, 13 );		
			
		
	}
	function onCompleteItem(objResponse){
		
		for(var i = 0 ; i < objResponse.length ; i++)
		{
			
			console.info("CALL onCompleteItem");
			
			var cantidad = 0;
			if(objResponse[i].length >= 11)
			{
				if(objResponse[i][11] == "add_cantidad")
				{
					cantidad = objResponse[i][9];
				}
			}
			
			var objRow 						= {};
			objRow.checked 					= false;
			objRow.itemID 					= objResponse[i][1];
			objRow.transactionMasterDetail 	= 0;
			objRow.itemNumber 				= objResponse[i][10];
			objRow.itemName 				= objResponse[i][7];
			objRow.itemUM 					= objResponse[i][8];
			objRow.quantity 				= cantidad;
			objRow.cost 					= objResponse[i][2];
			objRow.price 					= objResponse[i][3];
			objRow.lote 					= "";
			objRow.vencimiento				= "";
			objRow.masinfor					= "";
			objRow.precio1					= objResponse[i][4];
			objRow.precio2					= objResponse[i][5];
			
			//Berificar que el Item ya esta agregado 
			if(jLinq.from(objTableDetailTransaction.fnGetData()).where(function(obj){ return obj[1] == objRow.itemID;}).select().length > 0 )
			{
				fnShowNotification("El Item ya esta agregado","error");				
			}
			else
			{			
				objTableDetailTransaction.fnAddData
				(
					[
						objRow.checked,objRow.itemID,objRow.transactionMasterDetail,
						objRow.itemNumber,objRow.itemName,objRow.itemUM,
						objRow.quantity,objRow.cost,objRow.price,
						objRow.lote,objRow.vencimiento,
						objRow.masinfor,objRow.precio1,objRow.precio2
					]
				);
			}
			
		}	


		refreschChecked();
		var lastRow 	= $(".txtDetailQuantity").length ;
		lastRow 		= lastRow - 1;
		$($(".txtDetailQuantity")[lastRow]).focus();		
		
	}
	//Refresh
	function refreschChecked(){
		/*
		$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
		$('.txtDetailQuantity').mask('000,000.00', {reverse: true});
		$('.txtDetailCost').mask('000,000.00', {reverse: true});
		
		$('#txtDiscount').mask('000,000.00', {reverse: true});
		$('#txtIva').mask('000,000.00', {reverse: true});
		*/
	}
	function onCompleteOrdenCompra(objResponse){
		console.info("CALL onCompleteOrdenCompra");
	
		$("#txtTransactionMasterIDOrdenCompra").val(objResponse[0][1]);
		$("#txtTransactionNumberOrdenCompra").val(objResponse[0][2]);
	
	}
	function onCompleteProvider(objResponse){
		console.info("CALL onCompleteCustomer");
	
		$("#txtProviderID").val(objResponse[0][1]);
		$("#txtProviderDescription").val(objResponse[0][2] + " / " + objResponse[0][3]);
	
	}	
	function fnUpdateDetail(){
		console.info("fnUpdateDetail");
		var subtotal 	= 0;
		var iva			= $("#txtIva").val();
		var discount	= $("#txtDiscount").val();						
		var total		= 0;
		
		iva				= fnFormatFloat(fnFormatNumber(iva,numberDecimal));
		discount		= fnFormatFloat(fnFormatNumber(discount,numberDecimal));
		$("#txtIva").val(iva);
		$("#txtDiscount").val(discount);
		
		for(var i = 0; i < objTableDetailTransaction.fnGetData().length; i++){
			var row 	= objTableDetailTransaction.fnGetData()[i];
			subtotal 	= subtotal + fnFormatFloat(fnFormatNumber((row[6] * row[7]),numberDecimal));
		}
		
		subtotal		= parseInt(subtotal * 100) / 100;
		subtotal		= fnFormatFloat(fnFormatNumber(subtotal ,numberDecimalSummary));
		total			= parseInt(((subtotal + iva) - discount) * 100) / 100;
		total			= fnFormatFloat(fnFormatNumber(total,numberDecimalSummary));
		$("#txtSubTotal").val(subtotal);
		$("#txtTotal").val(total);		
		fnShowNotification("TOTAL: C$ " +  $.number(total) ,"success");
		
	}
</script>
