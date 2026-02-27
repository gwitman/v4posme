# Instrucciones para usar getBahavioSession

## Descripción
La función `getBahavioSession` permite personalizar valores (textos, etiquetas, configuraciones) según el tipo de compañía, buscando en la configuración de la sesión almacenada en `$objCompanyPageSetting`.

## Sintaxis

```php
getBahavioSession(
    $type_company,           // Tipo de compañía (string)
    $key_controller,         // Nombre del controlador (string)
    $key_element,           // Clave del elemento a personalizar (string)
    $default_value,         // Valor por defecto si no existe configuración (string)
    $objCompanyPageSetting  // Array de configuraciones de la compañía
);
```

## Parámetros

1. **$type_company**: Tipo de compañía obtenido de `$dataSession["company"]->type` o `$objCompany->type`
2. **$key_controller**: Nombre del controlador donde se usa (ej: "app_invoice_billing", "app_cxc_customer")
3. **$key_element**: Identificador único del elemento a personalizar (ej: "viewRegisterFormatoPaginaNormal80mmOpcion1_LabelTitle")
4. **$default_value**: Valor que se retorna si no existe configuración personalizada
5. **$objCompanyPageSetting**: Array de objetos con las configuraciones de la compañía

## Estructura del Array $objCompanyPageSetting

Cada elemento del array debe tener la siguiente estructura:

```php
[
    {
        "controller": "app_invoice_billing",
        "element": "viewRegisterFormatoPaginaNormal80mmOpcion1_LabelTitle",
        "valuei": "FACTURA PERSONALIZADA"
    },
    {
        "controller": "app_cxc_customer",
        "element": "labelTitlePageEdit",
        "valuei": "EDITAR CLIENTE"
    }
]
```

## Cómo funciona

1. La función busca en el array `$objCompanyPageSetting` un elemento que coincida con:
   - `controller` = `$key_controller` (comparación case-insensitive)
   - `element` = `$key_element` (comparación case-insensitive)

2. Si encuentra una coincidencia, retorna el valor de `valuei`
3. Si no encuentra coincidencia, retorna `$default_value`
4. En caso de error, retorna `$default_value`

## Ejemplos de Uso

### Ejemplo 1: En un Controlador (PHP)

```php
// En el método de un controlador
public function viewRegister() {
    $dataSession = $this->session->get();
    
    // Obtener configuraciones de la compañía
    $objListCompanyPageSetting = $this->Company_Page_Setting_Model->get_rowByKeyAndController(
        $dataSession["company"]->type,"controller_name"
    );
    
    // Usar getBahavioSession para obtener título personalizado
    $titleDocument = getBahavioSession(
        $dataSession["company"]->type,
        "app_invoice_billing",
        "viewRegisterFormatoPaginaNormal80mmOpcion1_LabelTitle",
        "FACTURA",
        $objListCompanyPageSetting
    );
    
    $dataView["titleDocument"] = $titleDocument;
    
    return view("app_invoice_billing/view_register", $dataView);
}
```

### Ejemplo 2: En una Vista (HTML/PHP)

```php
<!-- En un archivo de vista .php -->
<div class="form-group">
    <label class="col-lg-4 control-label" for="normal">
        <?php echo getBahavioSession(
            $company->type,
            "app_cxc_customer",
            "labelPermiteCobroPorWhatapp",
            "Permite cobro por whatsapp",
            $objListCompanyPageSetting
        ); ?>
    </label>
    <div class="col-lg-8">
        <input type="checkbox" name="txtAllowWhatsappCollection" id="txtAllowWhatsappCollection" value="1">
    </div>
</div>
```

### Ejemplo 3: Personalizar múltiples elementos en una vista

```php
<!-- En la vista -->
<h1>
    <?php echo getBahavioSession(
        $company->type,
        "app_cxc_customer",
        "labelTitlePageEdit",
        "EDITAR CLIENTE",
        $objListCompanyPageSetting
    ); ?>
</h1>

<div class="form-group">
    <label>
        <?php echo getBahavioSession(
            $company->type,
            "app_cxc_customer",
            "labelIdentificacion",
            "Cédula",
            $objListCompanyPageSetting
        ); ?>
    </label>
    <input type="text" name="txtIdentification" class="form-control">
</div>

<div class="form-group">
    <label>
        <?php echo getBahavioSession(
            $company->type,
            "app_cxc_customer",
            "labelNombre",
            "Nombre Completo",
            $objListCompanyPageSetting
        ); ?>
    </label>
    <input type="text" name="txtFirstName" class="form-control">
</div>

<button type="submit" class="btn btn-primary">
    <?php echo getBahavioSession(
        $company->type,
        "app_cxc_customer",
        "btnGuardar_Label",
        "Guardar",
        $objListCompanyPageSetting
    ); ?>
</button>
```

### Ejemplo 4: Uso en reportes

