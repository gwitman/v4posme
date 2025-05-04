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
			c.reference3,c.reference4,c.reference5,c.reference6 ,c.budget,c.modifiedOn,c.formContactId
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
			i.reference3,i.reference4,i.reference5,i.reference6,i.budget,
			i.modifiedOn,i.formContactID
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
			i.reference3,i.reference4,i.reference5,i.reference6,i.budget,
			i.modifiedOn,i.formContactID
		");
		$sql = $sql.sprintf(" from tb_customer i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.identification = '$identification' ");		
		$sql = $sql.sprintf(" and i.isActive= 1 order by i.entityID asc limit 1");	

		
		
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
			i.reference3,i.reference4,i.reference5,i.reference6,i.budget,
			i.modifiedOn,i.formContactID
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
		i.customerID, i.companyID, i.branchID, i.entityID, i.customerNumber, i.identificationType, i.identification, i.countryID, i.stateID, i.cityID, 
		i.location, i.address, i.currencyID, i.clasificationID, i.categoryID, i.subCategoryID, i.customerTypeID, i.birthDate, i.statusID, i.typePay, 
		i.payConditionID, i.sexoID, i.reference1, i.reference2, i.createdIn, i.createdBy, i.createdOn, i.createdAt, i.isActive,
		nat.firstName,nat.lastName,i.typeFirm,i.balancePoint,i.phoneNumber,i.dateContract,i.entityContactID,
		i.reference3,i.reference4,i.reference5,i.reference6,i.budget,
		i.modifiedOn,i.formContactID
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
			i.reference3,i.reference4,i.reference5,i.reference6,i.budget,
			i.modifiedOn,i.formContactID
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
				i.reference3,i.reference4,i.reference5,i.reference6,i.budget,
				i.modifiedOn,i.formContactID, i.balanceDol, i.balanceCor
			");
		$sql = $sql.sprintf(" from tb_customer i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.branchID = $branchID");
		$sql = $sql.sprintf(" and i.entityID = $entityID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByCompanyIDToMobile($companyID,$userID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer");
			
		$sql = "";
		$sql = sprintf("
		select 
			cp.companyID, 
			cp.branchID, 
			cp.entityID, 
			cp.customerNumber, 
			cp.identification, 
			cp.firstName,
			cp.lastName,
			cp.currencyName,
			cp.currencyID,
			cp.customerCreditLineID,
			cp.location,
			cp.phone , 
			cp.me, 
			cp.balance 
		from 
			(
			select 
				k.companyID, 
				k.branchID, 
				k.entityID, 
				k.customerNumber, 
				k.identification, 
				k.firstName,
				k.lastName,
				k.currencyName,
				k.currencyID,
				k.customerCreditLineID,
				k.location,
				k.phone , 
				k.me, 
				sum(k.balance) as balance 
			from 
				(
					select 
							i.companyID, 
							i.branchID, 
							i.entityID, 
							i.customerNumber, 
							i.identification, 
							nat.firstName,
							nat.lastName,
							cur.simbol as currencyName,
							cl.currencyID as currencyID,
							cl.customerCreditLineID,
							ifnull(i.location,'') as location,
							ifnull((select ep.number from tb_entity_phone ep  where ep.entityID = i.entityID limit 1 ),'') as phone , 
							IFNULL(cdd.balance,0) as balance,
							IFNULL(
								(
									select 
										1 
									from 
										tb_customer custp
										inner join tb_relationship rrp on 
											rrp.customerID = custp.entityID 
										inner join tb_employee empp on 
											empp.entityID = rrp.employeeID 
										inner join tb_user usrp on 
											usrp.employeeID = empp.entityID 
									where 
										custp.entityID = i.entityID and 
										usrp.userID = $userID 
									limit 1 
								),
								0 
							) as me 
					from 
							tb_customer i
							inner join  tb_naturales nat on nat.entityID = i.entityID 
							inner join  tb_customer_credit_line cl on cl.entityID = i.entityID 
							inner join tb_currency cur on  cur.currencyID = cl.currencyID 					
							left join  tb_customer_credit_document cdd on cdd.entityID = i.entityID and cdd.balance > 0 
					where 
							i.companyID = $companyID 
							and i.isActive= 1
								
					
					) k 
			group by 
				k.companyID, 
				k.branchID, 
				k.entityID, 
				k.customerNumber, 
				k.identification, 
				k.firstName,
				k.lastName,
				k.currencyName,
				k.currencyID,
				k.customerCreditLineID,
				k.location,
				k.phone,
				k.me 
			) cp
		order by 
			cp.me desc,
			cp.firstName
		");		
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	}
	
	function getIdentificationDuplicate($companyID)
    {
		$db 		= db_connect();
		$builder	= $db->table("tb_customer");
		
		$sql = "";
		$sql = $sql.sprintf(" select i.identification,count(*) as counter");
		$sql = $sql.sprintf(" from tb_customer i");
		$sql = $sql.sprintf(" where i.isActive = 1");
		$sql = $sql.sprintf(" and i.companyID = $companyID group by  i.identification having count(*) > 1 ");
		
   		return $db->query($sql)->getRow();
    }
   
   
}
?>