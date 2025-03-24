<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Remember_Model extends Model  {
   function __construct(){		
      parent::__construct();
   } 
  function delete_app_posme($rememberID){
		$db 	= db_connect();
		$builder	= $db->table("tb_remember");		
  		$data["isActive"] = 0;
		
		$builder->where("rememberID",$rememberID);
		return $builder->update($data);
		
   } 
   function update_app_posme($rememberID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_remember");		
		
		$builder->where("rememberID",$rememberID);
		return $builder->update($data);
		
   }
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_remember");	
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }
   function get_rowByPK($rememberID){
		$db 	= db_connect();
		    
		$sql = "";
		$sql = sprintf("select 
						companyID,
						rememberID,
						title,
						description,
						period,
						day,
						statusID,
						lastNotificationOn,
						isTemporal,
						createdBy,
						createdOn,
						createdIn,
						createdAt,
						isActive,
						tagID,
						leerFile,
						'' as entidad,
						'' as nombre
				");
		$sql = $sql.sprintf(" from tb_remember");
		$sql = $sql.sprintf(" where rememberID = $rememberID");
		$sql = $sql.sprintf(" and isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
	function getByCompany($companyID){
		$db 	= db_connect();
		
		$sql = "";
		$sql = sprintf("select companyID,rememberID,title,description,period,day,statusID,lastNotificationOn,isTemporal,createdBy,createdOn,createdIn,createdAt,isActive,tagID,leerFile");
		$sql = $sql.sprintf(" from tb_remember");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and isActive= 1");			
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	}
	function getNotificationCompanyByTagId($companyID,$tagID)
	{
		$db 	= db_connect();
		$sql = "
		select			
			c.companyID, 
			c.rememberID, 
			c.lastNotificationOn,
			c.day,
			c.tagID,
			c.leerFile
		from 
			tb_remember  c
			inner join tb_catalog_item ci on 
				c.period = ci.catalogItemID 
			inner join tb_workflow_stage ws on 
				c.statusID = ws.workflowStageID 
		where 
			c.isActive = 1 
			and ws.vinculable = 1  
			and c.companyID = $companyID  
			and c.tagID = $tagID 
		";
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	}
	function getNotificationCompany($companyID)
	{
		$db 	= db_connect();
		$sql = "
		select			
			c.companyID, 
			c.rememberID, 
			c.lastNotificationOn,
			c.day,
			c.tagID,
			c.leerFile 
		from 
			tb_remember  c
			inner join tb_catalog_item ci on 
				c.period = ci.catalogItemID 
			inner join tb_workflow_stage ws on 
				c.statusID = ws.workflowStageID 
		where 
			c.isActive = 1 
			and ws.vinculable = 1  
			and c.companyID = $companyID  
		";
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
	}
	function getProcessNotification($rememberID,$fechaProcess){
		$db 	= db_connect();
		
		
		$sql = "
		select
			
			case 
				when ci.sequence = 30 then 
					dayofmonth('".$fechaProcess."') 
				when ci.sequence = 15 then 
					case 
						when dayofmonth('".$fechaProcess."') <= 15 then 
							dayofmonth('".$fechaProcess."') 
						else 
							dayofmonth('".$fechaProcess."') - 15
					end
				when ci.sequence = 7 then 
					dayofweek('".$fechaProcess."') 
				when  ci.sequence = 365 then 
					dayofyear('".$fechaProcess."') 
				else 
					0
			end diaProcesado,
			'".$fechaProcess."' as Fecha,
			c.title,
			c.description,
			c.tagID,
			c.leerFile 
		from 
			tb_remember c
			inner join tb_catalog_item ci on 
				c.period = ci.catalogItemID 
		where 
			c.rememberID = ".$rememberID." 
		";
	
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
		
	}

    function getProgrammingByDate($date)
    {
        $db 	= db_connect();
        $sql = "
		select
            CONCAT('REM','',r.rememberID) as rememberID,
            '' as url,
            r.title,
            r.description,	
            r.createdOn,
            r.tagID,
			'yellow' as color,
			'' as entidad,
			'' as nombre
        from 
            tb_remember r 
            inner join tb_workflow_stage sr on 
                sr.workflowStageID = r.statusID 
        where 
            r.companyID = 2 and 
            r.isActive= 1 and 
            sr.isInit = 1 and 
			r.createdOn = ? 
        
        union all 
        
		
		/*FACTURAS*/
        select 
            TM.transactionNumber as rememberID,	
            CONCAT('".base_url()."/app_invoice_billing/edit/transactionMasterIDToPrinter/0/companyID/2/transactionID/19/transactionMasterID/',TM.transactionMasterID,'/codigoMesero/none') as url,
            'PROFORMA' AS title,
            tm.note AS description,	
            tm.nextVisit AS createdOn,
            0  AS tagID,
			'yellow' as color,
			emp.customerNumber  as entidad,
			nat.firstName as nombre
        from 
            tb_transaction_master tm 
            inner join tb_workflow_stage st on 
                tm.statusID = st.workflowStageID 
			inner join tb_naturales nat on 
				nat.entityID = tm.entityID  
			inner join tb_customer emp on 
				emp.entityID = nat.entityID 
        where 
            tm.isActive = 1 and 
            st.isInit = 1 and 
            tm.transactionID in ( 19 /*FAC*/  ) and 
            tm.nextVisit = ?
			
		union all 
		/*CONSULTAS MEDICAS*/
		select 
            TM.transactionNumber as rememberID,	
            CONCAT('".base_url()."/app_med_query/edit/companyID/2/transactionID/35/transactionMasterID/',TM.transactionMasterID) as url,
            'CONSULTAS' AS title,
            tm.note AS description,	
            tm.nextVisit AS createdOn,
            0  AS tagID,
			'yellow' as color,
			emp.customerNumber  as entidad,
			nat.firstName as nombre
        from 
            tb_transaction_master tm 
            inner join tb_workflow_stage st on 
                tm.statusID = st.workflowStageID 
			inner join tb_naturales nat on 
				nat.entityID = tm.entityID  
			inner join tb_customer emp on 
				emp.entityID = nat.entityID 
        where 
            tm.isActive = 1 and 
            st.isInit = 1 and 
            tm.transactionID in ( 35 /*CONSULTAS MEDICAS*/  ) and 
            tm.nextVisit = ?
			
			
		union all 
		/*TAREAS TASK*/
		select 
            TM.transactionNumber as rememberID,	
            CONCAT('".base_url()."/app_rrhh_task/edit/companyID/2/transactionID/44/transactionMasterID/',TM.transactionMasterID) as url,
            'TASK' AS title,
            tm.reference4 AS description,	
            tm.nextVisit AS createdOn,
            0  AS tagID,
			'yellow' as color,
			emp.employeNumber  as entidad,
			nat.firstName as nombre
        from 
            tb_transaction_master tm 
            inner join tb_workflow_stage st on 
                tm.statusID = st.workflowStageID 
			inner join tb_naturales nat on 
				nat.entityID = tm.entityIDSecondary 
			inner join tb_employee emp on 
				emp.entityID = nat.entityID 
        where 
            tm.isActive = 1 and 
            st.isInit = 1 and 
            tm.transactionID in ( 44 /*TAREAS*/  ) and 
            tm.nextVisit = ?
		";

        //Ejecutar Consulta
        return $db->query($sql, [$date,$date,$date,$date])->getResult();
    }

    function getProgramming()
    {
        $db 	= db_connect();
        $sql = "
		select
            CONCAT('REM','',r.rememberID) as rememberID,
            '' as url,
            r.title,
            r.description,	
            r.createdOn,
            r.tagID,
			'blue' as color
        from 
            tb_remember r 
            inner join tb_workflow_stage sr on 
                sr.workflowStageID = r.statusID 
        where 
            r.companyID = 2 and 
            r.isActive= 1 and 
            sr.isInit = 1 
        
        union all 
        
		
		/*FACTURAS*/
        select 
            TM.transactionNumber as rememberID,	
            CONCAT('".base_url()."/app_invoice_billing/edit/transactionMasterIDToPrinter/0/companyID/2/transactionID/19/transactionMasterID/',TM.transactionMasterID,'/codigoMesero/none') as url,
            'PROFORMA' AS title,
            tm.note AS description,	
            tm.nextVisit AS createdOn,
            0  AS tagID,
			'green' as color
        from 
            tb_transaction_master tm 
            inner join tb_workflow_stage st on 
                tm.statusID = st.workflowStageID 
        where 
            tm.isActive = 1 and 
            st.isInit = 1 and 
            tm.transactionID in ( 19 /*FAC*/  ) and 
            tm.nextVisit != '0000-00-00'
			
		union all 
		/*CONSULTAS MEDICAS*/
		select 
            TM.transactionNumber as rememberID,	
            CONCAT('".base_url()."/app_med_query/edit/companyID/2/transactionID/35/transactionMasterID/',TM.transactionMasterID) as url,
            'CONSULTAS' AS title,
            tm.note AS description,	
            tm.nextVisit AS createdOn,
            0  AS tagID,
			'red' as color
        from 
            tb_transaction_master tm 
            inner join tb_workflow_stage st on 
                tm.statusID = st.workflowStageID 
        where 
            tm.isActive = 1 and 
            st.isInit = 1 and 
            tm.transactionID in ( 35 /*CONSULTAS MEDICAS*/  ) and 
            tm.nextVisit != '0000-00-00'
			
			
		union all 
		/*TAREAS TASK*/
		select 
            TM.transactionNumber as rememberID,	
            CONCAT('".base_url()."/app_rrhh_task/edit/companyID/2/transactionID/44/transactionMasterID/',TM.transactionMasterID) as url,
            'TASK' AS title,
            tm.note AS description,	
            tm.nextVisit AS createdOn,
            0  AS tagID,
			'yellow' as color
        from 
            tb_transaction_master tm 
            inner join tb_workflow_stage st on 
                tm.statusID = st.workflowStageID 
        where 
            tm.isActive = 1 and 
            st.isInit = 1 and 
            tm.transactionID in ( 44 /*TAREAS*/  ) and 
            tm.nextVisit != '0000-00-00'
			
		";

        //Ejecutar Consulta
        return $db->query($sql)->getResult();
    }
}
?>