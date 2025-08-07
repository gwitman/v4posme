<?php
function helper_reporte80mmTransactionMasterCaracenos(
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
                          size: 2.7in 11in;
                          margin-top:0px;
                          margin-left:5px;
                          margin-right:20px;
                        }
                        table{
                          font-size: medium ;
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
                            RUC: 042-230476-0000T
                          </td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                        
						";
    

        
    
          $html = $html."
		  
						<tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper($objCompany->address)."
                          </td>
                        </tr>   
						<tr>
                          <td colspan='3' style='text-align:center'>
                            LEON
                          </td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:center'>
                            +(505) 8505-7109
                          </td>
                        </tr>						
                                
                         <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
						";
						
            
		 $html	= $html."<tr>
                          <td colspan=''>
                            EMPLEADO:
                          </td>
						  <td colspan='2'>
                            PROPIETARIO
                          </td>
                        </tr>
						<tr>
                          <td colspan=''>
                            CLIENTE:
                          </td>
						  <td colspan='2'>
                            ". ( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName." ".$objEntidadNatural->lastName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>
						
						 <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
						
						";
		  
		
			
						
          $html	= $html."
                        
						<tr>
                          <td colspan='3' style='text-align:left'>
                            ----------------------------
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
                            ----------------------------
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
                            EFECTIVO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->amount)."
                          </td>
                        </tr>
						
						<tr>
                          <td colspan='3' style='text-align:left'>
                            ----------------------------
                          </td>
                        </tr>
   
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
                        
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper("No somos los únicos pero marcamos la diferencia con todos")."
                          </td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:center'>
                            ".strtoupper("Gracias por su compra")."
                          </td>
                        </tr>
						
						 <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
						
						<tr>
                          <td colspan='3' style='text-align:left'>
                            ".(new DateTime($objTransactionMastser->createdOn))->format('Y-m-d h:i a')."
							</br>
							".strtoupper($objTransactionMastser->transactionNumber)."
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
						$cuerpo = $cuerpo.strtoupper($confiDetalle[$colun]["prefix_row_data"]." ". str_replace("-comand-new-row", "", $key ));
						$cuerpo = $cuerpo."</td>";
						
						$cuerpo 		= $cuerpo."</tr >";	
						break;
											
					}	
					else
					{	
						
						if($rowin > 0 && $colun == 0)
						$cuerpo 	= $cuerpo."<tr >";								
						
						$cuerpo = $cuerpo."<td style=".$confiDetalle[$colun]["style_row_data"]." colspan='".$confiDetalle[$colun]["colspan_row_data"]."' >";
						$cuerpo = $cuerpo.strtoupper($confiDetalle[$colun]["prefix_row_data"]." ".$key);
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


?>