<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Center_Cost_Model extends Model  {
   function __construct(){		
      parent::__construct();
   } 
  function delete_app_posme($companyID,$classID){
		$db 		= db_connect();
		$builder	= $db->table("tb_center_cost");		
  		$data["isActive"] = 0;
		
		$builder->where("companyID",$companyID);
		$builder->where("classID",$classID);	
		return $builder->update($data);
		
   } 
   function update_app_posme($companyID,$classID,$data){
		$db 		= db_connect();
		$builder	= $db->table("tb_center_cost");		
		
		$builder->where("companyID",$companyID);
		$builder->where("classID",$classID);	
		return $builder->update($data);
		
   }
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_center_cost");	
		$result 	= $builder->insert($data);
		return $db->insertID();		
		
		 
   }
   function getByClassNumber($classNumber,$companyID){
		$db 		= db_connect();
		
		$sql = "";
		$sql = sprintf("select classID,companyID,accountLevelID,parentAccountID,parentClassID,number,description,isActive,createdBy,createdOn,createdIn,createdAt");
		$sql = $sql.sprintf(" from tb_center_cost");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and number = '$classNumber' ");
		$sql = $sql.sprintf(" and isActive = 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByPK($companyID,$classID){
		$db 	= db_connect();
		    
		$sql = "";
		$sql = sprintf("select classID,companyID,accountLevelID,parentAccountID,parentClassID,number,description,isActive,createdBy,createdOn,createdIn,createdAt");
		$sql = $sql.sprintf(" from tb_center_cost");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and classID = $classID");
		$sql = $sql.sprintf(" and isActive = 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function getByCompany($companyID){
		$db 		= db_connect();
		
		$sql = "";
		$sql = sprintf("select classID,companyID,accountLevelID,parentAccountID,parentClassID,number,description,isActive,createdBy,createdOn,createdIn,createdAt");
		$sql = $sql.sprintf(" from tb_center_cost");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and isActive = 1");
		$sql = $sql.sprintf(" order by number asc ");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
}
?>