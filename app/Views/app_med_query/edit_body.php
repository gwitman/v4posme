<?php
	//Nombre del estado actual (para el encabezado)
	$mqv2StageName = "";
	if($objListWorkflowStage)
	foreach($objListWorkflowStage as $ws){
		if($ws->workflowStageID == $objTransactionMaster->statusID)
			$mqv2StageName = $ws->name;
	}
	//Proxima visita: vacio si no tiene fecha
	$mqv2NextVisit = "";
	if(!empty($objTransactionMaster->nextVisit) && substr($objTransactionMaster->nextVisit,0,4) != "0000")
		$mqv2NextVisit = date('Y-m-d',strtotime($objTransactionMaster->nextVisit));
?>
<div class="mqv2 mqv2-form">
<form id="form-new-invoice" name="form-new-invoice" role="form" autocomplete="off">

	<input type="hidden" name="txtCompanyID" value="<?php echo $objTransactionMaster->companyID; ?>">
	<input type="hidden" name="txtTransactionID" value="<?php echo $objTransactionMaster->transactionID; ?>">
	<input type="hidden" name="txtTransactionMasterID" value="<?php echo $objTransactionMaster->transactionMasterID; ?>">

	<!-- encabezado y acciones -->
	<div class="mqv2-card mqv2-header">
		<div class="mqv2-header-info">
			<div class="mqv2-header-icon"><i class="fas fa-notes-medical"></i></div>
			<div>
				<p class="mqv2-header-num">
					Asistencia #<span class="invoice-num"><?php echo $objTransactionMaster->transactionNumber; ?></span>
					<?php if($mqv2StageName != ""){ ?><span class="mqv2-chip"><?php echo htmlspecialchars($mqv2StageName); ?></span><?php } ?>
					<span class="mqv2-chip" id="mqv2HeaderPriority" style="display:none"></span>
				</p>
				<p class="mqv2-header-sub"><?php echo $objTransactionMaster->transactionOn; ?></p>
			</div>
		</div>
		<div class="mqv2-actions">
			<a href="<?php echo base_url(); ?>/app_med_query/index" id="btnBack" class="btn btn-default"><i class="icon16 i-arrow-left"></i> Atras</a>
			<a href="<?php echo base_url(); ?>/app_med_query/add" class="btn btn-default" id="btnNuevo"><i class="icon16 i-plus"></i> Nuevo</a>
			<a href="#" class="btn btn-default" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
			<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
			<a href="#" class="btn btn-primary mqv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<div class="row">

		<!-- paciente -->
		<div class="col-md-6">
			<div class="mqv2-card">
				<p class="mqv2-card-title"><span class="mqv2-ico"><i class="fas fa-user-injured"></i></span> Paciente</p>
				<div class="form-group">
					<label class="mqv2-label" for="txtCustomerDescription">Cliente</label>
					<div class="input-group">
						<input type="hidden" id="txtCustomerID" name="txtCustomerID" value="<?php echo $objTransactionMaster->entityID;  ?>">
						<input class="form-control" readonly id="txtCustomerDescription" type="text" placeholder="Busca un paciente" value="<?php echo $objNaturalDefault != null ? strtoupper($objCustomerDefault->customerNumber . " ". $objNaturalDefault->firstName . " ". $objNaturalDefault->lastName ) : strtoupper($objCustomerDefault->customerNumber." ".$objLegalDefault->comercialName); ?>">
						<span class="input-group-btn">
							<button class="btn btn-primary" type="button" id="btnSearchCustomer" title="Buscar paciente">
								<i aria-hidden="true" class="i-search-5"></i> Buscar
							</button>
						</span>
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" id="btnClearCustomer" title="Limpiar paciente">
								<i aria-hidden="true" class="i-undo-2"></i>
							</button>
						</span>
					</div>
				</div>
			</div>
		</div>
		<!-- /paciente -->

		<!-- consulta -->
		<div class="col-md-6">
			<div class="mqv2-card">
				<p class="mqv2-card-title"><span class="mqv2-ico"><i class="fas fa-calendar-alt"></i></span> Consulta</p>
				<div class="row">
					<div class="col-xs-12 col-sm-6 form-group">
						<label class="mqv2-label" for="txtDate">Fecha</label>
						<div class="input-group date" data-date-format="yyyy-mm-dd">
							<input size="16" class="form-control" type="text" name="txtDate" id="txtDate" value="<?php echo $objTransactionMaster->transactionOn; ?>" >
							<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 form-group">
						<label class="mqv2-label" for="txtNextVisit">Proxima visita</label>
						<div class="input-group date" data-date-format="yyyy-mm-dd">
							<input size="16" class="form-control" type="text" name="txtNextVisit" id="txtNextVisit" value="<?php echo $mqv2NextVisit; ?>">
							<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 form-group">
						<label class="mqv2-label" for="txtPriorityID">Prioridad</label>
						<select name="txtPriorityID" id="txtPriorityID" class="form-control">
								<option></option>
								<?php
								$count = 0;
								if($objListPrioridad)
								foreach($objListPrioridad as $price){
									if( $price->catalogItemID == $objTransactionMaster->priorityID )
										echo "<option value='".$price->catalogItemID."' selected >".$price->display."</option>";
									else
										echo "<option value='".$price->catalogItemID."'  >".$price->display."</option>";

									$count++;
								}
								?>
						</select>
					</div>
					<div class="col-xs-12 col-sm-6 form-group">
						<label class="mqv2-label" for="txtStatusID">Estado</label>
						<select name="txtStatusID" id="txtStatusID" class="form-control">
								<option></option>
								<?php
								if($objListWorkflowStage)
								foreach($objListWorkflowStage as $ws){

									if($ws->workflowStageID == $objTransactionMaster->statusID)
										echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
									else
										echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
								}
								?>
						</select>
					</div>
				</div>
			</div>
		</div>
		<!-- /consulta -->

	</div>

	<div class="row">

		<!-- signos vitales -->
		<div class="col-md-5" id="mqv2ColVitals" data-mqv2-autohide="1" data-mqv2-expand="#mqv2ColClinical">
			<div class="mqv2-card">
				<p class="mqv2-card-title"><span class="mqv2-ico"><i class="fas fa-heartbeat"></i></span> Signos vitales</p>
				<div class="row">
					<!-- Edad-->
					<div class="col-xs-12 col-sm-4 form-group <?php echo getBehavio($company->type,"app_med_query","divPanelEdad",""); ?>">
						<label class="mqv2-label" for="txtAge">Edad</label>
						<div class="mqv2-unit">
							<input class="form-control" type="number" inputmode="numeric" step="any" name="txtAge" id="txtAge" value="<?php echo number_format($objTransactionMaster->tax1,0);  ?>">
							<span>años</span>
						</div>
					</div>

					<!-- Altura-->
					<div class="col-xs-6 col-sm-4 form-group <?php echo getBehavio($company->type,"app_med_query","divPanelAltura",""); ?>">
						<label class="mqv2-label" for="txtHeight">Altura</label>
						<div class="mqv2-unit">
							<input class="form-control" type="number" inputmode="decimal" step="any" name="txtHeight" id="txtHeight" value="<?php echo number_format($objTransactionMaster->tax2,2);  ?>">
							<span>cm</span>
						</div>
					</div>

					<!-- Peso -->
					<div class="col-xs-6 col-sm-4 form-group <?php echo getBehavio($company->type,"app_med_query","divPanelPeso",""); ?> ">
						<label class="mqv2-label" for="txtWeight">Peso</label>
						<div class="mqv2-unit">
							<input class="form-control" type="number" inputmode="decimal" step="any" name="txtWeight" id="txtWeight" value="<?php echo number_format($objTransactionMaster->tax3,2);  ?>">
							<span>kg</span>
						</div>
					</div>

					<!-- IMC -->
					<div class="col-xs-12 form-group <?php echo getBehavio($company->type,"app_med_query","divPanelIMC",""); ?> ">
						<label class="mqv2-label" for="txtIMC">Indice de masa corporal (IMC)</label>
						<div class="mqv2-imc" id="mqv2ImcBox">
							<input class="form-control" readonly type="number" name="txtIMC" id="txtIMC" value="<?php echo number_format($objTransactionMaster->tax4,2) ?>">
							<span class="mqv2-imc-label" id="mqv2ImcLabel">Sin datos</span>
						</div>
						<div class="mqv2-imc-legend">
							<span class="l1">&lt; 18.5 bajo peso</span>
							<span class="l2">18.5 a 24.9 normal</span>
							<span class="l3">25 a 29.9 sobrepeso</span>
							<span class="l4">30 o mas obesidad</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /signos vitales -->

		<!-- evaluacion clinica -->
		<div class="col-md-7" id="mqv2ColClinical">
			<div class="mqv2-card">
				<p class="mqv2-card-title"><span class="mqv2-ico"><i class="fas fa-stethoscope"></i></span> Evaluacion clinica</p>
				<div class="row">
					<!-- Resultado -->
					<div class="col-xs-12 col-sm-6 form-group <?php echo getBehavio($company->type,"app_med_query","divPanelResultado",""); ?> ">
						<label class="mqv2-label" for="txtResult">Resultado</label>
						<input class="form-control" type="text" name="txtResult" id="txtResult" value="<?php echo $objTransactionMaster->reference1;  ?>">
					</div>

					<!-- Evaluacion -->
					<div class="col-xs-12 col-sm-6 form-group <?php echo getBehavio($company->type,"app_med_query","divPanelEvaluacion",""); ?> ">
						<label class="mqv2-label" for="txtEvaluation">Evaluacion</label>
						<input class="form-control" type="text" name="txtEvaluation" id="txtEvaluation" value="<?php echo $objTransactionMaster->reference2;  ?>">
					</div>

					<!-- Recomendacion -->
					<div class="col-xs-12 col-sm-6 form-group <?php echo getBehavio($company->type,"app_med_query","divPanelRecomendacion",""); ?> ">
						<label class="mqv2-label" for="txtRecomendation">Recomendacion</label>
						<input class="form-control" type="text" name="txtRecomendation" id="txtRecomendation" value="<?php echo $objTransactionMaster->reference3;  ?>">
					</div>

					<!-- Diagnostico -->
					<div class="col-xs-12 col-sm-6 form-group <?php echo getBehavio($company->type,"app_med_query","divPanelDiagnostico",""); ?> ">
						<label class="mqv2-label" for="txtDiagnostic">Diagnostico</label>
						<input class="form-control" type="text" name="txtDiagnostic" id="txtDiagnostic" value="<?php echo $objTransactionMaster->reference4;  ?>">
					</div>

					<!-- Observacion -->
					<div class="col-xs-12 form-group">
						<label class="mqv2-label" for="txtNote">Observacion</label>
						<textarea class="form-control" name="txtNote" id="txtNote" rows="3"><?php echo $objTransactionMaster->note;  ?></textarea>
					</div>
				</div>
			</div>
		</div>
		<!-- /evaluacion clinica -->

	</div>

	<!-- receta / indicaciones -->
	<div class="mqv2-card">
		<div class="mqv2-detail-head">
			<p class="mqv2-card-title"><span class="mqv2-ico"><i class="fas fa-pills"></i></span> Receta / indicaciones <span class="mqv2-count" id="mqv2DetailCount">0</span></p>
		</div>

		<!-- captura -->
		<div class="mqv2-entry" id="filaEntrada">
			<div class="row">
				<div class="col-xs-12 col-md-4 form-group">
					<label class="mqv2-label" for="txtDetailName">Nombre</label>
					<input class="form-control" type="text" name="txtDetailName" id="txtDetailName" value="" placeholder="Medicamento o indicacion">
					<span id="errorLabel" class="mqv2-error" style="display:none">Este campo no puede estar vacío</span>
				</div>
				<div class="col-xs-6 col-md-2 form-group">
					<label class="mqv2-label" for="txtPriority">Prioridad</label>
					<select name="txtPriority" id="txtPriority" class="form-control">
							<?php
							$count = 0;
							if($objListPriority){
								foreach($objListPriority as $ws){
									if($count == 0 )
										echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
									else
										echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
									$count++;
								}
							}
							?>
					</select>
				</div>
				<div class="col-xs-6 col-md-2 form-group">
					<label class="mqv2-label" for="txtFrecuency">Frecuencia</label>
					<select name="txtFrecuency" id="txtFrecuency" class="form-control">
							<?php
							$count = 0;
							if($objListFrecuency){
								foreach($objListFrecuency as $ws){
									if($count == 0 )
										echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
									else
										echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
									$count++;
								}
							}
							?>
					</select>
				</div>
				<div class="col-xs-6 col-md-2 form-group">
					<label class="mqv2-label" for="txtDose">Dosis</label>
					<select name="txtDose" id="txtDose" class="form-control">
							<?php
							$count = 0;
							if($objListDose){
								foreach($objListDose as $ws){
									if($count == 0 )
										echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
									else
										echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
									$count++;
								}
							}
							?>
					</select>
				</div>
				<div class="col-xs-6 col-md-1 form-group">
					<label class="mqv2-label" for="txtAmount">Monto</label>
					<input class="form-control" type="number" inputmode="decimal" step="any" name="txtAmount" id="txtAmount" value="">
				</div>
				<div class="col-xs-12 col-md-1 form-group mqv2-entry-btn">
					<button type="button" class="btn btn-primary" id="btnAddDetail" title="Agregar indicacion"><i class="fas fa-plus"></i><span class="visible-xs-inline visible-sm-inline"> Agregar</span></button>
				</div>
			</div>
		</div>
		<!-- /captura -->

		<table class="mqv2-table hidden" id="tableFrecuency">
			<thead>
				<tr>
					<th>Nombre</th>
					<th>Prioridad</th>
					<th>Frecuencia</th>
					<th>Dosis</th>
					<th class="mqv2-col-amount">Monto</th>
					<th class="mqv2-col-del"></th>
				</tr>
			</thead>
			<tbody id="mqv2DetailBody">
				<!-- Aquí se agregarán las filas dinámicamente -->

				<!-- Filas obtenidas de la base de datos -->
				<?php
				 if($objListDetails){
					foreach($objListDetails as $value):
					?>
						<tr class="mqv2-row">
							<td class="mqv2-col-name" data-label="Nombre">
								<input type="hidden" name="customerMasterDetails[]" value="<?= $value->transactionMasterDetailID?>" />
								<input class="form-control" type="text" name="txtDetailNameArray[]" value="<?= htmlspecialchars($value->itemNameLog ?? "") ?>">
							</td>
							<td data-label="Prioridad">
								<select name="txtPriorityArray[]" class="form-control">
									<?php
										if($objListPriority){
											foreach($objListPriority as $ws){
									?>
											<option value='<?=$ws->catalogItemID?>' <?= $ws->catalogItemID == $value->skuQuantity ? 'selected' : '' ?> ><?=$ws->name?></option>
									<?php
											}
										}
									?>
								</select>
							</td>
							<td data-label="Frecuencia">
								<select name="txtFrecuencyArray[]" class="form-control">
									<?php
										if($objListFrecuency){
											foreach($objListFrecuency as $ws){
									?>
											<option value='<?=$ws->catalogItemID?>' <?= $ws->catalogItemID == $value->skuQuantityBySku ? 'selected' : '' ?> ><?=$ws->name?></option>
									<?php
											}
										}
									?>
								</select>
							</td>
							<td data-label="Dosis">
								<select name="txtDoseArray[]" class="form-control">
									<?php
										if($objListDose){
											foreach($objListDose as $ws){
									?>
											<option value='<?=$ws->catalogItemID?>' <?= $ws->catalogItemID == $value->typePriceID ? 'selected' : '' ?> ><?=$ws->name?></option>
									<?php
											}
										}
									?>
								</select>
							</td>
							<td class="mqv2-col-amount" data-label="Monto">
								<input class="form-control" type="number" inputmode="decimal" step="any" name="txtAmountArray[]" value="<?= $value->amount ?>">
							</td>
							<td class="mqv2-col-del">
								<button type="button" class="btn mqv2-btn-del" title="Quitar" onclick="fnEliminarFila(this)"><i class="fas fa-trash"></i></button>
							</td>
						</tr>
				<?php
					endforeach;
				 }
				 ?>
			</tbody>
		</table>

		<div class="mqv2-empty" id="mqv2EmptyDetail">
			<i class="fas fa-prescription-bottle-alt"></i>
			Aun no hay indicaciones. Escribe el nombre y toca <b>Agregar</b>.
		</div>
	</div>
	<!-- /receta / indicaciones -->

