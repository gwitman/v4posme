<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/bootstrap-datepicker.js"></script>
<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet" />



<div id="heading" class="page-header">
    <h1><i class="icon20 i-bag-2"></i> LISTA DE <?php echo getBehavio($company->type, "app_cxc_document", "labelTitlePageList", "DOCUMENTOS"); ?></h1>
</div>

<div class="row">
    <div id="email" class="col-lg-12">
        <div class="email-bar" style="width:100%">

            <form class="navbar-form navbar-left" role="search">
                <div class="col-lg-12 srch">

                    <div class="input-group-btn">
                        <div id="datepicker" class="input-group date" data-date="<?php echo $objFecha->format("Y-m-d"); ?>" data-date-format="yyyy-mm-dd">
                            <input size="16" class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="<?php echo $objFecha->format("Y-m-d"); ?>">
                            <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                        </div>
                    </div>


                    <div class="input-group-btn">
                        <input type="text" class="form-control" id="txtSearchTransaction" placeholder="search ..." style="width:160px">
                        <button type="button" class="btn" id="btnSearchTransaction"><i class="icon16 i-search-2 gap-left5"></i></button>
                    </div>


                </div>



            </form>

            <div class="btn-group pull-right" style="margin-top:0px">
                <a href="#" class="btn btn-inverse" id="btnView"><i class="icon16 i-grid-2"></i>Vistas</a>
                <a href="#" class="btn btn-primary" id="btnEdit"><i class="icon16 i-pencil-4"></i>Editar</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="icon"><i class="icon20 i-table"></i></div>
                <h4>...</h4>
                <a href="#" class="minimize"></a>
            </div>
            <div class="panel-body" style="<?php echo getBehavio($company->type, "app_cxc_document", "bodyListInvoice", ""); ?>">