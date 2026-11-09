/**
 * posme-qztray.js
 *
 * Impresión directa del documento de facturación mediante QZ Tray.
 * Requiere que antes se cargue la librería oficial qz-tray.js (global `qz`)
 * y la función fnShowNotification (genyx-fn.js).
 *
 * Flujo:
 *   1. Configura certificado y firma (endpoints qzCertificate / qzSign).
 *   2. Conecta (o reutiliza) el websocket de QZ Tray.
 *   3. Descarga el PDF desde la misma URL de impresión que usa la pantalla.
 *   4. Lo envía a la impresora configurada (o a la predeterminada).
 * Ante cualquier error muestra una advertencia y ejecuta el respaldo (onFallback).
 */
var PosmeQzTray = (function () {

    var seguridadConfigurada = false;
    var imprimiendo          = false;
    var BOTON_DOCUMENTO      = '#btnAceptarDialogPrinterV2AceptarDocument';

    /**
     * Muestra una notificación usando la función estándar de la pantalla.
     *
     * @param {string} mensaje Texto a mostrar.
     * @param {string} tipo    success | warning | error.
     */
    function notificar(mensaje, tipo) {
        if (typeof fnShowNotification === 'function') {
            fnShowNotification(mensaje, tipo);
        } else {
            console.info(mensaje);
        }
    }

    /**
     * Crea un Error con un mensaje legible para el usuario, conservando la causa.
     *
     * @param {string} mensaje Motivo en español.
     * @param {*}      causa   Error original.
     * @return {Error}
     */
    function errorConMotivo(mensaje, causa) {
        var error = new Error(mensaje);
        error.causa = causa;
        return error;
    }

    /**
     * Configura una sola vez el certificado público y la firma de las peticiones.
     * El certificado se obtiene por GET y la firma por POST (SHA512) desde el servidor;
     * la clave privada nunca llega al navegador.
     *
     * @param {string} baseUrl URL base del sistema.
     */
    function configurarSeguridad(baseUrl) {
        if (seguridadConfigurada) {
            return;
        }

        qz.security.setCertificatePromise(function (resolve, reject) {
            fetch(baseUrl + '/app_invoice_billing/qzCertificate', {
                method: 'GET',
                credentials: 'same-origin',
                cache: 'no-store'
            })
                .then(function (r) {
                    if (!r.ok) {
                        throw new Error('No se pudo obtener el certificado (HTTP ' + r.status + ')');
                    }
                    return r.text();
                })
                .then(resolve)
                .catch(reject);
        });

        qz.security.setSignatureAlgorithm('SHA512');

        qz.security.setSignaturePromise(function (toSign) {
            return function (resolve, reject) {
                fetch(baseUrl + '/app_invoice_billing/qzSign', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                    body: 'request=' + encodeURIComponent(toSign)
                })
                    .then(function (r) {
                        if (!r.ok) {
                            throw new Error('No se pudo firmar la petición (HTTP ' + r.status + ')');
                        }
                        return r.text();
                    })
                    .then(resolve)
                    .catch(reject);
            };
        });

        seguridadConfigurada = true;
    }

    /**
     * Conecta con QZ Tray reutilizando la conexión si ya está activa.
     *
     * @return {Promise}
     */
    function conectar() {
        if (qz.websocket.isActive()) {
            return Promise.resolve();
        }
        return qz.websocket.connect({ retries: 1, delay: 1 });
    }

    /**
     * Convierte un ArrayBuffer a base64 procesando por bloques
     * para no desbordar la pila con String.fromCharCode en archivos grandes.
     *
     * @param {ArrayBuffer} buffer Contenido binario.
     * @return {string} Contenido en base64.
     */
    function arrayBufferABase64(buffer) {
        var bytes  = new Uint8Array(buffer);
        var bloque = 0x8000;
        var partes = [];

        for (var i = 0; i < bytes.length; i += bloque) {
            partes.push(String.fromCharCode.apply(null, bytes.subarray(i, i + bloque)));
        }
        return btoa(partes.join(''));
    }

    /**
     * Descarga el documento desde la URL de impresión y lo devuelve en base64.
     * Solo se acepta una respuesta application/pdf.
     *
     * @param {string} url URL de la vista de impresión.
     * @return {Promise<string>} PDF en base64.
     */
    function obtenerPdfBase64(url) {
        return fetch(url, { method: 'GET', credentials: 'same-origin', cache: 'no-store' })
            .then(function (r) {
                if (!r.ok) {
                    throw new Error('La vista de impresión respondió con error (HTTP ' + r.status + ')');
                }
                var tipo = r.headers.get('content-type') || '';
                if (tipo.toLowerCase().indexOf('application/pdf') === -1) {
                    throw new Error('La vista de impresión no devolvió un PDF');
                }
                return r.arrayBuffer();
            })
            .then(function (buffer) {
                if (!buffer || buffer.byteLength === 0) {
                    throw new Error('La vista de impresión devolvió un PDF vacío');
                }
                return arrayBufferABase64(buffer);
            });
    }

    /**
     * Habilita o deshabilita el botón de impresión de documento.
     *
     * @param {boolean} deshabilitar
     */
    function bloquearBoton(deshabilitar) {
        if (typeof $ === 'undefined') {
            return;
        }
        var boton = $(BOTON_DOCUMENTO);
        if (deshabilitar) {
            boton.addClass('disabled').attr('aria-disabled', 'true').css('pointer-events', 'none');
        } else {
            boton.removeClass('disabled').removeAttr('aria-disabled').css('pointer-events', '');
        }
    }

    /**
     * Imprime el documento directamente en la impresora mediante QZ Tray.
     *
     * @param {Object}   opciones
     * @param {string}   opciones.baseUrl     URL base del sistema.
     * @param {string}   opciones.url         URL de la vista de impresión (PDF).
     * @param {string}   opciones.printerName Nombre de la impresora; vacío = predeterminada.
     * @param {Function} opciones.onFallback  Respaldo a ejecutar si algo falla (window.open actual).
     * @return {Promise}
     */
    function imprimirDocumento(opciones) {
        // Evita impresiones duplicadas por doble clic
        if (imprimiendo) {
            return Promise.resolve();
        }

        var baseUrl     = opciones.baseUrl;
        var url         = opciones.url;
        var printerName = (opciones.printerName || '').trim();
        var onFallback  = typeof opciones.onFallback === 'function' ? opciones.onFallback : function () {};
        var impresora   = '';

        imprimiendo = true;
        bloquearBoton(true);

        return Promise.resolve()
            .then(function () {
                if (typeof qz === 'undefined') {
                    throw new Error('La librería de QZ Tray no está cargada');
                }
                configurarSeguridad(baseUrl);
                return conectar().catch(function (e) {
                    throw errorConMotivo('QZ Tray no está instalado o no está abierto en este equipo', e);
                });
            })
            .then(function () {
                var busqueda = printerName !== '' ? qz.printers.find(printerName) : qz.printers.getDefault();
                return busqueda.catch(function (e) {
                    throw errorConMotivo(
                        printerName !== ''
                            ? 'No se encontró la impresora "' + printerName + '"'
                            : 'No hay impresora predeterminada configurada',
                        e
                    );
                });
            })
            .then(function (nombreImpresora) {
                if (!nombreImpresora) {
                    throw new Error('No se encontró la impresora para imprimir');
                }
                impresora = nombreImpresora;
                return obtenerPdfBase64(url);
            })
            .then(function (data) {
                var config = qz.configs.create(impresora);
                return qz.print(config, [{ type: 'pixel', format: 'pdf', flavor: 'base64', data: data }]);
            })
            .then(function () {
                notificar('Documento enviado a la impresora: ' + impresora, 'success');
            })
            .catch(function (e) {
                var motivo = (e && e.message) ? e.message : String(e);
                console.error('QZ Tray:', e && e.causa ? e.causa : e);
                notificar('No se pudo imprimir con QZ Tray: ' + motivo + '. Se abrirá la vista de impresión.', 'warning');
                onFallback();
            })
            .finally(function () {
                imprimiendo = false;
                bloquearBoton(false);
            });
    }

    return {
        configurarSeguridad: configurarSeguridad,
        conectar:            conectar,
        obtenerPdfBase64:    obtenerPdfBase64,
        imprimirDocumento:   imprimirDocumento
    };
})();
