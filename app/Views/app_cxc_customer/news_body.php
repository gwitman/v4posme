<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-address-book"></i></div>
            <div>
                <p class="pv2-header-title">CODIGO:#<span class="invoice-num">00000000</span></p>
                <p class="pv2-header-sub">Registra un nuevo cliente</p>
            </div>
        </div>
        <div class="pv2-actions">
            <?php
                if($callback == "false")
                {
                    ?>
                    <a href="#" class="btn btn-default <?php echo getBehavio($company->type,"app_cxc_customer","showBtnViewLeads",""); ?> " id="btnLeads"><i class="icon16 i-pencil"></i> Leads</a>
                    <a href="<?php echo base_url(); ?>/app_cxc_customer/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
                    <?php
                }
            ?>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
    <form id="form-new-cxc-customer" name="form-new-cxc-customer" role="form" autocomplete="off">
        <div class="printArea">

            <ul id="myTab" class="nav nav-tabs">
                <li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
                <li class="<?php echo getBehavio($company->type,"app_cxc_customer","divPestanaReferencias",""); ?>" ><a href="#profile" data-toggle="tab">Referencias.</a></li>
                <li class="<?php echo getBehavio($company->type,"app_cxc_customer","divPestanaTelefono",""); ?>" ><a href="#profile-phones" data-toggle="tab">Telefonos.</a></li>
                <li class="<?php echo getBehavio($company->type,"app_cxc_customer","divPestanaEmail",""); ?>" ><a href="#profile-email" data-toggle="tab">Email.</a></li>
                <li class="<?php echo getBehavio($company->type,"app_cxc_customer","divPestanaCXC",""); ?>" ><a href="#profile-cxc" data-toggle="tab">CXC.</a></li>
                <li class="<?php echo getBehavio($company->type,"app_cxc_customer","divPestanaCXCLineas",""); ?>" ><a href="#profile-cxc-line" data-toggle="tab">CXC Lineas.</a></li>
                <li class="dropdown <?php echo getBehavio($company->type,"app_cxc_customer","divPestanaMas",""); ?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Mas <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#dropdown" data-toggle="tab">Direccion</a></li>
                        <li class="<?php echo getBehavio($company->type,"app_cxc_customer","divPestanaArchivos",""); ?>" ><a href="#dropdown-file" data-toggle="tab">Archivos</a></li>
                        <li><a href="#dropdown-formapago" data-toggle="tab">Forma de Pago</a></li>
                        <li><a href="#dropdown-recordatorio" data-toggle="tab">Recordatorio</a></li>
                    </ul>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade in active" id="home">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Datos generales</p>

                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtFechaNacimiento",""); ?>" >
                                    <label class="pv2-label" for="txtBirthDate">Nacimiento</label>
                                    <div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
                                        <input type="hidden" name="txtCallback" value="<?php echo $callback; ?>"/>
                                        <input class="form-control" type="text" autocomplete="off" name="txtBirthDate" id="txtBirthDate" value="2014-01-30">
                                        <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtFechaContacto","hidden"); ?>">
                                    <label class="pv2-label" for="txtDateContract">Fecha de Contacto</label>
                                    <div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
                                        <input class="form-control" type="text" autocomplete="off" name="txtDateContract" id="txtDateContract" value="2024-02-08">
                                        <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtNombres",""); ?>  ">
                                    <label class="pv2-label" for="txtFirstName"><?php echo getBehavio($company->type,"app_cxc_customer","lblTxtNombre","*Nombres"); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtFirstName" id="txtFirstName" value="">
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtApellidos",""); ?> ">
                                    <label class="pv2-label" for="txtLastName"><?php echo getBehavio($company->type,"app_cxc_customer","lblTxtApellidos","*Apellidos"); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtLastName" id="txtLastName" value="">
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtNombreLegal",""); ?> ">
                                    <label class="pv2-label" for="txtLegalName"><?php echo getBehavio($company->type,"app_cxc_customer","lblTxtFullName","*Nombre completo"); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtLegalName" id="txtLegalName" value="">
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtNombreComercial",""); ?> ">
                                    <label class="pv2-label" for="txtCommercialName"><?php echo getBehavio($company->type,"app_cxc_customer","divTxtFullNameCommercial","*Nombre comercial"); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtCommercialName" id="txtCommercialName" value="">
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divClassSex",""); ?>">
                                    <label class="pv2-label" for="txtSexoID">Sexo</label>
                                    <select name="txtSexoID" id="txtSexoID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListSexoID)
                                        foreach($objListSexoID as $ws){
                                            if($count == 0 )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtTypeIdentification",""); ?> ">
                                    <label class="pv2-label" for="txtIdentificationTypeID">Tipo Identificacion</label>
                                    <select name="txtIdentificationTypeID" id="txtIdentificationTypeID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListIdentificationType)
                                        foreach($objListIdentificationType as $ws){
                                            if($count == 0 )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtIdentification",""); ?> ">
                                    <label class="pv2-label" for="txtIdentification"><?php echo getBehavio($company->type,"app_cxc_customer","lblTxtIdentification","*Identificacion"); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtIdentification" id="txtIdentification" value="">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtPhoneNumber"><?php echo getBehavio($company->type,"app_cxc_customer","lblTxtPhoneTemp","Telefono"); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtPhoneNumber" id="txtPhoneNumber" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 <?php echo getBehavio($company->type,"app_cxc_customer","divRigthHome",""); ?>">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Clasificacion</p>

                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtEstado",""); ?> ">
                                    <label class="pv2-label" for="txtStatusID">Estado</label>
                                    <select name="txtStatusID" id="txtStatusID" class="select2">
                                        <option></option>
                                        <?php
                                        $indx = 0 ;
                                        if($objListWorkflowStage)
                                        foreach($objListWorkflowStage as $ws){

                                            if($indx == 0 )
                                                echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
                                            else
                                                echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";

                                            $indx++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtClasificacion",""); ?>  ">
                                    <label class="pv2-label" for="txtClasificationID"><?php echo getBehavio($company->type,"app_cxc_customer","Clasificacion",""); ?></label>
                                    <select name="txtClasificationID" id="txtClasificationID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListClasificationID)
                                        foreach($objListClasificationID as $ws){
                                            if($count == 0 )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtTipo",""); ?>  ">
                                    <label class="pv2-label" for="txtCustomerTypeID">Tipo</label>
                                    <select name="txtCustomerTypeID" id="txtCustomerTypeID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListCustomerTypeID)
                                        foreach($objListCustomerTypeID as $ws){
                                            if($count == 0 )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtCategoria",""); ?> " id="divTxtCategoryE" >
                                    <label class="pv2-label" for="txtCategoryID"><?php echo getBehavio($company->type,"app_cxc_customer","Categoria",""); ?></label>
                                    <select name="txtCategoryID" id="txtCategoryID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListCategoryID)
                                        foreach($objListCategoryID as $ws){
                                            if($count == 0 )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtSubCategoria",""); ?> ">
                                    <label class="pv2-label" for="txtSubCategoryID">Sub Categoria</label>
                                    <select name="txtSubCategoryID" id="txtSubCategoryID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListSubCategoryID)
                                        foreach($objListSubCategoryID as $ws){
                                            if($count == 0 )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtEstadoCivil",""); ?>  ">
                                    <label class="pv2-label" for="txtCivilStatusID">Estado Civil</label>
                                    <select name="txtCivilStatusID" id="txtCivilStatusID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListEstadoCivilID)
                                        foreach($objListEstadoCivilID as $ws){
                                            if($count == 0 )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtFormContact","hidden"); ?>  ">
                                    <label class="pv2-label" for="txtFormContactID">Forma de contacto</label>
                                    <select name="txtFormContactID" id="txtFormContactID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListFormContactID)
                                        foreach($objListFormContactID as $ws){
                                            if($count == 0 )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtProfesionUFicio",""); ?>  ">
                                    <label class="pv2-label" for="txtProfesionID">Profesion u Oficio</label>
                                    <select name="txtProfesionID" id="txtProfesionID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListProfesionID)
                                        foreach($objListProfesionID as $ws){
                                            if($count == 0 )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtAllowWhatsappPromotions">Permite WhatsApp Publicidad</label>
                                    <input type="checkbox" name="txtAllowWhatsappPromotions" id="txtAllowWhatsappPromotions" value="1">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtAllowWhatsappCollection">
                                        <?php echo getBahavioSession(
                                            $company->type,
                                            "app_cxc_customer",
                                            "labelPermiteCobroPorWhatapp",
                                            "Permite cobro por whatsapp",
                                            $objListCompanyPageSetting
                                        ); ?>
                                    </label>
                                    <input type="checkbox" name="txtAllowWhatsappCollection" id="txtAllowWhatsappCollection" value="1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="profile-cxc-line">
                    <div class="pv2-card">
                        <div class="pv2-detail-head">
                            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-coins"></i></span> Lineas de credito</p>
                            <div class="pv2-actions">
                                <a href="#" class="btn btn-default" id="btnNewLine"><i class="icon16 i-plus"></i> Agregar</a>
                                <a href="#" class="btn btn-danger" id="btnDeleteLine"><i class="icon16 i-remove"></i> Eliminar</a>
                            </div>
                        </div>
                        <span class="pv2-help">Marque la casilla de una fila y pulse Eliminar para quitarla.</span>
                        <div class="pv2-table-wrap">
                            <table id="tb_detail_credit_line" class="pv2-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>customerCreditLineID</th>
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
                                        <th>dayExcludedID</th>
                                    </tr>
                                </thead>
                                <tbody id="body_detail_line">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="profile-cxc">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-coins"></i></span> Condiciones de pago</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtCurrencyID">Moneda</label>
                                    <select name="txtCurrencyID" id="txtCurrencyID" class="select2">
                                        <option></option>
                                        <?php
                                        if($objListCurrency)
                                        foreach($objListCurrency as $ws){
                                            if($ws->currencyID == $objCurrency->currencyID )
                                            echo "<option value='".$ws->currencyID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->currencyID."'  >".$ws->name."</option>";
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
                                        if($objListTypePay)
                                        foreach($objListTypePay as $ws){
                                            if($count == 0 )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtPayConditionID">Condicion de Pago</label>
                                    <select name="txtPayConditionID" id="txtPayConditionID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListPayConditionID)
                                        foreach($objListPayConditionID as $ws){
                                            if($count == 0 )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-tags-2"></i></span> Credito y cuenta</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtIncomeDol">Ingresos US$</label>
                                    <input class="form-control pv2-num afx-monto" type="text" inputmode="decimal" autocomplete="off" name="txtIncomeDol" id="txtIncomeDol" value="5000.00">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtLimitCreditDol">Limite Credito US$</label>
                                    <input class="form-control pv2-num afx-monto" type="text" inputmode="decimal" autocomplete="off" name="txtLimitCreditDol" id="txtLimitCreditDol" value="900000.00">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtLimitBalanceDol">Balance US$</label>
                                    <input class="form-control pv2-num afx-monto" type="text" inputmode="decimal" autocomplete="off" name="txtLimitBalanceDol" id="txtLimitBalanceDol" value="">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtBalancePoint">Puntos</label>
                                    <input class="form-control pv2-num" type="text" readonly autocomplete="off" name="txtBalancePoint" id="txtBalancePoint" value="">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtAccountIDDescription">Cuenta</label>
                                    <div class="input-group afx-search">
                                        <input type="hidden" id="txtAccountID" name="txtAccountID">
                                        <input class="form-control" readonly id="txtAccountIDDescription" type="text" autocomplete="off">
                                        <span class="input-group-btn">
                                            <button class="btn btn-danger" type="button" id="btnClearAccount">
                                                <i aria-hidden="true" class="i-undo-2"></i>
                                                Limpiar
                                            </button>
                                        </span>
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary" type="button" id="btnSearchAccount">
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

                <div class="tab-pane fade" id="profile">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-home"></i></span> Ubicacion y balance</p>

                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtPais",""); ?> ">
                                    <label class="pv2-label" for="txtCountryID">Pais</label>
                                    <select name="txtCountryID" id="txtCountryID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListCountry)
                                        foreach($objListCountry as $ws){
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtDepartamento",""); ?> ">
                                    <label class="pv2-label" for="txtStateID">Departamento</label>
                                    <select name="txtStateID" id="txtStateID" class="select2">
                                        <option></option>
                                    </select>
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtMunicipio",""); ?> ">
                                    <label class="pv2-label" for="txtCityID">Municipio</label>
                                    <select name="txtCityID" id="txtCityID" class="select2">
                                        <option></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtLocation"><?php echo getBehavio($company->type,"app_cxc_customer","txtDomicilio","Domicilio"); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtLocation" id="txtLocation" value="">
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtPresupuesto",""); ?>">
                                    <label class="pv2-label" for="txtBudget">Presupuesto U$</label>
                                    <input class="form-control" type="number" autocomplete="off" name="txtBudget" id="txtBudget" value="">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtBalanceCor">Balance Cordobas</label>
                                    <input class="form-control pv2-num afx-monto" readonly type="text" autocomplete="off" name="txtBalanceCor" id="txtBalanceCor" value="0">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtBalanceDol">Balance Dolares</label>
                                    <input class="form-control pv2-num afx-monto" readonly type="text" autocomplete="off" name="txtBalanceDol" id="txtBalanceDol" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-tags-2"></i></span> Referencias</p>

                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtTypeFirmID",""); ?> ">
                                    <label class="pv2-label" for="txtTypeFirmID">Tipo de Firma</label>
                                    <select name="txtTypeFirmID" id="txtTypeFirmID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListTypeFirmID)
                                        foreach($objListTypeFirmID as $ws){
                                            if($count == 0 )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtEmployerDescription"><?php echo getBehavio($company->type,"app_cxc_customer","labelAgente","Agente"); ?></label>
                                    <div class="input-group afx-search">
                                        <input type="hidden" id="txtEmployerID" name="txtEmployerID" value="">
                                        <input class="form-control" readonly id="txtEmployerDescription" type="text" autocomplete="off" value="">
                                        <span class="input-group-btn">
                                            <button class="btn btn-danger" type="button" id="btnClearEmployer">
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
                                <div class="form-group">
                                    <label class="pv2-label" for="txtCustomerIDReferenceDescription"><?php echo getBehavio($company->type,"app_cxc_customer","labelClienteReferencia","Cliente Referente"); ?></label>
                                    <div class="input-group afx-search">
                                        <input type="hidden" id="txtCustomerIDReference" name="txtCustomerIDReference" value="">
                                        <input class="form-control" readonly id="txtCustomerIDReferenceDescription" type="text" autocomplete="off" value="">
                                        <span class="input-group-btn">
                                            <button class="btn btn-danger" type="button" id="btnClearCustomerReferente">
                                                <i aria-hidden="true" class="i-undo-2"></i>
                                                Limpiar
                                            </button>
                                        </span>
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary" type="button" id="btnSearchClienteReferente">
                                                <i aria-hidden="true" class="i-search-5"></i>
                                                Buscar
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","classDivReference1",""); ?> ">
                                    <label class="pv2-label" for="txtReference1"><?php echo getBehavio($company->type,"app_cxc_customer","Referencia1",""); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference1" id="txtReference1" value="">
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","classDivReference2",""); ?> ">
                                    <label class="pv2-label" for="txtReference2"><?php echo getBehavio($company->type,"app_cxc_customer","Referencia2",""); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference2" id="txtReference2" value="">
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","classDivReference3",""); ?> ">
                                    <label class="pv2-label" for="txtReference3"><?php echo getBehavio($company->type,"app_cxc_customer","Referencia3",""); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference3" id="txtReference3" value="">
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","classDivReference4",""); ?> ">
                                    <label class="pv2-label" for="txtReference4"><?php echo getBehavio($company->type,"app_cxc_customer","Referencia4",""); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference4" id="txtReference4" value="">
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","classDivReference5",""); ?> ">
                                    <label class="pv2-label" for="txtReference5"><?php echo getBehavio($company->type,"app_cxc_customer","Referencia5",""); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference5" id="txtReference5" value="">
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

                <div class="tab-pane fade" id="dropdown">
                    <div class="pv2-card">
                        <div class="form-group">
                            <label class="pv2-label" for="txtAddress">Direccion</label>
                            <textarea class="form-control" id="txtAddress" name="txtAddress" rows="6"></textarea>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="dropdown-file">
                </div>

                <div class="tab-pane fade" id="dropdown-formapago">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Titular</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtNombreTarjeta">Nombre tarjeta</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtNombreTarjeta" id="txtNombreTarjeta" value="">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtEmailTarjeta">Correo:</label>
                                    <input class="form-control" type="email" autocomplete="off" name="txtEmailTarjeta" id="txtEmailTarjeta" value="">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtNumeroTarjeta">Número tarjeta</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtNumeroTarjeta" id="txtNumeroTarjeta" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Tarjeta</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtVencimientoTarjeta">Vencimiento</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtVencimientoTarjeta" id="txtVencimientoTarjeta" value="">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtCodigoCvc">Código CVC</label>
                                    <input class="form-control" type="number" autocomplete="off" name="txtCodigoCvc" id="txtCodigoCvc" value="">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtTipoTarjeta">Tipo Tarjeta:</label>
                                    <select name="txtTipoTarjeta" id="txtTipoTarjeta" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListTypeID){
                                            foreach($objListTypeID as $ws){
                                                if($count == 0 )
                                                    echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                                else
                                                    echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                                $count++;
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="dropdown-recordatorio">
                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Recordatorios</p>
                        <div class="pv2-table-wrap">
                            <table class="pv2-table" id="tableFrecuency">
                                <thead>
                                    <tr>
                                        <th>Descripcion</th>
                                        <th>Estado</th>
                                        <th>Frecuencia</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="row_razon" id="filaEntrada">
                                        <td class="afx-col-block" data-label="Descripcion">
                                            <input class="form-control" type="text" autocomplete="off" name="txtNombreRecordatorio" id="txtNombreRecordatorio" value="">
                                            <label id="errorLabel" class="text-danger">Este campo no puede estar vacío</label>
                                        </td>
                                        <td class="afx-col-block" data-label="Estado">
                                            <label class="sr-only" for="txtSituationID">Situacion:</label>
                                            <select name="txtSituationID" id="txtSituationID" class="select2">
                                                <option></option>
                                                <?php
                                                $count = 0;
                                                if($objListSituationID){
                                                    foreach($objListSituationID as $ws){
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
                                        <td class="afx-col-block" data-label="Frecuencia">
                                            <label class="sr-only" for="txtFrecuencyContactID">Frecuencia:</label>
                                            <select name="txtFrecuencyContactID" id="txtFrecuencyContactID" class="select2">
                                                <option></option>
                                                <?php
                                                $count = 0;
                                                if($objListFrecuencyContactID){
                                                    foreach($objListFrecuencyContactID as $ws){
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
                                        <td data-label="Acción">
                                            <a href="#" class="btn btn-default" id="btnAddFrecuency" title="Agregar"><i class="fas fa-plus"></i></a>
                                        </td>
                                    </tr>
                                    <!-- Aquí se agregarán las filas dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- /formulario -->

    <!-- barra lateral de Leads -->
    <div id="mySidebar" class="sidebar" style="background-color:white">
        <div class="sidebar-content">
            <p class="pv2-card-title">Leads</p>

            <div class="form-group">
                <label class="pv2-label" for="txtLeadTipo">Tipo:</label>
                <select class="select2" id="txtLeadTipo" name="txtLeadTipo">
                    <?php
                    if($objPCItemTypeLeads)
                    {
                        $countxTlead = 0;
                        foreach($objPCItemTypeLeads as $itemLead)
                        {
                            if($countxTlead == 0)
                                echo '<option value="'.$itemLead->publicCatalogDetailID.'" selected >'.$itemLead->name.'</option>';
                            else
                                echo '<option value="'.$itemLead->publicCatalogDetailID.'" >'.$itemLead->name.'</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group" id="divTxtLeadsSubTipo">
                <label class="pv2-label" id="lblLeadSubTipoLeads" for="txtLeadSubTipo">Leads:</label>
                <select class="select2" id="txtLeadSubTipo" name="txtLeadSubTipo">
                    <?php
                    if($objPCItemSubTypeLeads)
                    {
                        $countxTlead = 0;
                        foreach($objPCItemSubTypeLeads as $itemLead)
                        {
                            if($countxTlead == 0)
                                echo '<option value="'.$itemLead->publicCatalogDetailID.'" selected >'.$itemLead->name.'</option>';
                            else
                                echo '<option value="'.$itemLead->publicCatalogDetailID.'" >'.$itemLead->name.'</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtLeadCategory">Categoria:</label>
                <select class="select2" id="txtLeadCategory" name="txtLeadCategory">
                    <?php
                    if($objPCItemCategoryLeads)
                    {
                        $countxTlead = 0;
                        foreach($objPCItemCategoryLeads as $itemLead)
                        {
                            if($countxTlead == 0)
                                echo '<option value="'.$itemLead->publicCatalogDetailID.'" selected >'.$itemLead->name.'</option>';
                            else
                                echo '<option value="'.$itemLead->publicCatalogDetailID.'" >'.$itemLead->name.'</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtLeadComentario">Comentario:</label>
                <textarea class="form-control" id="txtLeadComentario" rows="3" name="txtLeadComentario"></textarea>
            </div>

            <div class="pv2-actions">
                <button type="button" id="saveLeads" class="btn btn-primary">Salvar</button>
                <button type="button" id="cerrarLeads" class="btn btn-danger">Cerrar</button>
            </div>
        </div>
    </div>
    <!-- /barra lateral de Leads -->

    <!-- barra inferior (telefono) -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label">Nuevo cliente</p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>

<?php echo getBehavio($company->type,"app_cxc_customer","divScriptCustom",""); ?>
