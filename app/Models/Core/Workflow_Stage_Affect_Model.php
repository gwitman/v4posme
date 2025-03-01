<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;

class Workflow_Stage_Affect_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }    
   function get_rowByTransactionCausalID_And_WorkflowStageID($transactionCausalID,$workflowSourceStageID)
   {
		$db 	= db_connect();
   		$sql = "";
		$sql = sprintf("
			 select 
				x.workflowStageAffectID,
				x.transactionID,
				x.transactionCausalID,
				x.componentSourceID,
				x.workflowSourceID,
				x.workflowSourceStageID,
				x.componentTargetID,
				x.workflowTargetID,
				x.workflowTargetStageID,
				x.reference1,
				x.reference2,
				x.reference3,
				x.isActive 
			from 
				tb_workflow_stage_affect x 
			where 
				x.isActive = 1 and 
				x.transactionCausalID = $transactionCausalID and 
				x.workflowSourceStageID = $workflowSourceStageID  
			order by 
				x.workflowStageAffectID 
	
		");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
	
   }

}
?>