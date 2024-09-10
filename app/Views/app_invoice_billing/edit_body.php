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
  /* DISEÑO DE TABLA AL PASAR EL MOUSE Y DAR CLIC EN UNA CELDA */
	.container-overlay.selected{
		border-color: #ff0000 !important;
		border-width: 5px !important;
	}

	.container-overlay.selected > .overlay{		
		display: block;
	}
	.container-overlay {
		border: 1px solid #dee2e6;
		border-radius: 8px;
		background-size: cover;
		background-position: center;
		cursor: pointer;
		height: 150px;
		width: 150px;
		position: relative;		
	}
	.container-overlay .overlay {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, 0.5);
		z-index: 1; 
		display: none;
	}
	.text-overlay {
		position: relative;
		z-index: 2;
		font-size: 18px;
		color: #fff;
		top: 1%; 
		left: 1%;
		transform: translate(-1%, -1%);
	}

	.container-overlay:hover {
		border-color: #007bff;
	}
	.container-overlay:hover .overlay {
		display: block;
	}
	/* FIN DISEÑO DE TABLA AL PASAR EL MOUSE Y DAR CLIC EN UNA CELDA */
</style>


<style>
	.custom-table-container-categorias  {
		max-height: 400px; /* Ajusta la altura según sea necesario */
        overflow-y: auto;
	}
    .text-overlay-categoria{
        position: relative;
        z-index: 2;
        font-size: 18px;
        color: #ffffff;
		top: 1%; 
		left: 1%;
		transform: translate(-1%, -1%);
    }
    .custom-table-categorias .item-categoria {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background-size: cover;
        background-position: center;
        cursor: pointer;
        min-height: 150px;
    }
    .custom-table-categorias .item-categoria:hover {
        border-color: #007bff;
        border-radius: 8px;
    }
    .custom-table-categorias .overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        border-radius: 8px;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        /*opacity: 0;*/
        transition: opacity 0.3s;
    }
    .item-categoria:hover .overlay {
        opacity: 1;
    }
    .item-categoria.selected {
        border-color: #ff0000 !important;
        border-width: 5px !important;
        border-radius: 12px;
    }
</style>


<style>
    .custom-table-container-inventory  {
        max-height: 550px;
        overflow-y: auto;
    }

    .item-producto {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background-size: cover;
        background-position: center;
        cursor: pointer;
        min-height: 150px;
        min-width: 150px;
        position: relative;
    }
    .item-producto:hover {
        border-color: #007bff;
        border-radius: 8px;
    }
    .item-producto .overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        border-radius: 8px;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        /*opacity: 0;*/
        transition: opacity 0.3s;
    }
    .item-producto td:hover .overlay {
        opacity: 1;
        border-radius: 8px;
    }
    .item-producto.selected {
        border-color: #ff0000 !important;
        border-width: 5px !important;
        border-radius: 12px;
    }
</style>



<div 
	class="isloading-overlay"
	id="divLoandingCustom"
	style="position:fixed; left:0; top:0; z-index: 10000; background: rgba(0,0,0,0.9); width: 100%; height: 1090px;"	
>
	<span class="isloading-wrapper  isloading-show  isloading-overlay">espere un momento ...  
		<i class="icon-refresh icon-spin">
		</i>
	</span>
</div>

