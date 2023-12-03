set @componentName   					:= 'tb_transaction_master_rrhh_asistencia';
set @subelementoName					:= 'priorityID';
set @catalogName						:= 'TIPO-Asistencias';
set @catalogID							:= 0;
set @elementID							:= 0;


/*insertar catalogo*/
insert into tb_catalog(name,description,orden,isActive)
select 
	*
from 
	(
		select @catalogName as name,@catalogName as description,1 as orden,1 as active
	) t
where
	t.`name` not in (select u.`name` from tb_catalog u );
	
	
set @catalogID		:= (select u.`catalogID` from tb_catalog u  where u.name = @catalogName);

/*insertar el sabor del catalogo*/
insert ignore into tb_company_component_flavor (companyID,componentID,componentItemID,flavorID) values 
(2,3,@catalogID,0);


/*insertar el primer sub elemento del catalogo*/
insert into tb_catalog_item (catalogID,`name`,display,description,flavorID,sequence,parentCatalogID,parentCatalogItemID) 
select 
	*
from 
	(
		select 
				@catalogID as catalogID,
				'Ninguno' as nane,
				'Ninguno' as display,
				'Ninguno' as description,
				0 as flavorID,
				0 as sequence,
				0 as parentCatalogID,
				0 as parentCatalogItemID
	) t
where
	not exists (select * from tb_catalog_item u where u.catalogID = @catalogID and u.`name` = 'Ninguno'); 
	
	
/*insertar el subelemento*/
set @elementID := (select u.elementID from tb_element u where u.name = @componentName and u.elementTypeID = 2);
insert into tb_subelement (elementID,`name`,workflowID,catalogID) 
select 
	*
from 
	(
	 select 
		@elementID as elementID,
		@subelementoName as namex,
		0,
		@catalogID as catalogID
	) as tx 
where
   not exists (select * from tb_subelement u where u.elementID = @elementID and u.`name` = @subelementoName );
	
	
	
