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
		$configColumn["0"]["Titulo"] 		= "Linea";
		$configColumn["0"]["TituloFoot"]	= "";
		$configColumn["0"]["FiledSouce"]	= "lineNumber";
		$configColumn["0"]["Colspan"] 		= "1";
		$configColumn["0"]["Formato"] 		= "";
		$configColumn["0"]["Total"] 		= False;
		$configColumn["0"]["Alineacion"] 	= "Left";
		$configColumn["0"]["TotalValor"] 	= 0;
		$configColumn["0"]["FiledSoucePrefix"]	= "";
		$configColumn["0"]["Width"]			= "92px";
		
		$configColumn["1"]["Titulo"] 		= "Documento";
		$configColumn["1"]["TituloFoot"]	= "";
		$configColumn["1"]["FiledSouce"]	= "documentNumber";
		$configColumn["1"]["Colspan"] 		= "1";
		$configColumn["1"]["Formato"] 		= "";
		$configColumn["1"]["Total"] 		= False;
		$configColumn["1"]["Alineacion"] 	= "Left";
		$configColumn["1"]["TotalValor"] 	= 0;
		$configColumn["1"]["FiledSoucePrefix"]	= "";
		$configColumn["1"]["Width"]			= "92px";
		
		$configColumn["2"]["Titulo"] 		= "Desembolso";
		$configColumn["2"]["TituloFoot"]	= "";
		$configColumn["2"]["FiledSouce"]	= "documentOn";
		$configColumn["2"]["Colspan"] 		= "1";
		$configColumn["2"]["Formato"] 		= "";
		$configColumn["2"]["Total"] 		= False;
		$configColumn["2"]["Alineacion"] 	= "Left";
		$configColumn["2"]["TotalValor"] 	= 0;
		$configColumn["2"]["FiledSoucePrefix"]	= "";
		$configColumn["2"]["Width"]			= "92px";
		
		$configColumn["3"]["Titulo"] 		= "Capital Inicial";
		$configColumn["3"]["TituloFoot"]	= "";
		$configColumn["3"]["FiledSouce"]	= "amountDocument";
		$configColumn["3"]["Colspan"] 		= "1";
		$configColumn["3"]["Formato"] 		= "Number";
		$configColumn["3"]["Total"] 		= False;
		$configColumn["3"]["Alineacion"] 	= "Right";
		$configColumn["3"]["TotalValor"] 	= 0;
		$configColumn["3"]["FiledSoucePrefix"]	= "";
		$configColumn["3"]["Width"]			= "128px";
		
		$configColumn["4"]["Titulo"] 		= "% Interes Anual";
		$configColumn["4"]["TituloFoot"]	= "";
		$configColumn["4"]["FiledSouce"]	= "interesDocument";
		$configColumn["4"]["Colspan"] 		= "1";
		$configColumn["4"]["Formato"] 		= "Number";
		$configColumn["4"]["Total"] 		= True;
		$configColumn["4"]["Alineacion"] 	= "Right";
		$configColumn["4"]["TotalValor"] 	= 0;
		$configColumn["4"]["FiledSoucePrefix"]	= "";
		$configColumn["4"]["Width"]			= "136px";
			
		$configColumn["5"]["Titulo"] 		= "% Interes Anual / 12";
		$configColumn["5"]["TituloFoot"]	= "";
		$configColumn["5"]["FiledSouce"]	= "interesDocumentMultiploDe120";
		$configColumn["5"]["Colspan"] 		= "1";
		$configColumn["5"]["Formato"] 		= "Number";
		$configColumn["5"]["Total"] 		= False;
		$configColumn["5"]["Alineacion"] 	= "Right";
		$configColumn["5"]["TotalValor"] 	= 0;
		$configColumn["5"]["FiledSoucePrefix"]	= "";
		$configColumn["5"]["Width"]			= "163px";
		
		$configColumn["6"]["Titulo"] 		= "#Cuotas";
		$configColumn["6"]["TituloFoot"]	= "";
		$configColumn["6"]["FiledSouce"]	= "termDocument";
		$configColumn["6"]["Colspan"] 		= "1";
		$configColumn["6"]["Formato"] 		= "Number";
		$configColumn["6"]["Total"] 		= True;
		$configColumn["6"]["Alineacion"] 	= "Right";
		$configColumn["6"]["TotalValor"] 	= 0;
		$configColumn["6"]["FiledSoucePrefix"]	= "";
		$configColumn["6"]["Width"]			= "113px";
		
		$configColumn["7"]["Titulo"] 		= "Periodo";
		$configColumn["7"]["TituloFoot"]	= "";
		$configColumn["7"]["FiledSouce"]	= "periodPayDocument";
		$configColumn["7"]["Colspan"] 		= "1";
		$configColumn["7"]["Formato"] 		= "";
		$configColumn["7"]["Total"] 		= False;
		$configColumn["7"]["Alineacion"] 	= "Left";
		$configColumn["7"]["TotalValor"] 	= 0;
		$configColumn["7"]["FiledSoucePrefix"]	= "";
		$configColumn["7"]["Width"]			= "70px";
		
		$configColumn["8"]["Titulo"] 		= "Estado";
		$configColumn["8"]["TituloFoot"]	= "";
		$configColumn["8"]["FiledSouce"]	= "statusDocument";
		$configColumn["8"]["Colspan"] 		= "1";
		$configColumn["8"]["Formato"] 		= "";
		$configColumn["8"]["Total"] 		= False;
		$configColumn["8"]["Alineacion"] 	= "Left";
		$configColumn["8"]["TotalValor"] 	= 0;
		$configColumn["8"]["FiledSoucePrefix"]	= "";
		$configColumn["8"]["Width"]			= "80px";
			
		
		
		$resultado = helper_reporteGeneralCreateTable($objDocument,$configColumn,'1000px');
		
		
		?>
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'ESTADO DE CUENTA',
			$objCompany->name,
			$resultado["columnas"],
			'ESTADO DE CUENTA DEL CLIENTE '.$objClient["customerNumber"],
			"",
			"",
			(str_replace("px","",$resultado["width"]) * 2)."px"
		); 
		?>
		
		<br/>	
		
		
		<?php
		$configColumn1["0"]["Titulo"] 		= "Nombre";
		$configColumn1["0"]["TituloFoot"]	= "";
		$configColumn1["0"]["FiledSouce"]	= "legalName";
		$configColumn1["0"]["Colspan"] 		= "17";
		$configColumn1["0"]["Formato"] 		= "";
		$configColumn1["0"]["Total"] 		= False;
		$configColumn1["0"]["Alineacion"] 	= "Left";
		$configColumn1["0"]["TotalValor"] 	= 0;
		$configColumn1["0"]["FiledSoucePrefix"]	= "";
		$configColumn1["0"]["Width"]			= "195px";
		
		$configColumn1["1"]["Titulo"] 		= "Tipo de Identificacion";
		$configColumn1["1"]["TituloFoot"]	= "";
		$configColumn1["1"]["FiledSouce"]	= "identificationType";
		$configColumn1["1"]["Colspan"] 		= "17";
		$configColumn1["1"]["Formato"] 		= "";
		$configColumn1["1"]["Total"] 		= False;
		$configColumn1["1"]["Alineacion"] 	= "Left";
		$configColumn1["1"]["TotalValor"] 	= 0;
		$configColumn1["1"]["FiledSoucePrefix"]	= "";
		$configColumn1["1"]["Width"]			= "195px";
		
		$configColumn1["2"]["Titulo"] 		= "Pais";
		$configColumn1["2"]["TituloFoot"]	= "";
		$configColumn1["2"]["FiledSouce"]	= "country";
		$configColumn1["2"]["Colspan"] 		= "17";
		$configColumn1["2"]["Formato"] 		= "";
		$configColumn1["2"]["Total"] 		= False;
		$configColumn1["2"]["Alineacion"] 	= "Left";
		$configColumn1["2"]["TotalValor"] 	= 0;
		$configColumn1["2"]["FiledSoucePrefix"]	= "";
		$configColumn1["2"]["Width"]			= "195px";
		
		$configColumn1["3"]["Titulo"] 		= "Estado";
		$configColumn1["3"]["TituloFoot"]	= "";
		$configColumn1["3"]["FiledSouce"]	= "state";
		$configColumn1["3"]["Colspan"] 		= "17";
		$configColumn1["3"]["Formato"] 		= "";
		$configColumn1["3"]["Total"] 		= False;
		$configColumn1["3"]["Alineacion"] 	= "Left";
		$configColumn1["3"]["TotalValor"] 	= 0;
		$configColumn1["3"]["FiledSoucePrefix"]	= "";
		$configColumn1["3"]["Width"]			= "195px";
		
		$configColumn1["4"]["Titulo"] 		= "Ciudad";
		$configColumn1["4"]["TituloFoot"]	= "";
		$configColumn1["4"]["FiledSouce"]	= "city";
		$configColumn1["4"]["Colspan"] 		= "17";
		$configColumn1["4"]["Formato"] 		= "";
		$configColumn1["4"]["Total"] 		= False;
		$configColumn1["4"]["Alineacion"] 	= "Left";
		$configColumn1["4"]["TotalValor"] 	= 0;
		$configColumn1["4"]["FiledSoucePrefix"]	= "";
		$configColumn1["4"]["Width"]			= "195px";
		
		$configColumn1["5"]["Titulo"] 		= "Nacimiento";
		$configColumn1["5"]["TituloFoot"]	= "";
		$configColumn1["5"]["FiledSouce"]	= "birth";
		$configColumn1["5"]["Colspan"] 		= "17";
		$configColumn1["5"]["Formato"] 		= "Date";
		$configColumn1["5"]["Total"] 		= False;
		$configColumn1["5"]["Alineacion"] 	= "Left";
		$configColumn1["5"]["TotalValor"] 	= 0;
		$configColumn1["5"]["FiledSoucePrefix"]	= "";
		$configColumn1["5"]["Width"]			= "195px";
		
		$configColumn1["6"]["Titulo"] 		= "Estado del Cliente";
		$configColumn1["6"]["TituloFoot"]	= "";
		$configColumn1["6"]["FiledSouce"]	= "statusClient";
		$configColumn1["6"]["Colspan"] 		= "17";
		$configColumn1["6"]["Formato"] 		= "";
		$configColumn1["6"]["Total"] 		= False;
		$configColumn1["6"]["Alineacion"] 	= "Left";
		$configColumn1["6"]["TotalValor"] 	= 0;
		$configColumn1["6"]["FiledSoucePrefix"]	= "";
		$configColumn1["6"]["Width"]			= "195px";
		
		$configColumn1["10"]["Titulo"] 				= "Deuda Cordobas";
		$configColumn1["10"]["TituloFoot"]			= "";
		$configColumn1["10"]["FiledSouce"]			= "deudaCordobas";
		$configColumn1["10"]["Colspan"] 			= "17";
		$configColumn1["10"]["Formato"] 			= "Number";
		$configColumn1["10"]["Total"] 				= False;
		$configColumn1["10"]["Alineacion"] 			= "Right";
		$configColumn1["10"]["TotalValor"] 			= 0;
		$configColumn1["10"]["FiledSoucePrefix"]	= "";
		$configColumn1["10"]["Width"]				= "195px";

		$configColumn1["11"]["Titulo"] 				= "Deuda Dolares";
		$configColumn1["11"]["TituloFoot"]			= "";
		$configColumn1["11"]["FiledSouce"]			= "deudaDolares";
		$configColumn1["11"]["Colspan"] 			= "17";
		$configColumn1["11"]["Formato"] 			= "Number";
		$configColumn1["11"]["Total"] 				= False;
		$configColumn1["11"]["Alineacion"] 			= "Right";
		$configColumn1["11"]["TotalValor"] 			= 0;
		$configColumn1["11"]["FiledSoucePrefix"]	= "";
		$configColumn1["11"]["Width"]				= "195px";

		
		$resultado2 = helper_reporteGeneralCreateTableVertical(
			$objClient,
			$configColumn1,
			$resultado["columnas"],
			$resultado["width"]
		); 
		
		
		
		echo $resultado2; 
		echo "<table style='width:100%;order-spacing: 10px;'>
				<tbody>
				<tr>
					<td nowrap='' style='text-align:;width:167px;background-color:#00628e;color:white;' >Print</td>
					<td nowrap='' style='text-align:Left;width:auto' >"."<a href='".base_url()."/".$objParameterUrlImpresion->value."/customerNumber/".$customerNumber."' target='_blank' >Imprimir saldo en impresora</a>"."</td>
				</tr>
				</tbody>
			 </table>";
		echo "<table style='width:100%;order-spacing: 10px;'>
				<tbody>
				<tr>
					<td nowrap='' style='text-align:;width:167px;background-color:#00628e;color:white;' >Ver detalle</td>
					<td nowrap='' style='text-align:Left;width:auto' >"."<a href='".base_url()."/".$objParameterUrlImpresionViewDetalle->value."/customerNumber/".$customerNumber."' target='_blank' >Ver</a>"."</td>
				</tr>
				</tbody>
			 </table>";
		echo "<br/>";
		?>
		
		<br/>
		<br/>
		<br/>
		
		<?php 
		echo $resultado["table"];
		
		
		$configColumn2["1"]["Titulo"] 		= "Documento";
		$configColumn2["1"]["TituloFoot"]	= "";
		$configColumn2["1"]["FiledSouce"]	= "documentNumber";
		$configColumn2["1"]["Colspan"] 		= "1";
		$configColumn2["1"]["Formato"] 		= "";
		$configColumn2["1"]["Total"] 		= False;
		$configColumn2["1"]["Alineacion"] 	= "Left";
		$configColumn2["1"]["TotalValor"] 	= 0;
		$configColumn2["1"]["FiledSoucePrefix"]	= "";
		$configColumn2["1"]["Width"]			= "83px";
		
		$configColumn2["9"]["Titulo"] 		= "Moneda";
		$configColumn2["9"]["TituloFoot"]	= "";
		$configColumn2["9"]["FiledSouce"]	= "moneda";
		$configColumn2["9"]["Colspan"] 		= "1";
		$configColumn2["9"]["Formato"] 		= "";
		$configColumn2["9"]["Total"] 		= False;
		$configColumn2["9"]["Alineacion"] 	= "Left";
		$configColumn2["9"]["TotalValor"] 	= 0;
		$configColumn2["9"]["FiledSoucePrefix"]	= "";
		$configColumn2["9"]["Width"]			= "83px";
					 
		$configColumn2["10"]["Titulo"] 		= "Capital Saldo";
		$configColumn2["10"]["TituloFoot"]	= "";
		$configColumn2["10"]["FiledSouce"]	= "balanceDocument";
		$configColumn2["10"]["Colspan"] 		= "1";
		$configColumn2["10"]["Formato"] 		= "Number";
		$configColumn2["10"]["Total"] 		= False;
		$configColumn2["10"]["Alineacion"] 	= "Right";
		$configColumn2["10"]["TotalValor"] 	= 0;
		$configColumn2["10"]["FiledSoucePrefix"]	= "";
		$configColumn2["10"]["Width"]			= "83px";
					 
		$configColumn2["11"]["Titulo"] 		= "Dias Atrasados";
		$configColumn2["11"]["TituloFoot"]	= "";
		$configColumn2["11"]["FiledSouce"]	= "dayAtrazo";
		$configColumn2["11"]["Colspan"] 		= "1";
		$configColumn2["11"]["Formato"] 		= "Number";
		$configColumn2["11"]["Total"] 		= False;
		$configColumn2["11"]["Alineacion"] 	= "Right";
		$configColumn2["11"]["TotalValor"] 	= 0;
		$configColumn2["11"]["FiledSoucePrefix"]	= "";
		$configColumn2["11"]["Width"]			= "83px";
					 
		$configColumn2["12"]["Titulo"] 		= "Monto Atrazado";
		$configColumn2["12"]["TituloFoot"]	= "";
		$configColumn2["12"]["FiledSouce"]	= "amountAtrazo";
		$configColumn2["12"]["Colspan"] 		= "1";
		$configColumn2["12"]["Formato"] 		= "Number";
		$configColumn2["12"]["Total"] 		= True;
		$configColumn2["12"]["Alineacion"] 	= "Right";
		$configColumn2["12"]["TotalValor"] 	= 0;
		$configColumn2["12"]["FiledSoucePrefix"]	= "";
		$configColumn2["12"]["Width"]			= "83px";
					 
		$configColumn2["13"]["Titulo"] 		= "Monto Total de Interes del Credito";
		$configColumn2["13"]["TituloFoot"]	= "";
		$configColumn2["13"]["FiledSouce"]	= "interestTotalMontoDocument";
		$configColumn2["13"]["Colspan"] 		= "1";
		$configColumn2["13"]["Formato"] 		= "Number";
		$configColumn2["13"]["Total"] 		= False;
		$configColumn2["13"]["Alineacion"] 	= "Right";
		$configColumn2["13"]["TotalValor"] 		= 0;
		$configColumn2["13"]["FiledSoucePrefix"]	= "";
		$configColumn2["13"]["Width"]			= "83px";
					 
		
		$resultado3 = helper_reporteGeneralCreateTable($objDocument,$configColumn2,'1000px');
		echo $resultado3["table"];
		
		
		
		$configColumn3["1"]["Titulo"] 		= "Documento";
		$configColumn3["1"]["TituloFoot"]	= "";
		$configColumn3["1"]["FiledSouce"]	= "documentNumber";
		$configColumn3["1"]["Colspan"] 		= "1";
		$configColumn3["1"]["Formato"] 		= "";
		$configColumn3["1"]["Total"] 		= False;
		$configColumn3["1"]["Alineacion"] 	= "Left";
		$configColumn3["1"]["TotalValor"] 	= 0;
		$configColumn3["1"]["FiledSoucePrefix"]	= "";
		$configColumn3["1"]["Width"]			= "100px";
		
		$configColumn3["14"]["Titulo"] 		= "Vencimiento de Ultima Cuota";
		$configColumn3["14"]["TituloFoot"]	= "";
		$configColumn3["14"]["FiledSouce"]	= "vencimientoUltimaCuota";
		$configColumn3["14"]["Colspan"] 		= "1";
		$configColumn3["14"]["Formato"] 		= "";
		$configColumn3["14"]["Total"] 		= False;
		$configColumn3["14"]["Alineacion"] 	= "Left";
		$configColumn3["14"]["TotalValor"] 		= 0;
		$configColumn3["14"]["FiledSoucePrefix"]	= "";
		$configColumn3["14"]["Width"]			= "100px";
					 
		$configColumn3["15"]["Titulo"] 		= "Promedio de Pago en Dia";
		$configColumn3["15"]["TituloFoot"]	= "";
		$configColumn3["15"]["FiledSouce"]	= "promedioDiaPago";
		$configColumn3["15"]["Colspan"] 		= "1";
		$configColumn3["15"]["Formato"] 		= "Number";
		$configColumn3["15"]["Total"] 		= False;
		$configColumn3["15"]["Alineacion"] 	= "Right";
		$configColumn3["15"]["TotalValor"] 		= 0;
		$configColumn3["15"]["FiledSoucePrefix"]	= "";
		$configColumn3["15"]["Width"]			= "100px";
					 
		$configColumn3["16"]["Titulo"] 		= "La ultima cuota fue cancelada a los X Dias";
		$configColumn3["16"]["TituloFoot"]	= "";
		$configColumn3["16"]["FiledSouce"]	= "atrasoCancelacionDia";
		$configColumn3["16"]["Colspan"] 		= "1";
		$configColumn3["16"]["Formato"] 		= "Number";
		$configColumn3["16"]["Total"] 		= False;
		$configColumn3["16"]["Alineacion"] 	= "Right";
		$configColumn3["16"]["TotalValor"] 		= 0;
		$configColumn3["16"]["FiledSoucePrefix"]	= "";
		$configColumn3["16"]["Width"]			= "100px";
					 
		$configColumn3["17"]["Titulo"] 		= "Nota";
		$configColumn3["17"]["TituloFoot"]	= "";
		$configColumn3["17"]["FiledSouce"]	= "nota";
		$configColumn3["17"]["Colspan"] 		= "1";
		$configColumn3["17"]["Formato"] 			= "";
		$configColumn3["17"]["Total"] 			= False;
		$configColumn3["17"]["Alineacion"] 		= "Left";
		$configColumn3["17"]["TotalValor"] 		= 0;
		$configColumn3["17"]["FiledSoucePrefix"]	= "";
		$configColumn3["17"]["Width"]			= "100px";
		$resultado3 = helper_reporteGeneralCreateTable($objDocument,$configColumn3,'1000px');
		echo $resultado3["table"];
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