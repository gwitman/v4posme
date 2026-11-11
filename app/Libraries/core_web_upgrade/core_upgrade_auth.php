<?php
//posme:2026-11-10
namespace App\Libraries\core_web_upgrade;

use App\Libraries\core_web_deploy\core_deploy_files;
use App\Libraries\core_web_deploy\core_deploy_rules;
use App\Libraries\core_web_deploy\core_deploy_crypto;

/**
 * Autenticación del servidor central de actualizaciones (core_upgrade_server).
 *
 * Reglas equivalentes a core_deploy_agent::handle(): solo POST, HTTPS (salvo
 * allowHttp o localhost), firma HMAC-SHA256 con ventana de tiempo, nonce de un
 * solo uso y bloqueo por fallos. La diferencia: el secreto es por instalación y
 * se toma del registro WRITEPATH/posme_upgrade_server/server_config.php:
 *
 *   return [
 *       'enabled'   => true,
 *       'allowHttp' => false,
 *       'phpMin'    => '7.4',
 *       'clients'   => [
 *           'cliente_01' => ['secret' => '<64 hex>', 'companyKey' => 'archivo_sin_sql', 'enabled' => true, 'label' => 'Cliente 01'],
 *       ],
 *   ];
 *
 * El companyKey sale siempre del registro, nunca de la petición. Las respuestas
 * se firman con el mismo secreto para que el cliente compruebe su origen.
 */
class core_upgrade_auth
{
    private $dir;
    private $config;

    /**
     * @param string|null $dir Carpeta del servidor; por defecto WRITEPATH/posme_upgrade_server.
     */
    function __construct($dir = null)
    {
        $this->dir = $dir ?: WRITEPATH . core_upgrade_rules::SERVER_DIR;
    }

    /** Carpeta del servidor. */
    function dir()
    {
        return $this->dir;
    }

    /**
     * Lee el registro; null si el servidor no está habilitado.
     *
     * @return array|null
     */
    function config()
    {
        if ($this->config !== null) {
            return $this->config;
        }
        $file   = $this->dir . '/server_config.php';
        $config = is_file($file) ? include $file : null;
        if (!is_array($config) || empty($config['enabled']) || !isset($config['clients']) || !is_array($config['clients'])) {
            return null;
        }
        return $this->config = $config;
    }

    /**
     * Indica si esta instalación tiene el servidor central habilitado.
     *
     * @return bool
     */
    static function isServerEnabled()
    {
        $file = WRITEPATH . core_upgrade_rules::SERVER_DIR . '/server_config.php';
        if (!is_file($file)) {
            return false;
        }
        $config = include $file;
        return is_array($config) && !empty($config['enabled']);
    }

    /**
     * Valida la petición y devuelve la instalación autenticada.
     *
     * @param object $request Petición CI4.
     * @param string $action  Acción pedida.
     * @return array ['installationID','secret','companyKey','label','nonce','payload']
     * @throws \Exception Con el código HTTP como código de la excepción.
     */
    function authenticate($request, $action)
    {
        if (strtoupper($request->getMethod()) !== 'POST') {
            throw new \Exception('Método no permitido.', 405);
        }
        $config = $this->config();
        if ($config === null) {
            throw new \Exception('Servidor de actualizaciones no habilitado.', 404);
        }

        $host    = strtolower((string) $request->getServer('HTTP_HOST'));
        $isLocal = preg_match('/^(localhost|127\.0\.0\.1|\[::1\])(:\d+)?$/', $host) === 1;
        if (!$request->isSecure() && !$isLocal && empty($config['allowHttp'])) {
            throw new \Exception('El servidor solo acepta HTTPS.', 403);
        }
        if ($this->isBlocked($request)) {
            throw new \Exception('Demasiados intentos fallidos; espere unos minutos.', 429);
        }

        $installationID = (string) $request->getHeaderLine('X-Posme-Installation');
        $timestamp      = (string) $request->getHeaderLine('X-Posme-Timestamp');
        $nonce          = (string) $request->getHeaderLine('X-Posme-Nonce');
        $signature      = (string) $request->getHeaderLine('X-Posme-Signature');
        $body           = (string) $request->getBody();

        $client = null;
        if (preg_match('/^[A-Za-z0-9_\-]{3,64}$/', $installationID) === 1 && isset($config['clients'][$installationID]) && is_array($config['clients'][$installationID])) {
            $client = $config['clients'][$installationID];
        }
        $secret = $client && isset($client['secret']) ? (string) $client['secret'] : '';
        if (!$client || empty($client['enabled']) || strlen($secret) < 64) {
            $this->registerFailure($request);
            throw new \Exception('Instalación no registrada o deshabilitada.', 401);
        }

        if (!ctype_digit($timestamp) || abs(time() - (int) $timestamp) > core_deploy_rules::SIGNATURE_WINDOW
            || preg_match('/^[a-f0-9]{32}$/', $nonce) !== 1
            || !core_deploy_crypto::verify($secret, self::signedAction($installationID, $action), $timestamp, $nonce, $body, $signature)) {
            $this->registerFailure($request);
            throw new \Exception('Firma inválida o vencida.', 401);
        }
        if (!$this->useNonce($nonce)) {
            $this->registerFailure($request);
            throw new \Exception('Petición repetida.', 401);
        }

        $payload = json_decode($body, true);
        return [
            'installationID' => $installationID,
            'secret'         => $secret,
            'companyKey'     => isset($client['companyKey']) ? (string) $client['companyKey'] : '',
            'label'          => isset($client['label']) ? (string) $client['label'] : $installationID,
            'nonce'          => $nonce,
            'payload'        => is_array($payload) ? $payload : [],
        ];
    }

