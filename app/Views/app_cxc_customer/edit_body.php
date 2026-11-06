<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-address-book"></i></div>
            <div>
                <p class="pv2-header-title">CODIGO:#<span class="invoice-num"><?php echo $objCustomer->customerNumber; ?></span></p>
                <p class="pv2-header-sub">Revisa o actualiza los datos del cliente</p>
            </div>
        </div>
        <div class="pv2-actions">
            <?php
                if($callback == "false")
                {
                    ?>
                    <div class="btn-group <?php echo getBehavio($company->type,"app_cxc_customer","showBtnGroupAcciones","hidden"); ?> ">
                        <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">Acciones <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo base_url(); ?>/app_cxc_record/index?identificacion=<?php echo $objCustomer->identification; ?>"  class=" <?php echo getBehavio($company->type,"app_cxc_customer","showBtnIrBuro","hidden"); ?>" id="btnStupBuro"><i class="icon16 i-checkmark-4"></i> 001) Ir a Buro</a></li>
                            <li><a href="<?php echo base_url(); ?>/app_cxc_simulation/index?entityID=<?php echo $objCustomer->entityID; ?>"  class=" <?php echo getBehavio($company->type,"app_cxc_customer","showBtnIrSimulador","hidden"); ?>" id="btnStupSimulator"><i class="icon16 i-checkmark-4"></i> 002) Calculadora</a></li>
                            <li><a href="<?php echo base_url(); ?>/app_invoice_billing/add/codigoMesero/none" target="_blank"  class=" <?php echo getBehavio($company->type,"app_cxc_customer","showBtnIrInvoice","hidden"); ?>" id="btnStupInvoice"><i class="icon16 i-checkmark-4"></i> 003) Prestamo</a></li>
                            <li><a href="<?php echo base_url(); ?>/app_box_share/add?entityID=<?php echo $objCustomer->entityID; ?>" target="_blank"  class=" <?php echo getBehavio($company->type,"app_cxc_customer","showBtnIrShare","hidden"); ?>" id="btnStupShare"><i class="icon16 i-checkmark-4"></i> 004) Abono</a></li>
                        </ul>
                    </div>

                    <a href="<?php echo base_url(); ?>/app_cxc_customer/viewPrinterCarnet/companyID/2/branchID/2/entityID/<?php echo $objCustomer->entityID; ?>" target="_blank" class="btn btn-default" id="btnCarnet"><i class="icon16 i-pencil"></i> Carnet</a>
                    <a href="#" class="btn btn-default <?php echo getBehavio($company->type,"app_cxc_customer","showBtnViewLeads",""); ?> " id="btnLeads"><i class="icon16 i-pencil"></i> Leads</a>
                    <a href="<?php echo base_url(); ?>/app_cxc_customer/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
                    <a href="#" class="btn btn-danger <?php echo getBehavio($company->type,"app_cxc_customer","showBtnDelete","hidden"); ?> " id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
                    <a href="#" class="btn btn-default <?php echo getBehavio($company->type,"app_cxc_customer","showBtnPrinter","hidden"); ?> " id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
                    <?php
                }
            ?>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
    <form id="form-new-cxc-customer" name="form-new-cxc-customer" role="form" enctype="multipart/form-data" autocomplete="off">
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
                        <li class="<?php echo getBehavio($company->type,"app_cxc_customer","divPestanaArchivos",""); ?>" ><a id="btnClickArchivo" href="#dropdown-file" data-toggle="tab">Archivos</a></li>
                        <li><a href="#dropdown-formapago" data-toggle="tab">Forma de Pago</a></li>
                        <li><a href="#dropdown-recordatorio" data-toggle="tab">Recordatorios</a></li>
                    </ul>
                </li>
                <li class="<?php echo getBehavio($company->type,"app_cxc_customer","divPestanaTrakingLead",""); ?>" ><a href="#traking-lead" data-toggle="tab">Traking Lead.</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade in active" id="home">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <input type="hidden" name="txtCompanyID" value="<?php echo $objCustomer->companyID; ?>">
                                <input type="hidden" name="txtBranchID" value="<?php echo $objCustomer->branchID; ?>">
                                <input type="hidden" name="txtEntityID" value="<?php echo $objCustomer->entityID; ?>" id="txtEntityID" >
                                <input type="hidden" name="txtEntityNumberID" value="<?php echo $objCustomer->customerNumber; ?>" id="txtEntityNumberID" >
                                <input type="hidden" name="txtCallback" value="<?php echo $callback; ?>"/>

                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Datos generales</p>

                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtFechaNacimiento",""); ?> ">
                                    <label class="pv2-label" for="txtBirthDate">Nacimiento</label>
                                    <div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
                                        <input class="form-control" type="text" autocomplete="off" name="txtBirthDate" id="txtBirthDate" value="<?php echo $objCustomer->birthDate; ?>">
                                        <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtFechaContacto","hidden"); ?> ">
                                    <label class="pv2-label" for="txtDateContract">Fecha de Contacto</label>
                                    <div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
                                        <input class="form-control" type="text" autocomplete="off" name="txtDateContract" id="txtDateContract" value="<?php echo $objCustomer->dateContract; ?>">
                                        <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                    </div>
                                </div>

                                <div class="form-group  <?php echo getBehavio($company->type,"app_cxc_customer","divTxtNombres",""); ?>  ">
                                    <label class="pv2-label" for="txtFirstName"><?php echo getBehavio($company->type,"app_cxc_customer","lblTxtNombre","*Nombres"); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtFirstName" id="txtFirstName" value="<?php echo $objNatural->firstName; ?>">
                                </div>
                                <div class="form-group  <?php echo getBehavio($company->type,"app_cxc_customer","divTxtApellidos",""); ?>  ">
                                    <label class="pv2-label" for="txtLastName"><?php echo getBehavio($company->type,"app_cxc_customer","lblTxtApellidos","*Apellidos"); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtLastName" id="txtLastName" value="<?php echo $objNatural->lastName; ?>">
                                </div>
                                <div class="form-group" <?php echo getBehavio($company->type,"app_cxc_customer","divTxtNombreLegal",""); ?> >
                                    <label class="pv2-label" for="txtLegalName"><?php echo getBehavio($company->type,"app_cxc_customer","lblTxtFullName","*Nombre completo"); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtLegalName" id="txtLegalName" value="<?php echo $objLegal->legalName; ?>">
                                </div>
                                <div class="form-group  <?php echo getBehavio($company->type,"app_cxc_customer","divTxtNombreComercial",""); ?> ">
                                    <label class="pv2-label" for="txtCommercialName"><?php echo getBehavio($company->type,"app_cxc_customer","divTxtFullNameCommercial","*Nombre comercial"); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtCommercialName" id="txtCommercialName" value="<?php echo $objLegal->comercialName; ?>">
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divClassSex",""); ?> ">
                                    <label class="pv2-label" for="txtSexoID">Sexo</label>
                                    <select name="txtSexoID" id="txtSexoID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListSexoID)
                                        foreach($objListSexoID as $ws){
                                            if( $ws->catalogItemID == $objCustomer->sexoID )
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
                                            if( $ws->catalogItemID == $objCustomer->identificationType )
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
                                    <input class="form-control" type="text" autocomplete="off" name="txtIdentification" id="txtIdentification" value="<?php echo $objCustomer->identification; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtPhoneNumber"><?php echo getBehavio($company->type,"app_cxc_customer","lblTxtPhoneTemp","Telefono"); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtPhoneNumber" id="txtPhoneNumber" value="<?php echo $objCustomer->phoneNumber; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 <?php echo getBehavio($company->type,"app_cxc_customer","divRigthHome",""); ?> ">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Clasificacion</p>

                                <div class="form-group  <?php echo getBehavio($company->type,"app_cxc_customer","divTxtEstado",""); ?> ">
                                    <label class="pv2-label" for="txtStatusID">Estado</label>
                                    <select name="txtStatusID" id="txtStatusID" class="select2">
                                        <option></option>
                                        <?php
                                        if($objListWorkflowStage)
                                        foreach($objListWorkflowStage as $ws){
                                            if ($ws->workflowStageID == $objCustomer->statusID)
                                            echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group  <?php echo getBehavio($company->type,"app_cxc_customer","divTxtClasificacion",""); ?> ">
                                    <label class="pv2-label" for="txtClasificationID"><?php echo getBehavio($company->type,"app_cxc_customer","Clasificacion",""); ?></label>
                                    <select name="txtClasificationID" id="txtClasificationID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListClasificationID)
                                        foreach($objListClasificationID as $ws){
                                            if($ws->catalogItemID == $objCustomer->clasificationID )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group   <?php echo getBehavio($company->type,"app_cxc_customer","divTxtTipo",""); ?>  ">
                                    <label class="pv2-label" for="txtCustomerTypeID">Tipo</label>
                                    <select name="txtCustomerTypeID" id="txtCustomerTypeID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListCustomerTypeID)
                                        foreach($objListCustomerTypeID as $ws){
                                            if($ws->catalogItemID == $objCustomer->customerTypeID )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group   <?php echo getBehavio($company->type,"app_cxc_customer","divTxtCategoria",""); ?> " id="divTxtCategoryE" >
                                    <label class="pv2-label" for="txtCategoryID"><?php echo getBehavio($company->type,"app_cxc_customer","Categoria",""); ?></label>
                                    <select name="txtCategoryID" id="txtCategoryID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListCategoryID)
                                        foreach($objListCategoryID as $ws){
                                            if($ws->catalogItemID == $objCustomer->categoryID )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group  <?php echo getBehavio($company->type,"app_cxc_customer","divTxtSubCategoria",""); ?> ">
                                    <label class="pv2-label" for="txtSubCategoryID">Sub Categoria</label>
                                    <select name="txtSubCategoryID" id="txtSubCategoryID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListSubCategoryID)
                                        foreach($objListSubCategoryID as $ws){
                                            if($ws->catalogItemID == $objCustomer->subCategoryID )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group   <?php echo getBehavio($company->type,"app_cxc_customer","divTxtEstadoCivil",""); ?> ">
                                    <label class="pv2-label" for="txtCivilStatusID">Estado Civil</label>
                                    <select name="txtCivilStatusID" id="txtCivilStatusID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListEstadoCivilID)
                                        foreach($objListEstadoCivilID as $ws){
                                            if( $ws->catalogItemID == $objNatural->statusID )
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
                                            if( $ws->catalogItemID == $objCustomer->formContactID )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtProfesionUFicio",""); ?> ">
                                    <label class="pv2-label" for="txtProfesionID">Profesion u Oficio</label>
                                    <select name="txtProfesionID" id="txtProfesionID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListProfesionID)
                                        foreach($objListProfesionID as $ws){
                                            if( $ws->catalogItemID == $objNatural->profesionID )
                                            echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtBuro",""); ?>">
                                    <label class="pv2-label">Ver Buro</label>
                                    <div class="btn-group">
                                        <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">Lectura <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <?php
                                            if($objCustomerSinRiesgo)
                                            foreach($objCustomerSinRiesgo as $ws){
                                                echo '<li><a href="'.base_url().'app_cxc_record/index?file_exists='.$ws->file.'"  target="_blank"  >'.$ws->file.'</a></li>';
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtHuella",""); ?>">
                                    <label class="pv2-label">Huella</label>
                                    <div class="btn-group">
                                        <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">Lectura <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" id="btnScanerHuella">Abrir Sensor</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtAllowWhatsappPromotions">Permite WhatsApp Publicidad</label>
                                    <input type="checkbox" name="txtAllowWhatsappPromotions" id="txtAllowWhatsappPromotions" value="1" <?php echo ($objCustomer->allowWhatsappPromotions == 1) ? "checked":""; ?>  >
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
                                    <input type="checkbox" name="txtAllowWhatsappCollection" id="txtAllowWhatsappCollection" value="1" <?php echo ($objCustomer->allowWhatsappCollection == 1) ? "checked":""; ?>  >
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
                                        <th>customerCreditLineID</th>
                                        <th>creditLineID</th>
                                        <th>currencyID</th>
                                        <th>statusID</th>
                                        <th>InteresYear</th>
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
                                            if($ws->currencyID == $objCustomer->currencyID )
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
                                            if($ws->catalogItemID == $objCustomer->typePay )
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
                                            if($ws->catalogItemID == $objCustomer->payConditionID )
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
                                    <input class="form-control pv2-num afx-monto" type="text" inputmode="decimal" autocomplete="off" name="txtIncomeDol" id="txtIncomeDol" value="<?php echo sprintf("%01.4f", $objCustomerCredit->incomeDol); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtLimitCreditDol">Limite Credito US$</label>
                                    <input class="form-control pv2-num afx-monto" type="text" inputmode="decimal" autocomplete="off" name="txtLimitCreditDol" id="txtLimitCreditDol" value="<?php echo sprintf("%01.4f", $objCustomerCredit->limitCreditDol); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtLimitBalanceDol">Balance US$</label>
                                    <input class="form-control pv2-num afx-monto" type="text" inputmode="decimal" autocomplete="off" name="txtLimitBalanceDol" id="txtLimitBalanceDol" value="<?php echo sprintf("%01.4f", $objCustomerCredit->balanceDol); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtBalancePoint">Puntos</label>
                                    <input class="form-control pv2-num" type="text" readonly autocomplete="off" name="txtBalancePoint" id="txtBalancePoint" value="<?php echo $objCustomer->balancePoint; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtAccountIDDescription">Cuenta</label>
                                    <div class="input-group afx-search">
                                        <input type="hidden" id="txtAccountID" name="txtAccountID" value="<?php if($objAccount) echo $objAccount->accountID; ?>">
                                        <input class="form-control" readonly id="txtAccountIDDescription" type="text" autocomplete="off" value="<?php  if($objAccount) echo $objAccount->accountNumber." ".$objAccount->name ; ?>">
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
                            <div class="pv2-card is-required">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-home"></i></span> Ubicacion y balance</p>

                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtPais",""); ?>">
                                    <label class="pv2-label" for="txtCountryID">Pais <span class="pv2-req">*</span></label>
                                    <select name="txtCountryID" id="txtCountryID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListCountry)
                                        foreach($objListCountry as $ws){
                                            if( $ws->catalogItemID == $objCustomer->countryID)
                                            echo "<option value='".$ws->catalogItemID."' selected  >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtDepartamento",""); ?>">
                                    <label class="pv2-label" for="txtStateID">Departamento <span class="pv2-req">*</span></label>
                                    <select name="txtStateID" id="txtStateID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListState)
                                        foreach($objListState as $ws){
                                            if( $ws->catalogItemID == $objCustomer->stateID)
                                            echo "<option value='".$ws->catalogItemID."' selected  >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtMunicipio",""); ?>">
                                    <label class="pv2-label" for="txtCityID">Municipio <span class="pv2-req">*</span></label>
                                    <select name="txtCityID" id="txtCityID" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if($objListCity)
                                        foreach($objListCity as $ws){
                                            if( $ws->catalogItemID == $objCustomer->cityID)
                                            echo "<option value='".$ws->catalogItemID."' selected  >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtLocation"><?php echo getBehavio($company->type,"app_cxc_customer","txtDomicilio","Domicilio"); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtLocation" id="txtLocation" value="<?php echo $objCustomer->location; ?>">
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtPresupuesto",""); ?>">
                                    <label class="pv2-label" for="txtBudget">Presupuesto U$</label>
                                    <input class="form-control" type="number" autocomplete="off" name="txtBudget" id="txtBudget" value="<?php echo $objCustomer->budget; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtBalanceCor">Balance Cordobas</label>
                                    <input class="form-control pv2-num afx-monto" readonly type="text" autocomplete="off" name="txtBalanceCor" id="txtBalanceCor" value="<?php echo sprintf("%01.4f", $objCustomer->balanceCor); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtBalanceDol">Balance Dolares</label>
                                    <input class="form-control pv2-num afx-monto" readonly type="text" autocomplete="off" name="txtBalanceDol" id="txtBalanceDol" value="<?php echo sprintf("%01.4f", $objCustomer->balanceDol); ?>">
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
                                            if( $ws->catalogItemID == $objCustomer->typeFirm)
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
                                        <input type="hidden" id="txtEmployerID" name="txtEmployerID" value="<?php echo $objEmployerNatural != NULL ?  $objEmployer->entityID : "";  ?>">
                                        <input class="form-control" readonly id="txtEmployerDescription" type="text" autocomplete="off" value="<?php echo $objEmployerNatural != null ? strtoupper($objEmployer->employeNumber . " ". $objEmployerNatural->firstName . " ". $objEmployerNatural->lastName ) : ""; ?>">
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
                                        <input type="hidden" id="txtCustomerIDReference" name="txtCustomerIDReference" value="<?php echo $objCustomerReferente != NULL ?  $objCustomerReferente->entityID : "";  ?>">
                                        <input class="form-control" readonly id="txtCustomerIDReferenceDescription" type="text" autocomplete="off" value="<?php echo $objCustomerReferente != null ? strtoupper($objCustomerReferente->customerNumber . " ". $objCustomerReferenteNatural->firstName . " ". $objCustomerReferenteNatural->lastName ) : ""; ?>">
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
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","classDivReference1",""); ?>">
                                    <label class="pv2-label" for="txtReference1"><?php echo getBehavio($company->type,"app_cxc_customer","Referencia1",""); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference1" id="txtReference1" value="<?php echo $objCustomer->reference1; ?>">
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","classDivReference2",""); ?>">
                                    <label class="pv2-label" for="txtReference2"><?php echo getBehavio($company->type,"app_cxc_customer","Referencia2",""); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference2" id="txtReference2" value="<?php echo $objCustomer->reference2; ?>">
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","classDivReference3",""); ?> ">
                                    <label class="pv2-label" for="txtReference3"><?php echo getBehavio($company->type,"app_cxc_customer","Referencia3",""); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference3" id="txtReference3" value="<?php echo $objCustomer->reference3; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtPhoto">Buscar Foto:</label>
                                    <input type="file" id="txtPhoto" name="txtPhoto" accept="image/*">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label">Preview:</label>
                                    <!-- Vista previa -->
                                    <div class="preview-container">
                                        <img id="panelPreview" class="preview-img" alt="Vista previa" src="<?php echo $objCustomer->reference6; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","classDivReference4",""); ?> ">
                                    <label class="pv2-label" for="txtReference4"><?php echo getBehavio($company->type,"app_cxc_customer","Referencia4",""); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference4" id="txtReference4" value="<?php echo $objCustomer->reference4; ?>">
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","classDivReference5",""); ?> ">
                                    <label class="pv2-label" for="txtReference5"><?php echo getBehavio($company->type,"app_cxc_customer","Referencia5",""); ?></label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference5" id="txtReference5" value="<?php echo $objCustomer->reference5; ?>">
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

                <div class="tab-pane fade" id="traking-lead">
                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Traking Lead</p>
                        <?php if(!empty($objListTrackingLead)): ?>
                        <div class="pv2-table-wrap">
                            <table id="tb_traking_lead" class="pv2-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>No.</th>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Sub Tipo</th>
                                        <th>Categoría</th>
                                        <th>Comentario</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 1;
                                foreach($objListTrackingLead as $lead):
                                ?>
                                    <tr class="row_razon">
                                        <td data-label="#"><?php echo $i++; ?></td>
                                        <td data-label="No."><span class="badge"><?php echo htmlspecialchars($lead->transactionNumber); ?></span></td>
                                        <td data-label="Fecha"><?php echo htmlspecialchars($lead->transactionOn); ?></td>
                                        <td data-label="Tipo"><?php echo htmlspecialchars($lead->tipoName ?? '-'); ?></td>
                                        <td data-label="Sub Tipo"><?php echo htmlspecialchars($lead->subTipoName ?? '-'); ?></td>
                                        <td data-label="Categoría"><?php echo htmlspecialchars($lead->reference1 ?? '-'); ?></td>
                                        <td data-label="Comentario"><?php echo htmlspecialchars($lead->note ?? '-'); ?></td>
                                        <td data-label="Estado"><span class="label label-info"><?php echo htmlspecialchars($lead->statusName); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="pv2-empty">
                            <i class="icon16 i-info"></i> No hay registros de tracking de leads para este cliente.
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="tab-pane fade" id="dropdown">
                    <div class="pv2-card">
                        <div class="form-group">
                            <label class="pv2-label" for="txtAddress">Direccion</label>
                            <textarea class="form-control" id="txtAddress" name="txtAddress" rows="6"><?php echo $objCustomer->address; ?></textarea>
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
                                    <input class="form-control" type="text" autocomplete="off" name="txtNombreTarjeta" id="txtNombreTarjeta" value="<?= is_null($objPaymentMethod) ? "" : $objPaymentMethod->name ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtEmailTarjeta">Correo:</label>
                                    <input class="form-control" type="email" autocomplete="off" name="txtEmailTarjeta" id="txtEmailTarjeta" value="<?= is_null($objPaymentMethod) ? "" : $objPaymentMethod->email ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtNumeroTarjeta">Número tarjeta</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtNumeroTarjeta" id="txtNumeroTarjeta" value="<?= is_null($objPaymentMethod) ? "" : $objPaymentMethod->number ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Tarjeta</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtVencimientoTarjeta">Vencimiento</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtVencimientoTarjeta" id="txtVencimientoTarjeta" value="<?= is_null($objPaymentMethod) ? "" : $objPaymentMethod->expirationDate ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtCodigoCvc">Código CVC</label>
                                    <input class="form-control" type="number" autocomplete="off" name="txtCodigoCvc" id="txtCodigoCvc" value="<?= is_null($objPaymentMethod) ? "" : $objPaymentMethod->cvc ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtTipoTarjeta">Tipo Tarjeta:</label>
                                    <select name="txtTipoTarjeta" id="txtTipoTarjeta" class="select2">
                                        <option></option>
                                        <?php
                                        $count = 0;
                                        if(isset($objListTypeID)){
                                            foreach($objListTypeID as $ws){
                                                if(!isset($objPaymentMethod)){
                                                    if($count == 0 )
                                                        echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                                    else
                                                        echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                                }else{
                                                    if( $ws->catalogItemID == $objPaymentMethod->typeId )
                                                        echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                                    else
                                                        echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                                }
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

                                    <!-- Filas dinámicas obtenidas de la base de datos -->
                                    <?php
                                    if($objCustomerFrecuency){
                                        foreach($objCustomerFrecuency as $value):
                                        ?>
                                        <tr class="row_razon">
                                            <td class="afx-col-block" data-label="Descripcion">
                                                <input type="hidden" name="customerFrecuencyActuations[]" value="<?= $value->customerFrecuencyActuations?>" />
                                                <input class="form-control" type="text" autocomplete="off" name="txtNombreRecordatorioArray[]" value="<?= $value->name ?>">
                                            </td>
                                            <td class="afx-col-block" data-label="Estado">
                                                <select name="txtSituationIDArray[]" id="txtSituationIDArray" class="select2">
                                                    <?php
                                                        if($objListSituationID){
                                                            foreach($objListSituationID as $ws){
                                                    ?>
                                                            <option value='<?=$ws->catalogItemID?>' <?= $ws->catalogItemID== $value->situationID ? 'selected' : '' ?> ><?=$ws->name?></option>
                                                    <?php
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </td>
                                            <td class="afx-col-block" data-label="Frecuencia">
                                                <select name="txtFrecuencyContactIDArray[]" id="txtFrecuencyContactIDArray" class="select2">
                                                    <?php
                                                        if($objListFrecuencyContactID){
                                                            foreach($objListFrecuencyContactID as $ws){
                                                    ?>
                                                            <option value='<?=$ws->catalogItemID?>' <?= $ws->catalogItemID== $value->frecuencyContactID ? 'selected' : '' ?> ><?=$ws->name?></option>
                                                    <?php
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </td>
                                            <td data-label="Acción">
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
            </div>
        </div>
    </form>
    <!-- /formulario -->

    <!-- ui dialog -->
    <div id="windowDialogConstante" title="Amort Constante" class="dialog">
        <p>
            //*******************************<br/>
            //193 Amortizacion Constante<br/>
        </p>
        <p style="text-align:justify">
            Cuota Decreciente<br/>
            Capital Constante<br/>
            Interes Decreciente
        </p>
    </div>
    <div id="windowDialogAleman" title="Amort Aleman" class="dialog">
        <p>
            //*******************************<br/>
            //195 Amortizacion Aleman<br/>
        </p>
        <p style="text-align:justify">
            Primera Cuota solo el Interes<br/>
            Cuota Constante<br/>
            Capital Creciente<br/>
            Interes Decreciente
        </p>
    </div>
    <div id="windowDialogFrancesa" title="Amort Francesa" class="dialog">
        <p>
            //*******************************<br/>
            //194 Amortizacion Frances<br/>
        </p>
        <p style="text-align:justify">
            Cuota Constante<br/>
            Capital Creciente<br/>
            Interes Decreciente
        </p>
    </div>
    <div id="windowDialogAmericano" title="Amort Americano" class="dialog">
        <p>
            //*******************************<br/>
            //196 Amortizacion Americano<br/>
        </p>
        <p style="text-align:justify">
            Ultima Cuota solo el Interes y Capital<br/>
            Cuota Constante<br/>
            Capital 0<br/>
            Interes Constante
        </p>
    </div>
    <!-- /ui dialog -->

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
            <p class="pv2-mbar-label">Cliente #<?php echo $objCustomer->customerNumber; ?></p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>

<?php echo getBehavio($company->type,"app_cxc_customer","divScriptCustom",""); ?>
