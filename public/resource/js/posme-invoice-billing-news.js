/*
 * posme-invoice-billing-news.js
 * Rediseño visual de la pantalla "Nueva factura" (app_invoice_billing/add -> vistas news_*).
 *
 * Se carga UNICAMENTE desde app/Views/app_invoice_billing/news_head.php, despues de
 * bootstrap-datepicker.js y antes de news_script.php. Por eso:
 *   - Los reemplazos de $.fn.select2 / $.fn.uniform / $.fn.datepicker solo afectan esta pantalla.
 *   - Los $(function(){...}) registrados aqui se ejecutan ANTES que el ready de news_script.php
 *     (pero despues de los de theme-genyx/js/app.js y genyx-app-init.js, que se cargan en la masterpage;
 *     esos ready llaman a .select2()/.uniform() en tiempo de ejecucion y ya encuentran los reemplazos).
 *
 * Variables globales que espera (impresas en news_head.php):
 *   pmUseMobile               '1' si el usuario usa la version movil
 *   pmOcultarPickerNextVisit  valor de controlNextVisitOcultarPicker
 *
 * Expone:
 *   window.pmNativeControls   true si se usan controles nativos (fecha nativa, sin datepicker)
 *   window.pmDesplazarFilaDetalle(indice, reconstruida)  auto-scroll a una fila del detalle (lo llama onCompleteNewItem)
 *   #pmPanelAux.pm-aux-abierto  panel flotante izquierdo abierto (tactil)
 *   <html class="pm-invoice-news [pm-native] [pm-usemobile] [pm-superpuesto] [pm-sin-cobro] [pm-cobro-hoja-abierta]">
 *   Variables CSS en <html>: --pm-top, --pm-tabla-max, --pm-cobro-alto
 */
