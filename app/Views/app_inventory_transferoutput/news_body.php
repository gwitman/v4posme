<div class="pv2 pinv pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-upload-6"></i></div>
			<div>
				<p class="pv2-header-title">Salida por transferencia #<span class="invoice-num">00000000</span> <span class="pv2-chip">Nuevo</span></p>
				<p class="pv2-header-sub">Envia productos de una bodega a otra</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_inventory_transferoutput/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- formulario -->
	<form id="form-new-transaction" name="form-new-transaction" role="form" autocomplete="off">
	<div class="printArea">

		<!-- documento -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Documento</p>

			<!--tab menu-->
			<ul id="myTab" class="nav nav-tabs">
				<li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
				<li><a href="#dropdown" data-toggle="tab">Comentarios</a></li>
				<li><a href="#dropdown-file" data-toggle="tab">Archivos</a></li>
			</ul>
			<!--tab menu-->

			<!--tab content-->
			<div class="tab-content">
				<!--tab content general-->
				<div class="tab-pane fade in active" id="home">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-4 form-group">
							<label class="pv2-label" for="txtTransactionOn">Fecha</label>
							<div id="datepicker" class="input-group date"  data-date-format="yyyy-mm-dd">
								<input size="16"  class="form-control" type="text" name="txtTransactionOn" id="txtTransactionOn" >
								<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 form-group">
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
						<div class="col-xs-12 col-sm-6 col-md-4 form-group">
							<label class="pv2-label" for="txtTransactionCausalID">Causa</label>
							<select name="txtTransactionCausalID" id="txtTransactionCausalID" class="select2">
									<option></option>
									<?php
									$ix = 0;
									if($objListTransactionCausal)
									foreach($objListTransactionCausal as $i){
										if($ix == 0)
										echo "<option value='".$i->transactionCausalID."' selected >".$i->name."</option>";
										else
										echo "<option value='".$i->transactionCausalID."'>".$i->name."</option>";

										$ix++;
									}
									?>
							</select>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 form-group">
							<label class="pv2-label" for="txtWarehouseSourceID">Bodega origen</label>
							<select name="txtWarehouseSourceID" id="txtWarehouseSourceID" class="select2">
									<option selected ></option>
									<?php
									$count = 1;
									if($objListWarehouse)
									foreach($objListWarehouse as $i){
										if($count == 1)
										echo "<option value='".$i->warehouseID."'>".$i->name."</option>";
										else
										echo "<option value='".$i->warehouseID."' >".$i->name."</option>";
										$count++;
									}
									?>
							</select>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 form-group">
							<label class="pv2-label" for="txtWarehouseTargetID">Bodega destino</label>
							<select name="txtWarehouseTargetID" id="txtWarehouseTargetID" class="select2">
									<option selected ></option>
									<?php
									$count = 1;
									if($objListWarehouseAll)
									foreach($objListWarehouseAll as $i){
										if($count == 1)
										echo "<option value='".$i->warehouseID."' >".$i->name."</option>";
										else
										echo "<option value='".$i->warehouseID."' >".$i->name."</option>";
										$count++;
									}
									?>
							</select>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 form-group">
							<label class="pv2-label" for="txtUser">Usuario</label>
							<input class="form-control"  type="text"  name="txtUser" id="txtUser" readonly value="">
						</div>
						<div class="col-xs-12 col-sm-3 form-group">
							<label class="pinv-check" for="txtIsApplied">
								<input type="checkbox"   name="txtIsApplied" id="txtIsApplied" disabled value="1" > Aplicado
							</label>
						</div>
					</div>
				</div>
				<!--tab content general-->
				<!--tab content description-->
				<div class="tab-pane fade" id="dropdown">
					<div class="form-group">
						<label class="pv2-label" for="txtDescription">Nota</label>
						<textarea class="form-control"  id="txtDescription" name="txtDescription" rows="4"></textarea>
					</div>
				</div>
				<!--tab content description-->
				<!--tab content file-->
				<div class="tab-pane fade" id="dropdown-file">
					<p class="pv2-help">Podras adjuntar archivos despues de guardar.</p>
				</div>
				<!--tab content file-->
			</div>
			<!--tab content-->
		</div>
		<!-- /documento -->

		<!-- transferencia -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-users"></i></span> Transferencia</p>
			<div class="row">
				<div class="col-xs-12 col-md-6 form-group">
					<label class="pv2-label" for="txtEmployeeDescription">Envia</label>
					<div class="input-group pinv-search">
						<input type="hidden" id="txtEmployeeID"name="txtEmployeeID">
						<input class="form-control" readonly id="txtEmployeeDescription" type="txtEmployeeDescription">
						<span class="input-group-btn">
							<button class="btn btn-danger" type="button" id="btnClearEmployeeParent" title="Quitar">
								<i aria-hidden="true" class="i-undo-2"></i>
								<span class="pinv-btn-text">Quitar</span>
							</button>
						</span>
						<span class="input-group-btn">
							<button class="btn btn-primary" type="button" id="btnSearchEmployeeParent" title="Buscar">
								<i aria-hidden="true" class="i-search-5"></i>
								<span class="pinv-btn-text">Buscar</span>
							</button>
						</span>
					</div>
				</div>
				<div class="col-xs-12 col-md-6 form-group">
					<label class="pv2-label" for="txtEmployeeDescriptionTarget">Recibe</label>
					<div class="input-group pinv-search">
						<input type="hidden" id="txtEmployeeIDTarget" name="txtEmployeeIDTarget">
						<input class="form-control" readonly id="txtEmployeeDescriptionTarget" type="txtEmployeeDescriptionTarget">
						<span class="input-group-btn">
							<button class="btn btn-danger" type="button" id="btnClearEmployeeParentTarget" title="Quitar">
								<i aria-hidden="true" class="i-undo-2"></i>
								<span class="pinv-btn-text">Quitar</span>
							</button>
						</span>
						<span class="input-group-btn">
							<button class="btn btn-primary" type="button" id="btnSearchEmployeeParentTarget" title="Buscar">
								<i aria-hidden="true" class="i-search-5"></i>
								<span class="pinv-btn-text">Buscar</span>
							</button>
						</span>
					</div>
				</div>
				<div class="col-xs-12 col-md-6 form-group">
					<label class="pv2-label" for="labelTransactionNumberTemplate">Dar salida en base a una plantilla</label>
					<div class="input-group pinv-search">
						<input type="hidden" id="txtTransactionNumberTemplate" name="txtTransactionNumberTemplate">
						<input class="form-control" readonly id="labelTransactionNumberTemplate" type="labelTransactionNumberTemplate">
						<span class="input-group-btn">
							<button class="btn btn-danger" type="button" id="btnClearTransactionNumberTemplate" title="Quitar">
								<i aria-hidden="true" class="i-undo-2"></i>
								<span class="pinv-btn-text">Quitar</span>
							</button>
						</span>
						<span class="input-group-btn">
							<button class="btn btn-primary" type="button" id="btnSearchTransactionNumberTemplate" title="Buscar">
								<i aria-hidden="true" class="i-search-5"></i>
								<span class="pinv-btn-text">Buscar</span>
							</button>
						</span>
					</div>
				</div>
				<div class="col-xs-12 col-md-6 form-group">
					<label class="pv2-label" for="txtReference1"><?php echo getBehavio($company->type,"app_inventory_transferoutput","labelReference1",""); ?></label>
					<input class="form-control"  type="text" name="txtReference1" id="txtReference1" value="">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pinv-check" for="chkIsTemplate">
						<input type="checkbox"   name="chkIsTemplate" id="chkIsTemplate"  > Es plantilla
					</label>
					<input type="hidden"   name="txtIsTemplate" id="txtIsTemplate" value="false"  >
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pinv-check" for="chkIsTrnasferAll">
						<input type="checkbox"   name="chkIsTrnasferAll" id="chkIsTrnasferAll"  > Dar salida a lo existente en la bodega
					</label>
					<input type="hidden"   name="txtIsTrnasferAll" id="txtIsTrnasferAll" value="false"  >
				</div>
			</div>
		</div>
		<!-- /transferencia -->

		<!-- detalle del movimiento-->
		<div class="pv2-card">
			<div class="pv2-detail-head">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Detalle <span class="pinv-count">0</span></p>
				<div class="pv2-actions">
					<a href="#" class="btn btn-default btn-sm" id="btnDeleteDetailTransaction" ><i class="icon16 i-remove"></i> Quitar marcados</a>
					<a href="#" class="btn btn-primary btn-sm" id="btnNewDetailTransaction" ><i class="icon16 i-plus"></i> Agregar producto</a>
				</div>
			</div>
			<div class="pinv-table-wrap">
				<table id="tb_transaction_master_detail" class="table table-bordered">
					<thead>
					  <tr>
						<th></th><!--0-->
						<th>itemID</th><!--1-->
						<th>transactionDetailID</th><!--2-->
						<th>Codigo</th><!--3-->
						<th>Nombre</th><!--4-->
						<th>U/M</th><!--5-->
						<th>Cantidad</th><!--6-->
						<th>Lote</th><!--7-->
						<th>Expiracion</th><!--8-->
						<th>Mas</th><!--9-->
					  </tr>
					</thead>
					<tbody id="body_detail_transaction">
					</tbody>
				</table>
			</div>
			<div class="pinv-empty">
				<i class="icon20 i-file-plus"></i>
				Selecciona la bodega origen y toca <b>Agregar producto</b> para empezar.
			</div>
		</div>
		<!-- detalle del movimiento-->

	</div>
	</form>
	<!-- formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Salida por transferencia</p>
			<p class="pv2-mbar-total">0 productos</p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
