<?php
    // Página limpia (sin masterpage) del Constructor de Reportes: "Abrir en nueva página".
    // Recibe del controlador: title, viewLabel, dataViewID, configJson (JSON seguro para <script>, sin resolver),
    // useMobile y errorMessage. Si errorMessage no está vacío solo se muestra el mensaje.
    // La tabla la llena el JS llamando a app_builder_report/generate paginado, igual que index_script.
    $esMovil = (isset($useMobile) && $useMobile == "1");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo esc(isset($title) ? $title : 'Constructor de Reportes'); ?></title>
    <link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/bootstrap/bootstrap.css" rel="stylesheet" />
    <link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/css/icons.css" rel="stylesheet" />
    <?php echo view('core_template/posme_v2_style'); ?>
    <link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/posme-report-builder.css?v=4" rel="stylesheet" />
    <style>
        body { background: #f4f5f7; margin: 0; }
    </style>
</head>
<body>
<?php if (!empty($errorMessage)) { ?>
    <div class="prb-view pv2 prb">
        <div class="pv2-card">
            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-table"></i></span> Constructor de Reportes</p>
            <div class="prb-alert is-error" role="alert"><?php echo esc($errorMessage); ?></div>
        </div>
    </div>
<?php } else { ?>
    <div id="app" class="prb-view pv2 prb<?php echo $esMovil ? " pv2-mobile" : ""; ?>">
        <div class="pv2-card pv2-header">
            <div class="pv2-header-info">
                <div class="pv2-header-icon"><i class="icon20 i-table"></i></div>
                <div>
                    <p class="pv2-header-title">Resultado</p>
                    <p class="pv2-header-sub"><?php echo esc(isset($viewLabel) ? $viewLabel : ''); ?></p>
                </div>
            </div>
        </div>

        <div class="pv2-card">
            <div class="pv2-detail-head">
                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-table"></i></span> Resultado</p>
                <span v-if="generado" class="pv2-count">{{ rango }}</span>
            </div>

            <div v-if="errorResultado" class="prb-alert is-error" role="alert">
                {{ errorResultado }}
                <div v-if="!generado" style="margin-top:8px"><button type="button" class="btn btn-default" @click="pedirPagina(1)">Reintentar</button></div>
            </div>
            <div v-if="generando" class="prb-loading">Generando reporte...</div>

            <div v-if="generado && filas.length === 0 && !generando" class="pv2-empty">No hay datos para los criterios seleccionados.</div>
            <div v-else-if="generado && filas.length > 0" class="pv2-table-wrap">
                <table class="pv2-table">
                    <thead>
                        <tr>
                            <th v-for="col in columnas" :key="col.key" :class="{ 'prb-right': col.align === 'right' }">{{ col.label }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(fila, indice) in filas" :key="indice" class="row_razon">
                            <td v-for="col in columnas" :key="col.key" :data-label="col.label" :class="{ 'prb-right': col.align === 'right' }"><span>{{ celda(fila, col) }}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="generado && filas.length > 0" class="prb-paginacion">
                <div class="prb-paginacion-grupo">
                    <label class="pv2-label" for="prbPageSize" style="margin:0">Filas por página</label>
                    <select id="prbPageSize" class="form-control" autocomplete="off" :disabled="generando" :value="pageSize" @change="cambiarTamanio($event.target.value)">
                        <option v-for="tamanio in tamanios" :key="tamanio" :value="tamanio">{{ tamanio }}</option>
                    </select>
                </div>
                <div class="prb-paginacion-grupo">
                    <button type="button" class="prb-btn-icon" aria-label="Página anterior" title="Anterior" :disabled="generando || page <= 1" @click="pedirPagina(page - 1)">&#9664;</button>
                    <span>{{ textoPagina }}</span>
                    <button type="button" class="prb-btn-icon" aria-label="Página siguiente" title="Siguiente" :disabled="generando || !hayPaginaSiguiente" @click="pedirPagina(page + 1)">&#9654;</button>
                </div>
            </div>
            <div v-if="generado && limitePantalla" class="prb-alert is-info" style="margin-top:10px">Se alcanzó el máximo de {{ maxRowsTexto }} filas en pantalla. Use Exportar a Excel en el constructor para obtener más.</div>
        </div>
    </div>

    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/posme-report-builder.js?v=2"></script>
    <script>
    (function () {
        'use strict';

        var URL_GENERAR = '<?php echo base_url(); ?>/app_builder_report/generate';
        var CONFIG      = <?php echo isset($configJson) ? $configJson : 'null'; ?>;
        var PRB         = window.PosmeReportBuilder;

        var MENSAJE_SESION = 'El servidor no respondió como se esperaba. Es posible que su sesión haya vencido: recargue la página e inicie sesión.';
        var MENSAJE_RED    = 'Error de conexión con el servidor. Verifique su red e intente de nuevo.';
        var MENSAJE_FALLA  = 'No se pudo completar la operación.';

        /* Solo vale la respuesta de la última petición */
        var secuencia = 0;

        /**
         * POST JSON a generate con los mismos encabezados que index_script. Nunca lanza excepción:
         * red caída, HTML por sesión vencida o JSON inválido vuelven como {error:true, message}.
         *
         * @param {Object} datos {config, page, pageSize}.
         * @return {Promise<Object>} Respuesta del servidor.
         */
        async function generar(datos) {
            var res;
            try {
                res = await fetch(URL_GENERAR, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: JSON.stringify(datos)
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

        var contenedor = document.getElementById('app');
        if (typeof Vue === 'undefined' || !PRB) {
            contenedor.style.visibility = 'visible';
            contenedor.textContent = 'No se pudo cargar la página. Verifique su conexión a internet y recárguela.';
            return;
        }

        Vue.createApp({
            data: function () {
                return {
                    generando:      false,
                    generado:       false,
                    errorResultado: '',
                    columnas:       [],
                    filas:          [],
                    page:           1,
                    pageSize:       25,
                    pageSizeMax:    null,
                    hasMore:        false,
                    total:          null,
                    totalIsCapped:  false,
                    maxRows:        null
                };
            },
            computed: {
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
                },
                /* "Página n de N" si el servidor informó el total; si el total llegó al tope de conteo, "N+" */
                textoPagina: function () {
                    if (this.total === null || !this.pageSize) {
                        return 'Página ' + PRB.formatearEntero(this.page);
                    }
                    var paginas = Math.max(1, Math.ceil(this.total / this.pageSize));
                    return 'Página ' + PRB.formatearEntero(this.page) + ' de ' + PRB.formatearEntero(paginas) + (this.totalIsCapped ? '+' : '');
                }
            },
            methods: {
                celda: function (fila, columna) {
                    return PRB.formatearCelda(fila[columna.key], columna);
                },
                cambiarTamanio: function (tamanio) {
                    this.pageSize = Number(tamanio);
                    this.pedirPagina(1);
                },
                /* Pide una página; generate vuelve a validar y resuelve las fechas dinámicas en cada llamada */
                pedirPagina: async function (pagina) {
                    var actual = ++secuencia;
                    this.generando      = true;
                    this.errorResultado = '';
                    try {
                        var json = await generar({ config: CONFIG, page: pagina, pageSize: this.pageSize });
                        if (actual !== secuencia) { return; }

                        /* Caso error: si ya hay una página visible se conserva */
                        if (json.error === true) {
                            this.errorResultado = json.message;
                            return;
                        }

                        /* Casos sin datos y con datos: la plantilla los distingue por filas.length */
                        this.columnas      = json.columns || [];
                        this.filas         = json.rows || [];
                        this.page          = Number(json.page) || pagina;
                        this.pageSize      = Number(json.pageSize) || this.pageSize;
                        this.hasMore       = json.hasMore === true;
                        this.total         = (json.total === null || json.total === undefined) ? null : Number(json.total);
                        this.totalIsCapped = json.totalIsCapped === true;
                        this.maxRows       = Number(json.maxRows) || null;
                        if (json.pageSizeMax) {
                            this.pageSizeMax = json.pageSizeMax;
                        }
                        this.generado      = true;
                        window.scrollTo(0, 0);
                    } finally {
                        if (actual === secuencia) {
                            this.generando = false;
                        }
                    }
                }
            },
            mounted: function () {
                contenedor.style.visibility = 'visible';
                if (!CONFIG || typeof CONFIG !== 'object') {
                    this.errorResultado = 'No se recibió la configuración del reporte. Vuelva a abrirlo desde el constructor.';
                    return;
                }
                this.pedirPagina(1);
            }
        }).mount('#app');
    }());
    </script>
<?php } ?>
</body>
</html>
