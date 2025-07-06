<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $objFirmaEncription; ?></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		
		<style>
			@page {       
                          size: 2.7in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:0px;
			}
			table
			{
			  font-size: x-small;
			  font-weight: bold;
			  font-family: Consolas, monaco, monospace;
			}
		</style>
		
	</head>
	<body style="font-family:monospace;font-size:smaller;margin:10px 10px 10px 10px;width:100%"> 
		
		<?php
		$path    	 = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objLogo->value;
		$widthPage	 = "150px";
		$type    	 = pathinfo($path, PATHINFO_EXTENSION);
		$data    	 = file_get_contents($path);
		$base64  	 = 'data:image/' . $type . ';base64,' . base64_encode($data);	
		?>
		
		<table style='width:100%'>
			<tr>
			  <td colspan='3' style='text-align:center'>
				<img  src='<?php echo $base64; ?>' width='110'  >
			  </td>
			</tr>
			<tr>
			  <td colspan='3' style='text-align:center'>
				RESUMEN DE CAJA
			  </td>
			</tr>
			<tr>
			  <td colspan='3' style='text-align:center'>
				<?php echo $objCompany->name; ?>
			  </td>
			</tr>
			<tr>
			  <td colspan='3' style='text-align:center'>
				del <?php echo $startOn; ?> al  <?php echo $endOn; ?>
			  </td>
			</tr>
			<tr>
			  <td colspan='3' style='text-align:center'>
				Usuario <?php echo ($obUserModel ? $obUserModel->nickname : "TODOS");  ?>
			  </td>
			</tr>
		</table>
		
		
	    <br/><br/>
		
		<?php
		$totalCordoba 							= 0;
		$totalDolares 							= 0;
		$configColumnAbonos["0"]["Titulo"] 		= "Codigo";		
		$configColumnAbonos["1"]["Titulo"] 		= "Cliente";		
		$configColumnAbonos["2"]["Titulo"] 		= "Moneda";		
		$configColumnAbonos["3"]["Titulo"] 		= "Fecha";		
		$configColumnAbonos["4"]["Titulo"] 		= "Fac";		
		$configColumnAbonos["5"]["Titulo"] 		= "Transaccion";		
		$configColumnAbonos["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnAbonos["7"]["Titulo"] 		= "Estado";		
		$configColumnAbonos["8"]["Titulo"] 		= "Monto";		
		$configColumnAbonos["9"]["Titulo"] 		= "Usuario";		
		$configColumnAbonos["10"]["Titulo"] 		= "Nota";		
					 
		$configColumnAbonos["0"]["FiledSouce"] 		= "customerNumber";		
		$configColumnAbonos["1"]["FiledSouce"] 		= "firstName";		
		$configColumnAbonos["2"]["FiledSouce"] 		= "moneda";		
		$configColumnAbonos["3"]["FiledSouce"] 		= "transactionOn";		
		$configColumnAbonos["4"]["FiledSouce"] 		= "Fac";		
		$configColumnAbonos["5"]["FiledSouce"] 		= "transactionName";		
		$configColumnAbonos["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnAbonos["7"]["FiledSouce"] 		= "estado";		
		$configColumnAbonos["8"]["FiledSouce"] 		= "montoFac";		
		$configColumnAbonos["9"]["FiledSouce"] 		= "nickname";		
		$configColumnAbonos["10"]["FiledSouce"] 	= "note";	
		
		
		
		$configColumnAbonos["0"]["Formato"] 		= "";		
		$configColumnAbonos["1"]["Formato"] 		= "";		
		$configColumnAbonos["2"]["Formato"] 		= "";		
		$configColumnAbonos["3"]["Formato"] 		= "Date";		
		$configColumnAbonos["4"]["Formato"] 		= "";		
		$configColumnAbonos["5"]["Formato"] 		= "";		
		$configColumnAbonos["6"]["Formato"] 		= "";		
		$configColumnAbonos["7"]["Formato"] 		= "";		
		$configColumnAbonos["8"]["Formato"] 		= "Number";		
		$configColumnAbonos["9"]["Formato"] 		= "";		
		$configColumnAbonos["10"]["Formato"] 		= "";	
		
		
		
		$configColumnAbonos["0"]["Width"] 		= "80px";		
		$configColumnAbonos["1"]["Width"] 		= "220px";		
		$configColumnAbonos["2"]["Width"] 		= "85px";		
		$configColumnAbonos["3"]["Width"] 		= "80px";		
		$configColumnAbonos["4"]["Width"] 		= "80px";		
		$configColumnAbonos["5"]["Width"] 		= "200px";		
		$configColumnAbonos["6"]["Width"] 		= "80px";		
		$configColumnAbonos["7"]["Width"] 		= "80px";		
		$configColumnAbonos["8"]["Width"] 		= "120px";		
		$configColumnAbonos["9"]["Width"] 		= "120px";		
		$configColumnAbonos["10"]["Width"] 		= "220px";	
		
		$configColumnAbonos["0"]["Total"] 		= False;		
		$configColumnAbonos["1"]["Total"] 		= False;		
		$configColumnAbonos["2"]["Total"] 		= False;		
		$configColumnAbonos["3"]["Total"] 		= False;		
		$configColumnAbonos["4"]["Total"] 		= False;		
		$configColumnAbonos["5"]["Total"] 		= False;		
		$configColumnAbonos["6"]["Total"] 		= False;		
		$configColumnAbonos["7"]["Total"] 		= False;		
		$configColumnAbonos["8"]["Total"] 		= True;		
		$configColumnAbonos["9"]["Total"] 		= False;		
		$configColumnAbonos["10"]["Total"] 		= False;	
		
	
		$configColumnAbonosDolares["0"]["Titulo"] 		= "Codigo";		
		$configColumnAbonosDolares["1"]["Titulo"] 		= "Cliente";		
		$configColumnAbonosDolares["2"]["Titulo"] 		= "Moneda";		
		$configColumnAbonosDolares["3"]["Titulo"] 		= "Fecha";		
		$configColumnAbonosDolares["4"]["Titulo"] 		= "Fac";		
		$configColumnAbonosDolares["5"]["Titulo"] 		= "Transaccion";		
		$configColumnAbonosDolares["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnAbonosDolares["7"]["Titulo"] 		= "Estado";		
		$configColumnAbonosDolares["8"]["Titulo"] 		= "Monto";		
		$configColumnAbonosDolares["9"]["Titulo"] 		= "Usuario";		
		$configColumnAbonosDolares["10"]["Titulo"] 		= "Nota";		
						   
		$configColumnAbonosDolares["0"]["FiledSouce"] 		= "customerNumber";		
		$configColumnAbonosDolares["1"]["FiledSouce"] 		= "firstName";		
		$configColumnAbonosDolares["2"]["FiledSouce"] 		= "moneda";		
		$configColumnAbonosDolares["3"]["FiledSouce"] 		= "transactionOn";		
		$configColumnAbonosDolares["4"]["FiledSouce"] 		= "Fac";		
		$configColumnAbonosDolares["5"]["FiledSouce"] 		= "transactionName";		
		$configColumnAbonosDolares["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnAbonosDolares["7"]["FiledSouce"] 		= "estado";		
		$configColumnAbonosDolares["8"]["FiledSouce"] 		= "montoFac";		
		$configColumnAbonosDolares["9"]["FiledSouce"] 		= "nickname";		
		$configColumnAbonosDolares["10"]["FiledSouce"] 	= "note";	
						   
		$configColumnAbonosDolares["0"]["Formato"] 		= "";		
		$configColumnAbonosDolares["1"]["Formato"] 		= "";		
		$configColumnAbonosDolares["2"]["Formato"] 		= "";		
		$configColumnAbonosDolares["3"]["Formato"] 		= "Date";		
		$configColumnAbonosDolares["4"]["Formato"] 		= "";		
		$configColumnAbonosDolares["5"]["Formato"] 		= "";		
		$configColumnAbonosDolares["6"]["Formato"] 		= "";		
		$configColumnAbonosDolares["7"]["Formato"] 		= "";		
		$configColumnAbonosDolares["8"]["Formato"] 		= "Number";		
		$configColumnAbonosDolares["9"]["Formato"] 		= "";		
		$configColumnAbonosDolares["10"]["Formato"] 		= "";	
						   
		$configColumnAbonosDolares["0"]["Width"] 		= "80px";		
		$configColumnAbonosDolares["1"]["Width"] 		= "220px";		
		$configColumnAbonosDolares["2"]["Width"] 		= "85px";		
		$configColumnAbonosDolares["3"]["Width"] 		= "80px";		
		$configColumnAbonosDolares["4"]["Width"] 		= "80px";		
		$configColumnAbonosDolares["5"]["Width"] 		= "200px";		
		$configColumnAbonosDolares["6"]["Width"] 		= "80px";		
		$configColumnAbonosDolares["7"]["Width"] 		= "80px";		
		$configColumnAbonosDolares["8"]["Width"] 		= "120px";		
		$configColumnAbonosDolares["9"]["Width"] 		= "120px";		
		$configColumnAbonosDolares["10"]["Width"] 		= "220px";	
						   
		$configColumnAbonosDolares["0"]["Total"] 		= False;		
		$configColumnAbonosDolares["1"]["Total"] 		= False;		
		$configColumnAbonosDolares["2"]["Total"] 		= False;		
		$configColumnAbonosDolares["3"]["Total"] 		= False;		
		$configColumnAbonosDolares["4"]["Total"] 		= False;		
		$configColumnAbonosDolares["5"]["Total"] 		= False;		
		$configColumnAbonosDolares["6"]["Total"] 		= False;		
		$configColumnAbonosDolares["7"]["Total"] 		= False;		
		$configColumnAbonosDolares["8"]["Total"] 		= True;		
		$configColumnAbonosDolares["9"]["Total"] 		= False;		
		$configColumnAbonosDolares["10"]["Total"] 		= False;	
		
		
		$objDetailDolar 	= $objDetail;
		$objDetailCordoba 	= $objDetail;
		
		if($objDetailCordoba != null)
		$objDetailCordoba 	= array_filter($objDetailCordoba,function($var){
			if (strtoupper($var["moneda"]) == "CORDOBA")
				return true;
		});
		
		if($objDetailDolar != null)
		$objDetailDolar 	= array_filter($objDetailDolar,function($var){
			if (strtoupper($var["moneda"]) == "DOLAR")
				return true;
		});
		
		
		$resultadoAbonosCordoba = helper_reporteGeneralCreateTable(
			$objDetailCordoba,
			$configColumnAbonos,
			'0px',
			"CORDOBA = LISTA DE ABONOS/ ABONOS AL CAPITAL / CANCELACION ANTICIPADA "
		);
		
		$resultadoAbonosDolar = helper_reporteGeneralCreateTable(
			$objDetailDolar,
			$configColumnAbonosDolares,
			'0px',
			"DOLARES = LISTA DE ABONOS/ ABONOS AL CAPITAL / CANCELACION ANTICIPADA ",
			'68c778',
			'black'
		);
		
		$totalCordoba = 0 + ($resultadoAbonosCordoba["table"] === 0 ? 0 : $resultadoAbonosCordoba["configColumn"][8]["TotalValor"]);
		$totalDolares = 0 + ($resultadoAbonosDolar["table"] === 0 ? 0 : $resultadoAbonosDolar["configColumn"][8]["TotalValor"]);
		
		
		?>
		
		
		<?php
		
		$configColumnVentaContado["0"]["Titulo"] 		= "Codigo";		
		$configColumnVentaContado["1"]["Titulo"] 		= "Cliente";		
		$configColumnVentaContado["2"]["Titulo"] 		= "Moneda";		
		$configColumnVentaContado["3"]["Titulo"] 		= "Fecha";		
		$configColumnVentaContado["4"]["Titulo"] 		= "Fac";		
		$configColumnVentaContado["5"]["Titulo"] 		= "Transaccion";		
		$configColumnVentaContado["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnVentaContado["7"]["Titulo"] 		= "Estado";		
		$configColumnVentaContado["8"]["Titulo"] 		= "Monto";		
		$configColumnVentaContado["9"]["Titulo"] 		= "Usuario";		
		$configColumnVentaContado["10"]["Titulo"] 		= "Nota";		
					 
		$configColumnVentaContado["0"]["FiledSouce"] 		= "customerNumber";		
		$configColumnVentaContado["1"]["FiledSouce"] 		= "firstName";		
		$configColumnVentaContado["2"]["FiledSouce"] 		= "currencyName";		
		$configColumnVentaContado["3"]["FiledSouce"] 		= "transactionOn";		
		$configColumnVentaContado["4"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnVentaContado["5"]["FiledSouce"] 		= "tipo";		
		$configColumnVentaContado["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnVentaContado["7"]["FiledSouce"] 		= "statusName";		
		$configColumnVentaContado["8"]["FiledSouce"] 		= "totalDocument";		
		$configColumnVentaContado["9"]["FiledSouce"] 		= "nickname";		
		$configColumnVentaContado["10"]["FiledSouce"] 		= "";	

		$configColumnVentaContado["0"]["Formato"] 		= "";		
		$configColumnVentaContado["1"]["Formato"] 		= "";		
		$configColumnVentaContado["2"]["Formato"] 		= "";		
		$configColumnVentaContado["3"]["Formato"] 		= "Date";		
		$configColumnVentaContado["4"]["Formato"] 		= "";		
		$configColumnVentaContado["5"]["Formato"] 		= "";		
		$configColumnVentaContado["6"]["Formato"] 		= "";		
		$configColumnVentaContado["7"]["Formato"] 		= "";		
		$configColumnVentaContado["8"]["Formato"] 		= "Number";		
		$configColumnVentaContado["9"]["Formato"] 		= "";		
		$configColumnVentaContado["10"]["Formato"] 		= "";		

		$configColumnVentaContado["0"]["Width"] 		= "80px";		
		$configColumnVentaContado["1"]["Width"] 		= "220px";		
		$configColumnVentaContado["2"]["Width"] 		= "85px";		
		$configColumnVentaContado["3"]["Width"] 		= "80px";		
		$configColumnVentaContado["4"]["Width"] 		= "80px";		
		$configColumnVentaContado["5"]["Width"] 		= "200px";		
		$configColumnVentaContado["6"]["Width"] 		= "80px";		
		$configColumnVentaContado["7"]["Width"] 		= "80px";		
		$configColumnVentaContado["8"]["Width"] 		= "120px";		
		$configColumnVentaContado["9"]["Width"] 		= "120px";		
		$configColumnVentaContado["10"]["Width"] 		= "220px";			
		
		
		$configColumnVentaContado["0"]["Total"] 		= False;		
		$configColumnVentaContado["1"]["Total"] 		= False;		
		$configColumnVentaContado["2"]["Total"] 		= False;		
		$configColumnVentaContado["3"]["Total"] 		= False;		
		$configColumnVentaContado["4"]["Total"] 		= False;		
		$configColumnVentaContado["5"]["Total"] 		= False;		
		$configColumnVentaContado["6"]["Total"] 		= False;		
		$configColumnVentaContado["7"]["Total"] 		= False;		
		$configColumnVentaContado["8"]["Total"] 		= True;		
		$configColumnVentaContado["9"]["Total"] 		= False;		
		$configColumnVentaContado["10"]["Total"] 		= False;	
		
		
		$configColumnVentaContadoDolares["0"]["Titulo"] 		= "Codigo";		
		$configColumnVentaContadoDolares["1"]["Titulo"] 		= "Cliente";		
		$configColumnVentaContadoDolares["2"]["Titulo"] 		= "Moneda";		
		$configColumnVentaContadoDolares["3"]["Titulo"] 		= "Fecha";		
		$configColumnVentaContadoDolares["4"]["Titulo"] 		= "Fac";		
		$configColumnVentaContadoDolares["5"]["Titulo"] 		= "Transaccion";		
		$configColumnVentaContadoDolares["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnVentaContadoDolares["7"]["Titulo"] 		= "Estado";		
		$configColumnVentaContadoDolares["8"]["Titulo"] 		= "Monto";		
		$configColumnVentaContadoDolares["9"]["Titulo"] 		= "Usuario";		
		$configColumnVentaContadoDolares["10"]["Titulo"] 		= "Nota";		
								 
		$configColumnVentaContadoDolares["0"]["FiledSouce"] 		= "customerNumber";		
		$configColumnVentaContadoDolares["1"]["FiledSouce"] 		= "firstName";		
		$configColumnVentaContadoDolares["2"]["FiledSouce"] 		= "currencyName";		
		$configColumnVentaContadoDolares["3"]["FiledSouce"] 		= "transactionOn";		
		$configColumnVentaContadoDolares["4"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnVentaContadoDolares["5"]["FiledSouce"] 		= "tipo";		
		$configColumnVentaContadoDolares["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnVentaContadoDolares["7"]["FiledSouce"] 		= "statusName";		
		$configColumnVentaContadoDolares["8"]["FiledSouce"] 		= "totalDocument";		
		$configColumnVentaContadoDolares["9"]["FiledSouce"] 		= "nickname";		
		$configColumnVentaContadoDolares["10"]["FiledSouce"] 		= "";	
								 
		$configColumnVentaContadoDolares["0"]["Formato"] 		= "";		
		$configColumnVentaContadoDolares["1"]["Formato"] 		= "";		
		$configColumnVentaContadoDolares["2"]["Formato"] 		= "";		
		$configColumnVentaContadoDolares["3"]["Formato"] 		= "Date";		
		$configColumnVentaContadoDolares["4"]["Formato"] 		= "";		
		$configColumnVentaContadoDolares["5"]["Formato"] 		= "";		
		$configColumnVentaContadoDolares["6"]["Formato"] 		= "";		
		$configColumnVentaContadoDolares["7"]["Formato"] 		= "";		
		$configColumnVentaContadoDolares["8"]["Formato"] 		= "Number";		
		$configColumnVentaContadoDolares["9"]["Formato"] 		= "";		
		$configColumnVentaContadoDolares["10"]["Formato"] 		= "";		
								 
		$configColumnVentaContadoDolares["0"]["Width"] 		= "80px";		
		$configColumnVentaContadoDolares["1"]["Width"] 		= "220px";		
		$configColumnVentaContadoDolares["2"]["Width"] 		= "85px";		
		$configColumnVentaContadoDolares["3"]["Width"] 		= "80px";		
		$configColumnVentaContadoDolares["4"]["Width"] 		= "80px";		
		$configColumnVentaContadoDolares["5"]["Width"] 		= "200px";		
		$configColumnVentaContadoDolares["6"]["Width"] 		= "80px";		
		$configColumnVentaContadoDolares["7"]["Width"] 		= "80px";		
		$configColumnVentaContadoDolares["8"]["Width"] 		= "120px";		
		$configColumnVentaContadoDolares["9"]["Width"] 		= "120px";		
		$configColumnVentaContadoDolares["10"]["Width"] 		= "220px";			
								 
								 
		$configColumnVentaContadoDolares["0"]["Total"] 		= False;		
		$configColumnVentaContadoDolares["1"]["Total"] 		= False;		
		$configColumnVentaContadoDolares["2"]["Total"] 		= False;		
		$configColumnVentaContadoDolares["3"]["Total"] 		= False;		
		$configColumnVentaContadoDolares["4"]["Total"] 		= False;		
		$configColumnVentaContadoDolares["5"]["Total"] 		= False;		
		$configColumnVentaContadoDolares["6"]["Total"] 		= False;		
		$configColumnVentaContadoDolares["7"]["Total"] 		= False;		
		$configColumnVentaContadoDolares["8"]["Total"] 		= True;		
		$configColumnVentaContadoDolares["9"]["Total"] 		= False;		
		$configColumnVentaContadoDolares["10"]["Total"] 		= False;	
		
		
		$objSalesDolar 		= $objSales;
		$objSalesCordoba 	= $objSales;
		
		
		if($objSalesCordoba != null)
		$objSalesCordoba = array_filter($objSalesCordoba,function($var){
			if (
				strtoupper($var["currencyName"]) == "CORDOBA" && 
				strtoupper($var["tipo"]) == "CONTADO" 
			)
			return true;
			
		});
		
		if($objSalesDolar != null)
		$objSalesDolar = array_filter($objSalesDolar,function($var){ 
			if (
				strtoupper($var["currencyName"]) == "DOLAR" && 
				strtoupper($var["tipo"]) == "CONTADO" 
			)
			return true;
			
		});
		
		$resultadoVentaContadoCordoba = helper_reporteGeneralCreateTable(
			$objSalesCordoba,
			$configColumnVentaContado,
			'0px',
			'CORDOBA = VENTAS DE CONTADO'
		);
		
		$resultadoVentaContadoDolar = helper_reporteGeneralCreateTable(
			$objSalesDolar,
			$configColumnVentaContadoDolares,
			'0px',
			'DOLARES = VENTAS DE CONTADO',
			'68c778',
			'black'
		);
		
		$totalCordoba = $totalCordoba + ($resultadoVentaContadoCordoba["table"] === 0 ? 0 : $resultadoVentaContadoCordoba["configColumn"][8]["TotalValor"]);
		$totalDolares = $totalDolares + ($resultadoVentaContadoDolar["table"] === 0 ? 0 : $resultadoVentaContadoDolar["configColumn"][8]["TotalValor"]);
		
		?>
		
		
		<?php
		
		$configColumnVentaCredito["0"]["Titulo"] 		= "Codigo";		
		$configColumnVentaCredito["1"]["Titulo"] 		= "Cliente";		
		$configColumnVentaCredito["2"]["Titulo"] 		= "Moneda";		
		$configColumnVentaCredito["3"]["Titulo"] 		= "Fecha";		
		$configColumnVentaCredito["4"]["Titulo"] 		= "Fac";		
		$configColumnVentaCredito["5"]["Titulo"] 		= "Transaccion";		
		$configColumnVentaCredito["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnVentaCredito["7"]["Titulo"] 		= "Estado";		
		$configColumnVentaCredito["8"]["Titulo"] 		= "Monto";		
		$configColumnVentaCredito["9"]["Titulo"] 		= "Usuario";		
		$configColumnVentaCredito["10"]["Titulo"] 		= "Nota";		
					 
		$configColumnVentaCredito["0"]["FiledSouce"] 		= "customerNumber";		
		$configColumnVentaCredito["1"]["FiledSouce"] 		= "firstName";		
		$configColumnVentaCredito["2"]["FiledSouce"] 		= "currencyName";		
		$configColumnVentaCredito["3"]["FiledSouce"] 		= "transactionOn";		
		$configColumnVentaCredito["4"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnVentaCredito["5"]["FiledSouce"] 		= "tipo";		
		$configColumnVentaCredito["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnVentaCredito["7"]["FiledSouce"] 		= "statusName";		
		$configColumnVentaCredito["8"]["FiledSouce"] 		= "receiptAmount";		
		$configColumnVentaCredito["9"]["FiledSouce"] 		= "nickname";		
		$configColumnVentaCredito["10"]["FiledSouce"] 		= "";	

		$configColumnVentaCredito["0"]["Formato"] 		= "";		
		$configColumnVentaCredito["1"]["Formato"] 		= "";		
		$configColumnVentaCredito["2"]["Formato"] 		= "";		
		$configColumnVentaCredito["3"]["Formato"] 		= "Date";		
		$configColumnVentaCredito["4"]["Formato"] 		= "";		
		$configColumnVentaCredito["5"]["Formato"] 		= "";		
		$configColumnVentaCredito["6"]["Formato"] 		= "";		
		$configColumnVentaCredito["7"]["Formato"] 		= "";		
		$configColumnVentaCredito["8"]["Formato"] 		= "Number";		
		$configColumnVentaCredito["9"]["Formato"] 		= "";		
		$configColumnVentaCredito["10"]["Formato"] 		= "";		

		$configColumnVentaCredito["0"]["Width"] 		= "80px";		
		$configColumnVentaCredito["1"]["Width"] 		= "220px";		
		$configColumnVentaCredito["2"]["Width"] 		= "85px";		
		$configColumnVentaCredito["3"]["Width"] 		= "80px";		
		$configColumnVentaCredito["4"]["Width"] 		= "80px";		
		$configColumnVentaCredito["5"]["Width"] 		= "200px";		
		$configColumnVentaCredito["6"]["Width"] 		= "80px";		
		$configColumnVentaCredito["7"]["Width"] 		= "80px";		
		$configColumnVentaCredito["8"]["Width"] 		= "120px";		
		$configColumnVentaCredito["9"]["Width"] 		= "120px";		
		$configColumnVentaCredito["10"]["Width"] 		= "220px";			
						  
		$configColumnVentaCredito["0"]["Total"] 		= False;		
		$configColumnVentaCredito["1"]["Total"] 		= False;		
		$configColumnVentaCredito["2"]["Total"] 		= False;		
		$configColumnVentaCredito["3"]["Total"] 		= False;		
		$configColumnVentaCredito["4"]["Total"] 		= False;		
		$configColumnVentaCredito["5"]["Total"] 		= False;		
		$configColumnVentaCredito["6"]["Total"] 		= False;		
		$configColumnVentaCredito["7"]["Total"] 		= False;		
		$configColumnVentaCredito["8"]["Total"] 		= True;		
		$configColumnVentaCredito["9"]["Total"] 		= False;		
		$configColumnVentaCredito["10"]["Total"] 		= False;	
		
	
		$configColumnVentaCreditoDolares["0"]["Titulo"] 		= "Codigo";		
		$configColumnVentaCreditoDolares["1"]["Titulo"] 		= "Cliente";		
		$configColumnVentaCreditoDolares["2"]["Titulo"] 		= "Moneda";		
		$configColumnVentaCreditoDolares["3"]["Titulo"] 		= "Fecha";		
		$configColumnVentaCreditoDolares["4"]["Titulo"] 		= "Fac";		
		$configColumnVentaCreditoDolares["5"]["Titulo"] 		= "Transaccion";		
		$configColumnVentaCreditoDolares["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnVentaCreditoDolares["7"]["Titulo"] 		= "Estado";		
		$configColumnVentaCreditoDolares["8"]["Titulo"] 		= "Monto";		
		$configColumnVentaCreditoDolares["9"]["Titulo"] 		= "Usuario";		
		$configColumnVentaCreditoDolares["10"]["Titulo"] 		= "Nota";		
							
		$configColumnVentaCreditoDolares["0"]["FiledSouce"] 		= "customerNumber";		
		$configColumnVentaCreditoDolares["1"]["FiledSouce"] 		= "firstName";		
		$configColumnVentaCreditoDolares["2"]["FiledSouce"] 		= "currencyName";		
		$configColumnVentaCreditoDolares["3"]["FiledSouce"] 		= "transactionOn";		
		$configColumnVentaCreditoDolares["4"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnVentaCreditoDolares["5"]["FiledSouce"] 		= "tipo";		
		$configColumnVentaCreditoDolares["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnVentaCreditoDolares["7"]["FiledSouce"] 		= "statusName";		
		$configColumnVentaCreditoDolares["8"]["FiledSouce"] 		= "receiptAmount";		
		$configColumnVentaCreditoDolares["9"]["FiledSouce"] 		= "nickname";		
		$configColumnVentaCreditoDolares["10"]["FiledSouce"] 		= "";	
							
		$configColumnVentaCreditoDolares["0"]["Formato"] 		= "";		
		$configColumnVentaCreditoDolares["1"]["Formato"] 		= "";		
		$configColumnVentaCreditoDolares["2"]["Formato"] 		= "";		
		$configColumnVentaCreditoDolares["3"]["Formato"] 		= "Date";		
		$configColumnVentaCreditoDolares["4"]["Formato"] 		= "";		
		$configColumnVentaCreditoDolares["5"]["Formato"] 		= "";		
		$configColumnVentaCreditoDolares["6"]["Formato"] 		= "";		
		$configColumnVentaCreditoDolares["7"]["Formato"] 		= "";		
		$configColumnVentaCreditoDolares["8"]["Formato"] 		= "Number";		
		$configColumnVentaCreditoDolares["9"]["Formato"] 		= "";		
		$configColumnVentaCreditoDolares["10"]["Formato"] 		= "";		
							
		$configColumnVentaCreditoDolares["0"]["Width"] 		= "80px";		
		$configColumnVentaCreditoDolares["1"]["Width"] 		= "220px";		
		$configColumnVentaCreditoDolares["2"]["Width"] 		= "85px";		
		$configColumnVentaCreditoDolares["3"]["Width"] 		= "80px";		
		$configColumnVentaCreditoDolares["4"]["Width"] 		= "80px";		
		$configColumnVentaCreditoDolares["5"]["Width"] 		= "200px";		
		$configColumnVentaCreditoDolares["6"]["Width"] 		= "80px";		
		$configColumnVentaCreditoDolares["7"]["Width"] 		= "80px";		
		$configColumnVentaCreditoDolares["8"]["Width"] 		= "120px";		
		$configColumnVentaCreditoDolares["9"]["Width"] 		= "120px";		
		$configColumnVentaCreditoDolares["10"]["Width"] 	= "220px";			
								 
								 
		$configColumnVentaCreditoDolares["0"]["Total"] 		= False;		
		$configColumnVentaCreditoDolares["1"]["Total"] 		= False;		
		$configColumnVentaCreditoDolares["2"]["Total"] 		= False;		
		$configColumnVentaCreditoDolares["3"]["Total"] 		= False;		
		$configColumnVentaCreditoDolares["4"]["Total"] 		= False;		
		$configColumnVentaCreditoDolares["5"]["Total"] 		= False;		
		$configColumnVentaCreditoDolares["6"]["Total"] 		= False;		
		$configColumnVentaCreditoDolares["7"]["Total"] 		= False;		
		$configColumnVentaCreditoDolares["8"]["Total"] 		= True;		
		$configColumnVentaCreditoDolares["9"]["Total"] 		= False;		
		$configColumnVentaCreditoDolares["10"]["Total"] 	= False;	
	
		
		$objSalesCreditoDolar 		= $objSalesCredito;
		$objSalesCreditoCordoba 	= $objSalesCredito;
		
		if($objSalesCreditoCordoba != null)
		$objSalesCreditoCordoba = array_filter($objSalesCreditoCordoba,function($var){
			
			if (
				strtoupper($var["currencyName"]) == "CORDOBA" && 
				strtoupper($var["tipo"]) == "CREDITO"
			)
				return true;
		});
		
		if($objSalesCreditoDolar != null)
		$objSalesCreditoDolar = array_filter($objSalesCreditoDolar,function($var){
			if (
				strtoupper($var["currencyName"]) == "DOLAR" && 
				strtoupper($var["tipo"]) == "CREDITO"
			)
				return true;
		});
		
		$resultadoVentaCreditoCordoba = helper_reporteGeneralCreateTable(
			$objSalesCreditoCordoba,
			$configColumnVentaCredito,
			'0px',
			'CORDOBA = CREDITO PRIMA'
		);
		
		$resultadoVentaCreditoDolar = helper_reporteGeneralCreateTable(
			$objSalesCreditoDolar,
			$configColumnVentaCreditoDolares,
			'0px',
			'DOLARES = CREDITO PRIMA',
			'68c778',
			'black'
		);
		
		$totalCordoba = $totalCordoba + ($resultadoVentaCreditoCordoba["table"] === 0 ? 0 : $resultadoVentaCreditoCordoba["configColumn"][8]["TotalValor"]);
		$totalDolares = $totalDolares + ($resultadoVentaCreditoDolar["table"] === 0 ? 0 : $resultadoVentaCreditoDolar["configColumn"][8]["TotalValor"]);
		
		?>
		
		
		
		<?php
		
		$configColumnIngresoCaja["0"]["Titulo"] 		= "Codigo";		
		$configColumnIngresoCaja["1"]["Titulo"] 		= "Cliente";		
		$configColumnIngresoCaja["2"]["Titulo"] 		= "Moneda";		
		$configColumnIngresoCaja["3"]["Titulo"] 		= "Fecha";		
		$configColumnIngresoCaja["4"]["Titulo"] 		= "Fac";		
		$configColumnIngresoCaja["5"]["Titulo"] 		= "Transaccion";		
		$configColumnIngresoCaja["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnIngresoCaja["7"]["Titulo"] 		= "Estado";		
		$configColumnIngresoCaja["8"]["Titulo"] 		= "Monto";		
		$configColumnIngresoCaja["9"]["Titulo"] 		= "Usuario";		
		$configColumnIngresoCaja["10"]["Titulo"] 		= "Nota";		
					 
		$configColumnIngresoCaja["0"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnIngresoCaja["1"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnIngresoCaja["2"]["FiledSouce"] 		= "moneda";		
		$configColumnIngresoCaja["3"]["FiledSouce"] 		= "transactionOn";		
		$configColumnIngresoCaja["4"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnIngresoCaja["5"]["FiledSouce"] 		= "transactionName";		
		$configColumnIngresoCaja["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnIngresoCaja["7"]["FiledSouce"] 		= "estado";		
		$configColumnIngresoCaja["8"]["FiledSouce"] 		= "montoTransaccion";		
		$configColumnIngresoCaja["9"]["FiledSouce"] 		= "nickname";		
		$configColumnIngresoCaja["10"]["FiledSouce"] 		= "note";	

		
		$configColumnIngresoCaja["0"]["Formato"] 		= "";		
		$configColumnIngresoCaja["1"]["Formato"] 		= "";		
		$configColumnIngresoCaja["2"]["Formato"] 		= "";		
		$configColumnIngresoCaja["3"]["Formato"] 		= "Date";		
		$configColumnIngresoCaja["4"]["Formato"] 		= "";		
		$configColumnIngresoCaja["5"]["Formato"] 		= "";		
		$configColumnIngresoCaja["6"]["Formato"] 		= "";		
		$configColumnIngresoCaja["7"]["Formato"] 		= "";		
		$configColumnIngresoCaja["8"]["Formato"] 		= "Number";		
		$configColumnIngresoCaja["9"]["Formato"] 		= "";		
		$configColumnIngresoCaja["10"]["Formato"] 		= "";	

		$configColumnIngresoCaja["0"]["Width"] 		= "80px";		
		$configColumnIngresoCaja["1"]["Width"] 		= "220px";		
		$configColumnIngresoCaja["2"]["Width"] 		= "85px";		
		$configColumnIngresoCaja["3"]["Width"] 		= "80px";		
		$configColumnIngresoCaja["4"]["Width"] 		= "80px";		
		$configColumnIngresoCaja["5"]["Width"] 		= "200px";		
		$configColumnIngresoCaja["6"]["Width"] 		= "80px";		
		$configColumnIngresoCaja["7"]["Width"] 		= "80px";		
		$configColumnIngresoCaja["8"]["Width"] 		= "120px";		
		$configColumnIngresoCaja["9"]["Width"] 		= "120px";		
		$configColumnIngresoCaja["10"]["Width"] 	= "220px";				
		
		$configColumnIngresoCaja["0"]["Total"] 		= False;		
		$configColumnIngresoCaja["1"]["Total"] 		= False;		
		$configColumnIngresoCaja["2"]["Total"] 		= False;		
		$configColumnIngresoCaja["3"]["Total"] 		= False;		
		$configColumnIngresoCaja["4"]["Total"] 		= False;		
		$configColumnIngresoCaja["5"]["Total"] 		= False;		
		$configColumnIngresoCaja["6"]["Total"] 		= False;		
		$configColumnIngresoCaja["7"]["Total"] 		= False;		
		$configColumnIngresoCaja["8"]["Total"] 		= True;		
		$configColumnIngresoCaja["9"]["Total"] 		= False;		
		$configColumnIngresoCaja["10"]["Total"] 		= False;	
		
		
		/***********************************/
		$configColumnIngresoCajaDolares["0"]["Titulo"] 		= "Codigo";		
		$configColumnIngresoCajaDolares["1"]["Titulo"] 		= "Cliente";		
		$configColumnIngresoCajaDolares["2"]["Titulo"] 		= "Moneda";		
		$configColumnIngresoCajaDolares["3"]["Titulo"] 		= "Fecha";		
		$configColumnIngresoCajaDolares["4"]["Titulo"] 		= "Fac";		
		$configColumnIngresoCajaDolares["5"]["Titulo"] 		= "Transaccion";		
		$configColumnIngresoCajaDolares["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnIngresoCajaDolares["7"]["Titulo"] 		= "Estado";		
		$configColumnIngresoCajaDolares["8"]["Titulo"] 		= "Monto";		
		$configColumnIngresoCajaDolares["9"]["Titulo"] 		= "Usuario";		
		$configColumnIngresoCajaDolares["10"]["Titulo"] 		= "Nota";		
								
		$configColumnIngresoCajaDolares["0"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnIngresoCajaDolares["1"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnIngresoCajaDolares["2"]["FiledSouce"] 		= "moneda";		
		$configColumnIngresoCajaDolares["3"]["FiledSouce"] 		= "transactionOn";		
		$configColumnIngresoCajaDolares["4"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnIngresoCajaDolares["5"]["FiledSouce"] 		= "transactionName";		
		$configColumnIngresoCajaDolares["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnIngresoCajaDolares["7"]["FiledSouce"] 		= "estado";		
		$configColumnIngresoCajaDolares["8"]["FiledSouce"] 		= "montoTransaccion";		
		$configColumnIngresoCajaDolares["9"]["FiledSouce"] 		= "nickname";		
		$configColumnIngresoCajaDolares["10"]["FiledSouce"] 		= "note";	
								
								
		$configColumnIngresoCajaDolares["0"]["Formato"] 		= "";		
		$configColumnIngresoCajaDolares["1"]["Formato"] 		= "";		
		$configColumnIngresoCajaDolares["2"]["Formato"] 		= "";		
		$configColumnIngresoCajaDolares["3"]["Formato"] 		= "Date";		
		$configColumnIngresoCajaDolares["4"]["Formato"] 		= "";		
		$configColumnIngresoCajaDolares["5"]["Formato"] 		= "";		
		$configColumnIngresoCajaDolares["6"]["Formato"] 		= "";		
		$configColumnIngresoCajaDolares["7"]["Formato"] 		= "";		
		$configColumnIngresoCajaDolares["8"]["Formato"] 		= "Number";		
		$configColumnIngresoCajaDolares["9"]["Formato"] 		= "";		
		$configColumnIngresoCajaDolares["10"]["Formato"] 		= "";	
								
		$configColumnIngresoCajaDolares["0"]["Width"] 		= "80px";		
		$configColumnIngresoCajaDolares["1"]["Width"] 		= "220px";		
		$configColumnIngresoCajaDolares["2"]["Width"] 		= "85px";		
		$configColumnIngresoCajaDolares["3"]["Width"] 		= "80px";		
		$configColumnIngresoCajaDolares["4"]["Width"] 		= "80px";		
		$configColumnIngresoCajaDolares["5"]["Width"] 		= "200px";		
		$configColumnIngresoCajaDolares["6"]["Width"] 		= "80px";		
		$configColumnIngresoCajaDolares["7"]["Width"] 		= "80px";		
		$configColumnIngresoCajaDolares["8"]["Width"] 		= "120px";		
		$configColumnIngresoCajaDolares["9"]["Width"] 		= "120px";		
		$configColumnIngresoCajaDolares["10"]["Width"] 	= "220px";				
								
		$configColumnIngresoCajaDolares["0"]["Total"] 		= False;		
		$configColumnIngresoCajaDolares["1"]["Total"] 		= False;		
		$configColumnIngresoCajaDolares["2"]["Total"] 		= False;		
		$configColumnIngresoCajaDolares["3"]["Total"] 		= False;		
		$configColumnIngresoCajaDolares["4"]["Total"] 		= False;		
		$configColumnIngresoCajaDolares["5"]["Total"] 		= False;		
		$configColumnIngresoCajaDolares["6"]["Total"] 		= False;		
		$configColumnIngresoCajaDolares["7"]["Total"] 		= False;		
		$configColumnIngresoCajaDolares["8"]["Total"] 		= True;		
		$configColumnIngresoCajaDolares["9"]["Total"] 		= False;		
		$configColumnIngresoCajaDolares["10"]["Total"] 		= False;	
		
		
		$objCashDolar 	= $objCash;
		$objCashCordoba = $objCash;
		
		if($objCashCordoba != null)
		$objCashCordoba = array_filter($objCashCordoba,function($var){
			if (strtoupper($var["moneda"]) == "CORDOBA")
				return true;
		});
		
		if($objCashDolar != null)
		$objCashDolar = array_filter($objCashDolar,function($var){
			if (strtoupper($var["moneda"]) == "DOLAR")
				return true;
		});
		
		$resultadoIngresoCajaCordoba = helper_reporteGeneralCreateTable(
			$objCashCordoba,
			$configColumnIngresoCaja,
			'0px',
			'CORDOBA = INGRESO DE CAJA'
		);
		
		$resultadoIngresoCajaDolares = helper_reporteGeneralCreateTable(
			$objCashDolar,
			$configColumnIngresoCajaDolares,
			'0px',
			'DOLARES = INGRESO DE CAJA',
			'68c778',
			'black'
		);
		
		
		$totalCordoba = $totalCordoba + ($resultadoIngresoCajaCordoba["table"] === 0 ? 0 : $resultadoIngresoCajaCordoba["configColumn"][8]["TotalValor"]);
		$totalDolares = $totalDolares + ($resultadoIngresoCajaDolares["table"] === 0 ? 0 : $resultadoIngresoCajaDolares["configColumn"][8]["TotalValor"]);
		
		?>
		
		
		<?php
		
		$configColumnSalidaCaja["0"]["Titulo"] 		= "Codigo";		
		$configColumnSalidaCaja["1"]["Titulo"] 		= "Cliente";		
		$configColumnSalidaCaja["2"]["Titulo"] 		= "Moneda";		
		$configColumnSalidaCaja["3"]["Titulo"] 		= "Fecha";		
		$configColumnSalidaCaja["4"]["Titulo"] 		= "Fac";		
		$configColumnSalidaCaja["5"]["Titulo"] 		= "Transaccion";		
		$configColumnSalidaCaja["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnSalidaCaja["7"]["Titulo"] 		= "Estado";		
		$configColumnSalidaCaja["8"]["Titulo"] 		= "Monto";		
		$configColumnSalidaCaja["9"]["Titulo"] 		= "Usuario";		
		$configColumnSalidaCaja["10"]["Titulo"] 	= "Nota";		
					 
		$configColumnSalidaCaja["0"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnSalidaCaja["1"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnSalidaCaja["2"]["FiledSouce"] 		= "moneda";		
		$configColumnSalidaCaja["3"]["FiledSouce"] 		= "transactionOn";		
		$configColumnSalidaCaja["4"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnSalidaCaja["5"]["FiledSouce"] 		= "transactionName";		
		$configColumnSalidaCaja["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnSalidaCaja["7"]["FiledSouce"] 		= "estado";		
		$configColumnSalidaCaja["8"]["FiledSouce"] 		= "montoTransaccion";		
		$configColumnSalidaCaja["9"]["FiledSouce"] 		= "nickname";		
		$configColumnSalidaCaja["10"]["FiledSouce"] 	= "note";	

		$configColumnSalidaCaja["0"]["Formato"] 		= "";		
		$configColumnSalidaCaja["1"]["Formato"] 		= "";		
		$configColumnSalidaCaja["2"]["Formato"] 		= "";		
		$configColumnSalidaCaja["3"]["Formato"] 		= "Date";		
		$configColumnSalidaCaja["4"]["Formato"] 		= "";		
		$configColumnSalidaCaja["5"]["Formato"] 		= "";		
		$configColumnSalidaCaja["6"]["Formato"] 		= "";		
		$configColumnSalidaCaja["7"]["Formato"] 		= "";		
		$configColumnSalidaCaja["8"]["Formato"] 		= "Number";		
		$configColumnSalidaCaja["9"]["Formato"] 		= "";		
		$configColumnSalidaCaja["10"]["Formato"] 		= "";				
		
		$configColumnSalidaCaja["0"]["Width"] 		= "80px";		
		$configColumnSalidaCaja["1"]["Width"] 		= "220px";		
		$configColumnSalidaCaja["2"]["Width"] 		= "85px";		
		$configColumnSalidaCaja["3"]["Width"] 		= "80px";		
		$configColumnSalidaCaja["4"]["Width"] 		= "80px";		
		$configColumnSalidaCaja["5"]["Width"] 		= "200px";		
		$configColumnSalidaCaja["6"]["Width"] 		= "80px";		
		$configColumnSalidaCaja["7"]["Width"] 		= "80px";		
		$configColumnSalidaCaja["8"]["Width"] 		= "120px";		
		$configColumnSalidaCaja["9"]["Width"] 		= "120px";		
		$configColumnSalidaCaja["10"]["Width"] 		= "220px";	
		
		$configColumnSalidaCaja["0"]["Total"] 		= False;		
		$configColumnSalidaCaja["1"]["Total"] 		= False;		
		$configColumnSalidaCaja["2"]["Total"] 		= False;		
		$configColumnSalidaCaja["3"]["Total"] 		= False;		
		$configColumnSalidaCaja["4"]["Total"] 		= False;		
		$configColumnSalidaCaja["5"]["Total"] 		= False;		
		$configColumnSalidaCaja["6"]["Total"] 		= False;		
		$configColumnSalidaCaja["7"]["Total"] 		= False;		
		$configColumnSalidaCaja["8"]["Total"] 		= True;		
		$configColumnSalidaCaja["9"]["Total"] 		= False;		
		$configColumnSalidaCaja["10"]["Total"] 		= False;	
	
		$configColumnSalidaCajaDolares["0"]["Titulo"] 		= "Codigo";		
		$configColumnSalidaCajaDolares["1"]["Titulo"] 		= "Cliente";		
		$configColumnSalidaCajaDolares["2"]["Titulo"] 		= "Moneda";		
		$configColumnSalidaCajaDolares["3"]["Titulo"] 		= "Fecha";		
		$configColumnSalidaCajaDolares["4"]["Titulo"] 		= "Fac";		
		$configColumnSalidaCajaDolares["5"]["Titulo"] 		= "Transaccion";		
		$configColumnSalidaCajaDolares["6"]["Titulo"] 		= "Tran. Numero";		
		$configColumnSalidaCajaDolares["7"]["Titulo"] 		= "Estado";		
		$configColumnSalidaCajaDolares["8"]["Titulo"] 		= "Monto";		
		$configColumnSalidaCajaDolares["9"]["Titulo"] 		= "Usuario";		
		$configColumnSalidaCajaDolares["10"]["Titulo"] 		= "Nota";		
							   
		$configColumnSalidaCajaDolares["0"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnSalidaCajaDolares["1"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnSalidaCajaDolares["2"]["FiledSouce"] 		= "moneda";		
		$configColumnSalidaCajaDolares["3"]["FiledSouce"] 		= "transactionOn";		
		$configColumnSalidaCajaDolares["4"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnSalidaCajaDolares["5"]["FiledSouce"] 		= "transactionName";		
		$configColumnSalidaCajaDolares["6"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnSalidaCajaDolares["7"]["FiledSouce"] 		= "estado";		
		$configColumnSalidaCajaDolares["8"]["FiledSouce"] 		= "montoTransaccion";		
		$configColumnSalidaCajaDolares["9"]["FiledSouce"] 		= "nickname";		
		$configColumnSalidaCajaDolares["10"]["FiledSouce"] 		= "note";	
							   
		$configColumnSalidaCajaDolares["0"]["Formato"] 		= "";		
		$configColumnSalidaCajaDolares["1"]["Formato"] 		= "";		
		$configColumnSalidaCajaDolares["2"]["Formato"] 		= "";		
		$configColumnSalidaCajaDolares["3"]["Formato"] 		= "Date";		
		$configColumnSalidaCajaDolares["4"]["Formato"] 		= "";		
		$configColumnSalidaCajaDolares["5"]["Formato"] 		= "";		
		$configColumnSalidaCajaDolares["6"]["Formato"] 		= "";		
		$configColumnSalidaCajaDolares["7"]["Formato"] 		= "";		
		$configColumnSalidaCajaDolares["8"]["Formato"] 		= "Number";		
		$configColumnSalidaCajaDolares["9"]["Formato"] 		= "";		
		$configColumnSalidaCajaDolares["10"]["Formato"] 		= "";				
							   
		$configColumnSalidaCajaDolares["0"]["Width"] 		= "80px";		
		$configColumnSalidaCajaDolares["1"]["Width"] 		= "220px";		
		$configColumnSalidaCajaDolares["2"]["Width"] 		= "85px";		
		$configColumnSalidaCajaDolares["3"]["Width"] 		= "80px";		
		$configColumnSalidaCajaDolares["4"]["Width"] 		= "80px";		
		$configColumnSalidaCajaDolares["5"]["Width"] 		= "200px";		
		$configColumnSalidaCajaDolares["6"]["Width"] 		= "80px";		
		$configColumnSalidaCajaDolares["7"]["Width"] 		= "80px";		
		$configColumnSalidaCajaDolares["8"]["Width"] 		= "120px";		
		$configColumnSalidaCajaDolares["9"]["Width"] 		= "120px";		
		$configColumnSalidaCajaDolares["10"]["Width"] 		= "220px";	
							   
		$configColumnSalidaCajaDolares["0"]["Total"] 		= False;		
		$configColumnSalidaCajaDolares["1"]["Total"] 		= False;		
		$configColumnSalidaCajaDolares["2"]["Total"] 		= False;		
		$configColumnSalidaCajaDolares["3"]["Total"] 		= False;		
		$configColumnSalidaCajaDolares["4"]["Total"] 		= False;		
		$configColumnSalidaCajaDolares["5"]["Total"] 		= False;		
		$configColumnSalidaCajaDolares["6"]["Total"] 		= False;		
		$configColumnSalidaCajaDolares["7"]["Total"] 		= False;		
		$configColumnSalidaCajaDolares["8"]["Total"] 		= True;		
		$configColumnSalidaCajaDolares["9"]["Total"] 		= False;		
		$configColumnSalidaCajaDolares["10"]["Total"] 		= False;	
		


		$objCashOutCordoba 	= $objCashOut;
		$objCashOutDolar 	= $objCashOut;
		
		if($objCashOutCordoba != null)
		$objCashOutCordoba = array_filter($objCashOutCordoba,function($var){
			if (strtoupper($var["moneda"]) == "CORDOBA")
				return true;
		});
		
		if($objCashOutDolar != null)
		$objCashOutDolar = array_filter($objCashOutDolar,function($var){
			if (strtoupper($var["moneda"]) == "DOLAR")
				return true;
		});
		
		$resultadoSalidaCajaCordoba = helper_reporteGeneralCreateTable(
				$objCashOutCordoba,
				$configColumnSalidaCaja,
				'0px',
				'CORDOBA = SALIDA DE CAJA'
		);
		
		$resultadoSalidaCajaDolares = helper_reporteGeneralCreateTable(
				$objCashOutDolar,
				$configColumnSalidaCajaDolares,
				'0px',
				'DOLARES = SALIDA DE CAJA',
				'68c778',
				'black'
		);
		
		
		
		$totalCordoba = $totalCordoba - ($resultadoSalidaCajaCordoba["table"] === 0 ? 0 : $resultadoSalidaCajaCordoba["configColumn"][8]["TotalValor"]);
		$totalDolares = $totalDolares - ($resultadoSalidaCajaDolares["table"] === 0 ? 0 : $resultadoSalidaCajaDolares["configColumn"][8]["TotalValor"]);
		
		
		?>
		
		


		
		<br/>			
		
		<?php 		
		
		$configTotalesCordobaEntradasColumns[0]["Titulo"] 		= "Cordobas Abonos";			
		$configTotalesCordobaEntradasColumns[0]["FiledSouce"] 	= "total";
		$configTotalesCordobaEntradasColumns[0]["Formato"] 		= "Number";
		$configTotalesCordobaEntradasColumns[0]["Colspan"] 		= 1;		
		$configTotalesCordobaEntradasColumns[0]["Width"] 				= "80px";
		$configTotalesCordobaEntradasColumns[0]["FiledSoucePrefix"] 	= "prefix";
		
		$configTotalesCordobaEntradasColumns[1]["Titulo"] 		= "Cant";			
		$configTotalesCordobaEntradasColumns[1]["FiledSouce"] 	= "cantidad";
		$configTotalesCordobaEntradasColumns[1]["Formato"] 		= "Number";
		$configTotalesCordobaEntradasColumns[1]["Colspan"] 		= 1;		
		$configTotalesCordobaEntradasColumns[1]["Width"] 		= $widthPage;
		
		$detailTotalesCordobaTotales[0]["total"] 				= ($resultadoAbonosCordoba["table"] === 0 ? 0 : $resultadoAbonosCordoba["configColumn"][8]["TotalValor"]);
		$detailTotalesCordobaTotales[0]["cantidad"] 			= ($resultadoAbonosCordoba["table"] === 0 ? 0 : $resultadoAbonosCordoba["configColumn"][8]["CantidadRegistro"]);
		$detailTotalesCordobaTotales[0]["prefix"] 				= "C$";
		
		$rosTotales = helper_reporteGeneralCreateTable(
				$detailTotalesCordobaTotales,
				$configTotalesCordobaEntradasColumns,
				'0',
				NULL
		);
		
		echo $rosTotales["table"];
		
		?>
		
		<br/>
		
		<?php 		
		
		$configTotalesCordobaEntradasVentaContadoColumns[0]["Titulo"] 		= "Cordobas Ventas";			
		$configTotalesCordobaEntradasVentaContadoColumns[0]["FiledSouce"] 	= "total";
		$configTotalesCordobaEntradasVentaContadoColumns[0]["Formato"] 		= "Number";
		$configTotalesCordobaEntradasVentaContadoColumns[0]["Colspan"] 		= 1;		
		$configTotalesCordobaEntradasVentaContadoColumns[0]["Width"] 		= "80px";
		$configTotalesCordobaEntradasVentaContadoColumns[0]["FiledSoucePrefix"] 	= "prefix";
		
		$configTotalesCordobaEntradasVentaContadoColumns[1]["Titulo"] 		= "Cant";			
		$configTotalesCordobaEntradasVentaContadoColumns[1]["FiledSouce"] 	= "cantidad";
		$configTotalesCordobaEntradasVentaContadoColumns[1]["Formato"] 		= "Number";
		$configTotalesCordobaEntradasVentaContadoColumns[1]["Colspan"] 		= 1;		
		$configTotalesCordobaEntradasVentaContadoColumns[1]["Width"] 		= $widthPage;
		
		$detailTotalesCordobaTotalesVentaContado[0]["total"] 				= ($resultadoVentaContadoCordoba["table"] === 0 ? 0 : $resultadoVentaContadoCordoba["configColumn"][8]["TotalValor"]);
		$detailTotalesCordobaTotalesVentaContado[0]["cantidad"] 			= ($resultadoVentaContadoCordoba["table"] === 0 ? 0 : $resultadoVentaContadoCordoba["configColumn"][8]["CantidadRegistro"]);
		$detailTotalesCordobaTotalesVentaContado[0]["prefix"] 				= "C$";
		
		$rosTotales = helper_reporteGeneralCreateTable(
				$detailTotalesCordobaTotalesVentaContado,
				$configTotalesCordobaEntradasVentaContadoColumns,
				'0',
				NULL
		);
		
		echo $rosTotales["table"];
		
		?>
		
		<br/>
		
		
		
		<?php 		
		
		$configTotalesCordobaVentasCreditoColumns[0]["Titulo"] 				= "Cordobas Prima";			
		$configTotalesCordobaVentasCreditoColumns[0]["FiledSouce"] 			= "total";
		$configTotalesCordobaVentasCreditoColumns[0]["Formato"] 			= "Number";
		$configTotalesCordobaVentasCreditoColumns[0]["Colspan"] 			= 1;		
		$configTotalesCordobaVentasCreditoColumns[0]["Width"] 				= "80px";
		$configTotalesCordobaVentasCreditoColumns[0]["FiledSoucePrefix"] 	= "prefix";
							 
		$configTotalesCordobaVentasCreditoColumns[1]["Titulo"] 		= "Cant";			
		$configTotalesCordobaVentasCreditoColumns[1]["FiledSouce"] 	= "cantidad";
		$configTotalesCordobaVentasCreditoColumns[1]["Formato"] 	= "Number";
		$configTotalesCordobaVentasCreditoColumns[1]["Colspan"] 	= 1;		
		$configTotalesCordobaVentasCreditoColumns[1]["Width"] 		= $widthPage;
		
		$detailTotalesCordobaTotalesVentaCredito[0]["total"] 				= ($resultadoVentaCreditoCordoba["table"] === 0 ? 0 : $resultadoVentaCreditoCordoba["configColumn"][8]["TotalValor"]);
		$detailTotalesCordobaTotalesVentaCredito[0]["cantidad"] 			= ($resultadoVentaCreditoCordoba["table"] === 0 ? 0 : $resultadoVentaCreditoCordoba["configColumn"][8]["CantidadRegistro"]);
		$detailTotalesCordobaTotalesVentaCredito[0]["prefix"] 				= "C$";
		
		$rosTotales = helper_reporteGeneralCreateTable(
				$detailTotalesCordobaTotalesVentaCredito,
				$configTotalesCordobaVentasCreditoColumns,
				'0',
				NULL
		);
		
		echo $rosTotales["table"];
		
		?>
		
		<br/>
		
		
		<?php 		
		
		$configTotalesCordobaOtrasEntradasContadoColumns[0]["Titulo"] 		= "Cordobas Entradas";			
		$configTotalesCordobaOtrasEntradasContadoColumns[0]["FiledSouce"] 	= "total";
		$configTotalesCordobaOtrasEntradasContadoColumns[0]["Formato"] 		= "Number";
		$configTotalesCordobaOtrasEntradasContadoColumns[0]["Colspan"] 		= 1;	
		$configTotalesCordobaOtrasEntradasContadoColumns[0]["Width"] 		= "80px";
		$configTotalesCordobaOtrasEntradasContadoColumns[0]["FiledSoucePrefix"] 	= "prefix";
		
		$configTotalesCordobaOtrasEntradasContadoColumns[1]["Titulo"] 		= "Cant";			
		$configTotalesCordobaOtrasEntradasContadoColumns[1]["FiledSouce"] 	= "cantidad";
		$configTotalesCordobaOtrasEntradasContadoColumns[1]["Formato"] 		= "Number";
		$configTotalesCordobaOtrasEntradasContadoColumns[1]["Colspan"] 		= 1;		
		$configTotalesCordobaOtrasEntradasContadoColumns[1]["Width"] 		= $widthPage;
		
		$detailTotalesCordobaTotalesOtrasEntradas[0]["total"] 				= ($resultadoIngresoCajaCordoba["table"] === 0 ? 0 : $resultadoIngresoCajaCordoba["configColumn"][8]["TotalValor"]);
		$detailTotalesCordobaTotalesOtrasEntradas[0]["cantidad"] 			= ($resultadoIngresoCajaCordoba["table"] === 0 ? 0 : $resultadoIngresoCajaCordoba["configColumn"][8]["CantidadRegistro"]);
		$detailTotalesCordobaTotalesOtrasEntradas[0]["prefix"] 				= "C$";
		
		$rosTotales = helper_reporteGeneralCreateTable(
				$detailTotalesCordobaTotalesOtrasEntradas,
				$configTotalesCordobaOtrasEntradasContadoColumns,
				'0',
				NULL
		);
		
		echo $rosTotales["table"];
		
		?>
		
		<br/>
		
		<?php 		
		
		$configTotalesColumnsSalidasCaja[0]["Titulo"]           = "Cordobas Salidas";
		$configTotalesColumnsSalidasCaja[0]["FiledSouce"]       = "total";
		$configTotalesColumnsSalidasCaja[0]["Formato"]          = "Number";
		$configTotalesColumnsSalidasCaja[0]["Colspan"]          = 1;
		$configTotalesColumnsSalidasCaja[0]["Width"]            = "80px";
		$configTotalesColumnsSalidasCaja[0]["FiledSoucePrefix"] = "prefix";

		
		$configTotalesColumnsSalidasCaja[1]["Titulo"] 		= "Cant";			
		$configTotalesColumnsSalidasCaja[1]["FiledSouce"] 	= "cantidad";
		$configTotalesColumnsSalidasCaja[1]["Formato"] 		= "Number";
		$configTotalesColumnsSalidasCaja[1]["Colspan"] 		= 1;		
		$configTotalesColumnsSalidasCaja[1]["Width"] 		= $widthPage;
		
		$detailTotalesSalidasCajas[0]["total"] 				= ($resultadoSalidaCajaCordoba["table"] === 0 ? 0 : $resultadoSalidaCajaCordoba["configColumn"][8]["TotalValor"]);
		$detailTotalesSalidasCajas[0]["cantidad"] 			= ($resultadoSalidaCajaCordoba["table"] === 0 ? 0 : $resultadoSalidaCajaCordoba["configColumn"][8]["CantidadRegistro"]);
		$detailTotalesSalidasCajas[0]["prefix"] 			= "C$";
		
		$rosTotales = helper_reporteGeneralCreateTable(
				$detailTotalesSalidasCajas,
				$configTotalesColumnsSalidasCaja,
				'0',
				NULL
		);
		
		echo $rosTotales["table"];
		
		?>
		
		<br/>
		
		
	
	
		<?php 		
		$widthX 	= "120px";
-		$columnX 	= 2;
		$configTotalesColumns[0]["Titulo"] 		= "Total Cordobas";
		$configTotalesColumns[0]["FiledSouce"] 	= "total";
		$configTotalesColumns[0]["Formato"] 	= "Number";
		$configTotalesColumns[0]["Colspan"] 	= $columnX;		
		$configTotalesColumns[0]["Width"] 		= $widthPage;
		$configTotalesColumns[0]["FiledSoucePrefix"] 	= "prefix";
		
		$detailTotales[0]["total"] 				= $totalCordoba;
		$detailTotales[0]["prefix"] 			= "C$";
		
		$rosTotales = helper_reporteGeneralCreateTable(
				$detailTotales,
				$configTotalesColumns,
				'0',
				NULL				
				
		);
		
		echo $rosTotales["table"];
		
		?>
		
		
		
		<br/>
	
		<?php 		
		
		$configTotalesColumns[0]["Titulo"] 		= "Dolares Abonos";			
		$configTotalesColumns[0]["FiledSouce"] 	= "total";
		$configTotalesColumns[0]["Formato"] 	= "Number";
		$configTotalesColumns[0]["Colspan"] 	= 1;
		$configTotalesColumns[0]["Width"] 		= "80px";
		$configTotalesColumns[0]["FiledSoucePrefix"] 	= "prefix";
		
		$configTotalesColumns[1]["Titulo"] 		= "Cant";			
		$configTotalesColumns[1]["FiledSouce"] 	= "cantidad";
		$configTotalesColumns[1]["Formato"] 	= "Number";
		$configTotalesColumns[1]["Colspan"] 	= 1;		
		$configTotalesColumns[1]["Width"] 		= $widthPage;
		
		$detailTotales[0]["total"] 				= ($resultadoAbonosDolar["table"] === 0 ? 0 : $resultadoAbonosDolar["configColumn"][8]["TotalValor"]);		
		$detailTotales[0]["cantidad"] 			= ($resultadoAbonosDolar["table"] === 0 ? 0 : $resultadoAbonosDolar["configColumn"][8]["CantidadRegistro"]);
		$detailTotales[0]["prefix"] 			= "$";
		
		$rosTotales = helper_reporteGeneralCreateTable(
				$detailTotales,
				$configTotalesColumns,
				'0',
				NULL,
				'68c778',
				'black'
		);
		
		echo $rosTotales["table"];
		
		?>
		
		<br/>
		
		<?php 		
		
		$configTotalesColumns[0]["Titulo"]           = "Dolares Ventas";
		$configTotalesColumns[0]["FiledSouce"]       = "total";
		$configTotalesColumns[0]["Formato"]          = "Number";
		$configTotalesColumns[0]["Colspan"]          = 1;
		$configTotalesColumns[0]["Width"]            = "80px";
		$configTotalesColumns[0]["FiledSoucePrefix"] = "prefix";

		
		$configTotalesColumns[1]["Titulo"] 		= "Cant";			
		$configTotalesColumns[1]["FiledSouce"] 	= "cantidad";
		$configTotalesColumns[1]["Formato"] 	= "Number";
		$configTotalesColumns[1]["Colspan"] 	= 1;		
		$configTotalesColumns[1]["Width"] 		= $widthPage;
		
		
		$detailTotales[0]["total"] 		= ($resultadoVentaContadoDolar["table"] === 0 ? 0 : $resultadoVentaContadoDolar["configColumn"][8]["TotalValor"]);
		$detailTotales[0]["cantidad"] 	= ($resultadoVentaContadoDolar["table"] === 0 ? 0 : $resultadoVentaContadoDolar["configColumn"][8]["CantidadRegistro"]);
		$detailTotales[0]["prefix"] 	= "$";
		
		$rosTotales = helper_reporteGeneralCreateTable(
				$detailTotales,
				$configTotalesColumns,
				'0',
				NULL,
				'68c778',
				'black'
		);
		
		echo $rosTotales["table"];
		
		?>
		
		<br/>
		
		
		<?php 		
		
		$configTotalesDolaresVentasCreditoColumns[0]["Titulo"] 				= "Dolares Prima";			
		$configTotalesDolaresVentasCreditoColumns[0]["FiledSouce"] 			= "total";
		$configTotalesDolaresVentasCreditoColumns[0]["Formato"] 			= "Number";
		$configTotalesDolaresVentasCreditoColumns[0]["Colspan"] 			= 1;		
		$configTotalesDolaresVentasCreditoColumns[0]["Width"] 				= "80px";
		$configTotalesDolaresVentasCreditoColumns[0]["FiledSoucePrefix"] 	= "prefix";
						
		$configTotalesDolaresVentasCreditoColumns[1]["Titulo"] 		= "Cant";			
		$configTotalesDolaresVentasCreditoColumns[1]["FiledSouce"] 	= "cantidad";
		$configTotalesDolaresVentasCreditoColumns[1]["Formato"] 	= "Number";
		$configTotalesDolaresVentasCreditoColumns[1]["Colspan"] 	= 1;		
		$configTotalesDolaresVentasCreditoColumns[1]["Width"] 		= $widthPage;
		
		$detailTotalesDolaresTotalesVentaCredito[0]["total"] 				= ($resultadoVentaCreditoDolar["table"] === 0 ? 0 : $resultadoVentaCreditoDolar["configColumn"][8]["TotalValor"]);
		$detailTotalesDolaresTotalesVentaCredito[0]["cantidad"] 			= ($resultadoVentaCreditoDolar["table"] === 0 ? 0 : $resultadoVentaCreditoDolar["configColumn"][8]["CantidadRegistro"]);
		$detailTotalesDolaresTotalesVentaCredito[0]["prefix"] 				= "$";
		
		$rosTotales = helper_reporteGeneralCreateTable(
				$detailTotalesDolaresTotalesVentaCredito,
				$configTotalesDolaresVentasCreditoColumns,
				'0',
				NULL,
				'68c778',
				'black'
		);
		
		echo $rosTotales["table"];
		
		?>
		
		<br/>
		
		

		<?php 		
		
		$configTotalesColumns[0]["Titulo"]           = "Dolares Entradas";
		$configTotalesColumns[0]["FiledSouce"]       = "total";
		$configTotalesColumns[0]["Formato"]          = "Number";
		$configTotalesColumns[0]["Colspan"]          = 1;
		$configTotalesColumns[0]["Width"]            = "80px";
		$configTotalesColumns[0]["FiledSoucePrefix"] = "prefix";

		$configTotalesColumns[1]["Titulo"]           = "Cant";
		$configTotalesColumns[1]["FiledSouce"]       = "cantidad";
		$configTotalesColumns[1]["Formato"]          = "Number";
		$configTotalesColumns[1]["Colspan"]          = 1;
		$configTotalesColumns[1]["Width"]            = $widthPage;

		
		
		$detailTotales[0]["total"] 		= ($resultadoIngresoCajaDolares["table"] === 0 ? 0 : $resultadoIngresoCajaDolares["configColumn"][8]["TotalValor"]);
		$detailTotales[0]["cantidad"] 	= ($resultadoIngresoCajaDolares["table"] === 0 ? 0 : $resultadoIngresoCajaDolares["configColumn"][8]["CantidadRegistro"]);
		$detailTotales[0]["prefix"] 	= "$";
		
		$rosTotales = helper_reporteGeneralCreateTable(
				$detailTotales,
				$configTotalesColumns,
				'0',
				NULL,
				'68c778',
				'black'
		);
		
		echo $rosTotales["table"];
		
		?>
		
		<br/>

		<?php 		
		
		$configTotalesColumns[0]["Titulo"]            = "Dolares Salidas";
		$configTotalesColumns[0]["FiledSouce"]        = "total";
		$configTotalesColumns[0]["Formato"]           = "Number";
		$configTotalesColumns[0]["Colspan"]           = 1;
		$configTotalesColumns[0]["Width"]             = "80px";
		$configTotalesColumns[0]["FiledSoucePrefix"]  = "prefix";

		$configTotalesColumns[1]["Titulo"]            = "Cant";
		$configTotalesColumns[1]["FiledSouce"]        = "cantidad";
		$configTotalesColumns[1]["Formato"]           = "Number";
		$configTotalesColumns[1]["Colspan"]           = 1;
		$configTotalesColumns[1]["Width"]             = $widthPage;

		
		
		
		
		$detailTotales[0]["total"] 		= ($resultadoSalidaCajaDolares["table"] === 0 ? 0 : $resultadoSalidaCajaDolares["configColumn"][8]["TotalValor"]);
		$detailTotales[0]["cantidad"] 	= ($resultadoSalidaCajaDolares["table"] === 0 ? 0 : $resultadoSalidaCajaDolares["configColumn"][8]["CantidadRegistro"]);
		$detailTotales[0]["prefix"] 	= "$";
		
		$rosTotales = helper_reporteGeneralCreateTable(
				$detailTotales,
				$configTotalesColumns,
				'0',
				NULL,
				'68c778',
				'black'
		);
		
		echo $rosTotales["table"];
		
		?>
		
	
		
		<br/>

		<?php 		
		
		$configTotalesColumnsTotalDolares[0]["Titulo"] 				= "Total Dolares";			
		$configTotalesColumnsTotalDolares[0]["FiledSouce"] 			= "total";
		$configTotalesColumnsTotalDolares[0]["Formato"] 			= "Number";
		$configTotalesColumnsTotalDolares[0]["Colspan"] 			= $columnX;		
		$configTotalesColumnsTotalDolares[0]["Width"] 				= $widthPage;
		$configTotalesColumnsTotalDolares[0]["FiledSoucePrefix"] 	= "prefix";
		
		$detailTotales[0]["total"] = $totalDolares;
		$detailTotales[0]["prefix"] = "$";
		
		$rosTotales = helper_reporteGeneralCreateTable(
				$detailTotales,
				$configTotalesColumnsTotalDolares,
				'0',
				NULL,
				'68c778',
				'black'
		);
		
		echo $rosTotales["table"];
		
		?>
		
		<br/>
		
		
		<?php 		
		
		$configTotalesColumnsTotalDolaresFirma[0]["Titulo"] 	= "Firma";			
		$configTotalesColumnsTotalDolaresFirma[0]["FiledSouce"] = "total";		
		$configTotalesColumnsTotalDolaresFirma[0]["Colspan"] 	= $columnX;		
		$configTotalesColumnsTotalDolaresFirma[0]["Width"] 		= $widthPage;
		
		
		$detailTotalesFirma[0]["total"] = "";
		$rosTotales = helper_reporteGeneralCreateTable(
				$detailTotalesFirma,
				$configTotalesColumnsTotalDolaresFirma,
				'0',
				NULL
		);
		
		echo $rosTotales["table"];
		
		?>
		
		<br/>
		
		
		<table style='width:100%'>
			<tr>
			  <td colspan='3' style='text-align:center'>
				<?php echo $objFirmaEncription; ?>
			  </td>
			</tr>			
		</table>
		
	</body>	
</html>