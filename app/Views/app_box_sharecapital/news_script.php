				<!-- ./ page heading -->
				<script>
					var varUseMobile							= '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';
					var varParameterCantidadItemPoup			= '<?php echo $objParameterCantidadItemPoup; ?>';
					var varComponentItemID						= '<?php echo $objComponentItem->componentID; ?>';
					var varComponentCustomerCreditDocumentID	= '<?php echo $objComponentCustomerCreditDocument->componentID; ?>';
					var varIsEditMode							= false;
				</script>
				<?php echo view('core_template/posme_v2_common'); ?>
				<?php echo view('app_box_sharecapital/cap_v2_common'); ?>
				<script>
					$(document).ready(function(){
						pv2InitDate("txtDate", true);
						capBindCommon();

						//Guardar
						$(document).on("click","#btnAcept",function(e){
								e.preventDefault();
								$( "#form-new-invoice" ).attr("method","POST");
								$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_box_sharecapital/save/new");

								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-invoice" ).submit();
								}
						});
					});
				</script>
