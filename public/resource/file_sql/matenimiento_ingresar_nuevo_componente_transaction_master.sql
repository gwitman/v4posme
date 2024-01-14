set @componentName   					:= 'tb_transaction_master_accounting_expenses';
set @workflowName   					:= 'ESTADO DE LOS GASTOS CONTABLES';
set @transactionName 					:= 'GASTOS';
set @serieName							:= 'ING';
set @componentID					    := 0;
set @elementID							:= 0;
set @workflowID							:= 0;
set @workflowStageID1				  	:= 0;
set @workflowStageID2				  	:= 0;
set @transactionID						:= 0;

/*ingresar componente*/
insert into tb_component (`name`) 
select 
	tempx.nam
from 
	(
	 select @componentName as nam
	) as tempx 
WHERE	
	tempx.nam not in (select u.`name` from tb_component u );
set @componentID := (SELECT u.componentID FROM tb_component u where u.name = @componentName );


/*asociar componente a la compania*/
insert IGNORE into tb_company_component (companyID,componentID) values 
(2, @componentID);


/*crear elemento*/
insert into tb_element (elementTypeID,`name`) 
select 
	temp.elementTypeID2,temp.name2 
from 
	(select 2 /*tabla*/ as elementTypeID2, @componentName as name2) AS temp 
where
	temp.name2 not in (select u.`name` from tb_element u );
	
	
/*asociar el elemento al componente*/
set	@elementID := (SELECT u.elementID FROM tb_element u where u.name = @componentName );
insert IGNORE into tb_component_element (componentID,elementID) VALUES 
(@componentID,@elementID);
	
/*ingrear flujo*/
insert into tb_workflow (componentID,`name`,description,isActive)
select 
	*
from 
	(
		select 
			@componentID as componentID,
			@workflowName as namex,
			'N/D' as description,  
			1 as isActive 
	) as temp 
WHERE
	temp.namex  not in (select u.`name` from tb_workflow u );

set @workflowID := (select u.workflowID from tb_workflow u where u.name = @workflowName );

/*ingrear estado inicial del flujo*/
insert into tb_workflow_stage (
	componentID,workflowID,`name`,
	description,display,flavorID,
	editableParcial,editableTotal,eliminable,
	aplicable,vinculable,isActive,
	isInit
)
select 
	*
from 
	(
		select 
		@componentID as componentID,@workflowID as workflowID,'REGISTRADO' as `name`,
		'REGISTRADO' as description,'REGISTRADO' as display,0 as flavorID,
		0 AS editableParcial,1 as editableTotal,1 as eliminable,
		0 as aplicable,0 as vinculable,1 as isActive,
		1 as isInit
	) as tempx 
where
	not exists ( 
		select 
			* 
		from 
			tb_workflow_stage k 
		where 
			k.componentID = @componentID  and 
			k.workflowID = @workflowID and 
			k.`name` = 'REGISTRADO' 
	);
	
set @workflowStageID1 := (
		select 
			k.workflowStageID
		from 
			tb_workflow_stage k 
		where 
			k.componentID = @componentID  and 
			k.workflowID = @workflowID and 
			k.`name` = 'REGISTRADO' 
			
);

/*ingrear el segundo estado  del flujo*/
insert into tb_workflow_stage (
	componentID,workflowID,`name`,
	description,display,flavorID,
	editableParcial,editableTotal,eliminable,
	aplicable,vinculable,isActive,
	isInit
)
select 
	*
from 
	(
		select 
		@componentID as componentID,@workflowID as workflowID,'APLICADO' as `name`,
		'APLICADO' as description,'APLICADO' as display,0 as flavorID,
		0 AS editableParcial,0 as editableTotal,0 as eliminable,
		1 as aplicable,0 as vinculable,1 as isActive,
		0 as isInit
	) as tempx 
where
	not exists ( 
		select 
			* 
		from 
			tb_workflow_stage k 
		where 
			k.componentID = @componentID  and 
			k.workflowID = @workflowID and 
			k.`name` = 'APLICADO' 
	);
	
