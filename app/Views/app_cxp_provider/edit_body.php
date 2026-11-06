<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-bus"></i></div>
            <div>
                <p class="pv2-header-title">CODIGO:#<span class="invoice-num"><?php echo $objProvider->providerNumber; ?></span></p>
                <p class="pv2-header-sub">Revisa o actualiza los datos del proveedor</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_cxp_provider/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
            <a href="#" class="btn btn-default" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
    <form id="form-new-cxp-provider" name="form-new-cxp-provider" role="form" autocomplete="off">
        <div class="printArea">

            <ul id="myTab" class="nav nav-tabs">
                <li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
                <li><a href="#profile" data-toggle="tab">Referencias.</a></li>
                <li><a href="#profile-phones" data-toggle="tab">Telefonos.</a></li>
                <li><a href="#profile-email" data-toggle="tab">Email.</a></li>
                <li><a href="#profile-cxc-line" data-toggle="tab">CXC Lineas.</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Mas <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#dropdown" data-toggle="tab">Direccion</a></li>
                        <li><a id="btnClickArchivo" href="#dropdown-file" data-toggle="tab">Archivos</a></li>
                    </ul>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade in active" id="home">
                    <input type="hidden" name="txtCompanyID" value="<?php echo $objProvider->companyID; ?>">
                    <input type="hidden" name="txtBranchID" value="<?php echo $objProvider->branchID; ?>">
                    <input type="hidden" name="txtEntityID" value="<?php echo $objProvider->entityID; ?>">

                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Datos generales</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtFirstName">Primer Nombre</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtFirstName" id="txtFirstName" value="<?php echo $objNatural->firstName; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtLastName">Segundo Nombre</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtLastName" id="txtLastName" value="<?php echo $objNatural->lastName; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtLegalName">Nombre Legal</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtLegalName" id="txtLegalName" value="<?php echo $objLegal->legalName; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtCommercialName">Nombre Comercial</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtCommercialName" id="txtCommercialName" value="<?php echo $objLegal->comercialName; ?>">
                                    <span class="pv2-help">Escriba al menos uno de los nombres.</span>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtIdentificationTypeID">Tipo Identificacion</label>
                                    <select name="txtIdentificationTypeID" id="txtIdentificationTypeID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListIdentificationType)
                                        foreach($objListIdentificationType as $ws){
                                            if($ws->catalogItemID == $objProvider->identificationTypeID )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtIdentification">Identificacion <span class="pv2-req">*</span></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtIdentification" id="txtIdentification" value="<?php echo $objProvider->numberIdentification; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtIsLocal">Es Local</label>
                                    <input type="checkbox" name="txtIsLocal" id="txtIsLocal" value="1" <?php echo ($objProvider->isLocal ? "checked" : ""); ?> >
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Clasificacion</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtStatusID">Estado</label>
                                    <select name="txtStatusID" id="txtStatusID" class="select2">
                                        <option></option>
                                        <?php
                                        if($objListWorkflowStage)
                                        foreach($objListWorkflowStage as $ws){
                                            if( $ws->workflowStageID == $objProvider->statusID)
                                            echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->workflowStageID."'>".$ws->name."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtClasificationID">Clasificacion</label>
                                    <select name="txtClasificationID" id="txtClasificationID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListClasificationID)
                                        foreach($objListClasificationID as $ws){
                                            if($ws->catalogItemID == $objProvider->providerClasificationID )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtCategoryID">Categoria</label>
                                    <select name="txtCategoryID" id="txtCategoryID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListCategoryID)
                                        foreach($objListCategoryID as $ws){
                                            if($ws->catalogItemID == $objProvider->providerCategoryID )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtTypePayID">Tipo de Pago</label>
                                    <select name="txtTypePayID" id="txtTypePayID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListPayConditionID)
                                        foreach($objListPayConditionID as $ws){
                                            if($ws->catalogItemID == $objProvider->payConditionID )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtProviderTypeID">Tipo</label>
                                    <select name="txtProviderTypeID" id="txtProviderTypeID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListProviderTypeID)
                                        foreach($objListProviderTypeID as $ws){
                                            if($ws->catalogItemID == $objProvider->providerType )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtCurrencyID">Moneda</label>
                                    <select name="txtCurrencyID" id="txtCurrencyID" class="select2">
                                        <option></option>
                                        <?php
                                        if($objListCurrency)
                                        foreach($objListCurrency as $ws){
                                            if($ws->currencyID == $objProvider->currencyID )
                                            echo "<option value='".$ws->currencyID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->currencyID."'  >".$ws->name."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="profile">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card is-required">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-home"></i></span> Ubicacion y balance</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtCountryID">Pais <span class="pv2-req">*</span></label>
                                    <select name="txtCountryID" id="txtCountryID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListCountry)
                                        foreach($objListCountry as $ws){
                                            if( $ws->catalogItemID == $objProvider->countryID)
                                            echo "<option value='".$ws->catalogItemID."' selected  >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtStateID">Departamento <span class="pv2-req">*</span></label>
                                    <select name="txtStateID" id="txtStateID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListState)
                                        foreach($objListState as $ws){
                                            if( $ws->catalogItemID == $objProvider->stateID)
                                            echo "<option value='".$ws->catalogItemID."' selected  >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtCityID">Municipio <span class="pv2-req">*</span></label>
                                    <select name="txtCityID" id="txtCityID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListCity)
                                        foreach($objListCity as $ws){
                                            if( $ws->catalogItemID == $objProvider->cityID)
                                            echo "<option value='".$ws->catalogItemID."' selected  >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtBalanceCor">Balance Cordobas</label>
                                    <input class="form-control pv2-num afx-monto" readonly type="text" autocomplete="off" name="txtBalanceCor" id="txtBalanceCor" value="<?php echo sprintf("%01.4f", $objProvider->balanceCor); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtBalanceDol">Balance Dolares</label>
                                    <input class="form-control pv2-num afx-monto" readonly type="text" autocomplete="off" name="txtBalanceDol" id="txtBalanceDol" value="<?php echo sprintf("%01.4f", $objProvider->balanceDol); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-tags-2"></i></span> Referencias</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtReference1">Referencia1</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference1" id="txtReference1" value="<?php echo $objProvider->reference1; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtReference2">Referencia2</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference2" id="txtReference2" value="<?php echo $objProvider->reference2; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtDistancia">Distancia</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtDistancia" id="txtDistancia" value="<?php echo $objProvider->distancia; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtDayDelevery">No. Dias</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtDayDelevery" id="txtDayDelevery" value="<?php echo $objProvider->deleveryDay; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtDayDeleveryReal">No. Dias #</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtDayDeleveryReal" id="txtDayDeleveryReal" value="<?php echo $objProvider->deleveryDayReal; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="profile-phones">
                    <div class="pv2-card">
                        <div class="pv2-detail-head">
                            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-phone"></i></span> Telefonos</p>
                            <div class="pv2-actions">
                                <a href="#" class="btn btn-default" id="btnNewPhones"><i class="icon16 i-plus"></i> Agregar</a>
                                <a href="#" class="btn btn-danger" id="btnDeletePhones"><i class="icon16 i-remove"></i> Eliminar</a>
                            </div>
                        </div>
                        <span class="pv2-help">Marque la casilla de una fila y pulse Eliminar para quitarla.</span>
                        <div class="pv2-table-wrap">
                            <table id="tb_detail_phone" class="pv2-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>entityPhoneID</th>
                                        <th>entityPhoneTypeID</th>
                                        <th>Tipo</th>
                                        <th>Numero</th>
                                        <th>Primario</th>
                                    </tr>
                                </thead>
                                <tbody id="body_detail_phone">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="profile-email">
                    <div class="pv2-card">
                        <div class="pv2-detail-head">
                            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-envelop"></i></span> Correos</p>
                            <div class="pv2-actions">
                                <a href="#" class="btn btn-default" id="btnNewEmail"><i class="icon16 i-plus"></i> Agregar</a>
                                <a href="#" class="btn btn-danger" id="btnDeleteEmail"><i class="icon16 i-remove"></i> Eliminar</a>
                            </div>
                        </div>
                        <span class="pv2-help">Marque la casilla de una fila y pulse Eliminar para quitarla.</span>
                        <div class="pv2-table-wrap">
                            <table id="tb_detail_email" class="pv2-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>entityEmailID</th>
                                        <th>Email</th>
                                        <th>Primario</th>
                                    </tr>
                                </thead>
                                <tbody id="body_detail_email">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="profile-cxc-line">
                    <div class="pv2-card">
                        <div class="pv2-detail-head">
                            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-coins"></i></span> Lineas de credito</p>
                            <div class="pv2-actions">
                                <a href="#" class="btn btn-default" id="btnNewLine"><i class="icon16 i-plus"></i> Agregar</a>
                                <a href="#" class="btn btn-default" id="btnEditLine"><i class="icon16 i-pencil-4"></i> Editar</a>
                                <a href="#" class="btn btn-danger" id="btnDeleteLine"><i class="icon16 i-remove"></i> Eliminar</a>

                                <div class="btn-group">
                                    <button class="btn btn-default">mostrar</button>
                                    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                    <ul class="dropdown-menu pull-right">
                                        <li class="active"><a href="#" class="toggle-vis" data-column="14"><i class="fa fa-tint"></i>Linea</a></li>
                                        <li class="active"><a href="#" class="toggle-vis" data-column="15"><i class="fa fa-tint"></i>Numero</a></li>
                                        <li class="active"><a href="#" class="toggle-vis" data-column="16"><i class="fa fa-tint"></i>Limite</a></li>
                                        <li class="divider"></li>
                                        <li class="active"><a href="#" class="toggle-vis" data-column="17"><i class="fa fa-tint"></i>Balance</a></li>
                                        <li class="active"><a href="#" class="toggle-vis" data-column="18"><i class="fa fa-tint"></i>Estado</a></li>
                                        <li class="active"><a href="#" class="toggle-vis" data-column="19"><i class="fa fa-tint"></i>Moneda</a></li>
                                        <li class="divider"></li>
                                        <li class="active"><a href="#" class="toggle-vis" data-column="21"><i class="fa fa-tint"></i>Plan</a></li>
                                        <li class="active"><a href="#" class="toggle-vis" data-column="23"><i class="fa fa-tint"></i>Interes</a></li>
                                        <li class="active"><a href="#" class="toggle-vis" data-column="22"><i class="fa fa-tint"></i>Frecuencia</a></li>
                                        <li class="active"><a href="#" class="toggle-vis" data-column="24"><i class="fa fa-tint"></i>No Pagos</a></li>
                                        <li class="active"><a href="#" class="toggle-vis" data-column="25"><i class="fa fa-tint"></i>Dias Excluidos</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <span class="pv2-help">Marque la casilla de una fila y pulse Editar o Eliminar.</span>
                        <div class="pv2-table-wrap">
                            <table id="tb_detail_credit_line" class="pv2-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>providerCreditLineID</th>
                                        <th>creditLineID</th>
                                        <th>currencyID</th>
                                        <th>statusID</th>
                                        <th>InterestYear</th>
                                        <th>InterestPay</th>
                                        <th>TotalPay</th>
                                        <th>TotalDefeated</th>
                                        <th>DateOpen</th>
                                        <th>PeriodPay</th>
                                        <th>DateLastPay</th>
                                        <th>Term</th>
                                        <th>Note</th>
                                        <th>Linea</th>
                                        <th>Numero</th>
                                        <th>Limite</th>
                                        <th>Balance</th>
                                        <th>Estado</th>
                                        <th>Moneda</th>
                                        <th>Tipo Amortization</th>
                                        <th>Plan</th>
                                        <th>Frecuencia</th>
                                        <th>Interes Anual</th>
                                        <th>No. Pagos</th>
                                        <th>dayExcludedID</th>
                                    </tr>
                                </thead>
                                <tbody id="body_detail_line">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="dropdown">
                    <div class="pv2-card">
                        <div class="form-group">
                            <label class="pv2-label" for="txtAddress">Direccion</label>
                            <textarea class="form-control" id="txtAddress" name="txtAddress" rows="6"><?php echo $objProvider->address; ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="dropdown-file">
                </div>
            </div>
        </div>
    </form>
    <!-- /formulario -->

    <!-- barra inferior (telefono) -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label">Proveedor #<?php echo $objProvider->providerNumber; ?></p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>
