				<!-- ./ page heading -->
				<script>				
					$(document).ready(function(){
						$(document).on("click","#print-btn-report",function(){
							
							var warehouseID 			=	$("#txtWarehouseID").val();
							var categoryID	 			=	$("#txtCategoryID").val();
							
							if(!(warehouseID == "" /*|| startOn == "" || endOn == "" || itemID == ""*/ ) ){
								fnWaitOpen();
								window.location	= "<?php echo base_url(); ?>/app_inventory_report/list_item/viewReport/true/warehouseID/"+warehouseID+"/categoryID/"+categoryID;
							}
							else{
								fnShowNotification("Completar los Parametros","error");
							}
							
						});
					});					
				</script>