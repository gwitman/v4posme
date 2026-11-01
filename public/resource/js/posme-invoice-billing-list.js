/**
 * posme - Lista de facturas (app_invoice_billing/index)
 * Feature: rediseno-factura-index
 *
 * Solo capa visual; no cambia la logica de list_script.php:
 *   1. window.pmListNativo: telefono/tactil/useMobile=1 -> fecha nativa (type=date) en vez del
 *      datepicker de jQuery (list_script.php solo lo inicializa si pmListNativo es false).
 *      El valor sigue siendo yyyy-mm-dd.
 *   2. data-label en cada <td> de #ListView con el texto del encabezado visible, para que en
 *      telefono (<= 767px) cada fila se muestre como tarjeta "ETIQUETA  valor".
 *   3. Seleccion de la fila completa aunque el clic caiga en un elemento interno de la celda.
 *
 * Requiere jQuery (masterpage) y la variable pmListUseMobile (list_head.php).
 */
(function (window, document, $) {
	'use strict';

	if (!$) {
		return;
	}

	function media(consulta) {
		return !!(window.matchMedia && window.matchMedia(consulta).matches);
	}

	/* 1. Controles nativos (se calcula una sola vez al cargar) */
	var useMobile 	= String(window.pmListUseMobile || '') === '1';
	var nativo 		= useMobile || media('(hover:none) and (pointer:coarse)') || media('(max-width:767px)');
	window.pmListNativo = nativo;
	if (nativo) {
		document.documentElement.className += ' pm-list-nativo';
	}

	function fechaNativa() {
		if (!nativo) {
			return;
		}
		var input = document.getElementById('txtStartOn');
		if (!input || input.type === 'date') {
			return;
		}
		var valor = String(input.value || '');
		try {
			input.type = 'date';
		} catch (e) {
			return;		//navegador sin soporte: queda como texto yyyy-mm-dd
		}
		if (input.type === 'date') {
			input.value = /^\d{4}-\d{2}-\d{2}/.test(valor) ? valor.substring(0, 10) : '';
		}
	}

	/* 2. Etiquetas de las celdas (tarjetas en telefono) */
	function etiquetar() {
		var $tabla = $('#ListView');
		if ($tabla.length === 0) {
			return;
		}
		var titulos = [];
		$tabla.find('> thead > tr:first > th').each(function () {
			titulos.push($.trim($(this).text()));
		});
		if (titulos.length === 0) {
			return;
		}
		$tabla.find('> tbody > tr').each(function () {
			$(this).children('td').each(function (i) {
				if ($(this).hasClass('dataTables_empty')) {
					return;
				}
				var titulo = titulos[i] || '';
				if (this.getAttribute('data-label') !== titulo) {
					this.setAttribute('data-label', titulo);
				}
			});
		});
	}

	$(function () {
		fechaNativa();
		etiquetar();

		//DataTables 1.9 dispara "draw" al paginar, filtrar u ordenar
		$('#ListView').on('draw', etiquetar);
		$(window).on('load', etiquetar);
		window.setTimeout(etiquetar, 0);

		/* 3. La fila completa queda seleccionada (fnTableSelectedRow usa event.target.parentNode) */
		$(document).on('click', '#ListView > tbody > tr', function () {
			var $fila = $(this);
			if ($fila.find('td.dataTables_empty').length > 0) {
				return;
			}
			$fila.siblings('.row-selected').removeClass('row-selected');
			$fila.find('.row-selected').removeClass('row-selected');
			$fila.addClass('row-selected');
		});
	});

})(window, document, window.jQuery);
