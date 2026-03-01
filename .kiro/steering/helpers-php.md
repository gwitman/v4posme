---
title: Guía para Helpers PHP
inclusion: fileMatch
fileMatchPattern: "app/Helpers/*.php"
---

# Instrucciones para Helpers

Cuando trabajes en archivos de `app/Helpers/`:

## Estructura

```php
<?php
//posme:2023-02-27

function helper_nombreFuncion($param1, $param2) {
    // Lógica
    return $resultado;
}
```

## Reglas

1. Prefijo `helper_` para funciones del sistema
2. Sin namespace (funciones globales)
3. Nombres descriptivos en camelCase
4. Documentar con comentarios
5. Funciones puras cuando sea posible

## Antes de Crear

SIEMPRE revisar `documentacion-web-tools-helper.md` para verificar que la función no existe.

## Helpers Existentes

### Fechas
- `helper_getDate()` - Fecha actual
- `helper_getDateTime()` - Fecha y hora actual
- `helper_PrimerDiaDelMes()` - Primer día del mes
- `helper_UltimoDiaDelMes()` - Último día del mes

### URI
- `helper_SegmentsValue()` - Obtener valor de segmento
- `helper_SegmentsByIndex()` - Obtener por índice

### Request
- `helper_RequestGetValue()` - Valor con default
- `helper_RequestGetValueObjet()` - Campo de objeto con default

### Texto
- `helper_getStringClear()` - Limpiar caracteres
- `helper_QuitarAcentos()` - Quitar acentos
- `replaceSimbol()` - Convertir códigos a emojis

### Números
- `helper_GetLetras()` - Número a letras
- `helper_StringToNumber()` - String a número

Ver lista completa en `documentacion-web-tools-helper.md`
