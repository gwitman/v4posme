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
	@flavorTarget 
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
		

select 'clonoar public_catalog' as mensaje;	
insert into tb_public_catalog ( `name` , systemName,statusID,	orden,description,isActive,flavorID)
select 
	k.`name`,
	k.systemName,
	k.statusID,
	k.orden,
	k.description,
	k.isActive,
	@flavorTarget
from 
	tb_public_catalog k 
where 
	k.flavorID = @flavorOrigen and 
	not exists (select * from tb_public_catalog  p where p.flavorID = @flavorTarget and p.systemName = k.systemName) ;
	
	
select 'clonoar public_catalog_detail' as mensaje;		
insert into tb_public_catalog_detail(
		publicCatalogID,
		`name`,
		display,
		flavorID,
		description,
		`sequence`,
		parentCatalogDetailID,
		ratio,
		parentName,
		isActive,
		reference1,
		reference2,
		reference3
)
select 
	(
		select 
			ld.publicCatalogID 
		from 
			tb_public_catalog lm 
			inner join tb_public_catalog ld on 
				ld.systemName = lm.systemName and 
				ld.flavorID = @flavorTarget 
		where 
			lm.publicCatalogID = c.publicCatalogID 
		limit 1 
	)
	c.`name`,
	c.display,
	c.flavorID,
	c.description,
	c.`sequence`,
	c.parentCatalogDetailID,
	c.ratio,
	c.parentName,
	c.isActive,
	c.reference1,
	c.reference2,
	c.reference3
from 
	tb_public_catalog_detail c 
	inner join tb_public_catalog k on 
		k.publicCatalogID = c.publicCatalogID 
where 
	k.flavorID = @flavorOrigen and 
	not exists (
		select 
			* 
		from 
			tb_public_catalog  d 
			inner join tb_public_catalog_detail dd on 
				d.publicCatalogID = dd.publicCatalogID 
		where 
			d.flavorID = @flavorTarget and 
			concat(d.systemName,'',dd.name) = concat(k.systemName,'',c.name) 
			
	);