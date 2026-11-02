<div class="pv2 pinv pinv-form pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-storage-2"></i></div>
			<div>
				<p class="pv2-header-title">Catálogo #<span class="invoice-num"><?php echo $objPublicCatalog->publicCatalogID; ?></span> <span class="pv2-chip">Editando</span></p>
				<p class="pv2-header-sub"><?php echo htmlentities($objPublicCatalog->name, ENT_QUOTES); ?></p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_public_catalog/add" class="btn btn-default" id="btnNuevo"><i class="icon16 i-plus"></i> Nuevo</a>
			<a href="<?php echo base_url(); ?>/app_public_catalog/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
			<a href="#" class="btn btn-default" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- formulario -->
	<form id="form-new-invoice" name="form-new-invoice" role="form" autocomplete="off">
	<div class="printArea">

		<!-- datos del catalogo -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Datos del catálogo</p>

			<!--tab menu-->
			<ul id="myTab" class="nav nav-tabs">
				<li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
				<li><a href="#profile" data-toggle="tab">Referencias</a></li>
				<li><a id="btnClickArchivo" href="#dropdown-file" data-toggle="tab">Archivos</a></li>
			</ul>
			<!--tab menu-->

			<div class="tab-content">
				<!--tab content general-->
				<div class="tab-pane fade in active" id="home">
					<input type="hidden" name="txtPublicCatalogID" value="<?php echo $objPublicCatalog->publicCatalogID; ?>">
					<div class="row">
						<div class="col-xs-12 col-sm-4 form-group">
							<label class="pv2-label" for="txtName">Nombre</label>
							<input class="form-control" type="text" name="txtName" id="txtName" value="<?php echo $objPublicCatalog->name; ?>" autocomplete="off">
						</div>
						<div class="col-xs-12 col-sm-4 form-group">
							<label class="pv2-label" for="txtSystemName">Sistema</label>
							<input class="form-control" type="text" name="txtSystemName" id="txtSystemName" value="<?php echo $objPublicCatalog->systemName; ?>" autocomplete="off">
						</div>
						<div class="col-xs-12 col-sm-4 form-group">
							<label class="pv2-label" for="txtStatusID">Estado</label>
							<select name="txtStatusID" id="txtStatusID" class="select2">
								<option></option>
								<?php
								if ($objListWorkflowStage)
									foreach ($objListWorkflowStage as $ws) {

										if ($ws->workflowStageID == $objPublicCatalog->statusID)
											echo "<option value='" . $ws->workflowStageID . "' selected>" . $ws->name . "</option>";
										else
											echo "<option value='" . $ws->workflowStageID . "' >" . $ws->name . "</option>";
									}
								?>
							</select>
						</div>
					</div>
				</div>
				<!--tab content general-->
				<!--tab content referencias-->
				<div class="tab-pane fade" id="profile">
					<div class="row">
						<div class="col-xs-12 col-sm-4 form-group">
							<label class="pv2-label" for="txtFileImport">Archivo</label>
							<input class="form-control" type="text" name="txtFileImport" id="txtFileImport" value=".csv" autocomplete="off">
						</div>
						<div class="col-xs-12 col-sm-4 form-group">
							<label class="pv2-label">&nbsp;</label>
							<label class="pinv-check" for="txtIsTemplate">
								<input type="checkbox" name="txtIsTemplate" id="txtIsTemplate" value="1"> Generar plantilla
							</label>
						</div>
						<div class="col-xs-12 col-sm-4 form-group">
							<label class="pv2-label">&nbsp;</label>
							<label class="pinv-check" for="txtIsTemplate2">
								<input type="checkbox" name="txtIsTemplate2" id="txtIsTemplate2" value="1"> Procesar plantilla
							</label>
						</div>
					</div>
				</div>
				<!--tab content referencias-->
				<!--tab content file-->
				<div class="tab-pane fade" id="dropdown-file">
				</div>
				<!--tab content file-->
			</div>
		</div>
		<!-- /datos del catalogo -->

		<!-- elementos del catalogo -->
		<div class="pv2-card">
			<div class="pv2-detail-head">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Elementos <span class="pinv-count">0</span></p>
				<div class="pv2-actions">
					<a href="#" class="btn btn-default btn-sm" id="btnDeleteShare"><i class="icon16 i-remove"></i> Quitar marcados</a>
					<a href="#" class="btn btn-primary btn-sm" id="btnNewShare"><i class="icon16 i-plus"></i> Agregar</a>
				</div>
			</div>
			<div class="pinv-table-wrap">
							<table id="tb_transaction_master_detail" class="table table-bordered">
								<thead>
									<tr>
									<th data-label=""></th>
									<th width="23%">Configuración 1</th>
									<th width="23%">Configuración 2</th>
									<th width="23%">Configuración 3</th>
									<th width="23%">Configuración 4</th>
									</tr>
								</thead>
								<tbody id="body_tb_transaction_master_detail">
									<?php
									if ($objPublicCatalogDetail)
										foreach ($objPublicCatalogDetail as $key => $value) {
									?>
										<tr class="row_razon">
											<td>
												<input type="checkbox" class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />

												<input type="hidden" name="txtPublicCatalogDetail_publicCatalogID[]" id="txtPublicCatalogDetail_publicCatalogID" value="<?php echo $value->publicCatalogID; ?>" />
												<input type="hidden" name="txtPublicCatalogDetail_publicCatalogDetailID[]" id="txtPublicCatalogDetail_publicCatalogDetailID" value="<?php echo $value->publicCatalogDetailID; ?>" />
												<input type="hidden" name="txtPublicCatalogDetail_parentPublicCatalogDetailID[]" id="txtPublicCatalogDetail_parentPublicCatalogDetailID" value="<?php echo $value->parentCatalogDetailID; ?>" />
											</td>


											<td>

												<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Indicadores</label>
													<div class="col-lg-8">
														<input class="form-control txtPublicCatalogDetail_Name" type="text" name="txtPublicCatalogDetail_Name[]" id="txtPublicCatalogDetail_Name" value="<?php echo $value->name; ?>" autocomplete="off">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Valores</label>
													<div class="col-lg-8">
														<input class="form-control txtPublicCatalogDetail_Display" type="text" name="txtPublicCatalogDetail_Display[]" id="txtPublicCatalogDetail_Display" value="<?php echo $value->display; ?>" autocomplete="off">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Consjunto</label>
													<div class="col-lg-8">
														<input class="form-control txtPublicCatalogDetail_Reference4" type="text" name="txtPublicCatalogDetail_Reference4[]" id="txtPublicCatalogDetail_Reference4" value="<?php echo $value->reference4; ?>" autocomplete="off">
													</div>
												</div>

											</td>

											<td>

												<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Grupo</label>
													<div class="col-lg-8">
														<input class="form-control txtPublicCatalogDetail_Description" type="text" name="txtPublicCatalogDetail_Description[]" id="txtPublicCatalogDetail_Description" value="<?php echo $value->description; ?>" autocomplete="off">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Sub grupo</label>
													<div class="col-lg-8">
														<input class="form-control txtPublicCatalogDetail_Reference1" type="text" name="txtPublicCatalogDetail_Reference1[]" id="txtPublicCatalogDetail_Reference1" value="<?php echo $value->reference1; ?>" autocomplete="off">
													</div>
												</div>




											</td>

											<td>

												<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Edad</label>
													<div class="col-lg-8">
														<input class="form-control txtPublicCatalogDetail_Reference3" type="text" name="txtPublicCatalogDetail_Reference3[]" id="txtPublicCatalogDetail_Reference3" value="<?php echo $value->reference3; ?>" autocomplete="off">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Sexo</label>
													<div class="col-lg-8">
														<input class="form-control txtPublicCatalogDetail_Reference2" type="text" name="txtPublicCatalogDetail_Reference2[]" id="txtPublicCatalogDetail_Reference2" value="<?php echo $value->reference2; ?>" autocomplete="off">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Parent</label>
													<div class="col-lg-8">
														<input class="form-control txtPublicCatalogDetail_ParentName" type="text" name="txtPublicCatalogDetail_ParentName[]" id="txtPublicCatalogDetail_ParentName" value="<?php echo $value->parentName; ?>" autocomplete="off">
													</div>
												</div>

											</td>

											<td>

												<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Sequencia</label>
													<div class="col-lg-8">
														<input class="form-control txtPublicCatalogDetail_Sequence" type="text" name="txtPublicCatalogDetail_Sequence[]" id="txtPublicCatalogDetail_Sequence" value="<?php echo $value->sequence; ?>" autocomplete="off">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Ratio</label>
													<div class="col-lg-8">
														<input class="form-control txtPublicCatalogDetail_Ratio" type="text" name="txtPublicCatalogDetail_Ratio[]" id="txtPublicCatalogDetail_Ratio" value="<?php echo $value->ratio; ?>" autocomplete="off">
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Flavor</label>
													<div class="col-lg-8">
														<input class="form-control txtPublicCatalogDetail_Flavor" type="text" name="txtPublicCatalogDetail_Flavor[]" id="txtPublicCatalogDetail_Flavor" value="<?php echo $value->flavorID; ?>" autocomplete="off">
													</div>
												</div>

											</td>



										</tr>
									<?php
										}
									?>
								</tbody>
							</table>
			</div>
			<div class="pinv-empty">
				<i class="icon20 i-file-plus"></i>
				Este catálogo no tiene elementos.
			</div>
		</div>
		<!-- /elementos del catalogo -->

	</div>
	</form>
	<!-- /formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Catálogo #<?php echo $objPublicCatalog->publicCatalogID; ?></p>
			<p class="pv2-mbar-total">0 elementos</p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
