				<!-- ./ page heading -->
				<script>
					/**
					 * Llena el combo de ciclos con la respuesta de /app_accounting_api/getCycleNotClosed.
					 * Si no hay ciclos abiertos deja la opcion "Sin ciclos abiertos", muestra el aviso
					 * y deshabilita el boton de la tarjeta; si hay ciclos habilita el boton y
					 * dispara change para que select2 (PC) refresque la seleccion.
					 */
					function fnLlenarCiclos(selectorCiclo, selectorBoton, selectorAviso, cycles){
						var $ciclo = $(selectorCiclo);
						$ciclo.html("");
						if(!cycles || cycles.length == 0){
							$ciclo.append("<option value=''>Sin ciclos abiertos</option>");
							$(selectorAviso).show();
							$(selectorBoton).prop("disabled",true);
						}
						else{
							$.each(cycles,function(i,obj){
								$ciclo.append("<option value='"+obj.componentCycleID+"'>"+obj.startOnFormat+"</option>");
							});
							$(selectorAviso).hide();
							$(selectorBoton).prop("disabled",false);
						}
						$ciclo.trigger("change");
					}

					/**
					 * Limpia el combo de ciclos (periodo vacio o error) y deja el boton habilitado;
					 * la validacion previa al confirmar avisara si falta el ciclo.
					 */
					function fnLimpiarCiclos(selectorCiclo, selectorBoton, selectorAviso){
						$(selectorCiclo).html("<option></option>").trigger("change");
						$(selectorAviso).hide();
						$(selectorBoton).prop("disabled",false);
					}

					/**
					 * Valida que periodo y ciclo tengan valor antes de pedir confirmacion.
					 * Devuelve true si se puede continuar.
					 */
					function fnValidarPeriodoCiclo(selectorPeriodo, selectorCiclo){
						if(!$(selectorPeriodo).val()){
							fnShowNotification("Seleccione el periodo","error");
							return false;
						}
						if(!$(selectorCiclo).val()){
							fnShowNotification("Seleccione el ciclo","error");
							return false;
						}
						return true;
					}

					$(document).ready(function(){
						/*Proceso Mayorizar Ciclo*/
						$(document).on("click","#btnAceptMayorizateCycle",function(){
							if(!fnValidarPeriodoCiclo("#txtMayorizatePeriod","#txtMayorizateCicle")) return;
							fnShowConfirm("Confirmar..","Desea Realizar la Mayorizacion de Este Ciclo Contable",function(){
									fnWaitOpen();
									$.ajax({
										cache       : false,
										dataType    : 'json',
										type        : 'POST',
										url  		: "<?php echo base_url(); ?>/app_accounting_process/mayorizateCycle",
										data 		: { componentPeriodID :$("#txtMayorizatePeriod").val() , componentCycleID :$("#txtMayorizateCicle").val()},
										success:function(data){
											fnWaitClose();
											console.info("complete delete success");
											if(data.error){
												fnShowNotification(data.message,"error");
											}
											else{
												console.info(data);
												if(data.result.code == 0)
													fnShowNotification("success","success");
												else
													fnShowNotification("problemas en el proceso de mayorizacion","error");
											}
										},
										error:function(xhr,data){
											fnWaitClose();
											console.info("complete delete error");
											fnShowNotification("Error 505","error");
										}
									});
							});
						});
						//Al cambiar el periodo se cargan los ciclos abiertos
						$(document).on("change","#txtMayorizatePeriod",function(){
								if(!$("#txtMayorizatePeriod").val()){
									fnLimpiarCiclos("#txtMayorizateCicle","#btnAceptMayorizateCycle","#lblMayorizateSinCiclos");
									return;
								}
								fnWaitOpen();
								$.ajax({
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo base_url(); ?>/app_accounting_api/getCycleNotClosed",
									data 		: { componentPeriodID :$("#txtMayorizatePeriod").val() },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnLimpiarCiclos("#txtMayorizateCicle","#btnAceptMayorizateCycle","#lblMayorizateSinCiclos");
											fnShowNotification(data.message,"error");
										}
										else{
											fnLlenarCiclos("#txtMayorizateCicle","#btnAceptMayorizateCycle","#lblMayorizateSinCiclos",data.cycles);
										}
									},
									error:function(xhr,data){
										fnWaitClose();
										console.info("complete delete error");
										fnShowNotification("Error 505","error");
									}
								});
						});
						/*Proceso Cerrar Ciclo*/
						$(document).on("click","#btnAceptClosedCycle",function(){
							if(!fnValidarPeriodoCiclo("#txtClosedPeriod","#txtClosedCicle")) return;
							fnShowConfirm("Confirmar..","Desea Realizar el Cierre de Este Ciclo Contable",function(){
									fnWaitOpen();
									$.ajax({
										cache       : false,
										dataType    : 'json',
										type        : 'POST',
										url  		: "<?php echo base_url(); ?>/app_accounting_process/closedCycle",
										data 		: { componentPeriodID :$("#txtClosedPeriod").val() , componentCycleID :$("#txtClosedCicle").val()},
										success:function(data){
											fnWaitClose();
											console.info("complete delete success");
											if(data.error){
												fnShowNotification(data.message,"error");
											}
											else{
												console.info(data);
												if(data.result.code == 0)
													fnShowNotification("success","success");
												else
													fnShowNotification("problemas en el proceso de cierre","error");
											}
										},
										error:function(xhr,data){
											fnWaitClose();
											console.info("complete delete error");
											fnShowNotification("Error 505","error");
										}
									});
							});
						});
						//Al cambiar el periodo se cargan los ciclos abiertos
						$(document).on("change","#txtClosedPeriod",function(){
								if(!$("#txtClosedPeriod").val()){
									fnLimpiarCiclos("#txtClosedCicle","#btnAceptClosedCycle","#lblClosedSinCiclos");
									return;
								}
								fnWaitOpen();
								$.ajax({
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo base_url(); ?>/app_accounting_api/getCycleNotClosed",
									data 		: { componentPeriodID :$("#txtClosedPeriod").val() },
									success:function(data){
										fnWaitClose();
										console.info("complete delete success");
										if(data.error){
											fnLimpiarCiclos("#txtClosedCicle","#btnAceptClosedCycle","#lblClosedSinCiclos");
											fnShowNotification(data.message,"error");
										}
										else{
											fnLlenarCiclos("#txtClosedCicle","#btnAceptClosedCycle","#lblClosedSinCiclos",data.cycles);
										}
									},
									error:function(xhr,data){
										fnWaitClose();
										console.info("complete delete error");
										fnShowNotification("Error 505","error");
									}
								});
						});
						/*Proceso Contabilizar Documento*/
						$(document).on("click","#btnAceptContabilizeDocument",function(){
							fnShowConfirm("Confirmar..","Desea Realizar la Contabilidad de esta Transaccion",function(){
									fnWaitOpen();
									$.ajax({
										cache       : false,
										dataType    : 'json',
										type        : 'POST',
										url  		: "<?php echo base_url(); ?>/app_accounting_process/contabilizateDocument",
										data 		: { transactionID :$("#txtListTransactionID").val() },
										success:function(data){
											fnWaitClose();
											console.info("complete delete success");
											if(data.error){
												fnShowNotification(data.message,"error");
											}
											else{
												if(data.result.code == 0)
													fnShowNotification("success","success");
												else
													fnShowNotification("problemas al contabilizar documentos","error");
											}
										},
										error:function(xhr,data){
											fnWaitClose();
											console.info("complete delete error");
											fnShowNotification("Error 505","error");
										}
									});
							});
						});
						/*Proceso Ejecutar Notificaciones*/
						$(document).on("click","#btnAceptNotificacion",function(){
							fnShowConfirm("Confirmar..","Desea ejecutar las notificaciones...",function(){
									fnWaitOpen();
									$.ajax({
										cache       : false,
										dataType    : 'json',
										type        : 'POST',
										url  		: "<?php echo base_url(); ?>/app_accounting_process/execNotification",
										data 		: { notificationName :$("#txtNotificationProcess").val() },
										success:function(data)
										{
											fnWaitClose();
											console.info("complete delete success");
											if(data.error){
												fnShowNotification(data.message,"error");
											}
											else{
												if(data.result.code == 0)
													fnShowNotification("success","success");
												else
													fnShowNotification("problemas al ejecutar las notificaciones","error");
											}
										},
										error:function(xhr,data){
											fnWaitClose();
											console.info("complete delete error");
											//antes mostraba "success" aunque la peticion fallara
											fnShowNotification("Error 505","error");
										}
									});
							});
						});
						/*Proceso Ejecutar Limpiar Notificaciones*/
						$(document).on("click",".clear_notification",function(e,event){
							var tagID_ =	$(this).data("notificationid");
							fnShowConfirm("Confirmar..","Desea ejecutar la limpieza...",function(){
									fnWaitOpen();
									$.ajax({
										cache       : false,
										dataType    : 'json',
										type        : 'POST',
										url  		: "<?php echo base_url(); ?>/app_accounting_process/clearNotification",
										data 		: { tagID : tagID_ },
										success:function(data){
											fnWaitClose();
											console.info("complete clear success");
											if(data.error){
												fnShowNotification(data.message,"error");
											}
											else{
												if(data.result.code == 0)
													fnShowNotification("success","success");
												else
													fnShowNotification("problemas al ejecutar la limpieza","error");
											}
										},
										error:function(xhr,data){
											fnWaitClose();
											console.info("complete delete error");
											fnShowNotification("Error 505","error");
										}
									});
							});
						});

						//Telefono: combos nativos (sin select2). En PC no cambia nada
						PosmeInv.mobileSelects();
					});

					//Al cambiar el periodo se cargan los ciclos abiertos
					$(document).on("change","#txtTipoCambioPeriod",function(){
								if(!$("#txtTipoCambioPeriod").val()){
									fnLimpiarCiclos("#txtTipoCambioCicle","#btnDownloadTipoCambio","#lblTipoCambioSinCiclos");
									return;
								}
								fnWaitOpen();
								$.ajax({
									cache       : true,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo base_url(); ?>/app_accounting_api/getCycleNotClosed",
									data 		: { componentPeriodID :$("#txtTipoCambioPeriod").val() },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnLimpiarCiclos("#txtTipoCambioCicle","#btnDownloadTipoCambio","#lblTipoCambioSinCiclos");
											fnShowNotification(data.message,"error");
										}
										else{
											fnLlenarCiclos("#txtTipoCambioCicle","#btnDownloadTipoCambio","#lblTipoCambioSinCiclos",data.cycles);
										}
									},
									error:function(xhr,data){
										fnWaitClose();
										console.info("complete delete error");
										fnShowNotification("Error 505","error");
									}
								});
					});
					$(document).on("click","#btnDownloadTipoCambio",function(){
							if(!fnValidarPeriodoCiclo("#txtTipoCambioPeriod","#txtTipoCambioCicle")) return;
							fnShowConfirm("Confirmar..","Desea actualizar el Tipo de Cambio",function(){
									fnWaitOpen();
									$.ajax({
										cache       : false,
										dataType    : 'json',
										type        : 'POST',
										url  		: "<?php echo base_url(); ?>/app_accounting_process/downloadTipoCambio",
										data 		: { componentPeriodID :$("#txtTipoCambioPeriod").val() , componentCycleID :$("#txtTipoCambioCicle").val()},
										success:function(data){
											fnWaitClose();
											console.info("complete delete success");
											if(data.error){
												fnShowNotification(data.message,"error");
											}
											else{
												console.info(data);
												if(data.result.code == 0)
													fnShowNotification("success","success");
												else
													fnShowNotification("problemas en el proceso de tipo de cambio","error");
											}
										},
										error:function(xhr,data){
											fnWaitClose();
											console.info("complete delete error");
											fnShowNotification("Error 505","error");
										}
									});
							});
					});
				</script>
