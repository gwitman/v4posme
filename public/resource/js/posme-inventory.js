/* ==========================================================================
 * posme-inventory.js
 * Capa de interfaz (sin logica de negocio) para las pantallas de inventario
 * rediseñadas con el prefijo .pv2.pinv (ver public/resource/css/posme-inventory.css).
 *
 * API publica: window.PosmeInv
 *   PosmeInv.labelTable(selector)
 *       Escribe data-label en cada <td> del tbody segun el texto del <th> de su
 *       misma posicion (thead original o, si DataTables creo uno, .dataTables_scrollHead).
 *       Un <th data-label="..."> tiene prioridad sobre su texto.
 *       Se reaplica sola en el evento "draw" de DataTables 1.9 y con un
 *       MutationObserver sobre el tbody (filas de $.tmpl, fnAddData, fnUpdate...).
 *
 *   PosmeInv.initDetail({ table:"#tb_transaction_master_detail", totalSelector:"#txtTotal"|null })
 *       - llama labelTable(table)
 *       - .pinv-count      -> numero de filas reales (ignora la fila "No data" de DataTables)
 *       - .pinv-empty      -> visible solo si no hay filas
 *       - .pv2-mbar-total  -> valor de totalSelector o "N productos"
 *       - refresca con setTimeout(0) tras draw, mutacion y blur/change de inputs del tbody
 *       - llama pv2BindMobileBar() UNA sola vez (no la llames tambien en la pantalla)
 *       Opcionales: countSelector, emptySelector, mbarTotalSelector, scope,
 *                   unitSingular ("producto"), unitPlural ("productos").
 *
 *   PosmeInv.refresh()
 *       Recalcula contador / vacio / total de todos los detalles inicializados.
 *       Util despues de cambiar un total por codigo (no dispara eventos).
 *
 * Inicializacion idempotente: llamar varias veces initDetail/labelTable sobre la
 * misma tabla no duplica observadores ni eventos.
 * ES5: sin arrow functions, let/const ni template literals. Requiere jQuery.
 * ========================================================================== */
