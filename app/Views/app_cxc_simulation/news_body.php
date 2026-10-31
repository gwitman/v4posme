				<div class="simv2">

					<!-- encabezado -->
					<div class="simv2-card simv2-header">
						<div class="simv2-header-info">
							<div class="simv2-header-icon"><i class="icon20 i-calculate"></i></div>
							<div>
								<h1 class="simv2-header-title">Simulador de crédito</h1>
								<p class="simv2-header-sub">Calcule la tabla de amortización y guarde las condiciones en la línea de crédito del cliente</p>
							</div>
						</div>
						<div class="simv2-actions">
							<a href="<?php echo  base_url()."/app_cxc_customer/edit/companyID/".$objCustomerDefault->companyID."/branchID/".$objCustomerDefault->branchID."/entityID/".$objCustomerDefault->entityID ;  ?>" class="btn btn-default <?php echo getBehavio($company->type,"app_cxc_simulation","showBtnIrCustomerOfSimulator","hidden"); ?>" id="btnRegresarCliente"><i class="icon16 i-user"></i> Ir a cliente</a>
							<a href="#" class="btn btn-primary" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
						</div>
					</div>
					<!-- /encabezado -->

					<form id="form-new-invoice" name="form-new-invoice" class="form-horizontal" role="form">
					<div class="row">

						<!-- parametros -->
						<div class="col-md-8">
							<div class="simv2-card">
								<h4 class="simv2-card-title"><span class="simv2-ico"><i class="icon16 i-cog"></i></span> Parámetros</h4>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="simv2-label" for="txtAmount">Monto</label>
											<input class="form-control txt-numeric" type="text" inputmode="decimal" name="txtAmount" id="txtAmount" value="" placeholder="0.00" autocomplete="off">
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="simv2-label" for="txtInterestYear"><?php echo $objParameterCORE_VIEW_CUSTOM_LABEL_INTERES_ANUAL; ?></label>
											<div class="simv2-unit">
												<input class="form-control txt-numeric" type="text" inputmode="decimal" name="txtInterestYear" id="txtInterestYear" value="" placeholder="0" autocomplete="off">
												<span>%</span>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="simv2-label" for="txtNumberPay"># Pagos</label>
											<input class="form-control txt-numeric" type="text" inputmode="decimal" name="txtNumberPay" id="txtNumberPay" value="" placeholder="Ej. 12 o 50.666" title="Puede usar punto o coma como separador decimal" autocomplete="off">
											<span class="simv2-help">Admite decimales: la última cuota se calcula en forma proporcional.</span>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="simv2-label" for="txtFrecuencyID">Frecuencia</label>
											<select name="txtFrecuencyID" id="txtFrecuencyID" class="form-control">
												<?php
													if($objListPay)
													foreach($objListPay as $ws){
														echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="simv2-label" for="txtPlanID">Tipo de plan</label>
											<select name="txtPlanID" id="txtPlanID" class="form-control">
												<?php
													if($objListTypeAmortization)
													foreach($objListTypeAmortization as $ws){
														echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="simv2-label" for="txtDayExcluded">Días excluidos</label>
											<select name="txtDayExcluded" id="txtDayExcluded" class="form-control">
												<?php
													$indec =  0;
													if($objListDayExcluded)
													foreach($objListDayExcluded as $ws){
														if($indec == 0 )
														echo "<option value='".$ws->catalogItemID."' selected  >".$ws->name."</option>";
														else
														echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";

														$indec++;
													}
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="simv2-calc">
									<a href="#" class="btn" id="btnCalculate"><i class="icon16 i-calculate"></i> Calcular</a>
								</div>
							</div>
						</div>
						<!-- /parametros -->

						<!-- cliente -->
						<div class="col-md-4">
							<div class="simv2-card">
								<h4 class="simv2-card-title"><span class="simv2-ico"><i class="icon16 i-user"></i></span> Cliente y línea</h4>
								<div class="form-group">
									<label class="simv2-label" for="txtCustomerDescription">Entidad</label>
									<input type="hidden" id="txtCustomerID" name="txtCustomerID" value="<?php echo $objCustomerDefault->entityID;  ?>">
									<input class="form-control" readonly id="txtCustomerDescription" type="text" value="<?php echo $objNaturalDefault != null ? strtoupper($entityNumber . " ". $objNaturalDefault->firstName . " ". $objNaturalDefault->lastName ) : strtoupper($entityNumber." ".$objLegalDefault->comercialName); ?>">
									<div class="simv2-actions" style="margin-top:6px">
										<button class="btn btn-default btn-sm" type="button" id="btnClearCustomer"><i aria-hidden="true" class="i-undo-2"></i> Limpiar</button>
										<button class="btn btn-primary btn-sm" type="button" id="btnSearchCustomer"><i aria-hidden="true" class="i-search-5"></i> Buscar</button>
									</div>
								</div>
								<div class="form-group">
									<label class="simv2-label" for="txtCustomerCreditLineID">Línea de crédito</label>
									<select name="txtCustomerCreditLineID" id="txtCustomerCreditLineID" class="form-control">
									</select>
									<span class="simv2-help">Al guardar, las condiciones simuladas se aplican a esta línea.</span>
								</div>
							</div>
						</div>
						<!-- /cliente -->

					</div>

					<!-- resumen -->
					<div class="simv2-kpis">
						<div class="simv2-kpi k-capital">
							<div class="simv2-kpi-icon"><i class="icon16 i-coins"></i></div>
							<div class="simv2-kpi-body">
								<p class="simv2-kpi-label">Total capital</p>
								<input type="text" id="txtCapitalTotal" name="txtCapitalTotal" readonly class="simv2-kpi-value" value="0.00"/>
							</div>
						</div>
						<div class="simv2-kpi k-interest">
							<div class="simv2-kpi-icon"><i class="icon16 i-stats"></i></div>
							<div class="simv2-kpi-body">
								<p class="simv2-kpi-label">Total interés</p>
								<input type="text" id="txtInterestTotal" name="txtInterestTotal" readonly class="simv2-kpi-value" value="0.00"/>
							</div>
						</div>
						<div class="simv2-kpi k-total">
							<div class="simv2-kpi-icon"><i class="icon16 i-checkmark-circle"></i></div>
							<div class="simv2-kpi-body">
								<p class="simv2-kpi-label">Total a pagar</p>
								<input type="text" id="txtTotal" name="txtTotal" readonly class="simv2-kpi-value" value="0.00"/>
							</div>
						</div>
						<div class="simv2-kpi k-count">
							<div class="simv2-kpi-icon"><i class="icon16 i-list"></i></div>
							<div class="simv2-kpi-body">
								<p class="simv2-kpi-label">Número de cuotas</p>
								<span class="simv2-kpi-value" id="simv2KpiCount">0</span>
							</div>
						</div>
						<div class="simv2-kpi k-cuota">
							<div class="simv2-kpi-icon"><i class="icon16 i-calendar"></i></div>
							<div class="simv2-kpi-body">
								<p class="simv2-kpi-label">Cuota regular</p>
								<span class="simv2-kpi-value" id="simv2KpiCuota">0.00</span>
							</div>
						</div>
					</div>
					<!-- /resumen -->

					<!-- tabla de amortizacion -->
					<div class="simv2-card">
						<div class="simv2-detail-head">
							<h4 class="simv2-card-title"><span class="simv2-ico"><i class="icon16 i-table"></i></span> Tabla de amortización <span class="simv2-count" id="simv2DetailCount">0</span></h4>
							<span class="simv2-legend hidden" id="simv2Legend"><span class="simv2-tag" style="margin-left:0">proporcional</span> última cuota por la fracción del plazo</span>
						</div>
						<div class="simv2-table-wrap hidden" id="simv2TableWrap">
							<table id="tb_transaction_master_detail" class="simv2-table">
								<thead>
									<tr>
										<th class="simv2-col-num">Número</th>
										<th class="simv2-col-date">Fecha</th>
										<th>Inicial</th>
										<th>Interés</th>
										<th>Capital</th>
										<th>Cuota</th>
										<th>Final</th>
									</tr>
								</thead>
								<tbody id="body_tb_transaction_master_detail">
								</tbody>
							</table>
						</div>
						<div class="simv2-empty" id="simv2Empty">
							<i class="icon20 i-calculate"></i>
							<span id="simv2EmptyText">Ingrese los parámetros y presione <b>Calcular</b> para ver la tabla de amortización.</span>
						</div>
					</div>
					<!-- /tabla de amortizacion -->

					</form>
				</div>
