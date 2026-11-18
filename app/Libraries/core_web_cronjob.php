<?php
//posme:2026-11-18
namespace App\Libraries;

/**
 * Cliente de la API REST de cron-job.org (https://docs.cron-job.org/rest-api.html).
 * Lo usa app_cxc_advertising para levantar/bajar el job que procesa la cola de WhatsApp masivo.
 * La API key nunca se escribe en el log.
 */
class core_web_cronjob
{
    const API_URL = "https://api.cron-job.org/";

    /**
     * Crea un job que se ejecuta cada minuto (todas las horas, dias y meses).
     * @param string $apiKey   API key de cron-job.org
     * @param string $url      URL publica que invocara el job
     * @param string $timezone Zona horaria del horario
     * @return int jobId creado
     */
    function createJob($apiKey, $url, $timezone = "America/Managua")
    {
        $body = [
            "job" => [
                "url"            => $url,
                "enabled"        => true,
                "saveResponses"  => false,
                "requestMethod"  => 0,
                "requestTimeout" => -1,
                "schedule"       => [
                    "timezone"  => $timezone,
                    "expiresAt" => 0,
                    "hours"     => [-1],
                    "mdays"     => [-1],
                    "minutes"   => [-1],
                    "months"    => [-1],
                    "wdays"     => [-1]
                ]
            ]
        ];

        $result = $this->request("PUT", "jobs", $apiKey, $body);
        if ($result["httpCode"] != 200) {
            throw new \Exception($this->errorMessage($result["httpCode"]));
        }
        if (!isset($result["data"]["jobId"]) || (int) $result["data"]["jobId"] < 1) {
            throw new \Exception("cron-job.org no devolvió el identificador del job creado.");
        }
        return (int) $result["data"]["jobId"];
    }

    /**
     * Elimina un job. Si ya no existe (404) se considera eliminado.
     * @param string $apiKey
     * @param int    $jobId
     * @return bool
     */
    function deleteJob($apiKey, $jobId)
    {
        $result = $this->request("DELETE", "jobs/" . (int) $jobId, $apiKey);
        if ($result["httpCode"] == 200 || $result["httpCode"] == 404) {
            return true;
        }
        throw new \Exception($this->errorMessage($result["httpCode"]));
    }

    /**
     * Detalle de un job.
     * @param string $apiKey
     * @param int    $jobId
     * @return array|null jobDetails o null si no existe
     */
    function getJob($apiKey, $jobId)
    {
        $result = $this->request("GET", "jobs/" . (int) $jobId, $apiKey);
        if ($result["httpCode"] == 404) {
            return null;
        }
        if ($result["httpCode"] != 200) {
            throw new \Exception($this->errorMessage($result["httpCode"]));
        }
        return isset($result["data"]["jobDetails"]) && is_array($result["data"]["jobDetails"]) ? $result["data"]["jobDetails"] : null;
    }

    /**
     * Ejecuta una peticion contra la API.
     * @param string     $method GET | PUT | PATCH | DELETE
     * @param string     $path   Ruta relativa (ej. jobs/123)
     * @param string     $apiKey
     * @param array|null $body   Cuerpo JSON
     * @return array {httpCode, data}
     */
    private function request($method, $path, $apiKey, $body = null)
    {
        $apiKey = trim((string) $apiKey);
        if ($apiKey == "") {
            throw new \Exception("La API key de cron-job.org está vacía (CXC_ADVERTISING_CRONJOB_API_KEY).");
        }

        $options = [
            CURLOPT_URL            => self::API_URL . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 0,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => [
                "Authorization: Bearer " . $apiKey,
                "Content-Type: application/json"
            ]
        ];
        if ($body !== null) {
            $options[CURLOPT_POSTFIELDS] = json_encode($body);
        }

        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        $err      = curl_error($curl);
        $httpCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($err) {
            throw new \Exception("Error de conexión con cron-job.org: " . $err);
        }

        $data = json_decode((string) $response, true);
        return [
            "httpCode" => $httpCode,
            "data"     => is_array($data) ? $data : []
        ];
    }

    /**
     * Mensaje legible para un codigo HTTP de error de cron-job.org.
     * @param int $httpCode
     * @return string
     */
    private function errorMessage($httpCode)
    {
        $httpCode = (int) $httpCode;
        switch ($httpCode) {
            case 400:
                return "cron-job.org: solicitud inválida (400).";
            case 401:
                return "cron-job.org: API key inválida (401). Revise el parámetro CXC_ADVERTISING_CRONJOB_API_KEY.";
            case 403:
                return "cron-job.org: sin permiso o IP no permitida para la API key (403).";
            case 404:
                return "cron-job.org: el job no existe (404).";
            case 409:
                return "cron-job.org: conflicto con el estado actual del job (409).";
            case 429:
                return "Cuota de cron-job.org agotada (100 peticiones/día; crear máx. 1/s y 5/min). Intente más tarde.";
        }
        if ($httpCode >= 500) {
            return "cron-job.org: servicio no disponible (" . $httpCode . "). Intente más tarde.";
        }
        return "cron-job.org respondió con un código inesperado (" . $httpCode . ").";
    }
}
