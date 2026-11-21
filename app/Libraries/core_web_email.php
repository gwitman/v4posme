<?php
//posme:2026-11-20
namespace App\Libraries;

/**
 * Envio de correos del sistema (SMTP de app/Config/Email.php) con cola opcional en RabbitMQ.
 *
 * - sendNow         : envia en el momento (ej. recuperar contraseña, autorizacion de login).
 * - queueEmail      : si RABBITMQ_QUEUE_EMAIL esta habilitado publica en la cola <type>_Email_;
 *                     si no, o si la publicacion falla, envia directo (respaldo).
 * - queueEmailBatch : igual que queueEmail para varios mensajes con una sola conexion.
 * - processQueuePayload : callback del consumidor app_notification::procesarColaEmail.
 *
 * Opciones ($options) de cada correo:
 *  - from, fromName      : remitente (por defecto EMAIL_APP)
 *  - cc, bcc             : string separado por coma o arreglo
 *  - attachments         : lista de rutas locales (o arreglos {path, name})
 *  - inline              : lista de {path, placeholder}: la imagen se adjunta embebida (CID) y en el
 *                          cuerpo se reemplaza "cid:<placeholder>" por el CID real (ver CID_LOGO)
 */
class core_web_email
{
    const CID_LOGO          = "POSME_CID_LOGO";     //Marcador del logo embebido: la vista usa src="cid:POSME_CID_LOGO"
    const ORIGEN_DEFAULT    = "core_web_email";

    private $lastError      = "";
    private $lastErrorRed   = false;               //true si el ultimo error fue de conexion con el SMTP

    public function __construct()
    {
    }

    /**
     * Ultimo error de envio o de encolado.
     * @return string mensaje del error (vacio si no hubo)
     */
    function getLastError()
    {
        return $this->lastError;
    }

    /**
     * Convierte uno o varios destinatarios (string separado por coma/punto y coma o arreglo)
     * en una lista de correos validos, sin repetidos.
     * @param string|array $to destinatarios
     * @return array correos validos
     */
    function normalizeRecipients($to)
    {
        $lista = is_array($to) ? $to : preg_split('/[,;]+/', (string) $to);
        $result = [];
        foreach ($lista as $email) {
            $email = trim((string) $email);
            if ($email != "" && filter_var($email, FILTER_VALIDATE_EMAIL) && !in_array(strtolower($email), array_map("strtolower", $result))) {
                $result[] = $email;
            }
        }
        return $result;
    }

    /**
     * Indica si la empresa tiene habilitada la cola de correo (RABBITMQ_QUEUE_EMAIL).
     * @param int $companyID empresa
     * @return bool
     */
    function isQueueEnabled($companyID)
    {
        $core_web_rabbitmq = new core_web_rabbitmq();
        return $core_web_rabbitmq->isQueueEmailEnabled($companyID);
    }

    /**
     * Envia un correo en el momento. Nunca lanza excepcion: devuelve false y deja el error en getLastError().
     * @param int          $companyID empresa (para el log)
     * @param string|array $to        destinatarios
     * @param string       $subject   asunto
     * @param string       $body      cuerpo HTML
     * @param array        $options   from, fromName, cc, bcc, attachments, inline
     * @return bool true si el SMTP acepto el correo
     */
    function sendNow($companyID, $to, $subject, $body, $options = [])
    {
        $this->lastError    = "";
        $this->lastErrorRed = false;

        try {
            $recipients = $this->normalizeRecipients($to);
            if (count($recipients) == 0) {
                $this->lastError = "Sin destinatarios validos: " . (is_array($to) ? implode(",", $to) : (string) $to);
                log_message("error", "[core_web_email] sendNow company " . $companyID . " - " . $this->lastError);
                return false;
            }

            //Instancia nueva por envio: no arrastra destinatarios ni adjuntos de otro correo
            $email = \Config\Services::email(null, false);
            $email->setMailType("html");
            $email->wordWrap = false;

            $from     = trim((string) ($options["from"] ?? (defined("EMAIL_APP") ? EMAIL_APP : "")));
            $fromName = (string) ($options["fromName"] ?? "");
            $email->setFrom($from, $fromName);
            $email->setTo($recipients);

            $cc  = $this->normalizeRecipients($options["cc"] ?? []);
            $bcc = $this->normalizeRecipients($options["bcc"] ?? []);
            if (count($cc) > 0) {
                $email->setCC($cc);
            }
            if (count($bcc) > 0) {
                $email->setBCC($bcc);
            }

            //Adjuntos normales
            foreach (($options["attachments"] ?? []) as $attachment) {
                $path = is_array($attachment) ? (string) ($attachment["path"] ?? "") : (string) $attachment;
                $name = is_array($attachment) ? ($attachment["name"] ?? null) : null;
                if ($path != "" && is_file($path)) {
                    $email->attach($path, "attachment", $name);
                } else {
                    log_message("error", "[core_web_email] sendNow company " . $companyID . " - adjunto no encontrado: " . $path);
                }
            }

            //Imagenes embebidas (CID): el CID se genera al enviar, por eso el cuerpo trae un marcador
            foreach (($options["inline"] ?? []) as $inline) {
                $path        = (string) ($inline["path"] ?? "");
                $placeholder = (string) ($inline["placeholder"] ?? self::CID_LOGO);
                $cid         = false;
                if ($path != "" && is_file($path)) {
                    $email->attach($path, "inline");
                    $cid = $email->setAttachmentCID($path);
                }
                $body = str_replace("cid:" . $placeholder, $cid ? "cid:" . $cid : "", $body);
            }

            $email->setSubject((string) $subject);
            $email->setMessage((string) $body);

            if ($email->send(false)) {
                log_message("info", "[core_web_email] sendNow company " . $companyID . " - enviado a: " . implode(",", $recipients) . " | " . $subject);
                return true;
            }

            $this->lastError    = trim(strip_tags((string) $email->printDebugger([])));
            $this->lastErrorRed = $this->isConnectionError($this->lastError);
            log_message("error", "[core_web_email] sendNow company " . $companyID . " - no enviado a: " . implode(",", $recipients) . " | " . $this->lastError);
            return false;
        } catch (\Throwable $ex) {
            $this->lastError = $ex->getMessage();
            log_message("error", "[core_web_email] sendNow company " . $companyID . " - " . $ex->getLine() . " " . $ex->getMessage());
            return false;
        }
    }

