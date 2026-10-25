<?php
//posme:2026-10-25
namespace App\Libraries;

/**
 * core_web_google_calendar
 *
 * Crea, actualiza, elimina y consulta eventos de un calendario de Google
 * usando una CUENTA DE SERVICIO (service account) de Google Cloud.
 *
 * Parametros de empresa (tb_parameter / tb_company_parameter):
 *   GOOGLE_CALENDAR_ID                 ID del calendario (ej. xxxx@group.calendar.google.com)
 *   GOOGLE_CALENDAR_SERVICE_EMAIL      client_email de la cuenta de servicio
 *   GOOGLE_CALENDAR_PRIVATE_KEY        private_key de la cuenta de servicio (texto PEM)
 *                                      o la ruta al archivo .json / .pem de la cuenta de servicio
 *   GOOGLE_CALENDAR_TIMEZONE           zona horaria (default America/Managua)
 *   GOOGLE_CALENDAR_DURATION_MINUTES   duracion del evento en minutos (default 60)
 *
 * El calendario debe estar compartido con el correo de la cuenta de servicio
 * con permiso "Hacer cambios en eventos".
 *
 * Uso:
 *   $r = $this->core_web_google_calendar->execute($companyID, "add", "", "Titulo", "Descripcion", "2026-11-05", "14:30", "semanal");
 *   $eventID = $r["eventID"];
 */
class core_web_google_calendar {

	const urlToken    = "https://oauth2.googleapis.com/token";
	const urlCalendar = "https://www.googleapis.com/calendar/v3/calendars/";
	const scope       = "https://www.googleapis.com/auth/calendar";

	private $core_web_parameter;

	public function __construct() {
		$this->core_web_parameter = new core_web_parameter();
	}

	/**
	 * Funcion unica de la libreria.
	 *
	 * @param int    $companyID
	 * @param string $comando       add | update | delete | get
	 * @param string $eventID       ID del evento en Google (vacio en add; en get vacio = listar)
	 * @param string $titulo
	 * @param string $descripcion
	 * @param string $fecha         Y-m-d   (en get = fecha desde la cual listar; vacio = hoy)
	 * @param string $hora          H:i     (vacio = evento de todo el dia)
	 * @param string $periodicidad  unico | diario (siempre) | semanal | mensual
	 * @return array ['error'=>bool, 'message'=>string, 'eventID'=>string, 'data'=>mixed]
	 */
	function execute($companyID, $comando, $eventID = "", $titulo = "", $descripcion = "", $fecha = "", $hora = "", $periodicidad = "unico") {
		$result = array("error" => false, "message" => "SUCCESS", "eventID" => $eventID, "data" => null);

		try {
			$comando = strtolower(trim($comando));
			$eventID = trim((string)$eventID);
			if (!in_array($comando, array("add", "update", "delete", "get")))
				throw new \Exception("COMANDO NO VALIDO: " . $comando . " (use add, update, delete, get)");

			$config  = $this->getConfig($companyID);
			$token   = $this->getAccessToken($companyID, $config);
			$urlBase = self::urlCalendar . rawurlencode($config["calendarID"]) . "/events";

			switch ($comando) {

				case "add":
					$body     = $this->buildEvent($config, $titulo, $descripcion, $fecha, $hora, $periodicidad);
					$response = $this->request("POST", $urlBase, $token, $body);
					$result["eventID"] = $response["id"];
					$result["data"]    = $response;
					break;

				case "update":
					if ($eventID == "")
						throw new \Exception("EL eventID ES REQUERIDO PARA ACTUALIZAR");
					$body     = $this->buildEvent($config, $titulo, $descripcion, $fecha, $hora, $periodicidad);
					$response = $this->request("PUT", $urlBase . "/" . rawurlencode($eventID), $token, $body);
					$result["data"] = $response;
					break;

				case "delete":
					if ($eventID == "")
						throw new \Exception("EL eventID ES REQUERIDO PARA ELIMINAR");
					$this->request("DELETE", $urlBase . "/" . rawurlencode($eventID), $token, null);
					break;

				case "get":
					if ($eventID != "") {
						$result["data"] = $this->request("GET", $urlBase . "/" . rawurlencode($eventID), $token, null);
					} else {
						$result["data"] = $this->listEvents($urlBase, $token, $config, $fecha);
					}
					break;

				default:
					throw new \Exception("COMANDO NO VALIDO: " . $comando . " (use add, update, delete, get)");
			}
		} catch (\Exception $ex) {
			$result["error"]   = true;
			$result["message"] = $ex->getLine() . " " . $ex->getMessage();
		}

		return $result;
	}

	/*************************************************************/
	/* Funciones privadas                                         */
	/*************************************************************/

