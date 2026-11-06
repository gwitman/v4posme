<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-storage-2"></i></div>
            <div>
                <p class="pv2-header-title">ENDOSO:#<span class="invoice-num">00000000</span></p>
                <p class="pv2-header-sub">Registra un nuevo endoso sobre un documento</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_tools_endorsements/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
    <form id="form-new-endoso" name="form-new-endoso" role="form" autocomplete="off">
        <div class="printArea">
            <div class="row" id="home">
                <div class="col-xs-12 col-md-6">
                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Documento</p>

                        <div class="form-group">
                            <label class="pv2-label" for="txtDate">Fecha</label>
                            <div class="input-group date" data-date-format="yyyy-mm-dd">
                                <input size="16" class="form-control" type="text" autocomplete="off" name="txtDate" id="txtDate"/>
                                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtStatusID">Estado</label>
                            <select class="select2" id="txtStatusID" name="txtStatusID">
                                <?php
                                if (isset($objListWorkflowStage))
                                    foreach($objListWorkflowStage as $tr){
                                        echo '<option value="'.$tr->workflowStageID.'">'.$tr->name.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtTransactionID">Tipo de Transacción</label>
                            <select class="select2" name="txtTransactionID" id="txtTransactionID">
                                <?php
                                if (isset($tipoTransaccion))
                                    foreach($tipoTransaccion as $tr){
                                        echo '<option value="'.$tr->transactionID.'">'.$tr->name.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtTransactionNumberDescription">Documento</label>
                            <div class="input-group afx-search">
                                <input type="hidden" id="txtTransactionNumber" name="txtTransactionNumber" value="" />
                                <input type="hidden" id="txtTransactionMasterIDEndoso" name="txtTransactionMasterIDEndoso" value="" />
                                <input class="form-control" readonly id="txtTransactionNumberDescription" type="text" autocomplete="off" value="" />
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" id="btnClearDocument">
                                        <i aria-hidden="true" class="i-undo-2"></i>
                                        Limpiar
                                    </button>
                                </span>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button" id="btnSearchDocument">
                                        <i aria-hidden="true" class="i-search-5"></i>
                                        Buscar
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-pencil-4"></i></span> Motivo y referencias</p>

                        <div class="form-group">
                            <label class="pv2-label" for="txtComentario">Comentario</label>
                            <input class="form-control" type="text" autocomplete="off" name="txtComentario" id="txtComentario" placeholder="Escriba el motivo del endoso" />
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtReferencia1">Referencia 1</label>
                            <input class="form-control" type="text" autocomplete="off" name="txtReferencia1" id="txtReferencia1" />
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtReferencia2">Referencia 2</label>
                            <input class="form-control" type="text" autocomplete="off" name="txtReferencia2" id="txtReferencia2" />
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtReferencia3">Referencia 3</label>
                            <input class="form-control" type="text" autocomplete="off" name="txtReferencia3" id="txtReferencia3" />
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-6">
                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-grid-2"></i></span> Valor a endosar</p>

                        <div class="form-group">
                            <label class="pv2-label" for="txtValorModificar">Valor a Modificar</label>
                            <select class="select2" id="txtValorModificar" name="txtValorModificar">
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="hidden" id="txtSelectedTextValorAnterior" name="txtSelectedTextValorAnterior" value="">
                            <label class="pv2-label">Valor anterior</label>
                            <div id="contenedorDinamicoValorAnterior"></div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" id="txtSelectedTextValorNuevo" name="txtSelectedTextValorNuevo" value="">
                            <label class="pv2-label">Valor Nuevo</label>
                            <div id="contenedorDinamicoValorNuevo"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- /formulario -->

    <!-- barra inferior (telefono) -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label">Nuevo endoso</p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>