</form>

	<!-- barra inferior (telefono) -->
	<div class="mqv2-mbar">
		<div class="mqv2-mbar-info">
			<p class="mqv2-mbar-label">Paciente</p>
			<p class="mqv2-mbar-name" id="mqv2MbarName"></p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>


<script type="text/template" id="tmpl_row_detail">
	<tr class="mqv2-row">
		<td class="mqv2-col-name" data-label="Nombre">
			<input type="hidden" name="customerMasterDetails[]" value="0">
			<input class="form-control mqv2-in-name" type="text" name="txtDetailNameArray[]" value="">
		</td>
		<td data-label="Prioridad">
			<select name="txtPriorityArray[]" class="form-control mqv2-in-priority">
				<?php
					if(isset($objListPriority) && $objListPriority){
						foreach($objListPriority as $ws){
							echo "<option value='".$ws->catalogItemID."'>".$ws->name."</option>";
						}
					}
				?>
			</select>
		</td>
		<td data-label="Frecuencia">
			<select name="txtFrecuencyArray[]" class="form-control mqv2-in-frecuency">
				<?php
					if(isset($objListFrecuency) && $objListFrecuency){
						foreach($objListFrecuency as $ws){
							echo "<option value='".$ws->catalogItemID."'>".$ws->name."</option>";
						}
					}
				?>
			</select>
		</td>
		<td data-label="Dosis">
			<select name="txtDoseArray[]" class="form-control mqv2-in-dose">
				<?php
					if(isset($objListDose) && $objListDose){
						foreach($objListDose as $ws){
							echo "<option value='".$ws->catalogItemID."'>".$ws->name."</option>";
						}
					}
				?>
			</select>
		</td>
		<td class="mqv2-col-amount" data-label="Monto">
			<input class="form-control mqv2-in-amount" type="number" inputmode="decimal" step="any" name="txtAmountArray[]" value="">
		</td>
		<td class="mqv2-col-del">
			<button type="button" class="btn mqv2-btn-del" title="Quitar" onclick="fnEliminarFila(this)"><i class="fas fa-trash"></i></button>
		</td>
	</tr>
</script>
