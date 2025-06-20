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

<div class="table-responsive">
	<table class="table table-bordered table-hover table-striped align-middle">
	  <thead class="table-dark text-center">
		<tr>
		  <th>Usuario</th>
		  <th>No. Cli</th>
		  <th>No. Cre</th>
		  <th>No. Cli Can A</th>
		  <th>No. Cli Can H</th>
		  <th>No. Cli Nue</th>
		  <th>No. Cli Rec</th>
		  <th>Cap + Int (C$)</th>
		  <th>Cap (C$)</th>
		</tr>
	  </thead>
	  <tbody>
		<?php
		$data = $objListSummaryCredit;
		?>
		<?php foreach ($data as $row): ?>
		  <tr>
			<td class="fw-bold text-primary"><?= htmlspecialchars($row['nickname']) ?></td>
			<td class="text-end"><?= number_format($row['countCustomer']) ?></td>
			<td class="text-end"><?= number_format($row['countCredit']) ?></td>
			<td class="text-end text-danger"><?= number_format($row['countCustomerAcumulados']) ?></td>
			<td class="text-end text-danger"><?= number_format($row['countCustomerCancel']) ?></td>
			<td class="text-end text-success"><?= number_format($row['countCustomerNew']) ?></td>
			<td class="text-end text-success"><?= number_format($row['countCustomerRecuperation']) ?></td>
			<td class="text-end text-info fw-semibold">C$ <?= number_format($row['amountCartera'], 2) ?></td>
			<td class="text-end text-info fw-semibold">C$ <?= number_format($row['amountCapital'], 2) ?></td>
		  </tr>
		<?php endforeach; ?>
	  </tbody>
	</table>
</div>

<br/>
<br/>

<div class="row">
	 <div class="col-lg-12">	
		<div class="panel" style="margin-bottom:20px;">
			<div class="panel-heading">
				<div class="icon"><i class="icon20 i-health"></i></div> 
				<h4> No. Clientes</h4>
				<a href="#" class="minimize"></a>
			</div><!-- End .panel-heading -->
			<div class="panel-body">								
				<div id="grafico1" style="height:350px" ></div>
			</div><!-- End .panel-body -->
		</div><!-- End .widget -->	
	</div>
</div>

<div class="row">
	 <div class="col-lg-12">	
		<div class="panel" style="margin-bottom:20px;">
			<div class="panel-heading">
				<div class="icon"><i class="icon20 i-health"></i></div> 
				<h4> No. Creditos Activos</h4>
				<a href="#" class="minimize"></a>
			</div><!-- End .panel-heading -->
			<div class="panel-body">								
				<div id="grafico2" style="height:350px" ></div>
			</div><!-- End .panel-body -->
		</div><!-- End .widget -->	
	</div>
</div>

<div class="row">
	 <div class="col-lg-12">	
		<div class="panel" style="margin-bottom:20px;">
			<div class="panel-heading">
				<div class="icon"><i class="icon20 i-health"></i></div> 
				<h4> No. Creditos Cancelados</h4>
				<a href="#" class="minimize"></a>
			</div><!-- End .panel-heading -->
			<div class="panel-body">								
				<div id="grafico3" style="height:350px" ></div>
			</div><!-- End .panel-body -->
		</div><!-- End .widget -->	
	</div>
</div>

<div class="row">
	 <div class="col-lg-12">	
		<div class="panel" style="margin-bottom:20px;">
			<div class="panel-heading">
				<div class="icon"><i class="icon20 i-health"></i></div> 
				<h4> No. Creditos Nuevos</h4>
				<a href="#" class="minimize"></a>
			</div><!-- End .panel-heading -->
			<div class="panel-body">								
				<div id="grafico4" style="height:350px" ></div>
			</div><!-- End .panel-body -->
		</div><!-- End .widget -->	
	</div>
</div>

<div class="row">
	 <div class="col-lg-12">	
		<div class="panel" style="margin-bottom:20px;">
			<div class="panel-heading">
				<div class="icon"><i class="icon20 i-health"></i></div> 
				<h4> No. Creditos Recuperados</h4>
				<a href="#" class="minimize"></a>
			</div><!-- End .panel-heading -->
			<div class="panel-body">								
				<div id="grafico5" style="height:350px" ></div>
			</div><!-- End .panel-body -->
		</div><!-- End .widget -->	
	</div>
</div>

<div class="row">
	 <div class="col-lg-12">	
		<div class="panel" style="margin-bottom:20px;">
			<div class="panel-heading">
				<div class="icon"><i class="icon20 i-health"></i></div> 
				<h4> Valor de Cartera</h4>
				<a href="#" class="minimize"></a>
			</div><!-- End .panel-heading -->
			<div class="panel-body">								
				<div id="grafico6" style="height:350px" ></div>
			</div><!-- End .panel-body -->
		</div><!-- End .widget -->	
	</div>
</div>



