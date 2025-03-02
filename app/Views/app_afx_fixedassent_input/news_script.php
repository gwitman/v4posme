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
							$("#form-new-fixedasset_input").attr("method", "POST");
							$("#form-new-fixedasset_input").attr("action", "<?php echo base_url(); ?>/app_afx_fixedassent_input/save/new");

							if (validateForm()) {
								fnWaitOpen();
								$("#form-new-fixedasset_input").submit();
							}
						});

						//Buscar el Empleado
						$(document).on("click", "#btnSearchEmployee", function() {
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentEmployee->componentID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE/true/empty/false/not_redirect_when_empty";
							window.open(url_request, "MsgWindow", "width=900,height=450");
							window.onCompleteEmployee = onCompleteEmployee;
						});
						//Eliminar Empleado
						$(document).on("click", "#btnClearEmployee", function() {
							$("#txtEmployeeEntityID").val("");
							$("#txtEmployeeDescription").val("");
						});

						//Buscar area
						$(document).on("click", "#btnSearchArea", function() {
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentPublicCatalog->componentID; ?>/onCompleteArea/SELECCIONAR_ACTIVOFIJO_AREA/true/empty/false/not_redirect_when_empty";
							window.open(url_request, "MsgWindow", "width=900,height=450");
							window.onCompleteArea = onCompleteArea;
						});
						//Eliminar area
						$(document).on("click", "#btnClearArea", function() {
							$("#txtAreaID").val("");
							$("#txtAreaDescripcion").val("");
						});

						//Buscar proyecto
						$(document).on("click", "#btnSearchProyect", function() {
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentPublicCatalog->componentID; ?>/onCompleteProyect/SELECCIONAR_ACTIVOFIJO_PROYECTO/true/empty/false/not_redirect_when_empty";
							window.open(url_request, "MsgWindow", "width=900,height=450");
							window.onCompleteProyect = onCompleteProyect;
						});
						//Eliminar proyecto
						$(document).on("click", "#btnClearProyect", function() {
							$("#txtProyectID").val("");
							$("#txtProyectDescripcion").val("");
						});

						//Buscar Activo
						$(document).on("click", "#btnSearchFixedAsset", function() {
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentFixedAsset->componentID; ?>/onCompleteFixedAsset/SELECCIONAR_FIXEDASSENT/true/empty/false/not_redirect_when_empty";
							window.open(url_request, "MsgWindow", "width=900,height=450");
							window.onCompleteFixedAsset = onCompleteFixedAsset;
						});
						//Eliminar Activo
						$(document).on("click", "#btnClearFixedAsset", function() {
							$("#txtFixedAssetID").val("");
							$("#txtFixedAssetDescription").val("");
						});

						$(document).on("click", "#btnDeleteDetailTransaction", function() {
							console.info("btnDeleteDetailTransaction");
							$(".txtCheckedIsActive").each(function(i, obj) {
								if ($(obj).attr("checked") == "checked") {
									$(obj).parents("tr").first().remove();
								}
							});

						});

						$(document).on("click", "#btnNewDetailTransaction", function() {
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentFixedAsset->componentID; ?>/onCompleteFixedAsset/SELECCIONAR_FIXEDASSENT/true/empty/false/not_redirect_when_empty";
							window.open(url_request, "MsgWindow", "width=900,height=450");
							window.onCompleteFixedAsset = onCompleteFixedAsset;
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

						if ($("#txtEmployeeEntityID").val() === "") {
							fnShowNotification("Seleccione el Colaborador", "error", timerNotification);
							result = false;
						}

						if ($("#txtAreaID").val() === "") {
							fnShowNotification("Seleccione el Area", "error", timerNotification);
							result = false;
						}

						if ($("#txtProyectID").val() === "") {
							fnShowNotification("Seleccione el Proyecto", "error", timerNotification);
							result = false;
						}

						//Validar detalles de transaccion
						$("#body_tb_transaction_master_detail").each(function(index, obj) {
							var tableLength = $(obj).find("tr").length;
							if (tableLength < 1) {
								fnShowNotification("Seleccione Un Activo a Asignar", "error", timerNotification);
								result = false;
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

					function onCompleteEmployee(objResponse) {
						console.info("CALL onCompleteEmployee");
						$("#txtEmployeeEntityID").val(objResponse[0][2]);
						$("#txtEmployeeDescription").val(objResponse[0][3] + " | " + objResponse[0][4]);
					}

					function onCompleteArea(objResponse) {
						console.info("CALL onCompleteArea");
						$("#txtAreaID").val(objResponse[0][0]);
						$("#txtAreaDescripcion").val(objResponse[0][1] + " | " + objResponse[0][2]);
					}

					function onCompleteProyect(objResponse) {
						console.info("CALL onCompleteProyect");
						$("#txtProyectID").val(objResponse[0][0]);
						$("#txtProyectDescripcion").val(objResponse[0][1] + " | " + objResponse[0][2]);
					}

					function onCompleteFixedAsset(objResponse) {
						console.info("CALL onCompleteFixedAsset");

						var objRow 			= {};
						objRow.checked 		= false;
						objRow.assetID 		= objResponse[0][0];
						objRow.assetCode 	= objResponse[0][1];
						objRow.assetName 	= objResponse[0][2];

						// Validar si ya existe el activo
						var assetExists = false;
						$("#body_tb_transaction_master_detail").find("tr").each(function(index, obj) {
							if ($(obj).find("#txtFixedAssetID").val() == objRow.assetID) {
								fnShowNotification("El Activo Fijo ya existe en el detalle", "error");
								assetExists = true;
								return false;
							}
						});

						if (assetExists) {
							return;
						}

						var tmpl = $($("#tmpl_row_fixedasset").html());
						tmpl.find("#txtFixedAssetID").attr("value", objRow.assetID);
						tmpl.find("#txtFixedAssetCodeDetail").attr("value", objRow.assetCode);
						tmpl.find("#txtFixedAssetNameDetail").attr("value", objRow.assetName);
						tmpl.find("#txtFixedAssetCode").text(objRow.assetCode);
						tmpl.find("#txtFixedAssetName").text(objRow.assetName);
						$("#body_tb_transaction_master_detail").append(tmpl);
					}
				</script>