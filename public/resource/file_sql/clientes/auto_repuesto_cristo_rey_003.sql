/******************************************************************/
/*****Auto Repuestos Cristo Rey (companyID 2, flavorID 1297) *****/
/*****16-nov-2026: columna Costo visible en las vistas de    *****/
/*****busqueda de productos (factura y compra)               *****/
/******************************************************************/
/*Ejecutar paso a paso. Cada UPDATE tiene una condicion que evita aplicarlo dos veces.*/
/*Importante: el JS de factura y de compra lee las columnas por POSICION (nonVisible + visible),*/
/*por eso la columna nueva va al final y no se mueve ninguna columna existente.*/


/*PASO 1: SELECCIONAR_ITEM_BILLING_POPUP_INVOICE (popup de factura)*/
/*Se agrega Costo al final del SELECT y de visibleColumns; formatColumns pasa de 18 a 19 posiciones (Descripcion sigue oculta en la 14)*/
UPDATE tb_company_dataview SET
	sqlScript           = REPLACE(
							sqlScript,
							'as PrecioCredito',
							'as PrecioCredito,
	round(ifnull(i.cost,0),2) as Costo'
						  ),
	visibleColumns      = 'Codigo,Nombre,Barra,Medida,Cantidad,Precio,Descripcion,Minimo,PrecioMayor,PrecioCredito,Costo',
	formatColumns       = ',,,,,,,,,,,,,,display:none,,,,',
	formatColumnsHeader = ',,,,,,,,,,,,,,display:none,,,,'
WHERE
	companyID = 2 and
	dataViewID = 107 and
	callerID = 2 and
	flavorID = 1297 and
	isActive = 1 and
	visibleColumns not like '%Costo%';


/*PASO 2: SELECCIONAR_ITEM_TO_PROVIDER_T (popup de compra)*/
/*La columna oculta Costo (posicion 2, la usa el JS para el costo de la compra) se renombra a CostoOculto*/
/*y se agrega Costo visible al final; formatColumns pasa de 14 a 15 posiciones (Descripcion sigue oculta en la 11)*/
UPDATE tb_company_dataview SET
	sqlScript           = REPLACE(
							REPLACE(
								sqlScript,
								'x.cost as Costo,',
								'x.cost as CostoOculto,'
							),
							'as PrecioCredito',
							'as PrecioCredito,
	round(ifnull(x.cost,0),2) as Costo'
						  ),
	nonVisibleColumns   = 'companyID,itemID,CostoOculto,pricePublico,pricePorMayor,priceCredito',
	visibleColumns      = 'Codigo,Nombre,Medida,Cantidad,Barra,Descripcion,PrecioMayor,PrecioCredito,Costo',
	formatColumns       = ',,,,,,,,,,,display:none,,,',
	formatColumnsHeader = ',,,,,,,,,,,display:none,,,'
WHERE
	companyID = 2 and
	dataViewID = 114 and
	callerID = 2 and
	flavorID = 1297 and
	isActive = 1 and
	nonVisibleColumns not like '%CostoOculto%';


/*PASO 3 (verificacion): ambas vistas deben terminar en ...,Costo*/
SELECT
	companyDataViewID,
	dataViewID,
	name,
	nonVisibleColumns,
	visibleColumns,
	formatColumns
FROM
	tb_company_dataview
WHERE
	companyID = 2 and
	flavorID = 1297 and
	dataViewID in (107,114);
