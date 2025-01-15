				<!-- ./ page heading -->
				<script>
					$(document).ready(function(){						 
						 
						
						
						 //Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-invoice" ).attr("method","POST");
								$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_planilla_employee_pay/save/new");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-invoice" ).submit();
								}
								
						});
						//Nueva factura
						$(document).on("click","#btnNewShare",function(){
							var url_request 			= "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentEmployee->componentID; ?>/onCompleteNewShare/SELECCIONAR_EMPLOYEE_TO_PLANILLA/true/empty/false/not_redirect_when_empty";
							window.open(url_request,"MsgWindow","width=900,height=500");
							window.onCompleteNewShare 	= onCompleteNewShare; 
						});
						//Eliminar factura
						$(document).on("click","#btnDeleteShare",function(){
							console.info("btnDeleteShare");
							$(".txtCheckedIsActive").each(function(i,obj){
									if($(obj).attr("checked") == "checked"){
										$(obj).parents("tr").first().remove();
									}
							});	
							
						});
						$(document).on("change","input.txtDetailShare",function(){
							updateSummary();
						});
					});
					
					function updateSummary(){
						console.info("updateSummary");
						var total = 0;
						
						$("#body_tb_transaction_master_detail tr").each(function(index,obj){
							/*+salario quincenal*/
							/*+comision quincenal*/
							/*+bono quincenal*/
							/*-adelanto*/
							/*-deducciones por prestamo*/
							/*-deduccion por llegadas tarde*/
							/*-impuesto sobre la renta*/
							/*-ahorro*/
							/*neto*/
							var salario 				= fnFormatFloat($(obj).find("#txtSalario").val());
							var comision 				= fnFormatFloat($(obj).find("#txtComision").val());
							var bono 					= fnFormatFloat($(obj).find("#txtBonoQuincenal").val());
							var adelanto 				= fnFormatFloat($(obj).find("#txtAdelantos").val());
							var deduccionPrestamo		= fnFormatFloat($(obj).find("#txtDeduccionesPrestamo").val());
							var deduccionLlegadaTarde	= fnFormatFloat($(obj).find("#txtDeduccionesLlegadaTarde").val());
							var ahorro 					= fnFormatFloat($(obj).find("#txtAhorro").val());
							var inss 					= salario * 0.07;
							var inssPatronal 			= salario * 0.215;
							var IR						= fnCalculateIR((salario - inss)*2)/2;
							var neto 					= salario + comision + bono - adelanto - deduccionPrestamo - deduccionLlegadaTarde - inss - IR - ahorro;
							
							$(obj).find("#txtNeto").val(fnFormatNumber(neto,2));
							total 			= total + neto;

							$(obj).find("#txtINSS").val(fnFormatNumber(inss,2));

							$(obj).find("#txtINSSPatronal").val(fnFormatNumber(inssPatronal,2));

							$(obj).find("#txtIR").val(fnFormatNumber(IR,2));
						});
						
						$("#txtTotal").val(fnFormatNumber(total,2));
					}
					function onCompleteNewShare(objResponse){
						console.info("CALL onCompleteNewShare");	
						var objRow 						= {};
						objRow.checked 					= false;
						objRow.txtCalendarDetailID 		= 0;
						objRow.txtEmployeeID			= objResponse[0][2];	/*companyID*/
						objRow.name 					= objResponse[0][3] + "/" + objResponse[0][4];	/*entityID*/
						objRow.salary 					= fnFormatNumber((parseFloat(objResponse[0][7])),2);	
						salarioQuincenal 				= fnFormatNumber((parseFloat(objResponse[0][7])/2),2);	
						objRow.vacation 				= fnFormatNumber((parseFloat(objResponse[0][8]) + 1.25),2);		
						objRow.saving					= objResponse[0][9];

						//Validar si esta el item
						for(var i = 0 ; i < $(".classDetailItem").length; i++){
								var x  = $(($(".classDetailItem")[i])).val(); 
								var y  = objRow.txtEmployeeID;
								
								if(x == y){
									fnShowNotification("El Colaborador ya existe en el detalle","error");
									return;
								}
								
						}
						
						
						var tmpl = $($("#tmpl_row_document").html());
						tmpl.find("#txtCalendarDetailID").attr("value",objRow.txtCalendarDetailID);
						tmpl.find("#txtEmployeeID").attr("value",objRow.txtEmployeeID);
						tmpl.find("#txtDocument").text(objRow.name);
						tmpl.find("#txtVacationBalanceDay").attr("value", objRow.vacation);
						tmpl.find("#txtSalario").attr("value", salarioQuincenal);
						tmpl.find("#txtAhorro").attr("value", objRow.saving);
						$("#body_tb_transaction_master_detail").append(tmpl);
						refreschChecked();						
						updateSummary();
					}
					function fnCompleteGetDocumentInfo(data){
						console.info("fnCompleteGetDocumentInfo");
						console.info(data);
						fnWaitClose();
						
						var row = $(".classDetailItem[value="+data.customerCreditDocumentID+"]").parent().parent();
						
						
						row.find("#txtDetailTransactionDetailCancel").attr("value",data.cancel);
						row.find("#txtDetailTransactionDetailDay").attr("value",data.diario);
						row.find("#txtDetailTransactionDetailShare").attr("value",data.cuota);
						row.find("#txtOfCancel").text(data.cancel);
						row.find("#txtOfDay").text(data.diario);
						row.find("#txtOfShare").text(data.cuota);
					}
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						//Validar Nombre
						if($("#txtNombre").val() == ""){
							fnShowNotification("Ingrese el Nombre","error",timerNotification);
							result = false;
						}
						return result;
					}
					
					function refreschChecked(){
						$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();						
					}
					
					function fnCalculateIR(salaryAfterINSS)
					{
						$anualSalary = (salaryAfterINSS * 12) || 0;

						$overExcess 			= 0;
						$applicablePercentage 	= 0;
						$baseTax 				= 0;

						$withOutOverExcess			= 0;
						$withAplicablePercentage 	= 0;
						$withBaseTax				= 0;
						$monthlyIR					= 0;

						/* 
																	TARIFA PROGRESIVA
							---------------------------------------------------------------------------------------------------
							ESTRATOS DE RENTA			|	IMPUESTO BASE	|	PORCENTAJE APLICABLE	|	SOBRE EXCESO DE C$
							DE C$	HASTA C$			|		C$			|			%				|			C$
							---------------------------------------------------------------------------------------------------
							0.01 HASTA 100,000.00		|		0.00		|			0.0%			|			0.00
							---------------------------------------------------------------------------------------------------
							100,000.1  HASTA 200,000.00	|		0.00		|			15.0%			|		100,000.00
							---------------------------------------------------------------------------------------------------
							200,000.1  HASTA 350,000.00	|		15,000.00	|			20.0%			|		200,000.00
							---------------------------------------------------------------------------------------------------
							350,000.1  HASTA 500,000.00	|		45,000.00	|			25.0%			|		350,000.00
							---------------------------------------------------------------------------------------------------
							500,000.1 A MAS				|		82,500.00	|			30.0%			|		500,000.00
							---------------------------------------------------------------------------------------------------
						*/

						if($anualSalary >= 0 && $anualSalary <= 100000)
						{
							$overExcess 			= 0;
							$applicablePercentage 	= 0;
							$baseTax 				= 0;

							$withOutOverExcess = ($anualSalary - $overExcess) || 0;
							$withAplicablePercentage = ($withOutOverExcess * $applicablePercentage) || 0;
							$withBaseTax = ($withAplicablePercentage + $baseTax) || 0;
							$monthlyIR = ($withBaseTax / 12) || 0;
						}
						else if($anualSalary > 100000 && $anualSalary <= 200000)
						{
							$overExcess 			= 100000;
							$applicablePercentage 	= 0.15;
							$baseTax 				= 0;

							$withOutOverExcess = ($anualSalary - $overExcess) || 0;
							$withAplicablePercentage = ($withOutOverExcess * $applicablePercentage) || 0;
							$withBaseTax = ($withAplicablePercentage + $baseTax) || 0;
							$monthlyIR = ($withBaseTax / 12) || 0;
						}
						else if($anualSalary > 200000 && $anualSalary <= 350000)
						{
							$overExcess 			= 200000;
							$applicablePercentage 	= 0.20;
							$baseTax 				= 15000;

							$withOutOverExcess = ($anualSalary - $overExcess) || 0;
							$withAplicablePercentage = ($withOutOverExcess * $applicablePercentage) || 0;
							$withBaseTax = ($withAplicablePercentage + $baseTax) || 0;
							$monthlyIR = ($withBaseTax / 12) || 0;
						}
						else if($anualSalary > 350000 && $anualSalary <= 500000)
						{
							$overExcess 			= 350000;
							$applicablePercentage 	= 0.25;
							$baseTax 				= 45000;

							$withOutOverExcess = ($anualSalary - $overExcess) || 0;
							$withAplicablePercentage = ($withOutOverExcess * $applicablePercentage) || 0;
							$withBaseTax = ($withAplicablePercentage + $baseTax) || 0;
							$monthlyIR = ($withBaseTax / 12) || 0;
						}
						else if($anualSalary > 500000)
						{
							$overExcess 			= 500000;
							$applicablePercentage 	= 0.30;
							$baseTax 				= 82500;

							$withOutOverExcess = ($anualSalary - $overExcess) || 0;
							$withAplicablePercentage = ($withOutOverExcess * $applicablePercentage) || 0;
							$withBaseTax = ($withAplicablePercentage + $baseTax) || 0;
							$monthlyIR = ($withBaseTax / 12) || 0;
						}

						return $monthlyIR;
					}
				</script>