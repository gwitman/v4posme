<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Fixed_Assent_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function update_app_posme($companyID,$branchID,$fixedAssentID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_fixed_assent");
		
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);	
		$builder->where("fixedAssentID",$fixedAssentID);	
		return $builder->update($data);
		
   }
   function delete_app_posme($companyID,$branchID,$fixedAssentID){
		$db 	= db_connect();
		$builder	= $db->table("tb_fixed_assent");		  		
		$data["isActive"]	= 0;
		
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);	
		$builder->where("fixedAssentID",$fixedAssentID);	
		return $builder->update($data);
		
   } 
   function insert_app_posme($data){
		$db 			= db_connect();
		$builder		= $db->table("tb_fixed_assent");
		$result			= $builder->insert($data);
		$autoIncrement	= $db->insertID(); 		
		return $autoIncrement;
		
   }
   function get_rowByPK($companyID,$branchID,$fixedAssentID){
		$db 	= db_connect();
		$builder	= $db->table("tb_fixed_assent");    
		
		$sql = "";
		$sql = sprintf("select companyID, branchID, fixedAssentID, fixedAssentCode, name, description, modelNumber, marca, colorID, chasisNumber, reference1, reference2, year, asignedEmployeeID, categoryID, typeID, typeDepresiationID, yearOfUtility, priceStart, isForaneo, statusID,createdIn, createdOn, createdAt, createdBy, isActive");
		$sql = $sql.sprintf(" from tb_fixed_assent i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.branchID = $branchID");
		$sql = $sql.sprintf(" and i.fixedAssentID = $fixedAssentID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
}
?>