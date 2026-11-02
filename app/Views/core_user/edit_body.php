<div class="pv2 pinv pinv-form pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-users"></i></div>
			<div>
				<p class="pv2-header-title">Usuario #<?php echo $objUser->userID; ?> <span class="pv2-chip">Editando</span></p>
				<p class="pv2-header-sub"><?php echo htmlentities($objUser->nickname, ENT_QUOTES); ?></p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/core_user/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<ul id="myTab" class="nav nav-tabs">
		<li class="active"><a href="#home1" data-toggle="tab">Informacion General</a></li>
		<li><a href="#profile1" data-toggle="tab">Bodegas</a></li>
		<li><a href="#box1" data-toggle="tab">Cajas</a></li>
		<li><a href="#profile2" data-toggle="tab">Notificaciones</a></li>
	</ul>

	<!-- formulario -->
	<form id="form-new-rol" name="form-new-rol" role="form" autocomplete="off">
	<div class="tab-content">

		<!--Datos Principales-->
		<div class="tab-pane fade in active" id="home1">

			<input type="hidden" name="companyID" id="companyID" value="<?php echo $objUser->companyID;?>" />
			<input type="hidden" name="branchID" id="branchID" value="<?php echo $objUser->branchID;?>" />
			<input type="hidden" name="userID" id="userID" value="<?php echo $objUser->userID;?>" />
			<input type="hidden" name="txtBranchID" id="txtBranchID" value="<?php echo $objUser->locationID;?>" />

			<div class="pv2-card">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-key"></i></span> Datos de acceso</p>
				<div class="row">
					<div class="col-xs-12 col-sm-4 form-group">
						<label class="pv2-label" for="txtNickname">Nickname</label>
						<input class="form-control" type="text" name="txtNickname" id="txtNickname" value="<?php echo $objUser->nickname;?>" autocomplete="off">
					</div>
					<div class="col-xs-12 col-sm-4 form-group">
						<label class="pv2-label" for="txtPassword">Password</label>
						<input class="form-control" type="password" name="txtPassword" id="txtPassword" value="<?php echo $objUser->password;?>" autocomplete="new-password">
					</div>
					<div class="col-xs-12 col-sm-4 form-group">
						<label class="pv2-label" for="txtEmail">Email</label>
						<input class="form-control" type="text" name="txtEmail" id="txtEmail" value="<?php echo $objUser->email;?>" autocomplete="off">
					</div>
				</div>
			</div>

			<div class="pv2-card">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-office"></i></span> Rol y ubicacion</p>
				<div class="row">
					<div class="col-xs-12 col-sm-4 form-group">
						<label class="pv2-label" for="txtRoleID">Rol</label>
						<select name="txtRoleID" id="txtRoleID" class="select2">
								<option></option>
								<?php
								foreach($objListRoles as $i){
									if($i->roleID == $objMembership->roleID)
									echo "<option value='".$i->roleID."' selected='selected'>".$i->name."</option>";
									else
									echo "<option value='".$i->roleID."'>".$i->name."</option>";
								}
								?>
						</select>
					</div>
					<div class="col-xs-12 col-sm-4 form-group">
						<label class="pv2-label" for="txtBranchIDDesctiption">Sucursal</label>
						<select name="txtBranchIDDesctiption" id="txtBranchIDDesctiption" readonly="true" class="select2">
								<option></option>
								<?php
								foreach($objBranch as $i){
									if($i->branchID == $objUser->locationID)
									echo "<option value='".$i->branchID."' selected='selected'>".$i->name."</option>";
									else
									echo "<option value='".$i->branchID."'>".$i->name."</option>";
								}
								?>
						</select>
					</div>
					<div class="col-xs-12 col-sm-4 form-group">
						<label class="pv2-label" for="txtCashBoxID">Caja</label>
						<select name="txtCashBoxID" id="txtCashBoxID" class="select2">
								<option></option>
								<?php
								foreach($objListCash as $i)
								{
									if($cashBoxID == $i->cashBoxID)
									{
										echo "<option value='".$i->cashBoxID."' selected >".$i->name."</option>";
									}
									else {
										echo "<option value='".$i->cashBoxID."'>".$i->name."</option>";
									}
								}
								?>
						</select>
					</div>
					<div class="col-xs-12 col-sm-8 form-group">
						<label class="pv2-label" for="txtEmployeeDescription">Entidad</label>
						<div class="input-group">
							<input type="hidden" id="txtEmployeeID" name="txtEmployeeID" value="<?php echo $objUser->employeeID; ?>">
							<input class="form-control" readonly id="txtEmployeeDescription" type="text" value="<?php echo $entityNumber == NULL  ?  ""  : ( $entityNumber." / ".$objNatural->firstName." ".$objNatural->lastName); ?>">
							<span class="input-group-btn">
								<button class="btn btn-default" type="button" id="btnClearEmployeeParent" title="Limpiar"><i aria-hidden="true" class="i-undo-2"></i></button>
								<button class="btn btn-primary" type="button" id="btnSearchEmployeeParent" title="Buscar"><i aria-hidden="true" class="i-search-5"></i> Buscar</button>
							</span>
						</div>
					</div>
					<div class="col-xs-12 col-sm-4 form-group">
						<label class="pv2-label">&nbsp;</label>
						<label class="pinv-check" for="txtIsMobile">
							<input type="checkbox" name="txtIsMobile" id="txtIsMobile" value="1" <?php echo ($objUser->useMobile == 1) ? "checked":""; ?> > Usa telefono (Mobile)
						</label>
					</div>
				</div>
			</div>

		</div>

		<!--Notificaciones-->
		<div class="tab-pane fade pv2-card" id="profile2">
			<div class="pv2-detail-head">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-bell"></i></span> Notificaciones del usuario</p>
				<div class="pv2-actions">
					<a href="#" class="btn btn-default btn-sm" id="btnDeleteDetailNotification" ><i class="icon16 i-remove"></i> Quitar seleccionada</a>
					<a href="#" class="btn btn-primary btn-sm" id="btnNewDetailNotification" ><i class="icon16 i-plus"></i> Agregar</a>
				</div>
			</div>
			<p class="pv2-help">Toca una fila para seleccionarla.</p>
			<table id="ListElementNotification" class="table table-striped">
				<thead>
					<tr>
						<th>Lista de Notificaciones</th>
					</tr>
				</thead>
				<tbody id="tbody_detail_notification">
					<?php
					if($objListNotification)
					foreach($objListNotification as $i){
						echo "<tr>";
							echo "<td>";
									echo "<span><input type='hidden' name='txtDetailTagID[]' value='".$i->tagID."' />".$i->name."</span>";
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
			</table>
		</div>

		<!--Bodegas-->
		<div class="tab-pane fade pv2-card" id="profile1">
			<div class="pv2-detail-head">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-office"></i></span> Bodegas del usuario</p>
				<div class="pv2-actions">
					<a href="#" class="btn btn-default btn-sm" id="btnDeleteDetailWarehouse" ><i class="icon16 i-remove"></i> Quitar seleccionada</a>
					<a href="#" class="btn btn-primary btn-sm" id="btnNewDetailWarehouse" ><i class="icon16 i-plus"></i> Agregar</a>
				</div>
			</div>
			<p class="pv2-help">Toca una fila para seleccionarla.</p>
			<table id="ListElementWarehouse" class="table table-striped">
				<thead>
					<tr>
						<th>Lista de Bodegas</th>
					</tr>
				</thead>
				<tbody id="tbody_detail_warehouse">
					<?php
					if($objListWarehouse)
					foreach($objListWarehouse as $i){
						echo "<tr>";
							echo "<td>";
									echo "<span><input type='hidden' name='txtDetailWarehouseID[]' value='".$i->warehouseID."' />".$i->number." ".$i->name."</span>";
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
			</table>
		</div>

		<!--Cajas-->
		<div class="tab-pane fade pv2-card" id="box1">
			<div class="pv2-detail-head">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-coin"></i></span> Cajas del usuario</p>
				<div class="pv2-actions">
					<a href="#" class="btn btn-default btn-sm" id="btnDeleteDetailBox" ><i class="icon16 i-remove"></i> Quitar seleccionada</a>
					<a href="#" class="btn btn-primary btn-sm" id="btnNewDetailBox" ><i class="icon16 i-plus"></i> Agregar</a>
				</div>
			</div>
			<p class="pv2-help">Toca una fila para seleccionarla.</p>
			<table id="ListElementBox" class="table table-striped">
				<thead>
					<tr>
						<th>Lista de Cajas</th>
					</tr>
				</thead>
				<tbody id="tbody_detail_box">
					<?php
					if($objListBox)
					foreach($objListBox as $i){
						echo "<tr>";
							echo "<td>";
									echo "<span><input type='hidden' name='txtDetailCashBoxID[]' value='".$i->cashBoxID."' />".$i->cashBoxCode." ".$i->name."</span>";
							echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
			</table>
		</div>

	</div>
	</form>
	<!-- /formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Usuario</p>
			<p class="pv2-mbar-total"><?php echo htmlentities($objUser->nickname, ENT_QUOTES); ?></p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
