<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Company_Component_Relation_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_company_component_relation");
		$result		= $builder->insert($data);
		return $result;
   }
   function update_app_posme($companyComponentRelationID,$data){
		$db 		= db_connect();
		$builder	= $db->table("tb_company_component_relation");
		
		$builder->where("companyComponentRelationID", $companyComponentRelationID);
		return $builder->update($data);
		
   }
   function delete_app_posme_byComponentIDSource_AndComponentItemSourceID($componentIDConversation,$conversationID){
		$db 		= db_connect();
		$builder	= $db->table("tb_company_component_relation");		
  		
		
		
		$builder->where("componentIDSource",$componentIDConversation);
		$builder->where("componentItemIDSource",$conversationID);
		return $builder->delete();
		
   } 
   function get_rowByPK($companyComponentRelationID)
   {
		$db 	= db_connect();
		    
		$sql = "";
		$sql = sprintf("select 
						c.companyComponentRelationID,
						c.componentIDSource,
						c.componentItemIDSource,
						c.componentIDTarget,
						c.componentItemIDTarget,
						c.isActive,
						c.note,
						c.reference1,
						c.reference2,
						c.reference3  
						");
		$sql = $sql.sprintf(" from tb_company_component_relation c");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   
   function get_ConversationEmployerBy_entityIDCustomer($entityIDCustomer)
   {
	    $db  = db_connect();
		    
		$sql = "";
		$sql = sprintf("
		select 
			distinct 
		    nat.entityID,
			emp.employeNumber,
			nat.firstName 
		from 
			tb_company_component_relation r
			inner join tb_employee emp on 
				emp.entityID = r.componentItemIDTarget  
			inner join tb_naturales nat on 
				nat.entityID = emp.entityID 
			inner join tb_customer_conversation cc on 
				cc.conversationID = r.componentItemIDSource 
			inner join tb_naturales cus on 
				cus.entityID = cc.entityIDSource 
		where 
			r.isActive = 1 and 
			cus.entityID = $entityIDCustomer and 
			nat.isActive = 1 
			
		");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }	
   function get_ConversationEmployerBy_ConversationID($conversationID)
   {
	    $db  = db_connect();
		    
		$sql = "";
		$sql = sprintf("
		select 
			distinct 
		    nat.entityID,
			emp.employeNumber,
			nat.firstName 
		from 
			tb_company_component_relation r
			inner join tb_employee emp on 
				emp.entityID = r.componentItemIDTarget  
			inner join tb_naturales nat on 
				nat.entityID = emp.entityID 
		where 
			r.isActive = 1 and 
			r.componentItemIDSource = $conversationID and 
			nat.isActive = 1 
			
		");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }   
   function get_ConversationEmployerBy_entityIDEmployerAnd_ConversationID($entityIDEmployer,$converationID)
   {
	    $db  = db_connect();
		    
		$sql = "";
		$sql = sprintf("
		select 
			distinct 
		    nat.entityID,
			emp.employeNumber,
			nat.firstName 
		from 
			tb_company_component_relation r
			inner join tb_employee emp on 
				emp.entityID = r.componentItemIDTarget  
			inner join tb_naturales nat on 
				nat.entityID = emp.entityID 
				
			inner join tb_customer_conversation cc on 
				cc.conversationID = r.componentItemIDSource 
			inner join tb_naturales cus on 
				cus.entityID = cc.entityIDSource 
		where 
			r.isActive = 1 and 
			r.componentItemIDSource = $converationID and 
			nat.isActive = 1 and 
			emp.entityID = $entityIDEmployer;
			
		");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   } 
   
}
?>