				<!-- ./ page heading -->
				<script>
					var varParameterCantidadItemPoup	= '<?php echo $objParameterCantidadItemPoup; ?>';
					var varUseMobile					= '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';
				</script>
				<?php echo view('app_cxc_simulation/sim_v2_common'); ?>
				<script>
					//Obtener informacion del cliente
					fnWaitOpen();
					$.ajax({
						cache		: false,
						dataType	: 'json',
						type		: 'POST',
						url			: "<?php echo base_url(); ?>/app_invoice_api/getLineByEntity",
						data		: { entityID: <?php echo $objCustomerDefault->entityID; ?>},
						success		: fnCompleteGetCustomerCreditLine,
						error:function(xhr,data){
							console.info("complete data error");
							fnWaitClose();
							fnShowNotification("Error 505","error");
						}
					});

					$(document).ready(function(){
						//Combos nativos (sin select2 ni uniform)
						simv2NativeSelects();
						setTimeout(simv2NativeSelects, 0);
						simv2ResetResumen();

						//Buscar el Cliente
						$(document).on("click","#btnSearchCustomer",function(){
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteCustomer/SELECCIONAR_ENTIDAD_PAGINATED/true/empty/false/not_redirect_when_empty/1/1/"+varParameterCantidadItemPoup+"/";
							simv2OpenSearch(url_request, "Buscar cliente", "onCompleteCustomer", onCompleteCustomer, "width=900,height=450");
						});

						//Eliminar Cliente
						$(document).on("click","#btnClearCustomer",function(){
							fnClearCreditLine();
						});
						//Cambios
						$(document).on("change","#txtAmount,#txtInterestYear,#txtNumberPay,#txtFrecuencyID,#txtPlanID",function(){
							fnClearData();
						});
						//Enter en los parametros calcula la tabla (no envia el formulario)
						$(document).on("keydown","#form-new-invoice input",function(e){
							if(e.key !== "Enter" && e.keyCode !== 13) return;
							e.preventDefault();
							if(this.id == "txtAmount" || this.id == "txtInterestYear" || this.id == "txtNumberPay")
								$("#btnCalculate").trigger("click");
						});
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(e){
								e.preventDefault();
								$( "#form-new-invoice" ).attr("method","POST");
								$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_cxc_simulation/save/edit");

								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-invoice" ).submit();
								}
						});

						//Calcular amortization
						$(document).on("click","#btnCalculate",function(e){
								e.preventDefault();
								var plantID 		= $("#txtPlanID").val(); 		//tipo de plan (frances,aleman,americano,constante)
								var frecuencyID 	= $("#txtFrecuencyID").val(); 	//frecuencia de pago (semanal,quincenal,mensual,semenstral)
								var plazo			= simv2LeerPlazo();				//normaliza 50,666 => 50.666 y reescribe el input
								var numberPay		= $("#txtNumberPay").val();
								var interestYear	= $("#txtInterestYear").val();
								var amount			= $("#txtAmount").val();
								var dayExcluded		= $("#txtDayExcluded").val();
								fnClearData();

								if(isNaN(plazo) || plazo <= 0){
									fnShowNotification("Ingrese un numero de pagos valido (ej. 12 o 50.666)","error");
									$("#txtNumberPay").focus();
									return;
								}

								fnWaitOpen();
								$.ajax({
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo base_url(); ?>/app_cxc_api/getSimulateAmortization",
									data 		: {plantID :plantID , frecuencyID : frecuencyID,numberPay :numberPay,interestYear : interestYear, amount : amount, dayExcluded : dayExcluded },
									success		: function(data){ fnAmortizationComplete(data, plazo); },
									error:function(xhr,data){
										console.info("complete data error");
										fnWaitClose();
										simv2SetEmpty("No se pudo calcular la tabla de amortización. Intente de nuevo.");
										fnShowNotification("Error 505","error");
									}
								});
						});

					});
					function fnAmortizationComplete(data, plazo){
						console.info("complete success data");
						fnWaitClose();
						console.info(data);

						if(!data || data.error === true){
							var mensaje = data && data.message ? data.message : "No se pudo calcular la tabla de amortización";
							simv2SetEmpty("No se pudo calcular la tabla de amortización.");
							fnShowNotification(mensaje,"error");
							return;
						}
						if(!data.array || !data.array.detail || Object.keys(data.array.detail).length == 0){
							simv2SetEmpty("No se generaron cuotas. Revise el monto, la tasa y el número de pagos.");
							fnShowNotification("No se generaron cuotas, revise el monto y el numero de pagos","warning");
							return;
						}

						if(plazo === undefined) plazo = parseFloat(fnNormalizarDecimal($("#txtNumberPay").val())) || 0;
						var resumen = simv2RenderTable(data.array.detail, plazo);
						simv2SetResumen(resumen);
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

						if($("#txtCustomerCreditLineID").val() == null || $("#txtCustomerCreditLineID").val() == "")
						{
							fnShowNotification("Seleccione una linea de credito","error",timerNotification);
							result = false;
						}

						if($("#txtNumberPay").val() == "")
						{
							fnShowNotification("Ingrese el numero de pagos","error",timerNotification);
							result = false;
						}
						else
						{
							var plazo = simv2LeerPlazo();	//normaliza 50,666 => 50.666 y reescribe el input antes del submit
							if(isNaN(plazo) || plazo <= 0)
							{
								fnShowNotification("Ingrese un numero de pagos valido (ej. 12 o 50.666)","error",timerNotification);
								result = false;
							}
						}

						if($("#txtInterestYear").val() == "")
						{
							fnShowNotification("Ingrese el interes anual","error",timerNotification);
							result = false;
						}

						return result;
					}


					function refreschChecked(){
						//Los combos de esta pantalla son nativos: no se aplica uniform ni select2
						simv2NativeSelects();
					}
					function fnClearData(){
							console.info("fnClearData");
							simv2SetEmpty("Ingrese los parámetros y presione <b>Calcular</b> para ver la tabla de amortización.");
							simv2ResetResumen();
					}

					function fnClearCreditLine()
					{
						//Limpiar datos de la ENTIDAD
						$("#txtCustomerID").val("");
						$("#txtCustomerDescription").val("");

						//Limpiar select de lineas de credito
						$("#txtCustomerCreditLineID").html("");
						$("#txtCustomerCreditLineID").val("");
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
						refreschChecked();
					}
				</script>
