<?php
	/*
	 * Notificaciones pendientes del usuario (tb_error sin leer), agrupadas por tipo.
	 * Estilo de la pantalla de ABONOS (pv2). El marcado como leido lo maneja list_script.php
	 * llamando a core_notification/save por cada errorID.
	 */
	$grupos = array(
		array("clave" => "obligaciones", "titulo" => "Obligaciones",        "icono" => "📌", "lista" => $objListErrorOblicaciones),
		array("clave" => "pagos",        "titulo" => "Pendientes de pago",  "icono" => "💳", "lista" => $objListErrorPagos),
		array("clave" => "visita",       "titulo" => "Próxima visita",      "icono" => "📅", "lista" => $objListErrorProximaVisita),
		array("clave" => "inventario",   "titulo" => "Inventario mínimo",   "icono" => "📦", "lista" => $objListErrorInventarioMinimo),
		array("clave" => "cambio",       "titulo" => "Tipo de cambio",      "icono" => "💱", "lista" => $objListErrorTC),
		array("clave" => "cumple",       "titulo" => "Cumpleaños",          "icono" => "🎂", "lista" => $objListErrorCumple),
		array("clave" => "vencimiento",  "titulo" => "Vencimientos",        "icono" => "⏳", "lista" => $objListErrorFechaDeVencimiento),
	);

	$totalPendientes = 0;
	foreach ($grupos as $grupo) {
		$totalPendientes += $grupo["lista"] ? count($grupo["lista"]) : 0;
	}
