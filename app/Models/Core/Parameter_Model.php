<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Parameter_Model extends Model  {	
   function __construct(){		
      parent::__construct();	  
   }  
   function get_rowByName($name){
		$db 		= db_connect(); 				
		
		$sql = "";
		$sql = sprintf("select parameterID,name,description,isEdited,isRequiered");		
		$sql = $sql.sprintf(" from tb_parameter");		
		$sql = $sql.sprintf(" where name = '$name' ");		
		
		
		//Ejecutar Consulta
		$recordSet = $db->query($sql);
		$recordSet = $recordSet->getRow();

		//Resultado
		return $recordSet;
   }
   function get_all()
   {
	   $db 		= db_connect(); 				
		
		$sql = "";
		$sql = sprintf("select parameterID,name,description,isEdited,isRequiered");		
		$sql = $sql.sprintf(" from tb_parameter");			
		
		
		//Ejecutar Consulta
		$recordSet = $db->query($sql);
		$recordSet = $recordSet->getResult();

		//Resultado
		return $recordSet;
   }
   
  
   
}
?>