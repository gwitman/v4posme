/*
 * posme_invoice_discount.js
 * Calculo del descuento por linea / global de la factura (app_invoice_billing).
 * Solo se usa cuando el parametro INVOICE_BILLING_DISCOUNT_BY_ITEM = "true".
 * Funciones puras: no tocan el DOM. El backend aplica las mismas reglas en
 * app/Libraries/core_web_invoice_discount.php y revalida al guardar.
 *
 * Reglas:
 *  - bruto de linea = precio * cantidad.
 *  - Descuento por linea: % <-> monto (monto = round2(bruto * % / 100);
 *    % = monto / bruto * 100 con 4 decimales). Monto en [0, bruto], % en [0, 100].
 *  - Descuento global: base = SUB TOTAL bruto sin IVA; se prorratea por linea
 *    desc_i = round2(bruto_i * montoGlobal / subtotal) y el residuo va a la
 *    ultima linea con bruto > 0.
 *  - IVA sobre el neto de cada linea: iva_i = (bruto_i - desc_i) * ivaPct.
 *  - Servicio (taxServices) sobre el bruto.
 *  - TOTAL = SUB TOTAL - DESC + IVA + SERVICIO.
 *
 * Modulo UMD: window.PosmeInvoiceDiscount en el navegador y module.exports en Jest.
 */
