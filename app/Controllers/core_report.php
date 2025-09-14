<?php

namespace App\Controllers;

class core_report extends _BaseController
{

    public function show(): string
    {
        try{
            $segments               = $this->uri->getSegments();
            $key                    = helper_SegmentsValue($segments,'key');
            $findReporting          = $this->Reporting_Model->get_rowByKey($key);

            if ($findReporting->needAutenticated == 1){
                //AUTENTICACION
                if(!$this->core_web_authentication->isAuthenticated())
                    throw new \Exception(USER_NOT_AUTENTICATED);
            }

            $dataSession		            = $this->session->get();
            $findReportingParameter         = $this->Reporting_Parameter_Model->get_rowByReportID($findReporting->reportID);
            $dataView['reporting']          = $findReporting;
            $dataView["companyID"]          = $dataSession['company']->companyID;
            $dataView["userID"]             = $dataSession['user']->userID;			
            $dataView['reportingParameter'] = $findReportingParameter;
			$dataView['segments']			= $segments;

            return view("core_report/view_body",$dataView);//--finview-r
        }
        catch(\Exception $ex){
            return ($ex->getMessage().'; <br>Linea: ' .$ex->getLine());
        }
    }

    public function process()
    {
        try {
            $reportID       = $this->request->getPost('reportID');
            $findReporting  = $this->Reporting_Model->get_rowByPK($reportID);
			

            if ($findReporting->needAutenticated == 1){
                //AUTENTICACION
                if(!$this->core_web_authentication->isAuthenticated())
                    throw new \Exception(USER_NOT_AUTENTICATED);
            }
            $dataSession        = $this->session->get();
            $reportResult       = $this->Reporting_Result_Model->get_rowByReportID($findReporting->reportID);
            $groupReportResult  = [];
			$reportType			= 'html';

            foreach ($reportResult as $fila) {
                $numero                         = $fila->resultNumber;
                $groupReportResult[$numero][]   = $fila;
            }
            $findReportingParameter = $this->Reporting_Parameter_Model->get_rowByReportID($findReporting->reportID);
            $filtros                = [];
            foreach ($findReportingParameter as $item)
			{
                if (
					$item->name == "@prTypeReport"
                    || $item->name == "@prCompanyID"
                    || $item->name == "@prTokenID"
                    || $item->name == "@prUserID") continue;
					
                $valor = ltrim($item->name, '@');
                if ($valor == 'prCustomerEntityID')
				{
                    $filtros[$item->display] = $this->request->getPost('txtDescription');
                }
				elseif ($item->type == 'comboboxfull')
				{
                    $queryResult        = $this->Bd_Model->executeRender($item->datasource, '');
                    $id                 = $this->request->getPost($valor);
                    $valorEncontrado    = '';
                    if (count($queryResult) > 0) {
                        foreach ($queryResult as $q) {
                            if ($q['key'] == $id) {
                                $valorEncontrado = $q['value'];
                                break;
                            }
                        }
                    }
                    $filtros[$item->display] = $valorEncontrado;
                }
				elseif ($valor == 'prTypeReport')
				{
					$reportType				 = $item->value;
				}
                else
				{
                    $filtros[$item->display] = $this->request->getPost($valor);
                }

            }
            
			$query      = $findReporting->queryi;
            $pattern    = "/@(\w+)/";
            //extraemos los nombres de los parametros q pertenecen a la consulta
            preg_match_all($pattern, $query, $matches);
            $parametrosEnConsulta   = $matches[1];
            $params                 = [];
            foreach ($parametrosEnConsulta as $paramName) {
                $valor          = $this->request->getPost($paramName);
                $params[]       = $valor ?? null;
            }

			
			$isCall = substr($query, 0, 3);    
			//Es un procedimiento
			if(stripos($isCall, 'call') !== false)
			{
				$query   = preg_replace($pattern, "?", $query);					
				$result  = $this->Bd_Model->executeRenderMultipleNative($query, $params);
			}
			//Es una consulta
			else 
			{
				$newPost = [];
				foreach ($this->request->getPost() as $clave => $valor) {
					$newPost['@' . $clave] = "'".$valor."'";
				}

				// Reemplazar newPost 
				$query 	 = strtr($query, $newPost);								
				$result  = $this->Bd_Model->executeRenderMultipleNative($query, $params);
			}
	
	
            
            $dataView['params']             	= $params;
            $dataView['objCompany']         	= $dataSession['company'];
            $dataView['objDetail']          	= $result;
            $dataView['reportResult']       	= $reportResult;
            $dataView['groupReportResult']  	= $groupReportResult;
            $dataView['reporting']          	= $findReporting;
            $dataView["companyID"]          	= $dataSession['company']->companyID;
            $dataView["userID"]             	= $dataSession['user']->userID;
            $dataView['reportingParameter'] 	= $findReportingParameter;
            $dataView['filtros']            	= $filtros;
			
			// convertir respuesta a csv
			$typeReport                     	= $this->request->getPost('prTypeReport');
			$companyID							= $dataView["companyID"];
            
			if ($typeReport === 'csv') 
			{
				$objParameterDeliminterCsv	 	= $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$companyID);
				$objParameterDeliminterCsv	 	= $objParameterDeliminterCsv->value;
				$rows 							= $dataView['objDetail'];
				$csvContent 					= helper_toCsv($rows, trim(strtolower($objParameterDeliminterCsv)));

				// Enviar CSV
				return $this->response
					->setHeader('Content-Type', 'text/csv; charset=UTF-8')
					->setHeader('Content-Disposition', 'attachment; filename="reporte.csv"')
					->setBody($csvContent);
			}
			elseif (trim(strtolower($typeReport)) === 'ftp_pedidos_ya') 
			{
                // 1. Generar el CSV
				$objParameterDeliminterCsv	 	= $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$companyID);
				$objParameterDeliminterCsv	 	= $objParameterDeliminterCsv->value;
				$rows 							= $dataView['objDetail'];
				$csvContent 					= helper_toCsv($rows, trim(strtolower($objParameterDeliminterCsv)));
				
				// obtenemos parametros para la conexion ftp del cliente
				$ftpMerchatId					= $this->core_web_parameter->getParameter("INVENTORY_SEND_SFTP_PEDIDOSYA_MERCHATID",$companyID);
				$ftpMerchatId					= $ftpMerchatId->value;				
				$ftpIp							= $this->core_web_parameter->getParameter("INVENTORY_SEND_SFTP_PEDIDOSYA_IP",$companyID);
				$ftpIp							= $ftpIp->value;				
				$ftpUsername					= $this->core_web_parameter->getParameter("INVENTORY_SEND_SFTP_PEDIDOSYA_USERNAME",$companyID);
				$ftpUsername					= $ftpUsername->value;				
				$ftpPassword					= $this->core_web_parameter->getParameter("INVENTORY_SEND_SFTP_PEDIDOSYA_PASSWORD",$companyID);
				$ftpPassword					= $ftpPassword->value;				
				$ftpPort						= $this->core_web_parameter->getParameter("INVENTORY_SEND_SFTP_PEDIDOSYA_PORT",$companyID);
				$ftpPort						= $ftpPort->value;	
				$ftpDir							= $this->core_web_parameter->getParameter("INVENTORY_SEND_SFTP_PEDIDOSYA_DIRECTORY_TARGET",$companyID);
				$ftpDir							= $ftpDir->value;
				
				// Validacion si alguno de los campos esta vacio no hay que dejar pasar la solicitud
				if (!$ftpMerchatId or !$ftpIp or !$ftpUsername or !$ftpPassword or !$ftpPort)
				{
					return helper_notificationPage("Fallo envio por SFTP","No cuenta con las credenciales necesarias para realizar esta accion","","",true,"red");
				}				
				$fileName						= 'catalogo_' . $ftpMerchatId . '.csv';
				$resultado 						= helper_sendFtp($csvContent, $ftpMerchatId, $ftpIp, $ftpUsername, $ftpPassword, $ftpPort, $fileName, $ftpDir);
				//return $resultado;
				if (trim(strtolower($resultado)) == 'exitoso') 
				{
					return helper_notificationPage("Envio por SFTP", "El envio del archivo fue ralizado con exito","","",true);
				} 
				else 
				{
					return helper_notificationPage("Fallo envio por SFTP", $resultado,"","",true,"red");
				}
			}
			else
			{
                // Por defecto, html
                return view("core_report/view_a_disemp",$dataView);//--finview-r
			}
        }catch(\Exception $ex){
            return ($ex->getMessage().'; <br>Linea: ' .$ex->getLine());
        }
    }
}