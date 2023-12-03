
set @viewName := 'LISTA DE ASISTENCIAS COLABORADOR';
set @componentName := 'tb_transaction_master_rrhh_asistencia';


set @viewScript := "
select 
	x.companyID,
	x.transactionID,
	x.transactionMasterID,
	x.transactionNumber AS Numero,
	date_format(x.createdOn,'%Y-%m-%d') as Fecha,
	ws.name as Estado,
	substring(u.nickname,1,20) as 'Usuario',	
	substring(nat.firstName,1,15) as Colaborador ,
	ct.name as Tipo
from 
	tb_transaction_master x 
    inner join tb_transaction_causal ct on 
                   x.transactionCausalID = ct.transactionCausalID	
	inner join tb_workflow_stage ws on 
		x.statusID = ws.workflowStageID 
	inner join tb_user u on 
		x.createdBy = u.userID 
	inner join tb_naturales nat on 
		x.entityID = nat.entityID 
	inner join tb_currency cur on 
		x.currencyID = cur.currencyID  
where
	x.isActive = 1 and 
	x.transactionID = 31 and 
	x.companyID = {companyID}  {filterPermission} 
order by
       x.transactionNumber desc 
limit 0,50
";
			 
			 
set @viewColumnVisible := 'Numero,Fecha,Estado,Usuario,Colaborador,Tipo';
set @viewColumnNoVisible := 'companyID,transactionID,transactionMasterID';



set @viewID := 0;
set @componentID := 0;
set @callerID := 1; /*1: principal, 2 busqueda*/

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


/*ingreasr vista por defecto*/
insert IGNORE into tb_company_default_dataview (companyID,componentID,dataViewID,callerID,targetComponentID) VALUES 
(2,@componentID,@viewID,@callerID,0 );

/*asociar vista al componente*/
insert ignore into tb_company_component_item_dataview(companyID,componentID,dataViewID,callerID,flavorID) values 
(2,@componentID,@viewID,@callerID,0);

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
	