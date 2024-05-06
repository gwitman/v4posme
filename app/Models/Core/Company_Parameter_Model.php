<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;

class Company_Parameter_Model extends Model  {
   function __construct(){		
		parent::__construct();
   }  
   function update_app_posme($companyID,$parameterID,$data){
		$db 		= db_connect();		
		$builder 	= $db->table('tb_company_parameter');
		
		$builder->where("companyID",$companyID);
		$builder->where("parameterID",$parameterID);				
		$builder->update($data);		
		
   }
   function get_rowByParameterID_CompanyID($companyID,$parameterID){
		$db 		= db_connect(); 
		
		
		
		$sql = "";
		$sql = sprintf("select companyID,parameterID,display,description,value,customValue");		
		$sql = $sql.sprintf(" from  tb_company_parameter ");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and parameterID = $parameterID");
		
		//Ejecutar Consulta
		$recordSet = $db->query($sql);
		$recordSet = $recordSet->getRow();

		//Resultado
		return $recordSet;
   }
   
   function get_rowByCompanyID($companyID)
   {
	   	$db 	= db_connect();    
		$sql = "";
		$sql = sprintf("select c.companyID,c.parameterID,c.display,c.description,c.value,c.customValue, p.name ");
		$sql = $sql.sprintf(" from tb_company_parameter  c ");			
		$sql = $sql.sprintf(" inner join tb_parameter p  on p.parameterID = c.parameterID ");		
		$sql = $sql.sprintf(" where companyID = $companyID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   
   
}
?>