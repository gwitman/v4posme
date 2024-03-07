				<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet" /> 
				
				
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/flot/jquery.flot.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/flot/jquery.flot.pie.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/flot/jquery.flot.resize.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/flot/jquery.flot.tooltip.min.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/flot/jquery.flot.orderBars.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/flot/jquery.flot.time.min.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/flot/date.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/moment.min.js"></script>
				
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/gauge/justgage.1.0.1.min.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/gauge/raphael.2.1.0.min.js"></script>
				
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/sparklines/jquery.sparkline.min.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/pie-chart/jquery.easy-pie-chart.js"></script>
				 
				 
				
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/is-loading-master/jquery.isloading.min.js"></script>	
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/genyx-fn.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/chart-google/loader.js"></script>				
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/jLinq-2.2.1.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/jquery.number.min.js"></script>				
				<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/is-loading-master/style.css" rel="stylesheet" /> 	
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/bootstrap-datepicker.js"></script>				
				
				
				
				<div id="heading" class="page-header">
							<h1><i class="icon20 i-dashboard"></i> 
								Dashboard
							</h1>
				</div>
				
				
				<div class="row"  >
					 <div class="col-lg-12">		
						<img class="img-featured" style="width:400px;height:200px" src="<?php echo base_url();?>/resource/img/logos/logo-micro-finanza.png">													
					</div>
				</div>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>	
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>					
				<div class="row"  >
					 <div class="col-lg-6">		
						<div class="panel" style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-health"></i></div> 
								<h4>Parametros</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
						
							<div class="panel-body">								
								<div class="form-group">
									<label class="col-lg-4 control-label" for="datepicker">Inicio</label>
									<div class="col-lg-8">
										<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
											<input size="16"  class="form-control" type="text" name="txtDateStart" id="txtDateStart"  value="<?php echo $firstDate; ?>" >
											<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-4 control-label" for="datepicker">Fin</label>
									<div class="col-lg-8">
										<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
											<input size="16"  class="form-control" type="text" name="txtDateFinish" id="txtDateFinish" value="<?php echo $lastDate; ?>" >
											<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
										</div>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-lg-4 control-label" for="btnSalvarFiltro"></label>
									<div class="col-lg-8">
										<button type="button" id="btnSalvarFiltro" class="btn btn-success">Filtrar</button>    
									</div>
								</div>
								
								
								
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->									


					</div>
					<div class="col-lg-6">	
						<div class="panel" style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-health"></i></div> 
								<h4>Interés del cliente</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
						
							<div class="panel-body">								
								<div id="grafico2" style="height:300px" ></div>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->									
					</div>
				</div>			
				
				<div class="row"  >
					 <div class="col-lg-6">		
							<div class="panel" style="margin-bottom:20px;">
								<div class="panel-heading">
									<div class="icon"><i class="icon20 i-health"></i></div> 
									<h4>Tipo de propiedad</h4>
									<a href="#" class="minimize"></a>
								</div><!-- End .panel-heading -->
							
								<div class="panel-body">								
									<div id="grafico3" style="height:300px" ></div>
								</div><!-- End .panel-body -->
							</div><!-- End .widget -->		
					</div>
					<div class="col-lg-6">	
							<div class="panel" style="margin-bottom:20px;">
								<div class="panel-heading">
									<div class="icon"><i class="icon20 i-health"></i></div> 
									<h4>Clientes asignados</h4>
									<a href="#" class="minimize"></a>
								</div><!-- End .panel-heading -->
							
								<div class="panel-body">								
									<div id="grafico4" style="height:300px" ></div>
								</div><!-- End .panel-body -->
							</div><!-- End .widget -->		
					</div>
				</div>
				
				
				<div class="row"  >
					 <div class="col-lg-6">		
							<div class="panel" style="margin-bottom:20px;">
								<div class="panel-heading">
									<div class="icon"><i class="icon20 i-health"></i></div> 
									<h4>Clasificacion de clientes</h4>
									<a href="#" class="minimize"></a>
								</div><!-- End .panel-heading -->
							
								<div class="panel-body">								
									<div id="grafico5" style="height:300px" ></div>
								</div><!-- End .panel-body -->
							</div><!-- End .widget -->		
					</div>
					<div class="col-lg-6">	
							<div class="panel" style="margin-bottom:20px;">
								<div class="panel-heading">
									<div class="icon"><i class="icon20 i-health"></i></div> 
									<h4>Efectividad</h4>
									<a href="#" class="minimize"></a>
								</div><!-- End .panel-heading -->
							
								<div class="panel-body">								
									<div id="grafico6" style="height:300px" ></div>
								</div><!-- End .panel-body -->
							</div><!-- End .widget -->		
					</div>
				</div>
				
				<div class="row"  >
					 <div class="col-lg-6">		
							<div class="panel" style="margin-bottom:20px;">
								<div class="panel-heading">
									<div class="icon"><i class="icon20 i-health"></i></div> 
									<h4>Cierre de clientes</h4>
									<a href="#" class="minimize"></a>
								</div><!-- End .panel-heading -->
							
								<div class="panel-body">								
									<div id="grafico7" style="height:300px" ></div>
								</div><!-- End .panel-body -->
							</div><!-- End .widget -->		
					</div>
					<div class="col-lg-6">	
							<div class="panel" style="margin-bottom:20px;">
								<div class="panel-heading">
									<div class="icon"><i class="icon20 i-health"></i></div> 
									<h4>Enlistamiento de propiedades</h4>
									<a href="#" class="minimize"></a>
								</div><!-- End .panel-heading -->
							
								<div class="panel-body">								
									<div id="grafico8" style="height:300px" ></div>
								</div><!-- End .panel-body -->
							</div><!-- End .widget -->		
					</div>
				</div>
				
				
				<div class="row"  >
					 <div class="col-lg-6">		
							<div class="panel" style="margin-bottom:20px;">
								<div class="panel-heading">
									<div class="icon"><i class="icon20 i-health"></i></div> 
									<h4>Enlistamiento de propiedad metas</h4>
									<a href="#" class="minimize"></a>
								</div><!-- End .panel-heading -->
							
								<div class="panel-body">								
									<div id="grafico9" style="height:300px" ></div>
								</div><!-- End .panel-body -->
							</div><!-- End .widget -->		
					</div>
					<div class="col-lg-6">	
							<div class="panel" style="margin-bottom:20px;">
								<div class="panel-heading">
									<div class="icon"><i class="icon20 i-health"></i></div> 
									<h4>Rendimiento anual de venta</h4>
									<a href="#" class="minimize"></a>
								</div><!-- End .panel-heading -->
							
								<div class="panel-body">								
									<div id="grafico10" style="height:300px" ></div>
								</div><!-- End .panel-body -->
							</div><!-- End .widget -->		
					</div>
				</div>
				
				
				<div class="row"  >
					 <div class="col-lg-6">		
							<div class="panel" style="margin-bottom:20px;">
								<div class="panel-heading">
									<div class="icon"><i class="icon20 i-health"></i></div> 
									<h4>Rendimiento anual de enlistamiento</h4>
									<a href="#" class="minimize"></a>
								</div><!-- End .panel-heading -->
							
								<div class="panel-body">								
									<div id="grafico11" style="height:300px" ></div>
								</div><!-- End .panel-body -->
							</div><!-- End .widget -->		
					</div>
					
					<div class="col-lg-6">	
							<div class="panel" style="margin-bottom:20px;">
								<div class="panel-heading">
									<div class="icon"><i class="icon20 i-health"></i></div> 
									<h4>Fuente de Contacto</h4>
									<a href="#" class="minimize"></a>
								</div><!-- End .panel-heading -->
							
								<div class="panel-body">								
									<div id="grafico1" style="height:300px" ></div>
								</div><!-- End .panel-body -->
							</div><!-- End .widget -->		
					</div>
					
					
				</div>
				
				
				
				<script>						
					
					$(document).ready(function(){
							//https://www.w3schools.com/js/js_graphics_google_chart.asp
							google.charts.load('current',{packages:['corechart']});	
							
					
							//Clientes por Contactos
							///
							////////////////////////////////////////////////
							var objDataSource1	 												= new Array();
							var RealState_get_ClienteFuenteDeContacto 						 	= JSON.parse('<?php echo json_encode($RealState_get_ClienteFuenteDeContacto); ?>');												
							objDataSource1.push(new Array("Forma","Cantidad"));
							for(var i = 0 ; i < RealState_get_ClienteFuenteDeContacto.length;i++)
							{
								objDataSource1.push(
									new Array(
										RealState_get_ClienteFuenteDeContacto[i].Indicador,
										parseInt(RealState_get_ClienteFuenteDeContacto[i].Cantidad)
									)
								);	
							}
							
							google.charts.setOnLoadCallback(
								function () {

									var data = google.visualization.arrayToDataTable(
										objDataSource1
									);

									var options = {
									  title: 'Fuente de contacto',
									  colors: ['#FF5733', '#FFC300', '#FF85A2', '#FF33FF', '#33FFBD'],
									  seriesType: 'bars',
									};

									var chart = new google.visualization.ComboChart(document.getElementById('grafico1'));
									chart.draw(data, options);

								}
							);	
							
							
							
							//Clientes por Interes
							///
							////////////////////////////////////////////////							
							var objDataSource2	 												= new Array();
							var RealState_get_ClientesInteres			 						= JSON.parse('<?php echo json_encode($RealState_get_ClientesInteres); ?>');	
							objDataSource2.push(new Array("Interes","Cantidad"));
							for(var i = 0 ; i < RealState_get_ClientesInteres.length;i++)
							{
								objDataSource2.push(
									new Array(
										RealState_get_ClientesInteres[i].Indicador,
										parseInt(RealState_get_ClientesInteres[i].Cantidad)
									)
								);	
							}
																		
							google.charts.setOnLoadCallback(
								function () {
							
									var data = google.visualization.arrayToDataTable(
										objDataSource2
									);
							
									var options = {
									  title: 'Interes del cliente',
									  colors: ['#33A1FF', '#FF3366', '#FF3333', '#33FF33', '#33FFA8'],
									};
							
									var chart = new google.visualization.BarChart(document.getElementById('grafico2'));
									chart.draw(data, options);
							
								}
							);	
							
							
							//Clientes por Stilo de propiedad
							///
							////////////////////////////////////////////////	
							var objDataSource3	 												= new Array();
							var RealState_get_ClientesTipoPropiedad		 						= JSON.parse('<?php echo json_encode($RealState_get_ClientesTipoPropiedad); ?>');															
							objDataSource3.push(new Array("Tipo","Cantidad"));
							for(var i = 0 ; i < RealState_get_ClientesTipoPropiedad.length;i++)
							{
								objDataSource3.push(
									new Array(
										RealState_get_ClientesTipoPropiedad[i].Indicador,
										parseInt(RealState_get_ClientesTipoPropiedad[i].Cantidad)
									)
								);	
							}
																		
							google.charts.setOnLoadCallback(
								function () {
							
									var data = google.visualization.arrayToDataTable(
										objDataSource3
									);
							
									var options = {
									  title: 'Cliente vs tipo de propiedad',
									  colors: ['#3399FF', '#9966FF', '#FF33CC', '#FF6633', '#FFFF33'],
									};
							
									var chart = new google.visualization.PieChart(document.getElementById('grafico3'));
									chart.draw(data, options);
							
								}
							);	
							
							
							
							//Clientes por Agente
							///
							////////////////////////////////////////////////	
							var objDataSource4	 												= new Array();
							var RealState_get_ClientesPorAgentes			 					= JSON.parse('<?php echo json_encode($RealState_get_ClientesPorAgentes); ?>');								
							objDataSource4.push(new Array("Agente","Cantidad"));
							for(var i = 0 ; i < RealState_get_ClientesPorAgentes.length;i++)
							{
								objDataSource4.push(
									new Array(
										RealState_get_ClientesPorAgentes[i].Indicador,
										parseInt(RealState_get_ClientesPorAgentes[i].Cantidad)
									)
								);	
							}
																		
							google.charts.setOnLoadCallback(
								function () {
							
									var data = google.visualization.arrayToDataTable(
										objDataSource4
									);
							
									var options = {										
									  title: 'Cliente asignados',
									  colors: ['#FF33FF', '#66FF33', '#33FFFF', '#CC33FF', '#FFCC33'],
									  pieHole: 0.4,
									};
							
									var chart = new google.visualization.LineChart(document.getElementById('grafico4'));
									chart.draw(data, options);
							
								}
							);	
							
							
							
							//Clientes por Stilo de propiedad
							///
							////////////////////////////////////////////////	
							var objDataSource5	 												= new Array();
							var RealState_get_ClientesClasificacionPorAgentes 				 	= JSON.parse('<?php echo json_encode($RealState_get_ClientesClasificacionPorAgentes); ?>');	
							var RealState_get_Clientes05Indicadores								= jLinq.from(jLinq.from(RealState_get_ClientesClasificacionPorAgentes).select(function(a){ return a.Indicador })).distinct();
							var RealState_get_Clientes05AgenteEfectividad						= jLinq.from(jLinq.from(RealState_get_ClientesClasificacionPorAgentes).select(function(a){ return a.Agente })).distinct();
							
							var arrayTitle = new Array();
							arrayTitle.push("Agente");
							for(var i = 0 ; i < RealState_get_Clientes05Indicadores.length; i++)
							{
								arrayTitle.push(RealState_get_Clientes05Indicadores[i]);
							}
							
							objDataSource5.push(arrayTitle);
							for(var i = 0 ; i < RealState_get_Clientes05AgenteEfectividad.length;i++)
							{
								var arrayRow = new Array();
								arrayRow.push(RealState_get_Clientes05AgenteEfectividad[i]);
								for(var ix = 0 ; ix < RealState_get_Clientes05Indicadores.length;ix++)
								{
									var amountTotal =  jLinq.from(jLinq.from(RealState_get_ClientesClasificacionPorAgentes).where(function(a){ 
																		return (
																			a.Agente == RealState_get_Clientes05AgenteEfectividad[i] && 
																			a.Indicador == RealState_get_Clientes05Indicadores[ix] 
																		)
																	}).select(function(a){ return parseFloat(a.Cantidad) })).sum().result;
									arrayRow.push(amountTotal);
									
								}
								objDataSource5.push(arrayRow);	
							}
							
							google.charts.setOnLoadCallback(
								function () {
							
									var data = google.visualization.arrayToDataTable(
										objDataSource5
									);
							
									var options = {										
									  title: 'Clasificacion de clientes',
									  colors: ['#FF5733', '#FFC300', '#FF85A2', '#FF33FF', '#33FFBD'],
									  vAxis: {title: 'Clasificacion'},
									  hAxis: {title: 'Agente'},
									  seriesType: 'bars',
									  series: {5: {type: 'line'}}
		  
		  
									};
							
									var chart = new google.visualization.ComboChart(document.getElementById('grafico5'));
									chart.draw(data, options);
							
								}
							);	
							
							
							//Clientes por Efectividad por Agente
							///
							////////////////////////////////////////////////	
							var RealState_get_AgenteEfectividad		 							= JSON.parse('<?php echo json_encode($RealState_get_AgenteEfectividad); ?>');	
							var objDataSource6	 												= new Array();							
							objDataSource6.push(new Array("Agente","Cantidad"));
							for(var i = 0 ; i < RealState_get_AgenteEfectividad.length;i++)
							{
								objDataSource6.push(
									new Array(
										RealState_get_AgenteEfectividad[i].Indicador,
										parseInt(RealState_get_AgenteEfectividad[i].Cantidad)
									)
								);	
							}
																		
							google.charts.setOnLoadCallback(
								function () {
							
									var data = google.visualization.arrayToDataTable(
										objDataSource6
									);
							
									var options = {										
									  title: 'Efectividad por agente',
									  colors: ['#33A1FF', '#FF3366', '#FF3333', '#33FF33', '#33FFA8'],
									  pieHole: 0.4,
									};
							
									var chart = new google.visualization.PieChart(document.getElementById('grafico6'));
									chart.draw(data, options);
							
								}
							);	
							
							
							
							
							//Clientes por Stilo de propiedad
							///
							////////////////////////////////////////////////	
							var objDataSource7	 												= new Array();
							var RealState_get_ClientesCerrados			 						= JSON.parse('<?php echo json_encode($RealState_get_ClientesCerrados); ?>');
							objDataSource7.push(new Array("Clasificacion","Cantidad"));
							for(var i = 0 ; i < RealState_get_ClientesCerrados.length;i++)
							{
								objDataSource7.push(
									new Array(
										RealState_get_ClientesCerrados[i].Indicador,
										parseInt(RealState_get_ClientesCerrados[i].Cantidad)
									)
								);	
							}
																		
							google.charts.setOnLoadCallback(
								function () {
							
									var data = google.visualization.arrayToDataTable(
										objDataSource7
									);
							
									var options = {										
									  title: 'Cierre de clientes',
									  colors: ['#33A1FF', '#FF3366', '#FF3333', '#33FF33', '#33FFA8'],
									  pieHole: 0.4,
									};
							
									var chart = new google.visualization.PieChart(document.getElementById('grafico7'));
									chart.draw(data, options);
							
								}
							);	
							
							
							
							
							
							//Propiedades por Agente
							///
							////////////////////////////////////////////////	
							var objDataSource8	 												= new Array();
							var RealState_get_PropiedadesPorAgentes			 					= JSON.parse('<?php echo json_encode($RealState_get_PropiedadesPorAgentes); ?>');								
							objDataSource8.push(new Array("Agente","Cantidad"));
							for(var i = 0 ; i < RealState_get_PropiedadesPorAgentes.length;i++)
							{
								objDataSource8.push(
									new Array(
										RealState_get_PropiedadesPorAgentes[i].Indicador,
										parseInt(RealState_get_PropiedadesPorAgentes[i].Cantidad)
									)
								);	
							}
							
																		
							google.charts.setOnLoadCallback(
								function () {
							
									var data = google.visualization.arrayToDataTable(
										objDataSource8
									);
							
									var options2 = {										
										title: 'Enlistamiento de propiedades',
										colors: ['#3399FF', '#9966FF', '#FF33CC', '#FF6633', '#FFFF33'],												
									};
							
									var chart = new google.visualization.AreaChart(document.getElementById('grafico8'));
									chart.draw(data, options2);
							
								}
							);	
							
							
							//Propiedades por Agente vs Metas
							///
							////////////////////////////////////////////////	
							var RealState_get_PropiedadesPorAgentesMetas 				 		= JSON.parse('<?php echo json_encode($RealState_get_PropiedadesPorAgentesMetas); ?>');	
							var objDataSource9	 												= new Array();							
							objDataSource9.push(new Array("Agente","Cantidad"));
							for(var i = 0 ; i < RealState_get_PropiedadesPorAgentesMetas.length;i++)
							{
								objDataSource9.push(
									new Array(
										RealState_get_PropiedadesPorAgentesMetas[i].Indicador,
										parseInt(RealState_get_PropiedadesPorAgentesMetas[i].Cantidad)
									)
								);	
							}
							
																		
							google.charts.setOnLoadCallback(
								function () {
							
									var data = google.visualization.arrayToDataTable(
										objDataSource9
									);
							
									var options2 = {										
										title: 'Enlistamiento de propiedades metas',
										colors: ['#3399FF', '#9966FF', '#FF33CC', '#FF6633', '#FFFF33'],												
									};
							
									var chart = new google.visualization.AreaChart(document.getElementById('grafico9'));
									chart.draw(data, options2);
							
								}
							);
							
							
							//Agente Rendimiento Anual de Ventas propiedades
							///
							////////////////////////////////////////////////	
							var objDataSource10	 												= new Array();
							var RealState_get_PropiedadesRendimientoAnualVentas			 		= JSON.parse('<?php echo json_encode($RealState_get_PropiedadesRendimientoAnualVentas); ?>');	
							objDataSource10.push(new Array("Clasificacion","Cantidad"));
							for(var i = 0 ; i < RealState_get_PropiedadesRendimientoAnualVentas.length;i++)
							{
								objDataSource10.push(
									new Array(
										RealState_get_PropiedadesRendimientoAnualVentas[i].Indicador,
										parseInt(RealState_get_PropiedadesRendimientoAnualVentas[i].Cantidad)
									)
								);	
							}
																		
							google.charts.setOnLoadCallback(
								function () {
							
									var data = google.visualization.arrayToDataTable(
										objDataSource10
									);
							
									var options = {										
									  
									  title: 'Rendimiento anual de ventas',
									  colors: ['#FF5733', '#FFC300', '#FF85A2', '#FF33FF', '#33FFBD'],
									  vAxis: {title: 'Clasificacion'},
									  hAxis: {title: 'Agente'},
									  seriesType: 'bars',
									  series: {5: {type: 'line'}}
		  
		  
		  
									};
							
									var chart = new google.visualization.ComboChart(document.getElementById('grafico10'));
									chart.draw(data, options);
							
								}
							);	
							

							
							//Propiedades Agente Rendimiento anual de enlistamiento
							///
							////////////////////////////////////////////////							
							var objDataSource11	 												= new Array();
							var RealState_get_PropiedadesRendimientoAnualEnlistamiento		 	= JSON.parse('<?php echo json_encode($RealState_get_PropiedadesRendimientoAnualEnlistamiento); ?>');															
							objDataSource11.push(new Array("Agente","Cantidad"));
							for(var i = 0 ; i < RealState_get_PropiedadesRendimientoAnualEnlistamiento.length;i++)
							{
								objDataSource11.push(
									new Array(
										RealState_get_PropiedadesRendimientoAnualEnlistamiento[i].Indicador,
										parseInt(RealState_get_PropiedadesRendimientoAnualEnlistamiento[i].Cantidad)
									)
								);	
							}
																		
							google.charts.setOnLoadCallback(
								function () {
							
									var data = google.visualization.arrayToDataTable(
										objDataSource11
									);
							
									var options = {
									  title: 'Rendimiento anual de enlistamiento',
									  colors: ['#33A1FF', '#FF3366', '#FF3333', '#33FF33', '#33FFA8'],
									};
							
									var chart = new google.visualization.BarChart(document.getElementById('grafico11'));
									chart.draw(data, options);
							
								}
							);	
							
							
							
							
							
							
							$(document).on("click","#btnSalvarFiltro",function(){
									var txtDateStart		=	$("#txtDateStart").val();	
									var txtDateFinish		=	$("#txtDateFinish").val();
									fnWaitOpen();
									window.location	= "<?php echo base_url(); ?>/core_dashboards_santa_lucia_real_state/index/txtDateStart/"+txtDateStart+"/txtDateFinish/"+txtDateFinish;
									
							});
							
							$('#txtDateStart').datepicker({format:"yyyy-mm-dd"});
							$('#txtDateFinish').datepicker({format:"yyyy-mm-dd"});							
									
							
							
						}
					);		
					
					
				
					
						
					
					
											
				</script>
				
				