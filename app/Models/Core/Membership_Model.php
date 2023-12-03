<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Membership_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }  
   function delete_app_posme($companyID,$branchID,$userID){
		$db 		= db_connect();
		$builder	= $db->table("tb_membership");		
		
		$builder->where("companyID",$companyID);		
		$builder->where("branchID",$branchID);		
		$builder->where("userID",$userID);		
		
		$result = $builder->delete(); 
		return $result;  
   }
   function insert_app_posme($data){
		$db 		= db_connect();	
		$builder	= $db->table("tb_membership");		
		$result		= $builder->insert($data);
		$result		= $db->insertID();
		return $result; 
   }
   function get_rowByCompanyIDBranchIDUserID($companyID,$branchID,$userID){
		$db 	= db_connect();    
		$sql = "";
		$sql = sprintf("select companyID,branchID,userID,roleID");
		$sql = $sql.sprintf(" from tb_membership");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and branchID = $branchID");
		$sql = $sql.sprintf(" and userID = $userID");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
}
?>