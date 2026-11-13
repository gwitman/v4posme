<?php
//posme:2026-11-02
namespace App\Libraries;

use App\Models\Ai_Chat_Model;

/**
 * Herramientas (tools) que Claude puede usar desde el asistente "Jordania".
 *
 * Reglas de seguridad:
 *  - Toda lectura de datos se hace SOLO sobre las vistas ai_v_* y con @ai_company_id
 *    fijado desde la SESION (nunca desde el navegador ni desde lo que pida Claude).
 *  - Si hay usuario MySQL de solo lectura (constantes AI_READONLY_* de Constants.php) se usa esa conexion;
 *    sin el, la herramienta consulta_sql queda deshabilitada.
 *  - Cada herramienta exige el permiso de la pantalla equivalente del sistema (admin pasa siempre).
 *  - Todo lo ejecutado queda en tb_ai_auditoria_sql.
 */
class core_web_ai_tools
{
    /** Filas maximas devueltas a Claude por consulta. */
    const MAX_FILAS = 200;

    /** Segundos maximos de ejecucion por consulta. */
    const MAX_SEGUNDOS = 5;

    /**
     * Grupos de permiso => pantallas (address del menu) que lo otorgan.
     * El usuario tiene el grupo si tiene acceso a cualquiera de ellas.
     */
    public static $PERMISOS = [
        "general"    => [],
        "clientes"   => ["app_cxc_customer/index", "app_box_share/index", "app_cxc_report/index", "app_invoice_billing/index"],
        "ventas"     => ["app_invoice_billing/index", "app_sales_report/index", "app_invoice_report/index", "app_invoice_dashboards/index"],
        "inventario" => ["app_inventory_item/index", "app_inventory_report/index", "app_inventory_dashboards/index"],
        "cxc"        => ["app_cxc_report/index", "app_box_share/index", "app_cxc_customer/index", "app_cxc_document/index", "app_collection_report/index"],
        "cxp"        => ["app_cxp_provider/index", "app_cxp_report/index", "app_cxp_expenses/index", "app_inventory_inputunpost/index"],
        "caja"       => ["app_box_share/index", "app_box_report/index", "app_box_inputcash/index", "app_box_outcash/index", "app_box_closedcash/index"],
    ];

    /** Vista => grupo de permiso requerido para leerla. */
    public static $VISTAS = [
        "ai_v_monedas"          => "general",
        "ai_v_sucursales"       => "general",
        "ai_v_usuarios"         => "general",
        "ai_v_clientes"         => "clientes",
        "ai_v_productos"        => "inventario",
        "ai_v_inventario"       => "inventario",
        "ai_v_ventas"           => "ventas",
        "ai_v_ventas_detalle"   => "ventas",
        "ai_v_creditos"         => "cxc",
        "ai_v_cuotas"           => "cxc",
        "ai_v_abonos"           => "cxc",
        "ai_v_proveedores"      => "cxp",
        "ai_v_compras"          => "cxp",
        "ai_v_gastos"           => "cxp",
        "ai_v_caja_movimientos" => "caja",
    ];

    /** Herramienta => grupo de permiso requerido. */
    public static $HERRAMIENTAS = [
        "buscar_cliente"         => "clientes",
        "estado_cuenta_cliente"  => "cxc",
        "facturas_cliente"       => "ventas",
        "detalle_factura"        => "ventas",
        "buscar_producto"        => "inventario",
        "inventario"             => "inventario",
        "ventas_resumen"         => "ventas",
        "productos_mas_vendidos" => "ventas",
        "cuentas_por_cobrar"     => "cxc",
        "cuentas_por_pagar"      => "cxp",
        "caja_resumen"           => "caja",
        "buscar_en_manual"       => "general",
        "ruta_menu"              => "general",
        "consulta_sql"           => "general",
    ];

    private $dataSession = null;
    private $companyID   = 0;
    private $userID      = 0;
    private $db          = null;
    private $esReadonly  = false;
    private $Ai_Chat_Model;

    /**
     * Herramientas que puede usar el agente de IA de WhatsApp (clientes externos).
     * LISTA BLANCA: nunca se exponen consulta_sql, buscar_cliente, cuentas_por_cobrar ni ninguna otra.
     */
    const HERRAMIENTAS_EXTERNAS = ["mi_saldo", "verificar_identidad", "buscar_en_manual", "registrar_dato", "derivar_a_humano", "marcar_atendida_ia"];

    /** Intentos fallidos de verificacion de identidad antes de pasar a un humano. */
    const MAX_INTENTOS_IDENTIDAD = 2;

    /** Contexto del agente externo (WhatsApp): cliente fijado por el servidor y datos captados. */
    private $modoExterno         = false;
    private $entityIDCustomer    = 0;
    private $telefonoExterno     = "";
    private $nombreRegistrado    = "";
    private $aiData              = [];
    private $permitidasExternas  = [];

    /**
     * Fija el contexto de la sesion. Debe llamarse antes de usar cualquier herramienta.
     *
     * @param array $dataSession Sesion del usuario logueado.
     * @return void
     */
    function init($dataSession)
    {
        $this->dataSession   = $dataSession;
        $this->companyID     = (int) $dataSession["user"]->companyID;
        $this->userID        = (int) $dataSession["user"]->userID;
        $this->db            = null;
        $this->Ai_Chat_Model = new Ai_Chat_Model();
        $this->modoExterno   = false;
    }

    /* =================================================================================
       PERMISOS
    ================================================================================= */

