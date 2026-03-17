<?php
//posme:2024-01-15
namespace App\Libraries;

/**
 * core_web_loto_http_client
 *
 * HTTP Client para obtener resultados de lotería desde loto.com.ni.
 * Usa cURL nativo para control fino sobre la petición.
 *
 * Requisitos: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 3.7, 12.1, 13.4, 14.2, 14.3, 14.5
 */
class core_web_loto_nicaragua_http_client {

    /**********************Variables Estaticas********************/
    /*************************************************************/

    private const USER_AGENT     = 'Mozilla/5.0 (compatible; LotoScraper/1.0; +https://nuevaya.com.ni)';
    private const ALLOWED_DOMAIN = 'nuevaya.com.ni';
    private const DEFAULT_TIMEOUT = 30;

    private $CI;
    private $lastRequestTime = 0.0;
	
	/**********************Variables Estaticas********************/
    /*************************************************************/

    /**
     * Mapa de nombres de hora a formato HH:MM:SS
     */
    private $timeMap = [
        '12:00 m'  => '12:00:00',
        '12:00 md' => '12:00:00',
        '3:00 pm'  => '15:00:00',
        '6:00 pm'  => '18:00:00',
        '9:00 pm'  => '21:00:00',
    ];

    /**
     * Meses en español a número
     */
    private $monthMap = [
        'enero'      => '01', 'febrero'   => '02', 'marzo'    => '03',
        'abril'      => '04', 'mayo'      => '05', 'junio'    => '06',
        'julio'      => '07', 'agosto'    => '08', 'septiembre' => '09',
        'octubre'    => '10', 'noviembre' => '11', 'diciembre' => '12',
    ];


    /**********************Funciones******************************/
    /*************************************************************/

    public function __construct() {
    }

    /**
     * Obtiene el HTML de resultados de lotería desde la URL indicada.
     *
     * @param string $url URL del sitio loto.com.ni
     * @return string|null HTML de la página o NULL si falla
     */
    function fetchLotteryResults($url) {
        if (!$this->isAllowedDomain($url)) {
            log_message('error', '[core_web_loto_http_client] URL no pertenece al dominio permitido: ' . $this->sanitizeUrlForLog($url));
            return null;
        }

        $response = $this->makeRequest($url, self::DEFAULT_TIMEOUT);

        if (!$this->validateResponse($response)) {
            return null;
        }

        return $response['body'];
    }

