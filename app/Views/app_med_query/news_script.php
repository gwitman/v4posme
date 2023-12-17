				<!-- ./ page heading -->
				<script>
					
					$(document).ready(function(){						 
						 $('#txtDate').datepicker({format:"yyyy-mm-dd"});
						 $('#txtDate').val(moment().format("YYYY-MM-DD"));	
						 $("#txtDate").datepicker("update");
						 
					
						
						 //Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-invoice" ).attr("method","POST");
								$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_med_query/save/new");
								
								if(validateForm())
								{
									fnWaitOpen();
									$( "#form-new-invoice" ).submit();
								}
								
						});
						
						$(document).on("click","#btnClearCustomer",function(){
									$("#txtCustomerID").val("");
									$("#txtCustomerDescription").val("");
						});
						
						
						$(document).on("click","#btnSearchCustomer",function(){
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCustomer->componentID; ?>/onCompleteCustomer/SELECCIONAR_ENTIDAD/true/empty/false/not_redirect_when_empty";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteCustomer = onCompleteCustomer; 
						});		
		
					
						
						
					});				

					
					
					function onCompleteCustomer(objResponse){
						
						console.info("CALL onCompleteCustomer");
					
						var entityID = objResponse[2];
						$("#txtCustomerID").val(objResponse[2]);
						$("#txtCustomerDescription").val(objResponse[2] + " " + objResponse[4] + " / " + objResponse[5]);	
						
						
						
						
						
						
					}
					
					
					
					
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						debugger;
						
						//Validar Fecha						
						if($("#txtDate").val() == ""){
							fnShowNotification("Establecer Fecha al Documento","error",timerNotification);
							result = false;
						}					
					
						if( $("#txtCustomerID").val() == "0" ||  $("#txtCustomerID").val() == "13" ){
							fnShowNotification("Seleccione el cliente","error",timerNotification);
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