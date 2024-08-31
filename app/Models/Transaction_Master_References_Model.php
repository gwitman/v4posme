<?php 
//posme:2024-08-30
namespace App\Models;
use CodeIgniter\Model;

class Transaction_Master_References_Model extends Model  {

    function __construct(){	
        parent::__construct();
     }

     function delete_app_posme($transactionMasterReferenceID){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_references");
		$data["isActive"] = 0;
	
		$builder->where("transactionMasterReferenceID",$transactionMasterReferenceID);
		
		return $builder->update($data);
   }
   
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_references");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }
   function update_app_posme($transactionMasterReferenceID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_references");
        $builder->where("transactionMasterReferenceID",$transactionMasterReferenceID);
		return $builder->update($data);
   }
   function get_rowByPK($transactionMasterReferenceID){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_references");    
		//Ejecutar Consulta
        $builder = $builder->where("transactionMasterReferenceID",$transactionMasterReferenceID);
		return $builder->get()->getRowObject();
   }
}