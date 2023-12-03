<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Component_Cycle_Model extends Model  {
   function __construct(){	
      parent::__construct();
   } 
  function delete_app_posme($componentCycleID){
		$db 		= db_connect();
		$builder	= $db->table("tb_accounting_cycle");		
		
		$data["isActive"] = 0;
  		$builder->where("componentCycleID",$componentCycleID);
		return $builder->update($data);
		
   }
   function deleteNotInArray($companyID,$componentID,$componentPeriodID,$Array){
		$db 		= db_connect();
		$builder	= $db->table("tb_accounting_cycle");	
		
		$data["isActive"] = 0;
  		$builder->where("companyID",$companyID);	
		$builder->where("componentID",$componentID);	
		$builder->where("componentPeriodID",$componentPeriodID);	
		$builder->whereNotIn("componentCycleID",$Array);	
		return $builder->update($data);
		
   }   
   function update_app_posme($componentCycleID,$data){
		$db 		= db_connect();
		$builder	= $db->table("tb_accounting_cycle");				
		
		$builder->where("componentCycleID",$componentCycleID);	
		return $builder->update($data);
		
   }
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_accounting_cycle");	
		$result 	= $builder->insert($data);
		return $db->insertID();		
		 
   }
   function getByComponentPeriodID($componentPeriodID){
		$db 	= db_connect();
		
	   $sql = "
	   SELECT 
			componentCycleID,componentPeriodID,companyID,componentID,number,name,description,
			startOn,endOn,
			DATE_FORMAT( startOn,\"%Y-%M-%d\") as startOnFormat,
			DATE_FORMAT( endOn,\"%Y-%M-%d\") as endOnFormat,
			statusID,isActive,createdBy,createdOn,createdAt,createdIn
		FROM 
			tb_accounting_cycle i 
		WHERE 
			i.isActive = 1 AND 
			i.componentPeriodID = $componentPeriodID 
		ORDER BY 
			startOn 
		";
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   function get_rowByNotClosed($companyID,$componentPeriodID,$workflowStageClosed){
		$db 		= db_connect();
		
		$sql = "
		SELECT
			componentCycleID,componentPeriodID,companyID,componentID,number,name,description,
			startOn,endOn,
			DATE_FORMAT(startOn,\"%Y-%M-%d\") as startOnFormat,DATE_FORMAT(endOn,\"%Y-%M-%d\") as endOnFormat,
			statusID,isActive,createdBy,createdOn,createdAt,createdIn
		FROM
			tb_accounting_cycle
		WHERE					
			isActive 			= 1 and 
			companyID			= $companyID and  
			statusID 			!= $workflowStageClosed and  
			componentPeriodID 	= $componentPeriodID  
		ORDER BY
			startOn
		";
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   
   function get_rowByCompanyIDFecha($companyID,$dateStart){
		$db 	= db_connect();
		
		$sql = "
		SELECT
			componentCycleID,componentPeriodID,companyID,componentID,number,name,description,startOn,endOn,statusID,isActive,createdBy,createdOn,createdAt,createdIn
		FROM
			tb_accounting_cycle
		WHERE					
			isActive 	= 1 and
			companyID	= $companyID  and 			
			'$dateStart' between  startOn and endOn
		";
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
		
   }
   
   function get_rowByCompanyID_TopCycleOpenAscAndOpen($companyID,$top,$workflowStageClosed){
		$db 	= db_connect();
		
		$sql = "
		SELECT
			componentCycleID,componentPeriodID,companyID,componentID,number,name,description,startOn,endOn,statusID,isActive,createdBy,createdOn,createdAt,createdIn
		FROM
			tb_accounting_cycle
		WHERE					
			isActive 	= 1 and
			companyID	= $companyID and 
			statusID 	!= $workflowStageClosed 
		ORDER BY 
			componentCycleID 
		LIMIT 0,$top
		";
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   
   function get_rowByPK($periodID,$cycleID){
		$db 	= db_connect();
		    
		$sql = "
		SELECT
			componentCycleID,componentPeriodID,companyID,componentID,number,name,description,startOn,endOn,statusID,isActive,createdBy,createdOn,createdAt,createdIn
		FROM
			tb_accounting_cycle
		WHERE					
			isActive 	= 1 and
			componentPeriodID	= $periodID  and 			
			componentCycleID	= $cycleID  	
		";
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByCycleID($cycleID){
		$db 	= db_connect();
		    
		$sql = "
		SELECT
			componentCycleID,componentPeriodID,companyID,componentID,number,name,description,startOn,endOn,statusID,isActive,createdBy,createdOn,createdAt,createdIn
		FROM
			tb_accounting_cycle
		WHERE					
			isActive 	= 1 and 					
			componentCycleID	= $cycleID  	
		";
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByCycleNotIn($companyID,$componentID,$componentPeriodID,$Array){
		$db 		= db_connect();
		$builder	= $db->table("tb_accounting_cycle");		
		
		$builder->select("componentCycleID,componentPeriodID,companyID,componentID,number,name,description,startOn,endOn,statusID,isActive,createdBy,createdOn,createdAt,createdIn");
		$builder->where("companyID",$companyID);	
		$builder->where("componentID",$componentID);	
		$builder->where("componentPeriodID",$componentPeriodID);	
		$builder->where("isActive",1);	
		$builder->whereNotIn("componentCycleID",$Array);	
		
		
		//Ejecutar Consulta
		return $builder->get()->getResult();
		
		
   }
   function countJournalInCycle($cycleID,$companyID){
		$db 		= db_connect();
			

		$sql = "";
		$sql = $sql.sprintf("select count(*) as counter ");
		$sql = $sql.sprintf(" from tb_journal_entry je");
		$sql = $sql.sprintf(" where je.isActive = 1");
		$sql = $sql.sprintf(" and je.companyID = $companyID");
		$sql = $sql.sprintf(" and je.accountingCycleID = $cycleID");
   		return $db->query($sql)->getRow()->counter;
   }
}
?>