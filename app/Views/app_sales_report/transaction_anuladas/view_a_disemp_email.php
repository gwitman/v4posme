<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		
		<?php 
		echo helper_reporteGeneralCreateStyle();
		?>
		
	</head>
	<body style="font-family:monospace;font-size:smaller;margin:0px 0px 0px 0px"> 
	
		
		<?php
		$configColumn["0"]["Titulo"] 		= "Fecha";		
		$configColumn["1"]["Titulo"] 		= "Transaccion";		
		$configColumn["2"]["Titulo"] 		= "Estado";		
		$configColumn["3"]["Titulo"] 		= "Monto";		
		$configColumn["4"]["Titulo"] 		= "Nombre";	


		$configColumn["0"]["FiledSouce"] 		= "createdOn";		
		$configColumn["1"]["FiledSouce"] 		= "transactionNumber";		
		$configColumn["2"]["FiledSouce"] 		= "statusName";		
		$configColumn["3"]["FiledSouce"] 		= "monto";		
		$configColumn["4"]["FiledSouce"] 		= "transactionName";	

		$configColumn["0"]["Formato"] 		= "Date";		
		$configColumn["1"]["Formato"] 		= "";		
		$configColumn["2"]["Formato"] 		= "";		
		$configColumn["3"]["Formato"] 		= "Number";		
		$configColumn["4"]["Formato"] 		= "";	
		
		$configColumn["0"]["Width"] 		= "80px";		
		$configColumn["1"]["Width"] 		= "80px";		
		$configColumn["2"]["Width"] 		= "80px";		
		$configColumn["3"]["Width"] 		= "80px";		
		$configColumn["4"]["Width"] 		= "250px";	
		
		$configColumn["0"]["Total"] 		= False;		
		$configColumn["1"]["Total"] 		= False;		
		$configColumn["2"]["Total"] 		= False;		
		$configColumn["3"]["Total"] 		= True;		
		$configColumn["4"]["Total"] 		= False;
		
		$resultado 	= helper_reporteGeneralCreateTable($objDetail,$configColumn,'0px',NULL,NULL);
		?>
		
		
		
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'T-REGISTRAS O ANULADAS',
			$objCompany->name,
			$resultado["columnas"],
			'LISTADO DEL '.$objStartOn.' AL '.$objEndOn,
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