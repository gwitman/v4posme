<!-- botonera -->
<div class="row">
    <div id="email" class="col-lg-12">
        <div class="email-bar" style="border-left:1px solid #c9c9c9">
            <div class="btn-group pull-right">
                <a href="<?php echo base_url(); ?>/app_cxc_document/index" id="btnBack" class="btn btn-warning"><i class="icon16 i-rotate"></i> Atras</a>
                <a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
            </div>
        </div>
    </div>
</div>
<!-- /botonera -->

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">

            <!-- titulo de comprobante-->
            <div class="panel-heading">
                <div class="icon"><i class="icon20 i-file"></i></div>
                <h4>CODIGO:#<span class="invoice-num"><?php echo $objCustomer->customerNumber; ?></span></h4>
            </div>
            <!-- /titulo de comprobante-->

            <!-- body -->
            <form id="form-new-cxc-document" name="form-new-cxc-document" class="form-horizontal" role="form">
                <div class="panel-body printArea">

                    <ul id="myTab" class="nav nav-tabs">
                        <li class="active"><a href="#home" data-toggle="tab">Cliente</a></li>
                        <li><a href="#document" data-toggle="tab">Documento</a></li>
                        <li><a href="#amortization" data-toggle="tab">Amortizacion</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="home">
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="hidden" name="txtCompanyID" value="<?php echo $objCustomer->companyID; ?>">
                                    <input type="hidden" name="txtBranchID" value="<?php echo $objCustomer->branchID; ?>">
                                    <input type="hidden" name="txtEntityID" value="<?php echo $objCustomer->entityID; ?>" id="txtEntityID">
                                    <input type="hidden" name="txtStatusID" value="<?php echo $objCustomer->statusID ?>" id="txtStatusID">
                                    <input type="hidden" name="txtCountryID" value="<?php echo $objCustomer->countryID ?>" id="txtCountryID">
                                    <input type="hidden" name="txtEntityNumberID" value="<?php echo $objCustomer->customerNumber; ?>" id="txtEntityNumberID">
                                    <input type="hidden" name="txtLimitCreditDol" value="<?php echo ($objCustomerCredit->limitCreditDol); ?>" id="txtLimitCreditDol">
                                    <input type="hidden" name="txtCustomerCreditDocumentID" value="<?php echo ($objCustomerCreditDocument->customerCreditDocumentID); ?>" id="txtCustomerCreditDocumentID">


                                    <div class="form-group  <?php echo getBehavio($company->type, "app_cxc_document", "divTxtNombres", ""); ?>  ">
                                        <label class="col-lg-4 control-label text-primary" for="normal"><?php echo getBehavio($company->type, "app_cxc_document", "lblTxtNombre", "*Nombres"); ?></label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" readonly name="txtFirstName" id="txtFirstName" value="<?php echo $objNatural->firstName; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group  <?php echo getBehavio($company->type, "app_cxc_document", "divTxtApellidos", ""); ?>  ">
                                        <label class="col-lg-4 control-label text-primary" for="normal"><?php echo getBehavio($company->type, "app_cxc_document", "lblTxtApellidos", "*Apellidos"); ?></label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" readonly name="txtLastName" id="txtLastName" value="<?php echo $objNatural->lastName; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label text-primary" for="normal"><?php echo getBehavio($company->type, "app_cxc_document", "lblTxtFullName", "*Nombre completo"); ?></label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" readonly name="txtLegalName" id="txtLegalName" value="<?php echo $objLegal->legalName; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group <?php echo getBehavio($company->type, "app_cxc_document", "divTxtIdentification", ""); ?> ">
                                        <label class="col-lg-4 control-label text-primary" for="normal"><?php echo getBehavio($company->type, "app_cxc_document", "lblTxtIdentification", "*Identificacion"); ?></label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" readonly name="txtIdentification" id="txtIdentification" value="<?php echo $objCustomer->identification; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type, "app_cxc_document", "lblTxtPhoneTemp", "Telefono"); ?></label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" readonly name="txtPhoneNumber" id="txtPhoneNumber" value="<?php echo $objCustomer->phoneNumber; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Balance US$</label>
                                        <div class="col-lg-8">
                                            <input class="form-control txt-numeric" type="text" name="txtBalanceDol" id="txtBalanceDol" value="<?php echo number_format($objCustomerCredit->balanceDol, 2); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel-heading">
                                        <div class="icon"><i class="icon20 i-file"></i></div>
                                        <h4>DETALLES DE LINEA DE CREDITO<span class="invoice-num"></span></h4>
                                    </div>

                                    <table id="tb_detail_credit_line" class="table table-bordered">
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

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Numero</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" readonly name="" id="" value="<?php echo $objCustomerCreditDocument->documentNumber; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Monto Inicial</label>
                                        <div class="col-lg-8">
                                            <input class="form-control txt-numeric" readonly type="text" name="" id="" value="<?php echo number_format($objCustomerCreditDocument->amount, 2); ?>">
                                        </div>
                                    </div>
									
									<div class="form-group">
                                        <label class="col-lg-4 control-label" for="selectFilter">Moneda</label>
                                        <div class="col-lg-8">
                                            <select name="txtCurrencyID" id="txtCurrencyID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>">
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

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Saldo</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtCreditDocumentBalance" id="txtCreditDocumentBalance" value="<?php echo number_format($objCustomerCreditDocument->balance, 2); ?>">
                                        </div>
                                    </div>
                                    
									<div class="form-group" <?php echo getBehavio($company->type, "app_cxc_document", "divTxtEstado", ""); ?>>
                                        <label class="col-lg-4 control-label" for="selectFilter">Estado</label>
                                        <div class="col-lg-8">
                                            <select name="txtCreditDocumentStatusID" id="txtCreditDocumentStatusID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>">
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
                                    </div>
									
									
									<div class="form-group">
										<label class="col-lg-4 control-label" for="datepicker">Fecha</label>
										<div class="col-lg-8">
											<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
												<input size="16"  class="form-control" type="text" name="txtDocumentDate" id="txtDocumentDate" value="<?php echo $objCustomerCreditDocument->dateOn; ?>" >
												<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
											</div>
										</div>
									</div>
									
									
									
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <br />
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

                                    <table id="tb_detail_credit_entity" class="table table-bordered">
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
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="panel-heading">
                                        <div class="icon"><i class="icon20 i-file"></i></div>
                                        <h4>DETALLES DE AMORTIZACION<span class="invoice-num"></span></h4>
                                    </div>

									<div style=" 
										height:500px;
										overflow-y: auto;  
										overflow-x: auto;
										padding:0px;
										margin:0px;
										"
									>
										<table id="tb_detail_amortization" class="table table-bordered">
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
                </div>
            </form>
        </div>
    </div>
</div>