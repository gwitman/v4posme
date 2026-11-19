// Pantalla de espera moderna (fnWaitOpen / $.isLoading). Estilos en resource/css/posme-loading.css
var isLoadingConfig  = {
	text		: "Espere un momento",
	position	: "overlay",
	tpl			:
		'<div class="isloading-wrapper pm-loading-card %wrapper%" role="status" aria-live="polite">' +
			'<div class="pm-loading-head">' +
				'<div class="pm-loading-spinner">' +
					'<span class="pm-loading-ring"></span>' +
					'<span class="pm-loading-ring pm-loading-ring2"></span>' +
				'</div>' +
			'</div>' +
			'<div class="pm-loading-body">' +
				'<div class="pm-loading-title">%text%<span class="pm-loading-dots"><span>.</span><span>.</span><span>.</span></span></div>' +
				'<div class="pm-loading-sub">Procesando su solicitud</div>' +
			'</div>' +
			'<div class="pm-loading-bar"><i></i></div>' +
		'</div>'
};

/**
 * Bloquea el botón "Atrás" del navegador.
 * Se agrega una entrada "guarda" al historial; cuando el usuario presiona Atrás
 * (evento popstate) se vuelve a agregar, de modo que la página no cambia.
 * Chrome ignora entradas creadas sin interacción del usuario, por eso la guarda
 * también se renueva en el primer clic/tecla/toque después de cada intento.
 * Para permitir Atrás en una pantalla puntual: window.posmeAllowBack = true; antes de cargar este archivo.
 */
(function () {
	if (window.posmeAllowBack === true)				return;
	if (!window.history || !window.history.pushState)	return;
	if (window.top !== window.self)						return; // no aplicar dentro de iframes

	var necesitaGuarda = true;

	function fnPonerGuarda() {
		history.pushState({ pmNoBack: true }, document.title, location.href);
		necesitaGuarda = false;
	}

	// Al presionar Atrás: volver a poner la guarda y quedarse en la página
	window.addEventListener("popstate", function () {
		history.pushState({ pmNoBack: true }, document.title, location.href);
		necesitaGuarda = true;
	});

	// Renovar la guarda con interacción real del usuario
	function fnInteraccion() {
		if (necesitaGuarda) fnPonerGuarda();
	}
	document.addEventListener("pointerdown",	fnInteraccion, true);
	document.addEventListener("keydown",		fnInteraccion, true);
	document.addEventListener("touchstart",		fnInteraccion, true);

	fnPonerGuarda();
	necesitaGuarda = true; // la primera guarda pudo crearse sin interacción
})();

$(document).ready(function(){
	$(".select2").select2({
	    placeholder: "seleccionar..."
	});
});