	private function getConfig($companyID) {
		$config = array();
		$config["calendarID"]   = trim($this->core_web_parameter->getParameter("GOOGLE_CALENDAR_ID", $companyID)->value);
		$config["serviceEmail"] = trim($this->core_web_parameter->getParameter("GOOGLE_CALENDAR_SERVICE_EMAIL", $companyID)->value);
		$config["privateKey"]   = trim($this->core_web_parameter->getParameter("GOOGLE_CALENDAR_PRIVATE_KEY", $companyID)->value);
		$config["timeZone"]     = $this->getParameterOptional("GOOGLE_CALENDAR_TIMEZONE", $companyID, "America/Managua");
		$config["duration"]     = (int)$this->getParameterOptional("GOOGLE_CALENDAR_DURATION_MINUTES", $companyID, "60");

		if ($config["duration"] <= 0)
			$config["duration"] = 60;

		// La llave puede venir como ruta a un archivo .json o .pem de la cuenta de servicio
		if (strpos($config["privateKey"], "-----BEGIN") === false && is_file($config["privateKey"])) {
			$contenido = file_get_contents($config["privateKey"]);
			$json      = json_decode($contenido, true);
			if (is_array($json) && isset($json["private_key"])) {
				$config["privateKey"] = $json["private_key"];
				if ($config["serviceEmail"] == "" && isset($json["client_email"]))
					$config["serviceEmail"] = $json["client_email"];
			} else {
				$config["privateKey"] = $contenido;
			}
		}

		// Si se copio desde el JSON, los saltos de linea vienen como "\n" literal
		$config["privateKey"] = str_replace("\\n", "\n", $config["privateKey"]);

		if ($config["calendarID"] == "")
			throw new \Exception("EL PARAMETRO GOOGLE_CALENDAR_ID ESTA VACIO");
		if ($config["serviceEmail"] == "")
			throw new \Exception("EL PARAMETRO GOOGLE_CALENDAR_SERVICE_EMAIL ESTA VACIO");
		if (strpos($config["privateKey"], "PRIVATE KEY") === false)
			throw new \Exception("EL PARAMETRO GOOGLE_CALENDAR_PRIVATE_KEY NO CONTIENE UNA LLAVE VALIDA");

		return $config;
	}

	private function getParameterOptional($name, $companyID, $default) {
		try {
			$value = trim($this->core_web_parameter->getParameter($name, $companyID)->value);
			return $value == "" ? $default : $value;
		} catch (\Exception $ex) {
			return $default;
		}
	}

