<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Customer_Credit_Amortization_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function update_app_posme($creditAmortizationID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_amoritization");
		
		$builder->where("creditAmortizationID",$creditAmortizationID);	
		return $builder->update($data);
		
   }
   function delete_app_posme($creditAmortizationID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_amoritization");		
  		$data["isActive"] = 0;
		
		$builder->where("creditAmortizationID",$creditAmortizationID);	
		return $builder->update($data);
		
   } 
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_amoritization");
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }   
   function get_rowByPK($creditAmortizationID){
		$db 	= db_connect();
		
	    $sql = "";
		$sql = sprintf("select creditAmortizationID, customerCreditDocumentID, balanceStart,dateApply, interest, capital, `share`, balanceEnd, remaining, dayDelay, note, statusID, isActive,shareCapital");
		$sql = $sql.sprintf(" from tb_customer_credit_amoritization i");		
		$sql = $sql.sprintf(" where i.creditAmortizationID = $creditAmortizationID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByDocument($customerCreditDocumentID){
		$db 	= db_connect();
		    
		$sql = "";
		$sql = sprintf("select creditAmortizationID, customerCreditDocumentID,balanceStart, dateApply, interest, capital, `share`, balanceEnd, remaining, dayDelay, note, statusID, isActive,shareCapital");
		$sql = $sql.sprintf(" from tb_customer_credit_amoritization i");		
		$sql = $sql.sprintf(" where i.customerCreditDocumentID = $customerCreditDocumentID");
		$sql = $sql.sprintf(" and i.isActive= 1");		
		$sql = $sql.sprintf(" order by i.creditAmortizationID asc"); 
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByDocumentAndVinculable($customerCreditDocumentID){
		$db 	= db_connect();
		    
		
		$sql = "";
		$sql = sprintf("select i.creditAmortizationID, i.customerCreditDocumentID,i.balanceStart, i.dateApply, i.interest, i.capital, i.`share`, i.balanceEnd, i.remaining, i.dayDelay, i.note, i.statusID, i.isActive,i.shareCapital");
		$sql = $sql.sprintf(" from tb_customer_credit_amoritization i");		
		$sql = $sql.sprintf(" inner join  tb_workflow_stage ws on i.statusID = ws.workflowStageID");		
		$sql = $sql.sprintf(" where i.customerCreditDocumentID = $customerCreditDocumentID");
		$sql = $sql.sprintf(" and i.isActive= 1");
		$sql = $sql.sprintf(" and ws.vinculable= 1");
		$sql = $sql.sprintf(" order by i.dateApply asc"); 

		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByDocumentAndNonVinculable($customerCreditDocumentID){
		$db 		= db_connect();
		    
		
		$sql = "";
		$sql = sprintf("select i.creditAmortizationID, i.customerCreditDocumentID,i.balanceStart, i.dateApply, i.interest, i.capital, i.`share`, i.balanceEnd, i.remaining, i.dayDelay, i.note, i.statusID, i.isActive,i.shareCapital");
		$sql = $sql.sprintf(" from tb_customer_credit_amoritization i");		
		$sql = $sql.sprintf(" inner join  tb_workflow_stage ws on i.statusID = ws.workflowStageID");		
		$sql = $sql.sprintf(" where i.customerCreditDocumentID = $customerCreditDocumentID");
		$sql = $sql.sprintf(" and i.isActive= 1");
		$sql = $sql.sprintf(" and ws.vinculable = 1");
		$sql = $sql.sprintf(" order by i.creditAmortizationID asc"); 

		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }   
   function get_rowShareLate($companyID){
		$db 	= db_connect();
		
		
	    $sql = "";
		$sql = sprintf("select c.customerNumber,n.firstName,n.lastName,c.birthDate,ccd.documentNumber,ccd.currencyID,ccd.reportSinRiesgo,cca.dateApply,cca.remaining,cca.shareCapital");
		$sql = $sql.sprintf(" from tb_customer c");
		$sql = $sql.sprintf(" inner join  tb_naturales n on n.entityID = c.entityID");		
		$sql = $sql.sprintf(" inner join  tb_customer_credit_document ccd on c.entityID = ccd.entityID");		
		$sql = $sql.sprintf(" inner join  tb_customer_credit_amoritization cca on ccd.customerCreditDocumentID = cca.customerCreditDocumentID");		
		$sql = $sql.sprintf(" inner join  tb_workflow_stage cca_status on cca_status.workflowStageID = cca.statusID");
		$sql = $sql.sprintf(" inner join  tb_workflow_stage ccd_status on ccd_status.workflowStageID = ccd.statusID");
		$sql = $sql.sprintf(" where c.companyID = $companyID");
		$sql = $sql.sprintf(" and ccd_status.vinculable= 1");
		$sql = $sql.sprintf(" and c.isActive= 1");
		$sql = $sql.sprintf(" and cca.remaining > 0");
		$sql = $sql.sprintf(" and cca.dateApply < CURDATE()");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
}
?>