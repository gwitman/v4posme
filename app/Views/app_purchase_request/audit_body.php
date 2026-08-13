<div class="row"> 
	<div id="email" class="col-lg-12">
		<!-- botonera -->
		<div class="email-bar" style="border-left:1px solid #c9c9c9">                                
			<div class="btn-group pull-right">     
				<a href="<?php echo base_url(); ?>/app_purchase_request/edit/companyID/<?php echo $objTM->companyID; ?>/transactionID/<?php echo $objTM->transactionID; ?>/transactionMasterID/<?php echo $objTM->transactionMasterID; ?>" id="btnBack" class="btn btn-inverse"><i class="icon16 i-rotate"></i> Atras</a>
			</div>
		</div> 
		<!-- /botonera -->
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="icon"><i class="icon20 i-pen-3"></i></div>
				<h4>AUDITORÍA - Tracking de Cambios #<?php echo $objTM->transactionNumber; ?></h4>
			</div>
			<div class="panel-body">
				<?php if(!$objDataAudit || count($objDataAudit) == 0): ?>
					<div class="alert alert-info">No hay registros de auditoría para este documento.</div>
				<?php else: ?>
					<table id="tblAudit" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>#</th>
								<th>Fecha Modificación</th>
								<th>Usuario</th>
								<th>Campo</th>
								<th>Valor Anterior</th>
								<th>Valor Nuevo</th>
							</tr>
						</thead>
						<tbody>
							<?php $i = 1; foreach($objDataAudit as $item): ?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $item->modifiedOn; ?></td>
								<td><?php echo $item->nickname; ?></td>
								<td><?php echo $item->name; ?></td>
								<td><span class="label label-warning"><?php echo $item->oldValue; ?></span></td>
								<td><span class="label label-success"><?php echo $item->newValue; ?></span></td>
							</tr>
							<?php $i++; endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
