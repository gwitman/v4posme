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
		$width1			= "5555px";
		$width2  		= "0px";//99		
		$width3  		= "0px";//480
		$width4			= "0px";//480
		$width5  		= "0px";//480
		$width6  		= "0px";//480
		$width7  		= "0px";//97
		$width8  		= "0px";//150
		$width9  		= "0px";//98
		$width10  		= "0px";//110
		$width11  		= "0px";//96
		$width12  		= "0px";//115
		$width13  		= "0px";//480
		$width14  		= "0px";//0
		$width15  		= "0px";//0
		$width16  		= "0px";//0
		$width17  		= "0px";//0
		$width18  		= "0px";//0
		
		
		$totalCordoba 	= 0;
		$totalDolares 	= 0;
		$widthX 		= 5550;
		$columnX 		= 24;
		
		echo helper_reporteGeneralCreateEncabezado(
			'SABANA DE CREDITOS',
			$objCompany->name,
			$columnX+10,
			'DEL '.$startOn.' AL '.$endOn,
			"",
			"",
			$widthX."px"
		);
		
		
		$configColumn[1]["AutoIncrement"]		= true;
		
		$configColumn[1] ["Titulo"]				= "#";
		$configColumn[2] ["Titulo"] 			= "Usuario";		
		$configColumn[3] ["Titulo"] 			= "Cod. Cliente";		
		$configColumn[4] ["Titulo"] 			= "F.R. Cliente";	
		$configColumn[5] ["Titulo"] 			= "Cliente";		
		$configColumn[6] ["Titulo"] 			= "Telefono";		
		$configColumn[7] ["Titulo"] 			= "Estado Civil";
		$configColumn[8] ["Titulo"] 			= "Categoria";
		$configColumn[9] ["Titulo"] 			= "Identificacion";
		$configColumn[10]["Titulo"] 			= "Sexo";
		$configColumn[11]["Titulo"] 			= "Direccion";
		$configColumn[12]["Titulo"] 			= "Cod. Desembolso";			
		$configColumn[13]["Titulo"] 			= "Plazo";		
		$configColumn[14]["Titulo"] 			= "Interes";				
		$configColumn[15]["Titulo"] 			= "F. Desembolso";		
		$configColumn[16]["Titulo"] 			= "F. Ultimo abono";				
		$configColumn[17]["Titulo"] 			= "Estado Cliente";	
		$configColumn[18]["Titulo"] 			= "Estado Desembolso";		
		$configColumn[19]["Titulo"] 			= "Frecuencia de pago";
		$configColumn[20]["Titulo"] 			= "Desembolso";		
		$configColumn[21]["Titulo"] 			= "Desembolso + Interes";		
		$configColumn[22]["Titulo"] 			= "Monto Pagado";		
		$configColumn[23]["Titulo"] 			= "Avance";		
		$configColumn[24]["Titulo"] 			= "Saldo";
					  
		$configColumn[1]["FiledSouce"]			= "orden";		
		$configColumn[2]["FiledSouce"] 			= "nickname";		
		$configColumn[3]["FiledSouce"] 			= "customerNumber";		
		$configColumn[4]["FiledSouce"] 			= "customerCreatedOn";		
		$configColumn[5]["FiledSouce"] 			= "customerName";		
		$configColumn[6]["FiledSouce"] 			= "phoneNumber";	
		$configColumn[7]["FiledSouce"] 			= "statusCivil";
		$configColumn[8]["FiledSouce"] 			= "comercialName";
		$configColumn[9]["FiledSouce"] 			= "identification";
		$configColumn[10]["FiledSouce"] 		= "sexo";
		$configColumn[11]["FiledSouce"] 		= "location";
		$configColumn[12]["FiledSouce"] 		= "documentNumber";
		$configColumn[13]["FiledSouce"] 		= "term";		
		$configColumn[14]["FiledSouce"] 		= "interes";	
		$configColumn[15]["FiledSouce"] 		= "dateDocument";		
		$configColumn[16]["FiledSouce"] 		= "dateLastShareDocument";				
		$configColumn[17]["FiledSouce"] 		= "statusCustomer";	
		$configColumn[18]["FiledSouce"] 		= "statusName";	
		$configColumn[19]["FiledSouce"] 		= "periodPay";	
		$configColumn[20]["FiledSouce"] 		= "amountDocument";	
		$configColumn[21]["FiledSouce"] 		= "deudaTotal";	
		$configColumn[22]["FiledSouce"] 		= "montoPagado";	
		$configColumn[23]["FiledSouce"] 		= "avance";	
		$configColumn[24]["FiledSouce"] 		= "saldo";	
		
					  
					  
		$configColumn[1]["Formato"]			= "";
		$configColumn[2]["Formato"] 		= "";		
		$configColumn[3]["Formato"] 		= "";		
		$configColumn[4]["Formato"]			= "";		
		$configColumn[5]["Formato"] 		= "";	
		$configColumn[6]["Formato"] 		= "";	
		$configColumn[7]["Formato"] 		= "";			
		$configColumn[8]["Formato"] 		= "";			
		$configColumn[9]["Formato"] 		= "";			
		$configColumn[10]["Formato"] 		= "";			
		$configColumn[11]["Formato"] 		= "";			
		$configColumn[12]["Formato"] 		= "";		
		$configColumn[13]["Formato"] 		= "Number";		
		$configColumn[14]["Formato"] 		= "Number";				
		$configColumn[15]["Formato"] 		= "Date";		
		$configColumn[16]["Formato"] 		= "Date";		
		$configColumn[17]["Formato"] 		= "";	
		$configColumn[18]["Formato"] 		= "";	
		$configColumn[19]["Formato"] 		= "";	
		$configColumn[20]["Formato"] 		= "Number";		
		$configColumn[21]["Formato"] 		= "Number";		
		$configColumn[22]["Formato"] 		= "Number";	
		$configColumn[23]["Formato"] 		= "Number";	
		$configColumn[24]["Formato"] 		= "Number";	
		
		
		$configColumn[1]["Width"] 		= $width1;		
		$configColumn[2]["Width"] 		= $width2;		
		$configColumn[3]["Width"]		= $width3;		
		$configColumn[4]["Width"] 		= $width4;		
		$configColumn[5]["Width"] 		= $width5;	
		$configColumn[6]["Width"] 		= $width5;	
		$configColumn[7]["Width"] 		= $width5;			
		$configColumn[8]["Width"] 		= $width5;	
		$configColumn[9]["Width"] 		= $width5;	
		$configColumn[10]["Width"] 		= $width5;	
		$configColumn[11]["Width"] 		= $width5;	
		$configColumn[12]["Width"] 		= $width6;		
		$configColumn[13]["Width"] 		= $width7;		
		$configColumn[14]["Width"] 		= $width8;		
		$configColumn[15]["Width"] 		= $width9;		
		$configColumn[16]["Width"] 		= $width10;		
		$configColumn[17]["Width"] 		= $width11;	
		$configColumn[18]["Width"] 		= $width12;	
		$configColumn[19]["Width"] 		= $width13;	
		$configColumn[20]["Width"] 		= $width14;	
		$configColumn[21]["Width"] 		= $width15;	
		$configColumn[22]["Width"] 		= $width16;	
		$configColumn[23]["Width"] 		= $width17;	
		$configColumn[24]["Width"] 		= $width18;	
		
					  
		$configColumn[1]["Total"] 		= False;				  
		$configColumn[2]["Total"] 		= False;		
		$configColumn[3]["Total"] 		= False;		
		$configColumn[4]["Total"]		= False;		
		$configColumn[5]["Total"] 		= False;		
		$configColumn[6]["Total"] 		= False;		
		$configColumn[7]["Total"] 		= False;		
		$configColumn[8]["Total"] 		= False;		
		$configColumn[9]["Total"] 		= False;		
		$configColumn[10]["Total"] 		= False;		
		$configColumn[11]["Total"] 		= False;		
		$configColumn[12]["Total"] 		= False;		
		$configColumn[13]["Total"] 		= False;		
		$configColumn[14]["Total"] 		= False;		
		$configColumn[15]["Total"] 		= False;		
		$configColumn[16]["Total"] 		= False;		
		$configColumn[17]["Total"] 		= False;		
		$configColumn[18]["Total"] 		= False;		
		$configColumn[19]["Total"] 		= False;	
		$configColumn[20]["Total"] 		= False;	
		$configColumn[21]["Total"] 		= False;	
		$configColumn[22]["Total"] 		= False;	
		$configColumn[23]["Total"] 		= False;	
		$configColumn[24]["Total"] 		= False;
		
		
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