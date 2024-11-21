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
							var employeeNumber 			=	$("#txtEmployeeNumber").val();
							var startOn					=	$("#txtStartOn").val();	
							var endOn					=	$("#txtEndOn").val();							
							var hourStart				=	$("#txtHourStartOn").val();	
							var hourEnd					=	$("#txtHourEndOn").val();	
							
							if(!(employeeNumber == ""  )){
								fnWaitOpen();
								window.location	= "<?php echo base_url(); ?>/app_box_report/reconciliation_deposits/viewReport/true/employeeNumber/"+
								employeeNumber+"/startOn/"+startOn+"/endOn/"+endOn+
								"/hourStart/"+hourStart+"/hourEnd/"+hourEnd;
							}
							else{
								fnShowNotification("Completar los Parametros","error");
							}
						});
					});					
				</script>