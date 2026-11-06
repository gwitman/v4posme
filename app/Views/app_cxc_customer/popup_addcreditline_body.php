<div class="pv2">
    <form id="form-new-rol" autocomplete="off" onsubmit="return false;">
        <div class="pv2-card">
            <div class="form-group">
                <label class="pv2-label" for="txtNumber">Numero</label>
                <input class="form-control" id="txtNumber" readonly type="text" autocomplete="off" placeholder="">
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtCreditLineID">Linea</label>
                <select name="txtCreditLineID" id="txtCreditLineID" class="select2">
                    <option></option>
                    <?php
                    $count = 0;
                    if($objListLine)
                    foreach($objListLine as $ws){
                        if($count == 0)
                        echo "<option value='".$ws->creditLineID."' selected>".$ws->name."</option>";
                        else
                        echo "<option value='".$ws->creditLineID."' >".$ws->name."</option>";
                        $count++;
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtCurrencyID">Moneda</label>
                <select name="txtCurrencyID" id="txtCurrencyID" class="select2">
                    <option></option>
                    <?php
                    if($objCurrencyList)
                    foreach($objCurrencyList as $ws){
                        if($ws->name == $objParameterCurrenyDefault)
                        echo "<option value='".$ws->currencyID."' selected >".$ws->name."</option>";
                        else
                        echo "<option value='".$ws->currencyID."' >".$ws->name."</option>";
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
                        echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtLimitCredit">Limite de Credito</label>
                <input class="form-control pv2-num afx-monto txt-numeric" id="txtLimitCredit" type="text" inputmode="decimal" autocomplete="off" placeholder="" value="30000000.00">
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtLimitBalance">Balance</label>
                <input class="form-control pv2-num afx-monto txt-numeric" id="txtLimitBalance" type="text" inputmode="decimal" autocomplete="off" placeholder="" value="30000000.00">
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtInteresYear">Interes Anual (%)</label>
                <input class="form-control pv2-num afx-monto txt-numeric" id="txtInteresYear" type="text" inputmode="decimal" autocomplete="off" value="<?php echo $objParameterInteresDefault; ?>">
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtPeriodPay">Frecuencia de Pago</label>
                <select name="txtPeriodPay" id="txtPeriodPay" class="select2">
                    <option></option>
                    <?php
                    if($objListPay)
                    foreach($objListPay as $ws){

                        if($ws->catalogItemID == $objParameterPayDefault)
                        echo "<option value='".$ws->catalogItemID."' selected data-val='".$ws->sequence."' >".$ws->name."</option>";
                        else
                        echo "<option value='".$ws->catalogItemID."'  data-val='".$ws->sequence."' >".$ws->name."</option>";

                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtDayExcluded">Dias Excluidos</label>
                <select name="txtDayExcluded" id="txtDayExcluded" class="select2">
                    <option></option>
                    <?php
                    if($objListDayExcluded)
                    foreach($objListDayExcluded as $ws){

                        if($ws->catalogItemID == $objParameterCXC_DAY_EXCLUDED_IN_CREDIT )
                            echo "<option value='".$ws->catalogItemID."' selected data-val='".$ws->sequence."' >".$ws->name."</option>";
                        else
                            echo "<option value='".$ws->catalogItemID."' data-val='".$ws->sequence."' >".$ws->name."</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtTypeAmorization">Plan</label>
                <select name="txtTypeAmorization" id="txtTypeAmorization" class="select2">
                    <option></option>
                    <?php
                    if($objListTypeAmortization)
                    foreach($objListTypeAmortization as $ws){
                        if($ws->catalogItemID == $objParameterAmortizationDefault)
                        echo "<option value='".$ws->catalogItemID."' selected data-val='".$ws->sequence."' >".$ws->name."</option>";
                        else
                        echo "<option value='".$ws->catalogItemID."'  data-val='".$ws->sequence."' >".$ws->name."</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtTerm">No Pagos</label>
                <input class="form-control pv2-num afx-monto txt-numeric" id="txtTerm" type="text" inputmode="decimal" autocomplete="off" placeholder="" value="<?php echo $objParameterCXC_PLAZO_DEFAULT; ?>">
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtNote">Nota</label>
                <input class="form-control" id="txtNote" type="text" autocomplete="off" placeholder="" value="Ninguna">
            </div>
        </div>
    </form>
</div>
