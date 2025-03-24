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
						  var BOM = "\uFEFF"; // Marcador UTF-8
						  var blob = new Blob([ BOM + tablaHTML], { type: "application/vnd.ms-excel;charset=utf-8" });

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
		$configColumn[$key]["Style"] 					= array_key_exists("Style",$value) ? $value["Style"] : "" ;		
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
		$widthTemporal 	= $widthTemporal + str_replace("px","",$value['Width']);
		
		if($value['Colspan'] != "0" )
		$table2 		= $table2.'<th nowrap style="text-overflow:ellipsis;overflow:hidden;white-space:nowrap;text-align:left;width:'.$value['Width'].'" colspan="'.$value['Colspan'].'" class="border"  >'.$value['Titulo'].'</th>';
	
	}
	$widthTemporal = $widthTemporal."px";
	
	
	
	$couterIndexColumn = 0;
	foreach($configColumn as $key => $value ){
		if($value['Colspan'] != "0" )
		{
			$table3 = 
			$table3.
			'<th nowrap style="text-overflow:ellipsis;overflow:hidden;white-space:nowrap;text-align:left;width:'.$value['Width'].'" colspan="'.$value['Colspan'].'" class="border"  >
				<input style="width:80px;height: 10px;margin-top: 5px;margin-bottom: 5px;"  type="text" placeholder="Filtrar" data-index="'.$couterIndexColumn.'" />
			</th>';
		}
		$couterIndexColumn++;
	}
	
	//Armar titulo
	$table1 =  
	'<table id="'.$idTable.'"  style="
			width:'.$widthTemporal.';order-spacing: 10px; table-layout: fixed;
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
			
			
			if($value['Colspan'] != "0")
			{
				$table = $table. "<td nowrap style='text-overflow:ellipsis;overflow:hidden;white-space:nowrap;text-align:".$value["Alineacion"].";".$value["Style"]." ' colspan='".$value['Colspan']."'  class='border'>";
					
					
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
			
		}
		$table = $table. "</tr>";			
	}
		
	//Armar Pie de Tabla
	$table = $table.'</tbody>';
	
	$table = $table.'<tfoot><tr>';
			
				foreach($configColumn as $key => $value ){
					
					if($value['Colspan'] != "0" )
					{
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
    $rucCompany = "" /*ruc*/ ,
	$dataSet = "" 
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
											".$dataSet["objBranch"]->address."
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											".getBehavio($objCompany->type,"app_invoice_billing","lblRptInvoiceRuc","Edificio Delta RUC: 888-080396-0001K")."
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											".getBehavio($objCompany->type,"app_invoice_billing","lblRptInvoicePhone","Teléfono: 2223-2314.")."
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
										<td colspan='2'>*=".getBehavio($objCompany->type,"app_invoice_billing","lblRptInvoiceOpcion1","Equipo seminuevo")." </td>
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
								".getBehavio($objCompany->type,"app_invoice_billing","lblRptInvoiceOpcion2","Al comprar tu equipo en GLOBAL PRO,puedes cambiarlo por uno superior cuando gustes!")."
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

function helper_reporteA4TransactionMasterInvoiceEbenezer(
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
	$numberDocument = str_replace("FAC","SERIE \"A\" RECIBO No ",$objTransactionMastser->transactionNumber);
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
						  size: A4;   
						
						  
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
						  <td  style='text-align:center;width:20%;text-align:left'>
							
						  </td>
						  <td  style='text-align:center;width:60%'>
								<h2 style='margin:0px' >CENTRO ESCOLAR PRIVADO ADVENTISTA EBEN-EZER MALPASILLO</h2>
								<h2 style='margin:0px' >CEPAEM</h2>
								<h2 style='margin:0px' >Antigua petronic 5c. al sur - Malpaisillo, León </h2>
						  </td>
						  <td style='width:20%;text-align: right;vertical-align: top;' >
							<img  src='".$base64."' width='70'  >
						  </td>
						</tr>
					</table>
					</br>
						";
		   
	$f_html = $f_html."
				
			";
			
			
	$f_html = $f_html."
			
				
				<table style='width:98%' >
					<tr>
						<td style='text-align:left;width:33%;vertical-align:bottom ' >
							<h2 style='margin:0px;color:blue' >RUC : ". $rucCompany ."</h2>
							<table style='width:100px ;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom." '>
								<tr>
									<td style='width:50px'> <h2 style='margin:0px;color:black' >Día</h2>
									</td>
									<td style='width:50px'> <h2 style='margin:0px;color:black' >Mes</h2>
									</td>
									<td style='width:50px'> <h2 style='margin:0px;color:black' >Año</h2>
									</td>
									<td style=''>.
									</td>
								</tr>
								<tr>
									<td>
										".(new \DateTime($objTransactionMastser->transactionOn))->format("d")."
									</td>
									<td>
										".(new \DateTime($objTransactionMastser->transactionOn))->format("m")."
									</td>
									<td>
										".(new \DateTime($objTransactionMastser->transactionOn))->format("Y")."
									</td>
									<td> 
									</td>
								</tr>
							</table>
						</td>
						<td style='text-align:center;width:33%; vertical-align:top' >
							<h1 style='margin:0px;color:black' >RECIBO DE INGRESO</h1>
						</td>
						<td style='text-align:right;width:33%; vertical-align:bottom' >
							<h2 style='margin:0px;' >[[TIPO_DOCUMENTO]]</h2>
							<h2 style='margin:0px;color:red' >". $numberDocument ."</h2>
							<h2 style='margin:0px' >POR C$: ".number_format($objTransactionMastser->amount,2,'.',',')."</h2>
						</td>
					</tr>
				</table>
				
				</br>
			";
			
		   

		   
	$f_html = $f_html."
				
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
				   </table>
				   ";
				   
				   
	
		   
	$f_html = $f_html."
				
			";
				
	$f_html = $f_html."
				</br>
				<table style='width:98%' >
					<tr>
						<td style='text-align:center' >
							<h1 style='margin:0px' >\"Educamos Para Redimir\"</h1>
							<h2 style='margin:0px' >No se aceptan devoluciones</h2>
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
						<td style='text-align:center' >________________________</td>
						<td style='text-align:center' >________________________</td>						
					</tr>							
					<tr>
						<td style='text-align:center' >Entregue conforme</td>
						<td style='text-align:center' >Recibí conforme</td>						
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
		{
			$f_html				= $f_html_original."<div style='page-break-before:always;' ></div>".$f_html_copia."<div style='page-break-before:always;' ></div>".$f_html_credito;
		}
		else 
		{
			//$f_html				= $f_html_original."<div style='page-break-before:always;' ></div>".$f_html_copia;
			$f_html				= $f_html_original."</br></br></br></br></br>".$f_html_copia;
		}
	
	}
	$html 				= $html.$f_html."</body></html>";	
	
	
	return $html;
}

function helper_reporteA4TransactionMasterInvoiceA4Opcion1(
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
	$objListComanyParameter = "" /*parametros*/
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
                          size: A4;   
						
						  
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
                            <img  src='".$base64."' width='150'  >
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
											".helper_getParameterFiltered($objListComanyParameter,"CORE_PROPIETARY_ADDRESS")->value."
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											RUC: ".helper_getParameterFiltered($objListComanyParameter,"CORE_COMPANY_IDENTIFIER")->value."
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											Teléfono: ".helper_getParameterFiltered($objListComanyParameter,"WHATSAP_CURRENT_PROPIETARY_COMMERSE")->value."
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											".getBehavio($objCompany->type,"web_tools_report_helper","reporteA4TransactionMasterInvoiceA4Opcion1AutorizationDgi","AUT.DGI. No. ASFC 02/0009/02/2023/2")."
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
	
	for($i = 0 ; $i < 150 ; $i++)
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
					<td style='text-align:right;width:70px' >".$objCurrency->simbol." ".number_format(round($objDetail[$i]->unitaryPrice,2),2,".",",")."</td>
					<td style='text-align:right;width:70px' >".number_format(round($objDetail[$i]->quantity,2),2,".",",")."</td>
					<td style='text-align:right;width:70px' >".$objCurrency->simbol." 0.00</td>
					<td style='text-align:right;width:70px' >".$objCurrency->simbol." ".number_format(round($objDetail[$i]->unitaryPrice,2)  * round($objDetail[$i]->quantity,2)   ,2,".",",")."</td>
				<tr>
			";
		}
		//-wgonzalez-else
		//-wgonzalez-{
		//-wgonzalez-	$f_html = $f_html."			
		//-wgonzalez-		<tr>
		//-wgonzalez-			<td style='text-align:center;width:70px' >&nbsp;</td>
		//-wgonzalez-			<td style='text-align:center;' >&nbsp;</td>
		//-wgonzalez-			<td style='text-align:center;width:70px' >&nbsp;</td>
		//-wgonzalez-			<td style='text-align:center;width:70px' >&nbsp;</td>
		//-wgonzalez-			<td style='text-align:center;width:70px' >&nbsp;</td>
		//-wgonzalez-			<td style='text-align:center;width:70px' >&nbsp;</td>
		//-wgonzalez-		<tr>
		//-wgonzalez-	";
		//-wgonzalez-}
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
		$f_html				= $f_html_original."<div style='page-break-before:always;' ></div>".$f_html_copia."<div style='page-break-before:always;' ></div>".$f_html_credito;
		else 
		$f_html				= $f_html_original."<div style='page-break-before:always;' ></div>".$f_html_copia;
	
	}
	$html 				= $html.$f_html."</body></html>";	
	
	
       
    
    return $html;
}

