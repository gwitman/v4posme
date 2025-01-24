				<!-- ./ page heading -->
				<script>
					var objDetail 	= {};
					//$('.txt-numeric').mask('000,000.00', {reverse: true});
										
					
					$(document).ready(function(){	
						objDetail = $("#tb_transaction_master_detail").dataTable({
							"bPaginate"		: false,
							"bFilter"		: false,
							"bSort"			: false,
							"bInfo"			: false,
							"bAutoWidth"	: false							
						});
						
						
						//Buscar el Cliente
						$(document).on("click","#btnSearchCustomer",function(){
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objEntity->componentID; ?>/onCompleteCustomer/SELECCIONAR_ENTIDAD/true/empty/false/not_redirect_when_empty";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteCustomer = onCompleteCustomer; 
						});						
					
						//Eliminar Cliente
						$(document).on("click","#btnClearCustomer",function(){
							fnClearCreditLine();
						});
						//Cambios
						$(document).on("change","#txtAmount,#txtInterestYear,#txtNumberPay,#txtFrecuencyID,#txtPlanID",function(){
							fnClearData();
						});						
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-invoice" ).attr("method","POST");
								$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_cxc_simulation/save/edit");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-invoice" ).submit();
								}
						});		
						
						//Calcular amortization
						$(document).on("click","#btnCalculate",function(){
								var plantID 		= $("#txtPlanID").val(); 		//tipo de plan (frances,aleman,americano,constante)
								var frecuencyID 	= $("#txtFrecuencyID").val(); 	//frecuencia de pago (semanal,quincenal,mensual,semenstral)
								var numberPay		= $("#txtNumberPay").val();
								var interestYear	= $("#txtInterestYear").val();
								var amount			= $("#txtAmount").val();
								var dayExcluded		= $("#txtDayExcluded").val();
								fnClearData();
								
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo base_url(); ?>/app_cxc_api/getSimulateAmortization",
									data 		: {plantID :plantID , frecuencyID : frecuencyID,numberPay :numberPay,interestYear : interestYear, amount : amount, dayExcluded : dayExcluded },
									success		: fnAmortizationComplete,
									error:function(xhr,data){	
										console.info("complete data error");									
										fnWaitClose();
										fnShowNotification("Error 505","error");
									}
								});
					
					
					
						});
						
					});
					function fnAmortizationComplete(data){
						console.info("complete success data");
						fnWaitClose();
						console.info(data);	
						var total_capital 	= 0;
						var total_interest 	= 0;
						
						$.each(data.array.detail,function(i,obj){
								
								var objRow = obj;
								objDetail.fnAddData([
									objRow.pnum,
									objRow.date,
									objRow.saldoInicial,
									objRow.interes,
									objRow.principal,
									objRow.cuota,
									objRow.saldo
								]);
								total_capital	= total_capital + parseFloat(objRow.principal);
								total_interest	= total_interest + parseFloat(objRow.interes);
						});
						
						
						$("#txtCapitalTotal").val(fnFormatNumber(total_capital,2));
						$("#txtInterestTotal").val(fnFormatNumber(total_interest,2));
						$("#txtTotal").val(fnFormatNumber(total_interest + total_capital,2));
					}
					
					function onCompleteCustomer(objResponse){
						fnClearCreditLine();
						console.info("CALL onCompleteCustomer");
						var entityID = objResponse[0][2];
						$("#txtCustomerID").val(objResponse[0][2]);
						$("#txtCustomerDescription").val(objResponse[0][4] + " | " + objResponse[0][3] + " / " + objResponse[0][5]);
						
					
						fnClearData();
						//Obtener Informacion de Credito
						fnWaitOpen();
						$.ajax({									
							cache       : false,
							dataType    : 'json',
							type        : 'POST',
							url  		: "<?php echo base_url(); ?>/app_invoice_api/getLineByEntity",
							data 		: {entityID : entityID  },
							success		: fnCompleteGetCustomerCreditLine,
							error:function(xhr,data){	
								console.info("complete data error");									
								fnWaitClose();
								fnShowNotification("Error 505","error");
							}
						});
						
						
					}
					
					
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						//Validar Cliente
						if($("#txtCustomerID").val() == ""){
							fnShowNotification("Seleccionar el Cliente","error",timerNotification);
							result = false;
						}
						return result;
					}
					
					
					function refreschChecked(){
						$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
						//$('.txtDebit').mask('000,000.00', {reverse: true});
						//$('.txtCredit').mask('000,000.00', {reverse: true});
					}
					function fnClearData(){
							console.info("fnClearData");
							objDetail.fnClearTable();
					}

					function fnClearCreditLine()
					{
						//Limpiar datos de la ENTIDAD
						$("#txtCustomerID").val("");
						$("#txtCustomerDescription").val("");

						//Limpiar select de lineas de credito
						$("#txtCustomerCreditLineID").html("");
						$("#txtCustomerCreditLineID").val("");
						$("#txtCustomerCreditLineID").select2();
					}
					
					function fnCompleteGetCustomerCreditLine (data)
					{
						console.info("complete success data");
						fnWaitClose();
						tmpInfoClient = data;
						console.info(tmpInfoClient);
						
						//Renderizar Line Credit
						$("#txtCustomerCreditLineID").html("");
						$("#txtCustomerCreditLineID").val("");
						if(tmpInfoClient.objListCustomerCreditLine != null)
						for(var i = 0; i< tmpInfoClient.objListCustomerCreditLine.length;i++){
							if(i==0){
								$("#txtCustomerCreditLineID").append("<option value='"+tmpInfoClient.objListCustomerCreditLine[i].customerCreditLineID+"' selected>"+ tmpInfoClient.objListCustomerCreditLine[i].accountNumber + " / "+ tmpInfoClient.objListCustomerCreditLine[i].line +"</option>");
								$("#txtCustomerCreditLineID").val(tmpInfoClient.objListCustomerCreditLine[i].customerCreditLineID);
							}
							else
								$("#txtCustomerCreditLineID").append("<option  value='"+tmpInfoClient.objListCustomerCreditLine[i].customerCreditLineID+"'>"+ tmpInfoClient.objListCustomerCreditLine[i].accountNumber + " / "+ tmpInfoClient.objListCustomerCreditLine[i].line +"</option>");
						}
						
						//Refresh Control
						$("#txtCustomerCreditLineID").select2();
						refreschChecked();
					}
				</script>