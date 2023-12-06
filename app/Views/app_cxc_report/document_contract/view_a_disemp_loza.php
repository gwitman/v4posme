<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $objFirmaEncription; ?></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		
		<link href="https://fonts.googleapis.com/css?family=Inconsolata:400,700" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/style_table_report_printer.css">
		<link rel="stylesheet" type="text/css" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/style_table_report_printer.css" media="print">
	</head>
	<!--<body style="background:url('<?php echo base_url(); ?>/resource/img/sello_azul.png');background-repeat:no-repeat;background-size:150px;background-position:bottom +60px right +110px"> -->
	<body style="font-size:4px;margin-left:0px;margin-right:0px;">
		<div class="data_grid_encabezado">
			<table>
				<thead>
					<tr>
						<th colspan="8" style="font-size:small;font-family: 'Inconsolata', monospace;"><!--titulo--></th>
					</tr>
				</thead>
			</table>
		</div>
		<br/>
		<br/>
		<div class="data_grid_body">
			<table>
				<tbody >
					<tr style="width:100%">
						<td style="width:100%" >Lic. Luis Mendoza</td>
					</tr>
					<tr style="width:100%">
						<td style="width:100%" >Sus Manos.</td>
					</tr>
					<tr style="width:100%">
						<td style="width:100%" >Reciba cordiales saludos.</td>
					</tr>
					<tr style="width:100%">
						<td style="width:100%" >
							La presente es para hacer formal solicitud de PROMESA DE VENTA a nombre 
							de: <?php echo $objFirstDetail["legalName"]; ?> con cedula de identidad No 
							<?php echo $objFirstDetail["identification"]; ?>
						</td>
					</tr>
					<tr style="width:100%">
						<td style="width:100%" >Estado civil: <?php echo $objFirstDetail["estadoCivil"]; ?> .</td>
					</tr>
					<tr style="width:100%">
						<td style="width:100%" >Oficio: <?php echo $objFirstDetail["profesion"]; ?> .</td>
					</tr>
					<tr style="width:100%">
						<td style="width:100%" >Comentario: <?php echo $objFirstDetail["Concepto"]; ?></td>
					</tr>
					
					<tr style="width:100%">
						<td style="width:100%" >Lote: <?php echo $objFirstDetail["productNameLog"]; ?></td>
					</tr>
					
					<tr style="width:100%">
						<td style="width:100%" >Costo: <?php echo sprintf("%.2f",$objFirstDetail["amountTotal"]); ?></td>
					</tr>
					
					<tr style="width:100%">
						<td style="width:100%" >Prima: <?php echo sprintf("%.2f",$objFirstDetail["receiptAmount"]); ?></td>
					</tr>
					<tr style="width:100%">
						<td style="width:100%" >Cuota: <?php echo sprintf("%.2f",$objFirstDetail["montoCuota"] * $objFirstDetail["TipoCambio"]); ?></td>
					</tr>
					<tr style="width:100%">
						<td style="width:100%" >Saldo: <?php echo sprintf("%.2f",$objFirstDetail["cuota"]); ?></td>
					</tr>
					<tr style="width:100%">
						<td style="width:100%" >Plazo: <?php echo $objFirstDetail["term"]; ?> </td>
					</tr>
					<tr style="width:100%">
						<td style="width:100%" >
							Fecha de pago: <?php echo $objFirstDetail["period"]; ?> 
							iniciando a partir del día <b>
							<?php echo helper_DateToSpanish($objFirstDetail["fechInicial"],"d"); ?>
							de 
							<?php echo helper_DateToSpanish($objFirstDetail["fechInicial"],"F"); ?>
							Del 
							<?php echo helper_DateToSpanish($objFirstDetail["fechInicial"],"Y"); ?></b>
							
						</td>
					</tr>
					<tr style="width:100%">
						<td style="width:100%" >Nota: por devolucion se le deduce el 50% de lo abonado, con tres meses de incumplimiento.</td>
					</tr>
					<tr style="width:100%">
						<td style="width:100%" >Agradeciendo de antemano.</td>
					</tr>
					<tr style="width:100%">
						<td style="width:100%" >Exito en sus labores diarias.</td>
					</tr>
					<tr style="width:100%">
						<td style="width:100%" >
							Extiendo la presente a los 
							<?php echo helper_DateToSpanish($objFirstDetail["fechActual"],"d"); ?> 
							dias del mes de <?php echo helper_DateToSpanish($objFirstDetail["fechActual"],"F"); ?>
							del <?php echo helper_DateToSpanish($objFirstDetail["fechActual"],"Y"); ?>.
						</td>
					</tr>
					
					<tr style="width:100%">
						<td style="width:100%" >Atentamente: ______________________________.</td>
					</tr>
					<tr style="width:100%">
						<td style="width:100%" >Joel Bolívar Pérez Rosales.</td>
					</tr>
					<tr style="width:100%">
						<td style="width:100%" >Gerente Propietario.</td>
					</tr>
										
					
				</tbody>
			</table>
		</div>
		

	
	</body>	
</html>