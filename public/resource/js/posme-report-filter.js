/* ==========================================================================
   posMe - Pantallas de filtros de reportes (contenedor .prf)
   --------------------------------------------------------------------------
   Objetivo: en telefono/tableta tactil usar los controles NATIVOS del
   dispositivo (calendario y combo del sistema) en lugar de
   bootstrap-datepicker y select2, SOLO dentro de .prf.
   En PC, o fuera de .prf, todo se comporta exactamente igual que antes.

   Orden de carga (core_masterpage/default_report.php):
       1. jQuery, select2, genyx-app-init.js (head)
       2. bootstrap-datepicker (lo carga cada view_head dentro del body)
       3. core_template/posme_v2_common (pv2IsMobile, pv2MobileSelects)
       4. ESTE ARCHIVO
       5. $script (view_script de cada reporte)
   Asi, cuando el view_script llama $(...).datepicker() o genyx-app-init
   llama $(".select2").select2() en document.ready, ya usan los envoltorios.
   ES5: sin let/const/arrow functions.
   ========================================================================== */
(function (window, $) {
    'use strict';

    if (!$ || !$.fn) {
        return;
    }

    var SELECTOR_CONTENEDOR = '.prf';

    /* ----------------------------------------------------------------------
     * 1. Deteccion de dispositivo (se calcula una sola vez)
     * -------------------------------------------------------------------- */

    /**
     * Evalua una media query sin romper en navegadores sin matchMedia.
     * @param {string} consulta Media query.
     * @return {boolean}
     */
    function coincideMedia(consulta) {
        try {
            return !!(window.matchMedia && window.matchMedia(consulta).matches);
        } catch (e) {
            return false;
        }
    }

    /**
     * Indica si se deben usar controles nativos: telefono (pv2IsMobile o
     * ancho <= 767px) o tableta tactil (sin hover y puntero grueso).
     * @return {boolean}
     */
    function esDispositivoTactil() {
        if (typeof window.pv2IsMobile === 'function') {
            try {
                if (window.pv2IsMobile()) {
                    return true;
                }
            } catch (e) {
                /* se continua con la deteccion propia */
            }
        }
        if (window.matchMedia) {
            return coincideMedia('(hover:none) and (pointer:coarse)') || coincideMedia('(max-width:767px)');
        }
        return (window.innerWidth || 1024) <= 767;
    }

    var usarNativos = esDispositivoTactil();
    window.prfUsarNativos = usarNativos;

    if (!usarNativos) {
        //PC: no se toca nada
        return;
    }

    //Clases para el CSS: html.prf-native y .pv2-mobile en cada contenedor
    //(.pv2.pv2-mobile oculta select2 y muestra el combo nativo desde el inicio)
    document.documentElement.className += ' prf-native';
    $(SELECTOR_CONTENEDOR).addClass('pv2-mobile');

    /**
     * Copia las propiedades estaticas de un plugin (defaults, locales, etc.)
     * al envoltorio, para que el codigo que las usa siga funcionando.
     */
    function copiarPropiedades(origen, destino) {
        if (!origen) {
            return;
        }
        for (var clave in origen) {
            if (Object.prototype.hasOwnProperty.call(origen, clave)) {
                destino[clave] = origen[clave];
            }
        }
    }

    /**
     * Separa un conjunto jQuery en elementos dentro y fuera de .prf.
     * @return {{dentro: jQuery, fuera: jQuery}}
     */
    function separarPorContenedor($elementos) {
        var dentro = $elementos.filter(function () {
            return $(this).closest(SELECTOR_CONTENEDOR).length > 0;
        });
        return {
            dentro: dentro,
            fuera: $elementos.not(dentro)
        };
    }

    /* ----------------------------------------------------------------------
     * 2. Fechas: bootstrap-datepicker -> <input type="date">
     *    Patron de posme-invoice-billing-news.js (fechas nativas).
     * -------------------------------------------------------------------- */

    /**
     * Normaliza un valor de fecha a YYYY-MM-DD (formato del input date).
     * Acepta texto ("2024-01-30 00:00:00") u objeto Date.
     * @return {string} Fecha normalizada o "" si no es valida.
     */
    function normalizarFecha(valor) {
        if (valor === null || valor === undefined) {
            return '';
        }
        if (Object.prototype.toString.call(valor) === '[object Date]') {
            if (isNaN(valor.getTime())) {
                return '';
            }
            var mes = String(valor.getMonth() + 1);
            var dia = String(valor.getDate());
            return valor.getFullYear() + '-' + (mes.length < 2 ? '0' + mes : mes) + '-' + (dia.length < 2 ? '0' + dia : dia);
        }
        var texto = $.trim(String(valor)).substring(0, 10);
        return /^\d{4}-\d{2}-\d{2}$/.test(texto) ? texto : '';
    }

    /**
     * Convierte un input de texto en input nativo de fecha conservando su valor.
     * Si recibe el envoltorio (div.input-group.date) busca el input interno.
     */
    function convertirAFechaNativa(elemento) {
        var $el = $(elemento);
        if (!$el.is('input')) {
            $el = $el.find('input[type="text"], input:not([type])').first();
        }
        if ($el.length !== 1) {
            return;
        }
        var input = $el[0];
        if ($el.data('prfFechaNativa')) {
            return;
        }

        var valorAnterior = normalizarFecha(input.value);
        try {
            input.type = 'date';
        } catch (e) {
            return;
        }
        if (input.type !== 'date') {
            //Navegador sin soporte: queda como texto (yyyy-mm-dd manual)
            return;
        }
        $el.data('prfFechaNativa', true).addClass('prf-date-native');

        //El icono de calendario del tema ya no sirve: se oculta
        var $grupo = $el.closest('.input-group.date');
        if ($grupo.length > 0) {
            $grupo.find('.input-group-addon').hide();
            $grupo.addClass('prf-date-native-group');
        }
        input.value = valorAnterior;
    }

    if (typeof $.fn.datepicker === 'function') {
        var datepickerOriginal = $.fn.datepicker;

        var datepickerEnvoltorio = function (opcion) {
            var partes = separarPorContenedor(this);

            //Ningun elemento dentro de .prf: comportamiento original intacto
            if (partes.dentro.length === 0) {
                return datepickerOriginal.apply(this, arguments);
            }

            //Mezcla: los de fuera de .prf siguen con el plugin
            if (partes.fuera.length > 0) {
                datepickerOriginal.apply(partes.fuera, arguments);
            }

            if (partes.dentro.length > 0) {
                if (typeof opcion === 'string') {
                    //Metodos ("update", "setDate", "setValue", "remove"...): no-op.
                    //Si traen una fecha se asigna al input nativo.
                    if ((opcion === 'update' || opcion === 'setDate' || opcion === 'setValue') && arguments.length > 1) {
                        var fecha = normalizarFecha(arguments[1]);
                        partes.dentro.each(function () {
                            var $input = $(this).is('input') ? $(this) : $(this).find('input').first();
                            $input.val(fecha);
                        });
                    }
                } else {
                    //Primera llamada con opciones: se convierte a input date
                    partes.dentro.each(function () {
                        convertirAFechaNativa(this);
                    });
                }
            }
            return this;
        };

        copiarPropiedades(datepickerOriginal, datepickerEnvoltorio);
        $.fn.datepicker = datepickerEnvoltorio;
    }

    //Cuando el view_script asigna el valor con .val("2024-01-30 00:00:00")
    //el input date lo descartaria (queda vacio): se normaliza a YYYY-MM-DD
    //antes de asignarlo. Solo para input date dentro de .prf.
    if ($.valHooks && !$.valHooks.date) {
        $.valHooks.date = {
            set: function (elem, valor) {
                if ($(elem).closest(SELECTOR_CONTENEDOR).length === 0) {
                    //undefined = jQuery usa la asignacion normal
                    return undefined;
                }
                var normalizada = normalizarFecha(valor);
                elem.value = (normalizada !== '' || valor === '' || valor === null) ? normalizada : String(valor);
                return true;
            }
        };
    }

    /* ----------------------------------------------------------------------
     * 3. Combos: select2 -> <select> nativo dentro de .prf
     * -------------------------------------------------------------------- */

    /**
     * Deja un combo como nativo: misma accion que pv2MobileSelects
     * (quita la clase select2 y agrega form-control).
     */
    function dejarComboNativo(elemento) {
        var $select = $(elemento);
        try {
            if ($select.data('select2') && selectOriginal) {
                selectOriginal.call($select, 'destroy');
            }
        } catch (e) {
            if (window.console) {
                console.info('posme-report-filter: no se pudo destruir select2', e);
            }
        }
        $select.removeClass('select2 select2-offscreen').addClass('form-control').css('display', '');
    }

    var selectOriginal = (typeof $.fn.select2 === 'function') ? $.fn.select2 : null;

    if (selectOriginal) {
        var selectEnvoltorio = function (opcion) {
            var partes = separarPorContenedor(this);

            //Ningun elemento dentro de .prf: comportamiento original intacto
            if (partes.dentro.length === 0) {
                return selectOriginal.apply(this, arguments);
            }

            //Mezcla: los de fuera de .prf siguen con select2
            if (partes.fuera.length > 0) {
                selectOriginal.apply(partes.fuera, arguments);
            }

            //Inicializacion (sin argumentos u objeto de opciones): queda nativo
            if (opcion === undefined || typeof opcion === 'object') {
                partes.dentro.each(function () {
                    dejarComboNativo(this);
                });
                return this;
            }

            //destroy: se delega al original solo si select2 llego a inicializarse
            //(lo usa pv2MobileSelects)
            if (opcion === 'destroy') {
                partes.dentro.each(function () {
                    if ($(this).data('select2')) {
                        selectOriginal.call($(this), 'destroy');
                    }
                });
                return this;
            }

            //Metodos usados por los view_script, resueltos sobre el combo nativo
            if (opcion === 'val') {
                if (arguments.length > 1) {
                    partes.dentro.val(arguments[1]);
                    if (arguments[2] === true) {
                        partes.dentro.trigger('change');
                    }
                    return this;
                }
                return partes.dentro.first().val();
            }
            if (opcion === 'data') {
                if (arguments.length > 1) {
                    var dato = arguments[1] || {};
                    partes.dentro.val(dato.id);
                    return this;
                }
                var $opcion = partes.dentro.first().find('option:selected');
                return $opcion.length ? { id: $opcion.val(), text: $opcion.text() } : null;
            }
            if (opcion === 'enable') {
                partes.dentro.prop('disabled', arguments.length > 1 ? !arguments[1] : false);
                return this;
            }
            if (opcion === 'disable') {
                partes.dentro.prop('disabled', true);
                return this;
            }
            if (opcion === 'readonly') {
                partes.dentro.prop('disabled', arguments.length > 1 ? !!arguments[1] : true);
                return this;
            }
            //"open", "close", "focus", "container", etc.: no-op
            return this;
        };

        copiarPropiedades(selectOriginal, selectEnvoltorio);
        $.fn.select2 = selectEnvoltorio;
    }

    /**
     * Red de seguridad: si algun select2 dentro de .prf se inicializo antes de
     * este archivo (o por otra via), se convierte a nativo tras cargar la pagina.
     */
    function convertirCombosRestantes() {
        var $combos = $(SELECTOR_CONTENEDOR + ' select.select2, ' + SELECTOR_CONTENEDOR + ' select.select2-offscreen');
        if ($combos.length === 0) {
            return;
        }
        if (typeof window.pv2MobileSelects === 'function') {
            try {
                window.pv2MobileSelects($combos);
            } catch (e) {
                if (window.console) {
                    console.info('posme-report-filter: pv2MobileSelects', e);
                }
            }
        }
        //Tableta tactil (pv2IsMobile devuelve false): se convierte aqui
        $(SELECTOR_CONTENEDOR + ' select.select2, ' + SELECTOR_CONTENEDOR + ' select.select2-offscreen').each(function () {
            dejarComboNativo(this);
        });
    }

    $(window).on('load', function () {
        setTimeout(convertirCombosRestantes, 0);
    });
    $(function () {
        //Despues del document.ready de genyx-app-init.js
        setTimeout(convertirCombosRestantes, 0);
    });

})(window, window.jQuery);
