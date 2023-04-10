/*
select 
  me.menuElementID,
	co.`name`,
	e.`name`,
	e.elementTypeID,
	me.orden,
	me.display as menu,
	parente.display as parent ,
	parente.menuElementID
from 
	tb_element e 
	inner join tb_menu_element me on 
		e.elementID = me.elementID 
	inner join tb_component_element ce on 
		e.elementID = ce.elementID 
	inner join tb_component co on 
		co.componentID = ce.componentID 
	inner join tb_menu_element as parente on 	
		parente.menuElementID = me.parentMenuElementID 
ORDER BY 
		me.orden ;
*/


set @menuComponentPrincipal   		:= '0-TRANSACCIONES';
set @menuElement 					:= 'EXA. LAB';
set @menuElementAddress 			:= 'app_lab_examen/index.aspx';
set @menuElementParent				:= 'TRANSA';
set @menuElementOrden 			  	:= '0002.0004.0002.0000.0000';

set @menuComponentPrincipalID := IFNULL((select u.componentID from tb_component u where u.name = @menuComponentPrincipal),0);
set @menuElementID 					  := IFNULL((select u.elementID from tb_element u where u.name = @menuElement),0);
set @menuElementIDParent		  := IFNULL((select u.elementID from tb_element u where u.name = @menuElementParent),0);


/*--ingreasr elemento*/
insert into tb_element (elementTypeID,`name`) 
select 
	temp.elementTypeID2,temp.name2 
from 
	(select 1 /*pagina*/ as elementTypeID2, @menuElement as name2) AS temp 
where
	temp.name2 not in (select u.`name` from tb_element u );
	
/*asociar elemento al componente*/
set @menuElementID 					  := IFNULL((select u.elementID from tb_element u where u.name = @menuElement),0);
insert ignore into tb_component_element (componentID,elementID) 
values (@menuComponentPrincipalID,@menuElementID);  

/*insertar elemento del menu*/
insert into tb_menu_element (
	companyID,
	elementID,parentMenuElementID,
	display,address,orden,icon,
	template,nivel,typeMenuElementID,
	isActive
)
select 
	* 
from 
	(
		select 
			2 as companyID,
			@menuElementID as elementID,@menuElementIDParent  as parentMenuElementID ,
			@menuElement as display, @menuElementAddress as address,@menuElementOrden as orden,'i-stairs' as icon,
			'menu_nivel_2' as template,1 as nivel, 5 as typeMenuElementID,
			1 as active
	) as tempx 
where
	tempx.elementID not in (select u.elementID from tb_menu_element u);
