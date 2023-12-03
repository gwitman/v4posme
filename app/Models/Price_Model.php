<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Price_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function delete_app_posme($companyID,$listPriceID){
		$db 	= db_connect();
		$builder	= $db->table("tb_price");		
  		
		$builder->where("companyID",$companyID);
		$builder->where("listPriceID",$listPriceID);			
		$builder->delete();
		
   } 
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_price");
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }   
   
   function update_app_posme($companyID,$listPriceID,$itemID,$typePriceID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_price");
		
		$builder->where("companyID",$companyID);
		$builder->where("listPriceID",$listPriceID);	
		$builder->where("itemID",$itemID);	
		$builder->where("typePriceID",$typePriceID);	
		return $builder->update($data);
		
   }
   
   function get_rowByAll($companyID,$listPriceID){
		$db 	= db_connect();
		$builder	= $db->table("tb_price");
		$sql = "";
		$sql = sprintf("select i.companyID,i.listPriceID,i.itemID,i.priceID,i.typePriceID,i.percentage,i.price,ci.name as tipoPrice,it.itemNumber,it.name as itemName,it.cost,i.percentageCommision");
		$sql = $sql.sprintf(" from tb_price i");
		$sql = $sql.sprintf(" inner join  tb_item it on i.itemID = it.itemID");
		$sql = $sql.sprintf(" inner join  tb_catalog_item ci on ci.catalogItemID = i.typePriceID");
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.listPriceID = $listPriceID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByPK($companyID,$listPriceID,$itemID,$typePriceID){
		$db 	= db_connect();
		$builder	= $db->table("tb_price");    
		$sql = "";
		$sql = sprintf("select companyID,listPriceID,itemID,priceID,typePriceID,percentage,price,i.percentageCommision");
		$sql = $sql.sprintf(" from tb_price i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.listPriceID = $listPriceID");		
		$sql = $sql.sprintf(" and i.itemID = $itemID");		
		$sql = $sql.sprintf(" and i.typePriceID = $typePriceID");		
		$sql = $sql.sprintf(" limit 0,1 ");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByItemIDAndAmount($companyID,$listPriceID,$itemID,$amount)
   {
		$db 		= db_connect();
		$builder	= $db->table("tb_price");    
		$sql = "";
		$sql = sprintf("select companyID,listPriceID,itemID,priceID,typePriceID,percentage,price,i.percentageCommision");
		$sql = $sql.sprintf(" from tb_price i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.listPriceID = $listPriceID");		
		$sql = $sql.sprintf(" and i.itemID = $itemID");		
		$sql = $sql.sprintf(" and i.price >= '$amount' ");		
		$sql = $sql.sprintf(" order by i.price asc		");		
		$sql = $sql.sprintf(" limit 0,1 ");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByItemID($companyID,$listPriceID,$itemID){
		$db 	= db_connect();
		$builder	= $db->table("tb_price");    
		$sql = "";
		$sql = sprintf("select companyID,listPriceID,itemID,priceID,typePriceID,percentage,price,c.name as nameTypePrice,i.percentageCommision");
		$sql = $sql.sprintf(" from tb_price i");		
		$sql = $sql.sprintf(" inner join  tb_catalog_item c on c.catalogItemID = i.typePriceID");
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.listPriceID = $listPriceID");		
		$sql = $sql.sprintf(" and i.itemID = $itemID");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	}
	   
   
}
?>