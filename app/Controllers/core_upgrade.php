<?php
//posme:2026-11-10
namespace App\Controllers;

use App\Libraries\core_web_upgrade\core_upgrade_dbbackup;
use App\Libraries\core_web_upgrade\core_upgrade_rules;

/**
 * Actualización inversa de posMe (core_upgrade/byPop3).
 *
 * La instalación del cliente descarga del servidor central (core_upgrade_server)
 * los paquetes de su modo (local: app, vendor, public · nube: vendor, app/Config),
 * la estructura de la BD y los datos de configuración, y los aplica por etapas con
 * respaldo completo y reversión. La orquestación vive en core_web_upgrade.
 *
 * Seguridad: sesión de administrador (role->isAdmin = 1), solo POST en los
 * endpoints JSON y token propio en el encabezado X-Posme-Upgrade-Token
 * (comparado con hash_equals). upgrade_health no exige sesión: lo usa el
 * health check interno después de copiar archivos.
 */
class core_upgrade extends _BaseController
{
    const TOKEN_KEY = 'posme_upgrade_token';

    private $upgradeToken = '';

    /**
     * Pantalla de la actualización.
     *
     * @return mixed
     */
    function byPop3()
    {
        try {
            $dataSession = $this->upgrade_session();
            $this->upgrade_issueToken(false);
            session_write_close();

            $config = $this->core_web_upgrade->configRead();

            $dataView["upgradeToken"]     = $this->upgradeToken;
            $dataView["defaultSourceUrl"] = $config['sourceUrl'] !== '' ? $config['sourceUrl'] : core_upgrade_rules::DEFAULT_SOURCE_URL;
            $dataView["modeDetected"]     = core_upgrade_rules::detectMode((string) $this->request->getServer('HTTP_HOST'));
            $dataView["userName"]         = $dataSession["user"]->nickname;

            $dataSession["notification"] = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]      = $this->core_web_notification->get_message();
            $dataSession["head"]         = /*--inicio view*/ view('core_upgrade/bypop3_head', $dataView);//--finview
            $dataSession["body"]         = /*--inicio view*/ view('core_upgrade/bypop3_body', $dataView);//--finview
            $dataSession["script"]       = /*--inicio view*/ view('core_upgrade/bypop3_script', $dataView);//--finview
            $dataSession["footer"]       = "";
            return view("core_masterpage/default_masterpage", $dataSession);//--finview-r
        } catch (\Throwable $ex) {
            // Sesión expirada: ir directo al login
            if ($ex->getMessage() === USER_NOT_AUTENTICATED) {
                return redirect()->to(base_url("core_acount/login"));
            }
            return "<div style='font-family:sans-serif; padding:14px; margin:20px; background:#fdecec; color:#c62828; border-radius:8px;'>" . htmlspecialchars($this->core_web_upgrade->mask($ex->getMessage())) . "</div>";
        }
    }

    /**
     * Renueva (o reutiliza) el token de la pantalla.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    function upgrade_token()
    {
        try {
            if (strtoupper($this->request->getMethod()) !== 'POST') {
                throw new \Exception('Método no permitido.');
            }
            $this->upgrade_session();
            $this->upgrade_issueToken(false);
            session_write_close();
            return $this->upgrade_json([]);
        } catch (\Throwable $ex) {
            return $this->upgrade_jsonError($ex);
        }
    }

    /**
     * Lee o guarda la conexión con el servidor central (action = read | save).
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    function upgrade_config()
    {
        try {
            $this->upgrade_guard();
            $action = $this->upgrade_post('action', 'read');
            if ($action === 'save') {
                // Clave vacía: se conserva la guardada
                $data = $this->core_web_upgrade->configSave(
                    $this->upgrade_post('sourceUrl'),
                    $this->upgrade_post('installationID'),
                    $this->upgrade_post('secret')
                );
                return $this->upgrade_json($data, 'Conexión guardada.');
            }
            return $this->upgrade_json($this->core_web_upgrade->configRead());
        } catch (\Throwable $ex) {
            return $this->upgrade_jsonError($ex);
        }
    }

    /**
     * Consulta previa (versiones, componentes, comprobaciones, canRun).
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    function upgrade_check()
    {
        try {
            $this->upgrade_guard();
            $mode  = $this->upgrade_post('mode');
            // Casillas de qué sincronizar (los scripts de parámetros se ejecutan siempre)
            $parts = [
                'syncFiles'  => $this->upgrade_post('syncFiles', '1'),
                'syncSchema' => $this->upgrade_post('syncSchema', '1'),
                'syncData'   => $this->upgrade_post('syncData', '1'),
            ];
            $data  = $this->core_web_upgrade->check((string) $this->request->getServer('HTTP_HOST'), $mode !== '' ? $mode : null, $parts);
            return $this->upgrade_json($data);
        } catch (\Throwable $ex) {
            return $this->upgrade_jsonError($ex);
        }
    }

    /**
     * Ejecuta una etapa (o un bloque de una etapa) de la actualización.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    function upgrade_step()
    {
        try {
            $dataSession = $this->upgrade_guard();
            $params      = [
                'upgradeID' => $this->upgrade_post('upgradeID'),
                'action'    => $this->upgrade_post('action'),
                'index'     => $this->upgrade_post('index', '0'),
                'ids'       => $this->upgrade_post('ids', '[]'),
                'mode'      => $this->upgrade_post('mode'),
                'force'     => $this->upgrade_post('force', '0'),
                'syncFiles'  => $this->upgrade_post('syncFiles', '1'),
                'syncSchema' => $this->upgrade_post('syncSchema', '1'),
                'syncData'   => $this->upgrade_post('syncData', '1'),
                'confirm'   => $this->upgrade_post('confirm'),
                'status'    => $this->upgrade_post('status'),
                'includeDb' => $this->upgrade_post('includeDb', '0'),
            ];
            $data = $this->core_web_upgrade->step($params, $dataSession["user"]->nickname, (string) $this->request->getServer('HTTP_HOST'));
            return $this->upgrade_json($data);
        } catch (\Throwable $ex) {
            return $this->upgrade_jsonError($ex, true);
        }
    }

    /**
     * Estado de la actualización en curso (para retomar la pantalla).
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    function upgrade_status()
    {
        try {
            $this->upgrade_guard();
            return $this->upgrade_json($this->core_web_upgrade->status());
        } catch (\Throwable $ex) {
            return $this->upgrade_jsonError($ex);
        }
    }

    /**
     * Bitácora de una corrida (la última si no se indica upgradeID).
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    function upgrade_log()
    {
        try {
            $this->upgrade_guard();
            return $this->upgrade_json($this->core_web_upgrade->logLines($this->upgrade_post('upgradeID')));
        } catch (\Throwable $ex) {
            return $this->upgrade_jsonError($ex);
        }
    }

    /**
     * Health check sin sesión: la aplicación carga y la BD responde.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    function upgrade_health()
    {
        try {
            return $this->response->setJSON([
                'error'   => false,
                'message' => SUCCESS,
                'data'    => $this->core_web_upgrade->health(),
            ]);
        } catch (\Throwable $ex) {
            return $this->response->setStatusCode(500)->setJSON([
                'error'   => true,
                'message' => 'La instalación no está disponible.',
                'data'    => ['ok' => false],
            ]);
        }
    }

    // ------------------------------------------------------------ respaldo de base de datos

    /**
     * Respaldos guardados, espacio libre y restauración pendiente.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    function upgrade_backup_list()
    {
        try {
            $this->upgrade_guard();
            return $this->upgrade_json(core_upgrade_dbbackup::listAll());
        } catch (\Throwable $ex) {
            return $this->upgrade_jsonError($ex);
        }
    }

    /**
     * Genera un respaldo manual por bloques (action = begin | step | verify).
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    function upgrade_backup_create()
    {
        try {
            $dataSession = $this->upgrade_guard();
            @ignore_user_abort(true);
            $action   = $this->upgrade_post('action');
            $backupID = $this->upgrade_post('backupID');
            $db       = db_connect();
            if ($action === 'begin') {
                return $this->upgrade_json(core_upgrade_dbbackup::createBegin($db, $dataSession["user"]->nickname, $this->upgrade_post('note')));
            }
            if ($action === 'step') {
                return $this->upgrade_json(core_upgrade_dbbackup::createStep($db, $backupID, microtime(true) + core_upgrade_rules::STEP_BUDGET));
            }
            if ($action === 'verify') {
                $result = core_upgrade_dbbackup::createVerify($db, $backupID);
                if (!$result['ok']) {
                    throw new \Exception('El respaldo no pasó la verificación: ' . $result['note']);
                }
                return $this->upgrade_json($result, 'Respaldo generado y verificado.');
            }
            throw new \Exception('Acción de respaldo inválida.');
        } catch (\Throwable $ex) {
            return $this->upgrade_jsonError($ex);
        }
    }

    /**
     * Elimina un respaldo manual o subido.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    function upgrade_backup_delete()
    {
        try {
            $this->upgrade_guard();
            core_upgrade_dbbackup::delete($this->upgrade_post('backupID'));
            return $this->upgrade_json([], 'Respaldo eliminado.');
        } catch (\Throwable $ex) {
            return $this->upgrade_jsonError($ex);
        }
    }

    /**
     * Prepara la descarga: arma el .zip y devuelve una llave de un solo uso
     * para upgrade_backup_file.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    function upgrade_backup_download()
    {
        try {
            $this->upgrade_guard();
            $zip = core_upgrade_dbbackup::zipFile($this->upgrade_post('origin'), $this->upgrade_post('backupID'));
            $key = bin2hex(random_bytes(16));
            \App\Libraries\core_web_deploy\core_deploy_files::writeJson(
                core_upgrade_dbbackup::uploadsDir() . '/download_' . $key . '.json',
                ['path' => $zip['path'], 'name' => $zip['name'], 'expires' => time() + 600]
            );
            return $this->upgrade_json(['key' => $key, 'name' => $zip['name'], 'bytes' => (int) filesize($zip['path'])]);
        } catch (\Throwable $ex) {
            return $this->upgrade_jsonError($ex);
        }
    }

    /**
     * Entrega el .zip preparado (GET con sesión de administrador y llave de un solo uso).
     *
     * @return mixed
     */
    function upgrade_backup_file()
    {
        try {
            $this->upgrade_session();
            session_write_close();
            $key = (string) $this->request->getGet('k');
            if (preg_match('/^[a-f0-9]{32}$/', $key) !== 1) {
                throw new \Exception('Enlace de descarga inválido.');
            }
            $ticketFile = core_upgrade_dbbackup::uploadsDir() . '/download_' . $key . '.json';
            $ticket     = \App\Libraries\core_web_deploy\core_deploy_files::readJson($ticketFile);
            @unlink($ticketFile);
            if (!$ticket || (int) $ticket['expires'] < time() || !is_file($ticket['path'])) {
                throw new \Exception('El enlace de descarga venció; vuelva a pulsar Descargar.');
            }
            @set_time_limit(core_upgrade_rules::TIME_LIMIT);
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . str_replace('"', '', $ticket['name']) . '"');
            header('Content-Length: ' . filesize($ticket['path']));
            header('Cache-Control: no-store');
            header('X-Content-Type-Options: nosniff');
            $handle = fopen($ticket['path'], 'rb');
            while ($handle && !feof($handle)) {
                echo fread($handle, 1048576);
                flush();
            }
            if ($handle) {
                fclose($handle);
            }
            @unlink($ticket['path']);
            exit;
        } catch (\Throwable $ex) {
            if ($ex->getMessage() === USER_NOT_AUTENTICATED) {
                return redirect()->to(base_url("core_acount/login"));
            }
            return "<div style='font-family:sans-serif; padding:14px; margin:20px; background:#fdecec; color:#c62828; border-radius:8px;'>" . htmlspecialchars($this->core_web_upgrade->mask($ex->getMessage())) . "</div>";
        }
    }

    /**
     * Subida de un respaldo .zip por bloques (action = begin | chunk | finish | cancel).
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    function upgrade_backup_upload()
    {
        try {
            $dataSession = $this->upgrade_guard();
            $action      = $this->upgrade_post('action');
            $uploadID    = $this->upgrade_post('uploadID');
            if ($action === 'begin') {
                return $this->upgrade_json(core_upgrade_dbbackup::uploadBegin($this->upgrade_post('size', '0')));
            }
            if ($action === 'chunk') {
                $file = $this->request->getFile('chunk');
                if (!$file || !$file->isValid()) {
                    throw new \Exception('No llegó el bloque del archivo' . ($file ? ': ' . $file->getErrorString() : '.'));
                }
                return $this->upgrade_json(core_upgrade_dbbackup::uploadChunk($uploadID, $this->upgrade_post('offset', '0'), $file->getTempName()));
            }
            if ($action === 'finish') {
                $data = core_upgrade_dbbackup::uploadFinish(db_connect(), $uploadID, $this->upgrade_post('size', '0'), $this->upgrade_post('fileName'), $dataSession["user"]->nickname);
                return $this->upgrade_json($data, 'Respaldo subido y verificado.');
            }
            if ($action === 'cancel') {
                core_upgrade_dbbackup::uploadCancel($uploadID);
                return $this->upgrade_json([]);
            }
            throw new \Exception('Acción de subida inválida.');
        } catch (\Throwable $ex) {
            return $this->upgrade_jsonError($ex);
        }
    }

    /**
     * Restaura la BD desde un respaldo guardado (action = begin | step | cancel).
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    function upgrade_backup_restore()
    {
        try {
            $dataSession = $this->upgrade_guard();
            @ignore_user_abort(true);
            $action    = $this->upgrade_post('action');
            $restoreID = $this->upgrade_post('restoreID');
            if ($action === 'begin') {
                $data = core_upgrade_dbbackup::restoreBegin($this->upgrade_post('origin'), $this->upgrade_post('backupID'), $this->upgrade_post('confirm'), $dataSession["user"]->nickname);
                return $this->upgrade_json($data);
            }
            if ($action === 'step') {
                return $this->upgrade_json(core_upgrade_dbbackup::restoreStep(db_connect(), $restoreID, microtime(true) + core_upgrade_rules::STEP_BUDGET));
            }
            if ($action === 'cancel') {
                core_upgrade_dbbackup::restoreCancel($restoreID);
                return $this->upgrade_json([], 'Restauración cancelada. Mantenimiento desactivado.');
            }
            throw new \Exception('Acción de restauración inválida.');
        } catch (\Throwable $ex) {
            return $this->upgrade_jsonError($ex);
        }
    }

    // ------------------------------------------------------------ apoyo (no enrutable)

    /**
     * Sesión de administrador.
     *
     * @return array Datos de sesión.
     */
    protected function upgrade_session()
    {
        if (!$this->core_web_authentication->isAuthenticated()) {
            throw new \Exception(USER_NOT_AUTENTICATED);
        }
        $dataSession = $this->session->get();
        if (!isset($dataSession["role"]) || (int) $dataSession["role"]->isAdmin !== 1) {
            throw new \Exception(NOT_ACCESS_CONTROL);
        }
        return $dataSession;
    }

    /**
     * Emite (o reutiliza) el token de la pantalla guardado en sesión.
     *
     * @param bool $renew true = siempre uno nuevo.
     * @return string
     */
    protected function upgrade_issueToken($renew)
    {
        $token = (string) $this->session->get(self::TOKEN_KEY);
        if ($renew || preg_match('/^[a-f0-9]{64}$/', $token) !== 1) {
            $token = bin2hex(random_bytes(32));
            $this->session->set(self::TOKEN_KEY, $token);
        }
        $this->upgradeToken = $token;
        return $token;
    }

    /**
     * POST + sesión de administrador + token válido. Libera la sesión.
     *
     * @return array Datos de sesión.
     */
    protected function upgrade_guard()
    {
        if (strtoupper($this->request->getMethod()) !== 'POST') {
            throw new \Exception('Método no permitido.');
        }
        $dataSession = $this->upgrade_session();
        $expected    = (string) $this->session->get(self::TOKEN_KEY);
        $received    = (string) $this->request->getHeaderLine('X-Posme-Upgrade-Token');
        if ($expected === '' || $received === '' || !hash_equals($expected, $received)) {
            throw new \Exception('El token de seguridad de la actualización es inválido o venció.', 403);
        }
        $this->upgradeToken = $expected;
        @set_time_limit(core_upgrade_rules::TIME_LIMIT);
        // Liberar la sesión para no bloquear otras pestañas ni el health check
        session_write_close();
        return $dataSession;
    }

    /**
     * Valor POST de texto recortado.
     *
     * @param string $name    Campo.
     * @param string $default Valor por defecto.
     * @return string
     */
    protected function upgrade_post($name, $default = '')
    {
        $value = $this->request->getPost($name);
        return is_string($value) ? trim($value) : $default;
    }

    /**
     * Respuesta JSON correcta.
     *
     * @param array  $data    Datos.
     * @param string $message Mensaje.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    protected function upgrade_json($data, $message = SUCCESS)
    {
        return $this->response->setJSON([
            'error'          => false,
            'message'        => $message,
            'data'           => $data,
            'token'          => $this->upgradeToken,
            'sessionExpired' => false,
        ]);
    }

    /**
     * Respuesta JSON de error (mensaje enmascarado; 403 si el token es inválido).
     *
     * @param \Throwable $ex       Excepción.
     * @param bool       $withData true = incluye stage/action/detail/log de la etapa.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    protected function upgrade_jsonError($ex, $withData = false)
    {
        $message = $this->core_web_upgrade->mask($ex->getMessage());
        if ($ex->getCode() === 403) {
            $this->response->setStatusCode(403);
        }
        $data = $withData ? $this->core_web_upgrade->errorData() : null;
        return $this->response->setJSON([
            'error'          => true,
            'message'        => $message,
            'data'           => $data,
            'token'          => $this->upgradeToken,
            'sessionExpired' => ($ex->getMessage() === USER_NOT_AUTENTICATED),
        ]);
    }
}
