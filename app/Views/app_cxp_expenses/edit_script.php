				<!-- ./ page heading -->
				<script>		
					
					$(document).ready(function(){					
						$('#txtDate').datepicker({format:"yyyy-mm-dd"});						 
						$("#txtDate").datepicker("update");
						$('.txt-numeric').mask('000,000.00', {reverse: true});
						var urlPrinter = '<?php echo $objParameterUrlPrinter; ?>';
						
						
						//Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						
						
						
						$(document).on("change","#txtCurrencyID",function(){
							updatePantalla();
							updateAmount();
						});
						
						$(document).on("change","#txtPriorityID",function(){
							var tipoGasto = $(this).val();
							
							fnWaitOpen(); 
							$.ajax({									
								cache       : false,
								dataType    : 'json',
								type        : 'GET',
								url  		: "<?php echo base_url(); ?>/app_public_catalog_api/getPublicCatalogDetail/companyID/<?php echo $companyID; ?>/publicCatalogDetailID/" + tipoGasto ,
								
								success		: fnCompletPublicCatalogDetail,
								error:function(xhr,data){	
									fnWaitClose(); 
									console.info("complete data error");													
									fnShowNotification("Error 505","error");
								}
							});
							
						});
						
						
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-invoice" ).attr("method","POST");
								$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_cxp_expenses/save/edit");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-invoice" ).submit();
								}
								
						});
						
						
						$(document).on("click","#btnPrinter",function(){
									fnWaitOpen();
									window.open("<?php echo base_url(); ?>"+"/"+urlPrinter+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>", '_blank');
									fnWaitClose();																	
						});
						
						
						
						
						$(document).on("click","#btnDelete",function(){							
							fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo base_url(); ?>/app_cxp_expenses/delete",
									data 		: {companyID : <?php echo $objTransactionMaster->companyID;?>, transactionID : <?php echo $objTransactionMaster->transactionID;?>,transactionMasterID : <?php echo $objTransactionMaster->transactionMasterID; ?>  },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											window.location = "<?php echo base_url(); ?>/app_cxp_expenses/index";
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
							window.open("<?php echo $objParameterUrlServerFile."/core_elfinder/index/componentID/".$objComponentShare->componentID."/componentItemID/".$objTransactionMaster->transactionMasterID; ?>","blanck");
						});
						
					});
					
					
					
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						//Validar Fecha
						if($("#txtDate").val() == ""){
							fnShowNotification("Establecer Fecha al Documento","error",timerNotification);
							result = false;
						}
						
						
						//Validar Monto
						if($("#txtDetailAmount").val() == "0"){
							fnShowNotification("El monto no puede ser 0","error",timerNotification);
							result = false;
						}
						
						if( $("#txtAreaID").val() === null )
						{
							fnShowNotification("Categoria de Gasto es obligatorio","error",timerNotification);
							result = false;
						}
						
						
						return result;
					}
					
					function fnCompletPublicCatalogDetail(data)
					{
						fnWaitClose();
						data = data.objGridView;
						
						
						$("#txtAreaID").html("");
						$("#txtAreaID").val("");
						
						for(var i = 0 ; i < data.length; i++)
						{
							if(i == 0)
								$("#txtAreaID").append("<option value='"+data[i].publicCatalogDetailID+"' selected>"+ data[i].name + "</option>");
							else 
								$("#txtAreaID").append("<option value='"+data[i].publicCatalogDetailID+"'>"+ data[i].name + "</option>");
							
						}
						
						$("#txtAreaID").select2();
						
						
					}
					
					function refreschChecked(){
						$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
						//$('.txtDebit').mask('000,000.00', {reverse: true});
						//$('.txtCredit').mask('000,000.00', {reverse: true});
						$('.txt-numeric').mask('000,000.00', {reverse: true});
					}
					
				</script>