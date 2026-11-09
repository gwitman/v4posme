<?php
//posme:2026-11-09
namespace App\Libraries\core_web_deploy;

/**
 * Cliente HTTP firmado (HMAC-SHA256) para el agente core_deploy_agent.
 *
 * Cada petición es POST con cuerpo JSON y las cabeceras:
 *   X-Posme-Timestamp, X-Posme-Nonce, X-Posme-Signature.
 * Exige HTTPS salvo para localhost (pruebas locales).
 */
class core_deploy_agent_client
{
    private $baseUrl;
    private $secret;
    private $timeout;

    /**
     * @param string $baseUrl URL base de la instalación remota (la misma que se abre en el navegador).
     * @param string $secret  Secreto compartido con el agente.
     * @param int    $timeout Segundos por petición.
     */
    function __construct($baseUrl, $secret, $timeout = 180)
    {
        $baseUrl = rtrim(trim((string) $baseUrl), '/');
        $parts   = parse_url($baseUrl);
        if (!$parts || !isset($parts['scheme'], $parts['host'])) {
            throw new \Exception('URL del agente inválida.');
        }
        $isLocal = in_array(strtolower($parts['host']), ['localhost', '127.0.0.1', '::1'], true);
        if (strtolower($parts['scheme']) !== 'https' && !$isLocal) {
            throw new \Exception('La URL del agente debe usar HTTPS.');
        }
        if (strlen((string) $secret) < 32) {
            throw new \Exception('El secreto del agente no está configurado.');
        }
        $this->baseUrl = $baseUrl;
        $this->secret  = (string) $secret;
        $this->timeout = (int) $timeout;
    }

    /**
     * Ejecuta una acción del agente.
     *
     * @param string $action  Acción (ping, upload_chunk, ...).
     * @param array  $payload Datos.
     * @return array Respuesta 'data' del agente.
     */
    function call($action, array $payload = [])
    {
        if (preg_match('/^[a-z_]+$/', $action) !== 1) {
            throw new \Exception('Acción del agente inválida.');
        }
        if (!function_exists('curl_init')) {
            throw new \Exception('La extensión curl de PHP no está disponible.');
        }

        $body      = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);
        $timestamp = (string) time();
        $nonce     = core_deploy_crypto::nonce();
        $signature = core_deploy_crypto::sign($this->secret, $action, $timestamp, $nonce, $body);

        $curl = curl_init($this->baseUrl . '/core_deploy_agent/' . $action);
        curl_setopt_array($curl, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_CONNECTTIMEOUT => 20,
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
                'X-Posme-Timestamp: ' . $timestamp,
                'X-Posme-Nonce: ' . $nonce,
                'X-Posme-Signature: ' . $signature,
            ],
        ]);
        $response = curl_exec($curl);
        $errno    = curl_errno($curl);
        $error    = curl_error($curl);
        $status   = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($errno) {
            throw new \Exception('No se pudo contactar al agente: ' . $error);
        }
        $json = json_decode((string) $response, true);
        // Un 404 de CodeIgniter también es JSON (en modo development): sin la ruta del agente no hay agente
        if ($status === 404) {
            throw new \Exception('El agente no está instalado en el servidor remoto (HTTP 404: no existe la ruta core_deploy_agent/' . $action . '). Use "Instalar agente por SFTP/FTP" con "Subir también el código del agente".');
        }
        if (!is_array($json)) {
            throw new \Exception('Respuesta inválida del agente (HTTP ' . $status . ').');
        }
        if (!empty($json['error'])) {
            throw new \Exception('Agente: ' . (isset($json['message']) ? $json['message'] : 'error desconocido'));
        }
        // Una respuesta válida del agente siempre trae error=false y data; cualquier otra cosa no viene del agente
        if (!array_key_exists('error', $json) || !isset($json['data']) || !is_array($json['data'])) {
            throw new \Exception('La URL no responde como agente de posMe (HTTP ' . $status . '). Revise la URL de la instalación remota.');
        }
        return $json['data'];
    }

    /**
     * Sube un archivo local al agente por bloques.
     *
     * @param string        $deployID Identificador del despliegue.
     * @param string        $kind     package | data
     * @param string        $file     Ruta local.
     * @param int           $index    Bloque a enviar (0..n-1).
     * @return array ['index', 'total', 'done' => bool]
     */
    function uploadChunk($deployID, $kind, $file, $index)
    {
        $size  = filesize($file);
        $total = max(1, (int) ceil($size / core_deploy_rules::CHUNK_SIZE));
        if ($index < 0 || $index >= $total) {
            throw new \Exception('Bloque fuera de rango.');
        }
        $handle = fopen($file, 'rb');
        fseek($handle, $index * core_deploy_rules::CHUNK_SIZE);
        $content = fread($handle, core_deploy_rules::CHUNK_SIZE);
        fclose($handle);

        $this->call('upload_chunk', [
            'deployID' => $deployID,
            'kind'     => $kind,
            'index'    => $index,
            'total'    => $total,
            'sha256'   => hash('sha256', $content),
            'content'  => base64_encode($content),
        ]);

        if ($index === $total - 1) {
            $this->call('upload_commit', [
                'deployID' => $deployID,
                'kind'     => $kind,
                'total'    => $total,
                'size'     => $size,
                'sha256'   => hash_file('sha256', $file),
            ]);
        }
        return ['index' => $index, 'total' => $total, 'done' => $index === $total - 1];
    }
}
