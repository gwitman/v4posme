<?php

namespace App\Controllers;

use function PHPUnit\Framework\directoryExists;

class app_cxc_notes extends _BaseController
{
    public function edit()
    {

    }

    public function delete()
    {
        try{
            //AUTENTICADO
            if(!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession		= $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            if(APP_NEED_AUTHENTICATION == true){
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

                if(!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"delete",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                if ($resultPermission 	== PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_DELETE);

            }

            $companyID = $dataSession["user"]->companyID;
            /*
             * Grado -> classID
             * Alumno -> entityID
             * Colaborador -> entityIDSecondary
             * Materia -> areaID
             * Anio, mes y dia -> transactionON
             * ValorCuantitativo -> amount
             * ValorCualitativo -> priorityID
             */
            $classID            = $this->request->getPost("gradoId");
            $entityID           = $this->request->getPost("alumnoId");
            $entityIDSecondary  = $this->request->getPost("colaboradorId");
            $areaID             = $this->request->getPost("materiaId");
            $transactionON      = $this->request->getPost("txtTransactionOn");
            $objTM              = $this->Transaction_Master_Model->get_RowByNotas($classID, $entityID, $entityIDSecondary, $areaID, $transactionON, null);
            if (!$objTM){
                return $this->response->setJSON(array(
                    'error'   => true,
                    'message' => "No existe el valor con los campos buscado "
                ));//--finjson
            }

            if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $dataSession["user"]->userID))
                throw new \Exception(NOT_DELETE);

            //Eliminar el Registro
            $this->Transaction_Master_Model->delete_app_posme($companyID,$objTM->transactionID,$objTM->transactionMasterID);

            return $this->response->setJSON(array(
                'error'   => false,
                'message' => SUCCESS
            ));//--finjson
        }
        catch(\Exception $ex){
            $this->core_web_notification->set_message(true,$ex->getLine()." ".$ex->getMessage());
            return $this->response->setJSON(array(
                'error'   => true,
                'message' => $ex->getLine()." ".$ex->getMessage()
            ));//--finjson
        }
    }

    public function updateElement($dataSession, $objTM)
    {
        try {
            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION) {
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_INSERT);
            }

            $this->core_web_permission->getValueLicense($dataSession["user"]->companyID, get_class($this) . "/" . "index");

            //Obtener el Componente de Tarea
            $objComponentGrade = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_grade_book");
            if (!$objComponentGrade)
                throw new \Exception("EL COMPONENTE 'tb_transaction_master_grade_book' NO EXISTE...");

            //Obtener el componente de Customer
            $objComponentCustomer = $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
            if (!$objComponentCustomer)
                throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");

            //Obtener el componente de Colaborador
            $objComponentEmployer = $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
            if (!$objComponentEmployer)
                throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");

            //Obtener el componente de Materias
            $objComponentMateria = $this->core_web_tools->getComponentIDBy_ComponentName("tb_public_catalog");
            if (!$objComponentMateria)
                throw new \Exception("EL COMPONENTE 'tb_public_catalog' NO EXISTE...");

            //Obtener transaccion
            $companyID          = $dataSession["user"]->companyID;
            $branchID           = $dataSession["user"]->branchID;
            $transactionID      = $this->core_web_transaction->getTransactionID($companyID, "tb_transaction_master_grade_book", 0);
            $objT               = $this->Transaction_Model->getByCompanyAndTransaction($companyID, $transactionID);
            /*
             * Grado -> classID
             * Alumno -> entityID
             * Colaborador -> entityIDSecondary
             * Materia -> areaID
             * Anio, mes y dia -> transactionON
             * ValorCuantitativo -> amount
             * ValorCualitativo -> priorityID
             */
            $objTMNew["transactionOn"]         = /*inicio get post*/$this->request->getPost("txtTransactionOn");
            $objTMNew["statusIDChangeOn"]      = date("Y-m-d H:m:s");
            $objTMNew["componentID"]           = $objComponentGrade->componentID;
            $objTMNew["sign"]                  = $objT->signInventory;
            $objTMNew["reference1"]            = /*inicio get post*/$this->request->getPost("txtReferencia1");
            $objTMNew["reference2"]            = /*inicio get post*/$this->request->getPost("txtReferencia2");
            $objTMNew["amount"]                = /*inicio get post*/$this->request->getPost("txtCalificacionCuantitativa");
            $objTMNew["isApplied"]             = 0;
            $objTMNew["journalEntryID"]        = 0;
            $objTMNew["classID"]               = /*inicio get post*/$this->request->getPost("txtGradoID");
            $objTMNew["areaID"]                = /*inicio get post*/$this->request->getPost("txtMateriaID");
            $objTMNew["priorityID"]            = /*inicio get post*/$this->request->getPost("txtPriorityID");
            $objTMNew["isActive"]              = 1;
            $objTMNew["entityID"]              = /*inicio get post*/$this->request->getPost("txtAlumnoID");
            $objTMNew["entityIDSecondary"]     = /*inicio get post*/$this->request->getPost("txtColaboradorID");

