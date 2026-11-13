<?php
    // Etapas de la ejecución (fase B). Las etapas 3, 4 y 6 son por bloques y llevan mini barra.
    $upgStages = array(
        1 => array('Validando conexión con el servidor', 'Se comprueba la firma, la versión publicada y que estén todos los paquetes.', false),
        2 => array('Verificando instalación local', 'Permisos de escritura, espacio en disco, PHP y base de datos de este equipo.', false),
        3 => array('Generando respaldo de base de datos', 'Copia completa de la base de datos antes de cambiar nada.', true),
        4 => array('Descargando archivos del servidor', 'Los paquetes se descargan por bloques y se verifica su huella (SHA-256).', true),
        5 => array('Comparando estructuras de base de datos', 'Se calcula qué cambia en archivos, estructura y datos. Aquí se pausa para que revise.', false),
        6 => array('Aplicando modificaciones', 'Estructura, datos de configuración y archivos, en ese orden.', true),
        7 => array('Validando archivos y componentes', 'Se confirma que lo aplicado coincide con lo descargado.', false),
        8 => array('Finalizando actualización', 'Se registra la nueva versión y se desactiva el modo mantenimiento.', false),
    );
    $upgSourceUrl = isset($defaultSourceUrl) ? (string) $defaultSourceUrl : '';
