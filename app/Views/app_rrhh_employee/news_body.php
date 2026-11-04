<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-vcard"></i></div>
            <div>
                <p class="pv2-header-title">CODIGO:#<span class="invoice-num">00000000</span> <span class="pv2-chip">Nuevo</span></p>
                <p class="pv2-header-sub">Completa los datos del colaborador</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_rrhh_employee/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
    <form id="form-new-rrhh-employee" name="form-new-rrhh-employee" role="form" autocomplete="off">
        <div class="printArea">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="pv2-card" id="home">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Datos personales</p>

                        <div class="form-group">
                            <label class="pv2-label" for="txtFirstName">Primer Nombre <span class="pv2-req">*</span></label>
                            <input class="form-control" type="text" autocomplete="off" name="txtFirstName" id="txtFirstName" value="">
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtLastName">Apellidos</label>
                            <input class="form-control" type="text" autocomplete="off" name="txtLastName" id="txtLastName" value="">
                        </div>
                        <div class="form-group">
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
                        <div class="form-group">
                            <label class="pv2-label" for="txtIdentification">Identificacion <span class="pv2-req">*</span></label>
                            <input class="form-control" type="text" autocomplete="off" name="txtIdentification" id="txtIdentification" value="">
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtSocialSecurityNumber">INSS</label>
                            <input class="form-control" type="text" autocomplete="off" name="txtSocialSecurityNumber" id="txtSocialSecurityNumber" value="">
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-6">
                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Su trabajo</p>

                        <div class="form-group">
                            <label class="pv2-label" for="txtStatusID">Estado</label>
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
                        <div class="form-group">
                            <label class="pv2-label" for="txtClasificationID">Clasificacion</label>
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
                        <div class="form-group">
                            <label class="pv2-label" for="txtCategoryID">Contrato</label>
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
                        <div class="form-group">
                            <label class="pv2-label" for="txtTypeEmployeeID">Grado</label>
                            <select name="txtTypeEmployeeID" id="txtTypeEmployeeID" class="select2">
                                <option></option>
                                <?php
                                $count = 0;
                                if($objListTypeEmployeeID)
                                foreach($objListTypeEmployeeID as $ws){
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
                            <label class="pv2-label" for="txtDepartamentID">Departamento de la empresa</label>
                            <select name="txtDepartamentID" id="txtDepartamentID" class="select2">
                                <option></option>
                                <?php
                                $count = 0;
                                if($objListDepartamentID)
                                foreach($objListDepartamentID as $ws){
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
                            <label class="pv2-label" for="txtAreaID">Area</label>
                            <select name="txtAreaID" id="txtAreaID" class="select2">
                                <option></option>
                                <?php
                                $count = 0;
                                if($objListAreaID)
                                foreach($objListAreaID as $ws){
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
                            <label class="pv2-label" for="txtParentDescription">Jefe Inmediato</label>
                            <div class="input-group afx-search">
                                <input type="hidden" id="txtParentEmployeeID" name="txtParentEmployeeID">
                                <input class="form-control" readonly id="txtParentDescription" type="text" autocomplete="off">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" id="btnClearEmployeeParent">
                                        <i aria-hidden="true" class="i-undo-2"></i>
                                        Limpiar
                                    </button>
                                </span>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button" id="btnSearchEmployeeParent">
                                        <i aria-hidden="true" class="i-search-5"></i>
                                        Buscar
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtStartOn">Fecha Inicio</label>
                            <div class="input-group date" data-date-format="yyyy-mm-dd">
                                <input size="16" class="form-control" type="text" autocomplete="off" name="txtStartOn" id="txtStartOn" value="">
                                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtEndOn">Fecha Fin</label>
                            <div class="input-group date" data-date-format="yyyy-mm-dd">
                                <input size="16" class="form-control" type="text" autocomplete="off" name="txtEndOn" id="txtEndOn" value="">
                                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="pv2-card is-required" id="profile">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-home"></i></span> Dónde vive</p>

                        <div class="form-group">
                            <label class="pv2-label" for="txtCountryID">Pais <span class="pv2-req">*</span></label>
                            <select name="txtCountryID" id="txtCountryID" class="select2">
                                <option></option>
                                <?php
                                if($objListCountry)
                                foreach($objListCountry as $ws){
                                    echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtStateID">Departamento del pais <span class="pv2-req">*</span></label>
                            <select name="txtStateID" id="txtStateID" class="select2">
                                <option></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtCityID">Municipio <span class="pv2-req">*</span></label>
                            <select name="txtCityID" id="txtCityID" class="select2">
                                <option></option>
                            </select>
                        </div>
                        <div class="form-group" id="dropdown">
                            <label class="pv2-label" for="txtAddress">Direccion</label>
                            <textarea class="form-control" id="txtAddress" name="txtAddress" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-6">
                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-coins"></i></span> Pago y vacaciones</p>

                        <div class="form-group">
                            <label class="pv2-label" for="txtHourCost">Costo/H</label>
                            <input class="form-control pv2-num afx-monto" type="text" inputmode="decimal" autocomplete="off" name="txtHourCost" id="txtHourCost" value="">
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtVacationBalanceDay">Vacaciones Acumuladas</label>
                            <input class="form-control" readonly type="number" autocomplete="off" name="txtVacationBalanceDay" id="txtVacationBalanceDay" value="">
                        </div>
                    </div>

                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-tags-2"></i></span> Referencias</p>

                        <div class="form-group">
                            <label class="pv2-label" for="txtReference1"><?php echo getBahavioSession($company->type,'app_rrhh_employee','labelReference1','Referencia1',$objListCompanyPageSetting) ?></label>
                            <input class="form-control" type="text" autocomplete="off" name="txtReference1" id="txtReference1" value="">
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtReference2">Referencia2</label>
                            <input class="form-control" type="text" autocomplete="off" name="txtReference2" id="txtReference2" value="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="pv2-card" id="profile-phones">
                        <div class="pv2-detail-head">
                            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-phone"></i></span> Teléfonos</p>
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

                <div class="col-xs-12 col-md-6">
                    <div class="pv2-card" id="profile-email">
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
            </div>
        </div>
    </form>
    <!-- /formulario -->

    <!-- barra inferior (telefono) -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label">Nuevo colaborador</p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>
