<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-storage-2"></i></div>
            <div>
                <p class="pv2-header-title">Entrada de Activo Fijo <span class="pv2-chip">#<span class="invoice-num"><?= $objTM->transactionNumber ?></span></span></p>
                <p class="pv2-header-sub">Registra los activos que entran y a quién quedan asignados</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_afx_fixedassent_input/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="<?php echo base_url(); ?>/app_afx_fixedassent_input/add" class="btn btn-default" id="btnNuevo"><i class="icon16 i-plus"></i> Nuevo</a>
            <button type="button" class="btn btn-default" id="btnClickArchivo"><i class="icon16 i-file"></i> Archivos</button>
            <button type="button" class="btn btn-default" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</button>
            <button type="button" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</button>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
            <form id="form-new-fixedasset_input" name="form-new-fixedasset_input" role="form" autocomplete="off">
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
                                                        if ($ws->workflowStageID == $objTM->statusID) {
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
                                        <label class="pv2-label">Causal</label>
                                        <div>
                                            <select name="txtCausalID" id="txtCausalID" class="select2">
                                                <option></option>
                                                <?php
                                                if ($objCausal)
                                                    foreach ($objCausal as $oc) {
                                                        if($objTM->transactionCausalID == $oc->transactionCausalID){
                                                            echo "<option value='" . $oc->transactionCausalID . "' selected>" . $oc->name . "</option>";
                                                        }else{
                                                            echo "<option value='" . $oc->transactionCausalID . "' >" . $oc->name . "</option>";
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
                                  </div>
                                </div>

                                <div class="col-xs-12 col-md-6">
                                  <div class="pv2-card">
                                    <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Asignación</p>
                                    <div class="form-group">
                                        <label class="pv2-label">Asignado A.</label>
                                        <div>
                                            <div class="input-group afx-search">
                                                <input type="hidden" id="txtEmployeeEntityID" name="txtEmployeeEntityID" value="<?= $objEmployee->entityID ?>">
                                                <input class="form-control" readonly id="txtEmployeeDescription" type="text" autocomplete="off" value="<?= $objEmployee->employeNumber . " | " . $objNatural->firstName . " " . $objNatural->lastName ?>">

                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button" id="btnClearEmployee">
                                                        <i aria-hidden="true" class="i-undo-2"></i>
                                                        Limpiar
                                                    </button>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button" id="btnSearchEmployee">
                                                        <i aria-hidden="true" class="i-search-5"></i>
                                                        Buscar
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="pv2-label">Area Asignada</label>
                                        <div>
                                            <div class="input-group afx-search">
                                                <input type="hidden" id="txtAreaID" name="txtAreaID" value="<?= $objArea != null ? $objArea->Id : "" ?>">
                                                <input class="form-control" readonly id="txtAreaDescripcion" type="text" autocomplete="off" value="<?= $objArea != null ? $objArea->Id . " | " . $objArea->Indicador : "" ?>">

                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button" id="btnClearArea">
                                                        <i aria-hidden="true" class="i-undo-2"></i>
                                                        Limpiar
                                                    </button>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button" id="btnSearchArea">
                                                        <i aria-hidden="true" class="i-search-5"></i>
                                                        Buscar
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">Proyecto Asignado</label>
                                        <div>
                                            <div class="input-group afx-search">
                                                <input type="hidden" id="txtProyectID" name="txtProyectID" value="<?= $objProject != null ? $objProject->Id : "" ?>">
                                                <input class="form-control" readonly id="txtProyectDescripcion" type="text" autocomplete="off" value="<?= $objProject != null ? $objProject->Id  . " | " . $objProject->Indicador : "" ?>">

                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button" id="btnClearProyect">
                                                        <i aria-hidden="true" class="i-undo-2"></i>
                                                        Limpiar
                                                    </button>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button" id="btnSearchProyect">
                                                        <i aria-hidden="true" class="i-search-5"></i>
                                                        Buscar
                                                    </button>
                                                </span>
                                            </div>
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
                            </div>

                    <!-- detalle -->
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
                                        <th>Duración estimada</th>
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
                                                <td data-label="Duración estimada">
                                                    <input class="form-control" type="number" autocomplete="off" name="txtCurrentEstimatedDuration[]" id="txtCurrentEstimatedDuration" value="<?= $detail->fixedAssetDuration ?>">
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
            <p class="pv2-mbar-label">Editar entrada</p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>


<!-- Zona de generacion de la tabla de detalles de transaction_master -->
<script type="text/template" id="tmpl_row_fixedasset">
    <tr class="row_razon">
        <td class="td-checkbox" data-label="Quitar">
            <input type="checkbox" class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
            <input type="hidden"  name="txtNewFixedAssetID[]" id="txtNewFixedAssetID" value="" data-detailID="" />
        </td>
        <td data-label="Código">
            <span id="txtFixedAssetCode"></span>
        </td>
        <td data-label="Nombre">
            <span id="txtFixedAssetName"></span>
        </td>
        <td data-label="Duración estimada">
            <input class="form-control" type="number" autocomplete="off" name="txtNewEstimatedDuration[]" id="txtNewEstimatedDuration" value="">
        </td>
    </tr>
</script>