(function (root, $) {
	'use strict';

	if (!$) { return; }
	if (root.PosmeInv && root.PosmeInv.__version) { return; }

	var NS 			= '.pinv';
	var detalles 	= [];		//configuraciones registradas por initDetail
	var mbarLigada 	= false;

	/* ---------------------------------------------------------------- utilidades */
	function textoLimpio(el) {
		var $el 	= $(el);
		var forzado = $el.attr('data-label');
		if (forzado !== undefined && forzado !== null) { return $.trim(forzado); }
		return $.trim($el.text()).replace(/\s+/g, ' ');
	}

	function programar(fn) {
		//agrupa varios avisos seguidos en una sola ejecucion
		if (fn.__pendiente) { return; }
		fn.__pendiente = true;
		setTimeout(function () {
			fn.__pendiente = false;
			fn();
		}, 0);
	}

	function esFilaVacia(tr) {
		return $(tr).children('td.dataTables_empty').length > 0;
	}

	/* ---------------------------------------------------------------- etiquetas */
	function obtenerTitulos(table) {
		var $table 	= $(table);
		var $ths 	= $table.children('thead').find('tr').last().children('th,td');
		var $wrap 	= $table.closest('.dataTables_wrapper');
		if ($wrap.length) {
			var $scrollHead = $wrap.find('.dataTables_scrollHead thead tr').last().children('th,td');
			if ($scrollHead.length) { $ths = $scrollHead; }
		}
		var titulos = [];
		$ths.each(function () { titulos.push(textoLimpio(this)); });
		return titulos;
	}

	function aplicarEtiquetas(table) {
		var titulos = obtenerTitulos(table);
		var $tbody 	= $(table).children('tbody');
		$tbody.children('tr').each(function () {
			var $tr = $(this);
			if (esFilaVacia(this)) {
				$tr.addClass('pinv-row-empty');
				return;
			}
			$tr.removeClass('pinv-row-empty');
			$tr.children('td').each(function (indice) {
				var etiqueta = titulos[indice] !== undefined ? titulos[indice] : '';
				if (this.getAttribute('data-label') !== etiqueta) {
					this.setAttribute('data-label', etiqueta);
				}
			});
		});
	}

	function enlazarTabla(table, alCambiar) {
		var $table 	= $(table);
		var estado 	= $table.data('pinvEstado');
		if (!estado) {
			estado = { avisos: [] };
			$table.data('pinvEstado', estado);

			var notificar = function () {
				aplicarEtiquetas(table);
				for (var i = 0; i < estado.avisos.length; i++) {
					try { estado.avisos[i](); } catch (ex) { if (root.console) { console.info('PosmeInv', ex); } }
				}
			};
			estado.notificar = function () { programar(notificar); };

			//DataTables 1.9 dispara "draw" sobre la tabla
			$table.on('draw' + NS, estado.notificar);

			//filas agregadas/actualizadas sin redibujar (tmpl, fnAddData con bRedraw=false, fnUpdate)
			var tbody = $table.children('tbody')[0];
			if (tbody && root.MutationObserver) {
				estado.observer = new root.MutationObserver(function () { estado.notificar(); });
				estado.observer.observe(tbody, { childList: true, subtree: true });
			}
		}
		if (alCambiar && $.inArray(alCambiar, estado.avisos) === -1) {
			estado.avisos.push(alCambiar);
		}
		return estado;
	}

	function labelTable(selector) {
		$(selector).filter('table').each(function () {
			aplicarEtiquetas(this);
			enlazarTabla(this, null);
		});
	}

	/* ---------------------------------------------------------------- detalle */
	function contarFilas(table) {
		var total = 0;
		$(table).children('tbody').children('tr').each(function () {
			if (!esFilaVacia(this)) { total++; }
		});
		return total;
	}

	function leerValor(selector) {
		var $el = $(selector).first();
		if (!$el.length) { return ''; }
		return $el.is('input,select,textarea') ? $el.val() : $.trim($el.text());
	}

	function actualizarDetalle(cfg) {
		var $table = $(cfg.table).first();
		if (!$table.length) { return; }

		var $scope = cfg.scope ? $(cfg.scope) : $table.closest('.pinv');
		if (!$scope.length) { $scope = $(document); }

		var n = contarFilas($table[0]);

		$scope.find(cfg.countSelector).text(n);

		var $vacio = $scope.find(cfg.emptySelector);
		$table.toggleClass('pinv-has-emptybox', $vacio.length > 0);
		if ($vacio.length) { $vacio.toggle(n === 0); }

		var texto;
		if (cfg.totalSelector) {
			texto = leerValor(cfg.totalSelector);
		}
		else {
			texto = n + ' ' + (n === 1 ? cfg.unitSingular : cfg.unitPlural);
		}
		$scope.find(cfg.mbarTotalSelector).text(texto);
	}

	function ligarBarraMovil() {
		if (mbarLigada) { return; }
		mbarLigada = true;
		if (typeof root.pv2BindMobileBar === 'function') {
			root.pv2BindMobileBar();
		}
	}

	function initDetail(opts) {
		var cfg = $.extend({
			table				: '#tb_transaction_master_detail',
			totalSelector		: null,
			countSelector		: '.pinv-count',
			emptySelector		: '.pinv-empty',
			mbarTotalSelector	: '.pv2-mbar-total',
			scope				: null,
			unitSingular		: 'producto',
			unitPlural			: 'productos'
		}, opts || {});

		//idempotente: si ya existe una configuracion para la misma tabla se reemplaza
		var existente = null;
		for (var i = 0; i < detalles.length; i++) {
			if (detalles[i].table === cfg.table) { existente = detalles[i]; break; }
		}
		if (existente) {
			$.extend(existente, cfg);
			cfg = existente;
		}
		else {
			cfg.actualizar = function () { actualizarDetalle(cfg); };
			detalles.push(cfg);

			//cambios dentro de las celdas (cantidad, costo, precio...)
			$(document).on('blur' + NS + ' change' + NS, cfg.table + ' tbody :input', function () {
				programar(cfg.actualizar);
			});
		}

		$(cfg.table).filter('table').each(function () {
			aplicarEtiquetas(this);
			enlazarTabla(this, cfg.actualizar);
		});

		ligarBarraMovil();
		programar(cfg.actualizar);
		return cfg;
	}

	function refresh() {
		for (var i = 0; i < detalles.length; i++) {
			$(detalles[i].table).filter('table').each(function () { aplicarEtiquetas(this); });
			actualizarDetalle(detalles[i]);
		}
	}

	/* ---------------------------------------------------------------- controles en telefono */
	//Telefono = bandera del servidor (varUseMobile == "1") o pantalla menor a 768px
	function isMobile() {
		var pantallaChica = root.matchMedia ? root.matchMedia('(max-width: 767px)').matches : (root.innerWidth < 768);
		return (typeof root.varUseMobile !== 'undefined' && root.varUseMobile == '1') || pantallaChica;
	}

	//Deja solo la parte YYYY-MM-DD; cualquier otro formato se devuelve vacio
	//para que el input type="date" no muestre un valor invalido
	function normalizarFecha(valor) {
		var texto = $.trim(valor === null || valor === undefined ? '' : String(valor)).substring(0, 10);
		//la fecha cero de MySQL (0000-00-00) equivale a "sin fecha"
		return (/^\d{4}-\d{2}-\d{2}$/.test(texto) && texto !== '0000-00-00') ? texto : '';
	}

	//En telefono: quita select2 y deja el <select> nativo. En PC no hace nada.
	//Se ejecuta en setTimeout(0) para correr despues del select2/uniform global (genyx-app-init.js, app.js)
	function mobileSelects(scope) {
		if (!isMobile()) { return; }
		setTimeout(function () {
			$(scope || document).find('select.select2, select.select2-offscreen').each(function () {
				var $select = $(this);
				try {
					if ($select.data('select2')) { $select.select2('destroy'); }
				}
				catch (ex) {
					if (root.console) { console.info('PosmeInv.mobileSelects', ex); }
				}
				$select.removeClass('select2 select2-offscreen').addClass('form-control').css('display', '');
			});
		}, 0);
	}

	//En telefono: convierte los campos de fecha en <input type="date"> (calendario nativo).
	//No pone la fecha de hoy si el campo esta vacio. En PC no hace nada.
	function mobileDates(selectores) {
		if (!isMobile()) { return; }
		setTimeout(function () {
			$(selectores).filter('input').each(function () {
				var input 	= this;
				var $input 	= $(input);
				var valor 	= normalizarFecha(input.value);
				try {
					if ($input.data('datepicker')) { $input.datepicker('remove'); }
				}
				catch (ex) {
					if (root.console) { console.info('PosmeInv.mobileDates', ex); }
				}
				//readonly bloquea el selector nativo; el valor solo cambia desde el calendario
				$input.removeAttr('readonly');
				try { input.type = 'date'; } catch (ex2) { if (root.console) { console.info('PosmeInv.mobileDates', ex2); } }
				input.value = valor;

				var $grupo = $input.closest('.input-group');
				$grupo.find('.input-group-addon').hide();
				$grupo.removeClass('input-group date');
			});
		}, 0);
	}

	root.PosmeInv = {
		__version		: 2,
		initDetail		: initDetail,
		labelTable		: labelTable,
		refresh			: refresh,
		isMobile		: isMobile,
		mobileSelects	: mobileSelects,
		mobileDates		: mobileDates,
		normalizarFecha	: normalizarFecha
	};

})(window, window.jQuery);
