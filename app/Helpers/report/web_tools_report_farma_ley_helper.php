<?php
function helper_reporte80mmTransactionMasterFarmaLey(
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
                            <img  src='".$base64."' width='225'  >
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
                            FACTURA # ".strtoupper($objTransactionMastser->transactionNumber)."
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
						
          $html	= $html."
						<tr>
						  <td colspan='1'>
							Cliente:
                          <td colspan='2'>
							". ( $objTransactionMasterInfo->referenceClientName == "" ?   $objEntidadNatural->firstName." ".$objEntidadNatural->lastName  :  $objTransactionMasterInfo->referenceClientName)  ."
                          </td>
                        </tr>
						
						<tr>
                          <td colspan=''>
                            Cedula:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->identification."
                          </td>
                        </tr>
						<tr>
                          <td colspan=''>
                            Telefono:
                          </td>
						  <td colspan='2'>
                            ".$objEntidadCustomer->phoneNumber."
                          </td>
                        </tr>
						";
			
			
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
                            ".$objCompany->address."
                          </td>
                        </tr>

                        <tr>
                          <td colspan='3' style='text-align:center'>
                            ".$objParameterTelefono->value."
                          </td>
                        </tr>
						
						".getBehavio($objCompany->type,"web_tools_report_helper","reporte80mmTransactionMaster_Devolucion","")."

                      
                        

                                
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

function helper_reporteA4mmTransactionMasterInputUnpostFarmaLey(
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
											".getBehavio($objCompany->type,"app_invoice_billing","lblRptInvoiceRuc","Edificio Delta RUC: 001-300359-0017A")."
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
	
	for($i = 0 ; $i < 150 ; $i++)
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
					<td style='text-align:right;width:70px' >".$objCurrency->simbol." 0.00". /*number_format(round($objDetail[$i]->unitaryCost,2),2,".",",")*/  "</td>
					<td style='text-align:right;width:70px' >".number_format(round($objDetail[$i]->quantity,2),2,".",",")."</td>
					<td style='text-align:right;width:70px' >".$objCurrency->simbol." 0.00</td>
					<td style='text-align:right;width:70px' >".$objCurrency->simbol." 0.00". /*number_format(round($objDetail[$i]->cost,2),2,".",",").*/ "</td>
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
				  <!--
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
										<td colspan='2'>*=".getBehavio($objCompany->type,"app_invoice_billing","lblRptInvoiceOpcion1","")."</td>
									</tr>									
								</table>
							</td>
							
						</tr>
						
						
				   </table>
				   -->";
				   
				   
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
								".getBehavio($objCompany->type,"app_invoice_billing","lblRptInvoiceOpcion2","TU BIENESTAR EN LAS MEJORES MANOS!")."
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

function helper_reporteA4mmTransactionMasterInputUnpostFarmaLeyOnlyQuantity(
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
											Edificio Delta RUC: 001-300359-0017A
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
								La salud es lo mas importante!
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

?>