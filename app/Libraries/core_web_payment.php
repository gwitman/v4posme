<?php
//posme:2026-11-18
namespace App\Libraries;

use App\Libraries\core_web_pagadito\core_web_pagadito;

/**
 * Cobros con Pagadito reutilizables (licencia posMe, pago de campañas WhatsApp, etc.).
 * Credenciales por empresa: CORE_PAYMENT_SENDBOX, CORE_PAYMENT_PRUEBA_USUARIO, CORE_PAYMENT_PRUEBA_CLAVE,
 * CORE_PAYMENT_PRODUCCION_USUARIO y CORE_PAYMENT_PRODUCCION_CLAVE.
 * El retorno de Pagadito llega a core_user/payment_user_back (parametro1 = token, parametro2 = ERN).
 */
class core_web_payment
{
    const TRANSACTION_PAGADITO = "tb_transaction_master_pagadito";

    public function __construct()
    {
    }

    /**
     * Registra el cobro en Pagadito y devuelve la URL a la que se debe redirigir al usuario.
     * @param int    $companyID   Empresa dueña de las credenciales
     * @param string $ern         Número de referencia externo único (APP__ID__CONSECUTIVO)
     * @param string $description Descripción del detalle del cobro
     * @param int    $quantity    Cantidad del detalle
     * @param float  $price       Precio unitario en USD
     * @param string $urlProduct  URL de referencia del producto
     * @return array {url, token}
     */
    function getPaymentUrl($companyID, $ern, $description, $quantity, $price, $urlProduct = "")
    {
        $pagadito = $this->connect($companyID);
        $pagadito->add_detail($quantity, $description, $price, $urlProduct);

        $url = $pagadito->exec_trans_url($ern);
        if ($url === false || trim((string) $url) == "") {
            throw new \Exception("Pagadito " . $pagadito->get_rs_code() . ": " . $pagadito->get_rs_message());
        }

        //El token de la transacción viaja en la URL de cobro y es el mismo que regresa en parametro1
        $query = [];
        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);

