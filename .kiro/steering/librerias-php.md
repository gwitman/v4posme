---
title: Guía para Librerías PHP
inclusion: fileMatch
fileMatchPattern: "app/Libraries/*.php"
---

# Instrucciones para Librerías

Cuando trabajes en archivos de `app/Libraries/`:

## Estructura Obligatoria

```php
<?php
//posme:2023-02-27
namespace App\Libraries;

use App\Models\Core\Company_Model;
// ... otros imports

class core_web_nombre {
    
    private $CI;
    
    public function __construct() {
        // Inicialización
    }
    
    function metodoPublico($param1, $param2) {
        // Lógica
        return $resultado;
    }
}
```

## Reglas

1. Prefijo `core_web_` para librerías del sistema
2. Namespace `App\Libraries`
3. Importar modelos necesarios
4. Métodos públicos sin modificador (function, no public function)
5. Documentar parámetros y retornos
6. Lanzar excepciones con mensajes claros

## Antes de Crear

SIEMPRE revisar `documentacion-librerias-core.md` para verificar que la funcionalidad no existe ya.

## Librerías Existentes

- `core_web_authentication` - Autenticación
- `core_web_permission` - Permisos
- `core_web_transaction` - Transacciones
- `core_web_workflow` - Flujos de trabajo
- `core_web_parameter` - Parámetros
- `core_web_catalog` - Catálogos
- Y más en `documentacion-librerias-core.md`
