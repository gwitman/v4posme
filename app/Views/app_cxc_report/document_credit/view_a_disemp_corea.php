<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Tabla Amortizacion Cliente ...<?php echo $objFirmaEncription; ?></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		
		<link rel="stylesheet" type="text/css" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/style_table_report_printer.css">
		<link rel="stylesheet" type="text/css" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/style_table_report_printer.css" media="print">
	</head>
	<body style="background-image:url(<?php echo base_url(); ?>/resource/img/logos/<?php echo $objLogo->value;?>);background-size:80px 50px;position:relative;"> 
		<div style="position: absolute;width:100%">
			<div class="data_grid_encabezado">
				<table>
					<thead>
						<tr>
							<th colspan="6">TABLA DE AMORTIZACION DEL DOCUMENTO DE CREDITO.</th>
						</tr>
						<tr>
							<th colspan="6"><?php echo strtoupper($objCompany->name); ?></th>
						</tr>
						<tr>
							<th colspan="6">FONDO DE INVERSION PARA EL DESARROLLO LOCAL</th>
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
							<td nowrap><?php echo $objFirstDetail["customerNumber"]; ?></td>
						</tr>
						<tr>
							<td nowrap>Nombre</td>
							<td nowrap colspan="7"><?php echo $objFirstDetail["legalName"]; ?></td>
						</tr>
						<tr>
							<td nowrap>Linea</td>
							<td nowrap><?php echo $objFirstDetail["accountNumber"]; ?></td>
						</tr>
						<tr>
							<td nowrap>Pago</td>
							<td nowrap><?php echo $objFirstDetail["periodPay"]; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<br/>
			<div class="data_grid_left">
				<table>
					<tbody>
						<tr>
							<td nowrap>Documento</td>
							<td nowrap><?php echo $objFirstDetail["documentNumber"]; ?></td>
						</tr>
						<tr>
							<td nowrap>Fecha</td>
							<td nowrap><?php echo "'".date_format(date_create($objFirstDetail["dateOn"]),"Y-m-d"); ?></td>
						</tr>
						<tr>
							<td nowrap>Monto</td>						
							<td nowrap><?php echo number_format($objFirstDetail["Total"],2); ?></td>
						</tr>
						<tr>
							<td nowrap>Plazo</td>
							<td nowrap><?php echo $objFirstDetail["term"]; ?> cuotas</td>
						</tr>
						<tr>
							<td nowrap>Estado</td>
							<td nowrap><?php echo $objFirstDetail["statusDocument"]; ?></td>
						</tr>
						<tr>
							<td nowrap>Moneda</td>
							<td nowrap><?php echo $objFirstDetail["currencyName"]; ?></td>
						</tr>
						<tr>
							<td nowrap>Objetividad</td>
							<td nowrap><?php echo $objFirstDetail["note"]; ?></td>
						</tr>
						<tr>
							<td nowrap>Pagare y Tabla</td>
							<td nowrap>
								<?php echo "<a href='".base_url()."/app_cxc_report/document_contract/viewReport/true/documentNumber/".$objFirstDetail["documentNumber"]."' target='_blank'>Pagare Local</a>"; ?> 
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php echo "<a href='".base_url()."/app_cxc_report/document_contract/viewReport/true/documentNumber/".$objFirstDetail["documentNumber"]."/viewname/view_a_disemp_provider' target='_blank'>Pagare Con Proveedor</a>"; ?> 
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php echo "<a href='".base_url()."/app_cxc_report/document_contract/viewReport/true/documentNumber/".$objFirstDetail["documentNumber"]."/viewname/view_a_disemp_producto' target='_blank'>Pagare De Producto</a>"; ?> 
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php echo "<a href='".base_url()."/app_cxc_report/document_credit/viewReport/true/documentNumber/".$objFirstDetail["documentNumber"]."' target='_blank'>Amortizacion Cliente</a>"; ?> 
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php echo "<a href='".base_url()."/app_cxc_report/document_credit/viewReport/true/documentNumber/".$objFirstDetail["documentNumber"]."/viewname/view_a_disemp_fidlocal' target='_blank'>Amortizacion FID LOCAL</a>"; ?> 
							</td>
						</tr>
						
					</tbody>
				</table>
			</div>
			<br/>
			<div class="data_grid_body">
				<table>
					<thead>
						<tr>
							<th nowrap class="cell_left">No</th>
							<th nowrap class="cell_left">Fecha</th>
							<th nowrap class="cell_right">Inicial</th>
							
							<th nowrap class="cell_right">Cuota</th>
							<th nowrap class="cell_right">Final</th>						
							<th nowrap class="cell_right">Estado</th>
						</tr>
					</thead>				
					<tbody>
						<?php
						$capital 	= 0;
						$interes 	= 0;
						$count 		= 0;
						$total_		= $objFirstDetail["Total"];						
						$total_f	= $objFirstDetail["Total"];	
						if($objDetail)
						foreach($objDetail as $i){
								$count++;
								$capital	+= ($i["capital"]);
								$interes	+= ($i["interest"]);
								$total_		= $total_f;
								$total_f 	= $total_  - $i["share"];
								if ($count % 2 == 0 )
								echo "<tr style='background:#ddd'>";
								else 
								echo "<tr>";							
								echo "<td nowrap class='cell_left'>";
									echo $count;
								echo "</td>";
								echo "<td nowrap class='cell_left'>";
									echo "'".(date_format(date_create($i["dateApply"]),"Y-m-d"));
								echo "</td>";
								/*
								echo "<td nowrap class='cell_right'>";
									echo number_format($i["balanceStart"],2);
								echo "</td>";						
								*/
								
								echo "<td nowrap class='cell_right'>";
									echo number_format($total_	,2);
								echo "</td>";						
								
								echo "<td nowrap class='cell_right'>";
									echo number_format($i["share"],2);
								echo "</td>";
								/*
								echo "<td nowrap class='cell_right'>";
									echo number_format($i["balanceEnd"],2);
								echo "</td>";							
								*/
								
								echo "<td nowrap class='cell_right'>";
									echo number_format($total_f,2);
								echo "</td>";							
								
								echo "<td nowrap class='cell_right'>";
									echo ($i["statusShare"]);
								echo "</td>";
							echo "</tr>";
						}
						?>
					</tbody>
					<tfoot>
						<tr>
							<th nowrap class="cell_left">No</th>
							<th nowrap class="cell_left">Fecha</th>
							<th nowrap class="cell_right">Inicial</th>						
							<th nowrap class="cell_right">Cuota</th>
							<th nowrap class="cell_right">Final</th>						
							<th nowrap class="cell_right">Estado</th>
						</tr>
					</tfoot>
				</table>
			</div>
			<br/>		
			<div class="data_grid_right">
				<table>
					<tbody>

						<tr>
							<td colspan="6"></td>
							<td class="cell_right">Total:</td>
							<td class="cell_right"><?php echo number_format(($interes + $capital),2);?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<br/>
			<br/>
			<br/>
			<div class="data_grid_firm_app">
				<table>
					<tbody>
						<tr>
							<td colspan="4">______________________________</td>
							<td colspan="4">______________________________</td>
						</tr>
						<tr>
							<td colspan="4"><?php echo $objPropietaryName->value; ?></td>
							<td colspan="4"><?php echo $objFirstDetail["legalName"]; ?></td>
						</tr>
						<tr>
							<td colspan="4">Acredor</td>
							<td colspan="4">Deudor</td>
						</tr>
					</tbody>
				</table>
			</div>
			<br/>
			<div class="data_grid_firm_system">
				<table>
					<tbody>
						<tr>
							<td colspan="6">
								<span style="color: red;font-weight: bold;font-style: inherit;font-size: initial;"><?php echo $objParameterBank->value; ?></span><br/>								
								<span style="color: blue;font-weight: bold;font-style: inherit;font-size: initial;"><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></span><br/>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<!--<div style="background:url('<?php echo base_url(); ?>/resource/img/sello_azul.png');background-repeat:no-repeat;background-size:150px;background-position:top +60px right +110px;height:250px;height:718px;"> </div>-->
	</body>	
</html>