<script type="text/template" id="tmpl_row_document">
	<tr class="row_razon">
		<td>
			<input type="checkbox"  class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
			
			<input type="hidden" name="txtPublicCatalogDetail_publicCatalogID[]" id="txtPublicCatalogDetail_publicCatalogID"   />
			<input type="hidden" name="txtPublicCatalogDetail_publicCatalogDetailID[]" id="txtPublicCatalogDetail_publicCatalogDetailID" />
			<input type="hidden" name="txtPublicCatalogDetail_parentPublicCatalogDetailID[]" id="txtPublicCatalogDetail_parentPublicCatalogDetailID" />
		</td>
		
		<td>
											
			<div class="form-group">
					<label class="col-lg-4 control-label" for="normal">Indicadores</label>
					<div class="col-lg-8">
						<input class="form-control txtPublicCatalogDetail_Name"   type="text" name="txtPublicCatalogDetail_Name[]" id="txtPublicCatalogDetail_Name" value="" autocomplete="off">
					</div>
			</div>
			
			<div class="form-group">
					<label class="col-lg-4 control-label" for="normal">Valores</label>
					<div class="col-lg-8">
						<input class="form-control txtPublicCatalogDetail_Display"   type="text" name="txtPublicCatalogDetail_Display[]" id="txtPublicCatalogDetail_Display" value="" autocomplete="off">
					</div>
			</div>
			
			<div class="form-group">
					<label class="col-lg-4 control-label" for="normal">Consjunto</label>
					<div class="col-lg-8">
						<input class="form-control txtPublicCatalogDetail_Reference4"   type="text" name="txtPublicCatalogDetail_Reference4[]" id="txtPublicCatalogDetail_Reference4" value="" autocomplete="off">
					</div>
			</div>
			
		</td>
		
		<td>
			
			<div class="form-group">
					<label class="col-lg-4 control-label" for="normal">Grupo</label>
					<div class="col-lg-8">
						<input class="form-control txtPublicCatalogDetail_Description"   type="text" name="txtPublicCatalogDetail_Description[]" id="txtPublicCatalogDetail_Description" value="" autocomplete="off">
					</div>
			</div>
			
			<div class="form-group">
					<label class="col-lg-4 control-label" for="normal">Sub grupo</label>
					<div class="col-lg-8">
						<input class="form-control txtPublicCatalogDetail_Reference1"   type="text" name="txtPublicCatalogDetail_Reference1[]" id="txtPublicCatalogDetail_Reference1" value="" autocomplete="off">
					</div>
			</div>
			
			
			
			
		</td>
		
		<td>											
			
			<div class="form-group">
					<label class="col-lg-4 control-label" for="normal">Edad</label>
					<div class="col-lg-8">
						<input class="form-control txtPublicCatalogDetail_Reference3"   type="text" name="txtPublicCatalogDetail_Reference3[]" id="txtPublicCatalogDetail_Reference3" value="" autocomplete="off">
					</div>
			</div>
			
			<div class="form-group">
					<label class="col-lg-4 control-label" for="normal">Sexo</label>
					<div class="col-lg-8">
						<input class="form-control txtPublicCatalogDetail_Reference2"   type="text" name="txtPublicCatalogDetail_Reference2[]" id="txtPublicCatalogDetail_Reference2" value="" autocomplete="off">
					</div>
			</div>
			
			<div class="form-group">
					<label class="col-lg-4 control-label" for="normal">Parent</label>
					<div class="col-lg-8">
						<input class="form-control txtPublicCatalogDetail_ParentName"   type="text" name="txtPublicCatalogDetail_ParentName[]" id="txtPublicCatalogDetail_ParentName" value="" autocomplete="off">
					</div>
			</div>
				
		</td>
		
		<td>											
			
			<div class="form-group">
					<label class="col-lg-4 control-label" for="normal">Sequencia</label>
					<div class="col-lg-8">
						<input class="form-control txtPublicCatalogDetail_Sequence"   type="text" name="txtPublicCatalogDetail_Sequence[]" id="txtPublicCatalogDetail_Sequence" value="" autocomplete="off">
					</div>
			</div>
			
			<div class="form-group">
					<label class="col-lg-4 control-label" for="normal">Ratio</label>
					<div class="col-lg-8">
						<input class="form-control txtPublicCatalogDetail_Ratio"   type="text" name="txtPublicCatalogDetail_Ratio[]" id="txtPublicCatalogDetail_Ratio" value="" autocomplete="off">
					</div>
			</div>
			
			<div class="form-group">
					<label class="col-lg-4 control-label" for="normal">Flavor</label>
					<div class="col-lg-8">
						<input class="form-control txtPublicCatalogDetail_Flavor"   type="text" name="txtPublicCatalogDetail_Flavor[]" id="txtPublicCatalogDetail_Flavor" value="" autocomplete="off">
					</div>
			</div>
				
		</td>
		
		
	</tr>
</script>