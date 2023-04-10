<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Natural_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function update_app_posme($companyID,$branchID,$entityID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_naturales");
		
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);	
		$builder->where("entityID",$entityID);	
		return $builder->update($data);
		
   }
   function delete_app_posme($companyID,$branchID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_naturales");		
  		$data["isActive"] = 0;
		
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);	
		$builder->where("entityID",$entityID);	
		return $builder->update($data);
		
   } 
   function insert_app_posme($data){
		$db 	     = db_connect();
		$builder	 = $db->table("tb_naturales");
		$result	     = $builder->insert($data);
		return $result;
   }
   function get_rowByPK($companyID,$branchID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_naturales");    
		$sql = "";
		$sql = sprintf("select i.companyID,i.branchID,i.entityID,i.firstName,i.lastName,i.address,i.isActive,i.statusID,i.profesionID");
		$sql = $sql.sprintf(" from tb_naturales i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.branchID = $branchID");
		$sql = $sql.sprintf(" and i.entityID = $entityID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
}
?>