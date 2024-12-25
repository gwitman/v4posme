<div class="row">
    <div id="email" class="col-lg-12">

        <!-- botonera -->
        <div class="email-bar" style="border-left:1px solid #c9c9c9">
            <div class="btn-group pull-right">
                <a href="<?php echo base_url(); ?>/app_rrhh_task/add" class="btn btn-success" id="btnNuevo"><i class="icon16 i-checkmark-4"></i>Nuevo</a>
                <a href="<?php echo base_url(); ?>/app_rrhh_task/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>
                <a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
                <a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
            </div>
        </div>
        <!-- /botonera -->
    </div>
    <!-- End #email  -->
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">

            <!-- titulo de comprobante-->
            <div class="panel-heading">
                <div class="icon"><i class="icon20 i-file"></i></div>
                <h4>TAREA: #<span class="invoice-num"><?= $objTransactionMaster->transactionNumber ?></span></h4>
            </div>
            <!-- /titulo de comprobante-->

            <!-- body -->
            <form id="form-new-task" name="form-new-task" class="form-horizontal" role="form">
                <div class="panel-body printArea">
                    <ul id="myTab" class="nav nav-tabs">
                        <li class="active">
                            <a href="#home" data-toggle="tab">Informaci&oacute;n</a>
                        </li>
                        <li>
                            <a href="#referencias" data-toggle="tab">Referencias</a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">M&aacute;s <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="#comentarios" data-toggle="tab">Comentario</a></li>
                                <li id="btnClickArchivo"><a href="#archivos" data-toggle="tab">Archivos</a></li>
                            </ul>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Informacion -->
                        <div class="tab-pane fade in active" id="home">

                            <input type="hidden" name="txtCompanyID" value="<?= $objTransactionMaster->companyID; ?>">
                            <input type="hidden" name="txtTransactionID" value="<?= $objTransactionMaster->transactionID; ?>">
                            <input type="hidden" name="txtTransactionMasterID" value="<?= $objTransactionMaster->transactionMasterID; ?>">

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="txtReference4">Titulo</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtReference4" id="txtReference4" value="<?= $objTransactionMaster->reference4?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="txtDescripcion">Descripcion</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtDescripcion" id="txtDescripcion" value="<?= $objTransactionMaster->descriptionReference ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="txtDate">Fecha registro</label>
                                        <div class="col-lg-8">
                                            <div id="transactionOn" class="input-group date" data-date-format="yyyy-mm-dd">
                                                <input size="16" class="form-control" type="text" name="txtDate" id="txtDate" value="<?= $objTransactionMaster->transactionOn; ?>" />
                                                <span class="input-group-addon">
                                                    <i class="icon16 i-calendar-4"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="txtNextVisit">Fecha de entrega</label>
                                        <div class="col-lg-8">
                                            <div id="nextVisit" class="input-group date" data-date-format="yyyy-mm-dd">
                                                <input size="16" class="form-control" type="text" name="txtNextVisit" id="txtNextVisit" value="<?= $objTransactionMaster->nextVisit; ?>" />
                                                <span class="input-group-addon">
                                                    <i class="icon16 i-calendar-4"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="txtStatusID">Estado</label>
                                        <div class="col-lg-8">
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
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="txtPriorityID">Prioridad</label>
                                        <div class="col-lg-8">
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
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="txtAreaID">Categoria</label>
                                        <div class="col-lg-8">
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
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="txtEmisorDescription">Emisor</label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <input type="hidden" id="txtEmisorID" name="txtEmisorID" value="<?= $objTransactionMaster->entityIDSecondary?>" />
                                                <input class="form-control" readonly id="txtEmisorDescription" type="text" value="<?= isset($objEmisorNatural) ? strtoupper($objEmisor->entityID . " ". $objEmisorNatural->firstName . " ". $objEmisorNatural->lastName ) : strtoupper($objEmisor->entityID." ".$objEmisorLegal->comercialName); ?>" />
                                                <span class="input-group-btn">
                                                    <button class="btn btn-danger" type="button" id="btnClearEmisor">
                                                        <i aria-hidden="true" class="i-undo-2"></i>
                                                        clear
                                                    </button>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button" id="btnSearchEmisor">
                                                        <i aria-hidden="true"
                                                           class="i-search-5"></i>
                                                        buscar
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="txtAsignadoDescription">Asignado</label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <input type="hidden" id="txtAsignadoID" name="txtAsignadoID" value="<?= $objTransactionMaster->entityID?>" />
                                                <input class="form-control" readonly id="txtAsignadoDescription" type="text" value="<?= isset($objAsignadoNatural) ? strtoupper($objAsignado->entityID . " ". $objAsignadoNatural->firstName . " ". $objAsignadoNatural->lastName ) : strtoupper($objAsignado->entityID." ".$objAsignadoLegal->comercialName); ?>" />
                                                <span class="input-group-btn">
                                                    <button class="btn btn-danger" type="button" id="btnClearAsignado">
                                                        <i aria-hidden="true" class="i-undo-2"></i>
                                                        clear
                                                    </button>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button" id="btnSearchAsignado">
                                                        <i aria-hidden="true"
                                                           class="i-search-5"></i>
                                                        buscar
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="txtReference1">Referencia 1</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtReference1" id="txtReference1" value="<?= $objTransactionMaster->reference1?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="txtReference2">Referencia 2</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtReference2" id="txtReference2" value="<?= $objTransactionMaster->reference2?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="txtReference3">Referencia 3</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtReference3" id="txtReference3" value="<?= $objTransactionMaster->reference3?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Referencias -->
                        <div class="tab-pane fade in" id="referencias">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table" id="tableComments">
                                        <thead>
                                        <tr>
                                            <th>Descripción</th>
                                            <th>Situción</th>
                                            <th>Acción</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="" id="filaEntrada">
                                            <td>
                                                <input class="form-control"  type="text"  name="txtComentarioTarea" id="txtComentarioTarea" value="" />
                                                <label id="errorLabel" for="txtComentarioTarea" class="text-danger">Este campo no puede estar vacío</label>
                                            </td>
                                            <td>
                                                <label class="sr-only" for="txtSelectComments"></label>
                                                <select name="txtSelectComments" id="txtSelectComments" class="select2"  >
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
                                            <td>
                                                <a href="#" class="btn btn-flat btn-info" id="btnAddComments"><i class="fas fa-plus"></i></a>
                                            </td>
                                        </tr>
                                        <!-- Aquí se agregarán las filas dinámicamente -->

                                        <!-- Filas dinámicas obtenidas de la base de datos -->
                                        <?php
                                        if(isset($objDetalleComentariosTarea)){
                                            foreach($objDetalleComentariosTarea as $value):
                                                ?>
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="commentsDetailID[]" value="<?= $value->transactionMasterDetailID?>" />
                                                        <input class="form-control" type="text" name="txtComentarioTallerArray[]" value="<?= $value->reference1 ?>">
                                                    </td>
                                                    <td>
                                                        <label class="sr-only" for="txtCommentsID">Seleccionar Situación</label>
                                                        <select name="txtCommentsIDArray[]" id="comboCommentsId" class="select2"  >
                                                            <option></option>
                                                            <?php
                                                            $count = 0;
                                                            if(isset($objListComments)){
                                                                foreach($objListComments as $ws):?>
                                                                    <option value='<?=$ws->catalogItemID?>' <?= $ws->catalogItemID== $value->catalogStatusID ? 'selected' : '' ?> ><?=$ws->name?></option>
                                                                <?php
                                                                endforeach;
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-flat btn-danger" onclick="fnEliminarFila(this)">
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
                        </div>

                        <!-- Comentarios -->
                        <div class="tab-pane fade in" id="comentarios">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="txtNote">Comentario</label>
                                        <div class="col-lg-8">
                                            <textarea class="form-control" rows="6" name="txtNote" id="txtNote"><?= $objTransactionMaster->note ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Archivos -->
                        <div class="tab-pane fade in active" id="archivos">
                            <div class="row">
                                <div class="col-lg-12">

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- /body -->
        </div>
    </div>
</div>