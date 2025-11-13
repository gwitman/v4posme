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
							var txtCustomerNumber	=	$("#txtCustomerNumber").val();	
							var startOn					=	$("#txtStartOn").val();	
							var endOn					=	$("#txtEndOn").val();
			
							if(!( txtCustomerNumber == ""  ) ){
								fnWaitOpen();
								window.location	= "<?php echo base_url(); ?>/app_cxc_report/customer_status/"+
										"viewReport/true/customerNumber/"+txtCustomerNumber+
										"/startOn/"+ startOn+"/endOn/"+endOn;
							}
							else{
								fnShowNotification("Completar los Parametros","error");
							}
						});
					});					
				</script>