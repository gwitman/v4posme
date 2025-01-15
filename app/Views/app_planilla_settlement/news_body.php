<div class="row">
    <div id="email" class="col-lg-12">

        <!-- botonera -->
        <div class="email-bar" style="border-left:1px solid #c9c9c9">
            <div class="btn-group pull-right">
                <a href="<?php echo base_url(); ?>/app_planilla_settlement/index" id="btnBack" class="btn btn-inverse"><i class="icon16 i-rotate"></i> Atras</a>
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
                <h4>CODIGO:#<span class="invoice-num">00000000</span></h4>
            </div>
            <!-- /titulo de comprobante-->

            <!-- body -->
            <form id="form-new-rrhh-employee" name="form-new-rrhh-employee" class="form-horizontal" role="form">
                <div class="panel-body printArea">

                    <ul id="myTab" class="nav nav-tabs">
                        <li class="active"><a href="#home" data-toggle="tab">Datos Del Colaborador</a></li>
                        <li><a href="#profile-work" data-toggle="tab">Datos del Trabajo</a></li>
                        <li><a href="#profile-income" data-toggle="tab">Ingresos</a></li>
                        <li><a href="#profile-deduction" data-toggle="tab">Deducciones</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="home">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">

                                        <label class="col-lg-4 control-label" for="buttons">Colaborador</label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <input type="hidden" id="txtEmployeeEntityID" name="txtEmployeeEntityID" value="">
                                                <input type="hidden" id="txtTransactionOn" name="txtTransactionOn" value="">
                                                <input class="form-control" readonly id="txtEmployeeDescription" type="txtEmployeeDescription" value="">

                                                <span class="input-group-btn">
                                                    <button class="btn btn-danger" type="button" id="btnClearEmployee">
                                                        <i aria-hidden="true" class="i-undo-2"></i>
                                                        clear
                                                    </button>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button" id="btnSearchEmployee">
                                                        <i aria-hidden="true" class="i-search-5"></i>
                                                        buscar
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Codigo</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" readonly type="text" name="txtEmployeeCode" id="txtEmployeeCode" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Nombre Completo</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" readonly type="text" name="txtEmployeeFullName" id="txtEmployeeFullName" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Area</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" readonly type="text" name="txtEmployeeArea" id="txtEmployeeArea" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Liquidacion Neta</label>
                                        <div class="col-lg-8">
                                            <input class="form-control txt-employee-numeric" readonly type="text" name="txtEmployeeSettlement" id="txtEmployeeSettlement" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="selectFilter">Estado</label>
                                        <div class="col-lg-8">
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
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="profile-work">
                            <div class="row">
                                <div class="col-lg-6">

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Fecha de Inicio</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" readonly type="text" name="txtEmployeeBeginDate" id="txtEmployeeBeginDate" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="datepicker">Fecha de Fin</label>
                                        <div class="col-lg-8">
                                            <div id="datepicker" class="input-group date" data-date="" data-date-format="yyyy-mm-dd">
                                                <input size="16" class="form-control" type="text" name="txtEmployeeEndDate" id="txtEmployeeEndDate" value="">
                                                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Años Laborados</label>
                                        <div class="col-lg-8">
                                            <input class="form-control txt-employee-numeric" readonly type="text" name="txtEmployeeWorkYears" id="txtEmployeeWorkYears" value="">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-lg-6">

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Salario Devengado</label>
                                        <div class="col-lg-8">
                                            <input class="form-control txt-employee-numeric" readonly type="text" name="txtEmployeeSalary" id="txtEmployeeSalary" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Vacaciones Acumuladas</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" readonly type="text" name="txtEmployeeAccumulatedVacations" id="txtEmployeeAccumulatedVacations" value="">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="profile-income">
                            <div class="row">
                                <div class="col-lg-6">

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Salario Proporcional</label>
                                        <div class="col-lg-8">
                                            <input class="form-control txt-employee-numeric" readonly type="text" name="txtEmployeeProporcionalSalary" id="txtEmployeeProporcionalSalary" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Ahorro Acumulado</label>
                                        <div class="col-lg-8">
                                            <input class="form-control txt-employee-numeric" readonly type="text" name="txtEmployeeAmountSaving" id="txtEmployeeAmountSaving" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Comision</label>
                                        <div class="col-lg-8">
                                            <input class="form-control txt-employee-numeric" type="text" name="txtEmployeeComission" id="txtEmployeeComission" value="">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-lg-6">

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Bono</label>
                                        <div class="col-lg-8">
                                            <input class="form-control txt-employee-numeric" type="text" name="txtEmployeeBonus" id="txtEmployeeBonus" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Pago Vacaciones Acumuladas</label>
                                        <div class="col-lg-8">
                                            <input class="form-control txt-employee-numeric" readonly type="text" name="txtEmployeeAccumulatedVacationsPayment" id="txtEmployeeAccumulatedVacationsPayment" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Pago por mes 13</label>
                                        <div class="col-lg-8">
                                            <input class="form-control txt-employee-numeric" readonly type="text" name="txtEmployeePaymentMonthThirteen" id="txtEmployeePaymentMonthThirteen" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Pago por Antiguedad</label>
                                        <div class="col-lg-8">
                                            <input class="form-control txt-employee-numeric" readonly type="text" name="txtEmployeeSeniorityPayment" id="txtEmployeeSeniorityPayment" value="">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="profile-deduction">
                            <div class="row">
                                <div class="col-lg-6">

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">INSS</label>
                                        <div class="col-lg-8">
                                            <input class="form-control txt-employee-numeric" readonly type="text" name="txtEmployeeINSS" id="txtEmployeeINSS" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">IR</label>
                                        <div class="col-lg-8">
                                            <input class="form-control txt-employee-numeric" readonly type="text" name="txtEmployeeIR" id="txtEmployeeIR" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">IR Por Vacaciones</label>
                                        <div class="col-lg-8">
                                            <input class="form-control txt-employee-numeric" readonly type="text" name="txtEmployeeIRVacation" id="txtEmployeeIRVacation" value="">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-lg-6">

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Prestamos</label>
                                        <div class="col-lg-8">
                                            <input class="form-control txt-employee-numeric" type="text" name="txtEmployeeLoans" id="txtEmployeeLoans" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Llegadas Tarde</label>
                                        <div class="col-lg-8">
                                            <input class="form-control txt-employee-numeric" type="text" name="txtEmployeeLateArrival" id="txtEmployeeLateArrival" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Adelantos</label>
                                        <div class="col-lg-8">
                                            <input class="form-control txt-employee-numeric" type="text" name="txtEmployeeAdvancedPayment" id="txtEmployeeAdvancedPayment" value="">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- /body -->
        </div>
    </div>
</div>