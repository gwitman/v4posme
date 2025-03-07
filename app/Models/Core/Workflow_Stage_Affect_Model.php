<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;

class Workflow_Stage_Affect_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }    
   function get_rowByTransactionCausalID_And_WorkflowStageID_AndCondition1
   (
		$transactionID,
		$flavorID,
		$transactionCausalID,
		$componentSourceID,
		$workflowSourceStageID,
		$componentTargetID,
		$condition1
   )
   {
		$db 	= db_connect();
   		$sql = "";
		$sql = sprintf("
			 select 
				x.workflowStageAffectID,
				x.flavorID,
				x.transactionID,
				x.transactionCausalID,
				x.componentSourceID,
				x.workflowSourceID,
				x.workflowSourceStageID,
				x.componentTargetID,
				x.workflowTargetID,
				x.workflowTargetStageID,
				x.workflowTargetStageID as workflowStageID,
				x.reference1,
				x.reference2,
				x.reference3,
				x.isActive,
				x.condition1,
				x.condition2,
				x.condition3
			from 
				tb_workflow_stage_affect x 
			where
				x.transactionID = '$transactionID' and 
				x.flavorID = $flavorID and 
				x.transactionCausalID = '$transactionCausalID' and 
				x.componentSourceID = '$componentSourceID' and 
				x.workflowSourceStageID = '$workflowSourceStageID'  and 
				x.componentTargetID = '$componentTargetID' and 
				x.isActive = 1 and 
				IFNULL(x.condition1, '')  = '$condition1'
			order by 
				x.workflowStageAffectID 
	
		");
		
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
	
   }

}
?>