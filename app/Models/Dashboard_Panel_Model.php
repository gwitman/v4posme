<?php
//posme:2026-11-22
namespace App\Models;
use CodeIgniter\Model;

/**
 * Datos del panel principal (core_dashboards/index) para las compañías sin dashboard personalizado.
 * Todas las lecturas se hacen sobre las vistas ai_v_* de la BD, que filtran por la función
 * ai_company() (variable de sesión MySQL @ai_company_id). Por eso cada consulta fija primero
 * @ai_company_id en la misma conexión.
 */
class Dashboard_Panel_Model extends Model {

    function __construct(){
        parent::__construct();
    }

    /**
     * Devuelve la conexión con @ai_company_id fijado para que las vistas ai_v_* filtren la compañía.
     * @param int $companyID
     * @return \CodeIgniter\Database\BaseConnection
     */
    private function getConexion($companyID)
    {
        $db = db_connect();
        $db->query(sprintf("SET @ai_company_id = %d", $companyID));
        return $db;
    }

    /**
     * Ventas aplicadas agrupadas por mes, convertidas a la moneda funcional.
     * Regla: si la factura está en la moneda funcional se toma el total; si no, total * tipo de cambio de la factura.
     * @param int    $companyID
     * @param int    $currencyDefaultID moneda funcional
     * @param string $desde Y-m-d
     * @param string $hasta Y-m-d
     * @return array filas {mes 'Y-m', facturas, total}
     */
    function getVentasMensuales($companyID, $currencyDefaultID, $desde, $hasta)
    {
        $db  = $this->getConexion($companyID);
        $sql = "";
        $sql = $sql.sprintf("SELECT ");
        $sql = $sql.sprintf("    DATE_FORMAT(v.dia, '%%Y-%%m') AS mes, ");
        $sql = $sql.sprintf("    COUNT(*) AS facturas, ");
        $sql = $sql.sprintf("    SUM(CASE WHEN v.moneda_id = %d THEN v.total ELSE v.total * v.tipo_cambio END) AS total ", $currencyDefaultID);
        $sql = $sql.sprintf("FROM ai_v_ventas AS v ");
        $sql = $sql.sprintf("WHERE v.es_valida = 1 ");
        $sql = $sql.sprintf("    AND v.dia BETWEEN '%s' AND '%s' ", $desde, $hasta);
        $sql = $sql.sprintf("GROUP BY DATE_FORMAT(v.dia, '%%Y-%%m') ");
        $sql = $sql.sprintf("ORDER BY mes");
        return $db->query($sql)->getResult();
    }

    /**
     * Compras de mercadería aplicadas por mes y moneda (una fila por compra, sin repetir el total por detalle).
     * La conversión a moneda funcional se hace en el controlador con el tipo de cambio del día.
     * @param int    $companyID
     * @param string $desde Y-m-d
     * @param string $hasta Y-m-d
     * @return array filas {mes 'Y-m', moneda_id, total}
     */
    function getComprasMensuales($companyID, $desde, $hasta)
    {
        $db  = $this->getConexion($companyID);
        $sql = "";
        $sql = $sql.sprintf("SELECT ");
        $sql = $sql.sprintf("    x.mes, ");
        $sql = $sql.sprintf("    x.moneda_id, ");
        $sql = $sql.sprintf("    SUM(x.total_compra) AS total ");
        $sql = $sql.sprintf("FROM ( ");
        $sql = $sql.sprintf("    SELECT ");
        $sql = $sql.sprintf("        c.compra_id, ");
        $sql = $sql.sprintf("        DATE_FORMAT(MAX(c.dia), '%%Y-%%m') AS mes, ");
        $sql = $sql.sprintf("        MAX(c.moneda_id) AS moneda_id, ");
        $sql = $sql.sprintf("        MAX(c.total_compra) AS total_compra ");
        $sql = $sql.sprintf("    FROM ai_v_compras AS c ");
        $sql = $sql.sprintf("    WHERE c.es_valida = 1 ");
        $sql = $sql.sprintf("        AND c.dia BETWEEN '%s' AND '%s' ", $desde, $hasta);
        $sql = $sql.sprintf("    GROUP BY c.compra_id ");
        $sql = $sql.sprintf(") AS x ");
        $sql = $sql.sprintf("GROUP BY x.mes, x.moneda_id ");
        $sql = $sql.sprintf("ORDER BY x.mes");
        return $db->query($sql)->getResult();
    }

