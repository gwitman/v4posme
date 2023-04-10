<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;

class Exchangerate_Model extends Model  {
   function __construct(){
		
      parent::__construct();
   }
   function update_app_posme($companyID,$date,$currencyIDSource,$currencyIDTarget,$data){
		$db 	= db_connect();
		$builder = $db->table("tb_exchange_rate");
		
		$builder->where("companyID",$companyID);
		$builder->where("date",$date);
		$builder->where("currencyID",$currencyIDSource);
		$builder->where("targetCurrencyID",$currencyIDTarget);
		
		return $builder->update($data);		
		
   }   
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder 	= $db->table("tb_exchange_rate");
		$result		= $builder->insert($data);
		return $result;
   }
   function get_default($companyID){
		$db 	= db_connect();
   		$sql = "";
		$sql = sprintf("select currencyID,companyID,date,targetCurrencyID,ratio");
		$sql = $sql.sprintf(" from tb_exchange_rate");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and ratio= 1");
		$sql = $sql.sprintf(" limit 0,1 ");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }  
   function get_rowByPK($companyID,$date,$currencyIDSource,$currencyIDTarget){
		$db 	= db_connect();    
		$sql = "";
		$sql = sprintf("select currencyID,companyID,date,targetCurrencyID,ratio");
		$sql = $sql.sprintf(" from tb_exchange_rate");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and currencyID = $currencyIDSource");
		$sql = $sql.sprintf(" and targetCurrencyID = $currencyIDTarget");
		$sql = $sql.sprintf(" and date = '$date' ");
		$sql = $sql.sprintf(" limit 0,1 ");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function getByCompanyAndDate($companyID,$dateStartOn,$dateEndOn){
		$db 	= db_connect();    
		$sql = "
			select 
				er.date,
				er.value,
				er.ratio,
				c.name as nameSource,
				cc.simb as simbSource,				
				ct.name as nameTarget,
				cct.simb as simbTarget
			from
				tb_exchange_rate er 
				inner join  tb_company_currency cc on 
					er.companyID = cc.companyID and 
					er.currencyID = cc.currencyID 
				inner join tb_currency c on 
					cc.currencyID = c.currencyID
				inner join tb_company_currency cct on 
					er.companyID = cct.companyID and 
					er.targetCurrencyID = cct.currencyID 
				inner join tb_currency ct on 
					cct.currencyID = ct.currencyID 
			where
				er.companyID = $companyID and 
				er.date between '$dateStartOn' and '$dateEndOn'
			order by
				er.date,c.name
		";
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   
}
?>