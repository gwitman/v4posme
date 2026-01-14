<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Role_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }  
   function update_app_posme($companyID,$branchID,$roleID,$obj){
		$db 		= db_connect();
		$builder	= $db->table("tb_role");
		
		$data["name"] 			= $obj["name"];
		$data["description"] 	= $obj["description"];
		$data["urlDefault"] 	= $obj["urlDefault"];
		$data["isAdmin"] 		= $obj["isAdmin"];		
		$data["isActive"] 		= $obj["isActive"];   
		
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);
		$builder->where("roleID",$roleID);
		
		
		$result			 		= $builder->update($data);
		return $result; 
   }
   function insert_app_posme($obj){
		$db 		= db_connect();
		$builder	= $db->table("tb_role");
		
		$data["companyID"] 		= $obj["companyID"];		
		$data["branchID"] 		= $obj["branchID"];
		$data["name"] 			= $obj["name"];
		$data["description"] 	= $obj["description"];
		$data["urlDefault"] 	= $obj["urlDefault"];
		$data["isAdmin"] 		= $obj["isAdmin"];
		$data["createdOn"] 		= date("Y-m-d H:i:s");
		$data["createdBy"] 		= $obj["createdBy"];
		$data["isActive"] 		= $obj["isActive"];
		$data["typeApp"] 		= $obj["typeApp"];
		
		$result			 		= $builder->insert($data);
		$roleID 				= $db->insertID(); 		
		return $roleID;
		 
   }
   function get_rowByCompanyIDyBranchID($companyID,$branchID){
		$db 	= db_connect();    
		$sql = "";
		$sql = sprintf("select companyID,branchID,roleID,name,description,isAdmin,createdOn,isActive,urlDefault,createdBy,
						typeApp ");
		$sql = $sql.sprintf(" from tb_role");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and branchID = $branchID");
		$sql = $sql.sprintf(" and isActive= 1");		
		$sql = $sql.sprintf(" and isAdmin= 0");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByPK($companyID,$branchID,$roleID){
		$db 	= db_connect();    
		$sql = "";
		$sql = sprintf("select companyID,branchID,roleID,name,description,isAdmin,createdOn,isActive,urlDefault,createdBy,
						typeApp ");
		$sql = $sql.sprintf(" from tb_role");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and branchID = $branchID"); 
		$sql = $sql.sprintf(" and roleID = $roleID");
		$sql = $sql.sprintf(" and isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
}
?>