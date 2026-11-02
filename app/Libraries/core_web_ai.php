<?php
//posme:2026-11-02
namespace App\Libraries;

use App\Models\Ai_Chat_Model;

/**
 * Asistente IA "Jordania": cliente de la Messages API de Anthropic (PHP + cURL),
 * armado del system prompt, bucle de herramientas (tool use), cuotas y limite de frecuencia.
 *
 * Flujo de ask():
 *   1. Valida configuracion, longitud, frecuencia y cuota mensual (no llama a la API si se excede).
 *   2. Arma system prompt (fijo + esquema con cache) y el historial reciente.
 *   3. Llama a la API; mientras stop_reason == tool_use ejecuta herramientas con el companyID de la SESION.
 *   4. Suma el uso (tokens) de CADA llamada y guarda pregunta y respuesta.
 */
class core_web_ai
{
    const API_VERSION      = "2023-06-01";
    const API_URL          = "https://api.anthropic.com/v1/messages";
    const MODELO_DEFECTO   = "claude-haiku-5-5";
    const CACHE_INTERNET   = "ai_jordania_internet_ok";
    const CONNECT_TIMEOUT  = 5;
    const TIMEOUT          = 60;
    const INTERNET_MINUTOS = 5;

    /** Parametros de empresa con secretos: nunca se envian al navegador. */
    const PARAMETROS_SECRETOS = ["AI_API_KEY", "AI_READONLY_USERNAME", "AI_READONLY_PASSWORD"];

    /** Mensajes amigables por codigo de error. */
    public static $MENSAJES = [
        "sin_permiso"        => "No tienes permiso para usar a Jordania. Solicita acceso a \"Asistente IA\" a tu administrador.",
        "deshabilitado"      => "Jordania no está habilitada para esta empresa.",
        "sin_configuracion"  => "El asistente no está configurado (falta la clave de API). Contacta al administrador.",
        "pregunta_vacia"     => "Escribe tu pregunta para Jordania.",
        "pregunta_larga"     => "La pregunta es demasiado larga. Resúmela, por favor.",
        "limite_frecuencia"  => "Estás enviando muchas preguntas seguidas. Espera un minuto e intenta de nuevo.",
        "cuota_agotada"      => "Se alcanzó el límite mensual del asistente.",
        "sin_conexion"       => "No se pudo conectar con el asistente. Revisa tu conexión a internet.",
        "tiempo_agotado"     => "Jordania tardó demasiado en responder. Intenta de nuevo.",
        "api_key_invalida"   => "El asistente no está configurado correctamente (clave de API inválida). Contacta al administrador.",
        "saturado"           => "Jordania está recibiendo muchas solicitudes. Intenta de nuevo en un momento.",
        "error_api"          => "Jordania no pudo responder en este momento. Intenta de nuevo.",
        "error_interno"      => "Ocurrió un error con el asistente. El resto del sistema sigue funcionando normalmente.",
    ];

    private $core_web_parameter;
    private $core_web_ai_tools;
    private $Ai_Chat_Model;

    function __construct()
    {
        $this->core_web_parameter = new core_web_parameter();
        $this->core_web_ai_tools  = new core_web_ai_tools();
        $this->Ai_Chat_Model      = new Ai_Chat_Model();
    }

    /* =================================================================================
       CONFIGURACION
    ================================================================================= */

    /**
     * Valor de un parametro de empresa o el defecto si no existe (el asistente nunca debe fallar por eso).
     *
     * @param string $nombre    Nombre del parametro.
     * @param int    $companyID Empresa.
     * @param string $defecto   Valor por defecto.
     * @return string
     */
    function getParametro($nombre, $companyID, $defecto)
    {
        try {
            $objeto = $this->core_web_parameter->getParameter($nombre, $companyID);
            if ($objeto && isset($objeto->value) && trim((string) $objeto->value) !== "") {
                return trim((string) $objeto->value);
            }
        } catch (\Throwable $ex) {
            // Parametro no instalado: se usa el defecto
        }
        return $defecto;
    }

