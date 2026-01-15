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
    protected $allowedFields  = ['conversationID','entityIDSource', 'entityIDTarget','componentIDSource','componentIDTarget','createdOn','statusID','messageCounter','messageReceiptOn','messageSendOn','messgeConterNotRead', 'reference1','reference2', 'reference3','isActive'];

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
	
	//Obtener todas las conversaciones de un agente activa
    function getBySystemNameAndFlavorID($systemName,$flavorID)
    {
		$db 		= db_connect();
		$builder	= $db->table("tb_public_catalog");		
		$sql 		= 
			"
				select 
					c.publicCatalogID,c.`name`,
					c.systemName,c.statusID,
					c.orden,c.description,
					c.isActive,c.flavorID 
				from 
					tb_public_catalog c  
				where 
					c.systemName = '".$systemName."' and 
					c.isActive = 1 and 
					c.flavorID = '".$flavorID."';
				
			";
			
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
    }
}
?>