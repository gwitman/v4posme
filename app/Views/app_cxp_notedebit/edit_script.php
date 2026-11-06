				<!-- ./ page heading -->
				<script>
					var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';

					$(document).ready(function() {
						pv2InitDate("txtDate", false);
						afxBindCommon();

						var objProviderNoteDebit 			= <?php echo json_encode($objComponentNoteDebit); ?>;
						var varParameterCantidadItemPoup	= '<?php echo $objParameterCantidadItemPoup; ?>';

						//Regresar a la lista
						$(document).on("click", "#btnBack", function() {
							fnWaitOpen();
						});

						//Evento Agregar el Usuario
						$(document).on("click", "#btnAcept", function(e) {
							e.preventDefault();
							$("#form-new-notecredit").attr("method", "POST");
							$("#form-new-notecredit").attr("action", "<?php echo base_url(); ?>/app_cxp_notedebit/save/edit");

							if (validateForm()) {
								fnWaitOpen();
								//El monto viaja como numero puro, sin comas
								afxLimpiarMontos("#form-new-notecredit");
								$("#form-new-notecredit").submit();
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
									url: "<?php echo base_url(); ?>/app_cxp_notedebit/delete",
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
											window.location = "<?php echo base_url(); ?>/app_cxp_notedebit/index";
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
							window.open("<?php echo base_url(); ?>" + "/" + "/app_cxp_notedebit/viewPrinterFormatoA4" + "/companyID/<?php echo $objTM->companyID; ?>/transactionID/<?php echo $objTM->transactionID; ?>/transactionMasterID/<?php echo $objTM->transactionMasterID; ?>", '_blank');
							fnWaitClose();
						});

						$(document).on("click","#btnClickArchivo",function(){
							window.open("<?php echo base_url()."/core_elfinder/index/componentID/".$objComponentNoteDebit->componentID."/componentItemID/".$objTM->transactionMasterID; ?>","blanck");
						});

					});


					function validateForm() {
						var result = true;
						var timerNotification = 15000;

						//Validar Fecha
						if ($("#txtDate").val() == "") {
							fnShowNotification("Establecer Fecha al Documento", "error", timerNotification);
							result = false;
						}

						//Validar Monto (pv2Num detecta 0, 0.00 y vacio)
						if (pv2Num($("#txtAmount").val()) == 0) {
							fnShowNotification("El monto no puede ser 0", "error", timerNotification);
							result = false;
						}

						if ($("#txtProviderEntityID").val() === "") {
							fnShowNotification("Seleccione el Proveedor", "error", timerNotification);
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
				</script>
