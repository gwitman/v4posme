<?php
//posme:2023-02-27
function helper_reporteGeneralCreateStyle(){
	
		$result =  
		'
		<style>
			table, td, tr, th {
				border-collapse: collapse;
			}
			
			.border {
				border-color:black;
				border:solid 1px black;						
			}
			
		</style>
		';
		
		return $result;
}



function helper_reporteGeneralCreateEncabezado($titulo,$company,$countColumn,$titulo2,$titulo3,$titulo4,$width,$imagenOtherLine = NULL){
	
		$imagenOtherLine = $imagenOtherLine == NULL? false: $imagenOtherLine;
		$numerLine		 = $imagenOtherLine ? 0 : 3;
		
		$resultado = "";
		
		if($imagenOtherLine == true){
			$resultado = $resultado.
					'
					<table  style="	width:'.$width.';border-spacing: 10px;"	>
						<tr>
							<th colspan="'.$countColumn.'" rowspan="1" style="text-align:center;width:130px">
								<img width="220" height="110" 						
									style="
										width: 220px;
										height: 110px;
									"
									
									src="'.base_url().'/resource/img/logos/logo-micro-finanza.jpg" 
								/>
							</th>
						</tr>
					</table>
					';			
		}
		
		
		$resultado = $resultado.
		'
		<table style="width:'.$width.';border-spacing: 10px;" >
			<thead>
				<tr>
					';
					
				
		if($imagenOtherLine == false){
			
			$resultado = $resultado.
					'<th colspan="3" rowspan="5" style="text-align:left;width:130px">
						<img width="120" height="110" 						
							style="
								width: 120px;
								height: 110px;
							"
							
							src="'.base_url().'/resource/img/logos/logo-micro-finanza.jpg" 
						/>
					</th>';					
		}
		
		
					
		$resultado = $resultado.'
					<th colspan="'.($countColumn-$numerLine).'" style="
						text-align:Left;background-color:#68c778;color:black;
						width:80%
					">'.$titulo.'</th>
				</tr>
				
				<tr>
					<th colspan="'.($countColumn-$numerLine).'" style="
						text-align:Left;background-color:#00628e;color:white;
					">'.strtoupper($company).'</th>
				</tr>
				<tr>';
				
					if ($titulo2 == "")
						$resultado = $resultado. '<th colspan="'.($countColumn-$numerLine).'" style="text-align:Left">&nbsp;</th>';
					else 
						$resultado = $resultado. '<th colspan="'.($countColumn-$numerLine).'" style="text-align:Left;background-color:#00628e;color:white;">'.$titulo2.'</th>';
					
					$resultado = $resultado.'
				</tr>
				<tr>';
				
					if ($titulo3 == "")
						$resultado = $resultado. '<th colspan="'.($countColumn-$numerLine).'" style="text-align:Left">&nbsp;</th>';
					else 
						$resultado = $resultado. '<th colspan="'.($countColumn-$numerLine).'" style="text-align:Left;background-color:#00628e;color:white;">'.$titulo3.'</th>';
					
					
					$resultado = $resultado. '
				</tr>
				<tr>';
				
					if ($titulo4 == "")
						$resultado = $resultado. '<th colspan="'.($countColumn-$numerLine).'" style="text-align:Left">&nbsp;</th>';
					else 
						$resultado = $resultado. '<th colspan="'.($countColumn-$numerLine).'" style="text-align:Left;background-color:#00628e;color:white;">'.$titulo4.'</th>';
					
					
					$resultado = $resultado.'
				</tr>
				<tr>
					<th colspan="'.($countColumn).'" style="text-align:left">
						&nbsp;
					</th>
				</tr>
			</thead>
		</table>
		';
		
		return $resultado;
}



function helper_reporteGeneralCreateFirma($firma,$column,$width){
	
		$filterColumn = "[ \"\" ";
		for($i = 0 ; $i < ($column - 1); $i++)
		{
			$filterColumn = $filterColumn.",\"\" ";	
		}
		$filterColumn = $filterColumn."]";
		
		$result = 
		'
		<table style="width:'.$width.'">
			<thead>
				<tr>
					<th colspan="'.$column.'" >'.date("Y-m-d H:i:s").' '.$firma.' posMe</th>
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
						  a.download = "data_export.xls";
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
		
	
		';
		
		echo $result;
}

