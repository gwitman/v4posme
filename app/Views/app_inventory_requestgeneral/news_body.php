<div class="pv2 pinv pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-cloud"></i></div>
			<div>
				<p class="pv2-header-title">Solicitud general #<span class="invoice-num">00000000</span> <span class="pv2-chip">Nuevo</span></p>
				<p class="pv2-header-sub">Solicita productos a una bodega</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_inventory_requestgeneral/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
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
				<li><a href="#dropdown" data-toggle="tab">Comentario</a></li>
				<li><a href="#dropdown-file" data-toggle="tab">Archivos</a></li>
			</ul>
			<!--tab menu-->

			<!--tab content-->
			<div class="tab-content">
				<!--tab content general-->
				<div class="tab-pane fade in active" id="home">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-3 form-group">
							<label class="pv2-label" for="txtTransactionOn">Fecha</label>
							<div id="datepicker" class="input-group date"  data-date-format="yyyy-mm-dd">
								<input size="16"  class="form-control" type="text" name="txtTransactionOn" id="txtTransactionOn" >
								<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-3 form-group">
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
						<div class="col-xs-12 col-sm-6 col-md-3 form-group">
							<label class="pv2-label" for="txtWarehouseSourceID">Solicita a</label>
							<select name="txtWarehouseSourceID" id="txtWarehouseSourceID" class="select2">
									<option></option>
									<?php
									if($objListWarehouseAll)
									foreach($objListWarehouseAll as $i){
										echo "<option value='".$i->warehouseID."'>".$i->name."</option>";
									}
									?>
							</select>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-3 form-group">
							<label class="pv2-label" for="txtUser">Usuario</label>
							<input class="form-control"  type="text"  name="txtUser" id="txtUser" readonly value="<?php echo $objNatural != NULL ? $objNatural->firstName . " " . $objNatural->lastName  : $objUser->nickname ;?>">
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

		<!-- solicitante -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Solicitante</p>
			<div class="row">
				<div class="col-xs-12 col-md-6 form-group">
					<label class="pv2-label" for="txtEntityDescription">Solicitado por</label>
					<div class="input-group pinv-search">
						<input type="hidden" id="txtEntityID" name="txtEntityID" value="0">
						<input class="form-control" readonly id="txtEntityDescription" type="text" value="">
						<span class="input-group-btn">
							<button class="btn btn-danger" type="button" id="btnClearEntity" title="Quitar">
								<i aria-hidden="true" class="i-undo-2"></i>
								<span class="pinv-btn-text">Quitar</span>
							</button>
						</span>
						<span class="input-group-btn">
							<button class="btn btn-primary" type="button" id="btnSearchEntity" title="Buscar">
								<i aria-hidden="true" class="i-search-5"></i>
								<span class="pinv-btn-text">Buscar</span>
							</button>
						</span>
					</div>
				</div>
			</div>
		</div>
		<!-- /solicitante -->

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
					  </tr>
					</thead>
					<tbody id="body_detail_transaction">
					</tbody>
				</table>
			</div>
			<div class="pinv-empty">
				<i class="icon20 i-file-plus"></i>
				Selecciona a quien solicitas y toca <b>Agregar producto</b> para empezar.
			</div>
		</div>
		<!-- detalle del movimiento-->

	</div>
	</form>
	<!-- formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Solicitud general</p>
			<p class="pv2-mbar-total">0 productos</p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
