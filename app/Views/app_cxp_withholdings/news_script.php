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
				            $("#form-new-withholding").attr("method", "POST");
				            $("#form-new-withholding").attr("action", "<?php echo base_url(); ?>/app_cxp_withholdings/save/new");

				            if (validateForm()) {
				                fnWaitOpen();
				                $("#form-new-withholding").submit();
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

						//Calcular retencion
						$(document).on("change", "#txtInvoiceAmount, #txtTax", function(){
							onPercentageAndAmountChange();
						});

						
				    });

					function onPercentageAndAmountChange()
					{
						var amount 			= parseFloat($("#txtInvoiceAmount").val()) || 0;
						var percentage 		= $("#txtTax option:selected").data("ratio");
						var result 			= fnCalculatePercentage(amount, percentage);
						$("#txtWithholdingAmount").val(result.toFixed(2));							
					};

				    function validateForm() {
				        var result = true;
				        var timerNotification = 15000;

				        //Validar Fecha
				        if ($("#txtDate").val() == "") {
				            fnShowNotification("Establecer Fecha al Documento", "error", timerNotification);
				            result = false;
				        }

				        if ($("#txtProviderEntityID").val() === "") {
				            fnShowNotification("Seleccione el Proveedor", "error", timerNotification);
				            result = false;
				        }

						//Validar Factura
						if ($("#txtInvoice").val() == "") {
				            fnShowNotification("Establecer el número de Factura", "error", timerNotification);
				            result = false;
				        }

						//Validar Monto de Factura
						if ($("#txtInvoiceAmount").val() == "0" || $("#txtInvoiceAmount").val() == "") {
				            fnShowNotification("Establecer Monto de la Factura", "error", timerNotification);
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

					//Calcular porcengaje
					function fnCalculatePercentage(amount, percentage) {
						return amount * (percentage / 100);
					}

				</script>