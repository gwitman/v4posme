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
								<h4>Ingresos Diarios del Mes Actual</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
							
							<div class="panel-body">								
								<div id="grafico4" style="height:300px" ></div>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->
					</div>
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
								<h4>Ordenes de Taller por Estados</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
							<div class="panel-body">								
								<div id="grafico2" style="height:300px" ></div>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->	
					</div>
				</div>
				
				<div class="row">
					<div class="panel" style="margin-bottom:20px;">
						<div class="panel-heading">
							<div class="icon"><i class="icon20 i-health"></i></div> 
							<h4>Ordenes Por Colaborador</h4>
							<a href="#" class="minimize"></a>
						</div><!-- End .panel-heading -->
						
						<div class="panel-body">								
							<div id="grafico1" style="height:300px" ></div>
						</div><!-- End .panel-body -->
					</div><!-- End .widget -->
				</div>
				
				
				
				<script>	
					//https://www.w3schools.com/js/js_graphics_google_chart.asp
					
					var objStatusTaller						 = JSON.parse('<?php echo json_encode(array_unique(array_map(function($elem){ return $elem->firstName; }, $objListOrdenesPorEmpleado )));  ?>');
					var objTransactionMasterOrdenes			 = JSON.parse('<?php echo json_encode($objListOrdenesPorEmpleado); ?>');	
					var objTransactionMasterEstado			 = JSON.parse('<?php echo json_encode($objListEstado); ?>');	
					var objTransactionMasterMensuales		 = JSON.parse('<?php echo json_encode($objListVentaMensual); ?>');	
					var objTransactionMasterDiarias			 = JSON.parse('<?php echo json_encode($objListVentaDiaria); ?>');	
					var objDataSourceOrdenesPorEmpleado	 	 = new Array();
					var objDataSourceEstadoMasCantidad		 = new Array();
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
					
					
					
					//Obtener el listado de columnas diferentes
					const empleados = {};
					var arrayField  = new Array();
					arrayField.push("Colaborador");
					for(var i = 0 ; i < objStatusTaller.length;i++)
					{
						arrayField.push(objStatusTaller[i]);
					}
					objDataSourceOrdenesPorEmpleado.push(arrayField);
					
					
					//Darle fomrato e inicialiar los variable empleado
					for (let i = 0; i < objTransactionMasterOrdenes.length; i++) {
						const employeeName 	= objTransactionMasterOrdenes[i].employee;
						const status 		= objTransactionMasterOrdenes[i].firstName;
						const amount 		= parseInt(objTransactionMasterOrdenes[i].amount);

						// Inicializar el objeto de cada empleado si aún no existe
						if (!empleados[employeeName]) {
							
							for(var izz = 0
							/*
							empleados[employeeName] = Object.fromEntries(
								objStatusTaller.map(element => [ element, 0 ])
						    );
							*/
						}

						empleados[employeeName][status] = amount;
					}

					//Obtener los datos y armar el datasource
					for (let empleado in empleados) 
					{	
						var arrayValue = new Array();
						arrayValue.push(empleado);
						for(var att in empleados[empleado])
						{
							arrayValue.push(empleados[empleado][att]);
						}
						
						objDataSourceOrdenesPorEmpleado.push(arrayValue);
					}
										
					
					
					
					
					objDataSourceEstadoMasCantidad.push(new Array("Estado","Cantidad"));
					for(var i = 0 ; i < objTransactionMasterEstado.length;i++)
					{
						objDataSourceEstadoMasCantidad.push(
							new Array(
								objTransactionMasterEstado[i].firstName,
								parseInt(objTransactionMasterEstado[i].amount)
							)
						);	
					}
					
					
					
					
					$(document).ready(function(){
						google.charts.load('current',{packages:['corechart']});							
						google.charts.setOnLoadCallback(drawChartBarraHorizontalOrdenesPorEmpleado);		
						google.charts.setOnLoadCallback(drawChartBarraHorizontalEstadoMasCantidad);		
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
					
					function drawChartBarraHorizontalEstadoMasCantidad() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceEstadoMasCantidad
						);

						var options = {
						  title: 'Estado vs Cantidad',
						  colors: ['#00C868', '#006E98', '#ec8f6e', '#f3b49f', '#f6c7b6'],
						  seriesType: 'bars',
						};

						var chart = new google.visualization.ComboChart(document.getElementById('grafico2'));
						chart.draw(data, options);

					}
							
					
					function drawChartBarraHorizontalOrdenesPorEmpleado() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceOrdenesPorEmpleado
						);

						var options = {
						  title: 'Ordenes por Estado de Colaborador',
						  colors: ['#006E98', '#00C868', '#ec8f6e', '#f3b49f', '#f6c7b6'],
						  seriesType: 'bars'
						};

						var chart = new google.visualization.ComboChart(document.getElementById('grafico1'));
						chart.draw(data, options);

					}
											
				</script>
				
				