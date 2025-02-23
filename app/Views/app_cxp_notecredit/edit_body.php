<div class="row">
    <div id="email" class="col-lg-12">

        <!-- botonera -->
        <div class="email-bar" style="border-left:1px solid #c9c9c9">
            <div class="btn-group pull-right">
            <div class="btn-group pull-right">
                <a href="<?php echo base_url(); ?>/app_cxp_notecredit/add" class="btn btn-success" id="btnNuevo"><i class="icon16 i-checkmark-4"></i>Nuevo</a>
                <a href="<?php echo base_url(); ?>/app_cxp_notecredit/index" id="btnBack" class="btn btn-inverse"><i class="icon16 i-rotate"></i> Atras</a>
                <a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
                <a href="#" class="btn btn-primary" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
                <a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
            </div>
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
                <h4>Nota:#<span class="invoice-num"><?php echo $objTM->transactionNumber ?></span></h4>
            </div>
            <!-- /titulo de comprobante-->

            <!-- body -->
            <form id="form-new-notecredit" name="form-new-notecredit" class="form-horizontal" role="form">
                <div class="panel-body printArea">

                    <ul id="myTab" class="nav nav-tabs">
                        <li class="active">
                            <a href="#home" data-toggle="tab" id="tabInformacion">Informacion</a>
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
                                <div class="col-lg-6">

                                    <div class="form-group <?php echo getBehavio($company->type, "app_box_share", "divFecha", ""); ?> ">
                                        <label class="col-lg-4 control-label" for="datepicker">Fecha</label>
                                        <div class="col-lg-8">
                                            <div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
                                                <input size="16" class="form-control" type="text" name="txtDate" id="txtDate" value="<?php echo explode(' ', $objTM->transactionOn)[0]; ?>">
                                                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="selectFilter">Estado </label>
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

                                    <div class="form-group <?php echo getBehavio($company->type, "app_cxp_notecredit", "divMoneda", ""); ?>  ">
                                        <label class="col-lg-4 control-label" for="selectFilter">Moneda</label>
                                        <div class="col-lg-8">
                                            <select name="txtCurrencyID" id="txtCurrencyID" class="select2">
                                                <option></option>
                                                <?php
                                                    if ($objListCurrency)
                                                    foreach ($objListCurrency as $ws) {
                                                        if ($ws->currencyID == $objTM->currencyID)
                                                            echo "<option value='" . $ws->currencyID . "' selected>" . $ws->name . "</option>";
                                                        else
                                                            echo "<option value='" . $ws->currencyID . "' >" . $ws->name . "</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Monto</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtAmount" id="txtAmount" value="<?php echo $objTM->amount ?>">
                                        </div>
                                    </div>

                                    <div class="form-group <?php echo getBehavio($company->type, "app_cxp_notecredit", "divCustomerControlBuscar", ""); ?> ">
                                        <label class="col-lg-4 control-label" for="buttons"><?php echo getBehavio($company->type, "app_cxp_notecredit", "lblProveedor", "Proveedor"); ?></label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <input type="hidden" id="txtCustomerID" name="txtCustomerID" value="<?php echo $objTM->entityID ?>">
                                                <input class="form-control" readonly id="txtCustomerDescription" type="txtCustomerDescription" value="<?php echo $objCustomer->providerNumber . " | " . $objListLegal->legalName ?>">

                                        
                                                <span class="input-group-btn">
                                                    <button class="btn btn-danger" type="button" id="btnClearCustomer">
                                                        <i aria-hidden="true" class="i-undo-2"></i>
                                                        clear
                                                    </button>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button" id="btnSearchCustomer">
                                                        <i aria-hidden="true" class="i-search-5"></i>
                                                        buscar
                                                    </button>
                                                </span>

                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-lg-6">                                    
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Referencia 1</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtRef1" id="" value="<?php echo $objTM->reference1 ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Referencia 2</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtRef2" id="" value="<?php echo $objTM->reference2 ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Referencia 3</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtRef3" id="" value="<?php echo $objTM->reference3 ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Comentario</label>
                                        <div class="col-lg-8">
                                            <textarea class="form-control" name="txtComment" id="txtComment" rows="6"><?php echo $objTM->note; ?></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="dropdown-file">

                        </div>
                    </div>

                    <br />

                    <a href="#" class="btn btn-flat btn-danger <?php echo getBehavio($company->type, "app_box_share", "btnVerMovimientos", "hidden"); ?> " id="btnVerMovement">
                        <i class="i-print"></i>
                        <span class="percent">Ver</span>
                        <span class="txt">movimientos</span>
                    </a>




                </div>
            </form>
            <!-- /body -->
        </div>
    </div>
</div>

<div class="row">
    <div id="email" class="col-lg-12">

        <!-- botonera -->
        <div class="email-bar" style="border-left:1px solid #c9c9c9">
            <div class="btn-group pull-right">
                <a href="<?php echo base_url(); ?>/app_cxp_notecredit/index" id="btnBack" class="btn btn-warning"><i class="icon16 i-rotate"></i> Atras</a>
                <a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
            </div>
        </div>
        <!-- /botonera -->
    </div>
    <!-- End #email  -->
</div>
<!-- End .row-fluid  -->