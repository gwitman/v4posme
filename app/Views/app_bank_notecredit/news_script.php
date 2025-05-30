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

				        //Regresar a la lista
				        $(document).on("click", "#btnBack", function() {
				            fnWaitOpen();
				        });

				        $(document).on("click", "#btnAcept", function() {
				            $("#form-new-notecredit").attr("method", "POST");
				            $("#form-new-notecredit").attr("action", "<?php echo base_url(); ?>/app_bank_notecredit/save/new");

				            if (validateForm()) {
				                fnWaitOpen();
				                $("#form-new-notecredit").submit();
				            }

				        });

				        //Buscar el banco
				        $(document).on("click", "#btnSearchBank", function() {
							var currencyID 	= $("#txtCurrencyID").val(); 
				            var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentBank->componentID; ?>/onCompleteBank/SELECCIONAR_BANCO/true/"+ encodeURI('{\"currencyID\"|\"' + currencyID + '\"}') +"/false/not_redirect_when_empty";
				            window.open(url_request, "MsgWindow", "width=900,height=450");
				            window.onCompleteBank = onCompleteBank;
				        });

				        //Eliminar banco
				        $(document).on("click", "#btnClearBank", function() {
				            fnClearBank();
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
				        if ($("#txtAmount").val() == "0") {
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