function helper_reporteA4TransactionMasterInvoiceTitan(
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
											Plaza 101
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											RUC: 000000000
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
	
	for($i = 0 ; $i < 45 ; $i++)
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
								Gracias por su compra
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
											".getBehavio($objCompany->type,"app_invoice_billing","lblRptInvoiceAddress","Carretera Masaya, Frente al colegio Teresiano")."
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>
											".getBehavio($objCompany->type,"app_invoice_billing","lblRptInvoiceRuc","Edificio Delta RUC: 888-080396-0001K")."
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
								".getBehavio($objCompany->type,"app_invoice_billing","lblRptInvoiceOpcion2","Al comprar tu equipo en GLOBAL PRO,puedes cambiarlo por uno superior cuando gustes!")."
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
											".getBehavio($objCompany->type,"app_invoice_billing","lblRptInvoiceAddress","Carretera Masaya, Frente al colegio Teresiano")."
										  </td>
										</tr>
										<tr>
										  <td style='text-align:center'>											
											".getBehavio($objCompany->type,"app_invoice_billing","lblRptInvoiceRuc","Edificio Delta RUC: 888-080396-0001K")."
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
										<td style='height:60px;text-justify: auto;  '>". substr($objTransactionMastser->reference1,0,450) ."</td>
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
										<td colspan='2'>*=".getBehavio($objCompany->type,"app_invoice_billing","lblRptInvoiceOpcion1","Equipo seminuevo")."</td>
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
								".getBehavio($objCompany->type,"app_invoice_billing","lblRptInvoiceOpcion2","Al comprar tu equipo en GLOBAL PRO,puedes cambiarlo por uno superior cuando gustes!")."
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
  $causalName = "",
  $datView = ""
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
								  ". $datView["objBranch"]->address."
								</td>
							  </tr>
							  <tr>
								<td style='text-align:center'>
								".getBehavio($objCompany->type,"app_purchase_taller","lblReportEntradaRptPhone","Servicio Tecnico: 5863-7406") ."
								</td>
							  </tr>
							  <tr>
								<td style='text-align:center'>
								&nbsp;
								</td>
							  </tr>
							  <tr>
								<td style='text-align:center'>
								".getBehavio($objCompany->type,"app_purchase_taller","lblReportEntradaRptRed","Siguenos! Global Pro Nicaragua") ."
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
			".getBehavio($objCompany->type,"app_purchase_taller","lblReportEntradaRptCondicion1","GLOBAL PRO NO se hace reponsable de pérdidas de informacion, por lo cual solicitamos antes
			  hacer un respaldo a Nuestros clientes.") ."
            </td>
          </tr>
		  <tr>
            <td style='text-align:justify;font-weight: bold;font-size:12px'>			  
			  ".getBehavio($objCompany->type,"app_purchase_taller","lblReportEntradaRptCondicion2","El Costo del diagnóstico es de C$ 450.00 si decide realizar la reparacion, NO pagara diagnostico.
			  Despues de 30 dias de notificar que el equipo está listo para ser retirado, se cobrara $1 diario por 
			  concepto de Almacenamiento, despues de 6 meses se procedera a liquidar el equipo.
			  Toda reparación de GLOBAL PRO, cuenta con 3 meses de garantia") ."
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

function helper_reporteA4mmTransactionMasterTallerCompuMatt(
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
								  ".getBehavio($objCompany->type,"app_purchase_taller","lblReportEntradaRptDireccion","Carretera Masaya, Frente al colegio Teresiano") ."
								</td>
							  </tr>
							  <tr>
								<td style='text-align:center'>
								".getBehavio($objCompany->type,"app_purchase_taller","lblReportEntradaRptPhone","Servicio Tecnico: 5863-7406") ."
								</td>
							  </tr>
							  <tr>
								<td style='text-align:center'>
								&nbsp;
								</td>
							  </tr>
							  <tr>
								<td style='text-align:center'>
								".getBehavio($objCompany->type,"app_purchase_taller","lblReportEntradaRptRed","Siguenos! Global Pro Nicaragua") ."
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
	

if($objData["objTMD"])
{
$f_html = $f_html."
        <table style='width:98%;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom."' >";
		
		  foreach($objData["objTMD"] as $itemTMD)
		  {
				$f_html = $f_html."<tr>
					<td  style='width:20px;text-align:left;vertical-align:top;' >
						Caracteristicas adicionales:
					</td>
					<td style='width:180px;vertical-align:top;' >	
						".$itemTMD->reference1."
					</td>  
				  </tr>";		  
		  }
		  
$f_html = $f_html."
		</table>";
         
$f_html = $f_html."
      <table style='width:98%' >
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    ";
}
	
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
			".getBehavio($objCompany->type,"app_purchase_taller","lblReportEntradaRptCondicion1","GLOBAL PRO NO se hace reponsable de pérdidas de informacion, por lo cual solicitamos antes
			  hacer un respaldo a Nuestros clientes.") ."
            </td>
          </tr>
		  <tr>
            <td style='text-align:justify;font-weight: bold;font-size:12px'>			  
			  ".getBehavio($objCompany->type,"app_purchase_taller","lblReportEntradaRptCondicion2","El Costo del diagnóstico es de C$ 450.00 si decide realizar la reparacion, NO pagara diagnostico.
			  Despues de 30 dias de notificar que el equipo está listo para ser retirado, se cobrara $1 diario por 
			  concepto de Almacenamiento, despues de 6 meses se procedera a liquidar el equipo.
			  Toda reparación de GLOBAL PRO, cuenta con 3 meses de garantia") ."
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
  $causalName = "",
  $datView=""
  
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
								".$datView["objBranch"]->address."
								</td>
							  </tr>
							  <tr>
								<td style='text-align:center'>
								".getBehavio($objCompany->type,"app_purchase_taller","lblReportEntradaRptPhone","Servicio Tecnico: 5863-7406")."
								</td>
							  </tr>
							  <tr>
								<td style='text-align:center'>
								&nbsp;
								</td>
							  </tr>
							  <tr>
								<td style='text-align:center'>
								".getBehavio($objCompany->type,"app_purchase_taller","lblReportEntradaRptRed","Siguenos! Global Pro Nicaragua")."
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
			".getBehavio($objCompany->type,"app_purchase_taller","lblReportEntradaRptCondicion1","GLOBAL PRO NO se hace reponsable de pérdidas de informacion, por lo cual solicitamos antes
			  hacer un respaldo a Nuestros clientes.")."		  
            </td>
          </tr>
		  <tr>
            <td style='text-align:justify;font-weight: bold;font-size:12px'>
			".getBehavio($objCompany->type,"app_purchase_taller","lblReportEntradaRptCondicion2","El Costo del diagnóstico es de C$ 450.00 si decide realizar la reparacion, NO pagara diagnostico.
			  Despues de 30 dias de notificar que el equipo está listo para ser retirado, se cobrara $1 diario por 
			  concepto de Almacenamiento, despues de 6 meses se procedera a liquidar el equipo.
			  Toda reparación de GLOBAL PRO, cuenta con 3 meses de garantia")."
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
								".$objCompany->name."
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


function helper_reporteItemStickerSinPrecio(
  $titulo,
  $objCompany,
  $dataView
)
{
	
  
	$font_size1   	= "18px";
	$border_left 	= "border-left: 1px solid black;";
	$border_right 	= "border-right: 1px solid black;";
	$border_top 	= "border-top: 1px solid black;";
	$border_bottom 	= "border-bottom: 1px solid black;";
	$border_radius	= "border-radius: 10px;";
	$border_colapse = "border-collapse:separate;";


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
								".$objCompany->name."
							</td>
						  </tr>
						  <tr>
							<td style='text-align:center'>
								[[NOMBRE]]
							</td>
						  </tr>
						  <tr>
							<td style='text-align:center'>
								".$dataView["objListaItem"][0]->name."
							</td>
						  </tr>
						  <tr>
							<td style='text-align:center'>
							</td>
						  </tr>
					</table>
        
          ";
		   

  $html 				= $html.$f_html."</body></html>";	  
  return $html;
}

function helper_reporteItemStickerConPrecio(
  $titulo,
  $objCompany,
  $dataView
)
{
	
  
	$font_size1   	= "18px";
	$border_left 	= "border-left: 1px solid black;";
	$border_right 	= "border-right: 1px solid black;";
	$border_top 	= "border-top: 1px solid black;";
	$border_bottom 	= "border-bottom: 1px solid black;";
	$border_radius	= "border-radius: 10px;";
	$border_colapse = "border-collapse:separate;";


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
								".$objCompany->name."
							</td>
						  </tr>
						  <tr>
							<td style='text-align:center'>
								[[NOMBRE]]
							</td>
						  </tr>
						  <tr>
							<td style='text-align:center'>
								".$dataView["objListaItem"][0]->name."
							</td>
						  </tr>
						  <tr>
							<td style='text-align:center'>
								".round($dataView["objListaItem"][0]->itemPrice,2)."
							</td>
						  </tr>
					</table>
        
          ";
		   

  $html 				= $html.$f_html."</body></html>";	  
  return $html;
}


function helper_reporteA4mmTransactionMasterInventoryOtherOutputStickerGlobalPro(
  $titulo,
  $objCompany,
  $objParameterLogo,
  $objTransactionMastser,
  $objWorkflowStatusName,
  $objTransactionMasterDetail,    	
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
  $numberDocument = str_replace("IAT"," No ", $objTransactionMastser->transactionNumber);
  $tipoDocumento  = "STICKER";

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
                      table {
                        font-size: xx-small;
                        font-family: sans-serif, monaco, monospace;						 
						border-collapse: collapse;
                      }
					  td {
                        font-size: xx-small;
                        font-family: sans-serif, monaco, monospace;						
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
								". $numberDocument ."
							</td>
						  </tr>
						  <tr>
							<td style='text-align:center'>
								".$objWarehouse->name."
							</td>
						  </tr>
						  <tr>
							<td style='text-align:center'>
								".substr($objTransactionMastser->note,0,40)."
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
								".$objCompany->name." 
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
										<td style='height:60px;text-justify: auto;  '>". substr($objTransactionMastser->reference1,0,450) ."</td>
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

function helper_reporteA4mmJournalEntry(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objViewState,
    $objParameterTelefono
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
    $numberDocument = str_replace("ESP","COMPROBANTE No ", $objViewState["objJournalEntry"]->journalNumber);
	$tipoDocumento  = "COMPROBANTE";
	$telefono  		= "";
	$currencyName	= $objViewState["objJournalEntry"]->currencySimbol;
	
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
											COMPROBANTE NO ". $numberDocument ."
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
				<tr style='background-color: #ccc;'>
					<td style='text-align:center;width:70px; font-weight: bold;"."' >Cuenta</td>
					<td style='text-align:center; font-weight: bold;"."' >Descripcion</td>
					<td style='text-align:right;width:120px; font-weight: bold;"."' >Credito</td>
					<td style='text-align:right;width:120px; font-weight: bold;"."' >Debito</td>
				<tr>
			";
		
	$credit 	= 0;
	$debit 		= 0;
	$objDetail	= $objViewState["objJournalEntryDetail"];
	
	for($i = 0 ; $i < 22 ; $i++)
	{
		$count = count($objDetail);
		if($i < $count)
		{
			$credit 	+= $objDetail[$i]->credit; 
			$debit 		+= $objDetail[$i]->debit; 
			
			$f_html = $f_html."			
				<tr>
					<td style='text-align:left;width:70px' >".$objDetail[$i]->accountNumber."</td>
					<td style='text-align:left;' >".$objDetail[$i]->accountName."</td>
					<td style='text-align:right;width:120px' >".$currencyName." ".number_format(round($objDetail[$i]->credit,2),2,".",",")."</td>
					<td style='text-align:right;width:120px' >".$currencyName." ".number_format(round($objDetail[$i]->debit,2),2,".",",")."</td>
					
				<tr>
			";
		}
		else
		{
			$f_html = $f_html."			
				<tr>
					<td style='text-align:center;width:70px' >&nbsp;</td>
					<td style='text-align:center;' >&nbsp;</td>
					<td style='text-align:right;width:120px' >&nbsp;</td>
					<td style='text-align:right;width:120px' >&nbsp;</td>
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
										<td style='height:60px;text-justify: auto;  '>". "<!--nota del comprobante-->" ."</td>
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
										<td style=' font-weight: bold;'>CREDITO:</td>
										<td style='text-align:right'>".$currencyName." ".number_format ( round($credit ,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>
										<td style=' font-weight: bold;'>DEBITO:</td>
										<td style='text-align:right'>".$currencyName." ".number_format ( round($debit ,2) , 2 , ".","," ) ."</td>
									</tr>
									<tr>										
										<td></td>
										<td></td>
									</tr>	
									<tr>
										<td colspan='2'>***********</td>
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
								Soporte fisico contable GLOBAL PRO
							</td>
						</tr>
				   </table>";
	
	
	$f_html_copia 		= $f_html;
	$f_html_original 	= $f_html;
	$f_html_credito		= $f_html;
	
	$f_html_original 	= str_replace("[[TIPO_DOCUMENTO]]","ORIGINAL",$f_html_original);
	$f_html_copia 		= str_replace("[[TIPO_DOCUMENTO]]","COPIA",$f_html_copia);
	$f_html_credito 	= str_replace("[[TIPO_DOCUMENTO]]","COPIA",$f_html_copia);
	
	
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
                          margin-left:10px;
                          margin-right:0px;
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
                            SUB-TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->subAmount)."
                          </td>
                        </tr>  
						<tr>
                          <td colspan='2'>
                            IVA
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->tax1)."
                          </td>
                        </tr> 
                        <tr>
                          <td colspan='2'>
                            DESC
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->discount)."
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
						
						".getBehavio($objCompany->type,"web_tools_report_helper","reporte80mmTransactionMaster_Devolucion","")."

                        <tr>
                          <td colspan='3' style='text-align:center' >
                            posMe PRO Premium 3.1
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

function helper_reporte80mmTransactionMasterRepuestoCristoRey(
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
                          margin-left:10px;
                          margin-right:0px;
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
                            <img  src='".$base64."' width='140' height='130'  >
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
    
          $html = $html."
		  
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
                          <td colspan='3' style='text-align:center'>							
                            &nbsp;
                          </td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:center'>
                            COMPROBANTE NO:
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
                          </td>
                        </tr>
                                
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            FECHA: ". date("d/m/Y h:i:s", strtotime($objTransactionMastser->createdOn)) ."
                          </td>
                        </tr>
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
							________________________________
                            &nbsp;
                          </td>
                        </tr>";
						
          if($userNickName != "")
		  {      
			$html	= $html."<tr>
                          <td colspan=''>
                            VENDEDOR:
                          </td>
						  <td colspan='2'>
                            ". strtoupper((strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) ))   ."
                          </td>
                        </tr>";
		  }
						
          $html	= $html."<tr>
                          <td colspan=''>
                            CODIGO:
                          </td>
						  <td colspan='2'>
                            ".strtoupper($objEntidadCustomer->customerNumber)."
                          </td>
                        </tr>";
			
			
		  if($causalName != ""){
			$html	= $html."<tr>
							<td colspan='1'>
								TIPO:
							</td>
							<td colspan='2'>
								".strtoupper($causalName)."
							</td>
						</tr>";
		  }
			
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            ESTADO
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
                          </td>
                        </tr>
                            
						<tr>
                          <td colspan='1'>
                            MONEDA:
                          </td>
						  <td colspan='2'>
                            ".$objCurrency->simbol."
                          </td>
                        </tr>
						<tr>
                          <td colspan='1'>
                            T/C:
                          </td>
						  <td colspan='2'>
                            ".round(1/$objTransactionMastser->exchangeRate,2)."
                          </td>
                        </tr>
						";
			
		
						
          $html	= $html."
						<tr>
						  <td colspan='1'>
							CLIENTE:
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
                          <td colspan='3' style='text-align:left'>".strtoupper($objTransactionMastser->note)."</td>
                        </tr>
						-->
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
						    ________________________________
                            &nbsp;
                          </td>
                        </tr>
                                
                     
                                
                         [[DETALLE]]
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
							________________________________
                            &nbsp;
                          </td>
                        </tr>
						
						<!--
                        <tr>
                          <td colspan='2'>
                            SUB-TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->subAmount)."
                          </td>
                        </tr>  
						<tr>
                          <td colspan='2'>
                            IVA
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->tax1)."
                          </td>
                        </tr> 
                        <tr>
                          <td colspan='2'>
                            DESC
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->discount)."
                          </td>
                        </tr> 
						-->						
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
                            TOTAL DOL
                          </td>
                          <td style='text-align:right'>
                            "."$ "." ".sprintf("%.2f",$objTransactionMastser->amount*$objTransactionMastser->exchangeRate)."
                          </td>
                        </tr>
   
						<!--
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
						-->
						
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
                            &nbsp;
                          </td>
                        </tr>
						 <tr>
                          <td colspan='3' style='text-align:center' >
                            _________________
                          </td>
                        </tr>
						
                        <tr>
                          <td colspan='3' style='text-align:center' >
                            RECIBIDO CONFORME 
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
                          <td colspan='3' style='text-align:center' >
                            COD NO:  _________________
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
                          <td colspan='3' style='text-align:center' >
                            REVISE SU MERCADERIA ANTES DE SALIR. NO ACEPTAMOS DEVOLUCIONES
                          </td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:center' >
                            GRACIAS POR SU COMPRA!
                          </td>
                        </tr>
						
						
						
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

                       
						
						".getBehavio($objCompany->type,"web_tools_report_helper","reporte80mmTransactionMaster_Devolucion","")."

                        <tr>
                          <td colspan='3' style='text-align:center' >
                            POSME PRO PREMIUM 3.1
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

function helper_reporte80mmTransactionMasterEmanuel(
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
    $rucCompany = "" /*ruc*/ ,
	$dataView = "" 
)
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-logo-micro-finanzaV2.jpg';
    
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
                          margin-left:25px;
                          margin-right:0px;
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
    
          $html = $html."
		  
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
			$html	= $html."
						<!--
						<tr>
                          <td colspan=''>
                            Vendedor:
                          </td>
						  <td colspan='2'>
                            ". (strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )   ."
                          </td>
                        </tr>
						-->";
		  }
						
          $html	= $html."
						<!--
						<tr>
                          <td colspan=''>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>
						-->
						";
			
			
		  if($causalName != ""){
			$html	= $html."<tr>
							<!--
							<td colspan='1'>
								Tipo:
							</td>
							<td colspan='2'>
								".$causalName."
							</td>
							-->
							
							<td colspan='1'>
								Pago:
							</td>
							<td colspan='2'>
								". ($objTransactionMasterInfo->receiptAmountCard > 0 ? "TARJETA" : "EFECTIVO" ) ."
							</td>
							
						</tr>";
		  }
			
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            Estado
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "APLICADA" ? "CANCELADA" : $statusName ) ."
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
							RUC:
                          <td colspan='2'>
							". ( $objTransactionMasterInfo->referenceClientIdentifier == "" ?   "" :  $objTransactionMasterInfo->referenceClientIdentifier )  ." 
                          </td>
                        </tr>
						
						<tr>
						  <td colspan='1'>
							Cliente:
                          <td colspan='2'>
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
						
						<!--
						<tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
						-->
                                
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
                            SUB-TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->subAmount)."
                          </td>
                        </tr>  
						<tr>
                          <td colspan='2'>
                            IVA
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->tax1)."
                          </td>
                        </tr> 
                        <tr>
                          <td colspan='2'>
                            DESC
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->discount)."
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
                            Gracias por su compra!!
                          </td>
                        </tr>

						
						".getBehavio($objCompany->type,"web_tools_report_helper","reporte80mmTransactionMaster_Devolucion","")."

                        <tr>
                          <td colspan='3' style='text-align:center' >
                            posMe PRO Premium 3.1
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

