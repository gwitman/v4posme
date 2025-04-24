				<!-- ./ page heading -->
				<script>				
					$(document).ready(function(){
						$('#txtStartOn').datepicker({format:"yyyy-mm-dd"});
						$('#txtStartOn').val(moment().format("YYYY-MM-DD"));
						$("#txtStartOn").datepicker("update");																		
						$('#txtEndOn').datepicker({format:"yyyy-mm-dd"});
						$('#txtEndOn').val(moment().format("YYYY-MM-DD"));		
						$("#txtEndOn").datepicker("update");						
						
						$(document).on("click","#print-btn-report",function(){
							var startOn					=	$("#txtStartOn").val();	
							var endOn					=	$("#txtEndOn").val();
							var inventoryCategoryID		=	$("#txtInventoryCategoryID").val();	
							var warehouseID				=	$("#txtWarehouseID").val();	
							var userID					=	$("#txtUserID").val();	
							var texto					=	$("#txtTexto").val();	
							
							if(!( startOn == "" || endOn == "" ) ){
								fnWaitOpen();
								window.location	= "<?php echo base_url(); ?>/app_sales_report/sales_detail_commission/viewReport/true/startOn/"+
													startOn+"/endOn/"+endOn+"/inventoryCategoryID/"+
													inventoryCategoryID+"/warehouseID/"+
													warehouseID+"/userIDFilter/"+
													userID+"/texto/"+
													texto;
							}
							else{
								fnShowNotification("Completar los Parametros","error");
							}
							
						});
					});					
				</script>