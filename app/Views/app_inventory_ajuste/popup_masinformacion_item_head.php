	<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet" />
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/bootstrap-datepicker.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/moment.min.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/mask/jquery.mask.min.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/jLinq-2.2.1.js"></script>

	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/pie-chart/jquery.easy-pie-chart.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/ui/range-slider/rangeslider.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/pages/ui-elements.js"></script>
	<?php echo view('core_template/posme_v2_style'); ?>
	<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/posme-inventory.css?v=1" rel="stylesheet" />


	<div class="pv2 pinv pinv-popup" style="padding:12px 12px 0">
		<div class="pv2-card pv2-header" style="margin-bottom:0">
			<div class="pv2-header-info">
				<div class="pv2-header-icon"><i class="icon20 i-info"></i></div>
				<div>
					<p class="pv2-header-title">Mas informacion</p>
					<p class="pv2-header-sub">Lote, vencimiento y precios del producto</p>
				</div>
			</div>
			<div class="pv2-actions">
				<a href="#" class="btn btn-default"  id="btnPopupCancelar"><i class="icon16 i-rotate"></i> Cancelar</a>
				<a href="#" class="btn btn-primary" id="btnPopupAceptar"><i class="icon16 i-checkmark-4"></i> Aceptar</a>
			</div>
		</div>
	</div>
