				<!-- ./ page heading -->
				<script>
					$(document).ready(function(){						 
						 $('#txtDate').datepicker({format:"yyyy-mm-dd"});
						 $('#txtDate').val(moment().format("YYYY-MM-DD"));	
						 $("#txtDate").datepicker("update");
						 $('.txt-numeric').mask('000,000.00', {reverse: true});
						 updatePantalla();
						
						$(document).on("change",".denomination-quantity",function(){
							updateAmount();
						});
						
						$(document).on("change","#txtCurrencyID",function(){
							updatePantalla();
							updateAmount();
						});
						$(document).on("change","#txtAreaID",function(){
							updatePantalla();
							updateAmount();
						});
						
						 //Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-invoice" ).attr("method","POST");
								$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_box_outcash/save/new");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-invoice" ).submit();
								}
								
						});
						
					});
					
					function updateAmount()
					{
						var total 		= $("#txtDetailAmount").val();
						var totalTmp 	= 0;
					
						if($("#txtCurrencyID").val()  == "1")
						{							
							$( ".currency-1  .denomination-quantity" ).each(function() {
							  var i = parseFloat($( this ).val());
							  var p = parseFloat($( this ).data("reference"));
							  totalTmp = totalTmp + (i * p );
							});							
						}
						else
						{							
							$( ".currency-2  .denomination-quantity" ).each(function() {
							  var i = parseFloat($( this ).val());
							  var p = parseFloat($( this ).data("reference"));
							  totalTmp = totalTmp + (i * p );
							});
							
						}
						
						
						$("#txtDetailAmount").val(totalTmp);
					}
					
					function updatePantalla()
					{
					
						
						if($("#txtCurrencyID").val()  == "1")
						{
							$(".currency-1").removeClass("hidden");
							$(".currency-2").addClass("hidden");
							
							$( ".currency-2  .denomination-quantity" ).each(function() {
							  $( this ).val("0")
							});
							
						}
						else
						{
							$(".currency-2").removeClass("hidden");
							$(".currency-1").addClass("hidden");
							$(".currency-1  .denomination-quantity" ).each(function() {
							  $( this ).val("0")
							});
							
							
							
						}
						
						
						if( $("#txtAreaID option:selected").text()  == "Cierre" )
						{							
							$("#txtDetailAmount").attr("readonly","true");
						}
						else
						{							
							$("#txtDetailAmount").removeAttr("readonly");
							$(".currency-1").addClass("hidden");
							$(".currency-2").addClass("hidden");
						}
						
						
					}
					
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						//Validar Fecha
						if($("#txtDate").val() == ""){
							fnShowNotification("Establecer Fecha al Documento","error",timerNotification);
							result = false;
						}
						
						//Validar Monto
						if($("#txtDetailAmount").val() == "0"){
							fnShowNotification("El monto no puede ser 0","error",timerNotification);
							result = false;
						}
						
						
					
						
						return result;
					}
					
					function refreschChecked(){
						$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
						//$('.txtDebit').mask('000,000.00', {reverse: true});
						//$('.txtCredit').mask('000,000.00', {reverse: true});
						$('.txt-numeric').mask('000,000.00', {reverse: true});
					}
					
				</script>