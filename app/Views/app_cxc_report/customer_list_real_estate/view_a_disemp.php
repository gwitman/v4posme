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
			'DETALLE DE CLIENTE',
			$objCompany->name,
			17,
			'DEL '.$objStartOn.' AL '.$objEndOn,
			"",
			"",
			"2719px"
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
		
		$configColumn["1"]["Titulo"] 						= "Contacto";				
		$configColumn["1"]["FiledSouce"] 					= "Contacto";				
		$configColumn["1"]["Formato"] 						= "";		
		$configColumn["1"]["Width"] 						= "80px";	
		$configColumn["1"]["Total"] 						= False;		
		
		$configColumn["2"]["Titulo"] 						= "Modificacion";				
		$configColumn["2"]["FiledSouce"] 					= "Modificacion";				
		$configColumn["2"]["Formato"] 						= "";		
		$configColumn["2"]["Width"] 						= "80px";	
		$configColumn["2"]["Total"] 						= False;		
		
		$configColumn["3"]["Titulo"] 						= "Cliente";				
		$configColumn["3"]["FiledSouce"] 					= "Cliente";				
		$configColumn["3"]["Formato"] 						= "";		
		$configColumn["3"]["Width"] 						= "80px";	
		$configColumn["3"]["Total"] 						= False;		
		
		$configColumn["4"]["Titulo"] 						= "Sexo";				
		$configColumn["4"]["FiledSouce"] 					= "Sexo";				
		$configColumn["4"]["Formato"] 						= "";		
		$configColumn["4"]["Width"] 						= "80px";	
		$configColumn["4"]["Total"] 						= False;		
		
		$configColumn["5"]["Titulo"] 						= "Estado";				
		$configColumn["5"]["FiledSouce"] 					= "Estado";				
		$configColumn["5"]["Formato"] 						= "";		
		$configColumn["5"]["Width"] 						= "80px";	
		$configColumn["5"]["Total"] 						= False;		
		
		$configColumn["6"]["Titulo"] 						= "Clasificacion";				
		$configColumn["6"]["FiledSouce"] 					= "Clasificacion";				
		$configColumn["6"]["Formato"] 						= "";		
		$configColumn["6"]["Width"] 						= "80px";	
		$configColumn["6"]["Total"] 						= False;		
		
		$configColumn["7"]["Titulo"] 						= "Categoria";				
		$configColumn["7"]["FiledSouce"] 					= "Categoria";				
		$configColumn["7"]["Formato"] 						= "";		
		$configColumn["7"]["Width"] 						= "80px";	
		$configColumn["7"]["Total"] 						= False;		
		
		$configColumn["8"]["Titulo"] 						= "Presupuesto";				
		$configColumn["8"]["FiledSouce"] 					= "Presupuesto";				
		$configColumn["8"]["Formato"] 						= "";		
		$configColumn["8"]["Width"] 						= "80px";	
		$configColumn["8"]["Total"] 						= False;		
		
		$configColumn["9"]["Titulo"] 						= "Telefono";				
		$configColumn["9"]["FiledSouce"] 					= "Telefono";				
		$configColumn["9"]["Formato"] 						= "";		
		$configColumn["9"]["Width"] 						= "80px";	
		$configColumn["9"]["Total"] 						= False;		
		
		
		$configColumn["10"]["Titulo"] 						= "Ubicacion Interes";				
		$configColumn["10"]["FiledSouce"] 					= "Ubicacion Interes";				
		$configColumn["10"]["Formato"] 						= "";		
		$configColumn["10"]["Width"] 						= "80px";	
		$configColumn["10"]["Total"] 						= False;		
		
		$configColumn["11"]["Titulo"] 						= "Agente";				
		$configColumn["11"]["FiledSouce"] 					= "Agente";				
		$configColumn["11"]["Formato"] 						= "";		
		$configColumn["11"]["Width"] 						= "80px";	
		$configColumn["11"]["Total"] 						= False;		
		
		$configColumn["12"]["Titulo"] 						= "Encuentra 24";				
		$configColumn["12"]["FiledSouce"] 					= "Encuentra 24";				
		$configColumn["12"]["Formato"] 						= "";		
		$configColumn["12"]["Width"] 						= "80px";	
		$configColumn["12"]["Total"] 						= False;		
		
		$configColumn["13"]["Titulo"] 						= "Mensaje";				
		$configColumn["13"]["FiledSouce"] 					= "Mensaje";				
		$configColumn["13"]["Formato"] 						= "";		
		$configColumn["13"]["Width"] 						= "80px";	
		$configColumn["13"]["Total"] 						= False;		
		
		$configColumn["14"]["Titulo"] 						= "Comentario 1";				
		$configColumn["14"]["FiledSouce"] 					= "Comentario 1";				
		$configColumn["14"]["Formato"] 						= "";		
		$configColumn["14"]["Width"] 						= "80px";	
		$configColumn["14"]["Total"] 						= False;		
		
		$configColumn["15"]["Titulo"] 						= "Comentario 2";				
		$configColumn["15"]["FiledSouce"] 					= "Comentario 2";				
		$configColumn["15"]["Formato"] 						= "";		
		$configColumn["15"]["Width"] 						= "80px";	
		$configColumn["15"]["Total"] 						= False;		
		
		$configColumn["16"]["Titulo"] 						= "Ubicacion";				
		$configColumn["16"]["FiledSouce"] 					= "Ubicacion";				
		$configColumn["16"]["Formato"] 						= "";		
		$configColumn["16"]["Width"] 						= "80px";	
		$configColumn["16"]["Total"] 						= False;		
		
			
		
		
		$resultado 	= helper_reporteGeneralCreateTable($objDetail,$configColumn,'0px',NULL,NULL);
		echo $resultado["table"];
		?>
		
		
		
		<br/>	
		
		
		<?php 
		echo helper_reporteGeneralCreateFirma(	
			$objFirmaEncription,
			"17",
			"1360px"
		);
		
		
		?>
		
		
	</body>	
</html>