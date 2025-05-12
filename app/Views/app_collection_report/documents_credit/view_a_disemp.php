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
		$width0  		= "3550px";//99
		$width1  		= "0px";//480
		$width2  		= "0px";//480
		$width3  		= "0px";//480
		$width4  		= "0px";//97
		$width5  		= "0px";//150
		$width6  		= "0px";//98
		$width7  		= "0px";//110
		$width8  		= "0px";//96
		$width9  		= "0px";//115
		$width10  		= "0px";//480
		$width11  		= "0px";//0
		$width12  		= "0px";//0
		$width13  		= "0px";//0
		
		
		$totalCordoba 	= 0;
		$totalDolares 	= 0;
		$widthX 		= 1550;
		$columnX 		= 14;
		
		echo helper_reporteGeneralCreateEncabezado(
			'SABANA DE CREDITOS',
			$objCompany->name,
			$columnX+10,
			'DEL '.$startOn.' AL '.$endOn,
			"",
			"",
			$widthX."px"
		);
		
		$configColumn[0]["Titulo"] 		= "Usuario";		
		$configColumn[1]["Titulo"] 		= "Cod. Cliente";		
		$configColumn[2]["Titulo"] 		= "Cliente";		
		$configColumn[3]["Titulo"] 		= "Desembolso";		
		$configColumn[4]["Titulo"] 		= "Plazo";		
		$configColumn[5]["Titulo"] 		= "Interes";				
		$configColumn[6]["Titulo"] 		= "F. Desembolso";		
		$configColumn[7]["Titulo"] 		= "F. Ultimo abono";				
		$configColumn[8]["Titulo"] 		= "Estado";		
		$configColumn[9]["Titulo"] 		= "Frecuencia de pago";
		$configColumn[10]["Titulo"] 	= "Desembolso";		
		$configColumn[11]["Titulo"] 	= "Monto Pagado";		
		$configColumn[12]["Titulo"] 	= "Avance";		
		$configColumn[13]["Titulo"] 	= "Saldo";
					  
					  
		$configColumn[0]["FiledSouce"] 		= "nickname";		
		$configColumn[1]["FiledSouce"] 		= "customerNumber";		
		$configColumn[2]["FiledSouce"] 		= "customerName";		
		$configColumn[3]["FiledSouce"] 		= "documentNumber";		
		$configColumn[4]["FiledSouce"] 		= "term";		
		$configColumn[5]["FiledSouce"] 		= "interes";	
		$configColumn[6]["FiledSouce"] 		= "dateDocument";		
		$configColumn[7]["FiledSouce"] 		= "dateLastShareDocument";				
		$configColumn[8]["FiledSouce"] 		= "statusName";	
		$configColumn[9]["FiledSouce"] 		= "periodPay";	
		$configColumn[10]["FiledSouce"] 	= "amountDocument";	
		$configColumn[11]["FiledSouce"] 	= "montoPagado";	
		$configColumn[12]["FiledSouce"] 	= "avance";	
		$configColumn[13]["FiledSouce"] 	= "saldo";	
					  
		$configColumn[0]["Formato"] 		= "";		
		$configColumn[1]["Formato"] 		= "";		
		$configColumn[2]["Formato"] 		= "";		
		$configColumn[3]["Formato"] 		= "";		
		$configColumn[4]["Formato"] 		= "Number";		
		$configColumn[5]["Formato"] 		= "Number";				
		$configColumn[6]["Formato"] 		= "Date";		
		$configColumn[7]["Formato"] 		= "Date";		
		$configColumn[8]["Formato"] 		= "";	
		$configColumn[9]["Formato"] 		= "";	
		$configColumn[10]["Formato"] 		= "Number";		
		$configColumn[11]["Formato"] 		= "Number";	
		$configColumn[12]["Formato"] 		= "Number";	
		$configColumn[13]["Formato"] 		= "Number";	
					  
					  
					  
		$configColumn[0]["Width"] 		= $width0;		
		$configColumn[1]["Width"] 		= $width1;		
		$configColumn[2]["Width"] 		= $width2;		
		$configColumn[3]["Width"] 		= $width3;		
		$configColumn[4]["Width"] 		= $width4;		
		$configColumn[5]["Width"] 		= $width5;		
		$configColumn[6]["Width"] 		= $width6;		
		$configColumn[7]["Width"] 		= $width7;		
		$configColumn[8]["Width"] 		= $width8;		
		$configColumn[9]["Width"] 		= $width9;	
		$configColumn[10]["Width"] 		= $width10;	
		$configColumn[11]["Width"] 		= $width11;	
		$configColumn[12]["Width"] 		= $width12;	
		$configColumn[13]["Width"] 		= $width13;	
					  
					  
		$configColumn[0]["Total"] 		= False;		
		$configColumn[1]["Total"] 		= False;		
		$configColumn[2]["Total"] 		= False;		
		$configColumn[3]["Total"] 		= False;		
		$configColumn[4]["Total"] 		= False;		
		$configColumn[5]["Total"] 		= False;		
		$configColumn[6]["Total"] 		= False;		
		$configColumn[7]["Total"] 		= False;		
		$configColumn[8]["Total"] 		= False;		
		$configColumn[9]["Total"] 		= False;	
		$configColumn[10]["Total"] 		= False;	
		$configColumn[11]["Total"] 		= False;	
		$configColumn[12]["Total"] 		= False;	
		$configColumn[13]["Total"] 		= False;	
		
		
		//Llenar las columnas dinamicas
		
		
		//Calcular lso datos dinamicos
		if($objDetail)
		{
			//Obtener los campos mapeados
			$FiledSources = [];
			foreach ($configColumn as $key => $column) {
				if (isset($column['FiledSouce'])) {
					$FiledSources[$key] = $column['FiledSouce'];
				}
			}


			$indexNew		= $columnX;
			$objRowDetail 	= $objDetail[0];
			foreach ($objRowDetail as $key => $valor) 
			{
				
				if(
					!in_array($key, $FiledSources)
					&& 
					!(
						$key == "currencyID"  ||
						$key == "customerCreditDocumentID"
					)
				)
				{
					
					$configColumn[$indexNew]["Titulo"] 		= $key;		
					$configColumn[$indexNew]["FiledSouce"] 	= $key;						
					$configColumn[$indexNew]["Formato"] 	= "Number";	
					$configColumn[$indexNew]["Total"] 		= False;	
					$configColumn[$indexNew]["Width"] 		= "0px";		
					$indexNew 								= $indexNew + 1;
				}
			}
		}
		
		
		
		
		$resultado = helper_reporteGeneralCreateTable(
			$objDetail,
			$configColumn,
			'0px',
			"DETALLE DE CARTERA"
		);
		
		?>
		
		<?php 
		if($resultado["table"] !== 0)
		echo $resultado["table"];
		?>
		
		
		
		
	</body>	
</html>