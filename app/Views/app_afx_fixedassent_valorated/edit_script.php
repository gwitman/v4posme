				<!-- ./ page heading -->
				<script>
					$(document).ready(function() {

						var objTableDetailTransaction = {};

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

						//Evento Agregar entrada
						$(document).on("click", "#btnAcept", function() {
							$("#form-new-fixedasset_output").attr("method", "POST");
							$("#form-new-fixedasset_output").attr("action", "<?php echo base_url(); ?>/app_afx_fixedassent_valorated/save/edit");

							if (validateForm()) {
								fnWaitOpen();
								$("#form-new-fixedasset_output").submit();
							}
						});

						//Agregar activo
						$(document).on("click", "#btnNewDetailTransaction", function() {
							var currencyID = $("#txtCurrencyID").val();
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentFixedAsset->componentID; ?>/onCompleteFixedAsset/SELECCIONAR_FIXEDASSENT_VALORATED/true/" + encodeURI('{\"currencyID\"|\"' + currencyID + '\"}') + "/false/not_redirect_when_empty";
							window.open(url_request, "MsgWindow", "width=900,height=450");
							window.onCompleteFixedAsset = onCompleteFixedAsset;
						});

						//Eliminar activo
						$(document).on("click", "#btnDeleteDetailTransaction", function() {
							console.info("btnDeleteDetailTransaction");
							$(".txtCheckedIsActive").each(function(i, obj) {
								if ($(obj).attr("checked") == "checked") {
									$(obj).parents("tr").first().remove();
								}
							});
							fnCalculateTotalAmount();
						});

						//Actualizacion del monto total cuando se cambia el ratio
						$(document).on("change", "#txtFixedAssetRatio", function() {
							fnCalculateTotalAmount();
						});

						//Limpiar detalle de transaccion cuando se cambia la moneda
						$(document).on("change", "#txtCurrencyID", function() {
							$("#body_tb_transaction_master_detail").empty();
							$("#txtTotalAmount").val("");
						});

						$(document).on("change", "#txtYearID", function() {
							fnWaitOpen();
							$.ajax({
								cache: false,
								dataType: 'json',
								type: 'POST',
								url: "<?php echo base_url(); ?>/app_accounting_api/getCycleNotClosed",
								data: {
									componentPeriodID: $("#txtYearID").val()
								},
								success: function(data) {
									fnWaitClose();
									console.info("complete delete success");
									if (data.error) {
										fnShowNotification(data.message, "error");
									} else {
										$("#txtMonthID").html("").trigger("change");
										$.each(data.cycles, function(i, obj) {
											$("#txtMonthID").append("<option value='" + obj.componentCycleID + "'>" + obj.startOnFormat + "</option>")
										});
									}
								},
								error: function(xhr, data) {
									fnWaitClose();
									console.info("complete delete error");
									fnShowNotification("Error 505", "error");
								}
							});
						});

						$(document).on("click", "#btnDelete", function() {
							fnShowConfirm("Confirmar..", "Desea eliminar este Registro...", function() {
								fnWaitOpen();
								$.ajax({
									cache: false,
									dataType: 'json',
									type: 'POST',
									url: "<?php echo base_url(); ?>/app_afx_fixedassent_valorated/delete",
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
											window.location = "<?php echo base_url(); ?>/app_afx_fixedassent_valorated/index";
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
						
						$(document).on("click","#btnClickArchivo",function(){
							window.open("<?php echo base_url()."/core_elfinder/index/componentID/".$objComponentFixedAssetValorated->componentID."/componentItemID/".$objTM->transactionMasterID; ?>","blanck");
						});

						$(document).on("click", "#btnPrinter", function() {
							fnWaitOpen();
							window.open("<?php echo base_url(); ?>" + "/" + "/app_afx_fixedassent_valorated/viewPrinterFormatoA4" + "/companyID/<?= $objTM->companyID; ?>/transactionID/<?= $objTM->transactionID; ?>/transactionMasterID/<?= $objTM->transactionMasterID; ?>", '_blank');
							fnWaitClose();
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

						if ($("#txtYearID").val() === "") {
							fnShowNotification("Seleccione el Año", "error", timerNotification);
							result = false;
						}

						console.log($("#txtMonthID").val());
						if ($("#txtMonthID").val() === "") {
							fnShowNotification("Seleccione el Mes", "error", timerNotification);
							result = false;
						}

						//Validar detalles de transaccion
						$("#body_tb_transaction_master_detail").each(function(index, obj) {
							var tableLength = $(obj).find("tr").length;
							if (tableLength < 1) {
								fnShowNotification("Seleccione Un Activo a Valorar", "error", timerNotification);
								result = false;
							}
						});

						//Validar que el ratio no sea cero
						$("#body_tb_transaction_master_detail").find("tr").each(function(index, obj) {
							var ratio 			= parseFloat($(obj).find("#txtFixedAssetRatio").val()) 			|| 0;

							if (ratio == "" || ratio == 0) {	
								fnShowNotification("El Ratio no puede ser cero o vacio", "error", timerNotification);
								result = false;
								return false;
							}
						});

						return result;
					}

					function refreschChecked() {
						$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
						$('.txt-numeric').mask('000,000.00', {
							reverse: true
						});
					}

					function onCompleteFixedAsset(objResponse) {
						console.info("CALL onCompleteFixedAsset");
						var objRow 				= {};
						objRow.checked 			= false;
						objRow.assetID 			= objResponse[0][0];
						objRow.assetCode 		= objResponse[0][1];
						objRow.assetName 		= objResponse[0][2];
						objRow.ratio 			= objResponse[0][3];
						objRow.currentAmount	= objResponse[0][4];
						objRow.settlement 		= objResponse[0][5];

						// Validar si ya existe el activo
						var assetExists = false;
						$("#body_tb_transaction_master_detail").find("tr").each(function(index, obj) {
							var currentFixedAssetID = $(obj).find("#txtCurrentFixedAssetID").attr("data-assetID");
							var newFixedAssetID 	= $(obj).find("#txtNewFixedAssetID").val();

							if (currentFixedAssetID == objRow.assetID || newFixedAssetID == objRow.assetID) {
								fnShowNotification("El Activo Fijo ya existe en el detalle", "error");
								assetExists = true;
								return false;
							}
						});

						if (assetExists) {
							return;
						}

						var tmpl = $($("#tmpl_row_fixedasset").html());
						tmpl.find("#txtFixedAssetCode").text(objRow.assetCode);
						tmpl.find("#txtFixedAssetName").text(objRow.assetName);
						
						tmpl.find("#txtNewFixedAssetID").attr("value", objRow.assetID);
						tmpl.find("#txtFixedAssetRatio").attr("value", objRow.ratio);
						tmpl.find("#txtFixedSettlementAmount").attr("value", objRow.settlement);
						tmpl.find("#txtFixedAssetCurrentAmount").attr("value", objRow.currentAmount);

						$("#body_tb_transaction_master_detail").append(tmpl);
						
						fnCalculateTotalAmount();
					}

					function fnCalculateTotalAmount() {
						let totalAmount = 0;

						$("#body_tb_transaction_master_detail tr").each(function() {
							let ratio = parseFloat($(this).find("#txtFixedAssetRatio").val().trim()) || 0;
							totalAmount += ratio;
						});

						$("#txtTotalAmount").val(totalAmount);
					}
				</script>