function helper_reporteGeneralCreateFirmaNotEjecuteExport($firma,$column,$width){
	
		$filterColumn = "[ \"\" ";
		for($i = 0 ; $i < ($column - 1); $i++)
		{
			$filterColumn = $filterColumn.",\"\" ";	
		}
		$filterColumn = $filterColumn."]";
		
		$result = 
		'
		<table style="width:'.$width.'">
			<thead>
				<tr>
					<th colspan="'.$column.'" >'.date("Y-m-d H:i:s").' '.$firma.' posMe</th>
				</tr>
				<tr>
					<th colspan="'.$column.'" ><a id="btnExportToExcel" href="#" >export to excel</a></th>
				</tr>
			</thead>
		</table>
		
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<script>
			$(document).ready(function() {
				
				
				  // Arreglo para almacenar los filtros
				  var filtros = '.$filterColumn.';
				  var index   = 0;
				  

				  // Aplicar el filtro en cada columna al escribir en el input
				  $("#myReport thead input").on("keyup", function() {
					index 			= $(this).data("index");
					var value 		= $(this).val().trim().toLowerCase();
					filtros[index] 	= value;
					filtrarTabla();
				  });
				  
				  
				  //// Función para filtrar la tabla
				  function filtrarTabla() 
				  {
					$("#myReport tbody tr").each(function() {
					  var mostrar = true;
					  $(this).find("td").each(function(tdindex) {
						  
						if(tdindex != index)
							return;
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
		
		';
		
		echo $result;
}



//$resultado["columnas"] 	= 0;
//$resultado["width"] 		= 0;
//$resultado["table"] 		= "";
function helper_reporteGeneralCreateTable($objDetail,$configColumn,$widht,$titulo = NULL,$backgournd = NULL,$color = NULL){
	
	
	
	
	//iniciarlizar columnas
	$cantidadColumnas 		= 0;
	$resultado["columnas"] 	= 0;
	$resultado["width"] 	= 0;
	$resultado["table"] 	= "";
	$backgournd 			= $backgournd == NULL ? "00628e": $backgournd;
	$color 					= $color == NULL ? "white": $color;
	$idTable				= "MyTable";
	$filtrarRegistroOnLine	= false;
	
	
	
	foreach($configColumn as $key => $value){
		$cantidadColumnas = $cantidadColumnas + 1;
		$configColumn[$key]["Titulo"] 					= array_key_exists("Titulo",$value) ? $value["Titulo"] : "" ;
		$configColumn[$key]["TituloFoot"] 				= array_key_exists("TituloFoot",$value) ? $value["TituloFoot"] : "" ;
		$configColumn[$key]["FiledSouce"] 				= array_key_exists("FiledSouce",$value) ? $value["FiledSouce"] : "" ;
		$configColumn[$key]["Colspan"] 					= array_key_exists("Colspan",$value) ? $value["Colspan"] : "1" ;
		$configColumn[$key]["Formato"] 					= array_key_exists("Formato",$value) ? $value["Formato"] : "" ;
		$configColumn[$key]["Total"] 					= array_key_exists("Total",$value) ? $value["Total"] : False ;
		$configColumn[$key]["Alineacion"] 				= array_key_exists("Alineacion",$value) ? $value["Alineacion"] : "Left" ;
		$configColumn[$key]["TotalValor"] 				= array_key_exists("TotalValor",$value) ? $value["TotalValor"] : 0 ;
		$configColumn[$key]["CantidadRegistro"]			= array_key_exists("CantidadRegistro",$value) ? $value["CantidadRegistro"] : 0 ;
		$configColumn[$key]["FiledSoucePrefix"] 		= array_key_exists("FiledSoucePrefix",$value) ? $value["FiledSoucePrefix"] : "" ;
		$configColumn[$key]["Width"] 					= array_key_exists("Width",$value) ? $value["Width"] : "auto" ;
		$configColumn[$key]["AutoIncrement"] 			= array_key_exists("AutoIncrement",$value) ? $value["AutoIncrement"] : False ;
		$configColumn[$key]["IsUrl"] 					= array_key_exists("IsUrl",$value) ? $value["IsUrl"] : False ;
		$configColumn[$key]["FiledSouceUrl"] 			= array_key_exists("FiledSouceUrl",$value) ? $value["FiledSouceUrl"] : "" ;
		$configColumn[$key]["Url"] 						= array_key_exists("Url",$value) ? $value["Url"] : "" ;
		$configColumn[$key]["FiledSoucePrefixCustom"] 	= array_key_exists("FiledSoucePrefixCustom",$value) ? $value["FiledSoucePrefixCustom"] : "" ;
		$configColumn[$key]["Promedio"] 				= array_key_exists("Promedio",$value) ? $value["Promedio"] : False ;
		$configColumn[$key]["Alineacion"] 				= $configColumn[$key]["Formato"] == "Number"? "Right": "Left";
		$idTable										= array_key_exists("IdTable",$value) ? $value["IdTable"] : $idTable ;
		$filtrarRegistroOnLine							= array_key_exists("FiltrarRegistroOnLine",$value) ? $value["FiltrarRegistroOnLine"] : $filtrarRegistroOnLine;
	}
	
	
	$widthTemporal = 0;
	$table  = "";
	$table2 = "";
	$table3 = "";
	
	//Armar encabezado
	foreach($configColumn as $key => $value ){
		$widthTemporal = $widthTemporal + str_replace("px","",$value['Width']);
		
		$table2 = $table2.'<th nowrap style="text-align:left;width:'.$value['Width'].'" colspan="'.$value['Colspan'].'" class="border"  >'.$value['Titulo'].'</th>';
	}
	
	$couterIndexColumn = 0;
	foreach($configColumn as $key => $value ){
		$widthTemporal = $widthTemporal + str_replace("px","",$value['Width']);
		
		$table3 = 
		$table3.
		'<th nowrap style="text-align:left;width:'.$value['Width'].'" colspan="'.$value['Colspan'].'" class="border"  >
			<input style="width:80px;height: 10px;margin-top: 5px;margin-bottom: 5px;"  type="text" placeholder="Filtrar" data-index="'.$couterIndexColumn.'" />
		</th>';
		$couterIndexColumn++;
	}
	$widthTemporal = $widthTemporal."px";
	
	
	//Armar titulo
	$table1 =  
	'<table id="'.$idTable.'"  style="
			width:'.$widthTemporal.';order-spacing: 10px;
		" >
			<thead>
				';
					
				if($titulo != "" && $titulo != NULL){
					$table1 = $table1."
						<tr style='background-color:#".$backgournd.";color:".$color.";'>
							<th colspan='".$cantidadColumnas."' >".$titulo."</th>
						</tr>
					";
				};
				
	//Unir Titulo y encabezado de la tabla
	$table =  	$table.
				$table1.
				'<tr style="background-color:#'.$backgournd.';color:'.$color.';">'.
				$table2.
				'</tr>'.
				
				(
					$filtrarRegistroOnLine == true ? 
					(
					'<tr style="background-color:#'.$backgournd.';color:'.$color.';">'.
					$table3.
					'</tr>'
					)
					: 
					""
				)
				
				;
		
	//Fin de encabezado
	$table =  $table.'
			</tr>				
		</thead>				
		<tbody>
		';
		
	
		
	//Si no hay datos	
	if(!$objDetail){
		//Resultado;
		$resultado["columnas"] 	= $cantidadColumnas;
		$resultado["width"] 	= $widthTemporal;
		$resultado["table"] 	= 0;
		
		return $resultado;
	}
	
	
	//Armar cuerpo
	$autoIncrementValue 	= 0;
	$counterRow				= 0;
	if($objDetail)
	foreach($objDetail as $i){
		$autoIncrementValue++;
		$counterRow++;
		$table = $table. "<tr>";
		
		
		foreach($configColumn as $key => $value ){
			
			$table = $table. "<td nowrap style='text-align:".$value["Alineacion"]."' colspan='".$value['Colspan']."'  class='border'>";
				
				
				$valueField 			= $value["FiledSouce"] == "" ? "" : ($i[$value["FiledSouce"] ] );
				$tipoData				= array_key_exists("Formato",$value) ? $value["Formato"] : "" ;
				$sumaryzar				= array_key_exists("Total",$value) ? $value["Total"] : False ; 
				$prefix					= array_key_exists("FiledSoucePrefix",$value) ? $value["FiledSoucePrefix"] : "" ;
				$FiledSoucePrefixCustom	= array_key_exists("FiledSoucePrefixCustom",$value) ? $value["FiledSoucePrefixCustom"] : "" ;
				
				
				$autoIncrement			= array_key_exists("AutoIncrement",$value) ? $value["AutoIncrement"] : False ;
				$Promedio				= array_key_exists("Promedio",$value) ? $value["Promedio"] : False ;
				
				$IsUrl					= array_key_exists("IsUrl",$value) ? $value["IsUrl"] : False ;					
				$Url					= array_key_exists("Url",$value) ? $value["Url"] : "" ;	
				$FiledSouceUrl			= array_key_exists("FiledSouceUrl",$value) ? $value["FiledSouceUrl"] : "" ;	
				
				
				$valueFieldPrefixValue 	= "";
				$valueFieldUrlValue 	= "";
				
				if($prefix != "")
				$valueFieldPrefixValue 	= ($i[$value["FiledSoucePrefix"] ]);
			
				if($FiledSouceUrl != "")
				$valueFieldUrlValue 	= ($i[$value["FiledSouceUrl"] ]);
				
				
				//Formato al valor
				if($tipoData == "Number"){
					$valueField = number_format($valueField,2,'.',',');						
				}
				else if($tipoData == "Date"){
					$valueField = (date_format(date_create($valueField),"Y-m-d"));
					$valueField = str_replace("-0001-11-30","0001-11-30",$valueField);
				}
				
				//Sumaryzar datos
				if($sumaryzar){
					$configColumn[$key]["TotalValor"] 		= $value["TotalValor"] + str_replace(",","",$valueField);
					$configColumn[$key]["CantidadRegistro"] = $counterRow;
					
				}
				if($Promedio){
					$configColumn[$key]["TotalValor"] = $value["TotalValor"] + str_replace(",","",$valueField);
					$configColumn[$key]["CantidadRegistro"] = $counterRow;
				}
				
				//Prefix					
				if($prefix != ""){						
					$valueField 			= $valueFieldPrefixValue." ".$valueField;
				}
				
				if($FiledSoucePrefixCustom != ""){
					$valueField 			= $FiledSoucePrefixCustom." ".$valueField;
				}
				
				if($autoIncrement){
					$valueField = $autoIncrementValue;
				}
				
				if($IsUrl){
					$valueField = "<a href='".$Url.$valueFieldUrlValue."' >".$valueField."</a>";
				}
				
				
				
				$table		= $table. $valueField;
			$table = $table. "</td>";								
			
			
		}
		$table = $table. "</tr>";			
	}
		
	//Armar Pie de Tabla
	$table = $table.'</tbody>';
	
	$table = $table.'<tfoot><tr>';
			
				foreach($configColumn as $key => $value ){
					$table = $table.'<th nowrap style="text-align:'.$value['Alineacion'].'" colspan="'.$value['Colspan'].'" class="border"  >';
					
						$filedValue = $value['TituloFoot'];
						$sumaryzar	= $value["Total"] ;
						$totalValor	= $value["TotalValor"] ;
						$prefix		= $value["FiledSoucePrefix"] ;
						$Promedio	= $value["Promedio"] ;
						
						if($filedValue == ""){
							$filedValue = "	&nbsp;";
						}
						
						if($sumaryzar){
							$filedValue = $filedValue."SUMA&nbsp;&nbsp;=&nbsp;&nbsp;".number_format($totalValor,2,'.',',')."<br/>";
						}
						if($Promedio){
							$filedValue = $filedValue."PROMEDIO&nbsp;&nbsp;=&nbsp;&nbsp;".number_format($totalValor / $counterRow  ,2,'.',',') ;
						}
						
						
						if($prefix != ""){
							$valueFieldPrefixValue 	= ( $i[ $value["FiledSoucePrefix"] ] );
							$filedValue = $valueFieldPrefixValue." ".$filedValue;
						}
						
						$table = $table.$filedValue;
					$table = $table.'</th>';
				}
				
	$table = $table.'</tr></tfoot>';	
	$table = $table.'</table>';
		
	
	//Resultado;
	$resultado["columnas"] 		= $cantidadColumnas;
	$resultado["width"] 		= $widthTemporal;
	$resultado["table"] 		= $table;
	$resultado["configColumn"] 	= $configColumn;
	
	return $resultado;
}

function helper_reporteGeneralCreateTableVertical($objDetail,$configColumn,$maxColmun,$widht){
	
	
	
    $widht         = str_replace("px","", $widht);
	$table         =  
	'<table style="
			width:'.$widht.'px;order-spacing: 10px;
		" >
			<tbody>
			';
			
		
	
			
			foreach($configColumn as $key => $value ){
				
				$valueField 			= ( $objDetail[ $value["FiledSouce"] ] );					
				$tipoData				= $value["Formato"] ;
				$sumaryzar				= $value["Total"] ;
				$titulo					= $value["Titulo"] ;				
				$prefix					= $value["FiledSoucePrefix"] ;
				$width					= $value["Width"] ;
				$width                  = str_replace("px","", $width);
			
				
				
				//Formato al valor
				if($tipoData == "Number"){
					$valueField = number_format($valueField,2,'.',',');						
				}
				else if($tipoData == "Date"){
					$valueField = (date_format(date_create($valueField),"Y-m-d"));
					$valueField = str_replace("-0001-11-30","0001-11-30",$valueField);
				}
			
				
				//Sumaryzar datos
				if($sumaryzar){
					$configColumn[$key]["TotalValor"] = $value["TotalValor"] + $valueField;
				}
				
				//Prefix					
				if($prefix != ""){
					$valueFieldPrefixValue 	= ( $i[ $value["FiledSoucePrefix"] ] );
					$valueField 			= $valueFieldPrefixValue." ".$valueField;
				}
			
				
				
				
				$table = $table. "<tr>";
				
					$table = $table. "<td nowrap style='text-align:".";width:167px;background-color:#00628e;color:white;' colspan='2'   >";
						$table		= $table.$titulo;
					$table = $table. "</td>";
					
				
				
					if($tipoData == "Number"){
					 
						$table = $table. "<td nowrap style='text-align:".$value["Alineacion"].";width:180px' >";
							$table		= $table. $valueField;
						$table = $table. "</td>";
						
						
						$table = $table. "<td nowrap style='text-align:".$value["Alineacion"].";width:".($widht-180-167)."px' colspan='".($value['Colspan']-2)."'  >";
							$table		= $table."	&nbsp;";
						$table = $table. "</td>";
					
						
					}
					else{
					 
						$table = $table. "<td nowrap style='text-align:".$value["Alineacion"].";width:auto' colspan='".($value['Colspan']-1)."'  >";
						
							$table		= $table. $valueField;
						$table = $table. "</td>";
						
					}
					
				
					
					
					
				$table = $table. "</tr>";	
				
			}
			
				
		$table = $table.'
			</tbody>
		</table>
		';
		
	return $table;
}

function helper_generateTableParaPdf($confiDetalle,$arrayDetalle){
	$cuerpo = "";
    $colun  = 0;
	$rowin  = 0;
    foreach($arrayDetalle as $row){
		
            $colun  		= 0;
			$colunCantidad 	= count($row);
			$nuevaFila		= 0;
            foreach ($row as $col => $key){				
			
			    //encabezado
				if($rowin == 0){
					
					//Mostrar los productos en una sola fila
					if($colun == 0){
						$cuerpo 		= $cuerpo."<tr >";							
					}	
						
					$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style"]." colspan='".$confiDetalle[$colun]["colspan"]."' >";
					$cuerpo = $cuerpo." ".$key;
					$cuerpo = $cuerpo."</td>";
						
					if($colun == $colunCantidad ){
						$cuerpo 	   = $cuerpo."</tr >";
					}
					
				}
				//datos
				else{
					
					if( $confiDetalle[$colun]["nueva_fila_row_data"] ==  1 ){
						$cuerpo 		= $cuerpo."<tr >";							
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";
						
						$cuerpo 		= $cuerpo."</tr >";	
						$nuevaFila		= 1;						
					}	
					else{
							
						
						if($nuevaFila == 1)
							$cuerpo 	= $cuerpo."<tr >";								
						
						if($rowin > 0 && $colun == 0)
							$cuerpo 	= $cuerpo."<tr >";								
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";								
						
						if($colun == $colunCantidad)
							$cuerpo 	= $cuerpo."</tr >";								
						
						$nuevaFila = 0;
							
					}
						
				
				}
				
                
				
                $colun++;
            }
			
			$rowin++;
            
    }
	
	return $cuerpo;
}

function helper_reporteA4TransactionMasterInvoiceGlobalPro(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo,    
	$objDetail,
    $objParameterTelefono, /*telefono*/
	$objEmployerNatural , /*venedor*/
	$objTelefonoEmployer , /*telefono cliente*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    		= PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
	$font_size1   	= "18px";
	$border_left 	= "border-left: 1px solid black;";
	$border_right 	= "border-right: 1px solid black;";
	$border_top 	= "border-top: 1px solid black;";
	$border_bottom 	= "border-bottom: 1px solid black;";
	$border_radius	= "border-radius: 10px;";
	$border_colapse = "border-collapse:separate;";
	
	
	
    $type    		= pathinfo($path, PATHINFO_EXTENSION);
    $data    		= file_get_contents($path);
    $base64  		= 'data:image/' . $type . ';base64,' . base64_encode($data);
    $numberDocument = strpos($objTransactionMastser->transactionNumber ,"FAC") === false ?   str_replace("PRF","PROFORMA No. ",strtoupper($objTransactionMastser->transactionNumber)) : str_replace("FAC","FACTURA No. ",strtoupper($objTransactionMastser->transactionNumber)) ;
	$tipoDocumento  = strpos($objTransactionMastser->transactionNumber ,"FAC") === false ?   "PROFORMA": "FACTURA";
	$clientName		= ($objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName  :  $objTransactionMasterInfo->referenceClientName) ;
	$telefono  		= $objEntidadCustomer->phoneNumber;
	
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
						
                        @page {       
                          size: Legal;   
						
						  
                          margin-top:25px;
                          margin-left:25px;
                          margin-right:20px;
						  margin-bottom: 25px;
						  
						  
						  padding-top: 0px;
						  padding-right: 0px;
						  padding-bottom: 0px;
						  padding-left: 0px;
						  
                        }
                        table{
                          font-size: xx-small;
                          font-family: sans-serif, monaco, monospace;						 
						  border-collapse: collapse;
                        }
						td{
                          font-size: xx-small;
                          font-family: sans-serif, monaco, monospace;
						  /*border: 1px solid black;*/
						  border-collapse: collapse;
                        }
                      </style>
                    </head>
        
                    <body>
					";
        
		$f_html = 	  "
                      <table style='width:98%'>
                        <tr>
						  <td  style='text-align:center;width:500px;text-align:left'>
                            <img  src='".$base64."' width='300'  >
                          </td>
                          <td  style='text-align:center;'>
									<table style='width:100%'>
										<tr>
										  <td  style='text-align:center;font-size:".$font_size1."; font-weight: bold;'>
											". $numberDocument ."
										  </td>
										</tr>
										
										<tr>
										  <td style='text-align:center'>
											Carretera Masaya, Frente al colegio Teresiano 
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											Edificio Delta RUC: 888-080396-0001K
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											Teléfono: 2223-2314
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											AUT.DGI. No. ASFC 02/0009/02/2023/2
										  </td>
										</tr>
									</table>
                          </td>
                        </tr>
					</table>
					
						";
    	   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
			
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td style='text-align:center' colspan='3'>[[TIPO_DOCUMENTO]]</td>
						<td style='text-align:center;width:70px'>".$causalName."</td>
						<td style='text-align:center;width:70px; font-weight: bold;'>Vendedor</td>
						<td style='text-align:center; font-weight: bold;'>".$objEmployerNatural->firstName." ".$objEmployerNatural->lastName."</td>
					</tr>
					<tr>
						<td style='text-align:center; font-weight: bold;".$border_left.$border_right.$border_top.$border_bottom."'>Cliente</td>
						<td style='text-align:left;".$border_left.$border_right.$border_top.$border_bottom."' colspan='3'>".$clientName."</td>						
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."'>Cedula</td>
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."'>".$objEntidadCustomer->identification."</td>
					</tr>
					<tr>
						<td style='text-align:center;width:70px; font-weight: bold;".$border_left.$border_right.$border_top.$border_bottom."' >Telefono</td>
						<td style='text-align:left;".$border_left.$border_right.$border_top.$border_bottom."' colspan='3'>".$telefono."</td>						
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."' >Fecha</td>
						<td style='text-align:center;width:250px;".$border_left.$border_right.$border_top.$border_bottom."' >".$objTransactionMastser->createdOn."</td>
					</tr>
				</table>
			";
			
		   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
			
	$f_html = $f_html."
			<table style='width:98%' > 
				<tr style='background-color: #ccc;'>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >ARTICULO</td>
					<td style='text-align:center; font-weight: bold;"."' >DESCRIPCION</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Pre. Unit.</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Cant.</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Descuento</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >TOTAL</td>
				<tr>
			";
		
	$total 		= 0;
	$subtotal 	= 0;
	$iva 		= 0;
	$descuento 	= 0;
	
	for($i = 0 ; $i < 22 ; $i++)
	{
		$count = count($objDetail);
		if($i < $count)
		{
			$total 		= $total + $objDetail[$i]->amount ;
			$iva 		= $iva + ($objDetail[$i]->tax1 * $objDetail[$i]->quantity);
			$subtotal 	= $subtotal + ($objDetail[$i]->unitaryPrice * $objDetail[$i]->quantity);
			
			$f_html = $f_html."			
				<tr>
					<td style='text-align:left;width:70px' >".$objDetail[$i]->itemNumber."</td>
					<td style='text-align:left;' >".$objDetail[$i]->itemNameLog."</td>
					<td style='text-align:center;width:70px' >".$objCurrency->simbol." ".number_format(round($objDetail[$i]->unitaryPrice,2),2,".",",")."</td>
					<td style='text-align:center;width:70px' >".number_format(round($objDetail[$i]->quantity,2),2,".",",")."</td>
					<td style='text-align:center;width:70px' >".$objCurrency->simbol." 0.00</td>
					<td style='text-align:center;width:70px' >".$objCurrency->simbol." ".number_format(round($objDetail[$i]->unitaryPrice,2)  * round($objDetail[$i]->quantity,2)   ,2,".",",")."</td>
				<tr>
			";
		}
		else
		{
			$f_html = $f_html."			
				<tr>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
				<tr>
			";
		}
	}
				
	$f_html = $f_html."
			</table>
			";
		   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
				
	
	$f_html = $f_html."
				  <table style='width:98%;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >
						<tr>
							<td  style='text-align:left;vertical-align:top;widht:auto;' >
								<table style='width:100%;'>
									<tr>
										<td style='font-size:".$font_size1.";font-weight:bold;' >Nota al cliente:</td>
									</tr>
									<tr>
										<td style='height:60px;text-justify: auto;  '>". substr($objTransactionMastser->note,0,450) ."</td>
									</tr>
								</table>
							</td>
							<td style='width:200px;vertical-align:top;' >									
								<table style='width:100%;'>
									<tr>										
										<td style='text-align:right;vertical-align: top;font-size:".$font_size1."; font-weight: bold;'>Totales:</td>								
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>																
									<tr>										
										<td style='text-align:right;padding-top:5px;'>No se aceptan devoluciones.</td>										
									</tr>						
								</table>
								
							</td>
							<td  style='text-align:left;vertical-align:top;widht:100px;' >
								<table style='width:100%;'>
									<tr>										
										<td style='width:70px'>Subtotal</td>
										<td style='text-align:right;width:70px'>".$objCurrency->simbol." ".number_format ( round($subtotal,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>
										<td>Descuentos</td>
										<td style='text-align:right'>".$objCurrency->simbol." 0.00</td>
									</tr>
									<tr>
										<td>Subtotal</td>
										<td style='text-align:right'>".$objCurrency->simbol." ".number_format ( round($subtotal,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>
										<td>IVA</td>
										<td style='text-align:right'>".$objCurrency->simbol." ".number_format ( round($iva,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>
										<td style=' font-weight: bold;'>TOTAL:</td>
										<td style='text-align:right'>".$objCurrency->simbol." ".number_format ( round($objTransactionMastser->amount,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>										
										<td></td>
										<td></td>
									</tr>	
									<tr>
										<td colspan='2'>*=Equipo seminuevo</td>
									</tr>									
								</table>
							</td>
							
						</tr>
						
						
				   </table>";
				   
				   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
			
	$f_html = $f_html."
				  <table style='width:98%' >
						<tr>
							<td style='text-align:center;text-decoration:underline;font-weight: bold;font-size:14px'>
								Al comprar tu equipo en GLOBAL PRO,puedes cambiarlo por uno superior cuando gustes!
							</td>
						</tr>
				   </table>";
	
	
	$f_html_copia 		= $f_html;
	$f_html_original 	= $f_html;
	$f_html_credito		= $f_html;
	
	$f_html_original 		= str_replace("[[TIPO_DOCUMENTO]]","ORIGINAL",$f_html_original);
	$f_html_copia 			= str_replace("[[TIPO_DOCUMENTO]]","COPIA",$f_html_copia);
	$f_html_credito 		= str_replace("[[TIPO_DOCUMENTO]]","COPIA",$f_html_copia);
	
	if($tipoDocumento == "PROFORMA")
	{
		$f_html				= $f_html_original;
	}
	else 
	{
		if(strtoupper($causalName) == strtoupper( "CREDITO" ) )
		$f_html				= $f_html_original."<div style='page-break-before:always;' ></div>".$f_html_copia."<div style='page-break-before:always;' ></div>".$f_html_credito;
		else 
		$f_html				= $f_html_original."<div style='page-break-before:always;' ></div>".$f_html_copia;
	
	}
	$html 				= $html.$f_html."</body></html>";	
	
	

  
	//wgonzalez	
	//wgonzalez $html	= $html."<tr>
    //wgonzalez                 <td colspan='1'>
    //wgonzalez                   Estado
    //wgonzalez                 </td>
	//wgonzalez				  <td colspan='2'>
    //wgonzalez                   ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
    //wgonzalez                 </td>
    //wgonzalez               </tr>
   
	//wgonzalez	
    //wgonzalez               <tr>
    //wgonzalez                 <td colspan='3'>
    //wgonzalez                   Tipo de Cambio: ".$tipoCambio."
    //wgonzalez                 </td>
    //wgonzalez               </tr>
	//wgonzalez                    
       
    
    return $html;
}

function helper_reporteA4TransactionMasterInvoiceLaptopStore(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo,    
	$objDetail,
    $objParameterTelefono, /*telefono*/
	$objEmployerNatural , /*venedor*/
	$objTelefonoEmployer , /*telefono cliente*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    		= PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
	$font_size1   	= "18px";
	$border_left 	= "border-left: 1px solid black;";
	$border_right 	= "border-right: 1px solid black;";
	$border_top 	= "border-top: 1px solid black;";
	$border_bottom 	= "border-bottom: 1px solid black;";
	$border_radius	= "border-radius: 10px;";
	$border_colapse = "border-collapse:separate;";
	
	
	
    $type    		= pathinfo($path, PATHINFO_EXTENSION);
    $data    		= file_get_contents($path);
    $base64  		= 'data:image/' . $type . ';base64,' . base64_encode($data);
    $numberDocument = strpos($objTransactionMastser->transactionNumber ,"FAC") === false ?   str_replace("PRF","PROFORMA No. ",strtoupper($objTransactionMastser->transactionNumber)) : str_replace("FAC","FACTURA No. ",strtoupper($objTransactionMastser->transactionNumber)) ;
	$tipoDocumento  = strpos($objTransactionMastser->transactionNumber ,"FAC") === false ?   "PROFORMA": "FACTURA";
	$clientName		= ($objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName  :  $objTransactionMasterInfo->referenceClientName) ;
	$telefono  		= $objEntidadCustomer->phoneNumber;
	
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
						
                        @page {       
                          size: Legal;   
						
						  
                          margin-top:25px;
                          margin-left:25px;
                          margin-right:20px;
						  margin-bottom: 25px;
						  
						  
						  padding-top: 0px;
						  padding-right: 0px;
						  padding-bottom: 0px;
						  padding-left: 0px;
						  
                        }
                        table{
                          font-size: xx-small;
                          font-family: sans-serif, monaco, monospace;						 
						  border-collapse: collapse;
                        }
						td{
                          font-size: xx-small;
                          font-family: sans-serif, monaco, monospace;
						  /*border: 1px solid black;*/
						  border-collapse: collapse;
                        }
                      </style>
                    </head>
        
                    <body>
					";
        
		$f_html = 	  "
                      <table style='width:98%'>
                        <tr>
						  <td  style='text-align:center;width:500px;text-align:left'>
                            <img  src='".$base64."' width='300'  >
                          </td>
                          <td  style='text-align:center;'>
									<table style='width:100%'>
										<tr>
										  <td  style='text-align:center;font-size:".$font_size1."; font-weight: bold;'>
											". $numberDocument ."
										  </td>
										</tr>
										
										<tr>
										  <td style='text-align:center'>
											Barrio Riguera Talleres Modernos 4C.S, 1/2A 20V al Sur
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											RUC: 0012910650018M
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											Teléfono: 8894-3974
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											<!--AUT.DGI. No. ASFC 02/0009/02/2023/2-->
										  </td>
										</tr>
									</table>
                          </td>
                        </tr>
					</table>
					
						";
    	   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
			
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td style='text-align:center' colspan='3'>[[TIPO_DOCUMENTO]]</td>
						<td style='text-align:center;width:70px'>".$causalName."</td>
						<td style='text-align:center;width:70px; font-weight: bold;'>Vendedor</td>
						<td style='text-align:center; font-weight: bold;'>".$objEmployerNatural->firstName." ".$objEmployerNatural->lastName."</td>
					</tr>
					<tr>
						<td style='text-align:center; font-weight: bold;".$border_left.$border_right.$border_top.$border_bottom."'>Cliente</td>
						<td style='text-align:left;".$border_left.$border_right.$border_top.$border_bottom."' colspan='3'>".$clientName."</td>						
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."'>Cedula</td>
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."'>".$objEntidadCustomer->identification."</td>
					</tr>
					<tr>
						<td style='text-align:center;width:70px; font-weight: bold;".$border_left.$border_right.$border_top.$border_bottom."' >Telefono</td>
						<td style='text-align:left;".$border_left.$border_right.$border_top.$border_bottom."' colspan='3'>".$telefono."</td>						
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."' >Fecha</td>
						<td style='text-align:center;width:250px;".$border_left.$border_right.$border_top.$border_bottom."' >".$objTransactionMastser->createdOn."</td>
					</tr>
				</table>
			";
			
		   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
			
	$f_html = $f_html."
			<table style='width:98%' > 
				<tr style='background-color: #ccc;'>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >ARTICULO</td>
					<td style='text-align:center; font-weight: bold;"."' >DESCRIPCION</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Pre. Unit.</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Cant.</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Descuento</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >TOTAL</td>
				<tr>
			";
		
	$total 		= 0;
	$subtotal 	= 0;
	$iva 		= 0;
	$descuento 	= 0;
	
	for($i = 0 ; $i < 22 ; $i++)
	{
		$count = count($objDetail);
		if($i < $count)
		{
			$total 		= $total + $objDetail[$i]->amount ;
			$iva 		= $iva + ($objDetail[$i]->tax1 * $objDetail[$i]->quantity);
			$subtotal 	= $subtotal + ($objDetail[$i]->unitaryPrice * $objDetail[$i]->quantity);
			
			$f_html = $f_html."			
				<tr>
					<td style='text-align:left;width:70px' >".$objDetail[$i]->itemNumber."</td>
					<td style='text-align:left;' >".$objDetail[$i]->itemNameLog."</td>
					<td style='text-align:center;width:70px' >".$objCurrency->simbol." ".number_format(round($objDetail[$i]->unitaryPrice,2),2,".",",")."</td>
					<td style='text-align:center;width:70px' >".number_format(round($objDetail[$i]->quantity,2),2,".",",")."</td>
					<td style='text-align:center;width:70px' >".$objCurrency->simbol." 0.00</td>
					<td style='text-align:center;width:70px' >".$objCurrency->simbol." ".number_format(round($objDetail[$i]->unitaryPrice,2)  * round($objDetail[$i]->quantity,2)   ,2,".",",")."</td>
				<tr>
			";
		}
		else
		{
			$f_html = $f_html."			
				<tr>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
				<tr>
			";
		}
	}
				
	$f_html = $f_html."
			</table>
			";
		   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
				
	
	$f_html = $f_html."
				  <table style='width:98%;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >
						<tr>
							<td  style='text-align:left;vertical-align:top;widht:auto;' >
								<table style='width:100%;'>
									<tr>
										<td style='font-size:".$font_size1.";font-weight:bold;' >Nota al cliente:</td>
									</tr>
									<tr>
										<td style='height:60px;text-justify: auto;  '>". substr($objTransactionMastser->note,0,450) ."</td>
									</tr>
								</table>
							</td>
							<td style='width:200px;vertical-align:top;' >									
								<table style='width:100%;'>
									<tr>										
										<td style='text-align:right;vertical-align: top;font-size:".$font_size1."; font-weight: bold;'>Totales:</td>								
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>																
									<tr>										
										<td style='text-align:right;padding-top:5px;'>No se aceptan devoluciones.</td>										
									</tr>						
								</table>
								
							</td>
							<td  style='text-align:left;vertical-align:top;widht:100px;' >
								<table style='width:100%;'>
									<tr>										
										<td style='width:70px'>Subtotal</td>
										<td style='text-align:right;width:70px'>".$objCurrency->simbol." ".number_format ( round($subtotal,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>
										<td>Descuentos</td>
										<td style='text-align:right'>".$objCurrency->simbol." 0.00</td>
									</tr>
									<tr>
										<td>Subtotal</td>
										<td style='text-align:right'>".$objCurrency->simbol." ".number_format ( round($subtotal,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>
										<td>IVA</td>
										<td style='text-align:right'>".$objCurrency->simbol." ".number_format ( round($iva,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>
										<td style=' font-weight: bold;'>TOTAL:</td>
										<td style='text-align:right'>".$objCurrency->simbol." ".number_format ( round($objTransactionMastser->amount,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>										
										<td></td>
										<td></td>
									</tr>	
									<tr>
										<td colspan='2'>*=</td>
									</tr>									
								</table>
							</td>
							
						</tr>
						
						
				   </table>";
				   
				   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
			
	$f_html = $f_html."
				  <table style='width:98%' >
						<tr>
							<td style='text-align:center;text-decoration:underline;font-weight: bold;font-size:14px'>
								Laptop Nic Store
							</td>
						</tr>
				   </table>";
	
	
	$f_html_copia 		= $f_html;
	$f_html_original 	= $f_html;
	$f_html_credito		= $f_html;
	
	$f_html_original 		= str_replace("[[TIPO_DOCUMENTO]]","ORIGINAL",$f_html_original);
	$f_html_copia 			= str_replace("[[TIPO_DOCUMENTO]]","COPIA",$f_html_copia);
	$f_html_credito 		= str_replace("[[TIPO_DOCUMENTO]]","COPIA",$f_html_copia);
	
	if($tipoDocumento == "PROFORMA")
	{
		$f_html				= $f_html_original;
	}
	else 
	{
		if(strtoupper($causalName) == strtoupper( "CREDITO" ) )
		$f_html				= $f_html_original."<div style='page-break-before:always;' ></div>".$f_html_copia."<div style='page-break-before:always;' ></div>".$f_html_credito;
		else 
		$f_html				= $f_html_original."<div style='page-break-before:always;' ></div>".$f_html_copia;
	
	}
	$html 				= $html.$f_html."</body></html>";	
	
	

  
	//wgonzalez	
	//wgonzalez $html	= $html."<tr>
    //wgonzalez                 <td colspan='1'>
    //wgonzalez                   Estado
    //wgonzalez                 </td>
	//wgonzalez				  <td colspan='2'>
    //wgonzalez                   ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
    //wgonzalez                 </td>
    //wgonzalez               </tr>
   
	//wgonzalez	
    //wgonzalez               <tr>
    //wgonzalez                 <td colspan='3'>
    //wgonzalez                   Tipo de Cambio: ".$tipoCambio."
    //wgonzalez                 </td>
    //wgonzalez               </tr>
	//wgonzalez                    
       
    
    return $html;
}


function helper_reporteA4TransactionMasterInvoiceBpn(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo,    
	$objDetail,
    $objParameterTelefono, /*telefono*/
	$objEmployerNatural , /*venedor*/
	$objTelefonoEmployer , /*telefono cliente*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    		= PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
	$font_size1   	= "18px";
	$border_left 	= "border-left: 1px solid black;";
	$border_right 	= "border-right: 1px solid black;";
	$border_top 	= "border-top: 1px solid black;";
	$border_bottom 	= "border-bottom: 1px solid black;";
	$border_radius	= "border-radius: 10px;";
	$border_colapse = "border-collapse:separate;";
	
	
	
    $type    		= pathinfo($path, PATHINFO_EXTENSION);
    $data    		= file_get_contents($path);
    $base64  		= 'data:image/' . $type . ';base64,' . base64_encode($data);
    $numberDocument = strpos($objTransactionMastser->transactionNumber ,"FAC") === false ?   str_replace("PRF","PROFORMA No. ",strtoupper($objTransactionMastser->transactionNumber)) : str_replace("FAC","FACTURA No. ",strtoupper($objTransactionMastser->transactionNumber)) ;
	$tipoDocumento  = strpos($objTransactionMastser->transactionNumber ,"FAC") === false ?   "PROFORMA": "FACTURA";
	$clientName		= ($objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName  :  $objTransactionMasterInfo->referenceClientName) ;
	$telefono  		= $objEntidadCustomer->phoneNumber;
	
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
						
                        @page {       
                          size: Legal;   
						
						  
                          margin-top:25px;
                          margin-left:25px;
                          margin-right:20px;
						  margin-bottom: 25px;
						  
						  
						  padding-top: 0px;
						  padding-right: 0px;
						  padding-bottom: 0px;
						  padding-left: 0px;
						  
                        }
                        table{
                          font-size: xx-small;
                          font-family: sans-serif, monaco, monospace;						 
						  border-collapse: collapse;
                        }
						td{
                          font-size: xx-small;
                          font-family: sans-serif, monaco, monospace;
						  /*border: 1px solid black;*/
						  border-collapse: collapse;
                        }
                      </style>
                    </head>
        
                    <body>
					";
        
		$f_html = 	  "
                      <table style='width:98%'>
                        <tr>
						  <td  style='text-align:center;width:500px;text-align:left'>
                            <img  src='".$base64."' width='60'  height='80px'  >
                          </td>
                          <td  style='text-align:center;'>
									<table style='width:100%'>
										<tr>
										  <td  style='text-align:center;font-size:".$font_size1."; font-weight: bold;'>
											". $numberDocument ."
										  </td>
										</tr>
										
										<tr>
										  <td style='text-align:center'>
											Jinotega, Frente a casa materna
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											Edificio Delta RUC: 0010908920046P
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											&nbsp;
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											<!--AUT.DGI. No. ASFC 02/0009/02/2023/2-->
										  </td>
										</tr>
									</table>
                          </td>
                        </tr>
					</table>
					
						";
    	   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
			
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td style='text-align:center' colspan='3'>[[TIPO_DOCUMENTO]]</td>
						<td style='text-align:center;width:70px'>".$causalName."</td>
						<td style='text-align:center;width:70px; font-weight: bold;'>Vendedor</td>
						<td style='text-align:center; font-weight: bold;'>".$objEmployerNatural->firstName." ".$objEmployerNatural->lastName."</td>
					</tr>
					<tr>
						<td style='text-align:center; font-weight: bold;".$border_left.$border_right.$border_top.$border_bottom."'>Cliente</td>
						<td style='text-align:left;".$border_left.$border_right.$border_top.$border_bottom."' colspan='3'>".$clientName."</td>						
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."'>Cedula</td>
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."'>".$objEntidadCustomer->identification."</td>
					</tr>
					<tr>
						<td style='text-align:center;width:70px; font-weight: bold;".$border_left.$border_right.$border_top.$border_bottom."' >Telefono</td>
						<td style='text-align:left;".$border_left.$border_right.$border_top.$border_bottom."' colspan='3'>".$telefono."</td>						
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."' >Fecha</td>
						<td style='text-align:center;width:250px;".$border_left.$border_right.$border_top.$border_bottom."' >".$objTransactionMastser->createdOn."</td>
					</tr>
				</table>
			";
			
		   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
			
	$f_html = $f_html."
			<table style='width:98%' > 
				<tr style='background-color: #ccc;'>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >ARTICULO</td>
					<td style='text-align:center; font-weight: bold;"."' >DESCRIPCION</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Pre. Unit.</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Cant.</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Descuento</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >TOTAL</td>
				<tr>
			";
		
	$total 		= 0;
	$subtotal 	= 0;
	$iva 		= 0;
	$descuento 	= 0;
	
	for($i = 0 ; $i < 22 ; $i++)
	{
		$count = count($objDetail);
		if($i < $count)
		{
			$total 		= $total + $objDetail[$i]->amount ;
			$iva 		= $iva + ($objDetail[$i]->tax1 * $objDetail[$i]->quantity);
			$subtotal 	= $subtotal + ($objDetail[$i]->unitaryPrice * $objDetail[$i]->quantity);
			
			$f_html = $f_html."			
				<tr>
					<td style='text-align:left;width:70px' >".$objDetail[$i]->itemNumber."</td>
					<td style='text-align:left;' >".$objDetail[$i]->itemNameLog."</td>
					<td style='text-align:center;width:70px' >".$objCurrency->simbol." ".number_format(round($objDetail[$i]->unitaryPrice,2),2,".",",")."</td>
					<td style='text-align:center;width:70px' >".number_format(round($objDetail[$i]->quantity,2),2,".",",")."</td>
					<td style='text-align:center;width:70px' >".$objCurrency->simbol." 0.00</td>
					<td style='text-align:center;width:70px' >".$objCurrency->simbol." ".number_format(round($objDetail[$i]->unitaryPrice,2)  * round($objDetail[$i]->quantity,2)   ,2,".",",")."</td>
				<tr>
			";
		}
		else
		{
			$f_html = $f_html."			
				<tr>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
				<tr>
			";
		}
	}
				
	$f_html = $f_html."
			</table>
			";
		   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
				
	
	$f_html = $f_html."
				  <table style='width:98%;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >
						<tr>
							<td  style='text-align:left;vertical-align:top;widht:auto;' >
								<table style='width:100%;'>
									<tr>
										<td style='font-size:".$font_size1.";font-weight:bold;' >Nota al cliente:</td>
									</tr>
									<tr>
										<td style='height:60px;text-justify: auto;  '>". substr($objTransactionMastser->note,0,450) ."</td>
									</tr>
								</table>
							</td>
							<td style='width:200px;vertical-align:top;' >									
								<table style='width:100%;'>
									<tr>										
										<td style='text-align:right;vertical-align: top;font-size:".$font_size1."; font-weight: bold;'>Totales:</td>								
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>																
									<tr>										
										<td style='text-align:right;padding-top:5px;'>No se aceptan devoluciones.</td>										
									</tr>						
								</table>
								
							</td>
							<td  style='text-align:left;vertical-align:top;widht:100px;' >
								<table style='width:100%;'>
									<tr>										
										<td style='width:70px'>Subtotal</td>
										<td style='text-align:right;width:70px'>".$objCurrency->simbol." ".number_format ( round($subtotal,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>
										<td>Descuentos</td>
										<td style='text-align:right'>".$objCurrency->simbol." 0.00</td>
									</tr>
									<tr>
										<td>Subtotal</td>
										<td style='text-align:right'>".$objCurrency->simbol." ".number_format ( round($subtotal,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>
										<td>IVA</td>
										<td style='text-align:right'>".$objCurrency->simbol." ".number_format ( round($iva,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>
										<td style=' font-weight: bold;'>TOTAL:</td>
										<td style='text-align:right'>".$objCurrency->simbol." ".number_format ( round($objTransactionMastser->amount,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>										
										<td></td>
										<td></td>
									</tr>	
									<tr>
										<td colspan='2'><!-- *=Equipo seminuevo --> </td>
									</tr>									
								</table>
							</td>
							
						</tr>
						
						
				   </table>";
				   
				   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
			
	$f_html = $f_html."
				  <table style='width:98%' >
						<tr>
							<td style='text-align:center;text-decoration:underline;font-weight: bold;font-size:14px'>
								<!--Al comprar tu equipo en GLOBAL PRO,puedes cambiarlo por uno superior cuando gustes!-->
							</td>
						</tr>
				   </table>";
	
	
	$f_html_copia 		= $f_html;
	$f_html_original 	= $f_html;
	$f_html_credito		= $f_html;
	
	$f_html_original 		= str_replace("[[TIPO_DOCUMENTO]]","ORIGINAL",$f_html_original);
	$f_html_copia 			= str_replace("[[TIPO_DOCUMENTO]]","COPIA",$f_html_copia);
	$f_html_credito 		= str_replace("[[TIPO_DOCUMENTO]]","COPIA",$f_html_copia);
	
	if($tipoDocumento == "PROFORMA")
	{
		$f_html				= $f_html_original;
	}
	else 
	{
		if(strtoupper($causalName) == strtoupper( "CREDITO" ) )
		$f_html				= $f_html_original."<div style='page-break-before:always;' ></div>".$f_html_copia;//."<div style='page-break-before:always;' ></div>".$f_html_credito;
		else 
		$f_html				= $f_html_original."<div style='page-break-before:always;' ></div>".$f_html_copia;
	
	}
	$html 				= $html.$f_html."</body></html>";	
	
	

  
	//wgonzalez	
	//wgonzalez $html	= $html."<tr>
    //wgonzalez                 <td colspan='1'>
    //wgonzalez                   Estado
    //wgonzalez                 </td>
	//wgonzalez				  <td colspan='2'>
    //wgonzalez                   ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
    //wgonzalez                 </td>
    //wgonzalez               </tr>
   
	//wgonzalez	
    //wgonzalez               <tr>
    //wgonzalez                 <td colspan='3'>
    //wgonzalez                   Tipo de Cambio: ".$tipoCambio."
    //wgonzalez                 </td>
    //wgonzalez               </tr>
	//wgonzalez                    
       
    
    return $html;
}



function helper_reporteA4TransactionMasterTransferOutputGlobalPro(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
	$statusName,
	$objDetail,	
    $objNaturalSource,
    $objNaturalTarget,
	$objWarehouse,
	$objWarehouseTarget
)
{
    $path    		= PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
	$font_size1   	= "18px";
	$border_left 	= "border-left: 1px solid black;";
	$border_right 	= "border-right: 1px solid black;";
	$border_top 	= "border-top: 1px solid black;";
	$border_bottom 	= "border-bottom: 1px solid black;";
	$border_radius	= "border-radius: 10px;";
	$border_colapse = "border-collapse:separate;";
	
	
	
    $type    		= pathinfo($path, PATHINFO_EXTENSION);
    $data    		= file_get_contents($path);
    $base64  		= 'data:image/' . $type . ';base64,' . base64_encode($data);
    $numberDocument = str_replace("TSS","Transferencia Salida No: ",strtoupper($objTransactionMastser->transactionNumber)) ;
	$tipoDocumento  = "TRANSFERENCIA SALIDA";
	
	
	
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
						
                        @page {       
                          size: Legal;   
						
						  
                          margin-top:25px;
                          margin-left:25px;
                          margin-right:20px;
						  margin-bottom: 25px;
						  
						  
						  padding-top: 0px;
						  padding-right: 0px;
						  padding-bottom: 0px;
						  padding-left: 0px;
						  
                        }
                        table{
                          font-size: xx-small;
                          font-family: sans-serif, monaco, monospace;						 
						  border-collapse: collapse;
                        }
						td{
                          font-size: xx-small;
                          font-family: sans-serif, monaco, monospace;
						  /*border: 1px solid black;*/
						  border-collapse: collapse;
                        }
                      </style>
                    </head>
        
                    <body>
					";
        
		$f_html = 	  "
                      <table style='width:98%'>
                        <tr>
						  <td  style='text-align:center;width:500px;text-align:left'>
                            <img  src='".$base64."' width='300'  >
                          </td>
                          <td  style='text-align:center;'>
									<table style='width:100%'>
										<tr>
										  <td  style='text-align:center;font-size:".$font_size1."; font-weight: bold;'>
											". $numberDocument ."
										  </td>
										</tr>
										
										<tr>
										  <td style='text-align:center'>
											Carretera Masaya, Frente al colegio Teresiano 
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											Edificio Delta RUC: 888-080396-0001K
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											&nbsp;
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											AUT.DGI. No. ASFC 02/0009/02/2023/2
										  </td>
										</tr>
									</table>
                          </td>
                        </tr>
					</table>
					
						";
    	   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
			
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td style='text-align:center' colspan='3'>[[TIPO_DOCUMENTO]]</td>
						<td style='text-align:center;width:120px'>".$statusName."</td>
						<td style='text-align:center;width:120px; font-weight: bold;'><!--Telefono--></td>
						<td style='text-align:center; font-weight: bold;'>".  "<!--LXYZLXYZ-->"  ."</td>
					</tr>
					<tr>
						<td style='text-align:center; font-weight: bold;".$border_left.$border_right.$border_top.$border_bottom."'>Bodega Destino:</td>
						<td style='text-align:left;".$border_left.$border_right.$border_top.$border_bottom."' colspan='3'>". $objWarehouseTarget->name ."</td>
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."'>Fecha</td>
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."'>".$objTransactionMastser->createdOn."</td>
					</tr>
					<tr>
						<td style='text-align:center;width:120px; font-weight: bold;".$border_left.$border_right.$border_top.$border_bottom."' >Bodega Origen:</td>
						<td style='text-align:left;".$border_left.$border_right.$border_top.$border_bottom."' colspan='3'>" . $objWarehouse->name  ."</td>						
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."' >Orden / Cliente</td>
						<td style='text-align:center;width:250px;".$border_left.$border_right.$border_top.$border_bottom."' >". $objTransactionMastser->reference4 ."</td> 
					</tr>
				</table>
			";
			
		   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
			
	$f_html = $f_html."
			<table style='width:98%' > 
				<tr style='background-color: #ccc;'>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >ARTICULO</td>
					<td style='text-align:center; font-weight: bold;"."' >DESCRIPCION</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Pre. Unit.</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Cant.</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Descuento</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >TOTAL</td>
				<tr>
			";
		
	$cantidad 		= 0;	
	for($i = 0 ; $i < 22 ; $i++)
	{
		$count = count($objDetail);
		if($i < $count)
		{
			$cantidad++;
			
			$f_html = $f_html."			
				<tr>
					<td style='text-align:left;width:70px' >".$objDetail[$i]->itemNumber."</td>
					<td style='text-align:left;' >".$objDetail[$i]->itemName."</td>
					<td style='text-align:center;width:70px' >".number_format(round(0,2),2,".",",")."</td>
					<td style='text-align:center;width:70px' >".number_format(round($objDetail[$i]->quantity,2),2,".",",")."</td>
					<td style='text-align:center;width:70px' >"." 0.00</td>
					<td style='text-align:center;width:70px' >".number_format(round(0,2)  * round($objDetail[$i]->quantity,2)   ,2,".",",")."</td>
				<tr>
			";
		}
		else
		{
			$f_html = $f_html."			
				<tr>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
				<tr>
			";
		}
	}
				
	$f_html = $f_html."
			</table>
			";
		   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
				
	
	$f_html = $f_html."
				  <table style='width:98%;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >
						<tr>
							<td  style='text-align:left;vertical-align:top;widht:auto;height:20px' >
								
							</td>
							<td  style='text-align:left;vertical-align:top;widht:auto;' >
								
							</td>
							<td  style='text-align:left;vertical-align:top;widht:auto;' >
								
							</td>
						</tr>
						<tr>
							<td  style='text-align:left;vertical-align:top;widht:auto;' >
								________________________________
							</td>
							<td  style='text-align:left;vertical-align:top;widht:auto;' >
								
							</td>
							<td  style='text-align:left;vertical-align:top;widht:auto;' >
								________________________________
							</td>
						</tr>
						<tr>
							<td  style='text-align:left;vertical-align:top;widht:auto;' >
								". ($objNaturalSource ? ($objNaturalSource->firstName." ".$objNaturalSource->lastName) : "N/D") . "
							</td>
							<td  style='text-align:left;vertical-align:top;widht:auto;' >
								
							</td>
							<td  style='text-align:left;vertical-align:top;widht:auto;' >
								".( $objNaturalTarget ? ( $objNaturalTarget->firstName." ".$objNaturalTarget->lastName) : "N/D" ) ." 
							</td>
						</tr>
						<tr>
							<td  style='text-align:left;vertical-align:top;widht:auto;' >
								Envia:
							</td>
							<td  style='text-align:left;vertical-align:top;widht:auto;' >
								
							</td>
							<td  style='text-align:left;vertical-align:top;widht:auto;' >
								Recibe:
							</td>
						</tr>
				   </table>";
				   
				
	
	
	$f_html_copia 		= $f_html;
	$f_html_original 	= $f_html;
	
	
	$f_html_original 	= str_replace("[[TIPO_DOCUMENTO]]","ORIGINAL",$f_html_original);
	$f_html_copia 		= str_replace("[[TIPO_DOCUMENTO]]","COPIA",$f_html_copia);
	
	
	$f_html				= $f_html_original."<div style='page-break-before:always;' ></div>".$f_html_copia;
	$html 				= $html.$f_html."</body></html>";	
	
    
    return $html;
}


function helper_reporteA4TransactionMasterOutherOutputGlobalPro(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
	$statusName,
	$objDetail,
	$objWarehouse
)
{
    $path    		= PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
	$font_size1   	= "18px";
	$border_left 	= "border-left: 1px solid black;";
	$border_right 	= "border-right: 1px solid black;";
	$border_top 	= "border-top: 1px solid black;";
	$border_bottom 	= "border-bottom: 1px solid black;";
	$border_radius	= "border-radius: 10px;";
	$border_colapse = "border-collapse:separate;";
	
	
	
    $type    		= pathinfo($path, PATHINFO_EXTENSION);
    $data    		= file_get_contents($path);
    $base64  		= 'data:image/' . $type . ';base64,' . base64_encode($data);
    $numberDocument =  str_replace("OSS", "Ajuste de Salida ", strtoupper($objTransactionMastser->transactionNumber) ); ;
	$tipoDocumento  = "SALIDA DE INVENTARIO";
	
	
	
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
						
                        @page {       
                          size: Legal;   
						
						  
                          margin-top:25px;
                          margin-left:25px;
                          margin-right:20px;
						  margin-bottom: 25px;
						  
						  
						  padding-top: 0px;
						  padding-right: 0px;
						  padding-bottom: 0px;
						  padding-left: 0px;
						  
                        }
                        table{
                          font-size: xx-small;
                          font-family: sans-serif, monaco, monospace;						 
						  border-collapse: collapse;
                        }
						td{
                          font-size: xx-small;
                          font-family: sans-serif, monaco, monospace;
						  /*border: 1px solid black;*/
						  border-collapse: collapse;
                        }
                      </style>
                    </head>
        
                    <body>
					";
        
		$f_html = 	  "
                      <table style='width:98%'>
                        <tr>
						  <td  style='text-align:center;width:500px;text-align:left'>
                            <img  src='".$base64."' width='300'  >
                          </td>
                          <td  style='text-align:center;'>
									<table style='width:100%'>
										<tr>
										  <td  style='text-align:center;font-size:".$font_size1."; font-weight: bold;'>
											". $numberDocument ."
										  </td>
										</tr>
										
										<tr>
										  <td style='text-align:center'>
											Carretera Masaya, Frente al colegio Teresiano 
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											Edificio Delta RUC: 888-080396-0001K
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											&nbsp;
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											AUT.DGI. No. ASFC 02/0009/02/2023/2
										  </td>
										</tr>
									</table>
                          </td>
                        </tr>
					</table>
					
						";
    	   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
			
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td style='text-align:center' colspan='3'>[[TIPO_DOCUMENTO]]</td>
						<td style='text-align:center;width:70px'>".$statusName."</td>
						<td style='text-align:center;width:70px; font-weight: bold;'><!--Telefono--></td>
						<td style='text-align:center; font-weight: bold;'>".  "<!--LXYZLXYZ-->"  ."</td>
					</tr>
					<tr>
						<td style='text-align:center; font-weight: bold;".$border_left.$border_right.$border_top.$border_bottom."'>Bodega:</td>
						<td style='text-align:left;".$border_left.$border_right.$border_top.$border_bottom."' colspan='3'>". $objWarehouse->name  ."</td>
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."'>Fecha</td>
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."'>".$objTransactionMastser->createdOn."</td>
					</tr>
					
					<tr>
						<td style='text-align:center;width:70px; font-weight: bold;".$border_left.$border_right.$border_top.$border_bottom."' ><!--Envia:--></td>
						<td style='text-align:left;".$border_left.$border_right.$border_top.$border_bottom."' colspan='3'>".   "  <!--N/D-->" ."</td>						
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."' ><!--Cedula--></td>
						<td style='text-align:center;width:250px;".$border_left.$border_right.$border_top.$border_bottom."' >". "<!--LXYZLXYZ-->"  ."</td> 
					</tr>
					
				</table>
			";
			
		   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
			
	$f_html = $f_html."
			<table style='width:98%' > 
				<tr style='background-color: #ccc;'>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >ARTICULO</td>
					<td style='text-align:center; font-weight: bold;"."' >DESCRIPCION</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Pre. Unit.</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Cant.</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Descuento</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >TOTAL</td>
				<tr>
			";
		
	$cantidad 		= 0;	
	for($i = 0 ; $i < 22 ; $i++)
	{
		$count = count($objDetail);
		if($i < $count)
		{
			$cantidad++;
			
			$f_html = $f_html."			
				<tr>
					<td style='text-align:left;width:70px' >".$objDetail[$i]->itemNumber."</td>
					<td style='text-align:left;' >".$objDetail[$i]->itemName."</td>
					<td style='text-align:center;width:70px' >".number_format(round(0,2),2,".",",")."</td>
					<td style='text-align:center;width:70px' >".number_format(round($objDetail[$i]->quantity,2),2,".",",")."</td>
					<td style='text-align:center;width:70px' >"." 0.00</td>
					<td style='text-align:center;width:70px' >".number_format(round(0,2)  * round($objDetail[$i]->quantity,2)   ,2,".",",")."</td>
				<tr>
			";
		}
		else
		{
			$f_html = $f_html."			
				<tr>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
				<tr>
			";
		}
	}
				
	$f_html = $f_html."
			</table>
			";
		   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
				
	
	$f_html = $f_html."
				  <table style='width:98%;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >
						<tr>
							<td  style='text-align:left;vertical-align:top;widht:auto;' >
								<table style='width:100%;'>
									<tr>
										<td style='font-size:".$font_size1.";font-weight:bold;' >Nota:</td>
									</tr>
									<tr>
										<td style='height:60px;text-justify: auto;  '>". substr($objTransactionMastser->note,0,450) ."</td>
									</tr>
								</table>
							</td>
							<td style='width:200px;vertical-align:top;' >									
								<table style='width:100%;'>
									<tr>										
										<td style='text-align:right;vertical-align: top;font-size:".$font_size1."; font-weight: bold;'>Totales:</td>								
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>																
									<tr>										
										<td style='text-align:right;padding-top:5px;'><!--No se aceptan devoluciones.--></td>										
									</tr>						
								</table>
								
							</td>
							<td  style='text-align:left;vertical-align:top;widht:100px;' >
								<table style='width:100%;'>
									<tr>										
										<td style='width:70px'>Cantidad</td>
										<td style='text-align:right;width:70px'>".number_format ( round($cantidad,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>
										<td><!--Descuentos--></td>
										<td style='text-align:right'><!--0.00--></td>
									</tr>
									<tr>
										<td><!--Subtotal--></td>
										<td style='text-align:right'><!--0.00--></td>
									</tr>
									<tr>
										<td><!--IVA--></td>
										<td style='text-align:right'><!--0.00--></td>
									</tr>
									<tr>
										<td style=' font-weight: bold;'><!--TOTAL:--></td>
										<td style='text-align:right'><!--0.00--></td>
									</tr>
									<tr>										
										<td></td>
										<td></td>
									</tr>	
									<tr>
										<td colspan='2'><!--*=Equipo seminuevo--></td>
									</tr>									
								</table>
							</td>
							
						</tr>
						
						
				   </table>";
				   
				   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
			
	$f_html = $f_html."
				  <table style='width:98%' >
						<tr>
							<td style='text-align:center;text-decoration:underline;font-weight: bold;font-size:14px'>
								Al comprar tu equipo en GLOBAL PRO,puedes cambiarlo por uno superior cuando gustes!
							</td>
						</tr>
				   </table>";
	
	
	$f_html_copia 		= $f_html;
	$f_html_original 	= $f_html;
	
	
	$f_html_original 	= str_replace("[[TIPO_DOCUMENTO]]","ORIGINAL",$f_html_original);
	$f_html_copia 		= str_replace("[[TIPO_DOCUMENTO]]","COPIA",$f_html_copia);
	
	
	$f_html				= $f_html_original."<div style='page-break-before:always;' ></div>".$f_html_copia;
	$html 				= $html.$f_html."</body></html>";	
	
    
    return $html;
}


function helper_reporteA4TransactionMasterOutherInputGlobalPro(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
	$statusName,
	$objDetail,
	$objWarehouse
)
{
    $path    		= PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
	$font_size1   	= "18px";
	$border_left 	= "border-left: 1px solid black;";
	$border_right 	= "border-right: 1px solid black;";
	$border_top 	= "border-top: 1px solid black;";
	$border_bottom 	= "border-bottom: 1px solid black;";
	$border_radius	= "border-radius: 10px;";
	$border_colapse = "border-collapse:separate;";
	
	
	
    $type    		= pathinfo($path, PATHINFO_EXTENSION);
    $data    		= file_get_contents($path);
    $base64  		= 'data:image/' . $type . ';base64,' . base64_encode($data);
    $numberDocument = strtoupper($objTransactionMastser->transactionNumber) ;
	$tipoDocumento  = "SALIDA DE INVENTARIO";
	
	
	
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
						
                        @page {       
                          size: Legal;   
						
						  
                          margin-top:25px;
                          margin-left:25px;
                          margin-right:20px;
						  margin-bottom: 25px;
						  
						  
						  padding-top: 0px;
						  padding-right: 0px;
						  padding-bottom: 0px;
						  padding-left: 0px;
						  
                        }
                        table{
                          font-size: xx-small;
                          font-family: sans-serif, monaco, monospace;						 
						  border-collapse: collapse;
                        }
						td{
                          font-size: xx-small;
                          font-family: sans-serif, monaco, monospace;
						  /*border: 1px solid black;*/
						  border-collapse: collapse;
                        }
                      </style>
                    </head>
        
                    <body>
					";
        
		$f_html = 	  "
                      <table style='width:98%'>
                        <tr>
						  <td  style='text-align:center;width:500px;text-align:left'>
                            <img  src='".$base64."' width='300'  >
                          </td>
                          <td  style='text-align:center;'>
									<table style='width:100%'>
										<tr>
										  <td  style='text-align:center;font-size:".$font_size1."; font-weight: bold;'>
											". $numberDocument ."
										  </td>
										</tr>
										
										<tr>
										  <td style='text-align:center'>
											Carretera Masaya, Frente al colegio Teresiano 
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											Edificio Delta RUC: 888-080396-0001K
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											&nbsp;
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											AUT.DGI. No. ASFC 02/0009/02/2023/2
										  </td>
										</tr>
									</table>
                          </td>
                        </tr>
					</table>
					
						";
    	   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
			
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td style='text-align:center' colspan='3'>[[TIPO_DOCUMENTO]]</td>
						<td style='text-align:center;width:70px'>".$statusName."</td>
						<td style='text-align:center;width:70px; font-weight: bold;'><!--Telefono--></td>
						<td style='text-align:center; font-weight: bold;'>".  "<!--LXYZLXYZ-->"  ."</td>
					</tr>
					<tr>
						<td style='text-align:center; font-weight: bold;".$border_left.$border_right.$border_top.$border_bottom."'>Bodega:</td>
						<td style='text-align:left;".$border_left.$border_right.$border_top.$border_bottom."' colspan='3'>". $objWarehouse->name  ."</td>
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."'>Fecha</td>
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."'>".$objTransactionMastser->createdOn."</td>
					</tr>
					
					<tr>
						<td style='text-align:center;width:70px; font-weight: bold;".$border_left.$border_right.$border_top.$border_bottom."' ><!--Envia:--></td>
						<td style='text-align:left;".$border_left.$border_right.$border_top.$border_bottom."' colspan='3'>".   "  <!--N/D-->" ."</td>						
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."' ><!--Cedula--></td>
						<td style='text-align:center;width:250px;".$border_left.$border_right.$border_top.$border_bottom."' >". "<!--LXYZLXYZ-->"  ."</td> 
					</tr>
					
				</table>
			";
			
		   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
			
	$f_html = $f_html."
			<table style='width:98%' > 
				<tr style='background-color: #ccc;'>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >ARTICULO</td>
					<td style='text-align:center; font-weight: bold;"."' >DESCRIPCION</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Pre. Unit.</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Cant.</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Descuento</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >TOTAL</td>
				<tr>
			";
		
	$cantidad 		= 0;	
	for($i = 0 ; $i < 22 ; $i++)
	{
		$count = count($objDetail);
		if($i < $count)
		{
			$cantidad++;
			
			$f_html = $f_html."			
				<tr>
					<td style='text-align:left;width:70px' >".$objDetail[$i]->itemNumber."</td>
					<td style='text-align:left;' >".$objDetail[$i]->itemName."</td>
					<td style='text-align:center;width:70px' >".number_format(round(0,2),2,".",",")."</td>
					<td style='text-align:center;width:70px' >".number_format(round($objDetail[$i]->quantity,2),2,".",",")."</td>
					<td style='text-align:center;width:70px' >"." 0.00</td>
					<td style='text-align:center;width:70px' >".number_format(round(0,2)  * round($objDetail[$i]->quantity,2)   ,2,".",",")."</td>
				<tr>
			";
		}
		else
		{
			$f_html = $f_html."			
				<tr>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
				<tr>
			";
		}
	}
				
	$f_html = $f_html."
			</table>
			";
		   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
				
	
	$f_html = $f_html."
				  <table style='width:98%;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >
						<tr>
							<td  style='text-align:left;vertical-align:top;widht:auto;' >
								<table style='width:100%;'>
									<tr>
										<td style='font-size:".$font_size1.";font-weight:bold;' >Nota:</td>
									</tr>
									<tr>
										<td style='height:60px;text-justify: auto;  '>". substr($objTransactionMastser->note,0,450) ."</td>
									</tr>
								</table>
							</td>
							<td style='width:200px;vertical-align:top;' >									
								<table style='width:100%;'>
									<tr>										
										<td style='text-align:right;vertical-align: top;font-size:".$font_size1."; font-weight: bold;'>Totales:</td>								
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>																
									<tr>										
										<td style='text-align:right;padding-top:5px;'><!--No se aceptan devoluciones.--></td>										
									</tr>						
								</table>
								
							</td>
							<td  style='text-align:left;vertical-align:top;widht:100px;' >
								<table style='width:100%;'>
									<tr>										
										<td style='width:70px'>Cantidad</td>
										<td style='text-align:right;width:70px'>".number_format ( round($cantidad,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>
										<td><!--Descuentos--></td>
										<td style='text-align:right'><!--0.00--></td>
									</tr>
									<tr>
										<td><!--Subtotal--></td>
										<td style='text-align:right'><!--0.00--></td>
									</tr>
									<tr>
										<td><!--IVA--></td>
										<td style='text-align:right'><!--0.00--></td>
									</tr>
									<tr>
										<td style=' font-weight: bold;'><!--TOTAL:--></td>
										<td style='text-align:right'><!--0.00--></td>
									</tr>
									<tr>										
										<td></td>
										<td></td>
									</tr>	
									<tr>
										<td colspan='2'><!--*=Equipo seminuevo--></td>
									</tr>									
								</table>
							</td>
							
						</tr>
						
						
				   </table>";
				   
				   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
			
	$f_html = $f_html."
				  <table style='width:98%' >
						<tr>
							<td style='text-align:center;text-decoration:underline;font-weight: bold;font-size:14px'>
								Al comprar tu equipo en GLOBAL PRO,puedes cambiarlo por uno superior cuando gustes!
							</td>
						</tr>
				   </table>";
	
	
	$f_html_copia 		= $f_html;
	$f_html_original 	= $f_html;
	
	
	$f_html_original 	= str_replace("[[TIPO_DOCUMENTO]]","ORIGINAL",$f_html_original);
	$f_html_copia 		= str_replace("[[TIPO_DOCUMENTO]]","COPIA",$f_html_copia);
	
	
	$f_html				= $f_html_original."<div style='page-break-before:always;' ></div>".$f_html_copia;
	$html 				= $html.$f_html."</body></html>";	
	
    
    return $html;
}



function helper_reporteA4mmTransactionMasterInputUnpostGlobalPro(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo,    
	$objDetail,
    $objParameterTelefono, /*telefono*/
	$objEmployerNatural , /*venedor*/
	$objTelefonoEmployer , /*telefono cliente*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    		= PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
	$font_size1   	= "18px";
	$border_left 	= "border-left: 1px solid black;";
	$border_right 	= "border-right: 1px solid black;";
	$border_top 	= "border-top: 1px solid black;";
	$border_bottom 	= "border-bottom: 1px solid black;";
	$border_radius	= "border-radius: 10px;";
	$border_colapse = "border-collapse:separate;";
	
	
	
    $type    		= pathinfo($path, PATHINFO_EXTENSION);
    $data    		= file_get_contents($path);
    $base64  		= 'data:image/' . $type . ';base64,' . base64_encode($data);
    $numberDocument = str_replace("ESP","COMPRA No ", $objTransactionMastser->transactionNumber);
	$tipoDocumento  = "COMPRA";
	$clientName		= $objEntidadNatural->firstName;
	$telefono  		= "";
	
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
						
                        @page {       
                          size: Legal;   
						
						  
                          margin-top:25px;
                          margin-left:25px;
                          margin-right:20px;
						  margin-bottom: 25px;
						  
						  
						  padding-top: 0px;
						  padding-right: 0px;
						  padding-bottom: 0px;
						  padding-left: 0px;
						  
                        }
                        table{
                          font-size: xx-small;
                          font-family: sans-serif, monaco, monospace;						 
						  border-collapse: collapse;
                        }
						td{
                          font-size: xx-small;
                          font-family: sans-serif, monaco, monospace;
						  /*border: 1px solid black;*/
						  border-collapse: collapse;
                        }
                      </style>
                    </head>
        
                    <body>
					";
        
		$f_html = 	  "
                      <table style='width:98%'>
                        <tr>
						  <td  style='text-align:center;width:500px;text-align:left'>
                            <img  src='".$base64."' width='300'  >
                          </td>
                          <td  style='text-align:center;'>
									<table style='width:100%'>
										<tr>
										  <td  style='text-align:center;font-size:".$font_size1."; font-weight: bold;'>
											". $numberDocument ."
										  </td>
										</tr>
										
										<tr>
										  <td style='text-align:center'>
											Carretera Masaya, Frente al colegio Teresiano 
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											Edificio Delta RUC: 888-080396-0001K
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											&nbsp;
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											AUT.DGI. No. ASFC 02/0009/02/2023/2
										  </td>
										</tr>
									</table>
                          </td>
                        </tr>
					</table>
					
						";
    	   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
	/*
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td style='text-align:center' colspan='3'>[[TIPO_DOCUMENTO]]</td>
						<td style='text-align:center;width:70px'>".$causalName."</td>
						<td style='text-align:center;width:70px; font-weight: bold;'>Vendedor</td>
						<td style='text-align:center; font-weight: bold;'>".$objEmployerNatural->firstName." ".$objEmployerNatural->lastName."</td>
					</tr>
					<tr>
						<td style='text-align:center; font-weight: bold;".$border_left.$border_right.$border_top.$border_bottom."'>Cliente</td>
						<td style='text-align:left;".$border_left.$border_right.$border_top.$border_bottom."' colspan='3'>".$clientName."</td>						
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."'>Cedula</td>
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."'>".$objEntidadCustomer->numberIdentification."</td>
					</tr>
					<tr>
						<td style='text-align:center;width:70px; font-weight: bold;".$border_left.$border_right.$border_top.$border_bottom."' >Telefono</td>
						<td style='text-align:left;".$border_left.$border_right.$border_top.$border_bottom."' colspan='3'>".$telefono."</td>						
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."' >Fecha</td>
						<td style='text-align:center;width:250px;".$border_left.$border_right.$border_top.$border_bottom."' >".$objTransactionMastser->createdOn."</td>
					</tr>
				</table>
			";
	*/
	
	
	/*
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
	*/ 	
			
	$f_html = $f_html."
			<table style='width:98%' > 
				<tr style='background-color: #ccc;'>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >ARTICULO</td>
					<td style='text-align:center; font-weight: bold;"."' >DESCRIPCION</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Pre. Unit.</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Cant.</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Descuento</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >TOTAL</td>
				<tr>
			";
		
	$total 		= 0;
	$subtotal 	= 0;
	$iva 		= 0;
	$descuento 	= 0;
	
	for($i = 0 ; $i < 22 ; $i++)
	{
		$count = count($objDetail);
		if($i < $count)
		{
			$total 		= $total + $objDetail[$i]->cost;
			$iva 		= $iva + $objDetail[$i]->tax1;
			$subtotal 	= $subtotal + $objDetail[$i]->cost;
			
			$f_html = $f_html."			
				<tr>
					<td style='text-align:left;width:70px' >".$objDetail[$i]->itemNumber."</td>
					<td style='text-align:left;' >".$objDetail[$i]->itemName."</td>
					<td style='text-align:center;width:70px' >".$objCurrency->simbol." ".number_format(round($objDetail[$i]->unitaryCost,2),2,".",",")."</td>
					<td style='text-align:center;width:70px' >".number_format(round($objDetail[$i]->quantity,2),2,".",",")."</td>
					<td style='text-align:center;width:70px' >".$objCurrency->simbol." 0.00</td>
					<td style='text-align:center;width:70px' >".$objCurrency->simbol." ".number_format(round($objDetail[$i]->cost,2),2,".",",")."</td>
				<tr>
			";
		}
		else
		{
			$f_html = $f_html."			
				<tr>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
				<tr>
			";
		}
	}
				
	$f_html = $f_html."
			</table>
			";
		   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
				
	
	$f_html = $f_html."
				  <table style='width:98%;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >
						<tr>
							<td  style='text-align:left;vertical-align:top;widht:auto;' >
								<table style='width:100%;'>
									<tr>
										<td style='font-size:".$font_size1.";font-weight:bold;' >Nota al cliente:</td>
									</tr>
									<tr>
										<td style='height:60px;text-justify: auto;  '>". substr($objTransactionMastser->note,0,450) ."</td>
									</tr>
								</table>
							</td>
							<td style='width:200px;vertical-align:top;' >									
								<table style='width:100%;'>
									<tr>										
										<td style='text-align:right;vertical-align: top;font-size:".$font_size1."; font-weight: bold;'>Totales:</td>								
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>																
									<tr>										
										<td style='text-align:right;padding-top:5px;'>No se aceptan devoluciones.</td>										
									</tr>						
								</table>
								
							</td>
							<td  style='text-align:left;vertical-align:top;widht:100px;' >
								<table style='width:100%;'>
									<tr>										
										<td style='width:70px'>Subtotal</td>
										<td style='text-align:right;width:70px'>".$objCurrency->simbol." ".number_format ( round($subtotal,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>
										<td>Descuentos</td>
										<td style='text-align:right'>".$objCurrency->simbol." 0.00</td>
									</tr>
									<tr>
										<td>Subtotal</td>
										<td style='text-align:right'>".$objCurrency->simbol." ".number_format ( round($subtotal,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>
										<td>IVA</td>
										<td style='text-align:right'>".$objCurrency->simbol." ".number_format ( round($iva,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>
										<td style=' font-weight: bold;'>TOTAL:</td>
										<td style='text-align:right'>".$objCurrency->simbol." ".number_format ( round($objTransactionMastser->amount,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>										
										<td></td>
										<td></td>
									</tr>	
									<tr>
										<td colspan='2'>*=Equipo seminuevo</td>
									</tr>									
								</table>
							</td>
							
						</tr>
						
						
				   </table>";
				   
				   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
			
	$f_html = $f_html."
				  <table style='width:98%' >
						<tr>
							<td style='text-align:center;text-decoration:underline;font-weight: bold;font-size:14px'>
								Al comprar tu equipo en GLOBAL PRO,puedes cambiarlo por uno superior cuando gustes!
							</td>
						</tr>
				   </table>";
	
	
	$f_html_copia 		= $f_html;
	$f_html_original 	= $f_html;
	$f_html_credito		= $f_html;
	
	$f_html_original 		= str_replace("[[TIPO_DOCUMENTO]]","ORIGINAL",$f_html_original);
	$f_html_copia 			= str_replace("[[TIPO_DOCUMENTO]]","COPIA",$f_html_copia);
	$f_html_credito 		= str_replace("[[TIPO_DOCUMENTO]]","COPIA",$f_html_copia);
	
	if($tipoDocumento == "PROFORMA")
	{
		$f_html				= $f_html_original;
	}
	else 
	{
		if(strtoupper($causalName) == strtoupper( "CREDITO" ) )
		$f_html				= $f_html_original;
		else 
		$f_html				= $f_html_original;
	
	}
	$html 				= $html.$f_html."</body></html>";	
	
	

              
       
    
    return $html;
}

function helper_reporteA4mmTransactionMasterTallerGlobalPro(
  $titulo,
  $objCompany,
  $objParameterLogo,
  $objTransactionMastser,
  $tipoCambio,
  $objCurrency,
  $objTransactionMasterInfo,    	
  $objParameterTelefono, /*telefono*/	
  $objData,
  $statusName = "", /*estado*/
  $causalName = ""
  
)
{
	$path    		= PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
  
	$font_size1   	= "18px";
	$border_left 	= "border-left: 1px solid black;";
	$border_right 	= "border-right: 1px solid black;";
	$border_top 	= "border-top: 1px solid black;";
	$border_bottom 	= "border-bottom: 1px solid black;";
	$border_radius	= "border-radius: 10px;";
	$border_colapse = "border-collapse:separate;";



  $type    		= pathinfo($path, PATHINFO_EXTENSION);
  $data    		= file_get_contents($path);
  $base64  		= 'data:image/' . $type . ';base64,' . base64_encode($data);
  $numberDocument = str_replace("IAT"," No ", $objTransactionMastser->transactionNumber);
  $tipoDocumento  = "TALLER";

  $telefono  		= "";

  $html    = "";
  $html    = "
                  <!--
                  Online HTML, CSS and JavaScript editor to run code online.
                  https://www.programiz.com/html/online-compiler/
                  -->
                  <!DOCTYPE html>
                  <html lang='en'>
      
                  <head>
                    <meta charset='UTF-8' />
                    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                    <style>
          
                      @page {       
                        size: Legal;             
            
                        margin-top:25px;
                        margin-left:25px;
                        margin-right:20px;
						margin-bottom: 25px;
						
						
						padding-top: 0px;
						padding-right: 0px;
						padding-bottom: 0px;
						padding-left: 0px;
            
                      }
                      table{
                        font-size: xx-small;
                        font-family: sans-serif, monaco, monospace;						 
						border-collapse: collapse;
                      }
						td{
                        font-size: xx-small;
                        font-family: sans-serif, monaco, monospace;
						/*border: 1px solid black;*/
						border-collapse: collapse;
                      }
                    </style>
                  </head>
      
                  <body>
        ";
      
  $f_html = 	  "
                    <table style='width:98%'>
                      <tr>
						<td  style='vertical-align:top;text-align:center;width:33%;text-align:left'>
                          <img  src='".$base64."' width='200px' height='100px'  >
                        </td>
						<td style='vertical-align:middle;text-align:center;width:33%;text-align:center; font-weight: bold;font-size:".$font_size1."'>
							HOJA DE INGRESO
						</td>
                        <td  style='vertical-align:top;text-align:center;width:33%'>
							<table style='width:100%'>
							  <tr>
								<td  style='text-align:center;font-size:".$font_size1."; font-weight: bold;'>
								". $numberDocument ."
								</td>
							  </tr>
							  
							  <tr>
								<td style='text-align:center'>
								Carretera Masaya, Frente al colegio Teresiano 
								</td>
							  </tr>
							  <tr>
								<td style='text-align:center'>
								Servicio Tecnico: 5863-7406
								</td>
							  </tr>
							  <tr>
								<td style='text-align:center'>
								&nbsp;
								</td>
							  </tr>
							  <tr>
								<td style='text-align:center'>
								Siguenos! Global Pro Nicaragua
								</td>
							  </tr>
							</table>
                        </td>
                      </tr>
        </table>
        
          ";
		   
	$f_html = $f_html."
		  <table style='width:98%' >
			<tr>
			  <td>&nbsp;</td>
			</tr>			
		  </table>	
		";
		
      

$f_html = $f_html."
        <table style='width:98%;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >
          <tr>
            <td  style='width:20px;text-align:left;vertical-align:top;' >
				Nombre:
            </td>
            <td style='width:180px;vertical-align:top;' >	
				".$objData["objCustomerNatural"]->firstName."
            </td>
			<td style='width:20px;vertical-align:top;' >									              
				Marca:
            </td>
			<td style='width:120px;vertical-align:top;' >	
				".$objData["objItemMarca"]->name."
            </td>
			<td style='width:60px;vertical-align:top;' >									              
				No. Orden:
            </td>
            <td  style='width:120px;text-align:left;vertical-align:top;' >              
				".$objData["objTransactionMaster"]->transactionNumber."
            </td>            
          </tr>
		  <tr>
            <td  style='text-align:left;vertical-align:top;' >              
				Teléfono:
            </td>
            <td style='vertical-align:top;' >									              
				".$objData["objCustomer"]->phoneNumber."
            </td>
			<td style='vertical-align:top;' >									              
				Modelo:
            </td>
			<td style='vertical-align:top;' >									              
				".$objData["objTransactionMasterInfo"]->reference2."
            </td>
			<td style='vertical-align:top;' >									              
				Fecha:
            </td>
            <td  style='text-align:left;vertical-align:top;' >              
				".$objData["objTransactionMaster"]->transactionOn."
            </td>            
          </tr>
		  <tr>
            <td  style='text-align:left;vertical-align:top;' >              
				Articulo
            </td>
            <td style='vertical-align:top;' >				
				".$objData["objItemArticulo"]->name."
            </td>
			<td style='vertical-align:top;' >									              
				Serie
            </td>
			<td style='vertical-align:top;' >									              
				".$objData["objTransactionMaster"]->reference4."
            </td>
			<td style='vertical-align:top;' >									              
				Técnico:
            </td>
            <td  style='text-align:left;vertical-align:top;' >              
				".$objData["objEmployerNatural"]->firstName."
            </td>            
          </tr>
         </table>";
         
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
	
$f_html = $f_html."
        <table style='width:98%;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >
          <tr>
            <td  style='text-align:center;vertical-align:top;widht:auto;font-weight: bold;' >              
				Problema reportado
            </td>                 
          </tr>
		  <tr>
            <td  style='text-align:left;vertical-align:top;widht:auto;' >              
				".$objData["objTransactionMaster"]->reference2."
            </td>                 
          </tr>
		  <tr>
            <td  style='text-align:center;vertical-align:top;width:auto;font-weight: bold;' >              
				Nota
            </td>                 
          </tr>
		  <tr>
            <td  style='text-align:left;vertical-align:top;width:auto;' >              
				<table style='width:100%'>
					<tr>
						<td colspan='3'>
							".$objData["objTransactionMaster"]->reference1."
						</td>
					</tr>
					<tr>
						<td style='width:150px'>
							Deja cargador o Accesorios:
						</td>
						<td style='text-align:left'>
							".$objData["objItemAccesorios"]->name."
						</td>
						<td>
							EXPERTOS EN REPARACION Y MANTENIMIENTO
						</td>
					</tr>
				</table>
            </td>                 
          </tr>
         </table>";
		 
		 
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
    
    
$f_html = $f_html."
        <table style='width:98%' >
          <tr>
            <td style='text-align:justify;font-weight: bold;font-size:12px'>
              GLOBAL PRO NO se hace reponsable de pérdidas de informacion, por lo cual solicitamos antes
			  hacer un respaldo a Nuestros clientes.			
            </td>
          </tr>
		  <tr>
            <td style='text-align:justify;font-weight: bold;font-size:12px'>
              El Costo del diagnóstico es de C$ 450.00 si decide realizar la reparacion, NO pagara diagnostico.
			  Despues de 30 dias de notificar que el equipo está listo para ser retirado, se cobrara $1 diario por 
			  concepto de Almacenamiento, despues de 6 meses se procedera a liquidar el equipo.
			  Toda reparación de GLOBAL PRO, cuenta con 3 meses de garantia
            </td>
          </tr>
        </table>";
		
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
	
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";

$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
    

$f_html = $f_html."
        <table style='width:98%' >
          <tr>
            <td style='text-align:center;font-weight: bold'>
              
            </td>
			<td style='text-align:center;font-weight: bold'>
              ---------------------------------
            </td>
			<td style='text-align:center;font-weight: bold'>
              
            </td>
          </tr>
		  <tr>
            <td style='text-align:center;font-weight: bold'>
              
            </td>
			<td style='text-align:center;font-weight: bold'>
              ".$objData["objCustomerNatural"]->firstName."
            </td>
			<td style='text-align:center;font-weight: bold'>
              
            </td>
          </tr>
		  <tr>
            <td style='text-align:center;font-weight: bold;'>              
			  
            </td>
			<td style='text-align:center;font-weight: bold'>
              ".$objData["objCustomer"]->identification."
            </td>
			<td style='text-align:center;font-weight: bold'>
              
            </td>
          </tr>
		  
        </table>";


  $html 				= $html.$f_html."</body></html>";	  
  return $html;
}

function helper_reporteA4mmTransactionMasterTallerOutputGlobalPro(
  $titulo,
  $objCompany,
  $objParameterLogo,
  $objTransactionMastser,
  $tipoCambio,
  $objCurrency,
  $objTransactionMasterInfo,    	
  $objParameterTelefono, /*telefono*/	
  $objData,
  $statusName = "", /*estado*/
  $causalName = ""
  
)
{
	$path    		= PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
  
	$font_size1   	= "18px";
	$border_left 	= "border-left: 1px solid black;";
	$border_right 	= "border-right: 1px solid black;";
	$border_top 	= "border-top: 1px solid black;";
	$border_bottom 	= "border-bottom: 1px solid black;";
	$border_radius	= "border-radius: 10px;";
	$border_colapse = "border-collapse:separate;";



  $type    		= pathinfo($path, PATHINFO_EXTENSION);
  $data    		= file_get_contents($path);
  $base64  		= 'data:image/' . $type . ';base64,' . base64_encode($data);
  $numberDocument = str_replace("IAT"," No ", $objTransactionMastser->transactionNumber);
  $tipoDocumento  = "TALLER";

  $telefono  		= "";

  $html    = "";
  $html    = "
                  <!--
                  Online HTML, CSS and JavaScript editor to run code online.
                  https://www.programiz.com/html/online-compiler/
                  -->
                  <!DOCTYPE html>
                  <html lang='en'>
      
                  <head>
                    <meta charset='UTF-8' />
                    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                    <style>
          
                      @page {       
                        size: Legal;             
            
                        margin-top:25px;
                        margin-left:25px;
                        margin-right:20px;
						margin-bottom: 25px;
						
						
						padding-top: 0px;
						padding-right: 0px;
						padding-bottom: 0px;
						padding-left: 0px;
            
                      }
                      table{
                        font-size: xx-small;
                        font-family: sans-serif, monaco, monospace;						 
						border-collapse: collapse;
                      }
						td{
                        font-size: xx-small;
                        font-family: sans-serif, monaco, monospace;
						/*border: 1px solid black;*/
						border-collapse: collapse;
                      }
                    </style>
                  </head>
      
                  <body>
        ";
      
  $f_html = 	  "
                    <table style='width:98%'>
                      <tr>
						<td  style='vertical-align:top;text-align:center;width:33%;text-align:left'>
                          <img  src='".$base64."' width='200px' height='120px'  >
                        </td>
						<td style='vertical-align:top;text-align:center;width:33%;text-align:center; font-weight: bold;font-size:".$font_size1."'>
							HOJA DE SALIDA
						</td>
                        <td  style='vertical-align:top;text-align:center;width:33%'>
							<table style='width:100%'>
							  <tr>
								<td  style='text-align:center;font-size:".$font_size1."; font-weight: bold;'>
								". $numberDocument ."
								</td>
							  </tr>
							  
							  <tr>
								<td style='text-align:center'>
								Carretera Masaya, Frente al colegio Teresiano 
								</td>
							  </tr>
							  <tr>
								<td style='text-align:center'>
								Servicio Tecnico: 5863-7406
								</td>
							  </tr>
							  <tr>
								<td style='text-align:center'>
								&nbsp;
								</td>
							  </tr>
							  <tr>
								<td style='text-align:center'>
								Siguenos! Global Pro Nicaragua
								</td>
							  </tr>
							</table>
                        </td>
                      </tr>
        </table>
        
          ";
		   
	$f_html = $f_html."
		  <table style='width:98%' >
			<tr>
			  <td>&nbsp;</td>
			</tr>			
		  </table>	
		";
		
      

$f_html = $f_html."
        <table style='width:98%;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >
          <tr>
            <td  style='width:20px;text-align:left;vertical-align:top;' >
				Nombre:
            </td>
            <td style='width:180px;vertical-align:top;' >	
				".$objData["objCustomerNatural"]->firstName."
            </td>
			<td style='width:20px;vertical-align:top;' >									              
				Marca:
            </td>
			<td style='width:120px;vertical-align:top;' >	
				".$objData["objItemMarca"]->name."
            </td>
			<td style='width:60px;vertical-align:top;' >									              
				No. Orden:
            </td>
            <td  style='width:120px;text-align:left;vertical-align:top;' >              
				".$objData["objTransactionMaster"]->transactionNumber."
            </td>            
          </tr>
		  <tr>
            <td  style='text-align:left;vertical-align:top;' >              
				Teléfono:
            </td>
            <td style='vertical-align:top;' >									              
				".$objData["objCustomer"]->phoneNumber."
            </td>
			<td style='vertical-align:top;' >									              
				Modelo:
            </td>
			<td style='vertical-align:top;' >									              
				".$objData["objTransactionMasterInfo"]->reference2."
            </td>
			<td style='vertical-align:top;' >									              
				Fecha:
            </td>
            <td  style='text-align:left;vertical-align:top;' >              
				".$objData["objTransactionMaster"]->transactionOn."
            </td>            
          </tr>
		  <tr>
            <td  style='text-align:left;vertical-align:top;' >              
				Articulo
            </td>
            <td style='vertical-align:top;' >				
				".$objData["objItemArticulo"]->name."
            </td>
			<td style='vertical-align:top;' >									              
				Serie
            </td>
			<td style='vertical-align:top;' >									              
				".$objData["objTransactionMaster"]->reference4."
            </td>
			<td style='vertical-align:top;' >									              
				Técnico:
            </td>
            <td  style='text-align:left;vertical-align:top;' >              
				".$objData["objEmployerNatural"]->firstName."
            </td>            
          </tr>
         </table>";
         
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
	
$f_html = $f_html."
        <table style='width:98%;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >
          <tr>
            <td  style='text-align:center;vertical-align:top;widht:auto;font-weight: bold;' >              
				Trabajo efectuado
            </td>                 
          </tr>
		  <tr>
            <td  style='text-align:left;vertical-align:top;widht:auto;' >              
				".$objData["objTransactionMaster"]->reference3."
            </td>                 
          </tr>		 
         </table>";
		 
		 
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
    
    
$f_html = $f_html."
        <table style='width:98%' >
          <tr>
            <td style='text-align:justify;font-weight: bold;font-size:12px'>
              GLOBAL PRO NO se hace reponsable de pérdidas de informacion, por lo cual solicitamos antes
			  hacer un respaldo a Nuestros clientes.			
            </td>
          </tr>
		  <tr>
            <td style='text-align:justify;font-weight: bold;font-size:12px'>
              El Costo del diagnóstico es de C$ 450.00 si decide realizar la reparacion, NO pagara diagnostico.
			  Despues de 30 dias de notificar que el equipo está listo para ser retirado, se cobrara $1 diario por 
			  concepto de Almacenamiento, despues de 6 meses se procedera a liquidar el equipo.
			  Toda reparación de GLOBAL PRO, cuenta con 3 meses de garantia
            </td>
          </tr>
        </table>";
		
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
			
$f_html = $f_html."
        <table>
			<tr>
				<td width='550px'>
				</td>
				<td>
					<table style='width:200px;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >
					<tr>
					<td  style='text-align:left;vertical-align:top;widht:auto;font-weight: bold;' >              
						Monto a cancelar: C$ ".number_format($objTransactionMastser->amount,2,".",",")."
					</td>                 
					</tr>		  
					</table>
				</td>
			</tr>
		</table>";
		 
		 
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
	
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";

$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
    

$f_html = $f_html."
        <table style='width:98%' >
          <tr>
            <td style='text-align:center;font-weight: bold'>
              
            </td>
			<td style='text-align:center;font-weight: bold'>
              ---------------------------------
            </td>
			<td style='text-align:center;font-weight: bold'>
              
            </td>
          </tr>
		  <tr>
            <td style='text-align:center;font-weight: bold'>
              
            </td>
			<td style='text-align:center;font-weight: bold'>
              ".$objData["objCustomerNatural"]->firstName."
            </td>
			<td style='text-align:center;font-weight: bold'>
              
            </td>
          </tr>
		  <tr>
            <td style='text-align:center;font-weight: bold;'>              
			  
            </td>
			<td style='text-align:center;font-weight: bold'>
              Recibo conforme
            </td>
			<td style='text-align:center;font-weight: bold'>
              
            </td>
          </tr>
		  
        </table>";


  $html 				= $html.$f_html."</body></html>";	  
  return $html;
}

function helper_reporteA4mmTransactionMasterTallerOutputGarantiaGlobalPro(
  $titulo,
  $objCompany,
  $objParameterLogo,
  $objTransactionMastser,
  $tipoCambio,
  $objCurrency,
  $objTransactionMasterInfo,    	
  $objParameterTelefono, /*telefono*/	
  $objData,
  $statusName = "", /*estado*/
  $causalName = ""
  
)
{
	$path    		= PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
  
	$font_size1   	= "18px";
	$border_left 	= "border-left: 1px solid black;";
	$border_right 	= "border-right: 1px solid black;";
	$border_top 	= "border-top: 1px solid black;";
	$border_bottom 	= "border-bottom: 1px solid black;";
	$border_radius	= "border-radius: 10px;";
	$border_colapse = "border-collapse:separate;";



  $type    		= pathinfo($path, PATHINFO_EXTENSION);
  $data    		= file_get_contents($path);
  $base64  		= 'data:image/' . $type . ';base64,' . base64_encode($data);
  $numberDocument = str_replace("IAT"," No ", $objTransactionMastser->transactionNumber);
  $tipoDocumento  = "TALLER";

  $telefono  		= "";

  $html    = "";
  $html    = "
                  <!--
                  Online HTML, CSS and JavaScript editor to run code online.
                  https://www.programiz.com/html/online-compiler/
                  -->
                  <!DOCTYPE html>
                  <html lang='en'>
      
                  <head>
                    <meta charset='UTF-8' />
                    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                    <style>
          
                      @page {       
                        size: Legal;             
            
                        margin-top:25px;
                        margin-left:25px;
                        margin-right:20px;
						margin-bottom: 25px;
						
						
						padding-top: 0px;
						padding-right: 0px;
						padding-bottom: 0px;
						padding-left: 0px;
            
                      }
                      table{
                        font-size: xx-small;
                        font-family: sans-serif, monaco, monospace;						 
						border-collapse: collapse;
                      }
						td{
                        font-size: xx-small;
                        font-family: sans-serif, monaco, monospace;
						/*border: 1px solid black;*/
						border-collapse: collapse;
                      }
                    </style>
                  </head>
      
                  <body>
        ";
      
  $f_html = 	  "
                    <table style='width:98%'>
                      <tr>
						<td  style='vertical-align:top;text-align:center;width:33%;text-align:left'>
                          <img  src='".$base64."' width='200px' height='120px'  >
                        </td>
						<td style='vertical-align:top;text-align:center;width:33%;text-align:center; font-weight: bold;font-size:".$font_size1."'>
							HOJA DE SALIDA
						</td>
                        <td  style='vertical-align:top;text-align:center;width:33%'>
							<table style='width:100%'>
							  <tr>
								<td  style='text-align:center;font-size:".$font_size1."; font-weight: bold;'>
								". $numberDocument ."
								</td>
							  </tr>
							  
							  <tr>
								<td style='text-align:center'>
								Carretera Masaya, Frente al colegio Teresiano 
								</td>
							  </tr>
							  <tr>
								<td style='text-align:center'>
								Servicio Tecnico: 5863-7406
								</td>
							  </tr>
							  <tr>
								<td style='text-align:center'>
								&nbsp;
								</td>
							  </tr>
							  <tr>
								<td style='text-align:center'>
								Siguenos! Global Pro Nicaragua
								</td>
							  </tr>
							</table>
                        </td>
                      </tr>
        </table>
        
          ";
		   
	$f_html = $f_html."
		  <table style='width:98%' >
			<tr>
			  <td>&nbsp;</td>
			</tr>			
		  </table>	
		";
		
      

$f_html = $f_html."
        <table style='width:98%;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >
          <tr>
            <td  style='width:20px;text-align:left;vertical-align:top;' >
				Nombre:
            </td>
            <td style='width:180px;vertical-align:top;' >	
				".$objData["objCustomerNatural"]->firstName."
            </td>
			<td style='width:20px;vertical-align:top;' >									              
				Sello:
            </td>
			<td style='width:120px;vertical-align:top;' >	
				".$objData["objItemMarca"]->name."
            </td>
			<td style='width:60px;vertical-align:top;' >									              
				No. Orden:
            </td>
            <td  style='width:120px;text-align:left;vertical-align:top;' >              
				".$objData["objTransactionMaster"]->transactionNumber."
            </td>            
          </tr>
		  <tr>
            <td  style='text-align:left;vertical-align:top;' >              
				Teléfono:
            </td>
            <td style='vertical-align:top;' >									              
				".$objData["objCustomer"]->phoneNumber."
            </td>
			<td style='vertical-align:top;' >									              
				Modelo:
            </td>
			<td style='vertical-align:top;' >									              
				".$objData["objTransactionMaster"]->reference1."
            </td>
			<td style='vertical-align:top;' >									              
				Fecha:
            </td>
            <td  style='text-align:left;vertical-align:top;' >              
				".$objData["objTransactionMaster"]->transactionOn."
            </td>            
          </tr>
		  <tr>
            <td  style='text-align:left;vertical-align:top;' >              
				Articulo
            </td>
            <td style='vertical-align:top;' >				
				".$objData["objItemArticulo"]->name."
            </td>
			<td style='vertical-align:top;' >									              
				Serie
            </td>
			<td style='vertical-align:top;' >									              
				".$objData["objTransactionMaster"]->reference2."
            </td>
			<td style='vertical-align:top;' >									              
				Técnico:
            </td>
            <td  style='text-align:left;vertical-align:top;' >              
				".$objData["objEmployerNatural"]->firstName."
            </td>            
          </tr>
         </table>";
         
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
	
$f_html = $f_html."
        <table style='width:98%;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >
          <tr>
            <td  style='text-align:center;vertical-align:top;widht:auto;font-weight: bold;' >              
				Trabajo efectuado
            </td>                 
          </tr>
		  <tr>
            <td  style='text-align:left;vertical-align:top;widht:auto;' >              
				".$objData["objTransactionMaster"]->reference3."
            </td>                 
          </tr>		 
         </table>";
		 
		 
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
    
    
$f_html = $f_html."
        <table style='width:98%' >
          <tr>
            <td style='text-align:justify;font-weight: bold;font-size:12px'>
              GLOBAL PRO NO se hace reponsable de pérdidas de informacion, por lo cual solicitamos antes
			  hacer un respaldo a Nuestros clientes.			
            </td>
          </tr>
		  <tr>
            <td style='text-align:justify;font-weight: bold;font-size:12px'>
              El Costo del diagnóstico es de C$ 450.00 si decide realizar la reparacion, NO pagara diagnostico.
			  Despues de 30 dias de notificar que el equipo está listo para ser retirado, se cobrara $1 diario por 
			  concepto de Almacenamiento, despues de 6 meses se procedera a liquidar el equipo.
			  Toda reparación de GLOBAL PRO, cuenta con 3 meses de garantia
            </td>
          </tr>
        </table>";
		
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
			
$f_html = $f_html."
        <table>
			<tr>
				<td width='550px'>
				</td>
				<td>
					<table style='width:200px;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >
					<tr>
					<td  style='text-align:left;vertical-align:top;widht:auto;font-weight: bold;' >              
						Monto a cancelar: C$ ".number_format($objTransactionMastser->amount,2,".",",")."
					</td>                 
					</tr>		  
					</table>
				</td>
			</tr>
		</table>";
		 
		 
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
	
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";

$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
    

$f_html = $f_html."
        <table style='width:98%' >
          <tr>
            <td style='text-align:center;font-weight: bold'>
              
            </td>
			<td style='text-align:center;font-weight: bold'>
              ---------------------------------
            </td>
			<td style='text-align:center;font-weight: bold'>
              
            </td>
          </tr>
		  <tr>
            <td style='text-align:center;font-weight: bold'>
              
            </td>
			<td style='text-align:center;font-weight: bold'>
              ".$objData["objCustomerNatural"]->firstName."
            </td>
			<td style='text-align:center;font-weight: bold'>
              
            </td>
          </tr>
		  <tr>
            <td style='text-align:center;font-weight: bold;'>              
			  
            </td>
			<td style='text-align:center;font-weight: bold'>
              Recibo conforme
            </td>
			<td style='text-align:center;font-weight: bold'>
              
            </td>
          </tr>
		  
        </table>";


  $html 				= $html.$f_html."</body></html>";	  
  return $html;
}



function helper_reporteA4mmTransactionMasterTallerStickerGlobalPro(
  $titulo,
  $objCompany,
  $objParameterLogo,
  $objTransactionMastser,
  $tipoCambio,
  $objCurrency,
  $objTransactionMasterInfo,    	
  $objParameterTelefono, /*telefono*/	
  $objData,
  $statusName = "", /*estado*/
  $causalName = ""
  
)
{
	$path    		= PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
  
	$font_size1   	= "18px";
	$border_left 	= "border-left: 1px solid black;";
	$border_right 	= "border-right: 1px solid black;";
	$border_top 	= "border-top: 1px solid black;";
	$border_bottom 	= "border-bottom: 1px solid black;";
	$border_radius	= "border-radius: 10px;";
	$border_colapse = "border-collapse:separate;";



  $type    		= pathinfo($path, PATHINFO_EXTENSION);
  $data    		= file_get_contents($path);
  $base64  		= 'data:image/' . $type . ';base64,' . base64_encode($data);
  $numberDocument = str_replace("IAT"," No ", $objTransactionMastser->transactionNumber);
  $tipoDocumento  = "TALLER";

  $telefono  		= "";

  $html    = "";
  $html    = "
                  <!--
                  Online HTML, CSS and JavaScript editor to run code online.
                  https://www.programiz.com/html/online-compiler/
                  -->
                  <!DOCTYPE html>
                  <html lang='en'>
      
                  <head>
                    <meta charset='UTF-8' />
                    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                    <style>
          
                      @page {       
                        size: 6cm 3.4cm;             
            
                        margin-top:25px;
                        margin-left:25px;
                        margin-right:20px;
						margin-bottom: 25px;
						
						
						padding-top: 0px;
						padding-right: 0px;
						padding-bottom: 0px;
						padding-left: 0px;
            
                      }
                      table{
                        font-size: xx-small;
                        font-family: sans-serif, monaco, monospace;						 
						border-collapse: collapse;
                      }
						td{
                        font-size: xx-small;
                        font-family: sans-serif, monaco, monospace;
						/*border: 1px solid black;*/
						border-collapse: collapse;
                      }
                    </style>
                  </head>
      
                  <body>
        ";
      
  $f_html = 	  "
                    <table style='width:98%'>
						  <tr>
							<td style='text-align:center;font-weight: bold;'>
								GLOBAL PRO	
							</td>
						  </tr>
						  <tr>
							<td style='text-align:center'>
								Orden: ". $numberDocument ."
							</td>
						  </tr>
						  <tr>
							<td style='text-align:center'>
								Cliente: ".$objData["objCustomerNatural"]->firstName."
							</td>
						  </tr>
						  <tr>
							<td style='text-align:center'>
								Técnico: ".$objData["objEmployerNatural"]->firstName."
							</td>
						  </tr>
					</table>
        
          ";
		   

  $html 				= $html.$f_html."</body></html>";	  
  return $html;
}


function helper_reporteA4mmTransactionMasterPedidoStickerGlobalPro(
  $titulo,
  $objCompany,
  $objParameterLogo,
  $objTransactionMastser,
  $tipoCambio,
  $objCurrency,
  $objTransactionMasterInfo,    	
  $objParameterTelefono, /*telefono*/	
  $objData,
  $statusName = "", /*estado*/
  $causalName = ""
  
)
{
	$path    		= PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
  
	$font_size1   	= "18px";
	$border_left 	= "border-left: 1px solid black;";
	$border_right 	= "border-right: 1px solid black;";
	$border_top 	= "border-top: 1px solid black;";
	$border_bottom 	= "border-bottom: 1px solid black;";
	$border_radius	= "border-radius: 10px;";
	$border_colapse = "border-collapse:separate;";



  $type    		= pathinfo($path, PATHINFO_EXTENSION);
  $data    		= file_get_contents($path);
  $base64  		= 'data:image/' . $type . ';base64,' . base64_encode($data);
  $numberDocument = str_replace("IAT"," No ", $objTransactionMastser->transactionNumber);
  $tipoDocumento  = "TALLER";

  $telefono  		= "";

  $html    = "";
  $html    = "
                  <!--
                  Online HTML, CSS and JavaScript editor to run code online.
                  https://www.programiz.com/html/online-compiler/
                  -->
                  <!DOCTYPE html>
                  <html lang='en'>
      
                  <head>
                    <meta charset='UTF-8' />
                    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                    <style>
          
                      @page {       
                        size: 6cm 3.4cm;             
            
                        margin-top:25px;
                        margin-left:25px;
                        margin-right:20px;
						margin-bottom: 25px;
						
						
						padding-top: 0px;
						padding-right: 0px;
						padding-bottom: 0px;
						padding-left: 0px;
            
                      }
                      table{
                        font-size: xx-small;
                        font-family: sans-serif, monaco, monospace;						 
						border-collapse: collapse;
                      }
						td{
                        font-size: xx-small;
                        font-family: sans-serif, monaco, monospace;
						/*border: 1px solid black;*/
						border-collapse: collapse;
                      }
                    </style>
                  </head>
      
                  <body>
        ";
      
  $f_html = 	  "
                    <table style='width:98%'>
						  <tr>
							<td style='text-align:center;font-weight: bold;'>
								GLOBAL PRO	
							</td>
						  </tr>						  
						  <tr>
							<td style='text-align:center'>
								<b>".strtoupper($objData["objCustomerNatural"]->firstName)."</b>
							</td>
						  </tr>
						  <tr>
							<td style='text-align:center'>
								<span style='font-size: 12px;' >". $objData["objPieza"]->name." ".$objData["objTransactionMaster"]->reference2." ".$objData["objTransactionMaster"]->reference4. "</span>
							</td>
						  </tr>						  
						  <tr>
							<td style='text-align:center'>
								<span style='font-size: 12px;' >".$objData["objEmployerNatural"]->firstName."</span>
							</td>
						  </tr>
						  <tr>
							<td style='text-align:center'>
								<span style='font-size: 12px;' >".$objData["objTransactionMasterInfo"]->reference1."</span>
							</td>
						  </tr>
					</table>
        
          ";
		   

  $html 				= $html.$f_html."</body></html>";	  
  return $html;
}



function helper_reporteA4mmTransactionMasterGastosGlobalPro(
  $titulo,
  $objCompany,
  $objParameterLogo,
  $objTransactionMastser,
  $tipoCambio,
  $objCurrency,
  $objTransactionMasterInfo,    	
  $objParameterTelefono, /*telefono*/	
  $statusName = "", /*estado*/
  $causalName = ""
)
{
  $path    		= PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
  
$font_size1   	= "18px";
$border_left 	= "border-left: 1px solid black;";
$border_right 	= "border-right: 1px solid black;";
$border_top 	= "border-top: 1px solid black;";
$border_bottom 	= "border-bottom: 1px solid black;";
$border_radius	= "border-radius: 10px;";
$border_colapse = "border-collapse:separate;";



  $type    		= pathinfo($path, PATHINFO_EXTENSION);
  $data    		= file_get_contents($path);
  $base64  		= 'data:image/' . $type . ';base64,' . base64_encode($data);
  $numberDocument = str_replace("ESP","PEDIDO No ", $objTransactionMastser->transactionNumber);
$tipoDocumento  = "PEDIDO";

$telefono  		= "";

  $html    = "";
  $html    = "
                  <!--
                  Online HTML, CSS and JavaScript editor to run code online.
                  https://www.programiz.com/html/online-compiler/
                  -->
                  <!DOCTYPE html>
                  <html lang='en'>
      
                  <head>
                    <meta charset='UTF-8' />
                    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                    <style>
          
                      @page {       
                        size: Legal;   
          
            
                        margin-top:25px;
                        margin-left:25px;
                        margin-right:20px;
            margin-bottom: 25px;
            
            
            padding-top: 0px;
            padding-right: 0px;
            padding-bottom: 0px;
            padding-left: 0px;
            
                      }
                      table{
                        font-size: xx-small;
                        font-family: sans-serif, monaco, monospace;						 
            border-collapse: collapse;
                      }
          td{
                        font-size: xx-small;
                        font-family: sans-serif, monaco, monospace;
            /*border: 1px solid black;*/
            border-collapse: collapse;
                      }
                    </style>
                  </head>
      
                  <body>
        ";
      
  $f_html = 	  "
                    <table style='width:98%'>
                      <tr>
            <td  style='text-align:center;width:500px;text-align:left'>
                          <img  src='".$base64."' width='300'  >
                        </td>
                        <td  style='text-align:center;'>
                <table style='width:100%'>
                  <tr>
                    <td  style='text-align:center;font-size:".$font_size1."; font-weight: bold;'>
                    ". $numberDocument ."
                    </td>
                  </tr>
                  
                  <tr>
                    <td style='text-align:center'>
                    Carretera Masaya, Frente al colegio Teresiano 
                    </td>
                  </tr>
                  <tr>
                    <td style='text-align:center'>
                    Edificio Delta RUC: 888-080396-0001K
                    </td>
                  </tr>
                  <tr>
                    <td style='text-align:center'>
                    &nbsp;
                    </td>
                  </tr>
                  <tr>
                    <td style='text-align:center'>
                    AUT.DGI. No. ASFC 02/0009/02/2023/2
                    </td>
                  </tr>
                </table>
                        </td>
                      </tr>
        </table>
        
          ";
       
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
    
/*
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td style='text-align:center' colspan='3'>[[TIPO_DOCUMENTO]]</td>
          <td style='text-align:center;width:70px'>".$causalName."</td>
          <td style='text-align:center;width:70px; font-weight: bold;'>Vendedor</td>
          <td style='text-align:center; font-weight: bold;'>".$objEmployerNatural->firstName." ".$objEmployerNatural->lastName."</td>
        </tr>
        <tr>
          <td style='text-align:center; font-weight: bold;".$border_left.$border_right.$border_top.$border_bottom."'>Cliente</td>
          <td style='text-align:left;".$border_left.$border_right.$border_top.$border_bottom."' colspan='3'>".$clientName."</td>						
          <td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."'>Cedula</td>
          <td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."'>".$objEntidadCustomer->numberIdentification."</td>
        </tr>
        <tr>
          <td style='text-align:center;width:70px; font-weight: bold;".$border_left.$border_right.$border_top.$border_bottom."' >Telefono</td>
          <td style='text-align:left;".$border_left.$border_right.$border_top.$border_bottom."' colspan='3'>".$telefono."</td>						
          <td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."' >Fecha</td>
          <td style='text-align:center;width:250px;".$border_left.$border_right.$border_top.$border_bottom."' >".$objTransactionMastser->createdOn."</td>
        </tr>
      </table>
    ";
*/


/*
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
*/ 	
    
$f_html = $f_html."
    <table style='width:98%' > 
      <tr style='background-color: #ccc;'>
        <td style='text-align:center;width:70px; font-weight: bold;"."' >ARTICULO</td>
        <td style='text-align:center; font-weight: bold;"."' >DESCRIPCION</td>
        <td style='text-align:center;width:70px; font-weight: bold;"."' >Pre. Unit.</td>
        <td style='text-align:center;width:70px; font-weight: bold;"."' >Cant.</td>
        <td style='text-align:center;width:70px; font-weight: bold;"."' >Descuento</td>
        <td style='text-align:center;width:70px; font-weight: bold;"."' >TOTAL</td>
      <tr>
    ";
  


$f_html = $f_html."
    </table>
    ";
     
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
      

$f_html = $f_html."
        <table style='width:98%;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >
          <tr>
            <td  style='text-align:left;vertical-align:top;widht:auto;' >
              <table style='width:100%;'>
                <tr>
                  <td style='font-size:".$font_size1.";font-weight:bold;' >Nota al cliente:</td>
                </tr>
                <tr>
                  <td style='height:60px;text-justify: auto;  '>". substr($objTransactionMastser->note,0,450) ."</td>
                </tr>
              </table>
            </td>
            <td style='width:200px;vertical-align:top;' >									
              <table style='width:100%;'>
                <tr>										
                  <td style='text-align:right;vertical-align: top;font-size:".$font_size1."; font-weight: bold;'>Totales:</td>								
                </tr>
                <tr>
                  <td>&nbsp;</td>										
                </tr>
                <tr>
                  <td>&nbsp;</td>										
                </tr>
                <tr>
                  <td>&nbsp;</td>										
                </tr>																
                <tr>										
                  <td style='text-align:right;padding-top:5px;'>No se aceptan devoluciones.</td>										
                </tr>						
              </table>
              
            </td>
            <td  style='text-align:left;vertical-align:top;widht:100px;' >
              
            </td>
            
          </tr>
          
          
         </table>";
         
         
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
    
    
$f_html = $f_html."
        <table style='width:98%' >
          <tr>
            <td style='text-align:center;text-decoration:underline;font-weight: bold;font-size:14px'>
              Al comprar tu equipo en GLOBAL PRO,puedes cambiarlo por uno superior cuando gustes!
            </td>
          </tr>
         </table>";


$f_html_copia 		= $f_html;
$f_html_original 	= $f_html;
$f_html_credito		= $f_html;

$f_html_original 		= str_replace("[[TIPO_DOCUMENTO]]","ORIGINAL",$f_html_original);
$f_html_copia 			= str_replace("[[TIPO_DOCUMENTO]]","COPIA",$f_html_copia);
$f_html_credito 		= str_replace("[[TIPO_DOCUMENTO]]","COPIA",$f_html_copia);

if($tipoDocumento == "PROFORMA")
{
  $f_html				= $f_html_original;
}
else 
{
  if(strtoupper($causalName) == strtoupper( "CREDITO" ) )
  $f_html				= $f_html_original;
  else 
  $f_html				= $f_html_original;

}
$html 				= $html.$f_html."</body></html>";	



            
     
  
  return $html;
}


function helper_reporteA4mmTransactionMasterGarantiasGlobalPro(
  $titulo,
  $objCompany,
  $objParameterLogo,
  $objTransactionMastser,
  $tipoCambio,
  $objCurrency,
  $objTransactionMasterInfo,    	
  $objParameterTelefono, /*telefono*/	
  $objData,
  $statusName = "", /*estado*/
  $causalName = ""
  
)
{
	$path    		= PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
  
	$font_size1   	= "18px";
	$border_left 	= "border-left: 1px solid black;";
	$border_right 	= "border-right: 1px solid black;";
	$border_top 	= "border-top: 1px solid black;";
	$border_bottom 	= "border-bottom: 1px solid black;";
	$border_radius	= "border-radius: 10px;";
	$border_colapse = "border-collapse:separate;";



  $type    		= pathinfo($path, PATHINFO_EXTENSION);
  $data    		= file_get_contents($path);
  $base64  		= 'data:image/' . $type . ';base64,' . base64_encode($data);
  $numberDocument = str_replace("IAT"," No ", $objTransactionMastser->transactionNumber);
  $tipoDocumento  = "TALLER";

  $telefono  		= "";

  $html    = "";
  $html    = "
                  <!--
                  Online HTML, CSS and JavaScript editor to run code online.
                  https://www.programiz.com/html/online-compiler/
                  -->
                  <!DOCTYPE html>
                  <html lang='en'>
      
                  <head>
                    <meta charset='UTF-8' />
                    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                    <style>
          
                      @page {       
                        size: Legal;             
            
                        margin-top:25px;
                        margin-left:25px;
                        margin-right:20px;
						margin-bottom: 25px;
						
						
						padding-top: 0px;
						padding-right: 0px;
						padding-bottom: 0px;
						padding-left: 0px;
            
                      }
                      table{
                        font-size: xx-small;
                        font-family: sans-serif, monaco, monospace;						 
						border-collapse: collapse;
                      }
						td{
                        font-size: xx-small;
                        font-family: sans-serif, monaco, monospace;
						/*border: 1px solid black;*/
						border-collapse: collapse;
                      }
                    </style>
                  </head>
      
                  <body>
        ";
      
  $f_html = 	  "
                    <table style='width:98%'>
                      <tr>
						<td  style='vertical-align:top;text-align:center;width:33%;text-align:left'>
                          <img  src='".$base64."' width='200px' height='100px'  >
                        </td>
						<td style='vertical-align:middle;text-align:center;width:33%;text-align:center; font-weight: bold;font-size:".$font_size1."'>
							HOJA DE INGRESO
						</td>
                        <td  style='vertical-align:top;text-align:center;width:33%'>
							<table style='width:100%'>
							  <tr>
								<td  style='text-align:center;font-size:".$font_size1."; font-weight: bold;'>
								<!--". $numberDocument ."-->
								</td>
							  </tr>
							  
							  <tr>
								<td style='text-align:center'>
								Carretera Masaya, Frente al colegio Teresiano 
								</td>
							  </tr>
							  <tr>
								<td style='text-align:center'>
								Servicio Tecnico: 5863-7406
								</td>
							  </tr>
							  <tr>
								<td style='text-align:center'>
								&nbsp;
								</td>
							  </tr>
							  <tr>
								<td style='text-align:center'>
								Siguenos! Global Pro Nicaragua
								</td>
							  </tr>
							</table>
                        </td>
                      </tr>
        </table>
        
          ";
		   
	$f_html = $f_html."
		  <table style='width:98%' >
			<tr>
			  <td>&nbsp;</td>
			</tr>			
		  </table>	
		";
		
      

$f_html = $f_html."
        <table style='width:98%;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >
          <tr>
            <td  style='width:20px;text-align:left;vertical-align:top;' >
				Nombre:
            </td>
            <td style='width:180px;vertical-align:top;' >	
				".$objData["objCustomerNatural"]->firstName."
            </td>
			<td style='width:20px;vertical-align:top;' >									              
				Fecha:
            </td>
			<td style='width:120px;vertical-align:top;' >					
				".$objData["objTransactionMaster"]->transactionOn."
            </td>
			<td style='width:60px;vertical-align:top;' >									              
				Técnico:
            </td>
            <td  style='width:120px;text-align:left;vertical-align:top;' >              				
				".$objData["objEmployerNatural"]->firstName."
            </td>            
          </tr>
		  <tr>
            <td  style='text-align:left;vertical-align:top;' >              
				Teléfono:
            </td>
            <td style='vertical-align:top;' >									              
				".$objData["objCustomer"]->phoneNumber."
            </td>
			<td style='vertical-align:top;' >									              
				Modelo:
            </td>
			<td style='vertical-align:top;' >									              
				".$objData["objTransactionMaster"]->reference1."
            </td>
			<td style='vertical-align:top;' >									              
				Factura:
            </td>
            <td  style='text-align:left;vertical-align:top;' >              
				".$objData["objTransactionMaster"]->note."
            </td>            
          </tr>
		  <tr>
            <td  style='text-align:left;vertical-align:top;' >              
				Articulo
            </td>
            <td style='vertical-align:top;' >				
				".$objData["objItemArticulo"]->name."
            </td>
			<td style='vertical-align:top;' >									              
				Serie
            </td>
			<td style='vertical-align:top;' >									              
				".$objData["objTransactionMaster"]->reference2."
            </td>
			<td style='vertical-align:top;' >									              
				Fecha de compra:
            </td>
            <td  style='text-align:left;vertical-align:top;' >              
				".$objData["objTransactionMasterInvoice"]->transactionOn."
            </td>            
          </tr>
         </table>";
         
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
	
$f_html = $f_html."
        <table style='width:98%;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >
          <tr>
            <td  style='text-align:center;vertical-align:top;widht:auto;font-weight: bold;' >              
				Problema reportado
            </td>                 
          </tr>
		  <tr>
            <td  style='text-align:left;vertical-align:top;widht:auto;' >              
				".$objData["objTransactionMaster"]->reference3."
            </td>                 
          </tr>
		  <tr>
            <td  style='text-align:center;vertical-align:top;width:auto;font-weight: bold;' >              
				Nota
            </td>                 
          </tr>
		  <tr>
            <td  style='text-align:left;vertical-align:top;width:auto;' >              
				<table style='width:100%'>
					<tr>
						<td colspan='4'>
							".$objData["objTransactionMaster"]->reference4."
						</td>
					</tr>
					<tr>
						<td style='width:150px'>
							Deja cargador o Accesorios:
						</td>
						<td style='text-align:left'>
							".$objData["objItemEstadosEquipo"]->name."
						</td>
						<td>
							Sello de garantia:
						</td>
						<td>
							".$objData["objItemMarca"]->name."
						</td>
					</tr>
				</table>
            </td>                 
          </tr>
         </table>";
		 
		 
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
    
    
$f_html = $f_html."
        <table style='width:98%' >
          <tr>
            <td style='text-align:justify;font-weight: bold;font-size:12px'>
              
            </td>
          </tr>
		  <tr>
            <td style='text-align:justify;font-weight: bold;font-size:12px'>
				-GLOBAL PRO no se hace responsable de pérdidas de información, por lo cual solicitamos hacer un respaldo previamente.</br>
				-Para hacer efectiva la garantía es necesario presentar la factura y la hoja de garantía.</br>
				-La garantía no aplica al sistema operativo y problemas de carga (alto voltaje), en batería, cargador o chip de carga.</br>
				-No se cubrirá la garantía si presenta mal manejo del operador como golpes, líquido en su interior, uso excesivo sin previo mantenimiento, equipos abiertos, entre otras malas acciones que hayan sido la causa para la falla del equipo.</br>
				-Todo equipo que cumpla con los requisitos de garantía y tenga falla de fábrica, será revisado por nuestros técnicos, y se cambiarán las piezas que sean necesarias para su perfecto funcionamiento.</br>
			 </td>
          </tr>
        </table>";
		
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
	
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";

$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
    

$f_html = $f_html."
		<!--
        <table style='width:98%' >
          <tr>
            <td style='text-align:center;font-weight: bold'>
              
            </td>
			<td style='text-align:center;font-weight: bold'>
              ---------------------------------
            </td>
			<td style='text-align:center;font-weight: bold'>
              
            </td>
          </tr>
		  <tr>
            <td style='text-align:center;font-weight: bold'>
              
            </td>
			<td style='text-align:center;font-weight: bold'>
              ".$objData["objCustomerNatural"]->firstName."
            </td>
			<td style='text-align:center;font-weight: bold'>
              
            </td>
          </tr>
		  <tr>
            <td style='text-align:center;font-weight: bold;'>              
			  
            </td>
			<td style='text-align:center;font-weight: bold'>
              ".$objData["objCustomer"]->identification."
            </td>
			<td style='text-align:center;font-weight: bold'>
              
            </td>
          </tr>
		  
        </table>
		-->
		";


  $html 				= $html.$f_html."</body></html>";	  
  return $html;
}


function helper_reporteA4mmTransactionMasterPedidosGlobalPro(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo,    	
    $objParameterTelefono, /*telefono*/	
    $statusName = "", /*estado*/
    $causalName = ""
)
{
    $path    		= PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
	$font_size1   	= "18px";
	$border_left 	= "border-left: 1px solid black;";
	$border_right 	= "border-right: 1px solid black;";
	$border_top 	= "border-top: 1px solid black;";
	$border_bottom 	= "border-bottom: 1px solid black;";
	$border_radius	= "border-radius: 10px;";
	$border_colapse = "border-collapse:separate;";
	
	
	
    $type    		= pathinfo($path, PATHINFO_EXTENSION);
    $data    		= file_get_contents($path);
    $base64  		= 'data:image/' . $type . ';base64,' . base64_encode($data);
    $numberDocument = str_replace("ESP","PEDIDO No ", $objTransactionMastser->transactionNumber);
	$tipoDocumento  = "PEDIDO";
	
	$telefono  		= "";
	
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
						
                        @page {       
                          size: Legal;   
						
						  
                          margin-top:25px;
                          margin-left:25px;
                          margin-right:20px;
						  margin-bottom: 25px;
						  
						  
						  padding-top: 0px;
						  padding-right: 0px;
						  padding-bottom: 0px;
						  padding-left: 0px;
						  
                        }
                        table{
                          font-size: xx-small;
                          font-family: sans-serif, monaco, monospace;						 
						  border-collapse: collapse;
                        }
						td{
                          font-size: xx-small;
                          font-family: sans-serif, monaco, monospace;
						  /*border: 1px solid black;*/
						  border-collapse: collapse;
                        }
                      </style>
                    </head>
        
                    <body>
					";
        
		$f_html = 	  "
                      <table style='width:98%'>
                        <tr>
						  <td  style='text-align:center;width:500px;text-align:left'>
                            <img  src='".$base64."' width='300'  >
                          </td>
                          <td  style='text-align:center;'>
									<table style='width:100%'>
										<tr>
										  <td  style='text-align:center;font-size:".$font_size1."; font-weight: bold;'>
											". $numberDocument ."
										  </td>
										</tr>
										
										<tr>
										  <td style='text-align:center'>
											Carretera Masaya, Frente al colegio Teresiano 
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											Edificio Delta RUC: 888-080396-0001K
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											&nbsp;
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											AUT.DGI. No. ASFC 02/0009/02/2023/2
										  </td>
										</tr>
									</table>
                          </td>
                        </tr>
					</table>
					
						";
    	   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
	/*
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td style='text-align:center' colspan='3'>[[TIPO_DOCUMENTO]]</td>
						<td style='text-align:center;width:70px'>".$causalName."</td>
						<td style='text-align:center;width:70px; font-weight: bold;'>Vendedor</td>
						<td style='text-align:center; font-weight: bold;'>".$objEmployerNatural->firstName." ".$objEmployerNatural->lastName."</td>
					</tr>
					<tr>
						<td style='text-align:center; font-weight: bold;".$border_left.$border_right.$border_top.$border_bottom."'>Cliente</td>
						<td style='text-align:left;".$border_left.$border_right.$border_top.$border_bottom."' colspan='3'>".$clientName."</td>						
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."'>Cedula</td>
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."'>".$objEntidadCustomer->numberIdentification."</td>
					</tr>
					<tr>
						<td style='text-align:center;width:70px; font-weight: bold;".$border_left.$border_right.$border_top.$border_bottom."' >Telefono</td>
						<td style='text-align:left;".$border_left.$border_right.$border_top.$border_bottom."' colspan='3'>".$telefono."</td>						
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."' >Fecha</td>
						<td style='text-align:center;width:250px;".$border_left.$border_right.$border_top.$border_bottom."' >".$objTransactionMastser->createdOn."</td>
					</tr>
				</table>
			";
	*/
	
	
	/*
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
	*/ 	
			
	$f_html = $f_html."
			<table style='width:98%' > 
				<tr style='background-color: #ccc;'>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >ARTICULO</td>
					<td style='text-align:center; font-weight: bold;"."' >DESCRIPCION</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Pre. Unit.</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Cant.</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Descuento</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >TOTAL</td>
				<tr>
			";
		
	
	
	$f_html = $f_html."
			</table>
			";
		   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
				
	
	$f_html = $f_html."
				  <table style='width:98%;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >
						<tr>
							<td  style='text-align:left;vertical-align:top;widht:auto;' >
								<table style='width:100%;'>
									<tr>
										<td style='font-size:".$font_size1.";font-weight:bold;' >Nota al cliente:</td>
									</tr>
									<tr>
										<td style='height:60px;text-justify: auto;  '>". substr($objTransactionMastser->note,0,450) ."</td>
									</tr>
								</table>
							</td>
							<td style='width:200px;vertical-align:top;' >									
								<table style='width:100%;'>
									<tr>										
										<td style='text-align:right;vertical-align: top;font-size:".$font_size1."; font-weight: bold;'>Totales:</td>								
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>																
									<tr>										
										<td style='text-align:right;padding-top:5px;'>No se aceptan devoluciones.</td>										
									</tr>						
								</table>
								
							</td>
							<td  style='text-align:left;vertical-align:top;widht:100px;' >
								
							</td>
							
						</tr>
						
						
				   </table>";
				   
				   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
			
	$f_html = $f_html."
				  <table style='width:98%' >
						<tr>
							<td style='text-align:center;text-decoration:underline;font-weight: bold;font-size:14px'>
								Al comprar tu equipo en GLOBAL PRO,puedes cambiarlo por uno superior cuando gustes!
							</td>
						</tr>
				   </table>";
	
	
	$f_html_copia 		= $f_html;
	$f_html_original 	= $f_html;
	$f_html_credito		= $f_html;
	
	$f_html_original 		= str_replace("[[TIPO_DOCUMENTO]]","ORIGINAL",$f_html_original);
	$f_html_copia 			= str_replace("[[TIPO_DOCUMENTO]]","COPIA",$f_html_copia);
	$f_html_credito 		= str_replace("[[TIPO_DOCUMENTO]]","COPIA",$f_html_copia);
	
	if($tipoDocumento == "PROFORMA")
	{
		$f_html				= $f_html_original;
	}
	else 
	{
		if(strtoupper($causalName) == strtoupper( "CREDITO" ) )
		$f_html				= $f_html_original;
		else 
		$f_html				= $f_html_original;
	
	}
	$html 				= $html.$f_html."</body></html>";	
	
	
	
              
       
    
    return $html;
}


function helper_reporteA4mmTransactionMasterInputUnpostGlobalProOnlyQuantity(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo,    
	  $objDetail,
    $objParameterTelefono, /*telefono*/
	  $objEmployerNatural , /*venedor*/
	  $objTelefonoEmployer , /*telefono cliente*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	  $userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    		= PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
	$font_size1   	= "18px";
	$border_left 	= "border-left: 1px solid black;";
	$border_right 	= "border-right: 1px solid black;";
	$border_top 	= "border-top: 1px solid black;";
	$border_bottom 	= "border-bottom: 1px solid black;";
	$border_radius	= "border-radius: 10px;";
	$border_colapse = "border-collapse:separate;";
	
	
	
    $type    		= pathinfo($path, PATHINFO_EXTENSION);
    $data    		= file_get_contents($path);
    $base64  		= 'data:image/' . $type . ';base64,' . base64_encode($data);
    $numberDocument = str_replace("ESP","COMPRA No ", $objTransactionMastser->transactionNumber);
	$tipoDocumento  = "COMPRA";
	$clientName		= $objEntidadNatural->firstName;
	$telefono  		= "";
	
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
						
                        @page {       
                          size: Legal;   
						
						  
                          margin-top:25px;
                          margin-left:25px;
                          margin-right:20px;
						  margin-bottom: 25px;
						  
						  
						  padding-top: 0px;
						  padding-right: 0px;
						  padding-bottom: 0px;
						  padding-left: 0px;
						  
                        }
                        table{
                          font-size: xx-small;
                          font-family: sans-serif, monaco, monospace;						 
						  border-collapse: collapse;
                        }
						td{
                          font-size: xx-small;
                          font-family: sans-serif, monaco, monospace;
						  /*border: 1px solid black;*/
						  border-collapse: collapse;
                        }
                      </style>
                    </head>
        
                    <body>
					";
        
		$f_html = 	  "
                      <table style='width:98%'>
                        <tr>
						  <td  style='text-align:center;width:500px;text-align:left'>
                            <img  src='".$base64."' width='300'  >
                          </td>
                          <td  style='text-align:center;'>
									<table style='width:100%'>
										<tr>
										  <td  style='text-align:center;font-size:".$font_size1."; font-weight: bold;'>
											". $numberDocument ."
										  </td>
										</tr>
										
										<tr>
										  <td style='text-align:center'>
											Carretera Masaya, Frente al colegio Teresiano 
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											Edificio Delta RUC: 888-080396-0001K
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											&nbsp;
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											AUT.DGI. No. ASFC 02/0009/02/2023/2
										  </td>
										</tr>
									</table>
                          </td>
                        </tr>
					</table>
					
						";
    	   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
	/*
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td style='text-align:center' colspan='3'>[[TIPO_DOCUMENTO]]</td>
						<td style='text-align:center;width:70px'>".$causalName."</td>
						<td style='text-align:center;width:70px; font-weight: bold;'>Vendedor</td>
						<td style='text-align:center; font-weight: bold;'>".$objEmployerNatural->firstName." ".$objEmployerNatural->lastName."</td>
					</tr>
					<tr>
						<td style='text-align:center; font-weight: bold;".$border_left.$border_right.$border_top.$border_bottom."'>Cliente</td>
						<td style='text-align:left;".$border_left.$border_right.$border_top.$border_bottom."' colspan='3'>".$clientName."</td>						
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."'>Cedula</td>
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."'>".$objEntidadCustomer->numberIdentification."</td>
					</tr>
					<tr>
						<td style='text-align:center;width:70px; font-weight: bold;".$border_left.$border_right.$border_top.$border_bottom."' >Telefono</td>
						<td style='text-align:left;".$border_left.$border_right.$border_top.$border_bottom."' colspan='3'>".$telefono."</td>						
						<td style='text-align:center;".$border_left.$border_right.$border_top.$border_bottom."' >Fecha</td>
						<td style='text-align:center;width:250px;".$border_left.$border_right.$border_top.$border_bottom."' >".$objTransactionMastser->createdOn."</td>
					</tr>
				</table>
			";
	*/
	
	
	/*
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
	*/ 	
			
	$f_html = $f_html."
			<table style='width:98%' > 
				<tr style='background-color: #ccc;'>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >ARTICULO</td>
					<td style='text-align:center; font-weight: bold;"."' >DESCRIPCION</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Pre. Unit.</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Cant.</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Descuento</td>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >TOTAL</td>
				<tr>
			";
		
	$total 		= 0;
	$subtotal 	= 0;
	$iva 		= 0;
	$descuento 	= 0;
	
	for($i = 0 ; $i < 22 ; $i++)
	{
		$count = count($objDetail);
		if($i < $count)
		{
			$total 		= 0; //$total + $objDetail[$i]->cost;
			$iva 		= 0; //$iva + $objDetail[$i]->tax1;
			$subtotal 	= 0; //$subtotal + $objDetail[$i]->cost;
			
			$f_html = $f_html."			
				<tr>
					<td style='text-align:left;width:70px' >".$objDetail[$i]->itemNumber."</td>
					<td style='text-align:left;' >".$objDetail[$i]->itemName."</td>
					<td style='text-align:center;width:70px' >".$objCurrency->simbol." ".number_format(round( 0 /*$objDetail[$i]->unitaryCost*/ ,2),2,".",",")."</td>
					<td style='text-align:center;width:70px' >".number_format(round($objDetail[$i]->quantity,2),2,".",",")."</td>
					<td style='text-align:center;width:70px' >".$objCurrency->simbol." 0.00</td>
					<td style='text-align:center;width:70px' >".$objCurrency->simbol." ".number_format(round(0  /*$objDetail[$i]->cost */ ,2),2,".",",")."</td>
				<tr>
			";
		}
		else
		{
			$f_html = $f_html."			
				<tr>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;width:70px' >&nbsp;</td>
				<tr>
			";
		}
	}
				
	$f_html = $f_html."
			</table>
			";
		   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
				
	
	$f_html = $f_html."
				  <table style='width:98%;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >
						<tr>
							<td  style='text-align:left;vertical-align:top;widht:auto;' >
								<table style='width:100%;'>
									<tr>
										<td style='font-size:".$font_size1.";font-weight:bold;' >Nota al cliente:</td>
									</tr>
									<tr>
										<td style='height:60px;text-justify: auto;  '>". substr($objTransactionMastser->note,0,450) ."</td>
									</tr>
								</table>
							</td>
							<td style='width:200px;vertical-align:top;' >									
								<table style='width:100%;'>
									<tr>										
										<td style='text-align:right;vertical-align: top;font-size:".$font_size1."; font-weight: bold;'>Totales:</td>								
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>																
									<tr>										
										<td style='text-align:right;padding-top:5px;'>No se aceptan devoluciones.</td>										
									</tr>						
								</table>
								
							</td>
							<td  style='text-align:left;vertical-align:top;widht:100px;' >
								<table style='width:100%;'>
									<tr>										
										<td style='width:70px'>Subtotal</td>
										<td style='text-align:right;width:70px'>".$objCurrency->simbol." ".number_format ( round($subtotal,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>
										<td>Descuentos</td>
										<td style='text-align:right'>".$objCurrency->simbol." 0.00</td>
									</tr>
									<tr>
										<td>Subtotal</td>
										<td style='text-align:right'>".$objCurrency->simbol." ".number_format ( round($subtotal,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>
										<td>IVA</td>
										<td style='text-align:right'>".$objCurrency->simbol." ".number_format ( round($iva,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>
										<td style=' font-weight: bold;'>TOTAL:</td>
										<td style='text-align:right'>".$objCurrency->simbol." ".number_format ( round( 0 /*$objTransactionMastser->amount*/ ,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>										
										<td></td>
										<td></td>
									</tr>	
									<tr>
										<td colspan='2'>*=Equipo seminuevo</td>
									</tr>									
								</table>
							</td>
							
						</tr>
						
						
				   </table>";
				   
				   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
			
	$f_html = $f_html."
				  <table style='width:98%' >
						<tr>
							<td style='text-align:center;text-decoration:underline;font-weight: bold;font-size:14px'>
								Al comprar tu equipo en GLOBAL PRO,puedes cambiarlo por uno superior cuando gustes!
							</td>
						</tr>
				   </table>";
	
	
	$f_html_copia 		= $f_html;
	$f_html_original 	= $f_html;
	$f_html_credito		= $f_html;
	
	$f_html_original 		= str_replace("[[TIPO_DOCUMENTO]]","ORIGINAL",$f_html_original);
	$f_html_copia 			= str_replace("[[TIPO_DOCUMENTO]]","COPIA",$f_html_copia);
	$f_html_credito 		= str_replace("[[TIPO_DOCUMENTO]]","COPIA",$f_html_copia);
	
	if($tipoDocumento == "PROFORMA")
	{
		$f_html				= $f_html_original;
	}
	else 
	{
		if(strtoupper($causalName) == strtoupper( "CREDITO" ) )
		$f_html				= $f_html_original;
		else 
		$f_html				= $f_html_original;
	
	}
	$html 				= $html.$f_html."</body></html>";	
	
	

              
       
    
    return $html;
}

function helper_reporteA4mmTransactionMasterOutputCashGlobalPro(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo,    
	$objDetail,
    $objParameterTelefono, /*telefono*/
	$objEmployerNatural , /*venedor*/
	$objTelefonoEmployer , /*telefono cliente*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    		= PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
	$font_size1   	= "18px";
	$border_left 	= "border-left: 1px solid black;";
	$border_right 	= "border-right: 1px solid black;";
	$border_top 	= "border-top: 1px solid black;";
	$border_bottom 	= "border-bottom: 1px solid black;";
	$border_radius	= "border-radius: 10px;";
	$border_colapse = "border-collapse:separate;";
	
	
	
    $type    		= pathinfo($path, PATHINFO_EXTENSION);
    $data    		= file_get_contents($path);
    $base64  		= 'data:image/' . $type . ';base64,' . base64_encode($data);
    $numberDocument = str_replace("ENC","SALIDA DE CAJA ", $objTransactionMastser->transactionNumber);
	$tipoDocumento  = "SALIDA DE CAJA";
	$clientName		= $userNickName;
	$telefono  		= "";
	
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
						
                        @page {       
                          size: Legal;   
						
						  
                          margin-top:25px;
                          margin-left:25px;
                          margin-right:20px;
						  margin-bottom: 25px;
						  
						  
						  padding-top: 0px;
						  padding-right: 0px;
						  padding-bottom: 0px;
						  padding-left: 0px;
						  
                        }
                        table{
                          font-size: xx-small;
                          font-family: sans-serif, monaco, monospace;						 
						  border-collapse: collapse;
                        }
						td{
                          font-size: xx-small;
                          font-family: sans-serif, monaco, monospace;
						  /*border: 1px solid black;*/
						  border-collapse: collapse;
                        }
                      </style>
                    </head>
        
                    <body>
					";
        
		$f_html = 	  "
                      <table style='width:98%'>
                        <tr>
						  <td  style='text-align:center;width:500px;text-align:left'>
                            <img  src='".$base64."' width='300'  >
                          </td>
                          <td  style='text-align:center;'>
									<table style='width:100%'>
										<tr>
										  <td  style='text-align:center;font-size:".$font_size1."; font-weight: bold;'>
											". $numberDocument ."
										  </td>
										</tr>
										
										<tr>
										  <td style='text-align:center'>
											Carretera Masaya, Frente al colegio Teresiano 
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											Edificio Delta RUC: 888-080396-0001K
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											&nbsp;
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											AUT.DGI. No. ASFC 02/0009/02/2023/2
										  </td>
										</tr>
									</table>
                          </td>
                        </tr>
					</table>
					
						";
    	   
	
		   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
				
	
	$f_html = $f_html."
				  <table style='width:98%;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >
						<tr>
							<td  style='text-align:left;vertical-align:top;widht:auto;' >
								<table style='width:100%;'>
									<tr>
										<td style='font-size:".$font_size1.";font-weight:bold;' >Comentario:</td>
									</tr>
									<tr>
										<td style='height:60px;text-justify: auto;  '>". substr($objTransactionMastser->note,0,450) ."</td>
									</tr>
								</table>
							</td>
							<td style='width:200px;vertical-align:top;' >									
								<table style='width:100%;'>
									<tr>										
										<td style='text-align:right;vertical-align: top;font-size:".$font_size1."; font-weight: bold;'>Totales:</td>								
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>
									<tr>
										<td>&nbsp;</td>										
									</tr>																
														
								</table>
								
							</td>
							<td  style='text-align:left;vertical-align:top;widht:100px;' >
								<table style='width:100%;'>
									<tr>										
										<td style='width:70px'>----</td>
										<td style='text-align:right;width:70px'>".$objCurrency->simbol." ".number_format ( round($objTransactionMastser->amount,2) , 2 , ".","," ) ."</td>
									</tr>							
								</table>
							</td>
							
						</tr>
						
						
				   </table>";
				   
				   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			";
			
			

	
	
	$f_html_copia 		= $f_html;
	$f_html_original 	= $f_html;
	$f_html_credito		= $f_html;
	
	
	$html 				= $html.$f_html."</body></html>";	    
    return $html;
}


function helper_reporteA4mmTransactionMasterShareGlobalPro(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo,    
	$objDetail,
    $objParameterTelefono, /*telefono*/
	$objEmployerNatural , /*venedor*/
	$objTelefonoEmployer , /*telefono cliente*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "", /*ruc*/
	$saldoInicial = 0,
	$saldoFinal = 0,
	$objDetalleAbonos = '',
	$objPropertyAbonosTotal = ''
)
{
    $path    		= PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
	$font_size1   	= "18px";
	$border_left 	= "border-left: 1px solid black;";
	$border_right 	= "border-right: 1px solid black;";
	$border_top 	= "border-top: 1px solid black;";
	$border_bottom 	= "border-bottom: 1px solid black;";
	$border_radius	= "border-radius: 10px;";
	$border_colapse = "border-collapse:separate;";
	
	
	
    $type    		= pathinfo($path, PATHINFO_EXTENSION);
    $data    		= file_get_contents($path);
    $base64  		= 'data:image/' . $type . ';base64,' . base64_encode($data);
    $numberDocument = str_replace("SHR","RECIBO No ",$objTransactionMastser->transactionNumber);
	$tipoDocumento  = "ABONO";
	$clientName		= $objEntidadNatural->firstName;
	$telefono  		= $objEntidadCustomer->phoneNumber;
	
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
						
                        @page {       
                          size: Legal;   
						
						  
                          margin-top:25px;
                          margin-left:25px;
                          margin-right:20px;
						  margin-bottom: 25px;
						  
						  
						  padding-top: 0px;
						  padding-right: 0px;
						  padding-bottom: 0px;
						  padding-left: 0px;
						  
                        }
                        table{
                          font-size: xx-small;
                          font-family: sans-serif, monaco, monospace;						 
						  border-collapse: collapse;
                        }
						td{
                          font-size: xx-small;
                          font-family: sans-serif, monaco, monospace;
						  /*border: 1px solid black;*/
						  border-collapse: collapse;
                        }
                      </style>
                    </head>
        
                    <body>
					";
        
		$f_html = 	  "
                      <table style='width:98%'>
                        <tr>
						  <td  style='text-align:center;width:500px;text-align:left'>
                            <img  src='".$base64."' width='300'  >
                          </td>
                          <td  style='text-align:center;'>
									<table style='width:100%'>
										<tr>
										  <td  style='text-align:center;font-size:".$font_size1."; font-weight: bold;'>
											". $numberDocument ."
										  </td>
										</tr>
										
										<tr>
										  <td style='text-align:center'>
											Carretera Masaya, Frente al colegio Teresiano 
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											Edificio Delta RUC: 888-080396-0001K
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											Teléfono: 2223-2314
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											AUT.DGI. No. ASFC 02/0009/02/2023/2
										  </td>
										</tr>
									</table>
                          </td>
                        </tr>
					</table>
					
						";
    	   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>				
				</table>
			";
			
			
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td style='text-align:center;".$border_bottom.";width:160px' >
							".$objDetail[0]->reference1."
						</td>
						<td style='text-align:center;".$border_bottom.";width:160px' >
							C$ ".number_format($objTransactionMastser->amount,2,'.',',')."
						</td>
						<td style='text-align:center;".$border_bottom.";width:160px' >
							".$objTransactionMastser->transactionOn."
						</td>
					</tr>
					<tr>
						<td style='text-align:center;width:160px'>
							FACTURA
						</td>
						<td style='text-align:center;width:160px' >
							MONTO
						</td>
						<td style='text-align:center;width:160px'>
							FECHA
						</td>
					</tr>
				</table>
			";
			
		   

		   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>				
				</table>
			";
				
	
	$f_html = $f_html."
				  <table style='width:98%;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td  style='text-align:right;vertical-align:top;width:120px;padding:5px' >
								Recibimos de:
							</td>
							<td style='width:500px;vertical-align:top;padding:5px;".$border_bottom."' >
								".$objEntidadNatural->firstName."
							</td>
							<td style='width:50px'>&nbsp;</td>
						</tr>
						<tr>
							<td  style='text-align:right;vertical-align:top;width:120px;padding:5px' >
								La cantidad de:
							</td>
							<td style='width:auto;vertical-align:top;padding:5px;".$border_bottom."' >
								".helper_GetLetras($objTransactionMastser->amount,"CORDOBAS CON ","")."
							</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td  style='text-align:right;vertical-align:top;width:120px;padding:5px' >
								En concepto de:
							</td>
							<td style='width:auto;vertical-align:top;padding:5px;".$border_bottom."' >
								".$objTransactionMastser->note."
							</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
				   </table>";
				   
				   
	
		   
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>				
				</table>
			";
				
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td style='text-align:center' >SALDO INICIAL:</td>
						<td style='text-align:left;".$border_bottom."' >C$ ".number_format($saldoInicial,2,'.',',')."</td>
						<td style='text-align:center' >ABONO:</td>
						<td style='text-align:left;".$border_bottom."' >C$ ".number_format($objTransactionMastser->amount,2,'.',',')."</td>
						<td style='text-align:center' >SALDO FINAL:</td>
						<td style='text-align:left;".$border_bottom."' >C$ ".number_format($saldoFinal,2,'.',',')."</td>
					</tr>							
				</table>
			";
			
		   

	
	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td>&nbsp;</td>						
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>				
				</table>
			";
				

	$f_html = $f_html."
				<table style='width:98%' >
					<tr>
						<td style='text-align:center' >_____________________</td>
						<td style='text-align:center' >_____________________</td>						
					</tr>							
					<tr>
						<td style='text-align:center' >".$objTransactionMastser->reference1."</td>
						<td style='text-align:center' >".$objEntidadNatural->firstName."</td>						
					</tr>							
				</table>
			";
			
	
	$f_html_copia 		= $f_html;
	$f_html_original 	= $f_html;
	$f_html_credito		= $f_html;
	
	$f_html_original 		= str_replace("[[TIPO_DOCUMENTO]]","ORIGINAL",$f_html_original);
	$f_html_copia 			= str_replace("[[TIPO_DOCUMENTO]]","COPIA",$f_html_copia);
	$f_html_credito 		= str_replace("[[TIPO_DOCUMENTO]]","COPIA",$f_html_copia);
	
	if($tipoDocumento == "PROFORMA")
	{
		$f_html				= $f_html_original;
	}
	else 
	{
		if(strtoupper($causalName) == strtoupper( "CREDITO" ) )
		$f_html				= $f_html_original;
		else 
		$f_html				= $f_html_original;
	
	}
	$html 				= $html.$f_html."</body></html>";	
	
	
    return $html;
}



function helper_reporte80mmTransactionMaster(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo, 
    $confiDetalle, /**/
    $arrayDetalle, /**/
    $objParameterTelefono, /*telefono*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 2.7in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='110'  >
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->name)."
                          </td>
                        </tr>";
    

          if($userNickName != "")
          {
            $html	= $html."<tr>                              
    						  <td colspan='3' style='text-align:center'>
                                RUC : ".  $rucCompany ."
                              </td>
                            </tr>";
          }
    
          $html = $html."<tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";
						
          if($userNickName != "")
		  {      
			$html	= $html."<tr>
                          <td colspan=''>
                            Vendedor:
                          </td>
						  <td colspan='2'>
                            ". (strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )   ."
                          </td>
                        </tr>";
		  }
						
          $html	= $html."<tr>
                          <td colspan=''>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>";
			
			
		  if($causalName != ""){
			$html	= $html."<tr>
							<td colspan='1'>
								Tipo:
							</td>
							<td colspan='2'>
								".$causalName."
							</td>
						</tr>";
		  }
			
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            Estado
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
                          </td>
                        </tr>
                            
						<tr>
                          <td colspan='1'>
                            Moneda:
                          </td>
						  <td colspan='2'>
                            ".$objCurrency->simbol."
                          </td>
                        </tr>";
			
		
						
          $html	= $html."
						<tr>
						  <td colspan='1'>
							Cliente:
                          <td colspan='2'>
                          </td>
                        </tr>
						
						<tr>
						  <td colspan='3'>							
                            ". ( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName." ".$objEntidadNatural->lastName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>

						
						<!--
                        <tr>
                          <td colspan='3'>
                            Tipo de Cambio: ".$tipoCambio."
                          </td>
                        </tr>
						-->
						
						<tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                                
                     
                                
                         [[DETALLE]]
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                             
                        <tr>
                          <td colspan='2'>
                            TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount)."
                          </td>
                        </tr>
   
                        <tr>
                          <td colspan='2'>
                            RECIBIDO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount + $objTransactionMasterInfo->changeAmount)."
                          </td>
                        </tr>
                         <tr>
                          <td colspan='2'>
                            CAMBIO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f", ($objTransactionMasterInfo->changeAmount)  )."
                          </td>
                        </tr>

                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objCompany->address."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objParameterTelefono->value."
                          </td>
                        </tr>


                        <tr>
                          <td colspan='3' style='text-align:center' >
                            sistema 505-8712-5827
                          </td>
                        </tr>
                                
                        

                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
	$rowin  = 0;
    foreach($arrayDetalle as $row){
		
            $colun  		= 0;
			$colunCantidad 	= count($row);
			$nuevaFila		= 0;
            foreach ($row as $col => $key){				
			
			    //encabezado
				if($rowin == 0){
					
					//Mostrar los productos en una sola fila
					if($colun == 0){
						$cuerpo 		= $cuerpo."<tr >";							
					}	
						
					$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style"]." colspan='".$confiDetalle[$colun]["colspan"]."' >";
					$cuerpo = $cuerpo." ".$key;
					$cuerpo = $cuerpo."</td>";
						
					if($colun == $colunCantidad ){
						$cuerpo 	   = $cuerpo."</tr >";
					}
					
				}
				//datos
				else{
					
					if( $confiDetalle[$colun]["nueva_fila_row_data"] ==  1 &&  strpos($key, "-comand-new-row") !== false  )
					{
						$cuerpo 		= $cuerpo."<tr >";							
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='". $colunCantidad ."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ". str_replace("-comand-new-row", "", $key );
						$cuerpo = $cuerpo."</td>";
						
						$cuerpo 		= $cuerpo."</tr >";	
						break;
											
					}	
					else
					{	
						
						if($rowin > 0 && $colun == 0)
						$cuerpo 	= $cuerpo."<tr >";								
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";								
						
						if($colun == $colunCantidad)
						$cuerpo 	= $cuerpo."</tr >";
							
					}
						
				
				}
				
                
				
                $colun++;
            }
			
			$rowin++;
            
    }
    
	
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
    return $html;
}

function helper_reporte80mmTransactionMasterCharLot(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo, 
    $confiDetalle, /**/
    $arrayDetalle, /**/
    $objParameterTelefono, /*telefono*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 2.7in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='110'  >
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->name)."
                          </td>
                        </tr>";
    

          if($userNickName != "")
          {
            $html	= $html."<tr>                              
    						  <td colspan='3' style='text-align:center'>
                                RUC : ".  $rucCompany ."
                              </td>
                            </tr>";
          }
    
          $html = $html."<tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";
						
          if($userNickName != "")
		  {      
			$html	= $html."<tr>
                          <td colspan=''>
                            Vendedor:
                          </td>
						  <td colspan='2'>
                            ". (strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )   ."
                          </td>
                        </tr>";
		  }
						
          $html	= $html."<tr>
                          <td colspan=''>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>";
			
			
		  if($causalName != ""){
			$html	= $html."<tr>
							<td colspan='1'>
								Tipo:
							</td>
							<td colspan='2'>
								".$causalName."
							</td>
						</tr>";
		  }
			
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            Estado
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
                          </td>
                        </tr>
                            
						<tr>
                          <td colspan='1'>
                            Moneda:
                          </td>
						  <td colspan='2'>
                            ".$objCurrency->simbol."
                          </td>
                        </tr>";
			
		
						
          $html	= $html."
						<tr>
						  <td colspan='1'>
							Cliente:
                          <td colspan='2'>
                          </td>
                        </tr>
						
						<tr>
						  <td colspan='3'>							
                            ". ( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName." ".$objEntidadNatural->lastName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>

						
						<!--
                        <tr>
                          <td colspan='3'>
                            Tipo de Cambio: ".$tipoCambio."
                          </td>
                        </tr>
						-->
						
						<tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                                
                     
                                
                         [[DETALLE]]
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                             
                        <tr>
                          <td colspan='2'>
                            TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount)."
                          </td>
                        </tr>
   
                        <tr>
                          <td colspan='2'>
                            RECIBIDO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount + $objTransactionMasterInfo->changeAmount)."
                          </td>
                        </tr>
                         <tr>
                          <td colspan='2'>
                            CAMBIO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f", ($objTransactionMasterInfo->changeAmount)  )."
                          </td>
                        </tr>

                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objCompany->address."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objParameterTelefono->value."
                          </td>
                        </tr>


                        <tr>
                          <td colspan='3' style='text-align:center' >
                            sistema 505-8712-5827
                          </td>
                        </tr>
                                
                        

                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
	$rowin  = 0;
    foreach($arrayDetalle as $row){
		
            $colun  		= 0;
			$colunCantidad 	= count($row);
			$nuevaFila		= 0;
            foreach ($row as $col => $key){				
			
			    //encabezado
				if($rowin == 0){
					
					//Mostrar los productos en una sola fila
					if($colun == 0){
						$cuerpo 		= $cuerpo."<tr >";							
					}	
						
					$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style"]." colspan='".$confiDetalle[$colun]["colspan"]."' >";
					$cuerpo = $cuerpo." ".$key;
					$cuerpo = $cuerpo."</td>";
						
					if($colun == $colunCantidad ){
						$cuerpo 	   = $cuerpo."</tr >";
					}
					
				}
				//datos
				else{
					
					if( $confiDetalle[$colun]["nueva_fila_row_data"] ==  1 &&  strpos($key, "-comand-new-row") !== false  )
					{
						$cuerpo 		= $cuerpo."<tr >";							
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='". $colunCantidad ."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ". str_replace("-comand-new-row", "", $key );
						$cuerpo = $cuerpo."</td>";
						
						$cuerpo 		= $cuerpo."</tr >";	
						break;
											
					}	
					else
					{	
						
						if($rowin > 0 && $colun == 0)
						$cuerpo 	= $cuerpo."<tr >";								
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";								
						
						if($colun == $colunCantidad)
						$cuerpo 	= $cuerpo."</tr >";
							
					}
						
				
				}
				
                
				
                $colun++;
            }
			
			$rowin++;
            
    }
    
	
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
    return $html;
}


function helper_reporte80mmTransactionMasterFarmaJireth(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo, 
    $confiDetalle, /**/
    $arrayDetalle, /**/
    $objParameterTelefono, /*telefono*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 2.7in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
						
						<!--
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='110'  >
                          </td>
                        </tr>
						-->
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->name)."
                          </td>
                        </tr>";
    

          if($userNickName != "")
          {
            $html	= $html."<tr>                              
    						  <td colspan='3' style='text-align:center'>
                                RUC : ".  $rucCompany ."
                              </td>
                            </tr>";
          }
    
          $html = $html."<tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";
						
          if($userNickName != "")
		  {      
			$html	= $html."<tr>
                          <td colspan=''>
                            Vendedor:
                          </td>
						  <td colspan='2'>
                            ". (strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )   ."
                          </td>
                        </tr>";
		  }
						
          $html	= $html."<tr>
                          <td colspan=''>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>";
			
			
		  if($causalName != ""){
			$html	= $html."<tr>
							<td colspan='1'>
								Tipo:
							</td>
							<td colspan='2'>
								".$causalName."
							</td>
						</tr>";
		  }
			
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            Estado
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
                          </td>
                        </tr>
                            
						<tr>
                          <td colspan='1'>
                            Moneda:
                          </td>
						  <td colspan='2'>
                            ".$objCurrency->simbol."
                          </td>
                        </tr>";
			
		
						
          $html	= $html."
						<tr>
						  <td colspan='1'>
							Cliente:
                          <td colspan='2'>
                          </td>
                        </tr>
						
						<tr>
						  <td colspan='3'>							
                            ". ( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName." ".$objEntidadNatural->lastName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>

						
						<!--
                        <tr>
                          <td colspan='3'>
                            Tipo de Cambio: ".$tipoCambio."
                          </td>
                        </tr>
						-->
						
						<tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                                
                     
                                
                         [[DETALLE]]
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                             
                        <tr>
                          <td colspan='2'>
                            TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount)."
                          </td>
                        </tr>
   
                        <tr>
                          <td colspan='2'>
                            RECIBIDO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount + $objTransactionMasterInfo->changeAmount)."
                          </td>
                        </tr>
                         <tr>
                          <td colspan='2'>
                            CAMBIO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f", ($objTransactionMasterInfo->changeAmount)  )."
                          </td>
                        </tr>

                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objCompany->address."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objParameterTelefono->value."
                          </td>
                        </tr>


                        <tr>
                          <td colspan='3' style='text-align:center' >
                            sistema 505-8712-5827
                          </td>
                        </tr>
                                
                        

                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
	$rowin  = 0;
    foreach($arrayDetalle as $row){
		
            $colun  		= 0;
			$colunCantidad 	= count($row);
			$nuevaFila		= 0;
            foreach ($row as $col => $key){				
			
			    //encabezado
				if($rowin == 0){
					
					//Mostrar los productos en una sola fila
					if($colun == 0){
						$cuerpo 		= $cuerpo."<tr >";							
					}	
						
					$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style"]." colspan='".$confiDetalle[$colun]["colspan"]."' >";
					$cuerpo = $cuerpo." ".$key;
					$cuerpo = $cuerpo."</td>";
						
					if($colun == $colunCantidad ){
						$cuerpo 	   = $cuerpo."</tr >";
					}
					
				}
				//datos
				else{
					
					if( $confiDetalle[$colun]["nueva_fila_row_data"] ==  1 &&  strpos($key, "-comand-new-row") !== false  )
					{
						$cuerpo 		= $cuerpo."<tr >";							
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='". $colunCantidad ."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ". str_replace("-comand-new-row", "", $key );
						$cuerpo = $cuerpo."</td>";
						
						$cuerpo 		= $cuerpo."</tr >";	
						break;
											
					}	
					else
					{	
						
						if($rowin > 0 && $colun == 0)
						$cuerpo 	= $cuerpo."<tr >";								
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";								
						
						if($colun == $colunCantidad)
						$cuerpo 	= $cuerpo."</tr >";
							
					}
						
				
				}
				
                
				
                $colun++;
            }
			
			$rowin++;
            
    }
    
	
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
    return $html;
}

function helper_reporte80mmTransactionMasterBivalyStore(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo, 
    $confiDetalle, /**/
    $arrayDetalle, /**/
    $objParameterTelefono, /*telefono*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 2.7in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='110'  >
                          </td>
                        </tr>
                        <!--    
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->name)."
                          </td>
                        </tr>
						-->
						";
    

          if($userNickName != "")
          {
            $html	= $html."
							<!--
							<tr>                              
    						  <td colspan='3' style='text-align:center'>
                                RUC : ".  $rucCompany ."
                              </td>
                            </tr>
							-->
							";
          }
    
          $html = $html."
						<!--
						<tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                        -->
						
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";
						
          if($userNickName != "")
		  {      
			$html	= $html."<tr>
                          <td colspan=''>
                            Vendedor:
                          </td>
						  <td colspan='2'>
                            ". (strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )   ."
                          </td>
                        </tr>";
		  }
						
          $html	= $html."<tr>
                          <td colspan=''>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>";
			
			
		  if($causalName != ""){
			$html	= $html."<tr>
							<td colspan='1'>
								Tipo:
							</td>
							<td colspan='2'>
								".$causalName."
							</td>
						</tr>";
		  }
			
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            Estado
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
                          </td>
                        </tr>
                            
						<tr>
                          <td colspan='1'>
                            Moneda:
                          </td>
						  <td colspan='2'>
                            ".$objCurrency->simbol."
                          </td>
                        </tr>";
			
		
						
          $html	= $html."
						<tr>
						  <td colspan='1'>
							Cliente:
                          <td colspan='2'>
                          </td>
                        </tr>
						
						<tr>
						  <td colspan='3'>							
                            ". ( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName." ".$objEntidadNatural->lastName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>

						
						<!--
                        <tr>
                          <td colspan='3'>
                            Tipo de Cambio: ".$tipoCambio."
                          </td>
                        </tr>
						-->
						
						<tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                                
                     
                                
                         [[DETALLE]]
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                             
                        <tr>
                          <td colspan='2'>
                            TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount)."
                          </td>
                        </tr>
   
                        <tr>
                          <td colspan='2'>
                            RECIBIDO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount + $objTransactionMasterInfo->changeAmount)."
                          </td>
                        </tr>
                         <tr>
                          <td colspan='2'>
                            CAMBIO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f", ($objTransactionMasterInfo->changeAmount)  )."
                          </td>
                        </tr>

                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objCompany->address."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objParameterTelefono->value."
                          </td>
                        </tr>


                        <tr>
                          <td colspan='3' style='text-align:center' >
                            sistema 505-8712-5827
                          </td>
                        </tr>
                                
                        

                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
	$rowin  = 0;
    foreach($arrayDetalle as $row){
		
            $colun  		= 0;
			$colunCantidad 	= count($row);
			$nuevaFila		= 0;
            foreach ($row as $col => $key){				
			
			    //encabezado
				if($rowin == 0){
					
					//Mostrar los productos en una sola fila
					if($colun == 0){
						$cuerpo 		= $cuerpo."<tr >";							
					}	
						
					$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style"]." colspan='".$confiDetalle[$colun]["colspan"]."' >";
					$cuerpo = $cuerpo." ".$key;
					$cuerpo = $cuerpo."</td>";
						
					if($colun == $colunCantidad ){
						$cuerpo 	   = $cuerpo."</tr >";
					}
					
				}
				//datos
				else{
					
					if( $confiDetalle[$colun]["nueva_fila_row_data"] ==  1 &&  strpos($key, "-comand-new-row") !== false  )
					{
						$cuerpo 		= $cuerpo."<tr >";							
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='". $colunCantidad ."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ". str_replace("-comand-new-row", "", $key );
						$cuerpo = $cuerpo."</td>";
						
						$cuerpo 		= $cuerpo."</tr >";	
						break;
											
					}	
					else
					{	
						
						if($rowin > 0 && $colun == 0)
						$cuerpo 	= $cuerpo."<tr >";								
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";								
						
						if($colun == $colunCantidad)
						$cuerpo 	= $cuerpo."</tr >";
							
					}
						
				
				}
				
                
				
                $colun++;
            }
			
			$rowin++;
            
    }
    
	
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
    return $html;
}


function helper_reporte80mmTransactionMasterMiranda(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo, 
    $confiDetalle, /**/
    $arrayDetalle, /**/
    $objParameterTelefono, /*telefono*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 2.7in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='110'  >
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->name)."
                          </td>
                        </tr>";
    

          if($userNickName != "")
          {
            $html	= $html."
							<!--
							<tr>                              
    						  <td colspan='3' style='text-align:center'>
                                RUC : ".  $rucCompany ."
                              </td>
                            </tr>
							-->
							";
          }
    
          $html = $html."<tr>
                          <td colspan='3' style='text-align:center'>
                            CODIGO
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".str_replace("FAC","# VAUCHER ",strtoupper($objTransactionMastser->transactionNumber))."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";
						
          if($userNickName != "")
		  {      
			$html	= $html."<tr>
                          <td colspan=''>
                            Vendedor:
                          </td>
						  <td colspan='2'>
                            ". (strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )   ."
                          </td>
                        </tr>";
		  }
						
          $html	= $html."<tr>
                          <td colspan=''>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>";
			
			
		  if($causalName != ""){
			$html	= $html."<tr>
							<td colspan='1'>
								Tipo:
							</td>
							<td colspan='2'>
								".$causalName."
							</td>
						</tr>";
		  }
			
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            Estado
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
                          </td>
                        </tr>
                            
						<tr>
                          <td colspan='1'>
                            Moneda:
                          </td>
						  <td colspan='2'>
                            ".$objCurrency->simbol."
                          </td>
                        </tr>";
			
		
						
          $html	= $html."
						<tr>
						  <td colspan='1'>
							Cliente:
                          <td colspan='2'>
                          </td>
                        </tr>
						
						<tr>
						  <td colspan='3'>							
                            ". ( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName." ".$objEntidadNatural->lastName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>

						
						<!--
                        <tr>
                          <td colspan='3'>
                            Tipo de Cambio: ".$tipoCambio."
                          </td>
                        </tr>
						-->
						
						<tr>
                          <td colspan='3' style='text-align:left'>Gracias por su compra <!--".$objTransactionMastser->note."--> </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                                
                     
                                
                         [[DETALLE]]
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                             
                        <tr>
                          <td colspan='2'>
                            TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount)."
                          </td>
                        </tr>
   
                        <tr>
                          <td colspan='2'>
                            RECIBIDO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount + $objTransactionMasterInfo->changeAmount)."
                          </td>
                        </tr>
                         <tr>
                          <td colspan='2'>
                            CAMBIO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f", ($objTransactionMasterInfo->changeAmount)  )."
                          </td>
                        </tr>

                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objCompany->address."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objParameterTelefono->value."
                          </td>
                        </tr>


                        <tr>
                          <td colspan='3' style='text-align:center' >
                            sistema 505-8712-5827
                          </td>
                        </tr>
                                
                        

                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
	$rowin  = 0;
    foreach($arrayDetalle as $row){
		
            $colun  		= 0;
			$colunCantidad 	= count($row);
			$nuevaFila		= 0;
            foreach ($row as $col => $key){				
			
			    //encabezado
				if($rowin == 0){
					
					//Mostrar los productos en una sola fila
					if($colun == 0){
						$cuerpo 		= $cuerpo."<tr >";							
					}	
						
					$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style"]." colspan='".$confiDetalle[$colun]["colspan"]."' >";
					$cuerpo = $cuerpo." ".$key;
					$cuerpo = $cuerpo."</td>";
						
					if($colun == $colunCantidad ){
						$cuerpo 	   = $cuerpo."</tr >";
					}
					
				}
				//datos
				else{
					
					if( $confiDetalle[$colun]["nueva_fila_row_data"] ==  1 &&  strpos($key, "-comand-new-row") !== false  )
					{
						$cuerpo 		= $cuerpo."<tr >";							
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='". $colunCantidad ."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ". str_replace("-comand-new-row", "", $key );
						$cuerpo = $cuerpo."</td>";
						
						$cuerpo 		= $cuerpo."</tr >";	
						break;
											
					}	
					else
					{	
						
						if($rowin > 0 && $colun == 0)
						$cuerpo 	= $cuerpo."<tr >";								
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";								
						
						if($colun == $colunCantidad)
						$cuerpo 	= $cuerpo."</tr >";
							
					}
						
				
				}
				
                
				
                $colun++;
            }
			
			$rowin++;
            
    }
    
	
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
    return $html;
}

function helper_reporte80mmTransactionMasterBillingChic(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo, 
    $confiDetalle, /**/
    $arrayDetalle, /**/
    $objParameterTelefono, /*telefono*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 2.7in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
					  
                        <tr>
                          <td colspan='3' style='text-align:center'>
							<!--
                            <img  src='".$base64."' width='110'  >
							--> 
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <!--".strtoupper($objCompany->name)."-->
                          </td>
                        </tr>";
    

          if($userNickName != "")
          {
            $html	= $html."<tr>                              
    						  <td colspan='3' style='text-align:center'>
                                RUC : ".  $rucCompany ."
                              </td>
                            </tr>";
          }
    
          $html = $html."<tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";
						
          if($userNickName != "")
		  {      
			$html	= $html."<tr>
                          <td colspan=''>
                            Vendedor:
                          </td>
						  <td colspan='2'>
                            ". (strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )   ."
                          </td>
                        </tr>";
		  }
						
          $html	= $html."<tr>
                          <td colspan=''>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>";
			
			
		  if($causalName != ""){
			$html	= $html."<tr>
							<td colspan='1'>
								Tipo:
							</td>
							<td colspan='2'>
								".$causalName."
							</td>
						</tr>";
		  }
			
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            Estado
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
                          </td>
                        </tr>
                            
						<tr>
                          <td colspan='1'>
                            Moneda:
                          </td>
						  <td colspan='2'>
                            ".$objCurrency->simbol."
                          </td>
                        </tr>";
			
		
						
          $html	= $html."
						<tr>
						  <td colspan='1'>
							Cliente:
                          <td colspan='2'>
                          </td>
                        </tr>
						
						<tr>
						  <td colspan='3'>							
                            ". ( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName." ".$objEntidadNatural->lastName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>

						
						<!--
                        <tr>
                          <td colspan='3'>
                            Tipo de Cambio: ".$tipoCambio."
                          </td>
                        </tr>
						-->
						
						<tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                                
                     
                                
                         [[DETALLE]]
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                             
                        <tr>
                          <td colspan='2'>
                            TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount)."
                          </td>
                        </tr>
   
                        <tr>
                          <td colspan='2'>
                            RECIBIDO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount + $objTransactionMasterInfo->changeAmount)."
                          </td>
                        </tr>
                         <tr>
                          <td colspan='2'>
                            CAMBIO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f", ($objTransactionMasterInfo->changeAmount)  )."
                          </td>
                        </tr>

                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objCompany->address."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objParameterTelefono->value."
                          </td>
                        </tr>


                        <tr>
                          <td colspan='3' style='text-align:center' >
                            sistema 505-8712-5827
                          </td>
                        </tr>
                                
                        

                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
	$rowin  = 0;
    foreach($arrayDetalle as $row){
		
            $colun  		= 0;
			$colunCantidad 	= count($row);
			$nuevaFila		= 0;
            foreach ($row as $col => $key){				
			
			    //encabezado
				if($rowin == 0){
					
					//Mostrar los productos en una sola fila
					if($colun == 0){
						$cuerpo 		= $cuerpo."<tr >";							
					}	
						
					$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style"]." colspan='".$confiDetalle[$colun]["colspan"]."' >";
					$cuerpo = $cuerpo." ".$key;
					$cuerpo = $cuerpo."</td>";
						
					if($colun == $colunCantidad ){
						$cuerpo 	   = $cuerpo."</tr >";
					}
					
				}
				//datos
				else{
					
					if( $confiDetalle[$colun]["nueva_fila_row_data"] ==  1 &&  strpos($key, "-comand-new-row") !== false  )
					{
						$cuerpo 		= $cuerpo."<tr >";							
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='". $colunCantidad ."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ". str_replace("-comand-new-row", "", $key );
						$cuerpo = $cuerpo."</td>";
						
						$cuerpo 		= $cuerpo."</tr >";	
						break;
											
					}	
					else
					{	
						
						if($rowin > 0 && $colun == 0)
						$cuerpo 	= $cuerpo."<tr >";								
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";								
						
						if($colun == $colunCantidad)
						$cuerpo 	= $cuerpo."</tr >";
							
					}
						
				
				}
				
                
				
                $colun++;
            }
			
			$rowin++;
            
    }
    
	
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
    return $html;
}


function helper_reporte80mmTransactionMasterFunBlandon(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo, 
    $confiDetalle, /**/
    $arrayDetalle, /**/
    $objParameterTelefono, /*telefono*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 2.7in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='110'  >
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->name)."
                          </td>
                        </tr>";
    

          if($userNickName != "")
          {
            $html	= $html."<tr>                              
    						  <td colspan='3' style='text-align:center'>
                                RUC : ".  $rucCompany ."
                              </td>
                            </tr>";
          }
    
          $html = $html."<tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";
						
          if($userNickName != "")
		  {      
			$html	= $html."<tr>
                          <td colspan=''>
                            Vendedor:
                          </td>
						  <td colspan='2'>
                            ". (strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )   ."
                          </td>
                        </tr>";
		  }
						
          $html	= $html."<tr>
                          <td colspan=''>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>";
			
			
		  if($causalName != ""){
			$html	= $html."<tr>
							<td colspan='1'>
								Tipo:
							</td>
							<td colspan='2'>
								".$causalName."
							</td>
						</tr>";
		  }
			
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            Estado
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
                          </td>
                        </tr>
                            
						<tr>
                          <td colspan='1'>
                            Moneda:
                          </td>
						  <td colspan='2'>
                            ".$objCurrency->simbol."
                          </td>
                        </tr>";
			
		
						
          $html	= $html."
						<tr>
						  <td colspan='1'>
							Cliente:
                          <td colspan='2'>
                          </td>
                        </tr>
						
						<tr>
						  <td colspan='3'>							
                            ". ( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName." ".$objEntidadNatural->lastName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>

						
						<tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                                
                     
                                
                         [[DETALLE]]
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                            

                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objCompany->address."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objParameterTelefono->value."
                          </td>
                        </tr>

                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
	$rowin  = 0;
    foreach($arrayDetalle as $row){
		
            $colun  		= 0;
			$colunCantidad 	= count($row);
			$nuevaFila		= 0;
            foreach ($row as $col => $key){				
			
			    //encabezado
				if($rowin == 0){
					
					//Mostrar los productos en una sola fila
					if($colun == 0){
						$cuerpo 		= $cuerpo."<tr >";							
					}	
						
					$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style"]." colspan='".$confiDetalle[$colun]["colspan"]."' >";
					$cuerpo = $cuerpo." ".$key;
					$cuerpo = $cuerpo."</td>";
						
					if($colun == $colunCantidad ){
						$cuerpo 	   = $cuerpo."</tr >";
					}
					
				}
				//datos
				else{
					
					if( $confiDetalle[$colun]["nueva_fila_row_data"] ==  1 &&  strpos($key, "-comand-new-row") !== false  )
					{
						$cuerpo 		= $cuerpo."<tr >";							
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='". $colunCantidad ."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ". str_replace("-comand-new-row", "", $key );
						$cuerpo = $cuerpo."</td>";
						
						$cuerpo 		= $cuerpo."</tr >";	
						break;
											
					}	
					else
					{	
						
						if($rowin > 0 && $colun == 0)
						$cuerpo 	= $cuerpo."<tr >";								
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";								
						
						if($colun == $colunCantidad)
						$cuerpo 	= $cuerpo."</tr >";
							
					}
						
				
				}
				
                
				
                $colun++;
            }
			
			$rowin++;
            
    }
    
	
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
    return $html;
}


function helper_reporte80mmTransactionMasterFunBlandonReciboOficialCaja(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo,     
    $objParameterTelefono, /*telefono*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
					  <title>Recibo Oficial de Caja</title>
                      <style>
                        @page {       
                          size: A4;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
						
						body {
							font-family: Arial, sans-serif;
							margin: 20px;
						}		

					
						
                      </style>
                    </head>
        
                    <body>
						<p style='position:absolute;top:162px;left:125px' >".$objEntidadNatural->firstName." ".$objEntidadNatural->lastName."</p>
						
						<p style='position:absolute;top:190px;left:140px' >".helper_GetLetras($objTransactionMastser->amount,$objCurrency->simbol,"centavos")."</p>
						<!--<p style='position:absolute;top:218px;left:25px' >".helper_GetLetras($objTransactionMastser->amount,$objCurrency->simbol,"centavos")."</p>-->
						
						<p style='position:absolute;top:246px;left:140px' >".$objTransactionMastser->note."</p>
						<!--<p style='position:absolute;top:271px;left:25px' >".helper_GetLetras($objTransactionMastser->amount,$objCurrency->simbol,"centavos")."</p>-->
						
						<table style='width:100%'>
							<tbody>
								<tr>
									<td style='width:33%'>
										<img src='data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAA9AGcDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD+/J/vH8P5Cm05/vH8P5Cm0AFFFYutHXPIhTRLLTb5priOC+TUdZvtCNvp8uUubqyvLDR9alkvYEO+3tWhs1nfA/tOyKiQtK7S283p+dhN2Tdm7dEm2zaorxvW7lfhFpXi/wCIvjP4o+Mtb8K6Zb3OpnQdcs/AY0/SYX+zwWWkaHLoXgzQvEWoXNxe+XZ6VFq+uavqF7qGpLbS3VxutUh/P3VP2jP2o/i3DqmsfDGzi8BafpV5EYPCk+knSta1LSpN6uE8Z+NPCOueGde1o/Z5WtodAh0jQEmuLW1v/FFvZFNcuvz7xB8T+DPDPD4bEcT5rTw8sZOMMLh6fJGtVcm4Kc5YqphsNhqCqL2TxOLxFDDqp7vtbJtfXcM8GZxxNhcVmMHhcqyvBTdGvmWZ1KywzxPIqv1TC0sDh8bjcbilRkq8qGEwtaVKk4zq+z9pSU/1m/8A10gIJx749jxnr079emcjqDX5RaV8a/2q/CUGp6lquo2fi6PTvs6an4U8X2nheOa3S4toNQa0s/EPgFNLu9M1r7BMge5vI9csrM3sNxbW2pIqGvu74b+JtL+JPw5g8e/DvUdfSfW4neOx8Q63fedYazodxNZ3nhrUv7ZtfF1tosa6hazadqd/pWl3rzWjnUdNmvopLC6kx4C8VeDPEaeOw/D+Y3zDLYUKuLy7EPDuvDD4mFKrQxVOthMTisHicJWp1qU6eIweJxFNwq05ScI1KblrxJwNnHDmX4TOva4HN8jxlZ4Wnm2VTxfsaOMUZzjgsXhcwweAx+DxNSFKs6cMVhKMKvsayo1KkqVWMPcKK4rTdE8VDUotW1PxfqKwSxie48JQ2/hy90azu54lW4srXXF8K6Prt9plk4zp0062eoSSmSe/lngkisLXpdOk1WSO4OrWdhZyrdzJapp+o3GpRzWK7fs9xcSXGl6U1tdy5fzrOOO7hgwgS+uNzFf0iUUrWnGd4pvl5k4t7xanGLbj1cOaG3vao+KhNy3hOGrtzcr5kvtLklKyd1ZT5J94p3RoUUUVJYUUUUAOf7x/D+QptZHiLxFoHhTSdR8Q+J9b0nw7oOlWz3mqa3ruo2ek6Tp1rEu6S4vtR1Ca3s7SBFBLSzzRxrg5YV+Ivxm/4Lh/C7wT4h8S6N8LPhbe/FTSNGuLnTNJ8aXHj3R/C+heJtXinsgkmj2dto/ijVp/DX2Vdbkk1+4t7NY7y10K2ezjs/EY1TS+XE43DYOMZYirGkpX5b8zb5d7KEZS7K9t2l1Pz/jzxT4B8MsNhsTxtxHg8kjjZyhg6E6eJxeMxLhBzm6WCwFDE4p04qylWdFUYzlCEqinUhGX7o5or84P2N/+CkPw4/aovV8EeIPC+q/Bn4xw22qXl14A8UXyzWN5BYajBbLD4Y8Q6lYeGbrxHqa2d9YXep6bD4ctLnS5JLuIi6tILfUb36w/aG1bxDoXwr8Sax4d1u50O7s0sI2uLKOH7XLDqWpWelyxw3civJYkQ30kqXVmIb6KaOGS1urdkLN89xZxbg+FOEOIeMamGxOZ4LhzJsxzvFYTAujDF18PleGni8TSpLF1cNRjVjRpzbjVqwldcsIzm4wl7nB/F/DXHuUYXPuE84wuc5Vi5ckMTh3OMqVVKLnh8Vh6sYYnCYqkpL2uFxNKlXpt2nBHiX7afivw7qPw1vfh/Za3aTeL5td8KaquixSGYLaaLr+l6zdR6vJCJEsBJaQNNYwzgy3d6tkyxC3W4urX5J1PUrLxN4Mh0rR/i34x+DfiRIBbf8JB4V03S9cliidBFPC+i+I9B8RaFMWVm239vaWuq2xw9lqNs6rIvjnjPxp4Z+HvgfUfiD4x0zxL4iVvGfhrwtHZ6Hq+nabdPd+I9F8Za1LqN5earpurCfavhPyvLWOOaWa7eeSViCG3tSjsA+nXWlreR6frHh3wr4jtIdQkhnvLWHxP4Z0nxElpcXFvDbwXEloNT+zmeK3gSURBxEhJFf5E+N/iB448U8PcG/SPzPg/hnIfDbibMcfwrwJOdbLeIamKeWYnNHjcHnOV46eIjipOtl+NlWrYvJ8NhnKlSlhIwfs6j/V8l8RsPRweYeGuHr4TF18qxK4gxeGdLG0cZgqmbYfC0IVo42hKhRnSq0sLS5KUalWpFubbipcq3fh94hTwH4BvPDV0jeJfFGv+JPFXifxR4h+2a0tlqOreJdWuNQlvLYeINS1vVljSOSGBLAzadYWkUS22mWlhZRw28bfB3jzxj4A0LxD4b8IeItQ0LTPE/iK88U6stgYILx9VvbDSNLlktNSSBdSsIVs9FsY1js7uElzNJK8jTGuTrkvij468R/Db4SeIvFvhKTRrXX08W+CdHjvtY8K+FfFqRafqNp4tnvYILLxbouuafbtcS6faM9zFaR3OIURZgjOrfkHhRifFTx38acu4M4b41fBXEfilmWJy6rmGX18wyfK6KWCrYz6nUjlEnjaeWKlgIYelhKU506VKnRpcvsqaUeLibjrF8PcHZpPMK+LqcO5XQWZYvLcJToSlXlg3zU6lqkqPtq0HOU4+1rq8pSlzJuz+3fhV+1V4o8JvBpPjg3fjDQgdiX0k4bxJp+9lLMLudgusQqgkxBqMsd1udNmppDClsf0M8H+OPC3j3Sl1jwprFrq1kSEm8lmS5s5iMm2v7OVY7myuQMMIbmKNniZJo98Mkcjfgn8JPH/ib4m/B3S/FfjCXRbvX4/iV4/8PfbtG8JeEfCIk0fTfC/ws1GwtLq08H6FoNleG1vda1WaG4u7ea6T7bLH55hEccf67fso6DoUXww0fxBHo2lR6/c3Gu2lzrUen2qatPaprN0I7efUViF3LAixRKsMkzRoI0CqAigf6B+B2e+NPhn9IDir6LfHnFWT8eYPgbJ6eOqZ5XlmMsbhqMsDkWMpYbKsyr0vreOo06ed4eDoZvSlKP1ecMPisPSVOD+QybOsu4o4eyzibLqOJw+EzWh9YoUsVCnTrxp+0nT/AH1OnVrU4yvB606s1Zpt3ul9RZor85/2xP8AgpH8If2Vfsfh/S4dL+L/AMTru4v4rnwD4c8c+GdJl8LW2mW01xd6l451GZ9Uu/DNnEYZF2toV9dYgu5pIIorZi/xh8N/+C5XgnxP47urDxr8CvFXhL4WT3t5ZaT8QdI8Rw+JNRtbiO3ju7SHxZ4buvD/AIdsNFVbNLq48Q3ln4p1S10CVrZIm1jSGfX1/u6pmeBpVfYVcRGNZWvBKc1G6vecowcIJW96TlaLaUuW6v8AkvEH0gPCDhfiP/VTO+NsuwmdqvRw9fDxo4/E4fBVay5orH5hhsJWy7BKCt7d18VD6s5Q+s+x5kfvPRWPoHiHQ/FOkWHiDw1q+na9oeq20d5pmr6ReW+oabqFrKCY7iyvbSSW2uYXAO2WGV0ODzwaK71qk1qnqmtU/R9T9gpVadanTrUakKtKrCNSlVpyU6dSnOKlCpTnFuM4Ti1KMotqUWmm0z8kv+C0nhHx9r/7N3hzxD4Z+Ivhrwj4V8FfEHQ7/wAZ+F/EM8ulN44fxBNb+FPDq6friXMUcEnhm91q71a70+5+wwmzll8Ux6/o+o+DdOW//mbtRoFl4S0XVfByzWHi43l1FF4nvLGxit47weIPhxa6LocxvVtdA1G+Gj6vH4nuNG8PW3irVPCsyy3V3pqw/b9X0f8Auc+KvwY+FPxs0rTdC+LXgTw54+0XRNb0/wAS6ZpviXTotStLLWdJcT2l5FDLwc7TBeW5zbajZST6ffw3NjcT28n5I/tIeNP2db3wn8HdH+Bn7L/x606fwp+0j8DfGGv2fhb/AIJu/tc+DrfTfAHhr4l6d4t8c3KPJ+zbodvdWHlWst/eaLYvdXetXD7rfTb65JA+ezLKqmJr1cXCrGH7mCSUJ1ardPV+z5pctF6JKUE3f3vdaP4j8fvo353x9xfnXiDgMzw9HDU+HcDhIZZSoZrm+d4zF4JxT/s6jFww+V80Y0qVOOD9upv6xiHSjicTUlL+fPXdHXw5438B6/41+IF34X03SL/SZIfih8GfEHgz4l3/AIa8cf2pqep2mrr4Is/Ffw60/Rr/AFa/0NdfvLiLxKoskt7XWrTUdcS6tbOv7H/2gtas9K/ZvvJNX1PxX4qe70bwgh1vwl8NPH3xO1/X5o73Rrt9bj8EfA7wV448QzjVFt3upn8P+F59HspbmPdJZ2G2ZPKPB+rfsQ2/gv4p/Eez+CmsfD/wV8KNDufiH8Q9T+Kv7IPxx+EFtY6ZY+HvGH9reKNE0f4vfB/wldeMLuz8L/8ACVQeKbzwPpuvapZaTfwWXiPy7bWNGhuZ/FX7S/7FHiv4W/CfVNW8QXtt8M/iY3wE0X4IeIrT4UfFnRNI125/aTa60n4C6d8P7+LwHbWkjeLm0qO2h0q0VrTw/DJoKeMLDSbPWtCS98TibhapmXCXF+R08Hh86/1iybG5XPLcVmeIyKnjaOOwdXBYrDVc3wuCzKpgXWw9apCliqOW1alKbXNdWqQ/UPo+eB+aeDNTiZ47O8Bmq4hqZbVg8JTzL2uFqYBYmH+0PE4mngsX7SFeLp1aWW4PE0lGdKrXxNN01S/EX4uf8FNPDX7NvgjxBd65/wAEx/2x/jh4bm8VeH1utf8A2l/2dL/9n74MaVLptprlpDrugeLPF2gfFfVJtTvE1m4tdNtde8C+Dr+a0kuVla0d5LVvk4/8F2/hj481KfUD+w5rXhxZYdPtLSw0D9qDTLXStKsdO06202wsdM07/hmcW1lZWVlZW8FvaqrRQogSNY4lSMfrP+2D8O/g/wDCu11jxX/w0xY/Cuy0P4u+GPgzq/iT4meEfib4W8IeBvHXjDRPC+uaBbeLvi74Z8Kat4c8KaTf2HjTwrcQ+NvENvoHgGF9etLS98V2t9utq/Mn4wfsXfEfxL8TPAPg/wASfEz9kv4j/EH40+K/jOPAuhfFfwf+0nqnjH4pa7+zQ+oaJ8YdFj+J3gT4Bad8aY9G+H961w+paRo/xI8P6NrtzDBe6ZHrsJDSfD+G3DPhziOEuHvCXxZ+jFiaXBPDOLrYnJsCuMavGuV4HGYqriquJzDD1K+IwdWOIxFTH42pKrOvCqo4mUYUaUFGC/WOI6vEmDxOIxuRUqssVXjCnVxeFy3BVKtanT5fZU6tSa9s6cLK0JSnGOtkr2PpT4e/t9/si/FG5jhtfGXir4QXEtrbO1j8ZvDkp0438sdusthpXi/4c/8ACZWF5bQztMX1rxXo3w+tFgETvCjvKsX1n40+C/jP49/BDxBp/wADr3wB8XjN458JXMV98P8A4q/DPxDpLR6FZ+KItWRtWtfFn9nw3Ni+r6aJbO4uIbvF2jJAwWTZ+Z83/BPz9ifwd8I49Y+Nn7V13+z/APFbQ/hD8M/jr8T/AOxfAvxY+IvwV8H/AAy+M/jHUPB3wt8aW/8AwnXwo+FHj7w3oHiDVrSbRdR0zx1rba94S1yy1e28U3FhBpc80fu9z/wQ18C6d8UvDnwYuf26NIi+KfirwL4s+J3h/wAHJ8BrptWv/AXgbX/BvhjxN4m3Q/GCS0tLLT9d8f8AhTTokvLq3udUm1K5OkW9/Do+uS6b+ncJ/Q8+h5wP4m8MeMXhvxJxf4f8S8MZjUzbB8OY/D5lxBwy8TVwdfBzpSwc8NPHwo+zxM+WFDPeSE1BRtCPIfL5njPEvOsjzHh/PeGcNmuWZxgp4OeLy/McHlWYQo14q7f1l16MK0f72CUY7OMtD69+HHwG+IPwM+BENp8a38EfCdLL4m+M9du9R8d/E74c6JoltpWv+G/hfpGjXUutyeKZNKQ3+paFqtpFbm7+1CW2XzIUW5tWm/T39nR49c/Z7Nv8PvG3h3ULqeLxdZeHvH2gRt4t8GDVbuS8ksNc0a+ik07SPHOj6TeXcAubjw/q8+jXl9Y6losWsx3tlei1/Fr9lD9jX/glF4d+JPg3Q/EHxe8Q/tG/FrxB8QPi98MPCPhbx74O8a+FfBGt/Fr9m5YtW+MfhjSPBa+E7K31/wAQeB7SxvG1bwl4q8U+LdH1jSVvvseka0LSWeD9lvh9+2j+yV43/Z8+HX7Q3gf4reGj+z98RfGPhn4T/DTxlPoviXwrofiHxX4k+IsPwZ8OeF9C0jxDoGjavifx+3/CNCT+yIdK02Gz1HVL+6s9B0jUtStMs/8AB7wzw3jfxX46ZJnPEuecc8YYSGX5xiMRQw+TcL0cJTwWT4JU8pyaaxma+0nTyXCTlicbmUeWbrqFCUakXS+v4Py3Ncp4ZwXDuJwVHLsty7BSweEpPMZ4/NJxnKpJzxOPwtDA4ai06klB4WlOaaupxlFOX8efizwpb+G/ib4/8N/Frxnp3xd8XeGtR8d2fiHVvBd2PEdl4u1jw/Hr6Jqdrrs1qdWv/E15q0HirxF4g1PxDpfhyS1g0238QXWoa7qnjSaR3nQ/Fuu+FvFS3fiPwn4n05dU1/R/DuhxePfDnh3xrfx6V4w0CHxRZ6Zc2sXiEeJhr0Wo3MkT3Otalp97Bbarr+kapqEVpqmm6h/WZ4q+GX7E37L1xYsf2YpmvPGFz4l1GK++FH7H3xb/AGgLqAy6xp2s6pZaxqnwh+EvxLuPB+mPqs+n3GgaFrd3ommFLAxeFbD7LoE0enfJP7PPj39nmHw9rVx8Zv2cP2lF8bWvx9+N3jPwbqmpf8E9v2zr/XNN8JeJfjt4t+JPgZ7TX9B/Z8vmsNOubXW0vbzRZNVjQWuoapo2uWTWd9qthNcMgnzqEsRywkq8n7OEnyuTp2VSpVk/auUaknBSjFJwbtJ3Z/AFb6FvEizWngZcSYCnDOln+YSrYLB5xiMHlihiMuVLCZnm+J9tXzCtWo42o8IsTRoOvUy+pUi41alWtTX/AIIfz6G/7O3j2Dwx8SvG3inRbT4hJEfh54y0HwzpC/C3WrnQdP1PXLPw5f6R4g8Q6v4j0PXbm/i2a5q6+ErTUrzRrrUbD4f+FtSvfEDaoV+vnhL4f+BfANq1j4G8HeGPB1i9tYWbWnhfQdK0G2e10yOSHToHh0u1to3js4ZXitUYMsEbMkQRWbJXv4Oh9VwtDDXjL2NNQ5oxai7dUpSm16OT+Sdj+8vDbhLF8DcDcNcJYrGYfG18hy+OAnisFTxVPC1owrVJ03RpZhjMdiaMIUpQpqlLEzp01DkoRpYdU6MOvl5JHH47gDhc4O3BIwOmQD34zX5FeEPgt8LPiMlldeAPip+xR46s9Tj+JM9jqnhD9l/xR4m0u6/4VF4r0jwR8Sok1jRP2n7zTTe+EvFmu6VoeqWJulvTe3bG2t54re5lg/Xd/vH8P5CuS1bwR4W1zWdF8Q6lpEE2t+HoNVs9J1OGS5sry20/Xb7RdT1zSnmsp7drvR9a1Lw34ev9Y0e9+0aXql3omlT39pcSWNuY+k++UnF3XofmdougfAjVf2a/2hfCz/GH4PWngj4+fCbwn4F1Dxd8GfgN418FXug6R8e9X8ffAfwxPq+iah8QviFf614muvGl9rfhbTvD08Ogar4I8T6Vqtp4z0sCdobLx+D9m39jPwj+zH8Gf2YPDnxsj8I+K/2Y/jf+xd8R7LxJB4C8QnxF44+O3wI+Gvw/134C3vjL4UsI9U8TT/GH4afBjQtGfRfAF7oM3xG8WadqXh/wVexfEia70J/1jj+BXwiht9Rtrb4feF7KPVrf4bW2ovpumR6Xc3a/B2/t9T+FM899pxtr57/4eX1nYz+EtS+0jUNFfT9P+xXMX2G18pmqfAb4Qa5qVjret/D7w1revaZql7rmneI9asBq3iew1nUfDmheEb7VrPxNqD3GvW2pXXh3wv4a02a+h1FLp18OaBcGX7XommXFqD5n1bet+m/fZnzX4k0j4F654T+Ntt8Y9U0HxN4E8dLf+Nv2lfBXxA+DnjrStL1fw34p8MQ/Afw/HZ+EvEksmsaHos+l/A+ZJTdW3i2412/0yfxRpEmiaPrmgOn556N+wt+yLot9+yH4ItPj34k8T237D/hD9qzVfhr8P/if8O/HPjDxP4+8D/tn/E2/8DSSajItx4Z8beNm+FviK68NeAdD1X4ezx+IdE8Rp4c1LxNdaXd+JvDkTftdqPwc+G+sJfJq/hi21Yaro48P61/at5qmpf8ACQaNHPrd1a2Hib7bfT/8JNHp134l8Q3mkvr39oS6Rea1qN3pklpc3Lymxc/CT4dX97Zalq3hax17VNN1Xw3rmnav4llvvE2s6dq/g661m88Lajp+r+ILvU9RsLvQZ/EXiA6bLaXELW8euavbr/o2o3kM4JNrq7f136n4r/tJfsw/Af8AaK1/4o+O/iB+1143+HvxV8Sfs1fs1fDVfi9p/wACtb8A6l8Nn+D/AMavHPxk8IfGXQdW8TaNFoWjQePPG3/CceFPEWmLNa6Df+F9Pu/B0d/Fqttf6lefUvxe/Z18FfGP4nfAH9qe7+J3inwX8dP2cPjmmveDvFNj8A/iLYeKNY8FeNPhovgrxV+z/a/DPxPJceL9U+FvjrQrvUvFXinVdGsr+bQNWsvFviLRta8KjQvGkkP33rHwX+E/iCG4tdf+HXg7XLS80i38P3VprGg2GqW1zoVtaeM9Pj0e4t76KeGbTH0r4iePtGurF0NtfaJ4z8T6NfR3Ol63qNpcbCfDzwiunXmljS3aK/1S21u9vpdT1ibXrjWrK2tLSx1d/EsuoP4h/tSws7GxsbDUBqf2ux06zttOtJobKGKBAbe3+St07effyPxe+Hf7F/wf+Anxl0L9ozwv+0D8UrDxR8M/jX+2P+0X8RtJtv2dPiVP4e8Y237W/g7/AIWV4q+FnxL0Lw/py+IbjVPhrZwaf4w+EHhm91FvHWm6jqllosvhzXNX8Y6XZ3uHoP7An7NXhH/gnn8Ff+Cd158ZNV+OfwY+KekfEn4O/AHxT4c8M6XfatrfxH+KWneOf20/DHxk0PxBbeNbf4aX2qfDvwf4Q8S+NPhZ4tjFpo5treOwh1bVrjxDJpeq/tSnwf8Ahql3d3y+ENJF1qMzXuqOVuWj1bV5PCsfgSXxHq1u1ybfVPFs3geCDwbN4v1CK58US+FIIfDsmrvo0SWSs1D4OfDTUv7HM3g/R7WTw9461r4naDc6PHcaBfaJ8QvEuieJ/DniLxno2o6Jc2F/pfiLX9C8a+MNK1zVLG5gutWsfFPiG31B7hNXvxOD5292391na1r/AInwL401L4f/ABG+HvgjT/FHxn+HPxC8Q/AzSfEXgv4vfEb4rfsveM/HV1H4p8H6B4DuvHniPxfZ/D/xn4C8IfCi6sItV0vWPiHLKw8H2up3WoJpbeHrDwlq+naf5/qHws+Efh/7Xd+Jvib+x94NsNP+IPh/4XJrXjf9kL4i+BtA1X4k+JvF+qeBNB8EeGPEvi79o7RtD8WeJ9Q8WaPe6XFpnhbUNZvIWNhf3EcWm6ppV5efpZJ8B/g9JbalZn4d+GI7TW4r+11+1t7D7Lb+ItP1fRPDvhvW9H8SQ28kcXiLQ9e0Pwj4W0vxBoWtpf6Pr1p4f0qLWLG9+xwlez0nwZ4a0TUtV1jT9LjXVNah0y11HULme71C7msdEutTvtE0yOfULi6ez0jRb3WtZvNI0exNtpel3OrajPY2cEt5cPICb0SV/wClr+v9bdQPp/P/AOtRSMSAT/P60UEn/9k='
											 width='99.96'
											 height='58.8' 
										/>
									</td>
									<td style='width:33%'>
										<div style='text-align: center;'>FUNERARIA UN ENCUENTRO CON DIOS</div>
										<div style='text-align: center;'>'PRESENTE EN LOS MOMENTOS DIFÍCILES 24/7'</div>
										<div style='text-align: center;'>SEMÁFOROS DE VILLA FLOR SUR 1 1/2 C. ARRIBA MI</div>
										<div style='text-align: center;'>RUC 0011910051026M</div>
									</td>
									<td style='width:33%'>&nbsp;&nbsp;
									</td>
								</tr>
								<tr>
									<td>&nbsp;&nbsp;</td>
								</tr>
								
								<tr>
									<td colspan='3'>
										<table style='width:100%'>
											<tbody>
												<tr>
													<td style='width: 33%;'>
														<table style='width: 100%;'>
															<tbody>
																<tr>
																	<td style='width: 20%;'>DÍA</td>
																	<td style='width: 20%;'>MES</td>
																	<td style='width: 20%;'>AÑO</td>
																	<td></td>
																</tr>
																<tr>
																	<td> ".helper_DateToSpanish($objTransactionMastser->transactionOn,"d") ."</td>
																	<td> ".helper_DateToSpanish($objTransactionMastser->transactionOn,"M") ."</td>
																	<td> ".helper_DateToSpanish($objTransactionMastser->transactionOn,"Y") ."</td>
																	<td></td>
																</tr>
															</tbody>
														</table>
													</td>
													<td style='width: 33%;text-align: center;vertical-align:top'>
														RECIBO OFICIAL DE CAJA                
													</td>
													<td style='width: 33%;'>
														<table style='width: 100%;'>
															<tbody>
																<tr>
																	<td>Nº ".$objTransactionMastser->transactionNumber."</td>
																</tr>
																<tr>
																	<td>
																		<table style='width: 100%;'>
																			<tbody>
																				<tr>
																					<td style='text-align: right;'>POR</td>
																					<td style='text-align: right;'>". $objCurrency->simbol ." ".  number_format(round($objTransactionMastser->amount,2),2,",",".") ."</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td>&nbsp;&nbsp;</td>
								</tr>
								<!--objEntidadNatural-->
								<tr>
									<td colspan='3'>Recibimos de: _______________________________________________________________________________________</br></br>
									</td>
								</tr>
								<tr>
									<td colspan='3'>
										La cantidad de: _____________________________________________________________________________________</br></br>
										_____________________________________________________________________________________________________</br></br>
									</td>
								</tr>
								<tr>
									<td colspan='3'>
										En concepto de:______________________________________________________________________________________<br></br>
										_____________________________________________________________________________________________________
									</td>
								</tr>
								<tr>
									<td>&nbsp;&nbsp;</td>
								</tr>
								<tr>
									<td colspan='3'>
										<table style='width: 100%;'>
											<tbody>
												<tr>
													<td style='width:15%'>Forma de Pago:</td>
													<td style='width:15%'>[ ] Efectivo</td>
													<td style='width:30%'>[ ] Ck. Nº _________________</td>
													<td style='text-align:right'>
														Banco: _________________
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td colspan='3'>adftasf</td>
								</tr>
								<tr>
									<td>&nbsp;&nbsp;</td>
								</tr>
								<tr>
									<td colspan='3'>
										<table style='width: 100%;'>
											<tbody>
												<tr>
													<td style='width: 33%;text-align: center;'>
																_______________________________________</br>
																Recibí Conforme
													</td>
													<td style='width: 33%;'>
													</td>
													<td style='width: 33%;text-align: center;'>
																_______________________________________</br>
																Entregué Conforme
													</td>
												</tr>
											</tbody>
										</table>						
									</td>
								</tr>
							</tbody>
						</table>
					
					
					
                    </body>
                                
                    </html>
            ";
    
    
	
    
    
    return $html;
}


function helper_reporte80mmTransactionMasterGlamCuts(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo, 
    $confiDetalle, /**/
    $arrayDetalle, /**/
    $objParameterTelefono, /*telefono*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 2.7in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='220'  >
                          </td>
                        </tr>
                        
						<!--
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->name)."
                          </td>
                        </tr>
						-->
						";
    

          if($userNickName != "")
          {
            $html	= $html."<tr>                              
    						  <td colspan='3' style='text-align:center'>
                                RUC : ".  $rucCompany ."
                              </td>
                            </tr>";
          }
    
          $html = $html."<tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";
						
          if($userNickName != "")
		  {      
			$html	= $html."<tr>
                          <td colspan=''>
                            Vendedor:
                          </td>
						  <td colspan='2'>
                            ". (strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )   ."
                          </td>
                        </tr>";
		  }
						
          $html	= $html."<tr>
                          <td colspan=''>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>";
			
			
		  if($causalName != ""){
			$html	= $html."<tr>
							<td colspan='1'>
								Tipo:
							</td>
							<td colspan='2'>
								".$causalName."
							</td>
						</tr>";
		  }
			
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            Estado
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
                          </td>
                        </tr>
                            
						<tr>
                          <td colspan='1'>
                            Moneda:
                          </td>
						  <td colspan='2'>
                            ".$objCurrency->simbol."
                          </td>
                        </tr>";
			
		
						
          $html	= $html."
						<tr>
						  <td colspan='1'>
							Cliente:
                          <td colspan='2'>
                          </td>
                        </tr>
						
						<tr>
						  <td colspan='3'>							
                            ". ( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName." ".$objEntidadNatural->lastName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>

						
						<!--
                        <tr>
                          <td colspan='3'>
                            Tipo de Cambio: ".$tipoCambio."
                          </td>
                        </tr>
						-->
						
						<tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                                
                     
                                
                         [[DETALLE]]
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                             
                        <tr>
                          <td colspan='2'>
                            TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount)."
                          </td>
                        </tr>
   
                        <tr>
                          <td colspan='2'>
                            RECIBIDO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount + $objTransactionMasterInfo->changeAmount)."
                          </td>
                        </tr>
                         <tr>
                          <td colspan='2'>
                            CAMBIO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f", ($objTransactionMasterInfo->changeAmount)  )."
                          </td>
                        </tr>

                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objCompany->address."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objParameterTelefono->value."
                          </td>
                        </tr>


                        <tr>
                          <td colspan='3' style='text-align:center' >
                            sistema 505-8712-5827
                          </td>
                        </tr>
                                
                        

                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
	$rowin  = 0;
    foreach($arrayDetalle as $row){
		
            $colun  		= 0;
			$colunCantidad 	= count($row);
			$nuevaFila		= 0;
            foreach ($row as $col => $key){				
			
			    //encabezado
				if($rowin == 0){
					
					//Mostrar los productos en una sola fila
					if($colun == 0){
						$cuerpo 		= $cuerpo."<tr >";							
					}	
						
					$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style"]." colspan='".$confiDetalle[$colun]["colspan"]."' >";
					$cuerpo = $cuerpo." ".$key;
					$cuerpo = $cuerpo."</td>";
						
					if($colun == $colunCantidad ){
						$cuerpo 	   = $cuerpo."</tr >";
					}
					
				}
				//datos
				else{
					
					if( $confiDetalle[$colun]["nueva_fila_row_data"] ==  1 &&  strpos($key, "-comand-new-row") !== false  )
					{
						$cuerpo 		= $cuerpo."<tr >";							
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='". $colunCantidad ."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ". str_replace("-comand-new-row", "", $key );
						$cuerpo = $cuerpo."</td>";
						
						$cuerpo 		= $cuerpo."</tr >";	
						break;
											
					}	
					else
					{	
						
						if($rowin > 0 && $colun == 0)
						$cuerpo 	= $cuerpo."<tr >";								
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";								
						
						if($colun == $colunCantidad)
						$cuerpo 	= $cuerpo."</tr >";
							
					}
						
				
				}
				
                
				
                $colun++;
            }
			
			$rowin++;
            
    }
    
	
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
    return $html;
}

function helper_reporte80mmTransactionMasterAbonoKhadash(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo, 
    $confiDetalle, /**/
    $arrayDetalle, /**/
    $objParameterTelefono, /*telefono*/
	$objUser,
	$objCustomerCreditDocument, /*documento de credito*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/ 	
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 2.7in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='110'  >
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->name)."
                          </td>
                        </tr>";
    

      
		$html	= $html."<tr>                              
						  <td colspan='3' style='text-align:center'>
							RUC: 3210302740000A
						  </td>
						</tr>";
          
    
          $html = $html."<tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";
						
          if($userNickName != "")
		  {      
			$html	= $html."<tr>
                          <td colspan=''>
                            Vendedor:
                          </td>
						  <td colspan='2'>
                            ". (strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )   ."
                          </td>
                        </tr>";
		  }
						
          $html	= $html."<tr>
                          <td colspan=''>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>";
			
			
		  if($causalName != ""){
			$html	= $html."<tr>
							<td colspan='1'>
								Tipo:
							</td>
							<td colspan='2'>
								".$causalName."
							</td>
						</tr>";
		  }
			
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            Estado
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
                          </td>
                        </tr>
                            
						<tr>
                          <td colspan='1'>
                            Moneda:
                          </td>
						  <td colspan='2'>
                            ".$objCurrency->simbol."
                          </td>
                        </tr>
						
						<tr>
                          <td colspan='1'>
                            Usuario:
                          </td>
						  <td colspan='2'>
                            ".$objUser->nickname."
                          </td>
                        </tr>
						
						
						";
			
		
						
          $html	= $html."
						<tr>
						  <td colspan='1'>
							Cliente:
                          <td colspan='2'>
                          </td>
                        </tr>
						
						<tr>
						  <td colspan='3'>							
                            ". ( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName." ".$objEntidadNatural->lastName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>

						
						<!--
                        <tr>
                          <td colspan='3'>
                            Tipo de Cambio: ".$tipoCambio."
                          </td>
                        </tr>
						-->
						
						<tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                                
                     
                                
                         [[DETALLE]]
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                             
                        
						
						<tr>
                          <td colspan='2'>
                            DESEMBOLSO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objCustomerCreditDocument->amount)."
                          </td>
                        </tr>
   
                        <tr>
                          <td colspan='2'>
                            FECHA INICIAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCustomerCreditDocument->dateOn."
                          </td>
                        </tr>
                         <tr>
                          <td colspan='2'>
                            FECHA FINAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCustomerCreditDocument->dateFinish."
                          </td>
                        </tr>
						

                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
						
						
						<tr>
                          <td colspan='3' style='text-align:center'>
                            _____________________
                          </td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:center'>
                            Cobrador
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objCompany->address."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objParameterTelefono->value."
                          </td>
                        </tr>


                        <tr>
                          <td colspan='3' style='text-align:center' >
                            sistema 505-8712-5827
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;...
                          </td>
                        </tr>
						
						<tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;....
                          </td>
                        </tr>
						
						<tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;....
                          </td>
                        </tr>

                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
	$rowin  = 0;
    foreach($arrayDetalle as $row){
		
            $colun  		= 0;
			$colunCantidad 	= count($row);
			$nuevaFila		= 0;
            foreach ($row as $col => $key){				
			
			    //encabezado
				if($rowin == 0){
					
					//Mostrar los productos en una sola fila
					if($colun == 0){
						$cuerpo 		= $cuerpo."<tr >";							
					}	
						
					$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style"]." colspan='".$confiDetalle[$colun]["colspan"]."' >";
					$cuerpo = $cuerpo." ".$key;
					$cuerpo = $cuerpo."</td>";
						
					if($colun == $colunCantidad ){
						$cuerpo 	   = $cuerpo."</tr >";
					}
					
				}
				//datos
				else{
					
					if( $confiDetalle[$colun]["nueva_fila_row_data"] ==  1 ){
						$cuerpo 		= $cuerpo."<tr >";							
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";
						
						$cuerpo 		= $cuerpo."</tr >";	
						$nuevaFila		= 1;						
					}	
					else{
							
						
						if($nuevaFila == 1)
							$cuerpo 	= $cuerpo."<tr >";								
						
						if($rowin > 0 && $colun == 0)
							$cuerpo 	= $cuerpo."<tr >";								
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";								
						
						if($colun == $colunCantidad)
							$cuerpo 	= $cuerpo."</tr >";								
						
						$nuevaFila = 0;
							
					}
						
				
				}
				
                
				
                $colun++;
            }
			
			$rowin++;
            
    }
    
	
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
    return $html;
}


function helper_reporte80mmTransactionMasterMarysCosmetic(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo,     
    $arrayDetalle, /**/
    $objParameterTelefono, /*telefono*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 2.7in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:5px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='110'  >
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->name)."
                          </td>
                        </tr>";
    

          if($userNickName != "")
          {
            $html	= $html."<tr>                              
    						  <td colspan='3' style='text-align:center'>
                                ".  $rucCompany ."
                              </td>
                            </tr>";
          }
    
          $html = $html."<tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";
						
          if($userNickName != "")
		  {      
			$html	= $html."<tr>
                          <td colspan=''>
                            Vendedor:
                          </td>
						  <td colspan='2'>
                            ". (strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )   ."
                          </td>
                        </tr>";
		  }
						
          $html	= $html."<tr>
                          <td colspan=''>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>";
			
			
		  if($causalName != ""){
			$html	= $html."<tr>
							<td colspan='1'>
								Tipo:
							</td>
							<td colspan='2'>
								".$causalName."
							</td>
						</tr>";
		  }
			
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            Estado
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
                          </td>
                        </tr>
                            
						<tr>
                          <td colspan='1'>
                            Moneda:
                          </td>
						  <td colspan='2'>
                            ".$objCurrency->simbol."
                          </td>
                        </tr>";
			
		
						
          $html	= $html."
						<tr>
						  <td colspan='1'>
							Cliente:
                          <td colspan='2'>
                          </td>
                        </tr>
						
						<tr>
						  <td colspan='3'>							
                            ". ( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName." ".$objEntidadNatural->lastName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>

						
						<!--
                        <tr>
                          <td colspan='3'>
                            Tipo de Cambio: ".$tipoCambio."
                          </td>
                        </tr>
						-->
						
						<tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                                
                     
                                
                         [[DETALLE]]
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                             
                        <tr>
                          <td colspan='2'>
                            TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount)."
                          </td>
                        </tr>
   
                        <tr>
                          <td colspan='2'>
                            RECIBIDO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount + $objTransactionMasterInfo->changeAmount)."
                          </td>
                        </tr>
                         <tr>
                          <td colspan='2'>
                            CAMBIO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f", ($objTransactionMasterInfo->changeAmount)  )."
                          </td>
                        </tr>

                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objCompany->address."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objParameterTelefono->value."
                          </td>
                        </tr>

                                
                        

                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "<tr style='width:100%' ><td colspan='3' style='width:100%' ><table style='width:100%' >";
    $colun  = 0;
	$rowin  = 0;
	
    foreach($arrayDetalle as $row)
	{           
		$rowin++;

		if($rowin == 1)
		{
			$cuerpo = $cuerpo."					  
					   <tr style='width:100%'>							
							<td style='width:33%;text-align:left'>Cantidad**</td>
							<td style='width:33%;text-align:right'>Precio**</td>
							<td style='width:33%;text-align:right'>Total</td>
					   </tr>
					   ";
		}
		
		
		$cuerpo = $cuerpo."
				   <tr style='width:100%'>
						<td colspan='3'>". $row->itemName . " ". strtolower($row->skuFormatoDescription) ."<td>
				   </tr>
				   <tr style='width:100%'>						
						<td style='width:33%;text-align:right'>". sprintf("%01.2f",round($row->quantity,2)) ."**</td>
						<td style='width:33%;text-align:right'>". sprintf("%01.2f",round($row->unitaryAmount,2)) ."**</td>
						<td style='width:33%;text-align:right'>". sprintf("%01.2f",round($row->amount,2)) ."</td>
				   </tr>
				   ";
            
    }
	$cuerpo = $cuerpo."</table></td></tr>";
    
	
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
	//$html = $html."<div style='page-break-before:always;' ></div>" .$html;
	$html = $html.$html;
    return $html;
}


function helper_reporte80mmTransactionMasterAxceso(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo, 
    $confiDetalle, /**/
    $arrayDetalle, /**/
    $objParameterTelefono, /*telefono*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 2.7in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='220'  >
                          </td>
                        </tr>
                       
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            Multímetro las américa, contiguo al GRAN MALL primer piso 
							</br>
							<img style='width: 0.2cm;height: 0.2cm;' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAD0AAAA9CAYAAAAeYmHpAAAABHNCSVQICAgIfAhkiAAAAAFzUkdCAK7OHOkAAAAEZ0FNQQAAsY8L/GEFAAAACXBIWXMAAB4HAAAeBwGZeNkyAAAAGXRFWHRTb2Z0d2FyZQB3d3cuaW5rc2NhcGUub3Jnm+48GgAAEXFJREFUaEPNWwmUFNW5/rt7enr2fWEZmEGQHUFWFRCMS0iOj7jlPc0T9IhGYwgnJ8uJhkTQZ0xMgrsmRmNejOaYiHnRmARRjhoNuyyyZBCGRZiN2ffu6WXe9/1V1VO9DQPMYL46f9etW3f77v3vf/9bVe2QQUJPT48bMgbBCZDxpoyGlECKHA5HCu4HEG6AVEGOQMpNOQD5l9PpbMN5wDGgpEEiDXI5gldDLoWMBjk3750uUE4l8m7E+e+4XI8OqDTu/JsgFAqVQn4A2YNGDjhQbg3kOcglZpWfHdCIYZAHIdVm+2KAeyr9gZU2UXrEByCvQmabTTi3QMXLIBVmexS4PmXDexAdCAV6/EF/TyAY6DNdovIQ7oL8DMECszmnhdOe06iMxmgN5tuXjBidf2YIBTp6iwz0BOWEr1KOdh2XKl+11PhOSkuwVbwhn4Rwz4Ej2emRTFeGFCUXSEnKUBnhKZGy1JGSgngL9vIJqw7E78Xpm5jvGzSinzgt0iC8CKdnUOkoXiciW95xULa17pDd7ftAtkbagx3SIyFU5sSBX4fTTGmUEdKjRxwoLsWVIsXJhTIhfZzMzpou0zInS5IjKZzWgo04V4D7QPzHGtEP9Js0CH8dp0dQWXJE5SzCLGVT8zZZ17BB9nUckM5gl3hguJOdySZJTdknerRresQf8osP2uBG/vNSS+WK/AWyMG+epDlTjXTxyf8KpxUg79OIPtAv0iB8Lwp/iOF4Fe5p3y+v1Pyf7G7bp9dpGC2nuDR8NmAneENe7YRRaaXy5aIvgfxc41584q/hdAuId2hEApySNAh/D4X+hOHoirowmi/XrJU3T64Xf49f0l3pRgNs6c4aWp5IV6gLNiIgl+TMlmXDb5ahnmKNZ8cYycLE/4Twf0O8GhEHfZIG4WXI/DzD0YSPeU/I40d/KXvbyyUzKUPnndWAwQLLbw20SbGnSJaPWCazs6cnIv5rjPbtehEHCUmD8Hyc1qEgellGJMCCd7XtlZ8eeUoa/Q2SlZQVrvRcgJaBGsYav1qyVBYXwbbGJ74SxHVKRiMuaRAuwumfKGBMNOGtLTvkx4cfg6HpxtxNY+nm3XMItMOP+rsw328vWSL/OcRYPa22sp0IBxH8Aoi/rZE2JCL9IjIuYdhe0J62/XLfoZ9INwxLqjMl3LufBTjicG+wSnTKN0rviDvi4FGB8xwQ56YmjN4F0wQSLo5HuNJbgxF+HKrlxVLkgXOBxQX37cK4ICTevYEW1uHCkYLO/8Wnv5EtzR/FDCHI0pFaZVz1IoI0CKfj9COGWTBBwt1QpTVHn4FHVYcRTtW1NIR7FKbidVugQzrQ61xiOjDnuE7znnF/sKTHMKCo5FEY1Wp4fGwvYbUfuBO8ZplhRUTf4ObXkekphu2kX6z8gzx//GXJc+fwjsYbcEgQVdOwcP2clzdHUl2p4oM2vFX/vmxF76dz3kfkGQw4pDnQIvNzL5YHzv+ettnefoTfwKiH3eYwadzIgHyERGPtGSo6jsiK/SvR7B5xOw130ALTtQTa5cah18jXSm81Yw00+Vvkrr3fwf1W8cArG2xgOwJta5OVo78lVxYuiCZNxZgP4hsZF1Zv3LiGhM1L9IbRH7+rehW+czvUyKUFW8JC20B4bu4suav0Fk1rR647Wy7LnyudgU5Na887GMLWUtVfrlqr04xkCdaNMHneqRGAfU4v5Q9UXBOylD2t++XDxi3qabFgxltC1zAD8XeOXBruoGhcWbBQ0pPS1cra8w6GsH00aoc6j8j6uve0fuueGV4MbiMYVtK4mIhIPt5RWCRer31LvEGfXiN7xEFjNSdnppSmajlxMTqtTHdJHRztc3QkY5Py15NvS3ewO7ybI3GoNg0SH2OFR3oRIj1WrzicDqn21mIZ2KE7G0ZHCooP9cj07As0fV+4qnCh5jE0JbqcgRePI0UOdhyWHa0fq7YSFi+cv8izRXohf6ybxKam7VLf3SAuzGXaAbtwLXa73DLEU2imTow5OTOwPRwJjfHGlDMYQnDq/aNhs4btUw9zew445jjxQ98anrsBK9HW5p3oEScUBp0RLegcbh05h06FNCxhV8CadmF/HLesARequAfb3P3SFegKGzQC4UK/3z+JpOm1DDWicQOq3eJv1aWKm3gSjBEc7E06IP1Bk79Zn4rELWsQhEtrNTzIQ51HOYoKxhNJSUnjnMFgsAzz2W7F5UjXp1LX3aiqbS0JdmGXUl0rvdVmjsSgv/56zTrDk4tT1mCI9DjUeFZ0HjNb0UsamOB0uVx842AsVWQDVHbVYH2FarCbtJBoQSIU/FHzbk2fCPTX7y9fI96ATzswflmDIyT/aedxoyGIsgDyYznCw82L8M1a70lkglFAXKKDTyu3Ne/SJ5zxQNf0vvKH5Sgq5rxWQ3MODw5YNXgQ1HDbSJeRNNcvhWXEGjEHmaQvScK8qfXWyxvV63EVD9zsezHCTt0Y0K7GK2ewhFxomzh4hkNmAOSH0ZBlmNdhdAaMx0vsnb4k1ZUir1W9qWt6NHhvxXm3I52xhDAQr4zBEtLm8/VgiM8SDHAKA7lO0y+NQBAHM7KX+hJ6P7XYbj59+DdmzkhclDtDbh5xgzR1t5jlnbrMgRJOp6A+POkF2wC+LhKOeU5MMkZGHaCEQhJZSZnyZs3b8pcEan5n2VKZnz8Hjk6TZrLnH0xh21ySFLFOm4tUJ39j3gFzg6Fq0o+DSHYly6MVv4LROqHXdnBbef/478rEzPPVVhD2/PEOdjjtAR/59id9vINlcG+vqwauCbMDakm6jiGCiYkiT772lBLvh6TAA6rHuv4AlyfMo2gUegrkkckPgPg4qfc1mPk4IrFlUSW5KxuZOhwOjUMau5tNmxA/fSJhOfnJOUqUXCzg+jhJm4tZL4rhU6tjYnbDqYTpqOabGz+SNZ/8AjGxGJ46RJ6a+pBcWbQAxBvFh90bEVEWfhox/28quVZemvmUPDPtYbl+2NW6T27A9OADSSIiTwIJwmiNSBmGEFWdU5WxigrX6tWr8xDQxx68we0Yz3+v3YAeDuK6d06cCh5z7eYaPj1nihnbC67Xi4o/p5Z9N/bqTRhFEuKyRpDYwoK5ct/4b4kHU6Y4pVAWFl4il+ZfpEvk0Q54ir4mfaDBPGEaUeBQUVv+q2SxjE4vs6y2Nadfca1atSoAknchws2bfKuYgY3/O3UfqGV2o1H9BbuHJD5o2CJ5UK3JWfzMJBbskPkFF6nvTuelGaPbGmiH+o+VRy+4XzLdkatoXnKuGsPL0CF8IVjRcVSfu3NDFA8knJWUIV8tWyLZ0ECONAmbc/pJzvL2BQsW3ICIIYYrKuJOcsvB9sNwMz/WV6fsuf4e1BQ6Bu/VbZQcd5ZMyeZ3NrHIB5Erii6VuQWzsEFwS5Y7Ux6cdI+UpIb3PjHISc6Wufmz5MKcyfLWyfd1rpOIvX6OMx8XTcmagOXyeuq2xsPd5oh3I/1qpY6Lp3FxdyBgWEvsROQfdZvl7t33SJoz7bRUnNWSNOefFyP5jTHL5GvnxT5DOxtwM3Hd5mVy0lcf96Ejjd89Y5fLbaNuEjsn8NyFEZ9h6YcusqbO61ORWblTMR9GqQ/NOd5fwY+qE6cF5+4jnzwr9+59SJrhEg4UuJFp9reoekfXz87OdefI54rmGSNgAwZ2AySkLBF4D73wqUWaap6alCpXF1+huy0UF1N4X4IfJe4C8Wyo+B+P/0Vu3fZNWPcdWv7Zorz1oDT5WmNJ42j1t8sC2Iuy9BESChoemana7ILXeW2RboGsNcOamTuEa4YtkhFpw9VRYI7TEYINoarTqJW3HZI7tn9HVu3/uRyL48T0F2zbnyr/1vvQzyZcbVJdHvnKiOt4S5dSgpyA7ThvYsDIaeB/0Rs+a7TpqBem5MMYXCdt/g4UwAMjeIaSkZSma//vjq2VGzffLf/zr8dkd7Px5UJ/QcO1at/P5cOG7ZKO8uzl86CP/4Xiy2Va7iSMcsQyReIvQHSCR1gokP49btxkTX42sqvHK0u3rZC9LeW6zp4V0OOs0BfsVmPEpWlqzkS5qniBXJI/U0ZnlBnp4mBb42554uBz8k8Qzoal130SNEmBcvmoms/hX734WSlNK5FAMMKAHWltbZ2em5urfnAEaajjNMi7COYEzfnATOuq35Xlu1bqaPELoYEAK+YnV1yrA9CqAk+ejMscLTPzLlCHIhfLE40S9+wf1m/V1YRGlUtbNLijaoRhe3jy9+Wm0mslGMAuEQdHmQJOy3F+2kweSZpAr/Drg8/bTf1Lx16TH+77mS74uKfxAwoMGDcXHC06FtQwWn9OKH8ooPM3HYZV31AycRTq4M/fCO9rzdTVaoRpRAlzlHeizXNBOvwUM2LYkCAfvTLJvMRNqpDI++hlGiWC5wEXHCTKV0B0aKhR9Lz44p+qnIl4toVk7PnYtga4pbNzp8mqSd/Wa4swLTbShED4u3bCRLSuXoQEJVog4MR2m724r/WA+tNaz6AKyaBi7K6M2c83jtFpDGEaEh6PKfHkhT9StQ9AKwhqIwXlPQ4+MV8TRpPW91nWfGa9u5v3S1VnrTr5xph8lodpp8GagzExa6w8P3ONlKQNDXtehLkubwHxH5pREQiTRkF8XqakWaiFLY07zS2dsS+1hGkodpWz3x9oMerAshU0jNtlRXPlt7Mfk9L0EvH7/eE2mPOY34ovwSjH/YguTNrn852PxFOZkeAcotnf2rDTMCCMN4Ufs9KScnfU6GuGF9Sm77eM+zwPnOj7KZTL+lu629Tarxh7m7wwa40M8RQpYQtut76RaYam3gjCB83oGIRNMdTjNqjFr6kmrIAv6A60Vci1G5epU8De5vealtfDhwKTcsbJxXkzZCPWzjerNsAYOdXocD4R7L4zsfUR+XBBonw1PD13itw7YbksLLoYc5CvlnpVmoSBFrT/Kwj/TSMTIFw2eudF9M4Sq+dYyHOHfi9377hXn6QUpxZgHR0jc/IuVI9nSuZ4yUvJMUpAw96oXC/PVrwkO5r2wMbAA4OjwI3/mZAmdA2H309i52eNkltKb5Cby66XNCxdQX9AZzfBDqZKY6BqqNKQd/RGH9A2IUMGVJvbrtEkrbbT5ZAnPnkB86dOe3Zq9kQZklqkL/gUsHV0VbmWcrlxJjnFDy1ZV/uurD3xV9lav1MasMVjaj44TMaeGalMLbB3BVQXv7QN1CgfJIi1mhsV7sWvLVkk/zHsSsn1ZGud+rzMBA0W2sz270O5N0N2mbf6hEV6Hki/j0xOywqyKWwgHygoUCHnss7dBODcdyShSLD4pO2wbKzfLltgEw60Vkgt9r78RoVGUY0f0it91oF8/AqJvv4YuKKz8y+EWzoDpMcrsWiyhKnObPsrXV1d305PT+c/fvoFJQ2iK1H4g3ajQOjWzTyiYfUyYdkBC/SgXE401jSTHd2d+l6pCvtgPvij300tYfkp7hTsf7NkaEqxlMBOZCXDzWSrUBw3DdEP7G1kaaEfgPA779OCksZ8fgsEroombYedJAHNYMWHUGky4kcyLpo8QWIu5qNa92aPBbNBiUjS8qrssMiiXh/q/C3kYchhjTxNOFpaWvLT0tIOwhjkdnd3m9EGSVUtG9A5J0FwGyrehPOmurq6rYWFhdwB3AGytyJO/+ZAsAPYMWcKEIru6BbIn1HPM4jbakSdGRxo2OWo4B16YayAlVlAXDOudyK4GWd+eLYNaWLf1gHYuhVkZGQsRqO+DJmPBvOTyzBYPrXAEgusz5I49QdwzQ3DG5C1EP4776zhQMEPorKVvMDodCK8B0ElCWGPHsM5UmdPAa/XOx6awy/05oHgNESVoXMzEdfLKArUCkgX6jqBfB8jagvkA1zzK8bE8+4MQNJ/RiXlaBwfpXDZ6v1mYQCAcl0Qfj/Ol/+FIJYNLeD/LDlJg7j2ok6+T6uHVIFgNaR3ng04RP4fURa1+7RBRgYAAAAASUVORK5CYII=' />
							7652-2699
                          </td>
                        </tr>
						<!--
						<tr>
                          <td colspan='3' style='text-align:center'>
                            RUC: 3610802840006J
                          </td>
                        </tr>
						-->
						<tr>
                          <td colspan='3' style='text-align:center'>
                           <!-- +(505) 8909-5941 -->
                          </td>
                        </tr>
						
						
						";
    

          if($userNickName != "")
          {
            $html	= $html."<tr>                              
    						  <td colspan='3' style='text-align:center'>
                                <!--".  $rucCompany ."-->
                              </td>
                            </tr>";
          }
    
          $html = $html."  
						<tr>
                          <td colspan='3' style='text-align:center'>
                            Comprobante de pago
                          </td>
                        </tr>
						
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper(str_replace("FAC","",$objTransactionMastser->transactionNumber))."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";
						
          if($userNickName != "")
		  {      
			$html	= $html."<tr>
                          <td colspan=''>
                            Vendedor:
                          </td>
						  <td colspan='2'>
                            ". (strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )   ."
                          </td>
                        </tr>";
		  }
						
          $html	= $html."<tr>
                          <td colspan=''>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>";
			
			
		  if($causalName != ""){
			$html	= $html."<tr>
							<td colspan='1'>
								Tipo:
							</td>
							<td colspan='2'>
								".$causalName."
							</td>
						</tr>";
		  }
			
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            Estado
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
                          </td>
                        </tr>
                            
						<tr>
                          <td colspan='1'>
                            Moneda:
                          </td>
						  <td colspan='2'>
                            ".$objCurrency->simbol."
                          </td>
                        </tr>
						
						<tr>
							<td colspan='1'>Garantia:</td>
							<td colspan='2'>".$objTransactionMastser->note."</td>                          
                        </tr>
						
						<tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
						
						";
			
		
						
          $html	= $html."
						<tr>
						  <td colspan='1'>
							Cliente:
                          <td colspan='2'>
                          </td>
                        </tr>
						
						<tr>
						  <td colspan='3'>							
                            ". ( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>

						
						<!--
                        <tr>
                          <td colspan='3'>
                            Tipo de Cambio: ".$tipoCambio."
                          </td>
                        </tr>
						-->
						
                                
                        
                                
                     
                                
                         [[DETALLE]]
                         
						<tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
						
						<tr>
                          <td colspan='3' style='text-align:left'>
                            No. Arti. [[QUANTITY_ARTICLE]]
                          </td>
                        </tr>						 
						
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                             
                        <tr>
                          <td colspan='2'>
                            TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount)."
                          </td>
                        </tr>
   
                        <tr>
                          <td colspan='2'>
                            RECIBIDO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount + $objTransactionMasterInfo->changeAmount)."
                          </td>
                        </tr>
                         <tr>
                          <td colspan='2'>
                            CAMBIO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f", ($objTransactionMasterInfo->changeAmount)  )."
                          </td>
                        </tr>

						<!--
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

						
						<tr>
                          <td colspan='3' style='text-align:center'>
                            1) Este producto se entrega PROBADO Y Sin Grantia
                          </td>
                        </tr>
						
						<tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
						
						-->
						
						<!--
						
						<tr>
                          <td colspan='3' style='text-align:center'>
                            2) Este producto posee GARANTIA
                          </td>
                        </tr>
						
						<tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
						
						-->
						
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ***Gracias por su compra***
                          </td>
                        </tr>
                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
	$rowin  = 0;
    foreach($arrayDetalle as $row){
		
            $colun  		= 0;
			$colunCantidad 	= count($row);
			$nuevaFila		= 0;
            foreach ($row as $col => $key){				
			
			    //encabezado
				if($rowin == 0){
					
					//Mostrar los productos en una sola fila
					if($colun == 0){
						$cuerpo 		= $cuerpo."<tr >";							
					}	
						
					$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style"]." colspan='".$confiDetalle[$colun]["colspan"]."' >";
					$cuerpo = $cuerpo." ".$key;
					$cuerpo = $cuerpo."</td>";
						
					if($colun == $colunCantidad ){
						$cuerpo 	   = $cuerpo."</tr >";
					}
					
				}
				//datos
				else{
					
					if( $confiDetalle[$colun]["nueva_fila_row_data"] ==  1 ){
						$cuerpo 		= $cuerpo."<tr >";							
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";
						
						$cuerpo 		= $cuerpo."</tr >";	
						$nuevaFila		= 1;						
					}	
					else{
							
						
						if($nuevaFila == 1)
							$cuerpo 	= $cuerpo."<tr >";								
						
						if($rowin > 0 && $colun == 0)
							$cuerpo 	= $cuerpo."<tr >";								
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";								
						
						if($colun == $colunCantidad)
							$cuerpo 	= $cuerpo."</tr >";								
						
						$nuevaFila = 0;
							
					}
						
				
				}
				
                
				
                $colun++;
            }
			
			$rowin++;
            
    }
    
	
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
	$html = str_replace("[[QUANTITY_ARTICLE]]", ($rowin-1), $html);
	
    return $html;
}



function helper_reporte80mmTransactionMasterShareCarlosLuis(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo, 
    $confiDetalle, /**/
    $arrayDetalle, /**/
    $objParameterTelefono, /*telefono*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 2.7in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='110'  >
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->name)."
                          </td>
                        </tr>";
    

          if($userNickName != "")
          {
            $html	= $html."<tr>                              
    						  <td colspan='3' style='text-align:center'>
                                ".  $rucCompany ."
                              </td>
                            </tr>";
          }
    
          $html = $html."<tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";
						
          if($userNickName != "")
		  {      
			$html	= $html."<tr>
                          <td colspan=''>
                            Vendedor:
                          </td>
						  <td colspan='2'>
                            ". (strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )   ."
                          </td>
                        </tr>";
		  }
						
          $html	= $html."<tr>
                          <td colspan=''>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>";
			
			
		  if($causalName != ""){
			$html	= $html."<tr>
							<td colspan='1'>
								Tipo:
							</td>
							<td colspan='2'>
								".$causalName."
							</td>
						</tr>";
		  }
			
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            Estado
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
                          </td>
                        </tr>
                            
						<tr>
                          <td colspan='1'>
                            Moneda:
                          </td>
						  <td colspan='2'>
                            ".$objCurrency->simbol."
                          </td>
                        </tr>";
			
		
						
          $html	= $html."
						<tr>
						  <td colspan='1'>
							Cliente:
                          <td colspan='2'>
                          </td>
                        </tr>
						
						<tr>
						  <td colspan='3'>							
                            ". ( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>

						
						<!--
                        <tr>
                          <td colspan='3'>
                            Tipo de Cambio: ".$tipoCambio."
                          </td>
                        </tr>
						-->
						
						<tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                                
                     
                                
                         [[DETALLE]]
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                             
                        <tr>
                          <td colspan='2'>
                            TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount)."
                          </td>
                        </tr>
   
                        <tr>
                          <td colspan='2'>
                            RECIBIDO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount + $objTransactionMasterInfo->changeAmount)."
                          </td>
                        </tr>
                         <tr>
                          <td colspan='2'>
                            CAMBIO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f", ($objTransactionMasterInfo->changeAmount)  )."
                          </td>
                        </tr>

                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
						
						<tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
						
						<tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
						
						<tr>
                          <td colspan='3' style='text-align:center'>
                            ____________________________
                          </td>
                        </tr>
						
						 <tr>
                          <td colspan='3' style='text-align:center'>
                            Firma
                          </td>
                        </tr>
						
						
						 <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objCompany->address."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objParameterTelefono->value."
                          </td>
                        </tr>


                        <tr>
                          <td colspan='3' style='text-align:center' >
                            sistema 505-8712-5827
                          </td>
                        </tr>
                                
                        

                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
	$rowin  = 0;
    foreach($arrayDetalle as $row){
		
            $colun  		= 0;
			$colunCantidad 	= count($row);
			$nuevaFila		= 0;
            foreach ($row as $col => $key){				
			
			    //encabezado
				if($rowin == 0){
					
					//Mostrar los productos en una sola fila
					if($colun == 0){
						$cuerpo 		= $cuerpo."<tr >";							
					}	
						
					$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style"]." colspan='".$confiDetalle[$colun]["colspan"]."' >";
					$cuerpo = $cuerpo." ".$key;
					$cuerpo = $cuerpo."</td>";
						
					if($colun == $colunCantidad ){
						$cuerpo 	   = $cuerpo."</tr >";
					}
					
				}
				//datos
				else{
					
					if( $confiDetalle[$colun]["nueva_fila_row_data"] ==  1 ){
						$cuerpo 		= $cuerpo."<tr >";							
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";
						
						$cuerpo 		= $cuerpo."</tr >";	
						$nuevaFila		= 1;						
					}	
					else{
							
						
						if($nuevaFila == 1)
							$cuerpo 	= $cuerpo."<tr >";								
						
						if($rowin > 0 && $colun == 0)
							$cuerpo 	= $cuerpo."<tr >";								
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";								
						
						if($colun == $colunCantidad)
							$cuerpo 	= $cuerpo."</tr >";								
						
						$nuevaFila = 0;
							
					}
						
				
				}
				
                
				
                $colun++;
            }
			
			$rowin++;
            
    }
    
	
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
    return $html;
}


function helper_reporte80mmTransactionMasterPabloRosales(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo, 
    $confiDetalle, /**/
    $arrayDetalle, /**/
    $objParameterTelefono, /*telefono*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 2.7in 60in;                  
                          margin-top:0px;
                          margin-left:15px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='110'  >
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->name)."
                          </td>
                        </tr>";
    

          if($userNickName != "")
          {
            $html	= $html."<tr>                              
    						  <td colspan='3' style='text-align:center'>
                                ".  $rucCompany ."
                              </td>
                            </tr>";
          }
    
          $html = $html."<tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";
						
          if($userNickName != "")
		  {      
			$html	= $html."<tr>
                          <td colspan=''>
                            Vendedor:
                          </td>
						  <td colspan='2'>
                            ". (strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )   ."
                          </td>
                        </tr>";
		  }
						
          $html	= $html."<tr>
                          <td colspan=''>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>";
			
			
		  if($causalName != ""){
			$html	= $html."<tr>
							<td colspan='1'>
								Tipo:
							</td>
							<td colspan='2'>
								".$causalName."
							</td>
						</tr>";
		  }
			
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            Estado
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
                          </td>
                        </tr>
                            
						<tr>
                          <td colspan='1'>
                            Moneda:
                          </td>
						  <td colspan='2'>
                            ".$objCurrency->simbol."
                          </td>
                        </tr>";
			
		
						
          $html	= $html."
						<tr>
						  <td colspan='1'>
							Cliente:
                          <td colspan='2'>
                          </td>
                        </tr>
						
						<tr>
						  <td colspan='3'>							
                            ". ( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>

						
						<!--
                        <tr>
                          <td colspan='3'>
                            Tipo de Cambio: ".$tipoCambio."
                          </td>
                        </tr>
						-->
						
						<tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                                
                     
                                
                         [[DETALLE]]
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                             
                        <tr>
                          <td colspan='2'>
                            TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount)."
                          </td>
                        </tr>
   
                        <tr>
                          <td colspan='2'>
                            RECIBIDO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount + $objTransactionMasterInfo->changeAmount)."
                          </td>
                        </tr>
                         <tr>
                          <td colspan='2'>
                            CAMBIO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f", ($objTransactionMasterInfo->changeAmount)  )."
                          </td>
                        </tr>

                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objCompany->address."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objParameterTelefono->value."
                          </td>
                        </tr>


                        <tr>
                          <td colspan='3' style='text-align:center' >
                            sistema 505-8712-5827
                          </td>
                        </tr>
                                
                        

                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
	$rowin  = 0;
    foreach($arrayDetalle as $row){
		
            $colun  		= 0;
			$colunCantidad 	= count($row);
			$nuevaFila		= 0;
            foreach ($row as $col => $key){				
			
			    //encabezado
				if($rowin == 0){
					
					//Mostrar los productos en una sola fila
					if($colun == 0){
						$cuerpo 		= $cuerpo."<tr >";							
					}	
						
					$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style"]." colspan='".$confiDetalle[$colun]["colspan"]."' >";
					$cuerpo = $cuerpo." ".$key;
					$cuerpo = $cuerpo."</td>";
						
					if($colun == $colunCantidad ){
						$cuerpo 	   = $cuerpo."</tr >";
					}
					
				}
				//datos
				else{
					
					if( $confiDetalle[$colun]["nueva_fila_row_data"] ==  1 ){
						$cuerpo 		= $cuerpo."<tr >";							
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";
						
						$cuerpo 		= $cuerpo."</tr >";	
						$nuevaFila		= 1;						
					}	
					else{
							
						
						if($nuevaFila == 1)
							$cuerpo 	= $cuerpo."<tr >";								
						
						if($rowin > 0 && $colun == 0)
							$cuerpo 	= $cuerpo."<tr >";								
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";								
						
						if($colun == $colunCantidad)
							$cuerpo 	= $cuerpo."</tr >";								
						
						$nuevaFila = 0;
							
					}
						
				
				}
				
                
				
                $colun++;
            }
			
			$rowin++;
            
    }
    
	
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
    return $html;
}



function helper_reporte80mmTransactionMasterInvoiceCarlosLuis(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo, 
    $confiDetalle, /**/
    $arrayDetalle, /**/
    $objParameterTelefono, /*telefono*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 2.7in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='110'  >
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->name)."
                          </td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objCompany->address."
                          </td>
                        </tr>
						";
    

          if($userNickName != "")
          {
            $html	= $html."<tr>                              
    						  <td colspan='3' style='text-align:center'>
                                ".  $rucCompany ."
                              </td>
                            </tr>";
          }
    
          $html = $html."<tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";
						
          if($userNickName != "")
		  {      
			$html	= $html."<tr>
                          <td colspan=''>
                            Vendedor:
                          </td>
						  <td colspan='2'>
                            ". (strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )   ."
                          </td>
                        </tr>";
		  }
						
          $html	= $html."<tr>
                          <td colspan=''>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>";
			
			
		  if($causalName != ""){
			$html	= $html."<tr>
							<td colspan='1'>
								Tipo:
							</td>
							<td colspan='2'>
								".$causalName."
							</td>
						</tr>";
		  }
			
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            Estado
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
                          </td>
                        </tr>
                            
						<tr>
                          <td colspan='1'>
                            Moneda:
                          </td>
						  <td colspan='2'>
                            ".$objCurrency->simbol."
                          </td>
                        </tr>";
			
		
						
          $html	= $html."
						<tr>
						  <td colspan='1'>
							Cliente:
                          <td colspan='2'>
                          </td>
                        </tr>
						
						<tr>
						  <td colspan='3'>							
                            ". ( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>

						
						<!--
                        <tr>
                          <td colspan='3'>
                            Tipo de Cambio: ".$tipoCambio."
                          </td>
                        </tr>
						-->
						
						<tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                                
                     
                                
                         [[DETALLE]]
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                             
                        <tr>
                          <td colspan='2'>
                            TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount)."
                          </td>
                        </tr>
   
                        <tr>
                          <td colspan='2'>
                            RECIBIDO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMasterInfo->receiptAmount)."
                          </td>
                        </tr>
						";
						
			if($causalName != "" && $causalName != "CREDITO")
			{			
				$html	= $html."
                         <tr>
                          <td colspan='2'>
                            CAMBIO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f", ($objTransactionMasterInfo->receiptAmount - $objTransactionMastser->amount)  )."
                          </td>
                        </tr>";
			}
						

			$html = $html."
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";


			if($causalName != "" && $causalName == "CREDITO"){
				$html	= $html."
							<tr>
							  <td colspan='3' style='text-align:center'>
								&nbsp;
							  </td>
							</tr>
							<tr>
							  <td colspan='3' style='text-align:center'>
								&nbsp;
							  </td>
							</tr>
							<tr>
								<td colspan='3'  style='text-align:center' >
									____________________________
								</td>
							</tr>";
							
				$html	= $html."<tr>
								<td colspan='3'  style='text-align:center' >
									Firma
								</td>
							</tr>";
			}
			
			  
            $html = $html."
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objParameterTelefono->value."
                          </td>
                        </tr>


                        <tr>
                          <td colspan='3' style='text-align:center' >
                            sistema 505-8712-5827
                          </td>
                        </tr>
                                
                        

                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
	$rowin  = 0;
    foreach($arrayDetalle as $row){
		
            $colun  		= 0;
			$colunCantidad 	= count($row);
			$nuevaFila		= 0;
            foreach ($row as $col => $key){				
			
			    //encabezado
				if($rowin == 0){
					
					//Mostrar los productos en una sola fila
					if($colun == 0){
						$cuerpo 		= $cuerpo."<tr >";							
					}	
						
					$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style"]." colspan='".$confiDetalle[$colun]["colspan"]."' >";
					$cuerpo = $cuerpo." ".$key;
					$cuerpo = $cuerpo."</td>";
						
					if($colun == $colunCantidad ){
						$cuerpo 	   = $cuerpo."</tr >";
					}
					
				}
				//datos
				else{
					
					if( $confiDetalle[$colun]["nueva_fila_row_data"] ==  1 ){
						$cuerpo 		= $cuerpo."<tr >";							
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";
						
						$cuerpo 		= $cuerpo."</tr >";	
						$nuevaFila		= 1;						
					}	
					else{
							
						
						if($nuevaFila == 1)
							$cuerpo 	= $cuerpo."<tr >";								
						
						if($rowin > 0 && $colun == 0)
							$cuerpo 	= $cuerpo."<tr >";								
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";								
						
						if($colun == $colunCantidad)
							$cuerpo 	= $cuerpo."</tr >";								
						
						$nuevaFila = 0;
							
					}
						
				
				}
				
                
				
                $colun++;
            }
			
			$rowin++;
            
    }
    
	
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
    return $html;
}

function helper_reporte80mmTransactionMasterInvoiceDouglas(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo, 
    $confiDetalle, /**/
    $arrayDetalle, /**/
    $objParameterTelefono, /*telefono*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 2.7in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                       
						";
    
						
          if($userNickName != "")
		  {      
			$html	= $html."<tr>
                          <td colspan=''>
                            Vendedor:
                          </td>
						  <td colspan='2'>
                            ". (strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )   ."
                          </td>
                        </tr>";
		  }
						
          $html	= $html."<tr>
                          <td colspan=''>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>";
			
			
		  if($causalName != ""){
			$html	= $html."<tr>
							<td colspan='1'>
								Tipo:
							</td>
							<td colspan='2'>
								".$causalName."
							</td>
						</tr>";
		  }
			
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            Estado
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
                          </td>
                        </tr>
                            
						<tr>
                          <td colspan='1'>
                            Moneda:
                          </td>
						  <td colspan='2'>
                            ".$objCurrency->simbol."
                          </td>
                        </tr>";
			
		
						
          $html	= $html."
						<tr>
						  <td colspan='1'>
							Cliente:
                          <td colspan='2'>
                          </td>
                        </tr>
						
						<tr>
						  <td colspan='3'>							
                            ". ( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>

					
						
					
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                                
                     
                                
                         [[DETALLE]]
                                
                        
                             
                        <tr>
                          <td colspan='2'>
                            TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount)."
                          </td>
                        </tr>
   
                        <tr>
                          <td colspan='2'>
                            RECIBIDO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMasterInfo->receiptAmount)."
                          </td>
                        </tr>
                         <tr>
                          <td colspan='2'>
                            CAMBIO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f", ($objTransactionMasterInfo->receiptAmount - $objTransactionMastser->amount)  )."
                          </td>
                        </tr>

                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

                      
                      


                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
	$rowin  = 0;
    foreach($arrayDetalle as $row){
		
            $colun  		= 0;
			$colunCantidad 	= count($row);
			$nuevaFila		= 0;
            foreach ($row as $col => $key){				
			
			    //encabezado
				if($rowin == 0){
					
					//Mostrar los productos en una sola fila
					if($colun == 0){
						$cuerpo 		= $cuerpo."<tr >";							
					}	
						
					$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style"]." colspan='".$confiDetalle[$colun]["colspan"]."' >";
					$cuerpo = $cuerpo." ".$key;
					$cuerpo = $cuerpo."</td>";
						
					if($colun == $colunCantidad ){
						$cuerpo 	   = $cuerpo."</tr >";
					}
					
				}
				//datos
				else{
					
					if( $confiDetalle[$colun]["nueva_fila_row_data"] ==  1 ){
						$cuerpo 		= $cuerpo."<tr >";							
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";
						
						$cuerpo 		= $cuerpo."</tr >";	
						$nuevaFila		= 1;						
					}	
					else{
							
						
						if($nuevaFila == 1)
							$cuerpo 	= $cuerpo."<tr >";								
						
						if($rowin > 0 && $colun == 0)
							$cuerpo 	= $cuerpo."<tr >";								
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";								
						
						if($colun == $colunCantidad)
							$cuerpo 	= $cuerpo."</tr >";								
						
						$nuevaFila = 0;
							
					}
						
				
				}
				
                
				
                $colun++;
            }
			
			$rowin++;
            
    }
    
	
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
    return $html;
}

function helper_reporte80mmTransactionMasterFcBlandon(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo, 
    $confiDetalle, /**/
    $arrayDetalle, /**/
    $objParameterTelefono, /*telefono*/
	$objEmployee, /*empleado*/
	$objEmployeeNatural, /*datos del empleado*/
	$objDocumentAmortization,
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 2.7in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='110'  >
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->name)."
                          </td>
                        </tr>";
    

          if($userNickName != "")
          {
            $html	= $html."<tr>                              
    						  <td colspan='3' style='text-align:center'>
                                ".  $rucCompany ."
                              </td>
                            </tr>";
          }
    
          $html = $html."<tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";
						
          if($userNickName != "")
		  {      
			$html	= $html."<tr>
                          <td colspan=''>
                            Vendedor:
                          </td>
						  <td colspan='2'>
                            ". (strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )   ."
                          </td>
                        </tr>";
		  }
						
          $html	= $html."<tr>
                          <td colspan=''>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>";
			
			
		  if($causalName != ""){
			$html	= $html."<tr>
							<td colspan='1'>
								Tipo:
							</td>
							<td colspan='2'>
								".$causalName."
							</td>
						</tr>";
		  }
			
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            Estado
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
                          </td>
                        </tr>
                            
						<tr>
                          <td colspan='1'>
                            Moneda:
                          </td>
						  <td colspan='2'>
                            ".$objCurrency->simbol."
                          </td>
                        </tr>
						
						<tr>
                          <td colspan='1'>
                            Vencimiento:
                          </td>
						  <td colspan='2'>
                            ".$objDocumentAmortization->fechaVencimiento."
                          </td>
                        </tr>
						
						<tr>
                          <td colspan='1'>
                            Cuot Pend:
                          </td>
						  <td colspan='2'>
                            ".$objDocumentAmortization->numeroCuotasPendiente."
                          </td>
                        </tr>
						
						<tr>
                          <td colspan='1'>
                            Monto mora:
                          </td>
						  <td colspan='2'>
                            ".round($objDocumentAmortization->montoEnMora,2)."
                          </td>
                        </tr>
						
						";
						
					
			
		
						
          $html	= $html."
						<tr>
						  <td colspan='1'>
							Cliente:
                          <td colspan='2'>
                          </td>
                        </tr>
						
						<tr>
						  <td colspan='3'>							
                            ". ( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName." ".$objEntidadNatural->lastName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>

						
						<!--
                        <tr>
                          <td colspan='3'>
                            Tipo de Cambio: ".$tipoCambio."
                          </td>
                        </tr>
						-->
						
						<tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                                
                     
                                
                         [[DETALLE]]
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                             
                      
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

						<tr>
                          <td colspan='3' style='text-align:center'>
                           somos su mejor opcion
                          </td>
                        </tr>
						
						
						
						<tr>
                          <td colspan='3' style='text-align:center'>
                            ". ($objEmployeeNatural ?   $objEmployeeNatural->firstName : "N/D" ) ."
                          </td>
                        </tr>
						
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objCompany->address."
                          </td>
                        </tr>
						
						<tr>
                          <td colspan='3' style='text-align:center' >
                            telefono: 2230-3056
                          </td>
                        </tr>
						
						
                        <tr>
                          <td colspan='3' style='text-align:center' >
                            whatsapp: 7748-6403
                          </td>
                        </tr>
                         
						<tr>
                          <td colspan='3' style='text-align:center' >
                            admin: 8918-7584 
                          </td>
                        </tr>
                               						 
                        

                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
	$rowin  = 0;
    foreach($arrayDetalle as $row){
		
            $colun  		= 0;
			$colunCantidad 	= count($row);
			$nuevaFila		= 0;
            foreach ($row as $col => $key){				
			
			    //encabezado
				if($rowin == 0){
					
					//Mostrar los productos en una sola fila
					if($colun == 0){
						$cuerpo 		= $cuerpo."<tr >";							
					}	
						
					$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style"]." colspan='".$confiDetalle[$colun]["colspan"]."' >";
					$cuerpo = $cuerpo." ".$key;
					$cuerpo = $cuerpo."</td>";
						
					if($colun == $colunCantidad ){
						$cuerpo 	   = $cuerpo."</tr >";
					}
					
				}
				//datos
				else{
					
					if( $confiDetalle[$colun]["nueva_fila_row_data"] ==  1 ){
						$cuerpo 		= $cuerpo."<tr >";							
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";
						
						$cuerpo 		= $cuerpo."</tr >";	
						$nuevaFila		= 1;						
					}	
					else{
							
						
						if($nuevaFila == 1)
							$cuerpo 	= $cuerpo."<tr >";								
						
						if($rowin > 0 && $colun == 0)
							$cuerpo 	= $cuerpo."<tr >";								
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";								
						
						if($colun == $colunCantidad)
							$cuerpo 	= $cuerpo."</tr >";								
						
						$nuevaFila = 0;
							
					}
						
				
				}
				
                
				
                $colun++;
            }
			
			$rowin++;
            
    }
    
	
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
    return $html;
}

