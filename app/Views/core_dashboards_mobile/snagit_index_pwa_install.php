<!-- Modal PWA Install Prompt -->
<div id="pwa-install-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:16px; padding:24px; margin:20px; max-width:320px; width:90%; text-align:center; box-shadow:0 8px 32px rgba(0,0,0,0.2);">
    <div style="margin-bottom:16px;">
      <img src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/img/icons/android/launchericon-192x192.png" alt="posMe Live" style="width:64px; height:64px; border-radius:12px;">
    </div>
    <h5 style="margin:0 0 8px; font-weight:600; color:#333;">Instalar posMe Live</h5>
    <p style="margin:0 0 16px; color:#666; font-size:14px;" id="pwa-install-message">
      Instala esta aplicación en tu dispositivo para un acceso más rápido.
    </p>

    <!-- Instrucciones iOS -->
    <div id="pwa-ios-instructions" style="display:none; text-align:left; background:#f8f9fa; border-radius:8px; padding:12px; margin-bottom:16px; font-size:13px; color:#555;">
      <p style="margin:0 0 8px;"><strong>En Safari:</strong></p>
      <p style="margin:0 0 4px;">1. Toca el ícono <i class="bx bx-upload"></i> (Compartir)</p>
      <p style="margin:0 0 4px;">2. Selecciona <strong>"Agregar a inicio"</strong></p>
      <p style="margin:0;">3. Toca <strong>"Agregar"</strong></p>
    </div>

    <!-- Botones Android/Chrome -->
    <div id="pwa-android-buttons" style="display:none;">
      <button id="pwa-install-btn" style="width:100%; padding:12px; background:#1e88e5; color:#fff; border:none; border-radius:8px; font-size:15px; font-weight:500; cursor:pointer; margin-bottom:8px;">
        <i class="bx bx-download" style="margin-right:4px;"></i> Instalar
      </button>
    </div>

    <button id="pwa-dismiss-btn" style="width:100%; padding:10px; background:transparent; color:#888; border:1px solid #ddd; border-radius:8px; font-size:14px; cursor:pointer;">
      Ahora no
    </button>
  </div>
</div>

<script>
(function() {
    const overlay           = document.getElementById('pwa-install-overlay');
    const iosInstructions   = document.getElementById('pwa-ios-instructions');
    const androidButtons    = document.getElementById('pwa-android-buttons');
    const installBtn        = document.getElementById('pwa-install-btn');
    const dismissBtn        = document.getElementById('pwa-dismiss-btn');
    const message           = document.getElementById('pwa-install-message');

    let deferredPrompt  = null;
    const DISMISS_KEY   = 'pwa_install_dismissed';
    const DISMISS_DAYS  = 7;

    // Verificar si ya fue descartado recientemente
    function isDismissed() {
        const dismissed = localStorage.getItem(DISMISS_KEY);
        if (!dismissed) return false;
        const diff = Date.now() - parseInt(dismissed);
        return diff < (DISMISS_DAYS * 24 * 60 * 60 * 1000);
    }

    // Verificar si ya está instalada (standalone)
    function isStandalone() {
        return window.matchMedia('(display-mode: standalone)').matches
            || window.navigator.standalone === true;
    }

    // Detectar iOS
    function isIOS() {
        return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    }

    // Detectar mobile
    function isMobile() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }

    function showOverlay() {
        overlay.style.display = 'flex';
    }

    function hideOverlay() {
        overlay.style.display = 'none';
        localStorage.setItem(DISMISS_KEY, Date.now().toString());
    }

    // Capturar evento beforeinstallprompt (Android/Chrome)
    window.addEventListener('beforeinstallprompt', function(e) {
        e.preventDefault();
        deferredPrompt = e;

        if (!isStandalone() && !isDismissed() && isMobile()) {
            androidButtons.style.display = 'block';
            iosInstructions.style.display = 'none';
            showOverlay();
        }
    });

    // Botón instalar (Android/Chrome)
    installBtn.addEventListener('click', async function() {
        if (!deferredPrompt) return;
        deferredPrompt.prompt();
        const result = await deferredPrompt.userChoice;
        if (result.outcome === 'accepted') {
            hideOverlay();
        }
        deferredPrompt = null;
    });

    // Botón cerrar
    dismissBtn.addEventListener('click', hideOverlay);

    // Para iOS: mostrar instrucciones manuales
    if (isIOS() && !isStandalone() && !isDismissed() && isMobile()) {
        iosInstructions.style.display   = 'block';
        androidButtons.style.display    = 'none';
        message.textContent             = 'Para instalar esta app en tu iPhone, sigue estos pasos:';
        setTimeout(showOverlay, 1500);
    }
})();
</script>
