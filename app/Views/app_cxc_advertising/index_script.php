<?php
    /**
     * Devuelve el valor listo para incrustar en un script: si el controlador ya lo
     * codificó como JSON (cadena) se usa tal cual; si llega como arreglo u objeto se codifica aquí.
     *
     * @param mixed  $valor   Valor recibido del controlador.
     * @param string $defecto JSON a usar si no llegó nada.
     * @return string
     */
    $advJson = function ($valor, $defecto) {
        if ($valor === null || $valor === "") {
            return $defecto;
        }
        if (is_string($valor)) {
            return $valor;
        }
        $json = json_encode($valor, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
        return $json === false ? $defecto : $json;
    };
    $advEmpresa = (isset($company) && is_object($company) && isset($company->name)) ? $company->name : "Mi empresa";
?>
<script>
    var varUseMobile = '<?php echo (isset($useMobile) && $useMobile == "1") ? "1" : "0"; ?>';
</script>
<?php echo view('core_template/posme_v2_common'); ?>
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script>
(function () {
    'use strict';

    var URL_BASE    = '<?php echo base_url(); ?>/app_cxc_advertising/';
    var URL_SHEETJS = 'https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js';

    /* Datos del controlador (JSON ya seguro para script) */
    var VARIABLES_SERVIDOR  = <?php echo $advJson($objVariables ?? null, '[]'); ?>;
    var CATEGORIAS_SERVIDOR = <?php echo $advJson($objCategories ?? null, '[]'); ?>;
    var CONFIG_SERVIDOR     = <?php echo $advJson($objConfig ?? null, '{}'); ?>;
    var EMPRESA             = <?php echo json_encode((string) $advEmpresa, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;

    var MENSAJE_SESION = 'El servidor no respondió como se esperaba. Es posible que su sesión haya vencido: recargue la página e inicie sesión.';
    var MENSAJE_RED    = 'Error de conexión con el servidor. Verifique su red e intente de nuevo.';
    var MENSAJE_FALLA  = 'No se pudo completar la operación.';

    /* Columnas del Excel con significado propio: no se ofrecen como variables */
    var COLUMNAS_RESERVADAS = ['telefono', 'mensaje', 'imagen'];
    /* Encabezados alternos que se aceptan como teléfono */
    var ALIAS_TELEFONO      = ['celular', 'movil', 'whatsapp', 'telefonos', 'phone', 'numero', 'tel'];
    var TIPOS_IMAGEN        = ['image/png', 'image/jpeg', 'image/webp'];
    var MAX_IMAGEN          = 5 * 1024 * 1024;
    var POR_PAGINA          = 50;

    var secuenciaPreview = 0;
    var promesaSheetJs   = null;

    /* ---------------- Utilidades ---------------- */

    function escaparHtml(texto) {
        return String(texto === null || texto === undefined ? '' : texto)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    /* El toast pinta con innerHTML: todo texto se escapa antes de entregarlo */
    function notificar(mensaje, tipo) {
        fnShowNotification(escaparHtml(mensaje), tipo);
    }

    /* Encabezado de Excel -> llave: minúscula, sin acentos, sin espacios ni símbolos */
    function normalizarClave(texto) {
        var valor = String(texto === null || texto === undefined ? '' : texto);
        if (valor.normalize) {
            valor = valor.normalize('NFD').replace(/[̀-ͯ]/g, '');
        }
        return valor.toLowerCase().replace(/[^a-z0-9_]/g, '');
    }

    function dosDigitos(n) {
        return (n < 10 ? '0' : '') + n;
    }

    function fechaCorta(fecha) {
        return dosDigitos(fecha.getDate()) + '/' + dosDigitos(fecha.getMonth() + 1) + '/' + fecha.getFullYear();
    }

    /* Celda del Excel -> texto. Los teléfonos numéricos se pasan sin notación científica */
    function valorTexto(valor) {
        if (valor === null || valor === undefined) { return ''; }
        if (valor instanceof Date) { return isNaN(valor.getTime()) ? '' : fechaCorta(valor); }
        if (typeof valor === 'number') {
            if (!isFinite(valor)) { return ''; }
            return Number.isInteger(valor) ? valor.toFixed(0) : String(valor);
        }
        return String(valor).trim();
    }

    function esUrlHttps(texto) {
        return /^https:\/\/[^\s]+$/i.test(String(texto || '').trim());
    }

    function aBooleano(valor) {
        return valor === true || valor === 1 || valor === '1' || String(valor).toLowerCase() === 'true';
    }

    function normalizarVariables(lista) {
        if (!Array.isArray(lista)) { return []; }
        return lista.filter(function (item) {
            return item && item.name;
        }).map(function (item) {
            return {
                name:     String(item.name),
                label:    String(item.label || item.name),
                dataType: String(item.dataType || 'text').toLowerCase()
            };
        });
    }

    function normalizarCategorias(lista) {
        if (!Array.isArray(lista)) { return []; }
        return lista.filter(function (item) {
            return item && item.catalogItemID !== undefined;
        });
    }

    function normalizarConfig(datos) {
        var origen = (datos && typeof datos === 'object') ? datos : {};
        var maximo = parseInt(origen.maxMessage, 10);
        return {
            enabled:        aBooleano(origen.enabled),
            maxMessage:     isNaN(maximo) || maximo < 1 ? 1000 : maximo,
            costMeta:       pv2Num(origen.costMeta),
            costEvolution:  pv2Num(origen.costEvolution),
            wabaConfigured: aBooleano(origen.wabaConfigured)
        };
    }

    /* SheetJS se descarga solo cuando se usa el Excel */
    function cargarSheetJs() {
        if (window.XLSX) { return Promise.resolve(window.XLSX); }
        if (promesaSheetJs) { return promesaSheetJs; }
        promesaSheetJs = new Promise(function (resolver, rechazar) {
            var script            = document.createElement('script');
            script.src            = URL_SHEETJS;
            script.crossOrigin    = 'anonymous';
            script.referrerPolicy = 'no-referrer';
            script.onload  = function () {
                if (window.XLSX) { resolver(window.XLSX); } else { promesaSheetJs = null; rechazar(new Error('XLSX')); }
            };
            script.onerror = function () {
                promesaSheetJs = null;
                rechazar(new Error('XLSX'));
            };
            document.head.appendChild(script);
        });
        return promesaSheetJs;
    }

    /**
     * Interpreta la respuesta del servidor. Nunca lanza excepción: cualquier falla
     * (HTML por sesión vencida, JSON inválido) vuelve como {error:true, message}.
     *
     * @param {Response} res Respuesta de fetch.
     * @return {Promise<Object>}
     */
    async function leerRespuesta(res) {
        var json;
        try {
            json = await res.json();
        } catch (ex) {
            console.error(ex);
            return { error: true, message: MENSAJE_SESION };
        }
        if (!json || typeof json !== 'object') {
            return { error: true, message: MENSAJE_SESION };
        }
        if (json.error === true) {
            return { error: true, message: json.message || MENSAJE_FALLA };
        }
        if (!res.ok) {
            return { error: true, message: MENSAJE_FALLA };
        }
        return json;
    }

    /**
     * POST JSON al controlador; las fallas de red vuelven como {error:true, message}.
     *
     * @param {string} metodo Método de app_cxc_advertising.
     * @param {Object} datos Cuerpo de la petición.
     * @return {Promise<Object>}
     */
    async function llamar(metodo, datos) {
        var res;
        try {
            res = await fetch(URL_BASE + metodo, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify(datos || {})
            });
        } catch (ex) {
            console.error(ex);
            return { error: true, message: MENSAJE_RED };
        }
        return leerRespuesta(res);
    }

    /**
     * POST multipart (subida de archivos). El navegador arma el Content-Type con el boundary.
     *
     * @param {string} metodo Método de app_cxc_advertising.
     * @param {FormData} formulario Datos a enviar.
     * @return {Promise<Object>}
     */
    async function llamarArchivo(metodo, formulario) {
        var res;
        try {
            res = await fetch(URL_BASE + metodo, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formulario
            });
        } catch (ex) {
            console.error(ex);
            return { error: true, message: MENSAJE_RED };
        }
        return leerRespuesta(res);
    }

    function plantillaVacia() {
        return { name: '', language: 'es', headerType: '', variableType: 'positional', body: '', mapping: [] };
    }

    function estadoInicial() {
        return {
            paso:            1,
            pasos: [
                { num: 1, titulo: 'Mensaje',       sub: 'Texto e imagen' },
                { num: 2, titulo: 'Destinatarios', sub: 'Excel y clientes' },
                { num: 3, titulo: 'Proveedor',     sub: 'Envío y costo' },
                { num: 4, titulo: 'Resumen',       sub: 'Revisar y enviar' }
            ],
            erroresPaso:     [],

            /* Mensaje */
            caption:         '',
            body:            '',
            urlImage:        '',
            imagenNombre:    '',
            subiendoImagen:  false,
            mostrarEmojis:   false,

            /* Destinatarios */
            useExcel:        false,
            excelRows:       [],
            excelColumnas:   [],
            excelArchivo:    '',
            excelTotalLeido: 0,
            excelTruncado:   false,
            leyendoExcel:    false,
            errorExcel:      '',
            descargandoPlantilla: false,
            useCustomers:    true,
            filters:         { onlyBalance: false, onlyMora: false, categoryID: 0 },

            /* Proveedor y plantilla */
            provider:        'evolution',
            plantillaModo:   'lista',
            plantillaClave:  '',
            template:        plantillaVacia(),
            manualCantidad:  0,
            manualNombres:   '',

            /* Resumen */
            previsualizando: false,
            errorPreview:    '',
            summary:         null,
            discarded:       [],
            sample:          [],
            firmaResumen:    '',
            filtroMotivo:    '',
            paginaDescartados: 1,

            /* Envío */
            enviando:        false,
            resultado:       null
        };
    }

    function montar() {
        Vue.createApp({
            template: '#advTemplate',
            data: function () {
                var estado = estadoInicial();
                estado.variables          = normalizarVariables(VARIABLES_SERVIDOR);
                estado.categorias         = normalizarCategorias(CATEGORIAS_SERVIDOR);
                estado.config             = normalizarConfig(CONFIG_SERVIDOR);
                estado.empresa            = EMPRESA;
                estado.horaActual         = '';
                estado.emojis             = ['😀', '😊', '😍', '🎉', '🔥', '✅', '⭐', '🎁', '💰', '💳', '📢', '📅', '⏰', '📞', '👉', '🙏', '❤️', '🛒', '🚚', '⚠️'];
                estado.plantillas         = [];
                estado.plantillasCargadas = false;
                estado.cargandoPlantillas = false;
                estado.errorPlantillas    = '';
                if (!estado.config.wabaConfigured) { estado.plantillaModo = 'manual'; }
                return estado;
            },
            computed: {
                /* Filas máximas por archivo: el servidor rechaza archivos más grandes */
                limiteExcel: function () {
                    return Math.ceil(this.config.maxMessage * 1.5);
                },
                /* Columnas extra del Excel que se pueden usar como variables */
                columnasVariables: function () {
                    if (!this.useExcel) { return []; }
                    return this.excelColumnas.filter(function (columna) {
                        return COLUMNAS_RESERVADAS.indexOf(columna) === -1;
                    });
                },
                primeraFilaExcel: function () {
                    return (this.useExcel && this.excelRows.length > 0) ? this.excelRows[0] : null;
                },
                hayImagen: function () {
                    return this.urlImage !== '' || (this.useExcel && this.excelColumnas.indexOf('imagen') !== -1);
                },
                placeholderBody: function () {
                    return 'Hola ' + this.token('nombre') + ', tenemos una promoción especial para usted...';
                },
                /* Valores de ejemplo para la vista previa: catálogo con datos ficticios y Excel con su primera fila */
                valoresMuestra: function () {
                    var valores = {};
                    var hoy     = fechaCorta(new Date());
                    this.variables.forEach(function (variable) {
                        if (variable.dataType === 'money') {
                            valores[variable.name] = 'C$ 1,250.00';
                        } else if (variable.dataType === 'date') {
                            valores[variable.name] = hoy;
                        } else if (variable.name === 'nombre') {
                            valores[variable.name] = 'Juan Pérez';
                        } else {
                            valores[variable.name] = '[' + variable.label + ']';
                        }
                    });
                    var fila = this.primeraFilaExcel;
                    if (fila) {
                        this.columnasVariables.forEach(function (columna) {
                            if (String(fila[columna] || '') !== '') { valores[columna] = String(fila[columna]); }
                        });
                    }
                    return valores;
                },
                textoVistaPrevia: function () {
                    if (this.provider === 'meta') { return this.textoPlantilla; }
                    var texto   = this.body;
                    var titulo  = this.caption.trim();
                    if (titulo !== '') { texto = '*' + titulo + '*' + (texto !== '' ? '\n\n' + texto : ''); }
                    return this.reemplazarVariables(texto, this.valoresMuestra);
                },
                /* Texto de la plantilla de Meta con el mapeo aplicado */
                textoPlantilla: function () {
                    var self = this;
                    if (!this.template.name) { return ''; }
                    var valores = {};
                    this.template.mapping.forEach(function (item) {
                        var valor = self.valorMapeo(item);
                        valores[item.key] = valor === '' ? '-' : valor;
                    });
                    if (this.template.body) {
                        return this.reemplazarVariables(this.template.body, valores);
                    }
                    var texto = 'Plantilla *' + this.template.name + '*\n_El texto lo define Meta._';
                    this.template.mapping.forEach(function (item) {
                        texto += '\n' + self.token(item.key) + ' = ' + valores[item.key];
                    });
                    return texto;
                },
                imagenVistaPrevia: function () {
                    var imagenFila = this.primeraFilaExcel && esUrlHttps(this.primeraFilaExcel.imagen) ? String(this.primeraFilaExcel.imagen).trim() : '';
                    var imagen     = imagenFila || this.urlImage;
                    if (this.provider === 'meta') {
                        return this.template.headerType === 'IMAGE' ? imagen : '';
                    }
                    return imagen;
                },
                notaVistaPrevia: function () {
                    if (this.provider === 'meta') { return 'Vista previa de la plantilla con datos de ejemplo.'; }
                    if (this.useExcel && this.excelColumnas.indexOf('mensaje') !== -1) { return 'Las filas del Excel con la columna mensaje usan su propio texto.'; }
                    return 'Las variables se muestran con datos de ejemplo.';
                },
                /* Payload de preview/send sin la confirmación; su firma detecta cambios después del resumen */
                firmaPayload: function () {
                    return JSON.stringify(this.construirPayload(false));
                },
                resumenVencido: function () {
                    return this.summary !== null && this.firmaPayload !== this.firmaResumen;
                },
                resumenVigente: function () {
                    return this.summary !== null && !this.resumenVencido;
                },
                mensajesEstimados: function () {
                    if (this.resumenVigente) { return pv2Num(this.summary.valid); }
                    return this.useExcel ? this.excelRows.length : 0;
                },
                /* Costo: Meta cobra por mensaje; Evolution es un costo fijo */
                costoMetaEstimado: function () {
                    return this.mensajesEstimados * this.config.costMeta;
                },
                costoEstimado: function () {
                    if (this.resumenVigente) { return pv2Num(this.summary.cost); }
                    return this.provider === 'meta' ? this.costoMetaEstimado : this.config.costEvolution;
                },
                totalDescartados: function () {
                    if (!this.summary) { return 0; }
                    var s = this.summary;
                    return pv2Num(s.empty) + pv2Num(s.invalid) + pv2Num(s.duplicate) + pv2Num(s.overLimit);
                },
                motivos: function () {
                    var lista = [];
                    this.discarded.forEach(function (item) {
                        if (item.motivo && lista.indexOf(item.motivo) === -1) { lista.push(item.motivo); }
                    });
                    return lista;
                },
                discardedFiltrados: function () {
                    var motivo = this.filtroMotivo;
                    if (motivo === '') { return this.discarded; }
                    return this.discarded.filter(function (item) { return item.motivo === motivo; });
                },
                paginasDescartados: function () {
                    return Math.max(1, Math.ceil(this.discardedFiltrados.length / POR_PAGINA));
                },
                discardedPagina: function () {
                    var inicio = (this.paginaDescartados - 1) * POR_PAGINA;
                    return this.discardedFiltrados.slice(inicio, inicio + POR_PAGINA);
                },
                motivoBloqueo: function () {
                    if (this.enviando)                { return ''; }
                    if (!this.config.enabled)         { return 'Las campañas están deshabilitadas para esta empresa.'; }
                    if (this.summary === null)        { return 'Calcule el resumen para poder enviar.'; }
                    if (this.resumenVencido)          { return 'Actualice el resumen antes de enviar.'; }
                    if (!this.summary.enabled)        { return 'Las campañas están deshabilitadas para esta empresa.'; }
                    if (pv2Num(this.summary.overLimit) > 0) { return 'La campaña supera el máximo de mensajes permitido.'; }
                    if (pv2Num(this.summary.valid) < 1)     { return 'No hay mensajes válidos para enviar.'; }
                    return '';
                },
                puedeEnviar: function () {
                    return this.paso === 4 && !this.previsualizando && !this.enviando && this.motivoBloqueo === '';
                }
            },
            watch: {
                provider: function (valor) {
                    this.erroresPaso = [];
                    /* Al elegir Meta se cargan las plantillas aprobadas una sola vez */
                    if (valor === 'meta' && this.plantillaModo === 'lista' && !this.plantillasCargadas && !this.cargandoPlantillas && this.config.wabaConfigured) {
                        this.cargarPlantillas();
                    }
                },
                filtroMotivo: function () {
                    this.paginaDescartados = 1;
                },
                useExcel: function () {
                    this.errorExcel = '';
                }
            },
            methods: {
                fmt: function (valor) {
                    return pv2Fmt(valor);
                },
                /* Costo unitario con hasta 4 decimales (Meta cobra fracciones de centavo) */
                fmt4: function (valor) {
                    var texto = pv2Num(valor).toFixed(4).replace(/0{1,2}$/, '');
                    return texto;
                },
                entero: function (valor) {
                    return pv2Fmt(Math.round(pv2Num(valor))).replace(/\.\d+$/, '');
                },
                /* Token de variable; se arma aquí para no escribir llaves dobles en la plantilla de Vue */
                token: function (nombre) {
                    return '{' + '{' + nombre + '}' + '}';
                },
                reemplazarVariables: function (texto, valores) {
                    return String(texto || '').replace(/\{\{\s*([^{}\s]+)\s*\}\}/g, function (coincidencia, clave) {
                        if (Object.prototype.hasOwnProperty.call(valores, clave)) { return valores[clave]; }
                        var minuscula = clave.toLowerCase();
                        if (Object.prototype.hasOwnProperty.call(valores, minuscula)) { return valores[minuscula]; }
                        return coincidencia;
                    });
                },
                /**
                 * Convierte el formato de WhatsApp a HTML. Primero se escapa el texto
                 * y después se aplican *negrita*, _cursiva_ y ~tachado~: el resultado es seguro para v-html.
                 */
                formatearWhatsapp: function (texto) {
                    return escaparHtml(texto)
                        .replace(/\*([^*\n]+)\*/g, '<strong>$1</strong>')
                        .replace(/(^|[^A-Za-z0-9])_([^_\n]+)_(?![A-Za-z0-9])/g, '$1<em>$2</em>')
                        .replace(/~([^~\n]+)~/g, '<del>$1</del>')
                        .replace(/\n/g, '<br>');
                },
                valorMapeo: function (item) {
                    if (item.source === 'fixed') { return String(item.value || ''); }
                    if (!item.value) { return ''; }
                    var valor = this.valoresMuestra[item.value];
                    return valor === undefined ? '' : String(valor);
                },

                /* ---------------- Payload ---------------- */
                construirPayload: function (confirmar) {
                    var usarExcel = this.useExcel && this.excelRows.length > 0;
                    return {
                        provider:     this.provider,
                        caption:      this.caption.trim(),
                        body:         this.body,
                        urlImage:     this.urlImage,
                        useExcel:     usarExcel,
                        excelRows:    usarExcel ? this.excelRows : [],
                        useCustomers: this.useCustomers,
                        filters: {
                            onlyBalance: !!this.filters.onlyBalance,
                            onlyMora:    !!this.filters.onlyMora,
                            categoryID:  parseInt(this.filters.categoryID, 10) || 0
                        },
                        template: {
                            name:         this.template.name,
                            language:     this.template.language,
                            headerType:   this.template.headerType,
                            variableType: this.template.variableType,
                            body:         this.template.body,
                            /* Todas las llaves de la plantilla: el servidor valida que cada una esté mapeada */
                            variables:    this.template.mapping.map(function (item) { return item.key; }),
                            mapping: this.template.mapping.map(function (item) {
                                return { key: item.key, source: item.source, value: item.value };
                            })
                        },
                        confirm: confirmar === true
                    };
                },

                /* ---------------- Pasos y validaciones ---------------- */
                validarPaso: function (numero) {
                    var errores = [];
                    var self    = this;
                    if (numero === 1) {
                        var tieneMensajeExcel = this.useExcel && this.excelColumnas.indexOf('mensaje') !== -1;
                        if (this.provider === 'evolution' && this.body.trim() === '' && this.caption.trim() === '' && this.urlImage === '' && !tieneMensajeExcel) {
                            errores.push('Escriba el texto del mensaje, un título o suba una imagen.');
                        }
                    }
                    if (numero === 2) {
                        if (!this.useExcel && !this.useCustomers) {
                            errores.push('Elija al menos un origen de destinatarios: Excel o clientes registrados.');
                        }
                        if (this.useExcel && this.excelRows.length === 0) {
                            errores.push('Cargue el archivo Excel o desactive esa opción.');
                        }
                    }
                    if (numero === 3 && this.provider === 'meta') {
                        if (!this.template.name) {
                            errores.push('Seleccione o escriba la plantilla de Meta.');
                        } else {
                            if (!this.template.language) { errores.push('Indique el idioma de la plantilla.'); }
                            this.template.mapping.forEach(function (item) {
                                if (String(item.value || '').trim() === '') {
                                    errores.push('Asigne un valor a la variable ' + self.token(item.key) + '.');
                                }
                            });
                            if (this.template.headerType === 'IMAGE' && !this.hayImagen) {
                                errores.push('La plantilla requiere una imagen de encabezado.');
                            }
                        }
                    }
                    return errores;
                },
                /* Valida los pasos desde "desde" hasta "hasta"; si uno falla se queda en él */
                validarHasta: function (desde, hasta) {
                    for (var numero = desde; numero <= hasta; numero++) {
                        var errores = this.validarPaso(numero);
                        if (errores.length > 0) {
                            this.paso        = numero;
                            this.erroresPaso = errores;
                            this.$nextTick(function () { pv2FocusField('#advErrores'); });
                            return false;
                        }
                    }
                    return true;
                },
                irArriba: function () {
                    var contenedor = document.getElementById('app');
                    try { contenedor.scrollIntoView({ behavior: 'smooth', block: 'start' }); } catch (ex) { contenedor.scrollIntoView(); }
                },
                irAPaso: function (numero) {
                    if (numero === this.paso) { return; }
                    if (numero > this.paso && !this.validarHasta(this.paso, numero - 1)) { return; }
                    this.erroresPaso   = [];
                    this.mostrarEmojis = false;
                    this.paso          = numero;
                    this.irArriba();
                    if (numero === 4 && !this.resumenVigente) { this.previsualizar(); }
                },
                siguiente: function () {
                    if (this.paso < 4) { this.irAPaso(this.paso + 1); }
                },
                anterior: function () {
                    if (this.paso > 1) {
                        this.erroresPaso = [];
                        this.paso--;
                        this.irArriba();
                    }
                },

                /* ---------------- Editor ---------------- */
                insertarTexto: function (texto) {
                    if (this.provider === 'meta') { return; }
                    var editor = this.$refs.editor;
                    var inicio = (editor && typeof editor.selectionStart === 'number') ? editor.selectionStart : this.body.length;
                    var fin    = (editor && typeof editor.selectionEnd === 'number') ? editor.selectionEnd : inicio;
                    this.body  = this.body.slice(0, inicio) + texto + this.body.slice(fin);
                    var cursor = inicio + texto.length;
                    this.$nextTick(function () {
                        if (!editor) { return; }
                        editor.focus();
                        try { editor.setSelectionRange(cursor, cursor); } catch (ex) { console.info(ex); }
                    });
                },
                insertarVariable: function (nombre) {
                    this.insertarTexto(this.token(nombre));
                },
                /* Envuelve la selección con la marca de WhatsApp; los espacios quedan por fuera para que el formato aplique */
                aplicarFormato: function (marca) {
                    if (this.provider === 'meta') { return; }
                    var editor = this.$refs.editor;
                    if (!editor) { return; }
                    var inicio = editor.selectionStart;
                    var fin    = editor.selectionEnd;
                    var texto  = this.body.slice(inicio, fin);
                    if (texto === '') {
                        this.insertarTexto(marca + marca);
                        var medio = inicio + 1;
                        this.$nextTick(function () { try { editor.setSelectionRange(medio, medio); } catch (ex) { console.info(ex); } });
                        return;
                    }
                    var antes   = texto.match(/^\s*/)[0];
                    var despues = texto.match(/\s*$/)[0];
                    var nucleo  = texto.trim();
                    this.body   = this.body.slice(0, inicio) + antes + marca + nucleo + marca + despues + this.body.slice(fin);
                    var cursor  = inicio + antes.length + nucleo.length + 2;
                    this.$nextTick(function () {
                        editor.focus();
                        try { editor.setSelectionRange(cursor, cursor); } catch (ex) { console.info(ex); }
                    });
                },

                /* ---------------- Imagen ---------------- */
                subirImagen: async function (evento) {
                    var entrada = evento.target;
                    var archivo = entrada.files && entrada.files[0];
                    entrada.value = '';
                    if (!archivo) { return; }

                    /* Validación previa en el navegador; el servidor vuelve a validar */
                    if (TIPOS_IMAGEN.indexOf(archivo.type) === -1) {
                        notificar('Formato no permitido. Use PNG, JPG o WEBP.', 'error');
                        return;
                    }
                    if (archivo.size > MAX_IMAGEN) {
                        notificar('La imagen supera los 5 MB.', 'error');
                        return;
                    }

                    var formulario = new FormData();
                    formulario.append('image', archivo);
                    this.subiendoImagen = true;
                    var json = await llamarArchivo('uploadImage', formulario);
                    this.subiendoImagen = false;

                    if (json.error === true) {
                        notificar(json.message, 'error');
                        return;
                    }
                    if (!json.urlImage) {
                        notificar('El servidor no devolvió la dirección de la imagen.', 'error');
                        return;
                    }
                    this.urlImage     = String(json.urlImage);
                    this.imagenNombre = archivo.name;
                    notificar('Imagen cargada.', 'success');
                },
                quitarImagen: function () {
                    this.urlImage     = '';
                    this.imagenNombre = '';
                },

                /* ---------------- Excel ---------------- */
                leerExcel: async function (evento) {
                    var entrada = evento.target;
                    var archivo = entrada.files && entrada.files[0];
                    entrada.value = '';
                    if (!archivo) { return; }

                    this.errorExcel   = '';
                    this.leyendoExcel = true;
                    try {
                        var XLSX = await cargarSheetJs();
                        var datos = await archivo.arrayBuffer();
                        var libro = XLSX.read(datos, { type: 'array', cellDates: true });
                        var hoja  = libro.SheetNames.length > 0 ? libro.Sheets[libro.SheetNames[0]] : null;
                        if (!hoja) {
                            this.errorExcel = 'El archivo no tiene hojas.';
                            return;
                        }

                        /* raw:true conserva el número completo del teléfono; valorTexto lo convierte a texto */
                        var filas = XLSX.utils.sheet_to_json(hoja, { header: 1, raw: true, defval: '', blankrows: false });
                        if (filas.length === 0) {
                            this.errorExcel = 'El archivo está vacío.';
                            return;
                        }

                        /* Encabezados normalizados; las columnas repetidas o sin nombre se ignoran */
                        var columnas = [];
                        var claves   = filas[0].map(function (encabezado) {
                            var clave = normalizarClave(encabezado);
                            if (clave === '' || columnas.indexOf(clave) !== -1) { return ''; }
                            columnas.push(clave);
                            return clave;
                        });
                        if (columnas.indexOf('telefono') === -1) {
                            var indiceAlias = claves.findIndex(function (clave) { return ALIAS_TELEFONO.indexOf(clave) !== -1; });
                            if (indiceAlias === -1) {
                                this.errorExcel = 'El archivo no tiene la columna "telefono". Descargue la plantilla para ver el formato.';
                                return;
                            }
                            columnas[columnas.indexOf(claves[indiceAlias])] = 'telefono';
                            claves[indiceAlias] = 'telefono';
                        }

                        var registros = [];
                        for (var i = 1; i < filas.length; i++) {
                            var registro = {};
                            var conDatos = false;
                            for (var j = 0; j < claves.length; j++) {
                                if (claves[j] === '') { continue; }
                                var valor = valorTexto(filas[i][j]);
                                registro[claves[j]] = valor;
                                if (valor !== '') { conDatos = true; }
                            }
                            if (conDatos) { registros.push(registro); }
                        }
                        if (registros.length === 0) {
                            this.errorExcel = 'El archivo no tiene filas con datos.';
                            return;
                        }

                        /* Se corta en 1.5 veces el máximo: el servidor rechaza archivos con más filas */
                        var limite           = this.limiteExcel;
                        this.excelTotalLeido = registros.length;
                        this.excelTruncado   = registros.length > limite;
                        this.excelRows       = registros.slice(0, limite);
                        this.excelColumnas   = columnas;
                        this.excelArchivo    = archivo.name;
                        notificar('Archivo cargado: ' + this.entero(this.excelRows.length) + ' filas.', 'success');
                    } catch (ex) {
                        console.error(ex);
                        this.errorExcel = window.XLSX ? 'No se pudo leer el archivo. Verifique que sea un Excel o CSV válido.' : 'No se pudo cargar el lector de Excel. Verifique su conexión a internet.';
                    } finally {
                        this.leyendoExcel = false;
                    }
                },
                quitarExcel: function () {
                    this.excelRows       = [];
                    this.excelColumnas   = [];
                    this.excelArchivo    = '';
                    this.excelTotalLeido = 0;
                    this.excelTruncado   = false;
                    this.errorExcel      = '';
                },
                descargarPlantilla: async function () {
                    this.descargandoPlantilla = true;
                    try {
                        var XLSX = await cargarSheetJs();
                        /* Teléfonos como texto para que Excel no los convierta a notación científica */
                        var hoja = XLSX.utils.aoa_to_sheet([
                            ['telefono', 'mensaje', 'imagen', 'nombre'],
                            ['50588887777', '', '', 'Juan Pérez'],
                            ['50577776666', 'Mensaje propio para esta fila (opcional)', '', 'María López']
                        ]);
                        hoja['!cols'] = [{ wch: 16 }, { wch: 45 }, { wch: 40 }, { wch: 24 }];
                        var libro = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(libro, hoja, 'Destinatarios');
                        XLSX.writeFile(libro, 'plantilla_campana_whatsapp.xlsx');
                    } catch (ex) {
                        console.error(ex);
                        notificar('No se pudo generar la plantilla. Verifique su conexión a internet.', 'error');
                    } finally {
                        this.descargandoPlantilla = false;
                    }
                },

                /* ---------------- Plantillas de Meta ---------------- */
                cargarPlantillas: async function () {
                    this.cargandoPlantillas = true;
                    this.errorPlantillas    = '';
                    var json = await llamar('getTemplates', {});
                    this.cargandoPlantillas = false;

                    if (json.error === true) {
                        this.errorPlantillas = json.message;
                        return;
                    }
                    this.plantillasCargadas = true;
                    this.plantillas         = Array.isArray(json.templates) ? json.templates : [];
                    if (this.plantillas.length === 0) {
                        this.plantillaClave = '';
                        return;
                    }
                    /* Conserva la selección si la plantilla sigue en la lista */
                    var actual = this.template;
                    var indice = this.plantillas.findIndex(function (item) {
                        return item.name === actual.name && item.language === actual.language;
                    });
                    this.plantillaClave = indice === -1 ? '' : String(indice);
                },
                seleccionarPlantilla: function (clave) {
                    this.plantillaClave = clave;
                    this.erroresPaso    = [];
                    if (clave === '') {
                        this.template = plantillaVacia();
                        return;
                    }
                    var origen    = this.plantillas[parseInt(clave, 10)] || {};
                    var variables = Array.isArray(origen.variables) ? origen.variables : [];
                    this.template = {
                        name:         String(origen.name || ''),
                        language:     String(origen.language || ''),
                        headerType:   String(origen.headerType || '').toUpperCase(),
                        variableType: origen.variableType === 'named' ? 'named' : 'positional',
                        body:         String(origen.body || ''),
                        mapping:      this.generarMapeo(variables, [])
                    };
                },
                /* Crea el mapeo para las llaves dadas, conservando lo ya elegido y sugiriendo un origen por nombre */
                generarMapeo: function (llaves, previo) {
                    var self = this;
                    return llaves.map(function (llave) {
                        var clave     = String(llave);
                        var existente = previo.find(function (item) { return item.key === clave; });
                        if (existente) { return existente; }
                        var normal = normalizarClave(clave);
                        if (self.variables.some(function (variable) { return variable.name === normal; })) {
                            return { key: clave, source: 'variable', value: normal };
                        }
                        if (self.columnasVariables.indexOf(normal) !== -1) {
                            return { key: clave, source: 'excel', value: normal };
                        }
                        return { key: clave, source: 'variable', value: '' };
                    });
                },
                cambiarModoPlantilla: function (modo) {
                    if (modo === this.plantillaModo) { return; }
                    this.plantillaModo  = modo;
                    this.plantillaClave = '';
                    this.template       = plantillaVacia();
                    this.erroresPaso    = [];
                    if (modo === 'manual') {
                        this.manualCantidad = 0;
                        this.manualNombres  = '';
                    } else if (!this.plantillasCargadas && this.config.wabaConfigured) {
                        this.cargarPlantillas();
                    }
                },
                regenerarMapeoManual: function () {
                    var llaves = [];
                    if (this.template.variableType === 'positional') {
                        var cantidad = Math.min(20, Math.max(0, parseInt(this.manualCantidad, 10) || 0));
                        this.manualCantidad = cantidad;
                        for (var i = 1; i <= cantidad; i++) { llaves.push(String(i)); }
                    } else {
                        String(this.manualNombres || '').split(',').forEach(function (parte) {
                            var nombre = normalizarClave(parte);
                            if (nombre !== '' && llaves.indexOf(nombre) === -1) { llaves.push(nombre); }
                        });
                    }
                    this.template.body    = '';
                    this.template.mapping = this.generarMapeo(llaves, this.template.mapping);
                },
                cambiarOrigenMapeo: function (item, origen) {
                    item.source = origen;
                    item.value  = '';
                },

                /* ---------------- Resumen ---------------- */
                previsualizar: async function () {
                    if (!this.validarHasta(1, 3)) { return; }
                    var secuencia        = ++secuenciaPreview;
                    var firma            = this.firmaPayload;
                    this.previsualizando = true;
                    this.errorPreview    = '';
                    this.erroresPaso     = [];

                    var json = await llamar('preview', this.construirPayload(false));
                    if (secuencia !== secuenciaPreview) { return; }
                    this.previsualizando = false;

                    /* Error */
                    if (json.error === true) {
                        this.summary      = null;
                        this.errorPreview = json.message;
                        return;
                    }
                    if (!json.summary || typeof json.summary !== 'object') {
                        this.summary      = null;
                        this.errorPreview = MENSAJE_SESION;
                        return;
                    }
                    /* Sin datos / con datos: la plantilla muestra el vacío si totalInput es 0 */
                    this.summary           = json.summary;
                    this.discarded         = Array.isArray(json.discarded) ? json.discarded : [];
                    this.sample            = Array.isArray(json.sample) ? json.sample : [];
                    this.firmaResumen      = firma;
                    this.filtroMotivo      = '';
                    this.paginaDescartados = 1;
                },

                /* ---------------- Envío ---------------- */
                enviar: function () {
                    if (!this.puedeEnviar) { return; }
                    var self      = this;
                    var proveedor = this.provider === 'meta' ? 'Meta Cloud API' : 'Evolution API';
                    /* fnShowConfirm ya codifica el texto al pintarlo con $.tmpl: aquí no se escapa */
                    fnShowConfirm(
                        'Confirmar envío',
                        'Se enviarán ' + this.entero(this.summary.valid) + ' mensajes por ' + proveedor +
                        '. Costo estimado: USD ' + this.fmt(this.summary.cost) +
                        '. Tiempo aproximado: ' + this.entero(this.summary.estimatedMinutes) + ' min. ¿Desea continuar?',
                        function () { self.ejecutarEnvio(); }
                    );
                },
                ejecutarEnvio: async function () {
                    if (this.enviando) { return; }
                    this.enviando    = true;
                    this.erroresPaso = [];
                    var json = await llamar('send', this.construirPayload(true));
                    this.enviando = false;

                    /* Error */
                    if (json.error === true) {
                        this.erroresPaso = [json.message];
                        notificar(json.message, 'error');
                        return;
                    }
                    if (json.summary && typeof json.summary === 'object') { this.summary = json.summary; }
                    /* Sin datos */
                    if (!pv2Num(json.totalQueued)) {
                        this.erroresPaso = ['No se encoló ningún mensaje.'];
                        notificar('No se encoló ningún mensaje.', 'warning');
                        return;
                    }
                    /* Con datos */
                    this.resultado = { totalQueued: pv2Num(json.totalQueued), queue: String(json.queue || '') };
                    notificar(json.message || 'Campaña enviada a la cola.', 'success');
                    this.irArriba();
                },
                nuevaCampania: function () {
                    var estado = estadoInicial();
                    if (!this.config.wabaConfigured) { estado.plantillaModo = 'manual'; }
                    Object.assign(this, estado);
                    this.irArriba();
                }
            },
            mounted: function () {
                var ahora       = new Date();
                this.horaActual = dosDigitos(ahora.getHours()) + ':' + dosDigitos(ahora.getMinutes());
                document.getElementById('app').style.visibility = 'visible';
                pv2BindMobileBar();
            }
        }).mount('#app');
    }

    /* Después del $(function) de theme-genyx/js/app.js, que ya aplicó Uniform a los select existentes */
    $(function () {
        if (typeof Vue === 'undefined') {
            var contenedor = document.getElementById('app');
            contenedor.style.visibility = 'visible';
            contenedor.textContent = 'No se pudo cargar la pantalla. Verifique su conexión a internet y recargue la página.';
            return;
        }
        montar();
    });
}());
</script>