function helper_reporte80mmTransactionMasterRegistrada(
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
    $rucCompany = "" /*ruc*/ , 
	$dataView = "" 
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
                          margin-left:25px;
                          margin-right:0px;
                        }
                        table{
                          font-size: medium; /*x-small; small; medium ;  large ; x-large; xx-large; */
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
                            MESA: ". ($dataView["objMesa"] ? $dataView["objMesa"]->name : "N/D") ."
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
                          <td colspan='3' style='text-align:center' >
                            &nbsp;
                          </td>
                        </tr>
                        <tr>
                          <td colspan='3' style='text-align:center' >
                            Esto no es una factura, solicite su factura al cancelar
                          </td>
                        </tr>
                        <tr>
                          <td colspan='3' style='text-align:center' >
                            posMe PRO Premium 3.1
                          </td>
                        </tr>
                        <tr>
                          <td colspan='3' style='text-align:center' >
                            &nbsp;
                          </td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:center' >
                            &nbsp;
                          </td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:center' >
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

                if( $confiDetalle[$colun]["nueva_fila_row_data"] ==  1 &&  strpos($key, "-comand-new-row") !== false  )
                {
                    $cuerpo 		= $cuerpo."<tr><td>&nbsp;</td></tr><tr >";

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

function helper_reporte80mmEventosCalendario(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $evento,
    $objEntidadNatural,
    $objEntidadCustomer,
    $objParameterTelefono, /*telefono*/
    $rucCompany = "" /*ruc*/ ,
): string
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;

    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    // Crear un objeto DateTime a partir de la cadena
    $createdOn = new DateTime($evento->createdOn);
    $fecha = $createdOn->format('Y-m-d');
    $hora = $createdOn->format('H:i:s');
    $html    = "";
    $html    = "
                    <!DOCTYPE html>
                    <html lang='en'>
                    <title>$titulo</title>
                    <head>
                        <meta charset='UTF-8' />
                        <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                        <style>
                        @page {       
                            size: 3.15in 7in;                  
                            margin-top:0px;
                            margin-left:5px;
                            margin-right:5px;
                        }
                        table{
                            font-size: small; /*x-small; small; medium ;  large ; x-large; xx-large; */
                            font-weight: bold;
                            font-family: Consolas, monaco, monospace;
                        }
                        th, td {
                            text-align: left;
                        }
                        th {
                            background-color: #f2f2f2;
                        }
                        .firma {
                            text-align: center;
                            padding-top: 20px;
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
                            ".strtoupper($objCompany->address)."
                            </td>
                        </tr>  
                        <tr>                              
    						  <td colspan='3' style='text-align:center'>
                                RUC : ".  $rucCompany ."
                              </td>
                            </tr>                
                        <tr>
                            <td colspan='3' style='text-align:center'>
                            # ".strtoupper($evento->rememberID)."
                            </td>
                        </tr>
                                
                        <tr style='text-align:center'>
                            <td>FECHA</td>
                            <td>".$fecha."</td>
                        </tr>
                        <tr style='text-align:center'>
                            <td>HORA</td>
                            <td>".$hora."</td>
                        </tr>
                        <tr style='text-align:center'>
                            <td>ESTADO</td>
                            <td>REGISTRADA</td>
                        </tr>
                        <tr style='text-align:center'>
                            <td colspan='3' style='text-align: center'>TITULO</td>
                        </tr>
                        <tr style='text-align:center'>
                            <td colspan='3' style='text-align: left'>$evento->title</td>
                        </tr>
                        <tr style='text-align:center'>
                            <td colspan='3' style='text-align: center'>DESCRIPCION</td>
                        </tr>
                        <tr style='text-align:center'>
                            <td colspan='2' style='text-align: left'>$evento->description</td>
                        </tr>
                        <tr style='text-align:center'>
                            <td>ENTIDAD</td>
                            <td></td>
                        </tr>
                        <tr style='text-align:center'>
                            <td>NOMBRE</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan='3' style='text-align:center'>
                                <div class='firma'>
                                    <p>________________________</p>
                                    <p>FIRMA</p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan='3' style='text-align:center' >
                            posMe PRO Premium 3.1
                            </td>
                        </tr>
                        </table>
                    </body>
                                
                    </html>
            ";


    return $html;
}

function helper_reporte80mmTransactionMasterEmanuelPizza(
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
    $rucCompany = "" /*ruc*/ ,
	$dataView = "" 
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
                          margin-left:25px;
                          margin-right:0px;
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
    
          $html = $html."
		  
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
			$html	= $html."
						<!--
						<tr>
                          <td colspan=''>
                            Vendedor:
                          </td>
						  <td colspan='2'>
                            ". (strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )   ."
                          </td>
                        </tr>
						-->";
		  }
						
          $html	= $html."
						<!--
						<tr>
                          <td colspan=''>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>
						-->
						";
			
			
		  if($causalName != ""){
			$html	= $html."<tr>
							<!--
							<td colspan='1'>
								Tipo:
							</td>
							<td colspan='2'>
								".$causalName."
							</td>
							-->
							
							<td colspan='1'>
								Pago:
							</td>
							<td colspan='2'>
								". ($objTransactionMasterInfo->receiptAmountCard > 0 ? "TARJETA" : "EFECTIVO" ) ."
							</td>
							
						</tr>";
		  }
			
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            Estado
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "APLICADA" ? "CANCELADA" : $statusName ) ."
                          </td>
                        </tr>
						
						
                        <tr>
                          <td colspan='1'>
                            Mesa:
                          </td>
						  <td colspan='2'>
                            ".$dataView["objMesa"]->name."
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
						
						<!--
						<tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
						-->
                                
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
                            SUB-TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->subAmount)."
                          </td>
                        </tr>  
						<tr>
                          <td colspan='2'>
                            IVA
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->tax1)."
                          </td>
                        </tr> 
                        <tr>
                          <td colspan='2'>
                            DESC
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->discount)."
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

						<!--
						<tr>
                          <td colspan='3' style='text-align:center'>
                            Dirección de cliente: ".$dataView["objLegal"]->legalName." Telefono de cliente: ".$objEntidadCustomer->phoneNumber."
                          </td>
                        </tr>
						-->
						
						<tr>
                          <td colspan='3' style='text-align:center'>
                            Dirección de cliente: ".$objTransactionMasterInfo->referenceClientIdentifier." Telefono de cliente: ".$objTransactionMastser->numberPhone."
                          </td>
                        </tr>
						
						<tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
						
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            Gracias por su compra!!
                          </td>
                        </tr>

						
						".getBehavio($objCompany->type,"web_tools_report_helper","reporte80mmTransactionMaster_Devolucion","")."

                        <tr>
                          <td colspan='3' style='text-align:center' >
                            posMe PRO Premium 3.1
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


function helper_reporte80mmTransactionMasterTenampa(
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
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-logo-micro-finanza.jpg';
    
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
                            cantina - bar - restaurante
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
                            SUB-TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->subAmount)."
                          </td>
                        </tr>  
						<tr>
                          <td colspan='2'>
                            IVA
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->tax1)."
                          </td>
                        </tr> 
                        <tr>
                          <td colspan='2'>
                            DESC
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->discount)."
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
						
						".getBehavio($objCompany->type,"web_tools_report_helper","reporte80mmTransactionMaster_Devolucion","")."

                        <tr>
                          <td colspan='3' style='text-align:center' >
                            posMe PRO Premium 3.1
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


function helper_reporte80mmTransactionMasterColirio(
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
    $objParameterEmail, /*email*/
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
                            <div style='font-size:12pt'>".strtoupper($objCompany->namePublic)."</div>
                          </td>
                        </tr>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <div style='font-size:9pt'>".strtoupper($objCompany->name)."</div>
                          </td>
                        </tr>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <div style='font-size:7pt'>PARA GENTE IMPORTANTE, PARA GENTE COMO USTED</div>
                          </td>
                        </tr>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='110'  alt='Logo'/>
                          </td>
                        </tr>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            Dirección: ".$objCompany->address."
                          </td>
                        </tr>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            EMAIL: ".$objParameterEmail->value."
                          </td>
                        </tr>
                        <tr>                              
                          <td colspan='3' style='text-align:center'>
                            No RUC: ".  $rucCompany ."
                          </td>
                        </tr>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            Teléfono: ".$objParameterTelefono->value."
                          </td>
                        </tr>";


    if($userNickName != "")
    {
        $html	= $html."";
    }

    $html = $html."
                        <tr><td>&nbsp;</td></tr>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                        <tr>
                            <td colspan='3' style='text-align:center'>
                                <hr style='width:100%;' />
                            </td>
                        </tr>
                        <tr>
                          <td colspan='3' style='text-align:center'>                           
                            <div style='display:flex; justify-content: space-between;width: 100%; font-size: 8pt'>
                                <span style='text-align: left;'>FECHA: ".date('d/m/Y', strtotime($objTransactionMastser->createdOn))."</span>
                                <span style='text-align: right;'>HORA: ".date('h:i:s A', strtotime($objTransactionMastser->createdOn))."</span>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                           Comp. # <span style='font-size:9pt'>".strtoupper($objTransactionMastser->transactionNumber)."</span>
                          </td>
                        </tr>
                        <tr>
                            <td colspan='3' style='text-align:center'>
                                <hr style='width:100%;' />
                            </td>
                        </tr>
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";


    $html	= $html."<tr>
                          <td colspan='1'>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>
                        <tr>
						  <td colspan='1'>
							Cliente:
                          </td>
						  <td colspan='2'>							
                            ". ( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName." ".$objEntidadNatural->lastName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>";


    $html	= $html."<tr>
                          <td colspan='1'>
                            Cedula
                          </td>
						  <td colspan='2'>
                            ". $objEntidadCustomer->identification ."
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
						

						
						<!--
                        <tr>
                          <td colspan='3'>
                            Tipo de Cambio: ".$tipoCambio."
                          </td>
                        </tr>
						--> 
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
                          <td colspan='2'>
                            RECIBIDO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount + $objTransactionMasterInfo->changeAmount)."
                          </td>
                        </tr>

                        <tr>
                            <td colspan='3' style='text-align:center'>
                                <hr style='width:100%;' />
                            </td>
                        </tr>
                       <tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr>
                            <td colspan='3' style='text-align:center'>
                                <hr style='width: 75%'>
                                <span>Entregue Conforme<br>". $objEntidadCustomer->identification ."</span>
                                <hr style='width: 75%'>
                                <span>Número de Cedula</span>
                                <br>
                                <span>".(strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )."</span>
                                <hr style='width: 75%'>
                                <span>Responsable de Caja</span>
                            </td>
                        </tr>                                
                      </table>
                    </body>
                                
                    </html>
            ";

    return $html;
}

