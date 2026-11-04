				<!-- ./ page heading -->
				<script>
					var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';
				</script>
				<?php echo view('core_template/posme_v2_common'); ?>
				<script>
					$(document).ready(function() {
						//Fecha y combos nativos en telefono; barra inferior (una sola vez: enlazarla dos veces guardaria dos veces)
						pv2InitDate("txtDate", false);
						pv2MobileSelects("#txtStatusID,#txtCurrencyID");
						pv2BindMobileBar();
						$('.txt-numeric').mask('000,000.00', {
							reverse: true
						});

						//Monto: al salir del campo se muestra como 1,000.00 (sin mascara: la inversa convertiria 500 en 5.00)
						$(document).on("change", "#txtAmount", function() {
							$(this).val(pv2Fmt($(this).val()));
						});

						//Regresar a la lista
						$(document).on("click", "#btnBack", function() {
							fnWaitOpen();
						});

						$(document).on("click", "#btnAcept", function() {
							$("#form-new-notedebit").attr("method", "POST");
							$("#form-new-notedebit").attr("action", "<?php echo base_url(); ?>/app_bank_notedebit/save/edit");

							if (validateForm()) {
								//El formato es solo visual: al servidor viaja el numero puro. Si la validacion falla no se toca el campo
								$("#txtAmount").val(pv2Plain($("#txtAmount").val()));
								fnWaitOpen();
								$("#form-new-notedebit").submit();
							}

						});

						//Buscar el banco
				        $(document).on("click", "#btnSearchBank", function() {
							var currencyID 	= $("#txtCurrencyID").val(); 
				            var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentBank->componentID; ?>/onCompleteBank/SELECCIONAR_BANCO/true/"+ encodeURI('{\"currencyID\"|\"' + currencyID + '\"}') +"/false/not_redirect_when_empty";
				            pv2OpenSearch(url_request, "Buscar banco", "onCompleteBank", onCompleteBank, "width=900,height=450");
				        });

						//Eliminar banco
				        $(document).on("click", "#btnClearBank", function() {
				            fnClearBank();
				        });


						$(document).on("click", "#btnDelete", function() {
							fnShowConfirm("Confirmar..", "Desea eliminar este Registro...", function() {
								fnWaitOpen();
								$.ajax({
									cache: false,
									dataType: 'json',
									type: 'POST',
									url: "<?= base_url(); ?>/app_bank_notedebit/delete",
									data: {
										companyID: <?= $objTM->companyID; ?>,
										transactionID: <?= $objTM->transactionID; ?>,
										transactionMasterID: <?= $objTM->transactionMasterID; ?>
									},
									success: function(data) {
										console.info("complete delete success");
										fnWaitClose();
										if (data.error) {
											fnShowNotification(data.message, "error");
										} else {
											window.location = "<?= base_url(); ?>/app_bank_notedebit/index";
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
							window.open("<?= base_url(); ?>" + "/" + "/app_bank_notedebit/viewPrinterFormatoA4" + "/companyID/<?php echo $objTM->companyID; ?>/transactionID/<?php echo $objTM->transactionID; ?>/transactionMasterID/<?php echo $objTM->transactionMasterID; ?>", '_blank');
							fnWaitClose();
						});

						$(document).on("click","#btnClickArchivo",function(){
							window.open("<?= base_url()."/core_elfinder/index/componentID/".$objComponentNoteDebit->componentID."/componentItemID/".$objTM->transactionMasterID; ?>","blanck");
						});

						$(document).on("change","#txtCurrencyID",function(){
				            fnClearBank();
						});

					});


					function validateForm() {
				        var result = true;
				        var timerNotification = 15000;

						var date = new Date($("#txtDate").val());

						if (isNaN(date.valueOf())) {
							fnShowNotification("Ingrese la Fecha", "error", timerNotification);
							result = false;
						}

				        //Validar Monto
				        if (pv2Num($("#txtAmount").val()) == 0) {
				            fnShowNotification("El monto no puede ser 0", "error", timerNotification);
				            result = false;
				        }

				        if ($("#txtBankID").val() === "") {
				            fnShowNotification("Seleccione el Banco", "error", timerNotification);
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

					function fnClearBank() {
				        $("#txtBankID").val("");
				        $("#txtBankDescription").val("");
				    }

				    function onCompleteBank(objResponse) {
				        console.info("CALL onCompleteBank");
				        fnClearBank();
				        $("#txtBankID").val(objResponse[0][0]);
				        $("#txtBankDescription").val(objResponse[0][1]);
				    }

				</script>