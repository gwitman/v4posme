<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-storage-2"></i></div>
            <div>
                <p class="pv2-header-title">CHEQUERA:#<span class="invoice-num">00000000</span></p>
                <p class="pv2-header-sub">Agregar chequera</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_bank_cheque/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- body -->
    <form id="form-new-bankCheque" name="form-new-bankCheque" role="form" autocomplete="off">

        <div class="pv2-card" id="home">
            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Información</p>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="pv2-label" for="txtName">Nombre</label>
                        <input class="form-control" autocomplete="off" type="text" name="txtName" id="txtName" value="">
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

                    <div class="form-group">
                        <label class="pv2-label" for="txtCurrencyID">Moneda</label>
                        <select name="txtCurrencyID" id="txtCurrencyID" class="select2">
                            <?php
                            if ($objListCurrency)
                                foreach ($objListCurrency as $ws) {
                                    if ($ws->currencyID == $objCurrency->currencyID)
                                        echo "<option value='" . $ws->currencyID . "' selected>" . $ws->simb . "</option>";
                                    else
                                        echo "<option value='" . $ws->currencyID . "' >" . $ws->simb . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="pv2-label" for="txtBankDescription">Banco</label>
                        <div class="input-group">
                            <input type="hidden" id="txtBankID" name="txtBankID" value="">
                            <input class="form-control" readonly autocomplete="off" id="txtBankDescription" type="text" value="">

                            <span class="input-group-btn">
                                <button class="btn btn-danger" type="button" id="btnClearBank">
                                    <i aria-hidden="true" class="i-undo-2"></i>
                                    Limpiar
                                </button>
                            </span>
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="button" id="btnSearchBank">
                                    <i aria-hidden="true" class="i-search-5"></i>
                                    Buscar
                                </button>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="pv2-label" for="txtEmployeeDescription">Empleado</label>
                        <div class="input-group">
                            <input type="hidden" id="txtEmployeeEntityID" name="txtEmployeeEntityID" value="">
                            <input class="form-control" readonly autocomplete="off" id="txtEmployeeDescription" type="text" value="">

                            <span class="input-group-btn">
                                <button class="btn btn-danger" type="button" id="btnClearEmployee">
                                    <i aria-hidden="true" class="i-undo-2"></i>
                                    Limpiar
                                </button>
                            </span>
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="button" id="btnSearchEmployee">
                                    <i aria-hidden="true" class="i-search-5"></i>
                                    Buscar
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pv2-card">
            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-pencil-4"></i></span> Numeración</p>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="pv2-label" for="txtSerie">Serie</label>
                        <input class="form-control" autocomplete="off" type="text" name="txtSerie" id="txtSerie" value="">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="pv2-label" for="txtInitialValue">Valor Inicial</label>
                        <input class="form-control" autocomplete="off" inputmode="numeric" type="text" name="txtInitialValue" id="txtInitialValue" value="">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="pv2-label" for="txtFinalValue">Valor Final</label>
                        <input class="form-control" autocomplete="off" inputmode="numeric" type="text" name="txtFinalValue" id="txtFinalValue" value="">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="pv2-label" for="txtCurrentValue">Valor Corriente</label>
                        <input class="form-control" readonly autocomplete="off" inputmode="numeric" type="text" name="txtCurrentValue" id="txtCurrentValue" value="">
                    </div>
                </div>
            </div>
        </div>

    </form>
    <!-- /body -->

    <!-- barra inferior (telefono) -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label">Chequera</p>
            <p class="pv2-mbar-total">Nueva</p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>