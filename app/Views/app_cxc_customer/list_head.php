<?php echo view('core_template/posme_v2_style'); ?>

<div id="heading" class="page-header">
    <h1><i class="icon20 i-address-book"></i> LISTA DE <?php echo getBehavio($company->type,"app_cxc_customer","labelTitlePageList","CLIENTES"); ?></h1>
</div>

<div class="pv2">
    <div class="pv2-card pv2-listbar">
        <form class="pv2-search" role="search" autocomplete="off" onsubmit="$('#btnSearchCustomer').trigger('click'); return false;">
            <input type="text" class="form-control" id="txtSearchCustomer" placeholder="Buscar..." autocomplete="off">
            <button type="button" class="btn btn-primary" id="btnSearchCustomer" title="Buscar"><i class="icon16 i-search-2"></i></button>
        </form>
        <div class="pv2-actions">
            <a href="#" class="btn btn-default" id="btnView"><i class="icon16 i-grid-2"></i> Vistas</a>
            <a href="#" class="btn btn-default" id="btnEdit"><i class="icon16 i-pencil-4"></i> Editar</a>
            <a href="#" class="btn btn-danger" id="btnEliminar"><i class="icon16 i-remove"></i> Eliminar</a>
            <a href="#" class="btn btn-primary" id="btnNuevo"><i class="icon16 i-plus"></i> Nuevo</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <?php
            //El parametro de empresa decide si la lista tiene alto fijo con barra de desplazamiento
            if( $objParameterCORE_VIEW_CUSTOM_SCROLL_IN_LIST_CUSTOMER == "true" )
            {
                ?>
                <div id="div-container-list" style="overflow:scroll;height:600px">
                <?php
            }
            else
            {
                ?>
                <div id="div-container-list">
                <?php
            }
        ?>
