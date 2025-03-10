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
                <a href="<?php echo base_url(); ?>/app_afx_fixedassent_depreciated/index" id="btnBack" class="btn btn-inverse"><i class="icon16 i-rotate"></i> Atras</a>
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
                <h4>Depreciacion De Activo Fijo:#<span class="invoice-num">00000000</span></h4>
            </div>
            <!-- /titulo de comprobante-->

            <!-- body -->
            <form id="form-new-fixedasset_output" name="form-new-fixedasset_output" class="form-horizontal" role="form">
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

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Comentario</label>
                                        <div class="col-lg-8">
                                            <textarea class="form-control" name="txtComment" id="txtComment" rows="6"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="selectFilter">Año</label>
                                        <div class="col-lg-8">
                                            <select name="txtYearID" id="txtYearID" class="select2">
                                                <option></option>
                                                <option></option>
                                                <?php
                                                if ($objListAccountingPeriod)
                                                    foreach ($objListAccountingPeriod as $i) {
                                                        echo "<option value='" . $i->componentPeriodID . "'>" . helper_DateToSpanish($i->startOn, "Y") . "</option>";
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

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Monto Total</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" readonly type="text" name="txtTotalAmount" id="txtTotalAmount" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
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
            <input type="hidden"  name="txtFixedAssetID[]"              id="txtFixedAssetID"  />
            <input type="hidden"  name="txtFixedAssetCodeDetail[]"      id="txtFixedAssetCodeDetail"  />
            <input type="hidden"  name="txtFixedAssetNameDetail[]"      id="txtFixedAssetNameDetail"  />
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
            <input class="form-control" readonly type="number"  name="txtFixedAssetCurrentAmount[]"   id="txtFixedAssetCurrentAmount" />        
        </td>
        <td>
            <input class="form-control" readonly type="number" name="txtFixedSettlementAmount[]" id="txtFixedSettlementAmount" value="">
        </td>
    </tr>
</script>