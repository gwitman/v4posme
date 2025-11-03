<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $objFirmaEncription; ?></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		
		<?php 
		echo helper_reporteGeneralCreateStyle();
		?>
		
	</head>
	<body style="font-family:monospace;font-size:smaller;margin:0px 0px 0px 0px"> 
	
		
		
		
		<?php
		$configColumn["0"]["Titulo"] 		= "Cliente";
		$configColumn["0"]["TituloFoot"]	= "";
		$configColumn["0"]["FiledSouce"]	= "customerNumber";
		$configColumn["0"]["Colspan"] 		= "1";
		$configColumn["0"]["Formato"] 		= "";
		$configColumn["0"]["Total"] 		= False;
		$configColumn["0"]["Alineacion"] 	= "Left";
		$configColumn["0"]["TotalValor"] 	= 0;
		$configColumn["0"]["FiledSoucePrefix"]	= "";
		$configColumn["0"]["Width"]			= "80px";
		
		$configColumn["1"]["Titulo"] 		= "Nombre";
		$configColumn["1"]["TituloFoot"]	= "";
		$configColumn["1"]["FiledSouce"]	= "legalName";
		$configColumn["1"]["Colspan"] 		= "1";
		$configColumn["1"]["Formato"] 		= "";
		$configColumn["1"]["Total"] 		= False;
		$configColumn["1"]["Alineacion"] 	= "Left";
		$configColumn["1"]["TotalValor"] 	= 0;
		$configColumn["1"]["FiledSoucePrefix"]	= "";
		$configColumn["1"]["Width"]			= "250px";
		
		
		
		$configColumn["2"]["Titulo"] 		= "Id";
		$configColumn["2"]["TituloFoot"]	= "";
		$configColumn["2"]["FiledSouce"]	= "identification";
		$configColumn["2"]["Colspan"] 		= "1";
		$configColumn["2"]["Formato"] 		= "";
		$configColumn["2"]["Total"] 		= False;
		$configColumn["2"]["Alineacion"] 	= "Left";
		$configColumn["2"]["TotalValor"] 	= 0;
		$configColumn["2"]["FiledSoucePrefix"]	= "";
		$configColumn["2"]["Width"]			= "130px";
			
		$configColumn["3"]["Titulo"] 		= "Telefono";
		$configColumn["3"]["TituloFoot"]	= "";
		$configColumn["3"]["FiledSouce"]	= "phone";
		$configColumn["3"]["Colspan"] 		= "1";
		$configColumn["3"]["Formato"] 		= "";
		$configColumn["3"]["Total"] 		= False;
		$configColumn["3"]["Alineacion"] 	= "Left";
		$configColumn["3"]["TotalValor"] 	= 0;
		$configColumn["3"]["FiledSoucePrefix"]	= "";
		$configColumn["3"]["Width"]			= "90px";
		
		
		
		$configColumn["4"]["Titulo"] 		= "Direccion";
		$configColumn["4"]["TituloFoot"]	= "";
		$configColumn["4"]["FiledSouce"]	= "direccion";
		$configColumn["4"]["Colspan"] 		= "1";
		$configColumn["4"]["Formato"] 		= "";
		$configColumn["4"]["Total"] 		= False;
		$configColumn["4"]["Alineacion"] 	= "Left";
		$configColumn["4"]["TotalValor"] 		= 0;
		$configColumn["4"]["FiledSoucePrefix"]	= "";
		$configColumn["4"]["Width"]			= "200px";
		
		$configColumn["5"]["Titulo"] 		= "Pais";
		$configColumn["5"]["TituloFoot"]	= "";
		$configColumn["5"]["FiledSouce"]	= "pais";
		$configColumn["5"]["Colspan"] 		= "1";
		$configColumn["5"]["Formato"] 		= "";
		$configColumn["5"]["Total"] 		= False;
		$configColumn["5"]["Alineacion"] 	= "Left";
		$configColumn["5"]["TotalValor"] 		= 0;
		$configColumn["5"]["FiledSoucePrefix"]	= "";
		$configColumn["5"]["Width"]			= "200px";
		
		$configColumn["6"]["Titulo"] 		= "Departamento";
		$configColumn["6"]["TituloFoot"]	= "";
		$configColumn["6"]["FiledSouce"]	= "departamento";
		$configColumn["6"]["Colspan"] 		= "1";
		$configColumn["6"]["Formato"] 		= "";
		$configColumn["6"]["Total"] 		= False;
		$configColumn["6"]["Alineacion"] 	= "Left";
		$configColumn["6"]["TotalValor"] 		= 0;
		$configColumn["6"]["FiledSoucePrefix"]	= "";
		$configColumn["6"]["Width"]			= "200px";
		
		
		$configColumn["7"]["Titulo"] 		= "Municipio";
		$configColumn["7"]["TituloFoot"]	= "";
		$configColumn["7"]["FiledSouce"]	= "municipio";
		$configColumn["7"]["Colspan"] 		= "1";
		$configColumn["7"]["Formato"] 		= "";
		$configColumn["7"]["Total"] 		= False;
		$configColumn["7"]["Alineacion"] 	= "Left";
		$configColumn["7"]["TotalValor"] 		= 0;
		$configColumn["7"]["FiledSoucePrefix"]	= "";
		$configColumn["7"]["Width"]			= "200px";
		
		$configColumn["8"]["Titulo"] 		= "Domicilio";
		$configColumn["8"]["TituloFoot"]	= "";
		$configColumn["8"]["FiledSouce"]	= "domicilio";
		$configColumn["8"]["Colspan"] 		= "1";
		$configColumn["8"]["Formato"] 		= "";
		$configColumn["8"]["Total"] 		= False;
		$configColumn["8"]["Alineacion"] 	= "Left";
		$configColumn["8"]["TotalValor"] 		= 0;
		$configColumn["8"]["FiledSoucePrefix"]	= "";
		$configColumn["8"]["Width"]			= "200px";
		
		$configColumn["9"]["Titulo"] 		= "Gps";
		$configColumn["9"]["TituloFoot"]	= "";
		$configColumn["9"]["FiledSouce"]	= "gps";
		$configColumn["9"]["Colspan"] 		= "1";
		$configColumn["9"]["Formato"] 		= "";
		$configColumn["9"]["Total"] 		= False;
		$configColumn["9"]["Alineacion"] 	= "Left";
		$configColumn["9"]["TotalValor"] 		= 0;
		$configColumn["9"]["FiledSoucePrefix"]	= "";
		$configColumn["9"]["Width"]			= "200px";
		
		$configColumn["10"]["Titulo"] 		= "Tipo de cobro";
		$configColumn["10"]["TituloFoot"]	= "";
		$configColumn["10"]["FiledSouce"]	= "tipo_cobro";
		$configColumn["10"]["Colspan"] 		= "1";
		$configColumn["10"]["Formato"] 		= "";
		$configColumn["10"]["Total"] 		= False;
		$configColumn["10"]["Alineacion"] 	= "Left";
		$configColumn["10"]["TotalValor"] 		= 0;
		$configColumn["10"]["FiledSoucePrefix"]	= "";
		$configColumn["10"]["Width"]			= "200px";
		
		$configColumn["11"]["Titulo"] 		= "Tipo de factura";
		$configColumn["11"]["TituloFoot"]	= "";
		$configColumn["11"]["FiledSouce"]	= "tipo_factura";
		$configColumn["11"]["Colspan"] 		= "1";
		$configColumn["11"]["Formato"] 		= "";
		$configColumn["11"]["Total"] 		= False;
		$configColumn["11"]["Alineacion"] 	= "Left";
		$configColumn["11"]["TotalValor"] 		= 0;
		$configColumn["11"]["FiledSoucePrefix"]	= "";
		$configColumn["11"]["Width"]			= "200px";
		
		$configColumn["12"]["Titulo"] 		= "Observacion";
		$configColumn["12"]["TituloFoot"]	= "";
		$configColumn["12"]["FiledSouce"]	= "observacion";
		$configColumn["12"]["Colspan"] 		= "1";
		$configColumn["12"]["Formato"] 		= "";
		$configColumn["12"]["Total"] 		= False;
		$configColumn["12"]["Alineacion"] 	= "Left";
		$configColumn["12"]["TotalValor"] 		= 0;
		$configColumn["12"]["FiledSoucePrefix"]	= "";
		$configColumn["12"]["Width"]			= "200px";
		
		
		
		$configColumn["14"]["Titulo"] 		= "Visita";
		$configColumn["14"]["TituloFoot"]	= "";
		$configColumn["14"]["FiledSouce"]	= "lastVisit";
		$configColumn["14"]["Colspan"] 		= "1";
		$configColumn["14"]["Formato"] 		= "";
		$configColumn["14"]["Total"] 		= False;
		$configColumn["14"]["Alineacion"] 	= "Left";
		$configColumn["14"]["TotalValor"] 		= 0;
		$configColumn["14"]["FiledSoucePrefix"]	= "";
		$configColumn["14"]["Width"]			= "200px";
		
		
		
		
		$configColumn["15"]["Titulo"] 		= "Mora";
		$configColumn["15"]["TituloFoot"]	= "";
		$configColumn["15"]["FiledSouce"]	= "maxDiasMora";
		$configColumn["15"]["Colspan"] 		= "1";
		$configColumn["15"]["Formato"] 		= "Number";
		$configColumn["15"]["Total"] 		= False;
		$configColumn["15"]["Alineacion"] 	= "Right";
		$configColumn["15"]["TotalValor"] 	= 0;
		$configColumn["15"]["FiledSoucePrefix"]	= "";
		$configColumn["15"]["Width"]			= "50px";
		
		$configColumn["16"]["Titulo"] 		= "Factura";
		$configColumn["16"]["TituloFoot"]	= "";
		$configColumn["16"]["FiledSouce"]	= "factura";
		$configColumn["16"]["Colspan"] 		= "1";
		$configColumn["16"]["Formato"] 		= "";
		$configColumn["16"]["Total"] 		= False;
		$configColumn["16"]["Alineacion"] 	= "Left";
		$configColumn["16"]["TotalValor"] 	= 0;
		$configColumn["16"]["FiledSoucePrefix"]	= "";
		$configColumn["16"]["Width"]			= "80px";
		
		$configColumn["17"]["Titulo"] 		= "Atrazado";
		$configColumn["17"]["TituloFoot"]	= "";
		$configColumn["17"]["FiledSouce"]	= "montoAtrazado";
		$configColumn["17"]["Colspan"] 		= "1";
		$configColumn["17"]["Formato"] 		= "Number";
		$configColumn["17"]["Total"] 		= True;
		$configColumn["17"]["Alineacion"] 	= "Right";
		$configColumn["17"]["TotalValor"] 	= 0;
		$configColumn["17"]["FiledSoucePrefix"]	= "moneda";
		$configColumn["17"]["Width"]			= "80px";
			
		$configColumn["18"]["Titulo"] 		= "Proximo Pago";
		$configColumn["18"]["TituloFoot"]	= "";
		$configColumn["18"]["FiledSouce"]	= "proximoPago";
		$configColumn["18"]["Colspan"] 		= "1";
		$configColumn["18"]["Formato"] 		= "Date";
		$configColumn["18"]["Total"] 		= False;
		$configColumn["18"]["Alineacion"] 	= "Left";
		$configColumn["18"]["TotalValor"] 	= 0;
		$configColumn["18"]["FiledSoucePrefix"]	= "";
		$configColumn["18"]["Width"]			= "90px";
		
		$configColumn["19"]["Titulo"] 		= "M.Proximo Pago";
		$configColumn["19"]["TituloFoot"]	= "";
		$configColumn["19"]["FiledSouce"]	= "montoProximoPago";
		$configColumn["19"]["Colspan"] 		= "1";
		$configColumn["19"]["Formato"] 		= "Number";
		$configColumn["19"]["Total"] 		= True;
		$configColumn["19"]["Alineacion"] 	= "Right";
		$configColumn["19"]["TotalValor"] 	= 0;
		$configColumn["19"]["FiledSoucePrefix"]	= "moneda";
		$configColumn["19"]["Width"]			= "112px";
		
		$configColumn["20"]["Titulo"] 		= "Cancelacion";
		$configColumn["20"]["TituloFoot"]	= "";
		$configColumn["20"]["FiledSouce"]	= "ultimoPagoFecha";
		$configColumn["20"]["Colspan"] 		= "1";
		$configColumn["20"]["Formato"] 		= "Date";
		$configColumn["20"]["Total"] 		= False;
		$configColumn["20"]["Alineacion"] 	= "Left";
		$configColumn["20"]["TotalValor"] 	= 0;
		$configColumn["20"]["FiledSoucePrefix"]	= "";
		$configColumn["20"]["Width"]			= "90px";
		
		
		$configColumn["21"]["Titulo"] 		= "Ultima Cuota";
		$configColumn["21"]["TituloFoot"]	= "";
		$configColumn["21"]["FiledSouce"]	= "lastShareNumber";
		$configColumn["21"]["Colspan"] 		= "1";
		$configColumn["21"]["Formato"] 		= "";
		$configColumn["21"]["Total"] 		= False;
		$configColumn["21"]["Alineacion"] 	= "Left";
		$configColumn["21"]["TotalValor"] 	= 0;
		$configColumn["21"]["FiledSoucePrefix"]	= "";
		$configColumn["21"]["Width"]			= "90px";
		
		$configColumn["22"]["Titulo"] 		= "F.Ultima Cuota";
		$configColumn["22"]["TituloFoot"]	= "";
		$configColumn["22"]["FiledSouce"]	= "dateLastShareNumber";
		$configColumn["22"]["Colspan"] 		= "1";
		$configColumn["22"]["Formato"] 		= "Date";
		$configColumn["22"]["Total"] 		= False;
		$configColumn["22"]["Alineacion"] 	= "Left";
		$configColumn["22"]["TotalValor"] 	= 0;
		$configColumn["22"]["FiledSoucePrefix"]	= "";
		$configColumn["22"]["Width"]			= "125px";
		
		$configColumn["23"]["Titulo"] 		= "M.Ultima Cuota";
		$configColumn["23"]["TituloFoot"]	= "";
		$configColumn["23"]["FiledSouce"]	= "amountLastShareNumber";
		$configColumn["23"]["Colspan"] 		= "1";
		$configColumn["23"]["Formato"] 		= "Number";
		$configColumn["23"]["Total"] 		= True;
		$configColumn["23"]["Alineacion"] 	= "Right";
		$configColumn["23"]["TotalValor"] 	= 0;
		$configColumn["23"]["FiledSoucePrefix"]	= "moneda";
		$configColumn["23"]["Width"]			= "125px";
		
		
		
		$resultado = helper_reporteGeneralCreateTable($objDetail,$configColumn,'0');
		?>
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'CARTERA DE CREDITO',
			$objCompany->name,
			$resultado["columnas"],
			'',
			"",
			"",
			$resultado["width"]
		);
		?>
		
		<br/>	
		
		<?php 
		echo $resultado["table"];
		?>
		
		<br/>		
		<?php 
		echo helper_reporteGeneralCreateFirma(	
			$objFirmaEncription,
			$resultado["columnas"],
			$resultado["width"]
		);
		?>
		
		
	</body>	
	
</html>