(function ($, window, document) {
	'use strict';

	if (!$) {
		return;
	}

	var htmlEl = document.documentElement;

	function agregarClaseHtml(clase) {
		if ((' ' + htmlEl.className + ' ').indexOf(' ' + clase + ' ') < 0) {
			htmlEl.className = (htmlEl.className ? htmlEl.className + ' ' : '') + clase;
		}
	}

	function tieneClaseHtml(clase) {
		return (' ' + htmlEl.className + ' ').indexOf(' ' + clase + ' ') >= 0;
	}

	function alternarClaseHtml(clase, activo) {
		var tiene = tieneClaseHtml(clase);
		if (activo && !tiene) {
			agregarClaseHtml(clase);
		} else if (!activo && tiene) {
			htmlEl.className = $.trim((' ' + htmlEl.className + ' ').replace(' ' + clase + ' ', ' '));
		}
	}

	function media(query) {
		try {
			return !!(window.matchMedia && window.matchMedia(query).matches);
		} catch (e) {
			return false;
		}
	}

	/* ------------------------------------------------------------------
	 * 1. Regla "controles nativos" (se calcula una sola vez al cargar)
	 * ------------------------------------------------------------------ */
	var pmUseMobile = (typeof window.pmUseMobile !== 'undefined' && window.pmUseMobile !== null) ? String(window.pmUseMobile) : '0';
	var pmNativeControls =
		(pmUseMobile === '1') ||
		media('(hover:none) and (pointer:coarse)') ||
		media('(max-width:767px)');

	window.pmNativeControls = pmNativeControls;

	agregarClaseHtml('pm-invoice-news');
	if (pmNativeControls) {
		agregarClaseHtml('pm-native');
	}
	if (pmUseMobile === '1') {
		agregarClaseHtml('pm-usemobile');
	}

	/* ------------------------------------------------------------------
	 * 2. Sin select2 en ninguna pantalla/dispositivo (red de seguridad para
	 *    genyx-app-init.js y HTML/JS inyectado por personalizacion de empresa)
	 * ------------------------------------------------------------------ */
	$.fn.select2 = function () {
		return this;
	};

	/* ------------------------------------------------------------------
	 * 3. uniform: sigue aplicando a checkbox/radio/file, pero nunca a select
	 * ------------------------------------------------------------------ */
	if (typeof $.fn.uniform === 'function') {
		var uniformOriginal = $.fn.uniform;
		var uniformSinSelect = function () {
			var elementos = this.not('select');
			if (elementos.length > 0) {
				uniformOriginal.apply(elementos, arguments);
			}
			return this;
		};
		for (var claveUniform in uniformOriginal) {
			if (Object.prototype.hasOwnProperty.call(uniformOriginal, claveUniform)) {
				uniformSinSelect[claveUniform] = uniformOriginal[claveUniform];
			}
		}
		$.fn.uniform = uniformSinSelect;
	}

	/* ------------------------------------------------------------------
	 * 4. Fechas nativas en tablet/telefono
	 * ------------------------------------------------------------------ */
	function convertirAFechaNativa(selector, exigirHtmlOriginal) {
		var $el = $(selector);
		if ($el.length !== 1) {
			return;
		}
		var el = $el[0];
		var tipo = (el.getAttribute('type') || 'text').toLowerCase();
		if (el.tagName !== 'INPUT' || tipo !== 'text') {
			return;
		}
		if (exigirHtmlOriginal) {
			//Solo si es el HTML por defecto: div.input-group.date > input + span.input-group-addon
			var $padre = $el.parent();
			if (!$padre.is('div.input-group.date') || $el.siblings().length !== 1 || $el.siblings('.input-group-addon').length !== 1) {
				return;
			}
		}

		var valorAnterior = el.value;
		try {
			el.type = 'date';
		} catch (e) {
			return;
		}
		if (el.type !== 'date') {
			//Navegador sin soporte de input date: queda como texto (yyyy-mm-dd manual)
			return;
		}
		$el.addClass('pm-date-native');
		$el.parent('.input-group.date').addClass('pm-date-native-group');
		if (valorAnterior) {
			$el.val(valorAnterior);
		}
	}

	if (pmNativeControls) {

		//bootstrap-datepicker queda como no-op (conservando propiedades estaticas)
		var datepickerOriginal = $.fn.datepicker;
		var datepickerNoop = function () {
			return this;
		};
		if (datepickerOriginal) {
			for (var claveDp in datepickerOriginal) {
				if (Object.prototype.hasOwnProperty.call(datepickerOriginal, claveDp)) {
					datepickerNoop[claveDp] = datepickerOriginal[claveDp];
				}
			}
		}
		$.fn.datepicker = datepickerNoop;

		//input type=date solo acepta yyyy-mm-dd: recortar "yyyy-mm-dd hh:mm:ss" y limpiar lo invalido
		$.valHooks.date = {
			set: function (elem, value) {
				var texto = (value === null || value === undefined) ? '' : String(value);
				var valor = /^\d{4}-\d{2}-\d{2}/.test(texto) ? texto.substring(0, 10) : '';
				elem.value = valor;
				return valor;
			}
		};

		//Se registra antes que el ready de news_script.php
		$(function () {
			convertirAFechaNativa('#txtDate', false);
			convertirAFechaNativa('#txtDateFirst', false);

			//txtNextVisit: igual que news_script, solo cuando el picker no esta oculto por la empresa
			var ocultarPicker = (typeof window.varParameterOcultarCalendarPickerNextVisit !== 'undefined')
				? String(window.varParameterOcultarCalendarPickerNextVisit)
				: String(typeof window.pmOcultarPickerNextVisit !== 'undefined' ? window.pmOcultarPickerNextVisit : 'false');
			if ($.trim(ocultarPicker) === 'false') {
				convertirAFechaNativa('#txtNextVisit', true);
			}
		});
	}

	/* ------------------------------------------------------------------
	 * 5. Utilidades del layout (barra movil + panel de cobro)
	 * ------------------------------------------------------------------ */
	function esEscritorio() {
		return media('(min-width:1200px)');
	}
	function esTablet() {
		return media('(min-width:768px) and (max-width:1199px)');
	}
	function esTelefono() {
		return media('(max-width:767px)');
	}

	function fijarVariableCss(nombre, valor) {
		if (htmlEl.style.getPropertyValue(nombre) !== valor) {
			htmlEl.style.setProperty(nombre, valor);
		}
	}

	function leerVariableCssPx(nombre) {
		return parseInt(htmlEl.style.getPropertyValue(nombre), 10) || 0;
	}

	//Alto del navbar fijo de la masterpage (navbar-fixed-top) para el top de los elementos sticky
	function altoNavbarFijo() {
		var alto = 0;
		$('.navbar-fixed-top').each(function () {
			var estilo = window.getComputedStyle ? window.getComputedStyle(this) : null;
			if (!estilo || estilo.position !== 'fixed' || estilo.display === 'none') {
				return;
			}
			var r = this.getBoundingClientRect();
			if (r.top <= 1 && r.bottom > alto) {
				alto = Math.round(r.bottom);
			}
		});
		return alto;
	}

	//Sidebar (zona/mesa/pago/factura) o modal personalizado abierto
	function hayPanelSuperpuesto() {
		var abierto = false;
		$('.sidebar').each(function () {
			if (this.offsetWidth > 0 && !$(this).hasClass('hidden')) {
				abierto = true;
				return false;
			}
		});
		if (!abierto) {
			$('[class^="modal-customer1-"], [class*=" modal-customer1-"]').each(function () {
				if (this.offsetWidth > 0 || this.offsetHeight > 0) {
					abierto = true;
					return false;
				}
			});
		}
		if (!abierto && document.body && (' ' + document.body.className + ' ').indexOf(' modal-open ') >= 0) {
			abierto = true;
		}
		return abierto;
	}

	/* ------------------------------------------------------------------
	 * 6. Barra fija inferior (telefono), panel de cobro y auto-scroll
	 *    Los totales se escriben con .val()/.text() sin disparar eventos,
	 *    por eso el metodo principal es un sondeo liviano cada 500 ms que
	 *    solo toca el DOM cuando el valor cambia. Se complementa con
	 *    'change input' sobre #txtTotal/#txtChangeAmount y MutationObserver
	 *    sobre #txtTotalAlternativo, las .sidebar y el <body> (modal-open).
	 * ------------------------------------------------------------------ */
	$(function () {
		var $barra 			= $('#pmBarraMovil');
		var $layout 		= $('#pmLayout');
		var $aside 			= $('#pmColCobro');
		var $pagos 			= $('#pmCobroPagos');
		var $btnMasPagos 	= $('#pmBtnMasPagos');
		var $btnExpandir 	= $('#pmBtnCobroExpandir');
		var $fondo 			= $();
		var hayLayout 		= $layout.length > 0 && $aside.length > 0;

		//Fuera de los contenedores de la pagina para que position:fixed sea relativo a la ventana
		if ($barra.length > 0) {
			$barra.appendTo(document.body);
		}

		/* 6.1 Marcas de filas (solo se agregan clases; ids, names y orden de celdas intactos) */
		$('#txtTotal').closest('tr').addClass('pm-fila-total');
		$('#txtReceiptAmount').closest('tr').addClass('pm-fila-recibido');
		$('#txtReceiptAmountDol').closest('tr').addClass('pm-fila-recibido-ext');
		$('#txtChangeAmount').closest('tr').addClass('pm-fila-cambio');
		$('#txtReceiptAmountTarjeta, #txtReceiptAmountTarjetaDol, #txtReceiptAmountBank, #txtReceiptAmountBankDol, #txtReceiptAmountPoint').closest('tr').addClass('pm-pago-extra');
		$('#txtCustomerDescription').closest('.form-group').addClass('pm-campo-ancho');

		if (hayLayout) {
			//Fondo oscuro de la hoja inferior (no es parte del formulario)
			$fondo = $('<div class="pm-cobro-fondo" id="pmCobroFondo"></div>').appendTo(document.body);
		}

		/* 6.2 Hoja inferior de cobro (tablet expandido / telefono) */
		function hojaAbierta() {
			return hayLayout && $aside.hasClass('pm-cobro-abierto');
		}

		function abrirHoja() {
			if (!hayLayout || esEscritorio()) {
				return;
			}
			$aside.addClass('pm-cobro-abierto');
			alternarClaseHtml('pm-cobro-hoja-abierta', true);
			$btnExpandir.attr('aria-expanded', 'true').text('Menos');
			$aside.scrollTop(0);
		}

		function cerrarHoja() {
			if (!hayLayout) {
				return;
			}
			if ($aside.hasClass('pm-cobro-abierto')) {
				$aside.removeClass('pm-cobro-abierto');
			}
			alternarClaseHtml('pm-cobro-hoja-abierta', false);
			if ($btnExpandir.attr('aria-expanded') !== 'false') {
				$btnExpandir.attr('aria-expanded', 'false').text('Más');
			}
		}

		/* 6.2b Escritorio: boton flotante con el TOTAL que abre/cierra el panel de cobro */
		var $btnFlotante 		= $();
		var $flotanteMonto 		= $();
		var ultimoTotalFlotante = null;

		if (hayLayout) {
			$btnFlotante = $(
				'<button type="button" id="pmBtnCobroFlotante" aria-controls="pmColCobro" aria-expanded="false" title="Cobro (total y formas de pago)">' +
					'<span class="pm-cobro-flotante-icono" aria-hidden="true">&#128722;</span>' +
					'<span class="pm-cobro-flotante-texto">' +
						'<span class="pm-cobro-flotante-etiqueta">Total</span>' +
						'<span class="pm-cobro-flotante-monto" id="pmBtnCobroFlotanteMonto">0.00</span>' +
					'</span>' +
				'</button>'
			).appendTo(document.body);
			$flotanteMonto = $btnFlotante.find('#pmBtnCobroFlotanteMonto');
		}

		function flotanteAbierto() {
			return tieneClaseHtml('pm-cobro-flotante-abierto');
		}

		function abrirFlotante(enfocarRecibido) {
			if (!hayLayout || !esEscritorio() || flotanteAbierto()) {
				return;
			}
			alternarClaseHtml('pm-cobro-flotante-abierto', true);
			$btnFlotante.attr('aria-expanded', 'true');
			if (enfocarRecibido) {
				var $recibido = $aside.find('#txtReceiptAmount');
				if ($recibido.length > 0 && $recibido.is(':visible')) {
					$recibido.focus();
				}
			}
		}

		function cerrarFlotante() {
			if (!flotanteAbierto()) {
				return;
			}
			alternarClaseHtml('pm-cobro-flotante-abierto', false);
			$btnFlotante.attr('aria-expanded', 'false');
		}

		$btnFlotante.on('click', function (e) {
			e.preventDefault();
			if (flotanteAbierto()) {
				cerrarFlotante();
			} else {
				abrirFlotante(true);
			}
		});
		//Cualquier .focus() del flujo existente (ej. Enter vacio en el escaner -> #txtReceiptAmount) abre el panel
		$aside.on('focusin', function () {
			abrirFlotante(false);
		});

		$btnExpandir.on('click', function (e) {
			e.preventDefault();
			if (hojaAbierta()) {
				cerrarHoja();
			} else {
				abrirHoja();
			}
			refrescar();
		});
		$('#pmBtnCobroCerrar').on('click', function (e) {
			e.preventDefault();
			cerrarHoja();
			if (flotanteAbierto()) {
				cerrarFlotante();
				$btnFlotante.focus();
			}
			refrescar();
		});
		$fondo.on('click', function () {
			cerrarHoja();
			refrescar();
		});
		$(document).on('keydown', function (e) {
			if ((e.key === 'Escape' || e.keyCode === 27) && hojaAbierta()) {
				cerrarHoja();
				refrescar();
			}
			else if ((e.key === 'Escape' || e.keyCode === 27) && flotanteAbierto() && !hayPanelSuperpuesto()) {
				cerrarFlotante();
				//El foco no se queda en un control fuera de la ventana
				$btnFlotante.focus();
			}
		});

		/* 6.3 "Mas formas de pago" (tarjeta / transferencia / puntos) */
		function hayValorEnPagosExtra() {
			var hay = false;
			$pagos.find('tr.pm-pago-extra:not(.hidden) input.txt-numeric').each(function () {
				var texto 	= $.trim(String(this.value || '')).replace(/,/g, '');
				var numero 	= parseFloat(texto);
				if (texto !== '' && !isNaN(numero) && numero !== 0) {
					hay = true;
					return false;
				}
			});
			return hay;
		}

		function actualizarMasPagos() {
			if ($pagos.length === 0 || $btnMasPagos.length === 0) {
				return;
			}
			//Si una personalizacion movio #divPaymentOption o la empresa oculta esas filas, no hay boton
			var disponibles = $pagos.find('#divPaymentOption:not(.hidden) tr.pm-pago-extra:not(.hidden)').length > 0;
			if (!disponibles) {
				if (!$btnMasPagos.hasClass('hidden')) {
					$btnMasPagos.addClass('hidden');
				}
				return;
			}
			//Con montos distintos de 0 (edicion o captura) la seccion se muestra siempre
			if ($pagos.hasClass('pm-collapsed') && hayValorEnPagosExtra()) {
				$pagos.removeClass('pm-collapsed');
			}
			var colapsado 	= $pagos.hasClass('pm-collapsed');
			var texto 		= colapsado ? 'Más formas de pago' : 'Ocultar formas de pago';
			if ($btnMasPagos.text() !== texto) {
				$btnMasPagos.text(texto);
				$btnMasPagos.attr('aria-expanded', colapsado ? 'false' : 'true');
			}
			if ($btnMasPagos.hasClass('hidden')) {
				$btnMasPagos.removeClass('hidden');
			}
		}

		$btnMasPagos.on('click', function (e) {
			e.preventDefault();
			if ($pagos.hasClass('pm-collapsed')) {
				$pagos.removeClass('pm-collapsed');
			} else if (!hayValorEnPagosExtra()) {
				$pagos.addClass('pm-collapsed');
			}
			actualizarMasPagos();
		});

		/* 6.4 Medidas: top sticky, alto de la tabla, alto de la barra de cobro (tablet) */
		function cobroVacio() {
			//Las personalizaciones de restaurante mueven resumen/pago/botoneras a las sidebars
			return $aside.find('#divPanelShowSummaryNumber, #divPaymentOption, [id^="rowBotoneraFacturaFila"]').length === 0;
		}

		function medir() {
			if (!hayLayout) {
				return;
			}
			var top = altoNavbarFijo();
			fijarVariableCss('--pm-top', top + 'px');

			var altoCaptura = $('#pmBarraCaptura').outerHeight() || 0;
			var altoCobro 	= 0;
			if (esTablet() && !tieneClaseHtml('pm-sin-cobro')) {
				if (!hojaAbierta() && $aside.is(':visible')) {
					altoCobro = Math.round($aside.outerHeight() || 0);
					if (altoCobro > 0) {
						fijarVariableCss('--pm-cobro-alto', altoCobro + 'px');
					}
				} else {
					altoCobro = leerVariableCssPx('--pm-cobro-alto');
				}
			}

			var $titulo 	= $('#labelTitleDetalle');
			var altoTitulo 	= $titulo.is(':visible') ? ($titulo.outerHeight(true) || 0) : 0;
			var maximo 		= Math.max(240, Math.round(window.innerHeight - top - altoCaptura - altoTitulo - altoCobro - 24));
			fijarVariableCss('--pm-tabla-max', maximo + 'px');
		}

		/* 6.5 Refresco general */
		var $monto 			= $('#pmBarraMovilMonto');
		var $cambio 		= $('#pmBarraMovilCambio');
		var ultimoTotal 	= null;
		var ultimoCambio 	= null;

		function leerTotal() {
			var valor = '';
			var $total = $('#txtTotal');
			if ($total.length > 0) {
				valor = $.trim(String($total.val() || ''));
			}
			if (valor === '') {
				var $alternativo = $('#txtTotalAlternativo');
				if ($alternativo.length > 0) {
					valor = $.trim($alternativo.text());
				}
			}
			return valor === '' ? '0.00' : valor;
		}

		function leerCambio() {
			var valor = $.trim(String($('#txtChangeAmount').val() || ''));
			return valor === '' ? '0.00' : valor;
		}

		var refrescando = false;
		function refrescar() {
			if (refrescando) {
				return;
			}
			refrescando = true;
			try {
				var superpuesto = hayPanelSuperpuesto();
				alternarClaseHtml('pm-superpuesto', superpuesto);

				if (hayLayout) {
					var sinCobro = cobroVacio();
					alternarClaseHtml('pm-sin-cobro', sinCobro);
					if (hojaAbierta() && (sinCobro || esEscritorio())) {
						cerrarHoja();
					}
					if (flotanteAbierto() && (sinCobro || superpuesto || !esEscritorio())) {
						cerrarFlotante();
					}
					var totalFlotante = leerTotal();
					if (totalFlotante !== ultimoTotalFlotante) {
						ultimoTotalFlotante = totalFlotante;
						$flotanteMonto.text(totalFlotante);
					}
				}

				if ($barra.length > 0) {
					var total = leerTotal();
					if (total !== ultimoTotal) {
						ultimoTotal = total;
						$monto.text(total);
					}
					var cambio = leerCambio();
					if (cambio !== ultimoCambio) {
						ultimoCambio = cambio;
						$cambio.text(cambio);
					}
					if ($barra.hasClass('pm-barra-oculta') !== superpuesto) {
						$barra.toggleClass('pm-barra-oculta', superpuesto);
					}
				}

				if (hayLayout) {
					actualizarMasPagos();
					medir();
				}
			} finally {
				refrescando = false;
			}
		}

		$(document).on('change input', '#txtTotal, #txtChangeAmount', refrescar);
		$(document).on('input change', '#pmCobroPagos tr.pm-pago-extra input', actualizarMasPagos);

		if (typeof window.MutationObserver === 'function') {
			var alternativo = document.getElementById('txtTotalAlternativo');
			if (alternativo) {
				new window.MutationObserver(refrescar).observe(alternativo, { childList: true, characterData: true, subtree: true });
			}
			//Apertura/cierre de sidebars (style width) y de modales Bootstrap (body.modal-open): reaccion inmediata
			var observadorPaneles = new window.MutationObserver(refrescar);
			$('.sidebar').each(function () {
				observadorPaneles.observe(this, { attributes: true, attributeFilter: ['style', 'class'] });
			});
			if (document.body) {
				observadorPaneles.observe(document.body, { attributes: true, attributeFilter: ['class'] });
			}
		}

		var redimensionPendiente = false;
		$(window).on('resize orientationchange', function () {
			if (redimensionPendiente) {
				return;
			}
			redimensionPendiente = true;
			window.setTimeout(function () {
				redimensionPendiente = false;
				refrescar();
			}, 120);
		});
		$(window).on('load', refrescar);

		window.setInterval(function () {
			if (document.hidden) {
				return;
			}
			refrescar();
		}, 500);

		refrescar();
		//Despues del ready de news_script.php y de las personalizaciones (divTraslateElement)
		window.setTimeout(refrescar, 0);

		/* 6.6 Boton de la barra movil: COBRAR abre la hoja; sin panel de cobro, comportamiento anterior */
		$('#pmBarraMovilRegistrar').on('click', function (e) {
			e.preventDefault();
			if (hayLayout && !tieneClaseHtml('pm-sin-cobro')) {
				abrirHoja();
				refrescar();
				return;
			}
			var $opcionPago = $('#btnOptionPago');
			if ($opcionPago.length > 0 && $opcionPago.is(':visible')) {
				$opcionPago.trigger('click');
				return;
			}
			var $btnAcept = $('#btnAcept');
			if ($btnAcept.length > 0 && $btnAcept.is(':visible')) {
				$btnAcept.trigger('click');
				return;
			}
			var $destino = $('#divPaymentOption');
			if ($destino.length === 0 || !$destino.is(':visible')) {
				$destino = $('#divPanelShowSummaryNumber');
			}
			if ($destino.length > 0 && $destino.is(':visible')) {
				$('html, body').animate({ scrollTop: Math.max(0, $destino.offset().top - 70) }, 300);
			}
		});

		/* 6.7 Auto-scroll a la fila agregada (sin mover el foco: el escaner lo conserva) */
		function altoInferiorFijo() {
			if (esTelefono() && $barra.length > 0 && $barra.is(':visible')) {
				return $barra.outerHeight() || 0;
			}
			if (esTablet() && hayLayout && !hojaAbierta() && $aside.is(':visible')) {
				return $aside.outerHeight() || 0;
			}
			return 0;
		}

		function desplazarAFila(fila) {
			if (!fila || !fila.parentNode || !htmlEl.contains(fila)) {
				return;
			}
			var rFila = fila.getBoundingClientRect();
			if (rFila.height === 0) {
				return;
			}

			//1) Dentro del contenedor con scroll propio (.pm-table-wrap)
			var contenedor = $(fila).closest('.pm-table-wrap')[0];
			if (contenedor && window.getComputedStyle) {
				var estilo 		= window.getComputedStyle(contenedor);
				var desplazable = /(auto|scroll)/.test(estilo.overflowY) && contenedor.scrollHeight > contenedor.clientHeight + 1;
				if (desplazable) {
					var rCont 		= contenedor.getBoundingClientRect();
					var thead 		= contenedor.querySelector('thead');
					var altoThead 	= (thead && thead.offsetHeight) || 0;
					var arriba 		= rCont.top + altoThead;
					if (rFila.top < arriba) {
						contenedor.scrollTop -= Math.ceil(arriba - rFila.top) + 4;
					} else if (rFila.bottom > rCont.bottom) {
						contenedor.scrollTop += Math.ceil(rFila.bottom - rCont.bottom) + 4;
					}
					rFila = fila.getBoundingClientRect();
				}
			}

			//2) Pagina: solo si la tabla esta en la pagina (no dentro de una sidebar fija)
			if ($(fila).closest('.sidebar').length > 0) {
				return;
			}
			var limiteArriba 	= leerVariableCssPx('--pm-top') + ($('#pmBarraCaptura').outerHeight() || 0);
			var limiteAbajo 	= window.innerHeight - altoInferiorFijo();
			if (rFila.bottom > limiteAbajo) {
				window.scrollBy(0, Math.ceil(rFila.bottom - limiteAbajo) + 8);
			} else if (rFila.top < limiteArriba) {
				window.scrollBy(0, Math.floor(rFila.top - limiteArriba) - 8);
			}
		}

		var cuerpoDetalle 			= document.getElementById('body_tb_transaction_master_detail');
		var ultimoDesplazamientoExplicito 	= 0;

		/*
		 * Llamada explicita desde news_script.php (onCompleteNewItem):
		 *   reconstruida=true  -> producto nuevo insertado al inicio (la tabla se reconstruyo): ir a la fila 0
		 *   reconstruida=false -> producto existente cuya cantidad se sumo: ir a esa fila
		 * Nunca mueve el foco (el escaner lo conserva).
		 */
		window.pmDesplazarFilaDetalle = function (indice, reconstruida) {
			if (!cuerpoDetalle) {
				return;
			}
			ultimoDesplazamientoExplicito = new Date().getTime();
			var pos = parseInt(indice, 10);
			if (isNaN(pos) || pos < 0) {
				return;
			}
			window.setTimeout(function () {
				var filas 	= cuerpoDetalle.getElementsByTagName('tr');
				var fila 	= filas[pos];
				if (!fila) {
					return;
				}
				if (reconstruida && pos === 0) {
					//Contenedor con scroll propio al inicio (tablet/escritorio); en telefono la pagina se ajusta con desplazarAFila
					var contenedor = $(fila).closest('.pm-table-wrap')[0];
					if (contenedor && contenedor.scrollTop > 0) {
						contenedor.scrollTop = 0;
					}
				}
				desplazarAFila(fila);
			}, 30);
		};

		if (cuerpoDetalle && typeof window.MutationObserver === 'function') {
			//DataTables reutiliza los mismos <tr> al redibujar: se marca cada fila ya vista (propiedad JS, no atributo)
			var filasIniciales = cuerpoDetalle.getElementsByTagName('tr');
			for (var f = 0; f < filasIniciales.length; f++) {
				filasIniciales[f].pmFilaVista = true;
			}
			new window.MutationObserver(function (mutaciones) {
				var nuevas = [];
				for (var m = 0; m < mutaciones.length; m++) {
					var agregados = mutaciones[m].addedNodes || [];
					for (var n = 0; n < agregados.length; n++) {
						var nodo = agregados[n];
						if (!nodo || nodo.nodeType !== 1 || nodo.tagName !== 'TR' || nodo.pmFilaVista) {
							continue;
						}
						nodo.pmFilaVista = true;
						if ($(nodo).children('td.dataTables_empty').length === 0) {
							nuevas.push(nodo);
						}
					}
				}
				//Reconstruccion por producto nuevo al inicio: ya la atiende pmDesplazarFilaDetalle
				if (new Date().getTime() - ultimoDesplazamientoExplicito < 250) {
					return;
				}
				//Carga masiva (edicion): no se desplaza
				if (nuevas.length === 0 || nuevas.length > 3) {
					return;
				}
				var filaNueva = nuevas[nuevas.length - 1];
				window.setTimeout(function () {
					desplazarAFila(filaNueva);
				}, 30);
			}).observe(cuerpoDetalle, { childList: true });
		}
	});

	/* ------------------------------------------------------------------
	 * 7. Panel flotante izquierdo "Ayuda / Herramientas" (#pmPanelAux)
	 *    Contiene #panelResumenFacturaTool y #panelResumenFactura (ids y clases
	 *    de getBehavio intactos; el handler delegado de #txtToolCalcular sigue igual).
	 *    - Puntero fino con hover: lo abre/cierra el CSS (:hover).
	 *    - Tactil / sin hover: toque en la pestana alterna la clase pm-aux-abierto;
	 *      toque fuera o Esc lo cierran.
	 *    - Si ambos bloques estan ocultos (.hidden) o fueron movidos por una
	 *      personalizacion, el panel completo (con su pestana) recibe .hidden.
	 * ------------------------------------------------------------------ */
	$(function () {
		var $panelAux 	= $('#pmPanelAux');
		var $asa 		= $('#pmPanelAuxAsa');
		var $cuerpoAux 	= $('#pmPanelAuxCuerpo');
		if ($panelAux.length === 0 || $asa.length === 0) {
			return;
		}

		function conHover() {
			return media('(hover:hover) and (pointer:fine)');
		}

		function abierto() {
			return $panelAux.hasClass('pm-aux-abierto');
		}

		function fijarAbierto(valor) {
			if (valor === abierto()) {
				return;
			}
			$panelAux.toggleClass('pm-aux-abierto', valor);
			$asa.attr('aria-expanded', valor ? 'true' : 'false');
		}

		function actualizarVisibilidadAux() {
			var hayContenido = $cuerpoAux.children('#panelResumenFacturaTool, #panelResumenFactura').filter(function () {
				return !$(this).hasClass('hidden');
			}).length > 0;
			if ($panelAux.hasClass('hidden') === hayContenido) {
				$panelAux.toggleClass('hidden', !hayContenido);
			}
			if (!hayContenido) {
				fijarAbierto(false);
			}
		}

		$asa.on('click', function (e) {
			e.preventDefault();
			if (conHover()) {
				//Con mouse lo maneja :hover; el clic no deja el panel "pegado" abierto
				return;
			}
			fijarAbierto(!abierto());
		});

		$panelAux.on('mouseleave', function () {
			if (conHover()) {
				fijarAbierto(false);
			}
		});

		$(document).on('click touchstart', function (e) {
			if (!abierto()) {
				return;
			}
			if ($panelAux[0] === e.target || $.contains($panelAux[0], e.target)) {
				return;
			}
			fijarAbierto(false);
		});

		$(document).on('keydown', function (e) {
			if ((e.key === 'Escape' || e.keyCode === 27) && abierto()) {
				fijarAbierto(false);
				//Si el foco quedo dentro, el CSS (:has(:focus-visible)) lo mantendria abierto
				var activo = document.activeElement;
				if (activo && activo !== document.body && $.contains($panelAux[0], activo) && typeof activo.blur === 'function') {
					activo.blur();
				}
			}
		});

		actualizarVisibilidadAux();
		//Despues del ready de news_script.php y de las personalizaciones (divTraslateElement)
		window.setTimeout(actualizarVisibilidadAux, 0);
		$(window).on('load', actualizarVisibilidadAux);
	});

	/* ------------------------------------------------------------------
	 * 8. Sin autocompletado del navegador en los campos de la pantalla
	 *    autocomplete="off" en form#form-new-invoice (tambien en news_body.php)
	 *    y en cada input/textarea: los existentes al cargar y los que se
	 *    agregan despues (filas del detalle, sidebars, modales) al recibir
	 *    el foco, antes de que el navegador muestre sugerencias.
	 * ------------------------------------------------------------------ */
	var SELECTOR_SIN_AUTOCOMPLETAR = 'input:not([type="hidden"]):not([type="checkbox"]):not([type="radio"]):not([type="button"]):not([type="submit"]), textarea';

	function quitarAutocompletado() {
		$('#form-new-invoice').attr('autocomplete', 'off');
		$(SELECTOR_SIN_AUTOCOMPLETAR).attr('autocomplete', 'off');
	}

	$(function () {
		quitarAutocompletado();
		//Despues del ready de news_script.php y de las personalizaciones
		window.setTimeout(quitarAutocompletado, 0);
	});

	$(document).on('focusin', SELECTOR_SIN_AUTOCOMPLETAR, function () {
		if (this.getAttribute('autocomplete') !== 'off') {
			this.setAttribute('autocomplete', 'off');
		}
	});

})(window.jQuery, window, document);
