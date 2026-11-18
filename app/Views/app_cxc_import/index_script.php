<?php
    /**
     * Devuelve el valor listo para incrustar en un script: si el controlador ya lo
     * codificó como JSON (cadena) se usa tal cual; si llega como arreglo u objeto se codifica aquí.
     *
     * @param mixed  $valor   Valor recibido del controlador.
     * @param string $defecto JSON a usar si no llegó nada.
     * @return string
     */
    $cimJson = function ($valor, $defecto) {
        if ($valor === null || $valor === "") {
            return $defecto;
        }
        if (is_string($valor)) {
            return $valor;
        }
        $json = json_encode($valor, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
        return $json === false ? $defecto : $json;
    };
    $cimMaxRows = (isset($maxRows) && (int) $maxRows > 0) ? (int) $maxRows : 5000;
?>
<script>
    var varUseMobile = '<?php echo (isset($useMobile) && $useMobile == "1") ? "1" : "0"; ?>';
</script>
<?php echo view('core_template/posme_v2_common'); ?>
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script>
(function () {
    'use strict';

    var URL_BASE    = '<?php echo base_url(); ?>/app_cxc_import/';
    var URL_SHEETJS = 'https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js';

    /* Datos del controlador (JSON ya seguro para script) */
    var CATALOGOS_SERVIDOR = <?php echo $cimJson($catalogsJson ?? null, '{}'); ?>;
    var MAX_FILAS          = <?php echo $cimMaxRows; ?>;

    var MENSAJE_SESION = 'El servidor no respondió como se esperaba. Es posible que su sesión haya vencido: recargue la página e inicie sesión.';
    var MENSAJE_RED    = 'Error de conexión con el servidor. Verifique su red e intente de nuevo.';
    var MENSAJE_FALLA  = 'No se pudo completar la operación.';

    var MAX_ARCHIVO  = 5 * 1024 * 1024;
    var POR_PAGINA   = 50;
    var TAMANO_LOTE  = 20;
    var MAX_LOG      = 200;
    var LIMITE_LISTA = 300;

    /*
     * Columnas del archivo en el orden de la plantilla. "alias" son encabezados
     * alternos ya normalizados; "texto" indica columnas que se guardan como texto
     * en Excel (para no perder ceros a la izquierda ni caer en notación científica).
     */
    var COLUMNAS = [
        { key: 'codigo',             titulo: 'Código',                 texto: true,  alias: ['codigo', 'cod', 'codigocliente', 'code', 'customernumber'] },
        { key: 'nombre',             titulo: 'Nombre',                 texto: false, alias: ['nombre', 'nombres', 'firstname', 'name'] },
        { key: 'apellido',           titulo: 'Apellido',               texto: false, alias: ['apellido', 'apellidos', 'lastname'] },
        { key: 'telefono',           titulo: 'Teléfono',               texto: true,  alias: ['telefono', 'telefonos', 'celular', 'movil', 'phone', 'tel', 'whatsapp'] },
        { key: 'pais',               titulo: 'País',                   texto: false, alias: ['pais', 'country'] },
        { key: 'departamento',       titulo: 'Departamento',           texto: false, alias: ['departamento', 'depto', 'provincia', 'state'] },
        { key: 'municipio',          titulo: 'Municipio',              texto: false, alias: ['municipio', 'ciudad', 'city'] },
        { key: 'direccion',          titulo: 'Dirección',              texto: false, alias: ['direccion', 'domicilio', 'address'] },
        { key: 'nombreComercial',    titulo: 'Nombre comercial',       texto: false, alias: ['nombrecomercial', 'commercialname'] },
        { key: 'nombreLegal',        titulo: 'Nombre legal',           texto: false, alias: ['nombrelegal', 'razonsocial', 'legalname'] },
        { key: 'tipoIdentificacion', titulo: 'Tipo de identificación', texto: false, alias: ['tipodeidentificacion', 'tipoidentificacion', 'tipoid', 'identificationtype'] },
        { key: 'cedula',             titulo: 'Cédula',                 texto: true,  alias: ['cedula', 'identificacion', 'numerodeidentificacion', 'numeroidentificacion', 'ruc', 'dni', 'identification'] }
    ];

    var ESTADOS = {
        pendiente:   { texto: 'Sin validar',   clase: 'is-muted' },
        valida:      { texto: 'Válida',        clase: 'is-ok' },
        error:       { texto: 'Con error',     clase: 'is-bad' },
        importada:   { texto: 'Importada',     clase: 'is-done' },
        rechazada:   { texto: 'Rechazada',     clase: 'is-bad' },
        fallida:     { texto: 'Falló el envío', clase: 'is-bad' },
        noprocesada: { texto: 'No procesada',  clase: 'is-warn' }
    };
    /* Estados que se pueden (re)enviar a importar: nunca se reenvía una fila importada o rechazada */
    var ESTADOS_IMPORTABLES = ['valida', 'fallida', 'noprocesada'];
    /* Con "Importar también filas con error" se envían además las filas con error o rechazadas */
    var ESTADOS_IMPORTABLES_CON_ERROR = ['error', 'rechazada'];
    var ESTADOS_CON_ERROR   = ['error', 'rechazada', 'fallida'];

    var promesaSheetJs = null;
    var secuenciaLog   = 0;

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

    /* Encabezado o nombre -> llave: minúscula, sin acentos, sin espacios ni símbolos */
    function normalizarClave(texto) {
        var valor = String(texto === null || texto === undefined ? '' : texto);
        if (valor.normalize) {
            valor = valor.normalize('NFD').replace(/[̀-ͯ]/g, '');
        }
        return valor.toLowerCase().replace(/[^a-z0-9_]/g, '');
    }

    function entero(valor) {
        return pv2Fmt(Math.round(pv2Num(valor))).replace(/\.\d+$/, '');
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
     * POST JSON al controlador. Si la petición no llega al servidor (red caída)
     * vuelve {error:true, red:true, message} para distinguirla de un error del servidor.
     *
     * @param {string} metodo Método de app_cxc_import.
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
            return { error: true, red: true, message: MENSAJE_RED };
        }
        return leerRespuesta(res);
    }

    /* Catálogo -> [{id, name, parentID, clave}] con id como texto */
    function normalizarLista(lista) {
        if (!Array.isArray(lista)) { return []; }
        return lista.filter(function (item) {
            return item && item.id !== undefined && item.id !== null && item.name !== undefined;
        }).map(function (item) {
            return {
                id:       String(item.id),
                name:     String(item.name),
                parentID: item.parentID === undefined || item.parentID === null ? '' : String(item.parentID),
                clave:    normalizarClave(item.name)
            };
        });
    }

    function normalizarCatalogos(datos) {
        var origen = (datos && typeof datos === 'object') ? datos : {};
        return {
            identificationType: normalizarLista(origen.identificationType),
            country:            normalizarLista(origen.country),
            state:              normalizarLista(origen.state),
            city:               normalizarLista(origen.city)
        };
    }

    /* Busca en un catálogo por nombre (sin acentos ni espacios) o por id */
    function buscarEnLista(lista, texto) {
        var clave = normalizarClave(texto);
        var crudo = String(texto).trim();
        return lista.filter(function (item) {
            return item.clave === clave || item.id === crudo;
        });
    }

    /* Texto con formato de notación científica que Excel muestra en números largos */
    function esNotacionCientifica(texto) {
        return /^-?\d+(\.\d+)?e[+-]?\d+$/i.test(String(texto).trim());
    }

    /**
     * Valor de la celda como texto. Se usa el texto formateado (raw:false); si una
     * columna de texto llegó en notación científica se recupera el número completo de la celda.
     *
     * @param {Object} hoja  Hoja de SheetJS.
     * @param {Object} XLSX  Librería SheetJS.
     * @param {*}      valor Valor formateado.
     * @param {number} r     Fila absoluta (base 0).
     * @param {number} c     Columna absoluta (base 0).
     * @param {boolean} esTexto La columna es de texto (código, teléfono, cédula).
     * @return {string}
     */
    function valorCelda(hoja, XLSX, valor, r, c, esTexto) {
        var texto = String(valor === null || valor === undefined ? '' : valor).trim();
        if (!esTexto || !esNotacionCientifica(texto)) { return texto; }
        var celda = hoja[XLSX.utils.encode_cell({ r: r, c: c })];
        if (celda && celda.t === 'n' && isFinite(celda.v)) {
            return Number.isInteger(celda.v) ? celda.v.toFixed(0) : String(celda.v);
        }
        return texto;
    }

    function filaNueva(numero, valores) {
        return {
            row:              numero,
            valores:          valores,
            estado:           'pendiente',
            errores:          {},
            erroresGenerales: [],
            avisos:           {},
            customerNumber:   '',
            entityID:         0,
            mensaje:          ''
        };
    }

    function progresoVacio() {
        return { total: 0, procesadas: 0, importados: 0, fallidos: 0, detenido: '' };
    }

    function montar() {
        var app = Vue.createApp({
            template: '#cimTemplate',
            data: function () {
                return {
                    columnas:             COLUMNAS,
                    limiteLista:          LIMITE_LISTA,
                    maxRows:              MAX_FILAS,
                    catalogos:            normalizarCatalogos(CATALOGOS_SERVIDOR),

                    /* Catálogo visible */
                    mostrarCatalogos:     false,
                    catPais:              '',
                    catDepto:             '',
                    catTexto:             '',

                    /* Archivo */
                    descargandoPlantilla: false,
                    leyendo:              false,
                    archivo:              '',
                    errorArchivo:         '',
                    columnasFaltantes:    [],
                    columnasIgnoradas:    [],
                    filas:                [],

                    /* Vista previa */
                    soloErrores:          false,
                    incluirErrores:       false,
                    pagina:               1,

                    /* Validación */
                    validando:            false,
                    validado:             false,
                    errorValidacion:      '',

                    /* Importación */
                    importando:           false,
                    cancelando:           false,
                    importacionIniciada:  false,
                    progreso:             progresoVacio(),
                    log:                  []
                };
            },
            computed: {
                ocupado: function () {
                    return this.leyendo || this.validando || this.importando;
                },
                deptosDelPais: function () {
                    var pais = this.catPais;
                    if (pais === '') { return this.catalogos.state; }
                    return this.catalogos.state.filter(function (item) { return item.parentID === pais; });
                },
                deptosFiltrados: function () {
                    var texto = normalizarClave(this.catTexto);
                    var lista = this.deptosDelPais;
                    if (this.catDepto !== '') {
                        var depto = this.catDepto;
                        lista = lista.filter(function (item) { return item.id === depto; });
                    }
                    if (texto === '') { return lista; }
                    return lista.filter(function (item) { return item.clave.indexOf(texto) !== -1; });
                },
                municipiosFiltrados: function () {
                    var texto = normalizarClave(this.catTexto);
                    var lista;
                    if (this.catDepto !== '') {
                        var depto = this.catDepto;
                        lista = this.catalogos.city.filter(function (item) { return item.parentID === depto; });
                    } else if (this.catPais !== '') {
                        var ids = {};
                        this.deptosDelPais.forEach(function (item) { ids[item.id] = true; });
                        lista = this.catalogos.city.filter(function (item) { return ids[item.parentID] === true; });
                    } else {
                        lista = this.catalogos.city;
                    }
                    if (texto === '') { return lista; }
                    return lista.filter(function (item) { return item.clave.indexOf(texto) !== -1; });
                },
                totalValidas: function () {
                    /* Filas que pasaron la validación del servidor (incluye las ya importadas o pendientes de reintento) */
                    return this.filas.filter(function (fila) { return ['valida', 'importada', 'fallida', 'noprocesada'].indexOf(fila.estado) !== -1; }).length;
                },
                totalErrores: function () {
                    /* Rechazadas por la validación o por el servidor al importar */
                    return this.filas.filter(function (fila) { return fila.estado === 'error' || fila.estado === 'rechazada'; }).length;
                },
                totalImportadas: function () {
                    return this.filas.filter(function (fila) { return fila.estado === 'importada'; }).length;
                },
                totalAvisos: function () {
                    return this.filas.filter(function (fila) { return Object.keys(fila.avisos).length > 0; }).length;
                },
                filasImportables: function () {
                    var estados = this.incluirErrores ? ESTADOS_IMPORTABLES.concat(ESTADOS_IMPORTABLES_CON_ERROR) : ESTADOS_IMPORTABLES;
                    return this.filas.filter(function (fila) { return estados.indexOf(fila.estado) !== -1; });
                },
                totalPendientesImportar: function () {
                    return this.filasImportables.length;
                },
                filasFiltradas: function () {
                    if (!this.soloErrores) { return this.filas; }
                    var self = this;
                    return this.filas.filter(function (fila) { return self.esFilaConError(fila); });
                },
                paginas: function () {
                    return Math.max(1, Math.ceil(this.filasFiltradas.length / POR_PAGINA));
                },
                filasPagina: function () {
                    var inicio = (this.pagina - 1) * POR_PAGINA;
                    return this.filasFiltradas.slice(inicio, inicio + POR_PAGINA);
                },
                puedeValidar: function () {
                    return this.filas.length > 0 && !this.ocupado && !this.importacionIniciada;
                },
                motivoBloqueo: function () {
                    if (this.filas.length === 0)          { return 'Cargue un archivo.'; }
                    if (!this.validado)                   { return 'Valide el archivo antes de importar.'; }
                    if (this.totalPendientesImportar < 1) { return this.incluirErrores ? 'No hay filas pendientes de importar.' : 'No hay filas válidas pendientes de importar.'; }
                    return '';
                },
                puedeImportar: function () {
                    return !this.ocupado && this.motivoBloqueo === '';
                },
                porcentaje: function () {
                    if (this.progreso.total < 1) { return 0; }
                    return Math.min(100, Math.round(this.progreso.procesadas * 100 / this.progreso.total));
                },
                resumenFinal: function () {
                    return entero(this.progreso.importados) + ' importados, ' + entero(this.progreso.fallidos) + ' fallidos, ' +
                        entero(this.progreso.total - this.progreso.procesadas) + ' sin procesar';
                }
            },
            watch: {
                catPais: function () {
                    this.catDepto = '';
                },
                soloErrores: function () {
                    this.pagina = 1;
                },
                /* Si el filtro deja menos páginas, la página actual se ajusta */
                paginas: function (total) {
                    if (this.pagina > total) { this.pagina = total; }
                }
            },
            methods: {
                entero: entero,
                textoEstado: function (estado) {
                    return (ESTADOS[estado] || ESTADOS.pendiente).texto;
                },
                claseEstado: function (estado) {
                    return (ESTADOS[estado] || ESTADOS.pendiente).clase;
                },
                /* Antes de validar, "con error" incluye las advertencias del navegador */
                esFilaConError: function (fila) {
                    if (ESTADOS_CON_ERROR.indexOf(fila.estado) !== -1) { return true; }
                    return !this.validado && Object.keys(fila.avisos).length > 0;
                },
                agregarLog: function (tipo, texto) {
                    this.log.push({ id: ++secuenciaLog, tipo: tipo, texto: texto });
                    if (this.log.length > MAX_LOG) { this.log.splice(0, this.log.length - MAX_LOG); }
                },

                /* ---------------- Plantilla ---------------- */
                descargarPlantilla: async function () {
                    this.descargandoPlantilla = true;
                    try {
                        var XLSX = await cargarSheetJs();
                        var cat  = this.catalogos;

                        /* Ejemplo coherente: primer país, un departamento de ese país y un municipio de ese departamento */
                        var tipo   = cat.identificationType.length > 0 ? cat.identificationType[0] : null;
                        var pais   = cat.country.length > 0 ? cat.country[0] : null;
                        var depto  = (pais && cat.state.find(function (item) { return item.parentID === pais.id; })) || cat.state[0] || null;
                        var muni   = (depto && cat.city.find(function (item) { return item.parentID === depto.id; })) || cat.city[0] || null;
                        var nTipo  = tipo ? tipo.name : '';
                        var nPais  = pais ? pais.name : '';
                        var nDepto = depto ? depto.name : '';
                        var nMuni  = muni ? muni.name : '';

                        var filas = [
                            COLUMNAS.map(function (col) { return col.titulo; }),
                            ['CLI-0001', 'Juan', 'Pérez', '88887777', nPais, nDepto, nMuni, 'Del parque central 2 cuadras al sur', 'Pulpería Juan', 'Juan Pérez', nTipo, '0010101800001A'],
                            ['CLI-0002', 'María', 'López', '77776666', nPais, nDepto, nMuni, 'Frente a la escuela', '', 'María López', nTipo, '0012002900002B']
                        ];
                        var hoja = XLSX.utils.aoa_to_sheet(filas);
                        this.marcarColumnasTexto(XLSX, hoja, filas.length);
                        hoja['!cols'] = COLUMNAS.map(function (col) { return { wch: Math.max(14, col.titulo.length + 4) }; });

                        var libro = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(libro, hoja, 'Clientes');
                        XLSX.writeFile(libro, 'plantilla_importacion_clientes.xlsx');
                    } catch (ex) {
                        console.error(ex);
                        notificar('No se pudo generar la plantilla. Verifique su conexión a internet.', 'error');
                    } finally {
                        this.descargandoPlantilla = false;
                    }
                },
                /* Código, teléfono y cédula como texto: Excel no les quita ceros ni los pasa a notación científica */
                marcarColumnasTexto: function (XLSX, hoja, totalFilas) {
                    COLUMNAS.forEach(function (col, c) {
                        if (!col.texto) { return; }
                        for (var r = 1; r < totalFilas; r++) {
                            var direccion = XLSX.utils.encode_cell({ r: r, c: c });
                            var celda     = hoja[direccion];
                            if (!celda) { continue; }
                            hoja[direccion] = { t: 's', v: String(celda.v), z: '@' };
                        }
                    });
                },

                /* ---------------- Archivo ---------------- */
                leerArchivo: async function (evento) {
                    var entrada = evento.target;
                    var archivo = entrada.files && entrada.files[0];
                    entrada.value = '';
                    if (!archivo) { return; }

                    /* Un archivo nuevo invalida la validación y la vista anterior */
                    this.quitarArchivo();

                    if (!/\.(xlsx|xls|csv)$/i.test(archivo.name)) {
                        this.errorArchivo = 'Formato no permitido. Use un archivo .xlsx, .xls o .csv.';
                        return;
                    }
                    if (archivo.size > MAX_ARCHIVO) {
                        this.errorArchivo = 'El archivo supera los 5 MB. Divídalo en archivos más pequeños.';
                        return;
                    }

                    this.leyendo = true;
                    try {
                        var XLSX  = await cargarSheetJs();
                        var datos = await archivo.arrayBuffer();
                        var libro = XLSX.read(datos, { type: 'array' });
                        var hoja  = libro.SheetNames.length > 0 ? libro.Sheets[libro.SheetNames[0]] : null;
                        if (!hoja || !hoja['!ref']) {
                            this.errorArchivo = 'El archivo no tiene hojas con datos.';
                            return;
                        }

                        /* raw:false entrega el texto tal como se ve en Excel (sin convertir fechas ni números) */
                        var rango = XLSX.utils.decode_range(hoja['!ref']);
                        var filas = XLSX.utils.sheet_to_json(hoja, { header: 1, raw: false, defval: '', blankrows: false });
                        if (filas.length === 0) {
                            this.errorArchivo = 'El archivo está vacío.';
                            return;
                        }

                        /* Encabezados: cada uno se asocia a una columna conocida por su nombre o alias */
                        var mapa      = [];
                        var usadas    = {};
                        var ignoradas = [];
                        filas[0].forEach(function (encabezado, j) {
                            var clave = normalizarClave(encabezado);
                            if (clave === '') { mapa[j] = null; return; }
                            var columna = COLUMNAS.find(function (col) {
                                return normalizarClave(col.titulo) === clave || col.alias.indexOf(clave) !== -1;
                            });
                            if (!columna || usadas[columna.key]) {
                                mapa[j] = null;
                                ignoradas.push(String(encabezado).trim());
                                return;
                            }
                            usadas[columna.key] = true;
                            mapa[j] = columna;
                        });
                        if (!usadas.nombre) {
                            this.errorArchivo = 'El archivo no tiene la columna "Nombre" en la primera fila. Descargue la plantilla para ver el formato.';
                            return;
                        }

                        var registros = [];
                        for (var i = 1; i < filas.length; i++) {
                            var origen = filas[i];
                            /* __rowNum__ es la fila real de la hoja (base 0); con blankrows:false el índice no coincide */
                            var filaHoja = (origen.__rowNum__ !== undefined) ? origen.__rowNum__ : (rango.s.r + i);
                            var valores  = {};
                            var conDatos = false;
                            COLUMNAS.forEach(function (col) { valores[col.key] = ''; });
                            for (var j = 0; j < mapa.length; j++) {
                                if (!mapa[j]) { continue; }
                                var valor = valorCelda(hoja, XLSX, origen[j], filaHoja, rango.s.c + j, mapa[j].texto);
                                valores[mapa[j].key] = valor;
                                if (valor !== '') { conDatos = true; }
                            }
                            if (conDatos) { registros.push(filaNueva(filaHoja + 1, valores)); }
                        }
                        if (registros.length === 0) {
                            this.errorArchivo = 'El archivo no tiene filas con datos debajo del encabezado.';
                            return;
                        }
                        if (registros.length > MAX_FILAS) {
                            this.errorArchivo = 'El archivo tiene ' + entero(registros.length) + ' filas y el máximo por archivo es ' + entero(MAX_FILAS) + '. Divídalo en varios archivos.';
                            return;
                        }

                        /* Revisión ligera en el navegador: solo avisa, la validación real es del servidor */
                        var self = this;
                        registros.forEach(function (fila) { fila.avisos = self.avisosFila(fila.valores); });

                        this.columnasFaltantes = COLUMNAS.filter(function (col) { return !usadas[col.key]; }).map(function (col) { return col.titulo; });
                        this.columnasIgnoradas = ignoradas;
                        this.filas             = registros;
                        this.archivo           = archivo.name;
                        notificar('Archivo cargado: ' + entero(registros.length) + ' filas.', 'success');
                    } catch (ex) {
                        console.error(ex);
                        this.errorArchivo = window.XLSX ? 'No se pudo leer el archivo. Verifique que sea un Excel o CSV válido.' : 'No se pudo cargar el lector de Excel. Verifique su conexión a internet.';
                    } finally {
                        this.leyendo = false;
                    }
                },
                quitarArchivo: function () {
                    if (this.importando) { return; }
                    this.filas               = [];
                    this.archivo             = '';
                    this.errorArchivo        = '';
                    this.columnasFaltantes   = [];
                    this.columnasIgnoradas   = [];
                    this.soloErrores         = false;
                    this.pagina              = 1;
                    this.validado            = false;
                    this.errorValidacion     = '';
                    this.importacionIniciada = false;
                    this.progreso            = progresoVacio();
                    this.log                 = [];
                },
                /**
                 * Advertencias por campo contra los catálogos recibidos. No bloquea nada:
                 * el servidor decide si la fila es válida.
                 */
                avisosFila: function (valores) {
                    var cat    = this.catalogos;
                    var avisos = {};
                    if (valores.nombre === '') { avisos.nombre = 'El nombre es obligatorio.'; }

                    if (valores.tipoIdentificacion !== '' && cat.identificationType.length > 0 && buscarEnLista(cat.identificationType, valores.tipoIdentificacion).length === 0) {
                        avisos.tipoIdentificacion = 'No está en la lista de tipos de identificación.';
                    }

                    var paises = [];
                    if (valores.pais !== '' && cat.country.length > 0) {
                        paises = buscarEnLista(cat.country, valores.pais);
                        if (paises.length === 0) { avisos.pais = 'No está en la lista de países.'; }
                    }

                    var deptos = [];
                    if (valores.departamento !== '' && cat.state.length > 0) {
                        deptos = buscarEnLista(cat.state, valores.departamento);
                        if (deptos.length === 0) {
                            avisos.departamento = 'No está en la lista de departamentos.';
                        } else if (paises.length > 0) {
                            deptos = deptos.filter(function (d) { return paises.some(function (p) { return p.id === d.parentID; }); });
                            if (deptos.length === 0) { avisos.departamento = 'No pertenece al país indicado.'; }
                        }
                    }

                    if (valores.municipio !== '' && cat.city.length > 0) {
                        var munis = buscarEnLista(cat.city, valores.municipio);
                        if (munis.length === 0) {
                            avisos.municipio = 'No está en la lista de municipios.';
                        } else if (deptos.length > 0 && !munis.some(function (m) { return deptos.some(function (d) { return d.id === m.parentID; }); })) {
                            avisos.municipio = 'No pertenece al departamento indicado.';
                        }
                    }
                    return avisos;
                },
                payloadFila: function (fila) {
                    var datos = { row: fila.row };
                    COLUMNAS.forEach(function (col) { datos[col.key] = fila.valores[col.key]; });
                    return datos;
                },

                /* ---------------- Validación en el servidor ---------------- */
                validar: async function () {
                    if (!this.puedeValidar) { return; }
                    var self = this;
                    this.validando       = true;
                    this.errorValidacion = '';

                    var json = await llamar('validateRows', { rows: this.filas.map(function (fila) { return self.payloadFila(fila); }) });
                    this.validando = false;

                    /* Error */
                    if (json.error === true) {
                        this.errorValidacion = json.message;
                        notificar(json.message, 'error');
                        return;
                    }
                    /* Sin datos */
                    if (!json.data || !Array.isArray(json.data.rows) || json.data.rows.length === 0) {
                        this.errorValidacion = 'El servidor no devolvió el resultado de la validación. Intente de nuevo.';
                        return;
                    }

                    /* Con datos: cada fila toma su resultado; los errores sin columna conocida van al estado */
                    var resultados = {};
                    json.data.rows.forEach(function (item) {
                        if (item && item.row !== undefined) { resultados[String(item.row)] = item; }
                    });
                    this.filas.forEach(function (fila) {
                        var item = resultados[String(fila.row)];
                        fila.errores          = {};
                        fila.erroresGenerales = [];
                        fila.mensaje          = '';
                        if (!item) {
                            fila.estado = 'error';
                            fila.erroresGenerales.push('El servidor no validó esta fila.');
                            return;
                        }
                        var errores = Array.isArray(item.errors) ? item.errors : [];
                        errores.forEach(function (error) {
                            var campo   = error && error.field ? String(error.field) : '';
                            var mensaje = error && error.message ? String(error.message) : 'Dato no válido.';
                            var existe  = COLUMNAS.some(function (col) { return col.key === campo; });
                            if (existe) {
                                fila.errores[campo] = fila.errores[campo] ? fila.errores[campo] + ' ' + mensaje : mensaje;
                            } else {
                                fila.erroresGenerales.push(mensaje);
                            }
                        });
                        fila.estado = (item.ok === true && errores.length === 0) ? 'valida' : 'error';
                    });

                    this.validado = true;
                    this.pagina   = 1;
                    if (this.totalErrores > 0) {
                        notificar('Validación terminada: ' + entero(this.totalValidas) + ' válidas y ' + entero(this.totalErrores) + ' con error.', 'warning');
                    } else {
                        notificar('Validación terminada: las ' + entero(this.totalValidas) + ' filas son válidas.', 'success');
                    }
                },

                /* ---------------- Importación por lotes ---------------- */
                confirmarImportacion: function () {
                    if (!this.puedeImportar) { return; }
                    var self       = this;
                    var conErrores = this.incluirErrores ? this.filasImportables.filter(function (fila) { return ESTADOS_IMPORTABLES_CON_ERROR.indexOf(fila.estado) !== -1; }).length : 0;
                    var avisoError = conErrores > 0 ? ' Incluye ' + entero(conErrores) + ' filas con error: se corregirán con valores por defecto y los repetidos se crearán igual.' : '';
                    /* fnShowConfirm ya codifica el texto al pintarlo: aquí no se escapa */
                    fnShowConfirm(
                        'Importar clientes',
                        'Se crearán ' + entero(this.totalPendientesImportar) + ' clientes en lotes de ' + TAMANO_LOTE + '.' + avisoError + ' No cierre la página mientras se importan. ¿Desea continuar?',
                        function () { self.importar(); }
                    );
                },
                importar: async function () {
                    if (this.importando) { return; }
                    var pendientes = this.filasImportables.slice();
                    if (pendientes.length === 0) { return; }

                    this.importando          = true;
                    this.cancelando          = false;
                    this.importacionIniciada = true;
                    this.progreso            = progresoVacio();
                    this.progreso.total      = pendientes.length;
                    this.log                 = [];
                    $('#cimModalProgreso').modal({ backdrop: 'static', keyboard: false, show: true });

                    var totalLotes = Math.ceil(pendientes.length / TAMANO_LOTE);
                    var indice     = 0;
                    var numLote    = 0;

                    while (indice < pendientes.length) {
                        /* Cancelar se respeta entre lotes: el lote en curso siempre termina */
                        if (this.cancelando) {
                            this.agregarLog('warn', 'Importación cancelada por el usuario.');
                            break;
                        }
                        var lote = pendientes.slice(indice, indice + TAMANO_LOTE);
                        numLote++;
                        var self = this;
                        var json = await llamar('importRows', { rows: lote.map(function (fila) { return self.payloadFila(fila); }), incluirErrores: this.incluirErrores });

                        /* Error de red: el lote se marca como fallido (se puede reintentar) y se sigue con el siguiente */
                        if (json.error === true && json.red === true) {
                            lote.forEach(function (fila) {
                                fila.estado  = 'fallida';
                                fila.mensaje = 'No se pudo enviar: error de conexión.';
                            });
                            this.progreso.procesadas += lote.length;
                            this.progreso.fallidos   += lote.length;
                            this.agregarLog('error', 'Lote ' + numLote + ' de ' + totalLotes + ': error de conexión, ' + lote.length + ' filas marcadas como fallidas.');
                            indice += lote.length;
                            continue;
                        }
                        /* Error global (sesión, permiso, licencia): se detiene; el lote actual no se procesó */
                        if (json.error === true) {
                            this.progreso.detenido = json.message;
                            this.agregarLog('error', 'Importación detenida: ' + json.message);
                            break;
                        }
                        /* Sin datos: respuesta inesperada, se detiene para no duplicar clientes */
                        if (!Array.isArray(json.data)) {
                            this.progreso.detenido = MENSAJE_SESION;
                            this.agregarLog('error', 'Importación detenida: respuesta inesperada del servidor.');
                            break;
                        }

                        /* Con datos */
                        this.aplicarResultadoLote(lote, json.data, numLote, totalLotes);
                        indice += lote.length;
                    }

                    /* Lo que no llegó a enviarse queda como no procesado (se puede reintentar) */
                    var sinProcesar = pendientes.slice(indice);
                    sinProcesar.forEach(function (fila) {
                        fila.estado  = 'noprocesada';
                        fila.mensaje = 'No se procesó: la importación se detuvo antes.';
                    });

                    this.importando = false;
                    this.cancelando = false;
                    this.agregarLog('info', 'Fin: ' + this.resumenFinal + '.');
                    notificar('Importación finalizada: ' + this.resumenFinal + '.', this.progreso.fallidos > 0 || sinProcesar.length > 0 ? 'warning' : 'success');
                    this.$nextTick(function () { pv2FocusField('#cimBtnResultados'); });
                },
                aplicarResultadoLote: function (lote, resultados, numLote, totalLotes) {
                    var self      = this;
                    var porFila   = {};
                    var correctos = 0;
                    var fallidos  = 0;
                    resultados.forEach(function (item) {
                        if (item && item.row !== undefined) { porFila[String(item.row)] = item; }
                    });
                    lote.forEach(function (fila) {
                        var item = porFila[String(fila.row)];
                        if (item && item.ok === true) {
                            fila.estado         = 'importada';
                            fila.customerNumber = item.customerNumber ? String(item.customerNumber) : '';
                            fila.entityID       = pv2Num(item.entityID);
                            fila.mensaje        = item.message ? String(item.message) : 'Importado';
                            correctos++;
                            return;
                        }
                        fila.estado  = 'rechazada';
                        fila.mensaje = item && item.message ? String(item.message) : 'El servidor no devolvió resultado para esta fila.';
                        fallidos++;
                        self.agregarLog('error', 'Fila ' + entero(fila.row) + ': ' + fila.mensaje);
                    });
                    this.progreso.procesadas += lote.length;
                    this.progreso.importados += correctos;
                    this.progreso.fallidos   += fallidos;
                    this.agregarLog(fallidos > 0 ? 'warn' : 'ok', 'Lote ' + numLote + ' de ' + totalLotes + ': ' + correctos + ' importados' + (fallidos > 0 ? ', ' + fallidos + ' fallidos.' : '.'));
                },
                cancelarImportacion: function () {
                    if (this.importando) { this.cancelando = true; }
                },
                cerrarModal: function () {
                    if (this.importando) { return; }
                    $('#cimModalProgreso').modal('hide');
                },

                /* ---------------- Resultados ---------------- */
                descargarResultados: async function () {
                    try {
                        var XLSX  = await cargarSheetJs();
                        var self  = this;
                        var filas = [['Fila', 'Código', 'Nombre', 'Apellido', 'Cédula', 'Código de sistema', 'Estado', 'Mensaje']];
                        this.filas.forEach(function (fila) {
                            var mensajes = fila.erroresGenerales.slice();
                            COLUMNAS.forEach(function (col) {
                                if (fila.errores[col.key]) { mensajes.push(col.titulo + ': ' + fila.errores[col.key]); }
                            });
                            if (fila.mensaje) { mensajes.push(fila.mensaje); }
                            filas.push([
                                fila.row,
                                fila.valores.codigo,
                                fila.valores.nombre,
                                fila.valores.apellido,
                                fila.valores.cedula,
                                fila.customerNumber,
                                self.textoEstado(fila.estado),
                                mensajes.join(' | ')
                            ]);
                        });
                        var hoja = XLSX.utils.aoa_to_sheet(filas);
                        /* Código, cédula y código de sistema como texto */
                        [1, 4, 5].forEach(function (c) {
                            for (var r = 1; r < filas.length; r++) {
                                var direccion = XLSX.utils.encode_cell({ r: r, c: c });
                                if (hoja[direccion]) { hoja[direccion] = { t: 's', v: String(hoja[direccion].v), z: '@' }; }
                            }
                        });
                        hoja['!cols'] = [{ wch: 8 }, { wch: 14 }, { wch: 22 }, { wch: 22 }, { wch: 18 }, { wch: 18 }, { wch: 16 }, { wch: 60 }];
                        var libro = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(libro, hoja, 'Resultados');
                        XLSX.writeFile(libro, 'resultado_importacion_clientes.xlsx');
                    } catch (ex) {
                        console.error(ex);
                        notificar('No se pudo generar el archivo de resultados. Verifique su conexión a internet.', 'error');
                    }
                }
            },
            mounted: function () {
                document.getElementById('app').style.visibility = 'visible';
                pv2BindMobileBar();
            }
        });
        var vm = app.mount('#app');

        /* Avisa antes de salir mientras se lee, valida o importa */
        window.addEventListener('beforeunload', function (evento) {
            if (vm.validando || vm.importando) {
                evento.preventDefault();
                evento.returnValue = '';
                return '';
            }
            return undefined;
        });
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
