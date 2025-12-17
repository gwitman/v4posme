<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Item_Data_Sheet_Detail_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   function update_app_posme($itemDataSheetDetailID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_data_sheet_detail");
		
		$builder->where("itemDataSheetDetailID",$itemDataSheetDetailID);
		return $builder->update($data);

		
   }
   function delete_app_posme($itemDataSheetDetailID){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_data_sheet_detail");		
  		$data["isActive"] = 0;
		
		$builder->where("itemDataSheetDetailID",$itemDataSheetDetailID);
		return $builder->update($data);
		
   } 
   function deleteWhereDataSheet($itemDataSheetID){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_data_sheet_detail");
		$data["isActive"] = 0;
		
		$builder->where("itemDataSheetID",$itemDataSheetID);		
		return $builder->update($data);
		
	}
	function deleteWhereIDNotIn($itemDataSheetID,$listDSD_ID){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_data_sheet_detail");
		$data["isActive"] = 0;
		
		$builder->where("itemDataSheetID",$itemDataSheetID);	
		$builder->whereNotIn("itemDataSheetDetailID",$listDSD_ID);						
		return $builder->update($data);
		
	}

   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_data_sheet_detail");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }
   function get_rowByPK($itemDataSheetDetailID){
		$db 		= db_connect();
		$builder	= $db->table("tb_item_data_sheet_detail");
		$sql = "";
		$sql = sprintf("select i.itemDataSheetDetailID,i.itemDataSheetID,i.itemID,i.quantity,i.relatedItemID,i.isActive ,tm.itemNumber,tm.name , i.cost");
		$sql = $sql.sprintf(" from tb_item_data_sheet_detail i");
        $sql = $sql.sprintf(" inner join  tb_item tm on i.itemID = tm.itemID");
		$sql = $sql.sprintf(" where i.itemDataSheetDetailID = $itemDataSheetDetailID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByPKItemID($itemDataSheetID,$itemID){
		$db 		= db_connect();
		$builder	= $db->table("tb_item_data_sheet_detail");
		$sql = "";
		$sql = sprintf("select i.itemDataSheetDetailID,i.itemDataSheetID,i.itemID,i.quantity,i.relatedItemID,i.isActive ,tm.itemNumber,tm.name , i.cost");
		$sql = $sql.sprintf(" from tb_item_data_sheet_detail i");
        $sql = $sql.sprintf(" inner join  tb_item tm on i.itemID = tm.itemID");
		$sql = $sql.sprintf(" where i.itemDataSheetID = $itemDataSheetID");
		$sql = $sql.sprintf(" and tm.itemID = $itemID");
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByItemDataSheet($itemDataSheetID){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_data_sheet_detail");    
		$sql = "";
		$sql = sprintf("select i.itemDataSheetDetailID,i.itemDataSheetID,i.itemID,i.quantity,i.relatedItemID,i.isActive,tm.itemNumber,tm.name ,i.cost");
		$sql = $sql.sprintf(" from tb_item_data_sheet_detail i");		
        $sql = $sql.sprintf(" inner join  tb_item tm on i.itemID = tm.itemID");
		$sql = $sql.sprintf(" where i.itemDataSheetID = $itemDataSheetID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
}
?>