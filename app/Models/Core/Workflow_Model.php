<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Workflow_Model extends Model  {
   function __construct(){
		
      parent::__construct();
   }    
   function get_rowByWorkflowID($workflowID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select e.workflowID,e.componentID,e.name,e.description,e.isActive");
		$sql = $sql.sprintf(" from tb_workflow e");
		$sql = $sql.sprintf(" where e.workflowID = $workflowID");	
		$sql = $sql.sprintf(" and e.isActive= 1");	
		
		//Ejecutar Consulta
		 return $db->query($sql)->getRow();
		
   }
}
?>