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
	
		$result = 
		'
		<table style="width:'.$width.'">
			<thead>
				<tr>
					<th colspan="'.$column.'" >'.date("Y-m-d H:i:s").' '.$firma.' posMe</th>
				</tr>
				
			</thead>
		</table>
	
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
	
	
	
	foreach($configColumn as $key => $value){
		$cantidadColumnas = $cantidadColumnas + 1;
		$configColumn[$key]["Titulo"] 				= array_key_exists("Titulo",$value) ? $value["Titulo"] : "" ;
		$configColumn[$key]["TituloFoot"] 			= array_key_exists("TituloFoot",$value) ? $value["TituloFoot"] : "" ;
		$configColumn[$key]["FiledSouce"] 			= array_key_exists("FiledSouce",$value) ? $value["FiledSouce"] : "" ;
		$configColumn[$key]["Colspan"] 				= array_key_exists("Colspan",$value) ? $value["Colspan"] : "1" ;
		$configColumn[$key]["Formato"] 				= array_key_exists("Formato",$value) ? $value["Formato"] : "" ;
		$configColumn[$key]["Total"] 				= array_key_exists("Total",$value) ? $value["Total"] : False ;
		$configColumn[$key]["Alineacion"] 			= array_key_exists("Alineacion",$value) ? $value["Alineacion"] : "Left" ;
		$configColumn[$key]["TotalValor"] 			= array_key_exists("TotalValor",$value) ? $value["TotalValor"] : 0 ;
		$configColumn[$key]["CantidadRegistro"]		= array_key_exists("CantidadRegistro",$value) ? $value["CantidadRegistro"] : 0 ;
		$configColumn[$key]["FiledSoucePrefix"] 	= array_key_exists("FiledSoucePrefix",$value) ? $value["FiledSoucePrefix"] : "" ;
		$configColumn[$key]["Width"] 				= array_key_exists("Width",$value) ? $value["Width"] : "auto" ;
		$configColumn[$key]["AutoIncrement"] 		= array_key_exists("AutoIncrement",$value) ? $value["AutoIncrement"] : False ;
		$configColumn[$key]["IsUrl"] 				= array_key_exists("IsUrl",$value) ? $value["IsUrl"] : False ;
		$configColumn[$key]["FiledSouceUrl"] 			= array_key_exists("FiledSouceUrl",$value) ? $value["FiledSouceUrl"] : "" ;
		$configColumn[$key]["Url"] 						= array_key_exists("Url",$value) ? $value["Url"] : "" ;
		$configColumn[$key]["FiledSoucePrefixCustom"] 	= array_key_exists("FiledSoucePrefixCustom",$value) ? $value["FiledSoucePrefixCustom"] : "" ;
		$configColumn[$key]["Promedio"] 				= array_key_exists("Promedio",$value) ? $value["Promedio"] : False ;
		
		
		$configColumn[$key]["Alineacion"] 			= $configColumn[$key]["Formato"] == "Number"? "Right": "Left";
	}
	
	
	$widthTemporal = 0;
	$table  = "";
	$table2 = "";
	
	//Armar encabezado
	foreach($configColumn as $key => $value ){
		$widthTemporal = $widthTemporal + str_replace("px","",$value['Width']);
		
		$table2 = $table2.'<th nowrap style="text-align:left;width:'.$value['Width'].'" colspan="'.$value['Colspan'].'" class="border"  >'.$value['Titulo'].'</th>';
	}
	$widthTemporal = $widthTemporal."px";
	
	
	//Armar titulo
	$table1 =  
	'<table style="
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
	$table =  $table.
				$table1.				
				
				'<tr style="background-color:#'.$backgournd.';color:'.$color.';">'.
				$table2.
				'</tr>';
		
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
					
					//log_message("ERROR","Sumarizar totales");
					//log_message("ERROR",$valueField);
					//log_message("ERROR",print_r($configColumn[$key]["TotalValor"],true));
					
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
					<td style='text-align:left;' >".$objDetail[$i]->itemNameLog."</td>
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
					<td style='text-align:left;' >".$objDetail[$i]->itemNameLog."</td>
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
						<td></td>
						<td style='text-align:center;".$border_bottom.";width:160px' >Monto : C$ ".number_format($objTransactionMastser->amount,2,'.',',')."</td>
						<td></td>
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
                            # ".strtoupper($objTransactionMastser->transactionNumber)."
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
						
						
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ***Gracias por su compra***
                          </td>
                        </tr>

                      
                        <tr>
                          <td colspan='3' style='text-align:center' >
                            sistema +(505) 8712-5827
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
							  <td  style='text-align:left;width: 25px;' >Paciente</td>
							  <td  style='text-align:left;width: auto;' >".$objEntidadNatural->firstName."</td>
							  <td  style='text-align:left;width: 25px;' >Fecha:</td>
							  <td  style='text-align:left;width: 160px;' >".$objTransactionMastser->statusIDChangeOn."</td>
							  <td  style='text-align:left;width: 25px;' >Edad:</td>
							  <td  style='text-align:left;width: 25px;' >".$objEdad->name."</td>
							  <td  style='text-align:left;width: 25px;' >Sexo:</td>
							  <td  style='text-align:left;width: 25px;' >".$objSexo->name."</td>
							</tr
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
				
				$html = $html."<table style='width:100%;border-collapse: collapse'>";
				$html = $html."<tr style='background-color: blanchedalmond;' ><td colspan='".($cant * 1)."' style='text-align:center;font-weight:bold'  >".strtoupper($fila)."</td></tr>";				
				$html = $html."<tr>";
				foreach($columnas as $columna)
				{
					
					$html = $html."<td style='vertical-align:top'>";
					$valores 		= array_filter($objTransactionMasterDetail , function($k) use ($fila,$columna)
					{										
						return $k->description == $fila && $k->reference1 == $columna;
					});
					$html = $html."<table style='width:100%;border-collapse: collapse' >";					
					$html = $html."<tr style='background-color: blanchedalmond;' ><td colspan='3' style='text-align:left;;font-weight:bold' >".strtoupper($columna)."</td></tr>";
					foreach($valores as $valor)
					{
						$valor->display = strpos($valor->display,"****")  ?  str_replace("****","</br>",$valor->display) : $valor->display."</br>&nbsp;"   ;
						
						$valor->display = strlen($valor->display) > 20 ? substr($valor->display,0,strpos($valor->display," "))."</br>".substr($valor->display,strpos($valor->display," "),strlen($valor->display)) : $valor->display;  
						$valor->display = substr_count($valor->display,"</br>") > 1 ? str_replace("</br>&nbsp;","",$valor->display) : $valor->display; 
						$valor->name 	= strlen($valor->name) > 20 ? substr($valor->name,0,strpos($valor->name," "))."</br>".substr($valor->name,strpos($valor->name," "),strlen($valor->name)) : $valor->name;  
						
						$html = $html."<tr  >";
							$html = $html."<td style='text-align:left;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse; '>".$valor->name."</td>";
							$html = $html."<td style='text-align:left;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse;'>".$valor->reference3."</td>";
							$html = $html."<td style='text-align:right;vertical-align:top;border-bottom: black solid 1px;border-collapse: collapse;color:blue'>".$valor->display."</td>";
						$html = $html."</tr>";
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