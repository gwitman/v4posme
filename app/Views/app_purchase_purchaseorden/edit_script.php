				<!-- ./ page heading -->
				<script>
					var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';
					var objTableDetailTransaction = {};
					$(document).ready(function(){
						//Inicializar Controles
						pv2InitDate("txtTransactionOn", false);

						objTableDetailTransaction = $("#tb_transaction_master_detail").dataTable({
							"bPaginate"		: false,
							"bFilter"		: false,
							"bSort"			: false,
							"bInfo"			: false,
							"bAutoWidth"	: false,
							"oLanguage"		: { "sEmptyTable" : "Este documento no tiene productos. Toca Agregar para empezar." },
							"fnCreatedRow"	: fnDetailRowCreated,
							"fnDrawCallback": fnDetailDraw,
							"aaData": [
								<?php
									if($objTMD){
										$listrow = [];
										foreach($objTMD as $i)
										{
										$listrow[] = "[0,".$i->componentItemID.",".$i->transactionMasterDetailID.",'".$i->itemNumber."','".$i->itemName."','".$i->unitMeasureName."',".sprintf("%01.4f", $i->quantity)."]";
										}
										echo implode(",",$listrow);
									}
								?>
							],
							"aoColumnDefs": [
										{
											"aTargets"	: [ 0 ],//checked
											"sClass" 	: "td-checkbox",
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
											"aTargets"	: [ 6 ],//cantidad
											"mRender"	: function ( data, type, full ) {
												return '<input type="text" inputmode="decimal" autocomplete="off" class="form-control pv2-num afx-monto txtDetailQuantity txt-numeric " value="'+data+'" name="txtDetailQuantity[]" />';
											}
										}
							]
						});
						refreschChecked();
						afxBindCommon();

						//La cantidad no admite negativos (la mascara anterior tampoco los permitia)
						$(document).on("input",".pv2 input.txt-numeric",function(){
							if(this.value.indexOf("-") !== -1) this.value = this.value.replace(/-/g,"");
						});

						//Buscar el Proveedor
						$(document).on("click","#btnSearchProvider",function(){
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentProvider->componentID; ?>/onCompleteProvider/SELECCIONAR_PROVEEDOR/true/empty/false/not_redirect_when_empty";
							pv2OpenSearch(url_request, "Buscar proveedor", "onCompleteProvider", onCompleteProvider, "width=900,height=450");
						});
						//Eliminar Proveedor
						$(document).on("click","#btnClearProvider",function(){
									$("#txtProviderID").val("");
									$("#txtProviderDescription").val("");
						});

						//Ir a Lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						//Printer
						$(document).on("click","#btnPrinter",function(){
							fnWaitOpen();
							window.location = "<?php echo base_url(); ?>/app_purchase_purchaseorden/viewRegister/companyID/<?php echo $objTM->companyID;?>/transactionID/<?php echo $objTM->transactionID;?>/transactionMasterID/<?php echo $objTM->transactionMasterID;?>";
						});

						//Archivos
						$(document).on("click","#btnClickArchivo",function(){
							window.open("<?php echo base_url()."core_elfinder/index/componentID/".$objComponentOrdenCompra->componentID."/componentItemID/".$objTM->transactionMasterID; ?>","blanck");
						});

						//Eliminar el Documento
						$(document).on("click","#btnDelete",function(){
							fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
								fnWaitOpen();
								$.ajax({
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo base_url(); ?>/app_purchase_purchaseorden/delete",
									data 		: {companyID : <?php echo $objTM->companyID;?>, transactionID : <?php echo $objTM->transactionID;?> , transactionMasterID : <?php echo $objTM->transactionMasterID;?>  },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											window.location = "<?php echo base_url(); ?>/app_purchase_purchaseorden/index";
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
						//Guardar Documento
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-transaction" ).attr("method","POST");
								$( "#form-new-transaction" ).attr("action","<?php echo base_url(); ?>/app_purchase_purchaseorden/save/edit");

								if(validateForm()){
									fnWaitOpen();
									//Las cantidades se envian como numero puro: 1,000.00 -> 1000
									afxLimpiarMontos("#form-new-transaction");
									$( "#form-new-transaction" ).submit();
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

							var url_request 		= "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentItem->componentID; ?>/onCompleteItem/SELECCIONAR_ITEM_TO_PROVIDER/true/"+encodeURI('{\"providerID\"|\"'+$("#txtProviderID").val()+'\"}') + "/false/not_redirect_when_empty";
							pv2OpenSearch(url_request, "Buscar producto", "onCompleteItem", onCompleteItem, "width=900,height=450");

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
						//Cambio en la cantidades: se guarda en la fila real sin redibujar,
						//para no perder el foco al pasar a otra cantidad (teclado del telefono)
						$(document).on("blur",".txtDetailQuantity",function(){
							var objrow_ = $(this).closest("tr")[0];
							var objind_ = objTableDetailTransaction.fnGetPosition(objrow_);
							objTableDetailTransaction.fnUpdate( afxFmtMonto($(this).val()), objind_, 6, false, false );
							refreschChecked();
						})
						//Seleccionar Checke
						$(document).on("click",".classCheckedDetail",function(){
							var objrow_ = $(this).closest("tr")[0];
							var objind_ = objTableDetailTransaction.fnGetPosition(objrow_);
							var objdat_ = objTableDetailTransaction.fnGetData(objind_);
							objTableDetailTransaction.fnUpdate( !objdat_[0], objind_, 0 );
							refreschChecked();
						});
					});

					//Funciones
					////////////////////////////
					////////////////////////////
					//Las filas de DataTables nacen sin data-label: se agregan para mostrarlas como tarjeta en el telefono
					function fnDetailRowCreated(nRow){
						var labels = ["Quitar","","","Codigo","Nombre","U/M","Cantidad"];
						$(nRow).addClass("row_razon").children("td").each(function(index){
							if(labels[index]) $(this).attr("data-label",labels[index]);
						});
					}
					function fnDetailDraw(oSettings){
						$("#pv2DetailCount").text(oSettings.aoData.length);
					}
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
						var lengthRow = objTableDetailTransaction.fnGetData().length;
						if(lengthRow == 0){
							fnShowNotification("Agregar el Detalle del Documento","error",timerNotification);
							result = false;
						}
						return result;
					}
					function onCompleteItem(objResponse){
						console.info("CALL onCompleteItem");
						var objRow 						= {};
						objRow.checked 					= false;
						objRow.itemID 					= objResponse[0][1];
						objRow.transactionMasterDetail 	= 0;
						objRow.itemNumber 				= objResponse[0][2];
						objRow.itemName 				= objResponse[0][3];
						objRow.itemUM 					= objResponse[0][4];
						objRow.quantity 				= 0;


						//Berificar que el Item ya esta agregado
						if(jLinq.from(objTableDetailTransaction.fnGetData()).where(function(obj){ return obj[1] == objRow.itemID;}).select().length > 0 ){
							fnShowNotification("El Item ya esta agregado","error");
							return;
						}

						objTableDetailTransaction.fnAddData([objRow.checked,objRow.itemID,objRow.transactionMasterDetail,objRow.itemNumber,objRow.itemName,objRow.itemUM,objRow.quantity]);
						refreschChecked();

					}
					//Refresh
					function refreschChecked(){
						$("[type='checkbox'], [type='radio'], [type='file']").not('.toggle, .select2, .multiselect').uniform();
						afxInitMontos("#tb_transaction_master_detail");
					}
					function onCompleteProvider(objResponse){
						console.info("CALL onCompleteCustomer");

						$("#txtProviderID").val(objResponse[0][1]);
						$("#txtProviderDescription").val(objResponse[0][2] + " / " + objResponse[0][3]);

					}
				</script>
