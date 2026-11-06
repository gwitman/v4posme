<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-storage-2"></i></div>
            <div>
                <p class="pv2-header-title">Tarjeta:#<span class="invoice-num"><?php echo $objTM->transactionNumber ?></span></p>
                <p class="pv2-header-sub">Revisa o actualiza la tarjeta de fidelidad</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_sales_loyalty/add" class="btn btn-default" id="btnNuevo"><i class="icon16 i-plus"></i> Nuevo</a>
            <a href="<?php echo base_url(); ?>/app_sales_loyalty/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
            <a href="#" class="btn btn-default" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
    <form id="form-new-notecredit" name="form-new-notecredit" role="form" autocomplete="off">
        <div class="printArea">

            <ul id="myTab" class="nav nav-tabs">
                <li class="active">
                    <a href="#home" data-toggle="tab">Informacion</a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Mas <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a id="btnClickArchivo" href="#dropdown-file" data-toggle="tab">Archivos</a></li>
                    </ul>
                </li>
            </ul>

            <div class="tab-content">
                <input type="hidden" name="txtTransactionID" id="txtTransactionID" value="<?= $objTM->transactionID ?>">
                <input type="hidden" name="txtTransactionMasterID" id="txtTransactionMasterID" value="<?= $objTM->transactionMasterID ?>">

                <div class="tab-pane fade in active" id="home">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-storage-2"></i></span> Tarjeta</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDate">Fecha <span class="pv2-req">*</span></label>
                                    <div class="input-group date" data-date-format="yyyy-mm-dd">
                                        <input size="16" class="form-control" type="text" autocomplete="off" name="txtDate" id="txtDate" value="<?= explode(' ', $objTM->transactionOn)[0]; ?>">
                                        <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtStatusID">Estado</label>
                                    <select name="txtStatusID" id="txtStatusID" class="select2">
                                        <option></option>
                                        <?php
                                        if ($objListWorkflowStage)
                                            foreach ($objListWorkflowStage as $ws) {
                                                if ($ws->workflowStageID == $objTM->statusID)
                                                    echo "<option value='" . $ws->workflowStageID . "' selected>" . $ws->name . "</option>";
                                                else
                                                    echo "<option value='" . $ws->workflowStageID . "' >" . $ws->name . "</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtAmount">Meta <span class="pv2-req">*</span></label>
                                    <input class="form-control pv2-num afx-monto" type="text" inputmode="decimal" autocomplete="off" name="txtAmount" id="txtAmount" value="<?= sprintf("%01.4f", $objTM->amount) ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtTax1">Puntos</label>
                                    <input class="form-control pv2-num afx-monto" type="text" inputmode="decimal" autocomplete="off" name="txtTax1" id="txtTax1" value="<?= is_null($objTM->tax1) || $objTM->tax1 === "" ? "" : sprintf("%01.4f", $objTM->tax1) ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtNumberPhone">Telefono Cliente <span class="pv2-req">*</span></label>
                                    <input class="form-control" type="text" inputmode="tel" autocomplete="off" name="txtNumberPhone" id="txtNumberPhone" value="<?= $objTM->numberPhone ?>">
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-pencil-4"></i></span> Premio y referencias</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtReference1">Premio</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference1" id="txtReference1" value="<?= $objTM->reference1 ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtReference2">Referencia 1</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference2" id="txtReference2" value="<?= $objTM->reference2 ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtReference3">Referencia 2</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference3" id="txtReference3" value="<?= $objTM->reference3 ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtComment">Comentario</label>
                                    <textarea class="form-control" id="txtComment" name="txtComment" rows="6"><?= $objTM->note; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="dropdown-file">
                    <div class="pv2-empty">Los archivos se abren en una ventana nueva.</div>
                </div>
            </div>
        </div>
    </form>
    <!-- /formulario -->

    <!-- barra inferior (telefono) -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label">Tarjeta #<?php echo $objTM->transactionNumber ?></p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>