	/**
	 * Obtiene el access token de la cuenta de servicio (JWT firmado RS256).
	 * Se guarda en cache ~55 minutos para no pedir uno nuevo en cada llamada.
	 */
	private function getAccessToken($companyID, $config) {
		$cacheKey = "google_calendar_token_" . $companyID . "_" . md5($config["serviceEmail"]);
		$cache    = \Config\Services::cache();
		$token    = $cache->get($cacheKey);
		if ($token)
			return $token;

		$now    = time();
		$header = array("alg" => "RS256", "typ" => "JWT");
		$claims = array(
			"iss"   => $config["serviceEmail"],
			"scope" => self::scope,
			"aud"   => self::urlToken,
			"iat"   => $now,
			"exp"   => $now + 3600
		);

		$unsigned = $this->base64Url(json_encode($header)) . "." . $this->base64Url(json_encode($claims));

		$key = openssl_pkey_get_private($config["privateKey"]);
		if (!$key)
			throw new \Exception("NO SE PUDO LEER LA LLAVE PRIVADA DE LA CUENTA DE SERVICIO");

		$signature = "";
		if (!openssl_sign($unsigned, $signature, $key, OPENSSL_ALGO_SHA256))
			throw new \Exception("NO SE PUDO FIRMAR EL TOKEN JWT");

		$jwt = $unsigned . "." . $this->base64Url($signature);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::urlToken);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
			"grant_type" => "urn:ietf:params:oauth:grant-type:jwt-bearer",
			"assertion"  => $jwt
		)));
		$raw      = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$curlErr  = curl_error($ch);
		curl_close($ch);

		if ($raw === false)
			throw new \Exception("ERROR DE CONEXION CON GOOGLE: " . $curlErr);

		$data = json_decode($raw, true);
		if ($httpCode != 200 || !isset($data["access_token"])) {
			$detalle = isset($data["error_description"]) ? $data["error_description"] : $raw;
			throw new \Exception("GOOGLE NO ENTREGO EL TOKEN (" . $httpCode . "): " . $detalle);
		}

		$cache->save($cacheKey, $data["access_token"], 3300);
		return $data["access_token"];
	}

	/**
	 * Arma el cuerpo del evento. Fin = inicio + GOOGLE_CALENDAR_DURATION_MINUTES.
	 * Sin hora => evento de todo el dia.
	 */
	private function buildEvent($config, $titulo, $descripcion, $fecha, $hora, $periodicidad) {
		$titulo = trim((string)$titulo);
		$fecha  = trim((string)$fecha);
		$hora   = trim((string)$hora);

		if ($titulo == "")
			throw new \Exception("EL TITULO ES REQUERIDO");
		if ($fecha == "")
			throw new \Exception("LA FECHA ES REQUERIDA");

		$tz = new \DateTimeZone($config["timeZone"]);

		$event = array(
			"summary"     => $titulo,
			"description" => (string)$descripcion
		);

		if ($hora == "") {
			$inicio = \DateTime::createFromFormat("!Y-m-d", substr($fecha, 0, 10), $tz);
			if (!$inicio)
				throw new \Exception("FECHA NO VALIDA (use Y-m-d): " . $fecha);
			$fin = clone $inicio;
			$fin->modify("+1 day"); // en Google la fecha final de un evento de dia completo es exclusiva

			$event["start"] = array("date" => $inicio->format("Y-m-d"));
			$event["end"]   = array("date" => $fin->format("Y-m-d"));
		} else {
			if (strlen($hora) == 5)
				$hora = $hora . ":00";
			$inicio = \DateTime::createFromFormat("Y-m-d H:i:s", substr($fecha, 0, 10) . " " . $hora, $tz);
			if (!$inicio)
				throw new \Exception("FECHA U HORA NO VALIDA (use Y-m-d y H:i): " . $fecha . " " . $hora);
			$fin = clone $inicio;
			$fin->modify("+" . $config["duration"] . " minutes");

			$event["start"] = array("dateTime" => $inicio->format("Y-m-d\TH:i:s"), "timeZone" => $config["timeZone"]);
			$event["end"]   = array("dateTime" => $fin->format("Y-m-d\TH:i:s"),    "timeZone" => $config["timeZone"]);
		}

		$rrule = $this->getRecurrence($periodicidad);
		if ($rrule != "")
			$event["recurrence"] = array($rrule);

		return $event;
	}

	/**
	 * unico (default) => sin repeticion
	 * diario / siempre / todos los dias => RRULE:FREQ=DAILY
	 * semanal / todas las semanas       => RRULE:FREQ=WEEKLY
	 * mensual / todos los meses         => RRULE:FREQ=MONTHLY
	 */
	private function getRecurrence($periodicidad) {
		$p = strtolower(trim((string)$periodicidad));
		$p = strtr($p, array("á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u"));

		switch ($p) {
			case "":
			case "unico":
			case "una vez":
			case "solo ese dia":
				return "";
			case "diario":
			case "siempre":
			case "todos los dias":
				return "RRULE:FREQ=DAILY";
			case "semanal":
			case "todas las semanas":
				return "RRULE:FREQ=WEEKLY";
			case "mensual":
			case "todos los meses":
				return "RRULE:FREQ=MONTHLY";
			default:
				throw new \Exception("PERIODICIDAD NO VALIDA: " . $periodicidad . " (use unico, diario, semanal, mensual)");
		}
	}

	/**
	 * Lista eventos desde $fecha (o hoy) durante 31 dias.
	 * Los eventos repetitivos se devuelven expandidos (una entrada por ocurrencia).
	 */
	private function listEvents($urlBase, $token, $config, $fecha) {
		$tz     = new \DateTimeZone($config["timeZone"]);
		$fecha  = trim((string)$fecha);
		$inicio = $fecha == "" ? new \DateTime("today", $tz) : \DateTime::createFromFormat("!Y-m-d", substr($fecha, 0, 10), $tz);
		if (!$inicio)
			throw new \Exception("FECHA NO VALIDA (use Y-m-d): " . $fecha);
		$fin = clone $inicio;
		$fin->modify("+31 days");

		$items     = array();
		$pageToken = "";
		do {
			$query = array(
				"timeMin"      => $inicio->format(\DateTime::RFC3339),
				"timeMax"      => $fin->format(\DateTime::RFC3339),
				"singleEvents" => "true",
				"orderBy"      => "startTime",
				"maxResults"   => 250,
				"timeZone"     => $config["timeZone"]
			);
			if ($pageToken != "")
				$query["pageToken"] = $pageToken;

			$response  = $this->request("GET", $urlBase . "?" . http_build_query($query), $token, null);
			$items     = array_merge($items, isset($response["items"]) ? $response["items"] : array());
			$pageToken = isset($response["nextPageToken"]) ? $response["nextPageToken"] : "";
		} while ($pageToken != "");

		return $items;
	}

	private function request($method, $url, $token, $body) {
		$headers = array("Authorization: Bearer " . $token, "Accept: application/json");

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		if ($body !== null) {
			$headers[] = "Content-Type: application/json";
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$raw      = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$curlErr  = curl_error($ch);
		curl_close($ch);

		if ($raw === false)
			throw new \Exception("ERROR DE CONEXION CON GOOGLE: " . $curlErr);

		// 204 = eliminado; 410 = ya estaba eliminado
		if ($method == "DELETE" && ($httpCode == 204 || $httpCode == 200 || $httpCode == 410))
			return array();

		$data = json_decode($raw, true);
		if ($httpCode < 200 || $httpCode >= 300) {
			$detalle = isset($data["error"]["message"]) ? $data["error"]["message"] : $raw;
			throw new \Exception("GOOGLE CALENDAR RESPONDIO " . $httpCode . ": " . $detalle);
		}

		return is_array($data) ? $data : array();
	}

	private function base64Url($value) {
		return rtrim(strtr(base64_encode($value), "+/", "-_"), "=");
	}
}
?>
