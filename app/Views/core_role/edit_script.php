				
				<?php
					$jsonFlags = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_INVALID_UTF8_SUBSTITUTE;
				?>
				<script>
					//Variables Globales
					var site_url 						= "<?php echo base_url(); ?>/";
					var companyID 						= <?php echo json_encode((string)$objRole->companyID, $jsonFlags); ?>;
					var branchID 						= <?php echo json_encode((string)$objRole->branchID, $jsonFlags); ?>;
					var roleID	 						= <?php echo json_encode((string)$objRole->roleID, $jsonFlags); ?>;

					$(document).ready(function(){

						//Pantalla de permisos (public/resource/js/posme-role.js); guardar y regresar los maneja PosmeRole
						PosmeRole.init({
							listMenuElement		: <?php echo json_encode($listMenuElement ? $listMenuElement : array(), $jsonFlags) ?: '[]'; ?>,
							listUserPermission	: <?php echo json_encode($objUserPermission ? $objUserPermission : array(), $jsonFlags) ?: '[]'; ?>,
							autorizaciones		: <?php echo json_encode($listComponentAutorizationAll ? $listComponentAutorizationAll : array(), $jsonFlags) ?: '[]'; ?>,
							autorizacionesRol	: <?php echo json_encode($listComponentAutoriation ? $listComponentAutoriation : array(), $jsonFlags) ?: '[]'; ?>,
							esEdicion			: true,
							urlSave				: site_url+"core_role/save",
							urlIndex			: site_url+"core_role/index",
							urlDelete			: site_url+"core_role/delete"
						});

						//Comando Eliminar
						$(document).on("click","#btnDelete",function(){							
							fnShowConfirm("Confirmar..","Desea eliminar este Rol...",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: site_url+"core_role/delete",
									data 		: {companyID : companyID, branchID : branchID, roleID : roleID },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											window.location = site_url+"core_role/index";
										}
									},
									error:function(xhr,data){	
										console.info("complete delete error");									
										fnWaitClose();
										fnShowNotification("Error 505","error");
									}
								});
							});
						});
						

					});
				</script>