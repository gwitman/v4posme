					<div class="row"> 
						<div class="col-lg-12">
								<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="icon"><i class="icon20 i-tags-2"></i></div> 
                                        <h4>ARQUEO DIARIO</h4>										
                                        <a href="#" class="minimize"></a>
										<div class="w-right" style="margin-right:20px">
											<button id="print-btn-report" class="btn btn-primary btn-full tip" title="Ver Reporte" rel="panel-body"><i class="icon20 i-file gap-right0"></i></button>
										</div>
                                    </div>
									<!-- End .panel-heading -->
                                
                                    <div class="panel-body">
                                        <form class="form-horizontal pad15 pad-bottom0" role="form">											
											<div class="form-group">
												<label class="col-lg-6 control-label" for="selectFilter">Fecha Inicial y Final</label>
												<div class="col-lg-6"> 
													<div class="col-lg-6">
															<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
																<input size="16"  class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="2014-01-30">
																<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
															</div>
													</div>
													<div class="col-lg-6">
															<div id="datepicker_v2" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
																<input size="16"  class="form-control" type="text" name="txtEndOn" id="txtEndOn" value="2014-01-30">
																<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
															</div>
													</div>													
												</div>
											</div>
											
											
											
											
											<div class="form-group">
												<label class="col-lg-6 control-label" for="selectFilter">Hora Inicial y Final</label>
												<div class="col-lg-6"> 
													<div class="col-lg-6">
															<div id="hourdatepicker" class="input-group "  >
																<input size="16"  class="form-control" type="text" name="txtHourStartOn" id="txtHourStartOn" value="0">
																<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
															</div>
													</div>
													<div class="col-lg-6">
															<div id="hourdatepicker_v2" class="input-group "  >
																<input size="16"  class="form-control" type="text" name="txtHourEndOn" id="txtHourEndOn" value="23">
																<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
															</div>
													</div>													
												</div>
											</div>
											
											
											
											
											
											<div class="form-group <?php echo getBehavio($objCompany->type,"app_box_report","dailyTownUserDiv","") ?>">
												<label class="col-lg-6 control-label" for="selectFilter">Usuario</label>
												<div class="col-lg-6"> 
													<div class="col-lg-6">
													</div>
													<div class="col-lg-6">
														<select name="txtUserID" id="txtUserID" class="select2">
																<option value="0">TODOS</option>
																<?php
																if($objListaUsuarios)
																foreach($objListaUsuarios as $i){
																	echo "<option value='".$i->userID."'>".$i->nickname."</option>";
																}
																?>
														</select>
													</div>													
												</div>
											</div>
											
											<div class="form-group">
												<label class="col-lg-6 control-label" for="selectFilter">Sucursales</label>
												<div class="col-lg-6"> 
													<div class="col-lg-6">
													</div>
													<div class="col-lg-6">
														<select name="txtBranchID" id="txtBranchID" class="select2">
																<option value="0">TODOS</option>
																<?php
																if($objListBranch)
																foreach($objListBranch as $i){
																	echo "<option value='".$i->branchID."'>".$i->name."</option>";
																}
																?>
														</select>
													</div>													
												</div>
											</div>
											
											<div class="form-group <?php echo getBehavio($objCompany->type,"app_box_report","dailyTownShowAmountDiv","") ?>  ">
												<label class="col-lg-6 control-label" for="selectFilter">Mostrar Montos unicamente</label>
												<div class="col-lg-6"> 
													<div class="col-lg-6">
													</div>
													<div class="col-lg-6">
														<select name="txtShowSumaryAmount" id="txtShowSumaryAmount" class="select2">
																<option value="0">No</option>
																<option value="1">Si</option>																
														</select>
													</div>													
												</div>
											</div>
											
											<div class="form-group  <?php echo getBehavio($objCompany->type,"app_box_report","dailyTownConceptIngresosDiv","") ?>">
													<label class="col-lg-6 control-label" for="normal">Conceptos de ingresos</label> 
													<div class="col-lg-6">
														<hr/>
													</div>
											</div>
											
											<?php 
											foreach($objTipoMovementInputCash as  $value)
											{
												?>
												<div class="form-group <?php echo getBehavio($objCompany->type,"app_box_report","dailyTownConceptIngresosDiv","") ?> ">
														<label class="col-lg-6 control-label" for="normal"><?php echo $value->name; ?></label> 
														<div class="col-lg-6">
															<input type="checkbox"  class="areas"   name="txtIsServices" id="txtIsServices" value="<?php echo $value->catalogItemID;   ?>"    >
														</div>
												</div>
												<?php 
											}
											?>
										
											<div class="form-group <?php echo getBehavio($objCompany->type,"app_box_report","dailyTownConceptEgresosDiv","") ?>">
													<label class="col-lg-6 control-label" for="normal">Conceptos de egreso</label> 
													<div class="col-lg-6">
														<hr/>
													</div>
											</div>
											
											<?php 
											foreach($objTipoMovementOutputCash as  $value)
											{
												?>
												<div class="form-group <?php echo getBehavio($objCompany->type,"app_box_report","dailyTownConceptEgresosDiv","") ?>">
														<label class="col-lg-6 control-label" for="normal"><?php echo $value->name; ?></label> 
														<div class="col-lg-6">
															<input type="checkbox"  class="areas"  name="txtIsServices" id="txtIsServices" value="<?php echo $value->catalogItemID;   ?>"     >
														</div>
												</div>
												<?php 
											}
											?>
											
											
											<div class="form-group <?php echo getBehavio($objCompany->type,"app_box_report","dailyTownNoneConceptDiv","") ?> ">
													<label class="col-lg-6 control-label" for="normal">Ningun concepto</label> 
													<div class="col-lg-6">
														<input type="checkbox"  class="areas"   name="txtIsServices" id="txtIsServices" value="0"    >
													</div>
											</div>
											
												
											<div class="form-group <?php echo getBehavio($objCompany->type,"app_box_report","dailyTownCategoryItemDiv","") ?>">
													<label class="col-lg-6 control-label" for="normal">Categoria de productos</label> 
													<div class="col-lg-6">
														<hr/>
													</div>
											</div>
											
											<?php 
											foreach($objListCategoryItem as  $value)
											{
												?>
												<div class="form-group <?php echo getBehavio($objCompany->type,"app_box_report","dailyTownCategoryItemDiv","") ?>">
														<label class="col-lg-6 control-label" for="normal"><?php echo $value->name; ?></label> 
														<div class="col-lg-6">
															<input type="checkbox"  class="categorias"   name="txtCategoryItem" id="txtCategoryItem" value="<?php echo $value->inventoryCategoryID;   ?>"     >
														</div>
												</div>
												<?php 
											}
											?>
										
											<div class="form-group <?php echo getBehavio($objCompany->type,"app_box_report","dailyTownCategoryItemDiv","") ?>">
													<label class="col-lg-6 control-label" for="normal">Ninguna categoria</label> 
													<div class="col-lg-6">
														<input type="checkbox"  class="categorias"   name="txtCategoryItem" id="txtCategoryItem" value="0"    >
													</div>
											</div>
											
											
                                        </form>
                                    </div><!-- End .panel-body -->
                                </div><!-- End .widget -->	
						<div>
					<div>