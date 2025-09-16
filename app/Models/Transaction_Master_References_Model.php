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
   
   function update_app_posme_by_transactionMasterID($transactionMasterID,$data){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_references");
        $builder->where("transactionMasterID",$transactionMasterID);
		$result 	=  $builder->update($data);		
		return  $result;
   }
   
   function get_rowByPK($transactionMasterReferenceID){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_references ttmr");
		$builder 	= $builder->select('ttmr.transactionMasterReferenceID,
										ttmr.transactionMasterID,
										ttmr.transactionReferenceNumber,
										ttmr.createdOn,
										ttmr.isActive,
										ttmr.reference1,
										ttmr.reference2,
										ttmr.reference3,
										ttmr.refernece4,
										ttmr.refernece5,
										ttmr.reference6,
										ttmr.reference7,  
										ttmr.reference8,
										ttmr.referecne9,
										ttmr.reference10,
										ttmr.reference11,
										ttmr.reference12,
										ttmr.reference13,
										ttmr.reference14,
										ttmr.reference15,
										ttmr.reference16,
										ttmr.reference17,
										ttmr.reference18,
										ttmr.reference19,
										ttmr.reference20,
										ttmr.reference21,
										ttmr.reference22'
		);
		//Ejecutar Consulta
        $builder = $builder->where("transactionMasterReferenceID",$transactionMasterReferenceID);
		return $builder->get()->getRowObject();
   }
   function get_rowByTransactionMaster($transactionMasterID){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_references ttmr");
		$builder 	= $builder->select('ttmr.transactionMasterReferenceID,
										ttmr.transactionMasterID,
										ttmr.transactionReferenceNumber,
										ttmr.createdOn,
										ttmr.isActive,
										ttmr.reference1,
										ttmr.reference2,
										ttmr.reference3,
										ttmr.refernece4,
										ttmr.refernece5,
										ttmr.reference6,
										ttmr.reference7,  
										ttmr.reference8,
										ttmr.referecne9,
										ttmr.reference10,
										ttmr.reference11,
										ttmr.reference12,
										ttmr.reference13,
										ttmr.reference14,
										ttmr.reference15,
										ttmr.reference16,
										ttmr.reference17,
										ttmr.reference18,
										ttmr.reference19,
										ttmr.reference20,
										ttmr.reference21,
										ttmr.reference22'
		);
		//Ejecutar Consulta
        $builder = $builder->where("transactionMasterID",$transactionMasterID);
		return $builder->get()->getRowObject();
   }
}