<div class="row"> 
	<div id="email" class="col-lg-12">
	
		<!-- botonera -->
		<!--
		<div class="email-bar" style="border-left:1px solid #c9c9c9">                                
			<div class="btn-group pull-right">                                    
				<a href="<?php echo base_url(); ?>/app_invoice_billing/add" class="btn btn-info" id="btnNew"><i class="icon16 i-checkmark-4"></i> Nueva</a>
				<a href="<?php echo base_url(); ?>/app_invoice_billing/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
				<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>									
				<a href="#" class="btn btn-primary" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
				<a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
				
			</div>
		</div> 
		-->
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
					<h4>FACTURA:#<span class="invoice-num"><?php echo $objTransactionMaster->transactionNumber; ?></span></h4>
			</div>
			<!-- /titulo de comprobante-->
			
			<!-- body -->	
			<form id="form-new-invoice" name="form-new-invoice" class="form-horizontal" role="form">
			<div class="panel-body printArea"> 
			
				<div id="panelComandoAlternativa2">
				</div>
				
				<ul id="myTab" class="nav nav-tabs">
					<li class="active">
						<a href="#home" data-toggle="tab">Informacion</a>
					</li>
					<li class="elementMovilOculto <?php echo getBehavio($company->type,"app_invoice_billing","divPestanaReferencias",""); ?>  ">
						<a href="#profile" data-toggle="tab">Referencias.</a>
					</li>
					<li class="<?php echo getBehavio($company->type,"app_invoice_billing","divPestanaCredito",""); ?> " >
						<a href="#credit" data-toggle="tab">Info de Credito.</a>
					</li>
					<li class="dropdown elementMovilOculto <?php echo getBehavio($company->type,"app_invoice_billing","divPestanaMas",""); ?> ">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Mas <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="#dropdown" data-toggle="tab">Comentario</a></li>
							<li><a id="btnClickArchivo" href="#" target="blanck"  data-toggle="tab">Archivos</a></li>
						 </ul>
					</li>
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane fade in active" id="home">	
						<div class="row">										
						<div class="col-lg-6" id="divInformacionLeft" >
							
								<input type="hidden" id="txtUserID" name="txtUserID" value="<?php echo $userID; ?>">
								<input type="hidden" id="txtCompanyID" name="txtCompanyID" value="<?php echo $objTransactionMaster->companyID; ?>">
								<input type="hidden" id="txtTransactionID" name="txtTransactionID" value="<?php echo $objTransactionMaster->transactionID; ?>">
								<input type="hidden" id="txtTransactionMasterID" name="txtTransactionMasterID"  value="<?php echo $objTransactionMaster->transactionMasterID; ?>">
								<input type="hidden" id="txtCodigoMesero" name="txtCodigoMesero" value="<?php echo $codigoMesero;  ?>">
								
								<div class="form-group">
									<label class="col-lg-4 control-label" for="datepicker">Fecha</label>
									<div class="col-lg-8">
										<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
											<input size="16"  class="form-control" type="text" name="txtDate" id="txtDate" value="<?php echo $objTransactionMaster->transactionOn; ?>" >
											<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
										</div>
									</div>
								</div>
								
								
								<div class="form-group <?php echo getBehavio($company->type,"app_invoice_billing","divTxtCambio",""); ?> ">
										<label class="col-lg-4 control-label" for="normal">Cambio</label>
										<div class="col-lg-8">
											<input class="form-control"   type="text" disabled="disabled" name="txtExchangeRate" id="txtExchangeRate" value="<?php echo $exchangeRate; ?>">
										</div>
								</div>
								
								
								<div class="form-group" id="divNote" >
									<label class="col-lg-4 control-label" for="normal">Descripcion</label>
									<div class="col-lg-8">										
										<input class="form-control"   type="text" name="txtNote" id="txtNote" value="<?php echo $objTransactionMaster->note; ?>">
									</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_invoice_billing","divTxtMoneda",""); ?> "  id="divMoneda" >
									<label class="col-lg-4 control-label" for="selectFilter">Moneda</label>
									<div class="col-lg-8">
										<select name="txtCurrencyID" id="txtCurrencyID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>">
												<?php
												$count = 0;
												if($listCurrency)
												foreach($listCurrency as $currency){
													if($currency->currencyID == $objTransactionMaster->currencyID )
													echo "<option value='".$currency->currencyID."' selected >".$currency->name."</option>";
													else
													echo "<option value='".$currency->currencyID."'  >".$currency->name."</option>";
													$count++;
												}
												?>
										</select>
									</div>
								</div>
								
								
							
						</div>
						<div class="col-lg-6" id="divInformacionRight" >
						
								<div class="form-group">
									<label class="col-lg-4 control-label" for="buttons">Cliente</label>
									<div class="col-lg-8">
										<div class="input-group">
											<input type="hidden" id="txtCustomerID" name="txtCustomerID" value="<?php echo $objTransactionMaster->entityID;  ?>">
											<input class="form-control" readonly id="txtCustomerDescription" type="txtCustomerDescription" value="<?php echo $objNaturalDefault != null ? strtoupper($objCustomerDefault->customerNumber . " ". $objNaturalDefault->firstName . " ". $objNaturalDefault->lastName ) : strtoupper($objCustomerDefault->customerNumber." ".$objLegalDefault->comercialName); ?>">
											
											<span class="input-group-btn">
												<button class="btn btn-danger" type="button" id="btnClearCustomer">
													<i aria-hidden="true" class="i-undo-2"></i>
													clear
												</button>
											</span>
											<span class="input-group-btn">
												<button class="btn btn-primary" type="button" id="btnSearchCustomer">
													<i aria-hidden="true" class="i-search-5"></i>
													buscar
												</button>
											</span>
											<!--
											<span class="input-group-btn">
												<button class="btn btn-success" type="button" id="btnSearchCustomerNew">
													<i aria-hidden="true" class="i-plus"></i>
													nuevo
												</button>
											</span>
											-->
											
										</div>
									</div>
								</div>
								
								
								
								<div class="form-group  <?php echo getBehavio($company->type,"app_invoice_billing","divTxtCliente2",""); ?> " id="divBeneficiario" >
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_invoice_billing","divTxtClienteBeneficiario","Cliente"); ?></label>
										<div class="col-lg-8">
											<input class="form-control"   type="text" name="txtReferenceClientName" id="txtReferenceClientName" value="<?php echo $objTransactionMasterInfo->referenceClientName; ?>">
										</div>
								</div>
								
								<div id="divTxtElementoDisponibleParaMover1" class="hidden" >
									abc
								</div>
								
								<div class="form-group  <?php echo getBehavio($company->type,"app_invoice_billing","divTxtCedula2",""); ?>" id="divCedula" >
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_invoice_billing","divTxtCedulaBeneficiario","Cedula"); ?></label>
										<div class="col-lg-8">
											<input class="form-control"   type="text" name="txtReferenceClientIdentifier" id="txtReferenceClientIdentifier" value="<?php echo $objTransactionMasterInfo->referenceClientIdentifier; ?>">
										</div>
								</div>
								
								<div class="form-group" id="divTipoFactura" >
									<label class="col-lg-4 control-label" for="selectFilter">Tipo</label>
									<div class="col-lg-8">
										<select name="txtCausalID" id="txtCausalID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>">							
												<?php
												if($objCaudal)
												foreach($objCaudal as $causal){
													if($causal->transactionCausalID == $objTransactionMaster->transactionCausalID )
														echo "<option value='".$causal->transactionCausalID."' selected >".$causal->name."</option>";
													else
														echo "<option value='".$causal->transactionCausalID."'  >".$causal->name."</option>";
												}
												?>
										</select>
									</div>
								</div>
								
								

								<div class="form-group hidden" id="divLineaCredit">
									<label class="col-lg-4 control-label" for="selectFilter">Línea de Crédito</label>
									<div class="col-lg-8">
										<select name="txtCustomerCreditLineID" id="txtCustomerCreditLineID" class="<?php echo ($useMobile == "1" ? "" : "select2");  ?>" >
										</select>
									</div>
								</div>
								
								<div id="divTxtElementoDisponibleParaMove2" class="hidden" >
									abc
								</div>
								
								
							
						</div>
						</div>
					
						
					</div>
					<div class="tab-pane fade" id="profile">
						<div class="row">
							<div class="col-lg-6" id="divInformacionLeftZone" >
								<div class="form-group <?php echo getBehavio($company->type,"app_invoice_billing","divTxtZone",""); ?>" id="divZone"  >
									<label class="col-lg-4 control-label" for="selectFilter"><?php echo getBehavio($company->type,"app_invoice_billing","divLabelZone","Zona"); ?></label>
									<div class="col-lg-8">
										<select name="txtZoneID" id="txtZoneID" class="select2">
												<option></option>																
												<?php
												$count = 0;
												if($objListZone)
												foreach($objListZone as $z){
													if($z->catalogItemID  == $objTransactionMasterInfo->zoneID )
														echo "<option value='".$z->catalogItemID."' selected >".$z->display."</option>";
													else
														echo "<option value='".$z->catalogItemID."'  >".$z->display."</option>";
													$count++;
												}
												?>
										</select>
										
										<?php 
										if($objParameterEsResrarante == "true")
										{
											?>
											<a href="#" class="btn btn-flat btn-info btn-block btn-comando-showsona" id="btnShowZona">
												<i class="icon16 i-checkmark-4"></i> Zona
											</a>
											<?php 
										}
										?>
										
									</div>
								</div>
								
								
								
								<div class="form-group <?php echo getBehavio($company->type,"app_invoice_billing","divPrecio",""); ?> ">
									<label class="col-lg-4 control-label" for="selectFilter">Precio</label>
									<div class="col-lg-8">
										<select name="txtTypePriceID" id="txtTypePriceID" class="select2">
												<option></option>																
												<?php
												$count = 0;
												if($objListTypePrice)
												foreach($objListTypePrice as $price){
													if($count == 0 )
													echo "<option value='".$price->catalogItemID."' selected >".$price->display."</option>";
													else
													echo "<option value='".$price->catalogItemID."'  >".$price->display."</option>";
													$count++;
												}
												?>
										</select>
									</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_invoice_billing","divBodegaHidden",""); ?> "  id="divBodega"  >
									<label class="col-lg-4 control-label" for="selectFilter">Bodega</label>
									<div class="col-lg-8">
										<select name="txtWarehouseID" id="txtWarehouseID" class="select2">
												<option></option>																
												<?php
												$count = 0;
												if($objListWarehouse)
												foreach($objListWarehouse as $ware){
													if($objTransactionMaster->sourceWarehouseID == $ware->warehouseID)
													echo "<option value='".$ware->warehouseID."' selected >".$ware->name."</option>";
													else
													echo "<option value='".$ware->warehouseID."'  >".$ware->name."</option>";
													$count++;
												}
												?>
										</select>
									</div>
								</div>
								
								
							</div>
							<div class="col-lg-6" id="divInformacionRightZone" >
							
								<div class="form-group <?php echo getBehavio($company->type,"app_invoice_billing","divHiddenReference",""); ?>  " id="divReferencia"> 
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_invoice_billing","lblReferencia","Referencia"); ?></label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtReference3" id="txtReference3" value="<?php echo $objTransactionMaster->reference3; ?>">												
										</div>
								</div>		
								
								<div class="form-group <?php echo getBehavio($company->type,"app_invoice_billing","divHiddenEmployer",""); ?> " id="divVendedor" >
										<label class="col-lg-4 control-label" for="selectFilter"><?php echo getBehavio($company->type,"app_invoice_billing","txtTraductionVendedor","Vendedor"); ?></label>
										<div class="col-lg-8">
											<select name="txtEmployeeID" id="txtEmployeeID" class="select2">
													<option></option>																
													<?php
													$count = 0;
													if($objListEmployee)
													foreach($objListEmployee as $employee){
														if($employee->entityID == $objTransactionMaster->entityIDSecondary)
														echo "<option value='".$employee->entityID."' selected >".$employee->firstName."</option>";
														else
														echo "<option value='".$employee->entityID."'  >".$employee->firstName."</option>";
														$count++;
													}
													?>
											</select>
										</div>
								</div>
								
								
								
								<div class="form-group" id="divTrasuctionPhone" >
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_invoice_billing","txtTraductionPhone","Telefono"); ?></label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtNumberPhone" id="txtNumberPhone" value="<?php echo $objTransactionMaster->numberPhone; ?>">												
										</div>
								</div>	
								
								
								
								<div class="form-group <?php echo getBehavio($company->type,"app_invoice_billing","divMesa",""); ?> " id="divMesa" >
									<label class="col-lg-4 control-label" for="selectFilter"><?php echo getBehavio($company->type,"app_invoice_billing","txtTraductionMesa","Mesa"); ?></label>
									<div class="col-lg-8">
										<select name="txtMesaID" id="txtMesaID" class="select2">
												<option></option>																
												<?php
												$count = 0;
												if($objListMesa)
												foreach($objListMesa as $ware){
													if($ware->catalogItemID == $objTransactionMasterInfo->mesaID )
													echo "<option value='".$ware->catalogItemID."' selected >".$ware->name."</option>";
													else
													echo "<option value='".$ware->catalogItemID."'  >".$ware->name."</option>";
													$count++;
												}
												?>
										</select>
										
										<?php 
										if($objParameterEsResrarante == "true")
										{
											?>
											<a href="#" class="btn btn-flat btn-info btn-block btn-comando-showmesa" id="btnShowMesa">
												<i class="icon16 i-checkmark-4"></i> Mesa
											</a>
											<?php 
										}
										?>
										
										
									</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_invoice_billing","divNextVisitHidden",""); ?> " id="divSiguienteVisita" >
									<label class="col-lg-4 control-label" for="datepicker">Siguiente Visita</label>
									<div class="col-lg-8">
										<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
											<input size="16"  class="form-control" type="text" name="txtNextVisit" id="txtNextVisit" value="<?php echo $objTransactionMaster->nextVisit; ?>">
											<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
										</div>
									</div>
								</div>
								
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="credit">
						<div class="row">
								<div class="col-lg-6" id="divInformacionLeftReference">
								
									<div class="form-group  <?php echo getBehavio($company->type,"app_invoice_billing","divProviderCredit",""); ?> ">
											<label class="col-lg-4 control-label" for="normal">Proveedor de Credito</label>
											<div class="col-lg-8">
												<!--
												<input class="form-control"  type="text"  name="txtReference1" id="txtReference1" value="">												
												-->
												<select name="txtReference1" id="txtReference1" class="select2">
														<option value="0"></option>		
														<?php
														if($listProvider)
														foreach($listProvider as $ws){
															if($ws->entityID == $objTransactionMaster->reference1)
																echo "<option value='".$ws->entityID."' selected>".$ws->firstName." ".$ws->lastName."</option>";
															else 
																echo "<option value='".$ws->entityID."' >".$ws->firstName." ".$ws->lastName."</option>";
														}
														?>
												</select>
											</div>
									</div>
									
									<div class="form-group" id="divFixedExpenses" >
											<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_invoice_billing","txtTraductionExpenseLabel","% De Gasto."); ?></label>
											<div class="col-lg-8">
												<input class="form-control"   type="text" name="txtFixedExpenses" id="txtFixedExpenses" value="<?php echo helper_RequestGetValueObjet($objTransactionMasterDetailCredit,"reference1",0); ?>">
												<!--
												<a href="#" class="btn btn-primary  gap-right10" data-toggle="popover" data-placement="bottom" 
												data-content="Ejemplo: Del Interese de cada cuota, se multiplica por este % para saber de cuanto es la comision para FID-Local, este numero debe ser #0 o mayor que #1" title="" data-original-title="% de Gastos Fijo:">Ayuda:</a>
												-->
											</div>
									</div>
									
									<div class="form-group <?php echo getBehavio($company->type,"app_invoice_billing","divApplied",""); ?>  ">
											<label class="col-lg-4 control-label" for="normal">Aplicado</label>
											<div class="col-lg-8">
												<input type="checkbox" disabled   name="txtIsApplied" id="txtIsApplied" value="1" <?php if($objTransactionMaster->isApplied) echo "checked"; ?> >
											</div>
									</div>
									
									
									<div class="form-group hide">
											<label class="col-lg-4 control-label" for="normal">Primer Linea del Protocolo.</label>
											<div class="col-lg-8">
												<input class="form-control"   type="text" name="txtLayFirstLineProtocolo" id="txtLayFirstLineProtocolo" value="<?php echo helper_RequestGetValueObjet($objTransactionMasterDetailCredit,"reference3",0); ?>">
												
												<a href="#" class="btn btn-primary  gap-right10" data-toggle="popover" data-placement="bottom" 
												data-content="Ejemplo: 5" title="" 
												data-original-title="Tenor:">Ayuda:</a>
												
												
											</div>
									</div>
									
									
								</div>
								<div class="col-lg-6" id="divInformacionRightReference"  >
								
									<div class="form-group">
										<label class="col-lg-4 control-label" for="datepicker">Primer Pago</label>
										<div class="col-lg-8">
											<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
												<input size="16"  class="form-control" type="text" name="txtDateFirst" id="txtDateFirst" value="<?php echo $objTransactionMaster->transactionOn2; ?>" >
												<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
											</div>
										</div>
									</div>
									
									
									<div class="form-group">
											<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_invoice_billing","txtTermReference","Plazo ó Referencia2"); ?></label>
											<div class="col-lg-8">
												<input class="form-control"  type="text"  name="txtReference2" id="txtReference2" value="<?php echo $objTransactionMaster->reference2; ?>">												
											</div>
									</div>	
									
									
									<div class="form-group">
											<label class="col-lg-4 control-label" for="normal">Frecuencia</label>
											<div class="col-lg-8">
												<!--
												<input class="form-control"  type="text"  name="txtReference1" id="txtReference1" value="">												
												-->
												<select name="txtPeriodPay" id="txtPeriodPay" class="select2">
														<option value="0"></option>		
														<?php
														$index = -1;
														if($objListPay)
														foreach($objListPay as $ws){
																$index = $index + 1;																
																if($ws->catalogItemID == $objTransactionMaster->periodPay)
																echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
																else 
																echo "<option value='".$ws->catalogItemID."' >".$ws->name."</option>";	
														}
														?>
												</select>
											</div>
									</div>
									
									
									
								</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								
								
								<div class="form-group <?php echo getBehavio($company->type,"app_invoice_billing","divDesembolsoEfectivo",""); ?> ">
										<label class="col-lg-4 control-label" for="normal"></label>
										<div class="col-lg-8">
											 <label class="label-change-switch" id="txtLabelIsDesembolsoEfectivo">Es un desembolso en efectivo?</label>
											 <br/>
											 <div class="switch" data-on="success" data-off="warning">
												<input class="toggle"controls-row type="checkbox" checked id="txtCheckDeEfectivo" />
											 </div>																
										</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_invoice_billing","divReportSinRiesgo",""); ?> ">
										<label class="col-lg-4 control-label" for="normal"></label>
										<div class="col-lg-8">
											
											<label class="label-change-switch" id="txtLabelIsReportSinRiesgo">Reportar a SinRiesgo</label>
											<br/>
											
											<div class="switch" data-on="success" data-off="warning">
												<?php
												if(helper_RequestGetValueObjet ($objTransactionMasterDetailCredit,"reference2",0) == 1){
												?>
													<input class="toggle"controls-row type="checkbox" checked id="txtCheckReportSinRiesgo" name="txtCheckReportSinRiesgo" value="1"  />																									
												<?php
												}
												else{
												?>
													<input class="toggle"controls-row type="checkbox"  id="txtCheckReportSinRiesgo" name="txtCheckReportSinRiesgo" value="1"  />
												<?php
												}
												?>
											</div>																
										</div>
										
								</div>
								
								
								
							</div>
							<div class="col-lg-6">
								
								
								
							</div>
						</div>
					</div>
					
					
					<div class="tab-pane fade" id="dropdown">						
						<div class="form-group">
								<label class="col-lg-2 control-label" for="normal">Procedimiento</label>
								<div class="col-lg-10">
									<textarea class="form-control" type="text"  name="txtTMIReference1" id="txtTMIReference1" ><?php echo $objTransactionMasterInfo->reference1; ?></textarea>
								</div>
						</div>								
					</div>
					<div class="tab-pane fade" id="dropdown-file">
						
					</div>
				</div>    
		
				<br/>
				
				<div class="row" id="panelContainterDetailInvoice" >
					<div class="col-lg-12">
						<h3 id="labelTitleDetalle" >Detalle:</h3>
						<table id="tb_transaction_master_detail" class="table table-bordered" >
							<thead>
							  <tr>
								<th></th>
								<th></th>
								<th></th>
								<th>Codigo</th>
								<th>Descripcion</th>
								<th>U/M</th>
								<th>Cantidad</th>
								<th>Precio</th>
								<th>Total</th>
								<th></th>
								<th>skuQuantityBySku</th>
								<th>unitaryPriceInvidual</th>
								<th>Accion</th>
								<th>skuFormatoDescription</th>
								<th>Precio2</th>
								<th>Precio3</th>
								<th>itemNameDescription</th>
							  </tr>
							</thead>
							<tbody id="body_tb_transaction_master_detail">
							</tbody>
						</table>
						
					</div><!-- End .col-lg-12  --> 
				</div><!-- End .row-fluid  -->
				<input class="form-control"  type="hidden"  name="txtStatusIDOld" id="txtStatusIDOld" value="<?php echo $objTransactionMaster->statusID; ?>" >
				<input class="form-control"  type="hidden"  name="txtStatusID" id="txtStatusID" value="<?php echo $objTransactionMaster->statusID; ?>" >
				
				<br id="saltoDeLineaFila0" />
				
				<?php
				if($useMobile != "1")
				{
					?>
					<div class="row" id="rowBotoneraFacturaFila1" >
						<div class="col col-lg-2">
							<a href="#" class="btn btn-flat btn-info btn-block hidden btn-comando-factura" id="btnNewItem" ><i class="icon16 i-plus"></i> AGREGAR PRO</a>
						</div>
						<div class="col col-lg-2">
							<a href="#" class="btn btn-flat btn-danger btn-block hidden btn-comando-factura" id="btnDeleteItem" ><i class="icon16 i-remove"></i> ELIMINAR PRO</a>	
						</div>
						<div class="col col-lg-2">
							<div class="btn-group btn-block  hidden btn-comando-factura ">
								<button  type="button" class="btn btn-flat btn-success dropdown-toggle btn-block" data-toggle="dropdown" id="btnGroupdProducto" ><i class="icon16 i-box"></i> <?php echo getBehavio($company->type,"app_invoice_billing","lablBotunConfiguracion","PRODUCTO"); ?> <span class="caret"></span></button>
								<ul class="dropdown-menu">
										<li><a href="#" id="btnNewItemCatalog" >NUEVO PRODUCTO</a></li>						
										<li><a href="#" id="btnRefreshDataCatalogo" >ACTUALIZAR CATALOGO</a></li>										
								</ul>
							</div>
						</div>
						<div class="col col-lg-2">
							<div class="btn-group btn-block  hidden btn-comando-factura ">
								<button  type="button" class="btn btn-flat btn-inverse dropdown-toggle btn-block" data-toggle="dropdown"><i class="icon16 i-pencil"></i> SELECCION <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="#" id="btnBack"  >REGRESAR</a></li>
									<li><a href="#" id="btnSelectInvoice"  > SELECCIONAR</a></li>
									<li><a href="#" id="btnLinkPayment"  > LINK DE PAGO</a></li>
								</ul>								
							</div>
						</div>

					</div>
					<br id="saltoDeLineaFila2" />
					<div class="row" id="rowBotoneraFacturaFila2">
						<div class="col col-lg-2">
							<a href="<?php echo base_url(); ?>/app_invoice_billing/add/codigoMesero/<?php echo $codigoMesero; ?>" class="btn btn-flat btn-info btn-block hidden btn-comando-factura" id="btnNew"><i class="icon16 i-checkmark-4"></i> NUEVA FAC</a>
						</div>
						<div class="col col-lg-2">
							<a href="#" class="btn btn-flat btn-danger btn-block hidden btn-comando-factura" id="btnDelete"><i class="icon16 i-remove "></i> ELIMINAR FAC</a>	
						</div>
						<div class="col col-lg-2">
							<a href="#" class="btn btn-flat btn-primary btn-block hidden btn-comando-factura" id="btnPrinter"><i class="icon16 i-print "></i> IMPRIMIR</a>
						</div>
						
						<?php 
						if($objParameterShowComandoDeCocina == 'true' ){
							?>
								<div class="col col-lg-2">
									<a href="#" class="btn btn-flat btn-primary btn-block hidden btn-comando-factura" id="btnFooter"><i class="icon16 i-print "></i> COCINA</a>
								</div>
							<?php 
						}
						?>
						
						<?php 
						if($objParameterINVOICE_BILLING_SHOW_COMMAND_BAR == 'true' ){
							?>
								<div class="col col-lg-2">
									<a href="#" class="btn btn-flat btn-primary btn-block hidden btn-comando-factura" id="btnBar"><i class="icon16 i-print "></i> BAR</a>
								</div>
							<?php 
						}
						?>
						
						
					</div>
					
					<?php 
					if( $objParameterEsResrarante == "true")
					{
						?>
						<br id="saltoDeLineaFila3" />
						<div class="row" id="rowBotoneraFacturaFila3">
								<div class="col col-lg-2">
									<a href="#" class="btn btn-flat btn-primary btn-block btn-comando-factura" id="btnOptionPago"><i class="icon16 i-arrow-down-12 "></i> PROCESAR PAGO</a>
								</div>
								<div class="col col-lg-2">
									<a href="#" class="btn btn-flat btn-primary btn-block btn-comando-factura" id="btnVeDetalleFactura"><i class="icon16 i-accessibility "></i> <?php echo getBehavio($company->type,"app_invoice_billing","lablBotunVerDetalle","DETALLE"); ?>  </a>
								</div>
						</div>
					<?php 
					}
					?>
					
					
					<br/>
					<div class="row" id="rowBotoneraFacturaFila4">
						<?php
						$counter = 0;
						if($objListWorkflowStage)
						foreach($objListWorkflowStage as $ws){					
							$counter++;
							if($counter == 1){
							echo '<div class="col col-lg-2">';
								echo "<a href='#' class='btn btn-flat btn-warning btnAcept btn-block hidden btn-comando-factura'  data-valueworkflow='".$ws->workflowStageID."'   > <i class='icon16 i-checkmark-4'></i>  ".getBehavio($company->type,"core_web_language_workflowstage","billing_".$ws->name,$ws->name )."</a> ";							
							echo '</div>';
							}
							else{
							echo '<div class="col col-lg-2">';
								echo "<a href='#' class='btn btn-flat btn-warning btnAcept btnAceptAplicar btn-block hidden btn-comando-factura' data-valueworkflow='".$ws->workflowStageID."'  > <i class='icon16 i-checkmark-4'></i>  ".getBehavio($company->type,"core_web_language_workflowstage","billing_".$ws->name,$ws->name )  ."</a> ";
							echo '</div>';
							}
						}
						?>
						
						
						<?php 
						if($objParameterINVOICE_OPEN_CASH_WHEN_PRINTER_INVOICE == 'false' ){
							?>
								<div class="col col-lg-2">
									<a href="#" class="btn btn-flat btn-primary btn-block hidden btn-comando-factura" id="btnAbrirCaja"><i class="icon16 i-print "></i> ABRIR CAJA</a>
								</div>
							<?php 
						}
						?>
						
					</div>
				
					<?php
				}
				else{
					?>
					<div class="row">
					
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<div class="btn-group  btn-block hidden btn-comando-factura">
									<button type="button" class="btn btn-flat btn-info dropdown-toggle  btn-block" data-toggle="dropdown"><i class="icon32 i-pencil"></i> PRO <span class="caret"></span></button>
									<ul class="dropdown-menu">
											<li><a href="#" id="btnNewItem" >AGREGAR PRO</a></li>
											<li><a href="#" id="btnDeleteItem" >ELIMINAR PRO</a></li>											
									</ul>
								</div>
							</div>
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<div class="btn-group  btn-block hidden btn-comando-factura">
									<button type="button" class="btn btn-flat btn-danger dropdown-toggle  btn-block" data-toggle="dropdown"><i class="icon32 i-print"></i> FAC <span class="caret"></span></button>
									<ul class="dropdown-menu">											
											<li><a href="<?php echo base_url(); ?>/app_invoice_billing/index" id="btnBack" >REGRESAR</a></li>
											<li><a href="<?php echo base_url(); ?>/app_invoice_billing/add/codigoMesero/<?php echo $codigoMesero; ?>" id="btnNew">NUEVA FAC</a></li>
											<li><a href="#" id="btnDelete">ELIMINAR FAC</a></li>		
											<li><a href="#" id="btnPrinter">IMPRIMIR</a></li>	
											<li><a href="#" id="btnSelectInvoice"  > SELECCIONAR</a></li>
											<li><a href="#" id="btnLinkPayment"  > LINK DE PAGO</a></li>
									</ul>
								</div>
							</div>
							
							
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<div class="btn-group  btn-block hidden btn-comando-factura">
									<button type="button" class="btn btn-flat btn-primary dropdown-toggle  btn-block" data-toggle="dropdown"><i class="icon32 i-cloud"></i> SALV <span class="caret"></span></button>
									<ul class="dropdown-menu">
											<?php 
											if($objParameterShowComandoDeCocina == 'true' ){
												?>
													<li><a href="#"  id="btnFooter">COCINA</a></li>
												<?php 
											}
											?>
											<?php 
											if($objParameterINVOICE_BILLING_SHOW_COMMAND_BAR == 'true' ){
												?>
													<li><a href="#"  id="btnBar">BAR</a></li>
												<?php 
											}
											?>
											
											<?php											
											$counter = 0;
											if($objListWorkflowStage)
											foreach($objListWorkflowStage as $ws){					
												$counter++;
												if($counter == 1)
												{
													echo "<li><a href='#' class='btnAcept'  data-valueworkflow='".$ws->workflowStageID."'   > ". getBehavio($company->type,"core_web_language_workflowstage","billing_".$ws->name,$ws->name ) ."</a></li>";
												}
												else
												{												
													echo "<li class='badge-info' ><a href='#' class='btnAcept btnAceptAplicar' data-valueworkflow='".$ws->workflowStageID."'  >  ".getBehavio($company->type,"core_web_language_workflowstage","billing_".$ws->name,$ws->name ) ."</a> </li>";
												}
											}
											?>
									</ul>
								</div>
							</div>
							
							
					</div>
					<br/>
					<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<div class="btn-group  btn-block hidden btn-comando-factura">
									<button type="button" class="btn btn-flat btn-success dropdown-toggle  btn-block" data-toggle="dropdown"><i class="icon32 i-search"></i> MAS <span class="caret"></span></button>
									<ul class="dropdown-menu">											
											<li><a href="#" id="btnNewItemCatalog" >NUEVO PRODUCTO</a></li>						
											<li><a href="#" id="btnRefreshDataCatalogo" >ACTUALIZAR CATALOGO</a></li>
									</ul>
								</div>
							</div>							
					</div>
					
					
					<?php
				}
				?>
				
				
				
				
				<br id="saltoDeLineaFila6" />
				<br id="saltoDeLineaFila7" />
				<input class="form-control <?php echo getBehavio($company->type,"app_invoice_billing","txtScanerBarCode",""); ?> "  type="text"  name="txtScanerCodigo" id="txtScanerCodigo" value="">
																
				
									
				

				<div class="row">
				
				
					<div class="col-lg-3 <?php echo getBehavio($company->type,"app_invoice_billing","panelResumenFacturaTool",""); ?>   " id="panelResumenFacturaTool" >
						<div class="page-header">
							<h3>Tool Calcular Monto sin Iva</h3>
						</div>
						<table class="<?php echo $useMobile == "1" ? "" : "table table-bordered "  ?>"  >
							<tbody>
								<tr>
									<th style="text-align:left;" >01) MONTO</th>
									<td >
										<input type="text" id="txtToolMontoConIva" name="txtToolMontoConIva"  class="col-lg-12" value="" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
								</tr>
								<tr>
									<th style="text-align:left">02) </th>
									<td >
										<a href="#" class="btn btn-warning  btn-block" id="txtToolCalcular"><i class="icon16 i-checkmark-4"></i> CALCULAR</a>
									</td>
								</tr>
								<tr>
									<th style="text-align:left">03) MONTO SIN IVA</th>
									<td >
										<input type="text" id="txtToolMontoSinIva" name="txtToolMontoSinIva"  class="col-lg-12" value="" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					
					
					
					<div class="col-lg-3 <?php echo getBehavio($company->type,"app_invoice_billing","panelResumenFactura",""); ?>   " id="panelResumenFactura" >
						<div class="page-header">
							<h3>Ref.</h4>
						</div>
						<ul class="list-unstyled">
							<li><h3>CC: <span class="red-smooth">*</span></h3></li>
							<li><i class="icon16 i-arrow-right-3"></i>Scaner: Control + m = Imprimir</li>
							<li><i class="icon16 i-arrow-right-3"></i>Scaner: Control + k = Nuevo</li>
							<li><i class="icon16 i-arrow-right-3"></i>Scaner: Control + i = Abrir caja</li>
							<li><i class="icon16 i-arrow-right-3"></i>Ingreso Dolares: Control + a = Aplicar</li>
							<li><i class="icon16 i-arrow-right-3"></i>Ingreso Dolares: Control + b = Subir</li>
							
						</ul>
					</div>
					<div class="col-lg-5" id="divPaymentOption" >
						<div class="page-header">
							<h3>Pago</h3>
						</div>
						<table class="<?php echo $useMobile == "1" ? "" : "table table-bordered  "  ?>" id="table-resumen" >
							<tbody>
								<tr>
									<th style="text-align:left">01) CAMBIO</th>
									<td >
										<input type="text" id="txtChangeAmount" name="txtChangeAmount" readonly class="col-lg-12" value="<?php echo number_format($objTransactionMasterInfo->changeAmount,2); ?>" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
								</tr>
								
								<tr>
									<th style="text-align:left">02) MON.</th>
									<td>
										<input type="text" id="txtReceiptAmount" name="txtReceiptAmount"  class="col-lg-12 txt-numeric" value="<?php echo number_format($objTransactionMasterInfo->receiptAmount,2); ?>" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
								</tr>
								
								<tr>
									<th style="text-align:left">03) MON. EXT.</th>
									<td>
										<input type="text" id="txtReceiptAmountDol" name="txtReceiptAmountDol"  class="col-lg-12 txt-numeric" value="<?php echo number_format($objTransactionMasterInfo->receiptAmountDol,2); ?>" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
								</tr>
								
								<tr class="<?php echo getBehavio($company->type,"app_invoice_billing","rowOptionPaymentExtrasTarjeta",""); ?>" >
									<th style="text-align:left">04) Tarjeta. Nac.</th>
									<td style="">
										<input type="text" id="txtReceiptAmountTarjeta" name="txtReceiptAmountTarjeta"  class="col-lg-12 txt-numeric" value="<?php echo number_format($objTransactionMasterInfo->receiptAmountCard,2); ?>" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
									<td style="">
										<select name="txtReceiptAmountTarjeta_BankID" id="txtReceiptAmountTarjeta_BankID"  class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>">
												<?php
												$count = 0;
												if($objListBank)
												foreach($objListBank as $bank){
													if($bank->bankID == $objTransactionMasterInfo->receiptAmountCardBankID  )
													echo "<option value='".$bank->bankID."' selected >".$bank->name."</option>";
													else
													echo "<option value='".$bank->bankID."'  >".$bank->name."</option>";
													$count++;
												}
												?>
										</select>
									</td>
									<td style="">
										<input type="text" id="txtReceiptAmountTarjeta_Reference" name="txtReceiptAmountTarjeta_Reference"   class="col-lg-12" value="<?php echo $objTransactionMasterInfo->receiptAmountCardBankReference;  ?>" />
									</td>
								</tr>
								<tr class="<?php echo getBehavio($company->type,"app_invoice_billing","rowOptionPaymentExtrasTarjeta",""); ?>" >
									<th style="text-align:left">05) Tarjeta. Ext.</th>
									<td >
										<input type="text" id="txtReceiptAmountTarjetaDol" name="txtReceiptAmountTarjetaDol"  class="col-lg-12 txt-numeric" value="<?php echo number_format($objTransactionMasterInfo->receiptAmountCardDol,2); ?>" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
									<td style="">
										<select name="txtReceiptAmountTarjetaDol_BankID" id="txtReceiptAmountTarjetaDol_BankID"  class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>">
												<?php
												$count = 0;
												if($objListBank)
												foreach($objListBank as $bank){
													if($bank->bankID == $objTransactionMasterInfo->receiptAmountCardBankDolID )
													echo "<option value='".$bank->bankID."' selected >".$bank->name."</option>";
													else
													echo "<option value='".$bank->bankID."'  >".$bank->name."</option>";
													$count++;
												}
												?>
										</select>
									</td>
									<td style="">
										<input type="text" id="txtReceiptAmountTarjetaDol_Reference" name="txtReceiptAmountTarjetaDol_Reference"   class="col-lg-12" value="<?php echo $objTransactionMasterInfo->receiptAmountCardBankDolReference;  ?>" />
									</td>
								</tr>
								
								<tr class="<?php echo getBehavio($company->type,"app_invoice_billing","rowOptionPaymentExtras",""); ?>" >
									<th style="text-align:left">06) TRANS. Nac.</th>
									<td >
										<input type="text" id="txtReceiptAmountBank" name="txtReceiptAmountBank"  class="col-lg-12 txt-numeric" value="<?php echo number_format($objTransactionMasterInfo->receiptAmountBank,2); ?>" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
									<td style="">
										<select name="txtReceiptAmountBank_BankID" id="txtReceiptAmountBank_BankID"  class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>">
												<?php
												$count = 0;
												if($objListBank)
												foreach($objListBank as $bank){
													if($bank->bankID == $objTransactionMasterInfo->receiptAmountBankID )
													echo "<option value='".$bank->bankID."' selected >".$bank->name."</option>";
													else
													echo "<option value='".$bank->bankID."'  >".$bank->name."</option>";
													$count++;
												}
												?>
										</select>
									</td>
									<td style="">
										<input type="text" id="txtReceiptAmountBank_Reference" name="txtReceiptAmountBank_Reference"   class="col-lg-12" value="<?php echo $objTransactionMasterInfo->receiptAmountBankReference;  ?>" />
									</td>
								</tr>
								<tr class="<?php echo getBehavio($company->type,"app_invoice_billing","rowOptionPaymentExtras",""); ?>" >
									<th style="text-align:left" >07) TRANS. Ext.</th>
									<td >
										<input type="text" id="txtReceiptAmountBankDol" name="txtReceiptAmountBankDol"  class="col-lg-12 txt-numeric" value="<?php echo number_format($objTransactionMasterInfo->receiptAmountBankDol,2); ?>" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
									<td style="">
										<select name="txtReceiptAmountBankDol_BankID" id="txtReceiptAmountBankDol_BankID"  class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>">
												<?php
												$count = 0;
												if($objListBank)
												foreach($objListBank as $bank){
													if($bank->bankID == $objTransactionMasterInfo->receiptAmountBankDolID )
													echo "<option value='".$bank->bankID."' selected >".$bank->name."</option>";
													else
													echo "<option value='".$bank->bankID."'  >".$bank->name."</option>";
													$count++;
												}
												?>
										</select>
									</td>
									<td style="">
										<input type="text" id="txtReceiptAmountBankDol_Reference" name="txtReceiptAmountBankDol_Reference"   class="col-lg-12" value="<?php echo $objTransactionMasterInfo->receiptAmountBankDolReference;  ?>" />
									</td>
								</tr>
								
								<tr class="<?php echo getBehavio($company->type,"app_invoice_billing","rowOptionPaymentExtras",""); ?>" >
									<th style="text-align:left">08) Pt</th>
									<td >
										<input type="text" id="txtReceiptAmountPoint" name="txtReceiptAmountPoint"  class="col-lg-12 txt-numeric" value="<?php echo number_format($objTransactionMasterInfo->receiptAmountPoint,2); ?>" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
								</tr>
							
							</tbody>
						</table>
					</div>
					<div class="col-lg-4 <?php echo getBehavio($company->type,"app_invoice_billing","panelSummaryInvoice",""); ?> " id="divPanelShowSummaryNumber" >
						<div class="page-header">
							<h3>Resumen</h3>
						</div>
						<table class="<?php echo $useMobile == "1" ? "" : "table table-bordered "  ?>" id="table-resumen-pago" >
							<tbody>
								<tr>
									<th style="text-align:left" >01) SUB TOTAL</th>
									<td >
										<input type="text" id="txtSubTotal" name="txtSubTotal" readonly class="col-lg-12" value="" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
								</tr>
								<tr>
									<th style="text-align:left">02) IVA</th>
									<td >
										<input type="text" id="txtIva" name="txtIva" readonly class="col-lg-12" value="" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
								</tr>
								<tr>
									<th style="text-align:left">03) TOTAL</th>
									<td >
										<input type="text" id="txtTotal" name="txtTotal" readonly class="col-lg-12" value="" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
								</tr>
							</tbody>
						</table>
					</div><!-- End .col-lg-6  --> 
				</div><!-- End .row-fluid  -->
				   
			</div>
			
			
						

			<!-- Barra Lateral -->
			<div id="mySidebar" class="sidebar" style="background-color:white">
			  <div class="sidebar-content"> 
				
				<div class="row">
					<div id="siderbar_content_right">
					</div>
				</div>
				
				<div class="row" id="divPanelPaymentSideBar" >
					<div class="col col-lg-12">
						<a href="#" class="btn btn-flat btn-danger btn-block" id="btnRollbackPayment"><i class="icon16 i-arrow-bottom "></i> Regresar</a>
					</div>	
				</div>
			  </div>
			</div>


			<!-- Barra Lateral Factura-->
			<div id="mySidebarFactura" class="sidebar" style="background-color:white">
			  <div class="sidebar-content"> 
				<div id="siderbar_content_right_factura">
				</div>
				
				<div class="row" id="divPanelFacturaSideBarComandos" >	
				</div>
				
				</br>
				
				<div class="row" id="divPanelFacturaSideBar" >
					<div class="col col-lg-12">
						<a href="#" class="btn btn-flat btn-danger btn-block" id="btnRollbackFactura"><i class="icon16 i-arrow-bottom "></i> Regresar</a>
					</div>	
				</div>

                  <div class="mt-5 custom-table-container-categorias">
                      <div style="width: 98%; margin: 0 auto;">
                          <div class="row custom-table-categorias">
                              <?php
                              if (isset($objListInventoryCategoryRestaurant)):
                                  foreach($objListInventoryCategoryRestaurant as $k=>$category):
                                      ?>
                                      <div class="col-md-2 item-categoria"
                                           data-value="<?= $category->inventoryCategoryID ?>"
                                           data-parent="<?= $category->inventoryCategoryID?>"
                                           data-filter="[data-value='<?= $category->inventoryCategoryID ?>']"
                                           style="background-image: url('<?= base_url().'/'.$category->name ?>');"
                                           onclick="fnSelectCellCategoryInventory(this)">
                                          <span class="badge badge-success text-overlay-categoria"><?= $category->name; ?></span>
                                          <div class="overlay">
                                          </div>
                                      </div>
                                  <?php
                                  endforeach;
                              endif;
                              ?>
                          </div>
                      </div>
                  </div>

                  <div class="mt-5 custom-table-container-inventory">
                      <div style="width: 98%; margin: 0 auto;">
                          <div class="row">
                              <?php
                              if (isset($objListInventoryItemsRestaurant)):
                                  foreach ($objListInventoryItemsRestaurant as $k=>$item):
                                      ?>
                                      <?php
                                      if ($k== 0):
                                          ?>
                                          <div class="col-md-2 item-producto item-producto-back"
                                               data-filter="*"
                                               onclick="fnSelectCellInventoryBack(this)">
                                              <span class="badge badge-success text-overlay-categoria">REGRESAR</span>
                                              <div class="overlay">
                                              </div>
                                          </div>
                                      <?php
                                      endif;
                                      ?>
                                      <div class="col-md-2 item-producto"
                                           onclick="fnSelectCellInventory(this)"
                                           ondblclick="fnSelectDoubleCellInventory(this)"
                                           data-value="<?= $item["inventoryCategoryID"]; ?>"
                                           data-parent="<?= $item["inventoryCategoryID"]; ?>"
                                           data-codigo="<?= $item["Codigo"]; ?>">
                                          <span class="badge badge-success text-overlay-categoria"><?= $item['Nombre']; ?></span>
                                          <div class="overlay">
                                          </div>
                                      </div>
                                  <?php
                                  endforeach;
                              endif;
                              ?>
                          </div>
                      </div>
                  </div>
				
				
			  </div>
			</div>
			
			
			<!-- Barra Lateral Zona-->
			<div id="mySidebarZona" class="sidebar" style="background-color:white">
			  <div class="sidebar-content"> 
				<div id="siderbar_content_right_zona">
				</div>
				
				<div class="row" id="divPanelFacturaSideZona" >
					<div class="container mt-5">
						<table class="table custom-table-zalones">
							<tbody>
								<?php foreach ($objListZone as $index => $item): ?>
									<?php if ($index % 3 == 0): ?>
										<tr>
									<?php endif; ?>
									<td class="container-overlay" style="background-image: url('<?= base_url().'/resource/img/Zonas/'.$item->reference1 ?>'); background-size: auto; background-repeat: no-repeat;" 
										ondblclick="fnSelectCellZone(this)" data-value="<?= $item->catalogItemID; ?>">
										<span class="badge badge-success text-overlay"  ><?= $item->display; ?></span>
										<div class="overlay">
										</div>
									</td>
									<?php if ($index % 3 == 2): ?>
										</tr>
									<?php endif; ?>
								<?php endforeach; ?>
								<?php if (count($objListZone) % 3 != 0): ?>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
				
				</br>
				
				
				<div class="row" id="divPanelFacturaZona" >
					<div class="col col-lg-12">
						<a href="#" class="btn btn-flat btn-danger btn-block" id="btnRollbackZona"><i class="icon16 i-arrow-bottom "></i> Regresar</a>
					</div>	
				</div>
			  </div>
			</div>
			
			
			<!-- Barra Lateral Mesa-->
			<div id="mySidebarMesa" class="sidebar" style="background-color:white">
			  <div class="sidebar-content"> 
				<div id="siderbar_content_right_mesa">
				</div>
				
				<div class="row" id="divPanelFacturaSideMesa" >				
					<div class="container mt-5">
						<table class="table custom-table-mesas">
							<tbody>
								<?php foreach ($objListMesa as $index => $item): ?>
									<?php if ($index % 3 == 0): ?>
										<tr>
									<?php endif; ?>
									<td class="container-overlay" style="background-image: url('<?= base_url().'/resource/img/Mesas/'. $item->reference1; ?>'); background-size: auto; background-repeat: no-repeat;" 
										ondblclick="fnSelectCellMesaDoubleClick(this,<?= $item->reference2 ?>)" onclick="fnSelectCellMesa(this)" data-value="<?= $item->catalogItemID; ?>" data-parent="<?= $item->parentCatalogItemID; ?>">
										<span class="badge badge-success text-overlay"  ><?= $item->display; ?></span>
										<div class="overlay">
										</div>
									</td>
									<?php if ($index % 3 == 2): ?>
										</tr>
									<?php endif; ?>
								<?php endforeach; ?>
								<?php if (count($objListMesa) % 3 != 0): ?>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
				
				</br>
				
				
				<div class="row" id="divPanelFacturaMesa" >
					<div class="col-md-6">
						<a href="#" class="btn btn-flat btn-warning btn-block" id="btnRollbackZonas"><i class="icon16 i-arrow-left "></i> Zonas</a>
					</div>	
					<div class="col-md-6">
						<a href="#" class="btn btn-flat btn-danger btn-block" id="btnRollbackMesa"><i class="icon16 i-arrow-bottom "></i> Regresar</a>
					</div>
				</div>
			  </div>
			</div>
			



			</form>
			<!-- /body -->
			
			<div id="modalDialogMesaBussy" title="Mesa con factura" class="dialog hidden">
				<p>La mesa seleccionada tiene factura registrada, ¿Desea agregar productos a la mesa?</p>
			</div>

			<div id="modalDialogOpenPrimter" title="Formato de Impresion" class="dialog hidden">
				<p>Seleccione el formato que desea imprimir la factura</p>
			</div>
			
			<div id="modalDialogOpenPrimterCocina" title="Formato de Impresion" class="dialog">
				<p>Seleccione el formato que desea imprimir la factura</p>
			</div>
			
			<div id="modalDialogBackList" title="Regresar a la lista" class="dialog">
				<p>Seguro que desea regresa a la lista</p>
			</div>
			
			<div id="modalDialogOpenPrimterBar" title="Formato de Impresion" class="dialog">
				<p>Seleccione el formato que desea imprimir la factura</p>
			</div>
			
			<div id="modalDialogOpenPrimterClave" title="Clave" class="dialog">
				<input type="password" id="txtClaveValidToOpenCash" >
			</div>
			
			<div id="labelTotalAlternativo"  class="<?php echo getBehavio($company->type,"app_invoice_billing","panelLabelSumaryAlternativo","hidden"); ?>" >
				<div class="col col-lg-2 text-right">
					<h2>TOTAL: <span class="invoice-num red" id="txtTotalAlternativo">0.00</span></h2>
				</div>
			</div>
			
			
		</div>
	</div>
</div>

<div class="row"> 
	<div id="email" class="col-lg-12">
	
		<!-- botonera -->
		<!--
		<div class="email-bar" style="border-left:1px solid #c9c9c9">                                
			<div class="btn-group pull-right">                                    
				<a href="<?php echo base_url(); ?>/app_invoice_billing/add" class="btn btn-info" id="btnNew"><i class="icon16 i-checkmark-4"></i> Nueva</a>
				<a href="<?php echo base_url(); ?>/app_invoice_billing/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
				<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>									
				<a href="#" class="btn btn-primary" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
				<a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
			</div>
		</div> 
		-->
		<!-- /botonera -->
	</div>
	<!-- End #email  -->
</div>



  
<div id="main_content" >
</div>
<?php echo getBehavio($company->type,"app_invoice_billing","divTraslateElement",""); ?> 
