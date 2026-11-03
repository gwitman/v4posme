				<!-- ./ page heading -->
				<script>					
					
					$(document).ready(function(){					
						
						//En telefono no se usa el datepicker JS (se reemplaza por el calendario nativo)
						var esTelefono = PosmeInv.isMobile();

						$('#txtStartOn').datepicker({format:"yyyy-mm-dd"});
						$('#txtStartOn').val(moment().format("YYYY-MM-DD"));
						if(!esTelefono) $("#txtStartOn").datepicker("update");
						$('#txtEndOn').datepicker({format:"yyyy-mm-dd"});
						$('#txtEndOn').val(moment().format("YYYY-MM-DD"));
						if(!esTelefono) $("#txtEndOn").datepicker("update");

						$('#txtStartOnExchange').datepicker({format:"yyyy-mm-dd"});
						$('#txtStartOnExchange').val(moment().format("YYYY-MM-DD"));
						if(!esTelefono) $("#txtStartOnExchange").datepicker("update");
						$('#txtEndOnExchange').datepicker({format:"yyyy-mm-dd"});
						$('#txtEndOnExchange').val(moment().format("YYYY-MM-DD"));
						if(!esTelefono) $("#txtEndOnExchange").datepicker("update");

						//Telefono: fechas con <input type="date"> (valor Y-m-d, igual que el datepicker). En PC no cambia nada
						PosmeInv.mobileDates('#txtStartOn,#txtEndOn,#txtStartOnExchange,#txtEndOnExchange');

						
						//Evento Agregar el Usuario						
						$(document).on("click","#btnBackReport",function(){
							fnWaitOpen();
							window.location = "<?php echo base_url(); ?>/app_accounting_currency/index";
						});
						$(document).on("click","#btnBack",function(){
							fnWaitOpen();
							window.location = "<?php echo base_url(); ?>/app_accounting_currency/index";
						});			
						$(document).on("click","#btnBackExchangeRate",function(){
							fnWaitOpen();
							window.location = "<?php echo base_url(); ?>/app_accounting_currency/index";
						});							
						$(document).on("click","#btnAceptReport",function(){
							//En telefono el calendario nativo permite dejar la fecha vacia
							if( $("#txtStartOn").val() == "" || $("#txtEndOn").val() == "" )
							{
								fnShowNotification("Seleccione la fecha inicial y final","error");
								return;
							}
							window.open( '<?php echo base_url(); ?>/app_accounting_currency/process_view_report/startOn/'+$("#txtStartOn").val()+'/endOn/'+$("#txtEndOn").val(), '_blank'); 
						});
						$(document).on("click","#btnAcept",function(){
								fnShowConfirm("Confirmar..","Desea Processar el Archivo Nombrado...",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo base_url(); ?>/app_accounting_currency/process_file",
									data 		: {fileName : $("#txtFile").val() },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											fnShowNotification("success","success");
											$("#txtFile").val("");
										}
									},
									error:function(xhr,data){	
										console.info("complete delete error");									
										fnWaitClose();
										fnShowNotification("Error 505","error");
									}
								});
							});
						});
						
						$(document).on("click","#btnAceptExchangeRate",function(){
							
							if( $("#txtValueExchangeRate").val() == "" )
							{
								fnShowNotification("Escriba el valor del tipo de cambio","error");
								return;
							}
							if( $("#txtStartOnExchange").val() == "" || $("#txtEndOnExchange").val() == "" )
							{
								fnShowNotification("Seleccione la fecha inicial y final","error");
								return;
							}

							
							fnShowConfirm("Confirmar..","Actualizar tipo de cambio...",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo base_url(); ?>/app_accounting_currency/process_file_update_exchange_rate",
									data 		: {value : $("#txtValueExchangeRate").val(), startOn : $("#txtStartOnExchange").val(), endOn : $("#txtEndOnExchange").val() },
									success:function(data){
										console.info("complete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											fnShowNotification("success","success");
											$("#txtValueExchangeRate").val("");
										}
									},
									error:function(xhr,data){	
										console.info("complete error");									
										fnWaitClose();
										fnShowNotification("Error 505","error");
									}
								});
							});
						});
						
					});
					
				</script>