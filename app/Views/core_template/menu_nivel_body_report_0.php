<?php
	/*
	 * Tarjeta de un reporte en las paginas indice (app_xxxxx_report/index).
	 * Variables: $icon, $address, $display, $description (core_web_menu::render_item_body_report).
	 * Estilo y comportamiento: helper_getHtmlOfStylePageListReportCSS / helper_getHtmlOfStylePageListReportJS.
	 */
	$tituloTexto = trim(strip_tags($display));
	$inicial     = function_exists('mb_substr') ? mb_strtoupper(mb_substr($tituloTexto, 0, 1, 'UTF-8'), 'UTF-8') : strtoupper(substr($tituloTexto, 0, 1));
?>
<div class="reporte-item prl-card" data-url="<?php echo $address; ?>">
	<div class="prl-card-head">
		<span class="prl-card-icon" aria-hidden="true"><?php echo htmlspecialchars($inicial, ENT_QUOTES, 'UTF-8'); ?></span>
		<h3 class="prl-card-title" title="<?php echo htmlspecialchars($tituloTexto, ENT_QUOTES, 'UTF-8'); ?>"><?php echo $display; ?></h3>
	</div>

	<div class="prl-card-desc"><?php echo $description; ?></div>
	<button type="button" class="prl-card-more" aria-expanded="false" hidden>Ver más</button>

	<div class="prl-card-foot">
		<a class="prl-card-open" href="<?php echo $address; ?>" target="_blank" rel="noopener">Abrir reporte <span aria-hidden="true">&#8599;</span></a>
	</div>
</div>
