# Documentación: Librerías Core del Sistema POSME
## app/Libraries - CodeIgniter 4

---

## Índice General

1. [Autenticación y Seguridad](#autenticación-y-seguridad)
2. [Permisos y Autorización](#permisos-y-autorización)
3. [Transacciones](#transacciones)
4. [Workflow](#workflow)
5. [Parámetros](#parámetros)
6. [Catálogos](#catálogos)
7. [Contabilidad](#contabilidad)
8. [Amortización](#amortización)
9. [Inventario](#inventario)
10. [Herramientas y Utilidades](#herramientas-y-utilidades)

---

## Autenticación y Seguridad

### `core_web_authentication`
Maneja la autenticación de usuarios y sesiones.

**Ubicación:** `app/Libraries/core_web_authentication.php`

#### Métodos Principales

##### `get_UserBy_PasswordAndNickname($nickname, $password)`
Autentica un usuario por nickname y contraseña.

**Parámetros:**
- `$nickname` (string): Nombre de usuario
- `$password` (string): Contraseña

**Retorna:** Objeto con datos completos del usuario, empresa, sucursal, rol, menús

**Ejemplo:**
```php
$core_web_authentication = new core_web_authentication();
$userData = $core_web_authentication->get_UserBy_PasswordAndNickname('admin', 'password123');
```

**Uso:** Login de usuarios, validación de credenciales

**Datos retornados:**
- `user`: Objeto del usuario
- `company`: Datos de la empresa
- `branch`: Sucursal del usuario
- `role`: Rol asignado
- `menuTop`, `menuLeft`, `menuBodyReport`, etc.: Menús según permisos

---

##### `get_UserBy_Email($email)`
Obtiene usuario por email.

**Parámetros:**
- `$email` (string): Email del usuario

**Retorna:** Objeto usuario o null

**Ejemplo:**
```php
$user = $core_web_authentication->get_UserBy_Email('user@example.com');
```

**Uso:** Recuperación de contraseña, búsqueda de usuarios

---

##### `createLogin($data)`
Crea una sesión de login.

**Parámetros:**
- `$data` (array): Datos de sesión a guardar

**Retorna:** void

**Ejemplo:**
```php
$core_web_authentication->createLogin($userData);
```

**Uso:** Después de autenticar, guardar sesión

---

##### `destroyLogin()`
Destruye la sesión actual.

**Retorna:** void

**Ejemplo:**
```php
$core_web_authentication->destroyLogin();
```

**Uso:** Logout de usuarios

---

##### `isAuthenticated()`
Verifica si hay una sesión activa.

**Retorna:** bool

**Ejemplo:**
```php
if(!$core_web_authentication->isAuthenticated()) {
    throw new \Exception(USER_NOT_AUTENTICATED);
}
```

**Uso:** Validar acceso en cada método de controlador

---

##### `isSessionActiva($sessionID)`
Verifica si una sesión específica está activa.

**Parámetros:**
- `$sessionID` (string): ID de sesión

**Retorna:** bool

**Uso:** Validar sesiones concurrentes

---

##### `isCashBoxOpen($companyID, $userID, $dateTimeOn, $currencyID)`
Verifica si la caja está abierta para un usuario.

**Parámetros:**
- `$companyID` (int): ID de empresa
- `$userID` (int): ID de usuario
- `$dateTimeOn` (string): Fecha/hora
- `$currencyID` (int): ID de moneda

**Retorna:** bool

**Ejemplo:**
```php
$isOpen = $core_web_authentication->isCashBoxOpen(1, 5, '2024-03-15 10:00:00', 1);
```

**Uso:** Validar antes de realizar ventas o transacciones de caja

---

## Permisos y Autorización

### `core_web_permission`
Gestiona permisos de usuarios sobre funcionalidades.

**Ubicación:** `app/Libraries/core_web_permission.php`

#### Métodos Principales

##### `getElementID($controler, $method, $suffix, $dataMenuTop, $dataMenuLeft, ...)`
Obtiene el ID del elemento de menú.

**Parámetros:**
- `$controler` (string): Nombre del controlador
- `$method` (string): Método del controlador
- `$suffix` (string): Sufijo URL
- `$dataMenuTop`, `$dataMenuLeft`, etc.: Arrays de menús

**Retorna:** int (elementID) o null

**Uso:** Identificar elemento de menú para validar permisos

---

##### `urlPermited($controler, $method, $suffix, $dataMenuTop, $dataMenuLeft, ...)`
Verifica si el usuario tiene permiso para acceder a una URL.

**Parámetros:**
- `$controler` (string): Nombre del controlador
- `$method` (string): Método
- `$suffix` (string): Sufijo
- Menús del usuario

**Retorna:** bool

**Ejemplo:**
```php
$permited = $core_web_permission->urlPermited(
    get_class($this), 
    "index", 
    URL_SUFFIX,
    $dataSession["menuTop"],
    $dataSession["menuLeft"],
    $dataSession["menuBodyReport"],
    $dataSession["menuBodyTop"],
    $dataSession["menuHiddenPopup"]
);

if(!$permited)
    throw new \Exception(NOT_ACCESS_CONTROL);
```

**Uso:** Validar acceso a funcionalidades en controladores

---


##### `urlPermissionCmd($controler, $method, $suffix, $session_, $dataMenuTop, ...)`
Obtiene el nivel de permiso para una acción específica.

**Parámetros:**
- `$controler` (string): Controlador
- `$method` (string): Método (edit, delete, etc.)
- `$suffix` (string): Sufijo
- `$session_` (array): Datos de sesión
- Menús del usuario

**Retorna:** String con nivel de permiso

**Valores posibles:**
- `PERMISSION_ALL`: Permiso total
- `PERMISSION_ME`: Solo registros propios
- `PERMISSION_NONE`: Sin permiso

**Ejemplo:**
```php
$resultPermission = $core_web_permission->urlPermissionCmd(
    get_class($this), 
    "edit", 
    URL_SUFFIX, 
    $dataSession,
    $dataSession["menuTop"],
    $dataSession["menuLeft"],
    $dataSession["menuBodyReport"],
    $dataSession["menuBodyTop"],
    $dataSession["menuHiddenPopup"]
);

if($resultPermission == PERMISSION_NONE)
    throw new \Exception(NOT_ALL_EDIT);

if($resultPermission == PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))
    throw new \Exception(NOT_DELETE);
```

**Uso:** Validar permisos específicos (editar, eliminar, etc.)

---

##### `getValueLicense($companyID, $url)`
Obtiene el valor de una licencia para una funcionalidad.

**Parámetros:**
- `$companyID` (int): ID de empresa
- `$url` (string): URL de la funcionalidad

**Retorna:** String con valor de licencia

**Uso:** Validar licencias de módulos

---

##### `getLicenseMessage($companyID)`
Obtiene mensaje de estado de licencia.

**Parámetros:**
- `$companyID` (int): ID de empresa

**Retorna:** String con mensaje

**Uso:** Mostrar alertas de licencia

---

## Transacciones

### `core_web_transaction`
Gestiona transacciones del sistema.

**Ubicación:** `app/Libraries/core_web_transaction.php`

#### Métodos Principales

##### `getTransactionID($companyID, $componentName, $componentItemID)`
Obtiene el ID de transacción para un componente.

**Parámetros:**
- `$companyID` (int): ID de empresa
- `$componentName` (string): Nombre del componente
- `$componentItemID` (int): ID del item (0 si no aplica)

**Retorna:** int (transactionID)

**Ejemplo:**
```php
$transactionID = $core_web_transaction->getTransactionID(
    $companyID, 
    "tb_transaction_master_billing", 
    0
);
```

**Uso:** Obtener ID de transacción para facturas, compras, etc.

**Componentes comunes:**
- `tb_transaction_master_billing` - Facturas
- `tb_transaction_master_purchase` - Compras
- `tb_transaction_master_inputunpost` - Entradas
- `tb_transaction_master_outputunpost` - Salidas

---

##### `getTransaction($companyID, $name)`
Obtiene datos completos de una transacción.

**Parámetros:**
- `$companyID` (int): ID de empresa
- `$name` (string): Nombre de transacción

**Retorna:** Objeto transacción

**Ejemplo:**
```php
$objTransaction = $core_web_transaction->getTransaction($companyID, "INVOICE_BILLING");
```

**Uso:** Obtener configuración de transacciones

---

##### `getConcept($companyID, $transactionName, $conceptName)`
Obtiene un concepto de transacción.

**Parámetros:**
- `$companyID` (int): ID de empresa
- `$transactionName` (string): Nombre de transacción
- `$conceptName` (string): Nombre del concepto

**Retorna:** Objeto concepto

**Ejemplo:**
```php
$concept = $core_web_transaction->getConcept(
    $companyID, 
    "INVOICE_BILLING", 
    "INVOICE_BILLING_CASH"
);
```

**Uso:** Obtener conceptos contables de transacciones

---

##### `getDefaultCausalID($companyID, $transactionID)`
Obtiene el causal por defecto de una transacción.

**Parámetros:**
- `$companyID` (int): ID de empresa
- `$transactionID` (int): ID de transacción

**Retorna:** int (causalID)

**Ejemplo:**
```php
$causalID = $core_web_transaction->getDefaultCausalID($companyID, $transactionID);
```

**Uso:** Asignar causal automático a transacciones

---

##### `createInverseDocumentByTransaccion($companyIDOriginal, $transactionIDOriginal, ...)`
Crea documento inverso (reverso) de una transacción.

**Parámetros:**
- `$companyIDOriginal` (int): ID de empresa
- `$transactionIDOriginal` (int): ID transacción original
- `$transactionMasterIDOriginal` (int): ID master original
- `$transactionIDRevert` (int): ID transacción reverso
- `$transactionMasterIDRevert` (int): ID master reverso

**Retorna:** Array con resultado

**Ejemplo:**
```php
$result = $core_web_transaction->createInverseDocumentByTransaccion(
    $companyID,
    $transactionIDOriginal,
    $transactionMasterIDOriginal,
    $transactionIDRevert,
    $transactionMasterIDRevert
);
```

**Uso:** Anular facturas, devoluciones, reversos contables

---

##### `getCountTransactionBillingAnuladas($companyID)`
Cuenta facturas anuladas.

**Parámetros:**
- `$companyID` (int): ID de empresa

**Retorna:** int

**Uso:** Reportes, estadísticas

---

##### `getCountTransactionBillingCancel($companyID)`
Cuenta facturas canceladas.

**Parámetros:**
- `$companyID` (int): ID de empresa

**Retorna:** int

**Uso:** Reportes, estadísticas

---

## Workflow

### `core_web_workflow`
Gestiona flujos de trabajo y estados.

**Ubicación:** `app/Libraries/core_web_workflow.php`

#### Métodos Principales

##### `getWorkflowAllStage($table, $field, $companyID, $branchID, $roleID)`
Obtiene todos los estados de un workflow.

**Parámetros:**
- `$table` (string): Tabla del workflow
- `$field` (string): Campo de estado
- `$companyID` (int): ID de empresa
- `$branchID` (int): ID de sucursal
- `$roleID` (int): ID de rol

**Retorna:** Array de objetos con estados

**Ejemplo:**
```php
$objListWorkflowStage = $core_web_workflow->getWorkflowAllStage(
    "tb_transaction_master_billing",
    "statusID",
    $companyID,
    $branchID,
    $roleID
);
```

**Uso:** Llenar dropdowns de estados, mostrar opciones disponibles

---

##### `getWorkflowInitStage($table, $field, $companyID, $branchID, $roleID)`
Obtiene el estado inicial de un workflow.

**Parámetros:**
- `$table` (string): Tabla
- `$field` (string): Campo
- `$companyID`, `$branchID`, `$roleID`: IDs

**Retorna:** Objeto con estado inicial

**Ejemplo:**
```php
$objWorkflowStageInit = $core_web_workflow->getWorkflowInitStage(
    "tb_transaction_master_billing",
    "statusID",
    $companyID,
    $branchID,
    $roleID
);
```

**Uso:** Asignar estado inicial a nuevos registros

---

##### `getWorkflowStageTargetBySource($table, $field, $companyID, $branchID, $roleID, $sourceStageID)`
Obtiene estados destino desde un estado origen.

**Parámetros:**
- Estados origen y destino
- IDs de empresa, sucursal, rol

**Retorna:** Array de estados posibles

**Ejemplo:**
```php
$objListWorkflowStageTarget = $core_web_workflow->getWorkflowStageTargetBySource(
    "tb_transaction_master_billing",
    "statusID",
    $companyID,
    $branchID,
    $roleID,
    $currentStatusID
);
```

**Uso:** Mostrar transiciones de estado permitidas

---

##### `validateWorkflowStage($table, $field, $stageID, $cmd, $companyID, $branchID, $roleID)`
Valida si se puede ejecutar un comando en un estado.

**Parámetros:**
- `$table`, `$field`: Tabla y campo
- `$stageID` (int): Estado actual
- `$cmd` (string): Comando (EDIT, DELETE, NEW, etc.)
- IDs de empresa, sucursal, rol

**Retorna:** bool

**Ejemplo:**
```php
$canEdit = $core_web_workflow->validateWorkflowStage(
    "tb_transaction_master_billing",
    "statusID",
    $statusID,
    "EDIT",
    $companyID,
    $branchID,
    $roleID
);

if(!$canEdit)
    throw new \Exception("NO SE PUEDE EDITAR EN ESTE ESTADO");
```

**Uso:** Validar permisos según estado del documento

**Comandos comunes:**
- `NEW` - Crear nuevo
- `EDIT` - Editar
- `DELETE` - Eliminar
- `PRINT` - Imprimir
- `CANCEL` - Cancelar

---


## Parámetros

### `core_web_parameter`
Gestiona parámetros de configuración de empresa.

**Ubicación:** `app/Libraries/core_web_parameter.php`

#### Métodos Principales

##### `getParameter($parameterName, $companyID)`
Obtiene un parámetro completo.

**Parámetros:**
- `$parameterName` (string): Nombre del parámetro
- `$companyID` (int): ID de empresa

**Retorna:** Objeto parámetro con todos sus datos

**Ejemplo:**
```php
$objParameter = $core_web_parameter->getParameter("INVOICE_BILLING_QUANTITY_DAYS", $companyID);
// Acceder: $objParameter->value, $objParameter->parameterID, etc.
```

**Uso:** Obtener configuración completa de parámetros

---

##### `getParameterValue($parameterName, $companyID)`
Obtiene solo el valor de un parámetro.

**Parámetros:**
- `$parameterName` (string): Nombre del parámetro
- `$companyID` (int): ID de empresa

**Retorna:** String con el valor

**Ejemplo:**
```php
$days = $core_web_parameter->getParameterValue("INVOICE_BILLING_QUANTITY_DAYS", $companyID);
// Retorna: "30"
```

**Uso:** Obtener valores de configuración rápidamente

**Parámetros comunes:**
- `INVOICE_BILLING_QUANTITY_DAYS` - Días de crédito
- `INVOICE_BILLING_CANCEL` - Estado cancelado
- `INVOICE_BILLING_ANULADAS` - Estado anulado
- `CXC_FRECUENCY_PAY` - Frecuencia de pago
- `ACCOUNTING_CURRENCY_ID` - Moneda contable

---

##### `getParameterId($parameterName, $companyID)`
Obtiene el ID de un parámetro.

**Parámetros:**
- `$parameterName` (string): Nombre
- `$companyID` (int): ID empresa

**Retorna:** int (parameterID)

**Uso:** Referencias a parámetros

---

##### `getParameterAll($companyID)`
Obtiene todos los parámetros de una empresa.

**Parámetros:**
- `$companyID` (int): ID de empresa

**Retorna:** Array asociativo [nombre => valor]

**Ejemplo:**
```php
$allParams = $core_web_parameter->getParameterAll($companyID);
// ['INVOICE_BILLING_QUANTITY_DAYS' => '30', 'CXC_FRECUENCY_PAY' => 'MONTHLY', ...]
```

**Uso:** Cargar todas las configuraciones de una vez

---

##### `getParameterAllToJavaScript($companyID)`
Genera código JavaScript con todos los parámetros.

**Parámetros:**
- `$companyID` (int): ID de empresa

**Retorna:** String con código JavaScript

**Ejemplo:**
```php
echo $core_web_parameter->getParameterAllToJavaScript($companyID);
// Genera:
// var objCompanyParameter_Key_INVOICE_BILLING_QUANTITY_DAYS = '30';
// var objCompanyParameter_Key_CXC_FRECUENCY_PAY = 'MONTHLY';
```

**Uso:** Pasar parámetros a vistas JavaScript

---

##### `getParameterFiltered($objListCompanyParameter, $parameterName)`
Filtra un parámetro de una lista.

**Parámetros:**
- `$objListCompanyParameter` (array): Lista de parámetros
- `$parameterName` (string): Nombre a buscar

**Retorna:** Objeto parámetro o false

**Ejemplo:**
```php
$param = $core_web_parameter->getParameterFiltered(
    $objListCompanyParameter, 
    "INVOICE_BILLING_QUANTITY_DAYS"
);
```

**Uso:** Buscar en lista ya cargada sin consultar BD

---

## Catálogos

### `core_web_catalog`
Gestiona catálogos del sistema.

**Ubicación:** `app/Libraries/core_web_catalog.php`

#### Métodos Principales

##### `getCatalogAllItem($table, $field, $companyID)`
Obtiene todos los items de un catálogo.

**Parámetros:**
- `$table` (string): Tabla del catálogo
- `$field` (string): Campo del catálogo
- `$companyID` (int): ID de empresa

**Retorna:** Array de objetos con items del catálogo

**Ejemplo:**
```php
$objListCurrency = $core_web_catalog->getCatalogAllItem(
    "tb_transaction_master_billing",
    "currencyID",
    $companyID
);
```

**Uso:** Llenar dropdowns, listas de selección

**Catálogos comunes:**
- `currencyID` - Monedas
- `statusID` - Estados
- `typeID` - Tipos
- `classID` - Clasificaciones

---

##### `getCatalogAllItemIncludeId($table, $field, $companyID, $catalogItemID)`
Obtiene items de catálogo incluyendo uno específico aunque esté inactivo.

**Parámetros:**
- `$table`, `$field`, `$companyID`: Igual que anterior
- `$catalogItemID` (int): ID del item a incluir

**Retorna:** Array de items

**Ejemplo:**
```php
$objListCurrency = $core_web_catalog->getCatalogAllItemIncludeId(
    "tb_transaction_master_billing",
    "currencyID",
    $companyID,
    $currentCurrencyID
);
```

**Uso:** Edición de registros, mantener valor actual aunque esté inactivo

---

##### `getCatalogAllItemByNameCatalogo($name, $companyID)`
Obtiene items por nombre de catálogo.

**Parámetros:**
- `$name` (string): Nombre del catálogo
- `$companyID` (int): ID de empresa

**Retorna:** Array de items

**Ejemplo:**
```php
$objListPaymentMethod = $core_web_catalog->getCatalogAllItemByNameCatalogo(
    "tb_transaction_master_billing.paymentMethodID",
    $companyID
);
```

**Uso:** Obtener catálogos por nombre en lugar de tabla/campo

---

## Contabilidad

### `core_web_accounting`
Funciones contables del sistema.

**Ubicación:** `app/Libraries/core_web_accounting.php`

#### Métodos Principales

##### `cycleIsCloseByID($companyID, $cycleID)`
Verifica si un ciclo contable está cerrado.

**Parámetros:**
- `$companyID` (int): ID de empresa
- `$cycleID` (int): ID del ciclo

**Retorna:** bool

**Ejemplo:**
```php
if($core_web_accounting->cycleIsCloseByID($companyID, $cycleID)) {
    throw new \Exception("EL CICLO CONTABLE ESTÁ CERRADO");
}
```

**Uso:** Validar antes de crear/modificar transacciones

---

##### `cycleIsEmptyByID($companyID, $cycleID)`
Verifica si un ciclo está vacío (sin transacciones).

**Parámetros:**
- `$companyID` (int): ID de empresa
- `$cycleID` (int): ID del ciclo

**Retorna:** bool

**Uso:** Validar antes de eliminar ciclos

---

##### `cycleIsCloseByDate($companyID, $dateOn)`
Verifica si el ciclo de una fecha está cerrado.

**Parámetros:**
- `$companyID` (int): ID de empresa
- `$dateOn` (string): Fecha a validar

**Retorna:** bool

**Ejemplo:**
```php
if($core_web_accounting->cycleIsCloseByDate($companyID, '2024-03-15')) {
    throw new \Exception("NO SE PUEDE OPERAR EN ESTA FECHA");
}
```

**Uso:** Validar fechas de transacciones

---

## Amortización

### `core_web_amortization`
Gestiona amortizaciones de créditos.

**Ubicación:** `app/Libraries/core_web_amortization.php`

#### Métodos Principales

##### `cancelDocument($companyID, $customerCreditDocumentID, $amount)`
Cancela un documento de crédito.

**Parámetros:**
- `$companyID` (int): ID de empresa
- `$customerCreditDocumentID` (int): ID del documento
- `$amount` (float): Monto a cancelar

**Retorna:** void

**Ejemplo:**
```php
$core_web_amortization->cancelDocument($companyID, $documentID, 1500.00);
```

**Uso:** Aplicar pagos a créditos

---

##### `shareCapital($companyID, $customerCreditDocumentID, $amount)`
Aplica capital compartido.

**Parámetros:**
- `$companyID` (int): ID de empresa
- `$customerCreditDocumentID` (int): ID del documento
- `$amount` (float): Monto

**Retorna:** void

**Uso:** Distribución de pagos en créditos

---

##### `changeStatus($companyID, $customerCreditDocumentID)`
Cambia el estado de un documento de crédito.

**Parámetros:**
- `$companyID` (int): ID de empresa
- `$customerCreditDocumentID` (int): ID del documento

**Retorna:** void

**Uso:** Actualizar estados de créditos

---

## Inventario

### `core_web_inventory`
Gestiona operaciones de inventario.

**Ubicación:** `app/Libraries/core_web_inventory.php`

#### Métodos Principales (Comunes)

##### `validateWarehouseAndItem($companyID, $warehouseID, $itemID)`
Valida que exista relación bodega-item.

**Uso:** Validar antes de movimientos de inventario

##### `getQuantityItemInWarehouse($companyID, $warehouseID, $itemID)`
Obtiene cantidad disponible de un item en bodega.

**Retorna:** float

**Uso:** Validar existencias antes de ventas/salidas

##### `updateInventory($companyID, $warehouseID, $itemID, $quantity, $cost)`
Actualiza inventario (entrada/salida).

**Uso:** Registrar movimientos de inventario

---


## Herramientas y Utilidades

### `core_web_tools`
Herramientas generales del sistema.

**Ubicación:** `app/Libraries/core_web_tools.php`

#### Métodos Principales

##### `formatMessageError($message)`
Formatea mensajes de error para mostrar.

**Parámetros:**
- `$message` (string|array): Mensaje o array de mensajes

**Retorna:** String formateado

**Ejemplo:**
```php
$errorMsg = $core_web_tools->formatMessageError($ex->getMessage());
```

**Uso:** Formatear errores antes de mostrar al usuario

---

##### `formatParameter($filter)`
Formatea parámetros de filtros.

**Parámetros:**
- `$filter` (string): String con formato especial

**Retorna:** Array asociativo

**Ejemplo:**
```php
// Input: "{startDate}|2024-01-01{}|{endDate}|2024-12-31"
$params = $core_web_tools->formatParameter($filter);
// Output: ['{startDate}' => '2024-01-01', '{endDate}' => '2024-12-31']
```

**Uso:** Parsear filtros de reportes

---

##### `getComponentIDBy_ComponentName($componentName)`
Obtiene datos de un componente por nombre.

**Parámetros:**
- `$componentName` (string): Nombre del componente

**Retorna:** Objeto componente

**Ejemplo:**
```php
$objComponent = $core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
```

**Uso:** Obtener IDs y datos de componentes

---

### `core_web_view`
Gestiona vistas y renderizado.

**Ubicación:** `app/Libraries/core_web_view.php`

#### Funcionalidades
- Renderizado de vistas con datos
- Gestión de layouts
- Carga de assets (CSS, JS)

---

### `core_web_menu`
Gestiona menús del sistema.

**Ubicación:** `app/Libraries/core_web_menu.php`

#### Funcionalidades
- Construcción de menús según permisos
- Menús dinámicos por rol
- Breadcrumbs

---

### `core_web_notification`
Sistema de notificaciones.

**Ubicación:** `app/Libraries/core_web_notification.php`

#### Funcionalidades
- Notificaciones flash
- Alertas de sistema
- Mensajes de éxito/error

---

### `core_web_counter`
Gestiona contadores y numeración.

**Ubicación:** `app/Libraries/core_web_counter.php`

#### Funcionalidades
- Generación de números de documento
- Contadores por tipo de transacción
- Numeración automática

**Uso común:**
```php
$documentNumber = $core_web_counter->getNextNumber($companyID, $transactionID);
```

---

### `core_web_currency`
Gestiona monedas y tipos de cambio.

**Ubicación:** `app/Libraries/core_web_currency.php`

#### Funcionalidades
- Conversión de monedas
- Tipos de cambio
- Redondeo según moneda

---

### `core_web_convertion`
Conversiones de unidades de medida.

**Ubicación:** `app/Libraries/core_web_convertion.php`

#### Funcionalidades
- Conversión entre unidades
- Factores de conversión
- Unidades de medida

---

### `core_web_concept`
Gestiona conceptos contables.

**Ubicación:** `app/Libraries/core_web_concept.php`

#### Métodos Principales

##### `otherinput($companyID, $transactionID, $transactionMasterID)`
Procesa conceptos de otras entradas.

##### `inputunpost($companyID, $transactionID, $transactionMasterID)`
Procesa conceptos de entradas sin contabilizar.

##### `otheroutput($companyID, $transactionID, $transactionMasterID)`
Procesa conceptos de otras salidas.

**Uso:** Aplicar conceptos contables a transacciones

---

### `core_web_auditoria`
Sistema de auditoría.

**Ubicación:** `app/Libraries/core_web_auditoria.php`

#### Métodos Principales

##### `setAuditCreated(&$obj, $dataUser, $request)`
Establece datos de auditoría en creación.

**Parámetros:**
- `$obj` (object): Objeto a auditar (por referencia)
- `$dataUser` (array): Datos del usuario
- `$request`: Objeto request

**Ejemplo:**
```php
$core_web_auditoria->setAuditCreated($obj, $dataSession["user"], $this->request);
// Agrega: createdBy, createdOn, createdIn, createdAt
```

**Uso:** Registrar quién y cuándo creó un registro

---

##### `setAuditCreatedAdmin(&$obj, $request)`
Auditoría para usuario admin.

**Uso:** Registros creados por administrador

---

##### `getAuditDetail($companyID, $id, $tableName)`
Obtiene historial de auditoría de un registro.

**Parámetros:**
- `$companyID` (int): ID de empresa
- `$id` (int): ID del registro
- `$tableName` (string): Nombre de la tabla

**Retorna:** Array con historial de cambios

**Uso:** Ver historial de modificaciones

---

### `core_web_error`
Gestión de errores.

**Ubicación:** `app/Libraries/core_web_error.php`

#### Funcionalidades
- Logging de errores
- Notificación de errores críticos
- Formateo de stack traces

---

### `core_web_javascript`
Generación de código JavaScript.

**Ubicación:** `app/Libraries/core_web_javascript.php`

#### Funcionalidades
- Generar variables JS desde PHP
- Pasar datos a vistas JS
- Configuraciones dinámicas

---

### `core_web_conversation`
Gestión de conversaciones (chat/mensajería).

**Ubicación:** `app/Libraries/core_web_conversation.php`

#### Funcionalidades
- Gestión de conversaciones
- Mensajes entre usuarios
- Notificaciones de mensajes

---

### `core_web_whatsap`
Integración con WhatsApp.

**Ubicación:** `app/Libraries/core_web_whatsap.php`

#### Funcionalidades
- Envío de mensajes WhatsApp
- Notificaciones por WhatsApp
- Integración con API de WhatsApp

---

### `core_web_google`
Integración con servicios de Google.

**Ubicación:** `app/Libraries/core_web_google.php`

#### Funcionalidades
- Google Maps
- Google Calendar
- Autenticación Google

---

## Librerías Especializadas

### `core_financial`
Cálculos financieros.

**Ubicación:** `app/Libraries/core_financial.php`

#### Métodos

##### `getAmoritizationSimple($amount, $term, $interes, $share, $dateFirstPay)`
Calcula tabla de amortización simple.

**Parámetros:**
- `$amount` (float): Monto del préstamo
- `$term` (int): Plazo en períodos
- `$interes` (float): Tasa de interés
- `$share` (float): Cuota
- `$dateFirstPay` (string): Fecha primer pago

**Retorna:** Array con tabla de amortización

**Uso:** Generar tablas de pago de créditos

---

### `core_jwt`
Manejo de JSON Web Tokens.

**Ubicación:** `app/Libraries/core_jwt.php`

#### Métodos

##### `generateJWT($expires = 3600)`
Genera un JWT.

**Parámetros:**
- `$expires` (int): Tiempo de expiración en segundos

**Retorna:** String con JWT

**Uso:** Autenticación API, tokens de acceso

---

### `core_mysql_dump`
Respaldo de base de datos.

**Ubicación:** `app/Libraries/core_mysql_dump.php`

#### Funcionalidades
- Backup de base de datos
- Exportación de tablas
- Restauración de backups

---

### `core_web_transaction_master_detail`
Gestión de transacciones maestro-detalle.

**Ubicación:** `app/Libraries/core_web_transaction_master_detail.php`

#### Funcionalidades
- Validación de transacciones complejas
- Cálculos de totales
- Validación de detalles

---

## Librerías de Terceros (Carpetas)

### `core_web_barcode`
Generación de códigos de barras.

**Ubicación:** `app/Libraries/core_web_barcode/`

#### Uso
```php
$barcode = new core_web_barcode();
$barcode->generate($filepath, $text, $size, $orientation, $code_type);
```

**Tipos soportados:** code128, code39, ean13, etc.

---

### `core_web_qr`
Generación de códigos QR.

**Ubicación:** `app/Libraries/core_web_qr/`

#### Uso
```php
$qr = new core_web_qr();
$qr->generate($data, $filepath);
```

---

### `core_web_csv`
Manejo de archivos CSV.

**Ubicación:** `app/Libraries/core_web_csv/`

#### Funcionalidades
- Lectura de CSV
- Escritura de CSV
- Parseo de datos

---

### `core_web_printer_direct`
Impresión directa.

**Ubicación:** `app/Libraries/core_web_printer_direct/`

#### Funcionalidades
- Impresión en impresoras térmicas
- Comandos ESC/POS
- Impresión de tickets

---

### `core_web_pagadito`
Integración con pasarela de pago Pagadito.

**Ubicación:** `app/Libraries/core_web_pagadito/`

#### Funcionalidades
- Procesamiento de pagos
- Integración con Pagadito
- Callbacks de pago

---

### `core_web_elfinder`
Gestor de archivos.

**Ubicación:** `app/Libraries/core_web_elfinder/`

#### Funcionalidades
- Explorador de archivos
- Upload de archivos
- Gestión de carpetas

---

## Patrones de Uso Comunes

### Patrón de Controlador Completo

```php
function edit() {
    try {
        // 1. AUTENTICACIÓN
        if(!$this->core_web_authentication->isAuthenticated())
            throw new \Exception(USER_NOT_AUTENTICATED);
        $dataSession = $this->session->get();
        
        // 2. PERMISOS
        if(APP_NEED_AUTHENTICATION == true) {
            $permited = $this->core_web_permission->urlPermited(
                get_class($this), "index", URL_SUFFIX,
                $dataSession["menuTop"], $dataSession["menuLeft"],
                $dataSession["menuBodyReport"], $dataSession["menuBodyTop"],
                $dataSession["menuHiddenPopup"]
            );
            
            if(!$permited)
                throw new \Exception(NOT_ACCESS_CONTROL);
            
            $resultPermission = $this->core_web_permission->urlPermissionCmd(
                get_class($this), "edit", URL_SUFFIX, $dataSession,
                $dataSession["menuTop"], $dataSession["menuLeft"],
                $dataSession["menuBodyReport"], $dataSession["menuBodyTop"],
                $dataSession["menuHiddenPopup"]
            );
            
            if($resultPermission == PERMISSION_NONE)
                throw new \Exception(NOT_ALL_EDIT);
        }
        
        // 3. OBTENER PARÁMETROS
        $companyID = $dataSession["user"]->companyID;
        $branchID = $dataSession["user"]->branchID;
        $roleID = $dataSession["role"]->roleID;
        
        // 4. VALIDAR WORKFLOW
        $canEdit = $this->core_web_workflow->validateWorkflowStage(
            "tb_transaction_master",
            "statusID",
            $statusID,
            "EDIT",
            $companyID,
            $branchID,
            $roleID
        );
        
        if(!$canEdit)
            throw new \Exception("NO SE PUEDE EDITAR EN ESTE ESTADO");
        
        // 5. OBTENER PARÁMETROS DE CONFIGURACIÓN
        $objParameterDays = $this->core_web_parameter->getParameter(
            "INVOICE_BILLING_QUANTITY_DAYS",
            $companyID
        );
        
        // 6. LÓGICA DE NEGOCIO
        // ...
        
    } catch(\Exception $ex) {
        return $this->response->setJSON(array(
            'error' => true,
            'message' => $ex->getLine()." ".$ex->getMessage()
        ));
    }
}
```

---

## Resumen por Funcionalidad

### Para Autenticación y Seguridad
- `core_web_authentication` - Login, sesiones
- `core_web_permission` - Permisos, licencias

### Para Transacciones
- `core_web_transaction` - Gestión de transacciones
- `core_web_transaction_master_detail` - Maestro-detalle
- `core_web_concept` - Conceptos contables

### Para Configuración
- `core_web_parameter` - Parámetros de empresa
- `core_web_catalog` - Catálogos del sistema

### Para Flujos de Trabajo
- `core_web_workflow` - Estados y transiciones

### Para Contabilidad
- `core_web_accounting` - Ciclos contables
- `core_financial` - Cálculos financieros

### Para Inventario
- `core_web_inventory` - Movimientos de inventario

### Para Utilidades
- `core_web_tools` - Herramientas generales
- `core_web_error` - Manejo de errores
- `core_web_auditoria` - Auditoría

### Para Integración
- `core_web_whatsap` - WhatsApp
- `core_web_google` - Google Services
- `core_web_pagadito` - Pagos

### Para Generación
- `core_web_barcode` - Códigos de barras
- `core_web_qr` - Códigos QR
- `core_web_printer_direct` - Impresión

---

## Mejores Prácticas

1. **Siempre validar autenticación** con `core_web_authentication->isAuthenticated()`
2. **Verificar permisos** con `core_web_permission->urlPermited()` y `urlPermissionCmd()`
3. **Validar workflow** con `core_web_workflow->validateWorkflowStage()`
4. **Usar parámetros** en lugar de valores hardcodeados con `core_web_parameter`
5. **Registrar auditoría** con `core_web_auditoria->setAuditCreated()`
6. **Validar ciclos contables** con `core_web_accounting->cycleIsCloseByDate()`
7. **Obtener transactionID** con `core_web_transaction->getTransactionID()`
8. **Usar catálogos** con `core_web_catalog->getCatalogAllItem()`

---

**Proyecto:** POSME (Point of Sale Management Enterprise)  
**Framework:** CodeIgniter 4  
**Ubicación:** `app/Libraries/`
