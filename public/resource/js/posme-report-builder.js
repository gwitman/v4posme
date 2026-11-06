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
            value2: ''
        };
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
            var esNumero = !!campo && campo.type === 'number';
            var valor = esNumero ? limpiarNumero(filtro.value) : textoDe(filtro.value);
            var valor2 = esNumero ? limpiarNumero(filtro.value2) : textoDe(filtro.value2);

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
            var esNumero = !!campo && campo.type === 'number';
            var valor = esNumero ? limpiarNumero(filtro.value) : textoDe(filtro.value);
            var valor2 = esNumero ? limpiarNumero(filtro.value2) : textoDe(filtro.value2);
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
            var item = nuevoFiltro(campo, permitidos);
            item.operator = textoDe(filtro.operator) || item.operator;
            item.value = esNumero ? formatearEntradaNumero(filtro.value) : textoDe(filtro.value);
            item.value2 = esNumero ? formatearEntradaNumero(filtro.value2) : textoDe(filtro.value2);
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
        for (var i = 0; i < valores.length; i += 1) {
            if (permitirVacios && esVacio(valores[i])) {
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
        if (filtro.operator === 'between' && !faltaExtremo) {
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
        nombreDescarga: nombreDescarga
    };

    if (root) {
        root.PosmeReportBuilder = PosmeReportBuilder;
    }

    if (typeof module !== 'undefined' && module.exports) {
        module.exports = PosmeReportBuilder;
    }
}(typeof window !== 'undefined' ? window : this));
