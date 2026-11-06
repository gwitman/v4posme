<div class="pv2">
    <form id="form-new-rol" autocomplete="off" onsubmit="return false;">
        <input type="hidden" id="txtEntityRelatedID" name="txtEntityRelatedID"  value="<?php echo $objEntityRelated->ccEntityRelatedID; ?>">

        <div class="pv2-card">

            <div class="form-group">
                <label class="pv2-label" for="txtDocumentEntityID">Cliente</label>
                <select name="txtDocumentEntityID" id="txtDocumentEntityID" class="select2">
                    <option></option>
                    <?php
                    if ($objListCustomer)
                        foreach ($objListCustomer as $ws) {
                            $legalName = '';
                            foreach ($objCatalogLegalName as $name) {
                                if ($name->entityID == $ws->entityID) {
                                    $legalName = $name->legalName;
                                    break;
                                }
                            }
                            if ($objEntityRelated->entityID == $ws->entityID)
                                echo "<option value='" . $ws->entityID . "' selected>" . $ws->customerNumber . " | " . $legalName . "</option>";
                            else
                                echo "<option value='" . $ws->entityID . "' >" . $ws->customerNumber . " | " . $legalName . "</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtDocumentEntityType">Tipo de Relacion</label>
                <select name="txtDocumentEntityType" id="txtDocumentEntityType" class="select2">
                    <option></option>
                    <?php
                    if ($objListCatalogEntityType)
                        foreach ($objListCatalogEntityType as $ws) {
                            if ($objEntityRelated->type == $ws->catalogItemID)
                                echo "<option value='" . $ws->catalogItemID . "' selected>" . $ws->name . "</option>";
                            else
                                echo "<option value='" . $ws->catalogItemID . "' >" . $ws->name . "</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtDocumentEntityTypeCredit">Tipo de Credito</label>
                <select name="txtDocumentEntityTypeCredit" id="txtDocumentEntityTypeCredit" class="select2">
                    <option></option>
                    <?php
                    if ($objListCatalogEntityTypeCredit)
                        foreach ($objListCatalogEntityTypeCredit as $ws) {
                            if ($objEntityRelated->typeCredit == $ws->catalogItemID)
                                echo "<option value='" . $ws->catalogItemID . "' selected>" . $ws->name . "</option>";
                            else
                                echo "<option value='" . $ws->catalogItemID . "' >" . $ws->name . "</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtDocumentEntityStatusCredit">Estado de Credito</label>
                <select name="txtDocumentEntityStatusCredit" id="txtDocumentEntityStatusCredit" class="select2">
                    <option></option>
                    <?php
                    if ($objListCatalogEntityStatusCredit)
                        foreach ($objListCatalogEntityStatusCredit as $ws) {
                            if ($objEntityRelated->statusCredit == $ws->catalogItemID)
                                echo "<option value='" . $ws->catalogItemID . "' selected>" . $ws->name . "</option>";
                            else
                                echo "<option value='" . $ws->catalogItemID . "' >" . $ws->name . "</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtDocumentEntityTypeGarantia">Tipo de Garantia</label>
                <select name="txtDocumentEntityTypeGarantia" id="txtDocumentEntityTypeGarantia" class="select2">
                    <option></option>
                    <?php
                    if ($objListCatalogEntityTypeGarantia)
                        foreach ($objListCatalogEntityTypeGarantia as $ws) {
                            if ($objEntityRelated->typeGarantia == $ws->catalogItemID)
                                echo "<option value='" . $ws->catalogItemID . "' selected>" . $ws->name . "</option>";
                            else
                                echo "<option value='" . $ws->catalogItemID . "' >" . $ws->name . "</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtDocumentEntityTypeRecuperation">Tipo de Recuperacion</label>
                <select name="txtDocumentEntityTypeRecuperation" id="txtDocumentEntityTypeRecuperation" class="select2">
                    <option></option>
                    <?php
                    if ($objListCatalogEntityTypeRecuperation)
                        foreach ($objListCatalogEntityTypeRecuperation as $ws) {
                            if ($objEntityRelated->typeRecuperation == $ws->catalogItemID)
                                echo "<option value='" . $ws->catalogItemID . "' selected>" . $ws->name . "</option>";
                            else
                                echo "<option value='" . $ws->catalogItemID . "' >" . $ws->name . "</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtRatioDesembolso">Tasa de Desembolso</label>
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input class="form-control txt-numeric afx-monto pv2-num" id="txtRatioDesembolso" type="text" inputmode="decimal" autocomplete="off" placeholder="" value="<?php echo sprintf("%01.4f",$objEntityRelated->ratioDesembolso); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtRatioBalance">Tasa de Balance</label>
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input class="form-control txt-numeric afx-monto pv2-num" id="txtRatioBalance" type="text" inputmode="decimal" autocomplete="off" placeholder="" value="<?php echo sprintf("%01.4f",$objEntityRelated->ratioBalance); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtRatioBalanceExpired">Balance de Expiracion</label>
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input class="form-control txt-numeric afx-monto pv2-num" id="txtRatioBalanceExpired" type="text" inputmode="decimal" autocomplete="off" placeholder="" value="<?php echo sprintf("%01.4f",$objEntityRelated->ratioBalanceExpired); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtRatioShare">Tasa de Cuota</label>
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input class="form-control txt-numeric afx-monto pv2-num" id="txtRatioShare" type="text" inputmode="decimal" autocomplete="off" placeholder="" value="<?php echo sprintf("%01.4f",$objEntityRelated->ratioShare); ?>">
                </div>
            </div>

        </div>
    </form>
</div>
