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
			
		$configColumn["1"]["Titulo"] 		= "Fecha";					
		$configColumn["2"]["Titulo"] 		= "Tipo";		
		$configColumn["3"]["Titulo"] 		= "Categoria";		
		$configColumn["4"]["Titulo"] 		= "Monto";	

		
		$configColumn["1"]["FiledSouce"] 		= "createdOn";					
		$configColumn["2"]["FiledSouce"] 		= "Tipo";		
		$configColumn["3"]["FiledSouce"] 		= "Categoria";		
		$configColumn["4"]["FiledSouce"] 		= "amount";	

		
		$configColumn["1"]["Formato"] 		= "Date";		
		$configColumn["2"]["Formato"] 		= "";		
		$configColumn["3"]["Formato"] 		= "";		
		$configColumn["4"]["Formato"] 		= "Number";	
		
		$configColumn["1"]["Width"] 		= "80px";		
		$configColumn["2"]["Width"] 		= "80px";		
		$configColumn["3"]["Width"] 		= "80px";		
		$configColumn["4"]["Width"] 		= "80px";		
		
		
		$configColumn["1"]["Total"] 		= False;		
		$configColumn["2"]["Total"] 		= False;		
		$configColumn["3"]["Total"] 		= False;		
		$configColumn["4"]["Total"] 		= False;	
		
		$resultado 	= helper_reporteGeneralCreateTable($objDetail,$configColumn,'0px',NULL,NULL);
		?>
		
		
		
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'DETALLE DE GASTOS',
			$objCompany->name,
			$resultado["columnas"],
			'GASTOS DEL '.$objStartOn.' AL '.$objEndOn,
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