            $db = db_connect();
            $db->transStart();

            $transactionMasterID = $this->Transaction_Master_Model->update_app_posme($companyID,$objTM->transactionID,$objTM->transactionMasterID,$objTMNew);

            //Crear la Carpeta para almacenar los Archivos del Documento
            $documentoPath = PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponentGrade->componentID . "/component_item_" . $transactionMasterID;
            if (!file_exists($documentoPath)) {
                mkdir($documentoPath, 0755, true);
                chmod($documentoPath, 0755);
            }


            if ($db->transStatus() !== false) {
                $db->transCommit();
                $this->core_web_notification->set_message(false, SUCCESS);
                return $this->response->setJSON(array(
                    'error' => false,
                    'message' => SUCCESS
                ));//--finjson
            } else {
                $db->transRollback();
                $errorCode      = $db->error()["code"];
                $errorMessage   = 'Codigo: ' . $errorCode . '; Mensaje ' . $db->error()["message"];
                $this->core_web_notification->set_message(true, $errorMessage);
                return $this->response->setJSON(array(
                    'error'     => true,
                    'message'   => $errorMessage
                ));//--finjson
            }
        } catch (\Exception $ex) {
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }
            $this->core_web_notification->set_message(true, $ex->getLine() . " " . $ex->getMessage());
            return $this->response->setJSON(array(
                'error'     => true,
                'message'   => $ex->getLine() . " " . $ex->getMessage()
            ));//--finjson
        }
    }

    public function insertElement($dataSession)
    {
        try {
            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION) {
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_INSERT);
            }

            $this->core_web_permission->getValueLicense($dataSession["user"]->companyID, get_class($this) . "/" . "index");

            //Obtener el Componente de Tarea
            $objComponentGrade = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_grade_book");
            if (!$objComponentGrade)
                throw new \Exception("EL COMPONENTE 'tb_transaction_master_grade_book' NO EXISTE...");

            //Obtener el componente de Customer
            $objComponentCustomer = $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
            if (!$objComponentCustomer)
                throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");

            //Obtener el componente de Colaborador
            $objComponentEmployer = $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
            if (!$objComponentEmployer)
                throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");

            //Obtener el componente de Materias
            $objComponentMateria = $this->core_web_tools->getComponentIDBy_ComponentName("tb_public_catalog");
            if (!$objComponentMateria)
                throw new \Exception("EL COMPONENTE 'tb_public_catalog' NO EXISTE...");

            //Obtener transaccion
            $companyID      = $dataSession["user"]->companyID;
            $branchID       = $dataSession["user"]->branchID;
            $transactionID  = $this->core_web_transaction->getTransactionID($companyID, "tb_transaction_master_grade_book", 0);
            $objT           = $this->Transaction_Model->getByCompanyAndTransaction($companyID, $transactionID);
            /*
             * Grado -> classID
             * Alumno -> entityID
             * Colaborador -> entityIDSecondary
             * Materia -> areaID
             * Anio, mes y dia -> transactionON
             * ValorCuantitativo -> amount
             * ValorCualitativo -> priorityID
             */
            $objTM["companyID"]             = $companyID;
            $objTM["transactionID"]         = $transactionID;
            $objTM["branchID"]              = $branchID;
            $objTM["transactionNumber"]     = $this->core_web_counter->goNextNumber($companyID, $branchID, "tb_transaction_master_grade_book", 0);
            $objTM["transactionCausalID"]   = $this->core_web_transaction->getDefaultCausalID($companyID, $transactionID);
            $objTM["transactionOn"]         = /*inicio get post*/$this->request->getPost("txtTransactionOn");
            $objTM["statusIDChangeOn"]      = date("Y-m-d H:m:s");
            $objTM["componentID"]           = $objComponentGrade->componentID;
            $objTM["sign"]                  = $objT->signInventory;
            $objTM["currencyID"]            = null;
            $objTM["currencyID2"]           = null;
            $objTM["exchangeRate"]          = null;
            $objTM["reference1"]            = /*inicio get post*/$this->request->getPost("txtReferencia1");
            $objTM["reference2"]            = /*inicio get post*/$this->request->getPost("txtReferencia2");
            $objTM["amount"]                = /*inicio get post*/$this->request->getPost("txtCalificacionCuantitativa");
            $objTM["isApplied"]             = 0;
            $objTM["journalEntryID"]        = 0;
            $objTM["classID"]               = /*inicio get post*/$this->request->getPost("txtGradoID");
            $objTM["areaID"]                = /*inicio get post*/$this->request->getPost("txtMateriaID");
            $objTM["priorityID"]            = /*inicio get post*/$this->request->getPost("txtPriorityID");
            $objTM["sourceWarehouseID"]     = NULL;
            $objTM["targetWarehouseID"]     = NULL;
            $objTM["isActive"]              = 1;
            $objTM["entityID"]              = /*inicio get post*/$this->request->getPost("txtAlumnoID");
            $objTM["entityIDSecondary"]     = /*inicio get post*/$this->request->getPost("txtColaboradorID");
            $objTM["note"]                  = "";


            $this->core_web_auditoria->setAuditCreated($objTM, $dataSession, $this->request);

            $db = db_connect();
            $db->transStart();

            $transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);

            //Crear la Carpeta para almacenar los Archivos del Documento
            $documentoPath = PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponentGrade->componentID . "/component_item_" . $transactionMasterID;
            if (!file_exists($documentoPath)) {
                mkdir($documentoPath, 0755, true);
                chmod($documentoPath, 0755);
            }


            if ($db->transStatus() !== false) {
                $db->transCommit();
                $this->core_web_notification->set_message(false, SUCCESS);
                return $this->response->setJSON(array(
                    'error'     => false,
                    'message'   => SUCCESS
                ));//--finjson
            } else {
                $db->transRollback();
                $errorCode      = $db->error()["code"];
                $errorMessage   = 'Codigo: ' . $errorCode . '; Mensaje ' . $db->error()["message"];
                $this->core_web_notification->set_message(true, $errorMessage);
                return $this->response->setJSON(array(
                    'error'     => true,
                    'message'   => $errorMessage
                ));//--finjson
            }
        } catch (\Exception $ex) {
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }
            $this->core_web_notification->set_message(true, $ex->getLine() . " " . $ex->getMessage());
            return $this->response->setJSON(array(
                'error'     => true,
                'message'   => $ex->getLine() . " " . $ex->getMessage()
            ));//--finjson
        }
    }

    public function save()
    {
        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //Validar Formulario
            $this->validation->setRule("txtTransactionOn", "Fecha", "required");

            //Validar Formulario
            if (!$this->validation->withRequest($this->request)->run()) {
                $stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
                $this->core_web_notification->set_message(true, $stringValidation);
                return $this->response->setJSON(array(
                    'error'     => true,
                    'message'   => "Validar campos"
                ));//--finjson
            }

            /*
             * Grado -> classID
             * Alumno -> entityID
             * Colaborador -> entityIDSecondary
             * Materia -> areaID
             * Anio, mes y dia -> transactionON
             * ValorCuantitativo -> amount
             * ValorCualitativo -> priorityID
             */
            $classID            = $this->request->getPost("txtGradoID");
            $entityID           = $this->request->getPost("txtAlumnoID");
            $entityIDSecondary  = $this->request->getPost("txtColaboradorID");
            $areaID             = $this->request->getPost("txtMateriaID");
            $transactionON      = $this->request->getPost("txtTransactionOn");
            $priorityID         = $this->request->getPost("txtPriorityID");
            $objTm              = $this->Transaction_Master_Model->get_RowByNotas($classID, $entityID, $entityIDSecondary, $areaID, $transactionON,null);
            //Guardar o Editar Registro
            if (is_null($objTm)) {
                return $this->insertElement($dataSession);
            } else {
                return $this->updateElement($dataSession, $objTm);
            }

        } catch (\Exception $ex) {
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }
            $this->core_web_notification->set_message(true, $ex->getLine() . " " . $ex->getMessage());
            return $this->response->setJSON(array(
                'error'     => true,
                'message'   => $ex->getLine() . ' ' . $ex->getMessage()
            ));//--finjson
        }
    }

    public function index()
    {
        try {
            //AUTENTICACION
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_EDIT);

            }
            $companyID      = $dataSession["user"]->companyID;
            $userID         = $dataSession["user"]->userID;
            $branchID       = $dataSession["user"]->branchID;
            $roleID         = $dataSession["role"]->roleID;
            $transactionID  = $this->core_web_transaction->getTransactionID($companyID, "tb_transaction_master_grade_book", 0);

            //Obtener el componente Grado
            $objComponentNote = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_grade_book");
            if (!$objComponentNote)
                throw new \Exception("EL COMPONENTE 'tb_transaction_master_grade_book' NO EXISTE...");

            //Obtener el componente de Customer
            $objComponentCustomer = $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
            if (!$objComponentCustomer)
                throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");

            //Obtener el componente de Colaborador
            $objComponentEmployer = $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
            if (!$objComponentEmployer)
                throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");

            //Obtener el componente de Materias
            $objComponentMateria = $this->core_web_tools->getComponentIDBy_ComponentName("tb_public_catalog");
            if (!$objComponentMateria)
                throw new \Exception("EL COMPONENTE 'tb_public_catalog' NO EXISTE...");

            $dataView["companyID"]              = $companyID;
            $dataView["userID"]                 = $userID;
            $dataView["objListAnio"]            = $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_grade_book", "anosID", $companyID);
            $dataView["objListMeses"]           = $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_grade_book", "mesesID", $companyID);
            $dataView["objListGrado"]           = $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_grade_book", "gradoID", $companyID);
            $dataView["objComponentCustomer"]   = $objComponentCustomer;
            $dataView["objComponentEmployer"]   = $objComponentEmployer;
            $dataView["objComponentMateria"]    = $objComponentMateria;
            $dataView["objListCalificacionCualitativa"] = $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_grade_book", "priorityID", $companyID);

            //Renderizar Resultado
            $dataSession["notification"]    = $this->core_web_error->get_error($userID);
            $dataSession["message"]         = $this->core_web_notification->get_message();
            $dataSession["head"]            = /*--inicio view*/view('app_cxc_notes/index_head', $dataView);//--finview
            $dataSession["body"]            = /*--inicio view*/view('app_cxc_notes/index_body', $dataView);//--finview
            $dataSession["script"]          = /*--inicio view*/view('app_cxc_notes/index_script', $dataView);//--finview
            $dataSession["footer"]          = "";

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

    function searchTransactionMaster()
    {
        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new \Exception(NOT_ACCESS_FUNCTION);

            }
            $companyID              = $dataSession["user"]->companyID;
            $branchID               = $dataSession["user"]->branchID;
            $objComponentGrade      = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_grade_book");
            if (!$objComponentGrade)
                throw new \Exception("EL COMPONENTE 'tb_transaction_master_grade_book' NO EXISTE...");

            /*
            * Grado -> classID
            * Alumno -> entityID
            * Colaborador -> entityIDSecondary
            * Materia -> areaID
            * Anio, mes y dia -> transactionON
            * ValorCuantitativo -> amount
            * ValorCualitativo -> priorityID
            */
            $classID            = $this->request->getPost("gradoId");
            $entityID           = $this->request->getPost("alumnoId");
            $entityIDSecondary  = $this->request->getPost("colaboradorId");
            $areaID             = $this->request->getPost("materiaId");
            $anio               = $this->request->getPost("anio");
            $mes                = $this->request->getPost("mes");
            $transactionON      = $this->request->getPost("transactionOn");

            $objListTm           = $this->Transaction_Master_Model->get_RowGradeBook($classID, $entityID, $entityIDSecondary, $areaID, $anio, $mes, $transactionON);

            return $this->response->setJSON(array(
                'error'     => false,
                'message'   => SUCCESS,
                'datos'     => $objListTm
            ));//--finjson

        } catch (\Exception $ex) {

            return $this->response->setJSON(array(
                'error'     => true,
                'message'   => $ex->getLine() . " " . $ex->getMessage()
            ));//--finjson
        }
    }

    public function viewPrinterFormatoA4()
    {
        try{
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);

            //Obtener el Componente de Tarea
            $objComponentGrade = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_grade_book");
            if (!$objComponentGrade)
                throw new \Exception("EL COMPONENTE 'tb_transaction_master_grade_book' NO EXISTE...");

            //http://localhost/posmev4/app_cxc_notes/viewPrinterFormatoA4/companyID/2/userID/2/gradoID/2443/anio/2025/alumnoID/715
            $companyID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "companyID"); //--finuri
            $userID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "userID"); //--finuri
            $gradoID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "gradoID"); //--finuri
            $anio				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "anio"); //--finuri
            $alumnoID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "alumnoID"); //--finuri

            $dataSession        = $this->session->get();
            $branchID           = $dataSession["user"]->branchID;
            $token				= '';
            $obUserModel	    = $this->User_Model->get_rowByPK($companyID, $branchID, $userID);
            $objCompany 	    = $this->Company_Model->get_rowByPK($companyID);
            $fechaNow           = date('Y-m-d');
            $query				= "CALL pr_cxc_get_report_certificate_of_grades(?, ?, ?, ?, ?, ?);";

            $objData		= $this->Bd_Model->executeRenderMultipleNative(
                $query,
                [$userID, $token, $companyID, $gradoID, $anio, $alumnoID]
            );
            $objDetail = [];
            if (isset($objData)) {
                $objDataResult["objDetail"]     = $objData[0];
                $objDataResult["objAlumno"]     = $objData[1];
                $objDataResult["objLeyendas"]   = $objData[2];
            }
            else {
                $objDataResult["objDetail"]     = null;
                $objDataResult["objAlumno"]     = null;
                $objDataResult["objLeyendas"]   = null;
            }


            //parametros de reportes
            $params_["objCompany"]					= $objCompany;
            $params_["dateCurrent"]					= date("Y-m-d H:i:s");
            $params_["obUserModel"]					= $obUserModel;
            $params_["objDetail"]					= $objDataResult["objDetail"];
            $params_["objAlumno"]					= $objDataResult["objAlumno"][0];
            $params_["objLeyendas"]					= $objDataResult["objLeyendas"];
            $params_["objLogo"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO", $companyID);
            $params_["objTelefono"]					= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE", $companyID);
            $params_["message"]						= str_replace(" 00:00:00", "", $fechaNow) . " CIERRE DE CAJA: " . $objCompany->name . " ";
            $params_["objFirma"] 					= "{companyID:" .  ",branchID:" .  ",userID:" . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_box_get_report_closed" . ",ip:" . $this->request->getIPAddress() . ",sessionID:" . ",agenteID:" . $this->request->getUserAgent()->getAgentString() . ",lastActivity:" .  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}";
            $params_["objFirmaEncription"] 			= md5($params_["objFirma"]);
            $subject								= $params_["message"];
            if($objCompany->type=="colirio"){
                $html  									= /*--inicio view*/ view('app_cxc_notes/share_summary_certificado/view_a_disemp_colirio', $params_); //--finview
            }else{
                $html  									= /*--inicio view*/ view('app_cxc_notes/share_summary_certificado/view_a_disemp', $params_); //--finview
            }
            //echo $html;

            $this->dompdf->loadHTML($html);

            //1cm = 29.34666puntos
            //a4: 210 ancho x 297
            //a4: 21cm x 29.7cm
            //a4: 595.28puntos x 841.59puntos

            //$this->dompdf->setPaper('A4','portrait');
            //$this->dompdf->setPaper(array(0,0,234.76,6000));

            $this->dompdf->render();

            $objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
            $objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
            $objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
            $objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
            $objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;

            $fileNamePut = "certificado_".$params_["objAlumno"]["customerNumber"]."_".date("dmYhis").".pdf";
            $path        = "./resource/file_company/company_".$companyID."/component_".$objComponentGrade->componentID."/component_item_".$params_["objAlumno"]["customerNumber"]."/".$fileNamePut;
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
                chmod($path, 0755);
            }
            $path        .= $fileNamePut;

            file_put_contents(
                $path,
                $this->dompdf->output()
            );

            chmod($path, 644);

            if($objParameterShowLinkDownload == "true")
            {
                echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_".$objComponentGrade->componentID."/component_item_0"."/".
                    $fileNamePut."'>download certificado</a>
				";

            }
            else{
                //visualizar
                $this->dompdf->stream("file_".date('YmdHms'), ['Attachment' => $objParameterShowDownloadPreview ]);
            }
        }catch (\Exception $ex){
            echo $ex;
        }
    }
}
?>