    /**
     * Ventas aplicadas por categoría de producto en el rango, en moneda funcional.
     * @param int    $companyID
     * @param int    $currencyDefaultID
     * @param string $desde Y-m-d
     * @param string $hasta Y-m-d
     * @return array filas {categoria, total} de mayor a menor
     */
    function getVentasPorCategoria($companyID, $currencyDefaultID, $desde, $hasta)
    {
        $db  = $this->getConexion($companyID);
        $sql = "";
        $sql = $sql.sprintf("SELECT ");
        $sql = $sql.sprintf("    IFNULL(NULLIF(d.categoria, ''), 'Sin categoría') AS categoria, ");
        $sql = $sql.sprintf("    SUM(CASE WHEN d.moneda_id = %d THEN d.total ELSE d.total * d.tipo_cambio END) AS total ", $currencyDefaultID);
        $sql = $sql.sprintf("FROM ai_v_ventas_detalle AS d ");
        $sql = $sql.sprintf("WHERE d.es_valida = 1 ");
        $sql = $sql.sprintf("    AND d.dia BETWEEN '%s' AND '%s' ", $desde, $hasta);
        $sql = $sql.sprintf("GROUP BY IFNULL(NULLIF(d.categoria, ''), 'Sin categoría') ");
        $sql = $sql.sprintf("ORDER BY total DESC");
        return $db->query($sql)->getResult();
    }

    /**
     * Productos con más unidades vendidas (facturas aplicadas) en el rango.
     * @param int    $companyID
     * @param string $desde Y-m-d
     * @param string $hasta Y-m-d
     * @param int    $limite
     * @return array filas {producto_id, producto, cantidad}
     */
    function getProductosMasVendidos($companyID, $desde, $hasta, $limite)
    {
        $db  = $this->getConexion($companyID);
        $sql = "";
        $sql = $sql.sprintf("SELECT ");
        $sql = $sql.sprintf("    d.producto_id, ");
        $sql = $sql.sprintf("    MAX(d.producto) AS producto, ");
        $sql = $sql.sprintf("    SUM(d.cantidad) AS cantidad ");
        $sql = $sql.sprintf("FROM ai_v_ventas_detalle AS d ");
        $sql = $sql.sprintf("WHERE d.es_valida = 1 ");
        $sql = $sql.sprintf("    AND d.dia BETWEEN '%s' AND '%s' ", $desde, $hasta);
        $sql = $sql.sprintf("GROUP BY d.producto_id ");
        $sql = $sql.sprintf("ORDER BY cantidad DESC ");
        $sql = $sql.sprintf("LIMIT %d", $limite);
        return $db->query($sql)->getResult();
    }

    /**
     * Valor del inventario (existencia total * costo) de los productos que no son servicio.
     * @param int $companyID
     * @return object {productos, unidades, valor}
     */
    function getInventarioValor($companyID)
    {
        $db  = $this->getConexion($companyID);
        $sql = "";
        $sql = $sql.sprintf("SELECT ");
        $sql = $sql.sprintf("    COUNT(*) AS productos, ");
        $sql = $sql.sprintf("    IFNULL(SUM(p.existencia_total), 0) AS unidades, ");
        $sql = $sql.sprintf("    IFNULL(SUM(p.existencia_total * p.costo), 0) AS valor ");
        $sql = $sql.sprintf("FROM ai_v_productos AS p ");
        $sql = $sql.sprintf("WHERE IFNULL(p.es_servicio, 0) = 0 ");
        $sql = $sql.sprintf("    AND p.existencia_total > 0");
        return $db->query($sql)->getRow();
    }

    /**
     * Productos en o cerca del mínimo de existencia.
     * Regla: crítico <= 50% del mínimo, bajo <= mínimo, reponer pronto <= 120% del mínimo.
     * @param int $companyID
     * @param int $limite
     * @return array filas {producto_id, codigo, nombre, existencia, minimo}
     */
    function getAlertasStock($companyID, $limite)
    {
        $db  = $this->getConexion($companyID);
        $sql = "";
        $sql = $sql.sprintf("SELECT ");
        $sql = $sql.sprintf("    p.producto_id, ");
        $sql = $sql.sprintf("    p.codigo, ");
        $sql = $sql.sprintf("    p.nombre, ");
        $sql = $sql.sprintf("    p.existencia_total AS existencia, ");
        $sql = $sql.sprintf("    p.existencia_minima AS minimo ");
        $sql = $sql.sprintf("FROM ai_v_productos AS p ");
        $sql = $sql.sprintf("WHERE IFNULL(p.es_servicio, 0) = 0 ");
        $sql = $sql.sprintf("    AND p.existencia_minima > 0 ");
        $sql = $sql.sprintf("    AND p.existencia_total <= p.existencia_minima * 1.2 ");
        $sql = $sql.sprintf("ORDER BY p.existencia_total / p.existencia_minima ASC ");
        $sql = $sql.sprintf("LIMIT %d", $limite);
        return $db->query($sql)->getResult();
    }

