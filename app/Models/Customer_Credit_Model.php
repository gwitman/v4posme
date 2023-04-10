<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Customer_Credit_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function update_app_posme($companyID,$branchID,$entityID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit");
		
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);	
		$builder->where("entityID",$entityID);	
		return $builder->update($data);
		
   }
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit");
		$result = $builder->insert($data);
		return $result;
   }   
   function get_rowByPK($companyID,$branchID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit");    
		
		$sql = "";
		$sql = sprintf("select companyID, branchID, entityID, limitCreditDol, balanceDol, incomeDol");
		$sql = $sql.sprintf(" from tb_customer_credit i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.branchID = $branchID");
		$sql = $sql.sprintf(" and i.entityID = $entityID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
}
?>