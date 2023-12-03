<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		
		<?php 
		echo helper_reporteGeneralCreateStyle();
		?>
		
	</head>
	<body style="font-family:monospace;font-size:smaller;margin:0px 0px 0px 0px"> 
	
		
		<?php
		$configColumn["0"]["Titulo"] 		= "Tipo";		
		$configColumn["1"]["Titulo"] 		= "Codigo";		
		$configColumn["2"]["Titulo"] 		= "Producto";		
		$configColumn["3"]["Titulo"] 		= "Cantidad";		
		$configColumn["4"]["Titulo"] 		= "Costo promedio";		
		$configColumn["5"]["Titulo"] 		= "Utilidad";


		$configColumn["0"]["FiledSouce"] 		= "nameTransaction";		
		$configColumn["1"]["FiledSouce"] 		= "itemNumber";		
		$configColumn["2"]["FiledSouce"] 		= "itemName";		
		$configColumn["3"]["FiledSouce"] 		= "Cantidad";		
		$configColumn["4"]["FiledSouce"] 		= "CostoPromedio";		
		$configColumn["5"]["FiledSouce"] 		= "Utilidad";	

		$configColumn["0"]["Formato"] 		= "";		
		$configColumn["1"]["Formato"] 		= "";		
		$configColumn["2"]["Formato"] 		= "";				
		$configColumn["3"]["Formato"] 		= "Number";
		$configColumn["4"]["Formato"] 		= "Number";
		$configColumn["5"]["Formato"] 		= "Number";
		
		$configColumn["0"]["Width"] 		= "150";		
		$configColumn["1"]["Width"] 		= "80";		
		$configColumn["2"]["Width"] 		= "150";		
		$configColumn["3"]["Width"] 		= "120";		
		$configColumn["4"]["Width"] 		= "120";		
		$configColumn["5"]["Width"] 		= "120";	
		
		
		$configColumn["0"]["Total"] 		= False;		
		$configColumn["1"]["Total"] 		= False;		
		$configColumn["2"]["Total"] 		= False;		
		$configColumn["3"]["Total"] 		= False;		
		$configColumn["4"]["Total"] 		= False;		
		$configColumn["5"]["Total"] 		= False;
		
		$resultado 	= helper_reporteGeneralCreateTable($objDetail,$configColumn,'0px',NULL,NULL);
		?>
		
		
		
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'RESUMEN POR TRANSACCION',
			$objCompany->name,
			$resultado["columnas"],
			'DEL '.$objStartOn.' AL '.$objEndOn,
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