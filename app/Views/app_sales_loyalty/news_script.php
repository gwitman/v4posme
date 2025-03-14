				<!-- ./ page heading -->
				<script>
				    $(document).ready(function() {
						var valueTemp = 0;
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
				            $("#form-new-notecredit").attr("action", "<?php echo base_url(); ?>/app_sales_loyalty/save/new");

				            if (validateForm()) {
				                fnWaitOpen();
				                $("#form-new-notecredit").submit();
				            }

				        });

						$(document).on("focus","#txtTax1",function(){
							valueTemp = $(this).val();
						});
						$(document).on("change","#txtTax1",function(){
							debugger;
							var puntos 	= $(this).val();
							var meta 	= $("#txtAmount").val();
							
							if(puntos > meta)
							{
								$(this).val(valueTemp);
							}
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
				            fnShowNotification("La meta no puede estar vacia", "error", timerNotification);
				            result = false;
				        }

				        if ($("#txtNumberPhone").val() === "") {
				            fnShowNotification("Escribir numero de telefono", "error", timerNotification);
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


				</script>