<?php
// Sesion unica (CORE_SESSION_UNIQUE): cada CORE_SESSION_UNIQUE_INTERVAL segundos la pantalla pregunta
// si la sesion sigue vigente; si el usuario inicio sesion en otro equipo, se envia al login.
// Se incluye en las masterpages con: echo view("core_masterpage/session_unique_script");
$sessionUniqueUser     = \Config\Services::session()->get("user");
$sessionUniqueInterval = 0;
if ($sessionUniqueUser) {
    $sessionUniqueAuth     = new \App\Libraries\core_web_authentication();
    $sessionUniqueInterval = $sessionUniqueAuth->getSessionUniqueInterval($sessionUniqueUser->companyID);
}
if ($sessionUniqueInterval > 0):
?>
<script>
(function () {
    // Dentro de un iframe no se consulta: la ventana principal ya lo hace
    if (window.self !== window.top) { return; }

    var urlCheck   = "<?php echo base_url(); ?>/core_acount/checkSession";
    var urlLogin   = "<?php echo base_url(); ?>/core_acount/index";
    var intervalo  = <?php echo (int)$sessionUniqueInterval; ?> * 1000;
    var consultando = false;

    function posmeCheckSession() {
        if (consultando) { return; }
        consultando = true;
        fetch(urlCheck, { method: "GET", credentials: "same-origin", cache: "no-store", headers: { "X-Requested-With": "XMLHttpRequest" } })
            .then(function (r) { return r.json(); })
            .then(function (json) {
                consultando = false;
                // Solo se saca al usuario cuando el servidor confirma que la sesion ya no esta vigente
                if (json && json.error === false && json.activa === false) {
                    clearInterval(timer);
                    window.location.href = json.urlLogin ? json.urlLogin : urlLogin;
                }
            })
            .catch(function () { consultando = false; }); // sin red o error del servidor: se reintenta en el siguiente ciclo
    }

    var timer = setInterval(posmeCheckSession, intervalo);

    // Al volver a la pestana se consulta de inmediato
    document.addEventListener("visibilitychange", function () {
        if (document.visibilityState === "visible") { posmeCheckSession(); }
    });
})();
</script>
<?php endif; ?>