function helper_reporte80mmTransactionMasterInputOutPutCashColirio(
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
    $objParameterEmail, /*email*/
    $statusName = "", /*estado*/
    $causalName = "", /*causal*/
    $userNickName = "", /*vendedor*/
    $rucCompany = "" /*ruc*/
): string
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
                            <div style='font-size:12pt'>".strtoupper($objCompany->namePublic)."</div>
                          </td>
                        </tr>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <div style='font-size:9pt'>".strtoupper($objCompany->name)."</div>
                          </td>
                        </tr>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <div style='font-size:7pt'>PARA GENTE IMPORTANTE, PARA GENTE COMO USTED</div>
                          </td>
                        </tr>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            <img  src='".$base64."' width='110'  alt='Logo'/>
                          </td>
                        </tr>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            Dirección: ".$objCompany->address."
                          </td>
                        </tr>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            EMAIL: ".$objParameterEmail->value."
                          </td>
                        </tr>
                        <tr>                              
                          <td colspan='3' style='text-align:center'>
                            No RUC: ".  $rucCompany ."
                          </td>
                        </tr>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            Teléfono: ".$objParameterTelefono->value."
                          </td>
                        </tr>";


    $html = $html."
                        <tr><td>&nbsp;</td></tr>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                        <tr>
                            <td colspan='3' style='text-align:center'>
                                <hr style='width:100%;' />
                            </td>
                        </tr>
                        <tr>
                          <td colspan='3' style='text-align:center'>                           
                            <div style='display:flex; justify-content: space-between;width: 100%; font-size: 8pt'>
                                <span style='text-align: left;'>FECHA: ".date('d/m/Y', strtotime($objTransactionMastser->createdOn))."</span>
                                <span style='text-align: right;'>HORA: ".date('h:i:s A', strtotime($objTransactionMastser->createdOn))."</span>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td colspan='3' style='text-align:center'>
                           Comp. # <span style='font-size:9pt'>".strtoupper($objTransactionMastser->transactionNumber)."</span>
                          </td>
                        </tr>
                        <tr>
                            <td colspan='3' style='text-align:center'>
                                <hr style='width:100%;' />
                            </td>
                        </tr>
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";



    $html	= $html."                            
						<tr>
                          <td colspan='1'>
                            Moneda:
                          </td>
						  <td colspan='2'>
                            ".$objCurrency->simbol."
                          </td>
                        </tr>";



    $html	= $html."
						

						
						<!--
                        <tr>
                          <td colspan='3'>
                            Tipo de Cambio: ".$tipoCambio."
                          </td>
                        </tr>
						--> 
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
                          <td colspan='2'>
                            RECIBIDO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount)."
                          </td>
                        </tr>

                        <tr>
                            <td colspan='3' style='text-align:center'>
                                <hr style='width:100%;' />
                            </td>
                        </tr>
                       <tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr>
                            <td colspan='3' style='text-align:center'>
                                <hr style='width: 75%'>
                                <span>Entregue Conforme<br></span>
                                <hr style='width: 75%'>
                                <span>Número de Cedula</span>
                                <br>
                                <span>".(strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )."</span>
                                <hr style='width: 75%'>
                                <span>Responsable de Caja</span>
                            </td>
                        </tr>                                
                      </table>
                    </body>
                                
                    </html>
            ";

    return $html;
}

function helper_reporte80mmTransactionMasterViewDetailCredit(
    $titulo,
    $objCompany,
    $objParameterLogo,    
    $detalle, /**/
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
    

         
    
          $html = $html."<tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                                
                     
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";
						
        
						
          $html	= $html."<tr>
                          <td colspan=''>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$detalle[0]->customerNumber."
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
                            ". $detalle[0]->firstName  ."
                          </td>
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
                            [[TOTAL]]
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
                            posMe +(505) 8712-5827
                          </td>
                        </tr>
						
						
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
	$rowin  = 0;
	$total  = 0;
    foreach($detalle as $row){
		
          
			$cuerpo = $cuerpo."<tr>";	
			$cuerpo = $cuerpo."<td colspan='3'>";
			$cuerpo = $cuerpo.$row->createdOn; 
			$cuerpo = $cuerpo."</td>";		
			$cuerpo = $cuerpo."</tr>";
			
			$cuerpo = $cuerpo."<tr>";	
			$cuerpo = $cuerpo."<td colspan='3'>";
			$cuerpo = $cuerpo.$row->itemName; 
			$cuerpo = $cuerpo."</td>";		
			$cuerpo = $cuerpo."</tr>";
			
			
			
			$cuerpo = $cuerpo."<tr>";								 
									
			
			$cuerpo = $cuerpo."<td>";
			$cuerpo = $cuerpo.$row->quantity; 
			$cuerpo = $cuerpo."</td>";	

			$cuerpo = $cuerpo."<td>";
			$cuerpo = $cuerpo.$row->unitaryPrice; 
			$cuerpo = $cuerpo."</td>";			


			$cuerpo = $cuerpo."<td>";
			$cuerpo = $cuerpo.$row->amount; 
			$cuerpo = $cuerpo."</td>";				
						
			
			$cuerpo = $cuerpo."</tr>";			
			$total  = $total + ($row->quantity * $row->unitaryPrice);
		
            $cuerpo = $cuerpo."<tr>";	
			$cuerpo = $cuerpo."<td colspan='3'>";
			$cuerpo = $cuerpo.".............";
			$cuerpo = $cuerpo."</td>";		
			$cuerpo = $cuerpo."</tr>";
			
    }
    
	
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
	$html = str_replace("[[TOTAL]]", $total, $html);
    return $html;
}


function helper_reporte80mmTransactionMasterCancelWithShare(
    $titulo,
    $objCompany,
    $objParameterLogo,    
    $detalle, /**/
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
    

         
    
          $html = $html."<tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($titulo)."
                          </td>
                        </tr>
                                
                     
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>";
						
        
						
          $html	= $html."<tr>
                          <td colspan=''>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$detalle[0]->customerNumber."
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
                            ". $detalle[0]->firstName  ."
                          </td>
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
                            [[TOTAL]]
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
                            posMe +(505) 8712-5827
                          </td>
                        </tr>
						
						
                      </table>
                    </body>
                                
                    </html>
            ";
    
    $cuerpo = "";
    $colun  = 0;
	$rowin  = 0;
	$total  = 0;
    foreach($detalle as $row){
		
          
			$cuerpo = $cuerpo."<tr>";	
			$cuerpo = $cuerpo."<td colspan='3'>";
			$cuerpo = $cuerpo.$row->createdOn; 
			$cuerpo = $cuerpo."</td>";		
			$cuerpo = $cuerpo."</tr>";
			
			$cuerpo = $cuerpo."<tr>";	
			$cuerpo = $cuerpo."<td colspan='3'>";
			$cuerpo = $cuerpo.$row->itemName; 
			$cuerpo = $cuerpo."</td>";		
			$cuerpo = $cuerpo."</tr>";
			
			
			
			$cuerpo = $cuerpo."<tr>";								 
									
			
			$cuerpo = $cuerpo."<td>";
			$cuerpo = $cuerpo.$row->quantity; 
			$cuerpo = $cuerpo."</td>";	

			$cuerpo = $cuerpo."<td>";
			$cuerpo = $cuerpo.$row->unitaryPrice; 
			$cuerpo = $cuerpo."</td>";			


			$cuerpo = $cuerpo."<td>";
			$cuerpo = $cuerpo.$row->amount; 
			$cuerpo = $cuerpo."</td>";				
						
			
			$cuerpo = $cuerpo."</tr>";			
			$total  = $total + ($row->quantity * $row->unitaryPrice);
		
            $cuerpo = $cuerpo."<tr>";	
			$cuerpo = $cuerpo."<td colspan='3'>";
			$cuerpo = $cuerpo.".............";
			$cuerpo = $cuerpo."</td>";		
			$cuerpo = $cuerpo."</tr>";
			
    }
    
	
    
    $html = str_replace("[[DETALLE]]", $cuerpo, $html);
	$html = str_replace("[[TOTAL]]", $total, $html);
    return $html;
}


function helper_reporte80mmTransactionMasterLM(
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
                          size: 2.7in 10in;                  
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
                            SUB-TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->subAmount)."
                          </td>
                        </tr>  
						<tr>
                          <td colspan='2'>
                            IVA
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->tax1)."
                          </td>
                        </tr> 
                        <tr>
                          <td colspan='2'>
                            DESC
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->discount)."
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


