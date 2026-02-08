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
		$index_transactionNumber 			= 0;			
		$index_transactionOn 				= 1;	
		$index_createdByName 				= 2;
		$index_currencyName 				= 3;
		$index_causalName 					= 4;
		$index_productName 					= 5;
		$index_firstName 					= 6;
		$index_productNumber 				= 7;
		$index_transactionQuantity 			= 8;
		$index_transactionPrice 			= 9;
		$index_transactionPrice1 			= 10;
		$index_transactionDifPrice 			= 11;
		$index_transactionDifPriceTotal 	= 12;
		
		$configColumn[$index_transactionNumber]["Titulo"] 		= "Factura";
		$configColumn[$index_transactionNumber]["FiledSouce"] 	= "transactionNumber";	
		$configColumn[$index_transactionNumber]["Formato"] 		= "";
		$configColumn[$index_transactionNumber]["Width"] 		= "80px";
		$configColumn[$index_transactionNumber]["Total"] 		= False;
		
		$configColumn[$index_transactionOn]["Titulo"] 		= "Fecha";	
		$configColumn[$index_transactionOn]["FiledSouce"] 	= "transactionOn";	
		$configColumn[$index_transactionOn]["Formato"] 		= "Date";			
		$configColumn[$index_transactionOn]["Width"] 		= "80px";
		$configColumn[$index_transactionOn]["Total"] 		= False;
		
		$configColumn[$index_createdByName]["Titulo"] 		= "Vendedor";
		$configColumn[$index_createdByName]["FiledSouce"] 	= "createdByName";
		$configColumn[$index_createdByName]["Formato"] 		= "";
		$configColumn[$index_createdByName]["Width"] 		= "220px";
		$configColumn[$index_createdByName]["Total"] 		= False;
		
		$configColumn[$index_currencyName]["Titulo"] 		= "Moneda";
		$configColumn[$index_currencyName]["FiledSouce"] 	= "currencyName";
		$configColumn[$index_currencyName]["Formato"] 		= "";
		$configColumn[$index_currencyName]["Width"] 		= "80px";
		$configColumn[$index_currencyName]["Total"] 		= False;
		
		$configColumn[$index_causalName]["Titulo"] 		= "Tipo";
		$configColumn[$index_causalName]["FiledSouce"] 	= "causalName";	
		$configColumn[$index_causalName]["Formato"] 		= "";
		$configColumn[$index_causalName]["Width"] 		= "80px";
		$configColumn[$index_causalName]["Total"] 		= False;
		
		$configColumn[$index_productName]["Titulo"] 		= "Producto";	
		$configColumn[$index_productName]["FiledSouce"] 	= "productName";	
		$configColumn[$index_productName]["Formato"] 		= "";		
		$configColumn[$index_productName]["Width"] 		= "220px";
		$configColumn[$index_productName]["Total"] 		= False;
		
		$configColumn[$index_firstName]["Titulo"] 		= "Agente";
		$configColumn[$index_firstName]["FiledSouce"] 	= "firstName";
		$configColumn[$index_firstName]["Formato"] 		= "";
		$configColumn[$index_firstName]["Width"] 		= "220px";
		$configColumn[$index_firstName]["Total"] 		= False;
		
		$configColumn[$index_productNumber]["Titulo"] 		= "Producto Codigo";
		$configColumn[$index_productNumber]["FiledSouce"] 	= "productNumber";
		$configColumn[$index_productNumber]["Formato"] 		= "";
		$configColumn[$index_productNumber]["Width"] 		= "80px";
		$configColumn[$index_productNumber]["Total"] 		= False;
		
		$configColumn[$index_transactionQuantity]["Titulo"] 		= "Cantidad";
		$configColumn[$index_transactionQuantity]["FiledSouce"] 	= "transactionQuantity";
		$configColumn[$index_transactionQuantity]["Formato"] 		= "";
		$configColumn[$index_transactionQuantity]["Width"] 		= "80px";
		$configColumn[$index_transactionQuantity]["Total"] 		= False;
		
		
		$configColumn[$index_transactionPrice]["Titulo"] 		= "Precio vendido";
		$configColumn[$index_transactionPrice]["FiledSouce"] 	= "transactionPrice";
		$configColumn[$index_transactionPrice]["Formato"] 		= "";
		$configColumn[$index_transactionPrice]["Width"] 		= "150px";
		$configColumn[$index_transactionPrice]["Total"] 		= False;
		
		$configColumn[$index_transactionPrice1]["Titulo"] 		= "Precio standar";
		$configColumn[$index_transactionPrice1]["FiledSouce"] 	= "transactionPrice1";
		$configColumn[$index_transactionPrice1]["Formato"] 		= "";
		$configColumn[$index_transactionPrice1]["Width"] 		= "150px";
		$configColumn[$index_transactionPrice1]["Total"] 		= False;
		
		$configColumn[$index_transactionDifPrice]["Titulo"] 		= "Precios diferencia";
		$configColumn[$index_transactionDifPrice]["FiledSouce"] 	= "transactionDifPrice";
		$configColumn[$index_transactionDifPrice]["Formato"] 		= "";
		$configColumn[$index_transactionDifPrice]["Width"] 		= "150px";
		$configColumn[$index_transactionDifPrice]["Total"] 		= False;
		
		$configColumn[$index_transactionDifPriceTotal]["Titulo"] 		= "Total Diferencia";
		$configColumn[$index_transactionDifPriceTotal]["FiledSouce"] 	= "transactionDifPriceTotal";
		$configColumn[$index_transactionDifPriceTotal]["Formato"] 		= "";
		$configColumn[$index_transactionDifPriceTotal]["Width"] 		= "150px";
		$configColumn[$index_transactionDifPriceTotal]["Total"] 		= True;
		
		$objDetail 	= array_values(
			array_filter($objDetail, function($item) {
				return $item["transactionDifPrice"] > 0;
			})
		);
		$resultado 	= helper_reporteGeneralCreateTable($objDetail,$configColumn,'0px',NULL,NULL);
		?>
		
		
		
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'PRECIOS FUERAS DE RANGO',
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