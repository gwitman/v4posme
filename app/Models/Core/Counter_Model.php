<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Counter_Model extends Model  {
   function __construct(){
		
      parent::__construct();
   }    
   function get_rowByPK($companyID,$branchID,$componentID,$componentItemID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select e.companyID,e.branchID,e.componentID,e.componentItemID,e.initialValue,e.currentValue,e.seed,e.serie,e.length");
		$sql = $sql.sprintf(" from tb_counter e");
		$sql = $sql.sprintf(" where e.companyID = $companyID");	
		$sql = $sql.sprintf(" and e.branchID = $branchID");	
		$sql = $sql.sprintf(" and e.componentID = $componentID");	
		$sql = $sql.sprintf(" and e.componentItemID = $componentItemID");	
		
		//Ejecutar Consulta
		 return $db->query($sql)->getRow();
		
   }
   function update_app_posme($companyID,$branchID,$componentID,$componentItemID,$data){
		$db 		= db_connect();
		
		$builder 	= $db->table("tb_counter");
		$builder->where("companyID",$companyID );
		$builder->where("branchID",$branchID );
		$builder->where("componentID",$componentID );
		$builder->where("componentItemID",$componentItemID );
		
		return $builder->update($data); 
   }
}
?>