---
title: Estándares de Programación del Proyecto
inclusion: auto
---

# Estándares de Programación - POSME

## Archivos de Referencia

Cuando trabajes en este proyecto, SIEMPRE consulta estos archivos:

1. **Guía de Sintaxis:** `guia-sintaxis-estandares.md`
   - Convenciones de nombres
   - Estructura de código
   - Patrones de programación

2. **Helpers:** `documentacion-web-tools-helper.md`
   - Funciones auxiliares disponibles
   - Evita duplicar código que ya existe

3. **Librerías Core:** `documentacion-librerias-core.md`
   - Librerías del sistema
   - Métodos disponibles
   - Patrones de uso

## Reglas Obligatorias

### Al Crear Controladores
1. SIEMPRE usar el patrón de `guia-sintaxis-estandares.md`
2. SIEMPRE validar autenticación con `core_web_authentication`
3. SIEMPRE validar permisos con `core_web_permission`
4. SIEMPRE usar try-catch
5. SIEMPRE retornar JSON con estructura estándar

### Al Crear Modelos
1. Sufijo `_Model` obligatorio
2. Métodos: `get_rowByPK`, `insert_app_posme`, `update_app_posme`, `delete_app_posme`
3. Usar `db_connect()` para conexiones
4. Soft delete con `isActive = 0`

### Al Usar Helpers
1. ANTES de crear una función, verificar si existe en `documentacion-web-tools-helper.md`
2. Usar `helper_getDateTime()` en lugar de `date()`
3. Usar `helper_SegmentsValue()` para parámetros URI
4. Usar `helper_RequestGetValue()` para valores POST/GET

### Al Usar Librerías
1. ANTES de escribir lógica, verificar si existe en `documentacion-librerias-core.md`
2. Usar `core_web_parameter` para configuraciones
3. Usar `core_web_workflow` para estados
4. Usar `core_web_transaction` para transacciones

## Convenciones de Nombres

### Archivos
- Controladores: `app_[modulo]_[funcionalidad].php`
- Modelos: `[Nombre]_Model.php`
- Vistas: `[controlador]/[accion]_[tipo].php`

### Variables
- camelCase: `$companyID`, `$dataSession`
- snake_case en BD: `company_id`, `is_active`

### Constantes
- UPPER_SNAKE_CASE: `USER_NOT_AUTENTICATED`, `PERMISSION_NONE`

## Estructura de Base de Datos

### Claves Compuestas
Siempre usar: `companyID` + ID específico

### Campos Comunes
- `companyID` - Multi-tenant
- `branchID` - Sucursal
- `isActive` - Soft delete (0 o 1)
- `createdBy`, `createdOn`, `createdIn`, `createdAt` - Auditoría

### Tablas
- Prefijo: `tb_`
- snake_case: `tb_account`, `tb_customer_credit`

## Respuestas JSON Estándar

```php
// Éxito
return $this->response->setJSON(array(
    'error' => false,
    'message' => SUCCESS
));

// Error
return $this->response->setJSON(array(
    'error' => true,
    'message' => $ex->getLine()." ".$ex->getMessage()
));
```

## IMPORTANTE

Cuando te pida crear código:
1. Primero revisa los archivos de documentación
2. Usa las funciones/librerías existentes
3. Sigue los patrones establecidos
4. No inventes nuevas convenciones
