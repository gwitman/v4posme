<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $objFirmaEncription; ?></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		
		<!--
		<link rel="stylesheet" type="text/css" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/style_table_report_printer.css">
		<link rel="stylesheet" type="text/css" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/style_table_report_printer.css" media="print">
		-->
		
		<style>
			
			table, td, tr, th {
				border-collapse: collapse;
			}
			
			.border {
				border-color:black;
				border:solid 1px black;						
			}
			
		</style>
		
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
					<th colspan="6" style="
						text-align:right;background-color:#00628e;color:white;
						width:80%
					">LISTA DE PRODUCTO Y VENCIMIENTO</th>
				</tr>
				<tr>
					<th colspan="6" style="
						text-align:right;background-color:#00628e;color:white;
					"><?php echo strtoupper($objCompany->name); ?></th>
				</tr>
				<tr>
					<th colspan="6" style="text-align:left">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="6" style="text-align:left">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="6" style="text-align:left">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="9" style="text-align:left">
						&nbsp;
					</th>
				</tr>
			</thead>
		</table>
		
		
		<br/>
		
		<table style="
			width:100%;order-spacing: 10px;
		" >
			<thead>
				<tr style="background-color:#00628e;color:white;">
					<!--812-->
					<th style="text-align:left;width:50px;"  colspan="2" class="border">Codigo</th>
					<th style="text-align:left;width:222px;"  colspan="2" class="border">Nombre</th>											
					<th style="text-align:left;width:180px;"  colspan="2" class="border">Proveedor</th>					
					<th style="text-align:left;width:150px;" class="border">Bodega</th>
					<th style="text-align:left;width:150px;" class="border">Ven.</th>
					<th style="text-align:left;width:80px;" class="border">Cantidad</th>
				</tr>
			</thead>				
			<tbody>
				<?php
				$count 		= 0;
				if($objDetail)
				foreach($objDetail as $i){
					$count++;						
					echo "<tr>";
						echo "<td style='text-align:left'  colspan='2' class='border' >";
							echo ($i["itemNumber"]);
						echo "</td>";
						echo "<td style='text-align:left'  colspan='2' class='border' >";
							echo ($i["itemName"]);
						echo "</td>";												
						echo "<td style='text-align:left'  colspan='2' class='border' >";
							echo ($i["proveedorName"]);
						echo "</td>";						
						echo "<td style='text-align:left' class='border'>";
							echo ($i["warehouseName"]);								
						echo "</td>";
						echo "<td style='text-align:left' class='border'>";
							echo (date_format(date_create($i["dateExpired"]),"Y-m-d")).
							     " ".
								 ("(".$i["dateExpiredInDay"]." dias)");								
						echo "</td>";
						echo "<td style='text-align:right' class='border' >";
							echo (number_format($i["quantityExpired"],2,'.',","));
						echo "</td>";
						
					echo "</tr>";
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<th style="text-align:left"  colspan="2" class='border'>Codigo</th>
					<th style="text-align:left"  colspan="2" class='border'>Nombre</th>											
					<th style="text-align:left"  colspan="2" class='border'>Proveedor</th>					
					<th style="text-align:left"   class='border'>Bodega</th>
					<th style="text-align:left"   class='border'>Ven.</th>
					<th style="text-align:right"  class='border'>Cantidad</th>
				</tr>
			</tfoot>
		</table>
		
		
		
		<br/>		
		
		<table style="width:100%">
			<thead>
				<tr>
					<th colspan="9" ><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?> posMe</th>
				</tr>
			</thead>
		</table>
		
		
	</body>	
</html>