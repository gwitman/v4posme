<style>
    /* Pantalla de espera: autorizacion de ingreso por el administrador */
    .pm-auth-box     { display: flex; flex-direction: column; align-items: center; gap: 14px; margin-top: 6px; }
    .pm-auth-msg     { color: #14232c; font-size: 1rem; font-weight: 600; margin: 0; line-height: 1.4; }
    .pm-auth-msg.err { color: #c8372d; }
    .pm-auth-hint    { color: #5d6f7a; font-size: .85rem; margin: 0; min-height: 1.2em; }
    .pm-auth-spin    { width: 38px; height: 38px; border: 4px solid #d9e2e8; border-top-color: #00628e; border-radius: 50%; animation: pmAuthSpin .8s linear infinite; }
    .pm-auth-btns    { display: flex; gap: 10px; width: 100%; flex-wrap: wrap; justify-content: center; }
    .pm-auth-btn     { min-height: 46px; min-width: 180px; padding: 0 20px; border-radius: 10px; font-family: inherit; font-size: 1rem; font-weight: 600; cursor: pointer; border: 1px solid #d9e2e8; background: #fff; color: #14232c; }
    .pm-auth-btn:hover          { border-color: #00628e; color: #00628e; }
    .pm-auth-btn.primary        { background: #00628e; border-color: #00628e; color: #fff; }
    .pm-auth-btn.primary:hover  { filter: brightness(1.08); color: #fff; }
    .pm-auth-btn[disabled]      { opacity: .7; cursor: progress; }
    @keyframes pmAuthSpin { to { transform: rotate(360deg); } }
    @media (max-width: 560px) {
        body .container { margin: 0 14px; padding: 22px 18px; }
        .pm-auth-btn    { width: 100%; min-width: 0; }
    }
    @media (prefers-reduced-motion: reduce) { .pm-auth-spin { animation: none; } }
</style>
<script>
(function () {
    'use strict';

    // Marcadores reemplazados por core_acount::login con str_replace
    var URL_STATUS   = '[[URL_AUTORIZATION_STATUS]]';
    var URL_CANCEL   = '[[URL_AUTORIZATION_CANCEL]]';
    var URL_LOGIN    = '[[URL_LOGIN]]';
    var INTERVALO_MS = 5000;   // sondeo cada 5 segundos

    var temporizador = null;
    var detenido     = false;
    var cancelando   = false;
    var fallos       = 0;

    // ---------- Construccion de la interfaz dentro de la tarjeta de helper_notificationPage ----------
    var contenedor = document.querySelector('.container') || document.body;
    var titulo     = contenedor.querySelector('h1');
    var resumen    = contenedor.querySelector('p');

    var caja = document.createElement('div');
    caja.className = 'pm-auth-box';
    caja.innerHTML =
        '<div class="pm-auth-spin" id="pmAuthSpin" aria-hidden="true"></div>' +
        '<p class="pm-auth-msg" id="pmAuthMsg" role="status" aria-live="polite">Esperando autorización del administrador…</p>' +
        '<p class="pm-auth-hint" id="pmAuthHint"></p>' +
        '<div class="pm-auth-btns">' +
            '<button type="button" class="pm-auth-btn" id="pmAuthCancel">Cancelar</button>' +
            '<button type="button" class="pm-auth-btn primary" id="pmAuthLogin" hidden>Volver al inicio de sesión</button>' +
        '</div>';
    contenedor.appendChild(caja);

    var spin      = document.getElementById('pmAuthSpin');
    var msg       = document.getElementById('pmAuthMsg');
    var hint      = document.getElementById('pmAuthHint');
    var btnCancel = document.getElementById('pmAuthCancel');
    var btnLogin  = document.getElementById('pmAuthLogin');

    // Estado final (rechazado, vencido o sin solicitud): se detiene el sondeo y se ofrece volver al login
    function mostrarFinal(texto, tituloFinal) {
        detenido = true;
        if (temporizador) { clearTimeout(temporizador); temporizador = null; }
        spin.hidden      = true;
        msg.textContent  = texto;
        msg.className    = 'pm-auth-msg err';
        hint.textContent = '';
        btnCancel.hidden = true;
        btnLogin.hidden  = false;
        if (titulo && tituloFinal) { titulo.textContent = tituloFinal; titulo.style.color = '#c8372d'; }
        if (resumen) { resumen.hidden = true; }
        btnLogin.focus();
    }

    function programar() {
        if (detenido) return;
        temporizador = setTimeout(consultarEstado, INTERVALO_MS);
    }

    // ---------- Sondeo del estado de la solicitud ----------
    async function consultarEstado() {
        if (detenido || cancelando) return;
        try {
            var res  = await fetch(URL_STATUS, { method: 'GET', credentials: 'same-origin', cache: 'no-store', headers: { 'Accept': 'application/json' } });
            var json = await res.json();

            // Sin datos: respuesta vacia o sin estado, se reintenta
            if (!json || !json.status) {
                hint.textContent = 'No se recibió respuesta del servidor, reintentando…';
                programar();
                return;
            }

            fallos           = 0;
            hint.textContent = '';

            // Con datos: se actua segun el estado de la solicitud
            switch (json.status) {
                case 'pending':
                    msg.textContent = 'Esperando autorización del administrador…';
                    programar();
                    break;
                case 'authorized':
                    detenido        = true;
                    msg.className   = 'pm-auth-msg';
                    msg.textContent = 'Ingreso autorizado, entrando…';
                    window.location = json.redirect;
                    break;
                case 'rejected':
                    mostrarFinal(json.message || 'El administrador rechazó el ingreso.', 'Ingreso rechazado');
                    break;
                case 'expired':
                    mostrarFinal(json.message || 'La solicitud de autorización venció.', 'Solicitud vencida');
                    break;
                case 'none':
                    mostrarFinal(json.message || 'No hay una solicitud de autorización pendiente.', 'Sin solicitud');
                    break;
                default:
                    hint.textContent = 'Estado desconocido, reintentando…';
                    programar();
            }
        }
        catch (ex) {
            // Error de red o respuesta no JSON: se sigue intentando sin detener la espera
            fallos++;
            hint.textContent = 'Sin conexión con el servidor, reintentando' + (fallos > 1 ? ' (' + fallos + ')' : '') + '…';
            console.error('Error al consultar la autorización', ex);
            programar();
        }
    }

    // ---------- Cancelar la solicitud ----------
    btnCancel.addEventListener('click', async function () {
        if (cancelando) return;   // evita doble envio
        cancelando = true;
        if (temporizador) { clearTimeout(temporizador); temporizador = null; }
        btnCancel.disabled    = true;
        btnCancel.textContent = 'Cancelando…';
        try {
            var res  = await fetch(URL_CANCEL, { method: 'POST', credentials: 'same-origin', headers: { 'Accept': 'application/json' } });
            var json = await res.json();

            // Con datos: solicitud cancelada, se redirige
            if (json && json.status === 'cancelled') {
                detenido = true;
                window.location = json.redirect || URL_LOGIN;
                return;
            }
            // Error o sin datos: se informa y se reanuda la espera
            hint.textContent = (json && json.message) ? json.message : 'No se pudo cancelar la solicitud.';
        }
        catch (ex) {
            hint.textContent = 'No se pudo conectar con el servidor para cancelar.';
            console.error('Error al cancelar la autorización', ex);
        }
        cancelando            = false;
        btnCancel.disabled    = false;
        btnCancel.textContent = 'Cancelar';
        programar();
    });

    // ---------- Volver al login ----------
    btnLogin.addEventListener('click', function () { window.location = URL_LOGIN; });

    // Primera consulta inmediata; luego cada 5 segundos
    consultarEstado();
})();
</script>
