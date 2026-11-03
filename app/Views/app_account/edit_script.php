				<script>	 				
					$(document).ready(function() {
						//define chart clolors ( you maybe add more colors if you want or flot will add it automatic )
						var chartColours 	= ['#62aeef', '#d8605f', '#72c380', '#6f7a8a', '#f7cb38', '#5a8022', '#2c7282'];
						var tickSize 		= [1, "month"];
						
						if($(".chart-line").length) {
							//graph options
							var options = {
								grid: {
									show				: true,
									aboveData			: true,
									color				: "#3f3f3f" ,
									labelMargin			: 5,
									axisMargin			: 0, 
									borderWidth			: 0,
									borderColor			:null,
									minBorderMargin		: 5 ,
									clickable			: true, 
									hoverable			: true,
									autoHighlight		: true,
									mouseActiveRadius	: 20
								},
								series: {
									lines: {
										show		: true,
										fill		: true,
										lineWidth	: 2,
										steps		: false
										},
									points: {show:false}
								},
								legend: { 
									position	: "ne", 
									margin		: [0,-25], 
									noColumns	: 0,
									labelBoxBorderColor: null,
									labelFormatter: function(label, series) {										
										return label+'&nbsp;&nbsp;';
									},
									width: 40,
									height: 1
								},
								yaxis: {
									min			: 0,
									//Eje Y con formato 1,000.00 (solo visual)
									tickFormatter: function(valor){ return pv2Fmt(valor); }
								},
								xaxis: {
									mode		: "time",	
									minTickSize : tickSize,									
									timeformat	: "%m/%y"
								},
								colors: chartColours,
								shadowSize:1,
								tooltip: true,
								tooltipOpts: {
									//Monto con formato 1,000.00 (solo visual)
									content: function(label, xval, yval){
										return label + " : " + pv2Fmt(yval);
									},
									xDateFormat: "%d/%m",
									shifts: {
										x: -30,
										y: -50
									},
									defaultTheme: false
								}
							};   
							$.ajax({									
								cache       : false,
								dataType    : 'json',
								type        : 'POST',
								url  		: "<?php echo base_url(); ?>/app_accounting_api/getHistoryBalanceByAccount",
								data 		: {companyID : <?php echo $objAccount->companyID;?>, accountID : <?php echo $objAccount->accountID;?>  },
								success:function(data){

									//Caso 1: el servidor devolvio error -> se muestra el mensaje en el estado vacio
									if(data.error === true){
										$(".chart-line").hide();
										$("#pv2EmptyBalanceText").text(data.message);
										$("#pv2EmptyBalance").show();
										fnShowNotification(data.message,"error");
										return;
									}

									//Caso 2: sin balances -> estado vacio
									if(!data.data || data.data.length == 0){
										$(".chart-line").hide();
										$("#pv2EmptyBalanceText").text("Sin balances");
										$("#pv2EmptyBalance").show();
										return;
									}

									//Caso 3: con datos -> se dibuja la grafica
									$("#pv2EmptyBalance").hide();
									$(".chart-line").show();
									var d1 		= [];
									$.each(data.data,function(i,obj){
										d1.push([moment(obj.startOnCycle),parseFloat(obj.balanceEnd)]);
									});

									$.plot($(".chart-line"), [
										{
											label	: "Saldo", 
											data	: d1,
											lines	: {fillColor: "#f3faff"}
										}
									], options);
									
								},
								error:function(xhr,data){
									$(".chart-line").hide();
									$("#pv2EmptyBalanceText").text("error en la grafica");
									$("#pv2EmptyBalance").show();
									fnShowNotification("error en la grafica","error");
								}
							});
							
						}
					});	
						
					
					$(document).ready(function(){
						
						//Evento Regresar a la lista
						$(document).on("click","#btnBack",function(){
							fnWaitOpen();
						});
						//Comando Guardar
						$(document).on("click","#btnAcept",function(){
								fnWaitOpen();
								$( "#form-new-account" ).attr("method","POST");
								$( "#form-new-account" ).attr("action","<?php echo base_url(); ?>/app_accounting_account/save/edit");
								$( "#form-new-account" ).submit();
						});
						//Comando Eliminar
						$(document).on("click","#btnDelete",function(){							
							fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo base_url(); ?>/app_accounting_account/delete",
									data 		: {companyID : <?php echo $objAccount->companyID;?>, accountID : <?php echo $objAccount->accountID;?>  },
									success:function(data){
										fnWaitClose();
										console.info("complete delete success");
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											window.location = "<?php echo base_url(); ?>/app_accounting_account/index";
										}
									},
									error:function(xhr,data){	
										fnWaitClose();
										console.info("complete delete error");									
										fnShowNotification("Error 505","error");
									}
								});
							});
						});


					});


	//Telefono: boton Guardar en la barra inferior y combos nativos (sin select2). En PC no cambia nada
	$(document).ready(function(){
		if(window.pv2BindMobileBar){ pv2BindMobileBar(); }
		PosmeInv.mobileSelects();
	});
</script>