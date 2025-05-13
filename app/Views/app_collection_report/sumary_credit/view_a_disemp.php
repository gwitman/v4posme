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
		$width0  		= "1000px";//99
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
		$widthX 		= 1000;
		$columnX 		= 7;
		
		echo helper_reporteGeneralCreateEncabezado(
			'RESUMEN DE CREDITO',
			$objCompany->name,
			$columnX+10,
			'DEL '.$startOn.' AL '.$endOn,
			"",
			"",
			$widthX."px"
		);
		
		$configColumn[0]["Titulo"] 		= "Usuario";		
		$configColumn[1]["Titulo"] 		= "# De Clientes";		
		$configColumn[2]["Titulo"] 		= "# De Creditos";		
		$configColumn[3]["Titulo"] 		= "# De Clienes Cancelados";		
		$configColumn[4]["Titulo"] 		= "# De Clienes Nuevos";		
		$configColumn[5]["Titulo"] 		= "# De Clienes Recuperados";				
		$configColumn[6]["Titulo"] 		= "C$ Monto de Cartera";		
					  
					  
		$configColumn[0]["FiledSouce"] 		= "nickname";		
		$configColumn[1]["FiledSouce"] 		= "countCustomer";		
		$configColumn[2]["FiledSouce"] 		= "countCredit";		
		$configColumn[3]["FiledSouce"] 		= "countCustomerCancel";		
		$configColumn[4]["FiledSouce"] 		= "countCustomerNew";		
		$configColumn[5]["FiledSouce"] 		= "countCustomerRecuperation";	
		$configColumn[6]["FiledSouce"] 		= "amountCartera";		
					  
		$configColumn[0]["Formato"] 		= "";		
		$configColumn[1]["Formato"] 		= "Number";		
		$configColumn[2]["Formato"] 		= "Number";		
		$configColumn[3]["Formato"] 		= "Number";		
		$configColumn[4]["Formato"] 		= "Number";		
		$configColumn[5]["Formato"] 		= "Number";				
		$configColumn[6]["Formato"] 		= "Number";		
					  
		$configColumn[0]["Width"] 		= $width0;		
		$configColumn[1]["Width"] 		= $width1;		
		$configColumn[2]["Width"] 		= $width2;		
		$configColumn[3]["Width"] 		= $width3;		
		$configColumn[4]["Width"] 		= $width4;		
		$configColumn[5]["Width"] 		= $width5;		
		$configColumn[6]["Width"] 		= $width6;		
					  
		$configColumn[0]["Total"] 		= False;		
		$configColumn[1]["Total"] 		= True;		
		$configColumn[2]["Total"] 		= True;		
		$configColumn[3]["Total"] 		= True;		
		$configColumn[4]["Total"] 		= True;		
		$configColumn[5]["Total"] 		= True;		
		$configColumn[6]["Total"] 		= True;		
		
		$resultado = helper_reporteGeneralCreateTable(
			$objDetail,
			$configColumn,
			'0px',
			"..::RESUMEN::.."
		);
		
		?>
		
		<?php 
		if($resultado["table"] !== 0)
		echo $resultado["table"];
		?>
		
		
		
		
	</body>	
</html>