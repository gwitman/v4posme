				<!-- ./ page heading -->
				<?php
					$jsonFlags = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_INVALID_UTF8_SUBSTITUTE;
				?>
				<script>
					//Variables Globales
					var site_url 						= "<?php echo base_url(); ?>/";

					$(document).ready(function(){
						//Pantalla de permisos (public/resource/js/posme-role.js)
						PosmeRole.init({
							listMenuElement		: <?php echo json_encode($listMenuElement ? $listMenuElement : array(), $jsonFlags) ?: '[]'; ?>,
							listUserPermission	: [],
							autorizaciones		: <?php echo json_encode($listComponentAutorizationAll ? $listComponentAutorizationAll : array(), $jsonFlags) ?: '[]'; ?>,
							autorizacionesRol	: [],
							esEdicion			: false,
							urlSave				: site_url+"core_role/save",
							urlIndex			: site_url+"core_role/index",
							urlDelete			: site_url+"core_role/delete"
						});
					});
				</script>