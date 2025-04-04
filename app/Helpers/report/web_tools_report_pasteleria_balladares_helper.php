<?php
function helper_reporte80mmInventoryInputPasteleriaBalladares(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTM,
    $objWorkflowStatusName,
    $objTMD,
    $objWarehouse
): string
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$objParameterLogo->value;

    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    // Crear un objeto DateTime a partir de la cadena
    $createdOn = new DateTime($objTM->createdOn);
    $fecha 	= $createdOn->format('Y-m-d');
    $hora 	= $createdOn->format('H:i A');
	
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
                            size: 3.15in 15in;                  
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
                            ".($objCompany->name)."
                            </td>
                        </tr>    
                        <tr>
                            <td colspan='3' style='text-align:center'>
                            ".($objCompany->address)."
                            </td>
                        </tr>  
						      
						<tr>
                          <td colspan='3' style='text-align:center' >
                            &nbsp;
                          </td>
                        </tr>
						<tr>
                            <td colspan='3' style='text-align:center'>
								Otras entrada de inventario
                            </td>
                        </tr>
						
                        <tr>
                            <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTM->transactionNumber)."
                            </td>
                        </tr>
                                
						<tr>
                          <td colspan='3' style='text-align:center' >
                            &nbsp;
                          </td>
                        </tr>
						
						<tr style='text-align:center'>
                            <td>Fecha</td>
                            <td>".explode(" ",$objTM->createdOn)[0]."</td>
                        </tr>
						
                       
                        <tr style='text-align:center'>
                            <td>Estado</td>
                            <td>".$objWorkflowStatusName."</td>
                        </tr>
						
						<tr style='text-align:center'>
                            <td>Bodega</td>
                            <td colspan='2' >".$objWarehouse->name."</td>
                        </tr>
												
						<tr>
                          <td colspan='3' style='text-align:center' >
                            &nbsp;
                          </td>
                        </tr>
						
                        <tr style='text-align:center'>
                            <td colspan='3' style='text-align: center'>Descripcion</td>
                        </tr>
                        <tr style='text-align:center'>
                            <td colspan='3' style='text-align: center'>".$objTM->note."</td>
                        </tr>
						
						<tr>
                          <td colspan='3' style='text-align:center' >
                            &nbsp;
                          </td>
                        </tr>
						
						<tr style='text-align:center'>
                            <td colspan='3' style='text-align: center'>Detalle</td>
                        </tr>
						
						<tr>
                          <td colspan='3' style='text-align:center' >
                            &nbsp;
                          </td>
                        </tr>
						
						";
						
			
			foreach($objTMD as $item)
			{
				$html = $html."
					<tr>
					  <td colspan='3' style='text-align:left' >
						*".$item->itemName."
					  </td>
					</tr>
					<tr>
					  <td colspan='3' style='text-align:left' >
						unidades # ".round($item->quantity,2)."
					  </td>
					</tr>
				";
			}
						
			$html = $html."
						<tr>
                          <td colspan='3' style='text-align:center' >
                            &nbsp;
                          </td>
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

function helper_reporte80mmInventoryOutputPasteleriaBalladares(
    $titulo,
    $objCompany,
    $objParameterLogo,
    $objTM,
    $objWorkflowStatusName,
    $objTMD,
    $objWarehouse
): string
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$objParameterLogo->value;

    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    // Crear un objeto DateTime a partir de la cadena
    $createdOn = new DateTime($objTM->createdOn);
    $fecha 	= $createdOn->format('Y-m-d');
    $hora 	= $createdOn->format('H:i A');
	
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
                            size: 3.15in 15in;                  
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
                            ".($objCompany->name)."
                            </td>
                        </tr>    
                        <tr>
                            <td colspan='3' style='text-align:center'>
                            ".($objCompany->address)."
                            </td>
                        </tr>  
						        
								
						<tr>
                          <td colspan='3' style='text-align:center' >
                            &nbsp;
                          </td>
                        </tr>
						<tr>
                            <td colspan='3' style='text-align:center'>
								Otras salidas de inventario
                            </td>
                        </tr>
                        <tr>
                            <td colspan='3' style='text-align:center'>
                            # ".strtoupper($objTM->transactionNumber)."
                            </td>
                        </tr>
                                
						<tr>
                          <td colspan='3' style='text-align:center' >
                            &nbsp;
                          </td>
                        </tr>
						
						<tr style='text-align:center'>
                            <td>Fecha</td>
                            <td>".explode(" ",$objTM->createdOn)[0]."</td>
                        </tr>
						
                       
                        <tr style='text-align:center'>
                            <td>Estado</td>
                            <td>".$objWorkflowStatusName."</td>
                        </tr>
						
						<tr style='text-align:center'>
                            <td>Bodega</td>
                            <td colspan='2' >".$objWarehouse->name."</td>
                        </tr>
												
						<tr>
                          <td colspan='3' style='text-align:center' >
                            &nbsp;
                          </td>
                        </tr>
						
                        <tr style='text-align:center'>
                            <td colspan='3' style='text-align: center'>Descripcion</td>
                        </tr>
                        <tr style='text-align:center'>
                            <td colspan='3' style='text-align: center'>".$objTM->note."</td>
                        </tr>
						
						<tr>
                          <td colspan='3' style='text-align:center' >
                            &nbsp;
                          </td>
                        </tr>
						
						<tr style='text-align:center'>
                            <td colspan='3' style='text-align: center'>Detalle</td>
                        </tr>
						
						<tr>
                          <td colspan='3' style='text-align:center' >
                            &nbsp;
                          </td>
                        </tr>
						
						";
						
			
			foreach($objTMD as $item)
			{
				$html = $html."
					<tr>
					  <td colspan='3' style='text-align:left' >
						*".$item->itemName."
					  </td>
					</tr>
					<tr>
					  <td colspan='3' style='text-align:left' >
						unidades # ".round($item->quantity,2)."
					  </td>
					</tr>
				";
			}
						
			$html = $html."
						<tr>
                          <td colspan='3' style='text-align:center' >
                            &nbsp;
                          </td>
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
?>