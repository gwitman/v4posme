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
							var txtTiposID				=	$("#txtTiposID").val();	
							var txtCategoriaID			=	$("#txtCategoriaID").val();	
							var txtClassID				=	$("#txtClassID").val();

							if(!( startOn == "" || endOn == "" ) ){
								fnWaitOpen();
								window.location	= "<?php echo base_url(); ?>/app_cxp_report/expenses/viewReport/true/startOn/"+startOn+"/endOn/"+endOn+"/txtTiposID/"+txtTiposID+"/txtCategoriaID/"+txtCategoriaID+"/txtClassID/"+txtClassID;
							}
							else{
								fnShowNotification("Completar los Parametros","error");
							}
							
						});
					});					
				</script>