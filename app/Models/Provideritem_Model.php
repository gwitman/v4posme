<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class ProviderItem_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function deleteWhereItemID($companyID,$itemID){
		$db 	= db_connect();
		$builder	= $db->table("tb_provider_item");
		
		$builder->where("companyID",$companyID);
		$builder->where("itemID",$itemID);	
		$builder->delete();
		
   }
   function deleteWhereItemIdyProviderId($companyID,$itemID,$providerID){
		$db 	= db_connect();
		$builder	= $db->table("tb_provider_item");
		
		$builder->where("companyID",$companyID);
		$builder->where("itemID",$itemID);	
		$builder->where("entityID",$providerID);
		$builder->delete();
		
   }
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_provider_item");
		$result = $builder->insert($data);
				
   }   
   function get_rowByItemID($companyID,$itemID){
		$db 	= db_connect();
		
		$sql = "";
		$sql = sprintf("select ip.entityID,p.providerNumber,n.firstName,n.lastName,l.comercialName");
		$sql = $sql.sprintf(" from tb_provider_item ip");
		$sql = $sql.sprintf(" inner join  tb_provider p on ip.entityID = p.entityID");
		$sql = $sql.sprintf(" left join  tb_legal l on p.entityID = l.entityID");
		$sql = $sql.sprintf(" left join  tb_naturales n on p.entityID = n.entityID");
		$sql = $sql.sprintf(" where ip.companyID = $companyID");
		$sql = $sql.sprintf(" and ip.itemID = $itemID");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function getByPK($companyID,$itemID,$providerID){
		$db 	= db_connect();
		
		$sql = "";
		$sql = sprintf("select ip.entityID,p.providerNumber,n.firstName,n.lastName,l.comercialName");
		$sql = $sql.sprintf(" from tb_provider_item ip");
		$sql = $sql.sprintf(" inner join  tb_provider p on ip.entityID = p.entityID");
		$sql = $sql.sprintf(" left join  tb_legal l on p.entityID = l.entityID");
		$sql = $sql.sprintf(" left join  tb_naturales n on p.entityID = n.entityID");
		$sql = $sql.sprintf(" where ip.companyID = $companyID");
		$sql = $sql.sprintf(" and ip.itemID = $itemID");		
		$sql = $sql.sprintf(" and ip.entityID = $providerID");		
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
}
?>