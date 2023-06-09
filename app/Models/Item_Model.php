<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Item_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function update_app_posme($companyID,$itemID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_item");
		
		$builder->where("companyID",$companyID);
		$builder->where("itemID",$itemID);	
		return $builder->update($data);
		
   }
   function delete_app_posme($companyID,$itemID){
		$db 	= db_connect();
		$builder	= $db->table("tb_item");		
  		$data["isActive"] = 0;
		
		$builder->where("companyID",$companyID);
		$builder->where("itemID",$itemID);	
		return $builder->update($data);
		
   } 
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_item");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }
   function get_rowByCode($companyID,$itemNumber){
		$db 	= db_connect();
		$builder	= $db->table("tb_item");
		$sql = "";
		$sql = sprintf("select i.companyID, i.branchID, i.inventoryCategoryID, i.itemID, i.familyID, i.itemNumber, i.barCode, i.name, i.description, i.unitMeasureID, i.displayID, i.capacity, i.displayUnitMeasureID, i.defaultWarehouseID, i.quantity, i.quantityMax, i.quantityMin, i.cost, i.reference1, i.reference2, i.statusID, i.isPerishable, i.factorBox, i.factorProgram, i.createdIn, i.createdAt, i.createdBy, i.createdOn, i.isActive,i.isInvoiceQuantityZero,i.isServices ");
		$sql = $sql.sprintf(" from tb_item i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.itemNumber = '$itemNumber' ");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByCodeBarra($companyID,$itemNumber){
		$db 	= db_connect();
		$builder	= $db->table("tb_item");
		$sql = "";
		$sql = sprintf("select i.companyID, i.branchID, i.inventoryCategoryID, i.itemID, i.familyID, i.itemNumber, i.barCode, i.name, i.description, i.unitMeasureID, i.displayID, i.capacity, i.displayUnitMeasureID, i.defaultWarehouseID, i.quantity, i.quantityMax, i.quantityMin, i.cost, i.reference1, i.reference2, i.statusID, i.isPerishable, i.factorBox, i.factorProgram, i.createdIn, i.createdAt, i.createdBy, i.createdOn, i.isActive,i.isInvoiceQuantityZero,i.isServices ");
		$sql = $sql.sprintf(" from tb_item i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.barCode = '$itemNumber' ");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByPK($companyID,$itemID){
		$db 	= db_connect();
		$builder	= $db->table("tb_item");    
		$sql = "";
		$sql = sprintf("select i.companyID, i.branchID, i.inventoryCategoryID, i.itemID, i.familyID, i.itemNumber, i.barCode, i.name, i.description, i.unitMeasureID, i.displayID, i.capacity, i.displayUnitMeasureID, i.defaultWarehouseID, i.quantity, i.quantityMax, i.quantityMin, i.cost, i.reference1, i.reference2, i.statusID, i.isPerishable, i.factorBox, i.factorProgram, i.createdIn, i.createdAt, i.createdBy, i.createdOn, i.isActive,i.isInvoiceQuantityZero,i.isServices");
		$sql = $sql.sprintf(" from tb_item i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.itemID = $itemID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   
   function get_rowsByPK($companyID,$listItem){
		$db 	= db_connect();
		$builder	= $db->table("tb_item");    
		
		$builder->select("companyID, branchID, inventoryCategoryID, itemID, familyID, itemNumber, barCode, name, description, unitMeasureID, displayID, capacity, displayUnitMeasureID, defaultWarehouseID, quantity, quantityMax, quantityMin, cost, reference1, reference2, statusID, isPerishable, factorBox, factorProgram, createdIn, createdAt, createdBy, createdOn, isActive,isInvoiceQuantityZero,isServices");		
		$builder->where("companyID",$companyID);
		$builder->whereIn("itemID",$listItem);
		$builder->where("isActive",1);		
		
		//Ejecutar Consulta
		return $builder->get()->getResult();
   }
   function get_rowByCompany($companyID){
		$db 	= db_connect();
		$builder	= $db->table("tb_item");    
		$sql = "";
		$sql = sprintf("select i.companyID, i.branchID, i.inventoryCategoryID, i.itemID, i.familyID, i.itemNumber, i.barCode, i.name, i.description, i.unitMeasureID, i.displayID, i.capacity, i.displayUnitMeasureID, i.defaultWarehouseID, i.quantity, i.quantityMax, i.quantityMin, i.cost, i.reference1, i.reference2, i.statusID, i.isPerishable, i.factorBox, i.factorProgram, i.createdIn, i.createdAt, i.createdBy, i.createdOn, i.isActive,i.isInvoiceQuantityZero,i.isServices");
		$sql = $sql.sprintf(" from tb_item i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function getCount($companyID){
		$db 		= db_connect();
		$builder	= $db->table("tb_item");
		
		$sql = "";
		$sql = $sql.sprintf(" select count(*) as counter");
		$sql = $sql.sprintf(" from tb_item");
		$sql = $sql.sprintf(" where isActive = 1");
		$sql = $sql.sprintf(" and companyID = $companyID");
		
   		return $db->query($sql)->getRow()->counter;
   }
}
?>