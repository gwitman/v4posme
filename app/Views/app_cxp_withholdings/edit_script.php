				<!-- ./ page heading -->
				<script>
					$(document).ready(function() {
						$('#txtDate').datepicker({
							format: "yyyy-mm-dd"
						});
						$('#txtDate').val();
						$("#txtDate").datepicker("update");
						$('.txt-numeric').mask('000,000.00', {
							reverse: true
						});

						var objComponentWithholding 	= <?= json_encode($objComponentWithholding); ?>;
						var objListCatalogTaxPercentage = <?= json_encode($objPublicCatalogTaxPercentage); ?>;
						var taxPercentageMap			= {};
						
						//Cargar el map con los datos del objeto
						objListCatalogTaxPercentage.forEach(function(taxItem) {
							taxPercentageMap[taxItem.catalogItemID] = taxItem.ratio;
						});

						//Regresar a la lista
						$(document).on("click", "#btnBack", function() {
							fnWaitOpen();
						});

						//Evento Agregar el Usuario
						$(document).on("click", "#btnAcept", function() {
							$("#form-new-withholding").attr("method", "POST");
							$("#form-new-withholding").attr("action", "<?php echo base_url(); ?>/app_cxp_withholdings/save/edit");

							if (validateForm()) {
								fnWaitOpen();
								$("#form-new-withholding").submit();
							}

						});

						//Buscar el cliente
						$(document).on("click", "#btnSearchProvider", function() {
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentProvider->componentID; ?>/onCompleteProvider/SELECCIONAR_PROVEEDOR/true/empty/false/not_redirect_when_empty";
							window.open(url_request, "MsgWindow", "width=900,height=450");
							window.onCompleteProvider = onCompleteProvider;
						});

						//Eliminar cliente
						$(document).on("click", "#btnClearProvider", function() {
							fnClearProvider();
						});


						$(document).on("click", "#btnDelete", function() {
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

						$(document).on("click", "#btnPrinter", function() {
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
						var amount 			= parseFloat($("#txtInvoiceAmount").val()) || 0;
						var percentageID 	= $("#txtTax").val();
						var percentage 		= taxPercentageMap[percentageID] || 0;
						var result 			= fnCalculatePercentage(amount, percentage);
						$("#txtWithholdingAmount").val(result.toFixed(2));
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

						//Validar Monto de Factura
						if ($("#txtInvoiceAmount").val() == "0" || $("#txtInvoiceAmount").val() == "") {
							fnShowNotification("Establecer Monto de la Factura", "error", timerNotification);
							result = false;
						}

						return result;
					}

					function refreschChecked() {
						$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
						$('.txt-numeric').mask('000,000.00', {
							reverse: true
						});
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