?>
<div class="abv2" id="upgApp">
<form id="form-upgrade" name="form-upgrade" role="form" method="post" autocomplete="off" onsubmit="return false;">

    <!-- encabezado -->
    <div class="abv2-card abv2-header">
        <div class="abv2-header-info">
            <div class="abv2-header-icon"><i class="icon20 i-download-2"></i></div>
            <div style="min-width:0;">
                <p class="abv2-header-num">Actualización desde el servidor posMe</p>
                <p class="abv2-header-sub upg-header-url">Origen: <b id="upgHeaderUrl"><?php echo htmlspecialchars($upgSourceUrl !== '' ? $upgSourceUrl : 'sin configurar'); ?></b></p>
            </div>
        </div>
        <div class="abv2-actions">
            <span class="abv2-chip" id="upgStatusChip">Consultando...</span>
        </div>
    </div>

    <!-- pestañas -->
    <div class="upg-tabs" role="tablist">
        <button type="button" class="upg-tab is-active" role="tab" data-tab="update" id="upgTabBtnUpdate"><i class="icon16 i-download-2"></i> Actualización</button>
        <button type="button" class="upg-tab" role="tab" data-tab="backup" id="upgTabBtnBackup"><i class="icon16 i-database"></i> Respaldo de base de datos</button>
    </div>

    <!-- ============================================================ pestaña: actualización -->
    <div id="upgTabUpdate">

    <!-- ============================================================ fase A: explicación y confirmación -->
    <div id="upgPhaseA">
    <div class="row">
        <div class="col-md-8 col-lg-8">

            <!-- 1. objetivo -->
            <div class="abv2-card">
                <p class="abv2-card-title"><span class="upg-step-num">1</span> Objetivo</p>
                <p class="upg-text">Esta pantalla trae la versión publicada en el servidor central de posMe y la aplica sobre esta instalación: archivos de la aplicación, estructura de la base de datos y datos de configuración.</p>
                <p class="upg-text">Antes de cambiar nada se genera un respaldo completo de la base de datos y de los archivos que se reemplazan, de modo que si algo falla se puede <b>revertir</b>.</p>
            </div>

            <!-- 2. versiones -->
            <div class="abv2-card">
                <p class="abv2-card-title"><span class="upg-step-num">2</span> Versiones</p>
                <div id="upgVersions"><p class="upg-hint">Consultando la versión local y la del servidor...</p></div>
                <div class="upg-btns">
                    <button type="button" class="btn btn-default upg-btn-main" id="upgBtnCheck"><i class="icon16 i-loop-4"></i> Volver a consultar</button>
                </div>
            </div>

            <!-- 3. modo -->
            <div class="abv2-card">
                <p class="abv2-card-title"><span class="upg-step-num">3</span> Tipo de instalación</p>
                <div class="form-group">
                    <label class="abv2-label" for="upgMode">Modo</label>
                    <select id="upgMode" name="upgMode" class="form-control" autocomplete="off">
                        <option value="local" <?php echo (isset($modeDetected) && $modeDetected === 'cloud') ? '' : 'selected'; ?>>Local (equipo del cliente: app, vendor y public)</option>
                        <option value="cloud" <?php echo (isset($modeDetected) && $modeDetected === 'cloud') ? 'selected' : ''; ?>>Nube (hosting: vendor y app/Config)</option>
                    </select>
                    <p class="upg-hint" id="upgModeHint">Detectado automáticamente: <b><?php echo (isset($modeDetected) && $modeDetected === 'cloud') ? 'Nube' : 'Local'; ?></b>. Si no es correcto, cámbielo: se vuelve a consultar el servidor con el modo elegido.</p>
                </div>
            </div>

            <!-- 4. componentes -->
            <div class="abv2-card">
                <p class="abv2-card-title"><span class="upg-step-num">4</span> Componentes que se actualizan</p>
                <div class="upg-alert is-info" style="margin-top:0;">Solo se actualizan los archivos que vienen dentro de cada zip; <b>nada se elimina</b>. Los archivos protegidos nunca se sobrescriben.</div>
                <div id="upgComponents"><p class="upg-hint">Consultando...</p></div>
            </div>

            <!-- 5. estructura -->
            <div class="abv2-card">
                <p class="abv2-card-title"><span class="upg-step-num">5</span> Estructura de la base de datos</p>
                <p class="upg-text">Se compara la estructura del servidor con la local (tablas, columnas, índices, restricciones, vistas, funciones, procedimientos y triggers) y se agregan o modifican los objetos que falten. <b>Nunca elimina tablas ni columnas.</b></p>
                <p class="upg-text">Los cambios con riesgo de pérdida de datos aparecen desmarcados en la etapa 5 para que usted decida si los incluye.</p>
                <div id="upgSchemaInfo"></div>
            </div>

            <!-- 6. datos -->
            <div class="abv2-card">
                <p class="abv2-card-title"><span class="upg-step-num">6</span> Datos de configuración</p>
                <p class="upg-text">Se sincronizan las tablas de configuración del sistema (catálogos, menús, flujos, parámetros) y luego los scripts de parámetros, todo en una transacción.</p>
                <ul class="upg-list">
                    <li><b>tb_company</b> no se sincroniza: los datos de su empresa no cambian.</li>
                    <li><b>tb_company_parameter</b> solo inserta los parámetros nuevos: sus valores actuales se conservan.</li>
                </ul>
                <div id="upgDataTables"></div>
            </div>

            <!-- 7. pasos -->
            <div class="abv2-card">
                <p class="abv2-card-title"><span class="upg-step-num">7</span> Pasos del proceso</p>
                <ol class="upg-list">
                    <?php foreach($upgStages as $upgN => $upgS){ ?>
                    <li><b><?php echo htmlspecialchars($upgS[0]); ?></b>: <?php echo htmlspecialchars($upgS[1]); ?></li>
                    <?php } ?>
                </ol>
            </div>

            <!-- 8. advertencias -->
            <div class="abv2-card">
                <p class="abv2-card-title"><span class="upg-step-num">8</span> Advertencias</p>
                <div class="upg-alert is-bad" style="margin-top:0;"><b>Modo mantenimiento:</b> durante la actualización nadie podrá operar en el sistema. Avise a los usuarios antes de empezar.</div>
                <div class="upg-alert is-warn"><b>No cierre esta pestaña</b> ni apague el equipo hasta que termine.</div>
                <div class="upg-alert is-info"><b>Respaldo previo:</b> la etapa 3 respalda la base de datos; aun así, se recomienda tener un respaldo propio reciente.</div>
                <div id="upgWarnings"></div>
            </div>

            <!-- 9. configuración -->
            <div class="abv2-card">
                <p class="abv2-card-title"><span class="upg-step-num">9</span> Conexión con el servidor</p>
                <div class="row">
                    <div class="col-xs-12 form-group">
                        <label class="abv2-label" for="upgCfgUrl">URL del servidor de origen</label>
                        <input type="url" class="form-control" id="upgCfgUrl" value="<?php echo htmlspecialchars($upgSourceUrl); ?>" placeholder="https://servidor.posme/v4posme/public" autocomplete="off" spellcheck="false">
                    </div>
                    <div class="col-xs-12 col-sm-6 form-group">
                        <label class="abv2-label" for="upgCfgInstallation">ID de instalación</label>
                        <input type="text" class="form-control" id="upgCfgInstallation" autocomplete="off" spellcheck="false">
                    </div>
                    <div class="col-xs-12 col-sm-6 form-group">
                        <label class="abv2-label" for="upgCfgSecret">Clave</label>
                        <input type="password" class="form-control" id="upgCfgSecret" autocomplete="new-password" spellcheck="false">
                    </div>
                </div>
                <div class="upg-chips" id="upgCfgChips"></div>
                <p class="upg-hint">La clave se guarda cifrada y solo se muestra enmascarada. Déjela vacía para conservar la guardada.</p>
                <div class="upg-btns">
                    <button type="button" class="btn btn-primary upg-btn-main" id="upgBtnCfgSave"><i class="icon16 i-checkmark-4"></i> Guardar conexión</button>
                </div>
                <div id="upgCfgResult"></div>
            </div>
        </div>

        <!-- columna derecha: comprobaciones y confirmación -->
        <div class="col-md-4 col-lg-4">
            <div class="abv2-sticky">
                <div class="abv2-card">
                    <p class="abv2-card-title"><i class="icon16 i-checkmark-4"></i> Comprobaciones</p>
                    <ul class="upg-checks" id="upgChecks"><li><span class="upg-ico">…</span>Consultando...</li></ul>
                </div>
                <div class="abv2-card">
                    <p class="abv2-card-title"><i class="icon16 i-play-3"></i> Iniciar actualización</p>
                    <label class="upg-option" for="upgForce" id="upgForceBox" style="display:none;">
                        <input type="checkbox" id="upgForce">
                        <span><p class="upg-option-title">Forzar aunque esté al día</p>
                        <p class="upg-option-desc">La versión local coincide con la del servidor. Marque para volver a aplicar la misma versión.</p></span>
                    </label>
                    <div class="form-group">
                        <label class="abv2-label" for="upgConfirm">Escriba ACTUALIZAR para confirmar</label>
                        <input type="text" class="form-control upg-confirm" id="upgConfirm" autocomplete="off" autocapitalize="characters" autocorrect="off" spellcheck="false">
                    </div>
                    <ul class="upg-summary" id="upgIssues"></ul>
                    <button type="button" class="btn btn-primary btn-block upg-btn-main" id="upgBtnRun" disabled style="margin-top:10px;"><i class="icon16 i-play-3"></i> Actualizar posMe</button>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- ============================================================ fase B: ejecución -->
    <div id="upgPhaseB" style="display:none;">
    <div class="row">
        <div class="col-md-8 col-lg-8">

            <!-- avance general -->
            <div class="abv2-card">
                <p class="abv2-card-title"><i class="icon16 i-loop-4"></i> Avance general</p>
                <div class="upg-progress" id="upgProgress"><div id="upgProgressBar"></div></div>
                <p class="upg-progress-text" id="upgProgressText">En espera.</p>
                <div id="upgResult"></div>
                <div class="upg-btns" id="upgEndBtns">
                    <button type="button" class="btn btn-default upg-btn-main" id="upgBtnRollback" style="display:none;"><i class="icon16 i-undo"></i> Revertir</button>
                    <button type="button" class="btn btn-default upg-btn-main" id="upgBtnBack" style="display:none;"><i class="icon16 i-arrow-left-2"></i> Volver</button>
                    <button type="button" class="btn btn-primary upg-btn-main" id="upgBtnReload" style="display:none;"><i class="icon16 i-loop-4"></i> Recargar posMe</button>
                </div>
            </div>

            <!-- plan de la etapa 5 (pausa) -->
            <div class="abv2-card" id="upgPlanCard" style="display:none;">
                <p class="abv2-card-title"><span class="upg-step-num">5</span> Revise los cambios antes de aplicarlos</p>
                <div id="upgPlan"></div>
                <div class="upg-btns" id="upgPlanBtns">
                    <button type="button" class="btn btn-primary upg-btn-main" id="upgBtnContinue"><i class="icon16 i-play-3"></i> Continuar con la aplicación</button>
                    <button type="button" class="btn btn-default upg-btn-main" id="upgBtnCancel"><i class="icon16 i-remove"></i> Cancelar sin aplicar</button>
                </div>
            </div>

            <!-- etapas -->
            <div class="abv2-card">
                <p class="abv2-card-title"><i class="icon16 i-list"></i> Etapas</p>
                <ul class="upg-stages" id="upgStages">
                    <?php foreach($upgStages as $upgN => $upgS){ ?>
                    <li data-stage="<?php echo $upgN; ?>">
                        <div class="upg-stage-head">
                            <span class="upg-stage-ico"><?php echo $upgN; ?></span>
                            <span class="upg-stage-txt">
                                <span class="upg-stage-title"><?php echo htmlspecialchars($upgS[0]); ?></span>
                                <span class="upg-stage-desc"><?php echo htmlspecialchars($upgS[1]); ?></span>
                            </span>
                            <span class="upg-stage-state">Pendiente</span>
                        </div>
                        <div class="upg-stage-msg"></div>
                        <?php if($upgS[2]){ ?>
                        <div class="upg-progress is-mini"><div></div></div>
                        <?php } ?>
                        <details class="upg-stage-det" style="display:none;">
                            <summary class="upg-link">Ver detalle</summary>
                            <div class="upg-stage-detail"></div>
                        </details>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <!-- bitácora -->
        <div class="col-md-4 col-lg-4">
            <div class="abv2-sticky">
                <div class="abv2-card">
                    <p class="abv2-card-title"><i class="icon16 i-file"></i> Bitácora</p>
                    <pre class="upg-log" id="upgLog"></pre>
                    <div class="upg-btns">
                        <button type="button" class="btn btn-default upg-btn-main" id="upgBtnCopy"><i class="icon16 i-copy"></i> Copiar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <!-- ============================================================ pestaña: respaldo de base de datos -->
    <div id="upgTabBackup" style="display:none;">
    <div class="row">
        <div class="col-md-8 col-lg-8">

            <!-- restauración pendiente (falló o se interrumpió) -->
            <div class="abv2-card" id="bkpPendingCard" style="display:none;">
                <p class="abv2-card-title"><i class="icon16 i-warning"></i> Restauración pendiente</p>
                <div id="bkpPending"></div>
                <div class="upg-btns">
                    <button type="button" class="btn btn-primary upg-btn-main" id="bkpBtnResume"><i class="icon16 i-play-3"></i> Continuar restauración</button>
                    <button type="button" class="btn btn-default upg-btn-main" id="bkpBtnAbort"><i class="icon16 i-remove"></i> Cancelar y salir de mantenimiento</button>
                </div>
            </div>

            <!-- avance de la operación en curso -->
            <div class="abv2-card" id="bkpProgressCard" style="display:none;">
                <p class="abv2-card-title"><i class="icon16 i-loop-4"></i> <span id="bkpProgressTitle">Avance</span></p>
                <div class="upg-progress" id="bkpProgress"><div id="bkpProgressBar"></div></div>
                <p class="upg-progress-text" id="bkpProgressText">En espera.</p>
                <div id="bkpResult"></div>
            </div>

            <!-- respaldos guardados -->
            <div class="abv2-card">
                <p class="abv2-card-title"><i class="icon16 i-database"></i> Respaldos guardados</p>
                <p class="upg-text">Respaldos completos de la base de datos guardados en este servidor. Desde aquí puede <b>descargarlos</b>, <b>restaurar</b> la base de datos con uno de ellos o eliminarlos.</p>
                <div id="bkpList"><p class="upg-hint">Consultando...</p></div>
                <div class="upg-chips" id="bkpChips"></div>
                <div class="upg-btns">
                    <button type="button" class="btn btn-default upg-btn-main" id="bkpBtnRefresh"><i class="icon16 i-loop-4"></i> Actualizar lista</button>
                </div>
            </div>

            <!-- bitácora -->
            <div class="abv2-card">
                <p class="abv2-card-title"><i class="icon16 i-file"></i> Bitácora</p>
                <pre class="upg-log" id="bkpLog" style="height:160px;"></pre>
            </div>
        </div>

        <div class="col-md-4 col-lg-4">
            <div class="abv2-sticky">
                <!-- generar -->
                <div class="abv2-card">
                    <p class="abv2-card-title"><i class="icon16 i-plus"></i> Generar respaldo</p>
                    <p class="upg-text">Copia íntegra de la base de datos: estructura de tablas, datos, vistas, funciones, procedimientos y triggers.</p>
                    <div class="form-group">
                        <label class="abv2-label" for="bkpNote">Descripción (opcional)</label>
                        <input type="text" class="form-control" id="bkpNote" maxlength="120" autocomplete="off" placeholder="Ej.: antes del cierre de mes">
                    </div>
                    <button type="button" class="btn btn-primary btn-block upg-btn-main" id="bkpBtnCreate"><i class="icon16 i-database"></i> Generar respaldo ahora</button>
                    <p class="upg-hint">El sistema sigue disponible mientras se genera; para una copia exacta, evite registrar operaciones en ese momento.</p>
                </div>

                <!-- subir -->
                <div class="abv2-card">
                    <p class="abv2-card-title"><i class="icon16 i-upload"></i> Subir respaldo</p>
                    <p class="upg-text">Suba un archivo <b>.zip</b> descargado desde esta pantalla (de esta u otra instalación). Queda guardado en la lista para restaurarlo.</p>
                    <div class="form-group">
                        <label class="abv2-label" for="bkpFile">Archivo .zip</label>
                        <input type="file" class="form-control" id="bkpFile" accept=".zip,application/zip">
                    </div>
                    <div class="upg-progress" id="bkpUpProgress" style="display:none;"><div id="bkpUpProgressBar"></div></div>
                    <p class="upg-hint" id="bkpUpText"></p>
                    <button type="button" class="btn btn-default btn-block upg-btn-main" id="bkpBtnUpload"><i class="icon16 i-upload"></i> Subir respaldo</button>
                </div>

                <!-- advertencias -->
                <div class="abv2-card">
                    <p class="abv2-card-title"><i class="icon16 i-warning"></i> Antes de restaurar</p>
                    <div class="upg-alert is-bad" style="margin-top:0;"><b>Restaurar reemplaza toda la base de datos</b> por el contenido del respaldo: se pierde todo lo registrado después de esa fecha.</div>
                    <div class="upg-alert is-warn">Durante la restauración se activa el <b>modo mantenimiento</b>: nadie podrá operar en el sistema. No cierre esta pestaña.</div>
                    <div class="upg-alert is-info">Recomendación: genere un respaldo de la base actual antes de restaurar otro.</div>
                </div>
            </div>
        </div>
    </div>
    </div>
</form>

<!-- diálogo propio (no bloquea como window.confirm) -->
<div class="upg-overlay" id="upgOverlay">
    <div class="upg-dialog" role="dialog" aria-modal="true" aria-labelledby="upgDialogTitle">
        <h4 id="upgDialogTitle"></h4>
        <div id="upgDialogBody"></div>
        <div class="upg-btns" style="justify-content:flex-end; margin-top:14px;">
            <button type="button" class="btn btn-default upg-btn-main" id="upgDialogCancel">Cancelar</button>
            <button type="button" class="btn btn-primary upg-btn-main" id="upgDialogOk">Aceptar</button>
        </div>
    </div>
</div>
</div>
