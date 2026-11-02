<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $objFirmaEncription; ?></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		
		<?php echo helper_reporteGeneralCreateStyle(); ?>
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
								height: 60px;
							"
							
							src="<?php echo base_url(); ?>/resource/img/logos/logo-micro-finanza.jpg" 
						/>
					</th>
					<th colspan="9" style="
						text-align:right;background-color:#00628e;color:white;
						width:80%
					">COMPRAS</th>
				</tr>
				<tr>
					<th colspan="9" style="
						text-align:right;background-color:#00628e;color:white;
					"><?php echo strtoupper($objCompany->name); ?></th>
				</tr>
				<tr>
					<th colspan="9" style="
						text-align:right;background-color:#00628e;color:white;
					">LISTA DE COMPRAS DE <?php echo $startOn; ?> AL <?php echo $endOn; ?> <?php echo ($objWarehouse != null ? "EN ".$objWarehouse->name : ""); ?></th>
				</tr>
				<tr>
					<th colspan="9" style="text-align:left">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="9" style="text-align:left">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="12" style="text-align:left">
						&nbsp;
					</th>
				</tr>
			</thead>
		</table>
		
		
		<br/>
		
		
		<table style="width:1000px;order-spacing: 10px; table-layout: fixed;" id="myReport" class="pr-table" >
			<thead>
				<tr style='background-color:#00628e;color:white'>
					<th style="text-align:left;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;width:80px" class="border">Compra</th>
					<th style="text-align:left;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;width:100px" class="border">Fecha</th>
					<th style="text-align:left;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;width:100px" class="border">Moneda</th>
					<th style="text-align:left;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;width:100px" class="border">Proveedor</th>
					<th style="text-align:left;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;width:100px" class="border">Bodega</th>
					<th style="text-align:left;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;width:100px" class="border">Producto</th>
					<th style="text-align:left;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;width:350px" class="border">Descripcion</th>
					<th style="text-align:right;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;width:100px" class="border">Cantidad</th>					
					<th style="text-align:right;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;width:100px" class="border">Cost. Unitario</th>
					<th style="text-align:right;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;width:100px" class="border">Costo</th>
				</tr>
			
			</thead>				
			<tbody>
				<?php
				
				
				$costo = 0 ;
				if($objDetail)
				foreach($objDetail as $i)
				{
					$costo = $costo + $i["cost"];
					echo "<tr>";
						echo "<td style='text-align:right;border-top: 0px;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;'  class='border'>";
							echo ($i["transactionNumber"]);
						echo "</td>";
						echo "<td style='text-align:right;border-top: 0px;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;'  class='border'>";
							echo ($i["createdOn"]);
						echo "</td>";
						echo "<td style='text-align:right;border-top: 0px;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;'  class='border'>";
							echo ($i["currencyName"]);
						echo "</td>";
						echo "<td style='text-align:right;border-top: 0px;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;'  class='border'>";
							echo ($i["providerName"]);
						echo "</td>";
						echo "<td style='text-align:right;border-top: 0px;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;'  class='border'>";
							echo ($i["warehouseName"]);
						echo "</td>";
						echo "<td style='text-align:right;border-top: 0px;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;'  class='border'>";
							echo ($i["itemNumber"]);
						echo "</td>";
						echo "<td style='text-align:left;border-top: 0px;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;'  class='border'>";
							echo ($i["itemName"]);
						echo "</td>";
						echo "<td style='text-align:right;border-top: 0px;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;'  class='border'>";
							echo (number_format($i["quantity"],2,'.',','));
						echo "</td>";
						echo "<td style='text-align:right;border-top: 0px;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;'  class='border'>";
							echo (number_format($i["unitaryCost"],2,'.',','));
						echo "</td>";
						echo "<td style='text-align:right;border-top: 0px;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;'  class='border'>";
							echo (number_format($i["cost"],2,'.',','));
						echo "</td>";
					echo "</tr>";
				}
				?>
			</tbody>
			<tfoot>
			
				<tr>
					<th style="text-align:left;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;" class="border">Compra</th>
					<th style="text-align:left;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;" class="border">Fecha</th>
					<th style="text-align:left;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;" class="border">Moneda</th>
					<th style="text-align:left;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;" class="border">Proveedor</th>
					<th style="text-align:left;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;" class="border">Bodega</th>
					<th style="text-align:left;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;" class="border">Producto</th>
					<th style="text-align:left;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;" class="border">Descripcion</th>
					<th style="text-align:right;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;" class="border">Cantidad</th>					
					<th style="text-align:right;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;" class="border">Cost. Unitario</th>
					<th style="text-align:right;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;" class="border"><?php echo number_format($costo,2,'.',',');  ?></th>
					
				</tr>
				
				
			</tfoot>
		</table>
		
		
		<br/>		
		
		<table style="width:100%">
			<thead>
				<tr>
					<th colspan="12" ><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?> posMe</th>
				</tr>
			</tbody>
		</table>
		
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<script>
			$(document).ready(function() {


				  // Arreglo para almacenar los filtros
				  var filtros = '.$filterColumn.';
				  

				  // Aplicar el filtro en cada columna al escribir en el input
				  $("#myReport thead input").on("keyup", function() {
					var index = $(this).data("index");
					var value = $(this).val().trim().toLowerCase();
					filtros[index] = value;
					filtrarTabla();
				  });
				  
				  
				  //// Función para filtrar la tabla
				  function filtrarTabla() {
					$("#myReport tbody tr").each(function() {
					  var mostrar = true;
					  $(this).find("td").each(function(index) {
						var cellText 	= $(this).text().trim().toLowerCase();
						var filterValue = filtros[index];
						
						if(filterValue)
						filterValue = filterValue.toLowerCase();
						
						
						if (filterValue) 
						{
							  if (filterValue.startsWith(">")) 
							  {
								var filterNumber = parseFloat(filterValue.substr(1));
								var cellNumber = parseFloat(cellText);
								if (isNaN(filterNumber) || isNaN(cellNumber) || cellNumber <= filterNumber) {
								  mostrar = false;
								  return false; // salir del bucle interno
								}
							  }
							  else if (filterValue.startsWith("<")) 
							  {
								var filterNumber = parseFloat(filterValue.substr(1));
								var cellNumber = parseFloat(cellText);
								if (isNaN(filterNumber) || isNaN(cellNumber) || cellNumber >= filterNumber) {
								  mostrar = false;
								  return false; // salir del bucle interno
								}
							  } 
							  //else if (cellText !== filterValue) 
							  else if (!cellText.includes(filterValue)) 
							  {
								mostrar = false;
								return false; // salir del bucle interno
							  }
						}
						
					  });
					  $(this).toggle(mostrar);
					});
				  }
				  
				  
			});

		</script>


		<?php echo helper_reporteGeneralCreateAcciones(); ?>
	</body>	
</html>