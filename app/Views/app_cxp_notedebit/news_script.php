				<!-- ./ page heading -->
				<script>
				    $(document).ready(function() {
						var varParameterCantidadItemPoup	= '<?php echo $objParameterCantidadItemPoup; ?>';  

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
				            $("#form-new-notedebit").attr("method", "POST");
				            $("#form-new-notedebit").attr("action", "<?php echo base_url(); ?>/app_cxp_notedebit/save/new");

				            if (validateForm()) {
				                fnWaitOpen();
				                $("#form-new-notedebit").submit();
				            }

				        });

				        //Buscar el cliente
				        $(document).on("click", "#btnSearchProvider", function() {
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteProvider/SELECCIONAR_PROVEEDOR_PAGINATED/true/empty/false/not_redirect_when_empty/1/1/" + varParameterCantidadItemPoup + "/";
				            window.open(url_request, "MsgWindow", "width=900,height=450");
				            window.onCompleteProvider = onCompleteProvider;
				        });

				        //Eliminar cliente
				        $(document).on("click", "#btnClearProvider", function() {
				            fnClearProvider();
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

				        if ($("#txtProviderEntityID").val() === "") {
				            fnShowNotification("Seleccione el Proveedor", "error", timerNotification);
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