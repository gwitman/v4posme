<!-- Role cards -->
<div class="row g-4">
	<?php
	foreach($data as $item)
	{
	?>
		<div class="col-xl-4 col-lg-6 col-md-6">
		  <div class="card">
			<div class="card-body">
			  <div class="d-flex justify-content-between mb-2">
				<h6 class="fw-normal"><?php echo $item["Telefono"]; ?></h6>
				<ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
				  <li
					data-bs-toggle="tooltip"
					data-popup="tooltip-custom"
					data-bs-placement="top"
					title="Vinnie Mostowy"
					class="avatar avatar-sm pull-up"
				  >
					<img class="rounded-circle" src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/img/avatars/5.png" alt="Avatar" />
				  </li>
				</ul>
			  </div>
			  <div class="d-flex justify-content-between align-items-end">
				<div class="role-heading">
				  <h4 class="mb-1"><?php echo $item["Nombre"]; ?></h4>
				  <a
					href="<?php echo  base_url()."/app_cxc_conversation/edit/entityID/".$item["entityID"] ?>"
					
					class="role-edit-modal"
					><small>Ver</small></a
				  >
				</div>
				<a href="javascript:void(0);" class="text-muted"><i class="bx bx-copy"></i></a>
			  </div>
			</div>
		  </div>
		</div>
	<?php 
	}
	?>
</div>
<!--/ Role cards -->
