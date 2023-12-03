<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Biblia_Model extends Model  {
   function __construct(){	
      parent::__construct();
   } 
 
   
   function get_rowByDay($companyID,$dia){
	    
		$db 		= db_connect();
		
		$sql = "";
		$sql = sprintf("select i.versiculoID,i.orden,i.dia,i.capitulo,i.libro,i.versiculo");
		$sql = $sql.sprintf(" from tb_biblia i");		
		$sql = $sql.sprintf(" where dia >= ".($dia - 3));
		$sql = $sql.sprintf(" and dia <= ".($dia -1));
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
  
}
?>