/**
 * Constructor de reportes: funciones puras de la pantalla app_builder_report.
 *
 * No toca el DOM, no hace peticiones y no depende de Vue ni de jQuery, para poder
 * probarse con Jest. La validación de este archivo solo da mensajes rápidos al usuario:
 * la validación que manda es la del servidor (core_web_report_builder).
 *
 * Reglas de presentación:
 * - Número: 1,000.00; conteos sin decimales; null queda vacío (nunca 0.00).
 * - Fecha: se muestra tal como llega, sin new Date, para evitar el corrimiento de zona horaria.
 * - Al servidor solo viajan números puros (sin comas).
 */
(function (root) {
    'use strict';

    var TIPOS = ['text', 'number', 'date'];

    var OPERADORES = {
        text: [
            { value: 'eq', label: 'Igual' },
            { value: 'contains', label: 'Contiene' },
            { value: 'starts', label: 'Empieza con' }
        ],
        number: [
            { value: 'eq', label: '=' },
            { value: 'gt', label: '>' },
            { value: 'lt', label: '<' },
            { value: 'between', label: 'Entre' }
        ],
        date: [
            { value: 'eq', label: 'Igual' },
            { value: 'between', label: 'Entre' },
            { value: 'from', label: 'Desde' },
            { value: 'to', label: 'Hasta' }
        ]
    };

    var FUNCIONES = [
        { value: 'SUM', label: 'Suma' },
        { value: 'COUNT', label: 'Conteo' },
        { value: 'AVG', label: 'Promedio' },
        { value: 'MIN', label: 'Mínimo' },
        { value: 'MAX', label: 'Máximo' }
    ];

    /* Valor del combo "Agrupar / calcular" que indica agrupar sin función de agregación */
    var MODO_AGRUPAR = 'GROUP';

    /* Mismos topes que valida el servidor */
    var LIMITES = { columnas: 40, orden: 10, filtros: 30, agrupar: 20, titulo: 150, nombre: 150 };

    /*
     * Fechas dinámicas de un filtro de fecha. Viajan como {"dynamic":"<codigo>"} y las resuelve el
     * servidor al ejecutar (core_web_report_builder::resolveDynamicDate); el navegador nunca calcula la fecha.
     */
    var FECHAS_DESDE = [
        { value: 'inicio_dia', label: 'Inicio del día' },
        { value: 'inicio_mes', label: 'Inicio del mes' },
        { value: 'inicio_anio', label: 'Inicio del año' }
    ];
    var FECHAS_HASTA = [
        { value: 'fin_dia', label: 'Fin del día' },
        { value: 'hoy', label: 'Día de hoy' },
        { value: 'fin_mes', label: 'Fin del mes' },
        { value: 'fin_anio', label: 'Fin del año' }
    ];

    /* Formatos del indicador de un dashboard */
    var FORMATOS_INDICADOR = [
        { value: 'number', label: 'Número' },
        { value: 'money', label: 'Moneda' }
    ];

    var contadorUid = 0;

    function nuevoUid() {
        contadorUid += 1;
        return 'prb' + contadorUid;
    }

    function esVacio(valor) {
        return valor === null || valor === undefined || valor === '';
    }

    function textoDe(valor) {
        return valor === null || valor === undefined ? '' : String(valor);
    }

    function escaparHtml(valor) {
        return textoDe(valor)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    /* Texto en minúsculas y sin tildes, para el buscador de vistas */
    function normalizarBusqueda(valor) {
        var texto = textoDe(valor);
        if (texto.normalize) {
            texto = texto.normalize('NFD').replace(/[̀-ͯ]/g, '');
        }
        return texto.toLowerCase().trim();
    }

    function filtrarVistas(vistas, busqueda) {
        var lista = vistas || [];
        var texto = normalizarBusqueda(busqueda);
        if (texto === '') {
            return lista.slice();
        }
        return lista.filter(function (vista) {
            return normalizarBusqueda(vista.label + ' ' + textoDe(vista.description)).indexOf(texto) !== -1;
        });
    }

    /* ---------------- Números ---------------- */

    function agruparMiles(enteroTexto) {
        return enteroTexto.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    /* Quita comas y espacios: "15,500.25" => "15500.25" */
    function limpiarNumero(valor) {
        return textoDe(valor).replace(/[,\s]/g, '');
    }

    /* Número puro sin comas ni notación científica, y finito */
    function esNumeroValido(valor) {
        var texto = textoDe(valor);
        if (!/^[+-]?(\d+(\.\d*)?|\.\d+)$/.test(texto)) {
            return false;
        }
        return isFinite(Number(texto));
    }

    /* Suma 1 a una cadena de dígitos sin pasar por punto flotante */
    function incrementarDigitos(digitos) {
        var lista = digitos.split('');
        var i = lista.length - 1;
        while (i >= 0) {
            if (lista[i] !== '9') {
                lista[i] = String(Number(lista[i]) + 1);
                return lista.join('');
            }
            lista[i] = '0';
            i -= 1;
        }
        return '1' + lista.join('');
    }

    /**
     * Pasa notación científica a decimal corriendo el punto sobre el texto, sin punto flotante:
     * "1e-7" => "0.0000001", "1.5e+3" => "1500". String(numero) la produce en JS, y MySQL la
     * entrega como cadena en columnas DOUBLE.
     *
     * @param {string} texto Texto ya recortado.
     * @return {string} Decimal equivalente; el mismo texto si no es notación científica o si el
     *                  exponente excede el rango de un DOUBLE, para no armar cadenas enormes.
     */
    function expandirCientifica(texto) {
        var coincide = /^([+-]?)(\d*)(?:\.(\d*))?[eE]([+-]?\d+)$/.exec(texto);
        if (!coincide || (coincide[2] === '' && !coincide[3])) {
            return texto;
        }
        var exponente = Number(coincide[4]);
        if (Math.abs(exponente) > 400) {
            return texto;
        }
        var entero = coincide[2];
        var digitos = entero + (coincide[3] || '');
        var punto = entero.length + exponente;
        if (punto <= 0) {
            return coincide[1] + '0.' + new Array(1 - punto).join('0') + digitos;
        }
        if (punto >= digitos.length) {
            return coincide[1] + digitos + new Array(punto - digitos.length + 1).join('0');
        }
        return coincide[1] + digitos.substring(0, punto) + '.' + digitos.substring(punto);
    }

    /**
     * Da formato de pantalla a un número. El redondeo se hace sobre el texto porque los DECIMAL
     * de MySQL llegan como cadena y pasarlos por punto flotante puede alterarlos (1.005 => 1.00).
     *
     * @param {*} valor Número o texto numérico.
     * @param {number} [decimales=2] Cantidad de decimales.
     * @return {string} Texto con miles; vacío si es null; el texto original si no es numérico.
     */
    function formatearNumero(valor, decimales) {
        if (esVacio(valor)) {
            return '';
        }
        var cantidad = decimales === undefined ? 2 : decimales;
        var coincide = /^([+-]?)(\d*)(?:\.(\d*))?$/.exec(expandirCientifica(textoDe(valor).trim()));
        if (!coincide || (coincide[2] === '' && !coincide[3])) {
            return textoDe(valor);
        }

        var entero = coincide[2].replace(/^0+(?=\d)/, '') || '0';
        var fraccion = coincide[3] || '';
        var conservar = (fraccion + new Array(cantidad + 1).join('0')).substring(0, cantidad);
        var digitos = entero + conservar;

        if (fraccion.length > cantidad && Number(fraccion.charAt(cantidad)) >= 5) {
            digitos = incrementarDigitos(digitos);
        }

        var parteEntera = digitos.substring(0, digitos.length - cantidad);
        var parteDecimal = digitos.substring(digitos.length - cantidad);
        var signo = coincide[1] === '-' && /[1-9]/.test(digitos) ? '-' : '';
        return signo + agruparMiles(parteEntera) + (cantidad > 0 ? '.' + parteDecimal : '');
    }

    /* Agrupa miles al salir del campo de filtro sin rellenar decimales ni redondear */
    function formatearEntradaNumero(valor) {
        var texto = limpiarNumero(valor);
        if (!esNumeroValido(texto)) {
            return textoDe(valor);
        }
        var partes = texto.replace(/^\+/, '').split('.');
        var negativo = partes[0].charAt(0) === '-';
        var entero = negativo ? partes[0].substring(1) : partes[0];
        return (negativo ? '-' : '') + agruparMiles(entero === '' ? '0' : entero) +
            (partes.length > 1 ? '.' + partes[1] : '');
    }

    /* ---------------- Fechas ---------------- */

    /* Y-m-d real; se compara en UTC para no depender de la zona horaria del navegador */
    function esFechaValida(valor) {
        var coincide = /^(\d{4})-(\d{2})-(\d{2})$/.exec(textoDe(valor));
        if (!coincide) {
            return false;
        }
        var anio = Number(coincide[1]);
        var mes = Number(coincide[2]);
        var dia = Number(coincide[3]);
        var fecha = new Date(Date.UTC(anio, mes - 1, dia));
        return anio > 0 && fecha.getUTCFullYear() === anio && fecha.getUTCMonth() === mes - 1 && fecha.getUTCDate() === dia;
    }

    function codigoFechaValido(codigo) {
        return FECHAS_DESDE.concat(FECHAS_HASTA).some(function (opcion) {
            return opcion.value === codigo;
        });
    }

    /* true si el valor es {dynamic: codigo} con un código conocido */
    function esFechaDinamica(valor) {
        return !!valor && typeof valor === 'object' && typeof valor.dynamic === 'string' && codigoFechaValido(valor.dynamic);
    }

    /**
     * Fechas dinámicas que se ofrecen según operador y extremo del filtro:
     * Desde => inicios; Hasta => finales; Entre => inicios en "value" y finales en "value2"; Igual => todas.
     *
     * @param {string} operador Operador del filtro.
     * @param {string} extremo 'value' o 'value2'.
     * @return {Array} Opciones {value, label}.
     */
    function opcionesFechaDinamica(operador, extremo) {
        if (operador === 'from') {
            return FECHAS_DESDE;
        }
        if (operador === 'to') {
            return FECHAS_HASTA;
        }
        if (operador === 'between') {
            return extremo === 'value2' ? FECHAS_HASTA : FECHAS_DESDE;
        }
        return FECHAS_DESDE.concat(FECHAS_HASTA);
    }

    /* Tal como llega; la fecha cero de MySQL queda vacía, igual que en el Excel */
    function formatearFecha(valor) {
        var texto = textoDe(valor);
        return /^0000-00-00/.test(texto) ? '' : texto;
    }

    /* ---------------- Celdas y paginación ---------------- */

    function formatearCelda(valor, columna) {
        if (valor === null || valor === undefined) {
            return '';
        }
        var col = columna || {};
        if (col.fn === 'COUNT') {
            return formatearNumero(valor, 0);
        }
        if (col.fn || col.type === 'number') {
            return formatearNumero(valor, 2);
        }
        if (col.type === 'date') {
            return formatearFecha(valor);
        }
        return textoDe(valor);
    }

    function formatearEntero(valor) {
        return formatearNumero(valor, 0);
    }

    /* "1-25 de 1,240", o "1-25 de 1,240+" si el total llegó al tope de conteo */
    function rangoPaginacion(page, pageSize, cantidadFilas, total, totalIsCapped) {
        if (!cantidadFilas) {
            return '0 filas';
        }
        var desde = (page - 1) * pageSize + 1;
        var hasta = desde + cantidadFilas - 1;
        var rango = formatearEntero(desde) + '-' + formatearEntero(hasta);
        if (total === null || total === undefined) {
            return rango;
        }
        return rango + ' de ' + formatearEntero(total) + (totalIsCapped ? '+' : '');
    }

    /* El servidor rechaza offset + pageSize mayor que el máximo de filas en pantalla */
    function puedeAvanzar(page, pageSize, hasMore, maxRows) {
        if (!hasMore) {
            return false;
        }
        return !maxRows || (page * pageSize + pageSize) <= maxRows;
    }

    /* Tamaños de página ofrecidos, sin pasar el máximo que informe el servidor */
    function tamaniosPagina(maximo) {
        var tope = Number(maximo);
        var opciones = [25, 50, 100];
        if (!tope || tope <= 0) {
            return opciones;
        }
        var validas = opciones.filter(function (opcion) {
            return opcion <= tope;
        });
        return validas.length > 0 ? validas : [tope];
    }

    /* ---------------- Catálogo ---------------- */

    function buscarCampo(catalogo, nombre) {
        var lista = catalogo || [];
        for (var i = 0; i < lista.length; i += 1) {
            if (lista[i].name === nombre) {
                return lista[i];
            }
        }
        return null;
    }

    function etiquetaCampo(catalogo, nombre) {
        var campo = buscarCampo(catalogo, nombre);
        return campo ? campo.label : nombre;
    }

    function etiquetaFuncion(fn) {
        for (var i = 0; i < FUNCIONES.length; i += 1) {
            if (FUNCIONES[i].value === fn) {
                return FUNCIONES[i].label;
            }
        }
        return textoDe(fn);
    }

    function tipoValido(tipo) {
        return TIPOS.indexOf(tipo) !== -1 ? tipo : 'text';
    }

    /**
     * Operadores de un tipo de catálogo. Si getColumns informó cuáles permite, se respetan;
     * las etiquetas siempre son las de esta pantalla.
     */
    function operadoresDeTipo(tipo, permitidos) {
        var propios = OPERADORES[tipoValido(tipo)];
        var lista = permitidos && permitidos[tipoValido(tipo)];
        if (!Array.isArray(lista) || lista.length === 0) {
            return propios;
        }
        var filtrados = propios.filter(function (operador) {
            return lista.indexOf(operador.value) !== -1;
        });
        return filtrados.length > 0 ? filtrados : propios;
    }

    /* COUNT para cualquier tipo; SUM, AVG, MIN y MAX solo para números de catálogo */
    function funcionesDeTipo(tipo) {
        if (tipo === 'number') {
            return FUNCIONES;
        }
        return FUNCIONES.filter(function (fn) {
            return fn.value === 'COUNT';
        });
    }

    function opcionesModo(tipoCatalogo) {
        return [
            { value: '', label: 'Sin agrupar' },
            { value: MODO_AGRUPAR, label: 'Agrupar por este campo' }
        ].concat(funcionesDeTipo(tipoCatalogo));
    }

    function esFuncion(modo) {
        return !esVacio(modo) && modo !== MODO_AGRUPAR;
    }

    /* ---------------- Estado de la pantalla <-> config ---------------- */

    function nuevaColumna(campo) {
        return {
            uid: nuevoUid(),
            name: campo.name,
            label: campo.label,
            type: tipoValido(campo.type),
            align: campo.align === 'right' ? 'right' : 'left',
            modo: ''
        };
    }

    /*
     * Con función el resultado siempre se presenta como número a la derecha. Al quitar la función
     * se vuelve a lo del catálogo; entre "Sin agrupar" y "Agrupar" se respeta lo que eligió el usuario.
     */
    function aplicarModoColumna(columna, modo, catalogo) {
        var campo = buscarCampo(catalogo, columna.name);
        var veniaDeFuncion = esFuncion(columna.modo);
        columna.modo = modo;
        if (esFuncion(modo)) {
            columna.type = 'number';
            columna.align = 'right';
        } else if (campo && veniaDeFuncion) {
            columna.type = tipoValido(campo.type);
            columna.align = campo.align === 'right' ? 'right' : 'left';
        }
        return columna;
    }

    function nuevoFiltro(campo, permitidos) {
        return {
            uid: nuevoUid(),
            column: campo.name,
            operator: operadoresDeTipo(campo.type, permitidos)[0].value,
            value: '',
            value2: '',
            /* Código de fecha dinámica por extremo; '' = fecha manual */
            dinamico: '',
            dinamico2: ''
        };
    }

    /**
     * Valor de un extremo del filtro tal como viaja al servidor: {dynamic} si es un campo de fecha con
     * fecha dinámica elegida; si no, el texto (los números sin comas).
     *
     * @param {Object} filtro Filtro del estado de la pantalla.
     * @param {Object|null} campo Campo del catálogo.
     * @param {string} extremo 'value' o 'value2'.
     * @return {string|Object} Valor a enviar.
     */
    function valorExtremoFiltro(filtro, campo, extremo) {
        var codigo = textoDe(filtro[extremo === 'value2' ? 'dinamico2' : 'dinamico']);
        if (!!campo && campo.type === 'date' && codigo !== '' && codigoFechaValido(codigo)) {
            return { dynamic: codigo };
        }
        if (!!campo && campo.type === 'number') {
            return limpiarNumero(filtro[extremo]);
        }
        return textoDe(filtro[extremo]);
    }

    function claveOrden(nombre, fn) {
        return nombre + '|' + textoDe(fn);
    }

    function separarClaveOrden(clave) {
        var texto = textoDe(clave);
        var posicion = texto.lastIndexOf('|');
        if (posicion === -1) {
            return { column: texto, fn: '' };
        }
        return { column: texto.substring(0, posicion), fn: texto.substring(posicion + 1) };
    }

    function hayAgrupacion(columnas) {
        return (columnas || []).some(function (columna) {
            return !esVacio(columna.modo);
        });
    }

    /* Campos por los que se puede ordenar: con agrupación solo lo seleccionado; sin ella, todo el catálogo */
    function opcionesOrden(columnas, catalogo) {
        var lista = [];
        var vistos = {};
        if (!hayAgrupacion(columnas)) {
            (catalogo || []).forEach(function (campo) {
                lista.push({ clave: claveOrden(campo.name, ''), label: campo.label });
            });
            return lista;
        }
        (columnas || []).forEach(function (columna) {
            var fn = esFuncion(columna.modo) ? columna.modo : '';
            var clave = claveOrden(columna.name, fn);
            if (esVacio(columna.modo) || vistos[clave]) {
                return;
            }
            vistos[clave] = true;
            lista.push({
                clave: clave,
                label: etiquetaCampo(catalogo, columna.name) + (fn ? ' (' + etiquetaFuncion(fn) + ')' : '')
            });
        });
        return lista;
    }

    /* Quita del orden lo que apunta a una función que ya ninguna columna usa */
    function limpiarOrden(orden, columnas) {
        return (orden || []).filter(function (item) {
            var partes = separarClaveOrden(item.clave);
            if (partes.fn === '') {
                return true;
            }
            return (columnas || []).some(function (columna) {
                return columna.name === partes.column && columna.modo === partes.fn;
            });
        });
    }

    /* Filtros para Generar y Exportar: los vacíos no se envían (0 sí es un valor); números sin comas */
    function construirFiltros(filtros, catalogo) {
        var resultado = [];
        (filtros || []).forEach(function (filtro) {
            var campo = buscarCampo(catalogo, filtro.column);
            var valor = valorExtremoFiltro(filtro, campo, 'value');
            var valor2 = valorExtremoFiltro(filtro, campo, 'value2');

            if (filtro.operator === 'between') {
                if (valor !== '' || valor2 !== '') {
                    resultado.push({ column: filtro.column, operator: filtro.operator, value: valor, value2: valor2 });
                }
                return;
            }
            if (valor !== '') {
                resultado.push({ column: filtro.column, operator: filtro.operator, value: valor });
            }
        });
        return resultado;
    }

    /**
     * Filtros para guardar una consulta recurrente: van todos, porque el valor se indica al usarla.
     * Lo vacío viaja como null (0 sí es un valor) y value2 solo aplica al operador "Entre".
     *
     * @param {Array} filtros Filtros del estado de la pantalla.
     * @param {Array} catalogo Campos de la vista.
     * @return {Array} {column, operator, value, value2} con números sin comas.
     */
    function construirFiltrosParaGuardar(filtros, catalogo) {
        return (filtros || []).map(function (filtro) {
            var campo = buscarCampo(catalogo, filtro.column);
            var valor = valorExtremoFiltro(filtro, campo, 'value');
            var valor2 = valorExtremoFiltro(filtro, campo, 'value2');
            return {
                column: filtro.column,
                operator: filtro.operator,
                value: valor === '' ? null : valor,
                value2: (filtro.operator !== 'between' || valor2 === '') ? null : valor2
            };
        });
    }

    /**
     * Arma la config del contrato del servidor a partir del estado de la pantalla.
     *
     * @param {Object} estado {dataViewID, columnas, orden, filtros}.
     * @param {Array} catalogo Campos de la vista.
     * @return {Object} Config lista para enviarse.
     */
    function construirConfig(estado, catalogo) {
        var agrupar = [];
        var columns = (estado.columnas || []).map(function (columna) {
            var campo = buscarCampo(catalogo, columna.name);
            var titulo = textoDe(columna.label).trim();
            var item = {
                name: columna.name,
                label: (titulo !== '' ? titulo : (campo ? campo.label : columna.name)).substring(0, LIMITES.titulo),
                type: tipoValido(columna.type),
                align: columna.align === 'right' ? 'right' : 'left'
            };
            if (esFuncion(columna.modo)) {
                item.fn = columna.modo;
            } else if (columna.modo === MODO_AGRUPAR && agrupar.indexOf(columna.name) === -1) {
                agrupar.push(columna.name);
            }
            return item;
        });

        var orderBy = (estado.orden || []).map(function (item) {
            var partes = separarClaveOrden(item.clave);
            var salida = { column: partes.column, dir: item.dir === 'DESC' ? 'DESC' : 'ASC' };
            if (partes.fn !== '') {
                salida.fn = partes.fn;
            }
            return salida;
        });

        return {
            version: 1,
            dataViewID: Number(estado.dataViewID) || null,
            columns: columns,
            groupBy: agrupar,
            orderBy: orderBy,
            filters: construirFiltros(estado.filtros, catalogo)
        };
    }

    /* Igual que construirConfig, pero conserva los filtros sin valor */
    function construirConfigParaGuardar(estado, catalogo) {
        var config = construirConfig(estado, catalogo);
        config.filters = construirFiltrosParaGuardar(estado.filtros, catalogo);
        return config;
    }

    /**
     * Convierte una config guardada al estado de la pantalla. Lo que ya no esté en el catálogo
     * se quita y se informa en avisos, sin fallar.
     *
     * @return {Object} {columnas, orden, filtros, avisos}.
     */
    function desdeConfig(config, catalogo, permitidos) {
        var origen = config || {};
        var agrupar = Array.isArray(origen.groupBy) ? origen.groupBy : [];
        var avisos = [];
        var columnas = [];
        var orden = [];
        var filtros = [];

        (Array.isArray(origen.columns) ? origen.columns : []).forEach(function (columna) {
            var campo = buscarCampo(catalogo, columna.name);
            if (!campo) {
                avisos.push('Se quitó la columna "' + textoDe(columna.label || columna.name) + '" porque ya no está disponible.');
                return;
            }
            var item = nuevaColumna(campo);
            item.label = esVacio(columna.label) ? campo.label : textoDe(columna.label);
            item.type = tipoValido(columna.type || campo.type);
            item.align = columna.align === 'right' ? 'right' : 'left';
            if (!esVacio(columna.fn)) {
                item.modo = textoDe(columna.fn);
            } else if (agrupar.indexOf(columna.name) !== -1) {
                item.modo = MODO_AGRUPAR;
            }
            columnas.push(item);
        });

        agrupar.forEach(function (nombre) {
            var mostrada = columnas.some(function (columna) {
                return columna.name === nombre;
            });
            if (!mostrada) {
                avisos.push('Se quitó el agrupamiento por "' + etiquetaCampo(catalogo, nombre) + '" porque no está entre las columnas.');
            }
        });

        (Array.isArray(origen.orderBy) ? origen.orderBy : []).forEach(function (item) {
            if (!buscarCampo(catalogo, item.column)) {
                avisos.push('Se quitó el orden por "' + textoDe(item.column) + '" porque ya no está disponible.');
                return;
            }
            orden.push({
                uid: nuevoUid(),
                clave: claveOrden(item.column, item.fn),
                dir: textoDe(item.dir).toUpperCase() === 'DESC' ? 'DESC' : 'ASC'
            });
        });

        (Array.isArray(origen.filters) ? origen.filters : []).forEach(function (filtro) {
            var campo = buscarCampo(catalogo, filtro.column);
            if (!campo) {
                avisos.push('Se quitó el filtro por "' + textoDe(filtro.column) + '" porque ya no está disponible.');
                return;
            }
            var esNumero = campo.type === 'number';
            var esFecha = campo.type === 'date';
            var item = nuevoFiltro(campo, permitidos);
            item.operator = textoDe(filtro.operator) || item.operator;
            /* Una fecha dinámica guardada se preselecciona en su combo y deja vacío el campo manual */
            item.dinamico = esFecha && esFechaDinamica(filtro.value) ? filtro.value.dynamic : '';
            item.dinamico2 = esFecha && esFechaDinamica(filtro.value2) ? filtro.value2.dynamic : '';
            item.value = (filtro.value && typeof filtro.value === 'object') ? ''
                : (esNumero ? formatearEntradaNumero(filtro.value) : textoDe(filtro.value));
            item.value2 = (filtro.value2 && typeof filtro.value2 === 'object') ? ''
                : (esNumero ? formatearEntradaNumero(filtro.value2) : textoDe(filtro.value2));
            filtros.push(item);
        });

        return { columnas: columnas, orden: orden, filtros: filtros, avisos: avisos };
    }

    /* loadQuery debe traer un objeto; se tolera JSON en texto. Devuelve null si no se entiende */
    function normalizarConfigCargada(config) {
        if (config && typeof config === 'object') {
            return config;
        }
        if (typeof config === 'string') {
            try {
                var interpretada = JSON.parse(config);
                return interpretada && typeof interpretada === 'object' ? interpretada : null;
            } catch (ex) {
                return null;
            }
        }
        return null;
    }

    /* ---------------- Validación previa al envío ---------------- */

    /* Con permitirVacios (guardar) solo se revisa lo digitado; el valor que falte se indica al usar la consulta */
    function validarFiltro(filtro, campo, errores, permitirVacios) {
        var etiqueta = campo.label;
        var tipo = tipoValido(campo.type);
        var permitido = OPERADORES[tipo].some(function (operador) {
            return operador.value === filtro.operator;
        });
        if (!permitido) {
            errores.push('El operador del filtro "' + etiqueta + '" no es válido.');
            return;
        }

        var valores = filtro.operator === 'between' ? [filtro.value, filtro.value2] : [filtro.value];
        var faltaExtremo = filtro.operator === 'between' && (esVacio(filtro.value) || esVacio(filtro.value2));
        if (faltaExtremo && !permitirVacios) {
            errores.push('El filtro "' + etiqueta + '" necesita los dos valores de "Entre".');
            return;
        }
        var hayDinamica = false;
        for (var i = 0; i < valores.length; i += 1) {
            if (permitirVacios && esVacio(valores[i])) {
                continue;
            }
            /* La fecha dinámica la resuelve el servidor: aquí basta con que el código sea conocido */
            if (tipo === 'date' && esFechaDinamica(valores[i])) {
                hayDinamica = true;
                continue;
            }
            if (tipo === 'number' && !esNumeroValido(valores[i])) {
                errores.push('El valor del filtro "' + etiqueta + '" no es un número válido.');
                return;
            }
            if (tipo === 'date' && !esFechaValida(valores[i])) {
                errores.push('El valor del filtro "' + etiqueta + '" no es una fecha válida.');
                return;
            }
        }
        /* Con una fecha dinámica el orden de los extremos depende del día de ejecución: lo revisa el servidor */
        if (filtro.operator === 'between' && !faltaExtremo && !hayDinamica) {
            var invertido = tipo === 'number'
                ? Number(filtro.value) > Number(filtro.value2)
                : filtro.value > filtro.value2;
            if (invertido) {
                errores.push('En el filtro "' + etiqueta + '" el primer valor no puede ser mayor que el segundo.');
            }
        }
    }

    /**
     * Revisa la config antes de enviarla, con las mismas reglas del servidor.
     *
     * @param {Object} config Config de construirConfig o construirConfigParaGuardar.
     * @param {Array} catalogo Campos de la vista.
     * @param {Object} [opciones] {permitirVacios: true} al guardar, para aceptar filtros sin valor.
     * @return {Array} Mensajes para el usuario; vacío si no hay errores.
     */
    function validarConfig(config, catalogo, opciones) {
        var permitirVacios = !!opciones && opciones.permitirVacios === true;
        var errores = [];
        var columnas = config.columns || [];
        var agrupar = config.groupBy || [];
        var orden = config.orderBy || [];
        var filtros = config.filters || [];
        var vistos = {};

        if (!config.dataViewID) {
            errores.push('Seleccione una vista.');
        }
        if (columnas.length === 0) {
            errores.push('Agregue al menos una columna en "Mostrar".');
        }
        if (columnas.length > LIMITES.columnas) {
            errores.push('Puede mostrar hasta ' + LIMITES.columnas + ' columnas.');
        }
        if (orden.length > LIMITES.orden) {
            errores.push('Puede ordenar hasta por ' + LIMITES.orden + ' campos.');
        }
        if (filtros.length > LIMITES.filtros) {
            errores.push('Puede usar hasta ' + LIMITES.filtros + ' filtros.');
        }
        if (agrupar.length > LIMITES.agrupar) {
            errores.push('Puede agrupar hasta por ' + LIMITES.agrupar + ' campos.');
        }

        var hayFuncion = false;
        columnas.forEach(function (columna) {
            var campo = buscarCampo(catalogo, columna.name);
            var clave = claveOrden(columna.name, columna.fn);
            if (!campo) {
                errores.push('La columna "' + columna.label + '" ya no está disponible.');
                return;
            }
            if (vistos[clave]) {
                errores.push('La columna "' + campo.label + '" está repetida.');
            }
            vistos[clave] = true;
            if (columna.fn) {
                hayFuncion = true;
                var permitida = funcionesDeTipo(campo.type).some(function (fn) {
                    return fn.value === columna.fn;
                });
                if (!permitida) {
                    errores.push('"' + campo.label + '" no admite el cálculo ' + etiquetaFuncion(columna.fn) + '.');
                }
            }
        });

        var agrupado = hayFuncion || agrupar.length > 0;
        if (agrupado) {
            columnas.forEach(function (columna) {
                if (!columna.fn && agrupar.indexOf(columna.name) === -1) {
                    errores.push('Al agrupar, la columna "' + columna.label + '" debe agruparse o llevar un cálculo.');
                }
            });
        }

        orden.forEach(function (item) {
            var etiqueta = etiquetaCampo(catalogo, item.column);
            if (!buscarCampo(catalogo, item.column)) {
                errores.push('El orden por "' + etiqueta + '" ya no está disponible.');
                return;
            }
            if (!agrupado) {
                if (item.fn) {
                    errores.push('El orden por "' + etiqueta + '" usa un cálculo que no está en las columnas.');
                }
                return;
            }
            var seleccionada = columnas.some(function (columna) {
                return columna.name === item.column && textoDe(columna.fn) === textoDe(item.fn);
            });
            var enGrupo = !item.fn && agrupar.indexOf(item.column) !== -1;
            if (!seleccionada && !enGrupo) {
                errores.push('El orden por "' + etiqueta + '" debe coincidir con una columna agrupada o calculada.');
            }
        });

        filtros.forEach(function (filtro) {
            var campo = buscarCampo(catalogo, filtro.column);
            if (!campo) {
                errores.push('El filtro por "' + textoDe(filtro.column) + '" ya no está disponible.');
                return;
            }
            validarFiltro(filtro, campo, errores, permitirVacios);
        });

        return errores;
    }

    /* Aviso no bloqueante: sumar importes de varias monedas da un total sin sentido */
    function debeAvisarMoneda(config, catalogo) {
        var campoMoneda = null;
        (catalogo || []).forEach(function (campo) {
            if (textoDe(campo.name).toLowerCase() === 'moneda') {
                campoMoneda = campo;
            }
        });
        if (!campoMoneda) {
            return false;
        }
        var haySuma = (config.columns || []).some(function (columna) {
            return columna.fn === 'SUM';
        });
        return haySuma && (config.groupBy || []).indexOf(campoMoneda.name) === -1;
    }

    /* Resumen de solo lectura para el modo consulta */
    function resumirConfig(config, catalogo) {
        return {
            columnas: (config.columns || []).map(function (columna) {
                return columna.label + (columna.fn ? ' (' + etiquetaFuncion(columna.fn) + ')' : '');
            }),
            agrupar: (config.groupBy || []).map(function (nombre) {
                return etiquetaCampo(catalogo, nombre);
            }),
            orden: (config.orderBy || []).map(function (item) {
                return etiquetaCampo(catalogo, item.column) +
                    (item.fn ? ' (' + etiquetaFuncion(item.fn) + ')' : '') +
                    (item.dir === 'DESC' ? ' descendente' : ' ascendente');
            })
        };
    }

    /* ---------------- Combos excluyentes (Mostrar y Ordenar) ---------------- */

    /*
     * Campos que aún se pueden agregar en Mostrar. Misma regla que "Todas": un campo ya mostrado sin
     * función no se repite; si solo se usa con función agregada (SUM, COUNT...) sigue disponible.
     */
    function camposDisponibles(catalogo, columnas) {
        return (catalogo || []).filter(function (campo) {
            return !(columnas || []).some(function (columna) {
                return columna.name === campo.name && !esFuncion(columna.modo);
            });
        });
    }

    /**
     * Opciones de un combo de Ordenar sin las claves que ya usan los otros órdenes.
     *
     * @param {Array} opciones Resultado de opcionesOrden.
     * @param {Array} orden Órdenes del estado {uid, clave, dir}.
     * @param {string|null} uidActual Orden dueño del combo (conserva su clave); null = solo las libres.
     * @return {Array} Opciones disponibles.
     */
    function opcionesOrdenLibres(opciones, orden, uidActual) {
        var usadas = {};
        (orden || []).forEach(function (item) {
            if (item.uid !== uidActual) {
                usadas[item.clave] = true;
            }
        });
        return (opciones || []).filter(function (opcion) {
            return !usadas[opcion.clave];
        });
    }

    /* ---------------- Presentación (modal Guardar / dashboards) ---------------- */

    /* Estado vacío de la presentación. Los campos de columna se guardan por uid de columna, no por posición */
    function presentacionVacia() {
        return {
            isShared: 0,
            viewTypeCatalogItemID: 0,
            dashboardCatalogItemID: 0,
            orden: 0,
            cardTypeCatalogItemID: 0,
            widthCatalogItemID: 0,
            chartTypeCatalogItemID: 0,
            title: '',
            labelUid: '',
            valueUids: [],
            seriesUid: '',
            indicatorUid: '',
            indicatorFormat: 'number'
        };
    }

    function copiarPresentacion(origen) {
        var base = presentacionVacia();
        var datos = origen || {};
        Object.keys(base).forEach(function (clave) {
            if (datos[clave] !== undefined) {
                base[clave] = clave === 'valueUids' ? (Array.isArray(datos.valueUids) ? datos.valueUids.slice() : []) : datos[clave];
            }
        });
        return base;
    }

    /* Ítem de catálogo por id; null si no está */
    function buscarItemCatalogo(lista, id) {
        var numero = Number(id) || 0;
        var items = lista || [];
        for (var i = 0; i < items.length; i += 1) {
            if (Number(items[i].catalogItemID) === numero) {
                return items[i];
            }
        }
        return null;
    }

    /* Tipo de tarjeta por el name del catálogo, sin mayúsculas ni tildes: 'grafico' | 'tabla' | 'indicador' | '' */
    function tipoTarjeta(item) {
        var nombre = normalizarBusqueda(item ? item.name : '');
        return ['grafico', 'tabla', 'indicador'].indexOf(nombre) !== -1 ? nombre : '';
    }

    /* Columnas para los campos de la presentación: clave posicional "c" + índice en config.columns */
    function opcionesColumnasPresentacion(columnas, catalogo) {
        return (columnas || []).map(function (columna, indice) {
            var fn = esFuncion(columna.modo) ? columna.modo : '';
            var titulo = textoDe(columna.label).trim() || etiquetaCampo(catalogo, columna.name);
            return { uid: columna.uid, key: 'c' + indice, label: titulo + (fn ? ' (' + etiquetaFuncion(fn) + ')' : '') };
        });
    }

    /* uid => "c" + índice actual; '' si la columna ya no está */
    function claveDeColumna(columnas, uid) {
        if (esVacio(uid)) {
            return '';
        }
        var lista = columnas || [];
        for (var i = 0; i < lista.length; i += 1) {
            if (lista[i].uid === uid) {
                return 'c' + i;
            }
        }
        return '';
    }

    /* "c" + índice => uid de la columna en esa posición; '' si no existe */
    function uidDeClave(columnas, clave) {
        var coincide = /^c(\d+)$/.exec(textoDe(clave));
        var lista = columnas || [];
        if (!coincide || Number(coincide[1]) >= lista.length) {
            return '';
        }
        return lista[Number(coincide[1])].uid;
    }

    /**
     * Convierte la consulta cargada (loadQuery) a la presentación de la pantalla. Las claves guardadas
     * (c0, c1...) se pasan a uid de las columnas ya cargadas, para que sobrevivan a reordenar o quitar columnas.
     *
     * @param {Object} consulta query de loadQuery.
     * @param {Array} columnas Columnas del estado (desdeConfig).
     * @return {Object} Presentación.
     */
    function presentacionDesdeConsulta(consulta, columnas) {
        var datos = consulta || {};
        var display = datos.display && typeof datos.display === 'object' ? datos.display : {};
        var presentacion = presentacionVacia();
        presentacion.isShared = datos.isShared === true || Number(datos.isShared) === 1 ? 1 : 0;
        presentacion.viewTypeCatalogItemID = Number(datos.viewTypeCatalogItemID) || 0;
        presentacion.dashboardCatalogItemID = Number(datos.dashboardCatalogItemID) || 0;
        presentacion.orden = Math.max(0, parseInt(datos.orden, 10) || 0);
        presentacion.cardTypeCatalogItemID = Number(display.cardTypeCatalogItemID) || 0;
        presentacion.widthCatalogItemID = Number(display.widthCatalogItemID) || 0;
        presentacion.chartTypeCatalogItemID = Number(display.chartTypeCatalogItemID) || 0;
        presentacion.title = textoDe(display.title);
        presentacion.labelUid = uidDeClave(columnas, display.labelField);
        presentacion.valueUids = (Array.isArray(display.valueFields) ? display.valueFields : []).map(function (clave) {
            return uidDeClave(columnas, clave);
        }).filter(function (uid) {
            return uid !== '';
        });
        presentacion.seriesUid = uidDeClave(columnas, display.seriesField);
        presentacion.indicatorUid = uidDeClave(columnas, display.indicatorField);
        presentacion.indicatorFormat = display.indicatorFormat === 'money' ? 'money' : 'number';
        return presentacion;
    }

    /**
     * Quita de la presentación las columnas que ya no están en Mostrar.
     *
     * @return {Array} Nombres de los campos de la presentación que se limpiaron.
     */
    function limpiarPresentacion(presentacion, columnas) {
        var existe = function (uid) {
            return claveDeColumna(columnas, uid) !== '';
        };
        var limpiados = [];
        if (presentacion.labelUid !== '' && !existe(presentacion.labelUid)) {
            presentacion.labelUid = '';
            limpiados.push('Campo etiqueta');
        }
        var valores = presentacion.valueUids.filter(existe);
        if (valores.length !== presentacion.valueUids.length) {
            presentacion.valueUids = valores;
            limpiados.push('Campos valor');
        }
        if (presentacion.seriesUid !== '' && !existe(presentacion.seriesUid)) {
            presentacion.seriesUid = '';
            limpiados.push('Campo serie');
        }
        if (presentacion.indicatorUid !== '' && !existe(presentacion.indicatorUid)) {
            presentacion.indicatorUid = '';
            limpiados.push('Campo valor principal');
        }
        return limpiados;
    }

    /**
     * Revisa la presentación antes de guardar (el servidor valida igual).
     *
     * @param {Object} presentacion Estado del modal.
     * @param {Array} columnas Columnas del estado.
     * @param {Object} catalogos builderCatalogs.
     * @return {Array} Mensajes; vacío si está bien.
     */
    function validarPresentacion(presentacion, columnas, catalogos) {
        var errores = [];
        var cat = catalogos || {};
        var orden = Number(presentacion.orden);
        if (textoDe(presentacion.orden).trim() === '' || !isFinite(orden) || orden < 0 || Math.floor(orden) !== orden) {
            errores.push('El orden debe ser un número entero mayor o igual a 0.');
        }
        if (!presentacion.dashboardCatalogItemID) {
            return errores;
        }
        var tarjeta = tipoTarjeta(buscarItemCatalogo(cat.cardType, presentacion.cardTypeCatalogItemID));
        if (!buscarItemCatalogo(cat.cardType, presentacion.cardTypeCatalogItemID)) {
            errores.push('Seleccione el tipo de tarjeta.');
        }
        if (!buscarItemCatalogo(cat.width, presentacion.widthCatalogItemID)) {
            errores.push('Seleccione el ancho de la tarjeta.');
        }
        if (textoDe(presentacion.title).length > LIMITES.titulo) {
            errores.push('El título puede tener hasta ' + LIMITES.titulo + ' caracteres.');
        }
        if (tarjeta === 'grafico') {
            if (!buscarItemCatalogo(cat.chartType, presentacion.chartTypeCatalogItemID)) {
                errores.push('Seleccione el tipo de gráfico.');
            }
            if (claveDeColumna(columnas, presentacion.labelUid) === '') {
                errores.push('Seleccione el campo etiqueta del gráfico.');
            }
            if (presentacion.valueUids.filter(function (uid) { return claveDeColumna(columnas, uid) !== ''; }).length === 0) {
                errores.push('Seleccione al menos un campo valor del gráfico.');
            }
        }
        if (tarjeta === 'indicador' && claveDeColumna(columnas, presentacion.indicatorUid) === '') {
            errores.push('Seleccione el campo valor principal del indicador.');
        }
        return errores;
    }

    /**
     * Arma "display" del contrato de saveQuery; null si no hay dashboard. Las columnas se convierten
     * a su clave posicional actual ("c" + índice) en este momento.
     *
     * @return {Object|null} display.
     */
    function construirDisplay(presentacion, columnas, catalogos) {
        if (!presentacion.dashboardCatalogItemID) {
            return null;
        }
        var cat = catalogos || {};
        var tarjeta = tipoTarjeta(buscarItemCatalogo(cat.cardType, presentacion.cardTypeCatalogItemID));
        var esGrafico = tarjeta === 'grafico';
        var esIndicador = tarjeta === 'indicador';
        return {
            cardTypeCatalogItemID: Number(presentacion.cardTypeCatalogItemID) || 0,
            widthCatalogItemID: Number(presentacion.widthCatalogItemID) || 0,
            chartTypeCatalogItemID: esGrafico ? (Number(presentacion.chartTypeCatalogItemID) || 0) : 0,
            title: textoDe(presentacion.title).trim().substring(0, LIMITES.titulo),
            labelField: esGrafico ? claveDeColumna(columnas, presentacion.labelUid) : '',
            valueFields: esGrafico ? presentacion.valueUids.map(function (uid) {
                return claveDeColumna(columnas, uid);
            }).filter(function (clave) {
                return clave !== '';
            }) : [],
            seriesField: esGrafico ? claveDeColumna(columnas, presentacion.seriesUid) : '',
            indicatorField: esIndicador ? claveDeColumna(columnas, presentacion.indicatorUid) : '',
            indicatorFormat: esIndicador && presentacion.indicatorFormat === 'money' ? 'money' : 'number'
        };
    }

    /* ---------------- Utilidades de lista y descarga ---------------- */

    /* Devuelve una lista nueva con el elemento movido; si se sale de los límites queda igual */
    function moverElemento(lista, indice, delta) {
        var copia = (lista || []).slice();
        var destino = indice + delta;
        if (indice < 0 || indice >= copia.length || destino < 0 || destino >= copia.length) {
            return copia;
        }
        var elemento = copia.splice(indice, 1)[0];
        copia.splice(destino, 0, elemento);
        return copia;
    }

    /* Qué hacer con la respuesta de exportExcel: json (error del servidor), sesion (HTML o fallo HTTP) o archivo */
    function tipoRespuestaExcel(contentType, ok) {
        var tipo = textoDe(contentType).toLowerCase();
        if (tipo.indexOf('json') !== -1) {
            return 'json';
        }
        if (tipo.indexOf('text/html') !== -1 || !ok) {
            return 'sesion';
        }
        return 'archivo';
    }

    /* Nombre del archivo desde Content-Disposition, sin rutas ni caracteres inválidos */
    function nombreDescarga(contentDisposition, porDefecto) {
        var defecto = porDefecto || 'reporte.xlsx';
        var coincide = /filename\*?=(?:UTF-8'')?"?([^";]+)"?/i.exec(textoDe(contentDisposition));
        if (!coincide) {
            return defecto;
        }
        var nombre = coincide[1];
        try {
            nombre = decodeURIComponent(nombre);
        } catch (ex) {
            nombre = coincide[1];
        }
        nombre = nombre.replace(/[\\\/:*?"<>|]/g, '_').trim();
        return nombre !== '' ? nombre : defecto;
    }

    var PosmeReportBuilder = {
        LIMITES: LIMITES,
        MODO_AGRUPAR: MODO_AGRUPAR,
        escaparHtml: escaparHtml,
        filtrarVistas: filtrarVistas,
        limpiarNumero: limpiarNumero,
        esNumeroValido: esNumeroValido,
        formatearNumero: formatearNumero,
        formatearEntradaNumero: formatearEntradaNumero,
        formatearEntero: formatearEntero,
        esFechaValida: esFechaValida,
        formatearFecha: formatearFecha,
        formatearCelda: formatearCelda,
        rangoPaginacion: rangoPaginacion,
        puedeAvanzar: puedeAvanzar,
        tamaniosPagina: tamaniosPagina,
        buscarCampo: buscarCampo,
        etiquetaCampo: etiquetaCampo,
        etiquetaFuncion: etiquetaFuncion,
        operadoresDeTipo: operadoresDeTipo,
        funcionesDeTipo: funcionesDeTipo,
        opcionesModo: opcionesModo,
        esFuncion: esFuncion,
        nuevaColumna: nuevaColumna,
        aplicarModoColumna: aplicarModoColumna,
        nuevoFiltro: nuevoFiltro,
        nuevoUid: nuevoUid,
        claveOrden: claveOrden,
        separarClaveOrden: separarClaveOrden,
        hayAgrupacion: hayAgrupacion,
        opcionesOrden: opcionesOrden,
        limpiarOrden: limpiarOrden,
        construirFiltros: construirFiltros,
        construirFiltrosParaGuardar: construirFiltrosParaGuardar,
        construirConfig: construirConfig,
        construirConfigParaGuardar: construirConfigParaGuardar,
        desdeConfig: desdeConfig,
        normalizarConfigCargada: normalizarConfigCargada,
        validarConfig: validarConfig,
        debeAvisarMoneda: debeAvisarMoneda,
        resumirConfig: resumirConfig,
        moverElemento: moverElemento,
        tipoRespuestaExcel: tipoRespuestaExcel,
        nombreDescarga: nombreDescarga,
        FORMATOS_INDICADOR: FORMATOS_INDICADOR,
        esFechaDinamica: esFechaDinamica,
        opcionesFechaDinamica: opcionesFechaDinamica,
        valorExtremoFiltro: valorExtremoFiltro,
        camposDisponibles: camposDisponibles,
        opcionesOrdenLibres: opcionesOrdenLibres,
        presentacionVacia: presentacionVacia,
        copiarPresentacion: copiarPresentacion,
        buscarItemCatalogo: buscarItemCatalogo,
        tipoTarjeta: tipoTarjeta,
        opcionesColumnasPresentacion: opcionesColumnasPresentacion,
        claveDeColumna: claveDeColumna,
        uidDeClave: uidDeClave,
        presentacionDesdeConsulta: presentacionDesdeConsulta,
        limpiarPresentacion: limpiarPresentacion,
        validarPresentacion: validarPresentacion,
        construirDisplay: construirDisplay
    };

    if (root) {
        root.PosmeReportBuilder = PosmeReportBuilder;
    }

    if (typeof module !== 'undefined' && module.exports) {
        module.exports = PosmeReportBuilder;
    }
}(typeof window !== 'undefined' ? window : this));
