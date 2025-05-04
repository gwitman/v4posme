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
		
		$configColumn["0"]["IdTable"] 				= "myReport";		
		$configColumn["0"]["FiltrarRegistroOnLine"] = True;			
		$configColumn["0"]["Titulo"] 				= "Factura";	
		$configColumn["0"]["FiledSouce"] 			= "transactionNumber";		
		$configColumn["0"]["Formato"] 				= "";		
		$configColumn["0"]["Width"] 				= "80px";		
		$configColumn["0"]["Total"] 				= False;	
		
		
		$configColumn["2"]["Titulo"] 			= "Fecha";	
		$configColumn["2"]["FiledSouce"] 		= "transactionOn";		
		$configColumn["2"]["Formato"] 			= "Date";	
		$configColumn["2"]["Width"] 			= "80px";		
		$configColumn["2"]["Total"] 			= False;	
		
		$configColumn["3"]["Titulo"] 			= "Cod Proveedor";	
		$configColumn["3"]["FiledSouce"] 		= "providerNumber";		
		$configColumn["3"]["Formato"] 			= "";	
		$configColumn["3"]["Width"] 			= "80px";		
		$configColumn["3"]["Total"] 			= False;	
		
		$configColumn["4"]["Titulo"] 			= "Proveedor";		
		$configColumn["4"]["FiledSouce"] 		= "legalName";	
		$configColumn["4"]["Formato"] 			= "";		
		$configColumn["4"]["Width"] 			= "250px";		
		$configColumn["4"]["Total"] 			= False;
		
		$configColumn["5"]["Titulo"] 			= "Precio unitario";
		$configColumn["5"]["FiledSouce"] 		= "unitaryPrice";
		$configColumn["5"]["Formato"] 			= "Number";
		$configColumn["5"]["Width"] 			= "100px";
		$configColumn["5"]["Total"] 			= False;
		
		$configColumn["6"]["Titulo"] 			= "Cantidad";
		$configColumn["6"]["FiledSouce"] 		= "quantity";
		$configColumn["6"]["Formato"] 			= "Number";
		$configColumn["6"]["Width"] 			= "100px";
		$configColumn["6"]["Total"] 			= False;
		
		$configColumn["7"]["Titulo"] 			= "Costo unitario";
		$configColumn["7"]["FiledSouce"] 		= "unitaryCost";
		$configColumn["7"]["Formato"] 			= "Number";
		$configColumn["7"]["Width"] 			= "100px";
		$configColumn["7"]["Total"] 			= False;
		
		$configColumn["8"]["Titulo"] 			= "Costo Total";
		$configColumn["8"]["FiledSouce"] 		= "cost";
		$configColumn["8"]["Formato"] 			= "Number";
		$configColumn["8"]["Width"] 			= "100px";
		$configColumn["8"]["Total"] 			= True;
		
		$configColumn["9"]["Titulo"] 			= "Precio Total";
		$configColumn["9"]["FiledSouce"] 		= "amount";
		$configColumn["9"]["Formato"] 			= "Number";
		$configColumn["9"]["Width"] 			= "100px";
		$configColumn["9"]["Total"] 			= True;
		
		$configColumn["11"]["Titulo"] 			= "Codigo Producto";
		$configColumn["11"]["FiledSouce"] 		= "itemNumber";
		$configColumn["11"]["Formato"] 			= "";
		$configColumn["11"]["Width"] 			= "100px";
		$configColumn["11"]["Total"] 			= False;
		
		$configColumn["12"]["Titulo"] 			= "Producto";
		$configColumn["12"]["FiledSouce"] 		= "itemName";	
		$configColumn["12"]["Formato"] 			= "";	
		$configColumn["12"]["Width"] 			= "220px";	
		$configColumn["12"]["Total"] 			= False;
		
		$configColumn["13"]["Titulo"] 			= "Categoria";
		$configColumn["13"]["FiledSouce"] 		= "nameCategory";
		$configColumn["13"]["Formato"] 			= "";	
		$configColumn["13"]["Width"] 			= "220px";	
		$configColumn["13"]["Total"] 			= False;
		
		$configColumn["14"]["Titulo"] 			= "Expiracion";
		$configColumn["14"]["FiledSouce"] 		= "expirationDate";
		$configColumn["14"]["Formato"] 			= "Date";	
		$configColumn["14"]["Width"] 			= "80px";		
		$configColumn["14"]["Total"] 			= False;	
		
		
		
		$resultado 	= helper_reporteGeneralCreateTable($objDetail,$configColumn,'0px',NULL,NULL);
		?>
		
		
		
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'DETALLE DE COMPRAS',
			$objCompany->name,
			$resultado["columnas"],
			'COMPRAS DEL '.$objStartOn.' AL '.$objEndOn,
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