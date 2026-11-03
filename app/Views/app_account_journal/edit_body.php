<?php
	//Nombre del estado actual para el chip del encabezado
	$journalStageName = "";
	if($objListWorkflowStage)
	foreach($objListWorkflowStage as $ws){
		if($ws->workflowStageID == $objJournalEntry->statusID)
			$journalStageName = $ws->name;
	}
	if($journalStageName == "")
		$journalStageName = "Edición";
?>
<div class="pv2 pinv pinv-form pinv-journal pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-drawer-2"></i></div>
			<div>
				<p class="pv2-header-title">Comprobante #<span class="invoice-num"><?php echo $objJournalEntry->journalNumber; ?></span> <span class="pv2-chip<?php if($objJournalEntry->isApplied) echo " is-applied"; ?>"><?php echo ($objJournalEntry->isApplied) ? "Aplicado" : htmlentities($journalStageName, ENT_QUOTES); ?></span></p>
				<p class="pv2-header-sub">Revisa o modifica los movimientos del asiento contable</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_accounting_journal/edit/companyID/<?php echo ($objBackJournal != NULL ? $objJournalEntry->companyID : 0); ?>/journalEntryID/<?php echo ($objBackJournal != NULL ?  $objBackJournal->journalEntryID : 0); ?>" class="btn btn-default" title="Comprobante anterior" ><i class="icon16 i-backward-2"></i><span class="hidden-xs"> Anterior</span></a>
			<a href="<?php echo base_url(); ?>/app_accounting_journal/edit/companyID/<?php echo ($objNextJournal != NULL ? $objJournalEntry->companyID : 0); ?>/journalEntryID/<?php echo ($objNextJournal != NULL ?  $objNextJournal->journalEntryID : 0); ?>" class="btn btn-default" title="Comprobante siguiente" ><span class="hidden-xs">Siguiente </span><i class="icon16 i-forward-3"></i></a>
			<a href="<?php echo base_url(); ?>/app_accounting_journal/add"  class="btn btn-default pv2-hide-xs" ><i class="icon16 i-pushpin"></i> Nuevo</a>
			<a href="<?php echo base_url(); ?>/app_accounting_journal/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
			<a href="#" class="btn btn-default pv2-hide-xs" id="btnAudit"><i class="icon16 i-pen-3"></i> Auditoría</a>
			<a href="#" class="btn btn-default pv2-hide-xs" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
			<!-- telefono: acciones secundarias agrupadas (disparan los botones originales) -->
			<div class="btn-group pinv-journal-only-xs">
				<a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="icon16 i-menu-2"></i> Más <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="<?php echo base_url(); ?>/app_accounting_journal/add"><i class="icon16 i-pushpin"></i> Nuevo</a></li>
					<li><a href="#" class="jsJournalProxy" data-target-btn="#btnAudit"><i class="icon16 i-pen-3"></i> Auditoría</a></li>
					<li><a href="#" class="jsJournalProxy" data-target-btn="#btnPrinter"><i class="icon16 i-print"></i> Imprimir</a></li>
				</ul>
			</div>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- formulario -->
	<form id="form-new-account-journal" name="form-new-account-journal" role="form" autocomplete="off">
	<div class="printArea">

		<!-- datos del comprobante -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Datos del comprobante</p>

			<input type="hidden" name="txtJournalEntryID" value="<?php echo $objJournalEntry->journalEntryID; ?>">

			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-3 form-group">
					<label class="pv2-label" for="txtDate">Fecha</label>
					<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
						<input size="16" class="form-control" type="text" name="txtDate" id="txtDate" value="<?php echo $objJournalEntry->journalDate; ?>" autocomplete="off">
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
								if($ws->workflowStageID == $objJournalEntry->statusID)
									echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
								else
									echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
							}
							?>
					</select>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-3 form-group">
					<label class="pv2-label" for="txtJournalType">Tipo</label>
					<select name="txtJournalType" id="txtJournalType" class="select2">
							<option></option>
							<?php
							if($objListJournalType)
							foreach($objListJournalType as $ws){

								if($ws->catalogItemID == $objJournalEntry->journalTypeID)
									echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
								else
									echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";

							}
							?>
					</select>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-3 form-group">
					<label class="pv2-label" for="txtCurrencyID">Moneda</label>
					<select name="txtCurrencyID" id="txtCurrencyID" class="select2">
							<option></option>
							<?php
							if($objListCurrency)
							foreach($objListCurrency as $ws){
								if($ws->currencyID == $objJournalEntry->currencyID )
									echo "<option value='".$ws->currencyID."' selected >".$ws->name."</option>";
								else
									echo "<option value='".$ws->currencyID."'  >".$ws->name."</option>";
							}
							?>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtEntryName">Beneficiario <span class="pv2-req">*</span></label>
					<input class="form-control"  type="text"  name="txtEntryName" id="txtEntryName" value="<?php echo $objJournalEntry->entryName; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-3 form-group">
					<label class="pv2-label" for="txtExchangeRate">Tipo de cambio</label>
					<input class="form-control pv2-num"  type="text" disabled="disabled" name="txtExchangeRate" id="txtExchangeRate" value="<?php echo $objExchangeRate; ?>">
				</div>
				<div class="col-xs-12 col-sm-3 form-group">
					<label class="pv2-label">&nbsp;</label>
					<label class="pinv-journal-check" for="txtIsApplied">
						<input type="checkbox" disabled="disabled"   name="txtIsApplied" id="txtIsApplied" value="1" <?php if($objJournalEntry->isApplied) echo "checked"; ?> > Aplicado
					</label>
				</div>
			</div>
		</div>
		<!-- /datos del comprobante -->

		<!-- referencias y plantilla -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-link"></i></span> Referencias y plantilla</p>
			<div class="row">
				<div class="col-xs-12 col-sm-4 form-group">
					<label class="pv2-label" for="txtReference1">Referencia 1</label>
					<input class="form-control"  type="text"  name="txtReference1" id="txtReference1" value="<?php echo $objJournalEntry->reference1 ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-4 form-group">
					<label class="pv2-label" for="txtReference2">Referencia 2</label>
					<input class="form-control"  type="text"  name="txtReference2" id="txtReference2" value="<?php echo $objJournalEntry->reference2; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-4 form-group">
					<label class="pv2-label" for="txtReference3">Referencia 3</label>
					<input class="form-control"  type="text"  name="txtReference3" id="txtReference3" value="<?php echo $objJournalEntry->reference3; ?>" autocomplete="off">
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-4 form-group">
					<label class="pv2-label">&nbsp;</label>
					<label class="pinv-journal-check" for="txtIsTemplated">
						<input type="checkbox"  name="txtIsTemplated" id="txtIsTemplated" value="1" <?php if($objJournalEntry->isTemplated) echo "checked"; ?> > Es plantilla
					</label>
				</div>
				<div class="col-xs-12 col-sm-8 form-group">
					<label class="pv2-label" for="txtTitleTemplated">Título de la plantilla</label>
					<input class="form-control"  type="text"  name="txtTitleTemplated" id="txtTitleTemplated" value="<?php echo $objJournalEntry->titleTemplated; ?>" autocomplete="off">
				</div>
			</div>
		</div>
		<!-- /referencias y plantilla -->

		<!-- comentario -->
		<div class="pv2-card">
			<div class="pv2-detail-head">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-bubble"></i></span> Comentario</p>
				<div class="pv2-actions">
					<a href="#" class="btn btn-default btn-sm" id="btnClickArchivo"><i class="icon16 i-attachment"></i> Archivos</a>
				</div>
			</div>
			<div class="form-group">
				<label class="pv2-label" for="txtNote">Descripción</label>
				<textarea class="form-control" id="txtNote" name="txtNote" rows="4"><?php echo $objJournalEntry->note; ?></textarea>
			</div>
		</div>
		<!-- /comentario -->

		<!-- detalle del comprobante -->
		<div class="pv2-card">
			<div class="pv2-detail-head">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Movimientos <span class="pv2-count">0</span></p>
				<div class="pv2-actions">
					<a href="#" class="btn btn-default btn-sm" id="btnDeleteDetailJournal" ><i class="icon16 i-remove"></i> Quitar marcadas</a>
					<a href="#" class="btn btn-primary btn-sm" id="btnNewDetailJournal" ><i class="icon16 i-plus"></i> Agregar cuenta</a>
				</div>
			</div>
			<div class="pv2-table-wrap">
				<table id="tb_journal_entry_detail" class="table table-bordered">
					<thead>
					  <tr>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th>Cuenta</th>
						<th title="Centro de costo">CC</th>
						<th class="pinv-journal-num">Débito</th>
						<th class="pinv-journal-num">Crédito</th>
					  </tr>
					</thead>
					<tbody id="body_jornal_entry_detail">
					</tbody>
				</table>
			</div>
			<div class="pv2-empty">
				<i class="icon20 i-file-plus"></i>
				Este comprobante no tiene movimientos. Toca <b>Agregar cuenta</b> para empezar.
			</div>
		</div>
		<!-- /detalle del comprobante -->

		<!-- resumen -->
		<div class="pv2-kpis">
			<div class="pv2-kpi k-info">
				<div class="pv2-kpi-icon"><i class="icon16 i-arrow-down-2"></i></div>
				<div class="pv2-kpi-body">
					<label class="pv2-kpi-label" for="txtTotalDebit">Total débito</label>
					<input type="text" id="txtTotalDebit" class="pv2-kpi-value txt-numeric" value="" disabled />
				</div>
			</div>
			<div class="pv2-kpi k-purple">
				<div class="pv2-kpi-icon"><i class="icon16 i-arrow-up-2"></i></div>
				<div class="pv2-kpi-body">
					<label class="pv2-kpi-label" for="txtTotalCredit">Total crédito</label>
					<input type="text" id="txtTotalCredit" class="pv2-kpi-value txt-numeric" value="" disabled />
				</div>
			</div>
			<div class="pv2-kpi pinv-journal-kpi-balance" id="divJournalBalance">
				<div class="pv2-kpi-icon"><i class="icon16 i-checkmark-4"></i></div>
				<div class="pv2-kpi-body">
					<span class="pv2-kpi-label">Estado del comprobante</span>
					<p class="pinv-journal-balance" id="lblJournalBalance">Sin movimientos</p>
					<span class="pinv-journal-diff" id="lblJournalDiff"></span>
				</div>
			</div>
		</div>
		<!-- /resumen -->

	</div>
	</form>
	<!-- /formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Comprobante #<?php echo $objJournalEntry->journalNumber; ?> · <span id="lblJournalLines">0 líneas</span></p>
			<p class="pinv-journal-mbar">D <span id="lblMbarDebit">0.00</span> · C <span id="lblMbarCredit">0.00</span></p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
