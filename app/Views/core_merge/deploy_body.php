<div class="abv2" id="dplApp">
<form id="form-deploy" name="form-deploy" role="form" method="post" autocomplete="off" onsubmit="return false;">

	<!-- encabezado -->
	<div class="abv2-card abv2-header">
		<div class="abv2-header-info">
			<div class="abv2-header-icon"><i class="icon20 i-upload-3"></i></div>
			<div>
				<p class="abv2-header-num">Sincronización de instalación remota</p>
				<p class="abv2-header-sub">Archivos de la aplicación, estructura de la base de datos y datos de configuración</p>
			</div>
		</div>
		<div class="abv2-actions">
			<span class="abv2-chip" id="dplStatusChip">Sin iniciar</span>
			<a href="#" class="btn btn-default disabled" id="dplBtnLog"><i class="icon16 i-download-2"></i> Informe</a>
		</div>
	</div>

	<!-- pestañas principales -->
	<div class="dpl-maintabs" role="tablist">
		<button type="button" class="dpl-maintab is-active" data-dpl-pane="dplPaneSync" role="tab"><i class="icon16 i-loop-4"></i> Sincronización</button>
		<button type="button" class="dpl-maintab" data-dpl-pane="dplPaneClean" role="tab"><i class="icon16 i-remove"></i> Limpieza de servidor</button>
	</div>

	<div id="dplPaneSync">
	<div class="row">
		<div class="col-md-8 col-lg-8">

			<!-- 1. compañía -->
			<div class="abv2-card">
				<p class="abv2-card-title"><span class="dpl-step-num">1</span> Compañía a sincronizar</p>
				<div class="form-group">
					<label class="abv2-label" for="dplCompany">Compañía (scripts de file_sql/clientes)</label>
					<select id="dplCompany" name="dplCompany" class="form-control" autocomplete="off">
						<option value="">Seleccione una compañía...</option>
						<?php foreach($companies as $c){ ?>
						<option value="<?php echo htmlspecialchars($c['key']); ?>" <?php echo $c['valid'] ? '' : 'disabled'; ?>
							data-db="<?php echo htmlspecialchars($c['dbName']); ?>"
							data-server="<?php echo htmlspecialchars($c['server']); ?>">
							<?php echo htmlspecialchars($c['key']); ?><?php echo $c['valid'] ? '' : ' (sin cabecera /*BD:*/)'; ?>
						</option>
						<?php } ?>
					</select>
					<p class="dpl-hint"><?php echo number_format(count($companies)); ?> compañías encontradas. La base de datos esperada se toma de la cabecera <code>/*BD: ... */</code> del script.</p>
				</div>
				<div class="dpl-chips" id="dplCompanyChips"></div>
			</div>

			<!-- 2. conexión remota -->
			<div class="abv2-card" id="dplCardConnection">
				<p class="abv2-card-title"><span class="dpl-step-num">2</span> Conexión remota</p>
				<ul class="nav nav-tabs">
					<li class="active"><a href="#dplTabAgent" data-toggle="tab">Agente</a></li>
					<li><a href="#dplTabFtp" data-toggle="tab">SFTP / FTP</a></li>
					<li><a href="#dplTabDb" data-toggle="tab">MySQL directo</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="dplTabAgent">
						<div class="form-group">
							<label class="abv2-label" for="dplAgentUrl">URL de la instalación remota</label>
							<input type="url" class="form-control" id="dplAgentUrl" placeholder="https://dominio.com/v4posme/cliente/public" autocomplete="off">
							<p class="dpl-hint">La misma dirección con la que se abre posMe en el navegador. Debe usar HTTPS.</p>
						</div>
						<div class="dpl-chips"><span class="dpl-chip" id="dplSecretChip">Secreto del agente: sin configurar</span></div>
						<div class="dpl-btns">
							<button type="button" class="btn btn-default" id="dplBtnSecret"><i class="icon16 i-key"></i> Generar secreto</button>
							<button type="button" class="btn btn-default" id="dplBtnInstall"><i class="icon16 i-upload"></i> Instalar agente por SFTP/FTP</button>
						</div>
					</div>
					<div class="tab-pane" id="dplTabFtp">
						<div class="row">
							<div class="col-xs-12 col-sm-4 form-group">
								<label class="abv2-label" for="dplFtpProtocol">Protocolo</label>
								<select class="form-control" id="dplFtpProtocol" autocomplete="off">
									<option value="sftp">SFTP (recomendado)</option>
									<option value="ftps">FTPS (FTP con TLS)</option>
									<option value="ftp">FTP sin cifrado</option>
									<option value="none">No usar</option>
								</select>
							</div>
							<div class="col-xs-8 col-sm-5 form-group">
								<label class="abv2-label" for="dplFtpHost">Servidor</label>
								<input type="text" class="form-control" id="dplFtpHost" autocomplete="off">
							</div>
							<div class="col-xs-4 col-sm-3 form-group">
								<label class="abv2-label" for="dplFtpPort">Puerto</label>
								<input type="number" inputmode="numeric" class="form-control" id="dplFtpPort" placeholder="22 / 21" autocomplete="off">
							</div>
							<div class="col-xs-12 col-sm-6 form-group">
								<label class="abv2-label" for="dplFtpUser">Usuario</label>
								<input type="text" class="form-control" id="dplFtpUser" autocomplete="off">
							</div>
							<div class="col-xs-12 col-sm-6 form-group">
								<label class="abv2-label" for="dplFtpPassword">Contraseña</label>
								<input type="password" class="form-control" id="dplFtpPassword" autocomplete="new-password" placeholder="">
							</div>
							<div class="col-xs-12 col-sm-6 form-group">
								<label class="abv2-label" for="dplFtpRoot">Directorio raíz remoto</label>
								<input type="text" class="form-control" id="dplFtpRoot" placeholder="/public_html" autocomplete="off">
							</div>
							<div class="col-xs-12 col-sm-6 form-group">
								<label class="abv2-label" for="dplFtpApp">Directorio de la aplicación</label>
								<input type="text" class="form-control" id="dplFtpApp" placeholder="v4posme/cliente" autocomplete="off">
							</div>
						</div>
						<p class="dpl-hint">Se usa para instalar el agente la primera vez. SFTP es la opción más segura; FTP sin cifrado expone la contraseña en la red.</p>
					</div>
					<div class="tab-pane" id="dplTabDb">
						<div class="form-group">
							<label class="abv2-label" for="dplDbMode">Ejecución de estructura y datos</label>
							<select class="form-control" id="dplDbMode" autocomplete="off">
								<option value="agent">Por el agente (recomendado, la BD no se expone)</option>
								<option value="direct">MySQL directo (VPS con el puerto abierto)</option>
							</select>
						</div>
						<div class="row" id="dplDbFields">
							<div class="col-xs-8 col-sm-6 form-group">
								<label class="abv2-label" for="dplDbHost">Host MySQL</label>
								<input type="text" class="form-control" id="dplDbHost" autocomplete="off">
							</div>
							<div class="col-xs-4 col-sm-2 form-group">
								<label class="abv2-label" for="dplDbPort">Puerto</label>
								<input type="number" inputmode="numeric" class="form-control" id="dplDbPort" placeholder="3306" autocomplete="off">
							</div>
							<div class="col-xs-12 col-sm-4 form-group">
								<label class="abv2-label" for="dplDbName">Base de datos</label>
								<input type="text" class="form-control" id="dplDbName" autocomplete="off">
							</div>
							<div class="col-xs-12 col-sm-6 form-group">
								<label class="abv2-label" for="dplDbUser">Usuario</label>
								<input type="text" class="form-control" id="dplDbUser" autocomplete="off">
							</div>
							<div class="col-xs-12 col-sm-6 form-group">
								<label class="abv2-label" for="dplDbPassword">Contraseña</label>
								<input type="password" class="form-control" id="dplDbPassword" autocomplete="new-password">
							</div>
						</div>
					</div>
				</div>
				<p class="dpl-hint">Las contraseñas se guardan cifradas y nunca se vuelven a mostrar. Déjelas vacías para conservar las guardadas.</p>
				<div class="dpl-btns">
					<button type="button" class="btn btn-primary" id="dplBtnSave"><i class="icon16 i-checkmark-4"></i> Guardar conexión</button>
					<button type="button" class="btn btn-default" id="dplBtnTest"><i class="icon16 i-loop-4"></i> Probar conexiones</button>
				</div>
				<ul class="dpl-checks" id="dplChecks"></ul>
			</div>

			<!-- 3. paquete -->
			<div class="abv2-card">
				<p class="abv2-card-title"><span class="dpl-step-num">3</span> Paquete de actualización (ZIP)</p>
				<div class="form-group">
					<label class="abv2-label" for="dplZip">Archivo ZIP con la carpeta app/</label>
					<input type="file" class="form-control" id="dplZip" accept=".zip,application/zip">
					<p class="dpl-hint">Se actualizan solo <b>app/Config/Routes.php</b> y las carpetas <b>Controllers, Models, Libraries, Helpers y Views</b>. Se conservan app/Config, .env, writable, vendor y public del cliente.</p>
				</div>
				<div class="dpl-btns">
					<button type="button" class="btn btn-primary" id="dplBtnPackage"><i class="icon16 i-checkmark-4"></i> Validar paquete</button>
				</div>
				<div id="dplPackageResult"></div>
			</div>

			<!-- 4. opciones -->
			<div class="abv2-card">
				<p class="abv2-card-title"><span class="dpl-step-num">4</span> Opciones de sincronización</p>

				<label class="dpl-option" for="dplOptFiles">
					<input type="checkbox" id="dplOptFiles">
					<span><p class="dpl-option-title">Actualizar archivos de la aplicación</p>
					<p class="dpl-option-desc">Envía el paquete validado, lo verifica, respalda los archivos que se reemplazan y actualiza solo los autorizados.</p></span>
				</label>

				<label class="dpl-option" for="dplOptSchema">
					<input type="checkbox" id="dplOptSchema">
					<span><p class="dpl-option-title">Sincronizar estructura de la base de datos</p>
					<p class="dpl-option-desc">Tablas, columnas, índices, restricciones, vistas, funciones, procedimientos y triggers. Nunca elimina tablas ni columnas del destino.</p></span>
				</label>
				<div class="dpl-sub" id="dplSchemaBox" style="display:none;">
					<div class="dpl-btns"><button type="button" class="btn btn-default" id="dplBtnSchema"><i class="icon16 i-search"></i> Analizar diferencias</button></div>
					<div id="dplSchemaResult"></div>
				</div>

				<label class="dpl-option" for="dplOptData">
					<input type="checkbox" id="dplOptData">
					<span><p class="dpl-option-title">Sincronizar datos de configuración</p>
					<p class="dpl-option-desc">Orden: 1) tablas del perfil Navicat 01 (+ tb_counter solo inserción, perfil 03) → 2) 001_default → 3) script de la compañía. Todo en una transacción.</p></span>
				</label>
				<div class="dpl-sub" id="dplDataBox" style="display:none;">
					<div class="dpl-btns">
						<button type="button" class="btn btn-default btn-sm" data-dpl-all="full">Todas: completa</button>
						<button type="button" class="btn btn-default btn-sm" data-dpl-all="upsert">Todas: sin eliminar</button>
						<button type="button" class="btn btn-default btn-sm" data-dpl-all="default">Restablecer perfiles</button>
					</div>
					<div class="dpl-scroll" style="margin-top:8px;">
						<table class="dpl-table" id="dplTablesTable">
							<thead><tr><th>Tabla</th><th>Perfil</th><th>Modo</th><th class="dpl-n">Insertar</th><th class="dpl-n">Actualizar</th><th class="dpl-n">Eliminar</th></tr></thead>
							<tbody>
							<?php foreach($tables as $table => $info){ ?>
								<tr data-table="<?php echo htmlspecialchars($table); ?>" data-default="<?php echo htmlspecialchars($info['mode']); ?>">
									<td><b><?php echo htmlspecialchars($table); ?></b><div class="dpl-hint dpl-warn-cell"></div></td>
									<td><span class="dpl-badge"><?php echo htmlspecialchars($info['profile']); ?></span></td>
									<td>
										<select class="form-control dpl-mode" autocomplete="off">
											<option value="full" <?php echo $info['mode'] === 'full' ? 'selected' : ''; ?>>Completa</option>
											<option value="upsert" <?php echo $info['mode'] === 'upsert' ? 'selected' : ''; ?>>Insertar y actualizar</option>
											<option value="insert" <?php echo $info['mode'] === 'insert' ? 'selected' : ''; ?>>Solo insertar</option>
											<option value="skip">No sincronizar</option>
										</select>
									</td>
									<td class="dpl-n dpl-c-insert">-</td><td class="dpl-n dpl-c-update">-</td><td class="dpl-n dpl-c-delete">-</td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>
					<label class="dpl-option" for="dplRunScripts" style="margin-top:10px;">
						<input type="checkbox" id="dplRunScripts" checked>
						<span><p class="dpl-option-title">Ejecutar scripts de parámetros</p>
						<p class="dpl-option-desc">001_default.sql y luego el script de la compañía seleccionada.</p></span>
					</label>
					<div class="dpl-btns">
						<button type="button" class="btn btn-default" id="dplBtnData"><i class="icon16 i-search"></i> Analizar datos</button>
					</div>
					<div id="dplDataResult"></div>
				</div>
			</div>
		</div>

		<!-- resumen y ejecución -->
		<div class="col-md-4 col-lg-4">
			<div class="abv2-sticky">
				<div class="abv2-card">
					<p class="abv2-card-title"><span class="dpl-step-num">5</span> Resumen de operaciones</p>
					<ul class="dpl-summary" id="dplSummary"></ul>
					<label class="dpl-option" for="dplConfirm" style="margin-top:10px;">
						<input type="checkbox" id="dplConfirm">
						<span><p class="dpl-option-title">Revisé los cambios</p>
						<p class="dpl-option-desc">Entiendo que se modificará la instalación del cliente.</p></span>
					</label>
					<button type="button" class="btn btn-primary btn-block" id="dplBtnRun" disabled style="height:44px;"><i class="icon16 i-play-3"></i> Ejecutar sincronización</button>
				</div>

				<div class="abv2-card">
					<p class="abv2-card-title"><span class="dpl-step-num">6</span> Ejecución</p>
					<div class="dpl-progress"><div id="dplProgressBar"></div></div>
					<p class="dpl-hint" id="dplProgressText">En espera.</p>
					<ul class="dpl-stages" id="dplStages">
						<li data-stage="connect"><span class="dpl-stage-ico">1</span> Validación de conexiones <span class="dpl-stage-msg"></span></li>
						<li data-stage="package"><span class="dpl-stage-ico">2</span> Preparación del paquete <span class="dpl-stage-msg"></span></li>
						<li data-stage="transfer"><span class="dpl-stage-ico">3</span> Transferencia <span class="dpl-stage-msg"></span></li>
						<li data-stage="extract"><span class="dpl-stage-ico">4</span> Descompresión y verificación <span class="dpl-stage-msg"></span></li>
						<li data-stage="files"><span class="dpl-stage-ico">5</span> Actualización de archivos <span class="dpl-stage-msg"></span></li>
						<li data-stage="schema"><span class="dpl-stage-ico">6</span> Estructura de BD <span class="dpl-stage-msg"></span></li>
						<li data-stage="data"><span class="dpl-stage-ico">7</span> Datos de configuración <span class="dpl-stage-msg"></span></li>
						<li data-stage="verify"><span class="dpl-stage-ico">8</span> Verificación final <span class="dpl-stage-msg"></span></li>
					</ul>
					<pre class="dpl-log" id="dplLog"></pre>
					<div class="dpl-btns" id="dplRollbackBox" style="display:none;">
						<button type="button" class="btn btn-default" id="dplBtnRollbackFiles"><i class="icon16 i-undo"></i> Revertir archivos</button>
						<button type="button" class="btn btn-default" id="dplBtnRollbackData"><i class="icon16 i-undo"></i> Revertir datos</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>

	<!-- ============================================================ limpieza de servidor -->
	<div id="dplPaneClean" style="display:none;">
	<div class="row">
		<div class="col-md-8 col-lg-8">

			<!-- 1. agente -->
			<div class="abv2-card">
				<p class="abv2-card-title"><span class="dpl-step-num">1</span> Agente que hará la limpieza</p>
				<div class="form-group">
					<label class="abv2-label" for="dplCleanCompany">Compañía con conexión guardada</label>
					<select id="dplCleanCompany" name="dplCleanCompany" class="form-control" autocomplete="off">
						<option value="">Seleccione una compañía...</option>
						<?php foreach($companies as $c){ if(!$c['valid']) continue; ?>
						<option value="<?php echo htmlspecialchars($c['key']); ?>"><?php echo htmlspecialchars($c['key']); ?></option>
						<?php } ?>
					</select>
					<p class="dpl-hint">Su agente revisa la carpeta donde está instalado y limpia las instalaciones posMe vecinas del mismo hosting. Para otro hosting, elija una compañía de ese hosting.</p>
				</div>
			</div>

			<!-- 2. categorías -->
			<div class="abv2-card">
				<p class="abv2-card-title"><span class="dpl-step-num">2</span> Qué limpiar</p>
				<?php foreach(\App\Libraries\core_web_deploy\core_deploy_cleanup::$CATEGORIES as $key => $cat){ ?>
				<label class="dpl-option<?php echo $key === 'file_company' ? ' is-danger' : ''; ?>" for="dplCleanCat_<?php echo $key; ?>">
					<input type="checkbox" class="dpl-clean-cat" id="dplCleanCat_<?php echo $key; ?>" value="<?php echo $key; ?>" checked>
					<span><p class="dpl-option-title"><?php echo htmlspecialchars($cat[0]); ?></p>
					<p class="dpl-option-desc"><?php echo htmlspecialchars($cat[1]); ?></p></span>
				</label>
				<?php } ?>
				<div class="dpl-alert is-bad" style="margin-top:8px;"><b>Archivos de compañía</b> vacía las carpetas component_* de cada compañía: se pierden imágenes de productos, adjuntos, reportes y respaldos .sql. No se puede revertir.</div>
				<div class="dpl-btns" style="margin-top:10px;">
					<button type="button" class="btn btn-default" id="dplBtnCleanScan"><i class="icon16 i-search"></i> Analizar</button>
				</div>
			</div>

			<!-- 3. instalaciones -->
			<div class="abv2-card">
				<p class="abv2-card-title"><span class="dpl-step-num">3</span> Instalaciones del hosting</p>
				<div id="dplCleanResult"><p class="dpl-hint">Pulse Analizar para ver las instalaciones y el espacio que se liberaría. Analizar no borra nada.</p></div>
			</div>
		</div>

		<div class="col-md-4 col-lg-4">
			<div class="abv2-sticky">
				<div class="abv2-card">
					<p class="abv2-card-title"><span class="dpl-step-num">4</span> Confirmar limpieza</p>
					<ul class="dpl-summary" id="dplCleanSummary"></ul>
					<div class="form-group" style="margin-top:10px;">
						<label class="abv2-label" for="dplCleanConfirm">Escriba LIMPIAR para confirmar</label>
						<input type="text" class="form-control" id="dplCleanConfirm" autocomplete="off">
					</div>
					<button type="button" class="btn btn-danger btn-block" id="dplBtnClean" disabled style="height:44px;"><i class="icon16 i-remove"></i> Limpiar</button>
				</div>

				<div class="abv2-card">
					<p class="abv2-card-title"><span class="dpl-step-num">5</span> Avance</p>
					<div class="dpl-progress"><div id="dplCleanBar"></div></div>
					<p class="dpl-hint" id="dplCleanText">En espera.</p>
					<pre class="dpl-log" id="dplCleanLog"></pre>
				</div>
			</div>
		</div>
	</div>
	</div>
</form>

<!-- diálogo -->
<div class="dpl-overlay" id="dplOverlay">
	<div class="dpl-dialog">
		<h4 id="dplDialogTitle"></h4>
		<div id="dplDialogBody"></div>
		<div class="dpl-btns" style="justify-content:flex-end; margin-top:14px;">
			<button type="button" class="btn btn-default" id="dplDialogCancel">Cancelar</button>
			<button type="button" class="btn btn-primary" id="dplDialogOk">Aceptar</button>
		</div>
	</div>
</div>
</div>
