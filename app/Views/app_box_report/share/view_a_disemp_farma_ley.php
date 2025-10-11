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
		$width0  		= "1600px";//99
		$width1  		= "0px";//480
		$width2  		= "0px";//480
		$width3  		= "0px";//480
		$width4  		= "0px";//97
		$width5  		= "0px";//150
		$width6  		= "0px";//98
		$width7  		= "0px";//110
		$width8  		= "0px";//96
		$width9  		= "0px";//115
		$width10  		= "0px";//480
		$width11  		= "0px";//0
		$width12  		= "0px";//0
		
		
		$totalCordoba 	= 0;
		$totalDolares 	= 0;
		$widthX 		= 1600;
		$columnX 		= 13;
		
		echo helper_reporteGeneralCreateEncabezado(
			'MOVIMIENTOS DE CAJA',
			$objCompany->name,
			$columnX,
			'DEL '.$startOn.' AL '.$endOn,
			"",
			"",
			$widthX."px"
		);
		
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
		$configColumnAbonos["3"]["Formato"] 		= "DateTime";		
		$configColumnAbonos["4"]["Formato"] 		= "";		
		$configColumnAbonos["5"]["Formato"] 		= "";		
		$configColumnAbonos["6"]["Formato"] 		= "";		
		$configColumnAbonos["7"]["Formato"] 		= "";		
		$configColumnAbonos["8"]["Formato"] 		= "Number";		
		$configColumnAbonos["9"]["Formato"] 		= "";		
		$configColumnAbonos["10"]["Formato"] 		= "";	
		$configColumnAbonos["11"]["Formato"] 		= "";	
		$configColumnAbonos["12"]["Formato"] 		= "";	
		
		
		$configColumnAbonos["0"]["Width"] 		= $width0;		
		$configColumnAbonos["1"]["Width"] 		= $width1;		
		$configColumnAbonos["2"]["Width"] 		= $width2;		
		$configColumnAbonos["3"]["Width"] 		= $width3;		
		$configColumnAbonos["4"]["Width"] 		= $width4;		
		$configColumnAbonos["5"]["Width"] 		= $width5;		
		$configColumnAbonos["6"]["Width"] 		= $width6;		
		$configColumnAbonos["7"]["Width"] 		= $width7;		
		$configColumnAbonos["8"]["Width"] 		= $width8;		
		$configColumnAbonos["9"]["Width"] 		= $width9;		
		$configColumnAbonos["10"]["Width"] 		= $width10;	
		$configColumnAbonos["11"]["Width"] 		= $width11;	
		$configColumnAbonos["12"]["Width"] 		= $width12;	
		
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
			if (strtoupper($var["moneda"]) == "CORDOBA")
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
		if($resultadoAbonosCordoba["table"] !== 0)
		echo $resultadoAbonosCordoba["table"];
		?>
		<br/>
		
		
		
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
		$configColumnVentaContado["3"]["Formato"] 		= "DateTime";		
		$configColumnVentaContado["4"]["Formato"] 		= "";		
		$configColumnVentaContado["5"]["Formato"] 		= "";		
		$configColumnVentaContado["6"]["Formato"] 		= "";		
		$configColumnVentaContado["7"]["Formato"] 		= "";		
		$configColumnVentaContado["8"]["Formato"] 		= "Number";		
		$configColumnVentaContado["9"]["Formato"] 		= "";		
		$configColumnVentaContado["10"]["Formato"] 		= "";		
		$configColumnVentaContado["11"]["Formato"] 		= "";	
		$configColumnVentaContado["12"]["Formato"] 		= "";	

		$configColumnVentaContado["0"]["Width"] 		= $width0;	
		$configColumnVentaContado["1"]["Width"] 		= $width1;	
		$configColumnVentaContado["2"]["Width"] 		= $width2;	
		$configColumnVentaContado["3"]["Width"] 		= $width3;	
		$configColumnVentaContado["4"]["Width"] 		= $width4;	
		$configColumnVentaContado["5"]["Width"] 		= $width5;	
		$configColumnVentaContado["6"]["Width"] 		= $width6;	
		$configColumnVentaContado["7"]["Width"] 		= $width7;	
		$configColumnVentaContado["8"]["Width"] 		= $width8;	
		$configColumnVentaContado["9"]["Width"] 		= $width9;	
		$configColumnVentaContado["10"]["Width"] 		= $width10;		
		$configColumnVentaContado["11"]["Width"] 		= $width11;		
		$configColumnVentaContado["12"]["Width"] 		= $width12;
		
		
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
		if($resultadoVentaContadoCordoba["table"] !== 0)
		echo $resultadoVentaContadoCordoba["table"];
		?>
		<br/>
		
		<?php
		
		$configColumnVentaContadoPuntos["0"]["Titulo"] 		= "Codigo";		
		$configColumnVentaContadoPuntos["1"]["Titulo"] 		= "Cliente";		
		$configColumnVentaContadoPuntos["2"]["Titulo"] 		= "Moneda";		
		$configColumnVentaContadoPuntos["3"]["Titulo"] 		= "Fecha";		
		$configColumnVentaContadoPuntos["4"]["Titulo"] 		= "Fac";		
		$configColumnVentaContadoPuntos["5"]["Titulo"] 		= "Transaccion";		
		$configColumnVentaContadoPuntos["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnVentaContadoPuntos["7"]["Titulo"] 		= "Estado";		
		$configColumnVentaContadoPuntos["8"]["Titulo"] 		= "Monto";		
		$configColumnVentaContadoPuntos["9"]["Titulo"] 		= "Usuario";		
		$configColumnVentaContadoPuntos["10"]["Titulo"] 		= "Nota";	
		$configColumnVentaContadoPuntos["11"]["Titulo"] 		= "Categoria";	
		$configColumnVentaContadoPuntos["12"]["Titulo"] 		= "Sub Categoria";	
		
					 
		$configColumnVentaContadoPuntos["0"]["FiledSouce"] 		= "customerNumber";		
		$configColumnVentaContadoPuntos["1"]["FiledSouce"] 		= "firstName";		
		$configColumnVentaContadoPuntos["2"]["FiledSouce"] 		= "currencyName";		
		$configColumnVentaContadoPuntos["3"]["FiledSouce"] 		= "transactionOn";		
		$configColumnVentaContadoPuntos["4"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnVentaContadoPuntos["5"]["FiledSouce"] 		= "tipo";		
		$configColumnVentaContadoPuntos["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnVentaContadoPuntos["7"]["FiledSouce"] 		= "statusName";		
		$configColumnVentaContadoPuntos["8"]["FiledSouce"] 		= "receiptAmountPoint";		
		$configColumnVentaContadoPuntos["9"]["FiledSouce"] 		= "nickname";		
		$configColumnVentaContadoPuntos["10"]["FiledSouce"] 		= "";	
		$configColumnVentaContadoPuntos["11"]["FiledSouce"] 		= "categoryName";	
		$configColumnVentaContadoPuntos["12"]["FiledSouce"] 		= "categorySubName";	
		
		$configColumnVentaContadoPuntos["0"]["Formato"] 		= "";		
		$configColumnVentaContadoPuntos["1"]["Formato"] 		= "";		
		$configColumnVentaContadoPuntos["2"]["Formato"] 		= "";		
		$configColumnVentaContadoPuntos["3"]["Formato"] 		= "DateTime";		
		$configColumnVentaContadoPuntos["4"]["Formato"] 		= "";		
		$configColumnVentaContadoPuntos["5"]["Formato"] 		= "";		
		$configColumnVentaContadoPuntos["6"]["Formato"] 		= "";		
		$configColumnVentaContadoPuntos["7"]["Formato"] 		= "";		
		$configColumnVentaContadoPuntos["8"]["Formato"] 		= "Number";		
		$configColumnVentaContadoPuntos["9"]["Formato"] 		= "";		
		$configColumnVentaContadoPuntos["10"]["Formato"] 		= "";		
		$configColumnVentaContadoPuntos["11"]["Formato"] 		= "";	
		$configColumnVentaContadoPuntos["12"]["Formato"] 		= "";	

		$configColumnVentaContadoPuntos["0"]["Width"] 		= $width0;	
		$configColumnVentaContadoPuntos["1"]["Width"] 		= $width1;	
		$configColumnVentaContadoPuntos["2"]["Width"] 		= $width2;	
		$configColumnVentaContadoPuntos["3"]["Width"] 		= $width3;	
		$configColumnVentaContadoPuntos["4"]["Width"] 		= $width4;	
		$configColumnVentaContadoPuntos["5"]["Width"] 		= $width5;	
		$configColumnVentaContadoPuntos["6"]["Width"] 		= $width6;	
		$configColumnVentaContadoPuntos["7"]["Width"] 		= $width7;	
		$configColumnVentaContadoPuntos["8"]["Width"] 		= $width8;	
		$configColumnVentaContadoPuntos["9"]["Width"] 		= $width9;	
		$configColumnVentaContadoPuntos["10"]["Width"] 		= $width10;		
		$configColumnVentaContadoPuntos["11"]["Width"] 		= $width11;		
		$configColumnVentaContadoPuntos["12"]["Width"] 		= $width12;
		
		
		$configColumnVentaContadoPuntos["0"]["Total"] 		= False;		
		$configColumnVentaContadoPuntos["1"]["Total"] 		= False;		
		$configColumnVentaContadoPuntos["2"]["Total"] 		= False;		
		$configColumnVentaContadoPuntos["3"]["Total"] 		= False;		
		$configColumnVentaContadoPuntos["4"]["Total"] 		= False;		
		$configColumnVentaContadoPuntos["5"]["Total"] 		= False;		
		$configColumnVentaContadoPuntos["6"]["Total"] 		= False;		
		$configColumnVentaContadoPuntos["7"]["Total"] 		= False;		
		$configColumnVentaContadoPuntos["8"]["Total"] 		= True;		
		$configColumnVentaContadoPuntos["9"]["Total"] 		= False;		
		$configColumnVentaContadoPuntos["10"]["Total"] 		= False;	
		$configColumnVentaContadoPuntos["11"]["Total"] 		= False;	
		$configColumnVentaContadoPuntos["12"]["Total"] 		= False;
	
		$objSalesCordobaPuntos 	= $objSales;		
		if($objSalesCordobaPuntos != null)
		$objSalesCordobaPuntos = array_filter($objSalesCordobaPuntos,function($var){
			
			if (
				strtoupper($var["currencyName"]) == "CORDOBA" && 
				strtoupper($var["tipo"]) != "CREDITO" && 
				$var["receiptAmountPoint"] > 0 
				
			)
				return true;
		});
		
		$resultadoVentaContadoCordobaPuntos = helper_reporteGeneralCreateTable(
			$objSalesCordobaPuntos,
			$configColumnVentaContadoPuntos,
			'0px',
			'CORDOBA = VENTAS DE CONTADO CON PUNTOS'
		);

		
		$totalCordoba 	= $totalCordoba - ($resultadoVentaContadoCordobaPuntos["table"] === 0 ? 0 : $resultadoVentaContadoCordobaPuntos["configColumn"][8]["TotalValor"]);		
		$totalPuntos	= ($resultadoVentaContadoCordobaPuntos["table"] === 0 ? 0 : $resultadoVentaContadoCordobaPuntos["configColumn"][8]["TotalValor"]);
		?>
		
		<?php 
		if($resultadoVentaContadoCordobaPuntos["table"] !== 0)
		echo $resultadoVentaContadoCordobaPuntos["table"];
		?>
		<br/>

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
		$configColumnIngresoCaja["3"]["Formato"] 		= "DateTime";		
		$configColumnIngresoCaja["4"]["Formato"] 		= "";		
		$configColumnIngresoCaja["5"]["Formato"] 		= "";		
		$configColumnIngresoCaja["6"]["Formato"] 		= "";		
		$configColumnIngresoCaja["7"]["Formato"] 		= "";		
		$configColumnIngresoCaja["8"]["Formato"] 		= "Number";		
		$configColumnIngresoCaja["9"]["Formato"] 		= "";		
		$configColumnIngresoCaja["10"]["Formato"] 		= "";	
		$configColumnIngresoCaja["11"]["Formato"] 		= "";		
		$configColumnIngresoCaja["12"]["Formato"] 		= "";		

		$configColumnIngresoCaja["0"]["Width"] 		= $width0;	
		$configColumnIngresoCaja["1"]["Width"] 		= $width1;	
		$configColumnIngresoCaja["2"]["Width"] 		= $width2;	
		$configColumnIngresoCaja["3"]["Width"] 		= $width3;	
		$configColumnIngresoCaja["4"]["Width"] 		= $width4;	
		$configColumnIngresoCaja["5"]["Width"] 		= $width5;	
		$configColumnIngresoCaja["6"]["Width"] 		= $width6;	
		$configColumnIngresoCaja["7"]["Width"] 		= $width7;	
		$configColumnIngresoCaja["8"]["Width"] 		= $width8;	
		$configColumnIngresoCaja["9"]["Width"] 		= $width9;	
		$configColumnIngresoCaja["10"]["Width"] 	= $width10;
		$configColumnIngresoCaja["11"]["Width"] 	= $width11;			
		$configColumnIngresoCaja["12"]["Width"] 	= $width12;			
		
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
		if($resultadoIngresoCajaCordoba["table"] !== 0)
		echo $resultadoIngresoCajaCordoba["table"];
		?>
		<br/>
		
		
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
		$configColumnSalidaCaja["3"]["Formato"] 		= "DateTime";		
		$configColumnSalidaCaja["4"]["Formato"] 		= "";		
		$configColumnSalidaCaja["5"]["Formato"] 		= "";		
		$configColumnSalidaCaja["6"]["Formato"] 		= "";		
		$configColumnSalidaCaja["7"]["Formato"] 		= "";		
		$configColumnSalidaCaja["8"]["Formato"] 		= "Number";		
		$configColumnSalidaCaja["9"]["Formato"] 		= "";		
		$configColumnSalidaCaja["10"]["Formato"] 		= "";	
		$configColumnSalidaCaja["11"]["Formato"] 		= "";				
		$configColumnSalidaCaja["12"]["Formato"] 		= "";				
		
		$configColumnSalidaCaja["0"]["Width"] 		= $width0;	
		$configColumnSalidaCaja["1"]["Width"] 		= $width1;	
		$configColumnSalidaCaja["2"]["Width"] 		= $width2;	
		$configColumnSalidaCaja["3"]["Width"] 		= $width3;	
		$configColumnSalidaCaja["4"]["Width"] 		= $width4;	
		$configColumnSalidaCaja["5"]["Width"] 		= $width5;	
		$configColumnSalidaCaja["6"]["Width"] 		= $width6;	
		$configColumnSalidaCaja["7"]["Width"] 		= $width7;	
		$configColumnSalidaCaja["8"]["Width"] 		= $width8;	
		$configColumnSalidaCaja["9"]["Width"] 		= $width9;	
		$configColumnSalidaCaja["10"]["Width"] 		= $width10;
		$configColumnSalidaCaja["11"]["Width"] 		= $width11;
		$configColumnSalidaCaja["12"]["Width"] 		= $width12;
		
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
		
		<br/>			
	
		<?php 
		if($resultadoSalidaCajaCordoba["table"] !== 0)
		echo $resultadoSalidaCajaCordoba["table"];
		?>
		<br/>
		
	
	
		<?php 		
		$configTotalesColumns[0]["Titulo"] 		= '<span style="font-size:20px;
                 font-weight:bold;
                 font-family:Arial, Helvetica, sans-serif;
                 color:#ffffff;
                 letter-spacing:1px;">
			RESUMEN
		</span>';
				
		$configTotalesColumns[0]["FiledSouce"] 	= "total";
		$configTotalesColumns[0]["Formato"] 	= "";
		$configTotalesColumns[0]["Colspan"] 	= $columnX;		
		$configTotalesColumns[0]["Width"] 		= "350px";
		
		
		
		$detailTotales[0]["total"] 				= '
		<table style="width:100%;">
			<tr>
				<td>
					<span style="font-size:20px;
					 font-weight:bold;
					 font-family:Arial, Helvetica, sans-serif;
					 color:#008000;
					 letter-spacing:1px;">
						Total Efectivo:
					</span>
				</td>
				<td>
					<span style="font-size:20px;
					 font-weight:bold;
					 font-family:Arial, Helvetica, sans-serif;
					 color:#008000;
					 letter-spacing:1px;">
						C$'.number_format($totalCordoba + $totalPuntos,2,".",",").'</span>

				</td>
			</tr>
		</table>
		';
		
		
		$detailTotales[1]["total"] 				= '
		<table style="width:100%;">
			<tr>
				<td>
					<span style="font-size:20px;
					 font-weight:bold;
					 font-family:Arial, Helvetica, sans-serif;
					 color:#ff0000;
					 letter-spacing:1px;">
						Total Puntos:
					</span>
				</td>
				<td>
					<span style="font-size:20px;
					 font-weight:bold;
					 font-family:Arial, Helvetica, sans-serif;
					 color:#ff0000;
					 letter-spacing:1px;">
						'.$totalPuntos.'</span>

				</td>
			</tr>
		</table>
		';
		
		
		$rosTotales = helper_reporteGeneralCreateTable(
				$detailTotales,
				$configTotalesColumns,
				'0',
				NULL
				
		);		
		echo $rosTotales["table"];
		?>
		<br/>
		
	</body>	
</html>