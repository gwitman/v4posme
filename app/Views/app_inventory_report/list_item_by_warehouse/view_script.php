				<!-- ./ page heading -->
				<script>				
					$(document).ready(function(){
						$(document).on("click","#print-btn-report",function(){								
							var warehouseID		=	$("#warehouseID").val();		

							debugger;
							
							if(warehouseID != null)							
							{
								var warehouseIDString = "-1";
								for(var i = 0 ; i < warehouseID.length; i++)
								{
									warehouseIDString = warehouseIDString + "," + warehouseID[i];
								}
								
								
								window.location		= "<?php echo base_url(); ?>/app_inventory_report/list_item_by_warehouse/viewReport/true/warehouseIDFilter/"+warehouseIDString;
							}
						});
					});					
				</script>