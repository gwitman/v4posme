# Guía de Sintaxis y Estándares de Programación
## Proyecto POSME - CodeIgniter 4

---

## 1. Estructura de Archivos y Organización

### 1.1 Estructura de Directorios
```
app/
├── Config/          # Archivos de configuración
├── Controllers/     # Controladores de la aplicación
├── Models/          # Modelos de datos
├── Libraries/       # Librerías personalizadas
├── Helpers/         # Funciones auxiliares
├── Database/        # Migraciones y seeds
├── Language/        # Archivos de idioma
└── Filters/         # Filtros de peticiones
```

### 1.2 Convenciones de Nombres de Archivos

**Controladores:**
- Prefijo por módulo: `app_[modulo]_[funcionalidad].php`
- Ejemplos: `app_accounting_account.php`, `app_inventory_item.php`
- Controladores core: `core_[funcionalidad].php`

**Modelos:**
- Sufijo obligatorio: `_Model`
- PascalCase: `Account_Model.php`, `Customer_Model.php`
- Ubicación: `app/Models/` o `app/Models/Core/`

**Librerías:**
- Prefijo: `core_web_[funcionalidad].php`
- Ejemplos: `core_web_authentication.php`, `core_web_permission.php`

**Helpers:**
- Sufijo: `_helper.php`
- Ejemplo: `web_tools_helper.php`

---

## 2. Convenciones de Código

### 2.1 Namespaces
```php
<?php
//posme:2023-02-27
namespace App\Controllers;

class app_accounting_account extends _BaseController {
    // código
}
```

**Reglas:**
- Siempre incluir comentario de fecha: `//posme:YYYY-MM-DD`
- Namespace según ubicación: `App\Controllers`, `App\Models`, `App\Libraries`
- PSR-4 compliant

### 2.2 Nombres de Variables y Métodos

**Variables:**
- camelCase para variables locales: `$dataSession`, `$companyID`, `$accountNumber`
- snake_case para campos de base de datos: `company_id`, `account_number`, `is_active`

**Métodos:**
- camelCase: `isValidAccountNumber()`, `getUserData()`
- Prefijos para operaciones de BD:
  - `get_`: Consultas SELECT
  - `insert_`: Operaciones INSERT
  - `update_`: Operaciones UPDATE
  - `delete_`: Operaciones DELETE (soft delete)

**Ejemplos:**
```php
function get_rowByPK($companyID, $accountID)
function insert_app_posme($data)
function update_app_posme($companyID, $accountID, $data)
function delete_app_posme($companyID, $accountID)
function get_countAccount($companyID)
function getByAccountNumber($accountNumber, $companyID)
```

### 2.3 Constantes
```php
// Todas en MAYÚSCULAS con guiones bajos
USER_NOT_AUTENTICATED
NOT_ACCESS_CONTROL
PERMISSION_NONE
PERMISSION_ME
APP_NEED_AUTHENTICATION
SUCCESS
```

---

## 3. Patrones de Programación

### 3.1 Estructura de Controladores

**Patrón estándar para métodos de controlador:**
```php
function edit() {
    try {
        // 1. AUTENTICACIÓN
        if(!$this->core_web_authentication->isAuthenticated())
            throw new \Exception(USER_NOT_AUTENTICATED);
        $dataSession = $this->session->get();
        
        // 2. PERMISO SOBRE LA FUNCIÓN
        if(APP_NEED_AUTHENTICATION == true) {
            $permited = false;
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
        $companyID = helper_SegmentsValue($this->uri->getSegments(), "companyID");
        $accountID = helper_SegmentsValue($this->uri->getSegments(), "accountID");
        
        // 4. VALIDAR PARÁMETROS
        if(!$companyID || !$accountID) {
            $this->response->redirect(base_url()."/".get_class($this).'/add');
        }
        
        // 5. LÓGICA DE NEGOCIO
        // ... código específico ...
        
        // 6. RETORNAR RESPUESTA
        return $this->response->setJSON(array(
            'error'   => false,
            'message' => SUCCESS
        ));
        
    } catch(\Exception $ex) {
        return $this->response->setJSON(array(
            'error'   => true,
            'message' => $ex->getLine()." ".$ex->getMessage()
        ));
    }
}
```

