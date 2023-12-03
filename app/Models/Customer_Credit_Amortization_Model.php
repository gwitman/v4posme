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
   function get_rowByCustomerID($customerID)
   {
	   
	   $db 		= db_connect();

		$sql = "";
		$sql = sprintf("select ");
			$sql = $sql.sprintf(" cd.documentNumber,");		
			$sql = $sql.sprintf(" i.dateApply,");		
			$sql = $sql.sprintf(" i.remaining,");
			$sql = $sql.sprintf(" unix_timestamp(i.dateApply)   as Orden,");
			$sql = $sql.sprintf(" DATEDIFF(cast(now() as date),  i.dateApply) as Mora, ");
			$sql = $sql.sprintf(" ws.name as stageCuota, ");
			$sql = $sql.sprintf(" wsd.name as stageDocumento ");
		$sql = $sql.sprintf(" from ");		
			$sql = $sql.sprintf(" tb_customer_credit_amoritization i");		
			$sql = $sql.sprintf(" inner join tb_workflow_stage ws on ");		
			$sql = $sql.sprintf(" i.statusID = ws.workflowStageID ");		
			$sql = $sql.sprintf(" inner join tb_customer_credit_document cd on ");		
			$sql = $sql.sprintf(" cd.customerCreditDocumentID = i.customerCreditDocumentID ");		
			$sql = $sql.sprintf(" inner join tb_workflow_stage wsd on ");		
			$sql = $sql.sprintf(" cd.statusID = wsd.workflowStageID ");		
		$sql = $sql.sprintf(" where ");		
			$sql = $sql.sprintf(" i.isActive = 1 and ");		
			$sql = $sql.sprintf(" ws.vinculable = 1 and ");		
			$sql = $sql.sprintf(" cd.entityID = $customerID and ");		
			$sql = $sql.sprintf(" cd.statusID not in ( 82 /*cancelado*/, 83 /*anulado*/)");		
			
			
		//echo $sql;
	
	
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
   
   function get_rowBySummaryInformationCredit($documentNumber)
   {
	   $db 		= db_connect();

		$sql = "
		select 
			(
					select 
						max(u.dateApply) 
					from 
						tb_customer_credit_amoritization u 
					where 
						u.customerCreditDocumentID = c.customerCreditDocumentID 
			) as fechaVencimiento ,
			(
				select 
					count(u.dateApply) 
				from 
					tb_customer_credit_amoritization u 
				where 
					u.customerCreditDocumentID = c.customerCreditDocumentID and u.remaining > 0 and u.isActive = 1 
			) as numeroCuotasPendiente ,
			(
				select 
					sum(u.remaining) 
				from 
					tb_customer_credit_amoritization u 
				where 
					u.customerCreditDocumentID = c.customerCreditDocumentID and 
					u.remaining > 0  and u.isActive = 1 and 
					u.dateApply < CURRENT_DATE() 
			) as montoEnMora  
		from 
			tb_customer_credit_document c 
		where 
			c.documentNumber = '".$documentNumber."' and 
			c.isActive = 1
		";
				
	
	
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
		
   }
   function get_rowByCreditDocumentAndBalanceMinim($customerCreditDocumentID)
   {
	   
	    $db 		= db_connect();

		$sql = "";
		$sql = sprintf("select ");
			$sql = $sql.sprintf(" i.creditAmortizationID,");		
			$sql = $sql.sprintf(" cd.documentNumber,");		
			$sql = $sql.sprintf(" i.dateApply,");		
			$sql = $sql.sprintf(" i.remaining,");
			$sql = $sql.sprintf(" cast(i.dateApply as int) as Orden,");
			$sql = $sql.sprintf(" DATEDIFF(cast(now() as date),  i.dateApply) as Mora, ");
			$sql = $sql.sprintf(" ws.name as stageCuota, ");
			$sql = $sql.sprintf(" wsd.name as stageDocumento ");
		$sql = $sql.sprintf(" from ");		
			$sql = $sql.sprintf(" tb_customer_credit_amoritization i");		
			$sql = $sql.sprintf(" inner join tb_workflow_stage ws on ");		
			$sql = $sql.sprintf(" i.statusID = ws.workflowStageID ");		
			$sql = $sql.sprintf(" inner join tb_customer_credit_document cd on ");		
			$sql = $sql.sprintf(" cd.customerCreditDocumentID = i.customerCreditDocumentID ");		
			$sql = $sql.sprintf(" inner join tb_workflow_stage wsd on ");		
			$sql = $sql.sprintf(" cd.statusID = wsd.workflowStageID ");		
		$sql = $sql.sprintf(" where ");		
			$sql = $sql.sprintf(" i.isActive = 1 and ");		
			$sql = $sql.sprintf(" ws.vinculable = 1 and ");		
			$sql = $sql.sprintf(" i.remaining between 0 and 0.2 and  ");		
			$sql = $sql.sprintf(" cd.customerCreditDocumentID = $customerCreditDocumentID ");		
	
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
   
}
?>