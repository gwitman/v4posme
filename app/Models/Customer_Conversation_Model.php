<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;


class Customer_Conversation_Model extends Model  
{
	 
	 
    protected $table      = 'tb_customer_conversation';
    protected $primaryKey = 'conversationID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields  = ['conversationID','entityIDSource', 'entityIDTarget','componentIDSource','componentIDTarget','createdOn','statusID','messageCounter','messageReceiptOn','messageSendOn','messgeConterNotRead', 'reference1','reference2', 'reference3','lastActivityOn','lastMessage','isActive'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
	
	function insert_app_posme($data){
		$db 		= db_connect();
		$db->query("SET NAMES utf8mb4");
		$builder	= $db->table("tb_customer_conversation");
		$result		= $builder->insert($data);
		return $db->insertID();		
    }
    function update_app_posme($conversationID,$data){
		$db 		= db_connect();
		$db->query("SET NAMES utf8mb4");
		$builder	= $db->table("tb_customer_conversation");				
		
		$builder->where("conversationID",$conversationID);	
		return $builder->update($data);
		
    }
	function update_app_posme_ByCustomer($entityIDCustomer,$data){
		$db 		= db_connect();
		$db->query("SET NAMES utf8mb4");
		$builder	= $db->table("tb_customer_conversation");				
		
		$builder->where("entityIDSource",$entityIDCustomer);	
		return $builder->update($data);
		
    }
	//Obtener todas las conversaciones de un cliente
    function getByEntityIDCustomer_StatusNameRegister($entityIDCustomer)
    {
		$db 		= db_connect();
		$builder	= $db->table("tb_customer_conversation");		
		$sql 		= 
			"
				SELECT 
					c.conversationID,
					c.entityIDSource,
					c.entityIDTarget,
					c.componentIDSource,
					c.componentIDTarget,
					c.createdOn,
					c.statusID,
					c.messageCounter,
					c.messageReceiptOn,
					c.messageSendOn,
					c.messgeConterNotRead,
					c.reference1,
					c.reference2,
					c.reference3,
					c.isActive,
					c.lastActivityOn,
					c.lastMessage 
				FROM 
					tb_customer_conversation c 
					inner join tb_workflow_stage ws on 
						ws.workflowStageID = c.statusID 
				where 
					c.entityIDSource = $entityIDCustomer and 
					c.isActive = 1 and 
					ws.isInit = 1 
				
			";
			
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
    }
	//Obtener todas las conversaciones por id
    function getByConversationID($conversationID)
    {
		$db 		= db_connect();
		$builder	= $db->table("tb_customer_conversation");		
		$sql 		= 
			"
				SELECT 
					c.conversationID,
					c.entityIDSource,
					c.entityIDTarget,
					c.componentIDSource,
					c.componentIDTarget,
					c.createdOn,
					c.statusID,
					c.messageCounter,
					c.messageReceiptOn,
					c.messageSendOn,
					c.messgeConterNotRead,
					c.reference1,
					c.reference2,
					c.reference3,
					c.isActive,
					c.lastActivityOn,
					c.lastMessage 
				FROM 
					tb_customer_conversation c 
					inner join tb_workflow_stage ws on 
						ws.workflowStageID = c.statusID 
				where 
					c.conversationID = $conversationID and 
					c.isActive = 1 
				
			";
			
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
    }

