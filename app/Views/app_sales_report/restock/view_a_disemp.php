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
		$configColumn["2"]["Titulo"] 		= "Familia";
		$configColumn["3"]["Titulo"] 		= "Proveedor";
		$configColumn["4"]["Titulo"] 		= "Producto";
		$configColumn["5"]["Titulo"] 		= "Cantidad Inicial";
		$configColumn["6"]["Titulo"] 		= "Cantidad Vendida";		
		$configColumn["7"]["Titulo"] 		= "Cantidad Actual";
		$configColumn["8"]["Titulo"] 		= "Porcentaje";
		
		
		$configColumn["1"]["FiledSouce"] 		= "itemNumber";
		$configColumn["2"]["FiledSouce"] 		= "family";
		$configColumn["3"]["FiledSouce"] 		= "provider";
		$configColumn["4"]["FiledSouce"] 		= "itemName";	
		$configColumn["5"]["FiledSouce"] 		= "quantityInicial";
		$configColumn["6"]["FiledSouce"] 		= "quantity";		
		$configColumn["7"]["FiledSouce"] 		= "quantityInAllWarehouse";
		$configColumn["8"]["FiledSouce"] 		= "percentageSales";
		
		
		$configColumn["1"]["Formato"] 		= "";
		$configColumn["2"]["Formato"] 		= "";
		$configColumn["3"]["Formato"] 		= "";		
		$configColumn["4"]["Formato"] 		= "";		
		$configColumn["5"]["Formato"] 		= "Number";		
		$configColumn["6"]["Formato"] 		= "Number";		
		$configColumn["7"]["Formato"] 		= "Number";		
		$configColumn["8"]["Formato"] 		= "Number";
		
		
		$configColumn["1"]["Width"] 		= "100px";
		$configColumn["2"]["Width"] 		= "220px";	
		$configColumn["3"]["Width"] 		= "220px";
		$configColumn["4"]["Width"] 		= "220px";		
		$configColumn["5"]["Width"] 		= "100px";
		$configColumn["6"]["Width"] 		= "100px";
		$configColumn["7"]["Width"] 		= "100px";
		$configColumn["8"]["Width"] 		= "100px";
		
		
		$configColumn["1"]["Total"] 		= False;
		$configColumn["2"]["Total"] 		= False;	
		$configColumn["3"]["Total"] 		= False;		
		$configColumn["4"]["Total"] 		= False;
		$configColumn["5"]["Total"] 		= False;
		$configColumn["6"]["Total"] 		= False;
		$configColumn["7"]["Total"] 		= False;
		$configColumn["8"]["Total"] 		= False;
		
		
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