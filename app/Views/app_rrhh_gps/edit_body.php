<!-- Content wrapper -->
<div class="content-wrapper">	
	<!--/ Toast with Animation -->
	<div class="container-xxl flex-grow-1 container-p-y">
		<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Seguimiento /</span> <?php echo htmlspecialchars($txtUserName, ENT_QUOTES); ?></h4>
		<div class="row mb-12">
		<div class="col-md-12 col-lg-12 mb-6">
			<?= $message; ?>
			<form id="frmPublic" role="form" action="<?= base_url(); ?>/app_form_public/save" method="POST" enctype="multipart/form-data" autocomplete="off">
				<div class="card h-100" id="detalle-servicio">
					<div class="card-body">
						<div class="container-fluid">
							<div class="row">
								<div class="col-md-12 my-12 px-12" >									
									<div class="alert alert-warning mb-0" id="gpsSinDatos" style="display:none">No hay ubicaciones registradas para este colaborador.</div><div id="map" style="height: 60vh; min-height: 320px; width: 100%;"></div>									
								</div>
							</div>
						</div>
						</br>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Content wrapper -->