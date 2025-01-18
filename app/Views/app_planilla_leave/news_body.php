<div class="row">
    <div id="email" class="col-lg-12">

        <!-- botonera -->
        <div class="email-bar" style="border-left:1px solid #c9c9c9">
            <div class="btn-group pull-right">
                <a href="<?php echo base_url(); ?>/app_planilla_leave/index" id="btnBack" class="btn btn-inverse"><i class="icon16 i-rotate"></i> Atras</a>
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
            <form id="form-new-employee-leave" name="form-new--leave" class="form-horizontal" role="form">
                <div class="panel-body printArea">

                    <ul id="myTab" class="nav nav-tabs">
                        <li class="active"><a href="#home" data-toggle="tab">Datos Del Colaborador</a></li>
                        <li><a href="#profile-leave" data-toggle="tab">Detalles del Permiso</a></li>
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
                                                <input type="hidden" id="txtEndvsIntegrationDiff" name="txtEndvsIntegrationDiff" value="">

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
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="selectFilter">Estado</label>
                                        <div class="col-lg-8">
                                            <select name="txtEmployeeLeaveStatusID" id="txtEmployeeLeaveStatusID" class="select2">
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

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Dias de Vacaciones</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" readonly type="text" name="txtEmployeeVacationDays" id="txtEmployeeVacationDays" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type, "app_planilla_leave", "labelNota", "Nota");  ?></label>
                                        <div class="col-lg-8">
                                            <textarea class="form-control" type="text" name="txtEmployeeLeaveNote" id="txtEmployeeLeaveNote"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="profile-leave">
                            <div class="row">
                                <div class="col-lg-6">

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="selectFilter">Tipo de Permiso</label>
                                        <div class="col-lg-8">
                                            <select name="txtEmployeeTypeLeave" id="txtEmployeeTypeLeave" class="select2">
                                                <option></option>
                                                <?php
                                                $counter = 0;
                                                if ($objListLeaveStatus)
                                                    foreach ($objListLeaveStatus as $ws) {
                                                        if ($counter == 0)
                                                            echo "<option value='" . $ws->catalogItemID . "' selected>" . $ws->name . "</option>";
                                                        else
                                                            echo "<option value='" . $ws->catalogItemID . "' >" . $ws->name . "</option>";
                                                        $counter++;
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="datepicker">Fecha de Inicio</label>
                                        <div class="col-lg-8">
                                            <div id="datepicker" class="input-group date" data-date="" data-date-format="yyyy-mm-dd">
                                                <input size="16" class="form-control" type="text" name="txtEmployeeLeaveStartDate" id="txtEmployeeLeaveStartDate" value="">
                                                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="datepicker">Fecha de Fin</label>
                                        <div class="col-lg-8">
                                            <div id="datepicker" class="input-group date" data-date="" data-date-format="yyyy-mm-dd">
                                                <input size="16" class="form-control" type="text" name="txtEmployeeLeaveEndDate" id="txtEmployeeLeaveEndDate" value="">
                                                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="datepicker">Fecha de Integracion</label>
                                        <div class="col-lg-8">
                                            <div id="datepicker" class="input-group date" data-date="" data-date-format="yyyy-mm-dd">
                                                <input size="16" class="form-control" type="text" name="txtEmployeeLeaveIntegrationDate" id="txtEmployeeLeaveIntegrationDate" value="">
                                                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                            </div>
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