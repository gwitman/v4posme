<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet" />
<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/all.min.css" rel="stylesheet" />
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
<?php echo view('app_cxp_provider/prv_v2_common'); ?>
<?php echo view('app_cxc_customer/cst_v2_common'); ?>

<div id="heading" class="page-header">
    <h1><i class="icon20 i-address-book"></i> Editar <?php echo getBehavio($company->type,"app_cxc_customer","labelTitlePageEdit","Cliente"); ?></h1>
</div>
