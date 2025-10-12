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
					<th colspan="8" style="
						text-align:right;background-color:#00628e;color:white;
						width:80%
					">LISTA DE CLIENTES</th>
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
	
		<table style="
			width:100%;order-spacing: 10px;
		" >
			<thead >
				
				<tr style="background-color:#00628e;color:white;">
					<!--812-->					
					<th  style="text-align:left;width:80px;" class="border">Codigo</th>
					<th  style="text-align:left;width:250px;" class="border">Cliente</th>
					<th  style="text-align:right;width:80px;"  colspan="1"   class="border">Balance</th>
					<th  style="text-align:left;width:80px;"   class="border">Referencia1</th>
					<th  style="text-align:left;width:80px;"   class="border">Referencia2</th>
					<th  style="text-align:left;width:80px;"   class="border">Referencia3</th>						
					<th  style="text-align:left;width:80px;"   class="border">Referencia4</th>		
				</tr>
				
			</thead>				
			<tbody>
				<?php
				$count 				= 0;
				$costoTotal			= 0;
				$precioTotal 		= 0;				
				$costoTotalDol		= 0;
				$precioTotalDol 	= 0;
				
				if($objDetail){
					
					
					foreach($objDetail as $i){
						
						$moneda 		= $i["Moneda"];
						if($moneda == "Cordoba" || $moneda == "C$")
						{
							$costoTotal 	= $costoTotal +  ($i["balanceTotal"]  );
							$precioTotal 	= $precioTotal +  ($i["balanceTotalCapital"]  );
						}
						else
						{
							$costoTotalDol 		= $costoTotalDol +  ($i["balanceTotal"]  );
							$precioTotalDol 	= $precioTotalDol +  ($i["balanceTotalCapital"]  );
						}
						
						
						
						echo "<tr style='' >";	
							echo "<td style='text-align:left;'  colspan='1'  class='border' >";
								echo ($i["customerNumber"]);
							echo "</td>";
							echo "<td style='text-align:left;'  colspan='1'  class='border' >";
								echo ($i["customerName"]);
							echo "</td>";							
							echo "<td style='text-align:right;' colspan='1'  class='border' >";
								echo number_format($i["balanceTotal"],2,'.',',');
							echo "</td>";
							echo "<td  style='text-align:right' class='border'>";
								echo "&nbsp;";
							echo "</td>";
							echo "<td style='text-align:right' class='border'>";
								echo "&nbsp;";
							echo "</td>";
							echo "<td style='text-align:right' class='border' >";
								echo "&nbsp;";
							echo "</td>";	
							echo "<td style='text-align:right' class='border' colspan='1' >";	
								echo "&nbsp;";
							echo "</td>";					
						echo "</tr>";
					}
				}
				?>
			</tbody>
			<tfoot>
				
				
				<tr style="background-color:#00628e;color:white;">
					<!--812-->					
					<th  style="" class="border">Codigo</th>
					<th  style="" class="border">Cliente</th>
					<th  style=""  colspan="1"   class="border">
						C$ <?php echo number_format($costoTotal,2,'.',',');  ?>
					</th>
					<th  style=""   class="border">Referencia1</th>
					<th  style=""   class="border">Referencia2</th>
					<th  style=""   class="border">Referencia3</th>	
					<th  style=""   class="border">Referencia4</th>	
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