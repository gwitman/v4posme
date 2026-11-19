<?php
//posme:2026-11-16
namespace App\Controllers;

/**
 * Campanas de WhatsApp (publicidad / cobranza masiva).
 *
 * El usuario arma un mensaje (texto libre con Evolution o plantilla aprobada con Meta), elige el origen
 * de los destinatarios (Excel y/o clientes de la empresa) y los mensajes se encolan en RabbitMQ
 * (parametro RABBITMQ_QUEUE_WHATSAPP); el consumidor es app_notification::procesarColaWhatsappMasivo.
 *
 * Parametros: CXC_ADVERTISING_ENABLED, CXC_ADVERTISING_MAX_MESSAGE, CXC_ADVERTISING_COST_META_MESSAGE,
 * CXC_ADVERTISING_COST_EVOLUTION_FIXED, WHATSAP_META_WABA_ID, CXC_ADVERTISING_ALLOW_WITHOUT_PAYMENT,
 * CXC_ADVERTISING_FREE_MESSAGES. Las campanas que superan los mensajes gratuitos se pagan con Pagadito
 * (tb_transaction_master_pagadito, ERN CAM__companyID__transactionMasterID).
 *
 * Cron job: la cola se procesa con un job de cron-job.org (parametro CXC_ADVERTISING_CRONJOB_API_KEY) que invoca
 * CRONJOB_PATH cada minuto. El job se registra en tb_public_catalog (systemName CRONJOB_SYSTEM_NAME, por flavorID)
 * y tb_public_catalog_detail: name = jobId, reference1 = URL, reference2 = ACTIVO/BAJA, reference3 = fecha de
 * creacion, reference4 = userID. Sin cron job activo no se puede enviar la campana.
 */
class app_cxc_advertising extends _BaseController
{
    const MAX_IMAGE_BYTES        = 5242880;
    const MAX_SAMPLE             = 20;
    const MAX_DISCARDED          = 1000;
    const MESSAGES_PER_MIN       = 15;
    const ORIGIN_EXCEL           = "excel";
    const ORIGIN_CUSTOMER        = "cliente";
    const ORIGIN_TEST            = "prueba";
    const REASON_EMPTY           = "Teléfono vacío";
    const REASON_INVALID         = "Teléfono inválido";
    const REASON_DUPLICATE       = "Duplicado";
    const REASON_OVER_LIMIT      = "Excede el máximo";
    const CRONJOB_SYSTEM_NAME    = 'tb_transaction_master_advertising.cron_job';
    const CRONJOB_STATUS_ACTIVE  = "ACTIVO";
    const CRONJOB_STATUS_DOWN    = "BAJA";
    const CRONJOB_PATH           = "/app_notification/procesarColaWhatsappMasivo/maxMensajes/15/maxSegundos/25/pausa/3";

