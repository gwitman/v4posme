				<!-- ./ page heading -->
				<script>		
					
					$(document).ready(function(){					
						 $('#txtDate').datepicker({format:"yyyy-mm-dd"});						 
						 $("#txtDate").datepicker("update");
					
						 $('#txtNextVisit').datepicker({format:"yyyy-mm-dd"});						 
						 $("#txtNextVisit").datepicker("update");

						 fnCalcularIMC();

						//Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						
						
						
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-invoice" ).attr("method","POST");
								$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_med_query/save/edit");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-invoice" ).submit();
								}
								
						});
						
						//Funcion Imprimir
						$(document).on("click","#btnPrinter",function(){
									fnWaitOpen();
									window.open("<?php echo base_url(); ?>"+"/app_med_query/viewRegisterFormatoPaginaNormalA4LabGenerico"+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>", '_blank');
									fnWaitClose();																	
						});
						
							
							$(document).on("click","#btnDelete",function(){							
								fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
									fnWaitOpen();
									$.ajax({									
										cache       : false,
										dataType    : 'json',
										type        : 'POST',
										url  		: "<?php echo base_url(); ?>/app_med_query/delete",
										data 		: {companyID : <?php echo $objTransactionMaster->companyID;?>, transactionID : <?php echo $objTransactionMaster->transactionID;?>,transactionMasterID : <?php echo $objTransactionMaster->transactionMasterID; ?>  },
										success:function(data){
											console.info("complete delete success");
											fnWaitClose();
											if(data.error){
												fnShowNotification(data.message,"error");
											}
											else{
												window.location = "<?php echo base_url(); ?>/app_med_query/index";
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
								var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCustomer->componentID; ?>/onCompleteCustomer/SELECCIONAR_ENTIDAD/true/empty/false/not_redirect_when_empty";
								window.open(url_request,"MsgWindow","width=900,height=450");
								window.onCompleteCustomer = onCompleteCustomer; 
							});		
			
			
						});
					
					
					function onCompleteCustomer(objResponse){
						
						console.info("CALL onCompleteCustomer");
					
						var entityID = objResponse[0][2];
						$("#txtCustomerID").val(objResponse[0][2]);
						$("#txtCustomerDescription").val(objResponse[0][2] + " " + objResponse[0][4] + " / " + objResponse[0][5]);	
						
						
						
					}
					
					//calcular el IMC
					function fnCalcularIMC() {
						var peso = parseInt($("#txtWeight").val());
						var altura = (parseInt($("#txtHeight").val())) / 100;

						if (isNaN(peso) || isNaN(altura) || peso <= 0 || altura <= 0) {
							$('#txtIMC').val("0");
							return;
						}

						// Calcular el IMC (peso / (altura * altura))
						var imc = peso / (altura * altura);

						$('#txtIMC').val(imc.toFixed(2));
					}

					$("#txtWeight, #txtHeight").on('input', function() {
						fnCalcularIMC();
					});

					//Manejo de campos en vista de detalles
					//boton agregar detalles
					$(document).on("click","#btnAddDetail",function(){
						fnAgregarFila();
					});

					function fnEliminarFila(boton) {
						$(boton).closest('tr').remove();
					}

					function fnAgregarFila() {
		
						let texto = $('#txtDetailName').val();
						let monto = $('#txtAmount').val();

						// Si el campo de texto está vacío, mostrar un mensaje de error
						if (texto === "") {
							$('#errorLabel').show();
							return; 
						} else {
							$('#errorLabel').hide();
						}
						let combo1 = $('#txtPriority').val();
						let combo2 = $('#txtFrecuency').val();
						let combo3 = $('#txtDose').val();
				
						let selected = '';
						let nuevaFila = ""+
							"<tr> "+
								"<td>"+
									"<input class='form-control' type='hidden' name='customerMasterDetails[]' value='0'> "+
									"<input class='form-control' type='text' name='txtDetailNameArray[]' value='"+texto+"'> "+
								"</td>"+
								"<td>"+
									"<select name='txtPriorityArray[]' id='comboPriority'>"+
									<?php
										if(isset($objListPriority)){
											foreach($objListPriority as $ws){
									?>
												"<option value='<?=$ws->catalogItemID?>' " + ((combo1==<?= $ws->catalogItemID?>) ? 'selected' : '') + "><?=$ws->name?></option>"+
									<?php
											}
										}
									?>
								"</td>"+
								"<td>"+
									"<select name='txtFrecuencyArray[]' id='comboFrecuency'>"+
									<?php
										if(isset($objListFrecuency)){
											foreach($objListFrecuency as $ws){
									?>
												"<option value='<?=$ws->catalogItemID?>' " + ((combo2==<?= $ws->catalogItemID?>) ? 'selected' : '') + "><?=$ws->name?></option>"+
									<?php
											}
										}
									?>
								"</td>"+
								"<td>"+
									"<select name='txtDoseArray[]' id='comboDose'>"+
									<?php
										if(isset($objListDose)){
											foreach($objListDose as $ws){
									?>
												"<option value='<?=$ws->catalogItemID?>' " + ((combo3==<?= $ws->catalogItemID?>) ? 'selected' : '') + "><?=$ws->name?></option>"+
									<?php
											}
										}
									?>
									"</select>"+
								"</td>"+
								"<td>"+
									"<input class='form-control' type='number' name='txtAmountArray[]' value='"+monto+"'> "+
								"</td>"+
								"<td>"+
									"<button type='button' class='btn btn-flat btn-danger' onclick='fnEliminarFila(this)'>"+
										"<i class='fas fa-trash'></i>"+
									"</button>"+
								"</td>"+
							"</tr>";

						// Agregar la nueva fila después de la primera fila de entrada
						$('#filaEntrada').after(nuevaFila);

						$('#txtDetailName').val('');
						$('#txtFrecuency').val(null).trigger('change');
						$('#txtPriority').val(null).trigger('change');
						$('#txtDose').val(null).trigger('change');

						// Inicializar Select2 en los nuevos select creados
						$('#comboFrecuency').select2();
						$('#comboPriority').select2();
						$('#comboDose').select2();
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