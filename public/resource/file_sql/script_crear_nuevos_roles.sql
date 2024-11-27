set @nameCompany        := 'BAR_EXIST';
set @domainDestino 			:= CONCAT('@',LOWER( @nameCompany));


set @nameCompanyOrigen  		:= 'SOURCE';
set @domainOrigen 				:=  CONCAT('@',LOWER( @nameCompanyOrigen));
set @userAdministradorWeb 		:=  'administrador_web';
set @userAdministradorMobile 	:=  'administrador_mobile';
set @userAdministradorWindow 	:=  'administrador_window';
set @userFacturadorWeb 			:=  'facturador_web';
set @userFacturadorMobile 		:=  'facturador_mobile';
set @userFacturadorWindow 		:=  'facturador_window';
set @userSupervisor     		:=  'supervisor'; 
set @flavorID					:=  '0';


#INSERTAR ROLES
insert into tb_role(
	companyID,branchID,`name`,description,isAdmin,createdOn,urlDefault,createdBy,isActive
) 
select 	
	companyID,branchID,
	REPLACE(cc.`name`,@nameCompanyOrigen,@nameCompany) as `name`,description,0 as isAdmin,
	createdOn,'core_dashboards' as urlDefault,createdBy ,isActive
from 
	tb_role cc 
where
	cc.`name` like concat(@nameCompanyOrigen,'@%'); 



#CONFIGURAR ADMINISTRADOR_WEB
set @roleAdminWeb := (select u.roleID from tb_role u where u.`name` = CONCAT(@nameCompany,'@ADMINISTRADOR_WEB') );
insert into tb_user_permission(elementID,companyID,branchID,roleID,selected,inserted,deleted,edited)
select 
	e.elementID,e.companyID,e.branchID,@roleAdminWeb roleID,e.selected,e.inserted,e.deleted,e.edited
from 
	tb_user_permission e
	inner join tb_role r on 
		e.roleID = r.roleID 
where
	r.`name` = CONCAT(@nameCompanyOrigen,'@ADMINISTRADOR_WEB');


set @roleAdminWeb := (select u.roleID from tb_role u where u.`name` = CONCAT(@nameCompany,'@ADMINISTRADOR_WEB') );
insert into tb_role_autorization(companyID,componentAutorizationID,roleID,branchID)
select 
	 e.companyID,
	 componentAutorizationID,
	 @roleAdminWeb,
	 e.branchID
from 
	tb_role_autorization e
	inner join tb_role r on 
		e.roleID = r.roleID 
where
	r.`name` = CONCAT(@nameCompanyOrigen,'@ADMINISTRADOR_WEB');
	
	
#CONFIGURAR ADMINISTRADOR_MOBILE
set @roleAdminMobile := (select u.roleID from tb_role u where u.`name` = CONCAT(@nameCompany,'@ADMINISTRADOR_MOBILE') );
insert into tb_user_permission(elementID,companyID,branchID,roleID,selected,inserted,deleted,edited)
select 
	e.elementID,e.companyID,e.branchID,@roleAdminMobile roleID,e.selected,e.inserted,e.deleted,e.edited
from 
	tb_user_permission e
	inner join tb_role r on 
		e.roleID = r.roleID 
where
	r.`name` = CONCAT(@nameCompanyOrigen,'@ADMINISTRADOR_MOBILE');


set @roleAdminMobile := (select u.roleID from tb_role u where u.`name` = CONCAT(@nameCompany,'@ADMINISTRADOR_MOBILE') );
insert into tb_role_autorization(companyID,componentAutorizationID,roleID,branchID)
select 
	 e.companyID,
	 componentAutorizationID,
	 @roleAdminMobile,
	 e.branchID
from 
	tb_role_autorization e
	inner join tb_role r on 
		e.roleID = r.roleID 
where
	r.`name` = CONCAT(@nameCompanyOrigen,'@ADMINISTRADOR_MOBILE');
	
	
#CONFIGURAR ADMINISTRADOR_WINDOW
set @roleAdminWindow := (select u.roleID from tb_role u where u.`name` = CONCAT(@nameCompany,'@ADMINISTRADOR_WINDOW') );
insert into tb_user_permission(elementID,companyID,branchID,roleID,selected,inserted,deleted,edited)
select 
	e.elementID,e.companyID,e.branchID,@roleAdminWindow roleID,e.selected,e.inserted,e.deleted,e.edited
from 
	tb_user_permission e
	inner join tb_role r on 
		e.roleID = r.roleID 
where
	r.`name` = CONCAT(@nameCompanyOrigen,'@ADMINISTRADOR_WINDOW');


