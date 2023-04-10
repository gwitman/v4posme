<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class List_Price_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function update_app_posme($companyID,$listPriceID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_list_price");
		
		$builder->where("companyID",$companyID);
		$builder->where("listPriceID",$listPriceID);	
		return $builder->update($data);
		
   }
   function delete_app_posme($companyID,$listPriceID){
		$db 	= db_connect();
		$builder	= $db->table("tb_list_price");		
  		$data["isActive"] = 0;
		
		$builder->where("companyID",$companyID);
		$builder->where("listPriceID",$listPriceID);	
		return $builder->update($data);
		
   } 
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_list_price");
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }   
   function get_rowByPK($companyID,$listPriceID){
		$db 	= db_connect();
		$builder	= $db->table("tb_list_price");    
		$sql = "";
		$sql = sprintf("select i.companyID,i.listPriceID,i.startOn,i.endOn,i.name,i.description,i.statusID,i.createdOn,i.createdIn,i.createdBy,i.createdAt,i.isActive,ws.name as statusName");
		$sql = $sql.sprintf(" from tb_list_price i");		
		$sql = $sql.sprintf(" inner join  tb_workflow_stage ws on i.statusID = ws.workflowStageID");	
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.listPriceID = $listPriceID");		
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function getListPriceToApply($companyID){
		$db 		= db_connect();
		$builder	= $db->table("tb_list_price");
	    
		$sql = "";
		$sql = $sql."
		select 
			companyID,listPriceID,startOn,endOn,name,description,statusID,createdOn,createdIn,createdBy,createdAt,isActive
		from 
			tb_list_price c 
		where
			current_date() between c.startOn and c.endOn and 
			c.isActive = 1 and 
			c.companyID = ".$companyID." 
		order by 
			c.listPriceID desc 
		limit 0,1 
		";
		
		
	
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
}
?>