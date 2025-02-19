set @nameOrigen        	:= 'EMANUEL PUPUSAS';
set @flavorOrigen  	  	:= '664';
set @nameTarget        	:= 'EMANUEL PIZZA';
set @flavorTarget  	  	:= '714';

select 'clonoar catalogo item' as mensaje;
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
	
	
select 'clonoar company_dataview' as mensaje;	
insert into tb_company_dataview (
	companyID,
	dataViewID,
	callerID,
	componentID,
	`name`,
	description,
	sqlScript,
	visibleColumns,
	nonVisibleColumns,
	summaryColumns,
	formatColumns,
	isActive,
	flavorID,
	formatColumnsHeader 
)
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
	@flavorTarget  as flavorID 	,
	c.formatColumnsHeader
from 
	tb_company_dataview c 
where 
	c.flavorID = @flavorOrigen and 
	not exists (select * from tb_company_dataview ul where ul.flavorID = @flavorTarget and ul.dataViewID = c.dataViewID );
	
select 'clonoar company_default_dataview' as mensaje;	
insert into tb_company_default_dataview (companyID,componentID,dataViewID,callerID,targetComponentID)
select 
	c.companyID,
	c.componentID,
	c.dataViewID,
	c.callerID,
	c.targetComponentID
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