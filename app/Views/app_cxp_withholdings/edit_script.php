				<!-- ./ page heading -->
				<script>
					var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';

					$(document).ready(function() {
						pv2InitDate("txtDate", false);
						afxBindCommon();

						var objComponentWithholding 		= <?= json_encode($objComponentWithholding); ?>;
						var varParameterCantidadItemPoup	= '<?php echo $objParameterCantidadItemPoup; ?>';

						//Regresar a la lista
						$(document).on("click", "#btnBack", function() {
							fnWaitOpen();
						});

						//Evento Agregar el Usuario
						$(document).on("click", "#btnAcept", function(e) {
							e.preventDefault();
							$("#form-new-withholding").attr("method", "POST");
							$("#form-new-withholding").attr("action", "<?php echo base_url(); ?>/app_cxp_withholdings/save/edit");

							if (validateForm()) {
								fnWaitOpen();
								//Monto de factura y de retencion viajan como numero puro, sin comas
								afxLimpiarMontos("#form-new-withholding");
								$("#form-new-withholding").submit();
							}

						});

						//Buscar el cliente
						$(document).on("click", "#btnSearchProvider", function() {
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteProvider/SELECCIONAR_PROVEEDOR_PAGINATED/true/empty/false/not_redirect_when_empty/1/1/" + varParameterCantidadItemPoup + "/";
							pv2OpenSearch(url_request, "Buscar proveedor", "onCompleteProvider", onCompleteProvider, "width=900,height=450");
						});

						//Eliminar cliente
						$(document).on("click", "#btnClearProvider", function() {
							fnClearProvider();
						});


						$(document).on("click", "#btnDelete", function(e) {
							e.preventDefault();
							fnShowConfirm("Confirmar..", "Desea eliminar este Registro...", function() {
								fnWaitOpen();
								$.ajax({
									cache: false,
									dataType: 'json',
									type: 'POST',
									url: "<?php echo base_url(); ?>/app_cxp_withholdings/delete",
									data: {
										companyID: <?php echo $objTM->companyID; ?>,
										transactionID: <?php echo $objTM->transactionID; ?>,
										transactionMasterID: <?php echo $objTM->transactionMasterID; ?>
									},
									success: function(data) {
										console.info("complete delete success");
										fnWaitClose();
										if (data.error) {
											fnShowNotification(data.message, "error");
										} else {
											window.location = "<?php echo base_url(); ?>/app_cxp_withholdings/index";
										}
									},
									error: function(xhr, data) {
										console.info("complete delete error");
										fnWaitClose();
										fnShowNotification("Error 505", "error");
									}
								});
							});
						});

						$(document).on("click", "#btnPrinter", function(e) {
							e.preventDefault();
							fnWaitOpen();
							window.open("<?php echo base_url(); ?>" + "/" + "/app_cxp_withholdings/viewPrinterFormatoA4" + "/companyID/<?php echo $objTM->companyID; ?>/transactionID/<?php echo $objTM->transactionID; ?>/transactionMasterID/<?php echo $objTM->transactionMasterID; ?>", '_blank');
							fnWaitClose();
						});

						$(document).on("click", "#btnClickArchivo", function() {
							window.open("<?php echo base_url() . "/core_elfinder/index/componentID/" . $objComponentWithholding->componentID . "/componentItemID/" . $objTM->transactionMasterID; ?>", "blanck");
						});

						//Calcular porcentaje
						$(document).on("change", "#txtInvoiceAmount, #txtTax", function() {
							onPercentageAndAmountChange();
						});



					});

					function onPercentageAndAmountChange() {
						//Se calcula con numeros puros: el monto de factura puede traer comas
						var amount 			= pv2Num($("#txtInvoiceAmount").val());
						var percentage 		= pv2Num($("#txtTax option:selected").data("ratio"));
						var result 			= fnCalculatePercentage(amount, percentage);
						$("#txtWithholdingAmount").val(afxFmtMonto(result.toFixed(2)));
					}

					function validateForm() {
						var result = true;
						var timerNotification = 15000;

						//Validar Fecha
						if ($("#txtDate").val() == "") {
							fnShowNotification("Establecer Fecha al Documento", "error", timerNotification);
							result = false;
						}

						if ($("#txtProviderEntityID").val() === "") {
							fnShowNotification("Seleccione el Proveedor", "error", timerNotification);
							result = false;
						}

						//Validar Factura
						if ($("#txtInvoice").val() == "") {
							fnShowNotification("Establecer el número de Factura", "error", timerNotification);
							result = false;
						}

						//Validar Monto de Factura (pv2Num detecta 0, 0.00 y vacio)
						if (pv2Num($("#txtInvoiceAmount").val()) == 0) {
							fnShowNotification("Establecer Monto de la Factura", "error", timerNotification);
							result = false;
						}

						return result;
					}

					function refreschChecked() {
						//uniform solo sobre casillas, nunca sobre combos
						$("[type='checkbox'], [type='radio']").not('.toggle, .select2, .multiselect').uniform();
						afxInitMontos(".pv2");
					}

					function fnClearProvider() {
						$("#txtProviderEntityID").val("");
						$("#txtProviderDescription").val("");
					}

					function onCompleteProvider(objResponse) {
						console.info("CALL onCompleteProvider");
						fnClearProvider();
						$("#txtProviderEntityID").val(objResponse[0][1]);
						$("#txtProviderDescription").val(objResponse[0][2] + " | " + objResponse[0][3]);
					}

					//Calcular porcentaje
					function fnCalculatePercentage(amount, percentage) {
						return amount * (percentage / 100);
					}
				</script>
