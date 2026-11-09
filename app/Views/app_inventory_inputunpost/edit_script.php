<!-- ./ page heading -->
<script>	
	var numberDecimal												= 2;
	var numberDecimalSummary										= 2;
	var numberDecimalSummaryRound									= false;
	var objTableDetailTransaction 									= {};
	var objListaProductos											= {};
	var objWindowSearchProduct;								
	var objListaProductos2											= {};
	var objListaProductos3											= {};
	var objectParameterButtoms 										= {};
	var objParameterUrlPrinter										= '<?php echo $objParameterUrlPrinter; ?>';
	var objParameterUrlPrinterCode  								= '<?php echo $objParameterMasive; ?>';
	var varUseMobile												= '<?php echo $useMobile; ?>';
	var objParameterCORE_VIEW_CUSTOM_SCROLL_IN_DETATAIL_PURSHASE	= <?php echo $objParameterCORE_VIEW_CUSTOM_SCROLL_IN_DETATAIL_PURSHASE; ?>;
	var objParameterINVENTORY_URL_PRINTER_INPUTUNPOST_ONLY_QUANTITY	= '<?php echo $objParameterINVENTORY_URL_PRINTER_INPUTUNPOST_ONLY_QUANTITY; ?>';
	var objParameterINVENTORY_URL_PRINTER_INPUTUNPOST_SHOW_OPCIONES	= '<?php echo $objParameterINVENTORY_URL_PRINTER_INPUTUNPOST_SHOW_OPCIONES; ?>';
	var sScrollY 													= objParameterCORE_VIEW_CUSTOM_SCROLL_IN_DETATAIL_PURSHASE == true ?  "350px" : "auto";
	var varParameterCantidadItemPoup								= '<?php echo $objParameterCantidadItemPoup; ?>';  
	var columnIndexCheck 			= 0;
	var columnIndexItemID 			= 1;
	var columnIndexTransactionMasterID = 2;
	var columnIndexBarCode 			= 3;
	var columnIndexName 			= 4;
	var columnIndexUnidad 			= 5;
	var columnIndexCantidad 		= 6;
	var columnIndexCostoBruto 		= 7;
	var columnIndexCostoNeto 		= 8;
	var columnIndexPrice 			= 9;
	var columnIndexLote 			= 10;
	var columnIndexExpiracion 		= 11;
	var columnIndexMasInformacion 	= 12;
	var columnIndexPrice2 			= 13;
	var columnIndexPrice3 			= 14;
	var columnIndexExpandirCode 	= 15;
	var columnIndexIVA 				= 16;
	var columnIndexISC 				= 17;
	var columnIndexSubTotal 		= 18;
	
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
								fnFormatNumber(".$i->unitaryCost.",numberDecimal),
								fnFormatNumber(".($i->unitaryAmount).",2),
								'".helper_RequestGetValue($i->lote,'')."',
								'".helper_RequestGetValue($i->expirationDate,'')."',
								'mas informacion',
								'".explode("|",helper_RequestGetValue($i->reference3,'0|0'))[0]."',
								'".explode("|",helper_RequestGetValue($i->reference3,'0|0'))[1]."',
								'".helper_RequestGetValue($i->reference4,'')."',								
								fnFormatNumber(".$i->tax1.",numberDecimal),
								fnFormatNumber(".$i->tax2.",numberDecimal),
								fnFormatNumber(".($i->quantity * $i->unitaryCost).",numberDecimal),
							]";
						}
						echo implode(",",$listrow);
					}
				?>],
			"aoColumnDefs": [ 
						{
							"aTargets"	: [ columnIndexCheck ],//checked
							"mRender"	: function ( data, type, full ) {
								if (data == false)
								return '<input type="checkbox"  class="classCheckedDetail"  value="0" ></span>';
								else
								return '<input type="checkbox"  class="classCheckedDetail" checked="checked" value="0" ></span>';
							}
						},
						{
							"aTargets"		: [ columnIndexItemID ],//itemID
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtDetailItemID[]" />';
							}
						},
						{
							"aTargets"		: [columnIndexTransactionMasterID],//transactionMasterDetailID
							"bVisible"  	: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtDetailTransactionDetailID[]" />';
							}
						},
						{
							"aTargets"	: [columnIndexName], //nombre
							"sWidth"	: "120px",
							"mRender"		: function ( data, type, full ) {
								return '<input type="text" value="'+data+'"  />';
							}
						},
						{
							"aTargets"	: [columnIndexUnidad], //unidad
							"sWidth"	: "130px"
						},
						{
							"aTargets"	: [ columnIndexCantidad ],//cantidad
							"mRender"	: function ( data, type, full ) {
								var str =  '<input type="text" class="col-lg-12 txtDetailQuantity txt-numeric" value="'+data+'" name="txtDetailQuantity[]" />';
								if (varUseMobile == "1")
									str = str + " <span class='badge badge-inverse' >Cantidad</span>";
								return str;
							}
						},
						{
							"aTargets"	: [ columnIndexCostoBruto ],//costo bruto
							"mRender"	: function ( data, type, full ) {
								var str = '<input type="text" class="col-lg-12 txtDetailCost txt-numeric" value="'+data+'" name="txtDetailCostBruto[]" />';
								if (varUseMobile == "1")
									str = str + " <span class='badge badge-inverse' >Costo</span>";
								return str;
							}
						},
						{
							"aTargets"	: [ columnIndexCostoNeto ],//costo net = costo bruto - descuento
							"bVisible"  	: true,
							"sClass" 		: "hidden",
							"mRender"	: function ( data, type, full ) {
								var str = '<input type="text" class="col-lg-12 txtDetailCost txt-numeric" value="'+data+'" name="txtDetailCost[]" />';								
								return str;
							}
						},
						{
							"aTargets"	: [ columnIndexPrice ],//precio
							"mRender"	: function ( data, type, full ) {
								var str = '<input type="text" class="col-lg-12 txtDetailPrice txt-numeric" value="'+data+'" name="txtDetailPrice[]" />';
								if (varUseMobile == "1")
									str = str + " <span class='badge badge-inverse' >Precio</span>";
								return str;
							}
						},
						{
							"aTargets"		: [ columnIndexLote ],//lote
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailLote txt-numeric"" value="'+data+'" name="txtDetailLote[]" readonly="true" />';
							}
						},
						{
							"aTargets"		: [ columnIndexExpiracion ],//fecha expiracion
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailVencimiento txt-numeric"" value="'+data+'" name="txtDetailVencimiento[]" readonly="true" />';
							}
						},
						{
							"aTargets"	: [ columnIndexMasInformacion ],//mas informacion
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
							"aTargets"		: [ columnIndexPrice2 ],//precio 02
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailPrice2 txt-numeric"" value="'+data+'" name="txtDetailPrice2[]" />';
							}
						},
						{
							"aTargets"		: [ columnIndexPrice3 ],//precio 03
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailPrice3 txt-numeric"" value="'+data+'" name="txtDetailPrice3[]" />';
							}
						},
						{
							"aTargets"		: [ columnIndexExpandirCode ],//txtReference4TransactionMasterDetail expandir barcode
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtReference4TransactionMasterDetail" value="'+data+'" name="txtReference4TransactionMasterDetail[]" />';
							}
						},
						{
							"aTargets"		: [ columnIndexIVA ],//IVA
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailIva txt-numeric"" value="'+data+'" name="txtDetailIva[]" />';
							}
						},
						{
							"aTargets"		: [ columnIndexISC ],//ISC
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailIsc txt-numeric"" value="'+data+'" name="txtDetailIsc[]" />';
							}
						},
						{
							"aTargets"		: [ columnIndexSubTotal ],//Sub Total
							"bVisible"		: true,
							"sWidth"		: "120px",
							"bSearchable"	: false,
							"mRender"	: function ( data, type, full ) {
								return '<div style="text-align:right" ><span class="badge badge-success" >'+data+'</span></div>';
							}
						}
			]							
		});
		refreschChecked();
		onCompletePantalla();	
		fnUpdateDetail();
		
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
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?php echo $objComponentItem->componentID; ?>/onCompleteProvider/SELECCIONAR_PROVEEDOR_PAGINATED/true/empty/true/not_redirect_when_empty/1/1/"+varParameterCantidadItemPoup+"/";
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

				//Campo de archivo vacio = sin importacion
				if($.trim($("#txtFileImport").val()) == "")
					$("#txtFileImport").val(".csv");

				if(validateForm()){

					//Si hay un archivo .csv: validar, importar por lotes y luego guardar
					if(fnImportHayArchivo() && $("#txtFileImportProcesado").val() != "1")
					{
						fnImportValidarArchivo();
						return;
					}

					fnWaitOpen();
					$( "#form-new-transaction" ).submit();
				}

		});

		//Importar: mismo flujo que guardar con archivo (validar, lotes, guardar)
		$(document).on("click","#btnImportDetailTransaction",function(e){
				e.preventDefault();
				if(!fnImportHayArchivo())
				{
					fnShowNotification("Seleccione el archivo .csv subido en Archivos","error",8000);
					return;
				}
				$("#txtFileImportProcesado").val("0");
				$("#btnAcept").trigger("click");
		});

		//Si cambia el archivo se debe volver a importar
		$(document).on("change","#txtFileImport",function(){
				$("#txtFileImportProcesado").val("0");
				$("#txtImportModoRepetidos").val("");
		});

		//Recargar el combo de archivos (por si se subio uno nuevo en Archivos)
		$(document).on("click","#btnImportRefrescarArchivos",function(e){
				e.preventDefault();
				fnImportCargarArchivos(true);
		});
		fnImportCargarArchivos(false);

		//Dialogo de codigos repetidos (3 opciones)
		//(pimp-dialog: estilo moderno definido en resumen_flotante.php; el encabezado va dentro del contenido)
		$("#modalDialogImportRepetidos").dialog({
				autoOpen	: false,
				modal		: true,
				width		: Math.min(600, $(window).width() - 32),
				dialogClass	: "dialog pimp-dialog",
				open		: fnImportDialogoAbierto,
				buttons		: [
					{ text : "Cancelar", "class" : "pimp-btn", click : function(){
						$(this).dialog("close");
					}},
					{ text : "Omitir repetidos", "class" : "pimp-btn pimp-btn-warn", click : function(){
						$(this).dialog("close");
						fnImportIniciarLotes("omitir");
					}},
					{ text : "Generar codigo nuevo", "class" : "pimp-btn pimp-btn-primary", click : function(){
						$(this).dialog("close");
						fnImportIniciarLotes("nuevo");
					}}
				]
		});

		//Dialogo de progreso de la importacion (sin boton cerrar: no se debe interrumpir)
		$("#modalDialogImportProgreso").dialog({
				autoOpen		: false,
				modal			: true,
				width			: Math.min(480, $(window).width() - 32),
				dialogClass		: "dialog pimp-dialog",
				closeOnEscape	: false,
				open			: fnImportDialogoAbierto
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
			
			var itemID 													= $(this).data("itemid");
			var transactionMasterDetailID 								= $(this).data("transactionmasterdetailid");
			var tr 														= $(this).parent().parent()[0];
			var index 													= objTableDetailTransaction.fnGetPosition(tr);
			var objdat_ 												= objTableDetailTransaction.fnGetData(index);	
			var costo 													= objdat_[columnIndexCostoBruto];			
			var lote 													= objdat_[columnIndexLote];
			var vencimiento 											= objdat_[columnIndexExpiracion];
			var precio1 												= objdat_[columnIndexPrice2];
			var precio2 												= objdat_[columnIndexPrice3];
			var txtReference4TransactionMasterDetail 					= objdat_[columnIndexExpandirCode];
			var iva 													= objdat_[columnIndexIVA];
			var isc	 													= objdat_[columnIndexISC];
			
			
			
			vencimiento 					= vencimiento.replace(" 00:00:00","");
			
			if(lote == "") lote = "0";
			if(vencimiento == "") vencimiento = moment().format("YYYY-MM-DD");			
			if(txtReference4TransactionMasterDetail == "") 	txtReference4TransactionMasterDetail = "_";
			
			var url_request = "<?php echo base_url(); ?>/app_inventory_inputunpost/add_masinformacion/onCompleteUpdateMasInformacion/"+itemID+"/"+transactionMasterDetailID+"/"+index; 
			url_request = url_request + "/"+lote+"/"+vencimiento+"/"+precio1+"/"+precio2+"/"+iva+"/"+isc+"/"+txtReference4TransactionMasterDetail+"/"+costo;
			
			
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
			objTableDetailTransaction.fnUpdate( $(this).val(), objind_, columnIndexCantidad );
			
			var result 	=  parseFloat($(this).val()) * parseFloat(objdat_[columnIndexCostoBruto]);
			result 		= result.toFixed(2);			
			objTableDetailTransaction.fnUpdate(  result, objind_, columnIndexSubTotal );
			fnUpdateDetail();
			refreschChecked();
		})
		//Cambio en el Precio
		$(document).on("blur",".txtDetailPrice",function(){
			debugger;
			$(this).val(fnFormatFloat(fnFormatNumber(fnFormatFloat($(this).val()),numberDecimal)));
			var objrow_ = $(this).parent().parent()[0];
			var objind_ = objTableDetailTransaction.fnGetPosition(objrow_);
			var objdat_ = objTableDetailTransaction.fnGetData(objind_);			
			
			//actualizar el nuevo precio			
			<?php echo getBahavioSession($company->type, "app_inventory_inputunpost", "scriptCalculateCostChangeDetail", "",$objListCompanyPageSetting); ?>
			
			
			//actualisar el nuevo costo	neto		
			objTableDetailTransaction.fnUpdate(  newValue , objind_, columnIndexCostoNeto );
			
			//actualizar el nuevo costo bruto
			objTableDetailTransaction.fnUpdate(  newValue, objind_, columnIndexCostoBruto );
			var result 	= parseFloat(objdat_[columnIndexCantidad]) *  parseFloat(newValue) ;
			result 		= result.toFixed(2);
			
			//actuaoizar sub total
			objTableDetailTransaction.fnUpdate( result, objind_, columnIndexSubTotal );
			fnUpdateDetail();
			refreschChecked();
		});
		//Cambio en el Costo
		$(document).on("blur",".txtDetailCost",function(){
			$(this).val(fnFormatFloat(fnFormatNumber(fnFormatFloat($(this).val()),numberDecimal)));
			var objrow_ = $(this).parent().parent()[0];
			var objind_ = objTableDetailTransaction.fnGetPosition(objrow_);
			var objdat_ = objTableDetailTransaction.fnGetData(objind_);		
			
			//actualizar el nuevo precio
			<?php echo getBahavioSession($company->type, "app_inventory_inputunpost", "scriptCalculatePriceChangeDetail", "",$objListCompanyPageSetting); ?>			
			
			
			//actualisar el nuevo costo	neto		
			objTableDetailTransaction.fnUpdate($(this).val() , objind_, columnIndexCostoNeto );


			//actualizar costo bruto
			objTableDetailTransaction.fnUpdate( $(this).val(), objind_, columnIndexCostoBruto );
			var result 	= parseFloat(objdat_[columnIndexCantidad]) *  parseFloat($(this).val()) ;
			result 		= result.toFixed(2);
			
			//actualizar subo total
			objTableDetailTransaction.fnUpdate(  result, objind_, columnIndexSubTotal );
			fnUpdateDetail();
			refreschChecked();
		});
		//Cambio en el Descuento
		$(document).on("change","input#txtDiscount",function(){
			fnUpdateDetail();
		});
		//Cambio en el Iva
		$(document).on("change","input#txtIva",function(){
			fnUpdateDetail();
		});
		//Cambio en el Isc
		$(document).on("change","input#txtIsc",function(){
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

		//Rediseno inventario: etiquetas de celdas, contador, estado vacio y barra del telefono
		if(window.PosmeInv){ PosmeInv.initDetail({table:"#tb_transaction_master_detail", totalSelector:"#txtTotal"}); }
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
						iElement[columnIndexCantidad] == "" || 
						iElement[columnIndexCostoBruto] == "" || 
						iElement[columnIndexPrice2] == "" || 
						iElement[columnIndexPrice3] == ""
					)
					{
						fnShowNotification("No puede haber numeros mal formados , revisar producto " + iElement[3],"error",timerNotification);
						result = false;
					}
					
					if
					( 
						iElement[columnIndexCantidad] == "NaN" || 
						iElement[columnIndexCostoBruto] == "NaN" || 
						iElement[columnIndexPrice2] == "NaN" || 
						iElement[columnIndexPrice3] == "NaN"
					)
					{
						fnShowNotification("No puede haber numeros mal formados , revisar producto " + iElement[3],"error",timerNotification);
						result = false;
					}
					
					
					
					if(
						parseInt(iElement[columnIndexCantidad])  < 0  || 
						parseInt(iElement[columnIndexCostoBruto])  < 0  || 
						parseInt(iElement[columnIndexPrice2]) < 0  || 
						parseInt(iElement[columnIndexPrice3]) < 0
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

	/////////////////////////////////////////////
	//Importacion de archivo .csv por lotes
	/////////////////////////////////////////////
	var importTamanoLote = 200; //filas por peticion: cada una termina antes del timeout del servidor

	//Hay archivo cuando el campo tiene un nombre distinto a la extension por defecto
	function fnImportHayArchivo()
	{
		var archivo = $.trim($("#txtFileImport").val());
		return archivo != "" && archivo != ".csv";
	}

	//Llena el combo con los .csv del documento (el servidor los devuelve del mas nuevo al mas antiguo).
	//Conserva la seleccion actual si el archivo sigue existiendo.
	//El tema (theme-genyx/js/app.js) aplica uniform() a todos los select: lo envuelve en div.selector de ancho fijo
	//y tapa el texto con su propia flecha. Este combo usa el select nativo con el estilo de resumen_flotante.php.
	function fnImportQuitarUniform()
	{
		var $combo = $("#txtFileImport");
		if($combo.parent().hasClass("selector"))
		{
			if($.uniform && typeof $.uniform.restore == "function")
				$.uniform.restore($combo);
			else
			{
				$combo.siblings("span").remove();
				$combo.unwrap().css("opacity","");
			}
		}
	}

	function fnImportCargarArchivos(avisar)
	{
		fnImportQuitarUniform();
		var $combo 		= $("#txtFileImport");
		var actual 		= $combo.val();
		$.ajax({
			cache 		: false,
			dataType 	: "json",
			type 		: "POST",
			url 		: "<?php echo base_url(); ?>/app_inventory_inputunpost/listarArchivosImportacion",
			data 		: {
				transactionID 		: <?php echo $objTM->transactionID;?>,
				transactionMasterID : <?php echo $objTM->transactionMasterID;?>
			},
			success 	: function(json){
				//Error
				if(json.error)
				{
					if(avisar) fnShowNotification(fnImportEscapar(json.message),"error",8000);
					return;
				}

				fnImportQuitarUniform();
				$combo.find("option").not("[value='.csv']").remove();

				//Sin datos
				if(!json.data || json.data.length == 0)
				{
					$combo.val(".csv");
					if(avisar) fnShowNotification("No hay archivos .csv en Archivos","error",6000);
					return;
				}

				//Con datos
				$.each(json.data,function(i,archivo){
					$combo.append($("<option></option>").val(archivo.nombre).text(archivo.nombre + "  (" + archivo.fecha + ")"));
				});
				$combo.val($combo.find("option[value='" + String(actual).replace(/'/g,"\\'") + "']").length ? actual : ".csv");
				if(avisar) fnShowNotification(json.data.length + " archivo(s) .csv encontrados","success",4000);
			},
			error 		: function(){
				if(avisar) fnShowNotification("No se pudo obtener la lista de archivos","error",8000);
			}
		});
	}

	//Parametros comunes de las peticiones de importacion
	function fnImportParametros()
	{
		return {
			transactionID 		: <?php echo $objTM->transactionID;?>,
			transactionMasterID : <?php echo $objTM->transactionMasterID;?>,
			fileName 			: $.trim($("#txtFileImport").val())
		};
	}

	//Paso 1: validar el archivo sin grabar (columnas, numeros, fechas y codigos repetidos)
	function fnImportValidarArchivo()
	{
		fnWaitOpen();
		$.ajax({
			cache 		: false,
			dataType 	: 'json',
			type 		: 'POST',
			url 		: "<?php echo base_url(); ?>/app_inventory_inputunpost/validarArchivoImportacion",
			data 		: fnImportParametros(),
			success 	: function(json){
				fnWaitClose();

				//Error
				if(json.error === true)
				{
					fnShowNotification(fnImportEscapar(json.message),"error",15000);
					return;
				}

				//Sin datos
				if(!json.data || json.data.filasConDatos == 0)
				{
					fnShowNotification("El archivo no tiene filas para importar","error",15000);
					return;
				}

				//Con datos: errores de contenido bloquean la importacion
				if(json.data.cantidadErrores > 0)
				{
					var mensaje = "El archivo tiene " + json.data.cantidadErrores + " error(es):<br/>" + json.data.errores.slice(0,10).map(fnImportEscapar).join("<br/>");
					if(json.data.cantidadErrores > 10)
						mensaje = mensaje + "<br/>...";
					fnShowNotification(mensaje,"error",30000);
					return;
				}

				//Codigos repetidos: preguntar si se omiten o se genera codigo nuevo
				if(json.data.cantidadCodigosRepetidos > 0)
				{
					fnImportMostrarRepetidos(json.data);
					return;
				}

				fnImportIniciarLotes("");
			},
			error 		: function(xhr){
				fnWaitClose();
				fnShowNotification("No se pudo validar el archivo (error " + xhr.status + ")","error",15000);
			}
		});
	}

	//Muestra la lista de codigos repetidos y las opciones
	function fnImportMostrarRepetidos(data)
	{
		$("#lblImportRepetidosResumen").html(
			"<b>" + fnImportMiles(data.cantidadCodigosRepetidos) + "</b> codigo(s) repetidos &middot; " +
			"<b>" + fnImportMiles(data.cantidadFilasRepetidas) + "</b> fila(s) de mas. ¿Que desea hacer?"
		);

		var html = "<table class='pimp-tabla'><thead><tr><th>Codigo</th><th>Nombre</th><th>Filas</th></tr></thead><tbody>";
		for(var i = 0; i < data.repetidos.length; i++)
		{
			var r 		= data.repetidos[i];
			var filas 	= "";
			for(var j = 0; j < r.filas.length; j++)
				filas += "<span class='pimp-chip'>" + fnImportEscapar(r.filas[j]) + "</span>";
			html += "<tr><td class='pimp-codigo'>" + fnImportEscapar(r.codigo) + "</td><td>" + fnImportEscapar(r.nombre) + "</td><td>" + filas + "</td></tr>";
		}
		if(data.cantidadCodigosRepetidos > data.repetidos.length)
			html += "<tr><td colspan='3' class='pimp-mas'>... y " + fnImportMiles(data.cantidadCodigosRepetidos - data.repetidos.length) + " codigo(s) mas</td></tr>";
		html += "</tbody></table>";

		$("#divImportRepetidosLista").html(html);
		$("#modalDialogImportRepetidos").dialog("open");
	}

	//Paso 2: importar el archivo en lotes consecutivos
	function fnImportIniciarLotes(modoRepetidos)
	{
		$("#txtImportModoRepetidos").val(modoRepetidos);
		$("#divImportProgreso").removeClass("pimp-terminado");
		$("#lblImportTitulo").text("Importando productos");
		$("#lblImportArchivo").text($.trim($("#txtFileImport").val()));
		$("#lblImportProgreso").text("Preparando...");
		fnImportPintarAvance(0, {insertadas:0, omitidas:0, productosNuevos:0});
		$("#modalDialogImportProgreso").dialog("open");
		fnImportEnviarLote(0, modoRepetidos, {insertadas:0, omitidas:0, productosNuevos:0});
	}

	function fnImportEnviarLote(offset, modoRepetidos, acumulado)
	{
		var parametros 				= fnImportParametros();
		parametros.offset 			= offset;
		parametros.limite 			= importTamanoLote;
		parametros.modoRepetidos 	= modoRepetidos;
		parametros.txtProviderID 	= $("#txtProviderID").val();
		parametros.txtWarehouseID 	= $("#txtWarehouseID").val();
		parametros.txtCurrencyID 	= $("#txtCurrencyID").val();

		$.ajax({
			cache 		: false,
			dataType 	: 'json',
			type 		: 'POST',
			url 		: "<?php echo base_url(); ?>/app_inventory_inputunpost/importarArchivoLote",
			data 		: parametros,
			success 	: function(json){

				//Error: se detiene; al reintentar se reemplaza todo el detalle (offset 0)
				if(json.error === true)
				{
					$("#modalDialogImportProgreso").dialog("close");
					fnShowNotification("Error importando a partir de la fila " + (offset + 2) + ": " + fnImportEscapar(json.message),"error",30000);
					return;
				}

				//Sin datos
				if(!json.data)
				{
					$("#modalDialogImportProgreso").dialog("close");
					fnShowNotification("El servidor no devolvio el avance de la importacion","error",15000);
					return;
				}

				//Con datos: acumular y mostrar avance
				acumulado.insertadas 		+= json.data.insertadas;
				acumulado.omitidas 			+= json.data.omitidas;
				acumulado.productosNuevos 	+= json.data.productosNuevos;

				var porcentaje = json.data.total == 0 ? 100 : Math.round((json.data.siguiente * 100) / json.data.total);
				fnImportPintarAvance(porcentaje, acumulado);
				$("#lblImportProgreso").text("Procesadas " + fnImportMiles(json.data.siguiente) + " de " + fnImportMiles(json.data.total) + " filas");

				if(!json.data.terminado)
				{
					fnImportEnviarLote(json.data.siguiente, modoRepetidos, acumulado);
					return;
				}

				//Paso 3: detalle importado, guardar el documento sin reprocesar el archivo
				//La ventana queda abierta en estado "completado" mientras se guarda (la pagina se recarga al terminar)
				$("#divImportProgreso").addClass("pimp-terminado");
				$("#lblImportTitulo").text("Importacion completa");
				$("#lblImportProgreso").text("Guardando la compra...");
				fnShowNotification(
					"Importacion completa: " + fnImportMiles(acumulado.insertadas) + " linea(s), " +
					fnImportMiles(acumulado.productosNuevos) + " producto(s) nuevo(s), " +
					fnImportMiles(acumulado.omitidas) + " omitida(s). Guardando...","success",15000
				);
				$("#txtFileImportProcesado").val("1");
				$("#form-new-transaction").submit();
			},
			error 		: function(xhr){
				$("#modalDialogImportProgreso").dialog("close");
				fnShowNotification("Error de conexion importando a partir de la fila " + (offset + 2) + " (error " + xhr.status + "). Intente de nuevo.","error",30000);
			}
		});
	}

	//Actualiza porcentaje, barra y contadores de la ventana de progreso
	function fnImportPintarAvance(porcentaje, acumulado)
	{
		$("#barImportProgreso").css("width", porcentaje + "%");
		$("#lblImportPorcentaje").text(porcentaje + "%");
		$("#lblImportInsertadas").text(fnImportMiles(acumulado.insertadas));
		$("#lblImportNuevos").text(fnImportMiles(acumulado.productosNuevos));
		$("#lblImportOmitidas").text(fnImportMiles(acumulado.omitidas));
	}

	//Al abrir una ventana de importacion: fondo oscuro difuminado propio (el overlay de jQuery UI esta fuera del dialogo)
	function fnImportDialogoAbierto()
	{
		$(".ui-widget-overlay").last().addClass("pimp-overlay");
	}

	//Formato de miles para los contadores
	function fnImportMiles(numero)
	{
		return Number(numero).toLocaleString("en-US");
	}

	//Evita inyectar HTML con los textos del archivo
	function fnImportEscapar(texto)
	{
		return $("<div>").text(texto == null ? "" : texto).html();
	}

	function fnClearCreditLine()
	{
		$("#txtCreditLineID").val("");
		$("#txtCreditLineDescription").val("");
	}

	function onCompleteUpdateMasInformacion(objResponse){
			var index 										= objResponse.txtPosition;
			var vencimiento 								= objResponse.txtVencimiento;
			var lote 										= objResponse.txtLote;
			var precio1 									= objResponse.txtPrecio1;
			var precio2 									= objResponse.txtPrecio2;
			var txtReference4TransactionMasterDetail 		= objResponse.txtReference4TransactionMasterDetail;
			var iva											= objResponse.txtIva;
			var isc											= objResponse.txtIsc;
		
			
			var objdat_ = objTableDetailTransaction.fnGetData(index);		
			objTableDetailTransaction.fnUpdate( lote, index, columnIndexLote );
			objTableDetailTransaction.fnUpdate(  vencimiento, index, columnIndexExpiracion );	
			objTableDetailTransaction.fnUpdate(  precio1, index, columnIndexPrice2 );		
			objTableDetailTransaction.fnUpdate(  precio2, index, columnIndexPrice3 );					
			objTableDetailTransaction.fnUpdate(  txtReference4TransactionMasterDetail, index, columnIndexExpandirCode );		
			objTableDetailTransaction.fnUpdate(  iva, index, columnIndexIVA );		
			objTableDetailTransaction.fnUpdate(  isc, index, columnIndexISC );				
			fnUpdateDetail();		
			
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
			objRow.cost 					= objResponse[i][2] == "" ? 0 : objResponse[i][2];
			objRow.costNeto 				= objRow.cost;
			objRow.lote 					= "";
			objRow.vencimiento				= "";
			objRow.price					= objResponse[i][3];
			objRow.masinfor					= "";
			objRow.precio1					= objResponse[i][4];
			objRow.precio2					= objResponse[i][5];
			objRow.barCodeExtende			= "";
			objRow.subTotal					= cantidad * objResponse[i][2];
			objRow.iva						= 0;
			objRow.isc						= 0;
			
			
			//Calcular el descuento			
			<?php echo getBahavioSession($company->type, "app_inventory_inputunpost", "scriptCalculatePrice", "",$objListCompanyPageSetting); ?>
			<?php echo getBahavioSession($company->type, "app_inventory_inputunpost", "scriptCalculateCost",  "",$objListCompanyPageSetting); ?>
			
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
					objRow.checked,
					objRow.itemID,
					objRow.transactionMasterDetail,
					objRow.itemNumber,
					objRow.itemName,
					objRow.itemUM,
					objRow.quantity,
					objRow.cost,
					objRow.costNeto,
					objRow.price,
					objRow.lote,
					objRow.vencimiento,
					objRow.masinfor,
					objRow.precio1,
					objRow.precio2,
					objRow.barCodeExtende,
					objRow.iva,
					objRow.isc,
					objRow.subTotal
					]
				);
				
			}
			
			
		}
		
		
		refreschChecked();
		var lastRow 	= $(".txtDetailQuantity").length ;
		lastRow 		= lastRow - 1;
		$($(".txtDetailQuantity")[lastRow]).focus();
		fnUpdateDetail();
		
	}
	//Refresh
	function refreschChecked(){
		/*
		$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
		$('.txtDetailQuantity').mask('000,000.00', {reverse: true});
		$('.txtDetailCost').mask('000,000.00', {reverse: true});
		
		$('#txtDiscount').mask('000,000.00', {reverse: true});
		$('#txtIva').mask('000,000.00', {reverse: true});
		$('#txtIsc').mask('000,000.00', {reverse: true});
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
		var discount	= $("#txtDiscount").val();						
		var total		= 0;
		var iva			= 0;
		var isc			= 0;
		
		//El descuento puede venir con comas de miles: se quitan antes de convertirlo
		discount		= fnFormatFloat(fnFormatNumber(String(discount).replace(/,/g,""),numberDecimal));
		if(isNaN(discount)) discount = 0;

		for(var i = 0; i < objTableDetailTransaction.fnGetData().length; i++){
			var row 		= objTableDetailTransaction.fnGetData()[i];
			console.log(row);
			var quantity 	= row[columnIndexCantidad];
			var ivaRow		= row[columnIndexIVA];
			var iscRow		= row[columnIndexISC];
			subtotal 		= subtotal + fnFormatFloat(fnFormatNumber((row[columnIndexCantidad] * row[columnIndexCostoBruto]),numberDecimal));
			iva				= iva + fnFormatFloat(fnFormatNumber(ivaRow*quantity,numberDecimal));
			isc				= isc + fnFormatFloat(fnFormatNumber(iscRow*quantity,numberDecimal));
		}
		console.log("IVA; ",iva);
		console.log("ISC; ",isc);
		subtotal		= parseInt(subtotal * 100) / 100;
		subtotal		= fnFormatFloat(fnFormatNumber(subtotal ,numberDecimalSummary));
		total			= parseInt(((subtotal + iva + isc) - discount) * 100) / 100;
		total			= fnFormatFloat(fnFormatNumber(total,numberDecimalSummary));
		//Resumen visual: miles con coma y dos decimales (el servidor quita las comas con helper_StringToNumber)
		$("#txtDiscount").val(fnResumenDosDecimales(discount));
		$("#txtSubTotal").val(fnResumenDosDecimales(subtotal));
		$("#txtTotal").val(fnResumenDosDecimales(total));
		$("#txtIsc").val(fnResumenDosDecimales(isc));
		$("#txtIva").val(fnResumenDosDecimales(iva));
		
		//fnShowNotification("TOTAL: C$ " +  $.number(total) ,"success");
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
	
	//Telefono: combos nativos (sin select2) y calendario nativo en las fechas. En PC no cambia nada
	$(document).ready(function(){
		PosmeInv.mobileSelects();
		PosmeInv.mobileDates("#txtTransactionOn");
	});
</script>
