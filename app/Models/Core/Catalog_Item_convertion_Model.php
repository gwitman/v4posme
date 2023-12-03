<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Catalog_Item_Convertion_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function get_default($companyID,$catalogID){
		$db 		= db_connect();
		$builder 	= $db->table('tb_catalog_item_convertion');
		
   		
		$builder->where('companyID', $companyID);
		$builder->where('catalogID', $catalogID);
		$builder->where('isActive', 1);
		$builder->where('ratio', 1);
		
		//Ejecutar Consulta		
		return $builder->get()->getRow();
   }  
   function get_rowByPK($companyID,$catalogID,$catalogItemIDSource,$catalogItemIDTarget){
		$db 	= db_connect();    
		$builder 	= $db->table('tb_catalog_item_convertion');
		
		
		$builder->where('companyID', $companyID);
		$builder->where('catalogItemID', $catalogItemIDSource);
		$builder->where('targetCatalogItemID', $catalogItemIDTarget);
		$builder->where('catalogID', $catalogID );
		$builder->where('isActive', 1);
		
		
		//Ejecutar Consulta
		return $builder->get()->getRow();
   }
   
  
   
}
?>