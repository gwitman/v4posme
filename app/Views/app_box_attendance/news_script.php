				<!-- ./ page heading -->
				<script>
					
					$(document).ready(function(){						 
						 $('#txtDate').datepicker({format:"yyyy-mm-dd"});
						 $('#txtDate').val(moment().format("YYYY-MM-DD"));	
						 $("#txtDate").datepicker("update");
						 
						 
						 //$('#txtDetailReference2').datepicker({format:"yyyy-mm-dd"});
						 //$('#txtDetailReference2').val(moment().format("YYYY-MM-DD"));	
						 //$("#txtDetailReference2").datepicker("update");
						 //
						 //
						 //$('#txtDetailReference3').datepicker({format:"yyyy-mm-dd"});
						 //$('#txtDetailReference3').val(moment().format("YYYY-MM-DD"));	
						 //$("#txtDetailReference3").datepicker("update");
						
						 
						
						
						 //Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-invoice" ).attr("method","POST");
								$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_box_attendance/save/new");
								
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
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCustomer->componentID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_BILLING/true/empty/false/not_redirect_when_empty";
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
											console.info("cliente encontrado por el lector: " + data.user_id + " nombre: " + data.name);
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
								if(xhr.statusText != "OK")
								{
									console.info("complete data error");
									fnShowNotification("Error 505","error");
								}								
								
							}
						});			
						
					}
					
					
					
					function onCompleteCustomer(objResponse){
						
						console.info("CALL onCompleteCustomer");
					
						var entityID = objResponse[0][1];
						$("#txtCustomerID").val(objResponse[0][1]);
						$("#txtCustomerDescription").val(objResponse[0][2] + " " + objResponse[0][3] + " / " + objResponse[0][4]);	
						
						
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
					
					
					
					function fnCompleteGetCustomerCreditLine (data)
					{
						
						var timerNotification 	= 15000;
						console.info("complete success data credit line");		
						console.info(data);		
						var customer 	= data.objCustomer;
						data 			= data.objCustomerCreditAmoritizationAll;
						
						if(customer == null)
						{
							 $("#txtCustomerID").val("0");
							 $("#txtCustomerDescription").val("Clinte no encontrado");
							 fnShowNotification("Cliente no encontrado","error",timerNotification);
							 return;
						}
						
						if(data == null)
						{
							 $("#txtCustomerID").val("0");
							 $("#txtCustomerDescription").val(customer.customerNumber + " Clinte no tiene membresia");
							 fnShowNotification("Cliente sin membresia","error",timerNotification);
							 return;
						}
						
						if(data.length == 0)
						{
							 $("#txtCustomerID").val("0");
							 $("#txtCustomerDescription").val(customer.customerNumber + " Clinte no tiene membresia");
							 fnShowNotification("Cliente sin membresia","error",timerNotification);
							 return;
						}
						
						
						//Cantidad de Cuotas en mora
						var CantidadMora 	= jLinq.from(data).where(function(a){ return a.stageDocumento  != "CANCELADO"   }).select();
						CantidadMora 		= jLinq.from(CantidadMora).where(function(a){ return parseFloat(a.Mora ) > 0 }).select();
						if(CantidadMora.length == 0)
							CantidadMora = 0
						else 
							CantidadMora = jLinq.from(jLinq.from(CantidadMora).select(function(a){ return parseFloat(a.Mora); })).max();
						
						
						if(CantidadMora > 0 )
						{							
							fnShowNotification("Cliente con Mora","error",timerNotification);
							$("#txtDetailReference1").val("NO");	
							$("#txtDetailReference4").val(0);
						}
						
						if(CantidadMora <= 0)
						{							
							$("#txtDetailReference1").val("SI");
						}
						
						////Fecha de Vencimiento
						var FechaVencimiento 		= jLinq.from(data).where(function(a){ return a.stageDocumento  != "CANCELADO" }).select();
						var FechaVencimientoMora	= jLinq.from(jLinq.from(FechaVencimiento).select(function(a){ return parseFloat(a.Mora); })).min();
						var FechaVencimiento 		= jLinq.from(FechaVencimiento).where(function(a){ return a.stageDocumento  != "CANCELADO" && a.Mora == FechaVencimientoMora  }).select();						
						var FechaVencimiento		= FechaVencimiento[0];
						var FechaVencimiento		= FechaVencimiento.dateApply;
						$("#txtDetailReference3").val(FechaVencimiento);
						
						
						//Fecha del proximo pago						
						var FechaProximoPago 		= jLinq.from(data).where(function(a){ return a.stageDocumento  != "CANCELADO" }).select();						
						var FechaProximoPagoMora	= jLinq.from(jLinq.from(FechaProximoPago).select(function(a){ return parseFloat(a.Mora); })).max();
						var FechaProximoPago 		= jLinq.from(FechaProximoPago).where(function(a){ return a.stageDocumento  != "CANCELADO" && a.Mora == FechaProximoPagoMora  }).select();						
						var FechaProximoPago		= FechaProximoPago[0];
						var FechaProximoPago		= FechaProximoPago.dateApply;
						
						
						$("#txtDetailReference2").val( FechaProximoPago );
						
						
						//Dias del Proximo Pago
						if(FechaProximoPagoMora > 0 )
							$("#txtDetailReference4").val(( 0 ));						
						else 
							$("#txtDetailReference4").val(( FechaProximoPagoMora * -1 ));		


						//Enviar Formulario
						$( "#form-new-invoice" ).attr("method","POST");
						$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_box_attendance/save/new");
						
						
						if( '<?php echo $objParameterATTENDANCE_AUTO_PRINTER; ?>' == 'true')
						{
							if(validateForm())
							{
								fnWaitOpen();
								$( "#form-new-invoice" ).submit();
							}
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