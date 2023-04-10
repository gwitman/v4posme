<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Company_Currency_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }    
   function delete_app_posme($companyID,$currencyID){
		$db 		= db_connect();
		$builder	= $db->table("tb_company_currency");
		
		$builder->where("companyID",$companyID);
		$builder->where("currencyID",$currencyID);	
		$builder->delete();
		
   }
   function update_app_posme($companyID,$currencyID,$data){
		$db 		= db_connect();
		$builder	= $db->table("tb_company_currency");
		
		$builder->where("companyID",$companyID);
		$builder->where("currencyID",$currencyID);	
		return $builder->update($data);		
		
   }
   function insert_app_posme($data){
		$db 					= db_connect();		
		$builder				= $db->table("tb_company_currency");
		$result 				= $builder->insert($data);
		return $result;
   }
   function get_rowByPK($companyID,$currencyID){
		$db 	= db_connect();
		
		$sql = "";
		$sql = sprintf("select cc.companyID,cc.currencyID,cc.simb");
		$sql = $sql.sprintf(" from tb_company_currency cc");
		$sql = $sql.sprintf(" inner join  tb_currency c on cc.currencyID = c.currencyID");
		$sql = $sql.sprintf(" where cc.currencyID = $currencyID");
		$sql = $sql.sprintf(" and cc.companyID = $companyID");			
		$sql = $sql.sprintf(" and c.isActive= 1");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function getByCompany($companyID){
		$db 	= db_connect();
		    
		$sql = "";
		$sql = sprintf("select cc.companyID,cc.currencyID,cc.simb,c.simbol,c.name");
		$sql = $sql.sprintf(" from tb_company_currency cc");
		$sql = $sql.sprintf(" inner join  tb_currency c on cc.currencyID = c.currencyID");
		$sql = $sql.sprintf(" where cc.companyID = $companyID");		
		$sql = $sql.sprintf(" and c.isActive= 1");		
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
  
}
?>