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
                <a href="<?php echo base_url(); ?>/app_afx_fixedassent_transferpiece/index" id="btnBack" class="btn btn-inverse"><i class="icon16 i-rotate"></i> Atras</a>
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
                <h4>Transferencia de Piezas:#<span class="invoice-num">00000000</span></h4>
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
                        <div class="tab-pane fade in active" id="home">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="datepicker">Fecha</label>
                                        <div class="col-lg-8">
                                            <div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
                                                <input size="16" class="form-control" type="text" name="txtDate" id="txtDate">
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
                                                        echo "<option value='" . $ws->workflowStageID . "' selected>" . $ws->name . "</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Referencia 1</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtReference1" id="txtReference1" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Referencia 2</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtReference2" id="txtReference2" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Comentario</label>
                                        <div class="col-lg-8">
                                            <textarea class="form-control" name="txtComment" id="txtComment" rows="4"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="buttons">Activo Origen</label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <input type="hidden" id="txtSourceFixedAssetID" name="txtSourceFixedAssetID" value="">
                                                <input class="form-control" readonly id="txtSourceFixedAssetDescription" type="text" value="">
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
                                                <input type="hidden" id="txtTargetFixedAssetID" name="txtTargetFixedAssetID" value="">
                                                <input class="form-control" readonly id="txtTargetFixedAssetDescription" type="text" value="">
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
												<input type="hidden" id="txtEmployerID" name="txtEmployerID" value="0">
												<input class="form-control" readonly id="txtEmployerDescription" type="txtEmployerDescription" value="">
												
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

<!-- Template de fila de pieza -->
<script type="text/template" id="tmpl_row_piece">
    <tr class="row_razon">
        <td class="td-checkbox">
            <input type="checkbox" style="width: 20px; height:20px; display: block; margin: auto;" class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
        </td>
        <td>
            <select class="select2 select2-piecename" name="txtPieceName[]" style="width:100%">
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
            <input class="form-control" type="number" name="txtPieceQuantity[]" id="txtPieceQuantity" value="1">
        </td>
        <td>
            <select class="select2 select2-action" name="txtPieceAction[]" style="width:100%">
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
            <input class="form-control" type="text" name="txtPieceComment[]" id="txtPieceComment" value="">
        </td>
    </tr>
</script>
