/*
 * posme-menu-left.js
 * Complementos del menú lateral (core_masterpage/default_masterpage.php, #sidebar del tema Genyx):
 *  - Buscador que filtra las opciones del menú (incluye submenús).
 *  - Fondo oscuro (#pm-sidebar-backdrop) cuando el menú está abierto en tablet/teléfono.
 * No reemplaza la lógica de jquery.genyxAdmin.js (expandir, colapsar, página actual).
 */
(function () {
    'use strict';

    var ANCHO_MOVIL = 1024;

    /**
     * Normaliza un texto: minúsculas, sin acentos y sin espacios extremos.
     * @param {string} t Texto a normalizar
     * @return {string} Texto normalizado
     */
    function normalizar(t) {
        return (t || '').toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '').trim();
    }

    /**
     * Hijos directos de un elemento que cumplen un selector.
     * @param {HTMLElement} el Elemento padre
     * @param {string} sel Selector CSS
     * @return {HTMLElement[]} Hijos que coinciden
     */
    function hijos(el, sel) {
        return Array.prototype.filter.call(el.children, function (c) { return c.matches(sel); });
    }

    /**
     * Filtra recursivamente un <li> del menú.
     * Si el propio ítem coincide, se muestra con todos sus hijos; si no,
     * se muestra solo cuando algún hijo coincide (y su submenú se abre).
     * @param {HTMLElement} li Ítem del menú
     * @param {string} texto Texto normalizado a buscar
     * @return {boolean} true si el ítem queda visible
     */
    function filtrarItem(li, texto) {
        var enlace  = hijos(li, 'a')[0];
        var txt     = enlace ? enlace.querySelector('.txt') : null;
        var propio  = normalizar(txt ? txt.textContent : (enlace ? enlace.textContent : ''));
        var sub     = hijos(li, 'ul.sub')[0];

        if (propio.indexOf(texto) !== -1) {
            // Coincide el ítem: se muestra completo, sin forzar sus submenús
            li.style.display = '';
            if (sub) {
                limpiar(sub);
            }
            return true;
        }

        var alguno = false;
        if (sub) {
            hijos(sub, 'li').forEach(function (h) {
                if (filtrarItem(h, texto)) {
                    alguno = true;
                }
            });
            sub.style.display = alguno ? 'block' : '';
        }

        li.style.display = alguno ? '' : 'none';
        return alguno;
    }

    /**
     * Quita los estilos puestos por el filtro (los submenús vuelven al estado de Genyx).
     * @param {HTMLElement} cont Contenedor a limpiar
     */
    function limpiar(cont) {
        cont.querySelectorAll('li').forEach(function (li) { li.style.display = ''; });
        cont.querySelectorAll('ul.sub').forEach(function (ul) { ul.style.display = ''; });
    }

    /**
     * Inserta el buscador encima del menú y conecta sus eventos.
     * @param {HTMLElement} sidebar Elemento #sidebar
     */
    function crearBuscador(sidebar) {
        var wrapper = sidebar.querySelector('.sidebar-wrapper');
        var lista   = sidebar.querySelector('#mainnav > ul.nav-list');
        if (!wrapper || !lista) {
            return;
        }

        var cont       = document.createElement('div');
        cont.className = 'pm-menu-search';
        cont.innerHTML = '<div class="pm-menu-search-box">' +
                         '<i class="icon16 i-search-2"></i>' +
                         '<input type="search" autocomplete="off" placeholder="Buscar en el menú..." aria-label="Buscar en el menú" />' +
                         '</div>';

        var vacio         = document.createElement('div');
        vacio.className   = 'pm-menu-empty';
        vacio.textContent = 'Sin resultados';

        wrapper.insertBefore(cont, wrapper.firstChild);
        wrapper.appendChild(vacio);

        var input = cont.querySelector('input');

        // Filtra en cada pulsación
        input.addEventListener('input', function () {
            var texto = normalizar(input.value);

            if (texto === '') {
                limpiar(lista);
                vacio.style.display = 'none';
                return;
            }

            var visibles = 0;
            hijos(lista, 'li').forEach(function (li) {
                if (filtrarItem(li, texto)) {
                    visibles++;
                }
            });
            vacio.style.display = visibles === 0 ? 'block' : 'none';
        });

        // Enter abre la primera opción visible con dirección real; Esc limpia
        input.addEventListener('keydown', function (ev) {
            if (ev.key === 'Escape') {
                input.value = '';
                input.dispatchEvent(new Event('input'));
                return;
            }
            if (ev.key !== 'Enter') {
                return;
            }
            ev.preventDefault();
            var enlaces = lista.querySelectorAll('a[href]');
            for (var i = 0; i < enlaces.length; i++) {
                var href = enlaces[i].getAttribute('href') || '';
                if (href.indexOf('javascript') === 0 || href === '#') {
                    continue;
                }
                if (enlaces[i].offsetParent !== null) {
                    window.location.href = enlaces[i].href;
                    return;
                }
            }
        });
    }

    /**
     * Crea el fondo oscuro del menú deslizable y lo sincroniza con la clase
     * "hided" que maneja Genyx. Al tocar el fondo se cierra el menú usando el
     * mismo botón #resBtn para no desordenar el estado del tema.
     * @param {HTMLElement} sidebar Elemento #sidebar
     */
    function crearFondoMovil(sidebar) {
        var fondo = document.createElement('div');
        fondo.id  = 'pm-sidebar-backdrop';
        document.body.appendChild(fondo);

        function sincronizar() {
            var abierto = window.innerWidth <= ANCHO_MOVIL && !sidebar.classList.contains('hided');
            fondo.classList.toggle('pm-show', abierto);
        }

        // Cerrar el menú al tocar el fondo
        fondo.addEventListener('click', function () {
            var btn = document.getElementById('resBtn');
            if (btn) {
                btn.click();
            } else {
                sidebar.classList.add('hided');
            }
        });

        new MutationObserver(sincronizar).observe(sidebar, { attributes: true, attributeFilter: ['class'] });
        window.addEventListener('resize', sincronizar);
        sincronizar();
    }

    /**
     * Devuelve el primer segmento de la ruta que empieza con "app_" o "core_"
     * (nombre del controlador), para comparar enlaces aunque cambie la acción.
     * @param {string} path Ruta (pathname)
     * @return {string} Controlador o cadena vacía
     */
    function obtenerControlador(path) {
        var partes = path.toLowerCase().split('/');
        for (var i = 0; i < partes.length; i++) {
            if (partes[i].indexOf('app_') === 0 || partes[i].indexOf('core_') === 0) {
                return partes[i];
            }
        }
        return '';
    }

    /**
     * Marca la opción de la pantalla actual (Genyx no lo logra con enlaces absolutos).
     * Primero busca la ruta exacta; si no, el mismo controlador. Abre los submenús padres.
     * @param {HTMLElement} sidebar Elemento #sidebar
     */
    function marcarActual(sidebar) {
        var actual     = window.location.pathname.replace(/\/+$/, '').toLowerCase();
        var ctrlActual = obtenerControlador(actual);
        var enlaces    = sidebar.querySelectorAll('#mainnav a[href]');
        var elegido    = null;
        var porCtrl    = null;

        for (var i = 0; i < enlaces.length; i++) {
            var href = enlaces[i].getAttribute('href') || '';
            if (href.indexOf('javascript') === 0 || href === '#') {
                continue;
            }
            var ruta = enlaces[i].pathname.replace(/\/+$/, '').toLowerCase();
            if (ruta === actual) {
                elegido = enlaces[i];
                break;
            }
            if (!porCtrl && ctrlActual !== '' && obtenerControlador(ruta) === ctrlActual) {
                porCtrl = enlaces[i];
            }
        }

        elegido = elegido || porCtrl;
        if (!elegido) {
            return;
        }

        // Marca el ítem y sus padres; abre los submenús que lo contienen
        var li = elegido.closest('li');
        li.classList.add('current');
        var ul = li.parentElement;
        while (ul && ul.classList.contains('sub')) {
            ul.classList.add('show');
            var padre = ul.parentElement;
            var a = hijos(padre, 'a')[0];
            if (a && a.classList.contains('notExpand')) {
                a.classList.remove('notExpand');
                a.classList.add('expand');
            }
            ul = padre.parentElement;
        }

        try {
            elegido.scrollIntoView({ block: 'nearest' });
        } catch (e) { /* navegadores antiguos */ }
    }

    document.addEventListener('DOMContentLoaded', function () {
        var sidebar = document.getElementById('sidebar');
        if (!sidebar) {
            return;
        }
        // Se espera a que genyxAdmin agregue sus clases (hasSub, notExpand)
        setTimeout(function () { marcarActual(sidebar); }, 0);
        crearBuscador(sidebar);
        crearFondoMovil(sidebar);
    });
})();
