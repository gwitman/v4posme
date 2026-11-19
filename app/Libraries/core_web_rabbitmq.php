<?php
//posme:2026-11-15
namespace App\Libraries;

use PhpAmqpLib\Connection\AMQPConnectionConfig;
use PhpAmqpLib\Connection\AMQPConnectionFactory;
use PhpAmqpLib\Message\AMQPMessage;
use App\Models\Core\Company_Model;

/**
 * Cliente de colas compatible con RabbitMQ / LavinMQ (CloudAMQP) usando php-amqplib.
 *
 * Parametros por empresa (tb_company_parameter):
 *  - RABBITMQ_URL             : amqps://usuario:clave@host/vhost (amqp:// para conexion sin SSL)
 *  - RABBITMQ_QUEUE_WHATSAPP  : prefijo de la cola de WhatsApp masivo; la cola real es
 *                               <valor>_<tb_company.type>. *
 * Los mensajes viajan como JSON y son persistentes (sobreviven a un reinicio del broker).
 * Los que fallan se reencolan al final con el contador "intentos"; al llegar al maximo
 * se mueven a la cola "<cola>_error" para revision manual.
 */
class core_web_rabbitmq
{
    const PARAMETER_URL             = "RABBITMQ_URL";
    const PARAMETER_QUEUE_WHATSAPP  = "RABBITMQ_QUEUE_WHATSAPP";
    const SUFFIX_QUEUE_ERROR        = "_error";    //Valor que puede devolver el callback de consume para mandar el mensaje directo a la cola de error (sin reintentos)
    const RESULT_DESCARTAR          = "descartar";

    public function __construct()
    {
    }

    /**
     * Abre una conexion AMQP con la URL configurada en el parametro RABBITMQ_URL de la empresa.
     * @param int $companyID empresa
     * @return \PhpAmqpLib\Connection\AbstractConnection conexion abierta
     */
    function getConnection($companyID)
    {
        if (!class_exists(AMQPConnectionFactory::class)) {
            throw new \Exception("No esta instalada la libreria php-amqplib (composer require php-amqplib/php-amqplib)");
        }

        $core_web_parameter = new core_web_parameter();
        $amqpUrl            = trim((string) $core_web_parameter->getParameterValue(self::PARAMETER_URL, $companyID));
        if ($amqpUrl == "" || $amqpUrl == "0") {
            throw new \Exception("El parametro " . self::PARAMETER_URL . " esta vacio para la company " . $companyID);
        }

        $url = parse_url($amqpUrl);
        if ($url === false || !isset($url["host"])) {
            throw new \Exception("El parametro " . self::PARAMETER_URL . " no tiene un formato valido (amqps://usuario:clave@host/vhost)");
        }

        $isSecure = strtolower($url["scheme"] ?? "amqps") == "amqps";
        $vhost    = isset($url["path"]) && ltrim($url["path"], "/") != "" ? rawurldecode(ltrim($url["path"], "/")) : "/";

        $config = new AMQPConnectionConfig();
        $config->setHost($url["host"]);
        $config->setPort((int) ($url["port"] ?? ($isSecure ? 5671 : 5672)));
        $config->setUser(rawurldecode($url["user"] ?? "guest"));
        $config->setPassword(rawurldecode($url["pass"] ?? "guest"));
        $config->setVhost($vhost);
        $config->setConnectionTimeout(10);
        $config->setReadTimeout(30);
        $config->setWriteTimeout(30);
        $config->setHeartbeat(0);

        if ($isSecure) {
            $config->setIsSecure(true);
            $config->setSslVerify(true);
            $config->setSslVerifyName(true);

            //En Windows/XAMPP openssl.cafile suele venir vacio: se usa el bundle de curl si existe
            $caFile = (string) ini_get("openssl.cafile");
            if ($caFile == "") {
                $caFile = (string) ini_get("curl.cainfo");
            }
            if ($caFile != "" && is_file($caFile)) {
                $config->setSslCaCert($caFile);
            }
        }

        return AMQPConnectionFactory::create($config);
    }

