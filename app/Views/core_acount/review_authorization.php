<?php
//posme:2026-11-20
// Revision de ingreso: el ADMINISTRADOR abre esta pagina desde el enlace de su correo (sin sesion iniciada)
// y autoriza o rechaza el intento de ingreso de un usuario (CORE_AUTHENTICATE_BY_EMAIL = true).
// Variables: $valid, $resolved, $message, $token, $nickname, $companyName, $ip, $userAgent, $createdOn, $expiresOn, $urlResolve
$valid       = isset($valid) ? (bool) $valid : false;
$resolved    = isset($resolved) ? (bool) $resolved : false;
$message     = isset($message) ? $message : "";
$token       = isset($token) ? $token : "";
$nickname    = isset($nickname) ? $nickname : "";
$companyName = isset($companyName) ? $companyName : "";
$ip          = isset($ip) ? $ip : "";
$userAgent   = isset($userAgent) ? $userAgent : "";
$createdOn   = isset($createdOn) ? $createdOn : "";
$expiresOn   = isset($expiresOn) ? $expiresOn : "";
$urlResolve  = isset($urlResolve) ? $urlResolve : "";
$showForm    = $valid && !$resolved;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="robots" content="noindex, nofollow">
    <meta name="referrer" content="no-referrer">
    <meta name="theme-color" content="#00628e">
    <title>::posMe:: Autorizar ingreso</title>

    <link rel="shortcut icon" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/assets/images/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap">

    <style>
        /* Misma paleta y componentes que la pantalla de login (core_acount/login.php) */
        :root {
            --brand:        #00628e;
            --brand-deep:   #003a55;
            --accent:       #3fae55;
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
        body { background: var(--bg); color: var(--fg); font-family: var(--font-body); font-size: 15px; line-height: 1.5; min-height: 100vh; }

        .top {
            color: #fff; background: radial-gradient(120% 90% at 0% 0%, var(--brand) 0%, var(--brand-deep) 70%);
            padding: 22px max(16px, env(safe-area-inset-right, 0px)) 52px max(16px, env(safe-area-inset-left, 0px));
            text-align: center;
        }
        .wordmark { font-family: var(--font-display); font-weight: 800; font-size: 24px; letter-spacing: -.02em; }
        .wordmark span { color: #7fdc8e; }
        .top p { margin: 4px 0 0; color: rgba(255,255,255,.8); font-size: 14px; }

        main {
            display: flex; flex-direction: column; align-items: center; gap: 20px;
            padding: 0 max(16px, env(safe-area-inset-right, 0px)) 32px max(16px, env(safe-area-inset-left, 0px));
        }
        .card {
            width: 100%; max-width: 520px; margin-top: -32px; background: var(--surface); border: 1px solid var(--line); border-radius: 18px; padding: 28px 26px;
            box-shadow: 0 1px 2px rgba(10,30,45,.04), 0 12px 32px rgba(10,30,45,.06);
        }
        .ico { width: 52px; height: 52px; border-radius: 50%; display: grid; place-items: center; margin: 0 auto 12px; }
        .ico.info { background: rgba(0,98,142,.12); color: var(--brand); }
        .ico.ok   { background: var(--ok-soft); color: var(--accent); }
        .card h1 { font-family: var(--font-display); font-size: 21px; margin: 0 0 4px; letter-spacing: -.01em; text-align: center; }
        .card .sub { color: var(--muted); margin: 0 0 20px; font-size: 14px; text-align: center; }
        .card .only-msg { text-align: center; font-size: 16px; margin: 0; overflow-wrap: anywhere; }

        /* Datos del intento de ingreso */
        .data { margin: 0 0 22px; padding: 0; border: 1px solid var(--line); border-radius: var(--radius); overflow: hidden; }
        .data div { display: grid; grid-template-columns: 120px 1fr; gap: 12px; padding: 11px 14px; border-top: 1px solid var(--line); background: var(--field); }
        .data div:first-child { border-top: 0; }
        .data dt { color: var(--muted); font-size: 13px; font-weight: 600; }
        .data dd { margin: 0; font-size: 14px; overflow-wrap: anywhere; word-break: break-word; }
        .data dd.agent { font-size: 12.5px; color: var(--muted); }
        .data dd.exp { color: var(--danger); font-weight: 600; }

        form { margin: 0; }
        .btn-row { display: flex; gap: 10px; }
        .btn {
            flex: 1; height: 50px; border: 0; border-radius: var(--radius); font: 600 16px var(--font-body); cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px; transition: filter .15s, transform .05s; color: #fff;
        }
        .btn:active { transform: translateY(1px); }
        .btn:hover { filter: brightness(1.07); }
        .btn:focus-visible { outline: 2px solid var(--brand); outline-offset: 2px; }
        .btn[disabled] { opacity: .75; cursor: progress; }
        .btn-approve { background: var(--accent); color: #06240d; }
        .btn-reject  { background: var(--danger); }
        .spinner { width: 18px; height: 18px; border: 2px solid rgba(255,255,255,.45); border-top-color: #fff; border-radius: 50%; animation: spin .7s linear infinite; }
        .btn-approve .spinner { border-color: rgba(6,36,13,.3); border-top-color: #06240d; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .note { color: var(--muted); font-size: 12.5px; text-align: center; margin: 14px 0 0; }

        .foot { text-align: center; color: var(--muted); font-size: 12.5px; margin: 0; }

        @media (max-width: 480px) {
            .card { padding: 22px 18px; border-radius: 16px; }
            .data div { grid-template-columns: 1fr; gap: 2px; }
            .btn-row { flex-direction: column-reverse; }
        }
        @media (prefers-reduced-motion: reduce) { * { transition: none !important; animation: none !important; } }
    </style>
</head>

<body>
<header class="top">
    <div class="wordmark">pos<span>Me</span></div>
    <p>Autorización de ingreso</p>
</header>

<main>
    <div class="card">
    <?php if(!$showForm): ?>
        <!-- Enlace vencido, ya utilizado o solicitud ya resuelta: solo se muestra el mensaje -->
        <div class="ico <?php echo $resolved ? "ok" : "info"; ?>">
            <?php if($resolved): ?>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M5 12.5 10 17l9-10"/></svg>
            <?php else: ?>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg>
            <?php endif; ?>
        </div>
        <p class="only-msg"><?php echo esc($message); ?></p>
    <?php else: ?>
        <div class="ico info">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
        </div>
        <h1>Solicitud de ingreso</h1>
        <p class="sub">Un usuario intenta iniciar sesión y requiere tu autorización.</p>
        <?php if(!empty($message)): ?>
        <p class="note"><?php echo esc($message); ?></p>
        <?php endif; ?>

        <dl class="data">
            <div><dt>Usuario</dt><dd><?php echo esc($nickname); ?></dd></div>
            <div><dt>Compañía</dt><dd><?php echo esc($companyName); ?></dd></div>
            <div><dt>IP</dt><dd><?php echo esc($ip); ?></dd></div>
            <div><dt>Equipo</dt><dd class="agent"><?php echo esc($userAgent); ?></dd></div>
            <div><dt>Fecha</dt><dd><?php echo esc($createdOn); ?></dd></div>
            <div><dt>Vence</dt><dd class="exp"><?php echo esc($expiresOn); ?></dd></div>
        </dl>

        <!-- La decision solo se envia por POST; nunca se autoriza con GET -->
        <form method="POST" action="<?php echo esc($urlResolve, 'attr'); ?>" id="formResolve" autocomplete="off">
            <input type="hidden" name="token" value="<?php echo esc($token, 'attr'); ?>">
            <div class="btn-row">
                <button type="submit" class="btn btn-reject" name="action" value="reject" id="btnReject">Rechazar</button>
                <button type="submit" class="btn btn-approve" name="action" value="approve" id="btnApprove">Autorizar</button>
            </div>
        </form>
        <p class="note">Si no reconoces este intento, recházalo y cambia la contraseña del usuario.</p>
    <?php endif; ?>
    </div>

    <p class="foot">posMe · © <?php echo date("Y"); ?>. Todos los derechos reservados</p>
</main>

<?php if($showForm): ?>
<script>
(function () {
    'use strict';

    var formulario = document.getElementById('formResolve');
    var btnApprove = document.getElementById('btnApprove');
    var btnReject  = document.getElementById('btnReject');
    var enviado    = false;

    // Al volver con "atras" del navegador los botones quedan listos de nuevo
    window.addEventListener('pageshow', function () {
        enviado = false;
        btnApprove.disabled = false; btnApprove.textContent = 'Autorizar';
        btnReject.disabled  = false; btnReject.textContent  = 'Rechazar';
        var oculto = document.getElementById('hdnAction');
        if (oculto) oculto.parentNode.removeChild(oculto);
    });

    formulario.addEventListener('submit', function (event) {
        // Evita doble envio
        if (enviado) { event.preventDefault(); return; }
        enviado = true;

        // Los botones deshabilitados no se envian: se copia la accion elegida a un campo oculto
        // Sin submitter (navegador antiguo) no se asume ninguna accion: se envia tal cual
        var boton  = event.submitter;
        if (!boton) return;
        var oculto = document.createElement('input');
        oculto.type  = 'hidden';
        oculto.name  = 'action';
        oculto.id    = 'hdnAction';
        oculto.value = boton.value;
        formulario.appendChild(oculto);

        boton.innerHTML = '<span class="spinner"></span> ' + (boton.value === 'approve' ? 'Autorizando…' : 'Rechazando…');
        // Se deshabilitan despues de construir el envio para no perder el valor
        setTimeout(function () { btnApprove.disabled = true; btnReject.disabled = true; }, 0);
    });
})();
</script>
<?php endif; ?>
</body>
</html>
