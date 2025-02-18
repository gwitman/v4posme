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

				        //Evento Agregar el Usuario
				        $(document).on("click", "#btnAcept", function() {
				            $("#form-new-notecredit").attr("method", "POST");
				            $("#form-new-notecredit").attr("action", "<?php echo base_url(); ?>/app_cxc_notecredit/save/new");

				            if (validateForm()) {
				                fnWaitOpen();
				                $("#form-new-notecredit").submit();
				            }

				        });

				        //Buscar el cliente
				        $(document).on("click", "#btnSearchCustomer", function() {
				            var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCustomer->componentID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_ALL/true/empty/false/not_redirect_when_empty";
				            window.open(url_request, "MsgWindow", "width=900,height=450");
				            window.onCompleteCustomer = onCompleteCustomer;
				        });

				        //Eliminar cliente
				        $(document).on("click", "#btnClearCustomer", function() {
				            fnClearCustomer();
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

				        //Validar Monto
				        if ($("#txtAmount").val() == "0") {
				            fnShowNotification("El monto no puede ser 0", "error", timerNotification);
				            result = false;
				        }

				        if ($("#txtCustomerEntityID").val() === "") {
				            fnShowNotification("Seleccione el cliente", "error", timerNotification);
				            result = false;
				        }

				        return result;
				    }

				    function refreschChecked() {
				        $("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
				        //$('.txtDebit').mask('000,000.00', {reverse: true});
				        //$('.txtCredit').mask('000,000.00', {reverse: true});
				        $('.txt-numeric').mask('000,000.00', {
				            reverse: true
				        });
				    }

				    function fnClearCustomer() {
				        $("#txtCustomerEntityID").val("");
				        $("#txtCustomerDescription").val("");
				    }

				    function onCompleteCustomer(objResponse) {
				        console.info("CALL onCompleteCustomer");
				        fnClearCustomer();
				        $("#txtCustomerEntityID").val(objResponse[0][1]);
				        $("#txtCustomerDescription").val(objResponse[0][2] + " | " + objResponse[0][3]);
				    }

				</script>