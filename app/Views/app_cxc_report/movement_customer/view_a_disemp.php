<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Estado Cuenta Cliente ...<?php echo $objFirmaEncription; ?></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		
		<?php 
		echo helper_reporteGeneralCreateStyle();
		?>
		
	</head>
	<body style="font-family:monospace;font-size:smaller;margin:0px 0px 0px 0px"> 
			
		
		<?php
		$configColumn["0"]["Titulo"] 			= "Codigo";
		$configColumn["0"]["TituloFoot"]		= "";
		$configColumn["0"]["FiledSouce"]		= "customerNumber";
		$configColumn["0"]["Colspan"] 			= "1";
		$configColumn["0"]["Formato"] 			= "";
		$configColumn["0"]["Total"] 			= False;
		$configColumn["0"]["Alineacion"] 		= "Left";
		$configColumn["0"]["TotalValor"] 		= 0;
		$configColumn["0"]["FiledSoucePrefix"]	= "";
		$configColumn["0"]["Width"]				= "92px";
		
		$configColumn["1"]["Titulo"] 			= "Nombre";
		$configColumn["1"]["TituloFoot"]		= "";
		$configColumn["1"]["FiledSouce"]		= "firstName";
		$configColumn["1"]["Colspan"] 			= "1";
		$configColumn["1"]["Formato"] 			= "";
		$configColumn["1"]["Total"] 			= False;
		$configColumn["1"]["Alineacion"] 		= "Left";
		$configColumn["1"]["TotalValor"] 		= 0;
		$configColumn["1"]["FiledSoucePrefix"]	= "";
		$configColumn["1"]["Width"]				= "150px";
		
		$configColumn["2"]["Titulo"] 			= "Transaccion";
		$configColumn["2"]["TituloFoot"]		= "";
		$configColumn["2"]["FiledSouce"]		= "transactionNumber";
		$configColumn["2"]["Colspan"] 			= "1";
		$configColumn["2"]["Formato"] 			= "";
		$configColumn["2"]["Total"] 			= False;
		$configColumn["2"]["Alineacion"] 		= "Left";
		$configColumn["2"]["TotalValor"] 		= 0;
		$configColumn["2"]["FiledSoucePrefix"]	= "";
		$configColumn["2"]["Width"]				= "92px";
		
		
		$configColumn["3"]["Titulo"] 			= "Fecha";
		$configColumn["3"]["TituloFoot"]		= "";
		$configColumn["3"]["FiledSouce"]		= "transactionOn";
		$configColumn["3"]["Colspan"] 			= "1";
		$configColumn["3"]["Formato"] 			= "Date";
		$configColumn["3"]["Total"] 			= False;
		$configColumn["3"]["Alineacion"] 		= "Left";
		$configColumn["3"]["TotalValor"] 		= 0;
		$configColumn["3"]["FiledSoucePrefix"]	= "";
		$configColumn["3"]["Width"]				= "92px";
		
		
		$configColumn["4"]["Titulo"] 			= "Cod. Producto";
		$configColumn["4"]["TituloFoot"]		= "";
		$configColumn["4"]["FiledSouce"]		= "itemNumber";
		$configColumn["4"]["Colspan"] 			= "1";
		$configColumn["4"]["Formato"] 			= "";
		$configColumn["4"]["Total"] 			= False;
		$configColumn["4"]["Alineacion"] 		= "Left";
		$configColumn["4"]["TotalValor"] 		= 0;
		$configColumn["4"]["FiledSoucePrefix"]	= "";
		$configColumn["4"]["Width"]				= "92px";
		
		
		$configColumn["5"]["Titulo"] 			= "Producto";
		$configColumn["5"]["TituloFoot"]		= "";
		$configColumn["5"]["FiledSouce"]		= "itemName";
		$configColumn["5"]["Colspan"] 			= "1";
		$configColumn["5"]["Formato"] 			= "";
		$configColumn["5"]["Total"] 			= False;
		$configColumn["5"]["Alineacion"] 		= "Left";
		$configColumn["5"]["TotalValor"] 		= 0;
		$configColumn["5"]["FiledSoucePrefix"]	= "";
		$configColumn["5"]["Width"]				= "150px";
		
		
		$configColumn["6"]["Titulo"] 			= "Cantidad";
		$configColumn["6"]["TituloFoot"]		= "";
		$configColumn["6"]["FiledSouce"]		= "quantity";
		$configColumn["6"]["Colspan"] 			= "1";
		$configColumn["6"]["Formato"] 			= "Number";
		$configColumn["6"]["Total"] 			= False;
		$configColumn["6"]["Alineacion"] 		= "Left";
		$configColumn["6"]["TotalValor"] 		= 0;
		$configColumn["6"]["FiledSoucePrefix"]	= "";
		$configColumn["6"]["Width"]				= "92px";
		
		
		$configColumn["7"]["Titulo"] 			= "Precio";
		$configColumn["7"]["TituloFoot"]		= "";
		$configColumn["7"]["FiledSouce"]		= "unitaryPrice";
		$configColumn["7"]["Colspan"] 			= "1";
		$configColumn["7"]["Formato"] 			= "Number";
		$configColumn["7"]["Total"] 			= False;
		$configColumn["7"]["Alineacion"] 		= "Left";
		$configColumn["7"]["TotalValor"] 		= 0;
		$configColumn["7"]["FiledSoucePrefix"]	= "";
		$configColumn["7"]["Width"]				= "92px";
		
		$configColumn["8"]["Titulo"] 			= "Monto";
		$configColumn["8"]["TituloFoot"]		= "";
		$configColumn["8"]["FiledSouce"]		= "amount";
		$configColumn["8"]["Colspan"] 			= "1";
		$configColumn["8"]["Formato"] 			= "Number";
		$configColumn["8"]["Total"] 			= False;
		$configColumn["8"]["Alineacion"] 		= "Left";
		$configColumn["8"]["TotalValor"] 		= 0;
		$configColumn["8"]["FiledSoucePrefix"]	= "";
		$configColumn["8"]["Width"]				= "92px";
		
		
		$configColumn["9"]["Titulo"] 			= "Balance";
		$configColumn["9"]["TituloFoot"]		= "";
		$configColumn["9"]["FiledSouce"]		= "balance";
		$configColumn["9"]["Colspan"] 			= "1";
		$configColumn["9"]["Formato"] 			= "Number";
		$configColumn["9"]["Total"] 			= False;
		$configColumn["9"]["Alineacion"] 		= "Left";
		$configColumn["9"]["TotalValor"] 		= 0;
		$configColumn["9"]["FiledSoucePrefix"]	= "";
		$configColumn["9"]["Width"]				= "92px";
		
		$configColumn["9"]["Titulo"] 			= "Nota";
		$configColumn["9"]["TituloFoot"]		= "";
		$configColumn["9"]["FiledSouce"]		= "nota";
		$configColumn["9"]["Colspan"] 			= "1";
		$configColumn["9"]["Formato"] 			= "";
		$configColumn["9"]["Total"] 			= False;
		$configColumn["9"]["Alineacion"] 		= "Left";
		$configColumn["9"]["TotalValor"] 		= 0;
		$configColumn["9"]["FiledSoucePrefix"]	= "";
		$configColumn["9"]["Width"]				= "252px";
		
		$resultado = helper_reporteGeneralCreateTable($objClient,$configColumn,'2700px');
		?>
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'MOVIMIENTOS POR CLIENTE',
			$objCompany->name,
			$resultado["columnas"],
			'ESTADO DE CUENTA DEL CLIENTE '.$objClient[0]["customerNumber"],
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