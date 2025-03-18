<div class="row">
    <div id="email" class="col-lg-12">
        <!-- botonera -->
        <div class="email-bar" style="border-left:1px solid #c9c9c9">
            <div class="btn-group pull-right">
                <a href="<?php echo base_url(); ?>/app_bank_cheque_make/index" id="btnBack" class="btn btn-inverse"><i class="icon16 i-rotate"></i> Atras</a>
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
                <h4>Emision De Cheque:#<span class="invoice-num">00000000</span></h4>
            </div>
            <!-- /titulo de comprobante-->

            <!-- body -->
            <form id="form-new-emision" name="form-new-emision" class="form-horizontal" role="form">
                <div class="panel-body printArea">

                    <ul id="myTab" class="nav nav-tabs">
                        <li class="active">
                            <a href="#home" data-toggle="tab">Informacion</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="home">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="datepicker">Fecha</label>
                                        <div class="col-lg-8">
                                            <div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
                                                <input size="16" class="form-control" type="text" name="txtDate" id="txtDate">
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
                                                        echo "<option value='" . $ws->workflowStageID . "' selected>" . $ws->name . "</option>";
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
                                                        if ($ws->currencyID == $objCurrency->currencyID)
                                                            echo "<option value='" . $ws->currencyID . "' selected>" . $ws->simb . "</option>";
                                                        else
                                                            echo "<option value='" . $ws->currencyID . "' >" . $ws->simb . "</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- CHEQUERA  -->
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="buttons">Chequera</label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <input type="hidden" id="txtCheckbookID" name="txtCheckbookID" value="">
                                                <input class="form-control" readonly id="txtCheckbookDescription" type="txtCheckbookDescription" value="">

                                                <span class="input-group-btn">
                                                    <button class="btn btn-danger" type="button" id="btnCleaCheckbook">
                                                        <i aria-hidden="true" class="i-undo-2"></i>
                                                        clear
                                                    </button>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button" id="btnSearcCheckbook">
                                                        <i aria-hidden="true" class="i-search-5"></i>
                                                        buscar
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contador de Cheque -->
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Numero de Cheque</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" readonly type="text" name="txtCheckbookCounterDescription" id="txtCheckbookCounterDescription" value="">
                                            <input type="hidden" name="txtCheckbookCounter" id="txtCheckbookCounter" value="">
                                            <input type="hidden" name="txtCheckbookCounterLimit" id="txtCheckbookCounterLimit" value="">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Monto</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="number" name="txtAmount" id="txtAmount" value="">
                                        </div>
                                    </div>

                                    <!-- Pagar A: -->
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Pagar A:</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtReceiver" id="txtReceiver" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Referencia 1</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtReference1" id="txtReference1" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Referencia 2</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtReference2" id="txtReference2" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Referencia 3</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtReference3" id="txtReference3" value="">
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