    /**
     * Indica si la empresa tiene el asistente habilitado (AI_ASSISTANT_ENABLED = true).
     *
     * @param int $companyID Empresa.
     * @return bool
     */
    function estaHabilitado($companyID)
    {
        $valor = strtolower($this->getParametro("AI_ASSISTANT_ENABLED", $companyID, "false"));
        return in_array($valor, ["true", "1", "si", "yes"]);
    }

    /**
     * API key de la empresa (parametro AI_API_KEY).
     *
     * @param int $companyID Empresa de la sesion.
     * @return string
     */
    function getApiKey($companyID)
    {
        return (string) core_web_ai_tools::getConfig($companyID)->apiKey;
    }

    /**
     * Indica si la sesion tiene acceso al menu_element "Asistente IA" (address app_ai_chat/index).
     * Mismo mecanismo de permisos que el resto de pantallas (menus cargados en el login).
     *
     * @param array $dataSession Sesion.
     * @return bool
     */
    static function tieneAccesoAsistente($dataSession)
    {
        if (APP_NEED_AUTHENTICATION != true) {
            return true;
        }
        $permission = new \App\Libraries\core_web_permission();
        return (bool) $permission->urlPermited(
            "app_ai_chat",
            "index",
            URL_SUFFIX,
            $dataSession["menuTop"] ?? null,
            $dataSession["menuLeft"] ?? null,
            $dataSession["menuBodyReport"] ?? null,
            $dataSession["menuBodyTop"] ?? null,
            $dataSession["menuHiddenPopup"] ?? null
        );
    }

    /**
     * Respuesta de error estandar del asistente.
     *
     * @param string $codigo Clave de self::$MENSAJES.
     * @return array
     */
    static function error($codigo)
    {
        return [
            "ok"      => false,
            "error"   => $codigo,
            "message" => self::$MENSAJES[$codigo] ?? self::$MENSAJES["error_interno"],
        ];
    }

    /* =================================================================================
       PREGUNTA
    ================================================================================= */

