<?php
function helper_reporte80mmTransactionMasterDivas(
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
                          size: 2.7in 11in;                  
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
			$html	= $html."
						<tr>
                          <td colspan=''>
                            Formato:
                          </td>
						  <td colspan='2'>
                             [[FORMATO]]
                          </td>
                        </tr>
						<tr>
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
    
	
    
    $html 			= str_replace("[[DETALLE]]", $cuerpo, $html);
	$html_copia 	= $html;
	$html_original 	= $html;
	
	
	if($statusName != "APLICADA")
	{
		return $html;
	}
	else 
	{
		$html_copia 	= str_replace("[[FORMATO]]","COPIA",$html_copia);
		$html_original 	= str_replace("[[FORMATO]]","ORIGINAL",$html_original);
		$resultado 		= $html_original."<div style='page-break-before:always;' ></div>".$html_copia;
		return $resultado;
	}
	
}

?>