				<!-- ./ page heading -->
				<script>
					var varUseMobile							= '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';
					var varUrlPrinter							= '<?php echo $urlPrinterDocument; ?>';
					var varParameterCantidadItemPoup			= '<?php echo $objParameterCantidadItemPoup; ?>';
					var varComponentItemID						= '<?php echo $objComponentItem->componentID; ?>';
					var varComponentCustomerCreditDocumentID	= '<?php echo $objComponentCustomerCreditDocument->componentID; ?>';
					var varIsEditMode							= true;
				</script>
				<?php echo view('core_template/posme_v2_common'); ?>
				<?php echo view('app_box_sharecapital/cap_v2_common'); ?>
				<script>
					$(document).ready(function(){
						pv2InitDate("txtDate", false);
						capBindCommon();

						//Imprimir Documento
						$(document).on("click","#btnPrinter",function(e){
							e.preventDefault();
							fnWaitOpen();
							window.location = "<?php echo base_url(); ?>/"+varUrlPrinter+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>";
						});

						//Guardar
						$(document).on("click","#btnAcept",function(e){
								e.preventDefault();
								$( "#form-new-invoice" ).attr("method","POST");
								$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_box_sharecapital/save/edit");

								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-invoice" ).submit();
								}
						});

						//Eliminar el documento
						$(document).on("click","#btnDelete",function(e){
							e.preventDefault();
							fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
								fnWaitOpen();
								$.ajax({
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo base_url(); ?>/app_box_sharecapital/delete",
									data 		: {companyID : <?php echo $objTransactionMaster->companyID;?>, transactionID : <?php echo $objTransactionMaster->transactionID;?>,transactionMasterID : <?php echo $objTransactionMaster->transactionMasterID; ?>  },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											window.location = "<?php echo base_url(); ?>/app_box_sharecapital/index";
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

						//Archivos
						$(document).on("click","#btnClickArchivo",function(e){
							e.preventDefault();
							window.open("<?php echo base_url()."core_elfinder/index/componentID/".$objComponentTransactionShareCapital->componentID."/componentItemID/".$objTransactionMaster->transactionMasterID; ?>","blanck");
						});
					});
				</script>