function helper_reporte80mmTransactionMasterFarmaGael(
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
						<!--
						<tr>
                          <td colspan=''>
                            Codigo:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->customerNumber."
                          </td>
                        </tr>
						-->";
			
			
		  if($causalName != ""){
			$html	= $html."
						<!--
						<tr>
							<td colspan='1'>
								Tipo:
							</td>
							<td colspan='2'>
								".$causalName."
							</td>
						</tr>
						-->
						";
		  }
			
			
		  $html	= $html."<tr>
                          <td colspan='1'>
                            Estado
                          </td>
						  <td colspan='2'>
                            ". ($statusName == "CANCELADA" ? "APLICADA" : $statusName ) ."
                          </td>
                        </tr>
                         
						<!--
						<tr>
                          <td colspan='1'>
                            Moneda:
                          </td>
						  <td colspan='2'>
                            ".$objCurrency->simbol."
                          </td>
                        </tr>
						-->
						";
			
		
						
          $html	= $html."
						<!--
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
						-->
						
						<!--
                        <tr>
                          <td colspan='3'>
                            Tipo de Cambio: ".$tipoCambio."
                          </td>
                        </tr>
						-->
						
						<!--
						<tr>
                          <td colspan='3' style='text-align:left'>".$objTransactionMastser->note."</td>
                        </tr>
                         -->
						 
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
						
						<!--
                        <tr>
                          <td colspan='2'>
                            SUB-TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->subAmount)."
                          </td>
                        </tr>  
						<tr>
                          <td colspan='2'>
                            IVA
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->tax1)."
                          </td>
                        </tr> 
                        <tr>
                          <td colspan='2'>
                            DESC
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->discount)."
                          </td>
                        </tr>   
						-->
						
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

						<!--

                        <tr>
                          <td colspan='3' style='text-align:center' >
                            sistema 505-8712-5827
                          </td>
                        </tr>
                                
                        -->

                                
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
                            DESCUENTO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->discount)."
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
										<img src='data:image/jpeg;base64,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'
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
							<img style='width: 0.2cm;height: 0.2cm;' src='data:image/png;base64,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' />
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
							". strtolower( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>
						
						
						<!--
						<tr>
						  <td colspan='3'>							
                            ". strtolower( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>
						-->
						
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
	$size0	 = '30px';
    $size1	 = '14px';
	$size2	 = '16px';
	$size3	 = '12px';
	$size4	 = '10px';
	$color1  = 'black';
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
					  
					  <style>
						
						
						body {
							/*background-image: url(data:image/jpeg;base64,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zgjOCCBjBrNtdYtdcSPTtes30/USQYGZWjJPZo2PKnI6d+OvIoA6lJLW8B2PDP5UmDghtjjt7EVHqVvJdaZc28SRPJLGUAmPy88ZPB6df8K4u+bxF4RluriBobyxuJjI0kkYG12xksFxjpjP3foTit/QdUeSI2F7qltcasrNvVVxtx/DxgNgdce/pQBg6do3ifwxOi2XlX1tJlpYQ+AMY7tjBOeCM9ORxV7UNH0rxasskQey1WPAkWRdrjHTencEEYYe3JxiuhvlDWkq3Ua3EJQlolgLFgBkgc9fT3rhdW0HXR4snu9It5QIihilDhRgIoxljz6GgDodN1y6sZ103W44rVoo8C6luPlnxxuUsOTyMgtnnp2rb1D/AErRbr7PiXzbd/L2HO/KnGPXNc14f1618WWraXqtoslwqFmO35GA43Durc/4HnFaDaDqyILa08Qyw2SoI0jNshdVAAwHGD+PUfrQBy/w7tLiPXr15ImQRQGOQMMFWLDAIPP8J/KvSaqadp1vplr5FuGILF3d23NI56sx7k1boAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACuV1SKfxRqi6fGJV0i2kZLuWORQXlAyFA64BI7dSfQGr+v6o8DW+l2j/6ffMI1KgsYUPDSYHpzjp0Poas6FpKaJpMViriQqWZpAm3eSc5I57YH4UATadqdpqsDzWcyyxpIYyyg4yMHuB2Iq5UUFtb2qstvBHCrMWYRoFBY9zjvUtABVPUNJsNVjRL62SYIcru4K/QjnsKuUUAeYeHvHE1httNSVp7PJAf7zxg9ufvD9fyArcfw9D58PiDwu0DPhnEDk+W+QRxggqeSMHA+mKn1zwLbarepcW08diAm1o47cEMck7uCOef0rk9Aln0u5lew12yLeWzfZysm2YgZC/MqgH3zn9aAOn0/wAdifU49P1DTpLOVyIyxc8OcYypAIH8v1rn/HOsyz38ukARm2t5FdXDMzE7OcknH8R7VuiTQ/HlttcfZdSjXauSN478f31zn369M1D4c8DzafrT3WoMjx2zA2+zBEjf3j3GPT19hyAbHg/QP7E0vfMuLy4w0vP3R2X8M8+574FdFRRQAUUUUAFF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jKnHO045xgg8du1etaibVbFzewrLb5XcjoGB5GOD74ryrxt/yN99/wBs/wD0WtAHrysGUMpBUjII70tZ+hf8i9pn/XpF/wCgCtCgAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKAMXxXK0Ph2eVIxKySQsIyMhiJUOMe9aVj539n232lds/lL5i5zhsDIz9azvFUyW3h6aeRPMSKWF2TONwEqEir2l3ZvtKtLplZWliVyGHOSKALdFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAVHLPFCYxLIqGRtiBjjcfQVJRQAjKGGGAI9DXlvjq3jh1CFxaLbSyGTcBIGMihvlc+meeO3TtXqdcf448PtqMJ1JZ44/skDFlZeXA5Az+f+TQB0Ghf8i9pn/XpF/wCgCtCs/Qf+Re0z/r1i/wDQBWhQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFAGfrmm/wBr6LdWIfY0q/Kx6bgQRn2yBms5tQ8RpYQBdFEl4GxMTNGI2Xnlfnzk8dRxXQ0UAZ2n31/cELe6RLaMSfmE0ciAfUHP6Vo0UUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQBTnvJ4pmRNMu5lHSSNogp/76cH9KwtSuPEeqafNYx6Itr5/7tppLpHCoeCSBz09M11NFAFextfsWn21pv3+REse7GN20AZx+FWKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKAP/9k=);*/ /* Coloca aquí tu imagen Base64 */ 
							background-size: auto; /* Ajusta la imagen al tamaño completo */
							background-position: center; /* Centra la imagen */
							background-repeat: no-repeat; /* Evita repeticiones */
							-webkit-print-color-adjust: exact; /* Asegura la impresión de colores */
							height: 100vh; /* Ajusta la altura al tamaño del papel */
							margin: 0; /* Elimina márgenes */
						}
											
					  </style>
						
                    </head>
        
                    <body>
        
						<table style='width:100%;'>
							<tr><td>&nbsp; &nbsp; </td></tr>
							<tr><td>&nbsp; &nbsp; </td></tr>
							<tr>
							  <td style='text-align:center;width: 150px;'>
								<img  src='".$base64."' width='110'  >
							  </td>
							  <td  style='text-align:left'>
									<table>
										<tbody>
											<tr><td style='font-size: ".$size2."; color: ".$color1."; ' > ".strtoupper($objCompany->name)."</td></tr>
											<tr><td style='font-size: ".$size1."; color: ".$color1."; '> ".$objCompany->address."</td></tr>
											<tr><td style='font-size: ".$size1."; color: ".$color1."; '> ".strtoupper($objTransactionMastser->transactionNumber)."</td></tr>
											<tr><td style='font-size: ".$size1."; color: ".$color1."; ' >  ".$objParameterTelefono->value." ".getBehavio(
												$objCompany->type,
												"app_lab_examen",
												"lblReportCelularEmergencia",
												" EMERGENCIA: 8611-1898"
											)."</td></tr>
											<tr><td style='font-size: ".$size1."; color: ".$color1."; ' >  ".getBehavio(
												$objCompany->type,
												"app_lab_examen",
												"lblReportHorarioAtencion",
												"Horario de atencion: Lunes - Viernes 7:00 A.M A  03:00 P.M"
											)."</td></tr>
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
							  <td  style='text-align:left;width: 35px;font-size: ".$size1."; color: ".$color1."; ' >Paciente:</td>
							  <td  style='text-align:left;width: auto;font-size: ".$size1."; color: ".$color1."; ' >".$objEntidadNatural->firstName."</td>							  
							  <td  style='text-align:left;width: 25px;font-size: ".$size1."; color: ".$color1."; ' >Fecha:</td>
							  <td  style='text-align:left;width: 160px;font-size: 14px; color: ".$color1."; ' >".$objTransactionMastser->statusIDChangeOn."</td>
							  <td  style='text-align:left;width: 25px;font-size: ".$size1."; color: ".$color1."; ' >".getBehavio($objCompany->type,"web_tools_report_helper","Edad","").":</td>
							  <td  style='text-align:left;width: 25px;font-size: ".$size1."; color: ".$color1."; ' >".$objEdad->name."</td>
							  <td  style='text-align:left;width: 25px;font-size: ".$size1."; color: ".$color1."; ' >".getBehavio($objCompany->type,"web_tools_report_helper","Sexo","").":</td>
							  <td  style='text-align:left;width: 25px;font-size: ".$size1."; color: ".$color1."; ' >".$objSexo->name."</td>
							</tr>
							<tr>
								<td colspan='2' style='text-align:left;width: 25px;font-size: ".$size1."; color: ".$color1."; ' >
									Fecha de nacimineto:".$objEntidadCustomer->birthDate."
								</td>
								<td colspan='6' >
									
								</td>
							</tr>
							<tr>
								<td colspan='1' style='text-align:left;width: 25px;font-size: ".$size1."; color: ".$color1."; ' >
									Edad:
								</td>
								<td colspan='7' style='font-size: ".$size1."; color: ".$color1."; ' >
									".helper_GetFechaNacimiento($objEntidadCustomer->birthDate)." años
								</td>
							</tr>
							<tr>
								<td colspan='1' style='text-align:left;width: 25px;font-size: ".$size1."; color: ".$color1."; ' >
									Examen:
								</td>
								<td colspan='7'  style=' font-size: ".$size1."; color: ".$color1."; ' >
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
								$html = $html."<td style='text-align:left;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse;color:blue;width:33%'>".												
												str_replace("&lt;/br&gt;","</br>",htmlentities($valor->display)).
											"</td>";
							$html = $html."</tr>";

						}
						//si el indicador tiene 2 columnas
						//mostrar el indicador de la siguiente manera
						else 
						{
													
							$html = $html."<tr  >";
								$html = $html."<td style='text-align:left;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse; '>".$valor->name."</td>";
								$html = $html."<td style='text-align:left;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse;'>".$valor->reference3."</td>";
								$html = $html.
											"<td style='text-align:right;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse;color:blue'>".												
												str_replace("&lt;/br&gt;","</br>",htmlentities($valor->display)).
											"</td>";
											
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
							<tr>
								<td style='text-align:left;'>_____________________</td>
								<td style='text-align:left;'>_____________________</td>
							</tr>
							<tr>
								<td style='text-align:left;font-size: ".$size1."; color: ".$color1.";' >Firma: Heysell Morales Cod. MINSA: 47035</td>
								<td style='text-align:left;font-size: ".$size1."; color: ".$color1.";' >Sello</td>
							</tr>
						</table>
                     
						<table style='width:100%' >
							<tr><td style='text-align:left;'>&nbsp;</td></tr>							
							<tr><td style='text-align:left;'>&nbsp;</td></tr>							
							<tr><td style='text-align:center;font-size: ".$size1."; color: ".$color1.";'>".getBehavio(
												$objCompany->type,
												"app_lab_examen",
												"lblReportHorarioAtencion",
												"Profesionales al cuidado de tu salud."
							)."</td></tr>
						</table>
						
					</body>     
                    </html>
            ";
    
   return $html;
}

