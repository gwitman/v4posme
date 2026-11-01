/**
 * posme - Grillas (core_web_view::renderGreed, renderGreedMobile, renderGreedWithHtmlInFild,
 *         renderGreedWithHtmlInFildMobile): capa visual moderna y responsive.
 * Feature: rediseno-grid
 *
 * Solo agrega atributos/clases; no toca datos, columnas, ids ni los eventos de la grilla
 * (objTable<id>, objRowTable<id>, fnGetData, fnUpdate, clic y doble clic siguen igual).
 *
 *   1. .pm-grid-wrapper en el .dataTables_wrapper de cada table.pm-grid (estilo de filtro,
 *      cantidad de elementos y paginacion).
 *   2. data-label en cada <td> con el texto del encabezado visible: en telefono (<= 767px)
 *      cada fila se muestra como tarjeta "ETIQUETA  valor" (posme-grid.css).
 *
 * Se incluye junto a cada grilla (tambien en HTML traido por AJAX): se inicializa una sola vez
 * y las grillas nuevas se preparan con el evento "draw" de DataTables 1.9 (burbujea al document).
 */
(function (window, document, $) {
	'use strict';

	if (!$) {
		return;
	}
	//Ya cargado: solo preparar las grillas nuevas
	if (window.pmGrid) {
		$(function () {
			window.setTimeout(window.pmGrid.refrescar, 0);
		});
		return;
	}

	function etiquetar(tabla) {
		var $tabla = $(tabla);
		var titulos = [];
		$tabla.children('thead').children('tr').first().children('th').each(function () {
			titulos.push($.trim($(this).text()));
		});
		if (titulos.length === 0) {
			return;
		}
		$tabla.children('tbody').children('tr').each(function () {
			$(this).children('td').each(function (i) {
				if ((' ' + this.className + ' ').indexOf(' dataTables_empty ') >= 0) {
					return;
				}
				var titulo = titulos[i] || '';
				if (this.getAttribute('data-label') !== titulo) {
					this.setAttribute('data-label', titulo);
				}
			});
		});
	}

	function preparar(tabla) {
		var $envoltura = $(tabla).closest('.dataTables_wrapper');
		if ($envoltura.length > 0 && !$envoltura.hasClass('pm-grid-wrapper')) {
			$envoltura.addClass('pm-grid-wrapper');
		}
		etiquetar(tabla);
	}

	function refrescar() {
		$('table.pm-grid').each(function () {
			preparar(this);
		});
	}

	window.pmGrid = { refrescar: refrescar, etiquetar: etiquetar };

	//DataTables 1.9 dispara "draw" al iniciar, paginar, filtrar, ordenar y ocultar columnas
	$(document).on('draw', 'table.pm-grid', function (e) {
		if (e.target === this) {
			preparar(this);
		}
	});

	//Respaldo: las grillas se crean en su propio $(document).ready, despues de este archivo
	$(function () {
		window.setTimeout(refrescar, 0);
	});
	$(window).on('load', refrescar);

})(window, document, window.jQuery);
