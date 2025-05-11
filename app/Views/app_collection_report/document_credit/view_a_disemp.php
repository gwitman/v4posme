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
						<th colspan="8">TABLA DE PAGO</th>
					</tr>
					<tr>
						<th colspan="8"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
					<tr>
						<th colspan="8">TABLA DE PAGO</th>
					</tr>
				</thead>
			</table>
		</div>
		<br/>
		<div class="data_grid_left">
			<table>
				<tbody>
					<tr>
						<td nowrap>Codigo</td>
						<td nowrap><?php echo $objDetail[0]["customerNumber"]; ?></td>
					</tr>
					<tr>
						<td nowrap>Nombre</td>
						<td nowrap colspan="7"><?php echo $objDetail[0]["nameCustomer"]; ?></td>
					</tr>					
					<tr>
						<td nowrap>Telefono</td>
						<td nowrap><?php echo $objDetail[0]["phoneNumber"]; ?></td>
					</tr>
					<tr>
						<td nowrap>Moneda</td>
						<td nowrap><?php echo $objDetail[0]["simbol"]; ?></td>
					</tr>
					<tr>
						<td nowrap>Factura</td>
						<td nowrap><?php echo $objDetail[0]["documentNumber"]; ?></td>
					</tr>
					<tr>
						<td nowrap>Pagare</td>
						<td nowrap><?php echo "<a href='".base_url()."app_collection_report/customer' >Regresar</a>"; ?> </td>
					</tr>
					
				</tbody>
			</table>
		</div>
		<br/>
		<br/>
		<div class="data_grid_body">
			<table>
				<thead>
					<tr>
						<th nowrap class="cell_left">No</th>
						<th nowrap class="cell_left">Fecha Cuota</th>
						<th nowrap class="cell_right">Saldo Anterior</th>
						<th nowrap class="cell_right">Cuota</th>
						<th nowrap class="cell_right">Saldo Nuevo</th>
						<th nowrap class="cell_right">Abono Pactado</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$capital 	= 0;
					$interes 	= 0;
					$count 		= 0;
					
					if($objDetail)
					{
						$balance	= $objDetail[0]["capitalMoreInteres"];
						foreach($objDetail as $i){
							$count++;
							
							
							if($i["creditAmortizationID"] == 0 )
							echo "<tr style='background-color:chartreuse' >";
							else 
							echo "<tr>";
						
								echo "<td nowrap class='cell_left'>";
									echo $count;
								echo "</td>";
								echo "<td nowrap class='cell_left'>";
									echo "'".(date_format(date_create($i["dateApply"]),"Y-m-d"));
								echo "</td>";
								echo "<td nowrap class='cell_right'>";
									echo number_format($balance,2);
								echo "</td>";
								echo "<td nowrap class='cell_right'>";
									echo number_format($i["shareReal"],2);
								echo "</td>";
								$balance = $balance - $i["shareReal"];
								echo "<td nowrap class='cell_right'>";
									echo number_format($balance,2);
								echo "</td>";
								echo "<td nowrap class='cell_right'>";
									echo number_format($i["shareProgramin"],2);
								echo "</td>";
								
							echo "</tr>";
							
							
						}
					}
					?>
				</tbody>
			</table>
		</div>
		<br/>
		<br/>
		<div class="data_grid_firm_system">
			<table>
				<tbody>
					<tr>
						<td colspan="8"><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</body>	
</html>