    /**
     * Encola un correo (RABBITMQ_QUEUE_EMAIL habilitado) o lo envia directo si la cola esta
     * deshabilitada o la publicacion falla. Nunca lanza excepcion.
     * @param int          $companyID empresa
     * @param string|array $to        destinatarios
     * @param string       $subject   asunto
     * @param string       $body      cuerpo HTML
     * @param array        $options   from, fromName, cc, bcc, attachments, inline
     * @param string       $origen    etiqueta de quien lo genera (log)
     * @return bool true si quedo en la cola o se envio
     */
    function queueEmail($companyID, $to, $subject, $body, $options = [], $origen = "")
    {
        return $this->queueEmailBatch($companyID, [[
            "to"      => $to,
            "subject" => $subject,
            "body"    => $body,
            "options" => $options
        ]], $origen) == 1;
    }

    /**
     * Encola varios correos con una sola conexion; los que no tienen destinatario valido se omiten.
     * Si la cola esta deshabilitada o falla, se envian directo uno por uno.
     * @param int    $companyID empresa
     * @param array  $messages  lista de {to, subject, body, options}
     * @param string $origen    etiqueta de quien los genera (log)
     * @return int cantidad de correos encolados o enviados
     */
    function queueEmailBatch($companyID, $messages, $origen = "")
    {
        $this->lastError = "";
        $origen          = $origen != "" ? $origen : self::ORIGEN_DEFAULT;
        $payloads        = [];

        foreach ((is_array($messages) ? $messages : []) as $message) {
            $recipients = $this->normalizeRecipients($message["to"] ?? "");
            if (count($recipients) == 0) {
                log_message("error", "[core_web_email] " . $origen . " - correo omitido, sin destinatarios validos: " . (is_array($message["to"] ?? "") ? implode(",", $message["to"]) : (string) ($message["to"] ?? "")));
                continue;
            }
            $payloads[] = [
                "companyID" => (int) $companyID,
                "to"        => implode(",", $recipients),
                "subject"   => (string) ($message["subject"] ?? ""),
                "body"      => (string) ($message["body"] ?? ""),
                "options"   => is_array($message["options"] ?? null) ? $message["options"] : [],
                "origen"    => $origen
            ];
        }

        if (count($payloads) == 0) {
            if ($this->lastError == "") {
                $this->lastError = "Sin correos con destinatarios validos";
            }
            return 0;
        }

        if ($this->isQueueEnabled($companyID)) {
            try {
                $core_web_rabbitmq = new core_web_rabbitmq();
                $queueName         = $core_web_rabbitmq->getQueueEmail($companyID);
                $total             = $core_web_rabbitmq->publishBatch($companyID, $queueName, $payloads);
                log_message("info", "[core_web_email] " . $origen . " - cola: " . $queueName . " | encolados: " . $total);
                return $total;
            } catch (\Throwable $ex) {
                //Respaldo: si el broker no responde se envia directo para no perder el correo
                $this->lastError = $ex->getMessage();
                log_message("error", "[core_web_email] " . $origen . " - error encolando, se envia directo: " . $ex->getMessage());
            }
        }

        $total = 0;
        foreach ($payloads as $payload) {
            if ($this->sendNow($companyID, $payload["to"], $payload["subject"], $payload["body"], $payload["options"])) {
                $total++;
            }
        }
        return $total;
    }

    /**
     * Procesa un mensaje de la cola de correo (callback de core_web_rabbitmq::consume).
     * @param array $payload {companyID, to, subject, body, options, origen}
     * @return bool|string true enviado, false reintentar, RESULT_DESCARTAR datos invalidos,
     *                     RESULT_POSPONER si el SMTP no esta disponible
     */
    function processQueuePayload($payload)
    {
        $companyID  = (int) ($payload["companyID"] ?? 0);
        $recipients = $this->normalizeRecipients($payload["to"] ?? "");
        $subject    = (string) ($payload["subject"] ?? "");
        $body       = (string) ($payload["body"] ?? "");

        if (count($recipients) == 0 || trim($body) == "") {
            log_message("error", "[core_web_email] processQueuePayload - descartado (sin destinatario o sin cuerpo) origen: " . ($payload["origen"] ?? ""));
            return core_web_rabbitmq::RESULT_DESCARTAR;
        }

        $options = is_array($payload["options"] ?? null) ? $payload["options"] : [];
        if ($this->sendNow($companyID, $recipients, $subject, $body, $options)) {
            return true;
        }

        return $this->lastErrorRed ? core_web_rabbitmq::RESULT_POSPONER : false;
    }

    /**
     * Detecta si el error del SMTP es de conexion (servidor caido, DNS, tiempo agotado).
     * @param string $error texto del printDebugger
     * @return bool
     */
    private function isConnectionError($error)
    {
        $patrones = ["Connection refused", "timed out", "php_network_getaddresses", "getaddrinfo", "Unable to connect", "No route to host", "421 ", "try again later"];
        foreach ($patrones as $patron) {
            if (stripos((string) $error, $patron) !== false) {
                return true;
            }
        }
        return false;
    }
}
