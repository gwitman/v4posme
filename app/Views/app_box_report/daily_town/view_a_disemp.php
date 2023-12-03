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
		$totalCordoba 							= 0;
		$totalDolares 							= 0;
		$configColumnAbonos["0"]["Titulo"] 		= "Fecha";		
		//$configColumnAbonos["0"]["Titulo"] 		= "Codigo";		
		$configColumnAbonos["1"]["Titulo"] 		= "Cliente";		
		//$configColumnAbonos["2"]["Titulo"] 	= "Moneda";				
		$configColumnAbonos["4"]["Titulo"] 		= "Fac";		
		$configColumnAbonos["5"]["Titulo"] 		= "Transaccion";		
		$configColumnAbonos["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnAbonos["7"]["Titulo"] 		= "Estado";		
		$configColumnAbonos["8"]["Titulo"] 		= "Monto";		
		$configColumnAbonos["9"]["Titulo"] 		= "Vendedor";		
		$configColumnAbonos["10"]["Titulo"] 	= "Nota";		
		$configColumnAbonos["11"]["Titulo"] 	= "";		
					 
		$configColumnAbonos["0"]["FiledSouce"] 		= "transactionOn";		
		//$configColumnAbonos["0"]["FiledSouce"] 		= "customerNumber";		
		$configColumnAbonos["1"]["FiledSouce"] 		= "firstName";		
		//$configColumnAbonos["2"]["FiledSouce"] 		= "moneda";				
		$configColumnAbonos["4"]["FiledSouce"] 		= "Fac";		
		$configColumnAbonos["5"]["FiledSouce"] 		= "transactionName";		
		$configColumnAbonos["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnAbonos["7"]["FiledSouce"] 		= "estado";		
		$configColumnAbonos["8"]["FiledSouce"] 		= "montoFac";		
		$configColumnAbonos["9"]["FiledSouce"] 		= "nickname";		
		$configColumnAbonos["10"]["FiledSouce"] 	= "note";	
		$configColumnAbonos["11"]["FiledSouce"] 	= "conceptosName";	
		
		
		
		$configColumnAbonos["0"]["Formato"] 		= "Date";		
		//$configColumnAbonos["0"]["Formato"] 		= "";		
		$configColumnAbonos["1"]["Formato"] 		= "";		
		//$configColumnAbonos["2"]["Formato"] 		= "";		
		$configColumnAbonos["4"]["Formato"] 		= "";		
		$configColumnAbonos["5"]["Formato"] 		= "";		
		$configColumnAbonos["6"]["Formato"] 		= "";		
		$configColumnAbonos["7"]["Formato"] 		= "";		
		$configColumnAbonos["8"]["Formato"] 		= "Number";		
		$configColumnAbonos["9"]["Formato"] 		= "";		
		$configColumnAbonos["10"]["Formato"] 		= "";	
		$configColumnAbonos["11"]["Formato"] 		= "";	
		
		
		$configColumnAbonos["0"]["Width"] 		= "80px";		
		//$configColumnAbonos["0"]["Width"] 		= "80px";		
		$configColumnAbonos["1"]["Width"] 		= "220px";		
		//$configColumnAbonos["2"]["Width"] 		= "85px";	
		$configColumnAbonos["4"]["Width"] 		= "80px";		
		$configColumnAbonos["5"]["Width"] 		= "200px";		
		$configColumnAbonos["6"]["Width"] 		= "80px";		
		$configColumnAbonos["7"]["Width"] 		= "80px";		
		$configColumnAbonos["8"]["Width"] 		= "120px";		
		$configColumnAbonos["9"]["Width"] 		= "120px";		
		$configColumnAbonos["10"]["Width"] 		= "220px";	
		$configColumnAbonos["11"]["Width"] 		= "80px";	
		
		$configColumnAbonos["0"]["Total"] 		= False;	
		//$configColumnAbonos["0"]["Total"] 		= False;		
		$configColumnAbonos["1"]["Total"] 		= False;		
		//$configColumnAbonos["2"]["Total"] 		= False;
		$configColumnAbonos["4"]["Total"] 		= False;		
		$configColumnAbonos["5"]["Total"] 		= False;		
		$configColumnAbonos["6"]["Total"] 		= False;		
		$configColumnAbonos["7"]["Total"] 		= False;		
		$configColumnAbonos["8"]["Total"] 		= True;		
		$configColumnAbonos["9"]["Total"] 		= False;		
		$configColumnAbonos["10"]["Total"] 		= False;	
		$configColumnAbonos["11"]["Total"] 		= False;	
		
		/********************************/
		$configColumnAbonosDolares["0"]["Titulo"] 		= "Fecha";		
		//$configColumnAbonosDolares["0"]["Titulo"] 		= "Codigo";		
		$configColumnAbonosDolares["1"]["Titulo"] 		= "Cliente";		
		//$configColumnAbonosDolares["2"]["Titulo"] 		= "Moneda";	
		$configColumnAbonosDolares["4"]["Titulo"] 		= "Fac";		
		$configColumnAbonosDolares["5"]["Titulo"] 		= "Transaccion";		
		$configColumnAbonosDolares["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnAbonosDolares["7"]["Titulo"] 		= "Estado";		
		$configColumnAbonosDolares["8"]["Titulo"] 		= "Monto";		
		$configColumnAbonosDolares["9"]["Titulo"] 		= "Vendedor";		
		$configColumnAbonosDolares["10"]["Titulo"] 		= "Nota";		
		$configColumnAbonosDolares["11"][""] 			= "";
		
		$configColumnAbonosDolares["0"]["FiledSouce"] 		= "transactionOn";				
		//$configColumnAbonosDolares["0"]["FiledSouce"] 		= "customerNumber";		
		$configColumnAbonosDolares["1"]["FiledSouce"] 		= "firstName";		
		//$configColumnAbonosDolares["2"]["FiledSouce"] 		= "moneda";				
		$configColumnAbonosDolares["4"]["FiledSouce"] 		= "Fac";		
		$configColumnAbonosDolares["5"]["FiledSouce"] 		= "transactionName";		
		$configColumnAbonosDolares["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnAbonosDolares["7"]["FiledSouce"] 		= "estado";		
		$configColumnAbonosDolares["8"]["FiledSouce"] 		= "montoFac";		
		$configColumnAbonosDolares["9"]["FiledSouce"] 		= "nickname";		
		$configColumnAbonosDolares["10"]["FiledSouce"] 		= "note";	
		$configColumnAbonosDolares["11"]["FiledSouce"] 		= "conceptosName";	
		
		$configColumnAbonosDolares["0"]["Formato"] 		= "Date";				
		//$configColumnAbonosDolares["0"]["Formato"] 		= "";		
		$configColumnAbonosDolares["1"]["Formato"] 		= "";		
		//$configColumnAbonosDolares["2"]["Formato"] 		= "";				
		$configColumnAbonosDolares["4"]["Formato"] 		= "";		
		$configColumnAbonosDolares["5"]["Formato"] 		= "";		
		$configColumnAbonosDolares["6"]["Formato"] 		= "";		
		$configColumnAbonosDolares["7"]["Formato"] 		= "";		
		$configColumnAbonosDolares["8"]["Formato"] 		= "Number";		
		$configColumnAbonosDolares["9"]["Formato"] 		= "";		
		$configColumnAbonosDolares["10"]["Formato"] 	= "";	
		$configColumnAbonosDolares["11"]["Formato"] 	= "";	
		
		$configColumnAbonosDolares["0"]["Width"] 		= "80px";				
		//$configColumnAbonosDolares["0"]["Width"] 		= "80px";		
		$configColumnAbonosDolares["1"]["Width"] 		= "220px";		
		//$configColumnAbonosDolares["2"]["Width"] 		= "85px";				
		$configColumnAbonosDolares["4"]["Width"] 		= "80px";		
		$configColumnAbonosDolares["5"]["Width"] 		= "200px";		
		$configColumnAbonosDolares["6"]["Width"] 		= "80px";		
		$configColumnAbonosDolares["7"]["Width"] 		= "80px";		
		$configColumnAbonosDolares["8"]["Width"] 		= "120px";		
		$configColumnAbonosDolares["9"]["Width"] 		= "120px";		
		$configColumnAbonosDolares["10"]["Width"] 		= "220px";	
		$configColumnAbonosDolares["11"]["Width"] 		= "80px";	
		
		$configColumnAbonosDolares["0"]["Total"] 		= False;				
		//$configColumnAbonosDolares["0"]["Total"] 		= False;		
		$configColumnAbonosDolares["1"]["Total"] 		= False;		
		//$configColumnAbonosDolares["2"]["Total"] 		= False;				
		$configColumnAbonosDolares["4"]["Total"] 		= False;		
		$configColumnAbonosDolares["5"]["Total"] 		= False;		
		$configColumnAbonosDolares["6"]["Total"] 		= False;		
		$configColumnAbonosDolares["7"]["Total"] 		= False;		
		$configColumnAbonosDolares["8"]["Total"] 		= True;		
		$configColumnAbonosDolares["9"]["Total"] 		= False;		
		$configColumnAbonosDolares["10"]["Total"] 		= False;	
		$configColumnAbonosDolares["11"]["Total"] 		= False;	
		/*******************************/
		
		$objDetailDolar 	= $objDetail;
		$objDetailCordoba 	= $objDetail;
		
		if($objDetailCordoba != null)
		$objDetailCordoba 	= array_filter($objDetailCordoba,function($var){
			if (strtoupper($var["moneda"]) == "CORDOBA")
				return true;
		});
		
		if($objDetailDolar != null)
		$objDetailDolar 	= array_filter($objDetailDolar,function($var){
			if (strtoupper($var["moneda"]) == "DOLAR")
				return true;
		});
		
		
		$resultadoAbonosCordoba = helper_reporteGeneralCreateTable(
			$objDetailCordoba,
			$configColumnAbonos,
			'0px',
			"CORDOBA = LISTA DE ABONOS/ ABONOS AL CAPITAL / CANCELACION ANTICIPADA "
		);
		
		$resultadoAbonosDolar = helper_reporteGeneralCreateTable(
			$objDetailDolar,
			$configColumnAbonosDolares,
			'0px',
			"DOLARES = LISTA DE ABONOS/ ABONOS AL CAPITAL / CANCELACION ANTICIPADA ",
			'68c778',
			'black'
		);
		
		$totalCordoba = 0 + ($resultadoAbonosCordoba["table"] === 0 ? 0 : $resultadoAbonosCordoba["configColumn"][8]["TotalValor"]);
		$totalDolares = 0 + ($resultadoAbonosDolar["table"] === 0 ? 0 : $resultadoAbonosDolar["configColumn"][8]["TotalValor"]);
		
		
		?>
		
		
		<?php
		
		$configColumnVentaContado["0"]["Titulo"] 		= "Fecha";		
		//$configColumnVentaContado["0"]["Titulo"] 		= "Codigo";		
		$configColumnVentaContado["1"]["Titulo"] 		= "Cliente";		
		//$configColumnVentaContado["2"]["Titulo"] 		= "Moneda";		
		$configColumnVentaContado["4"]["Titulo"] 		= "Fac";		
		$configColumnVentaContado["5"]["Titulo"] 		= "Transaccion";		
		$configColumnVentaContado["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnVentaContado["7"]["Titulo"] 		= "Estado";		
		$configColumnVentaContado["8"]["Titulo"] 		= "Monto";		
		$configColumnVentaContado["9"]["Titulo"] 		= "Vendedor";		
		$configColumnVentaContado["10"]["Titulo"] 		= "Nota";	
		$configColumnVentaContado["11"]["Titulo"] 		= "Categoria";	
		
		
		$configColumnVentaContado["0"]["FiledSouce"] 		= "transactionOn";				
		//$configColumnVentaContado["0"]["FiledSouce"] 		= "customerNumber";		
		$configColumnVentaContado["1"]["FiledSouce"] 		= "firstName";		
		//$configColumnVentaContado["2"]["FiledSouce"] 		= "currencyName";				
		$configColumnVentaContado["4"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnVentaContado["5"]["FiledSouce"] 		= "tipo";		
		$configColumnVentaContado["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnVentaContado["7"]["FiledSouce"] 		= "statusName";		
		$configColumnVentaContado["8"]["FiledSouce"] 		= "totalDocument";		
		$configColumnVentaContado["9"]["FiledSouce"] 		= "nickname";		
		$configColumnVentaContado["10"]["FiledSouce"] 		= "";	
		$configColumnVentaContado["11"]["FiledSouce"] 		= "categoryName";	
		
		$configColumnVentaContado["0"]["Formato"] 		= "Date";		
		//$configColumnVentaContado["0"]["Formato"] 		= "";		
		$configColumnVentaContado["1"]["Formato"] 		= "";		
		//$configColumnVentaContado["2"]["Formato"] 		= "";		
		$configColumnVentaContado["4"]["Formato"] 		= "";		
		$configColumnVentaContado["5"]["Formato"] 		= "";		
		$configColumnVentaContado["6"]["Formato"] 		= "";		
		$configColumnVentaContado["7"]["Formato"] 		= "";		
		$configColumnVentaContado["8"]["Formato"] 		= "Number";		
		$configColumnVentaContado["9"]["Formato"] 		= "";		
		$configColumnVentaContado["10"]["Formato"] 		= "";		
		$configColumnVentaContado["11"]["Formato"] 		= "";	

		$configColumnVentaContado["0"]["Width"] 		= "80px";		
		//$configColumnVentaContado["0"]["Width"] 		= "80px";		
		$configColumnVentaContado["1"]["Width"] 		= "220px";		
		//$configColumnVentaContado["2"]["Width"] 		= "85px";				
		$configColumnVentaContado["4"]["Width"] 		= "80px";		
		$configColumnVentaContado["5"]["Width"] 		= "200px";		
		$configColumnVentaContado["6"]["Width"] 		= "80px";		
		$configColumnVentaContado["7"]["Width"] 		= "80px";		
		$configColumnVentaContado["8"]["Width"] 		= "120px";		
		$configColumnVentaContado["9"]["Width"] 		= "120px";		
		$configColumnVentaContado["10"]["Width"] 		= "220px";			
		$configColumnVentaContado["11"]["Width"] 		= "80px";			
		
		
		$configColumnVentaContado["0"]["Total"] 		= False;		
		//$configColumnVentaContado["0"]["Total"] 		= False;		
		$configColumnVentaContado["1"]["Total"] 		= False;		
		//$configColumnVentaContado["2"]["Total"] 		= False;		
		$configColumnVentaContado["4"]["Total"] 		= False;		
		$configColumnVentaContado["5"]["Total"] 		= False;		
		$configColumnVentaContado["6"]["Total"] 		= False;		
		$configColumnVentaContado["7"]["Total"] 		= False;		
		$configColumnVentaContado["8"]["Total"] 		= True;		
		$configColumnVentaContado["9"]["Total"] 		= False;		
		$configColumnVentaContado["10"]["Total"] 		= False;	
		$configColumnVentaContado["11"]["Total"] 		= False;	
		
	
		$configColumnVentaContadoDolares["0"]["Titulo"] 		= "Fecha";		
		//$configColumnVentaContadoDolares["0"]["Titulo"] 		= "Codigo";		
		$configColumnVentaContadoDolares["1"]["Titulo"] 		= "Cliente";		
		//$configColumnVentaContadoDolares["2"]["Titulo"] 		= "Moneda";				
		$configColumnVentaContadoDolares["4"]["Titulo"] 		= "Fac";		
		$configColumnVentaContadoDolares["5"]["Titulo"] 		= "Transaccion";		
		$configColumnVentaContadoDolares["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnVentaContadoDolares["7"]["Titulo"] 		= "Estado";		
		$configColumnVentaContadoDolares["8"]["Titulo"] 		= "Monto";		
		$configColumnVentaContadoDolares["9"]["Titulo"] 		= "Vendedor";		
		$configColumnVentaContadoDolares["10"]["Titulo"] 		= "Nota";		
		$configColumnVentaContadoDolares["11"]["Titulo"] 		= "Categoria";		
		
		$configColumnVentaContadoDolares["0"]["FiledSouce"] 		= "transactionOn";				
		//$configColumnVentaContadoDolares["0"]["FiledSouce"] 		= "customerNumber";		
		$configColumnVentaContadoDolares["1"]["FiledSouce"] 		= "firstName";		
		//$configColumnVentaContadoDolares["2"]["FiledSouce"] 		= "currencyName";				
		$configColumnVentaContadoDolares["4"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnVentaContadoDolares["5"]["FiledSouce"] 		= "tipo";		
		$configColumnVentaContadoDolares["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnVentaContadoDolares["7"]["FiledSouce"] 		= "statusName";		
		$configColumnVentaContadoDolares["8"]["FiledSouce"] 		= "totalDocument";		
		$configColumnVentaContadoDolares["9"]["FiledSouce"] 		= "nickname";		
		$configColumnVentaContadoDolares["10"]["FiledSouce"] 		= "";	
		$configColumnVentaContadoDolares["11"]["FiledSouce"] 		= "categoryName";		
		
		$configColumnVentaContadoDolares["0"]["Formato"] 		= "Date";				
		//$configColumnVentaContadoDolares["0"]["Formato"] 		= "";		
		$configColumnVentaContadoDolares["1"]["Formato"] 		= "";		
		//$configColumnVentaContadoDolares["2"]["Formato"] 		= "";				
		$configColumnVentaContadoDolares["4"]["Formato"] 		= "";		
		$configColumnVentaContadoDolares["5"]["Formato"] 		= "";		
		$configColumnVentaContadoDolares["6"]["Formato"] 		= "";		
		$configColumnVentaContadoDolares["7"]["Formato"] 		= "";		
		$configColumnVentaContadoDolares["8"]["Formato"] 		= "Number";		
		$configColumnVentaContadoDolares["9"]["Formato"] 		= "";		
		$configColumnVentaContadoDolares["10"]["Formato"] 		= "";		
		$configColumnVentaContadoDolares["11"]["Formato"] 		= "";		
		

		$configColumnVentaContadoDolares["0"]["Width"] 		= "80px";				
		//$configColumnVentaContadoDolares["0"]["Width"] 		= "80px";		
		$configColumnVentaContadoDolares["1"]["Width"] 		= "220px";		
		//$configColumnVentaContadoDolares["2"]["Width"] 		= "85px";				
		$configColumnVentaContadoDolares["4"]["Width"] 		= "80px";		
		$configColumnVentaContadoDolares["5"]["Width"] 		= "200px";		
		$configColumnVentaContadoDolares["6"]["Width"] 		= "80px";		
		$configColumnVentaContadoDolares["7"]["Width"] 		= "80px";		
		$configColumnVentaContadoDolares["8"]["Width"] 		= "120px";		
		$configColumnVentaContadoDolares["9"]["Width"] 		= "120px";		
		$configColumnVentaContadoDolares["10"]["Width"] 	= "220px";			
		$configColumnVentaContadoDolares["11"]["Width"] 	= "80px";			
								 
		
		$configColumnVentaContadoDolares["0"]["Total"] 		= False;				
		//$configColumnVentaContadoDolares["0"]["Total"] 		= False;		
		$configColumnVentaContadoDolares["1"]["Total"] 		= False;		
		//$configColumnVentaContadoDolares["2"]["Total"] 		= False;				
		$configColumnVentaContadoDolares["4"]["Total"] 		= False;		
		$configColumnVentaContadoDolares["5"]["Total"] 		= False;		
		$configColumnVentaContadoDolares["6"]["Total"] 		= False;		
		$configColumnVentaContadoDolares["7"]["Total"] 		= False;		
		$configColumnVentaContadoDolares["8"]["Total"] 		= True;		
		$configColumnVentaContadoDolares["9"]["Total"] 		= False;		
		$configColumnVentaContadoDolares["10"]["Total"] 	= False;	
		$configColumnVentaContadoDolares["11"]["Total"] 	= False;	
		
		
		$objSalesDolar 		= $objSales;
		$objSalesCordoba 	= $objSales;
		
		if($objSalesCordoba != null)
		$objSalesCordoba = array_filter($objSalesCordoba,function($var){
			
			if (
				strtoupper($var["currencyName"]) == "CORDOBA" && 
				strtoupper($var["tipo"]) != "CREDITO"
			)
				return true;
		});
		
		if($objSalesDolar != null)
		$objSalesDolar = array_filter($objSalesDolar,function($var){
			if (
				strtoupper($var["currencyName"]) == "DOLAR" && 
				strtoupper($var["tipo"]) != "CREDITO"
			)
				return true;
		});
		
		$resultadoVentaContadoCordoba = helper_reporteGeneralCreateTable(
			$objSalesCordoba,
			$configColumnVentaContado,
			'0px',
			'CORDOBA = VENTAS DE CONTADO'
		);
		
		$resultadoVentaContadoDolar = helper_reporteGeneralCreateTable(
			$objSalesDolar,
			$configColumnVentaContadoDolares,
			'0px',
			'DOLARES = VENTAS DE CONTADO',
			'68c778',
			'black'
		);
		
		$totalCordoba = $totalCordoba + ($resultadoVentaContadoCordoba["table"] === 0 ? 0 : $resultadoVentaContadoCordoba["configColumn"][8]["TotalValor"]);
		$totalDolares = $totalDolares + ($resultadoVentaContadoDolar["table"] === 0 ? 0 : $resultadoVentaContadoDolar["configColumn"][8]["TotalValor"]);
		
		?>
		
	
		<?php
		
		$configColumnVentaCredito["0"]["Titulo"] 		= "Fecha";		
		//$configColumnVentaCredito["0"]["Titulo"] 		= "Codigo";		
		$configColumnVentaCredito["1"]["Titulo"] 		= "Cliente";		
		//$configColumnVentaCredito["2"]["Titulo"] 		= "Moneda";		
		$configColumnVentaCredito["4"]["Titulo"] 		= "Fac";		
		$configColumnVentaCredito["5"]["Titulo"] 		= "Transaccion";		
		$configColumnVentaCredito["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnVentaCredito["7"]["Titulo"] 		= "Estado";		
		$configColumnVentaCredito["8"]["Titulo"] 		= "Monto";		
		$configColumnVentaCredito["9"]["Titulo"] 		= "Vendedor";		
		$configColumnVentaCredito["10"]["Titulo"] 		= "Nota";		
		$configColumnVentaCredito["11"]["Titulo"] 		= "Categoria";
		
		$configColumnVentaCredito["0"]["FiledSouce"] 		= "transactionOn";				
		//$configColumnVentaCredito["0"]["FiledSouce"] 		= "customerNumber";		
		$configColumnVentaCredito["1"]["FiledSouce"] 		= "firstName";		
		//$configColumnVentaCredito["2"]["FiledSouce"] 		= "currencyName";				
		$configColumnVentaCredito["4"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnVentaCredito["5"]["FiledSouce"] 		= "tipo";		
		$configColumnVentaCredito["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnVentaCredito["7"]["FiledSouce"] 		= "statusName";		
		$configColumnVentaCredito["8"]["FiledSouce"] 		= "receiptAmount";		
		$configColumnVentaCredito["9"]["FiledSouce"] 		= "nickname";		
		$configColumnVentaCredito["10"]["FiledSouce"] 		= "";	
		$configColumnVentaCredito["11"]["FiledSouce"] 		= "categoryName";	

		$configColumnVentaCredito["0"]["Formato"] 		= "Date";		
		//$configColumnVentaCredito["0"]["Formato"] 		= "";		
		$configColumnVentaCredito["1"]["Formato"] 		= "";		
		//$configColumnVentaCredito["2"]["Formato"] 		= "";				
		$configColumnVentaCredito["4"]["Formato"] 		= "";		
		$configColumnVentaCredito["5"]["Formato"] 		= "";		
		$configColumnVentaCredito["6"]["Formato"] 		= "";		
		$configColumnVentaCredito["7"]["Formato"] 		= "";		
		$configColumnVentaCredito["8"]["Formato"] 		= "Number";		
		$configColumnVentaCredito["9"]["Formato"] 		= "";		
		$configColumnVentaCredito["10"]["Formato"] 		= "";	
		$configColumnVentaCredito["11"]["Formato"] 		= "";			

		$configColumnVentaCredito["0"]["Width"] 		= "80px";		
		//$configColumnVentaCredito["0"]["Width"] 		= "80px";		
		$configColumnVentaCredito["1"]["Width"] 		= "220px";		
		//$configColumnVentaCredito["2"]["Width"] 		= "85px";				
		$configColumnVentaCredito["4"]["Width"] 		= "80px";		
		$configColumnVentaCredito["5"]["Width"] 		= "200px";		
		$configColumnVentaCredito["6"]["Width"] 		= "80px";		
		$configColumnVentaCredito["7"]["Width"] 		= "80px";		
		$configColumnVentaCredito["8"]["Width"] 		= "120px";		
		$configColumnVentaCredito["9"]["Width"] 		= "120px";		
		$configColumnVentaCredito["10"]["Width"] 		= "220px";	
		$configColumnVentaCredito["11"]["Width"] 		= "80px";					
		
		$configColumnVentaCredito["0"]["Total"] 		= False;				
		//$configColumnVentaCredito["0"]["Total"] 		= False;		
		$configColumnVentaCredito["1"]["Total"] 		= False;		
		//$configColumnVentaCredito["2"]["Total"] 		= False;				
		$configColumnVentaCredito["4"]["Total"] 		= False;		
		$configColumnVentaCredito["5"]["Total"] 		= False;		
		$configColumnVentaCredito["6"]["Total"] 		= False;		
		$configColumnVentaCredito["7"]["Total"] 		= False;		
		$configColumnVentaCredito["8"]["Total"] 		= True;		
		$configColumnVentaCredito["9"]["Total"] 		= False;		
		$configColumnVentaCredito["10"]["Total"] 		= False;	
		$configColumnVentaCredito["11"]["Total"] 		= False;
		
	
		$configColumnVentaCreditoDolares["0"]["Titulo"] 		= "Fecha";		
		//$configColumnVentaCreditoDolares["0"]["Titulo"] 		= "Codigo";		
		$configColumnVentaCreditoDolares["1"]["Titulo"] 		= "Cliente";		
		//$configColumnVentaCreditoDolares["2"]["Titulo"] 		= "Moneda";				
		$configColumnVentaCreditoDolares["4"]["Titulo"] 		= "Fac";		
		$configColumnVentaCreditoDolares["5"]["Titulo"] 		= "Transaccion";		
		$configColumnVentaCreditoDolares["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnVentaCreditoDolares["7"]["Titulo"] 		= "Estado";		
		$configColumnVentaCreditoDolares["8"]["Titulo"] 		= "Monto";		
		$configColumnVentaCreditoDolares["9"]["Titulo"] 		= "Vendedor";		
		$configColumnVentaCreditoDolares["10"]["Titulo"] 		= "Nota";		
		$configColumnVentaCreditoDolares["11"]["Titulo"] 		= "Categoria";		
		
		$configColumnVentaCreditoDolares["0"]["FiledSouce"] 		= "transactionOn";				
		//$configColumnVentaCreditoDolares["0"]["FiledSouce"] 		= "customerNumber";		
		$configColumnVentaCreditoDolares["1"]["FiledSouce"] 		= "firstName";		
		//$configColumnVentaCreditoDolares["2"]["FiledSouce"] 		= "currencyName";				
		$configColumnVentaCreditoDolares["4"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnVentaCreditoDolares["5"]["FiledSouce"] 		= "tipo";		
		$configColumnVentaCreditoDolares["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnVentaCreditoDolares["7"]["FiledSouce"] 		= "statusName";		
		$configColumnVentaCreditoDolares["8"]["FiledSouce"] 		= "receiptAmount";		
		$configColumnVentaCreditoDolares["9"]["FiledSouce"] 		= "nickname";		
		$configColumnVentaCreditoDolares["10"]["FiledSouce"] 		= "";	
		$configColumnVentaCreditoDolares["11"]["FiledSouce"] 		= "categoryName";		
		
		$configColumnVentaCreditoDolares["0"]["Formato"] 		= "Date";		
		//$configColumnVentaCreditoDolares["0"]["Formato"] 		= "";		
		$configColumnVentaCreditoDolares["1"]["Formato"] 		= "";		
		//$configColumnVentaCreditoDolares["2"]["Formato"] 		= "";		
		$configColumnVentaCreditoDolares["4"]["Formato"] 		= "";		
		$configColumnVentaCreditoDolares["5"]["Formato"] 		= "";		
		$configColumnVentaCreditoDolares["6"]["Formato"] 		= "";		
		$configColumnVentaCreditoDolares["7"]["Formato"] 		= "";		
		$configColumnVentaCreditoDolares["8"]["Formato"] 		= "Number";		
		$configColumnVentaCreditoDolares["9"]["Formato"] 		= "";		
		$configColumnVentaCreditoDolares["10"]["Formato"] 		= "";		
		$configColumnVentaCreditoDolares["11"]["Formato"] 		= "";		
		
		$configColumnVentaCreditoDolares["0"]["Width"] 		= "80px";				
		//$configColumnVentaCreditoDolares["0"]["Width"] 		= "80px";		
		$configColumnVentaCreditoDolares["1"]["Width"] 		= "220px";		
		//$configColumnVentaCreditoDolares["2"]["Width"] 		= "85px";				
		$configColumnVentaCreditoDolares["4"]["Width"] 		= "80px";		
		$configColumnVentaCreditoDolares["5"]["Width"] 		= "200px";		
		$configColumnVentaCreditoDolares["6"]["Width"] 		= "80px";		
		$configColumnVentaCreditoDolares["7"]["Width"] 		= "80px";		
		$configColumnVentaCreditoDolares["8"]["Width"] 		= "120px";		
		$configColumnVentaCreditoDolares["9"]["Width"] 		= "120px";		
		$configColumnVentaCreditoDolares["10"]["Width"] 	= "220px";	
		$configColumnVentaCreditoDolares["11"]["Width"] 	= "80px";				
								 
		
		$configColumnVentaCreditoDolares["0"]["Total"] 		= False;				
		//$configColumnVentaCreditoDolares["0"]["Total"] 		= False;		
		$configColumnVentaCreditoDolares["1"]["Total"] 		= False;		
		//$configColumnVentaCreditoDolares["2"]["Total"] 		= False;				
		$configColumnVentaCreditoDolares["4"]["Total"] 		= False;		
		$configColumnVentaCreditoDolares["5"]["Total"] 		= False;		
		$configColumnVentaCreditoDolares["6"]["Total"] 		= False;		
		$configColumnVentaCreditoDolares["7"]["Total"] 		= False;		
		$configColumnVentaCreditoDolares["8"]["Total"] 		= True;		
		$configColumnVentaCreditoDolares["9"]["Total"] 		= False;		
		$configColumnVentaCreditoDolares["10"]["Total"] 	= False;	
		$configColumnVentaCreditoDolares["11"]["Total"] 	= False;	
	
		
		$objSalesCreditoDolar 		= $objSalesCredito;
		$objSalesCreditoCordoba 	= $objSalesCredito;
		
		if($objSalesCreditoCordoba != null)
		$objSalesCreditoCordoba = array_filter($objSalesCreditoCordoba,function($var){
			
			if (
				strtoupper($var["currencyName"]) == "CORDOBA" && 
				strtoupper($var["tipo"]) == "CREDITO"
			)
				return true;
		});
		
		if($objSalesCreditoDolar != null)
		$objSalesCreditoDolar = array_filter($objSalesCreditoDolar,function($var){
			if (
				strtoupper($var["currencyName"]) == "DOLAR" && 
				strtoupper($var["tipo"]) == "CREDITO"
			)
				return true;
		});
		
		$resultadoVentaCreditoCordoba = helper_reporteGeneralCreateTable(
			$objSalesCreditoCordoba,
			$configColumnVentaCredito,
			'0px',
			'CORDOBA = CREDITO PRIMA'
		);
		
		$resultadoVentaCreditoDolar = helper_reporteGeneralCreateTable(
			$objSalesCreditoDolar,
			$configColumnVentaCreditoDolares,
			'0px',
			'DOLARES = CREDITO PRIMA',
			'68c778',
			'black'
		);
		
		$totalCordoba = $totalCordoba + ($resultadoVentaCreditoCordoba["table"] === 0 ? 0 : $resultadoVentaCreditoCordoba["configColumn"][8]["TotalValor"]);
		$totalDolares = $totalDolares + ($resultadoVentaCreditoDolar["table"] === 0 ? 0 : $resultadoVentaCreditoDolar["configColumn"][8]["TotalValor"]);
		
		?>
		
		<?php
		
		$configColumnIngresoCaja["0"]["Titulo"] 		= "Fecha";		
		//$configColumnIngresoCaja["0"]["Titulo"] 		= "Codigo";		
		$configColumnIngresoCaja["1"]["Titulo"] 		= "Cliente";		
		//$configColumnIngresoCaja["2"]["Titulo"] 		= "Moneda";		
		$configColumnIngresoCaja["4"]["Titulo"] 		= "Fac";		
		$configColumnIngresoCaja["5"]["Titulo"] 		= "Transaccion";		
		$configColumnIngresoCaja["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnIngresoCaja["7"]["Titulo"] 		= "Estado";		
		$configColumnIngresoCaja["8"]["Titulo"] 		= "Monto";		
		$configColumnIngresoCaja["9"]["Titulo"] 		= "Vendedor";		
		$configColumnIngresoCaja["10"]["Titulo"] 		= "Nota";		
		$configColumnIngresoCaja["11"]["Titulo"] 		= "Tipo";		
		
		$configColumnIngresoCaja["0"]["FiledSouce"] 		= "transactionOn";				
		//$configColumnIngresoCaja["0"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnIngresoCaja["1"]["FiledSouce"] 		= "transactionNumber";		
		//$configColumnIngresoCaja["2"]["FiledSouce"] 		= "moneda";				
		$configColumnIngresoCaja["4"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnIngresoCaja["5"]["FiledSouce"] 		= "transactionName";		
		$configColumnIngresoCaja["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnIngresoCaja["7"]["FiledSouce"] 		= "estado";		
		$configColumnIngresoCaja["8"]["FiledSouce"] 		= "montoTransaccion";		
		$configColumnIngresoCaja["9"]["FiledSouce"] 		= "nickname";		
		$configColumnIngresoCaja["10"]["FiledSouce"] 		= "note";	
		$configColumnIngresoCaja["11"]["FiledSouce"] 		= "tipoEntrada";		
		
		$configColumnIngresoCaja["0"]["Formato"] 		= "Date";		
		//$configColumnIngresoCaja["0"]["Formato"] 		= "";		
		$configColumnIngresoCaja["1"]["Formato"] 		= "";		
		//$configColumnIngresoCaja["2"]["Formato"] 		= "";		
		$configColumnIngresoCaja["4"]["Formato"] 		= "";		
		$configColumnIngresoCaja["5"]["Formato"] 		= "";		
		$configColumnIngresoCaja["6"]["Formato"] 		= "";		
		$configColumnIngresoCaja["7"]["Formato"] 		= "";		
		$configColumnIngresoCaja["8"]["Formato"] 		= "Number";		
		$configColumnIngresoCaja["9"]["Formato"] 		= "";		
		$configColumnIngresoCaja["10"]["Formato"] 		= "";	
		$configColumnIngresoCaja["11"]["Formato"] 		= "";		

		$configColumnIngresoCaja["0"]["Width"] 		= "80px";		
		//$configColumnIngresoCaja["0"]["Width"] 		= "80px";		
		$configColumnIngresoCaja["1"]["Width"] 		= "220px";		
		//$configColumnIngresoCaja["2"]["Width"] 		= "85px";				
		$configColumnIngresoCaja["4"]["Width"] 		= "80px";		
		$configColumnIngresoCaja["5"]["Width"] 		= "200px";		
		$configColumnIngresoCaja["6"]["Width"] 		= "80px";		
		$configColumnIngresoCaja["7"]["Width"] 		= "80px";		
		$configColumnIngresoCaja["8"]["Width"] 		= "120px";		
		$configColumnIngresoCaja["9"]["Width"] 		= "120px";		
		$configColumnIngresoCaja["10"]["Width"] 	= "220px";	
		$configColumnIngresoCaja["11"]["Width"] 	= "80px";				
		
		$configColumnIngresoCaja["0"]["Total"] 		= False;		
		//$configColumnIngresoCaja["0"]["Total"] 		= False;		
		$configColumnIngresoCaja["1"]["Total"] 		= False;		
		//$configColumnIngresoCaja["2"]["Total"] 		= False;	
		$configColumnIngresoCaja["4"]["Total"] 		= False;		
		$configColumnIngresoCaja["5"]["Total"] 		= False;		
		$configColumnIngresoCaja["6"]["Total"] 		= False;		
		$configColumnIngresoCaja["7"]["Total"] 		= False;		
		$configColumnIngresoCaja["8"]["Total"] 		= True;		
		$configColumnIngresoCaja["9"]["Total"] 		= False;		
		$configColumnIngresoCaja["10"]["Total"] 	= False;	
		$configColumnIngresoCaja["11"]["Total"] 	= False;				
		
		
		
		$configColumnIngresoCajaDolares["0"]["Titulo"] 		= "Fecha";		
		//$configColumnIngresoCajaDolares["0"]["Titulo"] 		= "Codigo";		
		$configColumnIngresoCajaDolares["1"]["Titulo"] 		= "Cliente";		
		//$configColumnIngresoCajaDolares["2"]["Titulo"] 		= "Moneda";	
		$configColumnIngresoCajaDolares["4"]["Titulo"] 		= "Fac";		
		$configColumnIngresoCajaDolares["5"]["Titulo"] 		= "Transaccion";		
		$configColumnIngresoCajaDolares["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnIngresoCajaDolares["7"]["Titulo"] 		= "Estado";		
		$configColumnIngresoCajaDolares["8"]["Titulo"] 		= "Monto";		
		$configColumnIngresoCajaDolares["9"]["Titulo"] 		= "Vendedor";		
		$configColumnIngresoCajaDolares["10"]["Titulo"] 		= "Nota";		
		$configColumnIngresoCajaDolares["11"]["Titulo"] 		= "Tipo";		
		
		$configColumnIngresoCajaDolares["0"]["FiledSouce"] 		= "transactionOn";				
		//$configColumnIngresoCajaDolares["0"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnIngresoCajaDolares["1"]["FiledSouce"] 		= "transactionNumber";		
		//$configColumnIngresoCajaDolares["2"]["FiledSouce"] 		= "moneda";				
		$configColumnIngresoCajaDolares["4"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnIngresoCajaDolares["5"]["FiledSouce"] 		= "transactionName";		
		$configColumnIngresoCajaDolares["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnIngresoCajaDolares["7"]["FiledSouce"] 		= "estado";		
		$configColumnIngresoCajaDolares["8"]["FiledSouce"] 		= "montoTransaccion";		
		$configColumnIngresoCajaDolares["9"]["FiledSouce"] 		= "nickname";		
		$configColumnIngresoCajaDolares["10"]["FiledSouce"] 	= "note";	
		$configColumnIngresoCajaDolares["11"]["FiledSouce"] 	= "tipoEntrada";								
		
		$configColumnIngresoCajaDolares["0"]["Formato"] 		= "Date";				
		//$configColumnIngresoCajaDolares["0"]["Formato"] 		= "";		
		$configColumnIngresoCajaDolares["1"]["Formato"] 		= "";		
		//$configColumnIngresoCajaDolares["2"]["Formato"] 		= "";				
		$configColumnIngresoCajaDolares["4"]["Formato"] 		= "";		
		$configColumnIngresoCajaDolares["5"]["Formato"] 		= "";		
		$configColumnIngresoCajaDolares["6"]["Formato"] 		= "";		
		$configColumnIngresoCajaDolares["7"]["Formato"] 		= "";		
		$configColumnIngresoCajaDolares["8"]["Formato"] 		= "Number";		
		$configColumnIngresoCajaDolares["9"]["Formato"] 		= "";		
		$configColumnIngresoCajaDolares["10"]["Formato"] 		= "";	
		$configColumnIngresoCajaDolares["11"]["Formato"] 		= "";	
						

		$configColumnIngresoCajaDolares["0"]["Width"] 		= "80px";								
		//$configColumnIngresoCajaDolares["0"]["Width"] 		= "80px";		
		$configColumnIngresoCajaDolares["1"]["Width"] 		= "220px";		
		//$configColumnIngresoCajaDolares["2"]["Width"] 		= "85px";				
		$configColumnIngresoCajaDolares["4"]["Width"] 		= "80px";		
		$configColumnIngresoCajaDolares["5"]["Width"] 		= "200px";		
		$configColumnIngresoCajaDolares["6"]["Width"] 		= "80px";		
		$configColumnIngresoCajaDolares["7"]["Width"] 		= "80px";		
		$configColumnIngresoCajaDolares["8"]["Width"] 		= "120px";		
		$configColumnIngresoCajaDolares["9"]["Width"] 		= "120px";		
		$configColumnIngresoCajaDolares["10"]["Width"] 		= "220px";		
		$configColumnIngresoCajaDolares["11"]["Width"] 		= "80px";			
		
		$configColumnIngresoCajaDolares["0"]["Total"] 		= False;				
		//$configColumnIngresoCajaDolares["0"]["Total"] 		= False;		
		$configColumnIngresoCajaDolares["1"]["Total"] 		= False;		
		//$configColumnIngresoCajaDolares["2"]["Total"] 		= False;				
		$configColumnIngresoCajaDolares["4"]["Total"] 		= False;		
		$configColumnIngresoCajaDolares["5"]["Total"] 		= False;		
		$configColumnIngresoCajaDolares["6"]["Total"] 		= False;		
		$configColumnIngresoCajaDolares["7"]["Total"] 		= False;		
		$configColumnIngresoCajaDolares["8"]["Total"] 		= True;		
		$configColumnIngresoCajaDolares["9"]["Total"] 		= False;		
		$configColumnIngresoCajaDolares["10"]["Total"] 		= False;	
		$configColumnIngresoCajaDolares["11"]["Total"] 		= False;			
		
		
		
		$objCashDolar 	= $objCash;
		$objCashCordoba = $objCash;
		
		if($objCashCordoba != null)
		$objCashCordoba = array_filter($objCashCordoba,function($var){
			if (strtoupper($var["moneda"]) == "CORDOBA")
				return true;
		});
		
		if($objCashDolar != null)
		$objCashDolar = array_filter($objCashDolar,function($var){
			if (strtoupper($var["moneda"]) == "DOLAR")
				return true;
		});
		
		$resultadoIngresoCajaCordoba = helper_reporteGeneralCreateTable(
			$objCashCordoba,
			$configColumnIngresoCaja,
			'0px',
			'CORDOBA = INGRESO DE CAJA'
		);
		
		$resultadoIngresoCajaDolares = helper_reporteGeneralCreateTable(
			$objCashDolar,
			$configColumnIngresoCajaDolares,
			'0px',
			'DOLARES = INGRESO DE CAJA',
			'68c778',
			'black'
		);
		
		
		$totalCordoba = $totalCordoba + ($resultadoIngresoCajaCordoba["table"] === 0 ? 0 : $resultadoIngresoCajaCordoba["configColumn"][8]["TotalValor"]);
		$totalDolares = $totalDolares + ($resultadoIngresoCajaDolares["table"] === 0 ? 0 : $resultadoIngresoCajaDolares["configColumn"][8]["TotalValor"]);
		
		?>
		
		
		<?php
		
		$configColumnSalidaCaja["0"]["Titulo"] 		= "Fecha";		
		//$configColumnSalidaCaja["0"]["Titulo"] 		= "Codigo";		
		$configColumnSalidaCaja["1"]["Titulo"] 		= "Cliente";		
		//$configColumnSalidaCaja["2"]["Titulo"] 		= "Moneda";	
		$configColumnSalidaCaja["4"]["Titulo"] 		= "Fac";		
		$configColumnSalidaCaja["5"]["Titulo"] 		= "Transaccion";		
		$configColumnSalidaCaja["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnSalidaCaja["7"]["Titulo"] 		= "Estado";		
		$configColumnSalidaCaja["8"]["Titulo"] 		= "Monto";		
		$configColumnSalidaCaja["9"]["Titulo"] 		= "Vendedor";		
		$configColumnSalidaCaja["10"]["Titulo"] 	= "Nota";		
		$configColumnSalidaCaja["11"]["Titulo"] 	= "Tipo";		
		
		$configColumnSalidaCaja["0"]["FiledSouce"] 		= "transactionOn";				
		//$configColumnSalidaCaja["0"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnSalidaCaja["1"]["FiledSouce"] 		= "transactionNumber";		
		//$configColumnSalidaCaja["2"]["FiledSouce"] 		= "moneda";				
		$configColumnSalidaCaja["4"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnSalidaCaja["5"]["FiledSouce"] 		= "transactionName";		
		$configColumnSalidaCaja["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnSalidaCaja["7"]["FiledSouce"] 		= "estado";		
		$configColumnSalidaCaja["8"]["FiledSouce"] 		= "montoTransaccion";		
		$configColumnSalidaCaja["9"]["FiledSouce"] 		= "nickname";		
		$configColumnSalidaCaja["10"]["FiledSouce"] 	= "note";	
		$configColumnSalidaCaja["11"]["FiledSouce"] 	= "tipoSalida";		

		$configColumnSalidaCaja["0"]["Formato"] 		= "Date";		
		//$configColumnSalidaCaja["0"]["Formato"] 		= "";		
		$configColumnSalidaCaja["1"]["Formato"] 		= "";		
		//$configColumnSalidaCaja["2"]["Formato"] 		= "";				
		$configColumnSalidaCaja["4"]["Formato"] 		= "";		
		$configColumnSalidaCaja["5"]["Formato"] 		= "";		
		$configColumnSalidaCaja["6"]["Formato"] 		= "";		
		$configColumnSalidaCaja["7"]["Formato"] 		= "";		
		$configColumnSalidaCaja["8"]["Formato"] 		= "Number";		
		$configColumnSalidaCaja["9"]["Formato"] 		= "";		
		$configColumnSalidaCaja["10"]["Formato"] 		= "";	
		$configColumnSalidaCaja["11"]["Formato"] 		= "";				
		
		$configColumnSalidaCaja["0"]["Width"] 		= "80px";		
		//$configColumnSalidaCaja["0"]["Width"] 		= "80px";		
		$configColumnSalidaCaja["1"]["Width"] 		= "220px";		
		//$configColumnSalidaCaja["2"]["Width"] 		= "85px";	
		$configColumnSalidaCaja["4"]["Width"] 		= "80px";		
		$configColumnSalidaCaja["5"]["Width"] 		= "200px";		
		$configColumnSalidaCaja["6"]["Width"] 		= "80px";		
		$configColumnSalidaCaja["7"]["Width"] 		= "80px";		
		$configColumnSalidaCaja["8"]["Width"] 		= "120px";		
		$configColumnSalidaCaja["9"]["Width"] 		= "120px";		
		$configColumnSalidaCaja["10"]["Width"] 		= "220px";	
		$configColumnSalidaCaja["11"]["Width"] 		= "80px";	
		
		$configColumnSalidaCaja["0"]["Total"] 		= False;		
		//$configColumnSalidaCaja["0"]["Total"] 		= False;		
		$configColumnSalidaCaja["1"]["Total"] 		= False;		
		//$configColumnSalidaCaja["2"]["Total"] 		= False;	
		$configColumnSalidaCaja["4"]["Total"] 		= False;		
		$configColumnSalidaCaja["5"]["Total"] 		= False;		
		$configColumnSalidaCaja["6"]["Total"] 		= False;		
		$configColumnSalidaCaja["7"]["Total"] 		= False;		
		$configColumnSalidaCaja["8"]["Total"] 		= True;		
		$configColumnSalidaCaja["9"]["Total"] 		= False;		
		$configColumnSalidaCaja["10"]["Total"] 		= False;	
		$configColumnSalidaCaja["11"]["Total"] 		= False;	
	
		$configColumnSalidaCajaDolares["0"]["Titulo"] 		= "Fecha";		
		//$configColumnSalidaCajaDolares["0"]["Titulo"] 		= "Codigo";		
		$configColumnSalidaCajaDolares["1"]["Titulo"] 		= "Cliente";		
		//$configColumnSalidaCajaDolares["2"]["Titulo"] 		= "Moneda";				
		$configColumnSalidaCajaDolares["4"]["Titulo"] 		= "Fac";		
		$configColumnSalidaCajaDolares["5"]["Titulo"] 		= "Transaccion";		
		$configColumnSalidaCajaDolares["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnSalidaCajaDolares["7"]["Titulo"] 		= "Estado";		
		$configColumnSalidaCajaDolares["8"]["Titulo"] 		= "Monto";		
		$configColumnSalidaCajaDolares["9"]["Titulo"] 		= "Vendedor";		
		$configColumnSalidaCajaDolares["10"]["Titulo"] 		= "Nota";		
		$configColumnSalidaCajaDolares["11"]["Titulo"] 		= "Tipo";		
		

		$configColumnSalidaCajaDolares["0"]["FiledSouce"] 		= "transactionOn";				
		//$configColumnSalidaCajaDolares["0"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnSalidaCajaDolares["1"]["FiledSouce"] 		= "transactionNumber";		
		//$configColumnSalidaCajaDolares["2"]["FiledSouce"] 		= "moneda";				
		$configColumnSalidaCajaDolares["4"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnSalidaCajaDolares["5"]["FiledSouce"] 		= "transactionName";		
		$configColumnSalidaCajaDolares["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnSalidaCajaDolares["7"]["FiledSouce"] 		= "estado";		
		$configColumnSalidaCajaDolares["8"]["FiledSouce"] 		= "montoTransaccion";		
		$configColumnSalidaCajaDolares["9"]["FiledSouce"] 		= "nickname";		
		$configColumnSalidaCajaDolares["10"]["FiledSouce"] 		= "note";	
		$configColumnSalidaCajaDolares["11"]["FiledSouce"] 		= "tipoSalida";	
		
		$configColumnSalidaCajaDolares["0"]["Formato"] 		= "Date";				
		//$configColumnSalidaCajaDolares["0"]["Formato"] 		= "";		
		$configColumnSalidaCajaDolares["1"]["Formato"] 		= "";		
		//$configColumnSalidaCajaDolares["2"]["Formato"] 		= "";				
		$configColumnSalidaCajaDolares["4"]["Formato"] 		= "";		
		$configColumnSalidaCajaDolares["5"]["Formato"] 		= "";		
		$configColumnSalidaCajaDolares["6"]["Formato"] 		= "";		
		$configColumnSalidaCajaDolares["7"]["Formato"] 		= "";		
		$configColumnSalidaCajaDolares["8"]["Formato"] 		= "Number";		
		$configColumnSalidaCajaDolares["9"]["Formato"] 		= "";		
		$configColumnSalidaCajaDolares["10"]["Formato"] 	= "";				
		$configColumnSalidaCajaDolares["11"]["Formato"] 	= "";				
		

		$configColumnSalidaCajaDolares["0"]["Width"] 		= "80px";				
		//$configColumnSalidaCajaDolares["0"]["Width"] 		= "80px";		
		$configColumnSalidaCajaDolares["1"]["Width"] 		= "220px";		
		//$configColumnSalidaCajaDolares["2"]["Width"] 		= "85px";				
		$configColumnSalidaCajaDolares["4"]["Width"] 		= "80px";		
		$configColumnSalidaCajaDolares["5"]["Width"] 		= "200px";		
		$configColumnSalidaCajaDolares["6"]["Width"] 		= "80px";		
		$configColumnSalidaCajaDolares["7"]["Width"] 		= "80px";		
		$configColumnSalidaCajaDolares["8"]["Width"] 		= "120px";		
		$configColumnSalidaCajaDolares["9"]["Width"] 		= "120px";		
		$configColumnSalidaCajaDolares["10"]["Width"] 		= "220px";	
		$configColumnSalidaCajaDolares["11"]["Width"] 		= "80px";	
		

		$configColumnSalidaCajaDolares["0"]["Total"] 		= False;				
		//$configColumnSalidaCajaDolares["0"]["Total"] 		= False;		
		$configColumnSalidaCajaDolares["1"]["Total"] 		= False;		
		//$configColumnSalidaCajaDolares["2"]["Total"] 		= False;				
		$configColumnSalidaCajaDolares["4"]["Total"] 		= False;		
		$configColumnSalidaCajaDolares["5"]["Total"] 		= False;		
		$configColumnSalidaCajaDolares["6"]["Total"] 		= False;		
		$configColumnSalidaCajaDolares["7"]["Total"] 		= False;		
		$configColumnSalidaCajaDolares["8"]["Total"] 		= True;		
		$configColumnSalidaCajaDolares["9"]["Total"] 		= False;		
		$configColumnSalidaCajaDolares["10"]["Total"] 		= False;	
		$configColumnSalidaCajaDolares["11"]["Total"] 		= False;	
		


		$objCashOutCordoba 	= $objCashOut;
		$objCashOutDolar 	= $objCashOut;
		
		if($objCashOutCordoba != null)
		$objCashOutCordoba = array_filter($objCashOutCordoba,function($var){
			if (strtoupper($var["moneda"]) == "CORDOBA")
				return true;
		});
		
		if($objCashOutDolar != null)
		$objCashOutDolar = array_filter($objCashOutDolar,function($var){
			if (strtoupper($var["moneda"]) == "DOLAR")
				return true;
		});
		
		$resultadoSalidaCajaCordoba = helper_reporteGeneralCreateTable(
				$objCashOutCordoba,
				$configColumnSalidaCaja,
				'0px',
				'CORDOBA = SALIDA DE CAJA'
		);
		
		$resultadoSalidaCajaDolares = helper_reporteGeneralCreateTable(
				$objCashOutDolar,
				$configColumnSalidaCajaDolares,
				'0px',
				'DOLARES = SALIDA DE CAJA',
				'68c778',
				'black'
		);
		
		
		
		
		$totalCordoba = $totalCordoba - ($resultadoSalidaCajaCordoba["table"] === 0 ? 0 : $resultadoSalidaCajaCordoba["configColumn"][8]["TotalValor"]);
		$totalDolares = $totalDolares - ($resultadoSalidaCajaDolares["table"] === 0 ? 0 : $resultadoSalidaCajaDolares["configColumn"][8]["TotalValor"]);
		
		
		
		?>
		
		

		<?php 
		$widthX 	= 0;
		$columnX 	= 0;
		
		
		if($resultadoAbonosCordoba["columnas"] != 0 ){
			$widthX 	= $resultadoAbonosCordoba["width"];
			$columnX 	= $resultadoAbonosCordoba["columnas"];
			
		}
		else if($resultadoAbonosDolar["columnas"] != 0 ){
			$widthX 	= $resultadoAbonosDolar["width"];
			$columnX 	= $resultadoAbonosDolar["columnas"];
		}
		else if($resultadoVentaContadoCordoba["columnas"] != 0 ){
			$widthX 	= $resultadoVentaContadoCordoba["width"];
			$columnX 	= $resultadoVentaContadoCordoba["columnas"];
		}
		else if($resultadoVentaContadoDolar["columnas"] != 0 ){
			$widthX 	= $resultadoVentaContadoDolar["width"];
			$columnX 	= $resultadoVentaContadoDolar["columnas"];
		}
		else if($resultadoVentaCreditoCordoba["columnas"] != 0 ){
			$widthX 	= $resultadoVentaCreditoCordoba["width"];
			$columnX 	= $resultadoVentaCreditoCordoba["columnas"];
		}
		else if($resultadoVentaCreditoDolar["columnas"] != 0 ){
			$widthX 	= $resultadoVentaCreditoDolar["width"];
			$columnX 	= $resultadoVentaCreditoDolar["columnas"];
		}
		else if($resultadoIngresoCajaCordoba["columnas"] != 0 ){
			$widthX 	= $resultadoIngresoCajaCordoba["width"];
			$columnX 	= $resultadoIngresoCajaCordoba["columnas"];
		}
		else if($resultadoIngresoCajaDolares["columnas"] != 0 ){
			$widthX 	= $resultadoIngresoCajaDolares["width"];
			$columnX 	= $resultadoIngresoCajaDolares["columnas"];
		}
		else if($resultadoSalidaCajaCordoba["columnas"] != 0 ){
			$widthX 	= $resultadoSalidaCajaCordoba["width"];
			$columnX 	= $resultadoSalidaCajaCordoba["columnas"];
		}
		else if($resultadoSalidaCajaDolares["columnas"] != 0 ){
			$widthX 	= $resultadoSalidaCajaDolares["width"];
			$columnX 	= $resultadoSalidaCajaDolares["columnas"];
		}
		else{
			$widthX 	= "0px";
			$columnX 	= 0;
		}
		
		
		echo helper_reporteGeneralCreateEncabezado(
			'<h2 style="margin:0px;" >ARQUEO DIARIO</h2>',
			'<h2 style="margin:0px;" >'.$objCompany->name.'</h2>',
			$columnX,
			'<h2 style="margin:0px; " >DEL '.$startOn.' AL '.$endOn."</h2>",
			"",
			"",
			$widthX 
		);
		
		?>
		<br/>			
		
		
		<?php 
		if($resultadoAbonosCordoba["table"] !== 0)
		echo $resultadoAbonosCordoba["table"];
		?>
		<br/>
		<?php 
		if($resultadoVentaContadoCordoba["table"] !== 0)
		echo $resultadoVentaContadoCordoba["table"];
		?>
		<br/>
		<?php 
		if($resultadoVentaCreditoCordoba["table"] !== 0)
		echo $resultadoVentaCreditoCordoba["table"];
		?>
		
		<br/>
		<?php 
		if($resultadoIngresoCajaCordoba["table"] !== 0)
		echo $resultadoIngresoCajaCordoba["table"];
		?>
		<br/>
		
		<?php 
		if($resultadoSalidaCajaCordoba["table"] !== 0)
		echo $resultadoSalidaCajaCordoba["table"];
		?>
		<br/>
		
	
	
		<?php 		
		$configTotalesColumns[0]["Titulo"] = "Total Cordoba";			
		$configTotalesColumns[0]["FiledSouce"] = "total";
		$configTotalesColumns[0]["Formato"] = "Number";
		$configTotalesColumns[0]["Colspan"] = $columnX;		
		$configTotalesColumns[0]["Width"] = $widthX;
		$detailTotales[0]["total"] = $totalCordoba;
		
		
		$rosTotales = helper_reporteGeneralCreateTable(
				$detailTotales,
				$configTotalesColumns,
				'0',
				NULL
				
		);
		
		echo $rosTotales["table"];
		
		?>
		
		
		
		<?php 
		if($resultadoAbonosDolar["table"] !== 0)
		echo $resultadoAbonosDolar["table"];
		?>
		<br/>

		<?php 
		if($resultadoVentaContadoDolar["table"] !== 0)
		echo $resultadoVentaContadoDolar["table"];
		?>
		<br/>
		
		<?php 
		if($resultadoVentaCreditoDolar["table"] !== 0)
		echo $resultadoVentaCreditoDolar["table"];
		?>
		
		<br/>

		<?php 
		if($resultadoIngresoCajaDolares["table"] !== 0)
		echo $resultadoIngresoCajaDolares["table"];
		?>
		<br/>
		
		<?php 
		if($resultadoSalidaCajaDolares["table"] !== 0)
		echo $resultadoSalidaCajaDolares["table"];
		?>
		<br/>
	

		<?php 		
		
		$configTotalesColumns[0]["Titulo"] = "Total Dolares";			
		$configTotalesColumns[0]["FiledSouce"] = "total";
		$configTotalesColumns[0]["Formato"] = "Number";
		$configTotalesColumns[0]["Colspan"] = $columnX;		
		$configTotalesColumns[0]["Width"] = $widthX;
		$detailTotales[0]["total"] = $totalDolares;
		
		$rosTotales = helper_reporteGeneralCreateTable(
				$detailTotales,
				$configTotalesColumns,
				'0',
				NULL,
				'68c778',
				'black'
		);
		
		echo $rosTotales["table"];
		
		?>
		
		<?php 
		echo helper_reporteGeneralCreateFirma(	
			$objFirmaEncription,
			$columnX,
			$widthX 
		);
		?>
		
		
	</body>	
</html>