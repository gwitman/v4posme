				<!-- ./ page heading -->
				<script>
					var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';
					var objTableDetailTransaction = {};
					$(document).ready(function(){
						//Inicializar Controles
						pv2InitDate("txtTransactionOn", false);
						afxBindCommon();
						fnRefrescarDetalle();

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
							window.location = "<?php echo base_url(); ?>/app_purchase_returnsprovider/viewRegister/companyID/<?php echo $objTM->companyID;?>/transactionID/<?php echo $objTM->transactionID;?>/transactionMasterID/<?php echo $objTM->transactionMasterID;?>";
						});

						//Archivos
						$(document).on("click","#btnClickArchivo",function(){
							window.open("<?php echo base_url()."core_elfinder/index/componentID/".$objComponentDevolucionCompra->componentID."/componentItemID/".$objTM->transactionMasterID; ?>","blanck");
						});

						//Eliminar el Documento
						$(document).on("click","#btnDelete",function(){
							fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
								fnWaitOpen();
								$.ajax({
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo base_url(); ?>/app_purchase_returnsprovider/delete",
									data 		: {companyID : <?php echo $objTM->companyID;?>, transactionID : <?php echo $objTM->transactionID;?> , transactionMasterID : <?php echo $objTM->transactionMasterID;?>  },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											window.location = "<?php echo base_url(); ?>/app_purchase_returnsprovider/index";
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
								$( "#form-new-transaction" ).attr("action","<?php echo base_url(); ?>/app_purchase_returnsprovider/save/edit");

								if(validateForm()){
									fnWaitOpen();
									//Las cantidades se envian como numero puro: 1,000.00 -> 1000 (afecta inventario al aplicar)
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
							$(".txtCheckedIsActive").each(function(i,obj){
									if($(obj).attr("checked") == "checked"){
										$(obj).parents("tr").first().remove();
									}
							});
							fnRefrescarDetalle();
						});

					});

					//Funciones
					////////////////////////////
					////////////////////////////
					//Contador y aviso de detalle vacio
					function fnRefrescarDetalle(){
						var total = $("#body_detail_transaction").find("tr").length;
						$("#pv2DetailCount").text(total);
						$("#pv2DetailEmpty").toggle(total == 0);
						$("#div_transaction_master_detail").toggle(total > 0);
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

						//Cada producto debe llevar una cantidad mayor que 0
						$("input[name='txtDetailQuantity[]']").each(function(e,i){
							if(pv2Num($(i).val()) <= 0) {
								fnShowNotification("La cantidad de cada producto debe ser mayor que 0","error",timerNotification);
								result = false;
							}
						});

						$("select.select2statusID").each(function(e,i){
							if($(i).val() == "") {
								fnShowNotification("Seleccionar la razon de la devolucion","error",timerNotification);
								result = false;
							}
						});

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
						objRow.lote 					= "";
						objRow.vencimiento 				= "";


						//Validar si esta el item
						for(var i = 0 ; i < $(".classDetailItem").length; i++){
								var x  = $(($(".classDetailItem")[i])).val();
								var y  = objRow.itemID;

								if(x == y){
									fnShowNotification("El Item ya esta agregado","error");
									return;
								}

						}


						var tmpl = $($("#tmpl_row_razon").html());
						var id 	 = tmpl.find("#txtDetailTipoID").attr("id") + moment().format("ms")

						tmpl.find("#txtDetailItemID").attr("value",objRow.itemID);
						tmpl.find("#txtDetailTipoID").attr("id",id);
						tmpl.find("#txtCodigo").text(objRow.itemNumber);
						tmpl.find("#txtNombre").text(objRow.itemName);
						tmpl.find("#txtUM").text(objRow.itemUM);

						if($("#"+id).length > 0 )
							return;

						$("#body_detail_transaction").append(tmpl);
						refreschChecked();
						fnRefrescarDetalle();

					}
					//Refresh
					function refreschChecked(){
						$("[type='checkbox'], [type='radio'], [type='file']").not('.toggle, .select2, .multiselect').uniform();

						//En telefono los combos quedan nativos; en PC se conserva select2
						afxInitSelect2(".pv2 select.select2",{placeholder: "seleccionar..."});

						afxInitMontos("#body_detail_transaction");

					}
					function onCompleteProvider(objResponse){
						console.info("CALL onCompleteCustomer");

						$("#txtProviderID").val(objResponse[0][1]);
						$("#txtProviderDescription").val(objResponse[0][2] + " / " + objResponse[0][3]);

					}
				</script>
