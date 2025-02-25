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
							var warehouseID 			=	$("#txtWarehouseID").val();
							var providerID	 			=	$("#txtProviderID").val();
							
							var horaInicial				=   $("#txtHoraInicial_hora").val();
							var minutoInicial			=   $("#txtHoraInicial_minuto").val();
							var zonaInicial				=   $("#txtHoraInicial_zona").val();
							var horaInicial				=   zonaInicial == "PM" ? (parseInt( horaInicial) + 12) : horaInicial;
							var horaInicial				=   horaInicial+":"+minutoInicial+":00";
							
							
							var horaFinal				=   $("#txtHoraFinal_hora").val();
							var minutoFinal				=   $("#txtHoraFinal_minuto").val();
							var zonaFinal				=   $("#txtHoraFinal_zona").val();
							var horaFinal				=   zonaFinal == "PM" ? (parseInt( horaFinal) + 12) : horaFinal;
							var horaFinal				=   horaFinal+":"+minutoFinal+":00";
							
							
							if(!( startOn == "" || endOn == "" ) ){
								fnWaitOpen();
								window.location	= "<?php echo base_url(); ?>/app_inventory_report/purchase/viewReport/true/startOn/"+startOn+
								"/endOn/"+endOn+"/warehouseID/"+warehouseID+
								"/horaInicial/"+horaInicial+"/horaFinal/"+horaFinal+
								"/providerID/"+providerID;
							}
							else{
								fnShowNotification("Completar los Parametros","error");
							}
						});
					});					
				</script>