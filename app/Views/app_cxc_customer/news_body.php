<style>

  /* Estilo para ocultar la barra lateral */
  .sidebar 
  {
    height: 100%;
    width: 0;
    position: fixed;
    top: 0;
    right: 0;
    background-color: #fff;
    overflow-x: hidden;
    /*transition: 0.5s;*/
    padding-top: 60px;
  }

  /* Estilo para el contenido de la barra lateral */
  .sidebar-content 
  {
    padding: 20px;
  }

</style>


<div class="row"> 
	<div id="email" class="col-lg-12">
	
		<!-- botonera -->
		<div class="email-bar" style="border-left:1px solid #c9c9c9">                                
			<div class="btn-group pull-right">                                    
				<?php 
					if($callback == "false")
					{
						?>
						<a href="#" class="btn btn-warning" id="btnLeads"><i class="icon16 i-pencil"></i> Leads</a>
						<a href="<?php echo base_url(); ?>/app_cxc_customer/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>
						<?php
					}
					else{
						?>
						<?php
					}
				?>     
				<a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
			</div>
		</div> 
		<!-- /botonera -->
	</div>
	<!-- End #email  -->
</div>
<!-- End .row-fluid  -->

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
					
			<!-- titulo de comprobante-->
			<div class="panel-heading">
					<div class="icon"><i class="icon20 i-file"></i></div> 
					<h4>CODIGO:#<span class="invoice-num">00000000</span></h4>
			</div>
			<!-- /titulo de comprobante-->
			
			<!-- body -->	
			<form id="form-new-cxc-customer" name="form-new-cxc-customer" class="form-horizontal" role="form">
			<div class="panel-body printArea"> 
			
				<ul id="myTab" class="nav nav-tabs">
					<li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
					<li><a href="#profile" data-toggle="tab">Referencias.</a></li>
					<li class="<?php echo getBehavio($company->type,"app_cxc_customer","divPestanaTelefono",""); ?>" ><a href="#profile-phones" data-toggle="tab">Telefonos.</a></li>
					<li><a href="#profile-email" data-toggle="tab">Email.</a></li>
					<li class="<?php echo getBehavio($company->type,"app_cxc_customer","divPestanaCXC",""); ?>" ><a href="#profile-cxc" data-toggle="tab">CXC.</a></li>
					<li class="<?php echo getBehavio($company->type,"app_cxc_customer","divPestanaCXCLineas",""); ?>" ><a href="#profile-cxc-line" data-toggle="tab">CXC Lineas.</a></li>
					<li class="dropdown <?php echo getBehavio($company->type,"app_cxc_customer","divPestanaMas",""); ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Mas <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="#dropdown" data-toggle="tab">Direccion</a></li>
							<li><a href="#dropdown-file" data-toggle="tab">Archivos</a></li>
						 </ul>
					</li>
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane fade in active" id="home">	
						<div class="row">										
						<div class="col-lg-6">
							
								<div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtFechaNacimiento",""); ?>" >
									<label class="col-lg-4 control-label" for="datepicker">Nacimiento</label>
									<div class="col-lg-8">
										<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
											<input type="hidden" name="txtCallback" value="<?php echo $callback; ?>"/>
											<input size="16"  class="form-control" type="text" name="txtBirthDate" id="txtBirthDate" value="2014-01-30">
											<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
										</div>
									</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtFechaContacto","hidden"); ?>">
									<label class="col-lg-4 control-label" for="datepicker">Fecha de Contacto</label>
									<div class="col-lg-8">
										<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
											<input size="16"  class="form-control" type="text" name="txtDateContract" id="txtDateContract" value="2024-02-08">
											<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
										</div>
									</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtNombres",""); ?>  ">
										<label class="col-lg-4 control-label text-primary" for="normal">*Nombres</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtFirstName" id="txtFirstName" value="">												
										</div>
								</div>
								<div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtApellidos",""); ?> ">
										<label class="col-lg-4 control-label text-primary" for="normal">*Apellidos</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtLastName" id="txtLastName" value="">												
										</div>
								</div>
								<div class="form-group">
										<label class="col-lg-4 control-label text-primary" for="normal">*Nombre Completo</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtLegalName" id="txtLegalName" value="">												
										</div>
								</div>
								<div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtNombreComercial",""); ?> ">
										<label class="col-lg-4 control-label text-primary" for="normal">*Nombre Comercial</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtCommercialName" id="txtCommercialName" value="">												
										</div>
								</div>
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">Sexo</label>
									<div class="col-lg-8">
										<select name="txtSexoID" id="txtSexoID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>" >
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
								</div>
								<div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtTypeIdentification",""); ?> ">
									<label class="col-lg-4 control-label" for="selectFilter">Tipo Identificacion</label>
									<div class="col-lg-8">
										<select name="txtIdentificationTypeID" id="txtIdentificationTypeID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>" >
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
								</div>
								<div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtIdentification",""); ?> ">
										<label class="col-lg-4 control-label text-primary" for="normal">*Identificacion</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtIdentification" id="txtIdentification" value="">												
										</div>
								</div>
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Telefono</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtPhoneNumber" id="txtPhoneNumber" value="">
										</div>
								</div>
								
								
								
						</div>
						<div class="col-lg-6">
						
								<div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtEstado",""); ?> ">
									<label class="col-lg-4 control-label" for="selectFilter">Estado</label>
									<div class="col-lg-8">
										<select name="txtStatusID" id="txtStatusID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>" >
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
								<div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtClasificacion",""); ?>  ">
									<label class="col-lg-4 control-label" for="selectFilter"><?php echo getBehavio($company->type,"app_cxc_customer","Clasificacion",""); ?></label>
									<div class="col-lg-8">
										<select name="txtClasificationID" id="txtClasificationID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>"  >
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
								</div>
								<div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtTipo",""); ?>  ">
									<label class="col-lg-4 control-label" for="selectFilter">Tipo</label>
									<div class="col-lg-8">
										<select name="txtCustomerTypeID" id="txtCustomerTypeID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>"  >
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
								</div>
								<div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtCategoria",""); ?> " id="divTxtCategoryE" >
									<label class="col-lg-4 control-label" for="selectFilter"><?php echo getBehavio($company->type,"app_cxc_customer","Categoria",""); ?></label>
									<div class="col-lg-8">
										<select name="txtCategoryID" id="txtCategoryID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>"  >
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
								</div>
								<div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtSubCategoria",""); ?> ">
									<label class="col-lg-4 control-label" for="selectFilter">Sub Categoria</label>
									<div class="col-lg-8">
										<select name="txtSubCategoryID" id="txtSubCategoryID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>"  >
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
								</div>
								<div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtEstadoCivil",""); ?>  ">
									<label class="col-lg-4 control-label" for="selectFilter">Estado Civil</label>
									<div class="col-lg-8">
										<select name="txtCivilStatusID" id="txtCivilStatusID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>"  >
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
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtProfesionUFicio",""); ?>  ">
									<label class="col-lg-4 control-label" for="selectFilter">Profesion u Oficio</label>
									<div class="col-lg-8">
										<select name="txtProfesionID" id="txtProfesionID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>">
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
								</div>
								
									
						</div>
						</div>
					</div>
					<div class="tab-pane fade" id="profile-cxc-line">
						<div class="row">
							<div class="col-lg-12">
								<br/>
								<a href="#" class="btn btn-flat btn-info" id="btnNewLine" >Agregar</a>
								<a href="#" class="btn btn-flat btn-danger" id="btnDeleteLine" >Eliminar</a>
								
								<table id="tb_detail_credit_line" class="table table-bordered">
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
							
							<div class="col-lg-6">
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">Moneda</label>
									<div class="col-lg-8">
										<select name="txtCurrencyID" id="txtCurrencyID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>">
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
								</div>
								
								
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">Tipo de Pago</label>
									<div class="col-lg-8">
										<select name="txtTypePayID" id="txtTypePayID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>"   >
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
								</div>
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">Condicion de Pago</label>
									<div class="col-lg-8">
										<select name="txtPayConditionID" id="txtPayConditionID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>"   >
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
							<div class="col-lg-6">
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Ingresos US$</label>
										<div class="col-lg-8">
											<input class="form-control txt-numeric"  type="text"  name="txtIncomeDol" id="txtIncomeDol" value="5000.00">												
										</div>
								</div>
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Limite Credito US$</label>
										<div class="col-lg-8">
											<input class="form-control txt-numeric"  type="text"  name="txtLimitCreditDol" id="txtLimitCreditDol" value="20000.00">												
										</div>
								</div>
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Balance US$</label>
										<div class="col-lg-8">
											<input class="form-control txt-numeric"  type="text" readonly  name="txtBalanceDol" id="txtBalanceDol" value="">												
										</div>
								</div>
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Puntos</label>
										<div class="col-lg-8">
											<input class="form-control txt-numeric"  type="text" readonly  name="txtBalancePoint" id="txtBalancePoint" value="">												
										</div>
								</div>
								
								<div class="form-group">
									<label class="col-lg-4 control-label" for="buttons">Cuenta</label>
									<div class="col-lg-8">
										<div class="input-group">
											<input type="hidden" id="txtAccountID" name="txtAccountID">
											<input class="form-control" readonly id="txtAccountIDDescription" type="txtAccountIDDescription">																
											
											<span class="input-group-btn">
												<button class="btn btn-danger" type="button" id="btnClearAccount">
													<i aria-hidden="true" class="i-undo-2"></i>
													clear
												</button>
											</span>
											<span class="input-group-btn">
												<button class="btn btn-primary" type="button" id="btnSearchAccount">
													<i aria-hidden="true" class="i-search-5"></i>
													buscar
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
						
							<div class="col-lg-6">
									<div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtPais",""); ?> ">
										<label class="col-lg-4 control-label" for="selectFilter">Pais</label>
										<div class="col-lg-8">
											<select name="txtCountryID" id="txtCountryID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>"   >
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
									</div>
									<div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtDepartamento",""); ?> ">
										<label class="col-lg-4 control-label" for="selectFilter">Departamento</label>
										<div class="col-lg-8">
											<select name="txtStateID" id="txtStateID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>"   >
													<option></option>				
											</select>
										</div>
									</div>
									<div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtMunicipio",""); ?> ">
										<label class="col-lg-4 control-label" for="selectFilter">Municipio</label>
										<div class="col-lg-8">
											<select name="txtCityID" id="txtCityID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>"   >
													<option></option>				
											</select>
										</div>
									</div>
									<div class="form-group">
											<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_cxc_customer","txtDomicilio","Domicilio"); ?></label>
											<div class="col-lg-8">
												<input class="form-control"  type="text"  name="txtLocation" id="txtLocation" value="">												
											</div>
									</div>
									<div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtPresupuesto",""); ?>">
											<label class="col-lg-4 control-label" for="normal">Presupuesto U$</label>
											<div class="col-lg-8">
												<input class="form-control"  type="number"  name="txtBudget" id="txtBudget" value="">												
											</div>
									</div>
							</div>
							<div class="col-lg-6">
								
								
								
								
								<div class="form-group <?php echo getBehavio($company->type,"app_cxc_customer","divTxtTypeFirmID",""); ?> ">
									<label class="col-lg-4 control-label" for="selectFilter">Tipo de Firma</label>
									<div class="col-lg-8">
										<select name="txtTypeFirmID" id="txtTypeFirmID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>"   >
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
								</div>
								
								<div class="form-group">
									<label class="col-lg-4 control-label" for="buttons">Agente</label>
									<div class="col-lg-8">
										<div class="input-group">
											<input type="hidden" id="txtEmployerID" name="txtEmployerID" value="">
											<input class="form-control" readonly id="txtEmployerDescription" type="txtEmployerDescription" value="">
											
											<span class="input-group-btn">
												<button class="btn btn-danger" type="button" id="btnClearEmployer">
													<i aria-hidden="true" class="i-undo-2"></i>
													clear
												</button>
											</span>
											<span class="input-group-btn">
												<button class="btn btn-primary" type="button" id="btnSearchEmployer">
													<i aria-hidden="true" class="i-search-5"></i>
													buscar
												</button>
											</span>											
										</div>
									</div>
								</div>
								
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_cxc_customer","Referencia1",""); ?></label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtReference1" id="txtReference1" value="">												
										</div>
								</div>											
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_cxc_customer","Referencia2",""); ?></label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtReference2" id="txtReference2" value="">												
										</div>
								</div>	
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_cxc_customer","Referencia3",""); ?></label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtReference3" id="txtReference3" value="">												
										</div>
								</div>	
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_cxc_customer","Referencia4",""); ?></label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtReference4" id="txtReference4" value="">												
										</div>
								</div>	
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_cxc_customer","Referencia5",""); ?></label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtReference5" id="txtReference5" value="">												
										</div>
								</div>	
								
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="profile-phones">
						<div class="row">													
							<div class="col-lg-6">
								<br/>
								<a href="#" class="btn btn-flat btn-info" id="btnNewPhones" >Agregar</a>
								<a href="#" class="btn btn-flat btn-danger" id="btnDeletePhones" >Eliminar</a>
								
								<table id="tb_detail_phone" class="table table-bordered">
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
						<div class="row">													
							<div class="col-lg-6">
								<br/>
								<a href="#" class="btn btn-flat btn-info" id="btnNewEmail" >Agregar</a>
								<a href="#" class="btn btn-flat btn-danger" id="btnDeleteEmail" >Eliminar</a>
								<table id="tb_detail_email" class="table table-bordered">
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
						
							<div class="form-group">
								<label class="col-lg-4 control-label" for="normal">Direccion</label>
								<div class="col-lg-8">
									<textarea class="form-control"  id="txtAddress" name="txtAddress" rows="6"></textarea>
								</div>
							</div>
						
					</div>
					<div class="tab-pane fade" id="dropdown-file">
						
					</div>
				</div>    
				
			</div>
			</form>
			<!-- /body -->
		</div>
	</div>
