<div class="pv2 pinv pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-cube-3"></i></div>
			<div>
				<p class="pv2-header-title">Receta #<span class="invoice-num"><?php echo $objItemDataSheet->itemDataSheetID; ?></span> <span class="pv2-chip">Edición</span></p>
				<p class="pv2-header-sub">Revisa o modifica los insumos de la receta</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_inventory_datasheet/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- formulario -->
	<form id="form-new-account-journal" name="form-new-account-journal" role="form" autocomplete="off">
	<div class="printArea">

		<div class="pv2-card">
			<!--tab menu-->
			<ul id="myTab" class="nav nav-tabs">
				<li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
				<li><a href="#profile" data-toggle="tab">Detalle</a></li>
			</ul>
			<!--tab menu-->

			<!--tab content-->
			<div class="tab-content">
				<!--tab content general-->
				<div class="tab-pane fade in active" id="home">
					<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Receta</p>
					<div class="row">
						<div class="col-xs-12 col-md-6 form-group">
							<label class="pv2-label" for="txtItemIDDescription">Producto</label>
							<div class="input-group">
								<input type="hidden" id="txtItemDataSheetID" name="txtItemDataSheetID" value="<?php echo $objItemDataSheet->itemDataSheetID;  ?>">
								<input type="hidden" id="txtItemID" name="txtItemID" value="<?php echo $objItemDataSheet->itemID;  ?>">
								<input class="form-control" readonly id="txtItemIDDescription" type="txtItemIDDescription" value="<?php echo $objItem->itemNumber." ". $objItem->name;  ?>">

								<span class="input-group-btn">
									<button class="btn btn-danger" type="button" id="txtClearItemID">
										<i aria-hidden="true" class="i-undo-2"></i>
										Quitar
									</button>
								</span>
								<span class="input-group-btn">
									<button class="btn btn-primary" type="button" id="txtSearchItemID">
										<i aria-hidden="true" class="i-search-5"></i>
										Buscar
									</button>
								</span>
							</div>
						</div>
						<div class="col-xs-12 col-md-6 form-group">
							<label class="pv2-label" for="txtName">Nombre</label>
							<input class="form-control"  type="text"  name="txtName" id="txtName" value="<?php echo $objItemDataSheet->name;  ?>">
						</div>
						<div class="col-xs-12 col-sm-4 form-group">
							<label class="pv2-label" for="txtStatusID">Estado</label>
							<select name="txtStatusID" id="txtStatusID" class="select2">
									<option></option>
									<?php
									if($objListWorkflowStage)
									foreach($objListWorkflowStage as $ws){
										if ($objItemDataSheet->statusID == $ws->workflowStageID )
											echo "<option value='".$ws->workflowStageID."' selected >".$ws->name."</option>";
										else
											echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
									}
									?>
							</select>
						</div>
						<div class="col-xs-6 col-sm-4 form-group">
							<label class="pv2-label" for="txtVersion">Version</label>
							<input class="form-control" readonly  type="text"  name="txtVersion" id="txtVersion" value="<?php echo $objItemDataSheet->version;  ?>">
						</div>
						<div class="col-xs-6 col-sm-4 form-group">
							<label class="pv2-label" for="txtCostGeneral">Costo</label>
							<input class="form-control" readonly  type="text"  name="txtCostGeneral" id="txtCostGeneral" value="<?php echo $costGeneral;  ?>">
						</div>
						<div class="col-xs-12 form-group">
							<label class="pv2-label" for="txtDescription">Descripcion</label>
							<textarea class="form-control"  id="txtDescription" name="txtDescription" rows="4" ><?php echo $objItemDataSheet->description;  ?></textarea>
						</div>
					</div>
				</div>
				<!--tab content general-->
				<!--tab content detalle-->
				<div class="tab-pane fade" id="profile">
					<div class="pv2-detail-head">
						<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Insumos <span class="pinv-count">0</span></p>
						<div class="pv2-actions">
							<a href="#" class="btn btn-default btn-sm" id="btnDeleteDetail" ><i class="icon16 i-remove"></i> Quitar marcados</a>
							<a href="#" class="btn btn-primary btn-sm" id="btnNewDetail" ><i class="icon16 i-plus"></i> Agregar insumo</a>
						</div>
					</div>
					<!-- detalle del movimiento-->
					<div class="pinv-table-wrap">
						<table id="tb_item_data_sheet_detail" class="table table-bordered">
							<thead>
							  <tr>
								<th></th>
								<th>itemDataSheetID</th>
								<th>itemID</th>
								<th>itemDataSheetDetailID</th>
								<th>itemRelatedID</th>
								<th>Codigo</th>
								<th>Nombre</th>
								<th>U/M</th>
								<th>Cantidad</th>
								<th>Costo</th>
								<th>CC</th>
							  </tr>
							</thead>
							<tbody id="tb_item_data_sheet_detail_body">
							</tbody>
						</table>
					</div>
					<div class="pinv-empty">
						<i class="icon20 i-file-plus"></i>
						Esta receta no tiene insumos. Toca <b>Agregar insumo</b> para empezar.
					</div>
					<!-- detalle del movimiento-->
				</div>
				<!--tab content detalle-->
			</div>
			<!--tab content-->
		</div>

	</div>
	</form>
	<!-- /formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Receta #<?php echo $objItemDataSheet->itemDataSheetID; ?></p>
			<p class="pv2-mbar-total">0 insumos</p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