    /**
     * Nombre de la cola de WhatsApp masivo: valor del parametro RABBITMQ_QUEUE_WHATSAPP + "_" + tb_company.type.
     * El type distingue las instalaciones que comparten el mismo broker.
     * @param int $companyID empresa
     * @return string nombre de la cola
     */
    function getQueueWhatsapp($companyID)
    {
        $core_web_parameter = new core_web_parameter();
        $queueName          = trim((string) $core_web_parameter->getParameterValue(self::PARAMETER_QUEUE_WHATSAPP, $companyID));
        if ($queueName == "" || $queueName == "0") {
            throw new \Exception("El parametro " . self::PARAMETER_QUEUE_WHATSAPP . " esta vacio para la company " . $companyID);
        }
        return $queueName . "_" . $this->getCompanyType($companyID);
    }

    /**
     * Tipo de la compañia (tb_company.type), usado para formar los nombres de las colas.
     * @param int $companyID empresa
     * @return string type de la compañia
     */
    function getCompanyType($companyID)
    {
        $Company_Model = new Company_Model();
        $objCompany    = $Company_Model->get_rowByPK($companyID);
        $type          = $objCompany ? trim((string) $objCompany->type) : "";
        if ($type == "") {
            throw new \Exception("La company " . $companyID . " no tiene tb_company.type para formar el nombre de la cola");
        }
        return $type;
    }

    /**
     * Publica varios mensajes en una cola usando una sola conexion (publicacion en lote).
     * @param int    $companyID empresa
     * @param string $queueName cola destino (se declara durable si no existe)
     * @param array  $payloads  lista de arreglos asociativos; cada uno se envia como JSON
     * @return int cantidad de mensajes publicados
     */
    function publishBatch($companyID, $queueName, $payloads)
    {
        if (!is_array($payloads) || count($payloads) == 0) {
            return 0;
        }

        $connection = $this->getConnection($companyID);
        $channel    = $connection->channel();
        $total      = 0;

        try {
            $this->declareQueue($channel, $queueName);

            foreach ($payloads as $payload) {
                $payload["intentos"]  = (int) ($payload["intentos"] ?? 0);
                $payload["encolado"]  = $payload["encolado"] ?? date("Y-m-d H:i:s");
                $channel->batch_basic_publish($this->createMessage($payload), "", $queueName);
                $total++;
            }
            $channel->publish_batch();
        } finally {
            $this->close($channel, $connection);
        }

        log_message("info", "[core_web_rabbitmq] publishBatch - cola: " . $queueName . " | mensajes: " . $total);
        return $total;
    }

    /**
     * Publica un solo mensaje en una cola.
     * @param int    $companyID empresa
     * @param string $queueName cola destino
     * @param array  $payload   datos del mensaje
     * @return bool true si se publico
     */
    function publish($companyID, $queueName, $payload)
    {
        return $this->publishBatch($companyID, $queueName, [$payload]) == 1;
    }

