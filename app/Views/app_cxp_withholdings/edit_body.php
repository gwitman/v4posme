<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-file"></i></div>
            <div>
                <p class="pv2-header-title">Retencion:#<span class="invoice-num"><?php echo $objTM->transactionNumber ?></span> <span class="pv2-chip">Editando</span></p>
                <p class="pv2-header-sub">Retención de proveedor</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_cxp_withholdings/add" class="btn btn-default" id="btnNuevo"><i class="icon16 i-plus"></i> Nuevo</a>
            <a href="<?php echo base_url(); ?>/app_cxp_withholdings/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
            <a href="#" class="btn btn-default" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
    <form id="form-new-withholding" name="form-new-withholding" role="form" autocomplete="off">
        <div class="printArea">

            <ul id="myTab" class="nav nav-tabs">
                <li class="active">
                    <a href="#home" data-toggle="tab">Informacion</a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Mas <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a id="btnClickArchivo" href="#dropdown-file" data-toggle="tab">Archivos</a></li>
                    </ul>
                </li>
            </ul>

            <div class="tab-content">
                <input type="hidden" name="txtTransactionID" id="txtTransactionID" value="<?= $objTM->transactionID ?>">
                <input type="hidden" name="txtTransactionMasterID" id="txtTransactionMasterID" value="<?= $objTM->transactionMasterID ?>">
                <div class="tab-pane fade in active" id="home">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Datos de la retención</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDate">Fecha <span class="pv2-req">*</span></label>
                                    <div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
                                        <input size="16" class="form-control" type="text" autocomplete="off" name="txtDate" id="txtDate" value="<?= explode(' ', $objTM->transactionOn)[0]; ?>">
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
                                                if ($ws->workflowStageID == $objTM->statusID)
                                                    echo "<option value='" . $ws->workflowStageID . "' selected>" . $ws->name . "</option>";
                                                else
                                                    echo "<option value='" . $ws->workflowStageID . "' >" . $ws->name . "</option>";
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
                                                if ($ws->currencyID == $objTM->currencyID)
                                                    echo "<option value='" . $ws->currencyID . "' selected>" . $ws->simb . "</option>";
                                                else
                                                    echo "<option value='" . $ws->currencyID . "' >" . $ws->simb . "</option>";
                                            }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtProviderDescription">Proveedor <span class="pv2-req">*</span></label>
                                    <div class="input-group afx-search">
                                        <input type="hidden" id="txtProviderEntityID" name="txtProviderEntityID" value="<?= $objTM->entityID ?>">
                                        <input class="form-control" readonly id="txtProviderDescription" type="text" autocomplete="off" value="<?= $objProvider->providerNumber . " | " . $objListNatural->firstName . " " . $objListNatural->lastName ?>">
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

                                <!-- FACTURA -->
                                <div class="form-group">
                                    <label class="pv2-label" for="txtInvoice">Factura <span class="pv2-req">*</span></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtInvoice" id="txtInvoice" value="<?= $objTM->reference3 ?>">
                                </div>

                                <!-- IMPUESTO -->
                                <div class="form-group">
                                    <label class="pv2-label" for="txtTax">Impuesto</label>
                                    <select name="txtTax" id="txtTax" class="select2">
                                        <?php
                                        $counter = 0;
                                        if ($objPublicCatalogTaxPercentage)
                                            foreach ($objPublicCatalogTaxPercentage as $tp) {
                                                if ($tp->catalogItemID == $objTM->reference4)
                                                    echo "<option value='" . $tp->catalogItemID . "' selected data-ratio='".$tp->ratio."' >" . $tp->name . "</option>";
                                                else
                                                    echo "<option value='" . $tp->catalogItemID . "' data-ratio='".$tp->ratio."' >" . $tp->name . "</option>";
                                                $counter++;
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-grid-2"></i></span> Montos</p>

                                <!-- MONTO DE FACTURA -->
                                <div class="form-group">
                                    <label class="pv2-label" for="txtInvoiceAmount">Monto de Factura <span class="pv2-req">*</span></label>
                                    <input class="form-control pv2-num afx-monto afx-sin-negativo" type="text" inputmode="decimal" autocomplete="off" name="txtInvoiceAmount" id="txtInvoiceAmount" value="<?= sprintf("%01.4f", $objTM->amount) ?>">
                                </div>

                                <!-- MONTO DE RETENCION -->
                                <div class="form-group">
                                    <label class="pv2-label" for="txtWithholdingAmount">Monto de Retencion</label>
                                    <input class="form-control pv2-num afx-monto" readonly type="text" inputmode="decimal" autocomplete="off" name="txtWithholdingAmount" id="txtWithholdingAmount" value="<?= sprintf("%01.4f", $objTM->discount) ?>">
                                </div>
                            </div>

                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-pencil-4"></i></span> Referencias y comentario</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtReference1">Referencia 1</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference1" id="txtReference1" value="<?= $objTM->reference1 ?>">
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtReference2">Referencia 2</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference2" id="txtReference2" value="<?= $objTM->reference2 ?>">
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtComment">Comentario</label>
                                    <textarea class="form-control" id="txtComment" name="txtComment" rows="6"><?= $objTM->note; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="dropdown-file">

                </div>
            </div>
        </div>
    </form>
    <!-- /formulario -->

    <!-- barra inferior (telefono) -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label">Retención #<?php echo $objTM->transactionNumber ?></p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>
