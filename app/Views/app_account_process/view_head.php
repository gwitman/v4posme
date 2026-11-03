				<?php echo view('core_template/posme_v2_style'); ?>
				<?php echo view('core_template/posme_v2_common'); ?>
				<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/posme-inventory.css?v=1" rel="stylesheet" />
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/posme-inventory.js?v=2"></script>
				<style>
					/* Procesos contables: tarjetas de accion (solo esta pantalla) */
					.pacp-grid { display:flex; flex-wrap:wrap; }
					.pacp-grid > [class*="col-"] { display:flex; }
					.pacp-grid .pv2-card { width:100%; display:flex; flex-direction:column; }
					.pacp-grid .pv2-card form { flex:1 1 auto; display:flex; flex-direction:column; }
					.pacp-desc { margin:-6px 0 12px; }
					.pacp-foot { margin-top:auto; display:flex; flex-wrap:wrap; justify-content:flex-end; gap:6px; }
					.pacp-foot .btn { border-radius:8px; }
					.pacp-foot .btn-group { display:flex; }
					.pacp-empty { color:var(--pv2-warn) !important; }
					@media (max-width:767px) {
						.pacp-foot { flex-direction:column; }
						.pacp-foot .btn, .pacp-foot .btn-group, .pacp-foot .btn-group > .btn { display:block; width:100%; min-height:44px; }
						.pacp-foot .dropdown-menu { width:100%; }
					}
				</style>
				<div id="heading" class="page-header">
							<h1><i class="icon20  i-hammer"></i> Procesos Contables</h1>
				</div>
