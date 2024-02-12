<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Customer_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   function update_app_posme($companyID,$branchID,$entityID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer");
		
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);	
		$builder->where("entityID",$entityID);	
		return $builder->update($data);
		
   }
   function delete_app_posme($companyID,$branchID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer");		
  		$data["isActive"] = 0;
		
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);	
		$builder->where("entityID",$entityID);	
		return $builder->update($data);
		
   } 
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_customer");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }   
   function get_happyBirthDay($companyID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer");
	    $sql = "";
		$sql = sprintf("select 
			c.customerNumber,n.firstName,n.lastName,c.birthDate,c.balancePoint,
			c.dateContract,c.entityContactID,
			c.reference3,c.reference4,c.reference5,c.reference6 ,c.budget
		");
		$sql = $sql.sprintf(" from tb_customer c");
		$sql = $sql.sprintf(" inner join  tb_naturales n on n.entityID = c.entityID");				
		$sql = $sql.sprintf(" where c.companyID = $companyID");
		$sql = $sql.sprintf(" and c.isActive= 1");
		$sql = $sql.sprintf(" and c.birthDate <= CURDATE()");
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByCode($companyID,$customerCode){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer");
		
	    $sql = "";
		$sql = sprintf("select 
			companyID, branchID, entityID, customerNumber, 
			identificationType, identification, countryID, 
			stateID, cityID, location, address, currencyID, 
			clasificationID, categoryID, subCategoryID, 
			customerTypeID, birthDate, statusID, typePay,
			payConditionID, sexoID, reference1, reference2, 
			createdIn, createdBy, createdOn, createdAt,
			isActive,typeFirm,i.balancePoint,i.phoneNumber,
			i.dateContract,i.entityContactID,
			i.reference3,i.reference4,i.reference5,i.reference6,i.budget
		");
		$sql = $sql.sprintf(" from tb_customer i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.customerNumber = '$customerCode' ");		
		$sql = $sql.sprintf(" and i.isActive= 1");	

		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByIdentification($companyID,$identification){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer");
		
	    $sql = "";
		$sql = sprintf("select 
			companyID, branchID, entityID, customerNumber, identificationType, 
			identification, countryID, stateID, cityID, location, address, 
			currencyID, clasificationID, categoryID, subCategoryID, 
			customerTypeID, birthDate, statusID, typePay, payConditionID, 
			sexoID, reference1, reference2, createdIn, createdBy, 
			createdOn, createdAt, isActive,typeFirm,i.balancePoint,
			i.phoneNumber,i.dateContract,i.entityContactID,
			i.reference3,i.reference4,i.reference5,i.reference6,i.budget
		");
		$sql = $sql.sprintf(" from tb_customer i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.identification = '$identification' ");		
		$sql = $sql.sprintf(" and i.isActive= 1");	

		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   
   function get_rowByCompany_phoneAndEmail($companyID)
   {
		$db 		= db_connect();
		$builder	= $db->table("tb_customer");
			
		$sql = "";
		$sql = sprintf("select 
			i.companyID, i.branchID, i.entityID, i.customerNumber, i.identificationType, i.identification, i.countryID, i.stateID, i.cityID, 
			i.location, i.address, i.currencyID, i.clasificationID, i.categoryID, i.subCategoryID, i.customerTypeID, i.birthDate, i.statusID, i.typePay, 
			i.payConditionID, i.sexoID, i.reference1, i.reference2, i.createdIn, i.createdBy, i.createdOn, i.createdAt, i.isActive,
			nat.firstName,nat.lastName,i.typeFirm,i.balancePoint,i.phoneNumber,i.dateContract,i.entityContactID,
			i.reference3,i.reference4,i.reference5,i.reference6,i.budget
		");
		$sql = $sql.sprintf(" from tb_customer i");
		$sql = $sql.sprintf(" inner join  tb_naturales nat on nat.entityID = i.entityID");				
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   function get_rowByCompany($companyID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer");
			
		$sql = "";
		$sql = sprintf("select 
		i.companyID, i.branchID, i.entityID, i.customerNumber, i.identificationType, i.identification, i.countryID, i.stateID, i.cityID, 
		i.location, i.address, i.currencyID, i.clasificationID, i.categoryID, i.subCategoryID, i.customerTypeID, i.birthDate, i.statusID, i.typePay, 
		i.payConditionID, i.sexoID, i.reference1, i.reference2, i.createdIn, i.createdBy, i.createdOn, i.createdAt, i.isActive,
		nat.firstName,nat.lastName,i.typeFirm,i.balancePoint,i.phoneNumber,i.dateContract,i.entityContactID,
		i.reference3,i.reference4,i.reference5,i.reference6,i.budget
		");
		$sql = $sql.sprintf(" from tb_customer i");
		$sql = $sql.sprintf(" inner join  tb_naturales nat on nat.entityID = i.entityID");				
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	}
   function get_rowByEntity($companyID,$entityID){
		$db 		= db_connect();
		$builder	= $db->table("tb_customer");    
		
		$sql = "";
		$sql = sprintf("select 
			i.companyID, i.branchID, i.entityID, i.customerNumber, 
			i.identificationType, i.identification, i.countryID, 
			i.stateID, i.cityID, i.location, 
			i.address, i.currencyID, i.clasificationID, 
			i.categoryID, i.subCategoryID, i.customerTypeID,
			i.birthDate, i.statusID, i.typePay, i.payConditionID, 
			i.sexoID, i.reference1, i.reference2, i.createdIn, i.createdBy, 
			i.createdOn, i.createdAt, i.isActive, i.typeFirm, 
			n.firstName,n.lastName,i.balancePoint,i.phoneNumber,
			i.dateContract,i.entityContactID,
			i.reference3,i.reference4,i.reference5,i.reference6,i.budget
		");
		$sql = $sql.sprintf(" from tb_customer i");	
		$sql = $sql.sprintf(" inner join  tb_naturales n on n.entityID = i.entityID");				
		$sql = $sql.sprintf(" where i.companyID = $companyID ");
		$sql = $sql.sprintf(" and i.entityID = $entityID");
		$sql = $sql.sprintf(" and i.isActive = 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByPK($companyID,$branchID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer");    
		$sql = "";
		$sql = sprintf("select 
				companyID, branchID, entityID, customerNumber, identificationType, 
				identification, countryID, stateID, cityID, location, address, 
				currencyID, clasificationID, categoryID, subCategoryID, 
				customerTypeID, birthDate, statusID, typePay, payConditionID, 
				sexoID, reference1, reference2, createdIn, createdBy, 
				createdOn, createdAt, isActive,i.typeFirm,i.balancePoint,
				i.phoneNumber,i.dateContract,i.entityContactID,
				i.reference3,i.reference4,i.reference5,i.reference6,i.budget
			");
		$sql = $sql.sprintf(" from tb_customer i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.branchID = $branchID");
		$sql = $sql.sprintf(" and i.entityID = $entityID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
}
?>