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
							var userID					=	$("#txtUserID").val();	
							var hourStart				=	$("#txtHourStartOn").val();	
							var hourEnd					=	$("#txtHourEndOn").val();	
							
							
						
							
							if(!( startOn == "" || endOn == ""  ) ){
								fnWaitOpen();
								window.location	= "<?php echo base_url(); ?>/app_collection_report/sumary_credit/"+
								"viewReport/true/startOn/"+startOn+"/endOn/"+endOn+"/userIDFilter/"+userID+
								"/hourStart/"+hourStart+"/hourEnd/"+hourEnd;
							}
							else{
								fnShowNotification("Completar los Parametros","error");
							}
						});
					});					
				</script>