?>
<?php echo view('core_template/posme_v2_style'); ?>
<style>
	/* ===== Notificaciones (prefijo .pnt) ===== */
	.pnt .pv2-header-icon { font-size: 22px; }
	.pnt-toolbar { display: flex; flex-direction: column; gap: 10px; }
	.pnt-filtros { display: flex; gap: 8px; flex-wrap: wrap; }
	.pnt-filtro {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		min-height: 34px;
		padding: 0 12px;
		border: 1px solid var(--pv2-border);
		border-radius: 20px;
		background: #FFFFFF;
		color: #1F2933;
		font-size: 13px;
		font-weight: 600;
		cursor: pointer;
		white-space: nowrap;
	}
	.pnt-filtro:hover { border-color: var(--pv2-accent); }
	.pnt-filtro.is-active { background: var(--pv2-accent); border-color: var(--pv2-accent); color: #FFFFFF; }
	.pnt-filtro.is-empty { opacity: .5; }
	.pnt-filtro-num {
		min-width: 22px;
		padding: 1px 7px;
		border-radius: 20px;
		background: var(--pv2-accent-bg);
		color: var(--pv2-accent);
		font-size: 11px;
		text-align: center;
	}
	.pnt-filtro.is-active .pnt-filtro-num { background: rgba(255, 255, 255, .25); color: #FFFFFF; }
	.pnt-buscar-fila { display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
	.pnt .pnt-buscar { flex: 1 1 320px; max-width: 520px; }
	.pnt-contador {
		font-size: 12px;
		font-weight: 700;
		color: var(--pv2-accent);
		background: var(--pv2-accent-bg);
		border-radius: 20px;
		padding: 5px 12px;
		white-space: nowrap;
	}

	/* Grupos: 2 columnas en computadora */
	.pnt-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; align-items: start; }
	.pnt-grupo { margin-bottom: 0; padding: 0; overflow: hidden; }
	.pnt-grupo[hidden] { display: none; }
	.pnt-grupo-head {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 10px;
		flex-wrap: wrap;
		padding: 12px 16px;
		border-bottom: 1px solid var(--pv2-border);
	}
	.pnt-grupo-head .pv2-card-title { margin: 0; }
	.pnt-grupo-acciones { display: flex; align-items: center; gap: 10px; }
	.pnt-check-todo { display: inline-flex; align-items: center; gap: 6px; margin: 0; font-size: 12px; font-weight: 600; color: var(--pv2-muted); cursor: pointer; }
	.pnt .pnt-check-todo input,
	.pnt .pnt-item input.checkIt { width: 18px; height: 18px; margin: 0; cursor: pointer; accent-color: var(--pv2-accent); }

	/* Notificaciones */
	.pnt-lista { list-style: none; margin: 0; padding: 0; max-height: 420px; overflow-y: auto; }
	.pnt-item {
		display: flex;
		align-items: flex-start;
		gap: 10px;
		padding: 10px 16px;
		border-bottom: 1px solid var(--pv2-border);
		transition: opacity .25s ease, background .15s ease;
	}
	.pnt-item:last-child { border-bottom: 0; }
	.pnt-item:hover { background: var(--pv2-soft); }
	.pnt-item.is-selected { background: var(--pv2-accent-bg); }
	.pnt-item[hidden] { display: none; }
	.pnt-item.is-saliendo { opacity: 0; }
	.pnt-item-check { padding-top: 2px; }
	.pnt-item-body { flex: 1 1 auto; min-width: 0; }
	.pnt-item-msg { margin: 0; font-size: 13px; line-height: 1.45; color: #1F2933; word-break: break-word; }
	.pnt-item-fecha { display: block; margin-top: 3px; font-size: 11px; color: var(--pv2-muted); }
	.pnt-item-leer {
		flex: 0 0 auto;
		min-width: 34px;
		height: 34px;
		border: 1px solid var(--pv2-border);
		border-radius: 8px;
		background: #FFFFFF;
		color: var(--pv2-ok);
		font-weight: 700;
		cursor: pointer;
	}
	.pnt-item-leer:hover { background: var(--pv2-ok-bg); border-color: var(--pv2-ok); }
	.pnt-empty[hidden] { display: none; }

	/* Telefono */
	@media (max-width: 767px) {
		.pnt.pv2-has-mbar { padding-bottom: 84px; }
		.pnt-grid { grid-template-columns: 1fr; gap: 10px; }
		.pnt-filtros { flex-wrap: nowrap; overflow-x: auto; -webkit-overflow-scrolling: touch; scrollbar-width: none; }
		.pnt-filtros::-webkit-scrollbar { display: none; }
		/* Sin scroll interno en telefono: la pagina completa se desplaza */
		.pnt-lista { max-height: none; overflow-y: visible; }
		.pnt-mbar[hidden] { display: none; }
		.pnt-filtro { min-height: 40px; font-size: 14px; }
		.pnt-contador { width: 100%; text-align: center; }
		.pnt-grupo-head { padding: 10px 12px; }
		.pnt-item { padding: 12px; }
		.pnt-item-msg { font-size: 14px; }
		.pnt-item-leer { min-width: 44px; height: 44px; }
		.pnt .pnt-check-todo input,
		.pnt .pnt-item input.checkIt { width: 22px; height: 22px; }
		.pnt .pv2-header .pv2-actions .pnt-hide-xs { display: none; }
		.pnt-mbar {
			display: flex;
			position: fixed;
			left: 0;
			right: 0;
			bottom: 0;
			z-index: 50;
			gap: 8px;
			padding: 10px 12px;
			background: #FFFFFF;
			border-top: 1px solid var(--pv2-border);
			box-shadow: 0 -2px 8px rgba(16, 24, 40, .08);
		}
		.pnt-mbar .btn { flex: 1 1 auto; min-height: 44px; border-radius: 8px; font-weight: 700; }
	}
	@media (min-width: 768px) {
		.pnt-mbar { display: none; }
	}
</style>

<div class="pv2 pnt pv2-has-mbar" id="pntApp">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon" aria-hidden="true">🔔</div>
			<div>
				<p class="pv2-header-title">Notificaciones <span class="pv2-chip"><span id="pntTotal"><?php echo $totalPendientes; ?></span> pendientes</span></p>
				<p class="pv2-header-sub">Selecciona las notificaciones y márcalas como leídas</p>
			</div>
		</div>
		<div class="pv2-actions">
			<button type="button" class="btn btn-default pnt-hide-xs" id="pntBtnSeleccionadas">✓ Marcar seleccionadas</button>
			<button type="button" class="btn btn-primary" id="pntBtnTodas">Marcar todas como leídas</button>
		</div>
	</div>

	<!-- filtros y buscador -->
	<div class="pv2-card pnt-toolbar">
		<div class="pnt-filtros" role="tablist" aria-label="Filtrar por tipo">
			<button type="button" class="pnt-filtro is-active" data-filtro="todas" role="tab" aria-selected="true">Todas <span class="pnt-filtro-num" id="pntNumTodas"><?php echo $totalPendientes; ?></span></button>
			<?php foreach ($grupos as $grupo) { $cantidad = $grupo["lista"] ? count($grupo["lista"]) : 0; ?>
			<button type="button" class="pnt-filtro<?php echo $cantidad == 0 ? ' is-empty' : ''; ?>" data-filtro="<?php echo $grupo["clave"]; ?>" role="tab" aria-selected="false"><?php echo $grupo["icono"]; ?> <?php echo $grupo["titulo"]; ?> <span class="pnt-filtro-num"><?php echo $cantidad; ?></span></button>
			<?php } ?>
		</div>
		<div class="pnt-buscar-fila">
			<form class="pv2-search pnt-buscar" role="search" autocomplete="off" onsubmit="return false;">
				<input type="search" class="form-control" id="pntBuscar" placeholder="Buscar en las notificaciones…" aria-label="Buscar en las notificaciones" autocomplete="off">
			</form>
			<span class="pnt-contador" id="pntContador" aria-live="polite"></span>
		</div>
	</div>

	<!-- grupos -->
	<div class="pnt-grid">
		<?php foreach ($grupos as $grupo) { $cantidad = $grupo["lista"] ? count($grupo["lista"]) : 0; ?>
		<section class="pv2-card pnt-grupo" data-grupo="<?php echo $grupo["clave"]; ?>"<?php echo $cantidad == 0 ? ' hidden' : ''; ?>>
			<div class="pnt-grupo-head">
				<p class="pv2-card-title">
					<span class="pv2-ico" aria-hidden="true"><?php echo $grupo["icono"]; ?></span>
					<?php echo $grupo["titulo"]; ?>
					<span class="pv2-count pnt-grupo-num"><?php echo $cantidad; ?></span>
				</p>
				<div class="pnt-grupo-acciones">
					<label class="pnt-check-todo"><input type="checkbox" class="pnt-check-todo-input" aria-label="Seleccionar todas de <?php echo $grupo["titulo"]; ?>"> Todas</label>
					<button type="button" class="btn btn-default btn-sm pnt-btn-grupo">Marcar leídas</button>
				</div>
			</div>
			<ul class="pnt-lista">
				<?php if ($grupo["lista"]) foreach ($grupo["lista"] as $i) { ?>
				<li class="pnt-item">
					<label class="pnt-item-check"><input type="checkbox" value="1" class="checkIt" data-errorid="<?php echo $i->errorID; ?>" aria-label="Seleccionar notificación"></label>
					<div class="pnt-item-body">
						<p class="pnt-item-msg"><?php echo $i->message; ?></p>
						<span class="pnt-item-fecha"><?php echo date_format(date_create($i->createdOn), "Y-m-d"); ?></span>
					</div>
					<button type="button" class="pnt-item-leer" title="Marcar como leída" aria-label="Marcar como leída">✓</button>
				</li>
				<?php } ?>
			</ul>
		</section>
		<?php } ?>
	</div>

	<!-- sin notificaciones / sin resultados -->
	<div class="pv2-empty pnt-empty" id="pntVacio"<?php echo $totalPendientes > 0 ? ' hidden' : ''; ?>>
		<i aria-hidden="true">🎉</i>
		<span id="pntVacioTexto">Estás al día: no tienes notificaciones pendientes.</span>
	</div>

	<!-- barra inferior (solo telefono) -->
	<div class="pnt-mbar">
		<button type="button" class="btn btn-primary" id="pntBtnSeleccionadasMovil">✓ Marcar seleccionadas</button>
	</div>
</div>
