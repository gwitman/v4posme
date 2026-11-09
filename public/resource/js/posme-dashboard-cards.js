/**
 * Tarjetas del Builder Report dentro de los dashboards (parcial core_dasboard/builder_cards).
 *
 * Flujo:
 * 1. Pide las tarjetas del dashboard (getDashboardCards). Si hay error o no hay tarjetas,
 *    el contenedor sigue oculto y el dashboard original se ve idéntico.
 * 2. Por cada tarjeta crea su caja con spinner y pide sus datos (getCardData) en paralelo;
 *    un error solo afecta a esa tarjeta.
 * 3. Indicador: valor grande; Tabla: encabezados y filas; Gráfico: Google Charts local.
 *
 * Formato numérico 1,000.00 con PosmeReportBuilder (posme-report-builder.js); los cálculos
 * de los gráficos usan números puros.
 * No depende de jQuery ni de Bootstrap.
 */
(function (window, document) {
    'use strict';

    var MENSAJE_RED    = 'No se pudo conectar con el servidor.';
    var MENSAJE_SESION = 'La sesión expiró o el servidor respondió algo inesperado.';
    var MENSAJE_FALLA  = 'No se pudieron cargar los datos.';
    var MENSAJE_VACIO  = 'Sin datos para mostrar.';

    /* Funciones de dibujo de los gráficos, para redibujar al cambiar el tamaño de la ventana */
    var graficos = [];
    var temporizadorResize = null;

    /* Promesa única de carga de Google Charts (se comparte entre todas las tarjetas) */
    var promesaGoogle = null;

    function rb() {
        return window.PosmeReportBuilder || null;
    }

    function textoDe(valor) {
        return valor === null || valor === undefined ? '' : String(valor);
    }

    /* Número con dos decimales: 1,000.00 */
    function formatoDosDecimales(valor) {
        if (rb()) {
            return rb().formatearNumero(valor, 2);
        }
        var numero = Number(textoDe(valor).replace(/[,\s]/g, ''));
        if (!isFinite(numero)) {
            return textoDe(valor);
        }
        return numero.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    /* Mismo formateo de celdas que la pantalla del constructor */
    function formatearCelda(valor, columna) {
        if (rb()) {
            return rb().formatearCelda(valor, columna);
        }
        return textoDe(valor);
    }

    /* Número puro para cálculos y gráficos: "15,500.25" => 15500.25; inválido => null */
    function numeroPuro(valor) {
        if (valor === null || valor === undefined || valor === '') {
            return null;
        }
        var numero = Number(textoDe(valor).replace(/[,\s]/g, ''));
        return isFinite(numero) ? numero : null;
    }

    /**
     * POST JSON al controlador app_builder_report. Nunca lanza excepción: cualquier falla
     * vuelve como {error:true, message}.
     *
     * @param {string} urlBase URL base del sistema.
     * @param {string} metodo Método del controlador.
     * @param {Object} datos Cuerpo de la petición.
     * @return {Promise<Object>} Respuesta del servidor.
     */
    async function llamar(urlBase, metodo, datos) {
        var res;
        try {
            res = await fetch(urlBase + '/app_builder_report/' + metodo, {
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

    function crear(etiqueta, clase, texto) {
        var el = document.createElement(etiqueta);
        if (clase) {
            el.className = clase;
        }
        if (texto !== undefined) {
            el.textContent = texto;
        }
        return el;
    }

    /* Reemplaza el contenido del cuerpo de la tarjeta por un mensaje pequeño */
    function mostrarMensaje(cuerpo, mensaje, esError) {
        cuerpo.innerHTML = '';
        cuerpo.appendChild(crear('div', 'pdash-msg' + (esError ? ' is-error' : ''), mensaje));
    }

    /* ---------------- Google Charts ---------------- */

    /**
     * Carga Google Charts local una sola vez. Si dashboards_default ya cargó loader.js se
     * reutiliza (google.charts.load con la misma versión "current" es seguro de repetir);
     * si no existe, se inyecta el loader local.
     *
     * @param {string} urlRecursos URL base de recursos (APP_URL_RESOURCE_CSS_JS).
     * @return {Promise} Se resuelve cuando google.visualization está listo.
     */
    function cargarGoogleCharts(urlRecursos) {
        if (promesaGoogle) {
            return promesaGoogle;
        }
        promesaGoogle = new Promise(function (resolver, rechazar) {
            var cargarPaquete = function () {
                try {
                    window.google.charts.load('current', { packages: ['corechart'] });
                    window.google.charts.setOnLoadCallback(function () {
                        resolver();
                    });
                } catch (ex) {
                    rechazar(ex);
                }
            };

            if (window.google && window.google.visualization && window.google.visualization.DataTable) {
                resolver();
                return;
            }
            if (window.google && window.google.charts && window.google.charts.load) {
                cargarPaquete();
                return;
            }

            var script = document.createElement('script');
            script.src = urlRecursos + '/resource/js/chart-google/loader.js';
            script.onload = cargarPaquete;
            script.onerror = function () {
                rechazar(new Error('No se pudo cargar Google Charts'));
            };
            document.head.appendChild(script);
        });
        return promesaGoogle;
    }

    /**
     * Arma la tabla de datos del gráfico.
     * - Sin seriesField: categoría = labelField; una columna numérica por cada valueField.
     * - Con seriesField (y no es PieChart): pivote; una serie por cada valor distinto de
     *   seriesField, con el primer valueField como valor.
     * - PieChart: solo el primer valueField.
     *
     * @return {Array|null} Matriz para arrayToDataTable o null si no hay datos útiles.
     */
    function matrizGrafico(columnas, filas, display, tipoGrafico) {
        var etiquetaDe = function (clave) {
            for (var i = 0; i < columnas.length; i++) {
                if (columnas[i].key === clave) {
                    return textoDe(columnas[i].label) || clave;
                }
            }
            return clave;
        };

        var campoEtiqueta = display.labelField || '';
        var valores       = Array.isArray(display.valueFields) ? display.valueFields.slice() : [];
        var campoSerie    = display.seriesField || '';

        if (campoEtiqueta === '' || valores.length === 0 || filas.length === 0) {
            return null;
        }
        if (tipoGrafico === 'PieChart') {
            valores    = [valores[0]];
            campoSerie = '';
        }

        var matriz = [];

        if (campoSerie !== '') {
            /* Pivote: categorías y series en el orden en que aparecen */
            var categorias = [];
            var series     = [];
            var mapa       = {};
            filas.forEach(function (fila) {
                var categoria = textoDe(fila[campoEtiqueta]);
                var serie     = textoDe(fila[campoSerie]);
                if (categorias.indexOf(categoria) === -1) {
                    categorias.push(categoria);
                }
                if (series.indexOf(serie) === -1) {
                    series.push(serie);
                }
                var clave = categoria + '\u0001' + serie;
                var numero = numeroPuro(fila[valores[0]]);
                if (numero !== null) {
                    mapa[clave] = (mapa[clave] || 0) + numero;
                }
            });
            matriz.push([etiquetaDe(campoEtiqueta)].concat(series));
            categorias.forEach(function (categoria) {
                var renglon = [categoria];
                series.forEach(function (serie) {
                    var clave = categoria + '\u0001' + serie;
                    renglon.push(mapa.hasOwnProperty(clave) ? mapa[clave] : null);
                });
                matriz.push(renglon);
            });
            return matriz;
        }

        matriz.push([etiquetaDe(campoEtiqueta)].concat(valores.map(etiquetaDe)));
        filas.forEach(function (fila) {
            var renglon = [textoDe(fila[campoEtiqueta])];
            valores.forEach(function (clave) {
                var numero = numeroPuro(fila[clave]);
                renglon.push(numero === null ? 0 : numero);
            });
            matriz.push(renglon);
        });
        return matriz;
    }

    function pintarGrafico(cuerpo, datos, urlRecursos) {
        var tipo = datos.chartType || 'ColumnChart';
        if (['BarChart', 'PieChart', 'ColumnChart', 'LineChart'].indexOf(tipo) === -1) {
            tipo = 'ColumnChart';
        }
        var matriz = matrizGrafico(datos.columns, datos.rows, datos.display || {}, tipo);
        if (!matriz || matriz.length < 2) {
            mostrarMensaje(cuerpo, MENSAJE_VACIO, false);
            return;
        }

        cargarGoogleCharts(urlRecursos).then(function () {
            cuerpo.innerHTML = '';
            var lienzo = crear('div', 'pdash-chart');
            cuerpo.appendChild(lienzo);

            var tabla;
            try {
                tabla = window.google.visualization.arrayToDataTable(matriz);
            } catch (ex) {
                console.error(ex);
                mostrarMensaje(cuerpo, MENSAJE_FALLA, true);
                return;
            }

            /* Formato 1,000.00 en tooltips y ejes */
            var formato = new window.google.visualization.NumberFormat({ pattern: '#,##0.00' });
            for (var c = 1; c < tabla.getNumberOfColumns(); c++) {
                formato.format(tabla, c);
            }

            var opciones = {
                legend: { position: 'bottom' },
                chartArea: { left: '12%', top: 20, width: '82%', height: '70%' },
                colors: ['#2f6fb5', '#2e7d32', '#c62828', '#8a5a00', '#6b7280', '#7e57c2', '#00838f'],
                fontSize: 12
            };
            if (tipo !== 'PieChart') {
                opciones.vAxis = { format: '#,##0.00' };
                if (tipo === 'BarChart') {
                    opciones.hAxis = { format: '#,##0.00' };
                    opciones.vAxis = {};
                }
            }

            var grafico = new window.google.visualization[tipo](lienzo);
            var dibujar = function () {
                if (lienzo.offsetWidth > 0) {
                    grafico.draw(tabla, opciones);
                }
            };
            graficos.push(dibujar);
            dibujar();
        }, function (ex) {
            console.error(ex);
            mostrarMensaje(cuerpo, MENSAJE_FALLA, true);
        });
    }

    /* ---------------- Indicador y tabla ---------------- */

    function pintarIndicador(cuerpo, datos) {
        var display = datos.display || {};
        var campo   = display.indicatorField || '';
        if (campo === '' || datos.rows.length === 0) {
            mostrarMensaje(cuerpo, MENSAJE_VACIO, false);
            return;
        }
        var valor = datos.rows[0][campo];
        var texto = (valor === null || valor === undefined || valor === '') ? '0.00' : formatoDosDecimales(valor);

        cuerpo.innerHTML = '';
        cuerpo.appendChild(crear('div', 'pdash-indicator', texto));
    }

    function pintarTabla(cuerpo, datos) {
        if (datos.rows.length === 0) {
            mostrarMensaje(cuerpo, MENSAJE_VACIO, false);
            return;
        }
        var contenedor = crear('div', 'pdash-table-wrap');
        var tabla      = crear('table', 'pdash-table');
        var thead      = crear('thead');
        var trHead     = crear('tr');
        datos.columns.forEach(function (columna) {
            var esNumero = columna.fn || columna.type === 'number';
            trHead.appendChild(crear('th', esNumero ? 'pdash-num' : '', textoDe(columna.label)));
        });
        thead.appendChild(trHead);

        var tbody = crear('tbody');
        datos.rows.forEach(function (fila) {
            var tr = crear('tr');
            datos.columns.forEach(function (columna) {
                var esNumero = columna.fn || columna.type === 'number';
                var td = crear('td', esNumero ? 'pdash-num' : '', formatearCelda(fila[columna.key], columna));
                td.setAttribute('data-label', textoDe(columna.label));
                tr.appendChild(td);
            });
            tbody.appendChild(tr);
        });

        tabla.appendChild(thead);
        tabla.appendChild(tbody);
        contenedor.appendChild(tabla);
        cuerpo.innerHTML = '';
        cuerpo.appendChild(contenedor);
    }

    /* ---------------- Tarjetas ---------------- */

    function claseAncho(ancho) {
        var valor = textoDe(ancho);
        if (valor === '20' || valor === '50') {
            return 'pdash-w' + valor;
        }
        return 'pdash-w100';
    }

    /* Crea la caja de la tarjeta con título y spinner; devuelve el cuerpo */
    function crearCaja(grilla, tarjeta) {
        var columna = crear('div', 'pdash-col ' + claseAncho(tarjeta.width));
        var caja    = crear('div', 'pdash-card pdash-type-' + textoDe(tarjeta.cardType).toLowerCase());
        var titulo  = textoDe(tarjeta.title) || textoDe(tarjeta.name);
        caja.appendChild(crear('div', 'pdash-card-title', titulo));

        var cuerpo = crear('div', 'pdash-card-body');
        var carga  = crear('div', 'pdash-loading');
        carga.appendChild(crear('span', 'pdash-spinner'));
        carga.appendChild(crear('span', '', 'Cargando...'));
        cuerpo.appendChild(carga);

        caja.appendChild(cuerpo);
        columna.appendChild(caja);
        grilla.appendChild(columna);
        return cuerpo;
    }

    /* Pide los datos de una tarjeta y la pinta; un error queda dentro de la propia tarjeta */
    async function cargarTarjeta(urlBase, urlRecursos, tarjeta, cuerpo) {
        var json = await llamar(urlBase, 'getCardData', { reportBuilderID: tarjeta.reportBuilderID });

        /* Caso 1: error */
        if (json.error === true) {
            mostrarMensaje(cuerpo, json.message || MENSAJE_FALLA, true);
            return;
        }

        var datos = {
            columns:   Array.isArray(json.columns) ? json.columns : [],
            rows:      Array.isArray(json.rows) ? json.rows : [],
            display:   json.display || tarjeta.display || {},
            cardType:  json.cardType || tarjeta.cardType,
            chartType: json.chartType || tarjeta.chartType,
            /* Tipo normalizado por el servidor (grafico/tabla/indicador): no depende de mayúsculas ni tildes del catálogo */
            cardKind:  textoDe(json.cardKind || tarjeta.cardKind).toLowerCase()
        };

        /* Caso 2: sin datos */
        if (datos.rows.length === 0) {
            mostrarMensaje(cuerpo, MENSAJE_VACIO, false);
            return;
        }

        /* Caso 3: con datos */
        try {
            if (datos.cardKind === 'indicador') {
                pintarIndicador(cuerpo, datos);
            } else if (datos.cardKind === 'grafico') {
                pintarGrafico(cuerpo, datos, urlRecursos);
            } else {
                pintarTabla(cuerpo, datos);
            }
        } catch (ex) {
            console.error(ex);
            mostrarMensaje(cuerpo, MENSAJE_FALLA, true);
        }
    }

    /**
     * Inicializa un contenedor .pdash del parcial.
     *
     * @param {HTMLElement} contenedor Elemento con data-dashboard, data-url y data-resource.
     */
    async function iniciar(contenedor) {
        var urlBase     = textoDe(contenedor.getAttribute('data-url')).replace(/\/+$/, '');
        var urlRecursos = textoDe(contenedor.getAttribute('data-resource')).replace(/\/+$/, '');
        var dashboard   = textoDe(contenedor.getAttribute('data-dashboard'));
        if (dashboard === '') {
            return;
        }

        var json = await llamar(urlBase, 'getDashboardCards', { dashboard: dashboard });

        /* Error o sin tarjetas: no se muestra nada, el dashboard queda igual */
        if (json.error === true) {
            console.warn('Tarjetas del dashboard: ' + json.message);
            return;
        }
        var tarjetas = Array.isArray(json.cards) ? json.cards : [];
        if (tarjetas.length === 0) {
            return;
        }

        /* Con tarjetas: grilla al final del dashboard original */
        var grilla = contenedor.querySelector('.pdash-grid');
        if (!grilla) {
            grilla = crear('div', 'pdash-grid');
            contenedor.appendChild(grilla);
        }
        contenedor.style.display = 'block';

        tarjetas.forEach(function (tarjeta) {
            var cuerpo = crearCaja(grilla, tarjeta);
            cargarTarjeta(urlBase, urlRecursos, tarjeta, cuerpo);
        });
    }

    /* Redibuja los gráficos al cambiar el tamaño de la ventana (con espera para no saturar) */
    window.addEventListener('resize', function () {
        if (temporizadorResize) {
            clearTimeout(temporizadorResize);
        }
        temporizadorResize = setTimeout(function () {
            graficos.forEach(function (dibujar) {
                try {
                    dibujar();
                } catch (ex) {
                    console.error(ex);
                }
            });
        }, 250);
    });

    /**
     * Coloca las tarjetas al final del contenido del dashboard, despues de los paneles que ya existen:
     * se mueven al final del mismo contenedor donde esta el titulo (#heading.page-header), para que
     * queden dentro del mismo marco de la pantalla. Si la pantalla no tiene titulo, quedan donde las
     * dejo el controlador (al final del cuerpo).
     *
     * @param {HTMLElement} contenedor Contenedor .pdash del parcial.
     */
    function ubicarAlFinal(contenedor) {
        var titulo = document.querySelector('#heading.page-header');
        if (titulo && titulo.parentNode && titulo.parentNode !== contenedor.parentNode) {
            titulo.parentNode.appendChild(contenedor);
        }
    }

    function arrancar() {
        var contenedores = document.querySelectorAll('.pdash[data-dashboard]');
        for (var i = 0; i < contenedores.length; i++) {
            if (contenedores[i].getAttribute('data-pdash-init') === '1') {
                continue;
            }
            ubicarAlFinal(contenedores[i]);
            contenedores[i].setAttribute('data-pdash-init', '1');
            iniciar(contenedores[i]);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', arrancar);
    } else {
        arrancar();
    }
}(window, document));
