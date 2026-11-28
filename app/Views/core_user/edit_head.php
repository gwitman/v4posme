				<?php echo view('core_template/posme_v2_style'); ?>
				<?php echo view('core_template/posme_v2_common'); ?>
				<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/posme-inventory.css?v=1" rel="stylesheet" />
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/posme-inventory.js?v=2"></script>
				<style>
					/* == core_user edit: tab Parametros == */
					/* una tarjeta por parametro; 2 columnas en pantallas grandes, 1 en tablet y telefono */
					.pv2.pinv .cuser-param-list { display:grid; grid-template-columns:1fr; gap:12px; }
					@media (min-width:1200px) { .pv2.pinv .cuser-param-list { grid-template-columns:1fr 1fr; } }
					.pv2.pinv .cuser-param-card { min-width:0; border:1px solid var(--pv2-border); border-radius:10px; background:#fff; padding:12px 14px; }
					.pv2.pinv .cuser-param-head { display:flex; align-items:flex-start; justify-content:space-between; gap:8px; margin-bottom:8px; }
					.pv2.pinv .cuser-param-title { min-width:0; font-weight:700; overflow-wrap:anywhere; }
					.pv2.pinv .cuser-param-name { display:block; font-size:11px; font-weight:400; color:var(--pv2-muted); overflow-wrap:anywhere; }
					.pv2.pinv .cuser-param-type { display:inline-block; font-size:10px; font-weight:700; text-transform:uppercase; color:var(--pv2-accent); background:var(--pv2-accent-bg); border-radius:10px; padding:0 6px; margin-left:4px; }
					.pv2.pinv .cuser-param-id { flex:0 0 auto; font-size:11px; color:var(--pv2-muted); white-space:nowrap; }
					.pv2.pinv .cuser-param-grid { display:grid; grid-template-columns:1fr 1fr; gap:8px 12px; }
					.pv2.pinv .cuser-param-field { min-width:0; }
					.pv2.pinv .cuser-param-field.is-full { grid-column:1 / -1; }
					.pv2.pinv .cuser-param-field label { display:block; font-size:12px; color:var(--pv2-muted); margin-bottom:2px; }
					/* descripcion y valor: solo lectura, el texto largo hace salto de linea (sin scroll horizontal) */
					.pv2.pinv .cuser-param-ro { width:100%; resize:vertical; white-space:pre-wrap; overflow-wrap:anywhere; overflow-x:hidden; background:var(--pv2-soft); cursor:default; }
					.pv2.pinv .cuser-param-card .jsParamEdit { width:100%; }
					.pv2.pinv .cuser-param-card.is-dirty { background:var(--pv2-warn-bg); }
					.pv2.pinv .cuser-param-card.has-error { background:var(--pv2-bad-bg); border-color:var(--pv2-bad); }
					.pv2.pinv .cuser-param-card.has-error .jsParamEdit { border-color:var(--pv2-bad); }
					/* == tabs Configuracion pantallas y Plantillas (reutilizan la tarjeta de Parametros) == */
					.pv2.pinv .cuser-ps-badge { display:inline-block; font-size:10px; font-weight:700; text-transform:uppercase; border-radius:10px; padding:0 6px; margin-left:4px; color:var(--pv2-ok); background:var(--pv2-ok-bg); }
					.pv2.pinv .cuser-ps-badge.is-new { color:var(--pv2-info); background:var(--pv2-info-bg); }
					.pv2.pinv .cuser-ps-meta { font-size:12px; color:var(--pv2-muted); overflow-wrap:anywhere; margin-bottom:6px; }
					.pv2.pinv .cuser-ps-meta b { color:#1f2933; font-weight:600; }
					.pv2.pinv .cuser-ps-value { width:100%; resize:vertical; white-space:pre-wrap; overflow-wrap:anywhere; overflow-x:hidden; }
					.pv2.pinv .cuser-ps-large .cuser-ps-value { font-family:Consolas, "Courier New", monospace; font-size:12px; }
					.pv2.pinv .cuser-ps-foot { display:flex; align-items:center; justify-content:space-between; gap:8px; margin-top:8px; flex-wrap:wrap; }
					/* boton Guardar de la tarjeta: mismo redondeo que los botones de la barra */
					.pv2.pinv .cuser-ps-foot .btn { border-radius:8px; }
					.pv2.pinv .cuser-ps-msg { font-size:12px; color:var(--pv2-muted); min-width:0; overflow-wrap:anywhere; }
					.pv2.pinv .cuser-param-card.has-error .cuser-ps-msg { color:var(--pv2-bad); }
					.pv2.pinv .cuser-param-card.is-new { border-style:dashed; border-color:var(--pv2-info); }
					.pv2.pinv .cuser-ps-warn { color:var(--pv2-warn); background:var(--pv2-warn-bg); }
					@media (max-width:767px) {
						.pv2.pinv .cuser-ps-foot .btn { width:100%; min-height:44px; }
						.pv2.pinv .cuser-ps-value { font-size:16px !important; }
						.pv2.pinv .cuser-param-grid { grid-template-columns:1fr; }
						.pv2.pinv .cuser-param-card .jsParamEdit { height:44px; font-size:16px; }
						.pv2.pinv .cuser-param-ro { font-size:16px; }
					}
				</style>
				<div id="heading" class="page-header">
							<h1><i class="icon20  i-users"></i> Editar Usuario</h1>
				</div> 