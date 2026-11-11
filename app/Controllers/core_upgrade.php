<?php
//posme:2026-11-10
namespace App\Controllers;

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
            $mode = $this->upgrade_post('mode');
            $data = $this->core_web_upgrade->check((string) $this->request->getServer('HTTP_HOST'), $mode !== '' ? $mode : null);
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
