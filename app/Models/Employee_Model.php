<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Employee_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function update_app_posme($companyID,$branchID,$entityID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_employee");
		
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);	
		$builder->where("entityID",$entityID);	
		return $builder->update($data);
		
   }
   function delete_app_posme($companyID,$branchID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_employee");		
		
  		$data["isActive"] = 0;
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);	
		$builder->where("entityID",$entityID);	
		return $builder->update($data);
		
   } 
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_employee");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }   
   function get_rowByBranchIDAndType($companyID,$branchID,$typeEmployer)
   {
		$db 		= db_connect();
		$builder	= $db->table("tb_employee");    
		
		$sql = "";
		$sql = sprintf("select i.companyID, i.branchID, i.entityID, i.employeNumber, i.numberIdentification, i.identificationTypeID, i.socialSecurityNumber, i.address, i.countryID, i.stateID, i.cityID, i.departamentID, i.areaID, i.clasificationID, i.categoryID, i.reference1, i.reference2, i.typeEmployeeID, i.hourCost, i.parentEmployeeID, i.startOn, i.endOn,i.statusID, i.createdOn, i.createdIn, i.createdAt, i.createdBy, i.isActive, i.vacationBalanceDay, i.amountSaving, n.firstName,n.lastName ");
		$sql = $sql.sprintf(" from tb_employee i");		
		$sql = $sql.sprintf(" inner join tb_naturales n on  n.entityID = i.entityID ");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.branchID = $branchID");
		$sql = $sql.sprintf(" and i.departamentID = $typeEmployer");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByBranchID($companyID,$branchID){
		$db 		= db_connect();
		$builder	= $db->table("tb_employee");    
		
		$sql = "";
		$sql = sprintf("select i.companyID, i.branchID, i.entityID, i.employeNumber, i.numberIdentification, i.identificationTypeID, i.socialSecurityNumber, i.address, i.countryID, i.stateID, i.cityID, i.departamentID, i.areaID, i.clasificationID, i.categoryID, i.reference1, i.reference2, i.typeEmployeeID, i.hourCost, i.parentEmployeeID, i.startOn, i.endOn,i.statusID, i.createdOn, i.createdIn, i.createdAt, i.createdBy, i.isActive, i.vacationBalanceDay, i.amountSaving, n.firstName,n.lastName ");
		$sql = $sql.sprintf(" from tb_employee i");		
		$sql = $sql.sprintf(" inner join tb_naturales n on  n.entityID = i.entityID ");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.branchID = $branchID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByPK($companyID,$branchID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_employee");    
		
		$sql = "";
		$sql = sprintf("select i.companyID, i.branchID, i.entityID, i.employeNumber, i.numberIdentification, i.identificationTypeID, i.socialSecurityNumber, i.address, i.countryID, i.stateID, i.cityID, i.departamentID, i.areaID, i.clasificationID, i.categoryID, i.reference1, i.reference2, i.typeEmployeeID, i.hourCost, i.parentEmployeeID, i.startOn, i.endOn,i.statusID, i.createdOn, i.createdIn, i.createdAt, i.createdBy, i.isActive, i.vacationBalanceDay, i.amountSaving, n.firstName,n.lastName ");
		$sql = $sql.sprintf(" from tb_employee i");		
		$sql = $sql.sprintf(" inner join tb_naturales n on  n.entityID = i.entityID ");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.branchID = $branchID");
		$sql = $sql.sprintf(" and i.entityID = $entityID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }

   function get_rowByEmployeeID($companyID,$branchID,$employeId){
		$db 	= db_connect();
		$builder	= $db->table("tb_employee te");    
		$query		= $builder->select('te.employeeID,
					te.companyID,
					te.branchID,
					te.entityID,
					te.employeNumber,
					te.numberIdentification,
					te.identificationTypeID,
					te.socialSecurityNumber,
					tn.address,
					countryID,
					stateID,
					cityID,
					departamentID,
					areaID,
					clasificationID,
					categoryID,
					reference1,
					reference2,
					typeEmployeeID,
					hourCost,
					comissionPorcentage,
					parentEmployeeID,
					startOn,
					endOn,
					te.statusID,
					createdOn,
					createdIn,
					createdAt,
					createdBy,
					te.isActive,
					te.vacationBalanceDay,
					te.amountSaving,
					tn.firstName,
					tn.lastName');
		$query		= $query->join('tb_naturales tn','te.entityID=tn.entityID');
		$query		= $query->where(['te.companyID'=>$companyID, 'te.branchID'=>$branchID, 'te.employeeID'=>$employeId,'te.isActive'=>1]);
		
		//Ejecutar Consulta
		return $query->get()->getRowObject();
	}

   function get_rowByCompanyID($companyID){
		$db 	= db_connect();
		$builder	= $db->table("tb_employee");    
		
		$sql = "";
		$sql = sprintf("select i.companyID, i.branchID, i.entityID, i.employeNumber, i.numberIdentification, i.identificationTypeID, i.socialSecurityNumber, i.address, i.countryID, i.stateID, i.cityID, i.departamentID, i.areaID, i.clasificationID, i.categoryID, i.reference1, i.reference2, i.typeEmployeeID, i.hourCost, i.parentEmployeeID, i.startOn, i.endOn,i.statusID, i.createdOn, i.createdIn, i.createdAt, i.createdBy, i.isActive, i.vacationBalanceDay, i.amountSaving, n.firstName,n.lastName ");
		$sql = $sql.sprintf(" from tb_employee i");		
		$sql = $sql.sprintf(" inner join tb_naturales n on  n.entityID = i.entityID ");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByEntityID($companyID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_employee");    
		
		$sql = "";
		$sql = sprintf("select i.companyID, i.branchID, i.entityID, i.employeNumber, i.numberIdentification, i.identificationTypeID, i.socialSecurityNumber, i.address, i.countryID, i.stateID, i.cityID, i.departamentID, i.areaID, i.clasificationID, i.categoryID, i.reference1, i.reference2, i.typeEmployeeID, i.hourCost, i.parentEmployeeID, i.startOn, i.endOn,i.statusID, i.createdOn, i.createdIn, i.createdAt, i.createdBy, i.isActive, i.vacationBalanceDay, i.amountSaving, n.firstName,n.lastName ");
		$sql = $sql.sprintf(" from tb_employee i");		
		$sql = $sql.sprintf(" inner join tb_naturales n on  n.entityID = i.entityID ");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.entityID = $entityID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
}
?>