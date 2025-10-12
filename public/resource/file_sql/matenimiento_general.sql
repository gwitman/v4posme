update tb_item set isInvoice = 1 ; 
update tb_item set currencyID = 1;
update tb_item set unitMeasureID = 78,displayUnitMeasureID = 78;

delete from tb_item_sku where catalogItemID = 78;
insert into tb_item_sku(itemID,catalogItemID,`value`)
select 
	i.itemID,
	78,
	1
from 
	tb_item i ;
	
	
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
	
	
	
	


#INSERTAR bancos
INSERT INTO `tb_bank` (
	`companyID`, `name`, `accountID`, 
	`currencyID`, `balance`, `isActive`
) 
VALUES (
	2, 'BAC.', 0, 0, 0.00000000, 1
);


