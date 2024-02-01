<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $objFirmaEncription; ?></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		
		
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/genyx-fn.js"></script>
		
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/chart-google/loader.js"></script>				
		<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/jLinq-2.2.1.js"></script>
		
				
		<?php 
		echo helper_reporteGeneralCreateStyle();
		?>
		
	</head>
	<body style="font-family:monospace;font-size:larger;margin:0px 0px 0px 0px"> 
		
		
		<table style="
			width:100%;border-spacing: 10px;			
		">
			<thead>
				<tr>
					<th   style="text-align:left;width:130px">
						<table>
							<tr>
								<td>
									<img width="120" height="110" 						
										style="
											width: 120px;
											height: 110px;
										"
										src="<?php echo base_url(); ?>/resource/img/logos/logo-micro-finanza.jpg" 
									/>
								</td>
							</tr>
						</table>
					</th>
					<th style="vertical-align:top" >
						<table style="width:100%">
							
							<tr  style="
								text-align:right;background-color:#00628e;color:white;
								width:80%
							">
								<th>COMPARATIVO MENSUAL DE GASTO</th>
							</tr>
							<tr>
								<th  style="
									text-align:right;background-color:#00628e;color:white;
								"><?php echo strtoupper($objCompany->name); ?></th>
							</tr>
							<tr>
								<th  style="
									text-align:right;background-color:#00628e;color:white;
								">INFORMACION DE <?php echo $objStartOn; ?> AL <?php echo $objEndOn; ?></th>
							</tr>
							<tr>
								<th style="
									text-align:right;background-color:#00628e;color:white;
								"></th>
							</tr>
							<tr>
								<th style="
									text-align:right;background-color:#00628e;color:white;
								"></th>
							</tr>				
							<tr>
								<th  style="text-align:left">
									&nbsp;
								</th>
							</tr>
						</table>
					</th>
				</tr>				
			</thead>
		</table>
		
		
		
		<br/>
		
		
		<br/>
		
		<table style="width:100%;order-spacing: 10px;">
			<thead>
				<tr style="background-color:#00628e;color:white;">									

					<?php
					$count 		= 0;
					if($objDetail)
					foreach($objDetail[0] as $i => $value)
					{	
					
						echo '<th style="text-align:left;width:80px;"   colspan="1" class="border">'.$i.'</th>';
						
					}
					?>
				
				</tr>
			</thead>				
			<tbody>
				<?php
				$columnx = 0;				
				if($objDetail)
				foreach($objDetail as $i)
				{		
					$columnx = 0;
					echo "<tr>";								
					foreach($i as $key => $ii)
					{			
						$columnx++;
						
						if($columnx == 1)
						echo "<td style='text-align:left'  colspan='1' class='border' >";
						else 
						echo "<td style='text-align:right'  colspan='1' class='border' >";	
							if($columnx > 1)
							echo (number_format($ii,2,'.',','));
							else 
							echo ($ii);
						echo "</td>";																											
					}
					echo "</tr>";
				}
				?>
				
				


			</tbody>
		
		</table>
		
		<table style='width:100%'>
			<tr style='width:100%'>
				<td style='width:100%'>
					<div id="grafico1" style="height:400px;width:400px;margin:auto" ></div>
				</td>
			</tr>
		</table>
		
		<script>	
			//https://www.w3schools.com/js/js_graphics_google_chart.asp
			
			var objTransactionMaster 		= JSON.parse('<?php echo json_encode($objDetail); ?>');				
			var objListCategoria 			= jLinq.from(jLinq.from(objTransactionMaster).select(function(a){ return a.Tipo })).distinct();
			
			
			
			//Data Source de Gastos
			//---------------------------------------
			//---------------------------------------
			var objDataSourceVentasPorCategoria = new Array();
			objDataSourceVentasPorCategoria.push(new Array("Tipo","Gasto"));
			for(var i=0; i< objListCategoria.length; i++){
				var category_ 	= objListCategoria[i];
				
				
				

				var venta_ 		= jLinq.from(jLinq.from(objTransactionMaster).where(function(a){ return a.Tipo == category_}).
								  select(function(a){ 
									var isuma = 0;
									
									Object.keys(a).forEach(propiedad => {									
										
										if(propiedad != "Tipo")
										isuma = isuma +  parseInt(a[propiedad]);
									});
									
									return isuma;
									
								  })).sum().result;
				
				objDataSourceVentasPorCategoria.push(new Array(category_,venta_));	
			}
			
			
			
			
		
			
			function drawChartBarraCirculo() {

				var data = google.visualization.arrayToDataTable(
					objDataSourceVentasPorCategoria
				);

				var options = {
				  title: 'Reporte de gastos'
				};

				var chart = new google.visualization.PieChart(document.getElementById('grafico1'));
				chart.draw(data, options);

			}
			
			
			google.charts.load('current',{packages:['corechart']});				
			google.charts.setOnLoadCallback(drawChartBarraCirculo);	
			  
		
			
			
			
									
		</script>
		
		
		
	</body>	
</html>