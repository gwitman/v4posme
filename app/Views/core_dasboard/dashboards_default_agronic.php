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
						</div>
						<div class="panel-body">
							<img class="img-featured" style="width:300px;height:200px" src="<?php echo base_url();?>/resource/img/logos/logo-micro-finanza.jpg">
						</div>
					</div>
					
					<div class="panel" style="margin-bottom:20px;">
						<div class="panel-heading">
							<div class="icon"><i class="icon20 i-health"></i></div> 
							<h4>Ventas diarias (Mes actual)</h4>
							<a href="#" class="minimize"></a>
						</div>
						<div class="panel-body">								
							<div id="grafico1" style="height:150px"></div>
						</div>
					</div>
					
					<div class="panel" style="margin-bottom:20px;">
						<div class="panel-heading">
							<div class="icon"><i class="icon20 i-health"></i></div> 
							<h4>Ventas de contado por mes (Año actual)</h4>
							<a href="#" class="minimize"></a>
						</div>
						<div class="panel-body">								
							<div id="grafico3" style="height:300px"></div>
						</div>
					</div>

					<div class="panel" style="margin-bottom:20px;">
						<div class="panel-heading">
							<div class="icon"><i class="icon20 i-health"></i></div> 
							<h4>Ventas de contado año anterior (<?php echo intval(date('Y')) - 1; ?>)</h4>
							<a href="#" class="minimize"></a>
						</div>
						<div class="panel-body">								
							<div id="grafico5" style="height:300px"></div>
						</div>
					</div>
				</div>

				<div class="col-lg-6">	
					<div class="panel" style="margin-bottom:20px;">
						<div class="panel-heading">
							<div class="icon"><i class="icon20 i-health"></i></div> 
							<h4>Ventas por usuario (Mes actual)</h4>
							<a href="#" class="minimize"></a>
						</div>
						<div class="panel-body">								
							<div id="grafico2" style="height:300px"></div>
						</div>
					</div>
					
					<div class="panel" style="margin-bottom:20px;">
						<div class="panel-heading">
							<div class="icon"><i class="icon20 i-health"></i></div> 
							<h4>Pagos por dia (Mes actual)</h4>
							<a href="#" class="minimize"></a>
						</div>
						<div class="panel-body">								
							<div id="grafico4" style="height:150px"></div>
						</div>
					</div>
					
					<div class="panel" style="margin-bottom:20px;">
						<div class="panel-heading">
							<div class="icon"><i class="icon20 i-quotes-left"></i></div> 
							<h4>Tipo de cambio</h4>
							<a href="#" class="minimize"></a>
						</div>
						<div class="panel-body">
							<blockquote>
								<p class="text-success">VALOR: <?php echo number_format($objExchangeRateCordobaDolar,2); ?></p>									
								<small>Dolar a Cordoba</small>
							</blockquote>
							<blockquote>
								<p class="text-danger">VALOR: <?php echo number_format($objExchangeRateDolarACordoba,4); ?></p>									
								<small>Cordoba al Dolar</small>
							</blockquote>
						</div>
					</div>
					
					<div class="panel">
						<div class="panel-heading">
							<div class="icon"><i class="icon20 i-quotes-left"></i></div> 
							<h4>Informacion de contacto</h4>
							<a href="#" class="minimize"></a>
						</div>
						<div class="panel-body">
							<blockquote>
								<p>Soporte Tenico: 8712-5827</p>									
								<small>posMe</small>
							</blockquote>
							<a aria-label="Chat on WhatsApp" target="_blank" href="https://wa.me/50587125827?text=Buenos dias le saluda <?php echo $user->email; ?> : "> 
								<img alt="Chat on WhatsApp" src="<?php echo base_url();?>/resource/img/logos/WhatsAppButtonGreenSmall.svg" /> 
							</a>
						</div>
					</div>
				</div>
			</div>

			<script>	
				var objPagosMensuales 						= JSON.parse('<?php echo json_encode($objPagosMensuales); ?>');	
				var objVentasCreditoMensuales				= JSON.parse('<?php echo json_encode($objListVentasCreditoMensuales); ?>');	
				var objVentasDeContadoMesActual				= JSON.parse('<?php echo json_encode($objListVentasContadoMesActual); ?>');	
				var objVentaContadoMensuales				= JSON.parse('<?php echo json_encode($objListVentaContadoMensuales); ?>');	
				var objVentaContadoAnnioAnterior			= JSON.parse('<?php echo json_encode($objListVentaContadoAnnioAnterior); ?>');	

				var objDataPagosMensuales					= new Array();
				var objDataSourceVentasCreditoMensuales		= new Array();
				var objDataSourceVentasDeContadoMesActual	= new Array();
				var objDataSourceVentasContadoMensuales		= new Array();
				var objDataSourceVentasAnnioAnterior		= new Array();
				
				// Ventas diarias mes actual
				objDataSourceVentasDeContadoMesActual.push(new Array("Dia","Ventas"));
				for(var i = 0; i < objVentasDeContadoMesActual.length; i++) {
					objDataSourceVentasDeContadoMesActual.push(
						new Array(
							objVentasDeContadoMesActual[i].Dia,
							parseInt(objVentasDeContadoMesActual[i].Venta)
						)
					);
				}
				
				// Ventas contado mensuales año actual
				objDataSourceVentasContadoMensuales.push(new Array("Mes","Ventas"));
				for(var i = 0; i < objVentaContadoMensuales.length; i++) {
					objDataSourceVentasContadoMensuales.push(
						new Array(
							objVentaContadoMensuales[i].Mes,
							parseInt(objVentaContadoMensuales[i].Venta)
						)
					);
				}
				
				// Pagos mensuales
				objDataPagosMensuales.push(new Array("Mes","Pagos"));
				for(var i = 0; i < objPagosMensuales.length; i++) {
					objDataPagosMensuales.push(
						new Array(
							objPagosMensuales[i].Mes,
							parseInt(objPagosMensuales[i].Pagos)
						)
					);
				}
				
				// Ventas credito mensuales
				objDataSourceVentasCreditoMensuales.push(new Array("Mes","Ventas"));
				for(var i = 0; i < objVentasCreditoMensuales.length; i++) {
					objDataSourceVentasCreditoMensuales.push(
						new Array(
							objVentasCreditoMensuales[i].Mes,
							parseInt(objVentasCreditoMensuales[i].Venta)
						)
					);
				}

				// Ventas contado año anterior
				objDataSourceVentasAnnioAnterior.push(new Array("Mes","Ventas"));
				for(var i = 0; i < objVentaContadoAnnioAnterior.length; i++) {
					objDataSourceVentasAnnioAnterior.push(
						new Array(
							objVentaContadoAnnioAnterior[i].Mes,
							parseInt(objVentaContadoAnnioAnterior[i].Venta)
						)
					);
				}
				
				$(document).ready(function(){
					google.charts.load('current',{packages:['corechart']});							
					google.charts.setOnLoadCallback(drawChartBarraVentasContadoMesActual);		
					google.charts.setOnLoadCallback(drawChartPastelVentasContadoMensuales);		
					google.charts.setOnLoadCallback(drawChartPastelVentasCreditoMensuales);		
					google.charts.setOnLoadCallback(drawChartBarraCapitalMensual);		
					google.charts.setOnLoadCallback(drawChartVentasAnnioAnterior);		
				});		
				
				function drawChartBarraVentasContadoMesActual() {
					var data = google.visualization.arrayToDataTable(objDataSourceVentasDeContadoMesActual);
					var options = {
					  title: '',
					  colors: ['#ff8000'],
					  seriesType: 'bars',
					};
					var chart = new google.visualization.ComboChart(document.getElementById('grafico1'));
					chart.draw(data, options);
				}
				
				function drawChartPastelVentasContadoMensuales() {
					var data = google.visualization.arrayToDataTable(objDataSourceVentasContadoMensuales);
					var options = {
					  title: '',
					  colors: ['#00C868', '#006E98', '#ec8f6e', '#f3b49f', '#f6c7b6'],
					};
					var chart = new google.visualization.PieChart(document.getElementById('grafico2'));
					chart.draw(data, options);
				}

				function drawChartPastelVentasCreditoMensuales() {
					var data = google.visualization.arrayToDataTable(objDataSourceVentasCreditoMensuales);
					var options = {
					  title: '',
					  colors: ['#00C868', '#006E98', '#ec8f6e', '#f3b49f', '#f6c7b6'],
					  seriesType: 'bars'
					};
					var chart = new google.visualization.ComboChart(document.getElementById('grafico3'));
					chart.draw(data, options);
				}
				
				function drawChartBarraCapitalMensual() {
					var data = google.visualization.arrayToDataTable(objDataPagosMensuales);
					var options = {
					  title: '',
					  colors: ['#006E98', '#00C868', '#ec8f6e', '#f3b49f', '#f6c7b6'],
					};
					var chart = new google.visualization.ComboChart(document.getElementById('grafico4'));
					chart.draw(data, options);
				}

				function drawChartVentasAnnioAnterior() {
					var paletteAnterior = ['#7E57C2','#5C6BC0','#42A5F5','#26C6DA','#66BB6A','#EF5350','#FFA726','#EC407A','#AB47BC','#26A69A','#D4E157','#FF7043'];

					var rawData = new google.visualization.DataTable();
					rawData.addColumn('string', 'Mes');
					rawData.addColumn('number', 'Ventas');
					rawData.addColumn({type:'string', role:'style'});

					for (var i = 1; i < objDataSourceVentasAnnioAnterior.length; i++) {
						var color = paletteAnterior[(i - 1) % paletteAnterior.length];
						rawData.addRow([
							String(objDataSourceVentasAnnioAnterior[i][0]),
							Number(objDataSourceVentasAnnioAnterior[i][1]),
							'color: ' + color
						]);
					}

					var options = {
					  title: '',
					  seriesType: 'bars',
					  legend: { position: 'none' },
					};
					var chart = new google.visualization.ComboChart(document.getElementById('grafico5'));
					chart.draw(rawData, options);
				}
			</script>
