<div class="pv2">
    <form id="form-new-rol" autocomplete="off" onsubmit="return false;">
        <div class="pv2-card">
            <div class="form-group">
                <label class="pv2-label" for="txtEntityPhoneTypeID">Tipo</label>
                <select name="txtEntityPhoneTypeID" id="txtEntityPhoneTypeID" class="select2">
                    <?php
                    $count = 0;
                    if($objListPhoneTypeID)
                    foreach($objListPhoneTypeID as $ws){
                        if($count == 0 )
                        echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                        else
                        echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                        $count++;
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtEntityPhoneNumber">Telefono <span class="pv2-req">*</span></label>
                <input class="form-control" id="txtEntityPhoneNumber" type="tel" autocomplete="off" placeholder="">
                <span class="pv2-help" id="msgPhoneRequired" style="display:none;color:var(--pv2-bad)">Escriba el número de teléfono.</span>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtIsPrimary">Es Primario</label>
                <input type="checkbox" name="txtIsPrimary" id="txtIsPrimary" value="1">
            </div>
        </div>
    </form>
</div>
