<?php
//posme:2026-11-17
namespace App\Controllers;

/**
 * Importacion de clientes desde Excel.
 *
 * El navegador lee el Excel, valida el archivo completo con validateRows y luego envia lotes
 * de hasta 20 filas a importRows. Cada cliente se inserta en su propia transaccion: el error
 * de una fila no afecta a las demas. Las tablas y valores por defecto son los mismos de
 * app_cxc_customer::insertElement / insertElementMobile.
 *
 * Parametros: CXC_IDENTIFICATION_TYPE_DEFAULT, CXC_PAIS_DEFAULT, CXC_DEPARTAMENTO_DEFAULT,
 * CXC_MUNICIPIO_DEFAULT, CXC_CREDIT_LINE_DEFAULT, INVENTORY_CURRENCY_ID_DEFAULT, CXC_PLAZO_DEFAULT,
 * CXC_TYPE_AMORTIZATION, CXC_FRECUENCIA_PAY_DEFAULT, CXC_INTERES_DEFAULT, CXC_DAY_EXCLUDED_IN_CREDIT,
 * CXC_*_ID_DEFAULT del cliente, CXC_STATUS_CIVIL_ID_DEFAULT, CXC_PROFESION_ID_DEFAULT,
 * CXC_USE_BIOMETRIC y CUSTOMER_ASSOCIATE_EMPLOYER (opcionales).
 */
class app_cxc_import extends _BaseController
{
    const MAX_ROWS_VALIDATE = 5000;
    const MAX_ROWS_INSERT   = 20;
    const MAX_LENGTH        = 250;
    const MAX_VALID_NAMES   = 30;
    const FIELDS            = ["codigo", "nombre", "apellido", "telefono", "pais", "departamento", "municipio", "direccion", "nombreComercial", "nombreLegal", "tipoIdentificacion", "cedula"];

