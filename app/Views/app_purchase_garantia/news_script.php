				<!-- ./ page heading -->
				<script>
					$(document).ready(function(){		
						var varParameterCantidadItemPoup		= '<?php echo $objParameterCantidadItemPoup; ?>';  
				 
						 $('#txtDate').datepicker({format:"yyyy-mm-dd"});
						 $('#txtDate').val(moment().format("YYYY-MM-DD"));	
						 $("#txtDate").datepicker("update");
						 $('.txt-numeric').mask('000,000.00', {reverse: true});
						 
						
						
						
						$(document).on("change","#txtCurrencyID",function(){
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
								$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_purchase_garantia/save/new");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-invoice" ).submit();
								}
								
						});
						
						
						//Buscar el Cliente
						$(document).on("click","#btnSearchCustomer",function(){
							
							
							//Redireccion pantalla
							var url_redirect		= "__app_cxc_customer__add__callback__onCompleteCustomer__comando__pantalla_abierta_desde_taller";			
							url_redirect 			= encodeURIComponent(url_redirect);
							
							
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_BILLING_PAGINATED/true/empty/false/"+url_redirect+"/1/1/"+varParameterCantidadItemPoup+"/";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteCustomer = onCompleteCustomer; 
						});	

						//Eliminar Cliente
						$(document).on("click","#btnClearCustomer",function(){
									$("#txtCustomerID").val("");
									$("#txtCustomerDescription").val("");
						});						
						
						
						//Buscar Colagorador
						$(document).on("click","#btnSearchEmployer",function(){
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE_PAGINATED/true/empty/true/not_redirect_when_empty/1/1/"+varParameterCantidadItemPoup+"/";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteEmployee = onCompleteEmployee; 
						});
						//Eliminar Colaborador
						$(document).on("click","#btnClearEmployer",function(){
									$("#txtEmployerID").val("");
									$("#txtEmployerDescription").val("");
						});
						
						//Buscar Factura
						$(document).on("click","#btnSearchNote",function(){
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteBilling/SELECCIONAR_FACTURA_PAGINATED/true/empty/false/not_redirect_when_empty/1/1/"+varParameterCantidadItemPoup+"/";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteBilling = onCompleteBilling; 
						});
						//Eliminar Factura
						$(document).on("click","#btnClearNote",function(){
									$("#txtNote").val("");
									$("#txtNoteDescription").val("");
						});
						
						
					});
					
					
					function onCompleteBilling(objResponse)
					{							
							
							$("#txtNote").val(objResponse[0][2]);
							$("#txtNoteDescription").val(objResponse[0][2]);
							
					}
					
					function onCompleteEmployee(objResponse)
					{							
							
							$("#txtEmployerID").val(objResponse[0][2]);
							$("#txtEmployerDescription").val(objResponse[0][3] + " / " + objResponse[0][4]);
							
					}
						
					
					function onCompleteCustomer(objResponse)
					{
						if(objResponse != undefined)
						{
							var entityID = objResponse[0][1];
							$("#txtCustomerID").val(objResponse[0][1]);
							$("#txtCustomerDescription").val(objResponse[0][2] + " " + objResponse[0][3] + " / " + objResponse[0][4]);								
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
						
						
						if($("#txtNote").val() == ""){
							fnShowNotification("Seleccionar factura","error",timerNotification);
							result = false;
						}
						
						if($("#txtCustomerID").val() == ""){
							fnShowNotification("Seleccionar cliente","error",timerNotification);
							result = false;
						}
						
						if($("#txtEmployerID").val() == ""){
							fnShowNotification("Seleccionar tecnico","error",timerNotification);
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