<div class="pv2 pinv pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-arrow-left-9"></i></div>
			<div>
				<p class="pv2-header-title">Otra entrada a inventario #<span class="invoice-num">00000000</span> <span class="pv2-chip">Nuevo</span></p>
				<p class="pv2-header-sub">Registra la entrada de productos a una bodega</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_inventory_otherinput/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
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
							<label class="pv2-label" for="txtWarehouseSourceID">Bodega</label>
							<select name="txtWarehouseSourceID" id="txtWarehouseSourceID" class="select2">
									<option></option>
									<?php
									if($objListWarehouse)
									foreach($objListWarehouse as $i){
										if($i->number == $warehouseDefault)
											echo "<option value='".$i->warehouseID."' selected>".$i->name."</option>";
										else
											echo "<option value='".$i->warehouseID."'>".$i->name."</option>";
									}
									?>
							</select>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-3 form-group">
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
						<th>Costo</th><!--7-->
						<th>Total</th><!--8-->
						<th>Lote</th><!--9-->
						<th>Expiracion</th><!--10-->
						<th>Mas</th><!--11-->
					  </tr>
					</thead>
					<tbody id="body_detail_transaction">
					</tbody>
				</table>
			</div>
			<div class="pinv-empty">
				<i class="icon20 i-file-plus"></i>
				Selecciona la bodega y toca <b>Agregar producto</b> para empezar.
			</div>
		</div>
		<!-- detalle del movimiento-->

		<!-- resumen -->
		<div class="pv2-card pinv-summary" id="homeSummary">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-4 col-sm-offset-6 col-md-offset-8">
					<div class="pv2-total">
						<label class="pv2-label" for="txtTotal">Total</label>
						<input class="form-control txt-numeric"  type="text"  name="txtTotal" id="txtTotal" readonly value="">
					</div>
				</div>
			</div>
		</div>
		<!-- /resumen -->

	</div>
	</form>
	<!-- formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Total otra entrada</p>
			<p class="pv2-mbar-total">0.00</p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