### 3.2 Estructura de Modelos

**Patrón estándar para modelos:**
```php
<?php
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Account_Model extends Model {
    
    function __construct() {
        parent::__construct();
    }
    
    // Método de eliminación (soft delete)
    function delete_app_posme($companyID, $accountID) {
        $db = db_connect();
        $builder = $db->table("tb_account");
        $data["isActive"] = 0;
        
        $builder->where("companyID", $companyID);
        $builder->where("accountID", $accountID);
        return $builder->update($data);
    }
    
    // Método de actualización
    function update_app_posme($companyID, $accountID, $data) {
        $db = db_connect();
        $builder = $db->table("tb_account");
        
        $builder->where("companyID", $companyID);
        $builder->where("accountID", $accountID);
        return $builder->update($data);
    }
    
    // Método de inserción
    function insert_app_posme($data) {
        $db = db_connect();
        $builder = $db->table("tb_account");
        $result = $builder->insert($data);
        return $db->insertID();
    }
    
    // Método de consulta por clave primaria
    function get_rowByPK($companyID, $accountID) {
        $db = db_connect();
        
        $sql = "";
        $sql = $sql.sprintf("SELECT * ");
        $sql = $sql.sprintf("FROM tb_account ");
        $sql = $sql.sprintf("WHERE companyID = %d ", $companyID);
        $sql = $sql.sprintf("AND accountID = %d ", $accountID);
        $sql = $sql.sprintf("AND isActive = 1");
        
        return $db->query($sql)->getRow();
    }
}
```

### 3.3 Interacción con Base de Datos

**Patrón Query Builder:**
```php
$db = db_connect();
$builder = $db->table("tb_account");
$builder->where("companyID", $companyID);
$builder->where("isActive", 1);
$result = $builder->get()->getResult();
```

**Patrón SQL Directo:**
```php
$db = db_connect();
$sql = "";
$sql = $sql.sprintf("SELECT * ");
$sql = $sql.sprintf("FROM tb_account ");
$sql = $sql.sprintf("WHERE companyID = %d ", $companyID);
$sql = $sql.sprintf("AND accountID = %d ", $accountID);
$sql = $sql.sprintf("AND isActive = 1");

return $db->query($sql)->getRow();
```

**Stored Procedures:**
```php
$query = "CALL pr_accounting_checkaccount_to_delete(?,?,?,?,?,@resultProcessMessage,@resultProcessCode);";
$resultProcess = $this->Bd_Model->executeRender(
    $query, [$companyID, $branchID, $loginID, $accountID, $app]
);

$query = "SELECT @resultProcessMessage as message, @resultProcessCode as codigo;";
$resultProcess = $this->Bd_Model->executeRender($query, null);
$resultProcess = $resultProcess[0];

if($resultProcess["codigo"] == 0)
    throw new \Exception($resultProcess["message"]);
```

---

## 4. Manejo de Errores y Excepciones

### 4.1 Try-Catch Obligatorio
```php
try {
    // Lógica de negocio
    
} catch(\Exception $ex) {
    return $this->response->setJSON(array(
        'error'   => true,
        'message' => $ex->getLine()." ".$ex->getMessage()
    ));
}
```

### 4.2 Validación de Datos
```php
// Validación de formularios
$this->validation->setRule("txtAccountNumber", "Codigo", "required");
$this->validation->setRule("txtAccountLevelID", "Clase", "required");
$this->validation->setRule("txtName", "Nombre", "required");

if($this->validation->withRequest($this->request)->run() == true) {
    // Procesar datos
}
```

### 4.3 Validación de Parámetros
```php
if(!$companyID || !$accountID) {
    throw new \Exception(NOT_PARAMETER);
}
```

---

## 5. Autenticación y Autorización

### 5.1 Verificación de Autenticación
```php
if(!$this->core_web_authentication->isAuthenticated())
    throw new \Exception(USER_NOT_AUTENTICATED);
    
$dataSession = $this->session->get();
```

