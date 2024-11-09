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
				
				
				<div class="row"  >
					 <div class="col-lg-6">		
						<img class="img-featured" style="width:400px;height:200px" src="<?php echo base_url();?>/resource/img/logos/logo-micro-finanza.jpg">													
					</div>
					<div class="col-lg-6">	
							<div class="panel" style="margin-bottom:20px;">
								<div class="panel-heading">
									<div class="icon"><i class="icon20 i-health"></i></div> 
									<h4>Desembolso Mensual</h4>
									<a href="#" class="minimize"></a>
								</div><!-- End .panel-heading -->
							
								<div class="panel-body">								
									<div id="grafico1" style="height:150px" ></div>
								</div><!-- End .panel-body -->
							</div><!-- End .widget -->		
							
							<div class="panel" style="margin-bottom:20px;">
								<div class="panel-heading">
									<div class="icon"><i class="icon20 i-health"></i></div> 
									<h4>Pagos Mensuales</h4>
									<a href="#" class="minimize"></a>
								</div><!-- End .panel-heading -->
							
								<div class="panel-body">								
									<div id="grafico2" style="height:150px" ></div>
								</div><!-- End .panel-body -->
							</div><!-- End .widget -->		
							
					</div>
				</div>
				
				<div class="row"  >
					 <div class="col-lg-6">		
							<div class="panel" style="margin-bottom:20px;">
								<div class="panel-heading">
									<div class="icon"><i class="icon20 i-health"></i></div> 
									<h4>Intereses Mensuales</h4>
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
									<h4>Capital Mensual</h4>
									<a href="#" class="minimize"></a>
								</div><!-- End .panel-heading -->
							
								<div class="panel-body">								
									<div id="grafico4" style="height:300px" ></div>
								</div><!-- End .panel-body -->
							</div><!-- End .widget -->		
					</div>
				</div>
				
				
				
				<script>	
					//https://www.w3schools.com/js/js_graphics_google_chart.asp
					
					var objCapitalMensual 				 		= JSON.parse('<?php echo json_encode($objListCapitalMensual); ?>');	
					var objInteresMensual			 			= JSON.parse('<?php echo json_encode($objListInteresMensual); ?>');	
					var objDesembolsosMensuales		 			= JSON.parse('<?php echo json_encode($objListDesembolsoMensual); ?>');	
					var objPagosMensuales			 			= JSON.parse('<?php echo json_encode($objListPagoMensual); ?>');	
					var objDataSourceCapitalMensual	 			= new Array();
					var objDataSourceInteresesMensuales	 		= new Array();
					var objDataSourceDesembolsosMensuales	 	= new Array();
					var objDataSourcePagosMensuales	 			= new Array();
					

					objDataSourceDesembolsosMensuales.push(new Array("Mes","Desembolso"));
					for(var i = 0 ; i < objDesembolsosMensuales.length;i++)
					{
						objDataSourceDesembolsosMensuales.push(
							new Array(
								objDesembolsosMensuales[i].Mes,
								parseInt(objDesembolsosMensuales[i].Desembolso)
							)
						);	
					}
					
					
					objDataSourcePagosMensuales.push(new Array("Mes","Pagos"));
					for(var i = 0 ; i < objPagosMensuales.length;i++)
					{
						objDataSourcePagosMensuales.push(
							new Array(
								objPagosMensuales[i].Mes,
								parseInt(objPagosMensuales[i].Pagos)
							)
						);	
					}
					
					
					
					objDataSourceCapitalMensual.push(new Array("Mes","Capital"));
					for(var i = 0 ; i < objCapitalMensual.length;i++)
					{
						objDataSourceCapitalMensual.push(
							new Array(
								objCapitalMensual[i].Mes,
								parseInt(objCapitalMensual[i].Capital)
							)
						);	
					}
					
					objDataSourceInteresesMensuales.push(new Array("Mes","Interes"));
					for(var i = 0 ; i < objInteresMensual.length;i++)
					{
						objDataSourceInteresesMensuales.push(
							new Array(
								objInteresMensual[i].Mes,
								parseInt(objInteresMensual[i].INteres)
							)
						);	
					}
					
					
					
					
					$(document).ready(function(){
						google.charts.load('current',{packages:['corechart']});							
						google.charts.setOnLoadCallback(drawChartBarraDesembolsosMensuales);		
						google.charts.setOnLoadCallback(drawChartBarraPagosMensuales);		
						google.charts.setOnLoadCallback(drawChartBarraInteresMensuales);		
						google.charts.setOnLoadCallback(drawChartBarraCapitalMensual);		
					});		
					
					function drawChartBarraDesembolsosMensuales() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceDesembolsosMensuales
						);

						var options = {
						  title: '',
						  colors: ['#ff8000', '#ff8000', '#ff8000', '#ff8000', '#ff8000'],
						  seriesType: 'bars',
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
						  colors: ['#ff0000', '#ff0000', '#ff0000', '#ff0000', '#ff0000'],
						};

						var chart = new google.visualization.ComboChart(document.getElementById('grafico2'));
						chart.draw(data, options);

					}
					
					function drawChartBarraInteresMensuales() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceInteresesMensuales
						);

						var options = {
						  title: '',
						  colors: ['#00C868', '#006E98', '#ec8f6e', '#f3b49f', '#f6c7b6'],
						  seriesType: 'bars'
						};

						var chart = new google.visualization.ComboChart(document.getElementById('grafico3'));
						chart.draw(data, options);

					}
							
					
					function drawChartBarraCapitalMensual() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceCapitalMensual
						);

						var options = {
						  title: '',
						  colors: ['#006E98', '#00C868', '#ec8f6e', '#f3b49f', '#f6c7b6'],
						  seriesType: 'bars'
						};

						var chart = new google.visualization.ComboChart(document.getElementById('grafico4'));
						chart.draw(data, options);

					}
											
				</script>
				
				