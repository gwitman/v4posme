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
 * CXC_ADVERTISING_COST_EVOLUTION_FIXED, WHATSAP_META_WABA_ID.
 */
class app_cxc_advertising extends _BaseController
{
    const MAX_IMAGE_BYTES   = 5242880;
    const MAX_SAMPLE        = 20;
    const MAX_DISCARDED     = 1000;
    const MESSAGES_PER_MIN  = 15;
    const ORIGIN_EXCEL      = "excel";
    const ORIGIN_CUSTOMER   = "cliente";
    const REASON_EMPTY      = "Teléfono vacío";
    const REASON_INVALID    = "Teléfono inválido";
    const REASON_DUPLICATE  = "Duplicado";
    const REASON_OVER_LIMIT = "Excede el máximo";

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

            $build    = $this->buildMessages($companyID, $input, $config);
            $summary  = $build["summary"];
            $provider = $summary["provider"];

            if ($summary["overLimit"] > 0) {
                throw new \Exception("La campaña supera el máximo de " . $config["maxMessage"] . " mensajes (" . $summary["overLimit"] . " de más). Reduzca los destinatarios.");
            }
            if ($summary["valid"] < 1) {
                throw new \Exception("No hay destinatarios válidos para enviar.");
            }
            if ($summary["valid"] > $config["maxMessage"]) {
                throw new \Exception("La campaña supera el máximo de " . $config["maxMessage"] . " mensajes.");
            }

            if ($provider == "meta") {
                $this->validateTemplate($input, $build["valid"]);
            } else {
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

            $queueName   = $this->core_web_rabbitmq->getQueueWhatsapp($companyID);
            $totalQueued = $this->core_web_rabbitmq->publishBatch($companyID, $queueName, $payloads);
            log_message("info", "[app_cxc_advertising::send] companyID: " . $companyID . " | userID: " . $dataSession["user"]->userID . " | proveedor: " . $provider . " | encolados: " . $totalQueued);

            return $this->response->setJSON([
                "error"       => false,
                "message"     => "Campaña encolada: " . $totalQueued . " mensajes.",
                "totalQueued" => $totalQueued,
                "queue"       => $queueName,
                "summary"     => $summary
            ]);
        } catch (\Throwable $ex) {
            return $this->responseError($ex);
        }
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
     * @return array {enabled, maxMessage, costMeta, costEvolution, wabaConfigured}
     */
    private function getConfig($companyID)
    {
        $enabled       = strtolower(trim((string) $this->core_web_parameter->getParameterValue("CXC_ADVERTISING_ENABLED", $companyID)));
        $maxMessage    = (int) $this->core_web_parameter->getParameterValue("CXC_ADVERTISING_MAX_MESSAGE", $companyID);
        $costMeta      = (float) $this->core_web_parameter->getParameterValue("CXC_ADVERTISING_COST_META_MESSAGE", $companyID);
        $costEvolution = (float) $this->core_web_parameter->getParameterValue("CXC_ADVERTISING_COST_EVOLUTION_FIXED", $companyID);
        $wabaID        = trim((string) $this->core_web_parameter->getParameterValue("WHATSAP_META_WABA_ID", $companyID));

        return [
            "enabled"        => $enabled === "true",
            "maxMessage"     => $maxMessage > 0 ? $maxMessage : 1000,
            "costMeta"       => $costMeta,
            "costEvolution"  => $costEvolution,
            "wabaConfigured" => $wabaID != ""
        ];
    }

    /**
     * Arma la lista de mensajes de la campana (compartido por preview y send).
     * Orden: filas de Excel y luego clientes; en duplicados gana Excel. Los validos que superan
     * CXC_ADVERTISING_MAX_MESSAGE se cuentan en overLimit (no se truncan en silencio).
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

        if (!$useExcel && !$useCustomer) {
            throw new \Exception("Seleccione al menos un origen de destinatarios (Excel o clientes).");
        }

        //El navegador corta el Excel en maxMessage * 1.5; mas filas indican una peticion manipulada
        $maxExcel = (int) ceil($maxMessage * 1.5);
        if (count($excelRows) > $maxExcel) {
            throw new \Exception("El Excel tiene " . count($excelRows) . " filas; el máximo permitido es " . $maxExcel . ".");
        }

        //Variables del catalogo (nunca del POST)
        $variables = $this->Advertising_Variable_Model->get_rowByCompany($companyID);
        $variables = is_array($variables) ? $variables : [];

        //Clientes: se piden mas que el maximo para poder detectar el exceso
        $customers = [];
        if ($useCustomer) {
            $customers = $this->Advertising_Variable_Model->get_rowCustomerByAdvertising(
                $companyID,
                $variables,
                !empty($filters["onlyBalance"]),
                !empty($filters["onlyMora"]),
                (int) ($filters["categoryID"] ?? 0),
                $maxMessage * 2
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
            "estimatedMinutes" => 0
        ];
        $discarded = [];
        $valid     = [];
        $phones    = [];

        foreach ($candidates as $candidate) {
            $rawPhone = trim($candidate["telefono"]);
            $phone    = preg_replace('/\D/', '', getNumberPhone(clearNumero($rawPhone)));
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
            } else if (count($valid) >= $maxMessage) {
                $reason = self::REASON_OVER_LIMIT;
                $summary["overLimit"]++;
            }

            if ($reason != "") {
                if (count($discarded) < self::MAX_DISCARDED) {
                    $discarded[] = [
                        "telefono" => $rawPhone,
                        "nombre"   => $candidate["nombre"],
                        "origen"   => $candidate["origen"],
                        "motivo"   => $reason
                    ];
                }
                continue;
            }

            $phones[$phone] = true;
            $valid[]        = $this->renderItem($candidate, $phone, $provider, $caption, $body, $template);
            if ($candidate["origen"] == self::ORIGIN_EXCEL) {
                $summary["fromExcel"]++;
            } else {
                $summary["fromCustomers"]++;
            }
        }

        $summary["valid"]            = count($valid);
        $summary["cost"]             = $provider == "meta" ? round($summary["valid"] * $config["costMeta"], 4) : $config["costEvolution"];
        $summary["estimatedMinutes"] = (int) ceil($summary["valid"] / self::MESSAGES_PER_MIN);

        return [
            "summary"   => $summary,
            "discarded" => $discarded,
            "valid"     => $valid
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
