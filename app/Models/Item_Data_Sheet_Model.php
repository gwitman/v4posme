<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Item_Data_Sheet_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   function update_app_posme($itemDataSheetID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_data_sheet");
		
		$builder->where("itemDataSheetID",$itemDataSheetID);	
		return $builder->update($data);
		
   }
   function delete_app_posme($itemDataSheetID){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_data_sheet");		
  		$data["isActive"] = 0;
		
		$builder->where("itemDataSheetID",$itemDataSheetID);	
		return $builder->update($data);
		
   } 
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_data_sheet");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }
   function get_rowByPK($itemDataSheetID){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_data_sheet");
		$sql = "";
		$sql = sprintf("select i.itemDataSheetID,i.itemID,i.version,i.statusID,i.name,i.description,i.createdOn,i.createdBy,i.createdIn,i.createdAt,i.isActive");
		$sql = $sql.sprintf(" from tb_item_data_sheet i");		
		$sql = $sql.sprintf(" where i.itemDataSheetID = $itemDataSheetID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByItemID($itemID){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_data_sheet");    
		$sql = "";
		$sql = sprintf("select i.itemDataSheetID,i.itemID,i.version,i.statusID,i.name,i.description,i.createdOn,i.createdBy,i.createdIn,i.createdAt,i.isActive");
		$sql = $sql.sprintf(" from tb_item_data_sheet i");		
		$sql = $sql.sprintf(" where i.itemID = $itemID");
		$sql = $sql.sprintf(" and i.isActive= 1");
		$sql = $sql.sprintf(" order by i.version desc");
		$sql = $sql.sprintf(" limit 0,1 ");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
}
?>