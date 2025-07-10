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
		$configColumn["1"]["Titulo"] 		= "Vendedor";
		$configColumn["2"]["Titulo"] 		= "Factura Numero";
		$configColumn["3"]["Titulo"] 		= "Cliente";		
		$configColumn["4"]["Titulo"] 		= "Monto";
			
		$configColumn["1"]["FiledSouce"] 		= "employerName";
		$configColumn["2"]["FiledSouce"] 		= "transactionNumber";	
		$configColumn["3"]["FiledSouce"] 		= "legalName";		
		$configColumn["4"]["FiledSouce"] 		= "amountConIva";
			
		$configColumn["1"]["Formato"] 		= "";
		$configColumn["2"]["Formato"] 		= "";			
		$configColumn["3"]["Formato"] 		= "";		
		$configColumn["4"]["Formato"] 		= "Number";
				
		$configColumn["1"]["Width"] 		= "220px";
		$configColumn["2"]["Width"] 		= "100px";	
		$configColumn["3"]["Width"] 		= "220px";		
		$configColumn["4"]["Width"] 		= "220px";				
		
		$configColumn["1"]["Total"] 		= False;
		$configColumn["2"]["Total"] 		= False;	
		$configColumn["3"]["Total"] 		= False;		
		$configColumn["4"]["Total"] 		= True;	
		
		$resultado 	= helper_reporteGeneralCreateTableGroupByVendors($objDetail,$configColumn,'0px',NULL,NULL);
		?>
					
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'DETALLE DE VENTAS POR REFERIDOS',
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
			4,
			4
		);
		?>		
		
	</body>	
</html>