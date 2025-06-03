				<!-- ./ page heading -->
				<script>				
					$(document).ready(function(){
						$(document).on("click","#print-btn-report",function(){
							
							var warehouseID 			=	$("#txtWarehouseID").val();
							var categoryID	 			=	$("#txtCategoryID").val();
							
							if(!(warehouseID == "" ) ){
								fnWaitOpen();
								window.location	= "<?php echo base_url(); ?>/app_inventory_report/list_item_inventory/viewReport/true/warehouseID/"+warehouseID+"/categoryID/"+categoryID;
							}
							else{
								fnShowNotification("Completar los Parametros","error");
							}
							
						});
					});					
				</script>