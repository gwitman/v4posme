<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Company_Component_Relation_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_company_component_relation");
		$result		= $builder->insert($data);
		return $result;
   }
   function update_app_posme($companyComponentRelationID,$data){
		$db 		= db_connect();
		$builder	= $db->table("tb_company_component_relation");
		
		$builder->where("companyComponentRelationID", $companyComponentRelationID);
		return $builder->update($data);
		
   }
   function get_rowByPK($companyComponentRelationID){
		$db 	= db_connect();
		    
		$sql = "";
		$sql = sprintf("select 
						c.companyComponentRelationID,
						c.componentIDSource,
						c.componentItemIDSource,
						c.componentIDTarget,
						c.componentItemIDTarget,
						c.isActive,
						c.note,
						c.reference1,
						c.reference2,
						c.reference3  
						");
		$sql = $sql.sprintf(" from tb_company_component_relation c");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   
}
?>