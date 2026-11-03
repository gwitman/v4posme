				<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet" />
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/bootstrap-datepicker.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/moment.min.js"></script>
				<?php echo view('core_template/posme_v2_style'); ?>
				<?php echo view('core_template/posme_v2_common'); ?>
				<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/posme-inventory.css?v=1" rel="stylesheet" />
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/posme-inventory.js?v=2"></script>
				<style>
					/* Herramienta de monedas: tarjetas de accion (solo esta pantalla) */
					.pacc-grid { display:flex; flex-wrap:wrap; }
					.pacc-grid > [class*="col-"] { display:flex; }
					.pacc-grid .pv2-card { width:100%; display:flex; flex-direction:column; }
					.pacc-grid .pv2-card form { flex:1 1 auto; display:flex; flex-direction:column; }
					.pacc-desc { margin:-6px 0 12px; }
					.pacc-foot { margin-top:auto; display:flex; justify-content:flex-end; gap:6px; }
					.pacc-foot .btn { border-radius:8px; }
					@media (max-width:767px) {
						.pacc-foot .btn { display:block; width:100%; min-height:44px; }
					}
				</style>

				<div id="heading" class="page-header">
							<h1><i class="icon20  i-coins"></i> Registrar Tasa de Cambio</h1>
				</div>
