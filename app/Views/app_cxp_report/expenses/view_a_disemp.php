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
		$configColumn["0"]["Titulo"] 		= "Gasto";		
		$configColumn["1"]["Titulo"] 		= "Fecha";		
		$configColumn["2"]["Titulo"] 		= "Monto";		
		$configColumn["3"]["Titulo"] 		= "IVA";
		$configColumn["4"]["Titulo"] 		= "Total";
		$configColumn["5"]["Titulo"] 		= "Tipo";
		$configColumn["6"]["Titulo"] 		= "Clasificación";
		$configColumn["7"]["Titulo"] 		= "Categoria";
		$configColumn["8"]["Titulo"] 		= "Nota";

		$configColumn["0"]["FiledSouce"] 		= "transactionNumber";		
		$configColumn["1"]["FiledSouce"] 		= "createdOn";		
		$configColumn["2"]["FiledSouce"] 		= "amount";		
		$configColumn["3"]["FiledSouce"] 		= "Iva";
		$configColumn["4"]["FiledSouce"] 		= "Total";
		$configColumn["5"]["FiledSouce"] 		= "Tipo";
		$configColumn["6"]["FiledSouce"] 		= "Clasificacion";
		$configColumn["7"]["FiledSouce"] 		= "Categoria";
		$configColumn["8"]["FiledSouce"] 		= "note";

		$configColumn["0"]["Formato"] 		= "";		
		$configColumn["1"]["Formato"] 		= "Date";		
		$configColumn["2"]["Formato"] 		= "Number";		
		$configColumn["3"]["Formato"] 		= "Number";
		$configColumn["4"]["Formato"] 		= "Number";
		$configColumn["5"]["Formato"] 		= "";
		$configColumn["6"]["Formato"] 		= "";
		$configColumn["7"]["Formato"] 		= "";
		$configColumn["8"]["Formato"] 		= "";
		
		$configColumn["0"]["Width"] 		= "80px";		
		$configColumn["1"]["Width"] 		= "80px";		
		$configColumn["2"]["Width"] 		= "120px";		
		$configColumn["3"]["Width"] 		= "120px";
		$configColumn["4"]["Width"] 		= "120px";
		$configColumn["5"]["Width"] 		= "120px";
		$configColumn["6"]["Width"] 		= "120px";
		$configColumn["7"]["Width"] 		= "250px";
		$configColumn["8"]["Width"] 		= "300px";
		
		$configColumn["0"]["Total"] 		= False;		
		$configColumn["1"]["Total"] 		= False;		
		$configColumn["2"]["Total"] 		= True;		
		$configColumn["3"]["Total"] 		= True;
		$configColumn["4"]["Total"] 		= True;
		$configColumn["5"]["Total"] 		= False;
		$configColumn["6"]["Total"] 		= False;
		$configColumn["7"]["Total"] 		= False;
		$configColumn["8"]["Total"] 		= False;
		
		$resultado 	= helper_reporteGeneralCreateTable($objDetail,$configColumn,'0px',NULL,NULL);
		?>
		
		
		
		
		<?= helper_reporteGeneralCreateEncabezado(
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
		
		<?= $resultado["table"]; ?>
		<br/>	
		


		<?= helper_reporteGeneralCreateFirma(
			$objFirmaEncription,
			$resultado["columnas"],
			$resultado["width"]
		);
		?>
		
		
		
	</body>	
</html>