    /**
     * Texto de acción que se firma (amarra la firma a la instalación).
     *
     * @param string $installationID Instalación.
     * @param string $action         Acción.
     * @return string
     */
    static function signedAction($installationID, $action)
    {
        return $installationID . '|' . $action;
    }

    /**
     * Firma de una respuesta del servidor.
     *
     * @param string $secret         Secreto de la instalación.
     * @param string $installationID Instalación.
     * @param string $action         Acción.
     * @param string $timestamp      Timestamp de la respuesta.
     * @param string $requestNonce   Nonce de la petición (amarra respuesta y petición).
     * @param string $body           Cuerpo de la respuesta.
     * @return string
     */
    static function signResponse($secret, $installationID, $action, $timestamp, $requestNonce, $body)
    {
        return core_deploy_crypto::sign($secret, 'response|' . self::signedAction($installationID, $action), $timestamp, $requestNonce, $body);
    }

    /** Archivo de fallos de la IP que hace la petición. */
    private function failureFile($request)
    {
        $ip = method_exists($request, 'getIPAddress') ? (string) $request->getIPAddress() : '';
        return $this->dir . '/failures/' . md5($ip) . '.json';
    }

    /** Registra un fallo de autenticación (por IP: un atacante no bloquea a todos los clientes). */
    private function registerFailure($request)
    {
        core_deploy_files::ensureDir($this->dir . '/failures');
        $file = $this->failureFile($request);
        $data = core_deploy_files::readJson($file) ?: ['since' => time(), 'count' => 0];
        if (time() - $data['since'] > core_deploy_rules::AUTH_FAILURE_TTL) {
            $data = ['since' => time(), 'count' => 0];
        }
        $data['count']++;
        core_deploy_files::writeJson($file, $data);
    }

    /** Indica si la IP está bloqueada por fallos. */
    private function isBlocked($request)
    {
        $data = core_deploy_files::readJson($this->failureFile($request));
        return $data && (time() - $data['since']) <= core_deploy_rules::AUTH_FAILURE_TTL && $data['count'] >= core_deploy_rules::MAX_AUTH_FAILURES;
    }

    /** Registra un nonce; false si ya se usó. Limpia los vencidos. */
    private function useNonce($nonce)
    {
        $dir = core_deploy_files::ensureDir($this->dir . '/nonces');
        if (mt_rand(1, 20) === 1) {
            foreach (glob($dir . '/*.n') ?: [] as $file) {
                if (time() - filemtime($file) > core_deploy_rules::SIGNATURE_WINDOW * 2) {
                    @unlink($file);
                }
            }
        }
        $handle = @fopen($dir . '/' . $nonce . '.n', 'x');
        if ($handle === false) {
            return false;
        }
        fclose($handle);
        return true;
    }
}
