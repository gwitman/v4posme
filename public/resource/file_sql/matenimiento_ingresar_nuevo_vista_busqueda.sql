
set @viewName 				:= 'SELECCIONAR_ITEM_PAGINATED';
set @componentName 			:= 'tb_item';
set @viewColumnVisible 		:= 'Codigo,Nombre,Medida,Cantidad,Costo,Barra';
set @viewColumnNoVisible 	:= 'companyID,itemID';


set @viewScript := "
select 
	x.companyID,
	x.itemID,
	x.itemNumber as Codigo,
	x.name as Nombre,
	ix.name as 'Medida',
	x.quantity as Cantidad,
	x.cost as Costo,
    x.barCode as Barra
from 
	tb_item x 
	inner join tb_catalog_item ix on 
		x.unitMeasureID = ix.catalogItemID   
where
	x.isActive = 1 and 
	x.companyID = {companyID} and 
	x.itemNumber like concat('%',REPLACE('{sSearchDB}',' ','%'),'%')
order by 
	x.itemNumber desc 
limit 
	{iDisplayStartDB},{iDisplayLength}
";
	   
	   
set @viewScriptDisplay := "
select 	
	count(*) as itemID
from 
	tb_item x 
	inner join tb_catalog_item ix on 
		x.unitMeasureID = ix.catalogItemID   
where
	x.isActive = 1 and 
	x.companyID = {companyID} and 
	x.itemNumber like concat('%',REPLACE('{sSearchDB}',' ','%'),'%')   
";
			 
set @viewScriptTotal := "
select 	
	count(*) as itemID
from 
	tb_item x 
	inner join tb_catalog_item ix on 
		x.unitMeasureID = ix.catalogItemID   
where
	x.isActive = 1 and 
	x.companyID = {companyID} 
";


set @viewID := 0;
set @componentID := 0;
set @callerID := 2; /*1: principal, 2 busqueda*/





/*ingresar vista normal*/
set @componentID := (SELECT u.componentID from tb_component u where u.`name` = @componentName );
insert into tb_dataview (`name`,description,sqlScript,visibleColumns,nonVisibleColumns,isActive,callerID,componentID) 
select 
	*
from 
	(
		select 
				@viewName as namex,
				@viewName as desription,
				'' as sq,'' as vi,'' as nov,1 AS isActive,
				@callerID as callerID,@componentID as componentID
	) t
where
	t.namex not in (select u.`name` from tb_dataview u );
	
set @viewID := (SELECT u.dataViewID from tb_dataview u where u.`name` =  @viewName);




/*ingrear la vista de la compania normal */
insert into tb_company_dataview( 
	companyID,dataViewID,callerID,componentID,
	`name`,description,sqlScript,visibleColumns,nonVisibleColumns,isActive,flavorID
)
select 
	*
from 
	(
		select 
			  2 as companyID,
				@viewID,
				@callerID as callerID,
				@componentID as componentID,
				@viewName as namex,
				@viewName as desription,
				'' as sq,'' as vi,'' as nov,1 AS isActive ,
				0 as flavorID 				
	) t
where
	t.namex not in (select u.`name` from tb_company_dataview u where u.flavorID = 0 );
	
	
UPDATE tb_company_dataview set 
	sqlScript = @viewScript,
	visibleColumns = @viewColumnVisible,
	nonVisibleColumns = @viewColumnNoVisible
WHERE
	dataViewID = @viewID and flavorID = 0; 
	
	
/*ingresar la vista mobile*/
set @componentID := (SELECT u.componentID from tb_component u where u.`name` = @componentName );
insert into tb_dataview (`name`,description,sqlScript,visibleColumns,nonVisibleColumns,isActive,callerID,componentID) 
select 
	*
from 
	(
		select 
				concat(@viewName,'','_MOBILE') as namex,
				@viewName as desription,
				'' as sq,'' as vi,'' as nov,1 AS isActive,
				@callerID as callerID,@componentID as componentID
	) t
where
	t.namex not in (select u.`name` from tb_dataview u );
	
set @viewID := (SELECT u.dataViewID from tb_dataview u where u.`name` =  CONCAT(@viewName,'','_MOBILE') );

