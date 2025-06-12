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
		$configColumn["0"]["IdTable"] 				= "myReport";		
		$configColumn["0"]["FiltrarRegistroOnLine"] = False;	

		$configColumn["0"]["Titulo"] 		= "Cod Cliente";
		$configColumn["1"]["Titulo"] 		= "Cliente";
		$configColumn["2"]["Titulo"] 		= "Costo";
		$configColumn["3"]["Titulo"] 		= "Monto";
		$configColumn["4"]["Titulo"] 		= "Monto con IVA";
		$configColumn["5"]["Titulo"] 		= "Utilidad";		
		$configColumn["7"]["Titulo"] 		= "IVA TOTAl";
		$configColumn["8"]["Titulo"] 		= "Pagos con Puntos";

		$configColumn["0"]["FiledSouce"] 		= "customerNumber";
		$configColumn["1"]["FiledSouce"] 		= "legalName";
		$configColumn["2"]["FiledSouce"] 		= "cost";
		$configColumn["3"]["FiledSouce"] 		= "amount";
		$configColumn["4"]["FiledSouce"] 		= "amountConIva";
		$configColumn["5"]["FiledSouce"] 		= "utilidad";		
		$configColumn["7"]["FiledSouce"] 		= "ivaTotal";
		$configColumn["8"]["FiledSouce"] 		= "pagoConPuntos";


		$configColumn["0"]["Formato"] 		= "";
		$configColumn["1"]["Formato"] 		= "";
		$configColumn["2"]["Formato"] 		= "Number";
		$configColumn["3"]["Formato"] 		= "Number";
		$configColumn["4"]["Formato"] 		= "Number";
		$configColumn["5"]["Formato"] 		= "Number";		
		$configColumn["7"]["Formato"] 		= "Number";
		$configColumn["8"]["Formato"] 		= "Number";


		$configColumn["0"]["Width"] 		= "100px";
		$configColumn["1"]["Width"] 		= "250px";
		$configColumn["2"]["Width"] 		= "100px";
		$configColumn["3"]["Width"] 		= "100px";
		$configColumn["4"]["Width"] 		= "100px";
		$configColumn["5"]["Width"] 		= "100px";		
		$configColumn["7"]["Width"] 		= "100px";
		$configColumn["8"]["Width"] 		= "100px";

		$configColumn["0"]["Total"] 		= False;
		$configColumn["1"]["Total"] 		= False;
		$configColumn["2"]["Total"] 		= True;
		$configColumn["3"]["Total"] 		= True;
		$configColumn["4"]["Total"] 		= True;
		$configColumn["5"]["Total"] 		= True;		
		$configColumn["7"]["Total"] 		= True;
		$configColumn["8"]["Total"] 		= True;

		
		$resultado 	= helper_reporteGeneralCreateTable($objDetail,$configColumn,'0px',NULL,NULL);
		?>
		
		
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'RESUMEN DE VENTA POR CLIENTE',
			$objCompany->name,
			$resultado["columnas"],
			'VENTAS DEL '.$objStartOn.' AL '.$objEndOn,
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