<?php
//posme:2026-11-02
namespace App\Controllers;

use App\Libraries\core_web_ai;

/**
 * Endpoints del asistente IA "Jordania".
 *
 * Condicion para usarlo: empresa con AI_ASSISTANT_ENABLED = true
 * + usuario con acceso al menu_element "Asistente IA" (address app_ai_chat/index).
 * companyID y userID se toman SIEMPRE de la sesion, nunca del navegador.
 *
 * Respuesta: { ok: bool, error: codigo|"" , message: texto, ... }
 */
class app_ai_chat extends _BaseController
{
    /**
     * Pantalla base del permiso "Asistente IA". El chat vive en el widget de todas las pantallas,
     * por eso solo informa como usarlo.
     */
    function index()
    {
        try {
            $acceso = $this->validarAcceso(false);
            if ($acceso["respuesta"] !== null) {
                return $acceso["respuesta"];
            }
            return $this->response->setJSON([
                "ok"      => true,
                "error"   => "",
                "message" => "Jordania está disponible en el botón flotante de la parte inferior derecha.",
            ]);
        } catch (\Throwable $ex) {
            return $this->errorInterno($ex);
        }
    }

    /**
     * Recibe una pregunta y devuelve la respuesta de Jordania.
     * POST: pregunta
     */
    function send()
    {
        try {
            $acceso = $this->validarAcceso(true);
            if ($acceso["respuesta"] !== null) {
                return $acceso["respuesta"];
            }
            $dataSession = $acceso["dataSession"];

            $pregunta = $this->getValor("pregunta");

            // Se libera la sesion: la llamada a la API puede tardar y no debe bloquear otras pantallas del usuario
            session_write_close();

            $resultado = $this->core_web_ai->ask($dataSession, $pregunta);
            return $this->response->setJSON($resultado);
        } catch (\Throwable $ex) {
            return $this->errorInterno($ex);
        }
    }

    /**
     * Mensajes de la conversacion activa del usuario.
     */
    function history()
    {
        try {
            $acceso = $this->validarAcceso(true);
            if ($acceso["respuesta"] !== null) {
                return $acceso["respuesta"];
            }
            $dataSession = $acceso["dataSession"];
            session_write_close();

            $companyID    = (int) $dataSession["user"]->companyID;
            $userID       = (int) $dataSession["user"]->userID;
            $mensajes     = [];
            $conversacion = $this->Ai_Chat_Model->get_rowConversacionActiva($companyID, $userID);

            if ($conversacion) {
                foreach ($this->Ai_Chat_Model->get_rowMensajes($companyID, $userID, $conversacion->conversacionID, 50) as $m) {
                    $datos      = json_decode((string) $m->contenidoJson, true);
                    $mensajes[] = [
                        "rol"   => $m->rol,
                        "texto" => is_array($datos) ? (string) ($datos["texto"] ?? "") : "",
                        "fecha" => $m->createdOn,
                    ];
                }
            }

            return $this->response->setJSON(["ok" => true, "error" => "", "message" => "", "mensajes" => $mensajes]);
        } catch (\Throwable $ex) {
            return $this->errorInterno($ex);
        }
    }

    /**
     * Inicia una conversacion nueva (cierra las anteriores con soft delete).
     */
    function reset()
    {
        try {
            $acceso = $this->validarAcceso(true);
            if ($acceso["respuesta"] !== null) {
                return $acceso["respuesta"];
            }
            $dataSession = $acceso["dataSession"];
            session_write_close();

            $this->Ai_Chat_Model->delete_conversacionesByUser((int) $dataSession["user"]->companyID, (int) $dataSession["user"]->userID);
            return $this->response->setJSON(["ok" => true, "error" => "", "message" => ""]);
        } catch (\Throwable $ex) {
            return $this->errorInterno($ex);
        }
    }