    /**
     * Procesa una pregunta del usuario y devuelve la respuesta de Jordania.
     *
     * @param array  $dataSession Sesion del usuario (fuente UNICA de companyID/userID).
     * @param string $pregunta    Texto escrito por el usuario.
     * @return array ['ok' => bool, 'error' => codigo, 'message' => texto, 'respuesta' => markdown, 'conversacionID' => int]
     */
    function ask($dataSession, $pregunta)
    {
        $companyID = (int) $dataSession["user"]->companyID;
        $userID    = (int) $dataSession["user"]->userID;
        $pregunta  = trim((string) $pregunta);
        $ahora     = helper_getDateTime();
        $periodo   = substr($ahora, 0, 7);

        // 1. Validaciones previas (ninguna llama a la API)
        $apiKey = $this->getApiKey($companyID);
        if ($apiKey === "") {
            return self::error("sin_configuracion");
        }
        if ($pregunta === "") {
            return self::error("pregunta_vacia");
        }
        $maxLargo = (int) $this->getParametro("AI_MAX_QUESTION_LENGTH", $companyID, "1000");
        if (mb_strlen($pregunta) > max(50, $maxLargo)) {
            return self::error("pregunta_larga");
        }

        $porMinuto = (int) $this->getParametro("AI_RATE_PER_MINUTE", $companyID, "6");
        $desde     = helper_getDateTime_Object()->modify("-60 seconds")->format("Y-m-d H:i:s");
        if ($porMinuto > 0 && $this->Ai_Chat_Model->get_countPreguntasDesde($companyID, $userID, $desde) >= $porMinuto) {
            return self::error("limite_frecuencia");
        }

        $cuotaEmpresa = (int) $this->getParametro("AI_QUOTA_COMPANY_MONTH", $companyID, "2000000");
        if ($cuotaEmpresa > 0 && $this->Ai_Chat_Model->get_tokensPeriodo($companyID, $periodo) >= $cuotaEmpresa) {
            return self::error("cuota_agotada");
        }
        $cuotaUsuario = (int) $this->getParametro("AI_QUOTA_USER_MONTH", $companyID, "0");
        if ($cuotaUsuario > 0 && $this->Ai_Chat_Model->get_tokensPeriodo($companyID, $periodo, $userID) >= $cuotaUsuario) {
            return self::error("cuota_agotada");
        }

        // 2. Conversacion e historial
        $conversacion   = $this->Ai_Chat_Model->get_rowConversacionActiva($companyID, $userID);
        $conversacionID = $conversacion
            ? (int) $conversacion->conversacionID
            : (int) $this->Ai_Chat_Model->insert_conversacion(["companyID" => $companyID, "userID" => $userID, "isActive" => 1, "createdOn" => $ahora, "updatedOn" => $ahora]);

        $historial = (int) $this->getParametro("AI_HISTORY_MESSAGES", $companyID, "8");
        $messages  = $this->getHistorialParaApi($companyID, $userID, $conversacionID, max(0, min(40, $historial)));
        $messages  = self::agregarMensaje($messages, "user", $pregunta);

        $this->Ai_Chat_Model->insert_mensaje([
            "conversacionID" => $conversacionID,
            "companyID"      => $companyID,
            "userID"         => $userID,
            "rol"            => "user",
            "contenidoJson"  => json_encode(["texto" => $pregunta], JSON_UNESCAPED_UNICODE),
            "createdOn"      => $ahora,
        ]);

        // 3. Herramientas y prompt
        $this->core_web_ai_tools->init($dataSession);
        $tools = $this->core_web_ai_tools->getDefinicionHerramientas();
        if (count($tools) > 0) {
            $tools[count($tools) - 1]["cache_control"] = ["type" => "ephemeral"];
        }

        $modelo = $this->getParametro("AI_MODEL", $companyID, self::MODELO_DEFECTO);
        if (!preg_match('/^claude-[a-z0-9.\-]+$/', $modelo)) {
            $modelo = self::MODELO_DEFECTO;
        }
        $maxTokens     = max(256, min(8192, (int) $this->getParametro("AI_MAX_TOKENS", $companyID, "1024")));
        $maxIteraciones = max(1, min(12, (int) $this->getParametro("AI_MAX_TOOL_ITERATIONS", $companyID, "6")));

        $payloadBase = [
            "model"      => $modelo,
            "max_tokens" => $maxTokens,
            "system"     => $this->buildSystem($dataSession),
            "tools"      => $tools,
        ];

        // 4. Bucle de herramientas
        $tokensIn   = 0;
        $tokensOut  = 0;
        $respuesta  = "";
        $iteracion  = 0;

        while (true) {
            $payload             = $payloadBase;
            $payload["messages"] = $messages;
            if ($iteracion >= $maxIteraciones) {
                // Ultima vuelta: se obliga a responder con texto
                $payload["tool_choice"] = ["type" => "none"];
            }

            $api = $this->callApi($apiKey, $payload);
            if (isset($api["usage"])) {
                $this->Ai_Chat_Model->upsert_uso($companyID, $userID, $periodo, $api["usage"], $iteracion == 0 ? 1 : 0, helper_getDateTime());
                $tokensIn  += (int) ($api["usage"]["input_tokens"] ?? 0) + (int) ($api["usage"]["cache_read_input_tokens"] ?? 0) + (int) ($api["usage"]["cache_creation_input_tokens"] ?? 0);
                $tokensOut += (int) ($api["usage"]["output_tokens"] ?? 0);
            }
            if (!$api["ok"]) {
                return self::error($api["error"]) + ["conversacionID" => $conversacionID];
            }

            $body      = $api["body"];
            $contenido = $body["content"] ?? [];
            $messages[] = ["role" => "assistant", "content" => $contenido];

            if (($body["stop_reason"] ?? "") == "tool_use" && $iteracion < $maxIteraciones) {
                $resultados = [];
                foreach ($contenido as $bloque) {
                    if (($bloque["type"] ?? "") != "tool_use") {
                        continue;
                    }
                    $ejecucion    = $this->core_web_ai_tools->ejecutar($bloque["name"] ?? "", $bloque["input"] ?? []);
                    $resultado    = [
                        "type"        => "tool_result",
                        "tool_use_id" => $bloque["id"],
                        "content"     => $ejecucion["contenido"],
                    ];
                    if (!$ejecucion["ok"]) {
                        $resultado["is_error"] = true;
                    }
                    $resultados[] = $resultado;
                }
                $messages[] = ["role" => "user", "content" => $resultados];
                $iteracion++;
                continue;
            }

            foreach ($contenido as $bloque) {
                if (($bloque["type"] ?? "") == "text") {
                    $respuesta .= ($respuesta === "" ? "" : "\n\n") . $bloque["text"];
                }
            }
            break;
        }

        if (trim($respuesta) === "") {
            $respuesta = "No encontré una respuesta para eso. ¿Puedes reformular la pregunta?";
        }

        // 5. Guardar respuesta
        $this->Ai_Chat_Model->insert_mensaje([
            "conversacionID" => $conversacionID,
            "companyID"      => $companyID,
            "userID"         => $userID,
            "rol"            => "assistant",
            "contenidoJson"  => json_encode(["texto" => $respuesta, "modelo" => $modelo, "iteraciones" => $iteracion], JSON_UNESCAPED_UNICODE),
            "tokensIn"       => $tokensIn,
            "tokensOut"      => $tokensOut,
            "createdOn"      => helper_getDateTime(),
        ]);
        $this->Ai_Chat_Model->update_conversacionFecha($conversacionID, helper_getDateTime());

        return [
            "ok"             => true,
            "error"          => "",
            "message"        => "",
            "respuesta"      => $respuesta,
            "conversacionID" => $conversacionID,
        ];
    }

