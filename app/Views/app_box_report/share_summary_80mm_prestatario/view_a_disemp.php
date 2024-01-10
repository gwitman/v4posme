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
                          margin-right:15px;
			}
			table
			{
			  font-size: x-small;
			  font-weight: bold;
			  font-family: Consolas, monaco, monospace;
			}
		</style>
		
	</head>
	<body style="font-family:monospace;font-size:smaller;margin:0px 0px 0px 0px"> 
		
		<?php
		$path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objLogo->value;
    
		$type    = pathinfo($path, PATHINFO_EXTENSION);
		$data    = file_get_contents($path);
		$base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);	
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
			$columnX 			= 2;
			$widthX 			= "235px";
			$totalCordoba		= 0;
			$totalDolares		= 0;
		?>
		
		
		<?php
		
		//wgonzalez-$configColumnVentaContado["0"]["Titulo"] 		= "Codigo";		
		//wgonzalez-$configColumnVentaContado["1"]["Titulo"] 		= "Cliente";		
		//wgonzalez-$configColumnVentaContado["2"]["Titulo"] 		= "Moneda";		
		//wgonzalez-$configColumnVentaContado["3"]["Titulo"] 		= "Fecha";		
		//wgonzalez-$configColumnVentaContado["4"]["Titulo"] 		= "Fac";		
		//wgonzalez-$configColumnVentaContado["5"]["Titulo"] 		= "Transaccion";		
		//wgonzalez-$configColumnVentaContado["6"]["Titulo"] 		= "Tran. Numero";		
		//wgonzalez-$configColumnVentaContado["7"]["Titulo"] 		= "Estado";		
		//wgonzalez-$configColumnVentaContado["8"]["Titulo"] 		= "Monto";		
		//wgonzalez-$configColumnVentaContado["9"]["Titulo"] 		= "Usuario";		
		//wgonzalez-$configColumnVentaContado["10"]["Titulo"] 		= "Nota";		
		//wgonzalez-			 
		//wgonzalez-$configColumnVentaContado["0"]["FiledSouce"] 		= "customerNumber";		
		//wgonzalez-$configColumnVentaContado["1"]["FiledSouce"] 		= "firstName";		
		//wgonzalez-$configColumnVentaContado["2"]["FiledSouce"] 		= "currencyName";		
		//wgonzalez-$configColumnVentaContado["3"]["FiledSouce"] 		= "transactionOn";		
		//wgonzalez-$configColumnVentaContado["4"]["FiledSouce"] 		= "transactionNumber";		
		//wgonzalez-$configColumnVentaContado["5"]["FiledSouce"] 		= "tipo";		
		//wgonzalez-$configColumnVentaContado["6"]["FiledSouce"] 		= "transactionNumber";		
		//wgonzalez-$configColumnVentaContado["7"]["FiledSouce"] 		= "statusName";		
		//wgonzalez-$configColumnVentaContado["8"]["FiledSouce"] 		= "totalDocument";		
		//wgonzalez-$configColumnVentaContado["9"]["FiledSouce"] 		= "nickname";		
		//wgonzalez-$configColumnVentaContado["10"]["FiledSouce"] 		= "";	
		//wgonzalez-
		//wgonzalez-$configColumnVentaContado["0"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaContado["1"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaContado["2"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaContado["3"]["Formato"] 		= "Date";		
		//wgonzalez-$configColumnVentaContado["4"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaContado["5"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaContado["6"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaContado["7"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaContado["8"]["Formato"] 		= "Number";		
		//wgonzalez-$configColumnVentaContado["9"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaContado["10"]["Formato"] 		= "";		
		//wgonzalez-
		//wgonzalez-$configColumnVentaContado["0"]["Width"] 		= "80px";		
		//wgonzalez-$configColumnVentaContado["1"]["Width"] 		= "220px";		
		//wgonzalez-$configColumnVentaContado["2"]["Width"] 		= "85px";		
		//wgonzalez-$configColumnVentaContado["3"]["Width"] 		= "80px";		
		//wgonzalez-$configColumnVentaContado["4"]["Width"] 		= "80px";		
		//wgonzalez-$configColumnVentaContado["5"]["Width"] 		= "200px";		
		//wgonzalez-$configColumnVentaContado["6"]["Width"] 		= "80px";		
		//wgonzalez-$configColumnVentaContado["7"]["Width"] 		= "80px";		
		//wgonzalez-$configColumnVentaContado["8"]["Width"] 		= "120px";		
		//wgonzalez-$configColumnVentaContado["9"]["Width"] 		= "120px";		
		//wgonzalez-$configColumnVentaContado["10"]["Width"] 		= "220px";			
		//wgonzalez-
		//wgonzalez-
		//wgonzalez-$configColumnVentaContado["0"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaContado["1"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaContado["2"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaContado["3"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaContado["4"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaContado["5"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaContado["6"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaContado["7"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaContado["8"]["Total"] 		= True;		
		//wgonzalez-$configColumnVentaContado["9"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaContado["10"]["Total"] 		= False;	
		//wgonzalez-
		//wgonzalez-
		//wgonzalez-$configColumnVentaContadoDolares["0"]["Titulo"] 		= "Codigo";		
		//wgonzalez-$configColumnVentaContadoDolares["1"]["Titulo"] 		= "Cliente";		
		//wgonzalez-$configColumnVentaContadoDolares["2"]["Titulo"] 		= "Moneda";		
		//wgonzalez-$configColumnVentaContadoDolares["3"]["Titulo"] 		= "Fecha";		
		//wgonzalez-$configColumnVentaContadoDolares["4"]["Titulo"] 		= "Fac";		
		//wgonzalez-$configColumnVentaContadoDolares["5"]["Titulo"] 		= "Transaccion";		
		//wgonzalez-$configColumnVentaContadoDolares["6"]["Titulo"] 		= "Tran. Numero";		
		//wgonzalez-$configColumnVentaContadoDolares["7"]["Titulo"] 		= "Estado";		
		//wgonzalez-$configColumnVentaContadoDolares["8"]["Titulo"] 		= "Monto";		
		//wgonzalez-$configColumnVentaContadoDolares["9"]["Titulo"] 		= "Usuario";		
		//wgonzalez-$configColumnVentaContadoDolares["10"]["Titulo"] 		= "Nota";		
		//wgonzalez-						 
		//wgonzalez-$configColumnVentaContadoDolares["0"]["FiledSouce"] 		= "customerNumber";		
		//wgonzalez-$configColumnVentaContadoDolares["1"]["FiledSouce"] 		= "firstName";		
		//wgonzalez-$configColumnVentaContadoDolares["2"]["FiledSouce"] 		= "currencyName";		
		//wgonzalez-$configColumnVentaContadoDolares["3"]["FiledSouce"] 		= "transactionOn";		
		//wgonzalez-$configColumnVentaContadoDolares["4"]["FiledSouce"] 		= "transactionNumber";		
		//wgonzalez-$configColumnVentaContadoDolares["5"]["FiledSouce"] 		= "tipo";		
		//wgonzalez-$configColumnVentaContadoDolares["6"]["FiledSouce"] 		= "transactionNumber";		
		//wgonzalez-$configColumnVentaContadoDolares["7"]["FiledSouce"] 		= "statusName";		
		//wgonzalez-$configColumnVentaContadoDolares["8"]["FiledSouce"] 		= "totalDocument";		
		//wgonzalez-$configColumnVentaContadoDolares["9"]["FiledSouce"] 		= "nickname";		
		//wgonzalez-$configColumnVentaContadoDolares["10"]["FiledSouce"] 		= "";	
		//wgonzalez-						 
		//wgonzalez-$configColumnVentaContadoDolares["0"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaContadoDolares["1"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaContadoDolares["2"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaContadoDolares["3"]["Formato"] 		= "Date";		
		//wgonzalez-$configColumnVentaContadoDolares["4"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaContadoDolares["5"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaContadoDolares["6"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaContadoDolares["7"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaContadoDolares["8"]["Formato"] 		= "Number";		
		//wgonzalez-$configColumnVentaContadoDolares["9"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaContadoDolares["10"]["Formato"] 		= "";		
		//wgonzalez-						 
		//wgonzalez-$configColumnVentaContadoDolares["0"]["Width"] 		= "80px";		
		//wgonzalez-$configColumnVentaContadoDolares["1"]["Width"] 		= "220px";		
		//wgonzalez-$configColumnVentaContadoDolares["2"]["Width"] 		= "85px";		
		//wgonzalez-$configColumnVentaContadoDolares["3"]["Width"] 		= "80px";		
		//wgonzalez-$configColumnVentaContadoDolares["4"]["Width"] 		= "80px";		
		//wgonzalez-$configColumnVentaContadoDolares["5"]["Width"] 		= "200px";		
		//wgonzalez-$configColumnVentaContadoDolares["6"]["Width"] 		= "80px";		
		//wgonzalez-$configColumnVentaContadoDolares["7"]["Width"] 		= "80px";		
		//wgonzalez-$configColumnVentaContadoDolares["8"]["Width"] 		= "120px";		
		//wgonzalez-$configColumnVentaContadoDolares["9"]["Width"] 		= "120px";		
		//wgonzalez-$configColumnVentaContadoDolares["10"]["Width"] 		= "220px";			
		//wgonzalez-						 
		//wgonzalez-						 
		//wgonzalez-$configColumnVentaContadoDolares["0"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaContadoDolares["1"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaContadoDolares["2"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaContadoDolares["3"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaContadoDolares["4"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaContadoDolares["5"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaContadoDolares["6"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaContadoDolares["7"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaContadoDolares["8"]["Total"] 		= True;		
		//wgonzalez-$configColumnVentaContadoDolares["9"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaContadoDolares["10"]["Total"] 		= False;	
		//wgonzalez-
		//wgonzalez-
		//wgonzalez-$objSalesDolar 		= $objSales;
		//wgonzalez-$objSalesCordoba 	= $objSales;
		//wgonzalez-
		//wgonzalez-
		//wgonzalez-if($objSalesCordoba != null)
		//wgonzalez-$objSalesCordoba = array_filter($objSalesCordoba,function($var){
		//wgonzalez-	if (
		//wgonzalez-		strtoupper($var["currencyName"]) == "CORDOBA" && 
		//wgonzalez-		strtoupper($var["tipo"]) == "CONTADO" 
		//wgonzalez-	)
		//wgonzalez-	return true;
		//wgonzalez-	
		//wgonzalez-});
		//wgonzalez-
		//wgonzalez-if($objSalesDolar != null)
		//wgonzalez-$objSalesDolar = array_filter($objSalesDolar,function($var){ 
		//wgonzalez-	if (
		//wgonzalez-		strtoupper($var["currencyName"]) == "DOLAR" && 
		//wgonzalez-		strtoupper($var["tipo"]) == "CONTADO" 
		//wgonzalez-	)
		//wgonzalez-	return true;
		//wgonzalez-	
		//wgonzalez-});
		//wgonzalez-
		//wgonzalez-$resultadoVentaContadoCordoba = helper_reporteGeneralCreateTable(
		//wgonzalez-	$objSalesCordoba,
		//wgonzalez-	$configColumnVentaContado,
		//wgonzalez-	'0px',
		//wgonzalez-	'CORDOBA = VENTAS DE CONTADO'
		//wgonzalez-);
		//wgonzalez-
		//wgonzalez-$resultadoVentaContadoDolar = helper_reporteGeneralCreateTable(
		//wgonzalez-	$objSalesDolar,
		//wgonzalez-	$configColumnVentaContadoDolares,
		//wgonzalez-	'0px',
		//wgonzalez-	'DOLARES = VENTAS DE CONTADO',
		//wgonzalez-	'68c778',
		//wgonzalez-	'black'
		//wgonzalez-);
		//wgonzalez-
		//wgonzalez-$totalCordoba = $totalCordoba + ($resultadoVentaContadoCordoba["table"] === 0 ? 0 : $resultadoVentaContadoCordoba["configColumn"][8]["TotalValor"]);
		//wgonzalez-$totalDolares = $totalDolares + ($resultadoVentaContadoDolar["table"] === 0 ? 0 : $resultadoVentaContadoDolar["configColumn"][8]["TotalValor"]);
		
		?>
		
		
		
		<?php
		
		//wgonzalez-$configColumnVentaCredito["0"]["Titulo"] 		= "Codigo";		
		//wgonzalez-$configColumnVentaCredito["1"]["Titulo"] 		= "Cliente";		
		//wgonzalez-$configColumnVentaCredito["2"]["Titulo"] 		= "Moneda";		
		//wgonzalez-$configColumnVentaCredito["3"]["Titulo"] 		= "Fecha";		
		//wgonzalez-$configColumnVentaCredito["4"]["Titulo"] 		= "Fac";		
		//wgonzalez-$configColumnVentaCredito["5"]["Titulo"] 		= "Transaccion";		
		//wgonzalez-$configColumnVentaCredito["6"]["Titulo"] 		= "Tran. Numero";		
		//wgonzalez-$configColumnVentaCredito["7"]["Titulo"] 		= "Estado";		
		//wgonzalez-$configColumnVentaCredito["8"]["Titulo"] 		= "Monto";		
		//wgonzalez-$configColumnVentaCredito["9"]["Titulo"] 		= "Usuario";		
		//wgonzalez-$configColumnVentaCredito["10"]["Titulo"] 		= "Nota";		
		//wgonzalez-			 
		//wgonzalez-$configColumnVentaCredito["0"]["FiledSouce"] 		= "customerNumber";		
		//wgonzalez-$configColumnVentaCredito["1"]["FiledSouce"] 		= "firstName";		
		//wgonzalez-$configColumnVentaCredito["2"]["FiledSouce"] 		= "currencyName";		
		//wgonzalez-$configColumnVentaCredito["3"]["FiledSouce"] 		= "transactionOn";		
		//wgonzalez-$configColumnVentaCredito["4"]["FiledSouce"] 		= "transactionNumber";		
		//wgonzalez-$configColumnVentaCredito["5"]["FiledSouce"] 		= "tipo";		
		//wgonzalez-$configColumnVentaCredito["6"]["FiledSouce"] 		= "transactionNumber";		
		//wgonzalez-$configColumnVentaCredito["7"]["FiledSouce"] 		= "statusName";		
		//wgonzalez-$configColumnVentaCredito["8"]["FiledSouce"] 		= "receiptAmount";		
		//wgonzalez-$configColumnVentaCredito["9"]["FiledSouce"] 		= "nickname";		
		//wgonzalez-$configColumnVentaCredito["10"]["FiledSouce"] 		= "";	
		//wgonzalez-
		//wgonzalez-$configColumnVentaCredito["0"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaCredito["1"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaCredito["2"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaCredito["3"]["Formato"] 		= "Date";		
		//wgonzalez-$configColumnVentaCredito["4"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaCredito["5"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaCredito["6"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaCredito["7"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaCredito["8"]["Formato"] 		= "Number";		
		//wgonzalez-$configColumnVentaCredito["9"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaCredito["10"]["Formato"] 		= "";		
		//wgonzalez-
		//wgonzalez-$configColumnVentaCredito["0"]["Width"] 		= "80px";		
		//wgonzalez-$configColumnVentaCredito["1"]["Width"] 		= "220px";		
		//wgonzalez-$configColumnVentaCredito["2"]["Width"] 		= "85px";		
		//wgonzalez-$configColumnVentaCredito["3"]["Width"] 		= "80px";		
		//wgonzalez-$configColumnVentaCredito["4"]["Width"] 		= "80px";		
		//wgonzalez-$configColumnVentaCredito["5"]["Width"] 		= "200px";		
		//wgonzalez-$configColumnVentaCredito["6"]["Width"] 		= "80px";		
		//wgonzalez-$configColumnVentaCredito["7"]["Width"] 		= "80px";		
		//wgonzalez-$configColumnVentaCredito["8"]["Width"] 		= "120px";		
		//wgonzalez-$configColumnVentaCredito["9"]["Width"] 		= "120px";		
		//wgonzalez-$configColumnVentaCredito["10"]["Width"] 		= "220px";			
		//wgonzalez-				  
		//wgonzalez-$configColumnVentaCredito["0"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaCredito["1"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaCredito["2"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaCredito["3"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaCredito["4"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaCredito["5"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaCredito["6"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaCredito["7"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaCredito["8"]["Total"] 		= True;		
		//wgonzalez-$configColumnVentaCredito["9"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaCredito["10"]["Total"] 		= False;	
		//wgonzalez-
		//wgonzalez-
		//wgonzalez-$configColumnVentaCreditoDolares["0"]["Titulo"] 		= "Codigo";		
		//wgonzalez-$configColumnVentaCreditoDolares["1"]["Titulo"] 		= "Cliente";		
		//wgonzalez-$configColumnVentaCreditoDolares["2"]["Titulo"] 		= "Moneda";		
		//wgonzalez-$configColumnVentaCreditoDolares["3"]["Titulo"] 		= "Fecha";		
		//wgonzalez-$configColumnVentaCreditoDolares["4"]["Titulo"] 		= "Fac";		
		//wgonzalez-$configColumnVentaCreditoDolares["5"]["Titulo"] 		= "Transaccion";		
		//wgonzalez-$configColumnVentaCreditoDolares["6"]["Titulo"] 		= "Tran. Numero";		
		//wgonzalez-$configColumnVentaCreditoDolares["7"]["Titulo"] 		= "Estado";		
		//wgonzalez-$configColumnVentaCreditoDolares["8"]["Titulo"] 		= "Monto";		
		//wgonzalez-$configColumnVentaCreditoDolares["9"]["Titulo"] 		= "Usuario";		
		//wgonzalez-$configColumnVentaCreditoDolares["10"]["Titulo"] 		= "Nota";		
		//wgonzalez-					
		//wgonzalez-$configColumnVentaCreditoDolares["0"]["FiledSouce"] 		= "customerNumber";		
		//wgonzalez-$configColumnVentaCreditoDolares["1"]["FiledSouce"] 		= "firstName";		
		//wgonzalez-$configColumnVentaCreditoDolares["2"]["FiledSouce"] 		= "currencyName";		
		//wgonzalez-$configColumnVentaCreditoDolares["3"]["FiledSouce"] 		= "transactionOn";		
		//wgonzalez-$configColumnVentaCreditoDolares["4"]["FiledSouce"] 		= "transactionNumber";		
		//wgonzalez-$configColumnVentaCreditoDolares["5"]["FiledSouce"] 		= "tipo";		
		//wgonzalez-$configColumnVentaCreditoDolares["6"]["FiledSouce"] 		= "transactionNumber";		
		//wgonzalez-$configColumnVentaCreditoDolares["7"]["FiledSouce"] 		= "statusName";		
		//wgonzalez-$configColumnVentaCreditoDolares["8"]["FiledSouce"] 		= "receiptAmount";		
		//wgonzalez-$configColumnVentaCreditoDolares["9"]["FiledSouce"] 		= "nickname";		
		//wgonzalez-$configColumnVentaCreditoDolares["10"]["FiledSouce"] 		= "";	
		//wgonzalez-					
		//wgonzalez-$configColumnVentaCreditoDolares["0"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaCreditoDolares["1"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaCreditoDolares["2"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaCreditoDolares["3"]["Formato"] 		= "Date";		
		//wgonzalez-$configColumnVentaCreditoDolares["4"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaCreditoDolares["5"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaCreditoDolares["6"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaCreditoDolares["7"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaCreditoDolares["8"]["Formato"] 		= "Number";		
		//wgonzalez-$configColumnVentaCreditoDolares["9"]["Formato"] 		= "";		
		//wgonzalez-$configColumnVentaCreditoDolares["10"]["Formato"] 		= "";		
		//wgonzalez-					
		//wgonzalez-$configColumnVentaCreditoDolares["0"]["Width"] 		= "80px";		
		//wgonzalez-$configColumnVentaCreditoDolares["1"]["Width"] 		= "220px";		
		//wgonzalez-$configColumnVentaCreditoDolares["2"]["Width"] 		= "85px";		
		//wgonzalez-$configColumnVentaCreditoDolares["3"]["Width"] 		= "80px";		
		//wgonzalez-$configColumnVentaCreditoDolares["4"]["Width"] 		= "80px";		
		//wgonzalez-$configColumnVentaCreditoDolares["5"]["Width"] 		= "200px";		
		//wgonzalez-$configColumnVentaCreditoDolares["6"]["Width"] 		= "80px";		
		//wgonzalez-$configColumnVentaCreditoDolares["7"]["Width"] 		= "80px";		
		//wgonzalez-$configColumnVentaCreditoDolares["8"]["Width"] 		= "120px";		
		//wgonzalez-$configColumnVentaCreditoDolares["9"]["Width"] 		= "120px";		
		//wgonzalez-$configColumnVentaCreditoDolares["10"]["Width"] 	= "220px";			
		//wgonzalez-						 
		//wgonzalez-						 
		//wgonzalez-$configColumnVentaCreditoDolares["0"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaCreditoDolares["1"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaCreditoDolares["2"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaCreditoDolares["3"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaCreditoDolares["4"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaCreditoDolares["5"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaCreditoDolares["6"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaCreditoDolares["7"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaCreditoDolares["8"]["Total"] 		= True;		
		//wgonzalez-$configColumnVentaCreditoDolares["9"]["Total"] 		= False;		
		//wgonzalez-$configColumnVentaCreditoDolares["10"]["Total"] 	= False;	
		//wgonzalez-
		//wgonzalez-
		//wgonzalez-$objSalesCreditoDolar 		= $objSalesCredito;
		//wgonzalez-$objSalesCreditoCordoba 	= $objSalesCredito;
		//wgonzalez-
		//wgonzalez-if($objSalesCreditoCordoba != null)
		//wgonzalez-$objSalesCreditoCordoba = array_filter($objSalesCreditoCordoba,function($var){
		//wgonzalez-	
		//wgonzalez-	if (
		//wgonzalez-		strtoupper($var["currencyName"]) == "CORDOBA" && 
		//wgonzalez-		strtoupper($var["tipo"]) == "CREDITO"
		//wgonzalez-	)
		//wgonzalez-		return true;
		//wgonzalez-});
		//wgonzalez-
		//wgonzalez-if($objSalesCreditoDolar != null)
		//wgonzalez-$objSalesCreditoDolar = array_filter($objSalesCreditoDolar,function($var){
		//wgonzalez-	if (
		//wgonzalez-		strtoupper($var["currencyName"]) == "DOLAR" && 
		//wgonzalez-		strtoupper($var["tipo"]) == "CREDITO"
		//wgonzalez-	)
		//wgonzalez-		return true;
		//wgonzalez-});
		//wgonzalez-
		//wgonzalez-$resultadoVentaCreditoCordoba = helper_reporteGeneralCreateTable(
		//wgonzalez-	$objSalesCreditoCordoba,
		//wgonzalez-	$configColumnVentaCredito,
		//wgonzalez-	'0px',
		//wgonzalez-	'CORDOBA = CREDITO PRIMA'
		//wgonzalez-);
		//wgonzalez-
		//wgonzalez-$resultadoVentaCreditoDolar = helper_reporteGeneralCreateTable(
		//wgonzalez-	$objSalesCreditoDolar,
		//wgonzalez-	$configColumnVentaCreditoDolares,
		//wgonzalez-	'0px',
		//wgonzalez-	'DOLARES = CREDITO PRIMA',
		//wgonzalez-	'68c778',
		//wgonzalez-	'black'
		//wgonzalez-);
		//wgonzalez-
		//wgonzalez-$totalCordoba = $totalCordoba + ($resultadoVentaCreditoCordoba["table"] === 0 ? 0 : $resultadoVentaCreditoCordoba["configColumn"][8]["TotalValor"]);
		//wgonzalez-$totalDolares = $totalDolares + ($resultadoVentaCreditoDolar["table"] === 0 ? 0 : $resultadoVentaCreditoDolar["configColumn"][8]["TotalValor"]);
		
		?>
		
		
		<?php			
		$configColumnAbonos["1"]["Titulo"] 		= "Cliente";
		$configColumnAbonos["8"]["Titulo"] 		= "Abono";		
		
		$configColumnAbonos["1"]["FiledSouce"] 		= "firstName";				
		$configColumnAbonos["8"]["FiledSouce"] 		= "montoFac";		
		
		$configColumnAbonos["1"]["Formato"] 		= "";				
		$configColumnAbonos["8"]["Formato"] 		= "Number";		
		
		$configColumnAbonos["1"]["Width"] 		= "80px";		
		$configColumnAbonos["8"]["Width"] 		= "20px";				
		
		$configColumnAbonos["1"]["Total"] 		= False;				
		$configColumnAbonos["8"]["Total"] 		= False;		
	
		
		
		$configColumnAbonosDolares["1"]["Titulo"] 		= "Cliente";				
		$configColumnAbonosDolares["8"]["Titulo"] 		= "Monto";								   
		
		$configColumnAbonosDolares["1"]["FiledSouce"] 		= "firstName";	
		$configColumnAbonosDolares["8"]["FiledSouce"] 		= "montoFac";							   
		
		$configColumnAbonosDolares["1"]["Formato"] 		= "";		
		$configColumnAbonosDolares["8"]["Formato"] 		= "Number";								   
		
		$configColumnAbonosDolares["1"]["Width"] 		= "80px";		
		$configColumnAbonosDolares["8"]["Width"] 		= "20px";								   
		
		$configColumnAbonosDolares["1"]["Total"] 		= False;		
		$configColumnAbonosDolares["8"]["Total"] 		= False;		
		
		
		$objDetailAbonosDolar 		= $objAbonos;
		$objDetailAbonosCordoba 	= $objAbonos;
		
		if($objDetailAbonosCordoba != null)
		$objDetailAbonosCordoba 	= array_filter($objDetailAbonosCordoba,function($var){
			if (strtoupper($var["moneda"]) == "CORDOBA")
				return true;
		});
		
		if($objDetailAbonosDolar != null)
		$objDetailAbonosDolar 	= array_filter($objDetailAbonosDolar,function($var){
			if (strtoupper($var["moneda"]) == "DOLAR")
				return true;
		});
		
		
		$resultadoAbonosCordoba = helper_reporteGeneralCreateTable(
			$objDetailAbonosCordoba,
			$configColumnAbonos,
			'0px',
			"C$ Abonos"
		);
		
		$resultadoAbonosDolar = helper_reporteGeneralCreateTable(
			$objDetailAbonosDolar,
			$configColumnAbonosDolares,
			'0px',
			"$ Abonos",
			'68c778',
			'black'
		);
		
		$totalCordoba = 0 + ($resultadoAbonosCordoba["table"] === 0 ? 0 : array_sum(array_column($objDetailAbonosCordoba, 'montoFac'))  );
		$totalDolares = 0 + ($resultadoAbonosDolar["table"] === 0 ? 0 : array_sum(array_column($objDetailAbonosDolar, 'montoFac'))  );
		echo $resultadoAbonosCordoba["table"];
		
		
		
		$configTotalesFirma[0]["Titulo"] 		= "Total C$ Abonos";			
		$configTotalesFirma[0]["FiledSouce"] 	= "total";		
		$configTotalesFirma[0]["Colspan"] 		= $columnX;		
		$configTotalesFirma[0]["Width"] 		= $widthX;
		$configTotalesFirma[0]["Alineacion"] 	= "Right";
		$configTotalesFirma[0]["Formato"] 		= "";				
		$detailTotalesFirma[0]["total"] 		= number_format($totalCordoba , 2, '.', ',');
		
		$rosTotales = helper_reporteGeneralCreateTable(
				$detailTotalesFirma,
				$configTotalesFirma,
				'0',
				NULL
		);		
		echo $rosTotales["table"];
		
		
		?>
		
		
		
		
		
		<?php
		
		//wgonzalez-configColumnIngresoCaja["0"]["Titulo"] 		= "Codigo";		
		//wgonzalez-configColumnIngresoCaja["1"]["Titulo"] 		= "Cliente";		
		//wgonzalez-configColumnIngresoCaja["2"]["Titulo"] 		= "Moneda";		
		//wgonzalez-configColumnIngresoCaja["3"]["Titulo"] 		= "Fecha";		
		//wgonzalez-configColumnIngresoCaja["4"]["Titulo"] 		= "Fac";		
		//wgonzalez-configColumnIngresoCaja["5"]["Titulo"] 		= "Transaccion";		
		//wgonzalez-configColumnIngresoCaja["6"]["Titulo"] 		= "Tran. Numero";		
		//wgonzalez-configColumnIngresoCaja["7"]["Titulo"] 		= "Estado";		
		//wgonzalez-configColumnIngresoCaja["8"]["Titulo"] 		= "Monto";		
		//wgonzalez-configColumnIngresoCaja["9"]["Titulo"] 		= "Usuario";		
		//wgonzalez-configColumnIngresoCaja["10"]["Titulo"] 		= "Nota";		
		//wgonzalez-			 
		//wgonzalez-configColumnIngresoCaja["0"]["FiledSouce"] 		= "transactionNumber";		
		//wgonzalez-configColumnIngresoCaja["1"]["FiledSouce"] 		= "transactionNumber";		
		//wgonzalez-configColumnIngresoCaja["2"]["FiledSouce"] 		= "moneda";		
		//wgonzalez-configColumnIngresoCaja["3"]["FiledSouce"] 		= "transactionOn";		
		//wgonzalez-configColumnIngresoCaja["4"]["FiledSouce"] 		= "transactionNumber";		
		//wgonzalez-configColumnIngresoCaja["5"]["FiledSouce"] 		= "transactionName";		
		//wgonzalez-configColumnIngresoCaja["6"]["FiledSouce"] 		= "transactionNumber";		
		//wgonzalez-configColumnIngresoCaja["7"]["FiledSouce"] 		= "estado";		
		//wgonzalez-configColumnIngresoCaja["8"]["FiledSouce"] 		= "montoTransaccion";		
		//wgonzalez-configColumnIngresoCaja["9"]["FiledSouce"] 		= "nickname";		
		//wgonzalez-configColumnIngresoCaja["10"]["FiledSouce"] 		= "note";	
		//wgonzalez-
		//wgonzalez-
		//wgonzalez-configColumnIngresoCaja["0"]["Formato"] 		= "";		
		//wgonzalez-configColumnIngresoCaja["1"]["Formato"] 		= "";		
		//wgonzalez-configColumnIngresoCaja["2"]["Formato"] 		= "";		
		//wgonzalez-configColumnIngresoCaja["3"]["Formato"] 		= "Date";		
		//wgonzalez-configColumnIngresoCaja["4"]["Formato"] 		= "";		
		//wgonzalez-configColumnIngresoCaja["5"]["Formato"] 		= "";		
		//wgonzalez-configColumnIngresoCaja["6"]["Formato"] 		= "";		
		//wgonzalez-configColumnIngresoCaja["7"]["Formato"] 		= "";		
		//wgonzalez-configColumnIngresoCaja["8"]["Formato"] 		= "Number";		
		//wgonzalez-configColumnIngresoCaja["9"]["Formato"] 		= "";		
		//wgonzalez-configColumnIngresoCaja["10"]["Formato"] 		= "";	
		//wgonzalez-
		//wgonzalez-configColumnIngresoCaja["0"]["Width"] 		= "80px";		
		//wgonzalez-configColumnIngresoCaja["1"]["Width"] 		= "220px";		
		//wgonzalez-configColumnIngresoCaja["2"]["Width"] 		= "85px";		
		//wgonzalez-configColumnIngresoCaja["3"]["Width"] 		= "80px";		
		//wgonzalez-configColumnIngresoCaja["4"]["Width"] 		= "80px";		
		//wgonzalez-configColumnIngresoCaja["5"]["Width"] 		= "200px";		
		//wgonzalez-configColumnIngresoCaja["6"]["Width"] 		= "80px";		
		//wgonzalez-configColumnIngresoCaja["7"]["Width"] 		= "80px";		
		//wgonzalez-configColumnIngresoCaja["8"]["Width"] 		= "120px";		
		//wgonzalez-configColumnIngresoCaja["9"]["Width"] 		= "120px";		
		//wgonzalez-configColumnIngresoCaja["10"]["Width"] 	= "220px";				
		//wgonzalez-
		//wgonzalez-configColumnIngresoCaja["0"]["Total"] 		= False;		
		//wgonzalez-configColumnIngresoCaja["1"]["Total"] 		= False;		
		//wgonzalez-configColumnIngresoCaja["2"]["Total"] 		= False;		
		//wgonzalez-configColumnIngresoCaja["3"]["Total"] 		= False;		
		//wgonzalez-configColumnIngresoCaja["4"]["Total"] 		= False;		
		//wgonzalez-configColumnIngresoCaja["5"]["Total"] 		= False;		
		//wgonzalez-configColumnIngresoCaja["6"]["Total"] 		= False;		
		//wgonzalez-configColumnIngresoCaja["7"]["Total"] 		= False;		
		//wgonzalez-configColumnIngresoCaja["8"]["Total"] 		= True;		
		//wgonzalez-configColumnIngresoCaja["9"]["Total"] 		= False;		
		//wgonzalez-configColumnIngresoCaja["10"]["Total"] 		= False;	
		//wgonzalez-
		//wgonzalez-
		//wgonzalez-***********************************/
		//wgonzalez-configColumnIngresoCajaDolares["0"]["Titulo"] 		= "Codigo";		
		//wgonzalez-configColumnIngresoCajaDolares["1"]["Titulo"] 		= "Cliente";		
		//wgonzalez-configColumnIngresoCajaDolares["2"]["Titulo"] 		= "Moneda";		
		//wgonzalez-configColumnIngresoCajaDolares["3"]["Titulo"] 		= "Fecha";		
		//wgonzalez-configColumnIngresoCajaDolares["4"]["Titulo"] 		= "Fac";		
		//wgonzalez-configColumnIngresoCajaDolares["5"]["Titulo"] 		= "Transaccion";		
		//wgonzalez-configColumnIngresoCajaDolares["6"]["Titulo"] 		= "Tran. Numero";		
		//wgonzalez-configColumnIngresoCajaDolares["7"]["Titulo"] 		= "Estado";		
		//wgonzalez-configColumnIngresoCajaDolares["8"]["Titulo"] 		= "Monto";		
		//wgonzalez-configColumnIngresoCajaDolares["9"]["Titulo"] 		= "Usuario";		
		//wgonzalez-configColumnIngresoCajaDolares["10"]["Titulo"] 		= "Nota";		
		//wgonzalez-						
		//wgonzalez-configColumnIngresoCajaDolares["0"]["FiledSouce"] 		= "transactionNumber";		
		//wgonzalez-configColumnIngresoCajaDolares["1"]["FiledSouce"] 		= "transactionNumber";		
		//wgonzalez-configColumnIngresoCajaDolares["2"]["FiledSouce"] 		= "moneda";		
		//wgonzalez-configColumnIngresoCajaDolares["3"]["FiledSouce"] 		= "transactionOn";		
		//wgonzalez-configColumnIngresoCajaDolares["4"]["FiledSouce"] 		= "transactionNumber";		
		//wgonzalez-configColumnIngresoCajaDolares["5"]["FiledSouce"] 		= "transactionName";		
		//wgonzalez-configColumnIngresoCajaDolares["6"]["FiledSouce"] 		= "transactionNumber";		
		//wgonzalez-configColumnIngresoCajaDolares["7"]["FiledSouce"] 		= "estado";		
		//wgonzalez-configColumnIngresoCajaDolares["8"]["FiledSouce"] 		= "montoTransaccion";		
		//wgonzalez-configColumnIngresoCajaDolares["9"]["FiledSouce"] 		= "nickname";		
		//wgonzalez-configColumnIngresoCajaDolares["10"]["FiledSouce"] 		= "note";	
		//wgonzalez-						
		//wgonzalez-						
		//wgonzalez-configColumnIngresoCajaDolares["0"]["Formato"] 		= "";		
		//wgonzalez-configColumnIngresoCajaDolares["1"]["Formato"] 		= "";		
		//wgonzalez-configColumnIngresoCajaDolares["2"]["Formato"] 		= "";		
		//wgonzalez-configColumnIngresoCajaDolares["3"]["Formato"] 		= "Date";		
		//wgonzalez-configColumnIngresoCajaDolares["4"]["Formato"] 		= "";		
		//wgonzalez-configColumnIngresoCajaDolares["5"]["Formato"] 		= "";		
		//wgonzalez-configColumnIngresoCajaDolares["6"]["Formato"] 		= "";		
		//wgonzalez-configColumnIngresoCajaDolares["7"]["Formato"] 		= "";		
		//wgonzalez-configColumnIngresoCajaDolares["8"]["Formato"] 		= "Number";		
		//wgonzalez-configColumnIngresoCajaDolares["9"]["Formato"] 		= "";		
		//wgonzalez-configColumnIngresoCajaDolares["10"]["Formato"] 		= "";	
		//wgonzalez-						
		//wgonzalez-configColumnIngresoCajaDolares["0"]["Width"] 		= "80px";		
		//wgonzalez-configColumnIngresoCajaDolares["1"]["Width"] 		= "220px";		
		//wgonzalez-configColumnIngresoCajaDolares["2"]["Width"] 		= "85px";		
		//wgonzalez-configColumnIngresoCajaDolares["3"]["Width"] 		= "80px";		
		//wgonzalez-configColumnIngresoCajaDolares["4"]["Width"] 		= "80px";		
		//wgonzalez-configColumnIngresoCajaDolares["5"]["Width"] 		= "200px";		
		//wgonzalez-configColumnIngresoCajaDolares["6"]["Width"] 		= "80px";		
		//wgonzalez-configColumnIngresoCajaDolares["7"]["Width"] 		= "80px";		
		//wgonzalez-configColumnIngresoCajaDolares["8"]["Width"] 		= "120px";		
		//wgonzalez-configColumnIngresoCajaDolares["9"]["Width"] 		= "120px";		
		//wgonzalez-configColumnIngresoCajaDolares["10"]["Width"] 	= "220px";				
		//wgonzalez-						
		//wgonzalez-configColumnIngresoCajaDolares["0"]["Total"] 		= False;		
		//wgonzalez-configColumnIngresoCajaDolares["1"]["Total"] 		= False;		
		//wgonzalez-configColumnIngresoCajaDolares["2"]["Total"] 		= False;		
		//wgonzalez-configColumnIngresoCajaDolares["3"]["Total"] 		= False;		
		//wgonzalez-configColumnIngresoCajaDolares["4"]["Total"] 		= False;		
		//wgonzalez-configColumnIngresoCajaDolares["5"]["Total"] 		= False;		
		//wgonzalez-configColumnIngresoCajaDolares["6"]["Total"] 		= False;		
		//wgonzalez-configColumnIngresoCajaDolares["7"]["Total"] 		= False;		
		//wgonzalez-configColumnIngresoCajaDolares["8"]["Total"] 		= True;		
		//wgonzalez-configColumnIngresoCajaDolares["9"]["Total"] 		= False;		
		//wgonzalez-configColumnIngresoCajaDolares["10"]["Total"] 		= False;	
		//wgonzalez-
		//wgonzalez-
		//wgonzalez-objCashDolar 	= $objCash;
		//wgonzalez-objCashCordoba = $objCash;
		//wgonzalez-
		//wgonzalez-f($objCashCordoba != null)
		//wgonzalez-objCashCordoba = array_filter($objCashCordoba,function($var){
		//wgonzalez-	if (strtoupper($var["moneda"]) == "CORDOBA")
		//wgonzalez-		return true;
		//wgonzalez-);
		//wgonzalez-
		//wgonzalez-f($objCashDolar != null)
		//wgonzalez-objCashDolar = array_filter($objCashDolar,function($var){
		//wgonzalez-	if (strtoupper($var["moneda"]) == "DOLAR")
		//wgonzalez-		return true;
		//wgonzalez-);
		//wgonzalez-
		//wgonzalez-resultadoIngresoCajaCordoba = helper_reporteGeneralCreateTable(
		//wgonzalez-	$objCashCordoba,
		//wgonzalez-	$configColumnIngresoCaja,
		//wgonzalez-	'0px',
		//wgonzalez-	'CORDOBA = INGRESO DE CAJA'
		//wgonzalez-;
		//wgonzalez-
		//wgonzalez-resultadoIngresoCajaDolares = helper_reporteGeneralCreateTable(
		//wgonzalez-	$objCashDolar,
		//wgonzalez-	$configColumnIngresoCajaDolares,
		//wgonzalez-	'0px',
		//wgonzalez-	'DOLARES = INGRESO DE CAJA',
		//wgonzalez-	'68c778',
		//wgonzalez-	'black'
		//wgonzalez-;
		//wgonzalez-
		//wgonzalez-
		//wgonzalez-totalCordoba = $totalCordoba + ($resultadoIngresoCajaCordoba["table"] === 0 ? 0 : $resultadoIngresoCajaCordoba["configColumn"][8]["TotalValor"]);
		//wgonzalez-totalDolares = $totalDolares + ($resultadoIngresoCajaDolares["table"] === 0 ? 0 : $resultadoIngresoCajaDolares["configColumn"][8]["TotalValor"]);
		
		?>
		
		
		<?php
		$configColumnSalidaCaja["1"]["Titulo"] 		= "Cliente";
		$configColumnSalidaCaja["8"]["Titulo"] 		= "Monto";	
		
		$configColumnSalidaCaja["1"]["FiledSouce"] 		= "transactionNumber";		
		$configColumnSalidaCaja["8"]["FiledSouce"] 		= "montoTransaccion";		
				
		
		$configColumnSalidaCaja["1"]["Formato"] 		= "";					
		$configColumnSalidaCaja["8"]["Formato"] 		= "Number";					
		
		$configColumnSalidaCaja["1"]["Width"] 		= "150px";		
		$configColumnSalidaCaja["8"]["Width"] 		= "20px";				
		
		$configColumnSalidaCaja["1"]["Total"] 		= False;		
		$configColumnSalidaCaja["8"]["Total"] 		= False;				
		
		$configColumnSalidaCajaDolares["1"]["Titulo"] 		= "Cliente";		
		$configColumnSalidaCajaDolares["8"]["Titulo"] 		= "Monto";				
		
		$configColumnSalidaCajaDolares["1"]["FiledSouce"] 		= "transactionNumber";
		$configColumnSalidaCajaDolares["8"]["FiledSouce"] 		= "montoTransaccion";		
		
		$configColumnSalidaCajaDolares["1"]["Formato"] 		= "";				
		$configColumnSalidaCajaDolares["8"]["Formato"] 		= "Number";				
			
		$configColumnSalidaCajaDolares["1"]["Width"] 		= "150px";			
		$configColumnSalidaCajaDolares["8"]["Width"] 		= "20px";		
		
		$configColumnSalidaCajaDolares["1"]["Total"] 		= False;		
		$configColumnSalidaCajaDolares["8"]["Total"] 		= False;		
		
		
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
				'C$ Salida de Caja'
		);
		
		$resultadoSalidaCajaDolares = helper_reporteGeneralCreateTable(
				$objCashOutDolar,
				$configColumnSalidaCajaDolares,
				'0px',
				'$ Salida de Caja',
				'68c778',
				'black'
		);
		
		
		
		$totalCordobaSalidaCaja = ($resultadoSalidaCajaCordoba["table"] === 0 ? 0 : array_sum(array_column($objCashOutCordoba, 'montoTransaccion'))  );
		$totalDolaresSalidaCaja = ($resultadoSalidaCajaDolares["table"] === 0 ? 0 : array_sum(array_column($objCashOutDolar, 'montoTransaccion'))  );
		$totalCordoba = $totalCordoba - $totalCordobaSalidaCaja;
		$totalDolares = $totalDolares - $totalDolaresSalidaCaja;
		echo $resultadoSalidaCajaCordoba["table"];
		
		
		$configTotalesFirma[0]["Titulo"] 		= "Total C$ Salida";
		$configTotalesFirma[0]["FiledSouce"] 	= "total";		
		$configTotalesFirma[0]["Colspan"] 		= $columnX;		
		$configTotalesFirma[0]["Width"] 		= $widthX;
		$configTotalesFirma[0]["Alineacion"] 	= "Right";
		$configTotalesFirma[0]["Formato"] 		= "";				
		$detailTotalesFirma[0]["total"] 		= number_format($totalCordobaSalidaCaja , 2, '.', ',');
		
		$rosTotales = helper_reporteGeneralCreateTable(
				$detailTotalesFirma,
				$configTotalesFirma,
				'0',
				NULL
		);		
		echo $rosTotales["table"];
		
		?>
		
		<?php
		$configTotalesFirma[0]["Titulo"] 		= "Total C$";
		$configTotalesFirma[0]["FiledSouce"] 	= "total";		
		$configTotalesFirma[0]["Colspan"] 		= $columnX;		
		$configTotalesFirma[0]["Width"] 		= $widthX;
		$configTotalesFirma[0]["Alineacion"] 	= "Right";
		$configTotalesFirma[0]["Formato"] 		= "";				
		$detailTotalesFirma[0]["total"] 		= number_format($totalCordoba , 2, '.', ',');
		
		$rosTotales = helper_reporteGeneralCreateTable(
				$detailTotalesFirma,
				$configTotalesFirma,
				'0',
				NULL
		);		
		echo $rosTotales["table"];
		?>

		
		
		<?php 		
		
		$configTotalesFirma[0]["Titulo"] = "Firma";			
		$configTotalesFirma[0]["FiledSouce"] = "total";		
		$configTotalesFirma[0]["Colspan"] = $columnX;		
		$configTotalesFirma[0]["Width"] = $widthX;
		
		
		$detailTotalesFirma[0]["total"] = "";
		$rosTotales = helper_reporteGeneralCreateTable(
				$detailTotalesFirma,
				$configTotalesFirma,
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