					<!--Botonera-->
					<div class="row">
						<div id="email" class="col-lg-12">
							<div class="email-bar" style="border-left:1px solid #c9c9c9">
								<div class="btn-group pull-right">
									<a href="#" class="btn btn-primary" id="btnDownload"><i class="icon16 i-checkmark-4"></i> Descargar</a>
									<a href="#" class="btn btn-warning" id="btnUpload"><i class="icon16 i-checkmark-4"></i> Subir Datos</a>
									<a href="#" class="btn btn-success" id="btnProcess"><i class="icon16 i-checkmark-4"></i> Procesar</a>
								</div>
							</div>
						</div>
					</div>
					<!--Botonera-->


					<div class="row">
						<div class="col-lg-12">
							<div class="panel panel-default">

								<!-- titulo del movimiento-->
								<div class="panel-heading">
									<div class="icon"><i class="icon20 i-file"></i></div>
									<h4>NUMERO:#<span class="invoice-num">00000000</span></h4>
								</div>
								<!-- /titulo del movimiento-->

								<!-- formulario -->
								<form id="form-expired-date" name="form-expired-date" class="form-horizontal" role="form">
									<div class="panel-body printArea">

										<!--tab menu-->
										<ul id="myTab" class="nav nav-tabs">
											<li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
										</ul>
										<!--tab menu-->

										<!--tab content-->
										<div class="tab-content">
											<!--tab content general-->
											<div class="tab-pane fade in active" id="home">
												<div class="row">
													<div class="col-lg-6">

														<div class="form-group">
															<label class="col-lg-2 control-label" for="selectFilter">Bodega</label>
															<div class="col-lg-8">
																<select name="txtWarehouseID" id="txtWarehouseID" class="select2">
																	<option></option>
																	<?php
																	if ($objListWarehouse)
																		foreach ($objListWarehouse as $i) {
																			echo "<option value='" . $i->warehouseID . "'>" . $i->name . "</option>";
																		}
																	?>
																</select>
															</div>
														</div>

													</div>
												</div>
											</div>
										</div>
										<!--tab content-->
									</div>
								</form>
								<!-- formulario -->
							</div>
						</div>
					</div>