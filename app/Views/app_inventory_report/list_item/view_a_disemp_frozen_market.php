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
					<th  style=""  class="border">Codigo</th>
					<th  style=""  class="border">Nombre</th>						
					<th  style=""  class="border">Categoria</th>	
					
					<th  style="" class="border">Cantidad</th>
					<th  style="" class="border">Costo</th>
					<th  style="" class="border">Costo T.</th>
					<th  style="" class="border">Precio</th>						
					<th  style="" class="border">Fisico</th>		
				</tr>
				
			</thead>				
			<tbody>
				<?php
				$count 			= 0;
				$costoTotal		= 0;
				$precioTotal 	= 0;
				$quantityTotal	= 0;
				
				if($objDetail){
					$category   = $objDetail[0]["categoryName"];
					
					foreach($objDetail as $i){
						$count++;	
						$costoTotal 	= $costoTotal +  ($i["cost"] * $i["quantity"] );
						$precioTotal 	= $precioTotal +  ($i["price"] * $i["quantity"] );
						$quantityTotal	= $quantityTotal +  $i["quantity"];
					
						echo "<tr  >";
							echo "<td style='text-align:left;'    class='border' >";
								echo (substr($i["itemNumber"],-15));
							echo "</td>";
							echo "<td style='text-align:left'   class='border' >";
								echo ($i["itemName"]);
							echo "</td>";		
							echo "<td style='text-align:left'   class='border' >";
								echo ($i["categoryName"]);
							echo "</td>";	
							
							echo "<td style='text-align:right;'  class='border' >";
								echo number_format($i["quantity"],2,'.',',');
							echo "</td>";
							echo "<td  style='text-align:right' class='border'>";
								echo number_format( $i["cost"],2,'.',',');
							echo "</td>";
							echo "<td style='text-align:right' class='border'>";
								echo (number_format($i["cost"] * $i["quantity"],2,'.',',' ));;
							echo "</td>";
							echo "<td style='text-align:right' class='border' >";
								echo (number_format( $i["price"],2,'.',','));
							echo "</td>";	
							echo "<td style='text-align:right' class='border'  >";								
							echo "</td>";					
						echo "</tr>";
					}
				}
				?>
			</tbody>
			<tfoot>
				
				<tr style="background-color:#00628e;color:white;">
					<!--812-->					
					<th  style=""  class="border">Codigo</th>
					<th  style=""  class="border">Nombre</th>						
					<th  style=""  class="border">Categoria</th>	
					
					<th  style="" class="border"><?php echo number_format($quantityTotal,2,'.',',');  ?></th>
					<th  style="" class="border">Costo</th>
					<th  style="" class="border"><?php echo number_format($costoTotal,2,'.',',');  ?></th>
					<th  style="" class="border"><?php echo number_format($precioTotal,2,'.',',');  ?></th>						
					<th  style="" class="border">Fisico</th>		
				</tr>
				
				
			</tfoot>
		</table>

		
		
		<br/>		

		<table style="width:100%">
			<thead>
				<tr>
					<th colspan="11" ><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?> posMe</th>
				</tr>
				<tr>
					<th colspan="'.$column.'" ><a id="btnExportToExcel" href="#" >export to excel</a></th>
				</tr>
			</thead>
		</table>
	
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<script>
			$(document).ready(function() {
				
				  $(document).on("click","#btnExportToExcel",function(e,o){
					  
						  
						  // Obtener el contenido HTML de la tabla
						  var tablaHTML = 
										"<div>"+	
										document.getElementById("myReport").outerHTML + 
										"</div>";
						  var tablaHTML = $(tablaHTML);
						  
						  tablaHTML.find("input").remove();						  
						  tablaHTML 	= $(tablaHTML[0]).html();

						  // Crear un objeto Blob con el contenido HTML
						  var blob = new Blob([tablaHTML], { type: "application/vnd.ms-excel" });

						  // Crear una URL para el objeto Blob
						  var url = URL.createObjectURL(blob);


						  // Crear un enlace <a> para iniciar la descarga
						  var a = document.createElement("a");
						  a.href = url;
						  a.download = "list_item.xls";
						  document.body.appendChild(a);

						  // Simular un clic en el enlace para iniciar la descarga
						  a.click();

						  // Limpiar el objeto URL
						  URL.revokeObjectURL(url);
						  
						
				  });
				
				

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
		
		
	</body>	
</html>