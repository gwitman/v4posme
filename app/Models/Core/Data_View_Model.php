<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Data_View_Model extends Model  {
   function __construct(){
		
      parent::__construct();  
   }  
   function getListBy_CompanyComponentCaller($componentID,$callerID){
		$db 	= db_connect();  
		 
		$sql = "";
		$sql = sprintf("select componentID,callerID,dataViewID");
		$sql = $sql.sprintf(" from tb_dataview"); 		
		$sql = $sql.sprintf(" where componentID = $componentID");
		$sql = $sql.sprintf(" and callerID = $callerID");		
		$sql = $sql.sprintf(" and isActive= 1"); 
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
		
   }
   function getViewByName($componentID,$name,$callerID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select componentID,callerID,dataViewID");
		$sql = $sql.sprintf(" from tb_dataview"); 		
		$sql = $sql.sprintf(" where componentID = $componentID");
		$sql = $sql.sprintf(" and callerID = $callerID");		
		$sql = $sql.sprintf(" and name = '$name' ");		
		$sql = $sql.sprintf(" and isActive= 1"); 
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
}
?>