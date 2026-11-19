<?php
//posme:2026-11-16
namespace App\Models;
use CodeIgniter\Model;

class Advertising_Variable_Model extends Model {

    /** Vista base de clientes para campañas */
    const BASE_VIEW = "vw_cxc_advertising_customer";

    function __construct(){
        parent::__construct();
    }

    /**
     * Catálogo de variables activas de campaña de la compañía.
     * @param int $companyID
     * @return array arreglo de objetos {advertisingVariableID,name,label,viewName,fieldName,keyField,dataType,sequence}
     */
    function get_rowByCompany($companyID){
        $db  = db_connect();
        $sql = "";
        $sql = $sql.sprintf("SELECT ");
        $sql = $sql.sprintf("  v.advertisingVariableID, ");
        $sql = $sql.sprintf("  v.name, ");
        $sql = $sql.sprintf("  v.label, ");
        $sql = $sql.sprintf("  v.viewName, ");
        $sql = $sql.sprintf("  v.fieldName, ");
        $sql = $sql.sprintf("  v.keyField, ");
        $sql = $sql.sprintf("  v.dataType, ");
        $sql = $sql.sprintf("  v.sequence ");
        $sql = $sql.sprintf("FROM tb_advertising_variable v ");
        $sql = $sql.sprintf("WHERE v.companyID = %d ", $companyID);
        $sql = $sql.sprintf("AND v.isActive = 1 ");
        $sql = $sql.sprintf("ORDER BY v.sequence ASC, v.advertisingVariableID ASC");
        return $db->query($sql)->getResult();
    }

    /**
     * Teléfonos de clientes activos que NO aceptan promociones por WhatsApp (allowWhatsappPromotions = 0 o NULL).
     * Incluye el teléfono del cliente y todos los de tb_entity_phone: la campaña no debe escribir a ninguno,
     * venga el número del Excel, de los contactos del teléfono o de la base de datos.
     * @param int $companyID
     * @return array objetos {telefono} sin normalizar (el controlador los deja en el formato de envío)
     */
    function get_phoneOptOutByCompany($companyID){
        $db  = db_connect();
        $sql = "";
        $sql = $sql.sprintf("SELECT ");
        $sql = $sql.sprintf("  c.phoneNumber AS telefono ");
        $sql = $sql.sprintf("FROM tb_customer c ");
        $sql = $sql.sprintf("WHERE c.companyID = %d ", $companyID);
        $sql = $sql.sprintf("AND c.isActive = 1 ");
        $sql = $sql.sprintf("AND IFNULL(c.allowWhatsappPromotions + 0, 0) = 0 ");
        // LENGTH evita comparar texto con literales (mezcla de collations)
        $sql = $sql.sprintf("AND LENGTH(TRIM(IFNULL(c.phoneNumber, ''))) > 0 ");
        $listCustomer = $db->query($sql)->getResult();

        // Consulta aparte (no UNION): tb_customer.phoneNumber y tb_entity_phone.number pueden tener collations distintas
        $sql = "";
        $sql = $sql.sprintf("SELECT ");
        $sql = $sql.sprintf("  ep.number AS telefono ");
        $sql = $sql.sprintf("FROM tb_customer c ");
        $sql = $sql.sprintf("INNER JOIN tb_entity_phone ep ON ");
        $sql = $sql.sprintf("  ep.entityID = c.entityID ");
        $sql = $sql.sprintf("WHERE c.companyID = %d ", $companyID);
        $sql = $sql.sprintf("AND c.isActive = 1 ");
        $sql = $sql.sprintf("AND IFNULL(c.allowWhatsappPromotions + 0, 0) = 0 ");
        $sql = $sql.sprintf("AND LENGTH(TRIM(IFNULL(ep.number, ''))) > 0 ");
        $listPhone = $db->query($sql)->getResult();

        return array_merge($listCustomer, $listPhone);
    }

