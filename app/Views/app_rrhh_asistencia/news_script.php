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
								$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_rrhh_asistencia/save/new");
								
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

					fnActiveSensorRead();					
					fnHuellaLeida();
					
					//Evento que verifica si ya se lello la huella
					function fnHuellaLeida()
					{
						
						if (typeof (EventSource) !== 'undefined') 
						{
							
							var source 			= new EventSource( '<?php echo base_url(); ?>' + '/app_api_fingerprint/web_ssejs');
							source.onmessage 	= function (event) 
							{ 							
								
								const data = JSON.parse(event.data);  
					
								if(data != null)
								{
									if (data.image !== null) {
										
										
										if (data.user_id != null) 
										{
											
											//jQuery("#" + localStorage.getItem("srnPc")).attr("src", "data:image/png;base64," + data.image);
											$("#txtCustomerID").val(data.user_id);
											$("#txtCustomerDescription").val(data.name);	

											$.ajax({									
												cache       : false,
												dataType    : 'json',
												type        : 'POST',
												url  		: "<?php echo base_url(); ?>/app_invoice_api/getLineByCustomer",
												data 		: {entityID : $("#txtCustomerID").val()  },
												success		: fnCompleteGetCustomerCreditLine,
												error:function(xhr,data){	
													console.info("complete data error");													
													fnShowNotification("Error 505","error");
												}
											});													
											
										} 
										else 
										{
											
											console.info("complete data error");													
											fnShowNotification("El usuairo no existe","error");
									
										}
									}
								}
					
							};
						} 
						else 
						{
								console.info("complete data error");													
								fnShowNotification("Error 505","error");
						}
						
					}
					
					//Preparar el lector
					function fnActiveSensorRead()
					{						
						$.ajax({									
							cache       : false,
							dataType    : 'json',
							type        : 'POST',
							url  		: "<?php echo base_url(); ?>/app_api_fingerprint/web_active_sensor_read",
							data 		: { customerID : 0  },
							success		: function()
							{
								
							},
							error:function(xhr,data)
							{	
								console.info("complete data error");													
								fnShowNotification("Error 505","error");
							}
						});			
						
					}
					
					
					
					function onCompleteCustomer(objResponse){
						
						console.info("CALL onCompleteCustomer");
						
						
						
						var entityID = objResponse[0][2];
						$("#txtCustomerID").val(objResponse[0][2]);
						$("#txtCustomerDescription").val(objResponse[0][2] + " " + objResponse[0][4] + " / " + objResponse[0][5]);	
						
						
						//Enviar Formulario
						$( "#form-new-invoice" ).attr("method","POST");
						$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_rrhh_asistencia/save/new");
						
						if(validateForm())
						{
							fnWaitOpen();
							$( "#form-new-invoice" ).submit();
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