function helper_reporte80mmTransactionMasterInputUnpost(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo,
    $confiDetalle,
    $arrayDetalle,
    $objParameterTelefono,
	$statusDisplay
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 3in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='110'  >
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->name)."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                               
						<tr>
						  <td colspan='1' style='text-align:left'>ESTADO:</td>
                          <td colspan='2' style='text-align:left'>".$statusDisplay."</td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                                
                     
                                
                         [[DETALLE]]
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                             
                        <tr>
                          <td colspan='2'>
                            TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount)."
                          </td>
                        </tr>
   
                       

                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objCompany->address."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objParameterTelefono->value."
                          </td>
                        </tr>


                        <tr>
                          <td colspan='3' style='text-align:center' >
                            sistema 505-8712-5827
                          </td>
                        </tr>
                                
                        

                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
    $rowin  = 0;
    foreach($arrayDetalle as $row){
        
        $colun  		= 0;
        $colunCantidad 	= count($row);
        $nuevaFila		= 0;
        foreach ($row as $col => $key){
            
            //encabezado
            if($rowin == 0){
                
                //Mostrar los productos en una sola fila
                if($colun == 0){
                    $cuerpo 		= $cuerpo."<tr >";
                }
                
                $cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style"]." colspan='".$confiDetalle[$colun]["colspan"]."' >";
                $cuerpo = $cuerpo." ".$key;
                $cuerpo = $cuerpo."</td>";
                
                if($colun == $colunCantidad ){
                    $cuerpo 	   = $cuerpo."</tr >";
                }
                
            }
            //datos
            else{
                
                if( $confiDetalle[$colun]["nueva_fila_row_data"] ==  1 ){
                    $cuerpo 		= $cuerpo."<tr >";
                    
                    $cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
                    $cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
                    $cuerpo = $cuerpo."</td>";
                    
                    $cuerpo 		= $cuerpo."</tr >";
                    $nuevaFila		= 1;
                }
                else{
                    
                    
                    if($nuevaFila == 1)
                        $cuerpo 	= $cuerpo."<tr >";
                        
                        if($rowin > 0 && $colun == 0)
                            $cuerpo 	= $cuerpo."<tr >";
                            
                            $cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
                            $cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
                            $cuerpo = $cuerpo."</td>";
                            
                            if($colun == $colunCantidad)
                                $cuerpo 	= $cuerpo."</tr >";
                                
                                $nuevaFila = 0;
                                
                }
                
                
            }
            
            
            
            $colun++;
        }
        
        $rowin++;
        
    }
    
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
    return $html;
}
function helper_reporteA4mmTransactionMasterInputUnpost(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo,
    $confiDetalle,
    $arrayDetalle,
    $objParameterTelefono,
	$statusDisplay
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: A4;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='110'  >
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->name)."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                               
						<tr>
						  <td colspan='1' style='text-align:left'>ESTADO:</td>
                          <td colspan='2' style='text-align:left'>".$statusDisplay."</td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                                
                     
                                
                         [[DETALLE]]
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                             
                        <tr>
                          <td colspan='2'>
                            TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount)."
                          </td>
                        </tr>
   
                       

                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objCompany->address."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objParameterTelefono->value."
                          </td>
                        </tr>


                        <tr>
                          <td colspan='3' style='text-align:center' >
                            sistema 505-8712-5827
                          </td>
                        </tr>
                                
                        

                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
    $rowin  = 0;
    foreach($arrayDetalle as $row){
        
        $colun  		= 0;
        $colunCantidad 	= count($row);
        $nuevaFila		= 0;
        foreach ($row as $col => $key){
            
            //encabezado
            if($rowin == 0){
                
                //Mostrar los productos en una sola fila
                if($colun == 0){
                    $cuerpo 		= $cuerpo."<tr >";
                }
                
                $cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style"]." colspan='".$confiDetalle[$colun]["colspan"]."' >";
                $cuerpo = $cuerpo." ".$key;
                $cuerpo = $cuerpo."</td>";
                
                if($colun == $colunCantidad ){
                    $cuerpo 	   = $cuerpo."</tr >";
                }
                
            }
            //datos
            else{
                
                if( $confiDetalle[$colun]["nueva_fila_row_data"] ==  1 ){
                    $cuerpo 		= $cuerpo."<tr >";
                    
                    $cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
                    $cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
                    $cuerpo = $cuerpo."</td>";
                    
                    $cuerpo 		= $cuerpo."</tr >";
                    $nuevaFila		= 1;
                }
                else{
                    
                    
                    if($nuevaFila == 1)
                        $cuerpo 	= $cuerpo."<tr >";
                        
                        if($rowin > 0 && $colun == 0)
                            $cuerpo 	= $cuerpo."<tr >";
                            
                            $cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
                            $cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
                            $cuerpo = $cuerpo."</td>";
                            
                            if($colun == $colunCantidad)
                                $cuerpo 	= $cuerpo."</tr >";
                                
                                $nuevaFila = 0;
                                
                }
                
                
            }
            
            
            
            $colun++;
        }
        
        $rowin++;
        
    }
    
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
    return $html;
}