    /**
     * Historial reciente como mensajes de texto (sin bloques de herramientas, para ahorrar tokens).
     *
     * @return array mensajes [{role, content}]
     */
    function getHistorialParaApi($companyID, $userID, $conversacionID, $limite)
    {
        $messages = [];
        if ($limite <= 0) {
            return $messages;
        }
        foreach ($this->Ai_Chat_Model->get_rowMensajes($companyID, $userID, $conversacionID, $limite) as $m) {
            $datos = json_decode((string) $m->contenidoJson, true);
            $texto = is_array($datos) ? (string) ($datos["texto"] ?? "") : "";
            if ($texto === "" || !in_array($m->rol, ["user", "assistant"])) {
                continue;
            }
            $messages = self::agregarMensaje($messages, $m->rol, $texto);
        }
        // La API exige que la conversacion empiece con el usuario
        while (count($messages) > 0 && $messages[0]["role"] != "user") {
            array_shift($messages);
        }
        return $messages;
    }

    /**
     * Agrega un mensaje de texto respetando la alternancia user/assistant (une mensajes seguidos del mismo rol).
     *
     * @param array  $messages Mensajes actuales.
     * @param string $rol      user | assistant.
     * @param string $texto    Contenido.
     * @return array
     */
    static function agregarMensaje($messages, $rol, $texto)
    {
        $ultimo = count($messages) - 1;
        if ($ultimo >= 0 && $messages[$ultimo]["role"] == $rol && is_string($messages[$ultimo]["content"])) {
            $messages[$ultimo]["content"] .= "\n\n" . $texto;
            return $messages;
        }
        $messages[] = ["role" => $rol, "content" => $texto];
        return $messages;
    }

    /* =================================================================================
       SYSTEM PROMPT
    ================================================================================= */

