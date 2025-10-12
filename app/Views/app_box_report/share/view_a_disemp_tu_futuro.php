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
			if (
				strtoupper($var["moneda"]) == "CORDOBA" && 
				strtoupper($var["transactionName"]) == "ABONOS DE CREDITO"
			)
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
						C$'.number_format($totalCordoba ,2,".",",").'</span>

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