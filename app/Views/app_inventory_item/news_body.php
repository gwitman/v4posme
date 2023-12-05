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
										<li><a href="#profile" data-toggle="tab">Referencias</a></li>
										<li><a href="#warehouse" data-toggle="tab">Bodegas</a></li>
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
															</div>
													</div>
													
													<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtBarCode"); ?>"   >
															<label class="col-lg-4 control-label text-primary" for="normal"><?php echo getBehavio($company->type,"app_inventory_item","labelBarCode"); ?></label>
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
													
													
													<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtPerecedero"); ?>">
															<label class="col-lg-4 control-label" for="normal">Perecedero</label>
															<div class="col-lg-8">
																<input type="checkbox"   name="txtIsPerishable" id="txtIsPerishable" value="1" >
															</div>
													</div>
													
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Cantidad Zero</label>
															<div class="col-lg-8">
																<input type="checkbox"   name="txtIsInvoiceQuantityZero" id="txtIsInvoiceQuantityZero" value="1" <?php  echo $objParameterInvoiceBillingQuantityZero == "true" ? "checked" : "";  ?> >
															</div>
													</div>
													
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Facturable</label>
															<div class="col-lg-8">
																<input type="checkbox"   name="txtIsInvoice" id="txtIsInvoice" value="1" checked >
															</div>
													</div>

													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Servicio</label>
															<div class="col-lg-8">
																<input type="checkbox"   name="txtIsServices" id="txtIsServices" value="1"  >
															</div>
													</div>
													
													<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtCapacidad"); ?>  ">
															<label class="col-lg-4 control-label" for="normal">*Capacidad</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtCapacity" id="txtCapacity" value="1">												
															</div>
													</div>
													
													<div class="form-group">
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
													
													<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtCantidadMinima"); ?>  ">
															<label class="col-lg-4 control-label" for="normal">*Cantidad Minima</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtQuantityMin" id="txtQuantityMin" value="1">												
															</div>
													</div>
													
													<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtCantidadMaxima"); ?>  ">
															<label class="col-lg-4 control-label" for="normal">*Cantidad Maxima</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtQuantityMax" id="txtQuantityMax" value="1000">												
															</div>
													</div>
													
													<div class="form-group">
													
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
													
													<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtSKUCompras"); ?> ">
															<label class="col-lg-4 control-label" for="normal">*SKU Compras</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtFactorBox" id="txtFactorBox" value="1">												
															</div>
													</div>
													
													<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtSKUProduccion"); ?> ">
															<label class="col-lg-4 control-label" for="normal">*SKU Produccion</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtFactorProgram" id="txtFactorProgram" value="1">												
															</div>
													</div>
												
											</div>
											<div class="col-lg-6">
											
													<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtEstado"); ?> ">
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
														<label class="col-lg-4 control-label text-primary" for="selectFilter">*Categoria</label>
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
													<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtFamilia"); ?> ">
														<label class="col-lg-4 control-label" for="selectFilter">*Familia</label>
														<div class="col-lg-8">
															<select name="txtFamilyID" id="txtFamilyID" class="select2">
																	<option></option>																
																	<?php
																	$count = 0;
																	if($objListFamily)
																	foreach($objListFamily as $ws){
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
													<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtUM"); ?>">
														<label class="col-lg-4 control-label text-primary" for="selectFilter">*UM.</label>
														<div class="col-lg-8">
															<select name="txtUnitMeasureID" id="txtUnitMeasureID" class="select2">
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
													
													<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtPresentacion"); ?> ">
														<label class="col-lg-4 control-label" for="selectFilter">*Presentacion</label>
														<div class="col-lg-8">
															<select name="txtDisplayID" id="txtDisplayID" class="select2">
																	<option></option>
																	<?php
																	$count = 0;
																	if($objListDisplay)
																	foreach($objListDisplay as $ws){
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
													
													<div class="form-group <?php echo getBehavio($company->type,"app_inventory_item","divTxtPresentacionUM"); ?> ">
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
												
													<div class="form-group">
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
													
													<div class="form-group">
														<label class="col-lg-4 control-label" for="selectFilter">Moneda</label>
														<div class="col-lg-8">
															<select name="txtCurrencyID" id="txtCurrencyID" class="select2">
																	<?php
																	$count = 0;
																	if($objListCurrency)
																	foreach($objListCurrency as $currency){
																		if($count == 0 )
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
											</div>
											<div class="row" id="divContainerRowPersonalization">
												<?php echo getBehavio($company->type,"app_inventory_item","divTraslateElementTablePrecio"); ?>
											</div>
										</div>
										<div class="tab-pane fade" id="profile">
										
												<div class="form-group">
														<label class="col-lg-2 control-label" for="normal">Marca</label>
														<div class="col-lg-5">
															<input class="form-control"  type="text"  name="txtReference1" id="txtReference1" value="">												
														</div>
												</div>											
												<div class="form-group">
														<label class="col-lg-2 control-label" for="normal">Modelo</label>
														<div class="col-lg-5">
															<input class="form-control"  type="text"  name="txtReference2" id="txtReference2" value="">												
														</div>
												</div>
												<div class="form-group">
														<label class="col-lg-2 control-label" for="normal">Serie ó MAI</label>
														<div class="col-lg-5">
															<input class="form-control"  type="text"  name="txtReference3" id="txtReference3" value="">												
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
		                                            <label class="col-lg-2 control-label" for="normal">Descripcion</label>
		                                            <div class="col-lg-6">
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
																			<?php echo getBehavio($company->type,"comand_traducir",$ws->name); ?> 
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
																			<?php echo $ws->display; ?>
																		</td>
																		<td>
																			1
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