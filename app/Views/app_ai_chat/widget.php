<?php
/**
 * Widget flotante del asistente IA "Jordania".
 * Lo imprime helper_getHtmlAiChatWidget() solo si el usuario tiene permiso y la empresa lo tiene habilitado.
 * Todo el JS/CSS es local (sin CDN) y se inicializa despues de cargar la pagina.
 */
?>
<link rel="stylesheet" href="<?php echo esc($aiUrlResource, 'attr'); ?>/resource/css/ai_chat/jordania_widget.css?v=20261102c">
<div id="jdn-root" class="jdn-root" data-url-base="<?php echo esc($aiUrlBase, 'attr'); ?>" style="display:none">
    <button type="button" id="jdn-boton" class="jdn-boton" title="Pregúntale a Jordania" aria-label="Pregúntale a Jordania" aria-expanded="false" aria-controls="jdn-panel">
        <img class="jdn-logo-boton" src="<?php echo esc($aiUrlResource, 'attr'); ?>/resource/img/favicon.ico" alt="" aria-hidden="true">
    </button>
    <section id="jdn-panel" class="jdn-panel" role="dialog" aria-label="Jordania · Asistente IA de posMe" hidden>
        <header class="jdn-encabezado">
            <span class="jdn-avatar" aria-hidden="true">J</span>
            <div class="jdn-titulo">
                <strong>Jordania</strong>
                <small id="jdn-subtitulo">Asistente IA de posMe</small>
            </div>
            <button type="button" id="jdn-nueva" class="jdn-icono" title="Nueva conversación" aria-label="Nueva conversación">&#8635;</button>
            <button type="button" id="jdn-cerrar" class="jdn-icono" title="Cerrar" aria-label="Cerrar">&times;</button>
        </header>
        <div id="jdn-mensajes" class="jdn-mensajes" aria-live="polite"></div>
        <div id="jdn-sugerencias" class="jdn-sugerencias"></div>
        <form id="jdn-form" class="jdn-form" autocomplete="off">
            <textarea id="jdn-input" class="jdn-input" rows="3" maxlength="1000" autocomplete="off" placeholder="Escribe tu pregunta para Jordania…" aria-label="Pregunta para Jordania"></textarea>
            <button type="submit" id="jdn-enviar" class="jdn-enviar" title="Enviar" aria-label="Enviar">&#10148;</button>
        </form>
        <div class="jdn-aviso">Las respuestas son generadas por IA; verifica cifras importantes.</div>
    </section>
</div>
<script src="<?php echo esc($aiUrlResource, 'attr'); ?>/resource/js/ai_chat/jordania_widget.js?v=20261102c" defer></script>