function helper_reporte58mmTransactionMaster(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo,
    $confiDetalle,
    $arrayDetalle,
    $objParameterTelefono
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 2.2in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:0px;
                        }
                        table{			
						  
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Arial, Consolas, monaco, monospace;						  
						  
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                        <tr>
						  <!--
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='110'  >
                          </td>
						  -->
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->name)."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3'>
                            CLIENTE: ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3'>
                            ".$objEntidadNatural->firstName."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3'>
                            Moneda: ".$objCurrency->simbol."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3'>
                            Tipo de Cambio: ".$tipoCambio."
                          </td>
                        </tr>
						
						<tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                                
                     
                                
                         [[DETALLE]]
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                             
                        <tr>
                          <td colspan='2'>
                            TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount)."
                          </td>
                        </tr>
   
                        <tr>
                          <td colspan='2'>
                            RECIBIDO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMasterInfo->receiptAmount)."
                          </td>
                        </tr>
                         <tr>
                          <td colspan='2'>
                            CAMBIO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",0)."
                          </td>
                        </tr>

                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objCompany->address."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objParameterTelefono->value."
                          </td>
                        </tr>


                        <tr>
                          <td colspan='3' style='text-align:center' >
                            sistema 505-8712-5827
                          </td>
                        </tr>
                                
                        

                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
    $rowin  = 0;
    foreach($arrayDetalle as $row){
        
        $colun  		= 0;
        $colunCantidad 	= count($row);
        $nuevaFila		= 0;
        foreach ($row as $col => $key){
            
            //encabezado
            if($rowin == 0){
                
                //Mostrar los productos en una sola fila
                if($colun == 0){
                    $cuerpo 		= $cuerpo."<tr >";
                }
                
                $cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style"]." colspan='".$confiDetalle[$colun]["colspan"]."' >";
                $cuerpo = $cuerpo." ".$key;
                $cuerpo = $cuerpo."</td>";
                
                if($colun == $colunCantidad ){
                    $cuerpo 	   = $cuerpo."</tr >";
                }
                
            }
            //datos
            else{
                
                if( $confiDetalle[$colun]["nueva_fila_row_data"] ==  1 ){
                    $cuerpo 		= $cuerpo."<tr >";
                    
                    $cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
                    $cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
                    $cuerpo = $cuerpo."</td>";
                    
                    $cuerpo 		= $cuerpo."</tr >";
                    $nuevaFila		= 1;
                }
                else{
                    
                    
                    if($nuevaFila == 1)
                        $cuerpo 	= $cuerpo."<tr >";
                        
                        if($rowin > 0 && $colun == 0)
                            $cuerpo 	= $cuerpo."<tr >";
                            
                            $cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
                            $cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
                            $cuerpo = $cuerpo."</td>";
                            
                            if($colun == $colunCantidad)
                                $cuerpo 	= $cuerpo."</tr >";
                                
                                $nuevaFila = 0;
                                
                }
                
                
            }
            
            
            
            $colun++;
        }
        
        $rowin++;
        
    }
    
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
    return $html;
}

