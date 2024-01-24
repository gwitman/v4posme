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
							var filterConcept			=   "-1";
							var categoryItem			=   "-1";
							
							
							for( var i = 0 ; i < $("input[type=checkbox].areas").length ; i ++)
							{
								if( $($("input[type=checkbox].areas")[i]).is(':checked')    )
								{
									var tem 		= $($("input[type=checkbox].areas")[i]).val();
									filterConcept 	= filterConcept + "," +  tem ;
								}
							}
							
							if( filterConcept.indexOf(",0") != -1 )
							{
								filterConcept = "0";
							}
							
							for( var i = 0 ; i < $("input[type=checkbox].categorias").length ; i ++)
							{
								if( $($("input[type=checkbox].categorias")[i]).is(':checked')    )
								{
									var tem 		= $($("input[type=checkbox].categorias")[i]).val();
									categoryItem 	= categoryItem + "," +  tem ;
								}
							}
							
							if( categoryItem.indexOf(",0") != -1 )
							{
								categoryItem = "0";
							}
							
							
							if(!( startOn == "" || endOn == ""  ) ){
								fnWaitOpen();
								window.location	= "<?php echo base_url(); ?>/app_box_report/daily_town/"+
								"viewReport/true/startOn/"+startOn+"/endOn/"+endOn+"/userIDFilter/"+userID+
								"/hourStart/"+hourStart+"/hourEnd/"+hourEnd+"/conceptoFilter/"+filterConcept+
								"/categoryItem/"+categoryItem;
							}
							else{
								fnShowNotification("Completar los Parametros","error");
							}
						});
					});					
				</script>