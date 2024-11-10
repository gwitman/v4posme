					<div class="row"> 
						<div class="col-lg-6">
								<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="icon"><i class="icon20 i-tags-2"></i></div> 
                                        <h4>*) Cierre de Ciclos Contables</h4>
                                        <a href="#" class="minimize"></a>
                                    </div>
									<!-- End .panel-heading -->
                                
                                    <div class="panel-body">
                                        <form class="form-horizontal pad15 pad-bottom0" role="form">
											<div class="form-group">
												<label class="col-lg-2 control-label" for="selectFilter">Periodo</label>
												<div class="col-lg-8">
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
											</div>
											<div class="form-group">
												<label class="col-lg-2 control-label" for="selectFilter">Ciclo</label>
												<div class="col-lg-8">
													<select name="txtClosedCicle" id="txtClosedCicle" class="select2">
															<option></option>
													</select>
												</div>
											</div>
													
													
                                            <div class="form-group">
                                                <button type="button" id="btnAceptClosedCycle" class="btn btn-primary pull-right">Aceptar</button>
                                            </div><!-- End .form-group  -->
                                        </form>
                                    </div><!-- End .panel-body -->
                                </div><!-- End .widget -->	
						</div>
                        <div  class="col-lg-6">                        	
                    		<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="icon"><i class="icon20 i-tags-2"></i></div> 
                                        <h4>*) Mayorizar Ciclos Contables</h4>
                                        <a href="#" class="minimize"></a>
                                    </div>
									<!-- End .panel-heading -->
                                
                                    <div class="panel-body">
                                        <form class="form-horizontal pad15 pad-bottom0" role="form">
											<div class="form-group">
												<label class="col-lg-2 control-label" for="selectFilter">Periodo</label>
												<div class="col-lg-8">
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
											</div>
											<div class="form-group">
												<label class="col-lg-2 control-label" for="selectFilter">Ciclo</label>
												<div class="col-lg-8">
													<select name="txtMayorizateCicle" id="txtMayorizateCicle" class="select2">
															<option></option>															
													</select>
												</div>
											</div>
													
													
                                            <div class="form-group">
                                                <button type="button" id="btnAceptMayorizateCycle" class="btn btn-primary pull-right">Aceptar</button>
                                            </div><!-- End .form-group  -->
                                        </form>
                                    </div><!-- End .panel-body -->
                                </div><!-- End .widget -->								
                        </div>                        
                    </div>
					
					<div class="row"> 
						<div class="col-lg-6">								
								<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="icon"><i class="icon20 i-tags-2"></i></div> 
                                        <h4>*) Contabilizar Documentos</h4>
                                        <a href="#" class="minimize"></a>
                                    </div>
									<!-- End .panel-heading -->
                                
                                    <div class="panel-body">
                                        <form class="form-horizontal pad15 pad-bottom0" role="form">
											<div class="form-group">
												<label class="col-lg-2 control-label" for="selectFilter">Transaccion</label>
												<div class="col-lg-8">
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
													
													
                                            <div class="form-group">
                                                <button type="button" id="btnAceptContabilizeDocument" class="btn btn-primary pull-right">Aceptar</button>
                                            </div><!-- End .form-group  -->
                                        </form>
                                    </div><!-- End .panel-body -->
                                </div><!-- End .widget -->			
						</div>
                        <div  class="col-lg-6">     
							<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="icon"><i class="icon20 i-tags-2"></i></div> 
                                        <h4>*) Actualizar Tipo de Cambio BCN</h4>
                                        <a href="#" class="minimize"></a>
                                    </div>
									<!-- End .panel-heading -->
                                
                                    <div class="panel-body">
                                        <form class="form-horizontal pad15 pad-bottom0" role="form">
											<div class="form-group">
												<label class="col-lg-2 control-label" for="selectFilter">Periodo</label>
												<div class="col-lg-8">
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
											</div>
											<div class="form-group">
												<label class="col-lg-2 control-label" for="selectFilter">Ciclo</label>
												<div class="col-lg-8">
													<select name="txtTipoCambioCicle" id="txtTipoCambioCicle" class="select2">
															<option></option>															
													</select>
												</div>
											</div>
													
													
                                            <div class="form-group">
                                                <button type="button" id="btnDownloadTipoCambio" class="btn btn-primary pull-right">Aceptar</button>
                                            </div><!-- End .form-group  -->
                                        </form>
                                    </div><!-- End .panel-body -->
                                </div><!-- End .widget -->								
						</div>
					</div>
				
					<div class="row"> 
						<div class="col-lg-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									<div class="icon"><i class="icon20 i-tags-2"></i></div> 
									<h4>*) Procesar Notificaciones</h4>
									<a href="#" class="minimize"></a>
								</div>
								<!-- End .panel-heading -->
							
								<div class="panel-body">
									<form class="form-horizontal pad15 pad-bottom0" role="form">
										<div class="form-group">
											<label class="col-lg-2 control-label" for="selectFilter">Seleccionar</label>
											<div class="col-lg-8">
												<select name="txtNotificationProcess" id="txtNotificationProcess" class="select2">
														<option value="" selected>SELECCIONAR</option>
														 <?php 
														 $i = 0 ;
														 if($objListTag)
														 {
															 foreach($objListTag as $itemTag)
															 {
																 if($i == 0)
																 echo '<option value="'.$itemTag->description.'"  >'.$itemTag->name.'</option>';
																 else 
																 echo '<option value="'.$itemTag->description.'"  >'.$itemTag->name.'</option>';
															 
																 $i++;
															 }
														 }
														 ?>
												</select>
											</div>
										</div>
												
										<div class="btn-group">
											<button class="btn btn-warning dropdown-toggle pull-right" data-toggle="dropdown">Limpiar <span class="caret"></span></button>
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
											<button type="button" id="btnAceptNotificacion" class="btn btn-primary pull-right">Aceptar</button>
										</div><!-- /btn-group -->
									</form>
								</div><!-- End .panel-body -->
							</div><!-- End .widget -->		
						</div>
					</div>