function helper_reporte80mmTransactionMasterPosMe(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo,
    $confiDetalle,
    $arrayDetalle,
    $objParameterTelefono
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 3in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='110'  >
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->name)."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3'>
                            CLIENTE: ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3'>
                            ".$objEntidadNatural->firstName."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3'>
                            Moneda: ".$objCurrency->simbol."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3'>
                            Tipo de Cambio: ".$tipoCambio."
                          </td>
                        </tr>
						
						<tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                                
                     
                                
                        [[DETALLE]]
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                             
                        <tr>
                          <td colspan='2'>
                            TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount)."
                          </td>
                        </tr>
   
                        <tr>
                          <td colspan='2'>
                            RECIBIDO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMasterInfo->receiptAmount)."
                          </td>
                        </tr>
                         <tr>
                          <td colspan='2'>
                            CAMBIO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",0)."
                          </td>
                        </tr>

                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objCompany->address."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objParameterTelefono->value."
                          </td>
                        </tr>


                        <tr>
                          <td colspan='3' style='text-align:center' >
                            sistema 505-8712-5827
                          </td>
                        </tr>
                                
                        

                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
	if($arrayDetalle)
	{
		foreach($arrayDetalle as $row){
				$cuerpo = $cuerpo."<tr >";
				$colun  = 0;
				foreach ($row as $col => $key){
					$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style"]." colspan='".$confiDetalle[$colun]["colspan"]."' >";
					$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix"]." ".$key;
					$cuerpo = $cuerpo."</td>";
					$colun++;
				}
				$cuerpo = $cuerpo."</tr>";        
		}
	}
    
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
    return $html;
}
 