set @roleAdminWindow := (select u.roleID from tb_role u where u.`name` = CONCAT(@nameCompany,'@ADMINISTRADOR_WINDOW') );
insert into tb_role_autorization(companyID,componentAutorizationID,roleID,branchID)
select 
	 e.companyID,
	 componentAutorizationID,
	 @roleAdminWindow,
	 e.branchID
from 
	tb_role_autorization e
	inner join tb_role r on 
		e.roleID = r.roleID 
where
	r.`name` = CONCAT(@nameCompanyOrigen,'@ADMINISTRADOR_WINDOW');


#CONFIGURAR RACTURADOR_WEB
set @roleFacturadorWeb := (select u.roleID from tb_role u where u.`name` = CONCAT(@nameCompany,'@RACTURADOR_WEB') );
insert into tb_user_permission(elementID,companyID,branchID,roleID,selected,inserted,deleted,edited)
select 
	e.elementID,e.companyID,e.branchID,@roleFacturadorWeb roleID,e.selected,e.inserted,e.deleted,e.edited
from 
	tb_user_permission e
	inner join tb_role r on 
		e.roleID = r.roleID 
where
	r.`name` = CONCAT(@nameCompanyOrigen,'@RACTURADOR_WEB');

set @roleFacturadorWeb := (select u.roleID from tb_role u where u.`name` = CONCAT(@nameCompany,'@RACTURADOR_WEB') );
insert into tb_role_autorization(companyID,componentAutorizationID,roleID,branchID)
select 
	 e.companyID,
	 componentAutorizationID,
	 @roleFacturadorWeb,
	 e.branchID
from 
	tb_role_autorization e
	inner join tb_role r on 
		e.roleID = r.roleID 
where
	r.`name` = CONCAT(@nameCompanyOrigen,'@RACTURADOR_WEB');
	
	

#CONFIGURAR RACTURADOR_MOBILE
set @roleFacturadorMobile := (select u.roleID from tb_role u where u.`name` = CONCAT(@nameCompany,'@RACTURADOR_MOBILE') );
insert into tb_user_permission(elementID,companyID,branchID,roleID,selected,inserted,deleted,edited)
select 
	e.elementID,e.companyID,e.branchID,@roleFacturadorMobile roleID,e.selected,e.inserted,e.deleted,e.edited
from 
	tb_user_permission e
	inner join tb_role r on 
		e.roleID = r.roleID 
where
	r.`name` = CONCAT(@nameCompanyOrigen,'@RACTURADOR_MOBILE');

set @roleFacturadorMobile := (select u.roleID from tb_role u where u.`name` = CONCAT(@nameCompany,'@RACTURADOR_MOBILE') );
insert into tb_role_autorization(companyID,componentAutorizationID,roleID,branchID)
select 
	 e.companyID,
	 componentAutorizationID,
	 @roleFacturadorMobile,
	 e.branchID
from 
	tb_role_autorization e
	inner join tb_role r on 
		e.roleID = r.roleID 
where
	r.`name` = CONCAT(@nameCompanyOrigen,'@RACTURADOR_MOBILE');
	
	
#CONFIGURAR RACTURADOR_WINDOW
set @roleFacturadorWindow := (select u.roleID from tb_role u where u.`name` = CONCAT(@nameCompany,'@RACTURADOR_WINDOW') );
insert into tb_user_permission(elementID,companyID,branchID,roleID,selected,inserted,deleted,edited)
select 
	e.elementID,e.companyID,e.branchID,@roleFacturadorWindow roleID,e.selected,e.inserted,e.deleted,e.edited
from 
	tb_user_permission e
	inner join tb_role r on 
		e.roleID = r.roleID 
where
	r.`name` = CONCAT(@nameCompanyOrigen,'@RACTURADOR_WINDOW');

set @roleFacturadorWindow := (select u.roleID from tb_role u where u.`name` = CONCAT(@nameCompany,'@RACTURADOR_WINDOW') );
insert into tb_role_autorization(companyID,componentAutorizationID,roleID,branchID)
select 
	 e.companyID,
	 componentAutorizationID,
	 @roleFacturadorWindow,
	 e.branchID
from 
	tb_role_autorization e
	inner join tb_role r on 
		e.roleID = r.roleID 
where
	r.`name` = CONCAT(@nameCompanyOrigen,'@RACTURADOR_WINDOW');


#CONFIGURAR SUPERVISOR
set @roleSupervisor := (select u.roleID from tb_role u where u.`name` = CONCAT(@nameCompany,'@SUPERVISOR') );
insert into tb_user_permission(elementID,companyID,branchID,roleID,selected,inserted,deleted,edited)
select 
	e.elementID,e.companyID,e.branchID,@roleSupervisor roleID,e.selected,e.inserted,e.deleted,e.edited
from 
	tb_user_permission e
	inner join tb_role r on 
		e.roleID = r.roleID 
