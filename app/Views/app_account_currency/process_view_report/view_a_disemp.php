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
								height: 60px;
							"
							
							src="<?php echo base_url(); ?>/resource/img/logos/logo-micro-finanza.jpg" 
						/>
					</th>
					<th colspan="8" style="
						text-align:right;background-color:#00628e;color:white;
						width:80%
					">LISTA DE ITEM</th>
				</tr>
				<tr>
					<th colspan="8" style="
						text-align:right;background-color:#00628e;color:white;
					"><?php echo strtoupper($objCompany->name); ?></th>
				</tr>
				<tr>
					<th colspan="8" style="text-align:left">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="8" style="text-align:left">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="8" style="text-align:left">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="11" style="text-align:left">
						&nbsp;
					</th>
				</tr>
			</thead>
		</table>
		
		<br/>		
		
		<table style="width:100%;" id="myReport" >
			<thead >
				
				<tr style="background-color:#00628e;color:white;">
					<!--812-->					
					<th  style=""  class="border">Fecha</th>
					<th  style=""  class="border">Moneda Local</th>						
					<th  style=""  class="border">Equiale A</th>	
					<th  style=""  class="border">Equiale A</th>	
					<th  style=""  class="border">Moneda Extranjera</th>
				</tr>
				
			</thead>				
			<tbody>
				<?php
				
				
				if($objDetail){
					
					
					foreach($objDetail as $i){
						
						if($i->nameSource == "Cordoba" )
						{
							echo "<tr  >";
								echo "<td style='text-align:left;'    class='border' >";
									echo ($i->date);
								echo "</td>";
								echo "<td style='text-align:right'   class='border' >";
									echo ($i->nameSource);
								echo "</td>";		
								echo "<td  style='text-align:right' class='border'>";
									echo number_format( $i->ratio,6,'.',',');
								echo "</td>";
								echo "<td  style='text-align:right' class='border'>";
									echo number_format( 1 / $i->ratio,6,'.',',');
								echo "</td>";
								echo "<td style='text-align:right'   class='border' >";
									echo ($i->nameTarget);
								echo "</td>";	
												
							echo "</tr>";
						}
					}
				}
				?>
			</tbody>
			<tfoot>
				
				<tr style="background-color:#00628e;color:white;">
					<!--812-->					
					<th  style=""  class="border">Fecha</th>
					<th  style=""  class="border">Moneda Local</th>						
					<th  style=""  class="border">Equiale A</th>	
					<th  style=""  class="border">Equiale A</th>	
					<th  style=""  class="border">Moneda Extranjera</th>	
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