function helper_reporteA4TransactionMasterExamenLab(
    $titulo, /*titulo*/
    $objCompany, /*compania*/
    $objParameterLogo, /*Logo*/
    $objTransactionMastser, /*documento*/
    $objEntidadNatural, /*nombre del cliente*/
    $objEntidadCustomer, /*cliente*/
    $tipoCambio, /*tipo de cambio*/
    $objCurrency, /*moneda*/
    $objTransactionMasterInfo, /*informacion del documento*/    
	$objTransactionMasterDetail, /*detalle de transaccion*/ 
    $objParameterTelefono, /*objeto parametro telefono*/
	$statusName, /*estado del documento*/
	$causalName /*nombre del causal*/ ,	
	$objMuestra,
	$objTipoExamen,
	$objEdad,
	$objSexo
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: a4;                  
                          margin-top:0px;
                          margin-left:20px;
                          margin-right:20px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
						<table style='width:100%'>
							<tr><td>&nbsp; &nbsp; </td></tr>
							<tr><td>&nbsp; &nbsp; </td></tr>
							<tr>
							  <td style='text-align:center;width: 150px;'>
								<img  src='".$base64."' width='110'  >
							  </td>
							  <td  style='text-align:left'>
									<table>
										<tbody>
											<tr><td> ".strtoupper($objCompany->name)."</td></tr>
											<tr><td> ".$objCompany->address."</td></tr>
											<tr><td> ".strtoupper($objTransactionMastser->transactionNumber)."</td></tr>
											<tr><td>  ".$objParameterTelefono->value."</td></tr>
											<tr><td>  Horario de atencion: 7:00 A.M A  03:00 P.M</td></tr>
										</tbody>									
									</table>
							  </td>
							</tr>
							<tr><td>&nbsp; &nbsp; </td></tr>
						</table>
						
                        ";
						
				$html	= $html."
						<table style='width:100%' >							
							<tr>
							  <td  style='text-align:left;width: 35px;' >Paciente:</td>
							  <td  style='text-align:left;width: auto;' >".$objEntidadNatural->firstName."</td>							  
							  <td  style='text-align:left;width: 25px;' >Fecha:</td>
							  <td  style='text-align:left;width: 160px;' >".$objTransactionMastser->statusIDChangeOn."</td>
							  <td  style='text-align:left;width: 25px;' >".getBehavio($objCompany->type,"web_tools_report_helper","Edad","").":</td>
							  <td  style='text-align:left;width: 25px;' >".$objEdad->name."</td>
							  <td  style='text-align:left;width: 25px;' >".getBehavio($objCompany->type,"web_tools_report_helper","Sexo","").":</td>
							  <td  style='text-align:left;width: 25px;' >".$objSexo->name."</td>
							</tr>
							<tr>
								<td colspan='2' style='text-align:left;width: 25px;' >
									Fecha de nacimineto:".$objEntidadCustomer->birthDate."
								</td>
								<td colspan='6' >
									
								</td>
							</tr>
							<tr>
								<td colspan='1' style='text-align:left;width: 25px;' >
									Edad:
								</td>
								<td colspan='7' >
									".helper_GetFechaNacimiento($objEntidadCustomer->birthDate)." años
								</td>
							</tr>
							<tr>
								<td colspan='1' style='text-align:left;width: 25px;' >
									Examen:
								</td>
								<td colspan='7' >
									".$objTipoExamen->name."
								</td>
							</tr>
							<tr><td colspan='8' >&nbsp; &nbsp; </td></tr>
						</table>";
			
			$objTransactionMasterDetail = array_filter($objTransactionMasterDetail , function($k){										
					return $k->reference3 != "";
			});
				
			$objListFilas	 	= array_column($objTransactionMasterDetail,"description");
			$objListFilas		= array_unique($objListFilas);
			foreach($objListFilas as $fila)
			{
				
					
				$columnas 		= array_filter($objTransactionMasterDetail , function($k) use ($fila)
				{										
					return $k->description == $fila;
				});
				$columnas	 	= array_column($columnas,"reference1");
				$columnas	 	= array_unique($columnas);
				$cant			= count($columnas);
				
				//Inicio de tabla de los grupos
				$html = $html."<table style='width:100%;border-collapse: collapse'>";
				$html = $html."<tr style='background-color: blanchedalmond;' ><td colspan='".($cant * 1)."' style='text-align:center;font-weight:bold'  >".strtoupper($fila)."</td></tr>";				
				$html = $html."<tr>";
				
				//Foreach de las una columna o dos columnas o tres columnas de los sub grupos
				foreach($columnas as $columna)
				{
					
					$html = $html."<td style='vertical-align:top'>";
					$valores 		= array_filter($objTransactionMasterDetail , function($k) use ($fila,$columna)
					{										
						return $k->description == $fila && $k->reference1 == $columna;
					});
					$html = $html."<table style='width:100%;border-collapse: collapse' >";					
					$html = $html."<tr style='background-color: blanchedalmond;' ><td colspan='3' style='text-align:left;;font-weight:bold' >".strtoupper($columna)."</td></tr>";
					
					//Encavezado
					if($cant == 1)
					{						
						
						$html = $html."<tr  >";
							$html = $html."<td style='text-align:left;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse;width:33% '>Análisis</td>";
							$html = $html."<td style='text-align:left;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse;width:33%'>Resultado</td>";
							$html = $html."<td style='text-align:left;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse;color:blue;width:33%'>Valores esperados</td>";
						$html = $html."</tr>";

					}					
					else 
					{
												
						$html = $html."<tr  >";
							$html = $html."<td style='text-align:left;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse; '>Análisis</td>";
							$html = $html."<td style='text-align:left;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse;'>Resultado</td>";
							$html = $html."<td style='text-align:right;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse;color:blue'>Valores esperados</td>";
						$html = $html."</tr>";
					}
					
						
					
					//foreach de los indicadores de cada columna
					foreach($valores as $valor)
					{
						$valor->display	= str_replace("****","</br>",$valor->display);
						$valor->display	= str_replace("|","</br>",$valor->display);
						$valor->display	= str_replace(",","</br>",$valor->display);
						
						//si el examen solo tiene una columna mostrar el indicador de la siguiente manera						
						if($cant == 1)
						{
							
							
							$html = $html."<tr  >";
								$html = $html."<td style='text-align:left;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse;width:33% '>".$valor->name."</td>";
								$html = $html."<td style='text-align:left;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse;width:33%'>".$valor->reference3."</td>";
								$html = $html."<td style='text-align:left;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse;color:blue;width:33%'>".htmlentities($valor->display)."</td>";
							$html = $html."</tr>";

						}
						//si el indicador tiene 2 columnas
						//mostrar el indicador de la siguiente manera
						else 
						{
													
							$html = $html."<tr  >";
								$html = $html."<td style='text-align:left;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse; '>".$valor->name."</td>";
								$html = $html."<td style='text-align:left;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse;'>".$valor->reference3."</td>";
								$html = $html."<td style='text-align:right;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse;color:blue'>".htmlentities($valor->display)."</td>";
							$html = $html."</tr>";
						}
					}
					$html = $html."</table>";					
					$html = $html."</td>";
					
				}
				$html = $html."</tr>";	
				$html = $html."<tr><td>&nbsp;</td></tr>";					
				$html = $html."</table>";
				
				
			}
		  
						
			$html	= $html."		
						<table style='width:100%' >
							<tr><td style='text-align:left;'>&nbsp;</td></tr>							
							<tr><td style='text-align:left;' >Observacion general:</td></tr>
							<tr><td style='text-align:left;'>".$objTransactionMastser->note."</td></tr>
							<tr><td style='text-align:left;'>&nbsp;</td></tr>													
						</table>
						
                        <table style='width:100%'>
							<tr><td style='text-align:left;' >_____________________</td><td style='text-align:left;'>_____________________</td></tr>
							<tr><td style='text-align:left;' >Sello</td><td style='text-align:left;' >Firma</td></td></tr>
						</table>
                     
						<table style='width:100%' >
							<tr><td style='text-align:left;'>&nbsp;</td></tr>							
							<tr><td style='text-align:left;'>&nbsp;</td></tr>							
							<tr><td style='text-align:center;'>Servicio y Calidad</td></tr>
						</table>
						
					</body>     
                    </html>
            ";
    
   return $html;
}