where
	r.`name` = CONCAT(@nameCompanyOrigen,'@SUPERVISOR');


set @roleSupervisor := (select u.roleID from tb_role u where u.`name` = CONCAT(@nameCompany,'@SUPERVISOR') );
insert into tb_role_autorization(companyID,componentAutorizationID,roleID,branchID)
select 
	 e.companyID,
	 componentAutorizationID,
	 @roleSupervisor,
	 e.branchID
from 
	tb_role_autorization e
	inner join tb_role r on 
		e.roleID = r.roleID 
where
	r.`name` = CONCAT(@nameCompanyOrigen,'@SUPERVISOR');
	
	
	

#INSERTAR USUARIOS
insert into tb_user (companyID,branchID,nickname,`password`,createdOn,isActive,email,createdBy,employeeID,useMobile,locationID)
select 
	companyID,
	branchID,
	nickname  as nickname,
	`password`,
	createdOn,
	isActive,
	REPLACE(c.email,@domainOrigen,@domainDestino) as email,
	createdBy,
	614 as employeeID ,
	c.useMobile ,
	0
from 
	tb_user c 
where 
	c.email = CONCAT(@userAdministradorWeb,@domainOrigen) or 
	c.email = CONCAT(@userAdministradorMobile,@domainOrigen) or 
	c.email = CONCAT(@userAdministradorWindow,@domainOrigen) or 
	c.email = CONCAT(@userFacturadorWeb,@domainOrigen) or 
	c.email = CONCAT(@userFacturadorMobile,@domainOrigen) or 
	c.email = CONCAT(@userFacturadorWindow,@domainOrigen) or 
	c.email = CONCAT(@userSupervisor,@domainOrigen);
	
#ASOCIAR ROLES A LOS USUARIOS
insert into tb_membership (branchID,companyID,roleID,userID)
select 
    m.branchID,
	m.companyID,
	urd.roleID,
	dd.userID
from 
	tb_membership m 
	inner join tb_user u on m.userID = u.userID  	
	inner join (
			select 
				x.userID,
				replace(x.email ,@domainDestino,'') as email
			from 
				tb_user x 
			where 
				x.email = CONCAT(@userAdministradorWeb,@domainDestino)  or 
				x.email = CONCAT(@userAdministradorMobile,@domainDestino)  or 
				x.email = CONCAT(@userAdministradorWindow,@domainDestino)  or 
				x.email = CONCAT(@userFacturadorWeb,@domainDestino)  or 
				x.email = CONCAT(@userFacturadorMobile,@domainDestino)  or 
				x.email = CONCAT(@userFacturadorWindow,@domainDestino)  or 
				x.email = CONCAT(@userSupervisor,@domainDestino)  	
	) as dd on dd.email = replace(u.email ,@domainOrigen,'')
	
	inner join tb_role ur on 	
		ur.roleID = m.roleID 
		
	inner join (
			select 
				z.roleID,
				replace(z.name ,replace(@nameCompany,'@',''),'') as nombrez
			from 
				tb_role z
			where 
				z.name = CONCAT(replace(@domainDestino,'@',''),'@',@userAdministradorWeb)  or 
				z.name = CONCAT(replace(@domainDestino,'@',''),'@',@userAdministradorMobile)  or 
				z.name = CONCAT(replace(@domainDestino,'@',''),'@',@userAdministradorWindow)  or 
				z.name = CONCAT(replace(@domainDestino,'@',''),'@',@userFacturadorWeb)  or 
				z.name = CONCAT(replace(@domainDestino,'@',''),'@',@userFacturadorMobile)  or 
				z.name = CONCAT(replace(@domainDestino,'@',''),'@',@userFacturadorWindow)  or 
				z.name = CONCAT(replace(@domainDestino,'@',''),'@',@userSupervisor)  	
	) as urd on urd.nombrez  = replace( ur.name , replace(@nameCompanyOrigen,'@',''),'')
		
where		
	u.email = CONCAT(@userAdministradorWeb,@domainOrigen)  or 
	u.email = CONCAT(@userAdministradorMobile,@domainOrigen)  or 
	u.email = CONCAT(@userAdministradorWindow,@domainOrigen)  or 
	u.email = CONCAT(@userFacturadorWeb,@domainOrigen)  or 
	u.email = CONCAT(@userFacturadorMobile,@domainOrigen)  or 
	u.email = CONCAT(@userFacturadorWindow,@domainOrigen)  or 
	u.email = CONCAT(@userSupervisor,@domainOrigen) ;
	
	



#INSERTAR TAG
insert into tb_user_tag (tagID,companyID,branchID,userID)
select 
	m.tagID,
  m.companyID,
	m.branchID,
	dd.userID	
