<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Component_Audit_Detail_Model extends Model  {
    function __construct(){
		
      parent::__construct();
    }
    function insert_app_posme($data){
		$db 		= db_connect();   
		$builder	= $db->table("tb_component_audit_detail");
		
		$result			 	= $builder->insert($data);
		$id 				= $db->insertID(); 		
		return $id;
   }
   function getAuditDetail($companyID,$id,$elementID){
		$db 	= db_connect();				
		
		$sql = "
			SELECT  
				cu.modifiedOn,
				u.nickname,

				CASE 
					WHEN se.subElementID = 342 AND se.elementID = 417 THEN 'Estado'
					ELSE se.name
				END AS name,

				CASE 
					WHEN ws.workflowStageID IS NOT NULL THEN ws.name
					WHEN ci.catalogItemID IS NOT NULL THEN ci.name
					ELSE cud.oldValue
				END AS oldValue,

				CASE 
					WHEN ws2.workflowStageID IS NOT NULL THEN ws2.name
					WHEN ci2.catalogItemID IS NOT NULL THEN ci2.name
					ELSE cud.newValue
				END AS newValue

			FROM tb_component_audit cu

			JOIN tb_component_audit_detail cud 
				ON cu.companyID = cud.companyID 
				AND cu.componentAuditID = cud.componentAuditID

			JOIN tb_user u 
				ON cu.modifiedBy = u.userID

			JOIN tb_subelement se 
				ON cud.fieldID = se.subElementID

			LEFT JOIN tb_workflow_stage ws 
				ON cud.oldValue = ws.workflowStageID

			LEFT JOIN tb_workflow_stage ws2 
				ON cud.newValue = ws2.workflowStageID

			LEFT JOIN tb_catalog_item ci 
				ON cud.oldValue = ci.catalogItemID

			LEFT JOIN tb_catalog_item ci2 
				ON cud.newValue = ci2.catalogItemID 
				
			WHERE 
				`cu`.`companyID` =  ".$companyID."
				AND `cu`.`elementItemID` =  ".$id."
				AND `cu`.`elementID` =  ".$elementID."
			ORDER BY 
				`cu`.`modifiedOn` desc
		";
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
			
   }
}
?>