function helper_reporteA4TransactionMasterExamenLabV1(
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
								$html = $html."<td style='text-align:left;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse;color:blue;width:33%'>".												
												str_replace("&lt;/br&gt;","</br>",htmlentities($valor->display)).
											"</td>";
							$html = $html."</tr>";

						}
						//si el indicador tiene 2 columnas
						//mostrar el indicador de la siguiente manera
						else 
						{
													
							$html = $html."<tr  >";
								$html = $html."<td style='text-align:left;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse; '>".$valor->name."</td>";
								$html = $html."<td style='text-align:left;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse;'>".$valor->reference3."</td>";
								$html = $html.
											"<td style='text-align:right;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse;color:blue'>".												
												str_replace("&lt;/br&gt;","</br>",htmlentities($valor->display)).
											"</td>";
											
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


function helper_reporteA4TransactionMasterConsultaMedica(
    $titulo,
	$rucCompany,
    $objCompany,
    $objParameterLogo,
    $fecha,
    $folio,
    $nombreCliente,  
	$objTransactionMasterDetail, 
	$objTransactionMaster,
	$objSexo,
	$objListPriorities,
	$objListFrecuencies,
	$objListDoses
) {
    $path    	= PATH_FILE_OF_APP_ROOT.'/img/logos/'.$objParameterLogo->value;	
	$path1    	= PATH_FILE_OF_APP_ROOT.'/img/medi.jpg';
    $type    	= pathinfo($path, PATHINFO_EXTENSION);
	$type1    	= pathinfo($path1, PATHINFO_EXTENSION);
    $data    	= file_get_contents($path);
	$data1    	= file_get_contents($path1);
    $base64  	= 'data:image/' . $type . ';base64,' . base64_encode($data);
	$base641  	= 'data:image/' . $type1 . ';base64,' . base64_encode($data1);

    $html = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8' />
            <meta name='viewport' content='width=device-width, initial-scale=1.0' />
            <style>
                @page {       
                    size: 13cm 21cm;                  
                    margin-top: 20px;
                    margin-left: 20px;
                    margin-right: 20px;
                }
                body {
                    font-family: Arial, sans-serif;
                    font-size: xx-small;
                }
                table {
                    font-size: xx-small;
                    font-family: Consolas, monaco, monospace;
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 2px;
                }
                th, td {
                    border: 0px solid black;
                    padding: 0px;
                    text-align: left;
                    vertical-align: top;
                }
                th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                td {
                    text-align: center;
                }
                img {
                    display: block;
                    margin: 0 auto;
                }
                .center {
                    text-align: center;
                }
                .right {
                    text-align: right;
                }
                .header-table td {
                    border: none;
                    font-size: xx-small;
                }
				
            </style>
        </head>
        <body>
            <table class='header-table'>
				<tr>
					<td colspan='2'>
						<table>
							<tr>
								<td style='text-align:left;' ><img src='".$base641."' style='margin:0px' width='50' height='60' >
								</td>
								<td><img src='".$base64."' width='110'   >
								</td>
								<td style='text-align:right;' ><img src='".$base641."' style='margin-right:0px' width='50' height='60'>
								</td>
							</tr>							
						</table>
					</td>
				</tr>
                <tr>
					<td colspan='2' class='center'>
						<b>".strtoupper($objCompany->name)."</b><br>
						CÉDULA PROFESIONAL: ".strtoupper($rucCompany)."<br>
						".strtoupper($titulo)."
					</td>
				</tr>

				<tr>
					<td colspan='2' class='right'>
						<table>
							<tr>
								<td>
								</td>
								<td  style='width: 80px;text-align:right' >Fecha:
								</td>
								<td  style='width: 80px;text-align:right' >".$fecha."
								</td>
							</tr>
							<tr>
								<td>
								</td>
								<td style='width: 80px;text-align:right' >Folio:
								</td>
								<td style='width: 80px;text-align:right' >".strtoupper($folio)."
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<table style='width: 100%; border-collapse: collapse;'>
				<thead>
					<tr>
						<th colspan='5' style='text-align: left;'>DETALLES DEL PACIENTE</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan='5' style='text-align: left;'><b>NOMBRE DEL PACIENTE:</b> ".strtoupper($nombreCliente->firstName . " " . $nombreCliente->lastName)."</td>
					</tr>
					<tr>
						<td style='text-align: left;'><b>SEXO:</b> ".$objSexo."</td>
						<td style='text-align: left;'><b>EDAD:</b> ".number_format($objTransactionMaster->tax1, 0)." años</td>
						<td style='text-align: left;'><b>ALTURA:</b> ".number_format($objTransactionMaster->tax2, 1)."cms</td>
						<td style='text-align: left;'><b>PESO:</b> ".number_format($objTransactionMaster->tax3, 1)."kgs</td>
						<td style='text-align: left;'><b>IMC:</b> ".number_format($objTransactionMaster->tax4, 1)."</td>
					</tr>
				</tbody>
			</table>
			</br>
			<table>
				<thead>
					<tr>
						<th colspan='4'>DETALLES DE LA CONSULTA</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan='1' style='text-align:left;'>	
							<b>EVALUACION</b>
						</td>
						<td colspan='3' style='text-align:left;'>" 
							. strtoupper($objTransactionMaster->reference2) . "<br>
						</td>";
						$html.="</td>				
					</tr>

					<tr>
						<td colspan='1' style='text-align:left;'>	
							<b>RECOMENDACION</b>
						</td>
						<td colspan='3' style='text-align:left;'>" 
							. strtoupper($objTransactionMaster->reference3) . "<br>
						</td>";
						$html.="</td>				
					</tr>
				</tbody>
			</table>
            <br/>
            <table>
			<thead>
				<tr>
            		<th colspan='2'>DETALLES DEL TRATAMIENTO</th>
                </tr>
            </thead>
            <tbody>
				";
					$count = 0;
					foreach($objTransactionMasterDetail as $detalle) 
					{
						
						$count++;
						$html .= "
						<tr>";
								
							$html .="
							<td style='text-align:left;width:50%'>
								".$count.")".$detalle->itemNameLog."
							</td>
							
							<td style='text-align:left;width:50%'>";

								foreach($objListDoses as $dose) {
									if ($detalle->typePriceID == $dose->catalogItemID) {
										$html .= $dose->name . " ";
									}
								}

								foreach($objListFrecuencies as $frecuency) {
									if ($detalle->skuQuantityBySku == $frecuency->catalogItemID) {
										$html .= $frecuency->name;
									}
								}
							$html .="</td>		
						</tr>";	
					
					}
    				$html .= "
                </tbody>
            </table>
			</br>
			<table class='header-table'>
				<tr>
					<td style='text-align:center;'>
						<b>Proxima Visita:</b><br>
						$objTransactionMaster->nextVisit
					</td>
				</tr>
			</table>

			<table class='header-table' style='position: fixed; bottom: 20px; width: 100%;'>
				<tr>
					<td style='text-align:center;'>
						_____________________ <br><br>
								<b>Sello</b>
					</td>
					<td style='text-align:center;'>
					_____________________ <br><br>
								<b>Firma</b>
					</td>
				</tr>
			</table>
        </body>
    </html>";

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
	$dataView, 
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
                        </tr>
						
						<tr>
                          <td colspan='3' style=''>
                            Zona: ".$dataView["objZone"]->name."
                          </td>
                        </tr>
						
						<tr>
                          <td colspan='3' style=''>
                            Mesa: ".$dataView["objMesa"]->name."
                          </td>
                        </tr>
						
						";
						
						
          $html	= $html."<tr>
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

function helper_reporteA4mmTransactionMasterShareEbenezer(
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
	$numberDocument = str_replace("SHR","SERIE \"A\" RECIBO No ",$objTransactionMastser->transactionNumber);
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
						  <td  style='text-align:center;width:20%;text-align:left'>
							
						  </td>
						  <td  style='text-align:center;width:60%'>
								<h2 style='margin:0px' >ESCUELA ADVENTISTA \"EBEN-EZER\" MALPAISILLO - LEON</h2>
								<h2 style='margin:0px' >EBEN-EZER</h2>
								<h2 style='margin:0px' >Antigua petronic 5c. al sur - Malpaisillo, León </h2>
						  </td>
						  <td style='width:20%;text-align: right;vertical-align: top;' >
							<img  src='".$base64."' width='70'  >
						  </td>
						</tr>
					</table>
					</br>
						";
		   
	$f_html = $f_html."
				
			";
			
			
	$f_html = $f_html."
			
				
				<table style='width:98%' >
					<tr>
						<td style='text-align:left;width:33%;vertical-align:bottom ' >
							<h2 style='margin:0px;color:blue' >RUC : ". $rucCompany ."</h2>
							<table style='width:100px ;".$border_colapse.$border_radius.$border_top.$border_left.$border_right.$border_bottom." '>
								<tr>
									<td style='width:50px'> <h2 style='margin:0px;color:black' >Día</h2>
									</td>
									<td style='width:50px'> <h2 style='margin:0px;color:black' >Mes</h2>
									</td>
									<td style='width:50px'> <h2 style='margin:0px;color:black' >Año</h2>
									</td>
									<td style=''>.
									</td>
								</tr>
								<tr>
									<td>
										".(new \DateTime($objTransactionMastser->transactionOn))->format("d")."
									</td>
									<td>
										".(new \DateTime($objTransactionMastser->transactionOn))->format("m")."
									</td>
									<td>
										".(new \DateTime($objTransactionMastser->transactionOn))->format("Y")."
									</td>
									<td> 
									</td>
								</tr>
							</table>
						</td>
						<td style='text-align:center;width:33%; vertical-align:top' >
							<h1 style='margin:0px;color:black' >RECIBO DE INGRESO</h1>
						</td>
						<td style='text-align:right;width:33%; vertical-align:bottom' >
							<h2 style='margin:0px;color:red' >". $numberDocument ."</h2>
							<h2 style='margin:0px' >POR C$: ".number_format($objTransactionMastser->amount,2,'.',',')."</h2>
						</td>
					</tr>
				</table>
				
				</br>
			";
			
		   

		   
	$f_html = $f_html."
				
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
				   </table>
				   ";
				   
				   
	
		   
	$f_html = $f_html."
				
			";
				
	$f_html = $f_html."
				</br>
				<table style='width:98%' >
					<tr>
						<td style='text-align:center' >
							<h1 style='margin:0px' >\"Educamos Para Redimir\"</h1>
							<h2 style='margin:0px' >No se aceptan devoluciones</h2>
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
						<td style='text-align:center' >________________________</td>
						<td style='text-align:center' >________________________</td>						
					</tr>							
					<tr>
						<td style='text-align:center' >Entregue conforme</td>
						<td style='text-align:center' >Recibí conforme</td>						
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


function helper_reporteA4CreditAndDebitNote(
    $objNote,						/* Tipo de nota(credito o debito), estado de la nota, monto inicial, monto final */
    $objCompany, 					/* company name, address */
    $objParameterLogo, 				/* company logo */
    $objTransactionMastser, 		/* transaction number, date, amount, note, reference */
    $objEntity, 					/* tipo de entidad(proveedor, cliente...), nombre, numero*/
    $objCurrency, 					/* tipo de moneda(cordoba, dolar) */
    $objParameterTelefono, 			/* numero de telefono */
    $objEmployerNatural, 			/* usuario (vendedor) */
    $rucCompany 					/* codigo ruc */
) {
    $path = PATH_FILE_OF_APP_ROOT . '/img/logos/' . $objParameterLogo->value;

    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    $numberDocument = str_replace("FAC", "SERIE \"A\" RECIBO No ", $objTransactionMastser->transactionNumber);
	$montoInicial 	= $objNote["montoInicial"];
	$montoFinal 	= $objNote["montoFinal"];

    $html = "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8' />
        <meta name='viewport' content='width=device-width, initial-scale=1.0' />
        <style>
            @page {
                size: A4;
                margin: 25px;
            }
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                color: #000;
            }
            .header, .footer {
                text-align: center;
                margin-bottom: 20px;
            }
            .header img {
                max-width: 200px;
            }
            .header h1 {
                font-size: 18px;
                margin: 5px 0;
            }
            .header h2 {
                font-size: 14px;
                margin: 5px 0;
            }
            .content {
                margin: 20px 0;
            }
            .content table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
                border: 1px solid #000;
            }
            .content table td {
                padding: 8px;
                border-right: 1px solid #000;
            }
            .content table td:last-child {
                border-right: none;
            }
            .content table tr:last-child td {
                border-bottom: none;
            }
            .content table th {
                padding: 8px;
                border: 1px solid #000;
                background-color: #f2f2f2;
            }
            .footer {
                font-size: 10px;
                margin-top: 20px;
            }
            .text-center {
                text-align: center;
            }
            .text-right {
                text-align: right;
            }
            .text-left {
                text-align: left;
            }
            .bold {
                font-weight: bold;
            }
            .col-30 {
                width: 30%;
            }
            .col-70 {
                width: 70%;
            }
			.col-60 {
				width: 60% !important;
			}
			.col-40 {
				width: 40% !important;
			}
        </style>
    </head>
    <body>
        <div class='header'>
            <img src='{$base64}' alt='Company Logo'>
            <h1>{$objCompany->name}</h1>
            <h2>RUC: {$rucCompany}</h2>
            <h2>Teléfono: {$objParameterTelefono->value}</h2>
            <h2>Dirección: {$objCompany->address}</h2>
        </div>

        <div class='content'>
            <table>
				<tr>
					<th class='text-left col-60' style='border-right:none'> NOTA DE " . $objNote["type"] . " DE ". strtoupper($objEntity["type"]) ."</th>
					<th class='text-right col-40' style='border-left:none'>" . $objNote["status"] . "</th>
				</tr>
                <tr>
                    <td class='bold col-30'>Número de Documento:</td>
                    <td class='col-70'>{$numberDocument}</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Fecha de Emisión:</td>
                    <td class='col-70'>" . (new \DateTime($objTransactionMastser->transactionOn))->format("Y/m/d") . "</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Código de ". $objEntity["type"] .":</td>
                    <td class='col-70'>". $objEntity["number"] ."</td>
                </tr>
                <tr>
                    <td class='bold col-30'>". $objEntity["type"] .":</td>
                    <td class='col-70'>". $objEntity["name"] ."</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Usuario:</td>
                    <td class='col-70'>{$objEmployerNatural->nickname}</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Moneda:</td>
                    <td class='col-70'>" . strtoupper($objCurrency->name) . "</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Monto Inicial:</td>
                    <td class='col-70'>{$objCurrency->simbol} " . number_format($montoInicial, 2, '.', ',') . "</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Monto:</td>
                    <td class='col-70'>{$objCurrency->simbol} " . number_format($objTransactionMastser->amount, 2, '.', ',') . "</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Monto Final:</td>
                    <td class='col-70'>{$objCurrency->simbol} " . number_format($montoFinal, 2, '.', ',') . "</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Referencia:</td>
                    <td class='col-70'>{$objTransactionMastser->reference1}</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Nota:</td>
                    <td class='col-70'>{$objTransactionMastser->note}</td>
                </tr>
            </table>
        </div>

        <div class='footer'>
            <p class='text-center'>Este documento es válido como comprobante de crédito según las normativas vigentes.</p>
        </div>
    </body>
    </html>
    ";

    return $html;
}


function helper_reporteA4Withholding(
    $objWithhold,						
    $objCompany, 					
    $objParameterLogo, 				
    $objTransactionMastser, 		
    $objEntity, 					
    $objCurrency, 					
    $objParameterTelefono, 			
    $objEmployerNatural, 			
    $rucCompany 					
) {
    $path = PATH_FILE_OF_APP_ROOT . '/img/logos/' . $objParameterLogo->value;

    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    $numberDocument = str_replace("FAC", "SERIE \"A\" RECIBO No ", $objTransactionMastser->transactionNumber);

    $html = "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8' />
        <meta name='viewport' content='width=device-width, initial-scale=1.0' />
        <style>
            @page {
                size: A4;
                margin: 25px;
            }
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                color: #000;
            }
            .header, .footer {
                text-align: center;
                margin-bottom: 20px;
            }
            .header img {
                max-width: 200px;
            }
            .header h1 {
                font-size: 18px;
                margin: 5px 0;
            }
            .header h2 {
                font-size: 14px;
                margin: 5px 0;
            }
            .content {
                margin: 20px 0;
            }
            .content table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
                border: 1px solid #000;
            }
            .content table td {
                padding: 8px;
                border-right: 1px solid #000;
            }
            .content table td:last-child {
                border-right: none;
            }
            .content table tr:last-child td {
                border-bottom: none;
            }
            .content table th {
                padding: 8px;
                border: 1px solid #000;
                background-color: #f2f2f2;
            }
            .footer {
                font-size: 10px;
                margin-top: 20px;
            }
            .text-center {
                text-align: center;
            }
            .text-right {
                text-align: right;
            }
            .text-left {
                text-align: left;
            }
            .bold {
                font-weight: bold;
            }
            .col-30 {
                width: 30%;
            }
            .col-70 {
                width: 70%;
            }
        </style>
    </head>
    <body>
        <div class='header'>
            <img src='{$base64}' alt='Company Logo'>
            <h1>{$objCompany->name}</h1>
            <h2>RUC: {$rucCompany}</h2>
            <h2>Teléfono: {$objParameterTelefono->value}</h2>
            <h2>Dirección: {$objCompany->address}</h2>
        </div>

        <div class='content'>
            <table>
				<tr>
					<th class='text-left' style='border-right:none'> RETENCION DE ". strtoupper($objEntity["type"]) ."</th>
					<th class='text-right' style='border-left:none'>" . $objWithhold["status"] . "</th>
				</tr>
                <tr>
                    <td class='bold col-30'>Número de Documento:</td>
                    <td class='col-70'>{$numberDocument}</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Fecha de Emisión:</td>
                    <td class='col-70'>" . (new \DateTime($objTransactionMastser->transactionOn))->format("Y/m/d") . "</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Código de ". $objEntity["type"] .":</td>
                    <td class='col-70'>". $objEntity["number"] ."</td>
                </tr>
                <tr>
                    <td class='bold col-30'>". $objEntity["type"] .":</td>
                    <td class='col-70'>". $objEntity["name"] ."</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Usuario:</td>
                    <td class='col-70'>{$objEmployerNatural->nickname}</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Moneda:</td>
                    <td class='col-70'>" . strtoupper($objCurrency->name) . "</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Factura:</td>
                    <td class='col-70'>". $objWithhold["invoice"] ."</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Impuesto:</td>
                    <td class='col-70'>". $objWithhold["taxPercentage"] ."</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Monto de Factura:</td>
                    <td class='col-70'>{$objCurrency->simbol} " . number_format($objWithhold["invoiceAmount"], 2, '.', ',') . "</td>
                </tr>
				<tr>
                    <td class='bold col-30'>Monto de Retencion:</td>
                    <td class='col-70'>{$objCurrency->simbol} " . number_format($objWithhold["withholdingAmount"], 2, '.', ',') . "</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Referencia:</td>
                    <td class='col-70'>{$objTransactionMastser->reference1}</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Nota:</td>
                    <td class='col-70'>{$objTransactionMastser->note}</td>
                </tr>
            </table>
        </div>

        <div class='footer'>
            <p class='text-center'></p>
        </div>
    </body>
    </html>
    ";

    return $html;
}