    /**
     * Bloques del system prompt: parte fija + esquema (con cache) y contexto variable (sin cache).
     *
     * @param array $dataSession Sesion.
     * @return array
     */
    function buildSystem($dataSession)
    {
        $companyID = (int) $dataSession["user"]->companyID;
        $esAdmin   = !empty($dataSession["role"]->isAdmin);
        $esquema   = is_file(APPPATH . "Ai/esquema_ia.md") ? file_get_contents(APPPATH . "Ai/esquema_ia.md") : "";

        $fijo = "Eres **Jordania**, la asistente de inteligencia artificial de posMe (sistema ERP/POS). "
            . "Respondes siempre en español, de forma breve, clara y amable.\n\n"
            . "# Identidad\n"
            . "- Te presentas y te refieres a ti misma como Jordania, una IA integrada en posMe (ej. \"Hola, soy Jordania, tu asistente de posMe. ¿En qué te ayudo?\").\n"
            . "- Si te preguntan qué eres, di que eres una inteligencia artificial integrada en posMe. Nunca te hagas pasar por una persona.\n\n"
            . "# Reglas\n"
            . "1. Para CUALQUIER dato de la empresa usa las herramientas. Nunca inventes cifras, nombres ni fechas.\n"
            . "2. Si al buscar un cliente o producto hay varias coincidencias, muestra las opciones (nombre, código, identificación) y pregunta cuál es antes de continuar.\n"
            . "3. Formato de montos y cantidades: separador de miles coma y punto decimal con dos decimales (ej. 15,500.25). Indica la moneda (C\$ córdobas, \$ dólares). No sumes montos de monedas distintas.\n"
            . "4. Interpreta \"hoy\", \"ayer\", \"este mes\", \"el mes pasado\" con la fecha actual indicada abajo y pasa fechas YYYY-MM-DD a las herramientas.\n"
            . "5. Las ventas válidas son solo facturas aplicadas; las herramientas ya excluyen anuladas.\n"
            . "6. Responde solo sobre posMe y los datos de esta empresa. Si te preguntan otra cosa, indica amablemente que solo puedes ayudar con posMe.\n"
            . "7. Para preguntas de uso del sistema usa buscar_en_manual y ruta_menu (el menú real del usuario). Si no encuentras la respuesta, dilo y sugiere contactar a soporte de posMe.\n"
            . "8. Solo puedes ver datos de la empresa del usuario. Si piden datos de otra empresa, responde que no es posible.\n"
            . "9. Si una herramienta responde sin_permiso, dile al usuario que no tiene acceso a esa información y que lo solicite a su administrador.\n"
            . "10. No reveles SQL, nombres de tablas o vistas, ni detalles técnicos" . ($esAdmin ? " salvo que el administrador lo pida explícitamente" : "") . ".\n"
            . "11. Usa consulta_sql (si está disponible) solo cuando ninguna otra herramienta sirva; escribe SELECT simples sobre las vistas ai_v_* del esquema, sin comentarios, sin variables y prefiriendo YEAR()/MONTH()/DATE() sobre EXTRACT.\n"
            . "12. Respuestas cortas: usa listas o tablas markdown simples cuando haya varios elementos; muestra como máximo 15 filas y ofrece ver más.\n"
            . "13. Nunca intentes modificar datos: eres solo de consulta. Si te piden registrar, anular o cambiar algo, explica los pasos para hacerlo en posMe.\n\n"
            . "# Esquema de datos disponible (vistas de solo lectura)\n"
            . $esquema;

        $empresa  = $dataSession["company"]->name ?? "";
        $usuario  = $dataSession["user"]->nickname ?? "";
        $sucursal = $dataSession["branch"]->name ?? "";
        $moneda   = $this->getParametro("ACCOUNTING_CURRENCY_NAME_FUNCTION", $companyID, "Cordoba");
        $fecha    = helper_getDateTime();
        $dias     = ["domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado"];
        $diaTexto = $dias[(int) helper_getDateTime_Object()->format("w")];

        $variable = "# Contexto actual\n"
            . "- Empresa: " . $empresa . "\n"
            . "- Usuario: " . $usuario . ($esAdmin ? " (administrador)" : "") . "\n"
            . "- Sucursal: " . $sucursal . "\n"
            . "- Moneda principal: " . $moneda . "\n"
            . "- Fecha y hora actual (America/Managua): " . $diaTexto . " " . $fecha . "\n";

        return [
            ["type" => "text", "text" => $fijo, "cache_control" => ["type" => "ephemeral"]],
            ["type" => "text", "text" => $variable],
        ];
    }

    /* =================================================================================
       API
    ================================================================================= */

    /**
     * Llama a la Messages API. Reintenta una vez con espera ante 429/529/503.
     *
     * @param string $apiKey  API key.
     * @param array  $payload Cuerpo de la solicitud.
     * @return array ['ok' => bool, 'status' => int, 'body' => array, 'usage' => array|null, 'error' => codigo]
     */
    function callApi($apiKey, $payload)
    {
        $url      = self::API_URL;
        $connect  = self::CONNECT_TIMEOUT;
        $timeout  = self::TIMEOUT;
        $cuerpo   = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);

