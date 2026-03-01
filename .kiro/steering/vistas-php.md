---
title: Guía para Vistas PHP
inclusion: fileMatch
fileMatchPattern: "app/Views/**/*.php"
---

# Instrucciones para Vistas

Cuando trabajes en archivos de `app/Views/`:

## Estructura de Vistas

Las vistas se dividen en:
- `*_body.php` - HTML principal
- `*_script.php` - JavaScript/Vue.js
- `*_style.php` - CSS (opcional)

## Patrones Vue.js

```javascript
const { createApp } = Vue;

createApp({
    data() {
        return {
            mensaje: '',
            mostrarAlerta: true,
            objListData: []
        }
    },
    methods: {
        async cargarListado() {
            try {
                const res = await fetch('<?php echo base_url(); ?>/api/endpoint', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({param: this.value})
                });
                
                const json = await res.json();
                
                if (json.success === false) {
                    this.objListData = [];
                    this.mensaje = 'Error al cargar';
                    return;
                }
                
                if (json.success === true && (!json.data || json.data.length === 0)) {
                    this.objListData = [];
                    this.mensaje = 'No hay datos';
                    this.mostrarAlerta = false;
                    return;
                }
                
                this.mostrarAlerta = false;
                this.mensaje = '';
                this.objListData = json.data;
                
            } catch (error) {
                console.error(error);
                this.mensaje = 'Error de conexión';
                this.objListData = [];
            }
        }
    },
    mounted() {
        this.cargarListado();
        document.getElementById('app').style.visibility = 'visible';
    }
}).mount('#app');
```

## Reglas

1. Usar Vue.js 3 para interactividad
2. Ocultar app hasta mounted: `visibility: hidden`
3. Manejo de 3 casos en fetch: error, sin datos, con datos
4. Usar `<?php echo base_url(); ?>` para URLs
5. Usar Bootstrap 5 para estilos
6. Usar tema Sneat para componentes

## Helpers en Vistas

```php
<?php echo helper_getDateTime(); ?>
<?php echo helper_GetLetras($monto, "CORDOBAS", "CENTAVOS"); ?>
<?php echo replaceSimbol($texto); ?>
```
