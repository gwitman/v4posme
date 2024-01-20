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
		$configColumn["1"]["Titulo"] 		= "Tipo";		
		$configColumn["2"]["Titulo"] 		= "Fecha";				
		$configColumn["11"]["Titulo"] 		= "Codigo Producto";
		$configColumn["12"]["Titulo"] 		= "Producto";
		$configColumn["13"]["Titulo"] 		= "Categoria";
		$configColumn["14"]["Titulo"] 		= "Vendedor";
		$configColumn["17"]["Titulo"] 		= "Comision";

		$configColumn["0"]["FiledSouce"] 		= "transactionNumber";		
		$configColumn["1"]["FiledSouce"] 		= "tipo";		
		$configColumn["2"]["FiledSouce"] 		= "transactionOn";				
		$configColumn["11"]["FiledSouce"] 		= "itemNumber";
		$configColumn["12"]["FiledSouce"] 		= "itemName";	
		$configColumn["13"]["FiledSouce"] 		= "nameCategory";
		$configColumn["14"]["FiledSouce"] 		= "employerName";		
		$configColumn["17"]["FiledSouce"] 		= "amountCommision";

		$configColumn["0"]["Formato"] 		= "";		
		$configColumn["1"]["Formato"] 		= "";		
		$configColumn["2"]["Formato"] 		= "Date";				
		$configColumn["11"]["Formato"] 		= "";
		$configColumn["12"]["Formato"] 		= "";	
		$configColumn["13"]["Formato"] 		= "";	
		$configColumn["14"]["Formato"] 		= "";		
		$configColumn["17"]["Formato"] 		= "Number";
		
		$configColumn["0"]["Width"] 		= "80px";
		$configColumn["1"]["Width"] 		= "80px";		
		$configColumn["2"]["Width"] 		= "80px";				
		$configColumn["11"]["Width"] 		= "100px";
		$configColumn["12"]["Width"] 		= "220px";	
		$configColumn["13"]["Width"] 		= "220px";	
		$configColumn["14"]["Width"] 		= "250px";
		$configColumn["17"]["Width"] 		= "100px";
		
		$configColumn["0"]["Total"] 		= False;		
		$configColumn["1"]["Total"] 		= False;		
		$configColumn["2"]["Total"] 		= False;
		$configColumn["11"]["Total"] 		= False;
		$configColumn["12"]["Total"] 		= False;	
		$configColumn["13"]["Total"] 		= False;	
		$configColumn["14"]["Total"] 		= False;
		$configColumn["17"]["Total"] 		= True;
		
		$resultado 	= helper_reporteGeneralCreateTable($objDetail,$configColumn,'0px',NULL,NULL);
		?>
		
		
		
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'DETALLE DE COMMISIONES',
			$objCompany->name,
			$resultado["columnas"],
			'COMISIONES DEL '.$objStartOn.' AL '.$objEndOn,
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