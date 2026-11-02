/*
 * posme-report.js
 * Utilidades comunes para los reportes de posMe (ES5, sin jQuery).
 *
 * - Exporta a Excel exactamente lo que el usuario ve (respeta filtros y filas ocultas).
 * - Imprime el reporte.
 * - Las funciones puras (parseNumero, esTextoConCeros, escaparHtml, coincideFiltro,
 *   nombreArchivo, documentoExcel) funcionan tambien en node (sin window/document),
 *   lo que permite probarlas con Jest.
 */
(function (global) {
    'use strict';

    var FORMATO_NUMERO = '\\#\\,\\#\\#0\\.00';
    var FORMATO_TEXTO  = '\\@';
    var REGEX_NUMERO_FORMATEADO = /^-?\(?[\d,]+\.\d{1,4}\)?$/;

    /**
     * Convierte un texto con formato visual (1,234.56 / -1,000.00 / (500.00)) a numero puro.
     * Devuelve null si el texto no representa un numero.
     */
    function parseNumero(texto) {
        if (texto === null || texto === undefined) {
            return null;
        }
        var t = String(texto).replace(/[\s ]+/g, '');
        if (t === '') {
            return null;
        }

        var negativo = false;

        // Contabilidad: (500.00) significa negativo
        if (/^\(.*\)$/.test(t)) {
            negativo = true;
            t = t.substring(1, t.length - 1);
        }
        if (t.charAt(0) === '-') {
            negativo = !negativo;
            t = t.substring(1);
        }
        // Tambien se acepta -(500.00)
        if (/^\(.*\)$/.test(t)) {
            negativo = !negativo;
            t = t.substring(1, t.length - 1);
        }

        // Las comas solo pueden ser separadores de miles
        if (!/^(\d{1,3}(,\d{3})+|\d+)?(\.\d+)?$/.test(t) || t === '' || t === '.') {
            return null;
        }

        var valor = parseFloat(t.replace(/,/g, ''));
        if (isNaN(valor)) {
            return null;
        }
        return negativo ? -valor : valor;
    }

    /**
     * Indica si el texto es un codigo numerico con ceros a la izquierda ("00123")
     * que debe conservarse como texto al exportar.
     */
    function esTextoConCeros(texto) {
        if (texto === null || texto === undefined) {
            return false;
        }
        return /^0\d+$/.test(String(texto).replace(/^\s+|\s+$/g, ''));
    }

    /**
     * Escapa los caracteres especiales de HTML.
     */
    function escaparHtml(s) {
        if (s === null || s === undefined) {
            return '';
        }
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    /**
     * Evalua si el texto de una celda cumple el filtro escrito por el usuario.
     * - Vacio: siempre coincide.
     * - ">100", "<0", ">=5", "<=5", "=10": comparacion numerica.
     * - Otro texto: la celda lo contiene (sin distinguir mayusculas).
     */
    function coincideFiltro(textoCelda, filtro) {
        var f = (filtro === null || filtro === undefined) ? '' : String(filtro).replace(/^\s+|\s+$/g, '');
        if (f === '') {
            return true;
        }
        var celda = (textoCelda === null || textoCelda === undefined) ? '' : String(textoCelda);

        var m = /^(>=|<=|>|<|=)\s*(.+)$/.exec(f);
        if (m) {
            var limite = parseNumero(m[2]);
            if (limite !== null) {
                var valor = parseNumero(celda);
                if (valor === null) {
                    return false;
                }
                switch (m[1]) {
                    case '>':  return valor > limite;
                    case '<':  return valor < limite;
                    case '>=': return valor >= limite;
                    case '<=': return valor <= limite;
                    default:   return valor === limite;
                }
            }
        }
        return celda.toLowerCase().indexOf(f.toLowerCase()) !== -1;
    }

    /**
     * Fecha local actual en formato YYYY-MM-DD.
     */
    function fechaHoyISO() {
        var d  = new Date();
        var mm = d.getMonth() + 1;
        var dd = d.getDate();
        return d.getFullYear() + '-' + (mm < 10 ? '0' : '') + mm + '-' + (dd < 10 ? '0' : '') + dd;
    }

    /**
     * Construye el nombre del archivo Excel: "Detalle de Venta", "2026-11-01" -> "detalle_de_venta_20261101.xls".
     */
    function nombreArchivo(titulo, fechaISO) {
        var base = (titulo === null || titulo === undefined) ? '' : String(titulo).toLowerCase();
        if (typeof base.normalize === 'function') {
            base = base.normalize('NFD').replace(/[̀-ͯ]/g, '');
        }
        base = base
            .replace(/[^a-z0-9]+/g, '_')
            .replace(/_+/g, '_')
            .replace(/^_+|_+$/g, '');
        if (base === '') {
            base = 'reporte';
        }

        var fecha = (fechaISO === null || fechaISO === undefined || String(fechaISO) === '') ? fechaHoyISO() : String(fechaISO);
        fecha = fecha.replace(/[^0-9]/g, '');

        return base + '_' + fecha + '.xls';
    }

    /**
     * Nombre valido para la hoja de Excel: maximo 31 caracteres y sin : \ / ? * [ ].
     */
    function nombreHoja(titulo) {
        var n = (titulo === null || titulo === undefined) ? '' : String(titulo);
        n = n.replace(/[:\\\/\?\*\[\]]/g, '').replace(/\s+/g, ' ').replace(/^\s+|\s+$/g, '');
        n = n.substring(0, 31).replace(/\s+$/, '');
        return n === '' ? 'Reporte' : n;
    }

    /**
     * Arma el documento HTML que Excel interpreta como libro (sin BOM; el BOM se agrega al Blob).
     */
    function documentoExcel(cuerpoHtml, titulo) {
        var hoja = escaparHtml(nombreHoja(titulo));
        return '' +
            '<html xmlns:o="urn:schemas-microsoft-com:office:office" ' +
            'xmlns:x="urn:schemas-microsoft-com:office:excel" ' +
            'xmlns="http://www.w3.org/TR/REC-html40">' +
            '<head>' +
            '<meta charset="utf-8">' +
            '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' +
            '<title>' + escaparHtml(titulo || 'reporte') + '</title>' +
            '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>' +
            '<x:Name>' + hoja + '</x:Name>' +
            '<x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions>' +
            '</x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->' +
            '<style>' +
            'table{border-collapse:collapse;}' +
            'td,th{vertical-align:top;}' +
            '.pr-xnum{mso-number-format:"' + FORMATO_NUMERO + '";}' +
            '.pr-xtxt{mso-number-format:"' + FORMATO_TEXTO + '";}' +
            '</style>' +
            '</head>' +
            '<body>' + (cuerpoHtml || '') + '</body>' +
            '</html>';
    }

    /* ------------------------------------------------------------------ */
    /* Funciones de DOM (solo navegador)                                   */
    /* ------------------------------------------------------------------ */

    var ETIQUETAS_CON_ESTILO = {
        TABLE: 1, TR: 1, TD: 1, TH: 1, CAPTION: 1,
        H1: 1, H2: 1, H3: 1, H4: 1, H5: 1, H6: 1,
        P: 1, SPAN: 1, B: 1, STRONG: 1, A: 1, DIV: 1, FONT: 1
    };

    function tieneClase(el, clase) {
        return !!(el && typeof el.className === 'string' &&
            (' ' + el.className + ' ').indexOf(' ' + clase + ' ') !== -1);
    }

    function agregarEstilo(el, css) {
        var actual = el.getAttribute('style') || '';
        actual = actual.replace(/^\s+|\s+$/g, '');
        if (actual !== '' && actual.charAt(actual.length - 1) !== ';') {
            actual += ';';
        }
        el.setAttribute('style', actual + css);
    }

    function agregarClase(el, clase) {
        el.className = (el.className ? el.className + ' ' : '') + clase;
    }

    /**
     * Copia como estilo inline los estilos calculados relevantes del nodo original al clon.
     */
    function copiarEstilos(original, clon, cs) {
        var css = '';
        var fondo = cs.backgroundColor;
        if (fondo && fondo !== 'transparent' && fondo !== 'rgba(0, 0, 0, 0)') {
            css += 'background-color:' + fondo + ';';
        }
        if (cs.color) {
            css += 'color:' + cs.color + ';';
        }
        if (cs.fontWeight) {
            css += 'font-weight:' + cs.fontWeight + ';';
        }
        if (cs.textAlign && original.tagName !== 'TR' && original.tagName !== 'TABLE') {
            css += 'text-align:' + cs.textAlign + ';';
        }
        // El shorthand "border" no siempre viene calculado: se arma por cada lado
        var lados = ['Top', 'Right', 'Bottom', 'Left'];
        var i;
        for (i = 0; i < lados.length; i++) {
            var estilo = cs['border' + lados[i] + 'Style'];
            if (estilo && estilo !== 'none' && estilo !== 'hidden') {
                css += 'border-' + lados[i].toLowerCase() + ':' +
                    cs['border' + lados[i] + 'Width'] + ' ' + estilo + ' ' +
                    cs['border' + lados[i] + 'Color'] + ';';
            }
        }
        if (css !== '') {
            agregarEstilo(clon, css);
        }
    }

    function quitarNodos(raiz, selector) {
        var nodos = raiz.querySelectorAll(selector);
        var i;
        for (i = nodos.length - 1; i >= 0; i--) {
            if (nodos[i].parentNode) {
                nodos[i].parentNode.removeChild(nodos[i]);
            }
        }
    }

    /**
     * Convierte las celdas numericas a valor puro con formato de Excel
     * y protege como texto los codigos con ceros a la izquierda.
     */
    function prepararCeldas(raiz) {
        var celdas = raiz.getElementsByTagName('td');
        var i;
        for (i = 0; i < celdas.length; i++) {
            var td = celdas[i];
            if (td.getElementsByTagName('table').length > 0) {
                continue;
            }
            var texto = (td.textContent || '').replace(/^\s+|\s+$/g, '');
            var valor = null;

            if (tieneClase(td, 'pr-num') && td.getAttribute('data-num') !== null && td.getAttribute('data-num') !== '') {
                valor = parseFloat(td.getAttribute('data-num'));
                if (isNaN(valor)) {
                    valor = null;
                }
            } else if (esTextoConCeros(texto)) {
                agregarClase(td, 'pr-xtxt');
                agregarEstilo(td, "mso-number-format:'" + FORMATO_TEXTO + "';");
                continue;
            } else if (REGEX_NUMERO_FORMATEADO.test(texto)) {
                valor = parseNumero(texto);
            }

            if (valor !== null) {
                td.textContent = String(valor);
                agregarClase(td, 'pr-xnum');
                agregarEstilo(td, "mso-number-format:'" + FORMATO_NUMERO + "';");
            }
        }
    }

    /**
     * Descarga el html como archivo .xls; en iOS (o si falla la descarga) abre el blob en otra ventana.
     */
    function descargar(html, archivo) {
        var blob = new Blob(['﻿' + html], { type: 'application/vnd.ms-excel;charset=utf-8' });

        if (window.navigator && typeof window.navigator.msSaveOrOpenBlob === 'function') {
            window.navigator.msSaveOrOpenBlob(blob, archivo);
            return;
        }

        var url = (window.URL || window.webkitURL).createObjectURL(blob);
        var esIOS = /iPad|iPhone|iPod/.test(window.navigator.userAgent || '');
        try {
            var a = document.createElement('a');
            if (esIOS || !('download' in a)) {
                throw new Error('descarga no soportada');
            }
            a.href = url;
            a.download = archivo;
            a.style.display = 'none';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        } catch (e) {
            window.open(url, '_blank');
        }
        setTimeout(function () {
            (window.URL || window.webkitURL).revokeObjectURL(url);
        }, 60000);
    }

    /**
     * Exporta a Excel el contenido visible del reporte.
     */
    function exportar() {
        if (typeof document === 'undefined' || typeof window === 'undefined') {
            return;
        }
        var original = document.body;
        var clon = original.cloneNode(true);

        // Recorrido paralelo (mismo orden de elementos) antes de quitar nodos
        var elsOriginal = original.getElementsByTagName('*');
        var elsClon = clon.getElementsByTagName('*');
        var total = Math.min(elsOriginal.length, elsClon.length);
        var i;
        for (i = 0; i < total; i++) {
            var o = elsOriginal[i];
            var c = elsClon[i];
            if (!ETIQUETAS_CON_ESTILO[o.tagName]) {
                continue;
            }
            var cs = window.getComputedStyle(o);
            if (!cs) {
                continue;
            }
            if (o.tagName === 'TR' && cs.display === 'none') {
                c.setAttribute('data-pr-oculto', '1');
                continue;
            }
            copiarEstilos(o, c, cs);
        }

        quitarNodos(clon, 'script, style, link, noscript, .pr-noexport, input, button, select, img, tr[data-pr-oculto]');
        prepararCeldas(clon);

        var nodoTitulo = document.querySelector('.pr-title');
        var titulo = nodoTitulo ? (nodoTitulo.textContent || '').replace(/\s+/g, ' ').replace(/^\s+|\s+$/g, '') : '';
        if (titulo === '') {
            titulo = 'reporte';
        }

        descargar(documentoExcel(clon.innerHTML, titulo), nombreArchivo(titulo, fechaHoyISO()));
    }

    /**
     * Imprime el reporte.
     */
    function imprimir() {
        if (typeof window !== 'undefined' && typeof window.print === 'function') {
            window.print();
        }
    }

    var PosmeReport = {
        parseNumero: parseNumero,
        esTextoConCeros: esTextoConCeros,
        escaparHtml: escaparHtml,
        coincideFiltro: coincideFiltro,
        nombreArchivo: nombreArchivo,
        documentoExcel: documentoExcel,
        exportar: exportar,
        imprimir: imprimir
    };

    if (typeof window !== 'undefined') {
        window.PosmeReport = PosmeReport;
    }

    /* Enlace delegado de los botones; una sola vez aunque el script se incluya varias veces */
    if (typeof document !== 'undefined' && typeof window !== 'undefined' && !window.__posmeReportEnlazado) {
        window.__posmeReportEnlazado = true;
        document.addEventListener('click', function (e) {
            var el = e.target;
            while (el && el !== document) {
                if (tieneClase(el, 'pr-btn-excel')) {
                    e.preventDefault();
                    window.PosmeReport.exportar();
                    return;
                }
                if (tieneClase(el, 'pr-btn-print')) {
                    e.preventDefault();
                    window.PosmeReport.imprimir();
                    return;
                }
                el = el.parentNode;
            }
        }, false);
    }

    if (typeof module !== 'undefined' && module.exports) {
        module.exports = PosmeReport;
    }
}(typeof window !== 'undefined' ? window : this));
