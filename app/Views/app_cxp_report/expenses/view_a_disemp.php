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
		$configColumn["00"]["Titulo"] 		= "Gasto";		
		$configColumn["01"]["Titulo"] 		= "Fecha";	
		$configColumn["02"]["Titulo"] 		= "Ruc";		
		$configColumn["03"]["Titulo"] 		= "Proveedor";		
		$configColumn["04"]["Titulo"] 		= "Factura";		
		$configColumn["05"]["Titulo"] 		= "Monto";		
		$configColumn["06"]["Titulo"] 		= "IVA";
		$configColumn["07"]["Titulo"] 		= "Total";
		$configColumn["08"]["Titulo"] 		= "Tipo";
		$configColumn["09"]["Titulo"] 		= "Codigo Reglon";
		$configColumn["10"]["Titulo"] 		= "Clasificación";
		$configColumn["11"]["Titulo"] 		= "Categoria";
		$configColumn["12"]["Titulo"] 		= "Nota";
		$configColumn["13"]["Titulo"] 		= "Sucursal";
		
		

		$configColumn["00"]["FiledSouce"] 		= "transactionNumber";		
		$configColumn["01"]["FiledSouce"] 		= "createdOn";	
		$configColumn["02"]["FiledSouce"] 		= "ruc";		
		$configColumn["03"]["FiledSouce"] 		= "Proveedor";		
		$configColumn["04"]["FiledSouce"] 		= "reference2";		
		$configColumn["05"]["FiledSouce"] 		= "amount";		
		$configColumn["06"]["FiledSouce"] 		= "Iva";
		$configColumn["07"]["FiledSouce"] 		= "Total";
		$configColumn["08"]["FiledSouce"] 		= "Tipo";
		$configColumn["09"]["FiledSouce"] 		= "CodigoReglon";		
		$configColumn["10"]["FiledSouce"] 		= "Clasificacion";
		$configColumn["11"]["FiledSouce"] 		= "Categoria";
		$configColumn["12"]["FiledSouce"] 		= "note";
		$configColumn["13"]["FiledSouce"] 		= "sucursal";
		

		$configColumn["00"]["Formato"] 		= "";		
		$configColumn["01"]["Formato"] 		= "Date";	
		$configColumn["02"]["Formato"] 		= "";		
		$configColumn["03"]["Formato"] 		= "";		
		$configColumn["04"]["Formato"] 		= "";		
		$configColumn["05"]["Formato"] 		= "Number";		
		$configColumn["06"]["Formato"] 		= "Number";
		$configColumn["07"]["Formato"] 		= "Number";
		$configColumn["08"]["Formato"] 		= "";
		$configColumn["09"]["Formato"] 		= "";
		$configColumn["10"]["Formato"] 		= "";
		$configColumn["11"]["Formato"] 		= "";		
		$configColumn["12"]["Formato"] 		= "";
		$configColumn["13"]["Formato"] 		= "";
		
		
		$configColumn["00"]["Width"] 		= "80px";		
		$configColumn["01"]["Width"] 		= "80px";	
		$configColumn["02"]["Width"] 		= "60px";		
		$configColumn["03"]["Width"] 		= "60px";		
		$configColumn["04"]["Width"] 		= "60px";	
		$configColumn["05"]["Width"] 		= "120px";		
		$configColumn["06"]["Width"] 		= "120px";
		$configColumn["07"]["Width"] 		= "120px";
		$configColumn["08"]["Width"] 		= "60px";
		$configColumn["09"]["Width"] 		= "60px";	
		$configColumn["10"]["Width"] 		= "120px";
		$configColumn["11"]["Width"] 		= "250px";
		$configColumn["12"]["Width"] 		= "300px";
		$configColumn["13"]["Width"] 		= "80px";
		
		
		$configColumn["00"]["Total"] 		= False;		
		$configColumn["01"]["Total"] 		= False;		
		$configColumn["02"]["Total"] 		= False;
		$configColumn["03"]["Total"] 		= False;
		$configColumn["04"]["Total"] 		= False;		
		$configColumn["05"]["Total"] 		= True;
		$configColumn["06"]["Total"] 		= True;
		$configColumn["07"]["Total"] 		= True;
		$configColumn["08"]["Total"] 		= False;
		$configColumn["09"]["Total"] 		= False;
		$configColumn["10"]["Total"] 		= False;
		$configColumn["11"]["Total"] 		= False;
		$configColumn["12"]["Total"] 		= False;
		$configColumn["13"]["Total"] 		= False;
		               
		
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