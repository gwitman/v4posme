---
title: Guía para Controladores PHP
inclusion: fileMatch
fileMatchPattern: "app/Controllers/*.php"
---

# Instrucciones para Controladores

Cuando trabajes en archivos de `app/Controllers/`:

## Estructura Obligatoria

```php
<?php
//posme:2023-02-27
namespace App\Controllers;

class app_modulo_funcionalidad extends _BaseController {
    
    function metodo() {
        try {
            // 1. AUTENTICACIÓN
            if(!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();
            
            // 2. PERMISOS
            if(APP_NEED_AUTHENTICATION == true) {
                $permited = $this->core_web_permission->urlPermited(...);
                if(!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);
            }
            
            // 3. PARÁMETROS
            $companyID = helper_SegmentsValue($this->uri->getSegments(), "companyID");
            
            // 4. LÓGICA
            
            // 5. RESPUESTA
            return $this->response->setJSON(array(
                'error' => false,
                'message' => SUCCESS
            ));
            
        } catch(\Exception $ex) {
            return $this->response->setJSON(array(
                'error' => true,
                'message' => $ex->getLine()." ".$ex->getMessage()
            ));
        }
    }
}
```

## Consulta Siempre

- `guia-sintaxis-estandares.md` - Patrones
- `documentacion-librerias-core.md` - Librerías disponibles
- `documentacion-web-tools-helper.md` - Helpers disponibles
