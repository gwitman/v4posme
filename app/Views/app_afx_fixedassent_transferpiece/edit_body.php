<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-storage-2"></i></div>
            <div>
                <p class="pv2-header-title">Transferencia de Piezas <span class="pv2-chip">#<span class="invoice-num"><?= $objTM->transactionNumber ?></span></span></p>
                <p class="pv2-header-sub">Registra el traslado de piezas entre activos fijos</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_afx_fixedassent_transferpiece/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="<?php echo base_url(); ?>/app_afx_fixedassent_transferpiece/add" class="btn btn-default" id="btnNuevo"><i class="icon16 i-plus"></i> Nuevo</a>
            <button type="button" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</button>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
            <form id="form-new-transferpiece" name="form-new-transferpiece" role="form" autocomplete="off">
                <div class="printArea">
                        <input type="hidden" name="txtTransactionID" id="txtTransactionID" value="<?= $objTM->transactionID ?>">
                        <input type="hidden" name="txtTransactionMasterID" id="txtTransactionMasterID" value="<?= $objTM->transactionMasterID ?>">
                        <input type="hidden" name="txtUserID" id="txtUserID" value="<?php echo $objUser->userID; ?>">
                        
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
                                                            echo "<option value='" . $ws->workflowStageID . "'>" . $ws->name . "</option>";
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
                                        <label class="pv2-label">Comentario</label>
                                        <div>
                                            <textarea class="form-control" name="txtComment" id="txtComment" rows="4"><?= $objTM->note ?></textarea>
                                        </div>
                                    </div>
                                  </div>
                                </div>

                                <div class="col-xs-12 col-md-6">
                                  <div class="pv2-card">
                                    <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-storage-2"></i></span> Activos y responsable</p>
                                    <div class="form-group">
                                        <label class="pv2-label">Activo Origen</label>
                                        <div>
                                            <div class="input-group afx-search">
                                                <input type="hidden" id="txtSourceFixedAssetID" name="txtSourceFixedAssetID" value="<?= $objSourceAsset ? $objTM->sourceWarehouseID : '' ?>">
                                                <input class="form-control" readonly id="txtSourceFixedAssetDescription" type="text" value="<?= $objSourceAsset ? $objSourceAsset->fixedAssentCode . ' | ' . $objSourceAsset->name : '' ?>">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button" id="btnClearSourceAsset">
                                                        <i aria-hidden="true" class="i-undo-2"></i> Limpiar
                                                    </button>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button" id="btnSearchSourceAsset">
                                                        <i aria-hidden="true" class="i-search-5"></i> Buscar
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="pv2-label">Activo Destino</label>
                                        <div>
                                            <div class="input-group afx-search">
                                                <input type="hidden" id="txtTargetFixedAssetID" name="txtTargetFixedAssetID" value="<?= $objTargetAsset ? $objTM->targetWarehouseID : '' ?>">
                                                <input class="form-control" readonly id="txtTargetFixedAssetDescription" type="text" value="<?= $objTargetAsset ? $objTargetAsset->fixedAssentCode . ' | ' . $objTargetAsset->name : '' ?>">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button" id="btnClearTargetAsset">
                                                        <i aria-hidden="true" class="i-undo-2"></i> Limpiar
                                                    </button>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button" id="btnSearchTargetAsset">
                                                        <i aria-hidden="true" class="i-search-5"></i> Buscar
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                     <div class="form-group">
										<label class="pv2-label"><?php echo getBehavio($company->type,"app_afx_transferpiece","labelTecnico","Tecnico");  ?></label>
										<div>
											<div class="input-group afx-search">
												<input type="hidden" id="txtEmployerID" name="txtEmployerID" value="<?php echo ($objEmployer ?  $objEmployer->entityID : "0");  ?>">
												<input class="form-control" readonly id="txtEmployerDescription" type="text" autocomplete="off" value="<?php echo ($objEmployer  ? strtoupper($objEmployer->employeNumber . " ". $objEmployerNatural->firstName . " ". $objEmployerNatural->lastName ) : ""); ?>">
												
												<span class="input-group-btn">
													<button class="btn btn-default" type="button" id="btnClearEmployer">
														<i aria-hidden="true" class="i-undo-2"></i>
														Limpiar
													</button>
												</span>
												<span class="input-group-btn">
													<button class="btn btn-primary" type="button" id="btnSearchEmployer">
														<i aria-hidden="true" class="i-search-5"></i>
														Buscar
													</button>
												</span>											
											</div>
										</div>
									</div>
									


                                  </div>
                                </div>
                            </div>

                    <!-- detalle -->
                    <div class="pv2-card">
                        <div class="pv2-detail-head">
                            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Piezas <span class="pv2-count" id="afxDetailCount">0</span></p>
                            <div class="pv2-actions">
                                <button type="button" class="btn btn-default btn-sm" id="btnDeleteDetailTransaction"><i class="icon16 i-remove"></i> Quitar marcadas</button>
                                <button type="button" class="btn btn-primary btn-sm" id="btnNewDetailTransaction"><i class="icon16 i-plus"></i> Agregar pieza</button>
                            </div>
                        </div>
                        <div id="div_transaction_master_detail" class="pv2-table-wrap">
                                <table id="tb_transaction_master_detail" class="pv2-table">
                                    <thead>
                                        <tr>
                                            <th class="afx-col-quitar"></th>
                                            <th>Nombre de la pieza</th>
                                            <th style="width:14%;">Cantidad</th>
                                            <th style="width:26%;">Acción</th>
                                            <th style="width:24%;">Comentario</th>
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
                                                        <input type="hidden" name="txtCurrentPieceID[]" id="txtCurrentPieceID" value="<?= $detail->transactionMasterDetailID ?>" />
                                                    </td>
                                                    <td class="afx-col-block" data-label="Nombre de la pieza">
                                                        <select class="select2 select2-piecename" name="txtCurrentPieceName[]" style="width:100%">
                                                            <option value="">--Seleccione--</option>
                                                            <?php
                                                            if (!empty($objListPieceNames))
                                                                foreach ($objListPieceNames as $pn) {
                                                                    $selected = ($pn->publicCatalogDetailID == $detail->piezaID) ? 'selected' : '';
                                                                    echo "<option value='" . $pn->publicCatalogDetailID . "' $selected>" . $pn->name . "</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td data-label="Cantidad">
                                                        <input class="form-control" type="number" autocomplete="off" name="txtCurrentPieceQuantity[]" id="txtCurrentPieceQuantity" value="<?= $detail->quantity ?>">
                                                    </td>
                                                    <td class="afx-col-block" data-label="Acción">
                                                        <select class="select2 select2-action" name="txtCurrentPieceAction[]" style="width:100%">
                                                            <option value="">--Seleccione--</option>
                                                            <?php
                                                            if (!empty($objListTypeAction))
                                                                foreach ($objListTypeAction as $ta) {
                                                                    $selected = ($ta->catalogItemID == $detail->typeMovementID) ? 'selected' : '';
                                                                    echo "<option value='" . $ta->catalogItemID . "' $selected>" . $ta->name . "</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td data-label="Comentario">
                                                        <input class="form-control" type="text" autocomplete="off" name="txtCurrentPieceComment[]" id="txtCurrentPieceComment" value="<?= $detail->comentMovement ?? '' ?>">
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
                            Este documento no tiene piezas. Toca <b>Agregar pieza</b> para empezar.
                        </div>
                    </div>
                    <!-- /detalle -->
                </div>
            </form>
    <!-- /formulario -->

    <!-- barra inferior (telefono) -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label">Editar transferencia</p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>

<!-- Template de fila nueva pieza -->
<script type="text/template" id="tmpl_row_piece">
    <tr class="row_razon">
        <td class="td-checkbox" data-label="Quitar">
            <input type="checkbox" class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
        </td>
        <td class="afx-col-block" data-label="Nombre de la pieza">
            <select class="select2 select2-piecename" name="txtNewPieceName[]" style="width:100%">
                <option value="">--Seleccione--</option>
                <?php
                if (!empty($objListPieceNames))
                    foreach ($objListPieceNames as $pn) {
                        echo "<option value='" . $pn->publicCatalogDetailID . "'>" . $pn->name . "</option>";
                    }
                ?>
            </select>
        </td>
        <td data-label="Cantidad">
            <input class="form-control" type="number" autocomplete="off" name="txtNewPieceQuantity[]" id="txtNewPieceQuantity" value="1">
        </td>
        <td class="afx-col-block" data-label="Acción">
            <select class="select2 select2-action" name="txtNewPieceAction[]" style="width:100%">
                <option value="">--Seleccione--</option>
                <?php
                if (!empty($objListTypeAction))
                    foreach ($objListTypeAction as $ta) {
                        echo "<option value='" . $ta->catalogItemID . "'>" . $ta->name . "</option>";
                    }
                ?>
            </select>
        </td>
        <td data-label="Comentario">
            <input class="form-control" type="text" autocomplete="off" name="txtNewPieceComment[]" id="txtNewPieceComment" value="">
        </td>
    </tr>
</script>
