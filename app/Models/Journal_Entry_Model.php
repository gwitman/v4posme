<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Journal_Entry_Model extends Model  {
   function __construct(){		
      parent::__construct();
   } 
  function delete_app_posme($companyID,$journalEntryID){
		$db 	= db_connect();
		$builder	= $db->table("tb_journal_entry");		
  		$data["isActive"] = 0;
		
		$builder->where("companyID",$companyID);
		$builder->where("journalEntryID",$journalEntryID);	
		return $builder->update($data);
		
   } 
   
   function update_app_posme($companyID,$journalEntryID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_journal_entry");		
		
		$builder->where("companyID",$companyID);
		$builder->where("journalEntryID",$journalEntryID);	
		return $builder->update($data);
		
   }
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_journal_entry");	
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
		 
   }
   function get_rowByCode($companyID,$journalNumber){
		$db 	= db_connect();
		$builder	= $db->table("tb_journal_entry");    
		
		$sql = "";
		$sql = sprintf("select je.journalEntryID,je.companyID,je.journalNumber,je.entryName,je.journalDate,je.tb_exchange_rate,je.createdOn,je.createdIn,je.createdAt,je.createdBy,je.isActive,je.isApplied,je.statusID,je.note,je.reference1,je.reference2,je.reference3,je.journalTypeID,je.currencyID,je.accountingCycleID,ws.name as workflowStageName,ci.display as journalTypeName,cu.name currencyName,cu.simbol  as currencySimbol,je.isModule,je.transactionMasterID");
		$sql = $sql.sprintf(" from tb_journal_entry je");
		$sql = $sql.sprintf(" inner join  tb_workflow_stage ws on je.statusID = ws.workflowStageID");
		$sql = $sql.sprintf(" inner join  tb_catalog_item ci on je.journalTypeID = ci.catalogItemID");
		$sql = $sql.sprintf(" inner join  tb_currency cu on je.currencyID = cu.currencyID");
		$sql = $sql.sprintf(" where je.companyID = $companyID");
		$sql = $sql.sprintf(" and je.journalNumber = '$journalNumber' ");
		$sql = $sql.sprintf(" and je.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByPK($companyID,$journalEntryID){
		$db 	= db_connect();
		$builder	= $db->table("tb_journal_entry");    
		
		$sql = "";
		$sql = sprintf("select je.journalEntryID,je.companyID,je.journalNumber,je.entryName,je.journalDate,je.tb_exchange_rate,je.createdOn,je.createdIn,je.createdAt,je.createdBy,je.isActive,je.isApplied,je.statusID,je.note,je.reference1,je.reference2,je.reference3,je.journalTypeID,je.currencyID,je.accountingCycleID,ws.name as workflowStageName,ci.display as journalTypeName,cu.name currencyName, cu.simbol  as currencySimbol ,je.isModule,je.transactionMasterID,je.isTemplated,je.titleTemplated");
		$sql = $sql.sprintf(" from tb_journal_entry je");
		$sql = $sql.sprintf(" inner join  tb_workflow_stage ws on je.statusID = ws.workflowStageID");
		$sql = $sql.sprintf(" inner join  tb_catalog_item ci on je.journalTypeID = ci.catalogItemID");
		$sql = $sql.sprintf(" inner join  tb_currency cu on je.currencyID = cu.currencyID");
		$sql = $sql.sprintf(" where je.companyID = $companyID");
		$sql = $sql.sprintf(" and je.journalEntryID = $journalEntryID");
		$sql = $sql.sprintf(" and je.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByPK_Next($companyID,$journalEntryID){
		$db 	= db_connect();
		$builder	= $db->table("tb_journal_entry");    
		
		$sql = "";
		$sql = sprintf("select je.journalEntryID,je.companyID,je.journalNumber,je.entryName,je.journalDate,je.tb_exchange_rate,je.createdOn,je.createdIn,je.createdAt,je.createdBy,je.isActive,je.isApplied,je.statusID,je.note,je.reference1,je.reference2,je.reference3,je.journalTypeID,je.currencyID,je.accountingCycleID,je.isModule,je.transactionMasterID");
		$sql = $sql.sprintf(" from tb_journal_entry je");		
		$sql = $sql.sprintf(" where je.companyID = $companyID");
		$sql = $sql.sprintf(" and je.journalEntryID >= $journalEntryID");
		$sql = $sql.sprintf(" and je.isActive= 1");			
		$sql = $sql.sprintf(" order by je.journalEntryID asc");		
		$sql = $sql.sprintf(" limit 0,1 ");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
	function get_rowByPK_Back($companyID,$journalEntryID){
		$db 	= db_connect();
		$builder	= $db->table("tb_journal_entry");    
		
		$sql = "";
		$sql = sprintf("select je.journalEntryID,je.companyID,je.journalNumber,je.entryName,je.journalDate,je.tb_exchange_rate,je.createdOn,je.createdIn,je.createdAt,je.createdBy,je.isActive,je.isApplied,je.statusID,je.note,je.reference1,je.reference2,je.reference3,je.journalTypeID,je.currencyID,je.accountingCycleID,je.isModule,je.transactionMasterID");
		$sql = $sql.sprintf(" from tb_journal_entry je");		
		$sql = $sql.sprintf(" where je.companyID = $companyID");
		$sql = $sql.sprintf(" and je.journalEntryID <= $journalEntryID");
		$sql = $sql.sprintf(" and je.isActive= 1");				
		$sql = $sql.sprintf(" order by je.journalEntryID desc");		
		$sql = $sql.sprintf(" limit 0,1 ");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
  
}
?>