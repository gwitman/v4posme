<div class="row"> 
	<div id="email" class="col-lg-12">
	
		<!-- botonera -->
		<div class="email-bar" style="border-left:1px solid #c9c9c9">                                
			<div class="btn-group pull-right">     
				<a href="<?php echo base_url(); ?>/app_public_catalog/add" class="btn btn-success" id="btnNuevo"><i class="icon16 i-checkmark-4"></i>Nuevo</a>						
				<a href="<?php echo base_url(); ?>/app_public_catalog/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
				<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>									
				<a href="#" class="btn btn-primary" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
				<a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
			</div>
		</div> 
		<!-- /botonera -->
	</div>
	<!-- End #email  -->
</div>
<!-- End .row-fluid  -->
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
					
			<!-- titulo de comprobante-->
			<div class="panel-heading">
					<div class="icon"><i class="icon20 i-file"></i></div> 
					<h4>Catalogo :#<span class="invoice-num"><?php echo $objPublicCatalog->publicCatalogID; ?></span></h4>
			</div>
			<!-- /titulo de comprobante-->
			
			<!-- body -->	
			<form id="form-new-invoice" name="form-new-invoice" class="form-horizontal" role="form">
			<div class="panel-body printArea"> 
			
				<ul id="myTab" class="nav nav-tabs">
					<li class="active">
						<a href="#home" data-toggle="tab">Informacion</a>
					</li>
					
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane fade in active" id="home">	
						<div class="row">										
							<div class="col-lg-6">
								
								
									<input type="hidden" name="txtPublicCatalogID" value="<?php echo $objPublicCatalog->publicCatalogID; ?>">
									
									<div class="form-group">
											<label class="col-lg-4 control-label" for="normal">Nombre</label>
											<div class="col-lg-8">
												<input class="form-control"   type="text" name="txtName" id="txtName" value="<?php echo $objPublicCatalog->name; ?>">
											</div>
									</div>
									
									<div class="form-group">
											<label class="col-lg-4 control-label" for="normal">Sistema</label>
											<div class="col-lg-8">
												<input class="form-control"   type="text" name="txtSystemName" id="txtSystemName" value="<?php echo $objPublicCatalog->systemName; ?>">
											</div>
									</div>
									

									
								
							</div>
							<div class="col-lg-6">
								<div class="form-group">
										<label class="col-lg-2 control-label" for="selectFilter">Estado</label>
										<div class="col-lg-8">
											<select name="txtStatusID" id="txtStatusID" class="select2">
													<option></option>																
													<?php
													if($objListWorkflowStage)
													foreach($objListWorkflowStage as $ws){
														
														if($ws->workflowStageID == $objPublicCatalog->statusID)
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
					</div>					
				</div>    
		
				<br/>
				<a href="#" class="btn btn-flat btn-info" id="btnNewShare" >Agregar</a>
				<a href="#" class="btn btn-flat btn-danger" id="btnDeleteShare" >Eliminar</a>	

									
				<div class="row">
					<div class="col-lg-12">
						<h3>Detalle:</h3>
						<table id="tb_transaction_master_detail" class="table table-bordered">
							<thead>
								<th></th>								
								<th width="23%" >Configuración 1</th>
								<th width="23%" >Configuración 2</th>
								<th width="23%" >Configuración 3</th>
								<th width="23%" >Configuración 4</th>								
								
							</thead>
							<tbody id="body_tb_transaction_master_detail">
								<?php
									if($objPublicCatalogDetail)
									foreach($objPublicCatalogDetail as $key => $value)
									{
									?>
									<tr class="row_razon">										
										<td>
											<input type="checkbox"  class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
											
											<input type="hidden" name="txtPublicCatalogDetail_publicCatalogID[]" id="txtPublicCatalogDetail_publicCatalogID"  value="<?php echo $value->publicCatalogID; ?>" />
											<input type="hidden" name="txtPublicCatalogDetail_publicCatalogDetailID[]" id="txtPublicCatalogDetail_publicCatalogDetailID" value="<?php echo $value->publicCatalogDetailID; ?>" />
											<input type="hidden" name="txtPublicCatalogDetail_parentPublicCatalogDetailID[]" id="txtPublicCatalogDetail_parentPublicCatalogDetailID" value="<?php echo $value->parentCatalogDetailID; ?>" />
										</td>
										
										
										<td>
											
											<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Indicadores</label>
													<div class="col-lg-8">
														<input class="form-control txtPublicCatalogDetail_Name"   type="text" name="txtPublicCatalogDetail_Name[]" id="txtPublicCatalogDetail_Name" value="<?php echo $value->name; ?>">
													</div>
											</div>
											
											<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Valores</label>
													<div class="col-lg-8">
														<input class="form-control txtPublicCatalogDetail_Display"   type="text" name="txtPublicCatalogDetail_Display[]" id="txtPublicCatalogDetail_Display" value="<?php echo $value->display; ?>">
													</div>
											</div>
											
											<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Consjunto</label>
													<div class="col-lg-8">
														<input class="form-control txtPublicCatalogDetail_Reference4"   type="text" name="txtPublicCatalogDetail_Reference4[]" id="txtPublicCatalogDetail_Reference4" value="<?php echo $value->reference4; ?>">
													</div>
											</div>
											
										</td>
										
										<td>
											
											<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Grupo</label>
													<div class="col-lg-8">
														<input class="form-control txtPublicCatalogDetail_Description"   type="text" name="txtPublicCatalogDetail_Description[]" id="txtPublicCatalogDetail_Description" value="<?php echo $value->description; ?>">
													</div>
											</div>
											
											<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Sub grupo</label>
													<div class="col-lg-8">
														<input class="form-control txtPublicCatalogDetail_Reference1"   type="text" name="txtPublicCatalogDetail_Reference1[]" id="txtPublicCatalogDetail_Reference1" value="<?php echo $value->reference1; ?>">
													</div>
											</div>
											
											
											
											
										</td>
										
										<td>											
											
											<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Edad</label>
													<div class="col-lg-8">
														<input class="form-control txtPublicCatalogDetail_Reference3"   type="text" name="txtPublicCatalogDetail_Reference3[]" id="txtPublicCatalogDetail_Reference3" value="<?php echo $value->reference3; ?>">
													</div>
											</div>
											
											<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Sexo</label>
													<div class="col-lg-8">
														<input class="form-control txtPublicCatalogDetail_Reference2"   type="text" name="txtPublicCatalogDetail_Reference2[]" id="txtPublicCatalogDetail_Reference2" value="<?php echo $value->reference2; ?>">
													</div>
											</div>
											
											<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Parent</label>
													<div class="col-lg-8">
														<input class="form-control txtPublicCatalogDetail_ParentName"   type="text" name="txtPublicCatalogDetail_ParentName[]" id="txtPublicCatalogDetail_ParentName" value="<?php echo $value->parentName; ?>">
													</div>
											</div>
												
										</td>
										
										<td>											
											
											<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Sequencia</label>
													<div class="col-lg-8">
														<input class="form-control txtPublicCatalogDetail_Sequence"   type="text" name="txtPublicCatalogDetail_Sequence[]" id="txtPublicCatalogDetail_Sequence" value="<?php echo $value->sequence; ?>">
													</div>
											</div>
											
											<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Ratio</label>
													<div class="col-lg-8">
														<input class="form-control txtPublicCatalogDetail_Ratio"   type="text" name="txtPublicCatalogDetail_Ratio[]" id="txtPublicCatalogDetail_Ratio" value="<?php echo $value->ratio; ?>">
													</div>
											</div>
											
											<div class="form-group">
													<label class="col-lg-4 control-label" for="normal">Flavor</label>
													<div class="col-lg-8">
														<input class="form-control txtPublicCatalogDetail_Flavor"   type="text" name="txtPublicCatalogDetail_Flavor[]" id="txtPublicCatalogDetail_Flavor" value="<?php echo $value->flavorID; ?>">
													</div>
											</div>
												
										</td>
										
										
										
									</tr>
									<?php
									}
								?>
							</tbody>
						</table>
						
					</div><!-- End .col-lg-12  --> 
				</div><!-- End .row-fluid  -->
				
				
					
			</div>
			</form>
		

		</div>
	</div>
</div>
<script type="text/template"  id="tmpl_row_document">
	<tr class="row_razon">
		<td>
			<input type="checkbox"  class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
			
			<input type="hidden" name="txtPublicCatalogDetail_publicCatalogID[]" id="txtPublicCatalogDetail_publicCatalogID"   />
			<input type="hidden" name="txtPublicCatalogDetail_publicCatalogDetailID[]" id="txtPublicCatalogDetail_publicCatalogDetailID" />
			<input type="hidden" name="txtPublicCatalogDetail_parentPublicCatalogDetailID[]" id="txtPublicCatalogDetail_parentPublicCatalogDetailID" />
		</td>
		
		<td>
											
			<div class="form-group">
					<label class="col-lg-4 control-label" for="normal">Indicadores</label>
					<div class="col-lg-8">
						<input class="form-control txtPublicCatalogDetail_Name"   type="text" name="txtPublicCatalogDetail_Name[]" id="txtPublicCatalogDetail_Name" value="">
					</div>
			</div>
			
			<div class="form-group">
					<label class="col-lg-4 control-label" for="normal">Valores</label>
					<div class="col-lg-8">
						<input class="form-control txtPublicCatalogDetail_Display"   type="text" name="txtPublicCatalogDetail_Display[]" id="txtPublicCatalogDetail_Display" value="">
					</div>
			</div>
			
			<div class="form-group">
					<label class="col-lg-4 control-label" for="normal">Consjunto</label>
					<div class="col-lg-8">
						<input class="form-control txtPublicCatalogDetail_Reference4"   type="text" name="txtPublicCatalogDetail_Reference4[]" id="txtPublicCatalogDetail_Reference4" value="">
					</div>
			</div>
			
		</td>
		
		<td>
			
			<div class="form-group">
					<label class="col-lg-4 control-label" for="normal">Grupo</label>
					<div class="col-lg-8">
						<input class="form-control txtPublicCatalogDetail_Description"   type="text" name="txtPublicCatalogDetail_Description[]" id="txtPublicCatalogDetail_Description" value="">
					</div>
			</div>
			
			<div class="form-group">
					<label class="col-lg-4 control-label" for="normal">Sub grupo</label>
					<div class="col-lg-8">
						<input class="form-control txtPublicCatalogDetail_Reference1"   type="text" name="txtPublicCatalogDetail_Reference1[]" id="txtPublicCatalogDetail_Reference1" value="">
					</div>
			</div>
			
			
			
			
		</td>
		
		<td>											
			
			<div class="form-group">
					<label class="col-lg-4 control-label" for="normal">Edad</label>
					<div class="col-lg-8">
						<input class="form-control txtPublicCatalogDetail_Reference3"   type="text" name="txtPublicCatalogDetail_Reference3[]" id="txtPublicCatalogDetail_Reference3" value="">
					</div>
			</div>
			
			<div class="form-group">
					<label class="col-lg-4 control-label" for="normal">Sexo</label>
					<div class="col-lg-8">
						<input class="form-control txtPublicCatalogDetail_Reference2"   type="text" name="txtPublicCatalogDetail_Reference2[]" id="txtPublicCatalogDetail_Reference2" value="">
					</div>
			</div>
			
			<div class="form-group">
					<label class="col-lg-4 control-label" for="normal">Parent</label>
					<div class="col-lg-8">
						<input class="form-control txtPublicCatalogDetail_ParentName"   type="text" name="txtPublicCatalogDetail_ParentName[]" id="txtPublicCatalogDetail_ParentName" value="">
					</div>
			</div>
				
		</td>
		
		<td>											
			
			<div class="form-group">
					<label class="col-lg-4 control-label" for="normal">Sequencia</label>
					<div class="col-lg-8">
						<input class="form-control txtPublicCatalogDetail_Sequence"   type="text" name="txtPublicCatalogDetail_Sequence[]" id="txtPublicCatalogDetail_Sequence" value="">
					</div>
			</div>
			
			<div class="form-group">
					<label class="col-lg-4 control-label" for="normal">Ratio</label>
					<div class="col-lg-8">
						<input class="form-control txtPublicCatalogDetail_Ratio"   type="text" name="txtPublicCatalogDetail_Ratio[]" id="txtPublicCatalogDetail_Ratio" value="">
					</div>
			</div>
			
			<div class="form-group">
					<label class="col-lg-4 control-label" for="normal">Flavor</label>
					<div class="col-lg-8">
						<input class="form-control txtPublicCatalogDetail_Flavor"   type="text" name="txtPublicCatalogDetail_Flavor[]" id="txtPublicCatalogDetail_Flavor" value="">
					</div>
			</div>
				
		</td>
		
		
	</tr>
</script>