<script>
    /* ==========================================================
       Consola de despliegue remoto (core_merge/deploy)
       Cada paso es una petición corta al servidor local; el servidor
       local habla con el agente remoto. El avance se muestra por etapa.
       ========================================================== */
    (function () {
        'use strict';

        var BASE       = '<?php echo base_url(); ?>';
        var CSRF_NAME  = '<?php echo $csrfName; ?>';
        var csrfHash   = '<?php echo $csrfHash; ?>';
        var MAX_UPLOAD = <?php echo (int) $maxUpload; ?>;

        // Estado de la pantalla
        var st = {
            company : null,   // compañía seleccionada (clave, BD, servidor)
            profile : null,   // perfil sin credenciales
            deployID: null,   // despliegue en preparación
            pkg     : null,   // resultado de la validación del ZIP
            plan    : null,   // plan de estructura
            data    : null,   // análisis de datos
            running : false
        };

        function $(id) { return document.getElementById(id); }
        function qsa(sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); }

        /** Escapa texto para insertarlo como HTML. */
        function esc(value) {
            return String(value === null || value === undefined ? '' : value)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        }

        /** Entero con separador de miles (1,000). */
        function fmtInt(n) {
            return Number(n || 0).toLocaleString('en-US');
        }

        /** Megabytes con dos decimales (1,000.00 MB). */
        function fmtMB(bytes) {
            return (Number(bytes || 0) / 1048576).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' MB';
        }

        /** Agrega una línea al registro visible. */
        function log(message) {
            var box  = $('dplLog');
            var time = new Date().toTimeString().substr(0, 8);
            box.textContent += '[' + time + '] ' + message + '\n';
            box.scrollTop = box.scrollHeight;
        }

        /** Mensaje en un contenedor. */
        function alertHtml(kind, html) {
            return '<div class="dpl-alert is-' + kind + '">' + html + '</div>';
        }

        /**
         * Llamada POST al servidor local. Maneja los 3 casos: error, sin datos, con datos.
         * Renueva el token CSRF en cada respuesta.
         */
        function api(method, params, isForm, isRetry) {
            var body;
            if (isForm) {
                body = params;
            } else {
                body = new FormData();
                Object.keys(params || {}).forEach(function (key) {
                    body.append(key, params[key] === null || params[key] === undefined ? '' : params[key]);
                });
            }
            body.set(CSRF_NAME, csrfHash);
            return fetch(BASE + '/core_merge/' + method, {
                method     : 'POST',
                headers    : { 'X-CSRF-TOKEN': csrfHash, 'X-Requested-With': 'XMLHttpRequest' },
                body       : body,
                credentials: 'same-origin'
            }).then(function (res) {
                // 403 = token CSRF cambiado en otra pestaña o vencido: se pide uno nuevo (GET) y se reintenta una vez
                if (res.status === 403 && !isRetry) {
                    return refreshToken().then(function () { return api(method, body, true, true); });
                }
                return res.text().then(function (text) {
                    var json = null;
                    try { json = JSON.parse(text); } catch (e) { json = null; }
                    if (!json) {
                        throw new Error(res.status === 403 ? 'Sesión o token vencido (HTTP 403). Recargue la página.' : 'Respuesta inválida del servidor (HTTP ' + res.status + ').');
                    }
                    if (json.csrf) { csrfHash = json.csrf; }
                    if (json.error === true) { throw new Error(json.message || 'Error desconocido'); }
                    return json.data || {};
                });
            });
        }

        /** Obtiene el token CSRF vigente del servidor (GET, sin validación CSRF). */
        function refreshToken() {
            return fetch(BASE + '/core_merge/deploy_token', {
                method     : 'GET',
                headers    : { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            }).then(function (res) { return res.json(); }).then(function (json) {
                if (json && json.csrf) { csrfHash = json.csrf; }
            }).catch(function () { /* si falla, el reintento mostrará el error 403 */ });
        }

        /**
         * Copia un texto al portapapeles y lo indica en el botón.
         * navigator.clipboard solo existe en HTTPS/localhost; si no, se usa un textarea temporal.
         */
        function copyText(text, button) {
            var done = function (ok) {
                var original = '📋 Copiar';
                button.textContent = ok ? '✓ Copiado' : '✕ No se pudo copiar';
                setTimeout(function () { button.textContent = original; }, 2000);
            };
            var fallback = function () {
                var area = document.createElement('textarea');
                area.value = text;
                area.setAttribute('readonly', '');
                area.style.position = 'fixed';
                area.style.opacity  = '0';
                document.body.appendChild(area);
                area.select();
                var ok = false;
                try { ok = document.execCommand('copy'); } catch (e) { ok = false; }
                document.body.removeChild(area);
                done(ok);
            };
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(function () { done(true); }, fallback);
            } else {
                fallback();
            }
        }

        /** Diálogo de confirmación propio (no bloquea como window.confirm). */
        function dialog(title, html, okText, cancelText) {
            return new Promise(function (resolve) {
                $('dplDialogTitle').textContent = title;
                $('dplDialogBody').innerHTML    = html;
                $('dplDialogOk').textContent    = okText || 'Aceptar';
                $('dplDialogCancel').style.display = cancelText === false ? 'none' : '';
                $('dplOverlay').classList.add('is-open');
                function close(value) {
                    $('dplOverlay').classList.remove('is-open');
                    $('dplDialogOk').onclick = null;
                    $('dplDialogCancel').onclick = null;
                    resolve(value);
                }
                $('dplDialogOk').onclick     = function () { close(true); };
                $('dplDialogCancel').onclick = function () { close(false); };
            });
        }

        /** Deshabilita un botón mientras corre una tarea. */
        function busy(button, promise) {
            button.disabled = true;
            var old = button.innerHTML;
            button.innerHTML = '<i class="icon16 i-spinner-6"></i> Procesando...';
            return promise.then(function (r) {
                button.disabled = false; button.innerHTML = old; return r;
            }, function (e) {
                button.disabled = false; button.innerHTML = old; throw e;
            });
        }

        function requireCompany() {
            if (!st.company) {
                // El combo tiene valor pero el perfil aún no llegó (o falló su carga)
                throw new Error($('dplCompany').value ? 'Espere: se están cargando los datos de la compañía (o vuelva a seleccionarla).' : 'Seleccione una compañía.');
            }
        }

        // ---------------------------------------------------------- pestañas

        qsa('#dplCardConnection .nav-tabs a').forEach(function (link) {
            link.addEventListener('click', function (ev) {
                ev.preventDefault();
                qsa('#dplCardConnection .nav-tabs li').forEach(function (li) { li.classList.remove('active'); });
                qsa('#dplCardConnection .tab-pane').forEach(function (p) { p.classList.remove('active'); });
                link.parentNode.classList.add('active');
                document.querySelector(link.getAttribute('href')).classList.add('active');
            });
        });

        // ---------------------------------------------------------- compañía y perfil

        /** Llena el formulario con el perfil (sin credenciales). */
        function fillProfile(p) {
            $('dplAgentUrl').value     = p.agentUrl || '';
            $('dplFtpProtocol').value  = p.transportProtocol || 'sftp';
            $('dplFtpHost').value      = p.ftpHost || '';
            $('dplFtpPort').value      = p.ftpPort || '';
            $('dplFtpUser').value      = p.ftpUser || '';
            $('dplFtpRoot').value      = p.ftpRootDir || '';
            $('dplFtpApp').value       = p.ftpAppDir || '';
            $('dplDbMode').value       = p.dbMode || 'agent';
            $('dplDbHost').value       = p.dbHost || '';
            $('dplDbPort').value       = p.dbPort || '';
            $('dplDbName').value       = p.dbName || '';
            $('dplDbUser').value       = p.dbUser || '';
            $('dplFtpPassword').value  = '';
            $('dplDbPassword').value   = '';
            $('dplFtpPassword').placeholder = p.hasFtpPassword ? '•••••• guardada' : '';
            $('dplDbPassword').placeholder  = p.hasDbPassword ? '•••••• guardada' : '';
            var chip = $('dplSecretChip');
            chip.className   = 'dpl-chip ' + (p.hasAgentSecret ? 'is-ok' : 'is-warn');
            chip.textContent = 'Secreto del agente: ' + (p.hasAgentSecret ? 'configurado (cifrado)' : 'sin configurar');

            // Modos de tabla guardados para esta compañía
            var modes = p.tableModes || {};
            qsa('#dplTablesTable tbody tr').forEach(function (tr) {
                var table = tr.getAttribute('data-table');
                tr.querySelector('.dpl-mode').value = modes[table] || tr.getAttribute('data-default');
            });
            toggleDbFields();
        }

        function toggleDbFields() {
            $('dplDbFields').style.display = $('dplDbMode').value === 'direct' ? '' : 'none';
        }
        $('dplDbMode').addEventListener('change', toggleDbFields);

        function resetDeploy() {
            st.deployID = null; st.pkg = null; st.plan = null; st.data = null;
            $('dplPackageResult').innerHTML = '';
            $('dplSchemaResult').innerHTML  = '';
            $('dplDataResult').innerHTML    = '';
            $('dplChecks').innerHTML        = '';
            $('dplRollbackBox').style.display = 'none';
            $('dplBtnLog').classList.add('disabled');
            resetCounts();
            renderSummary();
        }

        /** Muestra la BD esperada, el servidor y el estado del perfil guardado de la compañía. */
        function renderCompanyChips() {
            if (!st.company || !st.profile) { return; }
            var c = st.company, p = st.profile;
            $('dplCompanyChips').innerHTML =
                '<span class="dpl-chip">BD esperada: <b>' + esc(c.dbName) + '</b></span>' +
                (c.server ? '<span class="dpl-chip">Servidor: <b>' + esc(c.server) + '</b></span>' : '') +
                '<span class="dpl-chip ' + (p.exists ? 'is-ok' : 'is-warn') + '">' + (p.exists ? 'Conexión guardada' : 'Sin conexión guardada') + '</span>' +
                (p.lastDeployOn ? '<span class="dpl-chip">Último despliegue: <b>' + esc(p.lastDeployOn) + '</b> (' + esc(p.lastDeployStatus) + ')</span>' : '');
        }

        $('dplCompany').addEventListener('change', function () {
            var key = this.value;
            resetDeploy();
            st.company = null; st.profile = null;
            $('dplCompanyChips').innerHTML = '';
            if (!key) { return; }
            api('deploy_profile', { company: key }).then(function (data) {
                if (!data.company) { throw new Error('Sin datos de la compañía.'); }
                st.company = data.company;
                st.profile = data.profile;
                fillProfile(data.profile);
                renderCompanyChips();
                renderSummary();
            }).catch(function (e) {
                $('dplCompanyChips').innerHTML = '<span class="dpl-chip is-bad">' + esc(e.message) + '</span>';
            });
        });

        /** Modos de tabla seleccionados. */
        function tableModes() {
            var modes = {};
            qsa('#dplTablesTable tbody tr').forEach(function (tr) {
                modes[tr.getAttribute('data-table')] = tr.querySelector('.dpl-mode').value;
            });
            return modes;
        }

        function profileParams() {
            return {
                company          : st.company.key,
                agentUrl         : $('dplAgentUrl').value,
                transportProtocol: $('dplFtpProtocol').value,
                ftpHost          : $('dplFtpHost').value,
                ftpPort          : $('dplFtpPort').value,
                ftpUser          : $('dplFtpUser').value,
                ftpPassword      : $('dplFtpPassword').value,
                ftpRootDir       : $('dplFtpRoot').value,
                ftpAppDir        : $('dplFtpApp').value,
                dbMode           : $('dplDbMode').value,
                dbHost           : $('dplDbHost').value,
                dbPort           : $('dplDbPort').value,
                dbName           : $('dplDbName').value,
                dbUser           : $('dplDbUser').value,
                dbPassword       : $('dplDbPassword').value,
                tableModes       : JSON.stringify(tableModes())
            };
        }

        /** Guarda el perfil (las contraseñas vacías conservan las guardadas). */
        function saveProfile() {
            requireCompany();
            return api('deploy_profile_save', profileParams()).then(function (data) {
                st.profile = data.profile;
                fillProfile(data.profile);
                renderCompanyChips();
                return data;
            });
        }

        $('dplBtnSave').addEventListener('click', function () {
            try { requireCompany(); } catch (e) { $('dplChecks').innerHTML = '<li class="is-bad"><span class="dpl-ico">✕</span>' + esc(e.message) + '</li>'; return; }
            busy(this, saveProfile()).then(function () {
                $('dplChecks').innerHTML = '<li class="is-ok"><span class="dpl-ico">✓</span>Conexión guardada (credenciales cifradas).</li>';
                renderSummary();
            }).catch(function (e) {
                $('dplChecks').innerHTML = '<li class="is-bad"><span class="dpl-ico">✕</span>' + esc(e.message) + '</li>';
            });
        });

        $('dplBtnSecret').addEventListener('click', function () {
            var button = this;
            try { requireCompany(); } catch (e) { return; }
            var warn = st.profile && st.profile.hasAgentSecret
                ? alertHtml('warn', 'Ya existe un secreto. Si genera otro, el agente del servidor dejará de aceptar la consola hasta que instale el nuevo.')
                : '';
            dialog('Generar secreto del agente', warn + '<p>Se generará un secreto aleatorio de 64 caracteres, se guardará cifrado y se mostrará <b>una sola vez</b>.</p>', 'Generar').then(function (ok) {
                if (!ok) { return; }
                busy(button, saveProfile().then(function () { return api('deploy_secret', { company: st.company.key }); })).then(function (data) {
                    st.profile.hasAgentSecret = true;
                    fillProfile(st.profile);
                    var copyButton = function (id) {
                        return '<button type="button" class="btn btn-sm btn-outline-primary dpl-copy" id="' + id + '" style="margin-top:6px;">📋 Copiar</button>';
                    };
                    dialog('Secreto del agente',
                        alertHtml('warn', 'Cópielo ahora: no se volverá a mostrar.') +
                        '<p style="margin-top:10px;">Secreto:</p><div class="dpl-secret">' + esc(data.secret) + '</div>' + copyButton('dplCopySecret') +
                        '<p style="margin-top:10px;">Si no usa "Instalar agente", cree en el servidor el archivo <b>' + esc(data.configPath) + '</b> con este contenido:</p>' +
                        '<div class="dpl-secret" style="white-space:pre-wrap;">' + esc(data.configFile) + '</div>' + copyButton('dplCopyConfig'), 'Listo', false);
                    // dialog() ya insertó el HTML: se enlazan los botones de copiar
                    $('dplCopySecret').addEventListener('click', function () { copyText(data.secret, this); });
                    $('dplCopyConfig').addEventListener('click', function () { copyText(data.configFile, this); });
                }).catch(function (e) {
                    dialog('No se pudo generar', alertHtml('bad', esc(e.message)), 'Cerrar', false);
                });
            });
        });

        $('dplBtnInstall').addEventListener('click', function () {
            var button = this;
            try { requireCompany(); } catch (e) { return; }
            dialog('Instalar agente por SFTP/FTP',
                '<p>Se subirá <b>writable/posme_agent/agent_config.php</b> (con el secreto) y su protección .htaccess.</p>' +
                '<label class="dpl-option" for="dplInstallCode"><input type="checkbox" id="dplInstallCode"><span><p class="dpl-option-title">Subir también el código del agente</p>' +
                '<p class="dpl-option-desc">Para clientes que aún no lo tienen: core_deploy_agent.php, librerías core_web_deploy y app/Config/Routes.php de esta instalación.</p></span></label>', 'Instalar').then(function (ok) {
                if (!ok) { return; }
                var includeCode = $('dplInstallCode').checked ? '1' : '0';
                busy(button, saveProfile().then(function () { return api('deploy_install', { company: st.company.key, includeCode: includeCode }); })).then(function (data) {
                    var files = (data.uploaded || []).map(function (f) { return '<li class="is-ok"><span class="dpl-ico">✓</span>' + esc(f) + '</li>'; }).join('');
                    $('dplChecks').innerHTML = '<li class="is-ok"><span class="dpl-ico">✓</span>Agente instalado.</li>' + files;
                }).catch(function (e) {
                    $('dplChecks').innerHTML = '<li class="is-bad"><span class="dpl-ico">✕</span>' + esc(e.message) + '</li>';
                });
            });
        });

        $('dplBtnTest').addEventListener('click', function () {
            try { requireCompany(); } catch (e) { return; }
            $('dplChecks').innerHTML = '<li><span class="dpl-ico">…</span>Probando conexiones...</li>';
            busy(this, saveProfile().then(function () { return api('deploy_test', { company: st.company.key }); })).then(function (data) {
                var checks = data.checks || [];
                if (!checks.length) { $('dplChecks').innerHTML = '<li>Sin resultados.</li>'; return; }
                $('dplChecks').innerHTML = checks.map(function (c) {
                    return '<li class="' + (c.ok ? 'is-ok' : 'is-bad') + '"><span class="dpl-ico">' + (c.ok ? '✓' : '✕') + '</span>' + esc(c.label) + '<span class="dpl-group">' + esc(c.group) + '</span></li>';
                }).join('');
            }).catch(function (e) {
                $('dplChecks').innerHTML = '<li class="is-bad"><span class="dpl-ico">✕</span>' + esc(e.message) + '</li>';
            });
        });

        // ---------------------------------------------------------- paquete

        /** Crea un despliegue sin paquete cuando hace falta analizar estructura o datos. */
        function ensureDeploy() {
            requireCompany();
            if (st.deployID) { return Promise.resolve(st.deployID); }
            return api('deploy_new', { company: st.company.key }).then(function (data) {
                st.deployID = data.deployID;
                $('dplBtnLog').classList.remove('disabled');
                return st.deployID;
            });
        }

        function listHtml(title, items) {
            if (!items || !items.length) { return ''; }
            return '<details style="margin-top:8px;"><summary class="dpl-link">' + esc(title) + ' (' + fmtInt(items.length) + ')</summary><div class="dpl-scroll" style="max-height:200px;margin-top:6px;"><table class="dpl-table"><tbody>' +
                items.map(function (i) { return '<tr><td>' + esc(i.path) + '</td><td>' + esc(i.reason) + '</td></tr>'; }).join('') + '</tbody></table></div></details>';
        }

        $('dplBtnPackage').addEventListener('click', function () {
            var input = $('dplZip');
            try { requireCompany(); } catch (e) { $('dplPackageResult').innerHTML = alertHtml('bad', esc(e.message)); return; }
            if (!input.files.length) { $('dplPackageResult').innerHTML = alertHtml('bad', 'Seleccione el archivo ZIP.'); return; }
            var file = input.files[0];
            if (!/\.zip$/i.test(file.name)) { $('dplPackageResult').innerHTML = alertHtml('bad', 'El archivo debe ser .zip.'); return; }
            if (file.size > MAX_UPLOAD) { $('dplPackageResult').innerHTML = alertHtml('bad', 'El ZIP supera ' + fmtMB(MAX_UPLOAD) + '.'); return; }

            var form = new FormData();
            form.append('company', st.company.key);
            form.append('deployID', st.deployID || '');
            form.append('zip_file', file);
            $('dplPackageResult').innerHTML = alertHtml('info', 'Subiendo y validando ' + esc(file.name) + ' (' + fmtMB(file.size) + ')...');

            busy(this, api('deploy_package', form, true)).then(function (data) {
                st.deployID = data.deployID;
                st.pkg      = data;
                $('dplBtnLog').classList.remove('disabled');
                var t    = data.totals || {};
                var html = '<div class="abv2-stats" style="flex-wrap:wrap;margin-top:10px;">' +
                    '<div class="abv2-stat"><span class="abv2-label">Se actualizan</span><b>' + fmtInt(t.update) + '</b></div>' +
                    '<div class="abv2-stat"><span class="abv2-label">Se conservan</span><b>' + fmtInt(t.preserved) + '</b></div>' +
                    '<div class="abv2-stat"><span class="abv2-label">Ignorados</span><b>' + fmtInt(t.ignored) + '</b></div>' +
                    '<div class="abv2-stat"><span class="abv2-label">Rechazados</span><b>' + fmtInt(t.rejected) + '</b></div></div>';
                if (data.errors && data.errors.length) {
                    html += alertHtml('bad', '<b>El ZIP no pasó la validación:</b><br>' + data.errors.slice(0, 20).map(esc).join('<br>'));
                } else if (data.lintErrors && Object.keys(data.lintErrors).length) {
                    html += alertHtml('bad', '<b>Errores de sintaxis PHP (no se puede desplegar):</b><br>' + Object.keys(data.lintErrors).slice(0, 20).map(function (k) { return esc(k) + ' — ' + esc(data.lintErrors[k]); }).join('<br>'));
                } else {
                    html += alertHtml('ok', 'Paquete listo: ' + fmtInt(data.package.files) + ' archivos, ' + fmtMB(data.package.size) + ', ' + fmtInt(data.package.chunks) + ' bloques.<br><small>SHA-256: ' + esc(data.package.sha256) + '</small>');
                }
                html += listHtml('Archivos que se conservan', data.lists.preserved);
                html += listHtml('Archivos ignorados', data.lists.ignored);
                html += listHtml('Archivos rechazados', data.lists.rejected);
                $('dplPackageResult').innerHTML = html;
                if (data.package.ok) { $('dplOptFiles').checked = true; syncOptions(); }
                renderSummary();
            }).catch(function (e) {
                st.pkg = null;
                $('dplPackageResult').innerHTML = alertHtml('bad', esc(e.message));
                renderSummary();
            });
        });

        // ---------------------------------------------------------- opciones

        function syncOptions() {
            ['dplOptFiles', 'dplOptSchema', 'dplOptData'].forEach(function (id) {
                $(id).closest('.dpl-option').classList.toggle('is-on', $(id).checked);
            });
            $('dplSchemaBox').style.display = $('dplOptSchema').checked ? '' : 'none';
            $('dplDataBox').style.display   = $('dplOptData').checked ? '' : 'none';
            renderSummary();
        }
        ['dplOptFiles', 'dplOptSchema', 'dplOptData', 'dplConfirm'].forEach(function (id) {
            $(id).addEventListener('change', syncOptions);
        });

        // ---------------------------------------------------------- estructura

        var GROUP_LABEL = { tabla: 'Tablas', columna: 'Columnas', indice: 'Índices', restriccion: 'Restricciones', funcion: 'Funciones', vista: 'Vistas', procedimiento: 'Procedimientos', trigger: 'Triggers' };

        $('dplBtnSchema').addEventListener('click', function () {
            $('dplSchemaResult').innerHTML = alertHtml('info', 'Comparando estructura local y remota...');
            busy(this, ensureDeploy().then(function (id) { return api('deploy_schema', { deployID: id }); })).then(function (plan) {
                st.plan = plan;
                var steps = plan.steps || [];
                var html  = '<div class="dpl-chips">' +
                    '<span class="dpl-chip">Local: <b>' + esc(plan.localVersion) + '</b></span>' +
                    '<span class="dpl-chip">Remoto: <b>' + esc(plan.remoteVersion) + '</b></span>' +
                    Object.keys(plan.summary || {}).map(function (g) { return '<span class="dpl-chip is-warn">' + esc(GROUP_LABEL[g] || g) + ': <b>' + fmtInt(plan.summary[g]) + '</b></span>'; }).join('') + '</div>';
                if (plan.errors && plan.errors.length) {
                    html += alertHtml('warn', '<b>Objetos que no se pudieron leer:</b><br>' + plan.errors.slice(0, 15).map(esc).join('<br>'));
                }
                if (!steps.length) {
                    html += alertHtml('ok', 'La estructura remota ya coincide con la local. No hay cambios.');
                } else {
                    var risky = steps.filter(function (s) { return s.riesgo; }).length;
                    html += alertHtml(risky ? 'warn' : 'info', fmtInt(steps.length) + ' cambios. ' + (risky ? fmtInt(risky) + ' marcados con riesgo quedan desmarcados: revíselos antes de incluirlos.' : 'Ninguno con riesgo de pérdida de datos.'));
                    html += '<div class="dpl-btns"><span class="dpl-link" id="dplSchemaAll">Marcar todos</span> · <span class="dpl-link" id="dplSchemaSafe">Solo sin riesgo</span></div>';
                    html += '<div class="dpl-scroll" style="margin-top:6px;"><table class="dpl-table"><thead><tr><th></th><th>Objeto</th><th>Cambio</th></tr></thead><tbody>' +
                        steps.map(function (s, i) {
                            return '<tr><td><input type="checkbox" class="dpl-step-chk" data-id="' + esc(s.id) + '"' + (s.riesgo ? '' : ' checked') + '></td>' +
                                '<td><b>' + esc(s.objeto) + '</b><div class="dpl-hint">' + esc(GROUP_LABEL[s.grupo] || s.grupo) + '</div></td>' +
                                '<td><span class="dpl-badge ' + (s.riesgo ? 'is-risk' : (s.accion === 'crear' || s.accion === 'agregar' ? 'is-new' : '')) + '">' + esc(s.accion) + '</span> ' + esc(s.motivo) +
                                (s.riesgo ? '<div class="dpl-hint" style="color:var(--abv2-bad);">Riesgo: ' + esc(s.riesgo) + '</div>' : '') +
                                '<div><span class="dpl-link" data-sql="' + i + '">Ver SQL</span></div><div class="dpl-sql" id="dplSql' + i + '">' + esc(s.sql.join(';\n\n')) + ';</div></td></tr>';
                        }).join('') + '</tbody></table></div>';
                }
                if (plan.extras && plan.extras.length) {
                    html += '<details style="margin-top:8px;"><summary class="dpl-link">Objetos que existen solo en el destino — no se eliminan (' + fmtInt(plan.extras.length) + ')</summary><div class="dpl-scroll" style="max-height:200px;margin-top:6px;"><table class="dpl-table"><tbody>' +
                        plan.extras.map(function (x) { return '<tr><td>' + esc(x.tipo) + '</td><td>' + esc(x.objeto) + '</td></tr>'; }).join('') + '</tbody></table></div></details>';
                }
                if (plan.notes && plan.notes.length) {
                    html += alertHtml('info', plan.notes.slice(0, 20).map(esc).join('<br>'));
                }
                $('dplSchemaResult').innerHTML = html;
                bindSchemaTable();
                renderSummary();
            }).catch(function (e) {
                st.plan = null;
                $('dplSchemaResult').innerHTML = alertHtml('bad', esc(e.message));
                renderSummary();
            });
        });

        function bindSchemaTable() {
            qsa('#dplSchemaResult [data-sql]').forEach(function (link) {
                link.addEventListener('click', function () { $('dplSql' + link.getAttribute('data-sql')).classList.toggle('is-open'); });
            });
            qsa('#dplSchemaResult .dpl-step-chk').forEach(function (chk) { chk.addEventListener('change', renderSummary); });
            if ($('dplSchemaAll')) {
                $('dplSchemaAll').addEventListener('click', function () { qsa('.dpl-step-chk').forEach(function (c) { c.checked = true; }); renderSummary(); });
                $('dplSchemaSafe').addEventListener('click', function () {
                    var risky = {};
                    (st.plan.steps || []).forEach(function (s) { risky[s.id] = !!s.riesgo; });
                    qsa('.dpl-step-chk').forEach(function (c) { c.checked = !risky[c.getAttribute('data-id')]; });
                    renderSummary();
                });
            }
        }

        function selectedStepIDs() {
            return qsa('.dpl-step-chk').filter(function (c) { return c.checked; }).map(function (c) { return c.getAttribute('data-id'); });
        }

        // ---------------------------------------------------------- datos

        function resetCounts() {
            qsa('#dplTablesTable tbody tr').forEach(function (tr) {
                tr.querySelector('.dpl-c-insert').textContent = '-';
                tr.querySelector('.dpl-c-update').textContent = '-';
                tr.querySelector('.dpl-c-delete').textContent = '-';
                tr.querySelector('.dpl-warn-cell').textContent = '';
            });
        }

        // Cambiar un modo invalida el análisis anterior
        qsa('#dplTablesTable .dpl-mode').forEach(function (select) {
            select.addEventListener('change', function () {
                if (st.data) { st.data = null; resetCounts(); $('dplDataResult').innerHTML = alertHtml('warn', 'Cambió la selección de tablas: analice los datos de nuevo.'); }
                renderSummary();
            });
        });
        $('dplRunScripts').addEventListener('change', function () {
            if (st.data) { st.data = null; $('dplDataResult').innerHTML = alertHtml('warn', 'Cambió la opción de scripts: analice los datos de nuevo.'); }
            renderSummary();
        });
        qsa('[data-dpl-all]').forEach(function (button) {
            button.addEventListener('click', function () {
                var mode = button.getAttribute('data-dpl-all');
                qsa('#dplTablesTable tbody tr').forEach(function (tr) {
                    var def = tr.getAttribute('data-default');
                    // Las tablas de solo inserción (tb_counter) nunca se pasan a "completa"
                    tr.querySelector('.dpl-mode').value = mode === 'default' || def === 'insert' ? def : mode;
                });
                st.data = null; resetCounts(); renderSummary();
            });
        });

        $('dplBtnData').addEventListener('click', function () {
            $('dplDataResult').innerHTML = alertHtml('info', 'Comparando filas locales y remotas...');
            busy(this, ensureDeploy().then(function (id) {
                return api('deploy_data', { deployID: id, tableModes: JSON.stringify(tableModes()), runScripts: $('dplRunScripts').checked ? '1' : '0' });
            })).then(function (data) {
                st.data = data;
                var tables = data.tables || {};
                qsa('#dplTablesTable tbody tr').forEach(function (tr) {
                    var item = tables[tr.getAttribute('data-table')];
                    var c    = item && item.counts;
                    tr.querySelector('.dpl-c-insert').textContent = c ? fmtInt(c.insert) : '-';
                    tr.querySelector('.dpl-c-update').textContent = c ? fmtInt(c.update) : '-';
                    tr.querySelector('.dpl-c-delete').textContent = c ? fmtInt(c.delete) : '-';
                    tr.querySelector('.dpl-warn-cell').textContent = item && item.warnings ? item.warnings.join(' ') : '';
                });
                var t    = data.totals || {};
                var html = alertHtml('info', 'Cambios en tablas: ' + fmtInt(t.insert) + ' para insertar, ' + fmtInt(t.update) + ' para actualizar, ' + fmtInt(t.delete) + ' para eliminar. Paquete de datos: ' + fmtMB(data.size) + '.');
                (data.scripts || []).forEach(function (s) {
                    html += '<div class="dpl-alert ' + (s.ddl.length ? 'is-bad' : (s.unbounded.length ? 'is-warn' : 'is-ok')) + '"><b>' + esc(s.name) + '</b>: ' + fmtInt(s.statements) + ' sentencias.';
                    if (s.ddl.length) { html += '<br>Contiene DDL (no se puede ejecutar en la transacción): ' + s.ddl.slice(0, 5).map(function (x) { return 'línea ' + x.line; }).join(', '); }
                    if (s.unbounded.length) {
                        html += '<br>' + fmtInt(s.unbounded.length) + ' sentencias sin WHERE (afectan toda la tabla):<div class="dpl-sql is-open" style="max-height:140px;">' + s.unbounded.slice(0, 30).map(function (x) { return 'L' + x.line + ': ' + esc(x.sql); }).join('\n') + '</div>';
                    }
                    html += '</div>';
                });
                $('dplDataResult').innerHTML = html;
                renderSummary();
            }).catch(function (e) {
                st.data = null;
                $('dplDataResult').innerHTML = alertHtml('bad', esc(e.message));
                renderSummary();
            });
        });

        // ---------------------------------------------------------- resumen

        /** Evalúa qué falta para poder ejecutar. */
        function readiness() {
            var opt    = { files: $('dplOptFiles').checked, schema: $('dplOptSchema').checked, data: $('dplOptData').checked };
            var issues = [];
            if (!st.company) { issues.push('Seleccione una compañía.'); }
            if (st.company && (!st.profile || !st.profile.exists)) { issues.push('Guarde la conexión remota.'); }
            if (!opt.files && !opt.schema && !opt.data) { issues.push('Seleccione al menos una opción.'); }
            if (opt.files && !(st.pkg && st.pkg.package && st.pkg.package.ok)) { issues.push('Valide un paquete ZIP sin errores.'); }
            if (opt.schema && !st.plan) { issues.push('Analice las diferencias de estructura.'); }
            if (opt.data && !st.data) { issues.push('Analice los datos.'); }
            if (opt.data && st.data && (st.data.scripts || []).some(function (s) { return s.ddl.length; })) { issues.push('Un script contiene DDL.'); }
            if (!$('dplConfirm').checked) { issues.push('Confirme que revisó los cambios.'); }
            return { opt: opt, issues: issues };
        }

        function renderSummary() {
            var r   = readiness();
            var opt = r.opt;
            var items = [];
            items.push('<li class="' + (st.company ? '' : 'is-off') + '">🏢 Compañía: <b>' + esc(st.company ? st.company.key : 'sin seleccionar') + '</b></li>');
            items.push('<li class="' + (opt.files ? '' : 'is-off') + '">📦 Archivos: ' + (opt.files ? (st.pkg && st.pkg.package && st.pkg.package.ok ? fmtInt(st.pkg.package.files) + ' archivos (' + fmtMB(st.pkg.package.size) + ')' : 'paquete pendiente') : 'no') + '</li>');
            var nSteps = st.plan ? selectedStepIDs().length : 0;
            items.push('<li class="' + (opt.schema ? '' : 'is-off') + '">🗄️ Estructura: ' + (opt.schema ? (st.plan ? fmtInt(nSteps) + ' de ' + fmtInt((st.plan.steps || []).length) + ' cambios' : 'análisis pendiente') : 'no') + '</li>');
            var dataText = 'no';
            if (opt.data) {
                if (st.data) {
                    var t = st.data.totals || {};
                    dataText = fmtInt(t.insert) + ' ins. / ' + fmtInt(t.update) + ' act. / ' + fmtInt(t.delete) + ' elim.' + ($('dplRunScripts').checked ? ' + 001_default + ' + esc(st.company ? st.company.file : '') : '');
                } else {
                    dataText = 'análisis pendiente';
                }
            }
            items.push('<li class="' + (opt.data ? '' : 'is-off') + '">⚙️ Datos: ' + dataText + '</li>');
            if (st.profile && st.profile.exists) {
                items.push('<li>🔌 Ejecución BD: <b>' + (st.profile.dbMode === 'direct' ? 'MySQL directo' : 'agente') + '</b></li>');
            }
            r.issues.forEach(function (i) { items.push('<li style="color:var(--abv2-warn);">⚠ ' + esc(i) + '</li>'); });
            $('dplSummary').innerHTML = items.join('');
            $('dplBtnRun').disabled = st.running || r.issues.length > 0;
        }

        // ---------------------------------------------------------- ejecución

        var STAGES = ['connect', 'package', 'transfer', 'extract', 'files', 'schema', 'data', 'verify'];

        function stage(name, status, message) {
            var li = document.querySelector('#dplStages li[data-stage="' + name + '"]');
            li.className = status ? 'is-' + status : '';
            li.querySelector('.dpl-stage-ico').textContent = status === 'ok' ? '✓' : (status === 'bad' ? '✕' : (STAGES.indexOf(name) + 1));
            li.querySelector('.dpl-stage-msg').textContent = message || '';
        }

        function progress(done, total, text) {
            $('dplProgressBar').style.width = Math.round((done / total) * 100) + '%';
            $('dplProgressText').textContent = text;
        }

        function setChip(text, kind) {
            var chip = $('dplStatusChip');
            chip.textContent = text;
            chip.className   = 'abv2-chip' + (kind === 'ok' ? ' is-applied' : '');
            chip.style.background = kind === 'bad' ? 'var(--abv2-bad-bg)' : '';
            chip.style.color      = kind === 'bad' ? 'var(--abv2-bad)' : '';
        }

        function step(action, params) {
            var data = { deployID: st.deployID, action: action };
            Object.keys(params || {}).forEach(function (k) { data[k] = params[k]; });
            return api('deploy_step', data);
        }

        /** Sube un archivo por bloques mostrando el avance. */
        function uploadChunks(action, totalChunks, label, base, weight) {
            var index = 0;
            function next() {
                return step(action, { index: index }).then(function (r) {
                    index++;
                    var total = r.total || totalChunks;
                    progress(base + weight * (index / total), 100, label + ': bloque ' + fmtInt(index) + ' de ' + fmtInt(total));
                    return r.done ? r : next();
                });
            }
            return next();
        }

        /** Ejecuta los pasos de estructura en lotes; se detiene en el primer error. */
        function applySchema(ids) {
            var batches = [];
            for (var i = 0; i < ids.length; i += 8) { batches.push(ids.slice(i, i + 8)); }
            var done = 0;
            function next() {
                if (!batches.length) { return Promise.resolve(done); }
                var batch = batches.shift();
                return step('schema_apply', { ids: JSON.stringify(batch) }).then(function (r) {
                    (r.results || []).forEach(function (x) { log((x.ok ? 'OK    ' : 'ERROR ') + x.id + ' (' + x.ms + ' ms)' + (x.ok ? '' : ': ' + x.error)); });
                    if (!r.ok) { throw new Error('Falló una sentencia de estructura; se detuvo el proceso. Los pasos anteriores ya quedaron aplicados.'); }
                    done += batch.length;
                    stage('schema', 'run', fmtInt(done) + ' / ' + fmtInt(ids.length));
                    progress(60 + 15 * (done / ids.length), 100, 'Estructura: ' + fmtInt(done) + ' de ' + fmtInt(ids.length));
                    return next();
                });
            }
            return next();
        }

        $('dplBtnRun').addEventListener('click', function () {
            var r = readiness();
            if (r.issues.length) { return; }
            var opt = r.opt;
            var ids = opt.schema ? selectedStepIDs() : [];
            var lines = [];
            if (opt.files)  { lines.push('<li>📦 Actualizar ' + fmtInt(st.pkg.package.files) + ' archivos de la aplicación (con respaldo)</li>'); }
            if (opt.schema) { lines.push('<li>🗄️ Aplicar ' + fmtInt(ids.length) + ' cambios de estructura (respaldo de la estructura antes)</li>'); }
            if (opt.data)   { lines.push('<li>⚙️ Sincronizar datos de configuración en una transacción (respaldo de las tablas antes)</li>'); }
            dialog('Confirmar sincronización',
                '<p>Destino: <b>' + esc(st.company.key) + '</b> — base de datos <b>' + esc(st.company.dbName) + '</b></p><ul>' + lines.join('') + '</ul>' +
                '<div class="form-group"><label class="abv2-label" for="dplConfirmName">Escriba el nombre de la compañía para confirmar</label><input type="text" class="form-control" id="dplConfirmName" autocomplete="off"></div>',
                'Ejecutar').then(function (ok) {
                if (!ok) { return; }
                if ($('dplConfirmName').value.trim() !== st.company.key) {
                    dialog('Confirmación incorrecta', alertHtml('bad', 'El nombre no coincide. No se ejecutó nada.'), 'Cerrar', false);
                    return;
                }
                runDeploy(opt, ids);
            });
        });

        /** Ejecuta todas las etapas en orden. */
        function runDeploy(opt, ids) {
            st.running = true;
            renderSummary();
            $('dplLog').textContent = '';
            $('dplRollbackBox').style.display = 'none';
            STAGES.forEach(function (s) { stage(s, '', ''); });
            setChip('En ejecución', '');
            progress(0, 100, 'Iniciando...');
            var direct = st.profile && st.profile.dbMode === 'direct';
            var failed = false;

            var chain = Promise.resolve()
                // 1. Validación de conexiones + candados
                .then(function () {
                    stage('connect', 'run');
                    log('Validando conexión y tomando el candado del despliegue ' + st.deployID);
                    return step('begin', { files: opt.files ? '1' : '0', schema: opt.schema ? '1' : '0', data: opt.data ? '1' : '0' });
                })
                .then(function () { stage('connect', 'ok', 'destino verificado'); progress(8, 100, 'Conexión validada'); })
                // 2-5. Archivos
                .then(function () {
                    if (!opt.files) { ['package', 'transfer', 'extract', 'files'].forEach(function (s) { stage(s, 'skip', 'omitido'); }); return; }
                    stage('package', 'ok', fmtInt(st.pkg.package.files) + ' archivos');
                    stage('transfer', 'run');
                    log('Transfiriendo paquete (' + fmtMB(st.pkg.package.size) + ')');
                    return uploadChunks('upload', st.pkg.package.chunks, 'Transferencia', 10, 30)
                        .then(function () { stage('transfer', 'ok', 'hash verificado'); stage('extract', 'run'); log('Descomprimiendo y verificando en el servidor'); return step('extract'); })
                        .then(function (x) { stage('extract', 'ok', fmtInt(x.extracted) + ' archivos'); stage('files', 'run'); log('Respaldando y actualizando archivos'); return step('apply_files'); })
                        .then(function (x) {
                            log('Archivos: ' + fmtInt(x.updated) + ' actualizados, ' + fmtInt(x.created) + ' nuevos, ' + fmtInt(x.unchanged) + ' sin cambios, ' + fmtInt(x.errors.length) + ' errores');
                            (x.errors || []).forEach(function (e) { log('ERROR ' + e.path + ': ' + e.error); });
                            if (x.errors && x.errors.length) { stage('files', 'bad', fmtInt(x.errors.length) + ' errores'); throw new Error('Hubo errores al actualizar archivos; puede revertirlos.'); }
                            stage('files', 'ok', fmtInt(x.updated + x.created) + ' cambiados');
                            progress(55, 100, 'Archivos actualizados');
                        });
                })
                // 6. Estructura
                .then(function () {
                    if (!opt.schema) { stage('schema', 'skip', 'omitido'); return; }
                    if (!ids.length) { stage('schema', 'ok', 'sin cambios'); return; }
                    stage('schema', 'run');
                    log('Aplicando ' + ids.length + ' cambios de estructura');
                    return applySchema(ids).then(function () { stage('schema', 'ok', fmtInt(ids.length) + ' aplicados'); });
                })
                // 7. Datos
                .then(function () {
                    if (!opt.data) { stage('data', 'skip', 'omitido'); return; }
                    stage('data', 'run');
                    var upload = direct ? Promise.resolve() : (log('Transfiriendo paquete de datos (' + fmtMB(st.data.size) + ')'), uploadChunks('data_upload', 1, 'Datos', 76, 8));
                    return upload.then(function () { log('Aplicando datos y scripts en una transacción'); return step('data_apply'); }).then(function (x) {
                        Object.keys(x.tables || {}).forEach(function (t) { var c = x.tables[t]; log(t + ': ' + fmtInt(c.insert) + ' ins., ' + fmtInt(c.update) + ' act., ' + fmtInt(c.delete) + ' elim.'); });
                        (x.scripts || []).forEach(function (s) { log('Script ' + s.name + ': ' + fmtInt(s.statements) + ' sentencias, ' + fmtInt(s.rows) + ' filas afectadas'); });
                        if (!x.ok) { stage('data', 'bad', 'revertido'); throw new Error('Datos revertidos (ROLLBACK): ' + x.error); }
                        stage('data', 'ok', 'COMMIT (' + fmtInt(x.backupRows) + ' filas respaldadas)');
                        progress(88, 100, 'Datos sincronizados');
                    });
                })
                // 8. Verificación final
                .then(function () {
                    stage('verify', 'run');
                    return step('verify').then(function (v) {
                        var msg = [];
                        if (v.files)  { msg.push(v.files.mismatchCount ? fmtInt(v.files.mismatchCount) + ' archivos distintos' : 'archivos OK'); log('Verificación de archivos: ' + fmtInt(v.files.checked) + ' revisados, ' + fmtInt(v.files.mismatchCount) + ' distintos'); }
                        if (v.schema) { msg.push(v.schema.pending ? fmtInt(v.schema.pending) + ' diferencias de estructura' : 'estructura OK'); log('Verificación de estructura: ' + fmtInt(v.schema.pending) + ' diferencias pendientes'); }
                        var bad = (v.files && v.files.mismatchCount) || false;
                        stage('verify', bad ? 'bad' : 'ok', msg.join(', ') || 'OK');
                        if (bad) { throw new Error('La verificación final encontró diferencias.'); }
                    });
                })
                .catch(function (e) {
                    failed = true;
                    log('ERROR: ' + e.message);
                    STAGES.forEach(function (s) {
                        var li = document.querySelector('#dplStages li[data-stage="' + s + '"]');
                        if (li.className === 'is-run') { stage(s, 'bad', 'error'); }
                    });
                })
                .then(function () {
                    return step('finish', { status: failed ? 'error' : 'ok' }).catch(function (e) { log('No se pudo liberar el candado: ' + e.message); });
                })
                .then(function () {
                    st.running = false;
                    progress(100, 100, failed ? 'Terminó con errores. Revise el registro.' : 'Sincronización completada.');
                    setChip(failed ? 'Con errores' : 'Completado', failed ? 'bad' : 'ok');
                    $('dplRollbackBox').style.display = (opt.files || opt.data) ? '' : 'none';
                    $('dplBtnRollbackFiles').style.display = opt.files ? '' : 'none';
                    $('dplBtnRollbackData').style.display  = opt.data ? '' : 'none';
                    renderSummary();
                });
            return chain;
        }

        function rollback(action, title) {
            dialog(title, alertHtml('warn', 'Se restaurará el respaldo tomado antes de este despliegue (' + esc(st.deployID) + ').'), 'Revertir').then(function (ok) {
                if (!ok) { return; }
                log(title + '...');
                step(action).then(function (r) {
                    log(title + ': ' + JSON.stringify(r));
                    setChip('Revertido', '');
                }).catch(function (e) { log('ERROR al revertir: ' + e.message); });
            });
        }
        $('dplBtnRollbackFiles').addEventListener('click', function () { rollback('rollback_files', 'Revertir archivos'); });
        $('dplBtnRollbackData').addEventListener('click', function () { rollback('data_restore', 'Revertir datos'); });

        $('dplBtnLog').addEventListener('click', function (ev) {
            ev.preventDefault();
            if (!st.deployID) { return; }
            window.open(BASE + '/core_merge/deploy_log?deployID=' + encodeURIComponent(st.deployID), '_blank');
        });

        // ============================================================ pestañas principales

        qsa('.dpl-maintab').forEach(function (tab) {
            tab.addEventListener('click', function () {
                qsa('.dpl-maintab').forEach(function (t) {
                    t.classList.toggle('is-active', t === tab);
                    $(t.getAttribute('data-dpl-pane')).style.display = t === tab ? '' : 'none';
                });
            });
        });

        // ============================================================ limpieza de servidor

        var cl = { scan: null, company: '', categories: [], running: false };

        /** Línea en el registro de limpieza. */
        function cleanLog(message) {
            var box  = $('dplCleanLog');
            var time = new Date().toTimeString().substr(0, 8);
            box.textContent += '[' + time + '] ' + message + '\n';
            box.scrollTop = box.scrollHeight;
        }

        /** Categorías marcadas. */
        function cleanCategories() {
            return qsa('.dpl-clean-cat').filter(function (c) { return c.checked; }).map(function (c) { return c.value; });
        }

        /** Instalaciones marcadas en la tabla del análisis. */
        function cleanSelected() {
            return qsa('#dplCleanResult .dpl-clean-inst').filter(function (c) { return c.checked && !c.disabled; }).map(function (c) { return c.value; });
        }

        /** El análisis deja de valer si cambia la compañía o las categorías. */
        function cleanInvalidate() {
            cl.scan = null;
            $('dplCleanResult').innerHTML = '<p class="dpl-hint">Pulse Analizar para ver las instalaciones y el espacio que se liberaría. Analizar no borra nada.</p>';
            renderCleanSummary();
        }

        /** Totales estimados (archivos y bytes) de las instalaciones marcadas. */
        function cleanTotals() {
            var totals = { files: 0, bytes: 0 };
            if (!cl.scan) { return totals; }
            var selected = cleanSelected();
            cl.scan.installations.forEach(function (inst) {
                if (selected.indexOf(inst.name) < 0) { return; }
                cl.categories.forEach(function (cat) {
                    var s = inst.stats[cat];
                    if (s) { totals.files += s.files; totals.bytes += s.bytes; }
                });
            });
            return totals;
        }

        /** Resumen y validaciones antes de habilitar el botón Limpiar. */
        function renderCleanSummary() {
            var issues = [];
            var selected = cleanSelected();
            if (!$('dplCleanCompany').value) { issues.push('Seleccione la compañía cuyo agente hará la limpieza.'); }
            if (!cleanCategories().length) { issues.push('Seleccione al menos una categoría.'); }
            if (!cl.scan) { issues.push('Pulse Analizar.'); }
            if (cl.scan && !selected.length) { issues.push('Marque al menos una instalación.'); }
            if ($('dplCleanConfirm').value.trim() !== 'LIMPIAR') { issues.push('Escriba LIMPIAR para confirmar.'); }

            var totals = cleanTotals();
            var html = '<li>🖥️ Agente: <b>' + esc($('dplCleanCompany').value || 'sin seleccionar') + '</b></li>';
            if (cl.scan) {
                html += '<li>📁 Hosting: <b>' + esc(cl.scan.parent) + '</b></li>' +
                    '<li>🏢 Instalaciones: <b>' + fmtInt(selected.length) + '</b> de ' + fmtInt(cl.scan.installations.length) + '</li>' +
                    '<li>🧹 Se liberarían: <b>' + fmtMB(totals.bytes) + '</b> en ' + fmtInt(totals.files) + ' archivos</li>';
            }
            if (cleanCategories().indexOf('file_company') >= 0) {
                html += '<li style="color:#c0392b;">⚠ Incluye archivos de compañía (imágenes, adjuntos, respaldos)</li>';
            }
            issues.forEach(function (i) { html += '<li class="is-off">⚠ ' + esc(i) + '</li>'; });
            $('dplCleanSummary').innerHTML = html;
            $('dplBtnClean').disabled = issues.length > 0 || cl.running;
        }

        /** Tabla de instalaciones con lo que ocupa cada categoría analizada. */
        function renderCleanResult() {
            var labels = <?php echo json_encode(array_map(function ($c) { return $c[0]; }, \App\Libraries\core_web_deploy\core_deploy_cleanup::$CATEGORIES), JSON_UNESCAPED_UNICODE); ?>;
            var scan = cl.scan;
            if (!scan.installations.length) {
                $('dplCleanResult').innerHTML = alertHtml('warn', 'No se encontraron instalaciones posMe en la carpeta ' + esc(scan.parent) + '.');
                renderCleanSummary();
                return;
            }
            var head = '<th><input type="checkbox" id="dplCleanAll" checked aria-label="Todas"></th><th>Instalación</th>';
            cl.categories.forEach(function (cat) { head += '<th class="dpl-n">' + esc(labels[cat] || cat) + '</th>'; });
            head += '<th class="dpl-n">Total</th>';
            var partial = false;
            var rows = scan.installations.map(function (inst) {
                var total = { files: 0, bytes: 0 };
                var cells = cl.categories.map(function (cat) {
                    var s = inst.stats[cat] || { files: 0, bytes: 0, partial: false };
                    total.files += s.files; total.bytes += s.bytes;
                    if (s.partial) { partial = true; }
                    return '<td class="dpl-n">' + fmtMB(s.bytes) + (s.partial ? '+' : '') + '<br><small>' + fmtInt(s.files) + ' arch.</small></td>';
                }).join('');
                var badges = (inst.self ? ' <span class="dpl-badge is-new">agente</span>' : '') +
                    (inst.locked ? ' <span class="dpl-badge is-risk">despliegue en curso</span>' : '');
                return '<tr class="' + (inst.locked ? 'is-locked' : '') + '">' +
                    '<td><input type="checkbox" class="dpl-clean-inst" value="' + esc(inst.name) + '"' + (inst.locked ? ' disabled' : ' checked') + ' aria-label="' + esc(inst.name) + '"></td>' +
                    '<td>' + esc(inst.name) + badges + '</td>' + cells +
                    '<td class="dpl-n"><b>' + fmtMB(total.bytes) + '</b><br><small>' + fmtInt(total.files) + ' arch.</small></td></tr>';
            }).join('');
            $('dplCleanResult').innerHTML =
                '<p class="dpl-hint">Hosting <b>' + esc(scan.parent) + '</b>: ' + fmtInt(scan.installations.length) + ' instalaciones posMe. Marque las que desea limpiar.</p>' +
                (partial ? alertHtml('warn', 'El análisis se detuvo por tiempo: los valores con + son parciales. La limpieza sí recorre todo.') : '') +
                '<div class="dpl-scroll"><table class="dpl-table"><thead><tr>' + head + '</tr></thead><tbody>' + rows + '</tbody></table></div>';

            $('dplCleanAll').addEventListener('change', function () {
                var on = this.checked;
                qsa('#dplCleanResult .dpl-clean-inst').forEach(function (c) { if (!c.disabled) { c.checked = on; } });
                renderCleanSummary();
            });
            qsa('#dplCleanResult .dpl-clean-inst').forEach(function (c) { c.addEventListener('change', renderCleanSummary); });
            renderCleanSummary();
        }

        qsa('.dpl-clean-cat').forEach(function (c) {
            c.closest('.dpl-option').classList.toggle('is-on', c.checked);
            c.addEventListener('change', function () {
                c.closest('.dpl-option').classList.toggle('is-on', c.checked);
                cleanInvalidate();
            });
        });
        $('dplCleanCompany').addEventListener('change', cleanInvalidate);
        $('dplCleanConfirm').addEventListener('input', renderCleanSummary);

        // Analizar: el agente lista las instalaciones vecinas y mide cada categoría (no borra)
        $('dplBtnCleanScan').addEventListener('click', function () {
            var company    = $('dplCleanCompany').value;
            var categories = cleanCategories();
            if (!company || !categories.length) { renderCleanSummary(); return; }
            $('dplCleanResult').innerHTML = '<p class="dpl-hint">Analizando el hosting… puede tardar si hay muchos archivos.</p>';
            busy(this, api('deploy_cleanup_scan', { company: company, categories: JSON.stringify(categories) })).then(function (data) {
                if (!data.installations) { throw new Error('El agente no devolvió instalaciones.'); }
                cl.scan       = data;
                cl.company    = company;
                cl.categories = categories;
                renderCleanResult();
            }).catch(function (e) {
                cl.scan = null;
                $('dplCleanResult').innerHTML = alertHtml('bad', esc(e.message));
                renderCleanSummary();
            });
        });

        /** Limpia una instalación repitiendo mientras el agente indique que quedó parcial. */
        function cleanInstallation(name, rounds) {
            return api('deploy_cleanup_apply', {
                company     : cl.company,
                installation: name,
                categories  : JSON.stringify(cl.categories),
                confirm     : 'LIMPIAR'
            }).then(function (r) {
                Object.keys(r.categories || {}).forEach(function (cat) {
                    var s = r.categories[cat];
                    cleanLog(name + ' · ' + cat + ': ' + fmtInt(s.files) + ' archivos, ' + fmtMB(s.bytes) + (s.errors ? ', ' + fmtInt(s.errors) + ' no se pudieron borrar' : ''));
                });
                if (!r.complete) {
                    if (rounds >= 50) { throw new Error('Se alcanzó el máximo de tramos; vuelva a ejecutar la limpieza.'); }
                    cleanLog(name + ': continúa (tramo ' + (rounds + 1) + ')…');
                    return cleanInstallation(name, rounds + 1);
                }
            });
        }

        $('dplBtnClean').addEventListener('click', function () {
            var selected = cleanSelected();
            var totals   = cleanTotals();
            var button   = this;
            if (!selected.length || cl.running) { return; }
            dialog('Confirmar limpieza',
                (cl.categories.indexOf('file_company') >= 0 ? alertHtml('bad', 'Se borrarán imágenes, adjuntos, reportes y respaldos de las compañías. No se puede revertir.') : '') +
                '<p>Agente: <b>' + esc(cl.company) + '</b> — hosting <b>' + esc(cl.scan.parent) + '</b></p>' +
                '<p>Se limpiarán <b>' + fmtInt(selected.length) + '</b> instalaciones (aprox. ' + fmtMB(totals.bytes) + ', ' + fmtInt(totals.files) + ' archivos):</p>' +
                '<div class="dpl-secret">' + selected.map(esc).join(', ') + '</div>',
                'Limpiar').then(function (ok) {
                if (!ok) { return; }
                cl.running = true;
                button.disabled = true;
                $('dplCleanLog').textContent = '';
                var done = 0, failed = 0;
                var chain = Promise.resolve();
                selected.forEach(function (name) {
                    chain = chain.then(function () {
                        $('dplCleanText').textContent = 'Limpiando ' + name + ' (' + fmtInt(done + 1) + ' de ' + fmtInt(selected.length) + ')…';
                        return cleanInstallation(name, 0).catch(function (e) {
                            failed++;
                            cleanLog('ERROR ' + name + ': ' + e.message);
                        }).then(function () {
                            done++;
                            $('dplCleanBar').style.width = Math.round(100 * done / selected.length) + '%';
                        });
                    });
                });
                return chain.then(function () {
                    cl.running = false;
                    $('dplCleanConfirm').value = '';
                    $('dplCleanText').textContent = failed ? 'Terminado con ' + fmtInt(failed) + ' instalaciones con error.' : 'Limpieza terminada.';
                    cleanLog('Fin: ' + fmtInt(done - failed) + ' instalaciones limpiadas, ' + fmtInt(failed) + ' con error. Pulse Analizar para ver el espacio actual.');
                    cleanInvalidate();
                });
            });
        });

        syncOptions();
        renderSummary();
        renderCleanSummary();
    })();
</script>