    /**
     * Consume mensajes de una cola de forma acotada, pensado para ejecutarse por cron.
     * Toma un mensaje a la vez (basic_get) y llama $callback($payload); si devuelve true se
     * confirma (ack), si devuelve RESULT_DESCARTAR va directo a "<cola>_error", si devuelve
     * false o lanza excepcion se reencola con intentos + 1, y al superar $maxIntentos se
     * mueve a "<cola>_error".
     * Un candado de archivo evita que dos ejecuciones del cron consuman la misma cola a la vez.
     *
     * @param int      $companyID   empresa
     * @param string   $queueName   cola a consumir
     * @param callable $callback    function(array $payload): bool|string (true, false o RESULT_DESCARTAR)
     * @param int      $maxMessages maximo de mensajes a procesar en esta ejecucion
     * @param int      $maxSeconds  tiempo maximo de la ejecucion (debe ser menor al intervalo del cron)
     * @param int      $pauseSeconds pausa entre mensajes procesados (evita bloqueos de WhatsApp)
     * @param int      $maxIntentos intentos antes de mover el mensaje a la cola de error
     * @return array resumen: procesados, exitosos, reencolados, error, pendientes, bloqueado
     */
    function consume($companyID, $queueName, $callback, $maxMessages = 20, $maxSeconds = 50, $pauseSeconds = 3, $maxIntentos = 3)
    {
        $result = [
            "procesados"  => 0,
            "exitosos"    => 0,
            "reencolados" => 0,
            "error"       => 0,
            "pendientes"  => 0,
            "bloqueado"   => false
        ];

        $lockHandle = $this->acquireLock($queueName);
        if ($lockHandle === false) {
            $result["bloqueado"] = true;
            log_message("info", "[core_web_rabbitmq] consume - cola " . $queueName . " en uso por otra ejecucion");
            return $result;
        }

        $connection = null;
        $channel    = null;
        $inicio     = time();

        try {
            $connection = $this->getConnection($companyID);
            $channel    = $connection->channel();
            $this->declareQueue($channel, $queueName);
            $this->declareQueue($channel, $queueName . self::SUFFIX_QUEUE_ERROR);

            while ($result["procesados"] < $maxMessages && (time() - $inicio) < $maxSeconds) {
                $message = $channel->basic_get($queueName, false);
                if ($message === null) {
                    break;
                }

                $result["procesados"]++;
                $payload = json_decode($message->getBody(), true);
                $exitoso = false;

                if (!is_array($payload)) {
                    //Cuerpo ilegible: no tiene sentido reintentarlo
                    log_message("error", "[core_web_rabbitmq] consume - mensaje no es JSON valido: " . $message->getBody());
                    $channel->basic_publish($this->createMessage(["cuerpo" => $message->getBody(), "ultimoError" => "JSON invalido"]), "", $queueName . self::SUFFIX_QUEUE_ERROR);
                    $channel->basic_ack($message->getDeliveryTag());
                    $result["error"]++;
                    continue;
                }

                try {
                    $respuesta = $callback($payload);
                    $exitoso   = $respuesta === true;

                    //Datos invalidos (ej. sin telefono): reintentar no lo arregla
                    if ($respuesta === self::RESULT_DESCARTAR) {
                        $payload["ultimoError"] = "Mensaje descartado por datos invalidos";
                        $channel->basic_publish($this->createMessage($payload), "", $queueName . self::SUFFIX_QUEUE_ERROR);
                        $channel->basic_ack($message->getDeliveryTag());
                        $result["error"]++;
                        continue;
                    }

                    if (!$exitoso) {
                        $payload["ultimoError"] = "El envio no fue confirmado";
                    }
                } catch (\Throwable $ex) {
                    $payload["ultimoError"] = $ex->getMessage();
                    log_message("error", "[core_web_rabbitmq] consume - error procesando mensaje: " . $ex->getMessage());
                }

                if ($exitoso) {
                    $channel->basic_ack($message->getDeliveryTag());
                    $result["exitosos"]++;
                } else {
                    //Se publica la copia antes del ack para no perder el mensaje si la conexion cae
                    $payload["intentos"] = (int) ($payload["intentos"] ?? 0) + 1;
                    if ($payload["intentos"] >= $maxIntentos) {
                        $channel->basic_publish($this->createMessage($payload), "", $queueName . self::SUFFIX_QUEUE_ERROR);
                        $result["error"]++;
                    } else {
                        $channel->basic_publish($this->createMessage($payload), "", $queueName);
                        $result["reencolados"]++;
                    }
                    $channel->basic_ack($message->getDeliveryTag());
                }

                if ($pauseSeconds > 0) {
                    sleep($pauseSeconds);
                }
            }

            //Cantidad que queda en la cola (declaracion pasiva: no la crea ni la modifica)
            $info                 = $channel->queue_declare($queueName, true);
            $result["pendientes"] = is_array($info) ? (int) $info[1] : 0;
        } finally {
            $this->close($channel, $connection);
            $this->releaseLock($lockHandle);
        }

        log_message("info", "[core_web_rabbitmq] consume - cola: " . $queueName . " | " . json_encode($result));
        return $result;
    }

    /**
     * Estado de una cola y de su cola de error, sin crearlas (declaracion pasiva).
     * pending = mensajes listos para enviar; no cuenta el que el consumidor tiene tomado en ese instante.
     * @param int    $companyID empresa
     * @param string $queueName cola
     * @return array {queue, exists, pending, consumers, errorPending}
     */
    function queueStatus($companyID, $queueName)
    {
        $connection = $this->getConnection($companyID);
        $channel    = null;
        $data       = [
            "queue"        => $queueName,
            "exists"       => false,
            "pending"      => 0,
            "consumers"    => 0,
            "errorPending" => 0
        ];

        try {
            $channel = $connection->channel();
            $info    = $this->queueInfo($channel, $queueName);
            if ($info !== null) {
                $data["exists"]    = true;
                $data["pending"]   = $info["messages"];
                $data["consumers"] = $info["consumers"];
            } else {
                //Si la cola no existe el broker cierra el canal: se abre otro para la cola de error
                $channel = $connection->channel();
            }
            $infoError = $this->queueInfo($channel, $queueName . self::SUFFIX_QUEUE_ERROR);
            if ($infoError !== null) {
                $data["errorPending"] = $infoError["messages"];
            }
        } finally {
            $this->close($channel, $connection);
        }
        return $data;
    }

