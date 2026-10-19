---
inclusion: manual
---

# Consultas SQL guardadas

Repositorio de consultas SQL reutilizables. Se irán agregando más con el tiempo.

## Lista de órdenes de taller (Compu Matt)

Saca la lista de órdenes de taller de computadoras. Filtra por `transactionID = 40`.

```sql
SELECT
    c.transactionNumber AS Documento,
    c.transactionOn AS Fecha,
    ws.`name` AS Estado,
    CONCAT(cli.firstName, ' ', cli.lastName) AS Cliente,
    CONCAT(tec.firstName, ' ', tec.lastName) AS Tecnico,
    c.amount AS Monto,
    c.reference1 AS Nota,
    c.reference2 AS Problema,
    c.reference3 AS Solucion,
    tmi.reference1 AS Passwords,
    c.reference4 AS Serie,
    tmi.reference2 AS Modelo,
    tmi.referenceClientName AS Otras_Descripcion,
    acc.`name` AS Accesorios,
    marca.`name` AS Marca,
    art.`name` AS Articulo,
    estado.`name` AS EstadoArticulo
FROM tb_transaction_master c
INNER JOIN tb_transaction_master_info tmi ON tmi.transactionMasterID = c.transactionMasterID
INNER JOIN tb_workflow_stage ws ON ws.workflowStageID = c.statusID
INNER JOIN tb_naturales cli ON cli.entityID = c.entityID
INNER JOIN tb_naturales tec ON tec.entityID = c.entityIDSecondary
INNER JOIN tb_catalog_item acc ON acc.catalogItemID = c.priorityID
INNER JOIN tb_catalog_item marca ON marca.catalogItemID = tmi.zoneID
INNER JOIN tb_catalog_item art ON art.catalogItemID = tmi.routeID
INNER JOIN tb_catalog_item estado ON estado.catalogItemID = c.areaID
WHERE c.isActive = 1
  AND c.transactionID = 40
ORDER BY c.transactionMasterID DESC;
```
