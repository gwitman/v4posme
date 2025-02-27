<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class UserWarehouse_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   function deleteByUser($companyID,$userID){
		$db 		= db_connect();
		$builder	= $db->table("tb_user_warehouse");	
		
		$builder->where("companyID",$companyID);
		$builder->where("userID",$userID);
		$builder->delete();
   }
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_user_warehouse");
		
		$result		= $builder->insert($data);
		return $result;
   }
   function getRowByUserIDAndFacturable($companyID,$userID)
   {
	   $db 			= db_connect();
		$builder	= $db->table("tb_user_warehouse");
		$sql = "";
		$sql = sprintf("select uw.companyID, uw.warehouseID, uw.branchID,uw.userID, w.number, w.name, w.statusID, w.isActive,w.typeWarehouse");
		$sql = $sql.sprintf(" from tb_user_warehouse uw");
		$sql = $sql.sprintf(" inner join  tb_warehouse w on uw.warehouseID = w.warehouseID");
		$sql = $sql.sprintf(" where uw.companyID = $companyID");		
		$sql = $sql.sprintf(" and uw.userID = $userID");		
		$sql = $sql.sprintf(" and w.isActive= 1");		
		$sql = $sql.sprintf(" and w.typeWarehouse = 480 /*480 : tipo despacho*/ ");	
		$sql = $sql.sprintf(" order by w.number ");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   function getRowByUserID($companyID,$userID){
		$db 		= db_connect();
		$builder	= $db->table("tb_user_warehouse");
		$sql = "";
		$sql = sprintf("select uw.companyID, uw.warehouseID, uw.branchID,uw.userID, w.number, w.name, w.statusID, w.isActive,w.typeWarehouse");
		$sql = $sql.sprintf(" from tb_user_warehouse uw");
		$sql = $sql.sprintf(" inner join  tb_warehouse w on uw.warehouseID = w.warehouseID");
		$sql = $sql.sprintf(" where uw.companyID = $companyID");		
		$sql = $sql.sprintf(" and uw.userID = $userID");		
		$sql = $sql.sprintf(" and w.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function getRowByBranchID($companyID,$branchID){
		$db 	= db_connect();
		$builder	= $db->table("tb_user_warehouse");
		$sql = "";
		$sql = sprintf("select uw.companyID, uw.warehouseID, uw.branchID,uw.userID, w.number, w.name, w.statusID, w.isActive,w.typeWarehouse");
		$sql = $sql.sprintf(" from tb_user_warehouse uw");
		$sql = $sql.sprintf(" inner join  tb_warehouse w on uw.warehouseID = w.warehouseID");
		$sql = $sql.sprintf(" where uw.companyID = $companyID");		
		$sql = $sql.sprintf(" and uw.branchID = $branchID");		
		$sql = $sql.sprintf(" and w.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function getRowByCompanyID($companyID){
		$db 	= db_connect();
		$builder	= $db->table("tb_user_warehouse");
		$sql = "";
		$sql = sprintf("select uw.companyID,uw.branchID,uw.number, uw.name, uw.statusID, uw.isActive,w.typeWarehouse");
		$sql = $sql.sprintf(" from tb_warehouse uw");		
		$sql = $sql.sprintf(" where uw.companyID = $companyID");		
		$sql = $sql.sprintf(" and uw.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }

}
?>