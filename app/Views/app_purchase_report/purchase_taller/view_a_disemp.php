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
					">REPORTE DE TALLER <?php echo 'DEL '.$objStartOn.' AL '.$objEndOn; ?> </th>
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
					<th  style="text-align:left;text-align:left;width:220px;"   colspan="1" class="border">Estado</th>
					<?php
						
						$bodegasArray 		= explode(",", $employerListID);
						$bodegasArrayTodas 	= $employerListID == "-1,0" ? "true" : "false";
						foreach($objListEmployeer as $iele)
						{
							if($bodegasArrayTodas == "true" || in_array($iele->entityID,$bodegasArray ) )
							{
							echo '<th  style="text-align:right;width:120px;"  colspan="1"   class="border">';
								echo $iele->employeNumber."</br>".$iele->firstName;
							echo '</th>';
							}
						}
					?>
				</tr>
				
			</thead>				
			<tbody>
				<?php
				$bodegasArray 		= explode(",", $employerListID);
				$bodegasArrayTodas 	= $employerListID == "-1,0" ? "true" : "false";				
				if($objListItem)
				{
					
					foreach($objListItem as $ielement){
						
						//obtener estado:
						$i =  	array_filter($objDetail,function($var) use ($ielement) {
									if ( $var["Estado"] == $ielement)
										return true;
								});
								
						$i = reset($i);						
						
						echo "<tr >";
							echo "<td style='text-align:left;width:220px;'  colspan='1'  class='border' >";
								echo $ielement;
							echo "</td>";
							foreach($objListEmployeer as $iele)
							{
								$ieleName 	= 	$iele->firstName;
								$w			=  	array_filter($objDetail,function($var) use ($ielement,$ieleName) {
													if ( $var["Estado"] == $ielement && $var["firstName"] == $ieleName)
														return true;
												});	
								
				
								
								$w 			= reset($w);
								
								if($bodegasArrayTodas == "true" || in_array($iele->entityID,$bodegasArray ) )
								{
									echo "<td style='text-align:right;' colspan='1'  class='border' >";
											if($w === false)
											echo number_format(0,2,'.',',');
											else 
											echo number_format($w["Cantidad"],2,'.',',');
									echo "</td>";
								}
							}
						echo "</tr>";
					}
				}
				?>
			</tbody>
			<tfoot>
				<tr style="background-color:#00628e;color:white;">
					<!--812-->
					<th  style="text-align:left;text-align:left;"   colspan="1" class="border">Estado</th>
					<?php
						foreach($objListEmployeer as $iele)
						{
							if($bodegasArrayTodas == "true" || in_array($iele->entityID,$bodegasArray ) )
							echo '<th  style="text-align:right;width:120px;"  colspan="1"   class="border">'.$iele->firstName.'</th>';
						}
					?>
				</tr>
				
				
			</tfoot>
		</table>

		
		
		<br/>		
		
		<table style="
			width:100%;order-spacing: 10px;
		" >
			<thead >
				<tr style="background-color:#00628e;color:white">
					<!--812-->
					<th  style="text-align:left;text-align:left;width:220px;"   colspan="1" class="border">Documento</th>
					<th  style="text-align:left;text-align:left;width:220px;"   colspan="1" class="border">Fecha</th>
					<th  style="text-align:left;text-align:left;width:220px;"   colspan="1" class="border">Cod Cliente</th>
					<th  style="text-align:left;text-align:left;width:220px;"   colspan="1" class="border">Cliente</th>
					<th  style="text-align:left;text-align:left;width:220px;"   colspan="1" class="border">Colaborador</th>
					<th  style="text-align:left;text-align:left;width:220px;"   colspan="1" class="border">Factura</th>					
				</tr>
				
			</thead>				
			<tbody>
				<?php
					if($objTransaccionMaster)
					{
						foreach($objTransaccionMaster as $w)
						{
							echo "<tr style='background-color: antiquewhite;' >";
								echo "<td style='text-align:right;' colspan='1'  class='border' >";							
										echo $w["transactionNumber"];
								echo "</td>";
								echo "<td style='text-align:right;' colspan='1'  class='border' >";							
										echo $w["transactionOn"];
								echo "</td>";
								echo "<td style='text-align:right;' colspan='1'  class='border' >";							
										echo $w["customerNumber"];
								echo "</td>";
								echo "<td style='text-align:right;' colspan='1'  class='border' >";							
										echo $w["firstName"];
								echo "</td>";
								echo "<td style='text-align:right;' colspan='1'  class='border' >";							
										echo $w["employeNumber"]." ".$w["firstNameEmployer"];
								echo "</td>";
								echo "<td style='text-align:right;' colspan='1'  class='border' >";							
										echo $w["note"];
								echo "</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td style='text-align:left;' colspan='6'  class='border' >";							
										echo "<b>Problema:</b>".$w["reference2"];
								echo "</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td style='text-align:left;' colspan='6'  class='border' >";							
										echo "<b>Solucion:</b>".$w["reference3"];
								echo "</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td style='text-align:left;' colspan='6'  class='border' >";							
										echo "<b>Descripcion:</b>".$w["reference1"];
								echo "</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td style='text-align:left;' colspan='6'  class='border' >";							
										echo "<b>Estado:</b>".$w["estado_equipo"];
								echo "</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td style='text-align:left;' colspan='6'  class='border' >";							
										echo "<b>Modelo:</b>".$w["modelo"];
								echo "</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td style='text-align:left;' colspan='6'  class='border' >";							
										echo "<b>Articulo:</b>".$w["articulo"];
								echo "</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td style='text-align:left;' colspan='6'  class='border' >";							
										echo "<b>Otra Desc:</b>".$w["referenceClientName"];
								echo "</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td style='text-align:left;' colspan='6'  class='border' >";							
										echo "<b>Referencias:</b>".$w["detalles"];
								echo "</td>";
							echo "</tr>";
						}
					}
				?>
			</tbody>
			
		</table>
		
		</br>

		<table style="width:100%">
			<thead>
				<tr>
					<th colspan="11" ><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?> posMe</th>
				</tr>
			</thead>
		</table>
	
		
	</body>	
</html>