<div class="pv2 pinv pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-upload-6"></i></div>
			<div>
				<p class="pv2-header-title">Salida por transferencia #<span class="invoice-num"><?php echo $objTM->transactionNumber;?></span> <span class="pv2-chip<?php if($objTM->isApplied == 1) echo " is-applied"; ?>"><?php echo ($objTM->isApplied == 1) ? "Aplicado" : "Edición"; ?></span></p>
				<p class="pv2-header-sub">Revisa o modifica el envio de productos entre bodegas</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_inventory_transferoutput/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
			<a href="#" class="btn btn-default" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
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
				<li><a href="#referencias" data-toggle="tab">Referencias</a></li>
				<li><a href="#dropdown" data-toggle="tab">Comentarios</a></li>
				<li><a href="#dropdown-file" data-toggle="tab">Archivos</a></li>
			</ul>
			<!--tab menu-->

			<!--tab content-->
			<div class="tab-content">
				<!--tab content general-->
				<div class="tab-pane fade in active" id="home">
					<input type="hidden" name="txtCompanyID" value="<?php echo $objTM->companyID; ?>">
					<input type="hidden" name="txtTransactionID" value="<?php echo $objTM->transactionID; ?>">
					<input type="hidden" name="txtTransactionMasterID" value="<?php echo $objTM->transactionMasterID; ?>">

					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-4 form-group">
							<label class="pv2-label" for="txtTransactionOn">Fecha</label>
							<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
								<input size="16"  class="form-control" type="text" name="txtTransactionOn" id="txtTransactionOn" value="<?php echo $objTM->transactionOn;?>">
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
										if($ws->workflowStageID == $objTM->statusID)
										echo "<option value='".$ws->workflowStageID."' selected >".$ws->name."</option>";
										else
										echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
									}
									?>
							</select>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 form-group">
							<label class="pv2-label" for="txtTransactionCausalID">Causa</label>
							<select name="txtTransactionCausalID" id="txtTransactionCausalID" class="select2">
									<option></option>
									<?php
									if($objListTransactionCausal)
									foreach($objListTransactionCausal as $i){
										if($i->transactionCausalID == $objTM->transactionCausalID)
										echo "<option value='".$i->transactionCausalID."' selected >".$i->name."</option>";
										else
										echo "<option value='".$i->transactionCausalID."'>".$i->name."</option>";
									}
									?>
							</select>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 form-group">
							<label class="pv2-label" for="txtWarehouseSourceID">Bodega origen</label>
							<select name="txtWarehouseSourceID" id="txtWarehouseSourceID" class="select2">
									<option></option>
									<?php
									if($objListWarehouse)
									foreach($objListWarehouse as $i){
										if($i->warehouseID == $objTM->sourceWarehouseID)
										echo "<option value='".$i->warehouseID."' selected >".$i->name."</option>";
										else
										echo "<option value='".$i->warehouseID."'>".$i->name."</option>";
									}
									?>
							</select>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 form-group">
							<label class="pv2-label" for="txtWarehouseTargetID">Bodega destino</label>
							<select name="txtWarehouseTargetID" id="txtWarehouseTargetID" class="select2">
									<option></option>
									<?php
									if($objListWarehouseAll)
									foreach($objListWarehouseAll as $i){
										if($i->warehouseID == $objTM->targetWarehouseID)
										echo "<option value='".$i->warehouseID."' selected >".$i->name."</option>";
										else
										echo "<option value='".$i->warehouseID."'>".$i->name."</option>";
									}
									?>
							</select>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 form-group">
							<label class="pv2-label" for="txtUser">Usuario</label>
							<input class="form-control"  type="text"  name="txtUser" id="txtUser" readonly value="<?php echo $objNatural != NULL ? $objNatural->firstName . " " . $objNatural->lastName  : $objUser->nickname ;?>">
						</div>
						<div class="col-xs-12 col-sm-3 form-group">
							<label class="pinv-check" for="txtIsApplied">
								<input type="checkbox"   name="txtIsApplied" id="txtIsApplied" disabled value="1" <?php if($objTM->isApplied == 1) echo "checked"; ?> > Aplicado
							</label>
						</div>
					</div>
				</div>
				<!--tab content general-->
				<!--tab content description-->
				<div class="tab-pane fade" id="dropdown">
					<div class="form-group">
						<label class="pv2-label" for="txtDescription">Nota</label>
						<textarea class="form-control"  id="txtDescription" name="txtDescription" rows="4"><?php echo $objTM->note; ?></textarea>
					</div>
				</div>
				<!--tab content description-->
				<!--tab content file-->
				<div class="tab-pane fade" id="dropdown-file">
				</div>
				<!--tab content file-->
				<!--tab content referencias-->
				<div class="tab-pane fade" id="referencias">
					<div class="row">
						<div class="col-xs-12 col-sm-4 form-group">
							<label class="pv2-label">Referencia 1</label>
							<input class="form-control" readonly="true"  type="text"  name="txtReference1" id="txtReference1" value="<?php echo $objTM->reference1 ?>">
						</div>
						<div class="col-xs-12 col-sm-4 form-group">
							<label class="pv2-label" for="txtReference2">Referencia 2</label>
							<input class="form-control"  readonly="true" type="text"  name="txtReference2" id="txtReference2" value="<?php echo $objTM->reference2; ?>">
						</div>
						<div class="col-xs-12 col-sm-4 form-group">
							<label class="pv2-label" for="txtReference3">Referencia 3</label>
							<input class="form-control"   readonly="true" type="text"  name="txtReference3" id="txtReference3" value="<?php echo $objTM->reference3; ?>">
						</div>
					</div>
				</div>
				<!--tab content referencias-->
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
						<input type="hidden" id="txtEmployeeID"name="txtEmployeeID" value="<?php echo ($objEmployeeNaturalSource ? $objEmployeeNaturalSource->entityID : "0");  ?>">
						<input class="form-control" readonly id="txtEmployeeDescription" type="txtEmployeeDescription" value="<?php echo ($objEmployeeNaturalSource ? $objEmployeeNaturalSource->firstName." ".$objEmployeeNaturalSource->lastName : "");  ?>" >
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
						<input type="hidden" id="txtEmployeeIDTarget" name="txtEmployeeIDTarget" value="<?php echo ($objEmployeeNaturalTarget ? $objEmployeeNaturalTarget->entityID : "0");  ?>"  >
						<input class="form-control" readonly id="txtEmployeeDescriptionTarget" type="txtEmployeeDescriptionTarget" value="<?php echo ($objEmployeeNaturalTarget ? $objEmployeeNaturalTarget->firstName." ".$objEmployeeNaturalTarget->lastName : "");  ?>" >
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
					<label class="pv2-label"><?php echo getBehavio($company->type,"app_inventory_transferoutput","labelReference1",""); ?></label>
					<input class="form-control"  type="text" name="txtReference1" id="txtReference1" value="<?php echo $objTM->reference4; ?>">
				</div>
				<div class="col-xs-12 col-md-6 form-group">
					<label class="pv2-label">&nbsp;</label>
					<label class="pinv-check" for="chkIsTemplate">
						<input type="checkbox"   name="chkIsTemplate" id="chkIsTemplate"  <?php echo $objTM->isTemplate == 1 ? "checked" : ""; ?> > Es plantilla
					</label>
					<input type="hidden"   name="txtIsTemplate" id="txtIsTemplate" value="<?php echo $objTM->isTemplate == 1 ? "true" : "false"; ?>"  >
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
						<th></th>
						<th>itemID</th>
						<th>transactionDetailID</th>
						<th>Codigo</th>
						<th>Nombre</th>
						<th>U/M</th>
						<th>Cantidad</th>
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
				Este documento no tiene productos. Toca <b>Agregar producto</b> para empezar.
			</div>
		</div>
		<!-- detalle del movimiento-->

	</div>
	</form>
	<!-- formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Transferencia #<?php echo $objTM->transactionNumber;?></p>
			<p class="pv2-mbar-total">0 productos</p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
