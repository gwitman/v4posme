<?php
//posme:2026-11-09
namespace App\Libraries\core_web_deploy;

/**
 * Funciones puras para la pestaña "Configuración y logos" de la consola de despliegue:
 * fin de línea de los archivos de app/Config, validación UTF-8 y preparación/validación
 * de logos. Las comparten la consola (core_web_deploy) y el agente (core_deploy_agent).
 */
class core_deploy_company_files
{
    /**
     * Detecta el fin de línea predominante de un texto.
     *
     * @param string $content Texto.
     * @return string 'crlf' | 'lf' (sin saltos de línea → 'lf')
     */
    static function detectEol($content)
    {
        $content = (string) $content;
        $crlf    = substr_count($content, "\r\n");
        $lf      = substr_count($content, "\n");
        return $crlf > 0 && $crlf * 2 >= $lf ? 'crlf' : 'lf';
    }

    /**
     * Convierte todos los fines de línea de un texto al formato indicado.
     *
     * @param string $content Texto (cualquier mezcla de CRLF/LF).
     * @param string $eol     'crlf' | 'lf'
     * @return string
     */
    static function applyEol($content, $eol)
    {
        $normalized = str_replace("\r\n", "\n", (string) $content);
        return $eol === 'crlf' ? str_replace("\n", "\r\n", $normalized) : $normalized;
    }

    /**
     * Indica si un texto es UTF-8 válido.
     *
     * @param string $content Texto.
     * @return bool
     */
    static function isUtf8($content)
    {
        return is_string($content) && preg_match('//u', $content) === 1;
    }

    /**
     * Identificador de una carpeta de respaldo de archivos: AAAAMMDD_HHMMSS_xxxxxxxx.
     *
     * @return string
     */
    static function newBackupID()
    {
        return date('Ymd_His') . '_' . bin2hex(random_bytes(4));
    }

    /**
     * Valida un identificador de respaldo (evita rutas maliciosas).
     *
     * @param string $backupID Identificador.
     * @return bool
     */
    static function isBackupID($backupID)
    {
        return is_string($backupID) && preg_match('/^[0-9]{8}_[0-9]{6}_[a-f0-9]{8}$/D', $backupID) === 1;
    }

    /**
     * Valida que un binario sea una imagen del formato del logo destino y dentro de los límites.
     * Se usa en el agente antes de escribir.
     *
     * @param string $binary     Contenido de la imagen.
     * @param string $targetName Nombre del logo destino (lista fija).
     * @return array ['width', 'height']
     */
    static function checkLogoBinary($binary, $targetName)
    {
        if (!core_deploy_rules::isLogoName($targetName)) {
            throw new \Exception('Logo no permitido: ' . $targetName);
        }
        if (!is_string($binary) || $binary === '') {
            throw new \Exception('La imagen está vacía.');
        }
        if (strlen($binary) > core_deploy_rules::LOGO_MAX_BYTES) {
            throw new \Exception('La imagen supera 1 MB.');
        }
        $info = @getimagesizefromstring($binary);
        if (!is_array($info)) {
            throw new \Exception('El archivo no es una imagen válida.');
        }
        $expected = core_deploy_rules::logoFormat($targetName) === 'png' ? IMAGETYPE_PNG : IMAGETYPE_JPEG;
        if ((int) $info[2] !== $expected) {
            throw new \Exception('El formato de la imagen no coincide con la extensión de ' . $targetName . '.');
        }
        if ($info[0] < 1 || $info[1] < 1 || $info[0] > core_deploy_rules::LOGO_MAX_PIXELS || $info[1] > core_deploy_rules::LOGO_MAX_PIXELS) {
            throw new \Exception('La imagen debe medir como máximo ' . core_deploy_rules::LOGO_MAX_PIXELS . 'x' . core_deploy_rules::LOGO_MAX_PIXELS . ' px.');
        }
        return ['width' => (int) $info[0], 'height' => (int) $info[1]];
    }

    /**
     * Avisos (no errores) de un logo ya preparado.
     *
     * @param string $targetName Nombre del logo.
     * @param int    $width      Ancho en px.
     * @return array Lista de textos.
     */
    static function logoWarnings($targetName, $width)
    {
        $warnings = [];
        if (core_deploy_rules::isTicketLogo($targetName) && (int) $width > core_deploy_rules::LOGO_TICKET_MAX_WIDTH) {
            $warnings[] = $targetName . ': mide ' . (int) $width . ' px de ancho; para tickets de 80 mm se recomienda un máximo de ' . core_deploy_rules::LOGO_TICKET_MAX_WIDTH . ' px.';
        }
        return $warnings;
    }

