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
		
		
		if($objCompany->type == "globalpro")
		$configColumn["0"]["FiltrarRegistroOnLine"] = False;	
		else
		$configColumn["0"]["FiltrarRegistroOnLine"] = True;	
		
		$configColumn["0"]["Titulo"] 		= "Factura";	
		$configColumn["1"]["Titulo"] 		= "Tipo";		
		$configColumn["2"]["Titulo"] 		= "Fecha";		
		$configColumn["3"]["Titulo"] 		= "Cod Cliente";		
		$configColumn["4"]["Titulo"] 		= "Cliente";	
		$configColumn["5"]["Titulo"] 		= "Producto";	
		$configColumn["6"]["Titulo"] 		= "Codigo Producto";		
		$configColumn["7"]["Titulo"] 		= "Precio unitario";
		$configColumn["8"]["Titulo"] 		= "Cantidad";
		$configColumn["9"]["Titulo"] 		= "Costo unitario";
		$configColumn["10"]["Titulo"] 		= "Costo Total";
		$configColumn["11"]["Titulo"] 		= "Precio Total";
		$configColumn["12"]["Titulo"] 		= "Utilidad";
		$configColumn["13"]["Titulo"] 		= "Categoria";
		$configColumn["14"]["Titulo"] 		= "Vendedor";
		$configColumn["15"]["Titulo"] 		= "IVA";
		$configColumn["16"]["Titulo"] 		= "Precio Con Iva";
		$configColumn["17"]["Titulo"] 		= "Comision";

		$configColumn["0"]["FiledSouce"] 		= "transactionNumber";		
		$configColumn["1"]["FiledSouce"] 		= "tipo";		
		$configColumn["2"]["FiledSouce"] 		= "transactionOn";		
		$configColumn["3"]["FiledSouce"] 		= "customerNumber";		
		$configColumn["4"]["FiledSouce"] 		= "legalName";		
		$configColumn["5"]["FiledSouce"] 		= "itemName";	
		$configColumn["6"]["FiledSouce"] 		= "itemNumber";
		$configColumn["7"]["FiledSouce"] 		= "unitaryPrice";
		$configColumn["8"]["FiledSouce"] 		= "quantity";
		$configColumn["9"]["FiledSouce"] 		= "unitaryCost";
		$configColumn["10"]["FiledSouce"] 		= "cost";
		$configColumn["11"]["FiledSouce"] 		= "amount";
		$configColumn["12"]["FiledSouce"] 		= "utilidad";
		$configColumn["13"]["FiledSouce"] 		= "nameCategory";
		$configColumn["14"]["FiledSouce"] 		= "employerName";
		$configColumn["15"]["FiledSouce"] 		= "ivaTotal";
		$configColumn["16"]["FiledSouce"] 		= "amountConIva";
		$configColumn["17"]["FiledSouce"] 		= "amountCommision";
		
		$configColumn["0"]["Formato"] 		= "";		
		$configColumn["1"]["Formato"] 		= "";		
		$configColumn["2"]["Formato"] 		= "Date";		
		$configColumn["3"]["Formato"] 		= "";		
		$configColumn["4"]["Formato"] 		= "";	
		$configColumn["5"]["Formato"] 		= "";	
		$configColumn["6"]["Formato"] 		= "";		
		$configColumn["7"]["Formato"] 		= "Number";
		$configColumn["8"]["Formato"] 		= "Number";
		$configColumn["9"]["Formato"] 		= "Number";
		$configColumn["10"]["Formato"] 		= "Number";
		$configColumn["11"]["Formato"] 		= "Number";
		$configColumn["12"]["Formato"] 		= "Number";
		$configColumn["13"]["Formato"] 		= "";	
		$configColumn["14"]["Formato"] 		= "";
		$configColumn["15"]["Formato"] 		= "Number";
		$configColumn["16"]["Formato"] 		= "Number";
		$configColumn["17"]["Formato"] 		= "Number";
		
		$configColumn["0"]["Width"] 		= "80px";		
		$configColumn["1"]["Width"] 		= "80px";		
		$configColumn["2"]["Width"] 		= "80px";		
		$configColumn["3"]["Width"] 		= "80px";		
		$configColumn["4"]["Width"] 		= "250px";	
		$configColumn["5"]["Width"] 		= "220px";	
		$configColumn["6"]["Width"] 		= "100px";		
		$configColumn["7"]["Width"] 		= "100px";
		$configColumn["8"]["Width"] 		= "100px";
		$configColumn["9"]["Width"] 		= "100px";
		$configColumn["10"]["Width"] 		= "100px";
		$configColumn["11"]["Width"] 		= "100px";
		$configColumn["12"]["Width"] 		= "100px";
		$configColumn["13"]["Width"] 		= "220px";	
		$configColumn["14"]["Width"] 		= "250px";
		$configColumn["15"]["Width"] 		= "100px";
		$configColumn["16"]["Width"] 		= "100px";
		$configColumn["17"]["Width"] 		= "100px";
		
		$configColumn["0"]["Total"] 		= False;		
		$configColumn["1"]["Total"] 		= False;		
		$configColumn["2"]["Total"] 		= False;		
		$configColumn["3"]["Total"] 		= False;		
		$configColumn["4"]["Total"] 		= False;
		$configColumn["5"]["Total"] 		= False;
		$configColumn["6"]["Total"] 		= False;		
		$configColumn["7"]["Total"] 		= False;
		$configColumn["8"]["Total"] 		= False;
		$configColumn["9"]["Total"] 		= False;
		$configColumn["10"]["Total"] 		= True;
		$configColumn["11"]["Total"] 		= True;
		$configColumn["12"]["Total"] 		= True;
		$configColumn["13"]["Total"] 		= False;	
		$configColumn["14"]["Total"] 		= False;
		$configColumn["15"]["Total"] 		= True;
		$configColumn["16"]["Total"] 		= True;
		$configColumn["17"]["Total"] 		= True;
		
		$resultado 	= helper_reporteGeneralCreateTable($objDetail,$configColumn,'0px',NULL,NULL);
		?>
		
		
		
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'DETALLE DE VENTA',
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