    /**
     * Cantidad de productos en estado crítico (<= 50% del mínimo) y bajo el mínimo.
     * @param int $companyID
     * @return object {criticos, bajo_minimo}
     */
    function getAlertasStockResumen($companyID)
    {
        $db  = $this->getConexion($companyID);
        $sql = "";
        $sql = $sql.sprintf("SELECT ");
        $sql = $sql.sprintf("    IFNULL(SUM(CASE WHEN p.existencia_total <= p.existencia_minima * 0.5 THEN 1 ELSE 0 END), 0) AS criticos, ");
        $sql = $sql.sprintf("    IFNULL(SUM(CASE WHEN p.existencia_total <= p.existencia_minima THEN 1 ELSE 0 END), 0) AS bajo_minimo ");
        $sql = $sql.sprintf("FROM ai_v_productos AS p ");
        $sql = $sql.sprintf("WHERE IFNULL(p.es_servicio, 0) = 0 ");
        $sql = $sql.sprintf("    AND p.existencia_minima > 0");
        return $db->query($sql)->getRow();
    }

    /**
     * Saldo de créditos vigentes agrupado por moneda (la conversión se hace en el controlador).
     * @param int $companyID
     * @return array filas {moneda_id, documentos, saldo}
     */
    function getCuentasPorCobrar($companyID)
    {
        $db  = $this->getConexion($companyID);
        $sql = "";
        $sql = $sql.sprintf("SELECT ");
        $sql = $sql.sprintf("    c.moneda_id, ");
        $sql = $sql.sprintf("    COUNT(*) AS documentos, ");
        $sql = $sql.sprintf("    SUM(c.saldo) AS saldo ");
        $sql = $sql.sprintf("FROM ai_v_creditos AS c ");
        $sql = $sql.sprintf("WHERE c.vigente = 1 ");
        $sql = $sql.sprintf("    AND c.saldo > 0 ");
        $sql = $sql.sprintf("GROUP BY c.moneda_id");
        return $db->query($sql)->getResult();
    }

    /**
     * Créditos otorgados por mes y moneda (serie de la tarjeta "Por cobrar").
     * @param int    $companyID
     * @param string $desde Y-m-d
     * @param string $hasta Y-m-d
     * @return array filas {mes 'Y-m', moneda_id, total}
     */
    function getCreditosMensuales($companyID, $desde, $hasta)
    {
        $db  = $this->getConexion($companyID);
        $sql = "";
        $sql = $sql.sprintf("SELECT ");
        $sql = $sql.sprintf("    DATE_FORMAT(c.fecha, '%%Y-%%m') AS mes, ");
        $sql = $sql.sprintf("    c.moneda_id, ");
        $sql = $sql.sprintf("    SUM(c.monto) AS total ");
        $sql = $sql.sprintf("FROM ai_v_creditos AS c ");
        $sql = $sql.sprintf("WHERE DATE(c.fecha) BETWEEN '%s' AND '%s' ", $desde, $hasta);
        $sql = $sql.sprintf("GROUP BY DATE_FORMAT(c.fecha, '%%Y-%%m'), c.moneda_id ");
        $sql = $sql.sprintf("ORDER BY mes");
        return $db->query($sql)->getResult();
    }

    /**
     * Últimas facturas registradas (cualquier estado), de la más reciente a la más antigua.
     * @param int $companyID
     * @param int $limite
     * @return array filas {factura_id, numero, fecha, cliente_nombre, tipo_pago, moneda, total, estado, es_valida}
     */
    function getFacturasRecientes($companyID, $limite)
    {
        $db  = $this->getConexion($companyID);
        $sql = "";
        $sql = $sql.sprintf("SELECT ");
        $sql = $sql.sprintf("    v.factura_id, ");
        $sql = $sql.sprintf("    v.numero, ");
        $sql = $sql.sprintf("    v.fecha, ");
        $sql = $sql.sprintf("    v.cliente_nombre, ");
        $sql = $sql.sprintf("    v.tipo_pago, ");
        $sql = $sql.sprintf("    v.moneda, ");
        $sql = $sql.sprintf("    v.total, ");
        $sql = $sql.sprintf("    v.estado, ");
        $sql = $sql.sprintf("    v.es_valida ");
        $sql = $sql.sprintf("FROM ai_v_ventas AS v ");
        $sql = $sql.sprintf("ORDER BY v.factura_id DESC ");
        $sql = $sql.sprintf("LIMIT %d", $limite);
        return $db->query($sql)->getResult();
    }
}
