<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-storage-2"></i></div>
            <div>
                <p class="pv2-header-title">ASISTENCIA:#<span class="invoice-num"><?php echo $objTransactionMaster->transactionNumber; ?></span></p>
                <p class="pv2-header-sub">Revisa o corrige la asistencia del colaborador</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_rrhh_asistencia/add" class="btn btn-default" id="btnNuevo"><i class="icon16 i-plus"></i> Nuevo</a>
            <a href="<?php echo base_url(); ?>/app_rrhh_asistencia/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
    <form id="form-new-invoice" name="form-new-invoice" role="form" autocomplete="off">
        <div class="printArea">
            <input type="hidden" name="txtCompanyID" value="<?php echo $objTransactionMaster->companyID; ?>">
            <input type="hidden" name="txtTransactionID" value="<?php echo $objTransactionMaster->transactionID; ?>">
            <input type="hidden" name="txtTransactionMasterID" value="<?php echo $objTransactionMaster->transactionMasterID; ?>">

            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="pv2-card" id="home">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> ¿Quién?</p>

                        <div class="form-group">
                            <label class="pv2-label" for="txtCustomerDescription">Colaborador <span class="pv2-req">*</span></label>
                            <div class="input-group afx-search">
                                <input type="hidden" id="txtCustomerID" name="txtCustomerID" value="<?php echo $objTransactionMaster->entityID;  ?>">
                                <input class="form-control" readonly id="txtCustomerDescription" type="text" autocomplete="off" value="<?php echo $objNaturalDefault != null ? strtoupper($objCustomerDefault->customerNumber . " ". $objNaturalDefault->firstName . " ". $objNaturalDefault->lastName ) : strtoupper($objCustomerDefault->customerNumber." ".$objLegalDefault->comercialName); ?>">
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
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Registro</p>

                        <div class="form-group">
                            <label class="pv2-label" for="txtDate">Fecha <span class="pv2-req">*</span></label>
                            <div class="input-group date" data-date-format="yyyy-mm-dd">
                                <input size="16" class="form-control" type="text" autocomplete="off" name="txtDate" id="txtDate" value="<?php echo $objTransactionMaster->transactionOn; ?>">
                                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="pv2-label" for="txtPriorityID">Prioridad</label>
                            <select name="txtPriorityID" id="txtPriorityID" class="select2">
                                <option></option>
                                <?php
                                $count = 0;
                                if($objListPrioridad)
                                foreach($objListPrioridad as $price){
                                    if( $price->catalogItemID == $objTransactionMaster->priorityID )
                                        echo "<option value='".$price->catalogItemID."' selected >".$price->display."</option>";
                                    else
                                        echo "<option value='".$price->catalogItemID."'  >".$price->display."</option>";

                                    $count++;
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="pv2-label" for="txtStatusID">Estado</label>
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
                </div>
            </div>
        </div>
    </form>
    <!-- /formulario -->

    <!-- barra inferior (telefono) -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label">Asistencia #<?php echo $objTransactionMaster->transactionNumber; ?></p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>
