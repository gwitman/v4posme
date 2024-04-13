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
							var showActivos				=	$("#txtShowActivos").val();	
							var warehouseID				=	$("#txtWarehouseID").val();	
							var namePropietario			=	$("#txtNamePropietario").val();	
							var numberEncuentra24		=	$("#txtNumberEncuentra24").val();	
							
							if(!( startOn == "" || endOn == "" ) ){
								fnWaitOpen();
								window.location	= "<?php echo base_url(); ?>/app_inventory_report/list_item_real_estate/viewReport/true/startOn/"+
									startOn+"/endOn/"+endOn+"/showActivos/"+showActivos+
									"/warehouseID/"+warehouseID+"/namePropietario/"+namePropietario+"/numberEncuentra24/"+numberEncuentra24;
							}
							else{
								fnShowNotification("Completar los Parametros","error");
							}
							
						});
					});					
				</script>