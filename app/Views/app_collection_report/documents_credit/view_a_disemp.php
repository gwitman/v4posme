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
		$width0  		= "1550px";//99
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
		$widthX 		= 1550;
		$columnX 		= 13;
		
		echo helper_reporteGeneralCreateEncabezado(
			'SABANA DE CREDITOS',
			$objCompany->name,
			$columnX,
			'DEL '.$startOn.' AL '.$endOn,
			"",
			"",
			$widthX."px"
		);
		
		$configColumn["0"]["Titulo"] 		= "Usuario";		
		$configColumn["1"]["Titulo"] 		= "Cod. Cliente";		
		$configColumn["2"]["Titulo"] 		= "Cliente";		
		$configColumn["3"]["Titulo"] 		= "Desembolso";		
		$configColumn["4"]["Titulo"] 		= "Plazo";		
		$configColumn["5"]["Titulo"] 		= "Interes";		
		$configColumn["6"]["Titulo"] 		= "Desembolso";		
		$configColumn["7"]["Titulo"] 		= "F. Desembolso";		
		$configColumn["8"]["Titulo"] 		= "F. Ultimo abono";				
		$configColumn["10"]["Titulo"] 		= "Estado";		
		$configColumn["11"]["Titulo"] 		= "Frecuencia de pago";		
		
					 
		$configColumn["0"]["FiledSouce"] 		= "nickname";		
		$configColumn["1"]["FiledSouce"] 		= "customerNumber";		
		$configColumn["2"]["FiledSouce"] 		= "customerName";		
		$configColumn["3"]["FiledSouce"] 		= "documentNumber";		
		$configColumn["4"]["FiledSouce"] 		= "term";		
		$configColumn["5"]["FiledSouce"] 		= "interes";		
		$configColumn["6"]["FiledSouce"] 		= "amountDocument";		
		$configColumn["7"]["FiledSouce"] 		= "dateDocument";		
		$configColumn["8"]["FiledSouce"] 		= "dateLastShareDocument";				
		$configColumn["10"]["FiledSouce"] 		= "statusName";	
		$configColumn["11"]["FiledSouce"] 		= "periodPay";	
		
		$configColumn["0"]["Formato"] 		= "";		
		$configColumn["1"]["Formato"] 		= "";		
		$configColumn["2"]["Formato"] 		= "";		
		$configColumn["3"]["Formato"] 		= "";		
		$configColumn["4"]["Formato"] 		= "Number";		
		$configColumn["5"]["Formato"] 		= "Number";		
		$configColumn["6"]["Formato"] 		= "Number";		
		$configColumn["7"]["Formato"] 		= "Date";		
		$configColumn["8"]["Formato"] 		= "Date";		
		$configColumn["10"]["Formato"] 		= "";	
		$configColumn["11"]["Formato"] 		= "";	
		
		
		
		$configColumn["0"]["Width"] 		= $width0;		
		$configColumn["1"]["Width"] 		= $width1;		
		$configColumn["2"]["Width"] 		= $width2;		
		$configColumn["3"]["Width"] 		= $width3;		
		$configColumn["4"]["Width"] 		= $width4;		
		$configColumn["5"]["Width"] 		= $width5;		
		$configColumn["6"]["Width"] 		= $width6;		
		$configColumn["7"]["Width"] 		= $width7;		
		$configColumn["8"]["Width"] 		= $width8;		
		$configColumn["10"]["Width"] 		= $width10;	
		$configColumn["11"]["Width"] 		= $width11;	
		
		
		$configColumn["0"]["Total"] 		= False;		
		$configColumn["1"]["Total"] 		= False;		
		$configColumn["2"]["Total"] 		= False;		
		$configColumn["3"]["Total"] 		= False;		
		$configColumn["4"]["Total"] 		= False;		
		$configColumn["5"]["Total"] 		= False;		
		$configColumn["6"]["Total"] 		= False;		
		$configColumn["7"]["Total"] 		= False;		
		$configColumn["8"]["Total"] 		= False;		
		$configColumn["10"]["Total"] 		= False;	
		$configColumn["11"]["Total"] 		= False;	
		
		
		//Llenar las columnas dinamicas
		
		
		//Calcular lso datos dinamicos
		
		
		
		
		$resultado = helper_reporteGeneralCreateTable(
			$objDetail,
			$configColumn,
			'0px',
			"DETALLE DE CARTERA"
		);
		
		?>
		
		<?php 
		if($resultado["table"] !== 0)
		echo $resultado["table"];
		?>
		
		
		
		
	</body>	
</html>