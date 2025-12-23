SET @companyType									:= 'moncho';
SET @companyFlavorID								:= 1031;
SET @companyName									:= 'Moncho';


SET @insertarNuevaPantallaFacturacionExt6_2_0		:= 'no';
SET @updateItemSkuA78								:= 'no';
SET @updateIsInvoice1AllProductos					:= 'no';
SET @updateCurrencyID1AllProductos					:= 'no';
SET @updateUnitMeasure78AllProductos				:= 'no';
SET @insertarBanco 									:= 'no';
SET @insertarBancoName								:= 'BAC.';



#INSERTAR nueva pantalla de facturacion
INSERT INTO `tb_company_page_setting` (namei,keyi,controller,method,type,flavorID,element,valuei,isActive) 
SELECT 
	concat('Factura de ' ,@companyName),
	@companyType,
	'app_invoice_billing',
	'index',
	'string',
	0,
	'versionPantallaFacturacion',	
	'/versionPantalla/ext_6_0_2',
	1	
FROM 	
	dual 
WHERE 	
	UPPER(@insertarNuevaPantallaFacturacionExt6_2_0) = UPPER('si');
	
	
/*ACTUALIZAR PRODUCTOS*/
update tb_item set isInvoice = 1 where UPPER(@updateIsInvoice1AllProductos) = UPPER('si'); 
update tb_item set currencyID = 1 where UPPER(@updateCurrencyID1AllProductos) = UPPER('si');  
update tb_item set unitMeasureID = 78,displayUnitMeasureID = 78 where UPPER(@updateUnitMeasure78AllProductos) = UPPER('si');  

delete from tb_item_sku where catalogItemID = 78 and UPPER(@updateItemSkuA78) = UPPER('si');
insert into tb_item_sku(itemID,catalogItemID,`value`)
select 
	i.itemID,
	78,
	1
from 
	tb_item i 
where 
	UPPER(@updateItemSkuA78) = UPPER('si');
	
	
/*ACTUALIZAR PRECIOS*/
insert into tb_price (companyID,listPriceID,itemID,typePriceID,percentage,price,percentageCommision)
select 
	2,
	12,
	i.itemID,
	154,
	0,
	0,
	0
from 
	tb_item i 
where 
	i.itemID not in (select u.itemID from tb_price u where u.typePriceID = 154 );
	
	
insert into tb_price (companyID,listPriceID,itemID,typePriceID,percentage,price,percentageCommision)
select 
	2,
	12,
	i.itemID,
	155,
	0,
	0,
	0
from 
	tb_item i 
where 
	i.itemID not in (select u.itemID from tb_price u where u.typePriceID = 155 );
	
	
insert into tb_price (companyID,listPriceID,itemID,typePriceID,percentage,price,percentageCommision)
select 
	2,
	12,
	i.itemID,
	156,
	0,
	0,
	0
from 
	tb_item i 
where 
	i.itemID not in (select u.itemID from tb_price u where u.typePriceID = 156 );
	
	
insert into tb_price (companyID,listPriceID,itemID,typePriceID,percentage,price,percentageCommision)
select 
	2,
	12,
	i.itemID,
	477,
	0,
	0,
	0
from 
	tb_item i 
where 
	i.itemID not in (select u.itemID from tb_price u where u.typePriceID = 477 );
	
	
insert into tb_price (companyID,listPriceID,itemID,typePriceID,percentage,price,percentageCommision)
select 
	2,
	12,
	i.itemID,
	478,
	0,
	0,
	0
from 
	tb_item i 
where 
	i.itemID not in (select u.itemID from tb_price u where u.typePriceID = 478 );
	
	
#Actualizar Cantidades
update tb_item d , (
		select 
			iw.itemID,
			SUM(iw.quantity) as quantity  
		from 
			tb_item_warehouse iw 
			inner join tb_item i on 
				i.itemID = iw.itemID 
			inner join tb_warehouse w on 
				w.warehouseID = iw.warehouseID 
		where 
			i.isActive = 1 and 
			w.isActive = 1
		group by 
			iw.itemID 
		) as o
set 
	d.quantity = o.quantity 
where 
	d.itemID = o.itemID ; 


#INSERTAR bancos
INSERT INTO `tb_bank` (
	`companyID`, `name`, `accountID`, 
	`currencyID`, `balance`, `isActive`
) 
SELECT 
	2, @insertarBancoName, 0, 0, 0.00000000, 1
FROM 
	dual 
WHERE 
	UPPER(@insertarBanco) = UPPER('si');