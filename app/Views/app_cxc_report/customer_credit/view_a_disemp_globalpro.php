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
		
		
		$configColumn["1"]["Titulo"] 		= "Nombre";
		$configColumn["1"]["TituloFoot"]	= "";
		$configColumn["1"]["FiledSouce"]	= "legalName";
		$configColumn["1"]["Colspan"] 		= "1";
		$configColumn["1"]["Formato"] 		= "";
		$configColumn["1"]["Total"] 		= False;
		$configColumn["1"]["Alineacion"] 	= "Left";
		$configColumn["1"]["TotalValor"] 	= 0;
		$configColumn["1"]["FiledSoucePrefix"]	= "";
		$configColumn["1"]["Width"]			= "250px";
		
		
		$configColumn["2"]["Titulo"] 		= "Saldo";
		$configColumn["2"]["TituloFoot"]	= "";
		$configColumn["2"]["FiledSouce"]	= "remainingDocument";
		$configColumn["2"]["Colspan"] 		= "1";
		$configColumn["2"]["Formato"] 		= "";
		$configColumn["2"]["Total"] 		= True;
		$configColumn["2"]["Alineacion"] 	= "Left";
		$configColumn["2"]["TotalValor"] 	= 0;
		$configColumn["2"]["FiledSoucePrefix"]	= "";
		$configColumn["2"]["Width"]			= "80px";
		
		
		$configColumn["3"]["Titulo"] 		= "Factura";
		$configColumn["3"]["TituloFoot"]	= "";
		$configColumn["3"]["FiledSouce"]	= "factura";
		$configColumn["3"]["Colspan"] 		= "1";
		$configColumn["3"]["Formato"] 		= "";
		$configColumn["3"]["Total"] 		= False;
		$configColumn["3"]["Alineacion"] 	= "Left";
		$configColumn["3"]["TotalValor"] 	= 0;
		$configColumn["3"]["FiledSoucePrefix"]	= "";
		$configColumn["3"]["Width"]			= "80px";
		
		
		$configColumn["4"]["Titulo"] 			= "Vendedor";
		$configColumn["4"]["TituloFoot"]		= "";
		$configColumn["4"]["FiledSouce"]		= "employerName";
		$configColumn["4"]["Colspan"] 			= "1";
		$configColumn["4"]["Formato"] 			= "";
		$configColumn["4"]["Total"] 			= False;
		$configColumn["4"]["Alineacion"] 		= "Left";
		$configColumn["4"]["TotalValor"] 		= 0;
		$configColumn["4"]["FiledSoucePrefix"]	= "";
		$configColumn["4"]["Width"]				= "80px";
		
		
		
		$configColumn["8"]["Titulo"] 		= "Cedula";
		$configColumn["8"]["TituloFoot"]	= "";
		$configColumn["8"]["FiledSouce"]	= "identification";
		$configColumn["8"]["Colspan"] 		= "1";
		$configColumn["8"]["Formato"] 		= "";
		$configColumn["8"]["Total"] 		= False;
		$configColumn["8"]["Alineacion"] 	= "Left";
		$configColumn["8"]["TotalValor"] 	= 0;
		$configColumn["8"]["FiledSoucePrefix"]	= "";
		$configColumn["8"]["Width"]			= "130px";
			
		$configColumn["9"]["Titulo"] 		= "Telefono";
		$configColumn["9"]["TituloFoot"]	= "";
		$configColumn["9"]["FiledSouce"]	= "phone";
		$configColumn["9"]["Colspan"] 		= "1";
		$configColumn["9"]["Formato"] 		= "";
		$configColumn["9"]["Total"] 		= False;
		$configColumn["9"]["Alineacion"] 	= "Left";
		$configColumn["9"]["TotalValor"] 	= 0;
		$configColumn["9"]["FiledSoucePrefix"]	= "";
		$configColumn["9"]["Width"]			= "90px";
		
		
		$configColumn["10"]["Titulo"] 		= "Inicial";
		$configColumn["10"]["TituloFoot"]	= "";
		$configColumn["10"]["FiledSouce"]	= "capitalPrestado";
		$configColumn["10"]["Colspan"] 		= "1";
		$configColumn["10"]["Formato"] 		= "Number";
		$configColumn["10"]["Total"] 		= True;
		$configColumn["10"]["Alineacion"] 	= "Left";
		$configColumn["10"]["TotalValor"] 	= 0;
		$configColumn["10"]["FiledSoucePrefix"]	= "";
		$configColumn["10"]["Width"]			= "90px";
		
		
		$configColumn["11"]["Titulo"] 		= "Fecha de cancelacion";
		$configColumn["11"]["TituloFoot"]	= "";
		$configColumn["11"]["FiledSouce"]	= "ultimoPagoFecha";
		$configColumn["11"]["Colspan"] 		= "1";
		$configColumn["11"]["Formato"] 		= "Date";
		$configColumn["11"]["Total"] 		= False;
		$configColumn["11"]["Alineacion"] 	= "Left";
		$configColumn["11"]["TotalValor"] 	= 0;
		$configColumn["11"]["FiledSoucePrefix"]	= "";
		$configColumn["11"]["Width"]			= "90px";
		
		$resultado = helper_reporteGeneralCreateTable($objDetail,$configColumn,'0');
		?>
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'CLIENTES POR COBRAR',
			$objCompany->name,
			$resultado["columnas"],
			'',
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