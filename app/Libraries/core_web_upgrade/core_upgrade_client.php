<?php
//posme:2026-11-10
namespace App\Libraries\core_web_upgrade;

use App\Libraries\core_web_deploy\core_deploy_crypto;
use App\Libraries\core_web_deploy\core_deploy_rules;

/**
 * Cliente HTTP firmado hacia el servidor central (core_upgrade_server).
 *
 * Cada petición es POST JSON con X-Posme-Installation, X-Posme-Timestamp,
 * X-Posme-Nonce y X-Posme-Signature. La respuesta trae X-Posme-Response-Timestamp
 * y X-Posme-Response-Signature, que se verifican antes de usar los datos.
 */
class core_upgrade_client
{
    private $baseUrl;
    private $installationID;
    private $secret;
    private $timeout;
    private $curl;

    /** Segundos para establecer la conexión (TCP + HTTPS); el servidor central puede tardar más de 20 s en la negociación. */
    const CONNECT_TIMEOUT = 60;

    /**
     * @param string $baseUrl        URL base del servidor central.
     * @param string $installationID Identificador de esta instalación en el registro del servidor.
     * @param string $secret         Secreto compartido.
     * @param bool   $allowHttp      Permite http (solo pruebas).
     * @param int    $timeout        Segundos por petición.
     */
    function __construct($baseUrl, $installationID, $secret, $allowHttp = false, $timeout = 120)
    {
        $baseUrl = rtrim(trim((string) $baseUrl), '/');
        $parts   = parse_url($baseUrl);
        if (!$parts || !isset($parts['scheme'], $parts['host'])) {
            throw new \Exception('La URL del servidor de actualizaciones es inválida.');
        }
        if (strtolower($parts['scheme']) !== 'https' && !$allowHttp) {
            throw new \Exception('La URL del servidor de actualizaciones debe usar HTTPS.');
        }
        if (preg_match('/^[A-Za-z0-9_\-]{3,64}$/', (string) $installationID) !== 1) {
            throw new \Exception('El identificador de la instalación no está configurado.');
        }
        if (strlen((string) $secret) < 64) {
            throw new \Exception('El secreto de la instalación no está configurado.');
        }
        $this->baseUrl        = $baseUrl;
        $this->installationID = (string) $installationID;
        $this->secret         = (string) $secret;
        $this->timeout        = (int) $timeout;
    }

    /** Cierra la conexión reutilizada al terminar la petición. */
    function __destruct()
    {
        if ($this->curl) {
            curl_close($this->curl);
            $this->curl = null;
        }
    }

    /**
     * Manejador curl reutilizable: las llamadas de una misma petición (p. ej. los bloques
     * de la descarga) comparten la conexión abierta y no repiten la negociación HTTPS.
     *
     * @return resource|\CurlHandle
     */
    private function handle()
    {
        if ($this->curl) {
            // curl_reset limpia las opciones pero conserva la caché de conexiones
            curl_reset($this->curl);
        } else {
            $this->curl = curl_init();
        }
        return $this->curl;
    }

    /**
     * Ejecuta una acción del servidor central.
     *
     * @param string $action  ping | manifest | package_chunk | schema_snapshot | config_data | client_script
     * @param array  $payload Datos.
     * @return array 'data' de la respuesta verificada.
     */
    function call($action, array $payload = [])
    {
        if (preg_match('/^[a-z_]+$/', $action) !== 1) {
            throw new \Exception('Acción inválida.');
        }
        if (!function_exists('curl_init')) {
            throw new \Exception('La extensión curl de PHP no está disponible.');
        }

        $body      = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);
        $timestamp = (string) time();
        $nonce     = core_deploy_crypto::nonce();
        $signature = core_deploy_crypto::sign($this->secret, core_upgrade_auth::signedAction($this->installationID, $action), $timestamp, $nonce, $body);
        $headers   = [];

        $curl = $this->handle();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->baseUrl . '/core_upgrade_server/' . $action,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_CONNECTTIMEOUT => self::CONNECT_TIMEOUT,
            CURLOPT_TCP_KEEPALIVE  => 1,
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_ENCODING       => '',
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
                'X-Posme-Installation: ' . $this->installationID,
                'X-Posme-Timestamp: ' . $timestamp,
                'X-Posme-Nonce: ' . $nonce,
                'X-Posme-Signature: ' . $signature,
            ],
            CURLOPT_HEADERFUNCTION => function ($curl, $line) use (&$headers) {
                $parts = explode(':', $line, 2);
                if (count($parts) === 2) {
                    $headers[strtolower(trim($parts[0]))] = trim($parts[1]);
                }
                return strlen($line);
            },
        ]);
        $response = curl_exec($curl);
        $errno    = curl_errno($curl);
        $error    = curl_error($curl);
        $status   = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // La conexión queda abierta para la siguiente llamada; se cierra en __destruct

        if ($errno) {
            throw new \Exception('No se pudo contactar al servidor de actualizaciones: ' . $error);
        }
        // json_encode nunca produce espacios al inicio o al final: se quitan los que agregue
        // salida suelta del servidor (líneas en blanco tras el cierre PHP) para no invalidar la firma
        $response = trim((string) $response);
        $json     = json_decode($response, true);
        if (!is_array($json)) {
            throw new \Exception('El servidor de actualizaciones respondió algo inválido (HTTP ' . $status . ').');
        }

        $respTime = isset($headers['x-posme-response-timestamp']) ? $headers['x-posme-response-timestamp'] : '';
        $respSign = isset($headers['x-posme-response-signature']) ? strtolower($headers['x-posme-response-signature']) : '';
        $signed   = $respSign !== '' && ctype_digit($respTime)
            && abs(time() - (int) $respTime) <= core_deploy_rules::SIGNATURE_WINDOW
            && hash_equals(core_upgrade_auth::signResponse($this->secret, $this->installationID, $action, $respTime, $nonce, $response), $respSign);

        if (!empty($json['error'])) {
            // Los rechazos previos a la autenticación no pueden venir firmados: solo se muestra el mensaje
            $message = isset($json['message']) && is_string($json['message']) ? substr($json['message'], 0, 300) : 'error desconocido';
            throw new \Exception('Servidor de actualizaciones (HTTP ' . $status . '): ' . $message);
        }
        if (!$signed) {
            throw new \Exception('La respuesta del servidor de actualizaciones no tiene una firma válida; se descarta.');
        }
        if (!isset($json['data']) || !is_array($json['data'])) {
            throw new \Exception('Respuesta incompleta del servidor de actualizaciones.');
        }
        return $json['data'];
    }

    /**
     * Descarga un bloque de un paquete y lo guarda verificando su hash.
     *
     * @param string $name  Nombre del zip.
     * @param int    $index Bloque.
     * @param string $file  Ruta destino del bloque.
     * @return array ['bytes' => int, 'total' => int]
     */
    function downloadChunk($name, $index, $file)
    {
        $data    = $this->call('package_chunk', ['name' => $name, 'index' => (int) $index]);
        $content = base64_decode(isset($data['content']) ? (string) $data['content'] : '', true);
        if ($content === false || !isset($data['sha256']) || !hash_equals((string) $data['sha256'], hash('sha256', $content))) {
            throw new \Exception('El bloque ' . $index . ' de ' . $name . ' llegó dañado (hash distinto).');
        }
        if (file_put_contents($file, $content, LOCK_EX) === false) {
            throw new \Exception('No se pudo guardar el bloque ' . $index . ' de ' . $name . '.');
        }
        return ['bytes' => strlen($content), 'total' => isset($data['total']) ? (int) $data['total'] : 0];
    }
}
