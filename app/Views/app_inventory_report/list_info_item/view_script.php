<!-- ./ page heading -->
<script>				
	$(document).ready(function(){
		$(document).on("click","#print-btn-report",function(){
			
			var warehouseID 			=	$("#txtWarehouseID").val();
			var categoryID	 			=	$("#txtCategoryID").val();
			var formatID	 			=	$("#txtFormatID").val();
										
			if(!(warehouseID == "" /*|| startOn == "" || endOn == "" || itemID == ""*/ ) ){
					
				if (formatID == "0"){
					fnWaitOpen();
					window.location	= "<?php echo base_url(); ?>/app_inventory_report/list_info_item/viewReport/true/warehouseID/"+warehouseID+"/categoryID/"+categoryID+"/formatID/"+formatID;
				}
				else
				{
					var URL = "<?php echo base_url(); ?>/app_inventory_report/download_report_info_producto/warehouseID/"+warehouseID+"/categoryID/"+categoryID+"/formatID/"+formatID;
					window.open(URL, '_blank');
				}
			}
			else{
				fnShowNotification("Completar los Parametros","error");
			}													
			
		});
	});					
</script>