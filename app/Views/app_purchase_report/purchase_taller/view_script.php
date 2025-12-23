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
							var txtCustomerNumber		=	$("#txtCustomerNumber").val();							
							var employerID				=	$("#employerID").val();		
							var startOn					=	$("#txtStartOn").val();	
							var endOn					=	$("#txtEndOn").val();
							
							if(!( startOn == "" || endOn == "" ) )
							{
								if(employerID != null)							
								{
									var employerIDString = "-1";
									for(var i = 0 ; i < employerID.length; i++)
									{
										employerIDString = employerIDString + "," + employerID[i];
									}
									
									window.location		= "<?php echo base_url(); ?>/app_purchase_report/purchase_taller/" + 
											"viewReport/true/startOn/"+startOn+
											"/endOn/"+endOn+"/employerIDFilter/"+employerIDString+
											"/customerNumber/"+txtCustomerNumber;
								}
								else
								{
									fnShowNotification("Completar los Parametros","error");
								}
							}
							else
							{
								fnShowNotification("Completar los Parametros","error");
							}
						});
					});					
				</script>