```php
// En un helper de reportes
function generarReporteFactura($objCompany, $objListCompanyPageSetting) {
    
    $titulo = getBahavioSession(
        $objCompany->type,
        "app_invoice_billing",
        "reporteFactura_Titulo",
        "FACTURA DE VENTA",
        $objListCompanyPageSetting
    );
    
    $subtitulo = getBahavioSession(
        $objCompany->type,
        "app_invoice_billing",
        "reporteFactura_Subtitulo",
        "Detalle de productos",
        $objListCompanyPageSetting
    );
    
    $html = "
        <h1>{$titulo}</h1>
        <h2>{$subtitulo}</h2>
    ";
    
    return $html;
}
```

## Convención de Nombres para $key_element

Se recomienda usar una convención clara para identificar los elementos:

### Para títulos de página:
- `labelTitlePageList` - Título de la lista
- `labelTitlePageNew` - Título de nuevo registro
- `labelTitlePageEdit` - Título de edición
- `labelTitlePageReport` - Título de reporte

### Para etiquetas de campos:
- `label[NombreCampo]` - Ej: `labelIdentificacion`, `labelNombre`

### Para botones:
- `btn[Accion]_Label` - Ej: `btnGuardar_Label`, `btnCancelar_Label`

### Para vistas/reportes:
- `view[NombreVista]_[Elemento]` - Ej: `viewRegisterFormatoPaginaNormal80mmOpcion1_LabelTitle`

### Para mensajes:
- `msg[TipoMensaje]` - Ej: `msgExito`, `msgError`

## Cómo Preparar los Datos en el Controlador

Para usar `getBahavioSession` en las vistas, primero debes cargar los datos necesarios en el controlador:

```php
public function edit() {
    $dataSession = $this->session->get();
    $companyID = $dataSession["company"]->companyID;
    
    // Cargar configuraciones de personalización
    $objListCompanyPageSetting = $this->Company_Page_Setting_Model->get_rowByKeyAndController($dataSession["company"]->type,"controller_name");
    
    // Preparar datos para la vista
    $dataView["company"] = $dataSession["company"];
    $dataView["objListCompanyPageSetting"] = $objListCompanyPageSetting;
    $dataView["objCustomer"] = $this->Customer_Model->get_rowByPK($companyID, $branchID, $entityID);
    
    return view("app_cxc_customer/edit_body", $dataView);
}
```

## Buenas Prácticas

1. **Siempre proporcionar un valor por defecto**: Asegura que la aplicación funcione aunque no exista configuración personalizada.

2. **Usar nombres descriptivos**: Los `$key_element` deben ser claros y autodescriptivos.

3. **Cargar configuraciones una sola vez**: En el controlador, cargar `$objListCompanyPageSetting` una vez y pasarlo a todas las vistas.

4. **Documentar personalizaciones**: Mantener un registro de qué elementos son personalizables.

5. **Validar datos**: Siempre validar que `$objListCompanyPageSetting` no sea null antes de usarlo.

## Ejemplo Completo: Controlador + Vista

### Controlador (app_cxc_customer.php)

```php
public function edit() {
    $dataSession = $this->session->get();
    $companyID = $dataSession["company"]->companyID;
    
    // Cargar configuraciones de personalización
    $objListCompanyPageSetting = $this->Company_Page_Setting_Model->get_rowByKeyAndController($dataSession["company"]->type,"controller_name");
    
    // Preparar datos para la vista
    $dataView["company"] = $dataSession["company"];
    $dataView["objListCompanyPageSetting"] = $objListCompanyPageSetting;
    $dataView["objCustomer"] = $this->Customer_Model->get_rowByPK($companyID, $branchID, $entityID);
    
    return view("app_cxc_customer/edit_body", $dataView);
}
```

### Vista (edit_body.php)

```php
<div class="page-header">
    <h1>
        <?php echo getBahavioSession(
            $company->type,
            "app_cxc_customer",
            "labelTitlePageEdit",
            "EDITAR CLIENTE",
            $objListCompanyPageSetting
        ); ?>
    </h1>
</div>

<form>
    <div class="form-group">
        <label>
            <?php echo getBahavioSession(
                $company->type,
                "app_cxc_customer",
                "labelIdentificacion",
                "Cédula",
                $objListCompanyPageSetting
            ); ?>
        </label>
        <input type="text" name="txtIdentification" class="form-control">
    </div>
    
    <div class="form-group">
        <label>
            <?php echo getBahavioSession(
                $company->type,
                "app_cxc_customer",
                "labelPermiteCobroPorWhatapp",
                "Permite cobro por whatsapp",
                $objListCompanyPageSetting
            ); ?>
        </label>
        <input type="checkbox" name="txtAllowWhatsappCollection" value="1">
    </div>
    
    <button type="submit" class="btn btn-primary">
        <?php echo getBahavioSession(
            $company->type,
            "app_cxc_customer",
            "btnGuardar_Label",
            "Guardar",
            $objListCompanyPageSetting
        ); ?>
    </button>
</form>
```

## Diferencia con getBehavio

- **getBehavio**: Busca en archivos helper estáticos de personalización por tipo de compañía
- **getBahavioSession**: Busca en la base de datos/sesión, permitiendo configuraciones dinámicas por compañía

## Notas Importantes

1. La función hace comparación **case-insensitive** de `controller` y `element`
2. Retorna el primer elemento que coincida
3. Si hay error en la búsqueda, retorna el `$default_value`
4. El campo que retorna es `valuei` (con "i" al final)
