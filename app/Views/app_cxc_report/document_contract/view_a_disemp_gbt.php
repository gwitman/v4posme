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
						<th colspan="8" style="font-size:small;font-family: 'Inconsolata', monospace;">
							<br/>
							<br/>
							PAGARE A LA ORDEN
						</th>
					</tr>
				</thead>
			</table>
		</div>
		<br/>
		<br/>
		<div class="data_grid_body">
			<table>
				<tbody >
					<tr>
						<td style="text-align:justify;font-size:small;font-family: 'Inconsolata', monospace;">	
								
								<br/>
								<br/>
								<br/>
								<br/>
								Yo <u><b><?php echo $objFirstDetail["legalName"]; ?></b></u> mayor de edad ______________, Comerciante
								de este domicilio, Leon con numero de cedula: 
								<u><?php echo $objFirstDetail["identification"]; ?></u> 
								. Con mi propio nombre y en mi caracter de DEUDOR, por el presente PAGARE A LA ORDEN 
								del ACREDOR la suma de:  
								<u><?php echo sprintf("%01.2f",$objFirstDetail["cuota"]); ?></u> 
								&nbsp;  
								<u><b>(C$ <?php echo helper_GetLetras($objFirstDetail["cuota"],"CORDOBAS CON ",""); ?> CENTAVOS )</b></u> 								 
								Córdobas a pagarse en las oficinas del acredor
								hubicada en esta ciudad, la forma de pago es <?php echo $objFirstDetail["period"]; ?>.
								de  <b><?php echo $objFirstDetail["term"]; ?> 
								cuotas </b>
								de <b>C$ <?php echo sprintf("%01.2f",$objFirstDetail["cuota"] / $objFirstDetail["term"]); ?></b>  
								
								Reconozco en favor del ACREDOR el pago de una tasa de interes corriente y una tasa de interes 
								moratorio conforme a la ley de la materia que regula a este tipo de instituciones Micro financieras.
								Las obligaciones expresadas en cordoba en el presente PAGARE A LA ORDEN mantendran su valor con
								relacion dolare,  moneda de curso legal de los estados unidos de america. En este caso si se produce 
								una modificacion en el tipo oficial de cambio de cordoba con relacion al dolar estado unidense el 
								monto de las obligaciones expresadas en cordoba se ajustaran en la misma proporcion a la modificacion
								operada de conformidad a la tasa de cambio oficial vigente a la fecha de pago publicada por el banco 
								central de Nicaragua. la mora se producira si necesidad de requerimiento Judicial o Extra judicial 
								por la sola llegada del plazo o por la ocurrencia de cuales quiera de los siguiente eventos. por la
								falta de pago de cualquier de las cuotas amortizadas al principal de sus intereses, o del cualquier
								otro rubro en sus respectivos vencimientos , si se entablare en contra del deudor cualquier 
								accion prejudicial o judicial o si dejara sin satisfacer a sus respectivo vencimiento cualquier otra 
								obligacion que tenga conjunta o separadamente a favor del ACREEDOR.

								<br/>
								<br/>
								<br/>
								<br/>
								<br/><br/>
								<br/>
								<br/>
								<br/>
						</td>						
					</tr>
					<tr>
						<td style="text-align:justify;font-size:small;font-family: 'Inconsolata', monospace;">
							Renuncio a favor del ACREEDOR a: a) Al derecho de ser requerido de cobro prejudicial, 
							extra judicial  y judicial por efecto de la mora, pues esta operara por el simple retardo
							en el cumplimiento de las obligaciones aqui contraida. b) a alegar prescripcion por 
							vencimiento de la obligacion o de la accion judicial. c) al derecho de ser depositario 
							de los bienes embargados, pues se depositaran en el ACREEDOR o en la persona que elija 
							el ACREEDOR, quien ejercera el cargo cuenta y riesgo del DEUDOR. d) a todas las expresiones
							provenientes de casos fortuitos o fuerza mayor, las que asume por  imprevista e imprevisible
							que estas fueran a apelar del acto y/o acta de subasta o venta de martillo en su 
							casa. Asi mismo me obligo a pagar los gastos de cobranza administrativa, judicial, extrajudicial
							, deferidos al juramento estimatorio del acreedor y convengo en la plena aplicacion de los 
							articulos 696 y 697 de la Ley Numero 902, CODIGO PROCESAL CIVIL DE LA REPUBLICA DE NICARAGUA 
							, aun que sus efectos en lo general sean suspendidos por la ley. A si mismo me someto y acepto
							toda clase de renovacion que se opere en relacion a la presente obligacion. quedando el suscrito 
							comprometidos en los mismos terminos del presente documento. En la ciudad de Leon dia 
							<?php echo helper_DateToSpanish($objFirstDetail["fechActual"],"d"); ?> del 
							mes de <?php echo helper_DateToSpanish($objFirstDetail["fechActual"],"F"); ?> 
							año <?php echo helper_DateToSpanish($objFirstDetail["fechActual"],"Y"); ?>. 
							<br/>
						</td>						
					</tr>
				</tbody>
			</table>
		</div>
		<br/>
		<br/>
		<div class="data_grid_body">
			<table>
				<tbody>
					<tr>
						<td style="text-align:center">
							<br/>
							<br/>
							<br/>
							<br/>
							<br/>
							<br/>
							<br/>
							<br/>
							______________________________________________
						</td>
					</tr>
					<tr>
						<td style="font-size:small;font-family: 'Inconsolata', monospace;text-align:center">
							<b>DEUDOR</b>
						</td>
					</tr>					
				</tbody>
			</table>
		</div>
	</body>	
</html>