    /**
     * Pantalla principal de campanas.
     * @return mixed Vista del masterpage
     */
    function index()
    {
        try {
            $dataSession = $this->authorizeAccess();
            $companyID   = $dataSession["user"]->companyID;

            $objVariables = [];
            foreach ($this->Advertising_Variable_Model->get_rowByCompany($companyID) as $variable) {
                $objVariables[] = [
                    "name"     => $variable->name,
                    "label"    => $variable->label,
                    "dataType" => $variable->dataType
                ];
            }

            $objCategories = [];
            $listCategory  = $this->core_web_catalog->getCatalogAllItem("tb_customer", "categoryID", $companyID);
            foreach (($listCategory ? $listCategory : []) as $category) {
                $objCategories[] = [
                    "catalogItemID" => $category->catalogItemID,
                    "name"          => $category->name
                ];
            }

            $config = $this->getConfig($companyID);

            //Estado del cron job solo desde BD (sin llamar a la API); si falla la pantalla abre igual
            $config["cronJob"] = [
                "active"           => false,
                "jobId"            => "",
                "url"              => "",
                "createdOn"        => "",
                "apiKeyConfigured" => false
            ];
            try {
                $cronJob = $this->getCronJobActive($companyID);
                if ($cronJob) {
                    $config["cronJob"]["active"]    = true;
                    $config["cronJob"]["jobId"]     = (string) $cronJob->name;
                    $config["cronJob"]["url"]       = (string) $cronJob->reference1;
                    $config["cronJob"]["createdOn"] = (string) $cronJob->reference3;
                }
                //Al final: si el parametro no existe lanza excepcion y el estado del job ya quedo leido
                $apiKey                                = trim((string) $this->core_web_parameter->getParameterValue("CXC_ADVERTISING_CRONJOB_API_KEY", $companyID));
                $config["cronJob"]["apiKeyConfigured"] = $apiKey != "";
            } catch (\Throwable $exCron) {
                log_message("error", "[app_cxc_advertising::index] cron job: " . $exCron->getMessage());
            }

            //Conexion WhatsApp: solo el nombre de la instancia (el estado se consulta al abrir la pestana)
            $config["whatsapp"] = ["instanceName" => "", "configError" => ""];
            try {
                $evolution                          = $this->core_web_whatsap->evolutionGetInstanceConfig($companyID);
                $config["whatsapp"]["instanceName"] = $evolution["instanceName"];
            } catch (\Throwable $exWhatsapp) {
                $config["whatsapp"]["configError"] = $exWhatsapp->getMessage();
            }

            $dataView["company"]            = $dataSession["company"];
            $dataView["companyPageSetting"] = $dataSession["companyPageSetting"] ?? null;
            $dataView["title"]              = "Campañas WhatsApp";
            $dataView["useMobile"]          = $dataSession["user"]->useMobile;
            $dataView["objVariables"]       = $this->encodeForScript($objVariables);
            $dataView["objCategories"]      = $this->encodeForScript($objCategories);
            $dataView["objConfig"]          = $this->encodeForScript($config);

            $dataSession["notification"] = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]      = $this->core_web_notification->get_message();
            $dataSession["head"]         = /*--inicio view*/ view('app_cxc_advertising/index_head', $dataView);//--finview
            $dataSession["body"]         = /*--inicio view*/ view('app_cxc_advertising/index_body', $dataView);//--finview
            $dataSession["script"]       = /*--inicio view*/ view('app_cxc_advertising/index_script', $dataView);//--finview
            $dataSession["footer"]       = "";
            return view("core_masterpage/default_masterpage", $dataSession);//--finview-r
        } catch (\Throwable $ex) {
            $session = $this->session->get();
            if (empty($session["user"])) {
                return redirect()->to(base_url("core_acount/login"));
            }

            log_message("error", "[app_cxc_advertising::index] " . $ex->getLine() . " " . $ex->getMessage());
            $data["session"]   = $session;
            $data["exception"] = new \Exception($ex->getMessage());
            $data["urlLogin"]  = base_url();
            $data["urlIndex"]  = base_url() . "/app_cxc_advertising/index";
            $data["urlBack"]   = base_url() . "/app_cxc_advertising/index";
            return view("core_template/email_error_general", $data);
        }
    }

    /**
     * Sube la imagen de la campana (multipart, campo "image"): png, jpg, jpeg o webp de hasta 5 MB.
     * @return mixed JSON {error, message, urlImage, fileName}
     */
    function uploadImage()
    {
        try {
            $dataSession = $this->authorizeAccess();
            $companyID   = $dataSession["user"]->companyID;

            $file = $this->request->getFile("image");
            if (!$file || !$file->isValid() || $file->hasMoved()) {
                throw new \Exception("No se recibió una imagen válida.");
            }

            $extension = strtolower((string) $file->getExtension());
            $mimeType  = strtolower((string) $file->getMimeType());
            if (!in_array($extension, ["png", "jpg", "jpeg", "webp"], true) || !in_array($mimeType, ["image/png", "image/jpeg", "image/jpg", "image/webp"], true)) {
                throw new \Exception("Formato no permitido. Use png, jpg, jpeg o webp.");
            }
            if ($file->getSize() > self::MAX_IMAGE_BYTES) {
                throw new \Exception("La imagen excede el tamaño máximo de 5 MB.");
            }

            $folder        = "company_" . $companyID . "/component_advertising";
            $documentoPath = PATH_FILE_OF_APP . "/" . $folder;
            if (!file_exists($documentoPath)) {
                mkdir($documentoPath, 0755, true);
                chmod($documentoPath, 0755);
            }

            $newName = $file->getRandomName();
            $file->move($documentoPath, $newName, true);

            return $this->response->setJSON([
                "error"    => false,
                "message"  => SUCCESS,
                "urlImage" => base_url() . "/resource/file_company/" . $folder . "/" . $newName,
                "fileName" => $newName
            ]);
        } catch (\Throwable $ex) {
            return $this->responseError($ex);
        }
    }

    /**
     * Plantillas aprobadas de Meta para la empresa.
     * @return mixed JSON {error, message, templates}
     */
    function getTemplates()
    {
        try {
            $dataSession = $this->authorizeAccess();
            $companyID   = $dataSession["user"]->companyID;
            $result      = $this->core_web_whatsap->getMetaCloudTemplates($companyID);

            return $this->response->setJSON([
                "error"     => !$result["success"],
                "message"   => $result["message"],
                "templates" => $result["templates"]
            ]);
        } catch (\Throwable $ex) {
            return $this->responseError($ex);
        }
    }

    /**
     * Vista previa: depura destinatarios, calcula costo y devuelve una muestra de mensajes.
     * @return mixed JSON {error, message, summary, discarded, sample}
     */
    function preview()
    {
        try {
            $dataSession = $this->authorizeAccess();
            $companyID   = $dataSession["user"]->companyID;
            $input       = $this->getInput();
            $config      = $this->getConfig($companyID);
            $build       = $this->buildMessages($companyID, $input, $config);

            return $this->response->setJSON([
                "error"     => false,
                "message"   => SUCCESS,
                "summary"   => $build["summary"],
                "discarded" => $build["discarded"],
                "sample"    => array_slice($build["valid"], 0, self::MAX_SAMPLE)
            ]);
        } catch (\Throwable $ex) {
            return $this->responseError($ex);
        }
    }

    /**
     * Recalcula la campana, la valida y la encola en RabbitMQ.
     * @return mixed JSON {error, message, totalQueued, queue, summary}
     */
    function send()
    {
        try {
            $dataSession = $this->authorizeAccess();
            $companyID   = $dataSession["user"]->companyID;
            $input       = $this->getInput();
            $config      = $this->getConfig($companyID);

            if (empty($input["confirm"])) {
                throw new \Exception("Debe confirmar el envío de la campaña.");
            }
            if (!$config["enabled"]) {
                throw new \Exception("Las campañas de WhatsApp no están habilitadas (CXC_ADVERTISING_ENABLED).");
            }
            //Sin cron job activo nadie procesaria la cola (aplica tambien al telefono de prueba)
            if (!$this->getCronJobActive($companyID)) {
                throw new \Exception("No se puede activar la campaña, primero hay que levantar el cron job.");
            }

            $build    = $this->buildMessages($companyID, $input, $config);
            $summary  = $build["summary"];
            $provider = $summary["provider"];

            $this->validateCampaign($input, $build, $config);

            //Pago Pagadito: se busca un pago aprobado y libre que cubra la campana
            $payment       = null;
            $transactionID = 0;
            if ($summary["requiresPayment"]) {
                $transactionID = $this->core_web_payment->getTransactionPagaditoID($companyID);
                $payment       = $this->Transaction_Master_Model->get_rowByAdvertisingPaymentAvailable($companyID, $transactionID, $summary["valid"], $provider);
                if (!$payment) {
                    throw new \Exception("La campaña tiene " . $summary["valid"] . " mensajes y supera los " . $config["freeMessages"] . " gratuitos: realice el pago con Pagadito antes de enviarla.");
                }
            }

            $payloads = [];
            foreach ($build["valid"] as $item) {
                if ($provider == "meta") {
                    $payloads[] = [
                        "provider"     => "meta",
                        "phoneNumber"  => $item["telefono"],
                        "templateName" => $item["templateName"],
                        "languageCode" => $item["languageCode"],
                        "bodyParams"   => $item["bodyParams"],
                        "headerImage"  => $item["headerImage"],
                        "mensaje"      => $item["mensaje"],
                        "firstName"    => $item["firstName"],
                        "origen"       => "app_cxc_advertising"
                    ];
                } else {
                    $payloads[] = [
                        "provider"    => "evolution",
                        "phoneNumber" => $item["telefono"],
                        "mensaje"     => $item["mensaje"],
                        "urlImage"    => $item["urlImage"],
                        "firstName"   => $item["firstName"],
                        "origen"      => "app_cxc_advertising"
                    ];
                }
            }

            $queueName = $this->core_web_rabbitmq->getQueueWhatsapp($companyID);

            //Se marca el pago como usado antes de encolar (solo si seguia libre) para que no se reutilice
            if ($payment) {
                //Quien pago queda en createdBy; aqui se anota quien y cuando lo consumio
                $dataPayment["descriptionReference"] = json_encode([
                    "quantity" => $summary["valid"],
                    "provider" => $provider,
                    "usedOn"   => helper_getDateTime(),
                    "usedBy"   => $dataSession["user"]->userID
                ]);
                $affected = $this->Transaction_Master_Model->update_advertisingPaymentApplied($companyID, $transactionID, $payment->transactionMasterID, $dataPayment);
                if ($affected < 1) {
                    throw new \Exception("El pago " . $payment->transactionNumber . " ya fue utilizado por otra campaña.");
                }
            }

            try {
                $totalQueued = $this->core_web_rabbitmq->publishBatch($companyID, $queueName, $payloads);
            } catch (\Throwable $exQueue) {
                if ($payment) {
                    $this->Transaction_Master_Model->update_advertisingPaymentRelease($companyID, $transactionID, $payment->transactionMasterID);
                }
                throw $exQueue;
            }
            log_message("info", "[app_cxc_advertising::send] companyID: " . $companyID . " | userID: " . $dataSession["user"]->userID . " | proveedor: " . $provider . " | encolados: " . $totalQueued . " | pago: " . ($payment ? $payment->transactionNumber : "no requerido"));

            return $this->response->setJSON([
                "error"         => false,
                "message"       => "Campaña encolada: " . $totalQueued . " mensajes.",
                "totalQueued"   => $totalQueued,
                "queue"         => $queueName,
                "summary"       => $summary,
                "paymentNumber" => $payment ? $payment->transactionNumber : ""
            ]);
        } catch (\Throwable $ex) {
            return $this->responseError($ex);
        }
    }

    /**
     * Registra el pago Pagadito de la campana (tb_transaction_master_pagadito) y devuelve la URL de cobro.
     * Mismo cuerpo JSON que preview/send. El retorno de Pagadito llega a core_user/payment_user_back
     * (ERN CAM__companyID__transactionMasterID o CAM__ORIGEN__companyID__transactionMasterID si hay paymentOriginCode).
     * @return mixed JSON {error, message, urlPayment, transactionNumber, amount, quantity}
     */
    function paymentCreate()
    {
        try {
            $dataSession = $this->authorizeAccess();
            $companyID   = $dataSession["user"]->companyID;
            $branchID    = $dataSession["user"]->branchID;
            $roleID      = $dataSession["role"]->roleID;
            $userID      = $dataSession["user"]->userID;
            $input       = $this->getInput();
            $config      = $this->getConfig($companyID);

            if (!$config["enabled"]) {
                throw new \Exception("Las campañas de WhatsApp no están habilitadas (CXC_ADVERTISING_ENABLED).");
            }

            $build    = $this->buildMessages($companyID, $input, $config);
            $summary  = $build["summary"];
            $provider = $summary["provider"];

            if ($summary["testMode"] || !$summary["requiresPayment"]) {
                throw new \Exception("Esta campaña no requiere pago.");
            }
            if ($summary["paidAvailable"]) {
                throw new \Exception("Ya tiene un pago aprobado que cubre esta campaña.");
            }
            $this->validateCampaign($input, $build, $config);

            $transactionID = $this->core_web_payment->getTransactionPagaditoID($companyID);
            $statusID      = $this->core_web_payment->getWorkflowStageID($companyID, $branchID, $roleID, false);
            if ($statusID < 1) {
                throw new \Exception("No hay un estado inicial del workflow de '" . \App\Libraries\core_web_payment::TRANSACTION_PAGADITO . "' disponible para su rol.");
            }
            //Igual que las demas pantallas: el componentID es el componente propio de la transaccion
            $objComponent = $this->core_web_tools->getComponentIDBy_ComponentName(\App\Libraries\core_web_payment::TRANSACTION_PAGADITO);
            if (!$objComponent) {
                throw new \Exception("EL COMPONENTE '" . \App\Libraries\core_web_payment::TRANSACTION_PAGADITO . "' NO EXISTE...");
            }

            $quantity = (int) $summary["valid"];
            $cost     = (float) $summary["cost"];

            $objTM["companyID"]            = $companyID;
            $objTM["transactionID"]        = $transactionID;
            $objTM["branchID"]             = $branchID;
            $objTM["transactionNumber"]    = "CAM__" . $companyID . "__0";
            $objTM["transactionOn"]        = helper_getDateTime();
            $objTM["statusIDChangeOn"]     = helper_getDateTime();
            $objTM["componentID"]          = $objComponent->componentID;
            $objTM["note"]                 = "Pago campaña WhatsApp";
            $objTM["currencyID"]           = $this->getCurrencyUsdID($companyID);
            $objTM["exchangeRate"]         = 1;
            $objTM["amount"]               = $cost;
            $objTM["subAmount"]            = $cost;
            $objTM["periodPay"]            = $quantity;
            $objTM["reference1"]           = "";
            $objTM["reference2"]           = "";
            $objTM["reference3"]           = $provider;
            $objTM["reference4"]           = "REGISTERED";
            $objTM["descriptionReference"] = json_encode(["userID" => $userID, "quantity" => $quantity, "provider" => $provider]);
            $objTM["statusID"]             = $statusID;
            $objTM["isApplied"]            = 0;
            $objTM["isActive"]             = 1;
            $this->core_web_auditoria->setAuditCreated($objTM, $dataSession, $this->request);

            $transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);
            //Con codigo de origen el ERN es CAM__ORIGEN__companyID__id para que posme reenvie el retorno de Pagadito a esta instalacion
            $originCode          = $this->core_web_payment->getOriginCode();
            $ern                 = $originCode != ""
                ? "CAM__" . $originCode . "__" . $companyID . "__" . $transactionMasterID
                : "CAM__" . $companyID . "__" . $transactionMasterID;
            $this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, ["transactionNumber" => $ern]);

            try {
                $payment = $this->core_web_payment->getPaymentUrl(
                    $companyID,
                    $ern,
                    "Campaña WhatsApp " . $quantity . " mensajes (" . $provider . ")",
                    1,
                    $cost,
                    base_url() . "/app_cxc_advertising/index"
                );
            } catch (\Throwable $exPayment) {
                $this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, ["reference4" => "FAILED", "isActive" => 0]);
                throw $exPayment;
            }

            $this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, ["reference1" => $payment["token"]]);
            log_message("info", "[app_cxc_advertising::paymentCreate] companyID: " . $companyID . " | userID: " . $userID . " | pago: " . $ern . " | mensajes: " . $quantity . " | monto: " . $cost);

            return $this->response->setJSON([
                "error"             => false,
                "message"           => "Pago registrado: complete el cobro en Pagadito.",
                "urlPayment"        => $payment["url"],
                "transactionNumber" => $ern,
                "amount"            => $cost,
                "quantity"          => $quantity
            ]);
        } catch (\Throwable $ex) {
            return $this->responseError($ex);
        }
    }

    /**
     * Consulta en Pagadito los pagos de campana pendientes (REGISTERED/VERIFYING) de los ultimos 7 dias
     * y actualiza su estado (reference4), NAP (reference2) y statusID cuando quedan aprobados.
     * @return mixed JSON {error, message, checked, completed}
     */
    function paymentCheck()
    {
        try {
            $dataSession   = $this->authorizeAccess();
            $companyID     = $dataSession["user"]->companyID;
            $branchID      = $dataSession["user"]->branchID;
            $roleID        = $dataSession["role"]->roleID;
            $transactionID = $this->core_web_payment->getTransactionPagaditoID($companyID);
            $listPending   = $this->Transaction_Master_Model->get_rowByAdvertisingPaymentPending($companyID, $transactionID);
            $checked       = 0;
            $completed     = 0;
            $statusApplied = null;

            foreach (($listPending ? $listPending : []) as $pending) {
                if (trim((string) $pending->reference1) == "") {
                    continue;
                }

                try {
                    $status = $this->core_web_payment->getPaymentStatus($companyID, $pending->reference1);
                } catch (\Throwable $exStatus) {
                    log_message("error", "[app_cxc_advertising::paymentCheck] " . $pending->transactionNumber . " " . $exStatus->getMessage());
                    continue;
                }
                $checked++;

                if ($status["status"] == "") {
                    continue;
                }
                $data               = [];
                $data["reference4"] = $status["status"];
                $data["reference2"] = $status["nap"];
                if ($status["status"] == "COMPLETED") {
                    if ($statusApplied === null) {
                        $statusApplied = $this->getStageAppliedID($companyID, $branchID, $roleID);
                    }
                    if ($statusApplied > 0) {
                        $data["statusID"]         = $statusApplied;
                        $data["statusIDChangeOn"] = helper_getDateTime();
                    }
                    $completed++;
                }
                $this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $pending->transactionMasterID, $data);
            }

            return $this->response->setJSON([
                "error"     => false,
                "message"   => "Pagos verificados: " . $checked . ". Aprobados: " . $completed . ".",
                "checked"   => $checked,
                "completed" => $completed
            ]);
        } catch (\Throwable $ex) {
            return $this->responseError($ex);
        }
    }

    /**
     * Estado del cron job de campanas (solo BD, no consulta cron-job.org).
     * @return mixed JSON {error, message, active, jobId, url, createdOn}
     */
    function cronJobStatus()
    {
        try {
            $dataSession = $this->authorizeAccess();
            $companyID   = $dataSession["user"]->companyID;
            $cronJob     = $this->getCronJobActive($companyID);

            return $this->response->setJSON([
                "error"     => false,
                "message"   => $cronJob ? "Cron job activo (Job " . $cronJob->name . ")." : "No hay cron job activo.",
                "active"    => $cronJob ? true : false,
                "jobId"     => $cronJob ? (string) $cronJob->name : "",
                "url"       => $cronJob ? (string) $cronJob->reference1 : "",
                "createdOn" => $cronJob ? (string) $cronJob->reference3 : ""
            ]);
        } catch (\Throwable $ex) {
            return $this->responseError($ex);
        }
    }

    /**
     * Levanta el cron job en cron-job.org (cada minuto contra CRONJOB_PATH) y lo registra en tb_public_catalog_detail.
     * Usa GET_LOCK por flavorID para que dos solicitudes simultaneas no creen dos jobs.
     * @return mixed JSON {error, created, message, active, jobId, url, createdOn}
     */
    function cronJobCreate()
    {
        try {
            $dataSession = $this->authorizeAccess();
            $companyID   = $dataSession["user"]->companyID;
            $userID      = $dataSession["user"]->userID;
            $apiKey      = $this->getCronJobApiKey($companyID);
            $url         = $this->getCronJobUrl();
            $flavorID    = $this->getCompanyFlavorID($companyID);
            $lockName    = "posme_cronjob_" . $flavorID;
            $db          = db_connect();

            $lock = $db->query("SELECT GET_LOCK(?, 10) AS lockResult", [$lockName])->getRow();
            if (!$lock || (int) $lock->lockResult !== 1) {
                throw new \Exception("Otra solicitud está levantando el cron job, intente de nuevo.");
            }

            try {
                //Se relee dentro del lock: otra solicitud pudo haberlo creado
                $active = $this->getCronJobActive($companyID);
                if ($active) {
                    return $this->response->setJSON([
                        "error"     => false,
                        "created"   => false,
                        "message"   => "Ya existe un cron job activo (Job " . $active->name . ").",
                        "active"    => true,
                        "jobId"     => (string) $active->name,
                        "url"       => (string) $active->reference1,
                        "createdOn" => (string) $active->reference3
                    ]);
                }

                $catalog   = $this->getCronJobCatalog($companyID, true);
                $jobId     = $this->core_web_cronjob->createJob($apiKey, $url);
                $createdOn = helper_getDateTime();

                try {
                    $detailID = $this->Public_Catalog_Detail_Model->insert([
                        "publicCatalogID"       => $catalog->publicCatalogID,
                        "flavorID"              => $catalog->flavorID,
                        "name"                  => (string) $jobId,
                        "display"               => "cron-job.org",
                        "reference1"            => $url,
                        "reference2"            => self::CRONJOB_STATUS_ACTIVE,
                        "reference3"            => $createdOn,
                        "reference4"            => (string) $userID,
                        "isActive"              => "1",
                        "parentCatalogDetailID" => 0
                    ]);
                    if (!$detailID) {
                        throw new \Exception("No se pudo registrar el cron job " . $jobId . " en la base de datos.");
                    }
                } catch (\Throwable $exInsert) {
                    //Compensacion: si no quedo registrado se elimina el job remoto para no dejarlo huerfano
                    try {
                        $this->core_web_cronjob->deleteJob($apiKey, $jobId);
                    } catch (\Throwable $exDelete) {
                        log_message("error", "[app_cxc_advertising::cronJobCreate] Job " . $jobId . " quedo huerfano en cron-job.org: " . $exDelete->getMessage());
                    }
                    throw $exInsert;
                }
            } finally {
                $db->query("SELECT RELEASE_LOCK(?) AS lockResult", [$lockName]);
            }

            log_message("info", "[app_cxc_advertising::cronJobCreate] companyID: " . $companyID . " | userID: " . $userID . " | jobId: " . $jobId . " | url: " . $url);

            return $this->response->setJSON([
                "error"     => false,
                "created"   => true,
                "message"   => "Cron job activo (Job " . $jobId . ").",
                "active"    => true,
                "jobId"     => (string) $jobId,
                "url"       => $url,
                "createdOn" => $createdOn
            ]);
        } catch (\Throwable $ex) {
            return $this->responseError($ex);
        }
    }

    /**
     * Da de baja el cron job activo: lo elimina en cron-job.org (404 = ya no existia) y luego lo marca BAJA en BD.
     * Si la API falla no se toca la BD.
     * @return mixed JSON {error, message, active, jobId}
     */
    function cronJobDelete()
    {
        try {
            $dataSession = $this->authorizeAccess();
            $companyID   = $dataSession["user"]->companyID;
            $active      = $this->getCronJobActive($companyID);
            if (!$active) {
                throw new \Exception("No hay cron job activo.");
            }

            $apiKey = $this->getCronJobApiKey($companyID);
            $jobId  = (string) $active->name;
            $this->core_web_cronjob->deleteJob($apiKey, (int) $jobId);

            $this->Public_Catalog_Detail_Model->update($active->publicCatalogDetailID, [
                "isActive"   => "0",
                "reference2" => self::CRONJOB_STATUS_DOWN
            ]);
            log_message("info", "[app_cxc_advertising::cronJobDelete] companyID: " . $companyID . " | userID: " . $dataSession["user"]->userID . " | jobId: " . $jobId);

            return $this->response->setJSON([
                "error"   => false,
                "message" => "Cron job dado de baja (Job " . $jobId . ").",
                "active"  => false,
                "jobId"   => $jobId
            ]);
        } catch (\Throwable $ex) {
            return $this->responseError($ex);
        }
    }

    /**
     * Estado de la conexion de WhatsApp (instancia de Evolution API de la empresa).
     * @return mixed JSON {error, message, instanceName, exists, state, number, profileName, profilePicUrl}
     */
    function whatsappStatus()
    {
        try {
            $dataSession = $this->authorizeAccess();
            $companyID   = $dataSession["user"]->companyID;
            $status      = $this->core_web_whatsap->evolutionInstanceStatus($companyID);

            return $this->response->setJSON(array_merge(["error" => false, "message" => SUCCESS], $status));
        } catch (\Throwable $ex) {
            return $this->responseError($ex);
        }
    }

    /**
     * Conecta el WhatsApp: crea la instancia si no existe y devuelve el QR para escanear.
     * @return mixed JSON {error, message, instanceName, state, qrBase64, pairingCode, created}
     */
    function whatsappConnect()
    {
        try {
            $dataSession = $this->authorizeAccess();
            $companyID   = $dataSession["user"]->companyID;
            $result      = $this->core_web_whatsap->evolutionInstanceConnect($companyID);
            log_message("info", "[app_cxc_advertising::whatsappConnect] companyID: " . $companyID . " | userID: " . $dataSession["user"]->userID . " | instancia: " . $result["instanceName"] . " | estado: " . $result["state"]);

            return $this->response->setJSON(array_merge(["error" => false], $result));
        } catch (\Throwable $ex) {
            return $this->responseError($ex);
        }
    }

    /**
     * Reinicia la conexion de WhatsApp en Evolution API.
     * @return mixed JSON {error, message, instanceName}
     */
    function whatsappRestart()
    {
        try {
            $dataSession = $this->authorizeAccess();
            $companyID   = $dataSession["user"]->companyID;
            $result      = $this->core_web_whatsap->evolutionInstanceRestart($companyID);
            log_message("info", "[app_cxc_advertising::whatsappRestart] companyID: " . $companyID . " | userID: " . $dataSession["user"]->userID . " | instancia: " . $result["instanceName"]);

            return $this->response->setJSON(array_merge(["error" => false], $result));
        } catch (\Throwable $ex) {
            return $this->responseError($ex);
        }
    }

    /**
     * Desconecta el telefono de WhatsApp (logout de la instancia).
     * @return mixed JSON {error, message, instanceName}
     */
    function whatsappLogout()
    {
        try {
            $dataSession = $this->authorizeAccess();
            $companyID   = $dataSession["user"]->companyID;
            $result      = $this->core_web_whatsap->evolutionInstanceLogout($companyID);
            log_message("info", "[app_cxc_advertising::whatsappLogout] companyID: " . $companyID . " | userID: " . $dataSession["user"]->userID . " | instancia: " . $result["instanceName"]);

            return $this->response->setJSON(array_merge(["error" => false], $result));
        } catch (\Throwable $ex) {
            return $this->responseError($ex);
        }
    }

    /**
     * Validaciones comunes antes de encolar o cobrar la campana (tope, destinatarios, plantilla y mensajes vacios).
     * @param array $input  Cuerpo de la peticion
     * @param array $build  buildMessages()
     * @param array $config getConfig()
     * @return void
     */
    private function validateCampaign($input, $build, $config)
    {
        $summary = $build["summary"];
        if ($summary["overLimit"] > 0) {
            throw new \Exception("La campaña supera el máximo de " . $config["maxMessage"] . " mensajes (" . $summary["overLimit"] . " de más). Reduzca los destinatarios.");
        }
        if ($summary["valid"] < 1) {
            throw new \Exception("No hay destinatarios válidos para enviar.");
        }
        if ($summary["valid"] > $config["maxMessage"]) {
            throw new \Exception("La campaña supera el máximo de " . $config["maxMessage"] . " mensajes.");
        }

        if ($summary["provider"] == "meta") {
            $this->validateTemplate($input, $build["valid"]);
            return;
        }

        $empty = 0;
        foreach ($build["valid"] as $item) {
            if (trim($item["mensaje"]) == "" && $item["urlImage"] == "") {
                $empty++;
            }
        }
        if ($empty > 0) {
            throw new \Exception("Hay " . $empty . " mensajes vacíos (sin texto ni imagen).");
        }
    }

    /**
     * Estado aplicable del workflow de pagos Pagadito; 0 si no esta configurado o el rol no lo tiene.
     * @param int $companyID
     * @param int $branchID
     * @param int $roleID
     * @return int
     */
    private function getStageAppliedID($companyID, $branchID, $roleID)
    {
        try {
            return $this->core_web_payment->getWorkflowStageID($companyID, $branchID, $roleID, true);
        } catch (\Throwable $ex) {
            log_message("error", "[app_cxc_advertising::getStageAppliedID] " . $ex->getMessage());
            return 0;
        }
    }

    /**
     * Moneda del pago (Pagadito cobra en USD): moneda externa de la empresa o, si no existe, la funcional.
     * @param int $companyID
     * @return int|null
     */
    private function getCurrencyUsdID($companyID)
    {
        try {
            $objCurrency = $this->core_web_currency->getCurrencyExternal($companyID);
            if (!$objCurrency) {
                $objCurrency = $this->core_web_currency->getCurrencyDefault($companyID);
            }
            return $objCurrency ? $objCurrency->currencyID : null;
        } catch (\Throwable $ex) {
            log_message("error", "[app_cxc_advertising::getCurrencyUsdID] " . $ex->getMessage());
            return null;
        }
    }

    /**
     * flavorID de la empresa (el catalogo del cron job se guarda por flavor).
     * @param int $companyID
     * @return int
     */
    private function getCompanyFlavorID($companyID)
    {
        $objCompany = $this->Company_Model->get_rowByPK($companyID);
        if (!$objCompany) {
            throw new \Exception("No existe la empresa " . $companyID . ".");
        }
        return (int) $objCompany->flavorID;
    }

    /**
     * Catalogo publico del cron job (CRONJOB_SYSTEM_NAME) del flavor de la empresa.
     * @param int  $companyID
     * @param bool $createIfMissing true = lo crea si no existe
     * @return object|null {publicCatalogID, flavorID}
     */
    private function getCronJobCatalog($companyID, $createIfMissing)
    {
        $flavorID = $this->getCompanyFlavorID($companyID);
        $list     = $this->Public_Catalog_Model->getBySystemNameAndFlavorID(self::CRONJOB_SYSTEM_NAME, $flavorID);
        if ($list) {
            return (object) [
                "publicCatalogID" => (int) $list[0]->publicCatalogID,
                "flavorID"        => (int) $list[0]->flavorID
            ];
        }
        if (!$createIfMissing) {
            return null;
        }

        $publicCatalogID = $this->Public_Catalog_Model->insert([
            "name"        => "CRON JOB CAMPAÑAS WHATSAPP",
            "systemName"  => self::CRONJOB_SYSTEM_NAME,
            "statusID"    => 0,
            "orden"       => 1,
            "description" => "Jobs de cron-job.org de app_cxc_advertising",
            "isActive"    => 1,
            "flavorID"    => $flavorID
        ]);
        if (!$publicCatalogID) {
            throw new \Exception("No se pudo crear el catálogo del cron job.");
        }
        return (object) [
            "publicCatalogID" => (int) $publicCatalogID,
            "flavorID"        => $flavorID
        ];
    }

    /**
     * Cron job activo registrado en BD (primer detalle con reference2 = ACTIVO).
     * @param int $companyID
     * @return object|null Fila de tb_public_catalog_detail (publicCatalogDetailID, name = jobId, reference1..4)
     */
    private function getCronJobActive($companyID)
    {
        $catalog = $this->getCronJobCatalog($companyID, false);
        if (!$catalog) {
            return null;
        }
        $listDetail = $this->Public_Catalog_Detail_Model->getView($catalog->publicCatalogID);
        foreach (($listDetail ? $listDetail : []) as $detail) {
            if (trim((string) $detail->reference2) == self::CRONJOB_STATUS_ACTIVE) {
                return $detail;
            }
        }
        return null;
    }

    /**
     * API key de cron-job.org de la empresa (nunca se escribe en el log).
     * @param int $companyID
     * @return string
     */
    private function getCronJobApiKey($companyID)
    {
        $apiKey = trim((string) $this->core_web_parameter->getParameter("CXC_ADVERTISING_CRONJOB_API_KEY", $companyID)->value);
        if ($apiKey == "") {
            throw new \Exception("Configure el parámetro CXC_ADVERTISING_CRONJOB_API_KEY.");
        }
        return $apiKey;
    }

    /**
     * URL publica que invocara cron-job.org; se rechaza si el host no es accesible desde Internet.
     * @return string
     */
    private function getCronJobUrl()
    {
        $url  = base_url() . self::CRONJOB_PATH;
        $host = strtolower(trim((string) parse_url($url, PHP_URL_HOST), "[]"));

        $isLocal = $host == ""
            || $host == "localhost"
            || substr($host, -10) == ".localhost"
            || strpos($host, "127.") === 0
            || $host == "::1";
        if (!$isLocal && filter_var($host, FILTER_VALIDATE_IP) !== false) {
            $isLocal = filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
        }
        if ($isLocal) {
            throw new \Exception("La URL del sistema (" . base_url() . ") no es accesible desde Internet; cron-job.org no podrá invocarla.");
        }
        return $url;
    }

    /**
     * Autenticacion y permiso sobre index (todas las acciones usan el permiso de la pantalla).
     * @return array Sesion del usuario
     */
    private function authorizeAccess()
    {
        if (!$this->core_web_authentication->isAuthenticated()) {
            throw new \Exception(USER_NOT_AUTENTICATED);
        }
        $dataSession = $this->session->get();

        if (APP_NEED_AUTHENTICATION == true) {
            $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
            if (!$permited) {
                throw new \Exception(NOT_ACCESS_CONTROL);
            }
        }
        return $dataSession;
    }

    /**
     * Cuerpo JSON de la peticion.
     * @return array
     */
    private function getInput()
    {
        try {
            $input = $this->request->getJSON(true);
        } catch (\Throwable $ex) {
            $input = null;
        }
        if (!is_array($input)) {
            throw new \Exception("La petición no contiene datos válidos.");
        }
        return $input;
    }

    /**
     * Respuesta JSON de error.
     * @param \Throwable $ex
     * @return mixed
     */
    private function responseError($ex)
    {
        log_message("error", "[app_cxc_advertising] " . $ex->getLine() . " " . $ex->getMessage());
        return $this->response->setJSON([
            "error"   => true,
            "message" => $ex->getLine() . " " . $ex->getMessage()
        ]);
    }

    /**
     * Configuracion de campanas de la empresa.
     * @param int $companyID
     * @return array {enabled, maxMessage, costMeta, costEvolution, wabaConfigured, allowWithoutPayment, freeMessages}
     */
    private function getConfig($companyID)
    {
        $enabled             = strtolower(trim((string) $this->core_web_parameter->getParameterValue("CXC_ADVERTISING_ENABLED", $companyID)));
        $maxMessage          = (int) $this->core_web_parameter->getParameterValue("CXC_ADVERTISING_MAX_MESSAGE", $companyID);
        $costMeta            = (float) $this->core_web_parameter->getParameterValue("CXC_ADVERTISING_COST_META_MESSAGE", $companyID);
        $costEvolution       = (float) $this->core_web_parameter->getParameterValue("CXC_ADVERTISING_COST_EVOLUTION_FIXED", $companyID);
        $wabaID              = trim((string) $this->core_web_parameter->getParameterValue("WHATSAP_META_WABA_ID", $companyID));
        $allowWithoutPayment = strtolower(trim((string) $this->core_web_parameter->getParameterValue("CXC_ADVERTISING_ALLOW_WITHOUT_PAYMENT", $companyID)));
        $freeMessages        = (int) $this->core_web_parameter->getParameterValue("CXC_ADVERTISING_FREE_MESSAGES", $companyID);

        return [
            "enabled"             => $enabled === "true",
            "maxMessage"          => $maxMessage > 0 ? $maxMessage : 1000,
            "costMeta"            => $costMeta,
            "costEvolution"       => $costEvolution,
            "wabaConfigured"      => $wabaID != "",
            "allowWithoutPayment" => $allowWithoutPayment === "true",
            "freeMessages"        => $freeMessages > 0 ? $freeMessages : 10
        ];
    }

    /**
     * Arma la lista de mensajes de la campana (compartido por preview y send).
     * Orden: filas de Excel y luego clientes; en duplicados gana Excel. Si campaignMax > 0 se eligen
     * al azar esa cantidad de validos; los que aun superan CXC_ADVERTISING_MAX_MESSAGE se cuentan en
     * overLimit (no se truncan en silencio). Con testPhone se arma un solo mensaje (primer valido) hacia ese numero.
     * @param int   $companyID
     * @param array $input  Cuerpo de la peticion (ver contrato)
     * @param array $config getConfig()
     * @return array {summary, discarded, valid}
     */
    private function buildMessages($companyID, $input, $config)
    {
        $provider = strtolower(trim((string) ($input["provider"] ?? "")));
        if ($provider != "meta" && $provider != "evolution") {
            throw new \Exception("Proveedor no válido (meta o evolution).");
        }

        $caption     = trim((string) ($input["caption"] ?? ""));
        $body        = (string) ($input["body"] ?? "");
        $urlImage    = $this->cleanUrl((string) ($input["urlImage"] ?? ""), false);
        $useExcel    = !empty($input["useExcel"]);
        $useCustomer = !empty($input["useCustomers"]);
        $filters     = is_array($input["filters"] ?? null) ? $input["filters"] : [];
        $template    = is_array($input["template"] ?? null) ? $input["template"] : [];
        $excelRows   = $useExcel && is_array($input["excelRows"] ?? null) ? array_values($input["excelRows"]) : [];
        $maxMessage  = $config["maxMessage"];

        //Cantidad de mensajes de la campana: 0 = todos los validos; mayor a 0 = seleccion al azar
        $campaignMaxRaw = $input["campaignMax"] ?? "0";
        $campaignMaxRaw = is_scalar($campaignMaxRaw) ? trim((string) $campaignMaxRaw) : "x";
        $campaignMaxRaw = $campaignMaxRaw === "" ? "0" : $campaignMaxRaw;
        if (!ctype_digit($campaignMaxRaw)) {
            throw new \Exception("La cantidad de mensajes de la campaña debe ser un número entero mayor o igual a 0.");
        }
        $campaignMax = (int) $campaignMaxRaw;
        if ($campaignMax > $maxMessage) {
            throw new \Exception("La cantidad de mensajes de la campaña (" . $campaignMax . ") supera el máximo de " . $maxMessage . ".");
        }

        //Telefono de prueba: la campana se envia una sola vez a este numero
        $testPhone = preg_replace('/\D/', '', $this->toText($input["testPhone"] ?? ""));
        if ($testPhone != "") {
            //Ya viene solo con digitos; el 505 se agrega despues de limpiar
            $testPhone = getNumberPhone($testPhone);
            if (strlen($testPhone) < 11 || strlen($testPhone) > 15) {
                throw new \Exception("El teléfono de prueba no es válido (debe tener entre 11 y 15 dígitos con el código de país).");
            }
        }

        //Con telefono de prueba no hace falta origen: el mensaje solo va a ese numero
        if (!$useExcel && !$useCustomer && $testPhone == "") {
            throw new \Exception("Seleccione al menos un origen de destinatarios (Excel o clientes).");
        }

        //El navegador corta el Excel en maxMessage * 5; mas filas indican una peticion manipulada
        $maxExcel = (int) ($maxMessage * 5);
        if (count($excelRows) > $maxExcel) {
            throw new \Exception("El Excel tiene " . count($excelRows) . " filas; el máximo permitido es " . $maxExcel . ".");
        }

        //Variables del catalogo (nunca del POST)
        $variables = $this->Advertising_Variable_Model->get_rowByCompany($companyID);
        $variables = is_array($variables) ? $variables : [];

        //Clientes: se piden mas que el maximo para poder detectar el exceso y elegir al azar
        $customers = [];
        if ($useCustomer) {
            $customers = $this->Advertising_Variable_Model->get_rowCustomerByAdvertising(
                $companyID,
                $variables,
                !empty($filters["onlyBalance"]),
                !empty($filters["onlyMora"]),
                (int) ($filters["categoryID"] ?? 0),
                $maxMessage * 5
            );
            $customers = is_array($customers) ? $customers : [];
        }

        //Columnas del Excel que son variables {{columna}}
        $excelColumns = [];
        foreach ($excelRows as $row) {
            if (!is_array($row)) {
                continue;
            }
            foreach (array_keys($row) as $column) {
                $column = (string) $column;
                if (preg_match('/^[a-z0-9_]+$/', $column) && !in_array($column, ["telefono", "mensaje", "imagen"], true) && !in_array($column, $excelColumns, true)) {
                    $excelColumns[] = $column;
                }
            }
        }

        $candidates = [];
        foreach ($excelRows as $row) {
            $row     = is_array($row) ? $row : [];
            $values  = [];
            foreach ($variables as $variable) {
                //El navegador normaliza los encabezados del Excel a minuscula (fechaPago -> fechapago)
                $values[$variable->name] = $this->toText($row[$variable->name] ?? $row[strtolower($variable->name)] ?? "");
            }
            foreach ($excelColumns as $column) {
                $values[$column] = $this->toText($row[$column] ?? "");
            }
            $imageRow     = $this->cleanUrl($this->toText($row["imagen"] ?? ""), true);
            $candidates[] = [
                "origen"   => self::ORIGIN_EXCEL,
                "telefono" => $this->toText($row["telefono"] ?? ""),
                "nombre"   => $this->toText($row["nombre"] ?? ""),
                "mensaje"  => $this->toText($row["mensaje"] ?? ""),
                "urlImage" => $imageRow != "" ? $imageRow : $urlImage,
                "excel"    => $row,
                "values"   => $values
            ];
        }
        foreach ($customers as $customer) {
            $customer = (array) $customer;
            $moneda   = (string) ($customer["moneda"] ?? "");
            $values   = [];
            foreach ($variables as $variable) {
                $values[$variable->name] = $this->formatValue($customer["var_" . $variable->name] ?? "", $variable->dataType, $moneda);
            }
            foreach ($excelColumns as $column) {
                if (!array_key_exists($column, $values)) {
                    $values[$column] = "";
                }
            }
            $candidates[] = [
                "origen"   => self::ORIGIN_CUSTOMER,
                "telefono" => (string) ($customer["telefono"] ?? ""),
                "nombre"   => (string) ($customer["nombre"] ?? ""),
                "mensaje"  => "",
                "urlImage" => $urlImage,
                "excel"    => [],
                "values"   => $values
            ];
        }

        $summary = [
            "totalInput"       => count($candidates),
            "valid"            => 0,
            "empty"            => 0,
            "invalid"          => 0,
            "duplicate"        => 0,
            "overLimit"        => 0,
            "fromExcel"        => 0,
            "fromCustomers"    => 0,
            "provider"         => $provider,
            "costUnit"         => $provider == "meta" ? $config["costMeta"] : 0,
            "costFixed"        => $provider == "meta" ? 0 : $config["costEvolution"],
            "cost"             => 0.0,
            "enabled"          => $config["enabled"],
            "maxMessage"       => $maxMessage,
            "estimatedMinutes" => 0,
            "validTotal"       => 0,
            "notSelected"      => 0,
            "randomSelected"   => false,
            "testMode"         => false,
            "testPhone"        => "",
            "freeMessages"     => $config["freeMessages"],
            "requiresPayment"  => false,
            "paidAvailable"    => false
        ];
        $discarded = [];
        $valid     = [];
        $phones    = [];

        //1. Depuracion: vacios, invalidos y duplicados (se guarda el candidato crudo; se renderiza al final)
        foreach ($candidates as $candidate) {
            $rawPhone = trim($candidate["telefono"]);
            //Primero se deja solo digitos (quita letras, acentos, puntos, barras...) y despues se agrega el 505
            //a los de 8 digitos; al reves, "8888.7777" o "Cel 88887777" quedaban sin 505 y se descartaban
            $phone    = getNumberPhone(preg_replace('/\D/', '', $rawPhone));
            $reason   = "";

            if ($rawPhone == "") {
                $reason = self::REASON_EMPTY;
                $summary["empty"]++;
            } else if (strlen($phone) < 11 || strlen($phone) > 15) {
                $reason = self::REASON_INVALID;
                $summary["invalid"]++;
            } else if (isset($phones[$phone])) {
                $reason = self::REASON_DUPLICATE;
                $summary["duplicate"]++;
            }

            if ($reason != "") {
                $this->addDiscarded($discarded, $candidate, $rawPhone, $reason);
                continue;
            }

            $phones[$phone] = true;
            $valid[]        = [
                "candidate" => $candidate,
                "phone"     => $phone
            ];
        }
        $summary["validTotal"] = count($valid);

        if ($testPhone != "") {
            //2a. Modo prueba: primer valido en el orden original, enviado al telefono de prueba.
            //    Sin destinatarios validos se arma con un candidato en blanco (variables vacias).
            $candidateTest = count($valid) > 0 ? $valid[0]["candidate"] : $this->blankCandidate($variables, $excelColumns, $urlImage);
            $valid                = [["candidate" => $candidateTest, "phone" => $testPhone]];
            $summary["testMode"]  = true;
            $summary["testPhone"] = $testPhone;
        } else {
            //2b. Seleccion al azar cuando el usuario limita la cantidad de mensajes
            if ($campaignMax > 0 && count($valid) > $campaignMax) {
                shuffle($valid);
                $summary["notSelected"]    = count($valid) - $campaignMax;
                $summary["randomSelected"] = true;
                $valid                     = array_slice($valid, 0, $campaignMax);
            }

            //3. Tope de la empresa: el excedente se informa en overLimit (no se trunca en silencio)
            if (count($valid) > $maxMessage) {
                foreach (array_slice($valid, $maxMessage) as $over) {
                    $this->addDiscarded($discarded, $over["candidate"], trim($over["candidate"]["telefono"]), self::REASON_OVER_LIMIT);
                }
                $summary["overLimit"] = count($valid) - $maxMessage;
                $valid                = array_slice($valid, 0, $maxMessage);
            }
        }

        //4. Render solo de los destinatarios finalmente elegidos
        $items = [];
        foreach ($valid as $selected) {
            $items[] = $this->renderItem($selected["candidate"], $selected["phone"], $provider, $caption, $body, $template);
            if ($selected["candidate"]["origen"] == self::ORIGIN_EXCEL) {
                $summary["fromExcel"]++;
            } else if ($selected["candidate"]["origen"] == self::ORIGIN_CUSTOMER) {
                $summary["fromCustomers"]++;
            }
        }
        $valid = $items;

        $summary["valid"]            = count($valid);
        $summary["cost"]             = $provider == "meta" ? round($summary["valid"] * $config["costMeta"], 4) : $config["costEvolution"];
        $summary["estimatedMinutes"] = (int) ceil($summary["valid"] / self::MESSAGES_PER_MIN);

        //5. Pago: requerido si supera los mensajes gratuitos, tiene costo y no es prueba ni empresa exenta
        $summary["requiresPayment"]  = !$config["allowWithoutPayment"] && !$summary["testMode"] && $summary["valid"] > $config["freeMessages"] && $summary["cost"] > 0;
        if ($summary["requiresPayment"]) {
            $summary["paidAvailable"] = $this->getPaymentAvailable($companyID, $summary["valid"], $provider) != null;
        }

        return [
            "summary"   => $summary,
            "discarded" => $discarded,
            "valid"     => $valid
        ];
    }

    /**
     * Agrega un destinatario descartado respetando el tope MAX_DISCARDED.
     * @param array  $discarded Lista acumulada (por referencia)
     * @param array  $candidate Candidato crudo
     * @param string $rawPhone  Telefono tal como vino
     * @param string $reason    Motivo (constantes REASON_*)
     * @return void
     */
    private function addDiscarded(&$discarded, $candidate, $rawPhone, $reason)
    {
        if (count($discarded) >= self::MAX_DISCARDED) {
            return;
        }
        $discarded[] = [
            "telefono" => $rawPhone,
            "nombre"   => $candidate["nombre"],
            "origen"   => $candidate["origen"],
            "motivo"   => $reason
        ];
    }

    /**
     * Pago Pagadito aprobado y sin usar que cubre la cantidad de mensajes del proveedor.
     * @param int    $companyID
     * @param int    $quantity  Mensajes de la campana
     * @param string $provider  meta | evolution
     * @return object|null Fila de tb_transaction_master
     */
    private function getPaymentAvailable($companyID, $quantity, $provider)
    {
        $transactionID = $this->core_web_payment->getTransactionPagaditoID($companyID);
        return $this->Transaction_Master_Model->get_rowByAdvertisingPaymentAvailable($companyID, $transactionID, $quantity, $provider);
    }

    /**
     * Candidato en blanco para el envio de prueba cuando no hay destinatarios validos:
     * las variables {{...}} quedan vacias y se usa la imagen general de la campana.
     * @param array  $variables    Variables del catalogo (Advertising_Variable_Model)
     * @param array  $excelColumns Columnas del Excel usadas como variables
     * @param string $urlImage     Imagen general de la campana
     * @return array Candidato con la misma forma que los de Excel/clientes
     */
    private function blankCandidate($variables, $excelColumns, $urlImage)
    {
        $values = [];
        foreach ($variables as $variable) {
            $values[$variable->name] = "";
        }
        foreach ($excelColumns as $column) {
            $values[$column] = "";
        }
        return [
            "origen"   => self::ORIGIN_TEST,
            "telefono" => "",
            "nombre"   => "",
            "mensaje"  => "",
            "urlImage" => $urlImage,
            "excel"    => [],
            "values"   => $values
        ];
    }

    /**
     * Mensaje final de un destinatario valido.
     * @return array {telefono, nombre, origen, mensaje, urlImage, bodyParams, firstName, templateName, languageCode, headerImage}
     */
    private function renderItem($candidate, $phone, $provider, $caption, $body, $template)
    {
        $values  = $candidate["values"];
        $nombre  = trim($candidate["nombre"]);
        $parts   = preg_split('/\s+/', $nombre);
        $item    = [
            "telefono"     => $phone,
            "nombre"       => $nombre,
            "origen"       => $candidate["origen"],
            "mensaje"      => "",
            "urlImage"     => $candidate["urlImage"],
            "bodyParams"   => [],
            "firstName"    => (string) ($parts[0] ?? ""),
            "templateName" => "",
            "languageCode" => "",
            "headerImage"  => ""
        ];

        if ($provider == "meta") {
            $variableType = strtolower((string) ($template["variableType"] ?? "positional")) == "named" ? "named" : "positional";
            $params       = [];
            foreach ((is_array($template["mapping"] ?? null) ? $template["mapping"] : []) as $map) {
                $key    = trim((string) ($map["key"] ?? ""));
                $source = (string) ($map["source"] ?? "");
                $name   = (string) ($map["value"] ?? "");
                if ($key == "") {
                    continue;
                }
                if ($source == "variable") {
                    $value = (string) ($values[$name] ?? "");
                } else if ($source == "excel") {
                    $value = $this->toText($candidate["excel"][$name] ?? "");
                } else {
                    $value = $name;
                }
                //Meta rechaza variables vacias
                $params[$key] = trim($value) != "" ? $value : "-";
            }
            if ($variableType == "positional") {
                uksort($params, function ($a, $b) {
                    return (int) $a - (int) $b;
                });
                $bodyParams = array_values($params);
            } else {
                $bodyParams = $params;
            }

            //Texto de referencia: cuerpo de la plantilla (si el navegador lo envia) con las variables resueltas
            $templateBody = (string) ($template["body"] ?? "");
            $replace      = [];
            foreach ($params as $key => $value) {
                $replace["{{" . $key . "}}"] = $value;
            }
            $mensaje = $templateBody != "" ? strtr($templateBody, $replace) : implode(" | ", array_values($params));

            $item["mensaje"]      = $mensaje;
            $item["bodyParams"]   = $bodyParams;
            $item["templateName"] = trim((string) ($template["name"] ?? ""));
            $item["languageCode"] = trim((string) ($template["language"] ?? "")) != "" ? trim((string) $template["language"]) : "es";
            $item["headerImage"]  = strtoupper((string) ($template["headerType"] ?? "")) == "IMAGE" ? $candidate["urlImage"] : "";
            return $item;
        }

        //Evolution: texto libre; la fila de Excel con mensaje propio lo reemplaza
        $text    = trim($candidate["mensaje"]) != "" ? $candidate["mensaje"] : $body;
        //Igual que la vista previa: admite espacios dentro de las llaves y no distingue mayusculas;
        //la variable que no existe en el catalogo ni en el Excel se elimina
        $valuesLower = array_change_key_case($values, CASE_LOWER);
        $text        = preg_replace_callback('/\{\{\s*([A-Za-z0-9_]+)\s*\}\}/', function ($match) use ($values, $valuesLower) {
            return (string) ($values[$match[1]] ?? $valuesLower[strtolower($match[1])] ?? "");
        }, $text);
        if ($caption != "") {
            $text = "*" . $caption . "*\n\n" . $text;
        }
        $item["mensaje"] = $text;
        return $item;
    }

    /**
     * Valida la plantilla de Meta antes de encolar.
     * @param array $input
     * @param array $valid Mensajes validos
     * @return void
     */
    private function validateTemplate($input, $valid)
    {
        $template = is_array($input["template"] ?? null) ? $input["template"] : [];
        if (trim((string) ($template["name"] ?? "")) == "") {
            throw new \Exception("Indique el nombre de la plantilla de Meta.");
        }

        $mapping = is_array($template["mapping"] ?? null) ? $template["mapping"] : [];
        $mapped  = [];
        foreach ($mapping as $map) {
            $key    = trim((string) ($map["key"] ?? ""));
            $source = (string) ($map["source"] ?? "");
            $value  = trim((string) ($map["value"] ?? ""));
            if ($key == "" || !in_array($source, ["variable", "excel", "fixed"], true) || $value == "") {
                throw new \Exception("Todas las variables de la plantilla deben estar mapeadas.");
            }
            $mapped[$key] = true;
        }
        foreach ((is_array($template["variables"] ?? null) ? $template["variables"] : []) as $variable) {
            if (!isset($mapped[(string) $variable])) {
                throw new \Exception("La variable {{" . $variable . "}} de la plantilla no está mapeada.");
            }
        }
        if (strtolower((string) ($template["variableType"] ?? "positional")) != "named") {
            foreach (array_keys($mapped) as $key) {
                if (!ctype_digit((string) $key)) {
                    throw new \Exception("La plantilla usa variables numéricas; la variable '" . $key . "' no es válida.");
                }
            }
        }

        if (strtoupper((string) ($template["headerType"] ?? "")) == "IMAGE") {
            foreach ($valid as $item) {
                if ($item["headerImage"] == "") {
                    throw new \Exception("La plantilla tiene encabezado de imagen: suba una imagen o indíquela en la columna 'imagen' del Excel.");
                }
            }
        }
    }

    /**
     * Valor de una variable de cliente con formato segun su tipo.
     * @param mixed  $value
     * @param string $dataType text|money|date
     * @param string $moneda   Simbolo de la moneda del credito
     * @return string
     */
    private function formatValue($value, $dataType, $moneda)
    {
        $value = $this->toText($value);
        if ($value === "") {
            return "";
        }
        if ($dataType == "money") {
            return trim($moneda . " " . number_format((float) $value, 2));
        }
        if ($dataType == "date") {
            $time = strtotime($value);
            return $time !== false ? date("d/m/Y", $time) : $value;
        }
        return $value;
    }

    /**
     * Convierte un valor escalar a texto recortado (arreglos/objetos -> "").
     * @param mixed $value
     * @return string
     */
    private function toText($value)
    {
        return is_scalar($value) ? trim((string) $value) : "";
    }

    /**
     * URL de imagen valida o "".
     * @param string $url
     * @param bool   $onlyHttps true = solo https (columna imagen del Excel)
     * @return string
     */
    private function cleanUrl($url, $onlyHttps)
    {
        $url = trim($url);
        if ($url == "" || filter_var($url, FILTER_VALIDATE_URL) === false) {
            return "";
        }
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        if ($scheme == "https" || (!$onlyHttps && $scheme == "http")) {
            return $url;
        }
        return "";
    }

    /**
     * JSON seguro para incrustar en un script (escapa < > & ' ").
     * @param mixed $data
     * @return string
     */
    private function encodeForScript($data)
    {
        $json = json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
        if ($json === false) {
            throw new \RuntimeException("No se pudo codificar la información de la pantalla: " . json_last_error_msg());
        }
        return $json;
    }
}