(function (root, factory) {
    if (typeof module === 'object' && module.exports) {
        module.exports = factory();
    } else {
        root.PosmeInvoiceDiscount = factory();
    }
}(typeof self !== 'undefined' ? self : this, function () {
    'use strict';

    /**
     * Convierte un valor (numero o texto con comas de miles) a numero; invalido -> 0.
     * @param {*} valor
     * @return {number}
     */
    function num(valor) {
        if (valor === undefined || valor === null) {
            return 0;
        }
        var n = parseFloat(String(valor).replace(/,/g, ''));
        return isFinite(n) ? n : 0;
    }

    /**
     * Redondeo "mitad lejos de cero" a N decimales (igual que round() de PHP),
     * evitando el error de coma flotante (1.005 -> 1.01).
     * @param {*} valor
     * @param {number} decimales
     * @return {number}
     */
    function redondear(valor, decimales) {
        var n = num(valor);
        var signo = n < 0 ? -1 : 1;
        var abs = Math.abs(n);
        if (abs < 0.000001) {
            return 0;
        }
        var limpio = String(parseFloat(abs.toPrecision(15)));
        var r = Number(Math.round(Number(limpio + 'e' + decimales)) + 'e-' + decimales);
        return signo * r;
    }

    /**
     * Redondeo a 2 decimales.
     * @param {*} valor
     * @return {number}
     */
    function round2(valor) {
        return redondear(valor, 2);
    }

    /**
     * Redondeo a 4 decimales (porcentajes de descuento).
     * @param {*} valor
     * @return {number}
     */
    function round4(valor) {
        return redondear(valor, 4);
    }

    /**
     * Decide que dato manda cuando ya existe un descuento guardado:
     * si el % guardado corresponde al monto con la base actual, se respeta el monto ('valor');
     * si no (cambio cantidad/precio), se conserva el % y se recalcula el monto ('pct').
     * @param {number} base   bruto de la linea o subtotal de la factura
     * @param {number} pct    porcentaje guardado
     * @param {number} valor  monto guardado
     * @return {string} 'valor' | 'pct'
     */
    function resolverOrigen(base, pct, valor) {
        base  = num(base);
        pct   = num(pct);
        valor = num(valor);
        if (base <= 0 || valor <= 0) {
            return 'pct';
        }
        return round4(valor / base * 100) === round4(pct) ? 'valor' : 'pct';
    }

    /**
     * Calcula una linea con su descuento propio.
     * @param {object} p {precio, cantidad, ivaPct, taxServicesPct, descPct, descValor, origen:'pct'|'valor'}
     * @return {object} {bruto, descPct, descValor, neto, iva, servicio, total}
     */
    function calcLinea(p) {
        p = p || {};
        var bruto    = num(p.precio) * num(p.cantidad);
        var ivaPct   = num(p.ivaPct);
        var servPct  = num(p.taxServicesPct);
        var descPct  = 0;
        var descVal  = 0;

        if (bruto > 0) {
            if (p.origen === 'valor') {
                descVal = round2(p.descValor);
                descPct = round4(descVal / bruto * 100);
            } else {
                descPct = round4(p.descPct);
                descVal = round2(bruto * descPct / 100);
            }
        }

        var neto     = bruto - descVal;
        var iva      = neto * ivaPct;
        var servicio = bruto * servPct;

        return {
            bruto     : bruto,
            descPct   : descPct,
            descValor : descVal,
            neto      : neto,
            iva       : iva,
            servicio  : servicio,
            total     : neto + iva + servicio
        };
    }

    /**
     * Prorratea un descuento global entre las lineas segun su bruto.
     * @param {Array} lineas       [{bruto}]
     * @param {number} montoGlobal monto del descuento global
     * @return {Array} descuento de cada linea (mismo orden)
     */
    function prorratearGlobal(lineas, montoGlobal) {
        lineas = lineas || [];
        var monto     = round2(montoGlobal);
        var subtotal  = 0;
        var resultado = [];
        var suma      = 0;
        var ultima    = -1;
        var i;

        for (i = 0; i < lineas.length; i++) {
            subtotal += num(lineas[i].bruto);
        }
        // Igual que el servidor: el global no puede superar el subtotal
        monto = Math.min(monto, round2(subtotal));
        for (i = 0; i < lineas.length; i++) {
            var bruto = num(lineas[i].bruto);
            var desc  = 0;
            if (subtotal > 0 && monto > 0 && bruto > 0) {
                desc   = round2(bruto * monto / subtotal);
                ultima = i;
            }
            suma += desc;
            resultado.push(desc);
        }
        // Residuo del redondeo: se aplica desde la ultima linea con bruto > 0 hacia arriba,
        // sin que ninguna linea quede con descuento < 0 ni mayor a su bruto.
        if (ultima >= 0) {
            var residuo = round2(monto - suma);
            for (i = ultima; i >= 0 && residuo !== 0; i--) {
                var tope = round2(num(lineas[i].bruto));
                if (tope <= 0) {
                    continue;
                }
                var nuevo    = Math.min(Math.max(round2(resultado[i] + residuo), 0), tope);
                var aplicado = round2(nuevo - resultado[i]);
                resultado[i] = nuevo;
                residuo      = round2(residuo - aplicado);
            }
        }
        return resultado;
    }

    /**
     * Calcula el resumen de la factura.
     * @param {Array} lineas       [{precio, cantidad, ivaPct, taxServicesPct, descPct, descValor, origen}]
     * @param {string} modo        'none' | 'line' | 'global'
     * @param {number} montoGlobal monto del descuento global (solo modo 'global')
     * @return {object} {subtotal, iva, servicio, desc, total, pctGlobal, lineas:[{bruto, desc, descPct, neto, iva, servicio, total}]}
     */
    function calcResumen(lineas, modo, montoGlobal) {
        lineas = lineas || [];
        var calculadas = [];
        var i;

        for (i = 0; i < lineas.length; i++) {
            var l = lineas[i] || {};
            if (modo === 'line') {
                calculadas.push(calcLinea(l));
            } else {
                calculadas.push(calcLinea({
                    precio         : l.precio,
                    cantidad       : l.cantidad,
                    ivaPct         : l.ivaPct,
                    taxServicesPct : l.taxServicesPct,
                    descPct        : 0,
                    descValor      : 0,
                    origen         : 'pct'
                }));
            }
        }

        // Modo global: el monto se reparte y el IVA se recalcula sobre el neto
        if (modo === 'global') {
            var prorrateo = prorratearGlobal(calculadas, montoGlobal);
            for (i = 0; i < calculadas.length; i++) {
                var c        = calculadas[i];
                var ivaPctL  = num(lineas[i].ivaPct);
                c.descValor  = prorrateo[i];
                c.descPct    = c.bruto > 0 ? round4(c.descValor / c.bruto * 100) : 0;
                c.neto       = c.bruto - c.descValor;
                c.iva        = c.neto * ivaPctL;
                c.total      = c.neto + c.iva + c.servicio;
            }
        }

        var subtotal = 0;
        var iva      = 0;
        var servicio = 0;
        var desc     = 0;
        var salida   = [];

        for (i = 0; i < calculadas.length; i++) {
            subtotal += calculadas[i].bruto;
            iva      += calculadas[i].iva;
            servicio += calculadas[i].servicio;
            desc     += calculadas[i].descValor;
            salida.push({
                bruto    : calculadas[i].bruto,
                desc     : calculadas[i].descValor,
                descPct  : calculadas[i].descPct,
                neto     : calculadas[i].neto,
                iva      : calculadas[i].iva,
                servicio : calculadas[i].servicio,
                total    : calculadas[i].total
            });
        }

        return {
            subtotal  : round2(subtotal),
            iva       : round2(iva),
            servicio  : round2(servicio),
            desc      : round2(desc),
            total     : round2(subtotal - desc + iva + servicio),
            pctGlobal : subtotal > 0 ? round4(desc / subtotal * 100) : 0,
            lineas    : salida
        };
    }

    /**
     * Valida un descuento (de linea o global) contra su base.
     * @param {object} p {campo:'pct'|'valor', valor, base}
     * @return {object} {ok, mensaje}
     */
    function validarDescuento(p) {
        p = p || {};
        var texto = (p.valor === undefined || p.valor === null) ? '' : String(p.valor).replace(/,/g, '').trim();
        var valor = texto === '' ? 0 : parseFloat(texto);
        var base  = num(p.base);

        if (!isFinite(valor) || !/^-?\d*\.?\d*$/.test(texto)) {
            return { ok: false, mensaje: 'El descuento debe ser un numero valido' };
        }
        if (valor < 0) {
            return { ok: false, mensaje: 'El descuento no puede ser negativo' };
        }
        if (p.campo === 'pct') {
            if (valor > 100) {
                return { ok: false, mensaje: 'El porcentaje de descuento debe estar entre 0 y 100' };
            }
        } else {
            if (round2(valor) > round2(base)) {
                return { ok: false, mensaje: 'El descuento no puede ser mayor que el monto (' + round2(base).toFixed(2) + ')' };
            }
        }
        if (valor > 0 && base <= 0) {
            return { ok: false, mensaje: 'No se puede aplicar descuento sobre un monto en 0' };
        }
        return { ok: true, mensaje: '' };
    }

    return {
        num              : num,
        round2           : round2,
        round4           : round4,
        resolverOrigen   : resolverOrigen,
        calcLinea        : calcLinea,
        prorratearGlobal : prorratearGlobal,
        calcResumen      : calcResumen,
        validarDescuento : validarDescuento
    };
}));
