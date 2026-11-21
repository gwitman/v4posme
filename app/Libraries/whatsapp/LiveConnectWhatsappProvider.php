<?php
//posme:2026-11-20
namespace App\Libraries\whatsapp;

/**
 * Adaptador de LiveConnect (GlobalPro). Sus metodos devuelven un string:
 *  - status_message de LiveConnect cuando el envio se hizo,
 *  - "cURL Error #:..." si fallo la conexion,
 *  - "" si no se obtuvo el token o la respuesta no trae status_message.
 * Regla de exito: string no vacio que no empiece por "cURL Error".
 * LiveConnect envia archivos por /direct/wa/sendFile sin leyenda: el texto se envia aparte despues del archivo.
 */
class LiveConnectWhatsappProvider extends WhatsappProviderBase
{
    public function getName()
    {
        return "liveconnect";
    }

    public function sendText($companyID, $phone, $message)
    {
        return $this->normalize($this->whatsap->sendMessageByLiveconnect($companyID, $message, $phone));
    }

    public function sendMedia($companyID, $phone, $message, array $media)
    {
        $type = strtolower(trim((string) ($media["type"] ?? "")));
        $url  = trim((string) ($media["url"] ?? ""));
        if (!in_array($type, ["image", "document", "video", "audio"])) {
            return $this->notSupported($type);
        }
        if (stripos($url, "http") !== 0) {
            return $this->result(false, "", "LiveConnect requiere una URL publica del archivo (no acepta base64)", null);
        }

        //Nombre y extension: los del media o se derivan de la URL
        $path     = (string) parse_url($url, PHP_URL_PATH);
        $fileExt  = trim((string) ($media["fileExt"] ?? ""));
        $fileExt  = $fileExt != "" ? ltrim($fileExt, ".") : strtolower((string) pathinfo($path, PATHINFO_EXTENSION));
        $fileName = trim((string) ($media["fileName"] ?? ""));
        $fileName = $fileName != "" ? $fileName : (string) pathinfo($path, PATHINFO_FILENAME);

        $result = $this->normalize($this->whatsap->sendMessageByLiveconnectFile($companyID, $message, $phone, $url, $fileName, $fileExt));
        if ($result["success"] && trim((string) $message) != "") {
            $this->whatsap->sendMessageByLiveconnect($companyID, $message, $phone);
        }
        return $result;
    }

    /**
     * @param mixed $raw string devuelto por sendMessageByLiveconnect / sendMessageByLiveconnectFile
     * @return array resultado normalizado
     */
    private function normalize($raw)
    {
        $text    = is_string($raw) ? trim($raw) : "";
        $success = $text != "" && stripos($text, "cURL Error") !== 0;
        return $this->result($success, "", $text != "" ? $text : "LiveConnect no confirmo el envio", $raw);
    }
}
