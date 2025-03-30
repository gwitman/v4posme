<div class="row">
    <div id="email" class="col-lg-12">

        <!-- botonera -->
        <div class="email-bar" style="border-left:1px solid #c9c9c9">
            <div class="btn-group pull-right">
                <a href="<?php echo base_url(); ?>/app_tools_endorsements/index" id="btnBack" class="btn btn-inverse"><i
                        class="icon16 i-rotate"></i> Atras</a>
                <a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
            </div>
        </div>
        <!-- /botonera -->
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">

            <!-- titulo de comprobante-->
            <div class="panel-heading">
                <div class="icon"><i class="icon20 i-file"></i></div>
                <h4>ENDOSO:#<span class="invoice-num">00000000</span></h4>
            </div>

            <!-- body -->
            <form id="form-new-endoso" name="form-new-endoso" class="form-horizontal" role="form">
                <div class="panel-body printArea">
                    <ul id="myTab" class="nav nav-tabs">
                        <li class="active">
                            <a href="#home" data-toggle="tab">Informaci&oacute;n</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Informacion -->
                        <div class="tab-pane fade in active" id="home">
                            <div class="form-group">
                                <label class="col-lg-4 control-label" for="txtDate">Fecha</label>
                                <div class="col-lg-8">
                                    <div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
                                        <input size="16" class="form-control" type="text" name="txtDate" id="txtDate"/>
                                        <span class="input-group-addon">
                                            <i class="icon16 i-calendar-4"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 control-label" for="txtStatusID">Estado</label>
                                <div class="col-lg-8">
                                    <select class="select2" id="txtStatusID" name="txtStatusID">
                                        <?php
                                        if (isset($objListWorkflowStage))
                                            foreach($objListWorkflowStage as $tr){
                                                echo '<option value="'.$tr->workflowStageID.'">'.$tr->name.'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-4 control-label" for="txtTransactionID">Tipo de Transacción</label>
                                <div class="col-lg-8">
                                    <select class="select2" name="txtTransactionID" id="txtTransactionID">
                                        <?php
                                        if (isset($tipoTransaccion))
                                            foreach($tipoTransaccion as $tr){
                                                echo '<option value="'.$tr->transactionID.'">'.$tr->name.'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 control-label" for="txtTransactionNumberDescription">Documento</label>
                                <div class="input-group col-lg-8">
                                    <input type="hidden" id="txtTransactionNumber" name="txtTransactionNumber" value="" />
                                    <input type="hidden" id="txtTransactionMasterIDEndoso" name="txtTransactionMasterIDEndoso" value="" />
                                    <input class="form-control" readonly id="txtTransactionNumberDescription" type="text" value="" />
                                    <span class="input-group-btn">
                                    <button class="btn btn-danger" type="button" id="btnClearDocument">
                                        <i aria-hidden="true" class="i-undo-2"></i>
                                        Clear
                                    </button>
                                </span>
                                    <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button" id="btnSearchDocument">
                                        <i aria-hidden="true" class="i-search-5"></i>
                                        Buscar
                                    </button>
                                </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 control-label" for="txtValorModificar">Valor a Modificar</label>
                                <div class="col-lg-8">
                                    <select class="select2" id="txtValorModificar" name="txtValorModificar">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" id="txtSelectedTextValorAnterior" name="txtSelectedTextValorAnterior" value="">
                                <label class="col-lg-4 control-label">Valor anterior</label>
                                <div class="col-lg-8">
                                    <div id="contenedorDinamicoValorAnterior"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" id="txtSelectedTextValorNuevo" name="txtSelectedTextValorNuevo" value="">
                                <label class="col-lg-4 control-label">Valor Nuevo</label>
                                <div class="col-lg-8">
                                    <div id="contenedorDinamicoValorNuevo"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
