<?php echo view('core_template/posme_v2_style'); ?>

<div id="heading" class="page-header">
    <h1><i class="icon20 i-cube-3"></i> LISTA DE INMUEBLES DESACTIVADOS</h1>
</div>

<div class="pv2">
    <div class="pv2-card pv2-listbar">
        <form class="pv2-search" role="search" autocomplete="off" onsubmit="$('#btnSearchItem').trigger('click'); return false;">
            <input type="text" class="form-control" id="txtSearchItem" placeholder="Buscar…" autocomplete="off">
            <button type="button" class="btn btn-primary" id="btnSearchItem" title="Buscar"><i class="icon16 i-search-2"></i></button>
        </form>
        <div class="pv2-actions">
            <a href="#" class="btn btn-primary" id="btnEdit"><i class="icon16 i-pencil-4"></i> Editar</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div id="div-container-list">
