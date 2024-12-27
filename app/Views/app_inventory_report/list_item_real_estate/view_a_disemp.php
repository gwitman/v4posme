<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $objFirmaEncription; ?></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
		
		<?php 
		echo helper_reporteGeneralCreateStyle();
		?>
		
		
		<style>
			/* Estilo para la tabla */
			#myReport {
			  border-collapse: collapse;
			}

			#myReport th, #myReport td {
			  text-align: left;			  
			}


			/* Estilo para fijar el título de la tabla */
			#myReport thead {
			  position: sticky;
			  top: 0;
			  z-index: 1;
			}
		</style>
		
		
	</head>
	<body style="font-family:monospace;font-size:smaller;margin:0px 0px 0px 0px"> 
		
		
		<?php 
		echo helper_reporteGeneralCreateEncabezado(
			'DETALLE DE INUMEBLES',
			$objCompany->name,
			44,
			'DEL '.$objStartOn.' AL '.$objEndOn,
			"",
			"",
			"1000px"
		);
		?>
		
		
		<br/>	
		
		<?php
		
		$configColumn["0"]["Titulo"] = "Fecha de enlistamiento";
		$configColumn["0"]["FiledSouce"] = "Fecha de enlistamiento";
		$configColumn["0"]["Formato"] = "";
		$configColumn["0"]["Width"] = "80px";
		$configColumn["0"]["Total"] = false;
		$configColumn["0"]["IdTable"] 	= "myReport";		
		$configColumn["0"]["FiltrarRegistroOnLine"] = True;	
		
		$configColumn["1"]["Titulo"] = "Fecha de actualizacion";
		$configColumn["1"]["FiledSouce"] = "Fecha de actualizacion";
		$configColumn["1"]["Formato"] = "";
		$configColumn["1"]["Width"] = "80px";
		$configColumn["1"]["Total"] = false;		
		
		$configColumn["2"]["Titulo"] = "Tipo de casa";
		$configColumn["2"]["FiledSouce"] = "Tipo de casa";
		$configColumn["2"]["Formato"] = "";
		$configColumn["2"]["Width"] = "80px";
		$configColumn["2"]["Total"] = false;
		
		$configColumn["3"]["Titulo"] = "Proposito";
		$configColumn["3"]["FiledSouce"] = "Proposito";
		$configColumn["3"]["Formato"] = "";
		$configColumn["3"]["Width"] = "80px";
		$configColumn["3"]["Total"] = false;		
		
		$configColumn["4"]["Titulo"] = "Amueblado";
		$configColumn["4"]["FiledSouce"] = "Amueblado";
		$configColumn["4"]["Formato"] = "";
		$configColumn["4"]["Width"] = "80px";
		$configColumn["4"]["Total"] = false;
		
		$configColumn["5"]["Titulo"] = "Disponible";
		$configColumn["5"]["FiledSouce"] = "Disponible";
		$configColumn["5"]["Formato"] = "";
		$configColumn["5"]["Width"] = "80px";
		$configColumn["5"]["Total"] = false;		
		
		$configColumn["6"]["Titulo"] = "Ubicacion";
		$configColumn["6"]["FiledSouce"] = "Ubicacion";
		$configColumn["6"]["Formato"] = "";
		$configColumn["6"]["Width"] = "80px";
		$configColumn["6"]["Total"] = false;
		
		$configColumn["7"]["Titulo"] = "Zona";
		$configColumn["7"]["FiledSouce"] = "Zona";
		$configColumn["7"]["Formato"] = "";
		$configColumn["7"]["Width"] = "80px";
		$configColumn["7"]["Total"] = false;		
		
		$configColumn["8"]["Titulo"] = "Condominio";
		$configColumn["8"]["FiledSouce"] = "Condominio";
		$configColumn["8"]["Formato"] = "";
		$configColumn["8"]["Width"] = "80px";
		$configColumn["8"]["Total"] = false;
		
		$configColumn["09"]["Titulo"] = "Agente";
		$configColumn["09"]["FiledSouce"] = "Agente";
		$configColumn["09"]["Formato"] = "";
		$configColumn["09"]["Width"] = "80px";
		$configColumn["09"]["Total"] = false;		
		
		$configColumn["10"]["Titulo"] = "ID Encuentra 24";
		$configColumn["10"]["FiledSouce"] = "ID Encuentra 24";
		$configColumn["10"]["Formato"] = "";
		$configColumn["10"]["Width"] = "80px";
		$configColumn["10"]["Total"] = false;
		
		$configColumn["11"]["Titulo"] = "Pagina Web";
		$configColumn["11"]["FiledSouce"] = "Pagina Web";
		$configColumn["11"]["Formato"] = "";
		$configColumn["11"]["Width"] = "80px";
		$configColumn["11"]["Total"] = false;
		
		$configColumn["12"]["Titulo"] = "Link Youtube";
		$configColumn["12"]["FiledSouce"] = "Link Youtube";
		$configColumn["12"]["Formato"] = "";
		$configColumn["12"]["Width"] = "80px";
		$configColumn["12"]["Total"] = false;		
		
		$configColumn["13"]["Titulo"] = "Precio Venta";
		$configColumn["13"]["FiledSouce"] = "Precio Venta";
		$configColumn["13"]["Formato"] = "";
		$configColumn["13"]["Width"] = "80px";
		$configColumn["13"]["Total"] = false;
		
		$configColumn["14"]["Titulo"] = "Precio Renta";
		$configColumn["14"]["FiledSouce"] = "Precio Renta";
		$configColumn["14"]["Formato"] = "";
		$configColumn["14"]["Width"] = "80px";
		$configColumn["14"]["Total"] = false;		
		
		$configColumn["15"]["Titulo"] = "Area de contruccion M2";
		$configColumn["15"]["FiledSouce"] = "Area de contruccion M2";
		$configColumn["15"]["Formato"] = "";
		$configColumn["15"]["Width"] = "80px";
		$configColumn["15"]["Total"] = false;
		
		$configColumn["16"]["Titulo"] = "Area de terreno V2";
		$configColumn["16"]["FiledSouce"] = "Area de terreno V2";
		$configColumn["16"]["Formato"] = "";
		$configColumn["16"]["Width"] = "80px";
		$configColumn["16"]["Total"] = false;		
		
		$configColumn["17"]["Titulo"] = "Niveles";
		$configColumn["17"]["FiledSouce"] = "Niveles";
		$configColumn["17"]["Formato"] = "";
		$configColumn["17"]["Width"] = "80px";
		$configColumn["17"]["Total"] = false;
		
		$configColumn["18"]["Titulo"] = "Habitaciones";
		$configColumn["18"]["FiledSouce"] = "Habitaciones";
		$configColumn["18"]["Formato"] = "";
		$configColumn["18"]["Width"] = "80px";
		$configColumn["18"]["Total"] = false;
		
		$configColumn["19"]["Titulo"] = "Baños";
		$configColumn["19"]["FiledSouce"] = "Baños";
		$configColumn["19"]["Formato"] = "";
		$configColumn["19"]["Width"] = "80px";
		$configColumn["19"]["Total"] = false;		
		
		$configColumn["20"]["Titulo"] = "Baño de servicio";
		$configColumn["20"]["FiledSouce"] = "Baño de servicio";
		$configColumn["20"]["Formato"] = "";
		$configColumn["20"]["Width"] = "80px";
		$configColumn["20"]["Total"] = false;
		
		$configColumn["21"]["Titulo"] = "Baño de visita";
		$configColumn["21"]["FiledSouce"] = "Baño de visita";
		$configColumn["21"]["Formato"] = "";
		$configColumn["21"]["Width"] = "80px";
		$configColumn["21"]["Total"] = false;
		
		$configColumn["22"]["Titulo"] = "Cuarto de servicio";
		$configColumn["22"]["FiledSouce"] = "Cuarto de servicio";
		$configColumn["22"]["Formato"] = "";
		$configColumn["22"]["Width"] = "80px";
		$configColumn["22"]["Total"] = false;		
		
		$configColumn["23"]["Titulo"] = "Walk in closet";
		$configColumn["23"]["FiledSouce"] = "Walk in closet";
		$configColumn["23"]["Formato"] = "";
		$configColumn["23"]["Width"] = "80px";
		$configColumn["23"]["Total"] = false;		
		
		$configColumn["24"]["Titulo"] = "Aires";
		$configColumn["24"]["FiledSouce"] = "Aires";
		$configColumn["24"]["Formato"] = "";
		$configColumn["24"]["Width"] = "80px";
		$configColumn["24"]["Total"] = false;		
		
		$configColumn["25"]["Titulo"] = "Piscina privada";
		$configColumn["25"]["FiledSouce"] = "Piscina privada";
		$configColumn["25"]["Formato"] = "";
		$configColumn["25"]["Width"] = "80px";
		$configColumn["25"]["Total"] = false;
		
		$configColumn["26"]["Titulo"] = "Area club con piscina";
		$configColumn["26"]["FiledSouce"] = "Area club con piscina";
		$configColumn["26"]["Formato"] = "";
		$configColumn["26"]["Width"] = "80px";
		$configColumn["26"]["Total"] = false;
		
		$configColumn["27"]["Titulo"] = "Hora de visita";
		$configColumn["27"]["FiledSouce"] = "Hora de visita";
		$configColumn["27"]["Formato"] = "";
		$configColumn["27"]["Width"] = "80px";
		$configColumn["27"]["Total"] = false;		
		
		$configColumn["28"]["Titulo"] = "Acepta mascota";
		$configColumn["28"]["FiledSouce"] = "Acepta mascota";
		$configColumn["28"]["Formato"] = "";
		$configColumn["28"]["Width"] = "80px";
		$configColumn["28"]["Total"] = false;
		
		$configColumn["29"]["Titulo"] = "Diseño de propiedad";
		$configColumn["29"]["FiledSouce"] = "Diseño de propiedad";
		$configColumn["29"]["Formato"] = "";
		$configColumn["29"]["Width"] = "80px";
		$configColumn["29"]["Total"] = false;		
		
		$configColumn["30"]["Titulo"] = "Estilo de cocina";
		$configColumn["30"]["FiledSouce"] = "Estilo de cocina";
		$configColumn["30"]["Formato"] = "";
		$configColumn["30"]["Width"] = "80px";
		$configColumn["30"]["Total"] = false;
		
		$configColumn["31"]["Titulo"] = "Corretaje";
		$configColumn["31"]["FiledSouce"] = "Corretaje";
		$configColumn["31"]["Formato"] = "";
		$configColumn["31"]["Width"] = "80px";
		$configColumn["31"]["Total"] = false;		
		
		$configColumn["32"]["Titulo"] = "Plan de referido";
		$configColumn["32"]["FiledSouce"] = "Plan de referido";
		$configColumn["32"]["Formato"] = "";
		$configColumn["32"]["Width"] = "80px";
		$configColumn["32"]["Total"] = false;		
		
		$configColumn["33"]["Titulo"] = "Nombre";
		$configColumn["33"]["FiledSouce"] = "Nombre";
		$configColumn["33"]["Formato"] = "";
		$configColumn["33"]["Width"] = "80px";
		$configColumn["33"]["Total"] = false;		
		
		$configColumn["34"]["Titulo"] = "Pagina Web Link";
		$configColumn["34"]["FiledSouce"] = "Pagina Web Link";
		$configColumn["34"]["Formato"] = "";
		$configColumn["34"]["Width"] = "80px";
		$configColumn["34"]["Total"] = false;
		
		$configColumn["35"]["Titulo"] = "Otros Link";
		$configColumn["35"]["FiledSouce"] = "Otros Link";
		$configColumn["35"]["Formato"] = "";
		$configColumn["35"]["Width"] = "80px";
		$configColumn["35"]["Total"] = false;		
		
		$configColumn["36"]["Titulo"] = "Foto";
		$configColumn["36"]["FiledSouce"] = "Foto";
		$configColumn["36"]["Formato"] = "";
		$configColumn["36"]["Width"] = "80px";
		$configColumn["36"]["Total"] = false;
		
		$configColumn["37"]["Titulo"] = "Google";
		$configColumn["37"]["FiledSouce"] = "Google";
		$configColumn["37"]["Formato"] = "";
		$configColumn["37"]["Width"] = "80px";
		$configColumn["37"]["Total"] = false;		
		
		$configColumn["38"]["Titulo"] = "Exclusividad de agente";
		$configColumn["38"]["FiledSouce"] = "Exclusividad de agente";
		$configColumn["38"]["Formato"] = "";
		$configColumn["38"]["Width"] = "80px";
		$configColumn["38"]["Total"] = false;
		
		$configColumn["39"]["Titulo"] = "Moneda";
		$configColumn["39"]["FiledSouce"] = "Moneda";
		$configColumn["39"]["Formato"] = "";
		$configColumn["39"]["Width"] = "80px";
		$configColumn["39"]["Total"] = false;
				
		$configColumn["40"]["Titulo"] = "Telefono";
		$configColumn["40"]["FiledSouce"] = "Telefono";
		$configColumn["40"]["Formato"] = "";
		$configColumn["40"]["Width"] = "80px";
		$configColumn["40"]["Total"] = false;
		
		$resultado 	= helper_reporteGeneralCreateTable($objDetail,$configColumn,'0px',NULL,NULL);		
		echo str_replace("[[BASE_URL]]",base_url(),$resultado["table"]);
		?>
		
		<br/>
		
		<?php 
		echo helper_reporteGeneralCreateFirmaNotEjecuteExport(	
			$objFirmaEncription,
			"44",
			"1000px"
		);
		?>
		
		<?php
		echo 
		'
		<script>
			$(document).ready(function() {
				
				  $(document).on("click","#btnExportToExcel",function(e,o){
						  
						  // Obtener el contenido HTML de la tabla
						  var tablaHTML = 
										"<div>"+	
										document.getElementById("myReport").outerHTML + 
										"</div>";
						  var tablaHTML = $(tablaHTML);
						  
						  //Eliminar los input de filtros
						  tablaHTML.find("input").remove();	
						  
						  
						  var columnasDelete = [
							"Amueblado",
							"Amueblado",							
							"Ciudad",
							"Zona",
							"Condominio",
							"Pagina Web",
							"Link Youtube",
							"Area de contruccion M2",
							"Area de terreno V2",
							"Niveles",
							"Habitaciones",
							"Baños",
							"Baño de servicio",
							"Baño de visita",
							"Cuarto de servicio",
							"Walk in closet",
							"Aires",
							"Piscina privada",
							"Area club con piscina",
							"Hora de visita",
							"Acepta mascota",
							"Diseño de propiedad",
							"Estilo de cocina",
							"Corretaje",
							"Plan de referido",
							"Otros Link",
							"Foto",
							"Google",
							"Exclusividad de agente",
							"Pais",
							"Estado",
							"Moneda"
						  ];
						  
						  var columnRemove	   = -1;
						  for(var iel = 0; iel < columnasDelete.length; iel++)
						  {
							  columnRemove	   = -1;
							  var cantidadColumnas = $(tablaHTML.find("tr")[0]).find("th").length;
							  for(var colI = 0 ; colI < cantidadColumnas; colI++ )
							  {
								  columnRemove	   = -1;  
								  tablaHTML.find("tr").each(function()
								  {
									  
									  if(columnRemove == -1)
									  {
										  if(columnasDelete.includes( $(this).find("th:eq("+colI+")").text()))
										  {
											  columnRemove = colI;
										  }
									  }
								  
									  if(columnRemove > -1)
									  {
										$(this).find("td:eq("+columnRemove+"), th:eq("+columnRemove+")").remove();
									  }
								  });
							  }
							  
						  }
						  
						  
							
						  
						  tablaHTML 				= $(tablaHTML[0]).html();						  
						  const tabla 				= $(tablaHTML);
						  const filas 				= Array.from(tabla[0].querySelectorAll("tr"));
						  const datos 				= filas.map(fila => Array.from(fila.children).map(celda => celda.textContent));

						  
						  //Todos
						  var datos_ 				= [];
						  for(var i = 0 ; i  < datos.length; i++)
						  {
							  if(i != 1 )
							  datos_.push(datos[i]);
						  }						  
						  const grupo1 				= datos_.slice(1, datos_.length-1);
					      const hoja1 				= XLSX.utils.aoa_to_sheet([datos[0], ...grupo1]); // Encabezados + Grupo 1
						  
						  
						  //Activos
						  var grupo2 = [];
						  for(var i = 0 ; i  < grupo1.length; i++)
						  {
							  if(grupo1[i][4] == "Si" )
							  grupo2.push(grupo1[i]);
						  }						  
						  const hoja2 				= XLSX.utils.aoa_to_sheet([datos[0], ...grupo2]); // Encabezados + Grupo 1
						  
						  //No Activos
						  var grupo3 = [];
						  for(var i = 0 ; i  < grupo1.length; i++)
						  {
							  if(grupo1[i][4] == "No" )
							  grupo3.push(grupo1[i]);
						  }						  
						  const hoja3 				= XLSX.utils.aoa_to_sheet([datos[0], ...grupo3]); // Encabezados + Grupo 1
						  
						  
						  // Crear el libro de Excel
						  const libro = XLSX.utils.book_new();
						  XLSX.utils.book_append_sheet(libro, hoja1, "Todos");
						  XLSX.utils.book_append_sheet(libro, hoja2, "Activos");
						  XLSX.utils.book_append_sheet(libro, hoja3, "Desactivos");

						  // Exportar el archivo
						  XLSX.writeFile(libro, "resultado.xlsx");

						  //Decline-2024-12-27-UsarLibrary--https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js--// Crear un objeto Blob con el contenido HTML
						  //Decline-2024-12-27-UsarLibrary--https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js--var blob = new Blob([tablaHTML], { type: "application/vnd.ms-excel" });
						  //Decline-2024-12-27-UsarLibrary--https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js--
						  //Decline-2024-12-27-UsarLibrary--https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js--// Crear una URL para el objeto Blob
						  //Decline-2024-12-27-UsarLibrary--https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js--var url = URL.createObjectURL(blob);
						  //Decline-2024-12-27-UsarLibrary--https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js--
						  //Decline-2024-12-27-UsarLibrary--https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js--
						  //Decline-2024-12-27-UsarLibrary--https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js--// Crear un enlace <a> para iniciar la descarga
						  //Decline-2024-12-27-UsarLibrary--https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js--var a = document.createElement("a");
						  //Decline-2024-12-27-UsarLibrary--https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js--a.href = url;
						  //Decline-2024-12-27-UsarLibrary--https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js--a.download = "customer_list_real_estate.xls";
						  //Decline-2024-12-27-UsarLibrary--https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js--document.body.appendChild(a);
						  //Decline-2024-12-27-UsarLibrary--https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js--
						  //Decline-2024-12-27-UsarLibrary--https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js--// Simular un clic en el enlace para iniciar la descarga
						  //Decline-2024-12-27-UsarLibrary--https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js--a.click();
						  //Decline-2024-12-27-UsarLibrary--https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js--
						  //Decline-2024-12-27-UsarLibrary--https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js--// Limpiar el objeto URL
						  //Decline-2024-12-27-UsarLibrary--https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js--URL.revokeObjectURL(url);
						  
						
				  });
				
				

				  
				  
			});

		</script>
		';
		?>
		
		
	</body>	
</html>