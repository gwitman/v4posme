set @transactionMasterID :=  4677;

insert into tb_transaction_master_detail (
		companyID,transactionID,transactionMasterID,componentID,componentItemID,
		promotionID,amount,cost,quantity,discount,
		unitaryAmount,
		unitaryCost,
		unitaryPrice,
		reference3,isActive,quantityStock,quantiryStockInTraffic,
		quantityStockUnaswared,remaingStock,lote,
		inventoryWarehouseSourceID,
		inventoryWarehouseTargetID,
		catalogStatusID,inventoryStatusID ,
		itemFormulatedApplied,typePriceID,skuCatalogItemID,skuQuantity,skuQuantityBySku,amountCommision)
select 
	2,33 /*AJUSTE DE INVENTARIO*/,@transactionMasterID,33, /*tb_item */ i.itemID,
	0,0,0,0,0,
	(select u.price from tb_price u where u.itemID = i.itemID AND u.typePriceID = 154 /*precio general*/ and u.listPriceID = 12 /*precio por defecto*/  limit 1 ) as unitary_amount,
	i.cost,
	(select u.price from tb_price u where u.itemID = i.itemID AND u.typePriceID = 154 /*precio general*/ and u.listPriceID = 12 /*precio por defecto*/  limit 1 ) as unitary_price,
	'0.00000000|0.00000000',1,0,0,
	0,0,'',
	(select c.sourceWarehouseID  from tb_transaction_master c where c.transactionMasterID = @transactionMasterID),
	(select targetWarehouseID from tb_transaction_master c where c.transactionMasterID = @transactionMasterID),
	0,0,
	0,0,0,0,0,0
from 
	tb_item i 
where 
	i.isActive = 1 and 
	i.itemID not in (select uu.componentItemID from tb_transaction_master_detail uu where uu.transactionMasterID = @transactionMasterID and uu.isActive = 1 ) and 
	i.quantity <> 0 
limit 50 ;