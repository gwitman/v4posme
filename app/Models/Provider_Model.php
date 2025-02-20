<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Provider_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   function update_app_posme($companyID,$branchID,$entityID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_provider");
		
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);	
		$builder->where("entityID",$entityID);	
		return $builder->update($data);
		
   }
   function delete_app_posme($companyID,$branchID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_provider");
		
  		$data["isActive"] = 0;
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);	
		$builder->where("entityID",$entityID);	
		
		return $builder->update($data);
		
   } 
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_provider");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }   
   function get_rowByEntity($companyID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_provider");    
		
		$sql = "";
		$sql = sprintf("select companyID, branchID, entityID, providerNumber, numberIdentification, identificationTypeID, providerType, providerCategoryID, providerClasificationID, reference1, reference2, payConditionID, isLocal, countryID, stateID, cityID, address, currencyID, statusID, deleveryDay, deleveryDayReal, distancia, createdIn, createdBy, createdAt, createdOn, isActive");
		$sql = $sql.sprintf(" from tb_provider i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.entityID = $entityID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByCompany($companyID){
		$db 	= db_connect();
		$builder	= $db->table("tb_provider");
		$sql = "";
		$sql = sprintf("select p.entityID,p.companyID,p.providerNumber,p.numberIdentification,nat.firstName,nat.lastName");
		$sql = $sql.sprintf(" from tb_provider p");
		$sql = $sql.sprintf(" inner join  tb_entity e on p.entityID = e.entityID");
		$sql = $sql.sprintf(" inner join  tb_naturales nat on p.entityID = nat.entityID");
		$sql = $sql.sprintf(" where p.companyID = $companyID");
		$sql = $sql.sprintf(" and p.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByPK($companyID,$branchID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_provider");
		
		$sql = "";
		$sql = sprintf("select companyID, branchID, entityID, providerNumber, numberIdentification, identificationTypeID, providerType, providerCategoryID, providerClasificationID, reference1, reference2, payConditionID, isLocal, countryID, stateID, cityID, address, currencyID, statusID, deleveryDay, deleveryDayReal, distancia, createdIn, createdBy, createdAt, createdOn, isActive, balanceDol, balanceCor");
		$sql = $sql.sprintf(" from tb_provider i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.branchID = $branchID");
		$sql = $sql.sprintf(" and i.entityID = $entityID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByProviderNumber($companyID,$providerNumber){
		$db 	= db_connect();
		$builder	= $db->table("tb_provider");    
		
		$sql = "";
		$sql = sprintf("select companyID, branchID, entityID, providerNumber, numberIdentification, identificationTypeID, providerType, providerCategoryID, providerClasificationID, reference1, reference2, payConditionID, isLocal, countryID, stateID, cityID, address, currencyID, statusID, deleveryDay, deleveryDayReal, distancia, createdIn, createdBy, createdAt, createdOn, isActive");
		$sql = $sql.sprintf(" from tb_provider i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.providerNumber = '$providerNumber' ");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
	}
}
?>