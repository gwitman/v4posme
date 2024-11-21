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
		echo helper_reporteGeneralCreateEncabezado(
			'ARQUEOS DE CAJA',
			$objCompany->name." ".$branchName,
			5,
			'DEL '.$startOn.' AL '.$endOn,
			"",
			"",
			"800px" 
		);
		
		?>
		

		<?php 		
		
		$detailTotales 	= array();
		$index 			= 0;
		if($dataDetail)
		{
			foreach($dataDetail as $key => $valueDeail)
			{				
			
				
				//agregar llame si no existe
				$exist = array_filter($detailTotales,function($ele) use ($key) { return $ele["Fecha"] == $key; });
				if(!$exist)
				{
					$detailTotales[$index]["Fecha"] 	= $key;
					$detailTotales[$index]["Amount"] 	= 0;
					$index++;
				}
				
				
				//Ventas de Contado
				if($valueDeail["objSales"])
				{					
					
					
					$temp = array_filter($dataDetail[$key]["objSales"],function($ele) use ($key) { 
						return
							$ele["currencyName"] == "Cordoba" &&
							$ele["tipo"] == "CONTADO";
					});										
					if($temp)
					{
						$temp = array_column($temp, 'totalDocument');						
						$temp = array_sum($temp);												
						$detailTotales[$index-1]["Amount"]  = $detailTotales[$index-1]["Amount"] + $temp;		
					}
				}

				//Ventas de Credito Primas
				if($valueDeail["objSalesCredito"])
				{					
					$temp = array_filter($dataDetail[$key]["objSalesCredito"],function($ele) use ($key) { 
						return
							$ele["currencyName"] == "Cordoba" &&
							$ele["tipo"] == "CREDITO";
					});										
					if($temp)
					{
						$temp = array_column($temp, 'receiptAmount');						
						$temp = array_sum($temp);												
						$detailTotales[$index-1]["Amount"]  = $detailTotales[$index-1]["Amount"] + $temp;		
					}
				}
				
				
				//Abonos
				if($valueDeail["objDetail"])
				{					
					$temp = array_filter($dataDetail[$key]["objDetail"],function($ele) use ($key) { 
						return
							$ele["moneda"] == "Cordoba" ;
					});										
					if($temp)
					{
						$temp = array_column($temp, 'montoFac');						
						$temp = array_sum($temp);												
						$detailTotales[$index-1]["Amount"]  = $detailTotales[$index-1]["Amount"] + $temp;		
					}
				}
				
				
				
				//Ingreso de Caja				
				if($valueDeail["objCash"])
				{					
					$temp = array_filter($dataDetail[$key]["objCash"],function($ele) use ($key) { 
						return
							$ele["moneda"] == "Cordoba" ;
					});										
					if($temp)
					{
						$temp = array_column($temp, 'montoTransaccion');						
						$temp = array_sum($temp);												
						$detailTotales[$index-1]["Amount"]  = $detailTotales[$index-1]["Amount"] + $temp;		
					}
				}
				
				//Salida de Caja
				if($valueDeail["objCashOut"])
				{					
					$temp = array_filter($dataDetail[$key]["objCashOut"],function($ele) use ($key) { 
						return
							$ele["moneda"] == "Cordoba" ;
					});										
					if($temp)
					{
						$temp = array_column($temp, 'montoTransaccion');						
						$temp = array_sum($temp);												
						$detailTotales[$index-1]["Amount"]  = $detailTotales[$index-1]["Amount"] - $temp;		
					}
				}
				
				
			}
		}
		
		$configTotalesColumns[0]["Titulo"] = "Fecha";			
		$configTotalesColumns[0]["FiledSouce"] = "Fecha";
		$configTotalesColumns[0]["Formato"] = "";
		$configTotalesColumns[0]["Colspan"] = 4;		
		$configTotalesColumns[0]["Width"] = "200";
		
		$configTotalesColumns[1]["Titulo"] = "Monto";			
		$configTotalesColumns[1]["FiledSouce"] = "Amount";
		$configTotalesColumns[1]["Formato"] = "Number";
		$configTotalesColumns[1]["Colspan"] = "1";
		$configTotalesColumns[1]["Width"] = "200";
		
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
		
		<?php 
		echo helper_reporteGeneralCreateFirma(	
			$objFirmaEncription,
			5,
			"800px" 
		);
		?>
		
		
	</body>	
</html>