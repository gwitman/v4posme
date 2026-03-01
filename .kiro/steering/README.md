---
title: Configuración de Steering Files
inclusion: manual
---

# Sistema de Steering Files - POSME

Este directorio contiene las instrucciones automáticas que Kiro usa según el contexto.

## Archivos Configurados

### 1. Siempre Incluidos (Auto)

#### `estandares-proyecto.md`
- **Inclusión:** Automática en todas las conversaciones
- **Propósito:** Estándares generales del proyecto
- **Contenido:** Referencias a documentación, reglas obligatorias, convenciones

### 2. Por Tipo de Archivo (FileMatch)

#### `controladores-php.md`
- **Patrón:** `app/Controllers/*.php`
- **Se activa:** Al trabajar con controladores
- **Contenido:** Estructura de controladores, autenticación, permisos

#### `modelos-php.md`
- **Patrón:** `app/Models/*.php`
- **Se activa:** Al trabajar con modelos
- **Contenido:** Estructura de modelos, CRUD, soft delete

#### `helpers-php.md`
- **Patrón:** `app/Helpers/*.php`
- **Se activa:** Al trabajar con helpers
- **Contenido:** Convenciones de helpers, lista de helpers existentes

#### `librerias-php.md`
- **Patrón:** `app/Libraries/*.php`
- **Se activa:** Al trabajar con librerías
- **Contenido:** Estructura de librerías, lista de librerías existentes

#### `vistas-php.md`
- **Patrón:** `app/Views/**/*.php`
- **Se activa:** Al trabajar con vistas
- **Contenido:** Patrones Vue.js, estructura de vistas

## Archivos de Documentación (Referencia)

Estos archivos NO son steering files, pero son referenciados por ellos:

- `guia-sintaxis-estandares.md` - Guía completa de sintaxis
- `documentacion-web-tools-helper.md` - Documentación de helpers
- `documentacion-librerias-core.md` - Documentación de librerías

## Cómo Funciona

### Inclusión Automática (auto)
```yaml
---
inclusion: auto
---
```
Se incluye en TODAS las conversaciones.

### Inclusión por Archivo (fileMatch)
```yaml
---
inclusion: fileMatch
fileMatchPattern: "app/Controllers/*.php"
---
```
Se incluye solo cuando trabajas con archivos que coinciden con el patrón.

### Inclusión Manual
```yaml
---
inclusion: manual
---
```
Solo se incluye cuando lo referencias con `#nombre-archivo.md`

## Cómo Agregar Nuevos Steering Files

1. Crear archivo en `.kiro/steering/nombre.md`
2. Agregar front-matter con configuración:
```yaml
---
title: Título del Steering
inclusion: auto|fileMatch|manual
fileMatchPattern: "ruta/patron/*.ext"  # Solo para fileMatch
---
```
3. Escribir las instrucciones en markdown
4. Guardar y Kiro lo cargará automáticamente

## Ejemplos de Patrones

```yaml
# Todos los controladores
fileMatchPattern: "app/Controllers/*.php"

# Todos los archivos en Views (recursivo)
fileMatchPattern: "app/Views/**/*.php"

# Solo archivos de configuración
fileMatchPattern: "app/Config/*.php"

# Archivos JavaScript
fileMatchPattern: "**/*.js"

# Archivos de un módulo específico
fileMatchPattern: "app/Controllers/app_accounting_*.php"
```

## Mejores Prácticas

1. **Mantén los steering files concisos** - Solo información esencial
2. **Usa fileMatch para contexto específico** - No sobrecargues el contexto global
3. **Referencia documentación completa** - Los steering apuntan a docs, no las duplican
4. **Actualiza cuando cambien estándares** - Mantén sincronizados con el código real
5. **Prueba los patrones** - Verifica que fileMatchPattern funcione correctamente

## Verificar Qué Steering Está Activo

Cuando Kiro responde, verás en la parte superior:
```
## Included Rules (nombre-archivo.md) [Workspace]
```

Esto te indica qué steering files están activos en ese momento.

## Desactivar Temporalmente

Si necesitas que Kiro ignore un steering file temporalmente:
1. Cambia la extensión: `archivo.md` → `archivo.md.disabled`
2. O muévelo fuera de `.kiro/steering/`

## Comandos Útiles

### Ver todos los steering files
```bash
ls -la .kiro/steering/
```

### Buscar en steering files
```bash
grep -r "palabra" .kiro/steering/
```

### Editar steering file
Simplemente abre el archivo en Kiro y edítalo.

## Troubleshooting

### El steering no se carga
- Verifica el front-matter YAML
- Asegúrate que el patrón fileMatchPattern sea correcto
- Revisa que el archivo esté en `.kiro/steering/`

### Se carga cuando no debería
- Revisa el patrón fileMatchPattern
- Considera cambiar de `auto` a `fileMatch`

### Conflictos entre steering files
- Los más específicos (fileMatch) tienen prioridad
- Workspace-level tiene prioridad sobre global-level

## Estructura Recomendada

```
.kiro/steering/
├── README.md                    # Este archivo
├── estandares-proyecto.md       # Siempre incluido
├── controladores-php.md         # Por tipo de archivo
├── modelos-php.md              # Por tipo de archivo
├── helpers-php.md              # Por tipo de archivo
├── librerias-php.md            # Por tipo de archivo
├── vistas-php.md               # Por tipo de archivo
└── [otros específicos]         # Según necesidad
```

## Próximos Pasos

Considera crear steering files para:
- Archivos de configuración (`app/Config/*.php`)
- Tests (`tests/**/*.php`)
- Migraciones (`app/Database/Migrations/*.php`)
- APIs específicas (`app/Controllers/app_*_api.php`)
- Módulos específicos (contabilidad, inventario, etc.)
