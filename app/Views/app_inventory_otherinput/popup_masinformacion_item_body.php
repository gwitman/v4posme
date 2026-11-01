<div class="main pv2 pinv pinv-popup">
		<section id="content">
			<div class="wrapper">
				<div class="container-fluid" id="main_content">
					<form id="form-new-rol" autocomplete="off">
						<div class="pv2-card">
							<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-calendar-4"></i></span> Lote y vencimiento</p>
							<div class="row">
								<div class="col-xs-12 col-sm-6 form-group">
									<label class="pv2-label" for="txtVencimiento">Vencimiento</label>
									<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
										<input size="16"  class="form-control" type="text" name="txtVencimiento" id="txtVencimiento" readonly="true" value="<?php echo $vencimiento;?>">
										<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
									</div>
									<span class="pv2-help">Toca el campo para elegir la fecha.</span>
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
					</form>
				</div>
			</div>
		</section>
</div>