    /**
     * Re-codifica SIEMPRE una imagen con GD al formato de la extensión destino.
     * PNG destino: imagepng conservando transparencia. JPG destino: fondo blanco, calidad 90.
     * Así se descarta cualquier contenido ajeno incrustado en el archivo original.
     *
     * @param string $binary     Imagen recibida (PNG, JPG, GIF, WEBP… lo que soporte GD).
     * @param string $targetName Nombre del logo destino (lista fija).
     * @return array ['binary', 'width', 'height', 'warnings']
     */
    static function prepareLogo($binary, $targetName)
    {
        if (!core_deploy_rules::isLogoName($targetName)) {
            throw new \Exception('Logo no permitido: ' . $targetName);
        }
        if (!function_exists('imagecreatefromstring')) {
            throw new \Exception('La extensión GD de PHP no está disponible en este equipo.');
        }
        if (!is_string($binary) || $binary === '') {
            throw new \Exception('La imagen está vacía.');
        }
        if (strlen($binary) > core_deploy_rules::LOGO_MAX_BYTES) {
            throw new \Exception('La imagen recibida supera 1 MB.');
        }
        // Validar dimensiones antes de decodificar (evita agotar memoria con imágenes gigantes)
        $info = @getimagesizefromstring($binary);
        if (!is_array($info)) {
            throw new \Exception('El archivo no es una imagen válida.');
        }
        $width  = (int) $info[0];
        $height = (int) $info[1];
        if ($width < 1 || $height < 1 || $width > core_deploy_rules::LOGO_MAX_PIXELS || $height > core_deploy_rules::LOGO_MAX_PIXELS) {
            throw new \Exception('La imagen debe medir como máximo ' . core_deploy_rules::LOGO_MAX_PIXELS . 'x' . core_deploy_rules::LOGO_MAX_PIXELS . ' px.');
        }
        $source = @imagecreatefromstring($binary);
        if ($source === false) {
            throw new \Exception('No se pudo leer la imagen (formato no soportado).');
        }

        ob_start();
        if (core_deploy_rules::logoFormat($targetName) === 'png') {
            imagealphablending($source, false);
            imagesavealpha($source, true);
            $ok = imagepng($source);
        } else {
            // JPG no tiene transparencia: se aplana sobre fondo blanco
            $canvas = imagecreatetruecolor($width, $height);
            imagefill($canvas, 0, 0, imagecolorallocate($canvas, 255, 255, 255));
            imagealphablending($canvas, true);
            imagecopy($canvas, $source, 0, 0, 0, 0, $width, $height);
            $ok = imagejpeg($canvas, null, 90);
            unset($canvas);
        }
        $output = (string) ob_get_clean();
        unset($source);

        if (!$ok || $output === '') {
            throw new \Exception('No se pudo convertir la imagen.');
        }
        if (strlen($output) > core_deploy_rules::LOGO_MAX_BYTES) {
            throw new \Exception('La imagen convertida a ' . strtoupper(core_deploy_rules::logoFormat($targetName)) . ' supera 1 MB (' . number_format(strlen($output) / 1048576, 2) . ' MB); redúzcala antes de subirla.');
        }
        return [
            'binary'   => $output,
            'width'    => $width,
            'height'   => $height,
            'warnings' => self::logoWarnings($targetName, $width),
        ];
    }

    /**
     * Indica si dos rutas apuntan a la misma carpeta (ignora mayúsculas en Windows).
     *
     * @param string|false $a Ruta resuelta (realpath).
     * @param string|false $b Ruta resuelta (realpath).
     * @return bool
     */
    static function samePath($a, $b)
    {
        if (!is_string($a) || !is_string($b) || $a === '' || $b === '') {
            return false;
        }
        $a = rtrim(str_replace('\\', '/', $a), '/');
        $b = rtrim(str_replace('\\', '/', $b), '/');
        return DIRECTORY_SEPARATOR === '\\' ? strcasecmp($a, $b) === 0 : $a === $b;
    }
}
