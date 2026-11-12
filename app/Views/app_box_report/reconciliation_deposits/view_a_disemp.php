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
		//Columnas del reporte
		$configColumn["0"]["Titulo"] 		= "#";
		$configColumn["1"]["Titulo"] 		= "No";
		$configColumn["2"]["Titulo"] 		= "Cliente";
		$configColumn["3"]["Titulo"] 		= "Documento";
		$configColumn["4"]["Titulo"] 		= "Fecha";
		$configColumn["5"]["Titulo"] 		= "Cuota";
		$configColumn["6"]["Titulo"] 		= "Moneda";
		$configColumn["7"]["Titulo"] 		= "Gestor";
		$configColumn["8"]["Titulo"] 		= "Comentario";

		$configColumn["0"]["FiledSouce"] 	= "";
		$configColumn["1"]["FiledSouce"] 	= "NoCliente";
		$configColumn["2"]["FiledSouce"] 	= "Cliente";
		$configColumn["3"]["FiledSouce"] 	= "Documento";
		$configColumn["4"]["FiledSouce"] 	= "Fecha";
		$configColumn["5"]["FiledSouce"] 	= "Monto";
		$configColumn["6"]["FiledSouce"] 	= "Moneda";
		$configColumn["7"]["FiledSouce"] 	= "Gestor";
		$configColumn["8"]["FiledSouce"] 	= "";

		$configColumn["0"]["Width"] 		= "40px";
		$configColumn["1"]["Width"] 		= "100px";
		$configColumn["2"]["Width"] 		= "300px";
		$configColumn["3"]["Width"] 		= "120px";
		$configColumn["4"]["Width"] 		= "100px";
		$configColumn["5"]["Width"] 		= "120px";
		$configColumn["6"]["Width"] 		= "80px";
		$configColumn["7"]["Width"] 		= "200px";
		$configColumn["8"]["Width"] 		= "250px";

		$configColumn["0"]["AutoIncrement"]	= true;
		$configColumn["4"]["Formato"] 		= "Date";
		$configColumn["5"]["Formato"] 		= "Number";
		$configColumn["5"]["Total"] 		= true;

		$resultado = helper_reporteGeneralCreateTable($objDetail,$configColumn,'0px');

		//Subtitulos: gestor filtrado, rango de fechas y monto total
		$gestor 		= $objDetail ? $objDetail[0]["FiltroCode"]." / ".$objDetail[0]["FiltroName"] : "N/D";
		$montoTotal 	= $objDetail ? array_sum(array_column($objDetail, 'Monto')) : 0;
		$startOn 		= isset($startOn) ? $startOn : "";
		$endOn 			= isset($endOn) ? $endOn : "";
		$rangoFechas 	= ($startOn != "" || $endOn != "") ? 'DEL '.$startOn.' AL '.$endOn : "";
		?>

		<?php
		echo helper_reporteGeneralCreateEncabezado(
			'RECUPERACION',
			$objCompany->name,
			$resultado["columnas"],
			'GESTOR: '.$gestor,
			$rangoFechas,
			'MONTO TOTAL: '.number_format((float)$montoTotal,2,'.',','),
			$resultado["width"]
		);
		?>

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
