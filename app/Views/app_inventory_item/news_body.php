


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
					<h4>NUMERO:#<span class="invoice-num">00000000</span></h4>
			</div>
			<!-- /titulo de comprobante-->
			
			<!-- body -->	
			<form id="form-new-account-journal" name="form-new-account-journal" class="form-horizontal" role="form">
			<div class="panel-body printArea"> 
			
				<ul id="myTab" class="nav nav-tabs">
					<li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
					<li class="<?php echo getBehavio($company->type,"app_inventory_item","menuBodegaReferencias",""); ?>" ><a href="#profile" data-toggle="tab">Referencias</a></li>
					<li class="<?php echo getBehavio($company->type,"app_inventory_item","menuBodegaPestana",""); ?>" ><a href="#warehouse" data-toggle="tab">Bodegas</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Mas<b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="#dropdown" data-toggle="tab">Comentario</a></li>
							<li><a href="#dropdown-file" data-toggle="tab">Archivos</a></li>
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
											<input class="form-control"  type="text"  name="txtName" id="txtName" value="<?php  echo $app_inventory_item_last_inventory_name;  ?>">		
											<input type="hidden" name="txtCallback" value="<?php echo $callback; ?>"/>
											<input type="hidden" name="txtComando" value="<?php echo $comando; ?>"/>
											<input type="hidden" name="txtRolNameSession" id="txtRolNameSession" value="<?php echo $objRolName; ?>"/>
										</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtBarCode",""); ?>"   >
										<label class="col-lg-4 control-label text-primary" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","labelBarCode",""); ?></label>
										<div class="col-lg-8">
										
											<?php 
												if($company->type == "exceso")
												{
													?>
													<textarea class="form-control" type="text"  name="txtBarCode" id="txtBarCode" ></textarea>
													<?php 
												}
												else 
												{
													?>
													<input class="form-control"  type="text"  name="txtBarCode" id="txtBarCode" value="" >
													<?php 
												}
											?>
											<span class="badge badge-inverse" >Puede poner varios codigo, separados por coma</span>
										</div>
								</div>
								
								<div id="divTraslateElemento2">
								</div>
								
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtPerecedero",""); ?>">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","Perecedero",""); ?></label>
										<div class="col-lg-8">
											<input type="checkbox"   name="txtIsPerishable" id="txtIsPerishable" value="1" <?php echo getBehavio($company->type,"app_inventory_item","chkPerecedero",""); ?> >
										</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtCantidadZero",""); ?> ">
										<label class="col-lg-4 control-label" for="normal">Cantidad Zero</label>
										<div class="col-lg-8">
											<input type="checkbox"   name="txtIsInvoiceQuantityZero" id="txtIsInvoiceQuantityZero" value="1" <?php  echo $objParameterInvoiceBillingQuantityZero == "true" ? "checked" : "";  ?> >
										</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtFacturable",""); ?>">
										<label class="col-lg-4 control-label" for="normal">Facturable</label>
										<div class="col-lg-8">
											<input type="checkbox"   name="txtIsInvoice" id="txtIsInvoice" value="1" checked >
										</div>
								</div>

								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","Servicio",""); ?></label>
										<div class="col-lg-8">
											<input type="checkbox"   name="txtIsServices" id="txtIsServices" value="1"  >
										</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtCapacidad",""); ?>  ">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","*Capacidad",""); ?></label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtCapacity" id="txtCapacity" value="1">												
										</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtCantidad",""); ?> ">
										<?php 
											if($comando == "pantalla_abierta_desde_la_compra")
											{
												?>
												<label class="col-lg-4 control-label text-primary" for="normal">Cantidad</label>
												<div class="col-lg-8">
													<input class="form-control"   type="text"  name="txtQuantity" id="txtQuantity" value="">												
												</div>
												<?php 
											}
											else
											{
												?>
												<label class="col-lg-4 control-label" for="normal">Cantidad</label>
												<div class="col-lg-8">
													<input class="form-control" disabled  type="text"  name="txtQuantity" id="txtQuantity" value="">												
												</div>
												<?php 
											}
										?>
										
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtCantidadMinima",""); ?>  ">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","*Cantidad Minima",""); ?></label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtQuantityMin" id="txtQuantityMin" value="1">												
										</div>
								</div>
								
								<div id="divTraslateQuantityMax">
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtCantidadMaxima",""); ?>  ">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","*Cantidad Maxima",""); ?></label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtQuantityMax" id="txtQuantityMax" value="<?php 
												if($company->type == "luciaralstate")
												{
													echo "24";
												}
												else 
												{
													echo "1000";
												}
											?>">												
										</div>
								</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtCosto",""); ?> ">
								
										<?php 
											if($comando == "pantalla_abierta_desde_la_compra")
											{
												?>																	
												<label class="col-lg-4 control-label text-primary " for="normal">Costo</label>
												<div class="col-lg-8">
													<input class="form-control"  type="text"  name="txtCost" id="txtCost" value="">												
												</div>
												<?php 
											}
											else
											{
												?>
												<label class="col-lg-4 control-label" for="normal">Costo</label>
												<div class="col-lg-8">
													<input class="form-control" disabled type="text"  name="txtCost" id="txtCost" value="">												
												</div>
												<?php 
											}
										?>
										
										
									
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtSKUCompras",""); ?> ">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","*SKU Compras",""); ?></label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtFactorBox" id="txtFactorBox" value="1">												
										</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtSKUProduccion",""); ?> ">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","*SKU Produccion",""); ?></label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtFactorProgram" id="txtFactorProgram" value="1">												
										</div>
								</div>
                                <div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtBirthday","hidden"); ?>" >
                                    <label class="col-lg-4 control-label" for="txtDateLastUse">Cumpleaños</label>
                                    <div class="col-lg-8">
                                        <div class="input-group date" data-date="<?= date('m-d') ?>" data-date-format="mm-dd">
                                            <input type="hidden" name="txtCallback" value="<?php echo $callback; ?>"/>
                                            <input size="16"  class="form-control" type="text" name="txtDateLastUse" id="txtDateLastUse" value="<?= date('m-d') ?>">
                                            <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                        </div>
                                    </div>
                                </div>
						</div>
						<div class="col-lg-6">
						
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtEstado",""); ?> ">
									<label class="col-lg-4 control-label" for="selectFilter">*Estado</label>
									<div class="col-lg-8">
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
								<div class="form-group">
									<label class="col-lg-4 control-label text-primary" for="selectFilter"><?php echo getBehavio($company->type,"app_inventory_item","*Categoria",""); ?></label>
									<div class="col-lg-8">
										<select name="txtInventoryCategoryID" id="txtInventoryCategoryID" class="select2">
												<option></option>																
												<?php
													$count 					= 0;
													$lastInventoryCategory 	= $app_inventory_item_last_inventory_category;
													$categoryDefaultSet		= $objParameterAll["INVENTORY_CATEGORY_BY_DEFAULT"];
													
													if($objListInventoryCategory)
													foreach($objListInventoryCategory as $ws)
													{
														if($count == 0 && !$lastInventoryCategory && $categoryDefaultSet == "true" )
														{
															echo "<option value='".$ws->inventoryCategoryID."' selected >".$ws->name."</option>";
														}
														else if ( $ws->inventoryCategoryID ==  $lastInventoryCategory && $categoryDefaultSet == "true" )
														{
															echo "<option value='".$ws->inventoryCategoryID."' selected >".$ws->name."</option>";
														}
														else
														{
															echo "<option value='".$ws->inventoryCategoryID."'  >".$ws->name."</option>";
														}
														$count++;
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
												$count 					= 0;
												$selectedDefaultFamiliy	= getBehavio($company->type,"app_inventory_item","selectedFamilyDefault","true");
												
												if($objListFamily)
												foreach($objListFamily as $ws){
													if($count == 0 && $selectedDefaultFamiliy == "true" )
													echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
													else
													echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
													$count++;
												}
												?>
										</select>
									</div>
								</div>
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtUM",""); ?>">
									<label class="col-lg-4 control-label text-primary" for="selectFilter">*UM.</label>
									<div class="col-lg-8">
										<select name="txtUnitMeasureID" id="txtUnitMeasureID" class="select2">
												<option></option>
												<?php
												$count 					= 0;
												$selectedDefaultUM		= getBehavio($company->type,"app_inventory_item","selectedUM","true");
												
												if($objListUnitMeasure)
												foreach($objListUnitMeasure as $ws){
													if($count == 0 && $selectedDefaultUM == "true" )
													echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
													else
													echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";																		
													$count++;																	
													
												}
												?>
										</select>
									</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtPresentacion",""); ?> ">
									<label class="col-lg-4 control-label" for="selectFilter"><?php echo getBehavio($company->type,"app_inventory_item","*Presentacion",""); ?></label>
									<div class="col-lg-8">
										<select name="txtDisplayID" id="txtDisplayID" class="select2">
												<option></option>
												<?php
												$count 							= 0;
												$selectedDefaultDisplayUM		= getBehavio($company->type,"app_inventory_item","selectedDisplayUM","true");
												
												if($objListDisplay)
												foreach($objListDisplay as $ws){
													if($count == 0  && $selectedDefaultDisplayUM == "true" )
													echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
													else
													echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
													$count++;
													
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
												$count = 0;
												if($objListUnitMeasure)
												foreach($objListUnitMeasure as $ws){
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
							
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtBodega",""); ?> ">
									<label class="col-lg-4 control-label" for="selectFilter">*Bodega</label>
									<div class="col-lg-8">
										<select name="txtDefaultWarehouseID" id="txtDefaultWarehouseID" class="select2">
												<option></option>
												<?php
												if($objListWarehouse)
												foreach($objListWarehouse as $ws){
													
													if($warehouseDefault == $ws->number )
													echo "<option value='".$ws->warehouseID."' selected >".$ws->name."</option>";
													else
													echo "<option value='".$ws->warehouseID."' >".$ws->name."</option>";
													$count++;
													
												}
												?>
										</select>
									</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divPanelMoneda",""); ?> ">
									<label class="col-lg-4 control-label" for="selectFilter">Moneda</label>
									<div class="col-lg-8">
										<select name="txtCurrencyID" id="txtCurrencyID" class="select2">
												<?php
												$count = 0;
												if($objListCurrency)
												foreach($objListCurrency as $currency){
													
													if( $company->type == "luciaralstate" )
													{
														if($count == 1)
															echo "<option value='".$currency->currencyID."' selected >".$currency->name."</option>";
													}
													else 
													{
														if($count == 0 )
															echo "<option value='".$currency->currencyID."' selected >".$currency->name."</option>";
														else
															echo "<option value='".$currency->currencyID."'  >".$currency->name."</option>";
													}													
													
													$count++;
												}
												?>
										</select>
									</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divControlCreatedOn","hidden"); ?>" >
									<label class="col-lg-4 control-label" for="datepicker">Fecha de enlistamiento</label>
									<div class="col-lg-8">
										<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
											<input size="16"  class="form-control" type="text" name="txtCreatedOn" id="txtCreatedOn" value="0000-00-00" readonly="readonly" >
											<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
										</div>
									</div>
								</div>
								
								
								<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divControlModifiedOn","hidden"); ?> ">
									<label class="col-lg-4 control-label" for="datepicker">Ultima actualizacion</label>
									<div class="col-lg-8">
										<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
											<input size="16"  class="form-control" type="text" name="txtModifiedOn" id="txtModifiedOn" value="0000-00-00" readonly="readonly" >
											<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
										</div>
									</div>
								</div>
								
								<div id="divTraslateElemento1">
								</div>
								
								
								
								
						</div>
						</div>
						<div class="row" id="divContainerRowPersonalization">
							<?php echo getBehavio($company->type,"app_inventory_item","divTraslateElementTablePrecio",""); ?>
						</div>
					</div>
					<div class="tab-pane fade" id="profile">
					
							<div class="row">	
								<div class="col-lg-6">
									<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldEquiposMarca",""); ?> " id="panelDivMarca" >
											<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","Marca",""); ?></label>
											<div class="col-lg-8">
												<input class="form-control"  type="text"  name="txtReference1" id="txtReference1" value="">												
											</div>
									</div>											
									<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldEquiposModelo",""); ?> ">
											<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","Modelo",""); ?></label>
											<div class="col-lg-8">
												<input class="form-control"  type="text"  name="txtReference2" id="txtReference2" value="">												
											</div>
									</div>
									<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldEquiposSerie",""); ?> ">
											<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","Serie ó MAI",""); ?></label>
											<div class="col-lg-8">
												<input class="form-control"  type="text"  name="txtReference3" id="txtReference3" value="">												
											</div>
									</div>	

									<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldTelefonoRealState",""); ?> ">
											<label class="col-lg-4 control-label" for="normal">Telefono</label>
											<div class="col-lg-8">
												<input class="form-control"  type="text"  name="txtRealStatePhone" id="txtRealStatePhone" value="">
											</div>
									</div>
									
									<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldEmailRealState",""); ?> ">
											<label class="col-lg-4 control-label" for="normal">Email</label>
											<div class="col-lg-8">
												<input class="form-control"  type="text"  name="txtRealStateEmail" id="txtRealStateEmail" value="">
											</div>
									</div>
									
									
								
									<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?> ">
										<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","lblBanoServicio","Baño de servicio"); ?></label>
										<div class="col-lg-8">
											<input type="checkbox"   name="txtRealStateRoomBatchServices" id="txtRealStateRoomBatchServices" value="1" checked >
										</div>
									</div>
									
									<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?> ">
										<label class="col-lg-4 control-label" for="normal">Baño de visita</label>
										<div class="col-lg-8">
											<input type="checkbox"   name="txtRealStateRooBatchVisit" id="txtRealStateRooBatchVisit" value="1" checked >
										</div>
									</div>
									
									<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?> ">
										<label class="col-lg-4 control-label" for="normal">Cuarto de servicio</label>
										<div class="col-lg-8">
											<input type="checkbox"   name="txtRealStateRoomServices" id="txtRealStateRoomServices" value="1" checked >
										</div>
									</div>
									
									<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?> ">
										<label class="col-lg-4 control-label" for="normal">Walk in closet</label>
										<div class="col-lg-8">
											<input type="checkbox"   name="txtRealStateWallInCloset" id="txtRealStateWallInCloset" value="1" checked >
										</div>
									</div>
									
									<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?> ">
										<label class="col-lg-4 control-label" for="normal">Piscina privada</label>
										<div class="col-lg-8">
											<input type="checkbox"   name="txtRealStatePiscinaPrivate" id="txtRealStatePiscinaPrivate" value="1" checked >
										</div>
									</div>
									
									<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?> ">
										<label class="col-lg-4 control-label" for="normal">Area club con piscina</label>
										<div class="col-lg-8">
											<input type="checkbox"   name="txtRealStateClubPiscina" id="txtRealStateClubPiscina" value="1" checked >
										</div>
									</div>
									
									<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?> ">
										<label class="col-lg-4 control-label" for="normal">Acepta mascota</label>
										<div class="col-lg-8">
											<input type="checkbox"   name="txtRealStateAceptanMascota" id="txtRealStateAceptanMascota" value="1" checked >
										</div>
									</div>
									
									<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?> ">
										<label class="col-lg-4 control-label" for="normal">Corretaje</label>
										<div class="col-lg-8">
											<input type="checkbox"   name="txtRealStateContractCorrentaje" id="txtRealStateContractCorrentaje" value="1" checked >
										</div>
									</div>
									
									<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?> ">
										<label class="col-lg-4 control-label" for="normal">Plan de referido</label>
										<div class="col-lg-8">
											<input type="checkbox"   name="txtRealStatePlanReference" id="txtRealStatePlanReference" value="1" checked >
										</div>
									</div>
									
									<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?> ">
											<label class="col-lg-4 control-label" for="normal">Link youtube</label>
											<div class="col-lg-8">
												<input class="form-control"  type="text"  name="txtRealStateLinkYoutube" id="txtRealStateLinkYoutube" value="">												
											</div>
									</div>

									<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?> ">
											<label class="col-lg-4 control-label" for="normal">Pagina web</label>
											<div class="col-lg-8">
												<input class="form-control"  type="text"  name="txtRealStateLinkPaginaWeb" id="txtRealStateLinkPaginaWeb" value="">												
											</div>
									</div>

									<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?> ">
											<label class="col-lg-4 control-label" for="normal">Fotos</label>
											<div class="col-lg-8">
												<input class="form-control"  type="text"  name="txtRealStateLinkPhontos" id="txtRealStateLinkPhontos" value="">												
											</div>
									</div>

									<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?> ">
											<label class="col-lg-4 control-label" for="normal">Link ubicación google</label>
											<div class="col-lg-8">
												<input class="form-control"  type="text"  name="txtRealStateLinkGoogleMaps" id="txtRealStateLinkGoogleMaps" value="">												
											</div>
									</div>

									<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?> ">
											<label class="col-lg-4 control-label" for="normal">Otros link</label>
											<div class="col-lg-8">
												<input class="form-control"  type="text"  name="txtRealStateLinkOther" id="txtRealStateLinkOther" value="">												
											</div>
									</div>

									<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?> ">
											<label class="col-lg-4 control-label" for="normal">Estilo de cocina</label>
											<div class="col-lg-8">
												<input class="form-control"  type="text"  name="txtRealStateStyleKitchen" id="txtRealStateStyleKitchen" value="">												
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
															echo "<option value='".$ws->catalogItemID."' selected  >".$ws->name."</option>";
														}
														?>
												</select>
											</div>
										</div>
										
										<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliariaPais","hidden"); ?> ">
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
										<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliariaDepartamento","hidden"); ?> ">
											<label class="col-lg-4 control-label" for="selectFilter">Departamento</label>
											<div class="col-lg-8">
												<select name="txtStateID" id="txtStateID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>"   >
														<option></option>				
												</select>
											</div>
										</div>
										<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliariaMunicipio","hidden"); ?>">
											<label class="col-lg-4 control-label" for="selectFilter">Municipio</label>
											<div class="col-lg-8">
												<select name="txtCityID" id="txtCityID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>"   >
														<option></option>				
												</select>
											</div>
										</div>
										
										<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?> ">
												<label class="col-lg-4 control-label" for="normal">Ubicacion</label>
												<div class="col-lg-8">
													<input class="form-control"  type="text"  name="txtRealStateReferenceUbicacion" id="txtRealStateReferenceUbicacion" value="">												
												</div>
										</div>
										
										<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?>">
												<label class="col-lg-4 control-label" for="normal">Condominio</label>
												<div class="col-lg-8">
													<input class="form-control"  type="text"  name="txtRealStateReferenceCondominio" id="txtRealStateReferenceCondominio" value="">												
												</div>
										</div>
										
										<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?>">
												<label class="col-lg-4 control-label" for="normal">Zona</label>
												<div class="col-lg-8">
													<input class="form-control"  type="text"  name="txtRealStateReferenceZone" id="txtRealStateReferenceZone" value="">												
												</div>
										</div>
										
										<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","fieldInmobiliaria","hidden"); ?>">
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
										
										
								</div>
							
							
							</div>
					
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
															echo "<option value='".$ws->warehouseID."'  >".$ws->name."</option>";																		
														}
														?>
												</select>
											</td>
											<td></td>
											<td><input class="form-control"  type="text"  name="txtTmpDetailQuantityMin" id="txtTmpDetailQuantityMin" value=""></td>
											<td><input class="form-control"  type="text"  name="txtTmpDetailQuantityMax" id="txtTmpDetailQuantityMax" value=""></td>
										</tr>
										<?php
										if($objListWarehouse)
										foreach($objListWarehouse as $ws){
											if($warehouseDefault == $ws->number){
												?>
												<tr class="row_warehouse">
													<td>
														<input type="hidden" class="txtDetailWarehouseID" name="txtDetailWarehouseID[]" value="<?php echo $ws->warehouseID; ?>"></input>
														<input type="hidden" class="txtDetailQuantityMax" name="txtDetailQuantityMax[]" value="1000"></input>
														<input type="hidden" class="txtDetailQuantityMin" name="txtDetailQuantityMin[]" value="0"></input>
														<input type="hidden" class="txtDetailQuantity" name="txtDetailQuantity[]"    value="0"></input>
														<?php echo $ws->name; ?>
													</td>
													<td>
														0.00
													</td>
													<td>
														1
													</td>
													<td>
														1000
													</td>
												</tr>
												<?php
											}																
										}
										?>
									</tbody>
								</table>
								
							</div><!-- End .col-lg-12  --> 
						</div><!-- End .row-fluid  -->
						
					</div>
					<div class="tab-pane fade" id="dropdown">
						
							<div class="form-group">
								<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","Descripcion",""); ?></label>
								<div class="col-lg-8">
									<textarea class="form-control"  id="txtDescription" name="txtDescription" rows="6"></textarea>
								</div>
							</div>
						
					</div>
					<div class="tab-pane fade" id="dropdown-file">
						
					</div>										
					<div class="tab-pane fade" id="price">
						<div class="row">
							<div class="col-lg-12">
								<table class="table table-bordered" id="tblPrecios" >
									<thead>
									  <tr>																													  	
										<th>Tipo de Precio</th>
										<th>Precio</th>
										<th>Comision</th>
									  </tr>
									</thead>
									<tbody id="body_detail_precio">																
										<?php
										if($objListTypePreice)
										foreach($objListTypePreice as $ws){																
												?>
												<tr class="row_price">
													<td>
														<input type="hidden" class="txtDetailListPriceID" name="txtDetailListPriceID[]" value="<?php echo $objParameterListPreiceDefault; ?>"></input>
														<input type="hidden" class="txtDetailTypePriceID" name="txtDetailTypePriceID[]" value="<?php echo $ws->catalogItemID; ?>"></input>																			
														<?php echo getBehavio($company->type,"app_inventory_item_label_price",$ws->name,""); ?> 
													</td>																		
													<td>
														<input class="form-control"  type="text" id="txtDetailTypePriceValue" name="txtDetailTypePriceValue[]" value="0">
													</td>
													<td>
														<input class="form-control"  type="text" id="txtDetailTypeComisionValue" name="txtDetailTypeComisionValue[]" value="0">
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
						<script type="text/template" id="tmpl_row_sku">
							<tr class="">
								<td>
									<input type="hidden" class="txtDetailSkuID" name="txtDetailSkuID[]" value="${txtDetailSkuID}" />
									<input type="hidden" class="txtDetailSkuItemID" name="txtDetailSkuItemID[]" value="${txtDetailSkuItemID}"/>
									<input type="hidden" class="txtDetailSkuCatalogItemID" name="txtDetailSkuCatalogItemID[]" value="${txtDetailSkuCatalogItemID}"/>
									<input type="hidden" class="txtDetailSkuValue" name="txtDetailSkuValue[]" value="${txtDetailSkuValue}"/>
									<input type="hidden" class="txtDetailSkuPrecio" name="txtDetailSkuPrecio[]" value="${txtDetailSkuPrecio}"/>
									<input type="hidden" class="txtDetailSkuDefault" name="txtDetailSkuDefault[]" value="${txtDetailSkuDefault}"/>
									${skuDescription}
								</td>
								<td>
									${txtDetailSkuValue}
								</td>          
								<td>
									${txtDetailSkuPrecio}
								</td>
								<td>
									<input type="checkbox" class="sku-default" id="sku-default-${txtDetailSkuID}" ${txtDetailSkuDefault ? "checked=checked" : ""} />
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
											<th>Precio</th>
											<th>Predeterminado</th>
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
											<td><input class="form-control"  type="text"  name="txtTmpSkuCantidad" id="txtTmpSkuCantidad" value=""/></td>																
											<td><input class="form-control"  type="text"  name="txtTmpSkuPrecio" id="txtTmpSkuPrecio" value=""/></td>
											<td><input type="checkbox" class="sku-default" name="txtTmpSkuDefault" id="txtTmpSkuDefault" value=""/></td>
										</tr>	
										<?php
										$indexc = 0;
										if($objListUnitMeasure)															
										foreach($objListUnitMeasure as $ws)
										{
											if($indexc == 0){
												?>
												<tr class="row_sku">
													<td>
														<input type="hidden" class="txtDetailSkuID" name="txtDetailSkuID[]" value="0"></input>
														<input type="hidden" class="txtDetailSkuItemID" name="txtDetailSkuItemID[]" value="0"></input>
														<input type="hidden" class="txtDetailSkuCatalogItemID" name="txtDetailSkuCatalogItemID[]" value="<?php echo $ws->catalogItemID; ?>"></input>
														<input type="hidden" class="txtDetailSkuValue" name="txtDetailSkuValue[]" value="1"></input>
														<input type="hidden" class="txtDetailSkuPrecio" name="txtDetailSkuPrecio[]" value=""></input>
														<input type="hidden" class="txtDetailSkuDefault" name="txtDetailSkuDefault[]" value=""></input>
														<?php echo $ws->display; ?>
													</td>
													<td>
														1
													</td>
													<td>
														0
													</td>
													<td>
														<input type="checkbox" class="sku-default" name="txtTmpSkuDefault" id="txtTmpSkuDefault" value="1" checked />
													</td> 	
												</tr>
												<?php
											}		
											$indexc++;
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