### 5.2 Verificación de Permisos
```php
if(APP_NEED_AUTHENTICATION == true) {
    $permited = $this->core_web_permission->urlPermited(
        get_class($this), "index", URL_SUFFIX,
        $dataSession["menuTop"], $dataSession["menuLeft"],
        $dataSession["menuBodyReport"], $dataSession["menuBodyTop"],
        $dataSession["menuHiddenPopup"]
    );
    
    if(!$permited)
        throw new \Exception(NOT_ACCESS_CONTROL);
}
```

### 5.3 Permisos por Acción
```php
$resultPermission = $this->core_web_permission->urlPermissionCmd(
    get_class($this), "edit", URL_SUFFIX, $dataSession,
    $dataSession["menuTop"], $dataSession["menuLeft"],
    $dataSession["menuBodyReport"], $dataSession["menuBodyTop"],
    $dataSession["menuHiddenPopup"]
);

if($resultPermission == PERMISSION_NONE)
    throw new \Exception(NOT_ALL_EDIT);

if($resultPermission == PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))
    throw new \Exception(NOT_DELETE);
```

---

## 6. Helpers y Funciones Auxiliares

### 6.1 Funciones de URI
```php
// Obtener valor de segmento por clave
$companyID = helper_SegmentsValue($this->uri->getSegments(), "companyID");

// Obtener valor por índice
$method = helper_SegmentsByIndex($this->uri->getSegments(), 1, $method);

// Convertir segmentos a array clave-valor
$params = helper_SegmentsKeyValue($this->uri->getSegments());
```

### 6.2 Funciones de Request
```php
// Obtener valor POST con valor por defecto
$value = helper_RequestGetValue($this->request->getPost("field"), $default);

// Obtener valor de objeto con valor por defecto
$value = helper_RequestGetValueObjet($obj, "field", $default);
```

### 6.3 Generación de GUID
```php
$guid = GUIDv4(); // Genera un GUID v4
$guid = GUIDv4(false); // Con llaves {}
```

---

## 7. Respuestas JSON

### 7.1 Respuesta Exitosa
```php
return $this->response->setJSON(array(
    'error'   => false,
    'message' => SUCCESS
));
```

### 7.2 Respuesta con Error
```php
return $this->response->setJSON(array(
    'error'   => true,
    'message' => $ex->getLine()." ".$ex->getMessage()
));
```

### 7.3 Respuesta con Datos
```php
return $this->response->setJSON(array(
    'error' => false,
    'message' => SUCCESS,
    'data' => $result
));
```

---

## 8. Soft Delete Pattern

**Siempre usar soft delete con campo isActive:**
```php
function delete_app_posme($companyID, $accountID) {
    $db = db_connect();
    $builder = $db->table("tb_account");
    $data["isActive"] = 0;
    
    $builder->where("companyID", $companyID);
    $builder->where("accountID", $accountID);
    return $builder->update($data);
}
```

**En consultas siempre filtrar por isActive:**
```php
$sql = $sql.sprintf("WHERE isActive = 1");
```

---

## 9. Claves Compuestas

**El sistema usa claves compuestas en la mayoría de tablas:**
```php
// Patrón estándar: companyID + ID específico
function get_rowByPK($companyID, $accountID)
function update_app_posme($companyID, $accountID, $data)
function delete_app_posme($companyID, $accountID)

// Patrón con branch: companyID + branchID + ID específico
function get_rowByPK($companyID, $branchID, $entityID)
```

---

## 10. Comentarios y Documentación

### 10.1 Comentarios de Sección
```php
// 1. AUTENTICACIÓN
// código...

// 2. PERMISO SOBRE LA FUNCIÓN
// código...

// 3. OBTENER PARÁMETROS
// código...
```

### 10.2 Comentarios Inline
```php
$companyID = /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "companyID"); //--finuri
$value = /*inicio get post*/ $this->request->getPost("field");
```

### 10.3 Separadores de Sección
```php
////////////////////////////////////////
////////////////////////////////////////
////////////////////////////////////////
```

