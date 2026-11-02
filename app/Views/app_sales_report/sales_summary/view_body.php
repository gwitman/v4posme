<?php helper_getHtmlOfReportFilterOpen("Resumen de ventas", "📈", "Ventas"); ?>

											<div class="prf-field">
												<label class="pv2-label" for="txtStartOn">Fecha inicial</label>
												<input class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="2014-01-30" autocomplete="off">
											</div>

											<div class="prf-field">
												<label class="pv2-label" for="txtEndOn">Fecha final</label>
												<input class="form-control" type="text" name="txtEndOn" id="txtEndOn" value="2014-01-30" autocomplete="off">
											</div>

											<div class="prf-field">
												<label class="pv2-label" for="txtTax1">Ventas con IVA</label>
														<select name="txtTax1" id="txtTax1" class="select2">
																<option value="0">TODAS</option>
																<option value="-1">NO</option>
																<option value="1">SIN</option>
														</select>
											</div>

											<div class="prf-field">
												<label class="pv2-label" for="txtWarehouseID">Bodega</label>
														<select name="txtWarehouseID" id="txtWarehouseID" class="select2">
																<option value="0">TODAS</option>
																<?php
																if($objListWarehouse)
																foreach($objListWarehouse as $i){
																	echo "<option value='".$i->warehouseID."'>".$i->name."</option>";
																}
																?>
														</select>
											</div>

											<div class="prf-field">
												<label class="pv2-label" for="txtCustomerDescription">Cliente</label>
												<input type="hidden" id="txtCustomerID" name="txtCustomerID" value="0">
												<div class="prf-input-group">
													<input class="form-control" readonly id="txtCustomerDescription" type="text" value="TODOS" autocomplete="off">
													<button class="btn btn-primary" type="button" id="btnSearchCustomer" title="Buscar cliente">
														<i aria-hidden="true" class="i-search-5"></i>
														Buscar
													</button>
													<button class="btn btn-danger" type="button" id="btnClearCustomer" title="Limpiar cliente">
														<i aria-hidden="true" class="i-undo-2"></i>
														Limpiar
													</button>
												</div>
											</div>

<?php helper_getHtmlOfReportFilterClose(); ?>