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
		$configColumn["1"]["Titulo"] 		= "Codigo Producto";
		$configColumn["2"]["Titulo"] 		= "Producto";
		$configColumn["3"]["Titulo"] 		= "Cantidad Vendida";		
		$configColumn["4"]["Titulo"] 		= "Cantidad Actual";
		$configColumn["5"]["Titulo"] 		= "Familia";
		$configColumn["6"]["Titulo"] 		= "Cantidad Inicial";
		$configColumn["7"]["Titulo"] 		= "Porcentaje";
		
		
		$configColumn["1"]["FiledSouce"] 		= "itemNumber";
		$configColumn["2"]["FiledSouce"] 		= "itemName";	
		$configColumn["3"]["FiledSouce"] 		= "quantity";		
		$configColumn["4"]["FiledSouce"] 		= "quantityInAllWarehouse";
		$configColumn["5"]["FiledSouce"] 		= "family";
		$configColumn["6"]["FiledSouce"] 		= "quantityInicial";
		$configColumn["7"]["FiledSouce"] 		= "percentageSales";
		
		
		$configColumn["1"]["Formato"] 		= "";
		$configColumn["2"]["Formato"] 		= "";			
		$configColumn["3"]["Formato"] 		= "Number";		
		$configColumn["4"]["Formato"] 		= "Number";
		$configColumn["5"]["Formato"] 		= "";
		$configColumn["6"]["Formato"] 		= "Number";		
		$configColumn["7"]["Formato"] 		= "Number";
		
		
		$configColumn["1"]["Width"] 		= "100px";
		$configColumn["2"]["Width"] 		= "220px";	
		$configColumn["3"]["Width"] 		= "200px";		
		$configColumn["4"]["Width"] 		= "200px";
		$configColumn["5"]["Width"] 		= "300px";
		$configColumn["6"]["Width"] 		= "300px";
		$configColumn["7"]["Width"] 		= "300px";
		
		
		$configColumn["1"]["Total"] 		= False;
		$configColumn["2"]["Total"] 		= False;	
		$configColumn["3"]["Total"] 		= False;		
		$configColumn["4"]["Total"] 		= False;
		$configColumn["5"]["Total"] 		= False;
		$configColumn["6"]["Total"] 		= False;
		$configColumn["7"]["Total"] 		= False;
		
		
		$resultado 	= helper_reporteGeneralCreateTable($objDetail,$configColumn,'0px',NULL,NULL);
		?>
		
		
		
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'PRODUCTOS VENDIDOS',
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