---

## 11. Inicialización en BaseController

**Librerías disponibles globalmente:**
```php
protected $core_web_authentication;
protected $core_web_permission;
protected $core_web_parameter;
protected $core_web_notification;
protected $core_web_tools;
protected $core_web_transaction;
protected $core_web_view;
protected $core_web_workflow;
// ... etc
```

**Helpers cargados automáticamente:**
```php
protected $helpers = [
    'url', 'form', 'web_tools', 'cookie', 'text', 'path', 'language'
];
```

---

## 12. Mejores Prácticas

### 12.1 Seguridad
- Siempre validar autenticación antes de cualquier operación
- Verificar permisos específicos por acción (view, edit, delete)
- Usar prepared statements o query builder para prevenir SQL injection
- Validar todos los inputs del usuario

### 12.2 Base de Datos
- Usar soft delete (isActive = 0) en lugar de DELETE físico
- Siempre incluir companyID en consultas multi-tenant
- Usar transacciones para operaciones complejas
- Filtrar por isActive = 1 en todas las consultas

### 12.3 Código Limpio
- Un método, una responsabilidad
- Nombres descriptivos para variables y métodos
- Evitar código duplicado, usar librerías compartidas
- Comentar lógica compleja de negocio

### 12.4 Manejo de Errores
- Siempre usar try-catch en controladores
- Mensajes de error descriptivos
- Incluir línea de error en desarrollo: `$ex->getLine()." ".$ex->getMessage()`
- Retornar JSON consistente con flag 'error'

---

## 13. Convenciones de Base de Datos

### 13.1 Nombres de Tablas
- Prefijo: `tb_`
- snake_case: `tb_account`, `tb_customer_credit`, `tb_journal_entry`

### 13.2 Campos Comunes
```php
companyID       // ID de empresa (multi-tenant)
branchID        // ID de sucursal
isActive        // Soft delete flag (0 o 1)
createdBy       // Usuario que creó
createdOn       // Fecha de creación
createdIn       // IP de creación
createdAt       // Timestamp de creación
statusID        // Estado del registro
```

### 13.3 Stored Procedures
- Prefijo: `pr_`
- Ejemplo: `pr_accounting_checkaccount_to_delete`
- Usar parámetros OUT para mensajes: `@resultProcessMessage`, `@resultProcessCode`

---

## 14. Estructura de Librerías

**Patrón estándar para librerías:**
```php
<?php
//posme:2023-02-27
namespace App\Libraries;

use App\Models\Core\User_Model;
use App\Models\Core\Company_Model;
// ... otros imports

class core_web_authentication {
    
    private $CI;
    
    public function __construct() {
        // Inicialización
    }
    
    function isAuthenticated() {
        // Lógica de autenticación
    }
    
    function get_UserBy_PasswordAndNickname($nickname, $password) {
        // Lógica de obtención de usuario
    }
}
```

---

## 15. Redirecciones

```php
// Redirección a otra acción del mismo controlador
$this->response->redirect(base_url()."/".get_class($this).'/add');

// Redirección a otro controlador
$this->response->redirect(base_url()."/".'app_accounting_account/index');
```

---

## Resumen de Convenciones Clave

| Elemento | Convención | Ejemplo |
|----------|-----------|---------|
| Controladores | app_[modulo]_[funcionalidad] | app_accounting_account.php |
| Modelos | PascalCase_Model | Account_Model.php |
| Librerías | core_web_[funcionalidad] | core_web_authentication.php |
| Métodos | camelCase | isValidAccountNumber() |
| Variables | camelCase | $dataSession, $companyID |
| Constantes | UPPER_SNAKE_CASE | USER_NOT_AUTENTICATED |
| Tablas | tb_snake_case | tb_account, tb_customer_credit |
| Campos BD | snake_case | company_id, is_active |
| Namespaces | App\[Carpeta] | App\Controllers, App\Models |

---

**Fecha de creación:** 2023-02-27  
**Framework:** CodeIgniter 4  
**Proyecto:** POSME (Point of Sale Management Enterprise)
