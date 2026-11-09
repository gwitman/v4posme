<?php
//posme:2026-11-09
namespace App\Libraries\core_web_deploy;

/**
 * Transporte de archivos al servidor del cliente por SFTP, FTPS o FTP.
 *
 * Se usa para instalar el agente la primera vez y como respaldo para subir
 * el paquete. Prioridad recomendada: SFTP (extensión ssh2) > FTPS explícito > FTP.
 * Las credenciales nunca se escriben en mensajes ni en logs.
 */
class core_deploy_transport
{
    private $protocol;
    private $host;
    private $port;
    private $user;
    private $password;
    private $timeout;
    private $connection;
    private $sftp;

    /**
     * @param string $protocol sftp | ftps | ftp
     * @param string $host     Servidor.
     * @param int    $port     Puerto (0 = el predeterminado del protocolo).
     * @param string $user     Usuario.
     * @param string $password Contraseña.
     * @param int    $timeout  Segundos.
     */
    function __construct($protocol, $host, $port, $user, $password, $timeout = 20)
    {
        $this->protocol = strtolower((string) $protocol);
        $this->host     = trim((string) $host);
        $this->port     = (int) $port > 0 ? (int) $port : ($this->protocol === 'sftp' ? 22 : 21);
        $this->user     = (string) $user;
        $this->password = (string) $password;
        $this->timeout  = (int) $timeout;

        if (!in_array($this->protocol, ['sftp', 'ftps', 'ftp'], true)) {
            throw new \Exception('Protocolo de transferencia no soportado: ' . $this->protocol);
        }
        if ($this->host === '' || preg_match('/[\s\/\\\\]/', $this->host)) {
            throw new \Exception('Servidor de transferencia inválido.');
        }
    }

    /**
     * Abre la conexión y autentica.
     *
     * @return void
     */
    function connect()
    {
        if ($this->protocol === 'sftp') {
            if (!function_exists('ssh2_connect')) {
                throw new \Exception('La extensión ssh2 de PHP no está instalada; use FTPS o instale ssh2.');
            }
            $connection = @ssh2_connect($this->host, $this->port);
            if (!$connection) {
                throw new \Exception('No se pudo conectar por SFTP a ' . $this->host . ':' . $this->port . '.');
            }
            if (!@ssh2_auth_password($connection, $this->user, $this->password)) {
                throw new \Exception('SFTP: usuario o contraseña incorrectos.');
            }
            $sftp = @ssh2_sftp($connection);
            if (!$sftp) {
                throw new \Exception('SFTP: no se pudo iniciar el subsistema SFTP.');
            }
            $this->connection = $connection;
            $this->sftp       = $sftp;
            return;
        }

        if ($this->protocol === 'ftps') {
            if (!function_exists('ftp_ssl_connect')) {
                throw new \Exception('PHP no tiene soporte FTPS (ftp_ssl_connect).');
            }
            $connection = @ftp_ssl_connect($this->host, $this->port, $this->timeout);
        } else {
            if (!function_exists('ftp_connect')) {
                throw new \Exception('La extensión ftp de PHP no está instalada.');
            }
            $connection = @ftp_connect($this->host, $this->port, $this->timeout);
        }
        if (!$connection) {
            throw new \Exception('No se pudo conectar por ' . strtoupper($this->protocol) . ' a ' . $this->host . ':' . $this->port . '.');
        }
        if (!@ftp_login($connection, $this->user, $this->password)) {
            @ftp_close($connection);
            throw new \Exception(strtoupper($this->protocol) . ': usuario o contraseña incorrectos.');
        }
        @ftp_pasv($connection, true);
        $this->connection = $connection;
    }

    /**
     * Ruta remota normalizada (sin "..").
     *
     * @return string
     */
    static function joinPath()
    {
        $parts = [];
        foreach (func_get_args() as $piece) {
            foreach (explode('/', str_replace('\\', '/', (string) $piece)) as $segment) {
                if ($segment === '' || $segment === '.') {
                    continue;
                }
                if ($segment === '..') {
                    throw new \Exception('Ruta remota inválida.');
                }
                $parts[] = $segment;
            }
        }
        return '/' . implode('/', $parts);
    }

    /** URL del stream SFTP. */
    private function sftpUrl($path)
    {
        return 'ssh2.sftp://' . intval($this->sftp) . $path;
    }

    /**
     * Indica si existe un directorio remoto.
     *
     * @return bool
     */
    function isDir($path)
    {
        if ($this->protocol === 'sftp') {
            return @is_dir($this->sftpUrl($path));
        }
        $current = @ftp_pwd($this->connection);
        $ok      = @ftp_chdir($this->connection, $path);
        if ($ok && $current !== false) {
            @ftp_chdir($this->connection, $current);
        }
        return (bool) $ok;
    }

