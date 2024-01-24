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
					<th   style="text-align:left;width:130px">
						<table>
							<tr>
								<td>
									<img width="120" height="110" 						
										style="
											width: 120px;
											height: 110px;
										"
										src="<?php echo base_url(); ?>/resource/img/logos/logo-micro-finanza.jpg" 
									/>
								</td>
							</tr>
						</table>
					</th>
					<th style="vertical-align:top" >
						<table style="width:100%">
							
							<tr  style="
								text-align:right;background-color:#00628e;color:white;
								width:80%
							">
								<th>COMPARATIVO MENSUAL DE GASTO</th>
							</tr>
							<tr>
								<th  style="
									text-align:right;background-color:#00628e;color:white;
								"><?php echo strtoupper($objCompany->name); ?></th>
							</tr>
							<tr>
								<th  style="
									text-align:right;background-color:#00628e;color:white;
								">INFORMACION DE <?php echo $objStartOn; ?> AL <?php echo $objEndOn; ?></th>
							</tr>
							<tr>
								<th style="
									text-align:right;background-color:#00628e;color:white;
								"></th>
							</tr>
							<tr>
								<th style="
									text-align:right;background-color:#00628e;color:white;
								"></th>
							</tr>				
							<tr>
								<th  style="text-align:left">
									&nbsp;
								</th>
							</tr>
						</table>
					</th>
				</tr>				
			</thead>
		</table>
		
		
		
		<br/>
		
		
		<br/>
		
		<table style="width:100%;order-spacing: 10px;">
			<thead>
				<tr style="background-color:#00628e;color:white;">									

					<?php
					$count 		= 0;
					if($objDetail)
					foreach($objDetail[0] as $i => $value)
					{	
					
						echo '<th style="text-align:left;width:80px;"   colspan="1" class="border">'.$i.'</th>';
						
					}
					?>
				
				</tr>
			</thead>				
			<tbody>
				<?php
				$columnx = 0;
				if($objDetail)
				foreach($objDetail as $i)
				{		
					$columnx = 0;
					echo "<tr>";								
					foreach($i as $key => $ii)
					{			
						$columnx++;
						echo "<td style='text-align:left'  colspan='1' class='border' >";
							if($columnx > 1)
							echo (number_format($ii,2,'.',','));
							else 
							echo ($ii);
						echo "</td>";																											
					}
					echo "</tr>";
				}
				?>
			</tbody>
		
		</table>
		
		
		
		
	</body>	
</html>