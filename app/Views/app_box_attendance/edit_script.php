				<!-- ./ page heading -->
				<script>		
					
					$(document).ready(function(){					
						 $('#txtDate').datepicker({format:"yyyy-mm-dd"});						 
						 $("#txtDate").datepicker("update");
						 
						 
						 //$('#txtDetailReference2').datepicker({format:"yyyy-mm-dd"});						 
						 //$("#txtDetailReference2").datepicker("update");
						 //
						 //
						 //$('#txtDetailReference3').datepicker({format:"yyyy-mm-dd"});						 
						 //$("#txtDetailReference3").datepicker("update");
						 
						 
					
						//Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						
						
						
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-invoice" ).attr("method","POST");
								$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_box_attendance/save/edit");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-invoice" ).submit();
								}
								
						});
						
						$(document).on("click","#btnPrinter",function(){
									fnWaitOpen();
									window.open("<?php echo base_url(); ?>"+"/app_box_attendance/viewRegisterFormatoPaginaTicket80mm"+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>", '_blank');
									fnWaitClose();																	
						});
						
						
						$(document).on("click","#btnDelete",function(){							
							fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo base_url(); ?>/app_box_attendance/delete",
									data 		: {companyID : <?php echo $objTransactionMaster->companyID;?>, transactionID : <?php echo $objTransactionMaster->transactionID;?>,transactionMasterID : <?php echo $objTransactionMaster->transactionMasterID; ?>  },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											window.location = "<?php echo base_url(); ?>/app_box_attendance/index";
										}
									},
									error:function(xhr,data){	
										console.info("complete delete error");									
										fnWaitClose();
										fnShowNotification("Error 505","error");
									}
								});
							});
						});
						
						$(document).on("click","#btnClickArchivo",function(){
							window.open("<?php echo base_url()."/core_elfinder/index/componentID/".$objComponentShare->componentID."/componentItemID/".$objTransactionMaster->transactionMasterID; ?>","blanck");
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
						
						console.info("complete success data credit line");														
						data = data.objCustomerCreditAmoritizationAll;
						
						if(data == null)
						{
							 $("#txtCustomerID").val("0");
							 $("#txtCustomerDescription").val("Error");
							 fnShowNotification("Cliente sin membresia","error",timerNotification);
							 return;
						}
						
						if(data.length == 0)
						{
							 $("#txtCustomerID").val("0");
							 $("#txtCustomerDescription").val("Error");
							 fnShowNotification("Cliente sin membresia","error",timerNotification);
							 return;
						}
						
						
						//Cantidad de Cuotas en mora
						var CantidadMora = jLinq.from(jLinq.from(data).where(function(a){ 
							return parseFloat(a.Mora) > 0 
						}).select(function(a){ 
							return parseFloat(a.Mora) 
						})).sum().result;
						CantidadMora = parseFloat(CantidadMora);
						
						if(CantidadMora > 0 )
						{							
							fnShowNotification("Cliente con Mora","error",timerNotification);
							$("#txtDetailReference1").val("NO");					
						}
						
						if(CantidadMora <= 0)
						{							
							$("#txtDetailReference1").val("SI");
						}
						
						//Fecha del proximo pago						
						var FechaProximoPago = jLinq.from(jLinq.from(data).select(function(a){ 
							return parseFloat(a.Mora) 
						})).max();
						
						
						var FechaProximoPago = jLinq.from(data).where(function(a){
							return parseFloat(a.Mora) == FechaProximoPago
						}).select();						
						FechaProximoPago = FechaProximoPago[0].dateApply;						
						$("#txtDetailReference2").val(FechaProximoPago);
						
						
						//Fecha de Vencimiento
						var FechaProximoPago = jLinq.from(jLinq.from(data).select(function(a){ 
							return parseFloat(a.Mora) 
						})).min();
						
						
						var FechaProximoPago = jLinq.from(data).where(function(a){
							return parseFloat(a.Mora) == FechaProximoPago
						}).select();						
						FechaProximoPago = FechaProximoPago[0].dateApply;						
						$("#txtDetailReference3").val(FechaProximoPago);
						
					}
					
					
					
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						//Validar Fecha						
						if($("#txtDate").val() == ""){
							fnShowNotification("Establecer Fecha al Documento","error",timerNotification);
							result = false;
						}					
					
						if(  $("#txtCustomerID").val() == "0" ||  $("#txtCustomerID").val() == "13"  ){
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