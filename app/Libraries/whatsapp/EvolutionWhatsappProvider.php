<?php
//posme:2026-11-20
namespace App\Libraries\whatsapp;

/**
 * Adaptador de Evolution API. La instancia siempre es tb_company.type + "_" + WHATSAP_URL_REQUEST_SESSION
 * (core_web_whatsap::evolutionSendInstanceName). Exito = la respuesta trae key.id.
 */
class EvolutionWhatsappProvider extends WhatsappProviderBase
{
    public function getName()
    {
        return "evolution";
    }

    public function sendText($companyID, $phone, $message)
    {
        return $this->normalize($this->whatsap->sendMessageByEvolutionApiText($companyID, $message, $phone));
    }

    public function sendMedia($companyID, $phone, $message, array $media)
    {
        $type     = strtolower(trim((string) ($media["type"] ?? "")));
        $url      = (string) ($media["url"] ?? "");
        $fileName = (string) ($media["fileName"] ?? "");

        switch ($type) {
            case "image":
                return $this->normalize($this->whatsap->sendMessageByEvolutionApiImage($companyID, $url, $message, $phone));
            case "document":
                return $this->normalize($this->whatsap->sendMessageByEvolutionApiPdf($companyID, $url, $fileName, $message, $phone));
            case "video":
                return $this->normalize($this->whatsap->sendMessageByEvolutionApiVideo($companyID, $url, $message, $phone));
            case "audio":
                return $this->normalize($this->whatsap->sendMessageByEvolutionApiAudio($companyID, $url, $phone));
        }
        return $this->notSupported($type);
    }

    /**
     * Evolution acepta el mensaje cuando devuelve key.id.
     * @param mixed $raw Respuesta decodificada de Evolution
     * @return array resultado normalizado
     */
    private function normalize($raw)
    {
        $messageId = is_array($raw) ? (string) ($raw["key"]["id"] ?? "") : "";
        if ($messageId != "") {
            return $this->result(true, $messageId, "", $raw);
        }

        $error = "";
        if (is_array($raw)) {
            $error = $raw["error"] ?? ($raw["response"]["message"] ?? ($raw["message"] ?? ""));
            $error = is_array($error) ? json_encode($error, JSON_UNESCAPED_UNICODE) : (string) $error;
        }
        return $this->result(false, "", $error != "" ? $error : "Evolution API no confirmo el envio", $raw);
    }
}
