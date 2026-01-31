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
}
?>