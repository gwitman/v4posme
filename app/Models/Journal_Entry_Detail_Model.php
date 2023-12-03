<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Journal_Entry_Detail_Model extends Model  {
   function __construct(){	
      parent::__construct();
   } 
  function delete_app_posme($companyID,$journalEntryID,$journalEntryDetailID){
		$db 	= db_connect();
		$builder	= $db->table("tb_journal_entry_detail");		
  		$data["isActive"] = 0;
		
		$builder->where("companyID",$companyID);
		$builder->where("journalEntryID",$journalEntryID);	
		$builder->where("journalEntryDetailID",$journalEntryDetailID);	
		return $builder->update($data);
		
   } 
   function deleteWhereIDNotIn($companyID,$journalEntryID,$listDetailID){
		$db 	= db_connect();
		$builder	= $db->table("tb_journal_entry_detail");
		
		$data["isActive"] = 0;
		$builder->where("companyID",$companyID);
		$builder->where("journalEntryID",$journalEntryID);	
		$builder->whereNotIn("journalEntryDetailID",$listDetailID);			
		return $builder->update($data);
		
   }
   function update_app_posme($companyID,$journalEntryID,$journalEntryDetailID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_journal_entry_detail");		
		
		$builder->where("companyID",$companyID);
		$builder->where("journalEntryID",$journalEntryID);	
		$builder->where("journalEntryDetailID",$journalEntryDetailID);	
		return $builder->update($data);
		
   }
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_journal_entry_detail");	
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
		 
   }
   function get_rowByJournalEntryID($companyID,$journalEntryID){
		$db 	= db_connect();
		$builder	= $db->table("tb_journal_entry_detail");
		
		$sql = "";
		$sql = sprintf("select jed.journalEntryDetailID,jed.journalEntryID,jed.companyID,jed.accountID,jed.isActive,jed.classID,jed.debit,jed.credit,jed.note,jed.isApplied,jed.branchID,jed.tb_exchange_rate,cc.number as classNumber,a.accountNumber,a.name as accountName");
		$sql = $sql.sprintf(" from tb_journal_entry_detail jed");
		$sql = $sql.sprintf(" inner join  tb_account a on jed.accountID = a.accountID");
		$sql = $sql.sprintf(" left join  tb_center_cost cc on jed.classID = cc.classID");
		$sql = $sql.sprintf(" where jed.companyID = $companyID");
		$sql = $sql.sprintf(" and jed.journalEntryID = $journalEntryID");		
		$sql = $sql.sprintf(" and jed.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByPK($companyID,$journalEntryID,$journalEntryDetailID){
		$db 	= db_connect();
		$builder	= $db->table("tb_journal_entry_detail");    
		
		$sql = "";
		$sql = sprintf("select jed.journalEntryDetailID,jed.journalEntryID,jed.companyID,jed.accountID,jed.isActive,jed.classID,jed.debit,jed.credit,jed.note,jed.isApplied,jed.branchID,jed.tb_exchange_rate,cc.number as classNumber,a.accountNumber");
		$sql = $sql.sprintf(" from tb_journal_entry_detail jed");
		$sql = $sql.sprintf(" inner join  tb_account a on jed.accountID = a.accountID");
		$sql = $sql.sprintf(" left join  tb_center_cost cc on jed.classID = cc.classID");
		$sql = $sql.sprintf(" where jed.companyID = $companyID");
		$sql = $sql.sprintf(" and jed.journalEntryID = $journalEntryID");
		$sql = $sql.sprintf(" and jed.journalEntryDetailID = $journalEntryDetailID");
		$sql = $sql.sprintf(" and jed.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
  
}
?>