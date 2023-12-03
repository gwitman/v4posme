<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $objFirmaEncription; ?></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		<style>
			table, td, tr, th {
				border-collapse: collapse;
			}
			
			.border {
				border-color:black;
				border:solid 1px black;						
			}
			@media print {
			  body
			  {
				 font-size:<?php echo $objParameterTamanoLetra; ?>
			  }
			}
		</style>
	</head>
	<body style="font-family:monospace;font-size:<?php echo $objParameterTamanoLetra; ?>;margin:0px 0px 0px 0px"> 
		
		<table style="
			width:100%;border-spacing: 10px;			
		">
			<thead>
				<tr>
					<th colspan="3" rowspan="5" style="text-align:left;width:130px">
						<img width="120" height="110" 						
							style="
								width: 120px;
								height: 110px;
							"
							
							src="<?php echo base_url(); ?>/resource/img/logos/logo-micro-finanza.jpg" 
						/>
					</th>
					<th colspan="4" style="
						text-align:right;background-color:#00628e;color:white;
						width:80%
					">LISTA DE ITEM</th>
				</tr>
				<tr>
					<th colspan="4" style="
						text-align:right;background-color:#00628e;color:white;
					"><?php echo strtoupper($objCompany->name); ?></th>
				</tr>
				<tr>
					<th colspan="4" style="text-align:left">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="4" style="text-align:left">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="4" style="text-align:left">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="7" style="text-align:left">
						&nbsp;
					</th>
				</tr>
			</thead>
		</table>
		
		<br/>		
		
		<table style="
			width:100%;order-spacing: 10px;
		" >
			<thead >
				<tr style="background-color:#00628e;color:white">
					<!--812-->
					<th  style="text-align:left;text-align:left;width:80px;"   colspan="1" class="border">Codigo</th>
					<th  style="text-align:left;;width:350px;"  colspan="1" class="border">Nombre</th>						
					<th  style="text-align:left;text-align:left;width:150px;"    colspan="1" class="border">Categoria</th>	
					<th  style="text-align:right;width:8px;"  colspan="1"   class="border">Cantidad</th>
					<th  style="text-align:left;width:80px;" class="border">Precio 1</th>
					<th  style="text-align:left;width:80px;" class="border">Precio 2</th>
					<th  style="text-align:left;width:80px;" class="border">Precio 3</th>						
					
				</tr>
				
			</thead>				
			<tbody>
				<?php
				$count 			= 0;
				$costoTotal		= 0;
				$precioTotal 	= 0;
				
				if($objDetail){
					$category   = $objDetail[0]["categoryName"];
					
					foreach($objDetail as $i){
						$count++;	
						$costoTotal 	= $costoTotal +  ($i["cost"] * $i["quantity"] );
						$precioTotal 	= $precioTotal +  ($i["price"] * $i["quantity"] );
						
						
						
						echo "<tr >";
							echo "<td style='text-align:left;'  colspan='1'  class='border' >";
								echo (substr($i["itemNumber"],-15));
							echo "</td>";
							echo "<td style='text-align:left'  colspan='1'  class='border' >";
								echo ($i["warehouseName"]."----".$i["itemName"]);
							echo "</td>";		
							echo "<td style='text-align:left'  colspan='1' class='border' >";
								echo ($i["categoryName"]);
							echo "</td>";	
							echo "<td style='text-align:right;' colspan='1'  class='border' >";
								echo number_format($i["quantity"],2,'.',',');
							echo "</td>";
							echo "<td  style='text-align:right' class='border'>";
								echo number_format( $i["price"],2,'.',',');
							echo "</td>";
							echo "<td style='text-align:right' class='border'>";
								echo (number_format($i["price2"],2,'.',',' ));;
							echo "</td>";
							echo "<td style='text-align:right' class='border' >";
								echo (number_format( $i["price3"],2,'.',','));
							echo "</td>";												
						echo "</tr>";
						
					}
				}
				?>
			</tbody>
			<tfoot>
				<tr style="background-color:#00628e;color:white;">
					<!--812-->
					<th  style="text-align:left;text-align:left;"   colspan="1" class="border">Codigo</th>
					<th  style="text-align:left;"  colspan="1" class="border">Nombre</th>						
					<th  style="text-align:left;text-align:left;"    colspan="1" class="border">Categoria</th>	
					<th  style="text-align:right;"  colspan="1"   class="border">Cantidad</th>
					<th  style="text-align:left;" class="border">Precio 1</th>
					<th  style="text-align:left;" class="border">Precio 2</th>
					<th  style="text-align:left;" class="border">Precio 3</th>						
				</tr>
				
				
			</tfoot>
		</table>

		
		
		<br/>		

		<table style="width:100%">
			<thead>
				<tr>
					<th colspan="11" ><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?> posMe</th>
				</tr>
			</thead>
		</table>
	
		
	</body>	
</html>