/*
 * Widget del asistente IA "Jordania" (posMe).
 *
 * Reglas:
 *  - JS plano, sin dependencias ni CDN. Se inicializa despues de cargar la pagina.
 *  - Todo va en try/catch: un error del chat nunca debe romper la pantalla.
 *  - Sin internet (navigator.onLine / eventos online-offline / status del servidor) el chat
 *    queda deshabilitado en gris y se rehabilita solo.
 *  - Nunca usa alert(), location.reload() ni redirecciones.
 *  - El markdown de las respuestas se convierte a nodos DOM con textContent (nunca innerHTML).
 */
(function (global) {
    'use strict';

    /* =============================================================================
       MARKDOWN SEGURO (parser puro, sin DOM: se puede probar en Node)
    ============================================================================= */

    /**
     * Divide una linea en fragmentos: texto, negrita (**x**), cursiva (*x*) y codigo (`x`).
     * Los valores son texto plano; el HTML nunca se interpreta.
     */
    function parsearLinea(texto) {
        var fragmentos = [];
        var patron = /(\*\*([^*]+)\*\*|`([^`]+)`|\*([^*\s][^*]*)\*)/g;
        var ultimo = 0;
        var m;
        texto = String(texto == null ? '' : texto);
        while ((m = patron.exec(texto)) !== null) {
            if (m.index > ultimo) {
                fragmentos.push({ t: 'texto', v: texto.slice(ultimo, m.index) });
            }
            if (m[2] !== undefined) {
                fragmentos.push({ t: 'negrita', v: m[2] });
            } else if (m[3] !== undefined) {
                fragmentos.push({ t: 'codigo', v: m[3] });
            } else {
                fragmentos.push({ t: 'cursiva', v: m[4] });
            }
            ultimo = patron.lastIndex;
        }
        if (ultimo < texto.length) {
            fragmentos.push({ t: 'texto', v: texto.slice(ultimo) });
        }
        return fragmentos;
    }

    /** Celdas de una fila de tabla markdown: | a | b | */
    function celdasTabla(linea) {
        var limpia = linea.trim().replace(/^\|/, '').replace(/\|$/, '');
        return limpia.split('|').map(function (c) { return parsearLinea(c.trim()); });
    }

    /**
     * Convierte markdown simple en bloques: parrafo, titulo, lista, tabla.
     * @param {string} md Texto markdown de la respuesta.
     * @returns {Array} bloques
     */
    function parsearMarkdown(md) {
        var lineas = String(md == null ? '' : md).replace(/\r\n?/g, '\n').split('\n');
        var bloques = [];
        var parrafo = [];
        var i = 0;

        function cerrarParrafo() {
            if (parrafo.length) {
                bloques.push({ tipo: 'parrafo', lineas: parrafo.map(parsearLinea) });
                parrafo = [];
            }
        }

        while (i < lineas.length) {
            var linea = lineas[i];
            var limpia = linea.trim();

            if (limpia === '') {
                cerrarParrafo();
                i++;
                continue;
            }

            // Tabla: fila | ... | seguida de separador |---|
            if (/^\|.*\|$/.test(limpia) && i + 1 < lineas.length && /^\|?\s*:?-{2,}/.test(lineas[i + 1].trim())) {
                cerrarParrafo();
                var tabla = { tipo: 'tabla', encabezado: celdasTabla(limpia), filas: [] };
                i += 2;
                while (i < lineas.length && /^\|.*\|$/.test(lineas[i].trim())) {
                    tabla.filas.push(celdasTabla(lineas[i]));
                    i++;
                }
                bloques.push(tabla);
                continue;
            }

            var titulo = /^(#{1,6})\s+(.*)$/.exec(limpia);
            if (titulo) {
                cerrarParrafo();
                bloques.push({ tipo: 'titulo', contenido: parsearLinea(titulo[2]) });
                i++;
                continue;
            }

            var esLista = /^([-*•]|\d+[.)])\s+/.test(limpia);
            if (esLista) {
                cerrarParrafo();
                var ordenada = /^\d+[.)]\s+/.test(limpia);
                var lista = { tipo: 'lista', ordenada: ordenada, items: [] };
                while (i < lineas.length && /^\s*([-*•]|\d+[.)])\s+/.test(lineas[i])) {
                    lista.items.push(parsearLinea(lineas[i].trim().replace(/^([-*•]|\d+[.)])\s+/, '')));
                    i++;
                }
                bloques.push(lista);
                continue;
            }

            parrafo.push(limpia);
            i++;
        }
        cerrarParrafo();
        return bloques;
    }

    /** Agrega fragmentos de linea a un nodo usando solo textContent. */
    function pintarFragmentos(doc, padre, fragmentos) {
        fragmentos.forEach(function (f) {
            var nodo;
            if (f.t === 'negrita') {
                nodo = doc.createElement('strong');
            } else if (f.t === 'cursiva') {
                nodo = doc.createElement('em');
            } else if (f.t === 'codigo') {
                nodo = doc.createElement('code');
            } else {
                padre.appendChild(doc.createTextNode(f.v));
                return;
            }
            nodo.textContent = f.v;
            padre.appendChild(nodo);
        });
    }

    /** Construye los nodos DOM de una respuesta markdown. */
    function renderizarMarkdown(doc, contenedor, md) {
        parsearMarkdown(md).forEach(function (b) {
            if (b.tipo === 'parrafo') {
                var p = doc.createElement('p');
                b.lineas.forEach(function (fragmentos, idx) {
                    if (idx > 0) {
                        p.appendChild(doc.createElement('br'));
                    }
                    pintarFragmentos(doc, p, fragmentos);
                });
                contenedor.appendChild(p);
            } else if (b.tipo === 'titulo') {
                var h = doc.createElement('h4');
                pintarFragmentos(doc, h, b.contenido);
                contenedor.appendChild(h);
            } else if (b.tipo === 'lista') {
                var ul = doc.createElement(b.ordenada ? 'ol' : 'ul');
                b.items.forEach(function (item) {
                    var li = doc.createElement('li');
                    pintarFragmentos(doc, li, item);
                    ul.appendChild(li);
                });
                contenedor.appendChild(ul);
            } else if (b.tipo === 'tabla') {
                var envoltura = doc.createElement('div');
                envoltura.className = 'jdn-tabla-contenedor';
                var tabla = doc.createElement('table');
                tabla.className = 'jdn-tabla';
                var thead = doc.createElement('thead');
                var trh = doc.createElement('tr');
                b.encabezado.forEach(function (celda) {
                    var th = doc.createElement('th');
                    pintarFragmentos(doc, th, celda);
                    trh.appendChild(th);
                });
                thead.appendChild(trh);
                tabla.appendChild(thead);
                var tbody = doc.createElement('tbody');
                b.filas.forEach(function (fila) {
                    var tr = doc.createElement('tr');
                    fila.forEach(function (celda) {
                        var td = doc.createElement('td');
                        pintarFragmentos(doc, td, celda);
                        tr.appendChild(td);
                    });
                    tbody.appendChild(tr);
                });
                tabla.appendChild(tbody);
                envoltura.appendChild(tabla);
                contenedor.appendChild(envoltura);
            }
        });
    }

    var JordaniaMarkdown = { parsearMarkdown: parsearMarkdown, parsearLinea: parsearLinea, renderizarMarkdown: renderizarMarkdown };

    // Exportacion para pruebas (Jest/Node)
    if (typeof module !== 'undefined' && module.exports) {
        module.exports = JordaniaMarkdown;
    }
    if (typeof global.document === 'undefined') {
        return;
    }
    global.JordaniaMarkdown = JordaniaMarkdown;

    /* =============================================================================
       WIDGET
    ============================================================================= */

    var TIMEOUT_MS = 65000;
    var TEXTO_SIN_CONEXION = 'Jordania no está disponible (sin conexión a internet)';
    var MSG_RED = 'No se pudo conectar con el asistente. Revisa tu conexión a internet.';
    var MSG_SESION = 'Tu sesión expiró. Vuelve a iniciar sesión en posMe (no se recargó la pantalla).';
    var BIENVENIDA = 'Hola, soy Jordania, tu asistente de posMe. Puedo ayudarte con tus datos (ventas, clientes, saldos, inventario) y con el uso del sistema.';
    var SUGERENCIAS = [
        '¿Cuáles son los productos más vendidos este mes?',
        '¿Qué clientes tienen cuotas vencidas?',
        '¿Cómo registro un abono?'
    ];

    var estado = {
        urlBase: '',
        abierto: false,
        historialCargado: false,
        enviando: false,
        navegadorEnLinea: true,
        servidorDisponible: true,
        mensajeServidor: '',
        reintento: null
    };
    var el = {};

    /** Petición con timeout (AbortController). Devuelve {status, data, esJson} o lanza error de red. */
    function peticion(metodo, ruta, datos, timeoutMs) {
        var controlador = typeof AbortController !== 'undefined' ? new AbortController() : null;
        var temporizador = setTimeout(function () {
            if (controlador) {
                controlador.abort();
            }
        }, timeoutMs || TIMEOUT_MS);

        var opciones = {
            method: metodo,
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            signal: controlador ? controlador.signal : undefined
        };
        if (datos) {
            // Form-urlencoded para que el controlador lo lea con getPost
            var cuerpo = new URLSearchParams();
            Object.keys(datos).forEach(function (k) { cuerpo.append(k, datos[k]); });
            opciones.body = cuerpo;
        }

        return fetch(estado.urlBase + '/' + ruta, opciones).then(function (res) {
            clearTimeout(temporizador);
            return res.text().then(function (texto) {
                var data = null;
                try {
                    data = JSON.parse(texto);
                } catch (e) {
                    data = null;
                }
                return { status: res.status, data: data, esJson: data !== null && typeof data === 'object' };
            });
        }, function (err) {
            clearTimeout(temporizador);
            throw err;
        });
    }

    /* ------------------------------ Estado de conexion ------------------------------ */

    function disponible() {
        return estado.navegadorEnLinea && estado.servidorDisponible;
    }

    function actualizarEstadoVisual() {
        var ok = disponible();
        el.root.classList.toggle('jdn-sin-conexion', !ok);
        var texto = ok ? 'Pregúntale a Jordania' : (estado.navegadorEnLinea && estado.mensajeServidor ? 'Jordania no está disponible: ' + estado.mensajeServidor : TEXTO_SIN_CONEXION);
        el.boton.title = texto;
        el.boton.setAttribute('aria-label', texto);
        el.subtitulo.textContent = ok ? 'Asistente IA de posMe' : (estado.navegadorEnLinea && estado.mensajeServidor ? 'No disponible' : 'Sin conexión a internet');
        var bloquear = !ok || estado.enviando;
        el.input.disabled = !ok;
        el.enviar.disabled = bloquear;
        el.input.placeholder = ok ? 'Escribe tu pregunta para Jordania…' : texto;
        Array.prototype.forEach.call(el.sugerencias.querySelectorAll('button'), function (b) { b.disabled = bloquear; });
    }

    /** Consulta en segundo plano si el servidor puede llegar a la API. */
    function revisarServidor() {
        if (!estado.navegadorEnLinea) {
            return;
        }
        peticion('GET', 'app_ai_chat/status', null, 15000).then(function (r) {
            if (r.esJson && r.data.ok) {
                estado.servidorDisponible = !!r.data.disponible;
                estado.mensajeServidor = r.data.disponible ? '' : (r.data.error === 'sin_conexion' ? '' : (r.data.message || ''));
            } else if (r.esJson && r.data.error) {
                estado.servidorDisponible = false;
                estado.mensajeServidor = r.data.message || '';
            }
            actualizarEstadoVisual();
            programarReintento();
        }, function () {
            // Sin respuesta del servidor local: no se marca como caido para no bloquear; se reintentara
            programarReintento();
        });
    }

    function programarReintento() {
        if (estado.reintento) {
            clearTimeout(estado.reintento);
        }
        // Si no esta disponible se revisa cada minuto; si lo esta, cada 10 minutos
        estado.reintento = setTimeout(revisarServidor, disponible() ? 600000 : 60000);
    }

    /* ------------------------------ Mensajes ------------------------------ */

    function agregarBurbuja(rol, texto, esError) {
        var fila = document.createElement('div');
        fila.className = 'jdn-fila ' + (rol === 'user' ? 'jdn-fila-usuario' : 'jdn-fila-jordania');

        var burbuja = document.createElement('div');
        burbuja.className = 'jdn-burbuja' + (esError ? ' jdn-burbuja-error' : '');

        if (rol === 'user') {
            burbuja.textContent = texto;
        } else {
            var avatar = document.createElement('span');
            avatar.className = 'jdn-avatar';
            avatar.setAttribute('aria-hidden', 'true');
            avatar.textContent = 'J';
            fila.appendChild(avatar);

            var nombre = document.createElement('span');
            nombre.className = 'jdn-nombre';
            nombre.textContent = 'Jordania';
            burbuja.appendChild(nombre);

            var cuerpo = document.createElement('div');
            if (esError) {
                cuerpo.textContent = texto;
            } else {
                renderizarMarkdown(document, cuerpo, texto);
            }
            burbuja.appendChild(cuerpo);
        }

        fila.appendChild(burbuja);
        el.mensajes.appendChild(fila);
        el.mensajes.scrollTop = el.mensajes.scrollHeight;
        return fila;
    }

    function mostrarEscribiendo(mostrar) {
        if (mostrar) {
            if (!el.escribiendo) {
                el.escribiendo = agregarBurbuja('assistant', '');
                el.escribiendo.querySelector('.jdn-burbuja div').className = 'jdn-escribiendo';
                el.escribiendo.querySelector('.jdn-burbuja div').textContent = 'Jordania está escribiendo…';
            }
        } else if (el.escribiendo) {
            el.escribiendo.parentNode.removeChild(el.escribiendo);
            el.escribiendo = null;
        }
    }

    function pintarSugerencias(mostrar) {
        while (el.sugerencias.firstChild) {
            el.sugerencias.removeChild(el.sugerencias.firstChild);
        }
        if (!mostrar) {
            return;
        }
        SUGERENCIAS.forEach(function (texto) {
            var b = document.createElement('button');
            b.type = 'button';
            b.className = 'jdn-chip';
            b.textContent = texto;
            b.addEventListener('click', function () {
                try {
                    enviar(texto);
                } catch (e) {
                    registrarError(e);
                }
            });
            el.sugerencias.appendChild(b);
        });
        actualizarEstadoVisual();
    }

    function limpiarMensajes() {
        while (el.mensajes.firstChild) {
            el.mensajes.removeChild(el.mensajes.firstChild);
        }
        el.escribiendo = null;
        agregarBurbuja('assistant', BIENVENIDA);
    }

    /** Mensaje de error segun la respuesta del servidor. */
    function mensajeDeError(r) {
        if (r.status === 401 || (!r.esJson && r.status >= 200)) {
            return MSG_SESION;
        }
        if (r.esJson && r.data.message) {
            return r.data.message;
        }
        return 'Jordania no pudo responder en este momento. Intenta de nuevo.';
    }

    /* ------------------------------ Acciones ------------------------------ */

    function cargarHistorial() {
        estado.historialCargado = true;
        limpiarMensajes();
        pintarSugerencias(true);
        if (!estado.navegadorEnLinea) {
            return;
        }
        peticion('GET', 'app_ai_chat/history', null, 15000).then(function (r) {
            if (!r.esJson || !r.data.ok || !Array.isArray(r.data.mensajes)) {
                return;
            }
            if (r.data.mensajes.length > 0) {
                pintarSugerencias(false);
            }
            r.data.mensajes.forEach(function (m) {
                if (m.texto) {
                    agregarBurbuja(m.rol === 'user' ? 'user' : 'assistant', m.texto);
                }
            });
        }, function () { /* sin historial: no es critico */ });
    }

    function enviar(texto) {
        texto = String(texto || '').trim();
        if (texto === '' || estado.enviando || !disponible()) {
            return;
        }

        estado.enviando = true;
        pintarSugerencias(false);
        agregarBurbuja('user', texto);
        el.input.value = '';
        ajustarAltura();
        mostrarEscribiendo(true);
        actualizarEstadoVisual();

        peticion('POST', 'app_ai_chat/send', { pregunta: texto }).then(function (r) {
            mostrarEscribiendo(false);
            if (r.esJson && r.data.ok) {
                agregarBurbuja('assistant', r.data.respuesta || '');
            } else {
                agregarBurbuja('assistant', mensajeDeError(r), true);
                if (r.esJson && (r.data.error === 'sin_conexion' || r.data.error === 'tiempo_agotado')) {
                    estado.servidorDisponible = r.data.error !== 'sin_conexion';
                    programarReintento();
                }
            }
        }, function () {
            // Error de red o timeout: se deshabilita y se revisa de nuevo en segundo plano
            mostrarEscribiendo(false);
            agregarBurbuja('assistant', MSG_RED, true);
            estado.servidorDisponible = false;
            estado.mensajeServidor = '';
            programarReintento();
        }).then(function () {
            estado.enviando = false;
            actualizarEstadoVisual();
            if (disponible()) {
                el.input.focus();
            }
        });
    }

    function nuevaConversacion() {
        if (estado.enviando) {
            return;
        }
        limpiarMensajes();
        pintarSugerencias(true);
        if (estado.navegadorEnLinea) {
            peticion('POST', 'app_ai_chat/reset', {}, 15000).then(function () {}, function () {});
        }
    }

    function abrir(abrirPanel) {
        estado.abierto = abrirPanel;
        el.panel.hidden = !abrirPanel;
        el.boton.setAttribute('aria-expanded', abrirPanel ? 'true' : 'false');
        if (abrirPanel) {
            if (!estado.historialCargado) {
                cargarHistorial();
            }
            if (disponible()) {
                el.input.focus();
            }
        }
    }

    function ajustarAltura() {
        el.input.style.height = 'auto';
        el.input.style.height = Math.max(76, Math.min(el.input.scrollHeight, 160)) + 'px';
    }

    function registrarError(e) {
        if (global.console && console.warn) {
            console.warn('[Jordania]', e);
        }
    }

    /* ------------------------------ Inicio ------------------------------ */

    function iniciar() {
        try {
            el.root = document.getElementById('jdn-root');
            if (!el.root || el.root.getAttribute('data-iniciado') === '1') {
                return;
            }
            el.root.setAttribute('data-iniciado', '1');
            el.boton = document.getElementById('jdn-boton');
            el.panel = document.getElementById('jdn-panel');
            el.mensajes = document.getElementById('jdn-mensajes');
            el.sugerencias = document.getElementById('jdn-sugerencias');
            el.form = document.getElementById('jdn-form');
            el.input = document.getElementById('jdn-input');
            el.enviar = document.getElementById('jdn-enviar');
            el.subtitulo = document.getElementById('jdn-subtitulo');
            if (typeof fetch === 'undefined' || !el.boton || !el.panel) {
                return;
            }

            estado.urlBase = (el.root.getAttribute('data-url-base') || '').replace(/\/+$/, '');
            estado.navegadorEnLinea = navigator.onLine !== false;

            el.boton.addEventListener('click', function () {
                try { abrir(!estado.abierto); } catch (e) { registrarError(e); }
            });
            document.getElementById('jdn-cerrar').addEventListener('click', function () {
                try { abrir(false); } catch (e) { registrarError(e); }
            });
            document.getElementById('jdn-nueva').addEventListener('click', function () {
                try { nuevaConversacion(); } catch (e) { registrarError(e); }
            });
            el.form.addEventListener('submit', function (ev) {
                ev.preventDefault();
                try { enviar(el.input.value); } catch (e) { registrarError(e); }
            });
            // Enter hace salto de linea (multilinea); la pregunta solo se envia con el boton Enviar
            el.input.addEventListener('input', ajustarAltura);
            document.addEventListener('keydown', function (ev) {
                if (ev.key === 'Escape' && estado.abierto) {
                    abrir(false);
                }
            });

            global.addEventListener('online', function () {
                estado.navegadorEnLinea = true;
                actualizarEstadoVisual();
                revisarServidor();
            });
            global.addEventListener('offline', function () {
                estado.navegadorEnLinea = false;
                actualizarEstadoVisual();
            });

            el.root.style.display = '';
            actualizarEstadoVisual();

            // Revision del servidor en segundo plano (no bloquea la carga de la pantalla)
            setTimeout(revisarServidor, 2500);
        } catch (e) {
            registrarError(e);
        }
    }

    if (document.readyState === 'complete') {
        setTimeout(iniciar, 300);
    } else {
        global.addEventListener('load', function () { setTimeout(iniciar, 300); });
    }
})(typeof window !== 'undefined' ? window : this);