<script>	
	// https://www.w3schools.com/js/js_graphics_google_chart.asp					
	$(document).ready(function(){
		google.charts.load('current',{packages:['corechart']});							
		google.charts.setOnLoadCallback(drawClientes);			
		google.charts.setOnLoadCallback(drawCreditosActivos);	
		google.charts.setOnLoadCallback(drawMontoDesembolso);	
		google.charts.setOnLoadCallback(drawClientesCancelados);	
		google.charts.setOnLoadCallback(drawClientesNuevos);	
		google.charts.setOnLoadCallback(drawClientesRecuperados);	
	});		
	
	function drawClientesRecuperados() {
		const dataObj  = JSON.parse('<?php echo json_encode($objListSummaryCredit); ?>');	

		 // Estructura de columnas para el gráfico
        const dataArray = [
          ['Usuario', 'No. CLI REC']
        ];

        dataObj.forEach(item => {
          dataArray.push([
            item.nickname,
            parseInt(item.countCustomerRecuperation)
          ]);
        });

        const data = google.visualization.arrayToDataTable(dataArray);

        const options = {
          title: 'Clientes recuperados',
          isStacked: true,
          hAxis: {
            title: 'Total por categoría',
            minValue: 0
          },
          vAxis: {
            title: 'Usuario'
          },
          chartArea: {width: '70%'},
		  colors: ['#3cb44b']
        };

        const chart = new google.visualization.BarChart(document.getElementById('grafico5'));
        chart.draw(data, options);
		
		
	}
	
	
	function drawClientesNuevos() {
		const dataObj  = JSON.parse('<?php echo json_encode($objListSummaryCredit); ?>');	

		 // Estructura de columnas para el gráfico
        const dataArray = [
          ['Usuario', 'No. CRE NUE']
        ];

        dataObj.forEach(item => {
          dataArray.push([
            item.nickname,
            parseInt(item.countCustomerNew)
          ]);
        });

        const data = google.visualization.arrayToDataTable(dataArray);

        const options = {
          title: 'Clientes nuevos',
          isStacked: true,
          hAxis: {
            title: 'Total por categoría',
            minValue: 0
          },
          vAxis: {
            title: 'Usuario'
          },
          chartArea: {width: '70%'},
		  colors: ['#911eb4']
        };

        const chart = new google.visualization.BarChart(document.getElementById('grafico4'));
        chart.draw(data, options);
		
		
	}
	
	
	function drawClientesCancelados() {
		const dataObj  = JSON.parse('<?php echo json_encode($objListSummaryCredit); ?>');	

		 // Estructura de columnas para el gráfico
        const dataArray = [
          ['Usuario', 'No. CLI CAN']
        ];

        dataObj.forEach(item => {
          dataArray.push([
            item.nickname,
            parseInt(item.countCustomerCancel)
          ]);
        });

        const data = google.visualization.arrayToDataTable(dataArray);

        const options = {
          title: 'No de clientes cencelados',
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

        const chart = new google.visualization.BarChart(document.getElementById('grafico3'));
        chart.draw(data, options);
		
		
	}
	
	
	function drawMontoDesembolso() {
		const dataObj  = JSON.parse('<?php echo json_encode($objListSummaryCredit); ?>');	

		 // Estructura de columnas para el gráfico
        const dataArray = [
          ['Usuario', 'C$ Monto Desembolso']
        ];

        dataObj.forEach(item => {
          dataArray.push([
            item.nickname,            
            parseInt(item.amountCartera),
          ]);
        });

        const data = google.visualization.arrayToDataTable(dataArray);

		//Aplicar formato con prefijo C$
		const formatter = new google.visualization.NumberFormat({
		  prefix: 'C$. ',
		  groupingSymbol: ',',
		  fractionDigits: 2
		});
		formatter.format(data, 1); // Aplica el formato a la columna 1 (valores)

        const options = {
          title: 'Valor de la cartera',
          isStacked: true,
          hAxis: {
            title: 'Total por categoría',
            minValue: 0
          },
          vAxis: {
            title: 'Usuario'
          },
          chartArea: {width: '70%'},
		  colors: ['#f58231']
        };

        const chart = new google.visualization.BarChart(document.getElementById('grafico6'));
        chart.draw(data, options);
		
		
	}
	
	function drawCreditosActivos() {
		const dataObj  = JSON.parse('<?php echo json_encode($objListSummaryCredit); ?>');	

		 // Estructura de columnas para el gráfico
        const dataArray = [
          ['Usuario', 'No. CRE']
        ];

        dataObj.forEach(item => {
          dataArray.push([
            item.nickname,            
            parseInt(item.countCredit),
          ]);
        });

        const data = google.visualization.arrayToDataTable(dataArray);

		//Aplicar formato con prefijo C$
		const formatter = new google.visualization.NumberFormat({
		  prefix: 'Cant. ',
		  groupingSymbol: ',',
		  fractionDigits: 2
		});
		formatter.format(data, 1); // Aplica el formato a la columna 1 (valores)

        const options = {
          title: 'No creditos activos',
          isStacked: true,
          hAxis: {
            title: 'Total por categoría',
            minValue: 0
          },
          vAxis: {
            title: 'Usuario'
          },
          chartArea: {width: '70%'},
		  colors: ['#e6194b']
        };

        const chart = new google.visualization.BarChart(document.getElementById('grafico2'));
        chart.draw(data, options);
		
		
	}
	
	function drawClientes() {
		const dataObj  = JSON.parse('<?php echo json_encode($objListSummaryCredit); ?>');	

		 // Estructura de columnas para el gráfico
        const dataArray = [
          ['Usuario', 'No. CLI']
        ];

        dataObj.forEach(item => {
          dataArray.push([
            item.nickname,
            parseInt(item.countCustomer)
          ]);
        });

        const data = google.visualization.arrayToDataTable(dataArray);

        const options = {
          title: 'Clientes por usuario',
          isStacked: true,
          hAxis: {
            title: 'Total por categoría',
            minValue: 0
          },
          vAxis: {
            title: 'Usuario'
          },
          chartArea: {width: '70%'},
		  colors: ['#ffe119']
        };

        const chart = new google.visualization.BarChart(document.getElementById('grafico1'));
        chart.draw(data, options);
		
		
	}
	
	
</script>
