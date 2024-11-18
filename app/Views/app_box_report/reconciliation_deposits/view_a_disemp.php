<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $objFirmaEncription; ?></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		
		<link rel="stylesheet" type="text/css" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/style_table_report_printer.css">
		<link rel="stylesheet" type="text/css" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/style_table_report_printer.css" media="print">
	</head>
	<body style="background-image:url(<?php echo base_url(); ?>/resource/img/logos/<?php echo $objLogo->value;?>);background-size:80px 50px;"> 
		<div class="data_grid_encabezado">
			<table>
				<thead>
					<tr>
						<th colspan="9">RECUPERACION</th>
					</tr>
					<tr>
						<th colspan="9"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan="9"><?php 
							if($objDetail) { 
								echo $objDetail[0]["FiltroCode"]."/".$objDetail[0]["FiltroName"]; 
							} 
							else  
								echo "N/D" ;
						?></th>
					</tr>
					
					<tr>
						<th colspan="9">
							Monto Total: 
							<?php 
							if($objDetail) { 
								echo array_sum(array_column($objDetail, 'Monto'));
							} 
							else  
								echo 0.00 ;			
							?>
						</th>
					</tr>
					
					
				</thead>
			</table>
		</div>
		<br/>
		<div class="data_grid_body">
			<table>
				<thead>
					<tr>
						<th nowrap class="cell_left">#</th>
						<th nowrap class="cell_left">No</th>
						<th nowrap class="cell_left">Cliente</th>
						<th nowrap class="cell_left">Documento</th>						
						<th nowrap class="cell_left">Fecha</th>
						<th nowrap class="cell_right">Cuota</th>
						<th nowrap class="cell_right">Moneda</th>
						<th nowrap class="cell_right">Gestor</th>
						<th nowrap class="cell_right">Comentario</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count 			= 0;
					$countCliente	= 1;
					$cliente 		= "";
					$style			= "";
					$style2			= "";
					$cliente2 		= "";
					$clienteNuevo	= true;
					if($objDetail)
					foreach($objDetail as $i){
						//Calcular Sebra
						$count++;
						if ($count % 2 == 0 )
						$style = "background:#ddd";
						else 
						$style = "";
						//Separar Cliente al Final 
						if($i["NoCliente"] != $cliente && $cliente != "")
							echo  "<tr style='border-bottom-color:BLUE;border-bottom-style:solid;border-bottom-width:1px;'><td colspan='9'>&nbsp;</td></tr>";						
						$cliente = $i["NoCliente"]; 
						/*Estilo de cuota*/
						
						//Repitar Cliente unicamente al Inicio
						if( $cliente2 != "" && $cliente2 != $i["NoCliente"] ){
								$clienteNuevo = true;
								$countCliente++;
						}
					
						//Grid
						echo "<tr style='".$style."'>";					
							echo "<td nowrap class='cell_left'>";
								if($clienteNuevo) echo  $countCliente;
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								if($clienteNuevo) echo ($i["NoCliente"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								if($clienteNuevo) echo ($i["Cliente"]);
							echo "</td>";
						
							echo "<td nowrap class='cell_left'>";
								echo ($i["Documento"]);
							echo "</td>";
							echo "<td nowrap class='cell_left' >";
								echo "'".(date_format(date_create($i["Fecha"]),"Y-m-d"));
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["Monto"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";						
								echo ($i["Moneda"]);
							echo "</td>";
							
							echo "<td nowrap class='cell_left'>";
								echo ($i["Gestor"]);
							echo "</td>";
							
							echo "<td nowrap class='cell_left'>";
								echo '*';
							echo "</td>";
						echo "</tr>";					
						//Repitar Cliente unicamente al Inicio
						if($clienteNuevo)
						$clienteNuevo = false;
						$cliente2 = $i["NoCliente"];
						
					}
					?>
				</tbody>
			</table>
		</div>
		<br/>
		<br/>
		<br/>
		<div class="data_grid_firm_system">
			<table>
				<tbody>
					<tr>
						<td colspan="9"><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</body>	
</html>