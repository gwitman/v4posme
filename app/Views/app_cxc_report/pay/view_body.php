<div class="row"> 
	<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="icon"><i class="icon20 i-tags-2"></i></div> 
					<h4>REPORTE DE PAGOS</h4>										
					<a href="#" class="minimize"></a>
					<div class="w-right" style="margin-right:20px">
						<button id="print-btn-report" class="btn btn-primary btn-full tip" title="Ver Reporte" rel="panel-body"><i class="icon20 i-file gap-right0"></i></button>
					</div>
				</div>
				<!-- End .panel-heading -->
			
				<div class="panel-body">
					<form class="form-horizontal pad15 pad-bottom0" role="form">
						
						
						<div class="form-group">
							<label class="col-lg-7 control-label" for="selectFilter">CLIENTE</label>
							<div class="col-lg-5"> 
								<div class="col-lg-12">
									<select name="txtCustomerNumber" id="txtCustomerNumber" class="select2">
											<?php
											if($objListCustomer)
											foreach($objListCustomer as $i){
												echo "<option value='".$i->customerNumber."'>".$i->customerNumber." ".$i->firstName." ".$i->lastName."</option>";
											}
											?>
									</select>
								</div>												
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-7 control-label" for="txtReference">REFERENCIA</label>
							<div class="col-lg-5"> 
								<div class="col-lg-12">
									<input type="text" name="txtReference" id="txtReference" class="text" />
								</div>												
							</div>
						</div>

						
						
					</form>
				</div><!-- End .panel-body -->
			</div><!-- End .widget -->	
	<div>
<div>