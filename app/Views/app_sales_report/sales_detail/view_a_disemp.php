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
		$configColumn["5"]["Titulo"] 		= "Precio unitario";
		$configColumn["6"]["Titulo"] 		= "Cantidad";
		$configColumn["7"]["Titulo"] 		= "Costo unitario";
		$configColumn["8"]["Titulo"] 		= "Costo Total";
		$configColumn["9"]["Titulo"] 		= "Precio Total";
		$configColumn["10"]["Titulo"] 		= "Utilidad";
		$configColumn["11"]["Titulo"] 		= "Utilidad %";		
		$configColumn["12"]["Titulo"] 		= "Codigo Producto";
		$configColumn["13"]["Titulo"] 		= "Producto";
		$configColumn["14"]["Titulo"] 		= "Categoria";
		$configColumn["15"]["Titulo"] 		= "Vendedor";
		$configColumn["16"]["Titulo"] 		= "IVA";
		$configColumn["17"]["Titulo"] 		= "Precio Con Iva";
		$configColumn["18"]["Titulo"] 		= "Comision";

		$configColumn["0"]["FiledSouce"] 		= "transactionNumber";		
		$configColumn["1"]["FiledSouce"] 		= "tipo";		
		$configColumn["2"]["FiledSouce"] 		= "transactionOn";		
		$configColumn["3"]["FiledSouce"] 		= "customerNumber";		
		$configColumn["4"]["FiledSouce"] 		= "legalName";		
		$configColumn["5"]["FiledSouce"] 		= "unitaryPrice";
		$configColumn["6"]["FiledSouce"] 		= "quantity";
		$configColumn["7"]["FiledSouce"] 		= "unitaryCost";
		$configColumn["8"]["FiledSouce"] 		= "cost";
		$configColumn["9"]["FiledSouce"] 		= "amount";
		$configColumn["10"]["FiledSouce"] 		= "utilidad";
		$configColumn["11"]["FiledSouce"] 		= "utilidad_porcentual";
		$configColumn["12"]["FiledSouce"] 		= "itemNumber";
		$configColumn["13"]["FiledSouce"] 		= "itemName";	
		$configColumn["14"]["FiledSouce"] 		= "nameCategory";
		$configColumn["15"]["FiledSouce"] 		= "employerName";
		$configColumn["16"]["FiledSouce"] 		= "ivaTotal";
		$configColumn["17"]["FiledSouce"] 		= "amountConIva";
		$configColumn["18"]["FiledSouce"] 		= "amountCommision";
		
		$configColumn["0"]["Formato"] 		= "";		
		$configColumn["1"]["Formato"] 		= "";		
		$configColumn["2"]["Formato"] 		= "Date";		
		$configColumn["3"]["Formato"] 		= "";		
		$configColumn["4"]["Formato"] 		= "";		
		$configColumn["5"]["Formato"] 		= "Number";
		$configColumn["6"]["Formato"] 		= "Number";
		$configColumn["7"]["Formato"] 		= "Number";
		$configColumn["8"]["Formato"] 		= "Number";
		$configColumn["9"]["Formato"] 		= "Number";
		$configColumn["10"]["Formato"] 		= "Number";
		$configColumn["11"]["Formato"] 		= "Number";
		$configColumn["12"]["Formato"] 		= "";
		$configColumn["13"]["Formato"] 		= "";	
		$configColumn["14"]["Formato"] 		= "";	
		$configColumn["15"]["Formato"] 		= "";
		$configColumn["16"]["Formato"] 		= "Number";
		$configColumn["17"]["Formato"] 		= "Number";
		$configColumn["18"]["Formato"] 		= "Number";
		
		$configColumn["0"]["Width"] 		= "80px";		
		$configColumn["1"]["Width"] 		= "80px";		
		$configColumn["2"]["Width"] 		= "80px";		
		$configColumn["3"]["Width"] 		= "80px";		
		$configColumn["4"]["Width"] 		= "250px";		
		$configColumn["5"]["Width"] 		= "100px";
		$configColumn["6"]["Width"] 		= "100px";
		$configColumn["7"]["Width"] 		= "100px";
		$configColumn["8"]["Width"] 		= "140px";
		$configColumn["9"]["Width"] 		= "140px";
		$configColumn["10"]["Width"] 		= "140px";
		$configColumn["11"]["Width"] 		= "140px";
		$configColumn["12"]["Width"] 		= "100px";
		$configColumn["13"]["Width"] 		= "220px";	
		$configColumn["14"]["Width"] 		= "220px";	
		$configColumn["15"]["Width"] 		= "250px";
		$configColumn["16"]["Width"] 		= "100px";
		$configColumn["17"]["Width"] 		= "100px";
		$configColumn["18"]["Width"] 		= "100px";
		
		$configColumn["0"]["Total"] 		= False;		
		$configColumn["1"]["Total"] 		= False;		
		$configColumn["2"]["Total"] 		= False;		
		$configColumn["3"]["Total"] 		= False;		
		$configColumn["4"]["Total"] 		= False;		
		$configColumn["5"]["Total"] 		= False;
		$configColumn["6"]["Total"] 		= False;
		$configColumn["7"]["Total"] 		= False;
		$configColumn["8"]["Total"] 		= True;
		$configColumn["9"]["Total"] 		= True;
		$configColumn["10"]["Total"] 		= True;
		$configColumn["11"]["Total"] 		= False;
		
		$configColumn["12"]["Total"] 		= False;
		$configColumn["13"]["Total"] 		= False;	
		$configColumn["14"]["Total"] 		= False;	
		$configColumn["15"]["Total"] 		= False;
		$configColumn["16"]["Total"] 		= True;
		$configColumn["17"]["Total"] 		= True;
		$configColumn["18"]["Total"] 		= True;
		
		$configColumn["11"]["Promedio"] 	= True;
		
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