    /**
     * Clientes destinatarios de una campaña con el valor de cada variable del catálogo.
     * Solo clientes con teléfono y que aceptaron promociones por WhatsApp (allowWhatsappPromotions = 1).
     * Las variables con identificadores inválidos o inexistentes en information_schema se descartan y se registran en el log.
     * Las vistas extra deben exponer companyID y devolver una fila por entidad (keyField = entityID del cliente).
     * @param int   $companyID
     * @param array $variables   arreglo devuelto por get_rowByCompany (nunca datos del POST)
     * @param bool  $onlyBalance solo clientes con saldo > 0
     * @param bool  $onlyMora    solo clientes con mora > 0
     * @param int   $categoryID  categoría del cliente (0 = todas)
     * @param int   $limit       máximo de filas
     * @param bool  $onlyAllowPromotions true = solo clientes con allowWhatsappPromotions = 1; false = también los que no aceptan
     * @return array objetos {entityID,customerNumber,nombre,telefono,moneda,var_<name>...}
     */
    function get_rowCustomerByAdvertising($companyID, $variables, $onlyBalance, $onlyMora, $categoryID, $limit, $onlyAllowPromotions = true){
        $db        = db_connect();
        $limit     = intval($limit) > 0 ? intval($limit) : 1000;
        $regex     = "/^[A-Za-z0-9_]+$/";
        $variables = is_array($variables) ? $variables : array();

        // 1) Validar sintaxis de identificadores y reunir las vistas a consultar
        $candidates = array();
        $viewNames  = array(self::BASE_VIEW => true);
        $usedNames  = array();
        foreach ($variables as $variable) {
            $variable  = (object) $variable;
            $name      = isset($variable->name) ? trim($variable->name) : "";
            $viewName  = isset($variable->viewName) ? trim($variable->viewName) : "";
            $fieldName = isset($variable->fieldName) ? trim($variable->fieldName) : "";
            $keyField  = isset($variable->keyField) && trim($variable->keyField) != "" ? trim($variable->keyField) : "entityID";

            if (
                !preg_match($regex, $name) ||
                !preg_match($regex, $viewName) ||
                !preg_match($regex, $fieldName) ||
                !preg_match($regex, $keyField)
            ) {
                log_message("error", "Advertising_Variable_Model: variable descartada por identificador inválido: ".json_encode($variable));
                continue;
            }
            if (isset($usedNames[strtolower($name)])) {
                log_message("error", "Advertising_Variable_Model: variable duplicada descartada: ".$name);
                continue;
            }
            $usedNames[strtolower($name)] = true;
            $viewNames[$viewName]         = true;
            $candidates[]                 = array(
                "name"      => $name,
                "viewName"  => $viewName,
                "fieldName" => $fieldName,
                "keyField"  => $keyField
            );
        }

        // 2) Columnas reales de las vistas (identificadores ya validados por regex)
        $columns = array();
        $inList  = "'".implode("','", array_keys($viewNames))."'";
        $sql     = "";
        $sql     = $sql.sprintf("SELECT c.TABLE_NAME AS tableName, c.COLUMN_NAME AS columnName ");
        $sql     = $sql.sprintf("FROM information_schema.COLUMNS c ");
        $sql     = $sql.sprintf("WHERE c.TABLE_SCHEMA = DATABASE() ");
        $sql     = $sql.sprintf("AND c.TABLE_NAME IN (%s)", $inList);
        $rows    = $db->query($sql)->getResult();
        foreach ($rows as $row) {
            $columns[strtolower($row->tableName)][strtolower($row->columnName)] = true;
        }

        // 3) Armar columnas y LEFT JOIN por cada vista extra (vista + keyField)
        $selectVars = "";
        $joins      = "";
        $aliases    = array();
        foreach ($candidates as $item) {
            $viewKey  = strtolower($item["viewName"]);
            $isBase   = ($viewKey == strtolower(self::BASE_VIEW));
            $colsView = isset($columns[$viewKey]) ? $columns[$viewKey] : array();

            if (!isset($colsView[strtolower($item["fieldName"])])) {
                log_message("error", "Advertising_Variable_Model: variable descartada, no existe ".$item["viewName"].".".$item["fieldName"]);
                continue;
            }

            if ($isBase) {
                $alias = "b";
            }
            else {
                if (!isset($colsView[strtolower($item["keyField"])]) || !isset($colsView["companyid"])) {
                    log_message("error", "Advertising_Variable_Model: variable descartada, la vista ".$item["viewName"]." no tiene ".$item["keyField"]." o companyID");
                    continue;
                }
                $joinKey = $viewKey."|".strtolower($item["keyField"]);
                if (!isset($aliases[$joinKey])) {
                    $alias             = "v".(count($aliases) + 1);
                    $aliases[$joinKey] = $alias;
                    $joins             = $joins.sprintf(
                        "LEFT JOIN `%s` %s ON %s.`%s` = b.entityID AND %s.companyID = b.companyID ",
                        $item["viewName"], $alias, $alias, $item["keyField"], $alias
                    );
                }
                $alias = $aliases[$joinKey];
            }

            $selectVars = $selectVars.sprintf(", %s.`%s` AS `var_%s` ", $alias, $item["fieldName"], $item["name"]);
        }

        // 4) Consulta final
        $sql = "";
        $sql = $sql.sprintf("SELECT ");
        $sql = $sql.sprintf("  b.entityID, ");
        $sql = $sql.sprintf("  b.customerNumber, ");
        $sql = $sql.sprintf("  b.nombre, ");
        $sql = $sql.sprintf("  b.telefono, ");
        $sql = $sql.sprintf("  b.moneda ");
        $sql = $sql.$selectVars;
        $sql = $sql.sprintf("FROM %s b ", self::BASE_VIEW);
        $sql = $sql.$joins;
        $sql = $sql.sprintf("WHERE b.companyID = %d ", $companyID);
        // LENGTH evita comparar texto: b.telefono (utf8mb4_bin) vs literal de la conexion (utf8mb3) da "Illegal mix of collations"
        $sql = $sql.sprintf("AND LENGTH(b.telefono) > 0 ");
        if ($onlyAllowPromotions) {
            $sql = $sql.sprintf("AND b.allowWhatsappPromotions = 1 ");
        }
        if ($onlyBalance) {
            $sql = $sql.sprintf("AND b.saldo > 0 ");
        }
        if ($onlyMora) {
            $sql = $sql.sprintf("AND b.mora > 0 ");
        }
        if (intval($categoryID) > 0) {
            $sql = $sql.sprintf("AND b.categoryID = %d ", $categoryID);
        }
        $sql = $sql.sprintf("ORDER BY b.nombre ASC, b.entityID ASC ");
        $sql = $sql.sprintf("LIMIT %d", $limit);
        return $db->query($sql)->getResult();
    }
}
