<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;

class Component_Autorization_Detail_Page_Model extends Model  {
   function __construct(){	
      parent::__construct();
   } 
 
   
   function get_rowByWorkflowStageID_And_RoleID_Type($companyID,$roleID,$flavorID,$workflowStageID,$type){
	    
		$db 		= db_connect();
		
		$sql = "";
		$sql = sprintf("select 
							i.componentAutorizationDetailPageID,
							i.companyID,
							i.componentAuditID,
							i.workflowStageID,
							i.roleID,
							i.`name`,
							i.type,
							i.flavorID,
							i.scriptCss,
							i.scriptJavas,
							i.scriptHtm,
							i.isActive
						");
		$sql = $sql.sprintf(" 
						from 
							tb_component_autorization_detail_page i");		
		$sql = $sql.sprintf(" 
						where 
							i.isActive = 1 and 
							i.roleID = ".$roleID." and 
							i.companyID = ".$companyID." and 
							i.workflowStageID = ".$workflowStageID." and 
							i.type = '".$type."' and 
							i.flavorID = ".$flavorID."
							");
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
  
}
?>