<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Customer_Credit_Line_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function update_app_posme($customerCreditLineID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_line");
		
		$builder->where("customerCreditLineID",$customerCreditLineID);
		return $builder->update($data);
		
   }
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_line");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }   
   function delete_app_posme($customerCreditLineID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_line");		
		
  		$data["isActive"] = 0;
		$builder->where("customerCreditLineID",$customerCreditLineID);
		return $builder->update($data);
		
   } 
   function deleteWhereIDNotIn($companyID,$branchID,$entityID,$listCustomerCreditLineID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_line");
		
		$data["isActive"] = 0;
		
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);	
		$builder->where("entityID",$entityID);	
		$builder->whereNotIn("customerCreditLineID",$listCustomerCreditLineID);	
		return $builder->update($data);
		
   }
   function get_rowByEntityAndLine($companyID,$branchID,$entityID,$creditLineID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_line");    
		
		$sql = "";
		$sql = sprintf("select i.customerCreditLineID, i.companyID, i.branchID, i.entityID, i.creditLineID, 
		i.accountNumber, i.currencyID, i.limitCredit, i.balance, i.interestYear, i.interestPay, 
		i.totalPay, i.totalDefeated, i.dateOpen, i.periodPay, i.dateLastPay, i.term, i.note, 
		i.statusID, i.isActive,cr.name as currencyName,
		i.typeAmortization,cl.name as creditLineName");
		$sql = $sql.sprintf(" from tb_customer_credit_line i");
		$sql = $sql.sprintf(" inner join  tb_currency cr on i.currencyID = cr.currencyID");
		$sql = $sql.sprintf(" inner join  tb_credit_line cl on cl.creditLineID = i.creditLineID");
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.branchID = $branchID");
		$sql = $sql.sprintf(" and i.entityID = $entityID");
		$sql = $sql.sprintf(" and i.creditLineID = $creditLineID");
		$sql = $sql.sprintf(" and i.isActive= 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByEntity($companyID,$branchID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_line");    
		
		$sql = "";
		$sql = sprintf("select i.customerCreditLineID, i.companyID, i.branchID, i.entityID, i.creditLineID,
		i.accountNumber, i.currencyID, i.limitCredit, i.balance, i.interestYear, i.interestPay, 
		i.totalPay, i.totalDefeated, i.dateOpen, i.periodPay, i.dateLastPay, i.term, i.note, 
		i.statusID, i.isActive,cl.name as line,ws.name as statusName,cr.name as currencyName,
		i.typeAmortization,ci3.name as typeAmortizationLabel,
		ci2.name as periodPayLabel");
		$sql = $sql.sprintf(" from tb_customer_credit_line i");		
		$sql = $sql.sprintf(" inner join  tb_credit_line cl on i.creditLineID = cl.creditLineID");
		$sql = $sql.sprintf(" inner join  tb_workflow_stage ws on ws.workflowStageID = i.statusID");
		$sql = $sql.sprintf(" inner join  tb_currency cr on i.currencyID = cr.currencyID");
		$sql = $sql.sprintf(" inner join  tb_catalog_item ci2 on i.periodPay = ci2.catalogItemID");
		$sql = $sql.sprintf(" inner join  tb_catalog_item ci3 on i.typeAmortization = ci3.catalogItemID");
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.branchID = $branchID");
		$sql = $sql.sprintf(" and i.entityID = $entityID");
		$sql = $sql.sprintf(" and i.isActive= 1");
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
    function get_rowByBranchID($companyID,$branchID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_line");    
		
		$sql = "";
		$sql = sprintf("	select 
								i.customerCreditLineID, i.companyID, i.branchID, i.entityID, i.creditLineID,
								i.accountNumber, i.currencyID, i.limitCredit, i.balance, i.interestYear, i.interestPay, 
								i.totalPay, i.totalDefeated, i.dateOpen, i.periodPay, i.dateLastPay, i.term, i.note, 
								i.statusID, i.isActive,cl.name as line,ws.name as statusName,cr.name as currencyName,
								i.typeAmortization,ci3.name as typeAmortizationLabel,
								ci2.name as periodPayLabel
				");
		$sql = $sql.sprintf(" from 
								tb_customer_credit_line i");		
		$sql = $sql.sprintf(" 	inner join  tb_credit_line cl on i.creditLineID = cl.creditLineID");
		$sql = $sql.sprintf(" 	inner join  tb_workflow_stage ws on ws.workflowStageID = i.statusID");
		$sql = $sql.sprintf(" 	inner join  tb_currency cr on i.currencyID = cr.currencyID");
		$sql = $sql.sprintf(" 	inner join  tb_catalog_item ci2 on i.periodPay = ci2.catalogItemID");
		$sql = $sql.sprintf(" 	inner join  tb_catalog_item ci3 on i.typeAmortization = ci3.catalogItemID");
		$sql = $sql.sprintf(" where 
								i.companyID = $companyID");
		$sql = $sql.sprintf(" 	and i.branchID = $branchID");		
		$sql = $sql.sprintf(" 	and i.isActive= 1");
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByPK($customerCreditLineID){
		$db 	= db_connect();
		$builder	= $db->table("tb_customer_credit_line");    
		
		$sql = "";
		$sql = sprintf("select customerCreditLineID, companyID, branchID, entityID, creditLineID, accountNumber, currencyID, limitCredit, balance, interestYear, interestPay, totalPay, totalDefeated, dateOpen, periodPay, dateLastPay, term, note, statusID, isActive,typeAmortization");
		$sql = $sql.sprintf(" from tb_customer_credit_line i");		
		$sql = $sql.sprintf(" where i.customerCreditLineID = $customerCreditLineID");
		$sql = $sql.sprintf(" and i.isActive= 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
}
?>