        for ($intento = 1; $intento <= 2; $intento++) {
            $respuesta = $this->http($url, $apiKey, $cuerpo, $connect, $timeout);

            if ($respuesta["curlError"] !== "") {
                log_message("error", "core_web_ai cURL: " . $respuesta["curlError"]);
                return ["ok" => false, "status" => 0, "body" => [], "usage" => null, "error" => $respuesta["timeout"] ? "tiempo_agotado" : "sin_conexion"];
            }

            $status = $respuesta["status"];
            $body   = json_decode($respuesta["body"], true);
            $body   = is_array($body) ? $body : [];

            if ($status == 200) {
                return ["ok" => true, "status" => 200, "body" => $body, "usage" => $body["usage"] ?? null, "error" => ""];
            }

            if (in_array($status, [429, 503, 529]) && $intento == 1) {
                $espera = (int) ($respuesta["retryAfter"] ?: 2);
                sleep(max(1, min(5, $espera)));
                continue;
            }

            log_message("error", "core_web_ai API HTTP " . $status . ": " . mb_substr($respuesta["body"], 0, 500));
            if ($status == 401 || $status == 403) {
                return ["ok" => false, "status" => $status, "body" => $body, "usage" => null, "error" => "api_key_invalida"];
            }
            if (in_array($status, [429, 503, 529])) {
                return ["ok" => false, "status" => $status, "body" => $body, "usage" => null, "error" => "saturado"];
            }
            return ["ok" => false, "status" => $status, "body" => $body, "usage" => null, "error" => "error_api"];
        }

        return ["ok" => false, "status" => 0, "body" => [], "usage" => null, "error" => "error_api"];
    }

    /**
     * POST con cURL. Nunca lanza excepciones.
     *
     * @return array ['status', 'body', 'curlError', 'timeout', 'retryAfter']
     */
    private function http($url, $apiKey, $cuerpo, $connectTimeout, $timeout)
    {
        $retryAfter = 0;
        $ch         = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $cuerpo,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => $connectTimeout,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER     => [
                "x-api-key: " . $apiKey,
                "anthropic-version: " . self::API_VERSION,
                "content-type: application/json",
            ],
            CURLOPT_HEADERFUNCTION => function ($curl, $linea) use (&$retryAfter) {
                if (stripos($linea, "retry-after:") === 0) {
                    $retryAfter = (int) trim(substr($linea, 12));
                }
                return strlen($linea);
            },
        ]);

        $body    = curl_exec($ch);
        $errno   = curl_errno($ch);
        $error   = $errno ? curl_error($ch) : "";
        $status  = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            "status"     => $status,
            "body"       => is_string($body) ? $body : "",
            "curlError"  => $error,
            "timeout"    => $errno == CURLE_OPERATION_TIMEDOUT,
            "retryAfter" => $retryAfter,
        ];
    }

    /**
     * Verifica si el servidor tiene salida a la API (cualquier respuesta HTTP cuenta como disponible).
     * El resultado se guarda en cache del servidor unos minutos para no probar en cada pagina.
     *
     * @return bool
     */
    function hayInternet()
    {
        $cache = null;
        try {
            $cache   = \Config\Services::cache();
            $guardado = $cache->get(self::CACHE_INTERNET);
            if ($guardado !== null) {
                return $guardado === "si";
            }
        } catch (\Throwable $ex) {
            $cache = null;
        }

        $url     = self::API_URL;
        $minutos = self::INTERNET_MINUTOS;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_NOBODY         => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT        => 5,
        ]);
        curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $ok = $status > 0;
        if ($cache !== null) {
            try {
                // Si no hay salida se recuerda menos tiempo para recuperarse rapido
                $cache->save(self::CACHE_INTERNET, $ok ? "si" : "no", $ok ? $minutos * 60 : 60);
            } catch (\Throwable $ex) {
                // Sin cache: se probara de nuevo la proxima vez
            }
        }
        return $ok;
    }
}
