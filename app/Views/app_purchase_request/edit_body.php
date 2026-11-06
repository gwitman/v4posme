<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-storage-2"></i></div>
            <div>
                <p class="pv2-header-title">CODIGO:#<span class="invoice-num"><?php echo $objTransactionMaster->transactionNumber; ?></span> <span class="pv2-chip">Editando</span></p>
                <p class="pv2-header-sub">Actualiza los datos del registro</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_purchase_request/add" class="btn btn-default" id="btnNuevo"><i class="icon16 i-plus"></i> Nuevo</a>
            <a href="<?php echo base_url(); ?>/app_purchase_request/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
            <a href="#" class="btn btn-default" id="btnAudit"><i class="icon16 i-pen-3"></i> Auditoria</a>
            <a href="#" class="btn btn-default <?php echo getBehavio($company->type,"app_purchase_request","classBtnTaller","");  ?>" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir E.</a>
            <a href="#" class="btn btn-default <?php echo getBehavio($company->type,"app_purchase_request","classBtnTaller","");  ?>" id="btnPrinterOutput"><i class="icon16 i-print"></i> Imprimir S.</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
    <form id="form-new-invoice" name="form-new-invoice" role="form" enctype="multipart/form-data" autocomplete="off">
        <div class="printArea">

            <ul id="myTab" class="nav nav-tabs">
                <li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
                <li><a href="#profile" data-toggle="tab">Referencias</a></li>
                <li><a href="#dropdown" data-toggle="tab">Comentario</a></li>
                <li id="btnClickArchivo"><a href="#dropdown-file" data-toggle="tab">Archivos</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade in active" id="home">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <input type="hidden" name="txtCompanyID" value="<?php echo $objTransactionMaster->companyID; ?>">
                            <input type="hidden" name="txtTransactionID" value="<?php echo $objTransactionMaster->transactionID; ?>">
                            <input type="hidden" name="txtTransactionMasterID" id="txtTransactionMasterID" value="<?php echo $objTransactionMaster->transactionMasterID; ?>">
                            <input type="hidden" name="txtRoleID" id="txtRoleID" value="<?php echo $roleID; ?>">
                            <input type="hidden" name="txtUserID" id="txtUserID" value="<?php echo $userID; ?>">

                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-calendar-4"></i></span> Documento</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDate">Fecha <span class="pv2-req">*</span></label>
                                    <div>
                                        <div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
                                            <input size="16" class="form-control" type="text" autocomplete="off" name="txtDate" id="txtDate" value="<?php echo $objTransactionMaster->transactionOn; ?>">
                                            <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_request","classTxtSucursal",""); ?>">
                                    <label class="pv2-label" for="txtBranchID">Sucursal</label>
                                    <div>
                                        <select name="txtBranchID" id="txtBranchID" class="select2">
                                            <?php
                                            $counter = 0;
                                            if($objListBranch)
                                            foreach($objListBranch as $ws){
                                                if($ws->branchID == $objTransactionMaster->branchID )
                                                    echo "<option value='".$ws->branchID."' selected >".$ws->name."</option>";
                                                else
                                                    echo "<option value='".$ws->branchID."' >".$ws->name."</option>";

                                                $counter++;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_request","divTxtApplied","");  ?>">
                                    <label class="pv2-label" for="txtIsApplied">Aplicado</label>
                                    <div>
                                        <input type="checkbox" disabled name="txtIsApplied" id="txtIsApplied" value="1" <?php if($objTransactionMaster->isApplied) echo "checked"; ?> >
                                    </div>
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_request","divTxtChange","");  ?>">
                                    <label class="pv2-label" for="txtExchangeRate">Cambio</label>
                                    <div>
                                        <input class="form-control" type="text" autocomplete="off" disabled="disabled" name="txtExchangeRate" id="txtExchangeRate" value="<?php echo $exchangeRate; ?>">
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_request","divTxtStatus","");  ?>">
                                    <label class="pv2-label" for="txtStatusID"><?php echo getBehavio($company->type,"app_purchase_request","divLabelStatus","Estado");  ?></label>
                                    <div>
                                        <select name="txtStatusID" id="txtStatusID" class="select2">
                                            <option></option>
                                            <?php
                                            if($objListWorkflowStage){
                                                if(count($objListWorkflowStage) > 0 )
                                                {
                                                    foreach($objListWorkflowStage as $ws){

                                                        if($ws == null)
                                                            continue;

                                                        if($ws->workflowStageID == $objTransactionMaster->statusID)
                                                            echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
                                                        else
                                                            echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
                                                    }
                                                }
                                                else
                                                {
                                                    if($objListWorkflowStageCurrent)
                                                    {
                                                        echo "<option value='".$objListWorkflowStageCurrent[0]->workflowStageID."' selected>".$objListWorkflowStageCurrent[0]->name."</option>";
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                if($objListWorkflowStageCurrent)
                                                {
                                                    echo "<option value='".$objListWorkflowStageCurrent[0]->workflowStageID."' selected>".$objListWorkflowStageCurrent[0]->name."</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Personas</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtCustomerDescription">Cliente <span class="pv2-req">*</span></label>
                                    <div>
                                        <div class="input-group afx-search">
                                            <input type="hidden" id="txtCustomerID" name="txtCustomerID" value="<?php echo $objCustomer->entityID;  ?>">
                                            <input class="form-control" readonly id="txtCustomerDescription" type="text" autocomplete="off" value="<?php echo $objCustomerNatural != null ? strtoupper($objCustomer->customerNumber . " ". $objCustomerNatural->firstName . " ". $objCustomerNatural->lastName ) : strtoupper($objCustomer->customerNumber." ".$objCustomerLegal->comercialName); ?>">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" id="btnClearCustomer">
                                                    <i aria-hidden="true" class="i-undo-2"></i>
                                                    Limpiar
                                                </button>
                                            </span>
                                            <span class="input-group-btn">
                                                <button class="btn btn-primary" type="button" id="btnSearchCustomer">
                                                    <i aria-hidden="true" class="i-search-5"></i>
                                                    Buscar
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtEmployerDescription"><?php echo getBehavio($company->type,"app_purchase_request","labelTecnico","Tecnico");  ?> <span class="pv2-req">*</span></label>
                                    <div>
                                        <div class="input-group afx-search">
                                            <input type="hidden" id="txtEmployerID" name="txtEmployerID" value="<?php echo $objEmployer != null ? $objEmployer->entityID : 0 ;  ?>">
                                            <input class="form-control" readonly id="txtEmployerDescription" type="text" autocomplete="off" value="<?php echo $objEmployerNatural ? ($objEmployerNatural != null ? strtoupper($objEmployer->employeNumber . " ". $objEmployerNatural->firstName . " ". $objEmployerNatural->lastName ) : strtoupper($objEmployer->employeNumber." ".$objEmployerLegal->comercialName)) : ""; ?>">
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

                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Datos del registro</p>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_request","classNumber","");  ?>">
                                    <label class="pv2-label" for="txtNumber">Numero</label>
                                    <div>
                                        <input class="form-control" type="text" autocomplete="off" name="txtNumber" id="txtNumber" value="<?php echo $objTransactionMaster->descriptionReference; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtAreaID"><?php echo getBehavio($company->type,"app_purchase_request","divLabelStatusMachine","Estado del equipo");  ?></label>
                                    <div>
                                        <select name="txtAreaID" id="txtAreaID" class="select2">
                                            <?php
                                            if($objListEstadosEquipo)
                                            foreach($objListEstadosEquipo as $ws){
                                                if($ws->catalogItemID == $objTransactionMaster->areaID)
                                                    echo "<option value='".$ws->catalogItemID."' selected>".$ws->name."</option>";
                                                else
                                                    echo "<option value='".$ws->catalogItemID."' >".$ws->name."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtRouteID"><?php echo getBehavio($company->type,"app_purchase_request","labelArticulo","Articulo");  ?></label>
                                    <div>
                                        <select name="txtRouteID" id="txtRouteID" class="select2">
                                            <?php
                                            if($objListArticulos)
                                            foreach($objListArticulos as $ws){
                                                if($ws->catalogItemID == $objTransactionMasterInfo->routeID)
                                                    echo "<option value='".$ws->catalogItemID."' selected>".$ws->name."</option>";
                                                else
                                                    echo "<option value='".$ws->catalogItemID."' >".$ws->name."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtZoneID"><?php echo getBehavio($company->type,"app_purchase_request","labelMarca","Marca");  ?></label>
                                    <div>
                                        <select name="txtZoneID" id="txtZoneID" class="select2">
                                            <?php
                                            if($objListMarca)
                                            foreach($objListMarca as $ws){
                                                if($ws->catalogItemID == $objTransactionMasterInfo->zoneID)
                                                    echo "<option value='".$ws->catalogItemID."' selected>".$ws->name."</option>";
                                                else
                                                    echo "<option value='".$ws->catalogItemID."' >".$ws->name."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_request","classModelo","");  ?>">
                                    <label class="pv2-label" for="txtInfoReference2">Modelo</label>
                                    <div>
                                        <input class="form-control" type="text" autocomplete="off" name="txtInfoReference2" id="txtInfoReference2" value="<?php echo $objTransactionMasterInfo->reference2; ?>">
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_request","classSerie","");  ?>">
                                    <label class="pv2-label" for="txtReference4">Serie</label>
                                    <div>
                                        <input class="form-control" type="text" autocomplete="off" name="txtReference4" id="txtReference4" value="<?php echo $objTransactionMaster->reference4; ?>">
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_request","classAccesorios","");  ?>">
                                    <label class="pv2-label" for="txtPriorityID"><?php echo getBehavio($company->type,"app_purchase_request","labelAccesorios","Accesorios");  ?></label>
                                    <div>
                                        <select name="txtPriorityID" id="txtPriorityID" class="select2">
                                            <?php
                                            $counter = 0;
                                            if($objListAccesorios)
                                            foreach($objListAccesorios as $ws){
                                                if($counter == 0)
                                                    echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                                else
                                                    echo "<option value='".$ws->catalogItemID."' >".$ws->name."</option>";

                                                $counter++;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtTypeID">Tipo</label>
                                    <div>
                                        <select name="txtTypeID" id="txtTypeID" class="select2">
                                            <option></option>
                                            <?php
                                            if($objListTipo)
                                            foreach($objListTipo as $ws){
                                                if($ws->catalogItemID == $objTransactionMaster->printerQuantity)
                                                    echo "<option value='".$ws->catalogItemID."' selected>".$ws->name."</option>";
                                                else
                                                    echo "<option value='".$ws->catalogItemID."'>".$ws->name."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_request","classDescOtros","");  ?>">
                                    <label class="pv2-label" for="txtReferenceClientName">Cotizacion</label>
                                    <div>
                                        <input class="form-control" type="text" autocomplete="off" name="txtReferenceClientName" id="txtReferenceClientName" value="<?php echo $objTransactionMasterInfo->referenceClientName; ?>">
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_request","classTxtFactura","");  ?>">
                                    <label class="pv2-label" for="txtNoteDescription">Factura</label>
                                    <div>
                                        <div class="input-group afx-search">
                                            <input type="hidden" id="txtNote" name="txtNote" value="<?php echo  $objTransactionMaster->note;  ?>">
                                            <input class="form-control" readonly id="txtNoteDescription" type="text" autocomplete="off" value="">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" id="btnClearNote">
                                                    <i aria-hidden="true" class="i-undo-2"></i>
                                                    Limpiar
                                                </button>
                                            </span>
                                            <span class="input-group-btn">
                                                <button class="btn btn-primary" type="button" id="btnSearchNote">
                                                    <i aria-hidden="true" class="i-search-5"></i>
                                                    Buscar
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-grid-2"></i></span> Detalle</p>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_request","divTxtCurrency","");  ?>">
                                    <label class="pv2-label" for="txtCurrencyID">Moneda</label>
                                    <div>
                                        <select name="txtCurrencyID" id="txtCurrencyID" class="select2">
                                            <?php
                                            if($objListCurrency)
                                            foreach($objListCurrency as $ws){
                                                if($ws->currencyID == $objTransactionMaster->currencyID)
                                                    echo "<option value='".$ws->currencyID."' selected>".$ws->simb."</option>";
                                                else
                                                    echo "<option value='".$ws->currencyID."' >".$ws->simb."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_request","classProblema","");  ?>">
                                    <label class="pv2-label" for="txtDetailReference2">Problema</label>
                                    <div>
                                        <textarea class="form-control" name="txtDetailReference2" id="txtDetailReference2" rows="8"><?php echo $objTransactionMaster->reference2; ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDetailReference1"><?php echo getBehavio($company->type,"app_purchase_request","labelNota","Nota");  ?></label>
                                    <div>
                                        <textarea class="form-control" name="txtDetailReference1" id="txtDetailReference1" rows="8"><?php echo $objTransactionMaster->reference1; ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_request","classPassword","");  ?>">
                                    <label class="pv2-label" for="txtInfoReference1">Contraseña</label>
                                    <div>
                                        <input class="form-control" type="text" autocomplete="off" name="txtInfoReference1" id="txtInfoReference1" value="<?php echo $objTransactionMasterInfo->reference1; ?>">
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_request","classSolucion","");  ?>">
                                    <label class="pv2-label" for="txtDetailReference3">Solución</label>
                                    <div>
                                        <textarea class="form-control" name="txtDetailReference3" id="txtDetailReference3" rows="8"><?php echo $objTransactionMaster->reference3; ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_request","classAmount","");  ?>">
                                    <label class="pv2-label" for="txtDetailAmount">Monto</label>
                                    <div>
                                        <input class="form-control afx-monto pv2-num" inputmode="decimal" type="text" autocomplete="off" name="txtDetailAmount" id="txtDetailAmount" value="<?php echo sprintf("%01.4f",$objTransactionMaster->amount); ?>">
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_request","classLabel","");  ?>">
                                    <div>
                                        <blockquote class="red" style="text-align: justify;">
                                            Agregar cualquier nota al cliente (rayones, quebraduras) Observaciones (Al no encender el equipo, no sabemos si algún componente este dañado tal como batería,pantalla,tecaldo etc.)
                                        </blockquote>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="profile">
                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-pencil-4"></i></span> Comentarios</p>
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
                                            <input class="form-control" type="text" autocomplete="off" name="txtComentarioTaller" id="txtComentarioTaller" value="">
                                            <label id="errorLabel" for="txtComentarioTaller" class="text-danger" style="display:none">Este campo no puede estar vacío</label>
                                        </td>
                                        <td class="afx-col-block" data-label="Situación">
                                            <label class="sr-only" for="txtSelectComments"></label>
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
                                    if(isset($objDetalleComentariosTaller)){
                                        foreach($objDetalleComentariosTaller as $value):
                                        ?>
                                            <tr class="row_razon">
                                                <td class="afx-col-block" data-label="Descripción">
                                                    <input type="hidden" name="commentsDetailID[]" value="<?= $value->transactionMasterDetailID?>" />
                                                    <input class="form-control txtComentarioTallerArray" type="text" autocomplete="off" name="txtComentarioTallerArray[]" value="<?= $value->reference1 ?>">
                                                </td>
                                                <td class="afx-col-block" data-label="Situación">
                                                    <label class="sr-only" for="txtCommentsID">Seleccionar Situación</label>
                                                    <select name="txtCommentsIDArray[]" id="comboCommentsId" class="select2 comboCommentsIdClass">
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
                                                <td class="pv2-col-del">
                                                    <button type="button" class="btn btn-link pv2-btn-del btnDeleteReferencias" title="Quitar" onclick="fnEliminarFila(this)">
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

                <div class="tab-pane fade" id="dropdown"></div>

                <div class="tab-pane fade" id="dropdown-file">
                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Archivos</p>
                        <div class="pv2-table-wrap">
                            <table class="pv2-table">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Archivo</th>
                                    </tr>
                                </thead>
                                <tbody id="body_detail_file">
                                    <?php
                                    if($objTransactionMasterDetailDocument)
                                    foreach($objTransactionMasterDetailDocument as $ws)
                                    {
                                        ?>
                                        <tr class="row_razon">
                                            <td data-label="Tipo">
                                                <input type="hidden" name="txtFileID[]" value="<?php echo $ws->transactionMasterDetailID; ?>">
                                                <input type="hidden" name="txtFileTypeID[]" value="<?php echo $ws->componentItemID; ?>">
                                                <span class="badge badge-inverse"><?php echo $ws->tipoFile; ?></span>
                                            </td>
                                            <td class="afx-col-block" data-label="Archivo">
                                                <input type="file" name="txtFileDocument[]" value="<?php echo $ws->reference3; ?>">
                                                <a class="btn btn-default tal-file-link" href="<?php echo $objParameterUrlServerFile."/resource/file_company/company_2/component_98/component_item_".$objTransactionMaster->transactionMasterID."/".$ws->reference3; ?>" target="_blank">
                                                    <?php echo $ws->reference3; ?>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
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
            <p class="pv2-mbar-label">Codigo #<?php echo $objTransactionMaster->transactionNumber; ?></p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>
