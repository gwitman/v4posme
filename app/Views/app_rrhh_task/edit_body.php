<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-storage-2"></i></div>
            <div>
                <p class="pv2-header-title">TAREA: #<span class="invoice-num"><?= $objTransactionMaster->transactionNumber ?></span></p>
                <p class="pv2-header-sub">Revisa o actualiza la tarea</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_rrhh_task/add" class="btn btn-default" id="btnNuevo"><i class="icon16 i-plus"></i> Nuevo</a>
            <a href="<?php echo base_url(); ?>/app_rrhh_task/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
    <form id="form-new-task" name="form-new-task" role="form" autocomplete="off">
        <div class="printArea">
            <input type="hidden" name="txtCompanyID" value="<?= $objTransactionMaster->companyID; ?>">
            <input type="hidden" name="txtTransactionID" value="<?= $objTransactionMaster->transactionID; ?>">
            <input type="hidden" name="txtTransactionMasterID" value="<?= $objTransactionMaster->transactionMasterID; ?>">

            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="pv2-card" id="home">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> La tarea</p>

                        <div class="form-group">
                            <label class="pv2-label" for="txtReference4">Titulo <span class="pv2-req">*</span></label>
                            <input class="form-control" type="text" autocomplete="off" name="txtReference4" id="txtReference4" value="<?= $objTransactionMaster->reference4?>" />
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtDescripcion">Descripcion</label>
                            <input class="form-control" type="text" autocomplete="off" name="txtDescripcion" id="txtDescripcion" value="<?= $objTransactionMaster->descriptionReference ?>" />
                        </div>
                    </div>

                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Personas</p>

                        <div class="form-group">
                            <label class="pv2-label" for="txtEmisorDescription">Emisor <span class="pv2-req">*</span></label>
                            <div class="input-group afx-search">
                                <input type="hidden" id="txtEmisorID" name="txtEmisorID" value="<?= $objTransactionMaster->entityIDSecondary?>" />
                                <input class="form-control" readonly id="txtEmisorDescription" type="text" autocomplete="off" value="<?= isset($objEmisorNatural) ? strtoupper($objEmisor->employeNumber . " ". $objEmisorNatural->firstName . " ". $objEmisorNatural->lastName ) : strtoupper($objEmisor->employeNumber." ".$objEmisorLegal->comercialName); ?>" />
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" id="btnClearEmisor">
                                        <i aria-hidden="true" class="i-undo-2"></i>
                                        Limpiar
                                    </button>
                                </span>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button" id="btnSearchEmisor">
                                        <i aria-hidden="true" class="i-search-5"></i>
                                        Buscar
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtAsignadoDescription">Asignado <span class="pv2-req">*</span></label>
                            <div class="input-group afx-search">
                                <input type="hidden" id="txtAsignadoID" name="txtAsignadoID" value="<?= $objTransactionMaster->entityID?>" />
                                <input class="form-control" readonly id="txtAsignadoDescription" type="text" autocomplete="off" value="<?= isset($objAsignadoNatural) ? strtoupper($objAsignado->employeNumber . " ". $objAsignadoNatural->firstName . " ". $objAsignadoNatural->lastName ) : strtoupper($objAsignado->employeNumber." ".$objAsignadoLegal->comercialName); ?>" />
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" id="btnClearAsignado">
                                        <i aria-hidden="true" class="i-undo-2"></i>
                                        Limpiar
                                    </button>
                                </span>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button" id="btnSearchAsignado">
                                        <i aria-hidden="true" class="i-search-5"></i>
                                        Buscar
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-6">
                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-calendar-4"></i></span> Fechas y estado</p>

                        <div class="form-group">
                            <label class="pv2-label" for="txtDate">Fecha registro <span class="pv2-req">*</span></label>
                            <div class="input-group date" data-date-format="yyyy-mm-dd">
                                <input size="16" class="form-control" type="text" autocomplete="off" name="txtDate" id="txtDate" value="<?= $objTransactionMaster->transactionOn; ?>" />
                                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtNextVisit">Fecha de entrega</label>
                            <div class="input-group date" data-date-format="yyyy-mm-dd">
                                <input size="16" class="form-control" type="text" autocomplete="off" name="txtNextVisit" id="txtNextVisit" value="<?= $objTransactionMaster->nextVisit; ?>" />
                                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtStatusID">Estado</label>
                            <select name="txtStatusID" id="txtStatusID" class="select2">
                                <option></option>
                                <?php
                                if (isset($objListWorkflowStage))
                                    foreach ($objListWorkflowStage as $ws) {
                                        if ($ws->workflowStageID == $objTransactionMaster->statusID) {
                                            echo "<option value='" . $ws->workflowStageID . "' selected>" . $ws->name . "</option>";
                                        }else{
                                            echo "<option value='" . $ws->workflowStageID . "'>" . $ws->name . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtPriorityID">Prioridad</label>
                            <select name="txtPriorityID" id="txtPriorityID" class="select2">
                                <option></option>
                                <?php
                                if (isset($objListPrioridad))
                                    foreach ($objListPrioridad as $pr) {
                                        if ($pr->catalogItemID == $objTransactionMaster->priorityID) {
                                            echo "<option value='" . $pr->catalogItemID . "' selected>" . $pr->name . "</option>";
                                        }else{
                                            echo "<option value='" . $pr->catalogItemID . "'>" . $pr->name . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtAreaID">Categoria</label>
                            <select name="txtAreaID" id="txtAreaID" class="select2">
                                <option></option>
                                <?php
                                if (isset($objListCategoria))
                                    foreach ($objListCategoria as $cat) {
                                        if ($cat->catalogItemID == $objTransactionMaster->areaID) {
                                            echo "<option value='" . $cat->catalogItemID . "' selected>" . $cat->name . "</option>";
                                        }else{
                                            echo "<option value='" . $cat->catalogItemID . "'>" . $cat->name . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seguimiento -->
            <div class="pv2-card" id="referencias">
                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-tags-2"></i></span> Seguimiento</p>
                <div class="alert alert-warning" id="avisoSinComentarios" style="display:none">Si guarda sin ninguna fila, los comentarios anteriores <strong>no se borran</strong>. Para quitar un comentario, deje al menos otra fila.</div>
                <div class="pv2-table-wrap">
                    <table class="pv2-table" id="tableComments">
                        <thead>
                            <tr>
                                <th>Descripción</th>
                                <th>Situación</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="row_razon" id="filaEntrada">
                                <td class="afx-col-block" data-label="Descripción">
                                    <input class="form-control" type="text" autocomplete="off" name="txtComentarioTarea" id="txtComentarioTarea" value="" />
                                    <label id="errorLabel" for="txtComentarioTarea" class="text-danger" style="display:none">Este campo no puede estar vacío</label>
                                </td>
                                <td class="afx-col-block" data-label="Situación">
                                    <select name="txtSelectComments" id="txtSelectComments" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if(isset($objListComments)){
                                            foreach($objListComments as $ws){
                                                if($count == 0 )
                                                    echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                                else
                                                    echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                                $count++;
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td class="afx-col-block" data-label="">
                                    <a href="#" class="btn btn-default" id="btnAddComments"><i class="fas fa-plus"></i> Agregar</a>
                                </td>
                            </tr>
                            <!-- Aquí se agregarán las filas dinámicamente -->

                            <!-- Filas dinámicas obtenidas de la base de datos -->
                            <?php
                            if(isset($objDetalleComentariosTarea)){
                                foreach($objDetalleComentariosTarea as $value):
                                    ?>
                                    <tr class="row_razon">
                                        <td class="afx-col-block" data-label="Descripción">
                                            <input type="hidden" name="commentsDetailID[]" value="<?= $value->transactionMasterDetailID?>" />
                                            <input class="form-control" type="text" autocomplete="off" name="txtComentarioTallerArray[]" value="<?= $value->reference1 ?>">
                                        </td>
                                        <td class="afx-col-block" data-label="Situación">
                                            <select name="txtCommentsIDArray[]" class="select2">
                                                <option></option>
                                                <?php
                                                if(isset($objListComments)){
                                                    foreach($objListComments as $ws):?>
                                                        <option value='<?=$ws->catalogItemID?>' <?= $ws->catalogItemID== $value->catalogStatusID ? 'selected' : '' ?> ><?=$ws->name?></option>
                                                    <?php
                                                    endforeach;
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="pv2-col-del">
                                            <button type="button" class="btn btn-link pv2-btn-del" title="Quitar" onclick="fnEliminarFila(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php
                                endforeach;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="pv2-card" id="comentarios">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-pencil-4"></i></span> Comentario</p>
                        <div class="form-group">
                            <label class="pv2-label" for="txtNote">Comentario</label>
                            <textarea class="form-control" rows="4" name="txtNote" id="txtNote"><?= $objTransactionMaster->note ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-grid-2"></i></span> Más datos</p>
                        <div class="form-group">
                            <label class="pv2-label" for="txtReference1">Referencia 1</label>
                            <input class="form-control" type="text" autocomplete="off" name="txtReference1" id="txtReference1" value="<?= $objTransactionMaster->reference1?>"/>
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtReference2">Despues de</label>
                            <input class="form-control" type="text" autocomplete="off" name="txtReference2" id="txtReference2" value="<?= $objTransactionMaster->reference2?>" />
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtReference3">Orden</label>
                            <input class="form-control" type="text" autocomplete="off" name="txtReference3" id="txtReference3" value="<?= $objTransactionMaster->reference3?>" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="pv2-card" id="archivos">
                <div class="pv2-detail-head">
                    <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Archivos</p>
                    <a href="#" class="btn btn-default" id="btnClickArchivo"><i class="icon16 i-file"></i> Abrir archivos</a>
                </div>
            </div>
        </div>
    </form>
    <!-- /formulario -->

    <!-- barra inferior (telefono) -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label">Tarea #<?= $objTransactionMaster->transactionNumber ?></p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>
