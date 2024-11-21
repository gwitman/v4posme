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
								<h4>Facturacion de Contado(Mes Actual)</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
							
							<div class="panel-body">								
								<div id="grafico4" style="height:300px" ></div>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->	

						<div class="panel" style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-health"></i></div> 
								<h4>Cartera de Cobro</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
							
							<div class="panel-body">								
								<div id="grafico3" style="height:300px" ></div>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->	
						
					</div>

					<div class="col-lg-6">		
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
					</div>

					<div class="col-lg-6">		
						<div class="panel" style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-health"></i></div> 
								<h4>Servicios Contratados</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
							
							<div class="panel-body">								
								<div id="grafico1" style="height:300px" ></div>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->
						
						<div class="panel" style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-health"></i></div> 
								<h4>Pagos Realizados</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
							
							<div class="panel-body">								
								<div id="grafico2" style="height:300px" ></div>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->
					</div>
				</div>


				<script>	
					// https://www.w3schools.com/js/js_graphics_google_chart.asp
					
					var objServiciosContratados 				 	= JSON.parse('<?php echo json_encode($objListServiciosContratados); ?>');	
					var objPagosMensualesRealizados			 		= JSON.parse('<?php echo json_encode($objListPagosMensualesRealizados); ?>');	
					var objCarteraDeCobro		 					= JSON.parse('<?php echo json_encode($objListCarteraDeCobro); ?>');	
					var objVentaFacturacionContado			 		= JSON.parse('<?php echo json_encode($objListFacturacionContado); ?>');	
					var objDataServiciosContratados	 				= new Array();
					var objDataSourcePagosMensuales	 				= new Array();
					var objDataSourceCarteraDeCobro	 				= new Array();
					var objDataSourceFacturacionContado	 			= new Array();
					
					
					objDataServiciosContratados.push(new Array("Mes","Total"));
					for(var i = 0 ; i < objServiciosContratados.length;i++)
					{
						objDataServiciosContratados.push(
							new Array(
								objServiciosContratados[i].Mes,
								parseInt(objServiciosContratados[i].Total)
							)
						);	
					}

					objDataSourcePagosMensuales.push(new Array("Mes","Total"));
					for(var i = 0 ; i < objPagosMensualesRealizados.length;i++)
					{
						objDataSourcePagosMensuales.push(
							new Array(
								objPagosMensualesRealizados[i].Mes,
								parseInt(objPagosMensualesRealizados[i].Total)							
							)
						);	
					}


					objDataSourceCarteraDeCobro.push(new Array("Mes","Total"));
					for(var i = 0 ; i < objCarteraDeCobro.length;i++)
					{
						objDataSourceCarteraDeCobro.push(
							new Array(
								objCarteraDeCobro[i].Mes,
								parseInt(objCarteraDeCobro[i].Total)
							)
						);	
					}
					
					
					objDataSourceFacturacionContado.push(new Array("Dia","Total"));
					for(var i = 0 ; i < objVentaFacturacionContado.length;i++)
					{
						objDataSourceFacturacionContado.push(
							new Array(
								objVentaFacturacionContado[i].Dia,
								parseInt(objVentaFacturacionContado[i].Total)
							)
						);	
					}
					
					
					
					$(document).ready(function(){
						google.charts.load('current',{packages:['corechart']});							
						google.charts.setOnLoadCallback(drawChartLineaCarteraDeCobro);		
						google.charts.setOnLoadCallback(drawChartLineaFacturacionContado);		
						google.charts.setOnLoadCallback(drawChartBarraPagosMensuales);		
						google.charts.setOnLoadCallback(drawChartBarraServiciosContratados);		
					});		
					
					function drawChartBarraServiciosContratados() {

						var data = google.visualization.arrayToDataTable(
							objDataServiciosContratados
						);

						var options = {
						title: '',
						colors: ['#006E98', '#00C868', '#ec8f6e', '#f3b49f', '#f6c7b6'],
						seriesType: 'bars'						
					};

						var chart = new google.visualization.ComboChart(document.getElementById('grafico1'));
						chart.draw(data, options);
					}
					
					function drawChartBarraPagosMensuales() {

						var data = google.visualization.arrayToDataTable(
							objDataSourcePagosMensuales
						);

						var options = {
						title: '',
						colors: ['#00C868', '#006E98', '#ec8f6e', '#f3b49f', '#f6c7b6'],
						seriesType: 'bars'
						};

						var chart = new google.visualization.ComboChart(document.getElementById('grafico2'));
						chart.draw(data, options);
					}

					function drawChartLineaCarteraDeCobro() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceCarteraDeCobro
						);

						var options = {
						title: '',
						colors: ['#006E98', '#00C868', '#ec8f6e', '#f3b49f', '#f6c7b6']
						};

						var chart = new google.visualization.ComboChart(document.getElementById('grafico3'));
						chart.draw(data, options);
					}

					function drawChartLineaFacturacionContado() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceFacturacionContado
						);

						var options = {
						  title: '',
						  colors: ['#00C868', '#006E98', '#ec8f6e', '#f3b49f', '#f6c7b6']						
						};

						var chart = new google.visualization.ComboChart(document.getElementById('grafico4'));
						chart.draw(data, options);

					}

					
											
				</script>