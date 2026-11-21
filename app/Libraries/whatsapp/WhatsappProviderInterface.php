<?php
//posme:2026-11-20
namespace App\Libraries\whatsapp;

/**
 * Contrato de un proveedor de envio de WhatsApp (Evolution, Meta Cloud, LiveConnect…).
 * Para agregar un proveedor: nueva clase que implemente esta interfaz y una linea en
 * core_web_whatsap::$whatsappProviders.
 *
 * Todas las funciones devuelven el resultado NORMALIZADO:
 * ['success' => bool, 'messageId' => string, 'provider' => string, 'error' => string, 'raw' => mixed]
 */
interface WhatsappProviderInterface
{
    /**
     * Nombre con que se registra el proveedor (evolution, meta, liveconnect).
     * @return string
     */
    public function getName();

    /**
     * Envia un mensaje de texto.
     * @param int    $companyID
     * @param string $phone   Telefono destino (solo digitos)
     * @param string $message Texto
     * @return array resultado normalizado
     */
    public function sendText($companyID, $phone, $message);

    /**
     * Envia un archivo (imagen, documento, video o audio) con el texto como leyenda.
     * @param int    $companyID
     * @param string $phone   Telefono destino (solo digitos)
     * @param string $message Leyenda / texto que acompaña al archivo
     * @param array  $media   ['type' => 'image|document|video|audio', 'url' => .., 'fileName' => .., 'fileExt' => ..]
     *                        (url puede ser base64 para document en Evolution)
     * @return array resultado normalizado
     */
    public function sendMedia($companyID, $phone, $message, array $media);
}
