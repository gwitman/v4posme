<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/bootstrap-datepicker.js"></script>
<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet" />
<?php echo view('core_template/posme_v2_style'); ?>

<div id="heading" class="page-header">
    <h1><i class="icon20 i-bag-2"></i> LISTA DE <?php echo getBehavio($company->type, "app_cxc_document", "labelTitlePageList", "DOCUMENTOS"); ?></h1>
</div>

<div class="pv2">
    <div class="pv2-card pv2-listbar">
        <form class="pv2-search" role="search" autocomplete="off" style="flex-wrap:wrap" onsubmit="$('#btnSearchTransaction').trigger('click'); return false;">
            <div id="datepicker" class="input-group date" style="flex:1 1 150px; min-width:0" data-date="<?php echo $objFecha->format("Y-m-d"); ?>" data-date-format="yyyy-mm-dd">
                <input size="16" class="form-control" type="text" autocomplete="off" name="txtStartOn" id="txtStartOn" value="<?php echo $objFecha->format("Y-m-d"); ?>">
                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
            </div>
            <div style="display:flex; gap:6px; flex:1 1 150px; min-width:0">
                <input type="text" class="form-control" id="txtSearchTransaction" placeholder="Buscar…" autocomplete="off" style="min-width:0">
                <button type="button" class="btn btn-primary" id="btnSearchTransaction" title="Buscar"><i class="icon16 i-search-2"></i></button>
            </div>
        </form>
        <div class="pv2-actions">
            <a href="#" class="btn btn-default" id="btnView"><i class="icon16 i-grid-2"></i> Vistas</a>
            <a href="#" class="btn btn-primary" id="btnEdit"><i class="icon16 i-pencil-4"></i> Editar</a>
        </div>
    </div>
</div>

<!-- Estos divs no se cierran aqui: el controlador no carga list_footer y la grilla se imprime dentro -->
<div class="row">
    <div class="col-lg-12">
        <div id="div-container-list" style="<?php echo getBehavio($company->type, "app_cxc_document", "bodyListInvoice", ""); ?>">
