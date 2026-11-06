<div class="pv2<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-arrow-right-10"></i></div>
            <div>
                <p class="pv2-header-title">Fecha de expiración</p>
                <p class="pv2-header-sub">Descarga, sube y procesa el archivo de la bodega seleccionada</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="#" class="btn btn-default" id="btnDownload"><i class="icon16 i-checkmark-4"></i> Descargar</a>
            <a href="#" class="btn btn-default" id="btnUpload"><i class="icon16 i-checkmark-4"></i> Subir Datos</a>
            <a href="#" class="btn btn-primary" id="btnProcess"><i class="icon16 i-checkmark-4"></i> Procesar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
    <form id="form-expired-date" name="form-expired-date" role="form" autocomplete="off">
        <div class="printArea">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="pv2-card" id="home">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Información</p>

                        <div class="form-group">
                            <label class="pv2-label" for="txtWarehouseID">Bodega <span class="pv2-req">*</span></label>
                            <select name="txtWarehouseID" id="txtWarehouseID" class="select2">
                                <option></option>
                                <?php
                                if ($objListWarehouse)
                                    foreach ($objListWarehouse as $i) {
                                        echo "<option value='" . $i->warehouseID . "'>" . $i->name . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- /formulario -->
</div>
