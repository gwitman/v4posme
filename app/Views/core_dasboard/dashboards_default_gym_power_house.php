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

				<div id="heading" class="page-header">
							<h1><i class="icon20 i-dashboard"></i> 
								Dashboard								
							</h1>
				</div>
				
				<div class="row">
					 <div class="col-lg-6">		
						<div class="panel" style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-health"></i></div> 
								<h4><?php echo $company->name; ?></h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
						
							<div class="panel-body">
								<img class="img-featured" style="width:300px;height:200px" src="<?php echo base_url();?>/resource/img/logos/logo-micro-finanza.jpg">
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->		
						
						<div class="panel" style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-health"></i></div> 
								<h4>Ingresos por Membresias Mes Actual</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
							
							<div class="panel-body">								
								<div id="grafico2" style="height:300px" ></div>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->		
							
						<div class="panel <?php echo getBehavio($company->type,"core_dashboards","divPanelBiblico",""); ?>  " style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-quotes-left"></i></div> 
								<h4>Consejo bíblico</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
						
							<div class="panel-body">
										<blockquote>
											<p style="text-align: justify;">
													<?php 														
														foreach($objVersiculo as $objVersiculoItem)
														{
															echo $objVersiculoItem->versiculo." <br/><br/>"; 
														}
													?>
											</p>									
											<small>
												<?php echo $objVersiculo[0]->libro; ?> <?php echo $objVersiculo[0]->capitulo; ?>
											</small>
										</blockquote>
								
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->

						<div class="panel" style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-health"></i></div> 
								<h4>Conteo de Membresias</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
							<div class="panel-body">								
								<div id="grafico4" style="height:150px" ></div>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->	

					</div>
					<div class="col-lg-6">	
						
						<div class="panel" style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-quotes-left"></i></div> 
								<h4>Tipo de cambio</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
						
							<div class="panel-body">
								<blockquote>
									<p class="text-success">VALOR: <?php echo number_format($objExchangeRateCordobaDolar,2); ?></p>									
									<small>Dolar a Cordoba</small>
								</blockquote>
								
							   <blockquote>
									<p class="text-danger">VALOR: <?php echo number_format($objExchangeRateDolarACordoba,4); ?></p>									
									<small>Cordoba al Dolar</small>
								</blockquote>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->	
						
						<div class="panel" style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-health"></i></div> 
								<h4> Ventas de Contado Mes Actual</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
							<div class="panel-body">								
								<div id="grafico1" style="height:150px" ></div>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->	
						
						<div class="panel <?php echo getBehavio($company->type,"core_dashboards","divPanelSoporteTenico",""); ?> " style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-quotes-left"></i></div> 
								<h4>Informacion de contacto</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
						
							<div class="panel-body">
							   <blockquote>
									<p>Soporte Tenico: 8712-5827</p>									
									<small>posMe</small>
								</blockquote>
								<a aria-label="Chat on WhatsApp" target="_blank" href="https://wa.me/50587125827?text=Buenos dias le saluda <?php echo $user->email; ?> : "> 
									<img alt="Chat on WhatsApp" src="<?php echo base_url();?>/resource/img/logos/WhatsAppButtonGreenSmall.svg" /> 
								</a>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->	

						<div class="panel" style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-health"></i></div> 
								<h4>Proyeccion de Membresias</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
							
							<div class="panel-body">								
								<div id="grafico3" style="height:300px" ></div>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->	

						<div class="panel <?php echo getBehavio($company->type,"core_dashboards","divPanelUsuario",""); ?> " style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-quotes-left"></i></div> 
								<h4>Usuario</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
						
							<div class="panel-body">
							   <blockquote>
									<p><?php echo $user->nickname; ?><br/><?php echo $user->email; ?></p>
									<small>posMe</small>
								</blockquote>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->		
						
						
					</div>
				</div>

				<script>	
					// https://www.w3schools.com/js/js_graphics_google_chart.asp
					
					var objVentasDeContadoMesActual		 			= JSON.parse('<?php echo json_encode($objListVentasContadoMesActual); ?>');	
					var objIngresosPorMembresias 				 	= JSON.parse('<?php echo json_encode($objListIngresosPorMembresias); ?>');	
					var objProyeccionDeMembresias			 		= JSON.parse('<?php echo json_encode($objListProyeccionDeMembresias); ?>');	
					var objListConteoDeMembresias			 		= JSON.parse('<?php echo json_encode($objListConteoDeMembresias); ?>');	
					var objDataSourceVentasDeContadoMesActual	 	= new Array();
					var objDataIngresosPorMembresias	 			= new Array();
					var objDataSourceProyeccionDeMembresias	 		= new Array();
					var objDataSourceConteoDeMembresias	 			= new Array();
					
					objDataSourceVentasDeContadoMesActual.push(new Array("Dia","Total",{role:'style'}));
					for(var i = 0 ; i < objVentasDeContadoMesActual.length;i++)
					{
						objDataSourceVentasDeContadoMesActual.push(
							new Array(
								objVentasDeContadoMesActual[i].Dia,
								parseInt(objVentasDeContadoMesActual[i].Total),
								getColor(i)
							)
						);	
					}
				
						
					objDataIngresosPorMembresias.push(new Array("Dia","Total"));
					for(var i = 0 ; i < objIngresosPorMembresias.length;i++)
					{
						objDataIngresosPorMembresias.push(
							new Array(
								objIngresosPorMembresias[i].Dia,
								parseInt(objIngresosPorMembresias[i].Total)
							)
						);	
					}


					objDataSourceProyeccionDeMembresias.push(new Array("Mes","Total",{role:'style'}));
					for(var i = 0 ; i < objProyeccionDeMembresias.length;i++)
					{
						objDataSourceProyeccionDeMembresias.push(
							new Array(
								objProyeccionDeMembresias[i].Mes,
								parseInt(objProyeccionDeMembresias[i].Total),
								getColor(i)
							)
						);	
					}

					
					objDataSourceConteoDeMembresias.push(new Array("Mes","Total",{role:'style'}));
					for(var i = 0 ; i < objListConteoDeMembresias.length;i++)
					{
						objDataSourceConteoDeMembresias.push(
							new Array(
								objListConteoDeMembresias[i].Mes,
								parseInt(objListConteoDeMembresias[i].Total),
								getColor(i)
							)
						);	
					}
					
					function getColor(index)
					{
						const colors = ['#006E98', '#00C868', '#ec8f6e', '#f3b49f', '#f6c7b6'];
						return colors[index % colors.length];
					}
					
					
					$(document).ready(function(){
						google.charts.load('current',{packages:['corechart']});							
						google.charts.setOnLoadCallback(drawChartBarraVentasContadoMesActual);		
						google.charts.setOnLoadCallback(drawChartLineaIngresosPorMembresias);		
						google.charts.setOnLoadCallback(drawChartBarraProyectionDeMembresias);		
						google.charts.setOnLoadCallback(drawChartBarraConteoDeMembresias);		
					});		
					
					function drawChartBarraVentasContadoMesActual() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceVentasDeContadoMesActual
						);

						var options = {
						  title: '',
						  seriesType: 'bars',
						  legend:'none'
						};

						var chart = new google.visualization.ColumnChart(document.getElementById('grafico1'));
						chart.draw(data, options);

					}


					function drawChartLineaIngresosPorMembresias() {

						var data = google.visualization.arrayToDataTable(
							objDataIngresosPorMembresias
						);

						var options = {
						title: '',
						colors: ['#006E98', '#00C868', '#ec8f6e', '#f3b49f', '#f6c7b6'],
						};

						var chart = new google.visualization.ComboChart(document.getElementById('grafico2'));
						chart.draw(data, options);

					}


					function drawChartBarraProyectionDeMembresias() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceProyeccionDeMembresias
						);

						var options = {
						title: '',
						legend:'none'
						};

						var chart = new google.visualization.ColumnChart(document.getElementById('grafico3'));
						chart.draw(data, options);

					}


					function drawChartBarraConteoDeMembresias() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceConteoDeMembresias
						);

						var options = {
						title: '',
						seriesType: 'bars',
						legend:'none'
						};

						var chart = new google.visualization.ColumnChart(document.getElementById('grafico4'));
						chart.draw(data, options);

					}
											
				</script>
				