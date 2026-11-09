<?php
//posme:2026-11-09
namespace App\Libraries\core_web_deploy;

/**
 * Cifrado de credenciales y firma de peticiones del despliegue remoto.
 *
 * - Credenciales: AES-256-GCM con una llave local de 32 bytes guardada en
 *   WRITEPATH/posme_deploy/deploy.key (fuera de la BD y fuera de git). En la BD
 *   solo queda el texto cifrado "v1:<base64(iv|tag|cifrado)>".
 * - Peticiones al agente: HMAC-SHA256 sobre método, acción, timestamp, nonce y
 *   SHA-256 del cuerpo.
 */
class core_deploy_crypto
{
    const PREFIX = 'v1:';

    private $keyFile;
    private $key;

    /**
     * @param string|null $keyFile Ruta de la llave; por defecto WRITEPATH/posme_deploy/deploy.key.
     */
    function __construct($keyFile = null)
    {
        $this->keyFile = $keyFile ?: WRITEPATH . core_deploy_rules::CONSOLE_DIR . '/deploy.key';
    }

    /**
     * Carga la llave local o la crea la primera vez.
     *
     * @return string Llave binaria de 32 bytes.
     */
    private function key()
    {
        if ($this->key !== null) {
            return $this->key;
        }

        if (is_file($this->keyFile)) {
            $raw = base64_decode(trim((string) file_get_contents($this->keyFile)), true);
            if ($raw === false || strlen($raw) !== 32) {
                throw new \Exception('La llave de cifrado del despliegue está dañada: ' . basename($this->keyFile));
            }
            return $this->key = $raw;
        }

        $dir = dirname($this->keyFile);
        core_deploy_files::ensureDir($dir);
        $raw = random_bytes(32);
        if (file_put_contents($this->keyFile, base64_encode($raw), LOCK_EX) === false) {
            throw new \Exception('No se pudo crear la llave de cifrado del despliegue.');
        }
        @chmod($this->keyFile, 0600);
        return $this->key = $raw;
    }

    /**
     * Cifra un texto.
     *
     * @param string|null $plain Texto plano.
     * @return string|null Texto cifrado o null si el texto viene vacío.
     */
    function encrypt($plain)
    {
        if ($plain === null || $plain === '') {
            return null;
        }
        $iv     = random_bytes(12);
        $tag    = '';
        $cipher = openssl_encrypt((string) $plain, 'aes-256-gcm', $this->key(), OPENSSL_RAW_DATA, $iv, $tag);
        if ($cipher === false) {
            throw new \Exception('No se pudo cifrar la credencial.');
        }
        return self::PREFIX . base64_encode($iv . $tag . $cipher);
    }

    /**
     * Descifra un texto cifrado con encrypt().
     *
     * @param string|null $encoded Texto cifrado.
     * @return string|null Texto plano o null si viene vacío.
     */
    function decrypt($encoded)
    {
        if ($encoded === null || $encoded === '') {
            return null;
        }
        if (strpos($encoded, self::PREFIX) !== 0) {
            throw new \Exception('Formato de credencial cifrada desconocido.');
        }
        $raw = base64_decode(substr($encoded, strlen(self::PREFIX)), true);
        if ($raw === false || strlen($raw) < 29) {
            throw new \Exception('Credencial cifrada dañada.');
        }
        $plain = openssl_decrypt(substr($raw, 28), 'aes-256-gcm', $this->key(), OPENSSL_RAW_DATA, substr($raw, 0, 12), substr($raw, 12, 16));
        if ($plain === false) {
            throw new \Exception('No se pudo descifrar la credencial (¿cambió la llave deploy.key?).');
        }
        return $plain;
    }

    /**
     * Genera un secreto aleatorio para el agente (64 caracteres hexadecimales).
     *
     * @return string
     */
    static function randomSecret()
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Genera un nonce aleatorio.
     *
     * @return string
     */
    static function nonce()
    {
        return bin2hex(random_bytes(16));
    }

    /**
     * Calcula la firma HMAC de una petición al agente.
     *
     * @param string $secret    Secreto compartido.
     * @param string $action    Acción del agente (p. ej. "ping").
     * @param string $timestamp Unix timestamp en texto.
     * @param string $nonce     Valor de un solo uso.
     * @param string $body      Cuerpo crudo de la petición.
     * @return string Firma hexadecimal.
     */
    static function sign($secret, $action, $timestamp, $nonce, $body)
    {
        $payload = "POST\n" . $action . "\n" . $timestamp . "\n" . $nonce . "\n" . hash('sha256', (string) $body);
        return hash_hmac('sha256', $payload, (string) $secret);
    }

    /**
     * Verifica la firma en tiempo constante.
     *
     * @return bool
     */
    static function verify($secret, $action, $timestamp, $nonce, $body, $signature)
    {
        if (!is_string($signature) || $signature === '') {
            return false;
        }
        return hash_equals(self::sign($secret, $action, $timestamp, $nonce, $body), strtolower($signature));
    }

    /**
     * Oculta secretos conocidos dentro de un texto (para logs y mensajes).
     *
     * @param string $text    Texto.
     * @param array  $secrets Valores a ocultar.
     * @return string
     */
    static function mask($text, array $secrets)
    {
        foreach ($secrets as $secret) {
            if (is_string($secret) && strlen($secret) >= 4) {
                $text = str_replace($secret, '********', $text);
            }
        }
        return $text;
    }
}
