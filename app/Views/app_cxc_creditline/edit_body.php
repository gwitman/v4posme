<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-rating"></i></div>
            <div>
                <p class="pv2-header-title">Línea de crédito: #<?php echo $objCreditLine->creditLineID; ?></p>
                <p class="pv2-header-sub">Revisa o actualiza la línea</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_cxc_creditline/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
    <form id="form-new-account-type" name="form-new-rol" role="form" autocomplete="off">
        <input type="hidden" name="txtCompanyID" value="<?php echo $objCreditLine->companyID; ?>">
        <input type="hidden" name="txtCreditLineID" value="<?php echo $objCreditLine->creditLineID; ?>">

        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="pv2-card">
                    <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Datos de la línea</p>

                    <div class="form-group">
                        <label class="pv2-label" for="txtNumber">Codigo</label>
                        <input class="form-control" type="text" autocomplete="off" name="txtNumber" readonly id="txtNumber" value="<?php echo $objCreditLine->creditLineID; ?>">
                    </div>
                    <div class="form-group">
                        <label class="pv2-label" for="txtName">Nombre <span class="pv2-req">*</span></label>
                        <input class="form-control" type="text" autocomplete="off" name="txtName" id="txtName" value="<?php echo $objCreditLine->name; ?>">
                    </div>
                    <div class="form-group">
                        <label class="pv2-label" for="txtDescription">Direccion</label>
                        <textarea class="form-control" id="txtDescription" name="txtDescription" rows="3" autocomplete="off"><?php echo $objCreditLine->description; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- /formulario -->

    <!-- barra inferior (telefono) -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label">Línea de crédito #<?php echo $objCreditLine->creditLineID; ?></p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>
