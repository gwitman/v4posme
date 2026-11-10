<?php
//posme:2026-11-09
// Login de posMe: pantalla dividida (marca + formulario), responsiva.
// Variables: $message, $parameterPrice, $parameterLabelSistem, $objCompany, $boll_, $rememberNickname, $rememberActive
$message              = isset($message) ? $message : "";
$parameterPrice       = isset($parameterPrice) ? $parameterPrice : 0;
$parameterLabelSistem = isset($parameterLabelSistem) ? $parameterLabelSistem : "posMe";
$objCompany           = isset($objCompany) ? $objCompany : null;
$boll_                = isset($boll_) ? $boll_ : "";
$rememberNickname     = isset($rememberNickname) ? $rememberNickname : "";
$rememberActive       = isset($rememberActive) ? $rememberActive : false;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="description" content="posMe">
    <meta name="theme-color" content="#00628e">
    <title>::posMe::</title>

    <link rel="apple-touch-icon" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/assets/images/apple-touch-icon.png">
    <link rel="shortcut icon" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/assets/images/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap">

    <style>
        :root {
            --brand:        #00628e;   /* azul del logo */
            --brand-deep:   #003a55;
            --accent:       #3fae55;   /* verde del logo */
            --bg:           #f3f6f8;
            --surface:      #ffffff;
            --fg:           #14232c;
            --muted:        #5d6f7a;
            --line:         #d9e2e8;
            --field:        #f7fafb;
            --danger:       #c8372d;
            --danger-soft:  #fdecea;
            --ok-soft:      #e7f6ea;
            --font-display: "Manrope", system-ui, -apple-system, "Segoe UI", sans-serif;
            --font-body:    "Inter", system-ui, -apple-system, "Segoe UI", sans-serif;
            --radius:       12px;
        }

        * { box-sizing: border-box; }
        html, body { margin: 0; }
        body { background: var(--bg); color: var(--fg); font-family: var(--font-body); font-size: 15px; line-height: 1.5; }
        [hidden] { display: none !important; }

        .shell { display: grid; grid-template-columns: 1.05fr 1fr; min-height: 100vh; }

        /* ---------- Panel de marca ---------- */
        .brand-panel {
            position: relative; overflow: hidden; color: #fff;
            background: radial-gradient(120% 90% at 0% 0%, var(--brand) 0%, var(--brand-deep) 70%);
            padding: 48px; padding-left: max(48px, env(safe-area-inset-left, 0px));
            display: flex; flex-direction: column; justify-content: space-between; gap: 40px;
        }
        .brand-panel::after {
            content: ""; position: absolute; right: -120px; bottom: -160px; width: 420px; height: 420px;
            border-radius: 50%; border: 56px solid rgba(255,255,255,.05);
        }
        .wordmark { font-family: var(--font-display); font-weight: 800; font-size: 26px; letter-spacing: -.02em; }
        .wordmark span { color: #7fdc8e; }
        .pitch h1 { font-family: var(--font-display); font-size: clamp(28px, 3.2vw, 42px); line-height: 1.12; letter-spacing: -.02em; margin: 0 0 14px; max-width: 16ch; }
        .pitch p { margin: 0; color: rgba(255,255,255,.78); max-width: 42ch; }
        .modules { display: flex; flex-wrap: wrap; gap: 8px; margin: 24px 0 0; padding: 0; list-style: none; }
        .modules li { font-size: 13px; padding: 6px 12px; border-radius: 999px; background: rgba(255,255,255,.09); border: 1px solid rgba(255,255,255,.14); }

        /* Ticket decorativo */
        .ticket {
            position: relative; z-index: 1; width: min(300px, 100%); background: #fff; color: #1b2a33;
            border-radius: 6px 6px 0 0; padding: 18px 18px 22px;
            font: 12.5px/1.55 ui-monospace, "Cascadia Mono", Consolas, monospace;
            box-shadow: 0 18px 40px rgba(0,0,0,.28); transform: rotate(-2deg);
            -webkit-mask: radial-gradient(circle 6px at 50% 100%, transparent 98%, #000) 0 0/16px 100% repeat-x;
                    mask: radial-gradient(circle 6px at 50% 100%, transparent 98%, #000) 0 0/16px 100% repeat-x;
        }
        .ticket .t-h { text-align: center; font-weight: 700; letter-spacing: .08em; }
        .ticket .t-s { text-align: center; color: #5d6f7a; }
        .ticket .row { display: flex; justify-content: space-between; font-variant-numeric: tabular-nums; }
        .ticket hr { border: 0; border-top: 1px dashed #9aa9b2; margin: 8px 0; }
        .ticket .tot { font-weight: 700; font-size: 14px; }

        /* ---------- Columna del formulario ---------- */
        .form-col {
            display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 24px;
            padding-top: 40px; padding-bottom: 40px;
            padding-left: max(16px, env(safe-area-inset-left, 0px)); padding-right: max(16px, env(safe-area-inset-right, 0px));
        }
        .card {
            width: 100%; max-width: 420px; background: var(--surface); border: 1px solid var(--line); border-radius: 18px; padding: 32px 28px;
            box-shadow: 0 1px 2px rgba(10,30,45,.04), 0 12px 32px rgba(10,30,45,.06);
        }
        .logo { display: block; width: 150px; max-width: 100%; height: auto; margin: 0 auto 6px; }
        .sys-label { text-align: center; color: var(--muted); font-size: 13px; margin: 0 0 22px; }
        .card h2 { font-family: var(--font-display); font-size: 22px; margin: 0 0 4px; letter-spacing: -.01em; }
        .card .sub { color: var(--muted); margin: 0 0 22px; font-size: 14px; }

        .alert { display: flex; gap: 10px; align-items: flex-start; background: var(--danger-soft); color: var(--danger); border-radius: 10px; padding: 10px 12px; font-size: 14px; margin-bottom: 18px; }
        .alert svg { flex: none; margin-top: 2px; }

        form { display: flex; flex-direction: column; gap: 16px; margin: 0; }
        .field label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
        .control { position: relative; display: flex; align-items: center; }
        .control > svg { position: absolute; left: 13px; color: var(--muted); pointer-events: none; }
        .control input, .control select {
            width: 100%; height: 48px; border: 1px solid var(--line); background: var(--field); color: var(--fg);
            border-radius: var(--radius); padding: 0 44px 0 42px; font: inherit; font-size: 16px;
            transition: border-color .15s, box-shadow .15s;
        }
        .control select { padding-right: 14px; }
        .control input:focus, .control select:focus { outline: none; border-color: var(--brand); box-shadow: 0 0 0 4px rgba(0,98,142,.18); }
        .eye { position: absolute; right: 6px; width: 36px; height: 36px; border: 0; background: none; color: var(--muted); border-radius: 8px; cursor: pointer; display: grid; place-items: center; }
        .eye:hover { background: var(--line); }
        .saved-tag { position: absolute; right: 10px; font-size: 11.5px; font-weight: 600; color: var(--accent); background: var(--ok-soft); padding: 2px 8px; border-radius: 999px; pointer-events: none; }
        .field-err { color: var(--danger); font-size: 12.5px; margin-top: 6px; }

        .eye:focus-visible, .btn:focus-visible, .link:focus-visible, .x:focus-visible, .social a:focus-visible, .check input:focus-visible { outline: 2px solid var(--brand); outline-offset: 2px; }

        .row-between { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
        .check { display: inline-flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer; min-height: 32px; }
        .check input { width: 18px; height: 18px; accent-color: var(--brand); margin: 0; }
        .link { color: var(--brand); font-size: 14px; font-weight: 500; text-decoration: none; background: none; border: 0; padding: 0; cursor: pointer; font-family: inherit; min-height: 32px; }
        .link:hover { text-decoration: underline; }
        .remember-hint { font-size: 12.5px; color: var(--muted); margin: -8px 0 0; }

        .btn { height: 50px; border: 0; border-radius: var(--radius); font: 600 16px var(--font-body); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; transition: filter .15s, transform .05s; }
        .btn:active { transform: translateY(1px); }
        .btn[disabled] { opacity: .75; cursor: progress; }
        .btn-primary { background: var(--brand); color: #fff; }
        .btn-primary:hover { filter: brightness(1.08); }
        .btn-pay { background: var(--accent); color: #06240d; }
        .btn-pay:hover { filter: brightness(1.06); }
        .btn-ghost { background: transparent; color: var(--fg); border: 1px solid var(--line); }
        .btn-ghost:hover { border-color: var(--brand); color: var(--brand); }
        .btn-row { display: flex; gap: 10px; }
        .btn-row .btn { flex: 1; }
        .spinner { width: 18px; height: 18px; border: 2px solid rgba(255,255,255,.45); border-top-color: #fff; border-radius: 50%; animation: spin .7s linear infinite; }
        .btn-pay .spinner { border-color: rgba(6,36,13,.3); border-top-color: #06240d; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Pago de suscripcion */
        .pay { border: 1px dashed var(--line); border-radius: 14px; padding: 14px; display: flex; flex-direction: column; gap: 14px; }
        .switch { display: flex; align-items: center; justify-content: space-between; gap: 12px; cursor: pointer; position: relative; }
        .switch .txt b { display: block; font-size: 14px; }
        .switch .txt small { color: var(--muted); font-size: 12.5px; }
        .switch input { position: absolute; opacity: 0; width: 1px; height: 1px; }
        .track { flex: none; width: 44px; height: 26px; border-radius: 999px; background: var(--line); position: relative; transition: background .2s; }
        .track::after { content: ""; position: absolute; top: 3px; left: 3px; width: 20px; height: 20px; border-radius: 50%; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,.25); transition: transform .2s; }
        .switch input:checked + .track { background: var(--accent); }
        .switch input:checked + .track::after { transform: translateX(18px); }
        .switch input:focus-visible + .track { outline: 2px solid var(--brand); outline-offset: 2px; }
        .pay-body { display: flex; flex-direction: column; gap: 12px; }

        .foot { text-align: center; color: var(--muted); font-size: 12.5px; display: flex; flex-direction: column; gap: 8px; align-items: center; }
        .foot p { margin: 0; }
        .social { display: flex; gap: 6px; }
        .social a { width: 36px; height: 36px; border-radius: 50%; display: grid; place-items: center; color: var(--muted); border: 1px solid var(--line); }
        .social a:hover { color: var(--brand); border-color: var(--brand); }

        /* Modales */
        .modal-pm {
            position: fixed; inset: 0; background: rgba(5,15,22,.6); display: flex; align-items: center; justify-content: center; z-index: 1000;
            padding: 16px max(16px, env(safe-area-inset-right, 0px)) 16px max(16px, env(safe-area-inset-left, 0px));
        }
        .modal-box { background: var(--surface); border-radius: 16px; max-width: 400px; width: 100%; padding: 26px 24px; text-align: center; box-shadow: 0 24px 60px rgba(0,0,0,.35); }
        .modal-box.left { text-align: left; }
        .modal-box .head { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; }
        .modal-box .ico { width: 52px; height: 52px; border-radius: 50%; display: grid; place-items: center; margin: 0 auto 12px; }
        .modal-box.left .ico { margin: 0 0 12px; }
        .ico.danger { background: var(--danger-soft); color: var(--danger); }
        .ico.ok { background: var(--ok-soft); color: var(--accent); margin: 0 auto 12px !important; }
        .ico.info { background: rgba(0,98,142,.12); color: var(--brand); }
        .modal-box h3 { font-family: var(--font-display); margin: 0 0 6px; font-size: 18px; }
        .modal-box p { color: var(--muted); margin: 0 0 18px; font-size: 14px; }
        .modal-box form { gap: 14px; }
        .x { width: 36px; height: 36px; border: 0; background: none; color: var(--muted); border-radius: 8px; cursor: pointer; display: grid; place-items: center; flex: none; }
        .x:hover { background: var(--line); }

        /* ---------- Responsive ---------- */
        @media (max-width: 900px) {
            .shell { grid-template-columns: 1fr; min-height: auto; }
            .brand-panel { padding: 22px max(16px, env(safe-area-inset-right, 0px)) 34px max(16px, env(safe-area-inset-left, 0px)); gap: 0; }
            .brand-panel::after, .pitch p, .modules, .ticket { display: none; }
            .pitch h1 { font-size: 20px; margin: 6px 0 0; max-width: none; }
            .form-col { justify-content: flex-start; padding-top: 0; padding-bottom: 32px; }
            .card { margin-top: -18px; padding: 24px 20px; border-radius: 16px; }
        }
        @media (prefers-reduced-motion: reduce) { * { transition: none !important; animation: none !important; } }
    </style>
</head>

<body>
<div class="shell">

    <!-- Panel de marca -->
    <aside class="brand-panel">
        <div class="wordmark">pos<span>Me</span></div>
        <div class="pitch">
            <h1>Tu negocio completo, en una sola pantalla.</h1>
            <p>Facturación, inventario, caja y créditos sincronizados por sucursal. Ingresa con tu usuario para continuar donde lo dejaste.</p>
            <ul class="modules">
                <li>Facturación</li><li>Inventario</li><li>Caja</li><li>Cuentas por cobrar</li><li>Contabilidad</li><li>Planilla</li><li>Taller</li>
            </ul>
        </div>
        <div class="ticket" aria-hidden="true">
            <div class="t-h">FACTURA #FAC-00154</div>
            <div class="t-s">Sucursal Central · Managua</div>
            <hr>
            <div class="row"><span>2 × Aceite 1L</span><span>C$ 230.00</span></div>
            <div class="row"><span>1 × Arroz 5lb</span><span>C$ 185.50</span></div>
            <div class="row"><span>3 × Jabón</span><span>C$ 96.00</span></div>
            <hr>
            <div class="row"><span>IVA 15%</span><span>C$ 76.73</span></div>
            <div class="row tot"><span>TOTAL</span><span>C$ 588.23</span></div>
        </div>
    </aside>

    <!-- Formulario -->
    <main class="form-col">
        <div class="card">
            <img class="logo" src="<?php echo base_url(); ?>/resource/img/logos/posme.svg" width="150" height="71" alt="posMe">
            <p class="sys-label" id="lablTrheePoint"><?php echo $parameterLabelSistem; ?></p>

            <h2>Iniciar sesión</h2>
            <p class="sub">Ingresa tus credenciales para acceder.</p>

            <?php if($message != ""): ?>
            <div class="alert" role="alert">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg>
                <span><?php echo $message; ?></span>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo base_url(); ?>/core_acount/login" id="login-form" autocomplete="off">
                <!-- 1 = usar la contraseña guardada en la cookie cifrada (Recordarme) -->
                <input type="hidden" name="txtRememberUse" id="txtRememberUse" value="<?php echo $rememberActive ? "1" : "0"; ?>">

                <div class="field">
                    <label for="txtNickname">Usuario</label>
                    <div class="control">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
                        <input type="text" name="txtNickname" id="txtNickname" autocomplete="off" autocapitalize="none" spellcheck="false" placeholder="Tu usuario" value="<?php echo esc($rememberNickname, 'attr'); ?>">
                    </div>
                </div>

                <div class="field">
                    <label for="txtPassword">Contraseña</label>
                    <div class="control">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
                        <!-- En modo Recordarme el campo solo muestra puntos de relleno: la contraseña real nunca llega al navegador -->
                        <input type="password" name="txtPassword" id="txtPassword" autocomplete="off" placeholder="••••••••" value="<?php echo $rememberActive ? "**********" : ""; ?>">
                        <span class="saved-tag" id="savedTag" <?php echo $rememberActive ? "" : "hidden"; ?>>Guardada</span>
                        <button type="button" class="eye" id="btnEye" aria-label="Mostrar contraseña" <?php echo $rememberActive ? "hidden" : ""; ?>>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <div class="row-between">
                    <label class="check"><input type="checkbox" id="inputCheckbox" name="remember" <?php echo $rememberActive ? "checked" : ""; ?>><span>Recordarme</span></label>
                    <button type="button" class="link" id="btnForgot">¿Olvidaste tu contraseña?</button>
                </div>
                <p class="remember-hint" id="rememberHint" <?php echo $rememberActive ? "" : "hidden"; ?>>Tu usuario y contraseña quedarán guardados de forma cifrada en este equipo.</p>

                <button type="submit" class="btn btn-primary" id="btnLogin">Ingresar</button>

                <!-- Pago de suscripcion: mismos name inputCheckBoxPayment / txtPagarCantidadDe -->
                <div class="pay">
                    <label class="switch" for="inputCheckboxPayment">
                        <span class="txt"><b>Pagar suscripción</b><small>Renueva tu plan antes de ingresar</small></span>
                        <input type="checkbox" id="inputCheckboxPayment" name="inputCheckBoxPayment">
                        <span class="track" aria-hidden="true"></span>
                    </label>
                    <div class="pay-body" id="divPagosMeses" hidden>
                        <div class="field">
                            <label for="txtPagarCantidadDe">Cantidad de meses</label>
                            <div class="control">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
                                <select id="txtPagarCantidadDe" name="txtPagarCantidadDe">
                                    <option value="">Seleccionar</option>
                                    <?php for($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i.($i == 1 ? " mes" : " meses")." — $ ".number_format(round($parameterPrice * $i,2),2); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="field-err" id="payErr" hidden>Selecciona la cantidad de meses a pagar.</div>
                        </div>
                        <button type="submit" class="btn btn-pay" id="divPagosMesesBoton">Pagar</button>
                    </div>
                </div>
            </form>
        </div>

        <footer class="foot">
            <p>Aplicación elaborada por <?php echo $parameterLabelSistem; ?> · © <?php echo date("Y"); ?>. Todos los derechos reservados</p>
            <div class="social">
                <a href="https://www.facebook.com/profile.php?id=100085523680343" target="_blank" rel="noopener" aria-label="Facebook">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M14 8h3V4h-3c-2.8 0-5 2.2-5 5v2H6v4h3v7h4v-7h3l1-4h-4V9c0-.6.4-1 1-1z"/></svg>
                </a>
            </div>
        </footer>
    </main>
</div>

<!-- Modal: permiso de ubicacion (CORE_TRAKING_GPS) -->
<div id="modal" class="modal-pm" hidden>
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="mdlGpsT">
        <div class="ico danger"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s7-6.2 7-12a7 7 0 0 0-14 0c0 5.8 7 12 7 12z"/><circle cx="12" cy="10" r="2.5"/></svg></div>
        <h3 id="mdlGpsT">Permiso de ubicación requerido</h3>
        <p>Esta empresa registra la ubicación al iniciar sesión. Activa el permiso de localización en tu navegador y vuelve a intentarlo.</p>
        <button class="btn btn-primary" type="button" onclick="cerrarModal()">Entendido</button>
    </div>
</div>

<!-- Modal: recuperar contrasena (POST core_acount/rememberpassword) -->
<div id="modalForgot" class="modal-pm" hidden>
    <div class="modal-box left" role="dialog" aria-modal="true" aria-labelledby="fgT">
        <!-- Paso 1: pedir correo -->
        <div id="fgStep1">
            <div class="head">
                <div class="ico info"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg></div>
                <button type="button" class="x" id="fgClose" aria-label="Cerrar"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6 6 18"/></svg></button>
            </div>
            <h3 id="fgT">Recuperar contraseña</h3>
            <p>Escribe el correo registrado en tu usuario. Te enviaremos tus datos de acceso.</p>
            <form id="formForgot" autocomplete="off" novalidate>
                <div class="field">
                    <label for="txtEmail">Correo electrónico</label>
                    <div class="control">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/></svg>
                        <input type="email" id="txtEmail" name="txtEmail" autocomplete="off" inputmode="email" autocapitalize="none" placeholder="usuario@empresa.com">
                    </div>
                    <div class="field-err" id="fgErr" hidden></div>
                </div>
                <div class="btn-row">
                    <button type="button" class="btn btn-ghost" id="fgCancel">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="fgSend">Enviar</button>
                </div>
            </form>
        </div>
        <!-- Paso 2: confirmacion -->
        <div id="fgStep2" hidden style="text-align:center">
            <div class="ico ok"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M5 12.5 10 17l9-10"/></svg></div>
            <h3>Revisa tu correo</h3>
            <p>Enviamos tus datos de acceso a <b id="fgMailOk"></b>. Si no lo ves en unos minutos, revisa la carpeta de spam.</p>
            <button type="button" class="btn btn-primary" id="fgDone">Volver al inicio de sesión</button>
        </div>
    </div>
</div>

<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/jquery/jquery.js"></script>
<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/genyx-fn.js"></script>
<script>
var companyName = '<?php echo (!$objCompany ? "" : addslashes($objCompany->name)); ?>';
var boll_       = '<?php echo (!$boll_ ? "" : $boll_); ?>';

function mostrarModal() { document.getElementById('modal').hidden = false; }
function cerrarModal()  { document.getElementById('modal').hidden = true;  }

(function () {
    'use strict';

    var formulario  = document.getElementById('login-form');
    var txtNick     = document.getElementById('txtNickname');
    var txtPwd      = document.getElementById('txtPassword');
    var rememberUse = document.getElementById('txtRememberUse');
    var chkRemember = document.getElementById('inputCheckbox');
    var hint        = document.getElementById('rememberHint');
    var savedTag    = document.getElementById('savedTag');
    var btnEye      = document.getElementById('btnEye');
    var btnLogin    = document.getElementById('btnLogin');
    var btnPay      = document.getElementById('divPagosMesesBoton');
    var chkPay      = document.getElementById('inputCheckboxPayment');
    var divPay      = document.getElementById('divPagosMeses');
    var selPay      = document.getElementById('txtPagarCantidadDe');
    var payErr      = document.getElementById('payErr');
    var nickInicial = txtNick.value;

    // Limpieza: versiones anteriores guardaban usuario y clave en texto plano en el navegador
    try { localStorage.removeItem('objUserName'); localStorage.removeItem('objUserPassword'); } catch (e) {}

    // ---------- Recordarme ----------
    // Sale del modo "contraseña guardada": el campo queda vacío para escribir una nueva
    function salirModoGuardado(limpiar) {
        if (rememberUse.value !== '1') return;
        rememberUse.value = '0';
        if (limpiar) txtPwd.value = '';
        savedTag.hidden = true;
        btnEye.hidden   = false;
    }
    // Al escribir o borrar en la contraseña guardada, se reemplaza por lo que escriba el usuario
    txtPwd.addEventListener('beforeinput', function () { salirModoGuardado(true); });
    txtPwd.addEventListener('keydown', function (e) {
        if (e.key === 'Backspace' || e.key === 'Delete') salirModoGuardado(true);
    });
    // Si cambia el usuario, la contraseña guardada ya no aplica
    txtNick.addEventListener('input', function () {
        if (rememberUse.value === '1' && txtNick.value !== nickInicial) salirModoGuardado(true);
    });
    chkRemember.addEventListener('change', function () { hint.hidden = !chkRemember.checked; });

    // Mostrar u ocultar contraseña (no disponible con la contraseña guardada)
    btnEye.addEventListener('click', function () {
        var ver = txtPwd.type === 'password';
        txtPwd.type = ver ? 'text' : 'password';
        btnEye.setAttribute('aria-label', ver ? 'Ocultar contraseña' : 'Mostrar contraseña');
    });

    // ---------- Pago de suscripcion ----------
    chkPay.addEventListener('change', function () {
        divPay.hidden = !chkPay.checked;
        // Al apagar el interruptor se limpia el combo para no cobrar por error
        if (!chkPay.checked) { selPay.value = ''; payErr.hidden = true; }
    });
    selPay.addEventListener('change', function () { payErr.hidden = true; });

    // ---------- Envio del formulario ----------
    function ponerCargando(btn, texto) {
        btn.disabled  = true;
        btn.innerHTML = '<span class="spinner"></span> ' + texto;
    }
    function quitarCargando() {
        btnLogin.disabled = false; btnLogin.textContent = 'Ingresar';
        btnPay.disabled   = false; btnPay.textContent   = 'Pagar';
    }
    // Al volver con el boton "atras" del navegador, los botones quedan listos
    window.addEventListener('pageshow', quitarCargando);

    formulario.addEventListener('submit', function (event) {
        var pagando = event.submitter && event.submitter.id === 'divPagosMesesBoton';

        // Validacion: para pagar hay que elegir la cantidad de meses
        if (pagando && selPay.value === '') {
            event.preventDefault();
            payErr.hidden = false;
            selPay.focus();
            return;
        }

        if (btnLogin.disabled) { event.preventDefault(); return; }   // evita doble envio
        ponerCargando(pagando ? btnPay : btnLogin, pagando ? 'Procesando…' : 'Verificando…');

        // Rastreo GPS por empresa (CORE_TRAKING_GPS)
        if (boll_ === 'true') {
            event.preventDefault();
            if ('geolocation' in navigator) {
                navigator.geolocation.getCurrentPosition(
                    function () {
                        var userName     = txtNick.value;
                        // Con la contraseña guardada no se envia el relleno de puntos
                        var userPassword = rememberUse.value === '1' ? '' : txtPwd.value;
                        fnGetUsersCurrentLocation(userName, userPassword, 'https://posme.net/v4posme/posme/public/app_mobile_api/setPositionGps', companyName);
                        formulario.submit();
                    },
                    function (error) {
                        quitarCargando();
                        if (error.code === error.PERMISSION_DENIED) mostrarModal();
                    }
                );
            }
            else {
                console.error('Geolocalización no está disponible en este navegador.');
                quitarCargando();
                mostrarModal();
            }
        }
    });

    // ---------- Modal recuperar contrasena ----------
    var mf     = document.getElementById('modalForgot');
    var step1  = document.getElementById('fgStep1');
    var step2  = document.getElementById('fgStep2');
    var mail   = document.getElementById('txtEmail');
    var fgErr  = document.getElementById('fgErr');
    var fgSend = document.getElementById('fgSend');

    function abrirForgot() {
        step1.hidden = false; step2.hidden = true; fgErr.hidden = true;
        mf.hidden = false;
        setTimeout(function () { mail.focus(); }, 30);
    }
    function cerrarForgot() { mf.hidden = true; }
    function errorForgot(texto) { fgErr.textContent = texto; fgErr.hidden = false; }

    document.getElementById('btnForgot').addEventListener('click', abrirForgot);
    document.getElementById('fgClose').addEventListener('click', cerrarForgot);
    document.getElementById('fgCancel').addEventListener('click', cerrarForgot);
    document.getElementById('fgDone').addEventListener('click', cerrarForgot);
    mf.addEventListener('click', function (e) { if (e.target === mf) cerrarForgot(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { cerrarForgot(); cerrarModal(); } });

    document.getElementById('formForgot').addEventListener('submit', async function (e) {
        e.preventDefault();
        var correo = mail.value.trim();

        // Validacion del formato del correo antes de enviar
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo)) {
            errorForgot('Escribe un correo válido, por ejemplo usuario@empresa.com');
            return;
        }
        fgErr.hidden = true;
        fgSend.disabled  = true;
        fgSend.innerHTML = '<span class="spinner"></span> Enviando…';

        try {
            // Llamada al controlador: responde {error, message}
            var datos = new FormData();
            datos.append('txtEmail', correo);
            var res  = await fetch('<?php echo base_url(); ?>/core_acount/rememberpassword', { method: 'POST', body: datos });
            var json = await res.json();

            if (json.error === true) { errorForgot(json.message); return; }          // error del servidor
            if (!json.message)       { errorForgot('No se recibió respuesta del servidor.'); return; } // sin datos

            // Con datos: correo enviado
            document.getElementById('fgMailOk').textContent = correo;
            step1.hidden = true;
            step2.hidden = false;
        }
        catch (ex) {
            errorForgot('No se pudo conectar con el servidor. Revisa tu conexión e intenta de nuevo.');
        }
        finally {
            fgSend.disabled    = false;
            fgSend.textContent = 'Enviar';
        }
    });
})();
</script>
</body>
</html>
