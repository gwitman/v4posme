USE posme;

select 'Procesando...Contabilidad' as processsx;
delete from tb_journal_entry_detail;
delete from tb_journal_entry;
delete from tb_accounting_balance;


select 'Procesando...Clientes' as processsx;
delete from tb_customer_credit_amoritization;
delete from tb_customer_credit_document;
delete from tb_customer_credit_line where entityID not in ( 13,309,614);
delete from tb_customer_credit where entityID not in ( 13,309,614);
delete from tb_customer where entityID not in ( 13,309,614);
delete from tb_employee where entityID not in ( 13,309,614);

select 'Procesando...Transacciones' as processsx;
delete from tb_transaction_master_info;
delete from tb_transaction_master_purchase;
delete from tb_transaction_master_detail_credit;
delete from tb_transaction_master_concept;
delete from tb_transaction_master_detail;
delete from tb_transaction_master;

select 'Procesando...Activo Fijo' as processsx;
#delete from tb_fixed_assent;


select 'Procesando...Productos' as processsx;
delete from tb_item_data_sheet_detail where itemID not between 22010 AND 23010 ; 
delete from tb_item_data_sheet where itemID not between 22010 AND 23010 ; 
delete from tb_kardex where itemID not between 22010 AND 23010 ; 
delete from tb_item_sku where itemID not between 22010 AND 23010 ; 
delete from tb_item_warehouse where itemID not between 22010 AND 23010 ; 
delete from tb_price where itemID not between 22010 AND 23010 ; 
delete from tb_provider_item where itemID not between 22010 AND 23010 ; 
delete from tb_item where itemID not between 22010 AND 23010 ; 

delete from tb_item_data_sheet_detail where itemID > 22010 ;
delete from tb_item_data_sheet where itemID > 22010 ;
delete from tb_kardex where itemID > 22010 ;
delete from tb_item_sku where itemID > 22010 ;
delete from tb_item_warehouse where itemID > 22010 ;
delete from tb_price where itemID > 22010 ;
delete from tb_provider_item where itemID > 22010 ;
delete from tb_item where itemID > 22010 ;



update tb_item set 
	name = 'no usado',branchID = 2,companyID = 2, inventoryCategoryID = 2,familyID = 75,
	unitMeasureID = 75,displayID = 81,capacity = 1, displayUnitMeasureID = 78,
	defaultWarehouseID = 4 , quantity = 0,quantityMax = 0 , quantityMin = 0 ,
	cost = 0,reference1 = '',reference2 = '',
	reference3 = '' , statusID = 38, isPerishable = null, factorBox = 1, 
	factorProgram = 1 , createdAt = 2, createdBy = 2,
	isActive = 1, isInvoiceQuantityZero = 1 , 
	isServices = 0 , currencyID = 1  , isInvoice = 1 ;
	
	


delete from tb_component_audit;
delete from tb_component_audit_detail;
delete from tb_customer_consultas_sin_riesgo;
delete from tb_customer_credit_clasification;
delete from tb_customer_credit_document_entity_related;
delete from tb_error;
delete from tb_log;
delete from tb_log_messeger;
delete from tb_notification;


