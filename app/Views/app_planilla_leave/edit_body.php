<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-clock-5"></i></div>
            <div>
                <p class="pv2-header-title">PERMISO:#<span class="invoice-num"><?php echo $objTM->transactionNumber; ?></span></p>
                <p class="pv2-header-sub">Consulta y actualiza el permiso y las fechas de ausencia del colaborador</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_planilla_leave/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atrás</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

            <!-- formulario -->
            <form id="form-new-employee-leave" name="form-new--leave" role="form" autocomplete="off">
                <div class="printArea">
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                  <div class="pv2-card" id="home">
                                    <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Colaborador</p>
                                    <div class="form-group">

                                        <label class="pv2-label">Colaborador</label>
                                        <div>
                                            <div class="input-group afx-search">
                                                <input type="hidden" id="txtTransactionID" name="txtTransactionID" value="<?php echo $objTM->transactionID ?>">
                                                <input type="hidden" id="txtTransactionMasterID" name="txtTransactionMasterID" value="<?php echo $objTM->transactionMasterID ?>">
                                                <input type="hidden" id="txtEmployeeEntityID" name="txtEmployeeEntityID" value="<?php echo $objTM->entityID ?>">
                                                <input type="hidden" id="txtTransactionOn" name="txtTransactionOn" value="">
                                                <input class="form-control" readonly id="txtEmployeeDescription" type="text" autocomplete="off" value="<?php echo $objEmployee->employeNumber . " | " . $objListNaturales->firstName . " " . $objListNaturales->lastName ?>">
                                                <input type="hidden" id="txtEndvsIntegrationDiff" name="txtEndvsIntegrationDiff" value="<?php echo $objTM->amount ?>">

                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button" id="btnClearEmployee">
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

                                    <div class="form-group">
                                        <label class="pv2-label">Código</label>
                                        <div>
                                            <input class="form-control" readonly type="text" name="txtEmployeeCode" id="txtEmployeeCode" value="<?php echo $objEmployee->employeNumber ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">Nombre Completo</label>
                                        <div>
                                            <input class="form-control" readonly type="text" name="txtEmployeeFullName" id="txtEmployeeFullName" value="<?php echo $objListNaturales->firstName . " " . $objListNaturales->lastName ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">Área</label>
                                        <div>
                                            <input class="form-control" readonly type="text" name="txtEmployeeArea" id="txtEmployeeArea" value="<?php echo $objCatalogItem->name ?>">
                                        </div>
                                    </div>
                                  </div>
                                </div>

                                <div class="col-xs-12 col-md-6">
                                  <div class="pv2-card" id="profile-leave">
                                    <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Detalles del permiso</p>

                                    <div class="form-group">
                                        <label class="pv2-label">Tipo de Permiso</label>
                                        <div>
                                            <select name="txtEmployeeTypeLeave" id="txtEmployeeTypeLeave" class="select2">
                                                <option></option>
                                                <?php
                                                if ($objListLeaveStatus)
                                                    foreach ($objListLeaveStatus as $ws) {
                                                        if ($ws->catalogItemID == $objTM->areaID)
                                                            echo "<option value='" . $ws->catalogItemID . "' selected>" . $ws->name . "</option>";
                                                        else
                                                            echo "<option value='" . $ws->catalogItemID . "' >" . $ws->name . "</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label" for="txtEmployeeLeaveStartDate">Fecha de Inicio</label>
                                        <div>
                                            <div id="datepicker" class="input-group date" data-date="" data-date-format="yyyy-mm-dd">
                                                <input size="16" class="form-control" type="text" autocomplete="off" name="txtEmployeeLeaveStartDate" id="txtEmployeeLeaveStartDate" value="<?php echo explode(' ', $objTM->transactionOn)[0]; ?>">
                                                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label" for="txtEmployeeLeaveEndDate">Fecha de Fin</label>
                                        <div>
                                            <div class="input-group date" data-date="" data-date-format="yyyy-mm-dd">
                                                <input size="16" class="form-control" type="text" autocomplete="off" name="txtEmployeeLeaveEndDate" id="txtEmployeeLeaveEndDate" value="<?php echo explode(' ', $objTM->transactionOn2)[0]; ?>">
                                                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label" for="txtEmployeeLeaveIntegrationDate">Fecha de Integración</label>
                                        <div>
                                            <div class="input-group date" data-date="" data-date-format="yyyy-mm-dd">
                                                <input size="16" class="form-control" type="text" autocomplete="off" name="txtEmployeeLeaveIntegrationDate" id="txtEmployeeLeaveIntegrationDate" value="<?php echo explode(' ', $objTM->nextVisit)[0]; ?>">
                                                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">Días</label>
                                        <div>
                                            <input class="form-control" readonly type="text" name="txtEmployeeVacationDays" id="txtEmployeeVacationDays" value="<?php echo number_format($objTM->amount, 2, '.', '') ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">Estado</label>
                                        <div>
                                            <select name="txtEmployeeLeaveStatusID" id="txtEmployeeLeaveStatusID" class="select2">
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
                                        <label class="pv2-label"><?php echo getBehavio($company->type, "app_planilla_leave", "labelNota", "Nota");  ?></label>
                                        <div>
                                            <textarea class="form-control" name="txtEmployeeLeaveNote" id="txtEmployeeLeaveNote" rows="3"><?php echo $objTM->note; ?></textarea>
                                        </div>
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
            <p class="pv2-mbar-label">Editar permiso</p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>