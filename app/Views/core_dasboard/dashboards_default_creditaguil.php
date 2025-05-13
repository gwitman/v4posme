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
				<img class="img-featured" style="width:400px;height:200px" src="<?php echo base_url();?>/resource/img/logos/logo-micro-finanza.jpg">
			</div><!-- End .panel-body -->
		</div><!-- End .widget -->		
	</div>
	<div class="col-lg-6">	
		
		<div class="panel <?php echo getBehavio($company->type,"core_dashboards","divPanelSoporteTenico","hidden"); ?> " >
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
</div>

<div class="row">
	 <div class="col-lg-12">	
		<div class="panel" style="margin-bottom:20px;">
			<div class="panel-heading">
				<div class="icon"><i class="icon20 i-health"></i></div> 
				<h4> Estadistica de Credito</h4>
				<a href="#" class="minimize"></a>
			</div><!-- End .panel-heading -->
			<div class="panel-body">								
				<div id="grafico1" style="height:350px" ></div>
			</div><!-- End .panel-body -->
		</div><!-- End .widget -->	
	</div>
</div>

<script>	
	// https://www.w3schools.com/js/js_graphics_google_chart.asp					
	$(document).ready(function(){
		google.charts.load('current',{packages:['corechart']});							
		google.charts.setOnLoadCallback(drawChartStadisticaCredito);			
	});		
	
	function drawChartStadisticaCredito() {
		const dataObj  = JSON.parse('<?php echo json_encode($objListSummaryCredit); ?>');	

		 // Estructura de columnas para el gráfico
        const dataArray = [
          ['Usuario', 'Clientes', 'Créditos', 'Cancelados', 'Nuevos', 'Recuperados']
        ];

        dataObj.forEach(item => {
          dataArray.push([
            item.nickname,
            parseInt(item.countCustomer),
            parseInt(item.countCredit),
            parseInt(item.countCustomerCancel),
            parseInt(item.countCustomerNew),
            parseInt(item.countCustomerRecuperation)
          ]);
        });

        const data = google.visualization.arrayToDataTable(dataArray);

        const options = {
          title: 'Resumen de Clientes y Créditos por Usuario',
          isStacked: true,
          hAxis: {
            title: 'Total por categoría',
            minValue: 0
          },
          vAxis: {
            title: 'Usuario'
          },
          chartArea: {width: '70%'}
        };

        const chart = new google.visualization.BarChart(document.getElementById('grafico1'));
        chart.draw(data, options);
		
		
	}
</script>
