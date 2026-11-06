<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-file"></i></div>
            <div>
                <p class="pv2-header-title">Nota de crédito:#<span class="invoice-num">00000000</span> <span class="pv2-chip">Nueva</span></p>
                <p class="pv2-header-sub">Nota de crédito de proveedor</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_cxp_notecredit/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
    <form id="form-new-notecredit" name="form-new-notecredit" role="form" autocomplete="off">
        <div class="printArea">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="pv2-card" id="home">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Datos de la nota</p>

                        <div class="form-group <?php echo getBehavio($company->type, "app_box_share", "divFecha", ""); ?> ">
                            <label class="pv2-label" for="txtDate">Fecha <span class="pv2-req">*</span></label>
                            <div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
                                <input size="16" class="form-control" type="text" autocomplete="off" name="txtDate" id="txtDate">
                                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="pv2-label" for="txtStatusID">Estado</label>
                            <select name="txtStatusID" id="txtStatusID" class="select2">
                                <option></option>
                                <?php
                                if ($objListWorkflowStage)
                                    foreach ($objListWorkflowStage as $ws) {
                                        echo "<option value='" . $ws->workflowStageID . "' selected>" . $ws->name . "</option>";
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="form-group <?php echo getBehavio($company->type, "app_cxp_notecredit", "divMoneda", ""); ?>  ">
                            <label class="pv2-label" for="txtCurrencyID">Moneda</label>
                            <select name="txtCurrencyID" id="txtCurrencyID" class="<?php echo ($useMobile == "1" ? "form-control" : "select2"); ?>">
                                <option></option>
                                <?php
                                if ($objListCurrency)
                                    foreach ($objListCurrency as $ws) {

                                        if ($ws->currencyID == $objListCurrencyDefault->currencyID)
                                            echo "<option value='" . $ws->currencyID . "' selected >" . $ws->name . "</option>";
                                        else
                                            echo "<option value='" . $ws->currencyID . "' >" . $ws->name . "</option>";
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="pv2-label" for="txtAmount">Monto <span class="pv2-req">*</span></label>
                            <input class="form-control pv2-num afx-monto" type="text" inputmode="decimal" autocomplete="off" name="txtAmount" id="txtAmount" value="0">
                        </div>

                        <div class="form-group <?php echo getBehavio($company->type, "app_cxp_notecredit", "divCustomerControlBuscar", ""); ?> ">
                            <label class="pv2-label" for="txtCustomerDescription"><?php echo getBehavio($company->type, "app_cxp_notecredit", "lblProveedor", "Proveedor"); ?> <span class="pv2-req">*</span></label>
                            <div class="input-group afx-search">
                                <input type="hidden" id="txtCustomerID" name="txtCustomerID" value="">
                                <input class="form-control" readonly id="txtCustomerDescription" type="text" autocomplete="off" value="">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" id="btnClearCustomer">
                                        <i aria-hidden="true" class="i-undo-2"></i>
                                        Limpiar
                                    </button>
                                </span>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button" id="btnSearchCustomer">
                                        <i aria-hidden="true" class="i-search-5"></i>
                                        Buscar
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-6">
                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-pencil-4"></i></span> Referencias y comentario</p>

                        <div class="form-group">
                            <label class="pv2-label" for="txtRef1">Referencia 1</label>
                            <input class="form-control" type="text" autocomplete="off" name="txtRef1" id="txtRef1" value="">
                        </div>

                        <div class="form-group">
                            <label class="pv2-label" for="txtRef2">Referencia 2</label>
                            <input class="form-control" type="text" autocomplete="off" name="txtRef2" id="txtRef2" value="">
                        </div>

                        <div class="form-group">
                            <label class="pv2-label" for="txtRef3">Referencia 3</label>
                            <input class="form-control" type="text" autocomplete="off" name="txtRef3" id="txtRef3" value="">
                        </div>

                        <div class="form-group">
                            <label class="pv2-label" for="txtComment">Comentario</label>
                            <textarea class="form-control" name="txtComment" id="txtComment" rows="6"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content">
                <div class="tab-pane fade" id="dropdown-file">

                </div>
            </div>
        </div>
    </form>
    <!-- /formulario -->

    <!-- barra inferior (telefono) -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label">Nueva nota de crédito</p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>
