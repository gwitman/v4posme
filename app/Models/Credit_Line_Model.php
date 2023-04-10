<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Credit_Line_Model extends Model  {
   function __construct(){
	
      parent::__construct();
   }
   function update_app_posme($companyID,$creditLineID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_credit_line");
		
		$builder->where("companyID",$companyID);
		$builder->where("creditLineID",$creditLineID);
		return $builder->update($data);
		
   }
   function delete_app_posme($companyID,$creditLineID){
		$db 		= db_connect();
		$builder	= $db->table("tb_credit_line");		
		
  		$data["isActive"] = 0;
		$builder->where("companyID",$companyID);
		$builder->where("creditLineID",$creditLineID);	
		return $builder->update($data);
		
   } 
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_credit_line");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }   
   function get_rowByCompany($companyID){
		$db 	= db_connect();
		    
		$sql = "";
		$sql = sprintf("select companyID, creditLineID,name,description,isActive");
		$sql = $sql.sprintf(" from tb_credit_line i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByPK($companyID,$creditLineID){
		$db 	= db_connect();
		    
		$sql = "";
		$sql = sprintf("select companyID, creditLineID,name,description,isActive");
		$sql = $sql.sprintf(" from tb_credit_line i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.creditLineID = $creditLineID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
}
?>