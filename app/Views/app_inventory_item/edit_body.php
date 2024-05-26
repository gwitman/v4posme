<div class="row"> 
	<div id="email" class="col-lg-12">
	
		<!-- botonera -->
		<div class="email-bar" style="border-left:1px solid #c9c9c9">                                
			<div class="btn-group pull-right">   
				<?php 
					if($callback == "false")
					{
						?>
						<a href="<?php echo base_url(); ?>/app_inventory_item/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>
						<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
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
					<h4>NUMERO:#<span class="invoice-num"><?php echo $objItem->itemNumber; ?></span></h4>
			</div>
			<!-- /titulo de comprobante-->
			
			<!-- body -->	
			<form id="form-new-account-journal" name="form-new-account-journal" class="form-horizontal" role="form"  >
			<div class="panel-body printArea"> 
			
				<ul id="myTab" class="nav nav-tabs">
					<li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
					<li><a href="#profile" data-toggle="tab">Referencias</a></li>
					<li class="<?php echo getBehavio($company->type,"app_inventory_item","menuBodegaPestana",""); ?>" ><a href="#warehouse" data-toggle="tab">Bodegas</a></li>
					<li><a href="#provider" data-toggle="tab">Proveedores</a></li>
					<li>
						<a href="#concepts" data-toggle="tab">						
						<?php echo getBehavio($company->type,"app_inventory_item","Conceptos",""); ?> 
						</a>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Mas<b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="#dropdown" data-toggle="tab">Comentario</a></li>
							<li><a id="btnClickArchivo" href="#dropdown-file" data-toggle="tab">Archivos</a></li>
							<li><a id="btnPrice" href="#price" data-toggle="tab">Precios</a></li>
							<li><a href="#sku" data-toggle="tab">Sku</a></li>
						 </ul>
					</li>
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane fade in active" id="home">	
						<div class="row">										
						<div class="col-lg-6">
								
								<div class="form-group">
										<label class="col-lg-4 control-label text-primary" for="normal">*Nombre</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtName" id="txtName" value="<?php echo htmlentities($objItem->name,ENT_QUOTES); ?>">												
											<input type="hidden" name="txtItemID" value="<?php echo $objItem->itemID; ?>"/>
											<input type="hidden" name="txtCompanyID" value="<?php echo $objItem->companyID; ?>"/>
											<input type="hidden" name="txtCallback" value="<?php echo $callback; ?>"/>
											<input type="hidden" name="txtComando" value="<?php echo $comando; ?>"/>
										</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtBarCode",""); ?>" id="divTxtBarCode" >
										<label class="col-lg-4 control-label text-primary" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","labelBarCode",""); ?></label>
										<div class="col-lg-8">
										
											<?php 
												if($company->type == "exceso")
												{
													?>
													<textarea class="form-control" type="text"  name="txtBarCode" id="txtBarCode" ><?php echo $objItem->barCode; ?></textarea>
													<?php 
												}
												else 
												{
													?>
													<input class="form-control"  type="text"  name="txtBarCode" id="txtBarCode" value="<?php echo $objItem->barCode; ?>" >
													<?php 
												}
											?>
											
											
											
											<span class="badge badge-inverse" >Puede poner varios codigo, separados por coma</span>
										</div>
										
								</div>
								
								<div id="divTraslateElemento2">
								</div>
								
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtPerecedero",""); ?> ">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","Perecedero",""); ?></label>
										<div class="col-lg-8">
											<input type="checkbox"   name="txtIsPerishable" id="txtIsPerishable" value="1" <?php echo ($objItem->isPerishable == 1) ? "checked":""; ?> >
										</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtCantidadZero",""); ?> ">
										<label class="col-lg-4 control-label" for="normal">Cantidad Zero</label> 
										<div class="col-lg-8">
											<input type="checkbox"   name="txtIsInvoiceQuantityZero" id="txtIsInvoiceQuantityZero" value="1"  <?php echo ($objItem->isInvoiceQuantityZero == 1) ? "checked":""; ?>  >
										</div>
								</div>

								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtFacturable",""); ?> ">
										<label class="col-lg-4 control-label" for="normal">Facturable</label>
										<div class="col-lg-8">
											<input type="checkbox"   name="txtIsInvoice" id="txtIsInvoice" value="1"  <?php echo ($objItem->isInvoice == 1) ? "checked":""; ?>  >
										</div>
								</div>
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","Servicio",""); ?></label> 
										<div class="col-lg-8">
											<input type="checkbox"   name="txtIsServices" id="txtIsServices" value="1"  <?php echo ($objItem->isServices == 1) ? "checked":""; ?>  >
										</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtCapacidad",""); ?> ">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","*Capacidad",""); ?></label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtCapacity" id="txtCapacity" value="<?php echo $objItem->capacity; ?>">												
										</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtCantidad",""); ?> ">
										<label class="col-lg-4 control-label" for="normal">Cantidad</label>
										<div class="col-lg-8">
											<input class="form-control" disabled  type="text"  name="txtQuantity" id="txtQuantity" value="<?php echo $objItem->quantity; ?>">												
										</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtCantidadMinima",""); ?>  ">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","*Cantidad Minima",""); ?></label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtQuantityMin" id="txtQuantityMin" value="<?php echo $objItem->quantityMin; ?>">												
										</div>
								</div>
								
								<div id="divTraslateQuantityMax">
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtCantidadMaxima",""); ?>  ">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","*Cantidad Maxima",""); ?></label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtQuantityMax" id="txtQuantityMax" value="<?php echo $objItem->quantityMax; ?>">												
										</div>
								</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtCosto",""); ?> ">
										<label class="col-lg-4 control-label" for="normal">Costo</label>
										<div class="col-lg-8">
											<input class="form-control" disabled type="text"  name="txtCost" id="txtCost" value="<?php echo $objItem->cost; ?>">												
										</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtSKUCompras",""); ?>">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","*SKU Compras",""); ?></label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtFactorBox" id="txtFactorBox" value="<?php echo $objItem->factorBox; ?>">												
										</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtSKUProduccion",""); ?>">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","*SKU Produccion",""); ?></label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtFactorProgram" id="txtFactorProgram" value="<?php echo $objItem->factorProgram; ?>">												
										</div>
								</div>
							
						</div>
						<div class="col-lg-6">
						
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtEstado",""); ?>">
									<label class="col-lg-4 control-label" for="selectFilter">*Estado</label>
									<div class="col-lg-8">
										<select name="txtStatusID" id="txtStatusID" class="select2">
												<option></option>																
												<?php
												if($objListWorkflowStage)
												foreach($objListWorkflowStage as $ws){
													if($ws->workflowStageID == $objItem->statusID)
													echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
													else
													echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
												}
												?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter"><?php echo getBehavio($company->type,"app_inventory_item","*Categoria",""); ?></label>
									<div class="col-lg-8">
										<select name="txtInventoryCategoryID" id="txtInventoryCategoryID" class="select2">
												<option></option>																
												<?php
												if($objListInventoryCategory)
												foreach($objListInventoryCategory as $ws){
													if($ws->inventoryCategoryID == $objItem->inventoryCategoryID )
													echo "<option value='".$ws->inventoryCategoryID."' selected >".$ws->name."</option>";
													else
													echo "<option value='".$ws->inventoryCategoryID."'  >".$ws->name."</option>";
													
												}
												?>
										</select>
									</div>
								</div>
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtFamilia",""); ?> ">
									<label class="col-lg-4 control-label" for="selectFilter"><?php echo getBehavio($company->type,"app_inventory_item","*Familia",""); ?></label>
									<div class="col-lg-8">
										<select name="txtFamilyID" id="txtFamilyID" class="select2">
												<option></option>																
												<?php
												if($objListFamily)
												foreach($objListFamily as $ws){
													if($ws->catalogItemID == $objItem->familyID)
													echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
													else
													echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
												}
												?>
										</select>
									</div>
								</div>
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtUM",""); ?> ">
									<label class="col-lg-4 control-label" for="selectFilter">*UM.</label>
									<div class="col-lg-8">
										<select name="txtUnitMeasureID" id="txtUnitMeasureID" class="select2">
												<option></option>
												<?php
												if($objListUnitMeasure)
												foreach($objListUnitMeasure as $ws){
													if($ws->catalogItemID == $objItem->unitMeasureID)
													echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
													else
													echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
												}
												?>
										</select>
									</div>
								</div>
								
								<div class="form-group  <?php echo getBehavio($company->type,"app_inventory_item","divTxtPresentacion",""); ?> ">
									<label class="col-lg-4 control-label" for="selectFilter"><?php echo getBehavio($company->type,"app_inventory_item","*Presentacion",""); ?></label>
									<div class="col-lg-8">
										<select name="txtDisplayID" id="txtDisplayID" class="select2">
												<option></option>
												<?php
												if($objListDisplay)
												foreach($objListDisplay as $ws){
													if($ws->catalogItemID == $objItem->displayID)
													echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
													else
													echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";																		
												}
												?>
										</select>
									</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtPresentacionUM",""); ?> ">
									<label class="col-lg-4 control-label" for="selectFilter">*UM. Presentacion</label>
									<div class="col-lg-8">
										<select name="txtDisplayUnitMeasureID" id="txtDisplayUnitMeasureID" class="select2">
												<option></option>
												<?php
												if($objListUnitMeasure)
												foreach($objListUnitMeasure as $ws){
													if($ws->catalogItemID == $objItem->displayUnitMeasureID)
													echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";																		
													else
													echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";																		
												}
												?>
										</select>
									</div>
								</div>
							
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtBodega",""); ?> ">
									<label class="col-lg-4 control-label" for="selectFilter">*Bodega</label>
									<div class="col-lg-8">
										<select name="txtDefaultWarehouseID" id="txtDefaultWarehouseID" class="select2">
												<option></option>
												<?php
												if($objListWarehouse)
												foreach($objListWarehouse as $ws){
													if($ws->warehouseID == $objItem->defaultWarehouseID)
													echo "<option value='".$ws->warehouseID."' selected >".$ws->name."</option>";
													else
													echo "<option value='".$ws->warehouseID."'  >".$ws->name."</option>";
												}
												?>
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">Moneda</label>
									<div class="col-lg-8">
										<select name="txtCurrencyID" id="txtCurrencyID" class="select2">
												<?php
												$count = 0;
												if($objListCurrency)
												foreach($objListCurrency as $currency){
													if($currency->currencyID == $objItem->currencyID )
														echo "<option value='".$currency->currencyID."' selected >".$currency->name."</option>";
													else
														echo "<option value='".$currency->currencyID."'  >".$currency->name."</option>";
													$count++;
												}
												?>
										</select>
									</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divControlCreatedOn","hidden"); ?>" ">
									<label class="col-lg-4 control-label" for="datepicker">Fecha de enlistamiento</label>
									<div class="col-lg-8">
										<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
											<input size="16"  class="form-control" type="text" name="txtCreatedOn" id="txtCreatedOn" value="<?php echo $objItem->createdOn; ?>" readonly="readonly" >
											<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
										</div>
									</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divControlModifiedOn","hidden"); ?>" ">
									<label class="col-lg-4 control-label" for="datepicker">Ultima actualizacion</label>
									<div class="col-lg-8">
										<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
											<input size="16"  class="form-control" type="text" name="txtModifiedOn" id="txtModifiedOn" value="<?php echo $objItem->modifiedOn; ?>" readonly="readonly" >
											<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
										</div>
									</div>
								</div>
								
								<div id="divTraslateElemento1">
								</div>
								
								
								
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">Barra</label>
									<div class="col-lg-8">
										<img id="btnPrinterCode" width="200px" height="70px" src="<?php echo base_url(); ?>/app_inventory_item/popup_add_renderimg/<?php echo $objItem->companyID; ?>/<?php echo $objComponent->componentID; ?>/<?php echo $objItem->itemID; ?>" />
									</div>
								</div>
								
								
								
								
						</div>
						</div>
						<div class="row" >
							<div class="col-lg-12" id="divContainerRowPersonalization" >
								<?php echo getBehavio($company->type,"app_inventory_item","divTraslateElementTablePrecio",""); ?>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="profile">
						<div class="row">	
							<div class="col-lg-6">
							
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldEquiposMarca",""); ?> ">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","Marca",""); ?></label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtReference1" id="txtReference1" value="<?php echo $objItem->reference1; ?>">												
										</div>
								</div>											
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldEquiposModelo",""); ?> ">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","Modelo",""); ?></label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtReference2" id="txtReference2" value="<?php echo $objItem->reference2; ?>">												
										</div>
								</div>
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldEquiposSerie",""); ?> ">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","Serie ó MAI",""); ?></label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtReference3" id="txtReference3" value="<?php echo $objItem->reference3; ?>">												
										</div>
								</div>	

								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?> ">
									<label class="col-lg-4 control-label" for="normal">Baño de servicio</label>
									<div class="col-lg-8">
										<input type="checkbox"   name="txtRealStateRoomBatchServices" id="txtRealStateRoomBatchServices" value="1"  <?php echo ($objItem->realStateRoomBatchServices == 1) ? "checked":""; ?>  >
									</div>
								</div>	
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?> ">
									<label class="col-lg-4 control-label" for="normal">Baño de visita</label>
									<div class="col-lg-8">
										<input type="checkbox"   name="txtRealStateRooBatchVisit" id="txtRealStateRooBatchVisit" value="1"  <?php echo ($objItem->realStateRooBatchVisit == 1) ? "checked":""; ?>  >
									</div>
								</div>	

								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?>">
									<label class="col-lg-4 control-label" for="normal">Cuarto de servicio</label>
									<div class="col-lg-8">
										<input type="checkbox"   name="txtRealStateRoomServices" id="txtRealStateRoomServices" value="1" <?php echo ($objItem->realStateRoomServices == 1) ? "checked":""; ?>  >
									</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?> ">
									<label class="col-lg-4 control-label" for="normal">Walk in closet</label>
									<div class="col-lg-8">
										<input type="checkbox"   name="txtRealStateWallInCloset" id="txtRealStateWallInCloset" value="1" <?php echo ($objItem->realStateWallInCloset == 1) ? "checked":""; ?>  >
									</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?>">
									<label class="col-lg-4 control-label" for="normal">Piscina privada</label>
									<div class="col-lg-8">
										<input type="checkbox"   name="txtRealStatePiscinaPrivate" id="txtRealStatePiscinaPrivate" value="1" <?php echo ($objItem->realStatePiscinaPrivate == 1) ? "checked":""; ?>  >
									</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?>">
									<label class="col-lg-4 control-label" for="normal">Area club con piscina</label>
									<div class="col-lg-8">
										<input type="checkbox"   name="txtRealStateClubPiscina" id="txtRealStateClubPiscina" value="1" <?php echo ($objItem->realStateClubPiscina == 1) ? "checked":""; ?>  >
									</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?>">
									<label class="col-lg-4 control-label" for="normal">Acepta mascota</label>
									<div class="col-lg-8">
										<input type="checkbox"   name="txtRealStateAceptanMascota" id="txtRealStateAceptanMascota" value="1" <?php echo ($objItem->realStateAceptanMascota == 1) ? "checked":""; ?>  >
									</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?>">
									<label class="col-lg-4 control-label" for="normal">Corretaje</label>
									<div class="col-lg-8">
										<input type="checkbox"   name="txtRealStateContractCorrentaje" id="txtRealStateContractCorrentaje" value="1" <?php echo ($objItem->realStateContractCorrentaje == 1) ? "checked":""; ?>  >
									</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?>">
									<label class="col-lg-4 control-label" for="normal">Plan de referido</label>
									<div class="col-lg-8">
										<input type="checkbox"   name="txtRealStatePlanReference" id="txtRealStatePlanReference" value="1" <?php echo ($objItem->realStatePlanReference == 1) ? "checked":""; ?>  >
									</div>
								</div>
								
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?>">
										<label class="col-lg-4 control-label" for="normal">Link youtube</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtRealStateLinkYoutube" id="txtRealStateLinkYoutube" value="<?php echo $objItem->realStateLinkYoutube; ?>">												
										</div>
								</div>

								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?>">
										<label class="col-lg-4 control-label" for="normal">Pagina web</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtRealStateLinkPaginaWeb" id="txtRealStateLinkPaginaWeb" value="<?php echo $objItem->realStateLinkPaginaWeb; ?>">												
										</div>
								</div>

								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?>">
										<label class="col-lg-4 control-label" for="normal">Fotos</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtRealStateLinkPhontos" id="txtRealStateLinkPhontos" value="<?php echo $objItem->realStateLinkPhontos; ?>">												
										</div>
								</div>

								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?>">
										<label class="col-lg-4 control-label" for="normal">Link ubicación google</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtRealStateLinkGoogleMaps" id="txtRealStateLinkGoogleMaps" value="<?php echo $objItem->realStateLinkGoogleMaps; ?>">												
										</div>
								</div>

								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?>">
										<label class="col-lg-4 control-label" for="normal">Otros link</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtRealStateLinkOther" id="txtRealStateLinkOther" value="<?php echo $objItem->realStateLinkOther; ?>">												
										</div>
								</div>

								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?>">
										<label class="col-lg-4 control-label" for="normal">Estilo de cocina</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtRealStateStyleKitchen" id="txtRealStateStyleKitchen" value="<?php echo $objItem->realStateStyleKitchen; ?>">												
										</div>
								</div>	
								
							</div>
							
							<div class="col-lg-6">
							
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldExclusiveGerencia","hidden"); ?> ">
									<label class="col-lg-4 control-label" for="selectFilter">Exclusividad con la agencia</label>
									<div class="col-lg-8">
										<select name="txtRealStateGerenciaExclusive" id="txtRealStateGerenciaExclusive" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>"   >
												<option></option>																
												<?php
												$count = 0;
												if($objListDisplayGerenciaExcl)
												foreach($objListDisplayGerenciaExcl as $ws){
													if( $ws->catalogItemID == $objItem->realStateGerenciaExclusive)
													echo "<option value='".$ws->catalogItemID."' selected  >".$ws->name."</option>";
													else
													echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
												}
												?>
										</select>
									</div>
								</div>
								
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?>">
									<label class="col-lg-4 control-label text-primary" for="selectFilter">*Pais</label>
									<div class="col-lg-8">
										<select name="txtCountryID" id="txtCountryID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>"   >
												<option></option>																
												<?php
												$count = 0;
												if($objListCountry)
												foreach($objListCountry as $ws){
													if( $ws->catalogItemID == $objItem->realStateCountryID)
													echo "<option value='".$ws->catalogItemID."' selected  >".$ws->name."</option>";
													else
													echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
												}
												?>
										</select>
									</div>
								</div>
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?>">
									<label class="col-lg-4 control-label text-primary" for="selectFilter">*Departamento</label>
									<div class="col-lg-8">
										<select name="txtStateID" id="txtStateID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>"   >
												<option></option>
												<?php
												$count = 0;
												if($objListState)
												foreach($objListState as $ws){
													if( $ws->catalogItemID == $objItem->realStateStateID)
													echo "<option value='".$ws->catalogItemID."' selected  >".$ws->name."</option>";
													else
													echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
												}
												?>
										</select>
									</div>
								</div>
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?>">
									<label class="col-lg-4 control-label text-primary" for="selectFilter">*Municipio</label>
									<div class="col-lg-8">
										<select name="txtCityID" id="txtCityID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>"   >
												<option></option>				
												<?php
												$count = 0;
												if($objListCity)
												foreach($objListCity as $ws){
													if( $ws->catalogItemID == $objItem->realStateCityID)
													echo "<option value='".$ws->catalogItemID."' selected  >".$ws->name."</option>";
													else
													echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
												}
												?>
										</select>
									</div>
								</div>
								
								
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?>">
										<label class="col-lg-4 control-label" for="normal">Ubicacion</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtRealStateReferenceUbicacion" id="txtRealStateReferenceUbicacion" value="<?php echo $objItem->realStateReferenceUbicacion; ?>">
										</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?>">
										<label class="col-lg-4 control-label" for="normal">Condominio</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtRealStateReferenceCondominio" id="txtRealStateReferenceCondominio" value="<?php echo $objItem->realStateReferenceCondominio; ?>">												
										</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?>">
										<label class="col-lg-4 control-label" for="normal">Zona</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtRealStateReferenceZone" id="txtRealStateReferenceZone" value="<?php echo $objItem->realStateReferenceZone; ?>">												
										</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?> ">
									<label class="col-lg-4 control-label" for="buttons">Agente</label>
									<div class="col-lg-8">
										<div class="input-group">
											<input type="hidden" id="txtEmployerID" name="txtEmployerID" value="<?php echo $objEmployerNatural != NULL ?  $objEmployer->entityID : "";  ?>">
											<input class="form-control" readonly id="txtEmployerDescription" type="txtEmployerDescription" value="<?php echo $objEmployerNatural != null ? strtoupper($objEmployer->employeNumber . " ". $objEmployerNatural->firstName . " ". $objEmployerNatural->lastName ) : ""; ?>">
											
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
								
							</div>
						
						</div>
							
							
					
					</div>
					<div class="tab-pane fade" id="concepts">
						
						<br/>
						<a href="#" class="btn btn-flat btn-info" id="btnNewDetailConcept" >Agregar</a>
						<a href="#" class="btn btn-flat btn-danger" id="btnDeleteDetailConcept" >Eliminar</a>									
						
						<div class="row">
							<div class="col-lg-12">
								<table class="table table-bordered" id="table_concept">
									<thead>
									  <tr>		
										<th></th>
										<th>Nombre</th>
										<th>Valor para Entrada</th>
										<th>Valor para Salida</th>
									  </tr>
									</thead>
									<tbody id="body_detail_concept">	
									</tbody>
								</table>
								
							</div><!-- End .col-lg-12  --> 
						</div><!-- End .row-fluid  -->
						
						
					</div>
					<div class="tab-pane fade" id="provider">
						<br/>
						<a href="#" class="btn btn-flat btn-info" id="btnNewDetailProvider" >Agregar</a>
						<a href="#" class="btn btn-flat btn-danger" id="btnDeleteDetailProvider" >Eliminar</a>									
						
						<div class="row">
							<div class="col-lg-12">
								<table class="table table-bordered" id="table_provider">
									<thead>
									  <tr>		
										<th></th>
										<th></th>
										<th>Codigo</th>
										<th>Nombre</th>
									  </tr>
									</thead>
									<tbody id="body_detail_provider">	
									</tbody>
								</table>
								
							</div><!-- End .col-lg-12  --> 
						</div><!-- End .row-fluid  -->
						
					</div>
					<div class="tab-pane fade" id="warehouse">
						<br/>
						<a href="#" class="btn btn-flat btn-info" id="btnNewDetailWarehouse" >Agregar</a>
						<a href="#" class="btn btn-flat btn-danger" id="btnDeleteDetailWarehouse" >Eliminar</a>									
						<script type="text/template"  id="tmpl_row_warehouse">
							<tr class="row_warehouse">
								<td>
									<input type="hidden" class="txtDetailWarehouseID" name="txtDetailWarehouseID[]" value="${warehouseID}"></input>
									<input type="hidden" class="txtDetailQuantityMax" name="txtDetailQuantityMax[]" value="${quantityMax}"></input>
									<input type="hidden" class="txtDetailQuantityMin" name="txtDetailQuantityMin[]" value="${quantityMin}"></input>
									<input type="hidden" class="txtDetailQuantity" name="txtDetailQuantity[]"    value="0"></input>
									${warehouseDescription}
								</td>
								<td>
									0.00
								</td>
								<td>
									${quantityMin}
								</td>
								<td>
									${quantityMax}
								</td>
							</tr>
						</script>
						
						<div class="row">
							<div class="col-lg-12">
								<table class="table table-bordered">
									<thead>
									  <tr>															
										<th>Bodega</th>
										<th>Cantidad</th>
										<th>Minimo</th>
										<th>Maximo</th>
									  </tr>
									</thead>
									<tbody id="body_detail_warehouse">	
										<tr>
											<td>
												<select name="txtTempWarehouseID" id="txtTempWarehouseID" class="select2">
														<option></option>
														<?php
														if($objListWarehouse)
														foreach($objListWarehouse as $ws){
															echo "<option value='".$ws->warehouseID."' selected >".$ws->name."</option>";																		
														}
														?>
												</select>
											</td>
											<td></td>
											<td><input class="form-control"  type="text"  name="txtTmpDetailQuantityMin" id="txtTmpDetailQuantityMin" value=""></td>
											<td><input class="form-control"  type="text"  name="txtTmpDetailQuantityMax" id="txtTmpDetailQuantityMax" value=""></td>
										</tr>
										<?php
											if($objItemWarehouse)
											foreach($objItemWarehouse as $i){
												echo "<tr class='row_warehouse'>";
													echo "<td>";
														echo "<input type='hidden' class='txtDetailWarehouseID' name='txtDetailWarehouseID[]' value='".$i->warehouseID."'></input>";
														echo "<input type='hidden' class='txtDetailQuantityMax' name='txtDetailQuantityMax[]' value='".$i->quantityMax."'></input>";
														echo "<input type='hidden' class='txtDetailQuantityMin' name='txtDetailQuantityMin[]' value='".$i->quantityMin."'></input>";
														echo "<input type='hidden' class='txtDetailQuantity' name='txtDetailQuantity[]'    value='0'></input>";
														echo $i->warehouseName;
													echo "</td>";
													echo "<td>";
														echo $i->quantity;
													echo "</td>";
													echo "<td>";
														echo $i->quantityMin;
													echo "</td>";
													echo "<td>";
														echo $i->quantityMax;
													echo "</td>";
												echo "</tr>";
											}
										?>
									</tbody>
								</table>
								
							</div><!-- End .col-lg-12  --> 
						</div><!-- End .row-fluid  -->
						
					</div>
					<div class="tab-pane fade" id="dropdown">
						
							<div class="form-group">
								<label class="col-lg-2 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","Descripcion",""); ?></label>
								<div class="col-lg-6">
									<textarea class="form-control"  id="txtDescription" name="txtDescription" rows="6"><?php echo $objItem->description; ?></textarea>
								</div>
							</div>
						
					</div>
					<div class="tab-pane fade" id="dropdown-file">
						
					</div>
					<div class="tab-pane fade" id="price">
						<div class="row">
							<div class="col-lg-12">
								<table class="table table-bordered" id="tblPrecios">
									<thead>
										<tr>																													  	
											<th>Tipo de Precio</th>
											<th>Precio</th>
											<th>Comision</th>
										</tr>
									</thead>
									<tbody id="body_detail_precio">																
										<?php
										if($objListPriceItem)
										foreach($objListPriceItem as $ws){
												?>
												<tr class="row_price">
													<td>
														<input type="hidden" class="txtDetailListPriceID" name="txtDetailListPriceID[]" value="<?php echo $ws->listPriceID; ?>"></input>
														<input type="hidden" class="txtDetailTypePriceID" name="txtDetailTypePriceID[]" value="<?php echo $ws->typePriceID; ?>"></input>														
														<?php echo getBehavio($company->type,"app_inventory_item_label_price",$ws->nameTypePrice,""); ?> 
													</td>																		
													<td>
														<input class="form-control"  type="text" id="txtDetailTypePriceValue" name="txtDetailTypePriceValue[]" value="<?php echo $ws->price; ?>">
													</td>
													<td>
														<input class="form-control"  type="text" id="txtDetailTypeComisionValue" name="txtDetailTypeComisionValue[]" value="<?php echo $ws->percentageCommision; ?>">
													</td>
												</tr>
												<?php
											
										}
										?>
									</tbody>
								</table>
								
							</div><!-- End .col-lg-12  --> 
						</div><!-- End .row-fluid  -->
					</div>
				
					<div class="tab-pane fade" id="sku">
						<br/>
						<a href="#" class="btn btn-flat btn-info" id="btnNewDetailSku" >Agregar</a>
						<a href="#" class="btn btn-flat btn-danger" id="btnDeleteDetailSku" >Eliminar</a>									
						<script type="text/template"  id="tmpl_row_sku">
							<tr class="row_sku">
								<td>
									<input type="hidden" class="txtDetailSkuID" name="txtDetailSkuID[]" value="${txtDetailSkuID}"></input>
									<input type="hidden" class="txtDetailSkuItemID" name="txtDetailSkuItemID[]" value="${txtDetailSkuItemID}"></input>
									<input type="hidden" class="txtDetailSkuCatalogItemID" name="txtDetailSkuCatalogItemID[]" value="${txtDetailSkuCatalogItemID}"></input>
									<input type="hidden" class="txtDetailSkuValue" name="txtDetailSkuValue[]" value="${txtDetailSkuValue}"></input>
									${skuDescription}
								</td>
								<td>
									${txtDetailSkuValue}
								</td>													
							</tr>
						</script>
						
						<div class="row">
							<div class="col-lg-12">
								<table class="table table-bordered">
									<thead>
									  <tr>															
										<th>Sku</th>
										<th>Cantidad</th>
									  </tr>
									</thead>
									<tbody id="body_detail_sku">	
										<tr>
											<td>
												<select name="txtTempSkuID" id="txtTempSkuID" class="select2">
														<option></option>
														<?php
														if($objListUnitMeasure)
														foreach($objListUnitMeasure as $ws){
															echo "<option value='".$ws->catalogItemID."'  >".$ws->display."</option>";																		
														}
														?>
												</select>
											</td>																
											<td><input class="form-control"  type="text"  name="txtTmpSkuCantidad" id="txtTmpSkuCantidad" value=""></td>																
										</tr>	
										<?php
										
										if($objItemSku)															
										foreach($objItemSku as $ws)
										{
											
											?>
											<tr class="row_sku">
												<td>
													<input type="hidden" class="txtDetailSkuID" name="txtDetailSkuID[]" value="<?php echo $ws->skuID; ?>"></input>
													<input type="hidden" class="txtDetailSkuItemID" name="txtDetailSkuItemID[]" value="<?php echo $ws->itemID; ?>"></input>
													<input type="hidden" class="txtDetailSkuCatalogItemID" name="txtDetailSkuCatalogItemID[]" value="<?php echo $ws->catalogItemID; ?>"></input>
													<input type="hidden" class="txtDetailSkuValue" name="txtDetailSkuValue[]" value="<?php echo $ws->value; ?>"></input>
													<?php echo $ws->display; ?>
												</td>
												<td>
													<?php echo $ws->value; ?>
												</td>
											</tr>
											<?php											
										}
										?>															
									</tbody>
								</table>
								
							</div><!-- End .col-lg-12  --> 
						</div><!-- End .row-fluid  -->
						
					</div>
				
				</div>    
		 
			</div>
			</form>
			<!-- /body -->
		</div>
	</div>
</div>

<?php echo getBehavio($company->type,"app_inventory_item","divTraslate",""); ?>