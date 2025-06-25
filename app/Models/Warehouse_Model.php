<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;


class Warehouse_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   function delete_app_posme($companyID,$warehouseID){
		$db 	= db_connect();
		$builder	= $db->table("tb_warehouse");		
  		$data["isActive"] = 0;
		
		$builder->where("companyID",$companyID);
		$builder->where("warehouseID",$warehouseID);	
		return $builder->update($data);
		
   }
   function update_app_posme($companyID,$branchID,$warehouseID,$data){
		$db 		= db_connect();
		$builder	= $db->table("tb_warehouse");
		
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);
		$builder->where("warehouseID",$warehouseID);
		return $builder->update($data);
		
   }
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_warehouse");	
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }
   function getByCode($companyID,$code){
		$db 		= db_connect();
		
		
		$sql = "";
		$sql = sprintf("select companyID, warehouseID, branchID, number, name, statusID, isActive, typeWarehouse,emailResponsability");
		$sql = $sql.sprintf(" from tb_warehouse");
		$sql = $sql.sprintf(" where companyID = $companyID");		
		$sql = $sql.sprintf(" and isActive= 1");		
		$sql = $sql.sprintf(" and number = '$code' ");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function getByEmailContainsString($companyID,$key){
		$db 		= db_connect();
		
		
		$sql = "";
		$sql = sprintf("select companyID, warehouseID, branchID, number, name, statusID, isActive, typeWarehouse,emailResponsability");
		$sql = $sql.sprintf(" from tb_warehouse ");
		$sql = $sql.sprintf(" where companyID = $companyID");		
		$sql = $sql.sprintf(" and isActive= 1");		
		$sql = $sql." and emailResponsability LIKE '%".$key."' ";	
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByPK($companyID,$warehouseID){
		$db 	= db_connect();
		
		
		$sql = "";
		$sql = sprintf("select companyID, warehouseID, branchID, number, name, statusID, isActive,address,typeWarehouse,emailResponsability");
		$sql = $sql.sprintf(" from tb_warehouse");
		$sql = $sql.sprintf(" where companyID = $companyID");		
		$sql = $sql.sprintf(" and isActive= 1");		
		$sql = $sql.sprintf(" and warehouseID = $warehouseID");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function getByCompany($companyID){
		$db 	= db_connect();
		
		
		$sql = "";
		$sql = sprintf("select companyID, warehouseID, branchID, number, name, statusID, isActive,typeWarehouse,emailResponsability");
		$sql = $sql.sprintf(" from tb_warehouse");
		$sql = $sql.sprintf(" where companyID = $companyID");		
		$sql = $sql.sprintf(" and isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
}
?>