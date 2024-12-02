<div class="row">
    <div id="email" class="col-lg-12">
        <div class="email-wrapper" style="padding:15px 15px 15px 15px;padding-left:15px">
            <div class="row">
                <div class="email-content col-lg-12">
                    <form id="form-new-rol" class="form-horizontal">
                        <input type="hidden" id="txtEntityRelatedID" name="txtEntityRelatedID"  value="<?php echo $objEntityRelated->ccEntityRelatedID; ?>">

                        <div class="form-group">
                            <label class="col-lg-4 control-label" for="selectFilter">Cliente</label>
                            <div class="col-lg-8">
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
                        </div>

                        <div class="form-group">
                            <label class="col-lg-4 control-label" for="selectFilter">Tipo de Relacion</label>
                            <div class="col-lg-8">
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
                        </div>

                        <div class="form-group">
                            <label class="col-lg-4 control-label" for="selectFilter">Tipo de Credito</label>
                            <div class="col-lg-8">
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
                        </div>

                        <div class="form-group">
                            <label class="col-lg-4 control-label" for="selectFilter">Estado de Credito</label>
                            <div class="col-lg-8">
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
                        </div>

                        <div class="form-group">
                            <label class="col-lg-4 control-label" for="selectFilter">Tipo de Garantia</label>
                            <div class="col-lg-8">
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
                        </div>


                        <div class="form-group">
                            <label class="col-lg-4 control-label" for="selectFilter">Tipo de Recuperacion</label>
                            <div class="col-lg-8">
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
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label" for="preapend">Tasa de Desembolso</label>
                            <div class="col-lg-10">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input class="form-control txt-numeric" id="txtRatioDesembolso" type="text" placeholder="" value="<?php echo sprintf("%01.2f",$objEntityRelated->ratioDesembolso); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label" for="preapend">Tasa de Balance</label>
                            <div class="col-lg-10">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input class="form-control txt-numeric" id="txtRatioBalance" type="text" placeholder="" value="<?php echo sprintf("%01.2f",$objEntityRelated->ratioBalance); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label" for="preapend">Balance de Expiracion</label>
                            <div class="col-lg-10">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input class="form-control txt-numeric" id="txtRatioBalanceExpired" type="text" placeholder="" value="<?php echo sprintf("%01.2f",$objEntityRelated->ratioBalanceExpired); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label" for="preapend">Tasa de Cuota</label>
                            <div class="col-lg-10">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input class="form-control txt-numeric" id="txtRatioShare" type="text" placeholder="" value="<?php echo sprintf("%01.2f",$objEntityRelated->ratioShare); ?>">
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div><!-- End .row-fluid  -->
        </div>
    </div><!-- End #email  -->
</div><!-- End .row-fluid  -->