</div>


<!-- Barra Lateral -->
<div id="mySidebar" class="sidebar" style="background-color:white">
  <div class="sidebar-content">
    <h2>Leads</h2>
    
      <div class="form-group">
        <label for="txtLeadTipo">Tipo:</label>
        <select class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>"  id="txtLeadTipo" name="txtLeadTipo" >
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
	  
      <div class="form-group"  id="divTxtLeadsSubTipo" >
        <label id="lblLeadSubTipoLeads" for="txtLeadSubTipo">Leads:</label>
        <select class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>" id="txtLeadSubTipo" name="txtLeadSubTipo" >
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
        <label for="txtLeadCategory">Categoria:</label>
        <select class=" <?php echo ($useMobile == "1" ? "" : "select2"); ?>" id="txtLeadCategory" name="txtLeadCategory" >
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
        <label for="comentario">Comentario:</label>
        <textarea class="form-control" id="txtLeadComentario" rows="3" name="txtLeadComentario" ></textarea>
      </div>
	  
	  
      <button type="button" id="saveLeads" class="btn btn-success">Salvar</button>    
	  <button type="button" id="cerrarLeads" class="btn btn-danger">Cerrar</button>    
  </div>
</div>



<?php echo getBehavio($company->type,"app_cxc_customer","divScriptCustom",""); ?>

	