set @workflowStageID2 := (
		select 
			k.workflowStageID
		from 
			tb_workflow_stage k 
		where 
			k.componentID = @componentID  and 
			k.workflowID = @workflowID and 
			k.`name` = 'APLICADO' 			
);

insert IGNORE into tb_workflow_stage_relation (
	componentID,workflowID,workflowStageID,
	workflowStageTargetID,necesitaAuth,AuthRolID) values 
(@componentID,@workflowID,@workflowStageID1,@workflowStageID1,0,0);

insert IGNORE into tb_workflow_stage_relation (
	componentID,workflowID,workflowStageID,
	workflowStageTargetID,necesitaAuth,AuthRolID) values 
(@componentID,@workflowID,@workflowStageID2,@workflowStageID2,0,0);

insert IGNORE into tb_workflow_stage_relation (
	componentID,workflowID,workflowStageID,
	workflowStageTargetID,necesitaAuth,AuthRolID) values 
(@componentID,@workflowID,@workflowStageID1,@workflowStageID2,0,0);

insert ignore into tb_company_component_flavor (companyID,componentID,componentItemID,flavorID) values 
(2,2,@workflowID,0);


/*ingrear el contador*/
insert ignore into tb_counter(
	companyID,
	componentID,branchID,componentItemID,
	initialValue,currentValue,seed,
	serie,length
) 
select 
	*
from 
(
	select 
		2 as companyID,@componentID as componentID,2 AS branchID,0 AS componentItemID,
		0 AS initialValue,0 as currentValue,1 as seed,
		@serieName as siere,8 as length
) as tep
where
	tep.siere not in (select u.serie from tb_counter u );
	
/*ingrear sub elemento*/
insert into tb_subelement (elementID,`name`,workflowID,catalogID) 
select 
	*
from 
	(
	 select 
		@elementID as elementID,
		'statusID' as namex,
		@workflowID,0 
	) as tx 
where
   not exists (select * from tb_subelement u where u.elementID = @elementID and u.`name` = 'statusID' );
	 
	 
 /*insertar transaccion*/
 insert into tb_transaction(
	companyID,name,description,
	workflowID,isCountable,reference1,
	reference2,reference3,
	generateTransactionNumber,decimalPlaces,
	journalTypeID,signInventory,isRevert,isActive
)
select 
	*
from 
	(
		select 
			2 as companyID,@transactionName as name,@transactionName as description,
			@workflowID,0,'EMPTY' as reference1,
			'EMPTY' as reference2,'EMPTY' as reference3,
			1 as generateTransactionNumber,8 as decimalPlaces,
			0 as journalTypeID,0 as signInventory,0 as isRever,1 as isActive
			
	) as  t
where 
	t.`name` not in (select u.`name` from tb_transaction u );
	
	
/*insertar el sabor de la transaccion*/
set @transactionID := (select u.transactionID from tb_transaction u where u.name = @transactionName);


insert ignore into tb_company_component_flavor(companyID,componentID,componentItemID,flavorID) VALUES 
(2, @componentID,0,@transactionID);

/*insertar causal por defecto*/

insert into tb_transaction_causal (companyID,transactionID,branchID,name,warehouseSourceID,warehouseTargetID,isDefault,isActive)
select 
	*
from 
	(
		select 
			2 as companyID,
			@transactionID as transactionID,
			2 as branchID,
			'default' as name,
			0 as warehouseSource,
			0 as warehouseTareget,
			1 as isDefault ,
			1 as isActive 
	) as t 
where 
	t.transactionID not in (select u.transactionID from tb_transaction_causal u );



/*ingrear la autorizacion del super administrador*/
insert into tb_component_autorization_detail (companyID,componentAutorizationID,componentID,workflowID,workflowStageID) 
select 
	2,
	4,
	x.componentID,
	x.workflowID,
	x.workflowStageID
from 
	tb_workflow_stage X 
where 
	not exists (
		select 
			*
		from 
			tb_component_autorization_detail u 
		where 
			u.companyID = 2 and 
			u.componentAutorizationID = 4 and 
			u.componentID = x.componentID and 
			u.workflowID = x.workflowID and 
			u.workflowStageID = x.workflowStageID			
	);
	
select 'success' as mensaje;