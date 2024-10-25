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
									<h4>Ordenes Terminadas</h4>
									<a href="#" class="minimize"></a>
								</div><!-- End .panel-heading -->
							
								<div class="panel-body">								
									<div id="grafico1" style="height:150px" ></div>
								</div><!-- End .panel-body -->
							</div><!-- End .widget -->		
							
							<div class="panel" style="margin-bottom:20px;">
								<div class="panel-heading">
									<div class="icon"><i class="icon20 i-health"></i></div> 
									<h4>Desembolsos (Prestamos)</h4>
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
									<h4>Ingresos Mensuales</h4>
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
									<h4>Ingresos Diarios</h4>
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
					
					var objTransactionMaster 				 = JSON.parse('<?php echo json_encode($objListVentas); ?>');	
					var objTransactionMasterTecnico			 = JSON.parse('<?php echo json_encode($objListTecnico); ?>');	
					var objTransactionMasterMensuales		 = JSON.parse('<?php echo json_encode($objListVentaMensual); ?>');	
					var objTransactionMasterDiarias			 = JSON.parse('<?php echo json_encode($objListVentaDiaria); ?>');	
					var objDataSourceProductosMasVendidos	 = new Array();
					var objDataSourceProductosMasTenicos	 = new Array();
					var objDataSourceProductosMasMensuales	 = new Array();
					var objDataSourceProductosMasDiarias	 = new Array();
					
					//Obtener los ultimos 10 elementos					
					objDataSourceProductosMasMensuales.push(new Array("Mes","Venta"));
					for(var i = 0 ; i < objTransactionMasterMensuales.length;i++)
					{
						objDataSourceProductosMasMensuales.push(
							new Array(
								objTransactionMasterMensuales[i].firtsName,
								parseInt(objTransactionMasterMensuales[i].monto)
							)
						);	
					}
					
					
					//Obtener los ultimos 10 elementos					
					objDataSourceProductosMasDiarias.push(new Array("Dia","Venta"));
					for(var i = 0 ; i < objTransactionMasterDiarias.length;i++)
					{
						objDataSourceProductosMasDiarias.push(
							new Array(
								objTransactionMasterDiarias[i].firtsName,
								parseInt(objTransactionMasterDiarias[i].monto)
							)
						);	
					}
					
					
					
					//Obtener los ultimos 10 elementos					
					objDataSourceProductosMasVendidos.push(new Array("Colaborador","Venta"));
					for(var i = 0 ; i < objTransactionMaster.length;i++)
					{
						objDataSourceProductosMasVendidos.push(
							new Array(
								objTransactionMaster[i].firtsName,
								parseInt(objTransactionMaster[i].monto)
							)
						);	
					}
					
					//Obtener los ultimos 10 elementos					
					objDataSourceProductosMasTenicos.push(new Array("Colaborador","Venta"));
					for(var i = 0 ; i < objTransactionMasterTecnico.length;i++)
					{
						objDataSourceProductosMasTenicos.push(
							new Array(
								objTransactionMasterTecnico[i].firtsName,
								parseInt(objTransactionMasterTecnico[i].monto)
							)
						);	
					}
					
					
					
					
					$(document).ready(function(){
						google.charts.load('current',{packages:['corechart']});							
						google.charts.setOnLoadCallback(drawChartBarraHorizontalProductosMasVendidos);		
						google.charts.setOnLoadCallback(drawChartBarraHorizontalProductosMasTenicos);		
						google.charts.setOnLoadCallback(drawChartBarraHorizontalProductosMasMensuales);		
						google.charts.setOnLoadCallback(drawChartBarraHorizontalProductosMasDiarias);		
					});		
					
					function drawChartBarraHorizontalProductosMasMensuales() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceProductosMasMensuales
						);

						var options = {
						  title: 'Ingresos Mensuales',
						  colors: ['#ff8000', '#ff8000', '#ff8000', '#ff8000', '#ff8000'],
						  seriesType: 'bars',
						};

						var chart = new google.visualization.ComboChart(document.getElementById('grafico3'));
						chart.draw(data, options);

					}
					
					function drawChartBarraHorizontalProductosMasDiarias() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceProductosMasDiarias
						);

						var options = {
						  title: 'Ingresos Diarios',
						  colors: ['#ff0000', '#ff0000', '#ff0000', '#ff0000', '#ff0000'],
						};

						var chart = new google.visualization.ComboChart(document.getElementById('grafico4'));
						chart.draw(data, options);

					}
					
					function drawChartBarraHorizontalProductosMasTenicos() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceProductosMasTenicos
						);

						var options = {
						  title: 'Colaboradores vs Orden en Proceso',
						  colors: ['#00C868', '#006E98', '#ec8f6e', '#f3b49f', '#f6c7b6'],
						};

						var chart = new google.visualization.BarChart(document.getElementById('grafico2'));
						chart.draw(data, options);

					}
							
					
					function drawChartBarraHorizontalProductosMasVendidos() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceProductosMasVendidos
						);

						var options = {
						  title: 'Colaboradores vs Orden Terminada',
						  colors: ['#006E98', '#00C868', '#ec8f6e', '#f3b49f', '#f6c7b6'],
						};

						var chart = new google.visualization.BarChart(document.getElementById('grafico1'));
						chart.draw(data, options);

					}
											
				</script>
				
				