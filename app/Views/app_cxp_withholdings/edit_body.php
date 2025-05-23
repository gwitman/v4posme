<div class="row">
    <div id="email" class="col-lg-12">
        <!-- botonera -->
        <div class="email-bar" style="border-left:1px solid #c9c9c9">
            <div class="btn-group pull-right">
                <a href="<?php echo base_url(); ?>/app_cxp_withholdings/add" class="btn btn-success" id="btnNuevo"><i class="icon16 i-checkmark-4"></i>Nuevo</a>
                <a href="<?php echo base_url(); ?>/app_cxp_withholdings/index" id="btnBack" class="btn btn-inverse"><i class="icon16 i-rotate"></i> Atras</a>
                <a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
                <a href="#" class="btn btn-primary" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
                <a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
            </div>
        </div>
        <!-- /botonera -->
    </div>
    <!-- End #email  -->
</div>
<!-- End .row-fluid  -->

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">

            <!-- titulo de comprobante-->
            <div class="panel-heading">
                <div class="icon"><i class="icon20 i-file"></i></div>
                <h4>Retencion:#<span class="invoice-num"><?php echo $objTM->transactionNumber ?></span></h4>
            </div>
            <!-- /titulo de comprobante-->

            <!-- body -->
            <form id="form-new-withholding" name="form-new-withholding" class="form-horizontal" role="form">
                <div class="panel-body printArea">

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
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="datepicker">Fecha</label>
                                        <div class="col-lg-8">
                                            <div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
                                                <input size="16" class="form-control" type="text" name="txtDate" id="txtDate" value="<?= explode(' ', $objTM->transactionOn)[0]; ?>">
                                                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="selectFilter">Estado</label>
                                        <div class="col-lg-8">
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
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="selectFilter">Moneda</label>
                                        <div class="col-lg-8">
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
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="buttons">Proveedor</label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <input type="hidden" id="txtProviderEntityID" name="txtProviderEntityID" value="<?= $objTM->entityID ?>">
                                                <input class="form-control" readonly id="txtProviderDescription" type="txtProviderDescription" value="<?= $objProvider->providerNumber . " | " . $objListNatural->firstName . " " . $objListNatural->lastName ?>">

                                                <span class="input-group-btn">
                                                    <button class="btn btn-danger" type="button" id="btnClearProvider">
                                                        <i aria-hidden="true" class="i-undo-2"></i>
                                                        clear
                                                    </button>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button" id="btnSearchProvider">
                                                        <i aria-hidden="true" class="i-search-5"></i>
                                                        buscar
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- FACTURA -->
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Factura</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtInvoice" id="txtInvoice" value="<?= $objTM->reference3 ?>">
                                        </div>
                                    </div>

                                    <!-- IMPUESTO -->
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="selectFilter">Impuesto</label>
                                        <div class="col-lg-8">
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

                                <div class="col-lg-6">

                                    <!-- MONTO DE FACTURA -->
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Monto de Factura</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtInvoiceAmount" id="txtInvoiceAmount" value="<?= $objTM->amount ?>">
                                        </div>
                                    </div>

                                    <!-- MONTO DE RETENCION -->
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Monto de Retencion</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" readonly type="text" name="txtWithholdingAmount" id="txtWithholdingAmount" value="<?= $objTM->discount ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Referencia 1</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtReference1" id="txtReference1" value="<?= $objTM->reference1 ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Referencia 2</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtReference2" id="txtReference2" value="<?= $objTM->reference2 ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Comentario</label>
                                        <div class="col-lg-8">
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
            <!-- /body -->
        </div>
    </div>
</div>