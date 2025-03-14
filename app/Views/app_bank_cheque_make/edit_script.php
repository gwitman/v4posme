				<!-- ./ page heading -->
				<script>
				    $(document).ready(function() {

						var objTM			= JSON.parse('<?= json_encode($objTM)?>');

						$('#txtDate').datepicker({
				            format: "yyyy-mm-dd"
				        });
				        $('#txtDate').val();
				        $("#txtDate").datepicker("update");
				        $('.txt-numeric').mask('000,000.00', {
				            reverse: true
				        });

				        //Regresar a la lista
				        $(document).on("click", "#btnBack", function() {
				            fnWaitOpen();
				        });

				        $(document).on("click", "#btnAcept", function() {
				            $("#form-new-emision").attr("method", "POST");
				            $("#form-new-emision").attr("action", "<?php echo base_url(); ?>/app_bank_cheque_make/save/edit");

							if (validateForm()) {
				                fnWaitOpen();
				                $("#form-new-emision").submit();
				            }

				        });

				        //Buscar la chequera
				        $(document).on("click", "#btnSearcCheckbook", function() {
							var currencyID 	= $("#txtCurrencyID").val(); 
				            var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentBankCheck->componentID; ?>/onCompleteCheckbook/SELECCIONAR_CHEQUERA/true/"+ encodeURI('{\"currencyID\"|\"' + currencyID + '\"}') +"/false/not_redirect_when_empty";
				            window.open(url_request, "MsgWindow", "width=900,height=450");
				            window.onCompleteCheckbook = onCompleteCheckbook;
				        });

				        //Eliminar chequera
				        $(document).on("click", "#btnCleaCheckbook", function() {
				            fnClearCheckbook();
				        });

						$(document).on("click", "#btnDelete", function() {
							fnShowConfirm("Confirmar..", "Desea eliminar este Registro...", function() {
								fnWaitOpen();
								$.ajax({
									cache: false,
									dataType: 'json',
									type: 'POST',
									url: "<?= base_url(); ?>/app_bank_cheque_make/delete",
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
											window.location = "<?= base_url(); ?>/app_bank_cheque_make/index";
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
						
						$(document).on("change","#txtCurrencyID",function(){
				            fnClearCheckbook();
						});

						$(document).on("click", "#btnPrinter", function() {
							fnWaitOpen();
							window.open("<?= base_url(); ?>" + "/" + "/app_bank_cheque_make/viewPrinterFormatoA4" + "/companyID/<?php echo $objTM->companyID; ?>/transactionID/<?php echo $objTM->transactionID; ?>/transactionMasterID/<?php echo $objTM->transactionMasterID; ?>", '_blank');
							fnWaitClose();
						});

						$(document).on("click","#btnClickArchivo",function(){
							window.open("<?= base_url()."/core_elfinder/index/componentID/".$objComponentCheck->componentID."/componentItemID/".$objTM->transactionMasterID; ?>","blanck");
						});

						//No mostrar el valor del contador de la chequera a menos que el documento este aplicado
						if(objTM.statusID == 171 /*REGISTRADO*/)
						{
							$("#txtCheckbookCounterDescription").val("");
						}

				    });



				    function validateForm() {
				        var result = true;
				        var timerNotification = 15000;

						var date 			= new Date($("#txtDate").val());
						var valueFinal		= $("#txtCheckbookCounterLimit").val();
						var valueCurrent 	= $("#txtCheckbookCounter").val();

						if (isNaN(date.valueOf())) {
							fnShowNotification("Ingrese la Fecha", "error", timerNotification);
							result = false;
						}

				        if ($("#txtCheckbookID").val() === "") {
				            fnShowNotification("Seleccione la chequera", "error", timerNotification);
				            result = false;
				        }

						if($("#txtReceiver").val() === "")
						{
							fnShowNotification("Ingrese el beneficiario", "error", timerNotification);
				            result = false;
						}

						if($("#txtAmount").val() === "")
						{
							fnShowNotification("Ingrese el Monto", "error", timerNotification);
				            result = false;
						}

						if((valueCurrent == valueFinal) && $("#txtCheckbookID").val() != "")
						{
							fnShowNotification("Se ha alcanzdo el Limite de la Chequera", "error", timerNotification); //Mejorar este mensaje de error!!!!
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

				    function fnClearCheckbook() {
				        $("#txtCheckbookID").val("");
				        $("#txtCheckbookDescription").val("");
						$("#txtCheckbookCounter").val("");
						$("#txtCheckbookCounterDescription").val("");
				    }

					function onCompleteCheckbook(objResponse) {
				        console.info("CALL onCompleteBank");
				        fnClearCheckbook();
				        $("#txtCheckbookID").val(objResponse[0][0]);
				        $("#txtCheckbookDescription").val(objResponse[0][1] + " | " + objResponse[0][2]);
						
						var valueFinal		= objResponse[0][4] || 0;
						var valueCurrent	= objResponse[0][5] || 0;
						var checkboxSerie	= objResponse[0][6];

						$("#txtCheckbookCounterLimit").val(valueFinal);
						$("#txtCheckbookCounter").val(valueCurrent);
						// $("#txtCheckbookCounterDescription").val(checkboxSerie + valueCurrent);
					}

				</script>