    /**
     * Indica si el usuario tiene el grupo de permiso (por pantallas del menu en sesion).
     *
     * @param string $grupo Clave de self::$PERMISOS.
     * @return bool
     */
    function tienePermiso($grupo)
    {
        if ($grupo == "general") {
            return true;
        }
        if (!empty($this->dataSession["role"]->isAdmin)) {
            return true;
        }
        if (!isset(self::$PERMISOS[$grupo])) {
            return false;
        }

        $addresses = self::getAddressesSesion($this->dataSession);
        foreach (self::$PERMISOS[$grupo] as $address) {
            if (isset($addresses[strtoupper($address)])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Mismo criterio que app_inventory_report: el popup NO_ES_PERMITIDO_MOSTRAR_COSTOS oculta costos
     * (excepto al administrador).
     *
     * @return bool
     */
    function puedeVerCostos()
    {
        if (!empty($this->dataSession["role"]->isAdmin)) {
            return true;
        }
        $lista = $this->dataSession["menuHiddenPopup"] ?? [];
        if (is_array($lista)) {
            foreach ($lista as $m) {
                if (isset($m->display) && $m->display == "NO_ES_PERMITIDO_MOSTRAR_COSTOS") {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Direcciones (address) de todos los menus de la sesion, normalizadas en mayusculas y sin .aspx.
     *
     * @param array $dataSession Sesion.
     * @return array address => true
     */
    static function getAddressesSesion($dataSession)
    {
        $resultado = [];
        foreach (["menuTop", "menuLeft", "menuBodyReport", "menuBodyTop", "menuHiddenPopup", "menuLeftSnagit"] as $clave) {
            $lista = $dataSession[$clave] ?? null;
            if (!is_array($lista)) {
                continue;
            }
            foreach ($lista as $m) {
                if (!isset($m->address)) {
                    continue;
                }
                $address             = strtoupper(str_replace(".aspx", "", $m->address));
                $resultado[$address] = true;
            }
        }
        return $resultado;
    }

    /**
     * Vistas que el usuario puede leer con consulta_sql.
     *
     * @return array nombres de vista
     */
    function getVistasPermitidas()
    {
        $vistas = [];
        foreach (self::$VISTAS as $vista => $grupo) {
            if ($this->tienePermiso($grupo)) {
                $vistas[] = $vista;
            }
        }
        return $vistas;
    }

    /* =================================================================================
       DEFINICION DE HERRAMIENTAS PARA LA API
    ================================================================================= */

    /**
     * Definicion (nombre, descripcion, JSON schema) de las herramientas permitidas al usuario.
     *
     * @return array
     */
    function getDefinicionHerramientas()
    {
        $fecha  = ["type" => "string", "description" => "Fecha YYYY-MM-DD"];
        $limite = ["type" => "integer", "description" => "Cantidad maxima de filas (1-50)"];

        $todas = [
            [
                "name"         => "buscar_cliente",
                "description"  => "Busca clientes por nombre, apellido, nombre comercial, identificacion, codigo o telefono. Devuelve hasta 10 coincidencias con cliente_id. Usala antes de cualquier consulta sobre un cliente por nombre.",
                "input_schema" => ["type" => "object", "properties" => ["texto" => ["type" => "string", "description" => "Texto a buscar"]], "required" => ["texto"]],
            ],
            [
                "name"         => "estado_cuenta_cliente",
                "description"  => "Estado de cuenta de un cliente: saldo total por moneda, documentos de credito vigentes, cuotas pendientes y vencidas, y ultimos abonos.",
                "input_schema" => ["type" => "object", "properties" => ["cliente_id" => ["type" => "integer"]], "required" => ["cliente_id"]],
            ],
            [
                "name"         => "facturas_cliente",
                "description"  => "Ultimas facturas de venta de un cliente (mas reciente primero), con estado y total.",
                "input_schema" => ["type" => "object", "properties" => ["cliente_id" => ["type" => "integer"], "limite" => $limite], "required" => ["cliente_id"]],
            ],
            [
                "name"         => "detalle_factura",
                "description"  => "Encabezado y productos de una factura de venta por su numero (ej. FAC00001915).",
                "input_schema" => ["type" => "object", "properties" => ["numero" => ["type" => "string"]], "required" => ["numero"]],
            ],
            [
                "name"         => "buscar_producto",
                "description"  => "Busca productos por nombre, codigo o codigo de barras. Devuelve precio publico y existencia.",
                "input_schema" => ["type" => "object", "properties" => ["texto" => ["type" => "string"]], "required" => ["texto"]],
            ],
            [
                "name"         => "inventario",
                "description"  => "Consultas de inventario: productos bajo el minimo, con menor o mayor existencia, sin ventas en N dias, o existencias por bodega.",
                "input_schema" => [
                    "type"       => "object",
                    "properties" => [
                        "filtro" => ["type" => "string", "enum" => ["bajo_minimo", "menor_existencia", "mayor_existencia", "sin_movimiento", "por_bodega"]],
                        "texto"  => ["type" => "string", "description" => "Opcional: filtra por nombre de producto o bodega"],
                        "dias"   => ["type" => "integer", "description" => "Para sin_movimiento: dias sin ventas (defecto 90)"],
                        "limite" => $limite,
                    ],
                    "required"   => ["filtro"],
                ],
            ],
            [
                "name"         => "ventas_resumen",
                "description"  => "Totales de ventas validas (excluye anuladas y no aplicadas) entre dos fechas, agrupados. Siempre separa por moneda.",
                "input_schema" => [
                    "type"       => "object",
                    "properties" => [
                        "desde"       => $fecha,
                        "hasta"       => $fecha,
                        "agrupar_por" => ["type" => "string", "enum" => ["total", "dia", "semana", "mes", "sucursal", "usuario", "tipo_pago", "forma_pago"]],
                    ],
                    "required"   => ["desde", "hasta"],
                ],
            ],
            [
                "name"         => "productos_mas_vendidos",
                "description"  => "Ranking de productos vendidos (ventas validas) entre dos fechas, por cantidad o por monto. menos_vendidos=true invierte el orden.",
                "input_schema" => [
                    "type"       => "object",
                    "properties" => [
                        "desde"          => $fecha,
                        "hasta"          => $fecha,
                        "limite"         => $limite,
                        "orden"          => ["type" => "string", "enum" => ["cantidad", "monto"]],
                        "menos_vendidos" => ["type" => "boolean"],
                    ],
                    "required"   => ["desde", "hasta"],
                ],
            ],
            [
                "name"         => "cuentas_por_cobrar",
                "description"  => "Cartera de clientes: con_saldo (saldo por cliente), vencidas (clientes con cuotas vencidas y dias de mora), antiguedad (saldos por rango de mora).",
                "input_schema" => [
                    "type"       => "object",
                    "properties" => [
                        "filtro" => ["type" => "string", "enum" => ["con_saldo", "vencidas", "antiguedad"]],
                        "limite" => $limite,
                    ],
                    "required"   => ["filtro"],
                ],
            ],
            [
                "name"         => "cuentas_por_pagar",
                "description"  => "Proveedores y egresos: saldos (proveedores con saldo), compras (compras por proveedor entre fechas), gastos (gastos entre fechas).",
                "input_schema" => [
                    "type"       => "object",
                    "properties" => [
                        "filtro" => ["type" => "string", "enum" => ["saldos", "compras", "gastos"]],
                        "desde"  => $fecha,
                        "hasta"  => $fecha,
                        "limite" => $limite,
                    ],
                    "required"   => ["filtro"],
                ],
            ],
            [
                "name"         => "caja_resumen",
                "description"  => "Resumen de caja entre dos fechas: ventas de contado, abonos recibidos, ingresos y egresos de caja, por moneda.",
                "input_schema" => ["type" => "object", "properties" => ["desde" => $fecha, "hasta" => $fecha], "required" => ["desde", "hasta"]],
            ],
            [
                "name"         => "buscar_en_manual",
                "description"  => "Busca en el manual de uso de posMe los pasos para hacer algo en el sistema (facturar, anular, abonos, clientes, inventario, caja, reportes...).",
                "input_schema" => ["type" => "object", "properties" => ["texto" => ["type" => "string"]], "required" => ["texto"]],
            ],
            [
                "name"         => "ruta_menu",
                "description"  => "Busca en el menu REAL del usuario una opcion por nombre y devuelve su ruta (Modulo > Opcion). Usala para indicar donde esta una pantalla.",
                "input_schema" => ["type" => "object", "properties" => ["texto" => ["type" => "string"]], "required" => ["texto"]],
            ],
        ];

        if ($this->getConfigReadonly() !== null) {
            $todas[] = [
                "name"         => "consulta_sql",
                "description"  => "Ejecuta una consulta SELECT de solo lectura sobre las vistas ai_v_* descritas en el esquema, para preguntas que las otras herramientas no cubren. Una sola sentencia, sin comentarios ni variables. Maximo " . self::MAX_FILAS . " filas.",
                "input_schema" => [
                    "type"       => "object",
                    "properties" => [
                        "sql"         => ["type" => "string", "description" => "Sentencia SELECT o WITH"],
                        "explicacion" => ["type" => "string", "description" => "Para que sirve la consulta"],
                    ],
                    "required"   => ["sql", "explicacion"],
                ],
            ];
        }

        $permitidas = [];
        foreach ($todas as $herramienta) {
            if ($this->tienePermiso(self::$HERRAMIENTAS[$herramienta["name"]])) {
                $permitidas[] = $herramienta;
            }
        }
        return $permitidas;
    }

    /* =================================================================================
       EJECUCION
    ================================================================================= */

    /**
     * Ejecuta una herramienta pedida por Claude.
     *
     * @param string $nombre Nombre de la herramienta.
     * @param array  $input  Parametros enviados por Claude.
     * @return array ['ok' => bool, 'contenido' => string JSON o texto]
     */
    function ejecutar($nombre, $input)
    {
        $input = is_array($input) ? $input : [];

        // El agente externo (WhatsApp) nunca usa las herramientas internas de Jordania
        if ($this->modoExterno) {
            return ["ok" => false, "contenido" => "Herramienta no disponible: " . $nombre];
        }
        if (!isset(self::$HERRAMIENTAS[$nombre])) {
            return ["ok" => false, "contenido" => "Herramienta desconocida: " . $nombre];
        }
        if (!$this->tienePermiso(self::$HERRAMIENTAS[$nombre])) {
            return ["ok" => false, "contenido" => "sin_permiso: el usuario no tiene acceso a esta informacion en posMe. Indicaselo al usuario."];
        }

        try {
            switch ($nombre) {
                case "buscar_cliente":         $resultado = $this->buscarCliente($input); break;
                case "estado_cuenta_cliente":  $resultado = $this->estadoCuentaCliente($input); break;
                case "facturas_cliente":       $resultado = $this->facturasCliente($input); break;
                case "detalle_factura":        $resultado = $this->detalleFactura($input); break;
                case "buscar_producto":        $resultado = $this->buscarProducto($input); break;
                case "inventario":             $resultado = $this->inventario($input); break;
                case "ventas_resumen":         $resultado = $this->ventasResumen($input); break;
                case "productos_mas_vendidos": $resultado = $this->productosMasVendidos($input); break;
                case "cuentas_por_cobrar":     $resultado = $this->cuentasPorCobrar($input); break;
                case "cuentas_por_pagar":      $resultado = $this->cuentasPorPagar($input); break;
                case "caja_resumen":           $resultado = $this->cajaResumen($input); break;
                case "buscar_en_manual":       $resultado = $this->buscarEnManual($input); break;
                case "ruta_menu":              $resultado = $this->rutaMenu($input); break;
                case "consulta_sql":           $resultado = $this->consultaSql($input); break;
                default:                       $resultado = ["error" => "no implementada"];
            }
            return ["ok" => true, "contenido" => self::toJson($resultado)];
        } catch (\Throwable $ex) {
            log_message("error", "core_web_ai_tools " . $nombre . ": " . $ex->getMessage());
            return ["ok" => false, "contenido" => "Error al ejecutar " . $nombre . ": " . self::mensajeErrorSeguro($ex->getMessage())];
        }
    }

    /* =================================================================================
       AGENTE EXTERNO (WhatsApp)
       El interlocutor es un cliente, no un usuario de posMe: no hay sesion, el companyID
       lo fija el webhook y el cliente lo fija el servidor (nunca lo elige Claude).
    ================================================================================= */

    /**
     * Fija el contexto del agente de WhatsApp. Debe llamarse antes de usar las herramientas externas.
     *
     * @param int    $companyID        Empresa (APP_COMPANY del webhook).
     * @param int    $entityIDCustomer entityID del cliente de la conversacion.
     * @param string $telefono         Telefono de WhatsApp del cliente.
     * @param array  $aiData           Datos ya captados (tb_customer_conversation.aiData).
     * @param string $nombreRegistrado Nombre del contacto si ya esta registrado en el sistema; "" si es un numero nuevo.
     * @return void
     */
    function initExterno($companyID, $entityIDCustomer, $telefono, $aiData, $nombreRegistrado = "")
    {
        $this->nombreRegistrado = trim((string) $nombreRegistrado);
        $this->dataSession      = null;
        $this->companyID        = (int) $companyID;
        $this->userID           = 0;
        $this->db               = null;
        $this->Ai_Chat_Model    = new Ai_Chat_Model();
        $this->modoExterno      = true;
        $this->entityIDCustomer = (int) $entityIDCustomer;
        $this->telefonoExterno  = (string) $telefono;
        $this->aiData           = is_array($aiData) ? $aiData : [];
        $this->permitidasExternas = [];
    }

    /**
     * Datos captados y acciones pedidas por el agente (intent, datos, verificado, accion, motivo, resumen...).
     *
     * @return array
     */
    function getEstadoExterno()
    {
        return $this->aiData;
    }

    /**
     * Definicion de las herramientas externas permitidas.
     * Siempre: registrar_dato. Si $puedeCerrar: derivar_a_humano y marcar_atendida_ia.
     * Ademas las que pidan las skills, siempre dentro de la lista blanca.
     *
     * @param array $toolsSkill  Nombres de herramientas que declaran las skills (tb_ai_skill.tools).
     * @param bool  $puedeCerrar false cuando la conversacion ya fue derivada (la IA solo responde).
     * @return array
     */
    function getDefinicionHerramientasExterno($toolsSkill, $puedeCerrar = true)
    {
        $intencion = ["type" => "string", "description" => "Codigo de la intencion/objetivo detectado (ej. saldo_cxc, soporte, prospecto)"];

        $todas = [
            "mi_saldo" => [
                "name"         => "mi_saldo",
                "description"  => "Saldo y cuotas pendientes del cliente que escribe. No recibe parametros: el cliente lo fija el sistema. Si responde requiere_verificacion, pide cedula o codigo de cliente y nombre completo y usa verificar_identidad.",
                "input_schema" => ["type" => "object", "properties" => new \stdClass()],
            ],
            "verificar_identidad" => [
                "name"         => "verificar_identidad",
                "description"  => $this->nombreRegistrado !== ""
                    ? "Verifica la identidad del cliente registrado con su cedula (identificacion) o codigo de cliente. Ya se conoce su nombre: no se lo pidas. Solo hay " . self::MAX_INTENTOS_IDENTIDAD . " intentos."
                    : "Verifica la identidad del cliente con su cedula (identificacion) o codigo de cliente Y su nombre completo exacto. Solo hay " . self::MAX_INTENTOS_IDENTIDAD . " intentos.",
                "input_schema" => [
                    "type"       => "object",
                    "properties" => [
                        "identificacion_o_codigo" => ["type" => "string", "description" => "Cedula o codigo de cliente que escribio el cliente"],
                        "nombre_completo"         => ["type" => "string", "description" => "Nombre completo que escribio el cliente" . ($this->nombreRegistrado !== "" ? " (opcional: el cliente ya esta registrado)" : "")],
                    ],
                    // Contacto registrado: basta la cedula/codigo, el nombre ya se conoce
                    "required"   => $this->nombreRegistrado !== "" ? ["identificacion_o_codigo"] : ["identificacion_o_codigo", "nombre_completo"],
                ],
            ],
            "buscar_en_manual" => [
                "name"         => "buscar_en_manual",
                "description"  => "Busca en el manual de usuario de posMe. Usala cuando el cliente pregunte como se usa algo del sistema o tenga un error que pueda ser por desconocimiento (ej. no sabe anular, no encuentra un reporte, un mensaje de validacion). Explica los pasos con tus palabras, sin copiar el manual completo. Si no hay respuesta, no inventes.",
                "input_schema" => ["type" => "object", "properties" => ["texto" => ["type" => "string", "description" => "Palabras clave de la duda o del error (ej. 'anular factura', 'abono credito', 'cierre de caja')"]], "required" => ["texto"]],
            ],
            "registrar_dato" => [
                "name"         => "registrar_dato",
                "description"  => "Guarda un dato que dio el cliente (ej. nombre, empresa, problema, modulo, telefono, correo, interes). Usala cada vez que el cliente te de un dato requerido.",
                "input_schema" => [
                    "type"       => "object",
                    "properties" => [
                        "campo"     => ["type" => "string", "description" => "Nombre corto del dato en minusculas (ej. nombre, empresa, problema)"],
                        "valor"     => ["type" => "string"],
                        "intencion" => $intencion,
                    ],
                    "required"   => ["campo", "valor"],
                ],
            ],
            "derivar_a_humano" => [
                "name"         => "derivar_a_humano",
                "description"  => "Pasa la conversacion a un asesor humano. Usala cuando ya tengas los datos requeridos de un objetivo que se deriva, cuando el cliente pida hablar con una persona, o cuando no puedas ayudar.",
                "input_schema" => [
                    "type"       => "object",
                    "properties" => [
                        "motivo"    => ["type" => "string", "description" => "Por que se deriva"],
                        "resumen"   => ["type" => "string", "description" => "Resumen breve del caso para el asesor"],
                        "intencion" => $intencion,
                    ],
                    "required"   => ["motivo", "resumen"],
                ],
            ],
            "marcar_atendida_ia" => [
                "name"         => "marcar_atendida_ia",
                "description"  => "Marca la conversacion como resuelta por la IA. Usala solo cuando el objetivo se resolvio por completo (ej. ya le diste su saldo) y el cliente no necesita nada mas.",
                "input_schema" => [
                    "type"       => "object",
                    "properties" => [
                        "resumen"   => ["type" => "string", "description" => "Resumen breve de lo atendido"],
                        "intencion" => $intencion,
                    ],
                    "required"   => ["resumen"],
                ],
            ],
        ];

        $nombres = ["registrar_dato"];
        if ($puedeCerrar) {
            $nombres[] = "derivar_a_humano";
            $nombres[] = "marcar_atendida_ia";
        }
        foreach ((array) $toolsSkill as $nombre) {
            $nombre = (string) $nombre;
            // En una conversacion derivada las herramientas de cierre no se ofrecen aunque la skill las declare
            if (!$puedeCerrar && in_array($nombre, ["derivar_a_humano", "marcar_atendida_ia"], true)) {
                continue;
            }
            if (in_array($nombre, self::HERRAMIENTAS_EXTERNAS, true) && !in_array($nombre, $nombres, true)) {
                $nombres[] = $nombre;
            }
        }
        // verificar_identidad solo tiene sentido junto con mi_saldo
        if (in_array("mi_saldo", $nombres, true) && !in_array("verificar_identidad", $nombres, true)) {
            $nombres[] = "verificar_identidad";
        }

        $definicion = [];
        foreach ($nombres as $nombre) {
            $definicion[] = $todas[$nombre];
        }
        $this->permitidasExternas = $nombres;
        return $definicion;
    }

    /**
     * Ejecuta una herramienta externa pedida por Claude. Solo las de la lista blanca y las entregadas en la definicion.
     *
     * @param string $nombre Nombre de la herramienta.
     * @param array  $input  Parametros enviados por Claude.
     * @return array ['ok' => bool, 'contenido' => string]
     */
    function ejecutarExterno($nombre, $input)
    {
        $input = is_array($input) ? $input : [];
        if (!$this->modoExterno
            || !in_array($nombre, self::HERRAMIENTAS_EXTERNAS, true)
            || !in_array($nombre, $this->permitidasExternas, true)) {
            return ["ok" => false, "contenido" => "Herramienta no disponible: " . $nombre];
        }

        try {
            switch ($nombre) {
                case "mi_saldo":            $resultado = $this->miSaldo(); break;
                case "verificar_identidad": $resultado = $this->verificarIdentidad($input); break;
                case "buscar_en_manual":    $resultado = $this->buscarEnManual($input); break;
                case "registrar_dato":      $resultado = $this->registrarDato($input); break;
                case "derivar_a_humano":    $resultado = $this->marcarAccion("derivar", $input); break;
                case "marcar_atendida_ia":  $resultado = $this->marcarAccion("atendida", $input); break;
                default:                    $resultado = ["error" => "no implementada"];
            }
            return ["ok" => true, "contenido" => self::toJson($resultado)];
        } catch (\Throwable $ex) {
            log_message("error", "core_web_ai_tools externo " . $nombre . ": " . $ex->getMessage());
            return ["ok" => false, "contenido" => "Error al ejecutar " . $nombre . ": " . self::mensajeErrorSeguro($ex->getMessage())];
        }
    }

    /**
     * Ultimos 8 digitos del telefono (Nicaragua); sirve para comparar con o sin codigo de pais.
     *
     * @param string $telefono
     * @return string
     */
    static function digitosTelefono($telefono)
    {
        $digitos = preg_replace('/\D/', '', (string) $telefono);
        return strlen($digitos) > 8 ? substr($digitos, -8) : $digitos;
    }

    /**
     * Cliente fijado para consultar saldo: el ya verificado o, si solo un cliente tiene ese telefono, ese.
     *
     * @return int customerID (cliente_id de ai_v_clientes) o 0
     */
    private function clienteVerificado()
    {
        if (!empty($this->aiData["verificado"])) {
            return (int) $this->aiData["verificado"];
        }

        $digitos = self::digitosTelefono($this->telefonoExterno);
        if (strlen($digitos) < 7) {
            return 0;
        }

        $db    = $this->getConexion();
        $filas = $this->consultar("
            SELECT cliente_id
            FROM ai_v_clientes
            WHERE REPLACE(REPLACE(REPLACE(REPLACE(IFNULL(telefono, ''), ' ', ''), '-', ''), '+', ''), '.', '') LIKE " . $db->escape("%" . $digitos) . "
            LIMIT 2", "mi_saldo_telefono", ["telefono" => $digitos]);

        // Regla: un solo cliente con ese telefono => se le da su saldo directo
        if (count($filas) == 1) {
            $this->aiData["verificado"] = (int) $filas[0]["cliente_id"];
            return (int) $filas[0]["cliente_id"];
        }
        return 0;
    }

    /**
     * Saldo del cliente que escribe. Nunca recibe un cliente: lo fija el servidor.
     *
     * @return array
     */
    private function miSaldo()
    {
        $clienteID = $this->clienteVerificado();
        if ($clienteID <= 0) {
            return [
                "requiere_verificacion" => true,
                "instruccion"           => $this->nombreRegistrado !== ""
                    ? "Pide al cliente solo su numero de cedula o su codigo de cliente (ya conoces su nombre, no se lo pidas); luego usa verificar_identidad."
                    : "Pide al cliente su numero de cedula o su codigo de cliente y su nombre completo; luego usa verificar_identidad.",
            ];
        }

        $estado = $this->estadoCuentaCliente(["cliente_id" => $clienteID]);
        // Solo datos utiles para el cliente: sin identificadores internos
        unset($estado["cliente"]["cliente_id"]);
        return $estado;
    }

    /**
     * Verifica cedula/codigo + nombre exacto. Al segundo fallo pide derivar a un humano.
     *
     * @param array $input identificacion_o_codigo, nombre_completo
     * @return array
     */
    private function verificarIdentidad($input)
    {
        $intentos = (int) ($this->aiData["intentosIdentidad"] ?? 0);
        if ($intentos >= self::MAX_INTENTOS_IDENTIDAD) {
            return ["verificado" => false, "instruccion" => "Ya no quedan intentos. Usa derivar_a_humano con motivo 'identidad no verificada'."];
        }

        $registrado = $this->nombreRegistrado !== "";
        $documento  = trim((string) ($input["identificacion_o_codigo"] ?? ""));
        $nombre     = trim((string) ($input["nombre_completo"] ?? ""));
        if ($documento === "" || ($nombre === "" && !$registrado)) {
            return ["verificado" => false, "instruccion" => $registrado ? "Falta la cedula o el codigo de cliente." : "Faltan datos: pide cedula o codigo de cliente y nombre completo."];
        }

        $db    = $this->getConexion();
        $filas = $this->consultar("
            SELECT cliente_id, entidad_id, nombre
            FROM ai_v_clientes
            WHERE identificacion = " . $db->escape($documento) . "
                OR codigo = " . $db->escape($documento) . "
            LIMIT 5", "verificar_identidad", ["documento" => $documento]);

        $esperado = preg_replace('/\s+/', ' ', self::normalizar($nombre));
        foreach ($filas as $fila) {
            // Contacto registrado: basta que la cedula/codigo sea del mismo cliente que escribe (ya identificado por su telefono).
            // Numero nuevo: la cedula/codigo y el nombre completo deben coincidir.
            $esElMismo      = $registrado && (int) $fila["entidad_id"] == $this->entityIDCustomer;
            $nombreCoincide = $nombre !== "" && preg_replace('/\s+/', ' ', self::normalizar(trim((string) $fila["nombre"]))) === $esperado;
            if ($esElMismo || $nombreCoincide) {
                $this->aiData["verificado"]        = (int) $fila["cliente_id"];
                $this->aiData["intentosIdentidad"] = 0;
                return ["verificado" => true, "instruccion" => "Identidad verificada. Ya puedes usar mi_saldo."];
            }
        }

        $intentos++;
        $this->aiData["intentosIdentidad"] = $intentos;
        if ($intentos >= self::MAX_INTENTOS_IDENTIDAD) {
            return ["verificado" => false, "instruccion" => "No coincide y no quedan intentos. Usa derivar_a_humano con motivo 'identidad no verificada'. No des pistas de que dato fallo."];
        }
        return ["verificado" => false, "instruccion" => "No coincide. Pide amablemente que revise los datos (queda 1 intento). No des pistas de que dato fallo."];
    }

    /**
     * Guarda un dato captado del cliente en aiData.datos.
     *
     * @param array $input campo, valor, intencion
     * @return array
     */
    private function registrarDato($input)
    {
        // Se quitan tildes antes de normalizar para que "teléfono" y "telefono" sean la misma clave
        $campo = strtr(mb_strtolower(trim((string) ($input["campo"] ?? "")), "UTF-8"), ["á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u", "ü" => "u", "ñ" => "n"]);
        $campo = preg_replace('/[^a-z0-9_]/', '_', $campo);
        $campo = substr(trim($campo, "_"), 0, 40);
        $valor = mb_substr(trim((string) ($input["valor"] ?? "")), 0, 500);
        if ($campo === "" || $valor === "") {
            return ["error" => "campo y valor son obligatorios"];
        }
        $this->setIntencion($input);
        $this->aiData["datos"]         = isset($this->aiData["datos"]) && is_array($this->aiData["datos"]) ? $this->aiData["datos"] : [];
        $this->aiData["datos"][$campo] = $valor;
        return ["guardado" => true, "datos" => $this->aiData["datos"]];
    }

    /**
     * Registra la accion de cierre pedida por el agente (derivar | atendida). La aplica el webhook al terminar.
     *
     * @param string $accion derivar | atendida
     * @param array  $input  motivo, resumen, intencion
     * @return array
     */
    private function marcarAccion($accion, $input)
    {
        $this->setIntencion($input);
        $this->aiData["accion"]  = $accion;
        $this->aiData["motivo"]  = mb_substr(trim((string) ($input["motivo"] ?? "")), 0, 300);
        $this->aiData["resumen"] = mb_substr(trim((string) ($input["resumen"] ?? "")), 0, 1000);
        return [
            "registrado"  => true,
            "instruccion" => $accion == "derivar"
                ? "Listo. No escribas nada mas; el sistema le avisa al cliente."
                : "Listo. Despidete en una linea, sin preguntas.",
        ];
    }

    /** Fija aiData.intent si viene una intencion valida (letras, numeros y guion bajo). */
    private function setIntencion($input)
    {
        $intencion = strtolower(trim((string) ($input["intencion"] ?? "")));
        if ($intencion !== "" && preg_match('/^[a-z0-9_]{2,40}$/', $intencion)) {
            $this->aiData["intent"] = $intencion;
        }
    }

    /* ---------------------------------------------------------------------------------
       Herramientas especificas (SQL fijo sobre vistas, parametros escapados)
    --------------------------------------------------------------------------------- */

    private function buscarCliente($input)
    {
        $texto = trim((string) ($input["texto"] ?? ""));
        if (mb_strlen($texto) < 2) {
            return ["error" => "Escribe al menos 2 caracteres"];
        }

        $db       = $this->getConexion();
        $completo = $this->like($db, $texto);

        // Todas las palabras deben aparecer en el nombre (permite "Juan Perez" o "Perez Juan")
        $porPalabras = [];
        foreach (preg_split('/\s+/', $texto) as $palabra) {
            if ($palabra !== "") {
                $porPalabras[] = "nombre LIKE " . $this->like($db, $palabra);
            }
        }

        $sql = "
            SELECT
                cliente_id,
                codigo,
                nombre,
                nombre_comercial,
                identificacion,
                telefono,
                moneda
            FROM ai_v_clientes
            WHERE (" . implode(" AND ", $porPalabras) . ")
                OR nombre_comercial LIKE " . $completo . "
                OR identificacion LIKE " . $completo . "
                OR codigo LIKE " . $completo . "
                OR telefono LIKE " . $completo . "
            ORDER BY
                nombre
            LIMIT 10";

        $filas = $this->consultar($sql, "buscar_cliente", $input);
        return ["coincidencias" => count($filas), "clientes" => $filas];
    }

    private function estadoCuentaCliente($input)
    {
        $clienteID = (int) ($input["cliente_id"] ?? 0);
        if ($clienteID <= 0) {
            return ["error" => "cliente_id invalido; usa buscar_cliente primero"];
        }

        $cliente = $this->consultar(sprintf("
            SELECT cliente_id, codigo, nombre, identificacion, telefono, moneda
            FROM ai_v_clientes
            WHERE cliente_id = %d
            LIMIT 1", $clienteID), "estado_cuenta_cliente", $input);
        if (count($cliente) == 0) {
            return ["error" => "No existe el cliente"];
        }

        $saldos = $this->consultar(sprintf("
            SELECT moneda, COUNT(*) AS documentos, SUM(saldo) AS saldo_total
            FROM ai_v_creditos
            WHERE cliente_id = %d
                AND vigente = 1
            GROUP BY moneda
            LIMIT 10", $clienteID), "estado_cuenta_cliente", null);

        $documentos = $this->consultar(sprintf("
            SELECT documento_id, numero_documento, fecha, monto, saldo, moneda, plazo, estado
            FROM ai_v_creditos
            WHERE cliente_id = %d
                AND vigente = 1
            ORDER BY fecha DESC
            LIMIT 20", $clienteID), "estado_cuenta_cliente", null);

        $vencido = $this->consultar(sprintf("
            SELECT moneda_id, COUNT(*) AS cuotas_vencidas, SUM(saldo_pendiente) AS monto_vencido, MAX(dias_mora) AS max_dias_mora
            FROM ai_v_cuotas
            WHERE cliente_id = %d
                AND vencida = 1
                AND documento_vigente = 1
            GROUP BY moneda_id
            LIMIT 10", $clienteID), "estado_cuenta_cliente", null);

        $cuotas = $this->consultar(sprintf("
            SELECT numero_documento, numero_cuota, fecha_vencimiento, cuota, saldo_pendiente, vencida, dias_mora, moneda_id
            FROM ai_v_cuotas
            WHERE cliente_id = %d
                AND saldo_pendiente > 0
                AND documento_vigente = 1
                AND estado <> 'SANEADO'
            ORDER BY fecha_vencimiento
            LIMIT 30", $clienteID), "estado_cuenta_cliente", null);

        $abonos = $this->consultar(sprintf("
            SELECT numero, tipo, fecha, numero_documento, monto, moneda_id, usuario
            FROM ai_v_abonos
            WHERE cliente_id = %d
                AND es_valido = 1
            ORDER BY fecha DESC
            LIMIT 5", $clienteID), "estado_cuenta_cliente", null);

        return [
            "cliente"                 => $cliente[0],
            "saldo_por_moneda"        => $saldos,
            "vencido_por_moneda"      => $vencido,
            "documentos_vigentes"     => $documentos,
            "cuotas_pendientes"       => $cuotas,
            "ultimos_abonos"          => $abonos,
            "nota"                    => "moneda_id 1 = Cordoba, 2 = Dolar",
        ];
    }

    private function facturasCliente($input)
    {
        $clienteID = (int) ($input["cliente_id"] ?? 0);
        $limite    = $this->limite($input, 10);
        if ($clienteID <= 0) {
            return ["error" => "cliente_id invalido; usa buscar_cliente primero"];
        }

        $filas = $this->consultar(sprintf("
            SELECT numero, fecha, tipo_pago, moneda, total, estado, es_valida, sucursal, usuario
            FROM ai_v_ventas
            WHERE cliente_id = %d
            ORDER BY fecha DESC, factura_id DESC
            LIMIT %d", $clienteID, $limite), "facturas_cliente", $input);

        return ["facturas" => $filas, "nota" => "es_valida = 0 significa anulada o no aplicada"];
    }

    private function detalleFactura($input)
    {
        $numero = trim((string) ($input["numero"] ?? ""));
        if ($numero === "") {
            return ["error" => "Falta el numero de factura"];
        }

        $db         = $this->getConexion();
        $encabezado = $this->consultar("
            SELECT factura_id, numero, fecha, cliente_nombre, cliente_codigo, tipo_pago, moneda, subtotal, descuento, impuesto, total, estado, es_valida, sucursal, usuario, nota
            FROM ai_v_ventas
            WHERE numero = " . $db->escape($numero) . "
            LIMIT 1", "detalle_factura", $input);
        if (count($encabezado) == 0) {
            return ["error" => "No existe la factura " . $numero];
        }

        $columnas = $this->puedeVerCostos() ? ", costo, utilidad" : "";
        $lineas   = $this->consultar(sprintf("
            SELECT producto_codigo, producto, cantidad, precio_unitario, descuento, impuesto, total" . $columnas . "
            FROM ai_v_ventas_detalle
            WHERE factura_id = %d
            LIMIT 100", (int) $encabezado[0]["factura_id"]), "detalle_factura", null);

        return ["factura" => $encabezado[0], "productos" => $lineas];
    }

    private function buscarProducto($input)
    {
        $texto = trim((string) ($input["texto"] ?? ""));
        if (mb_strlen($texto) < 2) {
            return ["error" => "Escribe al menos 2 caracteres"];
        }

        $db          = $this->getConexion();
        $completo    = $this->like($db, $texto);
        $porPalabras = [];
        foreach (preg_split('/\s+/', $texto) as $palabra) {
            if ($palabra !== "") {
                $porPalabras[] = "nombre LIKE " . $this->like($db, $palabra);
            }
        }
        $columnas = $this->puedeVerCostos() ? ", costo" : "";

        $sql = "
            SELECT
                producto_id,
                codigo,
                nombre,
                categoria,
                existencia_total,
                existencia_minima,
                precio_publico,
                es_servicio" . $columnas . "
            FROM ai_v_productos
            WHERE (" . implode(" AND ", $porPalabras) . ")
                OR codigo LIKE " . $completo . "
                OR codigo_barras LIKE " . $completo . "
            ORDER BY
                nombre
            LIMIT 10";

        $filas = $this->consultar($sql, "buscar_producto", $input);
        return ["coincidencias" => count($filas), "productos" => $filas];
    }

    private function inventario($input)
    {
        $filtro   = (string) ($input["filtro"] ?? "bajo_minimo");
        $limite   = $this->limite($input, 20);
        $dias     = max(1, min(3650, (int) ($input["dias"] ?? 90)));
        $texto    = trim((string) ($input["texto"] ?? ""));
        $db       = $this->getConexion();
        $columnas = "producto_id, codigo, nombre, categoria, existencia_total, existencia_minima, precio_publico";
        $filtroTx = $texto !== "" ? " AND nombre LIKE " . $this->like($db, $texto) : "";

        switch ($filtro) {
            case "menor_existencia":
                $sql = "SELECT " . $columnas . " FROM ai_v_productos WHERE es_servicio = 0" . $filtroTx . " ORDER BY existencia_total ASC LIMIT " . $limite;
                break;
            case "mayor_existencia":
                $sql = "SELECT " . $columnas . " FROM ai_v_productos WHERE es_servicio = 0" . $filtroTx . " ORDER BY existencia_total DESC LIMIT " . $limite;
                break;
            case "sin_movimiento":
                $sql = sprintf("
                    SELECT " . $columnas . ", fecha_ultimo_movimiento
                    FROM ai_v_productos
                    WHERE es_servicio = 0
                        AND existencia_total > 0" . $filtroTx . "
                        AND producto_id NOT IN (
                            SELECT DISTINCT producto_id
                            FROM ai_v_ventas_detalle
                            WHERE es_valida = 1
                                AND dia >= CURDATE() - INTERVAL %d DAY
                        )
                    ORDER BY existencia_total DESC
                    LIMIT %d", $dias, $limite);
                break;
            case "por_bodega":
                $filtroBodega = $texto !== "" ? " WHERE producto LIKE " . $this->like($db, $texto) . " OR bodega LIKE " . $this->like($db, $texto) : "";
                $sql          = "SELECT producto_id, codigo, producto, bodega, existencia, existencia_minima FROM ai_v_inventario" . $filtroBodega . " ORDER BY producto, bodega LIMIT " . $limite;
                break;
            default:
                $sql = "SELECT " . $columnas . " FROM ai_v_productos WHERE es_servicio = 0 AND existencia_total < existencia_minima" . $filtroTx . " ORDER BY (existencia_total - existencia_minima) ASC LIMIT " . $limite;
        }

        $filas = $this->consultar($sql, "inventario", $input);
        return ["filtro" => $filtro, "productos" => $filas];
    }

    private function ventasResumen($input)
    {
        list($desde, $hasta) = $this->rangoFechas($input);
        $agrupar             = (string) ($input["agrupar_por"] ?? "total");
        $rango               = sprintf("dia BETWEEN '%s' AND '%s'", $desde, $hasta);

        $grupos = [
            "total"     => "'TOTAL'",
            "dia"       => "dia",
            "semana"    => "CONCAT(YEAR(dia), '-S', LPAD(WEEK(dia, 1), 2, '0'))",
            "mes"       => "DATE_FORMAT(dia, '%Y-%m')",
            "sucursal"  => "sucursal",
            "usuario"   => "usuario",
            "tipo_pago" => "tipo_pago",
        ];

        if ($agrupar == "forma_pago") {
            $sql = "
                SELECT
                    moneda,
                    COUNT(*) AS facturas,
                    SUM(total) AS total_facturado,
                    SUM(recibido_efectivo) AS efectivo,
                    SUM(recibido_efectivo_dolares) AS efectivo_dolares,
                    SUM(recibido_tarjeta) AS tarjeta,
                    SUM(recibido_tarjeta_dolares) AS tarjeta_dolares,
                    SUM(recibido_transferencia) AS transferencia,
                    SUM(recibido_transferencia_dolares) AS transferencia_dolares,
                    SUM(cambio) AS cambio_entregado
                FROM ai_v_ventas
                WHERE es_valida = 1
                    AND " . $rango . "
                GROUP BY moneda
                LIMIT 20";
        } else {
            $expresion = $grupos[$agrupar] ?? "'TOTAL'";
            $sql       = "
                SELECT
                    " . $expresion . " AS grupo,
                    moneda,
                    COUNT(*) AS facturas,
                    SUM(subtotal) AS subtotal,
                    SUM(descuento) AS descuento,
                    SUM(impuesto) AS impuesto,
                    SUM(total) AS total
                FROM ai_v_ventas
                WHERE es_valida = 1
                    AND " . $rango . "
                GROUP BY grupo, moneda
                ORDER BY grupo
                LIMIT " . self::MAX_FILAS;
        }

        $filas     = $this->consultar($sql, "ventas_resumen", $input);
        $anuladas  = $this->consultar("SELECT COUNT(*) AS anuladas FROM ai_v_ventas WHERE estado = 'ANULADA' AND " . $rango, "ventas_resumen", null);

        return [
            "desde"              => $desde,
            "hasta"              => $hasta,
            "agrupar_por"        => $agrupar,
            "resultado"          => $filas,
            "facturas_anuladas"  => $anuladas[0]["anuladas"] ?? 0,
            "nota"               => "Solo facturas aplicadas; se excluyen anuladas y registradas sin aplicar",
        ];
    }

    private function productosMasVendidos($input)
    {
        list($desde, $hasta) = $this->rangoFechas($input);
        $limite              = $this->limite($input, 10);
        $orden               = ($input["orden"] ?? "cantidad") == "monto" ? "monto" : "cantidad";
        $direccion           = !empty($input["menos_vendidos"]) ? "ASC" : "DESC";

        $sql = sprintf("
            SELECT
                producto_id,
                producto_codigo,
                MAX(producto) AS producto,
                SUM(cantidad) AS cantidad,
                SUM(subtotal) AS monto,
                COUNT(DISTINCT factura_id) AS facturas
            FROM ai_v_ventas_detalle
            WHERE es_valida = 1
                AND dia BETWEEN '%s' AND '%s'
            GROUP BY producto_id, producto_codigo
            ORDER BY %s %s
            LIMIT %d", $desde, $hasta, $orden, $direccion, $limite);

        $filas = $this->consultar($sql, "productos_mas_vendidos", $input);
        return ["desde" => $desde, "hasta" => $hasta, "orden" => $orden, "productos" => $filas, "nota" => "monto = cantidad x precio, sin impuesto, en la moneda de cada factura"];
    }

    private function cuentasPorCobrar($input)
    {
        $filtro = (string) ($input["filtro"] ?? "con_saldo");
        $limite = $this->limite($input, 20);

        if ($filtro == "vencidas") {
            $sql = "
                SELECT
                    cliente_id,
                    MAX(cliente_nombre) AS cliente,
                    moneda_id,
                    COUNT(*) AS cuotas_vencidas,
                    SUM(saldo_pendiente) AS monto_vencido,
                    MAX(dias_mora) AS max_dias_mora
                FROM ai_v_cuotas
                WHERE vencida = 1
                    AND documento_vigente = 1
                GROUP BY cliente_id, moneda_id
                ORDER BY monto_vencido DESC
                LIMIT " . $limite;
        } else if ($filtro == "antiguedad") {
            $sql = "
                SELECT
                    moneda_id,
                    SUM(IF(vencida = 0, saldo_pendiente, 0)) AS por_vencer,
                    SUM(IF(vencida = 1 AND dias_mora BETWEEN 1 AND 30, saldo_pendiente, 0)) AS mora_1_30,
                    SUM(IF(vencida = 1 AND dias_mora BETWEEN 31 AND 60, saldo_pendiente, 0)) AS mora_31_60,
                    SUM(IF(vencida = 1 AND dias_mora BETWEEN 61 AND 90, saldo_pendiente, 0)) AS mora_61_90,
                    SUM(IF(vencida = 1 AND dias_mora > 90, saldo_pendiente, 0)) AS mora_mas_90,
                    SUM(saldo_pendiente) AS total
                FROM ai_v_cuotas
                WHERE saldo_pendiente > 0
                    AND documento_vigente = 1
                    AND estado <> 'SANEADO'
                GROUP BY moneda_id
                LIMIT 10";
        } else {
            $filtro = "con_saldo";
            $sql    = "
                SELECT
                    cliente_id,
                    MAX(cliente_nombre) AS cliente,
                    moneda,
                    COUNT(*) AS documentos,
                    SUM(saldo) AS saldo
                FROM ai_v_creditos
                WHERE vigente = 1
                    AND saldo > 0
                GROUP BY cliente_id, moneda
                ORDER BY saldo DESC
                LIMIT " . $limite;
        }

        $filas = $this->consultar($sql, "cuentas_por_cobrar", $input);
        return ["filtro" => $filtro, "resultado" => $filas, "nota" => "moneda_id 1 = Cordoba, 2 = Dolar"];
    }

    private function cuentasPorPagar($input)
    {
        $filtro = (string) ($input["filtro"] ?? "saldos");
        $limite = $this->limite($input, 20);

        if ($filtro == "compras") {
            list($desde, $hasta) = $this->rangoFechas($input);
            $sql = sprintf("
                SELECT
                    proveedor_id,
                    MAX(proveedor) AS proveedor,
                    moneda_id,
                    COUNT(DISTINCT compra_id) AS compras,
                    SUM(costo_total) AS monto
                FROM ai_v_compras
                WHERE es_valida = 1
                    AND dia BETWEEN '%s' AND '%s'
                GROUP BY proveedor_id, moneda_id
                ORDER BY monto DESC
                LIMIT %d", $desde, $hasta, $limite);
        } else if ($filtro == "gastos") {
            list($desde, $hasta) = $this->rangoFechas($input);
            $sql = sprintf("
                SELECT numero, fecha, concepto, monto, moneda_id, usuario
                FROM ai_v_gastos
                WHERE es_valido = 1
                    AND dia BETWEEN '%s' AND '%s'
                ORDER BY fecha DESC
                LIMIT %d", $desde, $hasta, $limite);
        } else {
            $filtro = "saldos";
            $sql    = "
                SELECT proveedor_id, codigo, nombre, saldo_cordobas, saldo_dolares
                FROM ai_v_proveedores
                WHERE saldo_cordobas <> 0
                    OR saldo_dolares <> 0
                ORDER BY saldo_cordobas DESC, saldo_dolares DESC
                LIMIT " . $limite;
        }

        $filas = $this->consultar($sql, "cuentas_por_pagar", $input);
        return ["filtro" => $filtro, "resultado" => $filas, "nota" => "moneda_id 1 = Cordoba, 2 = Dolar"];
    }

    private function cajaResumen($input)
    {
        list($desde, $hasta) = $this->rangoFechas($input);
        $rango               = sprintf("dia BETWEEN '%s' AND '%s'", $desde, $hasta);

        $resultado = ["desde" => $desde, "hasta" => $hasta];

        if ($this->tienePermiso("ventas")) {
            $resultado["ventas_contado"] = $this->consultar("
                SELECT moneda, COUNT(*) AS facturas, SUM(total) AS total
                FROM ai_v_ventas
                WHERE es_valida = 1
                    AND tipo_pago LIKE '%CONTADO%'
                    AND " . $rango . "
                GROUP BY moneda
                LIMIT 10", "caja_resumen", $input);
        }

        $resultado["abonos"] = $this->consultar("
            SELECT tipo, moneda_id, COUNT(DISTINCT abono_id) AS cantidad, SUM(monto) AS total
            FROM ai_v_abonos
            WHERE es_valido = 1
                AND " . $rango . "
            GROUP BY tipo, moneda_id
            LIMIT 20", "caja_resumen", null);

        $resultado["ingresos_egresos"] = $this->consultar("
            SELECT tipo, moneda_id, COUNT(*) AS cantidad, SUM(monto) AS total
            FROM ai_v_caja_movimientos
            WHERE es_valido = 1
                AND " . $rango . "
            GROUP BY tipo, moneda_id
            LIMIT 20", "caja_resumen", null);

        $resultado["nota"] = "moneda_id 1 = Cordoba, 2 = Dolar";
        return $resultado;
    }

    /* ---------------------------------------------------------------------------------
       Conocimiento del sistema
    --------------------------------------------------------------------------------- */

    private function buscarEnManual($input)
    {
        $texto = (string) ($input["texto"] ?? "");
        $ruta  = APPPATH . "Helpers/posMe Ai/manual_posme.md";
        if (!is_file($ruta)) {
            return ["error" => "El manual no esta disponible"];
        }

        $secciones = self::buscarSeccionesManual(file_get_contents($ruta), $texto, 3);
        $this->auditar("buscar_en_manual", $input, count($secciones), 0, null);

        if (count($secciones) == 0) {
            return ["resultado" => "No se encontro informacion en el manual. Sugiere contactar a soporte de posMe."];
        }
        return ["secciones" => $secciones];
    }

    /**
     * Busca las secciones (## Titulo) del manual mas relevantes para el texto.
     * Puntaje: cada palabra en el titulo vale 3, en el cuerpo 1 (max. 5), multiplicado por las palabras distintas encontradas.
     *
     * @param string $manual Contenido markdown.
     * @param string $texto  Consulta del usuario.
     * @param int    $maximo Secciones a devolver.
     * @return array lista de ['titulo' => ..., 'contenido' => ...]
     */
    static function buscarSeccionesManual($manual, $texto, $maximo = 3)
    {
        $ignorar  = ["como", "para", "que", "los", "las", "del", "una", "uno", "por", "con", "donde", "puedo", "hago", "veo", "esta", "este", "sistema", "posme"];
        $palabras = [];
        foreach (preg_split('/[^a-z0-9ñ]+/u', self::normalizar($texto)) as $p) {
            if (mb_strlen($p) >= 3 && !in_array($p, $ignorar)) {
                // Raiz simple: "anulo" encuentra "anular", "facturas" encuentra "factura"
                $palabras[] = mb_strlen($p) > 4 ? mb_substr($p, 0, max(4, min(mb_strlen($p) - 1, 6))) : $p;
            }
        }
        if (count($palabras) == 0) {
            return [];
        }

        $partes     = preg_split('/^##\s+/m', $manual);
        $candidatas = [];
        foreach ($partes as $i => $parte) {
            if ($i == 0 || trim($parte) === "") {
                continue;
            }
            $lineas  = explode("\n", $parte, 2);
            $titulo  = trim($lineas[0]);
            $cuerpo  = isset($lineas[1]) ? trim($lineas[1]) : "";
            $tituloN = self::normalizar($titulo);
            $cuerpoN = self::normalizar($cuerpo);
            $puntaje   = 0;
            $distintas = 0;
            foreach (array_unique($palabras) as $p) {
                $parcial  = 3 * substr_count($tituloN, $p) + min(5, substr_count($cuerpoN, $p));
                $puntaje += $parcial;
                $distintas += $parcial > 0 ? 1 : 0;
            }
            // Se premian las secciones que contienen mas palabras distintas de la pregunta
            $puntaje = $puntaje * $distintas;
            if ($puntaje > 0) {
                $candidatas[] = ["puntaje" => $puntaje, "titulo" => $titulo, "contenido" => mb_substr($cuerpo, 0, 1800)];
            }
        }

        usort($candidatas, function ($a, $b) {
            return $b["puntaje"] <=> $a["puntaje"];
        });

        $resultado = [];
        foreach (array_slice($candidatas, 0, $maximo) as $c) {
            $resultado[] = ["titulo" => $c["titulo"], "contenido" => $c["contenido"]];
        }
        return $resultado;
    }

    private function rutaMenu($input)
    {
        $texto  = self::normalizar((string) ($input["texto"] ?? ""));
        $rutas  = self::buscarRutasMenu($this->dataSession, $texto, 8);
        $this->auditar("ruta_menu", $input, count($rutas), 0, null);

        if (count($rutas) == 0) {
            return ["resultado" => "No se encontro esa opcion en el menu del usuario (puede que no tenga permiso)."];
        }
        return ["opciones" => $rutas];
    }

    /**
     * Arma las rutas "Modulo > Submenu > Opcion" del menu real del usuario que coinciden con el texto.
     *
     * @param array  $dataSession Sesion (menuTop, menuLeft, menuBodyReport, menuBodyTop).
     * @param string $texto       Texto normalizado a buscar.
     * @param int    $maximo      Rutas a devolver.
     * @return array
     */
    static function buscarRutasMenu($dataSession, $texto, $maximo = 8)
    {
        $todos = [];
        foreach (["menuTop", "menuLeft", "menuBodyReport", "menuBodyTop"] as $clave) {
            $lista = $dataSession[$clave] ?? null;
            if (!is_array($lista)) {
                continue;
            }
            foreach ($lista as $m) {
                if (isset($m->menuElementID)) {
                    $todos[$m->menuElementID] = $m;
                }
            }
        }

        $palabras = array_filter(preg_split('/\s+/', $texto), function ($p) {
            return mb_strlen($p) >= 3;
        });
        if (count($palabras) == 0) {
            return [];
        }

        $resultado = [];
        foreach ($todos as $m) {
            $ruta    = [];
            $actual  = $m;
            $vueltas = 0;
            while ($actual && $vueltas < 6) {
                array_unshift($ruta, trim($actual->display));
                $padre  = $actual->parentMenuElementID ?? null;
                $actual = ($padre && isset($todos[$padre])) ? $todos[$padre] : null;
                $vueltas++;
            }
            $rutaTexto = implode(" > ", $ruta);
            $buscable  = self::normalizar($rutaTexto . " " . ($m->address ?? ""));
            $coincide  = 0;
            foreach ($palabras as $p) {
                if (strpos($buscable, $p) !== false) {
                    $coincide++;
                }
            }
            if ($coincide > 0) {
                $resultado[] = ["coincide" => $coincide, "ruta" => $rutaTexto, "pantalla" => str_replace(".aspx", "", $m->address ?? "")];
            }
        }

        usort($resultado, function ($a, $b) {
            return $b["coincide"] <=> $a["coincide"];
        });

        return array_map(function ($r) {
            return ["ruta" => $r["ruta"], "pantalla" => $r["pantalla"]];
        }, array_slice($resultado, 0, $maximo));
    }

    /* ---------------------------------------------------------------------------------
       consulta_sql (generica)
    --------------------------------------------------------------------------------- */

    private function consultaSql($input)
    {
        if ($this->getConfigReadonly() === null) {
            throw new \Exception("consulta_sql no esta habilitada; usa las otras herramientas");
        }

        $sql        = (string) ($input["sql"] ?? "");
        $validacion = self::validarSql($sql, $this->getVistasPermitidas(), $this->puedeVerCostos());
        if (!$validacion["ok"]) {
            $this->auditar("consulta_sql", ["sql" => $sql, "rechazado" => true], 0, 0, $validacion["error"]);
            throw new \Exception("SQL rechazado: " . $validacion["error"] . ". Corrige la consulta y reintenta.");
        }

        $filas    = $this->consultar($validacion["sql"], "consulta_sql", ["sql" => $sql, "explicacion" => $input["explicacion"] ?? ""]);
        $truncado = false;
        if ($validacion["detectarTruncado"] && count($filas) > self::MAX_FILAS) {
            $filas    = array_slice($filas, 0, self::MAX_FILAS);
            $truncado = true;
        }

        if (!$this->puedeVerCostos()) {
            $filas = self::quitarColumnasCosto($filas);
        }

        return ["filas" => count($filas), "truncado" => $truncado, "datos" => $filas];
    }

    /**
     * Valida una consulta escrita por la IA. Funcion pura (sin BD) para poder probarla.
     *
     * @param string $sql              Consulta propuesta.
     * @param array  $vistasPermitidas Vistas ai_v_* que el usuario puede leer.
     * @param bool   $puedeVerCostos   Si es false se rechazan columnas de costo/utilidad.
     * @return array ['ok' => bool, 'error' => string, 'sql' => string final con LIMIT, 'detectarTruncado' => bool]
     */
    static function validarSql($sql, $vistasPermitidas, $puedeVerCostos = true)
    {
        $rechazo = function ($mensaje) {
            return ["ok" => false, "error" => $mensaje, "sql" => "", "detectarTruncado" => false];
        };

        $sql = trim((string) $sql);
        $sql = preg_replace('/;\s*$/', '', $sql);
        if ($sql === "") {
            return $rechazo("consulta vacia");
        }
        if (strlen($sql) > 4000) {
            return $rechazo("consulta demasiado larga (maximo 4000 caracteres)");
        }

        // Barra invertida: su efecto depende de NO_BACKSLASH_ESCAPES y podria esconder un comentario
        if (strpos($sql, "\\") !== false) {
            return $rechazo("caracter no permitido: barra invertida");
        }

        // Se quitan los textos entre comillas para que su contenido no confunda las validaciones
        $sinTextos = preg_replace("/'(?:[^'\\\\]|\\\\.|'')*'/s", "''", $sql);
        $sinTextos = preg_replace('/"(?:[^"\\\\]|\\\\.|"")*"/s', '""', $sinTextos);
        if (preg_match("/['\"]/", str_replace(["''", '""'], "", $sinTextos))) {
            return $rechazo("comillas sin cerrar");
        }

        $minus = strtolower($sinTextos);

        if (!preg_match('/^\s*(select|with)\b/', $minus)) {
            return $rechazo("solo se permite SELECT o WITH");
        }
        if (strpos($minus, ";") !== false) {
            return $rechazo("solo se permite una sentencia (sin ;)");
        }
        foreach (["--", "#", "/*", "*/", "@", ":=", "`", "\\"] as $token) {
            if (strpos($minus, $token) !== false) {
                return $rechazo("caracter o comentario no permitido: " . $token);
            }
        }

        $prohibidas = [
            "insert", "update", "delete", "replace", "drop", "alter", "create", "truncate", "rename",
            "grant", "revoke", "set", "into", "outfile", "dumpfile", "load_file", "load", "sleep",
            "benchmark", "get_lock", "release_lock", "is_free_lock", "lock", "unlock", "handler", "call",
            "execute", "prepare", "deallocate", "procedure", "analyse", "kill", "shutdown", "flush",
            "information_schema", "performance_schema", "mysql", "sys", "database", "user", "current_user",
            "session_user", "system_user", "version", "connection_id", "found_rows", "row_count",
            "last_insert_id", "uuid", "rand", "pg_sleep", "waitfor",
        ];
        if (preg_match('/\b(' . implode("|", $prohibidas) . ')\b/', $minus, $m)) {
            return $rechazo("palabra no permitida: " . strtoupper($m[1]));
        }
        if (preg_match('/\btb_\w*/', $minus)) {
            return $rechazo("no se permite acceder a tablas tb_*; usa las vistas ai_v_*");
        }
        if (preg_match('/\b\w+\s*\.\s*ai_v_\w+/', $minus)) {
            return $rechazo("no se permite indicar la base de datos");
        }

        if (!$puedeVerCostos && preg_match('/\b(costo\w*|utilidad\w*)\b/', $minus, $m)) {
            return $rechazo("el usuario no tiene permiso para ver costos ni utilidades");
        }

        // FROM dentro de funciones (EXTRACT(YEAR FROM x), TRIM(... FROM x), SUBSTRING(x FROM n)) no es un origen
        $minus = preg_replace('/\b(extract|trim|substring|substr|position)\s*\(([^()]*?)\bfrom\b/', "$1($2,", $minus);

        // Nombres definidos por WITH (CTE) se permiten como origen
        $cte = [];
        if (preg_match_all('/(?:\bwith\b|,)\s*(?:recursive\s+)?([a-z_][a-z0-9_]*)\s*(?:\([^)]*\))?\s+as\s*\(/', $minus, $mm)) {
            $cte = $mm[1];
        }

        // Objetos usados como origen: despues de FROM / JOIN y en listas separadas por coma del FROM
        $origenes = [];
        if (preg_match_all('/\b(?:from|join)\s+([a-z_][a-z0-9_]*)/', $minus, $mm)) {
            $origenes = array_merge($origenes, $mm[1]);
        }
        if (preg_match_all('/\bfrom\s+(.+?)(?=\bwhere\b|\bgroup\b|\border\b|\bhaving\b|\blimit\b|\bjoin\b|\binner\b|\bleft\b|\bright\b|\bcross\b|\bnatural\b|\bstraight_join\b|\bunion\b|\bwindow\b|\)|$)/s', $minus, $mm)) {
            foreach ($mm[1] as $lista) {
                foreach (explode(",", $lista) as $item) {
                    if (preg_match('/^\s*([a-z_][a-z0-9_]*)/', $item, $x)) {
                        $origenes[] = $x[1];
                    }
                }
            }
        }
        // FROM (subconsulta): el contenido ya se valida por las mismas reglas
        $origenes = array_unique($origenes);
        if (count($origenes) == 0) {
            return $rechazo("la consulta debe leer de alguna vista ai_v_*");
        }
        foreach ($origenes as $o) {
            if ($o == "select" || in_array($o, $cte)) {
                continue;
            }
            if (strpos($o, "ai_v_") !== 0) {
                return $rechazo("solo se permite leer vistas ai_v_* (encontrado: " . $o . ")");
            }
            if (!in_array($o, $vistasPermitidas)) {
                return $rechazo("sin_permiso para la vista " . $o);
            }
        }

        // LIMIT: si no hay al final se agrega MAX_FILAS + 1 para detectar truncado; si es mayor se reduce
        $detectarTruncado = false;
        if (preg_match('/\blimit\s+(\d+)\s*(?:,\s*(\d+)|\s+offset\s+(\d+))?\s*$/', $minus, $m)) {
            $cantidad = isset($m[2]) && $m[2] !== "" ? (int) $m[2] : (int) $m[1];
            if ($cantidad > self::MAX_FILAS) {
                $offset = 0;
                if (isset($m[2]) && $m[2] !== "") {
                    $offset = (int) $m[1];
                } else if (isset($m[3]) && $m[3] !== "") {
                    $offset = (int) $m[3];
                }
                $sql              = preg_replace('/\blimit\s+\d+\s*(?:,\s*\d+|\s+offset\s+\d+)?\s*$/i', "LIMIT " . (self::MAX_FILAS + 1) . " OFFSET " . $offset, $sql);
                $detectarTruncado = true;
            }
        } else {
            $sql              = $sql . " LIMIT " . (self::MAX_FILAS + 1);
            $detectarTruncado = true;
        }

        return ["ok" => true, "error" => "", "sql" => $sql, "detectarTruncado" => $detectarTruncado];
    }

    /**
     * Elimina columnas de costo/utilidad de un resultado (usuarios sin permiso de ver costos).
     *
     * @param array $filas Filas asociativas.
     * @return array
     */
    static function quitarColumnasCosto($filas)
    {
        foreach ($filas as $i => $fila) {
            foreach (array_keys($fila) as $columna) {
                if (preg_match('/costo|utilidad/i', $columna)) {
                    unset($filas[$i][$columna]);
                }
            }
        }
        return $filas;
    }

    /* =================================================================================
       CONEXION Y UTILIDADES
    ================================================================================= */

    /**
     * Configuracion del usuario de solo lectura de la empresa de la sesion o null si no esta configurado.
     *
     * @return object|null
     */
    function getConfigReadonly()
    {
        $config = self::getConfig($this->companyID);
        if (empty($config->readonlyUsername) || empty($config->readonlyPassword)) {
            return null;
        }
        return $config;
    }

    /**
     * Configuracion privada del asistente tomada de las constantes de app/Config/Constants.php:
     *   AI_API_KEY            Clave de la API de Anthropic.
     *   AI_READONLY_HOSTNAME  Servidor MySQL del usuario de solo lectura (defecto localhost).
     *   AI_READONLY_USERNAME  Usuario MySQL de solo lectura (solo SELECT sobre ai_v_*).
     *   AI_READONLY_PASSWORD  Contrasena de ese usuario.
     *   AI_READONLY_DATABASE  Base de datos (defecto la misma de la aplicacion, DB_BDNAME).
     * Es la misma configuracion para todas las empresas del servidor. Si una constante no esta
     * definida se usa el valor por defecto (el asistente nunca debe romper la aplicacion).
     *
     * @param int $companyID Empresa de la sesion (se conserva por compatibilidad; no se usa).
     * @return object
     */
    static function getConfig($companyID = 0)
    {
        $leer = function ($nombre, $defecto) {
            $valor = defined($nombre) ? trim((string) constant($nombre)) : "";
            return $valor !== "" ? $valor : $defecto;
        };

        return (object) [
            "apiKey"           => $leer("AI_API_KEY", ""),
            "readonlyHostname" => $leer("AI_READONLY_HOSTNAME", "localhost"),
            "readonlyUsername" => $leer("AI_READONLY_USERNAME", ""),
            "readonlyPassword" => $leer("AI_READONLY_PASSWORD", ""),
            "readonlyDatabase" => $leer("AI_READONLY_DATABASE", DB_BDNAME),
            "readonlyPort"     => 3306,
        ];
    }

    /**
     * Conexion para leer las vistas: solo lectura si esta configurada; si no, la principal.
     * En ambos casos se fija @ai_company_id con el companyID de la sesion.
     *
     * @return \CodeIgniter\Database\BaseConnection
     */
    private function getConexion()
    {
        if ($this->db !== null) {
            return $this->db;
        }

        $config = $this->getConfigReadonly();
        if ($config !== null) {
            $this->db = \Config\Database::connect([
                "DSN"      => "",
                "hostname" => $config->readonlyHostname,
                "username" => $config->readonlyUsername,
                "password" => $config->readonlyPassword,
                "database" => $config->readonlyDatabase,
                "DBDriver" => "MySQLi",
                "DBPrefix" => "",
                "pConnect" => false,
                "DBDebug"  => true,
                "charset"  => "utf8mb4",
                "DBCollat" => "utf8mb4_general_ci",
                "swapPre"  => "",
                "encrypt"  => false,
                "compress" => false,
                "strictOn" => false,
                "failover" => [],
                "port"     => (int) $config->readonlyPort,
            ], false);
            $this->esReadonly = true;
        } else {
            $this->db         = db_connect();
            $this->esReadonly = false;
        }

        // Limite de tiempo por consulta (MariaDB). En MySQL 5.7+ se usa el hint de conLimiteDeTiempo()
        if (stripos((string) $this->db->getVersion(), "mariadb") !== false) {
            $this->db->query("SET SESSION max_statement_time = " . self::MAX_SEGUNDOS);
        }
        $this->db->query("SET @ai_company_id = " . (int) $this->companyID);
        return $this->db;
    }

    /**
     * Ejecuta un SELECT sobre las vistas con limite de tiempo y lo registra en auditoria.
     *
     * @param string     $sql         Consulta ya validada o fija.
     * @param string     $herramienta Nombre de la herramienta (auditoria).
     * @param array|null $parametros  Parametros a auditar (null = no auditar esta consulta).
     * @return array filas asociativas
     */
    private function consultar($sql, $herramienta, $parametros)
    {
        $db     = $this->getConexion();
        $inicio = microtime(true);
        $error  = null;
        $filas  = [];

        // Siempre se vuelve a fijar la empresa de la sesion antes de consultar
        $db->query("SET @ai_company_id = " . (int) $this->companyID);

        try {
            $filas = $db->query($this->conLimiteDeTiempo($db, $sql))->getResultArray();
        } catch (\Throwable $ex) {
            $error = $ex->getMessage();
        }

        $ms = (int) round((microtime(true) - $inicio) * 1000);
        if ($parametros !== null || $error !== null) {
            $this->auditar($herramienta, $parametros ?? ["sql" => $sql], count($filas), $ms, $error);
        }
        if ($error !== null) {
            throw new \Exception($error);
        }
        return $filas;
    }

    /**
     * Agrega el limite de tiempo en MySQL 5.7+ (hint). En MariaDB ya se fijo max_statement_time en la sesion.
     *
     * @param \CodeIgniter\Database\BaseConnection $db  Conexion.
     * @param string                                $sql Consulta.
     * @return string
     */
    private function conLimiteDeTiempo($db, $sql)
    {
        $version = (string) $db->getVersion();
        if (stripos($version, "mariadb") !== false) {
            return $sql;
        }
        if (version_compare($version, "5.7.8", ">=")) {
            return preg_replace('/^\s*(select)\b/i', "SELECT /*+ MAX_EXECUTION_TIME(" . (self::MAX_SEGUNDOS * 1000) . ") */", $sql, 1);
        }
        return $sql;
    }

    /**
     * Guarda en tb_ai_auditoria_sql. Nunca interrumpe la respuesta si falla.
     */
    private function auditar($herramienta, $parametros, $filas, $ms, $error)
    {
        try {
            $this->Ai_Chat_Model->insert_auditoria([
                "companyID"     => $this->companyID,
                "userID"        => $this->userID,
                "herramienta"   => $herramienta,
                "sqlParametros" => mb_substr(is_string($parametros) ? $parametros : json_encode($parametros, JSON_UNESCAPED_UNICODE), 0, 60000),
                "filas"         => (int) $filas,
                "ms"            => (int) $ms,
                "error"         => $error === null ? null : mb_substr($error, 0, 1000),
                "createdOn"     => helper_getDateTime(),
            ]);
        } catch (\Throwable $ex) {
            log_message("error", "core_web_ai_tools auditoria: " . $ex->getMessage());
        }
    }

    /** Limite entre 1 y 50 (defecto indicado). */
    private function limite($input, $defecto)
    {
        $limite = (int) ($input["limite"] ?? $defecto);
        return max(1, min(50, $limite > 0 ? $limite : $defecto));
    }

    /** Fechas desde/hasta validadas (YYYY-MM-DD); por defecto hoy. */
    private function rangoFechas($input)
    {
        $hoy   = substr(helper_getDate(), 0, 10);
        $desde = (string) ($input["desde"] ?? $hoy);
        $hasta = (string) ($input["hasta"] ?? $desde);
        $patron = '/^\d{4}-\d{2}-\d{2}$/';
        if (!preg_match($patron, $desde) || !preg_match($patron, $hasta)) {
            throw new \Exception("Fechas invalidas; usa el formato YYYY-MM-DD");
        }
        if ($desde > $hasta) {
            list($desde, $hasta) = [$hasta, $desde];
        }
        return [$desde, $hasta];
    }

    /** Patron LIKE escapado: '%texto%'. */
    private function like($db, $texto)
    {
        return "'%" . $db->escapeLikeString($texto) . "%'";
    }

    /**
     * Minusculas y sin tildes, para busquedas.
     *
     * @param string $texto
     * @return string
     */
    static function normalizar($texto)
    {
        $texto = mb_strtolower((string) $texto, "UTF-8");
        return strtr($texto, ["á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u", "ü" => "u", "à" => "a", "è" => "e", "ì" => "i", "ò" => "o", "ù" => "u"]);
    }

    /**
     * JSON compacto: numeros decimales recortados a 4 decimales y texto en UTF-8.
     *
     * @param mixed $datos
     * @return string
     */
    static function toJson($datos)
    {
        array_walk_recursive($datos, function (&$valor) {
            if (is_string($valor) && is_numeric($valor) && strpos($valor, ".") !== false && strlen($valor) < 30) {
                $valor = round((float) $valor, 4);
            } else if (is_string($valor) && !mb_check_encoding($valor, "UTF-8")) {
                $valor = mb_convert_encoding($valor, "UTF-8", "ISO-8859-1");
            }
        });
        return json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
    }

    /**
     * Mensaje de error sin datos de conexion (usuario, host, rutas).
     *
     * @param string $mensaje
     * @return string
     */
    static function mensajeErrorSeguro($mensaje)
    {
        $mensaje = preg_replace("/'[^']*'@'[^']*'/", "'***'", (string) $mensaje);
        $mensaje = preg_replace('/[A-Z]:\\\\[^\s]+/i', '[ruta]', $mensaje);
        return mb_substr($mensaje, 0, 400);
    }
}
