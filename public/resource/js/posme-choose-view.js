/**
 * posme-choose-view.js
 * Estilo moderno / responsive de la ventana de seleccion paginada (core_view/showviewbynamepaginate).
 * Lo carga core_view/choose_view_serch_head.php solo cuando $estiloModerno == "1".
 *
 * Solo agrega clases y atributos (data-label, placeholder). NO reemplaza ni mueve nodos y NO toca
 * los handlers de core_web_view::renderGreedPaginate:
 *   click/dblclick en #ListView tr, .td_interno, teclas en #ListView_filter > label > input,
 *   .btnSearchServer, .btnPaginateServer, #btnPopupAceptar, #btnPopupCancelar.
 *
 *   html.pm-choose              activa todos los estilos de posme-choose-view.css
 *   .pm-choose-barra            fila (.row) que contiene #email (barra de botones, sticky)
 *   td[data-label]              nombre de la columna visible (etiqueta de la tarjeta en telefono)
 *   tr.pm-choose-total          fila de totales (-TOTAL-) de renderGreedPaginate
 */
(function (window, document, $) {
	'use strict';

	var ID_TABLA = 'ListView';

	//Antes del primer pintado: evita el "salto" de estilos
	document.documentElement.className += ' pm-choose';

	if (!$) {
		return;
	}

	/* Textos de los encabezados visibles (fnSetColumnVis quita del DOM las columnas ocultas) */
	function encabezados(tabla) {
		var lista = [];
		$(tabla).children('thead').find('tr').first().children('th').each(function () {
			lista.push($.trim($(this).text()));
		});
		return lista;
	}

	/* Todas las filas: las de la pagina actual y las que DataTables tiene fuera del DOM */
	function filas(tabla) {
		var obj = window['objTable' + ID_TABLA];
		if (obj && typeof obj.fnGetNodes === 'function') {
			return obj.fnGetNodes();
		}
		return $(tabla).children('tbody').children('tr').get();
	}

	function etiquetar() {
		var tabla = document.getElementById(ID_TABLA);
		if (!tabla) {
			return;
		}
		var titulos = encabezados(tabla);
		$.each(filas(tabla), function (_, fila) {
			var esTotal = false;
			$(fila).children('td').each(function (i) {
				if ($(this).hasClass('dataTables_empty')) {
					return;
				}
				this.setAttribute('data-label', titulos[i] || '');
				if ($.trim($(this).text()) === '-TOTAL-') {
					esTotal = true;
				}
			});
			$(fila).toggleClass('pm-choose-total', esTotal);
		});
	}

	function iniciar() {
		$('#email').parent('.row').addClass('pm-choose-barra');
		$('#' + ID_TABLA + '_filter').find('input').attr('placeholder', 'Buscar...');

		etiquetar();
		//DataTables 1.9 dispara "draw" al filtrar / ordenar / paginar en el cliente
		$('#' + ID_TABLA).on('draw', etiquetar);
	}

	//Despues del ready de renderGreedPaginate (inicializa el dataTable y oculta columnas)
	$(function () {
		window.setTimeout(iniciar, 0);
	});

})(window, document, window.jQuery);
