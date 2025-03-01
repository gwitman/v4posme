				<!-- ./ page heading -->
				<script>
					$(document).ready(function() {
						//Regresar a la lista
						$(document).on("click", "#btnBack", function() {
							fnWaitOpen();
						});
						//Evento Agregar el Usuario
						$(document).on("click", "#btnAcept", function() {

							if (validateForm()) {
								fnWaitOpen();
								$("#form-new-account-type").attr("method", "POST");
								$("#form-new-account-type").attr("action", "<?php echo base_url(); ?>/app_bank/save/new");
								$("#form-new-account-type").submit();
							}
						});



						/* $('#txtDate').datepicker({
							format: "mm/yyyy",
							changeMonth: true,
							changeYear: true,
							showButtonPanel: false,
						}); */
						/* $('.txt-numeric').mask('000,000.00', {
						    reverse: true
						}); */

					});


					$(document).on("click", "#btnSearchCustomer", function() {
						var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCustomer->componentID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE/true/empty/false/not_redirect_when_empty";
						window.open(url_request, "MsgWindow", "width=900,height=450");
						window.onCompleteEmployee = onCompleteEmployee; 
					});

					//Eliminar cliente
					$(document).on("click", "#btnClearCustomer", function() {
						fnClearCustomer();
					});

					function fnClearCustomer() {
						$("#txtCustomerEntityID").val("");
						$("#txtCustomerDescription").val("");
					}

					function onCompleteEmployee(objResponse) {
						console.info("CALL onCompleteCustomer");
						fnClearCustomer();
						$("#txtCustomerEntityID").val(objResponse[0][2]);
						$("#txtCustomerDescription").val(objResponse[0][3] + " / " + objResponse[0][4]);
					}

					function validateForm() {
						var result = true;
						var timerNotification = 15000;

						//Validar Fecha
						if ($("#txtName").val() == "") {
							fnShowNotification("El nombre es requerido", "error", timerNotification);
							result = false;
						}

						//Validar Monto
						if ($("#txtCurrencyID").val() == "") {
							fnShowNotification("La moneda es requerida", "error", timerNotification);
							result = false;
						}

						if ($("#txtStatusID").val() == "") {
							fnShowNotification("El estado es requerido", "error", timerNotification);
							result = false;
						}

						return result;

					}
				</script>