<!-- ./ page heading -->
<script>	
	var numberDecimal				= 2;
	var numberDecimalSummary		= 2;
	var numberDecimalSummaryRound	= false;
	var objTableDetailTransaction 	= {};
	var objListaProductos			= {};
	var objWindowSearchProduct;
	var objListaProductos2			= {};
	var objListaProductos3			= {};
	var objectParameterButtoms 		= {};
	var objParameterUrlPrinter		= '<?php echo $objParameterUrlPrinter; ?>';
	var objParameterUrlPrinterCode  = '<?php echo $objParameterMasive; ?>';
	var varUseMobile				= '<?php echo $useMobile; ?>';
	var objParameterCORE_VIEW_CUSTOM_SCROLL_IN_DETATAIL_PURSHASE	= <?php echo $objParameterCORE_VIEW_CUSTOM_SCROLL_IN_DETATAIL_PURSHASE; ?>;
	var objParameterINVENTORY_URL_PRINTER_INPUTUNPOST_ONLY_QUANTITY	= '<?php echo $objParameterINVENTORY_URL_PRINTER_INPUTUNPOST_ONLY_QUANTITY; ?>';
	var objParameterINVENTORY_URL_PRINTER_INPUTUNPOST_SHOW_OPCIONES	= '<?php echo $objParameterINVENTORY_URL_PRINTER_INPUTUNPOST_SHOW_OPCIONES; ?>';
	var sScrollY = objParameterCORE_VIEW_CUSTOM_SCROLL_IN_DETATAIL_PURSHASE == true ?  "350px" : "auto";
	var varParameterCantidadItemPoup	= '<?php echo $objParameterCantidadItemPoup; ?>';  
	
	$(document).ready(function(){					
		//Inicializar Controles		
		$('#txtTransactionOn').datepicker({format:"yyyy-mm-dd"});
		$("#txtTransactionOn").datepicker("update");
		
		objTableDetailTransaction = $("#tb_transaction_master_detail").dataTable({
			"bPaginate"		: false,
			"bFilter"		: false,
			"bSort"			: false,
			"bInfo"			: false,			
			"sScrollY"		: sScrollY,
			/*"sScrollX"		: "350px",*/
			"bAutoWidth"	: false,
			"aaData": [<?php 
					if($objTMD){
						$listrow = [];									
						foreach($objTMD as $i)
						{
						$listrow[] = 
							"[
								0,
								".$i->componentItemID.",
								".$i->transactionMasterDetailID.",
								'".$i->barCode."',
								'".str_replace('\'','',$i->itemName)."',
								'".$i->unitMeasureName."',
								fnFormatNumber(".$i->quantity.",numberDecimal),
								fnFormatNumber(".$i->unitaryCost.",numberDecimal),
								fnFormatNumber(".($i->unitaryAmount).",2),
								'".helper_RequestGetValue($i->lote,'')."',
								'".helper_RequestGetValue($i->expirationDate,'')."',
								'mas informacion',
								'".explode("|",helper_RequestGetValue($i->reference3,'0|0'))[0]."',
								'".explode("|",helper_RequestGetValue($i->reference3,'0|0'))[1]."',
								'".helper_RequestGetValue($i->reference4,'')."'
							]";
						}
						echo implode(",",$listrow);
					}
				?>],
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
							"sWidth"	: "20%"
						},
						{
							"aTargets"	: [5], //unidad
							"sWidth"	: "130px"
						},
						{
							"aTargets"	: [ 6 ],//cantidad
							"mRender"	: function ( data, type, full ) {
								var str = '<input type="text" class="col-lg-12 txtDetailQuantity txt-numeric" value="'+data+'" name="txtDetailQuantity[]" />';
								if (varUseMobile == "1")
									str = str + " <span class='badge badge-inverse' >Cantidad</span>";
								return str;
							}
						},
						{
							"aTargets"	: [ 7 ],//costo
							"mRender"	: function ( data, type, full ) {
								var str = '<input type="text" class="col-lg-12 txtDetailCost txt-numeric" value="'+data+'" name="txtDetailCost[]" />';
								if (varUseMobile == "1")
									str = str + " <span class='badge badge-inverse' >Costo</span>";
								return str;
							}
						},
						{
							"aTargets"	: [ 8 ],//precio
							"mRender"	: function ( data, type, full ) {
								var str = '<input type="text" class="col-lg-12 txtDetailPrice txt-numeric" value="'+data+'" name="txtDetailPrice[]" />';
								if (varUseMobile == "1")
									str = str + " <span class='badge badge-inverse' >Precio</span>";
								return str;
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
						},
						{
							"aTargets"		: [ 14 ],//txtReference4TransactionMasterDetail expandir barcode
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtReference4TransactionMasterDetail " value="'+data+'" name="txtReference4TransactionMasterDetail[]" />';
							}
						}
			]							
		});
		refreschChecked();
		onCompletePantalla();	
		
		if(objParameterINVENTORY_URL_PRINTER_INPUTUNPOST_SHOW_OPCIONES == "true"){		
			objectParameterButtoms.Imprmir_Cantidades=function(){
				fnWaitOpen();
				window.open("<?php echo base_url(); ?>/"+objParameterINVENTORY_URL_PRINTER_INPUTUNPOST_ONLY_QUANTITY +"/companyID/<?php echo $objTM->companyID;?>/transactionID/<?php echo $objTM->transactionID;?>/transactionMasterID/<?php echo $objTM->transactionMasterID;?>","_blank");
				fnWaitClose();
				$(this).dialog("close");
			};		
		}
		
		objectParameterButtoms.Imprimir=function(){
			fnWaitOpen();							
			window.open("<?php echo base_url(); ?>/"+objParameterUrlPrinter+"/companyID/<?php echo $objTM->companyID;?>/transactionID/<?php echo $objTM->transactionID;?>/transactionMasterID/<?php echo $objTM->transactionMasterID;?>","_blank");
			fnWaitClose();
			$(this).dialog("close");
		};		
		
		
		$("#modalDialogOpenPrimter").dialog({
				autoOpen: false,
				modal: true,
				width:520,
				dialogClass: "dialog",
				buttons: objectParameterButtoms
		});
		
		
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
			fnClearCreditLine();
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
		
		//Buscar Linea de Credito
		$(document).on("click","#btnSearchCreditLine",function(){		
			var timerNotification 	= 15000;
			if($("#txtProviderID").val() == "")
			{
				fnShowNotification("Seleccione un proveedor","error",timerNotification);
				return;
			}
			
			var url_request 		= "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCreditLine->componentID; ?>/onCompleteCreditLine/SELECCIONAR_LINEA_CREDITO/true/"+encodeURI('{\"entityID\"|\"'+$("#txtProviderID").val())+ '\"}' +"/false/not_redirect_when_empty";  
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteCreditLine = onCompleteCreditLine;  
		});	
		
		//Eliminar linea de credito
		$(document).on("click","#btnClearCreditLine",function(){
			fnClearCreditLine();
		});

		//Ir a Lista
		$(document).on("click","#btnBack",function(){
				fnWaitOpen();
		});
		//Printer
		$(document).on("click","#btnPrinter",function(){
			
			if(objParameterINVENTORY_URL_PRINTER_INPUTUNPOST_SHOW_OPCIONES == "false")
			{
				fnWaitOpen();							
				window.open("<?php echo base_url(); ?>/"+objParameterUrlPrinter+"/companyID/<?php echo $objTM->companyID;?>/transactionID/<?php echo $objTM->transactionID;?>/transactionMasterID/<?php echo $objTM->transactionMasterID;?>","_blank");
				fnWaitClose();
			}
			else
			{
				$("#modalDialogOpenPrimter").dialog("open");
				
			}
			return
			
			
			
			
			
			
		});
		
		//Archivos
		$(document).on("click","#btnClickArchivo",function(){
			window.open("<?php echo base_url()."/core_elfinder/index/componentID/".$objComponentInputSinPost->componentID."/componentItemID/".$objTM->transactionMasterID; ?>","blanck");
		});
		
		//Guardar Documento
		$(document).on("click","#btnAcept",function(){
				$( "#form-new-transaction" ).attr("method","POST");
				$( "#form-new-transaction" ).attr("action","<?php echo base_url(); ?>/app_inventory_inputunpost/save/edit");
				
				if(validateForm()){
					fnWaitOpen();
					$( "#form-new-transaction" ).submit();
				}
				
		});	
		
		$(document).on("change","#txtCurrencyID",function(){
			objTableDetailTransaction.fnClearTable();
		});
		
		//Eliminar el Documento
		$(document).on("click","#btnDelete",function(){
			fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
				fnWaitOpen();
				$.ajax({									
					cache       : false,
					dataType    : 'json',
					type        : 'POST',
					url  		: "<?php echo base_url(); ?>/app_inventory_inputunpost/delete",
					data 		: {companyID : <?php echo $objTM->companyID;?>, transactionID : <?php echo $objTM->transactionID;?> , transactionMasterID : <?php echo $objTM->transactionMasterID;?>  },
					success:function(data){
						console.info("complete delete success");
						fnWaitClose();
						if(data.error){
							fnShowNotification(data.message,"error");
						}
						else{
							window.location = "<?php echo base_url(); ?>/app_inventory_inputunpost/index";
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
			var url_request 		= "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?php echo $objComponentItem->componentID; ?>/onCompleteItem/SELECCIONAR_ITEM_TO_PROVIDER_T/false/"+encodeURI('{\"providerID\"|\"'+$("#txtProviderID").val()+'\",\"currencyID\"|\"'+$("#txtCurrencyID").val() +'\"}' ) + "/true/"+url_redirect+"/1/1/"+varParameterCantidadItemPoup;  
			
			// Verificar si la ventana ya está abierta
			if (objWindowSearchProduct && !objWindowSearchProduct.closed) 
			{
				objWindowSearchProduct.focus();
			} 
			else 
			{
				objWindowSearchProduct 					= window.open(url_request,"MsgWindow","width=900,height=450");
				objWindowSearchProduct.onCompleteItem 	= onCompleteItem; 
			}
			
		
			
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
			var precio2 												= objdat_[13];
			var txtReference4TransactionMasterDetail 					= objdat_[14];
			vencimiento 					= vencimiento.replace(" 00:00:00","");
			
			if(lote == "") lote = "0";
			if(vencimiento == "") vencimiento = moment().format("YYYY-MM-DD");
			
			var url_request = "<?php echo base_url(); ?>/app_inventory_inputunpost/add_masinformacion/onCompleteUpdateMasInformacion/"+itemID+"/"+transactionMasterDetailID+"/"+index; 
			url_request = url_request + "/"+lote+"/"+vencimiento+"/"+precio1+"/"+precio2+"/"+txtReference4TransactionMasterDetail;
			
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
		//Eliminar Item del Detalle
		$(document).on("click","#btnPrinterDetailTransaction",function(){
			var listRow 					= objTableDetailTransaction.fnGetData();							
			var length 						= listRow.length;
			var i 							= 0;
			var j 							= 0;
			var listeProductos 				= "0-0-0-0-0-0";
			
			while (i < length )
			{
				if(listRow[i][0] == true)
				{	
					
					listeProductos = listeProductos + "|"+ listRow[i][1] + "-" + 1 +"-"+  listRow[i][3]  +"-"+ listRow[i][4]  +"-"+ listRow[i][3] + "-" + listRow[i][8] ;					
				}	
				i++;
			}
			
			if(listeProductos == "0-0-0-0-0-0"){
				fnShowNotification("Seleccionar el Registro...","error");
				return;
			}
			var url = objParameterUrlPrinterCode+"/listItem/"+listeProductos;
			window.open(url, "_blank");	
			
			
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
		
		//Bodega Proveedor
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
		//Detalle
		if($("#txtFileImport").val() == ".csv")
		{
			var lengthRow = objTableDetailTransaction.fnGetData().length;
			if(lengthRow == 0){
				fnShowNotification("Agregar el Detalle del Documento","error",timerNotification);
				result = false;
			}
			else{
				
				for(var i = 0 ; i < objTableDetailTransaction.fnGetData().length ; i++)
				{
					var iElement = objTableDetailTransaction.fnGetData()[i];
					
					if
					( 
						iElement[6] == "" || 
						iElement[7] == "" || 
						iElement[12] == "" || 
						iElement[13] == ""
					)
					{
						fnShowNotification("No puede haber numeros mal formados , revisar producto " + iElement[3],"error",timerNotification);
						result = false;
					}
					
					if
					( 
						iElement[6] == "NaN" || 
						iElement[7] == "NaN" || 
						iElement[12] == "NaN" || 
						iElement[13] == "NaN"
					)
					{
						fnShowNotification("No puede haber numeros mal formados , revisar producto " + iElement[3],"error",timerNotification);
						result = false;
					}
					
					
					
					if(
						parseInt(iElement[6])  < 0  || 
						parseInt(iElement[7])  < 0  || 
						parseInt(iElement[12]) < 0  || 
						parseInt(iElement[13]) < 0
					)
					{
						fnShowNotification("No puede haber cantidades en negativos " + iElement[3] ,"error",timerNotification);
						result = false;
					}
					
				}
			}
		}

		//Validar causal
		if($("#txtCausalID").val() == "")
		{
			fnShowNotification("El causal es invalido ","error",timerNotification);
			result = false;
		}

		//Validar linea de credito si el causal es tipo credito
		if($("#txtCreditLineID").val() == 0 /*VACIO*/  && $("#txtCausalID").val() == 55 /*CREDITO*/)
		{
			fnShowNotification("Por favor configura una linea de credito al proveedor","error",timerNotification);
			result = false;
		}

		return result;
	}

	function fnClearCreditLine()
	{
		$("#txtCreditLineID").val("");
		$("#txtCreditLineDescription").val("");
	}

	function onCompleteUpdateMasInformacion(objResponse){
			var index 		= objResponse.txtPosition;
			var vencimiento = objResponse.txtVencimiento;
			var lote 		= objResponse.txtLote;
			var precio1 		= objResponse.txtPrecio1;
			var precio2 		= objResponse.txtPrecio2;
			var txtReference4TransactionMasterDetail 		= objResponse.txtReference4TransactionMasterDetail;
		
			
			var objdat_ = objTableDetailTransaction.fnGetData(index);		
			objTableDetailTransaction.fnUpdate( lote, index, 9 );
			objTableDetailTransaction.fnUpdate(  vencimiento, index, 10 );	
			objTableDetailTransaction.fnUpdate(  precio1, index, 12 );		
			objTableDetailTransaction.fnUpdate(  precio2, index, 13 );					
			objTableDetailTransaction.fnUpdate(  txtReference4TransactionMasterDetail, index, 14 );					
			
			
	}

	function onCompleteItem(objResponse){
		
		for(var i = 0 ; i < objResponse.length ; i++)
		{
			console.info("CALL onCompleteItem");			
			var cantidad = 1;
			if(objResponse[i].length >= 11)
			{
				if(objResponse[i][11] == "add_cantidad")
				{
					if(objResponse[i][9] == "")
					cantidad = 1;
					else
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
			objRow.lote 					= "";
			objRow.vencimiento				= "";
			objRow.price					= objResponse[i][3];
			objRow.masinfor					= "";
			objRow.precio1					= objResponse[i][4];
			objRow.precio2					= objResponse[i][5];
			objRow.barCodeExtende			= "";
			
			//Berificar que el Item ya esta agregado 
			if(jLinq.from(objTableDetailTransaction.fnGetData()).where(function(obj){ return obj[1] == objRow.itemID;}).select().length > 0 )
			{
				fnShowNotification("El Item ya esta agregado","error");
				return;
			}
			else
			{
				
				objTableDetailTransaction.fnAddData
				(
					[
					objRow.checked,objRow.itemID,objRow.transactionMasterDetail,
					objRow.itemNumber,objRow.itemName,objRow.itemUM,
					objRow.quantity,objRow.cost,objRow.price,
					objRow.lote,objRow.vencimiento,objRow.masinfor,
					objRow.precio1,objRow.precio2,
					objRow.barCodeExtende
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
		
		if(varUseMobile == "1")
		{
			$("#tb_transaction_master_detail td").css("display","block");
		}
		
		
	}
	function onCompleteOrdenCompra(objResponse){
		console.info("CALL onCompleteOrdenCompra");
		$("#txtTransactionMasterIDOrdenCompra").val(objResponse[0][1]);
		$("#txtTransactionNumberOrdenCompra").val(objResponse[0][2]);
	
	}
	function onCompleteProvider(objResponse){
		console.info("CALL onCompleteProvider");
		fnClearCreditLine();
		objTableDetailTransaction.fnClearTable();
		$("#txtProviderID").val(objResponse[0][1]);
		$("#txtProviderDescription").val(objResponse[0][2] + " / " + objResponse[0][3]);
	
	}		
	function onCompleteCreditLine(objResponse){
		console.info("CALL onCompleteCreditLine");
		$("#txtCreditLineID").val(objResponse[0][0]);
		$("#txtCreditLineDescription").val(objResponse[0][2]);
	
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
	
	function onCompletePantalla(){
		
		
		
		if(varUseMobile == "1" ){
		   $(".elementMovilOculto").addClass("hidden");		   
		   
		   $("#tb_transaction_master_detail_wrapper .dataTables_scrollHead .table.table-bordered.dataTable th").css("display","none");
		   $("#tb_transaction_master_detail th").css("display","none");
		   $("#tb_transaction_master_detail td").css("display","block");
		   
	    }
	   
	    //$("#divLoandingCustom").remove();
		
	}
	
</script>