from 
	tb_user_tag m 
	inner join tb_user u on m.userID = u.userID  	
	inner join (
			select 
				x.userID,
				replace(x.email ,@domainDestino,'') as email
			from 
				tb_user x 
			where 
				x.email = CONCAT(@userAdministradorWeb,@domainDestino)  or 
				x.email = CONCAT(@userAdministradorMobile,@domainDestino)  or 
				x.email = CONCAT(@userAdministradorWindow,@domainDestino)  or 
				x.email = CONCAT(@userFacturadorWeb,@domainDestino)  or 
				x.email = CONCAT(@userFacturadorMobile,@domainDestino)  or 
				x.email = CONCAT(@userFacturadorWindow,@domainDestino)  or 
				x.email = CONCAT(@userSupervisor,@domainDestino)  	
	) as dd on dd.email = replace(u.email ,@domainOrigen,'')
where		
	u.email = CONCAT(@userAdministradorWeb,@domainOrigen)  or 
	u.email = CONCAT(@userAdministradorMobile,@domainOrigen)  or 
	u.email = CONCAT(@userAdministradorWindow,@domainOrigen)  or 
	u.email = CONCAT(@userFacturadorWeb,@domainOrigen)  or 
	u.email = CONCAT(@userFacturadorMobile,@domainOrigen)  or 
	u.email = CONCAT(@userFacturadorWindow,@domainOrigen)  or 
	u.email = CONCAT(@userSupervisor,@domainOrigen) ;


#INSERTAR BODEGAS 
insert into tb_user_warehouse (companyID,branchID,userID,warehouseID)
select 
  m.companyID,
	m.branchID,
	dd.userID,
	m.warehouseID	
from 
	tb_user_warehouse m 
	inner join tb_user u on m.userID = u.userID  	
	inner join (
			select 
				x.userID,
				replace(x.email ,@domainDestino,'') as email
			from 
				tb_user x 
			where 
				x.email = CONCAT(@userAdministradorWeb,@domainDestino)  or 
				x.email = CONCAT(@userAdministradorMobile,@domainDestino)  or 
				x.email = CONCAT(@userAdministradorWindow,@domainDestino)  or 
				x.email = CONCAT(@userFacturadorWeb,@domainDestino)  or 
				x.email = CONCAT(@userFacturadorMobile,@domainDestino)  or 
				x.email = CONCAT(@userFacturadorWindow,@domainDestino)  or 
				x.email = CONCAT(@userSupervisor,@domainDestino)  	
	) as dd on dd.email = replace(u.email ,@domainOrigen,'')
where		
	u.email = CONCAT(@userAdministradorWeb,@domainOrigen)  or 
	u.email = CONCAT(@userAdministradorMobile,@domainOrigen)  or 
	u.email = CONCAT(@userAdministradorWindow,@domainOrigen)  or 
	u.email = CONCAT(@userFacturadorWeb,@domainOrigen)  or 
	u.email = CONCAT(@userFacturadorMobile,@domainOrigen)  or 
	u.email = CONCAT(@userFacturadorWindow,@domainOrigen)  or 
	u.email = CONCAT(@userSupervisor,@domainOrigen) ;


#INSERTAR CATALOGO DE MESA 
set @flavorID := (select MAX(u.userID) from tb_user u );
INSERT INTO `tb_public_catalog` (
	`name`, `systemName`, `statusID`, `orden`, `description`, `isActive`, `flavorID`
) 
VALUES (
	'Catalogo de mesas por meseros', 'tb_transaction_master_billing.mesas_x_meseros', 
	118, 1, 'Catalogo de mesas por meseros', b'1', 
	@flavorID
);
set @publicCatalogIDMax := (select max(k.publicCatalogID) from tb_public_catalog k);
INSERT INTO `tb_public_catalog_detail` (
	`publicCatalogID`, `name`, `display`, `flavorID`, 
	`description`, `sequence`, `parentCatalogDetailID`, `ratio`, `parentName`, `isActive`, 
	`reference1`, `reference2`, `reference3`, `reference4`, `reference5`, `reference6`, 
	`reference7`, `reference8`, `reference9`, `reference10`, `reference11`, `reference12`, 
	`reference13`, `reference14`, `reference15`, `reference16`, `reference17`, `reference18`, 
	`reference19`, `reference20`, `reference21`, `reference22`, `reference23`, 
	`refecence24`, `reference25`
) VALUES (
	@publicCatalogIDMax, 'abc', 'Mesa 1', 0, '1', '6', 0, '7', '5', '1', '2', '4', '3', 'c', 
	NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 
	NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL
);

select c.userID as flavorID from tb_user c where c.isActive = 1 order by userID desc limit 1;
select * from tb_user c where c.isActive = 1 order by userID desc;
select * from tb_role c where c.isActive = 1 order by roleID desc;