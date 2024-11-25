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
						<th colspan="12">CRONOGRAMA DE COBRANZA</th>
					</tr>
					<tr>
						<th colspan="12"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan="12"><?php 
							if($objDetail) { 
								echo $objDetail[0]["FiltroCode"]."/".$objDetail[0]["FiltroName"]; 
							} 
							else  
								echo "N/D" ;
						?></th>
					</tr>
					
					<tr>
						<th colspan="12">
							Monto Total Atrazado: 
							<?php 
							if($objDetail) { 
								echo array_sum(array_column($objDetail, 'MontoTotalAtrazo'));
							} 
							else  
								echo 0.00 ;			
							?>
						</th>
					</tr>
					
					<tr>
						<th colspan="12">
							Monto Total Meta: 
							<?php 
							if($objDetail) { 
								echo array_sum(array_column($objDetail, 'MontoTotalCobradoCorriente'));
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
						<th nowrap class="cell_left">Telefono</th>
						<th nowrap class="cell_left">Factura</th>						
						<th nowrap class="cell_left">Fecha</th>
						<th nowrap class="cell_right">Cuota</th>
						<th nowrap class="cell_right">Abono</th>
						<th nowrap class="cell_right">Moneda</th>
						<th nowrap class="cell_right">Gestor</th>
						<th nowrap class="cell_right">Comentario</th>
						<th nowrap class="cell_right">Dirección</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count 				= 0;
					$countCliente		= 1;
					$cliente 			= "";
					$style				= "";
					$style2				= "";
					$cliente2 			= "";
					$clienteNuevo		= true;
					$totalByCustomer 	= 0;
					
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
						{
							echo  
								"<tr>
									<td colspan='6'>&nbsp;</td>									
									<td colspan='1' nowrap class='cell_right' >".number_format($totalByCustomer,2)."</td>
									<td colspan='5'>&nbsp;</td>
								</tr>";						
							echo  "<tr style='border-bottom-color:BLUE;border-bottom-style:solid;border-bottom-width:1px;'><td colspan='12'>&nbsp;</td></tr>";						
						}
						
						$cliente = $i["NoCliente"]; 
						$style2 = ";border-bottom-color:".$i["Atraso"].";border-bottom-style:dashed;border-bottom-width:1px;";
						//Repitar Cliente unicamente al Inicio
						if( $cliente2 != "" && $cliente2 != $i["NoCliente"] ){
							$clienteNuevo 		= true;
							$totalByCustomer 	= 0;
							$countCliente++;
						}
						else 
						{
							$totalByCustomer	= $totalByCustomer + $i["Cuota"];
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
								if($clienteNuevo)  echo ($i["Telefono"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["Factura"]);
							echo "</td>";
							echo "<td nowrap class='cell_left' style='".$style2."'>";
								echo "'".(date_format(date_create($i["Fecha"]),"Y-m-d"));
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["Cuota"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo ($i["Abono"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";						
								echo ($i["Moneda"]);
							echo "</td>";
							
							echo "<td nowrap class='cell_left'>";
								if($clienteNuevo) echo $i["Gestor"];
							echo "</td>";
							
							echo "<td nowrap class='cell_left'>";
								echo '*';
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								if($clienteNuevo) echo $i["Direccion"];
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
						<td colspan="12"><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</body>	
</html>