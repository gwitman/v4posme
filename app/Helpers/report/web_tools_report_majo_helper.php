<?php
function helper_reporte80mmSalesCommission(
    $dataView
): string
{
    $path    = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-logo-micro-finanza.jpg';

    $type    = pathinfo($path, PATHINFO_EXTENSION);
    $data    = file_get_contents($path);
    $base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
	
    $html    = "";
    $html    = "
                    <!DOCTYPE html>
                    <html lang='en'>
                    <title>REPORTE DE COMISIONES</title>
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
                            ".($dataView["objCompany"]->name)."
                            </td>
                        </tr>    
                        <tr>
                            <td colspan='3' style='text-align:center'>
                            ".($dataView["objCompany"]->address)."
                            </td>
                        </tr>  
						      
						<tr>
                          <td colspan='3' style='text-align:center' >
                            &nbsp;
                          </td>
                        </tr>
						<tr>
                            <td colspan='3' style='text-align:center'>
								DETALLE DE COMISSIONES
                            </td>
                        </tr>
						<tr>
                            <td colspan='3' style='text-align:center'>
								DEL ".($dataView["objStartOn"])." AL  ".($dataView["objEndOn"])."
                            </td>
                        </tr>
										
						<tr>
                          <td colspan='3' style='text-align:center' >
                            &nbsp;
                          </td>
                        </tr>
						
						
						";
						
			$total = 0;
			foreach($dataView["objDetail"] as $item)
			{
				
				$html = $html."					
					<tr>
					  <td colspan='3' style='text-align:left' >
						precio# ".round($item["amount"],2)." 
					  </td>
					</tr>
					<tr>
					  <td colspan='3' style='text-align:left' >
						cant# ".round($item["quantity"],2)."   comision: ".round($item["amountCommision"],2)." 
					  </td>
					</tr>					
					<tr>
					  <td colspan='3' style='text-align:left' >
						*".$item["itemNameLog"]."
					  </td>
					</tr>
					<tr>
					  <td colspan='3' style='text-align:left' >
						------------------
					  </td>
					</tr>
				";
				$total = $total + $item["amountCommision"];
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
                                    TOTAL C$: ".round($total,2)."
                                </div>
                            </td>
                        </tr>
						
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