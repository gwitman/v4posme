<style>
    #tb_transaction_master_detail {
        table-layout: fixed;
        width: 100%;
    }

    #tb_transaction_master_detail th,
    #tb_transaction_master_detail td {
        width: 20em;
        word-wrap: break-word;
    }

    #tb_transaction_master_detail thead {
        position: sticky;
        top: 0;
        z-index: 2;
    }

    #div_transaction_master_detail {
        height: 500px;
        overflow-y: auto;
        overflow-x: auto;
    }
</style>
<div class="row">
    <div id="email" class="col-lg-12">
        <!-- botonera -->
        <div class="email-bar" style="border-left:1px solid #c9c9c9">
            <div class="btn-group pull-right">
                <a href="<?php echo base_url(); ?>/app_afx_fixedassent_valorated/add" class="btn btn-success" id="btnNuevo"><i class="icon16 i-checkmark-4"></i>Nuevo</a>
                <a href="<?php echo base_url(); ?>/app_afx_fixedassent_valorated/index" id="btnBack" class="btn btn-inverse"><i class="icon16 i-rotate"></i> Atras</a>
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
                <h4>Valoracion De Activo Fijo:#<span class="invoice-num"><?= $objTM->transactionNumber ?></span></h4>
            </div>
            <!-- /titulo de comprobante-->

            <!-- body -->
            <form id="form-new-fixedasset_output" name="form-new-fixedasset_output" class="form-horizontal" role="form">
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
                                                <input size="16" class="form-control" type="text" name="txtDate" id="txtDate" value="<?= explode(" ", $objTM->transactionOn)[0] ?>">
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
                                                        if ($objTM->statusID == $ws->workflowStageID) {
                                                            echo "<option value='" . $ws->workflowStageID . "' selected>" . $ws->name . "</option>";
                                                        } else {
                                                            echo "<option value='" . $ws->workflowStageID . "' >" . $ws->name . "</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
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
                                        <label class="col-lg-4 control-label" for="normal">Referencia 3</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtReference3" id="txtReference3" value="<?= $objTM->reference3 ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Comentario</label>
                                        <div class="col-lg-8">
                                            <textarea class="form-control" name="txtComment" id="txtComment" rows="6"><?= $objTM->note ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="selectFilter">Año</label>
                                        <div class="col-lg-8">
                                            <select name="txtYearID" id="txtYearID" class="select2">
                                                <option></option>
                                                <?php
                                                if ($objListAccountingPeriod)
                                                    foreach ($objListAccountingPeriod as $ly) {
                                                        if ($ly->componentPeriodID == $objTM->classID) {
                                                            echo "<option value='" . $ly->componentPeriodID . "' selected >" . helper_DateToSpanish($ly->startOn, "Y") . "</option>";
                                                        } else {
                                                            echo "<option value='" . $ly->componentPeriodID . "'  >" . helper_DateToSpanish($ly->startOn, "Y") . "</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="selectFilter">Mes</label>
                                        <div class="col-lg-8">
                                            <select name="txtMonthID" id="txtMonthID" class="select2">
                                                <option></option>
                                                <?php
                                                if ($objListAccountingCycle)
                                                    foreach ($objListAccountingCycle as $lm) {
                                                        if ($lm->componentCycleID == $objTM->areaID) {
                                                            echo "<option value='" . $lm->componentCycleID . "' selected >" . $lm->startOnFormat . "</option>";
                                                        } else {
                                                            echo "<option value='" . $lm->componentCycleID . "'  >" . $lm->startOnFormat . "</option>";
                                                        }
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
                                        <label class="col-lg-4 control-label" for="normal">Monto Total</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" readonly type="text" name="txtTotalAmount" id="txtTotalAmount" value="<?= $objTM->amount ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="dropdown-file">

                        </div>
                    </div>

                    <br>
                    <a href="#" class="btn btn-flat btn-info" id="btnNewDetailTransaction">Agregar</a>
                    <a href="#" class="btn btn-flat btn-danger" id="btnDeleteDetailTransaction">Eliminar</a>
                    <div class="row">
                        <div class="col-lg-12">
                            <h3>Detalle:</h3>
                            <div id="div_transaction_master_detail">
                                <table id="tb_transaction_master_detail" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 40px;"></th>
                                            <th>Codigo</th>
                                            <th>Nombre</th>
                                            <th>Ratio</th>
                                            <th>Monto Corriente</th>
                                            <th>Monto de liquidacion</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body_tb_transaction_master_detail">
                                        <?php
                                        if ($objTMD) {
                                            foreach ($objTMD as $detail) {
                                        ?>
                                                <tr class="row_razon">
                                                    <td class="td-checkbox">
                                                        <input type="checkbox" style="width: 20px; height:20px; display: block; margin: auto;" class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
                                                        <input type="hidden" name="txtCurrentFixedAssetID[]" id="txtCurrentFixedAssetID" value="<?= $detail->tmdID ?>" data-assetID="<?= $detail->fixedAssetID ?>" />
                                                    </td>
                                                    <td>
                                                        <text id="txtFixedAssetCode"><?= $detail->fixedAssetCode ?></text>
                                                    </td>
                                                    <td>
                                                        <text id="txtFixedAssetName"><?= $detail->fixedAssetName ?></text>
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="number" name="txtFixedAssetRatio[]" id="txtFixedAssetRatio" value="<?= $detail->amountRatio ?>">
                                                    </td>
                                                    <td>
                                                        <input class="form-control" readonly type="number" name="txtFixedAssetCurrentAmount[]" id="txtFixedAssetCurrentAmount" value="<?= $detail->currentAmount ?>">
                                                    </td>
                                                    <td>
                                                        <input class="form-control" readonly type="number" name="txtFixedSettlementAmount[]" id="txtFixedSettlementAmount" value="<?= $detail->settlementAmount ?>">
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- /body -->
        </div>
    </div>
</div>

<!-- Zona de generacion de la tabla de detalles de transaction_master -->
<script type="text/template" id="tmpl_row_fixedasset">
    <tr class="row_razon">
        <td class="td-checkbox">
            <input type="checkbox" style="width: 20px; height:20px; display: block; margin: auto;"  class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
            <input type="hidden"  name="txtNewFixedAssetID[]"   id="txtNewFixedAssetID"  />
        </td>
        <td>
            <span id="txtFixedAssetCode"></span>
        </td>
        <td>
            <span id="txtFixedAssetName"></span>
        </td>
        <td>
            <input class="form-control" type="number" name="txtFixedAssetRatio[]" id="txtFixedAssetRatio" value="">
        </td>
        <td>
            <input class="form-control" readonly type="number"  name="txtFixedAssetCurrentAmount[]"   id="txtFixedAssetCurrentAmount" value=""/>        
        </td>
        <td>
            <input class="form-control" readonly type="number" name="txtFixedSettlementAmount[]" id="txtFixedSettlementAmount" value="">
        </td>
    </tr>
</script>