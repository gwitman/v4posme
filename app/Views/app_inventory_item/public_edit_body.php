<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Editar /</span> items</h4>

  <div class="row mb-5">

	<div class="col-md-12 col-lg-4 mb-6">
	 
	  <?php echo $message; ?>
	  
	  <div class="card h-100">
		<div class="card-body">
		  <h5 class="card-title"><?php echo $company->name; ?></h5>
		  <h6 class="card-subtitle text-muted">Codigo <?php echo $objItem->itemNumber; ?></h6>
		</div>
		
		<img class="img-fluid" src="<?php echo base_url();?>/resource/img/logos/logo-micro-finanza.jpg" alt="Card image cap" />
		
		
		<div class="card-body">
		
			<form role="form" action="<?php echo  base_url()."/app_inventory_item/save/edit_public/item/null/dataSession/null"; ?>" method="POST" >
						
					  <input type="hidden" id="txtItemID" name="txtItemID"  value="<?php echo $objItem->itemID; ?>" />
					  
					  <div class="mb-12 row">
						<label for="html5-tel-input" class="col-md-6 col-form-label">ID</label>
						<div class="col-md-6">
						  <input class="form-control" type="tel"  id="txtItemBarCode" name="txtItemBarCode" value="<?php echo $objItem->barCode; ?>" ready="true" />
						</div>
					  </div>
					  
					  <div class="mb-12 row">
						<label for="html5-text-input" class="col-md-6 col-form-label">Nombre</label>
						<div class="col-md-6">
						  <input class="form-control" type="text" id="txtItemName" name="txtItemName" value="<?php echo $objItem->name; ?>" ready="true"  />
						</div>
					  </div>
				   
					 
					  
					  <div class="mb-12 row">
						<label for="html5-date-input" class="col-md-6 col-form-label">Renovar</label>
						<div class="col-md-6">
						  <input class="form-control" type="date" id="txtRealStateDateExpired" name="txtRealStateDateExpired" />
						</div>
					  </div>
					  
					 <div class="mb-12 row">
						<button type="submit" class="btn rounded-pill btn-outline-success">Renovar</button>
					 </div>
				  
					 
					  
			</form>
		</div>
	  </div>
	</div>
  </div>
</div>
</div>
