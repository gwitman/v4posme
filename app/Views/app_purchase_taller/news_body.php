<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-storage-2"></i></div>
            <div>
                <p class="pv2-header-title">CODIGO:#<span class="invoice-num">00000000</span> <span class="pv2-chip">Nuevo</span></p>
                <p class="pv2-header-sub">Completa los datos del registro</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_purchase_taller/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
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
                <li><a href="#dropdown" data-toggle="tab">Datos</a></li>
                <li><a href="#dropdown-file" data-toggle="tab">Archivos</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade in active" id="home">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <input type="hidden" name="txtRoleID" id="txtRoleID" value="<?php echo $roleID; ?>">
                            <input type="hidden" name="txtUserID" id="txtUserID" value="<?php echo $userID; ?>">
                            <input type="hidden" name="txtTransactionMasterID" id="txtTransactionMasterID" value="0">

                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-calendar-4"></i></span> Documento</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDateOnly">Fecha <span class="pv2-req">*</span></label>
                                    <div>
                                        <div class="tal-datetime">
                                            <div id="datepicker" class="input-group date tal-datetime-item" data-date-format="yyyy-mm-dd">
                                                <input size="16" class="form-control" type="text" autocomplete="off" name="txtDateOnly" id="txtDateOnly">
                                                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                            </div>
                                            <div class="input-group tal-datetime-item">
                                                <input class="form-control" type="time" autocomplete="off" name="txtDateTime" id="txtDateTime" value="">
                                                <span class="input-group-addon"><i class="icon16 i-clock"></i></span>
                                            </div>
                                        </div>
                                        <input type="hidden" name="txtDate" id="txtDate" value="">
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_taller","classTxtSucursal",""); ?> ">
                                    <label class="pv2-label" for="txtBranchID">Sucursal</label>
                                    <div>
                                        <select name="txtBranchID" id="txtBranchID" class="select2">
                                            <?php
                                            $counter = 0;
                                            if($objListBranch)
                                            foreach($objListBranch as $ws){
                                                if($counter == 0 )
                                                    echo "<option value='".$ws->branchID."' ".getBehavio($company->type,"app_purchase_taller","attrSelectedSucursal","selected")." >".$ws->name."</option>";
                                                else
                                                    echo "<option value='".$ws->branchID."' >".$ws->name."</option>";

                                                $counter++;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_taller","divTxtApplied","");  ?> ">
                                    <label class="pv2-label" for="txtIsApplied">Aplicado</label>
                                    <div>
                                        <input type="checkbox" disabled name="txtIsApplied" id="txtIsApplied" value="1">
                                    </div>
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_taller","divTxtChange","");  ?>">
                                    <label class="pv2-label" for="txtExchangeRate">Cambio</label>
                                    <div>
                                        <input class="form-control" type="text" autocomplete="off" disabled="disabled" name="txtExchangeRate" id="txtExchangeRate" value="<?php echo $exchangeRate; ?>">
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_taller","divTxtStatus","");  ?>">
                                    <label class="pv2-label" for="txtStatusID"><?php echo getBehavio($company->type,"app_purchase_taller","divLabelStatus","Estado");  ?></label>
                                    <div>
                                        <select name="txtStatusID" id="txtStatusID" class="select2">
                                            <option></option>
                                            <?php
                                            if($objListWorkflowStage)
                                            foreach($objListWorkflowStage as $ws){
                                                echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Personas y factura</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtCustomerDescription">Cliente <span class="pv2-req">*</span></label>
                                    <div>
                                        <div class="input-group afx-search">
                                            <input type="hidden" id="txtCustomerID" name="txtCustomerID" value="">
                                            <input class="form-control" readonly id="txtCustomerDescription" type="text" autocomplete="off" value="">
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
                                    <label class="pv2-label" for="txtEmployerDescription"><?php echo getBehavio($company->type,"app_purchase_taller","labelTecnico","Tecnico");  ?> <span class="pv2-req">*</span></label>
                                    <div>
                                        <div class="input-group afx-search">
                                            <input type="hidden" id="txtEmployerID" name="txtEmployerID" value="">
                                            <input class="form-control" readonly id="txtEmployerDescription" type="text" autocomplete="off" value="">
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

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_taller","classTxtFactura","");  ?>">
                                    <label class="pv2-label" for="txtNoteDescription">Factura</label>
                                    <div>
                                        <div class="input-group afx-search">
                                            <input type="hidden" id="txtNote" name="txtNote" value="">
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

                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Datos del registro</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtAreaID"><?php echo getBehavio($company->type,"app_purchase_taller","divLabelStatusMachine","Estado del equipo");  ?></label>
                                    <div>
                                        <select name="txtAreaID" id="txtAreaID" class="select2">
                                            <?php
                                            $counter = 0;
                                            if($objListEstadosEquipo)
                                            foreach($objListEstadosEquipo as $ws){
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

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_taller","classTxtRouteID","");  ?>">
                                    <label class="pv2-label" for="txtRouteID"><?php echo getBehavio($company->type,"app_purchase_taller","labelArticulo","Articulo");  ?></label>
                                    <div>
                                        <select name="txtRouteID" id="txtRouteID" class="select2">
                                            <?php
                                            $counter = 0;
                                            if($objListArticulos)
                                            foreach($objListArticulos as $ws){
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

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_taller","classDescOtros","");  ?> ">
                                    <label class="pv2-label" for="txtReferenceClientName">Desc. otros</label>
                                    <div>
                                        <input class="form-control" type="text" autocomplete="off" name="txtReferenceClientName" id="txtReferenceClientName" value="">
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_taller","classTxtZoneID","");  ?>">
                                    <label class="pv2-label" for="txtZoneID"><?php echo getBehavio($company->type,"app_purchase_taller","labelMarca","Marca");  ?></label>
                                    <div>
                                        <select name="txtZoneID" id="txtZoneID" class="select2">
                                            <?php
                                            $counter = 0;
                                            if($objListMarca)
                                            foreach($objListMarca as $ws){
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

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_taller","classModelo","");  ?>">
                                    <label class="pv2-label" for="txtInfoReference2">Modelo</label>
                                    <div>
                                        <input class="form-control" type="text" autocomplete="off" name="txtInfoReference2" id="txtInfoReference2" value="">
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_taller","classSerie","");  ?>">
                                    <label class="pv2-label" for="txtReference4">Serie</label>
                                    <div>
                                        <input class="form-control" type="text" autocomplete="off" name="txtReference4" id="txtReference4" value="">
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_taller","classAccesorios","");  ?>">
                                    <label class="pv2-label" for="txtPriorityID"><?php echo getBehavio($company->type,"app_purchase_taller","labelAccesorios","Accesorios");  ?></label>
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
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-grid-2"></i></span> Detalle</p>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_taller","divTxtCurrency","");  ?>">
                                    <label class="pv2-label" for="txtCurrencyID">Moneda</label>
                                    <div>
                                        <select name="txtCurrencyID" id="txtCurrencyID" class="select2">
                                            <?php
                                            $counter = 0;
                                            if($objListCurrency)
                                            foreach($objListCurrency as $ws){

                                                if($counter == 0)
                                                    echo "<option value='".$ws->currencyID."' selected>".$ws->simb."</option>";
                                                else
                                                    echo "<option value='".$ws->currencyID."' >".$ws->simb."</option>";

                                                $counter++;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_taller","classProblema","");  ?>">
                                    <label class="pv2-label" for="txtDetailReference2">Problema</label>
                                    <div>
                                        <textarea class="form-control" name="txtDetailReference2" id="txtDetailReference2" rows="8"></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDetailReference1"><?php echo getBehavio($company->type,"app_purchase_taller","labelNota","Nota");  ?></label>
                                    <div>
                                        <textarea class="form-control" name="txtDetailReference1" id="txtDetailReference1" rows="8"></textarea>
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_taller","classPassword","");  ?>">
                                    <label class="pv2-label" for="txtInfoReference1">Contraseña</label>
                                    <div>
                                        <input class="form-control" type="text" autocomplete="off" name="txtInfoReference1" id="txtInfoReference1" value="">
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_taller","classSolucion","");  ?>">
                                    <label class="pv2-label" for="txtDetailReference3">Solucion</label>
                                    <div>
                                        <textarea class="form-control" name="txtDetailReference3" id="txtDetailReference3" rows="8"></textarea>
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_taller","classAmount","");  ?>">
                                    <label class="pv2-label" for="txtDetailAmount">Monto</label>
                                    <div>
                                        <input type="hidden" name="txtDetailTransactionDetailID" value="0">
                                        <input class="form-control afx-monto pv2-num" inputmode="decimal" type="text" autocomplete="off" name="txtDetailAmount" id="txtDetailAmount" value="0">
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_taller","classLabel","");  ?>">
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="dropdown">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-calendar-4"></i></span> Siguiente orden</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtNextVisitDate">Fecha de siguiente orden</label>
                                    <div>
                                        <div class="tal-datetime">
                                            <div id="datepickerNextVisit" class="input-group date tal-datetime-item" data-date-format="yyyy-mm-dd">
                                                <input size="16" class="form-control" type="text" autocomplete="off" name="txtNextVisitDate" id="txtNextVisitDate" value="">
                                                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                            </div>
                                            <div class="input-group tal-datetime-item">
                                                <input class="form-control" type="time" autocomplete="off" name="txtNextVisitTime" id="txtNextVisitTime" value="">
                                                <span class="input-group-addon"><i class="icon16 i-clock"></i></span>
                                            </div>
                                        </div>
                                        <input type="hidden" name="txtNextVisit" id="txtNextVisit" value="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtFixedAssetDescription">Activo Fijo</label>
                                    <div>
                                        <div class="input-group afx-search">
                                            <input type="hidden" id="txtFixedAssetID" name="txtFixedAssetID" value="">
                                            <input class="form-control" readonly id="txtFixedAssetDescription" type="text" autocomplete="off" value="">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" id="btnClearFixedAsset">
                                                    <i aria-hidden="true" class="i-undo-2"></i>
                                                    Limpiar
                                                </button>
                                            </span>
                                            <span class="input-group-btn">
                                                <button class="btn btn-primary" type="button" id="btnSearchFixedAsset">
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
                </div>

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
                                    if($objListArchivos)
                                    foreach($objListArchivos as $ws)
                                    {
                                        ?>
                                        <tr class="row_razon">
                                            <td data-label="Tipo">
                                                <input type="hidden" name="txtFileID[]" value="0">
                                                <input type="hidden" name="txtFileTypeID[]" value="<?php echo $ws->catalogItemID; ?>">
                                                <span class="badge badge-inverse"><?php echo $ws->name; ?></span>
                                            </td>
                                            <td class="afx-col-block" data-label="Archivo">
                                                <input type="file" name="txtFileDocument[]">
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
            <p class="pv2-mbar-label">Nuevo registro</p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>