    /**
     * Consumo de tokens del periodo (solo administradores).
     * GET/POST opcional: periodo (YYYY-MM)
     */
    function usage()
    {
        try {
            $acceso = $this->validarAcceso(false);
            if ($acceso["respuesta"] !== null) {
                return $acceso["respuesta"];
            }
            $dataSession = $acceso["dataSession"];
            session_write_close();

            if (empty($dataSession["role"]->isAdmin)) {
                return $this->response->setStatusCode(403)->setJSON(["ok" => false, "error" => "sin_permiso", "message" => "Solo un administrador puede ver el consumo del asistente."]);
            }

            $companyID = (int) $dataSession["user"]->companyID;
            $periodo   = $this->getValor("periodo");
            if (!preg_match('/^\d{4}-\d{2}$/', $periodo)) {
                $periodo = substr(helper_getDateTime(), 0, 7);
            }

            return $this->response->setJSON([
                "ok"                => true,
                "error"             => "",
                "message"           => "",
                "periodo"           => $periodo,
                "habilitado"        => $this->core_web_ai->estaHabilitado($companyID),
                "modelo"            => $this->core_web_ai->getParametro("AI_MODEL", $companyID, core_web_ai::MODELO_DEFECTO),
                "cuotaEmpresa"      => (int) $this->core_web_ai->getParametro("AI_QUOTA_COMPANY_MONTH", $companyID, "2000000"),
                "cuotaUsuario"      => (int) $this->core_web_ai->getParametro("AI_QUOTA_USER_MONTH", $companyID, "0"),
                "tokensEmpresa"     => $this->Ai_Chat_Model->get_tokensPeriodo($companyID, $periodo),
                "usuarios"          => $this->Ai_Chat_Model->get_rowUsoPorUsuario($companyID, $periodo),
            ]);
        } catch (\Throwable $ex) {
            return $this->errorInterno($ex);
        }
    }

    /**
     * Estado ligero para el widget (se consulta en segundo plano):
     * permiso + empresa habilitada + servidor con salida a internet (cacheado unos minutos).
     */
    function status()
    {
        try {
            $acceso = $this->validarAcceso(true);
            if ($acceso["respuesta"] !== null) {
                return $acceso["respuesta"];
            }
            session_write_close();

            $configurado = $this->core_web_ai->getApiKey() !== "";
            $internet    = $configurado ? $this->core_web_ai->hayInternet() : false;

            return $this->response->setJSON([
                "ok"          => true,
                "error"       => $configurado ? ($internet ? "" : "sin_conexion") : "sin_configuracion",
                "message"     => $configurado ? ($internet ? "" : core_web_ai::$MENSAJES["sin_conexion"]) : core_web_ai::$MENSAJES["sin_configuracion"],
                "disponible"  => $configurado && $internet,
            ]);
        } catch (\Throwable $ex) {
            return $this->errorInterno($ex);
        }
    }

    /* =================================================================================
       UTILIDADES
    ================================================================================= */

    /**
     * Autenticacion + permiso "Asistente IA" + (opcional) empresa habilitada.
     * Si falla devuelve la respuesta HTTP lista (401/403) y no se llama a la API ni se descuenta cuota.
     *
     * @param bool $exigirHabilitado Exige AI_ASSISTANT_ENABLED = true.
     * @return array ['dataSession' => array|null, 'respuesta' => Response|null]
     */
    private function validarAcceso($exigirHabilitado)
    {
        if (!$this->core_web_authentication->isAuthenticated()) {
            return [
                "dataSession" => null,
                "respuesta"   => $this->response->setStatusCode(401)->setJSON(["ok" => false, "error" => "sesion_expirada", "message" => "Tu sesión expiró. Vuelve a iniciar sesión en posMe."]),
            ];
        }

        $dataSession = $this->session->get();
        if (empty($dataSession["user"]) || !core_web_ai::tieneAccesoAsistente($dataSession)) {
            return [
                "dataSession" => null,
                "respuesta"   => $this->response->setStatusCode(403)->setJSON(core_web_ai::error("sin_permiso")),
            ];
        }

        if ($exigirHabilitado && !$this->core_web_ai->estaHabilitado((int) $dataSession["user"]->companyID)) {
            return [
                "dataSession" => null,
                "respuesta"   => $this->response->setStatusCode(403)->setJSON(core_web_ai::error("deshabilitado")),
            ];
        }

        return ["dataSession" => $dataSession, "respuesta" => null];
    }

    /** Valor de POST, GET o cuerpo JSON. */
    private function getValor($nombre)
    {
        $valor = $this->request->getPost($nombre);
        if ($valor === null) {
            $valor = $this->request->getGet($nombre);
        }
        if ($valor === null) {
            try {
                $json  = $this->request->getJSON(true);
                $valor = is_array($json) ? ($json[$nombre] ?? null) : null;
            } catch (\Throwable $ex) {
                $valor = null;
            }
        }
        return trim((string) helper_RequestGetValue($valor, ""));
    }

    /** Error no controlado: se registra en log y se responde JSON amigable (nunca pagina de error). */
    private function errorInterno($ex)
    {
        log_message("error", "app_ai_chat: " . $ex->getMessage() . " linea " . $ex->getLine());
        return $this->response->setJSON(core_web_ai::error("error_interno"));
    }
}
