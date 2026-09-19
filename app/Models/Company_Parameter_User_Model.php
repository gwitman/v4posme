<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Company_Parameter_User_Model extends Model  {
   function __construct(){		
		parent::__construct();
   }  
  
   function get_rowByParameterID($parameterID,$userID){
		$db 		= db_connect(); 
		
		
		
		$sql = "";
		$sql = sprintf("select parameterUserID,parameterID,customPageID,isActive,`value`,userID ");		
		$sql = $sql.sprintf(" from  tb_company_parameter_user ");
		$sql = $sql.sprintf(" where parameterID = $parameterID and userID = $userID and isActive = 1 ");
		
		//Ejecutar Consulta
		$recordSet = $db->query($sql);
		$recordSet = $recordSet->getRow();

		//Resultado
		return $recordSet;
   }
   function get_rowByCustomPageID($customPageID,$userID){
		$db 		= db_connect(); 
		
		
		
		$sql = "";
		$sql = sprintf("select parameterUserID,parameterID,customPageID,isActive,`value`,userID ");		
		$sql = $sql.sprintf(" from  tb_company_parameter_user ");
		$sql = $sql.sprintf(" where customPageID = $customPageID and userID = $userID and isActive = 1  ");
		
		//Ejecutar Consulta
		$recordSet = $db->query($sql);
		$recordSet = $recordSet->getRow();

		//Resultado
		return $recordSet;
   }
   
   
}
?>