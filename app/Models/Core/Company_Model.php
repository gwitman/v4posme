<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Company_Model extends Model  {
   function __construct(){
		
      parent::__construct();
   }  
   function get_rowByPK($companyID){
		$db 	= db_connect();    
		$sql = "";
		$sql = sprintf("select companyID,name,createdOn,address,flavorID,type,abreviature,namePublic");
		$sql = $sql.sprintf(" from tb_company");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rows(){
		$db 	= db_connect();    
		$sql = "";
		$sql = sprintf("select companyID,name,createdOn,address,flavorID,type, abreviature,namePublic");
		$sql = $sql.sprintf(" from tb_company");
		$sql = $sql.sprintf(" where isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function fnMerge_getRows_dbPosMeMerge_rowByCompanyID($companyID)
   {
	    $db 		= db_connect("merge"); 				
		
		$sql = "";
		$sql = sprintf("select companyID, name, address, createdOn, isActive, flavorID,type ,abreviature,namePublic");		
		$sql = $sql.sprintf(" from tb_company c ");		
		
		
		//Ejecutar Consulta
		$recordSet = $db->query($sql);
		$recordSet = $recordSet->getResult();

		//Resultado
		return $recordSet;
   }
   /**
    * Actualiza solo los datos generales editables de la compania (tab Compania de core_user/edit).
    * Nunca toca type, flavorID, namePublic ni isActive.
    *
    * @param int   $companyID Compania en sesion.
    * @param array $data      name, address, abreviature.
    * @return bool
    */
   function update_generalData_app_posme($companyID,$data){
		$db 		= db_connect();
		$builder 	= $db->table('tb_company');
		$allowed	= array_intersect_key($data,array_flip(array("name","address","abreviature")));

		$builder->where("companyID",intval($companyID));
		$builder->where("isActive",1);
		return $builder->update($allowed);
   }
   function fnMerge_updateRows_dbPosMe($data){
	    $db 		= db_connect(); 				
		$builder 	= $db->table('tb_company');
		
		$builder->upsertBatch($data);
   }
}
?>