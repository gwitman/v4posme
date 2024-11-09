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
						<img class="img-featured" style="width:400px;height:200px" src="<?php echo base_url();?>/resource/img/logos/logo-micro-finanza.png">													
					</div>
					<div class="col-lg-6">	
							<div class="panel" style="margin-bottom:20px;">
								<div class="panel-heading">
									<div class="icon"><i class="icon20 i-health"></i></div> 
									<h4>Ingresos Mensuales de Ventas de Contado</h4>
									<a href="#" class="minimize"></a>
								</div><!-- End .panel-heading -->
							
								<div class="panel-body">								
									<div id="grafico1" style="height:150px" ></div>
								</div><!-- End .panel-body -->
							</div><!-- End .widget -->		
							
							<div class="panel" style="margin-bottom:20px;">
								<div class="panel-heading">
									<div class="icon"><i class="icon20 i-health"></i></div> 
									<h4>Ingresos por Matriculas Mensual</h4>
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
									<h4>Estudiantes Por Municipio</h4>
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
									<h4>Estudiantes por Sexo</h4>
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
					
					var objStudentsByCity 				 		= JSON.parse('<?php echo json_encode($objListStudentsByCity); ?>');	
					var objStudentsBySex			 			= JSON.parse('<?php echo json_encode($objListStudentsBySex); ?>');	
					var objMonthlyCashSales		 				= JSON.parse('<?php echo json_encode($objListMonthlyCashSales); ?>');	
					var objInscriptionsEarnings					= JSON.parse('<?php echo json_encode($objListInscriptionsEarnings); ?>');	
					var objDataSourceStudentsByCity	 			= new Array();
					var objDataSourceStudentsBySex	 			= new Array();
					var objDataSourceMonthlyCashSales	 		= new Array();
					var objDataSourceInscriptionsEarnings	 	= new Array();
					
					//Obtener los ultimos 10 elementos					
					objDataSourceMonthlyCashSales.push(new Array("Mes","Ingresos"));
					for(var i = 0 ; i < objMonthlyCashSales.length;i++)
					{
						objDataSourceMonthlyCashSales.push(
							new Array(
								objMonthlyCashSales[i].mes,	
								parseInt(objMonthlyCashSales[i].monto)
							)
						);	
					}
					
					
					//Obtener los ultimos 10 elementos					
					objDataSourceInscriptionsEarnings.push(new Array("Dia","Ingresos"));
					for(var i = 0 ; i < objInscriptionsEarnings.length;i++)
					{
						objDataSourceInscriptionsEarnings.push(
							new Array(
								objInscriptionsEarnings[i].dia,
								parseInt(objInscriptionsEarnings[i].ingreso)
							)
						);	
					}
					
					
					
					//Obtener los ultimos 10 elementos					
					objDataSourceStudentsByCity.push(new Array("Municipios","Estudiantes"));
					for(var i = 0 ; i < objStudentsByCity.length;i++)
					{
						objDataSourceStudentsByCity.push(
							new Array(
								objStudentsByCity[i].municipio,
								parseInt(objStudentsByCity[i].cantidad)
							)
						);	
					}
					
					//Obtener los ultimos 10 elementos					
					objDataSourceStudentsBySex.push(new Array("Sexo","Cantidad"));
					for(var i = 0 ; i < objStudentsBySex.length;i++)
					{
						objDataSourceStudentsBySex.push(
							new Array(
								objStudentsBySex[i].sexo,
								parseInt(objStudentsBySex[i].cantidad)
							)
						);	
					}
					
					
					
					
					$(document).ready(function(){
						google.charts.load('current',{packages:['corechart']});							
						google.charts.setOnLoadCallback(drawChartPastelEstudiantesPorMunicipio);		
						google.charts.setOnLoadCallback(drawChartPastelEstudiantesPorSexo);		
						google.charts.setOnLoadCallback(drawChartBarrasIngresosPorVentasDeContado);		
						google.charts.setOnLoadCallback(drawChartIngresosPorMatriculasMensual);		
					});		
					
					function drawChartBarrasIngresosPorVentasDeContado() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceMonthlyCashSales
						);

						var options = {
						  title: '',
						  colors: ['#ff8000', '#ff8000', '#ff8000', '#ff8000', '#ff8000'],
						  seriesType: 'bars',
						};

						var chart = new google.visualization.ComboChart(document.getElementById('grafico1'));
						chart.draw(data, options);

					}
					
					function drawChartIngresosPorMatriculasMensual() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceInscriptionsEarnings
						);

						var options = {
						  title: '',
						  colors: ['#ff0000', '#ff0000', '#ff0000', '#ff0000', '#ff0000'],
						};

						var chart = new google.visualization.ComboChart(document.getElementById('grafico2'));
						chart.draw(data, options);

					}
					
					function drawChartPastelEstudiantesPorSexo() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceStudentsBySex
						);

						var options = {
						  title: '',
						  colors: ['#00C868', '#006E98', '#ec8f6e', '#f3b49f', '#f6c7b6'],
						};

						var chart = new google.visualization.PieChart(document.getElementById('grafico4'));
						chart.draw(data, options);

					}
					
					
					function drawChartPastelEstudiantesPorMunicipio() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceStudentsByCity
						);

						var options = {
						  title: '',
						  colors: ['#006E98', '#00C868', '#ec8f6e', '#f3b49f', '#f6c7b6'],
						};
						
						var chart = new google.visualization.PieChart(document.getElementById('grafico3'));
						chart.draw(data, options);

					}
											
				</script>
				
				