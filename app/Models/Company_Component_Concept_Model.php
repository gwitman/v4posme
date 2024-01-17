<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Company_Component_Concept_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_company_component_concept");
		$result		= $builder->insert($data);
		return $result;
   }
   function update_app_posme($companyID,$componentID,$componentItemID,$name,$data){
		$db 		= db_connect();
		$builder	= $db->table("tb_company_component_concept");
		
		$builder->where("companyID", $companyID);
		$builder->where("componentID", $componentID);	
		$builder->where("componentItemID",$componentItemID);	
		$builder->where("name",$name);	
		return $builder->update($data);
		
   }
   function get_rowByPK($companyID,$componentID,$componentItemID,$name){
		$db 	= db_connect();
		    
		$sql = "";
		$sql = sprintf("select companyID,componentID,componentItemID, name,valueIn,valueOut");
		$sql = $sql.sprintf(" from tb_company_component_concept td");
		$sql = $sql.sprintf(" where td.companyID = $companyID");
		$sql = $sql.sprintf(" and td.componentID = $componentID");		
		$sql = $sql.sprintf(" and td.componentItemID = $componentItemID");		
		$sql = $sql.sprintf(" and td.name = '$name'");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByComponentItemID($companyID,$componentID,$componentItemID){
		$db 		= db_connect();
		
		
		$sql = "";
		$sql = sprintf("select companyID,componentID,componentItemID, name,valueIn,valueOut");
		$sql = $sql.sprintf(" from tb_company_component_concept td");
		$sql = $sql.sprintf(" where td.companyID = $companyID");
		$sql = $sql.sprintf(" and td.componentID = $componentID");		
		$sql = $sql.sprintf(" and td.componentItemID = $componentItemID");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
   
   function get_rowByTransactionMasterID($companyID,$componentID,$transactionMasterID)
   {
	   $db 		= db_connect();
		
		
		$sql = "";
		$sql = sprintf("select td.companyID, td.componentID, td.componentItemID, td.name, td.valueIn, td.valueOut ");
		$sql = $sql.sprintf(" from tb_transaction_master tm ");
		$sql = $sql.sprintf(" inner join tb_transaction_master_detail tmd on  tm.transactionMasterID = tmd.transactionMasterID ");
		$sql = $sql.sprintf(" inner join tb_company_component_concept td on td.componentItemID = tmd.componentItemID ");
		$sql = $sql.sprintf(" where td.companyID = $companyID");
		$sql = $sql.sprintf(" and td.componentID = $componentID");		
		$sql = $sql.sprintf(" and tm.transactionMasterID = $transactionMasterID");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
   function deleteWhereComponentItemID($companyID,$componentID,$componentItemID){
		$db 		= db_connect();
		$builder	= $db->table("tb_company_component_concept");
		
		$builder->where("companyID",$companyID);
		$builder->where("componentID",$componentID);	
		$builder->where("componentItemID",$componentItemID);			
		$builder->delete();
		
   }
}
?>