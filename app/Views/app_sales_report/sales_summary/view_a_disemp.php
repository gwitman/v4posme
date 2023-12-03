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
		$configColumn["0"]["Titulo"] 		= "Factura";		
		$configColumn["1"]["Titulo"] 		= "Fecha";	
		$configColumn["2"]["Titulo"] 		= "Tipo";					
		$configColumn["3"]["Titulo"] 		= "Cod Cliente";		
		$configColumn["4"]["Titulo"] 		= "Cliente";		
		$configColumn["5"]["Titulo"] 		= "Sub total";	
		$configColumn["6"]["Titulo"] 		= "Iva";	
		$configColumn["7"]["Titulo"] 		= "Total";


		$configColumn["0"]["FiledSouce"] 		= "transactionNumber";		
		$configColumn["1"]["FiledSouce"] 		= "transactionOn";		
		$configColumn["2"]["FiledSouce"] 		= "tipo";	
		$configColumn["3"]["FiledSouce"] 		= "customerNumber";		
		$configColumn["4"]["FiledSouce"] 		= "legalName";		
		$configColumn["5"]["FiledSouce"] 		= "totalSinIva";		
		$configColumn["6"]["FiledSouce"] 		= "totalIva";		
		$configColumn["7"]["FiledSouce"] 		= "totalDocument";
		
		$configColumn["0"]["Formato"] 		= "";		
		$configColumn["1"]["Formato"] 		= "Date";	
		$configColumn["2"]["Formato"] 		= "";					
		$configColumn["3"]["Formato"] 		= "";		
		$configColumn["4"]["Formato"] 		= "";		
		$configColumn["5"]["Formato"] 		= "Number";
		$configColumn["6"]["Formato"] 		= "Number";
		$configColumn["7"]["Formato"] 		= "Number";
		
		$configColumn["0"]["Width"] 		= "100px";		
		$configColumn["1"]["Width"] 		= "100px";		
		$configColumn["2"]["Width"] 		= "100px";		
		$configColumn["3"]["Width"] 		= "100px";		
		$configColumn["4"]["Width"] 		= "250px";		
		$configColumn["5"]["Width"] 		= "100px";
		$configColumn["6"]["Width"] 		= "100px";
		$configColumn["7"]["Width"] 		= "100px";
		
		$configColumn["0"]["Total"] 		= False;		
		$configColumn["1"]["Total"] 		= False;		
		$configColumn["2"]["Total"] 		= False;		
		$configColumn["3"]["Total"] 		= False;		
		$configColumn["4"]["Total"] 		= False;		
		$configColumn["5"]["Total"] 		= True;
		$configColumn["6"]["Total"] 		= True;
		$configColumn["7"]["Total"] 		= True;
		
		$resultado 	= helper_reporteGeneralCreateTable($objDetail,$configColumn,'0px',NULL,NULL);
		?>
		
		
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'RESUMEN DE VENTA',
			$objCompany->name,
			$resultado["columnas"],
			'VENTAS DEL '.$objStartOn.' AL '.$objEndOn,
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