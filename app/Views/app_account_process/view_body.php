<div class="pv2 pinv pinv-form">

	<!-- encabezado -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-hammer"></i></div>
			<div>
				<p class="pv2-header-title">Procesos contables</p>
				<p class="pv2-header-sub">Cierre, mayorización, contabilización, tipo de cambio y notificaciones</p>
			</div>
		</div>
	</div>
	<!-- /encabezado -->

	<div class="row pacp-grid">

		<!-- cerrar ciclo -->
		<div class="col-xs-12 col-md-6">
			<div class="pv2-card">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-lock"></i></span> Cerrar ciclo</p>
				<p class="pv2-help pacp-desc">Cierra el ciclo contable seleccionado; después no se podrán registrar movimientos en él.</p>
				<form role="form" autocomplete="off" onsubmit="return false;">
					<div class="row">
						<div class="col-xs-12 col-sm-6 form-group">
							<label class="pv2-label" for="txtClosedPeriod">Periodo</label>
							<select name="txtClosedPeriod" id="txtClosedPeriod" class="select2">
									<option></option>
									<?php
									if($objListAccountingPeriod)
									foreach($objListAccountingPeriod as $i){
										echo "<option value='".$i->componentPeriodID."'>".helper_DateToSpanish($i->startOn,"Y")."</option>";
									}
									?>
							</select>
						</div>
						<div class="col-xs-12 col-sm-6 form-group">
							<label class="pv2-label" for="txtClosedCicle">Ciclo</label>
							<select name="txtClosedCicle" id="txtClosedCicle" class="select2">
									<option></option>
							</select>
							<span class="pv2-help pacp-empty" id="lblClosedSinCiclos" style="display:none">Sin ciclos abiertos en este periodo</span>
						</div>
					</div>
					<div class="pacp-foot">
						<button type="button" id="btnAceptClosedCycle" class="btn btn-primary"><i class="icon16 i-lock"></i> Cerrar ciclo</button>
					</div>
				</form>
			</div>
		</div>
		<!-- /cerrar ciclo -->

		<!-- mayorizar -->
		<div class="col-xs-12 col-md-6">
			<div class="pv2-card">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-stack"></i></span> Mayorizar</p>
				<p class="pv2-help pacp-desc">Traslada los movimientos del ciclo a los saldos del libro mayor.</p>
				<form role="form" autocomplete="off" onsubmit="return false;">
					<div class="row">
						<div class="col-xs-12 col-sm-6 form-group">
							<label class="pv2-label" for="txtMayorizatePeriod">Periodo</label>
							<select name="txtMayorizatePeriod" id="txtMayorizatePeriod" class="select2">
									<option></option>
									<?php
									if($objListAccountingPeriod)
									foreach($objListAccountingPeriod as $i){
										echo "<option value='".$i->componentPeriodID."'>".helper_DateToSpanish($i->startOn,"Y")."</option>";
									}
									?>
							</select>
						</div>
						<div class="col-xs-12 col-sm-6 form-group">
							<label class="pv2-label" for="txtMayorizateCicle">Ciclo</label>
							<select name="txtMayorizateCicle" id="txtMayorizateCicle" class="select2">
									<option></option>
							</select>
							<span class="pv2-help pacp-empty" id="lblMayorizateSinCiclos" style="display:none">Sin ciclos abiertos en este periodo</span>
						</div>
					</div>
					<div class="pacp-foot">
						<button type="button" id="btnAceptMayorizateCycle" class="btn btn-primary"><i class="icon16 i-stack"></i> Mayorizar</button>
					</div>
				</form>
			</div>
		</div>
		<!-- /mayorizar -->

		<!-- contabilizar documentos -->
		<div class="col-xs-12 col-md-6">
			<div class="pv2-card">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-calculate"></i></span> Contabilizar documentos</p>
				<p class="pv2-help pacp-desc">Genera los comprobantes contables de los documentos pendientes del tipo de transacción elegido.</p>
				<form role="form" autocomplete="off" onsubmit="return false;">
					<div class="row">
						<div class="col-xs-12 form-group">
							<label class="pv2-label" for="txtListTransactionID">Transacción</label>
							<select name="txtListTransactionID" id="txtListTransactionID" class="select2">
									<option value="0" selected >TODAS</option>
									<?php
									if($objListTransaction)
									foreach($objListTransaction as $i){
										echo "<option value='".$i->transactionID."'>".$i->name."</option>";
									}
									?>
							</select>
						</div>
					</div>
					<div class="pacp-foot">
						<button type="button" id="btnAceptContabilizeDocument" class="btn btn-primary"><i class="icon16 i-calculate"></i> Contabilizar</button>
					</div>
				</form>
			</div>
		</div>
		<!-- /contabilizar documentos -->

		<!-- tipo de cambio -->
		<div class="col-xs-12 col-md-6">
			<div class="pv2-card">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-coin"></i></span> Tipo de cambio</p>
				<p class="pv2-help pacp-desc">Descarga y actualiza el tipo de cambio del BCN para el ciclo seleccionado.</p>
				<form role="form" autocomplete="off" onsubmit="return false;">
					<div class="row">
						<div class="col-xs-12 col-sm-6 form-group">
							<label class="pv2-label" for="txtTipoCambioPeriod">Periodo</label>
							<select name="txtTipoCambioPeriod" id="txtTipoCambioPeriod" class="select2">
									<option></option>
									<?php
									if($objListAccountingPeriod)
									foreach($objListAccountingPeriod as $i){
										echo "<option value='".$i->componentPeriodID."'>".helper_DateToSpanish($i->startOn,"Y")."</option>";
									}
									?>
							</select>
						</div>
						<div class="col-xs-12 col-sm-6 form-group">
							<label class="pv2-label" for="txtTipoCambioCicle">Ciclo</label>
							<select name="txtTipoCambioCicle" id="txtTipoCambioCicle" class="select2">
									<option></option>
							</select>
							<span class="pv2-help pacp-empty" id="lblTipoCambioSinCiclos" style="display:none">Sin ciclos abiertos en este periodo</span>
						</div>
					</div>
					<div class="pacp-foot">
						<button type="button" id="btnDownloadTipoCambio" class="btn btn-primary"><i class="icon16 i-cloud-download"></i> Actualizar tipo de cambio</button>
					</div>
				</form>
			</div>
		</div>
		<!-- /tipo de cambio -->

		<!-- notificaciones -->
		<div class="col-xs-12 col-md-6">
			<div class="pv2-card">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-bell"></i></span> Notificaciones</p>
				<p class="pv2-help pacp-desc">Ejecuta el proceso de notificación elegido o limpia las notificaciones de una etiqueta.</p>
				<form role="form" autocomplete="off" onsubmit="return false;">
					<div class="row">
						<div class="col-xs-12 form-group">
							<label class="pv2-label" for="txtNotificationProcess">Notificación</label>
							<select name="txtNotificationProcess" id="txtNotificationProcess" class="select2">
									<option value="" selected>SELECCIONAR</option>
									 <?php
									 if($objListTag)
									 {
										 foreach($objListTag as $itemTag)
										 {
											 echo '<option value="'.$itemTag->description.'"  >'.$itemTag->name.'</option>';
										 }
									 }
									 ?>
							</select>
						</div>
					</div>
					<div class="pacp-foot">
						<div class="btn-group dropup">
							<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><i class="icon16 i-remove"></i> Limpiar <span class="caret"></span></button>
							<ul class="dropdown-menu">
								 <?php
								 if($objListTag)
								 {
									 foreach($objListTag as $itemTag)
									 {
										 echo '<li class="clear_notification" data-notificationid="'.$itemTag->tagID.'"><a href="#">'.$itemTag->name.'</a></li>';
									 }
								 }
								 ?>
							</ul>
						</div>
						<button type="button" id="btnAceptNotificacion" class="btn btn-primary"><i class="icon16 i-bell"></i> Ejecutar</button>
					</div>
				</form>
			</div>
		</div>
		<!-- /notificaciones -->

	</div>
</div>
