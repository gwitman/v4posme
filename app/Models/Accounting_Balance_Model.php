<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;


class Accounting_Balance_Model extends Model  {
   function __construct(){
		
      parent::__construct();
   } 
   function updateBalance($companyID,$periodID,$cycleID,$accountID,$balance,$debit,$credit){
		$db 		= db_connect();
	    
		$sql = "		
		UPDATE tb_accounting_balance SET 
			balance 	= balance + $balance , 
			debit 		= debit   + $debit , 
			credit 		= credit  + $credit 
		WHERE 
			companyID 			= $companyID and 
			accountID 			= $accountID and 
			componentPeriodID 	= $periodID and 
			componentCycleID 	= $cycleID;
		";
		
		$r 	= $db->query($sql);
		return $r;
		
   }
   function getMinAccount($companyID,$branchID,$loginID){
		$db 		= db_connect();
		$sql = "
		select min(accountID) as accountID from tb_journal_entry_detail_summary 
		where
			companyID = $companyID and 
			branchID = $branchID and 
			loginID = $loginID ;
		";
		
		
		$r 			= $db->query($sql)->getRow();
		return  	$r->accountID;
		
		
   }
   function getMinAccountBy($companyID,$branchID,$loginID,$accountID){
		$db 		= db_connect();
		
		
		$sql = "
		select min(accountID) as accountID from tb_journal_entry_detail_summary 
		where
			companyID = $companyID and 
			branchID = $branchID and 
			loginID = $loginID and 
			accountID > $accountID;
		";
		
		$r 			= $db->query($sql)->getRow();				
		$accountID 	= $r->accountID;
		return $accountID;
   }
   function getMaxAccount($companyID,$branchID,$loginID){
		$db 		= db_connect();
		$sql = "
		select max(accountID) as accountID from tb_journal_entry_detail_summary 
		where
			companyID = $companyID and 
			branchID = $branchID and 
			loginID = $loginID 
		";
		
		$r 			= $db->query($sql )->getRow();
		$accountID 	= $r->accountID;
		return $accountID;
   }
   function getInfoAccount($companyID,$branchID,$loginID,$accountID){
		$db 		= db_connect();
		
	    
		$sql = "
		select 
			debit,credit
		from 
			tb_journal_entry_detail_summary 
		where
			companyID = $companyID and 
			branchID = $branchID and 
			loginID = $loginID and 
			accountID = $accountID 
		";
		
		
		//Ejecutar Consulta
		 return $db->query($sql)->getRow();
		
   }
   function clearCycle($companyID,$periodID,$cycleID){
		$db 		= db_connect();
		
	    
		 
		$sql = "
		update 
			debit = 0,credit = 0
		from 
			tb_accounting_balance 
		where
			companyID = $companyID and 
			componentPeriodID = $periodID and 
			componentCycleID = $cycleID 
		";
		
		
		return $db->query($sql);
   }
   function deleteJournalEntryDetailSummary($companyID,$branchID,$loginID){
		$db 		= db_connect();
			
		
		$sql = "
		delete from tb_journal_entry_detail_summary 
		where
			companyID = $companyID and 
			branchID = $branchID and 
			loginID = $loginID 
		";
		
		return $db->query($sql);
		
   } 
   function setAccountBalance($companyID,$branchID,$loginID,$cycleID,$periodID,$componentAccountID){
		$db 		= db_connect();
	    
		$sql = "
		INSERT INTO tb_accounting_balance (componentCycleID,componentPeriodID,companyID,componentID,accountID,branchID,balance,debit,credit,classID,isActive)
		SELECT 
			$cycleID,
			$periodID,
			$companyID,
			$componentAccountID,
			a.accountID,
			$branchID,
			0 AS balance,
			0 as debit,
			0 as credit,
			0 as classID,
			1 AS isActive
		FROM 
			tb_account a
		WHERE  
			a.companyID = $companyID and 
			a.accountID NOT IN (SELECT accountID FROM tb_accounting_balance where companyID = $companyID and componentPeriodID = $periodID and componentCycleID = $cycleID and isActive = 1) AND 
			a.isActive = 1;
		";
		
		$r 	= $db->query($sql);
	
   }
   function setJournalSummary($companyID,$branchID,$loginID,$cycleID,$journalTypeClosed){
		$db 		= db_connect();
	    
		$sql = "
		INSERT INTO tb_journal_entry_detail_summary(companyID,branchID,loginID,journalEntryID,accountID,parentAccountID,debit,credit)
		SELECT 
			$companyID,
			$branchID,
			$loginID,
			je.journalEntryID,
			a.accountID,
			a.parentAccountID,
			sum(jed.debit),
			sum(jed.credit)
		FROM
			tb_journal_entry je 
			inner join tb_journal_entry_detail jed on 
					je.journalEntryID = jed.journalEntryID and je.companyID = jed.companyID 
			inner join tb_workflow_stage ws on
					je.statusID = ws.workflowStageID 
			inner join tb_account a on 
					jed.accountID = a.accountID 
		WHERE
			je.companyID = $companyID and 
			je.accountingCycleID = $cycleID and 		
			je.isActive = 1 and 
			jed.isActive = 1 and 
			je.journalTypeID != $journalTypeClosed and 
			(jed.debit + jed.credit)  > 0  
		group by
			accountID;
		";
		
		$r 	= $db->query($sql);
		return $r;
		
	}
	
}
?>