function helper_reporteA4FixedAssetTransaction(
    $objFAI,						
    $objCompany, 					
    $objParameterLogo, 				
    $objTransactionMastser, 		
    $objTMD, 					
    $objParameterTelefono, 			
    $objEmployerNatural, 			
    $rucCompany 					
) {
    $path = PATH_FILE_OF_APP_ROOT . '/img/logos/' . $objParameterLogo->value;

    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    $numberDocument = str_replace("FAC", "SERIE \"A\" RECIBO No ", $objTransactionMastser->transactionNumber);

    $html = "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8' />
        <meta name='viewport' content='width=device-width, initial-scale=1.0' />
        <style>
            @page {
                size: A4;
                margin: 25px;
            }
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                color: #000;
            }
            .header, .footer {
                text-align: center;
                margin-bottom: 20px;
            }
            .header img {
                max-width: 200px;
            }
            .header h1 {
                font-size: 18px;
                margin: 5px 0;
            }
            .header h2 {
                font-size: 14px;
                margin: 5px 0;
            }
            .content {
                margin: 20px 0;
            }
            .content table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
                border: 1px solid #000;
            }
            .content table td {
                padding: 8px;
                border-right: 1px solid #000;
            }
            .content table td:last-child {
                border-right: none;
            }
            .content table tr:last-child td {
                border-bottom: none;
            }
            .content table th {
                padding: 8px;
                border: 1px solid #000;
                background-color: #f2f2f2;
            }
            .footer {
                font-size: 10px;
                margin-top: 20px;
            }
            .text-center {
                text-align: center;
            }
            .text-right {
                text-align: right;
            }
            .text-left {
                text-align: left;
            }
            .bold {
                font-weight: bold;
            }
            .col-30 {
                width: 30%;
            }
            .col-70 {
                width: 70%;
            }
            .col-60 {
                width: 60% !important;
            }
            .col-40 {
                width: 40% !important;
            }

            /* New Styles for the Second Table */
            .table-fixed-assets {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
                border: 1px solid #000;
            }
            .table-fixed-assets th {
                background-color: #f2f2f2;
                padding: 10px;
                border: 1px solid #000;
                font-weight: bold;
                text-align: center;
            }
            .table-fixed-assets td {
                padding: 10px;
                border: 1px solid #000;
                text-align: left;
            }
            .table-fixed-assets td.text-center {
                text-align: center;
            }
            .table-fixed-assets td.text-right {
                text-align: right;
            }
            .table-fixed-assets tr:nth-child(even) {
                background-color: #f9f9f9; /* Alternate row color */
            }
            .table-fixed-assets tr:hover {
                background-color: #f1f1f1; /* Hover effect */
            }
        </style>
    </head>
    <body>
        <div class='header'>
            <img src='{$base64}' alt='Company Logo'>
            <h1>{$objCompany->name}</h1>
            <h2>RUC: {$rucCompany}</h2>
            <h2>Teléfono: {$objParameterTelefono->value}</h2>
            <h2>Dirección: {$objCompany->address}</h2>
        </div>

        <div class='content'>
            <table>
                <tr>
                    <th class='text-left col-30' style='border-right:none'>" . $objFAI["type"] ." DE ACTIVO FIJO</th>
                    <th class='text-right col-70' style='border-left:none'>" . $objFAI["status"] . "</th>
                </tr>
                <tr>
                    <td class='bold col-30'>Número de Documento:</td>
                    <td class='col-70'>{$numberDocument}</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Fecha de Emisión:</td>
                    <td class='col-70'>" . ($objFAI["transactionOn"]) . "</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Código de ". $objFAI["entityType"] .":</td>
                    <td class='col-70'>". $objFAI["entityNumber"] ."</td>
                </tr>
                <tr>
                    <td class='bold col-30'>". $objFAI["entityType"] .":</td>
                    <td class='col-70'>". $objFAI["entityName"] ."</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Usuario:</td>
                    <td class='col-70'>{$objEmployerNatural->nickname}</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Causal:</td>
                    <td class='col-70'>" . $objFAI["causalName"] . "</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Proyecto:</td>
                    <td class='col-70'>". $objFAI["projectName"] . "</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Area:</td>
                    <td class='col-70'>". $objFAI["areaName"] . "</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Referencia:</td>
                    <td class='col-70'>". $objFAI["reference1"] ."</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Referencia:</td>
                    <td class='col-70'>". $objFAI["reference2"] ."</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Referencia:</td>
                    <td class='col-70'>". $objFAI["reference3"] ."</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Nota:</td>
                    <td class='col-70'>". $objFAI["comment"] ."</td>
                </tr>
            </table>

            <!-- New Table: Activos Fijos a Asignar -->
            <table class='table-fixed-assets'>
                <thead>
                    <tr>
                        <th colspan='3' class='text-center'>ACTIVOS FIJOS DE ".  $objFAI["type"] ."</th>
                    </tr>
                    <tr>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Duración Estimada</th>
                    </tr>
                </thead>
                <tbody>
    ";

    if ($objTMD) {
        foreach ($objTMD as $detail) {
            $html .= "
                    <tr>
                        <td>{$detail->fixedAssetCode}</td>
                        <td>{$detail->fixedAssetName}</td>
                        <td  class='text-right'>{$detail->fixedAssetDuration}</td>
                    </tr>
            ";
        }
    }

    $html .= "
                </tbody>
            </table>
        </div>

        <div class='footer'>
            <p class='text-center'></p>
        </div>
    </body>
    </html>
    ";

    return $html;
}

function helper_reporteA4FixedAssetDepreciationAndValuation(
    $objFAD,						
    $objCompany, 					
    $objParameterLogo, 	
	$objCurrency,			
    $objTransactionMastser, 		
    $objTMD, 					
    $objParameterTelefono, 			
    $objEmployerNatural, 			
    $rucCompany 					
) {
    $path = PATH_FILE_OF_APP_ROOT . '/img/logos/' . $objParameterLogo->value;

    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    $numberDocument = str_replace("FAC", "SERIE \"A\" RECIBO No ", $objTransactionMastser->transactionNumber);

    $html = "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8' />
        <meta name='viewport' content='width=device-width, initial-scale=1.0' />
        <style>
            @page {
                size: A4;
                margin: 25px;
            }
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                color: #000;
            }
            .header, .footer {
                text-align: center;
                margin-bottom: 20px;
            }
            .header img {
                max-width: 200px;
            }
            .header h1 {
                font-size: 18px;
                margin: 5px 0;
            }
            .header h2 {
                font-size: 14px;
                margin: 5px 0;
            }
            .content {
                margin: 20px 0;
            }
            .content table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
                border: 1px solid #000;
            }
            .content table td {
                padding: 8px;
                border-right: 1px solid #000;
            }
            .content table td:last-child {
                border-right: none;
            }
            .content table tr:last-child td {
                border-bottom: none;
            }
            .content table th {
                padding: 8px;
                border: 1px solid #000;
                background-color: #f2f2f2;
            }
            .footer {
                font-size: 10px;
                margin-top: 20px;
            }
            .text-center {
                text-align: center;
            }
            .text-right {
                text-align: right;
            }
            .text-left {
                text-align: left;
            }
            .bold {
                font-weight: bold;
            }
            .col-30 {
                width: 30%;
            }
            .col-70 {
                width: 70%;
            }
            .col-60 {
                width: 60% !important;
            }
            .col-40 {
                width: 40% !important;
            }

            /* New Styles for the Second Table */
            .table-fixed-assets {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
                border: 1px solid #000;
            }
            .table-fixed-assets th {
                background-color: #f2f2f2;
                padding: 10px;
                border: 1px solid #000;
                font-weight: bold;
                text-align: center;
            }
            .table-fixed-assets td {
                padding: 10px;
                border: 1px solid #000;
                text-align: left;
            }
            .table-fixed-assets td.text-center {
                text-align: center;
            }
            .table-fixed-assets td.text-right {
                text-align: right;
            }
            .table-fixed-assets tr:nth-child(even) {
                background-color: #f9f9f9; /* Alternate row color */
            }
            .table-fixed-assets tr:hover {
                background-color: #f1f1f1; /* Hover effect */
            }
        </style>
    </head>
    <body>
        <div class='header'>
            <img src='{$base64}' alt='Company Logo'>
            <h1>{$objCompany->name}</h1>
            <h2>RUC: {$rucCompany}</h2>
            <h2>Teléfono: {$objParameterTelefono->value}</h2>
            <h2>Dirección: {$objCompany->address}</h2>
        </div>

        <div class='content'>
            <table>
                <tr>
                    <th class='text-left col-30' style='border-right:none'>" . $objFAD["type"] ." DE ACTIVOS FIJOS</th>
                    <th class='text-right col-70' style='border-left:none'>" . $objFAD["status"] . "</th>
                </tr>
                <tr>
                    <td class='bold col-30'>Número de Documento:</td>
                    <td class='col-70'>{$numberDocument}</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Fecha de Emisión:</td>
                    <td class='col-70'>" . ($objFAD["transactionOn"]) . "</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Año: </td>
                    <td class='col-70'>". $objFAD["yearPeriod"] ."</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Mes: </td>
                    <td class='col-70'>". $objFAD["monthCycle"] ."</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Usuario:</td>
                    <td class='col-70'>{$objEmployerNatural->nickname}</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Causal:</td>
                    <td class='col-70'>" . $objFAD["causalName"] . "</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Monto de Factura:</td>
                    <td class='col-70'>{$objCurrency->simbol} " . number_format($objFAD["totalAmount"], 2, '.', ',') . "</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Referencia:</td>
                    <td class='col-70'>". $objFAD["reference1"] ."</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Referencia:</td>
                    <td class='col-70'>". $objFAD["reference2"] ."</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Referencia:</td>
                    <td class='col-70'>". $objFAD["reference3"] ."</td>
                </tr>
                <tr>
                    <td class='bold col-30'>Nota:</td>
                    <td class='col-70'>". $objFAD["comment"] ."</td>
                </tr>
            </table>

            <!-- New Table: Activos Fijos a Asignar -->
            <table class='table-fixed-assets'>
                <thead>
                    <tr>
                        <th colspan='5' class='text-center'>LISTA DE ACTIVOS FIJOS EN ". $objFAD["type"] ." </th>
                    </tr>
                    <tr>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Ratio</th>
                        <th>Monto Corriente</th>
                        <th>Monto De Liquidacion</th>
                    </tr>
                </thead>
                <tbody>
    ";

    if ($objTMD) {
        foreach ($objTMD as $detail) {
            $html .= "
                    <tr>
                        <td>{$detail->fixedAssetCode}</td>
                        <td>{$detail->fixedAssetName}</td>
                        <td>{$detail->amountRatio}</td>
                        <td>{$detail->currentAmount}</td>
                        <td  class='text-right'>{$detail->settlementAmount}</td>
                    </tr>
            ";
        }
    }

    $html .= "
                </tbody>
            </table>
        </div>

        <div class='footer'>
            <p class='text-center'></p>
        </div>
    </body>
    </html>
    ";

    return $html;
}



