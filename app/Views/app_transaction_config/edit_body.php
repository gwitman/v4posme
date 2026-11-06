<div class="pv2 ptc pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-file"></i></div>
            <div>
                <p class="pv2-header-title"><?php echo $objTransaction->name;?></p>
                <p class="pv2-header-sub">Causales y configuración contable del documento</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_transaction_config/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
    <form id="form-edit-document" name="form-new-rol" role="form" autocomplete="off">
        <input type="hidden" name="txtCompanyID" id="companyID" value="<?php echo $objTransaction->companyID;?>" />
        <input type="hidden" name="txtTransactionID" id="txtTransactionID" value="<?php echo $objTransaction->transactionID;?>" />
        <input type="hidden" name="txtCmd" id="txtCmd" value="edit" />

        <div class="pv2-card">
            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-cube"></i></span> Datos del documento</p>
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label class="pv2-label" for="txtName">Nombre</label>
                        <input class="form-control" readonly="readonly" type="text" autocomplete="off" name="txtName" id="txtName" value="<?php echo $objTransaction->name;?>">
                    </div>
                    <div class="form-group">
                        <label class="pv2-label" for="txtWorkflowID">Workflow</label>
                        <select name="txtWorkflowID" readonly="readonly" id="txtWorkflowID" class="select2">
                            <?php
                            echo "<option value='".$objWorkflow->workflowID."'>".$objWorkflow->name."</option>";
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="pv2-label" for="txtJournalTypeID">Tipo de Comprobante</label>
                        <select name="txtJournalTypeID" id="txtJournalTypeID" class="select2">
                            <option></option>
                            <?php
                            if($objListJournalType)
                            foreach($objListJournalType as $i){
                                if($i->catalogItemID == $objTransaction->journalTypeID)
                                echo "<option value='".$i->catalogItemID."' selected >".$i->name."</option>";
                                else
                                echo "<option value='".$i->catalogItemID."'>".$i->name."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="pv2-label" for="txtReference1">Referencia 1</label>
                        <input class="form-control" type="text" autocomplete="off" name="txtReference1" id="txtReference1" value="<?php echo $objTransaction->reference1;?>">
                    </div>
                    <div class="form-group">
                        <label class="pv2-label" for="txtReference2">Referencia 2</label>
                        <input class="form-control" type="text" autocomplete="off" name="txtReference2" id="txtReference2" value="<?php echo $objTransaction->reference2;?>">
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label class="pv2-label" for="txtReference3">Referencia 3</label>
                        <input class="form-control" type="text" autocomplete="off" name="txtReference3" id="txtReference3" value="<?php echo $objTransaction->reference3;?>">
                    </div>
                    <div class="form-group">
                        <label class="pv2-label" for="txtSignInventory">Afectar el Inventario</label>
                        <input class="form-control" readonly="readonly" type="text" autocomplete="off" name="txtSignInventory" id="txtSignInventory" value="<?php echo $objTransaction->signInventory;?>">
                    </div>
                    <div class="form-group">
                        <label class="pv2-label" for="txtIsContabilize">Contabiliza</label>
                        <label class="checkbox-inline">
                            <input type="checkbox" id="txtIsContabilize" name="txtIsContabilize" value="1" <?php echo $objTransaction->isCountable == 1 ? "checked" : "";?> >
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="pv2-label" for="txtDescription">Descripcion</label>
                        <textarea class="form-control" id="txtDescription" name="txtDescription" rows="3"><?php echo $objTransaction->description;?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <ul id="myTab" class="nav nav-tabs">
            <li class="active"><a href="#causal" data-toggle="tab">Causales</a></li>
            <li><a href="#profile" data-toggle="tab">Configuracion</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active pv2-card" id="causal">
                <div class="pv2-detail-head">
                    <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Causales</p>
                    <div class="pv2-actions">
                        <a href="#" class="btn btn-default" id="btnNewDetailCausal"><i class="icon16 i-plus"></i> Agregar</a>
                        <a href="#" class="btn btn-danger" id="btnDeleteDetailCausal"><i class="icon16 i-remove"></i> Eliminar</a>
                    </div>
                </div>
                <p class="pv2-help">Toca una fila para seleccionarla y cargar su configuración.</p>

                <div class="pv2-table-wrap">
                    <table id="ListElementCausal" class="pv2-table">
                        <thead>
                            <tr>
                                <th style="width:19%">Sucursal</th>
                                <th style="width:30%">Nombre</th>
                                <th style="width:17%">Principal</th>
                                <th style="width:17%">Bodega Origen</th>
                                <th style="width:17%">Bodega Destino</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_detail_causal">
                            <?php
                                if($objListTransactionCausal)
                                foreach($objListTransactionCausal as $i){
                                    echo "<tr>";
                                        echo "<td>";
                                                echo "<span>";
                                                echo $i->branch;
                                                echo '<input type="hidden"  name="txtCausalID[]" value="'.$i->transactionCausalID.'" />';
                                                echo '<input type="hidden"  name="txtCausalBranchID[]" value="'.$i->branchID.'" />';
                                                echo '<input type="hidden"  name="txtCausalName[]" value="'.$i->name.'" />';
                                                echo '<input type="hidden"  name="txtCausalIsDefault[]" value="'.($i->isDefault ? "true" : "false").'" />';
                                                echo '<input type="hidden"  name="txtCausalWarehouseSourceID[]" value="'.$i->warehouseSourceID.'" />';
                                                echo '<input type="hidden"  name="txtCausalWarehouseTargetID[]" value="'.$i->warehouseTargetID.'" />';
                                                echo "</span>";
                                        echo "</td>";
                                        echo "<td>";
                                                echo $i->name;
                                        echo "</td>";
                                        echo "<td>";
                                                echo $i->isDefault == 1 ? "si" : "no";
                                        echo "</td>";
                                        echo "<td>";
                                                echo $i->warehouseSourceDescription;
                                        echo "</td>";
                                        echo "<td>";
                                                echo $i->warehouseTargetDescription;
                                        echo "</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade pv2-card" id="profile">
                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-grid-2"></i></span> Configuracion</p>

                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label class="pv2-label" for="txtCausalDescription">Causal</label>
                            <input class="form-control" readonly="readonly" type="text" autocomplete="off" name="txtCausalDescription" id="txtCausalDescription" value="">
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtConceptID">Concepto</label>
                            <select name="txtConceptID" id="txtConceptID" class="select2">
                                <option></option>
                                <?php
                                if($objListTransactionConcept)
                                foreach($objListTransactionConcept as $i){
                                    echo "<option value='".$i->conceptID."'>".$i->name."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtSignProfileDetail">D|C</label>
                            <select name="txtSignProfileDetail" id="txtSignProfileDetail" class="select2">
                                <option value="D">Debito</option>
                                <option value="C">Credito</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label class="pv2-label" for="txtAccountID">Cuenta</label>
                            <select name="txtAccountID" id="txtAccountID" class="select2">
                                <option></option>
                                <?php
                                if($objListAccount)
                                foreach($objListAccount as $i){
                                    echo "<option value='".$i->accountID."'>".$i->accountNumber."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtCC">Centro de Costo</label>
                            <select name="txtCC" id="txtCC" class="select2">
                                <option></option>
                                <?php
                                if($objListCenterCost)
                                foreach($objListCenterCost as $i){
                                    echo "<option value='".$i->classID."'>".$i->number."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="pv2-actions ptc-table-actions">
                    <a href="#" class="btn btn-default" id="btnNewDetailConfig"><i class="icon16 i-plus"></i> Agregar</a>
                    <a href="#" class="btn btn-danger" id="btnDeleteDetailConfig"><i class="icon16 i-remove"></i> Eliminar</a>
                </div>

                <div class="pv2-table-wrap">
                    <table id="ListElementConfig" class="pv2-table">
                        <thead>
                            <tr>
                                <th style="width:32%">Concepto</th>
                                <th style="width:17%">Cuenta Debito</th>
                                <th style="width:17%">CC Debito</th>
                                <th style="width:17%">Cuenta Credito</th>
                                <th style="width:17%">CC Credito</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_detail_config">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
    <!-- /formulario -->

    <!-- barra inferior (telefono) -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label"><?php echo $objTransaction->name;?></p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>
