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
		$configColumn["2"]["Titulo"] 		= "Fecha";		
		$configColumn["14"]["Titulo"] 		= "Vendedor";
		$configColumn["13"]["Titulo"] 		= "Telefono";
		$configColumn["4"]["Titulo"] 		= "Cliente";
		$configColumn["12"]["Titulo"] 		= "Producto";	
		$configColumn["15"]["Titulo"] 		= "Agente";
		$configColumn["16"]["Titulo"] 		= "Nota";
		
		$configColumn["0"]["FiledSouce"] 		= "transactionNumber";				
		$configColumn["2"]["FiledSouce"] 		= "transactionOn";		
		$configColumn["14"]["FiledSouce"] 		= "employerName";
		$configColumn["13"]["FiledSouce"] 		= "phoneNumber";
		$configColumn["4"]["FiledSouce"] 		= "legalName";		
		$configColumn["12"]["FiledSouce"] 		= "itemNameLog";	
		$configColumn["15"]["FiledSouce"] 		= "Agent";
		$configColumn["16"]["FiledSouce"] 		= "Commentary";

		$configColumn["0"]["Formato"] 		= "";				
		$configColumn["2"]["Formato"] 		= "Date";	
		$configColumn["14"]["Formato"] 		= "";
		$configColumn["13"]["Formato"] 		= "";
		$configColumn["4"]["Formato"] 		= "";		
		$configColumn["12"]["Formato"] 		= "";	
		$configColumn["15"]["Formato"] 		= "";
		$configColumn["16"]["Formato"] 		= "";
		
		
		$configColumn["0"]["Width"] 		= "80px";				
		$configColumn["2"]["Width"] 		= "80px";		
		$configColumn["14"]["Width"] 		= "250px";
		$configColumn["13"]["Width"] 		= "250px";
		$configColumn["4"]["Width"] 		= "250px";
		$configColumn["12"]["Width"] 		= "220px";	
		$configColumn["15"]["Width"] 		= "250px";
		$configColumn["16"]["Width"] 		= "250px";
		
		
		$configColumn["0"]["Total"] 		= False;
		$configColumn["2"]["Total"] 		= False;		
		$configColumn["14"]["Total"] 		= False;
		$configColumn["13"]["Total"] 		= False;
		$configColumn["4"]["Total"] 		= False;
		$configColumn["12"]["Total"] 		= False;	
		$configColumn["15"]["Total"] 		= False;
		$configColumn["16"]["Total"] 		= False;
		
		$resultado 	= helper_reporteGeneralCreateTable($objDetail,$configColumn,'0px',NULL,NULL);
		?>
		
		
		
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'SEGUIMIENTO POS VENTA',
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