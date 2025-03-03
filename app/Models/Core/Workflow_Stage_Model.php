<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;

class Workflow_Stage_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }    
   function get_rowByPK($ListWorkflowStageRole){
		$db 	= db_connect();
   		$sql = "";
		$sql = sprintf("select e.workflowID,e.componentID,e.workflowStageID,e.name,e.description,e.display,e.flavorID,e.editableParcial,e.editableTotal,e.eliminable,e.aplicable,e.vinculable,e.isActive");
		$sql = $sql.sprintf(" from tb_workflow_stage e");
		
		//Filtrar los estados
		if($ListWorkflowStageRole){
			$sql = $sql.sprintf(" where ");
			foreach($ListWorkflowStageRole as $i){	
				
				$sql = $sql.sprintf(
				"
					or 
					(   e.componentID = ".$i->componentID."  
						and e.workflowID = ".$i->workflowID." 
						and e.workflowStageID = ".$i->workflowStageID." 
						and e.isActive = 1 
					)
				");
						
			}	
		}
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
	
   }
   function get_rowByWorkflowIDAndFlavorID($workflowID,$flavorID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select e.workflowID,e.componentID,e.workflowStageID,e.name,e.description,e.display,e.flavorID,e.editableParcial,e.editableTotal,e.eliminable,e.aplicable,e.vinculable,e.isActive");
		$sql = $sql.sprintf(" from tb_workflow_stage e");
		$sql = $sql.sprintf(" where e.workflowID = $workflowID");	
		$sql = $sql.sprintf(" and e.flavorID = $flavorID");	
		$sql = $sql.sprintf(" and e.isActive= 1");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   function get_rowByWorkflowStageID($workflowID,$flavorID,$workflowStageID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select e.workflowID,e.componentID,e.workflowStageID,e.name,e.description,e.display,e.flavorID,e.editableParcial,e.editableTotal,e.eliminable,e.aplicable,e.vinculable,e.isActive");
		$sql = $sql.sprintf(" from tb_workflow_stage e");
		$sql = $sql.sprintf(" where e.workflowID = $workflowID");	
		$sql = $sql.sprintf(" and e.flavorID = $flavorID");	
		$sql = $sql.sprintf(" and e.workflowStageID = $workflowStageID");	
		$sql = $sql.sprintf(" and e.isActive= 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByWorkflowStageIDOnly($workflowStageID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select e.workflowID,e.componentID,e.workflowStageID,e.name,e.description,e.display,e.flavorID,e.editableParcial,e.editableTotal,e.eliminable,e.aplicable,e.vinculable,e.isActive");
		$sql = $sql.sprintf(" from tb_workflow_stage e");
		$sql = $sql.sprintf(" where e.workflowStageID = $workflowStageID");	
		$sql = $sql.sprintf(" and e.isActive= 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	}
   function get_rowByWorkflowIDAndFlavorID_Init($workflowID,$flavorID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select e.workflowID,e.componentID,e.workflowStageID,e.name,e.description,e.display,e.flavorID,e.editableParcial,e.editableTotal,e.eliminable,e.aplicable,e.vinculable,e.isActive");
		$sql = $sql.sprintf(" from tb_workflow_stage e");
		$sql = $sql.sprintf(" where e.workflowID = $workflowID");	
		$sql = $sql.sprintf(" and e.flavorID = $flavorID");	
		$sql = $sql.sprintf(" and e.isActive= 1");	
		$sql = $sql.sprintf(" and e.isInit= 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   function get_rowByWorkflowIDAndFlavorID_ApplyFirst($workflowID,$flavorID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select e.workflowID,e.componentID,e.workflowStageID,e.name,e.description,e.display,e.flavorID,e.editableParcial,e.editableTotal,e.eliminable,e.aplicable,e.vinculable,e.isActive");
		$sql = $sql.sprintf(" from tb_workflow_stage e");
		$sql = $sql.sprintf(" where e.workflowID = $workflowID");	
		$sql = $sql.sprintf(" and e.flavorID = $flavorID");	
		$sql = $sql.sprintf(" and e.isActive= 1");			
		$sql = $sql.sprintf(" and e.aplicable= 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
	}
	function get_rowByWorkflowName_And_WorkflowStageName($flavorID,$workflowName,$stageName)
	{
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select e.workflowID,e.componentID,e.workflowStageID,e.name,e.description,e.display,e.flavorID,e.editableParcial,e.editableTotal,e.eliminable,e.aplicable,e.vinculable,e.isActive");
		$sql = $sql.sprintf(" from tb_workflow_stage e");
		$sql = $sql.sprintf(" inner join tb_workflow w on w.workflowID = e.workflowID ");
		$sql = $sql.sprintf(" where ");	
		$sql = $sql.sprintf(" e.flavorID = $flavorID ");
		$sql = $sql.sprintf(" and e.isActive= 1");			
		$sql = $sql.sprintf(" and REPLACE(e.name,' ','') = REPLACE('$stageName',' ','') ");
		$sql = $sql.sprintf(" and REPLACE(w.name,' ','') = REPLACE('$workflowName',' ','') ");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	}

}
?>