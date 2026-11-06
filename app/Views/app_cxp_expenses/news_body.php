<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-storage-2"></i></div>
            <div>
                <p class="pv2-header-title">Gasto:#<span class="invoice-num">00000000</span> <span class="pv2-chip">Nuevo</span></p>
                <p class="pv2-header-sub">Registra el gasto, su monto y el proveedor</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_cxp_expenses/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- body -->
    <form id="form-new-invoice" name="form-new-invoice" role="form" autocomplete="off">
        <div class="printArea">

            <ul id="myTab" class="nav nav-tabs">
                <li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
                <li><a href="#profile" data-toggle="tab">Referencias</a></li>
                <li><a href="#dropdown" data-toggle="tab">Comentario</a></li>
                <li><a href="#dropdown-file" data-toggle="tab">Archivos</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade in active" id="home">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Datos del documento</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDate">Fecha <span class="pv2-req">*</span></label>
                                    <div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
                                        <input size="16" class="form-control" type="text" autocomplete="off" name="txtDate" id="txtDate">
                                        <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                    </div>
                                </div>

                                <div class="form-group <?= getBehavio($company->type, "app_cxp_expenses", "lblAplicado", ""); ?>">
                                    <label class="pv2-label" for="txtIsApplied">Aplicado</label>
                                    <div>
                                        <input type="checkbox" disabled name="txtIsApplied" id="txtIsApplied" value="1">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtBranchID">Sucursal</label>
                                    <select name="txtBranchID" id="txtBranchID" class="select2">
                                        <?php
                                        $counter = 0;
                                        if ($objListBranch)
                                            foreach ($objListBranch as $ws) {
                                                if ($counter == 0)
                                                    echo "<option value='" . $ws->branchID . "' selected >" . $ws->name . "</option>";
                                                else
                                                    echo "<option value='" . $ws->branchID . "' >" . $ws->name . "</option>";

                                                $counter++;
                                            }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group <?= getBehavio($company->type, "app_cxp_expenses", "lblCambio", ""); ?>">
                                    <label class="pv2-label" for="txtExchangeRate">Cambio</label>
                                    <input class="form-control" type="text" autocomplete="off" disabled="disabled" name="txtExchangeRate" id="txtExchangeRate" value="<?php echo $exchangeRate; ?>">
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
                                    <label class="pv2-label" for="txtDetailReference1"><?php echo getBahavioDB($company->type, "app_cxp_expenses", "Referencia 1", "Referencia 1"); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtDetailReference1" id="txtDetailReference1" value="">
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDetailReference2"><?php echo getBehavio($company->type, "app_cxp_expenses", "Referencia 2", ""); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtDetailReference2" id="txtDetailReference2" value="">
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDetailReference3"><?php echo getBehavio($company->type, "app_cxp_expenses", "Referencia 3", ""); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtDetailReference3" id="txtDetailReference3" value="">
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDetailReference4"><?php echo getBehavio($company->type, "app_cxp_expenses", "Referencia 4", "Reference 4"); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtDetailReference4" id="txtDetailReference4" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Clasificación del gasto</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtPriorityID">Tipo</label>
                                    <select name="txtPriorityID" id="txtPriorityID" class="select2">
                                        <?php
                                        $counter = 0;

                                        if ($objListCatalogoTipoGastos)
                                            foreach ($objListCatalogoTipoGastos as $ws) {
                                                if ($counter == 0)
                                                    echo "<option value='" . $ws->publicCatalogDetailID . "' selected >" . $ws->name . "</option>";
                                                else
                                                    echo "<option value='" . $ws->publicCatalogDetailID . "' >" . $ws->name . "</option>";

                                                $counter++;
                                            }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtAreaID">Categoria <span class="pv2-req">*</span></label>
                                    <select name="txtAreaID" id="txtAreaID" class="select2">
                                        <?php
                                        $counter = 0;
                                        if ($objListCatalogoCategoriaGastos)
                                            foreach ($objListCatalogoCategoriaGastos as $ws) {
                                                if ($counter == 0)
                                                    echo "<option value='" . $ws->publicCatalogDetailID . "' selected >" . $ws->name . "</option>";
                                                else
                                                    echo "<option value='" . $ws->publicCatalogDetailID . "' >" . $ws->name . "</option>";

                                                $counter++;
                                            }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtClassID">Clasificación</label>
                                    <select name="txtClassID" id="txtClassID" class="select2">
                                        <?php
                                        $counter = 0;
                                        $selected = getBahavioDB($company->type, "app_cxp_expenses", "txtClassID_select", "General");
                                        if ($objListCatalogItemClasificacion)
                                                foreach ($objListCatalogItemClasificacion as $ws) {
                                                    if ($selected == $ws->display)
                                                        echo "<option value='" . $ws->catalogItemID . "' selected >" . $ws->display . "</option>";
                                                    else
                                                        echo "<option value='" . $ws->catalogItemID . "' >" . $ws->display . "</option>";

                                                    $counter++;
                                                }

                                        ?>
                                    </select>
                                </div>

                                <div id="panelGeneralRigth">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="profile">
                    <input type="hidden" name="txtDocumentNumber" id="txtDocumentNumber" value="">
                    <input type="hidden" name="txtCustomerCreditDocumentID" id="txtCustomerCreditDocumentID" value="">
                    <div class="row">
                        <div class="col-xs-12 col-md-6" id="pv2ColMontos">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-coin"></i></span> Montos</p>

                                <div class="form-group" id="divPanelCurrency">
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

                                <div class="form-group" id="divPanelMonto">
                                    <label class="pv2-label" for="txtDetailAmount">Monto <span class="pv2-req">*</span></label>
                                    <input type="hidden" name="txtDetailTransactionDetailID" value="0">
                                    <input class="form-control pv2-num afx-monto" type="text" inputmode="decimal" autocomplete="off" name="txtDetailAmount" id="txtDetailAmount" value="0">
                                </div>

                                <div class="form-group" id="divPanelIVA">
                                    <label class="pv2-label" for="txtTransactionMasterTax1">IVA</label>
                                    <input class="form-control pv2-num afx-monto" type="text" inputmode="decimal" autocomplete="off" name="txtTransactionMasterTax1" id="txtTransactionMasterTax1" value="0">
                                </div>

                                <div class="form-group" id="divPanelTotal">
                                    <label class="pv2-label" for="txtTransactionMasterTax2">Total</label>
                                    <input class="form-control pv2-num afx-monto" readonly type="text" autocomplete="off" name="txtTransactionMasterTax2" id="txtTransactionMasterTax2" value="0">
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Proveedor y factura</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtProviderDescription">Proveedor</label>
                                    <div class="input-group afx-search">
                                        <input type="hidden" id="txtProviderID" name="txtProviderID" value="">
                                        <input class="form-control" readonly id="txtProviderDescription" type="text" autocomplete="off" value="">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" id="btnClearProvider">
                                                <i aria-hidden="true" class="i-undo-2"></i>
                                                Limpiar
                                            </button>
                                        </span>
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary" type="button" id="btnSearchProvider">
                                                <i aria-hidden="true" class="i-search-5"></i>
                                                Buscar
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtAmortizationDescription">Factura</label>
                                    <div class="input-group afx-search">
                                        <input type="hidden" id="txtAmortizationID" name="txtAmortizationID" value="">
                                        <input class="form-control" readonly id="txtAmortizationDescription" type="text" autocomplete="off" value="">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" id="btnClearAmortization">
                                                <i aria-hidden="true" class="i-undo-2"></i>
                                                Limpiar
                                            </button>
                                        </span>
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary" type="button" id="btnSearchAmortization">
                                                <i aria-hidden="true" class="i-search-5"></i>
                                                Buscar
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="dropdown">
                    <div class="pv2-card">
                        <div class="form-group">
                            <label class="pv2-label" for="txtNote">Descripcion</label>
                            <textarea class="form-control" id="txtNote" name="txtNote" rows="6"></textarea>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="dropdown-file">

                </div>
            </div>
        </div>
    </form>
    <!-- /body -->

    <!-- barra inferior (telefono) -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label">Nuevo gasto</p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>
