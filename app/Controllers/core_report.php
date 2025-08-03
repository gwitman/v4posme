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

            return view("core_report/view_body",$dataView);//--finview-r
        }
        catch(\Exception $ex){
            return ($ex->getMessage().'; <br>Linea: ' .$ex->getLine());
        }
    }

    public function process(): string
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
					$newPost['@' . $clave] = $valor;
				}

				// Reemplazar newPost
				$query 	 = strtr($query, $newPost);				
				$result  = $this->Bd_Model->executeRenderMultipleNative($query, $params);
			}
	
	
            
            $dataView['params']             = $params;
            $dataView['objCompany']         = $dataSession['company'];
            $dataView['objDetail']          = $result;
            $dataView['reportResult']       = $reportResult;
            $dataView['groupReportResult']  = $groupReportResult;
            $dataView['reporting']          = $findReporting;
            $dataView["companyID"]          = $dataSession['company']->companyID;
            $dataView["userID"]             = $dataSession['user']->userID;
            $dataView['reportingParameter'] = $findReportingParameter;
            $dataView['filtros']            = $filtros;

            return view("core_report/view_a_disemp",$dataView);//--finview-r
        }catch(\Exception $ex){
            return ($ex->getMessage().'; <br>Linea: ' .$ex->getLine());
        }
    }
}