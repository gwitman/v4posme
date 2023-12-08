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
		$sql = sprintf("select companyID,rememberID,title,description,period,day,statusID,lastNotificationOn,isTemporal,createdBy,createdOn,createdIn,createdAt,isActive,tagID");
		$sql = $sql.sprintf(" from tb_remember");
		$sql = $sql.sprintf(" where rememberID = $rememberID");
		$sql = $sql.sprintf(" and isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
	function getByCompany($companyID){
		$db 	= db_connect();
		
		$sql = "";
		$sql = sprintf("select companyID,rememberID,title,description,period,day,statusID,lastNotificationOn,isTemporal,createdBy,createdOn,createdIn,createdAt,isActive,tagID");
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
			c.tagID
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
			c.tagID
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
			c.tagID
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
	
}
?>