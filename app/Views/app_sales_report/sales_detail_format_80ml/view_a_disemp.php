 <!DOCTYPE html>
<html lang='en'>

	<head>
		<meta charset='UTF-8' />
		<meta name='viewport' content='width=device-width, initial-scale=1.0' />
				
		<style>
			 @page {       
                          size: 2.7in 800in;                  
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
	<body > 
		
		
		
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
				DETALLE DE VENTA 80MM
			  </td>
			</tr>
			<tr>
			  <td colspan='3' style='text-align:center'>
				<?php echo $objCompany->name; ?>
			  </td>
			</tr>
			<tr>
			  <td colspan='3' style='text-align:center'>
				del <?php echo $objStartOn; ?> al  <?php echo $objEndOn; ?>
			  </td>
			</tr>
		</table>
		
		
	    <br/><br/>
        <table>               
		<?php 	
		
		$confiDetalleHeader = array();
		$row = array(
			"style"		=>"text-align:left;width:auto",
			"colspan"	=>'1',
			"prefix"	=>'',
			
			
			"style_row_data"		=>"text-align:left;width:auto",
			"colspan_row_data"		=>'3',
			"prefix_row_data"		=>'',
			"nueva_fila_row_data"	=>1
		);
		array_push($confiDetalleHeader,$row);
			
		$row = array(
			"style"		=>"text-align:left;width:60px",
			"colspan"	=>'1',
			"prefix"	=>'',
			
			"style_row_data"		=>"text-align:right;width:60px",
			"colspan_row_data"		=>'1',
			"prefix_row_data"		=>'',
			"nueva_fila_row_data"	=>0
		);
		array_push($confiDetalleHeader,$row);
			
		$row = array(
			"style"		=>"text-align:right;width:90px",
			"colspan"	=>'1',
			"prefix"	=>'',
			
			"style_row_data"		=>"text-align:right;width:90px",
			"colspan_row_data"		=>'1',
			"prefix_row_data"		=>'',
			"nueva_fila_row_data"	=>0
		);
		array_push($confiDetalleHeader,$row);
		
		$row = array(
			"style"		=>"text-align:right;width:auto",
			"colspan"	=>'1',
			"prefix"	=>'',
			
			"style_row_data"		=>"text-align:right;width:auto",
			"colspan_row_data"		=>'1',
			"prefix_row_data"		=>'',
			"nueva_fila_row_data"	=>0
		);
		array_push($confiDetalleHeader,$row);
			
			
		//Titutlo
		$objDetailCurrent = array();		    
		$row = array("","","", "");
		array_push($objDetailCurrent,$row);
		
		//Registro		
		$total = 0;
		foreach($objDetail as $detail_)
		{
			$row = array(
				$detail_["itemName"],
				sprintf("%01.2f",round($detail_["quantity"],2)), 
				sprintf("%01.2f",round($detail_["amount"],2)),
				$detail_["itemNumber"]
			);
			array_push($objDetailCurrent,$row);
			$total = $total + $detail_["amount"];
			
		}
		
		$row = array("","Total:",sprintf("%01.2f",round($total,2)), "");
		array_push($objDetailCurrent,$row);
		
		
		echo helper_generateTableParaPdf($confiDetalleHeader,$objDetailCurrent);
		?>
		</table>
		<br/><br/>
		<table style='width:100%'>
			<tr>
			  <td colspan='3' style='text-align:center'>
				<?php echo $objFirmaEncription; ?>
			  </td>
			</tr>			
		</table>
		
		
	</body>	
</html>