insert into tb_company_dataview( 
	companyID,dataViewID,callerID,componentID,
	`name`,description,sqlScript,visibleColumns,nonVisibleColumns,isActive,flavorID
)
select 
	*
from 
	(
		select 
				2 as companyID,
				@viewID,
				@callerID as callerID,
				@componentID as componentID,
				CONCAT(@viewName,'','_MOBILE') as namex,
				@viewName as desription,
				'' as sq,'' as vi,'' as nov,1 AS isActive 	,
				0 as flavorID				
	) t
where
	t.namex not in (select u.`name` from tb_company_dataview u where u.flavorID = 0 );
	
	
UPDATE tb_company_dataview set 
	sqlScript = @viewScript,
	visibleColumns = @viewColumnVisible,
	nonVisibleColumns = @viewColumnNoVisible
WHERE
	dataViewID = @viewID AND flavorID = 0 ; 
	
/*ingresar la vista display*/
set @componentID := (SELECT u.componentID from tb_component u where u.`name` = @componentName );
insert into tb_dataview (`name`,description,sqlScript,visibleColumns,nonVisibleColumns,isActive,callerID,componentID) 
select 
	*
from 
	(
		select 
				concat(@viewName,'','_DISPLAY') as namex,
				@viewName as desription,
				'' as sq,'' as vi,'' as nov,1 AS isActive,
				@callerID as callerID,@componentID as componentID
	) t
where
	t.namex not in (select u.`name` from tb_dataview u );
	
set @viewID := (SELECT u.dataViewID from tb_dataview u where u.`name` =  CONCAT(@viewName,'','_DISPLAY') );

insert into tb_company_dataview( 
	companyID,dataViewID,callerID,componentID,
	`name`,description,sqlScript,visibleColumns,nonVisibleColumns,isActive,flavorID
)
select 
	*
from 
	(
		select 
				2 as companyID,
				@viewID,
				@callerID as callerID,
				@componentID as componentID,
				CONCAT(@viewName,'','_DISPLAY') as namex,
				@viewName as desription,
				'' as sq,'' as vi,'' as nov,1 AS isActive ,
				0 as flavorID 
	) t
where
	t.namex not in (select u.`name` from tb_company_dataview u where u.flavorID = 0 );
	
UPDATE tb_company_dataview set 
	sqlScript = @viewScriptDisplay,
	visibleColumns = 'itemID',
	nonVisibleColumns = NULL 
WHERE
	dataViewID = @viewID and flavorID = 0; 
	
/*ingresar la vista total*/
set @componentID := (SELECT u.componentID from tb_component u where u.`name` = @componentName );
insert into tb_dataview (`name`,description,sqlScript,visibleColumns,nonVisibleColumns,isActive,callerID,componentID) 
select 
	*
from 
	(
		select 
				concat(@viewName,'','_TOTAL') as namex,
				@viewName as desription,
				'' as sq,'' as vi,'' as nov,1 AS isActive,
				@callerID as callerID,@componentID as componentID
	) t
where
	t.namex not in (select u.`name` from tb_dataview u );
	
set @viewID := (SELECT u.dataViewID from tb_dataview u where u.`name` =  CONCAT(@viewName,'','_TOTAL') );


insert into tb_company_dataview( 
	companyID,dataViewID,callerID,componentID,
	`name`,description,sqlScript,visibleColumns,nonVisibleColumns,isActive,flavorID 
)
select 
	*
from 
	(
		select 
				2 as companyID,
				@viewID,
				@callerID as callerID,
				@componentID as componentID,
				CONCAT(@viewName,'','_TOTAL') as namex,
				@viewName as desription,
				'' as sq,'' as vi,'' as nov,1 AS isActive ,
				0 as flavorID 
	) t
where
	t.namex not in (select u.`name` from tb_company_dataview u where u.flavorID = 0 );
	
	
UPDATE tb_company_dataview set 
	sqlScript = @viewScriptTotal,
	visibleColumns = 'itemID',
	nonVisibleColumns = NULL
WHERE
	dataViewID = @viewID and flavorID = 0; 