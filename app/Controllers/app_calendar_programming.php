<?php

namespace App\Controllers;

use App\Controllers\_BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

class app_calendar_programming extends _BaseController
{
    function delete(): ResponseInterface
    {
        try {
            $dataSession = $this->session->get();
            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited) throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "delete", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE) throw new \Exception(NOT_ALL_INSERT);
            }

            $this->core_web_permission->getValueLicense($dataSession["user"]->companyID, get_class($this) . "/" . "index");

            $datos  = $this->request->getJSON(true);
            $id     = str_replace('REM','', $datos["idevent"]);
            $this->Remember_Model->delete_app_posme($id);

            $result = ['status' => 'success', 'message' => 'Datos eliminados correctamente', 'code' => $datos["idevent"]];
            return $this->response->setJSON($result);
        } catch (\Exception $exception) {
            $result = ['status' => 'error', 'message' => $exception->getLine().$exception->getMessage(), 'code' => 400];
            return $this->response->setJSON($result);
        }
    }

    function updateElement($dataSession, $datos): ResponseInterface
    {
        try {
            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited) throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE) throw new \Exception(NOT_ALL_INSERT);
            }

            $this->core_web_permission->getValueLicense($dataSession["user"]->companyID, get_class($this) . "/" . "index");
            $id                             = str_replace('REM','', $datos["id"]);
            $remember['title']              = $datos['title'];
            $remember['createdOn']          = $datos['start'];
            $remember['tagID']              = $datos['tagID'];
            $remember["description"]        = $datos['descripcion'];
            $remember["lastNotificationOn"] = date('c');

            $code   = $this->Remember_Model->update_app_posme($id, $remember);
            $result = ['status' => 'success', 'message' => 'Datos guardados correctamente', 'code' => $code];
            return $this->response->setJSON($result);
        } catch (\Exception $exception) {
            $result = ['status' => 'error', 'message' => $exception->getMessage(), 'code' => 400];
            return $this->response->setJSON($result);
        }
    }

    function insertElement($dataSession, $datos): ResponseInterface
    {
        try {
            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited) throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE) throw new \Exception(NOT_ALL_INSERT);
            }

            $branchID 								= $dataSession["user"]->branchID;
            $roleID 								= $dataSession["role"]->roleID;
            $companyID 								= $dataSession["user"]->companyID;

            $this->core_web_permission->getValueLicense($dataSession["user"]->companyID, get_class($this) . "/" . "index");

            $remember['companyID']          = $dataSession["user"]->companyID;
            $remember['title']              = $datos['title'];
            $this->core_web_auditoria->setAuditCreated($remember, $dataSession, $this->request);
            $remember['createdOn']          = $datos['start'];
            $remember['tagID']              = $datos['tagID'];
            $remember["day"]                = 1; //un dia
            $remember["statusID"]           = $this->core_web_workflow->getWorkflowInitStage("tb_remember","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;
            $remember["description"]        = $datos['descripcion'];
            $remember["lastNotificationOn"] = date('c');
            $remember["isTemporal"]         = 0;
            $remember["leerFile"]           = 0;
            $remember["period"]             = 2732; //Diario
            $remember["isActive"]           = 1;
            $code = $this->Remember_Model->insert_app_posme($remember);

            $result = ['status' => 'success', 'message' => 'Datos guardados correctamente', 'code' => $code];
            return $this->response->setJSON($result);
        } catch (\Exception $exception) {
            $result = ['status' => 'error', 'message' => $exception->getMessage(), 'code' => 400];
            return $this->response->setJSON($result);
        }
    }

    function save($mode = "")
    {
        $mode = helper_SegmentsByIndex($this->uri->getSegments(), 1, $mode);
        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated()) throw new \Exception(USER_NOT_AUTENTICATED);

            $dataSession = $this->session->get();
            $datos = $this->request->getJSON(true);
            //Guardar o Editar Registro
            if ($mode == "new") {
                return $this->insertElement($dataSession, $datos);
            } else if ($mode == "edit") {
                return $this->updateElement($dataSession, $datos);
            }

            $result = ['status' => 'error', 'message' => "El modo de operacion no es correcto (new|edit)", 'code' => 400];
            return $this->response->setJSON($result);

        } catch (\Exception $ex) {
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }

            $data["session"]    = $dataSession;
            $data["exception"]  = $ex;
            $data["urlLogin"]   = base_url();
            $data["urlIndex"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
            $data["urlBack"]    = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView         = view("core_template/email_error_general", $data);

            return $resultView;
        }
    }

    

    function find($id): ResponseInterface
    {
        try {
            if (!$this->core_web_authentication->isAuthenticated()) throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {

                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited) throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE) throw new \Exception(NOT_ACCESS_FUNCTION);

            }
            $id     = str_replace('REM','', $id);
            $result = $this->Remember_Model->get_rowByPK($id);
            return $this->response->setJSON($result);
        } catch (\Exception $exception) {
            $result = ['status' => 'error', 'message' => $exception->getMessage(), 'code' => 400];
            return $this->response->setJSON($result);
        }
    }

    function index()
    {
        try {

            if (!$this->core_web_authentication->isAuthenticated()) throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {

                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited) throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if ($resultPermission == PERMISSION_NONE) throw new \Exception(NOT_ACCESS_FUNCTION);
            }

            $dataView["objListTag"] 		= $this->Tag_Model->get_rows();
            //Renderizar Resultado
            $dataSession["notification"]    = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]         = $this->core_web_notification->get_message();
            $dataSession["head"]            = /*--inicio view*/ view('app_calendar_programming/index_head', $dataView);//--finview
            $dataSession["body"]            = /*--inicio view*/ view('app_calendar_programming/index_body', $dataView);//--finview
            $dataSession["footer"]          = /*--inicio view*/ view('app_calendar_programming/index_footer', $dataView);//--finview
            $dataSession["script"]          = /*--inicio view*/ view('app_calendar_programming/index_script', $dataView);//--finview
            return view("core_masterpage/default_masterpage", $dataSession);//--finview-r
        } catch (\Exception $ex) {
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }

            $data["session"]    = $dataSession;
            $data["exception"]  = $ex;
            $data["urlLogin"]   = base_url();
            $data["urlIndex"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
            $data["urlBack"]    = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView         = view("core_template/email_error_general", $data);

            return $resultView;
        }
    }

	function events(): ResponseInterface
    {
        try {
            if (!$this->core_web_authentication->isAuthenticated()) throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {

                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited) throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE) throw new \Exception(NOT_ACCESS_FUNCTION);

            }
            
			if($dataSession['company']->flavorID == 25)
			{
				$result = $this->Remember_Model->getProgrammingFacturaRegistradaSinHora();
			}
			if($dataSession['company']->flavorID == 26)
			{
				$result = $this->Remember_Model->getProgrammingFacturaAplicadaSinHora();
			}
			if($dataSession['company']->type == "chicextensiones")
			{
				$result = $this->Remember_Model->getProgrammingFacturaAplicadaSinHora();
			}
			if($dataSession['company']->type == "audio_pipe")
			{
				$result = $this->Remember_Model->getProgrammingFacturaAplicadaSinHora();
			}			
			else
			{				
				$result = $this->Remember_Model->getProgrammingFacturaAplicadaConHora();
			}
		
            $events = [];
            foreach ($result as $row) {
                $events[] = [
                    'id'    => $row->rememberID,
                    'title' => $row->title,
                    'start' => $row->createdOn,
                    'url'   => $row->url,
					'color' => $row->color
                ];
            }
            return $this->response->setJSON($events);
        } catch (\Exception $exception) {
            $result = ['status' => 'error', 'message' => $exception->getMessage(), 'code' => 400];
            return $this->response->setJSON($result);
        }
    }
	
    function imprimirEventos(){
        try {
            if (!$this->core_web_authentication->isAuthenticated()) throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {

                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited) throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if ($resultPermission == PERMISSION_NONE) throw new \Exception(NOT_ACCESS_FUNCTION);
            }

            $companyID              = $dataSession['user']->companyID;
            $objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
            $objParameterLogo       = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
            $objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
            $objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
            $objParameterRuc        = $objParameterRuc->value;
            $objCompany 	        = $this->Company_Model->get_rowByPK($companyID);

            $objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
            $objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
            $objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
            $date 		                        = $this->request->getGet("date");
            // Obtener eventos de la base de datos (si es necesario)
			
			if($dataSession['company']->flavorID == 25)
			{
			
			$eventos 	= $this->Remember_Model->getProgrammingByDateFacturaRegistradaSinHora($date);
			}
			else if ($dataSession['company']->type == "chicextensiones")
			{
			
			$eventos 	= $this->Remember_Model->getProgrammingByDateFacturaAplicadaSinHora($date);	
			}
			else if ($dataSession['company']->type == "audio_pipe")
			{
			
			$eventos 	= $this->Remember_Model->getProgrammingByDateFacturaAplicadaSinHora($date);	
			}
			else 
			{
			
            $eventos 	= $this->Remember_Model->getProgrammingByDateFacturaAplicadaConHora($date);
			}
			
            // Configurar Dompdf
            $options 	= new Options();
            $options->set('isHtml5ParserEnabled', true);
            $dompdf 	= new Dompdf($options);

            // Iniciar HTML
            $html = "<html><head><title>Eventos</title></head><body>";

            // Generar el HTML de cada evento y unirlos
            foreach ($eventos as $evento) {
                $html .= helper_reporte80mmEventosCalendario(
                    "Evento",
                    $objCompany,
                    $objParameterLogo,
                    $evento,
                    null,
                    null,
                    $objParameterTelefono,
                    $objParameterRuc
                );

                $html .= "<div style='page-break-after: always;'></div>"; // Salto de página entre eventos
            }

            $html .= "</body></html>";

            // Cargar contenido en Dompdf
            $dompdf->loadHtml($html);
            $dompdf->render();

            $fileNamePut = "eventos_".date("dmYhis").".pdf";
            $dompdf->stream($fileNamePut, ['Attachment' => $objParameterShowDownloadPreview ]);

        }catch (\Exception $exception){
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }

            $data["session"]    = $dataSession;
            $data["exception"]  = $exception;
            $data["urlLogin"]   = base_url();
            $data["urlIndex"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
            $data["urlBack"]    = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView         = view("core_template/email_error_general", $data);

            return $resultView;
        }
    }

    function imprimirEvento(){
        try {
            if (!$this->core_web_authentication->isAuthenticated()) throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {

                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited) throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if ($resultPermission == PERMISSION_NONE) throw new \Exception(NOT_ACCESS_FUNCTION);
            }
            $companyID              = $dataSession['user']->companyID;
            $objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
            $objParameterLogo       = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
            $objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
            $objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
            $objParameterRuc        = $objParameterRuc->value;
            $objCompany 	        = $this->Company_Model->get_rowByPK($companyID);
            $idevent                = $this->request->getGet("idevent");
			
			if($objCompany->type == "chicextensiones")
			{
				$evento 	            = $this->Remember_Model->getProgrammingByIdSinHora($idevent);
			}
			if($objCompany->type == "audio_pipe")
			{
				$evento 	            = $this->Remember_Model->getProgrammingByIdSinHora($idevent);
			}
			else 
			{
				$evento 	            = $this->Remember_Model->getProgrammingByIdConHora($idevent);
			}

            $objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
            $objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
            $objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
            // Configurar Dompdf
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);

            // Generar HTML para el PDF
            $html = helper_reporte80mmEventosCalendario(
                "Evento",
                $objCompany,
                $objParameterLogo,
                $evento,
                null,
                null,
                $objParameterTelefono,
                $objParameterRuc
            );

            // Cargar contenido en Dompdf
            $dompdf->loadHtml($html);
            $dompdf->render();
            $fileNamePut = "evento".$idevent."_".date("dmYhis").".pdf";
            $dompdf->stream($fileNamePut, ['Attachment' => $objParameterShowDownloadPreview ]);

        }catch (\Exception $exception){
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }

            $data["session"]    = $dataSession;
            $data["exception"]  = $exception;
            $data["urlLogin"]   = base_url();
            $data["urlIndex"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
            $data["urlBack"]    = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView         = view("core_template/email_error_general", $data);

            return $resultView;
        }
    }
}

?>