function helper_reporte80mmCocina(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo, 
    $confiDetalle, /**/
    $arrayDetalle, /**/
    $objParameterTelefono, /*telefono*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 2.7in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                            
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->name)."
                          </td>
                        </tr>";
    

          
          $html = $html."       
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";
						
						
          $html	= $html."<tr>
                          <td colspan='3'>
                            ". ( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>

						
						
						
                                
                         [[DETALLE]]
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ****************************
                          </td>
                        </tr>
						
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
	$rowin  = 0;
    foreach($arrayDetalle as $row){
		
            $colun  		= 0;
			$colunCantidad 	= count($row);
			$nuevaFila		= 0;
            foreach ($row as $col => $key){				
			
			    //encabezado
				if($rowin == 0){
					
					//Mostrar los productos en una sola fila
					if($colun == 0){
						$cuerpo 		= $cuerpo."<tr >";							
					}	
						
					$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style"]." colspan='".$confiDetalle[$colun]["colspan"]."' >";
					$cuerpo = $cuerpo." ".$key;
					$cuerpo = $cuerpo."</td>";
						
					if($colun == $colunCantidad ){
						$cuerpo 	   = $cuerpo."</tr >";
					}
					
				}
				//datos
				else{
					
					if( $confiDetalle[$colun]["nueva_fila_row_data"] ==  1 ){
						$cuerpo 		= $cuerpo."<tr >";							
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";
						
						$cuerpo 		= $cuerpo."</tr >";	
						$nuevaFila		= 1;						
					}	
					else{
							
						
						if($nuevaFila == 1)
							$cuerpo 	= $cuerpo."<tr >";								
						
						if($rowin > 0 && $colun == 0)
							$cuerpo 	= $cuerpo."<tr >";								
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";								
						
						if($colun == $colunCantidad)
							$cuerpo 	= $cuerpo."</tr >";								
						
						$nuevaFila = 0;
							
					}
						
				
				}
				
                
				
                $colun++;
            }
			
			$rowin++;
            
    }
    
	
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
    return $html;
}

function helper_reporte58mmCocina(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo, 
    $confiDetalle, /**/
    $arrayDetalle, /**/
    $objParameterTelefono, /*telefono*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 2.7in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                            
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->name)."
                          </td>
                        </tr>";
    

          
          $html = $html."       
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";
						
						
          $html	= $html."<tr>
                          <td colspan='3'>
                            ". ( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>

						
						
						
                                
                         [[DETALLE]]
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ****************************
                          </td>
                        </tr>
						
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
	$rowin  = 0;
    foreach($arrayDetalle as $row){
		
            $colun  		= 0;
			$colunCantidad 	= count($row);
			$nuevaFila		= 0;
            foreach ($row as $col => $key){				
			
			    //encabezado
				if($rowin == 0){
					
					//Mostrar los productos en una sola fila
					if($colun == 0){
						$cuerpo 		= $cuerpo."<tr >";							
					}	
						
					$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style"]." colspan='".$confiDetalle[$colun]["colspan"]."' >";
					$cuerpo = $cuerpo." ".$key;
					$cuerpo = $cuerpo."</td>";
						
					if($colun == $colunCantidad ){
						$cuerpo 	   = $cuerpo."</tr >";
					}
					
				}
				//datos
				else{
					
					if( $confiDetalle[$colun]["nueva_fila_row_data"] ==  1 ){
						$cuerpo 		= $cuerpo."<tr >";							
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";
						
						$cuerpo 		= $cuerpo."</tr >";	
						$nuevaFila		= 1;						
					}	
					else{
							
						
						if($nuevaFila == 1)
							$cuerpo 	= $cuerpo."<tr >";								
						
						if($rowin > 0 && $colun == 0)
							$cuerpo 	= $cuerpo."<tr >";								
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$key;
						$cuerpo = $cuerpo."</td>";								
						
						if($colun == $colunCantidad)
							$cuerpo 	= $cuerpo."</tr >";								
						
						$nuevaFila = 0;
							
					}
						
				
				}
				
                
				
                $colun++;
            }
			
			$rowin++;
            
    }
    
	
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
    return $html;
}

function helper_reporte80mmTransactionMasterAttendance(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objParameterTelefono, /*telefono*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 2.7in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='110'  >
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->name)."
                          </td>
                        </tr>";
    

          if($userNickName != "")
          {
            $html	= $html."<tr>                              
    						  <td colspan='3' style='text-align:center'>
                                ".  $rucCompany ."
                              </td>
                            </tr>";
          }
    
          $html = $html."<tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";
						
          if($userNickName != "")
		  {      
			$html	= $html."<tr>
                          <td colspan=''>
                            Vendedor:
                          </td>
						  <td colspan='2'>
                            ". (strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )   ."
                          </td>
                        </tr>";
		  }
						
          $html	= $html."
						<tr>
                          <td colspan=''>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>
						<tr>
                          <td colspan=''>
                            Cliente:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadNatural->firstName."
                          </td>
                        </tr>
						
						<tr>
                          <td colspan=''>
                            Solvencia:
                          </td>
						  <td colspan='2'>
                            ".$objTransactionMastser->reference1."
                          </td>
                        </tr>
						
						<tr>
                          <td colspan=''>
                            Proximo pago:
                          </td>
						  <td colspan='2'>
                            ".$objTransactionMastser->reference2."
                          </td>
                        </tr>
						
							<tr>
                          <td colspan=''>
                            Dias Pro. Pago:
                          </td>
						  <td colspan='2'>
                            ".$objTransactionMastser->reference4."
                          </td>
                        </tr>
						
						<tr>
                          <td colspan=''>
                            Vencimiento:
                          </td>
						  <td colspan='2'>
                            ".$objTransactionMastser->reference3."
                          </td>
                        </tr>
						";
			
			
		 
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            Estado
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
                          </td>
                        </tr>
                            
					
						";
			
		
						
          $html	= $html."
                       
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objCompany->address."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objParameterTelefono->value."
                          </td>
                        </tr>


                        <tr>
                          <td colspan='3' style='text-align:center' >
                            sistema 505-8712-5827
                          </td>
                        </tr>
                                
                        

                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    return $html;
}


function helper_reporte80mmTransactionMasterInputOutPutCash(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo, 
    $confiDetalle, /**/
    $arrayDetalle, /**/
    $objParameterTelefono, /*telefono*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 2.7in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='110'  >
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->name)."
                          </td>
                        </tr>";
    

          if($userNickName != "")
          {
            $html	= $html."<tr>                              
    						  <td colspan='3' style='text-align:center'>
                                ".  $rucCompany ."
                              </td>
                            </tr>";
          }
    
          $html = $html."<tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";
						
          if($userNickName != "")
		  {      
			$html	= $html."<tr>
                          <td colspan=''>
                            Vendedor:
                          </td>
						  <td colspan='2'>
                            ". (strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )   ."
                          </td>
                        </tr>";
		  }
						
          $html	= $html."
						
						";
			
			
		  if($causalName != ""){
			$html	= $html."<tr>
							<td colspan='1'>
								Tipo:
							</td>
							<td colspan='2'>
								".$causalName."
							</td>
						</tr>";
		  }
			
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            Estado
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
                          </td>
                        </tr>
                            
						<tr>
                          <td colspan='1'>
                            Moneda:
                          </td>
						  <td colspan='2'>
                            ".$objCurrency->simbol."
                          </td>
                        </tr>";
			
		
						
          $html	= $html."
						

						
						<tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
                     
                                
                         [[DETALLE]]
                                
                     
                        <tr>
                          <td colspan='2'>
                            TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount)."
                          </td>
                        </tr>
   

                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objCompany->address."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objParameterTelefono->value."
                          </td>
                        </tr>


                        <tr>
                          <td colspan='3' style='text-align:center' >
                            sistema 505-8712-5827
                          </td>
                        </tr>
                                
                        

                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $html = str_replace("[[DETALLE]]","", $html);
    return $html;
}


function helper_reporte80mmTransactionMasterShareCapital(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTransactionMastser,
    $objEntidadNatural,
    $objEntidadCustomer,
    $tipoCambio,
    $objCurrency,
    $objTransactionMasterInfo, 
    $confiDetalle, /**/
    $arrayDetalle, /**/
    $objParameterTelefono, /*telefono*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
	$userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;
    
    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    $html    = "";
    $html    = "
                    <!--
                    Online HTML, CSS and JavaScript editor to run code online.
                    https://www.programiz.com/html/online-compiler/
                    -->
                    <!DOCTYPE html>
                    <html lang='en'>
        
                    <head>
                      <meta charset='UTF-8' />
                      <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                      <style>
                        @page {       
                          size: 2.7in 60in;                  
                          margin-top:0px;
                          margin-left:0px;
                          margin-right:15px;
                        }
                        table{
                          font-size: x-small;
                          font-weight: bold;
                          font-family: Consolas, monaco, monospace;
                        }
                      </style>
                    </head>
        
                    <body>
        
                      <table style='width:100%'>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='110'  >
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->name)."
                          </td>
                        </tr>";
    

          if($userNickName != "")
          {
            $html	= $html."<tr>                              
    						  <td colspan='3' style='text-align:center'>
                                ".  $rucCompany ."
                              </td>
                            </tr>";
          }
    
          $html = $html."<tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ".$objTransactionMastser->createdOn."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";
						
          if($userNickName != "")
		  {      
			$html	= $html."<tr>
                          <td colspan=''>
                            Vendedor:
                          </td>
						  <td colspan='2'>
                            ". (strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )   ."
                          </td>
                        </tr>";
		  }
						
          $html	= $html."
						
						";
			
			
		  if($causalName != ""){
			$html	= $html."<tr>
							<td colspan='1'>
								Tipo:
							</td>
							<td colspan='2'>
								".$causalName."
							</td>
						</tr>";
		  }
			
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            Estado
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
                          </td>
                        </tr>
                            
						<tr>
                          <td colspan='1'>
                            Moneda:
                          </td>
						  <td colspan='2'>
                            ".$objCurrency->simbol."
                          </td>
                        </tr>";
			
		
						
          $html	= $html."
						

						
						<tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
                     
                                
                         [[DETALLE]]
                                
                     
                        <tr>
                          <td colspan='2'>
                            TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount)."
                          </td>
                        </tr>
   

                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objCompany->address."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objParameterTelefono->value."
                          </td>
                        </tr>


                        <tr>
                          <td colspan='3' style='text-align:center' >
                            sistema 505-8712-5827
                          </td>
                        </tr>
                                
                        

                                
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $html = str_replace("[[DETALLE]]","", $html);
    return $html;
}