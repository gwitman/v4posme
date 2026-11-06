<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-file"></i></div>
            <div>
                <p class="pv2-header-title">CODIGO:#<span class="invoice-num"><?php echo $entityNumber; ?></span></p>
                <p class="pv2-header-sub">Documento <?php echo $objCustomerCreditDocument->documentNumber; ?></p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_cxc_document/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- body -->
    <form id="form-new-cxc-document" name="form-new-cxc-document" role="form" autocomplete="off">
        <div class="printArea">

            <ul id="myTab" class="nav nav-tabs">
                <li class="active"><a href="#home" data-toggle="tab">ENTIDAD</a></li>
                <li><a href="#document" data-toggle="tab">Documento</a></li>
                <li><a href="#amortization" data-toggle="tab">Amortizacion</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade in active" id="home">
                    <input type="hidden" name="txtCompanyID" value="<?php echo $objCustomer->companyID; ?>">
                    <input type="hidden" name="txtBranchID" value="<?php echo $objCustomer->branchID; ?>">
                    <input type="hidden" name="txtEntityID" value="<?php echo $objCustomer->entityID; ?>" id="txtEntityID">
                    <input type="hidden" name="txtStatusID" value="<?php echo $objCustomer->statusID ?>" id="txtStatusID">
                    <input type="hidden" name="txtCountryID" value="<?php echo $objCustomer->countryID ?>" id="txtCountryID">
                    <input type="hidden" name="txtEntityNumberID" value="<?php echo $entityNumber; ?>" id="txtEntityNumberID">
                    <input type="hidden" name="txtLimitCreditDol" value="<?php echo $entityLimitCreditDol; ?>" id="txtLimitCreditDol">
                    <input type="hidden" name="txtCustomerCreditDocumentID" value="<?php echo ($objCustomerCreditDocument->customerCreditDocumentID); ?>" id="txtCustomerCreditDocumentID">

                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Datos de la entidad</p>

                                <div class="form-group  <?php echo getBehavio($company->type, "app_cxc_document", "divTxtNombres", ""); ?>  ">
                                    <label class="pv2-label" for="txtFirstName"><?php echo getBehavio($company->type, "app_cxc_document", "lblTxtNombre", "*Nombres"); ?></label>
                                    <input class="form-control" type="text" readonly autocomplete="off" name="txtFirstName" id="txtFirstName" value="<?php echo $objNatural->firstName; ?>">
                                </div>
                                <div class="form-group  <?php echo getBehavio($company->type, "app_cxc_document", "divTxtApellidos", ""); ?>  ">
                                    <label class="pv2-label" for="txtLastName"><?php echo getBehavio($company->type, "app_cxc_document", "lblTxtApellidos", "*Apellidos"); ?></label>
                                    <input class="form-control" type="text" readonly autocomplete="off" name="txtLastName" id="txtLastName" value="<?php echo $objNatural->lastName; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtLegalName"><?php echo getBehavio($company->type, "app_cxc_document", "lblTxtFullName", "*Nombre completo"); ?></label>
                                    <input class="form-control" type="text" readonly autocomplete="off" name="txtLegalName" id="txtLegalName" value="<?php echo $objLegal != null ? $objLegal->legalName : ""; ?>">
                                </div>
                                <div class="form-group <?php echo getBehavio($company->type, "app_cxc_document", "divTxtIdentification", ""); ?> ">
                                    <label class="pv2-label" for="txtIdentification"><?php echo getBehavio($company->type, "app_cxc_document", "lblTxtIdentification", "*Identificacion"); ?></label>
                                    <input class="form-control" type="text" readonly autocomplete="off" name="txtIdentification" id="txtIdentification" value="<?php echo $entityIdentification; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-phone"></i></span> Contacto y balance</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtPhoneNumber"><?php echo getBehavio($company->type, "app_cxc_document", "lblTxtPhoneTemp", "Telefono"); ?></label>
                                    <input class="form-control" type="text" readonly autocomplete="off" name="txtPhoneNumber" id="txtPhoneNumber" value="<?php echo $entityPhoneNumber; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtBalanceDol">Balance US$</label>
                                    <input class="form-control txt-numeric afx-monto pv2-num" type="text" inputmode="decimal" autocomplete="off" name="txtBalanceDol" id="txtBalanceDol" value="<?php echo sprintf("%01.4f", $entityBalanceDol); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> DETALLES DE LINEA DE CREDITO<span class="invoice-num"></span></p>

                        <div class="pv2-table-wrap">
                            <table id="tb_detail_credit_line" class="table pv2-table">
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

                <div class="tab-pane fade" id="document">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Documento</p>

                                <div class="form-group">
                                    <label class="pv2-label">Numero</label>
                                    <input class="form-control" type="text" readonly autocomplete="off" name="" id="" value="<?php echo $objCustomerCreditDocument->documentNumber; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label">Monto Inicial</label>
                                    <input class="form-control txt-numeric pv2-num" readonly type="text" autocomplete="off" name="" id="" value="<?php echo number_format($objCustomerCreditDocument->amount, 2); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtCurrencyID">Moneda</label>
                                    <select name="txtCurrencyID" id="txtCurrencyID" class="select2">
                                        <option></option>
                                        <?php
                                        if ($objListCurrency)
                                            foreach ($objListCurrency as $ws) {
                                                if ($ws->currencyID == $objCustomerCreditDocument->currencyID)
                                                    echo "<option value='" . $ws->currencyID . "' selected>" . $ws->name . "</option>";
                                                else
                                                    echo "<option value='" . $ws->currencyID . "' >" . $ws->name . "</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-calendar-4"></i></span> Saldo y estado</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtCreditDocumentBalance">Saldo</label>
                                    <input class="form-control afx-monto pv2-num" type="text" inputmode="decimal" autocomplete="off" name="txtCreditDocumentBalance" id="txtCreditDocumentBalance" value="<?php echo sprintf("%01.4f", $objCustomerCreditDocument->balance); ?>">
                                </div>

                                <div class="form-group" <?php echo getBehavio($company->type, "app_cxc_document", "divTxtEstado", ""); ?>>
                                    <label class="pv2-label" for="txtCreditDocumentStatusID">Estado</label>
                                    <select name="txtCreditDocumentStatusID" id="txtCreditDocumentStatusID" class="select2">
                                        <option></option>
                                        <?php
                                        if ($objListWorkflowStage)
                                            foreach ($objListWorkflowStage as $ws) {
                                                if ($ws->workflowStageID == $objCustomerCreditDocument->statusID)
                                                    echo "<option value='" . $ws->workflowStageID . "' selected>" . $ws->name . "</option>";
                                                else
                                                    echo "<option value='" . $ws->workflowStageID . "' >" . $ws->name . "</option>";
                                            }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDocumentDate">Fecha</label>
                                    <div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
                                        <input size="16" class="form-control" type="text" autocomplete="off" name="txtDocumentDate" id="txtDocumentDate" value="<?php echo $objCustomerCreditDocument->dateOn; ?>">
                                        <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pv2-card">
                        <div class="pv2-detail-head">
                            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Entidades relacionadas</p>
                            <div class="pv2-actions">
                                <a href="#" class="btn btn-flat btn-info" id="btnNewEntity">Agregar</a>
                                <a href="#" class="btn btn-flat btn-success" id="btnEditEntity">Editar</a>
                                <a href="#" class="btn btn-flat btn-danger" id="btnDeleteLine">Eliminar</a>

                                <div class="btn-group">
                                    <button class="btn btn-flat btn-warning">Mostrar</button>
                                    <button class="btn btn-flat dropdown-toggle btn-warning" data-toggle="dropdown"><span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li class="active"><a href="#" class="toggle-vis" data-column="3"><i class="fa fa-tint"></i>Cliente</a></li>
                                        <li class="active"><a href="#" class="toggle-vis" data-column="4"><i class="fa fa-tint"></i>Tipo de Relacion</a></li>
                                        <li class="active"><a href="#" class="toggle-vis" data-column="5"><i class="fa fa-tint"></i>Tipo de Credito</a></li>
                                        <li class="divider"></li>
                                        <li class=""><a href="#" class="toggle-vis" data-column="6"><i class="fa fa-tint"></i>Estado de Credito</a></li>
                                        <li class=""><a href="#" class="toggle-vis" data-column="7"><i class="fa fa-tint"></i>Tipo de Garantia</a></li>
                                        <li class=""><a href="#" class="toggle-vis" data-column="8"><i class="fa fa-tint"></i>Tipo de Recuperacion</a></li>
                                        <li class="divider"></li>
                                        <li class=""><a href="#" class="toggle-vis" data-column="9"><i class="fa fa-tint"></i>Tasa de Desembolso</a></li>
                                        <li class=""><a href="#" class="toggle-vis" data-column="10"><i class="fa fa-tint"></i>Tasa de Balance</a></li>
                                        <li class=""><a href="#" class="toggle-vis" data-column="11"><i class="fa fa-tint"></i>Balance de Expiracion</a></li>
                                        <li class=""><a href="#" class="toggle-vis" data-column="12"><i class="fa fa-tint"></i>Tasa de Cuota</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="pv2-table-wrap">
                            <table id="tb_detail_credit_entity" class="table pv2-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>ccEntityRelatedID</th>
                                        <th>customerCreditDocumentID</th>
                                        <th>Cliente</th>
                                        <th>Tipo de Relacion</th>
                                        <th>Tipo de Credito</th>
                                        <th>Estado de Credito</th>
                                        <th>Tipo de Garantia</th>
                                        <th>Tipo de Recuperacion</th>
                                        <th>Tasa de Desembolso</th>
                                        <th>Tasa de Balance</th>
                                        <th>Balance de Expiracion</th>
                                        <th>Tasa de Cuota</th>
                                        <th>createdOn</th>
                                        <th>createdIn</th>
                                        <th>createdAt</th>
                                        <th>isActive</th>
                                    </tr>
                                </thead>
                                <tbody id="body_detail_line">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="amortization">
                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> DETALLES DE AMORTIZACION<span class="invoice-num"></span></p>

                        <div class="pv2-table-wrap" id="cxdAmortizationWrap">
                            <table id="tb_detail_amortization" class="table pv2-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>creditAmortizationID</th>
                                        <th>customerCreditDocumentID</th>
                                        <th>Balance Inicial</th>
                                        <th>Fecha</th>
                                        <th>Interes</th>
                                        <th>Capital</th>
                                        <th>Cuota</th>
                                        <th>Balance Final</th>
                                        <th>Remanente</th>
                                        <th>Dias Atrazados</th>
                                        <th>Nota</th>
                                        <th>Estado</th>
                                        <th>IsActive</th>
                                        <th>Abono al Capital</th>
                                    </tr>
                                </thead>
                                <tbody id="body_detail_line">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- /body -->

    <!-- barra inferior (telefono) -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label">Documento</p>
            <p class="pv2-mbar-total"><?php echo $objCustomerCreditDocument->documentNumber; ?></p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>
