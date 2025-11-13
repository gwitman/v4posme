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
		
		$configColumn["1"]["Titulo"] 		= "Factura";
		$configColumn["1"]["TituloFoot"]	= "";
		$configColumn["1"]["FiledSouce"]	= "documentNumber";
		$configColumn["1"]["Colspan"] 		= "1";
		$configColumn["1"]["Formato"] 		= "";
		$configColumn["1"]["Total"] 		= False;
		$configColumn["1"]["Alineacion"] 	= "Left";
		$configColumn["1"]["TotalValor"] 	= 0;
		$configColumn["1"]["FiledSoucePrefix"]	= "";
		$configColumn["1"]["Width"]			= "92px";
		
		$configColumn["2"]["Titulo"] 		= "Fecha";
		$configColumn["2"]["TituloFoot"]	= "";
		$configColumn["2"]["FiledSouce"]	= "documentOn";
		$configColumn["2"]["Colspan"] 		= "1";
		$configColumn["2"]["Formato"] 		= "";
		$configColumn["2"]["Total"] 		= False;
		$configColumn["2"]["Alineacion"] 	= "Left";
		$configColumn["2"]["TotalValor"] 	= 0;
		$configColumn["2"]["FiledSoucePrefix"]	= "";
		$configColumn["2"]["Width"]			= "92px";
		
		$configColumn["3"]["Titulo"] 		= "Monto de Factura";
		$configColumn["3"]["TituloFoot"]	= "";
		$configColumn["3"]["FiledSouce"]	= "amountDocument";
		$configColumn["3"]["Colspan"] 		= "1";
		$configColumn["3"]["Formato"] 		= "Number";
		$configColumn["3"]["Total"] 		= True;
		$configColumn["3"]["Alineacion"] 	= "Right";
		$configColumn["3"]["TotalValor"] 	= 0;
		$configColumn["3"]["FiledSoucePrefix"]	= "";
		$configColumn["3"]["Width"]			= "128px";
		
		$configColumn["4"]["Titulo"] 		= "Saldo";
		$configColumn["4"]["TituloFoot"]	= "";
		$configColumn["4"]["FiledSouce"]	= "balanceDocument";
		$configColumn["4"]["Colspan"] 		= "1";
		$configColumn["4"]["Formato"] 		= "Number";
		$configColumn["4"]["Total"] 		= True;
		$configColumn["4"]["Alineacion"] 	= "Right";
		$configColumn["4"]["TotalValor"] 	= 0;
		$configColumn["4"]["FiledSoucePrefix"]	= "";
		$configColumn["4"]["Width"]			= "128px";
		
		$configColumn["5"]["Titulo"] 		= "Abonado";
		$configColumn["5"]["TituloFoot"]	= "";
		$configColumn["5"]["FiledSouce"]	= "amountShare";
		$configColumn["5"]["Colspan"] 		= "1";
		$configColumn["5"]["Formato"] 		= "Number";
		$configColumn["5"]["Total"] 		= True;
		$configColumn["5"]["Alineacion"] 	= "Right";
		$configColumn["5"]["TotalValor"] 	= 0;
		$configColumn["5"]["FiledSoucePrefix"]	= "";
		$configColumn["5"]["Width"]			= "128px";
		
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
		
		$configColumn["9"]["Titulo"] 		= "Moneda";
		$configColumn["9"]["TituloFoot"]	= "";
		$configColumn["9"]["FiledSouce"]	= "moneda";
		$configColumn["9"]["Colspan"] 		= "1";
		$configColumn["9"]["Formato"] 		= "";
		$configColumn["9"]["Total"] 		= False;
		$configColumn["9"]["Alineacion"] 	= "Left";
		$configColumn["9"]["TotalValor"] 	= 0;
		$configColumn["9"]["FiledSoucePrefix"]	= "";
		$configColumn["9"]["Width"]			= "80px";
		
		
		$configColumn["100"]["Titulo"] 		= "Comentario";
		$configColumn["100"]["TituloFoot"]	= "";
		$configColumn["100"]["FiledSouce"]	= "nota";
		$configColumn["100"]["Colspan"] 	= "1";
		$configColumn["100"]["Formato"] 	= "";
		$configColumn["100"]["Total"] 		= False;
		$configColumn["100"]["Alineacion"] 	= "Left";
		$configColumn["100"]["TotalValor"] 	= 0;
		$configColumn["100"]["FiledSoucePrefix"]	= "";
		$configColumn["100"]["Width"]		= "80px";
			
		
		
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