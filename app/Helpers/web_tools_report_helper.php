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
								<img width="120" height="110" 						
									style="
										width: 120px;
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
                            Cliente:
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
			
		
						
          $html	= $html."<tr>
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
    $confiDetalle, /*configuracion del cuerpo*/
    $arrayDetalle, /*cuerpo del documento*/
    $objParameterTelefono, /*objeto parametro telefono*/
	$statusName, /*estado del documento*/
	$causalName /*nombre del causal*/
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
                          <td colspan=''>
                            Cliente:
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
						";
		
						
          $html	= $html."<tr>
                          <td colspan='3'>
                            ".$objEntidadNatural->firstName."
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

