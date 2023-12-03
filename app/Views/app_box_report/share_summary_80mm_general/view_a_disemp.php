<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>REPORTE DE CAJA</title>
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
			  font-size: xx-small;
			  font-weight: bold;
			  font-family: Consolas, monaco, monospace;
			}
		</style>
		
	</head>
	<?php 
		$width 	= "";
		$margin = "";
	?>
	
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
				
			  </td>
			</tr>
			<tr>
			  <td colspan='1' style='text-align:left'>
				Fecha de reporte:
			  </td>
			  <td colspan='2' style='text-align:left'>
				<?php echo $dateCurrent; ?> 
			  </td>
			</tr>
			<tr>
			  <td colspan='1' style='text-align:left'>
				Del:
			  </td>
			  <td colspan='2' style='text-align:left'>
				<?php echo $startOn; ?> 
			  </td>
			</tr>
			<tr>
			  <td colspan='1' style='text-align:left'>
				Al
			  </td>
			  <td colspan='2' style='text-align:left'>
				<?php echo $endOn; ?>
			  </td>
			</tr>
			<tr>
			  <td colspan='1' style='text-align:left'>
				Usuario 
			  </td>
			  <td colspan='2' style='text-align:left'>
				<?php echo ($obUserModel ? $obUserModel->nickname : "TODOS");  ?>
			  </td>
			</tr>
		</table>
		
		<?php 
		$objListCategory				= array_column($objDetail,"substitulo");
		$objListCategoryDistinct		= array_unique($objListCategory);
		$subTotal 						= 0;
		$total							= 0;
		foreach($objListCategoryDistinct as $category)
		{
			$subTotal 	= 0;
			$objItem 	= array_filter($objDetail , function($k) use ($category)
			{										
				return $k["substitulo"] == $category;
			});			
			
			echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'></td></tr></table>";
			echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'></td></tr></table>";
			echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:left'>".strtoupper($category)."</td></tr></table>";						
			echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'><hr/></td></tr></table>";
			
			$comandoProcess = "";
			foreach($objItem as $i)
			{
				$comandoProcess = $i["comandoProce"];
				$sumary 		= $i["sumary"];
				
				if( strlen($i["nombre"]) > 20 )
				{
					echo "<table style='width:100%;border-collapse: collapse;' ><tr><td colspan='3' style='text-align:left'>    
							<table style='width:100%;border-collapse: collapse;' >
								<tr>
									<td colspan='3' style='text-align:left;width:100%'>
										".strtoupper($i["nombre"])."
									</td>
									
								</tr>
							</table>      
					</td></tr></table>";	
					echo "<table style='width:100%;border-collapse: collapse;' ><tr><td colspan='3' style='text-align:left'>    
							<table style='width:100%;border-collapse: collapse;' >
								<tr>
									<td colspan='1' style='text-align:left;width:60%'>
										 
									</td>
									<td colspan='1' style='text-align:left;width:20%'>
										". ($comandoProcess == "Default" ?  round($i["cantidad"],2) : $i["cantidad"]) ."
									</td>
									<td colspan='1' style='text-align:right;width:20%'>
										".($comandoProcess == "Default" ?  round($i["total"],2) : $i["total"])."
									</td>
								</tr>
							</table>      
					</td></tr></table>";							
				}
				else 
				{
					echo "<table style='width:100%;border-collapse: collapse;' ><tr><td colspan='3' style='text-align:left'>    
							<table style='width:100%;border-collapse: collapse;' >
								<tr>
									<td colspan='1' style='text-align:left;width:60%'>
										".strtoupper($i["nombre"])."
									</td>
									<td colspan='1' style='text-align:left;width:20%'>
										".($comandoProcess == "Default" ?  round($i["cantidad"],2) : $i["cantidad"])."
									</td>
									<td colspan='1' style='text-align:right;width:20%'>
										".($comandoProcess == "Default" ?  round($i["subtotal"],2) : $i["subtotal"])."
									</td>
								</tr>
							</table>      
					</td></tr></table>";					
				}
				
				
				$subTotal = $subTotal + round($i["total"],2);
			}
			
			
			
			if(strtoupper($sumary) == "SI" )
			{
				echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:right'>---------------------------</td></tr></table>";
				echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:right'>".substr($subTotal,-12)."</td></tr></table>";				
				$total = $total + $subTotal;
			}
			
			
		}
		
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'></td></tr></table>";
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'></td></tr></table>";
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'></td></tr></table>";
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'></td></tr></table>";
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'></td></tr></table>";		
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'></td></tr></table>";
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'></td></tr></table>";
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'></td></tr></table>";
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'>---------------------------</td></tr></table>";
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'></td></tr></table>";
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'> Revizado </td></tr></table>";
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'></td></tr></table>";
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'></td></tr></table>";
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'></td></tr></table>";
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'></td></tr></table>";
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'></td></tr></table>";
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'></td></tr></table>";
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'></td></tr></table>";
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'>---------------------------</td></tr></table>";
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'></td></tr></table>";
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'> Autorizado </td></tr></table>";
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'></td></tr></table>";		
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'>******* fin reporte *******</td></tr></table>";
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'></td></tr></table>";
		echo "<table style='width:100%' ><tr><td colspan='3' style='text-align:center'></td></tr></table>";
		
		
		?>
		
	</body>	
</html>