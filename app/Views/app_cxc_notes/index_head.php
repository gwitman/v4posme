<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet" />
<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/all.min.css" rel="stylesheet" />
<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/moment.min.js"></script>
<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/mask/jquery.mask.min.js"></script>
<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/jLinq-2.2.1.js"></script>
<?php echo view('core_template/posme_v2_style'); ?>
<?php echo view('core_template/posme_v2_common'); ?>
<?php echo view('app_afx_fixedassent/afx_v2_common'); ?>

<style>
.select2-container
{
    margin:0px
}
/* La columna de filtros es angosta tambien en PC: los botones de busqueda bajan a su propia linea */
.pnotes .afx-search { display:flex; flex-wrap:wrap; gap:0 6px; width:100%; }
.pnotes .afx-search > .form-control { flex:1 1 100%; width:100%; border-radius:8px; }
.pnotes .afx-search > .input-group-btn { display:block; flex:1 1 40%; width:auto; margin-top:6px; font-size:14px; }
.pnotes .afx-search > .input-group-btn .btn { width:100%; border-radius:8px; margin:0; }
.pnotes #data_table p { margin:0; }
.pnotes-dato { margin:0 0 8px; }
</style>

<div id="heading" class="page-header">
    <h1><i class="icon20  i-storage-2"></i> Notas</h1>
</div>
