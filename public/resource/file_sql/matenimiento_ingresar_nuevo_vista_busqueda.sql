
set @viewName := 'SELECCIONAR_ITEM_SKU';
set @componentName := 'tb_item';


set @viewScript := "select 
	x.itemID,
	u.catalogItemID ,
	x.name as Producto,
	u.display as Sku ,	
	ws.value as Valor
from 
	tb_item x 
	inner join tb_item_sku ws on 
		x.itemID = ws.itemID 
	inner join tb_catalog_item u on 
		ws.catalogItemID = u.catalogItemID 
where
	x.isActive = 1 and 
	x.companyID = {companyID}
order by
       x.itemNumber desc ";
			 
			 
set @viewColumnVisible := 'Producto,Sku,Valor';
set @viewColumnNoVisible := 'itemID,catalogItemID';



set @viewID := 0;
set @componentID := 0;
set @callerID := 2; /*1: principal, 2 busqueda*/


/*ingresar vista*/
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




/*ingrear la vista de la compania */
insert into tb_company_dataview( 
	companyID,dataViewID,callerID,componentID,
	`name`,description,sqlScript,visibleColumns,nonVisibleColumns,isActive
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
				'' as sq,'' as vi,'' as nov,1 AS isActive 				
	) t
where
	t.namex not in (select u.`name` from tb_company_dataview u );
	
	
UPDATE tb_company_dataview set 
	sqlScript = @viewScript,
	visibleColumns = @viewColumnVisible,
	nonVisibleColumns = @viewColumnNoVisible
WHERE
	dataViewID = @viewID; 
	