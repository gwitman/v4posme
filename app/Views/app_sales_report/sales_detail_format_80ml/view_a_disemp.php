<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $objFirmaEncription; ?></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		
		<style>
			body , html {
				margin:10px;
			}
		</style>
		<?php 
		echo helper_reporteGeneralCreateStyle();
		?>
		
	</head>
	<body style="font-family:monospace;font-size:smaller;margin:0px 0px 0px 0px"> 
		
		
		
		<?php
		$configColumn["0"]["Titulo"] 		= "Factura";				
		$configColumn["1"]["Titulo"] 		= "Cant.";
		$configColumn["2"]["Titulo"] 		= "Total";				
		$configColumn["3"]["Titulo"] 		= "Producto";

		$configColumn["0"]["FiledSouce"] 		= "transactionNumber";				
		$configColumn["1"]["FiledSouce"] 		= "quantity";				
		$configColumn["2"]["FiledSouce"] 		= "amount";		
		$configColumn["3"]["FiledSouce"] 		= "itemName";	

		$configColumn["0"]["Formato"] 		= "";				
		$configColumn["1"]["Formato"] 		= "Number";
		$configColumn["2"]["Formato"] 		= "Number";		
		$configColumn["3"]["Formato"] 		= "";	
		
		$configColumn["0"]["Width"] 		= "80px";					
		$configColumn["1"]["Width"] 		= "50px";				
		$configColumn["2"]["Width"] 		= "50px";			
		$configColumn["3"]["Width"] 		= "130px";	
		
		
		$configColumn["0"]["Total"] 		= False;						
		$configColumn["1"]["Total"] 		= False;				
		$configColumn["2"]["Total"] 		= True;		
		$configColumn["3"]["Total"] 		= False;	
		
		$resultado 	= helper_reporteGeneralCreateTable($objDetail,$configColumn,'0px',NULL,NULL);
		?>
		
		
		
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'DETALLE DE VENTA F80MM',
			$objCompany->name,
			$resultado["columnas"],
			'VENTAS DEL '.$objStartOn.' AL '.$objEndOn,
			"",
			"",
			$resultado["width"],
			true
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