    /**
     * Realiza la petición HTTP usando cURL nativo.
     *
     * @param string $url     URL destino
     * @param int    $timeout Timeout en segundos
     * @return array
     */
    private function makeRequest($url, $timeout = self::DEFAULT_TIMEOUT) {
        $this->applyRateLimit();

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_USERAGENT      => self::USER_AGENT,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 5,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_ENCODING       => '',
        ]);

        $body   = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error  = curl_error($ch);
        curl_close($ch);

        $this->lastRequestTime = microtime(true);

        if ($error !== '') {
            log_message('error', '[core_web_loto_http_client] Error cURL para ' . $this->sanitizeUrlForLog($url) . ': ' . $error);
        }

        return [
            'status' => $status,
            'body'   => is_string($body) ? $body : '',
            'error'  => $error,
        ];
    }

    /**
     * Valida que la respuesta HTTP sea utilizable.
     *
     * @param array $response Respuesta de makeRequest()
     * @return bool
     */
    private function validateResponse($response) {
        if ($response['error'] !== '') {
            return false;
        }
        if ($response['status'] !== 200) {
            log_message('error', '[core_web_loto_http_client] Código HTTP inesperado: ' . $response['status']);
            return false;
        }
        if (empty($response['body'])) {
            log_message('error', '[core_web_loto_http_client] Respuesta HTTP vacía (HTTP 200 pero sin cuerpo)');
            return false;
        }
        return true;
    }

    /**
     * Aplica rate limiting: garantiza al menos 1 segundo entre peticiones.
     */
    private function applyRateLimit() {
        if ($this->lastRequestTime > 0.0) {
            $elapsed = microtime(true) - $this->lastRequestTime;
            if ($elapsed < 1.0) {
                sleep(1);
            }
        }
    }

    /**
     * Valida que la URL pertenezca al dominio loto.com.ni.
     *
     * @param string $url
     * @return bool
     */
    private function isAllowedDomain($url) {
        $host = parse_url($url, PHP_URL_HOST);
        if ($host === null || $host === false) {
            return false;
        }
        return $host === self::ALLOWED_DOMAIN
            || str_ends_with($host, '.' . self::ALLOWED_DOMAIN);
    }

    /**
     * Sanitiza la URL para logging: elimina query string.
     *
     * @param string $url
     * @return string
     */
    private function sanitizeUrlForLog($url) {
        $parts = parse_url($url);
        if ($parts === false) {
            return '[URL inválida]';
        }
        return ($parts['scheme'] ?? 'https') . '://'
            . ($parts['host'] ?? '')
            . ($parts['path'] ?? '');
    }
	
	
	/**
     * Parsea el HTML y extrae el resultado más reciente de Loto Diaria.
     * Retorna el primer sorteo del día que tenga número ganador.
     *
     * @param string $html HTML de la página
     * @return array|null ['winningNumber'=>'61','drawDate'=>'2026-03-17','drawTime'=>'12:00:00'] o NULL
     */
    function parseResults($html) {
        if (empty($html)) {
            log_message('error', '[core_web_loto_nicaragua_parser] HTML vacío recibido');
            return null;
        }

        try {
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_NOERROR | LIBXML_NOWARNING);
            libxml_clear_errors();

            $drawDate = $this->extractDrawDate($dom);
            if ($drawDate === null) {
                log_message('error', '[core_web_loto_nicaragua_parser] No se pudo extraer la fecha del sorteo');
                return null;
            }

            // Buscar todas las tablas de resultados
            $xpath  = new \DOMXPath($dom);
            $tables = $xpath->query('//figure[contains(@class,"wp-block-table")]//table[contains(@class,"has-fixed-layout")]');

            if ($tables === false || $tables->length === 0) {
                log_message('error', '[core_web_loto_nicaragua_parser] No se encontraron tablas de resultados');
                return null;
            }

            foreach ($tables as $table) 
            {
                $result = $this->extractResultFromTable($table, $drawDate, $xpath);
                if ($result !== null) {
                    return $result;
                }
            }

            log_message('error', '[core_web_loto_nicaragua_parser] Ninguna tabla contenía resultados válidos');
            return null;

        } catch (\Exception $e) {
            log_message('error', '[core_web_loto_nicaragua_parser] Excepción al parsear HTML: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Extrae la fecha del sorteo desde el h2 de fecha en el HTML.
     * Busca patrones como "Martes 17 de Marzo 2026".
     *
     * @param DOMDocument $dom
     * @return string|null Fecha en formato Y-m-d o NULL
     */
    function extractDrawDate($dom) {
        $xpath = new \DOMXPath($dom);
        // Buscar h2 que contenga patrón de fecha con día, mes y año
        $headings = $xpath->query('//h2[contains(@class,"wp-block-heading") and contains(@class,"has-text-align-center")]');

        if ($headings !== false) {
            foreach ($headings as $h2) {
                $text = $this->cleanText($h2->textContent);
                $date = $this->parseDateString($text);
                if ($date !== null) {
                    return $date;
                }
            }
        }

        // Fallback: buscar cualquier h2 con patrón de fecha
        $allH2 = $xpath->query('//h2');
        if ($allH2 !== false) {
            foreach ($allH2 as $h2) {
                $text = $this->cleanText($h2->textContent);
                $date = $this->parseDateString($text);
                if ($date !== null) {
                    return $date;
                }
            }
        }

        return null;
    }

    /**
     * Extrae el número ganador de Loto Diaria desde una tabla de resultados.
     *
     * @param DOMNode   $table
     * @param string    $drawDate
     * @param DOMXPath  $xpath
     * @return array|null
     */
    private function extractResultFromTable($table, $drawDate, $xpath) {
        $rows = $xpath->query('.//tr', $table);
        if ($rows === false || $rows->length < 3) {
            return null;
        }

        // Fila 0: hora del sorteo (colspan=4, contiene h2 con la hora)
        $timeRow  = $rows->item(0);
        $drawTime = $this->extractDrawTimeFromRow($timeRow);
        if ($drawTime === null) {
            return null;
        }

        // Fila 1: encabezados (Loto Diaria | Fechas | Jugá3 | Premia2)
        // Fila 2: datos — primera celda es el número de Loto Diaria
        $dataRow = $rows->item(2);
        if ($dataRow === null) {
            return null;
        }

        $cells = $xpath->query('.//td', $dataRow);
        if ($cells === false || $cells->length === 0) {
            return null;
        }

        $rawNumber = $this->cleanText($cells->item(0)->textContent);
        if (empty($rawNumber)) {
            return null; // Sorteo aún no realizado
        }

        return [
            'lotoDiaria'    => $cells->item(0)->textContent,
            'lotoFechas'    => $cells->item(1)->textContent,
            'lotoJuega3'    => $cells->item(2)->textContent,
            'lotoPremia2'   => $cells->item(3)->textContent,
            'drawDate'      => $drawDate, /*fecha del sorteo*/
            'drawTime'      => $drawTime,/*hora del sorteo*/
        ];
    }

    /**
     * Extrae la hora del sorteo desde la fila de encabezado de la tabla.
     * El texto puede ser "12:00 m", "3:00 pm", "6:00 pm", "9:00 pm".
     *
     * @param DOMNode $row
     * @return string|null Hora en formato HH:MM:SS o NULL
     */
    function extractDrawTime($row) {
        return $this->extractDrawTimeFromRow($row);
    }

    /**
     * @param DOMNode $row
     * @return string|null
     */
    private function extractDrawTimeFromRow($row) {
        $text = $this->cleanText($row->textContent);
        $text = strtolower($text);

        foreach ($this->timeMap as $pattern => $time) {
            if (strpos($text, $pattern) !== false) {
                return $time;
            }
        }
        return null;
    }

    /**
     * Extrae solo los dígitos del número ganador.
     * El texto puede ser "61 (JG)", "47", "23 (2X)", etc.
     *
     * @param string $rawText
     * @return string|null Solo dígitos, o NULL si no hay dígitos
     */
    function extractWinningNumber($rawText) {
        // Extraer los primeros dígitos antes de cualquier paréntesis o espacio
        if (preg_match('/^(\d+)/', trim($rawText), $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Limpia y normaliza texto: elimina espacios múltiples, trim, etc.
     *
     * @param string $text
     * @return string
     */
    function cleanText($text) {
        if (empty($text)) {
            return '';
        }
        // Normalizar espacios y saltos de línea
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        return $text;
    }

    /**
     * Parsea un string de fecha en español como "Martes 17 de Marzo 2026"
     * y retorna formato Y-m-d.
     *
     * @param string $text
     * @return string|null
     */
    private function parseDateString($text) {
        // Patrón: (día semana) DD de MES YYYY
        if (preg_match('/(\d{1,2})\s+de\s+([a-záéíóúü]+)\s+(\d{4})/iu', $text, $m)) {
            $day   = str_pad($m[1], 2, '0', STR_PAD_LEFT);
            $month = strtolower($m[2]);
            $year  = $m[3];

            if (isset($this->monthMap[$month])) {
                return $year . '-' . $this->monthMap[$month] . '-' . $day;
            }
        }
        return null;
    }
	
	
}