    /**
     * Elimina todos los mensajes pendientes de una cola (la cola se conserva).
     * El mensaje que el consumidor tenga tomado en ese instante no se elimina.
     * @param int    $companyID empresa
     * @param string $queueName cola
     * @return int cantidad de mensajes eliminados
     */
    function purgeQueue($companyID, $queueName)
    {
        $connection = $this->getConnection($companyID);
        $channel    = null;
        $total      = 0;

        try {
            $channel = $connection->channel();
            if ($this->queueInfo($channel, $queueName) === null) {
                return 0;
            }
            $total = (int) $channel->queue_purge($queueName);
        } finally {
            $this->close($channel, $connection);
        }

        log_message("info", "[core_web_rabbitmq] purgeQueue - cola: " . $queueName . " | eliminados: " . $total);
        return $total;
    }

    /**
     * Mensajes y consumidores de una cola con declaracion pasiva (no la crea).
     * @param \PhpAmqpLib\Channel\AMQPChannel $channel canal abierto
     * @param string $queueName cola
     * @return array|null {messages, consumers} o null si la cola no existe (el canal queda cerrado)
     */
    function queueInfo($channel, $queueName)
    {
        try {
            $info = $channel->queue_declare($queueName, true);
        } catch (\PhpAmqpLib\Exception\AMQPProtocolChannelException $ex) {
            if ((int) $ex->getCode() == 404) {
                return null;
            }
            throw $ex;
        }
        return [
            "messages"  => is_array($info) ? (int) $info[1] : 0,
            "consumers" => is_array($info) ? (int) $info[2] : 0
        ];
    }

    /**
     * Declara una cola durable (no se borra al reiniciar el broker ni al cerrar la conexion).
     * @param \PhpAmqpLib\Channel\AMQPChannel $channel canal abierto
     * @param string $queueName nombre de la cola
     * @return void
     */
    function declareQueue($channel, $queueName)
    {
        $channel->queue_declare($queueName, false, true, false, false);
    }

    /**
     * Crea un mensaje persistente en JSON.
     * @param array $payload datos
     * @return AMQPMessage mensaje listo para publicar
     */
    function createMessage($payload)
    {
        return new AMQPMessage(
            json_encode($payload, JSON_UNESCAPED_UNICODE),
            [
                "content_type"  => "application/json",
                "delivery_mode" => AMQPMessage::DELIVERY_MODE_PERSISTENT
            ]
        );
    }

    /**
     * Cierra canal y conexion sin propagar errores de cierre.
     * @param mixed $channel    canal o null
     * @param mixed $connection conexion o null
     * @return void
     */
    function close($channel, $connection)
    {
        try {
            if ($channel !== null && $channel->is_open()) {
                $channel->close();
            }
        } catch (\Throwable $ex) {
            log_message("error", "[core_web_rabbitmq] close channel: " . $ex->getMessage());
        }

        try {
            if ($connection !== null && $connection->isConnected()) {
                $connection->close();
            }
        } catch (\Throwable $ex) {
            log_message("error", "[core_web_rabbitmq] close connection: " . $ex->getMessage());
        }
    }

    /**
     * Toma un candado exclusivo por cola en writable/rabbitmq para evitar consumidores simultaneos.
     * @param string $queueName cola
     * @return resource|false manejador del candado, o false si otra ejecucion lo tiene
     */
    function acquireLock($queueName)
    {
        $dir = WRITEPATH . "rabbitmq";
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        $file   = $dir . "/consume_" . preg_replace("/[^A-Za-z0-9_\-]/", "_", $queueName) . ".lock";
        $handle = @fopen($file, "c");
        if ($handle === false) {
            throw new \Exception("No se pudo crear el candado " . $file);
        }

        if (!flock($handle, LOCK_EX | LOCK_NB)) {
            fclose($handle);
            return false;
        }
        return $handle;
    }

    /**
     * Libera el candado tomado por acquireLock.
     * @param resource $handle manejador del candado
     * @return void
     */
    function releaseLock($handle)
    {
        if (is_resource($handle)) {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }
}
