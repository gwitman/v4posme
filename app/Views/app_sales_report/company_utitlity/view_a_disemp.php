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
	<body style="font-family:monospace;font-size:larger;margin:0px 0px 0px 0px"> 
		
		
		
		<?php		
		$configColumn["14"]["Titulo"] 			= "Indicador";
		$configColumn["17"]["Titulo"] 			= "Valor";		
		$configColumn["14"]["FiledSouce"] 		= "Indicador";		
		$configColumn["17"]["FiledSouce"] 		= "Monto";
		$configColumn["14"]["Formato"] 			= "";		
		$configColumn["17"]["Formato"] 			= "Number";				
		$configColumn["14"]["Width"] 			= "550px";
		$configColumn["17"]["Width"] 			= "100px";				
		$configColumn["14"]["Total"] 			= False;
		$configColumn["17"]["Total"] 			= False;		
		$resultado 	= helper_reporteGeneralCreateTable($objDetail,$configColumn,'0px',NULL,NULL);
		?>
		
		
		
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'UTILIDADES',
			$objCompany->name,
			$resultado["columnas"],
			'UTILIDADES DEL '.$objStartOn.' AL '.$objEndOn,
			"SUCURSAL: ". $branchName,
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