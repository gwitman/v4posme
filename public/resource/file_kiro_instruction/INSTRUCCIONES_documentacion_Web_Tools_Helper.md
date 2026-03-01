# Documentación: web_tools_helper.php
## Funciones Helper del Sistema POSME

---

## Índice de Funciones

1. [Generación de IDs y Claves](#generación-de-ids-y-claves)
2. [Manejo de Segmentos URI](#manejo-de-segmentos-uri)
3. [Manejo de Request](#manejo-de-request)
4. [Fechas y Tiempo](#fechas-y-tiempo)
5. [Conversión de Números a Letras](#conversión-de-números-a-letras)
6. [Formateo de Texto](#formateo-de-texto)
7. [Manejo de Archivos y Directorios](#manejo-de-archivos-y-directorios)
8. [Teléfonos y Contactos](#teléfonos-y-contactos)
9. [HTML y UI](#html-y-ui)
10. [CSV y Exportación](#csv-y-exportación)
11. [FTP y Transferencia](#ftp-y-transferencia)
12. [Utilidades Varias](#utilidades-varias)

---

## Generación de IDs y Claves

### `GUIDv4($trim = true)`
Genera un GUID versión 4 (identificador único global).

**Parámetros:**
- `$trim` (bool): Si es true, elimina las llaves `{}` del GUID

**Retorna:** String con el GUID generado

**Ejemplo:**
```php
$guid = GUIDv4(); // "a1b2c3d4-e5f6-7890-abcd-ef1234567890"
$guidConLlaves = GUIDv4(false); // "{a1b2c3d4-e5f6-7890-abcd-ef1234567890}"
```

**Uso:** Generar identificadores únicos para registros, transacciones, archivos temporales.

---

### `helper_ObtenerClavePrimaria($db, $table)`
Obtiene el nombre de la columna que es clave primaria de una tabla.

**Parámetros:**
- `$db`: Conexión a base de datos
- `$table` (string): Nombre de la tabla

**Retorna:** String con el nombre de la columna o null

**Ejemplo:**
```php
$db = db_connect();
$pk = helper_ObtenerClavePrimaria($db, 'tb_account'); // "accountID"
```

**Uso:** Operaciones dinámicas con tablas donde necesitas conocer la PK.

---

## Manejo de Segmentos URI

### `helper_SegmentsKeyValue($objListSegments)`
Convierte un array de segmentos URI en un array asociativo clave-valor.

**Parámetros:**
- `$objListSegments` (array): Array de segmentos URI

**Retorna:** Array asociativo

**Ejemplo:**
```php
$segments = ['companyID', '1', 'accountID', '5'];
$result = helper_SegmentsKeyValue($segments);
// ['companyID' => '1', 'accountID' => '5']
```

**Uso:** Parsear parámetros de URL en formato clave/valor.

---

### `helper_SegmentsValue($objListSegments, $key)`
Obtiene el valor de un segmento URI por su clave.

**Parámetros:**
- `$objListSegments` (array): Array de segmentos
- `$key` (string): Clave a buscar

**Retorna:** String con el valor o vacío

**Ejemplo:**
```php
$companyID = helper_SegmentsValue($this->uri->getSegments(), "companyID");
```

**Uso:** Extraer parámetros específicos de la URL en controladores.

---

### `helper_SegmentsByIndex($objListSegments, $i, $variable)`
Obtiene un segmento por índice, con valor por defecto.

**Parámetros:**
- `$objListSegments` (array): Array de segmentos
- `$i` (int): Índice
- `$variable`: Valor por defecto

**Retorna:** Valor del segmento o el valor por defecto

**Ejemplo:**
```php
$method = helper_SegmentsByIndex($this->uri->getSegments(), 1, 'index');
```

**Uso:** Obtener segmentos opcionales con fallback.

---

## Manejo de Request

### `helper_RequestGetValue($value, $default)`
Obtiene un valor de request con valor por defecto si está vacío.

**Parámetros:**
- `$value`: Valor a validar
- `$default`: Valor por defecto

**Retorna:** El valor o el default

**Ejemplo:**
```php
$status = helper_RequestGetValue($this->request->getPost("status"), 1);
```

**Uso:** Validar y asignar valores por defecto a parámetros POST/GET.

---

### `helper_RequestGetValueObjet($obj, $field, $default)`
Obtiene un campo de un objeto con valor por defecto.

**Parámetros:**
- `$obj`: Objeto
- `$field` (string): Nombre del campo
- `$default`: Valor por defecto

**Retorna:** Valor del campo o default

**Ejemplo:**
```php
$name = helper_RequestGetValueObjet($objCustomer, 'firstName', 'N/A');
```

**Uso:** Acceso seguro a propiedades de objetos que pueden ser null.

---

## Fechas y Tiempo

### `helper_getDate()`
Obtiene la fecha actual ajustada según `APP_HOUR_DIFERENCE_PHP`.

**Retorna:** String en formato 'Y-m-d 00:00:00'

**Ejemplo:**
```php
$fecha = helper_getDate(); // "2024-03-15 00:00:00"
```

**Uso:** Obtener fecha actual del sistema con ajuste de zona horaria.

---

### `helper_getDateTime()`
Obtiene fecha y hora actual ajustada.

**Retorna:** String en formato 'Y-m-d H:i:s'

**Ejemplo:**
```php
$fechaHora = helper_getDateTime(); // "2024-03-15 14:30:45"
```

**Uso:** Timestamps para createdOn, modifiedOn, etc.

---

### `helper_getDateTime_Object()`
Obtiene objeto DateTime ajustado.

**Retorna:** Objeto DateTime

**Ejemplo:**
```php
$dateObj = helper_getDateTime_Object();
$formatted = $dateObj->format('d/m/Y');
```

**Uso:** Cuando necesitas manipular fechas con métodos de DateTime.

---

### `helper_PrimerDiaDelMes()`
Obtiene el primer día del mes actual.

**Retorna:** String 'Y-m-01'

**Ejemplo:**
```php
$inicio = helper_PrimerDiaDelMes(); // "2024-03-01"
```

**Uso:** Filtros de reportes mensuales, rangos de fechas.

---

### `helper_UltimoDiaDelMes()`
Obtiene el último día del mes actual con hora 23:59:59.

**Retorna:** String 'Y-m-d 23:59:59'

**Ejemplo:**
```php
$fin = helper_UltimoDiaDelMes(); // "2024-03-31 23:59:59"
```

**Uso:** Fin de rango para reportes mensuales.

---

### `helper_PrimerDiaDelYear()`
Obtiene el primer día del año actual.

**Retorna:** String 'Y-01-01'

**Ejemplo:**
```php
$inicioAnio = helper_PrimerDiaDelYear(); // "2024-01-01"
```

**Uso:** Reportes anuales, cierres contables.

---

### `helper_UltimoDiaDelYear()`
Obtiene el último día del año actual.

**Retorna:** String 'Y-m-d 23:59:59'

**Ejemplo:**
```php
$finAnio = helper_UltimoDiaDelYear(); // "2024-12-31 23:59:59"
```

**Uso:** Cierres anuales, reportes fiscales.

---

### `helper_getDateMoreOneMonth()`
Obtiene la fecha actual más un mes.

**Retorna:** String 'Y-m-d'

**Ejemplo:**
```php
$vencimiento = helper_getDateMoreOneMonth(); // "2024-04-15"
```

**Uso:** Calcular fechas de vencimiento, plazos.

---

### `helper_DateToSpanish($date_, $format)`
Convierte una fecha a formato español.

**Parámetros:**
- `$date_` (string): Fecha a convertir
- `$format` (string): Formato de salida

**Retorna:** String con fecha en español

**Ejemplo:**
```php
$fecha = helper_DateToSpanish('2024-03-15', 'Y-F-d'); // "2024-MARZO-15"
```

**Uso:** Mostrar fechas en reportes, facturas en español.

---

### `helper_GetFechaNacimiento($fechaString)`
Calcula la edad en años desde una fecha de nacimiento.

**Parámetros:**
- `$fechaString` (string): Fecha en formato 'Y-m-d'

**Retorna:** Int con años de edad

**Ejemplo:**
```php
$edad = helper_GetFechaNacimiento('1990-05-20'); // 34
```

**Uso:** Calcular edad de clientes, empleados.

---

### `helper_CompareDateTime($stringDateTime1, $stringDateTime2)`
Compara dos fechas y devuelve diferencias detalladas.

**Parámetros:**
- `$stringDateTime1` (string): Primera fecha
- `$stringDateTime2` (string): Segunda fecha

**Retorna:** Array con comparador, diferencias en segundos, minutos, horas, días, meses, años

**Ejemplo:**
```php
$diff = helper_CompareDateTime('2024-01-01 10:00:00', '2024-01-02 12:30:00');
// ['comparador' => -1, 'segundos' => 95400, 'horas' => 26, 'dias' => 1, ...]
```

**Uso:** Calcular tiempo transcurrido, vencimientos, SLA.

---

## Conversión de Números a Letras

### `helper_GetLetras($xcifra, $moneda, $centavos)`
Convierte un número a su representación en letras (español).

**Parámetros:**
- `$xcifra` (float): Número a convertir
- `$moneda` (string): Nombre de la moneda
- `$centavos` (string): Nombre de los centavos

**Retorna:** String con el número en letras

**Ejemplo:**
```php
$letras = helper_GetLetras(1250.50, "CORDOBAS", "CENTAVOS");
// "UN MIL DOSCIENTOS CINCUENTA CORDOBAS 50/100 CENTAVOS"
```

**Uso:** Facturas, cheques, documentos legales.

---

### `helper_GetNumberLetras($xcifra, $moneda, $centavos)`
Similar a helper_GetLetras pero sin mostrar los centavos en formato fracción.

**Parámetros:**
- `$xcifra` (float): Número a convertir
- `$moneda` (string): Nombre de la moneda
- `$centavos` (string): Nombre de los centavos

**Retorna:** String con el número en letras

**Ejemplo:**
```php
$letras = helper_GetNumberLetras(1250, "CORDOBAS", "");
// "UN MIL DOSCIENTOS CINCUENTA CORDOBAS"
```

**Uso:** Documentos donde no se requiere mostrar centavos.

---

## Formateo de Texto

### `helper_getStringClear($texto)`
Limpia caracteres especiales de un texto.

**Parámetros:**
- `$texto` (string): Texto a limpiar

**Retorna:** String limpio

**Ejemplo:**
```php
$limpio = helper_getStringClear("<script>alert('xss')</script>");
// "scriptalert('xss')/script"
```

**Uso:** Sanitizar inputs, prevenir XSS.

---

### `helper_QuitarAcentos($texto)`
Elimina acentos y caracteres especiales del español.

**Parámetros:**
- `$texto` (string): Texto con acentos

**Retorna:** String sin acentos

**Ejemplo:**
```php
$sinAcentos = helper_QuitarAcentos("José María"); // "Jose Maria"
```

**Uso:** Normalizar nombres para búsquedas, URLs, archivos.

---

### `helper_StrPadString($string, $quantity, $char)`
Rellena un string con caracteres a la izquierda.

**Parámetros:**
- `$string` (string): Texto a rellenar
- `$quantity` (int): Longitud total deseada
- `$char` (string): Caracter de relleno

**Retorna:** String rellenado

**Ejemplo:**
```php
$numero = helper_StrPadString("5", 6, "0"); // "000005"
```

**Uso:** Formatear números de factura, códigos, IDs.

---

### `helper_StringToNumber($string)`
Convierte un string con formato de número a número.

**Parámetros:**
- `$string` (string): String con comas

**Retorna:** String sin comas

**Ejemplo:**
```php
$numero = helper_StringToNumber("1,250.50"); // "1250.50"
```

**Uso:** Procesar inputs de montos con formato.

---

### `helper_InsertarEntrePartes($original, $insertar, $n)`
Divide un texto en N partes e inserta un separador entre ellas.

**Parámetros:**
- `$original` (string): Texto original
- `$insertar` (string): Separador a insertar
- `$n` (int): Número de partes

**Retorna:** String con separadores

**Ejemplo:**
```php
$formatted = helper_InsertarEntrePartes("1234567890", "-", 3);
// "1234-567-890"
```

**Uso:** Formatear números de teléfono, tarjetas, códigos.

---

### `helper_obtenerPrimeras5Palabras($texto, $cantidad)`
Obtiene las primeras N palabras de un texto.

**Parámetros:**
- `$texto` (string): Texto completo
- `$cantidad` (int): Número de palabras a extraer

**Retorna:** String con las primeras palabras

**Ejemplo:**
```php
$resumen = helper_obtenerPrimeras5Palabras("Este es un texto largo", 3);
// "Este es un"
```

**Uso:** Crear resúmenes, previews de texto.

---

### `replaceSimbol($string)`
Reemplaza códigos de símbolos por emojis y caracteres especiales.

**Parámetros:**
- `$string` (string): Texto con códigos

**Retorna:** String con emojis

**Ejemplo:**
```php
$texto = replaceSimbol("Hola [simbol-carita-feliz] mundo");
// "Hola 😊 mundo"
```

**Uso:** Convertir códigos de emojis en mensajes, notificaciones.

**Símbolos disponibles:**
- `[simbol-carita-feliz]` → 😊
- `[simbol-corazon]` → ❤️
- `[simbol-check]` → ✅
- `[simbol-computadora]` → 💻
- Y muchos más...

---

### `helper_convertirLinkAHtml($text, $label)`
Convierte URLs en texto a enlaces HTML.

**Parámetros:**
- `$text` (string): Texto con URLs
- `$label` (string): Etiqueta para el enlace

**Retorna:** String con enlaces HTML

**Ejemplo:**
```php
$html = helper_convertirLinkAHtml("Ver https://example.com", "aquí");
// "Ver <a href='https://example.com'>aquí</a>"
```

**Uso:** Convertir URLs en mensajes a enlaces clicables.

---

### `helper_reemplazarPuntosExceptoUltimo($precieValueAbs)`
Reemplaza puntos por comas excepto el último (decimal).

**Parámetros:**
- `$precieValueAbs` (string): Número con puntos

**Retorna:** String formateado

**Ejemplo:**
```php
$precio = helper_reemplazarPuntosExceptoUltimo("1.250.50");
// "1,250.50"
```

**Uso:** Formatear precios con separadores de miles.

---

### `helper_convertToUTF8($texto)`
Convierte texto a UTF-8 si no lo está.

**Parámetros:**
- `$texto` (string): Texto a convertir

**Retorna:** String en UTF-8

**Ejemplo:**
```php
$utf8 = helper_convertToUTF8($textoWindows);
```

**Uso:** Normalizar encoding de textos de diferentes fuentes.

---

## Manejo de Archivos y Directorios

### `emptyDir($dir)`
Vacía un directorio sin eliminarlo.

**Parámetros:**
- `$dir` (string): Ruta del directorio

**Retorna:** void

**Ejemplo:**
```php
emptyDir(WRITEPATH . 'temp/');
```

**Uso:** Limpiar directorios temporales, cache.

---

### `deleteDir($dir)`
Elimina un directorio y todo su contenido.

**Parámetros:**
- `$dir` (string): Ruta del directorio

**Retorna:** void

**Ejemplo:**
```php
deleteDir(WRITEPATH . 'temp/session123/');
```

**Uso:** Eliminar directorios temporales completos.

---

## Teléfonos y Contactos

### `clearNumero($numero)`
Limpia caracteres especiales de un número de teléfono.

**Parámetros:**
- `$numero` (string): Número con formato

**Retorna:** String solo con dígitos

**Ejemplo:**
```php
$limpio = clearNumero("+505 (8888) 1234"); // "50588881234"
```

**Uso:** Normalizar números antes de guardar o comparar.

---

### `getNumberPhone($numero)`
Agrega código de país 505 si el número tiene 8 dígitos.

**Parámetros:**
- `$numero` (string): Número de teléfono

**Retorna:** String con código de país

**Ejemplo:**
```php
$completo = getNumberPhone("88881234"); // "50588881234"
```

**Uso:** Normalizar números locales a formato internacional.

---

### `getNumberPhoneIsContact($numero)`
Limpia número de contacto y quita código 505 si existe.

**Parámetros:**
- `$numero` (string): Número de contacto

**Retorna:** String con número local

**Ejemplo:**
```php
$local = getNumberPhoneIsContact("contact.us50588881234");
// "88881234"
```

**Uso:** Procesar números de contactos de WhatsApp.

---

## HTML y UI

### `helper_GetHtmlControlHora($id, $valueHora)`
Genera controles HTML para seleccionar hora.

**Parámetros:**
- `$id` (string): ID del control
- `$valueHora` (string): Hora inicial

**Retorna:** String con HTML

**Ejemplo:**
```php
echo helper_GetHtmlControlHora('txtHora', '2024-01-01 14:30:00');
```

**Uso:** Formularios con selección de hora.

---

### `helper_getHtmlOfPageLanding()`
Genera HTML de página de carga (loading).

**Retorna:** void (imprime HTML)

**Ejemplo:**
```php
helper_getHtmlOfPageLanding();
```

**Uso:** Mostrar loading mientras carga la página.

---

### `helper_getHtmlOfModalDialog($name, $idDivBody, $fncallBack, ...)`
Genera HTML de modal personalizado.

**Parámetros:**
- `$name` (string): Nombre del modal
- `$idDivBody` (string): ID del div con contenido
- `$fncallBack` (string): Función callback al aceptar
- `$fnShowBotonesCerrar` (bool): Mostrar botón cerrar
- `$fnShowBotonesAceptar` (bool): Mostrar botón aceptar
- `$cssModalContent` (string): CSS adicional

**Retorna:** void (imprime HTML)

**Ejemplo:**
```php
helper_getHtmlOfModalDialog('miModal', 'divContenido', 'procesarModal');
```

**Uso:** Crear modales de confirmación, alertas personalizadas.

---

### `helper_getCssWidthInvoiceMobile()`
Genera CSS para facturas responsive en móvil.

**Retorna:** void (imprime CSS)

**Ejemplo:**
```php
helper_getCssWidthInvoiceMobile();
```

**Uso:** Ajustar diseño de facturas para móviles.

---

### `helper_getHtmlOfStylePageListReportCSS()`
Genera CSS para listado de reportes en grid.

**Retorna:** void (imprime CSS)

**Ejemplo:**
```php
helper_getHtmlOfStylePageListReportCSS();
```

**Uso:** Estilizar páginas de listado de reportes.

---

### `helper_getHtmlOfStylePageListReportJS()`
Genera JavaScript para listado de reportes.

**Retorna:** void (imprime JS)

**Ejemplo:**
```php
helper_getHtmlOfStylePageListReportJS();
```

**Uso:** Funcionalidad de clicks en reportes.

---

### `helper_notificationPage($titlePage, $script, $summaryPage, ...)`
Genera página HTML completa de notificación.

**Parámetros:**
- `$titlePage` (string): Título
- `$script` (string): Script adicional
- `$summaryPage` (string): Mensaje
- `$labelButtonPage` (string): Texto del botón
- `$linkButtonPage` (string): URL del botón
- `$hiddenButtonPage` (bool): Ocultar botón
- `$backgroundHex` (string): Color de fondo

**Retorna:** String con HTML completo

**Ejemplo:**
```php
$html = helper_notificationPage(
    "Éxito", 
    "", 
    "Operación completada", 
    "Volver", 
    "/dashboard"
);
```

**Uso:** Páginas de confirmación, errores, notificaciones.

---

### `helper_getAlertThemeSneatBoostrap5_1($title, $type, $mensaje)`
Genera alerta con tema Sneat Bootstrap 5.

**Parámetros:**
- `$title` (string): Título de la alerta
- `$type` (string): Tipo (success, danger, warning, info)
- `$mensaje` (string): Mensaje

**Retorna:** String con HTML de alerta

**Ejemplo:**
```php
echo helper_getAlertThemeSneatBoostrap5_1(
    "Éxito", 
    "success", 
    "Registro guardado"
);
```

**Uso:** Mostrar alertas con diseño consistente.

---

## CSV y Exportación

### `helper_toCsv(array $data, string $delimiter = ',')`
Convierte array de datos a formato CSV.

**Parámetros:**
- `$data` (array): Array de datos
- `$delimiter` (string): Delimitador

**Retorna:** String con contenido CSV

**Ejemplo:**
```php
$csv = helper_toCsv([
    [['id' => 1, 'name' => 'Juan']],
    [['id' => 2, 'name' => 'María']]
]);
```

**Uso:** Exportar datos a CSV.

---

### `helper_generarCSV(array $headers, array $mapping, array $data, string $delimiter = ",")`
Genera CSV con encabezados y mapeo personalizado.

**Parámetros:**
- `$headers` (array): Encabezados del CSV
- `$mapping` (array): Mapeo de campos
- `$data` (array): Datos
- `$delimiter` (string): Delimitador

**Retorna:** String con CSV

**Ejemplo:**
```php
$csv = helper_generarCSV(
    ['ID', 'Nombre'],
    ['id', 'firstName'],
    $objListCustomers
);
```

**Uso:** Exportaciones personalizadas a CSV.

---

## FTP y Transferencia

### `helper_sendFtp($csvContent, $merchanId, $ftpIp, ...)`
Envía archivo por SFTP.

**Parámetros:**
- `$csvContent` (string): Contenido del archivo
- `$merchanId` (string): ID del comercio
- `$ftpIp` (string): IP del servidor
- `$ftpUser` (string): Usuario
- `$ftpPass` (string): Contraseña
- `$ftpPort` (int): Puerto
- `$fileName` (string): Nombre del archivo
- `$remoteDir` (string): Directorio remoto

**Retorna:** String con resultado

**Ejemplo:**
```php
$result = helper_sendFtp(
    $csvContent, 
    "MERCHANT001", 
    "192.168.1.100", 
    "user", 
    "pass", 
    22, 
    "export.csv", 
    "/uploads/"
);
```

**Uso:** Enviar archivos de exportación a servidores externos.

---

## Utilidades Varias

### `helper_GetColorSinRiesgo($valor)`
Obtiene color según estado de riesgo.

**Parámetros:**
- `$valor` (string): Estado

**Retorna:** String con nombre de color

**Ejemplo:**
```php
$color = helper_GetColorSinRiesgo("saneado"); // "red"
```

**Uso:** Colorear estados en reportes de crédito.

---

### `helper_GetSr($Sexo)`
Obtiene tratamiento según sexo.

**Parámetros:**
- `$Sexo` (string): "FEMENINO" o "MASCULINO"

**Retorna:** "Sra." o "Sr."

**Ejemplo:**
```php
$tratamiento = helper_GetSr("FEMENINO"); // "Sra."
```

**Uso:** Personalizar documentos, cartas.

---

### `helper_GetEl($Sexo)`
Obtiene artículo según sexo.

**Parámetros:**
- `$Sexo` (string): "FEMENINO" o "MASCULINO"

**Retorna:** "la" o "el"

**Ejemplo:**
```php
$articulo = helper_GetEl("MASCULINO"); // "el"
```

**Uso:** Generar textos gramaticalmente correctos.

---

### `helper_getParameterFiltered($objListCompanyParameter, $parameterName)`
Filtra un parámetro de una lista de parámetros de empresa.

**Parámetros:**
- `$objListCompanyParameter` (array): Lista de parámetros
- `$parameterName` (string): Nombre del parámetro

**Retorna:** Objeto del parámetro o false

**Ejemplo:**
```php
$param = helper_getParameterFiltered(
    $objListCompanyParameter, 
    "INVOICE_BILLING_QUANTITY_DAYS"
);
```

**Uso:** Obtener configuraciones específicas de empresa.

---

## Resumen de Uso por Categoría

### Para Controladores
- `helper_SegmentsValue()` - Extraer parámetros de URL
- `helper_RequestGetValue()` - Validar inputs POST/GET
- `helper_getDateTime()` - Timestamps de operaciones

### Para Modelos
- `GUIDv4()` - Generar IDs únicos
- `helper_getDate()` - Fechas para filtros

### Para Vistas
- `helper_GetLetras()` - Números en letras para facturas
- `helper_DateToSpanish()` - Fechas en español
- `replaceSimbol()` - Emojis en mensajes

### Para Reportes
- `helper_PrimerDiaDelMes()` - Rangos de fechas
- `helper_toCsv()` - Exportar a CSV
- `helper_generarCSV()` - CSV personalizado

### Para Validación
- `helper_getStringClear()` - Sanitizar inputs
- `clearNumero()` - Limpiar teléfonos
- `helper_QuitarAcentos()` - Normalizar texto

### Para UI
- `helper_getHtmlOfModalDialog()` - Modales
- `helper_notificationPage()` - Páginas de notificación
- `helper_getAlertThemeSneatBoostrap5_1()` - Alertas

---

## Mejores Prácticas

1. **Siempre usar helpers para fechas** en lugar de date() directo
2. **Sanitizar inputs** con helper_getStringClear() antes de guardar
3. **Normalizar teléfonos** con clearNumero() y getNumberPhone()
4. **Usar helper_RequestGetValue()** para valores opcionales
5. **Generar GUIDs** para identificadores únicos en lugar de IDs secuenciales
6. **Convertir números a letras** en documentos legales con helper_GetLetras()
7. **Usar helper_CompareDateTime()** para cálculos de tiempo precisos

---

**Archivo:** `app/Helpers/web_tools_helper.php`  
**Proyecto:** POSME (Point of Sale Management Enterprise)  
**Framework:** CodeIgniter 4
