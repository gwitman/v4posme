<div class="pv2">
    <form id="form-new-rol" autocomplete="off" onsubmit="return false;">
        <div class="pv2-card">
            <div class="form-group">
                <label class="pv2-label" for="txtBranchID">Sucursal <span class="pv2-req">*</span></label>
                <select name="txtBranchID" id="txtBranchID" class="select2">
                    <option></option>
                    <?php
                    if($objListBranch)
                    foreach($objListBranch as $i){
                        echo "<option value='".$i->branchID."'>".$i->name."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label class="pv2-label" for="txtName">Nombre <span class="pv2-req">*</span></label>
                <input class="form-control" type="text" autocomplete="off" name="txtName" id="txtName" value="">
            </div>
            <div class="form-group">
                <label class="pv2-label" for="txtWarehouseSourceID">Bodega Origen</label>
                <select name="txtWarehouseSourceID" id="txtWarehouseSourceID" class="select2">
                    <option></option>
                    <?php
                    if($objListWarehouse)
                    foreach($objListWarehouse as $i){
                        echo "<option value='".$i->warehouseID."'>".$i->number." ".$i->name."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label class="pv2-label" for="txtWarehouseTargetID">Bodega Destino</label>
                <select name="txtWarehouseTargetID" id="txtWarehouseTargetID" class="select2">
                    <option></option>
                    <?php
                    if($objListWarehouse)
                    foreach($objListWarehouse as $i){
                        echo "<option value='".$i->warehouseID."'>".$i->number." ".$i->name."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label class="pv2-label" for="txtIsDefault">Es Principal</label>
                <label class="checkbox-inline">
                    <input type="checkbox" id="txtIsDefault" name="txtIsDefault" value="1">
                </label>
            </div>
        </div>
    </form>
</div>
