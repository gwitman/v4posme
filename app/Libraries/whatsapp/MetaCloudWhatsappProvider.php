<?php
//posme:2026-11-20
namespace App\Libraries\whatsapp;

/**
 * Adaptador de la API oficial de Meta (WhatsApp Cloud API). Exito = success y messages[0].id.
 * Meta no recibe base64 (solo URL publica) y este adaptador no envia video.
 * Fuera de la ventana de 24 h Meta solo acepta plantillas (sendMessageByMetaCloudTemplate).
 */
class MetaCloudWhatsappProvider extends WhatsappProviderBase
{
    public function getName()
    {
        return "meta";
    }

    public function sendText($companyID, $phone, $message)
    {
        return $this->normalize($this->whatsap->sendMessageByMetaCloudText($companyID, $message, $phone));
    }

    public function sendMedia($companyID, $phone, $message, array $media)
    {
        $type     = strtolower(trim((string) ($media["type"] ?? "")));
        $url      = trim((string) ($media["url"] ?? ""));
        $fileName = (string) ($media["fileName"] ?? "");

        if ($type == "video") {
            return $this->notSupported($type);
        }
        if (stripos($url, "http") !== 0) {
            return $this->result(false, "", "Meta Cloud API requiere una URL publica del archivo (no acepta base64)", null);
        }

        switch ($type) {
            case "image":
                return $this->normalize($this->whatsap->sendMessageByMetaCloudImage($companyID, $url, $message, $phone));
            case "document":
                return $this->normalize($this->whatsap->sendMessageByMetaCloudPdf($companyID, $url, $fileName, $message, $phone));
            case "audio":
                return $this->normalize($this->whatsap->sendMessageByMetaCloudAudio($companyID, $url, $phone));
        }
        return $this->notSupported($type);
    }

    /**
     * @param mixed $raw Respuesta de sendMessageByMetaCloudRequest
     * @return array resultado normalizado
     */
    private function normalize($raw)
    {
        $success   = is_array($raw) && !empty($raw["success"]);
        $messageId = is_array($raw) ? (string) ($raw["messages"][0]["id"] ?? "") : "";
        $error     = is_array($raw) ? (string) ($raw["error"] ?? ($raw["message"] ?? "")) : "";
        return $this->result($success, $messageId, $error != "" ? $error : "Meta Cloud API no confirmo el envio", $raw);
    }
}