    /**
     * Pantalla de importacion de clientes.
     * @return mixed Vista del masterpage
     */
    function index()
    {
        try {
            $dataSession = $this->importAuthorize(false);
            $companyID   = $dataSession["user"]->companyID;

            $catalogs = [
                "identificationType" => $this->importCatalogToArray($this->core_web_catalog->getCatalogAllItem("tb_customer", "identificationType", $companyID), false),
                "country"            => $this->importCatalogToArray($this->core_web_catalog->getCatalogAllItem("tb_customer", "countryID", $companyID), false),
                "state"              => $this->importCatalogToArray($this->core_web_catalog->getCatalogAllItem("tb_customer", "stateID", $companyID), true),
                "city"               => $this->importCatalogToArray($this->core_web_catalog->getCatalogAllItem("tb_customer", "cityID", $companyID), true)
            ];

            $dataView["company"]       = $dataSession["company"];
            $dataView["title"]         = "Importar clientes";
            $dataView["useMobile"]     = $dataSession["user"]->useMobile;
            $dataView["maxRows"]       = self::MAX_ROWS_VALIDATE;
            $dataView["maxRowsInsert"] = self::MAX_ROWS_INSERT;
            $dataView["catalogsJson"]  = $this->importEncodeForScript($catalogs);

            $dataSession["notification"] = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]      = $this->core_web_notification->get_message();
            $dataSession["head"]         = /*--inicio view*/ view('app_cxc_import/index_head', $dataView);//--finview
            $dataSession["body"]         = /*--inicio view*/ view('app_cxc_import/index_body', $dataView);//--finview
            $dataSession["script"]       = /*--inicio view*/ view('app_cxc_import/index_script', $dataView);//--finview
            $dataSession["footer"]       = "";
            return view("core_masterpage/default_masterpage", $dataSession);//--finview-r
        } catch (\Throwable $ex) {
            $session = $this->session->get();
            if (empty($session["user"])) {
                return redirect()->to(base_url("core_acount/login"));
            }

            log_message("error", "[app_cxc_import::index] " . $ex->getLine() . " " . $ex->getMessage());
            $data["session"]   = $session;
            $data["exception"] = new \Exception($ex->getMessage());
            $data["urlLogin"]  = base_url();
            $data["urlIndex"]  = base_url() . "/app_cxc_customer/index";
            $data["urlBack"]   = base_url() . "/app_cxc_import/index";
            return view("core_template/email_error_general", $data);
        }
    }

    /**
     * Valida (sin guardar) las filas del Excel: datos, catalogos y duplicados en el archivo y en la BD.
     * Entrada JSON {"rows":[{row,codigo,nombre,...}]} con un maximo de 5000 filas.
     * @return mixed JSON {error, message, data:{total, valid, invalid, rows:[{row, ok, errors}]}}
     */
    function validateRows()
    {
        try {
            $dataSession = $this->importAuthorize(false);
            $companyID   = $dataSession["user"]->companyID;
            $rows        = $this->importGetRows(self::MAX_ROWS_VALIDATE);
            $ctx         = $this->importBuildContext($dataSession, false);
            $validated   = $this->importValidate($rows, $ctx, $companyID);

            $resultRows = [];
            $valid      = 0;
            foreach ($validated as $item) {
                $ok           = empty($item["errors"]);
                $valid        = $ok ? $valid + 1 : $valid;
                $resultRows[] = [
                    "row"    => $item["row"],
                    "ok"     => $ok,
                    "errors" => $item["errors"]
                ];
            }

            return $this->response->setJSON([
                "error"   => false,
                "message" => "",
                "data"    => [
                    "total"   => count($resultRows),
                    "valid"   => $valid,
                    "invalid" => count($resultRows) - $valid,
                    "rows"    => $resultRows
                ]
            ]);
        } catch (\Throwable $ex) {
            return $this->importResponseError($ex);
        }
    }

    /**
     * Inserta un lote de hasta 20 clientes. Revalida el lote (incluida la BD) y solo inserta las filas
     * validas; cada cliente va en su propia transaccion.
     * @return mixed JSON {error, message, data:[{row, ok, customerNumber, entityID, message}]}
     */
    function importRows()
    {
        try {
            set_time_limit(120);
            $dataSession = $this->importAuthorize(true);
            $companyID   = $dataSession["user"]->companyID;
            $rows        = $this->importGetRows(self::MAX_ROWS_INSERT);
            $ctx         = $this->importBuildContext($dataSession, true);

            //Opcion de la pantalla: importar tambien las filas con error (se corrigen con valores por defecto)
            $input          = $this->request->getJSON(true);
            $incluirErrores = is_array($input) && !empty($input["incluirErrores"]);

            //Licencia: una sola vez por lote
            $this->core_web_permission->getValueLicense($companyID, get_class($this) . "/" . "index");

            $validated = $this->importValidate($rows, $ctx, $companyID);
            foreach ($validated as $item) {
                if (($incluirErrores || empty($item["errors"])) && $item["data"]["telefono"] !== "" && empty($ctx["phoneTypeID"])) {
                    throw new \Exception("No existe el catálogo de tipos de teléfono (tb_entity_phone.typeID); no se pueden importar teléfonos.");
                }
            }

            $result = [];
            foreach ($validated as $item) {
                $corrected = "";
                if (!empty($item["errors"]) && $incluirErrores) {
                    $corrected = implode(" ", array_map(function ($e) {
                        return $e["message"];
                    }, $item["errors"]));
                    $item      = $this->importForceRow($item, $ctx);
                }

                if (!empty($item["errors"])) {
                    $result[] = [
                        "row"            => $item["row"],
                        "ok"             => false,
                        "customerNumber" => "",
                        "entityID"       => 0,
                        "message"        => implode(" ", array_map(function ($e) {
                            return $e["message"];
                        }, $item["errors"]))
                    ];
                    continue;
                }

                try {
                    $inserted = $this->importInsertCustomerFromRow($item, $ctx, $dataSession);
                    $result[] = [
                        "row"            => $item["row"],
                        "ok"             => true,
                        "customerNumber" => $inserted["customerNumber"],
                        "entityID"       => $inserted["entityID"],
                        "message"        => $corrected === "" ? "Importado" : "Importado con correcciones. Errores originales: " . $corrected
                    ];
                } catch (\Throwable $exRow) {
                    log_message("error", "[app_cxc_import::importRows] fila " . $item["row"] . " companyID: " . $companyID . " | " . $exRow->getLine() . " " . $exRow->getMessage());
                    $result[] = [
                        "row"            => $item["row"],
                        "ok"             => false,
                        "customerNumber" => "",
                        "entityID"       => 0,
                        "message"        => "No se pudo guardar el cliente: " . $exRow->getMessage()
                    ];
                }
            }

            $imported = count(array_filter($result, function ($r) {
                return $r["ok"];
            }));
            log_message("info", "[app_cxc_import::importRows] companyID: " . $companyID . " | userID: " . $dataSession["user"]->userID . " | filas: " . count($result) . " | importadas: " . $imported);

            return $this->response->setJSON([
                "error"   => false,
                "message" => "",
                "data"    => $result
            ]);
        } catch (\Throwable $ex) {
            return $this->importResponseError($ex);
        }
    }

    /**
     * Autenticacion y permisos (pantalla "index" y, si se inserta, comando "add").
     * @param bool $needAdd true = exige permiso de insertar
     * @return array Sesion del usuario
     */
    private function importAuthorize($needAdd)
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

            if ($needAdd) {
                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE) {
                    throw new \Exception(NOT_ALL_INSERT);
                }
            }
        }
        return $dataSession;
    }

    /**
     * Filas del cuerpo JSON {"rows":[...]}.
     * @param int $max Maximo de filas permitido
     * @return array
     */
    private function importGetRows($max)
    {
        try {
            $input = $this->request->getJSON(true);
        } catch (\Throwable $ex) {
            $input = null;
        }
        if (!is_array($input) || !isset($input["rows"]) || !is_array($input["rows"])) {
            throw new \Exception("La petición no contiene filas válidas.");
        }

        $rows = array_values($input["rows"]);
        if (count($rows) == 0) {
            throw new \Exception("No hay filas para procesar.");
        }
        if (count($rows) > $max) {
            throw new \Exception("Se recibieron " . count($rows) . " filas; el máximo permitido es " . $max . ".");
        }
        return $rows;
    }

    /**
     * Respuesta JSON de error global (sesion, permiso, parametros, licencia).
     * @param \Throwable $ex
     * @return mixed
     */
    private function importResponseError($ex)
    {
        log_message("error", "[app_cxc_import] " . $ex->getLine() . " " . $ex->getMessage());
        return $this->response->setJSON([
            "error"   => true,
            "message" => $ex->getLine() . " " . $ex->getMessage(),
            "data"    => null
        ]);
    }

    /**
     * Contexto de la peticion: parametros, catalogos indexados y (al insertar) datos fijos del alta.
     * Se arma una sola vez por peticion.
     * @param array $dataSession
     * @param bool  $forInsert true = tambien carga lo necesario para insertar
     * @return array
     */
    private function importBuildContext($dataSession, $forInsert)
    {
        $companyID     = $dataSession["user"]->companyID;
        $branchID      = $dataSession["user"]->branchID;
        $roleID        = $dataSession["role"]->roleID;
        $listParameter = $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
        $listParameter = is_array($listParameter) ? $listParameter : [];

        //Parametros que deben existir y tener valor
        $requiredValue = ["CXC_IDENTIFICATION_TYPE_DEFAULT", "CXC_PAIS_DEFAULT", "CXC_DEPARTAMENTO_DEFAULT", "CXC_MUNICIPIO_DEFAULT"];
        //Parametros que deben existir (el valor puede ser vacio)
        $requiredExist = [];
        if ($forInsert) {
            $requiredValue = array_merge($requiredValue, ["CXC_CREDIT_LINE_DEFAULT", "INVENTORY_CURRENCY_ID_DEFAULT"]);
            $requiredExist = [
                "CXC_PLAZO_DEFAULT", "CXC_TYPE_AMORTIZATION", "CXC_FRECUENCIA_PAY_DEFAULT", "CXC_INTERES_DEFAULT",
                "CXC_DAY_EXCLUDED_IN_CREDIT", "CXC_CLASIFICATION_ID_DEFAULT", "CXC_CATEGORY_ID_DEFAULT",
                "CXC_SUBCATEGORY_ID_DEFAULT", "CXC_CUSTOMER_TYPE_ID_DEFAULT", "CXC_CUSTOMER_TYPE_PAY_ID_DEFAULT",
                "CXC_CUSTOMER_PAY_CONDITION_ID_DEFAULT", "CXC_CUSTOMER_SEX_ID_DEFAULT", "CXC_TYPE_FIRM_ID_DEFAULT",
                "CXC_FORM_CONTACT_ID_DEFAULT", "CXC_STATUS_CIVIL_ID_DEFAULT", "CXC_PROFESION_ID_DEFAULT"
            ];
        }

        $param   = [];
        $missing = [];
        foreach (array_merge($requiredValue, $requiredExist) as $name) {
            $obj = $this->core_web_parameter->getParameterFiltered($listParameter, $name);
            if (!$obj || (in_array($name, $requiredValue) && trim((string) $obj->value) === "")) {
                $missing[] = $name;
                continue;
            }
            $param[$name] = trim((string) $obj->value);
        }
        if (!empty($missing)) {
            throw new \Exception("Faltan parámetros de la empresa para importar clientes: " . implode(", ", $missing) . ". Configúrelos antes de continuar.");
        }

        $ctx                       = [];
        $ctx["companyID"]          = $companyID;
        $ctx["branchID"]           = $branchID;
        $ctx["param"]              = $param;
        $ctx["identificationType"] = $this->importCatalogIndex($this->core_web_catalog->getCatalogAllItem("tb_customer", "identificationType", $companyID));
        $ctx["country"]            = $this->importCatalogIndex($this->core_web_catalog->getCatalogAllItem("tb_customer", "countryID", $companyID));
        $ctx["state"]              = $this->importCatalogIndex($this->core_web_catalog->getCatalogAllItem("tb_customer", "stateID", $companyID));
        $ctx["city"]               = $this->importCatalogIndex($this->core_web_catalog->getCatalogAllItem("tb_customer", "cityID", $companyID));

        if (!$forInsert) {
            return $ctx;
        }

        //Parametros opcionales (si no existen se toman como false)
        $objBiometric             = $this->core_web_parameter->getParameterFiltered($listParameter, "CXC_USE_BIOMETRIC");
        $objAssociate             = $this->core_web_parameter->getParameterFiltered($listParameter, "CUSTOMER_ASSOCIATE_EMPLOYER");
        $ctx["useBiometric"]      = $objBiometric && strcmp(strtolower(trim((string) $objBiometric->value)), "true") == 0;
        $ctx["associateEmployer"] = $objAssociate && strcmp(trim((string) $objAssociate->value), "true") == 0;

        $objComponent = $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
        if (!$objComponent) {
            throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
        }
        $ctx["componentID"] = $objComponent->componentID;

        $stageCustomer = $this->core_web_workflow->getWorkflowInitStage("tb_customer", "statusID", $companyID, $branchID, $roleID);
        if (empty($stageCustomer)) {
            throw new \Exception("No existe el estado inicial del flujo de clientes (tb_customer.statusID) para su rol.");
        }
        $ctx["customerStatusID"] = $stageCustomer[0]->workflowStageID;

        $stageLine = $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_line", "statusID", $companyID, $branchID, $roleID);
        if (empty($stageLine)) {
            throw new \Exception("No existe el estado inicial del flujo de líneas de crédito (tb_customer_credit_line.statusID) para su rol.");
        }
        $ctx["creditLineStatusID"] = $stageLine[0]->workflowStageID;

        $objCurrencyDefault = $this->core_web_currency->getCurrencyDefault($companyID);
        if (!$objCurrencyDefault) {
            throw new \Exception("No existe la moneda por defecto de la empresa.");
        }
        $ctx["currencyDefaultID"] = $objCurrencyDefault->currencyID;

        //Tipo de telefono: primer item del catalogo (solo se usa si la fila trae telefono)
        $ctx["phoneTypeID"] = null;
        try {
            $listPhoneType = $this->core_web_catalog->getCatalogAllItem("tb_entity_phone", "typeID", $companyID);
            if (!empty($listPhoneType)) {
                $ctx["phoneTypeID"] = $listPhoneType[0]->catalogItemID;
            }
        } catch (\Throwable $ex) {
            log_message("error", "[app_cxc_import::importBuildContext] catalogo tb_entity_phone.typeID: " . $ex->getMessage());
        }

        //Colaboradores a los que se asocia cada cliente (misma regla que insertElement)
        $listEmployeeID = [];
        $objUserAdmin   = $this->User_Model->get_rowByRoleAdmin($companyID);
        if ($objUserAdmin) {
            $listEmployeeID   = array_map(function ($i) {
                return $i->employeeID;
            }, $objUserAdmin);
            $listEmployeeID[] = $dataSession["user"]->employeeID;
            $listEmployeeID   = array_values(array_unique($listEmployeeID));
        }
        if ($ctx["associateEmployer"]) {
            $objListUserNotAdmin = $this->User_Model->get_rowByRoleNotAdmin($companyID);
            if ($objListUserNotAdmin) {
                foreach ($objListUserNotAdmin as $objUserNotAdmin) {
                    if (in_array($objUserNotAdmin->employeeID, $listEmployeeID)) {
                        continue;
                    }
                    $listEmployeeID[] = $objUserNotAdmin->employeeID;
                }
            }
        }
        $ctx["employeeIDs"] = $listEmployeeID;

        return $ctx;
    }

    /**
     * Valida las filas (compartido por validateRows e importRows).
     * @param array $rows      Filas de entrada
     * @param array $ctx       importBuildContext()
     * @param int   $companyID
     * @return array [{row, errors:[{field,message}], data:{campos limpios}, ids:{identificationType,countryID,stateID,cityID}}]
     */
    private function importValidate(array $rows, $ctx, $companyID)
    {
        $result     = [];
        $codeRows   = [];
        $cedulaRows = [];

        foreach ($rows as $index => $raw) {
            $raw       = is_array($raw) ? $raw : [];
            $rowNumber = isset($raw["row"]) && is_numeric($raw["row"]) ? (int) $raw["row"] : $index + 2;
            $data      = [];
            foreach (self::FIELDS as $field) {
                $data[$field] = $this->importCleanText($raw[$field] ?? "");
            }

            $errors = [];
            if ($data["nombre"] === "") {
                $errors[] = $this->importError("nombre", "El nombre es obligatorio.");
            }

            //Largo maximo de las columnas destino (varchar 250)
            foreach (self::FIELDS as $field) {
                if (in_array($field, ["pais", "departamento", "municipio", "tipoIdentificacion"])) {
                    continue;
                }
                $length = mb_strlen($data[$field], "UTF-8");
                if ($length > self::MAX_LENGTH) {
                    $errors[] = $this->importError($field, "El campo " . $this->importFieldLabel($field) . " tiene " . $length . " caracteres; el máximo es " . self::MAX_LENGTH . ".");
                }
            }

            $ids = $this->importResolveCatalogs($data, $ctx, $errors);

            if ($data["codigo"] !== "") {
                $codeRows[$this->importKey($data["codigo"])][] = $index;
            }
            if ($data["cedula"] !== "") {
                $cedulaRows[$this->importKey($data["cedula"])][] = $index;
            }

            $result[$index] = [
                "row"    => $rowNumber,
                "errors" => $errors,
                "data"   => $data,
                "ids"    => $ids
            ];
        }

        //Repetidos dentro del archivo: se marcan todas las filas involucradas
        $duplicates = [
            "codigo" => ["map" => $codeRows,   "label" => "El código"],
            "cedula" => ["map" => $cedulaRows, "label" => "La cédula"]
        ];
        foreach ($duplicates as $field => $config) {
            foreach ($config["map"] as $listIndex) {
                if (count($listIndex) < 2) {
                    continue;
                }
                foreach ($listIndex as $index) {
                    $others = [];
                    foreach ($listIndex as $otherIndex) {
                        if ($otherIndex !== $index) {
                            $others[] = $result[$otherIndex]["row"];
                        }
                    }
                    $result[$index]["errors"][] = $this->importError($field, $config["label"] . " '" . $result[$index]["data"][$field] . "' está repetido en el archivo (filas " . implode(", ", $others) . ").");
                }
            }
        }

        //Existentes en la BD (clientes activos). La cedula se valida siempre, sin importar CXC_VALIDAR_CEDULA_REPETIDA
        $listCode   = array_map(function ($i) use ($result) {
            return $result[$i[0]]["data"]["codigo"];
        }, array_values($codeRows));
        $listCedula = array_map(function ($i) use ($result) {
            return $result[$i[0]]["data"]["cedula"];
        }, array_values($cedulaRows));
        $dbCode     = $this->importIndexByField(empty($listCode) ? [] : $this->Customer_Model->get_rowByReference1List($companyID, $listCode), "reference1");
        $dbCedula   = $this->importIndexByField(empty($listCedula) ? [] : $this->Customer_Model->get_rowByIdentificationList($companyID, $listCedula), "identification");

        foreach ($result as $index => $item) {
            $keyCode   = $item["data"]["codigo"] === "" ? "" : $this->importKey($item["data"]["codigo"]);
            $keyCedula = $item["data"]["cedula"] === "" ? "" : $this->importKey($item["data"]["cedula"]);
            if ($keyCode !== "" && isset($dbCode[$keyCode])) {
                $result[$index]["errors"][] = $this->importError("codigo", "El código '" . $item["data"]["codigo"] . "' ya existe en el cliente " . $dbCode[$keyCode]->customerNumber . ".");
            }
            if ($keyCedula !== "" && isset($dbCedula[$keyCedula])) {
                $result[$index]["errors"][] = $this->importError("cedula", "La cédula '" . $item["data"]["cedula"] . "' ya existe en el cliente " . $dbCedula[$keyCedula]->customerNumber . ".");
            }
        }

        return array_values($result);
    }

    /**
     * Corrige una fila con error para poder importarla igual (opcion "Importar tambien filas con error"):
     * nombre vacio -> codigo, cedula o "SIN NOMBRE"; textos mas largos que la columna se recortan;
     * tipo de identificacion invalido -> parametro por defecto; si pais, departamento o municipio no se
     * resolvieron, los tres toman los parametros por defecto para no mezclar padres e hijos.
     * Los codigos y cedulas repetidos se dejan tal cual (el cliente se crea igual).
     * @param array $item Elemento de importValidate() con errores
     * @param array $ctx  importBuildContext()
     * @return array El mismo elemento corregido y sin errores
     */
    private function importForceRow($item, $ctx)
    {
        $param = $ctx["param"];
        $data  = $item["data"];
        $ids   = $item["ids"];

        foreach (self::FIELDS as $field) {
            $data[$field] = mb_substr($data[$field], 0, self::MAX_LENGTH, "UTF-8");
        }
        if ($data["nombre"] === "") {
            $data["nombre"] = $data["codigo"] !== "" ? $data["codigo"] : ($data["cedula"] !== "" ? $data["cedula"] : "SIN NOMBRE");
        }

        if ($ids["identificationType"] === null) {
            $ids["identificationType"] = $param["CXC_IDENTIFICATION_TYPE_DEFAULT"];
        }
        if ($ids["countryID"] === null || $ids["stateID"] === null || $ids["cityID"] === null) {
            $ids["countryID"] = $param["CXC_PAIS_DEFAULT"];
            $ids["stateID"]   = $param["CXC_DEPARTAMENTO_DEFAULT"];
            $ids["cityID"]    = $param["CXC_MUNICIPIO_DEFAULT"];
        }

        $item["data"]   = $data;
        $item["ids"]    = $ids;
        $item["errors"] = [];
        return $item;
    }

    /**
     * Resuelve tipo de identificacion, pais, departamento y municipio por nombre normalizado.
     * Vacio -> parametro por defecto. Departamento hijo del pais y municipio hijo del departamento.
     * Si el pais/departamento viene en el archivo y el hijo no, se usa el hijo del parametro cuando
     * pertenece a ese padre; si no, el primer hijo del catalogo.
     * @param array $data   Fila limpia
     * @param array $ctx    importBuildContext()
     * @param array $errors Errores de la fila (se agregan aqui)
     * @return array {identificationType, countryID, stateID, cityID}
     */
    private function importResolveCatalogs($data, $ctx, &$errors)
    {
        $param = $ctx["param"];
        $ids   = ["identificationType" => null, "countryID" => null, "stateID" => null, "cityID" => null];

        //Tipo de identificacion
        if ($data["tipoIdentificacion"] === "") {
            $ids["identificationType"] = $param["CXC_IDENTIFICATION_TYPE_DEFAULT"];
        } else {
            $id = $this->importFindInCatalog($ctx["identificationType"], $data["tipoIdentificacion"], null);
            if ($id === null) {
                $errors[] = $this->importError("tipoIdentificacion", "El tipo de identificación '" . $data["tipoIdentificacion"] . "' no existe. Valores válidos: " . $this->importValidNames($ctx["identificationType"], null));
            } else {
                $ids["identificationType"] = $id;
            }
        }

        //Pais
        $countryGiven = $data["pais"] !== "";
        if (!$countryGiven) {
            $ids["countryID"] = $param["CXC_PAIS_DEFAULT"];
        } else {
            $id = $this->importFindInCatalog($ctx["country"], $data["pais"], null);
            if ($id === null) {
                $errors[] = $this->importError("pais", "El país '" . $data["pais"] . "' no existe. Valores válidos: " . $this->importValidNames($ctx["country"], null));
                return $ids;
            }
            $ids["countryID"] = $id;
        }
        $countryName = $this->importCatalogName($ctx["country"], $ids["countryID"]);

        //Departamento
        $stateGiven = $data["departamento"] !== "";
        if (!$stateGiven) {
            $id = $countryGiven ? $this->importDefaultChild($ctx["state"], $ids["countryID"], $param["CXC_DEPARTAMENTO_DEFAULT"]) : $param["CXC_DEPARTAMENTO_DEFAULT"];
            if ($id === null) {
                $errors[] = $this->importError("pais", "El país '" . $countryName . "' no tiene departamentos configurados.");
                return $ids;
            }
            $ids["stateID"] = $id;
        } else {
            $id = $this->importFindInCatalog($ctx["state"], $data["departamento"], $ids["countryID"]);
            if ($id === null) {
                $errors[] = $this->importError("departamento", "El departamento '" . $data["departamento"] . "' no existe en el país '" . $countryName . "'. Valores válidos: " . $this->importValidNames($ctx["state"], $ids["countryID"]));
                return $ids;
            }
            $ids["stateID"] = $id;
        }
        $stateName = $this->importCatalogName($ctx["state"], $ids["stateID"]);

        //Municipio (si no viene departamento se busca dentro del departamento por defecto)
        if ($data["municipio"] === "") {
            $id = ($countryGiven || $stateGiven) ? $this->importDefaultChild($ctx["city"], $ids["stateID"], $param["CXC_MUNICIPIO_DEFAULT"]) : $param["CXC_MUNICIPIO_DEFAULT"];
            if ($id === null) {
                $errors[] = $this->importError("departamento", "El departamento '" . $stateName . "' no tiene municipios configurados.");
                return $ids;
            }
            $ids["cityID"] = $id;
        } else {
            $id = $this->importFindInCatalog($ctx["city"], $data["municipio"], $ids["stateID"]);
            if ($id === null) {
                $errors[] = $this->importError("municipio", "El municipio '" . $data["municipio"] . "' no existe en el departamento '" . $stateName . "'. Valores válidos: " . $this->importValidNames($ctx["city"], $ids["stateID"]));
                return $ids;
            }
            $ids["cityID"] = $id;
        }

        return $ids;
    }

    /**
     * Inserta un cliente valido en su propia transaccion (mismas tablas y valores por defecto que
     * app_cxc_customer::insertElement/insertElementMobile). La carpeta de archivos se crea despues del commit.
     * @param array $item        Elemento de importValidate() sin errores
     * @param array $ctx         importBuildContext(..., true)
     * @param array $dataSession
     * @return array {entityID, customerNumber}
     */
    private function importInsertCustomerFromRow($item, $ctx, $dataSession)
    {
        $data          = $item["data"];
        $ids           = $item["ids"];
        $param         = $ctx["param"];
        $companyID     = $ctx["companyID"];
        $branchID      = $ctx["branchID"];
        $today         = substr(helper_getDate(), 0, 10);
        $fullName      = mb_substr(trim($data["nombre"] . " " . $data["apellido"]), 0, self::MAX_LENGTH, "UTF-8");
        $comercialName = $data["nombreComercial"] !== "" ? $data["nombreComercial"] : $fullName;
        $legalName     = $data["nombreLegal"] !== "" ? $data["nombreLegal"] : $fullName;
        $completed     = false;

        $db = db_connect();
        $db->transStrict(false);
        $db->transStart();
        try {
            //Entidad
            $objEntity              = [];
            $objEntity["companyID"] = $companyID;
            $objEntity["branchID"]  = $branchID;
            $this->core_web_auditoria->setAuditCreated($objEntity, $dataSession, $this->request);
            $entityID = $this->Entity_Model->insert_app_posme($objEntity);
            if (empty($entityID)) {
                throw new \Exception("No se pudo crear la entidad.");
            }

            //Persona natural
            $objNatural                = [];
            $objNatural["companyID"]   = $companyID;
            $objNatural["branchID"]    = $branchID;
            $objNatural["entityID"]    = $entityID;
            $objNatural["isActive"]    = true;
            $objNatural["firstName"]   = $data["nombre"];
            $objNatural["lastName"]    = $data["apellido"];
            $objNatural["address"]     = $data["direccion"];
            $objNatural["statusID"]    = $param["CXC_STATUS_CIVIL_ID_DEFAULT"];
            $objNatural["profesionID"] = $param["CXC_PROFESION_ID_DEFAULT"];
            $this->Natural_Model->insert_app_posme($objNatural);

            //Persona juridica
            $objLegal                  = [];
            $objLegal["companyID"]     = $companyID;
            $objLegal["branchID"]      = $branchID;
            $objLegal["entityID"]      = $entityID;
            $objLegal["isActive"]      = true;
            $objLegal["comercialName"] = $comercialName;
            $objLegal["legalName"]     = $legalName;
            $objLegal["address"]       = $data["direccion"];
            $this->Legal_Model->insert_app_posme($objLegal);

            //Cliente (sin cedula -> identification = customerNumber)
            $customerNumber                         = $this->core_web_counter->goNextNumber($companyID, $branchID, "tb_customer", 0);
            $objCustomer                            = [];
            $objCustomer["companyID"]               = $companyID;
            $objCustomer["branchID"]                = $branchID;
            $objCustomer["entityID"]                = $entityID;
            $objCustomer["customerNumber"]          = $customerNumber;
            $objCustomer["identificationType"]      = $ids["identificationType"];
            $objCustomer["identification"]          = $data["cedula"] !== "" ? $data["cedula"] : $customerNumber;
            $objCustomer["countryID"]               = $ids["countryID"];
            $objCustomer["stateID"]                 = $ids["stateID"];
            $objCustomer["cityID"]                  = $ids["cityID"];
            $objCustomer["location"]                = "";
            $objCustomer["address"]                 = $data["direccion"];
            $objCustomer["currencyID"]              = $param["INVENTORY_CURRENCY_ID_DEFAULT"];
            $objCustomer["clasificationID"]         = $param["CXC_CLASIFICATION_ID_DEFAULT"];
            $objCustomer["categoryID"]              = $param["CXC_CATEGORY_ID_DEFAULT"];
            $objCustomer["subCategoryID"]           = $param["CXC_SUBCATEGORY_ID_DEFAULT"];
            $objCustomer["customerTypeID"]          = $param["CXC_CUSTOMER_TYPE_ID_DEFAULT"];
            $objCustomer["birthDate"]               = $today;
            $objCustomer["dateContract"]            = $today;
            $objCustomer["statusID"]                = $ctx["customerStatusID"];
            $objCustomer["typePay"]                 = $param["CXC_CUSTOMER_TYPE_PAY_ID_DEFAULT"];
            $objCustomer["payConditionID"]          = $param["CXC_CUSTOMER_PAY_CONDITION_ID_DEFAULT"];
            $objCustomer["sexoID"]                  = $param["CXC_CUSTOMER_SEX_ID_DEFAULT"];
            $objCustomer["reference1"]              = $data["codigo"];
            $objCustomer["reference2"]              = "";
            $objCustomer["reference3"]              = "";
            $objCustomer["reference4"]              = "";
            $objCustomer["reference5"]              = "";
            $objCustomer["balancePoint"]            = 0;
            $objCustomer["phoneNumber"]             = $data["telefono"];
            $objCustomer["typeFirm"]                = $param["CXC_TYPE_FIRM_ID_DEFAULT"];
            $objCustomer["budget"]                  = 0;
            $objCustomer["isActive"]                = true;
            $objCustomer["entityContactID"]         = 0;
            $objCustomer["formContactID"]           = $param["CXC_FORM_CONTACT_ID_DEFAULT"];
            $objCustomer["allowWhatsappPromotions"] = 0;
            $objCustomer["allowWhatsappCollection"] = 0;
            $this->core_web_auditoria->setAuditCreated($objCustomer, $dataSession, $this->request);
            $this->Customer_Model->insert_app_posme($objCustomer);

            //Lector biometrico
            if ($ctx["useBiometric"]) {
                $dataUser                      = [];
                $dataUser["id"]                = $entityID;
                $dataUser["name"]              = "buscar en otra base";
                $dataUser["email"]             = "buscar en otra base";
                $dataUser["email_verified_at"] = "0000-00-00 00:00:00";
                $dataUser["password"]          = "buscar en otra base";
                $dataUser["remember_token"]    = "buscar en otra base";
                $dataUser["created_at"]        = "0000-00-00 00:00:00";
                $dataUser["updated_at"]        = "0000-00-00 00:00:00";
                $dataUser["image"]             = "";
                $this->Biometric_User_Model->delete_app_posme($dataUser["id"]);
                $this->Biometric_User_Model->insert_app_posme($dataUser);
            }

            //Cuenta de la entidad
            $objEntityAccount                    = [];
            $objEntityAccount["companyID"]       = $companyID;
            $objEntityAccount["componentID"]     = $ctx["componentID"];
            $objEntityAccount["componentItemID"] = $entityID;
            $objEntityAccount["name"]            = "";
            $objEntityAccount["description"]     = "";
            $objEntityAccount["accountTypeID"]   = "0";
            $objEntityAccount["currencyID"]      = "0";
            $objEntityAccount["classID"]         = "0";
            $objEntityAccount["balance"]         = "0";
            $objEntityAccount["creditLimit"]     = "0";
            $objEntityAccount["maxCredit"]       = "0";
            $objEntityAccount["debitLimit"]      = "0";
            $objEntityAccount["maxDebit"]        = "0";
            $objEntityAccount["statusID"]        = "0";
            $objEntityAccount["accountID"]       = "0";
            $objEntityAccount["isActive"]        = 1;
            $this->core_web_auditoria->setAuditCreated($objEntityAccount, $dataSession, $this->request);
            $this->Entity_Account_Model->insert_app_posme($objEntityAccount);

            //Relacion con colaboradores
            foreach ($ctx["employeeIDs"] as $employeeID) {
                $dataRelationShip               = [];
                $dataRelationShip["employeeID"] = $employeeID;
                $dataRelationShip["customerID"] = $entityID;
                $dataRelationShip["isActive"]   = 1;
                $dataRelationShip["startOn"]    = $today;
                $dataRelationShip["endOn"]      = $today;
                $this->Relationship_Model->insert_app_posme($dataRelationShip);
            }

            //Credito del cliente (sin datos, igual que insertElement)
            $objCustomerCredit                   = [];
            $objCustomerCredit["companyID"]      = $companyID;
            $objCustomerCredit["branchID"]       = $branchID;
            $objCustomerCredit["entityID"]       = $entityID;
            $objCustomerCredit["limitCreditDol"] = 0;
            $objCustomerCredit["balanceDol"]     = 0;
            $objCustomerCredit["incomeDol"]      = 0;
            $this->Customer_Credit_Model->insert_app_posme($objCustomerCredit);

            //Telefono principal
            if ($data["telefono"] !== "") {
                $objEntityPhone              = [];
                $objEntityPhone["companyID"] = $companyID;
                $objEntityPhone["branchID"]  = $branchID;
                $objEntityPhone["entityID"]  = $entityID;
                $objEntityPhone["typeID"]    = $ctx["phoneTypeID"];
                $objEntityPhone["number"]    = $data["telefono"];
                $objEntityPhone["isPrimary"] = 1;
                $this->Entity_Phone_Model->insert_app_posme($objEntityPhone);
            }

            //Linea de credito por defecto (igual que insertElement cuando no se envian lineas)
            $objCustomerCreditLine                     = [];
            $objCustomerCreditLine["companyID"]        = $companyID;
            $objCustomerCreditLine["branchID"]         = $branchID;
            $objCustomerCreditLine["entityID"]         = $entityID;
            $objCustomerCreditLine["creditLineID"]     = $param["CXC_CREDIT_LINE_DEFAULT"];
            $objCustomerCreditLine["accountNumber"]    = $this->core_web_counter->goNextNumber($companyID, $branchID, "tb_customer_credit_line", 0);
            $objCustomerCreditLine["currencyID"]       = $ctx["currencyDefaultID"];
            $objCustomerCreditLine["limitCredit"]      = 300000;
            $objCustomerCreditLine["balance"]          = 300000;
            $objCustomerCreditLine["interestYear"]     = helper_StringToNumber($param["CXC_INTERES_DEFAULT"]);
            $objCustomerCreditLine["interestPay"]      = 0;
            $objCustomerCreditLine["totalPay"]         = 0;
            $objCustomerCreditLine["totalDefeated"]    = 0;
            $objCustomerCreditLine["dateOpen"]         = $today;
            $objCustomerCreditLine["periodPay"]        = $param["CXC_FRECUENCIA_PAY_DEFAULT"];
            $objCustomerCreditLine["dateLastPay"]      = $today;
            $objCustomerCreditLine["term"]             = helper_StringToNumber($param["CXC_PLAZO_DEFAULT"]);
            $objCustomerCreditLine["note"]             = "-";
            $objCustomerCreditLine["statusID"]         = $ctx["creditLineStatusID"];
            $objCustomerCreditLine["isActive"]         = 1;
            $objCustomerCreditLine["typeAmortization"] = $param["CXC_TYPE_AMORTIZATION"];
            $objCustomerCreditLine["dayExcluded"]      = $param["CXC_DAY_EXCLUDED_IN_CREDIT"];
            $this->Customer_Credit_Line_Model->insert_app_posme($objCustomerCreditLine);

            //transComplete hace commit, o rollback si alguna consulta fallo
            $completed = $db->transComplete();
        } catch (\Throwable $ex) {
            //Con una consulta fallida, transComplete hace rollback y limpia el estado (transStrict false)
            if ($db->transStatus() === false) {
                $db->transComplete();
            } else {
                $db->transRollback();
            }
            throw $ex;
        }

        if (!$completed) {
            $dbError = $db->error();
            throw new \Exception("Error de base de datos" . (!empty($dbError["message"]) ? ": " . $dbError["message"] : "."));
        }

        //Carpeta de archivos del cliente (despues del commit; un fallo aqui no invalida el cliente)
        try {
            $pathfile = PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $ctx["componentID"] . "/component_item_" . $entityID;
            if (!file_exists($pathfile)) {
                mkdir($pathfile, 0700, true);
            }
        } catch (\Throwable $ex) {
            log_message("error", "[app_cxc_import::importInsertCustomerFromRow] carpeta entityID " . $entityID . ": " . $ex->getMessage());
        }

        return [
            "entityID"       => (int) $entityID,
            "customerNumber" => $customerNumber
        ];
    }

    /**
     * Catalogo a arreglo simple para la vista.
     * @param array|null $list       Items de getCatalogAllItem
     * @param bool       $withParent true = incluye parentID
     * @return array [{id, name[, parentID]}]
     */
    private function importCatalogToArray($list, $withParent)
    {
        $result = [];
        foreach ((is_array($list) ? $list : []) as $item) {
            $row = ["id" => $item->catalogItemID, "name" => $item->name];
            if ($withParent) {
                $row["parentID"] = $item->parentCatalogItemID ?? null;
            }
            $result[] = $row;
        }
        return $result;
    }

    /**
     * Indexa un catalogo por nombre normalizado y por padre.
     * @param array|null $list Items de getCatalogAllItem
     * @return array {items:[id=>{name,parentID}], byNorm:[norm=>id], byParent:[parentID=>[norm=>id]], children:[parentID=>[id]]}
     */
    private function importCatalogIndex($list)
    {
        $index = ["items" => [], "byNorm" => [], "byParent" => [], "children" => []];
        foreach ((is_array($list) ? $list : []) as $item) {
            $id       = (string) $item->catalogItemID;
            $parentID = isset($item->parentCatalogItemID) && $item->parentCatalogItemID !== null ? (string) $item->parentCatalogItemID : null;
            $norm     = $this->importNormalize($item->name);

            $index["items"][$id] = ["name" => $item->name, "parentID" => $parentID];
            //Si hay nombres repetidos gana el primero (orden del catalogo)
            if (!isset($index["byNorm"][$norm])) {
                $index["byNorm"][$norm] = $id;
            }
            if ($parentID !== null) {
                if (!isset($index["byParent"][$parentID][$norm])) {
                    $index["byParent"][$parentID][$norm] = $id;
                }
                $index["children"][$parentID][] = $id;
            }
        }
        return $index;
    }

    /**
     * Busca un item por nombre normalizado; si se indica padre, debe ser hijo de ese padre
     * (los items sin padre registrado, p. ej. de catalogo publico, se aceptan por nombre).
     * @param array       $catalog  importCatalogIndex()
     * @param string      $name
     * @param string|null $parentID
     * @return string|null catalogItemID
     */
    private function importFindInCatalog($catalog, $name, $parentID)
    {
        $norm = $this->importNormalize($name);
        if ($parentID === null) {
            return $catalog["byNorm"][$norm] ?? null;
        }

        $parentID = (string) $parentID;
        if (isset($catalog["byParent"][$parentID][$norm])) {
            return $catalog["byParent"][$parentID][$norm];
        }

        $id = $catalog["byNorm"][$norm] ?? null;
        if ($id !== null && $catalog["items"][$id]["parentID"] === null) {
            return $id;
        }
        return null;
    }

    /**
     * Hijo por defecto de un padre: el del parametro si pertenece al padre (o no tiene padre registrado);
     * si no, el primer hijo del catalogo.
     * @param array  $catalog   importCatalogIndex()
     * @param string $parentID
     * @param string $defaultID Valor del parametro
     * @return string|null
     */
    private function importDefaultChild($catalog, $parentID, $defaultID)
    {
        $parentID  = (string) $parentID;
        $defaultID = (string) $defaultID;
        if (isset($catalog["items"][$defaultID])) {
            $defaultParent = $catalog["items"][$defaultID]["parentID"];
            if ($defaultParent === null || $defaultParent === $parentID) {
                return $defaultID;
            }
        }
        return $catalog["children"][$parentID][0] ?? null;
    }

    /**
     * Nombre de un item del catalogo (o su id si no esta en el catalogo).
     * @param array  $catalog importCatalogIndex()
     * @param string $id
     * @return string
     */
    private function importCatalogName($catalog, $id)
    {
        return $catalog["items"][(string) $id]["name"] ?? (string) $id;
    }

    /**
     * Lista de nombres validos para los mensajes de error (limitada a MAX_VALID_NAMES).
     * @param array       $catalog  importCatalogIndex()
     * @param string|null $parentID null = todo el catalogo
     * @return string
     */
    private function importValidNames($catalog, $parentID)
    {
        $names = [];
        if ($parentID === null) {
            foreach ($catalog["items"] as $item) {
                $names[] = $item["name"];
            }
        } else {
            foreach (($catalog["children"][(string) $parentID] ?? []) as $id) {
                $names[] = $catalog["items"][$id]["name"];
            }
        }
        if (empty($names)) {
            return "(ninguno configurado)";
        }
        $total = count($names);
        $names = array_slice($names, 0, self::MAX_VALID_NAMES);
        return implode(", ", $names) . ($total > self::MAX_VALID_NAMES ? ", ..." : "");
    }

    /**
     * Indexa filas de BD por un campo (clave importKey).
     * @param array  $rows
     * @param string $field
     * @return array [clave => fila]
     */
    private function importIndexByField($rows, $field)
    {
        $index = [];
        foreach ((is_array($rows) ? $rows : []) as $row) {
            $key = $this->importKey((string) $row->$field);
            if ($key !== "" && !isset($index[$key])) {
                $index[$key] = $row;
            }
        }
        return $index;
    }

    /**
     * Texto limpio: UTF-8, sin caracteres de control, espacios colapsados y recortado.
     * @param mixed $value
     * @return string
     */
    private function importCleanText($value)
    {
        if (!is_scalar($value) || is_bool($value)) {
            return "";
        }
        //Numeros enteros del Excel sin notacion cientifica (p. ej. cedulas o telefonos numericos)
        if (is_float($value) && floor($value) == $value && abs($value) < 1e15) {
            $text = number_format($value, 0, ".", "");
        } else {
            $text = (string) $value;
        }

        if (!mb_check_encoding($text, "UTF-8")) {
            $text = mb_convert_encoding($text, "UTF-8", "ISO-8859-1");
        }
        $text = preg_replace('/[\x{0000}-\x{001F}\x{007F}-\x{009F}\x{00A0}\x{200B}\x{FEFF}]/u', " ", $text);
        $text = preg_replace('/\s+/u', " ", (string) $text);
        return trim((string) $text);
    }

    /**
     * Texto normalizado para comparar con catalogos: minusculas, sin acentos y espacios colapsados.
     * @param mixed $texto
     * @return string
     */
    private function importNormalize($texto)
    {
        return mb_strtolower(helper_QuitarAcentos($this->importCleanText($texto)), "UTF-8");
    }

    /**
     * Clave para detectar repetidos de codigo y cedula.
     * @param string $texto
     * @return string
     */
    private function importKey($texto)
    {
        return mb_strtoupper(trim((string) $texto), "UTF-8");
    }

    /**
     * Error de una fila.
     * @param string $field   Clave del campo de entrada
     * @param string $message
     * @return array {field, message}
     */
    private function importError($field, $message)
    {
        return ["field" => $field, "message" => $message];
    }

    /**
     * Nombre visible de un campo de entrada.
     * @param string $field
     * @return string
     */
    private function importFieldLabel($field)
    {
        $labels = [
            "codigo"             => "Código",
            "nombre"             => "Nombre",
            "apellido"           => "Apellido",
            "telefono"           => "Teléfono",
            "pais"               => "País",
            "departamento"       => "Departamento",
            "municipio"          => "Municipio",
            "direccion"          => "Dirección",
            "nombreComercial"    => "Nombre comercial",
            "nombreLegal"        => "Nombre legal",
            "tipoIdentificacion" => "Tipo de identificación",
            "cedula"             => "Cédula"
        ];
        return $labels[$field] ?? $field;
    }

    /**
     * JSON seguro para incrustar en un script (escapa < > & ' ").
     * @param mixed $data
     * @return string
     */
    private function importEncodeForScript($data)
    {
        $json = json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
        if ($json === false) {
            throw new \RuntimeException("No se pudo codificar la información de la pantalla: " . json_last_error_msg());
        }
        return $json;
    }
}
