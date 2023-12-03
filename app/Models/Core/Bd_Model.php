<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;

class Bd_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }  
   
   function executeRender($query,$parameter){
		$db 				= db_connect(); 
		
		if($parameter === null)
		$queryResult 		= $db->query($query);		
		else 
		$queryResult 		= $db->query($query,$parameter);		
	
		$result 			= $queryResult->getResult("array"); 		
		return $result;
   }  
   
   function simpleQuery($query){
		$db 				= db_connect(); 		
		$result 			= $db->query($query);				
   }  
   
   function executeRenderMultipleNative($query,$parameter){
		$db 				= db_connect(); 
		
		//Rempalzar Parametros
		
		for($ip = 0; $ip < count($parameter); $ip++){
			$valueParameter = $parameter[$ip];
			$valueIndex 	= $ip;
			
			$search = '/'.preg_quote("?", '/').'/';
			$query =  preg_replace($search, "'".$valueParameter."'", $query, 1);
		}
		
		
		//Ejecutar query
		$k 					= 0;
		$array_result_sets 	= array();		
	
		if(mysqli_multi_query($db->connID,$query)){
			do{
				$result = mysqli_store_result($db->connID);
				if($result){
					$i = 0;
					while($row = $result->fetch_assoc()){
						$array_result_sets[$k][$i] = $row;
						$i++; 
					}
				}
				$k++;
			}while(mysqli_next_result($db->connID));
			
			return $array_result_sets;
		}
		$db_mysqli->close();
   }  
   
   
}
?>