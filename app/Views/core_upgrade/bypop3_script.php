<script>
    /* ==========================================================
       Actualización desde el servidor posMe (core_upgrade/byPop3)
       JS simple (sin Vue ni CDN): durante el proceso se reemplazan
       los archivos de este servidor, así que toda la lógica y el
       estado viven en memoria de esta página. Cada etapa es una
       petición corta a core_upgrade/upgrade_step.
       ========================================================== */
    (function () {
        'use strict';

        var BASE          = '<?php echo base_url(); ?>';
        var token         = <?php echo json_encode(isset($upgradeToken) ? (string) $upgradeToken : '', JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
        var DEFAULT_URL   = <?php echo json_encode(isset($defaultSourceUrl) ? (string) $defaultSourceUrl : '', JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
        var MODE_DETECTED = <?php echo json_encode(isset($modeDetected) ? (string) $modeDetected : 'local', JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
        var USER_NAME     = <?php echo json_encode(isset($userName) ? (string) $userName : '', JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
        var CONFIRM_WORD  = 'ACTUALIZAR';

        // Títulos de las etapas (mismos de la vista)
        var STAGE_TITLE = {
            1: 'Validando conexión con el servidor',
            2: 'Verificando instalación local',
            3: 'Generando respaldo de base de datos',
            4: 'Descargando archivos del servidor',
            5: 'Comparando estructuras de base de datos',
            6: 'Aplicando modificaciones',
            7: 'Validando archivos y componentes',
            8: 'Finalizando actualización',
            9: 'Reversión'
        };
        // % general al empezar cada etapa (se usa si el servidor no envía data.progress)
        var STAGE_START = { 1: 0, 2: 4, 3: 8, 4: 30, 5: 52, 6: 58, 7: 86, 8: 95, 9: 0 };
        var STAGE_END   = { 1: 4, 2: 8, 3: 30, 4: 52, 5: 58, 6: 86, 7: 95, 8: 100, 9: 100 };
        var MODE_LABEL  = { local: 'Local', cloud: 'Nube' };
        var TABLE_MODE  = { full: 'Completa (inserta, actualiza y elimina)', upsert: 'Inserta y actualiza', insert: 'Solo inserta', skip: 'No se sincroniza' };
        var MAX_LOOPS   = 200000;   // tope de seguridad para los bucles por bloques

        // Estado de la pantalla (todo en memoria)
        var st = {
            check       : null,    // última respuesta de upgrade_check
            config      : null,    // configuración (sin la clave)
            mode        : MODE_DETECTED === 'cloud' ? 'cloud' : 'local',
            checking    : false,
            upgradeID   : null,
            running     : false,   // proceso en marcha (activa beforeunload)
            stage       : 0,       // etapa en curso
            plan        : null,    // detalle de compare
            finished    : false,
            rolledBack  : false
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

        /** Cantidad de un campo que puede venir como número, arreglo u objeto. */
        function cnt(value) {
            if (Array.isArray(value)) { return value.length; }
            if (value && typeof value === 'object') { return Object.keys(value).length; }
            return Number(value || 0);
        }

        /** Agrega una línea a la bitácora visible. */
        function log(message) {
            var box  = $('upgLog');
            var time = new Date().toTimeString().substr(0, 8);
            box.textContent += '[' + time + '] ' + message + '\n';
            box.scrollTop = box.scrollHeight;
        }

        /** Copia a la bitácora las líneas que envía el servidor (data.log[]). */
        function logServer(lines) {
            (lines || []).forEach(function (line) { log(typeof line === 'string' ? line : JSON.stringify(line)); });
        }

        /** Mensaje en un contenedor. */
        function alertHtml(kind, html) {
            return '<div class="upg-alert is-' + kind + '">' + html + '</div>';
        }

        /** Bloque de indicadores (estilo ABONOS). pairs = [[etiqueta, valorHtml], ...] */
        function statsHtml(pairs) {
            return '<div class="abv2-stats upg-stats">' + pairs.map(function (p) {
                return '<div class="abv2-stat"><span class="abv2-label">' + esc(p[0]) + '</span><b>' + p[1] + '</b></div>';
            }).join('') + '</div>';
        }

        // ---------------------------------------------------------- comunicación

        /**
         * Llamada POST a core_upgrade con FormData y el encabezado X-Posme-Upgrade-Token.
         * Maneja los 3 casos: error (lanza Error con stage/action), sin datos ({}), con datos.
         * Renueva el token con cada respuesta; con 403 o token inválido pide uno nuevo y reintenta una vez.
         */
        function api(method, params, isRetry) {
            var body = new FormData();
            Object.keys(params || {}).forEach(function (key) {
                body.append(key, params[key] === null || params[key] === undefined ? '' : params[key]);
            });
            return fetch(BASE + '/core_upgrade/' + method, {
                method     : 'POST',
                headers    : { 'X-Posme-Upgrade-Token': token, 'X-Requested-With': 'XMLHttpRequest' },
                body       : body,
                credentials: 'same-origin',
                cache      : 'no-store'
            }).then(function (res) {
                return res.text().then(function (text) {
                    var json = null;
                    try { json = JSON.parse(text); } catch (e) { json = null; }
                    if (json && json.token) { token = json.token; }
                    // Sesión expirada: se envía directo a la pantalla de login
                    if (json && json.sessionExpired === true) {
                        st.running = false;
                        window.location.href = BASE + '/core_acount/login';
                        throw new Error('Su sesión ha expirado. Redirigiendo al login...');
                    }
                    // 403 o token inválido: se pide un token nuevo y se reintenta una sola vez
                    var tokenBad = res.status === 403 || (json && json.error === true && /token/i.test(String(json.message || '')));
                    if (tokenBad && !isRetry) {
                        return refreshToken().then(function () { return api(method, params, true); });
                    }
                    if (!json) {
                        throw new Error(res.status === 403 ? 'Sesión o token vencido (HTTP 403). Recargue la página.' : 'Respuesta inválida del servidor (HTTP ' + res.status + ').');
                    }
                    if (json.error === true) {
                        var err    = new Error(json.message || 'Error desconocido');
                        err.stage  = json.data && json.data.stage !== undefined ? Number(json.data.stage) : null;
                        err.action = json.data && json.data.action ? json.data.action : '';
                        err.log    = json.data && json.data.log ? json.data.log : null;
                        throw err;
                    }
                    return json.data || {};
                });
            });
        }

        /** Pide un token nuevo a upgrade_token (si falla, el reintento mostrará el error). */
        function refreshToken() {
            return fetch(BASE + '/core_upgrade/upgrade_token', {
                method     : 'POST',
                headers    : { 'X-Posme-Upgrade-Token': token, 'X-Requested-With': 'XMLHttpRequest' },
                body       : new FormData(),
                credentials: 'same-origin',
                cache      : 'no-store'
            }).then(function (res) { return res.json(); }).then(function (json) {
                if (json && json.sessionExpired === true) {
                    st.running = false;
                    window.location.href = BASE + '/core_acount/login';
                    return;
                }
                if (json && json.token) { token = json.token; }
            }).catch(function () { /* sin token nuevo */ });
        }

        /**
         * Copia un texto al portapapeles y lo indica en el botón.
         * navigator.clipboard solo existe en HTTPS/localhost; si no, se usa un textarea temporal.
         */
        function copyText(text, button) {
            var original = button.innerHTML;
            var done = function (ok) {
                button.textContent = ok ? '✓ Copiado' : '✕ No se pudo copiar';
                setTimeout(function () { button.innerHTML = original; }, 2000);
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
                $('upgDialogTitle').textContent    = title;
                $('upgDialogBody').innerHTML       = html;
                $('upgDialogOk').textContent       = okText || 'Aceptar';
                $('upgDialogCancel').textContent   = cancelText || 'Cancelar';
                $('upgDialogCancel').style.display = cancelText === false ? 'none' : '';
                $('upgOverlay').classList.add('is-open');
                function close(value) {
                    $('upgOverlay').classList.remove('is-open');
                    $('upgDialogOk').onclick     = null;
                    $('upgDialogCancel').onclick = null;
                    resolve(value);
                }
                $('upgDialogOk').onclick     = function () { close(true); };
                $('upgDialogCancel').onclick = function () { close(false); };
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

        /** Chip de estado del encabezado. kind: ok | bad | warn | '' */
        function setChip(text, kind) {
            var chip = $('upgStatusChip');
            chip.textContent = text;
            chip.className   = 'abv2-chip' + (kind === 'ok' ? ' is-applied' : '');
            chip.style.background = kind === 'bad' ? 'var(--abv2-bad-bg)' : (kind === 'info' ? 'var(--abv2-accent-bg)' : '');
            chip.style.color      = kind === 'bad' ? 'var(--abv2-bad)' : (kind === 'info' ? 'var(--abv2-accent)' : '');
        }

        // ---------------------------------------------------------- fase A: configuración

        /** Muestra la configuración (la clave solo enmascarada). */
        function renderConfig(cfg) {
            st.config = cfg || {};
            var url = st.config.sourceUrl || DEFAULT_URL || '';
            $('upgCfgUrl').value          = url;
            $('upgCfgInstallation').value = st.config.installationID || '';
            $('upgCfgSecret').value       = '';
            $('upgCfgSecret').placeholder = st.config.secretMasked ? st.config.secretMasked : 'sin configurar';
            $('upgHeaderUrl').textContent = url || 'sin configurar';
            $('upgCfgChips').innerHTML =
                '<span class="upg-chip ' + (st.config.configured ? 'is-ok' : 'is-warn') + '">' + (st.config.configured ? 'Conexión configurada' : 'Conexión sin configurar') + '</span>' +
                (st.config.secretMasked ? '<span class="upg-chip">Clave: <b>' + esc(st.config.secretMasked) + '</b></span>' : '');
        }

        /** Lee la configuración guardada (upgrade_config action=read). */
        function readConfig() {
            return api('upgrade_config', { action: 'read' }).then(function (cfg) {
                renderConfig(cfg);
            }).catch(function (e) {
                $('upgCfgResult').innerHTML = alertHtml('bad', 'No se pudo leer la configuración: ' + esc(e.message));
            });
        }

        // Guardar conexión (upgrade_config action=save) y volver a consultar
        $('upgBtnCfgSave').addEventListener('click', function () {
            var url = $('upgCfgUrl').value.trim();
            if (!url) { $('upgCfgResult').innerHTML = alertHtml('bad', 'Escriba la URL del servidor de origen.'); return; }
            if (!/^https?:\/\//i.test(url)) { $('upgCfgResult').innerHTML = alertHtml('bad', 'La URL debe empezar con https:// (o http:// si el servidor lo permite).'); return; }
            if (!$('upgCfgInstallation').value.trim()) { $('upgCfgResult').innerHTML = alertHtml('bad', 'Escriba el ID de instalación.'); return; }
            $('upgCfgResult').innerHTML = '';
            busy(this, api('upgrade_config', {
                action        : 'save',
                sourceUrl     : url,
                installationID: $('upgCfgInstallation').value.trim(),
                secret        : $('upgCfgSecret').value
            })).then(function (cfg) {
                renderConfig(cfg);
                $('upgCfgResult').innerHTML = alertHtml('ok', 'Conexión guardada.');
                runCheck();
            }).catch(function (e) {
                $('upgCfgResult').innerHTML = alertHtml('bad', esc(e.message));
            });
        });

        // ---------------------------------------------------------- fase A: consulta

        /** Consulta versiones, componentes y comprobaciones (upgrade_check). */
        function runCheck(mode) {
            st.checking = true;
            renderReady();
            setChip('Consultando...', 'info');
            var params = {};
            if (mode) { params.mode = mode; }
            var button = $('upgBtnCheck');
            return busy(button, api('upgrade_check', params)).then(function (data) {
                st.checking = false;
                if (!data || !data.local) { throw new Error('El servidor no devolvió información de la instalación.'); }
                st.check = data;
                renderCheck(data);
            }).catch(function (e) {
                st.checking = false;
                st.check    = null;
                setChip('Sin consultar', 'bad');
                $('upgVersions').innerHTML   = alertHtml('bad', 'No se pudo consultar: ' + esc(e.message));
                $('upgComponents').innerHTML = '';
                $('upgChecks').innerHTML     = '<li class="is-bad"><span class="upg-ico">✕</span>' + esc(e.message) + '</li>';
                renderReady();
            });
        }

        /** Pinta la respuesta de upgrade_check. */
        function renderCheck(d) {
            var local  = d.local || {};
            var remote = d.remote || {};
            st.mode    = d.mode === 'cloud' ? 'cloud' : 'local';
            $('upgMode').value = st.mode;
            $('upgModeHint').innerHTML = 'Detectado automáticamente: <b>' + esc(MODE_LABEL[local.modeDetected || MODE_DETECTED] || 'Local') + '</b>. ' +
                'Se está consultando como <b>' + esc(MODE_LABEL[st.mode]) + '</b>. Si no es correcto, cámbielo: se vuelve a consultar el servidor con el modo elegido.';

            // Estado de la versión
            var state, kind;
            if (!remote.reachable)  { state = 'Servidor no disponible'; kind = 'bad'; }
            else if (d.upToDate)    { state = 'Al día'; kind = 'ok'; }
            else                    { state = 'Actualización disponible'; kind = 'warn'; }
            setChip(state, kind === 'warn' ? '' : kind);

            $('upgVersions').innerHTML = statsHtml([
                ['Versión local', esc(local.version || 'sin versión')],
                ['Versión del servidor', esc(remote.reachable ? (remote.version || 'sin versión') : 'no disponible')],
                ['Publicado', esc(remote.publishedOn || '-')],
                ['Estado', '<span class="upg-badge ' + (kind === 'ok' ? 'is-new' : (kind === 'bad' ? 'is-risk' : 'is-warn')) + '">' + esc(state) + '</span>']
            ]) +
                '<div class="upg-chips">' +
                (local.publishedOn ? '<span class="upg-chip">Local publicada: <b>' + esc(local.publishedOn) + '</b></span>' : '') +
                (local.php ? '<span class="upg-chip">PHP local: <b>' + esc(local.php) + '</b></span>' : '') +
                (remote.phpMin ? '<span class="upg-chip">PHP mínimo: <b>' + esc(remote.phpMin) + '</b></span>' : '') +
                (local.dbName ? '<span class="upg-chip">Base de datos: <b>' + esc(local.dbName) + '</b></span>' : '') +
                '</div>';

            renderComponents(d);
            renderData(d);

            // Estructura
            var comp = d.components || {};
            $('upgSchemaInfo').innerHTML = comp.schema
                ? '<div class="upg-chips"><span class="upg-chip is-ok">Incluida en esta actualización</span></div>'
                : '<div class="upg-chips"><span class="upg-chip">No incluida en esta actualización</span></div>';

            // Comprobaciones
            var checks = d.checks || [];
            $('upgChecks').innerHTML = checks.length ? checks.map(function (c) {
                return '<li class="' + (c.ok ? 'is-ok' : 'is-bad') + '"><span class="upg-ico">' + (c.ok ? '✓' : '✕') + '</span>' +
                    '<span>' + esc(c.label) + (c.detail ? '<span class="upg-check-detail">' + esc(c.detail) + '</span>' : '') + '</span>' +
                    (c.group ? '<span class="upg-group">' + esc(c.group === 'local' ? 'local' : (c.group === 'remote' ? 'servidor' : c.group)) + '</span>' : '') + '</li>';
            }).join('') : '<li><span class="upg-ico">-</span>Sin comprobaciones.</li>';

            // Advertencias del servidor y mantenimiento activo
            var html = (d.warnings || []).map(function (w) { return alertHtml('warn', esc(w)); }).join('');
            var m = d.maintenance || {};
            if (m.active) {
                html += alertHtml('bad', '<b>Ya hay un modo mantenimiento activo</b>' + (m.since ? ' desde ' + esc(m.since) : '') + (m.by ? ' (por ' + esc(m.by) + ')' : '') + '. Puede haber otra actualización en curso.');
            }
            $('upgWarnings').innerHTML = html;

            // La casilla "Forzar" solo aparece si la instalación está al día
            $('upgForceBox').style.display = d.upToDate ? '' : 'none';
            if (!d.upToDate) { $('upgForce').checked = false; }
            syncForce();
            renderReady();
        }

        /** Tabla zip → destino → protegidos, con los zip faltantes en rojo. */
        function renderComponents(d) {
            var files    = (d.components && d.components.files) || [];
            var remote   = d.remote || {};
            var missing  = remote.missing || [];
            var packages = {};
            (remote.packages || []).forEach(function (p) { packages[p.name] = p; });
            if (!files.length) {
                $('upgComponents').innerHTML = alertHtml('warn', 'El servidor no indicó componentes para el modo ' + esc(MODE_LABEL[st.mode]) + '.');
                return;
            }
            var rows = files.map(function (f) {
                var isMissing = missing.indexOf(f.zip) >= 0;
                var pkg       = packages[f.zip];
                var prot      = f.protected && f.protected.length ? f.protected.map(esc).join('<br>') : '<span class="upg-hint">Lista general de protegidos</span>';
                return '<tr class="' + (isMissing ? 'is-missing' : '') + '">' +
                    '<td data-label="Paquete"><b>' + esc(f.zip) + '</b>' + (isMissing ? '<div>No está en el servidor</div>' : (pkg ? '<div class="upg-hint">' + fmtMB(pkg.size) + (pkg.mtime ? ' · ' + esc(pkg.mtime) : '') + '</div>' : '')) + '</td>' +
                    '<td data-label="Destino">' + esc(f.target) + '</td>' +
                    '<td data-label="Nunca se sobrescribe">' + prot + '</td></tr>';
            }).join('');
            var html = '<div class="upg-scroll" style="margin-top:10px;"><table class="upg-table is-stack"><thead><tr><th>Paquete</th><th>Destino</th><th>Nunca se sobrescribe</th></tr></thead><tbody>' + rows + '</tbody></table></div>';
            missing.forEach(function (z) {
                html += alertHtml('bad', '<b>No se puede continuar: falta ' + esc(z) + ' en el servidor.</b>');
            });
            html += '<p class="upg-hint">Además, siempre se protegen: .env, writable/, system/, logos y archivos de la compañía, public/.htaccess y public/index.php.' +
                (st.mode === 'local'
                    ? ' En instalación local, app/Config/Constants.php y app/Config/App.php se actualizan si vienen en syncApp.zip.'
                    : ' En la nube, app/Config/Constants.php y app/Config/App.php se conservan.') + '</p>';
            $('upgComponents').innerHTML = html;
        }

        /** Tablas de datos de configuración con su modo, dentro de un <details>. */
        function renderData(d) {
            var tables = (d.components && d.components.data && d.components.data.tables) || [];
            if (!tables.length) {
                $('upgDataTables').innerHTML = alertHtml('info', 'El servidor no indicó tablas de datos para sincronizar.');
                return;
            }
            $('upgDataTables').innerHTML = '<details style="margin-top:8px;"><summary class="upg-link">Ver las ' + fmtInt(tables.length) + ' tablas y su modo</summary>' +
                '<div class="upg-scroll" style="margin-top:6px;"><table class="upg-table is-stack"><thead><tr><th>Tabla</th><th>Modo</th></tr></thead><tbody>' +
                tables.map(function (t) {
                    return '<tr><td data-label="Tabla"><b>' + esc(t.table) + '</b></td><td data-label="Modo"><span class="upg-badge' + (t.mode === 'skip' ? '' : (t.mode === 'full' ? ' is-warn' : ' is-new')) + '">' + esc(TABLE_MODE[t.mode] || t.mode) + '</span></td></tr>';
                }).join('') + '</tbody></table></div></details>';
        }

        // Cambiar el modo vuelve a consultar con ese modo
        $('upgMode').addEventListener('change', function () {
            st.mode = this.value === 'cloud' ? 'cloud' : 'local';
            runCheck(st.mode);
        });
        $('upgBtnCheck').addEventListener('click', function () { runCheck(st.mode); });

        function syncForce() {
            $('upgForceBox').classList.toggle('is-on', $('upgForce').checked);
        }
        $('upgForce').addEventListener('change', function () { syncForce(); renderReady(); });
        $('upgConfirm').addEventListener('input', renderReady);

        /** Evalúa qué falta para poder iniciar. La palabra debe ser exacta (sin recortar espacios). */
        function readiness() {
            var issues = [];
            var d      = st.check;
            if (st.checking) { issues.push('Espere: consultando el servidor.'); }
            else if (!d) { issues.push('No hay consulta válida del servidor. Pulse "Volver a consultar".'); }
            else {
                if (!d.canRun) { issues.push('No se puede continuar: revise los componentes y las comprobaciones.'); }
                if (d.upToDate && !$('upgForce').checked) { issues.push('La instalación ya está al día. Marque "Forzar" para aplicarla de nuevo.'); }
            }
            if ($('upgConfirm').value !== CONFIRM_WORD) { issues.push('Escriba ACTUALIZAR (en mayúsculas, sin espacios) para confirmar.'); }
            return issues;
        }

        function renderReady() {
            var issues = readiness();
            $('upgIssues').innerHTML = issues.map(function (i) { return '<li class="is-off">⚠ ' + esc(i) + '</li>'; }).join('');
            $('upgBtnRun').disabled = st.running || issues.length > 0;
        }

        // ---------------------------------------------------------- fase B: etapas y avance

        var STATE_TEXT = { '': 'Pendiente', run: 'En curso', wait: 'Esperando confirmación', ok: 'Completada', bad: 'Con error', skip: 'Omitida' };

        function stageLi(n) { return document.querySelector('#upgStages li[data-stage="' + n + '"]'); }

        /** Cambia el estado de una etapa (run | wait | ok | bad | skip | ''). */
        function stage(n, status, message) {
            var li = stageLi(n);
            if (!li) { return; }
            li.className = status ? 'is-' + status : '';
            li.querySelector('.upg-stage-ico').textContent   = status === 'ok' ? '✓' : (status === 'bad' ? '✕' : n);
            li.querySelector('.upg-stage-state').textContent = STATE_TEXT[status || ''];
            if (message !== undefined) { li.querySelector('.upg-stage-msg').textContent = message || ''; }
            if (status === 'run') { st.stage = n; }
        }

        /** Detalle de la etapa dentro de su <details>. */
        function stageDetail(n, html) {
            var li = stageLi(n);
            if (!li) { return; }
            var det = li.querySelector('.upg-stage-det');
            det.querySelector('.upg-stage-detail').innerHTML = html;
            det.style.display = html ? '' : 'none';
        }

        /** Mini barra de las etapas por bloques (3, 4 y 6). */
        function stageMini(n, pct) {
            var li  = stageLi(n);
            var bar = li ? li.querySelector('.upg-progress.is-mini div') : null;
            if (bar) { bar.style.width = Math.max(0, Math.min(100, pct)) + '%'; }
        }

        /** Barra general: usa data.progress del servidor o, si no viene, el peso de la etapa. */
        function progress(n, data, fraction) {
            var pct;
            if (data && data.progress !== undefined && data.progress !== null && !isNaN(Number(data.progress))) {
                pct = Number(data.progress);
            } else {
                var f = Math.max(0, Math.min(1, fraction || 0));
                pct = STAGE_START[n] + (STAGE_END[n] - STAGE_START[n]) * f;
            }
            pct = Math.max(0, Math.min(100, Math.round(pct)));
            $('upgProgressBar').style.width = pct + '%';
            $('upgProgressText').textContent = (n >= 1 && n <= 8 ? 'Etapa ' + n + ' de 8 · ' : '') + STAGE_TITLE[n] + ' · ' + pct + '%';
        }

        function progressEnd(kind, text) {
            $('upgProgress').className = 'upg-progress' + (kind ? ' is-' + kind : '');
            if (kind === 'ok') { $('upgProgressBar').style.width = '100%'; }
            $('upgProgressText').textContent = text;
        }

        /** Un paso de upgrade_step; copia data.log a la bitácora. */
        function step(action, params) {
            var data = { upgradeID: st.upgradeID || '', action: action, mode: st.mode, force: $('upgForce').checked ? '1' : '0' };
            Object.keys(params || {}).forEach(function (k) { data[k] = params[k]; });
            return api('upgrade_step', data).then(function (r) {
                logServer(r.log);
                return r;
            }, function (e) {
                logServer(e.log);
                throw e;
            });
        }

        /**
         * Repite una acción por bloques (index 0, 1, 2...) hasta que el servidor responda done.
         * onBlock(r, index) pinta el avance de cada bloque.
         */
        function loop(action, params, onBlock) {
            var index = 0;
            function next() {
                if (index > MAX_LOOPS) { return Promise.reject(new Error('Demasiados bloques en ' + action + '; se detuvo por seguridad.')); }
                var p = {};
                Object.keys(params || {}).forEach(function (k) { p[k] = params[k]; });
                p.index = index;
                return step(action, p).then(function (r) {
                    index++;
                    if (onBlock) { onBlock(r, index); }
                    return r.done ? r : next();
                });
            }
            return next();
        }

        /** Fracción completada de un bloque (index/total del servidor o el contador local). */
        function frac(r, index) {
            var total = Number(r.total || 0);
            if (!total) { return r.done ? 1 : 0; }
            var done = r.index !== undefined && r.index !== null ? Number(r.index) + 1 : index;
            return Math.max(0, Math.min(1, done / total));
        }

        // ---------------------------------------------------------- fase B: plan de la etapa 5

        var GROUP_LABEL = { tabla: 'Tablas', columna: 'Columnas', indice: 'Índices', restriccion: 'Restricciones', funcion: 'Funciones', vista: 'Vistas', procedimiento: 'Procedimientos', trigger: 'Triggers' };

        function itemText(x) {
            if (x === null || x === undefined) { return ''; }
            if (typeof x !== 'object') { return String(x); }
            var type = x.type || x.tipo || x.group || x.grupo || '';
            var name = x.object || x.objeto || x.name || x.path || '';
            return (type ? type + ': ' : '') + (name || JSON.stringify(x));
        }

        function listDetails(title, items) {
            if (!Array.isArray(items) || !items.length) { return ''; }
            return '<details><summary class="upg-link">' + esc(title) + ' (' + fmtInt(items.length) + ')</summary><div class="upg-scroll" style="max-height:200px;"><table class="upg-table"><tbody>' +
                items.slice(0, 500).map(function (i) { return '<tr><td>' + esc(itemText(i)) + '</td></tr>'; }).join('') + '</tbody></table></div></details>';
        }

        /** Pinta el plan: archivos por zip, estructura (riesgosos desmarcados), extras y datos por tabla. */
        function renderPlan(plan) {
            var html = '';

            // Archivos por zip
            var files = plan.files || {};
            var zips  = Object.keys(files);
            html += '<p class="abv2-card-title" style="margin-top:4px;"><i class="icon16 i-archive"></i> Archivos</p>';
            if (!zips.length) {
                html += alertHtml('info', 'Sin cambios de archivos.');
            } else {
                html += '<div class="upg-scroll"><table class="upg-table is-stack"><thead><tr><th>Paquete</th><th class="upg-n">Actualizar</th><th class="upg-n">Crear</th><th class="upg-n">Sin cambios</th><th class="upg-n">Protegidos</th></tr></thead><tbody>' +
                    zips.map(function (z) {
                        var f = files[z] || {};
                        return '<tr><td data-label="Paquete"><b>' + esc(z) + '</b></td>' +
                            '<td class="upg-n" data-label="Actualizar">' + fmtInt(cnt(f.update)) + '</td>' +
                            '<td class="upg-n" data-label="Crear">' + fmtInt(cnt(f.create)) + '</td>' +
                            '<td class="upg-n" data-label="Sin cambios">' + fmtInt(cnt(f.unchanged)) + '</td>' +
                            '<td class="upg-n" data-label="Protegidos">' + fmtInt(cnt(f.protected)) + '</td></tr>';
                    }).join('') + '</tbody></table></div>';
                zips.forEach(function (z) {
                    var f = files[z] || {};
                    html += listDetails(z + ' · se actualizan', f.update) + listDetails(z + ' · se crean', f.create) + listDetails(z + ' · protegidos (no se tocan)', f.protected);
                });
            }

            // Estructura
            var schema = plan.schema || {};
            var steps  = schema.steps || [];
            html += '<p class="abv2-card-title" style="margin-top:14px;"><i class="icon16 i-database"></i> Estructura de la base de datos</p>';
            var summary = schema.summary || {};
            var groups  = Object.keys(summary);
            if (groups.length) {
                html += '<div class="upg-chips">' + groups.map(function (g) { return '<span class="upg-chip is-warn">' + esc(GROUP_LABEL[g] || g) + ': <b>' + fmtInt(summary[g]) + '</b></span>'; }).join('') + '</div>';
            }
            if (!steps.length) {
                html += alertHtml('ok', 'La estructura local ya coincide con la del servidor. No hay cambios.');
            } else {
                var risky = steps.filter(function (s) { return !!s.risk; }).length;
                html += alertHtml(risky ? 'warn' : 'info', fmtInt(steps.length) + ' cambios. ' + (risky ? fmtInt(risky) + ' con riesgo quedan desmarcados: revíselos antes de incluirlos.' : 'Ninguno con riesgo de pérdida de datos.'));
                html += '<div class="upg-btns"><span class="upg-link" id="upgSchemaAll">Marcar todos</span> · <span class="upg-link" id="upgSchemaSafe">Solo sin riesgo</span></div>';
                html += '<div class="upg-scroll" style="margin-top:6px;"><table class="upg-table"><thead><tr><th></th><th>Objeto</th><th>Cambio</th></tr></thead><tbody>' +
                    steps.map(function (s, i) {
                        var isNew = s.action === 'crear' || s.action === 'agregar' || s.action === 'create' || s.action === 'add';
                        return '<tr><td><input type="checkbox" class="upg-step-chk" aria-label="Incluir" data-id="' + esc(s.id) + '"' + (s.risk ? '' : ' checked') + '></td>' +
                            '<td><b>' + esc(s.object) + '</b><div class="upg-hint">' + esc(GROUP_LABEL[s.group] || s.group) + '</div></td>' +
                            '<td><span class="upg-badge ' + (s.risk ? 'is-risk' : (isNew ? 'is-new' : '')) + '">' + esc(s.action) + '</span>' +
                            (s.risk ? '<div class="upg-hint" style="color:var(--abv2-bad);">Riesgo: ' + esc(s.risk === true ? 'posible pérdida de datos' : s.risk) + '</div>' : '') +
                            ((s.sql || []).length ? '<div><span class="upg-link" data-sql="' + i + '">Ver SQL</span></div><div class="upg-sql" id="upgSql' + i + '">' + esc((s.sql || []).join(';\n\n')) + ';</div>' : '') +
                            '</td></tr>';
                    }).join('') + '</tbody></table></div>';
            }
            if (schema.extras && schema.extras.length) {
                html += listDetails('Objetos que existen solo en local — no se eliminan', schema.extras);
            }

            // Datos por tabla
            var data   = plan.data || {};
            var tables = data.tables || {};
            var names  = Object.keys(tables);
            html += '<p class="abv2-card-title" style="margin-top:14px;"><i class="icon16 i-cog"></i> Datos de configuración</p>';
            if (!names.length) {
                html += alertHtml('info', 'Sin cambios de datos.');
            } else {
                var tot = { insert: 0, update: 0, del: 0 };
                names.forEach(function (t) { var c = tables[t] || {}; tot.insert += cnt(c.insert); tot.update += cnt(c.update); tot.del += cnt(c['delete']); });
                html += statsHtml([['Insertar', fmtInt(tot.insert)], ['Actualizar', fmtInt(tot.update)], ['Eliminar', fmtInt(tot.del)]]);
                html += '<details style="margin-top:8px;"><summary class="upg-link">Ver detalle por tabla (' + fmtInt(names.length) + ')</summary><div class="upg-scroll" style="margin-top:6px;"><table class="upg-table is-stack"><thead><tr><th>Tabla</th><th>Modo</th><th class="upg-n">Insertar</th><th class="upg-n">Actualizar</th><th class="upg-n">Eliminar</th></tr></thead><tbody>' +
                    names.map(function (t) {
                        var c = tables[t] || {};
                        return '<tr><td data-label="Tabla"><b>' + esc(t) + '</b></td>' +
                            '<td data-label="Modo">' + esc(TABLE_MODE[c.mode] || c.mode || '') + '</td>' +
                            '<td class="upg-n" data-label="Insertar">' + fmtInt(cnt(c.insert)) + '</td>' +
                            '<td class="upg-n" data-label="Actualizar">' + fmtInt(cnt(c.update)) + '</td>' +
                            '<td class="upg-n" data-label="Eliminar">' + fmtInt(cnt(c['delete'])) + '</td></tr>';
                    }).join('') + '</tbody></table></div></details>';
            }
            html += listDetails('Scripts de parámetros que se ejecutarán', data.scripts);

            $('upgPlan').innerHTML = html;
            bindPlan(plan);
        }

        function bindPlan(plan) {
            qsa('#upgPlan [data-sql]').forEach(function (link) {
                link.addEventListener('click', function () { $('upgSql' + link.getAttribute('data-sql')).classList.toggle('is-open'); });
            });
            if ($('upgSchemaAll')) {
                $('upgSchemaAll').addEventListener('click', function () { qsa('.upg-step-chk').forEach(function (c) { c.checked = true; }); });
                $('upgSchemaSafe').addEventListener('click', function () {
                    var risky = {};
                    ((plan.schema && plan.schema.steps) || []).forEach(function (s) { risky[String(s.id)] = !!s.risk; });
                    qsa('.upg-step-chk').forEach(function (c) { c.checked = !risky[c.getAttribute('data-id')]; });
                });
            }
        }

        function selectedStepIDs() {
            return qsa('#upgPlan .upg-step-chk').filter(function (c) { return c.checked; }).map(function (c) { return c.getAttribute('data-id'); });
        }

        /** Pausa la etapa 5 hasta que el usuario pulse Continuar (true) o Cancelar (false). */
        function waitForContinue() {
            $('upgPlanCard').style.display = '';
            $('upgPlanBtns').style.display = '';
            $('upgPlanCard').scrollIntoView({ behavior: 'smooth', block: 'start' });
            return new Promise(function (resolve) {
                $('upgBtnContinue').onclick = function () {
                    var ids   = selectedStepIDs();
                    var total = qsa('#upgPlan .upg-step-chk').length;
                    dialog('Aplicar los cambios',
                        '<p>Se aplicarán <b>' + fmtInt(ids.length) + '</b> de ' + fmtInt(total) + ' cambios de estructura, los datos de configuración y los archivos.</p>' +
                        alertHtml('warn', 'A partir de aquí se modifica la instalación. Si algo falla podrá revertir con el respaldo de la etapa 3.'), 'Continuar').then(function (ok) {
                        if (!ok) { return; }
                        $('upgBtnContinue').onclick = null; $('upgBtnCancel').onclick = null;
                        $('upgPlanBtns').style.display = 'none';
                        qsa('#upgPlan .upg-step-chk').forEach(function (c) { c.disabled = true; });
                        resolve(ids);
                    });
                };
                $('upgBtnCancel').onclick = function () {
                    dialog('Cancelar la actualización', '<p>No se aplicará ningún cambio. Se desactivará el modo mantenimiento.</p>', 'Cancelar actualización', 'Seguir revisando').then(function (ok) {
                        if (!ok) { return; }
                        $('upgBtnContinue').onclick = null; $('upgBtnCancel').onclick = null;
                        $('upgPlanBtns').style.display = 'none';
                        resolve(null);
                    });
                };
            });
        }

        // ---------------------------------------------------------- fase B: ejecución

        // Iniciar: el valor debe ser EXACTAMENTE la palabra (sin trim)
        $('upgBtnRun').addEventListener('click', function () {
            if (readiness().length || st.running) { return; }
            var d = st.check;
            dialog('Iniciar actualización',
                '<p>Se actualizará esta instalación (<b>' + esc(MODE_LABEL[st.mode]) + '</b>) de la versión <b>' + esc(d.local.version || '-') + '</b> a la <b>' + esc(d.remote.version || '-') + '</b>.</p>' +
                alertHtml('bad', 'Se activará el <b>modo mantenimiento</b>: nadie podrá operar en el sistema hasta que termine. No cierre esta pestaña.'), 'Iniciar').then(function (ok) {
                if (!ok) { return; }
                runUpgrade($('upgConfirm').value);
            });
        });

        /** Recorre las 8 etapas en orden. */
        function runUpgrade(confirmValue) {
            st.running    = true;
            st.finished   = false;
            st.rolledBack = false;
            st.upgradeID  = null;
            st.stage      = 0;
            renderReady();
            $('upgPhaseA').style.display = 'none';
            $('upgPhaseB').style.display = '';
            $('upgLog').textContent = '';
            $('upgResult').innerHTML = '';
            $('upgPlanCard').style.display = 'none';
            ['upgBtnRollback', 'upgBtnBack', 'upgBtnReload'].forEach(function (id) { $(id).style.display = 'none'; });
            for (var n = 1; n <= 8; n++) { stage(n, '', ''); stageDetail(n, ''); stageMini(n, 0); }
            progressEnd('', 'Iniciando...');
            $('upgProgressBar').style.width = '0%';
            setChip('En ejecución', 'info');
            window.scrollTo(0, 0);
            var started  = Date.now();
            var canceled = false;
            var schemaIds = [];

            log('Usuario: ' + (USER_NAME || '-') + ' · modo ' + MODE_LABEL[st.mode] + (($('upgForce').checked) ? ' · forzado' : ''));

            var chain = Promise.resolve()
                // 0. Inicio: valida la confirmación, toma el candado y activa el mantenimiento
                .then(function () {
                    log('Iniciando: candado y modo mantenimiento');
                    return step('begin', { confirm: confirmValue });
                })
                .then(function (r) {
                    st.upgradeID = (r.detail && r.detail.upgradeID) || r.upgradeID || null;
                    if (!st.upgradeID) { throw new Error('El servidor no devolvió el identificador de la actualización.'); }
                    log('Actualización ' + st.upgradeID + ' iniciada. Modo mantenimiento activo.');
                })
                // 1. Conexión con el servidor
                .then(function () {
                    stage(1, 'run'); progress(1, null, 0);
                    return step('connect');
                })
                .then(function (r) {
                    var x = r.detail || {};
                    var missing = x.missing || [];
                    stageDetail(1, statsHtml([['Versión del servidor', esc(x.remoteVersion || '-')], ['Latencia', fmtInt(x.latencyMs) + ' ms'], ['Paquetes', fmtInt(cnt(x.packages))], ['Faltantes', fmtInt(missing.length)]]));
                    if (missing.length) { throw new Error('falta ' + missing.join(', ') + ' en el servidor'); }
                    stage(1, 'ok', 'Versión ' + (x.remoteVersion || '-') + ' · ' + fmtInt(x.latencyMs) + ' ms');
                    progress(1, r, 1);
                })
                // 2. Instalación local
                .then(function () {
                    stage(2, 'run'); progress(2, null, 0);
                    return step('verify_local');
                })
                .then(function (r) {
                    var x      = r.detail || {};
                    var checks = x.checks || [];
                    var okN    = checks.filter(function (c) { return c.ok; }).length;
                    stageDetail(2, statsHtml([['Comprobaciones', fmtInt(okN) + ' de ' + fmtInt(checks.length)], ['Espacio libre', fmtMB(x.diskFreeBytes)]]) +
                        (checks.length ? '<ul class="upg-checks">' + checks.map(function (c) {
                            return '<li class="' + (c.ok ? 'is-ok' : 'is-bad') + '"><span class="upg-ico">' + (c.ok ? '✓' : '✕') + '</span><span>' + esc(c.label) + (c.detail ? '<span class="upg-check-detail">' + esc(c.detail) + '</span>' : '') + '</span></li>';
                        }).join('') + '</ul>' : ''));
                    stage(2, 'ok', fmtInt(okN) + ' de ' + fmtInt(checks.length) + ' comprobaciones · ' + fmtMB(x.diskFreeBytes) + ' libres');
                    progress(2, r, 1);
                })
                // 3. Respaldo por bloques + verificación
                .then(function () {
                    stage(3, 'run'); progress(3, null, 0);
                    log('Respaldando la base de datos');
                    return loop('backup', {}, function (r, index) {
                        var x = r.detail || {};
                        var f = frac(r, index);
                        stageMini(3, f * 90);
                        stage(3, 'run', 'Parte ' + fmtInt(x.part || index) + (r.total ? ' de ' + fmtInt(r.total) : '') + ' · ' + fmtInt(x.tables) + ' tablas · ' + fmtInt(x.rows) + ' filas · ' + fmtMB(x.bytes));
                        progress(3, r, f * 0.9);
                    });
                })
                .then(function () { return step('backup_verify'); })
                .then(function (r) {
                    var x = r.detail || {};
                    stageDetail(3, statsHtml([['Partes', fmtInt(x.parts)], ['Tablas', fmtInt(x.tables)], ['Filas', fmtInt(x.rows)], ['Tamaño', fmtMB(x.bytes)]]) + (x.note ? '<p class="upg-hint">' + esc(x.note) + '</p>' : ''));
                    if (x.ok === false) { throw new Error('el respaldo no pasó la verificación' + (x.note ? ' (' + x.note + ')' : '')); }
                    stageMini(3, 100);
                    stage(3, 'ok', fmtInt(x.tables) + ' tablas · ' + fmtInt(x.rows) + ' filas · ' + fmtMB(x.bytes) + ' verificadas');
                    progress(3, r, 1);
                })
                // 4. Descarga por bloques (índice global) + extracción
                .then(function () {
                    stage(4, 'run'); progress(4, null, 0);
                    log('Descargando paquetes del servidor');
                    return loop('download', {}, function (r, index) {
                        var x = r.detail || {};
                        var f = frac(r, index);
                        stageMini(4, f * 85);
                        stage(4, 'run', (x.zip || '') + ' · bloque ' + fmtInt(x.chunk) + ' de ' + fmtInt(x.chunks) + ' · ' + fmtMB(x.bytes) + ' de ' + fmtMB(x.totalBytes));
                        progress(4, r, f * 0.85);
                    });
                })
                .then(function () {
                    stage(4, 'run', 'Descomprimiendo y verificando sintaxis PHP');
                    return step('extract');
                })
                .then(function (r) {
                    var zips = (r.detail && r.detail.zips) || [];
                    var lint = 0, files = 0;
                    var html = '<div class="upg-scroll"><table class="upg-table is-stack"><thead><tr><th>Paquete</th><th class="upg-n">Archivos</th><th class="upg-n">Protegidos</th><th class="upg-n">Rechazados</th><th class="upg-n">Errores PHP</th></tr></thead><tbody>' +
                        zips.map(function (z) {
                            lint  += cnt(z.lintErrors);
                            files += cnt(z.files);
                            return '<tr><td data-label="Paquete"><b>' + esc(z.zip) + '</b></td><td class="upg-n" data-label="Archivos">' + fmtInt(cnt(z.files)) + '</td><td class="upg-n" data-label="Protegidos">' + fmtInt(cnt(z.protected)) + '</td>' +
                                '<td class="upg-n" data-label="Rechazados">' + fmtInt(cnt(z.rejected)) + '</td><td class="upg-n" data-label="Errores PHP">' + fmtInt(cnt(z.lintErrors)) + '</td></tr>';
                        }).join('') + '</tbody></table></div>';
                    zips.forEach(function (z) {
                        html += listDetails(z.zip + ' · rechazados', z.rejected);
                        if (z.lintErrors && cnt(z.lintErrors)) {
                            var le = Array.isArray(z.lintErrors) ? z.lintErrors : (typeof z.lintErrors === 'object' ? Object.keys(z.lintErrors).map(function (k) { return k + ' — ' + z.lintErrors[k]; }) : []);
                            html += listDetails(z.zip + ' · errores de sintaxis PHP', le);
                        }
                    });
                    stageDetail(4, html);
                    // Un archivo PHP con error de sintaxis dejaría el sistema caído: no se continúa
                    if (lint) { throw new Error(fmtInt(lint) + ' archivos PHP con errores de sintaxis en los paquetes descargados'); }
                    stageMini(4, 100);
                    stage(4, 'ok', fmtInt(zips.length) + ' paquetes · ' + fmtInt(files) + ' archivos verificados');
                    progress(4, r, 1);
                })
                // 5. Comparación y pausa para revisar el plan
                .then(function () {
                    stage(5, 'run'); progress(5, null, 0);
                    return step('compare');
                })
                .then(function (r) {
                    st.plan = r.detail || {};
                    renderPlan(st.plan);
                    var steps = (st.plan.schema && st.plan.schema.steps) || [];
                    stageDetail(5, statsHtml([['Paquetes', fmtInt(Object.keys(st.plan.files || {}).length)], ['Cambios de estructura', fmtInt(steps.length)], ['Tablas de datos', fmtInt(Object.keys((st.plan.data && st.plan.data.tables) || {}).length)]]));
                    stage(5, 'wait', 'Revise el plan y pulse "Continuar con la aplicación".');
                    progress(5, r, 1);
                    setChip('Esperando confirmación', '');
                    log('Plan listo. Esperando confirmación del usuario.');
                    return waitForContinue();
                })
                .then(function (ids) {
                    if (ids === null) {
                        canceled = true;
                        stage(5, 'ok', 'Cancelado por el usuario: no se aplicó ningún cambio.');
                        for (var k = 6; k <= 8; k++) { stage(k, 'skip', 'Omitida'); }
                        log('Actualización cancelada por el usuario antes de aplicar cambios.');
                        return;
                    }
                    schemaIds = ids;
                    stage(5, 'ok', fmtInt(ids.length) + ' cambios de estructura seleccionados');
                    setChip('En ejecución', 'info');
                    log('Usuario confirmó: ' + fmtInt(ids.length) + ' cambios de estructura.');
                    return applyAll(schemaIds).then(verifyAndFinish);
                })
                .then(function () {
                    if (!canceled) { return; }
                    // Cancelado: se libera candado y mantenimiento sin aplicar nada
                    return step('finish', { status: 'error' }).catch(function (e) { log('No se pudo liberar el candado: ' + e.message); }).then(function () {
                        st.running = false;
                        progressEnd('', 'Actualización cancelada. No se aplicó ningún cambio.');
                        setChip('Cancelado', '');
                        $('upgResult').innerHTML = alertHtml('info', 'Se canceló antes de aplicar cambios. El modo mantenimiento se desactivó.');
                        $('upgBtnReload').style.display = '';
                    });
                })
                .then(function () {
                    if (canceled) { return; }
                    st.running  = false;
                    st.finished = true;
                    var secs = Math.round((Date.now() - started) / 1000);
                    progressEnd('ok', 'Actualización completada en ' + fmtInt(secs) + ' s.');
                    setChip('Completado', 'ok');
                    $('upgBtnReload').style.display = '';
                })
                .catch(function (e) { onFailure(e); });
            return chain;
        }

        /** Etapa 6: estructura en lotes de 8, datos y archivos por zip. */
        function applyAll(ids) {
            stage(6, 'run'); progress(6, null, 0);
            var detail = { schema: 0, data: null, files: [] };
            var batches = [];
            for (var i = 0; i < ids.length; i += 8) { batches.push(ids.slice(i, i + 8)); }
            var doneIds = 0;

            function renderDetail() {
                var html = statsHtml([['Estructura', fmtInt(detail.schema) + ' de ' + fmtInt(ids.length)], ['Datos', detail.data ? (detail.data.ok === false ? 'con error' : 'aplicados') : 'pendiente'], ['Paquetes', fmtInt(detail.files.length)]]);
                if (detail.files.length) {
                    html += '<div class="upg-scroll" style="margin-top:8px;"><table class="upg-table is-stack"><thead><tr><th>Paquete</th><th class="upg-n">Actualizados</th><th class="upg-n">Creados</th><th class="upg-n">Sin cambios</th><th class="upg-n">Errores</th></tr></thead><tbody>' +
                        detail.files.map(function (x) {
                            return '<tr><td data-label="Paquete"><b>' + esc(x.zip) + '</b></td><td class="upg-n" data-label="Actualizados">' + fmtInt(cnt(x.updated)) + '</td><td class="upg-n" data-label="Creados">' + fmtInt(cnt(x.created)) + '</td>' +
                                '<td class="upg-n" data-label="Sin cambios">' + fmtInt(cnt(x.unchanged)) + '</td><td class="upg-n" data-label="Errores">' + fmtInt(cnt(x.errors)) + '</td></tr>';
                        }).join('') + '</tbody></table></div>';
                }
                stageDetail(6, html);
            }

            // 6a. Estructura (se detiene en el primer error)
            function nextBatch() {
                if (!batches.length) { return Promise.resolve(); }
                var batch = batches.shift();
                return step('apply_schema', { ids: JSON.stringify(batch) }).then(function (r) {
                    var results = (r.detail && r.detail.results) || [];
                    results.forEach(function (x) { log((x.ok ? 'OK    ' : 'ERROR ') + x.id + ' (' + fmtInt(x.ms) + ' ms)' + (x.ok ? '' : ': ' + x.error)); });
                    var bad = results.filter(function (x) { return !x.ok; });
                    if (bad.length) { throw new Error('falló el cambio de estructura ' + bad[0].id + ': ' + (bad[0].error || 'error')); }
                    doneIds += batch.length;
                    detail.schema = doneIds;
                    var f = ids.length ? doneIds / ids.length : 1;
                    stageMini(6, f * 40);
                    stage(6, 'run', 'Estructura: ' + fmtInt(doneIds) + ' de ' + fmtInt(ids.length));
                    progress(6, r, f * 0.4);
                    renderDetail();
                    return nextBatch();
                });
            }

            return Promise.resolve()
                .then(function () {
                    if (!ids.length) { log('Sin cambios de estructura seleccionados.'); stageMini(6, 40); return; }
                    log('Aplicando ' + fmtInt(ids.length) + ' cambios de estructura');
                    return nextBatch();
                })
                // 6b. Datos de configuración (una transacción)
                .then(function () {
                    stage(6, 'run', 'Datos de configuración (transacción)');
                    log('Aplicando datos de configuración y scripts');
                    return step('apply_data');
                })
                .then(function (r) {
                    var x = r.detail || {};
                    detail.data = x;
                    renderDetail();
                    if (x.ok === false) { throw new Error('datos revertidos (ROLLBACK): ' + (x.error || 'error')); }
                    stageMini(6, 50);
                    progress(6, r, 0.5);
                })
                // 6c. Archivos por zip (index = zip)
                .then(function () {
                    log('Actualizando archivos');
                    return loop('apply_files', {}, function (r, index) {
                        var x = r.detail || {};
                        var f = frac(r, index);
                        detail.files.push(x);
                        renderDetail();
                        (x.errors || []).forEach(function (er) { log('ERROR ' + er.path + ': ' + er.error); });
                        log((x.zip || 'Paquete') + ': ' + fmtInt(cnt(x.updated)) + ' actualizados, ' + fmtInt(cnt(x.created)) + ' nuevos, ' + fmtInt(cnt(x.unchanged)) + ' sin cambios');
                        if (cnt(x.errors)) { throw new Error(fmtInt(cnt(x.errors)) + ' errores al actualizar ' + (x.zip || 'archivos')); }
                        if (x.health && x.health.ok === false) { throw new Error('posMe no responde después de actualizar ' + (x.zip || 'archivos') + (x.health.message ? ': ' + x.health.message : '')); }
                        stageMini(6, 50 + f * 50);
                        stage(6, 'run', 'Archivos: ' + (x.zip || '') + ' (' + fmtInt(index) + (r.total ? ' de ' + fmtInt(r.total) : '') + ')');
                        progress(6, r, 0.5 + f * 0.5);
                    });
                })
                .then(function () {
                    stageMini(6, 100);
                    var upd = 0, cre = 0;
                    detail.files.forEach(function (x) { upd += cnt(x.updated); cre += cnt(x.created); });
                    stage(6, 'ok', fmtInt(detail.schema) + ' cambios de estructura · datos aplicados · ' + fmtInt(upd + cre) + ' archivos cambiados');
                    progress(6, null, 1);
                });
        }

        /** Etapas 7 y 8: verificación y cierre. */
        function verifyAndFinish() {
            return Promise.resolve()
                .then(function () {
                    stage(7, 'run'); progress(7, null, 0);
                    return step('verify');
                })
                .then(function (r) {
                    var v        = r.detail || {};
                    var mismatch = cnt(v.files && v.files.mismatch);
                    var pending  = cnt(v.schema && v.schema.pending);
                    stageDetail(7, statsHtml([['Archivos revisados', fmtInt(v.files ? cnt(v.files.checked) : 0)], ['Distintos', fmtInt(mismatch)], ['Estructura pendiente', fmtInt(pending)], ['Datos', v.data && v.data.ok === false ? 'con diferencias' : 'OK']]) +
                        (Array.isArray(v.files && v.files.mismatch) ? listDetails('Archivos distintos', v.files.mismatch) : ''));
                    if (mismatch) { throw new Error(fmtInt(mismatch) + ' archivos no coinciden con lo descargado'); }
                    if (v.data && v.data.ok === false) { throw new Error('los datos de configuración no coinciden'); }
                    // La estructura pendiente es esperable si se dejaron cambios con riesgo sin marcar
                    if (pending) { log('Aviso: ' + fmtInt(pending) + ' diferencias de estructura quedaron pendientes (cambios no seleccionados).'); }
                    stage(7, 'ok', 'Archivos OK' + (pending ? ' · ' + fmtInt(pending) + ' cambios de estructura no aplicados' : ' · estructura OK'));
                    progress(7, r, 1);
                })
                .then(function () {
                    stage(8, 'run'); progress(8, null, 0);
                    return step('finish', { status: 'ok' });
                })
                .then(function (r) {
                    var x = r.detail || {};
                    stageDetail(8, statsHtml([['Nueva versión', esc(x.newVersion || '-')], ['Duración', fmtInt(Math.round(Number(x.durationMs || 0) / 1000)) + ' s']]));
                    stage(8, 'ok', 'Versión ' + (x.newVersion || '-') + ' · modo mantenimiento desactivado');
                    progress(8, r, 1);
                    $('upgResult').innerHTML = alertHtml('ok', '<b>posMe se actualizó correctamente</b>' + (x.newVersion ? ' a la versión ' + esc(x.newVersion) : '') + '. Pulse "Recargar posMe" para continuar.');
                    log('Actualización finalizada.');
                });
        }

        /** Error en cualquier etapa: se marca, se cierra el proceso (finish error, sin bloquear) y se ofrece Revertir. */
        function onFailure(e) {
            var n = e && e.stage !== null && e.stage !== undefined && !isNaN(e.stage) && e.stage >= 1 && e.stage <= 8 ? Number(e.stage) : st.stage;
            var message = e && e.message ? e.message : String(e);
            log('ERROR: ' + message);
            if (n >= 1 && n <= 8) {
                stage(n, 'bad', message);
            }
            // Cualquier otra etapa que haya quedado "en curso" pasa a error
            for (var k = 1; k <= 8; k++) {
                var li = stageLi(k);
                if (li && (li.className === 'is-run' || li.className === 'is-wait')) { stage(k, 'bad'); }
            }
            $('upgPlanBtns').style.display = 'none';
            st.running = false;
            progressEnd('bad', n >= 1 ? 'Falló en la etapa ' + n + ' de 8.' : 'No se pudo iniciar.');
            setChip('Con errores', 'bad');

            if (!st.upgradeID) {
                // Falló el inicio: no se tocó nada, se vuelve a la explicación
                $('upgResult').innerHTML = alertHtml('bad', '<b>No se pudo iniciar la actualización:</b> ' + esc(message));
                $('upgBtnBack').style.display = '';
                return;
            }
            $('upgResult').innerHTML = alertHtml('bad', '<b>Falló en la etapa ' + n + ' – ' + esc(STAGE_TITLE[n] || '') + ':</b> ' + esc(message) +
                '<br>Puede revertir los cambios con el respaldo tomado antes de aplicarlos.');
            $('upgBtnRollback').style.display = '';
            $('upgBtnReload').style.display   = '';
            // Se libera el candado y el mantenimiento sin bloquear la pantalla
            step('finish', { status: 'error' }).then(function () {
                log('Proceso cerrado con error. Modo mantenimiento desactivado.');
            }).catch(function (er) { log('No se pudo cerrar el proceso: ' + er.message); });
        }

        // ---------------------------------------------------------- reversión

        $('upgBtnRollback').addEventListener('click', function () {
            var button = this;
            if (!st.upgradeID || st.running) { return; }
            dialog('Revertir la actualización',
                alertHtml('warn', 'Se restaurarán los archivos y los datos de configuración que había antes de la actualización ' + esc(st.upgradeID) + '.') +
                '<label class="upg-option is-danger" for="upgIncludeDb" style="margin-top:10px;"><input type="checkbox" id="upgIncludeDb">' +
                '<span><p class="upg-option-title">Restaurar también la base de datos completa (borra lo registrado después del respaldo)</p>' +
                '<p class="upg-option-desc">Úselo solo si la estructura o los datos quedaron dañados.</p></span></label>', 'Revertir').then(function (ok) {
                if (!ok) { return; }
                var includeDb = $('upgIncludeDb') && $('upgIncludeDb').checked ? '1' : '0';
                runRollback(button, includeDb);
            });
        });
        // El diálogo se reutiliza: el estilo is-on se calcula al marcar la casilla
        document.addEventListener('change', function (ev) {
            if (ev.target && ev.target.id === 'upgIncludeDb') { ev.target.closest('.upg-option').classList.toggle('is-on', ev.target.checked); }
        });

        /** Ejecuta rollback por bloques hasta done. */
        function runRollback(button, includeDb) {
            st.running = true;
            button.disabled = true;
            $('upgBtnReload').style.display = 'none';
            setChip('Revirtiendo', 'info');
            $('upgProgress').className = 'upg-progress';
            log('Revirtiendo' + (includeDb === '1' ? ' (incluye base de datos completa)' : ''));
            var PHASE = { files: 'archivos', config_data: 'datos de configuración', db: 'base de datos' };
            loop('rollback', { includeDb: includeDb }, function (r, index) {
                var x = r.detail || {};
                progress(9, r, frac(r, index));
                $('upgProgressText').textContent = 'Reversión · ' + (PHASE[x.phase] || x.phase || '') + ' · ' + fmtInt(cnt(x.restored)) + ' restaurados';
                log('Reversión ' + (PHASE[x.phase] || x.phase || '') + ': ' + fmtInt(cnt(x.restored)) + ' restaurados');
            }).then(function () {
                st.running    = false;
                st.rolledBack = true;
                progressEnd('ok', 'Reversión completada.');
                setChip('Revertido', '');
                $('upgResult').innerHTML = alertHtml('ok', 'Se restauró la instalación anterior. Pulse "Recargar posMe".');
                $('upgBtnRollback').style.display = 'none';
                $('upgBtnReload').style.display   = '';
            }).catch(function (e) {
                st.running = false;
                button.disabled = false;
                log('ERROR al revertir: ' + e.message);
                progressEnd('bad', 'La reversión falló.');
                setChip('Reversión con errores', 'bad');
                $('upgResult').innerHTML = alertHtml('bad', '<b>No se pudo revertir:</b> ' + esc(e.message) + '<br>Puede intentarlo de nuevo.');
                $('upgBtnReload').style.display = '';
            });
        }

        // ---------------------------------------------------------- botones finales y bitácora

        $('upgBtnReload').addEventListener('click', function () {
            st.running = false;
            window.location.href = BASE + '/';
        });
        $('upgBtnBack').addEventListener('click', function () {
            $('upgPhaseB').style.display = 'none';
            $('upgPhaseA').style.display = '';
            $('upgConfirm').value = '';
            runCheck(st.mode);
        });
        $('upgBtnCopy').addEventListener('click', function () { copyText($('upgLog').textContent, this); });

        // Aviso al cerrar la pestaña con el proceso en marcha
        window.addEventListener('beforeunload', function (ev) {
            if (!st.running) { return; }
            ev.preventDefault();
            ev.returnValue = '';
            return '';
        });

        // ---------------------------------------------------------- pestaña: respaldo de base de datos

        var BKP_CONFIRM  = 'RESTAURAR';
        var SOURCE_LABEL = { manual: 'Manual', upload: 'Subido', upgrade: 'Actualización' };
        var PHASE_LABEL  = { schema: 'estructura de tablas', data: 'datos', routines: 'vistas, funciones, procedimientos y triggers', done: 'terminado' };

        // Estado de la pestaña (en memoria)
        var bk = {
            busy   : false,   // operación en curso (bloquea botones y pestañas)
            list   : [],      // respaldos guardados
            restore: null,    // restauración pendiente
            loaded : false
        };

        /** Agrega una línea a la bitácora de respaldos. */
        function bkLog(message) {
            var box  = $('bkpLog');
            var time = new Date().toTimeString().substr(0, 8);
            box.textContent += '[' + time + '] ' + message + '\n';
            box.scrollTop = box.scrollHeight;
        }

        /** Cambia de pestaña (no se permite con un proceso en marcha). */
        function showTab(name) {
            if (st.running || bk.busy) {
                dialog('Proceso en curso', '<p>Espere a que termine el proceso actual antes de cambiar de pestaña.</p>', 'Entendido', false);
                return;
            }
            $('upgTabUpdate').style.display = name === 'backup' ? 'none' : '';
            $('upgTabBackup').style.display = name === 'backup' ? '' : 'none';
            qsa('.upg-tab').forEach(function (b) { b.classList.toggle('is-active', b.getAttribute('data-tab') === name); });
            if (name === 'backup' && !bk.loaded) { loadBackups(); }
        }
        qsa('.upg-tab').forEach(function (b) {
            b.addEventListener('click', function () { showTab(b.getAttribute('data-tab')); });
        });

        /** Bloquea o libera los controles de la pestaña mientras corre una operación. */
        function bkSetBusy(on) {
            bk.busy = on;
            ['bkpBtnCreate', 'bkpBtnUpload', 'bkpBtnRefresh', 'bkpFile', 'bkpNote', 'bkpBtnResume', 'bkpBtnAbort'].forEach(function (id) { $(id).disabled = on; });
            qsa('#bkpList [data-bk]').forEach(function (b) { b.disabled = on; });
        }

        /** Muestra la tarjeta de avance con un título. */
        function bkProgressStart(title) {
            $('bkpProgressCard').style.display = '';
            $('bkpProgressTitle').textContent  = title;
            $('bkpProgress').className         = 'upg-progress';
            $('bkpProgressBar').style.width    = '0%';
            $('bkpProgressText').textContent   = 'Iniciando...';
            $('bkpResult').innerHTML           = '';
        }

        /** Actualiza la barra de avance (fraction de 0 a 1). */
        function bkProgress(fraction, text) {
            var pct = Math.max(0, Math.min(100, Math.round((fraction || 0) * 100)));
            $('bkpProgressBar').style.width  = pct + '%';
            $('bkpProgressText').textContent = pct + '% · ' + text;
        }

        /** Cierra la barra de avance con éxito o error. kind: ok | bad */
        function bkProgressEnd(kind, text, html) {
            $('bkpProgress').className       = 'upg-progress is-' + kind;
            $('bkpProgressBar').style.width  = '100%';
            $('bkpProgressText').textContent = text;
            $('bkpResult').innerHTML         = html || '';
        }

        /** Consulta los respaldos guardados. Maneja error, sin datos y con datos. */
        function loadBackups() {
            $('bkpList').innerHTML = '<p class="upg-hint">Consultando...</p>';
            return api('upgrade_backup_list', {}).then(function (d) {
                bk.loaded = true;
                renderBackups(d);
            }).catch(function (e) {
                $('bkpList').innerHTML = alertHtml('bad', esc(e.message));
            });
        }

        /** Pinta la lista de respaldos, los indicadores y la restauración pendiente. */
        function renderBackups(d) {
            bk.list    = Array.isArray(d.backups) ? d.backups : [];
            bk.restore = d.restore || null;

            // Indicadores
            var chips = ['<span class="upg-chip">Respaldos: <b>' + fmtInt(bk.list.length) + '</b></span>'];
            if (d.freeBytes !== null && d.freeBytes !== undefined) {
                chips.push('<span class="upg-chip">Espacio libre: <b>' + fmtMB(d.freeBytes) + '</b></span>');
            }
            if (d.busy) {
                chips.push('<span class="upg-chip is-warn">Proceso en curso: <b>' + esc(d.busy) + '</b></span>');
            }
            $('bkpChips').innerHTML = chips.join('');

            // Sin datos
            if (!bk.list.length) {
                $('bkpList').innerHTML = '<div class="abv2-empty"><i class="icon20 i-database"></i>Aún no hay respaldos guardados. Genere uno o suba un archivo .zip.</div>';
            } else {
                // Con datos
                $('bkpList').innerHTML = '<div class="upg-scroll"><table class="upg-table is-stack"><thead><tr>' +
                    '<th>Fecha</th><th>Origen</th><th>Base de datos</th><th class="upg-n">Tablas</th><th class="upg-n">Filas</th><th class="upg-n">Tamaño</th><th>Estado</th><th></th>' +
                    '</tr></thead><tbody>' + bk.list.map(function (b, i) {
                        var state = !b.complete ? '<span class="upg-badge is-risk">Incompleto</span>'
                            : (b.verified ? '<span class="upg-badge is-new">Verificado</span>' : '<span class="upg-badge is-warn">Sin verificar</span>');
                        var extra = [b.note, b.fileName, b.by ? 'por ' + b.by : ''].filter(function (x) { return !!x; }).join(' · ');
                        var btns  = '';
                        if (b.complete) {
                            btns += '<button type="button" class="btn btn-default btn-sm" data-bk="download" data-i="' + i + '"><i class="icon16 i-download-2"></i> Descargar</button> ';
                        }
                        if (b.complete && b.verified) {
                            btns += '<button type="button" class="btn btn-primary btn-sm" data-bk="restore" data-i="' + i + '"><i class="icon16 i-undo"></i> Restaurar</button> ';
                        }
                        if (b.origin === 'manual') {
                            btns += '<button type="button" class="btn btn-default btn-sm abv2-btn-del" data-bk="delete" data-i="' + i + '" title="Eliminar"><i class="icon16 i-remove"></i></button>';
                        }
                        return '<tr>' +
                            '<td data-label="Fecha"><b>' + esc(b.createdOn) + '</b>' + (extra ? '<span class="bkp-note">' + esc(extra) + '</span>' : '') + '</td>' +
                            '<td data-label="Origen">' + esc(SOURCE_LABEL[b.source] || b.source) + '</td>' +
                            '<td data-label="Base de datos">' + esc(b.dbName) + '</td>' +
                            '<td data-label="Tablas" class="upg-n">' + fmtInt(b.tables) + '</td>' +
                            '<td data-label="Filas" class="upg-n">' + fmtInt(b.rows) + '</td>' +
                            '<td data-label="Tamaño" class="upg-n">' + fmtMB(b.bytes) + '</td>' +
                            '<td data-label="Estado">' + state + '</td>' +
                            '<td data-label="Acciones" class="bkp-actions">' + btns + '</td>' +
                            '</tr>';
                    }).join('') + '</tbody></table></div>' +
                    '<p class="upg-hint">Los respaldos de origen <b>Actualización</b> los genera la pestaña anterior antes de actualizar; se conservan los dos últimos.</p>';
            }

            // Restauración pendiente (falló o se interrumpió)
            if (bk.restore) {
                $('bkpPendingCard').style.display = '';
                $('bkpPending').innerHTML = alertHtml('bad',
                    'La restauración del respaldo <b>' + esc(bk.restore.backupID) + '</b> iniciada el ' + esc(bk.restore.startedOn) +
                    ' por ' + esc(bk.restore.by) + ' no terminó. El sistema sigue en <b>modo mantenimiento</b> y la base de datos puede estar incompleta.' +
                    (bk.restore.lastError ? '<br>Último error: ' + esc(bk.restore.lastError) : '')) +
                    '<p class="upg-hint">Continúe para terminarla (retoma donde quedó) o cancélela para salir del modo mantenimiento.</p>';
            } else {
                $('bkpPendingCard').style.display = 'none';
            }
            bkSetBusy(bk.busy);
        }

        $('bkpBtnRefresh').addEventListener('click', function () { loadBackups(); });

        // Acciones de cada fila (descargar, restaurar, eliminar)
        $('bkpList').addEventListener('click', function (ev) {
            var button = ev.target.closest ? ev.target.closest('[data-bk]') : null;
            if (!button || button.disabled || bk.busy) { return; }
            var item = bk.list[Number(button.getAttribute('data-i'))];
            if (!item) { return; }
            var action = button.getAttribute('data-bk');
            if (action === 'download') { downloadBackup(item, button); }
            if (action === 'restore')  { askRestore(item); }
            if (action === 'delete')   { deleteBackup(item); }
        });

        /** Genera un respaldo manual por bloques: begin → step... → verify. */
        $('bkpBtnCreate').addEventListener('click', function () {
            if (bk.busy) { return; }
            var backupID = null;
            var loops    = 0;
            bkSetBusy(true);
            bkProgressStart('Generando respaldo de la base de datos');
            bkLog('Respaldo iniciado.');

            function next() {
                if (++loops > MAX_LOOPS) { throw new Error('Se superó el número máximo de bloques.'); }
                return api('upgrade_backup_create', { action: 'step', backupID: backupID }).then(function (r) {
                    bkProgress(r.unitsTotal ? r.unitsDone / r.unitsTotal : 0,
                        'Tablas ' + fmtInt(r.tables) + ' de ' + fmtInt(r.tablesAll) + ' · ' + fmtInt(r.rows) + ' filas · ' + fmtMB(r.bytes));
                    return r.done ? r : next();
                });
            }

            api('upgrade_backup_create', { action: 'begin', note: $('bkpNote').value }).then(function (r) {
                backupID = r.backupID;
                bkLog('Respaldo ' + backupID + ': ' + fmtInt(r.tablesAll) + ' tablas por copiar.');
                return next();
            }).then(function (r) {
                bkLog('Datos copiados: ' + fmtInt(r.rows) + ' filas. Verificando...');
                $('bkpProgressText').textContent = 'Verificando el respaldo...';
                return api('upgrade_backup_create', { action: 'verify', backupID: backupID });
            }).then(function (v) {
                bkLog('Respaldo verificado: ' + fmtInt(v.parts) + ' partes, ' + fmtInt(v.rows) + ' filas, ' + fmtMB(v.bytes) + '.');
                bkProgressEnd('ok', 'Respaldo generado y verificado.',
                    statsHtml([['Tablas', fmtInt(v.tables)], ['Filas', fmtInt(v.rows)], ['Tamaño', fmtMB(v.bytes)]]) +
                    (v.note ? alertHtml('info', 'Nota: ' + esc(v.note)) : ''));
                $('bkpNote').value = '';
            }).catch(function (e) {
                bkLog('ERROR: ' + e.message);
                bkProgressEnd('bad', 'No se pudo generar el respaldo.', alertHtml('bad', esc(e.message)));
            }).then(function () {
                bkSetBusy(false);
                loadBackups();
            });
        });

        /** Prepara el .zip en el servidor y lo descarga con una llave de un solo uso. */
        function downloadBackup(item, button) {
            bkLog('Preparando la descarga de ' + item.backupID + '...');
            busy(button, api('upgrade_backup_download', { origin: item.origin, backupID: item.backupID })).then(function (r) {
                if (!r.key) { throw new Error('El servidor no devolvió el enlace de descarga.'); }
                var link = document.createElement('a');
                link.href     = BASE + '/core_upgrade/upgrade_backup_file?k=' + encodeURIComponent(r.key);
                link.download = r.name || 'respaldo.zip';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                bkLog('Descarga iniciada: ' + r.name + ' (' + fmtMB(r.bytes) + ').');
            }).catch(function (e) {
                bkLog('ERROR al descargar: ' + e.message);
                dialog('No se pudo descargar', alertHtml('bad', esc(e.message)), 'Cerrar', false);
            });
        }

        /** Elimina un respaldo manual o subido tras confirmar. */
        function deleteBackup(item) {
            dialog('Eliminar respaldo', '<p>Se eliminará el respaldo del <b>' + esc(item.createdOn) + '</b> (' + esc(item.dbName) + '). Esta acción no se puede deshacer.</p>', 'Eliminar').then(function (ok) {
                if (!ok) { return; }
                bkSetBusy(true);
                api('upgrade_backup_delete', { backupID: item.backupID }).then(function () {
                    bkLog('Respaldo ' + item.backupID + ' eliminado.');
                }).catch(function (e) {
                    bkLog('ERROR al eliminar: ' + e.message);
                    dialog('No se pudo eliminar', alertHtml('bad', esc(e.message)), 'Cerrar', false);
                }).then(function () {
                    bkSetBusy(false);
                    loadBackups();
                });
            });
        }

        /** Pide la confirmación escrita y arranca la restauración. */
        function askRestore(item) {
            var html = alertHtml('bad', 'Se reemplazará <b>toda la base de datos actual</b> por el respaldo del <b>' + esc(item.createdOn) + '</b>' +
                    ' (' + esc(item.dbName) + ', ' + fmtInt(item.tables) + ' tablas, ' + fmtInt(item.rows) + ' filas). Lo registrado después de esa fecha se perderá.') +
                alertHtml('warn', 'Se activará el modo mantenimiento hasta que termine. No cierre esta pestaña.') +
                '<div class="form-group" style="margin-top:12px;"><label class="abv2-label" for="bkpConfirm">Escriba ' + BKP_CONFIRM + ' para confirmar</label>' +
                '<input type="text" class="form-control upg-confirm" id="bkpConfirm" autocomplete="off" autocapitalize="characters" autocorrect="off" spellcheck="false"></div>';
            dialog('Restaurar base de datos', html, 'Restaurar').then(function (ok) {
                if (!ok) { return; }
                var confirmValue = ($('bkpConfirm') ? $('bkpConfirm').value : '').trim();
                if (confirmValue !== BKP_CONFIRM) {
                    dialog('Confirmación incorrecta', '<p>Debe escribir <b>' + BKP_CONFIRM + '</b> en mayúsculas. No se restauró nada.</p>', 'Cerrar', false);
                    return;
                }
                bkSetBusy(true);
                bkProgressStart('Restaurando base de datos');
                bkLog('Restauración del respaldo ' + item.backupID + ' iniciada.');
                api('upgrade_backup_restore', { action: 'begin', origin: item.origin, backupID: item.backupID, confirm: confirmValue }).then(function (r) {
                    return runRestore(r.restoreID);
                }, function (e) {
                    bkLog('ERROR: ' + e.message);
                    bkProgressEnd('bad', 'No se pudo iniciar la restauración.', alertHtml('bad', esc(e.message)));
                    bkSetBusy(false);
                    loadBackups();
                });
            });
        }

        /** Restaura por bloques hasta terminar (también retoma una restauración pendiente). */
        function runRestore(restoreID) {
            var loops = 0;
            bkSetBusy(true);
            $('bkpPendingCard').style.display = 'none';

            function next() {
                if (++loops > MAX_LOOPS) { throw new Error('Se superó el número máximo de bloques.'); }
                return api('upgrade_backup_restore', { action: 'step', restoreID: restoreID }).then(function (r) {
                    bkProgress(r.total ? r.restored / r.total : 0, 'Restaurando ' + (PHASE_LABEL[r.phase] || r.phase) + ' · ' + fmtInt(r.restored) + ' de ' + fmtInt(r.total) + ' partes');
                    return r.done ? r : next();
                });
            }

            return next().then(function (r) {
                bkLog('Restauración completada (' + fmtInt(r.total) + ' partes). Mantenimiento desactivado.');
                bkProgressEnd('ok', 'Base de datos restaurada.',
                    alertHtml('ok', 'La base de datos se restauró correctamente y el modo mantenimiento se desactivó.') +
                    '<div class="upg-btns"><button type="button" class="btn btn-primary upg-btn-main" id="bkpBtnReload"><i class="icon16 i-loop-4"></i> Recargar posMe</button></div>');
                $('bkpBtnReload').addEventListener('click', function () { window.location.href = BASE + '/'; });
            }).catch(function (e) {
                bkLog('ERROR en la restauración: ' + e.message);
                bkProgressEnd('bad', 'La restauración no terminó.', alertHtml('bad', esc(e.message) + '<br>Puede continuarla o cancelarla desde la tarjeta "Restauración pendiente".'));
            }).then(function () {
                bkSetBusy(false);
                loadBackups();
            });
        }

        $('bkpBtnResume').addEventListener('click', function () {
            if (!bk.restore || bk.busy) { return; }
            bkProgressStart('Restaurando base de datos');
            bkLog('Continuando la restauración ' + bk.restore.restoreID + '...');
            runRestore(bk.restore.restoreID);
        });

        $('bkpBtnAbort').addEventListener('click', function () {
            if (!bk.restore || bk.busy) { return; }
            var restoreID = bk.restore.restoreID;
            dialog('Cancelar restauración', alertHtml('bad', 'La base de datos puede quedar <b>incompleta</b>. Úselo solo si va a restaurar otro respaldo o a revisar manualmente.'), 'Cancelar restauración', 'Volver').then(function (ok) {
                if (!ok) { return; }
                bkSetBusy(true);
                api('upgrade_backup_restore', { action: 'cancel', restoreID: restoreID }).then(function () {
                    bkLog('Restauración cancelada. Mantenimiento desactivado.');
                }).catch(function (e) {
                    bkLog('ERROR al cancelar: ' + e.message);
                    dialog('No se pudo cancelar', alertHtml('bad', esc(e.message)), 'Cerrar', false);
                }).then(function () {
                    bkSetBusy(false);
                    loadBackups();
                });
            });
        });

        /** Sube un respaldo .zip por bloques de 1 MB y lo registra en la lista. */
        $('bkpBtnUpload').addEventListener('click', function () {
            if (bk.busy) { return; }
            var file = $('bkpFile').files && $('bkpFile').files[0];
            if (!file) {
                dialog('Subir respaldo', '<p>Seleccione primero el archivo <b>.zip</b> del respaldo.</p>', 'Cerrar', false);
                return;
            }
            if (!/\.zip$/i.test(file.name)) {
                dialog('Subir respaldo', '<p>El archivo debe ser un <b>.zip</b> descargado desde esta pantalla.</p>', 'Cerrar', false);
                return;
            }
            var uploadID = null;
            var offset   = 0;
            var chunk    = 1048576;
            var loops    = 0;
            var bar      = $('bkpUpProgressBar');
            bkSetBusy(true);
            $('bkpUpProgress').style.display = '';
            $('bkpUpProgress').className     = 'upg-progress';
            bar.style.width = '0%';
            $('bkpUpText').textContent = 'Preparando la subida...';
            bkLog('Subiendo ' + file.name + ' (' + fmtMB(file.size) + ')...');

            function next() {
                if (offset >= file.size) { return Promise.resolve(); }
                if (++loops > MAX_LOOPS) { throw new Error('Se superó el número máximo de bloques.'); }
                var piece = file.slice(offset, Math.min(file.size, offset + chunk));
                return api('upgrade_backup_upload', { action: 'chunk', uploadID: uploadID, offset: offset, chunk: piece }).then(function (r) {
                    offset = Number(r.received || 0);
                    bar.style.width = Math.round(offset / file.size * 100) + '%';
                    $('bkpUpText').textContent = fmtMB(offset) + ' de ' + fmtMB(file.size);
                    return next();
                });
            }

            api('upgrade_backup_upload', { action: 'begin', size: file.size }).then(function (r) {
                uploadID = r.uploadID;
                chunk    = Number(r.chunk || chunk);
                return next();
            }).then(function () {
                $('bkpUpText').textContent = 'Archivo recibido. Extrayendo y verificando...';
                return api('upgrade_backup_upload', { action: 'finish', uploadID: uploadID, size: file.size, fileName: file.name });
            }).then(function (b) {
                $('bkpUpProgress').className = 'upg-progress is-ok';
                $('bkpUpText').textContent   = 'Respaldo subido y verificado: ' + fmtInt(b.tables) + ' tablas, ' + fmtInt(b.rows) + ' filas.';
                $('bkpFile').value = '';
                bkLog('Respaldo subido y verificado (' + b.backupID + ').');
            }).catch(function (e) {
                $('bkpUpProgress').className = 'upg-progress is-bad';
                $('bkpUpText').textContent   = 'Error: ' + e.message;
                bkLog('ERROR al subir: ' + e.message);
                if (uploadID) { api('upgrade_backup_upload', { action: 'cancel', uploadID: uploadID }).catch(function () { /* sin acción */ }); }
            }).then(function () {
                bkSetBusy(false);
                loadBackups();
            });
        });

        // Aviso al cerrar la pestaña con una operación de respaldo en marcha
        window.addEventListener('beforeunload', function (ev) {
            if (!bk.busy) { return; }
            ev.preventDefault();
            ev.returnValue = '';
            return '';
        });

        // ---------------------------------------------------------- carga inicial

        renderReady();
        readConfig().then(function () { runCheck(); });
        // #respaldo en la URL abre directo la pestaña de respaldos
        if (window.location.hash === '#respaldo') { showTab('backup'); }
    })();
</script>