	//Obtener todas las conversaciones de un agente
	function getByEntityEntityIDEmployer_StatusNameRegister($entityIDEmployer)
	{
		$db 		= db_connect();
		$builder	= $db->table("tb_customer_conversation");		
		$sql 		= 
			"
				SELECT 
					c.conversationID,
					c.entityIDSource,
					c.entityIDTarget,
					c.componentIDSource,
					c.componentIDTarget,
					c.createdOn,
					c.statusID,
					c.messageCounter,
					c.messageReceiptOn,
					c.messageSendOn,
					c.messgeConterNotRead,
					c.reference1,
					c.reference2,
					c.reference3,
					c.isActive,
					c.lastActivityOn,
					c.lastMessage 
				FROM 
					tb_customer_conversation c 
					inner join tb_workflow_stage ws on 
						ws.workflowStageID = c.statusID 
					inner join tb_company_component_relation ccr on 
						ccr.componentItemIDSource =  c.conversationID 
				where 
					ccr.componentItemIDTarget = $entityIDEmployer and 
					c.isActive = 1 and 
					ws.isInit = 1 
				
			";
			
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
    }
	function getByAll_StatusNameRegister()
	{
		$db 		= db_connect();
		$builder	= $db->table("tb_customer_conversation");		
		$sql 		= 
			"
				SELECT 
					c.conversationID,
					c.entityIDSource,
					c.entityIDTarget,
					c.componentIDSource,
					c.componentIDTarget,
					c.createdOn,
					c.statusID,
					c.messageCounter,
					c.messageReceiptOn,
					c.messageSendOn,
					c.messgeConterNotRead,
					c.reference1,
					c.reference2,
					c.reference3,
					c.isActive,
					c.lastActivityOn,
					c.lastMessage 
				FROM 
					tb_customer_conversation c 
					inner join tb_workflow_stage ws on 
						ws.workflowStageID = c.statusID 
				where 
					c.isActive = 1 and 
					ws.isInit = 1 
				
			";
			
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
    }
	function getBy_StartOn_EndOn_EmployerID_InboxID_StatusID($startOn,$endOn,$entityIDEmployer,$inboxID,$statusID)
	{
		$db 		= db_connect();
		$builder	= $db->table("tb_customer_conversation");		
		$sql 		= 
			"
				SELECT 
				    cus.customerID,
					cus.entityID,
					cus.phoneNumber,
					cus.customerNumber,
					nat.firstName , 
					cus.identification,
					emp.employeNumber,
					emp.entityID as entityIDEmployer,
					natp.firstName as firstNameEmployer,
					c.conversationID,
					c.entityIDSource,
					c.entityIDTarget,
					c.componentIDSource,
					c.componentIDTarget,
					c.createdOn,
					c.statusID,					
					c.messageCounter,
					c.messageReceiptOn,
					c.messageSendOn,					
					DATE_FORMAT(c.messageReceiptOn, '%Y-%m-%d %h:%i %p') AS messageReceiptOnStr,
					DATE_FORMAT(c.messageSendOn,    '%Y-%m-%d %h:%i %p') AS messageSendOnStr,  
					CASE
						WHEN c.messageSendOn IS NULL THEN  0
						WHEN c.messageReceiptOn IS NULL THEN  0
						WHEN c.messageSendOn >= NOW() THEN 0
						WHEN c.messageSendOn > c.messageReceiptOn THEN 0
						ELSE DATEDIFF(c.messageReceiptOn, c.messageSendOn) 
					END AS dayNotContacted,
					c.messgeConterNotRead,
					c.reference1,
					c.reference2,
					c.reference3,
					c.isActive,
					c.lastActivityOn,
					c.lastMessage 
				FROM 
					tb_customer_conversation c 
					inner join tb_workflow_stage ws on 
						ws.workflowStageID = c.statusID 
					inner join tb_customer cus on 
						cus.entityID = c.entityIDSource 
					inner join tb_naturales nat on 
						nat.entityID = cus.entityID 
					inner join tb_company_component_relation ccr on 
						ccr.componentItemIDSource = c.conversationID 
					inner join tb_employee emp on 
						ccr.componentItemIDTarget = emp.entityID 
					inner join tb_naturales natp on 
						natp.entityID = emp.entityID 
				where 
					c.isActive = 1 and 
					ws.isInit = 1  and
					( 
						($entityIDEmployer  = 0 ) or 
						(emp.entityID = $entityIDEmployer and $entityIDEmployer  != 0) 
					) and 
					(
						($statusID  = 0 ) or 
						(c.statusID = $statusID and $statusID != 0 )
					) and 
					(
					  /*filtrar las cerradas*/
					  (	$statusID = 0 /*todas*/) or 
					  (
						$statusID = 205 /*abiertas*/ and 
						c.createdOn between '$startOn' and '$endOn 23:59:59' 						
					  ) or 
					  (
						$statusID = 206 /*cerradas*/ and 
						c.lastActivityOn between '$startOn' and '$endOn 23:59:59' 
					  )
					)
				order by 
					cus.entityID 
					
				
			";
			
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	}
}
?>