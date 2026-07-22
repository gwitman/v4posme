<style>
    #tb_transaction_master_detail {
        table-layout: fixed;
        width: 100%;
    }

    #tb_transaction_master_detail th,
    #tb_transaction_master_detail td {
        word-wrap: break-word;
    }

    #tb_transaction_master_detail thead {
        position: sticky;
        top: 0;
        z-index: 2;
    }

    #div_transaction_master_detail {
        height: 400px;
        overflow-y: auto;
        overflow-x: auto;
    }
</style>
<div class="row">
    <div id="email" class="col-lg-12">
        <!-- botonera -->
        <div class="email-bar" style="border-left:1px solid #c9c9c9">
            <div class="btn-group pull-right">
                <a href="<?php echo base_url(); ?>/app_afx_fixedassent_transferpiece/add" class="btn btn-success" id="btnNuevo"><i class="icon16 i-checkmark-4"></i>Nuevo</a>
                <a href="<?php echo base_url(); ?>/app_afx_fixedassent_transferpiece/index" id="btnBack" class="btn btn-inverse"><i class="icon16 i-rotate"></i> Atras</a>
                <a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
                <a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
            </div>
        </div>
        <!-- /botonera -->
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">

            <!-- titulo de comprobante-->
            <div class="panel-heading">
                <div class="icon"><i class="icon20 i-file"></i></div>
                <h4>Transferencia de Piezas:#<span class="invoice-num"><?= $objTM->transactionNumber ?></span></h4>
            </div>
            <!-- /titulo de comprobante-->

            <!-- body -->
            <form id="form-new-transferpiece" name="form-new-transferpiece" class="form-horizontal" role="form">
                <div class="panel-body printArea">

                    <ul id="myTab" class="nav nav-tabs">
                        <li class="active">
                            <a href="#home" data-toggle="tab">Informacion</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <input type="hidden" name="txtTransactionID" id="txtTransactionID" value="<?= $objTM->transactionID ?>">
                        <input type="hidden" name="txtTransactionMasterID" id="txtTransactionMasterID" value="<?= $objTM->transactionMasterID ?>">
                        <div class="tab-pane fade in active" id="home">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="datepicker">Fecha</label>
                                        <div class="col-lg-8">
                                            <div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
                                                <input size="16" class="form-control" type="text" name="txtDate" id="txtDate" value="<?= explode(" ", $objTM->transactionOn)[0] ?>">
                                                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="selectFilter">Estado</label>
                                        <div class="col-lg-8">
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
                                        <label class="col-lg-4 control-label" for="normal">Referencia 1</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtReference1" id="txtReference1" value="<?= $objTM->reference1 ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Referencia 2</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtReference2" id="txtReference2" value="<?= $objTM->reference2 ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Comentario</label>
                                        <div class="col-lg-8">
                                            <textarea class="form-control" name="txtComment" id="txtComment" rows="4"><?= $objTM->note ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="buttons">Activo Origen</label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <input type="hidden" id="txtSourceFixedAssetID" name="txtSourceFixedAssetID" value="<?= $objSourceAsset ? $objTM->sourceWarehouseID : '' ?>">
                                                <input class="form-control" readonly id="txtSourceFixedAssetDescription" type="text" value="<?= $objSourceAsset ? $objSourceAsset->fixedAssentCode . ' | ' . $objSourceAsset->name : '' ?>">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-danger" type="button" id="btnClearSourceAsset">
                                                        <i aria-hidden="true" class="i-undo-2"></i> clear
                                                    </button>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button" id="btnSearchSourceAsset">
                                                        <i aria-hidden="true" class="i-search-5"></i> buscar
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="buttons">Activo Destino</label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <input type="hidden" id="txtTargetFixedAssetID" name="txtTargetFixedAssetID" value="<?= $objTargetAsset ? $objTM->targetWarehouseID : '' ?>">
                                                <input class="form-control" readonly id="txtTargetFixedAssetDescription" type="text" value="<?= $objTargetAsset ? $objTargetAsset->fixedAssentCode . ' | ' . $objTargetAsset->name : '' ?>">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-danger" type="button" id="btnClearTargetAsset">
                                                        <i aria-hidden="true" class="i-undo-2"></i> clear
                                                    </button>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button" id="btnSearchTargetAsset">
                                                        <i aria-hidden="true" class="i-search-5"></i> buscar
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                     <div class="form-group">
										<label class="col-lg-4 control-label" for="buttons"><?php echo getBehavio($company->type,"app_afx_transferpiece","labelTecnico","Tecnico");  ?></label>
										<div class="col-lg-8">
											<div class="input-group">
												<input type="hidden" id="txtEmployerID" name="txtEmployerID" value="<?php echo ($objEmployer ?  $objEmployer->entityID : "0");  ?>">
												<input class="form-control" readonly id="txtEmployerDescription" type="txtEmployerDescription" value="<?php echo ($objEmployer  ? strtoupper($objEmployer->employeNumber . " ". $objEmployerNatural->firstName . " ". $objEmployerNatural->lastName ) : ""); ?>">
												
												<span class="input-group-btn">
													<button class="btn btn-danger" type="button" id="btnClearEmployer">
														<i aria-hidden="true" class="i-undo-2"></i>
														clear
													</button>
												</span>
												<span class="input-group-btn">
													<button class="btn btn-primary" type="button" id="btnSearchEmployer">
														<i aria-hidden="true" class="i-search-5"></i>
														buscar
													</button>
												</span>											
											</div>
										</div>
									</div>
									


                                </div>
                            </div>
                        </div>
                    </div>

                    <br>
                    <a href="#" class="btn btn-flat btn-info" id="btnNewDetailTransaction">Agregar Pieza</a>
                    <a href="#" class="btn btn-flat btn-danger" id="btnDeleteDetailTransaction">Eliminar Pieza</a>
                    <div class="row">
                        <div class="col-lg-12">
                            <h3>Detalle de Piezas:</h3>
                            <div id="div_transaction_master_detail">
                                <table id="tb_transaction_master_detail" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 40px;"></th>
                                            <th>Nombre Pieza</th>
                                            <th style="width: 120px;">Cantidad</th>
                                            <th style="width: 250px;">Accion</th>
                                            <th style="width: 200px;">Comentario</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body_tb_transaction_master_detail">
                                        <?php
                                        if ($objTMD) {
                                            foreach ($objTMD as $detail) {
                                        ?>
                                                <tr class="row_razon">
                                                    <td class="td-checkbox">
                                                        <input type="checkbox" style="width: 20px; height:20px; display: block; margin: auto;" class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
                                                        <input type="hidden" name="txtCurrentPieceID[]" id="txtCurrentPieceID" value="<?= $detail->transactionMasterDetailID ?>" />
                                                    </td>
                                                    <td>
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
                                                    <td>
                                                        <input class="form-control" type="number" name="txtCurrentPieceQuantity[]" id="txtCurrentPieceQuantity" value="<?= $detail->quantity ?>">
                                                    </td>
                                                    <td>
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
                                                    <td>
                                                        <input class="form-control" type="text" name="txtCurrentPieceComment[]" id="txtCurrentPieceComment" value="<?= $detail->comentMovement ?? '' ?>">
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- /body -->
        </div>
    </div>
</div>

<!-- Template de fila nueva pieza -->
<script type="text/template" id="tmpl_row_piece">
    <tr class="row_razon">
        <td class="td-checkbox">
            <input type="checkbox" style="width: 20px; height:20px; display: block; margin: auto;" class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
        </td>
        <td>
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
        <td>
            <input class="form-control" type="number" name="txtNewPieceQuantity[]" id="txtNewPieceQuantity" value="1">
        </td>
        <td>
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
        <td>
            <input class="form-control" type="text" name="txtNewPieceComment[]" id="txtNewPieceComment" value="">
        </td>
    </tr>
</script>
