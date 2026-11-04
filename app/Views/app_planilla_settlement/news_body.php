<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-clock-5"></i></div>
            <div>
                <p class="pv2-header-title">LIQUIDACION:#<span class="invoice-num">00000000</span> <span class="pv2-chip">Nuevo</span></p>
                <p class="pv2-header-sub">Calcula los ingresos, deducciones y la liquidación neta del colaborador</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_planilla_settlement/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

            <!-- formulario -->
            <form id="form-new-rrhh-employee" name="form-new-rrhh-employee" role="form" autocomplete="off">
                <div class="printArea">
                        <div class="pv2-card" id="home">
                            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Colaborador</p>
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group">

                                        <label class="pv2-label">Colaborador</label>
                                        <div>
                                            <div class="input-group afx-search">
                                                <input type="hidden" id="txtEmployeeEntityID" name="txtEmployeeEntityID" value="">
                                                <input type="hidden" id="txtTransactionOn" name="txtTransactionOn" value="">
                                                <input class="form-control" readonly id="txtEmployeeDescription" type="text" autocomplete="off" value="">

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
                                        <label class="pv2-label">Codigo</label>
                                        <div>
                                            <input class="form-control" readonly type="text" name="txtEmployeeCode" id="txtEmployeeCode" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">Nombre Completo</label>
                                        <div>
                                            <input class="form-control" readonly type="text" name="txtEmployeeFullName" id="txtEmployeeFullName" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">Area</label>
                                        <div>
                                            <input class="form-control" readonly type="text" name="txtEmployeeArea" id="txtEmployeeArea" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group">
                                        <label class="pv2-label">Estado</label>
                                        <div>
                                            <select name="txtEmployeeSettlementStatusID" id="txtEmployeeSettlementStatusID" class="select2">
                                                <option></option>
                                                <?php
                                                $counter = 0;
                                                if ($objListWorkflowStage)
                                                    foreach ($objListWorkflowStage as $ws) {
                                                        if ($counter == 0)
                                                            echo "<option value='" . $ws->workflowStageID . "' selected>" . $ws->name . "</option>";
                                                        else
                                                            echo "<option value='" . $ws->workflowStageID . "' >" . $ws->name . "</option>";
                                                        $counter++;
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- siempre a la vista: resultado de los calculos de la liquidacion -->
                                    <div class="pv2-total">
                                        <label class="pv2-label" for="txtEmployeeSettlement">Liquidación Neta</label>
                                        <input class="form-control pv2-num txt-employee-numeric" readonly type="text" name="txtEmployeeSettlement" id="txtEmployeeSettlement" value="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pv2-card" id="profile-work">
                            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Trabajo</p>
                            <div class="row">
                                <div class="col-xs-12 col-md-6">

                                    <div class="form-group">
                                        <label class="pv2-label">Fecha de Inicio</label>
                                        <div>
                                            <input class="form-control" readonly type="text" name="txtEmployeeBeginDate" id="txtEmployeeBeginDate" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label" for="txtEmployeeEndDate">Fecha de Fin</label>
                                        <div>
                                            <div id="datepicker" class="input-group date" data-date="" data-date-format="yyyy-mm-dd">
                                                <input size="16" class="form-control" type="text" autocomplete="off" name="txtEmployeeEndDate" id="txtEmployeeEndDate" value="">
                                                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">Años Laborados</label>
                                        <div>
                                            <input class="form-control pv2-num txt-employee-numeric" readonly type="text" name="txtEmployeeWorkYears" id="txtEmployeeWorkYears" value="">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-xs-12 col-md-6">

                                    <div class="form-group">
                                        <label class="pv2-label">Salario Devengado</label>
                                        <div>
                                            <input class="form-control pv2-num txt-employee-numeric" readonly type="text" name="txtEmployeeSalary" id="txtEmployeeSalary" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">Vacaciones Acumuladas</label>
                                        <div>
                                            <input class="form-control" readonly type="text" name="txtEmployeeAccumulatedVacations" id="txtEmployeeAccumulatedVacations" value="">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="pv2-card" id="profile-income">
                            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-plus"></i></span> Ingresos</p>
                            <div class="row">
                                <div class="col-xs-12 col-md-6">

                                    <div class="form-group">
                                        <label class="pv2-label">Salario Proporcional</label>
                                        <div>
                                            <input class="form-control pv2-num txt-employee-numeric" readonly type="text" name="txtEmployeeProporcionalSalary" id="txtEmployeeProporcionalSalary" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">Ahorro Acumulado</label>
                                        <div>
                                            <input class="form-control pv2-num txt-employee-numeric" readonly type="text" name="txtEmployeeAmountSaving" id="txtEmployeeAmountSaving" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">Comision</label>
                                        <div>
                                            <input class="form-control pv2-num txt-employee-numeric" inputmode="decimal" autocomplete="off" type="text" name="txtEmployeeComission" id="txtEmployeeComission" value="">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-xs-12 col-md-6">

                                    <div class="form-group">
                                        <label class="pv2-label">Bono</label>
                                        <div>
                                            <input class="form-control pv2-num txt-employee-numeric" inputmode="decimal" autocomplete="off" type="text" name="txtEmployeeBonus" id="txtEmployeeBonus" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">Pago Vacaciones Acumuladas</label>
                                        <div>
                                            <input class="form-control pv2-num txt-employee-numeric" readonly type="text" name="txtEmployeeAccumulatedVacationsPayment" id="txtEmployeeAccumulatedVacationsPayment" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">Pago por mes 13</label>
                                        <div>
                                            <input class="form-control pv2-num txt-employee-numeric" readonly type="text" name="txtEmployeePaymentMonthThirteen" id="txtEmployeePaymentMonthThirteen" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">Pago por Antiguedad</label>
                                        <div>
                                            <input class="form-control pv2-num txt-employee-numeric" readonly type="text" name="txtEmployeeSeniorityPayment" id="txtEmployeeSeniorityPayment" value="">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="pv2-card" id="profile-deduction">
                            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-remove"></i></span> Deducciones</p>
                            <div class="row">
                                <div class="col-xs-12 col-md-6">

                                    <div class="form-group">
                                        <label class="pv2-label">INSS</label>
                                        <div>
                                            <input class="form-control pv2-num txt-employee-numeric" readonly type="text" name="txtEmployeeINSS" id="txtEmployeeINSS" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">IR</label>
                                        <div>
                                            <input class="form-control pv2-num txt-employee-numeric" readonly type="text" name="txtEmployeeIR" id="txtEmployeeIR" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">IR Por Vacaciones</label>
                                        <div>
                                            <input class="form-control pv2-num txt-employee-numeric" readonly type="text" name="txtEmployeeIRVacation" id="txtEmployeeIRVacation" value="">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-xs-12 col-md-6">

                                    <div class="form-group">
                                        <label class="pv2-label">Prestamos</label>
                                        <div>
                                            <input class="form-control pv2-num txt-employee-numeric" inputmode="decimal" autocomplete="off" type="text" name="txtEmployeeLoans" id="txtEmployeeLoans" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">Llegadas Tarde</label>
                                        <div>
                                            <input class="form-control pv2-num txt-employee-numeric" inputmode="decimal" autocomplete="off" type="text" name="txtEmployeeLateArrival" id="txtEmployeeLateArrival" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">Adelantos</label>
                                        <div>
                                            <input class="form-control pv2-num txt-employee-numeric" inputmode="decimal" autocomplete="off" type="text" name="txtEmployeeAdvancedPayment" id="txtEmployeeAdvancedPayment" value="">
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
            <p class="pv2-mbar-label">Nueva liquidación</p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>