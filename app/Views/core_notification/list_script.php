<script>
	/*
	 * Notificaciones: filtros por tipo, buscador, seleccion y "marcar como leida".
	 * Por cada notificacion se llama a core_notification/save (POST errorID); al terminar
	 * todas las llamadas se muestra un solo aviso y se quitan de la pantalla las marcadas.
	 */
	$(document).ready(function () {
		var $app = $("#pntApp");
		if ($app.length === 0) {
			return;
		}

		var filtroActual = "todas";
		var acentos      = new RegExp("[" + String.fromCharCode(0x300) + "-" + String.fromCharCode(0x36F) + "]", "g");

		// Casillas nativas: quitar el estilo uniform que el tema aplica a todos los checkbox
		if ($.uniform && $.uniform.restore) {
			$.uniform.restore($app.find("input[type=checkbox]"));
		}

		// Texto en minusculas y sin acentos para el buscador
		function normalizar(texto) {
			texto = (texto || "").toString().toLowerCase();
			if (texto.normalize) {
				texto = texto.normalize("NFD").replace(acentos, "");
			}
			return texto.replace(/\s+/g, " ").trim();
		}

		$app.find(".pnt-item").each(function () {
			$(this).attr("data-buscar", normalizar($(this).find(".pnt-item-body").text()));
		});

		// Recalcula contadores, visibilidad de grupos y mensaje de vacio
		function actualizarVista() {
			var palabras = normalizar($("#pntBuscar").val()).split(" ").filter(function (p) { return p !== ""; });
			var total    = 0;
			var visibles = 0;

			$app.find(".pnt-grupo").each(function () {
				var $grupo    = $(this);
				var clave     = $grupo.data("grupo");
				var $items    = $grupo.find(".pnt-item");
				var cantidad  = $items.length;
				var enGrupo   = 0;

				total += cantidad;

				$items.each(function () {
					var texto    = $(this).attr("data-buscar");
					var coincide = palabras.every(function (p) { return texto.indexOf(p) !== -1; });
					$(this).prop("hidden", !coincide);
					if (coincide) {
						enGrupo++;
					}
				});

				var mostrarGrupo = enGrupo > 0 && (filtroActual === "todas" || filtroActual === clave);
				$grupo.prop("hidden", !mostrarGrupo);
				$grupo.find(".pnt-grupo-num").text(cantidad);
				if (mostrarGrupo) {
					visibles += enGrupo;
				}

				var $filtro = $app.find(".pnt-filtro[data-filtro='" + clave + "']");
				$filtro.find(".pnt-filtro-num").text(cantidad);
				$filtro.toggleClass("is-empty", cantidad === 0);

				// "Todas" del grupo se desmarca si ya no estan todas seleccionadas
				var $checks = $items.filter(":not([hidden])").find("input.checkIt");
				$grupo.find(".pnt-check-todo-input").prop("checked", $checks.length > 0 && $checks.filter(":checked").length === $checks.length);
			});

			$("#pntTotal, #pntNumTodas").text(total);
			$("#pntContador").text(palabras.length || filtroActual !== "todas" ? ("Mostrando " + visibles + " de " + total) : (total + " pendientes"));
			$("#pntBtnTodas").prop("disabled", visibles === 0);
			$app.find(".pnt-mbar").prop("hidden", total === 0);
			$("#pntVacioTexto").text(total === 0 ? "Estás al día: no tienes notificaciones pendientes." : "No hay notificaciones que coincidan con el filtro o la búsqueda.");
			$("#pntVacio").prop("hidden", visibles !== 0);
		}

		// Envia cada errorID a core_notification/save; al terminar quita las marcadas y avisa una sola vez
		function marcarComoLeidas($items) {
			if ($items.length === 0) {
				fnShowNotification("Selecciona al menos una notificación", "error");
				return;
			}

			fnWaitOpen();
			var correctas = 0;
			var fallidas  = 0;
			var llamadas  = [];

			$items.each(function () {
				var $item   = $(this);
				var errorID = $item.find("input.checkIt").data("errorid");

				llamadas.push(
					$.ajax({
						cache    : false,
						dataType : "json",
						type     : "POST",
						url      : "<?php echo base_url(); ?>/core_notification/save",
						data     : { errorID: errorID }
					}).then(function (data) {
						if (data && data.error === false) {
							correctas++;
							$item.addClass("is-saliendo");
						}
						else {
							fallidas++;
						}
					}, function () {
						fallidas++;
						return $.Deferred().resolve();
					})
				);
			});

			$.when.apply($, llamadas).always(function () {
				setTimeout(function () {
					$app.find(".pnt-item.is-saliendo").remove();
					actualizarVista();
					fnWaitClose();

					if (fallidas === 0) {
						fnShowNotification(correctas === 1 ? "1 notificación marcada como leída" : (correctas + " notificaciones marcadas como leídas"), "success");
					}
					else {
						fnShowNotification(correctas + " de " + (correctas + fallidas) + " se marcaron como leídas; " + fallidas + (fallidas === 1 ? " no se pudo marcar" : " no se pudieron marcar"), "error");
					}
				}, 250);
			});
		}

		// Filtros por tipo
		$app.on("click", ".pnt-filtro", function () {
			filtroActual = $(this).data("filtro");
			$app.find(".pnt-filtro").removeClass("is-active").attr("aria-selected", "false");
			$(this).addClass("is-active").attr("aria-selected", "true");
			actualizarVista();
		});

		// Buscador (Escape limpia)
		$("#pntBuscar").on("input", actualizarVista).on("keydown", function (e) {
			if (e.key === "Escape") {
				$(this).val("");
				actualizarVista();
			}
		});

		// Seleccion individual: resalta la fila
		$app.on("change", "input.checkIt", function () {
			$(this).closest(".pnt-item").toggleClass("is-selected", this.checked);
			actualizarVista();
		});

		// "Todas" de un grupo: solo las visibles de ese grupo
		$app.on("change", ".pnt-check-todo-input", function () {
			var marcado = this.checked;
			$(this).closest(".pnt-grupo").find(".pnt-item:not([hidden]) input.checkIt").each(function () {
				this.checked = marcado;
				$(this).closest(".pnt-item").toggleClass("is-selected", marcado);
			});
		});

		// Marcar leidas del grupo: solo las seleccionadas de ese grupo
		$app.on("click", ".pnt-btn-grupo", function () {
			var $grupo         = $(this).closest(".pnt-grupo");
			var $seleccionadas = $grupo.find(".pnt-item:not([hidden])").filter(function () { return $(this).find("input.checkIt").prop("checked"); });
			marcarComoLeidas($seleccionadas);
		});

		// Marcar una sola
		$app.on("click", ".pnt-item-leer", function () {
			marcarComoLeidas($(this).closest(".pnt-item"));
		});

		// Marcar seleccionadas de toda la pantalla
		$("#pntBtnSeleccionadas, #pntBtnSeleccionadasMovil").on("click", function () {
			marcarComoLeidas($app.find(".pnt-grupo:not([hidden]) .pnt-item:not([hidden])").filter(function () { return $(this).find("input.checkIt").prop("checked"); }));
		});

		// Marcar todas las visibles (con confirmacion del sistema)
		$("#pntBtnTodas").on("click", function () {
			var $visibles = $app.find(".pnt-grupo:not([hidden]) .pnt-item:not([hidden])");
			if ($visibles.length === 0) {
				return;
			}
			fnShowConfirm("Confirmar..", "¿Marcar " + $visibles.length + " notificaciones como leídas?", function () {
				marcarComoLeidas($visibles);
			});
		});

		actualizarVista();
	});
</script>
