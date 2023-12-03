<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Component_Period_Model extends Model  {
   function __construct(){	
      parent::__construct();
   } 
  function delete_app_posme($companyID,$componentID,$componentPeriodID){
		$db 		= db_connect();
		$builder	= $db->table("tb_accounting_period");		
		
		$obj["isActive"] = 0;
  		$builder->where("companyID",$companyID);
		$builder->where("componentID",$componentID);
		$builder->where("componentPeriodID",$componentPeriodID);
		return $builder->update($obj);
		
   } 
   function update_app_posme($companyID,$componentID,$componentPeriodID,$obj){
		$db 		= db_connect();
		$builder	= $db->table("tb_accounting_period");		
		
		$builder->where("companyID",$companyID);
		$builder->where("componentID",$componentID);
		$builder->where("componentPeriodID",$componentPeriodID);	
		return $builder->update($obj);
		
   }
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_accounting_period");			
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }
   function validateTime($companyID,$componentID,$dateStart,$dateEnd){
		$db 	= db_connect();
		
		
		$sql = "
		SELECT
			componentPeriodID,companyID,componentID,number,name,description,startOn,endOn,statusID,isActive,createdBy,createdOn,createdIn,createdAt 
		FROM
			tb_accounting_period
		WHERE					
			isActive 	= 1 and
			companyID	= $companyID  and 
			componentID = $componentID and (
			'$dateStart' between  startOn and endOn  or   
			'$dateEnd'   between  startOn and endOn ) 
		ORDER BY 
			startOn
		";
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByCompanyIDFecha($companyID,$dateStart)
   {
		$sql = "
		SELECT
			componentPeriodID, companyID, componentID, number, name, description, startOn, endOn, statusID, isActive, createdBy, createdOn, createdAt, createdIn
		FROM
			tb_accounting_period
		WHERE					
			isActive 	= 1 and
			companyID	= $companyID  and 			
			'$dateStart' between  startOn and endOn
		";
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
		
   }
   function get_countPeriod($companyID){
		$db 		= db_connect();
		
		
		$sql = "";
		$sql = $sql.sprintf(" select count(*) as counter ");
		$sql = $sql.sprintf(" from tb_accounting_period");
		$sql = $sql.sprintf(" where isActive = 1");
		$sql = $sql.sprintf(" and companyID = $companyID");
		
   		return $db->query($sql)->getRow()->counter;
   }
   function get_rowByPK($componentPeriodID){
		$db 	= db_connect();
		    
		$sql = "";
		$sql = sprintf("select componentPeriodID,companyID,componentID,number,name,description,startOn,endOn,statusID,isActive,createdBy,createdOn,createdIn,createdAt");
		$sql = $sql.sprintf(" from tb_accounting_period");		
		$sql = $sql.sprintf(" where componentPeriodID = $componentPeriodID");
		$sql = $sql.sprintf(" and isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByNotClosed($companyID,$workflowStageClosed){
		$db 	= db_connect();
		
		
		$sql = "
		SELECT
			componentPeriodID,companyID,componentID,number,name,description,startOn,endOn,statusID,isActive,createdBy,createdOn,createdAt,createdIn
		FROM
			tb_accounting_period
		WHERE					
			isActive 	= 1 and
			companyID	= $companyID and 
			statusID    != $workflowStageClosed 
		ORDER BY
			startOn 
		";
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   function get_rowByCompany($companyID){
		$db 	= db_connect();
		
		$sql = " 
		SELECT
			componentPeriodID,companyID,componentID,number,name,description,startOn,endOn,statusID,isActive,createdBy,createdOn,createdAt,createdIn
		FROM
			tb_accounting_period
		WHERE					
			isActive 	= 1 and
			companyID	= $companyID 
		ORDER BY
			startOn 
		";
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   
   function countJournalInPeriod($periodID,$companyID){
		$db 	= db_connect();
		
		
		$sql = "
		SELECT
			COUNT(*) AS count_
		FROM
			tb_journal_entry je
			inner join tb_accounting_cycle ce on 
				je.accountingCycleID = ce.componentCycleID 
		WHERE					
			je.isActive 	= 1 and
			je.companyID	= $companyID and
			ce.componentPeriodID = $periodID 
		";
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow()->count_;
   }
}
?>