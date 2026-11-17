<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet" />
<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/moment.min.js"></script>
<?php echo view('core_template/posme_v2_style'); ?>
<?php echo view('core_template/posme_v2_common'); ?>
<?php echo view('app_afx_fixedassent/afx_v2_common'); ?>
<?php echo view('app_invoice_return/ret_v2_common'); ?>

<div id="heading" class="page-header">
    <h1><i class="icon20 i-cart-remove"></i> <?php echo (isset($isApplied) && $isApplied) ? "CONSULTAR" : "EDITAR"; ?> SUSTITUCION DE PRODUCTOS</h1>
</div>
