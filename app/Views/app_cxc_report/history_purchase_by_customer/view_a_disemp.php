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
		
		$configColumn["0"]["Titulo"]		= "Fecha";
		$configColumn["0"]["FiledSouce"]	= "createdOn";
		$configColumn["0"]["Width"]			= "80px";
		$configColumn["0"]["Formato"]		= "Date";
		
		$configColumn["1"]["Titulo"]		= "Factura";
		$configColumn["1"]["FiledSouce"]	= "transactionNumber";
		$configColumn["1"]["Width"]			= "80px";
		
		$configColumn["2"]["Titulo"]		= "Codigo";
		$configColumn["2"]["FiledSouce"]	= "itemNumber";
		$configColumn["2"]["Width"]			= "80px";
		
		$configColumn["3"]["Titulo"]		= "Producto";
		$configColumn["3"]["FiledSouce"]	= "itemName";
		$configColumn["3"]["Width"]			= "80px";
		
		$configColumn["4"]["Titulo"]		= "Cantidad";
		$configColumn["4"]["FiledSouce"]	= "quantity";
		$configColumn["4"]["Width"]			= "80px";
		
		$configColumn["5"]["Titulo"]		= "Precio";
		$configColumn["5"]["FiledSouce"]	= "unitaryPrice";
		$configColumn["5"]["Width"]			= "120px";
		$configColumn["5"]["Formato"]		= "Number";
		$configColumn["5"]["Total"]			= False;
		
		$configColumn["6"]["Titulo"]		= "Monto";
		$configColumn["6"]["FiledSouce"]	= "amount";
		$configColumn["6"]["Width"]			= "120px";
		$configColumn["6"]["Formato"]		= "Number";
		$configColumn["6"]["Total"]			= true;
						
		$resultado = helper_reporteGeneralCreateTable($objPayList,$configColumn,'0');
		?>
				
		<?php 

		$objClient["customerNumber"]     = isset($objClient["customerNumber"]) ? $objClient["customerNumber"] : '';
		$objClient["legalName"]          = isset($objClient["legalName"]) ? $objClient["legalName"] : '';
		$objClient["identificationType"] = isset($objClient["identificationType"]) ? $objClient["identificationType"] : '';
		$objClient["identification"]     = isset($objClient["identification"]) ? $objClient["identification"] : '';
		$objClient["state"]              = isset($objClient["state"]) ? $objClient["state"] : '';
		$objClient["address"]            = isset($objClient["address"]) ? $objClient["address"] : '';
		$objClient["phoneNumber"]        = isset($objClient["phoneNumber"]) ? $objClient["phoneNumber"] : '';
		$objClient["statusClient"]       = isset($objClient["statusClient"]) ? $objClient["statusClient"] : '';
		$objClient["limitCreditCordoba"] = isset($objClient["limitCreditCordoba"]) ? $objClient["limitCreditCordoba"] : 0;
		$objClient["balanceCordoba"]     = isset($objClient["balanceCordoba"]) ? $objClient["balanceCordoba"] : 0;
		$objClient["incomeCordoba"]      = isset($objClient["incomeCordoba"]) ? $objClient["incomeCordoba"] : 0;

		echo helper_reporteGeneralCreateEncabezado(
			'LISTADO DE HISTORICOS DE COMPRAS',
			$objCompany->name,
			$resultado["columnas"],
			'LISTADO DE HISTORICOS DE COMPRAS DEL CLIENTE :'.$objClient["customerNumber"],
			"",
			"",
			$resultado["width"]
		);
		?>
		
		<br/>	
		
		<?php
		$configColumn1["0"]["Titulo"] 		= "Nombre";
		$configColumn1["0"]["TituloFoot"]	= "";
		$configColumn1["0"]["FiledSouce"]	= "legalName";
		$configColumn1["0"]["Colspan"] 		= "7";
		$configColumn1["0"]["Formato"] 		= "";
		$configColumn1["0"]["Total"] 		= False;
		$configColumn1["0"]["Alineacion"] 	= "Left";
		$configColumn1["0"]["TotalValor"] 	= 0;
		$configColumn1["0"]["FiledSoucePrefix"]	= "";
		$configColumn1["0"]["Width"]			= "120px";
		
		$configColumn1["1"]["Titulo"] 		= "Tipo de Identificacion";
		$configColumn1["1"]["TituloFoot"]	= "";
		$configColumn1["1"]["FiledSouce"]	= "identificationType";
		$configColumn1["1"]["Colspan"] 		= "7";
		$configColumn1["1"]["Formato"] 		= "";
		$configColumn1["1"]["Total"] 		= False;
		$configColumn1["1"]["Alineacion"] 	= "Left";
		$configColumn1["1"]["TotalValor"] 	= 0;
		$configColumn1["1"]["FiledSoucePrefix"]	= "";
		$configColumn1["1"]["Width"]			= "120px";
		
		$configColumn1["2"]["Titulo"] 		= "Identificacion";
		$configColumn1["2"]["TituloFoot"]	= "";
		$configColumn1["2"]["FiledSouce"]	= "identification";
		$configColumn1["2"]["Colspan"] 		= "7";
		$configColumn1["2"]["Formato"] 		= "";
		$configColumn1["2"]["Total"] 		= False;
		$configColumn1["2"]["Alineacion"] 	= "Left";
		$configColumn1["2"]["TotalValor"] 	= 0;
		$configColumn1["2"]["FiledSoucePrefix"]	= "";
		$configColumn1["2"]["Width"]			= "120px";
		
		$configColumn1["3"]["Titulo"] 		= "Estado";
		$configColumn1["3"]["TituloFoot"]	= "";
		$configColumn1["3"]["FiledSouce"]	= "state";
		$configColumn1["3"]["Colspan"] 		= "7";
		$configColumn1["3"]["Formato"] 		= "";
		$configColumn1["3"]["Total"] 		= False;
		$configColumn1["3"]["Alineacion"] 	= "Left";
		$configColumn1["3"]["TotalValor"] 	= 0;
		$configColumn1["3"]["FiledSoucePrefix"]	= "";
		$configColumn1["3"]["Width"]			= "120px";
		
		$configColumn1["4"]["Titulo"] 		= "Direccion";
		$configColumn1["4"]["TituloFoot"]	= "";
		$configColumn1["4"]["FiledSouce"]	= "address";
		$configColumn1["4"]["Colspan"] 		= "7";
		$configColumn1["4"]["Formato"] 		= "";
		$configColumn1["4"]["Total"] 		= False;
		$configColumn1["4"]["Alineacion"] 	= "Left";
		$configColumn1["4"]["TotalValor"] 	= 0;
		$configColumn1["4"]["FiledSoucePrefix"]	= "";
		$configColumn1["4"]["Width"]			= "120px";
		
		$configColumn1["5"]["Titulo"] 		= "Telefono";
		$configColumn1["5"]["TituloFoot"]	= "";
		$configColumn1["5"]["FiledSouce"]	= "phoneNumber";
		$configColumn1["5"]["Colspan"] 		= "7";
		$configColumn1["5"]["Formato"] 		= "";
		$configColumn1["5"]["Total"] 		= False;
		$configColumn1["5"]["Alineacion"] 	= "Left";
		$configColumn1["5"]["TotalValor"] 	= 0;
		$configColumn1["5"]["FiledSoucePrefix"]	= "";
		$configColumn1["5"]["Width"]			= "120px";
		
		$configColumn1["6"]["Titulo"] 		= "Estado del Cliente";
		$configColumn1["6"]["TituloFoot"]	= "";
		$configColumn1["6"]["FiledSouce"]	= "statusClient";
		$configColumn1["6"]["Colspan"] 		= "7";
		$configColumn1["6"]["Formato"] 		= "";
		$configColumn1["6"]["Total"] 		= False;
		$configColumn1["6"]["Alineacion"] 	= "Left";
		$configColumn1["6"]["TotalValor"] 	= 0;
		$configColumn1["6"]["FiledSoucePrefix"]	= "";
		$configColumn1["6"]["Width"]			= "120px";				
		
		$objClient;
		$resultado2 = helper_reporteGeneralCreateTableVertical(
			$objClient,
			$configColumn1,
			$resultado["columnas"],
			$resultado["width"]
		);
		
		echo $resultado2;
		?>
				
		<br/>	
				
		<?php 
		echo $resultado["table"];
		?>
		
		<br/>		
		<?php 
		helper_reporteGeneralCreateFirma(	
			$objFirmaEncription,
			$resultado["columnas"],
			$resultado["width"]
		);
		?>								
		
	</body>	
</html>