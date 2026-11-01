<div class="main">
		<section id="content">
			<div class="wrapper">
				<div class="container-fluid" id="main_content">
					<div class="pv2 pinv pinv-popup" style="padding:12px">
						<form id="form-new-rol" autocomplete="off">

							<!-- lote y vencimiento -->
							<div class="pv2-card">
								<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-calendar-4"></i></span> Lote y vencimiento</p>
								<div class="row">
									<div class="col-xs-12 col-sm-6 form-group">
										<label class="pv2-label" for="txtVencimiento">Vencimiento</label>
										<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
											<input size="16"  class="form-control" type="text" name="txtVencimiento" id="txtVencimiento" value="<?php echo $vencimiento;?>"  readonly="readonly"  >
											<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
										</div>
									</div>
									<div class="col-xs-12 col-sm-6 form-group">
										<label class="pv2-label" for="txtLote">Lote</label>
										<div class="input-group">
											<span class="input-group-addon">@</span>
											<input class="form-control" id="txtLote" type="text" placeholder="" value="<?php echo $lote;?>">
										</div>
									</div>
								</div>
							</div>

							<!-- precios -->
							<div class="pv2-card">
								<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-coin"></i></span> Precios</p>
								<div class="row">
									<div class="col-xs-12 col-sm-6 form-group">
										<label class="pv2-label" for="txtPrecio1">Precio por mayor</label>
										<div class="input-group">
											<span class="input-group-addon">$</span>
											<input class="form-control pv2-num" id="txtPrecio1" type="text" placeholder="" value="<?php echo $precio1;?>">
										</div>
									</div>
									<div class="col-xs-12 col-sm-6 form-group">
										<label class="pv2-label" for="txtPrecio2">Precio especial</label>
										<div class="input-group">
											<span class="input-group-addon">$</span>
											<input class="form-control pv2-num" id="txtPrecio2" type="text" placeholder="" value="<?php echo $precio2;?>">
										</div>
									</div>
									<div class="col-xs-12 form-group">
										<label class="pv2-label" for="txtReference4TransactionMasterDetail">Expandir codigo</label>
										<input class="form-control"  type="text"  name="txtReference4TransactionMasterDetail" id="txtReference4TransactionMasterDetail" value="<?php echo $txtReference4TransactionMasterDetail;?>">
									</div>
								</div>
							</div>

							<!-- impuestos -->
							<div class="pv2-card">
								<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-calculate"></i></span> Impuestos por unidad</p>
								<div class="row">
									<div class="col-xs-12 col-sm-6 form-group">
										<label class="pv2-label" for="txtIva">IVA</label>
										<label class="pinv-check" for="txtCheckIva">
											<input type="checkbox"   name="txtCheckIva" id="txtCheckIva" value="1" > Calcular IVA sobre el costo
										</label>
										<input type="hidden" id="txtCosto"  value="<?php echo $costo;?>" />
										<div class="input-group">
											<span class="input-group-addon">$</span>
											<input class="form-control pv2-num" id="txtIva" type="text" placeholder="" value="<?php echo $iva;?>" readonly="true" >
										</div>
									</div>
									<div class="col-xs-12 col-sm-6 form-group">
										<label class="pv2-label" for="txtIsc">ISC</label>
										<div class="input-group">
											<span class="input-group-addon">$</span>
											<input class="form-control pv2-num" id="txtIsc" type="text" placeholder="" value="<?php echo $isc;?>">
										</div>
									</div>
								</div>
							</div>

						</form>
					</div>
				</div>
			</div>
		</section>

</div>
