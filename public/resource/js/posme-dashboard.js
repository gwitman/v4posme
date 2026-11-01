/* ==========================================================================
 * posme-dashboard.js
 * core_dashboards/index (app/Views/core_dasboard/dashboards_*.php)
 *   - Registra las funciones draw* que cada vista pasa a google.charts.setOnLoadCallback
 *     y las vuelve a llamar cuando cambia el ANCHO de la ventana (debounce 250ms),
 *     para que los graficos usen el 100% del panel al girar el telefono / cambiar tamano.
 *   - Evita que a.minimize / a.maximize (href="#") suban la pantalla y redibuja al abrir el panel.
 * Debe cargarse despues de chart-google/loader.js y antes de $(document).ready de la vista.
 * ========================================================================== */
(function (w, $) {
	if (w.posmeDashboard) return;	//incluido dos veces

	var RETARDO		= 250;
	var callbacks	= [];
	var timer		= null;
	var anchoAnterior = $ ? $(w).width() : w.innerWidth;

	function redibujar() {
		if (!(w.google && w.google.visualization)) return;
		for (var i = 0; i < callbacks.length; i++) {
			try { callbacks[i](); } catch (e) { /* un grafico con error no detiene a los demas */ }
		}
	}
	function programar() {
		clearTimeout(timer);
		timer = setTimeout(redibujar, RETARDO);
	}

	//Interceptar setOnLoadCallback para conocer las funciones de dibujo
	if (w.google && w.google.charts && typeof w.google.charts.setOnLoadCallback === "function" && !w.google.charts.__pmDashboard) {
		var original = w.google.charts.setOnLoadCallback;
		w.google.charts.setOnLoadCallback = function (fn) {
			if (typeof fn === "function" && callbacks.indexOf(fn) === -1) callbacks.push(fn);
			return original.apply(this, arguments);
		};
		w.google.charts.__pmDashboard = true;
	}

	if ($) {
		//Solo cuando cambia el ancho (la barra del navegador del telefono cambia el alto)
		$(w).on("resize orientationchange", function () {
			var ancho = $(w).width();
			if (ancho === anchoAnterior) return;
			anchoAnterior = ancho;
			programar();
		});

		$(document).on("click", ".pm-dash .panel-heading a.minimize, .pm-dash .panel-heading a.maximize", function (e) {
			e.preventDefault();
			setTimeout(programar, 260);	//despues del slideDown de genyx
		});
	}

	w.posmeDashboard = { redibujar: redibujar, programar: programar, callbacks: callbacks };
})(window, window.jQuery);
