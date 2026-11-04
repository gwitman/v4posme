<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-storage-2"></i></div>
            <div>
                <p class="pv2-header-title">Valoración de Activo Fijo <span class="pv2-chip">#<span class="invoice-num"><?= $objTM->transactionNumber ?></span></span></p>
                <p class="pv2-header-sub">Registra la valoración de los activos en un período</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_afx_fixedassent_valorated/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="<?php echo base_url(); ?>/app_afx_fixedassent_valorated/add" class="btn btn-default" id="btnNuevo"><i class="icon16 i-plus"></i> Nuevo</a>
            <button type="button" class="btn btn-default" id="btnClickArchivo"><i class="icon16 i-file"></i> Archivos</button>
            <button type="button" class="btn btn-default" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</button>
            <button type="button" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</button>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
            <form id="form-new-fixedasset_output" name="form-new-fixedasset_output" role="form" autocomplete="off">
                <div class="printArea">
                        <input type="hidden" name="txtTransactionID" id="txtTransactionID" value="<?= $objTM->transactionID ?>">
                        <input type="hidden" name="txtTransactionMasterID" id="txtTransactionMasterID" value="<?= $objTM->transactionMasterID ?>">

                            <div class="row" id="home">
                                <div class="col-xs-12 col-md-6">
                                  <div class="pv2-card">
                                    <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Datos del documento</p>
                                    <div class="form-group">
                                        <label class="pv2-label" for="txtDate">Fecha</label>
                                        <div>
                                            <div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
                                                <input size="16" class="form-control" type="text" autocomplete="off" name="txtDate" id="txtDate" value="<?= explode(" ", $objTM->transactionOn)[0] ?>">
                                                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">Estado</label>
                                        <div>
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
                                        <label class="pv2-label">Referencia 1</label>
                                        <div>
                                            <input class="form-control" type="text" autocomplete="off" name="txtReference1" id="txtReference1" value="<?= $objTM->reference1 ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">Referencia 2</label>
                                        <div>
                                            <input class="form-control" type="text" autocomplete="off" name="txtReference2" id="txtReference2" value="<?= $objTM->reference2 ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">Referencia 3</label>
                                        <div>
                                            <input class="form-control" type="text" autocomplete="off" name="txtReference3" id="txtReference3" value="<?= $objTM->reference3 ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">Comentario</label>
                                        <div>
                                            <textarea class="form-control" name="txtComment" id="txtComment" rows="6"><?= $objTM->note ?></textarea>
                                        </div>
                                    </div>
                                  </div>
                                </div>

                                <div class="col-xs-12 col-md-6">
                                  <div class="pv2-card">
                                    <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-calendar-4"></i></span> Período y monto</p>
                                    <div class="form-group">
                                        <label class="pv2-label">Año</label>
                                        <div>
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
                                        <label class="pv2-label">Mes</label>
                                        <div>
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
                                        <label class="pv2-label">Moneda</label>
                                        <div>
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
                                        <label class="pv2-label">Monto Total</label>
                                        <div>
                                            <input class="form-control pv2-num afx-monto" readonly type="text" inputmode="decimal" autocomplete="off" name="txtTotalAmount" id="txtTotalAmount" value="<?= $objTM->amount ?>">
                                        </div>
                                    </div>
                                  </div>
                                </div>
                            </div>

                    <!-- detalle: las filas existentes van primero; el servidor lee ratio y monto por posicion -->
                    <div class="pv2-card">
                        <div class="pv2-detail-head">
                            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Activos <span class="pv2-count" id="afxDetailCount">0</span></p>
                            <div class="pv2-actions">
                                <button type="button" class="btn btn-default btn-sm" id="btnDeleteDetailTransaction"><i class="icon16 i-remove"></i> Quitar marcados</button>
                                <button type="button" class="btn btn-primary btn-sm" id="btnNewDetailTransaction"><i class="icon16 i-plus"></i> Agregar activo</button>
                            </div>
                        </div>
                        <div id="div_transaction_master_detail" class="pv2-table-wrap">
                            <table id="tb_transaction_master_detail" class="pv2-table">
                                <thead>
                                    <tr>
                                        <th class="afx-col-quitar"></th>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Ratio</th>
                                        <th>Monto corriente</th>
                                        <th>Monto de liquidación</th>
                                    </tr>
                                </thead>
                                <tbody id="body_tb_transaction_master_detail">
                                    <?php
                                    if ($objTMD) {
                                        foreach ($objTMD as $detail) {
                                    ?>
                                            <tr class="row_razon">
                                                <td class="td-checkbox" data-label="Quitar">
                                                    <input type="checkbox" class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
                                                    <input type="hidden" name="txtCurrentFixedAssetID[]" id="txtCurrentFixedAssetID" value="<?= $detail->tmdID ?>" data-assetID="<?= $detail->fixedAssetID ?>" />
                                                </td>
                                                <td data-label="Código">
                                                    <text id="txtFixedAssetCode"><?= $detail->fixedAssetCode ?></text>
                                                </td>
                                                <td data-label="Nombre">
                                                    <text id="txtFixedAssetName"><?= $detail->fixedAssetName ?></text>
                                                </td>
                                                <td data-label="Ratio">
                                                    <input class="form-control pv2-num afx-monto" type="text" inputmode="decimal" autocomplete="off" name="txtFixedAssetRatio[]" id="txtFixedAssetRatio" value="<?= $detail->amountRatio ?>">
                                                </td>
                                                <td data-label="Monto corriente">
                                                    <input class="form-control pv2-num afx-monto" readonly type="text" inputmode="decimal" autocomplete="off" name="txtFixedAssetCurrentAmount[]" id="txtFixedAssetCurrentAmount" value="<?= $detail->currentAmount ?>">
                                                </td>
                                                <td data-label="Monto de liquidación">
                                                    <input class="form-control pv2-num afx-monto" readonly type="text" inputmode="decimal" autocomplete="off" name="txtFixedSettlementAmount[]" id="txtFixedSettlementAmount" value="<?= $detail->settlementAmount ?>">
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="pv2-empty" id="afxDetailEmpty">
                            <i class="icon20 i-file-plus"></i>
                            Este documento no tiene activos. Toca <b>Agregar activo</b> para empezar.
                        </div>
                    </div>
                    <!-- /detalle -->
                </div>
            </form>
    <!-- /formulario -->

    <!-- barra inferior (telefono) -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label">Editar valoración</p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>

<!-- Zona de generacion de la tabla de detalles de transaction_master -->
<script type="text/template" id="tmpl_row_fixedasset">
    <tr class="row_razon">
        <td class="td-checkbox" data-label="Quitar">
            <input type="checkbox" class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
            <input type="hidden"  name="txtNewFixedAssetID[]"   id="txtNewFixedAssetID"  />
        </td>
        <td data-label="Código">
            <span id="txtFixedAssetCode"></span>
        </td>
        <td data-label="Nombre">
            <span id="txtFixedAssetName"></span>
        </td>
        <td data-label="Ratio">
            <input class="form-control pv2-num afx-monto" type="text" inputmode="decimal" autocomplete="off" name="txtFixedAssetRatio[]" id="txtFixedAssetRatio" value="">
        </td>
        <td data-label="Monto corriente">
            <input class="form-control pv2-num afx-monto" readonly type="text" inputmode="decimal" autocomplete="off" name="txtFixedAssetCurrentAmount[]" id="txtFixedAssetCurrentAmount" value=""/>
        </td>
        <td data-label="Monto de liquidación">
            <input class="form-control pv2-num afx-monto" readonly type="text" inputmode="decimal" autocomplete="off" name="txtFixedSettlementAmount[]" id="txtFixedSettlementAmount" value="">
        </td>
    </tr>
</script>