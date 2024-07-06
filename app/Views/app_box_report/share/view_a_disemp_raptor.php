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
	<body style="font-family:monospace;font-size:<?php echo $fontSize; ?>;margin:0px 0px 0px 0px"> 
		
		
		<?php
		$totalCordoba 							= 0;
		$totalDolares 							= 0;
		$configColumnAbonos["0"]["Titulo"] 		= "Codigo";		
		$configColumnAbonos["1"]["Titulo"] 		= "Cliente";		
		$configColumnAbonos["2"]["Titulo"] 		= "Moneda";		
		$configColumnAbonos["3"]["Titulo"] 		= "Fecha";		
		$configColumnAbonos["4"]["Titulo"] 		= "Fac";		
		$configColumnAbonos["5"]["Titulo"] 		= "Transaccion";		
		$configColumnAbonos["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnAbonos["7"]["Titulo"] 		= "Estado";		
		$configColumnAbonos["8"]["Titulo"] 		= "Monto";		
		$configColumnAbonos["9"]["Titulo"] 		= "Usuario";		
		$configColumnAbonos["10"]["Titulo"] 	= "Nota";		
		$configColumnAbonos["11"]["Titulo"] 	= "Categoria";		
		$configColumnAbonos["12"]["Titulo"] 	= "Sub Categoria";
					 
		$configColumnAbonos["0"]["FiledSouce"] 		= "customerNumber";		
		$configColumnAbonos["1"]["FiledSouce"] 		= "firstName";		
		$configColumnAbonos["2"]["FiledSouce"] 		= "moneda";		
		$configColumnAbonos["3"]["FiledSouce"] 		= "transactionOn";		
		$configColumnAbonos["4"]["FiledSouce"] 		= "Fac";		
		$configColumnAbonos["5"]["FiledSouce"] 		= "transactionName";		
		$configColumnAbonos["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnAbonos["7"]["FiledSouce"] 		= "estado";		
		$configColumnAbonos["8"]["FiledSouce"] 		= "montoFac";		
		$configColumnAbonos["9"]["FiledSouce"] 		= "nickname";		
		$configColumnAbonos["10"]["FiledSouce"] 	= "note";	
		$configColumnAbonos["11"]["FiledSouce"] 	= "conceptosName";	
		$configColumnAbonos["12"]["FiledSouce"] 	= "conceptosSubName";	
		
		
		
		$configColumnAbonos["0"]["Formato"] 		= "";		
		$configColumnAbonos["1"]["Formato"] 		= "";		
		$configColumnAbonos["2"]["Formato"] 		= "";		
		$configColumnAbonos["3"]["Formato"] 		= "Date";		
		$configColumnAbonos["4"]["Formato"] 		= "";		
		$configColumnAbonos["5"]["Formato"] 		= "";		
		$configColumnAbonos["6"]["Formato"] 		= "";		
		$configColumnAbonos["7"]["Formato"] 		= "";		
		$configColumnAbonos["8"]["Formato"] 		= "Number";		
		$configColumnAbonos["9"]["Formato"] 		= "";		
		$configColumnAbonos["10"]["Formato"] 		= "";	
		$configColumnAbonos["11"]["Formato"] 		= "";	
		$configColumnAbonos["12"]["Formato"] 		= "";	
		
		
		
		$configColumnAbonos["0"]["Width"] 		= "20px";		
		$configColumnAbonos["1"]["Width"] 		= "220px";		
		$configColumnAbonos["2"]["Width"] 		= "20px";		
		$configColumnAbonos["3"]["Width"] 		= "20px";		
		$configColumnAbonos["4"]["Width"] 		= "20px";		
		$configColumnAbonos["5"]["Width"] 		= "200px";		
		$configColumnAbonos["6"]["Width"] 		= "20px";		
		$configColumnAbonos["7"]["Width"] 		= "20px";		
		$configColumnAbonos["8"]["Width"] 		= "120px";		
		$configColumnAbonos["9"]["Width"] 		= "120px";		
		$configColumnAbonos["10"]["Width"] 		= "220px";	
		$configColumnAbonos["11"]["Width"] 		= "80px";	
		$configColumnAbonos["12"]["Width"] 		= "80px";
		
		$configColumnAbonos["0"]["Total"] 		= False;		
		$configColumnAbonos["1"]["Total"] 		= False;		
		$configColumnAbonos["2"]["Total"] 		= False;		
		$configColumnAbonos["3"]["Total"] 		= False;		
		$configColumnAbonos["4"]["Total"] 		= False;		
		$configColumnAbonos["5"]["Total"] 		= False;		
		$configColumnAbonos["6"]["Total"] 		= False;		
		$configColumnAbonos["7"]["Total"] 		= False;		
		$configColumnAbonos["8"]["Total"] 		= True;		
		$configColumnAbonos["9"]["Total"] 		= False;		
		$configColumnAbonos["10"]["Total"] 		= False;	
		$configColumnAbonos["11"]["Total"] 		= False;	
		$configColumnAbonos["12"]["Total"] 		= False;					
		$objDetailCordoba 	= $objDetail;
		
		if($objDetailCordoba != null)
		$objDetailCordoba 	= array_filter($objDetailCordoba,function($var){
			if (strtoupper($var["moneda"]) == "CORDOBA" && strtoupper($var["transactionName"]) == "ABONOS DE CREDITO" )
				return true;
		});
		
		
		
		$resultadoAbonosCordoba = helper_reporteGeneralCreateTable(
			$objDetailCordoba,
			$configColumnAbonos,
			'0px',
			"CORDOBA = LISTA DE ABONOS/ ABONOS AL CAPITAL / CANCELACION ANTICIPADA "
		);
		$totalCordoba = 0 + ($resultadoAbonosCordoba["table"] === 0 ? 0 : $resultadoAbonosCordoba["configColumn"][8]["TotalValor"]);
		
		?>
		
		
		<?php
		
		$configColumnVentaContado["0"]["Titulo"] 		= "Codigo";		
		$configColumnVentaContado["1"]["Titulo"] 		= "Cliente";		
		$configColumnVentaContado["2"]["Titulo"] 		= "Moneda";		
		$configColumnVentaContado["3"]["Titulo"] 		= "Fecha";		
		$configColumnVentaContado["4"]["Titulo"] 		= "Fac";		
		$configColumnVentaContado["5"]["Titulo"] 		= "Transaccion";		
		$configColumnVentaContado["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnVentaContado["7"]["Titulo"] 		= "Estado";		
		$configColumnVentaContado["8"]["Titulo"] 		= "Monto";		
		$configColumnVentaContado["9"]["Titulo"] 		= "Usuario";		
		$configColumnVentaContado["10"]["Titulo"] 		= "Nota";	
		$configColumnVentaContado["11"]["Titulo"] 		= "Categoria";	
		$configColumnVentaContado["12"]["Titulo"] 		= "Sub Categoria";	
		
					 
		$configColumnVentaContado["0"]["FiledSouce"] 		= "customerNumber";		
		$configColumnVentaContado["1"]["FiledSouce"] 		= "firstName";		
		$configColumnVentaContado["2"]["FiledSouce"] 		= "currencyName";		
		$configColumnVentaContado["3"]["FiledSouce"] 		= "transactionOn";		
		$configColumnVentaContado["4"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnVentaContado["5"]["FiledSouce"] 		= "tipo";		
		$configColumnVentaContado["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnVentaContado["7"]["FiledSouce"] 		= "statusName";		
		$configColumnVentaContado["8"]["FiledSouce"] 		= "totalDocument";		
		$configColumnVentaContado["9"]["FiledSouce"] 		= "nickname";		
		$configColumnVentaContado["10"]["FiledSouce"] 		= "";	
		$configColumnVentaContado["11"]["FiledSouce"] 		= "categoryName";	
		$configColumnVentaContado["12"]["FiledSouce"] 		= "categorySubName";	
		
		$configColumnVentaContado["0"]["Formato"] 		= "";		
		$configColumnVentaContado["1"]["Formato"] 		= "";		
		$configColumnVentaContado["2"]["Formato"] 		= "";		
		$configColumnVentaContado["3"]["Formato"] 		= "Date";		
		$configColumnVentaContado["4"]["Formato"] 		= "";		
		$configColumnVentaContado["5"]["Formato"] 		= "";		
		$configColumnVentaContado["6"]["Formato"] 		= "";		
		$configColumnVentaContado["7"]["Formato"] 		= "";		
		$configColumnVentaContado["8"]["Formato"] 		= "Number";		
		$configColumnVentaContado["9"]["Formato"] 		= "";		
		$configColumnVentaContado["10"]["Formato"] 		= "";		
		$configColumnVentaContado["11"]["Formato"] 		= "";	
		$configColumnVentaContado["12"]["Formato"] 		= "";	

		$configColumnVentaContado["0"]["Width"] 		= "20px";		
		$configColumnVentaContado["1"]["Width"] 		= "220px";		
		$configColumnVentaContado["2"]["Width"] 		= "20px";		
		$configColumnVentaContado["3"]["Width"] 		= "20px";		
		$configColumnVentaContado["4"]["Width"] 		= "20px";		
		$configColumnVentaContado["5"]["Width"] 		= "200px";		
		$configColumnVentaContado["6"]["Width"] 		= "20px";		
		$configColumnVentaContado["7"]["Width"] 		= "20px";		
		$configColumnVentaContado["8"]["Width"] 		= "120px";		
		$configColumnVentaContado["9"]["Width"] 		= "120px";		
		$configColumnVentaContado["10"]["Width"] 		= "220px";			
		$configColumnVentaContado["11"]["Width"] 		= "80px";			
		$configColumnVentaContado["12"]["Width"] 		= "80px";	
		
		
		$configColumnVentaContado["0"]["Total"] 		= False;		
		$configColumnVentaContado["1"]["Total"] 		= False;		
		$configColumnVentaContado["2"]["Total"] 		= False;		
		$configColumnVentaContado["3"]["Total"] 		= False;		
		$configColumnVentaContado["4"]["Total"] 		= False;		
		$configColumnVentaContado["5"]["Total"] 		= False;		
		$configColumnVentaContado["6"]["Total"] 		= False;		
		$configColumnVentaContado["7"]["Total"] 		= False;		
		$configColumnVentaContado["8"]["Total"] 		= True;		
		$configColumnVentaContado["9"]["Total"] 		= False;		
		$configColumnVentaContado["10"]["Total"] 		= False;	
		$configColumnVentaContado["11"]["Total"] 		= False;	
		$configColumnVentaContado["12"]["Total"] 		= False;			
		$objSalesCordoba 	= $objSales;
		
		if($objSalesCordoba != null)
		$objSalesCordoba = array_filter($objSalesCordoba,function($var){
			
			if (
				strtoupper($var["currencyName"]) == "CORDOBA" && 
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
		
		
		
		$totalCordoba = $totalCordoba + ($resultadoVentaContadoCordoba["table"] === 0 ? 0 : $resultadoVentaContadoCordoba["configColumn"][8]["TotalValor"]);
		?>
		
	
		<?php
		
		$configColumnVentaCredito["0"]["Titulo"] 		= "Codigo";		
		$configColumnVentaCredito["1"]["Titulo"] 		= "Cliente";		
		$configColumnVentaCredito["2"]["Titulo"] 		= "Moneda";		
		$configColumnVentaCredito["3"]["Titulo"] 		= "Fecha";		
		$configColumnVentaCredito["4"]["Titulo"] 		= "Fac";		
		$configColumnVentaCredito["5"]["Titulo"] 		= "Transaccion";		
		$configColumnVentaCredito["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnVentaCredito["7"]["Titulo"] 		= "Estado";		
		$configColumnVentaCredito["8"]["Titulo"] 		= "Monto";		
		$configColumnVentaCredito["9"]["Titulo"] 		= "Usuario";		
		$configColumnVentaCredito["10"]["Titulo"] 		= "Nota";		
		$configColumnVentaCredito["11"]["Titulo"] 		= "Categoria";
		$configColumnVentaCredito["12"]["Titulo"] 		= "Sub Categoria";
					 
		$configColumnVentaCredito["0"]["FiledSouce"] 		= "customerNumber";		
		$configColumnVentaCredito["1"]["FiledSouce"] 		= "firstName";		
		$configColumnVentaCredito["2"]["FiledSouce"] 		= "currencyName";		
		$configColumnVentaCredito["3"]["FiledSouce"] 		= "transactionOn";		
		$configColumnVentaCredito["4"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnVentaCredito["5"]["FiledSouce"] 		= "tipo";		
		$configColumnVentaCredito["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnVentaCredito["7"]["FiledSouce"] 		= "statusName";		
		$configColumnVentaCredito["8"]["FiledSouce"] 		= "receiptAmount";		
		$configColumnVentaCredito["9"]["FiledSouce"] 		= "nickname";		
		$configColumnVentaCredito["10"]["FiledSouce"] 		= "";	
		$configColumnVentaCredito["11"]["FiledSouce"] 		= "categoryName";	
		$configColumnVentaCredito["12"]["FiledSouce"] 		= "categorySubName";	

		$configColumnVentaCredito["0"]["Formato"] 		= "";		
		$configColumnVentaCredito["1"]["Formato"] 		= "";		
		$configColumnVentaCredito["2"]["Formato"] 		= "";		
		$configColumnVentaCredito["3"]["Formato"] 		= "Date";		
		$configColumnVentaCredito["4"]["Formato"] 		= "";		
		$configColumnVentaCredito["5"]["Formato"] 		= "";		
		$configColumnVentaCredito["6"]["Formato"] 		= "";		
		$configColumnVentaCredito["7"]["Formato"] 		= "";		
		$configColumnVentaCredito["8"]["Formato"] 		= "Number";		
		$configColumnVentaCredito["9"]["Formato"] 		= "";		
		$configColumnVentaCredito["10"]["Formato"] 		= "";	
		$configColumnVentaCredito["11"]["Formato"] 		= "";			
		$configColumnVentaCredito["12"]["Formato"] 		= "";			

		$configColumnVentaCredito["0"]["Width"] 		= "20px";		
		$configColumnVentaCredito["1"]["Width"] 		= "220px";		
		$configColumnVentaCredito["2"]["Width"] 		= "20px";		
		$configColumnVentaCredito["3"]["Width"] 		= "20px";		
		$configColumnVentaCredito["4"]["Width"] 		= "20px";		
		$configColumnVentaCredito["5"]["Width"] 		= "200px";		
		$configColumnVentaCredito["6"]["Width"] 		= "20px";		
		$configColumnVentaCredito["7"]["Width"] 		= "20px";		
		$configColumnVentaCredito["8"]["Width"] 		= "120px";		
		$configColumnVentaCredito["9"]["Width"] 		= "120px";		
		$configColumnVentaCredito["10"]["Width"] 		= "220px";	
		$configColumnVentaCredito["11"]["Width"] 		= "80px";					
		$configColumnVentaCredito["12"]["Width"] 		= "80px";					
						  
		$configColumnVentaCredito["0"]["Total"] 		= False;		
		$configColumnVentaCredito["1"]["Total"] 		= False;		
		$configColumnVentaCredito["2"]["Total"] 		= False;		
		$configColumnVentaCredito["3"]["Total"] 		= False;		
		$configColumnVentaCredito["4"]["Total"] 		= False;		
		$configColumnVentaCredito["5"]["Total"] 		= False;		
		$configColumnVentaCredito["6"]["Total"] 		= False;		
		$configColumnVentaCredito["7"]["Total"] 		= False;		
		$configColumnVentaCredito["8"]["Total"] 		= True;		
		$configColumnVentaCredito["9"]["Total"] 		= False;		
		$configColumnVentaCredito["10"]["Total"] 		= False;	
		$configColumnVentaCredito["11"]["Total"] 		= False;
		$configColumnVentaCredito["12"]["Total"] 		= False;
		$objSalesCreditoCordoba 	= $objSalesCredito;
		
		if($objSalesCreditoCordoba != null)
		$objSalesCreditoCordoba = array_filter($objSalesCreditoCordoba,function($var){
			
			if (
				strtoupper($var["currencyName"]) == "CORDOBA" && 
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
		$totalCordoba = $totalCordoba + ($resultadoVentaCreditoCordoba["table"] === 0 ? 0 : $resultadoVentaCreditoCordoba["configColumn"][8]["TotalValor"]);		
		
		?>
		
		<?php
		
		$configColumnIngresoCaja["0"]["Titulo"] 		= "Codigo";		
		$configColumnIngresoCaja["1"]["Titulo"] 		= "Cliente";		
		$configColumnIngresoCaja["2"]["Titulo"] 		= "Moneda";		
		$configColumnIngresoCaja["3"]["Titulo"] 		= "Fecha";		
		$configColumnIngresoCaja["4"]["Titulo"] 		= "Fac";		
		$configColumnIngresoCaja["5"]["Titulo"] 		= "Transaccion";		
		$configColumnIngresoCaja["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnIngresoCaja["7"]["Titulo"] 		= "Estado";		
		$configColumnIngresoCaja["8"]["Titulo"] 		= "Monto";		
		$configColumnIngresoCaja["9"]["Titulo"] 		= "Usuario";		
		$configColumnIngresoCaja["10"]["Titulo"] 		= "Nota";		
		$configColumnIngresoCaja["11"]["Titulo"] 		= "Tipo";		
		$configColumnIngresoCaja["12"]["Titulo"] 		= "Sub Tipo";		
					 
		$configColumnIngresoCaja["0"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnIngresoCaja["1"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnIngresoCaja["2"]["FiledSouce"] 		= "moneda";		
		$configColumnIngresoCaja["3"]["FiledSouce"] 		= "transactionOn";		
		$configColumnIngresoCaja["4"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnIngresoCaja["5"]["FiledSouce"] 		= "transactionName";		
		$configColumnIngresoCaja["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnIngresoCaja["7"]["FiledSouce"] 		= "estado";		
		$configColumnIngresoCaja["8"]["FiledSouce"] 		= "montoTransaccion";		
		$configColumnIngresoCaja["9"]["FiledSouce"] 		= "nickname";		
		$configColumnIngresoCaja["10"]["FiledSouce"] 		= "note";	
		$configColumnIngresoCaja["11"]["FiledSouce"] 		= "tipoEntrada";		
		$configColumnIngresoCaja["12"]["FiledSouce"] 		= "tipoSubEntrada";		
		
		$configColumnIngresoCaja["0"]["Formato"] 		= "";		
		$configColumnIngresoCaja["1"]["Formato"] 		= "";		
		$configColumnIngresoCaja["2"]["Formato"] 		= "";		
		$configColumnIngresoCaja["3"]["Formato"] 		= "Date";		
		$configColumnIngresoCaja["4"]["Formato"] 		= "";		
		$configColumnIngresoCaja["5"]["Formato"] 		= "";		
		$configColumnIngresoCaja["6"]["Formato"] 		= "";		
		$configColumnIngresoCaja["7"]["Formato"] 		= "";		
		$configColumnIngresoCaja["8"]["Formato"] 		= "Number";		
		$configColumnIngresoCaja["9"]["Formato"] 		= "";		
		$configColumnIngresoCaja["10"]["Formato"] 		= "";	
		$configColumnIngresoCaja["11"]["Formato"] 		= "";		
		$configColumnIngresoCaja["12"]["Formato"] 		= "";		

		$configColumnIngresoCaja["0"]["Width"] 		= "20px";		
		$configColumnIngresoCaja["1"]["Width"] 		= "220px";		
		$configColumnIngresoCaja["2"]["Width"] 		= "20px";		
		$configColumnIngresoCaja["3"]["Width"] 		= "20px";		
		$configColumnIngresoCaja["4"]["Width"] 		= "20px";		
		$configColumnIngresoCaja["5"]["Width"] 		= "200px";		
		$configColumnIngresoCaja["6"]["Width"] 		= "20px";		
		$configColumnIngresoCaja["7"]["Width"] 		= "20px";		
		$configColumnIngresoCaja["8"]["Width"] 		= "120px";		
		$configColumnIngresoCaja["9"]["Width"] 		= "120px";		
		$configColumnIngresoCaja["10"]["Width"] 	= "220px";	
		$configColumnIngresoCaja["11"]["Width"] 	= "80px";				
		$configColumnIngresoCaja["12"]["Width"] 	= "80px";				
		
		$configColumnIngresoCaja["0"]["Total"] 		= False;		
		$configColumnIngresoCaja["1"]["Total"] 		= False;		
		$configColumnIngresoCaja["2"]["Total"] 		= False;		
		$configColumnIngresoCaja["3"]["Total"] 		= False;		
		$configColumnIngresoCaja["4"]["Total"] 		= False;		
		$configColumnIngresoCaja["5"]["Total"] 		= False;		
		$configColumnIngresoCaja["6"]["Total"] 		= False;		
		$configColumnIngresoCaja["7"]["Total"] 		= False;		
		$configColumnIngresoCaja["8"]["Total"] 		= True;		
		$configColumnIngresoCaja["9"]["Total"] 		= False;		
		$configColumnIngresoCaja["10"]["Total"] 	= False;	
		$configColumnIngresoCaja["11"]["Total"] 	= False;				
		$configColumnIngresoCaja["12"]["Total"] 	= False;				
		$objCashCordoba = $objCash;
		
		if($objCashCordoba != null)
		$objCashCordoba = array_filter($objCashCordoba,function($var){
			if (strtoupper($var["moneda"]) == "CORDOBA")
				return true;
		});
		
		$resultadoIngresoCajaCordoba = helper_reporteGeneralCreateTable(
			$objCashCordoba,
			$configColumnIngresoCaja,
			'0px',
			'CORDOBA = INGRESO DE CAJA'
		);
		
		
		
		$totalCordoba = $totalCordoba + ($resultadoIngresoCajaCordoba["table"] === 0 ? 0 : $resultadoIngresoCajaCordoba["configColumn"][8]["TotalValor"]);
		?>
		
		
		<?php
		
		$configColumnSalidaCaja["0"]["Titulo"] 		= "Codigo";		
		$configColumnSalidaCaja["1"]["Titulo"] 		= "Cliente";		
		$configColumnSalidaCaja["2"]["Titulo"] 		= "Moneda";		
		$configColumnSalidaCaja["3"]["Titulo"] 		= "Fecha";		
		$configColumnSalidaCaja["4"]["Titulo"] 		= "Fac";		
		$configColumnSalidaCaja["5"]["Titulo"] 		= "Transaccion";		
		$configColumnSalidaCaja["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnSalidaCaja["7"]["Titulo"] 		= "Estado";		
		$configColumnSalidaCaja["8"]["Titulo"] 		= "Monto";		
		$configColumnSalidaCaja["9"]["Titulo"] 		= "Usuario";		
		$configColumnSalidaCaja["10"]["Titulo"] 	= "Nota";		
		$configColumnSalidaCaja["11"]["Titulo"] 	= "Tipo";		
		$configColumnSalidaCaja["12"]["Titulo"] 	= "Sub Tipo";		
					 
		$configColumnSalidaCaja["0"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnSalidaCaja["1"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnSalidaCaja["2"]["FiledSouce"] 		= "moneda";		
		$configColumnSalidaCaja["3"]["FiledSouce"] 		= "transactionOn";		
		$configColumnSalidaCaja["4"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnSalidaCaja["5"]["FiledSouce"] 		= "transactionName";		
		$configColumnSalidaCaja["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnSalidaCaja["7"]["FiledSouce"] 		= "estado";		
		$configColumnSalidaCaja["8"]["FiledSouce"] 		= "montoTransaccion";		
		$configColumnSalidaCaja["9"]["FiledSouce"] 		= "nickname";		
		$configColumnSalidaCaja["10"]["FiledSouce"] 	= "note";	
		$configColumnSalidaCaja["11"]["FiledSouce"] 	= "tipoSalida";		
		$configColumnSalidaCaja["12"]["FiledSouce"] 	= "tipoSubSalida";		

		$configColumnSalidaCaja["0"]["Formato"] 		= "";		
		$configColumnSalidaCaja["1"]["Formato"] 		= "";		
		$configColumnSalidaCaja["2"]["Formato"] 		= "";		
		$configColumnSalidaCaja["3"]["Formato"] 		= "Date";		
		$configColumnSalidaCaja["4"]["Formato"] 		= "";		
		$configColumnSalidaCaja["5"]["Formato"] 		= "";		
		$configColumnSalidaCaja["6"]["Formato"] 		= "";		
		$configColumnSalidaCaja["7"]["Formato"] 		= "";		
		$configColumnSalidaCaja["8"]["Formato"] 		= "Number";		
		$configColumnSalidaCaja["9"]["Formato"] 		= "";		
		$configColumnSalidaCaja["10"]["Formato"] 		= "";	
		$configColumnSalidaCaja["11"]["Formato"] 		= "";				
		$configColumnSalidaCaja["12"]["Formato"] 		= "";				
		
		$configColumnSalidaCaja["0"]["Width"] 		= "20px";		
		$configColumnSalidaCaja["1"]["Width"] 		= "220px";		
		$configColumnSalidaCaja["2"]["Width"] 		= "20px";		
		$configColumnSalidaCaja["3"]["Width"] 		= "20px";		
		$configColumnSalidaCaja["4"]["Width"] 		= "20px";		
		$configColumnSalidaCaja["5"]["Width"] 		= "200px";		
		$configColumnSalidaCaja["6"]["Width"] 		= "20px";		
		$configColumnSalidaCaja["7"]["Width"] 		= "20px";		
		$configColumnSalidaCaja["8"]["Width"] 		= "120px";		
		$configColumnSalidaCaja["9"]["Width"] 		= "120px";		
		$configColumnSalidaCaja["10"]["Width"] 		= "220px";	
		$configColumnSalidaCaja["11"]["Width"] 		= "80px";	
		$configColumnSalidaCaja["12"]["Width"] 		= "80px";	
		
		$configColumnSalidaCaja["0"]["Total"] 		= False;		
		$configColumnSalidaCaja["1"]["Total"] 		= False;		
		$configColumnSalidaCaja["2"]["Total"] 		= False;		
		$configColumnSalidaCaja["3"]["Total"] 		= False;		
		$configColumnSalidaCaja["4"]["Total"] 		= False;		
		$configColumnSalidaCaja["5"]["Total"] 		= False;		
		$configColumnSalidaCaja["6"]["Total"] 		= False;		
		$configColumnSalidaCaja["7"]["Total"] 		= False;		
		$configColumnSalidaCaja["8"]["Total"] 		= True;		
		$configColumnSalidaCaja["9"]["Total"] 		= False;		
		$configColumnSalidaCaja["10"]["Total"] 		= False;	
		$configColumnSalidaCaja["11"]["Total"] 		= False;	
		$configColumnSalidaCaja["12"]["Total"] 		= False;		
		$objCashOutCordoba 	= $objCashOut;
		
		if($objCashOutCordoba != null)
		$objCashOutCordoba = array_filter($objCashOutCordoba,function($var){
			if (strtoupper($var["moneda"]) == "CORDOBA")
				return true;
		});
		
		$resultadoSalidaCajaCordoba = helper_reporteGeneralCreateTable(
				$objCashOutCordoba,
				$configColumnSalidaCaja,
				'0px',
				'CORDOBA = SALIDA DE CAJA'
		);
		
		
		$totalCordoba = $totalCordoba - ($resultadoSalidaCajaCordoba["table"] === 0 ? 0 : $resultadoSalidaCajaCordoba["configColumn"][8]["TotalValor"]);		
		
		?>
		
		
		<?php 
		$widthX 	= 0;
		$columnX 	= 0;
		
		
		if($resultadoAbonosCordoba["columnas"] != 0 ){
			$widthX 	= $resultadoAbonosCordoba["width"];
			$columnX 	= $resultadoAbonosCordoba["columnas"];
			
		}
		else if($resultadoVentaContadoCordoba["columnas"] != 0 ){
			$widthX 	= $resultadoVentaContadoCordoba["width"];
			$columnX 	= $resultadoVentaContadoCordoba["columnas"];
		}
		else if($resultadoVentaCreditoCordoba["columnas"] != 0 ){
			$widthX 	= $resultadoVentaCreditoCordoba["width"];
			$columnX 	= $resultadoVentaCreditoCordoba["columnas"];
		}
		else if($resultadoIngresoCajaCordoba["columnas"] != 0 ){
			$widthX 	= $resultadoIngresoCajaCordoba["width"];
			$columnX 	= $resultadoIngresoCajaCordoba["columnas"];
		}		
		else if($resultadoSalidaCajaCordoba["columnas"] != 0 ){
			$widthX 	= $resultadoSalidaCajaCordoba["width"];
			$columnX 	= $resultadoSalidaCajaCordoba["columnas"];
		}		
		else{
			$widthX 	= "0px";
			$columnX 	= 0;
		}
		
		
		
		echo helper_reporteGeneralCreateEncabezado(
			'MOVIMIENTOS DE CAJA',
			$objCompany->name,
			$columnX,
			'DEL '.$startOn.' AL '.$endOn,
			"",
			"",
			"800px" 
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
		//if($resultadoVentaCreditoCordoba["table"] !== 0)
		//echo $resultadoVentaCreditoCordoba["table"];
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
		$configTotalesColumns[0]["Width"] = "400px";
		$detailTotales[0]["total"] = $totalCordoba;
		
		
		$rosTotales = helper_reporteGeneralCreateTable(
				$detailTotales,
				$configTotalesColumns,
				'0',
				NULL
				
		);
		
		echo $rosTotales["table"];
		
		?>
		
		
		
		<br/>
	

		<?php 
		echo helper_reporteGeneralCreateFirma(	
			$objFirmaEncription,
			$columnX,
			"800px" 
		);
		?>
		
		
	</body>	
</html>