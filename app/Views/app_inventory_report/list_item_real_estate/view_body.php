<?php helper_getHtmlOfReportFilterOpen("Detalle de inmuebles", "📦", "Inventario"); ?>

	<div class="prf-field">
		<label class="pv2-label" for="txtTypeDate">Filtrar por</label>
		<select name="txtTypeDate" id="txtTypeDate" class="select2">
				<option value="actualizacion" selected>Fecha de actualizacion</option>
				<option value="enlistamiento">Fecha de enlistamiento</option>
				<option value="ambas">Ambas</option>
		</select>
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtStartOn">Fecha inicial</label>
		<input class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtEndOn">Fecha final</label>
		<input class="form-control" type="text" name="txtEndOn" id="txtEndOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtNumberEncuentra24">Encuentra 24</label>
		<input class="form-control" type="text" name="txtNumberEncuentra24" id="txtNumberEncuentra24" value="" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtWorkflowStageID">Estado</label>
		<select name="txtWorkflowStageID" id="txtWorkflowStageID" class="select2">
				<option value="0">TODOS</option>
				<?php
				if($objListWorkflowStage)
				foreach($objListWorkflowStage as $i){

					if($i != null)
					echo "<option value='".$i->workflowStageID."'>".$i->name."</option>";
				}
				?>
		</select>
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtAgenteFilter">Colaborador (agente)</label>
		<select name="txtAgenteFilter" id="txtAgenteFilter" class="select2">
				<option value="0">TODOS</option>
				<?php
				if(isset($objListAgente) && $objListAgente)
				foreach($objListAgente as $i){
					if($i != null)
					echo "<option value='".$i->entityID."'>".$i->firstName." ".$i->lastName."</option>";
				}
				?>
		</select>
	</div>

	<div class="prf-field is-wide">
		<label class="pv2-label" for="txtNamePropietario">Propietario</label>
		<input class="form-control" type="text" name="txtNamePropietario" id="txtNamePropietario" value="" autocomplete="off">
	</div>

	<!-- Mostrar y bodega: ocultos, el script envia su valor -->
	<div class="prf-field hidden">
		<label class="pv2-label" for="txtShowActivos">Mostrar</label>
		<select name="txtShowActivos" id="txtShowActivos" class="select2">
				<option value="1" selected >Activos</option>
				<option value="0">Eliminados</option>
		</select>
	</div>

	<div class="prf-field hidden">
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

<?php helper_getHtmlOfReportFilterClose(); ?>
