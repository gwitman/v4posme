				<!-- ./ page heading -->
				<script>
					var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';

					$(document).ready(function(){
						var varParameterCantidadItemPoup	= '<?php echo $objParameterCantidadItemPoup; ?>';

						pv2InitDate("txtDate", false);
						var urlPrinter = '<?php echo $objParameterUrlPrinter; ?>';
						<?php echo getBehavio($company->type,"app_cxp_expenses","scriptReady",""); ?>

						afxBindCommon();
						//Si la personalizacion movio los montos a la pestana principal, no se deja la tarjeta vacia
						$("#pv2ColMontos").toggle($("#pv2ColMontos .form-group").length > 0);

						//Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});

                        $(document).on("change","#txtDetailAmount,#txtTransactionMasterTax1",function(){
                            fnCalcularMontoTotal()
                        });

						$(document).on("change","#txtPriorityID",function(){
							var tipoGasto = $(this).val();

							fnWaitOpen();
							$.ajax({
								cache       : false,
								dataType    : 'json',
								type        : 'GET',
								url  		: "<?php echo base_url(); ?>/app_public_catalog_api/getPublicCatalogDetail/companyID/<?php echo $companyID; ?>/publicCatalogDetailID/" + tipoGasto ,

								success		: fnCompletPublicCatalogDetail,
								error:function(xhr,data){
									fnWaitClose();
									console.info("complete data error");
									fnShowNotification("Error 505","error");
								}
							});

						});


						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-invoice" ).attr("method","POST");
								$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_cxp_expenses/save/edit");

								if(validateForm()){
									fnWaitOpen();
									//Los montos se envian como numero puro: 1,000.00 -> 1000
									afxLimpiarMontos("#form-new-invoice");
									$( "#form-new-invoice" ).submit();
								}

						});

						//Buscar el proveedor
						$(document).on("click","#btnSearchProvider",function(){
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteProvider/SELECCIONAR_PROVEEDOR_PAGINATED/true/empty/false/not_redirect_when_empty/1/1/"+varParameterCantidadItemPoup+"/";
							pv2OpenSearch(url_request, "Buscar proveedor", "onCompleteProvider", onCompleteProvider, "width=900,height=450");
						});

						//Eliminar proveedor
						$(document).on("click","#btnClearProvider",function(){
							fnClearExpense();
						});

						//Buscar el amortizacion
						$(document).on("click","#btnSearchAmortization",function()
						{
							var providerID = $("#txtProviderID").val();
							var currencyID	= $("#txtCurrencyID").val();
							if(providerID == "")
							{
								fnShowNotification("Seleccione el proveedor","error");
								return;
							}

							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentAmortization->componentID; ?>/onCompleteAmortization/SELECCIONAR_CUOTA_CXP/true/" + encodeURI('{\"entityID\"|\"' + providerID + '\",\"currencyID\"|\"' + currencyID + '\"}') + "/false/not_redirect_when_empty";
							pv2OpenSearch(url_request, "Buscar factura", "onCompleteAmortization", onCompleteAmortization, "width=900,height=450");
						});

						//Eliminar amortizacion
						$(document).on("click","#btnClearAmortization",function(){
							$("#txtAmortizationID").val("");
							$("#txtAmortizationDescription").val("");
						});

						$(document).on("change","#txtCurrencyID",function(){
							$("#txtAmortizationID").val("");
							$("#txtAmortizationDescription").val("");
						});

						$(document).on("click","#btnPrinter",function(){
									fnWaitOpen();
									window.open("<?php echo base_url(); ?>"+"/"+urlPrinter+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>", '_blank');
									fnWaitClose();
						});




						$(document).on("click","#btnDelete",function(){
							fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
								fnWaitOpen();
								$.ajax({
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo base_url(); ?>/app_cxp_expenses/delete",
									data 		: {companyID : <?php echo $objTransactionMaster->companyID;?>, transactionID : <?php echo $objTransactionMaster->transactionID;?>,transactionMasterID : <?php echo $objTransactionMaster->transactionMasterID; ?>  },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											window.location = "<?php echo base_url(); ?>/app_cxp_expenses/index";
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

						$(document).on("click","#btnClickArchivo",function(){
							window.open("<?php echo $objParameterUrlServerFile."/core_elfinder/index/componentID/".$objComponentShare->componentID."/componentItemID/".$objTransactionMaster->transactionMasterID; ?>","blanck");
						});

					});



					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;

						//Validar Fecha
						if($("#txtDate").val() == ""){
							fnShowNotification("Establecer Fecha al Documento","error",timerNotification);
							result = false;
						}


						//Validar Monto
						if(pv2Num($("#txtDetailAmount").val()) == 0){
							fnShowNotification("El monto no puede ser 0","error",timerNotification);
							result = false;
						}

						if( $("#txtAreaID").val() === null )
						{
							fnShowNotification("Categoria de Gasto es obligatorio","error",timerNotification);
							result = false;
						}


						<?php echo getBahavioDB($company->type, "app_cxp_expenses", "validateJs", ""); ?>

						return result;
					}

					function fnCompletPublicCatalogDetail(data)
					{
						fnWaitClose();
						data = data.objGridView;


						$("#txtAreaID").html("");
						$("#txtAreaID").val("");

						for(var i = 0 ; i < data.length; i++)
						{
							if(i == 0)
								$("#txtAreaID").append("<option value='"+data[i].publicCatalogDetailID+"' selected>"+ data[i].name + "</option>");
							else
								$("#txtAreaID").append("<option value='"+data[i].publicCatalogDetailID+"'>"+ data[i].name + "</option>");

						}

						afxInitSelect2("#txtAreaID");


					}

					function refreschChecked(){
						$("[type='checkbox'], [type='radio'], [type='file']").not('.toggle, .select2, .multiselect').uniform();
					}

                    //Total = Monto + IVA. Se calcula con numeros puros; el campo muestra el formato 1,000.00
                    function fnCalcularMontoTotal(){
                        let monto = pv2Num($('#txtDetailAmount').val());
                        let iva = pv2Num($('#txtTransactionMasterTax1').val());
                        var total = monto+iva;
                        $('#txtTransactionMasterTax2').val(afxFmtMonto(afxRedondear(total)));
                    }

					function fnClearExpense()
					{
						$("#txtProviderID").val("");
						$("#txtProviderDescription").val("");

						$("#txtAmortizationID").val("");
						$("#txtAmortizationDescription").val("");
					}

					function onCompleteProvider(objResponse)
					{
						console.info("CALL onCompleteProvider");
						fnClearExpense();
						$("#txtProviderID").val(objResponse[0][1]);
						$("#txtProviderDescription").val(objResponse[0][2] + " | " + objResponse[0][3]);
					}

					function onCompleteAmortization(objResponse)
					{
						console.info("CALL onCompleteProvider");
						$("#txtCustomerCreditDocumentID").val(objResponse[0][0]);
						$("#txtAmortizationID").val(objResponse[0][1]);
						$("#txtDocumentNumber").val(objResponse[0][2]);
						$("#txtAmortizationDescription").val(objResponse[0][2] + " | Pendiente = " + objResponse[0][5]);
					}
				</script>
