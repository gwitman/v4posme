<script>
    var varUseMobile = '<?php echo (isset($useMobile) && $useMobile == "1") ? "1" : "0"; ?>';
</script>
<?php echo view('core_template/posme_v2_common'); ?>
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/posme-report-builder.js?v=2"></script>
<script>
(function () {
    'use strict';

    var URL_BASE = '<?php echo base_url(); ?>/app_builder_report/';
    var PRB      = window.PosmeReportBuilder;

    /* Catálogos del modal Guardar (JSON ya seguro para <script>) y si el usuario administra la compañía */
    var CATALOGOS_SERVIDOR = <?php echo isset($builderCatalogs) ? $builderCatalogs : 'null'; ?>;
    var ES_ADMIN_COMPANIA  = <?php echo !empty($isAdminCompany) ? 'true' : 'false'; ?>;

    /* Cada catálogo llega siempre como lista; si uno falló viene vacío y su mensaje está en errors */
    function normalizarCatalogos(datos) {
        var origen    = (datos && typeof datos === 'object') ? datos : {};
        var resultado = { errors: Array.isArray(origen.errors) ? origen.errors : [] };
        ['viewType', 'dashboard', 'cardType', 'width', 'chartType'].forEach(function (clave) {
            resultado[clave] = Array.isArray(origen[clave]) ? origen[clave] : [];
        });
        return resultado;
    }

    var MENSAJE_SESION = 'El servidor no respondió como se esperaba. Es posible que su sesión haya vencido: recargue la página e inicie sesión.';
    var MENSAJE_RED    = 'Error de conexión con el servidor. Verifique su red e intente de nuevo.';
    var MENSAJE_FALLA  = 'No se pudo completar la operación.';

    /* Contadores para descartar respuestas viejas: solo vale la de la última petición */
    var secuenciaGenerar = 0;
    var secuenciaEditor  = 0;

    /**
     * POST JSON al controlador. Nunca lanza excepción: cualquier falla (red, HTML por sesión
     * vencida, JSON inválido) vuelve como {error:true, message}, así cada llamada solo atiende
     * los casos error / sin datos / con datos.
     *
     * @param {string} metodo Método de app_builder_report.
     * @param {Object} datos Cuerpo de la petición.
     * @return {Promise<Object>} Respuesta del servidor.
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

    /* El toast pinta con innerHTML: todo texto se escapa antes de entregarlo */
    function notificar(mensaje, tipo) {
        fnShowNotification(PRB.escaparHtml(mensaje), tipo);
    }

    function montar() {
        Vue.createApp({
            template: '#prbTemplate',
            data: function () {
                return {
                    seccion:           'vistas',

                    vistas:            [],
                    textoVista:        '',
                    cargandoVistas:    false,
                    errorVistas:       '',

                    consultas:         [],
                    cargandoConsultas: false,
                    errorConsultas:    '',

                    /* Editor. modo: nuevo | edicion | consulta */
                    modo:              'nuevo',
                    reportBuilderID:   null,
                    dataViewID:        null,
                    vistaEtiqueta:     '',
                    nombre:            '',
                    catalogo:          [],
                    operadoresPermitidos: null,
                    columnas:          [],
                    orden:             [],
                    filtros:           [],
                    campoNuevo:        '',
                    firmaGuardada:     '',
                    cargandoConfig:    false,
                    guardando:         false,
                    exportando:        false,
                    avisos:            [],
                    erroresConfig:     [],
                    errorGeneral:      '',

                    /* Modal Guardar: presentacion es lo guardado; formGuardar, la copia que edita el modal */
                    catalogos:          normalizarCatalogos(CATALOGOS_SERVIDOR),
                    esAdminCompania:    ES_ADMIN_COMPANIA,
                    formatosIndicador:  PRB.FORMATOS_INDICADOR,
                    presentacion:       PRB.presentacionVacia(),
                    formGuardar:        PRB.presentacionVacia(),
                    compartidaOriginal: false,
                    erroresModal:       [],
                    avisosPresentacion: [],

                    /* Resultado */
                    generando:         false,
                    generado:          false,
                    errorResultado:    '',
                    columnasResultado: [],
                    filas:             [],
                    page:              1,
                    pageSize:          25,
                    pageSizeMax:       null,
                    hasMore:           false,
                    total:             null,
                    totalIsCapped:     false,
                    maxRows:           null,
                    configGenerada:    null,
                    firmaGenerada:     ''
                };
            },
            computed: {
                hayVista: function () {
                    return this.dataViewID !== null;
                },
                esConsulta: function () {
                    return this.modo === 'consulta';
                },
                etiquetaModo: function () {
                    if (this.modo === 'consulta') { return 'Consulta'; }
                    if (this.modo === 'edicion')  { return 'Edición'; }
                    return 'Nuevo';
                },
                tituloEncabezado: function () {
                    if (!this.hayVista) { return 'Nuevo reporte'; }
                    return this.nombre.trim() !== '' ? this.nombre : 'Reporte sin nombre';
                },
                vistasFiltradas: function () {
                    return PRB.filtrarVistas(this.vistas, this.textoVista);
                },
                configActual: function () {
                    return PRB.construirConfig({
                        dataViewID: this.dataViewID,
                        columnas:   this.columnas,
                        orden:      this.orden,
                        filtros:    this.filtros
                    }, this.catalogo);
                },
                /* Lo que se guarda: a diferencia de configActual, conserva los filtros sin valor */
                configParaGuardar: function () {
                    return PRB.construirConfigParaGuardar({
                        dataViewID: this.dataViewID,
                        columnas:   this.columnas,
                        orden:      this.orden,
                        filtros:    this.filtros
                    }, this.catalogo);
                },
                firmaConfig: function () {
                    return JSON.stringify(this.configActual);
                },
                firmaEditor: function () {
                    return JSON.stringify({ nombre: this.nombre.trim(), config: this.configParaGuardar });
                },
                /* En modo consulta los valores de filtros no se guardan: no cuentan como cambios */
                dirty: function () {
                    return this.hayVista && !this.esConsulta && this.firmaEditor !== this.firmaGuardada;
                },
                resultStale: function () {
                    return this.generado && this.firmaConfig !== this.firmaGenerada;
                },
                opcionesDeOrden: function () {
                    return PRB.opcionesOrden(this.columnas, this.catalogo);
                },
                /* Claves de orden que ningún orden usa todavía (habilita "Agregar orden") */
                opcionesOrdenLibres: function () {
                    return PRB.opcionesOrdenLibres(this.opcionesDeOrden, this.orden, null);
                },
                /* Combo "Agregar columna": sin los campos ya mostrados (salvo los usados con función) */
                catalogoDisponible: function () {
                    return PRB.camposDisponibles(this.catalogo, this.columnas);
                },
                /* Mismas reglas que Generar: habilita "Abrir en nueva página" */
                configValida: function () {
                    return this.hayVista && PRB.validarConfig(this.configActual, this.catalogo).length === 0;
                },
                /* Opciones de los campos de la presentación, según config.columns actual */
                columnasPresentacion: function () {
                    return PRB.opcionesColumnasPresentacion(this.columnas, this.catalogo);
                },
                tipoTarjetaModal: function () {
                    return PRB.tipoTarjeta(PRB.buscarItemCatalogo(this.catalogos.cardType, this.formGuardar.cardTypeCatalogItemID));
                },
                /* Cambia al agregar o quitar columnas (no al reordenarlas) */
                firmaUidsColumnas: function () {
                    return this.columnas.map(function (columna) { return columna.uid; }).sort().join(',');
                },
                avisoMoneda: function () {
                    return PRB.debeAvisarMoneda(this.configActual, this.catalogo);
                },
                resumen: function () {
                    return PRB.resumirConfig(this.configActual, this.catalogo);
                },
                rango: function () {
                    return PRB.rangoPaginacion(this.page, this.pageSize, this.filas.length, this.total, this.totalIsCapped);
                },
                tamanios: function () {
                    return PRB.tamaniosPagina(this.pageSizeMax);
                },
                hayPaginaSiguiente: function () {
                    return PRB.puedeAvanzar(this.page, this.pageSize, this.hasMore, this.maxRows);
                },
                limitePantalla: function () {
                    return this.hasMore && !this.hayPaginaSiguiente;
                },
                maxRowsTexto: function () {
                    return PRB.formatearEntero(this.maxRows);
                }
            },
            watch: {
                /* Si se quita de Mostrar una columna usada en la presentación, ese campo se limpia y se avisa */
                firmaUidsColumnas: function () {
                    var limpiados = PRB.limpiarPresentacion(this.presentacion, this.columnas);
                    if (limpiados.length > 0) {
                        var aviso = 'Se quitó una columna usada en la presentación del dashboard (' + limpiados.join(', ') + '). Revísela al guardar.';
                        this.avisosPresentacion = [aviso];
                        notificar(aviso, 'warning');
                    }
                }
            },
            methods: {
                /* ---------------- Ayudas de plantilla ---------------- */
                etiquetaCatalogo: function (nombre) {
                    return PRB.etiquetaCampo(this.catalogo, nombre);
                },
                tipoCampo: function (nombre) {
                    var campo = PRB.buscarCampo(this.catalogo, nombre);
                    return campo ? campo.type : 'text';
                },
                opcionesModo: function (nombre) {
                    return PRB.opcionesModo(this.tipoCampo(nombre));
                },
                operadoresDe: function (nombre) {
                    return PRB.operadoresDeTipo(this.tipoCampo(nombre), this.operadoresPermitidos);
                },
                etiquetaOperador: function (filtro) {
                    var lista = this.operadoresDe(filtro.column);
                    for (var i = 0; i < lista.length; i += 1) {
                        if (lista[i].value === filtro.operator) {
                            return lista[i].label;
                        }
                    }
                    return '';
                },
                tieneFuncion: function (columna) {
                    return PRB.esFuncion(columna.modo);
                },
                /* Cada combo de Ordenar excluye lo que usan los otros órdenes y conserva su propia clave */
                opcionesOrdenDe: function (item) {
                    return PRB.opcionesOrdenLibres(this.opcionesDeOrden, this.orden, item.uid);
                },
                opcionesFecha: function (filtro, extremo) {
                    return PRB.opcionesFechaDinamica(filtro.operator, extremo);
                },
                celda: function (fila, columna) {
                    return PRB.formatearCelda(fila[columna.key], columna);
                },
                nombreVista: function (dataViewID) {
                    for (var i = 0; i < this.vistas.length; i += 1) {
                        if (this.vistas[i].dataViewID == dataViewID) {
                            return this.vistas[i].label;
                        }
                    }
                    return '';
                },
                fechaConsulta: function (fecha) {
                    return PRB.formatearFecha(fecha);
                },
                irAElemento: function (id) {
                    this.$nextTick(function () {
                        var elemento = document.getElementById(id);
                        if (elemento && elemento.scrollIntoView) {
                            elemento.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    });
                },
                mostrarError: function (mensaje) {
                    this.errorGeneral = mensaje;
                    notificar(mensaje, 'error');
                },
                /* Si hay cambios sin guardar pide confirmación antes de continuar */
                confirmarCambio: function (accion) {
                    if (!this.dirty) {
                        accion();
                        return;
                    }
                    fnShowConfirm('Cambios sin guardar', 'Tiene cambios sin guardar que se perderán. ¿Desea continuar?', accion);
                },

                /* ---------------- Listados ---------------- */
                cargarVistas: async function () {
                    this.cargandoVistas = true;
                    this.errorVistas    = '';
                    try {
                        var json = await llamar('getViews');
                        if (json.error === true) {
                            this.vistas      = [];
                            this.errorVistas = json.message;
                            return;
                        }
                        this.vistas = json.views || [];
                    } finally {
                        this.cargandoVistas = false;
                    }
                },
                cargarConsultas: async function () {
                    this.cargandoConsultas = true;
                    this.errorConsultas    = '';
                    try {
                        var json = await llamar('listQueries');
                        if (json.error === true) {
                            this.consultas      = [];
                            this.errorConsultas = json.message;
                            return;
                        }
                        this.consultas = json.queries || [];
                    } finally {
                        this.cargandoConsultas = false;
                    }
                },

                /* ---------------- Apertura del editor ---------------- */
                limpiarResultado: function () {
                    secuenciaGenerar += 1;
                    this.generando         = false;
                    this.generado          = false;
                    this.errorResultado    = '';
                    this.columnasResultado = [];
                    this.filas             = [];
                    this.page              = 1;
                    this.hasMore           = false;
                    this.total             = null;
                    this.totalIsCapped     = false;
                    this.configGenerada    = null;
                    this.firmaGenerada     = '';
                },
                limpiarEditor: function () {
                    secuenciaEditor += 1;
                    this.cargandoConfig  = false;
                    this.modo            = 'nuevo';
                    this.reportBuilderID = null;
                    this.dataViewID      = null;
                    this.vistaEtiqueta   = '';
                    this.nombre          = '';
                    this.catalogo        = [];
                    this.columnas        = [];
                    this.orden           = [];
                    this.filtros         = [];
                    this.avisos          = [];
                    this.erroresConfig   = [];
                    this.firmaGuardada   = '';
                    this.presentacion       = PRB.presentacionVacia();
                    this.avisosPresentacion = [];
                    this.limpiarResultado();
                },
                /* Los operadores vienen por tipo y limitan los combos de filtros */
                aplicarCatalogo: function (json) {
                    this.catalogo             = json.columns || [];
                    this.operadoresPermitidos = json.operators || null;
                    this.aplicarPageSizeMax(json.pageSizeMax);
                },
                /* pageSizeMax es opcional en la respuesta; el tamaño elegido nunca lo pasa */
                aplicarPageSizeMax: function (maximo) {
                    if (!maximo) { return; }
                    this.pageSizeMax = maximo;
                    var opciones = PRB.tamaniosPagina(maximo);
                    if (this.pageSize > opciones[opciones.length - 1]) {
                        this.pageSize = opciones[opciones.length - 1];
                    }
                },
                seleccionarVista: function (vista) {
                    /* Misma vista de un reporte nuevo: se vuelve al editor sin perder lo configurado */
                    if (vista.dataViewID == this.dataViewID && this.modo === 'nuevo') {
                        this.seccion = 'configurar';
                        return;
                    }
                    var self = this;
                    this.confirmarCambio(function () {
                        self.abrirVista(vista);
                    });
                },
                abrirVista: async function (vista) {
                    var secuencia = ++secuenciaEditor;
                    this.cargandoConfig = true;
                    this.errorGeneral   = '';
                    try {
                        var json = await llamar('getColumns', { dataViewID: vista.dataViewID });
                        if (secuencia !== secuenciaEditor) { return; }
                        if (json.error === true) {
                            this.mostrarError(json.message);
                            return;
                        }
                        if (!json.columns || json.columns.length === 0) {
                            this.mostrarError('La vista seleccionada no tiene campos disponibles.');
                            return;
                        }

                        this.limpiarResultado();
                        this.aplicarCatalogo(json);
                        this.modo            = 'nuevo';
                        this.reportBuilderID = null;
                        this.dataViewID      = vista.dataViewID;
                        this.vistaEtiqueta   = vista.label;
                        this.nombre          = '';
                        this.columnas        = [];
                        this.orden           = [];
                        this.filtros         = [];
                        this.avisos          = [];
                        this.erroresConfig   = [];
                        this.presentacion       = PRB.presentacionVacia();
                        this.avisosPresentacion = [];
                        this.firmaGuardada   = this.firmaEditor;
                        this.seccion         = 'configurar';
                    } finally {
                        if (secuencia === secuenciaEditor) {
                            this.cargandoConfig = false;
                        }
                    }
                },
                abrirConsulta: function (reportBuilderID, modo) {
                    var self = this;
                    this.confirmarCambio(function () {
                        self.cargarConsulta(reportBuilderID, modo);
                    });
                },
                /* El estado actual solo se reemplaza cuando llegaron la consulta y su catálogo */
                cargarConsulta: async function (reportBuilderID, modo) {
                    var secuencia = ++secuenciaEditor;
                    this.cargandoConfig = true;
                    this.errorGeneral   = '';
                    try {
                        var jsonConsulta = await llamar('loadQuery', { reportBuilderID: reportBuilderID });
                        if (secuencia !== secuenciaEditor) { return; }
                        if (jsonConsulta.error === true) {
                            this.mostrarError(jsonConsulta.message);
                            return;
                        }
                        var consulta = jsonConsulta.query;
                        var config   = consulta ? PRB.normalizarConfigCargada(consulta.config) : null;
                        if (!config) {
                            this.mostrarError('No se pudo leer la configuración de la consulta.');
                            return;
                        }

                        var jsonColumnas = await llamar('getColumns', { dataViewID: consulta.dataViewID });
                        if (secuencia !== secuenciaEditor) { return; }
                        if (jsonColumnas.error === true) {
                            this.mostrarError(jsonColumnas.message);
                            return;
                        }

                        this.limpiarResultado();
                        this.aplicarCatalogo(jsonColumnas);
                        var estado = PRB.desdeConfig(config, this.catalogo, this.operadoresPermitidos);

                        this.modo            = modo;
                        this.reportBuilderID = consulta.reportBuilderID;
                        this.dataViewID      = consulta.dataViewID;
                        this.vistaEtiqueta   = this.nombreVista(consulta.dataViewID);
                        this.nombre          = consulta.name || '';
                        this.columnas        = estado.columnas;
                        this.orden           = estado.orden;
                        this.filtros         = estado.filtros;
                        this.avisos          = (jsonConsulta.warnings || []).concat(estado.avisos, jsonConsulta.displayWarnings || []);
                        this.erroresConfig   = [];
                        /* Las claves guardadas (c0, c1...) se convierten a uid de las columnas recién cargadas */
                        this.presentacion       = PRB.presentacionDesdeConsulta(consulta, estado.columnas);
                        this.avisosPresentacion = (jsonConsulta.displayWarnings || []).slice();
                        this.firmaGuardada   = this.firmaEditor;
                        this.seccion         = 'configurar';
                    } finally {
                        if (secuencia === secuenciaEditor) {
                            this.cargandoConfig = false;
                        }
                    }
                },
                /* Conserva los valores de filtro digitados: se guardan solo si el usuario presiona Guardar */
                pasarAEdicion: function () {
                    this.modo = 'edicion';
                },

                /* ---------------- Mostrar y agrupar ---------------- */
                agregarColumna: function () {
                    var campo = PRB.buscarCampo(this.catalogo, this.campoNuevo);
                    this.campoNuevo = '';
                    if (!campo) { return; }
                    if (this.columnas.length >= PRB.LIMITES.columnas) {
                        notificar('Puede mostrar hasta ' + PRB.LIMITES.columnas + ' columnas.', 'warning');
                        return;
                    }
                    this.columnas.push(PRB.nuevaColumna(campo));
                },
                agregarTodas: function () {
                    var self = this;
                    this.catalogo.forEach(function (campo) {
                        var yaEsta = self.columnas.some(function (columna) {
                            return columna.name === campo.name && !PRB.esFuncion(columna.modo);
                        });
                        if (!yaEsta && self.columnas.length < PRB.LIMITES.columnas) {
                            self.columnas.push(PRB.nuevaColumna(campo));
                        }
                    });
                },
                moverColumna: function (indice, delta) {
                    this.columnas = PRB.moverElemento(this.columnas, indice, delta);
                },
                quitarColumna: function (indice) {
                    this.columnas.splice(indice, 1);
                    this.orden = PRB.limpiarOrden(this.orden, this.columnas);
                },
                /* Si el orden apuntaba a la función anterior de esta columna, la sigue */
                cambiarModo: function (columna, modo) {
                    var fnAnterior = PRB.esFuncion(columna.modo) ? columna.modo : '';
                    var fnNueva    = PRB.esFuncion(modo) ? modo : '';
                    var clave      = PRB.claveOrden(columna.name, fnAnterior);
                    PRB.aplicarModoColumna(columna, modo, this.catalogo);
                    this.orden.forEach(function (item) {
                        if (item.clave === clave) {
                            item.clave = PRB.claveOrden(columna.name, fnNueva);
                        }
                    });
                    this.orden = PRB.limpiarOrden(this.orden, this.columnas);
                },

                /* ---------------- Ordenar ---------------- */
                agregarOrden: function () {
                    if (this.orden.length >= PRB.LIMITES.orden) {
                        notificar('Puede ordenar hasta por ' + PRB.LIMITES.orden + ' campos.', 'warning');
                        return;
                    }
                    /* Toma la primera clave que ningún otro orden usa */
                    if (this.opcionesOrdenLibres.length === 0) { return; }
                    this.orden.push({ uid: PRB.nuevoUid(), clave: this.opcionesOrdenLibres[0].clave, dir: 'ASC' });
                },

                /* ---------------- Filtros ---------------- */
                agregarFiltro: function () {
                    if (this.filtros.length >= PRB.LIMITES.filtros) {
                        notificar('Puede usar hasta ' + PRB.LIMITES.filtros + ' filtros.', 'warning');
                        return;
                    }
                    this.filtros.push(PRB.nuevoFiltro(this.catalogo[0], this.operadoresPermitidos));
                },
                /* Cada tipo tiene sus operadores: al cambiar de campo se reinician operador y valores */
                cambiarCampoFiltro: function (filtro, nombre) {
                    var campo = PRB.buscarCampo(this.catalogo, nombre);
                    if (!campo) { return; }
                    filtro.column   = campo.name;
                    filtro.operator = PRB.operadoresDeTipo(campo.type, this.operadoresPermitidos)[0].value;
                    filtro.value    = '';
                    filtro.value2   = '';
                    filtro.dinamico  = '';
                    filtro.dinamico2 = '';
                },
                /* La fecha dinámica elegida se conserva solo si el nuevo operador la ofrece */
                cambiarOperadorFiltro: function (filtro) {
                    if (filtro.operator !== 'between') {
                        filtro.value2    = '';
                        filtro.dinamico2 = '';
                    }
                    var codigo = filtro.dinamico;
                    var sigue  = PRB.opcionesFechaDinamica(filtro.operator, 'value').some(function (opcion) {
                        return opcion.value === codigo;
                    });
                    if (!sigue) {
                        filtro.dinamico = '';
                    }
                },
                /* Solo cambia lo que se ve: construirConfig envía el número sin comas */
                formatearValorFiltro: function (filtro, campo) {
                    if (this.tipoCampo(filtro.column) === 'number') {
                        filtro[campo] = PRB.formatearEntradaNumero(filtro[campo]);
                    }
                },

                /* ---------------- Generar ---------------- */
                validarAntesDeEnviar: function () {
                    this.erroresConfig = PRB.validarConfig(this.configActual, this.catalogo);
                    if (this.erroresConfig.length > 0) {
                        this.irAElemento('prbErrores');
                        return false;
                    }
                    return true;
                },
                /* Al guardar se aceptan filtros sin valor o con un solo extremo: se llenan al usar la consulta */
                validarAntesDeGuardar: function () {
                    this.erroresConfig = PRB.validarConfig(this.configParaGuardar, this.catalogo, { permitirVacios: true });
                    if (this.erroresConfig.length > 0) {
                        this.irAElemento('prbErrores');
                        return false;
                    }
                    return true;
                },
                generar: function () {
                    if (!this.validarAntesDeEnviar()) { return; }
                    /* Copia de la config: la paginación debe seguir mostrando lo generado aunque se edite el formulario */
                    var config = JSON.parse(this.firmaConfig);
                    this.pedirPagina(config, 1, true);
                    this.irAElemento('prbResultado');
                },
                irAPagina: function (pagina) {
                    if (this.configGenerada) {
                        this.pedirPagina(this.configGenerada, pagina, false);
                    }
                },
                cambiarTamanio: function (tamanio) {
                    this.pageSize = Number(tamanio);
                    if (this.configGenerada) {
                        this.pedirPagina(this.configGenerada, 1, false);
                    }
                },
                pedirPagina: async function (config, pagina, esNueva) {
                    var secuencia = ++secuenciaGenerar;
                    this.generando      = true;
                    this.errorResultado = '';
                    try {
                        var json = await llamar('generate', { config: config, page: pagina, pageSize: this.pageSize });
                        if (secuencia !== secuenciaGenerar) { return; }

                        /* Caso error: al paginar se conserva la página que ya se ve */
                        if (json.error === true) {
                            this.errorResultado = json.message;
                            if (esNueva) {
                                this.generado = false;
                                this.filas    = [];
                            }
                            return;
                        }

                        /* Casos sin datos y con datos: la plantilla los distingue por filas.length */
                        this.columnasResultado = json.columns || [];
                        this.filas             = json.rows || [];
                        this.page              = Number(json.page) || pagina;
                        this.pageSize          = Number(json.pageSize) || this.pageSize;
                        this.hasMore           = json.hasMore === true;
                        this.total             = (json.total === null || json.total === undefined) ? null : Number(json.total);
                        this.totalIsCapped     = json.totalIsCapped === true;
                        this.maxRows           = Number(json.maxRows) || null;
                        this.aplicarPageSizeMax(json.pageSizeMax);
                        this.configGenerada    = config;
                        this.firmaGenerada     = JSON.stringify(config);
                        this.generado          = true;
                    } finally {
                        if (secuencia === secuenciaGenerar) {
                            this.generando = false;
                        }
                    }
                },

                /* ---------------- Guardar (modal) ---------------- */
                /* Valida la configuración y abre el modal con una copia de la presentación guardada */
                abrirModalGuardar: function () {
                    this.errorGeneral = '';
                    if (!this.validarAntesDeGuardar()) { return; }
                    this.formGuardar        = PRB.copiarPresentacion(this.presentacion);
                    this.compartidaOriginal = this.presentacion.isShared === 1;
                    /* Sin permiso de administrador la consulta solo puede quedar privada (el servidor lo valida igual) */
                    if (!this.esAdminCompania) {
                        this.formGuardar.isShared = 0;
                    }
                    this.erroresModal = [];
                    $('#prbModalGuardar').modal('show');
                },
                /* Cancelar descarta lo editado en el modal; el nombre digitado se conserva */
                cerrarModalGuardar: function () {
                    if (this.guardando) { return; }
                    $('#prbModalGuardar').modal('hide');
                },
                mostrarErroresModal: function (errores) {
                    this.erroresModal = errores;
                    this.$nextTick(function () {
                        var caja = document.getElementById('prbErroresModal');
                        if (caja && caja.scrollIntoView) {
                            caja.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        }
                    });
                },
                /**
                 * Envía saveQuery con la configuración y la presentación. Las columnas de la presentación se
                 * convierten aquí a su clave posicional actual ("c" + índice en config.columns).
                 */
                confirmarGuardar: async function () {
                    if (this.guardando) { return; }
                    var nombre  = this.nombre.trim();
                    var errores = [];
                    if (nombre === '') {
                        errores.push('Escriba un nombre para guardar la consulta.');
                    }
                    errores = errores.concat(PRB.validarPresentacion(this.formGuardar, this.columnas, this.catalogos));
                    var erroresConfig = PRB.validarConfig(this.configParaGuardar, this.catalogo, { permitirVacios: true });
                    errores = errores.concat(erroresConfig);
                    if (errores.length > 0) {
                        this.mostrarErroresModal(errores);
                        return;
                    }

                    /* Firma tomada antes de enviar: lo editado mientras se guarda sigue contando como cambio */
                    var firma        = this.firmaEditor;
                    var presentacion = PRB.copiarPresentacion(this.formGuardar);
                    var datos        = {
                        name:                   nombre,
                        config:                 JSON.parse(JSON.stringify(this.configParaGuardar)),
                        isShared:               (this.esAdminCompania && presentacion.isShared === 1) ? 1 : 0,
                        viewTypeCatalogItemID:  Number(presentacion.viewTypeCatalogItemID) || 0,
                        dashboardCatalogItemID: Number(presentacion.dashboardCatalogItemID) || 0,
                        orden:                  Number(presentacion.orden) || 0,
                        display:                PRB.construirDisplay(presentacion, this.columnas, this.catalogos)
                    };
                    if (this.reportBuilderID) {
                        datos.reportBuilderID = this.reportBuilderID;
                    }

                    this.guardando    = true;
                    this.erroresModal = [];
                    try {
                        var json = await llamar('saveQuery', datos);
                        /* Caso error: el mensaje del servidor queda visible dentro del modal */
                        if (json.error === true) {
                            this.mostrarErroresModal([json.message]);
                            return;
                        }
                        presentacion.isShared   = datos.isShared;
                        presentacion.orden      = datos.orden;
                        this.presentacion       = presentacion;
                        this.avisosPresentacion = [];
                        this.reportBuilderID    = json.reportBuilderID || this.reportBuilderID;
                        this.modo               = 'edicion';
                        this.firmaGuardada      = firma;
                        this.avisos             = [];
                        this.erroresConfig      = [];
                        this.guardando          = false;
                        $('#prbModalGuardar').modal('hide');
                        notificar('Consulta guardada.', 'success');
                        this.cargarConsultas();
                    } finally {
                        this.guardando = false;
                    }
                },

                /* ---------------- Abrir en nueva página ---------------- */
                /**
                 * Envía la configuración actual (con filtros dinámicos, sin guardar) por un formulario normal a
                 * viewResult en otra pestaña; esa página vuelve a ejecutar generate paginado a ancho completo.
                 */
                abrirNuevaPagina: function () {
                    if (!this.validarAntesDeEnviar()) { return; }
                    var config     = JSON.parse(this.firmaConfig);
                    var formulario = document.createElement('form');
                    formulario.method = 'post';
                    formulario.action = URL_BASE + 'viewResult';
                    formulario.target = '_blank';
                    formulario.setAttribute('autocomplete', 'off');
                    formulario.style.display = 'none';
                    [['config', JSON.stringify(config)], ['dataViewID', String(config.dataViewID || '')]].forEach(function (par) {
                        var campo   = document.createElement('input');
                        campo.type  = 'hidden';
                        campo.name  = par[0];
                        campo.value = par[1];
                        formulario.appendChild(campo);
                    });
                    document.body.appendChild(formulario);
                    formulario.submit();
                    document.body.removeChild(formulario);
                },

                /* ---------------- Mis consultas ---------------- */
                duplicarConsulta: function (consulta) {
                    var self = this;
                    this.confirmarCambio(async function () {
                        self.errorGeneral   = '';
                        /* Deshabilita los botones de la lista: un doble clic crearía dos copias */
                        self.cargandoConfig = true;
                        var json = await llamar('duplicateQuery', { reportBuilderID: consulta.reportBuilderID });
                        if (json.error === true) {
                            self.cargandoConfig = false;
                            self.mostrarError(json.message);
                            return;
                        }
                        self.cargarConsultas();
                        self.cargarConsulta(json.reportBuilderID, 'edicion');
                    });
                },
                eliminarConsulta: function (consulta) {
                    var self = this;
                    /* fnShowConfirm ya codifica el texto al pintarlo con $.tmpl: aquí no se escapa */
                    fnShowConfirm('Eliminar consulta', '¿Desea eliminar la consulta "' + consulta.name + '"?', async function () {
                        self.errorGeneral = '';
                        var json = await llamar('deleteQuery', { reportBuilderID: consulta.reportBuilderID });
                        if (json.error === true) {
                            self.mostrarError(json.message);
                            return;
                        }
                        if (consulta.reportBuilderID == self.reportBuilderID) {
                            self.limpiarEditor();
                        }
                        notificar('Consulta eliminada.', 'success');
                        self.cargarConsultas();
                    });
                },

                /* ---------------- Exportar ---------------- */
                exportar: async function () {
                    this.errorGeneral = '';
                    if (!this.validarAntesDeEnviar()) { return; }

                    this.exportando = true;
                    try {
                        var res = await fetch(URL_BASE + 'exportExcel', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            body: JSON.stringify({ config: JSON.parse(this.firmaConfig) })
                        });
                        var tipo = PRB.tipoRespuestaExcel(res.headers.get('Content-Type'), res.ok);

                        if (tipo === 'json') {
                            var json = await res.json();
                            this.mostrarError(json.message || MENSAJE_FALLA);
                            return;
                        }
                        if (tipo === 'sesion') {
                            this.mostrarError(MENSAJE_SESION);
                            return;
                        }

                        var archivo = await res.blob();
                        var enlace  = document.createElement('a');
                        var url     = URL.createObjectURL(archivo);
                        enlace.href     = url;
                        enlace.download = PRB.nombreDescarga(res.headers.get('Content-Disposition'), 'reporte.xlsx');
                        document.body.appendChild(enlace);
                        enlace.click();
                        document.body.removeChild(enlace);
                        setTimeout(function () { URL.revokeObjectURL(url); }, 10000);

                        if (res.headers.get('X-Report-Truncated') === '1') {
                            notificar('El archivo se limitó al máximo de filas permitido.', 'warning');
                        } else {
                            notificar('Archivo generado.', 'success');
                        }
                    } catch (ex) {
                        console.error(ex);
                        this.mostrarError(MENSAJE_RED);
                    } finally {
                        this.exportando = false;
                    }
                }
            },
            mounted: function () {
                document.getElementById('app').style.visibility = 'visible';
                pv2BindMobileBar();
                /* Si el modal se cierra con Esc, se limpian sus mensajes */
                var self = this;
                $('#prbModalGuardar').on('hidden.bs.modal', function () {
                    self.erroresModal = [];
                });
                this.cargarVistas();
                this.cargarConsultas();
            }
        }).mount('#app');
    }

    /* Después del $(function) de theme-genyx/js/app.js, que ya aplicó Uniform a los select existentes */
    $(function () {
        if (typeof Vue === 'undefined' || !PRB) {
            var contenedor = document.getElementById('app');
            contenedor.style.visibility = 'visible';
            contenedor.textContent = 'No se pudo cargar la pantalla. Verifique su conexión a internet y recargue la página.';
            return;
        }
        montar();
    });
}());
</script>
