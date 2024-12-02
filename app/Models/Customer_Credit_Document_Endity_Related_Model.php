<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Customer_Credit_Document_Endity_Related_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   function update_app_posme($entityRelatedID, $customerCreditDocumentID, $data){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_document_entity_related");
		
        $builder->where("ccEntityRelatedID",$entityRelatedID);	
        $builder->where("customerCreditDocumentID",$customerCreditDocumentID);	
		return $builder->update($data);
		
   }
   function delete_app_posme($customerCreditDocumentID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_document_entity_related");		
		
  		$data["isActive"] = 0;
        $builder->where("customerCreditDocumentID",$customerCreditDocumentID);	
        $builder->where("entityID",$entityID);	
		return $builder->update($data);
		
   } 

   function deleteWhereIDNotIn($ccEntityRelatedID, $customerCreditEntityDocumentID, $entityID)
   {
		$db 		= db_connect();
		$builder	= $db->table("tb_customer_credit_document_entity_related");

		$data["isActive"] = 0;

		$builder->where("customerCreditDocumentID", $customerCreditEntityDocumentID);
		$builder->where("entityID", $entityID);
		$builder->whereNotIn("ccEntityRelatedID", $ccEntityRelatedID);
		return $builder->update($data);
   }

   function deleteWhereDocumentID($customerCreditEntityDocumentID)
   {
		$db 		= db_connect();
		$builder	= $db->table("tb_customer_credit_document_entity_related");

		$data["isActive"] = 0;

		$builder->where("customerCreditDocumentID", $customerCreditEntityDocumentID);
		return $builder->update($data);
   }

   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_document_entity_related");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }   
   function get_rowByPK($ccEntityRelatedID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_document_entity_related");
	    $sql = "";
		$sql = sprintf("select ccEntityRelatedID,customerCreditDocumentID,entityID,type,typeCredit,statusCredit,typeGarantia,typeRecuperation,ratioDesembolso,ratioBalance,ratioBalanceExpired,ratioShare,createdOn,createdBy,createdIn,createdAt,isActive");
		$sql = $sql.sprintf(" from tb_customer_credit_document_entity_related i");	
		$sql = $sql.sprintf(" where i.ccEntityRelatedID = $ccEntityRelatedID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByEntity($customerCreditDocumentID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_document_entity_related");    
        $sql = "";
		$sql = sprintf("select ccEntityRelatedID,customerCreditDocumentID,entityID,type,typeCredit,statusCredit,typeGarantia,typeRecuperation,ratioDesembolso,ratioBalance,ratioBalanceExpired,ratioShare,createdOn,createdBy,createdIn,createdAt,isActive");
        $sql = $sql.sprintf(" from tb_customer_credit_document_entity_related i");	
        $sql = $sql.sprintf(" where i.customerCreditDocumentID = $customerCreditDocumentID");
        $sql = $sql.sprintf(" and i.entityID = $entityID");
        $sql = $sql.sprintf(" and i.isActive= 1");		
        
        //Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByDocument($customerCreditDocumentID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_document_entity_related");    
		$sql = "";
		$sql = sprintf("select ccEntityRelatedID,customerCreditDocumentID,entityID,type,typeCredit,statusCredit,typeGarantia,typeRecuperation,ratioDesembolso,ratioBalance,ratioBalanceExpired,ratioShare,createdOn,createdBy,createdIn,createdAt,isActive");
		$sql = $sql.sprintf(" from tb_customer_credit_document_entity_related i");		
		$sql = $sql.sprintf(" where i.customerCreditDocumentID = $customerCreditDocumentID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
}
?>