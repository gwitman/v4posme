<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;

class Role_Autorization_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function deleteByRole($companyID,$branchID,$roleID){		
		$db 		= db_connect();
		$builder 	= $db->table("tb_role_autorization");
		
   	    
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);
		$builder->where("roleID",$roleID);
		
   	    $result 	=  $builder->delete();
		return $result;
		
   }
   function delete_app_posme($companyID,$branchID,$roleID,$componentAutorizationID){
		$db 		= db_connect();
		$builder 	= $db->table("tb_role_autorization");
		
   	    
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);
		$builder->where("roleID",$roleID);
		$builder->where("componentAutorizationID",$componentAutorizationID);
		
		
   	    $result 	=  $builder->delete();
		return $result;
   }
   function insert_app_posme($obj){
		$db 		= db_connect();
		$builder 	= $db->table("tb_role_autorization");
		
		$data["companyID"] 					= $obj["companyID"];		
		$data["branchID"] 					= $obj["branchID"];
		$data["roleID"] 					= $obj["roleID"];
		$data["componentAutorizationID"] 	= $obj["componentAutorizationID"];
		
		$result = $builder->insert($data);		
		return $result;
		
		 
   }
   function get_rowByRoleAutorization($companyID,$branchID,$roleID){
		$db 	= db_connect();
   		$sql = "";
		$sql = sprintf("select ra.companyID,ra.branchID,ra.roleID,ra.componentAutorizationID,ca.name");
		$sql = $sql.sprintf(" from tb_role_autorization ra");
		$sql = $sql.sprintf(" inner join  tb_component_autorization ca  on ra.companyID = ca.companyID and ra.componentAutorizationID = ca.componentAutorizationID");		
		$sql = $sql.sprintf(" where ra.companyID = $companyID");
		$sql = $sql.sprintf(" and ra.branchID = $branchID");
		$sql = $sql.sprintf(" and ra.roleID = $roleID");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByRole($companyID,$branchID,$roleID){
		$db 	= db_connect();
   		$sql = "";
		$sql = sprintf("select ra.companyID,ra.branchID,ra.roleID,cad.componentAutorizationID,cad.componentID,cad.workflowID,cad.workflowStageID");
		$sql = $sql.sprintf(" from tb_role_autorization ra");
		$sql = $sql.sprintf(" inner join  tb_component_autorization ca on ra.companyID = ca.companyID and ra.componentAutorizationID = ca.componentAutorizationID");
		$sql = $sql.sprintf(" inner join  tb_component_autorization_detail cad on ca.companyID = cad.companyID and ca.componentAutorizationID = cad.componentAutorizationID");
		$sql = $sql.sprintf(" where ra.companyID = $companyID");
		$sql = $sql.sprintf(" and ra.branchID = $branchID");
		$sql = $sql.sprintf(" and ra.roleID = $roleID");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByPK($companyID,$branchID,$roleID,$componentAutorizationID){
		$db 	= db_connect();    
		$sql = "";
		
		$sql = sprintf("select ra.companyID,ra.branchID,ra.roleID,cad.componentAutorizationID,cad.componentID,cad.workflowID,cad.workflowStageID");
		$sql = $sql.sprintf(" from tb_role_autorization ra");
		$sql = $sql.sprintf(" inner join  tb_component_autorization ca on ra.companyID = ca.companyID and ra.componentAutorizationID = ca.componentAutorizationID");
		$sql = $sql.sprintf(" inner join  tb_component_autorization_detail cad on ca.companyID = cad.companyID and ca.componentAutorizationID = cad.componentAutorizationID");
		$sql = $sql.sprintf(" where ra.companyID = $companyID");
		$sql = $sql.sprintf(" and ra.branchID = $branchID");
		$sql = $sql.sprintf(" and ra.roleID = $roleID");
		$sql = $sql.sprintf(" and ra.componentAutorizationID = $componentAutorizationID");						
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
}
?>