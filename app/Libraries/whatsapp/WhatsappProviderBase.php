<?php
//posme:2026-11-20
namespace App\Libraries\whatsapp;

use App\Libraries\core_web_whatsap;

/**
 * Base comun de los adaptadores de WhatsApp: guarda la libreria core_web_whatsap
 * (que tiene las llamadas HTTP reales) y arma el resultado normalizado.
 */
abstract class WhatsappProviderBase implements WhatsappProviderInterface
{
    /** @var core_web_whatsap */
    protected $whatsap;

    /**
     * @param core_web_whatsap $whatsap Libreria con los metodos de envio de cada proveedor
     */
    public function __construct(core_web_whatsap $whatsap)
    {
        $this->whatsap = $whatsap;
    }

    /**
     * Arma el resultado normalizado de un envio.
     * @param bool   $success
     * @param string $messageId
     * @param string $error
     * @param mixed  $raw Respuesta cruda del proveedor
     * @return array ['success','messageId','provider','error','raw']
     */
    protected function result($success, $messageId, $error, $raw)
    {
        return [
            "success"   => (bool) $success,
            "messageId" => (string) $messageId,
            "provider"  => $this->getName(),
            "error"     => $success ? "" : (string) $error,
            "raw"       => $raw
        ];
    }

    /**
     * Resultado de error para un tipo de archivo que el proveedor no soporta.
     * @param string $type
     * @return array resultado normalizado
     */
    protected function notSupported($type)
    {
        return $this->result(false, "", "El proveedor " . $this->getName() . " no soporta el envio de tipo '" . $type . "'", null);
    }
}
