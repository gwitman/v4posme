<div class="pv2">
    <form id="form-new-rol" autocomplete="off" onsubmit="return false;">
        <input type="hidden" id="customerCreditLineID" name="customerCreditLineID" value="<?php echo $objCustomerCreditLine->customerCreditLineID; ?>">

        <div class="pv2-card">
            <div class="form-group">
                <label class="pv2-label" for="txtNumber">Numero</label>
                <input class="form-control" id="txtNumber" readonly type="text" autocomplete="off" placeholder="" value="<?php echo $objCustomerCreditLine->accountNumber; ?>">
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtCreditLineID">Linea</label>
                <select name="txtCreditLineID" id="txtCreditLineID" class="select2">
                    <option></option>
                    <?php
                    if($objListLine)
                    foreach($objListLine as $ws){
                        if($objCustomerCreditLine->creditLineID == $ws->creditLineID)
                            echo "<option value='".$ws->creditLineID."' selected>".$ws->name."</option>";
                        else
                            echo "<option value='".$ws->creditLineID."' >".$ws->name."</option>";
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
                        if($objCustomerCreditLine->currencyID == $ws->currencyID)
                            echo "<option value='".$ws->currencyID."' selected>".$ws->name."</option>";
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
                        if($objCustomerCreditLine->statusID == $ws->workflowStageID)
                            echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
                        else
                            echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtLimitCredit">Limite de Credito</label>
                <input class="form-control pv2-num afx-monto txt-numeric" id="txtLimitCredit" type="text" inputmode="decimal" autocomplete="off" placeholder="" value="<?php echo sprintf("%01.4f",$objCustomerCreditLine->limitCredit); ?>">
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtLimitBalance">Balance</label>
                <input class="form-control pv2-num afx-monto txt-numeric" id="txtLimitBalance" type="text" inputmode="decimal" autocomplete="off" placeholder="" value="<?php echo sprintf("%01.4f",$objCustomerCreditLine->balance); ?>">
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtInteresYear">Interes Anual (%)</label>
                <input class="form-control pv2-num afx-monto txt-numeric" id="txtInteresYear" type="text" inputmode="decimal" autocomplete="off" placeholder="" value="<?php echo sprintf("%01.4f",$objCustomerCreditLine->interestYear); ?>">
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtPeriodPay">Frecuencia de Pago</label>
                <select name="txtPeriodPay" id="txtPeriodPay" class="select2">
                    <option></option>
                    <?php
                    if($objListPay)
                    foreach($objListPay as $ws){

                        if($objCustomerCreditLine->periodPay == $ws->catalogItemID)
                            echo "<option value='".$ws->catalogItemID."' selected data-val='".$ws->sequence."' >".$ws->name."</option>";
                        else
                            echo "<option value='".$ws->catalogItemID."' data-val='".$ws->sequence."' >".$ws->name."</option>";
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

                        if($objCustomerCreditLine->dayExcluded == $ws->catalogItemID)
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

                        if($objCustomerCreditLine->typeAmortization == $ws->catalogItemID)
                            echo "<option value='".$ws->catalogItemID."' selected data-val='".$ws->sequence."' >".$ws->name."</option>";
                        else
                            echo "<option value='".$ws->catalogItemID."' data-val='".$ws->sequence."' >".$ws->name."</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtTerm">No Pagos</label>
                <input class="form-control pv2-num afx-monto txt-numeric" id="txtTerm" type="text" inputmode="decimal" autocomplete="off" placeholder="" value="<?php echo sprintf("%01.4f",$objCustomerCreditLine->term); ?>">
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtNote">Nota</label>
                <input class="form-control" id="txtNote" type="text" autocomplete="off" placeholder="" value="<?php echo $objCustomerCreditLine->note; ?>">
            </div>
        </div>
    </form>
</div>
