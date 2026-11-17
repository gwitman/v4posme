<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet" />
<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/moment.min.js"></script>
<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/mask/jquery.mask.min.js"></script>
<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/jLinq-2.2.1.js"></script>
<?php echo view('core_template/posme_v2_style'); ?>
<?php echo view('core_template/posme_v2_common'); ?>
<?php echo view('app_afx_fixedassent/afx_v2_common'); ?>
<style>
    /* archivo de precios: combo + botones en una fila que no se desborda en telefono */
    .price-file-row { display:flex; gap:6px; align-items:center; }
    .price-file-row select { flex:1 1 auto; min-width:0; }
    .price-file-row .btn { flex:0 0 auto; height:38px; min-width:42px; }
    .price-page-size { width:auto; max-width:170px; }
    .price-pager { display:flex; align-items:center; justify-content:center; gap:12px; margin-top:10px; }
    .price-pager .btn { min-width:44px; height:38px; }
    .price-up   { color:#1a7f37; font-weight:700; }
    .price-down { color:#c62828; font-weight:700; }
    @media (max-width:767px){
        .price-file-row .btn, .price-pager .btn { height:44px; }
        .price-page-size { max-width:none; width:100%; }
    }
</style>

<div id="heading" class="page-header">
    <h1><i class="icon20 i-coins"></i> Editar Lista de Precio</h1>
</div>
