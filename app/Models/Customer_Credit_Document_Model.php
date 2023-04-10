<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Customer_Credit_Document_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function update_app_posme($customerCreditDocumentID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_document");
		
		$builder->where("customerCreditDocumentID",$customerCreditDocumentID);	
		return $builder->update($data);
		
   }
   function delete_app_posme($customerCreditDocumentID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_document");		
  		$data["isActive"] = 0;
		
		$builder->where("customerCreditDocumentID",$customerCreditDocumentID);	
		return $builder->update($data);
		
   } 
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_document");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }   
   function get_rowByPK($customerCreditDocumentID){
		$db 	     = db_connect();
		$builder	 = $db->table("tb_customer_credit_document");
	    $sql = "";
		$sql = sprintf("select i.customerCreditDocumentID, i.companyID, i.entityID, i.customerCreditLineID,i.documentNumber, i.dateOn, i.amount, i.interes, i.term,i.exchangeRate, i.reference1, i.reference2, i.reference3, i.statusID, i.isActive,i.balance,i.balanceProvicioned,i.currencyID,cur.name as currencyName,cur.simbol as currencySimbol,	(select sum(tccda.remaining) from tb_customer_credit_document tccd inner join tb_customer_credit_amoritization tccda on tccd.customerCreditDocumentID = tccda.customerCreditDocumentID where tccd.customerCreditDocumentID = i.customerCreditDocumentID)  as balanceNew,i.reportSinRiesgo ");
		$sql = $sql.sprintf(" from tb_customer_credit_document i");	
		$sql = $sql.sprintf(" inner join  tb_currency cur on i.currencyID = cur.currencyID");
		$sql = $sql.sprintf(" where i.customerCreditDocumentID = $customerCreditDocumentID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByEntity($companyID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_document");    
		$sql = "";
		$sql = sprintf("select customerCreditDocumentID, companyID, entityID, customerCreditLineID,documentNumber, dateOn, amount, interes, term,exchangeRate, reference1, reference2, reference3, statusID, isActive,balance,i.currencyID,i.reportSinRiesgo");
		$sql = $sql.sprintf(" from tb_customer_credit_document i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.entityID = $entityID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByEntityApplied($companyID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_document");  
		
		$sql = "		
		select 
			i.customerCreditDocumentID, i.companyID, i.entityID, i.customerCreditLineID,i.documentNumber, 
			i.dateOn, i.amount, i.interes, i.term, i.exchangeRate, i.reference1, i.reference2, 
			i.reference3, i.statusID, i.isActive,i.balance,i.currencyID,
			i.reportSinRiesgo,
			sum(cc.remaining) as remaining
		from 
			tb_customer_credit_document i
			inner join tb_customer_credit_amoritization cc on cc.customerCreditDocumentID = i.customerCreditDocumentID
			inner join tb_workflow_stage a on a.workflowStageID = i.statusID 
		where 
			i.companyID = $companyID and 
			i.entityID = $entityID and 
			i.isActive = 1 and 
			a.aplicable= 1
		group by 
			i.customerCreditDocumentID, i.companyID, i.entityID, i.customerCreditLineID,i.documentNumber, 
			i.dateOn, i.amount, i.interes, i.term, i.exchangeRate, i.reference1, i.reference2, 
			i.reference3, i.statusID, i.isActive,i.balance,i.currencyID,
			i.reportSinRiesgo

		";
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	
   }
   
   function get_rowByEntityCreditLine($companyID,$entityID,$creditLineID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_document");    
		$sql = "";
		$sql = sprintf("select customerCreditDocumentID, companyID, entityID, customerCreditLineID,documentNumber, dateOn, amount, interes, term,exchangeRate, reference1, reference2, reference3, statusID, isActive,balance,i.currencyID,i.reportSinRiesgo");
		$sql = $sql.sprintf(" from tb_customer_credit_document i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.entityID = $entityID");
		$sql = $sql.sprintf(" and i.customerCreditLineID = $creditLineID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByDocument($companyID,$entityID,$documentNumber){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_document");
	    $sql = "";
		$sql = sprintf("select customerCreditDocumentID, companyID, entityID, customerCreditLineID,documentNumber, dateOn, amount, interes, term,exchangeRate, reference1, reference2, reference3, statusID, isActive,balance,i.currencyID,i.reportSinRiesgo");
		$sql = $sql.sprintf(" from tb_customer_credit_document i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.entityID = $entityID");
		$sql = $sql.sprintf(" and i.documentNumber = '$documentNumber' ");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   
}
?>