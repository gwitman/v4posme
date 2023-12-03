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
							
							if(!( startOn == "" || endOn == "" ) )
							{
								var url  		= "<?php echo base_url(); ?>/app_sales_report/sales_detail_format_80ml_direct/viewReport/true/startOn/"+
								startOn+"/endOn/"+endOn+
								"/hourStart/"+horaInicial+"/hourEnd/"+horaFinal;
								//window.location	=  url;								
								window.open(url, "_blank" /*, "toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400"*/  );
								
							}
							else{
								fnShowNotification("Completar los Parametros","error");
							}
							
						});
					});					
				</script>