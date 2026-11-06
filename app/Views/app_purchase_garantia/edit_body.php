<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-storage-2"></i></div>
            <div>
                <p class="pv2-header-title">INGRESO:#<span class="invoice-num"><?php echo $objTransactionMaster->transactionNumber; ?></span> <span class="pv2-chip">Editando</span></p>
                <p class="pv2-header-sub">Actualiza la garantia, su cliente y el problema reportado</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_purchase_garantia/add" class="btn btn-default" id="btnNuevo"><i class="icon16 i-plus"></i> Nuevo</a>
            <a href="<?php echo base_url(); ?>/app_purchase_garantia/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
            <a href="#" class="btn btn-default" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
            <a href="#" class="btn btn-default" id="btnPrinterOutput"><i class="icon16 i-print"></i> Imprimir S.</a>
            <a href="#" class="btn btn-default" id="btnPrinterStiker"><i class="icon16 i-print"></i> Imprimir Sticker</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
    <form id="form-new-invoice" name="form-new-invoice" role="form" autocomplete="off">
        <div class="printArea">

            <ul id="myTab" class="nav nav-tabs">
                <li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
                <li><a href="#profile" data-toggle="tab">Referencias</a></li>
                <li><a href="#dropdown" data-toggle="tab">Comentario</a></li>
                <li id="btnClickArchivo"><a href="#dropdown-file" data-toggle="tab">Archivos</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade in active" id="home">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <input type="hidden" name="txtCompanyID" value="<?php echo $objTransactionMaster->companyID; ?>">
                            <input type="hidden" name="txtTransactionID" value="<?php echo $objTransactionMaster->transactionID; ?>">
                            <input type="hidden" name="txtTransactionMasterID" value="<?php echo $objTransactionMaster->transactionMasterID; ?>">

                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-calendar-4"></i></span> Documento</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDate">Fecha <span class="pv2-req">*</span></label>
                                    <div>
                                        <div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
                                            <input size="16" class="form-control" type="text" autocomplete="off" name="txtDate" id="txtDate" value="<?php echo $objTransactionMaster->transactionOn; ?>">
                                            <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtBranchID">Sucursal</label>
                                    <div>
                                        <select name="txtBranchID" id="txtBranchID" class="select2">
                                            <?php
                                            $counter = 0;
                                            if($objListBranch)
                                            foreach($objListBranch as $ws){
                                                if($ws->branchID == $objTransactionMaster->branchID )
                                                    echo "<option value='".$ws->branchID."' selected >".$ws->name."</option>";
                                                else
                                                    echo "<option value='".$ws->branchID."' >".$ws->name."</option>";

                                                $counter++;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_garantia","divPanelAplicado",""); ?> ">
                                    <label class="pv2-label" for="txtIsApplied">Aplicado</label>
                                    <div>
                                        <input type="checkbox" disabled name="txtIsApplied" id="txtIsApplied" value="1" <?php if($objTransactionMaster->isApplied) echo "checked"; ?> >
                                    </div>
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_garantia","divPanelCambio",""); ?>">
                                    <label class="pv2-label" for="txtExchangeRate">Cambio</label>
                                    <div>
                                        <input class="form-control" type="text" autocomplete="off" disabled="disabled" name="txtExchangeRate" id="txtExchangeRate" value="<?php echo $exchangeRate; ?>">
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_garantia","divPanelEstado",""); ?> ">
                                    <label class="pv2-label" for="txtStatusID">Estado</label>
                                    <div>
                                        <select name="txtStatusID" id="txtStatusID" class="select2">
                                            <option></option>
                                            <?php
                                            if($objListWorkflowStage)
                                            foreach($objListWorkflowStage as $ws){

                                                if($ws->workflowStageID == $objTransactionMaster->statusID)
                                                    echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
                                                else
                                                    echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtPriorityID">Status de Garantia</label>
                                    <div>
                                        <select name="txtPriorityID" id="txtPriorityID" class="select2">
                                            <?php
                                            if($objListCatalogoStatusGarantia)
                                            foreach($objListCatalogoStatusGarantia as $ws){
                                                if($ws->catalogItemID == $objTransactionMaster->priorityID)
                                                    echo "<option value='".$ws->catalogItemID."' selected>".$ws->name."</option>";
                                                else
                                                    echo "<option value='".$ws->catalogItemID."' >".$ws->name."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Personas y factura</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtCustomerDescription">Cliente <span class="pv2-req">*</span></label>
                                    <div>
                                        <div class="input-group afx-search">
                                            <input type="hidden" id="txtCustomerID" name="txtCustomerID" value="<?php echo $objCustomer->entityID;  ?>">
                                            <input class="form-control" readonly id="txtCustomerDescription" type="text" autocomplete="off" value="<?php echo $objCustomerNatural != null ? strtoupper($objCustomer->customerNumber . " ". $objCustomerNatural->firstName . " ". $objCustomerNatural->lastName ) : strtoupper($objCustomer->customerNumber." ".$objCustomerLegal->comercialName); ?>">
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

                                <div class="form-group">
                                    <label class="pv2-label" for="txtEmployerDescription">Tecnico <span class="pv2-req">*</span></label>
                                    <div>
                                        <div class="input-group afx-search">
                                            <input type="hidden" id="txtEmployerID" name="txtEmployerID" value="<?php echo $objEmployer->entityID;  ?>">
                                            <input class="form-control" readonly id="txtEmployerDescription" type="text" autocomplete="off" value="<?php echo $objEmployerNatural != null ? strtoupper($objEmployer->employeNumber . " ". $objEmployerNatural->firstName . " ". $objEmployerNatural->lastName ) : strtoupper($objEmployer->employeNumber." ".$objEmployerLegal->comercialName); ?>">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" id="btnClearEmployer">
                                                    <i aria-hidden="true" class="i-undo-2"></i>
                                                    Limpiar
                                                </button>
                                            </span>
                                            <span class="input-group-btn">
                                                <button class="btn btn-primary" type="button" id="btnSearchEmployer">
                                                    <i aria-hidden="true" class="i-search-5"></i>
                                                    Buscar
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtNoteDescription">Factura <span class="pv2-req">*</span></label>
                                    <div>
                                        <div class="input-group afx-search">
                                            <input type="hidden" id="txtNote" name="txtNote" value="<?php echo $objBilling->transactionNumber;  ?>">
                                            <input class="form-control" readonly id="txtNoteDescription" type="text" autocomplete="off" value="<?php echo $objBilling->transactionNumber; ?>">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" id="btnClearNote">
                                                    <i aria-hidden="true" class="i-undo-2"></i>
                                                    Limpiar
                                                </button>
                                            </span>
                                            <span class="input-group-btn">
                                                <button class="btn btn-primary" type="button" id="btnSearchNote">
                                                    <i aria-hidden="true" class="i-search-5"></i>
                                                    Buscar
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Producto</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtRouteID">Producto</label>
                                    <div>
                                        <select name="txtRouteID" id="txtRouteID" class="select2">
                                            <?php
                                            $counter = 0;
                                            if($objListCatalogoArticulos)
                                            foreach($objListCatalogoArticulos as $ws){
                                                if($ws->catalogItemID == $objTransactionMasterInfo->routeID)
                                                    echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                                else
                                                    echo "<option value='".$ws->catalogItemID."' >".$ws->name."</option>";

                                                $counter++;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtZoneID">Sello</label>
                                    <div>
                                        <select name="txtZoneID" id="txtZoneID" class="select2">
                                            <?php
                                            $counter = 0;
                                            if($objListCatalogoSellos)
                                            foreach($objListCatalogoSellos as $ws){
                                                if($ws->catalogItemID == $objTransactionMasterInfo->zoneID)
                                                    echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                                else
                                                    echo "<option value='".$ws->catalogItemID."' >".$ws->name."</option>";

                                                $counter++;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDetailReference1">Modelo</label>
                                    <div>
                                        <input class="form-control" type="text" autocomplete="off" name="txtDetailReference1" id="txtDetailReference1" value="<?php echo $objTransactionMaster->reference1; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDetailReference2">Serie</label>
                                    <div>
                                        <input class="form-control" type="text" autocomplete="off" name="txtDetailReference2" id="txtDetailReference2" value="<?php echo $objTransactionMaster->reference2; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-grid-2"></i></span> Detalle de la garantia</p>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_garantia","divPanelMoneda",""); ?>">
                                    <label class="pv2-label" for="txtCurrencyID">Moneda</label>
                                    <div>
                                        <select name="txtCurrencyID" id="txtCurrencyID" class="select2">
                                            <?php
                                            if($objListCurrency)
                                            foreach($objListCurrency as $ws){
                                                if($ws->currencyID == $objTransactionMaster->currencyID)
                                                    echo "<option value='".$ws->currencyID."' selected>".$ws->simb."</option>";
                                                else
                                                    echo "<option value='".$ws->currencyID."' >".$ws->simb."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_garantia","divPanelMonto",""); ?> ">
                                    <label class="pv2-label" for="txtDetailAmount">Monto</label>
                                    <div>
                                        <input class="form-control afx-monto pv2-num" inputmode="decimal" type="text" autocomplete="off" name="txtDetailAmount" id="txtDetailAmount" value="<?php echo sprintf("%01.4f",$objTransactionMaster->amount); ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDetailReference3">Problema</label>
                                    <div>
                                        <input class="form-control" type="text" autocomplete="off" name="txtDetailReference3" id="txtDetailReference3" value="<?php echo $objTransactionMaster->reference3; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDetailReference4">Solucion</label>
                                    <div>
                                        <textarea class="form-control" id="txtDetailReference4" name="txtDetailReference4" rows="6"><?php echo $objTransactionMaster->reference4; ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtAreaID">Deja cargador</label>
                                    <div>
                                        <select name="txtAreaID" id="txtAreaID" class="select2">
                                            <?php
                                            if($objListCatalogoAccesorio)
                                            foreach($objListCatalogoAccesorio as $ws){
                                                if($ws->catalogItemID == $objTransactionMaster->areaID)
                                                    echo "<option value='".$ws->catalogItemID."' selected>".$ws->name."</option>";
                                                else
                                                    echo "<option value='".$ws->catalogItemID."' >".$ws->name."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="profile">
                    <div class="row">
                        <div class="col-lg-6 currency-1"></div>
                        <div class="col-lg-6 currency-2"></div>
                    </div>
                </div>

                <div class="tab-pane fade" id="dropdown"></div>

                <div class="tab-pane fade" id="dropdown-file"></div>
            </div>

        </div>
    </form>
    <!-- /formulario -->

    <!-- barra inferior (telefono) -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label">Ingreso #<?php echo $objTransactionMaster->transactionNumber; ?></p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>
