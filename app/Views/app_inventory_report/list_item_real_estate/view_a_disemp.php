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
		
		
		<style>
			/* Estilo para la tabla */
			#myReport {
			  border-collapse: collapse;
			}

			#myReport th, #myReport td {
			  text-align: left;			  
			}


			/* Estilo para fijar el título de la tabla */
			#myReport thead {
			  position: sticky;
			  top: 0;
			  z-index: 1;
			}
		</style>
		
		
	</head>
	<body style="font-family:monospace;font-size:smaller;margin:0px 0px 0px 0px"> 
		
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'DETALLE DE INUMEBLES',
			$objCompany->name,
			44,
			'DEL '.$objStartOn.' AL '.$objEndOn,
			"",
			"",
			"7040px"
		);
		?>
		
		
		<br/>	
		
		<?php
		$configColumn["0"]["Titulo"] 						= "Codigo";				
		$configColumn["0"]["FiledSouce"] 					= "Codigo";				
		$configColumn["0"]["Formato"] 						= "";		
		$configColumn["0"]["Width"] 						= "80px";	
		$configColumn["0"]["Total"] 						= False;		
		$configColumn["0"]["IdTable"] 						= "myReport";		
		$configColumn["0"]["FiltrarRegistroOnLine"] 		= True;				
		
		$configColumn["1"]["Titulo"] = "Nombre";
		$configColumn["1"]["FiledSouce"] = "Nombre";
		$configColumn["1"]["Formato"] = "";
		$configColumn["1"]["Width"] = "80px";
		$configColumn["1"]["Total"] = false;
		
		$configColumn["2"]["Titulo"] = "Pagina Web";
		$configColumn["2"]["FiledSouce"] = "Pagina Web";
		$configColumn["2"]["Formato"] = "";
		$configColumn["2"]["Width"] = "80px";
		$configColumn["2"]["Total"] = false;
		
		$configColumn["3"]["Titulo"] = "Amueblado";
		$configColumn["3"]["FiledSouce"] = "Amueblado";
		$configColumn["3"]["Formato"] = "";
		$configColumn["3"]["Width"] = "80px";
		$configColumn["3"]["Total"] = false;
		
		$configColumn["4"]["Titulo"] = "Aires";
		$configColumn["4"]["FiledSouce"] = "Aires";
		$configColumn["4"]["Formato"] = "";
		$configColumn["4"]["Width"] = "80px";
		$configColumn["4"]["Total"] = false;
		
		$configColumn["5"]["Titulo"] = "Niveles";
		$configColumn["5"]["FiledSouce"] = "Niveles";
		$configColumn["5"]["Formato"] = "";
		$configColumn["5"]["Width"] = "80px";
		$configColumn["5"]["Total"] = false;
		
		$configColumn["6"]["Titulo"] = "Hora de visita";
		$configColumn["6"]["FiledSouce"] = "Hora de visita";
		$configColumn["6"]["Formato"] = "";
		$configColumn["6"]["Width"] = "80px";
		$configColumn["6"]["Total"] = false;
		
		$configColumn["7"]["Titulo"] = "Baños";
		$configColumn["7"]["FiledSouce"] = "Baños";
		$configColumn["7"]["Formato"] = "";
		$configColumn["7"]["Width"] = "80px";
		$configColumn["7"]["Total"] = false;
		
		$configColumn["8"]["Titulo"] = "Habitaciones";
		$configColumn["8"]["FiledSouce"] = "Habitaciones";
		$configColumn["8"]["Formato"] = "";
		$configColumn["8"]["Width"] = "80px";
		$configColumn["8"]["Total"] = false;
		
		$configColumn["9"]["Titulo"] = "Diseño de propiedad";
		$configColumn["9"]["FiledSouce"] = "Diseño de propiedad";
		$configColumn["9"]["Formato"] = "";
		$configColumn["9"]["Width"] = "80px";
		$configColumn["9"]["Total"] = false;
		
		$configColumn["10"]["Titulo"] = "Tipo de casa";
		$configColumn["10"]["FiledSouce"] = "Tipo de casa";
		$configColumn["10"]["Formato"] = "";
		$configColumn["10"]["Width"] = "80px";
		$configColumn["10"]["Total"] = false;
		
		$configColumn["11"]["Titulo"] = "Proposito";
		$configColumn["11"]["FiledSouce"] = "Proposito";
		$configColumn["11"]["Formato"] = "";
		$configColumn["11"]["Width"] = "80px";
		$configColumn["11"]["Total"] = false;
		
		$configColumn["12"]["Titulo"] = "Moneda";
		$configColumn["12"]["FiledSouce"] = "Moneda";
		$configColumn["12"]["Formato"] = "";
		$configColumn["12"]["Width"] = "80px";
		$configColumn["12"]["Total"] = false;
		
		$configColumn["13"]["Titulo"] = "Fecha de enlistamiento";
		$configColumn["13"]["FiledSouce"] = "Fecha de enlistamiento";
		$configColumn["13"]["Formato"] = "";
		$configColumn["13"]["Width"] = "80px";
		$configColumn["13"]["Total"] = false;
		
		$configColumn["14"]["Titulo"] = "Fecha de actualizacion";
		$configColumn["14"]["FiledSouce"] = "Fecha de actualizacion";
		$configColumn["14"]["Formato"] = "";
		$configColumn["14"]["Width"] = "80px";
		$configColumn["14"]["Total"] = false;
		
		$configColumn["15"]["Titulo"] = "Precio Venta";
		$configColumn["15"]["FiledSouce"] = "Precio Venta";
		$configColumn["15"]["Formato"] = "";
		$configColumn["15"]["Width"] = "80px";
		$configColumn["15"]["Total"] = false;
		
		$configColumn["16"]["Titulo"] = "Precio Renta";
		$configColumn["16"]["FiledSouce"] = "Precio Renta";
		$configColumn["16"]["Formato"] = "";
		$configColumn["16"]["Width"] = "80px";
		$configColumn["16"]["Total"] = false;
		
		$configColumn["17"]["Titulo"] = "Disponible";
		$configColumn["17"]["FiledSouce"] = "Disponible";
		$configColumn["17"]["Formato"] = "";
		$configColumn["17"]["Width"] = "80px";
		$configColumn["17"]["Total"] = false;
		
		$configColumn["18"]["Titulo"] = "Area de contruccion M2";
		$configColumn["18"]["FiledSouce"] = "Area de contruccion M2";
		$configColumn["18"]["Formato"] = "";
		$configColumn["18"]["Width"] = "80px";
		$configColumn["18"]["Total"] = false;
		
		$configColumn["19"]["Titulo"] = "Area de terreno V2";
		$configColumn["19"]["FiledSouce"] = "Area de terreno V2";
		$configColumn["19"]["Formato"] = "";
		$configColumn["19"]["Width"] = "80px";
		$configColumn["19"]["Total"] = false;
		
		$configColumn["20"]["Titulo"] = "ID Encuentra 24";
		$configColumn["20"]["FiledSouce"] = "ID Encuentra 24";
		$configColumn["20"]["Formato"] = "";
		$configColumn["20"]["Width"] = "80px";
		$configColumn["20"]["Total"] = false;
		
		$configColumn["21"]["Titulo"] = "Baño de servicio";
		$configColumn["21"]["FiledSouce"] = "Baño de servicio";
		$configColumn["21"]["Formato"] = "";
		$configColumn["21"]["Width"] = "80px";
		$configColumn["21"]["Total"] = false;
		
		$configColumn["22"]["Titulo"] = "Baño de visita";
		$configColumn["22"]["FiledSouce"] = "Baño de visita";
		$configColumn["22"]["Formato"] = "";
		$configColumn["22"]["Width"] = "80px";
		$configColumn["22"]["Total"] = false;
		
		$configColumn["23"]["Titulo"] = "Cuarto de servicio";
		$configColumn["23"]["FiledSouce"] = "Cuarto de servicio";
		$configColumn["23"]["Formato"] = "";
		$configColumn["23"]["Width"] = "80px";
		$configColumn["23"]["Total"] = false;
		
		$configColumn["24"]["Titulo"] = "Walk in closet";
		$configColumn["24"]["FiledSouce"] = "Walk in closet";
		$configColumn["24"]["Formato"] = "";
		$configColumn["24"]["Width"] = "80px";
		$configColumn["24"]["Total"] = false;
		
		$configColumn["25"]["Titulo"] = "Piscina privada";
		$configColumn["25"]["FiledSouce"] = "Piscina privada";
		$configColumn["25"]["Formato"] = "";
		$configColumn["25"]["Width"] = "80px";
		$configColumn["25"]["Total"] = false;
		
		$configColumn["26"]["Titulo"] = "Area club con piscina";
		$configColumn["26"]["FiledSouce"] = "Area club con piscina";
		$configColumn["26"]["Formato"] = "";
		$configColumn["26"]["Width"] = "80px";
		$configColumn["26"]["Total"] = false;
		
		$configColumn["27"]["Titulo"] = "Acepta mascota";
		$configColumn["27"]["FiledSouce"] = "Acepta mascota";
		$configColumn["27"]["Formato"] = "";
		$configColumn["27"]["Width"] = "80px";
		$configColumn["27"]["Total"] = false;
		
		$configColumn["28"]["Titulo"] = "Corretaje";
		$configColumn["28"]["FiledSouce"] = "Corretaje";
		$configColumn["28"]["Formato"] = "";
		$configColumn["28"]["Width"] = "80px";
		$configColumn["28"]["Total"] = false;
		
		$configColumn["29"]["Titulo"] = "Plan de referido";
		$configColumn["29"]["FiledSouce"] = "Plan de referido";
		$configColumn["29"]["Formato"] = "";
		$configColumn["29"]["Width"] = "80px";
		$configColumn["29"]["Total"] = false;
		
		$configColumn["30"]["Titulo"] = "Link Youtube";
		$configColumn["30"]["FiledSouce"] = "Link Youtube";
		$configColumn["30"]["Formato"] = "";
		$configColumn["30"]["Width"] = "80px";
		$configColumn["30"]["Total"] = false;
		
		$configColumn["31"]["Titulo"] = "Pagina Web Link";
		$configColumn["31"]["FiledSouce"] = "Pagina Web Link";
		$configColumn["31"]["Formato"] = "";
		$configColumn["31"]["Width"] = "80px";
		$configColumn["31"]["Total"] = false;
		
		$configColumn["32"]["Titulo"] = "Foto";
		$configColumn["32"]["FiledSouce"] = "Foto";
		$configColumn["32"]["Formato"] = "";
		$configColumn["32"]["Width"] = "80px";
		$configColumn["32"]["Total"] = false;
		
		$configColumn["33"]["Titulo"] = "Google";
		$configColumn["33"]["FiledSouce"] = "Google";
		$configColumn["33"]["Formato"] = "";
		$configColumn["33"]["Width"] = "80px";
		$configColumn["33"]["Total"] = false;
		
		$configColumn["34"]["Titulo"] = "Otros Link";
		$configColumn["34"]["FiledSouce"] = "Otros Link";
		$configColumn["34"]["Formato"] = "";
		$configColumn["34"]["Width"] = "80px";
		$configColumn["34"]["Total"] = false;
		
		$configColumn["35"]["Titulo"] = "Estilo de cocina";
		$configColumn["35"]["FiledSouce"] = "Estilo de cocina";
		$configColumn["35"]["Formato"] = "";
		$configColumn["35"]["Width"] = "80px";
		$configColumn["35"]["Total"] = false;
		
		$configColumn["36"]["Titulo"] = "Agente";
		$configColumn["36"]["FiledSouce"] = "Agente";
		$configColumn["36"]["Formato"] = "";
		$configColumn["36"]["Width"] = "80px";
		$configColumn["36"]["Total"] = false;
		
		$configColumn["37"]["Titulo"] = "Zona";
		$configColumn["37"]["FiledSouce"] = "Zona";
		$configColumn["37"]["Formato"] = "";
		$configColumn["37"]["Width"] = "80px";
		$configColumn["37"]["Total"] = false;
		
		$configColumn["38"]["Titulo"] = "Condominio";
		$configColumn["38"]["FiledSouce"] = "Condominio";
		$configColumn["38"]["Formato"] = "";
		$configColumn["38"]["Width"] = "80px";
		$configColumn["38"]["Total"] = false;
		
		$configColumn["39"]["Titulo"] = "Ubicacion";
		$configColumn["39"]["FiledSouce"] = "Ubicacion";
		$configColumn["39"]["Formato"] = "";
		$configColumn["39"]["Width"] = "80px";
		$configColumn["39"]["Total"] = false;
		
		$configColumn["40"]["Titulo"] = "Exclusividad de agente";
		$configColumn["40"]["FiledSouce"] = "Exclusividad de agente";
		$configColumn["40"]["Formato"] = "";
		$configColumn["40"]["Width"] = "80px";
		$configColumn["40"]["Total"] = false;
		
		$configColumn["41"]["Titulo"] = "Pais";
		$configColumn["41"]["FiledSouce"] = "Pais";
		$configColumn["41"]["Formato"] = "";
		$configColumn["41"]["Width"] = "80px";
		$configColumn["41"]["Total"] = false;
		
		$configColumn["42"]["Titulo"] = "Estado";
		$configColumn["42"]["FiledSouce"] = "Estado";
		$configColumn["42"]["Formato"] = "";
		$configColumn["42"]["Width"] = "80px";
		$configColumn["42"]["Total"] = false;
		
		$configColumn["43"]["Titulo"] = "Ciudad";
		$configColumn["43"]["FiledSouce"] = "Ciudad";
		$configColumn["43"]["Formato"] = "";
		$configColumn["43"]["Width"] = "80px";
		$configColumn["43"]["Total"] = false;


			
		
		
		$resultado 	= helper_reporteGeneralCreateTable($objDetail,$configColumn,'0px',NULL,NULL);
		echo $resultado["table"];
		?>
		
		<br/>
		
		<?php 
		echo helper_reporteGeneralCreateFirma(	
			$objFirmaEncription,
			"44",
			"2250px"
		);
		?>
		
		
		
	</body>	
</html>