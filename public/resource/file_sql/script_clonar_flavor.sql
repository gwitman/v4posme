set @nameOrigen        	:= 'RUSTIK';
set @flavorOrigen  	  	:= '298';
set @nameTarget        	:= 'TENAMPA';
set @flavorTarget  	  	:= '636';


insert into tb_catalog_item (
	catalogID,
	name,
	display,
	flavorID,
	description,
	`sequence`,
	parentCatalogID,
	parentCatalogItemID,
	ratio,
	reference1,
	reference2,
	reference3,
	reference4
) 
select 
	c.catalogID,
	c.name,
	c.display,
	@flavorTarget as flavorID,
	c.description,
	c.sequence,
	c.parentCatalogID,
	c.parentCatalogItemID,
	c.ratio,
	c.reference1,
	c.reference2,
	c.reference3,
	c.reference4
from 
	tb_catalog_item c 
where 
	c.flavorID = @flavorOrigen and 
	not exists (select * from tb_catalog_item ul where ul.flavorID = @flavorTarget and ul.catalogID = c.catalogID );
	
	
	

select 
	c.companyID,
	c.dataViewID,
	c.callerID,
	c.componentID,
	REPLACE ( c.name, @nameOrigen , @nameTarget )  as name,
	c.description,
	c.sqlScript,
	c.visibleColumns,
	c.nonVisibleColumns,
	c.summaryColumns,
	c.formatColumns,
	c.isActive,
	@flavorTarget  as flavorID 	
from 
	tb_company_dataview c 
where 
	c.flavorID = @flavorOrigen and 
	not exists (select * from tb_company_dataview ul where ul.flavorID = @flavorTarget and ul.dataViewID = c.dataViewID );
	
	

select 
	c.companyID,
	c.componentID,
	c.dataViewID,
	c.callerID,
	c.targetComponentID,
	c.companyDefaultDataviewID 
from 
	tb_company_default_dataview c 
where 
	c.targetComponentID = @flavorOrigen and 
	not exists (
		select 
			* 
		from 
			tb_company_default_dataview ul 
		where 
			ul.targetComponentID = @flavorTarget and 
			ul.dataViewID = c.dataViewID 
		);