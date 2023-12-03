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
		$width 		= 0;
		$columns 	= 0;
		
		
		
		echo helper_reporteGeneralCreateEncabezado(
			'TRANSFERENCIA',
			$objCompany->name,
			3 /*columnas*/,
			'ELABORADO EL  '.$startOn,
			"",
			"",
			"385px" /*width*/
		);
		
		?>
		
		
		<?php		
		$configColumn["0"]["Titulo"] 		= "Codigo";		
		$configColumn["1"]["Titulo"] 		= "Nombre";		
		$configColumn["2"]["Titulo"] 		= "Cantidad";		
		
					 
		$configColumn["0"]["FiledSouce"] 		= "itemNumber";		
		$configColumn["1"]["FiledSouce"] 		= "itemName";		
		$configColumn["2"]["FiledSouce"] 		= "quantity";		
		
		$configColumn["0"]["Formato"] 		= "";		
		$configColumn["1"]["Formato"] 		= "";		
		$configColumn["2"]["Formato"] 		= "Number";	
		
		$configColumn["0"]["Width"] 		= "80px";		
		$configColumn["1"]["Width"] 		= "220px";		
		$configColumn["2"]["Width"] 		= "85px";	
		
		$configColumn["0"]["Total"] 		= False;		
		$configColumn["1"]["Total"] 		= False;		
		$configColumn["2"]["Total"] 		= False;	
		
		$resultadoDetail = helper_reporteGeneralCreateTable(
			$objDetail,
			$configColumn,
			'385px',
			"DETALLE DE TRANSFERENCIA SALIDA Y ENTRADA "
		);
		
		if($resultadoDetail["table"] !== 0)
		{
			$width 		= $resultadoDetail["width"];
			$columns 	= $resultadoDetail["columnas"];
			echo $resultadoDetail["table"];
			
		}
		else
		{
			$width 		 = "385px";
			$columns	 = 3;
		}			
		
		
		?>
		

		<br/>		
		<?php 
		echo helper_reporteGeneralCreateFirma(	
			$objFirmaEncription,
			$columns,
			$width
		);
		?>
		
		
	</body>	
</html>