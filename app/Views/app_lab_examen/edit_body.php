<?php
	//Numero del examen y estado de la pantalla (nuevo / editando)
	$examenNumero = helper_RequestGetValueObjet($objTransactionMaster,"transactionNumber",0);
	$examenNuevo  = helper_RequestGetValueObjet($objTransactionMaster,"transactionMasterID",0) == 0;
?>
<div class="pv2 pinv pinv-form pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-last"></i></div>
			<div>
				<p class="pv2-header-title">Examen #<span class="invoice-num"><?php echo $examenNumero; ?></span> <span class="pv2-chip"><?php echo $examenNuevo ? "Nuevo" : "Editando"; ?></span></p>
				<p class="pv2-header-sub">Registro y resultados del examen de laboratorio</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_lab_examen/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
			<a href="#" class="btn btn-default" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- formulario -->
	<form id="form-new-transaction" name="form-new-invoice" role="form" autocomplete="off">

		<input type="hidden" id="txtCompanyID" name="txtCompanyID" value="<?php echo helper_RequestGetValueObjet($objTransactionMaster,"companyID",0); ?>">
		<input type="hidden" id="txtTransactionID" name="txtTransactionID" value="<?php echo helper_RequestGetValueObjet($objTransactionMaster,"transactionID",0); ?>">
		<input type="hidden" id="txtTransactionMasterID" name="txtTransactionMasterID" value="<?php echo helper_RequestGetValueObjet($objTransactionMaster,"transactionMasterID",0); ?>">
		<input type="hidden" id="txtComando" name="txtComando" value="view" >
		<input type="hidden" id="txtStatusOld" name="txtStatusOld" value="<?php echo helper_RequestGetValueObjet($objTransactionMaster,"statusID",0) ?>" >

		<!-- paciente -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Paciente</p>
			<div class="row">
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtCustomerDescription">Paciente</label>
					<div class="input-group">
						<input type="hidden" id="txtCustomerID" name="txtCustomerID" value="<?php
							echo
							helper_RequestGetValueObjet(
								$objTransactionMaster,
								"entityID",
								helper_RequestGetValueObjet(
									$objCustomerDefault,
									"entityID",
									0
								)
							);
						?>">
						<input class="form-control" readonly id="txtCustomerDescription" type="text" autocomplete="off"
						value="<?php
								echo
									helper_RequestGetValueObjet($objCustomerDefault,"customerNumber","").
									" ".
									helper_RequestGetValueObjet($objNaturalDefault,"firstName","");
								?>">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" id="btnClearCustomer" title="Limpiar"><i aria-hidden="true" class="i-undo-2"></i> Limpiar</button>
							<button class="btn btn-primary" type="button" id="btnSearchCustomer" title="Buscar"><i aria-hidden="true" class="i-search-5"></i> Buscar</button>
						</span>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtRemitente">Remitente</label>
					<input class="form-control"   type="text" name="txtRemitente" id="txtRemitente" autocomplete="off" value="<?php echo helper_RequestGetValueObjet($objTransactionMaster,"reference2","..."); ?>" />
				</div>
				<div class="col-xs-12 col-sm-4 form-group">
					<label class="pv2-label" for="txtSexoID">Sexo</label>
											<select name="txtSexoID" id="txtSexoID" class="select2">
													<option></option>																
													<?php
													$count = 0;
													if($objListSexo)
													foreach($objListSexo as $z){
														if($z->publicCatalogDetailID  == helper_RequestGetValueObjet($objTransactionMaster,"areaID",$sexoID) )
															echo "<option data-urlprinter='".$z->name."' value='".$z->publicCatalogDetailID."' selected >".$z->name."</option>";
														else
															echo "<option data-urlprinter='".$z->name."' value='".$z->publicCatalogDetailID."'  >".$z->name."</option>";
														$count++;
													}
													?>
											</select>
				</div>
				<div class="col-xs-12 col-sm-4 form-group">
					<label class="pv2-label" for="txtEdadID">Edad</label>
											<select name="txtEdadID" id="txtEdadID" class="select2">
													<option></option>																
													<?php
													$count = 0;
													if($objListEdad)
													foreach($objListEdad as $z){
														if($z->publicCatalogDetailID  == helper_RequestGetValueObjet($objTransactionMaster,"priorityID",$edadID	) )
															echo "<option data-urlprinter='".$z->name."' value='".$z->publicCatalogDetailID."' selected >".$z->name."</option>";
														else
															echo "<option data-urlprinter='".$z->name."' value='".$z->publicCatalogDetailID."'  >".$z->name."</option>";
														$count++;
													}
													?>
											</select>
				</div>
				<div class="col-xs-12 col-sm-4 form-group">
					<label class="pv2-label" for="txtMuestra">Muestra</label>
					<select name="txtMuestra" id="txtMuestra" class="select2">
						<option></option>
						<?php
						$count = 0;
						if($objListMuestra)
						foreach($objListMuestra as $z){
							if($z->publicCatalogDetailID  == helper_RequestGetValueObjet($objTransactionMaster,"reference1",$muestraID) )
								echo "<option data-urlprinter='".$z->name."' value='".$z->publicCatalogDetailID."' selected >".$z->name."</option>";
							else
								echo "<option data-urlprinter='".$z->name."' value='".$z->publicCatalogDetailID."'  >".$z->name."</option>";
							$count++;
						}
						?>
					</select>
				</div>
			</div>
		</div>
		<!-- /paciente -->

		<!-- examen -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Examen</p>
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-3 form-group">
					<label class="pv2-label" for="txtExamenID">Examen</label>
											<select name="txtExamenID" id="txtExamenID" class="select2">
													<option></option>																
													<?php
													$count = 0;
													if($objListExamenes)
													foreach($objListExamenes as $z){
														if($z->publicCatalogDetailID  == helper_RequestGetValueObjet($objTransactionMaster,"reference3",$examenId) )
															echo "<option data-urlprinter='".$z->parentName."' value='".$z->publicCatalogDetailID."' selected >".$z->name."</option>";
														else
															echo "<option data-urlprinter='".$z->parentName."' value='".$z->publicCatalogDetailID."'  >".$z->name."</option>";
														$count++;
													}
													?>
											</select>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-3 form-group">
					<label class="pv2-label" for="txtStatusID">Estado</label>
											<select name="txtStatusID" id="txtStatusID" class="select2">
													<option></option>																
													<?php
													$counter = 0;
													if($objListWorkflowStage)
													foreach($objListWorkflowStage as $ws){
														$counter++;
														if(
															$ws->workflowStageID == helper_RequestGetValueObjet($objTransactionMaster,"statusID",0) 
															||
															$counter == 1 && helper_RequestGetValueObjet($objTransactionMaster,"statusID",0) == 0 
														)
															echo "<option value='".$ws->workflowStageID."' selected >".$ws->name."</option>";
														else 
															echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
													}
													?>
											</select>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-3 form-group">
					<label class="pv2-label" for="txtDate">Fecha</label>
					<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
						<input size="16"  class="form-control" type="text" name="txtDate" id="txtDate" autocomplete="off" value="<?php echo helper_RequestGetValueObjet($objTransactionMaster,"transactionOn","2023-01-01"); ?>" >
						<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-3 form-group">
					<label class="pv2-label" for="txtHora_hora">Hora</label>
					<input type="hidden" id="txtHoraHidden" name="txtHoraHidden" />
					<div class="pv2-hora">
						<?php
						echo helper_GetHtmlControlHora("txtHora",helper_RequestGetValueObjet($objTransactionMaster,"reference4",helper_getDateTime()));
						?>
					</div>
				</div>
			</div>
		</div>
		<!-- /examen -->

		<!-- descripcion -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-pencil-4"></i></span> Descripción</p>
			<div class="form-group">
				<label class="pv2-label" for="txtNote">Descripción del examen</label>
				<textarea class="form-control"   type="text" name="txtNote" id="txtNote" rows="4"  value=""><?php echo helper_RequestGetValueObjet($objTransactionMaster,"note","..."); ?></textarea>
			</div>
		</div>
		<!-- /descripcion -->

		<!-- resultados -->
		<div class="pv2-card">
			<div class="pv2-detail-head">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Resultados <span class="pinv-count">0</span></p>
			</div>
			<div class="pinv-table-wrap">
				<table id="tb_transaction_master_detail" class="table table-bordered">
					<thead>
					  <tr>
						<th>transactionMasterDetailID</th>
						<th>publicCatalogDetailID</th>
						<th>Nombre</th>
						<th>Valor</th>
					  </tr>
					</thead>
					<tbody id="body_detail_transaction">
					</tbody>
				</table>
			</div>
			<div class="pinv-empty">
				<i class="icon20 i-file-plus"></i>
				Este examen no tiene parámetros configurados.
			</div>
		</div>
		<!-- /resultados -->

	</form>
	<!-- /formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Examen #<?php echo $examenNumero; ?></p>
			<p class="pv2-mbar-total">0 parametros</p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>