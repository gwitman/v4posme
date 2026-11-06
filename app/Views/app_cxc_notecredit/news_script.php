				<!-- ./ page heading -->
				<script>
				    var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';

				    $(document).ready(function() {
				        pv2InitDate("txtDate", true);
				        afxBindCommon();

				        //Regresar a la lista
				        $(document).on("click", "#btnBack", function() {
				            fnWaitOpen();
				        });

				        //Evento Agregar el Usuario
				        $(document).on("click", "#btnAcept", function(e) {
				            e.preventDefault();
				            $("#form-new-notecredit").attr("method", "POST");
				            $("#form-new-notecredit").attr("action", "<?php echo base_url(); ?>/app_cxc_notecredit/save/new");

				            if (validateForm()) {
				                fnWaitOpen();
				                //El monto viaja como numero puro, sin comas
				                afxLimpiarMontos("#form-new-notecredit");
				                $("#form-new-notecredit").submit();
				            }

				        });

				        //Buscar el cliente
				        $(document).on("click", "#btnSearchCustomer", function() {
				            var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCustomer->componentID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_ALL/true/empty/false/not_redirect_when_empty";
				            pv2OpenSearch(url_request, "Buscar cliente", "onCompleteCustomer", onCompleteCustomer, "width=900,height=450");
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

				        //Un monto negativo invertiria el efecto de la nota sobre el saldo
				        if (pv2Num($("#txtAmount").val()) < 0) {
				        	fnShowNotification("El monto no puede ser negativo", "error", timerNotification);
				        	result = false;
				        }

				        //Validar Monto (pv2Num detecta 0, 0.00 y vacio)
				        if (pv2Num($("#txtAmount").val()) == 0) {
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
				        //uniform solo sobre casillas, nunca sobre combos
				        $("[type='checkbox'], [type='radio']").not('.toggle, .select2, .multiselect').uniform();
				        afxInitMontos(".pv2");
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
