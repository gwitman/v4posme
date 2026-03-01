---
title: Guía para Modelos PHP
inclusion: fileMatch
fileMatchPattern: "app/Models/*.php"
---

# Instrucciones para Modelos

Cuando trabajes en archivos de `app/Models/`:

## Estructura Obligatoria

```php
<?php
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Nombre_Model extends Model {
    
    function __construct() {
        parent::__construct();
    }
    
    // Soft delete
    function delete_app_posme($companyID, $id) {
        $db = db_connect();
        $builder = $db->table("tb_tabla");
        $data["isActive"] = 0;
        
        $builder->where("companyID", $companyID);
        $builder->where("id", $id);
        return $builder->update($data);
    }
    
    // Actualizar
    function update_app_posme($companyID, $id, $data) {
        $db = db_connect();
        $builder = $db->table("tb_tabla");
        
        $builder->where("companyID", $companyID);
        $builder->where("id", $id);
        return $builder->update($data);
    }
    
    // Insertar
    function insert_app_posme($data) {
        $db = db_connect();
        $builder = $db->table("tb_tabla");
        $result = $builder->insert($data);
        return $db->insertID();
    }
    
    // Obtener por PK
    function get_rowByPK($companyID, $id) {
        $db = db_connect();
        
        $sql = "";
        $sql = $sql.sprintf("SELECT * ");
        $sql = $sql.sprintf("FROM tb_tabla ");
        $sql = $sql.sprintf("WHERE companyID = %d ", $companyID);
        $sql = $sql.sprintf("AND id = %d ", $id);
        $sql = $sql.sprintf("AND isActive = 1");
        
        return $db->query($sql)->getRow();
    }
}
```

## Reglas

1. Sufijo `_Model` obligatorio
2. Siempre usar `db_connect()`
3. Soft delete con `isActive = 0`
4. Claves compuestas: `companyID` + ID específico
5. Siempre filtrar por `isActive = 1` en consultas
6. Usar sprintf para SQL directo
7. Usar Query Builder para operaciones simples

## Consulta

- `guia-sintaxis-estandares.md` - Convenciones
