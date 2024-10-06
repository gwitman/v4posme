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
   function fnMerge_updateRows_dbPosMe($data){
	    $db 		= db_connect(); 				
		$builder 	= $db->table('tb_company');
		
		$builder->upsertBatch($data);
   }
}
?>