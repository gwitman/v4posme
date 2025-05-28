<?php
function helper_reporte80mmTransactionMasterElektroClima(
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
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$objParameterLogo->value;
    
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
                          size: 2.7in 50in;                  
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
                            ELECTRODOMESTICOS Y AIRES ACONDICIONADOS
                          </td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:center'>
                            Semáforo de Lista Vista  3c 1/2c al Sur, Frente a Pali Miraflores
                          </td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:center'>
                            Tel: 8617-7707 / 8588-6854
                          </td>
                        </tr>
						<tr>                              
						  <td colspan='3' style='text-align:center'>
							RUC : ".  $rucCompany ."
						  </td>
						</tr>
						<!--
						<tr>
                          <td colspan='3' style='text-align:center'>
                            Boucher La Radial
                          </td>
                        </tr>
						-->
						<tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
						
						";
    

         
          $html = $html."
                        <tr>
                          <td colspan='3' style='text-align:left'>
							FECHA: ".explode(' ',$objTransactionMastser->createdOn)[0]." &nbsp;&nbsp;
                            # ".str_replace("FAC","",strtoupper($objTransactionMastser->transactionNumber))."
                          </td>
                        </tr>
                                
                      
                        ";
						
         
		$html	= $html."<tr>
					  <td colspan=''>
						Vendedor:
					  </td>
					  <td colspan='2'>
						". (strpos($userNickName , "@") === false ? $userNickName : substr($userNickName,0,strpos($userNickName , "@") ) )   ."
					  </td>
					</tr>";
	 
						
     
			
		
		$html	= $html."
					
					<tr>
						<td colspan='1'>
							Tipo:
						</td>
						<td colspan='2'>
							".$causalName."
						</td>
					</tr>
					";
	  
			
			
		  $html	= $html."
						<!--
						<tr>
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
						-->
						";
			
		
					
          $html	= $html."
						<tr  >
						  <td colspan='1' style='border-top: 2px dotted black;border-left: 2px dotted black;' >
							Cliente:
                          <td colspan='2' style='border-top: 2px dotted black;border-right: 2px dotted black;' >
                          </td>
                        </tr>
						
						<tr>
						  <td colspan='3' style='border-left: 2px dotted black;border-right: 2px dotted black;' >							
                            ". ( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName." ".$objEntidadNatural->lastName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>

						<tr >
                          <td colspan='1' style='border-bottom: 2px dotted black;border-left: 2px dotted black;'>
                            RUC:
                          </td>
						  <td colspan='2' style='border-bottom: 2px dotted black;border-right: 2px dotted black;'>
                            ".$objEntidadCustomer->identification."
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
                            ".$objCurrency->simbol." ".number_format($objTransactionMastser->subAmount,2,".",",")."
                          </td>
                        </tr>  
						<!--
						<tr>
                          <td colspan='2'>
                            IVA
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".number_format($objTransactionMastser->tax1,2,".",",")."
                          </td>
                        </tr>
						-->
                        <tr>
                          <td colspan='2'>
                            DESC
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".sprintf("%.2f",$objTransactionMastser->discount)."
                          </td>
                        </tr>    
						<tr>
                          <td colspan='3' style='text-align:right'>
                            _____________
                          </td>
                        </tr>  						
                        <tr>
                          <td colspan='2'>
                            TOTAL
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".number_format($objTransactionMastser->amount,2,".",",")."
                          </td>
                        </tr>
						 <tr>
                          <td colspan='2'>
                            SALDO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".number_format($objTransactionMastser->amount,2,".",",")."
                          </td>
                        </tr>
   
						
                        <tr>
                          <td colspan='2'>
                            RECIBIDO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".number_format(($objTransactionMastser->amount + $objTransactionMasterInfo->changeAmount),2,".",",")."
                          </td>
                        </tr>
                         <tr>
                          <td colspan='2'>
                            CAMBIO
                          </td>
                          <td style='text-align:right'>
                            ".$objCurrency->simbol." ".number_format($objTransactionMasterInfo->changeAmount,2,".",",")."
                          </td>
                        </tr>
						
						
                        <tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>

						<tr>
                          <td colspan='3' style='text-align:center;border: 2px dotted black;'>
                            ***Gracias por su compra***
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
                            01. GARANTIA PARA AIRES ACONDICIONADOS:</br>
							Revise su mercancia por favor, no se aceptan devoluciones.
                          </td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:center'>
							01. GARANTIA PARA ELECTRODOMESTICOS:</br>
                            02. En casos de reclamos sobres servicios técnicos, debe notificar en las próximas 72 horas para ser válido.
                          </td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
						-->
						<tr>
                          <td colspan='3' style='text-align:left'>
							GARANTIA PARA AIRES ACONDICIONADOS</br></br>
                            01. Este producto cuenta con garantía de 12 meses en partes eléctricas apartir de la compra o
							instalación por desperfecto de fabricación.
                          </td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
						<!--
						<tr>
                          <td colspan='3' style='text-align:left'>
                            04. La garantía cubre si el equipo tiene protector de voltaje.
                          </td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:left'>
                            05. Los mantenimientos deben de ser realizados por la empresa en el tiempo que cubre  la garantia por un precio adicional.
                          </td>
                        </tr>
						-->
						<tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:left'>
							GARANTIA PARA ELECTRODOMESTICOS</br></br>
                            02. Esta garantía cubre:</br>
							*Defectos de fabricación en materiales o mano de obra.</br>
							*Reparaciones o remplazos de piezas defectuosas sin costo adicional.</br>
							*Mano de obra y servicios técnicos autorizados.</br>
						  </td>
                        </tr>
						
						<tr>
                          <td colspan='3' style='text-align:center'>
                            &nbsp;
                          </td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:center'>
							___________________
                          </td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:center'>
                            Recibí  Conforme
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
							___________________
                          </td>
                        </tr>
						<tr>
                          <td colspan='3' style='text-align:center'>
							Entregue Conforme
                          </td>
                        </tr>
						
						<!--
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
						
						$texto 		= $key;
						if($rowin > 0 && $colun == 0)
						{
							$cuerpo 	= $cuerpo."<tr >";															
							$cuerpo = $cuerpo."<td colspan='3' >";
							$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$texto;
							$cuerpo = $cuerpo."</td>";								
							$cuerpo = $cuerpo."</tr >";
						}
						else 
						{
							if($colun == 1)
							$cuerpo 	= $cuerpo."<tr >";								
							
							if($colun == 1)
							$cuerpo = $cuerpo."<td colspan='1' >";
							else 
							$cuerpo = $cuerpo."<td colspan='2' >";
						
							$cuerpo = $cuerpo.$confiDetalle[$colun]["prefix_row_data"]." ".$texto;
							$cuerpo = $cuerpo."</td>";								
							
							if($colun == 2)
							$cuerpo 	= $cuerpo."</tr ></br>";
						}
							
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