        return [
            "url"   => $url,
            "token" => (string) ($query["token"] ?? "")
        ];
    }

    /**
     * Consulta el estado de una transacción de Pagadito por su token.
     * @param int    $companyID Empresa dueña de las credenciales
     * @param string $token     Token de la transacción (reference1)
     * @return array {status, nap, date} status: REGISTERED|COMPLETED|VERIFYING|REVOKED|FAILED
     */
    function getPaymentStatus($companyID, $token)
    {
        $pagadito = $this->connect($companyID);
        if (!$pagadito->get_status($token)) {
            throw new \Exception("Pagadito " . $pagadito->get_rs_code() . ": " . $pagadito->get_rs_message());
        }

        return [
            "status" => (string) $pagadito->get_rs_status(),
            "nap"    => (string) $pagadito->get_rs_reference(),
            "date"   => (string) $pagadito->get_rs_date_trans()
        ];
    }

    /**
     * transactionID del tipo de transacción de pagos Pagadito.
     * @param int $companyID
     * @return int
     */
    function getTransactionPagaditoID($companyID)
    {
        //La transaccion se resuelve por el componente (tb_component + tb_company_component_flavor), igual que las demas pantallas
        $core_web_transaction = new core_web_transaction();
        $transactionID        = $core_web_transaction->getTransactionID($companyID, self::TRANSACTION_PAGADITO, 0);
        if (!$transactionID) {
            throw new \Exception("No existe la transacción del componente '" . self::TRANSACTION_PAGADITO . "' para la empresa " . $companyID . ".");
        }
        return (int) $transactionID;
    }

    /**
     * Estado del workflow de tb_transaction_master_pagadito.statusID.
     * @param int  $companyID
     * @param int  $branchID
     * @param int  $roleID
     * @param bool $applied true = primer estado aplicable (pago aprobado); false = estado inicial
     * @return int workflowStageID o 0 si el rol no tiene estados disponibles
     */
    function getWorkflowStageID($companyID, $branchID, $roleID, $applied)
    {
        $core_web_workflow = new core_web_workflow();
        $list              = $applied
            ? $core_web_workflow->getWorkflowStageApplyFirst(self::TRANSACTION_PAGADITO, "statusID", $companyID, $branchID, $roleID)
            : $core_web_workflow->getWorkflowInitStage(self::TRANSACTION_PAGADITO, "statusID", $companyID, $branchID, $roleID);

        return $list ? (int) $list[0]->workflowStageID : 0;
    }

    /**
     * Código de origen de esta instalación para el ERN de los pagos (APP__ORIGEN__ID__CONSECUTIVO).
     * Se configura en tb_company_page_setting_large (controller core_user, element paymentOriginCode).
     * @return string código alfanumérico en mayúsculas o "" si no está configurado (ERN sin origen)
     */
    function getOriginCode()
    {
        $code = trim((string) $this->getSettingLarge("paymentOriginCode"));
        return preg_match('/^[A-Za-z0-9]{1,20}$/', $code) ? strtoupper($code) : "";
    }

    /**
     * URL base (.../public) de un origen autorizado en la lista blanca de pagos.
     * Lista blanca en tb_company_page_setting_large (controller core_user, element paymentOrigins),
     * JSON {"CODIGO":"https://servidor/v4posme/carpeta/public", ...}. Solo la usa la instalación que recibe el retorno de Pagadito.
     * @param string $code Código de origen que viene en el ERN
     * @return string URL sin "/" final o "" si el origen no está autorizado
     */
    function getOriginUrl($code)
    {
        $json = trim((string) $this->getSettingLarge("paymentOrigins"));
        $json = preg_replace('/^\xEF\xBB\xBF/', '', $json);
        $list = json_decode($json, true);
        if (!is_array($list)) {
            return "";
        }

        foreach ($list as $key => $url) {
            if (strcasecmp(trim((string) $key), trim((string) $code)) != 0) {
                continue;
            }
            //Solo URLs http/https bien formadas: evita redirecciones a esquemas o valores no validos
            $url    = rtrim(trim((string) $url), "/");
            $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
            if (filter_var($url, FILTER_VALIDATE_URL) === false || !in_array($scheme, ["http", "https"])) {
                return "";
            }
            return $url;
        }
        return "";
    }

    /**
     * Valor de tb_company_page_setting_large para core_user según el tipo de la empresa de la instalación (APP_COMPANY);
     * si no existe se usa el de "demo".
     * @param string $element
     * @return string
     */
    private function getSettingLarge($element)
    {
        $Company_Model = new \App\Models\Core\Company_Model();
        $objCompany    = $Company_Model->get_rowByPK(APP_COMPANY);
        $value         = $objCompany ? getBahavioLargeDB($objCompany->type, "core_user", $element, "") : "";
        if (trim((string) $value) == "") {
            $value = getBahavioLargeDB("demo", "core_user", $element, "");
        }
        return (string) $value;
    }

    /**
     * Inicializa Pagadito con las credenciales de la empresa y abre la conexión.
     * @param int $companyID
     * @return core_web_pagadito
     */
    private function connect($companyID)
    {
        $core_web_parameter = new core_web_parameter();
        $sandbox            = strtolower(trim((string) $core_web_parameter->getParameterValue("CORE_PAYMENT_SENDBOX", $companyID))) == "true";
        $uid                = $sandbox
            ? $core_web_parameter->getParameterValue("CORE_PAYMENT_PRUEBA_USUARIO", $companyID)
            : $core_web_parameter->getParameterValue("CORE_PAYMENT_PRODUCCION_USUARIO", $companyID);
        $wsk                = $sandbox
            ? $core_web_parameter->getParameterValue("CORE_PAYMENT_PRUEBA_CLAVE", $companyID)
            : $core_web_parameter->getParameterValue("CORE_PAYMENT_PRODUCCION_CLAVE", $companyID);

        $pagadito = new core_web_pagadito();
        $pagadito->Init($uid, $wsk);
        if ($sandbox) {
            $pagadito->mode_sandbox_on();
        }
        if (!$pagadito->connect()) {
            throw new \Exception("Pagadito " . $pagadito->get_rs_code() . ": " . $pagadito->get_rs_message());
        }
        return $pagadito;
    }
}
