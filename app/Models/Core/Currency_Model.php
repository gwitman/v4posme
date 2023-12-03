<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Currency_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }  
   function get_rowName($name){
		$db 	= db_connect();    
		$sql = "";
		$sql = sprintf("select currencyID,simbol,name,description,isActive");
		$sql = $sql.sprintf(" from tb_currency");
		$sql = $sql.sprintf(" where name = '$name' ");		
		$sql = $sql.sprintf(" and isActive= 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByPK($currencyID){
		$db 	= db_connect();
	    $sql = "";
		$sql = sprintf("select currencyID,simbol,name,description,isActive");
		$sql = $sql.sprintf(" from tb_currency");
		$sql = $sql.sprintf(" where currencyID = $currencyID");		
		$sql = $sql.sprintf(" and isActive= 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function getList(){
		$db 	= db_connect();    
		$sql = "";
		$sql = sprintf("select currencyID,simbol,name,description,isActive");
		$sql = $sql.sprintf(" from tb_currency ");			
		$sql = $sql.sprintf(" where isActive= 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
}
?>