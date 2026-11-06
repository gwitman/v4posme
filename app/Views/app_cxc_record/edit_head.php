				<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet" />
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/bootstrap-datepicker.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/moment.min.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/mask/jquery.mask.min.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/jLinq-2.2.1.js"></script>

				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/pie-chart/jquery.easy-pie-chart.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/ui/range-slider/rangeslider.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/pages/ui-elements.js"></script>
				<?php echo view('core_template/posme_v2_style'); ?>
				<?php echo view('core_template/posme_v2_common'); ?>
				<?php echo view('app_afx_fixedassent/afx_v2_common'); ?>
				<style>
					/* Buro de credito: datos de solo lectura (etiqueta y valor en la misma fila) */
					.cxr-credito { border-top:1px solid var(--pv2-border); padding-top:12px; margin-top:12px; }
					.cxr-credito:first-of-type { border-top:0; padding-top:0; margin-top:0; }
					.cxr-credito-titulo { font-weight:700; margin:0 0 2px; word-break:break-word; }
					.cxr-credito-fecha { font-size:12px; margin:0 0 10px; }
					.cxr-datos { display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:0 24px; }
					.cxr-fila { display:flex; justify-content:space-between; gap:12px; padding:3px 0; font-size:13px; }
					.cxr-fila-label { color:var(--pv2-muted); flex:0 0 auto; }
					.cxr-fila-valor { text-align:right; min-width:0; word-break:break-word; }
					.cxr-seccion { font-size:14px; font-weight:700; margin:16px 0 8px; }
					.pv2 .dl-horizontal { margin-bottom:0; }
					.pv2 .dl-horizontal dd + dt { margin-top:6px; }
				</style>

				<div id="heading" class="page-header">
							<h1><i class="icon20 i-address-book"></i> Consultar Record de Credito Historico y Actual</h1>
				</div>