    /**
     * Crea un directorio remoto (recursivo).
     *
     * @return void
     */
    function mkdirRecursive($path)
    {
        $current = '';
        foreach (explode('/', trim($path, '/')) as $segment) {
            $current .= '/' . $segment;
            if ($this->isDir($current)) {
                continue;
            }
            $ok = $this->protocol === 'sftp'
                ? @ssh2_sftp_mkdir($this->sftp, $current, 0755)
                : @ftp_mkdir($this->connection, $current);
            if (!$ok && !$this->isDir($current)) {
                throw new \Exception('No se pudo crear la carpeta remota: ' . $current);
            }
        }
    }

    /**
     * Sube un contenido a un archivo remoto.
     *
     * @param string $content    Contenido.
     * @param string $remotePath Ruta absoluta remota.
     * @return void
     */
    function putContents($content, $remotePath)
    {
        $this->mkdirRecursive(dirname($remotePath));
        if ($this->protocol === 'sftp') {
            if (@file_put_contents($this->sftpUrl($remotePath), $content) === false) {
                throw new \Exception('No se pudo escribir el archivo remoto: ' . basename($remotePath));
            }
            return;
        }
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, $content);
        rewind($stream);
        $ok = @ftp_fput($this->connection, $remotePath, $stream, FTP_BINARY);
        fclose($stream);
        if (!$ok) {
            throw new \Exception('No se pudo subir el archivo remoto: ' . basename($remotePath));
        }
    }

    /**
     * Sube un archivo local.
     *
     * @return void
     */
    function putFile($localPath, $remotePath)
    {
        $this->mkdirRecursive(dirname($remotePath));
        if ($this->protocol === 'sftp') {
            $in  = fopen($localPath, 'rb');
            $out = @fopen($this->sftpUrl($remotePath), 'wb');
            if (!$in || !$out) {
                throw new \Exception('No se pudo abrir el archivo remoto: ' . basename($remotePath));
            }
            stream_copy_to_stream($in, $out);
            fclose($in);
            fclose($out);
            return;
        }
        if (!@ftp_put($this->connection, $remotePath, $localPath, FTP_BINARY)) {
            throw new \Exception('No se pudo subir: ' . basename($remotePath));
        }
    }

    /**
     * Elimina un archivo remoto (sin error si no existe).
     *
     * @return void
     */
    function delete($remotePath)
    {
        if ($this->protocol === 'sftp') {
            @ssh2_sftp_unlink($this->sftp, $remotePath);
            return;
        }
        @ftp_delete($this->connection, $remotePath);
    }

    /**
     * Prueba la conexión: directorio de la aplicación y permiso de escritura.
     *
     * @param string $appDir Directorio remoto de la aplicación.
     * @return array ['ok' => bool, 'checks' => [...]]
     */
    function test($appDir)
    {
        $checks = [];
        $this->connect();
        $checks[] = ['label' => 'Conexión ' . strtoupper($this->protocol), 'ok' => true];

        $exists   = $this->isDir($appDir);
        $checks[] = ['label' => 'Directorio de la aplicación existe', 'ok' => $exists];
        $hasApp   = $exists && $this->isDir(rtrim($appDir, '/') . '/app') && $this->isDir(rtrim($appDir, '/') . '/writable');
        $checks[] = ['label' => 'Contiene app/ y writable/ (instalación de posMe)', 'ok' => $hasApp];

        $canWrite = false;
        if ($hasApp) {
            $probe = rtrim($appDir, '/') . '/writable/posme_deploy_probe_' . bin2hex(random_bytes(3)) . '.txt';
            try {
                $this->putContents('ok', $probe);
                $this->delete($probe);
                $canWrite = true;
            } catch (\Throwable $ex) {
                $canWrite = false;
            }
        }
        $checks[] = ['label' => 'Permiso de escritura en writable/', 'ok' => $canWrite];
        $this->close();

        return ['ok' => $exists && $hasApp && $canWrite, 'checks' => $checks];
    }

    /**
     * Cierra la conexión.
     *
     * @return void
     */
    function close()
    {
        if ($this->protocol !== 'sftp' && $this->connection) {
            @ftp_close($this->connection);
        }
        if ($this->protocol === 'sftp' && $this->connection && function_exists('ssh2_disconnect')) {
            @ssh2_disconnect($this->connection);
        }
        $this->connection = null;
        $this->sftp       = null;
    }
}
