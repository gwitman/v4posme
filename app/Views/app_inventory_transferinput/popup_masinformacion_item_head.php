	<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet" />
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/bootstrap-datepicker.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/posme-inventory.js?v=2"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/moment.min.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/mask/jquery.mask.min.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/jLinq-2.2.1.js"></script>

	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/pie-chart/jquery.easy-pie-chart.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/ui/range-slider/rangeslider.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/pages/ui-elements.js"></script>
	<?php echo view('core_template/posme_v2_style'); ?>
	<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/posme-inventory.css?v=1" rel="stylesheet" />


	<div class="pv2 pinv pinv-popup">
		<div class="pinv-popup-bar">
			<div class="pinv-popup-title">
				<span class="pv2-ico"><i class="icon16 i-info"></i></span>
				<span>Mas informacion del producto</span>
			</div>
			<div class="pinv-popup-actions">
				<a href="#" class="btn btn-default" id="btnPopupCancelar"><i class="icon16 i-rotate"></i> Cancelar</a>
				<a href="#" class="btn btn-primary" id="btnPopupAceptar"><i class="icon16 i-checkmark-4"></i> Aceptar</a>
			</div>
		</div>
	</div>
