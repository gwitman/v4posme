					<!-- body -->
					<?php
						//Tipos de app disponibles (vacio = default)
						$typeAppActual	= "default";
						$tiposApp		= array();
						if($listMenuElement)
						foreach($listMenuElement as $m){
							$t = ($m->typeApp === null || $m->typeApp === "") ? "default" : $m->typeApp;
							if(!in_array($t,$tiposApp,true)) $tiposApp[] = $t;
						}
						if(!in_array($typeAppActual,$tiposApp,true)) $tiposApp[] = $typeAppActual;
					?>
					<div class="pm-role" id="pmRole">
					<form id="form-new-rol" name="form-new-rol" method="POST" action="<?php echo base_url(); ?>/core_role/save" role="form" autocomplete="off">

						<!-- barra de acciones -->
						<div class="pm-role-barra">
							<div class="pm-role-barra-titulo">Nuevo rol</div>
							<div class="pm-role-barra-botones">
								<a href="<?php echo base_url(); ?>/core_role/index" id="btnBack" class="btn btn-default"><i class="icon16 i-arrow-left-2"></i> Regresar</a>
								<button type="button" class="btn btn-primary pm-role-guardar" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</button>
							</div>
						</div>

						<!-- avisos -->
						<div class="alert alert-info pm-role-aviso">Los cambios en el menú se ven cuando el usuario vuelve a iniciar sesión.</div>

						<!-- datos del rol -->
						<div class="pm-role-card">
							<div class="pm-role-card-head">
								<h4 class="pm-role-card-titulo"><i class="icon16 i-shield"></i>Datos del rol</h4>
							</div>
							<div class="pm-role-card-body">
								<div class="pm-role-datos">
									<div class="pm-role-campo">
										<label for="txtName">Nombre <span class="pm-role-req">*</span></label>
										<input class="form-control" type="text" name="txtName" id="txtName" value="" maxlength="250">
									</div>
									<div class="pm-role-campo">
										<label for="txtUrlDefault">Url por defecto <span class="pm-role-req">*</span></label>
										<input class="form-control" type="text" name="txtUrlDefault" id="txtUrlDefault" value="" placeholder="core_dashboards/index">
									</div>
									<div class="pm-role-campo">
										<label for="txtTypeApp">Tipo de app</label>
										<select class="form-control" name="txtTypeApp" id="txtTypeApp">
											<?php foreach($tiposApp as $t){ ?>
											<option value="<?php echo htmlspecialchars($t); ?>" <?php echo ($t === $typeAppActual) ? 'selected="selected"' : ''; ?>><?php echo htmlspecialchars($t); ?></option>
											<?php } ?>
										</select>
									</div>
									<?php if(isset($canEditAdminCompany) && $canEditAdminCompany){ ?>
									<div class="pm-role-campo">
										<label for="txtIsAdminCompany">Administrador de compañía</label>
										<select class="form-control" name="txtIsAdminCompany" id="txtIsAdminCompany">
											<option value="0" selected="selected">No</option>
											<option value="1">Sí</option>
										</select>
									</div>
									<?php } ?>
									<div class="pm-role-campo pm-role-campo-ancho">
										<label for="txtDescription">Descripción <span class="pm-role-req">*</span></label>
										<textarea class="form-control" id="txtDescription" name="txtDescription" rows="2"></textarea>
									</div>
								</div>
							</div>
						</div>

						<!-- permisos por pantalla -->
						<div class="pm-role-card">
							<div class="pm-role-card-head">
								<h4 class="pm-role-card-titulo"><i class="icon16 i-grid-2"></i>Permisos por pantalla</h4>
							</div>
							<div class="pm-role-card-body">
								<div class="pm-role-herramientas">
									<input type="text" class="form-control pm-role-buscar" id="pmRoleBuscar" placeholder="Buscar pantalla…" autocomplete="off">
									<label class="pm-role-solo"><input type="checkbox" id="pmRoleSoloAcceso"> Solo con acceso</label>
									<button type="button" class="btn btn-default" id="pmRoleTotal" title="Acceso total a las pantallas visibles">Acceso total</button>
									<button type="button" class="btn btn-default" id="pmRoleVer" title="Solo ver en las pantallas visibles">Solo ver</button>
									<button type="button" class="btn btn-default" id="pmRoleQuitar" title="Quitar acceso a las pantallas visibles">Quitar todo</button>
								</div>
								<div class="pm-role-contador" id="pmRoleContador"></div>
								<div class="pm-role-conservados" id="pmRoleConservados" style="display:none"></div>
								<div id="pmRoleGrupos"></div>
								<div class="pm-role-vacio" id="pmRoleSinResultados" style="display:none">No hay pantallas que coincidan con el filtro.</div>
							</div>
						</div>

						<!-- autorizaciones -->
						<div class="pm-role-card">
							<div class="pm-role-card-head">
								<h4 class="pm-role-card-titulo"><i class="icon16 i-key"></i>Autorizaciones</h4>
								<div>
									<button type="button" class="btn btn-default btn-sm" id="pmRoleAutTodas">Marcar todas</button>
									<button type="button" class="btn btn-default btn-sm" id="pmRoleAutNinguna">Ninguna</button>
								</div>
							</div>
							<div class="pm-role-card-body">
								<div class="pm-role-auts" id="pmRoleAutorizaciones"></div>
							</div>
						</div>

						<!-- campos del POST (se generan al guardar) -->
						<div id="pmRolePost" style="display:none"></div>

						<!-- barra fija inferior (telefono) -->
						<div class="pm-role-barra-movil">
							<span class="pm-role-barra-movil-txt" id="pmRoleContadorMovil"></span>
							<button type="button" class="btn btn-primary pm-role-guardar"><i class="icon16 i-checkmark-4"></i> Guardar</button>
						</div>

					</form>
					</div>
					<!-- /body  -->
