<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-file"></i></div>
            <div>
                <p class="pv2-header-title">Nota:#<span class="invoice-num"><?php echo $objTM->transactionNumber ?></span> <span class="pv2-chip">Editando</span></p>
                <p class="pv2-header-sub">Nota de crédito de cliente</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_cxc_notecredit/add" class="btn btn-default" id="btnNuevo"><i class="icon16 i-plus"></i> Nuevo</a>
            <a href="<?php echo base_url(); ?>/app_cxc_notecredit/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
            <a href="#" class="btn btn-default" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
    <form id="form-new-notecredit" name="form-new-notecredit" role="form" autocomplete="off">
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
                <input type="hidden" name="txtTransactionID" id="txtTransactionID" value="<?php echo $objTM->transactionID ?>">
                <input type="hidden" name="txtTransactionMasterID" id="txtTransactionMasterID" value="<?php echo $objTM->transactionMasterID ?>">
                <div class="tab-pane fade in active" id="home">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Datos de la nota</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDate">Fecha <span class="pv2-req">*</span></label>
                                    <div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
                                        <input size="16" class="form-control" type="text" autocomplete="off" name="txtDate" id="txtDate" value="<?php echo explode(' ', $objTM->transactionOn)[0]; ?>">
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
                                    <label class="pv2-label" for="txtAmount">Monto <span class="pv2-req">*</span></label>
                                    <input class="form-control pv2-num afx-monto afx-sin-negativo" type="text" inputmode="decimal" autocomplete="off" name="txtAmount" id="txtAmount" value="<?php echo sprintf("%01.4f", $objTM->amount); ?>">
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtCustomerDescription">Cliente <span class="pv2-req">*</span></label>
                                    <div class="input-group afx-search">
                                        <input type="hidden" id="txtCustomerEntityID" name="txtCustomerEntityID" value="<?php echo $objTM->entityID ?>">
                                        <input class="form-control" readonly id="txtCustomerDescription" type="text" autocomplete="off" value="<?php echo $objCustomer->customerNumber . " | " . $objListLegal->legalName ?>">
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
                                    <label class="pv2-label" for="txtReference1">Referencia 1</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference1" id="txtReference1" value="<?php echo $objTM->reference1 ?>">
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtReference2">Referencia 2</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference2" id="txtReference2" value="<?php echo $objTM->reference2 ?>">
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtReference3">Referencia 3</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference3" id="txtReference3" value="<?php echo $objTM->reference3 ?>">
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtComment">Comentario</label>
                                    <textarea class="form-control" id="txtComment" name="txtComment" rows="6"><?php echo $objTM->note; ?></textarea>
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
            <p class="pv2-mbar-label">Nota de crédito #<?php echo $objTM->transactionNumber ?></p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>