function helper_reporteA4TarjetaFidelidad(
    $objCompany, 					/* company name, address */
    $objParameterLogo, 				/* company logo */
    $objTransactionMastser, 		/* transaction number, date, amount, note, reference */
    $objParameterTelefono, 			/* numero de telefono */
    $rucCompany 					/* codigo ruc */
) {
    $path 			= PATH_FILE_OF_APP_ROOT . '/img/fidelidad_background.jpg';
	$imagenOcupado 	= PATH_FILE_OF_APP_ROOT . "/img/fidelidad_ocupado.jpg";
	$imagenExtra   	= PATH_FILE_OF_APP_ROOT . "/img/fidelidad_extra.jpg";
	
	$type               = pathinfo($path, PATHINFO_EXTENSION);
	$data               = file_get_contents($path);
	$base64             = 'data:image/' . $type . ';base64,' . base64_encode($data);
	$cantidadCuadros    = $objTransactionMastser->amount;
	$cantidadOcupados   = $objTransactionMastser->tax1;

	

	$base64Ocupado = 'data:image/' . pathinfo($imagenOcupado, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($imagenOcupado));
	$base64Extra   = 'data:image/' . pathinfo($imagenExtra, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($imagenExtra));

	$html = '
	<!DOCTYPE html>
	<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Tarjeta con Cuadros</title>
		<style>
			@page {
				size: 3.5in 2in;
				margin: 0;
			}

			/*
			body {
				display: flex;
				justify-content: center;
				align-items: center;
				height: 100vh;
				background: url("' . $base64 . '") no-repeat center center/cover;
				background-size: cover;
				margin: 0;
				background-size:81%;
			}
			*/
			
			body {
				display: flex;
				justify-content: center;
				align-items: center;
				height: 100vh;
				background: url("' . $base64 . '") no-repeat center center/cover;
				background-size: cover;
				margin: 0;
				background-size:81%;
				
			}

			.tabla-container {
				background: rgba(255, 255, 255, 0.8);
				padding: 1px;
				border-radius: 10px;
				box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
				display: flex;
				flex-direction: column;
				align-items: center;
				
				background: url("' . $base64 . '") no-repeat center center/cover;
			}

			table {
				border-collapse: collapse;
				margin: auto;
				width:100%;
				border-spacing: 0; 
			}

			td {
				width: 50px;
				height: 50px;
				text-align: center;
				border: none;
			}

			.ocupado {
				background: url("' . $base64Ocupado . '") no-repeat center center;
				background-size: cover;
				border: 0.0px solid black;
				background-size:81%;
			}

			.no-ocupado {
				/*background-color: lightblue;*/
				background-color: white;
				border: 0.0px solid black;
			}

			.extra {
				background: url("' . $base64Extra . '") no-repeat center center;
				background-size: cover;
				border: 0.0px solid black;
				background-size:81%;
			}
		</style>
	</head>
	<body>
		<div class="tabla-container">
			<table>';
				
				$cantidadCuadros = $objTransactionMastser->amount;
				$cantidadOcupados = $objTransactionMastser->tax1;
				
				$maxPorFila = 5;
				$filas = ceil(($cantidadCuadros + 1) / $maxPorFila);
				$cuadro = 0;
				
				for ($i = 0; $i < $filas; $i++) {
					$html .= "<tr>";
					for ($j = 0; $j < $maxPorFila; $j++) {
						if ($cuadro < $cantidadCuadros) {
							$clase = ($cuadro < $cantidadOcupados) ? 'ocupado' : 'no-ocupado';
							$html .= "<td class='$clase'></td>";
						} elseif ($cuadro == $cantidadCuadros) {
							$html .= "<td class='extra'></td>";
						} else {
							$html .= "<td></td>";
						}
						$cuadro++;
					}
					$html .= "</tr>";
				}
			
	$html .= '
			</table>
			<table style="width:100%;border-collapse: collapse;  border-spacing: 0; ">
				<tr>
					<td style="height:25px;text-align: left;">
					  '.$objTransactionMastser->reference1.'
					</td>
				</tr>
				<tr>
					<td style="height:25px;text-align: left;">
					  Cliente: '.$objTransactionMastser->numberPhone.'
					</td>
				</tr>
				<tr>
					<td style="height:25px;text-align: left;">
					  '.$objTransactionMastser->transactionNumber.' Ptn: '.ceil($objTransactionMastser->tax1).' / '.ceil($objTransactionMastser->amount).'
					</td>
				</tr>
			</table>
		</div>
	</body>
	</html>
	';

	return $html;
}

function helper_reporteA4Checkbook(
    $objTMCheckbook,    
    $objCheckbook,    
    $objTM,                
    $objCompany,                     
    $objParameterLogo,                 
    $objTransactionMastser,         
    $objCurrency,                     
    $objParameterTelefono,             
    $objUser,             
    $rucCompany                     
) {
    $path = PATH_FILE_OF_APP_ROOT . '/img/logos/' . $objParameterLogo->value;

    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

    $html = "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8' />
        <meta name='viewport' content='width=device-width, initial-scale=1.0' />
        <style>
        @page {
			size: A4;
			margin: 2px;
		}
		body {
			font-family: Arial, sans-serif;
			font-size: 12px;
			color: #000;
		}
		.check {
			width: 96%;
			margin: 0 auto;
			border: 2px solid #000;
			padding: 5px 10px;
			position: relative;
		}
		.header {
			overflow: hidden;
			border-bottom: 1px solid #eee;
		}
		.header .left {	
			float: left;
			text-align: left;
		}
		.header .right {
			float: right;
			text-align: right;
		}
		.header img {
			max-width: 120px;
			display: inline-block;
			margin-bottom: 5px;
			margin-right: 5px;
		}
		.header h1 {
			font-size: 14px;
			margin: 8px 0 2px;
		}
		.header h2 {
			font-size: 11px;
			margin: 2px 0;
		}
		.title-row {
			width: 100%;
			margin-top: 5px;
			overflow: hidden;
		}
		.title-row .title {
			font-size: 14px;
			font-weight: bold;
			float: left;
		}
		.title-row .status {
			font-size: 14px;
			float: right;
		}
		.content {
			margin: 2px 0;
		}

		/* Layout con CSS 2.1 usando floats */
		.container {
			width: 100%;
			margin-top: 5px;
		}
		.row {
			overflow: hidden;
			width: 100%;
			margin-bottom: 5px;
		}
		.field-name {
			float: left;
			width: auto;
			padding: 5px;
			font-weight: bold;
		}
		.field-value {
			float: left;
			width: 41%;
			padding: 5px;
			position: relative;
		}
		.field-value span {
			display: block;
			border-bottom: 1px solid #000; /* Línea debajo del valor */
			padding-bottom: 2px; 
		}
		.clearfix::after {
			content: '';
			display: table;
			clear: both;
		}
			
        </style>
    </head>
    <body>
        <div class='check'>
            <div class='header clearfix'>
                <div class='left'>
                    <div class='title-row'>
                        <img src='{$base64}' alt='Company Logo'>
                        <div style='display:inline-block'>
                            <h1>{$objCompany->name}</h1>
                            <h2>RUC: {$rucCompany}</h2>
                            <h2>Teléfono: {$objParameterTelefono->value}</h2>
                        </div>
                    </div>
                </div>
            </div>

			<div class='title-row clearfix' style='border-bottom: 1px solid #eee'>
				<div class='title'>{$objTMCheckbook["type"]} {$objCheckbook->name}</div>
				<div class='status'>{$objTMCheckbook["status"]}</div>
			</div>

            <div class='content'>
				<div class='container'>
					<div class='row clearfix'>
						<div class='field-name'><span>Número de Documento:</span></div>
						<div class='field-value'><span>{$objTM->transactionNumber}</span></div>
						<div class='field-name'><span>Fecha de Emisión:</span></div>
						<div class='field-value'><span>" . (new \DateTime($objTransactionMastser->transactionOn))->format("Y-m-d") ."</span></div>
					</div>
					<div class='row clearfix'>
						<div class='field-name'><span>Numero de Chequera:</span></div>
						<div class='field-value'><span>". $objCheckbook->chequeNumber ."</span></div>
						<div class='field-name'><span>Numero de Cheque:</span></div>
						<div class='field-value'><span>". (!empty($objTM->numberPhone) ? $objTM->numberPhone : "No disponible") ."</span></div>
					</div>
					<div class='row clearfix'>
						<div class='field-name'>Pagar A:</div>
						<div class='field-value'><span>{$objTM->reference4}</span></div>
						<div class='field-name'>Monto Total:</div>
						<div class='field-value'><span>". $objCurrency->simbol ." ".$objTM->amount."</span></div>
					</div>
				</div>
            </div>
        </div>
    </body>
    </html>
    ";

    return $html;
}


function helper_reporteA4ProductionOrder(
    $objProductionOrder,						
    $objCompany, 					
    $objParameterTelefono, 			
    $objParameterLogo, 				
    $rucCompany, 	
	$objTM,				
    $objWorkflowStage, 		
    $objTMD, 			
	$objCurrency,		
    $objRequestEmployeeName,
	$objSenderEmployeeName 			
) {
    $path = PATH_FILE_OF_APP_ROOT . '/img/logos/' . $objParameterLogo->value;

    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    $numberDocument = str_replace("FAC", "SERIE \"A\" RECIBO No ", $objTM->transactionNumber);

	$html = "
	<!DOCTYPE html>
	<html lang='en'>
	<head>
		<meta charset='UTF-8' />
		<meta name='viewport' content='width=device-width, initial-scale=1.0' />
		<style>
			@page {
				size: A4;
				margin: 25px;
			}
			body {
				font-family: Arial, sans-serif;
				font-size: 12px;
				color: #000;
			}
			.header, .footer {
				text-align: center;
				margin-bottom: 20px;
			}
			.header img {
				max-width: 200px;
			}
			.header h1 {
				font-size: 18px;
				margin: 5px 0;
			}
			.header h2 {
				font-size: 14px;
				margin: 5px 0;
			}
			.content {
				margin: 20px 0;
			}
			.content table {
				width: 100%;
				border-collapse: collapse;
				margin-bottom: 20px;
				border: 1px solid #000;
			}
			.content table td {
				padding: 8px;
				border-right: 1px solid #000;
			}
			.content table td:last-child {
				border-right: none;
			}
			.content table tr:last-child td {
				border-bottom: none;
			}
			.content table th {
				padding: 8px;
				border: 1px solid #000;
				background-color: #f2f2f2;
			}
			.footer {
				font-size: 10px;
				margin-top: 20px;
			}
			.text-center {
				text-align: center;
			}
			.text-right {
				text-align: right;
			}
			.text-left {
				text-align: left;
			}
			.bold {
				font-weight: bold;
			}
			.col-30 {
				width: 30%;
			}
			.col-70 {
				width: 70%;
			}
			.col-60 {
				width: 60% !important;
			}
			.col-40 {
				width: 40% !important;
			}
		</style>
	</head>
	<body>
		<div class='header'>
			<img src='{$base64}' alt='Company Logo'>
			<h1>{$objCompany->name}</h1>
			<h2>RUC: {$rucCompany}</h2>
			<h2>Teléfono: {$objParameterTelefono->value}</h2>
			<h2>Dirección: {$objCompany->address}</h2>
		</div>

		<div class='content'>
			<!-- Transaction Info Table -->
			<table>
				<thead>
					<tr>
						<th class='text-left col-30 style='border-right:none'> ORDEN DE PRODUCCION</th>
						<th class='text-right col-70 style='border-left:none'>". $objWorkflowStage ."</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class='bold'>Fecha:</td>
						<td>". explode(" ", $objTM->transactionOn)[0] ."</td>
					</tr>
					<tr>
						<td class='bold'>Orden de Produccion:</td>
						<td>". $objTM->transactionNumber ."</td>
					</tr>
					<tr>
						<td class='bold'>Solicitado Por:</td>
						<td>". $objRequestEmployeeName ."</td>
					</tr>
					<tr>
						<td class='bold'>Elaborado Por:</td>
						<td>". $objSenderEmployeeName ."</td>
					</tr>
					<tr>
						<td class='bold'>Monto Total:</td>
						<td>".$objCurrency->simbol . " " . $objTM->amount ."</td>
					</tr>
					<tr>
						<td class='bold'>Referencia 1:</td>
						<td>". $objTM->reference1 ."</td>
					</tr>
					<tr>
						<td class='bold'>Referencia 2:</td>
						<td>". $objTM->reference2 ."</td>
					</tr>
					<tr>
						<td class='bold'>Referencia 3:</td>
						<td>". $objTM->reference3 ."</td>
					</tr>
					<tr>
						<td class='bold'>Comentario:</td>
						<td>". $objTM->note ."</td>
					</tr>
				</tbody>
			</table>

			<!-- Input Items Table -->
			<table>
				<thead>
					<tr>
						<th colspan='7'>Insumos</th>
					</tr>
					<tr>
						<th>Código</th>
						<th>Nombre</th>
						<th>Cantidad</th>
						<th>Costo Unitario</th>
						<th>Costo Total</th>
						<th>Almacén Origen</th>
						<th>Producto Destino</th>
					</tr>
				</thead>
				<tbody>";
					if ($objTMD) {
						foreach ($objTMD as $detail) {
							if ($detail->itemWarehouseSourceID != null) {
								$html .= "
								<tr>
									<td>{$detail->itemNumber}</td>
									<td>{$detail->itemName}</td>
									<td class='text-right'>{$detail->itemQuantity}</td>
									<td class='text-right'>{$detail->itemUnitaryCost}</td>
									<td class='text-right'>{$detail->itemTotalCost}</td>
									<td>{$detail->itemWarehouseSource}</td>
									<td>". $detail->itemDestinationNumber . " | " . $detail->itemDestinationName ."</td>
								</tr>
								";
							}
						}
					}
			$html .="	</tbody>
			</table>

			<!-- Output Items Table -->
			<table>
				<thead>
					<tr>
						<th colspan='6'>Productos Finales</th>
					</tr>
					<tr>
						<th>Código</th>
						<th>Nombre</th>
						<th>Cantidad</th>
						<th>Costo Unitario</th>
						<th>Costo Total</th>
						<th>Almacén Destino</th>
					</tr>
				</thead>
				<tbody>";
					if ($objTMD) {
						foreach ($objTMD as $detail) {
							if ($detail->itemWarehouseTargetID != null) {
								$html .= "
								<tr>
									<td>{$detail->itemNumber}</td>
									<td>{$detail->itemName}</td>
									<td class='text-right'>{$detail->itemQuantity}</td>
									<td class='text-right'>{$detail->itemUnitaryCost}</td>
									<td class='text-right'>{$detail->itemTotalCost}</td>
									<td>{$detail->itemWarehouseTarget}</td>
								</tr>
								";
							}
						}
					}
				$html .="	</tbody>

				</tbody>
			</table>
		</div>

		<div class='footer'>
			<p class='text-center'>Este documento es generado automáticamente.</p>
		</div>
	</body>
	</html>
	";

    return $html;
}