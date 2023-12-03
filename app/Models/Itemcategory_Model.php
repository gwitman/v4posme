<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class ItemCategory_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   function delete_app_posme($companyID,$itemCategoryID){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_category");
		$data["isActive"] = 0;
		
		$builder->where("companyID",$companyID);
		$builder->where("inventoryCategoryID",$itemCategoryID);	
		return $builder->update($data);
		
   }
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_category");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }
   function update_app_posme($companyID,$itemCategoryID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_category");
		
		$builder->where("companyID",$companyID);
		$builder->where("inventoryCategoryID",$itemCategoryID);	
		return $builder->update($data);
		
   }
   function getByCompany($companyID){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_category");
		
		$sql = "";
		$sql = sprintf("select companyID,branchID,inventoryCategoryID,name,description,isActive");
		$sql = $sql.sprintf(" from tb_item_category");
		$sql = $sql.sprintf(" where companyID = $companyID");		
		$sql = $sql.sprintf(" and isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function getByPK($companyID,$itemCategoryID){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_category");
		
		$sql = "";
		$sql = sprintf("select companyID,branchID,inventoryCategoryID,name,description,isActive");
		$sql = $sql.sprintf(" from tb_item_category");
		$sql = $sql.sprintf(" where companyID = $companyID");		
		$sql = $sql.sprintf(" and isActive= 1");		
		$sql = $sql.sprintf(" and inventoryCategoryID = $itemCategoryID");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
}
?>