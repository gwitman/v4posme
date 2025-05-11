<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $objFirmaEncription; ?></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		
		
		<?php 
		echo helper_reporteGeneralCreateStyle();
		
		$width0  		= "600px";//99
		$width1  		= "300px";//480
		$width4  		= "15px";//97
		$width5  		= "17px";//150
		$width6  		= "16px";//98
		$width7  		= "17px";//110
		$width8  		= "15px";//96
		$width9  		= "10px";//115
		$width10  		= "60px";//480
		$width00  		= "0px";//0
		
		
		$width10_  		= "30px";//240
		$width11_  		= "30px";//240
		$totalCordoba 	= 0;
		$totalDolares 	= 0;
		$widthX 		= 1050;
		$columnX 		= 10;
		
		?>
		
		
		
	</head>
	<body style="font-family:monospace;font-size:smaller;margin:0px 0px 0px 0px"> 
		
		
		<?php
		
		echo helper_reporteGeneralCreateEncabezado(
			'<h2 style="margin:0px;" >ARQUEO DIARIO</h2>',
			'<h2 style="margin:0px;" >'.$objCompany->name." ".$branchName.'</h2>',
			$columnX,
			'<h2 style="margin:0px; " >DEL '.$startOn.' AL '.$endOn."</h2>",
			"",
			"",
			$widthX."px" 
		);
		
		
		/************************************/
		/*ABONOS CORDOBAS*/
		$configColumnAbonos["0"]["Titulo"]      = "Fecha";
		$configColumnAbonos["0"]["FiledSouce"]  = "transactionOn";
		$configColumnAbonos["0"]["Colspan"]     = "1";
		$configColumnAbonos["0"]["Formato"]     = "Date";
		$configColumnAbonos["0"]["Width"]       = $width0;
		$configColumnAbonos["0"]["Total"]       = False;

		$configColumnAbonos["1"]["Titulo"]      = "Cliente";
		$configColumnAbonos["1"]["FiledSouce"]  = "firstName";
		$configColumnAbonos["1"]["Colspan"]     = "1";
		$configColumnAbonos["1"]["Formato"]     = "";
		$configColumnAbonos["1"]["Width"]       = $width1;
		$configColumnAbonos["1"]["Total"]       = False;

		$configColumnAbonos["4"]["Titulo"]      = "Fac";
		$configColumnAbonos["4"]["FiledSouce"]  = "Fac";
		$configColumnAbonos["4"]["Colspan"]     = "1";
		$configColumnAbonos["4"]["Formato"]     = "";
		$configColumnAbonos["4"]["Width"]       = $width4;
		$configColumnAbonos["4"]["Total"]       = False;

		$configColumnAbonos["5"]["Titulo"]      = "Transaccion";
		$configColumnAbonos["5"]["FiledSouce"]  = "transactionName";
		$configColumnAbonos["5"]["Colspan"]     = "1";
		$configColumnAbonos["5"]["Formato"]     = "";
		$configColumnAbonos["5"]["Width"]       = $width5;
		$configColumnAbonos["5"]["Total"]       = False;

		$configColumnAbonos["6"]["Titulo"]      = "Tran. Numero";
		$configColumnAbonos["6"]["FiledSouce"]  = "transactionNumber";
		$configColumnAbonos["6"]["Colspan"]     = "1";
		$configColumnAbonos["6"]["Formato"]     = "";
		$configColumnAbonos["6"]["Width"]       = $width6;
		$configColumnAbonos["6"]["Total"]       = False;

		$configColumnAbonos["7"]["Titulo"]      = "Estado";
		$configColumnAbonos["7"]["FiledSouce"]  = "estado";
		$configColumnAbonos["7"]["Colspan"]     = "1";
		$configColumnAbonos["7"]["Formato"]     = "";
		$configColumnAbonos["7"]["Width"]       = $width7;
		$configColumnAbonos["7"]["Total"]       = False;

		$configColumnAbonos["8"]["Titulo"]      = "Monto";
		$configColumnAbonos["8"]["FiledSouce"]  = "montoFac";
		$configColumnAbonos["8"]["Colspan"]     = "1";
		$configColumnAbonos["8"]["Formato"]     = "Number";
		$configColumnAbonos["8"]["Width"]       = $width8;
		$configColumnAbonos["8"]["Total"]       = True;

		$configColumnAbonos["9"]["Titulo"]      = "Vendedor";
		$configColumnAbonos["9"]["FiledSouce"]  = "nickname";
		$configColumnAbonos["9"]["Colspan"]     = "1";
		$configColumnAbonos["9"]["Formato"]     = "";
		$configColumnAbonos["9"]["Width"]       = $width9;
		$configColumnAbonos["9"]["Total"]       = False;

		$configColumnAbonos["10"]["Titulo"]     = "Nota";
		$configColumnAbonos["10"]["FiledSouce"] = "note";
		$configColumnAbonos["10"]["Colspan"]    = "2";
		$configColumnAbonos["10"]["Formato"]    = "";
		$configColumnAbonos["10"]["Width"]      = $width10;
		$configColumnAbonos["10"]["Total"]      = False;

		$configColumnAbonos["11"]["Titulo"]     = "";
		$configColumnAbonos["11"]["FiledSouce"] = "";
		$configColumnAbonos["11"]["Colspan"]    = "0";
		$configColumnAbonos["11"]["Formato"]    = "";
		$configColumnAbonos["11"]["Width"]      = $width00;
		$configColumnAbonos["11"]["Total"]      = False;


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
		/************************************/
		/*VENTAS CONTADO CORDOBAS*/
		
		$configColumnVentaContado["0"]["Titulo"]      = "Fecha";
		$configColumnVentaContado["0"]["FiledSouce"]  = "transactionOn";
		$configColumnVentaContado["0"]["Formato"]     = "Date";
		$configColumnVentaContado["0"]["Width"]       = $width0;
		$configColumnVentaContado["0"]["Total"]       = False;

		$configColumnVentaContado["1"]["Titulo"]      = "Cliente";
		$configColumnVentaContado["1"]["FiledSouce"]  = "firstName";
		$configColumnVentaContado["1"]["Formato"]     = "";
		$configColumnVentaContado["1"]["Width"]       = $width1;
		$configColumnVentaContado["1"]["Total"]       = False;

		$configColumnVentaContado["4"]["Titulo"]      = "Fac";
		$configColumnVentaContado["4"]["FiledSouce"]  = "transactionNumber";
		$configColumnVentaContado["4"]["Formato"]     = "";
		$configColumnVentaContado["4"]["Width"]       = $width4;
		$configColumnVentaContado["4"]["Total"]       = False;

		$configColumnVentaContado["5"]["Titulo"]      = "Transaccion";
		$configColumnVentaContado["5"]["FiledSouce"]  = "tipo";
		$configColumnVentaContado["5"]["Formato"]     = "";
		$configColumnVentaContado["5"]["Width"]       = $width5;
		$configColumnVentaContado["5"]["Total"]       = False;

		$configColumnVentaContado["6"]["Titulo"]      = "Tran. Numero";
		$configColumnVentaContado["6"]["FiledSouce"]  = "transactionNumber";
		$configColumnVentaContado["6"]["Formato"]     = "";
		$configColumnVentaContado["6"]["Width"]       = $width6;
		$configColumnVentaContado["6"]["Total"]       = False;

		$configColumnVentaContado["7"]["Titulo"]      = "Estado";
		$configColumnVentaContado["7"]["FiledSouce"]  = "statusName";
		$configColumnVentaContado["7"]["Formato"]     = "";
		$configColumnVentaContado["7"]["Width"]       = $width7;
		$configColumnVentaContado["7"]["Total"]       = False;

		$configColumnVentaContado["8"]["Titulo"]      = "Monto";
		$configColumnVentaContado["8"]["FiledSouce"]  = "totalDocument";
		$configColumnVentaContado["8"]["Formato"]     = "Number";
		$configColumnVentaContado["8"]["Width"]       = $width8;
		$configColumnVentaContado["8"]["Total"]       = True;

		$configColumnVentaContado["9"]["Titulo"]      = "Vendedor";
		$configColumnVentaContado["9"]["FiledSouce"]  = "nickname";
		$configColumnVentaContado["9"]["Formato"]     = "";
		$configColumnVentaContado["9"]["Width"]       = $width9;
		$configColumnVentaContado["9"]["Total"]       = False;

		$configColumnVentaContado["10"]["Titulo"]     = "Nota";
		$configColumnVentaContado["10"]["FiledSouce"] = "";
		$configColumnVentaContado["10"]["Formato"]    = "";
		$configColumnVentaContado["10"]["Width"]      = $width10_;
		$configColumnVentaContado["10"]["Total"]      = False;

		$configColumnVentaContado["11"]["Titulo"]     = "Categoria";
		$configColumnVentaContado["11"]["FiledSouce"] = "categoryName";
		$configColumnVentaContado["11"]["Formato"]    = "";
		$configColumnVentaContado["11"]["Width"]      = $width11_;
		$configColumnVentaContado["11"]["Total"]      = False;

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
		
		<?php
		/************************************/
		/*VENTAS CREDITO CORDOBA*/
		
		$configColumnVentaCredito["0"]["Titulo"]      = "Fecha";
		$configColumnVentaCredito["0"]["FiledSouce"]  = "transactionOn";
		$configColumnVentaCredito["0"]["Formato"]     = "Date";
		$configColumnVentaCredito["0"]["Width"]       = $width0;
		$configColumnVentaCredito["0"]["Total"]       = False;

		$configColumnVentaCredito["1"]["Titulo"]      = "Cliente";
		$configColumnVentaCredito["1"]["FiledSouce"]  = "firstName";
		$configColumnVentaCredito["1"]["Formato"]     = "";
		$configColumnVentaCredito["1"]["Width"]       = $width1;
		$configColumnVentaCredito["1"]["Total"]       = False;

		$configColumnVentaCredito["4"]["Titulo"]      = "Fac";
		$configColumnVentaCredito["4"]["FiledSouce"]  = "transactionNumber";
		$configColumnVentaCredito["4"]["Formato"]     = "";
		$configColumnVentaCredito["4"]["Width"]       = $width4;
		$configColumnVentaCredito["4"]["Total"]       = False;

		$configColumnVentaCredito["5"]["Titulo"]      = "Transaccion";
		$configColumnVentaCredito["5"]["FiledSouce"]  = "tipo";
		$configColumnVentaCredito["5"]["Formato"]     = "";
		$configColumnVentaCredito["5"]["Width"]       = $width5;
		$configColumnVentaCredito["5"]["Total"]       = False;

		$configColumnVentaCredito["6"]["Titulo"]      = "Tran. Numero";
		$configColumnVentaCredito["6"]["FiledSouce"]  = "transactionNumber";
		$configColumnVentaCredito["6"]["Formato"]     = "";
		$configColumnVentaCredito["6"]["Width"]       = $width6;
		$configColumnVentaCredito["6"]["Total"]       = False;

		$configColumnVentaCredito["7"]["Titulo"]      = "Estado";
		$configColumnVentaCredito["7"]["FiledSouce"]  = "statusName";
		$configColumnVentaCredito["7"]["Formato"]     = "";
		$configColumnVentaCredito["7"]["Width"]       = $width7;
		$configColumnVentaCredito["7"]["Total"]       = False;

		$configColumnVentaCredito["8"]["Titulo"]      = "Monto";
		$configColumnVentaCredito["8"]["FiledSouce"]  = "receiptAmount";
		$configColumnVentaCredito["8"]["Formato"]     = "Number";
		$configColumnVentaCredito["8"]["Width"]       = $width8;
		$configColumnVentaCredito["8"]["Total"]       = True;

		$configColumnVentaCredito["9"]["Titulo"]      = "Vendedor";
		$configColumnVentaCredito["9"]["FiledSouce"]  = "nickname";
		$configColumnVentaCredito["9"]["Formato"]     = "";
		$configColumnVentaCredito["9"]["Width"]       = $width9;
		$configColumnVentaCredito["9"]["Total"]       = False;

		$configColumnVentaCredito["10"]["Titulo"]     = "Nota";
		$configColumnVentaCredito["10"]["FiledSouce"] = "";
		$configColumnVentaCredito["10"]["Formato"]    = "";
		$configColumnVentaCredito["10"]["Width"]      = $width10_;
		$configColumnVentaCredito["10"]["Total"]      = False;

		$configColumnVentaCredito["11"]["Titulo"]     = "Categoria";
		$configColumnVentaCredito["11"]["FiledSouce"] = "categoryName";
		$configColumnVentaCredito["11"]["Formato"]    = "";
		$configColumnVentaCredito["11"]["Width"]      = $width11_;
		$configColumnVentaCredito["11"]["Total"]      = False;

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
		
		<br/>
		<?php 
		if($resultadoVentaCreditoCordoba["table"] !== 0)
		echo $resultadoVentaCreditoCordoba["table"];
		?>
		
		
		<?php
	
		/************************************/
		/*INGRESO A CAJA CORDOBA*/		
				
		$configColumnIngresoCaja["0"]["Titulo"]      = "Fecha";
		$configColumnIngresoCaja["0"]["FiledSouce"]  = "transactionOn";
		$configColumnIngresoCaja["0"]["Formato"]     = "Date";
		$configColumnIngresoCaja["0"]["Width"]       = $width0;
		$configColumnIngresoCaja["0"]["Total"]       = False;

		$configColumnIngresoCaja["1"]["Titulo"]      = "Cliente";
		$configColumnIngresoCaja["1"]["FiledSouce"]  = "transactionNumber";
		$configColumnIngresoCaja["1"]["Formato"]     = "";
		$configColumnIngresoCaja["1"]["Width"]       = $width1;
		$configColumnIngresoCaja["1"]["Total"]       = False;

		$configColumnIngresoCaja["4"]["Titulo"]      = "Fac";
		$configColumnIngresoCaja["4"]["FiledSouce"]  = "transactionNumber";
		$configColumnIngresoCaja["4"]["Formato"]     = "";
		$configColumnIngresoCaja["4"]["Width"]       = $width4;
		$configColumnIngresoCaja["4"]["Total"]       = False;

		$configColumnIngresoCaja["5"]["Titulo"]      = "Transaccion";
		$configColumnIngresoCaja["5"]["FiledSouce"]  = "transactionName";
		$configColumnIngresoCaja["5"]["Formato"]     = "";
		$configColumnIngresoCaja["5"]["Width"]       = $width5;
		$configColumnIngresoCaja["5"]["Total"]       = False;

		$configColumnIngresoCaja["6"]["Titulo"]      = "Tran. Numero";
		$configColumnIngresoCaja["6"]["FiledSouce"]  = "transactionNumber";
		$configColumnIngresoCaja["6"]["Formato"]     = "";
		$configColumnIngresoCaja["6"]["Width"]       = $width6;
		$configColumnIngresoCaja["6"]["Total"]       = False;

		$configColumnIngresoCaja["7"]["Titulo"]      = "Estado";
		$configColumnIngresoCaja["7"]["FiledSouce"]  = "estado";
		$configColumnIngresoCaja["7"]["Formato"]     = "";
		$configColumnIngresoCaja["7"]["Width"]       = $width7;
		$configColumnIngresoCaja["7"]["Total"]       = False;

		$configColumnIngresoCaja["8"]["Titulo"]      = "Monto";
		$configColumnIngresoCaja["8"]["FiledSouce"]  = "montoTransaccion";
		$configColumnIngresoCaja["8"]["Formato"]     = "Number";
		$configColumnIngresoCaja["8"]["Width"]       = $width8;
		$configColumnIngresoCaja["8"]["Total"]       = True;

		$configColumnIngresoCaja["9"]["Titulo"]      = "Vendedor";
		$configColumnIngresoCaja["9"]["FiledSouce"]  = "nickname";
		$configColumnIngresoCaja["9"]["Formato"]     = "";
		$configColumnIngresoCaja["9"]["Width"]       = $width9;
		$configColumnIngresoCaja["9"]["Total"]       = False;

		$configColumnIngresoCaja["10"]["Titulo"]     = "Nota";
		$configColumnIngresoCaja["10"]["FiledSouce"] = "note";
		$configColumnIngresoCaja["10"]["Formato"]    = "";
		$configColumnIngresoCaja["10"]["Width"]      = $width10_;
		$configColumnIngresoCaja["10"]["Total"]      = False;

		$configColumnIngresoCaja["11"]["Titulo"]     = "Tipo";
		$configColumnIngresoCaja["11"]["FiledSouce"] = "tipoEntrada";
		$configColumnIngresoCaja["11"]["Formato"]    = "";
		$configColumnIngresoCaja["11"]["Width"]      = $width11_;
		$configColumnIngresoCaja["11"]["Total"]      = False;
				
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
		
		
		
		<br/>
		<?php 
		if($resultadoIngresoCajaCordoba["table"] !== 0)
		echo $resultadoIngresoCajaCordoba["table"];
		?>
		<br/>
		
		
		<?php
		
		/************************************/
		/*SALIDA DE CAJA CORDOBA*/
		
		$configColumnSalidaCaja["0"]["Titulo"]      = "Fecha";
		$configColumnSalidaCaja["0"]["FiledSouce"]  = "transactionOn";
		$configColumnSalidaCaja["0"]["Formato"]     = "Date";
		$configColumnSalidaCaja["0"]["Width"]       = $width0;
		$configColumnSalidaCaja["0"]["Total"]       = False;

		$configColumnSalidaCaja["1"]["Titulo"]      = "Cliente";
		$configColumnSalidaCaja["1"]["FiledSouce"]  = "transactionNumber";
		$configColumnSalidaCaja["1"]["Formato"]     = "";
		$configColumnSalidaCaja["1"]["Width"]       = $width1;
		$configColumnSalidaCaja["1"]["Total"]       = False;

		$configColumnSalidaCaja["4"]["Titulo"]      = "Fac";
		$configColumnSalidaCaja["4"]["FiledSouce"]  = "transactionNumber";
		$configColumnSalidaCaja["4"]["Formato"]     = "";
		$configColumnSalidaCaja["4"]["Width"]       = $width4;
		$configColumnSalidaCaja["4"]["Total"]       = False;

		$configColumnSalidaCaja["5"]["Titulo"]      = "Transaccion";
		$configColumnSalidaCaja["5"]["FiledSouce"]  = "transactionName";
		$configColumnSalidaCaja["5"]["Formato"]     = "";
		$configColumnSalidaCaja["5"]["Width"]       = $width5;
		$configColumnSalidaCaja["5"]["Total"]       = False;

		$configColumnSalidaCaja["6"]["Titulo"]      = "Tran. Numero";
		$configColumnSalidaCaja["6"]["FiledSouce"]  = "transactionNumber";
		$configColumnSalidaCaja["6"]["Formato"]     = "";
		$configColumnSalidaCaja["6"]["Width"]       = $width6;
		$configColumnSalidaCaja["6"]["Total"]       = False;

		$configColumnSalidaCaja["7"]["Titulo"]      = "Estado";
		$configColumnSalidaCaja["7"]["FiledSouce"]  = "estado";
		$configColumnSalidaCaja["7"]["Formato"]     = "";
		$configColumnSalidaCaja["7"]["Width"]       = $width7;
		$configColumnSalidaCaja["7"]["Total"]       = False;

		$configColumnSalidaCaja["8"]["Titulo"]      = "Monto";
		$configColumnSalidaCaja["8"]["FiledSouce"]  = "montoTransaccion";
		$configColumnSalidaCaja["8"]["Formato"]     = "Number";
		$configColumnSalidaCaja["8"]["Width"]       = $width8;
		$configColumnSalidaCaja["8"]["Total"]       = True;

		$configColumnSalidaCaja["9"]["Titulo"]      = "Vendedor";
		$configColumnSalidaCaja["9"]["FiledSouce"]  = "nickname";
		$configColumnSalidaCaja["9"]["Formato"]     = "";
		$configColumnSalidaCaja["9"]["Width"]       = $width9;
		$configColumnSalidaCaja["9"]["Total"]       = False;

		$configColumnSalidaCaja["10"]["Titulo"]     = "Nota";
		$configColumnSalidaCaja["10"]["FiledSouce"] = "note";
		$configColumnSalidaCaja["10"]["Formato"]    = "";
		$configColumnSalidaCaja["10"]["Width"]      = $width10_;
		$configColumnSalidaCaja["10"]["Total"]      = False;

		$configColumnSalidaCaja["11"]["Titulo"]     = "Tipo";
		$configColumnSalidaCaja["11"]["FiledSouce"] = "tipoSalida";
		$configColumnSalidaCaja["11"]["Formato"]    = "";
		$configColumnSalidaCaja["11"]["Width"]      = $width11_;
		$configColumnSalidaCaja["11"]["Total"]      = False;

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
		
		<?php 
		$configTotalesColumns[0]["Titulo"] 		= "Total Cordoba";			
		$configTotalesColumns[0]["FiledSouce"] 	= "total";
		$configTotalesColumns[0]["Formato"] 	= "Number";
		$configTotalesColumns[0]["Style"] 		= "font-weight: bold;font-size:large;";
		$configTotalesColumns[0]["Colspan"] 	= $columnX;				
		$configTotalesColumns[0]["Width"] 		= (str_replace("px", "", $widthX) /*/ 2*/ )."px";
		$detailTotales[0]["total"] 				= $totalCordoba;
		
		
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
		
		/************************************/
		/*ABONOS DOLARES*/
		$configColumnAbonosDolares["0"]["Titulo"]      = "Fecha";
		$configColumnAbonosDolares["0"]["FiledSouce"]  = "transactionOn";
		$configColumnAbonosDolares["0"]["Formato"]     = "Date";
		$configColumnAbonosDolares["0"]["Width"]       = $width0;
		$configColumnAbonosDolares["0"]["Total"]       = False;

		$configColumnAbonosDolares["1"]["Titulo"]      = "Cliente";
		$configColumnAbonosDolares["1"]["FiledSouce"]  = "firstName";
		$configColumnAbonosDolares["1"]["Formato"]     = "";
		$configColumnAbonosDolares["1"]["Width"]       = $width1;
		$configColumnAbonosDolares["1"]["Total"]       = False;

		$configColumnAbonosDolares["4"]["Titulo"]      = "Fac";
		$configColumnAbonosDolares["4"]["FiledSouce"]  = "Fac";
		$configColumnAbonosDolares["4"]["Formato"]     = "";
		$configColumnAbonosDolares["4"]["Width"]       = $width4;
		$configColumnAbonosDolares["4"]["Total"]       = False;

		$configColumnAbonosDolares["5"]["Titulo"]      = "Transaccion";
		$configColumnAbonosDolares["5"]["FiledSouce"]  = "transactionName";
		$configColumnAbonosDolares["5"]["Formato"]     = "";
		$configColumnAbonosDolares["5"]["Width"]       = $width5;
		$configColumnAbonosDolares["5"]["Total"]       = False;

		$configColumnAbonosDolares["6"]["Titulo"]      = "Tran. Numero";
		$configColumnAbonosDolares["6"]["FiledSouce"]  = "transactionNumber";
		$configColumnAbonosDolares["6"]["Formato"]     = "";
		$configColumnAbonosDolares["6"]["Width"]       = $width6;
		$configColumnAbonosDolares["6"]["Total"]       = False;

		$configColumnAbonosDolares["7"]["Titulo"]      = "Estado";
		$configColumnAbonosDolares["7"]["FiledSouce"]  = "estado";
		$configColumnAbonosDolares["7"]["Formato"]     = "";
		$configColumnAbonosDolares["7"]["Width"]       = $width7;
		$configColumnAbonosDolares["7"]["Total"]       = False;

		$configColumnAbonosDolares["8"]["Titulo"]      = "Monto";
		$configColumnAbonosDolares["8"]["FiledSouce"]  = "montoFac";
		$configColumnAbonosDolares["8"]["Formato"]     = "Number";
		$configColumnAbonosDolares["8"]["Width"]       = $width8;
		$configColumnAbonosDolares["8"]["Total"]       = True;

		$configColumnAbonosDolares["9"]["Titulo"]      = "Vendedor";
		$configColumnAbonosDolares["9"]["FiledSouce"]  = "nickname";
		$configColumnAbonosDolares["9"]["Formato"]     = "";
		$configColumnAbonosDolares["9"]["Width"]       = $width9;
		$configColumnAbonosDolares["9"]["Total"]       = False;

		$configColumnAbonosDolares["10"]["Titulo"]     = "Nota";
		$configColumnAbonosDolares["10"]["FiledSouce"] = "note";
		$configColumnAbonosDolares["10"]["Formato"]    = "";
		$configColumnAbonosDolares["10"]["Width"]      = $width10;
		$configColumnAbonosDolares["10"]["Total"]      = False;

		$configColumnAbonosDolares["11"]["Titulo"]     = "";
		$configColumnAbonosDolares["11"]["FiledSouce"] = "";
		$configColumnAbonosDolares["11"]["Formato"]    = "";
		$configColumnAbonosDolares["11"]["Width"]      = $width00;
		$configColumnAbonosDolares["11"]["Total"]      = False;

		
		
		$objDetailDolar 	= $objDetail;
		if($objDetailDolar != null)
		$objDetailDolar 	= array_filter($objDetailDolar,function($var){
			if (strtoupper($var["moneda"]) == "DOLAR")
				return true;
		});
		
		$resultadoAbonosDolar = helper_reporteGeneralCreateTable(
			$objDetailDolar,
			$configColumnAbonosDolares,
			'0px',
			"DOLARES = LISTA DE ABONOS/ ABONOS AL CAPITAL / CANCELACION ANTICIPADA ",
			'68c778',
			'black'
		);
		
		$totalDolares = 0 + ($resultadoAbonosDolar["table"] === 0 ? 0 : $resultadoAbonosDolar["configColumn"][8]["TotalValor"]);
		
		?>
		
		
		<?php 
		if($resultadoAbonosDolar["table"] !== 0)
		echo $resultadoAbonosDolar["table"];
		?>
		<br/>


		<?php
		
		/************************************/
		/*VENTAS CONTADO DOLARES*/
		
		$configColumnVentaContadoDolares["0"]["Titulo"]      = "Fecha";
		$configColumnVentaContadoDolares["0"]["FiledSouce"]  = "transactionOn";
		$configColumnVentaContadoDolares["0"]["Formato"]     = "Date";
		$configColumnVentaContadoDolares["0"]["Width"]       = $width0;
		$configColumnVentaContadoDolares["0"]["Total"]       = False;

		$configColumnVentaContadoDolares["1"]["Titulo"]      = "Cliente";
		$configColumnVentaContadoDolares["1"]["FiledSouce"]  = "firstName";
		$configColumnVentaContadoDolares["1"]["Formato"]     = "";
		$configColumnVentaContadoDolares["1"]["Width"]       = $width1;
		$configColumnVentaContadoDolares["1"]["Total"]       = False;

		$configColumnVentaContadoDolares["4"]["Titulo"]      = "Fac";
		$configColumnVentaContadoDolares["4"]["FiledSouce"]  = "transactionNumber";
		$configColumnVentaContadoDolares["4"]["Formato"]     = "";
		$configColumnVentaContadoDolares["4"]["Width"]       = $width4;
		$configColumnVentaContadoDolares["4"]["Total"]       = False;

		$configColumnVentaContadoDolares["5"]["Titulo"]      = "Transaccion";
		$configColumnVentaContadoDolares["5"]["FiledSouce"]  = "tipo";
		$configColumnVentaContadoDolares["5"]["Formato"]     = "";
		$configColumnVentaContadoDolares["5"]["Width"]       = $width5;
		$configColumnVentaContadoDolares["5"]["Total"]       = False;

		$configColumnVentaContadoDolares["6"]["Titulo"]      = "Tran. Numero";
		$configColumnVentaContadoDolares["6"]["FiledSouce"]  = "transactionNumber";
		$configColumnVentaContadoDolares["6"]["Formato"]     = "";
		$configColumnVentaContadoDolares["6"]["Width"]       = $width6;
		$configColumnVentaContadoDolares["6"]["Total"]       = False;

		$configColumnVentaContadoDolares["7"]["Titulo"]      = "Estado";
		$configColumnVentaContadoDolares["7"]["FiledSouce"]  = "statusName";
		$configColumnVentaContadoDolares["7"]["Formato"]     = "";
		$configColumnVentaContadoDolares["7"]["Width"]       = $width7;
		$configColumnVentaContadoDolares["7"]["Total"]       = False;

		$configColumnVentaContadoDolares["8"]["Titulo"]      = "Monto";
		$configColumnVentaContadoDolares["8"]["FiledSouce"]  = "totalDocument";
		$configColumnVentaContadoDolares["8"]["Formato"]     = "Number";
		$configColumnVentaContadoDolares["8"]["Width"]       = $width8;
		$configColumnVentaContadoDolares["8"]["Total"]       = True;

		$configColumnVentaContadoDolares["9"]["Titulo"]      = "Vendedor";
		$configColumnVentaContadoDolares["9"]["FiledSouce"]  = "nickname";
		$configColumnVentaContadoDolares["9"]["Formato"]     = "";
		$configColumnVentaContadoDolares["9"]["Width"]       = $width9;
		$configColumnVentaContadoDolares["9"]["Total"]       = False;

		$configColumnVentaContadoDolares["10"]["Titulo"]     = "Nota";
		$configColumnVentaContadoDolares["10"]["FiledSouce"] = "";
		$configColumnVentaContadoDolares["10"]["Formato"]    = "";
		$configColumnVentaContadoDolares["10"]["Width"]      = $width10_;
		$configColumnVentaContadoDolares["10"]["Total"]      = False;

		$configColumnVentaContadoDolares["11"]["Titulo"]     = "Categoria";
		$configColumnVentaContadoDolares["11"]["FiledSouce"] = "categoryName";
		$configColumnVentaContadoDolares["11"]["Formato"]    = "";
		$configColumnVentaContadoDolares["11"]["Width"]      = $width11_;
		$configColumnVentaContadoDolares["11"]["Total"]      = False;

		
		
		$objSalesDolar 		= $objSales;
		if($objSalesDolar != null)
		$objSalesDolar = array_filter($objSalesDolar,function($var){
			if (
				strtoupper($var["currencyName"]) == "DOLAR" && 
				strtoupper($var["tipo"]) != "CREDITO"
			)
				return true;
		});
		
		
		
		$resultadoVentaContadoDolar = helper_reporteGeneralCreateTable(
			$objSalesDolar,
			$configColumnVentaContadoDolares,
			'0px',
			'DOLARES = VENTAS DE CONTADO',
			'68c778',
			'black'
		);
		$totalDolares = $totalDolares + ($resultadoVentaContadoDolar["table"] === 0 ? 0 : $resultadoVentaContadoDolar["configColumn"][8]["TotalValor"]);
		?>
		
		<?php 
		if($resultadoVentaContadoDolar["table"] !== 0)
		echo $resultadoVentaContadoDolar["table"];
		?>
		<br/>
		
		
		<?php
		
		
		/************************************/
		/*VENTAS CREDITO DOLARES*/
		
		$configColumnVentaCreditoDolares["0"]["Titulo"]      = "Fecha";
		$configColumnVentaCreditoDolares["0"]["FiledSouce"]  = "transactionOn";
		$configColumnVentaCreditoDolares["0"]["Formato"]     = "Date";
		$configColumnVentaCreditoDolares["0"]["Width"]       = $width0;
		$configColumnVentaCreditoDolares["0"]["Total"]       = False;

		$configColumnVentaCreditoDolares["1"]["Titulo"]      = "Cliente";
		$configColumnVentaCreditoDolares["1"]["FiledSouce"]  = "firstName";
		$configColumnVentaCreditoDolares["1"]["Formato"]     = "";
		$configColumnVentaCreditoDolares["1"]["Width"]       = $width1;
		$configColumnVentaCreditoDolares["1"]["Total"]       = False;

		$configColumnVentaCreditoDolares["4"]["Titulo"]      = "Fac";
		$configColumnVentaCreditoDolares["4"]["FiledSouce"]  = "transactionNumber";
		$configColumnVentaCreditoDolares["4"]["Formato"]     = "";
		$configColumnVentaCreditoDolares["4"]["Width"]       = $width4;
		$configColumnVentaCreditoDolares["4"]["Total"]       = False;

		$configColumnVentaCreditoDolares["5"]["Titulo"]      = "Transaccion";
		$configColumnVentaCreditoDolares["5"]["FiledSouce"]  = "tipo";
		$configColumnVentaCreditoDolares["5"]["Formato"]     = "";
		$configColumnVentaCreditoDolares["5"]["Width"]       = $width5;
		$configColumnVentaCreditoDolares["5"]["Total"]       = False;

		$configColumnVentaCreditoDolares["6"]["Titulo"]      = "Tran. Numero";
		$configColumnVentaCreditoDolares["6"]["FiledSouce"]  = "transactionNumber";
		$configColumnVentaCreditoDolares["6"]["Formato"]     = "";
		$configColumnVentaCreditoDolares["6"]["Width"]       = $width6;
		$configColumnVentaCreditoDolares["6"]["Total"]       = False;

		$configColumnVentaCreditoDolares["7"]["Titulo"]      = "Estado";
		$configColumnVentaCreditoDolares["7"]["FiledSouce"]  = "statusName";
		$configColumnVentaCreditoDolares["7"]["Formato"]     = "";
		$configColumnVentaCreditoDolares["7"]["Width"]       = $width7;
		$configColumnVentaCreditoDolares["7"]["Total"]       = False;

		$configColumnVentaCreditoDolares["8"]["Titulo"]      = "Monto";
		$configColumnVentaCreditoDolares["8"]["FiledSouce"]  = "receiptAmount";
		$configColumnVentaCreditoDolares["8"]["Formato"]     = "Number";
		$configColumnVentaCreditoDolares["8"]["Width"]       = $width8;
		$configColumnVentaCreditoDolares["8"]["Total"]       = True;

		$configColumnVentaCreditoDolares["9"]["Titulo"]      = "Vendedor";
		$configColumnVentaCreditoDolares["9"]["FiledSouce"]  = "nickname";
		$configColumnVentaCreditoDolares["9"]["Formato"]     = "";
		$configColumnVentaCreditoDolares["9"]["Width"]       = $width9;
		$configColumnVentaCreditoDolares["9"]["Total"]       = False;

		$configColumnVentaCreditoDolares["10"]["Titulo"]     = "Nota";
		$configColumnVentaCreditoDolares["10"]["FiledSouce"] = "";
		$configColumnVentaCreditoDolares["10"]["Formato"]    = "";
		$configColumnVentaCreditoDolares["10"]["Width"]      = $width10_;
		$configColumnVentaCreditoDolares["10"]["Total"]      = False;

		$configColumnVentaCreditoDolares["11"]["Titulo"]     = "Categoria";
		$configColumnVentaCreditoDolares["11"]["FiledSouce"] = "categoryName";
		$configColumnVentaCreditoDolares["11"]["Formato"]    = "";
		$configColumnVentaCreditoDolares["11"]["Width"]      = $width11_;
		$configColumnVentaCreditoDolares["11"]["Total"]      = False;

	
		
		$objSalesCreditoDolar 		= $objSalesCredito;		
		if($objSalesCreditoDolar != null)
		$objSalesCreditoDolar = array_filter($objSalesCreditoDolar,function($var){
			if (
				strtoupper($var["currencyName"]) == "DOLAR" && 
				strtoupper($var["tipo"]) == "CREDITO"
			)
				return true;
		});
		
		$resultadoVentaCreditoDolar = helper_reporteGeneralCreateTable(
			$objSalesCreditoDolar,
			$configColumnVentaCreditoDolares,
			'0px',
			'DOLARES = CREDITO PRIMA',
			'68c778',
			'black'
		);

		$totalDolares = $totalDolares + ($resultadoVentaCreditoDolar["table"] === 0 ? 0 : $resultadoVentaCreditoDolar["configColumn"][8]["TotalValor"]);
		?>
		
		
		<?php 
		if($resultadoVentaCreditoDolar["table"] !== 0)
		echo $resultadoVentaCreditoDolar["table"];
		?>
		
		<br/>
		
		<?php
		
		/************************************/
		/*INGRESO A CAJA DOLARES*/
		$configColumnIngresoCajaDolares["0"]["Titulo"]      = "Fecha";
		$configColumnIngresoCajaDolares["0"]["FiledSouce"]  = "transactionOn";
		$configColumnIngresoCajaDolares["0"]["Formato"]     = "Date";
		$configColumnIngresoCajaDolares["0"]["Width"]       = $width0;
		$configColumnIngresoCajaDolares["0"]["Total"]       = False;

		$configColumnIngresoCajaDolares["1"]["Titulo"]      = "Cliente";
		$configColumnIngresoCajaDolares["1"]["FiledSouce"]  = "transactionNumber";
		$configColumnIngresoCajaDolares["1"]["Formato"]     = "";
		$configColumnIngresoCajaDolares["1"]["Width"]       = $width1;
		$configColumnIngresoCajaDolares["1"]["Total"]       = False;

		$configColumnIngresoCajaDolares["4"]["Titulo"]      = "Fac";
		$configColumnIngresoCajaDolares["4"]["FiledSouce"]  = "transactionNumber";
		$configColumnIngresoCajaDolares["4"]["Formato"]     = "";
		$configColumnIngresoCajaDolares["4"]["Width"]       = $width4;
		$configColumnIngresoCajaDolares["4"]["Total"]       = False;

		$configColumnIngresoCajaDolares["5"]["Titulo"]      = "Transaccion";
		$configColumnIngresoCajaDolares["5"]["FiledSouce"]  = "transactionName";
		$configColumnIngresoCajaDolares["5"]["Formato"]     = "";
		$configColumnIngresoCajaDolares["5"]["Width"]       = $width5;
		$configColumnIngresoCajaDolares["5"]["Total"]       = False;

		$configColumnIngresoCajaDolares["6"]["Titulo"]      = "Tran. Numero";
		$configColumnIngresoCajaDolares["6"]["FiledSouce"]  = "transactionNumber";
		$configColumnIngresoCajaDolares["6"]["Formato"]     = "";
		$configColumnIngresoCajaDolares["6"]["Width"]       = $width6;
		$configColumnIngresoCajaDolares["6"]["Total"]       = False;

		$configColumnIngresoCajaDolares["7"]["Titulo"]      = "Estado";
		$configColumnIngresoCajaDolares["7"]["FiledSouce"]  = "estado";
		$configColumnIngresoCajaDolares["7"]["Formato"]     = "";
		$configColumnIngresoCajaDolares["7"]["Width"]       = $width7;
		$configColumnIngresoCajaDolares["7"]["Total"]       = False;

		$configColumnIngresoCajaDolares["8"]["Titulo"]      = "Monto";
		$configColumnIngresoCajaDolares["8"]["FiledSouce"]  = "montoTransaccion";
		$configColumnIngresoCajaDolares["8"]["Formato"]     = "Number";
		$configColumnIngresoCajaDolares["8"]["Width"]       = $width8;
		$configColumnIngresoCajaDolares["8"]["Total"]       = True;

		$configColumnIngresoCajaDolares["9"]["Titulo"]      = "Vendedor";
		$configColumnIngresoCajaDolares["9"]["FiledSouce"]  = "nickname";
		$configColumnIngresoCajaDolares["9"]["Formato"]     = "";
		$configColumnIngresoCajaDolares["9"]["Width"]       = $width9;
		$configColumnIngresoCajaDolares["9"]["Total"]       = False;

		$configColumnIngresoCajaDolares["10"]["Titulo"]     = "Nota";
		$configColumnIngresoCajaDolares["10"]["FiledSouce"] = "note";
		$configColumnIngresoCajaDolares["10"]["Formato"]    = "";
		$configColumnIngresoCajaDolares["10"]["Width"]      = $width10_;
		$configColumnIngresoCajaDolares["10"]["Total"]      = False;

		$configColumnIngresoCajaDolares["11"]["Titulo"]     = "Tipo";
		$configColumnIngresoCajaDolares["11"]["FiledSouce"] = "tipoEntrada";
		$configColumnIngresoCajaDolares["11"]["Formato"]    = "";
		$configColumnIngresoCajaDolares["11"]["Width"]      = $width11_;
		$configColumnIngresoCajaDolares["11"]["Total"]      = False;
		
		
		
		
		$objCashDolar 	= $objCash;
		if($objCashDolar != null)
		$objCashDolar = array_filter($objCashDolar,function($var){
			if (strtoupper($var["moneda"]) == "DOLAR")
				return true;
		});
		$resultadoIngresoCajaDolares = helper_reporteGeneralCreateTable(
			$objCashDolar,
			$configColumnIngresoCajaDolares,
			'0px',
			'DOLARES = INGRESO DE CAJA',
			'68c778',
			'black'
		);

		$totalDolares = $totalDolares + ($resultadoIngresoCajaDolares["table"] === 0 ? 0 : $resultadoIngresoCajaDolares["configColumn"][8]["TotalValor"]);
		
		?>
		

		<?php 
		if($resultadoIngresoCajaDolares["table"] !== 0)
		echo $resultadoIngresoCajaDolares["table"];
		?>
		<br/>
		
		<?php
		/************************************/
		/*SALIDA DE CAJA DOLARES*/
		// Índice 0
		$configColumnSalidaCajaDolares["0"]["Titulo"]      	= "Fecha";
		$configColumnSalidaCajaDolares["0"]["FiledSouce"]   = "transactionOn";
		$configColumnSalidaCajaDolares["0"]["Formato"]      = "Date";
		$configColumnSalidaCajaDolares["0"]["Width"]        = $width0;
		$configColumnSalidaCajaDolares["0"]["Total"]        = False;

		// Índice 1
		$configColumnSalidaCajaDolares["1"]["Titulo"]      	= "Cliente";
		$configColumnSalidaCajaDolares["1"]["FiledSouce"]   = "transactionNumber";
		$configColumnSalidaCajaDolares["1"]["Formato"]      = "";
		$configColumnSalidaCajaDolares["1"]["Width"]        = $width1;
		$configColumnSalidaCajaDolares["1"]["Total"]        = False;

		// Índice 4
		$configColumnSalidaCajaDolares["4"]["Titulo"]      	= "Fac";
		$configColumnSalidaCajaDolares["4"]["FiledSouce"]   = "transactionNumber";
		$configColumnSalidaCajaDolares["4"]["Formato"]      = "";
		$configColumnSalidaCajaDolares["4"]["Width"]        = $width4;
		$configColumnSalidaCajaDolares["4"]["Total"]        = False;

		// Índice 5
		$configColumnSalidaCajaDolares["5"]["Titulo"]      	= "Transaccion";
		$configColumnSalidaCajaDolares["5"]["FiledSouce"]   = "transactionName";
		$configColumnSalidaCajaDolares["5"]["Formato"]      = "";
		$configColumnSalidaCajaDolares["5"]["Width"]        = $width5;
		$configColumnSalidaCajaDolares["5"]["Total"]        = False;

		// Índice 6
		$configColumnSalidaCajaDolares["6"]["Titulo"]      	= "Tran. Numero";
		$configColumnSalidaCajaDolares["6"]["FiledSouce"]   = "transactionNumber";
		$configColumnSalidaCajaDolares["6"]["Formato"]      = "";
		$configColumnSalidaCajaDolares["6"]["Width"]        = $width6;
		$configColumnSalidaCajaDolares["6"]["Total"]        = False;

		// Índice 7
		$configColumnSalidaCajaDolares["7"]["Titulo"]      	= "Estado";
		$configColumnSalidaCajaDolares["7"]["FiledSouce"]   = "estado";
		$configColumnSalidaCajaDolares["7"]["Formato"]      = "";
		$configColumnSalidaCajaDolares["7"]["Width"]        = $width7;
		$configColumnSalidaCajaDolares["7"]["Total"]        = False;

		// Índice 8
		$configColumnSalidaCajaDolares["8"]["Titulo"]      	= "Monto";
		$configColumnSalidaCajaDolares["8"]["FiledSouce"]   = "montoTransaccion";
		$configColumnSalidaCajaDolares["8"]["Formato"]      = "Number";
		$configColumnSalidaCajaDolares["8"]["Width"]        = $width8;
		$configColumnSalidaCajaDolares["8"]["Total"]        = True;

		// Índice 9
		$configColumnSalidaCajaDolares["9"]["Titulo"]      	= "Vendedor";
		$configColumnSalidaCajaDolares["9"]["FiledSouce"]   = "nickname";
		$configColumnSalidaCajaDolares["9"]["Formato"]      = "";
		$configColumnSalidaCajaDolares["9"]["Width"]        = $width9;
		$configColumnSalidaCajaDolares["9"]["Total"]        = False;

		// Índice 10
		$configColumnSalidaCajaDolares["10"]["Titulo"]     	= "Nota";
		$configColumnSalidaCajaDolares["10"]["FiledSouce"]  = "note";
		$configColumnSalidaCajaDolares["10"]["Formato"]     = "";
		$configColumnSalidaCajaDolares["10"]["Width"]       = $width10_;
		$configColumnSalidaCajaDolares["10"]["Total"]       = False;

		// Índice 11
		$configColumnSalidaCajaDolares["11"]["Titulo"]     	= "Tipo";
		$configColumnSalidaCajaDolares["11"]["FiledSouce"]  = "tipoSalida";
		$configColumnSalidaCajaDolares["11"]["Formato"]     = "";
		$configColumnSalidaCajaDolares["11"]["Width"]       = $width11_;
		$configColumnSalidaCajaDolares["11"]["Total"]       = False;

		$objCashOutDolar 	= $objCashOut;
		if($objCashOutDolar != null)
		$objCashOutDolar = array_filter($objCashOutDolar,function($var){
			if (strtoupper($var["moneda"]) == "DOLAR")
				return true;
		});
		$resultadoSalidaCajaDolares = helper_reporteGeneralCreateTable(
				$objCashOutDolar,
				$configColumnSalidaCajaDolares,
				'0px',
				'DOLARES = SALIDA DE CAJA',
				'68c778',
				'black'
		);
		
		$totalDolares = $totalDolares - ($resultadoSalidaCajaDolares["table"] === 0 ? 0 : $resultadoSalidaCajaDolares["configColumn"][8]["TotalValor"]);
		
		
		
		?>
		
		
		<?php 
		if($resultadoSalidaCajaDolares["table"] !== 0)
		echo $resultadoSalidaCajaDolares["table"];
		?>
		<br/>
	

		<?php 		
		
		$configTotalesColumns[0]["Titulo"] 		= "Total Dolares";			
		$configTotalesColumns[0]["FiledSouce"] 	= "total";
		$configTotalesColumns[0]["Formato"] 	= "Number";
		$configTotalesColumns[0]["Colspan"] 	= $columnX;		
		$configTotalesColumns[0]["Width"] 		= (str_replace("px", "", $widthX) /*/ 2*/ )."px";
		$detailTotales[0]["total"] 				= $totalDolares;
		
		$rosTotales = helper_reporteGeneralCreateTable(
				$detailTotales,
				$configTotalesColumns,
				'0',
				NULL,
				'68c778',
				'black'
		);
		
		if($totalDolares > 0)
		echo $rosTotales["table"];
		
		?>
		
		
		
	</body>	
</html>
<div style='page-break-before:always;' ></div>