				<!-- ./ page heading -->
				<script>
				    $(document).ready(function() {
				        // $('#txtDate').datepicker({
				        //     format: "yyyy-mm-dd"
				        // });
				        // $('#txtDate').val();
				        // $("#txtDate").datepicker("update");
				        // $('.txt-numeric').mask('000,000.00', {
				        //     reverse: true
				        // });

				        //Regresar a la lista
				        $(document).on("click", "#btnBack", function() {
				            fnWaitOpen();
				        });

				        //Evento Agregar el Usuario
				        $(document).on("click", "#btnAcept", function() {
				            $("#form-new-bankCheque").attr("method", "POST");
				            $("#form-new-bankCheque").attr("action", "<?php echo base_url(); ?>/app_bank_cheque/save/new");

				            if (validateForm()) {
				                fnWaitOpen();
				                $("#form-new-bankCheque").submit();
				            }
				        });

				        //Buscar el banco
				        $(document).on("click", "#btnSearchBank", function() {
							var currencyID 		= $("#txtCurrencyID").val();
				            var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentBank->componentID; ?>/onCompleteBank/SELECCIONAR_BANCO/true/" + encodeURI('{\"currencyID\"|\"' + currencyID + '\"}') + "/false/not_redirect_when_empty";
				            window.open(url_request, "MsgWindow", "width=900,height=450");
				            window.onCompleteBank = onCompleteBank;
				        });

				        //Eliminar banco
				        $(document).on("click", "#btnClearBank", function() {
				            fnClearBank();
				        });

						$(document).on("change","#txtCurrencyID", function(){
				            fnClearBank();
						});

						//Buscar el Empleado
				        $(document).on("click", "#btnSearchEmployee", function() {
				            var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentEmployee->componentID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE/true/empty/false/not_redirect_when_empty";
				            window.open(url_request, "MsgWindow", "width=900,height=450");
				            window.onCompleteEmployee = onCompleteEmployee;
				        });

				        //Eliminar Empleado
				        $(document).on("click", "#btnClearEmployee", function() {
				            fnClearEmployee();
				        });
				    });



				    function validateForm() {
				        var result = true;
				        var timerNotification = 15000;

						var initialValue		= $("#txtInitialValue").val().trim();
						var finalValue			= $("#txtFinalValue").val().trim();
						debugger;
						if ($("#txtName").val() == "") {
				            fnShowNotification("Ingrese el Nombre", "error", timerNotification);
				            result = false;
				        }

				        if (isNaN(initialValue) || initialValue === "0" || initialValue == "") {
				            fnShowNotification("El valor Inicial es invalido", "error", timerNotification);
				            result = false;
				        }

						if (isNaN(finalValue) || finalValue === "0" || finalValue == "") {
				            fnShowNotification("El valor Final es Invalido", "error", timerNotification);
				            result = false;
				        }

						if ($("#txtSerie").val() == "") {
				            fnShowNotification("Ingrese la Serie", "error", timerNotification);
				            result = false;
				        }

				        if ($("#txtBankID").val() === "") {
				            fnShowNotification("Seleccione el Banco", "error", timerNotification);
				            result = false;
				        }

						if ($("#txtEmployeeEntityID").val() === "") {
				            fnShowNotification("Seleccione el Empleado", "error", timerNotification);
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

					function fnClearEmployee() {
				        $("#txtEmployeeEntityID").val("");
				        $("#txtEmployeeDescription").val("");
				    }

				    function onCompleteBank(objResponse) {
				        console.info("CALL onCompleteBank");
				        fnClearBank();
				        $("#txtBankID").val(objResponse[0][0]);
				        $("#txtBankDescription").val(objResponse[0][1]);
				    }

				    function onCompleteEmployee(objResponse) {
				        console.info("CALL onCompleteEmployee");
				        fnClearEmployee();
				        $("#txtEmployeeEntityID").val(objResponse[0][2]);
				        $("#txtEmployeeDescription").val(objResponse[0][3] + " | " + objResponse[0][4]);
				    }

				</script>